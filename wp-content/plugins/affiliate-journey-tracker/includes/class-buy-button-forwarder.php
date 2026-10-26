<?php
namespace AffiliateJourneyTracker;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Buy Button Forwarder Class
 *
 * Safe HTML DOM processor that detects Buy Now buttons pointing to Site A Pretty Links
 * or 2nd page / Site B bridge URLs, appending ref_page=<slug> to anchor hrefs.
 */
class BuyButtonForwarder {

	/**
	 * Register HTML content filtering hooks.
	 */
	public function init(): void {
		add_filter( 'the_content', array( $this, 'filter_html_content' ), 99 );
		add_filter( 'render_block', array( $this, 'filter_gutenberg_block' ), 10, 2 );
		add_filter( 'widget_text', array( $this, 'filter_html_content' ), 99 );
	}

	/**
	 * Filter Gutenberg block content for anchor buttons.
	 *
	 * @param string $block_content Block HTML.
	 * @param array  $block         Block data.
	 * @return string Modified block HTML.
	 */
	public function filter_gutenberg_block( string $block_content, array $block ): string {
		$is_admin_ctx = function_exists( 'is_admin' ) ? \is_admin() : false;
		if ( empty( $block_content ) || $is_admin_ctx ) {
			return $block_content;
		}

		$block_name = ! empty( $block['blockName'] ) ? $block['blockName'] : '';

		if ( false !== strpos( $block_content, '<a' ) ) {
			return $this->filter_html_content( $block_content, $block_name );
		}

		return $block_content;
	}

	/**
	 * Main Rendered HTML Processor.
	 *
	 * Parses HTML safely using WP_HTML_Tag_Processor or DOMDocument, identifies qualifying anchor tags,
	 * and appends ref_page=<slug> to href.
	 *
	 * @param string $content    Rendered HTML page or post content.
	 * @param string $block_name Optional Gutenberg block name.
	 * @return string Modified HTML with updated Buy Now button links.
	 */
	public function filter_html_content( string $content, string $block_name = '' ): string {
		$is_admin = function_exists( 'is_admin' ) ? \is_admin() : false;
		if ( empty( $content ) || $is_admin ) {
			return $content;
		}

		// Bypass link modifications on home page
		if ( is_front_page() || is_home() ) {
			return $content;
		}

		// Fast check: if no anchor tag exists, return immediately.
		if ( false === strpos( $content, '<a' ) ) {
			return $content;
		}

		$journey_slug = Url_Builder::resolve_origin();
		if ( empty( $journey_slug ) ) {
			return $content;
		}

		$post              = get_post();
		$is_listicle       = $post && is_listicle( $post );
		$current_post_slug = ( $post instanceof \WP_Post ) ? $post->post_name : '';

		// 1. Prefer WP_HTML_Tag_Processor if available (WP 6.2+)
		if ( class_exists( 'WP_HTML_Tag_Processor' ) ) {
			return $this->process_html_tag_processor( $content, $journey_slug, $block_name, $is_listicle, $current_post_slug );
		}

		// 2. Fallback DOMDocument parsing.
		return $this->process_html_dom_document( $content, $journey_slug, $block_name, $is_listicle, $current_post_slug );
	}

	/**
	 * Process HTML using WordPress 6.2+ WP_HTML_Tag_Processor.
	 *
	 * @param string $html              Raw HTML content.
	 * @param string $journey_slug      Origin listing slug.
	 * @param string $block_name        Optional block name.
	 * @param bool   $is_listicle       Whether current post is a Listicle.
	 * @param string $current_post_slug Current post slug.
	 * @return string Modified HTML.
	 */
	private function process_html_tag_processor( string $html, string $journey_slug, string $block_name = '', bool $is_listicle = false, string $current_post_slug = '' ): string {
		$processor = new \WP_HTML_Tag_Processor( $html );
		$modified  = false;

		while ( $processor->next_tag( 'A' ) ) {
			$href  = $processor->get_attribute( 'href' );
			$class = $processor->get_attribute( 'class' );
			if ( empty( $href ) ) {
				continue;
			}

			if ( $this->is_qualifying_pretty_link( $href, $block_name, (string) $class ) ) {
				$new_href = $this->append_origin_to_href( $href, $journey_slug );
				if ( $new_href !== $href ) {
					$processor->set_attribute( 'href', $new_href );
					$modified = true;
					$this->log_button_modification( $href, $new_href, $journey_slug );
				}
			} elseif ( $is_listicle && $this->is_internal_post_link( $href ) ) {
				// Tag internal Read More links from Listicles
				$processor->set_attribute( 'data-attribution-action', 'listicle-read-more' );
				$processor->set_attribute( 'data-listicle-slug', $current_post_slug );
				$modified = true;
			}
		}

		return $modified ? $processor->get_updated_html() : $html;
	}

	/**
	 * Process HTML using DOMDocument.
	 *
	 * @param string $html              Raw HTML content.
	 * @param string $journey_slug      Origin listing slug.
	 * @param string $block_name        Optional Gutenberg block name.
	 * @param bool   $is_listicle       Whether current post is a Listicle.
	 * @param string $current_post_slug Current post slug.
	 * @return string Modified HTML.
	 */
	private function process_html_dom_document( string $html, string $journey_slug, string $block_name = '', bool $is_listicle = false, string $current_post_slug = '' ): string {
		if ( ! class_exists( 'DOMDocument' ) ) {
			return $html;
		}

		$dom = new \DOMDocument();
		$libxml_state = libxml_use_internal_errors( true );

		$encoded_html = '<?xml encoding="utf-8" ?>' . $html;
		@$dom->loadHTML( $encoded_html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

		libxml_clear_errors();
		libxml_use_internal_errors( $libxml_state );

		$anchors  = $dom->getElementsByTagName( 'a' );
		$modified = false;

		foreach ( $anchors as $anchor ) {
			if ( ! $anchor instanceof \DOMElement ) {
				continue;
			}

			$href  = $anchor->getAttribute( 'href' );
			$class = $anchor->getAttribute( 'class' );
			if ( empty( $href ) ) {
				continue;
			}

			if ( $this->is_qualifying_pretty_link( $href, $block_name, (string) $class ) ) {
				$new_href = $this->append_origin_to_href( $href, $journey_slug );
				if ( $new_href !== $href ) {
					$anchor->setAttribute( 'href', $new_href );
					$modified = true;
					$this->log_button_modification( $href, $new_href, $journey_slug );
				}
			} elseif ( $is_listicle && $this->is_internal_post_link( $href ) ) {
				$anchor->setAttribute( 'data-attribution-action', 'listicle-read-more' );
				$anchor->setAttribute( 'data-listicle-slug', $current_post_slug );
				$modified = true;
			}
		}

		if ( ! $modified ) {
			return $html;
		}

		$output = $dom->saveHTML();
		$output = preg_replace( '/^<\?xml[^>]*\?>/i', '', $output );

		return ( null !== $output ) ? trim( $output ) : $html;
	}

	/**
	 * Check if a URL points to an internal single post / page (not home, not external).
	 *
	 * @param string $url URL to inspect.
	 * @return bool True if internal content link.
	 */
	public function is_internal_post_link( string $url ): bool {
		$url = trim( $url );
		if ( empty( $url ) || strpos( $url, '#' ) === 0 || strpos( $url, 'javascript:' ) === 0 || strpos( $url, 'mailto:' ) === 0 || strpos( $url, 'tel:' ) === 0 ) {
			return false;
		}

		$home_host = strtolower( (string) wp_parse_url( home_url(), PHP_URL_HOST ) );
		$parsed    = wp_parse_url( $url );
		$host      = isset( $parsed['host'] ) ? strtolower( $parsed['host'] ) : '';
		$path      = isset( $parsed['path'] ) ? trim( $parsed['path'], '/' ) : '';

		if ( empty( $path ) ) {
			return false;
		}

		if ( empty( $host ) || $host === $home_host || $this->is_site_a_domain( $host ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Append ref_page=<slug> to href safely.
	 *
	 * @param string $href         Current href attribute.
	 * @param string $journey_slug Origin listing slug.
	 * @return string Modified href with origin query string.
	 */
	public function append_origin_to_href( string $href, string $journey_slug ): string {
		return Url_Builder::append_origin( $href, $journey_slug );
	}

	/**
	 * Validate if an anchor href qualifies as a Site A Pretty Link or Site B Bridge URL.
	 *
	 * @param string $url        Target href URL.
	 * @param string $block_name Gutenberg block name (optional).
	 * @param string $class_attr CSS class attribute (optional).
	 * @return bool True if qualifying link, false otherwise.
	 */
	public function is_qualifying_pretty_link( string $url, string $block_name = '', string $class_attr = '' ): bool {
		$url = trim( $url );
		if ( empty( $url ) || strpos( $url, '#' ) === 0 || strpos( $url, 'javascript:' ) === 0 || strpos( $url, 'mailto:' ) === 0 || strpos( $url, 'tel:' ) === 0 ) {
			return false;
		}

		// 1. Exclude Amazon, eBay, & non-relevant external sites
		$excluded_markers = array( 'amazon.com', 'amzn.to', 'amazon.', 'ebay.com', 'wikipedia.org' );
		$lower_url        = strtolower( $url );
		foreach ( $excluded_markers as $marker ) {
			if ( strpos( $lower_url, $marker ) !== false ) {
				return false;
			}
		}

		// 2. Direct Site B / Bridge URL check
		if ( Url_Builder::is_site_b_url( $url ) ) {
			return $this->matches_button_identifiers( $url, $block_name, $class_attr );
		}

		$parsed = wp_parse_url( $url );
		$host   = isset( $parsed['host'] ) ? strtolower( $parsed['host'] ) : '';

		// 3. Site A Domain Validation
		if ( ! empty( $host ) && ! $this->is_site_a_domain( $host ) ) {
			return false;
		}

		// 4. Configured Button Slugs / Gutenberg Block Names Filter
		if ( ! $this->matches_button_identifiers( $url, $block_name, $class_attr ) ) {
			return false;
		}

		// 5. Extract Path / Slug for Pretty Link DB Lookup
		$path = isset( $parsed['path'] ) ? trim( $parsed['path'], '/' ) : '';
		if ( empty( $path ) ) {
			return false;
		}

		$path_parts = explode( '/', $path );
		$slug       = strtolower( end( $path_parts ) );

		if ( empty( $slug ) ) {
			return false;
		}

		return $this->is_pretty_link_slug( $slug );
	}

	/**
	 * Check if URL, Gutenberg block, or CSS class matches configured Button Slugs / Block Names.
	 *
	 * @param string $url        Anchor URL.
	 * @param string $block_name Gutenberg block name.
	 * @param string $class_attr CSS class attribute.
	 * @return bool True if matched or if setting is empty.
	 */
	public function matches_button_identifiers( string $url, string $block_name = '', string $class_attr = '' ): bool {
		$raw = get_option( 'affiliate_jt_button_identifiers', '' );
		if ( empty( trim( $raw ) ) ) {
			return true;
		}

		$lines       = explode( "\n", str_replace( "\r", "", $raw ) );
		$block_lower = strtolower( $block_name );
		$class_lower = strtolower( $class_attr );

		$parsed = wp_parse_url( $url );
		$path   = isset( $parsed['path'] ) ? strtolower( trim( $parsed['path'], '/' ) ) : '';

		foreach ( $lines as $line ) {
			$item = strtolower( trim( $line ) );
			if ( empty( $item ) ) {
				continue;
			}

			$item_clean = ltrim( $item, '/.' );

			// 1. Path or slug match
			if ( ! empty( $path ) && ( $path === $item_clean || substr( $path, -strlen( $item_clean ) ) === $item_clean ) ) {
				return true;
			}

			// 2. Gutenberg block name match
			if ( ! empty( $block_name ) && strpos( $block_lower, $item ) !== false ) {
				return true;
			}

			// 3. CSS class match
			if ( ! empty( $class_attr ) && strpos( $class_lower, $item_clean ) !== false ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if host matches configured Site A Domains.
	 *
	 * @param string $host Target host.
	 * @return bool True if Site A domain.
	 */
	public function is_site_a_domain( string $host ): bool {
		$host        = strtolower( trim( $host ) );
		$raw_domains = get_option( 'affiliate_jt_site_a_domains', "thebuyersreviews.com\ndailyhealthsupplement.com\nsupplementmag.com" );
		$lines       = explode( "\n", str_replace( "\r", "", $raw_domains ) );

		foreach ( $lines as $domain ) {
			$domain = strtolower( trim( $domain ) );
			if ( empty( $domain ) ) {
				continue;
			}

			if ( $host === $domain || substr( $host, -strlen( '.' . $domain ) ) === '.' . $domain ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Query Pretty Links Database Table to confirm if a slug is a Pretty Link.
	 *
	 * @param string $slug URL slug.
	 * @return bool True if slug exists in Pretty Links database.
	 */
	public function is_pretty_link_slug( string $slug ): bool {
		global $wpdb;

		if ( class_exists( 'PrliLink' ) && method_exists( 'PrliLink', 'get_hash_by_slug' ) ) {
			$hash = \PrliLink::get_hash_by_slug( $slug );
			if ( ! empty( $hash ) ) {
				return true;
			}
		}

		if ( isset( $wpdb ) && is_object( $wpdb ) ) {
			$table_name = $wpdb->prefix . 'prli_links';
			$query   = $wpdb->prepare( "SELECT id FROM {$table_name} WHERE slug = %s LIMIT 1", $slug );
			$link_id = $wpdb->get_var( $query );

			if ( ! empty( $link_id ) ) {
				return true;
			}
		}

		$reserved = array( 'wp-content', 'wp-includes', 'wp-admin', 'feed', 'category', 'tag', 'author', 'page', 'cart', 'checkout', 'my-account', 'privacy-policy', 'terms' );
		if ( in_array( $slug, $reserved, true ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Log Buy Now button href modification entry.
	 *
	 * @param string $original_href Original href.
	 * @param string $new_href      Modified href.
	 * @param string $journey_slug  Journey origin slug.
	 */
	private function log_button_modification( string $original_href, string $new_href, string $journey_slug ): void {
		Logger::log_event(
			array(
				'event_type'  => 'HTML Render',
				'origin_slug' => $journey_slug,
				'source_url'  => $original_href,
				'target_url'  => $new_href,
				'message'     => sprintf( 'Modified button href to include ref_page=%s', $journey_slug ),
			),
			'debug'
		);
	}
}
