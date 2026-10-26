<?php
/**
 * Safora Abstract Base Storage Driver
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

abstract class Safora_Backup_Module implements Safora_Storage_Interface {

	protected $options = array();

	public function __construct() {
		$this->options = $this->get_config();
	}

	/**
	 * Retrieve saved configuration for this driver
	 *
	 * @return array
	 */
	public function get_config() {
		$service_name = $this->get_id();
		$opts = Safora_Options::get_option($service_name, array());
		return is_array($opts) ? $opts : array();
	}

	/**
	 * Return service identifier string (e.g. 's3', 'googledrive')
	 *
	 * @return string
	 */
	abstract public function get_id();

	/**
	 * Return human-readable label
	 *
	 * @return string
	 */
	abstract public function get_label();

	/**
	 * Read job data transient
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	protected function jobdata_get($key, $default = false) {
		global $safora_core;
		if ($safora_core) {
			return $safora_core->jobdata_get($key, $default);
		}
		return $default;
	}

	/**
	 * Write job data transient
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	protected function jobdata_set($key, $value) {
		global $safora_core;
		if ($safora_core) {
			$safora_core->jobdata_set($key, $value);
		}
	}

	/**
	 * Log message to job log
	 *
	 * @param string $message
	 * @param string $level
	 * @return void
	 */
	protected function log($message, $level = 'notice') {
		global $safora_core;
		if ($safora_core) {
			$safora_core->log('[' . $this->get_label() . '] ' . $message, $level);
		}
	}

	/**
	 * Render settings fields HTML for admin dashboard
	 *
	 * @return string HTML
	 */
	abstract public function render_config_fields();
}
