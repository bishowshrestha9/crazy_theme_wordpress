<?php
/**
 * The template for displaying reviews and the review submission form
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="sr-reviews-area">

	<?php if ( have_comments() ) : ?>
		<h3 class="sr-reviews-area-title">
			<?php
			$comment_count = get_comments_number();
			echo wp_kses(
				sprintf(
					/* translators: 1: Number of reviews, 2: Post title */
					_nx( '%1$s Review for %2$s', '%1$s Reviews for %2$s', $comment_count, 'comments title', 'supplement-review' ),
					number_format_i18n( $comment_count ),
					'<span>' . esc_html( get_the_title() ) . '</span>'
				),
				array(
					'span' => array(),
				)
			);
			?>
		</h3>

		<ul class="sr-reviews-list">
			<?php
			wp_list_comments(
				array(
					'callback' => 'sr_render_review_comment',
					'style'    => 'ul',
					'type'     => 'comment',
				)
			);
			?>
		</ul>

		<?php
		the_comments_navigation(
			array(
				'prev_text' => '&larr; ' . esc_html__( 'Older Reviews', 'supplement-review' ),
				'next_text' => esc_html__( 'Newer Reviews', 'supplement-review' ) . ' &rarr;',
			)
		);
		?>

	<?php endif; ?>

	<?php if ( comments_open() ) : ?>
		<div class="sr-review-form-card rf-form-wrapper" id="rfFormWrapper">
			<!-- 1. Section heading with thin light-grey divider line -->
			<div class="rf-header">
				<h3 class="rf-heading"><?php esc_html_e( 'Write a Review', 'supplement-review' ); ?></h3>
				<hr class="rf-divider" aria-hidden="true" />
			</div>

			<form action="<?php echo esc_url( site_url( '/wp-comments-post.php' ) ); ?>" method="post" id="commentform" class="rf-form sr-review-form" enctype="multipart/form-data">

				<!-- Security Nonce & Spam Prevention -->
				<?php wp_nonce_field( 'sr_comment_review_action', 'sr_comment_nonce' ); ?>
				<input type="hidden" name="sr_ts" value="<?php echo esc_attr( time() ); ?>" />
				<input type="hidden" name="sr_review_rating" id="srReviewRatingInput" value="5" />
				<!-- Honeypot field (hidden from legitimate users) -->
				<div style="display:none !important;" aria-hidden="true">
					<label for="sr_hp_website"><?php esc_html_e( 'Leave this field blank', 'supplement-review' ); ?></label>
					<input type="text" name="sr_hp_website" id="sr_hp_website" tabindex="-1" autocomplete="off" />
				</div>

				<!-- 2. Review Headline * (Single-line input) -->
				<div class="rf-field">
					<label class="rf-label" for="sr_review_headline">
						<?php esc_html_e( 'Review Headline', 'supplement-review' ); ?> <span class="rf-required-star" aria-hidden="true">*</span>
					</label>
					<input
						type="text"
						id="sr_review_headline"
						name="sr_review_headline"
						class="rf-input"
						placeholder="<?php esc_attr_e( 'Enter the headline of the review', 'supplement-review' ); ?>"
						required
					/>
				</div>

				<!-- 3. Two columns on one row: Name * and Email * -->
				<?php
				$current_user = wp_get_current_user();
				$user_name    = ( $current_user && $current_user->exists() ) ? $current_user->display_name : '';
				$user_email   = ( $current_user && $current_user->exists() ) ? $current_user->user_email : '';
				?>
				<div class="rf-row">
					<div class="rf-col rf-field">
						<label class="rf-label" for="author">
							<?php esc_html_e( 'Name', 'supplement-review' ); ?> <span class="rf-required-star" aria-hidden="true">*</span>
						</label>
						<input
							type="text"
							id="author"
							name="author"
							class="rf-input"
							value="<?php echo esc_attr( $user_name ); ?>"
							placeholder="<?php esc_attr_e( 'Enter your name', 'supplement-review' ); ?>"
							required
							autocomplete="name"
						/>
					</div>

					<div class="rf-col rf-field">
						<label class="rf-label" for="email">
							<?php esc_html_e( 'Email', 'supplement-review' ); ?> <span class="rf-required-star" aria-hidden="true">*</span>
						</label>
						<input
							type="email"
							id="email"
							name="email"
							class="rf-input"
							value="<?php echo esc_attr( $user_email ); ?>"
							placeholder="<?php esc_attr_e( 'Enter your email', 'supplement-review' ); ?>"
							required
							autocomplete="email"
						/>
					</div>
				</div>

				<!-- 4. Review Headline * (Large Textarea, matching screenshot label) -->
				<div class="rf-field">
					<label class="rf-label" for="comment">
						<?php esc_html_e( 'Review Headline', 'supplement-review' ); ?> <span class="rf-required-star" aria-hidden="true">*</span>
					</label>
					<textarea
						id="comment"
						name="comment"
						class="rf-textarea"
						placeholder="<?php esc_attr_e( 'Please share your experience about this product to help others learn about it.', 'supplement-review' ); ?>"
						required
					></textarea>
				</div>

				<!-- 5. Upload attachment -->
				<div class="rf-field">
					<label class="rf-label" for="sr_review_image">
						<?php esc_html_e( 'Upload attachment', 'supplement-review' ); ?>
					</label>
					<input
						type="file"
						id="sr_review_image"
						name="sr_review_image"
						class="rf-input-file"
						accept="image/png,image/jpeg,image/webp"
					/>
				</div>

				<!-- 6. Full-width Sage Green Submit Button -->
				<button type="submit" name="submit" id="submit" class="rf-submit-btn">
					<span class="rf-btn-text"><?php esc_html_e( 'Submit Your Review', 'supplement-review' ); ?></span>
				</button>

				<!-- 7. Privacy Policy Checkbox -->
				<div class="rf-checkbox-wrap">
					<input
						type="checkbox"
						id="sr_privacy_consent"
						name="sr_privacy_consent"
						value="1"
						class="rf-checkbox"
						required
					/>
					<label for="sr_privacy_consent" class="rf-checkbox-label">
						<?php esc_html_e( 'Privacy Policy', 'supplement-review' ); ?>
					</label>
				</div>

				<!-- 8. Submission Disclaimer with Underlined Links -->
				<p class="rf-disclaimer">
					<?php
					printf(
						/* translators: 1: Review Guidelines link, 2: Terms of Use link */
						__( 'Submitting this review means that you agree to our %1$s, confirming that you are a verified customer who has purchased the product and may have used the merchandise or experienced the service, and providing only a real interaction and experience without ulterior motives or has an affiliate or business with the company in any way. By ticking this box and submitting this review, you also accept that submitting fake reviews is a violation of Consumer Health Digest\'s %2$s and such conduct will not be tolerated.', 'supplement-review' ),
						'<a href="' . esc_url( home_url( '/review-guidelines/' ) ) . '" class="rf-link">' . esc_html__( 'Review Guidelines', 'supplement-review' ) . '</a>',
						'<a href="' . esc_url( home_url( '/terms-of-use/' ) ) . '" class="rf-link">' . esc_html__( 'Terms of Use', 'supplement-review' ) . '</a>'
					);
					?>
				</p>

				<?php comment_id_fields(); ?>
				<?php do_action( 'comment_form', get_the_ID() ); ?>
			</form>
		</div>
	<?php endif; ?>

</div><!-- #comments -->
