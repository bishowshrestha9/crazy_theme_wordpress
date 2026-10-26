<?php defined( 'ABSPATH' ) || exit; if ( $edPricing ) : ?>
	<div class="affx-sp-price pricing-align-<?php echo esc_attr( $productPricingAlign ); ?>">
		<div class="affx-sp-marked-price">
			<?php echo wp_kses_post( $productSalePrice ); ?>
		</div>
		<div class="affx-sp-sale-price">
			<del>
				<?php echo wp_kses_post( $productPrice ); ?>
			</del>
		</div>
	</div>
<?php endif; ?>
