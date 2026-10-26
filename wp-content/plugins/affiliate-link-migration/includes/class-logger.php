<?php
namespace AffiliateLinkMigration;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Logger Class
 *
 * Handles recording migration modification logs, rendering the logs table,
 * clearing logs, and exporting logs to CSV.
 */
class Logger {

	public const OPTION_KEY = 'affiliate_lm_logs';
	public const MAX_LOGS   = 1000;

	public function init(): void {
		add_action( 'wp_ajax_affiliate_lm_clear_logs', array( $this, 'ajax_clear_logs' ) );
		add_action( 'wp_ajax_affiliate_lm_export_csv', array( $this, 'ajax_export_csv' ) );
	}

	/**
	 * Log a link modification entry.
	 *
	 * @param int    $post_id Post ID.
	 * @param string $title   Post Title.
	 * @param string $old_url Original URL.
	 * @param string $new_url Modified URL.
	 * @param string $status  Status string (Updated, Dry Run, Skipped, Error).
	 * @return bool True if logged.
	 */
	public static function log( int $post_id, string $title, string $old_url, string $new_url, string $status = 'Updated' ): bool {
		$enabled = function_exists( 'get_option' ) ? \get_option( 'affiliate_lm_enable_logs', 1 ) : 1;
		if ( ! $enabled ) {
			return false;
		}

		$entry = array(
			'timestamp'  => function_exists( 'current_time' ) ? \current_time( 'mysql' ) : date( 'Y-m-d H:i:s' ),
			'post_id'    => $post_id,
			'post_title' => sanitize_text_field( $title ),
			'old_url'    => sanitize_text_field( $old_url ),
			'new_url'    => sanitize_text_field( $new_url ),
			'status'     => sanitize_text_field( $status ),
		);

		if ( function_exists( 'get_option' ) && function_exists( 'update_option' ) ) {
			$logs = \get_option( self::OPTION_KEY, array() );
			if ( ! is_array( $logs ) ) {
				$logs = array();
			}

			array_unshift( $logs, $entry );

			if ( count( $logs ) > self::MAX_LOGS ) {
				$logs = array_slice( $logs, 0, self::MAX_LOGS );
			}

			\update_option( self::OPTION_KEY, $logs, false );
		}

		return true;
	}

	/**
	 * Get stored logs.
	 *
	 * @param int $limit Max records.
	 * @return array Logs array.
	 */
	public static function get_logs( int $limit = 500 ): array {
		if ( ! function_exists( 'get_option' ) ) {
			return array();
		}
		$logs = \get_option( self::OPTION_KEY, array() );
		if ( ! is_array( $logs ) ) {
			return array();
		}
		return array_slice( $logs, 0, $limit );
	}

	/**
	 * Clear all stored logs.
	 */
	public static function clear_logs(): bool {
		if ( function_exists( 'delete_option' ) ) {
			\delete_option( self::OPTION_KEY );
		}
		return true;
	}

	/**
	 * AJAX Handler to clear logs.
	 */
	public function ajax_clear_logs(): void {
		check_ajax_referer( 'affiliate_lm_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'affiliate-link-migration' ) ) );
		}

		self::clear_logs();
		wp_send_json_success( array( 'message' => __( 'Migration logs cleared successfully.', 'affiliate-link-migration' ) ) );
	}

	/**
	 * AJAX Handler to export logs to CSV.
	 */
	public function ajax_export_csv(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Permission denied.', 'affiliate-link-migration' ) );
		}

		$nonce = isset( $_GET['nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'affiliate_lm_nonce' ) ) {
			wp_die( __( 'Security check failed.', 'affiliate-link-migration' ) );
		}

		$logs     = self::get_logs( 1000 );
		$filename = 'affiliate-link-migration-log-' . date( 'Y-m-d-His' ) . '.csv';

		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );
		header( 'Pragma: no-cache' );
		header( 'Expires: 0' );

		$output = fopen( 'php://output', 'w' );
		fputcsv( $output, array( 'Timestamp', 'Post ID', 'Post Title', 'Original URL', 'Modified URL', 'Status' ) );

		foreach ( $logs as $log ) {
			fputcsv(
				$output,
				array(
					$log['timestamp'],
					$log['post_id'],
					$log['post_title'],
					$log['old_url'],
					$log['new_url'],
					$log['status'],
				)
			);
		}

		fclose( $output );
		exit;
	}

	/**
	 * Render Migration Logs Admin Page.
	 */
	public function render_logs_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$nonce = wp_create_nonce( 'affiliate_lm_nonce' );
		$logs  = self::get_logs( 500 );

		?>
		<div class="wrap affiliate-lm-wrap">
			<div style="display:flex; justify-content:space-between; align-items:center;">
				<h1><span class="dashicons dashicons-list-view"></span> <?php esc_html_e( 'Migration Logs & Export', 'affiliate-link-migration' ); ?></h1>
				<div>
					<a href="<?php echo esc_url( admin_url( 'admin-ajax.php?action=affiliate_lm_export_csv&nonce=' . $nonce ) ); ?>" class="button button-primary"><span class="dashicons dashicons-download" style="margin-top:4px;"></span> <?php esc_html_e( 'Export CSV', 'affiliate-link-migration' ); ?></a>
					<button type="button" id="lm-clear-logs-btn" class="button button-secondary" data-nonce="<?php echo esc_attr( $nonce ); ?>"><?php esc_html_e( 'Clear Logs', 'affiliate-link-migration' ); ?></button>
				</div>
			</div>
			<p><?php esc_html_e( 'Complete audit log recording every modified post ID, title, original link, modified link, timestamp, and migration status.', 'affiliate-link-migration' ); ?></p>

			<div id="lm-log-message"></div>

			<!-- Log Table -->
			<div class="affiliate-lm-card full-width" style="margin-top:15px;">
				<div class="log-table-container">
					<table class="widefat fixed striped log-table">
						<thead>
							<tr>
								<th style="width:140px;"><?php esc_html_e( 'Timestamp', 'affiliate-link-migration' ); ?></th>
								<th style="width:80px;"><?php esc_html_e( 'Post ID', 'affiliate-link-migration' ); ?></th>
								<th style="width:200px;"><?php esc_html_e( 'Post Title', 'affiliate-link-migration' ); ?></th>
								<th><?php esc_html_e( 'Original URL', 'affiliate-link-migration' ); ?></th>
								<th><?php esc_html_e( 'Modified URL (origin=auto)', 'affiliate-link-migration' ); ?></th>
								<th style="width:100px;"><?php esc_html_e( 'Status', 'affiliate-link-migration' ); ?></th>
							</tr>
						</thead>
						<tbody id="lm-log-tbody">
							<?php if ( ! empty( $logs ) ) : ?>
								<?php foreach ( $logs as $log ) : ?>
									<tr>
										<td><code><?php echo esc_html( $log['timestamp'] ); ?></code></td>
										<td><code>#<?php echo esc_html( (string) $log['post_id'] ); ?></code></td>
										<td><strong><?php echo esc_html( $log['post_title'] ); ?></strong></td>
										<td><code><?php echo esc_html( $log['old_url'] ); ?></code></td>
										<td><code class="result-url"><?php echo esc_html( $log['new_url'] ); ?></code></td>
										<td>
											<?php if ( $log['status'] === 'Updated' ) : ?>
												<span class="badge badge-success"><?php esc_html_e( 'Updated', 'affiliate-link-migration' ); ?></span>
											<?php elseif ( $log['status'] === 'Dry Run' ) : ?>
												<span class="badge badge-info"><?php esc_html_e( 'Dry Run', 'affiliate-link-migration' ); ?></span>
											<?php else : ?>
												<span class="badge badge-warning"><?php echo esc_html( $log['status'] ); ?></span>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<tr>
									<td colspan="6"><em><?php esc_html_e( 'No migration log entries recorded yet.', 'affiliate-link-migration' ); ?></em></td>
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
