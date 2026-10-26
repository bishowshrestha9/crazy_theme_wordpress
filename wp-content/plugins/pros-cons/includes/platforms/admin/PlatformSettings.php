<?php

namespace ProsCons\Platforms\Admin;

defined( 'ABSPATH' ) || exit;

use ProsCons\Platforms\PlatformConfig;
use ProsCons\Platforms\Api\Amazon\AmazonApiValidator;
use ProsCons\Platforms\Api\Amazon\AmazonCreatorApi;
use Exception;
use WP_REST_Request;

/**
 * Amazon settings handler
 *
 * @package ProsCons
 */
class PlatformSettings {
	use \ProsCons\Helpers\ResponseHelper;
	use \ProsCons\Helpers\OptionsHelper;

	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register WP api endpoints
	 *
	 * @return void
	 */
	public function register_routes(): void {
		register_rest_route(
			'prosandcons/v1/api',
			'/save-amazon-settings',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'save_settings' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			'prosandcons/v1/api',
			'/get-amazon-settings',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_settings' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			'prosandcons/v1/api',
			'/get-amazon-countries',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_countries' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			'prosandcons/v1/api',
			'/get-amazon-status',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_amazon_status' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			'prosandcons/v1/api',
			'/get-usage-stats',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_usage_stats' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		// eBay settings endpoints
		register_rest_route(
			'prosandcons/v1/api',
			'/save-ebay-settings',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'save_ebay_settings' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			'prosandcons/v1/api',
			'/get-ebay-settings',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_ebay_settings' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		// AliExpress settings endpoints
		register_rest_route(
			'prosandcons/v1/api',
			'/save-aliexpress-settings',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'save_aliexpress_settings' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			'prosandcons/v1/api',
			'/get-aliexpress-settings',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_aliexpress_settings' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		// Platform statuses
		register_rest_route(
			'prosandcons/v1/api',
			'/platform-statuses',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_platform_statuses' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	/**
	 * Sanitize settings fields
	 *
	 * @param array $params
	 * @return array
	 */
	private function sanitize_settings_fields( array $params ): array {
		$attributes = array();

		// API Type (creator, zero, pa_api)
		$attributes['api_type'] = isset( $params['api_type'] ) && in_array( $params['api_type'], array( 'creator', 'zero', 'pa_api' ), true )
			? $params['api_type']
			: 'creator';

		// Creator API credentials
		$attributes['creator_client_id']     = isset( $params['creator_client_id'] ) ? sanitize_text_field( $params['creator_client_id'] ) : '';
		$attributes['creator_client_secret'] = isset( $params['creator_client_secret'] ) ? sanitize_text_field( $params['creator_client_secret'] ) : '';

		// PA API credentials (legacy)
		$attributes['api_key']    = isset( $params['api_key'] ) ? sanitize_text_field( $params['api_key'] ) : '';
		$attributes['api_secret'] = isset( $params['api_secret'] ) ? sanitize_text_field( $params['api_secret'] ) : '';

		// Common fields
		$attributes['country']               = isset( $params['country'] ) && ! empty( $params['country'] ) ? sanitize_text_field( $params['country'] ) : '';
		$attributes['tracking_id']           = isset( $params['tracking_id'] ) && ! empty( $params['tracking_id'] ) ? sanitize_text_field( $params['tracking_id'] ) : '';
		$attributes['language']              = isset( $params['language'] ) && ! empty( $params['language'] ) ? sanitize_text_field( $params['language'] ) : '';
		$attributes['update_frequency']      = isset( $params['update_frequency'] ) ? sanitize_text_field( $params['update_frequency'] ) : '';
		$attributes['geolocation']           = isset( $params['geolocation'] ) ? (bool) $params['geolocation'] : false;
		$attributes['geolocation_countries'] = isset( $params['geolocation_countries'] ) && is_array( $params['geolocation_countries'] )
			? array_map( 'sanitize_text_field', $params['geolocation_countries'] )
			: array();

		// Validation based on API type
		switch ( $attributes['api_type'] ) {
			case 'creator':
				if ( empty( $attributes['creator_client_id'] ) ) {
					$this->send_json_error( __( 'Creator API Client ID is required', 'prosandcons' ) );
				}
				if ( empty( $attributes['creator_client_secret'] ) ) {
					$this->send_json_error( __( 'Creator API Client Secret is required', 'prosandcons' ) );
				}
				break;

			case 'zero':
				// Only tracking_id and country required (checked below)
				break;

			case 'pa_api':
				if ( empty( $attributes['api_key'] ) ) {
					$this->send_json_error( __( 'PA API Key is required', 'prosandcons' ) );
				}
				if ( empty( $attributes['api_secret'] ) ) {
					$this->send_json_error( __( 'PA API Secret is required', 'prosandcons' ) );
				}
				break;
		}

		// Common validations
		if ( empty( $attributes['tracking_id'] ) ) {
			$this->send_json_error( __( 'Tracking ID is required', 'prosandcons' ) );
		}

		if ( empty( $attributes['country'] ) ) {
			$this->send_json_error( __( 'Country is required', 'prosandcons' ) );
		}

		// Geolocation validation
		if ( $attributes['geolocation'] ) {
			if ( empty( $attributes['geolocation_countries'] ) ) {
				$this->send_json_error( __( 'Please setup at least one store for geolocation or disable it.', 'prosandcons' ) );
			} else {
				foreach ( $attributes['geolocation_countries'] as $tracking_id ) {
					if ( empty( $tracking_id ) ) {
						$this->send_json_error( __( 'Tracking ID is required for all geolocation stores.', 'prosandcons' ) );
					}
				}
			}
		}

		return $attributes;
	}

	/**
	 * Save Amazon settings from API response
	 *
	 * @param \WP_REST_Request $request
	 * @return void
	 */
	public function save_settings( \WP_REST_Request $request ): void {
		try {
			$params     = json_decode( $request->get_body(), true );
			$attributes = $this->sanitize_settings_fields( $params );

			$this->set_option( 'amazon_settings', $attributes );

			// Validate credentials based on API type
			if ( $attributes['api_type'] === 'creator' ) {
				// Get credential version based on country
				$config  = new PlatformConfig();
				$version = $config->get_creator_version_for_country( $attributes['country'] ?? 'us' );
				$country = $attributes['country'] ?? 'us';

				// Validate Creator API credentials with actual API test call
				$validation = AmazonCreatorApi::validate_credentials(
					$attributes['creator_client_id'],
					$attributes['creator_client_secret'],
					$version,
					$country,
					$attributes['tracking_id'] ?? ''
				);

				if ( ! $validation['valid'] ) {
					$this->set_option( 'amazon_activated', false );
					$this->send_json_error(
						__( 'Invalid Creator API credentials', 'prosandcons' ),
						array(
							'invalid_api_key' => true,
							'errors'          => array(
								array(
									'Code'    => $validation['error_code'] ?? 'InvalidCredentials',
									'Message' => $validation['error'],
								),
							),
						)
					);
				}

				// Check for AssociateNotEligible error even when credentials are valid
				if ( isset( $validation['error_code'] ) && $validation['error_code'] === 'AssociateNotEligible' ) {
					$this->set_option( 'amazon_activated', false );
					$this->send_json_error(
						__( 'Amazon Associate Account Not Eligible', 'prosandcons' ),
						array(
							'invalid_api_key' => true,
							'errors'          => array(
								array(
									'Code'    => 'AssociateNotEligible',
									'Message' => $validation['error'],
								),
							),
						)
					);
				}

				$this->set_option( 'amazon_activated', true );

			} elseif ( $attributes['api_type'] === 'zero' ) {
				// Zero API just needs tracking_id and country
				if ( ! empty( $attributes['tracking_id'] ) && ! empty( $attributes['country'] ) ) {
					$this->set_option( 'amazon_activated', true );
				} else {
					$this->set_option( 'amazon_activated', false );
				}
			} else { // pa_api
				// Validate PA API credentials
				$validator = new AmazonApiValidator();

				if ( ! $validator->is_credentials_valid() ) {
					$errors = $validator->get_errors();

					$this->set_option( 'amazon_activated', false );
					$this->send_json_error(
						__( 'Invalid credentials', 'prosandcons' ),
						array(
							'invalid_api_key' => true,
							'errors'          => $errors,
						)
					);
				}

				$this->set_option( 'amazon_activated', true );
			}

			$this->send_json_success( __( 'Settings saved successfully', 'prosandcons' ) );
		} catch ( Exception $e ) {
			$this->send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Get Amazon settings
	 *
	 * @return void
	 */
	public function get_settings(): void {
		try {
			$defaults = array(
				'api_type'              => 'creator',
				'creator_client_id'     => '',
				'creator_client_secret' => '',
				'api_key'               => '',
				'api_secret'            => '',
				'tracking_id'           => '',
				'country'               => 'us',
				'language'              => 'en_US',
				'update_frequency'      => 'daily',
				'geolocation'           => false,
				'geolocation_countries' => array(),
			);

			$settings = $this->get_option( 'amazon_settings', array() );

			// Migration: Convert old external_api boolean to api_type
			if ( ! isset( $settings['api_type'] ) && isset( $settings['external_api'] ) ) {
				$settings['api_type'] = $settings['external_api'] === true ? 'zero' : 'pa_api';
				unset( $settings['external_api'] );
				// Save migrated data
				$this->set_option( 'amazon_settings', $settings );
			}

			$settings = wp_parse_args( $settings, $defaults );

			$this->send_json_plain_success( $settings );

		} catch ( Exception $e ) {
			$this->send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Get Amazon countries through API request
	 *
	 * @return void
	 */
	public function get_countries(): void {
		try {
			$configs = new PlatformConfig();

			$this->send_json_plain_success( $configs->countries );

		} catch ( Exception $e ) {
			$this->send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Responses to API if Amazon is activated or not
	 *
	 * @return void
	 */
	public function get_amazon_status(): void {
		try {
			$configs = new PlatformConfig();
			$errors  = array();

			if ( ! $configs->is_active() ) {
				$validator = new AmazonApiValidator();
				$errors    = $validator->get_errors();

				if ( ! $validator->is_credentials_valid() ) {
					$this->send_json_plain_success(
						array(
							'activated'      => $configs->is_active(),
							'empty_settings' => $configs->is_settings_empty(),
							'errors'         => $errors,
						)
					);
				}
			}

			$this->send_json_plain_success(
				array(
					'activated'      => $configs->is_active(),
					'empty_settings' => $configs->is_settings_empty(),
					'errors'         => $errors,
				)
			);
		} catch ( Exception $e ) {
			$this->send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Get usage statistics with fallback values
	 *
	 * @return void
	 */
	public function get_usage_stats(): void {
		try {
			$license_key = '';

			if ( empty( $license_key ) ) {
				$this->send_json_plain_success(
					array(
						'show_usage' => true,
						'error'      => true,
						'message'    => __( 'No valid license found', 'prosandcons' ),
					)
				);
				return;
			}

			$response = wp_remote_post(
				PROSCONS_EXTERNAL_API_ENDPOINT . '/wp-json/prosandcons-proxy/v1/usage-stats',
				array(
					'headers'   => array(
						'Content-Type' => 'application/json',
					),
					'body'      => wp_json_encode(
						array(
							'license_key' => $license_key,
							'domain'      => $site_url,
						)
					),
					'timeout'   => 30,
					'sslverify' => false,
				)
			);

			if ( is_wp_error( $response ) ) {
				$this->send_json_plain_success(
					array(
						'show_usage' => true,
						'error'      => true,
						'message'    => __( 'Failed to fetch usage statistics', 'prosandcons' ),
					)
				);
				return;
			}

			$body = wp_remote_retrieve_body( $response );
			$data = json_decode( $body, true );

			if ( ! $data || ! is_array( $data ) ) {
				$this->send_json_plain_success(
					array(
						'show_usage' => true,
						'error'      => true,
						'message'    => __( 'Invalid response from usage API', 'prosandcons' ),
					)
				);
				return;
			}

			if ( isset( $data['code'] ) && $data['code'] === 'rate_limit_exceeded' ) {
				$this->send_json_plain_success(
					array(
						'show_usage'          => true,
						'rate_limit_exceeded' => true,
						'limit'               => $data['data']['limit'] ?? 0,
						'reset_at'            => $data['data']['reset_at'] ?? '',
						'reset_timestamp'     => $data['data']['reset_timestamp'] ?? 0,
					)
				);
				return;
			}

			// Ensure we have valid numeric values, with defaults that make sense
			$limit     = isset( $data['limit'] ) && is_numeric( $data['limit'] ) ? (int) $data['limit'] : 3000;
			$used      = isset( $data['used'] ) && is_numeric( $data['used'] ) ? (int) $data['used'] : 0;
			$remaining = isset( $data['remaining'] ) && is_numeric( $data['remaining'] ) ? (int) $data['remaining'] : $limit - $used;

			$reset_date = new \DateTime( 'first day of next month' );
			$reset_date->setTime( 0, 0, 0 );
			$fallback_reset_at        = $reset_date->format( 'Y-m-d\TH:i:s\Z' );
			$fallback_reset_timestamp = $reset_date->getTimestamp();

			$this->send_json_plain_success(
				array(
					'show_usage'      => true,
					'limit'           => $limit,
					'used'            => $used,
					'remaining'       => $remaining,
					'reset_at'        => $data['reset_at'] ?? $fallback_reset_at,
					'reset_timestamp' => $data['reset_timestamp'] ?? $fallback_reset_timestamp,
				)
			);

		} catch ( Exception $e ) {
			$this->send_json_error( $e->getMessage() );
		}
	}

	/**
	 * Save eBay settings
	 *
	 * @param WP_REST_Request $request
	 * @return void
	 */
	public function save_ebay_settings( WP_REST_Request $request ): void {
		$params   = json_decode( $request->get_body(), true );
		$existing = get_option( 'prosandcons_ebay_settings', array() );

		$submitted_secret = isset( $params['client_secret'] ) ? sanitize_text_field( $params['client_secret'] ) : '';
		$attributes       = array(
			'client_id'      => isset( $params['client_id'] ) ? sanitize_text_field( $params['client_id'] ) : '',
			'client_secret'  => '' !== $submitted_secret ? $submitted_secret : ( $existing['client_secret'] ?? '' ),
			'campaign_id'    => isset( $params['campaign_id'] ) ? sanitize_text_field( $params['campaign_id'] ) : '',
			'marketplace_id' => isset( $params['marketplace_id'] ) ? sanitize_text_field( $params['marketplace_id'] ) : 'EBAY_US',
		);

		update_option( 'prosandcons_ebay_settings', $attributes );

		$config     = new \ProsCons\Platforms\Api\Ebay\EbayConfig();
		$validation = $config->validate();

		$verified         = get_option( 'prosandcons_platform_verified', array() );
		$verified['ebay'] = $validation['success'];
		update_option( 'prosandcons_platform_verified', $verified );

		$this->send_json_plain_success(
			array(
				'message'   => $validation['message'],
				'connected' => $validation['success'],
			)
		);
	}

	/**
	 * Get eBay settings
	 *
	 * @return void
	 */
	public function get_ebay_settings(): void {
		$settings = get_option( 'prosandcons_ebay_settings', array() );

		$this->send_json_plain_success(
			array(
				'client_id'      => $settings['client_id'] ?? '',
				'campaign_id'    => $settings['campaign_id'] ?? '',
				'marketplace_id' => $settings['marketplace_id'] ?? 'EBAY_US',
				'has_secret'     => ! empty( $settings['client_secret'] ),
			)
		);
	}

	/**
	 * Save AliExpress settings
	 *
	 * @param WP_REST_Request $request
	 * @return void
	 */
	public function save_aliexpress_settings( WP_REST_Request $request ): void {
		$params   = json_decode( $request->get_body(), true );
		$existing = get_option( 'prosandcons_aliexpress_settings', array() );

		$submitted_secret = isset( $params['app_secret'] ) ? sanitize_text_field( $params['app_secret'] ) : '';
		$attributes       = array(
			'app_key'     => isset( $params['app_key'] ) ? sanitize_text_field( $params['app_key'] ) : '',
			'app_secret'  => '' !== $submitted_secret ? $submitted_secret : ( $existing['app_secret'] ?? '' ),
			'tracking_id' => isset( $params['tracking_id'] ) ? sanitize_text_field( $params['tracking_id'] ) : '',
		);

		update_option( 'prosandcons_aliexpress_settings', $attributes );

		// Validate by attempting a test search
		$connected = false;
		$message   = __( 'AliExpress settings saved', 'prosandcons' );

		if ( ! empty( $attributes['app_key'] ) && ! empty( $attributes['app_secret'] ) ) {
			$config = new \ProsCons\Platforms\Api\AliExpress\AliExpressConfig();
			$search = new \ProsCons_Pro\Platforms\Api\AliExpress\AliExpressSearch( 'test', 1, 1 );

			try {
				$results   = $search->get_products();
				$connected = ! empty( $results );
				$message   = $connected
					? __( 'AliExpress API connected successfully', 'prosandcons' )
					: __( 'Settings saved but no results returned. Check your API credentials.', 'prosandcons' );
			} catch ( \Exception $e ) {
				$message = $e->getMessage();
			}
		}

		$verified               = get_option( 'prosandcons_platform_verified', array() );
		$verified['aliexpress'] = $connected;
		update_option( 'prosandcons_platform_verified', $verified );

		$this->send_json_plain_success(
			array(
				'message'   => $message,
				'connected' => $connected,
			)
		);
	}

	/**
	 * Get AliExpress settings
	 *
	 * @return void
	 */
	public function get_aliexpress_settings(): void {
		$settings = get_option( 'prosandcons_aliexpress_settings', array() );

		$this->send_json_plain_success(
			array(
				'app_key'     => $settings['app_key'] ?? '',
				'tracking_id' => $settings['tracking_id'] ?? '',
				'has_secret'  => ! empty( $settings['app_secret'] ),
			)
		);
	}

	/**
	 * Get platform statuses
	 *
	 * @return void
	 */
	public function get_platform_statuses(): void {
		$amazon_config = new PlatformConfig();

		$ebay_settings       = get_option( 'prosandcons_ebay_settings', array() );
		$aliexpress_settings = get_option( 'prosandcons_aliexpress_settings', array() );
		$verified            = get_option( 'prosandcons_platform_verified', array() );

		$platforms = array(
			'amazon'     => ! $amazon_config->is_settings_empty(),
			'ebay'       => ! empty( $ebay_settings['client_id'] ),
			'aliexpress' => ! empty( $aliexpress_settings['app_key'] ),
		);

		$statuses = array();
		foreach ( $platforms as $id => $active ) {
			$statuses[ $id ] = array(
				'active'   => $active,
				'verified' => $verified[ $id ] ?? null,
			);
		}

		$this->send_json_plain_success( $statuses );
	}
}
