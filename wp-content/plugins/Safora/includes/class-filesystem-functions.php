<?php
/**
 * Safora Filesystem Helper Functions
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Filesystem_Functions {

	/**
	 * Thoroughly test if a directory is actually writable by attempting to create and remove a test file
	 *
	 * @param string $dir
	 * @return bool
	 */
	public static function really_is_writable($dir) {
		if (!is_dir($dir)) return false;
		$dir = untrailingslashit($dir);
		$test_file = $dir . '/safora-test-' . md5(uniqid(rand(), true)) . '.tmp';
		$handle = @fopen($test_file, 'w');
		if (false === $handle) return false;
		$written = @fwrite($handle, 'test');
		@fclose($handle);
		@unlink($test_file);
		return (false !== $written && $written > 0);
	}

	/**
	 * Write security files (.htaccess, web.config, index.html) to protect backup folder from web access
	 *
	 * @param string $dir
	 * @return void
	 */
	public static function backup_dir_security($dir) {
		if (!is_dir($dir) || !self::really_is_writable($dir)) return;
		$dir = untrailingslashit($dir);

		// Apache / LiteSpeed .htaccess
		$htaccess_file = $dir . '/.htaccess';
		if (!file_exists($htaccess_file)) {
			$htaccess_content = "# Protect Safora backup files from direct web downloads\n"
				. "<IfModule mod_authz_core.c>\n"
				. "  Require all denied\n"
				. "</IfModule>\n"
				. "<IfModule !mod_authz_core.c>\n"
				. "  Order deny,allow\n"
				. "  Deny from all\n"
				. "</IfModule>\n";
			@file_put_contents($htaccess_file, $htaccess_content);
		}

		// IIS web.config
		$webconfig_file = $dir . '/web.config';
		if (!file_exists($webconfig_file)) {
			$webconfig_content = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"
				. "<configuration>\n"
				. "  <system.webServer>\n"
				. "    <authorization>\n"
				. "      <deny users=\"*\" />\n"
				. "    </authorization>\n"
				. "  </system.webServer>\n"
				. "</configuration>\n";
			@file_put_contents($webconfig_file, $webconfig_content);
		}

		// Blank index.html
		$index_file = $dir . '/index.html';
		if (!file_exists($index_file)) {
			@file_put_contents($index_file, '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body>Directory listing forbidden.</body></html>');
		}
	}

	/**
	 * Remove temporary artifacts older than a given age
	 *
	 * @param string $dir
	 * @param int $age_seconds Default 86400 (24h)
	 * @param string $match_prefix
	 * @return int Number of removed files
	 */
	public static function clean_temporary_files($dir, $age_seconds = 86400, $match_prefix = '') {
		if (!is_dir($dir)) return 0;
		$count = 0;
		$now = time();
		$handle = @opendir($dir);
		if (!$handle) return 0;

		while (false !== ($file = @readdir($handle))) {
			if ('.' === $file || '..' === $file) continue;
			$path = $dir . '/' . $file;
			if (is_file($path)) {
				$is_temp = false;
				if (preg_match('/(\.tmp|\.table\.tmpr\d+|\.table\.tmp|\.table\.gz\.tmp|deleteflag-.*\.txt)$/i', $file)) {
					$is_temp = true;
				}
				if (!empty($match_prefix) && 0 !== strpos($file, $match_prefix)) {
					$is_temp = false;
				}
				if ($is_temp && ($now - @filemtime($path)) > $age_seconds) {
					if (@unlink($path)) {
						$count++;
					}
				}
			}
		}
		@closedir($handle);
		return $count;
	}

	/**
	 * Recursively compute disk size of a directory
	 *
	 * @param string $dir
	 * @return int
	 */
	public static function get_disk_space_used($dir) {
		$size = 0;
		if (!is_dir($dir)) return 0;
		$files = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
			RecursiveIteratorIterator::SELF_FIRST
		);
		foreach ($files as $file) {
			if ($file->isFile()) {
				$size += $file->getSize();
			}
		}
		return $size;
	}

	/**
	 * Recursively delete directory contents and the directory itself
	 *
	 * @param string $dir
	 * @return bool
	 */
	public static function recursive_delete($dir) {
		if (!is_dir($dir)) return false;
		$files = array_diff(scandir($dir), array('.', '..'));
		foreach ($files as $file) {
			$path = "$dir/$file";
			is_dir($path) ? self::recursive_delete($path) : @unlink($path);
		}
		return @rmdir($dir);
	}

	/**
	 * Locate working binary executable in standard server locations
	 *
	 * @param array $possible_paths
	 * @return string|false
	 */
	public static function find_working_binary($possible_paths) {
		if (!function_exists('proc_open')) return false;

		// Check if exec / proc_open is disabled in php.ini
		$disabled_functions = explode(',', ini_get('disable_functions'));
		$disabled = array_map('trim', $disabled_functions);
		if (in_array('proc_open', $disabled)) return false;

		foreach ($possible_paths as $path) {
			if (file_exists($path) && is_executable($path)) {
				return $path;
			}
		}
		return false;
	}
}
