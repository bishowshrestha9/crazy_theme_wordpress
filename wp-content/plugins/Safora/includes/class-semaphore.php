<?php
/**
 * Safora Concurrency & Semaphore Lock Manager
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Semaphore {

	const LOCK_TIMEOUT = 900; // 15 minutes before considering a lock stale

	/**
	 * Acquire global backup semaphore lock
	 *
	 * @param string $lock_name
	 * @param int $timeout
	 * @return bool True if acquired, false if locked
	 */
	public static function acquire_lock($lock_name = 'backup', $timeout = self::LOCK_TIMEOUT) {
		$option_name = 'safora_semaphore_' . $lock_name;
		$now = time();
		$current = Safora_Options::get_option($option_name, false);

		if (false !== $current) {
			$lock_time = (int) $current;
			if (($now - $lock_time) < $timeout) {
				// Lock is active and not expired
				return false;
			}
		}

		// Lock doesn't exist or has expired, claim it
		Safora_Options::update_option($option_name, $now);
		return true;
	}

	/**
	 * Release global semaphore lock
	 *
	 * @param string $lock_name
	 * @return bool
	 */
	public static function release_lock($lock_name = 'backup') {
		$option_name = 'safora_semaphore_' . $lock_name;
		return Safora_Options::delete_option($option_name);
	}

	/**
	 * Acquire job-specific resumption transient lock
	 *
	 * @param string $nonce
	 * @param int $resumption
	 * @param int $timeout
	 * @return bool
	 */
	public static function acquire_resumption_lock($nonce, $resumption, $timeout = 180) {
		$transient_key = 'safora_lock_' . substr(md5($nonce . '_' . $resumption), 0, 20);
		if (get_transient($transient_key)) {
			return false;
		}
		set_transient($transient_key, time(), $timeout);
		return true;
	}

	/**
	 * Release job-specific resumption transient lock
	 *
	 * @param string $nonce
	 * @param int $resumption
	 * @return bool
	 */
	public static function release_resumption_lock($nonce, $resumption) {
		$transient_key = 'safora_lock_' . substr(md5($nonce . '_' . $resumption), 0, 20);
		return delete_transient($transient_key);
	}
}
