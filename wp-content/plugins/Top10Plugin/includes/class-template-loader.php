<?php
/**
 * Template Loader Class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Top10_Template_Loader
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
        add_filter('template_include', array($this, 'load_template'));
    }

    /**
     * Load custom template for Top 10 pages
     */
    public function load_template($template)
    {
        if (is_singular('top10_page')) {
            global $post;
            
            // Get layout type (default to 'product')
            $layout_type = get_post_meta($post->ID, '_top10_page_layout', true);
            if (empty($layout_type)) {
                $layout_type = 'product';
            }
            
            // Load appropriate template based on layout type
            if ($layout_type === 'supplement') {
                $custom_template = TOP10_PLUGIN_DIR . 'templates/template-top10-supplement.php';
            } else {
                $custom_template = TOP10_PLUGIN_DIR . 'templates/template-top10.php';
            }

            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }

        return $template;
    }
}
