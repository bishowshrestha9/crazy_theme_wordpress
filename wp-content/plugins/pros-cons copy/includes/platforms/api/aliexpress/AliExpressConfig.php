<?php

namespace ProsCons\Platforms\Api\AliExpress;

defined( 'ABSPATH' ) || exit;

/**
 * AliExpress API configuration
 *
 * @package ProsCons
 */
class AliExpressConfig {

	/**
	 * AliExpress App Key
	 *
	 * @var string
	 */
	public $app_key;

	/**
	 * AliExpress App Secret
	 *
	 * @var string
	 */
	public $app_secret;

	/**
	 * AliExpress Tracking ID
	 *
	 * @var string
	 */
	public $tracking_id;

	public function __construct() {
		$settings = get_option( 'prosandcons_aliexpress_settings', array() );

		$this->app_key     = isset( $settings['app_key'] ) ? $settings['app_key'] : '';
		$this->app_secret  = isset( $settings['app_secret'] ) ? $settings['app_secret'] : '';
		$this->tracking_id = isset( $settings['tracking_id'] ) ? $settings['tracking_id'] : '';
	}

	/**
	 * Get AliExpress API base URL
	 *
	 * @return string
	 */
	public function get_api_url(): string {
		return 'https://api-sg.aliexpress.com/sync';
	}

	/**
	 * Check if required settings are empty
	 *
	 * @return bool
	 */
	public function is_settings_empty(): bool {
		return empty( $this->app_key );
	}
}
