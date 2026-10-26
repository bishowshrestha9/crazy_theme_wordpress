<?php defined( 'ABSPATH' ) || exit; if ( $edProductImage ) : ?>
<div class="affx-sp-img-wrapper <?php echo esc_attr( $imageVerticalAlignClass ?? '' ); ?> <?php echo esc_attr( $imageHorizontalAlignClass ?? '' ); ?>">
	<?php if ( ! empty( $useSlider ) && ! empty( $sliderImages ) ) : ?>
		<?php include PROSCONS_PLUGIN_DIR . '/templates/partials/slider.php'; ?>
	<?php else : ?>
		<?php echo wp_kses_post( $productImage ); ?>
	<?php endif; ?>
</div>
<?php endif; ?>
