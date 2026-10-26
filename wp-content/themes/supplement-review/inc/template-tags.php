<?php
/**
 * Custom Template Tags for this theme
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Prints the Author Bar matching the screenshot design.
 * Avatar, Name, Medical Role, Published Date, Updated Date, Reviewed By, Reading Time.
 */
function sr_review_author_bar() {
	$author_id    = get_the_author_meta( 'ID' );
	$author_name  = get_the_author();
	$author_role  = get_post_meta( get_the_ID(), '_sr_author_role', true );
	if ( empty( $author_role ) ) {
		$author_role = get_the_author_meta( 'sr_user_role_title', $author_id );
	}
	if ( empty( $author_role ) ) {
		$author_role = __( 'Medical Researcher & Contributor', 'supplement-review' );
	}

	$reviewed_by  = get_post_meta( get_the_ID(), '_sr_reviewed_by', true );
	if ( empty( $reviewed_by ) ) {
		$reviewed_by = $author_name;
	}

	$published_date = get_the_date( 'M j, Y' );
	$modified_date  = get_the_modified_date( 'M j, Y' );
	$reading_time   = sr_get_reading_time( get_the_ID() );
	?>
	<div class="sr-author-row">
		<div class="sr-author-profile">
			<?php echo get_avatar( $author_id, 44, '', esc_attr( $author_name ), array( 'class' => 'sr-author-avatar' ) ); ?>
			<div class="sr-author-details">
				<span class="sr-author-name"><?php echo esc_html( $author_name ); ?></span>
				<span class="sr-author-role"><?php echo esc_html( $author_role ); ?></span>
			</div>
		</div>

		<div class="sr-review-meta-info">
			<span class="sr-meta-item sr-meta-date-group">
				<span class="sr-date-published"><?php echo esc_html( $published_date ); ?></span>
				<?php if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) : ?>
					<span class="sr-date-updated"><?php printf( esc_html__( '(Updated on: %s)', 'supplement-review' ), esc_html( $modified_date ) ); ?></span>
				<?php endif; ?>
			</span>

			<span class="sr-meta-divider" aria-hidden="true">&bull;</span>

			<span class="sr-meta-item sr-reviewed-by">
				<?php
				echo wp_kses(
					sprintf(
						/* translators: %s: Reviewer name */
						__( 'Reviewed By %s', 'supplement-review' ),
						'<strong>' . esc_html( $reviewed_by ) . '</strong>'
					),
					array(
						'strong' => array(),
					)
				);
				?>
			</span>

			<span class="sr-meta-divider" aria-hidden="true">&bull;</span>

			<span class="sr-meta-item sr-read-time">
				<?php printf( esc_html__( '%d min read', 'supplement-review' ), absint( $reading_time ) ); ?>
			</span>
		</div>
	</div>
	<?php
}

/**
 * Prints the Trust Badges Row (Disclaimer, Fact Check, Review Process).
 */
function sr_trust_badges_row() {
	$disclaimer_url = get_theme_mod( 'sr_badge_disclaimer_url', '' );
	$factcheck_url  = get_theme_mod( 'sr_badge_factcheck_url', '' );
	$process_url    = get_theme_mod( 'sr_badge_process_url', '' );

	$badges = array(
		array(
			'label' => __( 'Disclaimer', 'supplement-review' ),
			'url'   => ! empty( $disclaimer_url ) ? $disclaimer_url : '#disclaimer',
		),
		array(
			'label' => __( 'Fact Check', 'supplement-review' ),
			'url'   => ! empty( $factcheck_url ) ? $factcheck_url : '#fact-check',
		),
		array(
			'label' => __( 'Review Process', 'supplement-review' ),
			'url'   => ! empty( $process_url ) ? $process_url : '#review-process',
		),
	);
	?>
	<div class="sr-trust-badges" role="region" aria-label="<?php esc_attr_e( 'Trust Badges', 'supplement-review' ); ?>">
		<?php foreach ( $badges as $badge ) : ?>
			<a href="<?php echo esc_url( $badge['url'] ); ?>" class="sr-trust-badge">
				<svg class="sr-badge-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
					<path d="M21.375 13.125C20.625 16.875 17.7977 20.4054 13.829 21.1949C9.8603 21.9843 5.83298 20.1383 3.84043 16.6164C1.84787 13.0947 2.33978 8.69181 5.06047 5.69658C7.78115 2.70133 12.375 1.87501 16.125 3.37501" stroke="#66D86A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					<path d="M8.625 11.625L12.375 15.375L21.375 5.625" stroke="#66D86A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
				<span><?php echo esc_html( $badge['label'] ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Prints Author Bio Box at the bottom of the review.
 */
function sr_author_bio_box() {
	$author_id    = get_the_author_meta( 'ID' );
	$author_name  = get_the_author();
	$author_bio   = get_the_author_meta( 'description' );
	$is_verified  = get_the_author_meta( 'sr_user_verified', $author_id );
	$user_email   = get_the_author_meta( 'user_email' );

	if ( empty( $author_bio ) ) {
		$author_bio = sprintf(
			/* translators: %s: Author name */
			__( '%s is an experienced health and wellness researcher dedicated to providing evidence-based supplement reviews, clinical breakdowns, and consumer safety guidance.', 'supplement-review' ),
			$author_name
		);
	}
	?>
	<aside class="sr-author-bio-box" aria-label="<?php esc_attr_e( 'About the Author', 'supplement-review' ); ?>">
		<?php echo get_avatar( $author_id, 80, '', esc_attr( $author_name ), array( 'class' => 'sr-bio-avatar' ) ); ?>
		<div class="sr-bio-content">
			<div class="sr-bio-header">
				<h3 class="sr-bio-name"><?php echo esc_html( $author_name ); ?></h3>
				<?php if ( $is_verified ) : ?>
					<span class="sr-bio-badge">
						<?php sr_svg( 'shield-check', array( 'width' => 14, 'height' => 14 ) ); ?>
						<?php esc_html_e( 'Verified Medical Reviewer', 'supplement-review' ); ?>
					</span>
				<?php endif; ?>
			</div>
			<p class="sr-bio-text"><?php echo wp_kses_post( $author_bio ); ?></p>
			<div class="sr-bio-socials">
				<?php if ( ! empty( $user_email ) ) : ?>
					<a href="<?php echo esc_url( 'mailto:' . antispambot( $user_email ) ); ?>" class="sr-bio-social-link" aria-label="<?php esc_attr_e( 'Contact author via email', 'supplement-review' ); ?>">
						<?php sr_svg( 'envelope', array( 'width' => 16, 'height' => 16 ) ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</aside>
	<?php
}
