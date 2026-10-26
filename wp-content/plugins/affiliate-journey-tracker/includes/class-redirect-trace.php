<?php
namespace AffiliateJourneyTracker;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Redirect Trace Class
 *
 * Dedicated diagnostic trace logger capturing every Pretty Link redirect execution breakdown.
 */
class RedirectTrace {

	public const OPTION_KEY = 'affiliate_jt_redirect_trace';
	public const MAX_TRACES = 100;

	/**
	 * Log a redirect trace entry if trace logging is enabled.
	 *
	 * @param array $data Trace data details.
	 * @return bool True if logged.
	 */
	public static function log_trace( array $data ): bool {
		$enabled = function_exists( 'get_option' ) ? \get_option( 'affiliate_jt_enable_redirect_trace', 1 ) : 1;
		if ( ! $enabled ) {
			return false;
		}

		$entry = array(
			'timestamp'     => function_exists( 'current_time' ) ? \current_time( 'mysql' ) : date( 'Y-m-d H:i:s' ),
			'link_id'       => isset( $data['link_id'] ) ? (int) $data['link_id'] : 0,
			'link_slug'     => isset( $data['link_slug'] ) ? sanitize_text_field( $data['link_slug'] ) : 'n-a',
			'requested_url' => isset( $data['requested_url'] ) ? sanitize_text_field( $data['requested_url'] ) : '',
			'original_url'  => isset( $data['original_url'] ) ? sanitize_text_field( $data['original_url'] ) : '',
			'modified_url'  => isset( $data['modified_url'] ) ? sanitize_text_field( $data['modified_url'] ) : '',
			'dest_host'     => isset( $data['dest_host'] ) ? sanitize_text_field( $data['dest_host'] ) : '',
			'cookie_found'  => ! empty( $data['cookie_found'] ),
			'journey_slug'  => isset( $data['journey_slug'] ) ? sanitize_text_field( $data['journey_slug'] ) : '',
			'origin_added'  => ! empty( $data['origin_added'] ),
			'hook_used'     => isset( $data['hook_used'] ) ? sanitize_text_field( $data['hook_used'] ) : 'prli_target_url',
			'exec_time'     => isset( $data['exec_time'] ) ? (float) $data['exec_time'] : 0.0,
			'status'        => isset( $data['status'] ) ? sanitize_text_field( $data['status'] ) : 'Logged',
		);

		if ( function_exists( 'get_option' ) && function_exists( 'update_option' ) ) {
			$traces = \get_option( self::OPTION_KEY, array() );
			if ( ! is_array( $traces ) ) {
				$traces = array();
			}

			array_unshift( $traces, $entry );

			if ( count( $traces ) > self::MAX_TRACES ) {
				$traces = array_slice( $traces, 0, self::MAX_TRACES );
			}

			\update_option( self::OPTION_KEY, $traces, false );
		}

		return true;
	}

	/**
	 * Retrieve stored redirect traces.
	 *
	 * @param int $limit Max count.
	 * @return array Traces array.
	 */
	public static function get_traces( int $limit = 100 ): array {
		if ( ! function_exists( 'get_option' ) ) {
			return array();
		}
		$traces = \get_option( self::OPTION_KEY, array() );
		if ( ! is_array( $traces ) ) {
			return array();
		}
		return array_slice( $traces, 0, $limit );
	}

	/**
	 * Clear stored trace entries.
	 *
	 * @return bool True.
	 */
	public static function clear_traces(): bool {
		if ( function_exists( 'delete_option' ) ) {
			\delete_option( self::OPTION_KEY );
		}
		return true;
	}

	/**
	 * Render Redirect Trace Admin Page.
	 */
	public function render_trace_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$nonce  = wp_create_nonce( 'affiliate_jt_debug_nonce' );
		$traces = self::get_traces( 100 );

		?>
		<div class="wrap affiliate-jt-debug-wrap">
			<div style="display:flex; justify-content:space-between; align-items:center;">
				<h1><span class="dashicons dashicons-location-alt"></span> <?php esc_html_e( 'Redirect Trace Dashboard', 'affiliate-journey-tracker' ); ?></h1>
				<button type="button" id="jt-clear-traces-btn" class="button button-secondary" data-nonce="<?php echo esc_attr( $nonce ); ?>"><?php esc_html_e( 'Clear Redirect Traces', 'affiliate-journey-tracker' ); ?></button>
			</div>
			<p><?php esc_html_e( 'Detailed execution trace log recording Pretty Link ID, slug, requested URL, original destination, modified destination, cookie status, hook used, execution time (ms), and status.', 'affiliate-journey-tracker' ); ?></p>

			<div id="jt-trace-message"></div>

			<!-- Trace Table -->
			<div class="affiliate-jt-card full-width" style="margin-top:15px;">
				<div class="log-table-container">
					<table class="widefat fixed striped log-table">
						<thead>
							<tr>
								<th style="width:140px;"><?php esc_html_e( 'Timestamp', 'affiliate-journey-tracker' ); ?></th>
								<th style="width:110px;"><?php esc_html_e( 'Pretty Link Slug', 'affiliate-journey-tracker' ); ?></th>
								<th style="width:130px;"><?php esc_html_e( 'Destination Host', 'affiliate-journey-tracker' ); ?></th>
								<th style="width:120px;"><?php esc_html_e( 'Journey Slug', 'affiliate-journey-tracker' ); ?></th>
								<th><?php esc_html_e( 'Original Destination URL', 'affiliate-journey-tracker' ); ?></th>
								<th><?php esc_html_e( 'Modified Destination URL', 'affiliate-journey-tracker' ); ?></th>
								<th style="width:120px;"><?php esc_html_e( 'Hook Used', 'affiliate-journey-tracker' ); ?></th>
								<th style="width:70px;"><?php esc_html_e( 'Time', 'affiliate-journey-tracker' ); ?></th>
								<th style="width:140px;"><?php esc_html_e( 'Status', 'affiliate-journey-tracker' ); ?></th>
							</tr>
						</thead>
						<tbody id="jt-trace-tbody">
							<?php if ( ! empty( $traces ) ) : ?>
								<?php foreach ( $traces as $trace ) : ?>
									<tr>
										<td><code><?php echo esc_html( $trace['timestamp'] ); ?></code></td>
										<td><strong><?php echo esc_html( $trace['link_slug'] ); ?></strong></td>
										<td><code><?php echo esc_html( $trace['dest_host'] ); ?></code></td>
										<td><span class="origin-highlight"><?php echo esc_html( $trace['journey_slug'] ); ?></span></td>
										<td><code><?php echo esc_html( $trace['original_url'] ); ?></code></td>
										<td><code class="result-url"><?php echo esc_html( $trace['modified_url'] ); ?></code></td>
										<td><code><?php echo esc_html( $trace['hook_used'] ); ?></code></td>
										<td><code><?php echo esc_html( (string) $trace['exec_time'] ); ?>ms</code></td>
										<td>
											<?php if ( ! empty( $trace['origin_added'] ) ) : ?>
												<span class="badge badge-success"><?php esc_html_e( 'Intercepted', 'affiliate-journey-tracker' ); ?></span>
											<?php else : ?>
												<span class="badge badge-warning"><?php echo esc_html( $trace['status'] ); ?></span>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<tr>
									<td colspan="9"><em><?php esc_html_e( 'No redirect trace entries recorded yet.', 'affiliate-journey-tracker' ); ?></em></td>
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
