<?php
/**
 * Ajax of ProsCons.
 *
 * @package ProsCons
 */

namespace ProsCons;

defined( 'ABSPATH' ) || exit;

/**
 * Admin class
 *
 * @package ProsCons
 */
class ProsCons_Ajax {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Initialization.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function init() {
		// Initialize hooks.
		$this->init_hooks();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void
	 */
	private function init_hooks() {
		add_action( 'wp_ajax_get_block_settings', array( $this, 'get_block_settings' ) );
		add_action( 'wp_ajax_save_block_settings', array( $this, 'save_block_settings' ) );
		add_action( 'wp_ajax_get_customization_settings', array( $this, 'get_customization_settings' ) );
		add_action( 'wp_ajax_save_customization_settings', array( $this, 'save_customization_settings' ) );
	}


	/**
	 * Get Block Settings values.
	 */
	public function get_block_settings() {
		check_ajax_referer( 'prosandcons_ajax_nonce', 'security' );

		$data = affx_get_block_settings( true );
		wp_send_json_success( $data );
	}

	/**
	 * Save Block Settings values.
	 */
	public function save_block_settings() {
		check_ajax_referer( 'prosandcons_ajax_nonce', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You do not have permission to perform this action.', 'prosandcons' ) );
		}

		$data = array();

		if ( isset( $_POST['data'] ) ) {
			$raw_data     = stripslashes_deep( $_POST['data'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			$decoded_data = json_decode( $raw_data );

			if ( json_last_error() !== JSON_ERROR_NONE ) {
				wp_send_json_error( __( 'Invalid JSON data.', 'prosandcons' ) );
			}

			$data = affx_clean_vars( $decoded_data, true, 512, JSON_OBJECT_AS_ARRAY );
		}

		update_option( 'prosandcons_block_settings', wp_json_encode( $data ) );

		wp_send_json_success( __( 'Saved successfully.', 'prosandcons' ) );
	}

	/**
	 * Get Customization Settings values.
	 */
	public function get_customization_settings() {
		check_ajax_referer( 'prosandcons_ajax_nonce', 'security' );

		$data = affx_get_customization_settings( true );
		wp_send_json_success( $data );
	}

	/**
	 * Save Customization Settings values.
	 */
	public function save_customization_settings() {
		check_ajax_referer( 'prosandcons_ajax_nonce', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'You do not have permission to perform this action.', 'prosandcons' ) );
		}

		$data = array();

		if ( isset( $_POST['data'] ) ) {
			$raw_data     = stripslashes_deep( $_POST['data'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			$decoded_data = json_decode( $raw_data );

			if ( json_last_error() !== JSON_ERROR_NONE ) {
				wp_send_json_error( __( 'Invalid JSON data.', 'prosandcons' ) );
			}

			$data = affx_clean_vars( $decoded_data, true, 512, JSON_OBJECT_AS_ARRAY );
		}

		update_option( 'prosandcons_customization_settings', wp_json_encode( $data ) );

		wp_send_json_success( __( 'Saved successfully.', 'prosandcons' ) );
	}
}

new ProsCons_Ajax();
