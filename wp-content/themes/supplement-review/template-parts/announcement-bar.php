<?php
/**
 * Top Announcement Bar Template Part
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$enabled  = get_theme_mod( 'sr_announcement_enabled', true );
if ( ! $enabled ) {
	return;
}

$text     = get_theme_mod( 'sr_announcement_text', __( 'Special Christmas Offer — Up to 50% Off Blood Sugar Formulas & Free US Shipping', 'supplement-review' ) );
$link     = get_theme_mod( 'sr_announcement_link', '#' );
$bg_color = get_theme_mod( 'sr_announcement_bg', '#6ea590' );
?>
<aside class="sr-announcement-bar" style="background-color: <?php echo esc_attr( $bg_color ); ?>;" aria-label="<?php esc_attr_e( 'Site Announcement', 'supplement-review' ); ?>">
	<div class="sr-container">
		<?php if ( ! empty( $link ) && '#' !== $link ) : ?>
			<a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $text ); ?></a>
		<?php else : ?>
			<span><?php echo esc_html( $text ); ?></span>
		<?php endif; ?>
	</div>
</aside>
