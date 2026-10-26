<?php defined( 'ABSPATH' ) || exit; if ( $edTitle ) : ?>
	<<?php echo esc_attr( $productTitleTag ); ?> class="affx-single-product-title">
		<?php echo wp_kses_post( $productTitle ); ?>
	</<?php echo esc_attr( $productTitleTag ); ?>>
<?php endif; ?>
