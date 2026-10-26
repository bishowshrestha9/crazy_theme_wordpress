<?php
/**
 * Plugin Name: Pretty Links Target Domain Checker
 * Plugin URI: https://wordpress.org/plugins/prli-target-domain-scanner
 * Description: Detects whether Pretty Links target URLs match an expected target domain provided via admin input, and lists all non-matching links with direct edit links and CSV export.
 * Version: 1.1.0
 * Author: Bishow Shrestha
 * Text Domain: prli-target-domain-scanner
 * License: GPLv2 or later
 */

if (!defined('ABSPATH')) {
	exit;
}

define('PRLI_TDS_VERSION', '1.1.0');
define('PRLI_TDS_PLUGIN_FILE', __FILE__);
define('PRLI_TDS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PRLI_TDS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Require Core Classes
require_once PRLI_TDS_PLUGIN_DIR . 'includes/class-domain-scanner-engine.php';
require_once PRLI_TDS_PLUGIN_DIR . 'includes/class-admin-page.php';
require_once PRLI_TDS_PLUGIN_DIR . 'includes/class-ajax-handler.php';

/**
 * Initialize Plugin
 */
function prli_tds_init_plugin(): void {
	PRLI_TDS_Admin_Page::init();
	PRLI_TDS_Ajax_Handler::init();
}
add_action('plugins_loaded', 'prli_tds_init_plugin');
