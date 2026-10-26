<?php

namespace ProsCons\Migration;

defined( 'ABSPATH' ) || exit;

use ProsCons\Migration\Migrations\RemoveNoticeLayout3;
use ProsCons\Migration\Migrations\ClearOldCronJobs;

/**
 * Migration Manager Class
 *
 * Provides migration utilities for ProsCons
 *
 * @since 1.3.8
 * @package ProsCons\Migration
 */
class MigrationManager {

	public function __construct() {
		add_action( 'init', array( $this, 'run_migrations' ) );
	}

	/**
	 * Run migrations sequentially.
	 */
	public function run_migrations() {
		try {
			$migrations = array(
				RemoveNoticeLayout3::class,
				ClearOldCronJobs::class,
			);

			foreach ( $migrations as $migration_class ) {
				if ( class_exists( $migration_class ) ) {
					$migration_class::execute();
				}
			}
		} catch ( \Exception $e ) {
			error_log( 'ProsCons Migration failed: ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}
	}
}

new MigrationManager();
