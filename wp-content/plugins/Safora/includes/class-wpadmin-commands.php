<?php
/**
 * Safora Admin AJAX Command Handlers
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_WPAdmin_Commands {

	public static function init() {
		add_action('wp_ajax_safora_backup_now', array(__CLASS__, 'backup_now'));
		add_action('wp_ajax_safora_resume_job', array(__CLASS__, 'resume_job'));
		add_action('wp_ajax_nopriv_safora_resume_job', array(__CLASS__, 'resume_job'));
		add_action('wp_ajax_safora_poll_log', array(__CLASS__, 'poll_log'));
		add_action('wp_ajax_safora_abort_backup', array(__CLASS__, 'abort_backup'));
		add_action('wp_ajax_safora_test_storage', array(__CLASS__, 'test_storage'));
		add_action('wp_ajax_safora_delete_backup', array(__CLASS__, 'delete_backup'));
		add_action('wp_ajax_safora_restore_backup', array(__CLASS__, 'restore_backup'));
		add_action('wp_ajax_safora_poll_restore_log', array(__CLASS__, 'poll_restore_log'));
		add_action('wp_ajax_safora_download_file', array(__CLASS__, 'download_file'));
		add_action('wp_ajax_safora_rescan_backups', array(__CLASS__, 'rescan_backups'));
		add_action('wp_ajax_safora_push_migration', array(__CLASS__, 'push_migration'));

		// Site-to-site migration remote receiver endpoints (public with key verification)
		add_action('wp_ajax_safora_ping_remote', array(__CLASS__, 'ping_remote'));
		add_action('wp_ajax_nopriv_safora_ping_remote', array(__CLASS__, 'ping_remote'));
		add_action('wp_ajax_safora_receive_backup', array(__CLASS__, 'receive_backup'));
		add_action('wp_ajax_nopriv_safora_receive_backup', array(__CLASS__, 'receive_backup'));
		add_action('wp_ajax_safora_remote_upload_complete', array(__CLASS__, 'remote_upload_complete'));
		add_action('wp_ajax_nopriv_safora_remote_upload_complete', array(__CLASS__, 'remote_upload_complete'));

		// Advanced Tools AJAX endpoints
		add_action('wp_ajax_safora_site_info_action', array(__CLASS__, 'site_info_action'));
		add_action('wp_ajax_safora_phpinfo', array(__CLASS__, 'get_phpinfo'));
		add_action('wp_ajax_safora_get_disk_space', array(__CLASS__, 'get_disk_space'));
		add_action('wp_ajax_safora_get_db_tables', array(__CLASS__, 'get_db_tables'));
		add_action('wp_ajax_safora_get_cron_events', array(__CLASS__, 'get_cron_events'));
		add_action('wp_ajax_safora_run_search_replace', array(__CLASS__, 'run_search_replace'));
		add_action('wp_ajax_safora_export_settings', array(__CLASS__, 'export_settings'));
		add_action('wp_ajax_safora_import_settings', array(__CLASS__, 'import_settings'));
		add_action('wp_ajax_safora_wipe_settings', array(__CLASS__, 'wipe_settings'));
		add_action('wp_ajax_safora_save_lock_settings', array(__CLASS__, 'save_lock_settings'));
		add_action('wp_ajax_safora_googledrive_list_folders', array(__CLASS__, 'googledrive_list_folders'));
	}

	public static function backup_now() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		global $safora_core;
		$include_files = !empty($_POST['include_files']);
		$include_db = !empty($_POST['include_db']);
		$send_remote = isset($_POST['send_remote']) ? !empty($_POST['send_remote']) : true;

		$use_fastcgi = function_exists('fastcgi_finish_request');
		$nonce = $safora_core->boot_backup($include_files, $include_db, false, $send_remote);
		if (is_wp_error($nonce)) {
			wp_send_json_error($nonce->get_error_message());
		}

		if ($use_fastcgi) {
			wp_send_json_success(array('nonce' => $nonce));
			fastcgi_finish_request();
			$safora_core->backup_resume(0, $nonce);
			exit;
		}

		wp_send_json_success(array('nonce' => $nonce));
	}

	public static function resume_job() {
		global $safora_core;
		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
		$resumption = isset($_POST['resumption']) ? (int) $_POST['resumption'] : 0;

		if (empty($nonce) || !$safora_core) {
			wp_send_json_error('Missing nonce');
		}

		$job_time = $safora_core->jobdata_get('job_time');
		if (!$job_time) {
			wp_send_json_error('Invalid job');
		}

		if (function_exists('fastcgi_finish_request')) {
			wp_send_json_success('Resuming in background');
			fastcgi_finish_request();
		}

		@set_time_limit(900);
		$safora_core->backup_resume($resumption, $nonce);
		wp_die();
	}

	public static function poll_log() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		global $safora_core;
		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
		if (empty($nonce)) wp_send_json_error('No nonce provided');

		$log_file = $safora_core->backups_dir_location() . "/log.{$nonce}.txt";
		if (!file_exists($log_file)) {
			wp_send_json_success(array('log' => 'Waiting for backup process to initialize...', 'finished' => false));
		}

		$log_contents = file_get_contents($log_file);
		$finished = (false !== strpos($log_contents, 'Safora Backup Job Finished Successfully'));

		// Watchdog: If job is still running but heartbeat is stale, trigger resumption ping
		if (!$finished) {
			$last_hb = (int) $safora_core->jobdata_get('last_heartbeat', 0);
			if ($last_hb > 0 && (time() - $last_hb) > 15) {
				$safora_core->spawn_resumption(1, $nonce);
			}
		}

		wp_send_json_success(array(
			'log' => $log_contents,
			'finished' => $finished,
		));
	}

	public static function abort_backup() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		global $safora_core;
		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
		if (empty($nonce)) wp_send_json_error('Missing nonce');

		$abort_flag = $safora_core->backups_dir_location() . '/deleteflag-' . $nonce . '.txt';
		@file_put_contents($abort_flag, time());

		wp_send_json_success('Abort signal sent.');
	}

	public static function test_storage() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		global $safora_core;
		$service = isset($_POST['service']) ? sanitize_text_field($_POST['service']) : '';
		$modules = $safora_core->get_storage_modules();

		if (!isset($modules[$service])) {
			wp_send_json_error('Storage driver not found.');
		}

		$res = $modules[$service]->credentials_test();
		if (!empty($res['result'])) {
			wp_send_json_success($res['message']);
		} else {
			wp_send_json_error($res['message']);
		}
	}

	public static function delete_backup() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		global $safora_core;
		$ts = isset($_POST['timestamp']) ? (int) $_POST['timestamp'] : 0;
		if (!$ts) wp_send_json_error('Invalid timestamp');

		$history = Safora_Backup_History::always_get_from_db();
		if (!isset($history[$ts])) wp_send_json_error('Backup set not found');

		$b = $history[$ts];
		$backup_dir = $safora_core->backups_dir_location();

		// Delete local files
		if (!empty($b['db'])) @unlink($backup_dir . '/' . $b['db']);
		foreach (array('plugins', 'themes', 'uploads', 'others', 'wpcore') as $ent) {
			if (!empty($b[$ent])) {
				$files = is_array($b[$ent]) ? $b[$ent] : array($b[$ent]);
				foreach ($files as $fn) @unlink($backup_dir . '/' . $fn);
			}
		}

		Safora_Backup_History::delete_backup($ts);
		wp_send_json_success('Backup set deleted successfully.');
	}

	public static function restore_backup() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		$ts = isset($_POST['timestamp']) ? (int) $_POST['timestamp'] : 0;
		$entities = isset($_POST['entities']) ? (array) $_POST['entities'] : array();
		$passphrase = isset($_POST['passphrase']) ? sanitize_text_field($_POST['passphrase']) : '';

		$history = Safora_Backup_History::always_get_from_db();
		if (!isset($history[$ts])) wp_send_json_error('Backup set not found.');

		global $safora_core;
		$backup_dir = $safora_core ? $safora_core->backups_dir_location() : WP_CONTENT_DIR . '/safora';
		$log_file = $backup_dir . '/restore.log';
		if (file_exists($log_file)) {
			@unlink($log_file);
		}
		delete_transient('safora_restore_log');

		$restorer = new Safora_Restorer($history[$ts], array(
			'entities'   => $entities,
			'passphrase' => $passphrase,
		));

		$res = $restorer->perform_restore();
		if (is_wp_error($res)) {
			wp_send_json_error($res->get_error_message());
		}

		$log_str = file_exists($log_file) ? @file_get_contents($log_file) : 'Restoration successfully completed!';
		wp_send_json_success(array(
			'message'  => 'Restoration successfully completed!',
			'finished' => true,
			'log'      => $log_str,
		));
	}

	public static function poll_restore_log() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		global $safora_core;
		$backup_dir = $safora_core ? $safora_core->backups_dir_location() : WP_CONTENT_DIR . '/safora';
		$log_file = $backup_dir . '/restore.log';

		$log_str = '';
		if (file_exists($log_file)) {
			$log_str = @file_get_contents($log_file);
		}

		if (empty($log_str)) {
			$log = get_transient('safora_restore_log');
			$log_str = is_array($log) ? implode("\n", $log) : '';
		}

		$finished = (false !== strpos($log_str, 'Restoration successfully completed'));

		wp_send_json_success(array('log' => $log_str, 'finished' => $finished));
	}

	public static function download_file() {
		if (!isset($_GET['security']) || !wp_verify_nonce($_GET['security'], 'safora_admin_nonce')) {
			wp_die('Invalid security token.', 'Forbidden', array('response' => 403));
		}
		if (!current_user_can('manage_options')) {
			wp_die('Unauthorized.', 'Forbidden', array('response' => 403));
		}

		global $safora_core;
		$filename = isset($_GET['file']) ? sanitize_file_name($_GET['file']) : '';
		if (empty($filename)) {
			wp_die('No file specified.', 'Error', array('response' => 400));
		}

		$backup_dir = $safora_core ? $safora_core->backups_dir_location() : WP_CONTENT_DIR . '/safora';
		$filepath = $backup_dir . '/' . $filename;

		// Directory traversal guard
		$real_backup_dir = realpath($backup_dir);
		$real_filepath = realpath($filepath);

		if (!$real_filepath || 0 !== strpos($real_filepath, $real_backup_dir) || !file_exists($real_filepath)) {
			wp_die('File not found or access denied.', 'Not Found', array('response' => 404));
		}

		$fsize = filesize($real_filepath);
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="' . basename($real_filepath) . '"');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . $fsize);
		clean_buffers();
		readfile($real_filepath);
		exit;
	}

	public static function rescan_backups() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		$total = Safora_Backup_History::rescan_local_backups();
		wp_send_json_success(array('total' => $total, 'message' => "Rescan complete: found $total backup sets on disk."));
	}

	/**
	 * Extract migration secret key from various HTTP headers, POST, and GET variables
	 *
	 * @return string
	 */
	public static function get_incoming_migration_key() {
		$keys = array(
			isset($_SERVER['HTTP_X_SAFORA_KEY']) ? sanitize_text_field($_SERVER['HTTP_X_SAFORA_KEY']) : '',
			isset($_POST['safora_migration_key']) ? sanitize_text_field($_POST['safora_migration_key']) : '',
			isset($_POST['key']) ? sanitize_text_field($_POST['key']) : '',
			isset($_GET['key']) ? sanitize_text_field($_GET['key']) : '',
		);

		foreach ($keys as $k) {
			$k = trim($k);
			if (!empty($k)) return $k;
		}
		return '';
	}

	public static function push_migration() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		$target_url = isset($_POST['target_url']) ? esc_url_raw($_POST['target_url']) : '';
		$key = isset($_POST['key']) ? sanitize_text_field($_POST['key']) : '';

		if (empty($target_url) || empty($key)) {
			wp_send_json_error('Please specify both destination URL and Migration Secret Key.');
		}

		// Save migration credentials for the remotesend driver
		Safora_Options::update_option('remotesend', array(
			'target_url' => $target_url,
			'key'        => $key,
		));

		// Isolate this migration job to remotesend only, without polluting global scheduled backup services
		global $safora_core;
		$nonce = $safora_core->boot_backup(true, true, null, true, array('remotesend'));
		if (is_wp_error($nonce)) {
			wp_send_json_error($nonce->get_error_message());
		}

		wp_send_json_success(array('nonce' => $nonce));
	}

	public static function ping_remote() {
		$site_key = get_option('safora_migration_secret_key');
		$incoming_key = self::get_incoming_migration_key();

		if (empty($site_key) || empty($incoming_key) || !hash_equals($site_key, $incoming_key)) {
			wp_send_json_error('Unauthorized: invalid migration key');
		}

		wp_send_json_success('Safora remote migration endpoint active and authenticated.');
	}

	public static function receive_backup() {
		$site_key = get_option('safora_migration_secret_key');
		$incoming_key = self::get_incoming_migration_key();

		if (empty($site_key) || empty($incoming_key) || !hash_equals($site_key, $incoming_key)) {
			wp_send_json_error('Unauthorized: invalid migration key');
		}

		$filename = isset($_SERVER['HTTP_X_SAFORA_FILE']) ? sanitize_file_name($_SERVER['HTTP_X_SAFORA_FILE']) : '';
		if (empty($filename) && isset($_REQUEST['file'])) {
			$filename = sanitize_file_name($_REQUEST['file']);
		}
		if (empty($filename)) {
			wp_send_json_error('No filename provided');
		}

		$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
		$allowed_exts = array('zip', 'gz', 'crypt', 'db', 'tar');
		if (!in_array($ext, $allowed_exts, true)) {
			wp_send_json_error('Unauthorized file extension.');
		}

		$chunk_index = isset($_REQUEST['chunk']) ? (int) $_REQUEST['chunk'] : (isset($_SERVER['HTTP_X_SAFORA_CHUNK']) ? (int) $_SERVER['HTTP_X_SAFORA_CHUNK'] : 0);
		$total_chunks = isset($_REQUEST['chunks']) ? (int) $_REQUEST['chunks'] : (isset($_SERVER['HTTP_X_SAFORA_CHUNKS']) ? (int) $_SERVER['HTTP_X_SAFORA_CHUNKS'] : 1);
		$offset = isset($_REQUEST['offset']) ? (int) $_REQUEST['offset'] : (isset($_SERVER['HTTP_X_SAFORA_OFFSET']) ? (int) $_SERVER['HTTP_X_SAFORA_OFFSET'] : 0);
		$expected_md5 = isset($_REQUEST['md5']) ? sanitize_text_field($_REQUEST['md5']) : (isset($_SERVER['HTTP_X_SAFORA_MD5']) ? sanitize_text_field($_SERVER['HTTP_X_SAFORA_MD5']) : '');

		global $safora_core;
		$backup_dir = $safora_core ? $safora_core->backups_dir_location() : WP_CONTENT_DIR . '/safora';
		if (!is_dir($backup_dir)) {
			wp_mkdir_p($backup_dir);
		}

		$dest = $backup_dir . '/' . $filename;
		$tmp_dest = $dest . '.tmp';

		// Open temporary file for writing/appending chunks
		$mode = (0 === $offset || 0 === $chunk_index) ? 'wb' : 'c+b';
		$out = @fopen($tmp_dest, $mode);
		if (!$out) {
			wp_send_json_error("Could not write to temporary archive file: $tmp_dest");
		}

		if ($offset > 0) {
			fseek($out, $offset);
		}

		$input = fopen('php://input', 'rb');
		if (!$input) {
			fclose($out);
			wp_send_json_error('Could not open php://input for reading');
		}

		$bytes_written = 0;
		while (!feof($input)) {
			$chunk = fread($input, 1048576);
			if (false === $chunk || '' === $chunk) break;
			$written = fwrite($out, $chunk);
			if (false === $written) break;
			$bytes_written += $written;
		}

		fclose($input);
		fclose($out);

		// If this was the last chunk (or single-chunk upload), finalize archive file
		$is_last_chunk = ($chunk_index >= $total_chunks - 1);
		if ($is_last_chunk) {
			@rename($tmp_dest, $dest);

			if (!empty($expected_md5)) {
				$actual_md5 = md5_file($dest);
				if ($actual_md5 !== $expected_md5) {
					@unlink($dest);
					wp_send_json_error("Checksum mismatch: expected $expected_md5, got $actual_md5");
				}
			}

			// Rescan to register newly arrived archive
			Safora_Backup_History::rescan_local_backups();

			wp_send_json_success(array(
				'completed' => true,
				'file'      => $filename,
				'bytes'     => file_exists($dest) ? filesize($dest) : $bytes_written,
			));
		}

		wp_send_json_success(array(
			'completed'      => false,
			'chunk'          => $chunk_index,
			'total_chunks'   => $total_chunks,
			'bytes_received' => $bytes_written,
		));
	}

	public static function remote_upload_complete() {
		$site_key = get_option('safora_migration_secret_key');
		$incoming_key = self::get_incoming_migration_key();

		if (empty($site_key) || empty($incoming_key) || !hash_equals($site_key, $incoming_key)) {
			wp_send_json_error('Unauthorized: invalid migration key');
		}

		$total = Safora_Backup_History::rescan_local_backups();
		wp_send_json_success(array('total' => $total, 'message' => "Upload complete registered: $total backup sets available."));
	}

	/**
	 * Handle Site Info debugging utilities (HTTP Get, Call WP Action, Reset Site ID, Raw Backups)
	 */
	public static function site_info_action() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		$subaction = isset($_POST['subaction']) ? sanitize_key($_POST['subaction']) : '';

		if ('httpget' === $subaction) {
			$uri = isset($_POST['uri']) ? esc_url_raw($_POST['uri']) : '';
			$curl = !empty($_POST['curl']);
			if (empty($uri)) wp_send_json_error('No URL provided');

			$parsed_scheme = strtolower(wp_parse_url($uri, PHP_URL_SCHEME));
			if (!in_array($parsed_scheme, array('http', 'https'), true)) {
				wp_send_json_error('Invalid scheme. Only HTTP and HTTPS URLs are permitted.');
			}

			if ($curl && function_exists('curl_init')) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $uri);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_TIMEOUT, 15);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				$result = curl_exec($ch);
				$info = curl_getinfo($ch);
				$err = curl_error($ch);
				curl_close($ch);

				if ($err) {
					wp_send_json_success(array('output' => "cURL Error: " . $err));
				} else {
					$sample = substr($result, 0, 1000);
					wp_send_json_success(array('output' => "HTTP Code: " . $info['http_code'] . "\nResponse Length: " . strlen($result) . " bytes\n\n" . $sample));
				}
			} else {
				$response = wp_remote_get($uri, array('timeout' => 15, 'sslverify' => false));
				if (is_wp_error($response)) {
					wp_send_json_success(array('output' => "wp_remote_get Error: " . $response->get_error_message()));
				} else {
					$code = wp_remote_retrieve_response_code($response);
					$body = wp_remote_retrieve_body($response);
					$sample = substr($body, 0, 1000);
					wp_send_json_success(array('output' => "HTTP Code: " . $code . "\nResponse Length: " . strlen($body) . " bytes\n\n" . $sample));
				}
			}
		} elseif ('callwpaction' === $subaction) {
			$hook = isset($_POST['wpaction']) ? sanitize_text_field($_POST['wpaction']) : '';
			if (empty($hook)) wp_send_json_error('No action specified');

			ob_start();
			do_action($hook);
			$output = ob_get_clean();

			wp_send_json_success(array('output' => "Action '$hook' executed successfully.\nCaptured output:\n" . (empty($output) ? '(no output returned)' : $output)));
		} elseif ('reset_site_id' === $subaction) {
			$new_id = md5(get_bloginfo('url') . wp_salt() . uniqid(rand(), true));
			Safora_Options::update_option('site_id', $new_id);
			wp_send_json_success(array('site_id' => $new_id));
		} elseif ('raw_backups' === $subaction) {
			$history = Safora_Backup_History::always_get_from_db();
			wp_send_json_success(array('raw' => json_encode($history, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)));
		} else {
			wp_send_json_error('Unknown subaction');
		}
	}

	/**
	 * Output PHPInfo HTML for modal viewer
	 */
	public static function get_phpinfo() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		ob_start();
		phpinfo(INFO_ALL);
		$pinfo = ob_get_clean();

		// Strip everything outside <body> and strip unwanted styles
		if (preg_match('/<body[^>]*>(.*)<\/body>/is', $pinfo, $match)) {
			$pinfo = $match[1];
		}

		wp_send_json_success(array('html' => $pinfo));
	}

	/**
	 * Compute disk space for individual site entities
	 */
	public static function get_disk_space() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		$entity = isset($_POST['entity']) ? sanitize_key($_POST['entity']) : 'safora';
		$size = 0;

		if ('safora' === $entity || 'updraft' === $entity) {
			$custom_dir = Safora_Options::get_setting('backup_dir');
			$dir_name = !empty($custom_dir) ? trim($custom_dir, " \t\n\r\0\x0B/") : 'safora';
			$path = WP_CONTENT_DIR . '/' . $dir_name;
			if (file_exists($path)) {
				$size = Safora_Filesystem_Functions::get_disk_space_used($path);
			}
		} elseif ('plugins' === $entity) {
			$size = Safora_Filesystem_Functions::get_disk_space_used(WP_PLUGIN_DIR);
		} elseif ('themes' === $entity) {
			$size = Safora_Filesystem_Functions::get_disk_space_used(get_theme_root());
		} elseif ('uploads' === $entity) {
			$upload_dir = wp_upload_dir();
			$size = Safora_Filesystem_Functions::get_disk_space_used($upload_dir['basedir']);
		} elseif ('others' === $entity) {
			$size = Safora_Filesystem_Functions::get_disk_space_used(WP_CONTENT_DIR);
		} elseif ('wpcore' === $entity) {
			$size = Safora_Filesystem_Functions::get_disk_space_used(ABSPATH);
		}

		$formatted = round($size / 1048576, 1) . ' MB';
		wp_send_json_success(array('size' => $formatted, 'bytes' => $size));
	}

	/**
	 * Fetch database tables status and compute total DB size
	 */
	public static function get_db_tables() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		global $wpdb;
		$tables_status = $wpdb->get_results("SHOW TABLE STATUS", ARRAY_A);
		$total_size = 0;
		$tables = array();

		if (is_array($tables_status)) {
			foreach ($tables_status as $status) {
				$name = $status['Name'];
				$records = isset($status['Rows']) ? (int) $status['Rows'] : 0;
				$data_length = isset($status['Data_length']) ? (int) $status['Data_length'] : 0;
				$index_length = isset($status['Index_length']) ? (int) $status['Index_length'] : 0;
				$type = isset($status['Engine']) ? $status['Engine'] : 'InnoDB';
				$total_size += ($data_length + $index_length);

				$tables[] = array(
					'name'                   => $name,
					'records'                => number_format_i18n($records),
					'data_length'            => $data_length,
					'data_length_formatted'  => round($data_length / 1048576, 2) . ' MB',
					'index_length'           => $index_length,
					'index_length_formatted' => round($index_length / 1048576, 2) . ' MB',
					'type'                   => $type,
				);
			}
		}

		$total_size_formatted = round($total_size / 1048576, 1) . ' MB';
		wp_send_json_success(array('size' => $total_size_formatted, 'tables' => $tables));
	}

	/**
	 * Fetch registered WordPress Cron events
	 */
	public static function get_cron_events() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		$crons = _get_cron_array();
		$events = array();
		$now = time();

		if (is_array($crons)) {
			foreach ($crons as $timestamp => $cron_hooks) {
				foreach ($cron_hooks as $hook => $keys) {
					foreach ($keys as $key => $data) {
						$schedule = isset($data['schedule']) ? $data['schedule'] : false;
						$interval_text = 'Single event';
						if ($schedule) {
							$schedules = wp_get_schedules();
							$interval_text = isset($schedules[$schedule]['display']) ? $schedules[$schedule]['display'] : ucfirst($schedule);
						}
						$overdue = ($timestamp < $now);
						$time_diff = human_time_diff($timestamp, $now);
						$next_run = date_i18n('Y-m-d H:i:s', $timestamp) . ' (' . ($overdue ? $time_diff . ' ago' : 'in ' . $time_diff) . ')';

						$events[] = array(
							'hook'     => $hook,
							'name'     => $interval_text,
							'time'     => $next_run,
							'interval' => $interval_text,
							'overdue'  => $overdue,
						);
					}
				}
			}
		}

		wp_send_json_success($events);
	}

	/**
	 * Run recursive serialization-safe search and replace
	 */
	public static function run_search_replace() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		global $wpdb;
		$search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';
		$replace = isset($_POST['replace']) ? sanitize_text_field($_POST['replace']) : '';
		$dry_run = !empty($_POST['dry_run']);
		$prefix_only = !empty($_POST['prefix_only']);

		if (empty($search)) {
			wp_send_json_error('Please specify a search string.');
		}

		$all_tables = $wpdb->get_col("SHOW TABLES");
		$target_tables = array();
		foreach ($all_tables as $t) {
			if ($prefix_only && 0 !== strpos($t, $wpdb->prefix)) continue;
			$target_tables[] = $t;
		}

		$engine = new Safora_Search_Replace(array($search), array($replace));
		$report = $engine->run($target_tables);

		wp_send_json_success(array(
			'dry_run'      => $dry_run,
			'tables_count' => count($target_tables),
			'rows'         => $report['rows'],
			'changes'      => $report['change'],
			'updates'      => $report['updates'],
		));
	}

	/**
	 * Export plugin settings
	 */
	public static function export_settings() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		$defaults = Safora_Options::get_defaults();
		$export_data = array();
		foreach ($defaults as $k => $v) {
			$export_data[$k] = Safora_Options::get_setting($k);
		}

		$drivers = array('s3', 'googledrive', 'dropbox', 'ftp', 'sftp', 'email', 'onedrive', 'webdav', 'remotesend');
		foreach ($drivers as $d) {
			$export_data['driver_' . $d] = Safora_Options::get_option($d, array());
		}

		wp_send_json_success(array('settings' => $export_data, 'json' => json_encode($export_data, JSON_PRETTY_PRINT)));
	}

	/**
	 * Import plugin settings
	 */
	public static function import_settings() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		$json = isset($_POST['settings_json']) ? stripslashes($_POST['settings_json']) : '';
		$data = json_decode($json, true);

		if (!is_array($data)) {
			wp_send_json_error('Invalid settings JSON format.');
		}

		$defaults = Safora_Options::get_defaults();
		foreach ($defaults as $k => $v) {
			if (isset($data[$k])) {
				Safora_Options::update_option($k, $data[$k]);
			}
		}

		$drivers = array('s3', 'googledrive', 'dropbox', 'ftp', 'sftp', 'email', 'onedrive', 'webdav', 'remotesend');
		foreach ($drivers as $d) {
			if (isset($data['driver_' . $d])) {
				Safora_Options::update_option($d, $data['driver_' . $d]);
			}
		}

		wp_send_json_success('Settings imported successfully.');
	}

	/**
	 * Wipe all plugin settings
	 */
	public static function wipe_settings() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		$defaults = Safora_Options::get_defaults();
		foreach ($defaults as $k => $v) {
			Safora_Options::delete_option($k);
		}

		$drivers = array('s3', 'googledrive', 'dropbox', 'ftp', 'sftp', 'email', 'onedrive', 'webdav', 'remotesend', 'site_id', 'lock_enabled', 'lock_password');
		foreach ($drivers as $d) {
			Safora_Options::delete_option($d);
		}

		Safora_Semaphore::release_lock('backup');
		wp_send_json_success('All settings have been wiped successfully.');
	}

	/**
	 * Save admin lock settings
	 */
	public static function save_lock_settings() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		$enabled = !empty($_POST['enabled']) ? 1 : 0;
		$pass = isset($_POST['password']) ? sanitize_text_field($_POST['password']) : '';

		Safora_Options::update_option('lock_enabled', $enabled);
		if (!empty($pass)) {
			Safora_Options::update_option('lock_password', wp_hash_password($pass));
		}

		wp_send_json_success('Lock settings saved successfully.');
	}

	/**
	 * List existing Google Drive folders for folder picker
	 */
	public static function googledrive_list_folders() {
		check_ajax_referer('safora_admin_nonce', 'security');
		if (!current_user_can('manage_options')) wp_send_json_error('Unauthorized');

		global $safora_core;
		$modules = $safora_core ? $safora_core->get_storage_modules() : array();
		if (empty($modules['googledrive'])) {
			wp_send_json_error('Google Drive storage module not loaded.');
		}

		$gd = $modules['googledrive'];
		$token = $gd->get_access_token();
		if (empty($token)) {
			wp_send_json_error('Please authenticate with Google Drive first before selecting an existing folder.');
		}

		$query = "mimeType = 'application/vnd.google-apps.folder' and trashed = false";
		$search_url = 'https://www.googleapis.com/drive/v3/files?q=' . urlencode($query) . '&fields=files(id,name,modifiedTime)&pageSize=100&orderBy=name';

		$res = wp_remote_get($search_url, array(
			'headers' => array('Authorization' => 'Bearer ' . $token),
			'timeout' => 30,
		));

		if (is_wp_error($res)) {
			wp_send_json_error('Error connecting to Google Drive: ' . $res->get_error_message());
		}

		$code = wp_remote_retrieve_response_code($res);
		if (200 != $code) {
			wp_send_json_error('Google Drive API returned status ' . $code . '. Please re-authenticate.');
		}

		$body = json_decode(wp_remote_retrieve_body($res), true);
		$folders = array();
		if (!empty($body['files']) && is_array($body['files'])) {
			foreach ($body['files'] as $f) {
				$folders[] = array(
					'id'   => $f['id'],
					'name' => $f['name'],
				);
			}
		}

		wp_send_json_success(array('folders' => $folders));
	}
}

function clean_buffers() {
	while (ob_get_level() > 0) {
		ob_end_clean();
	}
}
