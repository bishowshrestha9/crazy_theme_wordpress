<?php
namespace AffiliateJourneyTracker;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Debug Page Class
 *
 * Renders the diagnostic debug dashboard inspecting all 11 required status properties.
 */
class Debug_Page {

	/**
	 * Register hooks for AJAX handlers.
	 */
	public function init(): void {
		add_action( 'wp_ajax_affiliate_jt_clear_logs', array( $this, 'ajax_clear_logs' ) );
		add_action( 'wp_ajax_affiliate_jt_test_url', array( $this, 'ajax_test_url' ) );
	}

	/**
	 * AJAX Handler to clear system logs safely.
	 */
	public function ajax_clear_logs(): void {
		check_ajax_referer( 'affiliate_jt_debug_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'affiliate-journey-tracker' ) ) );
		}

		Logger::clear_logs();
		wp_send_json_success( array( 'message' => __( 'Logs cleared successfully.', 'affiliate-journey-tracker' ) ) );
	}

	/**
	 * AJAX Handler to generate test URL transformation.
	 */
	public function ajax_test_url(): void {
		check_ajax_referer( 'affiliate_jt_debug_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'affiliate-journey-tracker' ) ) );
		}

		$input_url = isset( $_POST['url'] ) ? esc_url_raw( wp_unslash( $_POST['url'] ) ) : '';
		$origin    = isset( $_POST['origin'] ) ? sanitize_title( wp_unslash( $_POST['origin'] ) ) : '';

		if ( empty( $input_url ) ) {
			wp_send_json_error( array( 'message' => __( 'Please enter a valid target URL.', 'affiliate-journey-tracker' ) ) );
		}

		if ( empty( $origin ) ) {
			$origin = Url_Builder::resolve_origin();
		}

		$generated_url = Url_Builder::append_origin( $input_url, $origin );

		wp_send_json_success(
			array(
				'original_url'  => $input_url,
				'origin'        => $origin,
				'generated_url' => $generated_url,
			)
		);
	}

	/**
	 * Render full Debug diagnostic page HTML.
	 */
	public function render_debug_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$nonce = wp_create_nonce( 'affiliate_jt_debug_nonce' );

		// 1. Current Cookie & State Info
		$cookie_data = Cookie_Manager::get_cookie_data();
		$state_data  = Cookie_Manager::get_attribution_state();
		$raw_cookie  = isset( $_COOKIE[ Cookie_Manager::COOKIE_NAME ] ) ? wp_unslash( $_COOKIE[ Cookie_Manager::COOKIE_NAME ] ) : null;
		$cookie_exists = ! empty( $raw_cookie );

		// 2. Current Page & Post Info
		$post               = get_post();
		$current_page_title = $post ? get_the_title( $post ) : __( 'N/A (Admin Context)', 'affiliate-journey-tracker' );
		$current_post_id   = $post ? $post->ID : 0;
		$current_slug      = $post ? $post->post_name : 'n-a';
		$is_top            = $post ? is_listicle( $post ) : false;
		$detected_type     = $is_top ? __( 'Listicle Page (Category: Listicle)', 'affiliate-journey-tracker' ) : __( 'Product Review / Standard Page', 'affiliate-journey-tracker' );

		// 3. Origin & URL Test Info
		$resolved_origin = Url_Builder::resolve_origin( $post );
		$site_b_domain   = get_option( 'affiliate_jt_site_b_domain', 'bestproductpicks.com, getbestsupplements.com, siteb.com' );
		$first_domain    = trim( explode( ',', $site_b_domain )[0] );
		$sample_before   = 'https://' . rtrim( $first_domain, '/' ) . '/glokore-eyerenew-pro';
		$sample_after    = Url_Builder::append_origin( $sample_before, $resolved_origin ? $resolved_origin : 'glokore-eyerenew-pro-reviews' );

		// 4. User & Server Info
		$current_user = wp_get_current_user();
		$user_display = $current_user->exists() ? sprintf( '%s (%s)', $current_user->display_name, implode( ', ', $current_user->roles ) ) : 'Guest';
		$server_time  = current_time( 'Y-m-d H:i:s' );

		$logs = Logger::get_logs( 50 );

		?>
		<div class="wrap affiliate-jt-debug-wrap">
			<h1><span class="dashicons dashicons-analytics"></span> <?php esc_html_e( 'Journey Tracker Debug Diagnostic Page', 'affiliate-journey-tracker' ); ?></h1>
			<p><?php esc_html_e( 'Comprehensive live diagnostic dashboard inspecting attribution state, cookie storage, and ref_page forwarding.', 'affiliate-journey-tracker' ); ?></p>

			<!-- 11-Property Diagnostic Matrix -->
			<div class="affiliate-jt-card full-width">
				<h2><span class="dashicons dashicons-dashboard"></span> <?php esc_html_e( 'System & Journey Tracking Status Matrix', 'affiliate-journey-tracker' ); ?></h2>
				<table class="widefat fixed striped">
					<tbody>
						<tr>
							<th style="width:230px;"><?php esc_html_e( '1. Current Page Context', 'affiliate-journey-tracker' ); ?></th>
							<td><strong><?php echo esc_html( $current_page_title ); ?></strong></td>
						</tr>
						<tr>
							<th><?php esc_html_e( '2. Current Post ID', 'affiliate-journey-tracker' ); ?></th>
							<td><code><?php echo esc_html( (string) $current_post_id ); ?></code></td>
						</tr>
						<tr>
							<th><?php esc_html_e( '3. Current Slug', 'affiliate-journey-tracker' ); ?></th>
							<td><code><?php echo esc_html( $current_slug ); ?></code></td>
						</tr>
						<tr>
							<th><?php esc_html_e( '4. Post Classification', 'affiliate-journey-tracker' ); ?></th>
							<td>
								<?php if ( $is_top ) : ?>
									<span class="badge badge-success"><?php echo esc_html( $detected_type ); ?></span>
								<?php else : ?>
									<span class="badge badge-neutral"><?php echo esc_html( $detected_type ); ?></span>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( '5. Attribution Mode', 'affiliate-journey-tracker' ); ?></th>
							<td>
								<?php if ( ! empty( $state_data['mode'] ) && 'LISTICLE-PRESERVED' === $state_data['mode'] ) : ?>
									<span class="badge badge-warning">LISTICLE-PRESERVED</span>
									<small>(Listicle: <strong><?php echo esc_html( $state_data['listicle_slug'] ); ?></strong> &rarr; Target Review: <strong><?php echo esc_html( $state_data['intended_review_slug'] ); ?></strong>)</small>
								<?php else : ?>
									<span class="badge badge-neutral">NORMAL</span>
									<small>(Current product page receives direct attribution)</small>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( '6. ref Cookie Value', 'affiliate-journey-tracker' ); ?></th>
							<td>
								<?php if ( $cookie_exists ) : ?>
									<span class="origin-highlight"><?php echo esc_html( $raw_cookie ); ?></span>
								<?php else : ?>
									<em><?php esc_html_e( 'No active ref cookie stored', 'affiliate-journey-tracker' ); ?></em>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<th><?php esc_html_e( '7. Resolved ref for Destination', 'affiliate-journey-tracker' ); ?></th>
							<td><span class="origin-highlight"><?php echo esc_html( $resolved_origin ? $resolved_origin : $current_slug ); ?></span></td>
						</tr>
						<tr>
							<th><?php esc_html_e( '8. Sample Original Bridge URL', 'affiliate-journey-tracker' ); ?></th>
							<td><code><?php echo esc_html( $sample_before ); ?></code></td>
						</tr>
						<tr>
							<th><?php esc_html_e( '9. Modified Bridge URL (with ref=)', 'affiliate-journey-tracker' ); ?></th>
							<td><code class="result-url"><?php echo esc_html( $sample_after ); ?></code></td>
						</tr>
						<tr>
							<th><?php esc_html_e( '10. Current User', 'affiliate-journey-tracker' ); ?></th>
							<td><code><?php echo esc_html( $user_display ); ?></code></td>
						</tr>
						<tr>
							<th><?php esc_html_e( '11. Server Timestamp', 'affiliate-journey-tracker' ); ?></th>
							<td><code><?php echo esc_html( $server_time ); ?></code></td>
						</tr>
					</tbody>
				</table>
			</div>

			<!-- Interactive URL Generator Sandbox -->
			<div class="affiliate-jt-card full-width" style="margin-top:20px;">
				<h2><span class="dashicons dashicons-admin-links"></span> <?php esc_html_e( 'Generated URL Sandbox & Testing Tool', 'affiliate-journey-tracker' ); ?></h2>
				<div class="url-tester-box">
					<div class="field-row">
						<label for="jt-test-url-input"><strong><?php esc_html_e( 'Target 2nd Page / Bridge URL:', 'affiliate-journey-tracker' ); ?></strong></label>
						<input type="url" id="jt-test-url-input" class="large-text" value="<?php echo esc_url( $sample_before ); ?>" placeholder="https://links.theunbiasedreviews.org/go/vital-shoulder-massager" />
					</div>
					<div class="field-row" style="margin-top:10px;">
						<label for="jt-test-origin-input"><strong><?php esc_html_e( 'Origin Slug (ref override):', 'affiliate-journey-tracker' ); ?></strong></label>
						<input type="text" id="jt-test-origin-input" class="regular-text" placeholder="<?php echo esc_attr( $resolved_origin ? $resolved_origin : 'best-heaters-in-the-town' ); ?>" />
						<button type="button" id="jt-run-url-test" class="button button-primary"><?php esc_html_e( 'Generate Tracked URL', 'affiliate-journey-tracker' ); ?></button>
					</div>

					<div id="jt-url-test-result" class="url-result-panel" style="margin-top:15px; display:block;">
						<p><strong><?php esc_html_e( 'Before:', 'affiliate-journey-tracker' ); ?></strong> <code id="jt-before-url"><?php echo esc_html( $sample_before ); ?></code></p>
						<p><strong><?php esc_html_e( 'After (with ref=):', 'affiliate-journey-tracker' ); ?></strong> <code id="jt-after-url" class="result-url"><?php echo esc_html( $sample_after ); ?></code></p>
					</div>
				</div>
			</div>

			<!-- Real-Time Click & Redirect Logs -->
			<div class="affiliate-jt-card full-width" style="margin-top:20px;">
				<div style="display:flex; justify-content:space-between; align-items:center;">
					<h2><span class="dashicons dashicons-list-view"></span> <?php esc_html_e( 'Real-Time Click & Redirect Logs', 'affiliate-journey-tracker' ); ?></h2>
					<button type="button" id="jt-clear-logs-btn" class="button button-secondary" data-nonce="<?php echo esc_attr( $nonce ); ?>">
						<span class="dashicons dashicons-trash" style="vertical-align:middle; margin-top:-2px;"></span>
						<?php esc_html_e( 'Clear Logs', 'affiliate-journey-tracker' ); ?>
					</button>
				</div>
				<p><?php esc_html_e( 'Live log of all visitor button clicks, Pretty Link redirects, and ref_page origin parameter attachments.', 'affiliate-journey-tracker' ); ?></p>
				<div id="jt-log-message"></div>

				<div class="log-table-container">
					<table class="widefat fixed striped log-table">
						<thead>
							<tr>
								<th style="width:145px;"><?php esc_html_e( 'Timestamp', 'affiliate-journey-tracker' ); ?></th>
								<th style="width:110px;"><?php esc_html_e( 'Event Type', 'affiliate-journey-tracker' ); ?></th>
								<th style="width:140px;"><?php esc_html_e( 'Origin Slug (ref_page)', 'affiliate-journey-tracker' ); ?></th>
								<th><?php esc_html_e( 'Source Page URL', 'affiliate-journey-tracker' ); ?></th>
								<th><?php esc_html_e( 'Target 2nd Page URL', 'affiliate-journey-tracker' ); ?></th>
								<th style="width:110px;"><?php esc_html_e( 'Client IP', 'affiliate-journey-tracker' ); ?></th>
							</tr>
						</thead>
						<tbody id="jt-log-tbody">
							<?php if ( ! empty( $logs ) ) : ?>
								<?php foreach ( $logs as $log ) : ?>
									<tr>
										<td><code><?php echo esc_html( $log['timestamp'] ); ?></code></td>
										<td>
											<?php if ( ! empty( $log['event_type'] ) && 'Button Click' === $log['event_type'] ) : ?>
												<span class="badge badge-success"><?php echo esc_html( $log['event_type'] ); ?></span>
											<?php elseif ( ! empty( $log['event_type'] ) && 'Pretty Link Redirect' === $log['event_type'] ) : ?>
												<span class="badge badge-warning"><?php echo esc_html( $log['event_type'] ); ?></span>
											<?php else : ?>
												<span class="badge badge-neutral"><?php echo esc_html( ! empty( $log['event_type'] ) ? $log['event_type'] : 'Log' ); ?></span>
											<?php endif; ?>
										</td>
										<td><span class="origin-highlight"><?php echo esc_html( ! empty( $log['origin_slug'] ) ? $log['origin_slug'] : 'N/A' ); ?></span></td>
										<td><code><?php echo esc_html( ! empty( $log['source_url'] ) ? $log['source_url'] : $log['message'] ); ?></code></td>
										<td><code class="result-url"><?php echo esc_html( ! empty( $log['target_url'] ) ? $log['target_url'] : 'N/A' ); ?></code></td>
										<td><code><?php echo esc_html( ! empty( $log['ip'] ) ? $log['ip'] : '127.0.0.1' ); ?></code></td>
									</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<tr>
									<td colspan="6"><em><?php esc_html_e( 'No click or journey tracking logs recorded yet.', 'affiliate-journey-tracker' ); ?></em></td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>

		</div>
		<?php
	}
}
