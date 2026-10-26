<?php
defined( 'ABSPATH' ) || exit;
?>
<div <?php echo wp_kses_post( $wrapper_attributes ); ?>>
	<div class="affx-specification-block-container">
		<table class="affx-specification-table <?php echo esc_attr( $styleClasses ); ?>">
			<?php if ( $edSpecificationTitle ) : ?>
				<thead>
					<tr>
						<th class="affx-spec-title" colspan="2">
							<<?php echo esc_attr( $specificationTitleTag ); ?> class="affx-specification-title">
								<?php echo wp_kses_post( $specificationTitle ); ?>
							</<?php echo esc_attr( $specificationTitleTag ); ?>>
						</th>
					</tr>
				</thead>
			<?php endif; ?>
			<tbody>
				<?php foreach ( $specificationTable as $specification ) : ?>
					<tr>
						<td class="affx-spec-label"><?php echo wp_kses_post( $specification['specificationLabel'] ); ?></td>
						<td class="affx-spec-value"><?php echo wp_kses_post( $specification['specificationValue'] ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
