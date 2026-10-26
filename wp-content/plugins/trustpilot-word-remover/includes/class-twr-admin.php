<?php
if (!defined('ABSPATH')) {
    exit;
}

class TWR_Admin {

    private $scanner;
    private $remover;

    public function __construct() {
        $this->scanner = new TWR_Scanner();
        $this->remover = new TWR_Remover();
    }

    public function init() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));

        // Cron hook
        add_action('twr_cron_process_queue', array($this, 'cron_process_queue'));

        // AJAX handlers
        add_action('wp_ajax_twr_scan_posts', array($this, 'ajax_scan_posts'));
        add_action('wp_ajax_twr_get_all_matching_ids', array($this, 'ajax_get_all_matching_ids'));
        add_action('wp_ajax_twr_remove_single', array($this, 'ajax_remove_single'));
        add_action('wp_ajax_twr_remove_bulk', array($this, 'ajax_remove_bulk'));
        add_action('wp_ajax_twr_get_preview', array($this, 'ajax_get_preview'));
        add_action('wp_ajax_twr_export_csv', array($this, 'ajax_export_csv'));
        add_action('wp_ajax_twr_start_slow_queue', array($this, 'ajax_start_slow_queue'));
        add_action('wp_ajax_twr_get_queue_status', array($this, 'ajax_get_queue_status'));
        add_action('wp_ajax_twr_control_queue', array($this, 'ajax_control_queue'));
        add_action('wp_ajax_twr_emergency_stop', array($this, 'ajax_emergency_stop'));
    }

    public function add_admin_menu() {
        add_menu_page(
            __('Trustpilot Word Remover', 'trustpilot-word-remover'),
            __('Trustpilot Finder', 'trustpilot-word-remover'),
            'manage_options',
            'trustpilot-word-remover',
            array($this, 'render_admin_page'),
            'dashicons-search',
            80
        );

        add_submenu_page(
            'tools.php',
            __('Trustpilot Word Remover', 'trustpilot-word-remover'),
            __('Trustpilot Remover', 'trustpilot-word-remover'),
            'manage_options',
            'trustpilot-word-remover-tools',
            array($this, 'render_admin_page')
        );
    }

    public function enqueue_assets($hook) {
        if (strpos($hook, 'trustpilot-word-remover') === false) {
            return;
        }

        wp_enqueue_style(
            'twr-admin-style',
            TWR_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            TWR_VERSION
        );

        wp_enqueue_script(
            'twr-admin-script',
            TWR_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            TWR_VERSION,
            true
        );

        wp_localize_script('twr-admin-script', 'twrData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('twr_admin_nonce'),
            'strings' => array(
                'confirmSingle'        => __('Are you sure you want to process "Trustpilot" in this post?', 'trustpilot-word-remover'),
                'confirmBulkRemove'    => __('Are you sure you want to REMOVE "Trustpilot" from %d selected post(s)?', 'trustpilot-word-remover'),
                'confirmBulkReplace'   => __('Are you sure you want to REPLACE "Trustpilot" with "%s" in %d selected post(s)?', 'trustpilot-word-remover'),
                'confirmReplaceAll'    => __('Are you sure you want to REPLACE "Trustpilot" with "%s" in ALL %d matching post(s) across the entire website at once?', 'trustpilot-word-remover'),
                'confirmRemoveAll'     => __('Are you sure you want to REMOVE "Trustpilot" from ALL %d matching post(s) across the entire website at once?', 'trustpilot-word-remover'),
                'promptReplacement'    => __('Enter the word to replace "Trustpilot" with:', 'trustpilot-word-remover'),
                'removing'             => __('Processing posts...', 'trustpilot-word-remover'),
                'scanning'             => __('Scanning database...', 'trustpilot-word-remover'),
                'noSelection'          => __('Please select at least one post from the list.', 'trustpilot-word-remover'),
                'noMatchingPosts'      => __('No matching posts found in the database.', 'trustpilot-word-remover'),
            )
        ));
    }

    public function render_admin_page() {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.', 'trustpilot-word-remover'));
        }

        $stats = $this->scanner->get_summary_stats();
        $post_types = $this->scanner->get_scannable_post_types();
        ?>
        <div class="wrap twr-wrap">
            <div class="twr-header">
                <div class="twr-header-title">
                    <div class="twr-icon-badge">
                        <span class="dashicons dashicons-search"></span>
                    </div>
                    <div>
                        <h1><?php _e('Trustpilot Word Scanner & Remover', 'trustpilot-word-remover'); ?></h1>
                        <p class="twr-subtitle"><?php _e('Detect posts containing "Trustpilot" and safely remove or replace occurrences in bulk with intelligent punctuation cleanup.', 'trustpilot-word-remover'); ?></p>
                    </div>
                </div>
                <div class="twr-header-badge">
                    v1.0.0
                </div>
            </div>

            <!-- Stats Dashboard Cards -->
            <div class="twr-stats-grid">
                <div class="twr-stat-card">
                    <div class="twr-stat-number" id="twr-stat-scanned"><?php echo esc_html(number_format_i18n($stats['total_scanned'])); ?></div>
                    <div class="twr-stat-label"><?php _e('Total Public Posts Scanned', 'trustpilot-word-remover'); ?></div>
                </div>
                <div class="twr-stat-card twr-stat-alert">
                    <div class="twr-stat-number" id="twr-stat-matched"><?php echo esc_html(number_format_i18n($stats['total_matched'])); ?></div>
                    <div class="twr-stat-label"><?php _e('Posts Containing "Trustpilot"', 'trustpilot-word-remover'); ?></div>
                </div>
                <div class="twr-stat-card twr-stat-highlight">
                    <div class="twr-stat-number" id="twr-stat-occurrences"><?php echo esc_html(number_format_i18n($stats['total_occurrences'])); ?></div>
                    <div class="twr-stat-label"><?php _e('Total "Trustpilot" Matches Found', 'trustpilot-word-remover'); ?></div>
                </div>
            </div>

            <!-- Background Job Active Banner -->
            <div id="twr-slow-job-banner" class="twr-card twr-slow-banner" style="display:none;">
                <div class="twr-slow-banner-content">
                    <div class="twr-slow-banner-left">
                        <span class="twr-pulse-dot" id="twr-slow-pulse"></span>
                        <div>
                            <h4 id="twr-slow-banner-title"><?php _e('Background Slow Job Active', 'trustpilot-word-remover'); ?></h4>
                            <p id="twr-slow-banner-desc"><?php _e('Processing replacements slowly in background over 4 hours...', 'trustpilot-word-remover'); ?></p>
                        </div>
                    </div>
                    <div class="twr-slow-banner-right">
                        <span id="twr-slow-eta-badge" class="twr-badge-eta">ETA: Calculating...</span>
                        <button type="button" id="twr-btn-queue-pause" class="button button-secondary"><?php _e('Pause', 'trustpilot-word-remover'); ?></button>
                        <button type="button" id="twr-btn-queue-resume" class="button button-primary" style="display:none;"><?php _e('Resume', 'trustpilot-word-remover'); ?></button>
                        <button type="button" id="twr-btn-queue-cancel" class="button button-secondary button-danger"><?php _e('Cancel Job', 'trustpilot-word-remover'); ?></button>
                        <button type="button" class="button button-danger button-emergency-stop twr-btn-emergency-stop">
                            <span class="dashicons dashicons-warning"></span> <?php _e('🚨 EMERGENCY STOP', 'trustpilot-word-remover'); ?>
                        </button>
                    </div>
                </div>
                <div class="twr-slow-progress-bg">
                    <div id="twr-slow-progress-fill" class="twr-slow-progress-fill" style="width: 0%;"></div>
                </div>
            </div>

            <!-- Action Toolbar & Filters -->
            <div class="twr-card twr-toolbar">
                <div class="twr-filter-row">
                    <div class="twr-filter-group">
                        <label for="twr-search-input"><?php _e('Search Posts:', 'trustpilot-word-remover'); ?></label>
                        <input type="text" id="twr-search-input" class="regular-text" placeholder="<?php _e('Filter by post title or ID...', 'trustpilot-word-remover'); ?>">
                    </div>

                    <div class="twr-filter-group">
                        <label for="twr-post-type-filter"><?php _e('Post Type:', 'trustpilot-word-remover'); ?></label>
                        <select id="twr-post-type-filter">
                            <option value=""><?php _e('All Post Types', 'trustpilot-word-remover'); ?></option>
                            <?php foreach ($post_types as $pt): ?>
                                <option value="<?php echo esc_attr($pt); ?>"><?php echo esc_html(ucfirst($pt)); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="twr-filter-group">
                        <label for="twr-replacement-input"><?php _e('Replace Word With:', 'trustpilot-word-remover'); ?></label>
                        <input type="text" id="twr-replacement-input" class="regular-text" placeholder="<?php _e('e.g. Consumer reviews (or leave empty to delete)', 'trustpilot-word-remover'); ?>">
                    </div>

                    <div class="twr-filter-group">
                        <label for="twr-pace-selector"><?php _e('Execution Pace:', 'trustpilot-word-remover'); ?></label>
                        <select id="twr-pace-selector">
                            <option value="4hours" selected>🐢 Slow Pace (Spreads over 4 Hours)</option>
                            <option value="1hour">⏱️ Gradual (Spreads over 1 Hour)</option>
                            <option value="instant">⚡ Instant (Fastest AJAX)</option>
                        </select>
                    </div>

                    <div class="twr-btn-group">
                        <button type="button" id="twr-btn-scan" class="button button-primary button-hero">
                            <span class="dashicons dashicons-search"></span> <?php _e('Scan / Refresh List', 'trustpilot-word-remover'); ?>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div id="twr-progress-container" class="twr-card twr-progress-card" style="display:none;">
                <div class="twr-progress-header">
                    <span id="twr-progress-title"><?php _e('Processing...', 'trustpilot-word-remover'); ?></span>
                    <span id="twr-progress-percentage">0%</span>
                </div>
                <div class="twr-progress-bar-bg">
                    <div id="twr-progress-bar-fill" class="twr-progress-bar-fill" style="width: 0%;"></div>
                </div>
                <div id="twr-progress-status" class="twr-progress-status"></div>
            </div>

            <!-- Batch Operations Bar -->
            <div class="twr-batch-actions-bar">
                <div class="twr-batch-left">
                    <button type="button" id="twr-btn-replace-all" class="button button-primary button-replace-all">
                        <span class="dashicons dashicons-superhero"></span> <?php _e('Replace in ALL Posts (Global)', 'trustpilot-word-remover'); ?>
                    </button>
                    <button type="button" id="twr-btn-remove-all" class="button button-secondary button-remove-all">
                        <span class="dashicons dashicons-trash"></span> <?php _e('Remove in ALL Posts (Global)', 'trustpilot-word-remover'); ?>
                    </button>
                    <span class="twr-batch-divider">|</span>
                    <button type="button" id="twr-btn-bulk-replace" class="button button-primary button-replace">
                        <span class="dashicons dashicons-update"></span> <?php _e('Replace Selected', 'trustpilot-word-remover'); ?>
                    </button>
                    <button type="button" id="twr-btn-bulk-remove" class="button button-secondary button-danger">
                        <span class="dashicons dashicons-trash"></span> <?php _e('Remove Selected', 'trustpilot-word-remover'); ?>
                    </button>
                    <span class="twr-selected-counter" id="twr-selected-count">0 posts selected</span>
                </div>
                <div class="twr-batch-right">
                    <button type="button" class="button button-danger button-emergency-stop twr-btn-emergency-stop">
                        <span class="dashicons dashicons-warning"></span> <?php _e('🚨 EMERGENCY STOP', 'trustpilot-word-remover'); ?>
                    </button>
                    <button type="button" id="twr-btn-export-csv" class="button button-secondary">
                        <span class="dashicons dashicons-download"></span> <?php _e('Export Results CSV', 'trustpilot-word-remover'); ?>
                    </button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="twr-table-container">
                <table class="wp-list-table widefat fixed striped posts twr-table">
                    <thead>
                        <tr>
                            <td id="cb" class="manage-column column-cb check-column">
                                <input type="checkbox" id="twr-cb-select-all">
                            </td>
                            <th scope="col" class="column-id" style="width: 75px;"><?php _e('ID', 'trustpilot-word-remover'); ?></th>
                            <th scope="col" class="column-title" style="width: 250px;"><?php _e('Post Title & Status', 'trustpilot-word-remover'); ?></th>
                            <th scope="col" class="column-type" style="width: 110px;"><?php _e('Type', 'trustpilot-word-remover'); ?></th>
                            <th scope="col" class="column-count" style="width: 120px;"><?php _e('Matches', 'trustpilot-word-remover'); ?></th>
                            <th scope="col" class="column-snippets"><?php _e('Context Snippets', 'trustpilot-word-remover'); ?></th>
                            <th scope="col" class="column-actions" style="width: 170px;"><?php _e('Actions', 'trustpilot-word-remover'); ?></th>
                        </tr>
                    </thead>
                    <tbody id="twr-table-body">
                        <tr>
                            <td colspan="7">
                                <div class="twr-empty-container">
                                    <span class="dashicons dashicons-search twr-empty-icon"></span>
                                    <h3 class="twr-empty-title"><?php _e('Ready to Scan', 'trustpilot-word-remover'); ?></h3>
                                    <p class="twr-empty-desc"><?php _e('Click "Scan / Refresh List" above to detect posts containing "Trustpilot".', 'trustpilot-word-remover'); ?></p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            <div class="twr-pagination-bar" id="twr-pagination-container" style="display:none;">
                <div class="twr-pagination-info" id="twr-pagination-info"></div>
                <div class="twr-pagination-links" id="twr-pagination-links"></div>
            </div>
        </div>

        <!-- Diff Preview Modal -->
        <div id="twr-modal-overlay" class="twr-modal-overlay" style="display:none;">
            <div class="twr-modal-content">
                <div class="twr-modal-header">
                    <h3 id="twr-modal-title"><?php _e('Preview Changes', 'trustpilot-word-remover'); ?></h3>
                    <button type="button" class="twr-modal-close" id="twr-modal-close">&times;</button>
                </div>
                <div class="twr-modal-body" id="twr-modal-body">
                    <!-- Loaded dynamically via AJAX -->
                </div>
                <div class="twr-modal-footer">
                    <button type="button" id="twr-modal-confirm-remove" class="button button-primary">
                        <?php _e('Confirm & Remove Now', 'trustpilot-word-remover'); ?>
                    </button>
                    <button type="button" class="button button-secondary" id="twr-modal-cancel">
                        <?php _e('Cancel', 'trustpilot-word-remover'); ?>
                    </button>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * AJAX Endpoint: Scan posts
     */
    public function ajax_scan_posts() {
        check_ajax_referer('twr_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'trustpilot-word-remover')));
        }

        $paged     = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;
        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : '';
        $search    = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

        $scan_data = $this->scanner->scan_posts(array(
            'posts_per_page' => 20,
            'paged'          => $paged,
            'post_type'      => $post_type,
            'search'         => $search,
        ));

        $stats = $this->scanner->get_summary_stats();

        wp_send_json_success(array(
            'items'       => $scan_data['items'],
            'total'       => $scan_data['total'],
            'max_pages'   => $scan_data['max_pages'],
            'paged'       => $paged,
            'stats'       => $stats,
        ));
    }

    /**
     * AJAX Endpoint: Get all matching post IDs for global batch operations
     */
    public function ajax_get_all_matching_ids() {
        check_ajax_referer('twr_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'trustpilot-word-remover')));
        }

        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : '';
        $search    = isset($_POST['search']) ? sanitize_text_field($_POST['search']) : '';

        $ids = $this->scanner->get_all_matching_post_ids(array(
            'post_type' => $post_type,
            'search'    => $search,
        ));

        wp_send_json_success(array(
            'post_ids' => $ids,
            'total'    => count($ids),
        ));
    }

    /**
     * Cron Callback: Process background queue batch
     */
    public function cron_process_queue() {
        $this->remover->process_next_queue_batch();
    }

    /**
     * AJAX Endpoint: Start background slow queue
     */
    public function ajax_start_slow_queue() {
        check_ajax_referer('twr_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'trustpilot-word-remover')));
        }

        $post_ids       = isset($_POST['post_ids']) && is_array($_POST['post_ids']) ? array_map('intval', $_POST['post_ids']) : array();
        $replacement    = isset($_POST['replacement']) ? sanitize_text_field($_POST['replacement']) : '';
        $duration_hours = isset($_POST['duration_hours']) ? floatval($_POST['duration_hours']) : 4;

        if (empty($post_ids)) {
            wp_send_json_error(array('message' => __('No post IDs provided.', 'trustpilot-word-remover')));
        }

        $res = $this->remover->start_slow_queue($post_ids, $replacement, $duration_hours);
        if ($res) {
            wp_send_json_success($this->remover->get_queue_status());
        } else {
            wp_send_json_error(array('message' => __('Failed to start slow queue.', 'trustpilot-word-remover')));
        }
    }

    /**
     * AJAX Endpoint: Get queue status
     */
    public function ajax_get_queue_status() {
        check_ajax_referer('twr_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'trustpilot-word-remover')));
        }

        wp_send_json_success($this->remover->get_queue_status());
    }

    /**
     * AJAX Endpoint: Pause, Resume, or Cancel queue
     */
    public function ajax_control_queue() {
        check_ajax_referer('twr_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'trustpilot-word-remover')));
        }

        $queue_action = isset($_POST['queue_action']) ? sanitize_text_field($_POST['queue_action']) : '';
        $res = $this->remover->control_queue($queue_action);

        if ($res) {
            wp_send_json_success($this->remover->get_queue_status());
        } else {
            wp_send_json_error(array('message' => __('Action failed.', 'trustpilot-word-remover')));
        }
    }

    /**
     * AJAX Endpoint: Emergency Stop
     */
    public function ajax_emergency_stop() {
        check_ajax_referer('twr_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'trustpilot-word-remover')));
        }

        $this->remover->emergency_stop();

        wp_send_json_success(array(
            'message' => __('🚨 EMERGENCY STOP EXECUTED: All background queues, scheduled tasks, and batch operations have been forcefully killed!', 'trustpilot-word-remover'),
            'queue'   => $this->remover->get_queue_status(),
        ));
    }

    /**
     * AJAX Endpoint: Remove single post match
     */
    public function ajax_remove_single() {
        check_ajax_referer('twr_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'trustpilot-word-remover')));
        }

        $post_id     = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $replacement = isset($_POST['replacement']) ? sanitize_text_field($_POST['replacement']) : '';

        if (!$post_id) {
            wp_send_json_error(array('message' => __('Invalid post ID.', 'trustpilot-word-remover')));
        }

        $result = $this->remover->clean_post($post_id, 'Trustpilot', $replacement);

        if ($result['success']) {
            $stats = $this->scanner->get_summary_stats();
            $result['stats'] = $stats;
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }

    /**
     * AJAX Endpoint: Remove bulk selected posts
     */
    public function ajax_remove_bulk() {
        check_ajax_referer('twr_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'trustpilot-word-remover')));
        }

        $post_ids    = isset($_POST['post_ids']) && is_array($_POST['post_ids']) ? array_map('intval', $_POST['post_ids']) : array();
        $replacement = isset($_POST['replacement']) ? sanitize_text_field($_POST['replacement']) : '';

        if (empty($post_ids)) {
            wp_send_json_error(array('message' => __('No post IDs provided.', 'trustpilot-word-remover')));
        }

        $cleaned_count = 0;
        $total_occurrences_removed = 0;
        $errors = array();

        foreach ($post_ids as $pid) {
            $res = $this->remover->clean_post($pid, 'Trustpilot', $replacement);
            if ($res['success']) {
                if (!empty($res['updated'])) {
                    $cleaned_count++;
                    $total_occurrences_removed += isset($res['removed_count']) ? $res['removed_count'] : 0;
                }
            } else {
                $errors[] = sprintf(__('Post #%d error: %s', 'trustpilot-word-remover'), $pid, $res['message']);
            }
        }

        $stats = $this->scanner->get_summary_stats();

        wp_send_json_success(array(
            'message'                   => sprintf(__('Bulk process completed. Cleaned %d post(s), removed %d occurrence(s).', 'trustpilot-word-remover'), $cleaned_count, $total_occurrences_removed),
            'cleaned_count'             => $cleaned_count,
            'total_occurrences_removed' => $total_occurrences_removed,
            'errors'                    => $errors,
            'stats'                     => $stats,
        ));
    }

    /**
     * AJAX Endpoint: Get Diff Preview
     */
    public function ajax_get_preview() {
        check_ajax_referer('twr_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'trustpilot-word-remover')));
        }

        $post_id     = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $replacement = isset($_POST['replacement']) ? sanitize_text_field($_POST['replacement']) : '';

        $preview = $this->remover->generate_diff_preview($post_id, 'Trustpilot', $replacement);

        if ($preview['success']) {
            wp_send_json_success($preview);
        } else {
            wp_send_json_error($preview);
        }
    }

    /**
     * AJAX Endpoint: Export CSV
     */
    public function ajax_export_csv() {
        check_ajax_referer('twr_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die(__('Permission denied.', 'trustpilot-word-remover'));
        }

        $scan_data = $this->scanner->scan_posts(array(
            'posts_per_page' => -1, // get all
        ));

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=trustpilot_posts_export_' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, array('Post ID', 'Post Title', 'Post Type', 'Status', 'Match Count', 'Permalink', 'Context Snippets'));

        foreach ($scan_data['items'] as $item) {
            $snippets_clean = array_map('strip_tags', $item['snippets']);
            fputcsv($output, array(
                $item['id'],
                $item['title'],
                $item['post_type'],
                $item['post_status'],
                $item['occurrences'],
                $item['view_link'],
                implode(' | ', $snippets_clean),
            ));
        }

        fclose($output);
        exit;
    }
}
