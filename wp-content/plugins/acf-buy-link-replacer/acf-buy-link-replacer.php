<?php
/**
 * Plugin Name: ACF Buy Link Replacer
 * Description: Scans all posts and pages for http://[acf field='buy_link'] and replaces with acf:buy_link in table links and text Visit Official Website.
 * Version: 1.0.0
 * Author: Bishow Shrestha
 */

if (!defined('ABSPATH')) {
    exit;
}

class ACF_Buy_Link_Replacer
{
    private $page_slug = 'acf-buy-link-replacer';
    private $default_search_pattern = "http://[acf field='buy_link']";
    private $default_replace_pattern = "acf:buy_link";
    private $default_text_target = "Visit Official Website";

    public function __construct()
    {
        add_action('admin_menu', array($this, 'register_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));

        // AJAX actions
        add_action('wp_ajax_ablr_replace_single', array($this, 'ajax_replace_single'));
        add_action('wp_ajax_ablr_replace_bulk', array($this, 'ajax_replace_bulk'));
    }

    public function register_menu()
    {
        add_submenu_page(
            'tools.php',
            'ACF Link Replacer',
            'ACF Link Replacer',
            'manage_options',
            $this->page_slug,
            array($this, 'render_admin_page')
        );
    }

    public function enqueue_admin_assets($hook)
    {
        if (strpos($hook, $this->page_slug) === false) {
            return;
        }

        wp_enqueue_style(
            'ablr-admin-css',
            plugin_dir_url(__FILE__) . 'assets/css/admin.css',
            array(),
            '1.0.0'
        );

        wp_enqueue_script(
            'ablr-admin-js',
            plugin_dir_url(__FILE__) . 'assets/js/admin.js',
            array('jquery'),
            '1.0.0',
            true
        );

        wp_localize_script('ablr-admin-js', 'ablrData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('ablr_ajax_nonce')
        ));
    }

    public function render_admin_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Access denied.');
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'posts';

        $search_pattern  = isset($_POST['ablr_search_pattern']) ? sanitize_text_field(wp_unslash($_POST['ablr_search_pattern'])) : $this->default_search_pattern;
        $replace_pattern = isset($_POST['ablr_replace_pattern']) ? sanitize_text_field(wp_unslash($_POST['ablr_replace_pattern'])) : $this->default_replace_pattern;
        $text_target     = isset($_POST['ablr_text_target']) ? sanitize_text_field(wp_unslash($_POST['ablr_text_target'])) : $this->default_text_target;

        $message = '';

        if (isset($_POST['ablr_action_replace_all']) && check_admin_referer('ablr_scan_action', 'ablr_nonce')) {
            $updated_count = $this->replace_all_posts($search_pattern, $replace_pattern);
            $message = sprintf('Successfully updated %d post(s).', $updated_count);
        }

        $scan_results = $this->scan_posts($search_pattern, $text_target);
        $total_posts_count = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$table_name} WHERE post_status IN ('publish','draft','private') AND post_type NOT IN ('revision','nav_menu_item')");

        ?>
        <div class="wrap ablr-wrap">
            <div class="ablr-header">
                <div>
                    <h1><span class="dashicons dashicons-editor-unlink"></span> ACF Buy Link Replacer</h1>
                    <p>Scan all post content for <code>http://[acf field='buy_link']</code> and replace with <code>acf:buy_link</code> in tables and text links.</p>
                </div>
                <div class="ablr-header-badge">v1.0.0</div>
            </div>

            <?php if (!empty($message)): ?>
                <div class="notice notice-success is-dismissible"><p><?php echo esc_html($message); ?></p></div>
            <?php endif; ?>

            <div class="ablr-card">
                <div class="ablr-card-title"><span class="dashicons dashicons-search"></span> Scan & Replacement Settings</div>
                <form method="post" action="">
                    <?php wp_nonce_field('ablr_scan_action', 'ablr_nonce'); ?>
                    <div class="ablr-form-grid">
                        <div class="ablr-field-group">
                            <label for="ablr_search_pattern">Find Target URL / Shortcode:</label>
                            <input type="text" id="ablr_search_pattern" name="ablr_search_pattern" value="<?php echo esc_attr($search_pattern); ?>" required />
                        </div>
                        <div class="ablr-field-group">
                            <label for="ablr_replace_pattern">Replace With:</label>
                            <input type="text" id="ablr_replace_pattern" name="ablr_replace_pattern" value="<?php echo esc_attr($replace_pattern); ?>" required />
                        </div>
                        <div class="ablr-field-group">
                            <label for="ablr_text_target">Optional Table Link Text Filter:</label>
                            <input type="text" id="ablr_text_target" name="ablr_text_target" value="<?php echo esc_attr($text_target); ?>" placeholder="e.g. Visit Official Website" />
                        </div>
                        <div>
                            <button type="submit" name="ablr_action_scan" class="button button-primary button-large" style="height: 38px; display: inline-flex; align-items: center; gap: 6px;">
                                <span class="dashicons dashicons-search" style="font-size:16px;"></span> Scan Posts
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="ablr-stats-grid">
                <div class="ablr-stat-card">
                    <div class="ablr-stat-number"><?php echo number_format_i18n($total_posts_count); ?></div>
                    <div class="ablr-stat-label">Total Site Posts</div>
                </div>
                <div class="ablr-stat-card stat-warning">
                    <div class="ablr-stat-number" id="ablr-stat-matches"><?php echo number_format_i18n(count($scan_results)); ?></div>
                    <div class="ablr-stat-label">Matched Posts</div>
                </div>
                <div class="ablr-stat-card stat-success">
                    <div class="ablr-stat-number" id="ablr-stat-replaced">0</div>
                    <div class="ablr-stat-label">Replaced in Session</div>
                </div>
            </div>

            <div class="ablr-card" style="padding: 16px 20px;">
                <div class="ablr-toolbar">
                    <div style="font-weight: 600; color: #334155; display: flex; align-items: center; gap: 8px;">
                        <span class="dashicons dashicons-list-view"></span>
                        Found <?php echo count($scan_results); ?> post(s) with matching links/text
                    </div>

                    <div class="ablr-toolbar-actions">
                        <button type="button" id="ablr-btn-replace-selected" class="button button-secondary" disabled>
                            <span class="dashicons dashicons-update" style="font-size:16px; vertical-align:middle;"></span>
                            Replace Selected (<span id="ablr-selected-count">0</span>)
                        </button>

                        <button type="button" id="ablr-btn-replace-all" class="button button-primary">
                            <span class="dashicons dashicons-redo" style="font-size:16px; vertical-align:middle;"></span>
                            Replace All Matched
                        </button>
                    </div>
                </div>

                <?php if (!empty($scan_results)): ?>
                    <div class="ablr-table-container">
                        <table class="ablr-table" id="ablr-results-table">
                            <thead>
                                <tr>
                                    <th style="width: 40px; text-align: center;">
                                        <input type="checkbox" id="ablr-select-all" title="Select All" />
                                    </th>
                                    <th style="width: 60px;">ID</th>
                                    <th style="width: 220px;">Title & Type</th>
                                    <th>Content Snippet / Preview</th>
                                    <th style="width: 100px; text-align: center;">Status</th>
                                    <th style="width: 110px; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($scan_results as $row): ?>
                                    <tr id="ablr-row-<?php echo (int) $row['ID']; ?>">
                                        <td style="text-align: center;">
                                            <input type="checkbox" class="ablr-row-checkbox" value="<?php echo (int) $row['ID']; ?>" />
                                        </td>
                                        <td><strong>#<?php echo (int) $row['ID']; ?></strong></td>
                                        <td>
                                            <a href="<?php echo esc_url(get_edit_post_link($row['ID'])); ?>" target="_blank" rel="noopener">
                                                <strong><?php echo esc_html($row['post_title']); ?></strong>
                                            </a>
                                            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                                Type: <code><?php echo esc_html($row['post_type']); ?></code> | Status: <?php echo esc_html($row['post_status']); ?>
                                            </div>
                                        </td>
                                        <td class="ablr-snippet-cell">
                                            <div class="ablr-snippet-box"><?php echo $row['snippet_html']; ?></div>
                                        </td>
                                        <td class="ablr-status-cell" style="text-align: center;">
                                            <span class="ablr-badge ablr-badge-pending">Matched</span>
                                        </td>
                                        <td style="text-align: center;">
                                            <button type="button" class="button button-small ablr-btn-replace-single" data-id="<?php echo (int) $row['ID']; ?>">
                                                Replace
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div style="padding: 40px; text-align: center; color: #64748b;">
                        <span class="dashicons dashicons-yes-alt" style="font-size: 48px; width:48px; height:48px; color: #16a34a; margin-bottom: 12px;"></span>
                        <h3 style="margin: 0 0 6px 0; color: #1e293b;">No Matching Content Found</h3>
                        <p style="margin: 0;">No posts currently contain <code><?php echo esc_html($search_pattern); ?></code>.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    private function scan_posts($search_pattern, $text_target = '')
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'posts';

        // Search for variations of http(s)://[acf field='buy_link'] and http(s)://[acf field="buy_link"]
        $like1 = '%' . $wpdb->esc_like("http://[acf field='buy_link']") . '%';
        $like2 = '%' . $wpdb->esc_like('http://[acf field="buy_link"]') . '%';
        $like3 = '%' . $wpdb->esc_like("https://[acf field='buy_link']") . '%';
        $like4 = '%' . $wpdb->esc_like('https://[acf field="buy_link"]') . '%';
        $like5 = '%' . $wpdb->esc_like($search_pattern) . '%';

        $sql = $wpdb->prepare(
            "SELECT ID, post_title, post_type, post_status, post_content 
             FROM {$table_name} 
             WHERE post_status IN ('publish','draft','private','pending','future') 
               AND post_type NOT IN ('revision','nav_menu_item','attachment')
               AND (post_content LIKE %s OR post_content LIKE %s OR post_content LIKE %s OR post_content LIKE %s OR post_content LIKE %s)
             ORDER BY ID DESC",
            $like1, $like2, $like3, $like4, $like5
        );

        $rows = $wpdb->get_results($sql, ARRAY_A);
        $results = array();

        if (empty($rows)) {
            return $results;
        }

        foreach ($rows as $row) {
            $content = (string) $row['post_content'];
            $snippet_html = $this->generate_snippet_html($content, $search_pattern);

            $results[] = array(
                'ID'           => (int) $row['ID'],
                'post_title'   => !empty($row['post_title']) ? (string) $row['post_title'] : '(Untitled)',
                'post_type'    => (string) $row['post_type'],
                'post_status'  => (string) $row['post_status'],
                'snippet_html' => $snippet_html,
            );
        }

        return $results;
    }

    public function replace_in_post_content($content, $search_pattern, $replace_pattern)
    {
        if (empty($content)) {
            return $content;
        }

        // Comprehensive pattern replacement array
        $patterns = array(
            '/https?:\/\/\[acf\s+field=[\'"]buy_link[\'"]\]/i',
            '/http:\/\/\[acf field=\'buy_link\'\]/i',
            '/http:\/\/\[acf field="buy_link"\]/i',
            '/https:\/\/\[acf field=\'buy_link\'\]/i',
            '/https:\/\/\[acf field="buy_link"\]/i'
        );

        if (!empty($search_pattern)) {
            $custom = preg_quote($search_pattern, '/');
            if (!in_array('/' . $custom . '/i', $patterns)) {
                $patterns[] = '/' . $custom . '/i';
            }
        }

        return preg_replace($patterns, $replace_pattern, $content);
    }

    private function generate_snippet_html($content, $search_pattern)
    {
        $patterns = array(
            "http://[acf field='buy_link']",
            'http://[acf field="buy_link"]',
            "https://[acf field='buy_link']",
            'https://[acf field="buy_link"]',
            $search_pattern
        );

        $found_pos = false;
        $matched_str = '';

        foreach ($patterns as $pat) {
            if (empty($pat)) continue;
            $pos = stripos($content, $pat);
            if ($pos !== false) {
                $found_pos = $pos;
                $matched_str = substr($content, $pos, strlen($pat));
                break;
            }
        }

        if ($found_pos === false) {
            return esc_html(mb_substr($content, 0, 150)) . '...';
        }

        $start = max(0, $found_pos - 60);
        $length = strlen($matched_str) + 120;
        $snippet_raw = mb_substr($content, $start, $length);

        $escaped_snippet = esc_html($snippet_raw);
        $escaped_match = esc_html($matched_str);

        if (!empty($escaped_match)) {
            $escaped_snippet = str_replace(
                $escaped_match,
                '<span class="ablr-highlight-old">' . $escaped_match . '</span>',
                $escaped_snippet
            );
        }

        return $escaped_snippet;
    }

    private function replace_all_posts($search_pattern, $replace_pattern)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'posts';

        $posts = $this->scan_posts($search_pattern);
        if (empty($posts)) {
            return 0;
        }

        $count = 0;
        foreach ($posts as $item) {
            $id = $item['ID'];
            $raw_content = $wpdb->get_var($wpdb->prepare("SELECT post_content FROM {$table_name} WHERE ID = %d", $id));
            if ($raw_content) {
                $new_content = $this->replace_in_post_content($raw_content, $search_pattern, $replace_pattern);
                if ($new_content !== $raw_content) {
                    $res = $wpdb->update(
                        $table_name,
                        array('post_content' => $new_content),
                        array('ID' => $id),
                        array('%s'),
                        array('%d')
                    );
                    if ($res !== false) {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }

    public function ajax_replace_single()
    {
        check_ajax_referer('ablr_ajax_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions.');
        }

        $post_id = isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0;
        $search_pattern  = isset($_POST['search_pattern']) ? sanitize_text_field(wp_unslash($_POST['search_pattern'])) : $this->default_search_pattern;
        $replace_pattern = isset($_POST['replace_pattern']) ? sanitize_text_field(wp_unslash($_POST['replace_pattern'])) : $this->default_replace_pattern;

        if (!$post_id) {
            wp_send_json_error('Invalid post ID.');
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'posts';

        $content = $wpdb->get_var($wpdb->prepare("SELECT post_content FROM {$table_name} WHERE ID = %d", $post_id));
        if ($content === null) {
            wp_send_json_error('Post content not found.');
        }

        $new_content = $this->replace_in_post_content($content, $search_pattern, $replace_pattern);

        $res = $wpdb->update(
            $table_name,
            array('post_content' => $new_content),
            array('ID' => $post_id),
            array('%s'),
            array('%d')
        );

        if ($res !== false) {
            $new_snippet = $this->generate_snippet_html($new_content, $replace_pattern);
            wp_send_json_success(array(
                'post_id' => $post_id,
                'snippet' => '<div class="ablr-snippet-box">' . $new_snippet . '</div>'
            ));
        } else {
            wp_send_json_error('Database update failed.');
        }
    }

    public function ajax_replace_bulk()
    {
        check_ajax_referer('ablr_ajax_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions.');
        }

        $post_ids = isset($_POST['post_ids']) && is_array($_POST['post_ids']) ? array_map('intval', $_POST['post_ids']) : array();
        $search_pattern  = isset($_POST['search_pattern']) ? sanitize_text_field(wp_unslash($_POST['search_pattern'])) : $this->default_search_pattern;
        $replace_pattern = isset($_POST['replace_pattern']) ? sanitize_text_field(wp_unslash($_POST['replace_pattern'])) : $this->default_replace_pattern;

        if (empty($post_ids)) {
            wp_send_json_error('No valid post IDs provided.');
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'posts';

        $updated_items = array();
        $updated_count = 0;

        foreach ($post_ids as $post_id) {
            $content = $wpdb->get_var($wpdb->prepare("SELECT post_content FROM {$table_name} WHERE ID = %d", $post_id));
            if ($content !== null) {
                $new_content = $this->replace_in_post_content($content, $search_pattern, $replace_pattern);
                $res = $wpdb->update(
                    $table_name,
                    array('post_content' => $new_content),
                    array('ID' => $post_id),
                    array('%s'),
                    array('%d')
                );

                if ($res !== false) {
                    $updated_count++;
                    $new_snippet = $this->generate_snippet_html($new_content, $replace_pattern);
                    $updated_items[] = array(
                        'post_id' => $post_id,
                        'snippet' => '<div class="ablr-snippet-box">' . $new_snippet . '</div>'
                    );
                }
            }
        }

        wp_send_json_success(array(
            'updated_count' => $updated_count,
            'updated_items' => $updated_items
        ));
    }
}

new ACF_Buy_Link_Replacer();
