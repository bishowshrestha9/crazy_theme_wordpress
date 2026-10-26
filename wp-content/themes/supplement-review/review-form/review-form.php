<?php
/**
 * Plugin Name: Write a Review Form
 * Plugin URI: https://example.com/write-a-review-form
 * Description: Pixel-accurate "Write a Review" submission form with AJAX/no-JS fallback, secure file attachment handling, Custom Post Type storage, admin moderation queue, shortcode [review_form], and Gutenberg block.
 * Version: 1.0.0
 * Author: Antigravity Team
 * Author URI: https://example.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: review-form-plugin
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 7.4
 *
 * @package Review_Form_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define Plugin Constants
define( 'RF_PLUGIN_VERSION', '1.0.0' );
define( 'RF_PLUGIN_FILE', __FILE__ );
define( 'RF_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'RF_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Load Subsystems
require_once RF_PLUGIN_DIR . 'includes/class-rf-cpt.php';
require_once RF_PLUGIN_DIR . 'includes/class-rf-form-handler.php';
require_once RF_PLUGIN_DIR . 'includes/class-rf-admin.php';

/**
 * Main Plugin Initialization Class
 */
class Review_Form_Plugin {

	/**
	 * Bootstraps the plugin.
	 */
	public static function init() {
		// Initialize Subsystems
		RF_Custom_Post_Type::init();
		RF_Form_Handler::init();
		RF_Admin_Moderation::init();

		// Register Assets & Gutenberg Block
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'register_frontend_assets' ) );
		add_action( 'init', array( __CLASS__, 'register_gutenberg_block' ) );
		add_action( 'init', array( __CLASS__, 'load_textdomain' ) );
	}

	/**
	 * Register frontend assets (loaded on-demand when form is present).
	 */
	public static function register_frontend_assets() {
		wp_register_style(
			'rf-form-style',
			RF_PLUGIN_URL . 'assets/css/review-form.css',
			array(),
			RF_PLUGIN_VERSION
		);

		wp_register_script(
			'rf-form-script',
			RF_PLUGIN_URL . 'assets/js/review-form.js',
			array(),
			RF_PLUGIN_VERSION,
			true
		);

		wp_localize_script(
			'rf-form-script',
			'rfData',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	/**
	 * Register Gutenberg Block for insertion via "/review form".
	 */
	public static function register_gutenberg_block() {
		// Register editor script
		wp_register_script(
			'rf-block-editor-script',
			RF_PLUGIN_URL . 'assets/js/review-form-block.js',
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' ),
			RF_PLUGIN_VERSION,
			true
		);

		// Register block type
		register_block_type( RF_PLUGIN_DIR . 'block.json', array(
			'render_callback' => array( 'RF_Form_Handler', 'render_shortcode' ),
		) );
	}

	/**
	 * Load plugin translation domain.
	 */
	public static function load_textdomain() {
		load_plugin_textdomain( 'review-form-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Activation hook callback.
	 */
	public static function on_activation() {
		RF_Custom_Post_Type::register_post_type();
		RF_Custom_Post_Type::register_post_status();
		flush_rewrite_rules();
	}

	/**
	 * Deactivation hook callback.
	 */
	public static function on_deactivation() {
		flush_rewrite_rules();
	}
}

add_action( 'plugins_loaded', array( 'Review_Form_Plugin', 'init' ) );
register_activation_hook( __FILE__, array( 'Review_Form_Plugin', 'on_activation' ) );
register_deactivation_hook( __FILE__, array( 'Review_Form_Plugin', 'on_deactivation' ) );
