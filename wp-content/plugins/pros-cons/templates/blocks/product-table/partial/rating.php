<?php defined( 'ABSPATH' ) || exit; if ( $edRating && ! empty( $product['rating'] ) ) : ?>
	<?php if ( $layoutStyle === 'layoutOne' ) : ?>
		<span class="star-rating-single-wrap"><?php echo wp_kses_post( $product['rating'] ); ?></span>
	<?php elseif ( $layoutStyle === 'layoutTwo' ) : ?>
		<div class="affx-circle-progress-container">
			<span class="circle-wrap" style="--data-deg:rotate(<?php echo esc_attr( 180 * ( $product['rating'] / 10 ) ); ?>deg);">
				<span class="circle-mask full"><span class="fill"></span></span>
				<span class="circle-mask"><span class="fill"></span></span>
			</span>
			<span class="affx-circle-inside"><?php echo wp_kses_post( $product['rating'] ); ?></span>
		</div>
	<?php elseif ( $layoutStyle === 'layoutThree' ) : ?>
		<?php echo \ProsCons\Helpers\ProsCons_Helpers::kses( $this->render_pt_stars( $product['rating'], $starColor, $starInactiveColor ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<?php endif; ?>
<?php endif; ?>
