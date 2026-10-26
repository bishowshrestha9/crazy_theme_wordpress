<?php
/**
 * The template for displaying general Archive pages (tags, authors, dates)
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

		<?php sr_breadcrumbs(); ?>

		<header class="sr-archive-header" style="margin-bottom:36px;padding-bottom:20px;border-bottom:1px solid var(--sr-color-border);">
			<?php
			the_archive_title( '<h1 class="sr-archive-title" style="margin-bottom:10px;">', '</h1>' );
			the_archive_description( '<div class="sr-archive-description" style="color:var(--sr-color-text-body);font-size:1.05rem;">', '</div>' );
			?>
		</header>

		<?php if ( have_posts() ) : ?>

			<div class="sr-cards-grid-4">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content-card' );
				endwhile;
				?>
			</div>

			<div style="margin-top:48px;display:flex;justify-content:center;">
				<?php
				the_posts_pagination(
					array(
						'mid_size'  => 2,
						'prev_text' => '&larr; ' . esc_html__( 'Previous', 'supplement-review' ),
						'next_text' => esc_html__( 'Next', 'supplement-review' ) . ' &rarr;',
					)
				);
				?>
			</div>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content-none' ); ?>

		<?php endif; ?>

	</div><!-- .sr-container -->
</main><!-- #primary -->

<?php
get_footer();
