<?php
namespace AffiliateLinkMigration;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Dashboard Class
 *
 * Renders the central statistics dashboard cards and overview page.
 */
class Dashboard {

	/**
	 * Render Dashboard Page HTML.
	 */
	public function render_dashboard_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$counts      = wp_count_posts( 'post' );
		$total_posts = isset( $counts->publish ) ? (int) $counts->publish : 0;

		$progress = get_option( Migration_Engine::PROGRESS_OPTION, array() );

		$processed_ids   = ! empty( $progress['processed_ids'] ) ? (array) $progress['processed_ids'] : array();
		$processed_count = count( $processed_ids );
		$remaining       = max( 0, $total_posts - $processed_count );
		$updated_links   = ! empty( $progress['updated_links'] ) ? (int) $progress['updated_links'] : 0;
		$skipped_links   = ! empty( $progress['skipped_links'] ) ? (int) $progress['skipped_links'] : 0;
		$errors          = ! empty( $progress['errors'] ) ? (int) $progress['errors'] : 0;

		$start_time  = ! empty( $progress['start_timestamp'] ) ? (int) $progress['start_timestamp'] : 0;
		$elapsed_sec = $start_time > 0 ? ( time() - $start_time ) : 0;
		$eta_sec     = ( $processed_count > 0 && $remaining > 0 ) ? (int) round( ( $elapsed_sec / $processed_count ) * $remaining ) : 0;

		$dry_run  = (bool) get_option( 'affiliate_lm_dry_run', 0 );
		$pct      = ( $total_posts > 0 ) ? min( 100, (int) round( ( $processed_count / $total_posts ) * 100 ) ) : 100;
		$nonce    = wp_create_nonce( 'affiliate_lm_nonce' );

		?>
		<div class="wrap affiliate-lm-wrap">
			<h1><span class="dashicons dashicons-migrate"></span> <?php esc_html_e( 'Affiliate Link Migration Dashboard', 'affiliate-link-migration' ); ?></h1>
			<p><?php esc_html_e( 'One-time migration tool to append origin=auto to all Site A Pretty Links across published posts.', 'affiliate-link-migration' ); ?></p>

			<?php if ( $dry_run ) : ?>
				<div class="notice notice-warning inline"><p><strong><?php esc_html_e( 'Dry Run Mode Active:', 'affiliate-link-migration' ); ?></strong> <?php esc_html_e( 'Link transformations will be simulated and logged without modifying post content in the database.', 'affiliate-link-migration' ); ?></p></div>
			<?php endif; ?>

			<!-- Top Metrics Cards Grid -->
			<div class="affiliate-lm-grid">
				<div class="affiliate-lm-card">
					<h2><span class="dashicons dashicons-admin-post"></span> <?php esc_html_e( 'Total Posts', 'affiliate-link-migration' ); ?></h2>
					<div class="metric-value"><?php echo esc_html( number_format( $total_posts ) ); ?></div>
					<p class="description"><?php esc_html_e( 'Published WordPress Posts', 'affiliate-link-migration' ); ?></p>
				</div>

				<div class="affiliate-lm-card">
					<h2><span class="dashicons dashicons-yes-alt"></span> <?php esc_html_e( 'Processed', 'affiliate-link-migration' ); ?></h2>
					<div class="metric-value" style="color:#10b981;"><?php echo esc_html( number_format( $processed_count ) ); ?></div>
					<p class="description"><?php echo esc_html( (string) $pct ); ?>% <?php esc_html_e( 'Completed', 'affiliate-link-migration' ); ?></p>
				</div>

				<div class="affiliate-lm-card">
					<h2><span class="dashicons dashicons-hourglass"></span> <?php esc_html_e( 'Remaining', 'affiliate-link-migration' ); ?></h2>
					<div class="metric-value" style="color:#f59e0b;"><?php echo esc_html( number_format( $remaining ) ); ?></div>
					<p class="description"><?php esc_html_e( 'Posts Pending Migration', 'affiliate-link-migration' ); ?></p>
				</div>

				<div class="affiliate-lm-card">
					<h2><span class="dashicons dashicons-admin-links"></span> <?php esc_html_e( 'Updated Links', 'affiliate-link-migration' ); ?></h2>
					<div class="metric-value" style="color:#6366f1;"><?php echo esc_html( number_format( $updated_links ) ); ?></div>
					<p class="description"><?php esc_html_e( 'Appended with origin=auto', 'affiliate-link-migration' ); ?></p>
				</div>
			</div>

			<!-- Additional Metrics Grid -->
			<div class="affiliate-lm-grid" style="margin-top:20px;">
				<div class="affiliate-lm-card">
					<h2><span class="dashicons dashicons-controls-skipforward"></span> <?php esc_html_e( 'Skipped Posts', 'affiliate-link-migration' ); ?></h2>
					<div class="metric-value"><?php echo esc_html( number_format( $skipped_links ) ); ?></div>
					<p class="description"><?php esc_html_e( 'No Site A links or already had origin=', 'affiliate-link-migration' ); ?></p>
				</div>

				<div class="affiliate-lm-card">
					<h2><span class="dashicons dashicons-warning"></span> <?php esc_html_e( 'Errors', 'affiliate-link-migration' ); ?></h2>
					<div class="metric-value" style="color:<?php echo $errors > 0 ? '#ef4444' : '#6b7280'; ?>;"><?php echo esc_html( (string) $errors ); ?></div>
					<p class="description"><?php esc_html_e( 'Failed Post Update Attempts', 'affiliate-link-migration' ); ?></p>
				</div>

				<div class="affiliate-lm-card">
					<h2><span class="dashicons dashicons-clock"></span> <?php esc_html_e( 'Elapsed Time', 'affiliate-lm-migration' ); ?></h2>
					<div class="metric-value"><?php echo esc_html( $this->format_seconds( $elapsed_sec ) ); ?></div>
					<p class="description"><?php esc_html_e( 'Total Migration Run Time', 'affiliate-link-migration' ); ?></p>
				</div>

				<div class="affiliate-lm-card">
					<h2><span class="dashicons dashicons-dashboard"></span> <?php esc_html_e( 'Estimated Time (ETA)', 'affiliate-link-migration' ); ?></h2>
					<div class="metric-value"><?php echo esc_html( $this->format_seconds( $eta_sec ) ); ?></div>
					<p class="description"><?php esc_html_e( 'Time Remaining to Finish', 'affiliate-link-migration' ); ?></p>
				</div>
			</div>

			<!-- Migration Launcher Quick Action -->
			<div class="affiliate-lm-card full-width" style="margin-top:20px; display:flex; justify-content:space-between; align-items:center;">
				<div>
					<h3><?php esc_html_e( 'Ready to start batch migration?', 'affiliate-link-migration' ); ?></h3>
					<p style="margin:0;"><?php esc_html_e( 'Launch batch execution to process published posts 50 at a time with live progress tracking.', 'affiliate-link-migration' ); ?></p>
				</div>
				<div>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=affiliate-link-migration-run' ) ); ?>" class="button button-primary button-hero"><span class="dashicons dashicons-controls-play" style="margin-top:6px;"></span> <?php esc_html_e( 'Go to Migration Tool', 'affiliate-link-migration' ); ?></a>
				</div>
			</div>

		</div>
		<?php
	}

	private function format_seconds( int $seconds ): string {
		if ( $seconds <= 0 ) {
			return '00:00';
		}
		$hours = floor( $seconds / 3600 );
		$mins  = floor( ( $seconds % 3600 ) / 60 );
		$secs  = $seconds % 60;

		if ( $hours > 0 ) {
			return sprintf( '%02d:%02d:%02d', $hours, $mins, $secs );
		}
		return sprintf( '%02d:%02d', $mins, $secs );
	}
}
