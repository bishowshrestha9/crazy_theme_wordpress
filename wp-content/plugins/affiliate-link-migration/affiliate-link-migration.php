<?php
/**
 * Plugin Name: Affiliate Link Migration
 * Description: Production-ready batch migration tool for scanning published posts and appending origin=auto to Site A Pretty Links.
 * Version: 1.0.0
 * Author: Senior WordPress Plugin Developer
 * Text Domain: affiliate-link-migration
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

namespace AffiliateLinkMigration;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'AFFILIATE_LM_VERSION', '1.0.0' );
define( 'AFFILIATE_LM_PATH', plugin_dir_path( __FILE__ ) );
define( 'AFFILIATE_LM_URL', plugin_dir_url( __FILE__ ) );

// Load Class Files
require_once AFFILIATE_LM_PATH . 'includes/class-logger.php';
require_once AFFILIATE_LM_PATH . 'includes/class-settings.php';
require_once AFFILIATE_LM_PATH . 'includes/class-migration-engine.php';
require_once AFFILIATE_LM_PATH . 'includes/class-dashboard.php';
require_once AFFILIATE_LM_PATH . 'includes/class-admin-menu.php';
require_once AFFILIATE_LM_PATH . 'includes/class-plugin.php';

/**
 * Plugin Activation Callback.
 */
function activate_plugin(): void {
	if ( false === get_option( 'affiliate_lm_site_domains' ) ) {
		add_option( 'affiliate_lm_site_domains', "thebuyersreviews.com\ndailyhealthsupplement.com\nsupplementmag.com" );
	}
	if ( false === get_option( 'affiliate_lm_batch_size' ) ) {
		add_option( 'affiliate_lm_batch_size', 50 );
	}
	if ( false === get_option( 'affiliate_lm_dry_run' ) ) {
		add_option( 'affiliate_lm_dry_run', 0 );
	}
	if ( false === get_option( 'affiliate_lm_enable_logs' ) ) {
		add_option( 'affiliate_lm_enable_logs', 1 );
	}
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate_plugin' );

/**
 * Bootstrap Plugin Instance.
 */
add_action( 'plugins_loaded', function() {
	Plugin::get_instance();
} );
