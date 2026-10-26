<?php

namespace ProsCons;

defined( 'ABSPATH' ) || exit;
use ProsCons\Platforms\PlatformConfig;
use ProsCons\Platforms\PlatformController;
use ProsCons\Helpers\Elementor\WidgetHelper;
use ProsCons\Elementor\ControlsManager;
use ProsCons\Elementor\ElementorManager;
use ProsCons\Analytics\AnalyticsController;
use ProsCons\Modules\ModulesAPI;
use ProsCons\BrokenLinks\BrokenLinkController;
/**
 * Admin class, handles admin screen functionality
 *
 * @package ProsCons
 */
class ProsConsAdmin {
    /**
     * Constructor
     */
    public function __construct() {
        $this->init();
        new PlatformController();
        new AnalyticsController();
        new BrokenLinkController();
        add_action( 'rest_api_init', array(new ModulesAPI(), 'register_routes') );
        new Notice\AdminNoticeManager();
        new ControlsManager();
        new ElementorManager();
    }

    /**
     * Init
     *
     * @return void
     */
    public function init() {
        add_action( 'enqueue_block_editor_assets', array($this, 'editor_assets') );
        add_action( 'enqueue_block_assets', array($this, 'enqueue_block_styles') );
        add_action( 'elementor/editor/after_enqueue_scripts', array($this, 'editor_assets'), 9 );
        add_filter( 'block_categories_all', array($this, 'add_block_category') );
        add_action( 'admin_enqueue_scripts', array($this, 'enqueue_admin_scripts') );
        // Admin Script Translations
        add_action( 'admin_enqueue_scripts', array($this, 'set_script_translations'), 99999999999 );
        // Register pages
        add_action( 'admin_menu', array($this, 'add_affiliate_menu') );
        add_filter( 'plugin_action_links_' . plugin_basename( PROSCONS_PLUGIN_FILE ), array($this, 'prosandcons_add_action_links') );
        add_filter( 'network_admin_plugin_action_links_' . plugin_basename( PROSCONS_PLUGIN_FILE ), array($this, 'prosandcons_add_action_links') );
    }

    /**
     * Add Block Category
     *
     * @param [type] $categories
     */
    public function add_block_category( $categories ) {
        // setup category array
        $affx_category = array(
            'slug'  => 'prosandcons',
            'title' => __( 'ProsCons', 'prosandcons' ),
        );
        // make a new category array and insert ours at position 1
        $new_categories = array();
        $new_categories[0] = $affx_category;
        // rebuild cats array
        foreach ( $categories as $category ) {
            $new_categories[] = $category;
        }
        return $new_categories;
    }

    /**
     * Admin Enqueue Scripts
     *
     * @param string $hook Current admin page hook.
     * @return void
     */
    public function enqueue_admin_scripts( $hook ) {
        if ( 'toplevel_page_prosandcons_blocks' === $hook ) {
            $admin_deps = (include_once plugin_dir_path( PROSCONS_PLUGIN_FILE ) . '/build/adminJS.asset.php');
            wp_register_script(
                'prosandcons-admin',
                plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'build/adminJS.js',
                $admin_deps['dependencies'],
                $admin_deps['version'],
                true
            );
            wp_enqueue_style(
                'affx-googlefonts',
                'https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap',
                array(),
                PROSCONS_VERSION,
                null
            );
            $ajax_nonce = wp_create_nonce( 'prosandcons_ajax_nonce' );
            // Add localization vars.
            wp_localize_script( 'prosandcons-admin', 'ProsConsAdmin', array(
                'customizationData' => affx_get_customization_settings(),
                'pluginUrl'         => esc_url( plugin_dir_url( PROSCONS_PLUGIN_FILE ) ),
                'ajax_nonce'        => $ajax_nonce,
                'licenseActive'     => 'false',
                'ajax_url'          => admin_url( 'admin-ajax.php' ),
                'features'          => apply_filters( 'prosandcons_admin_features', array() ),
                'version'           => PROSCONS_VERSION,
            ) );
            wp_enqueue_script( 'prosandcons-admin' );
            // Styles.
            wp_enqueue_style(
                'prosandcons-dashboard',
                plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'build/dashboard.css',
                array(),
                PROSCONS_VERSION
            );
            // Styles.
            wp_enqueue_style(
                'prosandcons-options-style-css',
                plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'build/admin.css',
                array(),
                PROSCONS_VERSION
            );
            wp_enqueue_style(
                'toastr',
                plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'assets/css/toastr.min.css',
                array(),
                '2.1.3',
                'all'
            );
        }
        wp_enqueue_style(
            'prosandcons-admin-css',
            plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'build/adminCSS.css',
            array(),
            PROSCONS_VERSION
        );
    }

    /**
     * Enqueue block styles in editor iframe and frontend.
     *
     * @return void
     */
    public function enqueue_block_styles() {
        if ( is_admin() ) {
            wp_enqueue_style(
                'prosandcons-block-styles',
                plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'build/admin.css',
                array(),
                PROSCONS_VERSION
            );
        }
    }

    /**
     * Enqueue Blocks.
     *
     * @return void
     */
    public function editor_assets() {
        global $wp_customize;
        $blocks_deps = (include_once plugin_dir_path( PROSCONS_PLUGIN_FILE ) . '/build/blocks.asset.php');
        $blocks_export_deps = (include_once plugin_dir_path( PROSCONS_PLUGIN_FILE ) . '/build/blockComponents.asset.php');
        $amazon_config = new PlatformConfig();
        wp_register_script(
            'prosandcons',
            plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'build/blocks.js',
            $blocks_deps['dependencies'],
            $blocks_deps['version'],
            true
        );
        wp_register_script(
            'prosandcons-block-export',
            plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'build/blockComponents.js',
            $blocks_export_deps['dependencies'],
            $blocks_export_deps['version'],
            true
        );
        // Add localization vars.
        wp_localize_script( 'prosandcons', 'ProsCons', array(
            'customizationData'     => affx_get_customization_settings(),
            'siteURL'               => esc_url( home_url( '/' ) ),
            'pluginUrl'             => esc_url( plugin_dir_url( PROSCONS_PLUGIN_FILE ) ),
            'proActive'             => 'false',
            'WPVersion'             => version_compare( get_bloginfo( 'version' ), '5.9', '>=' ),
            'isAmazonActive'        => ( $amazon_config->is_active() ? 'true' : 'false' ),
            'connectAllButton'      => WidgetHelper::get_connect_all_button_html(),
            'templateLibraryButton' => WidgetHelper::get_template_library_button_html(),
            'hasElementorTemplates' => ( \ProsConsTemplateLibrary::instance()->has_elementor_templates() ? 'true' : 'false' ),
            'ajax_nonce'            => wp_create_nonce( 'prosandcons_ajax_nonce' ),
        ) );
        wp_enqueue_script( 'prosandcons' );
        wp_enqueue_script( 'prosandcons-block-export' );
        $pro_blocks_preview_deps = (include_once plugin_dir_path( PROSCONS_PLUGIN_FILE ) . '/build/proBlocksPreview.asset.php');
        wp_enqueue_script(
            'prosandcons-pro-blocks-preview',
            plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'build/proBlocksPreview.js',
            $pro_blocks_preview_deps['dependencies'],
            $pro_blocks_preview_deps['version'],
            true
        );
        if ( !isset( $wp_customize ) ) {
            wp_enqueue_style(
                'prosandcons-gb-style-css',
                plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'build/admin.css',
                array(),
                PROSCONS_VERSION
            );
        }
        if ( function_exists( 'gutenberg_get_block_categories' ) ) {
            $scripts = 'js/scripts-old.js';
        } elseif ( function_exists( 'get_block_categories' ) ) {
            $scripts = 'js/scripts.js';
        }
        if ( !isset( $wp_customize ) ) {
            wp_enqueue_script(
                'prosandcons-disable-blocks',
                plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'assets/' . $scripts,
                array('wp-edit-post'),
                PROSCONS_VERSION,
                false
            );
            wp_localize_script( 'prosandcons-disable-blocks', 'ProsConsBlocks', affx_get_disabled_blocks() );
        }
        // Styles.
        wp_enqueue_style(
            'fontawesome',
            plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'build/fontawesome.css',
            array(),
            PROSCONS_VERSION
        );
        wp_enqueue_style(
            'prosandcons-editor-css',
            plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'build/editorCSS.css',
            array(),
            filemtime( PROSCONS_PLUGIN_DIR . '/build/editorCSS.css' )
        );
    }

    /**
     * Add ProsCons Menu
     *
     * @return void
     */
    public function add_affiliate_menu() {
        $ADMIN_ICON = base64_encode( '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0)"><path d="M25 8.73L24 7L4 41.64H6H16H18L31 19.12L25 8.73Z" fill="#69758F"/><path d="M23 8.73L24 7L44 41.64H42H32H30L17 19.12L23 8.73Z" fill="#69758F"/><path d="M23 8.73L17 19.12L24 31.25L31 19.12L25 8.73L24 7L23 8.73Z" fill="#A3ACBF"/></g><defs><clipPath id="clip0"><rect width="40" height="34.64" fill="white" transform="translate(4 7)"/></clipPath></defs></svg>' );
        // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
        add_menu_page(
            esc_html__( 'ProsCons', 'prosandcons' ),
            esc_html__( 'ProsCons', 'prosandcons' ),
            'manage_options',
            'prosandcons_blocks',
            array($this, 'render_options_page'),
            'data:image/svg+xml;base64,' . $ADMIN_ICON,
            40
        );
    }

    /**
     * Set Script Translations
     *
     * @return void
     */
    public function set_script_translations() {
        wp_set_script_translations( 'prosandcons', 'prosandcons' );
        // Blocks.
        wp_set_script_translations( 'prosandcons-admin', 'prosandcons' );
        // ProsCons Page.
    }

    /**
     * Render options page
     *
     * @return void
     */
    public function render_options_page() {
        echo '<div id="prosandconsAdminPageRoot"></div>';
    }

    /**
     * Add a link to the settings page to the plugins list
     *
     * @param array $links array of links for the plugins, adapted when the current plugin is found.
     *
     * @return array $links
     */
    public function prosandcons_add_action_links( $links ) {
        $settings_link = '<a href="' . esc_url( admin_url( 'admin.php?page=prosandcons_blocks' ) ) . '">' . esc_html__( 'Settings', 'prosandcons' ) . '</a>';
        array_unshift( $links, $settings_link );
        $get_pro = '<a title="' . esc_html__( 'Get ProsCons Pro', 'prosandcons' ) . '" href="' . esc_url( 'https://prosandconsblocks.com/pricing/?utm_source=prosandcons&utm_medium=getting_started&utm_campaign=pro_upgrade' ) . '" style="font-weight:700; color: #1da867;" target="_blank">' . esc_html__( 'Get ProsCons Pro', 'prosandcons' ) . '</a>';
        array_unshift( $links, $get_pro );
        return $links;
    }

}
