<?php
define('WP_USE_THEMES', false);
require_once('wp-load.php');

$query = new WP_Query(array(
    'post_type' => 'any',
    'post_status' => 'any',
    'posts_per_page' => 10,
    's' => 'propsandcons'
));

echo "Found " . $query->found_posts . " posts matching 'propsandcons'\n";

foreach ($query->posts as $post) {
    echo "ID: " . $post->ID . " | Title: " . $post->post_title . " | Status: " . $post->post_status . " | Type: " . $post->post_type . "\n";
    if (strpos($post->post_content, 'affiliate-booster/propsandcons') !== false) {
        echo "  - Contains 'affiliate-booster/propsandcons'\n";
    } else {
        echo "  - Does NOT contain exactly 'affiliate-booster/propsandcons'\n";
        // Show a snippet of content to see how it's stored
        echo "  - Content snippet: " . substr($post->post_content, 0, 500) . "...\n";
    }
}
