<?php
namespace ProsCons\Notice;

defined( 'ABSPATH' ) || exit;

/**
 * This class handles all necessary functionalities for admin notices,
 * like enqueue assets to admin panel to handle notices from JS side,
 * initializes all notices, vice versa.
 *
 * @package ProsCons
 */
class NoticeHandler {

	public function __construct() {
		add_action( 'admin_init', array( $this, 'set_first_applied_time' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );

		new ReviewNotice();
		new CampaignNoticeHandler();
	}

	/**
	 * Set first initiation time
	 *
	 * @return void
	 */
	public function set_first_applied_time(): void {
		$first_initiated_at = get_option( 'prosandcons_notice_initiated' );

		if ( ! $first_initiated_at ) {
			update_option( 'prosandcons_notice_initiated', time() );
		}
	}

	/**
	 * Enqueue assets to handle notices in admin panel
	 *
	 * @return void
	 */
	public function enqueue_assets(): void {
		$notice_deps = include_once plugin_dir_path( PROSCONS_PLUGIN_FILE ) . '/build/noticesJS.asset.php';
		wp_enqueue_script( 'prosandcons-notices', plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'build/noticesJS.js', $notice_deps['dependencies'], $notice_deps['version'], true );

		wp_localize_script(
			'prosandcons-notices',
			'ProsConsNotice',
			array(
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'prosandcons_ajax_nonce' ),
			)
		);
	}
}

new NoticeHandler();
