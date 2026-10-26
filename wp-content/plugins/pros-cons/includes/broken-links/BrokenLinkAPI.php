<?php

namespace ProsCons\BrokenLinks;

use ProsCons\Helpers\QueryBuilder;
use WP_REST_Request;

defined( 'ABSPATH' ) || exit;

/**
 * Broken Link Checker REST API (free version)
 *
 * @package ProsCons
 */
class BrokenLinkAPI {
	use \ProsCons\Helpers\ResponseHelper;

	/**
	 * Register broken link REST routes
	 *
	 * @return void
	 */
	public function register_routes(): void {
		$namespace = 'prosandcons/v1/broken-links';

		register_rest_route(
			$namespace,
			'/summary',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_summary' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/links',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_links' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/recheck',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'recheck_link' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);

		register_rest_route(
			$namespace,
			'/scan-now',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'trigger_scan' ),
				'permission_callback' => function () {
					return current_user_can( 'manage_options' );
				},
			)
		);



		register_rest_route(
			$namespace,
			'/scan-progress',
			array(
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'get_scan_progress' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				),
				array(
					'methods'             => 'DELETE',
					'callback'            => array( $this, 'cancel_scan' ),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
				),
			)
		);
	}

	/**
	 * Get link check summary
	 *
	 * @return void
	 */
	public function get_summary(): void {
		$summary = BrokenLinkScanner::get_summary();

		$this->send_json_plain_success(
			array(
				'total'     => $summary['total'],
				'healthy'   => $summary['healthy'],
				'broken'    => $summary['broken'],
				'warnings'  => $summary['warnings'],
				'dismissed' => $summary['dismissed'],
				'last_scan' => $summary['last_checked'],
			)
		);
	}

	/**
	 * Get paginated list of links
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function get_links( WP_REST_Request $request ): void {
		$status     = sanitize_text_field( $request->get_param( 'status' ) ?? '' );
		$platform   = sanitize_text_field( $request->get_param( 'platform' ) ?? '' );
		$search     = sanitize_text_field( $request->get_param( 'search' ) ?? '' );
		$page       = max( 1, (int) ( $request->get_param( 'page' ) ?? 1 ) );
		$per_page   = max( 1, min( 100, (int) ( $request->get_param( 'per_page' ) ?? 20 ) ) );
		$sort_by    = sanitize_text_field( $request->get_param( 'sort_by' ) ?? '' );
		$sort_order = strtoupper( sanitize_text_field( $request->get_param( 'sort_order' ) ?? '' ) ) === 'ASC' ? 'ASC' : 'DESC';
		$offset     = ( $page - 1 ) * $per_page;

		$dismissed = $request->get_param( 'dismissed' );

		$count_query = QueryBuilder::table( 'link_checks' );
		$data_query  = QueryBuilder::table( 'link_checks' )->select( '*' );

		if ( $dismissed !== null && $dismissed !== '' ) {
			$count_query->where_int( 'dismissed', '=', (int) $dismissed );
			$data_query->where_int( 'dismissed', '=', (int) $dismissed );
		} else {
			$count_query->where_raw( 'dismissed = 0' );
			$data_query->where_raw( 'dismissed = 0' );
		}

		if ( empty( $status ) || $status === 'issues' ) {
			$count_query->where_raw( "status IN ('broken', 'warning')" );
			$data_query->where_raw( "status IN ('broken', 'warning')" );
		} elseif ( $status !== 'all' ) {
			$count_query->where( 'status', '=', $status );
			$data_query->where( 'status', '=', $status );
		}

		if ( ! empty( $platform ) ) {
			$count_query->where( 'platform', '=', $platform );
			$data_query->where( 'platform', '=', $platform );
		}

		if ( ! empty( $search ) ) {
			$count_query->where_like( 'url', $search );
			$data_query->where_like( 'url', $search );
		}

		$total = $count_query->count();

		$allowed_sort = array( 'url', 'status', 'click_count', 'last_checked' );
		if ( ! empty( $sort_by ) && in_array( $sort_by, $allowed_sort, true ) ) {
			$data_query->order_by( $sort_by, $sort_order );
		} else {
			$data_query->order_by_raw( "CASE WHEN status = 'broken' THEN 0 WHEN status = 'warning' THEN 1 ELSE 2 END, last_checked DESC" );
		}

		$links = $data_query
			->limit( $per_page )
			->offset( $offset )
			->get();

		$this->send_json_plain_success(
			array(
				'links'       => $links,
				'total'       => $total,
				'page'        => $page,
				'per_page'    => $per_page,
				'total_pages' => (int) ceil( $total / $per_page ),
			)
		);
	}

	/**
	 * Recheck a single link
	 *
	 * @param WP_REST_Request $request Request object.
	 * @return void
	 */
	public function recheck_link( WP_REST_Request $request ): void {
		$url = sanitize_url( $request->get_param( 'url' ) ?? '' );

		if ( empty( $url ) ) {
			$this->send_json_error( 'URL is required.' );
			return;
		}

		$url_hash = md5( $url );
		$result   = BrokenLinkScanner::check_link( $url );

		QueryBuilder::table( 'link_checks' )->update(
			array(
				'status'        => $result['status'],
				'http_code'     => $result['http_code'],
				'status_label'  => $result['status_label'],
				'error_message' => $result['error_message'],
				'redirect_url'  => $result['redirect_url'],
				'last_checked'  => gmdate( 'Y-m-d H:i:s' ),
			),
			array( 'url_hash' => $url_hash ),
			array( '%s', '%d', '%s', '%s', '%s', '%s' ),
			array( '%s' )
		);

		$this->send_json_plain_success( $result );
	}

	/**
	 * Trigger a full scan
	 *
	 * @return void
	 */
	public function trigger_scan(): void {
		// Reset all links for a fresh scan.
		QueryBuilder::table( 'link_checks' )
			->where_raw( 'dismissed = 0' )
			->update_raw( "status = 'unchecked', last_checked = NULL, status_label = '', http_code = NULL, error_message = '', redirect_url = ''" );

		// Collect any new links from clicks/products tables.
		BrokenLinkScanner::collect_links();

		$total = QueryBuilder::table( 'link_checks' )
			->where_raw( "status = 'unchecked' AND dismissed = 0" )
			->count();

		// Store scan progress in a transient.
		set_transient(
			'prosandcons_scan_progress',
			array(
				'total'   => $total,
				'checked' => 0,
				'status'  => 'running',
			),
			HOUR_IN_SECONDS
		);

		// Schedule background batches via Action Scheduler.
		if ( function_exists( 'as_enqueue_async_action' ) ) {
			$batches = (int) ceil( $total / 10 );
			for ( $i = 0; $i < $batches; $i++ ) {
				as_enqueue_async_action( 'prosandcons_check_link_batch', array(), 'prosandcons' );
			}
		} else {
			// Fallback: scan a small batch synchronously.
			BrokenLinkScanner::scan_batch( 10 );
			$this->update_scan_progress();
		}

		$this->send_json_plain_success(
			array(
				'total'   => $total,
				'checked' => 0,
				'status'  => 'running',
			)
		);
	}

	/**
	 * Get current scan progress.
	 *
	 * @return void
	 */
	public function get_scan_progress(): void {
		$progress = get_transient( 'prosandcons_scan_progress' );

		if ( ! $progress ) {
			$this->send_json_plain_success( array( 'status' => 'idle' ) );
			return;
		}

		$unchecked = QueryBuilder::table( 'link_checks' )
			->where_raw( "status = 'unchecked' AND dismissed = 0" )
			->count();

		$checked = max( 0, $progress['total'] - $unchecked );

		// Complete when all checked OR no more background jobs pending.
		$has_pending_jobs = function_exists( 'as_has_scheduled_action' )
			&& as_has_scheduled_action( 'prosandcons_check_link_batch' );

		if ( $unchecked === 0 || ! $has_pending_jobs ) {
			delete_transient( 'prosandcons_scan_progress' );
			self::fire_scan_complete( $progress['total'] );
			$this->send_json_plain_success(
				array(
					'total'   => $progress['total'],
					'checked' => $checked,
					'status'  => 'complete',
				)
			);
			return;
		}

		$this->send_json_plain_success(
			array(
				'total'   => $progress['total'],
				'checked' => $checked,
				'status'  => 'running',
			)
		);
	}

	/**
	 * Cancel a running scan.
	 *
	 * @return void
	 */
	public function cancel_scan(): void {
		delete_transient( 'prosandcons_scan_progress' );

		if ( function_exists( 'as_unschedule_all_actions' ) ) {
			as_unschedule_all_actions( 'prosandcons_check_link_batch', array(), 'prosandcons' );
		}

		$this->send_json_success( 'Scan cancelled.' );
	}


	/**
	 * Update scan progress transient after a batch completes.
	 *
	 * @return void
	 */
	public static function update_scan_progress(): void {
		$progress = get_transient( 'prosandcons_scan_progress' );

		if ( ! $progress ) {
			return;
		}

		$unchecked = QueryBuilder::table( 'link_checks' )
			->where_raw( "status = 'unchecked' AND dismissed = 0" )
			->count();

		if ( $unchecked === 0 ) {
			delete_transient( 'prosandcons_scan_progress' );
			self::fire_scan_complete( $progress['total'] );
			return;
		}

		$progress['checked'] = max( 0, $progress['total'] - $unchecked );
		set_transient( 'prosandcons_scan_progress', $progress, HOUR_IN_SECONDS );
	}

	/**
	 * Fire scan complete action only once per scan
	 *
	 * @param int $total Total links scanned.
	 * @return void
	 */
	private static function fire_scan_complete( int $total ): void {
		if ( get_transient( 'prosandcons_scan_complete_fired' ) ) {
			return;
		}
		set_transient( 'prosandcons_scan_complete_fired', true, 60 );
		do_action( 'prosandcons_scan_complete', $total );
	}
}
