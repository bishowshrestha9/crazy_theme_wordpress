<?php
/**
 * Standalone Product Card Scanner Admin Interface & Actions
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProductCard_Post_Scanner_Admin {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));

        // AJAX Handlers
        add_action('wp_ajax_pcps_scan_posts', array($this, 'ajax_scan_posts'));
        add_action('wp_ajax_pcps_export_csv', array($this, 'ajax_export_csv'));

        // Direct POST Download Handler
        add_action('admin_post_pcps_export_csv', array($this, 'handle_direct_csv_export'));
    }

    /**
     * Add Admin Menu Item
     */
    public function add_admin_menu() {
        add_menu_page(
            'Product Card Scanner',
            'Card Scanner',
            'manage_options',
            'product-card-post-scanner',
            array($this, 'render_admin_page'),
            'dashicons-products',
            80
        );
    }

    /**
     * Enqueue Admin Assets
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'product-card-post-scanner') === false) {
            return;
        }

        $dir_url = plugin_dir_url(dirname(__FILE__));
        $version = PCPS_VERSION;

        wp_enqueue_style('pcps-admin-css', $dir_url . 'assets/css/admin.css', array(), $version);
        wp_enqueue_script('pcps-admin-js', $dir_url . 'assets/js/admin.js', array('jquery'), $version, true);

        wp_localize_script('pcps-admin-js', 'pcpsData', array(
            'ajaxUrl'   => admin_url('admin-ajax.php'),
            'exportUrl' => admin_url('admin-post.php?action=pcps_export_csv'),
            'nonce'     => wp_create_nonce('pcps_scanner_nonce'),
        ));
    }

    /**
     * AJAX Endpoint: Scan Posts
     */
    public function ajax_scan_posts() {
        check_ajax_referer('pcps_scanner_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized user'));
        }

        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'all';
        $post_status = isset($_POST['post_status']) ? sanitize_text_field($_POST['post_status']) : 'all';
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

        $scanner = ProductCard_Post_Scanner::get_instance();
        $results = $scanner->scan_posts(array(
            'post_type'   => $post_type,
            'post_status' => $post_status,
            'search'      => $search,
        ));

        wp_send_json_success($results);
    }

    /**
     * AJAX Endpoint: Export CSV
     */
    public function ajax_export_csv() {
        check_ajax_referer('pcps_scanner_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized user'));
        }

        $post_type = isset($_REQUEST['post_type']) ? sanitize_text_field($_REQUEST['post_type']) : 'all';
        $post_status = isset($_REQUEST['post_status']) ? sanitize_text_field($_REQUEST['post_status']) : 'all';
        $search = isset($_REQUEST['search']) ? sanitize_text_field($_REQUEST['search']) : '';

        $scanner = ProductCard_Post_Scanner::get_instance();
        $scan_result = $scanner->scan_posts(array(
            'post_type'   => $post_type,
            'post_status' => $post_status,
            'search'      => $search,
        ));

        $scanner->export_csv($scan_result['posts'], true);
    }

    /**
     * Admin POST Endpoint for direct CSV stream download
     */
    public function handle_direct_csv_export() {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'pcps_scanner_nonce')) {
            wp_die('Invalid security nonce');
        }

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized permission');
        }

        $post_type = isset($_REQUEST['post_type']) ? sanitize_text_field($_REQUEST['post_type']) : 'all';
        $post_status = isset($_REQUEST['post_status']) ? sanitize_text_field($_REQUEST['post_status']) : 'all';
        $search = isset($_REQUEST['search']) ? sanitize_text_field($_REQUEST['search']) : '';

        $scanner = ProductCard_Post_Scanner::get_instance();
        $scan_result = $scanner->scan_posts(array(
            'post_type'   => $post_type,
            'post_status' => $post_status,
            'search'      => $search,
        ));

        $scanner->export_csv($scan_result['posts'], true);
    }

    /**
     * Render Admin Dashboard HTML
     */
    public function render_admin_page() {
        $public_post_types = get_post_types(array('public' => true), 'objects');
        ?>
        <div class="wrap pcps-scanner-wrap">
            <div class="pcps-header">
                <div class="pcps-header-main">
                    <span class="pcps-header-icon dashicons dashicons-products"></span>
                    <div>
                        <h1>Product Card Post Scanner & CSV Exporter</h1>
                        <p class="pcps-subtitle">Scan all site posts, pages, and custom post types to detect where Product Cards are used and export a CSV list of Post Names.</p>
                    </div>
                </div>
                <div class="pcps-header-badge">v<?php echo esc_html(PCPS_VERSION); ?></div>
            </div>

            <!-- Stats Grid -->
            <div class="pcps-stats-grid">
                <div class="pcps-stat-card">
                    <div class="pcps-stat-value" id="pcps-stat-scanned">0</div>
                    <div class="pcps-stat-label">Total Posts Scanned</div>
                    <span class="pcps-stat-icon dashicons dashicons-admin-post"></span>
                </div>
                <div class="pcps-stat-card pcps-card-highlight">
                    <div class="pcps-stat-value" id="pcps-stat-matched">0</div>
                    <div class="pcps-stat-label">Posts with Product Cards</div>
                    <span class="pcps-stat-icon dashicons dashicons-yes-alt"></span>
                </div>
                <div class="pcps-stat-card">
                    <div class="pcps-stat-value" id="pcps-stat-cards-count">0</div>
                    <div class="pcps-stat-label">Total Product Cards Found</div>
                    <span class="pcps-stat-icon dashicons dashicons-grid-view"></span>
                </div>
                <div class="pcps-stat-card">
                    <div class="pcps-stat-value" id="pcps-stat-products-count">0</div>
                    <div class="pcps-stat-label">Unique Products Detected</div>
                    <span class="pcps-stat-icon dashicons dashicons-tag"></span>
                </div>
            </div>

            <!-- Toolbar Controls -->
            <div class="pcps-toolbar">
                <div class="pcps-toolbar-row">
                    <div class="pcps-filters">
                        <div class="pcps-filter-group">
                            <label for="pcps-filter-post-type">Post Type:</label>
                            <select id="pcps-filter-post-type">
                                <option value="all">All Public Post Types</option>
                                <?php foreach ($public_post_types as $pt_slug => $pt_obj): ?>
                                    <option value="<?php echo esc_attr($pt_slug); ?>"><?php echo esc_html($pt_obj->labels->singular_name); ?> (<?php echo esc_html($pt_slug); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="pcps-filter-group">
                            <label for="pcps-filter-post-status">Post Status:</label>
                            <select id="pcps-filter-post-status">
                                <option value="all">All Statuses</option>
                                <option value="publish">Published</option>
                                <option value="draft">Draft</option>
                                <option value="pending">Pending Review</option>
                                <option value="future">Scheduled</option>
                                <option value="private">Private</option>
                            </select>
                        </div>

                        <div class="pcps-filter-group pcps-search-group">
                            <label for="pcps-search-input">Search Results:</label>
                            <input type="text" id="pcps-search-input" placeholder="Search post title, ID, or product..." />
                        </div>
                    </div>

                    <div class="pcps-actions">
                        <button type="button" id="pcps-btn-scan" class="button button-primary button-hero">
                            <span class="dashicons dashicons-search"></span> Scan Site Posts
                        </button>
                        
                        <form id="pcps-csv-export-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline-block;">
                            <input type="hidden" name="action" value="pcps_export_csv">
                            <input type="hidden" name="_wpnonce" value="<?php echo esc_attr(wp_create_nonce('pcps_scanner_nonce')); ?>">
                            <input type="hidden" name="post_type" id="pcps-export-post-type" value="all">
                            <input type="hidden" name="post_status" id="pcps-export-post-status" value="all">
                            <input type="hidden" name="search" id="pcps-export-search" value="">
                            
                            <button type="submit" id="pcps-btn-export" class="button button-secondary button-hero pcps-btn-success" disabled>
                                <span class="dashicons dashicons-download"></span> Export Post List to CSV
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div id="pcps-progress-container" class="pcps-progress-container" style="display: none;">
                <div class="pcps-progress-bar-wrapper">
                    <div id="pcps-progress-bar" class="pcps-progress-bar"></div>
                </div>
                <div class="pcps-progress-status" id="pcps-progress-status">Scanning all database posts... Please wait.</div>
            </div>

            <!-- Results Section -->
            <div class="pcps-results-container">
                <div class="pcps-results-header">
                    <h2>Scanned Post List (<span id="pcps-results-count">0</span> matches)</h2>
                    <div class="pcps-results-meta" id="pcps-last-scanned-time">Click "Scan Site Posts" to start scan.</div>
                </div>

                <div class="pcps-table-responsive">
                    <table class="wp-list-table widefat fixed striped posts pcps-table" id="pcps-results-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 80px;">Post ID</th>
                                <th style="width: 30%;">Post Name / Title</th>
                                <th style="width: 110px;">Post Type</th>
                                <th style="width: 110px;">Post Status</th>
                                <th style="width: 100px;">Cards Used</th>
                                <th>Detected Product Name(s)</th>
                                <th style="width: 160px;">Date Published</th>
                                <th style="width: 140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="pcps-results-tbody">
                            <tr class="pcps-empty-row">
                                <td colspan="9">
                                    <div class="pcps-empty-state">
                                        <span class="dashicons dashicons-search" style="font-size: 42px; width: 42px; height: 42px; color: #94a3b8;"></span>
                                        <h3>No scan performed yet</h3>
                                        <p>Click the <strong>"Scan Site Posts"</strong> button above to search all posts across your WordPress site for Product Card blocks.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }
}

// Initialize admin
new ProductCard_Post_Scanner_Admin();
