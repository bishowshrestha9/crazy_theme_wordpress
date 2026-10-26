<?php defined( 'ABSPATH' ) || exit; if ( $edButton ) : ?>
	<div class="button-wrapper <?php echo esc_attr( $buttonDirection ); ?>-direction" style="--button-gap: <?php echo esc_attr( $buttonsGap ); ?>px"><?php echo wp_kses_post( $content ); ?></div>
<?php endif; ?>
