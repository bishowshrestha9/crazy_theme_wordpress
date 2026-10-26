<?php
/**
 * Safora Core Controller & State Machine
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Core {

	private $job_nonce = '';
	private $job_timestamp = 0;
	private $log_handle = null;
	private $storage_modules = array();

	public function __construct() {
		$this->register_storage_modules();
		$this->init_hooks();
	}

	/**
	 * Register all available cloud storage modules
	 */
	private function register_storage_modules() {
		$modules = array(
			's3'          => 'Safora_Backup_Module_S3',
			'googledrive' => 'Safora_Backup_Module_GoogleDrive',
			'dropbox'     => 'Safora_Backup_Module_Dropbox',
			'ftp'         => 'Safora_Backup_Module_FTP',
			'sftp'        => 'Safora_Backup_Module_SFTP',
			'email'       => 'Safora_Backup_Module_Email',
			'onedrive'    => 'Safora_Backup_Module_OneDrive',
			'webdav'      => 'Safora_Backup_Module_WebDAV',
			'remotesend'  => 'Safora_Backup_Module_RemoteSend',
		);

		foreach ($modules as $id => $class_name) {
			if (class_exists($class_name)) {
				$this->storage_modules[$id] = new $class_name();
			}
		}
	}

	/**
	 * Hook registrations
	 */
	private function init_hooks() {
		add_action('safora_backup_resume', array($this, 'backup_resume'), 10, 2);
		add_action('safora_backup', array($this, 'backup_files_cron_event'));
		add_action('safora_backup_database', array($this, 'backup_db_cron_event'));
		add_action('safora_backup_cron', array($this, 'backup_cron_event'));
		add_action('updraft_backup', array($this, 'backup_files_cron_event'));
		add_action('updraft_backup_database', array($this, 'backup_db_cron_event'));
		add_filter('cron_schedules', array($this, 'add_cron_schedules'), 30);
	}

	/**
	 * Register custom cron intervals
	 */
	public function add_cron_schedules($schedules) {
		$schedules['everyhour']   = array('interval' => 3600, 'display' => 'Every Hour');
		$schedules['every2hours'] = array('interval' => 7200, 'display' => 'Every 2 Hours');
		$schedules['every4hours'] = array('interval' => 14400, 'display' => 'Every 4 Hours');
		$schedules['every8hours'] = array('interval' => 28800, 'display' => 'Every 8 Hours');
		$schedules['weekly']      = array('interval' => 604800, 'display' => 'Weekly (7 days)');
		$schedules['fortnightly'] = array('interval' => 1209600, 'display' => 'Fortnightly (14 days)');
		$schedules['monthly']     = array('interval' => 2592000, 'display' => 'Monthly (30 days)');
		return $schedules;
	}

	/**
	 * Triggered by scheduled files cron event (or files + DB if same schedule)
	 */
	public function backup_files_cron_event() {
		$include_files = (bool) Safora_Options::get_setting('include_plugins', 1);
		$db_int = Safora_Options::get_setting('interval_database', 'manual');
		$files_int = Safora_Options::get_setting('interval', 'manual');
		$include_db = ($db_int === $files_int && $db_int !== 'manual') ? (bool) Safora_Options::get_setting('include_db', 1) : false;
		$this->boot_backup($include_files, $include_db, true);
	}

	/**
	 * Triggered by scheduled database cron event
	 */
	public function backup_db_cron_event() {
		$db_int = Safora_Options::get_setting('interval_database', 'manual');
		$files_int = Safora_Options::get_setting('interval', 'manual');
		if ($db_int === $files_int && $files_int !== 'manual') {
			// Already executed together in backup_files_cron_event
			return;
		}
		$include_db = (bool) Safora_Options::get_setting('include_db', 1);
		$this->boot_backup(false, $include_db, true);
	}

	/**
	 * Triggered by general scheduled backup cron event
	 */
	public function backup_cron_event() {
		$include_files = (bool) Safora_Options::get_setting('include_plugins', 1);
		$include_db = (bool) Safora_Options::get_setting('include_db', 1);
		$this->boot_backup($include_files, $include_db, true);
	}

	/**
	 * Calculate the first UNIX timestamp for a scheduled backup event based on interval, HH:MM, and day
	 *
	 * @param string $sched Interval key ('every2hours', 'every4hours', 'every8hours', 'twicedaily', 'daily', 'weekly', 'fortnightly', 'monthly')
	 * @param string $start_time HH:MM in blog time zone
	 * @param int $start_day Day of week (0-6) or Day of month (1-28)
	 * @return int UNIX timestamp (GMT)
	 */
	public static function calculate_next_schedule_time($sched = 'daily', $start_time = '00:00', $start_day = 0) {
		$unixtime_now = time();
		$now_gmt_str = gmdate('Y-m-d H:i:s', $unixtime_now);
		$now_blog_str = get_date_from_gmt($now_gmt_str, 'Y-m-d H:i:s');

		// Parse HH:MM
		$start_hour = 0;
		$start_minute = 0;
		if (preg_match('/^(\d{1,2}):(\d{1,2})$/', trim($start_time), $matches)) {
			$start_hour = min(23, max(0, (int) $matches[1]));
			$start_minute = min(59, max(0, (int) $matches[2]));
		}

		if ('hourly' === $sched || 'everyhour' === $sched || 'every2hours' === $sched || 'every4hours' === $sched || 'every8hours' === $sched || 'twicedaily' === $sched) {
			$interval_map = array(
				'everyhour'   => 3600,
				'hourly'      => 3600,
				'every2hours' => 7200,
				'every4hours' => 14400,
				'every8hours' => 28800,
				'twicedaily'  => 43200,
			);
			$step_sec = isset($interval_map[$sched]) ? $interval_map[$sched] : 86400;

			$target_blog_date = substr($now_blog_str, 0, 11) . sprintf('%02d:%02d:00', $start_hour, $start_minute);
			$target_unix = get_gmt_from_date($target_blog_date, 'U');

			if ($target_unix < $unixtime_now) {
				$diff = $unixtime_now - $target_unix;
				$steps = ceil($diff / $step_sec);
				$target_unix += ($steps * $step_sec);
			}
			return $target_unix;
		}

		if ('monthly' === $sched) {
			$start_day = max(1, min(28, (int) $start_day));
			$cur_year = (int) get_date_from_gmt($now_gmt_str, 'Y');
			$cur_month = (int) get_date_from_gmt($now_gmt_str, 'm');

			$this_month_blog = sprintf('%04d-%02d-%02d %02d:%02d:00', $cur_year, $cur_month, $start_day, $start_hour, $start_minute);
			$target_unix = get_gmt_from_date($this_month_blog, 'U');

			if ($target_unix < $unixtime_now) {
				$next_month = ($cur_month === 12) ? 1 : ($cur_month + 1);
				$next_year = ($cur_month === 12) ? ($cur_year + 1) : $cur_year;
				$next_month_blog = sprintf('%04d-%02d-%02d %02d:%02d:00', $next_year, $next_month, $start_day, $start_hour, $start_minute);
				$target_unix = get_gmt_from_date($next_month_blog, 'U');
			}
			return $target_unix;
		}

		if ('weekly' === $sched || 'fortnightly' === $sched) {
			$start_day = max(0, min(6, (int) $start_day)); // 0 = Sunday, 6 = Saturday
			$day_today_blog = (int) get_date_from_gmt($now_gmt_str, 'w');

			$days_diff = $start_day - $day_today_blog;
			if ($days_diff < 0) {
				$days_diff += 7;
			}

			$target_blog_date = get_date_from_gmt(gmdate('Y-m-d H:i:s', $unixtime_now + ($days_diff * 86400)), 'Y-m-d') . sprintf(' %02d:%02d:00', $start_hour, $start_minute);
			$target_unix = get_gmt_from_date($target_blog_date, 'U');

			if ($target_unix < $unixtime_now) {
				$target_unix += 86400 * 7;
			}
			return $target_unix;
		}

		// Daily (default)
		$target_blog_date = substr($now_blog_str, 0, 11) . sprintf('%02d:%02d:00', $start_hour, $start_minute);
		$target_unix = get_gmt_from_date($target_blog_date, 'U');
		if ($target_unix < $unixtime_now) {
			$target_unix += 86400; // Next day
		}
		return $target_unix;
	}

	/**
	 * Schedule or reschedule WP-Cron backup events based on current settings
	 */
	public function reschedule_cron_backups() {
		$files_int = Safora_Options::get_setting('interval', 'manual');
		$db_int = Safora_Options::get_setting('interval_database', 'manual');
		$files_time = Safora_Options::get_setting('starttime_files', '00:00');
		$files_day = (int) Safora_Options::get_setting('startday_files', 0);
		$db_time = Safora_Options::get_setting('starttime_db', '00:00');
		$db_day = (int) Safora_Options::get_setting('startday_db', 0);

		// 1. Files schedule
		wp_clear_scheduled_hook('safora_backup');
		if ('manual' !== $files_int && !empty($files_int)) {
			$first_time = self::calculate_next_schedule_time($files_int, $files_time, $files_day);
			wp_schedule_event($first_time, $files_int, 'safora_backup');
		}

		// 2. Database schedule
		wp_clear_scheduled_hook('safora_backup_database');
		if ('manual' !== $db_int && !empty($db_int)) {
			if ($db_int !== $files_int) {
				$first_db_time = self::calculate_next_schedule_time($db_int, $db_time, $db_day);
				wp_schedule_event($first_db_time, $db_int, 'safora_backup_database');
			}
		}
	}

	/**
	 * Main entrypoint to initiate a backup job
	 *
	 * @param bool $files
	 * @param bool $db
	 * @param bool|null $immediate_resume Force immediate synchronous execution (defaults to true for WP-CLI, false for Web)
	 * @param bool $send_remote
	 * @param array|null $services_override Explicit list of storage services for this job (e.g. array('remotesend'))
	 * @return string|WP_Error Nonce if successful
	 */
	public function boot_backup($files = true, $db = true, $immediate_resume = null, $send_remote = true, $services_override = null) {
		if (null === $immediate_resume) {
			$immediate_resume = (defined('WP_CLI') && WP_CLI);
		}

		// Acquire global lock
		if (!Safora_Semaphore::acquire_lock('backup', 900)) {
			return new WP_Error('already_running', 'Another backup job is currently in progress.');
		}

		$this->job_timestamp = time();
		$this->job_nonce = substr(md5(uniqid(rand(), true)), 0, 12);

		$this->jobdata_set_multi(array(
			'job_time'          => $this->job_timestamp,
			'job_nonce'         => $this->job_nonce,
			'backup_files'      => $files ? 1 : 0,
			'backup_db'         => $db ? 1 : 0,
			'send_remote'       => $send_remote ? 1 : 0,
			'services_override' => is_array($services_override) ? $services_override : null,
			'uploaded'          => array(),
			'created_files'     => array(),
			'errors'            => array(),
			'warnings'          => array(),
			'last_heartbeat'    => time(),
		));

		$this->logfile_open($this->job_nonce);
		$this->log("==========================================");
		$this->log("Safora Backup Job Started (Nonce: {$this->job_nonce}, Time: {$this->job_timestamp})");
		$this->log("WordPress Version: " . get_bloginfo('version') . " | PHP Version: " . PHP_VERSION);
		$this->log("Site URL: " . site_url() . " | Home URL: " . home_url());
		$this->log("Memory Limit: " . ini_get('memory_limit') . " | Max Execution Time: " . ini_get('max_execution_time'));
		$this->log("==========================================");

		if ($immediate_resume) {
			// Execute synchronously (for CLI / Cron)
			$this->backup_resume(0, $this->job_nonce);
		} else {
			// Spawn asynchronously for web/AJAX requests
			$this->spawn_resumption(0, $this->job_nonce);
		}

		return $this->job_nonce;
	}

	/**
	 * Spawn background resumption asynchronously
	 *
	 * @param int $resumption_no
	 * @param string $nonce
	 */
	public function spawn_resumption($resumption_no = 0, $nonce = '') {
		if (empty($nonce)) return;

		// 1. Schedule via WP-Cron single event
		wp_schedule_single_event(time(), 'safora_backup_resume', array($resumption_no, $nonce));

		// 2. Dispatch non-blocking loopback HTTP request to admin-ajax
		$ajax_url = admin_url('admin-ajax.php');
		wp_remote_post($ajax_url, array(
			'timeout'   => 0.01,
			'blocking'  => false,
			'sslverify' => apply_filters('https_local_ssl_verify', false),
			'body'      => array(
				'action'     => 'safora_resume_job',
				'nonce'      => $nonce,
				'resumption' => $resumption_no,
			),
		));

		// 3. Trigger spawn_cron fallback
		if (function_exists('spawn_cron')) {
			spawn_cron(time());
		}
	}

	/**
	 * Resumption State Machine
	 *
	 * @param int $resumption_no
	 * @param string $nonce
	 */
	public function backup_resume($resumption_no = 0, $nonce = '') {
		if (empty($nonce)) return;

		// Acquire per-resumption lock
		if (!Safora_Semaphore::acquire_resumption_lock($nonce, $resumption_no)) {
			return;
		}

		$this->job_nonce = $nonce;
		$this->job_timestamp = (int) $this->jobdata_get('job_time', time());
		$this->logfile_open($this->job_nonce);

		$interval = (int) $this->jobdata_get('resume_interval', 60);
		Safora_Job_Scheduler::init($this->job_nonce, $resumption_no, $interval);

		$this->log("Executing resumption pass #$resumption_no (Nonce: $nonce)...");

		// Schedule safety resumption in case PHP times out
		Safora_Job_Scheduler::reschedule($interval);

		@set_time_limit(900);
		if (function_exists('wp_raise_memory_limit')) {
			wp_raise_memory_limit('admin');
		}

		$do_files = (bool) $this->jobdata_get('backup_files', 1);
		$do_db = (bool) $this->jobdata_get('backup_db', 1);

		$backup_engine = new Safora_Backup($do_files, $do_db);

		// 1. Files Backup
		if ($do_files && !$this->jobdata_get('all_files_done')) {
			$files = $backup_engine->backup_files();
			$this->jobdata_set('all_files_done', 1);
		}

		// 2. Database Backup
		if ($do_db && !$this->jobdata_get('db_done')) {
			$db_file = $backup_engine->backup_database();
		}

		// Collect all created files
		$all_created = $backup_engine->get_created_files();
		$this->jobdata_set('created_files', $all_created);

		// 3. Cloud Dispatch
		$send_remote = (bool) $this->jobdata_get('send_remote', 1);
		$services_override = $this->jobdata_get('services_override', null);

		if (is_array($services_override)) {
			$services = $services_override;
		} else {
			$services = (array) Safora_Options::get_setting('service');

			// Auto-include Google Drive if authenticated and not yet in service array
			if (isset($this->storage_modules['googledrive'])) {
				$gd_token = $this->storage_modules['googledrive']->get_access_token();
				if (!empty($gd_token) && !in_array('googledrive', $services)) {
					$services[] = 'googledrive';
					Safora_Options::update_option('service', array_values(array_unique($services)));
				}
			}
		}

		if ($send_remote && !empty($services) && !empty($all_created)) {
			$this->log("Dispatching created archives to cloud storage services: " . implode(', ', $services));
			foreach ($services as $service_id) {
				if (isset($this->storage_modules[$service_id])) {
					try {
						$res = $this->storage_modules[$service_id]->backup($all_created);
						if (is_wp_error($res)) {
							$this->log("Storage driver '$service_id' reported error: " . $res->get_error_message(), 'error');
						}
					} catch (Exception $e) {
						$this->log("Exception in storage driver $service_id: " . $e->getMessage(), 'error');
					}
				} else {
					$this->log("Notice: Storage driver '$service_id' module not found.", 'warning');
				}
			}
		} elseif (!$send_remote) {
			$this->log("Remote cloud storage upload skipped (disabled in backup options).");
		} elseif (empty($services)) {
			$this->log("Notice: No remote cloud storage service selected or authenticated.");
		}

		// 4. Save to Backup History
		$this->save_backup_to_history($all_created, $services, $backup_engine->get_checksums());

		// 5. Complete backup
		$this->backup_finish(true, true);
	}

	/**
	 * Complete backup job lifecycle, prune history, and clean locks
	 */
	public function backup_finish($cleanup = true, $send_email = true) {
		$this->log("Finishing backup job and releasing semaphore locks...");

		// Clear scheduled resumption events for this job
		$crons = _get_cron_array();
		if (!empty($crons)) {
			foreach ($crons as $ts => $hooks) {
				if (isset($hooks['safora_backup_resume'])) {
					foreach ($hooks['safora_backup_resume'] as $sig => $data) {
						if (isset($data['args'][1]) && $data['args'][1] === $this->job_nonce) {
							wp_unschedule_event($ts, 'safora_backup_resume', $data['args']);
						}
					}
				}
			}
		}

		// Prune retained backups
		$services = (array) Safora_Options::get_setting('service');
		$pruned = Safora_Backup_History::prune_retained_backups($services);
		if (!empty($pruned)) {
			$this->log("Pruned old backups beyond retention quota: " . implode(', ', $pruned));
		}

		// Delete local copies if setting enabled and remote services configured
		$delete_local = (bool) Safora_Options::get_setting('delete_local');
		if ($delete_local && !empty($services)) {
			$created = (array) $this->jobdata_get('created_files', array());
			$bdir = $this->backups_dir_location();
			foreach ($created as $fn) {
				if ($this->is_uploaded($fn)) {
					@unlink($bdir . '/' . $fn);
					$this->log("Deleted local copy after verified cloud upload: $fn");
				}
			}
		}

		// Send summary email if configured
		if ($send_email) {
			$this->send_results_email();
		}

		$this->log("Safora Backup Job Finished Successfully.");
		$this->logfile_close();

		// Release global lock
		Safora_Semaphore::release_lock('backup');
	}

	/**
	 * Save current backup set to persistent history
	 */
	private function save_backup_to_history($files, $services, $checksums) {
		$record = array(
			'timestamp' => $this->job_timestamp,
			'nonce'     => $this->job_nonce,
			'service'   => $services,
			'checksums' => $checksums,
			'always_keep' => false,
		);

		foreach ($files as $fn) {
			if (preg_match('/-db(\.gz|\.gz\.crypt)$/i', $fn)) {
				$record['db'] = $fn;
			} elseif (preg_match('/-([a-z]+)(\d*)\.zip(\.crypt)?$/i', $fn, $m)) {
				$entity = $m[1];
				if (!isset($record[$entity])) $record[$entity] = array();
				$record[$entity][] = $fn;
			}
		}

		Safora_Backup_History::save_backup($this->job_timestamp, $record);
		$this->log("Saved backup set to history registry.");
	}

	/**
	 * Send results email
	 */
	private function send_results_email() {
		$to = Safora_Options::get_setting('reporting_email');
		if (empty($to)) {
			$email_opt = Safora_Options::get_setting('email');
			if (is_array($email_opt) && !empty($email_opt['email'])) {
				$to = $email_opt['email'];
			} elseif (is_string($email_opt)) {
				$to = $email_opt;
			}
		}
		if (empty($to) || !is_string($to)) return;

		$subject = sprintf('[%s] Backup Complete - Safora', get_bloginfo('name'));
		$body = "Your WordPress backup job has completed.\n\n"
			. "Site: " . site_url() . "\n"
			. "Timestamp: " . date('Y-m-d H:i:s', $this->job_timestamp) . "\n"
			. "Job Nonce: " . $this->job_nonce . "\n"
			. "Files: " . implode(', ', (array) $this->jobdata_get('created_files', array())) . "\n\n"
			. "Thank you for using Safora Backup.";

		wp_mail($to, $subject, $body);
	}

	// ---------------------------------------------------------
	// Helpers & Transients
	// ---------------------------------------------------------

	public function get_job_nonce() { return $this->job_nonce; }
	public function get_job_timestamp() { return $this->job_timestamp; }
	public function get_storage_modules() { return $this->storage_modules; }

	public function backups_dir_location() {
		$custom_dir = Safora_Options::get_setting('backup_dir');
		$dir_name = !empty($custom_dir) ? trim($custom_dir, " \t\n\r\0\x0B/") : 'safora';
		$dir = WP_CONTENT_DIR . '/' . $dir_name;

		if (!is_dir($dir)) {
			wp_mkdir_p($dir);
		}

		Safora_Filesystem_Functions::backup_dir_security($dir);
		return $dir;
	}

	public function get_backupable_file_entities() {
		$upload_info = wp_upload_dir();
		return array(
			'plugins' => WP_PLUGIN_DIR,
			'themes'  => get_theme_root(),
			'uploads' => $upload_info['basedir'],
			'others'  => WP_CONTENT_DIR,
			'wpcore'  => ABSPATH,
		);
	}

	public function jobdata_set($key, $val) {
		$data = $this->jobdata_get_all();
		$data[$key] = $val;
		set_transient('safora_jobdata_' . $this->job_nonce, $data, 86400);
	}

	public function jobdata_set_multi($pairs) {
		$data = $this->jobdata_get_all();
		foreach ($pairs as $k => $v) {
			$data[$k] = $v;
		}
		set_transient('safora_jobdata_' . $this->job_nonce, $data, 86400);
	}

	public function jobdata_get($key, $default = false) {
		$data = $this->jobdata_get_all();
		return isset($data[$key]) ? $data[$key] : $default;
	}

	private function jobdata_get_all() {
		$data = get_transient('safora_jobdata_' . $this->job_nonce);
		return is_array($data) ? $data : array();
	}

	public function uploaded_file($file) {
		$uploaded = (array) $this->jobdata_get('uploaded', array());
		$uploaded[$file] = time();
		$this->jobdata_set('uploaded', $uploaded);
	}

	public function is_uploaded($file) {
		$uploaded = (array) $this->jobdata_get('uploaded', array());
		return isset($uploaded[$file]);
	}

	public function logfile_open($nonce) {
		if ($this->log_handle) return;
		$dir = $this->backups_dir_location();
		$this->log_handle = @fopen($dir . "/log.{$nonce}.txt", 'a');
	}

	public function log($msg, $level = 'notice') {
		$ts = date('Y-m-d H:i:s');
		$line = "[$ts] [$level] $msg\n";
		if ($this->log_handle) {
			@fwrite($this->log_handle, $line);
		}
	}

	public function logfile_close() {
		if ($this->log_handle) {
			@fclose($this->log_handle);
			$this->log_handle = null;
		}
	}
}
