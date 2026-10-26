<?php
defined( 'ABSPATH' ) || exit;
?>
<tr>
	<?php if ( $edImage ) : ?>
	<td class="affx-img-col">
		<div class="affx-pdt-img-container">
			<?php if ( ! empty( $ribbonText ) && $edRibbon ) : ?>
				<span class="affx-pdt-ribbon affx-ribbon-2"><?php echo wp_kses_post( $ribbonText ); ?></span>
			<?php endif; ?>
			<?php if ( $edCounter ) : ?>
				<span class="affx-pdt-counter"><?php echo wp_kses_post( $counterText ); ?></span>
			<?php endif; ?>
			<?php include 'partial/image.php'; ?>
		</div>
	</td>
	<?php endif; ?>
	<td><?php require 'partial/title.php'; ?><?php require 'partial/features.php'; ?></td>
	<?php if ( $edRating ) : ?>
		<td class="affx-rating-col"><?php include 'partial/rating.php'; ?></td>
	<?php endif; ?>
	<td class="affx-price-col">
		<?php require 'partial/price.php'; ?>
		<div class="affx-btn-wrapper">
			<?php require 'partial/button1.php'; ?>
			<?php require 'partial/button2.php'; ?>
		</div>
	</td>
</tr>
