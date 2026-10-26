<?php
/**
 * SPC_Admin — Admin page + AJAX handlers for the bulk converter.
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPC_Admin
{

    private SPC_Converter $converter;

    public function __construct(SPC_Converter $converter)
    {
        $this->converter = $converter;

        add_action('admin_menu', array($this, 'register_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('wp_ajax_spc_scan', array($this, 'ajax_scan'));
        add_action('wp_ajax_spc_convert', array($this, 'ajax_convert'));
    }

    // ─────────────────────────────────────────────────────────────────
    // Menu
    // ─────────────────────────────────────────────────────────────────

    public function register_menu(): void
    {
        add_management_page(
            __('Pros & Cons Converter', 'simple-pros-cons'),
            __('Pros & Cons Converter', 'simple-pros-cons'),
            'edit_posts',
            'spc-converter',
            array($this, 'render_page')
        );
    }

    // ─────────────────────────────────────────────────────────────────
    // Assets
    // ─────────────────────────────────────────────────────────────────

    public function enqueue_assets(string $hook): void
    {
        if ($hook !== 'tools_page_spc-converter') {
            return;
        }

        wp_enqueue_style(
            'spc-admin-css',
            SPC_URL . 'assets/css/admin.css',
            array(),
            SPC_VERSION
        );

        wp_enqueue_script(
            'spc-admin-js',
            SPC_URL . 'assets/js/admin.js',
            array('jquery'),
            SPC_VERSION,
            true
        );

        wp_localize_script('spc-admin-js', 'spcAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('spc_converter_nonce'),
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // Admin Page HTML
    // ─────────────────────────────────────────────────────────────────

    public function render_page(): void
    {
        ?>
        <div class="wrap spc-admin-wrap">

            <div class="spc-page-header">
                <div class="spc-page-header-inner">
                    <div class="spc-page-title-area">
                        <span class="spc-logo">✓✗</span>
                        <div>
                            <h1>
                                <?php esc_html_e('Pros & Cons Bulk Converter', 'simple-pros-cons'); ?>
                            </h1>
                            <p>
                                <?php esc_html_e('Automatically detect Pros & Cons headings + lists in your posts and convert them to the Simple Pros & Cons block.', 'simple-pros-cons'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- How It Works -->
            <div class="spc-info-card">
                <h3>📋
                    <?php esc_html_e('How it works', 'simple-pros-cons'); ?>
                </h3>
                <p>
                    <?php esc_html_e('The converter looks for posts where you have written your pros and cons as headings (e.g. <strong>Pros</strong>, <strong>Advantages</strong>, <strong>Cons</strong>, <strong>Drawbacks</strong>) followed immediately by a bullet list. It replaces those sections with the beautiful Simple Pros & Cons block.', 'simple-pros-cons'); ?>
                </p>
                <div class="spc-example-row">
                    <div class="spc-example-before">
                        <strong>BEFORE</strong>
                        <code>&lt;h2&gt;Pros&lt;/h2&gt;<br>&lt;ul&gt;&lt;li&gt;Fast&lt;/li&gt;...&lt;/ul&gt;<br>&lt;h2&gt;Cons&lt;/h2&gt;<br>&lt;ul&gt;&lt;li&gt;Expensive&lt;/li&gt;...&lt;/ul&gt;</code>
                    </div>
                    <div class="spc-example-arrow">→</div>
                    <div class="spc-example-after">
                        <strong>AFTER</strong>
                        <code>&lt;!-- wp:simple-pros-cons/pros-cons {...} /--&gt;</code>
                    </div>
                </div>
                <p class="spc-tip">⚠️
                    <?php esc_html_e('Always back up your database before bulk converting.', 'simple-pros-cons'); ?>
                </p>
            </div>

            <!-- Scan Section -->
            <div class="spc-card">
                <div class="spc-card-body spc-scan-section">
                    <button id="spc-scan-btn" class="spc-btn spc-btn-primary spc-btn-large">
                        <span class="spc-btn-icon">🔍</span>
                        <?php esc_html_e('Scan All Posts & Pages', 'simple-pros-cons'); ?>
                    </button>
                    <div id="spc-scan-spinner" class="spc-spinner" style="display:none;"></div>
                    <p id="spc-scan-status" class="spc-scan-status"></p>
                </div>
            </div>

            <!-- Results -->
            <div id="spc-results" style="display:none;">

                <!-- Stats bar -->
                <div class="spc-stats-bar">
                    <div class="spc-stat-box" id="spc-stat-found">
                        <span class="spc-stat-num">0</span>
                        <span class="spc-stat-label">
                            <?php esc_html_e('Posts found', 'simple-pros-cons'); ?>
                        </span>
                    </div>
                    <div class="spc-stat-box spc-stat-success" id="spc-stat-done">
                        <span class="spc-stat-num">0</span>
                        <span class="spc-stat-label">
                            <?php esc_html_e('Converted', 'simple-pros-cons'); ?>
                        </span>
                    </div>
                    <div class="spc-stat-box spc-stat-error" id="spc-stat-error">
                        <span class="spc-stat-num">0</span>
                        <span class="spc-stat-label">
                            <?php esc_html_e('Errors', 'simple-pros-cons'); ?>
                        </span>
                    </div>
                </div>

                <!-- Convert All -->
                <div class="spc-card" id="spc-convert-all-card" style="display:none;">
                    <div class="spc-card-body spc-convert-all-bar">
                        <div>
                            <strong>
                                <?php esc_html_e('Convert all detected posts at once', 'simple-pros-cons'); ?>
                            </strong>
                            <p>
                                <?php esc_html_e('Posts are converted one at a time. Do not close this page.', 'simple-pros-cons'); ?>
                            </p>
                        </div>
                        <button id="spc-convert-all-btn" class="spc-btn spc-btn-success spc-btn-large">
                            ⚡
                            <?php esc_html_e('Convert All', 'simple-pros-cons'); ?>
                        </button>
                    </div>
                    <div id="spc-progress-bar-wrap" style="display:none;">
                        <div class="spc-progress-bar">
                            <div class="spc-progress-fill" id="spc-progress-fill"></div>
                        </div>
                        <p class="spc-progress-label" id="spc-progress-label"></p>
                    </div>
                </div>

                <!-- Posts Table -->
                <div class="spc-card">
                    <div class="spc-card-header">
                        <h3>
                            <?php esc_html_e('Posts with detectable Pros & Cons sections', 'simple-pros-cons'); ?>
                        </h3>
                    </div>
                    <div id="spc-posts-list"></div>
                </div>

            </div><!-- /#spc-results -->

            <!-- Empty State -->
            <div id="spc-empty" class="spc-empty-state" style="display:none;">
                <span class="spc-empty-icon">🎉</span>
                <h3>
                    <?php esc_html_e('All done!', 'simple-pros-cons'); ?>
                </h3>
                <p>
                    <?php esc_html_e('No posts with unconverted Pros & Cons headings were found.', 'simple-pros-cons'); ?>
                </p>
            </div>

        </div><!-- /.wrap -->
        <?php
    }

    // ─────────────────────────────────────────────────────────────────
    // AJAX — Scan
    // ─────────────────────────────────────────────────────────────────

    public function ajax_scan(): void
    {
        check_ajax_referer('spc_converter_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => 'Permission denied.'));
        }

        $posts = $this->converter->scan_posts();
        $payload = array();

        foreach ($posts as $post) {
            $detection = $post->spc_detection ?? array();
            $pairs = $detection['pairs'] ?? array();

            // Build a readable preview per pair
            $preview_pairs = array();
            foreach ($pairs as $pair) {
                $preview_pairs[] = array(
                    'pros_title' => esc_html($pair['pros_title'] ?? 'Pros'),
                    'pros_items' => array_map('esc_html', array_slice($pair['pros_items'], 0, 3)),
                    'cons_title' => esc_html($pair['cons_title'] ?? 'Cons'),
                    'cons_items' => array_map('esc_html', array_slice($pair['cons_items'], 0, 3)),
                    'pros_total' => count($pair['pros_items'] ?? array()),
                    'cons_total' => count($pair['cons_items'] ?? array()),
                );
            }

            $payload[] = array(
                'id' => $post->ID,
                'title' => esc_html($post->post_title ?: '(no title)'),
                'edit_link' => esc_url(get_edit_post_link($post->ID)),
                'view_link' => esc_url(get_permalink($post->ID)),
                'post_type' => esc_html($post->post_type),
                'count' => $detection['count'] ?? 0,
                'pairs' => $preview_pairs,
            );
        }

        wp_send_json_success(array('posts' => $payload, 'total' => count($payload)));
    }

    // ─────────────────────────────────────────────────────────────────
    // AJAX — Convert Single Post
    // ─────────────────────────────────────────────────────────────────

    public function ajax_convert(): void
    {
        check_ajax_referer('spc_converter_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => 'Permission denied.'));
        }

        $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
        if (!$post_id) {
            wp_send_json_error(array('message' => 'Invalid post ID.'));
        }

        $result = $this->converter->convert_post($post_id);

        if ($result['success']) {
            wp_send_json_success($result);
        } else {
            wp_send_json_error($result);
        }
    }
}
