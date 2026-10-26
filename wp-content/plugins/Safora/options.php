<?php
/**
 * Safora Options & Configuration Manager
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Options {

	const PREFIX = 'safora_';

	/**
	 * Get option with multisite support
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public static function get_option($key, $default = false) {
		$prefixed_key = self::PREFIX . $key;
		if (is_multisite()) {
			return get_site_option($prefixed_key, $default);
		}
		return get_option($prefixed_key, $default);
	}

	/**
	 * Update option with multisite support
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return bool
	 */
	public static function update_option($key, $value) {
		$prefixed_key = self::PREFIX . $key;
		if (is_multisite()) {
			return update_site_option($prefixed_key, $value);
		}
		return update_option($prefixed_key, $value);
	}

	/**
	 * Delete option with multisite support
	 *
	 * @param string $key
	 * @return bool
	 */
	public static function delete_option($key) {
		$prefixed_key = self::PREFIX . $key;
		if (is_multisite()) {
			return delete_site_option($prefixed_key);
		}
		return delete_option($prefixed_key);
	}

	/**
	 * Return the admin URL for Safora settings
	 *
	 * @return string
	 */
	public static function admin_page_url() {
		if (is_multisite()) {
			return network_admin_url('settings.php?page=safora');
		}
		return admin_url('options-general.php?page=safora');
	}

	/**
	 * Get default configuration values
	 *
	 * @return array
	 */
	public static function get_defaults() {
		return array(
			'interval'            => 'manual',
			'interval_database'   => 'manual',
			'starttime_files'     => '00:00',
			'startday_files'      => 0,
			'starttime_db'        => '00:00',
			'startday_db'         => 0,
			'retain'              => 2,
			'retain_db'           => 2,
			'retain_extrarules'   => array('files' => array(), 'db' => array()),
			'incremental_mode'    => 'none',
			'split_every'         => 400, // MB
			'service'             => array(),
			'include_plugins'     => 1,
			'include_themes'      => 1,
			'include_uploads'     => 1,
			'include_others'      => 1,
			'include_wpcore'      => 0,
			'include_db'          => 1,
			'exclude_uploads'     => "backup*\n*backups\nsafora*\n*cache*",
			'exclude_others'      => "upgrade\ncache",
			'backup_dir'          => 'safora',
			'email'               => '',
			'reporting_email'     => '',
			'debug_mode'          => 0,
			'binzip'              => 1,
			'binmysqldump'        => 1,
			'encryption_passphrase' => '',
			'delete_local'        => 0, // delete local archive after cloud upload
			'backup_name_prefix'  => '', // custom prefix for backup files
		);
	}

	/**
	 * Get sanitized option or fall back to default
	 *
	 * @param string $key
	 * @return mixed
	 */
	public static function get_setting($key) {
		$defaults = self::get_defaults();
		$default = isset($defaults[$key]) ? $defaults[$key] : false;
		return self::get_option($key, $default);
	}

	/**
	 * Get sanitized custom backup filename prefix
	 *
	 * @return string
	 */
	public static function get_backup_name_prefix() {
		$prefix = trim((string) self::get_setting('backup_name_prefix', ''));
		if (empty($prefix)) {
			return 'backup';
		}
		$clean = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $prefix);
		$clean = trim($clean, '_-');
		return !empty($clean) ? $clean : 'backup';
	}
}
