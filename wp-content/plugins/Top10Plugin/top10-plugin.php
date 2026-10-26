<?php
/**
 * Plugin Name: Top 10 Plugin
 * Plugin URI: https://example.com/top10-plugin
 * Description: Create dynamic Top 10 product showcase pages with custom admin interface
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: top10-plugin
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}



// Activation hook
register_activation_hook(__FILE__, 'top10_activate');

function top10_activate()
{
    // top10_register_post_types(); // Removed

    flush_rewrite_rules();
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'top10_deactivate');

function top10_deactivate()
{
    flush_rewrite_rules();
}




// Define plugin constants
define('TOP10_VERSION', '1.0.0');
define('TOP10_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('TOP10_PLUGIN_URL', plugin_dir_url(__FILE__));
define('TOP10_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main Top10 Plugin Class
 */
class Top10_Plugin
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
        $this->includes();

        // Initialize classes to register hooks early
        Top10_Custom_Post_Types::get_instance();
        Top10_Blocks::get_instance(); // Move here to catch 'init' hook

        $this->init_hooks();
    }

    /**
     * Include required files
     */
    private function includes()
    {
        require_once TOP10_PLUGIN_DIR . 'includes/class-custom-post-types.php';
        require_once TOP10_PLUGIN_DIR . 'includes/class-meta-boxes.php';
        require_once TOP10_PLUGIN_DIR . 'includes/class-blocks.php';
        require_once TOP10_PLUGIN_DIR . 'includes/class-template-loader.php';
        require_once TOP10_PLUGIN_DIR . 'includes/class-helpers.php';
    }

    /**
     * Initialize hooks
     */
    private function init_hooks()
    {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));



        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }



    /**
     * Initialize plugin
     */
    public function init()
    {
        // Initialize custom post types
        // Top10_Custom_Post_Types::get_instance(); // Moved to __construct to ensure hooks fire

        // Initialize meta boxes
        Top10_Meta_Boxes::get_instance();

        // Top10_Blocks::get_instance(); // Moved to __construct

        // Initialize template loader
        Top10_Template_Loader::get_instance();
    }

    /**
     * Enqueue frontend assets
     */
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets()
    {
        // Force load on all pages for now to ensure blocks render correctly
        $should_load = true;

        /*
        // Check for Custom Post Type
        if (is_singular('top10_page')) {
            $should_load = true;
        }

        // Check for Blocks
        if (!$should_load && is_singular()) {
            $post = get_post();
            if ($post && has_blocks($post->post_content)) {
                $blocks = parse_blocks($post->post_content);
                foreach ($blocks as $block) {
                    if (strpos($block['blockName'], 'top10/') === 0) {
                        $should_load = true;
                        break;
                    }
                }
            }
        }
        */

        if ($should_load) {
            wp_enqueue_style(
                'top10-figma-styles',
                TOP10_PLUGIN_URL . 'assets/css/top10-figma-styles.css',
                array(),
                TOP10_VERSION
            );

            // Try to force font loading if not working
            wp_enqueue_style(
                'google-fonts-popins',
                'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap',
                array(),
                null
            );

            wp_enqueue_script(
                'top10-scripts',
                TOP10_PLUGIN_URL . 'assets/js/top10-scripts.js',
                array('jquery'),
                TOP10_VERSION,
                true
            );

            wp_enqueue_script(
                'top10-sticky-sidebar',
                TOP10_PLUGIN_URL . 'assets/js/sticky-sidebar.js',
                array('jquery'),
                TOP10_VERSION,
                true
            );
        }
    }

    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook)
    {
        global $post_type;

        // Only load on Top 10 post types
        if (in_array($post_type, array('top10_page'))) {
            wp_enqueue_media();

            wp_enqueue_style(
                'top10-admin-styles',
                TOP10_PLUGIN_URL . 'admin/css/admin-styles.css',
                array(),
                TOP10_VERSION
            );

            wp_enqueue_script(
                'top10-admin-scripts',
                TOP10_PLUGIN_URL . 'admin/js/admin-scripts.js',
                array('jquery', 'jquery-ui-sortable'),
                TOP10_VERSION,
                true
            );
        }
    }

   
    public function activate()
    {
        
        require_once TOP10_PLUGIN_DIR . 'includes/class-custom-post-types.php';

        
        $cpt = Top10_Custom_Post_Types::get_instance();
        $cpt->register_post_types();

        flush_rewrite_rules();
    }


    public function deactivate()
    {
        
        flush_rewrite_rules();
    }
}


function top10_plugin_init()
{
    return Top10_Plugin::get_instance();
}


top10_plugin_init();

add_action('wp_footer', function () {
    if (!is_singular())
        return;

    global $post;
    echo '<div style="background:black; color:lime; padding:20px; position:relative; z-index:99999;">';
    echo '<h3>DEBUG: Page ID ' . $post->ID . '</h3>';

    if (has_blocks($post->post_content)) {
        echo '<strong>Blocks Found:</strong><br>';
        $blocks = parse_blocks($post->post_content);
        foreach ($blocks as $block) {
            echo '[' . $block['blockName'] . '] <br>';
           
            if (strpos($block['blockName'], 'top10/') !== false) {
                echo ' -- Attributes: ' . print_r($block['attrs'], true) . '<br>';
            }
        }
    } else {
        echo '<strong>No Blocks Found in Post Content.</strong> Is this page using the Classic Editor?';
    }
    echo '</div>';
});
