<?php


// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Register custom post types directly on init
add_action('init', 'top10_register_post_types');

function top10_register_post_types()
{
    // Register Top 10 Product
    register_post_type('top10_product', array(
        'labels' => array(
            'name' => 'Top 10 Products',
            'singular_name' => 'Product',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Product',
            'edit_item' => 'Edit Product',
            'all_items' => 'All Products',
        ),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-star-filled',
        'supports' => array('title', 'editor', 'thumbnail'),
        'capability_type' => 'post',
    ));

    // Register Top 10 Page
    register_post_type('top10_page', array(
        'labels' => array(
            'name' => 'Top 10 Pages',
            'singular_name' => 'Top 10 Page',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Top 10 Page',
            'edit_item' => 'Edit Top 10 Page',
            'all_items' => 'All Top 10 Pages',
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 21,
        'menu_icon' => 'dashicons-list-view',
        'supports' => array('title', 'editor'),
        'rewrite' => array('slug' => 'top10'),
        'capability_type' => 'post',
    ));
}

// Activation hook
register_activation_hook(__FILE__, 'top10_activate');

function top10_activate()
{
    top10_register_post_types();
    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'top10_deactivate');

function top10_deactivate()
{
    flush_rewrite_rules();
}
