<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="no-results not-found sr-card" style="text-align:center;padding:48px 24px;">
	<header class="page-header" style="margin-bottom:20px;">
		<h2 class="page-title"><?php esc_html_e( 'Nothing Found', 'supplement-review' ); ?></h2>
	</header>

	<div class="page-content" style="max-width:560px;margin:0 auto;">
		<?php if ( is_search() ) : ?>
			<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords or browse our categories.', 'supplement-review' ); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'supplement-review' ); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</section>
