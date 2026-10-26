<?php
/**
 * The template for displaying all single posts / supplement reviews
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main">
	<div class="sr-container">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<!-- Review Header Area -->
			<header class="sr-review-header">
				<!-- Breadcrumbs with Green Pill -->
				<?php sr_breadcrumbs(); ?>

				<!-- H1 Review Title -->
				<h1 class="sr-review-title"><?php the_title(); ?></h1>

				<!-- Trust Badges (Disclaimer | Fact Check | Review Process) -->
				<?php sr_trust_badges_row(); ?>

				<!-- Author & Medical Credential Bar -->
				<?php sr_review_author_bar(); ?>
			</header>

			<?php $has_product_sidebar = function_exists( 'crazytheme_has_product_sidebar' ) ? crazytheme_has_product_sidebar( get_the_ID() ) : true; ?>
			<!-- Two-Column Review Layout (or Full-Width when sidebar is disabled) -->
			<div class="sr-review-layout <?php echo ! $has_product_sidebar ? 'sr-no-sidebar' : ''; ?>">
				<!-- Main Content Column -->
				<div class="sr-review-main <?php echo ! $has_product_sidebar ? 'sr-review-main--fullwidth' : ''; ?>">
					<!-- Table of Contents 3-Column Card -->
					<?php get_template_part( 'template-parts/toc' ); ?>

					<!-- Compact Sticky Contents Bar (Anchored inside article column) -->
					<?php sr_render_sticky_contents_bar( get_the_ID() ); ?>

					<!-- Article Content -->
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'sr-review-content entry-content' ); ?>>
						<?php
						the_content();

						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'supplement-review' ),
								'after'  => '</div>',
							)
						);
						?>
					</article>

					<!-- Customer Reviews & "Write a Review" Form -->
					<?php
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					?>
				</div><!-- .sr-review-main -->

				<?php if ( $has_product_sidebar ) : ?>
					<!-- Sticky Sidebar: Product Summary Card (Ends at bottom of review form) -->
					<aside class="sr-review-sidebar ps-sidebar">
						<?php get_template_part( 'template-parts/product-summary' ); ?>
					</aside>
				<?php endif; ?>
			</div><!-- .sr-review-layout.ps-layout -->

			<!-- Author Bio Card (OUTSIDE and AFTER two-column grid, spans full container width) -->
			<?php get_template_part( 'template-parts/author-card' ); ?>

			<!-- "You may also like" Related Reviews -->
			<?php get_template_part( 'template-parts/related-posts' ); ?>

		<?php endwhile; ?>

	</div><!-- .sr-container -->
</main><!-- #primary -->

<?php
get_footer();
