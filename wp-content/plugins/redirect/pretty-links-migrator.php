<?php
/**
 * Pretty Links Migrator
 * Adds /go/ prefix to affiliate links and updates post content
 */

if (!defined('ABSPATH'))
    exit;

class AMR_Pretty_Links_Migrator
{
    private $patterns = array();
    private $log = array();

    public function __construct()
    {
        // Default patterns to detect affiliate links
        $this->patterns = array(
            '/-dtc$/',           // Matches: whispeara-dtc, echoxen-dtc
            '/-tsl$/',           // Matches: product-tsl
            '/-offer$/',         // Matches: product-offer
            '/-deal$/',          // Matches: product-deal
            '/-discount$/',      // Matches: product-discount
        );

        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_post_amr_migrate_links', array($this, 'handle_migration'));
        add_action('admin_post_amr_preview_migration', array($this, 'handle_preview'));
    }

    public function add_admin_menu()
    {
        add_management_page(
            'Pretty Links Migration',
            'Pretty Links Migration',
            'manage_options',
            'amr-pretty-links-migration',
            array($this, 'render_admin_page')
        );
    }

    public function render_admin_page()
    {
        ?>
        <div class="wrap">
            <h1>Pretty Links Migration Tool</h1>
            <p>This tool will add the <code>/go/</code> prefix to all affiliate links in your posts.</p>

            <div class="card">
                <h2>How It Works</h2>
                <ol>
                    <li>Scans all published posts for affiliate link patterns</li>
                    <li>Identifies links like <code>/whispeara-dtc</code>, <code>/echoxen-dtc</code>, etc.</li>
                    <li>Updates them to <code>/go/whispeara-dtc</code>, <code>/go/echoxen-dtc</code></li>
                    <li>Creates 301 redirects from old URLs to new ones</li>
                    <li>Enables redirect page for all affiliate links</li>
                </ol>
            </div>

            <div class="card">
                <h2>Detected Patterns</h2>
                <p>The following patterns will be detected:</p>
                <ul>
                    <?php foreach ($this->patterns as $pattern): ?>
                        <li><code><?php echo esc_html($pattern); ?></code></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                <?php wp_nonce_field('amr_preview_migration', 'amr_nonce'); ?>
                <input type="hidden" name="action" value="amr_preview_migration">

                <h2>Step 1: Preview Changes</h2>
                <p>Click below to see what will be changed (no changes will be made yet):</p>
                <p>
                    <button type="submit" class="button button-secondary">
                        Preview Migration
                    </button>
                </p>
            </form>

            <?php if (isset($_GET['preview']) && $_GET['preview'] === 'done'): ?>
                <div class="notice notice-info">
                    <p><strong>Preview Complete!</strong> Review the changes below.</p>
                </div>

                <?php
                $preview_data = get_transient('amr_migration_preview');
                if ($preview_data):
                    ?>
                    <div class="card">
                        <h2>Preview Results</h2>
                        <p><strong>Total Posts to Update:</strong>
                            <?php echo count($preview_data['posts']); ?>
                        </p>
                        <p><strong>Total Links to Update:</strong>
                            <?php echo $preview_data['total_links']; ?>
                        </p>

                        <?php if (!empty($preview_data['posts'])): ?>
                            <h3>Posts That Will Be Updated:</h3>
                            <table class="wp-list-table widefat fixed striped">
                                <thead>
                                    <tr>
                                        <th>Post Title</th>
                                        <th>Links Found</th>
                                        <th>Changes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($preview_data['posts'] as $post_data): ?>
                                        <tr>
                                            <td>
                                                <strong>
                                                    <?php echo esc_html($post_data['title']); ?>
                                                </strong><br>
                                                <small><a href="<?php echo get_permalink($post_data['id']); ?>" target="_blank">View
                                                        Post</a></small>
                                            </td>
                                            <td>
                                                <?php echo count($post_data['links']); ?>
                                            </td>
                                            <td>
                                                <ul style="margin: 0;">
                                                    <?php foreach ($post_data['links'] as $link): ?>
                                                        <li>
                                                            <code><?php echo esc_html($link['old']); ?></code>
                                                            →
                                                            <code><?php echo esc_html($link['new']); ?></code>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                            <form method="post" action="<?php echo admin_url('admin-post.php'); ?>"
                                onsubmit="return confirm('Are you sure you want to migrate all links? This will update your post content.');">
                                <?php wp_nonce_field('amr_migrate_links', 'amr_nonce'); ?>
                                <input type="hidden" name="action" value="amr_migrate_links">

                                <h2>Step 2: Apply Migration</h2>
                                <p class="description">⚠️ <strong>Important:</strong> This will modify your post content. Make
                                    sure you have a backup!</p>
                                <p>
                                    <button type="submit" class="button button-primary">
                                        Apply Migration Now
                                    </button>
                                </p>
                            </form>
                        <?php else: ?>
                            <p>No affiliate links found that need migration.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if (isset($_GET['migrated']) && $_GET['migrated'] === 'success'): ?>
                <div class="notice notice-success">
                    <p><strong>Migration Complete!</strong></p>
                    <?php
                    $migration_log = get_transient('amr_migration_log');
                    if ($migration_log):
                        ?>
                        <ul>
                            <li>Posts Updated:
                                <?php echo $migration_log['posts_updated']; ?>
                            </li>
                            <li>Links Updated:
                                <?php echo $migration_log['links_updated']; ?>
                            </li>
                            <li>Redirects Created:
                                <?php echo $migration_log['redirects_created']; ?>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <style>
            .card {
                background: #fff;
                border: 1px solid #ccd0d4;
                border-radius: 4px;
                padding: 20px;
                margin: 20px 0;
                box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
            }

            .card h2 {
                margin-top: 0;
            }

            .card ul,
            .card ol {
                margin-left: 20px;
            }
        </style>
        <?php
    }

    public function handle_preview()
    {
        check_admin_referer('amr_preview_migration', 'amr_nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        $preview_data = $this->scan_posts();
        set_transient('amr_migration_preview', $preview_data, HOUR_IN_SECONDS);

        wp_redirect(admin_url('tools.php?page=amr-pretty-links-migration&preview=done'));
        exit;
    }

    public function handle_migration()
    {
        check_admin_referer('amr_migrate_links', 'amr_nonce');

        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }

        $result = $this->migrate_posts();
        set_transient('amr_migration_log', $result, HOUR_IN_SECONDS);

        wp_redirect(admin_url('tools.php?page=amr-pretty-links-migration&migrated=success'));
        exit;
    }

    private function scan_posts()
    {
        $posts = get_posts(array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ));

        $preview_data = array(
            'posts' => array(),
            'total_links' => 0,
        );

        foreach ($posts as $post) {
            $links = $this->find_affiliate_links($post->post_content);

            if (!empty($links)) {
                $preview_data['posts'][] = array(
                    'id' => $post->ID,
                    'title' => $post->post_title,
                    'links' => $links,
                );
                $preview_data['total_links'] += count($links);
            }
        }

        return $preview_data;
    }

    private function find_affiliate_links($content)
    {
        $links = array();
        $site_url = get_site_url();

        // Find all links in content
        preg_match_all('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>/i', $content, $matches);

        if (!empty($matches[1])) {
            foreach ($matches[1] as $url) {
                // Check if it's an internal link
                if (strpos($url, $site_url) === 0 || strpos($url, '/') === 0) {
                    $path = parse_url($url, PHP_URL_PATH);

                    if ($path) {
                        // Check if it matches any pattern and doesn't already have /go/
                        foreach ($this->patterns as $pattern) {
                            if (preg_match($pattern, $path) && strpos($path, '/go/') === false) {
                                $old_url = $url;
                                $new_path = '/go' . $path;
                                $new_url = str_replace($path, $new_path, $url);

                                $links[] = array(
                                    'old' => $old_url,
                                    'new' => $new_url,
                                    'path' => $path,
                                );
                                break;
                            }
                        }
                    }
                }
            }
        }

        return $links;
    }

    private function migrate_posts()
    {
        $posts = get_posts(array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ));

        $stats = array(
            'posts_updated' => 0,
            'links_updated' => 0,
            'redirects_created' => 0,
        );

        $redirects = array();

        foreach ($posts as $post) {
            $links = $this->find_affiliate_links($post->post_content);

            if (!empty($links)) {
                $updated_content = $post->post_content;

                foreach ($links as $link) {
                    // Replace old URL with new URL
                    $updated_content = str_replace($link['old'], $link['new'], $updated_content);
                    $stats['links_updated']++;

                    // Store redirect info
                    $redirects[$link['path']] = '/go' . $link['path'];
                }

                // Update post
                wp_update_post(array(
                    'ID' => $post->ID,
                    'post_content' => $updated_content,
                ));

                $stats['posts_updated']++;
            }
        }

        // Save redirects
        update_option('amr_affiliate_redirects', $redirects);
        $stats['redirects_created'] = count($redirects);

        return $stats;
    }
}

// Initialize the migrator
new AMR_Pretty_Links_Migrator();
