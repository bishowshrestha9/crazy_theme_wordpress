<?php
/**
 * Plugin Name: WordPress Google Drive Backup
 * Plugin URI: https://example.com/wordpress-google-drive-backup
 * Description: Backup WordPress site and database to Google Drive with OAuth 2.0, scheduling, and restore support.
 * Version: 1.0.0
 * Author: Bishow Shrestha
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-gd-backup
 * Requires PHP: 8.0
 * Requires at least: 5.9
 */

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WP_GD_BACKUP_VERSION', '1.0.0');
define('WP_GD_BACKUP_DIR', plugin_dir_path(__FILE__));
define('WP_GD_BACKUP_URL', plugin_dir_url(__FILE__));
define('WP_GD_BACKUP_BASENAME', plugin_basename(__FILE__));

// Include required modules
require_once WP_GD_BACKUP_DIR . 'includes/class-oauth-handler.php';
require_once WP_GD_BACKUP_DIR . 'includes/class-backup-generator.php';
require_once WP_GD_BACKUP_DIR . 'includes/class-google-drive-uploader.php';
require_once WP_GD_BACKUP_DIR . 'includes/class-scheduler.php';
require_once WP_GD_BACKUP_DIR . 'includes/class-restore-handler.php';
require_once WP_GD_BACKUP_DIR . 'includes/class-admin-page.php';

/**
 * Main Plugin Class
 */
class WP_Google_Drive_Backup
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
        $this->init_hooks();
    }

    private function init_hooks()
    {
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        add_action('admin_init', array($this, 'init_modules'));

        // Scheduler
        add_action('wp_gd_backup_scheduled_event', array($this, 'run_scheduled_backup'));
    }

    public function init_modules()
    {
        if (!is_admin()) {
            return;
        }

        WP_GD_OAuth_Handler::get_instance();
        WP_GD_Scheduler::get_instance();
        WP_GD_Admin_Page::get_instance();
    }

    public function run_scheduled_backup()
    {
        $scheduler = WP_GD_Scheduler::get_instance();
        $scheduler->run_scheduled_backup();
    }

    public function activate()
    {
        global $wpdb;

        // Create backup history table
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'gd_backup_history';

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            backup_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            backup_size bigint(20) DEFAULT 0,
            file_name varchar(255) NOT NULL,
            file_id varchar(255),
            status varchar(50) DEFAULT 'pending',
            backup_type varchar(50) DEFAULT 'full',
            notes text,
            PRIMARY KEY (id),
            KEY backup_date (backup_date)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        // Schedule the cron event
        if (!wp_next_scheduled('wp_gd_backup_scheduled_event')) {
            wp_schedule_event(time(), 'daily', 'wp_gd_backup_scheduled_event');
        }
    }

    public function deactivate()
    {
        wp_clear_scheduled_hook('wp_gd_backup_scheduled_event');
    }
}

// Initialize plugin
add_action('plugins_loaded', array('WP_Google_Drive_Backup', 'get_instance'));
