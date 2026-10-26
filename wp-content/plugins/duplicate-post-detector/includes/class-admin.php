<?php
/**
 * Standalone Duplicate Post Detector Admin Interface
 */

if (!defined('ABSPATH')) {
    exit;
}

class Duplicate_Post_Detector_Admin {

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
        add_action('wp_ajax_dpd_scan_duplicates', array($this, 'ajax_scan_duplicates'));
        add_action('wp_ajax_dpd_export_csv', array($this, 'ajax_export_csv'));

        // Direct POST Download Handler
        add_action('admin_post_dpd_export_csv', array($this, 'handle_direct_csv_export'));
    }

    /**
     * Add Admin Menu Item
     */
    public function add_admin_menu() {
        add_menu_page(
            'Duplicate Post Detector',
            'Duplicate Posts',
            'manage_options',
            'duplicate-post-detector',
            array($this, 'render_admin_page'),
            'dashicons-admin-page',
            80
        );
    }

    /**
     * Enqueue Admin Assets
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'duplicate-post-detector') === false) {
            return;
        }

        $dir_url = plugin_dir_url(dirname(__FILE__));
        $version = DPD_VERSION;

        wp_enqueue_style('dpd-admin-css', $dir_url . 'assets/css/admin.css', array(), $version);
        wp_enqueue_script('dpd-admin-js', $dir_url . 'assets/js/admin.js', array('jquery'), $version, true);

        wp_localize_script('dpd-admin-js', 'dpdData', array(
            'ajaxUrl'   => admin_url('admin-ajax.php'),
            'exportUrl' => admin_url('admin-post.php?action=dpd_export_csv'),
            'nonce'     => wp_create_nonce('dpd_detector_nonce'),
        ));
    }

    /**
     * AJAX Endpoint: Scan Duplicates
     */
    public function ajax_scan_duplicates() {
        check_ajax_referer('dpd_detector_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized permission'));
        }

        $mode = isset($_POST['mode']) ? sanitize_text_field($_POST['mode']) : 'base_slug';
        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'all';
        $post_status = isset($_POST['post_status']) ? sanitize_text_field($_POST['post_status']) : 'all';
        $min_occurrences = isset($_POST['min_occurrences']) ? intval($_POST['min_occurrences']) : 2;
        $search = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

        $detector = Duplicate_Post_Detector::get_instance();
        $results = $detector->scan_duplicates(array(
            'mode'            => $mode,
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
        check_ajax_referer('dpd_detector_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized permission'));
        }

        $mode = isset($_REQUEST['mode']) ? sanitize_text_field($_REQUEST['mode']) : 'base_slug';
        $post_type = isset($_REQUEST['post_type']) ? sanitize_text_field($_REQUEST['post_type']) : 'all';
        $post_status = isset($_REQUEST['post_status']) ? sanitize_text_field($_REQUEST['post_status']) : 'all';
        $min_occurrences = isset($_REQUEST['min_occurrences']) ? intval($_REQUEST['min_occurrences']) : 2;
        $search = isset($_REQUEST['search']) ? sanitize_text_field($_REQUEST['search']) : '';

        $detector = Duplicate_Post_Detector::get_instance();
        $scan_result = $detector->scan_duplicates(array(
            'mode'            => $mode,
            'post_type'       => $post_type,
            'post_status'     => $post_status,
            'min_occurrences' => $min_occurrences,
            'search'          => $search,
        ));

        $detector->export_csv($scan_result['duplicate_groups'], true);
    }

    /**
     * Admin POST Endpoint for direct CSV stream download
     */
    public function handle_direct_csv_export() {
        if (!isset($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'dpd_detector_nonce')) {
            wp_die('Invalid security nonce');
        }

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized permission');
        }

        $mode = isset($_REQUEST['mode']) ? sanitize_text_field($_REQUEST['mode']) : 'base_slug';
        $post_type = isset($_REQUEST['post_type']) ? sanitize_text_field($_REQUEST['post_type']) : 'all';
        $post_status = isset($_REQUEST['post_status']) ? sanitize_text_field($_REQUEST['post_status']) : 'all';
        $min_occurrences = isset($_REQUEST['min_occurrences']) ? intval($_REQUEST['min_occurrences']) : 2;
        $search = isset($_REQUEST['search']) ? sanitize_text_field($_REQUEST['search']) : '';

        $detector = Duplicate_Post_Detector::get_instance();
        $scan_result = $detector->scan_duplicates(array(
            'mode'            => $mode,
            'post_type'       => $post_type,
            'post_status'     => $post_status,
            'min_occurrences' => $min_occurrences,
            'search'          => $search,
        ));

        $detector->export_csv($scan_result['duplicate_groups'], true);
    }

    /**
     * Render Admin Dashboard HTML
     */
    public function render_admin_page() {
        $public_post_types = get_post_types(array('public' => true), 'objects');
        ?>
        <div class="wrap dpd-wrap">
            <div class="dpd-header">
                <div class="dpd-header-main">
                    <span class="dpd-header-icon dashicons dashicons-admin-page"></span>
                    <div>
                        <h1>Duplicate Post Slug Detector & CSV Exporter</h1>
                        <p class="dpd-subtitle">Scan all WordPress posts and pages to detect duplicate postings (e.g. slug vs slug-2) and export a complete CSV list.</p>
                    </div>
                </div>
                <div class="dpd-header-badge">v<?php echo esc_html(DPD_VERSION); ?></div>
            </div>

            <!-- Stats Grid -->
            <div class="dpd-stats-grid">
                <div class="dpd-stat-card">
                    <div class="dpd-stat-value" id="dpd-stat-scanned">0</div>
                    <div class="dpd-stat-label">Total Posts Analyzed</div>
                    <span class="dpd-stat-icon dashicons dashicons-admin-post"></span>
                </div>
                <div class="dpd-stat-card dpd-card-highlight">
                    <div class="dpd-stat-value" id="dpd-stat-duplicate-groups">0</div>
                    <div class="dpd-stat-label">Duplicate Groups Found</div>
                    <span class="dpd-stat-icon dashicons dashicons-filter"></span>
                </div>
                <div class="dpd-stat-card">
                    <div class="dpd-stat-value" id="dpd-stat-total-duplicates">0</div>
                    <div class="dpd-stat-label">Total Duplicate Posts</div>
                    <span class="dpd-stat-icon dashicons dashicons-documents"></span>
                </div>
                <div class="dpd-stat-card">
                    <div class="dpd-stat-value" id="dpd-stat-max-duplication">0</div>
                    <div class="dpd-stat-label">Highest Duplication Count</div>
                    <span class="dpd-stat-icon dashicons dashicons-chart-bar"></span>
                </div>
            </div>

            <!-- Toolbar Controls -->
            <div class="dpd-toolbar">
                <div class="dpd-toolbar-row">
                    <div class="dpd-filters">
                        <div class="dpd-filter-group">
                            <label for="dpd-filter-mode">Detection Mode:</label>
                            <select id="dpd-filter-mode">
                                <option value="base_slug">Base Slug Match (e.g. slug & slug-2)</option>
                                <option value="exact_slug">Exact Slug Match</option>
                                <option value="exact_title">Exact Post Title Match</option>
                            </select>
                        </div>

                        <div class="dpd-filter-group">
                            <label for="dpd-filter-post-type">Post Type:</label>
                            <select id="dpd-filter-post-type">
                                <option value="all">All Public Post Types</option>
                                <?php foreach ($public_post_types as $pt_slug => $pt_obj): ?>
                                    <option value="<?php echo esc_attr($pt_slug); ?>"><?php echo esc_html($pt_obj->labels->singular_name); ?> (<?php echo esc_html($pt_slug); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="dpd-filter-group">
                            <label for="dpd-filter-post-status">Post Status:</label>
                            <select id="dpd-filter-post-status">
                                <option value="all">All Statuses</option>
                                <option value="publish">Published</option>
                                <option value="draft">Draft</option>
                                <option value="pending">Pending Review</option>
                                <option value="future">Scheduled</option>
                                <option value="private">Private</option>
                                <option value="trash">Trash</option>
                            </select>
                        </div>

                        <div class="dpd-filter-group dpd-search-group">
                            <label for="dpd-search-input">Search Slugs:</label>
                            <input type="text" id="dpd-search-input" placeholder="Search slug or title..." />
                        </div>
                    </div>

                    <div class="dpd-actions">
                        <button type="button" id="dpd-btn-scan" class="button button-primary button-hero">
                            <span class="dashicons dashicons-search"></span> Scan Duplicate Posts
                        </button>
                        
                        <form id="dpd-csv-export-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" style="display:inline-block;">
                            <input type="hidden" name="action" value="dpd_export_csv">
                            <input type="hidden" name="_wpnonce" value="<?php echo esc_attr(wp_create_nonce('dpd_detector_nonce')); ?>">
                            <input type="hidden" name="mode" id="dpd-export-mode" value="base_slug">
                            <input type="hidden" name="post_type" id="dpd-export-post-type" value="all">
                            <input type="hidden" name="post_status" id="dpd-export-post-status" value="all">
                            <input type="hidden" name="search" id="dpd-export-search" value="">
                            
                            <button type="submit" id="dpd-btn-export" class="button button-secondary button-hero dpd-btn-success" disabled>
                                <span class="dashicons dashicons-download"></span> Export Duplicates to CSV
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div id="dpd-progress-container" class="dpd-progress-container" style="display: none;">
                <div class="dpd-progress-bar-wrapper">
                    <div id="dpd-progress-bar" class="dpd-progress-bar"></div>
                </div>
                <div class="dpd-progress-status" id="dpd-progress-status">Analyzing post slugs across database... Please wait.</div>
            </div>

            <!-- Results Section -->
            <div class="dpd-results-container">
                <div class="dpd-results-header">
                    <h2>Duplicate Slug Groups (<span id="dpd-results-count">0</span> groups detected)</h2>
                    <div class="dpd-results-meta" id="dpd-last-scanned-time">Click "Scan Duplicate Posts" to start analysis.</div>
                </div>

                <div class="dpd-table-responsive">
                    <table class="wp-list-table widefat fixed striped posts dpd-table" id="dpd-results-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 25%;">Base Slug Group</th>
                                <th style="width: 130px;">Duplicates Count</th>
                                <th>Duplicate Post Occurrences (Titles, Slugs & Links)</th>
                            </tr>
                        </thead>
                        <tbody id="dpd-results-tbody">
                            <tr class="dpd-empty-row">
                                <td colspan="4">
                                    <div class="dpd-empty-state">
                                        <span class="dashicons dashicons-search" style="font-size: 42px; width: 42px; height: 42px; color: #94a3b8;"></span>
                                        <h3>No scan performed yet</h3>
                                        <p>Click <strong>"Scan Duplicate Posts"</strong> above to search your WordPress database for duplicate post slugs.</p>
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
new Duplicate_Post_Detector_Admin();
