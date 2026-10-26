<?php
/**
 * Flush Rewrite Rules & Force Activation Helper
 * 
 * Visit: http://localhost:10018/wp-content/plugins/SupplementPageBuilder/flush-rules.php
 * Then delete this file.
 */

// Load WordPress
define('WP_USE_THEMES', false);
require_once(dirname(__FILE__) . '/../../../wp-load.php');

// Check if user is logged in as admin
if (!is_user_logged_in() || !current_user_can('manage_options')) {
    die('Access denied. Please log in as an administrator.');
}

// Load plugin files
require_once('includes/class-custom-post-type.php');
require_once('includes/class-meta-boxes.php');
require_once('includes/class-template-loader.php');
require_once('includes/class-blocks.php');

// Initialize custom post type
$cpt = SPB_Custom_Post_Type::get_instance();

// Manually register the post type
$labels = array(
    'name'                  => 'Supplement Pages',
    'singular_name'         => 'Supplement Page',
    'menu_name'             => 'Supplement Pages',
    'add_new'               => 'Add New',
    'add_new_item'          => 'Add New Supplement Page',
    'new_item'              => 'New Supplement Page',
    'edit_item'             => 'Edit Supplement Page',
    'view_item'             => 'View Supplement Page',
    'all_items'             => 'All Pages',
    'search_items'          => 'Search Supplement Pages',
    'not_found'             => 'No supplement pages found.',
    'not_found_in_trash'    => 'No supplement pages found in Trash.',
);

$args = array(
    'labels'             => $labels,
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array('slug' => 'supplement'),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => 5,
    'menu_icon'          => 'dashicons-analytics',
    'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
    'show_in_rest'       => true,
);

register_post_type('supplement_page', $args);

// Flush rewrite rules
flush_rewrite_rules(true);

echo '<!DOCTYPE html>
<html>
<head>
    <title>Plugin Activated</title>
    <style>
        body { font-family: -apple-system, sans-serif; max-width: 800px; margin: 50px auto; padding: 20px; }
        h1 { color: #2ecc71; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .steps { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #0073aa; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px; }
        .btn:hover { background: #005a87; }
    </style>
</head>
<body>
    <h1>✓ Supplement Page Builder Activated!</h1>
    <div class="success">
        <strong>Success!</strong> The post type has been registered and rewrite rules have been flushed.
    </div>
    <div class="steps">
        <h3>Next Steps:</h3>
        <ol>
            <li>Go back to your WordPress admin dashboard</li>
            <li>Look for <strong>"Supplement Pages"</strong> in the sidebar (near Posts/Pages)</li>
            <li>Click "Add New" to create your first supplement page</li>
            <li><strong>Delete this file (flush-rules.php)</strong> for security</li>
        </ol>
    </div>
    <a href="' . admin_url() . '" class="btn">← Go to Dashboard</a>
    <a href="' . admin_url('post-new.php?post_type=supplement_page') . '" class="btn">+ Create New Supplement Page</a>
</body>
</html>';
