<?php
namespace AffiliateLinkMigration;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Menu Class
 *
 * Registers the dedicated top-level admin menu and child pages for Affiliate Migration.
 */
class Admin_Menu {

	public function init(): void {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
	}

	public function register_menu(): void {
		// Top-Level Parent Menu: Affiliate Migration (NOT under Tools or Settings)
		add_menu_page(
			__( 'Affiliate Migration', 'affiliate-link-migration' ),
			__( 'Affiliate Migration', 'affiliate-link-migration' ),
			'manage_options',
			'affiliate-link-migration',
			array( $this, 'render_dashboard_page' ),
			'dashicons-migrate',
			31
		);

		// Submenu 1: Dashboard
		add_submenu_page(
			'affiliate-link-migration',
			__( 'Dashboard - Affiliate Migration', 'affiliate-link-migration' ),
			__( 'Dashboard', 'affiliate-link-migration' ),
			'manage_options',
			'affiliate-link-migration',
			array( $this, 'render_dashboard_page' )
		);

		// Submenu 2: Migration Tool
		add_submenu_page(
			'affiliate-link-migration',
			__( 'Migration Engine - Affiliate Migration', 'affiliate-link-migration' ),
			__( 'Migration', 'affiliate-link-migration' ),
			'manage_options',
			'affiliate-link-migration-run',
			array( $this, 'render_migration_run_page' )
		);

		// Submenu 3: Logs
		add_submenu_page(
			'affiliate-link-migration',
			__( 'Logs - Affiliate Migration', 'affiliate-link-migration' ),
			__( 'Logs', 'affiliate-link-migration' ),
			'manage_options',
			'affiliate-link-migration-logs',
			array( $this, 'render_logs_page' )
		);

		// Submenu 4: Settings
		add_submenu_page(
			'affiliate-link-migration',
			__( 'Settings - Affiliate Migration', 'affiliate-link-migration' ),
			__( 'Settings', 'affiliate-link-migration' ),
			'manage_options',
			'affiliate-link-migration-settings',
			array( $this, 'render_settings_page' )
		);
	}

	public function render_dashboard_page(): void {
		Plugin::get_instance()->dashboard->render_dashboard_page();
	}

	public function render_migration_run_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$nonce    = wp_create_nonce( 'affiliate_lm_nonce' );
		$dry_run  = (bool) get_option( 'affiliate_lm_dry_run', 0 );
		$counts   = wp_count_posts( 'post' );
		$total    = isset( $counts->publish ) ? (int) $counts->publish : 0;
		$progress = get_option( Migration_Engine::PROGRESS_OPTION, array() );

		$processed = ! empty( $progress['processed_ids'] ) ? count( $progress['processed_ids'] ) : 0;
		$pct       = ( $total > 0 ) ? min( 100, (int) round( ( $processed / $total ) * 100 ) ) : 0;

		?>
		<div class="wrap affiliate-lm-wrap">
			<h1><span class="dashicons dashicons-controls-play"></span> <?php esc_html_e( 'Batch Migration Engine', 'affiliate-link-migration' ); ?></h1>
			<p><?php esc_html_e( 'Execute memory-safe AJAX batch processing to append origin=auto to Site A Pretty Links across published posts.', 'affiliate-link-migration' ); ?></p>

			<?php if ( $dry_run ) : ?>
				<div class="notice notice-warning inline"><p><strong><?php esc_html_e( 'Dry Run Mode Active:', 'affiliate-link-migration' ); ?></strong> <?php esc_html_e( 'Simulation mode enabled. Link modifications will be logged without saving updates to post content in database.', 'affiliate-link-migration' ); ?></p></div>
			<?php endif; ?>

			<!-- Control Bar Card -->
			<div class="affiliate-lm-card full-width">
				<div style="display:flex; justify-content:space-between; align-items:center;">
					<div>
						<h2><span class="dashicons dashicons-update"></span> <?php esc_html_e( 'Migration Controller', 'affiliate-link-migration' ); ?></h2>
						<p style="margin:0;"><?php esc_html_e( 'Click Start Batch Migration to launch AJAX execution (50 posts per batch).', 'affiliate-link-migration' ); ?></p>
					</div>
					<div>
						<button type="button" id="lm-start-migration-btn" class="button button-primary button-hero" data-nonce="<?php echo esc_attr( $nonce ); ?>"><span class="dashicons dashicons-controls-play" style="margin-top:6px;"></span> <?php esc_html_e( 'Start Batch Migration', 'affiliate-link-migration' ); ?></button>
						<button type="button" id="lm-reset-migration-btn" class="button button-secondary" data-nonce="<?php echo esc_attr( $nonce ); ?>"><?php esc_html_e( 'Reset Progress', 'affiliate-link-migration' ); ?></button>
					</div>
				</div>

				<!-- Live Progress Bar -->
				<div style="margin-top:20px;">
					<div style="display:flex; justify-content:space-between; font-weight:600; margin-bottom:6px;">
						<span><?php esc_html_e( 'Migration Progress', 'affiliate-link-migration' ); ?></span>
						<span id="lm-progress-pct-text"><?php echo esc_html( (string) $pct ); ?>%</span>
					</div>
					<div class="progress-bar-bg">
						<div id="lm-progress-inner" class="progress-bar-inner" style="width: <?php echo esc_attr( (string) $pct ); ?>%;"></div>
					</div>
				</div>

				<!-- Live Migration Stats Table -->
				<div style="margin-top:20px;">
					<table class="widefat fixed striped">
						<tbody>
							<tr>
								<th style="width:200px;"><?php esc_html_e( 'Total Posts Count', 'affiliate-link-migration' ); ?></th>
								<td><code id="lm-stat-total"><?php echo esc_html( (string) $total ); ?></code></td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Processed Posts', 'affiliate-link-migration' ); ?></th>
								<td><strong id="lm-stat-processed" style="color:#10b981;"><?php echo esc_html( (string) $processed ); ?></strong></td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Remaining Posts', 'affiliate-link-migration' ); ?></th>
								<td><strong id="lm-stat-remaining" style="color:#f59e0b;"><?php echo esc_html( (string) max( 0, $total - $processed ) ); ?></strong></td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Updated Links Count', 'affiliate-link-migration' ); ?></th>
								<td><code id="lm-stat-updated"><?php echo esc_html( (string) ( ! empty( $progress['updated_links'] ) ? $progress['updated_links'] : 0 ) ); ?></code></td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Skipped Posts Count', 'affiliate-link-migration' ); ?></th>
								<td><code id="lm-stat-skipped"><?php echo esc_html( (string) ( ! empty( $progress['skipped_links'] ) ? $progress['skipped_links'] : 0 ) ); ?></code></td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Elapsed Time / ETA', 'affiliate-link-migration' ); ?></th>
								<td>
									Elapsed: <code id="lm-stat-elapsed">00:00</code> | ETA: <code id="lm-stat-eta">00:00</code>
								</td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Current Status', 'affiliate-link-migration' ); ?></th>
								<td><span class="badge badge-neutral" id="lm-stat-status"><?php esc_html_e( 'Idle - Ready to launch', 'affiliate-link-migration' ); ?></span></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php
	}

	public function render_logs_page(): void {
		Plugin::get_instance()->logger->render_logs_page();
	}

	public function render_settings_page(): void {
		Plugin::get_instance()->settings->render_settings_page();
	}
}
