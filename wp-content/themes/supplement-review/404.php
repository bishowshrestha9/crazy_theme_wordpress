<?php
/**
 * The template for displaying 404 pages (Not Found)
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

		<section class="error-404 not-found sr-card" style="max-width:720px;margin:40px auto;text-align:center;padding:60px 32px;">
			<div style="font-size:5rem;font-weight:800;color:var(--sr-color-primary);line-height:1;margin-bottom:16px;font-family:var(--sr-font-heading);">
				404
			</div>

			<h1 class="page-title" style="font-size:2rem;margin-bottom:14px;">
				<?php esc_html_e( 'Oops! That Supplement Review Cannot Be Found', 'supplement-review' ); ?>
			</h1>

			<p style="color:var(--sr-color-text-body);font-size:1.05rem;line-height:1.6;margin-bottom:28px;">
				<?php esc_html_e( 'The page or product evaluation you are looking for might have been updated, renamed, or temporarily relocated. Try searching below or explore popular categories.', 'supplement-review' ); ?>
			</p>

			<div style="max-width:500px;margin:0 auto 36px;">
				<?php get_search_form(); ?>
			</div>

			<div style="border-top:1px solid var(--sr-color-border);padding-top:28px;">
				<h3 style="font-size:1.15rem;margin-bottom:16px;"><?php esc_html_e( 'Popular Review Categories', 'supplement-review' ); ?></h3>
				<div style="display:flex;justify-content:center;gap:12px;flex-wrap:wrap;">
					<a href="<?php echo esc_url( home_url( '/category/weight-loss/' ) ); ?>" class="sr-badge-pill">
						<?php esc_html_e( 'Weight Loss', 'supplement-review' ); ?>
					</a>
					<a href="<?php echo esc_url( home_url( '/category/male-enhancement/' ) ); ?>" class="sr-badge-pill">
						<?php esc_html_e( 'Male Enhancement', 'supplement-review' ); ?>
					</a>
					<a href="<?php echo esc_url( home_url( '/category/brain-enhancement/' ) ); ?>" class="sr-badge-pill">
						<?php esc_html_e( 'Brain Enhancement', 'supplement-review' ); ?>
					</a>
					<a href="<?php echo esc_url( home_url( '/category/joint-pain-relief/' ) ); ?>" class="sr-badge-pill">
						<?php esc_html_e( 'Joint Pain Relief', 'supplement-review' ); ?>
					</a>
				</div>
			</div>
		</section>

	</div><!-- .sr-container -->
</main><!-- #primary -->

<?php
get_footer();
