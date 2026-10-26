<?php
/**
 * Safora Dropbox Storage Driver
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Backup_Module_Dropbox extends Safora_Backup_Module {

	public function get_id() {
		return 'dropbox';
	}

	public function get_label() {
		return 'Dropbox';
	}

	public function backup($backup_files) {
		global $safora_core;
		$opts = $this->get_config();
		$token = isset($opts['access_token']) ? trim($opts['access_token']) : '';

		if (empty($token)) {
			$this->log('Dropbox access token is missing.', 'error');
			return new WP_Error('no_token', 'Dropbox not authorized');
		}

		$backup_dir = $safora_core->backups_dir_location();
		$remote_folder = isset($opts['folder']) ? '/' . trim($opts['folder'], '/') : '';

		foreach ($backup_files as $file) {
			Safora_Job_Scheduler::record_still_alive();
			if ($safora_core->is_uploaded($file)) continue;

			$local_path = $backup_dir . '/' . $file;
			if (!file_exists($local_path)) continue;

			$dropbox_path = ($remote_folder ? $remote_folder : '') . '/' . $file;
			$this->log("Uploading $file to Dropbox path $dropbox_path...");

			$body = file_get_contents($local_path);
			$upload_args = json_encode(array(
				'path' => $dropbox_path,
				'mode' => 'overwrite',
				'autorename' => false,
				'mute' => false,
			));

			$res = wp_remote_post('https://content.dropboxapi.com/2/files/upload', array(
				'headers' => array(
					'Authorization'   => 'Bearer ' . $token,
					'Dropbox-API-Arg' => $upload_args,
					'Content-Type'    => 'application/octet-stream',
				),
				'body'    => $body,
				'timeout' => 300,
			));

			if (!is_wp_error($res) && 200 == wp_remote_retrieve_response_code($res)) {
				$safora_core->uploaded_file($file);
				$this->log("Successfully uploaded $file to Dropbox.");
			} else {
				$this->log("Failed uploading $file to Dropbox: " . (is_wp_error($res) ? $res->get_error_message() : wp_remote_retrieve_body($res)), 'error');
			}
		}

		return true;
	}

	public function download($file) {
		global $safora_core;
		$opts = $this->get_config();
		$token = isset($opts['access_token']) ? trim($opts['access_token']) : '';
		if (empty($token)) return false;

		$remote_folder = isset($opts['folder']) ? '/' . trim($opts['folder'], '/') : '';
		$dropbox_path = ($remote_folder ? $remote_folder : '') . '/' . $file;
		$local_path = ($safora_core ? $safora_core->backups_dir_location() : WP_CONTENT_DIR . '/safora') . '/' . $file;

		$res = wp_remote_post('https://content.dropboxapi.com/2/files/download', array(
			'headers' => array(
				'Authorization'   => 'Bearer ' . $token,
				'Dropbox-API-Arg' => json_encode(array('path' => $dropbox_path)),
			),
			'timeout' => 600,
		));

		if (!is_wp_error($res) && 200 == wp_remote_retrieve_response_code($res)) {
			$body = wp_remote_retrieve_body($res);
			return (false !== file_put_contents($local_path, $body));
		}
		return false;
	}

	public function delete($files, $backup_history = array()) {
		$opts = $this->get_config();
		$token = isset($opts['access_token']) ? trim($opts['access_token']) : '';
		if (empty($token)) return false;

		$remote_folder = isset($opts['folder']) ? '/' . trim($opts['folder'], '/') : '';

		foreach ((array) $files as $file) {
			$dropbox_path = ($remote_folder ? $remote_folder : '') . '/' . $file;
			wp_remote_post('https://api.dropboxapi.com/2/files/delete_v2', array(
				'headers' => array(
					'Authorization' => 'Bearer ' . $token,
					'Content-Type'  => 'application/json',
				),
				'body' => json_encode(array('path' => $dropbox_path)),
				'timeout' => 30,
			));
		}
		return true;
	}

	public function listfiles() {
		return array();
	}

	public function credentials_test() {
		$opts = $this->get_config();
		$token = isset($opts['access_token']) ? trim($opts['access_token']) : '';
		if (empty($token)) {
			return array('result' => false, 'message' => 'Please enter Dropbox Access Token.');
		}

		$res = wp_remote_post('https://api.dropboxapi.com/2/users/get_current_account', array(
			'headers' => array('Authorization' => 'Bearer ' . $token),
		));

		if (!is_wp_error($res) && 200 == wp_remote_retrieve_response_code($res)) {
			return array('result' => true, 'message' => 'Dropbox connection verified successfully!');
		}

		return array('result' => false, 'message' => 'Dropbox token invalid or revoked.');
	}

	public function render_config_fields() {
		$opts = $this->get_config();
		$token = isset($opts['access_token']) ? esc_attr($opts['access_token']) : '';
		$folder = isset($opts['folder']) ? esc_attr($opts['folder']) : '';

		ob_start();
		?>
		<div class="safora-storage-subform" id="safora-storage-dropbox-form">
			<table class="form-table">
				<tr>
					<th><label for="safora_dbx_token">Dropbox Access Token</label></th>
					<td><input type="text" id="safora_dbx_token" name="safora_dropbox[access_token]" value="<?php echo $token; ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="safora_dbx_folder">Dropbox Subfolder (Optional)</label></th>
					<td><input type="text" id="safora_dbx_folder" name="safora_dropbox[folder]" value="<?php echo $folder; ?>" class="regular-text" placeholder="safora-backups"></td>
				</tr>
			</table>
			<button type="button" class="button safora-test-storage-btn" data-service="dropbox">Test Dropbox Connection</button>
		</div>
		<?php
		return ob_get_clean();
	}
}
