<?php

namespace ProsCons\BrokenLinks;

use ProsCons\Helpers\QueryBuilder;

defined( 'ABSPATH' ) || exit;

/**
 * Broken Link Scanner (free version)
 *
 * @package ProsCons
 */
class BrokenLinkScanner {

	/**
	 * Collect links from clicks and products tables into link_checks
	 *
	 * @return void
	 */
	public static function collect_links(): void {
		$click_links = QueryBuilder::table( 'clicks' )
			->select( 'url, url_hash, COUNT(*) as click_count, GROUP_CONCAT(DISTINCT page_id) as page_ids' )
			->group_by( 'url_hash' )
			->get();

		if ( ! empty( $click_links ) ) {
			foreach ( $click_links as $link ) {
				$url_hash = $link['url_hash'];

				$exists = QueryBuilder::table( 'link_checks' )
					->select( 'id' )
					->where( 'url_hash', '=', $url_hash )
					->get_value();

				if ( $exists ) {
					QueryBuilder::table( 'link_checks' )->update(
						array(
							'click_count' => (int) $link['click_count'],
							'page_ids'    => $link['page_ids'],
						),
						array( 'url_hash' => $url_hash ),
						array( '%d', '%s' ),
						array( '%s' )
					);
					continue;
				}

				$platform = \ProsCons\Analytics\AnalyticsHelper::detect_platform( $link['url'] );

				QueryBuilder::table( 'link_checks' )->insert(
					array(
						'url'         => $link['url'],
						'url_hash'    => $url_hash,
						'status'      => 'unchecked',
						'platform'    => $platform,
						'click_count' => (int) $link['click_count'],
						'page_ids'    => $link['page_ids'],
					),
					array( '%s', '%s', '%s', '%s', '%d', '%s' )
				);
			}
		}

		$product_links = QueryBuilder::table( 'products' )
			->select( 'url' )
			->where_raw( "url IS NOT NULL AND url != ''" )
			->get();

		if ( ! empty( $product_links ) ) {
			foreach ( $product_links as $product ) {
				$url      = $product['url'];
				$url_hash = md5( $url );

				$exists = QueryBuilder::table( 'link_checks' )
					->select( 'id' )
					->where( 'url_hash', '=', $url_hash )
					->get_value();

				if ( $exists ) {
					continue;
				}

				$platform = \ProsCons\Analytics\AnalyticsHelper::detect_platform( $url );

				QueryBuilder::table( 'link_checks' )->insert(
					array(
						'url'      => $url,
						'url_hash' => $url_hash,
						'status'   => 'unchecked',
						'platform' => $platform,
					),
					array( '%s', '%s', '%s', '%s' )
				);
			}
		}
	}

	/**
	 * Scan a batch of links
	 *
	 * @param int $batch_size Number of links to check.
	 * @return void
	 */
	public static function scan_batch( int $batch_size = 10 ): void {
		$cutoff = gmdate( 'Y-m-d H:i:s', time() - DAY_IN_SECONDS );

		$links = QueryBuilder::table( 'link_checks' )
			->select( 'id, url' )
			->where_raw( 'dismissed = 0' )
			->where_raw( '(last_checked IS NULL OR last_checked < \'' . esc_sql( $cutoff ) . '\')' )
			->order_by_raw( "CASE WHEN status = 'unchecked' THEN 0 ELSE 1 END, last_checked ASC" )
			->limit( $batch_size )
			->get();

		if ( empty( $links ) ) {
			return;
		}

		foreach ( $links as $link ) {
			$result = self::check_link( $link['url'] );

			QueryBuilder::table( 'link_checks' )->update(
				array(
					'status'        => $result['status'],
					'http_code'     => $result['http_code'],
					'status_label'  => $result['status_label'],
					'error_message' => $result['error_message'],
					'redirect_url'  => $result['redirect_url'],
					'last_checked'  => gmdate( 'Y-m-d H:i:s' ),
				),
				array( 'id' => $link['id'] ),
				array( '%s', '%d', '%s', '%s', '%s', '%s' ),
				array( '%d' )
			);
		}
	}

	/**
	 * Check a single link and return its status
	 *
	 * @param string $url URL to check.
	 * @return array
	 */
	public static function check_link( string $url ): array {
		$result = array(
			'status'        => 'healthy',
			'http_code'     => null,
			'status_label'  => 'Working',
			'error_message' => '',
			'redirect_url'  => '',
		);

		$parsed = wp_parse_url( $url );

		if ( empty( $parsed['host'] ) || ! preg_match( '/^[a-z0-9]([a-z0-9\-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9\-]*[a-z0-9])?)*$/i', $parsed['host'] ) ) {
			$result['status']        = 'broken';
			$result['status_label']  = 'Invalid URL';
			$result['error_message'] = 'URL has no valid hostname.';
			return $result;
		}

		$browser_ua   = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Safari/537.36';
		$request_args = array(
			'timeout'     => 15,
			'redirection' => 0,
			'sslverify'   => false,
			'user-agent'  => $browser_ua,
			'headers'     => array(
				'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
				'Accept-Language' => 'en-US,en;q=0.5',
			),
		);

		$response  = wp_remote_head( $url, $request_args );
		$http_code = is_wp_error( $response ) ? 0 : wp_remote_retrieve_response_code( $response );

		if ( 405 === $http_code || 0 === $http_code ) {
			$response = wp_remote_get( $url, $request_args );
		}

		if ( is_wp_error( $response ) ) {
			$error_message           = $response->get_error_message();
			$result['error_message'] = $error_message;

			if ( stripos( $error_message, 'timed out' ) !== false ) {
				$result['status']       = 'warning';
				$result['status_label'] = 'Slow Response';
			} elseif ( stripos( $error_message, 'ssl' ) !== false || stripos( $error_message, 'certificate' ) !== false ) {
				$result['status']       = 'warning';
				$result['status_label'] = 'Security Issue';
			} elseif ( stripos( $error_message, 'resolve' ) !== false || stripos( $error_message, 'dns' ) !== false ) {
				$result['status']       = 'broken';
				$result['status_label'] = 'Domain Not Found';
			} else {
				$result['status']       = 'broken';
				$result['status_label'] = "Can't Connect";
			}

			return $result;
		}

		$http_code           = wp_remote_retrieve_response_code( $response );
		$result['http_code'] = $http_code;

		if ( $http_code >= 200 && $http_code < 300 ) {
			$result['status']       = 'healthy';
			$result['status_label'] = 'Working';
		} elseif ( $http_code >= 300 && $http_code < 400 ) {
			$redirect_url           = wp_remote_retrieve_header( $response, 'location' );
			$result['redirect_url'] = $redirect_url;
			$result['status']       = 'healthy';
			$result['status_label'] = 'Working';

			$original_host = strtolower( str_replace( 'www.', '', wp_parse_url( $url, PHP_URL_HOST ) ?? '' ) );
			$redirect_host = strtolower( str_replace( 'www.', '', wp_parse_url( $redirect_url, PHP_URL_HOST ) ?? '' ) );

			if ( ! empty( $redirect_host ) && $original_host !== $redirect_host ) {
				$result['status']       = 'warning';
				$result['status_label'] = 'Redirected';
			}
		} elseif ( 404 === $http_code || 410 === $http_code || 403 === $http_code ) {
			$result['status']       = 'broken';
			$result['status_label'] = 'Page Not Found';
		} elseif ( 503 === $http_code ) {
			$result['status']       = 'warning';
			$result['status_label'] = 'Temporarily Unavailable';
		} elseif ( $http_code >= 500 ) {
			$result['status']       = 'broken';
			$result['status_label'] = 'Site Down';
		} elseif ( $http_code >= 400 ) {
			$result['status']       = 'broken';
			$result['status_label'] = 'Access Denied';
		} else {
			$result['status']       = 'healthy';
			$result['status_label'] = 'Working';
		}

		return $result;
	}

	/**
	 * Get summary counts
	 *
	 * @return array
	 */
	public static function get_summary(): array {
		$counts = QueryBuilder::table( 'link_checks' )
			->select(
				"COUNT(*) as total,
				SUM(CASE WHEN status = 'healthy' THEN 1 ELSE 0 END) as healthy,
				SUM(CASE WHEN status = 'broken' THEN 1 ELSE 0 END) as broken,
				SUM(CASE WHEN status = 'warning' THEN 1 ELSE 0 END) as warnings,
				SUM(CASE WHEN dismissed = 1 THEN 1 ELSE 0 END) as dismissed,
				MAX(last_checked) as last_checked"
			)
			->get_row();

		$healthy  = (int) ( $counts['healthy'] ?? 0 );
		$broken   = (int) ( $counts['broken'] ?? 0 );
		$warnings = (int) ( $counts['warnings'] ?? 0 );
		$checked  = $healthy + $broken + $warnings;

		return array(
			'total'        => (int) ( $counts['total'] ?? 0 ),
			'checked'      => $checked,
			'unchecked'    => (int) ( $counts['total'] ?? 0 ) - $checked,
			'healthy'      => $healthy,
			'broken'       => $broken,
			'warnings'     => $warnings,
			'dismissed'    => (int) ( $counts['dismissed'] ?? 0 ),
			'last_checked' => $counts['last_checked'] ?? null,
		);
	}
}
