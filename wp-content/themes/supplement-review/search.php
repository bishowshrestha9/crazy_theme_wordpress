<?php
/**
 * The template for displaying search results pages
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

		<header class="sr-search-results-header" style="margin-bottom:36px;padding-bottom:20px;border-bottom:1px solid var(--sr-color-border);">
			<h1 class="page-title" style="margin-bottom:12px;">
				<?php
				echo wp_kses(
					sprintf(
						/* translators: %s: Search query */
						__( 'Search Results for: %s', 'supplement-review' ),
						'<span>' . esc_html( get_search_query() ) . '</span>'
					),
					array(
						'span' => array(),
					)
				);
				?>
			</h1>
			<p style="color:var(--sr-color-text-muted);margin:0;">
				<?php
				global $wp_query;
				printf(
					/* translators: %d: Result count */
					esc_html( _n( 'Found %d review matching your search criteria.', 'Found %d reviews matching your search criteria.', (int) $wp_query->found_posts, 'supplement-review' ) ),
					(int) $wp_query->found_posts
				);
				?>
			</p>
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
