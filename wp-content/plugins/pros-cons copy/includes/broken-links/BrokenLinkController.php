<?php

namespace ProsCons\BrokenLinks;

defined( 'ABSPATH' ) || exit;

/**
 * Broken Link Checker controller (free version)
 *
 * @package ProsCons
 */
class BrokenLinkController {

	/**
	 * Constructor
	 */
	public function __construct() {
		if ( ! \ProsCons\Modules\ModulesAPI::is_enabled( 'broken_link_checker' ) ) {
			return;
		}



		add_action( 'admin_init', array( $this, 'create_table' ) );
		add_action( 'prosandcons_check_link_batch', array( $this, 'run_batch_scan' ) );
		add_action( 'rest_api_init', array( new BrokenLinkAPI(), 'register_routes' ) );
	}

	/**
	 * Create the link_checks table
	 *
	 * @return void
	 */
	public function create_table(): void {
		$db_version_key  = 'prosandcons_link_checks_db_version';
		$current_version = '1.0';

		if ( get_option( $db_version_key ) === $current_version ) {
			return;
		}

		global $wpdb;

		$table   = $wpdb->prefix . 'prosandcons_link_checks';
		$charset = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE {$table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			url varchar(2048) NOT NULL,
			url_hash varchar(32) NOT NULL,
			status varchar(30) NOT NULL DEFAULT 'unchecked',
			http_code int DEFAULT NULL,
			status_label varchar(100) DEFAULT '',
			error_message varchar(500) DEFAULT '',
			redirect_url varchar(2048) DEFAULT '',
			platform varchar(40) DEFAULT '',
			page_ids text DEFAULT '',
			click_count int DEFAULT 0,
			dismissed tinyint(1) DEFAULT 0,
			last_checked datetime DEFAULT NULL,
			created_at datetime DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY (id),
			KEY url_hash (url_hash),
			KEY status (status),
			KEY last_checked (last_checked),
			KEY dismissed (dismissed)
		) {$charset};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		update_option( $db_version_key, $current_version );
	}

	/**
	 * Run a batch scan
	 *
	 * @return void
	 */
	public function run_batch_scan(): void {
		BrokenLinkScanner::scan_batch( 10 );
		BrokenLinkAPI::update_scan_progress();
	}
}
