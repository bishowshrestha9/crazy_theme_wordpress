<?php

namespace ProsCons\Analytics;

use ProsCons\Helpers\QueryBuilder;

defined( 'ABSPATH' ) || exit;

use WP_REST_Request;

/**
 * Analytics REST API endpoints
 *
 * @package ProsCons
 */
class AnalyticsAPI {
	use \ProsCons\Helpers\ResponseHelper;

	/**
	 * Register all analytics REST routes
	 *
	 * @return void
	 */
	public function register_routes(): void {
		$namespace = 'prosandcons/v1/analytics';

		register_rest_route(
			$namespace,
			'/settings',
			array(
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'get_tracking_settings' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				),
				array(
					'methods'             => 'POST',
					'callback'            => array( $this, 'save_tracking_settings' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				),
			)
		);

		register_rest_route(
			$namespace,
			'/track',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'track_click' ),
				'permission_callback' => function ( \WP_REST_Request $request ) {
					$nonce = $request->get_header( 'X-WP-Nonce' );
					return wp_verify_nonce( $nonce, 'wp_rest' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/overview',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_overview' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/top-links',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_top_links' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/top-pages',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_top_pages' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/devices',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_devices' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/clicks',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_clicks' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/impressions',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'track_impression' ),
				'permission_callback' => function ( \WP_REST_Request $request ) {
					$nonce = $request->get_header( 'X-WP-Nonce' );
					return wp_verify_nonce( $nonce, 'wp_rest' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/link-details',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_link_details' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/page-details',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_page_details' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/block-types',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_block_types' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/referrers',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_referrers' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/realtime',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_realtime' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/insights',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_insights' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);


	}

	/**
	 * Track a click event (public endpoint)
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function track_click( WP_REST_Request $request ): void {
		if ( ! get_option( 'prosandcons_tracking_enabled', true ) ) {
			$this->send_json_plain_success( array( 'tracked' => false ) );
			return;
		}

		$body = $request->get_json_params();
		$url  = isset( $body['url'] ) ? esc_url_raw( $body['url'] ) : '';

		if ( empty( $url ) ) {
			$this->send_json_error( 'URL is required.' );
			return;
		}

		$page_id      = isset( $body['page_id'] ) ? absint( $body['page_id'] ) : 0;
		$page_title   = isset( $body['page_title'] ) ? sanitize_text_field( $body['page_title'] ) : '';
		$block_type   = isset( $body['block_type'] ) ? sanitize_text_field( $body['block_type'] ) : '';
		$element_type = isset( $body['element_type'] ) ? sanitize_text_field( $body['element_type'] ) : '';
		$platform     = isset( $body['platform'] ) ? sanitize_text_field( $body['platform'] ) : AnalyticsHelper::detect_platform( $url );
		$product_id   = isset( $body['product_id'] ) ? absint( $body['product_id'] ) : null;
		$referrer     = isset( $body['referrer'] ) ? esc_url_raw( $body['referrer'] ) : '';

		$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';
		$ua_data    = AnalyticsHelper::parse_user_agent( $user_agent );

		$raw_visitor_id = isset( $body['visitor_id'] ) ? sanitize_text_field( $body['visitor_id'] ) : '';
		$visitor_hash   = ! empty( $raw_visitor_id ) ? hash( 'sha256', $raw_visitor_id ) : '';

		$url_hash = md5( $url );

		QueryBuilder::table( 'clicks' )->insert(
			array(
				'url'          => $url,
				'url_hash'     => $url_hash,
				'page_id'      => $page_id,
				'page_title'   => $page_title,
				'block_type'   => $block_type,
				'element_type' => $element_type,
				'platform'     => $platform,
				'product_id'   => $product_id,
				'device'       => $ua_data['device'],
				'browser'      => $ua_data['browser'],
				'os'           => $ua_data['os'],
				'visitor_hash' => $visitor_hash,
				'referrer'     => $referrer,
			),
			array( '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s' )
		);

		$this->send_json_plain_success( array( 'tracked' => true ) );
	}

	/**
	 * Get analytics overview
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function get_overview( WP_REST_Request $request ): void {
		$from = sanitize_text_field( $request->get_param( 'from' ) );
		$to   = sanitize_text_field( $request->get_param( 'to' ) );

		if ( empty( $from ) || empty( $to ) ) {
			$this->send_json_error( 'Parameters from and to are required.' );
			return;
		}

		$total_clicks = QueryBuilder::table( 'clicks' )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->count();

		$unique_clicks = QueryBuilder::table( 'clicks' )
			->select( 'COUNT(DISTINCT CONCAT(visitor_hash, url_hash))' )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->get_value();
		$unique_clicks = (int) $unique_clicks;

		$clicks_today = QueryBuilder::table( 'clicks' )
			->where_raw( "DATE(created_at) = '" . esc_sql( current_time( 'Y-m-d' ) ) . "'" )
			->count();

		$from_date     = new \DateTime( $from );
		$to_date       = new \DateTime( $to );
		$period_length = $from_date->diff( $to_date )->days + 1;

		$prev_to   = $from_date->modify( '-1 day' )->format( 'Y-m-d' );
		$prev_from = ( new \DateTime( $prev_to ) )->modify( '-' . ( $period_length - 1 ) . ' days' )->format( 'Y-m-d' );

		$prev_clicks = QueryBuilder::table( 'clicks' )
			->where_between( 'created_at', $prev_from . ' 00:00:00', $prev_to . ' 23:59:59' )
			->count();

		$clicks_trend = 0;
		if ( $prev_clicks > 0 ) {
			$clicks_trend = round( ( ( $total_clicks - $prev_clicks ) / $prev_clicks ) * 100, 1 );
		}

		$clicks_by_day = QueryBuilder::table( 'clicks' )
			->select( 'DATE(created_at) as date, COUNT(*) as clicks' )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->group_by( 'DATE(created_at)' )
			->order_by( 'date', 'ASC' )
			->get();

		$impressions = QueryBuilder::table( 'impressions' )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->count();

		$ctr = $impressions > 0 ? round( ( $total_clicks / $impressions ) * 100, 2 ) : 0;

		$this->send_json_plain_success(
			array(
				'total_clicks'  => $total_clicks,
				'unique_clicks' => $unique_clicks,
				'clicks_today'  => $clicks_today,
				'clicks_trend'  => $clicks_trend,
				'clicks_by_day' => $clicks_by_day,
				'impressions'   => $impressions,
				'ctr'           => $ctr,
			)
		);
	}

	/**
	 * Get top performing links
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function get_top_links( WP_REST_Request $request ): void {
		$from  = sanitize_text_field( $request->get_param( 'from' ) );
		$to    = sanitize_text_field( $request->get_param( 'to' ) );
		$limit = absint( $request->get_param( 'limit' ) ) ? absint( $request->get_param( 'limit' ) ) : 10;

		if ( empty( $from ) || empty( $to ) ) {
			$this->send_json_error( 'Parameters from and to are required.' );
			return;
		}

		$results = QueryBuilder::table( 'clicks' )
			->select( 'url, COUNT(*) as clicks, COUNT(DISTINCT CONCAT(visitor_hash, url_hash)) as unique_clicks, platform, MAX(created_at) as last_clicked' )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->group_by( 'url_hash' )
			->order_by( 'clicks', 'DESC' )
			->limit( $limit )
			->get();

		$this->send_json_plain_success( array( 'links' => $results ) );
	}

	/**
	 * Get top performing pages
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function get_top_pages( WP_REST_Request $request ): void {
		$from  = sanitize_text_field( $request->get_param( 'from' ) );
		$to    = sanitize_text_field( $request->get_param( 'to' ) );
		$limit = absint( $request->get_param( 'limit' ) ) ? absint( $request->get_param( 'limit' ) ) : 10;

		if ( empty( $from ) || empty( $to ) ) {
			$this->send_json_error( 'Parameters from and to are required.' );
			return;
		}

		$results = QueryBuilder::table( 'clicks' )
			->select( 'page_id, page_title, COUNT(*) as clicks, COUNT(DISTINCT CONCAT(visitor_hash, url_hash)) as unique_clicks' )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->group_by( 'page_id' )
			->order_by( 'clicks', 'DESC' )
			->limit( $limit )
			->get();

		$this->send_json_plain_success( array( 'pages' => $results ) );
	}

	/**
	 * Get device, browser, and OS breakdown
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function get_devices( WP_REST_Request $request ): void {
		$from      = sanitize_text_field( $request->get_param( 'from' ) );
		$to        = sanitize_text_field( $request->get_param( 'to' ) );
		$has_dates = ! empty( $from ) && ! empty( $to );

		$columns = array(
			'devices'   => 'device',
			'browsers'  => 'browser',
			'os'        => 'os',
			'platforms' => 'platform',
		);

		$result = array();
		foreach ( $columns as $key => $column ) {
			$query = QueryBuilder::table( 'clicks' )
				->select( $column . ', COUNT(*) as count' )
				->group_by( $column )
				->order_by( 'count', 'DESC' );

			if ( $has_dates ) {
				$query->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' );
			}

			$result[ $key ] = $query->get();
		}

		$this->send_json_plain_success( $result );
	}

	/**
	 * Get paginated click list
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function get_clicks( WP_REST_Request $request ): void {
		$from     = sanitize_text_field( $request->get_param( 'from' ) );
		$to       = sanitize_text_field( $request->get_param( 'to' ) );
		$page     = absint( $request->get_param( 'page' ) ) ? absint( $request->get_param( 'page' ) ) : 1;
		$per_page = absint( $request->get_param( 'per_page' ) ) ? absint( $request->get_param( 'per_page' ) ) : 20;
		$platform = sanitize_text_field( $request->get_param( 'platform' ) );
		$search   = sanitize_text_field( $request->get_param( 'search' ) );
		$offset   = ( $page - 1 ) * $per_page;

		$data_query  = QueryBuilder::table( 'clicks' )
			->select( 'url, platform, block_type, element_type, COUNT(*) as clicks, COUNT(DISTINCT visitor_hash) as unique_clicks, MAX(created_at) as last_clicked' );
		$count_query = QueryBuilder::table( 'clicks' )
			->select( 'COUNT(DISTINCT url_hash)' );

		if ( ! empty( $from ) && ! empty( $to ) ) {
			$data_query->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' );
			$count_query->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' );
		}

		if ( ! empty( $platform ) ) {
			$data_query->where( 'platform', '=', $platform );
			$count_query->where( 'platform', '=', $platform );
		}

		if ( ! empty( $search ) ) {
			$data_query->where_like( 'url', $search );
			$count_query->where_like( 'url', $search );
		}

		$clicks = $data_query
			->group_by( 'url_hash' )
			->order_by( 'clicks', 'DESC' )
			->limit( $per_page )
			->offset( $offset )
			->get();

		$total = (int) $count_query->get_value();

		$this->send_json_plain_success(
			array(
				'clicks'   => $clicks,
				'total'    => $total,
				'page'     => $page,
				'per_page' => $per_page,
				'pages'    => (int) ceil( $total / $per_page ),
			)
		);
	}

	/**
	 * Get tracking settings
	 *
	 * @return void
	 */
	public function get_tracking_settings(): void {
		$enabled = get_option( 'prosandcons_tracking_enabled', true );

		$this->send_json_plain_success(
			array(
				'enabled' => (bool) $enabled,
			)
		);
	}

	/**
	 * Save tracking settings
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function save_tracking_settings( WP_REST_Request $request ): void {
		$body    = $request->get_json_params();
		$enabled = isset( $body['enabled'] ) ? (bool) $body['enabled'] : true;

		update_option( 'prosandcons_tracking_enabled', $enabled );

		$this->send_json_plain_success(
			array(
				'enabled' => $enabled,
				'message' => $enabled
					? __( 'Click tracking enabled', 'prosandcons' )
					: __( 'Click tracking disabled', 'prosandcons' ),
			)
		);
	}

	/**
	 * Track a page impression (public endpoint)
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function track_impression( WP_REST_Request $request ): void {
		if ( ! get_option( 'prosandcons_tracking_enabled', true ) ) {
			$this->send_json_plain_success( array( 'tracked' => false ) );
			return;
		}

		$body        = $request->get_json_params();
		$page_id     = isset( $body['page_id'] ) ? absint( $body['page_id'] ) : 0;
		$page_title  = isset( $body['page_title'] ) ? sanitize_text_field( $body['page_title'] ) : '';
		$block_count = isset( $body['block_count'] ) ? absint( $body['block_count'] ) : 0;

		if ( $block_count <= 0 ) {
			$this->send_json_error( 'No trackable blocks on page.' );
			return;
		}

		QueryBuilder::table( 'impressions' )->insert(
			array(
				'page_id'     => $page_id,
				'page_title'  => $page_title,
				'block_count' => $block_count,
			),
			array( '%d', '%s', '%d' )
		);

		$this->send_json_plain_success( array( 'tracked' => true ) );
	}

	/**
	 * Get detailed stats for a specific link
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function get_link_details( WP_REST_Request $request ): void {
		$url      = sanitize_text_field( $request->get_param( 'url' ) );
		$from     = sanitize_text_field( $request->get_param( 'from' ) );
		$to       = sanitize_text_field( $request->get_param( 'to' ) );
		$url_hash = md5( $url );

		if ( empty( $url ) || empty( $from ) || empty( $to ) ) {
			$this->send_json_error( 'Parameters url, from, and to are required.' );
			return;
		}

		$clicks_by_day = QueryBuilder::table( 'clicks' )
			->select( 'DATE(created_at) as date, COUNT(*) as clicks' )
			->where( 'url_hash', '=', $url_hash )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->group_by( 'DATE(created_at)' )
			->order_by( 'date', 'ASC' )
			->get();

		$pages = QueryBuilder::table( 'clicks' )
			->select( 'page_id, page_title, COUNT(*) as clicks' )
			->where( 'url_hash', '=', $url_hash )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->group_by( 'page_id' )
			->order_by( 'clicks', 'DESC' )
			->get();

		$devices = QueryBuilder::table( 'clicks' )
			->select( 'device, COUNT(*) as count' )
			->where( 'url_hash', '=', $url_hash )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->group_by( 'device' )
			->order_by( 'count', 'DESC' )
			->get();

		$browsers = QueryBuilder::table( 'clicks' )
			->select( 'browser, COUNT(*) as count' )
			->where( 'url_hash', '=', $url_hash )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->group_by( 'browser' )
			->order_by( 'count', 'DESC' )
			->get();

		$total_clicks = QueryBuilder::table( 'clicks' )
			->where( 'url_hash', '=', $url_hash )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->count();

		$unique_clicks = QueryBuilder::table( 'clicks' )
			->where( 'url_hash', '=', $url_hash )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->count( 'DISTINCT visitor_hash' );

		$this->send_json_plain_success(
			array(
				'total_clicks'  => $total_clicks,
				'unique_clicks' => $unique_clicks,
				'pages_count'   => count( $pages ),
				'clicks_by_day' => $clicks_by_day,
				'pages'         => $pages,
				'devices'       => $devices,
				'browsers'      => $browsers,
			)
		);
	}

	/**
	 * Get detailed stats for a specific page
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function get_page_details( WP_REST_Request $request ): void {
		$page_id = absint( $request->get_param( 'page_id' ) );
		$from    = sanitize_text_field( $request->get_param( 'from' ) );
		$to      = sanitize_text_field( $request->get_param( 'to' ) );

		if ( empty( $page_id ) || empty( $from ) || empty( $to ) ) {
			$this->send_json_error( 'Parameters page_id, from, and to are required.' );
			return;
		}

		$links = QueryBuilder::table( 'clicks' )
			->select( 'url, COUNT(*) as clicks' )
			->where_int( 'page_id', '=', $page_id )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->group_by( 'url_hash' )
			->order_by( 'clicks', 'DESC' )
			->get();

		$clicks_by_day = QueryBuilder::table( 'clicks' )
			->select( 'DATE(created_at) as date, COUNT(*) as clicks' )
			->where_int( 'page_id', '=', $page_id )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->group_by( 'DATE(created_at)' )
			->order_by( 'date', 'ASC' )
			->get();

		$devices = QueryBuilder::table( 'clicks' )
			->select( 'device, COUNT(*) as count' )
			->where_int( 'page_id', '=', $page_id )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->group_by( 'device' )
			->order_by( 'count', 'DESC' )
			->get();

		$total_clicks = QueryBuilder::table( 'clicks' )
			->where_int( 'page_id', '=', $page_id )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->count();

		$unique_clicks = QueryBuilder::table( 'clicks' )
			->where_int( 'page_id', '=', $page_id )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->count( 'DISTINCT visitor_hash' );

		$this->send_json_plain_success(
			array(
				'total_clicks'  => $total_clicks,
				'unique_clicks' => $unique_clicks,
				'links_count'   => count( $links ),
				'links'         => $links,
				'clicks_by_day' => $clicks_by_day,
				'devices'       => $devices,
			)
		);
	}

	/**
	 * Get click counts grouped by block type
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function get_block_types( WP_REST_Request $request ): void {
		$from = sanitize_text_field( $request->get_param( 'from' ) );
		$to   = sanitize_text_field( $request->get_param( 'to' ) );

		if ( empty( $from ) || empty( $to ) ) {
			$this->send_json_error( 'Parameters from and to are required.' );
			return;
		}

		$results = QueryBuilder::table( 'clicks' )
			->select( 'block_type, COUNT(*) as count' )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->where_raw( "block_type != ''" )
			->group_by( 'block_type' )
			->order_by( 'count', 'DESC' )
			->get();

		$this->send_json_plain_success( array( 'block_types' => $results ) );
	}

	/**
	 * Get top referrers grouped by hostname
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function get_referrers( WP_REST_Request $request ): void {
		$from  = sanitize_text_field( $request->get_param( 'from' ) );
		$to    = sanitize_text_field( $request->get_param( 'to' ) );
		$limit = absint( $request->get_param( 'limit' ) ) ? absint( $request->get_param( 'limit' ) ) : 10;

		if ( empty( $from ) || empty( $to ) ) {
			$this->send_json_error( 'Parameters from and to are required.' );
			return;
		}

		$rows = QueryBuilder::table( 'clicks' )
			->select( 'referrer, COUNT(*) as count' )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->where_raw( "referrer != ''" )
			->group_by( 'referrer' )
			->order_by( 'count', 'DESC' )
			->get();

		$grouped = array();
		foreach ( $rows as $row ) {
			$host = wp_parse_url( $row['referrer'], PHP_URL_HOST );
			if ( empty( $host ) ) {
				continue;
			}
			if ( ! isset( $grouped[ $host ] ) ) {
				$grouped[ $host ] = 0;
			}
			$grouped[ $host ] += (int) $row['count'];
		}

		arsort( $grouped );
		$results = array();
		$i       = 0;
		foreach ( $grouped as $referrer => $count ) {
			if ( $i >= $limit ) {
				break;
			}
			$results[] = array(
				'referrer' => $referrer,
				'count'    => $count,
			);
			++$i;
		}

		$this->send_json_plain_success( array( 'referrers' => $results ) );
	}

	/**
	 * Get realtime analytics data
	 *
	 * @return void
	 */
	public function get_realtime(): void {
		$clicks_1h = QueryBuilder::table( 'clicks' )
			->where_raw( "created_at >= '" . esc_sql( gmdate( 'Y-m-d H:i:s', time() - HOUR_IN_SECONDS ) ) . "'" )
			->count();

		$clicks_24h = QueryBuilder::table( 'clicks' )
			->where_raw( "created_at >= '" . esc_sql( gmdate( 'Y-m-d H:i:s', time() - DAY_IN_SECONDS ) ) . "'" )
			->count();

		$active_visitors = QueryBuilder::table( 'clicks' )
			->where_raw( "created_at >= '" . esc_sql( gmdate( 'Y-m-d H:i:s', time() - 5 * MINUTE_IN_SECONDS ) ) . "'" )
			->count( 'DISTINCT visitor_hash' );

		$this->send_json_plain_success(
			array(
				'clicks_last_hour' => $clicks_1h,
				'clicks_today'     => $clicks_24h,
				'active_visitors'  => $active_visitors,
			)
		);
	}

	/**
	 * Get data-driven insights
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function get_insights( WP_REST_Request $request ): void {
		$from = sanitize_text_field( $request->get_param( 'from' ) );
		$to   = sanitize_text_field( $request->get_param( 'to' ) );

		if ( empty( $from ) || empty( $to ) ) {
			$this->send_json_error( 'Parameters from and to are required.' );
			return;
		}

		$from_dt = $from . ' 00:00:00';
		$to_dt   = $to . ' 23:59:59';

		$total_clicks = QueryBuilder::table( 'clicks' )
			->where_between( 'created_at', $from_dt, $to_dt )
			->count();

		$insights = array();

		if ( $total_clicks === 0 ) {
			$this->send_json_plain_success( array( 'insights' => $insights ) );
			return;
		}

		$platforms = QueryBuilder::table( 'clicks' )
			->select( 'platform, COUNT(*) as count' )
			->where_between( 'created_at', $from_dt, $to_dt )
			->group_by( 'platform' )
			->order_by( 'count', 'DESC' )
			->get();

		$platform_map = array();
		foreach ( $platforms as $p ) {
			$platform_map[ $p['platform'] ] = (int) $p['count'];
		}

		if ( isset( $platform_map['amazon'] ) && count( $platform_map ) > 1 ) {
			$non_amazon = $total_clicks - $platform_map['amazon'];
			if ( $non_amazon > 0 ) {
				$ratio = round( ( ( $platform_map['amazon'] - $non_amazon ) / $non_amazon ) * 100, 1 );
				if ( $ratio > 0 ) {
					$insights[] = array(
						'type'    => 'info',
						'message' => sprintf(
							/* translators: %s: percentage */
							__( 'Amazon links get %s%% more clicks than manual links', 'prosandcons' ),
							$ratio
						),
						'icon'    => 'amazon',
					);
				}
			}
		}

		$devices = QueryBuilder::table( 'clicks' )
			->select( 'device, COUNT(*) as count' )
			->where_between( 'created_at', $from_dt, $to_dt )
			->group_by( 'device' )
			->get();

		foreach ( $devices as $d ) {
			if ( strtolower( $d['device'] ) === 'mobile' ) {
				$pct = round( ( (int) $d['count'] / $total_clicks ) * 100, 1 );
				if ( $pct > 0 ) {
					$insights[] = array(
						'type'    => 'info',
						'message' => sprintf(
							/* translators: %s: percentage */
							__( '%s%% of clicks come from mobile', 'prosandcons' ),
							$pct
						),
						'icon'    => 'smartphone',
					);
				}
				break;
			}
		}

		$top_page = QueryBuilder::table( 'clicks' )
			->select( 'page_title, COUNT(*) as clicks' )
			->where_between( 'created_at', $from_dt, $to_dt )
			->group_by( 'page_id' )
			->order_by( 'clicks', 'DESC' )
			->limit( 1 )
			->get_row();

		if ( $top_page && (int) $top_page['clicks'] > 0 ) {
			$insights[] = array(
				'type'    => 'success',
				'message' => sprintf(
					/* translators: 1: page title, 2: click count */
					__( 'Your best page is %1$s with %2$d clicks', 'prosandcons' ),
					$top_page['page_title'],
					(int) $top_page['clicks']
				),
				'icon'    => 'star',
			);
		}

		$top_link = QueryBuilder::table( 'clicks' )
			->select( 'COUNT(*) as clicks' )
			->where_between( 'created_at', $from_dt, $to_dt )
			->group_by( 'url_hash' )
			->order_by( 'clicks', 'DESC' )
			->limit( 1 )
			->get_row();

		if ( $top_link && (int) $top_link['clicks'] > 0 ) {
			$insights[] = array(
				'type'    => 'success',
				'message' => sprintf(
					/* translators: %d: click count */
					__( 'Your top link got %d clicks', 'prosandcons' ),
					(int) $top_link['clicks']
				),
				'icon'    => 'link',
			);
		}

		$top_block = QueryBuilder::table( 'clicks' )
			->select( 'block_type, COUNT(*) as count' )
			->where_between( 'created_at', $from_dt, $to_dt )
			->where_raw( "block_type != ''" )
			->group_by( 'block_type' )
			->order_by( 'count', 'DESC' )
			->limit( 1 )
			->get_row();

		if ( $top_block && (int) $top_block['count'] > 0 ) {
			$insights[] = array(
				'type'    => 'info',
				'message' => sprintf(
					/* translators: %s: block type name */
					__( '%s blocks get the most clicks', 'prosandcons' ),
					ucwords( str_replace( '-', ' ', $top_block['block_type'] ) )
				),
				'icon'    => 'layout',
			);
		}

		$elements = QueryBuilder::table( 'clicks' )
			->select( 'element_type, COUNT(*) as count' )
			->where_between( 'created_at', $from_dt, $to_dt )
			->where_raw( "element_type != ''" )
			->group_by( 'element_type' )
			->order_by( 'count', 'DESC' )
			->get();

		$element_map = array();
		foreach ( $elements as $e ) {
			$element_map[ strtolower( $e['element_type'] ) ] = (int) $e['count'];
		}

		if ( isset( $element_map['button'] ) && isset( $element_map['image'] ) && $element_map['image'] > 0 ) {
			$pct = round( ( ( $element_map['button'] - $element_map['image'] ) / $element_map['image'] ) * 100, 1 );
			if ( $pct > 0 ) {
				$insights[] = array(
					'type'    => 'info',
					'message' => sprintf(
						/* translators: %s: percentage */
						__( 'Buttons get %s%% more clicks than image links', 'prosandcons' ),
						$pct
					),
					'icon'    => 'mouse-pointer',
				);
			}
		}

		$this->send_json_plain_success( array( 'insights' => $insights ) );
	}

	/**
	 * Export click data as CSV
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function get_export( WP_REST_Request $request ): void {
		$from = sanitize_text_field( $request->get_param( 'from' ) );
		$to   = sanitize_text_field( $request->get_param( 'to' ) );

		if ( empty( $from ) || empty( $to ) ) {
			$this->send_json_error( 'Parameters from and to are required.' );
			return;
		}

		$rows = QueryBuilder::table( 'clicks' )
			->select( 'created_at, url, page_title, platform, block_type, element_type, device, browser, os, country' )
			->where_between( 'created_at', $from . ' 00:00:00', $to . ' 23:59:59' )
			->order_by( 'created_at', 'DESC' )
			->get();

		$filename = 'prosandcons-clicks-' . $from . '-to-' . $to . '.csv';

		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );

		$output = fopen( 'php://output', 'w' );
		fputcsv( $output, array( 'Date', 'URL', 'Page', 'Platform', 'Block Type', 'Element', 'Device', 'Browser', 'OS', 'Country' ) );

		foreach ( $rows as $row ) {
			fputcsv(
				$output,
				array(
					$row['created_at'],
					$row['url'],
					$row['page_title'],
					$row['platform'],
					$row['block_type'],
					$row['element_type'],
					$row['device'],
					$row['browser'],
					$row['os'],
					$row['country'],
				)
			);
		}

		unset( $output );
		exit;
	}
}
