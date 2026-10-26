<?php
namespace AffiliateJourneyTracker;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Migration Class
 *
 * One-time idempotent batch migration tool populating the `affiliate_content_type` post meta field.
 */
class Migration {

	/**
	 * Meta key identifier.
	 */
	public const META_KEY = 'affiliate_content_type';

	/**
	 * Initialize hooks.
	 */
	public function init(): void {
		add_action( 'wp_ajax_affiliate_jt_run_migration_batch', array( $this, 'ajax_run_migration_batch' ) );
	}

	/**
	 * Retrieve migration statistical counters.
	 *
	 * @return array Stats breakdown.
	 */
	public static function get_stats(): array {
		$total_posts_args = array(
			'post_type'      => 'post',
			'post_status'    => array( 'publish', 'draft', 'pending', 'future', 'private' ),
			'posts_per_page' => -1,
			'fields'         => 'ids',
		);
		$total_ids   = get_posts( $total_posts_args );
		$total_count = count( $total_ids );

		$classified_args = array(
			'post_type'      => 'post',
			'post_status'    => array( 'publish', 'draft', 'pending', 'future', 'private' ),
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'     => self::META_KEY,
					'compare' => 'EXISTS',
				),
			),
		);
		$classified_ids   = get_posts( $classified_args );
		$classified_count = count( $classified_ids );

		$remaining_count = max( 0, $total_count - $classified_count );

		return array(
			'total'      => $total_count,
			'classified' => $classified_count,
			'remaining'  => $remaining_count,
		);
	}

	/**
	 * AJAX Handler for executing one migration batch of 100 posts.
	 */
	public function ajax_run_migration_batch(): void {
		check_ajax_referer( 'affiliate_jt_debug_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'affiliate-journey-tracker' ) ) );
		}

		$batch_size = 100;
		$results    = $this->process_batch( $batch_size );
		$stats      = self::get_stats();

		wp_send_json_success(
			array(
				'batch_processed' => $results['processed'],
				'top_assigned'    => $results['top_assigned'],
				'review_assigned' => $results['review_assigned'],
				'skipped'         => $results['skipped'],
				'errors'          => $results['errors'],
				'stats'           => $stats,
			)
		);
	}

	/**
	 * Process a batch of unclassified posts.
	 *
	 * IDEMPOTENT BUSINESS RULES:
	 * - Only process posts missing `affiliate_content_type`.
	 * - If slug contains "top" or "best" -> set `top_listing`.
	 * - Otherwise -> set `individual_review`.
	 * - Never overwrite existing meta.
	 *
	 * @param int $batch_size Number of posts to process in batch.
	 * @return array Batch execution results.
	 */
	public function process_batch( int $batch_size = 100 ): array {
		$unclassified_args = array(
			'post_type'      => 'post',
			'post_status'    => array( 'publish', 'draft', 'pending', 'future', 'private' ),
			'posts_per_page' => $batch_size,
			'meta_query'     => array(
				array(
					'key'     => self::META_KEY,
					'compare' => 'NOT EXISTS',
				),
			),
		);

		$posts_to_migrate = get_posts( $unclassified_args );

		$processed       = 0;
		$top_assigned    = 0;
		$review_assigned = 0;
		$skipped         = 0;
		$errors          = 0;

		foreach ( $posts_to_migrate as $post ) {
			if ( ! $post instanceof \WP_Post ) {
				continue;
			}

			// IDEMPOTENCY CHECK: If post meta already exists, skip it.
			$existing_meta = get_post_meta( $post->ID, self::META_KEY, true );
			if ( ! empty( $existing_meta ) ) {
				$skipped++;
				continue;
			}

			$slug = strtolower( $post->post_name );

			// CLASSIFICATION RULE: WordPress category 'Listicle' OR slug keywords.
			if ( is_listicle( $post ) || strpos( $slug, 'top' ) !== false || strpos( $slug, 'best' ) !== false ) {
				$meta_value = 'top_listing';
				$top_assigned++;
			} else {
				$meta_value = 'individual_review';
				$review_assigned++;
			}

			$updated = update_post_meta( $post->ID, self::META_KEY, $meta_value );

			if ( false !== $updated ) {
				$processed++;
				Logger::log(
					sprintf(
						'Migration: Post ID %d ("%s") classified as %s',
						$post->ID,
						$slug,
						$meta_value
					),
					'info'
				);
			} else {
				$errors++;
				Logger::log(
					sprintf( 'Migration Error: Failed to update post ID %d', $post->ID ),
					'error'
				);
			}
		}

		return array(
			'processed'       => $processed,
			'top_assigned'    => $top_assigned,
			'review_assigned' => $review_assigned,
			'skipped'         => $skipped,
			'errors'          => $errors,
		);
	}

	/**
	 * Render Migration Admin Page HTML.
	 */
	public function render_migration_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$stats = self::get_stats();
		$nonce = wp_create_nonce( 'affiliate_jt_debug_nonce' );

		$pct = ( $stats['total'] > 0 ) ? round( ( $stats['classified'] / $stats['total'] ) * 100, 1 ) : 100;

		?>
		<div class="wrap affiliate-jt-debug-wrap">
			<h1><span class="dashicons dashicons-update"></span> <?php esc_html_e( 'Affiliate Journey Migration Tool', 'affiliate-journey-tracker' ); ?></h1>
			<p><?php esc_html_e( 'One-time automatic migration tool populating the affiliate_content_type post meta field across all posts.', 'affiliate-journey-tracker' ); ?></p>

			<!-- Migration Stats Overview Cards -->
			<div class="affiliate-jt-grid">
				<div class="affiliate-jt-card">
					<h2><span class="dashicons dashicons-admin-post"></span> <?php esc_html_e( 'Total Posts', 'affiliate-journey-tracker' ); ?></h2>
					<p class="stat-number" id="jt-mig-stat-total"><?php echo esc_html( (string) $stats['total'] ); ?></p>
				</div>
				<div class="affiliate-jt-card">
					<h2><span class="dashicons dashicons-yes-alt"></span> <?php esc_html_e( 'Already Classified', 'affiliate-journey-tracker' ); ?></h2>
					<p class="stat-number stat-success" id="jt-mig-stat-classified"><?php echo esc_html( (string) $stats['classified'] ); ?></p>
				</div>
				<div class="affiliate-jt-card">
					<h2><span class="dashicons dashicons-hourglass"></span> <?php esc_html_e( 'Remaining', 'affiliate-journey-tracker' ); ?></h2>
					<p class="stat-number stat-warning" id="jt-mig-stat-remaining"><?php echo esc_html( (string) $stats['remaining'] ); ?></p>
				</div>
			</div>

			<!-- Progress Bar & Run Action -->
			<div class="affiliate-jt-card full-width" style="margin-top:20px;">
				<h2><span class="dashicons dashicons-controls-play"></span> <?php esc_html_e( 'Batch Migration Execution', 'affiliate-journey-tracker' ); ?></h2>

				<div class="progress-bar-container" style="margin-top:15px; background:#e2e8f0; border-radius:6px; height:24px; overflow:hidden; position:relative;">
					<div id="jt-mig-progress-inner" style="width:<?php echo esc_attr( $pct ); ?>%; background:#10b981; height:100%; transition:width 0.4s ease;"></div>
					<span id="jt-mig-progress-text" style="position:absolute; top:0; left:0; width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-weight:bold; font-size:12px; color:#1e293b;">
						<?php echo esc_html( $pct . '%' ); ?>
					</span>
				</div>

				<div style="margin-top:20px;">
					<button type="button" id="jt-run-migration-btn" class="button button-primary button-hero" data-nonce="<?php echo esc_attr( $nonce ); ?>" <?php disabled( 0, $stats['remaining'] ); ?>>
						<span class="dashicons dashicons-update" style="vertical-align:middle; margin-top:-2px;"></span>
						<?php esc_html_e( 'Run Migration', 'affiliate-journey-tracker' ); ?>
					</button>
				</div>

				<!-- Completion Summary Card -->
				<div id="jt-mig-summary-card" class="url-result-panel" style="margin-top:20px; display:none;">
					<h3><span class="dashicons dashicons-saved"></span> <?php esc_html_e( 'Migration Complete', 'affiliate-journey-tracker' ); ?></h3>
					<ul style="margin-left:20px; list-style:disc;">
						<li><strong><?php esc_html_e( 'Top Listings Assigned', 'affiliate-journey-tracker' ); ?>:</strong> <span id="jt-summary-top">0</span></li>
						<li><strong><?php esc_html_e( 'Individual Reviews Assigned', 'affiliate-journey-tracker' ); ?>:</strong> <span id="jt-summary-reviews">0</span></li>
						<li><strong><?php esc_html_e( 'Skipped (Already Classified)', 'affiliate-journey-tracker' ); ?>:</strong> <span id="jt-summary-skipped">0</span></li>
						<li><strong><?php esc_html_e( 'Errors', 'affiliate-journey-tracker' ); ?>:</strong> <span id="jt-summary-errors">0</span></li>
					</ul>
				</div>
			</div>

		</div>
		<?php
	}
}
