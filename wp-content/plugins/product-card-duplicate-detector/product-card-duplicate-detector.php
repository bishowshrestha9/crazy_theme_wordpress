<?php
/**
 * Plugin Name: Product Card Duplicate Product Name Detector & Exporter
 * Plugin URI: https://yourwebsite.com
 * Description: Standalone plugin to scan all WordPress posts/pages, detect duplicate product names used across Product Cards, and export the duplicate list to CSV.
 * Version: 1.0.0
 * Author: Bishow Shrestha
 * License: GPL v2 or later
 * Text Domain: product-card-duplicate-detector
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PCDD_VERSION', '1.0.0');
define('PCDD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PCDD_PLUGIN_URL', plugin_dir_url(__FILE__));

// Require include files
require_once PCDD_PLUGIN_DIR . 'includes/class-detector.php';

if (is_admin()) {
    require_once PCDD_PLUGIN_DIR . 'includes/class-admin.php';
}
