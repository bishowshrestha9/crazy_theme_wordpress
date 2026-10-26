<?php
/**
 * Plugin Name: Site Info Plugin
 * Plugin URI: https://example.com/site-info-plugin
 * Description: Comprehensive site analysis tool that extracts all links, products, affiliates, and detailed site information
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * Text Domain: site-info-plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SIP_VERSION', '1.0.0');
define('SIP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SIP_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once SIP_PLUGIN_DIR . 'includes/class-admin-page.php';
require_once SIP_PLUGIN_DIR . 'includes/class-site-analyzer.php';
require_once SIP_PLUGIN_DIR . 'includes/class-ajax-handler.php';
require_once SIP_PLUGIN_DIR . 'includes/class-csv-exporter.php';

/**
 * Main Plugin Class
 */
class Site_Info_Plugin
{
    private static $instance = null;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        SIP_Admin_Page::get_instance();
        SIP_Ajax_Handler::get_instance();
        
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    public function enqueue_admin_assets($hook)
    {
        if ('toplevel_page_site-info-plugin' !== $hook) {
            return;
        }
        
        wp_enqueue_style(
            'sip-admin-styles',
            SIP_PLUGIN_URL . 'assets/css/admin-styles.css',
            array(),
            SIP_VERSION
        );

        wp_enqueue_script(
            'sip-admin-scripts',
            SIP_PLUGIN_URL . 'assets/js/admin-scripts.js',
            array('jquery'),
            SIP_VERSION,
            true
        );
        
        wp_localize_script('sip-admin-scripts', 'sipAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('sip_fetch_info')
        ));
    }
}

// Initialize plugin
function sip_init()
{
    return Site_Info_Plugin::get_instance();
}

add_action('plugins_loaded', 'sip_init');

// Activation hook
// Activation hook
// Activation hook
function sip_activate()
{
    // Nothing to do on activation
}

register_activation_hook(__FILE__, 'sip_activate');

// Deactivation hook
function sip_deactivate()
{
    // Nothing to do on deactivation
}

register_deactivation_hook(__FILE__, 'sip_deactivate');
