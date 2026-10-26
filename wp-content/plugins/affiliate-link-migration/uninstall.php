<?php
/**
 * Uninstall Handler for Affiliate Link Migration Plugin
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Clean up stored options and migration progress data.
delete_option( 'affiliate_lm_site_domains' );
delete_option( 'affiliate_lm_batch_size' );
delete_option( 'affiliate_lm_dry_run' );
delete_option( 'affiliate_lm_enable_logs' );
delete_option( 'affiliate_lm_logs' );
delete_option( 'affiliate_lm_progress' );
delete_option( 'affiliate_lm_stats' );
