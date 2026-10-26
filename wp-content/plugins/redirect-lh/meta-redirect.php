<?php
/*
Plugin Name: Auto Meta Redirect 
Description: Intercepts Site A links and kills the Pretty Links redirect loop with skeleton loading animation.
Version: 1.6
Author: Bishow Shrestha
*/

if (!defined('ABSPATH')) exit;

function amr_get_redirect_delay() {
    // Fixed 5 second delay for skeleton loading
    return 5;
}

function amr_get_allowed_redirect_hosts_setting() {
    return (string) get_option('amr_allowed_redirect_hosts', '');
}

function amr_parse_host_list($raw_hosts) {
    $raw_hosts = (string) $raw_hosts;
    if ($raw_hosts === '') return array();

    $parts = preg_split('/[\r\n,\s]+/', $raw_hosts);
    $hosts = array();
    foreach ($parts as $part) {
        $part = trim($part);
        if ($part === '') continue;

        $part = preg_replace('#^https?://#i', '', $part);
        $part = preg_replace('#/.*$#', '', $part);
        $part = strtolower(trim($part, ". \t\n\r\0\x0B"));
        if ($part === '') continue;
        $hosts[] = $part;
    }

    return array_values(array_unique($hosts));
}

function amr_get_pretty_links_hosts($limit = 250) {
    global $wpdb;
    if (!$wpdb) return array();

    $limit = (int) apply_filters('amr_pretty_links_hosts_limit', (int) $limit);
    if ($limit < 1) {
        $limit = 250;
    }

    $table_name = $wpdb->prefix . 'prli_links';
    $exists = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table_name));
    if ($exists !== $table_name) return array();

    $rows = $wpdb->get_col($wpdb->prepare(
        "SELECT url FROM $table_name WHERE url <> '' LIMIT %d",
        (int) $limit
    ));

    $hosts = array();
    foreach ((array) $rows as $url) {
        $parsed = wp_parse_url($url);
        if (empty($parsed['host'])) continue;
        $hosts[] = strtolower($parsed['host']);
    }

    return array_values(array_unique($hosts));
}

function amr_host_is_allowed($host, $allowed_hosts) {
    $host = strtolower((string) $host);
    if ($host === '') return false;

    foreach ((array) $allowed_hosts as $allowed) {
        $allowed = strtolower((string) $allowed);
        if ($allowed === '') continue;
        if ($host === $allowed) return true;
        if (substr($host, -strlen('.' . $allowed)) === '.' . $allowed) return true;
    }

    return false;
}

function amr_get_allowed_redirect_hosts() {
    $hosts = amr_parse_host_list(amr_get_allowed_redirect_hosts_setting());
    $hosts = array_merge($hosts, amr_get_pretty_links_hosts());

    $site_host = wp_parse_url(home_url(), PHP_URL_HOST);
    if (!empty($site_host)) {
        $hosts[] = strtolower($site_host);
    }

    $hosts = apply_filters('amr_allowed_redirect_hosts', $hosts);
    $hosts = array_values(array_unique(array_filter(array_map('strtolower', (array) $hosts))));

    return $hosts;
}

function amr_is_allowed_redirect_url($url) {
    $url = esc_url_raw($url);
    if (!$url) return false;

    $parsed = wp_parse_url($url);
    if (empty($parsed['scheme']) || empty($parsed['host'])) return false;

    $scheme = strtolower($parsed['scheme']);
    if ($scheme !== 'http' && $scheme !== 'https') return false;

    $host = strtolower($parsed['host']);
    $allowed_hosts = amr_get_allowed_redirect_hosts();
    if (empty($allowed_hosts)) {
        $site_host = wp_parse_url(home_url(), PHP_URL_HOST);
        return $site_host ? strtolower($site_host) === $host : false;
    }

    return amr_host_is_allowed($host, $allowed_hosts);
}

function amr_show_redirect_page() {
    // Always show skeleton loading page
    return true;
}

function amr_intercept_and_kill_loop() {
    if (is_admin() || wp_doing_ajax()) return;

    // Handle direct target redirect via query parameter (from JS processor)
    if (isset($_GET['amr_redirect']) && $_GET['amr_redirect'] == '1') {
        $raw_target = isset($_GET['target']) ? wp_unslash($_GET['target']) : '';
        $raw_target = esc_url_raw($raw_target);

        // DEBUG: Log JS-triggered redirect
        error_log('=== AMR JS REDIRECT TRIGGERED ===');
        error_log('Target: ' . $raw_target);
        error_log('Show page setting: ' . (amr_show_redirect_page() ? 'YES' : 'NO'));

        if ($raw_target && amr_is_allowed_redirect_url($raw_target)) {
            $GLOBALS['amr_target_url'] = $raw_target;
        } else {
            $GLOBALS['amr_target_url'] = '';
            $GLOBALS['amr_redirect_error'] = 'Invalid Redirect: destination is not allowed.';
            status_header(400);
            error_log('ERROR: Invalid redirect URL: ' . $raw_target);
        }

        $template = plugin_dir_path(__FILE__) . 'redirect-template.php';
        if (file_exists($template)) {
            include $template;
            exit;
        } else {
            error_log('ERROR: Template not found (JS redirect): ' . $template);
        }
    }

    // Get the request path relative to the site's home URL
    $home_path = parse_url(home_url(), PHP_URL_PATH);
    $request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    
    if ($home_path && strpos($request_uri, $home_path) === 0) {
        $request_path = substr($request_uri, strlen($home_path));
    } else {
        $request_path = $request_uri;
    }
    
    $request_path = trim($request_path, '/');
    if (empty($request_path)) return;

    global $wpdb;
    $table_name = $wpdb->prefix . 'prli_links';

    // Verify if Pretty Links table exists first
    if ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table_name)) != $table_name) {
        return;
    }

    // Check if it's a Pretty Link
    $link = $wpdb->get_row($wpdb->prepare(
        "SELECT url FROM $table_name WHERE slug = %s LIMIT 1",
        $request_path
    ));

    if ($link) {
        error_log('=== AMR PRETTY LINK DETECTED ===');
        error_log('Slug: ' . $request_path);
        error_log('Target URL: ' . $link->url);
        
        // --- THE FIX ---
        // 1. Remove Pretty Links from the execution chain so it can't redirect
        remove_action('template_redirect', 'prli_issue_redirect', 10);
        remove_action('template_redirect', 'prli_issue_redirect', 11);
        remove_action('init', 'prli_issue_redirect', 10);
        remove_action('init', 'prli_issue_redirect', 11);

        // 2. Load our data
        $GLOBALS['amr_target_url'] = $link->url;

        $template = plugin_dir_path(__FILE__) . 'redirect-template.php';
        
        // DEBUG: Remove after testing
        error_log('=== AMR DEBUG ===');
        error_log('Show redirect page: ' . (amr_show_redirect_page() ? 'YES' : 'NO'));
        error_log('Template path: ' . $template);
        error_log('Template exists: ' . (file_exists($template) ? 'YES' : 'NO'));
        error_log('Target URL: ' . $GLOBALS['amr_target_url']);
        error_log('================');
        
        if (file_exists($template)) {
            // Force a 200 OK status so the browser stops thinking it's a redirect
            status_header(200);
            include $template;
            exit; 
        } else {
            error_log('ERROR: Template file not found at: ' . $template);
        }
    }
}

// Run at 'init' to ensure we can remove the Pretty Links hooks
add_action('init', 'amr_intercept_and_kill_loop', 1);

// Enqueue the client-side link processor
function amr_enqueue_scripts() {
    if (is_admin() || wp_doing_ajax()) return;

    $allowed_hosts = amr_get_allowed_redirect_hosts();
    
    wp_enqueue_script(
        'amr-link-processor',
        plugin_dir_url(__FILE__) . 'link-processor.js',
        array(),
        '1.4',
        true
    );
    
    // Pass home URL to JS
    wp_add_inline_script('amr-link-processor', 'var amrData = ' . json_encode(array(
        'homeUrl' => home_url('/'),
        'allowedHosts' => $allowed_hosts,
    )), 'before');
}
add_action('wp_enqueue_scripts', 'amr_enqueue_scripts');

// --- SETTINGS PAGE ---
function amr_add_settings_page() {
    add_options_page(
        'Auto Meta Redirect Settings',
        'Auto Meta Redirect',
        'manage_options',
        'auto-meta-redirect',
        'amr_render_settings_page'
    );
}
add_action('admin_menu', 'amr_add_settings_page');

function amr_render_settings_page() {
    if (isset($_POST['amr_save_settings'])) {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized', 403);
        }
        check_admin_referer('amr_settings_nonce');
        update_option('amr_allowed_redirect_hosts', sanitize_textarea_field($_POST['amr_allowed_hosts'] ?? ''));
        echo '<div class="notice notice-success is-dismissible"><p>Settings saved!</p></div>';
    }

    $allowed_hosts_setting = amr_get_allowed_redirect_hosts_setting();
    ?>
    <div class="wrap">
        <h1>Auto Meta Redirect Settings</h1>
        <p>This plugin shows a skeleton loading animation when redirecting Pretty Links to hide Site A affiliate URLs.</p>
        <form method="post">
            <?php wp_nonce_field('amr_settings_nonce'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Redirect Delay</th>
                    <td>
                        <strong>5 seconds</strong>
                        <p class="description">Fixed delay before redirecting to the target URL.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Allowed Redirect Hosts</th>
                    <td>
                        <textarea name="amr_allowed_hosts" rows="5" class="large-text code" placeholder="example.com&#10;affiliate-network.com"><?php echo esc_textarea($allowed_hosts_setting); ?></textarea>
                        <p class="description">Optional. Comma/newline-separated hostnames (subdomains allowed). This plugin automatically allows hosts found in your Pretty Links target URLs (plus your current site host). Use this box only to add extra allowed domains.</p>
                    </td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="amr_save_settings" class="button button-primary" value="Save Settings">
            </p>
        </form>
    </div>
    <?php
}



/* Keep your settings/helper functions below */