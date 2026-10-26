<?php
/**
 * Plugin Name: Pretty Link Domain Replacer
 * Description: Scans all Pretty Links target URLs for mwebtrackerhq.com and provides individual and bulk options to replace target domain to mwebpathiq.com.
 * Version: 1.0.0
 * Author: Bishow Shrestha
 */

if (!defined('ABSPATH')) {
    exit;
}

class Pretty_Link_Domain_Replacer
{
    private $page_slug = 'pretty-link-domain-replacer';
    private $default_search_domain = 'mwebtrackerhq.com';
    private $default_replace_domain = 'mwebpathiq.com';

    public function __construct()
    {
        add_action('admin_menu', array($this, 'register_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));

        // AJAX handlers
        add_action('wp_ajax_pldr_replace_single', array($this, 'ajax_replace_single'));
        add_action('wp_ajax_pldr_replace_bulk', array($this, 'ajax_replace_bulk'));
    }

    public function register_menu()
    {
        // Add under Tools
        add_submenu_page(
            'tools.php',
            'Pretty Link Replacer',
            'Pretty Link Replacer',
            'manage_options',
            $this->page_slug,
            array($this, 'render_admin_page')
        );

        // Also add under Pretty Links menu if available
        if (menu_page_url('pretty-link', false)) {
            add_submenu_page(
                'pretty-link',
                'Pretty Link Domain Replacer',
                'Domain Replacer',
                'manage_options',
                $this->page_slug . '-pl',
                array($this, 'render_admin_page')
            );
        }
    }

    public function enqueue_admin_assets($hook)
    {
        if (strpos($hook, $this->page_slug) === false) {
            return;
        }

        wp_enqueue_style(
            'pldr-admin-css',
            plugin_dir_url(__FILE__) . 'assets/css/admin.css',
            array(),
            '1.0.0'
        );

        wp_enqueue_script(
            'pldr-admin-js',
            plugin_dir_url(__FILE__) . 'assets/js/admin.js',
            array('jquery'),
            '1.0.0',
            true
        );

        wp_localize_script('pldr-admin-js', 'pldrData', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('pldr_ajax_nonce')
        ));
    }

    public function render_admin_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Access denied.');
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'prli_links';
        $table_exists = ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table_name)) === $table_name);

        $search_domain = isset($_POST['pldr_search_domain']) ? sanitize_text_field(wp_unslash($_POST['pldr_search_domain'])) : $this->default_search_domain;
        $replace_domain = isset($_POST['pldr_replace_domain']) ? sanitize_text_field(wp_unslash($_POST['pldr_replace_domain'])) : $this->default_replace_domain;

        $search_domain_clean = $this->normalize_domain($search_domain);
        $replace_domain_clean = $this->normalize_domain($replace_domain);

        $message = '';
        $message_type = 'updated';

        // Handle POST fallback for Replace All
        if ($table_exists && isset($_POST['pldr_action_replace_all']) && check_admin_referer('pldr_scan_action', 'pldr_nonce')) {
            $updated_count = $this->replace_all_domain_links($table_name, $search_domain_clean, $replace_domain_clean);
            $message = sprintf('Successfully updated %d Pretty Links target URL(s) from "%s" to "%s".', $updated_count, esc_html($search_domain_clean), esc_html($replace_domain_clean));
        }

        $scan_results = array();
        $total_links_count = 0;

        if ($table_exists) {
            $total_links_count = (int) $wpdb->get_var("SELECT COUNT(*) FROM {$table_name}");
            if (!empty($search_domain_clean)) {
                $scan_results = $this->scan_links($table_name, $search_domain_clean, $replace_domain_clean);
            }
        }

        ?>
        <div class="wrap pldr-wrap">
            <div class="pldr-header">
                <div>
                    <h1><span class="dashicons dashicons-admin-links"></span> Pretty Link Domain Replacer</h1>
                    <p>Scan Pretty Links target URLs for target domains and quickly replace them individually or in bulk.</p>
                </div>
                <div class="pldr-header-badge">v1.0.0</div>
            </div>

            <?php if (!$table_exists): ?>
                <div class="notice notice-error"><p>Pretty Links database table (<code><?php echo esc_html($table_name); ?></code>) was not found. Please verify Pretty Links plugin is installed.</p></div>
            <?php endif; ?>

            <?php if (!empty($message)): ?>
                <div class="notice notice-success is-dismissible"><p><?php echo esc_html($message); ?></p></div>
            <?php endif; ?>

            <div class="pldr-card">
                <div class="pldr-card-title"><span class="dashicons dashicons-search"></span> Scan & Replacement Settings</div>
                <form method="post" action="">
                    <?php wp_nonce_field('pldr_scan_action', 'pldr_nonce'); ?>
                    <div class="pldr-form-grid">
                        <div class="pldr-field-group">
                            <label for="pldr_search_domain">Search Target Domain:</label>
                            <input type="text" id="pldr_search_domain" name="pldr_search_domain" value="<?php echo esc_attr($search_domain); ?>" placeholder="e.g. mwebtrackerhq.com" required />
                        </div>
                        <div class="pldr-field-group">
                            <label for="pldr_replace_domain">Replace Target Domain With:</label>
                            <input type="text" id="pldr_replace_domain" name="pldr_replace_domain" value="<?php echo esc_attr($replace_domain); ?>" placeholder="e.g. mwebpathiq.com" required />
                        </div>
                        <div>
                            <button type="submit" name="pldr_action_scan" class="button button-primary button-large" style="height: 38px; display: inline-flex; align-items: center; gap: 6px;">
                                <span class="dashicons dashicons-search" style="font-size:16px;"></span> Scan Pretty Links
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <?php if ($table_exists && !empty($search_domain_clean)): ?>
                <div class="pldr-stats-grid">
                    <div class="pldr-stat-card">
                        <div class="pldr-stat-number"><?php echo number_format_i18n($total_links_count); ?></div>
                        <div class="pldr-stat-label">Total Pretty Links</div>
                    </div>
                    <div class="pldr-stat-card stat-warning">
                        <div class="pldr-stat-number" id="pldr-stat-matches"><?php echo number_format_i18n(count($scan_results)); ?></div>
                        <div class="pldr-stat-label">Matched Domain Links</div>
                    </div>
                    <div class="pldr-stat-card stat-success">
                        <div class="pldr-stat-number" id="pldr-stat-replaced">0</div>
                        <div class="pldr-stat-label">Replaced in Session</div>
                    </div>
                </div>

                <div class="pldr-card" style="padding: 16px 20px;">
                    <div class="pldr-toolbar">
                        <div style="font-weight: 600; color: #334155; display: flex; align-items: center; gap: 8px;">
                            <span class="dashicons dashicons-list-view"></span>
                            Found <?php echo count($scan_results); ?> link(s) matching <code><?php echo esc_html($search_domain_clean); ?></code>
                        </div>

                        <div class="pldr-toolbar-actions">
                            <button type="button" id="pldr-btn-replace-selected" class="button button-secondary" disabled>
                                <span class="dashicons dashicons-update" style="font-size:16px; vertical-align:middle;"></span>
                                Replace Selected (<span id="pldr-selected-count">0</span>)
                            </button>

                            <button type="button" id="pldr-btn-replace-all" class="button button-primary">
                                <span class="dashicons dashicons-redo" style="font-size:16px; vertical-align:middle;"></span>
                                Replace All Matched
                            </button>
                        </div>
                    </div>

                    <?php if (!empty($scan_results)): ?>
                        <div class="pldr-table-container">
                            <table class="pldr-table" id="pldr-results-table">
                                <thead>
                                    <tr>
                                        <th style="width: 40px; text-align: center;">
                                            <input type="checkbox" id="pldr-select-all" title="Select All" />
                                        </th>
                                        <th style="width: 60px;">ID</th>
                                        <th style="width: 180px;">Name</th>
                                        <th style="width: 140px;">Slug</th>
                                        <th>Current Target URL</th>
                                        <th>Proposed New Target URL</th>
                                        <th style="width: 100px; text-align: center;">Status</th>
                                        <th style="width: 110px; text-align: center;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($scan_results as $row): ?>
                                        <tr id="pldr-row-<?php echo (int) $row['id']; ?>">
                                            <td style="text-align: center;">
                                                <input type="checkbox" class="pldr-row-checkbox" value="<?php echo (int) $row['id']; ?>" />
                                            </td>
                                            <td><strong>#<?php echo (int) $row['id']; ?></strong></td>
                                            <td><?php echo esc_html($row['name']); ?></td>
                                            <td>
                                                <a href="<?php echo esc_url(site_url('/' . $row['slug'])); ?>" target="_blank" rel="noopener">
                                                    <code>/<?php echo esc_html($row['slug']); ?></code>
                                                </a>
                                            </td>
                                            <td class="pldr-target-cell pldr-url-code">
                                                <?php echo $this->highlight_domain($row['url'], $search_domain_clean, 'pldr-url-highlight-old'); ?>
                                            </td>
                                            <td class="pldr-new-url-cell pldr-url-code">
                                                <?php echo $this->highlight_domain($row['proposed_url'], $replace_domain_clean, 'pldr-url-highlight-new'); ?>
                                            </td>
                                            <td class="pldr-status-cell" style="text-align: center;">
                                                <span class="pldr-badge pldr-badge-pending">Matched</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <button type="button" class="button button-small pldr-btn-replace-single" data-id="<?php echo (int) $row['id']; ?>">
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
                            <h3 style="margin: 0 0 6px 0; color: #1e293b;">No Matched Target URLs Found</h3>
                            <p style="margin: 0;">No Pretty Links target URLs contain the domain <code><?php echo esc_html($search_domain_clean); ?></code>.</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }

    private function normalize_domain($input)
    {
        $input = trim($input);
        $input = strtolower($input);
        $input = preg_replace('/^https?:\/\//', '', $input);
        $input = preg_replace('/^www\./', '', $input);
        $input = rtrim($input, '/');
        return $input;
    }

    private function scan_links($table_name, $search_domain, $replace_domain)
    {
        global $wpdb;

        $like = '%' . $wpdb->esc_like($search_domain) . '%';
        $sql = $wpdb->prepare(
            "SELECT id, name, slug, url FROM {$table_name} WHERE url LIKE %s ORDER BY id DESC",
            $like
        );

        $rows = $wpdb->get_results($sql, ARRAY_A);
        $matched = array();

        if (empty($rows)) {
            return $matched;
        }

        foreach ($rows as $row) {
            $url = isset($row['url']) ? (string) $row['url'] : '';
            $proposed_url = $this->replace_target_url($url, $search_domain, $replace_domain);

            $matched[] = array(
                'id'           => (int) $row['id'],
                'name'         => !empty($row['name']) ? (string) $row['name'] : '(No Name)',
                'slug'         => (string) $row['slug'],
                'url'          => $url,
                'proposed_url' => $proposed_url,
            );
        }

        return $matched;
    }

    public function replace_target_url($url, $search_domain, $replace_domain)
    {
        if (empty($search_domain) || empty($replace_domain) || empty($url)) {
            return $url;
        }

        // Case-insensitive domain replacement preserving scheme, path, & query params
        return str_ireplace($search_domain, $replace_domain, $url);
    }

    private function highlight_domain($url, $domain, $css_class)
    {
        if (empty($domain)) {
            return esc_html($url);
        }

        $escaped_url = esc_html($url);
        $escaped_domain = esc_html($domain);

        return str_ireplace(
            $escaped_domain,
            '<span class="' . esc_attr($css_class) . '">' . $escaped_domain . '</span>',
            $escaped_url
        );
    }

    private function replace_all_domain_links($table_name, $search_domain, $replace_domain)
    {
        global $wpdb;

        $rows = $this->scan_links($table_name, $search_domain, $replace_domain);
        if (empty($rows)) {
            return 0;
        }

        $updated_count = 0;
        foreach ($rows as $row) {
            $new_url = $row['proposed_url'];
            if ($new_url !== $row['url']) {
                $res = $wpdb->update(
                    $table_name,
                    array('url' => $new_url),
                    array('id' => (int) $row['id']),
                    array('%s'),
                    array('%d')
                );
                if ($res !== false && $res > 0) {
                    $updated_count++;
                }
            }
        }

        return $updated_count;
    }

    public function ajax_replace_single()
    {
        check_ajax_referer('pldr_ajax_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions.');
        }

        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $search_domain = isset($_POST['search_domain']) ? sanitize_text_field(wp_unslash($_POST['search_domain'])) : $this->default_search_domain;
        $replace_domain = isset($_POST['replace_domain']) ? sanitize_text_field(wp_unslash($_POST['replace_domain'])) : $this->default_replace_domain;

        $search_domain_clean = $this->normalize_domain($search_domain);
        $replace_domain_clean = $this->normalize_domain($replace_domain);

        if (!$id || empty($search_domain_clean) || empty($replace_domain_clean)) {
            wp_send_json_error('Invalid parameters provided.');
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'prli_links';
        $row = $wpdb->get_row($wpdb->prepare("SELECT id, url FROM {$table_name} WHERE id = %d", $id), ARRAY_A);

        if (!$row) {
            wp_send_json_error('Pretty Link record not found.');
        }

        $old_url = (string) $row['url'];
        $new_url = $this->replace_target_url($old_url, $search_domain_clean, $replace_domain_clean);

        $result = $wpdb->update(
            $table_name,
            array('url' => $new_url),
            array('id' => $id),
            array('%s'),
            array('%d')
        );

        if ($result !== false) {
            wp_send_json_success(array(
                'id'      => $id,
                'old_url' => $old_url,
                'new_url' => $new_url
            ));
        } else {
            wp_send_json_error('Database update failed.');
        }
    }

    public function ajax_replace_bulk()
    {
        check_ajax_referer('pldr_ajax_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions.');
        }

        $ids = isset($_POST['ids']) && is_array($_POST['ids']) ? array_map('intval', $_POST['ids']) : array();
        $search_domain = isset($_POST['search_domain']) ? sanitize_text_field(wp_unslash($_POST['search_domain'])) : $this->default_search_domain;
        $replace_domain = isset($_POST['replace_domain']) ? sanitize_text_field(wp_unslash($_POST['replace_domain'])) : $this->default_replace_domain;

        $search_domain_clean = $this->normalize_domain($search_domain);
        $replace_domain_clean = $this->normalize_domain($replace_domain);

        if (empty($ids) || empty($search_domain_clean) || empty($replace_domain_clean)) {
            wp_send_json_error('No valid IDs or domain parameters provided.');
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'prli_links';

        $updated_items = array();
        $updated_count = 0;

        foreach ($ids as $id) {
            $row = $wpdb->get_row($wpdb->prepare("SELECT id, url FROM {$table_name} WHERE id = %d", $id), ARRAY_A);
            if ($row) {
                $old_url = (string) $row['url'];
                $new_url = $this->replace_target_url($old_url, $search_domain_clean, $replace_domain_clean);

                $res = $wpdb->update(
                    $table_name,
                    array('url' => $new_url),
                    array('id' => $id),
                    array('%s'),
                    array('%d')
                );

                if ($res !== false) {
                    $updated_count++;
                    $updated_items[] = array(
                        'id'      => $id,
                        'old_url' => $old_url,
                        'new_url' => $new_url
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

new Pretty_Link_Domain_Replacer();
