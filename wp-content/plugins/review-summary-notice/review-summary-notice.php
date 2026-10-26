<?php
/**
 * Plugin Name: Review Summary Notice
 * Description: Gutenberg Review Summary Notice block with dynamic ACF Product Name integration, custom styling, and AffiliateX Notice block converter.
 * Version: 1.0.1
 * Author: Bishow Shrestha
 * Text Domain: review-summary-notice
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RSN_VERSION', '1.0.1' );
define( 'RSN_PLUGIN_FILE', __FILE__ );
define( 'RSN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'RSN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Load classes
require_once RSN_PLUGIN_DIR . 'includes/class-rsn-block.php';
require_once RSN_PLUGIN_DIR . 'includes/class-rsn-converter.php';

/**
 * Initialize Plugin
 */
function rsn_init_plugin() {
	RSN_Block::get_instance();
	RSN_Converter::get_instance();
}
add_action( 'plugins_loaded', 'rsn_init_plugin' );
