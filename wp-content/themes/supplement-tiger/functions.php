<?php
/**
 * Supplement Tiger functions and definitions
 */

if (!function_exists('supplement_tiger_setup')):
    function supplement_tiger_setup()
    {
        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        // Let WordPress manage the document title.
        add_theme_support('title-tag');

        // Enable support for Post Thumbnails on posts and pages.
        add_theme_support('post-thumbnails');

        // Register standard menu locations
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'supplement-tiger'),
            'footer' => esc_html__('Footer Menu', 'supplement-tiger'),
        ));

        // Switch default core markup to output valid HTML5.
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ));

        // Add support for core custom logo.
        add_theme_support('custom-logo', array(
            'height' => 80,
            'width' => 250,
            'flex-width' => true,
            'flex-height' => true,
        ));
    }
endif;
add_action('after_setup_theme', 'supplement_tiger_setup');

/**
 * Enqueue scripts and styles.
 */
function supplement_tiger_scripts()
{
    wp_enqueue_style('supplement-tiger-style', get_stylesheet_uri(), array(), filemtime(get_stylesheet_directory() . '/style.css'));
}
add_action('wp_enqueue_scripts', 'supplement_tiger_scripts');

/**
 * Optional: Customize excerpt length
 */
function supplement_tiger_excerpt_length($length)
{
    return 20;
}
add_filter('excerpt_length', 'supplement_tiger_excerpt_length', 999);

/**
 * Add "buy-now-btn" class to specific menu items dynamically if needed.
 */
function supplement_tiger_nav_classes($classes, $item)
{
    if (stripos($item->title, 'buy') !== false) {
        $classes[] = 'buy-now-btn';
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'supplement_tiger_nav_classes', 10, 2);

/**
 * Register widget area for the sidebar.
 */
function supplement_tiger_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__('Sidebar', 'supplement-tiger'),
        'id' => 'main-sidebar',
        'description' => esc_html__('Add additional widgets here.', 'supplement-tiger'),
        'before_widget' => '<section id="%1$s" class="sidebar-widget widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
}
add_action('widgets_init', 'supplement_tiger_widgets_init');
