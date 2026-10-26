<?php
namespace AffiliateJourneyTracker;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Cookie Manager Class
 *
 * Manages creation, reading, and lifecycle of the `ref_page` and `affiliate_jt_state` cookies.
 */
class Cookie_Manager {

	/**
	 * Primary Cookie name.
	 */
	public const COOKIE_NAME = 'ref_page';

	/**
	 * State Cookie name.
	 */
	public const STATE_COOKIE_NAME = 'affiliate_jt_state';

	/**
	 * Secondary Cookie name (for backward compatibility).
	 */
	public const LEGACY_COOKIE_NAME = 'affiliate_origin';

	/**
	 * Initialize hooks for server-side cookie management.
	 */
	public function init(): void {
		add_action( 'template_redirect', array( $this, 'handle_page_visit' ) );
	}

	/**
	 * Automatically handles page visits: sets cookie on content pages, clears on homepage.
	 */
	public function handle_page_visit(): void {
		if ( is_admin() || ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) ) {
			return;
		}

		// Homepage is excluded from tracking - clear cookie if landing on homepage
		if ( is_front_page() || is_home() ) {
			$this->clear_cookie();
			return;
		}

		$post = get_post();
		if ( ! $post instanceof \WP_Post || empty( $post->post_name ) ) {
			return;
		}

		$current_slug = sanitize_title( $post->post_name );
		$is_listicle  = is_listicle( $post );

		// Check if we have an active LISTICLE-PRESERVED state
		$state = self::get_attribution_state();

		if ( ! empty( $state ) && isset( $state['mode'] ) && 'LISTICLE-PRESERVED' === $state['mode'] ) {
			// If current post is the intended review page, preserve the listicle attribution
			if ( ! empty( $state['intended_review_slug'] ) && $state['intended_review_slug'] === $current_slug ) {
				$this->set_cookie( $post->ID, $state['attribution_slug'], $state );
				return;
			}
		}

		// If on a Listicle, set state to NORMAL with listicle slug
		if ( $is_listicle ) {
			$new_state = array(
				'mode'                 => 'NORMAL',
				'attribution_slug'     => $current_slug,
				'listicle_slug'        => $current_slug,
				'intended_review_slug' => null,
				'timestamp'            => time(),
			);
			$this->set_cookie( $post->ID, $current_slug, $new_state );
			return;
		}

		// If direct landing on a product review (or no preserved listicle state for this review)
		$new_state = array(
			'mode'                 => 'NORMAL',
			'attribution_slug'     => $current_slug,
			'listicle_slug'        => null,
			'intended_review_slug' => null,
			'timestamp'            => time(),
		);
		$this->set_cookie( $post->ID, $current_slug, $new_state );
	}

	/**
	 * Set the origin cookies on the visitor's browser.
	 *
	 * @param int        $listing_id   Post ID.
	 * @param string     $listing_slug Origin slug.
	 * @param array|null $state        Optional attribution state array.
	 * @return bool True if cookie headers were sent.
	 */
	public function set_cookie( int $listing_id, string $listing_slug, ?array $state = null ): bool {
		if ( empty( $listing_slug ) || headers_sent() ) {
			return false;
		}

		$expiry_hours = (int) get_option( 'affiliate_jt_cookie_expiry', 24 );
		if ( $expiry_hours <= 0 ) {
			$expiry_hours = 24;
		}

		$expires       = time() + ( $expiry_hours * HOUR_IN_SECONDS );
		$cookie_path   = defined( 'COOKIEPATH' ) && COOKIEPATH ? COOKIEPATH : '/';
		$cookie_domain = defined( 'COOKIE_DOMAIN' ) && COOKIE_DOMAIN ? COOKIE_DOMAIN : '';
		$is_secure     = is_ssl();

		// Set primary ref_page cookie
		@setcookie( self::COOKIE_NAME, $listing_slug, $expires, $cookie_path, $cookie_domain, $is_secure, false );
		$_COOKIE[ self::COOKIE_NAME ] = $listing_slug;

		// Set state cookie
		if ( ! empty( $state ) ) {
			@setcookie( self::STATE_COOKIE_NAME, wp_json_encode( $state ), $expires, $cookie_path, $cookie_domain, $is_secure, false );
			$_COOKIE[ self::STATE_COOKIE_NAME ] = wp_json_encode( $state );
		}

		// Set legacy JSON cookie
		$data = array(
			'listing_id'   => $listing_id,
			'listing_slug' => $listing_slug,
			'timestamp'    => time(),
		);
		@setcookie( self::LEGACY_COOKIE_NAME, wp_json_encode( $data ), $expires, $cookie_path, $cookie_domain, $is_secure, false );
		$_COOKIE[ self::LEGACY_COOKIE_NAME ] = wp_json_encode( $data );

		return true;
	}

	/**
	 * Clear / delete ref_page and state cookies.
	 *
	 * @return bool True if cookie cleared.
	 */
	public function clear_cookie(): bool {
		unset( $_COOKIE[ self::COOKIE_NAME ], $_COOKIE[ self::STATE_COOKIE_NAME ], $_COOKIE[ self::LEGACY_COOKIE_NAME ] );

		$cookie_path   = defined( 'COOKIEPATH' ) && COOKIEPATH ? COOKIEPATH : '/';
		$cookie_domain = defined( 'COOKIE_DOMAIN' ) && COOKIE_DOMAIN ? COOKIE_DOMAIN : '';
		$is_secure     = is_ssl();

		if ( ! headers_sent() ) {
			@setcookie( self::LEGACY_COOKIE_NAME, '', time() - 3600, $cookie_path, $cookie_domain, $is_secure, false );
			@setcookie( self::STATE_COOKIE_NAME, '', time() - 3600, $cookie_path, $cookie_domain, $is_secure, false );
			return @setcookie( self::COOKIE_NAME, '', time() - 3600, $cookie_path, $cookie_domain, $is_secure, false );
		}

		return true;
	}

	/**
	 * Retrieve attribution state array from cookie.
	 *
	 * @return array|null Parsed state or null.
	 */
	public static function get_attribution_state(): ?array {
		if ( ! empty( $_COOKIE[ self::STATE_COOKIE_NAME ] ) ) {
			$raw = wp_unslash( $_COOKIE[ self::STATE_COOKIE_NAME ] );
			if ( 0 === strpos( $raw, '{' ) ) {
				$decoded = json_decode( $raw, true );
				if ( is_array( $decoded ) && ! empty( $decoded['attribution_slug'] ) ) {
					return $decoded;
				}
			}
		}
		return null;
	}

	/**
	 * Retrieve stored origin listing slug from cookie.
	 *
	 * @return string|null Slug if exists, null otherwise.
	 */
	public static function get_stored_slug(): ?string {
		if ( ! empty( $_COOKIE[ self::COOKIE_NAME ] ) ) {
			$raw = sanitize_title( wp_unslash( $_COOKIE[ self::COOKIE_NAME ] ) );
			if ( ! empty( $raw ) && 'home' !== $raw ) {
				return $raw;
			}
		}

		if ( ! empty( $_COOKIE[ self::LEGACY_COOKIE_NAME ] ) ) {
			$raw = wp_unslash( $_COOKIE[ self::LEGACY_COOKIE_NAME ] );
			if ( 0 === strpos( $raw, '{' ) ) {
				$decoded = json_decode( $raw, true );
				if ( is_array( $decoded ) && ! empty( $decoded['listing_slug'] ) ) {
					$slug = sanitize_title( $decoded['listing_slug'] );
					if ( ! empty( $slug ) && 'home' !== $slug ) {
						return $slug;
					}
				}
			}
			$slug = sanitize_title( $raw );
			if ( ! empty( $slug ) && 'home' !== $slug ) {
				return $slug;
			}
		}

		return null;
	}

	/**
	 * Retrieve parsed cookie data array (compatibility helper).
	 *
	 * @return array|null Parsed data or null.
	 */
	public static function get_cookie_data(): ?array {
		$slug = self::get_stored_slug();
		if ( empty( $slug ) ) {
			return null;
		}

		return array(
			'listing_id'   => 0,
			'listing_slug' => $slug,
			'timestamp'    => time(),
		);
	}
}
