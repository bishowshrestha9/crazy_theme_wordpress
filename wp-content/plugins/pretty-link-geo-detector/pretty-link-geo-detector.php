<?php
/**
 * Plugin Name: Pretty Links GEO-Redirect Detector & Exporter
 * Description: Detects if Pretty Links have GEO-redirect (country-specific) rules configured and exports complete report in CSV.
 * Version: 1.0.0
 * Author: Antigravity
 * Text Domain: pretty-link-geo-detector
 */

if (!defined('ABSPATH')) {
    exit;
}

define('PLGD_VERSION', '1.0.0');
define('PLGD_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PLGD_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once PLGD_PLUGIN_DIR . 'includes/class-geo-detector.php';
require_once PLGD_PLUGIN_DIR . 'includes/class-csv-exporter.php';

class PLGD_Geo_Detector_Plugin
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
        add_action('admin_menu', array($this, 'register_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
        
        // AJAX Handlers
        add_action('wp_ajax_plgd_scan_links', array($this, 'ajax_scan_links'));
        add_action('wp_ajax_plgd_export_csv', array($this, 'ajax_export_csv'));
        add_action('admin_post_plgd_download_csv', array($this, 'handle_direct_csv_download'));
    }

    public function register_admin_menu()
    {
        add_management_page(
            __('Pretty Links GEO Detector', 'pretty-link-geo-detector'),
            __('Pretty Links GEO Detector', 'pretty-link-geo-detector'),
            'manage_options',
            'pretty-link-geo-detector',
            array($this, 'render_admin_page')
        );
    }

    public function enqueue_admin_assets($hook)
    {
        if ('tools_page_pretty-link-geo-detector' !== $hook) {
            return;
        }

        wp_enqueue_style('plgd-admin-css', PLGD_PLUGIN_URL . 'assets/css/admin.css', array(), PLGD_VERSION);
        wp_enqueue_script('plgd-admin-js', PLGD_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), PLGD_VERSION, true);

        wp_localize_script('plgd-admin-js', 'plgdData', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('plgd_nonce'),
            'download_url' => admin_url('admin-post.php?action=plgd_download_csv&_wpnonce=' . wp_create_nonce('plgd_download'))
        ));
    }

    public function render_admin_page()
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        $detector = new PLGD_Geo_Detector();
        $table_check = $detector->check_tables_exist();

        ?>
        <div class="wrap plgd-container">
            <div class="plgd-header">
                <div class="plgd-header-title">
                    <span class="dashicons dashicons-location-alt plgd-icon"></span>
                    <div>
                        <h1><?php _e('Pretty Links GEO-Redirect Detector & Exporter', 'pretty-link-geo-detector'); ?></h1>
                        <p class="plgd-subtitle"><?php _e('Detect country-based redirect rules on your Pretty Links and export detailed audit reports in CSV.', 'pretty-link-geo-detector'); ?></p>
                    </div>
                </div>
            </div>

            <?php if (!$table_check['success']) : ?>
                <div class="notice notice-warning plgd-notice">
                    <p><strong><?php _e('Notice:', 'pretty-link-geo-detector'); ?></strong> <?php echo esc_html($table_check['message']); ?></p>
                </div>
            <?php endif; ?>

            <div class="plgd-card plgd-control-panel">
                <div class="plgd-actions-row">
                    <div class="plgd-btn-group">
                        <button type="button" id="plgd-start-scan" class="button button-primary button-hero plgd-btn-main">
                            <span class="dashicons dashicons-search"></span> <?php _e('Scan Pretty Links for GEO-Redirects', 'pretty-link-geo-detector'); ?>
                        </button>
                        <button type="button" id="plgd-export-btn" class="button button-secondary button-hero plgd-btn-export" disabled>
                            <span class="dashicons dashicons-download"></span> <?php _e('Export Results to CSV', 'pretty-link-geo-detector'); ?>
                        </button>
                    </div>
                    <div class="plgd-filter-group" id="plgd-filter-container" style="display:none;">
                        <label for="plgd-status-filter"><strong><?php _e('Filter:', 'pretty-link-geo-detector'); ?></strong></label>
                        <select id="plgd-status-filter">
                            <option value="all"><?php _e('All Links', 'pretty-link-geo-detector'); ?></option>
                            <option value="geo"><?php _e('GEO-Redirect Only', 'pretty-link-geo-detector'); ?></option>
                            <option value="none"><?php _e('No GEO-Redirect', 'pretty-link-geo-detector'); ?></option>
                        </select>
                    </div>
                </div>

                <div id="plgd-progress-container" style="display:none;" class="plgd-progress-box">
                    <div class="plgd-progress-bar-wrapper">
                        <div id="plgd-progress-bar" class="plgd-progress-bar"></div>
                    </div>
                    <p id="plgd-progress-status"><?php _e('Initializing scanner...', 'pretty-link-geo-detector'); ?></p>
                </div>
            </div>

            <div id="plgd-summary-cards" class="plgd-stats-grid" style="display:none;">
                <div class="plgd-stat-card stat-total">
                    <span class="stat-number" id="stat-total-count">0</span>
                    <span class="stat-label"><?php _e('Total Links Scanned', 'pretty-link-geo-detector'); ?></span>
                </div>
                <div class="plgd-stat-card stat-geo">
                    <span class="stat-number" id="stat-geo-count">0</span>
                    <span class="stat-label"><?php _e('GEO-Redirect Links Detected', 'pretty-link-geo-detector'); ?></span>
                </div>
                <div class="plgd-stat-card stat-standard">
                    <span class="stat-number" id="stat-standard-count">0</span>
                    <span class="stat-label"><?php _e('Standard Links (No GEO)', 'pretty-link-geo-detector'); ?></span>
                </div>
            </div>

            <div id="plgd-results-card" class="plgd-card plgd-results-box" style="display:none;">
                <div class="plgd-results-header">
                    <h2><?php _e('Detection Results', 'pretty-link-geo-detector'); ?></h2>
                    <input type="text" id="plgd-search-input" placeholder="<?php _e('Search slug or target URL...', 'pretty-link-geo-detector'); ?>" class="regular-text">
                </div>

                <div class="plgd-table-responsive">
                    <table class="wp-list-table widefat fixed striped" id="plgd-results-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;"><?php _e('ID', 'pretty-link-geo-detector'); ?></th>
                                <th style="width: 180px;"><?php _e('Product Name', 'pretty-link-geo-detector'); ?></th>
                                <th style="width: 180px;"><?php _e('Pretty Link / Slug', 'pretty-link-geo-detector'); ?></th>
                                <th><?php _e('Default Target URL', 'pretty-link-geo-detector'); ?></th>
                                <th><?php _e('GEO Target Rules / Details', 'pretty-link-geo-detector'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="plgd-results-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }

    public function ajax_scan_links()
    {
        check_ajax_referer('plgd_nonce', 'security');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(__('Unauthorized request', 'pretty-link-geo-detector'));
        }

        $detector = new PLGD_Geo_Detector();
        $results = $detector->scan_all_links();

        // Store in transient for export
        set_transient('plgd_last_scan_results_' . get_current_user_id(), $results, HOUR_IN_SECONDS);

        wp_send_json_success($results);
    }

    public function handle_direct_csv_download()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('Unauthorized request', 'pretty-link-geo-detector'));
        }

        check_admin_referer('plgd_download');

        $results = get_transient('plgd_last_scan_results_' . get_current_user_id());
        if (!$results || empty($results['links'])) {
            $detector = new PLGD_Geo_Detector();
            $results = $detector->scan_all_links();
        }

        PLGD_CSV_Exporter::output_csv($results['links']);
        exit;
    }
}

PLGD_Geo_Detector_Plugin::get_instance();
