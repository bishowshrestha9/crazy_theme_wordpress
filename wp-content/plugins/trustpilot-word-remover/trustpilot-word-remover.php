<?php
/**
 * Plugin Name: Trustpilot Word Scanner & Remover
 * Plugin URI: https://example.com/trustpilot-word-remover
 * Description: Scans all posts, pages, and custom post types for occurrences of the word "Trustpilot", presents them in a detailed list with context snippets, and provides smart removal/replacement options with punctuation cleanup.
 * Version: 1.0.0
 * Author: Antigravity AI
 * Text Domain: trustpilot-word-remover
 * License: GPL v2 or later
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define Plugin Constants
define('TWR_VERSION', '1.0.0');
define('TWR_PLUGIN_FILE', __FILE__);
define('TWR_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TWR_PLUGIN_URL', plugin_dir_url(__FILE__));

// Require Core Classes
require_once TWR_PLUGIN_DIR . 'includes/class-twr-scanner.php';
require_once TWR_PLUGIN_DIR . 'includes/class-twr-remover.php';
require_once TWR_PLUGIN_DIR . 'includes/class-twr-admin.php';

/**
 * Initialize Plugin
 */
function twr_init_plugin() {
    if (is_admin()) {
        $admin = new TWR_Admin();
        $admin->init();
    }
}
add_action('plugins_loaded', 'twr_init_plugin');
