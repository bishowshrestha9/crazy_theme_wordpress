<?php
/**
 * Plugin Name: Sources & FAQ Accordion Sections
 * Plugin URI: https://example.com/sources-faq-sections
 * Description: Pixel-accurate Sources citation card and accessible FAQ accordion components with Gutenberg blocks (/sources, /faq), shortcodes, FAQPage JSON-LD schema, and zero jQuery dependencies.
 * Version: 1.0.0
 * Author: Antigravity Team
 * Author URI: https://example.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: sources-faq-sections
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package Sources_FAQ_Sections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define Plugin Constants
define( 'SF_PLUGIN_VERSION', '1.0.0' );
define( 'SF_PLUGIN_FILE', __FILE__ );
define( 'SF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Load Subsystems
require_once SF_PLUGIN_DIR . 'includes/class-sf-sources.php';
require_once SF_PLUGIN_DIR . 'includes/class-sf-faq.php';

/**
 * Main Sources & FAQ Plugin Bootstrap
 */
class Sources_FAQ_Plugin {

	/**
	 * Bootstraps the plugin.
	 */
	public static function init() {
		// Register Shortcodes
		add_shortcode( 'sources_section', array( 'SF_Sources_Handler', 'render' ) );
		add_shortcode( 'faq_section', array( 'SF_FAQ_Handler', 'render' ) );

		// Register Scripts & Blocks
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_frontend_assets' ) );
		add_action( 'init', array( __CLASS__, 'register_gutenberg_blocks' ) );
		add_action( 'init', array( __CLASS__, 'load_textdomain' ) );
	}

	/**
	 * Register frontend assets (enqueued conditionally only when shortcode or block is used).
	 */
	public static function register_frontend_assets() {
		wp_register_style(
			'sources-faq-style',
			SF_PLUGIN_URL . 'assets/css/sources-faq.css',
			array(),
			SF_PLUGIN_VERSION
		);

		wp_register_script(
			'sources-faq-script',
			SF_PLUGIN_URL . 'assets/js/sources-faq.js',
			array(),
			SF_PLUGIN_VERSION,
			true
		);
	}

	/**
	 * Register Gutenberg blocks for /sources and /faq.
	 */
	public static function register_gutenberg_blocks() {
		// Register the block editor script
		wp_register_script(
			'sources-faq-blocks-script',
			SF_PLUGIN_URL . 'assets/js/blocks.js',
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' ),
			SF_PLUGIN_VERSION,
			true
		);

		// Register Sources Block (/sources)
		register_block_type( SF_PLUGIN_DIR . 'blocks/sources/block.json', array(
			'render_callback' => array( 'SF_Sources_Handler', 'render' ),
		) );

		// Register FAQ Block (/faq)
		register_block_type( SF_PLUGIN_DIR . 'blocks/faq/block.json', array(
			'render_callback' => array( 'SF_FAQ_Handler', 'render' ),
		) );
	}

	/**
	 * Load translation textdomain.
	 */
	public static function load_textdomain() {
		load_plugin_textdomain( 'sources-faq-sections', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
}

add_action( 'plugins_loaded', array( 'Sources_FAQ_Plugin', 'init' ) );
