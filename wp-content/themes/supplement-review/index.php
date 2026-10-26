<?php
/**
 * The main template file
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

		<?php if ( have_posts() ) : ?>

			<header class="page-header" style="margin-bottom:32px;">
				<h1 class="page-title"><?php esc_html_e( 'Supplement Reviews & Articles', 'supplement-review' ); ?></h1>
			</header>

			<div class="sr-cards-grid-4">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content-card' );
				endwhile;
				?>
			</div>

			<div style="margin-top:40px;display:flex;justify-content:center;">
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
