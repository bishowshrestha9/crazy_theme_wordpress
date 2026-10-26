<?php

namespace ProsCons\Analytics;

defined( 'ABSPATH' ) || exit;

/**
 * Analytics helper with static utility methods
 *
 * @package ProsCons
 */
class AnalyticsHelper {

	/**
	 * Parse user agent string into device, browser, and OS
	 *
	 * @param string $ua User agent string.
	 * @return array{device: string, browser: string, os: string}
	 */
	public static function parse_user_agent( string $ua ): array {
		$device = 'desktop';
		if ( preg_match( '/iPad|Tablet/i', $ua ) ) {
			$device = 'tablet';
		} elseif ( preg_match( '/Mobile|Android|iPhone/i', $ua ) ) {
			$device = 'mobile';
		}

		$browser = 'other';
		if ( preg_match( '/Edg(e|\/)/i', $ua ) ) {
			$browser = 'Edge';
		} elseif ( preg_match( '/OPR|Opera/i', $ua ) ) {
			$browser = 'Opera';
		} elseif ( preg_match( '/Chrome/i', $ua ) ) {
			$browser = 'Chrome';
		} elseif ( preg_match( '/Firefox/i', $ua ) ) {
			$browser = 'Firefox';
		} elseif ( preg_match( '/Safari/i', $ua ) ) {
			$browser = 'Safari';
		}

		$os = 'other';
		if ( preg_match( '/Windows/i', $ua ) ) {
			$os = 'Windows';
		} elseif ( preg_match( '/iPhone|iPad|iPod/i', $ua ) ) {
			$os = 'iOS';
		} elseif ( preg_match( '/Macintosh|Mac OS/i', $ua ) ) {
			$os = 'Mac';
		} elseif ( preg_match( '/Android/i', $ua ) ) {
			$os = 'Android';
		} elseif ( preg_match( '/Linux/i', $ua ) ) {
			$os = 'Linux';
		}

		return array(
			'device'  => $device,
			'browser' => $browser,
			'os'      => $os,
		);
	}

	/**
	 * Detect affiliate platform from URL
	 *
	 * @param string $url The affiliate URL.
	 * @return string Platform identifier.
	 */
	public static function detect_platform( string $url ): string {
		if ( strpos( $url, 'amazon.' ) !== false ) {
			return 'amazon';
		}
		if ( strpos( $url, 'ebay.' ) !== false ) {
			return 'ebay';
		}
		if ( strpos( $url, 'aliexpress.' ) !== false ) {
			return 'aliexpress';
		}

		return 'other';
	}

	/**
	 * Get HTML tracking attributes for an affiliate link
	 *
	 * @param string $url          The affiliate URL.
	 * @param string $block_type   Block type identifier.
	 * @param string $element_type Element type identifier.
	 * @return string HTML attribute string.
	 */
	public static function get_tracking_attributes( string $url, string $block_type, string $element_type ): string {
		$platform = self::detect_platform( $url );

		return sprintf(
			'data-affx-track="1" data-affx-block="%s" data-affx-element="%s" data-affx-platform="%s"',
			esc_attr( $block_type ),
			esc_attr( $element_type ),
			esc_attr( $platform )
		);
	}
}
