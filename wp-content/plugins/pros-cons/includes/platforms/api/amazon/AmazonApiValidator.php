<?php

namespace ProsCons\Platforms\Api\Amazon;

defined( 'ABSPATH' ) || exit;

use ProsCons\Platforms\Api\PlatformApiBase;

/**
 * Amazon API credential validator
 *
 * @package ProsCons
 */
class AmazonApiValidator extends PlatformApiBase {

	/**
	 * Stores API response
	 *
	 * @var Array|Boolean
	 */
	private $result;

	protected function get_path(): string {
		return '/paapi5/searchitems';
	}

	protected function get_params(): array {
		return array(
			'Keywords' => 'Shoes',
		);
	}

	protected function get_target(): string {
		return 'SearchItems';
	}

	/**
	 * Check if credentials are valid, checks API keys and secret
	 *
	 * @return boolean
	 */
	public function is_credentials_valid(): bool {
		$this->result = $this->get_result();

		if ( $this->result === false ) {
			return false;
		}

		if ( isset( $this->result['Errors'] ) && count( $this->result['Errors'] ) > 0 ) {
			return false;
		}

		return true;
	}

	/**
	 * Get errors from API response
	 *
	 * @return array|bool
	 */
	public function get_errors() {
		// First check if result exists and has Errors key
		if ( ! isset( $this->result ) || ! isset( $this->result['Errors'] ) ) {
			return false;
		}

		// Then check if Errors is an array and has items
		if ( is_array( $this->result['Errors'] ) && count( $this->result['Errors'] ) === 0 ) {
			return false;
		}

		return isset( $this->result['Errors'] ) ? $this->result['Errors'] : array();
	}
}
