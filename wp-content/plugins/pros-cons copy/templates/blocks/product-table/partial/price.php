<?php defined( 'ABSPATH' ) || exit; if ( $edPrice ) : ?>
	<div class="affx-pdt-price-wrap">
		<?php if ( ! empty( $product['offerPrice'] ) ) : ?>
			<span class="affx-pdt-offer-price"><?php echo wp_kses_post( $product['offerPrice'] ); ?></span>
		<?php endif; ?>
		<?php if ( ! empty( $product['regularPrice'] ) ) : ?>
			<del class="affx-pdt-reg-price"><?php echo wp_kses_post( $product['regularPrice'] ); ?></del>
		<?php endif; ?>
	</div>
<?php endif; ?>
