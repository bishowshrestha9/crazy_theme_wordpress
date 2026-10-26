<?php
require_once('wp-load.php');
global $wpdb;

$slug = 'nooceptin';
$alm_table = $wpdb->prefix . 'alm_links';
$prli_table = $wpdb->prefix . 'prli_links';

header('Content-Type: text/plain');

echo "SEARCHING FOR SLUG: $slug\n";
echo "==========================\n\n";

// Check ALM
if ($wpdb->get_var("SHOW TABLES LIKE '$alm_table'") === $alm_table) {
    $alm_res = $wpdb->get_row($wpdb->prepare("SELECT * FROM $alm_table WHERE slug = %s", $slug));
    if ($alm_res) {
        echo "Found in ALM Table:\n";
        print_r($alm_res);
    } else {
        echo "Not found in ALM Table.\n";
    }
}

// Check Pretty Links
if ($wpdb->get_var("SHOW TABLES LIKE '$prli_table'") === $prli_table) {
    $prli_res = $wpdb->get_row($wpdb->prepare("SELECT * FROM $prli_table WHERE slug = %s", $slug));
    if ($prli_res) {
        echo "\nFound in Pretty Links Table:\n";
        print_r($prli_res);
    } else {
        echo "\nNot found in Pretty Links Table.\n";
    }
}

// Check Posts
$post = $wpdb->get_row($wpdb->prepare("SELECT ID, post_title, post_name, post_status, post_type FROM $wpdb->posts WHERE post_name = %s", $slug));
if ($post) {
    echo "\nFound in Posts Table:\n";
    print_r($post);
} else {
    echo "\nNot found in Posts Table by slug '$slug'.\n";
}

// Flush Rewrite Rules just in case
flush_rewrite_rules();
echo "\nRewrite rules flushed.\n";

unlink(__FILE__);
