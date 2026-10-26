<?php
/**
 * Site Analyzer Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SIP_Site_Analyzer
{
    private $site_url;
    private $data = array();

    public function __construct($url = '')
    {
        $this->site_url = !empty($url) ? $url : home_url();
    }

    public function analyze()
    {
        $this->data = array();
        
        // Get site overview
        $this->data['overview'] = $this->get_site_overview();
        
        // Get all links from homepage and posts
        $this->data['all_links'] = $this->get_all_links();
        
        // Categorize links
        $this->categorize_links();
        
        // Get Pretty Links
        $this->data['pretty_links'] = $this->get_pretty_links();
        
        // Get Pages with detailed analysis
        $this->data['pages'] = $this->get_pages_detailed();
        
        // Get Products mentioned across the site
        $this->data['products'] = $this->get_products_mentioned();
        
        // Get Images
        $this->data['images'] = $this->get_images();
        
        // Get Plugins
        $this->data['plugins'] = $this->get_active_plugins();
        
        // Get Theme info
        $this->data['theme'] = $this->get_theme_info();
        
        // Get Scripts and Styles
        $this->get_enqueued_assets();
        
        return $this->data;
    }

    private function get_site_overview()
    {
        global $wpdb;
        
        $overview = array(
            'site_name' => get_bloginfo('name'),
            'site_url' => home_url(),
            'site_description' => get_bloginfo('description'),
            'wordpress_version' => get_bloginfo('version'),
            'total_posts' => wp_count_posts('post')->publish,
            'total_pages' => wp_count_posts('page')->publish,
            'total_users' => count_users()['total_users'],
            'active_theme' => wp_get_theme()->get('Name'),
            'php_version' => phpversion(),
            'mysql_version' => $wpdb->db_version(),
        );
        
        // Count custom post types
        $post_types = get_post_types(array('public' => true, '_builtin' => false), 'objects');
        foreach ($post_types as $post_type) {
            $count = wp_count_posts($post_type->name);
            $overview['total_' . $post_type->name] = isset($count->publish) ? $count->publish : 0;
        }
        
        return $overview;
    }

    private function get_all_links()
    {
        $links = array();
        
        // Get links from posts
        $args = array(
            'post_type' => 'any',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        );
        
        $posts = get_posts($args);
        
        foreach ($posts as $post) {
            $content = $post->post_content;
            $post_links = $this->extract_links_from_content($content);
            $links = array_merge($links, $post_links);
        }
        
        // Remove duplicates
        $links = array_map('unserialize', array_unique(array_map('serialize', $links)));
        
        return $links;
    }

    private function extract_links_from_content($content)
    {
        $links = array();
        
        // Find all <a> tags
        preg_match_all('/<a\s+(?:[^>]*?\s+)?href=(["\'])(.*?)\1[^>]*>(.*?)<\/a>/is', $content, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $url = $match[2];
            $text = wp_strip_all_tags($match[3]);
            
            if (!empty($url) && filter_var($url, FILTER_VALIDATE_URL)) {
                $links[] = array(
                    'url' => $url,
                    'text' => $text,
                    'type' => $this->determine_link_type($url),
                );
            }
        }
        
        return $links;
    }

    private function determine_link_type($url)
    {
        $site_domain = parse_url(home_url(), PHP_URL_HOST);
        $link_domain = parse_url($url, PHP_URL_HOST);
        
        if ($site_domain === $link_domain) {
            return 'internal';
        }
        
        return 'external';
    }

    private function categorize_links()
    {
        $external_links = array();
        $internal_links = array();
        $affiliate_links = array();
        
        foreach ($this->data['all_links'] as $link) {
            if ($link['type'] === 'external') {
                $external_links[] = $link;
                
                // Check if it's an affiliate link
                if ($this->is_affiliate_link($link['url'])) {
                    $affiliate_links[] = $link;
                }
            } else {
                $internal_links[] = $link;
            }
        }
        
        $this->data['external_links'] = $external_links;
        $this->data['internal_links'] = $internal_links;
        $this->data['affiliate_links'] = $affiliate_links;
    }

    private function is_affiliate_link($url)
    {
        $affiliate_patterns = array(
            'amazon',
            'clickbank',
            'shareasale',
            'cj.com',
            'jvzoo',
            'warriorplus',
            '/go/',
            '/refer/',
            '/recommends/',
            '/out/',
            '/link/',
            'affiliate',
            'partner',
            'aff_id',
            'ref=',
            'tag=',
        );
        
        $url_lower = strtolower($url);
        
        foreach ($affiliate_patterns as $pattern) {
            if (strpos($url_lower, $pattern) !== false) {
                return true;
            }
        }
        
        return false;
    }

    private function get_pretty_links()
    {
        global $wpdb;
        
        $pretty_links = array();
        
        // Check if Pretty Links plugin is active
        $table_name = $wpdb->prefix . 'prli_links';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
            $results = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
            
            foreach ($results as $row) {
                $pretty_links[] = array(
                    'pretty_url' => home_url() . '/' . $row['slug'],
                    'destination' => $row['url'],
                    'type' => 'Pretty Link',
                );
            }
        }
        
        // Also check for custom redirects in posts
        $args = array(
            'post_type' => 'any',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_redirect_url',
                    'compare' => 'EXISTS',
                ),
            ),
        );
        
        $redirect_posts = get_posts($args);
        
        foreach ($redirect_posts as $post) {
            $redirect_url = get_post_meta($post->ID, '_redirect_url', true);
            if (!empty($redirect_url)) {
                $pretty_links[] = array(
                    'pretty_url' => get_permalink($post->ID),
                    'destination' => $redirect_url,
                    'type' => 'Custom Redirect',
                );
            }
        }
        
        return $pretty_links;
    }
    
    private function get_pages_detailed()
    {
        $pages_data = array();
        
        // Get all post types
        $post_types = get_post_types(array('public' => true), 'objects');
        
        foreach ($post_types as $post_type_obj) {
            $post_type = $post_type_obj->name;
            
            $args = array(
                'post_type' => $post_type,
                'post_status' => 'publish',
                'posts_per_page' => -1,
            );
            
            $posts = get_posts($args);
            
            foreach ($posts as $post) {
                $page_info = array(
                    'id' => $post->ID,
                    'title' => $post->post_title,
                    'type' => $post_type,
                    'type_label' => $post_type_obj->labels->singular_name,
                    'url' => get_permalink($post->ID),
                    'date' => $post->post_date,
                    'meta_title' => $this->get_meta_title($post->ID),
                    'pretty_links' => $this->get_pretty_links_from_page($post),
                    'affiliate_links' => $this->extract_affiliate_links_from_page($post),
                    'views_30_days' => $this->get_page_views_30_days($post->ID),
                    'word_count' => str_word_count(strip_tags($post->post_content)),
                    'products_mentioned' => $this->extract_products_from_page($post),
                    'external_links_count' => $this->count_external_links($post->post_content),
                    'internal_links_count' => $this->count_internal_links($post->post_content),
                );
                
                $pages_data[] = $page_info;
            }
        }
        
        return $pages_data;
    }
    
    private function extract_products_from_page($post)
    {
        $products = array();
        $content = $post->post_content;
        
        // Common product name patterns
        $product_patterns = array(
            // Look for product names in headings
            '/<h[1-6][^>]*>(.*?)<\/h[1-6]>/is',
            // Look for product names in strong/bold
            '/<strong[^>]*>(.*?)<\/strong>/is',
            '/<b[^>]*>(.*?)<\/b>/is',
        );
        
        foreach ($product_patterns as $pattern) {
            preg_match_all($pattern, $content, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $match) {
                    $text = wp_strip_all_tags($match);
                    // Filter out common non-product text
                    if (strlen($text) > 3 && strlen($text) < 100 && !in_array(strtolower($text), array('read more', 'click here', 'buy now', 'learn more'))) {
                        $products[] = $text;
                    }
                }
            }
        }
        
        // Look for product mentions in meta fields
        $meta_keys = array('product_name', 'supplement_name', 'product_title', '_product_name');
        foreach ($meta_keys as $key) {
            $meta_value = get_post_meta($post->ID, $key, true);
            if (!empty($meta_value)) {
                $products[] = $meta_value;
            }
        }
        
        // Remove duplicates and limit
        $products = array_unique($products);
        $products = array_slice($products, 0, 10); // Limit to 10 products per page
        
        return $products;
    }
    
    private function extract_affiliate_links_from_page($post)
    {
        $affiliate_links = array();
        $content = $post->post_content;
        
        // Extract all links from content
        preg_match_all('/<a\s+(?:[^>]*?\s+)?href=(["\'])(.*?)\1[^>]*>(.*?)<\/a>/is', $content, $matches, PREG_SET_ORDER);
        
        foreach ($matches as $match) {
            $url = $match[2];
            $text = wp_strip_all_tags($match[3]);
            
            if ($this->is_affiliate_link($url)) {
                $affiliate_links[] = array(
                    'url' => $url,
                    'text' => $text,
                    'domain' => parse_url($url, PHP_URL_HOST),
                );
            }
        }
        
        return $affiliate_links;
    }
    
    private function count_external_links($content)
    {
        $count = 0;
        preg_match_all('/<a\s+(?:[^>]*?\s+)?href=(["\'])(.*?)\1/is', $content, $matches);
        
        if (!empty($matches[2])) {
            foreach ($matches[2] as $url) {
                if ($this->determine_link_type($url) === 'external') {
                    $count++;
                }
            }
        }
        
        return $count;
    }
    
    private function count_internal_links($content)
    {
        $count = 0;
        preg_match_all('/<a\s+(?:[^>]*?\s+)?href=(["\'])(.*?)\1/is', $content, $matches);
        
        if (!empty($matches[2])) {
            foreach ($matches[2] as $url) {
                if ($this->determine_link_type($url) === 'internal') {
                    $count++;
                }
            }
        }
        
        return $count;
    }
    
    private function get_meta_title($post_id)
    {
        // Try Yoast SEO
        $yoast_title = get_post_meta($post_id, '_yoast_wpseo_title', true);
        if (!empty($yoast_title)) {
            return $yoast_title;
        }
        
        // Try Rank Math
        $rankmath_title = get_post_meta($post_id, 'rank_math_title', true);
        if (!empty($rankmath_title)) {
            return $rankmath_title;
        }
        
        // Try All in One SEO
        $aioseo_title = get_post_meta($post_id, '_aioseo_title', true);
        if (!empty($aioseo_title)) {
            return $aioseo_title;
        }
        
        // Default to post title
        return get_the_title($post_id);
    }
    
    private function get_pretty_links_from_page($post)
    {
        global $wpdb;
        $pretty_link = null;
        
        // Check Pretty Links plugin database
        $table_name = $wpdb->prefix . 'prli_links';
        
        if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name) {
            // Get all pretty links
            $all_pretty_links = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
            
            if (!empty($all_pretty_links)) {
                $content = $post->post_content;
                $post_slug = $post->post_name;
                $post_title = strtolower($post->post_title);
                
                // Strategy 1: Check if pretty link slug is in content (most reliable)
                foreach ($all_pretty_links as $link) {
                    $slug = $link['slug'];
                    
                    // Check multiple patterns
                    if (strpos($content, '/' . $slug) !== false ||
                        strpos($content, '"' . $slug . '"') !== false ||
                        strpos($content, "'" . $slug . "'") !== false ||
                        strpos($content, '=' . $slug) !== false) {
                        $pretty_link = array(
                            'pretty_url' => home_url() . '/' . $slug,
                            'slug' => $slug,
                            'target_url' => $link['url'],
                            'clicks' => isset($link['clicks']) ? intval($link['clicks']) : 0,
                        );
                        break;
                    }
                }
                
                // Strategy 2: Match by post slug
                if (!$pretty_link) {
                    foreach ($all_pretty_links as $link) {
                        $slug = $link['slug'];
                        
                        // Direct match or contains
                        if ($slug === $post_slug || 
                            strpos($post_slug, $slug) !== false ||
                            strpos($slug, $post_slug) !== false) {
                            $pretty_link = array(
                                'pretty_url' => home_url() . '/' . $slug,
                                'slug' => $slug,
                                'target_url' => $link['url'],
                                'clicks' => isset($link['clicks']) ? intval($link['clicks']) : 0,
                            );
                            break;
                        }
                    }
                }
                
                // Strategy 3: Match by title similarity
                if (!$pretty_link) {
                    foreach ($all_pretty_links as $link) {
                        $slug = $link['slug'];
                        $slug_clean = str_replace('-', ' ', $slug);
                        
                        // Check if slug words are in title
                        if (strpos($post_title, $slug) !== false ||
                            strpos($post_title, $slug_clean) !== false) {
                            $pretty_link = array(
                                'pretty_url' => home_url() . '/' . $slug,
                                'slug' => $slug,
                                'target_url' => $link['url'],
                                'clicks' => isset($link['clicks']) ? intval($link['clicks']) : 0,
                            );
                            break;
                        }
                    }
                }
                
                // Strategy 4: If post type is supplement_page, try name field or custom meta
                if (!$pretty_link && $post->post_type === 'supplement_page') {
                    // Check for stored pretty link in post meta
                    $stored_link = get_post_meta($post->ID, '_pretty_link_slug', true);
                    if ($stored_link) {
                        foreach ($all_pretty_links as $link) {
                            if ($link['slug'] === $stored_link) {
                                $pretty_link = array(
                                    'pretty_url' => home_url() . '/' . $link['slug'],
                                    'slug' => $link['slug'],
                                    'target_url' => $link['url'],
                                    'clicks' => isset($link['clicks']) ? intval($link['clicks']) : 0,
                                );
                                break;
                            }
                        }
                    }
                }
            }
        }
        
        return $pretty_link ? array($pretty_link) : array();
    }
    
    private function get_page_views_30_days($post_id)
    {
        global $wpdb;
        $views = 0;
        $date_30_days_ago = date('Y-m-d', strtotime('-30 days'));
        $post_url = get_permalink($post_id);
        
        // WP Statistics Plugin - Check pages table (most accurate for page-specific views)
        if (function_exists('wp_statistics_pages') || defined('WP_STATISTICS_VERSION')) {
            $pages_table = $wpdb->prefix . 'statistics_pages';
            
            if ($wpdb->get_var("SHOW TABLES LIKE '$pages_table'") === $pages_table) {
                // Get the page URI (path only, without domain)
                $uri = str_replace(home_url(), '', $post_url);
                
                // Try with full URI
                $views = $wpdb->get_var($wpdb->prepare(
                    "SELECT SUM(`count`) FROM $pages_table 
                    WHERE `uri` = %s 
                    AND `date` >= %s",
                    $uri,
                    $date_30_days_ago
                ));
                
                // If no results, try with just the path
                if (!$views) {
                    $path = parse_url($post_url, PHP_URL_PATH);
                    $views = $wpdb->get_var($wpdb->prepare(
                        "SELECT SUM(`count`) FROM $pages_table 
                        WHERE `uri` = %s 
                        AND `date` >= %s",
                        $path,
                        $date_30_days_ago
                    ));
                }
                
                // If still no results, try matching post ID
                if (!$views) {
                    $views = $wpdb->get_var($wpdb->prepare(
                        "SELECT SUM(`count`) FROM $pages_table 
                        WHERE `id` = %d 
                        AND `date` >= %s",
                        $post_id,
                        $date_30_days_ago
                    ));
                }
                
                if ($views > 0) {
                    return intval($views);
                }
            }
            
            // Try visitor table as fallback
            $visitor_table = $wpdb->prefix . 'statistics_visitor';
            if ($wpdb->get_var("SHOW TABLES LIKE '$visitor_table'") === $visitor_table) {
                $uri = str_replace(home_url(), '', $post_url);
                $path = parse_url($post_url, PHP_URL_PATH);
                
                $views = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM $visitor_table 
                    WHERE (`last_page_url` = %s OR `last_page_url` = %s) 
                    AND `last_counter` >= %s",
                    $uri,
                    $path,
                    $date_30_days_ago
                ));
                
                if ($views > 0) {
                    return intval($views);
                }
            }
        }
        
        // Post Views Counter Plugin
        if (function_exists('pvc_get_post_views')) {
            $views = pvc_get_post_views($post_id);
            if ($views > 0) {
                return intval($views);
            }
        }
        
        // Jetpack Stats
        if (function_exists('stats_get_csv')) {
            $stats = stats_get_csv('postviews', array(
                'post_id' => $post_id,
                'days' => 30,
                'limit' => 1
            ));
            if (!empty($stats[0]['views'])) {
                return intval($stats[0]['views']);
            }
        }
        
        return 0;
    }
    
    private function get_products_mentioned()
    {
        $all_products = array();
        
        // Get all pages data
        if (!isset($this->data['pages'])) {
            return $all_products;
        }
        
        foreach ($this->data['pages'] as $page) {
            if (!empty($page['products_mentioned'])) {
                foreach ($page['products_mentioned'] as $product) {
                    if (!isset($all_products[$product])) {
                        $all_products[$product] = array(
                            'name' => $product,
                            'mentioned_in' => array(),
                        );
                    }
                    $all_products[$product]['mentioned_in'][] = array(
                        'page_title' => $page['title'],
                        'page_url' => $page['url'],
                        'page_type' => $page['type_label'],
                    );
                }
            }
        }
        
        return array_values($all_products);
    }

    private function get_images()
    {
        $args = array(
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'post_status' => 'inherit',
            'posts_per_page' => -1,
        );
        
        $images = get_posts($args);
        
        $image_data = array();
        
        foreach ($images as $image) {
            $image_data[] = array(
                'title' => $image->post_title,
                'url' => wp_get_attachment_url($image->ID),
                'alt' => get_post_meta($image->ID, '_wp_attachment_image_alt', true),
            );
        }
        
        return $image_data;
    }

    private function get_active_plugins()
    {
        $active_plugins = get_option('active_plugins');
        $plugins = array();
        
        foreach ($active_plugins as $plugin) {
            $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);
            $plugins[] = array(
                'name' => $plugin_data['Name'],
                'version' => $plugin_data['Version'],
                'author' => $plugin_data['Author'],
            );
        }
        
        $this->data['overview']['total_plugins'] = count($plugins);
        
        return $plugins;
    }

    private function get_theme_info()
    {
        $theme = wp_get_theme();
        
        return array(
            'name' => $theme->get('Name'),
            'version' => $theme->get('Version'),
            'author' => $theme->get('Author'),
            'description' => $theme->get('Description'),
        );
    }

    private function get_enqueued_assets()
    {
        global $wp_scripts, $wp_styles;
        
        $this->data['scripts'] = !empty($wp_scripts->queue) ? count($wp_scripts->queue) : 0;
        $this->data['styles'] = !empty($wp_styles->queue) ? count($wp_styles->queue) : 0;
    }
}
