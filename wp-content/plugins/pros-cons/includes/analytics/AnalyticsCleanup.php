<?php

namespace ProsCons\Analytics;

use ProsCons\Helpers\QueryBuilder;

defined( 'ABSPATH' ) || exit;

/**
 * Analytics data cleanup handler
 *
 * @package ProsCons
 */
class AnalyticsCleanup {

	public function __construct() {
		add_action( 'prosandcons_cleanup_analytics', array( $this, 'cleanup' ) );
	}

	/**
	 * Schedule the daily cleanup event if not already scheduled
	 *
	 * @return void
	 */
	public function schedule(): void {
		if ( ! wp_next_scheduled( 'prosandcons_cleanup_analytics' ) ) {
			wp_schedule_event( time(), 'daily', 'prosandcons_cleanup_analytics' );
		}
	}

	/**
	 * Delete analytics records older than the configured retention period
	 *
	 * @return void
	 */
	public function cleanup(): void {
		$days = absint( get_option( 'prosandcons_analytics_retention_days', 90 ) );

		QueryBuilder::table( 'clicks' )
			->where_raw( 'created_at < DATE_SUB(NOW(), INTERVAL ' . $days . ' DAY)' )
			->delete_where();
	}
}
