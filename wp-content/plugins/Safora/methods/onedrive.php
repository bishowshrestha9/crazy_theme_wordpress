<?php
/**
 * Safora OneDrive, WebDAV, and Remote Send Drivers
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

/**
 * Microsoft OneDrive Driver
 */
class Safora_Backup_Module_OneDrive extends Safora_Backup_Module {
	public function get_id() { return 'onedrive'; }
	public function get_label() { return 'Microsoft OneDrive'; }

	public function backup($backup_files) {
		global $safora_core;
		$opts = $this->get_config();
		$token = isset($opts['access_token']) ? $opts['access_token'] : '';
		if (empty($token)) return new WP_Error('no_token', 'OneDrive not authorized');

		$backup_dir = $safora_core->backups_dir_location();
		foreach ($backup_files as $file) {
			Safora_Job_Scheduler::record_still_alive();
			if ($safora_core->is_uploaded($file)) continue;

			$local_path = $backup_dir . '/' . $file;
			if (!file_exists($local_path)) continue;

			$this->log("Uploading $file to OneDrive...");
			$url = 'https://graph.microsoft.com/v1.0/me/drive/root:/' . rawurlencode($file) . ':/content';
			$body = file_get_contents($local_path);

			$res = wp_remote_request($url, array(
				'method'  => 'PUT',
				'headers' => array(
					'Authorization' => 'Bearer ' . $token,
					'Content-Type'  => 'application/octet-stream',
				),
				'body'    => $body,
				'timeout' => 300,
			));

			if (!is_wp_error($res) && in_array(wp_remote_retrieve_response_code($res), array(200, 201))) {
				$safora_core->uploaded_file($file);
				$this->log("Successfully uploaded $file to OneDrive.");
			} else {
				$this->log("Failed OneDrive upload for $file", 'error');
			}
		}
		return true;
	}

	public function download($file) {
		global $safora_core;
		$opts = $this->get_config();
		$token = isset($opts['access_token']) ? $opts['access_token'] : '';
		if (empty($token)) return false;

		$url = 'https://graph.microsoft.com/v1.0/me/drive/root:/' . rawurlencode($file) . ':/content';
		$local_path = ($safora_core ? $safora_core->backups_dir_location() : WP_CONTENT_DIR . '/safora') . '/' . $file;

		$res = wp_remote_get($url, array(
			'headers' => array('Authorization' => 'Bearer ' . $token),
			'timeout' => 600,
		));

		if (!is_wp_error($res) && in_array(wp_remote_retrieve_response_code($res), array(200, 302))) {
			$body = wp_remote_retrieve_body($res);
			return (false !== file_put_contents($local_path, $body));
		}
		return false;
	}

	public function delete($files, $backup_history = array()) { return true; }
	public function listfiles() { return array(); }

	public function credentials_test() {
		$opts = $this->get_config();
		$token = isset($opts['access_token']) ? $opts['access_token'] : '';
		if (empty($token)) return array('result' => false, 'message' => 'Please provide OneDrive token.');
		$res = wp_remote_get('https://graph.microsoft.com/v1.0/me/drive', array(
			'headers' => array('Authorization' => 'Bearer ' . $token),
		));
		if (!is_wp_error($res) && 200 == wp_remote_retrieve_response_code($res)) {
			return array('result' => true, 'message' => 'OneDrive connection verified!');
		}
		return array('result' => false, 'message' => 'OneDrive token invalid.');
	}

	public function render_config_fields() {
		$opts = $this->get_config();
		$token = isset($opts['access_token']) ? esc_attr($opts['access_token']) : '';
		ob_start();
		?>
		<div class="safora-storage-subform" id="safora-storage-onedrive-form">
			<table class="form-table">
				<tr>
					<th><label for="safora_od_token">OneDrive Access Token</label></th>
					<td><input type="text" id="safora_od_token" name="safora_onedrive[access_token]" value="<?php echo $token; ?>" class="regular-text"></td>
				</tr>
			</table>
			<button type="button" class="button safora-test-storage-btn" data-service="onedrive">Test OneDrive Connection</button>
		</div>
		<?php
		return ob_get_clean();
	}
}
