<?php
/**
 * Title: Key Ingredients 3-Column Grid
 * Slug: supplement-review/ingredients-grid
 * Categories: supplement-review
 * Description: Clean 3-column card grid highlighting primary formula ingredients.
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"className":"sr-ingredients-grid","layout":{"type":"constrained"}} -->
<div class="wp-block-group sr-ingredients-grid">
	<div class="sr-ingredient-card">
		<div class="sr-ingredient-icon"><?php sr_svg( 'leaf', array( 'width' => 28, 'height' => 28 ) ); ?></div>
		<h4 class="sr-ingredient-title"><?php esc_html_e( 'Chromium Picolinate', 'supplement-review' ); ?></h4>
		<p class="sr-ingredient-desc"><?php esc_html_e( 'Essential trace mineral shown in clinical studies to assist healthy insulin receptor sensitivity.', 'supplement-review' ); ?></p>
	</div>
	<div class="sr-ingredient-card">
		<div class="sr-ingredient-icon"><?php sr_svg( 'leaf', array( 'width' => 28, 'height' => 28 ) ); ?></div>
		<h4 class="sr-ingredient-title"><?php esc_html_e( 'Gymnema Sylvestre', 'supplement-review' ); ?></h4>
		<p class="sr-ingredient-desc"><?php esc_html_e( 'Known as the sugar destroyer, helps curb intense sweet cravings and slows intestinal sugar uptake.', 'supplement-review' ); ?></p>
	</div>
	<div class="sr-ingredient-card">
		<div class="sr-ingredient-icon"><?php sr_svg( 'leaf', array( 'width' => 28, 'height' => 28 ) ); ?></div>
		<h4 class="sr-ingredient-title"><?php esc_html_e( 'Cinnamon Bark Extract', 'supplement-review' ); ?></h4>
		<p class="sr-ingredient-desc"><?php esc_html_e( 'Rich in polyphenols that mimic natural metabolic pathways and support daily post-meal balance.', 'supplement-review' ); ?></p>
	</div>
</div>
<!-- /wp:group -->
