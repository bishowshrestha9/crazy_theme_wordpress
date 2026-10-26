<?php
defined( 'ABSPATH' ) || exit;
?>
<div <?php echo wp_kses_post( $wrapper_attributes ); ?>>
	<<?php echo esc_attr( $tag ) . ' ' . wp_kses_post( $inner_wrapper_attributes ); ?>>
		<div class="affx-sp-inner affx-amazon-item__border">
			<?php
			switch ( $productLayout ) {
				case 'layoutOne':
					include 'single-product/layout-1.php';
					break;
				case 'layoutTwo':
					include 'single-product/layout-2.php';
					break;
				case 'layoutThree':
					include 'single-product/layout-3.php';
					break;
			}
			?>
		</div>
	</<?php echo esc_attr( $tag ); ?>>
</div>
