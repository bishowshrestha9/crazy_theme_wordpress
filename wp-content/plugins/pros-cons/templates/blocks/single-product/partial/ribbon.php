<?php defined( 'ABSPATH' ) || exit; if ( $edRibbon ) : ?>
	<div class="affx-sp-ribbon<?php echo esc_attr( $ribbonLayout ); ?>
	<?php
	if ( $ribbonAlign !== 3 ) {
		echo 'ribbon-align-' . esc_attr( $ribbonAlign ); }
	?>
	">
		<div class="affx-sp-ribbon-title">
			<?php echo wp_kses_post( $ribbonText ?? '' ); ?>
		</div>
	</div>
<?php endif; ?>
