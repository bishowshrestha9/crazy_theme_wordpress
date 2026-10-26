<?php defined( 'ABSPATH' ) || exit;
if ( ! $edButton1 || empty( $product['button1'] ) ) {
	return;
}
	$button1Rel = array();
if ( $product['btn1RelNoFollow'] ) {
	$button1Rel[] = 'nofollow';
}
if ( $product['btn1RelSponsored'] ) {
	$button1Rel[] = 'sponsored';
}
	$button1RelAttr = ! empty( $button1Rel ) ? 'rel="' . implode( ' ', $button1Rel ) . '"' : '';
?>
<div class="affx-btn-inner">
	<a href="<?php echo esc_url( apply_filters( 'prosandcons_button_url', do_shortcode( $product['button1URL'] ) ) ); ?>" class="prosandcons-button primary <?php echo $edButton1Icon ? 'icon-btn icon-' . esc_attr( $button1IconAlign ) : ''; ?>" <?php echo esc_html( $button1RelAttr ); ?> <?php echo $product['btn1OpenInNewTab'] ? 'target="_blank"' : ''; ?> <?php echo $product['btn1Download'] ? 'download' : ''; ?>>
		<?php if ( $edButton1Icon && $button1IconAlign === 'left' ) : ?>
			<i class="button-icon <?php echo esc_attr( $button1Icon['value'] ); ?>"></i>
		<?php endif; ?>
		<?php echo wp_kses_post( $product['button1'] ); ?>
		<?php if ( $edButton1Icon && $button1IconAlign === 'right' ) : ?>
			<i class="button-icon <?php echo esc_attr( $button1Icon['value'] ); ?>"></i>
		<?php endif; ?>
	</a>
</div>
