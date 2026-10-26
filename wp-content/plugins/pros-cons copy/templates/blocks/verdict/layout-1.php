<?php
defined( 'ABSPATH' ) || exit;
?>
<div class="main-text-holder">
	<div class="content-wrapper">
		<?php require 'partial/title.php'; ?>
		<?php require 'partial/content.php'; ?>
	</div>
	<?php require 'partial/score.php'; ?>
</div>
<?php echo wp_kses_post( $innerBlocksContentHtml ); ?>
