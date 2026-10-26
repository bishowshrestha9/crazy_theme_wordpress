<?php
/**
 * Plugin Name: Product Card 'n' Cleaner
 * Plugin URI: https://yourwebsite.com
 * Description: Scans all WordPress posts/pages for Product Cards with unwanted '.n', '!n', '?n', 'nn', or 'nnn' appended to sentences (in User Experience, Verdict, and Expert Reviews) and replaces 'nn' / 'nnn' with '.' and cleans '!n' / '.n' with interactive preview and one-click removal.
 * Version: 1.2.0
 * Author: Bishow Shrestha
 * License: GPL v2 or later
 * Text Domain: product-card-n-cleaner
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProductCardNCleaner {

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

        // AJAX handlers
        add_action('wp_ajax_pcpc_scan_posts', array($this, 'ajax_scan_posts'));
        add_action('wp_ajax_pcpc_fix_post', array($this, 'ajax_fix_post'));
        add_action('wp_ajax_pcpc_fix_all', array($this, 'ajax_fix_all'));
        add_action('wp_ajax_pcpc_preview_card', array($this, 'ajax_preview_card'));
        add_action('wp_ajax_pcpc_restore_post', array($this, 'ajax_restore_post'));

        // Frontend auto-clean hook
        add_filter('render_block', array($this, 'filter_frontend_render_block'), 10, 2);
    }

    public function add_admin_menu() {
        add_management_page(
            'Product Card \'n\' Cleaner',
            'Product Card \'n\' Cleaner',
            'manage_options',
            'product-card-n-cleaner',
            array($this, 'render_admin_page')
        );

        add_menu_page(
            'Product Card \'n\' Cleaner',
            'Card \'n\' Cleaner',
            'manage_options',
            'product-card-n-cleaner-main',
            array($this, 'render_admin_page'),
            'dashicons-clean',
            81
        );
    }

    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'product-card-n-cleaner') === false) {
            return;
        }

        $dir_url = plugin_dir_url(__FILE__);
        $version = '1.2.0';

        wp_enqueue_style('pcpc-admin-style', $dir_url . 'assets/css/admin.css', array(), $version);
        
        $card_plugin_css = WP_PLUGIN_DIR . '/ProductCardPlugin-TBR 4/assets/css/product-card.css';
        if (file_exists($card_plugin_css)) {
            wp_enqueue_style('pcpc-product-card-css', plugins_url('ProductCardPlugin-TBR 4/assets/css/product-card.css'), array(), $version);
        }

        wp_enqueue_script('pcpc-admin-script', $dir_url . 'assets/js/admin.js', array('jquery'), $version, true);

        wp_localize_script('pcpc-admin-script', 'pcpcData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('pcpc_admin_nonce'),
        ));
    }

    /**
     * Helper to clean '.n', '!n', '?n', 'nn', 'nnn' artifacts in target text
     * Replaces 'nn' / 'nnn' with '.' and strips trailing 'n' after ! or ?
     */
    public function clean_n_text($text) {
        if (empty($text) || !is_string($text)) {
            return $text;
        }

        $cleaned = $text;

        // Step 1: Handle !n/!nn/!nnn or ?n/?nn/?nnn -> preserve ! or ?
        $cleaned = preg_replace('/([\!\?])\s*n+/i', '$1', $cleaned);

        // Step 2: Handle .n/.nn/.nnn -> preserve .
        $cleaned = preg_replace('/\.+\s*n+/i', '.', $cleaned);

        // Step 3: Replace any standalone or word-attached 'nn', 'nnn', 'n{2,}' with '.'
        $cleaned = preg_replace('/\s*n{2,}(?=[\s"\'\`\.\,\?\!]|$)/i', '.', $cleaned);

        // Step 4: Handle single 'n' at end of sentence/word if preceded by word without punctuation
        $cleaned = preg_replace('/\.n/i', '.', $cleaned);

        // Step 5: Cleanup double dots or trailing punctuation artifacts produced
        $cleaned = preg_replace('/\.\.+/', '.', $cleaned);
        $cleaned = preg_replace('/([\!\?])\./', '$1', $cleaned);

        return trim($cleaned);
    }

    /**
     * Check if a string has unwanted '.n', '!n', '?n', 'nn', 'nnn' issues
     */
    public function has_n_issue($text) {
        if (empty($text) || !is_string($text)) {
            return false;
        }

        // Check for punctuation followed by n (e.g. .n, !n, ?n, .nn, !nnn)
        if (preg_match('/[\.\!\?]\s*n+/i', $text)) {
            return true;
        }

        // Check for repeated n's (nn, nnn, nnnn)
        if (preg_match('/n{2,}(?=[\s"\'\`\.\,\?\!]|$)/i', $text)) {
            return true;
        }

        return false;
    }

    /**
     * Target attribute keys in Product Card block
     */
    private function get_target_attributes() {
        return array(
            'userExperience' => 'User Experience',
            'verdict'        => 'Verdict',
            'expertReview1'  => 'Expert Review (Bullet 1)',
            'expertReview2'  => 'Expert Review (Bullet 2)',
            'expertReview3'  => 'Expert Review (Bullet 3)',
        );
    }

    /**
     * Scan posts for Product Card blocks with '.n', '!n', 'nn', 'nnn'
     */
    public function ajax_scan_posts() {
        check_ajax_referer('pcpc_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized user'));
        }

        $post_types = get_post_types(array('public' => true), 'names');
        $args = array(
            'post_type'      => array_values($post_types),
            'post_status'    => array('publish', 'draft', 'private', 'pending', 'future'),
            'posts_per_page' => -1,
            'fields'         => 'ids',
        );

        $query = new WP_Query($args);
        $post_ids = $query->posts;

        $results = array();
        $total_cards_scanned = 0;
        $flagged_cards_count = 0;

        $target_attrs = $this->get_target_attributes();

        foreach ($post_ids as $pid) {
            $post = get_post($pid);
            if (!$post || empty($post->post_content)) {
                continue;
            }

            if (strpos($post->post_content, 'product-card') === false && strpos($post->post_content, 'expertReview') === false) {
                continue;
            }

            $blocks = parse_blocks($post->post_content);
            $card_blocks_in_post = array();

            $this->extract_product_card_blocks($blocks, $card_blocks_in_post);

            if (empty($card_blocks_in_post)) {
                $this->extract_blocks_by_regex($post->post_content, $card_blocks_in_post);
            }

            if (empty($card_blocks_in_post)) {
                continue;
            }

            $post_has_issue = false;
            $post_cards_data = array();

            foreach ($card_blocks_in_post as $b_idx => $block_attrs) {
                $total_cards_scanned++;
                $card_affected_fields = array();
                $card_has_issue = false;

                foreach ($target_attrs as $attr_key => $attr_label) {
                    $val = isset($block_attrs[$attr_key]) ? $block_attrs[$attr_key] : '';
                    if (!empty($val) && $this->has_n_issue($val)) {
                        $card_has_issue = true;
                        $post_has_issue = true;
                        $clean_val = $this->clean_n_text($val);
                        $card_affected_fields[] = array(
                            'key'          => $attr_key,
                            'label'        => $attr_label,
                            'original_val' => $val,
                            'clean_val'    => $clean_val,
                            'has_issue'    => true,
                        );
                    } else if (!empty($val)) {
                        $card_affected_fields[] = array(
                            'key'          => $attr_key,
                            'label'        => $attr_label,
                            'original_val' => $val,
                            'clean_val'    => $val,
                            'has_issue'    => false,
                        );
                    }
                }

                if ($card_has_issue) {
                    $flagged_cards_count++;
                }

                $post_cards_data[] = array(
                    'block_index'     => $b_idx,
                    'attrs'           => $block_attrs,
                    'has_issue'       => $card_has_issue,
                    'affected_fields' => $card_affected_fields,
                );
            }

            if ($post_has_issue) {
                $has_backup = (bool) get_post_meta($pid, '_pcpc_content_backup', true);

                $results[] = array(
                    'post_id'     => $pid,
                    'title'       => get_the_title($pid) ? get_the_title($pid) : '(No Title #' . $pid . ')',
                    'post_type'   => get_post_type($pid),
                    'edit_url'    => get_edit_post_link($pid, 'raw'),
                    'view_url'    => get_permalink($pid),
                    'cards'       => $post_cards_data,
                    'has_backup'  => $has_backup,
                );
            }
        }

        wp_send_json_success(array(
            'total_posts_scanned' => count($post_ids),
            'flagged_posts_count' => count($results),
            'total_cards_scanned' => $total_cards_scanned,
            'flagged_cards_count' => $flagged_cards_count,
            'posts'               => $results,
        ));
    }

    private function extract_product_card_blocks($blocks, &$results) {
        foreach ($blocks as $block) {
            if (isset($block['blockName']) && (
                strpos($block['blockName'], 'product-card') !== false ||
                $block['blockName'] === 'product-card-plugin/product-card'
            )) {
                $results[] = isset($block['attrs']) ? $block['attrs'] : array();
            }

            if (!empty($block['innerBlocks'])) {
                $this->extract_product_card_blocks($block['innerBlocks'], $results);
            }
        }
    }

    private function extract_blocks_by_regex($post_content, &$results) {
        if (preg_match_all('/<!--\s+wp:(?:product-card-plugin\/product-card|[\w\-]+\/product-card)\s+({.*?})\s+\/-->/s', $post_content, $matches)) {
            foreach ($matches[1] as $json_str) {
                $decoded = json_decode($json_str, true);
                if (is_array($decoded)) {
                    $results[] = $decoded;
                }
            }
        }
    }

    public function ajax_fix_post() {
        check_ajax_referer('pcpc_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized user'));
        }

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        if (!$post_id) {
            wp_send_json_error(array('message' => 'Invalid Post ID'));
        }

        $post = get_post($post_id);
        if (!$post) {
            wp_send_json_error(array('message' => 'Post not found'));
        }

        if (!get_post_meta($post_id, '_pcpc_content_backup', true)) {
            update_post_meta($post_id, '_pcpc_content_backup', $post->post_content);
            update_post_meta($post_id, '_pcpc_content_backup_time', current_time('mysql'));
        }

        $updated_content = $post->post_content;
        $fixed_count = 0;
        $target_attrs = array_keys($this->get_target_attributes());

        $updated_content = preg_replace_callback(
            '/<!--\s+wp:(product-card-plugin\/product-card|[\w\-]+\/product-card)\s+({.*?})\s+\/-->/s',
            function ($matches) use (&$fixed_count, $target_attrs) {
                $block_name = $matches[1];
                $json_str   = $matches[2];
                $attrs      = json_decode($json_str, true);

                if (!is_array($attrs)) {
                    return $matches[0];
                }

                $modified = false;
                foreach ($target_attrs as $key) {
                    if (isset($attrs[$key]) && is_string($attrs[$key])) {
                        if ($this->has_n_issue($attrs[$key])) {
                            $attrs[$key] = $this->clean_n_text($attrs[$key]);
                            $modified = true;
                        }
                    }
                }

                if ($modified) {
                    $fixed_count++;
                    $new_json = wp_json_encode($attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                    return '<!-- wp:' . $block_name . ' ' . $new_json . ' /-->';
                }

                return $matches[0];
            },
            $updated_content
        );

        if ($updated_content !== $post->post_content) {
            wp_update_post(array(
                'ID'           => $post_id,
                'post_content' => $updated_content,
            ));

            wp_send_json_success(array(
                'message'     => 'Post #' . $post_id . ' updated successfully! Cleaned ' . $fixed_count . ' card(s).',
                'fixed_count' => $fixed_count,
                'has_backup'  => true,
            ));
        } else {
            wp_send_json_success(array(
                'message'     => 'No changes needed for Post #' . $post_id,
                'fixed_count' => 0,
                'has_backup'  => (bool) get_post_meta($post_id, '_pcpc_content_backup', true),
            ));
        }
    }

    public function ajax_fix_all() {
        check_ajax_referer('pcpc_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized user'));
        }

        $post_ids = isset($_POST['post_ids']) ? array_map('intval', (array) $_POST['post_ids']) : array();

        if (empty($post_ids)) {
            wp_send_json_error(array('message' => 'No posts provided to fix'));
        }

        $fixed_posts = 0;
        $total_cards_fixed = 0;
        $target_attrs = array_keys($this->get_target_attributes());

        foreach ($post_ids as $post_id) {
            $post = get_post($post_id);
            if (!$post) continue;

            if (!get_post_meta($post_id, '_pcpc_content_backup', true)) {
                update_post_meta($post_id, '_pcpc_content_backup', $post->post_content);
                update_post_meta($post_id, '_pcpc_content_backup_time', current_time('mysql'));
            }

            $updated_content = preg_replace_callback(
                '/<!--\s+wp:(product-card-plugin\/product-card|[\w\-]+\/product-card)\s+({.*?})\s+\/-->/s',
                function ($matches) use (&$total_cards_fixed, $target_attrs) {
                    $block_name = $matches[1];
                    $json_str   = $matches[2];
                    $attrs      = json_decode($json_str, true);

                    if (!is_array($attrs)) {
                        return $matches[0];
                    }

                    $modified = false;
                    foreach ($target_attrs as $key) {
                        if (isset($attrs[$key]) && is_string($attrs[$key])) {
                            if ($this->has_n_issue($attrs[$key])) {
                                $attrs[$key] = $this->clean_n_text($attrs[$key]);
                                $modified = true;
                            }
                        }
                    }

                    if ($modified) {
                        $total_cards_fixed++;
                        $new_json = wp_json_encode($attrs, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                        return '<!-- wp:' . $block_name . ' ' . $new_json . ' /-->';
                    }

                    return $matches[0];
                },
                $post->post_content
            );

            if ($updated_content !== $post->post_content) {
                wp_update_post(array(
                    'ID'           => $post_id,
                    'post_content' => $updated_content,
                ));
                $fixed_posts++;
            }
        }

        wp_send_json_success(array(
            'message'           => 'Successfully cleaned ' . $fixed_posts . ' post(s) containing ' . $total_cards_fixed . ' product card(s)!',
            'fixed_posts_count' => $fixed_posts,
            'total_cards_fixed' => $total_cards_fixed,
        ));
    }

    public function ajax_restore_post() {
        check_ajax_referer('pcpc_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized user'));
        }

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $backup = get_post_meta($post_id, '_pcpc_content_backup', true);

        if (!$backup) {
            wp_send_json_error(array('message' => 'No backup found for Post #' . $post_id));
        }

        wp_update_post(array(
            'ID'           => $post_id,
            'post_content' => $backup,
        ));

        delete_post_meta($post_id, '_pcpc_content_backup');
        delete_post_meta($post_id, '_pcpc_content_backup_time');

        wp_send_json_success(array(
            'message' => 'Post #' . $post_id . ' has been restored to its original state before cleaning.',
        ));
    }

    public function ajax_preview_card() {
        check_ajax_referer('pcpc_admin_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized user'));
        }

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $block_index = isset($_POST['block_index']) ? intval($_POST['block_index']) : 0;

        $post = get_post($post_id);
        if (!$post) {
            wp_send_json_error(array('message' => 'Post not found'));
        }

        $card_blocks = array();
        $blocks = parse_blocks($post->post_content);
        $this->extract_product_card_blocks($blocks, $card_blocks);

        if (empty($card_blocks)) {
            $this->extract_blocks_by_regex($post->post_content, $card_blocks);
        }

        $attrs = isset($card_blocks[$block_index]) ? $card_blocks[$block_index] : array();

        if (empty($attrs) && !empty($card_blocks[0])) {
            $attrs = $card_blocks[0];
        }

        $cleaned_attrs = $attrs;
        foreach ($this->get_target_attributes() as $key => $label) {
            if (isset($cleaned_attrs[$key]) && is_string($cleaned_attrs[$key])) {
                $cleaned_attrs[$key] = $this->clean_n_text($cleaned_attrs[$key]);
            }
        }

        $html_before = $this->render_card_html($attrs, 'before');
        $html_after  = $this->render_card_html($cleaned_attrs, 'after');

        wp_send_json_success(array(
            'post_title'  => get_the_title($post_id),
            'html_before' => $html_before,
            'html_after'  => $html_after,
            'attrs'       => $attrs,
            'clean_attrs' => $cleaned_attrs,
        ));
    }

    private function render_card_html($attributes, $type = 'before') {
        $status          = isset($attributes['status']) ? $attributes['status'] : 'legit';
        $productName     = isset($attributes['productName']) ? $attributes['productName'] : 'Product Name';
        $imageUrl        = isset($attributes['imageUrl']) ? $attributes['imageUrl'] : '';
        $userExperience  = isset($attributes['userExperience']) ? $attributes['userExperience'] : '';
        $expertReview1   = isset($attributes['expertReview1']) ? $attributes['expertReview1'] : '';
        $expertReview2   = isset($attributes['expertReview2']) ? $attributes['expertReview2'] : '';
        $expertReview3   = isset($attributes['expertReview3']) ? $attributes['expertReview3'] : '';
        $verdict         = isset($attributes['verdict']) ? $attributes['verdict'] : '';
        $rating          = isset($attributes['rating']) ? $attributes['rating'] : '4.9';
        $readReviewsUrl  = isset($attributes['readReviewsUrl']) ? $attributes['readReviewsUrl'] : '#';
        $price           = isset($attributes['price']) ? $attributes['price'] : '69';
        $originalPrice   = isset($attributes['originalPrice']) ? $attributes['originalPrice'] : '99';
        $currency        = isset($attributes['currency']) ? $attributes['currency'] : '$';
        $ctaLabel        = isset($attributes['ctaLabel']) ? $attributes['ctaLabel'] : 'Buy Now';
        $headerColor     = isset($attributes['headerColor']) ? $attributes['headerColor'] : '#000000';

        $cardClass       = $status === 'scam' ? 'pcpx-card-scam' : 'pcpx-card-legit';
        $reviewHeaderText = ($productName ? $productName . ' ' : '') . 'Review Summary';

        $ux_display      = ($type === 'before') ? $this->highlight_n($userExperience) : esc_html($userExperience);
        $er1_display     = ($type === 'before') ? $this->highlight_n($expertReview1) : esc_html($expertReview1);
        $er2_display     = ($type === 'before') ? $this->highlight_n($expertReview2) : esc_html($expertReview2);
        $er3_display     = ($type === 'before') ? $this->highlight_n($expertReview3) : esc_html($expertReview3);
        $verdict_display = ($type === 'before') ? $this->highlight_n($verdict) : esc_html($verdict);

        ob_start();
        ?>
        <div class="pcpc-preview-card-wrapper pcpc-preview-<?php echo esc_attr($type); ?>">
            <div class="pcpx-product-card-outer">
                <div class="pcpx-card <?php echo esc_attr($cardClass); ?>">
                    <div class="pcpx-review-summary-header-main" style="color: #ffffff;">
                        <?php echo esc_html($reviewHeaderText); ?>
                    </div>
                    <div class="pcpx-card-inner">
                        <div class="pcpx-image-column">
                            <div class="pcpx-image-container">
                                <?php if ($imageUrl): ?>
                                    <img src="<?php echo esc_url($imageUrl); ?>" alt="<?php echo esc_attr($productName); ?>" class="pcpx-product-image" />
                                <?php else: ?>
                                    <div class="pcpx-image-placeholder">No Image</div>
                                <?php endif; ?>
                            </div>
                            <div class="pcpx-price-section">
                                <?php if ($originalPrice && $originalPrice !== $price): ?>
                                    <span class="pcpx-original-price"><?php echo esc_html($currency . $originalPrice); ?></span>
                                <?php endif; ?>
                                <span class="pcpx-price"><?php echo esc_html($currency . $price); ?></span>
                            </div>
                            <a href="#" class="pcpx-cta-button" onclick="return false;">
                                <span class="pcpx-cta-main-text"><?php echo esc_html($ctaLabel); ?></span>
                            </a>
                        </div>
                        <div class="pcpx-content-column">
                            <div class="pcpx-rating-row">
                                <div class="pcpx-rating-stars">
                                    <span class="pcpx-stars">★★★★★</span> <?php echo esc_html($rating); ?>/5
                                </div>
                                <?php if ($readReviewsUrl): ?>
                                    <span class="pcpx-read-reviews">Read reviews</span>
                                <?php endif; ?>
                            </div>

                            <?php if ($userExperience): ?>
                                <div class="pcpx-section">
                                    <div class="pcpx-section-label" style="color: <?php echo esc_attr($headerColor); ?>;">USER EXPERIENCE</div>
                                    <p class="pcpx-section-text"><?php echo $ux_display; ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if ($expertReview1 || $expertReview2 || $expertReview3): ?>
                                <div class="pcpx-section">
                                    <div class="pcpx-section-label" style="color: <?php echo esc_attr($headerColor); ?>;">EXPERT REVIEWS</div>
                                    <ul class="pcpx-expert-reviews-list">
                                        <?php if ($expertReview1): ?><li><?php echo $er1_display; ?></li><?php endif; ?>
                                        <?php if ($expertReview2): ?><li><?php echo $er2_display; ?></li><?php endif; ?>
                                        <?php if ($expertReview3): ?><li><?php echo $er3_display; ?></li><?php endif; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <?php if ($verdict): ?>
                                <div class="pcpx-verdict-box">
                                    <span class="pcpx-verdict-icon">✓</span>
                                    <div><strong>Final Verdict:</strong> <?php echo $verdict_display; ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    private function highlight_n($text) {
        if (empty($text)) return '';
        $escaped = esc_html($text);
        // Highlight .n, !n, ?n, nn, nnn
        $escaped = preg_replace('/([\.\!\?]\s*n+)/i', '<mark class="pcpc-n-highlight">$1</mark>', $escaped);
        $escaped = preg_replace('/(?<!<mark class="pcpc-n-highlight">)(n{2,})(?=[\s"\'\`\.\,\?\!]|$)/i', '<mark class="pcpc-n-highlight">$1</mark>', $escaped);
        return $escaped;
    }

    public function filter_frontend_render_block($block_content, $block) {
        if (isset($block['blockName']) && strpos($block['blockName'], 'product-card') !== false) {
            $block_content = $this->clean_n_text($block_content);
        }
        return $block_content;
    }

    public function render_admin_page() {
        ?>
        <div class="wrap pcpc-admin-wrap">
            <div class="pcpc-header-panel">
                <div class="pcpc-header-title">
                    <span class="dashicons dashicons-clean pcpc-header-icon"></span>
                    <div>
                        <h1>Product Card 'n' Cleaner v1.2</h1>
                        <p class="pcpc-subtitle">Scan, inspect, preview, and replace <code>nn</code>/<code>nnn</code> with <code>.</code> and clean <code>.n</code>/<code>!n</code>/<code>?n</code> across all posts.</p>
                    </div>
                </div>
                <div class="pcpc-header-actions">
                    <button type="button" id="pcpc-btn-scan" class="button button-primary button-hero">
                        <span class="dashicons dashicons-search" style="margin-top: 4px;"></span> Scan Site Now
                    </button>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="pcpc-stats-grid" id="pcpc-stats-grid" style="display: none;">
                <div class="pcpc-stat-card">
                    <div class="pcpc-stat-number" id="stat-posts-scanned">0</div>
                    <div class="pcpc-stat-label">Posts Scanned</div>
                </div>
                <div class="pcpc-stat-card pcpc-stat-warning">
                    <div class="pcpc-stat-number" id="stat-flagged-posts">0</div>
                    <div class="pcpc-stat-label">Posts with 'n' Issue</div>
                </div>
                <div class="pcpc-stat-card">
                    <div class="pcpc-stat-number" id="stat-total-cards">0</div>
                    <div class="pcpc-stat-label">Total Product Cards</div>
                </div>
                <div class="pcpc-stat-card pcpc-stat-danger">
                    <div class="pcpc-stat-number" id="stat-flagged-cards">0</div>
                    <div class="pcpc-stat-label">Flagged Product Cards</div>
                </div>
            </div>

            <!-- Global Action Bar -->
            <div class="pcpc-bulk-panel" id="pcpc-bulk-panel" style="display: none;">
                <div class="pcpc-bulk-left">
                    <label>
                        <input type="checkbox" id="pcpc-select-all-checkbox" checked /> Select All Flagged Posts
                    </label>
                    <span class="pcpc-selected-count-badge" id="pcpc-selected-count">0 selected</span>
                </div>
                <div class="pcpc-bulk-right">
                    <button type="button" id="pcpc-btn-fix-selected" class="button button-primary button-large">
                        <span class="dashicons dashicons-yes-alt"></span> Fix All Selected Posts
                    </button>
                </div>
            </div>

            <!-- Loader & Progress -->
            <div id="pcpc-loader" class="pcpc-loader-container" style="display: none;">
                <div class="pcpc-spinner"></div>
                <p id="pcpc-loader-text">Scanning site for Product Cards...</p>
                <div class="pcpc-progress-bar-wrap">
                    <div class="pcpc-progress-bar" id="pcpc-progress-bar" style="width: 0%;"></div>
                </div>
            </div>

            <!-- Results Table Container -->
            <div class="pcpc-card-panel" id="pcpc-results-panel" style="display: none;">
                <div class="pcpc-panel-header">
                    <h2>Flagged Product Cards (.n, !n, ?n, nn, nnn)</h2>
                    <input type="text" id="pcpc-search-input" placeholder="Search post title..." class="regular-text" />
                </div>

                <div class="pcpc-table-responsive">
                    <table class="wp-list-table widefat fixed striped pcpc-table">
                        <thead>
                            <tr>
                                <th class="check-column"><input type="checkbox" id="pcpc-select-all-header" checked /></th>
                                <th style="width: 250px;">Post Title</th>
                                <th style="width: 100px;">Post Type</th>
                                <th>Affected Fields &amp; Content Difference</th>
                                <th style="width: 220px; text-align: right;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="pcpc-table-body">
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Empty State -->
            <div id="pcpc-empty-state" class="pcpc-card-panel pcpc-empty-box" style="display: none;">
                <span class="dashicons dashicons-yes-alt pcpc-empty-icon"></span>
                <h3>No Product Cards with 'n' artifacts found!</h3>
                <p>All scanned product cards have clean content without trailing '.n', '!n', 'nn', or 'nnn'.</p>
            </div>

            <!-- Card Preview Modal -->
            <div id="pcpc-modal-preview" class="pcpc-modal" style="display: none;">
                <div class="pcpc-modal-overlay"></div>
                <div class="pcpc-modal-content">
                    <div class="pcpc-modal-header">
                        <h3>Product Card Live Preview Comparison</h3>
                        <button type="button" class="pcpc-modal-close">&times;</button>
                    </div>
                    <div class="pcpc-modal-body">
                        <div class="pcpc-preview-title-bar">
                            <span id="modal-post-title" class="pcpc-modal-post-name"></span>
                        </div>
                        <div class="pcpc-preview-split">
                            <div class="pcpc-preview-col">
                                <div class="pcpc-preview-badge pcpc-badge-before">Current (With Artifacts)</div>
                                <div id="modal-preview-before"></div>
                            </div>
                            <div class="pcpc-preview-col">
                                <div class="pcpc-preview-badge pcpc-badge-after">Fixed Preview (Cleaned)</div>
                                <div id="modal-preview-after"></div>
                            </div>
                        </div>
                    </div>
                    <div class="pcpc-modal-footer">
                        <button type="button" class="button pcpc-modal-close">Close</button>
                        <button type="button" id="modal-btn-fix-this" class="button button-primary button-large">
                            <span class="dashicons dashicons-yes"></span> Apply Fix to This Post
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <?php
    }
}

ProductCardNCleaner::get_instance();
