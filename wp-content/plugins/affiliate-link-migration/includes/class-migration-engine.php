<?php
namespace AffiliateLinkMigration;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Migration Engine Class
 *
 * Core batch processor responsible for querying published posts in paginated memory-safe batches,
 * parsing DOM HTML content, replacing qualifying Site A Pretty Links with origin=auto,
 * and reporting real-time progress.
 */
class Migration_Engine {

	public const PROGRESS_OPTION = 'affiliate_lm_progress';

	public function init(): void {
		add_action( 'wp_ajax_affiliate_lm_run_batch', array( $this, 'ajax_run_batch' ) );
		add_action( 'wp_ajax_affiliate_lm_reset_migration', array( $this, 'ajax_reset_migration' ) );
	}

	/**
	 * AJAX Handler to execute a single batch iteration.
	 */
	public function ajax_run_batch(): void {
		check_ajax_referer( 'affiliate_lm_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'affiliate-link-migration' ) ) );
		}

		// Prevent PHP execution timeouts during long-running batch operations.
		if ( function_exists( 'set_time_limit' ) ) {
			@set_time_limit( 300 );
		}

		$progress = get_option( self::PROGRESS_OPTION, array() );

		if ( empty( $progress ) || ! is_array( $progress ) ) {
			$counts      = wp_count_posts( 'post' );
			$total_posts = isset( $counts->publish ) ? (int) $counts->publish : 0;
			$progress    = array(
				'processed_ids'   => array(),
				'total_posts'     => $total_posts,
				'updated_links'   => 0,
				'skipped_links'   => 0,
				'errors'          => 0,
				'start_timestamp' => time(),
			);
		}

		$batch_size    = (int) get_option( 'affiliate_lm_batch_size', 50 );
		$dry_run       = (bool) get_option( 'affiliate_lm_dry_run', 0 );
		$processed_ids = isset( $progress['processed_ids'] ) ? $progress['processed_ids'] : array();

		// Query next batch of published posts using memory-safe ID pagination.
		$args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => $batch_size,
			'post__not_in'   => ! empty( $processed_ids ) ? $processed_ids : array( 0 ),
			'orderby'        => 'ID',
			'order'          => 'ASC',
			'fields'         => 'ids',
		);

		$query     = new \WP_Query( $args );
		$batch_ids = $query->posts;

		$batch_processed = 0;
		$batch_updated   = 0;
		$batch_skipped   = 0;
		$batch_errors    = 0;

		if ( ! empty( $batch_ids ) ) {
			foreach ( $batch_ids as $post_id ) {
				$post_id = (int) $post_id;
				$post    = get_post( $post_id );
				if ( ! $post ) {
					continue;
				}

				$res = $this->process_post( $post, $dry_run );

				if ( $res['updated_count'] > 0 ) {
					$batch_updated += $res['updated_count'];
				} else {
					$batch_skipped ++;
				}

				if ( ! empty( $res['has_error'] ) ) {
					$batch_errors ++;
				}

				$processed_ids[] = $post_id;
				$batch_processed ++;
			}
		}

		// Update persistent progress.
		$progress['processed_ids'] = $processed_ids;
		$progress['updated_links'] += $batch_updated;
		$progress['skipped_links'] += $batch_skipped;
		$progress['errors']        += $batch_errors;

		update_option( self::PROGRESS_OPTION, $progress, false );

		// Calculate Statistics for UI update.
		$total_posts     = (int) $progress['total_posts'];
		$processed_count = count( $processed_ids );
		$remaining       = max( 0, $total_posts - $processed_count );
		$elapsed_sec     = time() - (int) $progress['start_timestamp'];
		$eta_sec         = ( $processed_count > 0 && $remaining > 0 ) ? (int) round( ( $elapsed_sec / $processed_count ) * $remaining ) : 0;

		$pct = ( $total_posts > 0 ) ? min( 100, (int) round( ( $processed_count / $total_posts ) * 100 ) ) : 100;

		wp_send_json_success(
			array(
				'batch_processed' => $batch_processed,
				'processed'       => $processed_count,
				'total'           => $total_posts,
				'remaining'       => $remaining,
				'updated_links'   => $progress['updated_links'],
				'skipped'         => $progress['skipped_links'],
				'errors'          => $progress['errors'],
				'percent'         => $pct,
				'elapsed_formatted' => $this->format_seconds( $elapsed_sec ),
				'eta_formatted'     => $this->format_seconds( $eta_sec ),
				'is_complete'     => ( $remaining === 0 || $batch_processed === 0 ),
				'dry_run'         => $dry_run,
			)
		);
	}

	/**
	 * Process a single post object and update qualifying links in HTML content.
	 *
	 * @param \WP_Post $post    Post object.
	 * @param bool     $dry_run Dry run flag.
	 * @return array Process summary.
	 */
	public function process_post( \WP_Post $post, bool $dry_run = false ): array {
		$content = $post->post_content;
		if ( empty( $content ) || false === strpos( $content, '<a' ) ) {
			return array(
				'updated_count' => 0,
				'has_error'     => false,
			);
		}

		$result = $this->transform_post_content( $content, $post->ID, $post->post_title, $dry_run );

		if ( $result['updated_count'] > 0 && ! $dry_run && ! empty( $result['new_content'] ) ) {
			$update_res = wp_update_post(
				array(
					'ID'           => $post->ID,
					'post_content' => $result['new_content'],
				)
			);

			if ( is_wp_error( $update_res ) ) {
				return array(
					'updated_count' => 0,
					'has_error'     => true,
				);
			}
		}

		return array(
			'updated_count' => $result['updated_count'],
			'has_error'     => false,
		);
	}

	/**
	 * DOM HTML Content Transformer.
	 *
	 * Uses WP_HTML_Tag_Processor or DOMDocument to find qualifying anchor elements
	 * and append origin=auto to href.
	 *
	 * @param string $content    Post HTML content.
	 * @param int    $post_id    Post ID.
	 * @param string $post_title Post title.
	 * @param bool   $dry_run    Dry run flag.
	 * @return array Transformation results.
	 */
	public function transform_post_content( string $content, int $post_id, string $post_title, bool $dry_run = false ): array {
		if ( class_exists( 'WP_HTML_Tag_Processor' ) ) {
			return $this->transform_with_tag_processor( $content, $post_id, $post_title, $dry_run );
		}
		return $this->transform_with_dom_document( $content, $post_id, $post_title, $dry_run );
	}

	/**
	 * Transform HTML using WP_HTML_Tag_Processor (WP 6.2+).
	 */
	private function transform_with_tag_processor( string $content, int $post_id, string $post_title, bool $dry_run ): array {
		$processor     = new \WP_HTML_Tag_Processor( $content );
		$updated_count = 0;

		while ( $processor->next_tag( 'A' ) ) {
			$href = $processor->get_attribute( 'href' );
			if ( empty( $href ) ) {
				continue;
			}

			if ( $this->is_qualifying_site_a_link( $href ) ) {
				$new_href = $this->append_origin_auto( $href );
				if ( $new_href !== $href ) {
					$processor->set_attribute( 'href', $new_href );
					$updated_count ++;
					Logger::log( $post_id, $post_title, $href, $new_href, $dry_run ? 'Dry Run' : 'Updated' );
				}
			}
		}

		return array(
			'updated_count' => $updated_count,
			'new_content'   => $updated_count > 0 ? $processor->get_updated_html() : $content,
		);
	}

	/**
	 * Transform HTML using DOMDocument fallback.
	 */
	private function transform_with_dom_document( string $content, int $post_id, string $post_title, bool $dry_run ): array {
		if ( ! class_exists( 'DOMDocument' ) ) {
			return array(
				'updated_count' => 0,
				'new_content'   => $content,
			);
		}

		$dom          = new \DOMDocument();
		$libxml_state = libxml_use_internal_errors( true );
		$encoded_html = '<?xml encoding="utf-8" ?>' . $content;
		@$dom->loadHTML( $encoded_html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		libxml_clear_errors();
		libxml_use_internal_errors( $libxml_state );

		$anchors       = $dom->getElementsByTagName( 'a' );
		$updated_count = 0;

		foreach ( $anchors as $anchor ) {
			if ( ! $anchor instanceof \DOMElement ) {
				continue;
			}

			$href = $anchor->getAttribute( 'href' );
			if ( empty( $href ) ) {
				continue;
			}

			if ( $this->is_qualifying_site_a_link( $href ) ) {
				$new_href = $this->append_origin_auto( $href );
				if ( $new_href !== $href ) {
					$anchor->setAttribute( 'href', $new_href );
					$updated_count ++;
					Logger::log( $post_id, $post_title, $href, $new_href, $dry_run ? 'Dry Run' : 'Updated' );
				}
			}
		}

		if ( $updated_count === 0 ) {
			return array(
				'updated_count' => 0,
				'new_content'   => $content,
			);
		}

		$output = $dom->saveHTML();
		$output = preg_replace( '/^<\?xml[^>]*\?>/i', '', $output );

		return array(
			'updated_count' => $updated_count,
			'new_content'   => ( null !== $output ) ? trim( $output ) : $content,
		);
	}

	/**
	 * Append origin=auto to href using add_query_arg().
	 *
	 * @param string $href Target URL.
	 * @return string Modified URL with origin=auto.
	 */
	public function append_origin_auto( string $href ): string {
		if ( function_exists( 'add_query_arg' ) ) {
			return add_query_arg( 'origin', 'auto', $href );
		}

		$separator = ( false !== strpos( $href, '?' ) ) ? '&' : '?';
		return $href . $separator . 'origin=auto';
	}

	/**
	 * Check if URL qualifies for origin=auto migration.
	 *
	 * RULES:
	 * 1. Must not be empty, anchor hash (#), javascript:, mailto:, tel:.
	 * 2. Must NOT be external, Amazon, eBay, wikipedia, etc.
	 * 3. Must match configured Site A domains.
	 * 4. Must NOT already contain origin=.
	 *
	 * @param string $url Target href URL.
	 * @return bool True if qualifying Site A link.
	 */
	public function is_qualifying_site_a_link( string $url ): bool {
		$url = trim( $url );
		if ( empty( $url ) || strpos( $url, '#' ) === 0 || strpos( $url, 'javascript:' ) === 0 || strpos( $url, 'mailto:' ) === 0 || strpos( $url, 'tel:' ) === 0 ) {
			return false;
		}

		// Exclude explicit external affiliate networks & non-site domains.
		$excluded = array( 'amazon.com', 'amzn.to', 'amazon.', 'ebay.com', 'wikipedia.org' );
		$lower    = strtolower( $url );
		foreach ( $excluded as $ex ) {
			if ( strpos( $lower, $ex ) !== false ) {
				return false;
			}
		}

		// Skip if URL already contains origin=
		if ( strpos( $lower, 'origin=' ) !== false ) {
			return false;
		}

		$parsed = wp_parse_url( $url );
		if ( empty( $parsed['host'] ) ) {
			return false;
		}

		$host        = strtolower( $parsed['host'] );
		$raw_domains = get_option( 'affiliate_lm_site_domains', "thebuyersreviews.com\ndailyhealthsupplement.com\nsupplementmag.com" );
		$lines       = explode( "\n", str_replace( "\r", "", $raw_domains ) );

		foreach ( $lines as $domain ) {
			$domain = strtolower( trim( $domain ) );
			if ( empty( $domain ) ) {
				continue;
			}

			if ( $host === $domain || substr( $host, -strlen( '.' . $domain ) ) === '.' . $domain ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Reset stored migration progress state.
	 */
	public function ajax_reset_migration(): void {
		check_ajax_referer( 'affiliate_lm_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Permission denied.', 'affiliate-link-migration' ) ) );
		}

		delete_option( self::PROGRESS_OPTION );
		wp_send_json_success( array( 'message' => __( 'Migration progress state reset successfully.', 'affiliate-link-migration' ) ) );
	}

	/**
	 * Format seconds into human-readable HH:MM:SS or MM:SS format.
	 *
	 * @param int $seconds Total seconds.
	 * @return string Formatted time.
	 */
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
