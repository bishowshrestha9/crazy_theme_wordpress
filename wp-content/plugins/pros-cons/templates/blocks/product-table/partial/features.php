<?php defined( 'ABSPATH' ) || exit; if ( $productContentType === 'list' && isset( $product['list'] ) ) : ?>
	<?php echo wp_kses_post( $product['list'] ); ?>
<?php elseif ( $productContentType === 'paragraph' ) : ?>
	<p class="prosandcons-content"><?php echo wp_kses_post( is_array( $product['features'] ) ? implode( ' ', $product['features'] ) : $product['features'] ); ?></p>
<?php endif; ?>
