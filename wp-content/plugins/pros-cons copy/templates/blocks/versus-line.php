<?php
defined( 'ABSPATH' ) || exit;
?>
<div <?php echo wp_kses_post( $wrapper_attributes ); ?>>
	<div class="affx-versus-line-block-container">
		<div class="affx-versus-table-wrap">
			<table class="affx-product-versus-table">
				<tbody>
					<?php foreach ( $versusTable as $item ) : ?>
						<tr>
							<td class="data-label">
								<<?php echo esc_attr( $versusTitleTag ); ?> class="affx-versus-title">
									<?php echo wp_kses_post( $item['versusTitle'] ); ?>
								</<?php echo esc_attr( $versusTitleTag ); ?>>
								<span class="data-info"><?php echo wp_kses_post( $item['versusSubTitle'] ); ?></span>
							</td>
							<td><?php echo wp_kses_post( $item['versusValue1'] ); ?></td>
							<td>
								<span class="affx-vs-icon"><?php echo wp_kses_post( $vsLabel ); ?></span>
							</td>
							<td><?php echo wp_kses_post( $item['versusValue2'] ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
