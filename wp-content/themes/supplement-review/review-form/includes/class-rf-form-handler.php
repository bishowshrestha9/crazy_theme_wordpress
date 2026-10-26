<?php
/**
 * Review Submission Form Renderer and Request Handler
 *
 * Handles shortcode [review_form], AJAX submissions, no-JS fallback,
 * input validation, secure file uploads, rate limiting, and honeypot spam protection.
 *
 * @package Review_Form_Plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RF_Form_Handler {

	/**
	 * Initialize form handlers and shortcode.
	 */
	public static function init() {
		add_shortcode( 'review_form', array( __CLASS__, 'render_shortcode' ) );

		// AJAX Submission hooks
		add_action( 'wp_ajax_rf_submit_review', array( __CLASS__, 'handle_submission_ajax' ) );
		add_action( 'wp_ajax_nopriv_rf_submit_review', array( __CLASS__, 'handle_submission_ajax' ) );

		// No-JS admin-post fallback hooks
		add_action( 'admin_post_rf_submit_review', array( __CLASS__, 'handle_submission_fallback' ) );
		add_action( 'admin_post_nopriv_rf_submit_review', array( __CLASS__, 'handle_submission_fallback' ) );
	}

	/**
	 * Shortcode callback to render the form.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public static function render_shortcode( $atts = array() ) {
		// Enqueue frontend assets on demand
		wp_enqueue_style( 'rf-form-style' );
		wp_enqueue_script( 'rf-form-script' );

		ob_start();
		self::render_form_html();
		return ob_get_clean();
	}

	/**
	 * Render the exact pixel-accurate form HTML.
	 */
	public static function render_form_html() {
		// Check for status query args from no-JS fallback redirect
		$fallback_status = isset( $_GET['rf_status'] ) ? sanitize_text_field( wp_unslash( $_GET['rf_status'] ) ) : '';
		$fallback_msg    = isset( $_GET['rf_msg'] ) ? sanitize_text_field( wp_unslash( $_GET['rf_msg'] ) ) : '';
		?>
		<div class="rf-form-wrapper" id="rfFormWrapper">
			<!-- Inline Alert Feedback Messages -->
			<div class="rf-alert rf-alert-success <?php echo ( 'success' === $fallback_status ) ? 'rf-show' : ''; ?>" role="status" aria-live="polite">
				<?php
				if ( 'success' === $fallback_status && ! empty( $fallback_msg ) ) {
					echo esc_html( $fallback_msg );
				} else {
					esc_html_e( 'Thank you! Your review has been submitted and is pending verification.', 'review-form-plugin' );
				}
				?>
			</div>

			<div class="rf-alert rf-alert-error <?php echo ( 'error' === $fallback_status ) ? 'rf-show' : ''; ?>" role="alert" aria-live="assertive">
				<?php
				if ( 'error' === $fallback_status && ! empty( $fallback_msg ) ) {
					echo esc_html( $fallback_msg );
				}
				?>
			</div>

			<!-- 1. Section Heading with Divider -->
			<div class="rf-header">
				<h3 class="rf-heading"><?php esc_html_e( 'Write a Review', 'review-form-plugin' ); ?></h3>
				<hr class="rf-divider" aria-hidden="true" />
			</div>

			<!-- Form Container -->
			<form class="rf-form" method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
				<input type="hidden" name="action" value="rf_submit_review" />
				<?php wp_nonce_field( 'rf_submit_nonce_action', 'rf_nonce' ); ?>

				<!-- Anti-spam Honeypot Field -->
				<input type="text" name="rf_hp_website" value="" class="rf-hp" tabindex="-1" autocomplete="off" aria-hidden="true" />

				<!-- 2. Review Headline (Input) -->
				<div class="rf-field">
					<label class="rf-label" for="rf_headline">
						<?php esc_html_e( 'Review Headline', 'review-form-plugin' ); ?>
						<span class="rf-required-star" aria-hidden="true">*</span>
					</label>
					<input
						type="text"
						id="rf_headline"
						name="rf_headline"
						class="rf-input"
						placeholder="<?php esc_attr_e( 'Enter the headline of the review', 'review-form-plugin' ); ?>"
						required
					/>
				</div>

				<!-- 3. Two columns on one row: Name and Email -->
				<div class="rf-row">
					<div class="rf-col rf-field">
						<label class="rf-label" for="rf_name">
							<?php esc_html_e( 'Name', 'review-form-plugin' ); ?>
							<span class="rf-required-star" aria-hidden="true">*</span>
						</label>
						<input
							type="text"
							id="rf_name"
							name="rf_name"
							class="rf-input"
							placeholder="<?php esc_attr_e( 'Enter your name', 'review-form-plugin' ); ?>"
							required
							autocomplete="name"
						/>
					</div>

					<div class="rf-col rf-field">
						<label class="rf-label" for="rf_email">
							<?php esc_html_e( 'Email', 'review-form-plugin' ); ?>
							<span class="rf-required-star" aria-hidden="true">*</span>
						</label>
						<input
							type="email"
							id="rf_email"
							name="rf_email"
							class="rf-input"
							placeholder="<?php esc_attr_e( 'Enter your email', 'review-form-plugin' ); ?>"
							required
							autocomplete="email"
						/>
					</div>
				</div>

				<!-- 4. Review Headline (Textarea, exactly matching screenshot) -->
				<div class="rf-field">
					<label class="rf-label" for="rf_review_text">
						<?php esc_html_e( 'Review Headline', 'review-form-plugin' ); ?>
						<span class="rf-required-star" aria-hidden="true">*</span>
					</label>
					<textarea
						id="rf_review_text"
						name="rf_review_text"
						class="rf-textarea"
						placeholder="<?php esc_attr_e( 'Please share your experience about this product to help others learn about it.', 'review-form-plugin' ); ?>"
						required
					></textarea>
				</div>

				<!-- 5. Upload Attachment Field -->
				<div class="rf-field">
					<label class="rf-label" for="rf_attachment">
						<?php esc_html_e( 'Upload attachment', 'review-form-plugin' ); ?>
					</label>
					<input
						type="file"
						id="rf_attachment"
						name="rf_attachment"
						class="rf-input-file"
						accept="image/png,image/jpeg,image/webp"
					/>
				</div>

				<!-- 6. Submit Your Review Button -->
				<button type="submit" class="rf-submit-btn">
					<span class="rf-btn-text"><?php esc_html_e( 'Submit Your Review', 'review-form-plugin' ); ?></span>
				</button>

				<!-- 7. Privacy Policy Checkbox -->
				<div class="rf-checkbox-wrap">
					<input
						type="checkbox"
						id="rf_privacy"
						name="rf_privacy"
						value="1"
						class="rf-checkbox"
						required
					/>
					<label for="rf_privacy" class="rf-checkbox-label">
						<?php esc_html_e( 'Privacy Policy', 'review-form-plugin' ); ?>
					</label>
				</div>

				<!-- 8. Detailed Review Agreement Paragraph -->
				<p class="rf-disclaimer">
					<?php
					printf(
						/* translators: 1: Review Guidelines link, 2: Terms of Use link */
						__( 'Submitting this review means that you agree to our %1$s, confirming that you are a verified customer who has purchased the product and may have used the merchandise or experienced the service, and providing only a real interaction and experience without ulterior motives or has an affiliate or business with the company in any way. By ticking this box and submitting this review, you also accept that submitting fake reviews is a violation of Consumer Health Digest\'s %2$s and such conduct will not be tolerated.', 'review-form-plugin' ),
						'<a href="#review-guidelines" class="rf-link">' . esc_html__( 'Review Guidelines', 'review-form-plugin' ) . '</a>',
						'<a href="#terms-of-use" class="rf-link">' . esc_html__( 'Terms of Use', 'review-form-plugin' ) . '</a>'
					);
					?>
				</p>
			</form>
		</div>
		<?php
	}

	/**
	 * Process form submission (shared between AJAX and no-JS fallback).
	 *
	 * @return array Result array with status and message.
	 */
	public static function process_submission() {
		// 1. Verify Nonce
		if ( ! isset( $_POST['rf_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['rf_nonce'] ), 'rf_submit_nonce_action' ) ) {
			return array(
				'success' => false,
				'message' => __( 'Security verification failed. Please refresh the page and try again.', 'review-form-plugin' ),
			);
		}

		// 2. Anti-spam Honeypot Check
		if ( ! empty( $_POST['rf_hp_website'] ) ) {
			// Silent drop for bot submissions
			return array(
				'success' => true,
				'message' => __( 'Thank you! Your review has been submitted for review.', 'review-form-plugin' ),
			);
		}

		// 3. Rate Limiting Check (1 submission per 60 seconds per IP)
		$ip = self::get_client_ip();
		$rate_transient = 'rf_rl_' . md5( $ip );
		if ( get_transient( $rate_transient ) ) {
			return array(
				'success' => false,
				'message' => __( 'You are submitting reviews too quickly. Please wait 60 seconds before submitting another.', 'review-form-plugin' ),
			);
		}

		// 4. Validate & Sanitize Input Fields
		$headline    = isset( $_POST['rf_headline'] ) ? sanitize_text_field( wp_unslash( $_POST['rf_headline'] ) ) : '';
		$name        = isset( $_POST['rf_name'] ) ? sanitize_text_field( wp_unslash( $_POST['rf_name'] ) ) : '';
		$email       = isset( $_POST['rf_email'] ) ? sanitize_email( wp_unslash( $_POST['rf_email'] ) ) : '';
		$review_text = isset( $_POST['rf_review_text'] ) ? sanitize_textarea_field( wp_unslash( $_POST['rf_review_text'] ) ) : '';
		$privacy     = isset( $_POST['rf_privacy'] ) && '1' === $_POST['rf_privacy'];

		if ( empty( $headline ) ) {
			return array(
				'success' => false,
				'message' => __( 'Please enter a review headline.', 'review-form-plugin' ),
			);
		}

		if ( empty( $name ) ) {
			return array(
				'success' => false,
				'message' => __( 'Please enter your name.', 'review-form-plugin' ),
			);
		}

		if ( empty( $email ) || ! is_email( $email ) ) {
			return array(
				'success' => false,
				'message' => __( 'Please provide a valid email address.', 'review-form-plugin' ),
			);
		}

		if ( empty( $review_text ) ) {
			return array(
				'success' => false,
				'message' => __( 'Please enter your review experience.', 'review-form-plugin' ),
			);
		}

		if ( ! $privacy ) {
			return array(
				'success' => false,
				'message' => __( 'You must agree to the Privacy Policy and Review Guidelines to submit.', 'review-form-plugin' ),
			);
		}

		// 5. Handle File Attachment Upload if provided
		$attachment_id  = 0;
		$attachment_url = '';

		if ( ! empty( $_FILES['rf_attachment']['name'] ) ) {
			// Require WordPress core file upload libraries
			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once ABSPATH . 'wp-admin/includes/file.php';
			}
			if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
				require_once ABSPATH . 'wp-admin/includes/image.php';
				require_once ABSPATH . 'wp-admin/includes/media.php';
			}

			$file = $_FILES['rf_attachment']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

			// Check File Size (Max 2MB)
			if ( $file['size'] > 2 * 1024 * 1024 ) {
				return array(
					'success' => false,
					'message' => __( 'Attachment exceeds the 2 MB limit. Please upload a smaller image.', 'review-form-plugin' ),
				);
			}

			// Verify Real MIME Type
			$allowed_mimes = array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'png'          => 'image/png',
				'webp'         => 'image/webp',
			);

			$file_info = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], $allowed_mimes );
			if ( empty( $file_info['ext'] ) || empty( $file_info['type'] ) ) {
				return array(
					'success' => false,
					'message' => __( 'Invalid file format. Only JPG, PNG, and WEBP images are allowed.', 'review-form-plugin' ),
				);
			}

			$upload_overrides = array(
				'test_form' => false,
				'mimes'     => $allowed_mimes,
			);

			$movefile = wp_handle_upload( $file, $upload_overrides );

			if ( $movefile && ! isset( $movefile['error'] ) ) {
				$filename = $movefile['file'];
				$filetype = wp_check_filetype( basename( $filename ), null );

				$attachment = array(
					'guid'           => $movefile['url'],
					'post_mime_type' => $filetype['type'],
					'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
					'post_content'   => '',
					'post_status'    => 'inherit',
				);

				$attachment_id  = wp_insert_attachment( $attachment, $filename );
				$attachment_url = $movefile['url'];

				if ( ! is_wp_error( $attachment_id ) && $attachment_id > 0 ) {
					$attach_data = wp_generate_attachment_metadata( $attachment_id, $filename );
					wp_update_attachment_metadata( $attachment_id, $attach_data );
				}
			} else {
				return array(
					'success' => false,
					'message' => sprintf( __( 'Upload failed: %s', 'review-form-plugin' ), esc_html( $movefile['error'] ) ),
				);
			}
		}

		// 6. Save Review to CPT (status: pending)
		$post_arr = array(
			'post_title'   => $headline,
			'post_content' => $review_text,
			'post_status'  => 'pending',
			'post_type'    => RF_Custom_Post_Type::POST_TYPE,
			'post_author'  => 0, // Guest submission
		);

		$review_id = wp_insert_post( $post_arr, true );

		if ( is_wp_error( $review_id ) ) {
			return array(
				'success' => false,
				'message' => __( 'Could not save review. Please try again later.', 'review-form-plugin' ),
			);
		}

		// 7. Save Submitter Meta (never exposed publicly)
		update_post_meta( $review_id, '_rf_submitter_name', $name );
		update_post_meta( $review_id, '_rf_submitter_email', $email );
		update_post_meta( $review_id, '_rf_submitter_ip', $ip );
		update_post_meta( $review_id, '_rf_privacy_accepted', 1 );

		if ( $attachment_id ) {
			update_post_meta( $review_id, '_rf_attachment_id', $attachment_id );
		}
		if ( ! empty( $attachment_url ) ) {
			update_post_meta( $review_id, '_rf_attachment_url', $attachment_url );
		}

		// Set rate limit transient for 60 seconds
		set_transient( $rate_transient, 1, 60 );

		return array(
			'success' => true,
			'message' => __( 'Thank you! Your review has been submitted and is pending verification.', 'review-form-plugin' ),
		);
	}

	/**
	 * Handle AJAX form submission.
	 */
	public static function handle_submission_ajax() {
		$result = self::process_submission();
		if ( $result['success'] ) {
			wp_send_json_success( array( 'message' => $result['message'] ) );
		} else {
			wp_send_json_error( array( 'message' => $result['message'] ) );
		}
	}

	/**
	 * Handle no-JS admin-post fallback submission.
	 */
	public static function handle_submission_fallback() {
		$result = self::process_submission();
		$referer = wp_get_referer();
		if ( ! $referer ) {
			$referer = home_url( '/' );
		}

		$status = $result['success'] ? 'success' : 'error';
		$redirect_url = add_query_arg(
			array(
				'rf_status' => $status,
				'rf_msg'    => rawurlencode( $result['message'] ),
			),
			$referer
		);

		wp_safe_redirect( $redirect_url );
		exit;
	}

	/**
	 * Safely determine client IP address.
	 *
	 * @return string Sanitized IP address.
	 */
	private static function get_client_ip() {
		$ip = '';
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip_list = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
			$ip = trim( $ip_list[0] );
		} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		}
		return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : '0.0.0.0';
	}
}
