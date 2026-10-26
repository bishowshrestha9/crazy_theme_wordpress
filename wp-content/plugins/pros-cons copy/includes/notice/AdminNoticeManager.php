<?php
/**
 * Admin notice manager for ProsCons
 *
 * @package ProsCons\Notice
 */

namespace ProsCons\Notice;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Class AdminNoticeManager
 *
 * @package ProsCons\Notice
 */
class AdminNoticeManager {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialize hooks
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'hide_non_prosandcons_notices' ) );
	}

	/**
	 * Hide non-ProsCons notices in plugin admin pages
	 *
	 * @return void
	 */
	public function hide_non_prosandcons_notices() {
		$screen = get_current_screen();

		// Return early if not on ProsCons admin page
		if ( ! $screen || false === strpos( $screen->base, 'prosandcons' ) ) {
			return;
		}

		$custom_css = '
			.wrap > .notice,
			.wrap > div.updated,
			.wrap > div.error,
			.wrap > div.warning,
			#wpbody-content > .notice,
			#wpbody-content > div.updated,
			#wpbody-content > div.error,
			#wpbody-content > div.warning {
				display: none !important;
			}
    	';

		wp_add_inline_style( 'prosandcons-admin-css', $custom_css );
	}
}
