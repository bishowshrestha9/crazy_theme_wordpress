<?php
/**
 * Standalone Product Card Duplicate Detector Admin Interface
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProductCard_Duplicate_Detector_Admin {

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
        add_action('wp_ajax_pcdd_scan_duplicates', array($this, 'ajax_scan_duplicates'));
        add_action('wp_ajax_pcdd_export_csv', array($this, 'ajax_export_csv'));

        // Direct POST Download Handler
        add_action('admin_post_pcdd_export_csv', array($this, 'handle_direct_csv_export'));
    }

    /**
     * Add Admin Menu Item
     */
    public function add_admin_menu() {
        add_menu_page(
            'Duplicate Product Detector',
            'Duplicate Detector',
            'manage_options',
            'product-card-duplicate-detector',
            array($this, 'render_admin_page'),
            'dashicons-filter',
            81
        );
    }

    /**
     * Enqueue Admin Assets
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'product-card-duplicate-detector') === false) {
            return;
        }

        $dir_url = plugin_dir_url(dirname(__FILE__));
        $version = PCDD_VERSION;

        wp_enqueue_style('pcdd-admin-css', $dir_url . 'assets/css/admin.css', array(), $version);
        wp_enqueue_script('pcdd-admin-js', $dir_url . 'assets/js/admin.js', array('jquery'), $version, true);

        wp_localize_script('pcdd-admin-js', 'pcddData', array(
            'ajaxUrl'   => admin_url('admin-ajax.php'),
            'exportUrl' => admin_url('admin-post.php?action=pcdd_export_csv'),
            'nonce'     => wp_create_nonce('pcdd_detector_nonce'),
        ));
    }

    /**
     * AJAX Endpoint: Scan Duplicates
     */
    public function ajax_scan_duplicates() {
        check_ajax_referer('pcdd_detector_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized permission'));
        }

        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'all';
        $post_status = isset($_POST['post_status']) ? sanitize_text_field($_POST['post_status']) : 'all';
        $min_occurrences = isset($_POST['min_occurrences']) ? intval($_POST['min_occurrences']) : 2;
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

        $detector = ProductCard_Duplicate_Detector::get_instance();
        $results = $detector->scan_duplicates(array(
            'post_type'       => $post_type,
            'post_status'     => $post_status,
            'min_occurrences' => $min_occurrences,
            'search'          => $search,
        ));

        wp_send_json_success($results);
    }

    /**
     * AJAX Endpoint: Export CSV
     */
    public function ajax_export_csv() {
        check_ajax_referer('pcdd_detector_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized permission'));
        }

        $post_type = isset($_REQUEST['post_type']) ? sanitize_text_field($_REQUEST['post_type']) : 'all';
        $post_status = isset($_REQUEST['post_status']) ? sanitize_text_field($_REQUEST['post_status']) : 'all';
        $min_occurrences = isset($_REQUEST['min_occurrences']) ? intval($_REQUEST['min_occurrences']) : 2;
        $search = isset($_REQUEST['search']) ? sanitize_text_field($_REQUEST['search']) : '';

        $detector = ProductCard_Duplicate_Detector::get_instance();
        $scan_result = $detector->scan_duplicates(array(
            'post_type'       => $post_type,
            'post_status'     => $post_status,
            'min_occurrences' => $min_occurrences,
            'search'          => $search,
        ));

        $detector->export_csv($scan_result['duplicates'], true);
    }

    /**
     * Admin POST Endpoint for direct CSV download
     */
    public function handle_direct_csv_export() {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'pcdd_detector_nonce')) {
            wp_die('Invalid security nonce');
        }

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized permission');
        }

        $post_type = isset($_REQUEST['post_type']) ? sanitize_text_field($_REQUEST['post_type']) : 'all';
        $post_status = isset($_REQUEST['post_status']) ? sanitize_text_field($_REQUEST['post_status']) : 'all';
        $min_occurrences = isset($_REQUEST['min_occurrences']) ? intval($_REQUEST['min_occurrences']) : 2;
        $search = isset($_REQUEST['search']) ? sanitize_text_field($_REQUEST['search']) : '';

        $detector = ProductCard_Duplicate_Detector::get_instance();
        $scan_result = $detector->scan_duplicates(array(
            'post_type'       => $post_type,
            'post_status'     => $post_status,
            'min_occurrences' => $min_occurrences,
            'search'          => $search,
        ));

        $detector->export_csv($scan_result['duplicates'], true);
    }

    /**
     * Render Admin Page
     */
    public function render_admin_page() {
        $public_post_types = get_post_types(array('public' => true), 'objects');
        ?>
        <div class="wrap pcdd-wrap">
            <div class="pcdd-header">
                <div class="pcdd-header-main">
                    <span class="pcdd-header-icon dashicons dashicons-filter"></span>
                    <div>
                        <h1>Duplicate Product Name Detector & CSV Exporter</h1>
                        <p class="pcdd-subtitle">Scan all posts to detect duplicate Product Names across Product Cards and export a CSV report of the duplicates.</p>
                    </div>
                </div>
                <div class="pcdd-header-badge">v<?php echo esc_html(PCDD_VERSION); ?></div>
            </div>

            <!-- Stats Grid -->
            <div class="pcdd-stats-grid">
                <div class="pcdd-stat-card">
                    <div class="pcdd-stat-value" id="pcdd-stat-scanned">0</div>
                    <div class="pcdd-stat-label">Total Posts Scanned</div>
                    <span class="pcdd-stat-icon dashicons dashicons-admin-post"></span>
                </div>
                <div class="pcdd-stat-card">
                    <div class="pcdd-stat-value" id="pcdd-stat-cards">0</div>
                    <div class="pcdd-stat-label">Total Cards Scanned</div>
                    <span class="pcdd-stat-icon dashicons dashicons-grid-view"></span>
                </div>
                <div class="pcdd-stat-card pcdd-card-highlight">
                    <div class="pcdd-stat-value" id="pcdd-stat-duplicate-names">0</div>
                    <div class="pcdd-stat-label">Duplicate Product Names</div>
                    <span class="pcdd-stat-icon dashicons dashicons-warning"></span>
                </div>
                <div class="pcdd-stat-card">
                    <div class="pcdd-stat-value" id="pcdd-stat-duplicate-occurrences">0</div>
                    <div class="pcdd-stat-label">Total Duplicate Cards</div>
                    <span class="pcdd-stat-icon dashicons dashicons-tag"></span>
                </div>
            </div>

            <!-- Toolbar Controls -->
            <div class="pcdd-toolbar">
                <div class="pcdd-toolbar-row">
                    <div class="pcdd-filters">
                        <div class="pcdd-filter-group">
                            <label for="pcdd-filter-post-type">Post Type:</label>
                            <select id="pcdd-filter-post-type">
                                <option value="all">All Public Post Types</option>
                                <?php foreach ($public_post_types as $pt_slug => $pt_obj): ?>
                                    <option value="<?php echo esc_attr($pt_slug); ?>"><?php echo esc_html($pt_obj->labels->singular_name); ?> (<?php echo esc_html($pt_slug); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="pcdd-filter-group">
                            <label for="pcdd-filter-min-occurrences">Min Duplicates:</label>
                            <select id="pcdd-filter-min-occurrences">
                                <option value="2">2+ Occurrences</option>
                                <option value="3">3+ Occurrences</option>
                                <option value="5">5+ Occurrences</option>
                                <option value="10">10+ Occurrences</option>
                            </select>
                        </div>

                        <div class="pcdd-filter-group pcdd-search-group">
                            <label for="pcdd-search-input">Search Product:</label>
                            <input type="text" id="pcdd-search-input" placeholder="Search product name or post..." />
                        </div>
                    </div>

                    <div class="pcdd-actions">
                        <button type="button" id="pcdd-btn-scan" class="button button-primary button-hero">
                            <span class="dashicons dashicons-search"></span> Scan Duplicates
                        </button>
                        
                        <form id="pcdd-csv-export-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline-block;">
                            <input type="hidden" name="action" value="pcdd_export_csv">
                            <input type="hidden" name="_wpnonce" value="<?php echo esc_attr(wp_create_nonce('pcdd_detector_nonce')); ?>">
                            <input type="hidden" name="post_type" id="pcdd-export-post-type" value="all">
                            <input type="hidden" name="post_status" id="pcdd-export-post-status" value="all">
                            <input type="hidden" name="min_occurrences" id="pcdd-export-min-occurrences" value="2">
                            <input type="hidden" name="search" id="pcdd-export-search" value="">
                            
                            <button type="submit" id="pcdd-btn-export" class="button button-secondary button-hero pcdd-btn-success" disabled>
                                <span class="dashicons dashicons-download"></span> Export Duplicates to CSV
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div id="pcdd-progress-container" class="pcdd-progress-container" style="display: none;">
                <div class="pcdd-progress-bar-wrapper">
                    <div id="pcdd-progress-bar" class="pcdd-progress-bar"></div>
                </div>
                <div class="pcdd-progress-status" id="pcdd-progress-status">Analyzing posts for duplicate product names... Please wait.</div>
            </div>

            <!-- Results Section -->
            <div class="pcdd-results-container">
                <div class="pcdd-results-header">
                    <h2>Duplicate Product Names (<span id="pcdd-results-count">0</span> detected)</h2>
                    <div class="pcdd-results-meta" id="pcdd-last-scanned-time">Click "Scan Duplicates" to start scan.</div>
                </div>

                <div class="pcdd-table-responsive">
                    <table class="wp-list-table widefat fixed striped posts pcdd-table" id="pcdd-results-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 25%;">Product Name</th>
                                <th style="width: 140px;">Total Cards</th>
                                <th style="width: 140px;">Posts Count</th>
                                <th>Posts Containing This Product</th>
                                <th style="width: 100px;">Details</th>
                            </tr>
                        </thead>
                        <tbody id="pcdd-results-tbody">
                            <tr class="pcdd-empty-row">
                                <td colspan="6">
                                    <div class="pcdd-empty-state">
                                        <span class="dashicons dashicons-search" style="font-size: 42px; width: 42px; height: 42px; color: #94a3b8;"></span>
                                        <h3>No duplicate scan performed yet</h3>
                                        <p>Click <strong>"Scan Duplicates"</strong> to detect product names that are reused across multiple Product Cards.</p>
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

// Initialize Admin
new ProductCard_Duplicate_Detector_Admin();
