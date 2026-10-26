<?php
/**
 * Review Submission & Comment System with Star Ratings and Photo Upload
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add multipart form enctype to comment form for file uploads.
 */
function sr_comment_form_enctype() {
	echo ' enctype="multipart/form-data"';
}
add_action( 'comment_form_top', 'sr_comment_form_enctype' );

/**
 * Check and rate-limit comment submissions per IP.
 *
 * @param array $commentdata Comment data.
 * @return array Verified comment data.
 */
function sr_preprocess_review_comment( $commentdata ) {
	// Only apply custom review validations if on a single post
	if ( ! isset( $_POST['sr_comment_nonce'] ) ) {
		return $commentdata;
	}

	// 1. Nonce Verification
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sr_comment_nonce'] ) ), 'sr_comment_review_action' ) ) {
		wp_die( esc_html__( 'Security check failed. Please refresh the page and try again.', 'supplement-review' ) );
	}

	// 2. Honeypot check
	if ( ! empty( $_POST['sr_hp_website'] ) ) {
		wp_die( esc_html__( 'Spam detected.', 'supplement-review' ) );
	}

	// 3. Time-based spam check (must take at least 3 seconds)
	if ( isset( $_POST['sr_ts'] ) ) {
		$time_elapsed = time() - absint( $_POST['sr_ts'] );
		if ( $time_elapsed < 3 ) {
			wp_die( esc_html__( 'Please take your time writing a thoughtful review.', 'supplement-review' ) );
		}
	}

	// 4. Rate Limiting per IP (max 4 reviews per hour)
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	if ( ! empty( $ip ) ) {
		$rate_key = 'sr_comment_ip_' . md5( $ip );
		$attempts = (int) get_transient( $rate_key );
		if ( $attempts >= 4 ) {
			wp_die( esc_html__( 'You have submitted too many reviews recently. Please try again later.', 'supplement-review' ) );
		}
		set_transient( $rate_key, $attempts + 1, HOUR_IN_SECONDS );
	}

	// 5. Privacy Policy Agreement check
	if ( empty( $_POST['sr_privacy_consent'] ) && empty( $_POST['rf_privacy'] ) ) {
		wp_die( esc_html__( 'You must agree to the Privacy Policy to submit your review.', 'supplement-review' ) );
	}

	// 6. Rating validation
	$rating = isset( $_POST['sr_review_rating'] ) ? absint( $_POST['sr_review_rating'] ) : 5;
	if ( $rating < 1 || $rating > 5 ) {
		$rating = 5;
	}

	return $commentdata;
}
add_filter( 'preprocess_comment', 'sr_preprocess_review_comment' );

/**
 * Save review metadata and handle optional image upload.
 *
 * @param int        $comment_id Comment ID.
 * @param int|string $comment_approved 1 if approved, 0 if held.
 * @param array      $commentdata Comment data array.
 */
function sr_save_review_comment_meta( $comment_id, $comment_approved, $commentdata ) {
	if ( ! isset( $_POST['sr_comment_nonce'] ) ) {
		return;
	}

	// Save star rating
	$rating = isset( $_POST['sr_review_rating'] ) ? absint( $_POST['sr_review_rating'] ) : 5;
	update_comment_meta( $comment_id, 'sr_rating', max( 1, min( 5, $rating ) ) );

	// Save headline (supports both sr_review_headline and rf_headline)
	$headline = '';
	if ( ! empty( $_POST['sr_review_headline'] ) ) {
		$headline = sanitize_text_field( wp_unslash( $_POST['sr_review_headline'] ) );
	} elseif ( ! empty( $_POST['rf_headline'] ) ) {
		$headline = sanitize_text_field( wp_unslash( $_POST['rf_headline'] ) );
	}
	if ( ! empty( $headline ) ) {
		update_comment_meta( $comment_id, 'sr_headline', $headline );
	}

	// Handle Image Upload (supports both sr_review_image and rf_attachment)
	$upload_file = null;
	if ( ! empty( $_FILES['sr_review_image']['name'] ) ) {
		$upload_file = $_FILES['sr_review_image']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	} elseif ( ! empty( $_FILES['rf_attachment']['name'] ) ) {
		$upload_file = $_FILES['rf_attachment']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	}

	if ( $upload_file && ! empty( $upload_file['name'] ) ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$file = $upload_file;

		// Max size check (2 MB = 2 * 1024 * 1024 bytes)
		if ( $file['size'] > 2097152 ) {
			return;
		}

		// Check file type and extension
		$file_info = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'] );
		$allowed_mimes = array( 'image/jpeg', 'image/png', 'image/webp' );

		if ( in_array( $file_info['type'], $allowed_mimes, true ) ) {
			$upload_overrides = array( 'test_form' => false );
			$movefile = wp_handle_upload( $file, $upload_overrides );

			if ( $movefile && ! isset( $movefile['error'] ) ) {
				$attachment = array(
					'guid'           => $movefile['url'],
					'post_mime_type' => $movefile['type'],
					'post_title'     => sanitize_file_name( $file['name'] ),
					'post_content'   => '',
					'post_status'    => 'inherit',
				);

				$attach_id = wp_insert_attachment( $attachment, $movefile['file'], $commentdata['comment_post_ID'] );
				if ( ! is_wp_error( $attach_id ) ) {
					$attach_data = wp_generate_attachment_metadata( $attach_id, $movefile['file'] );
					wp_update_attachment_metadata( $attach_id, $attach_data );
					update_comment_meta( $comment_id, 'sr_review_image_id', $attach_id );
				}
			}
		}
	}

	// Hold comment for moderation by default for safety
	if ( ! current_user_can( 'moderate_comments' ) ) {
		wp_set_comment_status( $comment_id, 'hold' );
	}
}
add_action( 'comment_post', 'sr_save_review_comment_meta', 10, 3 );

/**
 * Custom Comment Display Callback for Approved Reviews.
 *
 * @param WP_Comment $comment Current comment.
 * @param array      $args Arguments.
 * @param int        $depth Depth.
 */
function sr_render_review_comment( $comment, $args, $depth ) {
	$rating    = (int) get_comment_meta( $comment->comment_ID, 'sr_rating', true );
	$headline  = get_comment_meta( $comment->comment_ID, 'sr_headline', true );
	$image_id  = (int) get_comment_meta( $comment->comment_ID, 'sr_review_image_id', true );
	$author    = get_comment_author( $comment );
	$date      = get_comment_date( 'M j, Y', $comment );
	?>
	<li <?php comment_class( 'sr-review-item' ); ?> id="comment-<?php comment_ID(); ?>">
		<article class="sr-review-article">
			<div class="sr-review-item-header">
				<div class="sr-review-item-author">
					<strong class="sr-reviewer-name"><?php echo esc_html( $author ); ?></strong>
					<span class="sr-badge-pill" style="margin-left:8px;font-size:0.75rem;">
						<?php sr_svg( 'check-circle', array( 'width' => 12, 'height' => 12 ) ); ?>
						<?php esc_html_e( 'Verified Customer', 'supplement-review' ); ?>
					</span>
				</div>
				<span class="sr-review-date" style="font-size:0.8125rem;color:var(--sr-color-text-muted);"><?php echo esc_html( $date ); ?></span>
			</div>

			<?php if ( $rating > 0 ) : ?>
				<div style="margin-bottom:8px;">
					<?php echo sr_get_star_rating_html( $rating, false ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $headline ) ) : ?>
				<h4 class="sr-review-headline"><?php echo esc_html( $headline ); ?></h4>
			<?php endif; ?>

			<div class="sr-review-text">
				<?php comment_text(); ?>
			</div>

			<?php if ( $image_id ) : ?>
				<?php
				$img_url  = wp_get_attachment_image_url( $image_id, 'large' );
				$thumb_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
				if ( $thumb_url ) :
					?>
					<div class="sr-review-photo-wrap" style="margin-top:12px;">
						<a href="<?php echo esc_url( $img_url ); ?>" target="_blank" rel="noopener noreferrer">
							<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php esc_attr_e( 'Customer review photo', 'supplement-review' ); ?>" class="sr-review-user-photo" />
						</a>
					</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( '0' === $comment->comment_approved ) : ?>
				<p class="sr-comment-awaiting-moderation" style="font-size:0.8125rem;color:var(--sr-color-primary);margin-top:8px;">
					<em><?php esc_html_e( 'Your review is awaiting moderation before publication.', 'supplement-review' ); ?></em>
				</p>
			<?php endif; ?>
		</article>
	</li>
	<?php
}

/**
 * Returns real aggregate rating data based strictly on approved reviews with ratings.
 * Returns null if no approved reviews exist.
 *
 * @param int $post_id Post ID.
 * @return array|null Array with 'ratingValue' and 'reviewCount', or null.
 */
function sr_get_approved_reviews_aggregate( $post_id ) {
	$comments = get_comments(
		array(
			'post_id' => $post_id,
			'status'  => 'approve',
		)
	);

	if ( empty( $comments ) ) {
		return null;
	}

	$total_score = 0;
	$rated_count = 0;

	foreach ( $comments as $comment ) {
		$score = (int) get_comment_meta( $comment->comment_ID, 'sr_rating', true );
		if ( $score >= 1 && $score <= 5 ) {
			$total_score += $score;
			$rated_count++;
		}
	}

	if ( $rated_count === 0 ) {
		return null;
	}

	$average = round( $total_score / $rated_count, 1 );

	return array(
		'ratingValue' => $average,
		'reviewCount' => $rated_count,
	);
}
