<?php

namespace ProsCons\Platforms\Api;

use ProsCons\Platforms\PlatformConfig;
use ProsCons\Platforms\Api\Amazon\AmazonApiSignature;

defined( 'ABSPATH' ) || exit;

/**
 * Amazon API base class to create a new Amazon API request
 *
 * @package ProsCons
 */
abstract class PlatformApiBase {
	/**
	 * Amazon configuration data
	 *
	 * @var PlatformConfig
	 */
	protected $configs;

	public function __construct() {
		$this->configs = new PlatformConfig();
	}

	/**
	 * Get Amazon partner type
	 *
	 * @return string
	 */
	protected function get_partner_type(): string {
		return 'Associates';
	}

	/**
	 * Get Amazon API resources, for example "Images.Primary.Small"
	 *
	 * @return array
	 */
	protected function get_resources(): array {
		return array(
			'ItemInfo.Title',
			'ItemInfo.Features',
			'Offers.Listings.Price',
			'Offers.Listings.SavingBasis',
			'OffersV2.Listings.Condition',
			'OffersV2.Listings.Price',
			'OffersV2.Listings.Availability',
			'BrowseNodeInfo.WebsiteSalesRank',
			'Images.Primary.Small',
			'Images.Primary.Medium',
			'Images.Primary.Large',
			'Images.Variants.Small',
			'Images.Variants.Medium',
			'Images.Variants.Large',
		);
	}

	/**
	 * Get Amazon API search index
	 *
	 * @return string
	 */
	protected function get_search_index(): string {
		return 'All';
	}

	/**
	 * Get request timeout in seconds
	 *
	 * @return int
	 */
	protected function get_request_timeout(): int {
		return 30;
	}

	/**
	 * Get API headers
	 *
	 * @return array
	 */
	protected function get_headers(): array {
		return array(
			'content-encoding' => 'amz-1.0',
			'content-type'     => 'application/json; charset=utf-8',
			'host'             => $this->configs->host,
			'x-amz-target'     => 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1.' . $this->get_target(),
		);
	}

	/**
	 * Get API signature, inlcudes header with authenticatiob token
	 *
	 * @return AmazonApiSignature
	 */
	public function get_signature(): AmazonApiSignature {
		$signature = new AmazonApiSignature( $this->configs->api_key, $this->configs->api_secret );
		$signature->set_region_name( $this->configs->region );
		$signature->set_service_name( 'ProductAdvertisingAPI' );
		$signature->set_path( $this->get_path() );
		$signature->set_payload( $this->get_payload() );
		$signature->set_request_method( 'POST' );

		foreach ( $this->get_headers() as $header_name => $header_value ) {
			$signature->add_header( $header_name, $header_value );
		}

		return $signature;
	}

	/**
	 * Get API payload, contains json encoded body
	 *
	 * @return string
	 */
	protected function get_payload(): string {
		$default_attributes = array(
			'PartnerType'           => $this->get_partner_type(),
			'PartnerTag'            => $this->configs->tracking_id,
			'Resources'             => $this->get_resources(),
			'LanguagesOfPreference' => array( $this->configs->get_language() ),
		);

		$attributes = $this->get_params();
		$attributes = \wp_parse_args( $attributes, $default_attributes );
		$attributes = wp_json_encode( $attributes );

		return $attributes;
	}

	/**
	 * Get API endpoint
	 *
	 * @return string
	 */
	protected function get_endpoint(): string {
		if ( $this->configs->is_using_zero_api() ) {
			// Zero API proxy
			$end_point = sprintf(
				'%s/wp-json/prosandcons-proxy/v1/amazon-proxy%s',
				PROSCONS_EXTERNAL_API_ENDPOINT,
				$this->get_path()
			);
		} else {
			// PA API (legacy)
			$end_point = sprintf(
				'https://%s%s',
				$this->configs->host,
				$this->get_path()
			);
		}

		return $end_point;
	}

	/**
	 * Get results from API response
	 *
	 * @return bool|array
	 */
	public function get_result() {
		if ( $this->configs->is_settings_empty() ) {
			return false;
		}

		if ( $this->configs->is_using_zero_api() ) {
			// Zero API logic
			$license_key = '';
			$site_url    = site_url();

			$license_key = '';

			$headers = array(
				'Content-Type'  => 'application/json',
				'X-Tracking-ID' => $this->configs->tracking_id,
				'X-Country'     => $this->configs->country,
				'X-Language'    => $this->configs->language,
				'X-License-Key' => $license_key,
				'X-Domain'      => $site_url,
			);

			$request_args = array(
				'method'    => 'POST',
				'headers'   => $headers,
				'sslverify' => false,
				'timeout'   => $this->get_request_timeout(),
				'body'      => $this->get_payload(),
			);

			$response = \wp_remote_post( $this->get_endpoint(), $request_args );

			if ( \is_wp_error( $response ) ) {
				return false;
			}

			$response_code = \wp_remote_retrieve_response_code( $response );

			if ( $response_code !== 200 ) {
				return false;
			}
		} else {
			// PA API (legacy) logic
			$signature = $this->get_signature();

			if ( ! $signature ) {
				return false;
			}

			$response = \wp_remote_post(
				$this->get_endpoint(),
				array(
					'method'  => 'POST',
					'body'    => $this->get_payload(),
					'headers' => $signature->get_headers(),
				)
			);

			if ( \is_wp_error( $response ) ) {
				return false;
			}
		}

		$response = \wp_remote_retrieve_body( $response );
		$response = json_decode( $response, true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return false;
		}

		return $response;
	}

	/**
	 * Get Amazon API path to place after endpoint
	 *
	 * @return string
	 */
	abstract protected function get_path(): string;

	/**
	 * Get Amazon API parameters for search query
	 *
	 * @return array
	 */
	abstract protected function get_params(): array;

	/**
	 * Get Amazon API target type
	 *
	 * @return string
	 */
	abstract protected function get_target(): string;
}
