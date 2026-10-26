<?php
/**
 * Custom Post Type Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SIP_Custom_Post_Type
{
    private static $instance = null;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action('init', array($this, 'register_post_type'));
    }

    public function register_post_type()
    {
        $labels = array(
            'name'                  => _x('Site Info', 'Post type general name', 'site-info-plugin'),
            'singular_name'         => _x('Site Info', 'Post type singular name', 'site-info-plugin'),
            'menu_name'             => _x('Site Info', 'Admin Menu text', 'site-info-plugin'),
            'add_new'               => __('Add New', 'site-info-plugin'),
            'add_new_item'          => __('Add New Site Info', 'site-info-plugin'),
            'new_item'              => __('New Site Info', 'site-info-plugin'),
            'edit_item'             => __('Edit Site Info', 'site-info-plugin'),
            'view_item'             => __('View Site Info', 'site-info-plugin'),
            'all_items'             => __('All Site Info', 'site-info-plugin'),
            'search_items'          => __('Search Site Info', 'site-info-plugin'),
            'not_found'             => __('No site info found.', 'site-info-plugin'),
            'not_found_in_trash'    => __('No site info found in Trash.', 'site-info-plugin'),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => false,
            'capability_type'    => 'post',
            'has_archive'        => false,
            'hierarchical'       => false,
            'menu_position'      => 25,
            'menu_icon'          => 'dashicons-info',
            'supports'           => array('title'),
            'show_in_rest'       => false,
        );

        register_post_type('site_info', $args);
    }
}
