<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="affx-pdt-table-single">
	<?php if ( $edImage ) : ?>
			<?php include 'partial/image.php'; ?>
	<?php endif; ?>
	<div class="affx-pdt-content-wrap">
		<div class="affx-content-left">
			<?php if ( $edCounter ) : ?>
				<span class="affx-pdt-counter"><?php echo wp_kses_post( $counterText ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $ribbonText ) && $edRibbon ) : ?>
				<span class="affx-pdt-ribbon"><?php echo wp_kses_post( $ribbonText ); ?></span>
			<?php endif; ?>
			<?php require 'partial/title.php'; ?>
			<div class="affx-rating-wrap"><?php require 'partial/rating.php'; ?></div>
			<?php require 'partial/price.php'; ?>
			<div class="affx-pdt-desc"><?php require 'partial/features.php'; ?></div>
		</div>
		<div class="affx-pdt-button-wrap">
			<div class="affx-btn-wrapper">
				<?php require 'partial/button1.php'; ?>
				<?php require 'partial/button2.php'; ?>
			</div>
		</div>
	</div>
</div>
