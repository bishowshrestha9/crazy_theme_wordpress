<?php
/**
 * AJAX Handler Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SIP_Ajax_Handler
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
        add_action('wp_ajax_sip_fetch_site_info', array($this, 'fetch_site_info'));
        add_action('wp_ajax_sip_export_csv', array($this, 'export_csv'));
    }

    public function fetch_site_info()
    {
        check_ajax_referer('sip_fetch_info', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'You do not have permission to perform this action.'));
        }

        try {
            // Create analyzer instance
            $analyzer = new SIP_Site_Analyzer();
            
            // Analyze the site
            $site_data = $analyzer->analyze();

            // Save the data
            update_option('sip_site_data', $site_data);
            update_option('sip_last_fetched', current_time('mysql'));

            wp_send_json_success(array(
                'message' => 'Site information fetched successfully! Refresh the page to see results.',
                'data' => $site_data,
            ));

        } catch (Exception $e) {
            wp_send_json_error(array('message' => 'Error: ' . $e->getMessage()));
        }
    }
    
    public function export_csv()
    {
        check_ajax_referer('sip_export_csv', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die('You do not have permission to perform this action.');
        }

        $site_data = get_option('sip_site_data', array());
        
        if (empty($site_data)) {
            wp_die('No data available to export. Please fetch site info first.');
        }

        require_once SIP_PLUGIN_DIR . 'includes/class-csv-exporter.php';
        $exporter = new SIP_CSV_Exporter($site_data);
        $exporter->export();
    }
}
