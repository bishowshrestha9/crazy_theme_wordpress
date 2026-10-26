<?php

namespace ProsCons\Platforms\Api\Ebay;

defined( 'ABSPATH' ) || exit;

/**
 * EBay API configuration manager.
 * Reads settings from prosandcons_ebay_settings.
 *
 * @package ProsCons
 */
class EbayConfig {

	/**
	 * EBay API Client ID (App ID)
	 *
	 * @var string
	 */
	public $client_id;

	/**
	 * EBay API Client Secret (Cert ID)
	 *
	 * @var string
	 */
	public $client_secret;

	/**
	 * EBay Dev ID
	 *
	 * @var string
	 */
	public $campaign_id;

	/**
	 * EBay Marketplace ID
	 *
	 * @var string
	 */
	public $marketplace_id;

	/**
	 * Constructor
	 */
	public function __construct() {
		$settings = get_option( 'prosandcons_ebay_settings', array() );

		$this->client_id      = isset( $settings['client_id'] ) ? $settings['client_id'] : '';
		$this->client_secret  = isset( $settings['client_secret'] ) ? $settings['client_secret'] : '';
		$this->campaign_id    = isset( $settings['campaign_id'] ) ? $settings['campaign_id'] : '';
		$this->marketplace_id = isset( $settings['marketplace_id'] ) ? $settings['marketplace_id'] : 'EBAY_US';
	}

	/**
	 * Transient key for caching the access token
	 *
	 * @var string
	 */
	private const TOKEN_TRANSIENT_KEY = 'prosandcons_ebay_access_token';

	/**
	 * Get OAuth 2.0 access token via Client Credentials flow.
	 *
	 * @return string|false Access token or false on failure.
	 */
	public function get_access_token() {
		$cached = get_transient( self::TOKEN_TRANSIENT_KEY );
		if ( ! empty( $cached ) ) {
			return $cached;
		}

		if ( empty( $this->client_id ) || empty( $this->client_secret ) ) {
			return false;
		}

		$response = wp_remote_post(
			'https://api.ebay.com/identity/v1/oauth2/token',
			array(
				'headers' => array(
					'Content-Type'  => 'application/x-www-form-urlencoded',
					'Authorization' => 'Basic ' . base64_encode( $this->client_id . ':' . $this->client_secret ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Required for OAuth
				),
				'body'    => 'grant_type=client_credentials&scope=https%3A%2F%2Fapi.ebay.com%2Foauth%2Fapi_scope',
				'timeout' => 15,
			)
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! isset( $data['access_token'] ) ) {
			return false;
		}

		$expires = isset( $data['expires_in'] ) ? min( (int) $data['expires_in'] - 60, 7200 ) : 7200;
		set_transient( self::TOKEN_TRANSIENT_KEY, $data['access_token'], $expires );

		return $data['access_token'];
	}

	/**
	 * Check if required settings are empty.
	 *
	 * @return bool True if settings are missing.
	 */
	public function is_settings_empty(): bool {
		return empty( $this->client_id ) || empty( $this->client_secret );
	}

	/**
	 * Get the configured marketplace ID.
	 *
	 * @return string
	 */
	public function get_marketplace_id(): string {
		return $this->marketplace_id;
	}

	/**
	 * Validate the API key by making a test search.
	 *
	 * @return array Array with 'success' bool and 'message' string.
	 */
	public function validate(): array {
		if ( empty( $this->client_id ) || empty( $this->client_secret ) ) {
			return array(
				'success' => false,
				'message' => __( 'Client ID and Client Secret are required', 'prosandcons' ),
			);
		}

		// Validate by obtaining an OAuth token and making a test search
		delete_transient( self::TOKEN_TRANSIENT_KEY );
		$token = $this->get_access_token();

		if ( empty( $token ) ) {
			return array(
				'success' => false,
				'message' => __( 'Failed to authenticate with eBay. Check your Client ID and Client Secret.', 'prosandcons' ),
			);
		}

		$response = wp_remote_get(
			'https://api.ebay.com/buy/browse/v1/item_summary/search?q=test&limit=1',
			array(
				'headers' => array(
					'Authorization'           => 'Bearer ' . $token,
					'X-EBAY-C-MARKETPLACE-ID' => $this->marketplace_id,
				),
				'timeout' => 15,
			)
		);

		if ( is_wp_error( $response ) ) {
			return array(
				'success' => false,
				'message' => $response->get_error_message(),
			);
		}

		$status = wp_remote_retrieve_response_code( $response );

		if ( 200 === $status ) {
			return array(
				'success' => true,
				'message' => __( 'eBay API connected successfully', 'prosandcons' ),
			);
		}

		$data      = json_decode( wp_remote_retrieve_body( $response ), true );
		$error_msg = $data['errors'][0]['message'] ?? __( 'eBay API returned an error', 'prosandcons' );

		return array(
			'success' => false,
			'message' => $error_msg,
		);
	}
}
