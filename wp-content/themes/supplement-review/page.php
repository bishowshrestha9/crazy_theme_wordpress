<?php
/**
 * The template for displaying all pages
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main id="primary" class="site-main">
	<div class="sr-container sr-content-container">

		<?php sr_breadcrumbs(); ?>

		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content-page' );

			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
		endwhile;
		?>

	</div><!-- .sr-container -->
</main><!-- #primary -->

<?php
get_footer();
