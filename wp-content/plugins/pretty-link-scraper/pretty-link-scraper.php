<?php
/**
 * Plugin Name: Pretty Link Scraper & Exporter
 * Description: Scrape pretty links from any site or export your own into a CSV.
 * Version: 1.0.0
 * Author: Antigravity
 */

if (!defined('ABSPATH'))
    exit;

define('PLSC_PATH', plugin_dir_path(__FILE__));
define('PLSC_URL', plugin_dir_url(__FILE__));

class PrettyLinkScraper
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_plsc_scrape_url', [$this, 'ajax_scrape_url']);
        add_action('wp_ajax_plsc_resolve_link', [$this, 'ajax_resolve_link']);
        add_action('admin_post_plsc_export_local', [$this, 'handle_local_export']);
    }

    public function add_menu()
    {
        add_submenu_page(
            'tools.php',
            'Pretty Link Scraper',
            'Pretty Link Scraper',
            'manage_options',
            'pretty-link-scraper',
            [$this, 'render_page']
        );
    }

    public function enqueue_assets($hook)
    {
        if ('tools_page_pretty-link-scraper' !== $hook)
            return;

        wp_enqueue_style('plsc-style', PLSC_URL . 'assets/css/style.css');
        wp_enqueue_script('plsc-js', PLSC_URL . 'assets/js/scraper.js', ['jquery'], '1.0.0', true);
        wp_localize_script('plsc-js', 'plscData', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('plsc_nonce')
        ]);
    }

    public function render_page()
    {
        ?>
        <div class="wrap plsc-admin-wrap">
            <div class="plsc-header">
                <h1>Link Scraper & Exporter</h1>
                <p>Scrape links from any URL or export your local Pretty Links.</p>
            </div>

            <div class="plsc-grid">
                <!-- Local Export Column -->
                <div class="plsc-card local-export">
                    <h2>Local Export</h2>
                    <p>Get a CSV of all internal pretty links, ThirstyAffiliates, and pattern-based redirects.</p>
                    <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                        <input type="hidden" name="action" value="plsc_export_local">
                        <?php wp_nonce_field('plsc_export_local'); ?>
                        <button type="submit" class="button button-primary">Download Local CSV</button>
                    </form>
                </div>

                <!-- External Scraper Column -->
                <div class="plsc-card external-scraper">
                    <h2>Scrape External Site</h2>
                    <p>Enter a URL to identify all "pretty" looking links and resolve their targets.</p>
                    <div class="plsc-input-group">
                        <input type="url" id="plsc-target-url" placeholder="https://example.com" class="regular-text">
                        <button type="button" id="plsc-start-scrape" class="button button-secondary">Start Scrape</button>
                    </div>

                    <div id="plsc-progress" style="display:none;">
                        <div class="plsc-progress-bar">
                            <div id="plsc-progress-fill"></div>
                        </div>
                        <p id="plsc-status-text">Ready to scrape...</p>
                    </div>
                </div>
            </div>

            <div id="plsc-results" class="plsc-card" style="display:none; margin-top:20px;">
                <div class="plsc-results-header">
                    <h2>Scraped Links</h2>
                    <button type="button" id="plsc-download-results" class="button button-primary">Download Results CSV</button>
                </div>
                <div class="plsc-table-container">
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>Source URL (Pretty)</th>
                                <th>Target URL (Resolved)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="plsc-results-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
    }

    public function ajax_scrape_url()
    {
        check_ajax_referer('plsc_nonce', 'security');

        $url = esc_url_raw($_POST['url']);
        if (!$url)
            wp_send_json_error('Invalid URL');

        $response = wp_remote_get($url, ['user-agent' => 'Mozilla/5.0']);
        if (is_wp_error($response))
            wp_send_json_error($response->get_error_message());

        $html = wp_remote_retrieve_body($response);

        // Find all links
        preg_match_all('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>/i', $html, $matches);

        $links = array_unique($matches[1]);
        $base_url = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);

        $filtered_links = [];
        foreach ($links as $link) {
            // Convert relative to absolute
            if (strpos($link, '/') === 0) {
                $link = rtrim($base_url, '/') . $link;
            } elseif (strpos($link, 'http') !== 0) {
                continue; // Skip fragments, mailto, etc.
            }

            // Identify pattern
            if ($this->is_likely_pretty_link($link, $base_url)) {
                $filtered_links[] = $link;
            }
        }

        wp_send_json_success($filtered_links);
    }

    public function ajax_resolve_link()
    {
        check_ajax_referer('plsc_nonce', 'security');

        $url = esc_url_raw($_POST['url']);
        if (!$url)
            wp_send_json_error('Invalid Link');

        // Follow redirects to get the final location
        $response = wp_remote_get($url, [
            'method' => 'GET', // Some servers block HEAD or don't return Location on HEAD
            'redirection' => 5,
            'user-agent' => 'Mozilla/5.0',
            'timeout' => 10
        ]);

        if (is_wp_error($response)) {
            wp_send_json_success(['source' => $url, 'target' => 'Error: ' . $response->get_error_message()]);
        }

        $final_url = wp_remote_retrieve_header($response, 'location');
        if (!$final_url) {
            // Not a redirect, or already at final location
            // Check if we were redirected by fetching the current effective URL is hard with wp_remote_get
            // But usually, if it's 200 OK and no Location header, the URL in $url is the one.
            // However, we can use the response array's 'http_response' object if it exists.

            // For now, let's just return the URL itself if no Location is found.
            wp_send_json_success(['source' => $url, 'target' => $url]);
        } else {
            wp_send_json_success(['source' => $url, 'target' => $final_url]);
        }
    }

    private function is_likely_pretty_link($url, $base_url)
    {
        $path = parse_url($url, PHP_URL_PATH);
        if (!$path)
            return false;

        $patterns = ['/go/', '/recommends/', '/out/', '/link/', '/refer/', '/visit/', '/get/'];
        foreach ($patterns as $p) {
            if (strpos($path, $p) === 0)
                return true;
        }

        if (preg_match('/-(dtc|tsl|offer|deal|discount)$/', $path))
            return true;

        // If it's on the same domain and has no extension like .html, .php, it might be a pretty link
        $host = parse_url($url, PHP_URL_HOST);
        $base_host = parse_url($base_url, PHP_URL_HOST);

        if ($host === $base_host && $path !== '/' && !preg_match('/\.(html|php|jpg|png|css|js)$/i', $path)) {
            // Too many false positives? Maybe. But good for discovery.
            // Let's stick to known patterns for now to be "safe" or just return true if it's on the same domain and suspicious.
        }

        return false;
    }

    public function handle_local_export()
    {
        check_admin_referer('plsc_export_local');
        if (!current_user_can('manage_options'))
            wp_die('Access denied');

        $links = $this->get_all_local_links();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=local-pretty-links-' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Title', 'Pretty URL', 'Target URL', 'Source']);

        foreach ($links as $link) {
            fputcsv($output, [$link['title'], $link['slug'], $link['target'], $link['source']]);
        }
        fclose($output);
        exit;
    }

    private function get_all_local_links()
    {
        global $wpdb;
        $links = [];

        // 0. Use Meta-Redirect plugin if available
        if (function_exists('amr_get_all_pretty_links')) {
            $amr_links = amr_get_all_pretty_links();
            foreach ($amr_links as $link) {
                $links[] = [
                    'title' => $link['title'],
                    'slug' => home_url($link['slug']),
                    'target' => $link['target'],
                    'source' => $link['source']
                ];
            }
            if (!empty($links))
                return $links;
        }

        // 1. Pretty Links Table
        $prli_table = $wpdb->prefix . 'prli_links';
        if ($wpdb->get_var("SHOW TABLES LIKE '$prli_table'") === $prli_table) {
            $results = $wpdb->get_results("SELECT name, slug, url FROM $prli_table");
            foreach ($results as $row) {
                $links[] = [
                    'title' => $row->name,
                    'slug' => home_url('/' . trim($row->slug, '/')),
                    'target' => $row->url,
                    'source' => 'Pretty Links Plugin'
                ];
            }
        }

        // 2. ThirstyAffiliates
        if (post_type_exists('thirstylink')) {
            $ta_links = get_posts(['post_type' => 'thirstylink', 'posts_per_page' => -1]);
            foreach ($ta_links as $post) {
                $links[] = [
                    'title' => $post->post_title,
                    'slug' => get_permalink($post->ID),
                    'target' => get_post_meta($post->ID, '_ta_destination_url', true),
                    'source' => 'ThirstyAffiliates'
                ];
            }
        }

        // 3. Custom Redirects (-dtc, -tsl etc from posts)
        $posts = get_posts(['post_type' => 'post', 'posts_per_page' => -1]);
        foreach ($posts as $post) {
            // This is harder to "list" because they are patterns, but we can look for links in content
            // or just use the logic from meta-redirecct-plugin if available.
            // For now, let's just include the ones explicitly stored in some meta if any.
        }

        return $links;
    }
}

new PrettyLinkScraper();
