<?php
/**
 * Standard Page Content Template Part
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'sr-card' ); ?>>
	<header class="entry-header" style="margin-bottom:24px;border-bottom:1px solid var(--sr-color-border);padding-bottom:16px;">
		<?php the_title( '<h1 class="entry-title" style="margin:0;">', '</h1>' ); ?>
	</header>

	<div class="entry-content">
		<?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'supplement-review' ),
				'after'  => '</div>',
			)
		);
		?>
	</div>
</article>
