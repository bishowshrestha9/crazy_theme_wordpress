<?php
/**
 * Plugin Name: Product Card Plugin TBR (Beta)
 * Plugin URI: https://yourwebsite.com
 * Description: Dynamic product card Gutenberg block with status-based themes (Green for Legit, Red for Scam)
 * Version: 2.4.0
 * Author: Bishow Shrestha
 * License: GPL v2 or later
 * Text Domain: product-card-plugin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PCP_VERSION', '2.4.0');
define('PCP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PCP_PLUGIN_URL', plugin_dir_url(__FILE__));

class ProductCardPlugin
{

    public function __construct()
    {
        // Enqueue styles and scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));

        // Register Gutenberg block
        add_action('init', array($this, 'register_gutenberg_block'));

        // REST API and AJAX endpoints for Buy Later
        add_action('rest_api_init', array($this, 'register_rest_routes'));
        add_action('wp_ajax_pcpx_buy_later', array($this, 'handle_ajax_buy_later'));
        add_action('wp_ajax_nopriv_pcpx_buy_later', array($this, 'handle_ajax_buy_later'));

        // Admin menu and CSV export for lead management
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'handle_csv_export'));

        // Automatically ensure table exists
        add_action('admin_init', array('ProductCardPlugin', 'check_and_create_table'));

        // Background mailer hook
        add_action('pcpx_send_buy_later_emails', array($this, 'async_send_buy_later_emails'));
    }

    /**
     * Ensure dedicated database table exists
     */
    public static function check_and_create_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'pcpx_leads';
        if ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $table_name)) !== $table_name) {
            self::activate_plugin();
        }
    }

    /**
     * Activate Plugin: Create dedicated database table & migrate legacy leads
     */
    public static function activate_plugin()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'pcpx_leads';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
            timestamp datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            email varchar(100) NOT NULL,
            product_name varchar(255) NOT NULL,
            price varchar(50) DEFAULT '' NOT NULL,
            ip varchar(100) DEFAULT '' NOT NULL,
            PRIMARY KEY  (id),
            KEY email (email)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // Migrate any legacy leads stored in wp_options
        self::migrate_legacy_leads();
    }

    /**
     * Migrate existing leads from wp_options table (if any exist)
     */
    private static function migrate_legacy_leads()
    {
        global $wpdb;
        $legacyLeads = get_option('pcpx_buy_later_leads');
        if (is_array($legacyLeads) && !empty($legacyLeads)) {
            $table_name = $wpdb->prefix . 'pcpx_leads';
            foreach ($legacyLeads as $lead) {
                if (!empty($lead['email'])) {
                    $wpdb->insert(
                        $table_name,
                        array(
                            'email'        => sanitize_email($lead['email']),
                            'product_name' => isset($lead['product_name']) ? sanitize_text_field($lead['product_name']) : 'Product',
                            'price'        => isset($lead['price']) ? sanitize_text_field($lead['price']) : '',
                            'timestamp'    => isset($lead['timestamp']) ? sanitize_text_field($lead['timestamp']) : current_time('mysql'),
                            'ip'           => isset($lead['ip']) ? sanitize_text_field($lead['ip']) : '',
                        ),
                        array('%s', '%s', '%s', '%s', '%s')
                    );
                }
            }
            delete_option('pcpx_buy_later_leads');
        }
    }

    /**
     * Enqueue CSS and JS for frontend
     */
    public function enqueue_assets()
    {
        wp_enqueue_style('product-card-styles', PCP_PLUGIN_URL . 'assets/css/product-card.css', array(), PCP_VERSION);
        wp_enqueue_script('product-card-scripts', PCP_PLUGIN_URL . 'assets/js/product-card.js', array('jquery'), PCP_VERSION, true);

        // Localize script with API endpoints and nonces
        wp_localize_script('product-card-scripts', 'pcpxSettings', array(
            'ajaxUrl'   => admin_url('admin-ajax.php'),
            'restUrl'   => rest_url('product-card/v1/buy-later'),
            'nonce'     => wp_create_nonce('wp_rest'),
            'ajaxNonce' => wp_create_nonce('pcpx_buy_later_ajax_nonce'),
        ));
    }

    /**
     * Enqueue block editor assets
     */
    public function enqueue_block_editor_assets()
    {
        wp_enqueue_style('product-card-editor-styles', PCP_PLUGIN_URL . 'assets/css/product-card.css', array(), PCP_VERSION);
    }

    /**
     * Register Gutenberg block
     */
    public function register_gutenberg_block()
    {
        // Register block script
        wp_register_script(
            'product-card-block',
            PCP_PLUGIN_URL . 'assets/js/block.js',
            array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
            PCP_VERSION
        );

        // Register the block with all attributes
        register_block_type('product-card-plugin/product-card', array(
            'editor_script' => 'product-card-block',
            'render_callback' => array($this, 'render_product_card_block'),
            'attributes' => array(
                'status' => array(
                    'type' => 'string',
                    'default' => 'legit'
                ),
                'productName' => array(
                    'type' => 'string',
                    'default' => 'Gutoptim'
                ),
                'productCategory' => array(
                    'type' => 'string',
                    'default' => 'Supplement'
                ),
                'imageUrl' => array(
                    'type' => 'string',
                    'default' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=800'
                ),
                'userExperience' => array(
                    'type' => 'string',
                    'default' => 'Fewer night bathroom trips, stronger urine flow, and better sleep quality'
                ),
                'expertReview1' => array(
                    'type' => 'string',
                    'default' => 'Triple Therapy combines heat, massage, and compression to improve circulation, ease soreness, and support daily recovery over time.'
                ),
                'expertReview2' => array(
                    'type' => 'string',
                    'default' => 'Customizable settings with five heat levels and three massage modes tailor relief intensity to your comfort and routine.'
                ),
                'expertReview3' => array(
                    'type' => 'string',
                    'default' => 'Portable rechargeable design makes consistent use easy at home, at work, or while traveling between daily appointments.'
                ),
                'verdict' => array(
                    'type' => 'string',
                    'default' => 'A reliable pick with consistent results, practical value, and noticeable improvements for daily relief.'
                ),
                'rating' => array(
                    'type' => 'string',
                    'default' => '4.9'
                ),
                'readReviewsUrl' => array(
                    'type' => 'string',
                    'default' => '#'
                ),
                'price' => array(
                    'type' => 'string',
                    'default' => '69'
                ),
                'originalPrice' => array(
                    'type' => 'string',
                    'default' => '99'
                ),
                'currency' => array(
                    'type' => 'string',
                    'default' => '$'
                ),
                'ctaLabel' => array(
                    'type' => 'string',
                    'default' => 'Buy Now'
                ),
                'ctaSubText' => array(
                    'type' => 'string',
                    'default' => ''
                ),
                'ctaUrl' => array(
                    'type' => 'string',
                    'default' => '#'
                ),
                'buyLaterLabel' => array(
                    'type' => 'string',
                    'default' => 'Buy Later'
                ),
                'bestFor' => array(
                    'type' => 'string',
                    'default' => ''
                ),
                'headerColor' => array(
                    'type' => 'string',
                    'default' => '#000000'
                ),
                'reviewSummary' => array(
                    'type' => 'string',
                    'default' => ''
                ),
                'cardTheme' => array(
                    'type' => 'string',
                    'default' => 'default'
                ),
            )

        ));
    }

    /**
     * Register REST API routes
     */
    public function register_rest_routes()
    {
        register_rest_route('product-card/v1', '/buy-later', array(
            'methods'             => 'POST',
            'callback'            => array($this, 'handle_rest_buy_later'),
            'permission_callback' => '__return_true',
        ));
    }

    /**
     * REST API callback for capturing email
     */
    public function handle_rest_buy_later($request)
    {
        $params = $request->get_json_params();
        if (empty($params)) {
            $params = $request->get_body_params();
        }

        $email = isset($params['email']) ? sanitize_email(trim($params['email'])) : '';
        $productName = isset($params['product_name']) ? sanitize_text_field(trim($params['product_name'])) : '';
        $price = isset($params['price']) ? sanitize_text_field(trim($params['price'])) : '';

        return $this->process_buy_later_lead($email, $productName, $price);
    }

    /**
     * AJAX callback for capturing email
     */
    public function handle_ajax_buy_later()
    {
        $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
        if (!wp_verify_nonce($nonce, 'pcpx_buy_later_ajax_nonce') && !wp_verify_nonce($nonce, 'wp_rest')) {
            wp_send_json_error(array('message' => 'Security check failed. Invalid nonce.'), 403);
            return;
        }

        $email = isset($_POST['email']) ? sanitize_email(trim($_POST['email'])) : '';
        $productName = isset($_POST['product_name']) ? sanitize_text_field(trim($_POST['product_name'])) : '';
        $price = isset($_POST['price']) ? sanitize_text_field(trim($_POST['price'])) : '';

        $response = $this->process_buy_later_lead($email, $productName, $price);

        if (is_wp_error($response)) {
            wp_send_json_error(array('message' => $response->get_error_message()), 400);
        } else {
            wp_send_json_success($response->get_data());
        }
    }

    /**
     * Process, validate, and store the Buy Later lead into custom database table
     */
    private function process_buy_later_lead($email, $productName, $price)
    {
        global $wpdb;

        if (empty($email) || !is_email($email)) {
            return new WP_Error('invalid_email', 'Please enter a valid email address.', array('status' => 400));
        }

        // Ensure database table exists on the fly
        self::check_and_create_table();

        $table_name = $wpdb->prefix . 'pcpx_leads';
        $lead = array(
            'email'        => $email,
            'product_name' => $productName ?: 'Product',
            'price'        => $price,
            'timestamp'    => current_time('mysql'),
            'ip'           => isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '',
        );

        // Insert row safely into custom DB table using $wpdb->insert()
        $inserted = $wpdb->insert(
            $table_name,
            array(
                'email'        => $lead['email'],
                'product_name' => $lead['product_name'],
                'price'        => $lead['price'],
                'timestamp'    => $lead['timestamp'],
                'ip'           => $lead['ip'],
            ),
            array('%s', '%s', '%s', '%s', '%s')
        );

        if ($inserted === false) {
            return new WP_Error('db_insert_error', 'Failed to save lead to the database.', array('status' => 500));
        }

        $lead['id'] = $wpdb->insert_id;

        // Extensible action for third-party integrations (e.g., Mailchimp, CRM, zapier)
        do_action('pcpx_buy_later_lead_saved', $lead);

        // Schedule background email sending so AJAX/REST response is instant (< 50ms)
        wp_schedule_single_event(time(), 'pcpx_send_buy_later_emails', array($lead));
        if (function_exists('spawn_cron')) {
            @spawn_cron();
        }
        register_shutdown_function(array($this, 'async_send_buy_later_emails'), $lead);

        return rest_ensure_response(array(
            'success' => true,
            'message' => "We've saved your reminder! We'll send you an email soon.",
            'lead'    => array(
                'id'           => $lead['id'],
                'email'        => $email,
                'product_name' => $lead['product_name']
            )
        ));
    }

    /**
     * Asynchronously send email notifications in the background
     */
    public function async_send_buy_later_emails($lead)
    {
        static $sent_leads = array();

        if (empty($lead) || !is_array($lead) || empty($lead['email'])) {
            return;
        }

        // Deduplicate execution if both shutdown function and WP-Cron trigger
        $lead_key = $lead['email'] . '_' . (isset($lead['id']) ? $lead['id'] : $lead['timestamp']);
        if (isset($sent_leads[$lead_key])) {
            return;
        }
        $sent_leads[$lead_key] = true;

        // If fastcgi_finish_request exists, close HTTP socket immediately to browser
        if (function_exists('fastcgi_finish_request')) {
            @fastcgi_finish_request();
        }

        $email       = $lead['email'];
        $productName = isset($lead['product_name']) ? $lead['product_name'] : 'Product';
        $price       = isset($lead['price']) ? $lead['price'] : '';
        $timestamp   = isset($lead['timestamp']) ? $lead['timestamp'] : current_time('mysql');
        $ip          = isset($lead['ip']) ? $lead['ip'] : '';

        $siteName   = get_bloginfo('name');
        $adminEmail = get_option('admin_email');
        $siteUrl    = site_url();

        // Standardized headers with valid From address to ensure delivery to $email
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . esc_html($siteName) . ' <' . sanitize_email($adminEmail) . '>',
            'Reply-To: ' . sanitize_email($adminEmail)
        );

        // 1. Send HTML reminder email directly to the collected user email address ($email)
        $userSubject = sprintf('Your Product Reminder for %s', $productName);
        $userHtmlBody = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>' . esc_html($userSubject) . '</title>
        </head>
        <body style="font-family: -apple-system, BlinkMacSystemFont, \'Segoe UI\', Roboto, Helvetica, Arial, sans-serif; background-color: #f4f5f7; margin: 0; padding: 20px; color: #333333;">
            <div style="max-width: 560px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                <div style="background-color: #0f172a; padding: 24px; text-align: center; color: #ffffff;">
                    <h2 style="margin: 0; font-size: 22px; font-weight: 700;">' . esc_html($siteName) . '</h2>
                </div>
                <div style="padding: 30px;">
                    <h3 style="margin-top: 0; color: #0f172a; font-size: 18px;">Product Reminder Saved!</h3>
                    <p style="font-size: 15px; line-height: 1.5; color: #475569;">
                        Hello, thank you for saving <strong>' . esc_html($productName) . '</strong> for later! Here are your saved product details:
                    </p>
                    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 16px; margin: 20px 0;">
                        <p style="margin: 0 0 8px 0; font-size: 14px;"><strong>Product:</strong> ' . esc_html($productName) . '</p>
                        ' . ($price ? '<p style="margin: 0 0 8px 0; font-size: 14px;"><strong>Price:</strong> ' . esc_html($price) . '</p>' : '') . '
                        <p style="margin: 0; font-size: 14px;"><strong>Saved On:</strong> ' . esc_html($timestamp) . '</p>
                    </div>
                    <div style="text-align: center; margin-top: 25px;">
                        <a href="' . esc_url($siteUrl) . '" style="display: inline-block; background-color: #10b981; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 6px; font-weight: 600; font-size: 15px;">Return to Site & Buy Now</a>
                    </div>
                </div>
                <div style="background-color: #f1f5f9; padding: 16px; text-align: center; font-size: 12px; color: #64748b;">
                    <p style="margin: 0;">You received this email because you requested a product reminder on ' . esc_html($siteName) . '.</p>
                </div>
            </div>
        </body>
        </html>';

        wp_mail($email, $userSubject, $userHtmlBody, $headers);

        // 2. Send notification copy to admin if admin is different from collected email
        if ($adminEmail && strtolower(trim($adminEmail)) !== strtolower(trim($email))) {
            $adminSubject = sprintf('[Product Lead] %s saved %s', $email, $productName);
            $adminHtmlBody = '
            <!DOCTYPE html>
            <html>
            <body>
                <p>A new shopper lead requested a Buy Later reminder:</p>
                <ul>
                    <li><strong>Shopper Email:</strong> ' . esc_html($email) . '</li>
                    <li><strong>Product:</strong> ' . esc_html($productName) . '</li>
                    <li><strong>Price:</strong> ' . esc_html($price ?: 'N/A') . '</li>
                    <li><strong>Date:</strong> ' . esc_html($timestamp) . '</li>
                    <li><strong>IP Address:</strong> ' . esc_html($ip ?: 'N/A') . '</li>
                </ul>
            </body>
            </html>';

            wp_mail($adminEmail, $adminSubject, $adminHtmlBody, $headers);
        }
    }

    /**
     * Add Submenu page under Tools
     */
    public function add_admin_menu()
    {
        add_management_page(
            'Product Card Leads',
            'Product Leads',
            'manage_options',
            'pcpx-leads',
            array($this, 'render_admin_leads_page')
        );
    }

    /**
     * Handle CSV Export action for lead data
     */
    public function handle_csv_export()
    {
        if (!isset($_GET['page']) || $_GET['page'] !== 'pcpx-leads' || !isset($_GET['action']) || $_GET['action'] !== 'export_csv') {
            return;
        }

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized user access.');
        }

        check_admin_referer('pcpx_export_leads');

        global $wpdb;
        $table_name = $wpdb->prefix . 'pcpx_leads';
        $leads = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC", ARRAY_A);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=product-card-leads-' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, array('ID', 'Date/Time', 'Email Address', 'Product Name', 'Price', 'IP Address'));

        if (!empty($leads)) {
            foreach ($leads as $row) {
                fputcsv($output, array(
                    $row['id'],
                    $row['timestamp'],
                    $row['email'],
                    $row['product_name'],
                    $row['price'],
                    $row['ip'],
                ));
            }
        }

        fclose($output);
        exit;
    }

    /**
     * Render Admin Leads Management Page
     */
    public function render_admin_leads_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'pcpx_leads';
        $notice = '';

        // Single lead delete action
        if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['lead_id'])) {
            $lead_id = absint($_GET['lead_id']);
            check_admin_referer('pcpx_delete_lead_' . $lead_id);

            $deleted = $wpdb->delete($table_name, array('id' => $lead_id), array('%d'));
            if ($deleted) {
                $notice = '<div class="notice notice-success is-dismissible"><p>Lead #' . $lead_id . ' successfully deleted.</p></div>';
            }
        }

        // Bulk leads delete action
        if (isset($_POST['action']) && $_POST['action'] === 'bulk_delete' && !empty($_POST['lead_ids']) && is_array($_POST['lead_ids'])) {
            check_admin_referer('pcpx_bulk_delete_leads');

            $lead_ids = array_map('absint', $_POST['lead_ids']);
            $ids_placeholder = implode(',', array_fill(0, count($lead_ids), '%d'));

            $query = $wpdb->prepare("DELETE FROM $table_name WHERE id IN ($ids_placeholder)", $lead_ids);
            $count = $wpdb->query($query);

            if ($count) {
                $notice = '<div class="notice notice-success is-dismissible"><p>Successfully deleted ' . esc_html($count) . ' lead(s).</p></div>';
            }
        }

        // Search parameters
        $search = isset($_REQUEST['s']) ? sanitize_text_field(trim($_REQUEST['s'])) : '';
        $where_clauses = array();
        $params = array();

        if (!empty($search)) {
            $where_clauses[] = "(email LIKE %s OR product_name LIKE %s)";
            $like_term = '%' . $wpdb->esc_like($search) . '%';
            $params[] = $like_term;
            $params[] = $like_term;
        }

        $where_sql = !empty($where_clauses) ? 'WHERE ' . implode(' AND ', $where_clauses) : '';

        // Pagination setup
        $per_page = 20;
        $paged = max(1, isset($_GET['paged']) ? absint($_GET['paged']) : 1);
        $offset = ($paged - 1) * $per_page;

        // Total count using prepared query
        if (!empty($params)) {
            $total_query = $wpdb->prepare("SELECT COUNT(*) FROM $table_name $where_sql", $params);
            $total_items = (int) $wpdb->get_var($total_query);
        } else {
            $total_items = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
        }

        $total_pages = ceil($total_items / $per_page);

        // Fetch rows using prepared query
        $query_params = array_merge($params, array($per_page, $offset));
        if (!empty($where_sql)) {
            $data_query = $wpdb->prepare("SELECT * FROM $table_name $where_sql ORDER BY id DESC LIMIT %d OFFSET %d", $query_params);
        } else {
            $data_query = $wpdb->prepare("SELECT * FROM $table_name ORDER BY id DESC LIMIT %d OFFSET %d", $per_page, $offset);
        }

        $leads = $wpdb->get_results($data_query);

        $export_url = wp_nonce_url(admin_url('tools.php?page=pcpx-leads&action=export_csv'), 'pcpx_export_leads');
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline">Product Card Leads</h1>
            <a href="<?php echo esc_url($export_url); ?>" class="page-title-action">Export to CSV</a>
            <hr class="wp-header-end">

            <?php echo $notice; ?>

            <form method="get" style="margin-bottom: 15px;">
                <input type="hidden" name="page" value="pcpx-leads" />
                <p class="search-box">
                    <label class="screen-reader-text" for="pcpx-lead-search">Search Leads:</label>
                    <input type="search" id="pcpx-lead-search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search email or product...">
                    <input type="submit" id="search-submit" class="button" value="Search Leads">
                </p>
            </form>

            <form method="post">
                <?php wp_nonce_field('pcpx_bulk_delete_leads'); ?>
                <input type="hidden" name="action" value="bulk_delete">

                <div class="tablenav top">
                    <div class="alignleft actions bulkactions">
                        <input type="submit" class="button action" value="Bulk Delete" onclick="return confirm('Are you sure you want to delete the selected leads?');">
                    </div>
                    <div class="tablenav-pages">
                        <span class="displaying-num"><?php echo esc_html($total_items); ?> lead(s)</span>
                        <?php
                        if ($total_pages > 1) {
                            $page_links = paginate_links(array(
                                'base'      => add_query_arg('paged', '%#%'),
                                'format'    => '',
                                'prev_text' => '&laquo;',
                                'next_text' => '&raquo;',
                                'total'     => $total_pages,
                                'current'   => $paged,
                            ));
                            echo '<span class="pagination-links">' . $page_links . '</span>';
                        }
                        ?>
                    </div>
                </div>

                <table class="wp-list-table widefat fixed striped table-view-list">
                    <thead>
                        <tr>
                            <td id="cb" class="manage-column column-cb check-column">
                                <input id="cb-select-all-1" type="checkbox" onclick="jQuery('input[name=\'lead_ids[]\']').prop('checked', this.checked);">
                            </td>
                            <th scope="col" class="manage-column column-id" style="width: 60px;">ID</th>
                            <th scope="col" class="manage-column column-email">Email</th>
                            <th scope="col" class="manage-column column-product">Product Name</th>
                            <th scope="col" class="manage-column column-price">Price</th>
                            <th scope="col" class="manage-column column-date">Captured Date</th>
                            <th scope="col" class="manage-column column-ip">IP Address</th>
                            <th scope="col" class="manage-column column-actions" style="width: 80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($leads)): ?>
                            <?php foreach ($leads as $lead): ?>
                                <?php
                                $delete_url = wp_nonce_url(
                                    admin_url('tools.php?page=pcpx-leads&action=delete&lead_id=' . $lead->id),
                                    'pcpx_delete_lead_' . $lead->id
                                );
                                ?>
                                <tr>
                                    <th scope="row" class="check-column">
                                        <input type="checkbox" name="lead_ids[]" value="<?php echo esc_attr($lead->id); ?>">
                                    </th>
                                    <td><?php echo esc_html($lead->id); ?></td>
                                    <td><strong><a href="<?php echo esc_url('mailto:' . $lead->email); ?>"><?php echo esc_html($lead->email); ?></a></strong></td>
                                    <td><?php echo esc_html($lead->product_name); ?></td>
                                    <td><?php echo esc_html($lead->price ?: '—'); ?></td>
                                    <td><?php echo esc_html($lead->timestamp); ?></td>
                                    <td><code><?php echo esc_html($lead->ip ?: 'N/A'); ?></code></td>
                                    <td>
                                        <a href="<?php echo esc_url($delete_url); ?>" class="button button-small button-link-delete" onclick="return confirm('Delete lead #<?php echo esc_attr($lead->id); ?>?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">No leads found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </form>
        </div>
        <?php
    }

    /**
     * Render product card block using attributes
     */
    public function render_product_card_block($attributes)
    {
        // Get attributes with defaults
        $status = isset($attributes['status']) ? $attributes['status'] : 'legit';
        $productName = isset($attributes['productName']) ? $attributes['productName'] : 'Gutoptim';
        $productCategory = isset($attributes['productCategory']) ? $attributes['productCategory'] : 'Supplement';
        $imageUrl = isset($attributes['imageUrl']) ? $attributes['imageUrl'] : '';
        $userExperience = isset($attributes['userExperience']) ? $attributes['userExperience'] : '';
        $userExperience = $this->trim_words($userExperience, 20);

        $expertReview1 = isset($attributes['expertReview1']) ? $attributes['expertReview1'] : '';
        $expertReview2 = isset($attributes['expertReview2']) ? $attributes['expertReview2'] : '';
        $expertReview3 = isset($attributes['expertReview3']) ? $attributes['expertReview3'] : '';
        $expertReview1 = $this->trim_words($expertReview1, 20);
        $expertReview2 = $this->trim_words($expertReview2, 20);
        $expertReview3 = $this->trim_words($expertReview3, 20);

        $verdict = isset($attributes['verdict']) ? $attributes['verdict'] : '';
        $bestFor = isset($attributes['bestFor']) ? $attributes['bestFor'] : '';

        $rating = isset($attributes['rating']) ? $attributes['rating'] : '4.9';
        $readReviewsUrl = isset($attributes['readReviewsUrl']) ? $attributes['readReviewsUrl'] : '#';
        $price = isset($attributes['price']) ? $attributes['price'] : '69';
        $originalPrice = isset($attributes['originalPrice']) ? $attributes['originalPrice'] : '99';
        $currency = isset($attributes['currency']) ? $attributes['currency'] : '$';
        $ctaLabel = (isset($attributes['ctaLabel']) && $attributes['ctaLabel'] !== '') ? $attributes['ctaLabel'] : 'Buy Now';
        $buyLaterLabel = (isset($attributes['buyLaterLabel']) && $attributes['buyLaterLabel'] !== '') ? $attributes['buyLaterLabel'] : 'Buy Later';
        $ctaSubText = (isset($attributes['ctaSubText']) && $attributes['ctaSubText'] !== '') ? $attributes['ctaSubText'] : 'from official website';
        $ctaUrl = isset($attributes['ctaUrl']) ? $attributes['ctaUrl'] : '#';
        $ctaHref = (!empty($ctaUrl) && $ctaUrl !== '#') ? $ctaUrl : 'javascript:void(0);';
        $headerColor = isset($attributes['headerColor']) ? $attributes['headerColor'] : '#000000';
        $reviewSummary = isset($attributes['reviewSummary']) ? $attributes['reviewSummary'] : '';

        $cardTheme = isset($attributes['cardTheme']) ? $attributes['cardTheme'] : 'default';
        $themeClass = ($cardTheme && $cardTheme !== 'default') ? 'pcpx-theme-' . sanitize_html_class($cardTheme) : '';
        $statusClass = $status === 'scam' ? 'pcpx-status-scam' : 'pcpx-status-legit';
        $statusLabel = ucfirst($status);
        $cardClass = ($status === 'scam' ? 'pcpx-card-scam' : 'pcpx-card-legit') . ($themeClass ? ' ' . $themeClass : '');
        $reviewHeaderColor = '#ffffff';
        $reviewHeaderText = ($productName ? $productName . ' ' : '') . 'Review Summary';
        $cardId = 'pcpx-' . sanitize_title($productName) . '-' . wp_rand(100, 999);
        $modalId = 'pcpx-modal-' . sanitize_title($productName) . '-' . wp_rand(100, 999);

        // Prepare Schema.org JSON-LD structured data
        $siteName = get_bloginfo('name');
        $ratingNum = min(5.0, max(0.0, floatval($rating)));

        // Currency Code mapping
        $currencyCode = 'USD';
        $trimmedCurrency = trim($currency);
        if (preg_match('/^[A-Z]{3}$/i', $trimmedCurrency)) {
            $currencyCode = strtoupper($trimmedCurrency);
        } elseif ($trimmedCurrency === '€') {
            $currencyCode = 'EUR';
        } elseif ($trimmedCurrency === '£') {
            $currencyCode = 'GBP';
        } elseif ($trimmedCurrency === '₹') {
            $currencyCode = 'INR';
        }

        $numericPrice = floatval(preg_replace('/[^0-9.]/', '', $price));

        $schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'Product',
            'name'     => wp_strip_all_tags($productName),
        );

        if (!empty($productCategory)) {
            $schema['category'] = wp_strip_all_tags($productCategory);
        }

        if (!empty($imageUrl)) {
            $schema['image'] = array(esc_url_raw($imageUrl));
        }

        $schemaDescription = !empty($verdict) ? $verdict : (!empty($expertReview1) ? $expertReview1 : (!empty($userExperience) ? $userExperience : $reviewSummary));
        if (!empty($schemaDescription)) {
            $schema['description'] = wp_strip_all_tags($schemaDescription);
        }

        if ($ratingNum > 0) {
            $schema['aggregateRating'] = array(
                '@type'       => 'AggregateRating',
                'ratingValue' => (string) $ratingNum,
                'bestRating'  => '5',
                'worstRating' => '1',
                'ratingCount' => '1',
            );

            $schema['review'] = array(
                '@type'        => 'Review',
                'reviewRating' => array(
                    '@type'       => 'Rating',
                    'ratingValue' => (string) $ratingNum,
                    'bestRating'  => '5',
                    'worstRating' => '1',
                ),
                'author'       => array(
                    '@type' => 'Organization',
                    'name'  => !empty($siteName) ? $siteName : 'Expert Reviewer',
                ),
            );
            if (!empty($schemaDescription)) {
                $schema['review']['reviewBody'] = wp_strip_all_tags($schemaDescription);
            }
        }

        if ($numericPrice > 0) {
            $offer = array(
                '@type'         => 'Offer',
                'priceCurrency' => $currencyCode,
                'price'         => (string) $numericPrice,
                'availability'  => 'https://schema.org/InStock',
            );
            if (!empty($ctaUrl) && $ctaUrl !== '#') {
                $offer['url'] = esc_url_raw($ctaUrl);
            }
            $schema['offers'] = $offer;
        }

        $formattedPrice = (strpos(trim($price), $currency) === 0 ? '' : $currency) . $price;

        ob_start();
        ?>
        <div class="pcpx-product-card-outer" id="<?php echo esc_attr($cardId); ?>" data-product-name="<?php echo esc_attr($productName); ?>">
            <script type="application/ld+json">
                <?php echo wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
            </script>
            <div class="pcpx-card <?php echo esc_attr($cardClass); ?>">
                <div class="pcpx-review-summary-header-main" style="color: <?php echo esc_attr($reviewHeaderColor); ?>;">
                    <?php echo esc_html($reviewHeaderText); ?>
                </div>
                <div class="pcpx-card-inner">
                    <!-- Left Column: Product Image & CTA Action Group -->
                    <div class="pcpx-image-column">
                        <div class="pcpx-image-container">
                            <?php if ($imageUrl): ?>
                                <img src="<?php echo esc_url($imageUrl); ?>" alt="<?php echo esc_attr($productName); ?>"
                                    class="pcpx-product-image" />
                            <?php else: ?>
                                <div class="pcpx-image-placeholder">No Image</div>
                            <?php endif; ?>
                        </div>

                        <!-- CTA Group: Buy Now (Primary) + Buy Later (Secondary) -->
                        <div class="pcpx-cta-group">
                            <a href="<?php echo esc_attr($ctaHref); ?>" class="pcpx-cta-button pcpx-btn-primary" target="_blank" rel="noopener">
                                <span class="pcpx-btn-left-icon">
                                    <svg class="pcpx-btn-icon pcpx-cart-icon" width="40" height="40" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                                    </svg>
                                </span>
                                <span class="pcpx-btn-text-block">
                                    <span class="pcpx-cta-main-text"><?php echo esc_html($ctaLabel); ?></span>
                                    <span class="pcpx-cta-sub-text"><?php echo esc_html($ctaSubText ?: 'Visit Official Website'); ?></span>
                                </span>
                                <span class="pcpx-btn-right-chevron">
                                    <svg class="pcpx-btn-chevron" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </span>
                            </a>

                            <button type="button" class="pcpx-buy-later-btn pcpx-btn-secondary"
                                data-product-name="<?php echo esc_attr($productName); ?>"
                                data-price="<?php echo esc_attr($formattedPrice); ?>"
                                data-modal-id="<?php echo esc_attr($modalId); ?>"
                                aria-haspopup="dialog">
                                <span class="pcpx-btn-left-icon">
                                    <svg class="pcpx-btn-icon pcpx-email-icon" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                                    </svg>
                                </span>
                                <span class="pcpx-btn-text-block">
                                    <span class="pcpx-buy-later-main-text"><?php echo esc_html($buyLaterLabel); ?></span>
                                    <span class="pcpx-buy-later-sub-text">Save Official Link to Email</span>
                                </span>
                                <span class="pcpx-btn-right-chevron">
                                    <svg class="pcpx-btn-chevron" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Right Column: Header with Rating + Repositioned Top-Right Price, followed by content -->
                    <div class="pcpx-content-column">
                        <div class="pcpx-content-header-row">
                            <div class="pcpx-rating-row">
                                <?php if ($rating): ?>
                                    <div class="pcpx-rating-stars">
                                        <?php
                                        $ratingValue = max(0, min(5, floatval($rating)));
                                        $ratingInstanceId = 'pcpx_star_' . wp_rand(1000, 99999);
                                        echo '<span class="pcpx-stars">';
                                        $starPath = 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z';
                                        for ($i = 1; $i <= 5; $i++) {
                                            $rem = $ratingValue - ($i - 1);
                                            if ($rem >= 1.0) {
                                                echo '<svg width="20" height="20" viewBox="0 0 24 24" class="pcpx-star-svg" style="width:20px; height:20px; display:inline-block; vertical-align:middle; flex-shrink:0;" aria-hidden="true">';
                                                echo '<path d="' . $starPath . '" fill="#FFB800" stroke="#000000" stroke-width="1.2" stroke-linejoin="round"/>';
                                                echo '</svg>';
                                            } elseif ($rem <= 0.0) {
                                                echo '<svg width="20" height="20" viewBox="0 0 24 24" class="pcpx-star-svg" style="width:20px; height:20px; display:inline-block; vertical-align:middle; flex-shrink:0;" aria-hidden="true">';
                                                echo '<path d="' . $starPath . '" fill="#E2E8F0" stroke="#000000" stroke-width="1.2" stroke-linejoin="round"/>';
                                                echo '</svg>';
                                            } else {
                                                $gradId = $ratingInstanceId . '_' . $i;
                                                echo '<svg width="20" height="20" viewBox="0 0 24 24" class="pcpx-star-svg" style="width:20px; height:20px; display:inline-block; vertical-align:middle; flex-shrink:0;" aria-hidden="true">';
                                                echo '<defs>';
                                                echo '<linearGradient id="' . $gradId . '" x1="0%" y1="0%" x2="100%" y2="0%">';
                                                echo '<stop offset="50%" stop-color="#FFB800"/>';
                                                echo '<stop offset="50%" stop-color="#E2E8F0"/>';
                                                echo '</linearGradient>';
                                                echo '</defs>';
                                                echo '<path d="' . $starPath . '" fill="url(#' . $gradId . ')" stroke="#000000" stroke-width="1.2" stroke-linejoin="round"/>';
                                                echo '</svg>';
                                            }
                                        }
                                        echo '</span>';
                                        echo ' <span class="pcpx-rating-score-text">' . rtrim(rtrim(number_format($ratingValue, 1, '.', ''), '0'), '.') . '/5</span>';
                                        ?>
                                    </div>
                                <?php endif; ?>
                                <?php if ($readReviewsUrl): ?>
                                    <a class="pcpx-read-reviews" href="<?php echo esc_url($readReviewsUrl); ?>" target="_blank" rel="noopener">Read reviews</a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php $labelAttr = ($headerColor && $headerColor !== '#000000') ? ' style="color: ' . esc_attr($headerColor) . ';"' : ''; ?>
                        <?php if ($bestFor): ?>
                            <div class="pcpx-section pcpx-best-for-section">
                                <div class="pcpx-section-label" data-icon="best-for"<?php echo $labelAttr; ?>>Best For</div>
                                <p class="pcpx-section-text"><?php echo esc_html($bestFor); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($userExperience): ?>
                            <div class="pcpx-section">
                                <div class="pcpx-section-label" data-icon="experience"<?php echo $labelAttr; ?>>User Experience</div>
                                <p class="pcpx-section-text"><?php echo esc_html($userExperience); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($expertReview1 || $expertReview2 || $expertReview3): ?>
                            <div class="pcpx-section">
                                <div class="pcpx-section-label" data-icon="reviews"<?php echo $labelAttr; ?>>Expert Reviews</div>
                                <ul class="pcpx-expert-reviews-list">
                                    <?php if ($expertReview1): ?>
                                        <li><?php echo esc_html($expertReview1); ?></li>
                                    <?php endif; ?>
                                    <?php if ($expertReview2): ?>
                                        <li><?php echo esc_html($expertReview2); ?></li>
                                    <?php endif; ?>
                                    <?php if ($expertReview3): ?>
                                        <li><?php echo esc_html($expertReview3); ?></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <!-- Price Section: Positioned Just Above Final Verdict -->
                        <div class="pcpx-price-section pcpx-price-above-verdict">
                            <?php if ($originalPrice && $originalPrice !== $price): ?>
                                <span class="pcpx-original-price">
                                    <?php echo esc_html($currency . $originalPrice); ?>
                                </span>
                            <?php endif; ?>
                            <span class="pcpx-price">
                                <?php echo esc_html($formattedPrice); ?>
                            </span>
                        </div>

                    </div>
                </div>
                <?php if ($verdict): ?>
                    <div class="pcpx-verdict-box">
                        <span class="pcpx-verdict-icon"></span>
                        <div class="pcpx-verdict-text"><strong>Final Verdict:</strong> <?php echo esc_html($verdict); ?></div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- "Buy Later" Popup Modal -->
            <div class="pcpx-modal-backdrop" id="<?php echo esc_attr($modalId); ?>" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr($modalId); ?>-title">
                <div class="pcpx-modal-card">
                    <button type="button" class="pcpx-modal-close" aria-label="Close modal">&times;</button>
                    <div class="pcpx-modal-body">
                        <!-- Input Form State -->
                        <div class="pcpx-modal-form-view">
                            <div class="pcpx-modal-icon-badge">
                                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                </svg>
                            </div>
                            <h3 class="pcpx-modal-title" id="<?php echo esc_attr($modalId); ?>-title">Save For Later</h3>
                            <p class="pcpx-modal-msg">
                                We'll remind you to complete your purchase of <strong class="pcpx-modal-product-title"><?php echo esc_html($productName); ?></strong>.
                            </p>
                            <form class="pcpx-buy-later-form" novalidate>
                                <input type="hidden" name="product_name" value="<?php echo esc_attr($productName); ?>">
                                <input type="hidden" name="price" value="<?php echo esc_attr($formattedPrice); ?>">
                                <div class="pcpx-input-wrapper">
                                    <label for="<?php echo esc_attr($modalId); ?>-email" class="pcpx-sr-only">Email address</label>
                                    <input type="email" id="<?php echo esc_attr($modalId); ?>-email" name="email" class="pcpx-email-input" placeholder="Enter your email address" required autocomplete="email">
                                    <div class="pcpx-input-error" role="alert" style="display:none;"></div>
                                </div>
                                <button type="submit" class="pcpx-modal-submit-btn">
                                    <span class="pcpx-btn-label">Remind Me</span>
                                    <span class="pcpx-btn-spinner" style="display:none;" aria-hidden="true"></span>
                                </button>
                            </form>
                            <p class="pcpx-modal-privacy">We respect your privacy. No spam, ever.</p>
                        </div>

                        <!-- Success Confirmation State -->
                        <div class="pcpx-modal-success-view" style="display:none;">
                            <div class="pcpx-modal-icon-badge pcpx-badge-success">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 6L9 17l-5-5"></path>
                                </svg>
                            </div>
                            <h3 class="pcpx-modal-title">Reminder Set!</h3>
                            <p class="pcpx-modal-msg">
                                We've saved your spot for <strong><?php echo esc_html($productName); ?></strong>. We'll send an email reminder to <strong class="pcpx-confirmed-email"></strong> so you can finish your purchase whenever you're ready.
                            </p>
                            <button type="button" class="pcpx-modal-done-btn">Done</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function trim_words($text, $max_words) {
        $text = trim(wp_strip_all_tags((string) $text));
        
        // Fix the issue where stripslashes turns \n\n into nn at the end, removing the full stop
        $text = preg_replace('/n{2,}$/', '.', $text);
        
        if ($text === '') {
            return '';
        }
        $words = preg_split('/\s+/', $text);
        if (!$words || count($words) <= $max_words) {
            return $text;
        }
        $trimmed = array_slice($words, 0, $max_words);
        return implode(' ', $trimmed);
    }
}

// Register activation hook
register_activation_hook(__FILE__, array('ProductCardPlugin', 'activate_plugin'));

// Initialize the plugin
new ProductCardPlugin();
