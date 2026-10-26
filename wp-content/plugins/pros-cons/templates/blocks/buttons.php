<?php
defined( 'ABSPATH' ) || exit;
?>
<div <?php echo wp_kses_post( $wrapper_attributes ); ?>>
	<div class="affx-btn-inner">
		<<?php echo esc_attr( $tag ) . ' ' . wp_kses_post( $link_attributes ); ?>>
			<?php echo wp_kses_post( $iconLeft ); ?>
			<span class="prosandcons-btn"><?php echo wp_kses_post( $buttonLabel ); ?></span>
			<?php echo wp_kses_post( $iconRight ); ?>
			<?php if ( $layoutStyle === 'layout-type-2' && $priceTagPosition ) : ?>
				<span class="price-tag">
					<?php echo wp_kses_post( $productPrice ); ?>
				</span>
			<?php endif; ?>
		</<?php echo esc_attr( $tag ); ?>>
	</div>
</div>
