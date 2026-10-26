<?php
/**
 * Plugin Name: Duplicate Post Slug Detector & CSV Exporter
 * Plugin URI: https://yourwebsite.com
 * Description: Standalone plugin to scan all WordPress posts, pages, and custom post types, detect duplicate postings by comparing post slugs (e.g. slug vs slug-2), and export the duplicate post list in CSV format.
 * Version: 1.0.0
 * Author: Bishow Shrestha
 * License: GPL v2 or later
 * Text Domain: duplicate-post-detector
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('DPD_VERSION', '1.0.0');
define('DPD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('DPD_PLUGIN_URL', plugin_dir_url(__FILE__));

// Require include files
require_once DPD_PLUGIN_DIR . 'includes/class-detector.php';

if (is_admin()) {
    require_once DPD_PLUGIN_DIR . 'includes/class-admin.php';
}
