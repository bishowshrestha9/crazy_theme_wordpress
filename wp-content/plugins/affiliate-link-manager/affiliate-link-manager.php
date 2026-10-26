<?php
/**
 * Plugin Name: Affiliate Link Manager
 * Description: Create pretty links and track their origins if they are created from other pretty links.
 * Version: 1.0.0
 * Author: Bishow Shrestha 
 */

if (!defined('ABSPATH'))
    exit;

class AffiliateLinkManager
{
    private $table_name;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'alm_links';

        register_activation_hook(__FILE__, [$this, 'activate']);
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('init', [$this, 'handle_redirection']);

        // AJAX handlers
        add_action('wp_ajax_alm_save_link', [$this, 'ajax_save_link']);
        add_action('wp_ajax_alm_delete_link', [$this, 'ajax_delete_link']);
        add_action('wp_ajax_alm_check_url', [$this, 'ajax_check_url']);
        add_action('wp_ajax_alm_import_csv', [$this, 'ajax_import_csv']);
        add_action('admin_post_alm_export_links', [$this, 'handle_export_links']);
    }

    public function activate()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $this->table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            slug varchar(255) NOT NULL,
            target_url text NOT NULL,
            old_pretty_link text DEFAULT NULL,
            parent_id mediumint(9) DEFAULT NULL,
            parent_type varchar(50) DEFAULT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id),
            UNIQUE KEY slug (slug)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    public function add_menu()
    {
        add_menu_page(
            'Affiliate Links',
            'Affiliate Links',
            'manage_options',
            'affiliate-link-manager',
            [$this, 'render_admin_page'],
            'dashicons-admin-links',
            30
        );
    }

    public function enqueue_assets($hook)
    {
        if ('toplevel_page_affiliate-link-manager' !== $hook)
            return;

        wp_enqueue_style('alm-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin.css');
        wp_enqueue_script('alm-admin-js', plugin_dir_url(__FILE__) . 'assets/js/admin.js', ['jquery'], '1.0.0', true);

        wp_localize_script('alm-admin-js', 'almData', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('alm_nonce')
        ]);
    }

    public function render_admin_page()
    {
        ?>
        <div class="wrap alm-wrap">
            <div class="alm-header">
                <h1>Affiliate Link Manager</h1>
                <div class="alm-header-actions">
                    <a href="<?php echo wp_nonce_url(admin_url('admin-post.php?action=alm_export_links'), 'alm_export_links'); ?>"
                        class="button button-secondary">Export Mapping CSV</a>
                    <button id="alm-import-btn" class="button button-secondary">Import Scrapper CSV</button>
                    <button id="alm-add-new-btn" class="button button-primary">Add New Link</button>
                </div>
            </div>

            <div id="alm-links-list" class="alm-card">
                <h2>All Links</h2>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Pretty Link (Slug)</th>
                            <th>Target URL</th>
                            <th>Origin/Parent</th>
                            <th>Old Pretty Link (Scraped)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="alm-links-tbody">
                        <?php $this->render_links_rows(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal for Adding/Editing -->
            <div id="alm-modal" class="alm-modal" style="display:none;">
                <div class="alm-modal-content">
                    <span class="alm-close">&times;</span>
                    <h2 id="alm-modal-title">Create New Pretty Link</h2>
                    <form id="alm-link-form">
                        <div class="alm-form-group">
                            <label for="alm-name">Link Name</label>
                            <input type="text" id="alm-name" name="name" required placeholder="e.g. My Amazon Product">
                        </div>
                        <div class="alm-form-group">
                            <label for="alm-slug">Pretty Slug</label>
                            <div class="alm-slug-input">
                                <span>
                                    <?php echo home_url('/go/'); ?>
                                </span>
                                <input type="text" id="alm-slug" name="slug" required placeholder="my-slug">
                            </div>
                        </div>
                        <div class="alm-form-group">
                            <label for="alm-target">Target URL</label>
                            <input type="url" id="alm-target" name="target_url" required
                                placeholder="https://example.com/long-url">
                            <div id="alm-url-check-status" class="alm-status-msg"></div>
                        </div>

                        <div id="alm-parent-info" class="alm-parent-box" style="display:none;">
                            <strong>Origin Detected:</strong>
                            <span id="alm-parent-label"></span>
                            <input type="hidden" id="alm-parent-id" name="parent_id">
                            <input type="hidden" id="alm-parent-type" name="parent_type">
                        </div>

                        <div class="alm-form-actions">
                            <button type="submit" class="button button-primary">Save link</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal for CSV Import -->
            <div id="alm-import-modal" class="alm-modal" style="display:none;">
                <div class="alm-modal-content">
                    <span class="alm-close">&times;</span>
                    <h2>Import Links from Scraper</h2>
                    <p class="description">Upload the CSV exported from the Pretty Link Scraper plugin.</p>

                    <form id="alm-import-form" enctype="multipart/form-data">
                        <div class="alm-form-group">
                            <label for="alm-csv-file">Select CSV File</label>
                            <input type="file" id="alm-csv-file" name="csv_file" accept=".csv" required>
                        </div>
                        <div class="alm-form-group">
                            <label>
                                <input type="checkbox" name="update_existing" value="1">
                                Update existing links if Source/Old Link matches
                            </label>
                        </div>
                        <div id="alm-import-status" style="margin-top:10px;"></div>
                        <div class="alm-form-actions">
                            <button type="submit" class="button button-primary">Start Import</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    private function render_links_rows()
    {
        global $wpdb;
        $links = $wpdb->get_results("SELECT * FROM $this->table_name ORDER BY created_at DESC");

        if (empty($links)) {
            echo '<tr><td colspan="6">No links found.</td></tr>';
            return;
        }

        foreach ($links as $link) {
            $parent_info = 'None';
            $badge_class = '';
            if ($link->parent_id) {
                $badge_class = 'alm-origin-' . strtolower(str_replace(' ', '-', $link->parent_type));
                $parent_info = $link->parent_type;
                // Try to get more info if it's our own
                if ($link->parent_type === 'ALM') {
                    $parent = $wpdb->get_row($wpdb->prepare("SELECT name FROM $this->table_name WHERE id = %d", $link->parent_id));
                    if ($parent)
                        $parent_info = 'ALM: ' . $parent->name;
                }
            }
            ?>
            <tr data-id="<?php echo $link->id; ?>">
                <td><strong>
                        <?php echo esc_html($link->name); ?>
                    </strong></td>
                <td><code>/go/<?php echo esc_html($link->slug); ?></code></td>
                <td class="alm-url-cell"><?php echo esc_html($link->target_url); ?></td>
                <td><span class="alm-badge <?php echo $badge_class; ?>"><?php echo esc_html($parent_info); ?></span></td>
                <td class="alm-url-cell">
                    <?php echo $link->old_pretty_link ? '<code>' . esc_html($link->old_pretty_link) . '</code>' : '<em>None</em>'; ?>
                </td>
                <td>
                    <button class="button button-small alm-delete" data-id="<?php echo $link->id; ?>">Delete</button>
                </td>
            </tr>
            <?php
        }
    }

    public function ajax_check_url()
    {
        check_ajax_referer('alm_nonce', 'security');
        $url = esc_url_raw($_POST['url']);

        $origin = $this->detect_url_origin($url);

        if ($origin) {
            wp_send_json_success($origin);
        } else {
            wp_send_json_error();
        }
    }

    private function detect_url_origin($url)
    {
        global $wpdb;

        // 1. Check ALM Table
        $slug = $this->extract_slug($url);
        if ($slug) {
            $link = $wpdb->get_row($wpdb->prepare("SELECT id, name FROM $this->table_name WHERE slug = %s", $slug));
            if ($link)
                return ['id' => $link->id, 'type' => 'ALM', 'label' => $link->name];
        }

        // 2. Check Pretty Links (prli_links)
        $prli_table = $wpdb->prefix . 'prli_links';
        if ($wpdb->get_var("SHOW TABLES LIKE '$prli_table'") === $prli_table) {
            $link = $wpdb->get_row($wpdb->prepare("SELECT id, name FROM $prli_table WHERE slug = %s", $slug));
            if ($link)
                return ['id' => $link->id, 'type' => 'Pretty Links', 'label' => $link->name];
        }

        // 3. Check ThirstyAffiliates
        if (post_type_exists('thirstylink')) {
            // This is harder because TA has its own redirect logic, but we can check if URL matches any TA post slug
            // For now, let's keep it simple.
        }

        return null;
    }

    private function extract_slug($url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (!$path)
            return null;

        // Remove common prefixes
        $prefixes = ['/go/', '/recommends/', '/out/', '/link/'];
        foreach ($prefixes as $p) {
            if (strpos($path, $p) === 0) {
                return trim(substr($path, strlen($p)), '/');
            }
        }

        return trim($path, '/');
    }

    public function ajax_save_link()
    {
        check_ajax_referer('alm_nonce', 'security');
        global $wpdb;

        $name = sanitize_text_field($_POST['name']);
        $slug = sanitize_title($_POST['slug']);
        $target_url = esc_url_raw($_POST['target_url']);
        $parent_id = !empty($_POST['parent_id']) ? intval($_POST['parent_id']) : null;
        $parent_type = !empty($_POST['parent_type']) ? sanitize_text_field($_POST['parent_type']) : null;

        $result = $wpdb->insert($this->table_name, [
            'name' => $name,
            'slug' => $slug,
            'target_url' => $target_url,
            'parent_id' => $parent_id,
            'parent_type' => $parent_type
        ]);

        if ($result) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Failed to save link. Slug might already exist.');
        }
    }

    public function ajax_delete_link()
    {
        check_ajax_referer('alm_nonce', 'security');
        global $wpdb;
        $id = intval($_POST['id']);
        $wpdb->delete($this->table_name, ['id' => $id]);
        wp_send_json_success();
    }

    public function ajax_import_csv()
    {
        check_ajax_referer('alm_nonce', 'security');

        if (!current_user_can('manage_options'))
            wp_send_json_error('Unauthorized');
        if (empty($_FILES['csv_file']))
            wp_send_json_error('No file uploaded');

        $file = $_FILES['csv_file']['tmp_name'];
        if (!is_file($file))
            wp_send_json_error('File processing error');

        $handle = fopen($file, 'r');
        if (!$handle)
            wp_send_json_error('Cannot open file');

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            wp_send_json_error('Empty CSV file');
        }

        $update_existing = isset($_POST['update_existing']) && $_POST['update_existing'] == '1';

        global $wpdb;
        $imported = 0;
        $skipped = 0;

        $col_name = -1;
        $col_target = -1;
        $col_old = -1;
        $col_affiliate = -1;

        $header_str = implode(',', $header);

        // Robust column detection
        foreach ($header as $i => $val) {
            $val = strtolower(trim($val));
            if (strpos($val, 'title') !== false || strpos($val, 'name') !== false)
                $col_name = $i;
            if (strpos($val, 'source pretty') !== false || strpos($val, 'pretty url') !== false || (strpos($val, 'source') !== false && $col_old === -1))
                $col_old = $i;
            if (strpos($val, 'new pretty') !== false || strpos($val, 'target url') !== false || (strpos($val, 'target') !== false && $col_target === -1))
                $col_target = $i;
            if (strpos($val, 'affiliate') !== false)
                $col_affiliate = $i;
        }

        if ($col_target === -1) {
            fclose($handle);
            wp_send_json_error('Target URL column not found. Headers: ' . $header_str);
        }

        $prli_table = "{$wpdb->prefix}prli_links";
        $prli_exists = ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $prli_table)) === $prli_table);

        // Ensure CPT is registered for this session
        if (!post_type_exists('pretty-link')) {
            register_post_type('pretty-link', [
                'public' => true,
                'show_ui' => true,
                'capability_type' => 'post',
                'hierarchical' => false,
                'rewrite' => false,
                'query_var' => false,
                'supports' => ['title']
            ]);
        }

        while (($row = fgetcsv($handle)) !== false) {
            $target_url = isset($row[$col_target]) ? esc_url_raw($row[$col_target]) : '';
            if (!$target_url) {
                $skipped++;
                continue;
            }

            $old_link = ($col_old !== -1 && isset($row[$col_old])) ? esc_url_raw($row[$col_old]) : '';
            $name = ($col_name !== -1 && !empty($row[$col_name])) ? sanitize_text_field($row[$col_name]) : 'Imported Link';
            $target_affiliate = ($col_affiliate !== -1 && isset($row[$col_affiliate])) ? esc_url_raw($row[$col_affiliate]) : (isset($row[3]) ? esc_url_raw($row[3]) : '');

            // Aggressive slug extraction
            $slug = '';
            if ($old_link) {
                $slug = $this->extract_slug($old_link);
            }
            if (!$slug && $target_url) {
                // Try extracting slug from target if old_link didn't work (for Site B logic)
                $slug = $this->extract_slug($target_url);
            }
            if (!$slug) {
                $slug = sanitize_title($name);
            }
            if (!$slug) {
                $slug = wp_generate_password(8, false);
            }

            if ($prli_exists) {
                $prli_id = null;
                if ($update_existing) {
                    if (!empty($target_affiliate)) {
                        $prli_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $prli_table WHERE url = %s", $target_affiliate));
                    }
                    if (!$prli_id) {
                        $prli_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM $prli_table WHERE slug = %s", $slug));
                    }
                }

                if ($prli_id) {
                    // Update existing Pretty Link
                    $wpdb->update($prli_table, [
                        'url' => $target_url,
                        'name' => $name,
                        'redirection_type' => '301'
                    ], ['id' => $prli_id]);
                    $cpt_id = $wpdb->get_var($wpdb->prepare("SELECT link_cpt_id FROM $prli_table WHERE id = %d", $prli_id));
                    if ($cpt_id) {
                        wp_update_post(['ID' => $cpt_id, 'post_title' => $name, 'post_status' => 'publish']);
                    }
                } else {
                    // Create New Pretty Link
                    // 1. Create the CPT Post
                    $new_post_id = wp_insert_post([
                        'post_title' => $name,
                        'post_name' => $slug,
                        'post_status' => 'publish',
                        'post_type' => 'pretty-link',
                        'post_author' => get_current_user_id() ? get_current_user_id() : 1
                    ]);

                    if ($new_post_id && !is_wp_error($new_post_id)) {
                        // 2. Insert into prli_links table
                        $wpdb->insert($prli_table, [
                            'name' => $name,
                            'slug' => $slug,
                            'url' => $target_url,
                            'link_cpt_id' => $new_post_id,
                            'created_at' => current_time('mysql'),
                            'redirection_type' => '301',
                            'track_me' => 1,
                            'nofollow' => 0,
                            'group_id' => 0,
                            'param_forwarding' => 'off',
                            'link_key' => wp_generate_password(10, false)
                        ]);
                    }
                }
            }

            // Sync with ALM local table
            $wpdb->replace($this->table_name, [
                'name' => $name,
                'slug' => $slug,
                'target_url' => $target_url,
                'old_pretty_link' => $old_link,
                'parent_id' => null,
                'parent_type' => null
            ]);
            $imported++;
        }

        fclose($handle);
        wp_send_json_success("Import successful! $imported links processed. They should now appear in both Affiliate Links and Pretty Links tabs.");
    }


    public function handle_redirection()
    {
        if (is_admin())
            return;

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (strpos($path, '/go/') !== 0)
            return;

        $slug = trim(substr($path, 4), '/');
        if (!$slug)
            return;

        global $wpdb;

        // Try ALM first
        $link = $wpdb->get_row($wpdb->prepare("SELECT target_url FROM $this->table_name WHERE slug = %s", $slug));

        // If not in ALM, try Pretty Links plugin
        if (!$link) {
            $prli_table = $wpdb->prefix . 'prli_links';
            if ($wpdb->get_var("SHOW TABLES LIKE '$prli_table'") === $prli_table) {
                $prli_link = $wpdb->get_row($wpdb->prepare("SELECT url FROM $prli_table WHERE slug = %s", $slug));
                if ($prli_link) {
                    $link = (object) ['target_url' => $prli_link->url];
                }
            }
        }

        if ($link && !empty($link->target_url)) {
            // Check if the Meta Redirect plugin is active
            $active_plugins = (array) get_option('active_plugins', array());
            $meta_redirect_active = false;
            foreach ($active_plugins as $plugin) {
                if (strpos($plugin, 'meta-redirecct-plugin/meta-redirect.php') !== false) {
                    $meta_redirect_active = true;
                    break;
                }
            }

            if ($meta_redirect_active) {
                // Redirect to the intermediate countdown page
                $redirect_url = add_query_arg(
                    array(
                        'amr_redirect' => '1',
                        'target' => urlencode($link->target_url)
                    ),
                    home_url('/')
                );
                wp_redirect($redirect_url);
            } else {
                // Fallback to direct redirect if plugin is inactive
                wp_redirect($link->target_url, 301);
            }
            exit;
        }
    }

    public function handle_export_links()
    {
        check_admin_referer('alm_export_links');
        if (!current_user_can('manage_options'))
            wp_die('Access denied');

        global $wpdb;
        $links = $wpdb->get_results("SELECT * FROM $this->table_name ORDER BY created_at DESC");

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=alm-links-mapping-' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        // We use headers that match what our importer expects
        // "Old Pretty Link" will be the "Source" for the next site's import
        fputcsv($output, ['Title', 'Source Pretty Link', 'New Pretty Link', 'Target Affiliate URL']);

        foreach ($links as $link) {
            fputcsv($output, [
                $link->name,
                $link->old_pretty_link, // Site A's link (if this is Site B)
                home_url('/go/' . $link->slug), // Site B's link
                $link->target_url // The actual affiliate link
            ]);
        }
        fclose($output);
        exit;
    }
}

new AffiliateLinkManager();
