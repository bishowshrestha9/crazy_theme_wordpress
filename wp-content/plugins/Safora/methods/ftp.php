<?php
/**
 * Safora FTP Storage Driver
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Backup_Module_FTP extends Safora_Backup_Module {

	public function get_id() {
		return 'ftp';
	}

	public function get_label() {
		return 'FTP / FTPS';
	}

	private function connect() {
		if (!function_exists('ftp_connect')) {
			$this->log('FTP extension is not enabled in PHP.', 'error');
			return false;
		}

		$opts = $this->get_config();
		$host = isset($opts['host']) ? trim($opts['host']) : '';
		$user = isset($opts['user']) ? trim($opts['user']) : '';
		$pass = isset($opts['pass']) ? $opts['pass'] : '';
		$port = !empty($opts['port']) ? (int) $opts['port'] : 21;
		$ssl  = !empty($opts['ssl']);
		$passive = !isset($opts['passive']) || !empty($opts['passive']);

		if (empty($host) || empty($user)) return false;

		$conn = $ssl && function_exists('ftp_ssl_connect') ? @ftp_ssl_connect($host, $port, 30) : @ftp_connect($host, $port, 30);
		if (!$conn) return false;

		if (!@ftp_login($conn, $user, $pass)) {
			@ftp_close($conn);
			return false;
		}

		if ($passive) {
			@ftp_pasv($conn, true);
		}

		return $conn;
	}

	public function backup($backup_files) {
		global $safora_core;
		$conn = $this->connect();
		if (!$conn) return new WP_Error('ftp_fail', 'Could not connect or log in to FTP server.');

		$opts = $this->get_config();
		$remote_dir = isset($opts['dir']) ? trim($opts['dir']) : '';
		if (!empty($remote_dir)) {
			@ftp_mkdir($conn, $remote_dir);
			@ftp_chdir($conn, $remote_dir);
		}

		$backup_dir = $safora_core->backups_dir_location();

		foreach ($backup_files as $file) {
			Safora_Job_Scheduler::record_still_alive();
			if ($safora_core->is_uploaded($file)) continue;

			$local_path = $backup_dir . '/' . $file;
			if (!file_exists($local_path)) continue;

			$this->log("Uploading $file via FTP...");
			if (ftp_put($conn, $file, $local_path, FTP_BINARY)) {
				$safora_core->uploaded_file($file);
				$this->log("Successfully uploaded $file via FTP.");
			} else {
				$this->log("Failed FTP upload for $file", 'error');
			}
		}

		@ftp_close($conn);
		return true;
	}

	public function download($file) {
		global $safora_core;
		$conn = $this->connect();
		if (!$conn) return false;

		$opts = $this->get_config();
		$remote_dir = isset($opts['dir']) ? trim($opts['dir']) : '';
		if (!empty($remote_dir)) @ftp_chdir($conn, $remote_dir);

		$local_path = $safora_core->backups_dir_location() . '/' . $file;
		$res = @ftp_get($conn, $local_path, $file, FTP_BINARY);
		@ftp_close($conn);
		return $res;
	}

	public function delete($files, $backup_history = array()) {
		$conn = $this->connect();
		if (!$conn) return false;

		$opts = $this->get_config();
		$remote_dir = isset($opts['dir']) ? trim($opts['dir']) : '';
		if (!empty($remote_dir)) @ftp_chdir($conn, $remote_dir);

		foreach ($files as $file) {
			@ftp_delete($conn, $file);
		}
		@ftp_close($conn);
		return true;
	}

	public function listfiles() {
		$conn = $this->connect();
		if (!$conn) return array();

		$opts = $this->get_config();
		$remote_dir = isset($opts['dir']) ? trim($opts['dir']) : '';
		if (!empty($remote_dir)) @ftp_chdir($conn, $remote_dir);

		$list = (array) @ftp_nlist($conn, '.');
		@ftp_close($conn);
		return $list;
	}

	public function credentials_test() {
		$conn = $this->connect();
		if ($conn) {
			@ftp_close($conn);
			return array('result' => true, 'message' => 'FTP connection and login succeeded!');
		}
		return array('result' => false, 'message' => 'Failed to connect or log in to FTP server.');
	}

	public function render_config_fields() {
		$opts = $this->get_config();
		$host = isset($opts['host']) ? esc_attr($opts['host']) : '';
		$user = isset($opts['user']) ? esc_attr($opts['user']) : '';
		$pass = isset($opts['pass']) ? esc_attr($opts['pass']) : '';
		$dir  = isset($opts['dir']) ? esc_attr($opts['dir']) : '';
		$port = isset($opts['port']) ? esc_attr($opts['port']) : '21';
		$ssl  = !empty($opts['ssl']);

		ob_start();
		?>
		<div class="safora-storage-subform" id="safora-storage-ftp-form">
			<table class="form-table">
				<tr>
					<th><label for="safora_ftp_host">FTP Host / Server</label></th>
					<td><input type="text" id="safora_ftp_host" name="safora_ftp[host]" value="<?php echo $host; ?>" class="regular-text" placeholder="ftp.example.com"></td>
				</tr>
				<tr>
					<th><label for="safora_ftp_user">FTP Username</label></th>
					<td><input type="text" id="safora_ftp_user" name="safora_ftp[user]" value="<?php echo $user; ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="safora_ftp_pass">FTP Password</label></th>
					<td><input type="password" id="safora_ftp_pass" name="safora_ftp[pass]" value="<?php echo $pass; ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th><label for="safora_ftp_dir">Remote Directory</label></th>
					<td><input type="text" id="safora_ftp_dir" name="safora_ftp[dir]" value="<?php echo $dir; ?>" class="regular-text" placeholder="/backups"></td>
				</tr>
				<tr>
					<th><label for="safora_ftp_port">Port</label></th>
					<td><input type="number" id="safora_ftp_port" name="safora_ftp[port]" value="<?php echo $port; ?>" class="small-text"></td>
				</tr>
				<tr>
					<th>SSL / FTPS</th>
					<td><label><input type="checkbox" name="safora_ftp[ssl]" value="1" <?php checked($ssl, true); ?>> Use explicit SSL (FTPS)</label></td>
				</tr>
			</table>
			<button type="button" class="button safora-test-storage-btn" data-service="ftp">Test FTP Connection</button>
		</div>
		<?php
		return ob_get_clean();
	}
}
