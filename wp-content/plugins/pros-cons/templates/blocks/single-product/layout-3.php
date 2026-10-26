<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="affx-sp-content <?php echo esc_attr( $imageAlign ); ?> <?php echo esc_attr( $imageClass ); ?>">
	<?php require 'partial/ribbon.php'; ?>
	<?php require 'partial/image.php'; ?>
	<div class="affx-sp-content-wrapper">
		<div class="title-wrapper affx-<?php echo esc_attr( $ratingClass ); ?> <?php echo esc_attr( $productRatingNumberClass ); ?>">
			<div class="affx-title-left">
				<?php require 'partial/title.php'; ?>
				<?php require 'partial/subtitle.php'; ?>
			</div>
			<?php require 'partial/rating.php'; ?>
		</div>
		<?php require 'partial/price.php'; ?>
		<?php require 'partial/content.php'; ?>
	</div>
	<?php require 'partial/button.php'; ?>
</div>
