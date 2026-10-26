<?php
/**
 * Public Class
 *
 * @package ProsCons
 */

namespace ProsCons;

defined( 'ABSPATH' ) || exit;

use ProsCons\Helpers\ProsCons_Helpers;

/**
 * AffilateX Public instance
 *
 * @since 1.0.0
 */
class ProsConsPublic {

	/**
	 * Class Constructor for public instance.
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Init Hooks
	 *
	 * @return void
	 */
	public function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp', array( $this, 'generate_assets' ), 99 );
		add_action( 'wp_head', array( $this, 'generate_stylesheet' ), 80 );
	}

	/**
	 * Generate Fonts Assets
	 *
	 * @return void
	 */
	public function generate_assets() {
		$m = new \AB_FONTS_MANAGER();
		$m->generate_assets();
	}

	/**
	 * Load Frontend assets.
	 *
	 * @return void
	 */
	public function enqueue_styles() {
		if ( ProsCons_Helpers::post_has_prosandcons_items() ) {
			wp_enqueue_style(
				'prosandcons-public', // Handle.
				plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'build/publicCSS.css',
				array(),
				PROSCONS_VERSION
			);
			$m = new \AB_FONTS_MANAGER();
			$m->load_dynamic_google_fonts();

			wp_enqueue_style(
				'fontawesome',
				plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'build/fontawesome.css',
				array(),
				PROSCONS_VERSION
			);

			wp_enqueue_script(
				'prosandcons-frontend',
				plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'build/frontendJs.js',
				array( 'jquery' ),
				PROSCONS_VERSION,
				false
			);

			if ( get_option( 'prosandcons_tracking_enabled', true ) ) {
				$page_id    = (int) get_the_ID();
				$page_title = get_the_title();

				if ( ! $page_id ) {
					$queried_id = get_queried_object_id();
					if ( $queried_id ) {
						$page_id    = (int) $queried_id;
						$page_title = $page_title ? $page_title : wp_get_document_title();
					}
				}

				wp_localize_script(
					'prosandcons-frontend',
					'affxAnalytics',
					array(
						'pageId'    => $page_id,
						'pageTitle' => $page_title ? $page_title : wp_get_document_title(),
						'endpoint'  => rest_url( 'prosandcons/v1/analytics/track' ),
					)
				);
			}

			if ( has_block( 'prosandcons/single-product' ) ) {
				wp_enqueue_style(
					'prosandcons-glide',
					plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'build/glideCSS.css',
					array(),
					PROSCONS_VERSION
				);
			}
		}
	}


	/**
	 * Generates stylesheet and appends in head tag.
	 *
	 * @since 0.0.1
	 */
	public function generate_stylesheet() {

		$m          = new \AB_FONTS_MANAGER();
		$stylesheet = $m::$stylesheet;

		if ( is_null( $stylesheet ) || '' === $stylesheet ) {
			return;
		}
		ob_start();
		?>
		<style id="prosandcons-styles-frontend"><?php echo $stylesheet; //phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped, WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
		<?php
		ob_end_flush();
	}
}
