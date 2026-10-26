<?php
/**
 * Safora SFTP (SSH) Storage Driver
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Backup_Module_SFTP extends Safora_Backup_Module {

	public function get_id() {
		return 'sftp';
	}

	public function get_label() {
		return 'SFTP (SSH File Transfer)';
	}

	private function connect() {
		if (!function_exists('ssh2_connect')) {
			$this->log('ssh2 PHP extension is not installed.', 'error');
			return false;
		}

		$opts = $this->get_config();
		$host = isset($opts['host']) ? trim($opts['host']) : '';
		$user = isset($opts['user']) ? trim($opts['user']) : '';
		$pass = isset($opts['pass']) ? $opts['pass'] : '';
		$port = !empty($opts['port']) ? (int) $opts['port'] : 22;

		if (empty($host) || empty($user)) return false;

		$conn = @ssh2_connect($host, $port);
		if (!$conn) return false;

		if (!@ssh2_auth_password($conn, $user, $pass)) {
			return false;
		}

		$sftp = @ssh2_sftp($conn);
		return $sftp ? array('conn' => $conn, 'sftp' => $sftp) : false;
	}

	public function backup($backup_files) {
		global $safora_core;
		$res = $this->connect();
		if (!$res) return new WP_Error('sftp_fail', 'Could not connect/auth to SFTP server.');

		$sftp = $res['sftp'];
		$opts = $this->get_config();
		$dir = isset($opts['dir']) ? trim($opts['dir'], '/') : '';
		$backup_dir = $safora_core->backups_dir_location();

		foreach ($backup_files as $file) {
			Safora_Job_Scheduler::record_still_alive();
			if ($safora_core->is_uploaded($file)) continue;

			$local_path = $backup_dir . '/' . $file;
			if (!file_exists($local_path)) continue;

			$remote_path = 'ssh2.sftp://' . intval($sftp) . '/' . ($dir ? $dir . '/' : '') . $file;
			$this->log("Uploading $file via SFTP...");

			$data = file_get_contents($local_path);
			if (false !== file_put_contents($remote_path, $data)) {
				$safora_core->uploaded_file($file);
				$this->log("Successfully uploaded $file via SFTP.");
			} else {
				$this->log("Failed SFTP upload for $file", 'error');
			}
		}

		return true;
	}

	public function download($file) {
		global $safora_core;
		$res = $this->connect();
		if (!$res) return false;

		$sftp = $res['sftp'];
		$opts = $this->get_config();
		$dir = isset($opts['dir']) ? trim($opts['dir'], '/') : '';
		$local_path = ($safora_core ? $safora_core->backups_dir_location() : WP_CONTENT_DIR . '/safora') . '/' . $file;
		$remote_path = 'ssh2.sftp://' . intval($sftp) . '/' . ($dir ? $dir . '/' : '') . $file;

		$data = @file_get_contents($remote_path);
		if (false !== $data) {
			return (false !== file_put_contents($local_path, $data));
		}
		return false;
	}

	public function delete($files, $backup_history = array()) {
		$res = $this->connect();
		if (!$res) return false;

		$sftp = $res['sftp'];
		$opts = $this->get_config();
		$dir = isset($opts['dir']) ? trim($opts['dir'], '/') : '';

		foreach ((array) $files as $file) {
			$remote_path = 'ssh2.sftp://' . intval($sftp) . '/' . ($dir ? $dir . '/' : '') . $file;
			@unlink($remote_path);
		}
		return true;
	}

	public function listfiles() {
		return array();
	}

	public function credentials_test() {
		$res = $this->connect();
		if ($res) {
			return array('result' => true, 'message' => 'SFTP connection and authentication succeeded!');
		}
		return array('result' => false, 'message' => 'SFTP connection failed. Check host, port, username, and password.');
	}

	public function render_config_fields() {
		$opts = $this->get_config();
		$host = isset($opts['host']) ? esc_attr($opts['host']) : '';
		$user = isset($opts['user']) ? esc_attr($opts['user']) : '';
		$pass = isset($opts['pass']) ? esc_attr($opts['pass']) : '';
		$dir  = isset($opts['dir']) ? esc_attr($opts['dir']) : '';
		$port = isset($opts['port']) ? esc_attr($opts['port']) : '22';

		ob_start();
		?>
		<div class="safora-storage-subform" id="safora-storage-sftp-form">
			<table class="form-table">
				<tr>
					<th><label for="safora_sftp_host">SFTP Host / Server</label></th>
					<td><input type="text" id="safora_sftp_host" name="safora_sftp[host]" value="<?php echo $host; ?>" class="regular-text" placeholder="ssh.example.com"></td>
				</tr>
				<tr>
					<th><label for="safora_sftp_user">SFTP Username</label></th>
					<td><input type="text" id="safora_sftp_user" name="safora_sftp[user]" value="<?php echo $user; ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="safora_sftp_pass">SFTP Password</label></th>
					<td><input type="password" id="safora_sftp_pass" name="safora_sftp[pass]" value="<?php echo $pass; ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="safora_sftp_dir">Remote Directory</label></th>
					<td><input type="text" id="safora_sftp_dir" name="safora_sftp[dir]" value="<?php echo $dir; ?>" class="regular-text" placeholder="/home/user/backups"></td>
				</tr>
				<tr>
					<th><label for="safora_sftp_port">SSH Port</label></th>
					<td><input type="number" id="safora_sftp_port" name="safora_sftp[port]" value="<?php echo $port; ?>" class="small-text"></td>
				</tr>
			</table>
			<button type="button" class="button safora-test-storage-btn" data-service="sftp">Test SFTP Connection</button>
		</div>
		<?php
		return ob_get_clean();
	}
}
