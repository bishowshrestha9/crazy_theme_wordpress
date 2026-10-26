<?php
defined( 'ABSPATH' ) || exit;
ob_start();

foreach ( $productTable as $index => $product ) {
	$counterText  = $edCounter ? ( $index + 1 ) : '';
	$ribbonText   = $product['ribbon'] ?? '';
	$imageId      = $product['imageId'] ?? '';
	$imageUrl     = esc_url( do_shortcode( $product['imageUrl'] ?? '' ) );
	$imageAlt     = esc_attr( $product['imageAlt'] ?? '' );
	$featuresList = $product['featuresList'] ?? array();

	if ( is_array( $featuresList ) && count( $featuresList ) > 0 && isset( $featuresList[0]['list'] ) && is_string( $featuresList[0]['list'] ) && has_shortcode( $featuresList[0]['list'], 'prosandcons-product' ) ) {
		$featuresList = json_decode( do_shortcode( $featuresList[0]['list'] ), true );
	}

	switch ( $layoutStyle ) {
		case 'layoutOne':
			include 'product-table/layout-1.php';
			break;
		case 'layoutTwo':
			include 'product-table/layout-2.php';
			break;
		case 'layoutThree':
			include 'product-table/layout-3.php';
			break;
	}
}

$table_body = ob_get_clean();
?>
<div <?php echo wp_kses_post( $wrapper_attributes ); ?>>
	<div class="affx-pdt-table-container--free affx-block-admin <?php echo $layoutStyle === 'layoutThree' ? 'layout-3' : ''; ?>">
		<div class="affx-pdt-table-wrapper">
			<?php if ( $layoutStyle === 'layoutThree' ) : ?>
				<?php echo \ProsCons\Helpers\ProsCons_Helpers::kses( $table_body ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<table class="affx-pdt-table">
					<thead>
						<tr>
							<?php if ( $edImage ) : ?>
								<td class="affx-img-col"><span><?php echo wp_kses_post( $imageColTitle ); ?></span></td>
							<?php endif; ?>
							<?php if ( ! ( $layoutStyle === 'layoutOne' && ! $edProductName ) ) : ?>
								<td><span><?php echo wp_kses_post( $productColTitle ); ?></span></td>
							<?php endif; ?>
							<?php if ( $layoutStyle === 'layoutOne' ) : ?>
								<td><span><?php echo wp_kses_post( $featuresColTitle ); ?></span></td>
								<td class="affx-price-col"><span><?php echo wp_kses_post( $priceColTitle ); ?></span></td>
							<?php endif; ?>
							<?php if ( $layoutStyle === 'layoutTwo' && $edRating ) : ?>
								<td><span><?php echo wp_kses_post( $ratingColTitle ); ?></span></td>
							<?php endif; ?>
							<?php if ( $layoutStyle === 'layoutTwo' ) : ?>
								<td class="affx-price-col"><span><?php echo wp_kses_post( $priceColTitle ); ?></span></td>
							<?php endif; ?>
						</tr>
					</thead>
					<tbody><?php echo wp_kses_post( $table_body ); ?></tbody>
				</table>
			<?php endif; ?>
		</div>
	</div>
</div>
