<?php
/**
 * Custom Post Types Class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Top10_Custom_Post_Types
{

    /**
     * Instance of this class
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        add_action('init', array($this, 'register_post_types'));
    }

    /**
     * Register custom post types
     */
    public function register_post_types()
    {
        $this->register_product_post_type();

        // $this->register_page_post_type();
    }

    /**
     * Register Top 10 Product post type
     */
    private function register_product_post_type()
    {
        $labels = array(
            'name' => _x('Top 10 Products', 'Post type general name', 'top10-plugin'),
            'singular_name' => _x('Product', 'Post type singular name', 'top10-plugin'),
            'menu_name' => _x('Top 10 Products', 'Admin Menu text', 'top10-plugin'),
            'name_admin_bar' => _x('Product', 'Add New on Toolbar', 'top10-plugin'),
            'add_new' => __('Add New', 'top10-plugin'),
            'add_new_item' => __('Add New Product', 'top10-plugin'),
            'new_item' => __('New Product', 'top10-plugin'),
            'edit_item' => __('Edit Product', 'top10-plugin'),
            'view_item' => __('View Product', 'top10-plugin'),
            'all_items' => __('All Products', 'top10-plugin'),
            'search_items' => __('Search Products', 'top10-plugin'),
            'parent_item_colon' => __('Parent Products:', 'top10-plugin'),
            'not_found' => __('No products found.', 'top10-plugin'),
            'not_found_in_trash' => __('No products found in Trash.', 'top10-plugin'),
        );

        $args = array(
            'labels' => $labels,
            'public' => false,
            'publicly_queryable' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => false,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 20,
            'menu_icon' => 'dashicons-star-filled',
            'supports' => array('title', 'editor', 'thumbnail'),
            'show_in_rest' => true,
        );

        register_post_type('top10_product', $args);
    }

    /**
     * Register Top 10 Page post type
     */
    private function register_page_post_type()
    {
        $labels = array(
            'name' => _x('Top 10 Pages', 'Post type general name', 'top10-plugin'),
            'singular_name' => _x('Top 10 Page', 'Post type singular name', 'top10-plugin'),
            'menu_name' => _x('Top 10 Pages', 'Admin Menu text', 'top10-plugin'),
            'name_admin_bar' => _x('Top 10 Page', 'Add New on Toolbar', 'top10-plugin'),
            'add_new' => __('Add New', 'top10-plugin'),
            'add_new_item' => __('Add New Top 10 Page', 'top10-plugin'),
            'new_item' => __('New Top 10 Page', 'top10-plugin'),
            'edit_item' => __('Edit Top 10 Page', 'top10-plugin'),
            'view_item' => __('View Top 10 Page', 'top10-plugin'),
            'all_items' => __('All Top 10 Pages', 'top10-plugin'),
            'search_items' => __('Search Top 10 Pages', 'top10-plugin'),
            'parent_item_colon' => __('Parent Top 10 Pages:', 'top10-plugin'),
            'not_found' => __('No Top 10 pages found.', 'top10-plugin'),
            'not_found_in_trash' => __('No Top 10 pages found in Trash.', 'top10-plugin'),
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'top10'),
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 21,
            'menu_icon' => 'dashicons-list-view',
            'supports' => array('title', 'editor'),
            'show_in_rest' => true,
        );

        register_post_type('top10_page', $args);
    }
}
