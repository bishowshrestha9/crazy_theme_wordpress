<?php

namespace ProsCons\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Options helper with isolated prefix
 *
 * @package ProsCons
 */
trait OptionsHelper {
	/**
	 * Prefix for option keys, it isolate the options from other plugins
	 *
	 * @var string
	 */
	private $prefix = 'prosandcons_';

	/**
	 * Get ProsCons option
	 *
	 * @param string $key
	 * @param int|bool|string|array|null $default
	 * @return int|bool|string|array|null
	 */
	private function get_option( string $key, $default = false ) {
		return get_option( $this->prefix . $key, $default );
	}

	/**
	 * Set ProsCons option
	 *
	 * @param string $key
	 * @param int|bool|string|array|null $value
	 * @return void
	 */
	private function set_option( string $key, $value ): void {
		update_option( $this->prefix . $key, $value );
	}

	/**
	 * Delete ProsCons option
	 *
	 * @param string $key
	 * @return void
	 */
	private function delete_option( string $key ): void {
		delete_option( $this->prefix . $key );
	}

	/**
	 * Set ProsCons transient option
	 *
	 * @param string $key
	 * @param int|bool|string|array|null $value
	 * @param integer $expiration
	 * @return void
	 */
	private function set_transient( string $key, $value = null, int $expiration = 0 ): void {
		set_transient( $this->prefix . $key, $value, $expiration );
	}

	/**
	 * Get ProsCons transient option
	 *
	 * @param string $key
	 * @param int|bool|string|array|null $default
	 * @return int|bool|string|array|null
	 */
	private function get_transient( string $key, $default = null ) {
		return get_transient( $this->prefix . $key, $default );
	}

	/**
	 * Delete ProsCons transient option
	 *
	 * @param string $key
	 * @return void
	 */
	private function delete_transient( string $key ): void {
		delete_transient( $this->prefix . $key );
	}
}
