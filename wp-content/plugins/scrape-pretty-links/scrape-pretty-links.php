<?php
/**
 * Plugin Name: Scrape Pretty Links
 * Description: Scrape links from any site and export them as a CSV compatible with the official Pretty Links plugin.
 * Version: 1.0.0
 * Author: Bishow Shrestha
 */

if (!defined('ABSPATH')) exit;

define('SPL_PATH', plugin_dir_path(__FILE__));
define('SPL_URL', plugin_dir_url(__FILE__));

class ScrapePrettyLinks {

    public function __construct() {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
        add_action('wp_ajax_spl_scrape_url', [$this, 'ajax_scrape_url']);
        add_action('wp_ajax_spl_resolve_link', [$this, 'ajax_resolve_link']);
        add_action('wp_ajax_spl_scan_my_content', [$this, 'ajax_scan_my_content']);
        add_action('admin_post_spl_export_local', [$this, 'handle_local_export']);
    }

    public function add_menu() {
        add_menu_page(
            'Scrape Pretty Links',
            'Scrape Pretty Links',
            'manage_options',
            'scrape-pretty-links',
            [$this, 'render_page'],
            'dashicons-admin-links',
            65
        );
    }

    public function enqueue_assets($hook) {
        if ('toplevel_page_scrape-pretty-links' !== $hook) return;
        
        wp_enqueue_style('spl-style', SPL_URL . 'assets/css/style.css');
        wp_enqueue_script('spl-js', SPL_URL . 'assets/js/scraper.js', ['jquery'], '1.0.0', true);
        wp_localize_script('spl-js', 'splData', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('spl_nonce')
        ]);
        
        // Add Inter font
        wp_enqueue_style('google-font-inter', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    }

    public function render_page() {
        ?>
        <div class="wrap spl-admin-wrap">
            <div class="spl-header">
                <h1>Scrape Pretty Links</h1>
                <p>Identify, resolve, and export links into an official Pretty Links compatible CSV.</p>
            </div>

            <div class="spl-grid">
                <!-- Local Database Scanner -->
                <div class="spl-card primary-card">
                    <h2>Local Link Scanner</h2>
                    <p>This will scan your site's database for existing <strong>Pretty Links</strong> and <strong>ThirstyAffiliates</strong> and prepare them for export.</p>
                    <div style="margin-top:1.5rem;">
                        <button type="button" id="spl-start-local-scan" class="spl-button pulse">Scan My Pretty Links</button>
                    </div>
                </div>

                <!-- CSV Converter Card -->
                <div class="spl-card">
                    <h2>CSV Converter</h2>
                    <p>Upload any CSV file (like legacy exports) to automatically format it for the official Pretty Links import on Site B.</p>
                    <div class="spl-input-group">
                        <input type="file" id="spl-csv-upload" accept=".csv" class="regular-text" style="display:none;">
                        <button type="button" onclick="document.getElementById('spl-csv-upload').click()" class="spl-button outline">Select CSV</button>
                        <button type="button" id="spl-process-csv" class="spl-button">Convert & Officialise</button>
                    </div>
                    <p id="spl-csv-status" style="margin-top:10px; font-size: 0.9rem;"></p>
                </div>
            </div>

            <div id="spl-progress-container" class="spl-progress-container" style="display:none; margin-bottom: 2rem;">
                <div class="spl-progress-bar">
                    <div id="spl-progress-fill"></div>
                </div>
                <p id="spl-status-text">Scanning database...</p>
            </div>

            <!-- Results Section -->
            <div id="spl-results-section" class="spl-results-section" style="display:none;">
                <div class="spl-card">
                    <div class="spl-table-head">
                        <h3>Scraped Results</h3>
                        <button type="button" id="spl-download-results" class="spl-button">Download Official CSV</button>
                    </div>
                    <div class="spl-table-container">
                        <table class="spl-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Pretty Link (Source)</th>
                                    <th>Target URL (Resolved)</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="spl-results-body">
                                <!-- Results appended here via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    public function ajax_scrape_url() {
        check_ajax_referer('spl_nonce', 'security');
        
        $url = esc_url_raw($_POST['url']);
        if (!$url) wp_send_json_error('Invalid URL');

        $response = wp_remote_get($url, [
            'timeout' => 20,
            'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36'
        ]);

        if (is_wp_error($response)) wp_send_json_error($response->get_error_message());

        $html = wp_remote_retrieve_body($response);
        if (empty($html)) wp_send_json_error('Empty page content.');

        // Extract all links
        preg_match_all('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>/i', $html, $matches);
        $links = array_unique($matches[1]);
        $base_url_obj = parse_url($url);
        $base_host = $base_url_obj['host'];
        $base_scheme = $base_url_obj['scheme'];

        $filtered = [];
        foreach ($links as $link) {
            // Absolute check
            if (strpos($link, '/') === 0) {
                $link = $base_scheme . '://' . $base_host . $link;
            } elseif (strpos($link, 'http') !== 0) {
                continue;
            }

            // Heuristic for "Pretty" links
            if ($this->is_likely_pretty($link, $base_host)) {
                $filtered[] = $link;
            }
        }

        wp_send_json_success(array_values($filtered));
    }

    public function ajax_resolve_link() {
        check_ajax_referer('spl_nonce', 'security');
        
        $url = esc_url_raw($_POST['url']);
        if (!$url) wp_send_json_error('Invalid URL');

        // We use a custom approach to get the final URL more reliably.
        // PHP's native get_headers or a curl call is often better than wp_remote_get for just finding the destination.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_NOBODY, true); // We only need headers
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36');
        
        curl_exec($ch);
        $final_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($final_url && $http_code >= 200 && $http_code < 400) {
            wp_send_json_success(['source' => $url, 'target' => $final_url]);
        } else {
            // Fallback for some servers that block NOBODY requests
            $response = wp_remote_get($url, [
                'timeout' => 15,
                'redirection' => 5,
                'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36'
            ]);
            
            if (is_wp_error($response)) {
                wp_send_json_success(['source' => $url, 'target' => 'Resolve failed', 'error' => true]);
            } else {
                // If it's 200, the URL we have is likely the one.
                wp_send_json_success(['source' => $url, 'target' => $url]);
            }
        }
    }

    private function is_likely_pretty($url, $base_host) {
        $parsed = parse_url($url);
        if ($parsed['host'] !== $base_host) return false;

        $path = trim($parsed['path'], '/');
        if (empty($path)) return false;

        // Common prefixes
        $prefixes = ['go', 'visit', 'out', 'recommends', 'link', 'get', 'refer'];
        $path_parts = explode('/', $path);
        
        if (in_array($path_parts[0], $prefixes)) return true;

        // Custom patterns used by the user's other sites (from conversation history)
        if (preg_match('/-(dtc|tsl|offer|deal|discount)$/', $path)) return true;

        // If it looks like a clean slug (no extension, not a known WP core path)
        if (!preg_match('/\.(html|php|jpg|png|css|js)$/i', $path)) {
            $wp_paths = ['wp-admin', 'wp-content', 'wp-includes', 'category', 'tag', 'author'];
            if (!in_array($path_parts[0], $wp_paths)) {
                 return true; 
            }
        }

        return false;
    }

    public function handle_local_export() {
        check_admin_referer('spl_export_local');
        if (!current_user_can('manage_options')) wp_die('Access denied');

        $links = $this->get_all_local_links();

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=pretty-links-official-export-' . date('Y-m-d') . '.csv');

        $output = fopen('php://output', 'w');
        // Official headers from sample: URL,Slug,Name,"Redirection Type","Track Me","No Follow"
        fputcsv($output, ['URL', 'Slug', 'Name', 'Redirection Type', 'Track Me', 'No Follow']);

        foreach ($links as $link) {
            fputcsv($output, [
                $link['url'],
                $link['slug'],
                $link['name'],
                $link['redirect_type'] ?? '301',
                '1', // Track Me default
                '0'  // No Follow default
            ]);
        }
        fclose($output);
        exit;
    }

    private function get_all_local_links() {
        global $wpdb;
        $links = [];

        // 1. Pretty Links Table
        $prli_table = $wpdb->prefix . 'prli_links';
        if ($wpdb->get_var("SHOW TABLES LIKE '$prli_table'") === $prli_table) {
            $results = $wpdb->get_results("SELECT name, slug, url, redirect_type FROM $prli_table");
            foreach ($results as $row) {
                $links[] = [
                    'name' => $row->name, 
                    'slug' => $row->slug, 
                    'url' => $row->url,
                    'redirect_type' => $row->redirect_type
                ];
            }
        }

        // 2. ThirstyAffiliates
        if (post_type_exists('thirstylink')) {
            $ta_links = get_posts(['post_type' => 'thirstylink', 'posts_per_page' => -1]);
            foreach ($ta_links as $post) {
                $links[] = [
                    'name' => $post->post_title,
                    'slug' => $post->post_name,
                    'url'  => get_post_meta($post->ID, '_ta_destination_url', true)
                ];
            }
        }

        return $links;
    }

    public function ajax_scan_my_content() {
        check_ajax_referer('spl_nonce', 'security');
        
        global $wpdb;
        $posts = $wpdb->get_results("SELECT post_content FROM {$wpdb->posts} WHERE post_status = 'publish' AND post_content LIKE '%href=%'");
        
        $all_links = [];
        $base_host = parse_url(home_url(), PHP_URL_HOST);

        foreach ($posts as $post) {
            preg_match_all('/href=["\']([^"\']+)["\']/i', $post->post_content, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $link) {
                    if (strpos($link, '/') === 0) {
                        $link = home_url($link);
                    }
                    
                    if ($this->is_likely_pretty($link, $base_host)) {
                        $all_links[] = $link;
                    }
                }
            }
        }

        wp_send_json_success(array_values(array_unique($all_links)));
    }
}

new ScrapePrettyLinks();
