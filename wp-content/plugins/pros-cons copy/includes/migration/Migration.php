<?php

namespace ProsCons\Migration;

defined( 'ABSPATH' ) || exit;

/**
 * Abstract Migration Base Class
 *
 * @since 1.3.8
 * @package ProsCons\Migration
 */
abstract class Migration {

	/**
	 * Execute the migration with version checking and database update.
	 *
	 * @throws \Exception If the migration fails.
	 */
	public static function execute() {
		$db_version        = static::get_db_version();
		$migration_version = static::get_version();

		if ( version_compare( $db_version, $migration_version, '>=' ) ) {
			return false;
		}

		try {
			static::run();
			static::update_db_version( $migration_version );
			return true;
		} catch ( \Exception $e ) {
			error_log( 'ProsCons Migration failed for version ' . $migration_version . ': ' . $e->getMessage() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			throw $e;
		}
	}

	/**
	 * Get the version this migration targets.
	 *
	 * This method must be implemented by all concrete migration classes.
	 */
	abstract protected static function get_version();

	/**
	 * Run the migration logic.
	 *
	 * This method must be implemented by all concrete migration classes.
	 * It should contain the specific logic for the migration task.
	 */
	abstract protected static function run();

	/**
	 * Get the plugin version saved in the database.
	 */
	protected static function get_db_version() {
		return get_option( 'prosandcons_db_version', '1.3.7' );
	}

	/**
	 * Update the plugin version saved in the database.
	 *
	 * @param string $version Version string.
	 */
	protected static function update_db_version( $version ) {
		update_option( 'prosandcons_db_version', $version );
	}
}
