<?php
/**
 * Safora Adaptive Job Scheduler & Heartbeat Engine
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Job_Scheduler {

	private static $last_useful_progress = 0;
	private static $job_nonce = '';
	private static $current_resumption = 0;
	private static $resume_interval = 60; // Fast responsive 60-second default

	/**
	 * Initialize scheduler state for a job run
	 *
	 * @param string $nonce
	 * @param int $resumption
	 * @param int $interval
	 */
	public static function init($nonce, $resumption, $interval = 60) {
		self::$job_nonce = $nonce;
		self::$current_resumption = $resumption;
		self::$resume_interval = max(30, (int) $interval);
		self::$last_useful_progress = time();
	}

	/**
	 * Record a heartbeat pulse that current thread is alive and working
	 *
	 * @return void
	 */
	public static function record_still_alive() {
		global $safora_core;
		if (empty(self::$job_nonce)) return;

		$now = time();
		if ($safora_core) {
			$safora_core->jobdata_set('last_heartbeat', $now);
		}

		self::check_abort_signal();
		self::reschedule_if_needed();
	}

	/**
	 * Record that significant progress occurred (e.g. table finished or file batch added)
	 *
	 * @return void
	 */
	public static function something_useful_happened() {
		global $safora_core;
		self::$last_useful_progress = time();
		if ($safora_core) {
			$safora_core->jobdata_set('last_useful_progress', self::$last_useful_progress);
		}
		self::check_abort_signal();
	}

	/**
	 * Check if a cancellation/abort signal was issued by user
	 *
	 * @return void
	 */
	public static function check_abort_signal() {
		global $safora_core;
		if (empty(self::$job_nonce) || !$safora_core) return;

		$backup_dir = $safora_core->backups_dir_location();
		$abort_flag = $backup_dir . '/deleteflag-' . self::$job_nonce . '.txt';

		if (file_exists($abort_flag)) {
			@unlink($abort_flag);
			$safora_core->log('User abort flag detected (' . basename($abort_flag) . '). Aborting backup job cleanly.');
			$safora_core->backup_finish(true, false);
			die();
		}
	}

	/**
	 * Reschedule the next resumption cron event
	 *
	 * @param int $seconds
	 * @return void
	 */
	public static function reschedule($seconds) {
		$seconds = max(30, (int) $seconds);
		$next_run = time() + $seconds;
		$next_resumption = self::$current_resumption + 1;

		// Clear existing scheduled resumptions for this job
		$crons = _get_cron_array();
		if (!empty($crons)) {
			foreach ($crons as $ts => $hooks) {
				if (isset($hooks['safora_backup_resume'])) {
					foreach ($hooks['safora_backup_resume'] as $sig => $data) {
						if (isset($data['args'][1]) && $data['args'][1] === self::$job_nonce) {
							wp_unschedule_event($ts, 'safora_backup_resume', $data['args']);
						}
					}
				}
			}
		}

		// Schedule next resumption event
		wp_schedule_single_event($next_run, 'safora_backup_resume', array($next_resumption, self::$job_nonce));
	}

	/**
	 * If the next scheduled resumption is coming up within 20s, push it further out
	 *
	 * @return void
	 */
	public static function reschedule_if_needed() {
		$next_run = wp_next_scheduled('safora_backup_resume', array(self::$current_resumption + 1, self::$job_nonce));
		if ($next_run && ($next_run - time()) < 20) {
			self::increase_resume_and_reschedule(30, false);
		}
	}

	/**
	 * Increase resumption interval adaptively and reschedule next run
	 *
	 * @param int $howmuch
	 * @param bool $due_to_overlap
	 * @return void
	 */
	public static function increase_resume_and_reschedule($howmuch = 30, $due_to_overlap = false) {
		global $safora_core;
		self::$resume_interval = min(300, self::$resume_interval + $howmuch);
		if ($safora_core) {
			$safora_core->jobdata_set('resume_interval', self::$resume_interval);
		}
		self::reschedule(self::$resume_interval);
	}

	/**
	 * Handle overlapping file modification detection: stop current thread gracefully
	 *
	 * @param string $file
	 * @param int $time_now
	 * @param int $time_mod
	 * @param int $increase
	 * @return void
	 */
	public static function terminate_due_to_activity($file, $time_now, $time_mod, $increase = 30) {
		global $safora_core;
		if ($safora_core) {
			$safora_core->log("Detected activity on ($file, mod=$time_mod, now=$time_now). Another process is likely running. Yielding gracefully.");
		}
		self::increase_resume_and_reschedule($increase, true);
		die();
	}
}
