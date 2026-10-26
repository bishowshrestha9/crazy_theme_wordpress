<?php
/**
 * Related Reviews ("You may also like") Template Part
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$categories = get_the_category( get_the_ID() );
$cat_ids    = ! empty( $categories ) ? wp_list_pluck( $categories, 'term_id' ) : array();

$args = array(
	'post_type'           => 'post',
	'posts_per_page'      => 4,
	'post__not_in'        => array( get_the_ID() ),
	'ignore_sticky_posts' => 1,
);

if ( ! empty( $cat_ids ) ) {
	$args['category__in'] = $cat_ids;
}

$related_query = new WP_Query( $args );

if ( ! $related_query->have_posts() ) {
	// Fallback to recent posts if no category match
	unset( $args['category__in'] );
	$related_query = new WP_Query( $args );
}

if ( $related_query->have_posts() ) :
	?>
	<section class="sr-related-section" aria-label="<?php esc_attr_e( 'Related Reviews', 'supplement-review' ); ?>">
		<h2 class="sr-related-heading"><?php esc_html_e( 'You May Also Like', 'supplement-review' ); ?></h2>
		<div class="sr-cards-grid-4">
			<?php
			while ( $related_query->have_posts() ) :
				$related_query->the_post();
				get_template_part( 'template-parts/content-card' );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</section>
	<?php
endif;
