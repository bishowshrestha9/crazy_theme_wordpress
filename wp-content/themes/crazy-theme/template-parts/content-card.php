<?php
/**
 * Review Post Card Template Part
 * Used in related posts, homepage grids, and archives.
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_the_category();
$cat_name   = ! empty( $categories ) ? $categories[0]->name : __( 'Review', 'supplement-review' );
$thumb_url  = get_the_post_thumbnail_url( get_the_ID(), 'sr-card' );
if ( empty( $thumb_url ) ) {
	$product_img_id = (int) get_post_meta( get_the_ID(), '_sr_product_image_id', true );
	if ( $product_img_id ) {
		$thumb_url = wp_get_attachment_image_url( $product_img_id, 'medium' );
	} else {
		$thumb_url = get_template_directory_uri() . '/assets/images/product-sample.png';
	}
}

$rating = (float) get_post_meta( get_the_ID(), '_sr_product_rating', true );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'sr-review-card' ); ?>>
	<a href="<?php the_permalink(); ?>" class="sr-card-thumbnail-wrap" aria-label="<?php the_title_attribute(); ?>">
		<span class="sr-card-badge"><?php echo esc_html( $cat_name ); ?></span>
		<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy" />
	</a>

	<div class="sr-card-body">
		<?php if ( $rating > 0 ) : ?>
			<div style="margin-bottom:8px;">
				<?php echo sr_get_star_rating_html( $rating, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>

		<h3 class="sr-card-title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>

		<div class="sr-card-meta">
			<span class="sr-card-author">
				<?php printf( esc_html__( 'By %s', 'supplement-review' ), esc_html( get_the_author() ) ); ?>
			</span>
			<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
				<?php echo esc_html( get_the_date( 'M j, Y' ) ); ?>
			</time>
		</div>
	</div>
</article>
