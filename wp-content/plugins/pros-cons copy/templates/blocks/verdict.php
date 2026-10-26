<?php
defined( 'ABSPATH' ) || exit;
?>
<div <?php echo wp_kses_post( $wrapper_attributes ); ?>>
	<div class="affblk-verdict-wrapper">
		<div class="<?php echo esc_attr( $layoutClass ); ?><?php echo esc_attr( $arrowClass ); ?>">
			<?php
			switch ( $verdictLayout ) {
				case 'layoutOne':
					include 'verdict/layout-1.php';
					break;
				case 'layoutTwo':
					include 'verdict/layout-2.php';
					break;
			}
			?>
		</div>
	</div>
</div>
