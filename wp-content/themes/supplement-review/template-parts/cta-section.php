<?php
/**
 * Pre-Footer Call to Action Template Part
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$cta_enabled = get_theme_mod( 'sr_cta_enabled', true );
if ( ! $cta_enabled ) {
	return;
}

$heading = get_theme_mod( 'sr_cta_heading', __( "Know What You're Taking", 'supplement-review' ) );
$desc    = get_theme_mod( 'sr_cta_description', __( 'We dive deep into clinical studies, ingredient purity, and genuine user reports to help you choose supplements with complete confidence.', 'supplement-review' ) );
$btn_txt = get_theme_mod( 'sr_cta_btn_text', __( 'Get your - 50% off', 'supplement-review' ) );
$btn_url = get_theme_mod( 'sr_cta_btn_link', '#' );
?>
<section class="sr-pre-footer-cta" aria-label="<?php esc_attr_e( 'Call to action', 'supplement-review' ); ?>">
	<div class="sr-container">
		<h2><?php echo esc_html( $heading ); ?></h2>
		<p><?php echo esc_html( $desc ); ?></p>
		<?php if ( ! empty( $btn_txt ) ) : ?>
			<a href="<?php echo esc_url( $btn_url ); ?>" class="sr-btn sr-btn-cta">
				<span><?php echo esc_html( $btn_txt ); ?></span>
				<?php sr_svg( 'arrow-right', array( 'width' => 18, 'height' => 18 ) ); ?>
			</a>
		<?php endif; ?>
	</div>
</section>
