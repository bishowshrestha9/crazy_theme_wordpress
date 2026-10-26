<?php

namespace ProsCons\Platforms\Api\Amazon;

defined( 'ABSPATH' ) || exit;

/**
 * Amazon Creator API Handler
 * Handles OAuth authentication and API endpoints for Creator API
 * Note: Cannot use official SDK due to PHP 8.1+ requirement (plugin supports PHP 7.4+)
 *
 * @package ProsCons
 */
class AmazonCreatorApi {

	/**
	 * Get Creator API endpoint base URL
	 * Always returns the same base URL for all marketplaces
	 *
	 * @return string
	 */
	public static function get_endpoint(): string {
		return 'https://creatorsapi.amazon/catalog/v1';
	}

	/**
	 * Get marketplace domain for x-marketplace header
	 *
	 * @param string $country Country code
	 * @return string
	 */
	public static function get_marketplace( string $country ): string {
		$marketplaces = array(
			'us' => 'www.amazon.com',
			'uk' => 'www.amazon.co.uk',
			'de' => 'www.amazon.de',
			'fr' => 'www.amazon.fr',
			'it' => 'www.amazon.it',
			'es' => 'www.amazon.es',
			'ca' => 'www.amazon.ca',
			'jp' => 'www.amazon.co.jp',
			'au' => 'www.amazon.com.au',
			'in' => 'www.amazon.in',
			'mx' => 'www.amazon.com.mx',
			'br' => 'www.amazon.com.br',
			'nl' => 'www.amazon.nl',
			'sg' => 'www.amazon.sg',
			'ae' => 'www.amazon.ae',
			'sa' => 'www.amazon.sa',
			'se' => 'www.amazon.se',
			'pl' => 'www.amazon.pl',
			'tr' => 'www.amazon.com.tr',
			'be' => 'www.amazon.com.be',
			'eg' => 'www.amazon.eg',
		);

		return isset( $marketplaces[ $country ] ) ? $marketplaces[ $country ] : 'www.amazon.com';
	}

	/**
	 * Get default language for marketplace validation
	 * Returns the primary language code for each country's marketplace
	 *
	 * @param string $country Country code
	 * @return string Language code (e.g. 'en_US', 'pt_BR')
	 */
	public static function get_marketplace_language( string $country ): string {
		$languages = array(
			'us' => 'en_US',
			'uk' => 'en_GB',
			'de' => 'de_DE',
			'fr' => 'fr_FR',
			'it' => 'it_IT',
			'es' => 'es_ES',
			'ca' => 'en_CA',
			'jp' => 'ja_JP',
			'au' => 'en_AU',
			'in' => 'en_IN',
			'mx' => 'es_MX',
			'br' => 'pt_BR',
			'nl' => 'nl_NL',
			'sg' => 'en_SG',
			'ae' => 'en_AE',
			'sa' => 'en_AE',
			'se' => 'sv_SE',
			'pl' => 'pl_PL',
			'tr' => 'tr_TR',
			'be' => 'nl_BE',
			'eg' => 'en_AE',
		);

		return isset( $languages[ $country ] ) ? $languages[ $country ] : 'en_US';
	}

	/**
	 * Get OAuth token endpoint based on credential version
	 *
	 * @param string $version Credential Version (2.1, 2.2, 2.3, 3.1, 3.2, or 3.3)
	 * @return string Token endpoint URL
	 */
	public static function get_token_endpoint( string $version ): string {
		$endpoints = array(
			'2.1' => 'https://creatorsapi.auth.us-east-1.amazoncognito.com/oauth2/token', // NA
			'2.2' => 'https://creatorsapi.auth.eu-south-2.amazoncognito.com/oauth2/token', // EU
			'2.3' => 'https://creatorsapi.auth.us-west-2.amazoncognito.com/oauth2/token', // FE
			'3.1' => 'https://api.amazon.com/auth/o2/token', // NA (LwA)
			'3.2' => 'https://api.amazon.co.uk/auth/o2/token', // EU (LwA)
			'3.3' => 'https://api.amazon.co.jp/auth/o2/token', // FE (LwA)
		);

		return isset( $endpoints[ $version ] ) ? $endpoints[ $version ] : $endpoints['2.1'];
	}

	/**
	 * Get OAuth access token
	 *
	 * @param string $client_id Credential ID
	 * @param string $client_secret Credential Secret
	 * @param string $version Credential Version (2.1, 2.2, 2.3, 3.1, 3.2, or 3.3)
	 * @return array ['success' => bool, 'token' => string|null, 'error' => string|null]
	 */
	private static function get_access_token( string $client_id, string $client_secret, string $version = '2.1' ): array {
		$token_url = self::get_token_endpoint( $version );
		$is_lwa    = strpos( $version, '3.' ) === 0; // v3.x uses Login with Amazon (LwA)

		if ( $is_lwa ) {
			// v3.x credentials use JSON body
			$response = wp_remote_post(
				$token_url,
				array(
					'headers' => array(
						'Content-Type' => 'application/json',
					),
					'body'    => wp_json_encode(
						array(
							'grant_type'    => 'client_credentials',
							'client_id'     => $client_id,
							'client_secret' => $client_secret,
							'scope'         => 'creatorsapi::default',
						)
					),
					'timeout' => 30,
				)
			);
		} else {
			// v2.x credentials use Basic Auth
			// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Used for OAuth Basic Authentication header, not code obfuscation
			$auth_header = 'Basic ' . base64_encode( $client_id . ':' . $client_secret );

			$response = wp_remote_post(
				$token_url,
				array(
					'headers' => array(
						'Content-Type'  => 'application/x-www-form-urlencoded',
						'Authorization' => $auth_header,
					),
					'body'    => array(
						'grant_type' => 'client_credentials',
						'scope'      => 'creatorsapi/default',
					),
					'timeout' => 30,
				)
			);
		}

		if ( is_wp_error( $response ) ) {
			return array(
				'success' => false,
				'token'   => null,
				'error'   => $response->get_error_message(),
			);
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );

		if ( $code !== 200 ) {
			$data          = json_decode( $body, true );
			$error_message = 'Invalid credentials';

			if ( isset( $data['error_description'] ) ) {
				$error_message = $data['error_description'];
			} elseif ( isset( $data['error'] ) ) {
				$error_message = $data['error'];
			}

			if ( $code === 400 || $code === 401 ) {
				$error_message .= ' (Check Credential ID and Secret)';
			}

			return array(
				'success' => false,
				'token'   => null,
				'error'   => $error_message,
			);
		}

		// Verify we got a valid token
		$data = json_decode( $body, true );
		if ( ! isset( $data['access_token'] ) ) {
			return array(
				'success' => false,
				'token'   => null,
				'error'   => 'OAuth response missing access token',
			);
		}

		return array(
			'success' => true,
			'token'   => $data['access_token'],
			'error'   => null,
		);
	}

	/**
	 * Validate Creator API credentials by making a test API call
	 * This checks both OAuth authentication AND account eligibility
	 *
	 * @param string $client_id Credential ID
	 * @param string $client_secret Credential Secret
	 * @param string $version Credential Version (2.1, 2.2, 2.3, 3.1, 3.2, or 3.3)
	 * @param string $country Country code for marketplace (default: 'us')
	 * @param string $tracking_id Partner/Tracking ID (optional, used for test API call)
	 * @return array ['valid' => bool, 'error' => string|null, 'error_code' => string|null]
	 */
	public static function validate_credentials( string $client_id, string $client_secret, string $version = '2.1', string $country = 'us', string $tracking_id = '' ): array {
		// Step 1: Get OAuth token
		$token_result = self::get_access_token( $client_id, $client_secret, $version );

		if ( ! $token_result['success'] ) {
			return array(
				'valid'      => false,
				'error'      => $token_result['error'],
				'error_code' => 'InvalidCredentials',
			);
		}

		$access_token = $token_result['token'];

		// Step 2: Make a test API call to verify account eligibility
		$endpoint    = self::get_endpoint() . '/searchItems';
		$marketplace = self::get_marketplace( $country );
		$language    = self::get_marketplace_language( $country );

		$payload_data = array(
			'keywords'              => 'test',
			'itemCount'             => 1,
			'marketplace'           => $marketplace,
			'resources'             => array( 'itemInfo.title' ),
			'languagesOfPreference' => array( $language ),
		);

		if ( ! empty( $tracking_id ) ) {
			$payload_data['partnerTag'] = $tracking_id;
		}

		$payload = wp_json_encode( $payload_data );

		$headers = array(
			'Authorization' => 'Bearer ' . $access_token . ', Version ' . $version,
			'Content-Type'  => 'application/json',
			'x-marketplace' => $marketplace,
		);

		$response = wp_remote_post(
			$endpoint,
			array(
				'headers' => $headers,
				'body'    => $payload,
				'timeout' => 30,
			)
		);

		if ( is_wp_error( $response ) ) {
			return array(
				'valid'      => false,
				'error'      => $response->get_error_message(),
				'error_code' => 'RequestError',
			);
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );

		$data = json_decode( $body, true );

		if ( isset( $data['reason'] ) || isset( $data['type'] ) ) {
			$error_code = $data['reason'] ?? $data['type'] ?? 'UnknownError';
			$error_msg  = $data['message'] ?? 'Unknown error occurred';

			if ( $error_code === 'AssociateNotEligible' ) {
				return array(
					'valid'      => true,
					'error'      => $error_msg . ' Learn more: https://prosandconsblocks.com/docs/how-to-fix-amazon-api-associatenoteligible-error/',
					'error_code' => $error_code,
				);
			}

			return array(
				'valid'      => false,
				'error'      => $error_msg,
				'error_code' => $error_code,
			);
		} elseif ( isset( $data['errors'] ) && is_array( $data['errors'] ) && count( $data['errors'] ) > 0 ) {
			$error      = $data['errors'][0];
			$error_code = $error['code'] ?? 'UnknownError';
			$error_msg  = $error['message'] ?? 'Unknown error occurred';

			return array(
				'valid'      => false,
				'error'      => $error_msg,
				'error_code' => $error_code,
			);
		}

		if ( $code === 200 && isset( $data['searchResult'] ) ) {
			return array(
				'valid'      => true,
				'error'      => null,
				'error_code' => null,
			);
		}

		return array(
			'valid'      => false,
			'error'      => 'Unexpected API response format',
			'error_code' => 'UnexpectedResponse',
		);
	}
}
