<?php
/**
 * Safora WebDAV Storage Driver (Nextcloud, ownCloud, cPanel WebDAV, etc.)
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Backup_Module_WebDAV extends Safora_Backup_Module {
	public function get_id() {
		return 'webdav';
	}

	public function get_label() {
		return 'WebDAV (Nextcloud, ownCloud, etc.)';
	}

	public function backup($backup_files) {
		global $safora_core;
		$opts = $this->get_config();
		$url = isset($opts['url']) ? rtrim($opts['url'], '/') : '';
		$user = isset($opts['user']) ? $opts['user'] : '';
		$pass = isset($opts['pass']) ? $opts['pass'] : '';

		if (empty($url)) return new WP_Error('no_url', 'WebDAV URL missing');

		$backup_dir = $safora_core ? $safora_core->backups_dir_location() : WP_CONTENT_DIR . '/safora';
		foreach ($backup_files as $file) {
			Safora_Job_Scheduler::record_still_alive();
			if ($safora_core && $safora_core->is_uploaded($file)) continue;

			$local_path = $backup_dir . '/' . $file;
			if (!file_exists($local_path)) continue;

			$this->log("Uploading $file to WebDAV ($url/$file)...");
			$body = file_get_contents($local_path);

			$headers = array('Content-Type' => 'application/octet-stream');
			if (!empty($user)) {
				$headers['Authorization'] = 'Basic ' . base64_encode($user . ':' . $pass);
			}

			$res = wp_remote_request($url . '/' . $file, array(
				'method'  => 'PUT',
				'headers' => $headers,
				'body'    => $body,
				'timeout' => 300,
			));

			if (!is_wp_error($res) && in_array(wp_remote_retrieve_response_code($res), array(200, 201, 204))) {
				if ($safora_core) $safora_core->uploaded_file($file);
				$this->log("Successfully uploaded $file to WebDAV.");
			} else {
				$this->log("Failed WebDAV upload for $file", 'error');
			}
		}
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

	public function credentials_test() {
		$opts = $this->get_config();
		$url = isset($opts['url']) ? rtrim($opts['url'], '/') : '';
		$user = isset($opts['user']) ? $opts['user'] : '';
		$pass = isset($opts['pass']) ? $opts['pass'] : '';

		if (empty($url)) return array('result' => false, 'message' => 'Please enter WebDAV URL.');

		$headers = array();
		if (!empty($user)) {
			$headers['Authorization'] = 'Basic ' . base64_encode($user . ':' . $pass);
		}

		$res = wp_remote_request($url, array(
			'method'  => 'PROPFIND',
			'headers' => $headers,
		));

		if (!is_wp_error($res) && in_array(wp_remote_retrieve_response_code($res), array(200, 207))) {
			return array('result' => true, 'message' => 'WebDAV server responded successfully!');
		}

		return array('result' => false, 'message' => 'WebDAV test failed. Status: ' . (is_wp_error($res) ? $res->get_error_message() : wp_remote_retrieve_response_code($res)));
	}

	public function render_config_fields() {
		$opts = $this->get_config();
		$url = isset($opts['url']) ? esc_attr($opts['url']) : '';
		$user = isset($opts['user']) ? esc_attr($opts['user']) : '';
		$pass = isset($opts['pass']) ? esc_attr($opts['pass']) : '';

		ob_start();
		?>
		<div class="safora-storage-subform" id="safora-storage-webdav-form">
			<table class="form-table">
				<tr>
					<th><label for="safora_wd_url">WebDAV URL</label></th>
					<td><input type="text" id="safora_wd_url" name="safora_webdav[url]" value="<?php echo $url; ?>" class="regular-text" placeholder="https://example.com/remote.php/webdav/backups"></td>
				</tr>
				<tr>
					<th><label for="safora_wd_user">WebDAV Username</label></th>
					<td><input type="text" id="safora_wd_user" name="safora_webdav[user]" value="<?php echo $user; ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="safora_wd_pass">WebDAV Password</label></th>
					<td><input type="password" id="safora_wd_pass" name="safora_webdav[pass]" value="<?php echo $pass; ?>" class="regular-text"></td>
				</tr>
			</table>
			<button type="button" class="button safora-test-storage-btn" data-service="webdav">Test WebDAV Connection</button>
		</div>
		<?php
		return ob_get_clean();
	}
}
