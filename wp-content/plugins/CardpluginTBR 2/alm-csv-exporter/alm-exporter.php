<?php
/**
 * Plugin Name: ALM CSV Exporter
 * Description: Export links from Affiliate Link Manager into a CSV format ready for Pretty Links or internal use.
 * Version: 1.0.0
 * Author: Bishow Shrestha
 */

if (!defined('ABSPATH'))
    exit;

class ALM_CSV_Exporter
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_post_alm_export_csv_action', [$this, 'handle_export']);
    }

    public function add_menu()
    {
        add_management_page(
            'ALM Exporter',
            'ALM Exporter',
            'manage_options',
            'alm-exporter',
            [$this, 'render_page']
        );
    }

    public function render_page()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'alm_links';
        $count = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table)) ? $wpdb->get_var("SELECT COUNT(*) FROM $table") : 0;
        ?>
        <div class="wrap">
            <h1>ALM CSV Exporter</h1>
            <p>Export all links from the <strong>Affiliate Link Manager</strong> table into a clean CSV file.</p>

            <div class="card"
                style="max-width: 500px; padding: 20px; background: #fff; border: 1px solid #ccd0d4; margin-top: 20px;">
                <h2>Export Settings</h2>
                <p>Links found: <strong>
                        <?php echo intval($count); ?>
                    </strong></p>
                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                    <input type="hidden" name="action" value="alm_export_csv_action">
                    <?php wp_nonce_field('alm_export_nonce'); ?>
                    <button type="submit" class="button button-primary button-large" <?php echo $count == 0 ? 'disabled' : ''; ?>>Download CSV Now</button>
                </form>
            </div>

            <div style="margin-top: 20px;">
                <h3>CSV Columns in this export (Optimized for Pretty Links):</h3>
                <ul>
                    <li><strong>URL:</strong> The final affiliate link destination</li>
                    <li><strong>Slug:</strong> The pretty path (e.g. volume-pills)</li>
                    <li><strong>Name:</strong> The title of your link</li>
                </ul>
            </div>
        </div>
        <?php
    }

    public function handle_export()
    {
        check_admin_referer('alm_export_nonce');
        if (!current_user_can('manage_options'))
            wp_die('Access denied');

        global $wpdb;
        $table = $wpdb->prefix . 'alm_links';

        $links = $wpdb->get_results("SELECT * FROM $table ORDER BY id ASC");

        $filename = 'pretty-links-import-' . date('Y-m-d') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);

        $output = fopen('php://output', 'w');

        // OFFICIAL PRETTY LINKS HEADERS (LOWERCASE AS PER DOCUMENTATION)
        fputcsv($output, ['url', 'slug', 'name', 'redirect_type', 'track_me', 'nofollow']);

        foreach ($links as $link) {
            fputcsv($output, [
                $link->target_url, // url (Required)
                $link->slug,       // slug
                $link->name,       // name
                '301',             // redirect_type
                '1',               // track_me
                '0'                // nofollow
            ]);
        }

        fclose($output);
        exit;
    }
}

new ALM_CSV_Exporter();
