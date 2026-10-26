<?php defined( 'ABSPATH' ) || exit;
use ProsCons\Helpers\ProsCons_Helpers;
?>
<div class="affx-pdt-img-wrapper">
	<?php
	echo wp_kses_post( ProsCons_Helpers::prosandcons_get_media_image_html( $imageId, $imageUrl, $imageAlt ) );
	?>
</div>
