<?php

namespace ProsCons\Platforms;

defined( 'ABSPATH' ) || exit;

use ProsCons\Helpers\OptionsHelper;

/**
 * This class manages and output Amazon configurations
 *
 * @package ProsCons
 */
class PlatformConfig {

	use OptionsHelper;

	/**
	 * Amazon API key
	 *
	 * @var string
	 */
	public $api_key;

	/**
	 * Amazon API secret
	 *
	 * @var string
	 */
	public $api_secret;

	/**
	 * Amazon Tracking ID
	 *
	 * @var string
	 */
	public $tracking_id;

	/**
	 * Amazon Country code
	 *
	 * @var string
	 */
	public $country;

	/**
	 * Amazon Host
	 *
	 * @var string
	 */
	public $host;

	/**
	 * Amazon Region
	 *
	 * @var string
	 */
	public $region;

	/**
	 * Amazon Country Name/Title
	 *
	 * @var string
	 */
	public $country_name;

	/**
	 * Amazon Language
	 *
	 * @var string
	 */
	public $language;

	/**
	 * Amazon Update Frequency
	 *
	 * @var string
	 */
	public $update_frequency;

	/**
	 * Use External API instead of Amazon API
	 *
	 * @var bool
	 * @deprecated Use api_type instead
	 */
	public $use_external_api;

	/**
	 * API Type (creator, zero, pa_api)
	 *
	 * @var string
	 */
	public $api_type;

	/**
	 * Creator API Client ID
	 *
	 * @var string
	 */
	public $creator_client_id;

	/**
	 * Creator API Client Secret
	 *
	 * @var string
	 */
	public $creator_client_secret;

	/**
	 * Creator API Credential Version (auto-detected from country)
	 *
	 * @var string
	 */
	public $creator_version;

	/**
	 * Get Creator API version based on country
	 *
	 * @param string $country Country code
	 * @return string Version (3.1, 3.2, or 3.3)
	 */
	public function get_creator_version_for_country( string $country ): string {
		$na_countries = array( 'us', 'ca', 'mx', 'br' );
		if ( in_array( $country, $na_countries, true ) ) {
			return '3.1';
		}

		$fe_countries = array( 'jp', 'au', 'in', 'sg' );
		if ( in_array( $country, $fe_countries, true ) ) {
			return '3.3';
		}

		return '3.2';
	}

	/**
	 * Creator API Access Token (cached)
	 *
	 * @var string
	 */
	private $creator_access_token;

	/**
	 * Creator API Token Expiration
	 *
	 * @var int
	 */
	private $creator_token_expires;

	/**
	 * Geolocation enabled
	 *
	 * @var bool
	 */
	private $geolocation_enabled;

	/**
	 * Geolocation countries
	 *
	 * @var array
	 */
	private $geolocation_countries;

	/**
	 * Amazon Countries
	 *
	 * @var array
	 */
	public $countries = array(
		'au' => array(
			'label'           => 'Australia',
			'host'            => 'webservices.amazon.com.au',
			'region'          => 'us-west-2',
			'languages'       => array( 'en_AU' ),
			'currency'        => 'AUD',
			'currency_symbol' => 'A$',
			'domain'          => 'amazon.com.au',
		),
		'be' => array(
			'label'           => 'Belgium',
			'host'            => 'webservices.amazon.com.be',
			'region'          => 'eu-west-1',
			'languages'       => array( 'fr_BE', 'nl_BE', 'en_GB' ),
			'currency'        => 'EUR',
			'currency_symbol' => '€',
			'domain'          => 'amazon.com.be',
		),
		'br' => array(
			'label'           => 'Brazil',
			'host'            => 'webservices.amazon.com.br',
			'region'          => 'us-east-1',
			'languages'       => array( 'pt_BR' ),
			'currency'        => 'BRL',
			'currency_symbol' => 'R$',
			'domain'          => 'amazon.com.br',
		),
		'ca' => array(
			'label'           => 'Canada',
			'host'            => 'webservices.amazon.ca',
			'region'          => 'us-east-1',
			'languages'       => array( 'en_CA', 'fr_CA' ),
			'currency'        => 'CAD',
			'currency_symbol' => 'C$',
			'domain'          => 'amazon.ca',
		),
		'eg' => array(
			'label'           => 'Egypt',
			'host'            => 'webservices.amazon.eg',
			'region'          => 'eu-west-1',
			'languages'       => array( 'en_AE', 'ar_AE' ),
			'currency'        => 'EGP',
			'currency_symbol' => 'ج.م',
			'domain'          => 'amazon.eg',
		),
		'fr' => array(
			'label'           => 'France',
			'host'            => 'webservices.amazon.fr',
			'region'          => 'eu-west-1',
			'languages'       => array( 'fr_FR' ),
			'currency'        => 'EUR',
			'currency_symbol' => '€',
			'domain'          => 'amazon.fr',
		),
		'de' => array(
			'label'           => 'Germany',
			'host'            => 'webservices.amazon.de',
			'region'          => 'eu-west-1',
			'languages'       => array( 'de_DE', 'cs_CZ', 'en_GB', 'nl_NL', 'pl_PL', 'tr_TR' ),
			'currency'        => 'EUR',
			'currency_symbol' => '€',
			'domain'          => 'amazon.de',
		),
		'in' => array(
			'label'           => 'India',
			'host'            => 'webservices.amazon.in',
			'region'          => 'eu-west-1',
			'languages'       => array( 'en_IN', 'hi_IN', 'kn_IN', 'ml_IN', 'ta_IN', 'te_IN' ),
			'currency'        => 'INR',
			'currency_symbol' => '₹',
			'domain'          => 'amazon.in',
		),
		'it' => array(
			'label'           => 'Italy',
			'host'            => 'webservices.amazon.it',
			'region'          => 'eu-west-1',
			'languages'       => array( 'it_IT' ),
			'currency'        => 'EUR',
			'currency_symbol' => '€',
			'domain'          => 'amazon.it',
		),
		'jp' => array(
			'label'           => 'Japan',
			'host'            => 'webservices.amazon.co.jp',
			'region'          => 'eu-west-2',
			'languages'       => array( 'ja_JP', 'en_US', 'zh_CN' ),
			'currency'        => 'JPY',
			'currency_symbol' => '¥',
			'domain'          => 'amazon.co.jp',
		),
		'mx' => array(
			'label'           => 'Mexico',
			'host'            => 'webservices.amazon.com.mx',
			'region'          => 'us-east-1',
			'languages'       => array( 'es_MX' ),
			'currency'        => 'MXN',
			'currency_symbol' => 'MX$',
			'domain'          => 'amazon.com.mx',
		),
		'nl' => array(
			'label'           => 'Netherlands',
			'host'            => 'webservices.amazon.nl',
			'region'          => 'eu-west-1',
			'languages'       => array( 'nl_NL' ),
			'currency'        => 'EUR',
			'currency_symbol' => '€',
			'domain'          => 'amazon.nl',
		),
		'pl' => array(
			'label'           => 'Poland',
			'host'            => 'webservices.amazon.pl',
			'region'          => 'eu-west-1',
			'languages'       => array( 'pl_PL', 'en_GB' ),
			'currency'        => 'PLN',
			'currency_symbol' => 'zł',
			'domain'          => 'amazon.pl',
		),
		'sg' => array(
			'label'           => 'Singapore',
			'host'            => 'webservices.amazon.sg',
			'region'          => 'us-east-2',
			'languages'       => array( 'en_SG' ),
			'currency'        => 'SGD',
			'currency_symbol' => 'S$',
			'domain'          => 'amazon.sg',
		),
		'sa' => array(
			'label'           => 'Saudi Arabia',
			'host'            => 'webservices.amazon.sa',
			'region'          => 'eu-west-1',
			'languages'       => array( 'en_AE', 'ar_AE' ),
			'currency'        => 'SAR',
			'currency_symbol' => 'ر.س',
			'domain'          => 'amazon.sa',
		),
		'es' => array(
			'label'           => 'Spain',
			'host'            => 'webservices.amazon.es',
			'region'          => 'eu-west-1',
			'languages'       => array( 'es_ES' ),
			'currency'        => 'EUR',
			'currency_symbol' => '€',
			'domain'          => 'amazon.es',
		),
		'se' => array(
			'label'           => 'Sweden',
			'host'            => 'webservices.amazon.se',
			'region'          => 'eu-west-1',
			'languages'       => array( 'sv_SE' ),
			'currency'        => 'SEK',
			'currency_symbol' => 'kr',
			'domain'          => 'amazon.se',
		),
		'tr' => array(
			'label'           => 'Turkey',
			'host'            => 'webservices.amazon.com.tr',
			'region'          => 'eu-west-1',
			'languages'       => array( 'tr_TR' ),
			'currency'        => 'TRY',
			'currency_symbol' => '₺',
			'domain'          => 'amazon.com.tr',
		),
		'ae' => array(
			'label'           => 'United Arab Emirates',
			'host'            => 'webservices.amazon.ae',
			'region'          => 'eu-west-1',
			'languages'       => array( 'en_AE', 'ar_AE' ),
			'currency'        => 'AED',
			'currency_symbol' => 'د.إ',
			'domain'          => 'amazon.ae',
		),
		'uk' => array(
			'label'           => 'United Kingdom',
			'host'            => 'webservices.amazon.co.uk',
			'region'          => 'eu-west-1',
			'languages'       => array( 'en_GB' ),
			'currency'        => 'GBP',
			'currency_symbol' => '£',
			'domain'          => 'amazon.co.uk',
		),
		'us' => array(
			'label'           => 'United States',
			'host'            => 'webservices.amazon.com',
			'region'          => 'us-east-1',
			'languages'       => array( 'en_US', 'de_DE', 'es_US', 'ko_KR', 'pt_BR', 'zh_CN', 'zh_TW' ),
			'currency'        => 'USD',
			'currency_symbol' => '$',
			'domain'          => 'amazon.com',
		),
	);

	public function __construct() {
		$configs = $this->get_option( 'amazon_settings' );

		// Migration: Convert old external_api boolean to api_type
		if ( ! isset( $configs['api_type'] ) && isset( $configs['external_api'] ) ) {
			$configs['api_type'] = $configs['external_api'] === true ? 'zero' : 'pa_api';
			unset( $configs['external_api'] );
			// Save migrated data
			$this->set_option( 'amazon_settings', $configs );
		}

		$country_data = $this->get_country_data( $configs['country'] ?? 'us' );

		// PA API credentials (legacy)
		$this->api_key    = isset( $configs['api_key'] ) ? $configs['api_key'] : '';
		$this->api_secret = isset( $configs['api_secret'] ) ? $configs['api_secret'] : '';

		// Common fields
		$this->tracking_id      = isset( $configs['tracking_id'] ) ? $configs['tracking_id'] : '';
		$this->country          = isset( $configs['country'] ) ? $configs['country'] : 'us';
		$this->host             = $country_data['host'];
		$this->region           = $country_data['region'];
		$this->country_name     = $country_data['label'];
		$this->language         = isset( $configs['language'] ) ? $configs['language'] : 'en_US';
		$this->update_frequency = isset( $configs['update_frequency'] ) ? $configs['update_frequency'] : 'daily';

		// API type field
		$this->api_type = isset( $configs['api_type'] ) ? $configs['api_type'] : 'creator';

		// Creator API credentials
		$this->creator_client_id     = isset( $configs['creator_client_id'] ) ? $configs['creator_client_id'] : '';
		$this->creator_client_secret = isset( $configs['creator_client_secret'] ) ? $configs['creator_client_secret'] : '';
		$this->creator_version       = $this->get_creator_version_for_country( $this->country );
		$this->creator_access_token  = isset( $configs['creator_access_token'] ) ? $configs['creator_access_token'] : '';
		$this->creator_token_expires = isset( $configs['creator_token_expires'] ) ? (int) $configs['creator_token_expires'] : 0;

		// Legacy field for backward compatibility
		$this->use_external_api = ( $this->api_type === 'zero' );

		// Geolocation
		$this->geolocation_enabled   = isset( $configs['geolocation'] ) ? (bool) $configs['geolocation'] : false;
		$this->geolocation_countries = isset( $configs['geolocation_countries'] ) ? $configs['geolocation_countries'] : array();
	}

	/**
	 * Returns the country set in amazon config.
	 */
	public function get_country(): string {
		return $this->country;
	}

	/**
	 * Get country data: region, host, country name
	 *
	 * @param string $country
	 * @return array
	 */
	protected function get_country_data( string $country ): array {
		return isset( $this->countries[ $country ] ) ? $this->countries[ $country ] : $this->countries['us'];
	}

	/**
	 * Determines if Amazon connection is active
	 *
	 * @return boolean
	 */
	public function is_active(): bool {
		return $this->is_settings_empty() === false && $this->get_option( 'amazon_activated', false );
	}

	/**
	 * Determines if settings are empty
	 *
	 * @return boolean
	 */
	public function is_settings_empty(): bool {
		// Common required fields
		if ( empty( $this->country ) || empty( $this->tracking_id ) ) {
			return true;
		}

		// API-type specific required fields
		switch ( $this->api_type ) {
			case 'creator':
				return empty( $this->creator_client_id ) || empty( $this->creator_client_secret );
			case 'zero':
				return false; // Only tracking_id and country required (checked above)
			case 'pa_api':
				return empty( $this->api_key ) || empty( $this->api_secret );
			default:
				return true;
		}
	}

	/**
	 * Check if using Creator API
	 *
	 * @return boolean
	 */
	public function is_using_creator_api(): bool {
		return $this->api_type === 'creator';
	}

	/**
	 * Check if using Zero API
	 *
	 * @return boolean
	 */
	public function is_using_zero_api(): bool {
		return $this->api_type === 'zero';
	}

	/**
	 * Check if using PA API (legacy)
	 *
	 * @return boolean
	 */
	public function is_using_pa_api(): bool {
		return $this->api_type === 'pa_api';
	}

	/**
	 * Check if using external API instead of Amazon API
	 *
	 * @return boolean
	 * @deprecated Use is_using_zero_api() instead
	 */
	public function is_using_external_api(): bool {
		return $this->is_using_zero_api();
	}

	/**
	 * Check if using Amazon API directly
	 *
	 * @return boolean
	 * @deprecated Use is_using_pa_api() instead
	 */
	public function is_using_amazon_api(): bool {
		return $this->is_using_pa_api();
	}

	/**
	 * Static helper to check if settings indicate external API usage
	 *
	 * @param array $settings
	 * @return boolean
	 */
	public static function is_external_api_from_settings( array $settings ): bool {
		return isset( $settings['external_api'] ) && $settings['external_api'] === true;
	}

	/**
	 * Check if geolocation is enabled
	 *
	 * @return boolean
	 */
	public function is_geolocation_enabled(): bool {
		return $this->geolocation_enabled === true;
	}

	/**
	 * Get geolocation countries
	 *
	 * @return array
	 */
	public function get_geolocation_countries(): array {
		return $this->geolocation_countries;
	}

	/**
	 * Get Amazon language
	 *
	 * @return string
	 */
	public function get_language(): string {
		return $this->language;
	}

	/**
	 * Get currency symbol for current country
	 *
	 * @return string
	 */
	public function get_currency_symbol(): string {
		$country_data = $this->get_country_data( $this->country );
		return $country_data['currency_symbol'] ?? '$';
	}

	/**
	 * Get currency code for current country
	 *
	 * @return string
	 */
	public function get_currency_code(): string {
		$country_data = $this->get_country_data( $this->country );
		return $country_data['currency'] ?? 'USD';
	}

	/**
	 * Format price with correct currency symbol based on marketplace
	 *
	 * @param string $display_price The price string from API (e.g. "$29.99")
	 * @return string Correctly formatted price
	 */
	public function format_price_for_marketplace( string $display_price ): string {
		if ( empty( $display_price ) ) {
			return $display_price;
		}

		$correct_symbol = $this->get_currency_symbol();
		$numeric_value  = preg_replace( '/^[^\d]*/', '', $display_price );
		$numeric_value  = preg_replace( '/[^\d.,\s]*$/', '', $numeric_value );

		if ( empty( $numeric_value ) ) {
			return $display_price;
		}

		return $correct_symbol . $numeric_value;
	}

	/**
	 * Get currency symbol for specific country
	 *
	 * @param string $country
	 * @return string
	 */
	public function get_currency_symbol_for_country( string $country ): string {
		$country_data = $this->get_country_data( $country );
		return $country_data['currency_symbol'] ?? '$';
	}

	/**
	 * Transform Amazon URL to use country-specific domain
	 *
	 * @param string $url The original Amazon URL (e.g. from DetailPageURL)
	 * @param string $country The country code to use (optional)
	 * @return string URL with country-specific domain
	 */
	public function transform_url_for_marketplace( string $url, ?string $country = null ): string {
		if ( empty( $url ) ) {
			return $url;
		}

		$country = $country ?? $this->country;

		$target_domain = $this->countries[ $country ]['domain'] ?? 'amazon.com';

		return preg_replace(
			'/https?:\/\/(www\.)?amazon\.[a-z]+(?:\.[a-z]+)?/',
			'https://' . $target_domain,
			$url
		);
	}

	/**
	 * Get Creator API access token (with auto-refresh)
	 *
	 * @return string|false
	 */
	public function get_creator_access_token() {
		// Check if token exists and is not expired
		if ( ! empty( $this->creator_access_token ) && time() < $this->creator_token_expires ) {
			return $this->creator_access_token;
		}

		// Token expired or doesn't exist, fetch new one
		return $this->refresh_creator_token();
	}

	/**
	 * Refresh Creator API OAuth token
	 *
	 * @return string|false
	 */
	public function refresh_creator_token() {
		if ( empty( $this->creator_client_id ) || empty( $this->creator_client_secret ) ) {
			return false;
		}

		// Get the correct token endpoint for this credential version
		require_once PROSCONS_PLUGIN_DIR . '/includes/platforms/api/amazon/AmazonCreatorApi.php';
		$token_url = \ProsCons\Platforms\Api\Amazon\AmazonCreatorApi::get_token_endpoint( $this->creator_version );
		$is_lwa    = strpos( $this->creator_version, '3.' ) === 0; // v3.x uses Login with Amazon (LwA)

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
							'client_id'     => $this->creator_client_id,
							'client_secret' => $this->creator_client_secret,
							'scope'         => 'creatorsapi::default',
						)
					),
					'timeout' => 30,
				)
			);
		} else {
			// v2.x credentials use Basic Auth
			// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode -- Used for OAuth Basic Authentication header, not code obfuscation
			$auth_header = 'Basic ' . base64_encode( $this->creator_client_id . ':' . $this->creator_client_secret );

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
			return false;
		}

		$code = wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );

		$data = json_decode( $body, true );

		if ( ! isset( $data['access_token'] ) || ! isset( $data['expires_in'] ) ) {
			return false;
		}

		// Cache the token
		$this->creator_access_token  = $data['access_token'];
		$this->creator_token_expires = time() + $data['expires_in'] - 60; // 60 second buffer

		// Save to database
		$settings                          = $this->get_option( 'amazon_settings', array() );
		$settings['creator_access_token']  = $this->creator_access_token;
		$settings['creator_token_expires'] = $this->creator_token_expires;
		$this->set_option( 'amazon_settings', $settings );

		return $this->creator_access_token;
	}
}
