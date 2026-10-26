<?php
/**
 * Plugin Name: Supplement Page Builder
 * Plugin URI: https://example.com/supplement-page-builder
 * Description: A flexible page builder for creating supplement review pages with block-based components (Top Choices, Comparison Table, FAQs, etc.)
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * Text Domain: supplement-page-builder
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SPB_VERSION', '1.0.0');
define('SPB_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SPB_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once SPB_PLUGIN_DIR . 'includes/class-custom-post-type.php';
require_once SPB_PLUGIN_DIR . 'includes/class-meta-boxes.php';
require_once SPB_PLUGIN_DIR . 'includes/class-template-loader.php';
require_once SPB_PLUGIN_DIR . 'includes/class-blocks.php';

/**
 * Main Plugin Class
 */
class Supplement_Page_Builder
{
    /**
     * Instance of this class
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        // Initialize classes immediately (they'll hook into 'init' themselves)
        SPB_Custom_Post_Type::get_instance();
        SPB_Meta_Boxes::get_instance();
        SPB_Template_Loader::get_instance();
        SPB_Blocks::get_instance();
        
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        
        // Register UTM parameters as query vars so WordPress accepts them
        add_filter('query_vars', array($this, 'register_utm_query_vars'));
    }
    
    /**
     * Register UTM parameters as query variables
     */
    public function register_utm_query_vars($vars)
    {
        $vars[] = 'utm_page';
        $vars[] = 'utm_source';
        $vars[] = 'utm_medium';
        $vars[] = 'utm_campaign';
        $vars[] = 'utm_term';
        $vars[] = 'utm_content';
        return $vars;
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook)
    {
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }

        global $post;
        if (!$post || $post->post_type !== 'supplement_page') {
            return;
        }

        wp_enqueue_style(
            'spb-admin-styles',
            SPB_PLUGIN_URL . 'admin/css/admin-styles.css',
            array(),
            SPB_VERSION
        );

        wp_enqueue_script(
            'spb-admin-scripts',
            SPB_PLUGIN_URL . 'admin/js/admin-scripts.js',
            array('jquery'),
            SPB_VERSION,
            true
        );
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets()
    {
        if (!is_singular('supplement_page')) {
            return;
        }

        wp_enqueue_style(
            'spb-styles',
            SPB_PLUGIN_URL . 'assets/css/supplement-styles.css',
            array(),
            SPB_VERSION
        );

        wp_enqueue_script(
            'spb-scripts',
            SPB_PLUGIN_URL . 'assets/js/supplement-scripts.js',
            array('jquery'),
            SPB_VERSION,
            true
        );
    }
}

// Initialize plugin
function spb_init()
{
    return Supplement_Page_Builder::get_instance();
}

add_action('plugins_loaded', 'spb_init');

/**
 * Plugin activation hook
 */
function spb_activate()
{
    // Initialize the custom post type
    SPB_Custom_Post_Type::get_instance();
    
    // Flush rewrite rules
    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'spb_activate');

/**
 * Plugin deactivation hook
 */
function spb_deactivate()
{
    // Flush rewrite rules
    flush_rewrite_rules();
}

register_deactivation_hook(__FILE__, 'spb_deactivate');
