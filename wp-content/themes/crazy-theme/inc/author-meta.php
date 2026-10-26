<?php
/**
 * Author Profile Custom Fields & Management
 *
 * Adds and sanitizes custom user profile fields:
 * - Custom Author Photo URL
 * - Credential Text (e.g., "Certified Personal Trainer and Nutrition Coach at")
 * - Credential Link Text (e.g., "American Council on Exercise (ACE)")
 * - Credential Link URL
 * - Facebook Profile URL
 * - Public Contact Email
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue WordPress Media Uploader scripts on user profile edit screens.
 *
 * @param string $hook Current admin screen hook.
 */
function sr_author_meta_admin_scripts( $hook ) {
	if ( 'profile.php' !== $hook && 'user-edit.php' !== $hook ) {
		return;
	}

	wp_enqueue_media();
	wp_enqueue_script(
		'sr-author-media-uploader',
		SR_URI . '/assets/js/author-meta-admin.js',
		array( 'jquery' ),
		SR_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'sr_author_meta_admin_scripts' );

/**
 * Render custom fields on the user profile edit screen.
 *
 * @param WP_User $user Current user object.
 */
function sr_render_author_meta_fields( $user ) {
	if ( ! current_user_can( 'edit_user', $user->ID ) ) {
		return;
	}

	wp_nonce_field( 'sr_save_author_meta_nonce', 'sr_author_meta_nonce_field' );

	$photo_url       = get_user_meta( $user->ID, 'sr_author_photo_url', true );
	$credential_text = get_user_meta( $user->ID, 'sr_credential_text', true );
	$cred_link_text  = get_user_meta( $user->ID, 'sr_credential_link_text', true );
	$cred_link_url   = get_user_meta( $user->ID, 'sr_credential_link_url', true );
	$facebook_url    = get_user_meta( $user->ID, 'sr_facebook_url', true );
	$public_email    = get_user_meta( $user->ID, 'sr_public_email', true );
	?>
	<h2><?php esc_html_e( 'Supplement Review Author Card Details', 'supplement-review' ); ?></h2>
	<table class="form-table" role="presentation">
		<!-- Custom Portrait Photo -->
		<tr>
			<th scope="row">
				<label for="sr_author_photo_url"><?php esc_html_e( 'Author Portrait Photo', 'supplement-review' ); ?></label>
			</th>
			<td>
				<div style="display:flex;align-items:flex-start;gap:16px;">
					<div id="sr-author-photo-preview" style="width:77px;height:114px;background:#f0f2f5;border:1px solid #dcdcde;overflow:hidden;flex-shrink:0;">
						<?php if ( ! empty( $photo_url ) ) : ?>
							<img src="<?php echo esc_url( $photo_url ); ?>" alt="" style="width:100%;height:100%;object-fit:cover;object-position:top center;display:block;" />
						<?php else : ?>
							<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#8c8f94;font-size:11px;text-align:center;padding:4px;">No Photo</div>
						<?php endif; ?>
					</div>
					<div>
						<input type="url" name="sr_author_photo_url" id="sr_author_photo_url" value="<?php echo esc_url( $photo_url ); ?>" class="regular-text" style="width:360px;" placeholder="https://example.com/photo.jpg" />
						<button type="button" class="button" id="sr-upload-author-photo-btn"><?php esc_html_e( 'Choose / Upload Photo', 'supplement-review' ); ?></button>
						<button type="button" class="button" id="sr-remove-author-photo-btn" style="<?php echo empty( $photo_url ) ? 'display:none;' : ''; ?>"><?php esc_html_e( 'Remove Photo', 'supplement-review' ); ?></button>
						<p class="description"><?php esc_html_e( 'Recommended size: 77x114px or 154x228px (2x retina). Falls back to Gravatar if empty.', 'supplement-review' ); ?></p>
					</div>
				</div>
			</td>
		</tr>

		<!-- Credential Prefix Text -->
		<tr>
			<th scope="row">
				<label for="sr_credential_text"><?php esc_html_e( 'Credential Text', 'supplement-review' ); ?></label>
			</th>
			<td>
				<input type="text" name="sr_credential_text" id="sr_credential_text" value="<?php echo esc_attr( $credential_text ); ?>" class="regular-text" placeholder="e.g. Certified Personal Trainer and Nutrition Coach at" />
				<p class="description"><?php esc_html_e( 'Text appearing next to the checkmark icon before the credential organization link.', 'supplement-review' ); ?></p>
			</td>
		</tr>

		<!-- Credential Organization Link Text -->
		<tr>
			<th scope="row">
				<label for="sr_credential_link_text"><?php esc_html_e( 'Credential Link Text', 'supplement-review' ); ?></label>
			</th>
			<td>
				<input type="text" name="sr_credential_link_text" id="sr_credential_link_text" value="<?php echo esc_attr( $cred_link_text ); ?>" class="regular-text" placeholder="e.g. American Council on Exercise (ACE)" />
				<p class="description"><?php esc_html_e( 'The anchor text for the credential link.', 'supplement-review' ); ?></p>
			</td>
		</tr>

		<!-- Credential Organization Link URL -->
		<tr>
			<th scope="row">
				<label for="sr_credential_link_url"><?php esc_html_e( 'Credential Link URL', 'supplement-review' ); ?></label>
			</th>
			<td>
				<input type="url" name="sr_credential_link_url" id="sr_credential_link_url" value="<?php echo esc_url( $cred_link_url ); ?>" class="regular-text" placeholder="https://www.acefitness.org" />
				<p class="description"><?php esc_html_e( 'URL for the credential organization (opens in new tab).', 'supplement-review' ); ?></p>
			</td>
		</tr>

		<!-- Facebook Profile URL -->
		<tr>
			<th scope="row">
				<label for="sr_facebook_url"><?php esc_html_e( 'Facebook Profile URL', 'supplement-review' ); ?></label>
			</th>
			<td>
				<input type="url" name="sr_facebook_url" id="sr_facebook_url" value="<?php echo esc_url( $facebook_url ); ?>" class="regular-text" placeholder="https://facebook.com/jayce.decker" />
				<p class="description"><?php esc_html_e( 'Link for the Facebook icon overlaying the bottom of the photo.', 'supplement-review' ); ?></p>
			</td>
		</tr>

		<!-- Public Contact Email -->
		<tr>
			<th scope="row">
				<label for="sr_public_email"><?php esc_html_e( 'Public Contact Email', 'supplement-review' ); ?></label>
			</th>
			<td>
				<input type="email" name="sr_public_email" id="sr_public_email" value="<?php echo esc_attr( $public_email ); ?>" class="regular-text" placeholder="author@example.com" />
				<p class="description"><?php esc_html_e( 'Public email for the mailto icon overlay. Automatically obfuscated with antispambot().', 'supplement-review' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'sr_render_author_meta_fields' );
add_action( 'edit_user_profile', 'sr_render_author_meta_fields' );

/**
 * Save custom fields with security nonce verification and data sanitization.
 *
 * @param int $user_id User ID being saved.
 * @return bool|void False on failure.
 */
function sr_save_author_meta_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	if ( ! isset( $_POST['sr_author_meta_nonce_field'] ) || ! wp_verify_nonce( sanitize_key( $_POST['sr_author_meta_nonce_field'] ), 'sr_save_author_meta_nonce' ) ) {
		return false;
	}

	// 1. Author Photo URL
	if ( isset( $_POST['sr_author_photo_url'] ) ) {
		$photo = esc_url_raw( wp_unslash( $_POST['sr_author_photo_url'] ) );
		update_user_meta( $user_id, 'sr_author_photo_url', $photo );
	}

	// 2. Credential Text
	if ( isset( $_POST['sr_credential_text'] ) ) {
		$cred_text = sanitize_text_field( wp_unslash( $_POST['sr_credential_text'] ) );
		update_user_meta( $user_id, 'sr_credential_text', $cred_text );
	}

	// 3. Credential Link Text
	if ( isset( $_POST['sr_credential_link_text'] ) ) {
		$cred_link_text = sanitize_text_field( wp_unslash( $_POST['sr_credential_link_text'] ) );
		update_user_meta( $user_id, 'sr_credential_link_text', $cred_link_text );
	}

	// 4. Credential Link URL
	if ( isset( $_POST['sr_credential_link_url'] ) ) {
		$cred_link_url = esc_url_raw( wp_unslash( $_POST['sr_credential_link_url'] ), array( 'http', 'https' ) );
		update_user_meta( $user_id, 'sr_credential_link_url', $cred_link_url );
	}

	// 5. Facebook URL
	if ( isset( $_POST['sr_facebook_url'] ) ) {
		$fb_url = esc_url_raw( wp_unslash( $_POST['sr_facebook_url'] ), array( 'http', 'https' ) );
		update_user_meta( $user_id, 'sr_facebook_url', $fb_url );
	}

	// 6. Public Email
	if ( isset( $_POST['sr_public_email'] ) ) {
		$email = sanitize_email( wp_unslash( $_POST['sr_public_email'] ) );
		update_user_meta( $user_id, 'sr_public_email', $email );
	}
}
add_action( 'personal_options_update', 'sr_save_author_meta_fields' );
add_action( 'edit_user_profile_update', 'sr_save_author_meta_fields' );
