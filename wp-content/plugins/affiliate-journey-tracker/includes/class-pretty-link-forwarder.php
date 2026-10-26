<?php
namespace AffiliateJourneyTracker;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pretty Link Forwarder Class
 *
 * Dedicated service for intercepting Site A Pretty Links redirects before execution
 * and dynamically forwarding the visitor journey origin slug to Site B target URLs.
 */
class PrettyLinkForwarder {

	/**
	 * Register Pretty Links hooks and compatibility mode fallback filters.
	 */
	public function init(): void {
		// Official Pretty Links Filters (Free & Pro)
		add_filter( 'prli_target_url', array( $this, 'filter_prli_target_url' ), 10, 2 );
		add_filter( 'prli_issue_redirect', array( $this, 'filter_prli_issue_redirect' ), 10, 2 );
		add_filter( 'prli_target_url_custom', array( $this, 'filter_prli_target_url_custom' ), 10, 2 );

		// Compatibility Mode Fallbacks
		if ( get_option( 'affiliate_jt_enable_compatibility_mode', 0 ) ) {
			add_filter( 'wp_redirect', array( $this, 'filter_wp_redirect' ), 10, 2 );
			add_action( 'template_redirect', array( $this, 'action_template_redirect' ), 0 );
		}
	}

	/**
	 * Hook callback for prli_target_url filter.
	 *
	 * @param string $target_url Configured Pretty Link target destination URL.
	 * @param mixed  $link       Pretty Link object or link ID.
	 * @return string Modified target URL or original destination.
	 */
	public function filter_prli_target_url( string $target_url, $link = null ): string {
		return $this->forward_pretty_link( $target_url, $link, 'prli_target_url' );
	}

	/**
	 * Hook callback for prli_issue_redirect filter.
	 *
	 * @param string $target_url Target URL.
	 * @param mixed  $link       Link object.
	 * @return string Modified URL.
	 */
	public function filter_prli_issue_redirect( string $target_url, $link = null ): string {
		return $this->forward_pretty_link( $target_url, $link, 'prli_issue_redirect' );
	}

	/**
	 * Hook callback for prli_target_url_custom filter.
	 *
	 * @param string $target_url Target URL.
	 * @param mixed  $link       Link object.
	 * @return string Modified URL.
	 */
	public function filter_prli_target_url_custom( string $target_url, $link = null ): string {
		return $this->forward_pretty_link( $target_url, $link, 'prli_target_url_custom' );
	}

	/**
	 * Hook callback for wp_redirect fallback filter.
	 *
	 * @param string $location Destination redirect URL.
	 * @param int    $status   HTTP status code.
	 * @return string Modified location URL.
	 */
	public function filter_wp_redirect( string $location, int $status = 302 ): string {
		if ( $this->is_allowed_site_b_url( $location ) ) {
			return $this->forward_pretty_link( $location, null, 'wp_redirect' );
		}
		return $location;
	}

	/**
	 * Action callback for template_redirect fallback.
	 */
	public function action_template_redirect(): void {
		if ( is_admin() || ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) ) {
			return;
		}

		if ( ! empty( $GLOBALS['prli_blog_url'] ) && $this->is_allowed_site_b_url( $GLOBALS['prli_blog_url'] ) ) {
			$GLOBALS['prli_blog_url'] = $this->forward_pretty_link( $GLOBALS['prli_blog_url'], null, 'template_redirect' );
		}
	}

	/**
	 * Core Redirection & Parameter Appending Algorithm.
	 *
	 * ALGORITHM STEPS:
	 * 1. Pretty Link requested.
	 * 2. Load Pretty Link destination.
	 * 3. Extract host & check if destination is an allowed Site B domain.
	 * 4. If destination is not Site B -> return original destination.
	 * 5. Read Journey Cookie.
	 * 6. Extract listing slug (e.g. best-smart-rings).
	 * 7. Check whether origin parameter already exists. If yes -> return original destination.
	 * 8. Append origin=<listing_slug> using add_query_arg().
	 * 9. Record Redirect Trace.
	 * 10. Return modified destination URL.
	 *
	 * @param string $destination_url Target destination URL.
	 * @param mixed  $link            Pretty Link object or ID.
	 * @param string $hook_used       Hook method identifier.
	 * @return string Final destination URL.
	 */
	public function forward_pretty_link( string $destination_url, $link = null, string $hook_used = 'prli_target_url' ): string {
		$start_time = microtime( true );

		if ( empty( $destination_url ) ) {
			return $destination_url;
		}

		// 1. Check if Forwarding Engine is enabled.
		$enabled = get_option( 'affiliate_jt_enable_forwarding', 1 );
		if ( ! $enabled ) {
			return $destination_url;
		}

		// Parse Request & Pretty Link Details.
		$req_uri       = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '/go/pretty-link';
		$requested_url = home_url( $req_uri );
		$link_slug     = trim( $req_uri, '/' );
		$link_id       = 0;

		if ( is_object( $link ) ) {
			if ( ! empty( $link->id ) ) {
				$link_id = (int) $link->id;
			}
			if ( ! empty( $link->slug ) ) {
				$link_slug = $link->slug;
			}
		} elseif ( is_numeric( $link ) ) {
			$link_id = (int) $link;
		}

		$parsed_dest = wp_parse_url( $destination_url );
		$dest_host   = isset( $parsed_dest['host'] ) ? strtolower( $parsed_dest['host'] ) : '';

		// 2. Smart Destination Domain Validation.
		if ( ! $this->is_allowed_site_b_url( $destination_url ) ) {
			RedirectTrace::log_trace(
				array(
					'link_id'       => $link_id,
					'link_slug'     => $link_slug,
					'requested_url' => $requested_url,
					'original_url'  => $destination_url,
					'modified_url'  => $destination_url,
					'dest_host'     => $dest_host,
					'cookie_found'  => false,
					'journey_slug'  => 'N/A',
					'origin_added'  => false,
					'hook_used'     => $hook_used,
					'exec_time'     => round( ( microtime( true ) - $start_time ) * 1000, 2 ),
					'status'        => 'Skipped - Not Site B Domain',
				)
			);
			return $destination_url;
		}

		// 3. Read Journey Cookie.
		$cookie_data  = Cookie_Manager::get_cookie_data();
		$cookie_found = ! empty( $cookie_data );
		$journey_slug = ! empty( $cookie_data['listing_slug'] ) ? $cookie_data['listing_slug'] : Url_Builder::resolve_origin();

		if ( empty( $journey_slug ) ) {
			RedirectTrace::log_trace(
				array(
					'link_id'       => $link_id,
					'link_slug'     => $link_slug,
					'requested_url' => $requested_url,
					'original_url'  => $destination_url,
					'modified_url'  => $destination_url,
					'dest_host'     => $dest_host,
					'cookie_found'  => false,
					'journey_slug'  => 'None',
					'origin_added'  => false,
					'hook_used'     => $hook_used,
					'exec_time'     => round( ( microtime( true ) - $start_time ) * 1000, 2 ),
					'status'        => 'Skipped - No Journey Cookie',
				)
			);
			return $destination_url;
		}

		// 4. Append ONLY ref=<slug> safely using Url_Builder::append_origin / add_query_arg.
		$modified_url = Url_Builder::append_origin( $destination_url, $journey_slug );

		if ( ! headers_sent() && ! empty( $journey_slug ) && 'home' !== $journey_slug ) {
			@header( 'X-Ref: ' . rawurlencode( $journey_slug ) );
		}

		$exec_time = round( ( microtime( true ) - $start_time ) * 1000, 2 );

		// 5. Record Redirect Trace.
		RedirectTrace::log_trace(
			array(
				'link_id'       => $link_id,
				'link_slug'     => $link_slug,
				'requested_url' => $requested_url,
				'original_url'  => $destination_url,
				'modified_url'  => $modified_url,
				'dest_host'     => $dest_host,
				'cookie_found'  => $cookie_found,
				'journey_slug'  => $journey_slug,
				'origin_added'  => true,
				'hook_used'     => $hook_used,
				'exec_time'     => $exec_time,
				'status'        => 'Intercepted & Appended ref',
			)
		);

		Logger::log_event(
			array(
				'event_type'  => 'Pretty Link Redirect',
				'origin_slug' => $journey_slug,
				'source_url'  => $requested_url,
				'target_url'  => $modified_url,
				'message'     => sprintf( 'Pretty Link redirect: %s -> %s (ref: %s | %sms)', $requested_url, $modified_url, $journey_slug, (string) $exec_time ),
			),
			'info'
		);

		return $modified_url;
	}

	/**
	 * Check if a URL belongs to configured Site B allowed domains.
	 *
	 * @param string $url Target destination URL.
	 * @return bool True if allowed domain, false otherwise.
	 */
	public function is_allowed_site_b_url( string $url ): bool {
		return Url_Builder::is_site_b_url( $url );
	}
}
