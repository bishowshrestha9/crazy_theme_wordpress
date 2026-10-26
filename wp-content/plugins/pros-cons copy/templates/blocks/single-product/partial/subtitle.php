<?php defined( 'ABSPATH' ) || exit; if ( $edSubtitle ) : ?>
	<<?php echo esc_attr( $productSubTitleTag ); ?> class="affx-single-product-subtitle">
		<?php echo wp_kses_post( $productSubTitle ); ?>
	</<?php echo esc_attr( $productSubTitleTag ); ?>>
<?php endif; ?>
