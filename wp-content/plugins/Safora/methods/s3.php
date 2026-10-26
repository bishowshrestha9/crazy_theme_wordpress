<?php
/**
 * Safora Amazon S3 & S3-Compatible Storage Driver
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Backup_Module_S3 extends Safora_Backup_Module {

	public function get_id() {
		return 's3';
	}

	public function get_label() {
		return 'Amazon S3 / S3-Compatible';
	}

	/**
	 * Get S3 Client instance
	 *
	 * @return Safora_S3|false
	 */
	private function get_client() {
		$opts = $this->get_config();
		$key = isset($opts['apikey']) ? trim($opts['apikey']) : '';
		$secret = isset($opts['apisecret']) ? trim($opts['apisecret']) : '';
		$endpoint = isset($opts['endpoint']) && !empty($opts['endpoint']) ? trim($opts['endpoint']) : 's3.amazonaws.com';
		$region = isset($opts['region']) && !empty($opts['region']) ? trim($opts['region']) : 'us-east-1';
		$use_ssl = !isset($opts['nossl']) || !$opts['nossl'];

		if (empty($key) || empty($secret)) {
			$this->log('Cannot initialize S3: Access Key or Secret Key missing.', 'error');
			return false;
		}

		return new Safora_S3($key, $secret, $endpoint, $use_ssl, $region);
	}

	public function backup($backup_files) {
		global $safora_core;
		$s3 = $this->get_client();
		if (!$s3) return new WP_Error('no_credentials', 'S3 credentials not configured.');

		$opts = $this->get_config();
		$path_val = isset($opts['path']) ? trim($opts['path'], " \t\n\r\0\x0B/") : '';
		$parts = explode('/', $path_val, 2);
		$bucket = $parts[0];
		$prefix = isset($parts[1]) ? trailingslashit($parts[1]) : '';

		if (empty($bucket)) {
			return new WP_Error('no_bucket', 'S3 Bucket name is missing in configuration.');
		}

		$backup_dir = $safora_core->backups_dir_location();
		$chunk_size = 5 * 1024 * 1024; // 5 MB S3 multipart minimum

		foreach ($backup_files as $file) {
			Safora_Job_Scheduler::record_still_alive();

			if ($safora_core->is_uploaded($file)) {
				$this->log("File already uploaded to S3: $file");
				continue;
			}

			$local_path = $backup_dir . '/' . $file;
			if (!file_exists($local_path)) {
				$this->log("File not found locally for S3 upload: $file", 'error');
				continue;
			}

			$filesize = filesize($local_path);
			$remote_key = $prefix . $file;

			$this->log("Uploading $file (" . Safora_Manipulation_Functions::format_bytes($filesize) . ") to S3 bucket '$bucket' key '$remote_key'...");

			if ($filesize <= $chunk_size) {
				// Single part upload
				$res = $s3->putObjectFile($local_path, $bucket, $remote_key);
				if ($res['code'] >= 200 && $res['code'] < 300) {
					$safora_core->uploaded_file($file);
					$this->log("Successfully uploaded $file to S3.");
				} else {
					$this->log("Failed single-part upload for $file: " . $res['error'], 'error');
				}
			} else {
				// Resumable Multipart upload
				$upload_id_key = 's3_uploadid_' . md5($file);
				$upload_id = $this->jobdata_get($upload_id_key, false);

				if (!$upload_id) {
					$upload_id = $s3->initiateMultipartUpload($bucket, $remote_key);
					if (!$upload_id) {
						$this->log("Failed to initiate multipart upload for $file", 'error');
						continue;
					}
					$this->jobdata_set($upload_id_key, $upload_id);
					$this->log("Initiated S3 multipart upload ID: $upload_id");
				}

				$handle = fopen($local_path, 'rb');
				$part_number = 1;
				$parts_etags = array();
				$upload_failed = false;

				while (!feof($handle)) {
					Safora_Job_Scheduler::record_still_alive();

					$etag_key = "s3_etag_{$upload_id}_{$part_number}";
					$saved_etag = $this->jobdata_get($etag_key, false);

					$chunk_data = fread($handle, $chunk_size);
					if (false === $chunk_data || '' === $chunk_data) break;

					if ($saved_etag) {
						// Chunk already uploaded in previous run
						$parts_etags[$part_number] = $saved_etag;
					} else {
						$etag = $s3->uploadPart($bucket, $remote_key, $upload_id, $part_number, $chunk_data);
						if ($etag) {
							$this->jobdata_set($etag_key, $etag);
							$parts_etags[$part_number] = $etag;
							$this->log("Uploaded part #$part_number for $file (ETag: $etag)");
						} else {
							$this->log("Failed uploading part #$part_number for $file", 'error');
							$upload_failed = true;
							break;
						}
					}
					$part_number++;
				}
				fclose($handle);

				if (!$upload_failed && count($parts_etags) >= ($part_number - 1)) {
					$complete_res = $s3->completeMultipartUpload($bucket, $remote_key, $upload_id, $parts_etags);
					if ($complete_res['code'] >= 200 && $complete_res['code'] < 300) {
						$safora_core->uploaded_file($file);
						$this->log("Successfully completed multipart upload for $file to S3.");
					} else {
						$this->log("Failed completing multipart upload: " . $complete_res['error'], 'error');
					}
				}
			}
		}

		return true;
	}

	public function download($file) {
		global $safora_core;
		$s3 = $this->get_client();
		if (!$s3) return new WP_Error('no_credentials', 'S3 credentials not configured.');

		$opts = $this->get_config();
		$parts = explode('/', trim($opts['path'], '/'), 2);
		$bucket = $parts[0];
		$prefix = isset($parts[1]) ? trailingslashit($parts[1]) : '';

		$backup_dir = $safora_core->backups_dir_location();
		$local_path = $backup_dir . '/' . $file;
		$remote_key = $prefix . $file;

		$this->log("Downloading $file from S3 ($bucket/$remote_key)...");
		return $s3->getObjectFile($bucket, $remote_key, $local_path);
	}

	public function delete($files, $backup_history = array()) {
		$s3 = $this->get_client();
		if (!$s3) return false;

		$opts = $this->get_config();
		$parts = explode('/', trim($opts['path'], '/'), 2);
		$bucket = $parts[0];
		$prefix = isset($parts[1]) ? trailingslashit($parts[1]) : '';

		foreach ($files as $file) {
			$remote_key = $prefix . $file;
			$s3->deleteObject($bucket, $remote_key);
			$this->log("Deleted remote S3 file: $remote_key");
		}
		return true;
	}

	public function listfiles() {
		$s3 = $this->get_client();
		if (!$s3) return array();

		$opts = $this->get_config();
		$parts = explode('/', trim($opts['path'], '/'), 2);
		$bucket = $parts[0];
		$prefix = isset($parts[1]) ? trailingslashit($parts[1]) : '';

		return $s3->listObjects($bucket, $prefix);
	}

	public function credentials_test() {
		$s3 = $this->get_client();
		if (!$s3) {
			return array('result' => false, 'message' => 'Please provide S3 Access Key and Secret Key.');
		}

		$opts = $this->get_config();
		$parts = explode('/', trim($opts['path'], '/'), 2);
		$bucket = $parts[0];

		if (empty($bucket)) {
			return array('result' => false, 'message' => 'Please specify an S3 Bucket name.');
		}

		$res = $s3->request('GET', $bucket, '', array('max-keys' => 1));
		if ($res['code'] >= 200 && $res['code'] < 300) {
			return array('result' => true, 'message' => 'Success! Connection to S3 bucket verified.');
		}

		$error_msg = !empty($res['error']) ? (string) $res['error'] : Safora_Http_Error_Descriptions::get_description($res['code']);
		return array('result' => false, 'message' => "S3 Connection Failed (HTTP {$res['code']}): " . strip_tags((string) $error_msg));
	}

	public function render_config_fields() {
		$opts = $this->get_config();
		$key = isset($opts['apikey']) ? esc_attr($opts['apikey']) : '';
		$secret = isset($opts['apisecret']) ? esc_attr($opts['apisecret']) : '';
		$path = isset($opts['path']) ? esc_attr($opts['path']) : '';
		$endpoint = isset($opts['endpoint']) ? esc_attr($opts['endpoint']) : '';
		$region = isset($opts['region']) ? esc_attr($opts['region']) : 'us-east-1';

		ob_start();
		?>
		<div class="safora-storage-subform" id="safora-storage-s3-form">
			<table class="form-table">
				<tr>
					<th><label for="safora_s3_apikey">S3 Access Key</label></th>
					<td><input type="text" id="safora_s3_apikey" name="safora_s3[apikey]" value="<?php echo $key; ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="safora_s3_apisecret">S3 Secret Key</label></th>
					<td><input type="password" id="safora_s3_apisecret" name="safora_s3[apisecret]" value="<?php echo $secret; ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="safora_s3_path">S3 Bucket / Path</label></th>
					<td>
						<input type="text" id="safora_s3_path" name="safora_s3[path]" value="<?php echo $path; ?>" class="regular-text" placeholder="my-bucket/backups">
						<p class="description">Format: <code>bucket-name</code> or <code>bucket-name/folder</code></p>
					</td>
				</tr>
				<tr>
					<th><label for="safora_s3_endpoint">Custom S3 Endpoint (Optional)</label></th>
					<td>
						<input type="text" id="safora_s3_endpoint" name="safora_s3[endpoint]" value="<?php echo $endpoint; ?>" class="regular-text" placeholder="s3.amazonaws.com">
						<p class="description">Leave blank for AWS S3. For Wasabi: <code>s3.wasabisys.com</code>, DigitalOcean: <code>nyc3.digitaloceanspaces.com</code>, MinIO: <code>minio.example.com:9000</code></p>
					</td>
				</tr>
				<tr>
					<th><label for="safora_s3_region">S3 Region</label></th>
					<td><input type="text" id="safora_s3_region" name="safora_s3[region]" value="<?php echo $region; ?>" class="regular-text"></td>
				</tr>
			</table>
			<button type="button" class="button safora-test-storage-btn" data-service="s3">Test S3 Connection</button>
		</div>
		<?php
		return ob_get_clean();
	}
}
