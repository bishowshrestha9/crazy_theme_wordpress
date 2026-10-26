<?php
/**
 * Safora Manipulation & String Helper Functions
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Manipulation_Functions {

	/**
	 * Convert byte count to human-readable format
	 *
	 * @param int $bytes
	 * @param int $decimals
	 * @return string
	 */
	public static function format_bytes($bytes, $decimals = 2) {
		if ($bytes < 0) return '0 B';
		$units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
		$bytes = max($bytes, 0);
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024));
		$pow = min($pow, count($units) - 1);
		$bytes /= pow(1024, $pow);
		return round($bytes, $decimals) . ' ' . $units[$pow];
	}

	/**
	 * Convert human size string (e.g. 256M, 1G) to bytes
	 *
	 * @param string $val
	 * @return int
	 */
	public static function convert_to_bytes($val) {
		$val = trim($val);
		if (empty($val)) return 0;
		$last = strtolower($val[strlen($val) - 1]);
		$val = (int) $val;
		switch ($last) {
			case 'g':
				$val *= 1024 * 1024 * 1024;
				break;
			case 'm':
				$val *= 1024 * 1024;
				break;
			case 'k':
				$val *= 1024;
				break;
		}
		return $val;
	}

	/**
	 * Clean and normalize a path for cross-platform safety
	 *
	 * @param string $path
	 * @return string
	 */
	public static function normalize_path($path) {
		$path = str_replace('\\', '/', $path);
		$path = preg_replace('|(?<=.)/+|', '/', $path);
		if (':' === substr($path, 1, 1)) {
			$path = ucfirst($path);
		}
		return $path;
	}

	/**
	 * Safely truncate string for logging
	 *
	 * @param string $string
	 * @param int $length
	 * @return string
	 */
	public static function truncate_log_string($string, $length = 1000) {
		if (strlen($string) > $length) {
			return substr($string, 0, $length) . '... [TRUNCATED]';
		}
		return $string;
	}
}
