<?php
/**
 * Bulk Import Script for Button Plugin + Pattern Deployment
 *
 * Usage: wp eval-file bulk-import.php
 */

if (!defined('ABSPATH')) {
    require_once('wp-load.php');
}

$json_file = __DIR__ . '/products.json';

if (!file_exists($json_file)) {
    echo "Error: products.json not found in " . __DIR__ . "\n";
    exit(1);
}

$products = json_decode(file_get_contents($json_file), true);

if (!$products) {
    echo "Error: Invalid JSON or empty file.\n";
    exit(1);
}

echo "Found " . count($products) . " products to import.\n";

foreach ($products as $index => $product) {
    echo "Processing: " . $product['productName'] . "...\n";

    // 1. Prepare Attributes
    $category = isset($product['productCategory']) ? $product['productCategory'] : 'General';
    $cat_slug = sanitize_title($category);

    // 2. Find the Pattern (wp_block)
    // Priority 1: Category specific pattern e.g. "supplements-two-products"
    // Priority 2: Generic fallback pattern e.g. ending in "-two-products"

    $pattern_content = '';
    $pattern_found_title = '';

    // Try exact category match first
    $specific_slug = $cat_slug . '-two-products';
    $args = [
        'post_type' => 'wp_block',
        'name' => $specific_slug,
        'posts_per_page' => 1,
        'post_status' => 'publish'
    ];
    $query = new WP_Query($args);

    if ($query->have_posts()) {
        $pattern = $query->posts[0];
        $pattern_content = $pattern->post_content;
        $pattern_found_title = $pattern->post_title;
        echo "  -> Found specific pattern: {$pattern->post_name}\n";
    } else {
        // Fallback: Search for ANY block ending in -two-products
        // We can't use 'name' with wildcards, so we query recent blocks and filter, or use like query
        global $wpdb;
        $like = '%-two-products';
        $fallback_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'wp_block' AND post_status = 'publish' AND post_name LIKE %s LIMIT 1",
            $like
        ));

        if ($fallback_id) {
            $pattern_post = get_post($fallback_id);
            $pattern_content = $pattern_post->post_content;
            $pattern_found_title = $pattern_post->post_title;
            echo "  -> Found fallback pattern: {$pattern_post->post_name}\n";
        } else {
            echo "  -> WARNING: No pattern ending in '-two-products' found. Skipping content injection.\n";
            //$pattern_content = '<!-- wp:paragraph --><p>No pattern found.</p><!-- /wp:paragraph -->';
        }
    }

    // 3. Construct the Post Content
    // Structure: Button Plugin Wrapper -> Pattern Content (inside)

    $button_attributes = [
        'productName' => $product['productName'],
        'productCategory' => $category,
        'buttonText' => isset($product['buttonText']) ? $product['buttonText'] : 'Find Alternatives',
        'loadingPreset' => isset($product['loadingPreset']) ? $product['loadingPreset'] : 'set1',
        'buttonColor' => isset($product['buttonColor']) ? $product['buttonColor'] : '#10B981',
        'buttonTextColor' => isset($product['buttonTextColor']) ? $product['buttonTextColor'] : '#ffffff',
    ];

    $encoded_attrs = json_encode($button_attributes);

    // IMPORTANT: The HTML comment format must be exact for Gutenberg
    $block_content = <<<EOT
<!-- wp:create-block/button-plugin $encoded_attrs -->
$pattern_content
<!-- /wp:create-block/button-plugin -->
EOT;

    // 4. Insert Post
    $post_data = [
        'post_title' => $product['productName'] . ' Review',
        'post_content' => $block_content,
        'post_status' => 'publish',
        'post_author' => 1,
        'post_type' => 'post',
    ];

    // Check if post already exists to avoid duplicates (optional, based on title)
    $existing = get_page_by_title($post_data['post_title'], OBJECT, 'post');
    if ($existing) {
        echo "  -> Post already exists (ID: {$existing->ID}). Skipping.\n";
        continue;
    }

    $post_id = wp_insert_post($post_data);

    if ($post_id && !is_wp_error($post_id)) {
        echo "  -> Created Post ID: $post_id\n";

        // Set Category
        if (!term_exists($category, 'category')) {
            wp_create_category($category);
        }
        wp_set_object_terms($post_id, $category, 'category');

        // Set ACF Fields / Meta if needed (Simple meta for now)
        update_post_meta($post_id, 'product_name', $product['productName']);
        update_post_meta($post_id, 'product_category', $category);

    } else {
        echo "  -> Failed to create post: " . ($post_id->get_error_message() ?? 'Unknown error') . "\n";
    }
}

echo "Done.\n";
