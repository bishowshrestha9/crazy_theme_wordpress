<?php

namespace ProsCons\Migration\Migrations;

defined( 'ABSPATH' ) || exit;

use ProsCons\Migration\Migration;

/**
 * Migration to clear old WP-Cron jobs before switching to Action Scheduler.
 *
 * @package ProsCons\Migration\Migrations
 */
class ClearOldCronJobs extends Migration {
	/**
	 * The version this migration targets.
	 */
	protected static function get_version() {
		return '1.4.0';
	}

	/**
	 * Run the migration logic.
	 */
	protected static function run() {
		wp_clear_scheduled_hook( 'prosandcons_sync_amazon_products' );
		wp_clear_scheduled_hook( 'prosandcons_sync_product_listings' );
		wp_clear_scheduled_hook( 'prosandcons_cleanup_product_listings' );
	}
}
