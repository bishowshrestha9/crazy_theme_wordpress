<?php
/**
 * Plugin Name: AffiliatePages - Pros & Cons, Notice, and CTA Blocks for Affiliates
 * Plugin URI: https://wordpress.org/plugins/affiliatebooster-plugin/
 * Description: #1 affiliate plugin to increase your sales.
 * Version: 3.1.1
 * Author: Jitendra Vaswani, Vashishtha Kapoor
 * Contributors: Affiliate Booster
 * Author URI: https://www.affiliatebooster.com/
 * License: GPL2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Text Domain: affiliatebooster-blocks
 */

/**
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

define('AFB_DOMAIN', 'affiliate-booster');
define('AFB_DIR', plugin_dir_path(__FILE__));
define('AFB_URL', plugins_url('/', __FILE__));

/**
 * Initialize the blocks
 */
function affiliate_booster_gutenberg_loader() {
    /**
     * Load the blocks functionality
     */
    require_once ( AFB_DIR . 'dist/init.php');
    require_once ( AFB_DIR . 'disableblocks/class-abdisable-gutenberg-blocks.php');

    include_once ABSPATH . 'wp-admin/includes/plugin.php';

    if ( is_plugin_active( 'gutenberg/gutenberg.php' ) || version_compare( get_bloginfo( 'version' ), '4.9.9', '>' ) ) {
        ABDisable_Gutenberg_Blocks::instance();
    }
}

add_action('plugins_loaded', 'affiliate_booster_gutenberg_loader');

/**
 * Load the plugin text-domain
 */
function affiliate_booster_gutenberg_init() {
    load_plugin_textdomain('affiliate-booster', false, basename(dirname(__FILE__)) . '/languages');
    add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ),  'affiliate_booster_add_action_links'  );
}

add_action('init', 'affiliate_booster_gutenberg_init');


function affiliate_booster_enqueue_scripts() {
    wp_enqueue_style( 'wp-color-picker' );
    
    // file is not exist
    // wp_enqueue_script( 'abglobalsetting', AFB_URL . '/dist/assets/js/ab-global-setting.js', array('jquery','wp-color-picker'), null, true );
    wp_localize_script( 'wp-components', 'abglobalsetting',
        array( 
            'data_btnbg' => get_option( 'affiliate_booster_btnbgcolor' ),
            'data_btnhoverbg' => get_option( 'affiliate_booster_btnbghovercolor' ),

            'data_btnborder' => get_option( 'affiliate_booster_btnbordercolor' ),
            'data_btnhoverborder' => get_option( 'affiliate_booster_btnborderhovercolor' ),
            'data_btnbordertype' => get_option( 'affiliate_booster_btnbordertype' ),

            'data_btntext' => get_option( 'affiliate_booster_btntextcolor' ),
            'data_btnhovertext' => get_option( 'affiliate_booster_btntexthovercolor' ),

            'data_prosiconcolor' => get_option( 'affiliate_booster_prosiconcolor' ),
            'data_consiconcolor' => get_option( 'affiliate_booster_consiconcolor' ),

            'data_iconlisticoncolor' => get_option( 'affiliate_booster_iconlisticoncolor' ),
            
            'data_boxbordercolor' => get_option( 'affiliate_booster_boxbordercolor' ),
        )
    );
}
add_action( 'admin_enqueue_scripts', 'affiliate_booster_enqueue_scripts' );

/**
 * Load the plugin welcome page css
 */
function affiliate_booster_load_admin_scripts( $hook ) {
	wp_register_style( 'affiliatebooster-welcome', AFB_URL.'/assets/css/affiliatebooster-welcome.css', false, '' );
	wp_enqueue_style( 'affiliatebooster-welcome' );
}
add_action( 'admin_enqueue_scripts', 'affiliate_booster_load_admin_scripts' );


/**
 * Add a check for our plugin before redirecting
 */
function affiliate_booster_gutenberg_activate( $wp = '3.4.1', $php = '7.4' ) {
    global $wp_version;
    if ( version_compare( PHP_VERSION, $php, '<' ) ){
        $flag = 'PHP';
	}else{
        return;
	}	
	// $version = 'PHP' == $flag ? $php : $wp;
	if('PHP' == $flag){
		$version = $php;
		deactivate_plugins( basename( __FILE__ ) );
    	wp_die('<p>The <strong>Affiliate Booster</strong> plugin requires '.$flag.' version '.$version.' or greater.</p>','Plugin Activation Error',  array( 'response'=>200, 'back_link'=>TRUE ) );
	}
    
}
register_activation_hook( __FILE__, 'affiliate_booster_gutenberg_activate');


/**
 * Redirect to the affiliate booster page on single plugin activation
 */
function affiliate_booster_gutenberg_redirect() {
    if ( get_option( 'affiliate_booster_gutenberg_do_activation_redirect', false ) ) {
        delete_option( 'affiliate_booster_gutenberg_do_activation_redirect' );
        if( !isset( $_GET['activate-multi'] ) ) {
            wp_redirect( "admin.php?page=affiliate_booster" );
        }
    }
}
add_action( 'admin_init', 'affiliate_booster_gutenberg_redirect' );

/**
 * Adds a menu item for the affiliatebooster-blocks page.
 *
 * since 1.0.0
 */
function affiliate_booster_getting_started_menu() {

	add_menu_page(
		__( 'AffiliatePages', 'affiliatebooster-blocks' ),
		__( 'AffiliatePages', 'affiliatebooster-blocks' ),
		'manage_options',
		'affiliate_booster',
		'affiliate_booster_welcome_page',
		AFB_URL.'/assets/images/icon.png'
	);

}
add_action( 'admin_menu', 'affiliate_booster_getting_started_menu' );


/**
 * Outputs the markup used on the affiliatebooster-blocks
 *
 * since 1.0.0
 */
function affiliate_booster_welcome_page() {
	require_once plugin_dir_path( __FILE__ ) . 'src/welcome_page.php';
}

/**
 * Create Affiliate Booster Categories
 */
add_filter( 'block_categories_all', function( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug' => 'affiliate-booster',
				'title' => __( 'AffiliatePages', 'affiliate-booster' ),
			),
		)
	);
}, 10, 2 );


function affiliate_booster_add_action_links( $links ) {

	
	$get_pro = '<a title="' . esc_html__( 'Get AffiliatePages Pro', 'affiliate-booster' ) .'" href="'. esc_url( "https://www.affiliatebooster.com/" ) .'" style="font-weight:700; color: #1da867;" target="_blank">' . esc_html__( 'Get AffiliatePages Pro', 'affiliate-booster' ) . '</a>';
	array_unshift( $links, $get_pro );
	

	return $links;
}

