<?php
/**
 * Author Bio Card Template Part
 *
 * Renders the author bio card below the article and review form.
 * Full container width, matching Image 2 reference pixel-accurately.
 * Populates dynamically from the post author's profile with full security escaping,
 * circular 80x80 photo with social icons below, and Schema.org Person JSON-LD.
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Only display on single posts
if ( ! is_singular( 'post' ) ) {
	return;
}

$author_id = get_the_author_meta( 'ID' );
if ( ! $author_id ) {
	global $post;
	$author_id = $post ? (int) $post->post_author : 0;
}

// 1. Author Name
$author_name = get_the_author_meta( 'display_name', $author_id );
if ( empty( $author_name ) ) {
	$author_name = 'Jayce Decker';
}

// 2. Author Bio Text
$author_bio = get_the_author_meta( 'description', $author_id );
if ( empty( $author_bio ) ) {
	$author_bio = __( 'Jayce Decker is a certified personal trainer and nutrition coach who writes about effective workout routines and nutrition tips for the American Council on Exercise (ACE). He holds certifications from several renowned fitness organizations and has helped many clients reach their health and fitness objectives. At DailyHealthSupplement.com, Jayce provides practical advice and motivational content to inspire readers to lead healthier lives.', 'supplement-review' );
}

// 3. Author Photo (Circular 80x80)
$photo_url = get_user_meta( $author_id, 'sr_author_photo_url', true );
$photo_2x  = '';
if ( empty( $photo_url ) ) {
	$default_photo = SR_URI . '/assets/images/author-jayce-circle.jpg';
	$photo_url     = $default_photo;
	$photo_2x      = SR_URI . '/assets/images/author-jayce-circle@2x.jpg 2x, ' . $default_photo . ' 1x';
} else {
	$photo_2x = $photo_url . ' 1x';
}

// 4. Credential Line Data
$credential_text = get_user_meta( $author_id, 'sr_credential_text', true );
$cred_link_text  = get_user_meta( $author_id, 'sr_credential_link_text', true );
$cred_link_url   = get_user_meta( $author_id, 'sr_credential_link_url', true );

// Fallbacks for demo content if profile fields are not populated yet
if ( empty( $credential_text ) && empty( $cred_link_text ) ) {
	$credential_text = 'Certified Personal Trainer and Nutrition Coach at ';
	$cred_link_text  = 'American Council on Exercise (ACE)';
	$cred_link_url   = 'https://www.acefitness.org';
}

// 5. Social Links Data
$facebook_url = get_user_meta( $author_id, 'sr_facebook_url', true );
if ( empty( $facebook_url ) && 'Jayce Decker' === $author_name ) {
	$facebook_url = 'https://facebook.com';
}

$public_email = get_user_meta( $author_id, 'sr_public_email', true );
if ( empty( $public_email ) ) {
	$public_email = get_the_author_meta( 'user_email', $author_id );
}
if ( empty( $public_email ) && 'Jayce Decker' === $author_name ) {
	$public_email = 'jayce@dailyhealthsupplement.com';
}

// JSON-LD Person Schema data assembly
$same_as = array();
if ( ! empty( $facebook_url ) ) {
	$same_as[] = esc_url( $facebook_url );
}

$schema_person = array(
	'@context'    => 'https://schema.org',
	'@type'       => 'Person',
	'name'        => wp_strip_all_tags( $author_name ),
	'description' => wp_strip_all_tags( $author_bio ),
);

if ( ! empty( $photo_url ) ) {
	$schema_person['image'] = esc_url( $photo_url );
}

if ( ! empty( $credential_text ) || ! empty( $cred_link_text ) ) {
	$job_title = trim( $credential_text . ' ' . $cred_link_text );
	$schema_person['jobTitle'] = wp_strip_all_tags( $job_title );
}

if ( ! empty( $cred_link_text ) ) {
	$schema_person['worksFor'] = array(
		'@type' => 'Organization',
		'name'  => wp_strip_all_tags( $cred_link_text ),
		'url'   => ! empty( $cred_link_url ) ? esc_url( $cred_link_url ) : '',
	);
}

if ( ! empty( $same_as ) ) {
	$schema_person['sameAs'] = $same_as;
}
?>

<!-- Schema.org Person JSON-LD for Author -->
<script type="application/ld+json">
<?php echo wp_json_encode( $schema_person, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT ); ?>
</script>

<!-- Author Bio Card Section (Full Container Width) -->
<section class="ab-card" aria-labelledby="author-name">
	<!-- Left Column: Circular Photo + Centered Social Icons Below -->
	<div class="ab-left-col">
		<div class="ab-photo-wrap">
			<img
				src="<?php echo esc_url( $photo_url ); ?>"
				<?php if ( ! empty( $photo_2x ) ) : ?>
					srcset="<?php echo esc_attr( $photo_2x ); ?>"
				<?php endif; ?>
				alt="<?php echo esc_attr( sprintf( __( 'Photo of %s', 'supplement-review' ), $author_name ) ); ?>"
				class="ab-photo"
				width="80"
				height="80"
				loading="lazy"
			/>
		</div>

		<?php if ( ! empty( $facebook_url ) || ! empty( $public_email ) ) : ?>
			<div class="ab-social-row">
				<?php if ( ! empty( $facebook_url ) ) : ?>
					<a
						href="<?php echo esc_url( $facebook_url ); ?>"
						class="ab-social-link"
						target="_blank"
						rel="noopener noreferrer"
						aria-label="<?php echo esc_attr( sprintf( __( 'Facebook profile of %s', 'supplement-review' ), $author_name ) ); ?>"
					>
						<!-- Facebook 'f' Icon -->
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">
							<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
						</svg>
					</a>
				<?php endif; ?>

				<?php if ( ! empty( $public_email ) ) : ?>
					<a
						href="<?php echo esc_url( 'mailto:' . antispambot( $public_email ) ); ?>"
						class="ab-social-link"
						aria-label="<?php echo esc_attr( sprintf( __( 'Email %s', 'supplement-review' ), $author_name ) ); ?>"
					>
						<!-- Mail / Envelope Icon (Outline Style) -->
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
							<rect x="2" y="4" width="20" height="16" rx="2"/>
							<path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
						</svg>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<!-- Text Column (Right) -->
	<div class="ab-content">
		<!-- 1. Name: Montserrat 24px, weight 600, color #080A09, line-height 1.3 -->
		<h3 class="ab-name" id="author-name"><?php echo esc_html( $author_name ); ?></h3>

		<!-- 2. Credential Line: 6px below name -->
		<?php if ( ! empty( $credential_text ) || ! empty( $cred_link_text ) ) : ?>
			<div class="ab-credential-row">
				<!-- Green Check-Circle Icon (16px, stroke #3F6F5D) -->
				<svg class="ab-check-icon" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
					<circle cx="8" cy="8" r="7" stroke="#3F6F5D" stroke-width="1.3"/>
					<path d="M5 8.2L7 10.2L11.2 5.8" stroke="#3F6F5D" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>

				<?php if ( ! empty( $credential_text ) ) : ?>
					<span class="ab-credential-text"><?php echo esc_html( $credential_text ); ?></span>
				<?php endif; ?>

				<?php if ( ! empty( $cred_link_text ) ) : ?>
					<?php if ( ! empty( $cred_link_url ) ) : ?>
						<a
							href="<?php echo esc_url( $cred_link_url ); ?>"
							class="ab-credential-link"
							target="_blank"
							rel="noopener noreferrer"
						><?php echo esc_html( $cred_link_text ); ?></a>
					<?php else : ?>
						<span class="ab-credential-link"><?php echo esc_html( $cred_link_text ); ?></span>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<!-- 3. Bio Paragraph: 20px below credential line -->
		<?php if ( ! empty( $author_bio ) ) : ?>
			<p class="ab-bio"><?php echo wp_kses_post( $author_bio ); ?></p>
		<?php endif; ?>
	</div>
</section>
