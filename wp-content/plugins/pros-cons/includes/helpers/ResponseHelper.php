<?php

namespace ProsCons\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Response helper for API and AJAX responses
 *
 * @package ProsCons
 */
trait ResponseHelper {

	/**
	 * Send json success message to API and AJAX responses
	 *
	 * @param string $message
	 * @param array $data
	 * @return void
	 */
	protected function send_json_success( string $message, array $data = array() ): void {
		wp_send_json_success(
			array_merge(
				array( 'message' => $message ),
				$data
			)
		);
	}

	/**
	 * Send json success with plain data to API and AJAX responses
	 *
	 * @param array $data
	 * @return void
	 */
	protected function send_json_plain_success( array $data = array() ): void {
		wp_send_json_success( $data );
	}

	/**
	 * Send json error message to API and AJAX responses
	 *
	 * @param string $message
	 * @param array $data
	 * @return void
	 */
	protected function send_json_error( string $message, array $data = array() ): void {
		wp_send_json_error(
			array_merge(
				array( 'message' => $message ),
				$data
			)
		);
	}

	/**
	 * Send json error with plain data to API and AJAX responses
	 *
	 * @param array $data
	 * @return void
	 */
	protected function send_json_plain_error( array $data = array() ): void {
		wp_send_json_error( $data );
	}
}
