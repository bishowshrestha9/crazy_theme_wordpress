<?php

namespace ProsCons\Platforms\Api\Amazon;

defined( 'ABSPATH' ) || exit;

/**
 * This class creates signature for Amazon API authentication
 *
 * @package ProsCons
 */
class AmazonApiSignature {
	/**
	 * API Key to access Amazon API
	 *
	 * @var string
	 */
	private $access_key_id = null;

	/**
	 * API secret to access Amazon API
	 *
	 * @var string
	 */
	private $secret_acceses_key = null;

	/**
	 * Path for endpoint
	 *
	 * @var string
	 */
	private $path = null;

	/**
	 * Region name to determinze API region
	 *
	 * @var string
	 */
	private $region_name = null;

	/**
	 * Service name
	 *
	 * @var string
	 */
	private $service_name = null;

	/**
	 * HTTP method to determine request type
	 *
	 * @var string
	 */
	private $http_method_name = null;

	/**
	 * Query parameters
	 *
	 * @var array
	 */
	private $query_parameters = array();

	/**
	 * Headers includes authentication token and other information
	 *
	 * @var array
	 */
	private $aws_headers = array();

	/**
	 * JSON encoded body
	 *
	 * @var string
	 */
	private $payload = '';

	/**
	 * HMAC algorithm
	 *
	 * @var string
	 */
	private $hmac_algorithm = 'AWS4-HMAC-SHA256';

	/**
	 * AWS4 request
	 *
	 * @var string
	 */
	private $aws4_request = 'aws4_request';

	/**
	 * Signed headers
	 *
	 * @var string
	 */
	private $str_signed_header = null;

	/**
	 * X-Amz-Date, used for header
	 *
	 * @var string
	 */
	private $xamz_date = null;

	/**
	 * Current date in required format
	 *
	 * @var string
	 */
	private $current_date = null;

	public function __construct( string $access_key_id, string $secret_acceses_key ) {
		$this->access_key_id      = $access_key_id;
		$this->secret_acceses_key = $secret_acceses_key;
		$this->xamz_date          = $this->get_timestamp();
		$this->current_date       = $this->get_date();
	}

	/**
	 * Set path for endpoint
	 *
	 * @param string $path
	 * @return void
	 */
	public function set_path( string $path ): void {
		$this->path = $path;
	}

	/**
	 * Set service name
	 *
	 * @param string $service_name
	 * @return void
	 */
	public function set_service_name( string $service_name ): void {
		$this->service_name = $service_name;
	}

	/**
	 * Set region name, determines API region
	 *
	 * @param string $region_name
	 * @return void
	 */
	public function set_region_name( string $region_name ): void {
		$this->region_name = $region_name;
	}

	/**
	 * Set payload, contains json encoded body
	 *
	 * @param string $payload
	 * @return void
	 */
	public function set_payload( string $payload ): void {
		$this->payload = $payload;
	}

	/**
	 * Set request method
	 *
	 * @param string $method
	 * @return void
	 */
	public function set_request_method( string $method ): void {
		$this->http_method_name = $method;
	}

	/**
	 * Add header item
	 *
	 * @param string $header_name
	 * @param string $header_value
	 * @return void
	 */
	public function add_header( string $header_name, string $header_value ): void {
		$this->aws_headers[ $header_name ] = $header_value;
	}

	/**
	 * Prepare canonical request
	 *
	 * @return string
	 */
	private function prepare_canonical_request(): string {
		$canonical_url  = '';
		$canonical_url .= $this->http_method_name . "\n";
		$canonical_url .= $this->path . "\n\n";
		$signed_headers = '';
		foreach ( $this->aws_headers as $key => $value ) {
			$signed_headers .= $key . ';';
			$canonical_url  .= $key . ':' . $value . "\n";
		}
		$canonical_url          .= "\n";
		$this->str_signed_header = substr( $signed_headers, 0, - 1 );
		$canonical_url          .= $this->str_signed_header . "\n";
		$canonical_url          .= $this->generate_hex( $this->payload );
		return $canonical_url;
	}

	/**
	 * Prepare string to create signature
	 *
	 * @param string $canonical_url
	 * @return string
	 */
	private function prepare_string_to_sign( string $canonical_url ): string {
		$string_to_sign  = '';
		$string_to_sign .= $this->hmac_algorithm . "\n";
		$string_to_sign .= $this->xamz_date . "\n";
		$string_to_sign .= $this->current_date . '/' . $this->region_name . '/' . $this->service_name . '/' . $this->aws4_request . "\n";
		$string_to_sign .= $this->generate_hex( $canonical_url );
		return $string_to_sign;
	}

	/**
	 * Calculate signature
	 *
	 * @param string $string_to_sign
	 * @return string
	 */
	private function calculate_signature( string $string_to_sign ): string {
		$signature_key     = $this->get_signature_key( $this->secret_acceses_key, $this->current_date, $this->region_name, $this->service_name );
		$signature         = hash_hmac( 'sha256', $string_to_sign, $signature_key, true );
		$str_hex_signature = strtolower( bin2hex( $signature ) );
		return $str_hex_signature;
	}

	/**
	 * Get array of headers
	 *
	 * @return array
	 */
	public function get_headers(): array {
		$this->aws_headers['x-amz-date'] = $this->xamz_date;
		ksort( $this->aws_headers );
		$canonical_url  = $this->prepare_canonical_request();
		$string_to_sign = $this->prepare_string_to_sign( $canonical_url );
		$signature      = $this->calculate_signature( $string_to_sign );
		if ( $signature ) {
			$this->aws_headers['Authorization'] = $this->build_authorization_string( $signature );
			return $this->aws_headers;
		}

		return array();
	}

	/**
	 * Build authorization string
	 *
	 * @param string $str_signature
	 * @return string
	 */
	private function build_authorization_string( string $str_signature ): string {
		return $this->hmac_algorithm . ' Credential=' . $this->access_key_id . '/' . $this->get_date() . '/' . $this->region_name . '/' . $this->service_name . '/' . $this->aws4_request . ',SignedHeaders=' . $this->str_signed_header . ',Signature=' . $str_signature;
	}

	/**
	 * Generate hex
	 *
	 * @param string $data
	 * @return string
	 */
	private function generate_hex( string $data ): string {
		return strtolower( bin2hex( hash( 'sha256', $data, true ) ) );
	}

	/**
	 * Get hash formatted signature key
	 *
	 * @param string $key
	 * @param string $date
	 * @param string $region_name
	 * @param string $service_name
	 * @return string
	 */
	private function get_signature_key( string $key, string $date, string $region_name, string $service_name ): string {
		$k_secret  = 'AWS4' . $key;
		$k_date    = hash_hmac( 'sha256', $date, $k_secret, true );
		$k_region  = hash_hmac( 'sha256', $region_name, $k_date, true );
		$k_service = hash_hmac( 'sha256', $service_name, $k_region, true );
		$k_signing = hash_hmac( 'sha256', $this->aws4_request, $k_service, true );

		return $k_signing;
	}

	/**
	 * Get current timestamp
	 *
	 * @return string
	 */
	private function get_timestamp(): string {
		return gmdate( 'Ymd\THis\Z' );
	}

	/**
	 * Get current date
	 *
	 * @return string
	 */
	private function get_date(): string {
		return gmdate( 'Ymd' );
	}
}
