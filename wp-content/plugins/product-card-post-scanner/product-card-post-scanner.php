<?php
/**
 * Plugin Name: Product Card Post Scanner & CSV Exporter
 * Plugin URI: https://yourwebsite.com
 * Description: Standalone plugin to scan all WordPress posts, pages, and custom post types for Product Card blocks and export a list of post names and details in CSV format.
 * Version: 1.0.0
 * Author: Bishow Shrestha
 * License: GPL v2 or later
 * Text Domain: product-card-post-scanner
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PCPS_VERSION', '1.0.0');
define('PCPS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PCPS_PLUGIN_URL', plugin_dir_url(__FILE__));

// Require includes
require_once PCPS_PLUGIN_DIR . 'includes/class-scanner.php';

if (is_admin()) {
    require_once PCPS_PLUGIN_DIR . 'includes/class-admin.php';
}
