<?php
/**
 * Title: Pros and Cons Comparison
 * Slug: supplement-review/pros-cons
 * Categories: supplement-review
 * Description: Side-by-side cards showing positive benefits and consumer drawbacks.
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"className":"sr-pros-cons-grid","layout":{"type":"constrained"}} -->
<div class="wp-block-group sr-pros-cons-grid">
	<div class="sr-pros-card">
		<div class="sr-pro-header"><?php sr_svg( 'thumbs-up', array( 'width' => 20, 'height' => 20 ) ); ?> <span><?php esc_html_e( 'What We Liked (Pros)', 'supplement-review' ); ?></span></div>
		<ul class="sr-pro-list">
			<li><?php sr_svg( 'check', array( 'width' => 16, 'height' => 16 ) ); ?> <span><?php esc_html_e( 'Fast-acting liquid dropper formula for superior absorption', 'supplement-review' ); ?></span></li>
			<li><?php sr_svg( 'check', array( 'width' => 16, 'height' => 16 ) ); ?> <span><?php esc_html_e( 'Non-GMO, vegan, and free of harsh chemical stimulants', 'supplement-review' ); ?></span></li>
			<li><?php sr_svg( 'check', array( 'width' => 16, 'height' => 16 ) ); ?> <span><?php esc_html_e( 'Manufactured in an FDA-registered, cGMP-certified facility', 'supplement-review' ); ?></span></li>
			<li><?php sr_svg( 'check', array( 'width' => 16, 'height' => 16 ) ); ?> <span><?php esc_html_e( 'Backed by a full 60-day money-back guarantee', 'supplement-review' ); ?></span></li>
		</ul>
	</div>
	<div class="sr-cons-card">
		<div class="sr-con-header"><?php sr_svg( 'thumbs-down', array( 'width' => 20, 'height' => 20 ) ); ?> <span><?php esc_html_e( 'What to Consider (Cons)', 'supplement-review' ); ?></span></div>
		<ul class="sr-con-list">
			<li><?php sr_svg( 'close', array( 'width' => 16, 'height' => 16 ) ); ?> <span><?php esc_html_e( 'Only available directly through the official manufacturer website', 'supplement-review' ); ?></span></li>
			<li><?php sr_svg( 'close', array( 'width' => 16, 'height' => 16 ) ); ?> <span><?php esc_html_e( 'Stock shortages occur during promotional sale events', 'supplement-review' ); ?></span></li>
			<li><?php sr_svg( 'close', array( 'width' => 16, 'height' => 16 ) ); ?> <span><?php esc_html_e( 'Individual results require 30-60 days of consistent daily usage', 'supplement-review' ); ?></span></li>
		</ul>
	</div>
</div>
<!-- /wp:group -->
