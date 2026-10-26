<?php
/**
 * Plugin Name: AffiliateX Pros & Cons Converter
 * Plugin URI: https://wordpress.org/plugins/affiliatex-pros-cons-converter/
 * Description: Bulk converts legacy AffiliateX Pros and Cons blocks into Pros and Cons 4 block format.
 * Version: 1.0.0
 * Author: Om Kunwar & Bishow Shrestha
 * Text Domain: affiliatex-pros-cons-converter
 * License: GPLv2 or later
 */

defined( 'ABSPATH' ) || exit;

define( 'APCC_VERSION', '1.0.0' );
define( 'APCC_PLUGIN_FILE', __FILE__ );
define( 'APCC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'APCC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once APCC_PLUGIN_DIR . 'includes/class-apcc-converter.php';

function apcc_init_converter() {
	return APCC_Converter::get_instance();
}
add_action( 'plugins_loaded', 'apcc_init_converter' );
