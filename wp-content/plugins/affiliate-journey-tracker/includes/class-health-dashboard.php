<?php
namespace AffiliateJourneyTracker;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Health Dashboard Class
 *
 * Renders central status dashboard for Site A Buy Now Button Forwarding Engine.
 */
class HealthDashboard {

	/**
	 * Render Health Dashboard Page HTML.
	 */
	public function render_dashboard_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$forwarding_enabled = get_option( 'affiliate_jt_enable_forwarding', 1 );
		$logs_enabled       = get_option( 'affiliate_jt_enable_logs', 1 );
		$raw_domains        = get_option( 'affiliate_jt_site_a_domains', "thebuyersreviews.com\ndailyhealthsupplement.com\nsupplementmag.com" );

		$cookie_data   = Cookie_Manager::get_cookie_data();
		$cookie_active = ! empty( $cookie_data );

		$stats = Migration::get_stats();
		$logs  = Logger::get_logs( 1 );
		$last_log = ! empty( $logs[0] ) ? $logs[0] : null;

		$raw_buttons = get_option( 'affiliate_jt_button_identifiers', '' );
		$button_desc = ! empty( trim( $raw_buttons ) ) ? $raw_buttons : 'All Qualifying Site A Pretty Links (Default)';

		?>
		<div class="wrap affiliate-jt-debug-wrap">
			<h1><span class="dashicons dashicons-randomize"></span> <?php esc_html_e( 'Affiliate Journey Tracker & Buy Button Engine', 'affiliate-journey-tracker' ); ?></h1>
			<p><?php esc_html_e( 'Central control panel for Site A journey tracking and rendered Buy Now button URL transformation.', 'affiliate-journey-tracker' ); ?></p>

			<!-- Health Status Cards Grid -->
			<div class="affiliate-jt-grid">
				<!-- Card 1: Forwarding Engine -->
				<div class="affiliate-jt-card">
					<h2><span class="dashicons dashicons-update"></span> <?php esc_html_e( 'Buy Button Forwarding', 'affiliate-journey-tracker' ); ?></h2>
					<p>
						<?php if ( $forwarding_enabled ) : ?>
							<span class="badge badge-success"><?php esc_html_e( 'Forwarding Operational', 'affiliate-journey-tracker' ); ?></span>
						<?php else : ?>
							<span class="badge badge-warning"><?php esc_html_e( 'Forwarding Disabled', 'affiliate-journey-tracker' ); ?></span>
						<?php endif; ?>
					</p>
					<p class="description"><strong><?php esc_html_e( 'Processing Mode', 'affiliate-journey-tracker' ); ?>:</strong> DOM HTML Transformation</p>
				</div>

				<!-- Card 2: Journey Cookie -->
				<div class="affiliate-jt-card">
					<h2><span class="dashicons dashicons-admin-network"></span> <?php esc_html_e( 'Journey Cookie', 'affiliate-journey-tracker' ); ?></h2>
					<p>
						<?php if ( $cookie_active ) : ?>
							<span class="badge badge-success"><?php esc_html_e( 'Active Cookie Stored', 'affiliate-journey-tracker' ); ?></span>
						<?php else : ?>
							<span class="badge badge-neutral"><?php esc_html_e( 'No Active Cookie', 'affiliate-journey-tracker' ); ?></span>
						<?php endif; ?>
					</p>
					<p class="description"><strong><?php esc_html_e( 'Current Origin', 'affiliate-journey-tracker' ); ?>:</strong> <code><?php echo esc_html( ! empty( $cookie_data['listing_slug'] ) ? $cookie_data['listing_slug'] : 'N/A' ); ?></code></p>
				</div>

				<!-- Card 3: Version & Log Mode -->
				<div class="affiliate-jt-card">
					<h2><span class="dashicons dashicons-shield"></span> <?php esc_html_e( 'Engine Version & Specs', 'affiliate-journey-tracker' ); ?></h2>
					<p><strong><?php esc_html_e( 'Plugin Version', 'affiliate-journey-tracker' ); ?>:</strong> <code><?php echo esc_html( AFFILIATE_JT_VERSION ); ?></code></p>
					<p style="margin-top:8px;">
						<strong><?php esc_html_e( 'Debug Logging', 'affiliate-journey-tracker' ); ?>:</strong>
						<?php echo $logs_enabled ? '<span class="badge badge-success">Enabled</span>' : '<span class="badge badge-neutral">Disabled</span>'; ?>
					</p>
				</div>
			</div>

			<!-- Domains & Content Quick Stats -->
			<div class="affiliate-jt-grid" style="margin-top:20px;">
				<!-- Card 4: Configured Site A Domains -->
				<div class="affiliate-jt-card">
					<h2><span class="dashicons dashicons-admin-site-alt3"></span> <?php esc_html_e( 'Configured Site A Domains', 'affiliate-journey-tracker' ); ?></h2>
					<pre style="background:#f8f9fa; padding:10px; border-radius:4px; font-size:12px; font-family:monospace; margin:0; max-height:100px; overflow-y:auto;"><?php echo esc_html( $raw_domains ); ?></pre>
				</div>

				<!-- Card 5: Allowed Buttons / Gutenberg Blocks -->
				<div class="affiliate-jt-card">
					<h2><span class="dashicons dashicons-button"></span> <?php esc_html_e( 'Allowed Button Slugs / Block Names', 'affiliate-journey-tracker' ); ?></h2>
					<pre style="background:#f8f9fa; padding:10px; border-radius:4px; font-size:12px; font-family:monospace; margin:0; max-height:100px; overflow-y:auto;"><?php echo esc_html( $button_desc ); ?></pre>
				</div>

				<!-- Card 6: Content Classification Stats -->
				<div class="affiliate-jt-card">
					<h2><span class="dashicons dashicons-chart-bar"></span> <?php esc_html_e( 'Content Summary', 'affiliate-journey-tracker' ); ?></h2>
					<table class="widefat fixed striped">
						<tbody>
							<tr>
								<th><?php esc_html_e( 'Total Posts', 'affiliate-journey-tracker' ); ?></th>
								<td><code><?php echo esc_html( (string) $stats['total'] ); ?></code></td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Classified Posts', 'affiliate-journey-tracker' ); ?></th>
								<td><strong style="color:#10b981;"><?php echo esc_html( (string) $stats['classified'] ); ?></strong></td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Unclassified Remaining', 'affiliate-journey-tracker' ); ?></th>
								<td><strong style="color:#f59e0b;"><?php echo esc_html( (string) $stats['remaining'] ); ?></strong></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<!-- Last Activity Card -->
			<div class="affiliate-jt-card full-width" style="margin-top:20px;">
				<h2><span class="dashicons dashicons-clock"></span> <?php esc_html_e( 'Last HTML Transformation Event', 'affiliate-journey-tracker' ); ?></h2>
				<?php if ( $last_log ) : ?>
					<table class="widefat fixed striped">
						<tbody>
							<tr>
								<th style="width:200px;"><?php esc_html_e( 'Timestamp & IP', 'affiliate-journey-tracker' ); ?></th>
								<td><code><?php echo esc_html( $last_log['timestamp'] ); ?></code> (IP: <code><?php echo esc_html( $last_log['ip'] ); ?></code>)</td>
							</tr>
							<tr>
								<th><?php esc_html_e( 'Event Message', 'affiliate-journey-tracker' ); ?></th>
								<td><?php echo esc_html( $last_log['message'] ); ?></td>
							</tr>
						</tbody>
					</table>
				<?php else : ?>
					<p><em><?php esc_html_e( 'No HTML button transformation events logged yet.', 'affiliate-journey-tracker' ); ?></em></p>
				<?php endif; ?>
			</div>

		</div>
		<?php
	}
}
