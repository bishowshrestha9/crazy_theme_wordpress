<?php
/**
 * Template Loader Class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class SPB_Template_Loader
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
     * Load custom template
     */
    public function load_template($template)
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
