<?php
/**
 * Plugin Name: Pretty Links /go/ Cleaner
 * Description: Scans Pretty Links records that contain /go/ in target URL and removes them safely from admin.
 * Version: 1.0.0
 * Author: Bishow Shrestha
 */

if (!defined('ABSPATH')) {
    exit;
}

class PrettyLinksGoCleaner
{
    private $page_slug = 'pretty-links-go-cleaner';

    public function __construct()
    {
        add_action('admin_menu', array($this, 'register_menu'));
    }

    public function register_menu()
    {
        add_submenu_page(
            'tools.php',
            'Pretty Links /go/ Cleaner',
            'Pretty Links /go/ Cleaner',
            'manage_options',
            $this->page_slug,
            array($this, 'render_page')
        );
    }

    public function render_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Access denied.');
        }

        global $wpdb;
        $prli_table = $wpdb->prefix . 'prli_links';
        $table_exists = ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $prli_table)) === $prli_table);

        $message = '';
        $message_type = 'updated';

        if ($table_exists && isset($_POST['plgc_delete_all']) && check_admin_referer('plgc_delete_action', 'plgc_nonce')) {
            $deleted_count = $this->delete_go_links($prli_table);
            $message = sprintf('Deleted %d Pretty Links containing /go/.', $deleted_count);
        }

        if ($table_exists && isset($_POST['plgc_strip_go']) && check_admin_referer('plgc_strip_action', 'plgc_strip_nonce')) {
            $updated_count = $this->strip_go_from_urls($prli_table);
            $message = sprintf('Updated %d Pretty Links by stripping /go/ from target URLs.', $updated_count);
        }

        $results = array();
        $preview_rows = array();
        if ($table_exists) {
            // Handle preview export first (downloads CSV and exits)
            if (isset($_POST['plgc_export_preview']) && check_admin_referer('plgc_export_preview_action', 'plgc_export_preview_nonce')) {
                $data = $this->preview_strip_urls($prli_table);
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=pretty-links-strip-preview-' . date('Y-m-d') . '.csv');
                $out = fopen('php://output', 'w');
                fputcsv($out, ['ID', 'Name', 'Slug', 'Old URL', 'New URL']);
                foreach ($data as $r) {
                    fputcsv($out, [(int) $r->id, $r->name, $r->slug, $r->old_url, $r->new_url]);
                }
                fclose($out);
                exit;
            }

            // Handle preview request
            if (isset($_POST['plgc_preview_strip']) && check_admin_referer('plgc_preview_action', 'plgc_preview_nonce')) {
                $preview_rows = $this->preview_strip_urls($prli_table);
            }

            $results = $this->get_go_links($prli_table);
        }

        ?>
        <div class="wrap">
            <h1>Pretty Links /go/ Cleaner</h1>
            <p>Scan Pretty Links records where target URL contains <code>/go/</code>, then remove them.</p>

            <?php if (!$table_exists): ?>
                <div class="notice notice-error"><p>Pretty Links table not found: <code><?php echo esc_html($prli_table); ?></code></p></div>
            <?php endif; ?>

            <?php if (!empty($message)): ?>
                <div class="notice <?php echo esc_attr($message_type); ?> is-dismissible"><p><?php echo esc_html($message); ?></p></div>
            <?php endif; ?>

            <div class="card" style="max-width: 1200px; padding: 16px;">
                <h2>Scan Result</h2>
                <p><strong>Matches found:</strong> <?php echo esc_html(count($results)); ?></p>

                <?php if (!empty($results)): ?>
                    <div style="display:flex; gap:12px; margin-bottom:12px;">
                        <form method="post" onsubmit="return confirm('Delete all matched links containing /go/? This cannot be undone.');">
                            <?php wp_nonce_field('plgc_delete_action', 'plgc_nonce'); ?>
                            <input type="submit" name="plgc_delete_all" class="button button-primary" value="Delete All Matched Links">
                        </form>

                        <form method="post" onsubmit="return confirm('Strip "/go/" from matched target URLs? This will update URL paths in the Pretty Links table.');">
                            <?php wp_nonce_field('plgc_strip_action', 'plgc_strip_nonce'); ?>
                            <input type="submit" name="plgc_strip_go" class="button" value="Strip /go/ from Target URLs">
                        </form>

                        <form method="post" onsubmit="return true;">
                            <?php wp_nonce_field('plgc_preview_action', 'plgc_preview_nonce'); ?>
                            <input type="submit" name="plgc_preview_strip" class="button" value="Preview Strip /go/ Changes">
                        </form>
                    </div>

                    <table class="widefat striped" style="margin-top: 16px;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Slug</th>
                                <th>Target URL</th>
                                <th>CPT Post ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($results as $row): ?>
                                <tr>
                                    <td><?php echo esc_html($row->id); ?></td>
                                    <td><?php echo esc_html($row->name); ?></td>
                                    <td><code><?php echo esc_html($row->slug); ?></code></td>
                                    <td><code><?php echo esc_html($row->url); ?></code></td>
                                    <td><?php echo esc_html($row->link_cpt_id); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No Pretty Links entries found with <code>/go/</code> in target URL.</p>
                <?php endif; ?>

                <?php if (!empty($preview_rows)): ?>
                    <div style="margin-top:20px; padding:12px; border:1px solid #ddd; background:#fff;">
                        <h3>Preview: Proposed /go/ Strip Changes</h3>
                        <p><strong>Rows to change:</strong> <?php echo esc_html(count($preview_rows)); ?></p>

                        <form method="post" style="display:inline-block; margin-right:8px;">
                            <?php wp_nonce_field('plgc_export_preview_action', 'plgc_export_preview_nonce'); ?>
                            <input type="submit" name="plgc_export_preview" class="button" value="Export Preview CSV">
                        </form>

                        <form method="post" onsubmit="return confirm('Apply the previewed URL changes (strip /go/)? This will update the database.');" style="display:inline-block;">
                            <?php wp_nonce_field('plgc_strip_action', 'plgc_strip_nonce'); ?>
                            <input type="submit" name="plgc_strip_go" class="button button-primary" value="Apply Changes (Strip /go/)">
                        </form>

                        <table class="widefat striped" style="margin-top:12px;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Old URL</th>
                                    <th>New URL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($preview_rows as $p): ?>
                                    <tr>
                                        <td><?php echo esc_html($p->id); ?></td>
                                        <td><?php echo esc_html($p->name); ?></td>
                                        <td><code><?php echo esc_html($p->slug); ?></code></td>
                                        <td><code><?php echo esc_html($p->old_url); ?></code></td>
                                        <td><code><?php echo esc_html($p->new_url); ?></code></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    private function get_go_links($prli_table)
    {
        global $wpdb;

        $like = '%' . $wpdb->esc_like('/go/') . '%';
        $sql = $wpdb->prepare(
            "SELECT id, name, slug, url, link_cpt_id FROM {$prli_table} WHERE url LIKE %s ORDER BY id DESC",
            $like
        );

        return $wpdb->get_results($sql);
    }

    private function delete_go_links($prli_table)
    {
        global $wpdb;

        $rows = $this->get_go_links($prli_table);
        if (empty($rows)) {
            return 0;
        }

        $deleted_count = 0;

        foreach ($rows as $row) {
            $deleted = $wpdb->delete($prli_table, array('id' => (int) $row->id), array('%d'));
            if ($deleted) {
                $deleted_count++;
                if (!empty($row->link_cpt_id)) {
                    wp_delete_post((int) $row->link_cpt_id, true);
                }
            }
        }

        return $deleted_count;
    }

    private function strip_go_from_urls($prli_table)
    {
        global $wpdb;

        $rows = $this->get_go_links($prli_table);
        if (empty($rows)) {
            return 0;
        }

        $updated_count = 0;
        $alm_table = $wpdb->prefix . 'alm_links';
        $alm_exists = ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $alm_table)) === $alm_table);

        foreach ($rows as $row) {
            $old_url = $row->url;

            // Replace first occurrence of '/go/' in the path with '/'
            $new_url = preg_replace('%/go/%', '/', $old_url, 1);

            // Normalize double slashes after scheme (e.g., https:////)
            $new_url = preg_replace('#([^:]/)/+#', '$1/', $new_url);

            if ($new_url === $old_url) {
                continue;
            }

            $updated = $wpdb->update($prli_table, ['url' => $new_url], ['id' => (int) $row->id], ['%s'], ['%d']);
            if ($updated !== false) {
                $updated_count++;

                // If ALM table exists, update its target_url entries that match the old URL
                if ($alm_exists) {
                    $wpdb->update($alm_table, ['target_url' => $new_url], ['target_url' => $old_url], ['%s'], ['%s']);
                }
            }
        }

        return $updated_count;
    }

    private function preview_strip_urls($prli_table)
    {
        global $wpdb;

        $rows = $this->get_go_links($prli_table);
        $preview = array();
        if (empty($rows)) {
            return $preview;
        }

        foreach ($rows as $row) {
            $old_url = $row->url;

            $new_url = preg_replace('%/go/%', '/', $old_url, 1);
            $new_url = preg_replace('#([^:]/)/+#', '$1/', $new_url);

            $item = new stdClass();
            $item->id = $row->id;
            $item->name = $row->name;
            $item->slug = $row->slug;
            $item->old_url = $old_url;
            $item->new_url = $new_url;
            $item->link_cpt_id = $row->link_cpt_id;

            $preview[] = $item;
        }

        return $preview;
    }
}

new PrettyLinksGoCleaner();
