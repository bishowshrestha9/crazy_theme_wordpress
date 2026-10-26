<?php
/**
 * Safora Remote Send Driver (Direct Site-to-Site Migration Engine)
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Backup_Module_RemoteSend extends Safora_Backup_Module {

	const DEFAULT_CHUNK_SIZE = 2097152; // 2 MB per chunk
	const MIN_CHUNK_SIZE     = 131072;  // 128 KB minimum fallback

	public function get_id() {
		return 'remotesend';
	}

	public function get_label() {
		return 'Send to another WordPress site';
	}

	/**
	 * Upload files to remote destination site using chunked streaming
	 *
	 * @param array $backup_files List of backup filenames
	 * @return bool|WP_Error
	 */
	public function backup($backup_files) {
		global $safora_core;

		$opts = $this->get_config();
		$target_url = isset($opts['target_url']) ? rtrim(trim($opts['target_url']), '/') : '';
		$key = isset($opts['key']) ? trim($opts['key']) : '';

		if (empty($target_url) || empty($key)) {
			return new WP_Error('no_target', 'Destination site URL or Migration Secret Key is missing.');
		}

		// Verify remote endpoint availability before starting file transmission
		$this->log("Connecting to destination site: $target_url...");
		$ping = $this->credentials_test();
		if (empty($ping['result'])) {
			$err_msg = isset($ping['message']) ? $ping['message'] : 'Could not authenticate with destination site.';
			$this->log("Pre-flight ping failed: $err_msg", 'error');
			return new WP_Error('auth_failed', $err_msg);
		}
		$this->log("Destination site verified successfully.");

		$backup_dir = $safora_core ? $safora_core->backups_dir_location() : WP_CONTENT_DIR . '/safora';
		$uploaded_files = array();

		foreach ($backup_files as $file) {
			Safora_Job_Scheduler::record_still_alive();

			if ($safora_core && $safora_core->is_uploaded($file)) {
				$uploaded_files[] = $file;
				continue;
			}

			$local_path = $backup_dir . '/' . $file;
			if (!file_exists($local_path)) {
				$this->log("Local archive file not found: $file", 'warning');
				continue;
			}

			$filesize = filesize($local_path);
			$this->log("Starting chunked transfer for $file (" . size_format($filesize) . ")...");

			$send_result = $this->send_file_in_chunks($local_path, $file, $target_url, $key);
			if (is_wp_error($send_result)) {
				$this->log("Failed to transfer $file: " . $send_result->get_error_message(), 'error');
				return $send_result;
			}

			if ($safora_core) {
				$safora_core->uploaded_file($file);
			}
			$uploaded_files[] = $file;
			$this->log("Successfully transferred $file to destination site.");
		}

		// Send upload_complete notification to trigger finalization and history rescan on receiver
		$this->log("Sending upload_complete notification to destination site...");
		$this->notify_upload_complete($target_url, $key, $uploaded_files);

		return true;
	}

	/**
	 * Send a single file to target URL in resilient chunks
	 *
	 * @param string $local_path Full local path to archive
	 * @param string $filename Basename of file
	 * @param string $target_url Destination WordPress site URL
	 * @param string $key Secret migration key
	 * @return bool|WP_Error
	 */
	private function send_file_in_chunks($local_path, $filename, $target_url, $key) {
		$filesize = filesize($local_path);
		$chunk_size = self::DEFAULT_CHUNK_SIZE;
		$file_md5 = md5_file($local_path);

		$endpoint = $target_url . '/wp-admin/admin-ajax.php?action=safora_receive_backup';

		$handle = @fopen($local_path, 'rb');
		if (!$handle) {
			return new WP_Error('file_open_failed', "Could not open $filename for reading.");
		}

		$offset = 0;
		$chunk_index = 0;

		while ($offset < $filesize) {
			Safora_Job_Scheduler::record_still_alive();
			@set_time_limit(300);

			$remaining = $filesize - $offset;
			$current_chunk_size = min($chunk_size, $remaining);
			$total_chunks = max(1, (int) ceil($filesize / $chunk_size));

			fseek($handle, $offset);
			$chunk_data = fread($handle, $current_chunk_size);
			if (false === $chunk_data) {
				fclose($handle);
				return new WP_Error('read_error', "Error reading chunk $chunk_index of $filename.");
			}

			$percent = $filesize > 0 ? round((($offset + strlen($chunk_data)) / $filesize) * 100, 1) : 100;
			$this->log("Sending $filename chunk " . ($chunk_index + 1) . " of $total_chunks ({$percent}%)...");

			$attempt = 0;
			$chunk_success = false;
			$last_error_message = '';

			while ($attempt < 3 && !$chunk_success) {
				$attempt++;

				$query_params = array(
					'action'     => 'safora_receive_backup',
					'key'        => $key,
					'file'       => $filename,
					'chunk'      => $chunk_index,
					'chunks'     => $total_chunks,
					'offset'     => $offset,
					'filesize'   => $filesize,
					'chunk_size' => strlen($chunk_data),
					'md5'        => $file_md5,
				);

				$req_url = add_query_arg($query_params, $endpoint);

				$response = wp_remote_post($req_url, array(
					'headers' => array(
						'X-Safora-Key'       => $key,
						'X-Safora-File'      => $filename,
						'X-Safora-Chunk'     => (string) $chunk_index,
						'X-Safora-Chunks'    => (string) $total_chunks,
						'X-Safora-Offset'    => (string) $offset,
						'X-Safora-Filesize'  => (string) $filesize,
						'X-Safora-MD5'       => $file_md5,
						'Content-Type'       => 'application/octet-stream',
					),
					'body'      => $chunk_data,
					'timeout'   => 180,
					'sslverify' => apply_filters('https_local_ssl_verify', false),
				));

				if (is_wp_error($response)) {
					$last_error_message = $response->get_error_message();
					$this->log("Attempt $attempt failed for chunk $chunk_index: $last_error_message", 'warning');
					sleep(1);
					continue;
				}

				$status_code = (int) wp_remote_retrieve_response_code($response);
				$body_str = wp_remote_retrieve_body($response);

				// Auto-throttle chunk size on HTTP 413 (Payload Too Large) or 504 / 502 gateway limits
				if (in_array($status_code, array(413, 502, 504)) && $chunk_size > self::MIN_CHUNK_SIZE) {
					$new_chunk_size = max(self::MIN_CHUNK_SIZE, (int) floor($chunk_size / 2));
					$this->log("HTTP $status_code received. Reducing chunk size from " . size_format($chunk_size) . " to " . size_format($new_chunk_size) . " and retrying...", 'warning');
					$chunk_size = $new_chunk_size;
					$current_chunk_size = min($chunk_size, $remaining);
					fseek($handle, $offset);
					$chunk_data = fread($handle, $current_chunk_size);
					continue;
				}

				if (200 === $status_code) {
					$json = json_decode($body_str, true);
					if (is_array($json) && !empty($json['success'])) {
						$chunk_success = true;
						break;
					} else {
						$last_error_message = isset($json['data']) ? (is_string($json['data']) ? $json['data'] : json_encode($json['data'])) : 'Receiver returned error status';
						$this->log("Receiver error on chunk $chunk_index: $last_error_message", 'warning');
					}
				} else {
					$last_error_message = "HTTP status $status_code: " . substr($body_str, 0, 300);
					$this->log("HTTP error on chunk $chunk_index: $last_error_message", 'warning');
				}

				sleep(2);
			}

			if (!$chunk_success) {
				fclose($handle);
				return new WP_Error('chunk_failed', "Failed transferring chunk $chunk_index of $filename after $attempt attempts. $last_error_message");
			}

			$offset += strlen($chunk_data);
			$chunk_index++;
		}

		fclose($handle);
		return true;
	}

	/**
	 * Send final completion handshake to remote site
	 *
	 * @param string $target_url Destination site URL
	 * @param string $key Migration key
	 * @param array $files Uploaded file list
	 * @return bool
	 */
	private function notify_upload_complete($target_url, $key, $files = array()) {
		$endpoint = $target_url . '/wp-admin/admin-ajax.php?action=safora_remote_upload_complete';
		$req_url = add_query_arg(array('key' => $key), $endpoint);

		$res = wp_remote_post($req_url, array(
			'headers'   => array(
				'X-Safora-Key' => $key,
				'Content-Type' => 'application/json',
			),
			'body'      => wp_json_encode(array('files' => $files)),
			'timeout'   => 60,
			'sslverify' => apply_filters('https_local_ssl_verify', false),
		));

		if (is_wp_error($res) || 200 !== (int) wp_remote_retrieve_response_code($res)) {
			$this->log("Notice: upload_complete handshake notification failed, but all files were transferred.", 'warning');
			return false;
		}

		$this->log("Destination site acknowledged receipt of all backup archives.");
		return true;
	}

	public function download($file) {
		return false;
	}

	public function delete($files, $backup_history = array()) {
		return true;
	}

	public function listfiles() {
		return array();
	}

	/**
	 * Test connection and authentication against remote destination
	 *
	 * @return array
	 */
	public function credentials_test() {
		$opts = $this->get_config();
		$target_url = isset($opts['target_url']) ? rtrim(trim($opts['target_url']), '/') : '';
		$key = isset($opts['key']) ? trim($opts['key']) : '';

		if (empty($target_url) || empty($key)) {
			return array('result' => false, 'message' => 'Please provide remote site URL and Key.');
		}

		$endpoint = $target_url . '/wp-admin/admin-ajax.php?action=safora_ping_remote';
		$req_url = add_query_arg(array('key' => $key), $endpoint);

		$res = wp_remote_post($req_url, array(
			'headers'   => array('X-Safora-Key' => $key),
			'body'      => array('safora_migration_key' => $key),
			'timeout'   => 20,
			'sslverify' => apply_filters('https_local_ssl_verify', false),
		));

		if (is_wp_error($res)) {
			return array('result' => false, 'message' => 'Connection error: ' . $res->get_error_message());
		}

		$code = (int) wp_remote_retrieve_response_code($res);
		$body = wp_remote_retrieve_body($res);
		$json = json_decode($body, true);

		if (200 === $code && is_array($json) && !empty($json['success'])) {
			return array('result' => true, 'message' => 'Remote site connection verified successfully!');
		}

		$msg = 'HTTP ' . $code;
		if (is_array($json) && isset($json['data'])) {
			$msg = is_string($json['data']) ? $json['data'] : json_encode($json['data']);
		} elseif (!empty($body)) {
			$msg .= ': ' . substr(strip_tags($body), 0, 150);
		}

		return array('result' => false, 'message' => 'Destination site returned: ' . $msg);
	}

	/**
	 * Render configuration fields in settings
	 */
	public function render_config_fields() {
		$opts = $this->get_config();
		$target_url = isset($opts['target_url']) ? esc_attr($opts['target_url']) : '';
		$key = isset($opts['key']) ? esc_attr($opts['key']) : '';

		ob_start();
		?>
		<div class="safora-storage-subform" id="safora-storage-remotesend-form">
			<table class="form-table">
				<tr>
					<th><label for="safora_rs_url">Destination Site URL</label></th>
					<td><input type="url" id="safora_rs_url" name="safora_remotesend[target_url]" value="<?php echo $target_url; ?>" class="regular-text" placeholder="https://destination-site.com"></td>
				</tr>
				<tr>
					<th><label for="safora_rs_key">Remote Migration Secret Key</label></th>
					<td><input type="password" id="safora_rs_key" name="safora_remotesend[key]" value="<?php echo $key; ?>" class="regular-text"></td>
				</tr>
			</table>
			<button type="button" class="button safora-test-storage-btn" data-service="remotesend">Test Remote Connection</button>
		</div>
		<?php
		return ob_get_clean();
	}
}
