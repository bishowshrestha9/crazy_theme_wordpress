<?php
/**
 * Plugin Name:       Affiliate Journey Tracker
 * Description:       Tracks affiliate click origins from Top Listings through Review pages to Buy Buttons, appending origin tracking to Site B URLs.
 * Version:           1.0.0
 * Author:            Bishow Shrestha
 * Text Domain:       affiliate-journey-tracker
 * Domain Path:       /languages
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @package           AffiliateJourneyTracker
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Global Helper: Check if a post is a Listicle / Top Listing based on WordPress category "Listicle".
 *
 * BUSINESS RULE & SPECIFICATION:
 * A post is identified as a Listicle if it has the WordPress category "Listicle".
 * A post may belong to multiple categories; if one of them is "Listicle", it is a Listicle.
 *
 * @param \WP_Post|int|null $post Optional. Post object or ID. Defaults to global $post.
 * @return bool True if post is a listicle, false otherwise.
 */
if ( ! function_exists( 'is_listicle' ) ) {
	function is_listicle( $post = null ) {
		if ( null === $post ) {
			$post = get_post();
		}
		if ( is_numeric( $post ) ) {
			$post = get_post( $post );
		}
		if ( ! $post instanceof \WP_Post ) {
			return false;
		}

		// 1. Primary rule: Check if post has WordPress category 'Listicle'
		if ( function_exists( 'has_category' ) && ( has_category( 'listicle', $post ) || has_category( 'Listicle', $post ) ) ) {
			return true;
		}

		if ( function_exists( 'get_the_category' ) ) {
			$categories = get_the_category( $post->ID );
			if ( ! empty( $categories ) && is_array( $categories ) ) {
				foreach ( $categories as $cat ) {
					if ( 0 === strcasecmp( $cat->slug, 'listicle' ) || 0 === strcasecmp( $cat->name, 'listicle' ) ) {
						return true;
					}
				}
			}
		}

		// 2. Compatibility fallback: check post meta if set
		$content_type = get_post_meta( $post->ID, 'affiliate_content_type', true );
		if ( 'top_listing' === $content_type || 'listicle' === $content_type ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'is_top_listing' ) ) {
	function is_top_listing( $post = null ) {
		return is_listicle( $post );
	}
}

// Plugin Constants.
define( 'AFFILIATE_JT_VERSION', '1.0.2' );
define( 'AFFILIATE_JT_FILE', __FILE__ );
define( 'AFFILIATE_JT_PATH', plugin_dir_path( __FILE__ ) );
define( 'AFFILIATE_JT_URL', plugin_dir_url( __FILE__ ) );

/**
 * Standard PSR-4 Autoloader for AffiliateJourneyTracker namespace.
 *
 * @param string $class Class name to load.
 */
spl_autoload_register( function ( $class ) {
	$prefix   = 'AffiliateJourneyTracker\\';
	$base_dir = AFFILIATE_JT_PATH . 'includes/';

	$len = strlen( $prefix );
	if ( 0 !== strncmp( $prefix, $class, $len ) ) {
		return;
	}

	$relative_class = substr( $class, $len );

	// Convert Class_Name or ClassName (CamelCase) to class-class-name.php format for WP standard
	$class_parts = explode( '\\', $relative_class );
	$last_part   = array_pop( $class_parts );

	$kebab     = strtolower( preg_replace( '/(?<!^)[A-Z]/', '-$0', str_replace( '_', '-', $last_part ) ) );
	$kebab     = preg_replace( '/-+/', '-', $kebab );
	$file_name = 'class-' . $kebab . '.php';

	$sub_path = '';
	if ( ! empty( $class_parts ) ) {
		$sub_path = strtolower( implode( '/', $class_parts ) ) . '/';
	}

	$file = $base_dir . $sub_path . $file_name;

	if ( file_exists( $file ) ) {
		require_once $file;
	}
} );

/**
 * Initialize the core plugin instance on plugins_loaded.
 */
function affiliate_journey_tracker_init() {
	\AffiliateJourneyTracker\Plugin::get_instance();
}
add_action( 'plugins_loaded', 'affiliate_journey_tracker_init' );

/**
 * Plugin activation callback.
 */
register_activation_hook( __FILE__, function () {
	// Set default options if not exists.
	if ( false === get_option( 'affiliate_jt_cookie_expiry' ) ) {
		update_option( 'affiliate_jt_cookie_expiry', 24 );
	}
	if ( false === get_option( 'affiliate_jt_site_b_domain' ) ) {
		update_option( 'affiliate_jt_site_b_domain', 'siteb.com' );
	}
	if ( false === get_option( 'affiliate_jt_enable_debug' ) ) {
		update_option( 'affiliate_jt_enable_debug', 1 );
	}
	if ( false === get_option( 'affiliate_jt_enable_logs' ) ) {
		update_option( 'affiliate_jt_enable_logs', 1 );
	}
} );

/**
 * Plugin deactivation callback.
 */
register_deactivation_hook( __FILE__, function () {
	// Clean up transient or temp data if required (preserve settings for safety).
} );
