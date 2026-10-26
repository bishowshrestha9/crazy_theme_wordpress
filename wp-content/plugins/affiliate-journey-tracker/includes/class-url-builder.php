<?php
namespace AffiliateJourneyTracker;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * URL Builder Class
 *
 * Resolves tracking origin slugs and handles Site B / 2nd page URL interception and parameter appending.
 */
class Url_Builder {

	/**
	 * Calculate the origin tracking slug for a given post and optional cookie state.
	 *
	 * BUSINESS RULES:
	 * 1. If homepage: returns empty string (homepage excluded).
	 * 2. If cookie slug provided / stored: returns journey origin slug.
	 * 3. If on content page (review, post, top listing): returns page slug.
	 *
	 * @param \WP_Post|int|null $post Optional post object or ID.
	 * @param string|null       $cookie_slug Optional cookie slug override.
	 * @return string Calculated origin slug.
	 */
	public static function resolve_origin( $post = null, ?string $cookie_slug = null ): string {
		if ( is_front_page() || is_home() ) {
			return '';
		}

		if ( ! empty( $cookie_slug ) ) {
			$clean = self::sanitize_slug_from_value( $cookie_slug );
			if ( ! empty( $clean ) ) {
				return $clean;
			}
		}

		if ( null === $post ) {
			$post = get_post();
			if ( ! $post && function_exists( 'get_queried_object' ) ) {
				$queried = get_queried_object();
				if ( $queried instanceof \WP_Post ) {
					$post = $queried;
				}
			}
		}
		if ( is_numeric( $post ) ) {
			$post = get_post( $post );
		}

		$current_slug = ( $post instanceof \WP_Post && ! empty( $post->post_name ) ) ? sanitize_title( $post->post_name ) : '';

		// 1. Check if post is a Listicle (always attributes to itself)
		if ( $post instanceof \WP_Post && is_listicle( $post ) ) {
			return $current_slug;
		}

		// 2. If post is a Review page, check for active LISTICLE-PRESERVED state
		$state = Cookie_Manager::get_attribution_state();
		if ( ! empty( $state ) && isset( $state['mode'] ) && 'LISTICLE-PRESERVED' === $state['mode'] ) {
			// If we arrived via Listicle Read More to this intended review page
			if ( ! empty( $state['intended_review_slug'] ) && ( empty( $current_slug ) || $state['intended_review_slug'] === $current_slug ) ) {
				if ( ! empty( $state['attribution_slug'] ) ) {
					$clean = self::sanitize_slug_from_value( $state['attribution_slug'] );
					if ( ! empty( $clean ) ) {
						return $clean;
					}
				}
			}
		}

		// 3. Normal Review page: attributes to current post
		if ( ! empty( $current_slug ) && 'home' !== $current_slug ) {
			return $current_slug;
		}

		// 4. Fallback stored state or stored slug
		$stored = Cookie_Manager::get_stored_slug();
		if ( ! empty( $stored ) ) {
			$clean = self::sanitize_slug_from_value( $stored );
			if ( ! empty( $clean ) ) {
				return $clean;
			}
		}

		// 5. Fallback HTTP Referer (useful during direct Pretty Link clicks)
		if ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
			$ref_url  = esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) );
			$ref_slug = self::sanitize_slug_from_value( $ref_url );
			if ( ! empty( $ref_slug ) ) {
				return $ref_slug;
			}
		}

		return '';
	}

	/**
	 * Helper: Extract clean slug from either a plain slug or full URL. Returns empty if home.
	 *
	 * @param string $val Value to parse.
	 * @return string Clean slug or empty string.
	 */
	public static function sanitize_slug_from_value( string $val ): string {
		$val = trim( $val );
		if ( empty( $val ) || 'home' === $val ) {
			return '';
		}

		if ( 0 === strpos( $val, 'http://' ) || 0 === strpos( $val, 'https://' ) ) {
			$path  = (string) wp_parse_url( $val, PHP_URL_PATH );
			$parts = array_values( array_filter( explode( '/', trim( $path, '/' ) ) ) );
			$slug  = ! empty( $parts ) ? end( $parts ) : '';
			if ( empty( $slug ) || 'home' === $slug ) {
				return '';
			}
			return sanitize_title( $slug );
		}

		$clean = sanitize_title( $val );
		return ( 'home' === $clean ) ? '' : $clean;
	}

	/**
	 * Determine if a given URL points to Site B target domain(s) or external domain or Pretty Link.
	 *
	 * @param string $url URL to test.
	 * @return bool True if URL points to Site B or an external target domain, false otherwise.
	 */
	public static function is_site_b_url( string $url ): bool {
		if ( empty( $url ) || strpos( $url, 'javascript:' ) === 0 || strpos( $url, '#' ) === 0 || strpos( $url, 'mailto:' ) === 0 || strpos( $url, 'tel:' ) === 0 ) {
			return false;
		}

		$parsed = wp_parse_url( $url );
		$host   = isset( $parsed['host'] ) ? strtolower( $parsed['host'] ) : '';
		$path   = isset( $parsed['path'] ) ? strtolower( $parsed['path'] ) : '';

		// If it has /go/ in path, it is a Pretty Link / redirect link
		if ( false !== strpos( $path, '/go/' ) ) {
			return true;
		}

		if ( empty( $host ) ) {
			return false;
		}

		// Retrieve configured Site B / Bridge target domain options.
		$raw_domains = get_option( 'affiliate_jt_site_b_domains', get_option( 'affiliate_jt_site_b_domain', "bestproductpicks.com\ngetbestsupplements.com\nsiteb.com\nlinks.theunbiasedreviews.org" ) );
		$domain_list = preg_split( '/[\s,]+/', trim( (string) $raw_domains ) );
		$has_targets = false;

		foreach ( (array) $domain_list as $target_domain ) {
			$target_domain = strtolower( trim( $target_domain ) );
			if ( empty( $target_domain ) ) {
				continue;
			}

			$has_targets = true;

			if ( $host === $target_domain || substr( $host, -strlen( '.' . $target_domain ) ) === '.' . $target_domain ) {
				return true;
			}
		}

		// Fallback: If no explicit target domains are configured, treat any non-local external domain as Site B target.
		$home_host = strtolower( (string) wp_parse_url( home_url(), PHP_URL_HOST ) );
		if ( ! empty( $home_host ) && $host !== $home_host && substr( $host, -strlen( '.' . $home_host ) ) !== '.' . $home_host ) {
			return true;
		}

		return false;
	}

	/**
	 * Append ONLY ref=<slug> to a target Site B / Pretty Link URL, strictly stripping all subid/origin/ref_page variations.
	 *
	 * @param string      $target_url Target Site B / Bridge URL.
	 * @param string|null $origin     Origin slug (optional, auto-resolved if null).
	 * @return string Modified URL with only ref=<slug> appended.
	 */
	public static function append_origin( string $target_url, ?string $origin = null ): string {
		if ( empty( $target_url ) ) {
			return $target_url;
		}

		if ( null === $origin || '' === $origin ) {
			$origin = self::resolve_origin();
		}

		$origin = self::sanitize_slug_from_value( (string) $origin );

		$parsed = wp_parse_url( $target_url );
		if ( empty( $parsed ) || ( empty( $parsed['host'] ) && empty( $parsed['path'] ) ) ) {
			return $target_url;
		}

		// Extract all existing query parameters
		$query_params = array();
		if ( ! empty( $parsed['query'] ) ) {
			parse_str( $parsed['query'], $query_params );
		}

		// STRIP ALL variations of tracking parameters: ref, ref_page, origin, subid, subid1..5, sub_id
		$keys_to_remove = array( 'ref', 'ref_page', 'origin', 'subid', 'subid1', 'subid2', 'subid3', 'subid4', 'subid5', 'sub_id', 'affiliate_origin' );
		foreach ( $keys_to_remove as $key ) {
			unset( $query_params[ $key ] );
		}
		foreach ( array_keys( $query_params ) as $key ) {
			$lower = strtolower( (string) $key );
			if ( 0 === strpos( $lower, 'subid' ) || 0 === strpos( $lower, 'ref_' ) || 0 === strpos( $lower, 'affiliate_' ) || 0 === strpos( $lower, 'origin' ) ) {
				unset( $query_params[ $key ] );
			}
		}

		// If origin is valid and not home, attach ONLY 'ref'
		if ( ! empty( $origin ) && 'home' !== $origin && ! is_front_page() && ! is_home() ) {
			$query_params['ref'] = $origin;
		}

		// Reconstruct clean URL
		$scheme   = isset( $parsed['scheme'] ) ? $parsed['scheme'] . '://' : '';
		$host     = isset( $parsed['host'] ) ? $parsed['host'] : '';
		$port     = isset( $parsed['port'] ) ? ':' . $parsed['port'] : '';
		$user     = isset( $parsed['user'] ) ? $parsed['user'] : '';
		$pass     = isset( $parsed['pass'] ) ? ':' . $parsed['pass'] : '';
		$pass     = ( $user || $pass ) ? "$pass@" : '';
		$path     = isset( $parsed['path'] ) ? $parsed['path'] : '';
		$query    = ! empty( $query_params ) ? '?' . http_build_query( $query_params, '', '&', PHP_QUERY_RFC3986 ) : '';
		$fragment = isset( $parsed['fragment'] ) ? '#' . $parsed['fragment'] : '';

		return "$scheme$user$pass$host$port$path$query$fragment";
	}

	/**
	 * Intercept Pretty Links target URL before redirect execution.
	 *
	 * @param string $target_url Target URL configured for Pretty Link.
	 * @param mixed  $link       Pretty Link object or ID (optional).
	 * @return string Modified destination URL if Site B link, or original.
	 */
	public static function intercept_pretty_link_target_url( string $target_url, $link = null ): string {
		if ( empty( $target_url ) ) {
			return $target_url;
		}

		$req_uri       = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/go/pretty-link';
		$requested_url = home_url( $req_uri );

		if ( self::is_site_b_url( $target_url ) ) {
			$origin = self::resolve_origin();
			$modified_url = self::append_origin( $target_url, $origin );

			Logger::log_event(
				array(
					'event_type'  => 'Pretty Link Redirect',
					'origin_slug' => $origin,
					'source_url'  => $requested_url,
					'target_url'  => $modified_url,
					'message'     => sprintf( 'Redirected %s -> %s (ref_page: %s)', $requested_url, $modified_url, $origin ),
				),
				'info'
			);

			return $modified_url;
		}

		return $target_url;
	}

	/**
	 * Filter post content to update links pointing to Site B.
	 *
	 * @param string $content HTML content.
	 * @return string Content with updated Buy button Site B URLs.
	 */
	public static function filter_content_links( string $content ): string {
		if ( empty( $content ) || is_admin() ) {
			return $content;
		}

		$origin = self::resolve_origin();

		return preg_replace_callback(
			'/<a\s+([^>]*href=["\']([^"\']+)["\'][^>]*)>/i',
			function ( $matches ) use ( $origin ) {
				$full_tag = $matches[0];
				$url      = $matches[2];

				if ( self::is_site_b_url( $url ) ) {
					$updated_url = self::append_origin( $url, $origin );
					return str_replace( $url, $updated_url, $full_tag );
				}

				return $full_tag;
			},
			$content
		);
	}

	/**
	 * Intercept outgoing redirects (e.g. wp_redirect) pointing to Site B.
	 *
	 * @param string $location Destination redirect URL.
	 * @param int    $status   HTTP status code.
	 * @return string Modified location URL if pointing to Site B.
	 */
	public static function intercept_outgoing_redirect( string $location, int $status = 302 ): string {
		if ( self::is_site_b_url( $location ) ) {
			return self::intercept_pretty_link_target_url( $location );
		}

		return $location;
	}
}
