<?php
defined( 'ABSPATH' ) || exit;
use ProsCons\Helpers\ProsCons_Helpers;
?>
<div <?php echo wp_kses_post( $wrapper_attributes ); ?>>
	<div class="affx-product-comparison-block-container affx-versus-block-container<?php echo $matchCardHeights ? ' match-heights' : ''; ?>">
		<div class="affx-versus-table-wrap">
			<table class="affx-product-versus-table layout-1">
				<thead>
					<tr>
						<?php if ( $pcTitleColumn ) : ?>
							<th class="data-label" style="width:<?php echo 92 / ( count( $productComparisonTable ) + 1 ); ?>%;"></th>
						<?php endif; ?>
						<?php foreach ( $productComparisonTable as $item ) : ?>
							<th class="affx-product-col" style="width:<?php echo ( $pcTitleColumn ? 92 : 100 ) / ( $pcTitleColumn ? count( $productComparisonTable ) + 1 : count( $productComparisonTable ) ); ?>%;">
								<?php if ( $pcRibbon && ! empty( $item['ribbonText'] ) ) : ?>
									<span class="affx-pc-ribbon"><?php echo wp_kses_post( $item['ribbonText'] ); ?></span>
								<?php endif; ?>
								<div class="affx-versus-product">
									<?php if ( $pcImage ) : ?>
										<div class="affx-versus-product-img">
											<?php echo wp_kses_post( ProsCons_Helpers::prosandcons_get_media_image_html( $item['imageId'] ?? 0, $item['imageUrl'] ?? '', $item['imageAlt'] ?? '' ) ); ?>
										</div>
									<?php endif; ?>
									<div class="affx-product-content">
										<?php if ( $pcTitle ) : ?>
											<div class="affx-product-title-wrap">
												<<?php echo esc_attr( $pcTitleTag ); ?> class="affx-comparison-title" style="text-align: <?php echo esc_attr( $pcTitleAlign ); ?>;">
													<?php echo wp_kses_post( $item['title'] ); ?>
												</<?php echo esc_attr( $pcTitleTag ); ?>>
											</div>
										<?php endif; ?>
										<div>
											<?php if ( $pcPrice ) : ?>
												<div class="affx-price-wrap">
													<span class="affx-price"><?php echo wp_kses_post( $item['price'] ); ?></span>
												</div>
											<?php endif; ?>
											<?php if ( $pcRating ) : ?>
												<div class="affx-rating-wrap">
													<?php echo \ProsCons\Helpers\ProsCons_Helpers::kses( $this->render_pc_stars( $item['rating'], $starColor, $starInactiveColor ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
												</div>
											<?php endif; ?>
											<?php if ( $pcButton ) : ?>
												<div class="affx-btn-wrap">
													<a href="<?php echo esc_url( apply_filters( 'prosandcons_button_url', do_shortcode( $item['buttonURL'] ) ) ); ?>" class="prosandcons-button affx-winner-button <?php echo $pcButtonIcon ? 'icon-btn icon-' . esc_attr( $buttonIconAlign ) : ''; ?>" <?php echo esc_html( $item['rel'] . ' ' . esc_html( $item['target'] ) . ' ' . esc_html( $item['download'] ) ); ?>>
														<?php if ( $pcButtonIcon && $buttonIconAlign === 'left' ) : ?>
															<i class="button-icon <?php echo esc_attr( $buttonIcon['value'] ); ?>"></i>
														<?php endif; ?>
														<?php echo wp_kses_post( $item['button'] ); ?>
														<?php if ( $pcButtonIcon && $buttonIconAlign === 'right' ) : ?>
															<i class="button-icon <?php echo esc_attr( $buttonIcon['value'] ); ?>"></i>
														<?php endif; ?>
													</a>
												</div>
											<?php endif; ?>
										</div>
									</div>
								</div>
							</th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $comparisonSpecs as $index => $item ) : ?>
						<tr>
							<?php foreach ( $productComparisonTable as $countIndex => $count ) : ?>
								<?php if ( $countIndex === 0 ) : ?>
									<?php if ( $pcTitleColumn ) : ?>
										<td class="data-label">
											<?php echo wp_kses_post( $item['title'] ); ?>
										</td>
									<?php endif; ?>
									<td>
										<?php echo wp_kses_post( $item['specs'][ $countIndex ] ?? '' ); ?>
									</td>
								<?php else : ?>
									<td>
										<?php echo wp_kses_post( $item['specs'][ $countIndex ] ?? '' ); ?>
									</td>
								<?php endif; ?>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
