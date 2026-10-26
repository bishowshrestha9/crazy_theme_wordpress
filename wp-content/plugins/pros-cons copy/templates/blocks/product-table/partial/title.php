<?php defined( 'ABSPATH' ) || exit; if ( $edProductName ) : ?>
	<<?php echo esc_attr( $productNameTag ); ?> class="affx-pdt-name"><?php echo wp_kses_post( $product['name'] ); ?></<?php echo esc_attr( $productNameTag ); ?>>
<?php endif; ?>
