<?php
/**
 * Plugin Name: Safora - Next-Gen WordPress Backup & Migration
 * Plugin URI:  https://github.com/safora-backup/safora
 * Description: High-performance, resilient, adaptive WordPress backup, restoration, and cloud migration engine.
 * Version:     1.4.1
 * Author:      Safora Engineering Team
 * Author URI:  https://safora.io
 * License:     GPLv3 or later
 * Text Domain: safora
 * Domain Path: /languages
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

// Guard against duplicate loads
if (defined('SAFORA_VERSION')) return;

// Plugin Constants
define('SAFORA_VERSION', '1.4.1');
define('SAFORA_DIR', plugin_dir_path(__FILE__));
define('SAFORA_URL', plugin_dir_url(__FILE__));
define('SAFORA_SPLIT_MIN', 25); // Minimum archive split size in MB
define('SAFORA_MAXBATCHFILES', 1000); // Batch file count per zip flush
define('SAFORA_SET_TIME_LIMIT', 900); // 15-minute execution window

// Gzopen compatibility polyfill for Ubuntu / 64-bit PHP systems
if (!function_exists('gzopen') && function_exists('gzopen64')) {
	function gzopen($filename, $mode, $use_include_path = 0) {
		return gzopen64($filename, $mode, $use_include_path);
	}
}

/**
 * Load core plugin dependencies
 */
require_once SAFORA_DIR . 'includes/class-manipulation-functions.php';
require_once SAFORA_DIR . 'includes/class-filesystem-functions.php';
require_once SAFORA_DIR . 'options.php';
require_once SAFORA_DIR . 'includes/class-database-utility.php';
require_once SAFORA_DIR . 'includes/class-semaphore.php';
require_once SAFORA_DIR . 'includes/class-job-scheduler.php';
require_once SAFORA_DIR . 'includes/class-backup-history.php';
require_once SAFORA_DIR . 'includes/class-zip.php';
require_once SAFORA_DIR . 'includes/class-encryption.php';
require_once SAFORA_DIR . 'includes/class-search-replace.php';
require_once SAFORA_DIR . 'includes/class-http-error-descriptions.php';
require_once SAFORA_DIR . 'includes/class-storage-interface.php';

// Storage Methods
require_once SAFORA_DIR . 'methods/backup-module.php';
require_once SAFORA_DIR . 'includes/S3.php';
require_once SAFORA_DIR . 'methods/s3.php';
require_once SAFORA_DIR . 'methods/googledrive.php';
require_once SAFORA_DIR . 'methods/dropbox.php';
require_once SAFORA_DIR . 'methods/ftp.php';
require_once SAFORA_DIR . 'methods/sftp.php';
require_once SAFORA_DIR . 'methods/email.php';
require_once SAFORA_DIR . 'methods/onedrive.php';
require_once SAFORA_DIR . 'methods/webdav.php';
require_once SAFORA_DIR . 'methods/remotesend.php';

// Backup & Restoration Engines
require_once SAFORA_DIR . 'backup.php';
require_once SAFORA_DIR . 'restorer.php';
require_once SAFORA_DIR . 'includes/class-core.php';
require_once SAFORA_DIR . 'includes/class-wpadmin-commands.php';
require_once SAFORA_DIR . 'includes/class-commands.php';

// Admin Controller
if (is_admin() || (defined('DOING_CRON') && DOING_CRON) || (defined('WP_CLI') && WP_CLI)) {
	require_once SAFORA_DIR . 'admin.php';
}

/**
 * Initialize Safora plugin
 */
function safora_init() {
	global $safora_core;
	$safora_core = new Safora_Core();

	if (is_admin()) {
		Safora_Admin::init();
		Safora_WPAdmin_Commands::init();
	}
}
add_action('plugins_loaded', 'safora_init');

/**
 * Plugin activation hook
 */
function safora_activate() {
	// Secure and prepare backup directory
	$custom_dir = Safora_Options::get_setting('backup_dir');
	$dir_name = !empty($custom_dir) ? trim($custom_dir, " \t\n\r\0\x0B/") : 'safora';
	$dir = WP_CONTENT_DIR . '/' . $dir_name;

	if (!is_dir($dir)) {
		wp_mkdir_p($dir);
	}
	Safora_Filesystem_Functions::backup_dir_security($dir);
}
register_activation_hook(__FILE__, 'safora_activate');

/**
 * Plugin deactivation hook
 */
function safora_deactivate() {
	wp_clear_scheduled_hook('safora_backup_cron');
	Safora_Semaphore::release_lock('backup');
}
register_deactivation_hook(__FILE__, 'safora_deactivate');
