<?php
/**
 * Custom Post Type Class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class SPB_Custom_Post_Type
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
        add_action('init', array($this, 'register_post_type'), 0);
        add_filter('single_template', array($this, 'load_custom_template'));
    }

    /**
     * Register custom post type
     */
    public function register_post_type()
    {
        $labels = array(
            'name'                  => _x('Supplement Pages', 'Post type general name', 'supplement-page-builder'),
            'singular_name'         => _x('Supplement Page', 'Post type singular name', 'supplement-page-builder'),
            'menu_name'             => _x('Supplement Pages', 'Admin Menu text', 'supplement-page-builder'),
            'add_new'               => __('Add New', 'supplement-page-builder'),
            'add_new_item'          => __('Add New Supplement Page', 'supplement-page-builder'),
            'new_item'              => __('New Supplement Page', 'supplement-page-builder'),
            'edit_item'             => __('Edit Supplement Page', 'supplement-page-builder'),
            'view_item'             => __('View Supplement Page', 'supplement-page-builder'),
            'all_items'             => __('All Supplement Pages', 'supplement-page-builder'),
            'search_items'          => __('Search Supplement Pages', 'supplement-page-builder'),
            'not_found'             => __('No supplement pages found.', 'supplement-page-builder'),
            'not_found_in_trash'    => __('No supplement pages found in Trash.', 'supplement-page-builder'),
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
    }

    /**
     * Load custom template
     */
    public function load_custom_template($template)
    {
        if (is_singular('supplement_page')) {
            $custom_template = SPB_PLUGIN_DIR . 'templates/single-supplement-page.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }
        return $template;
    }
}
