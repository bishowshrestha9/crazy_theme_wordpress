<?php
/**
 * Register Gutenberg Block Patterns and Block Styles
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register custom pattern categories and block patterns.
 */
function sr_register_block_patterns() {
	if ( ! function_exists( 'register_block_pattern_category' ) || ! function_exists( 'register_block_pattern' ) ) {
		return;
	}

	// Register Pattern Category
	register_block_pattern_category(
		'supplement-review',
		array(
			'label' => __( 'Supplement Review Components', 'supplement-review' ),
		)
	);

	// 1. Quick Verdict Box
	register_block_pattern(
		'supplement-review/quick-verdict',
		array(
			'title'       => __( 'Quick Verdict Box', 'supplement-review' ),
			'description' => __( 'Highlighted sage green verdict container with summary text.', 'supplement-review' ),
			'categories'  => array( 'supplement-review' ),
			'content'     => '<!-- wp:group {"className":"sr-quick-verdict","layout":{"type":"constrained"}} -->
<div class="wp-block-group sr-quick-verdict">
<h3 class="wp-block-heading">' . esc_html__( 'Quick Verdict', 'supplement-review' ) . '</h3>
<p>' . esc_html__( 'We also researched product reviews and complaints across available clinical information and consumer sources to provide a broader picture of purchasing considerations, product claims, reported experiences, and factors consumers should consider before choosing a liquid metabolic-support supplement.', 'supplement-review' ) . '</p>
</div>
<!-- /wp:group -->',
		)
	);

	// 2. Review Summary Box (as in screenshot)
	register_block_pattern(
		'supplement-review/review-summary',
		array(
			'title'       => __( 'Full Review Summary Box', 'supplement-review' ),
			'description' => __( 'Two-column box featuring product thumbnail, pricing, Buy button, observations, and certifications.', 'supplement-review' ),
			'categories'  => array( 'supplement-review' ),
			'content'     => '<!-- wp:group {"className":"sr-review-summary-box","layout":{"type":"constrained"}} -->
<div class="wp-block-group sr-review-summary-box">
<div class="sr-summary-banner">' . esc_html__( 'Product Review Summary', 'supplement-review' ) . '</div>
<div class="sr-summary-body-grid">
	<div class="sr-summary-col-left">
		<img class="sr-summary-product-img" src="' . esc_url( get_template_directory_uri() . '/assets/images/product-sample.png' ) . '" alt="Product Bottle" />
		<div class="sr-summary-price"><del>$98</del> $49</div>
		<a href="#" class="sr-btn sr-btn-primary sr-btn-lg" target="_blank" rel="noopener noreferrer sponsored nofollow">' . esc_html__( 'Buy Now from Official Website', 'supplement-review' ) . '</a>
	</div>
	<div class="sr-summary-col-right">
		<div>
			<div class="sr-summary-section-label">' . esc_html__( 'USER EXPERIENCE', 'supplement-review' ) . '</div>
			<p>' . esc_html__( 'Users report steadier energy and improved daily metabolic support within 4-8 weeks of use.', 'supplement-review' ) . '</p>
		</div>
		<div>
			<div class="sr-summary-section-label">' . esc_html__( 'OUR OBSERVATION', 'supplement-review' ) . '</div>
			<p>' . esc_html__( 'Chromium picolinate combined with a broad botanical and nutritional blend for metabolic support.', 'supplement-review' ) . '</p>
		</div>
		<div class="sr-verdict-pill-box">
			' . sr_get_svg( 'check-circle', array( 'width' => 16, 'height' => 16 ) ) . '
			<span><strong>' . esc_html__( 'Verdict:', 'supplement-review' ) . '</strong> ' . esc_html__( 'Legit, convenient option for adults seeking daily glucose support.', 'supplement-review' ) . '</span>
		</div>
		<div class="sr-summary-tiles-grid">
			<div class="sr-summary-tile">
				<div class="sr-summary-section-label">' . esc_html__( 'EXPERT RATING', 'supplement-review' ) . '</div>
				<strong>★★★★★ 4.9/5</strong>
			</div>
			<div class="sr-summary-tile">
				<div class="sr-summary-section-label">' . esc_html__( 'BEST FOR', 'supplement-review' ) . '</div>
				<span>' . esc_html__( 'Adults seeking metabolic balance & steady energy.', 'supplement-review' ) . '</span>
			</div>
			<div class="sr-summary-tile">
				<div class="sr-summary-section-label">' . esc_html__( 'CERTIFICATION', 'supplement-review' ) . '</div>
				<div class="sr-tile-badge-icon">' . sr_get_svg( 'award', array( 'width' => 14, 'height' => 14 ) ) . ' ' . esc_html__( 'FDA & GMP-Certified Facility', 'supplement-review' ) . '</div>
			</div>
			<div class="sr-summary-tile">
				<div class="sr-summary-section-label">' . esc_html__( 'MONEY BACK GUARANTEE', 'supplement-review' ) . '</div>
				<strong>' . esc_html__( '60 Days 100% Refund Policy', 'supplement-review' ) . '</strong>
			</div>
		</div>
	</div>
</div>
</div>
<!-- /wp:group -->',
		)
	);

	// 3. Pros and Cons Grid
	register_block_pattern(
		'supplement-review/pros-cons',
		array(
			'title'       => __( 'Pros and Cons Comparison', 'supplement-review' ),
			'description' => __( 'Side-by-side cards showing positive benefits and consumer drawbacks.', 'supplement-review' ),
			'categories'  => array( 'supplement-review' ),
			'content'     => '<!-- wp:group {"className":"sr-pros-cons-grid","layout":{"type":"constrained"}} -->
<div class="wp-block-group sr-pros-cons-grid">
	<div class="sr-pros-card">
		<div class="sr-pro-header">' . sr_get_svg( 'thumbs-up', array( 'width' => 20, 'height' => 20 ) ) . ' <span>' . esc_html__( 'What We Liked (Pros)', 'supplement-review' ) . '</span></div>
		<ul class="sr-pro-list">
			<li>' . sr_get_svg( 'check', array( 'width' => 16, 'height' => 16 ) ) . ' <span>' . esc_html__( 'Fast-acting liquid dropper formula for superior absorption', 'supplement-review' ) . '</span></li>
			<li>' . sr_get_svg( 'check', array( 'width' => 16, 'height' => 16 ) ) . ' <span>' . esc_html__( 'Non-GMO, vegan, and free of harsh chemical stimulants', 'supplement-review' ) . '</span></li>
			<li>' . sr_get_svg( 'check', array( 'width' => 16, 'height' => 16 ) ) . ' <span>' . esc_html__( 'Manufactured in an FDA-registered, cGMP-certified facility', 'supplement-review' ) . '</span></li>
			<li>' . sr_get_svg( 'check', array( 'width' => 16, 'height' => 16 ) ) . ' <span>' . esc_html__( 'Backed by a full 60-day money-back guarantee', 'supplement-review' ) . '</span></li>
		</ul>
	</div>
	<div class="sr-cons-card">
		<div class="sr-con-header">' . sr_get_svg( 'thumbs-down', array( 'width' => 20, 'height' => 20 ) ) . ' <span>' . esc_html__( 'What to Consider (Cons)', 'supplement-review' ) . '</span></div>
		<ul class="sr-con-list">
			<li>' . sr_get_svg( 'close', array( 'width' => 16, 'height' => 16 ) ) . ' <span>' . esc_html__( 'Only available directly through the official manufacturer website', 'supplement-review' ) . '</span></li>
			<li>' . sr_get_svg( 'close', array( 'width' => 16, 'height' => 16 ) ) . ' <span>' . esc_html__( 'Stock shortages occur during promotional sale events', 'supplement-review' ) . '</span></li>
			<li>' . sr_get_svg( 'close', array( 'width' => 16, 'height' => 16 ) ) . ' <span>' . esc_html__( 'Individual results require 30-60 days of consistent daily usage', 'supplement-review' ) . '</span></li>
		</ul>
	</div>
</div>
<!-- /wp:group -->',
		)
	);

	// 4. Key Ingredients Grid
	register_block_pattern(
		'supplement-review/ingredients-grid',
		array(
			'title'       => __( 'Key Ingredients 3-Column Grid', 'supplement-review' ),
			'description' => __( 'Clean 3-column card grid highlighting primary formula ingredients.', 'supplement-review' ),
			'categories'  => array( 'supplement-review' ),
			'content'     => '<!-- wp:group {"className":"sr-ingredients-grid","layout":{"type":"constrained"}} -->
<div class="wp-block-group sr-ingredients-grid">
	<div class="sr-ingredient-card">
		<div class="sr-ingredient-icon">' . sr_get_svg( 'leaf', array( 'width' => 28, 'height' => 28 ) ) . '</div>
		<h4 class="sr-ingredient-title">' . esc_html__( 'Chromium Picolinate', 'supplement-review' ) . '</h4>
		<p class="sr-ingredient-desc">' . esc_html__( 'Essential trace mineral shown in clinical studies to assist healthy insulin receptor sensitivity.', 'supplement-review' ) . '</p>
	</div>
	<div class="sr-ingredient-card">
		<div class="sr-ingredient-icon">' . sr_get_svg( 'leaf', array( 'width' => 28, 'height' => 28 ) ) . '</div>
		<h4 class="sr-ingredient-title">' . esc_html__( 'Gymnema Sylvestre', 'supplement-review' ) . '</h4>
		<p class="sr-ingredient-desc">' . esc_html__( 'Known as the sugar destroyer, helps curb intense sweet cravings and slows intestinal sugar uptake.', 'supplement-review' ) . '</p>
	</div>
	<div class="sr-ingredient-card">
		<div class="sr-ingredient-icon">' . sr_get_svg( 'leaf', array( 'width' => 28, 'height' => 28 ) ) . '</div>
		<h4 class="sr-ingredient-title">' . esc_html__( 'Cinnamon Bark Extract', 'supplement-review' ) . '</h4>
		<p class="sr-ingredient-desc">' . esc_html__( 'Rich in polyphenols that mimic natural metabolic pathways and support daily post-meal balance.', 'supplement-review' ) . '</p>
	</div>
</div>
<!-- /wp:group -->',
		)
	);

	// 5. Rating Table
	register_block_pattern(
		'supplement-review/rating-table',
		array(
			'title'       => __( 'Editorial Rating Table', 'supplement-review' ),
			'description' => __( 'Responsive evaluation table with Key Parameters, Description, and Score.', 'supplement-review' ),
			'categories'  => array( 'supplement-review' ),
			'content'     => '<div class="sr-rating-table-wrap">
<table class="sr-rating-table">
	<thead>
		<tr>
			<th>' . esc_html__( 'Key Parameter', 'supplement-review' ) . '</th>
			<th>' . esc_html__( 'Editorial Description', 'supplement-review' ) . '</th>
			<th>' . esc_html__( 'Score', 'supplement-review' ) . '</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><strong>' . esc_html__( 'Ingredient Quality', 'supplement-review' ) . '</strong></td>
			<td>' . esc_html__( 'Standardized extracts, zero fillers, non-GMO verified', 'supplement-review' ) . '</td>
			<td><span class="sr-badge-pill">4.9 / 5</span></td>
		</tr>
		<tr>
			<td><strong>' . esc_html__( 'Efficacy & Science', 'supplement-review' ) . '</strong></td>
			<td>' . esc_html__( 'Clinically validated dosages supported by peer-reviewed research', 'supplement-review' ) . '</td>
			<td><span class="sr-badge-pill">4.8 / 5</span></td>
		</tr>
		<tr>
			<td><strong>' . esc_html__( 'Value for Money', 'supplement-review' ) . '</strong></td>
			<td>' . esc_html__( 'Competitive tiered pricing with discounts on multi-bottle bundles', 'supplement-review' ) . '</td>
			<td><span class="sr-badge-pill">4.7 / 5</span></td>
		</tr>
		<tr>
			<td><strong>' . esc_html__( 'Consumer Safety', 'supplement-review' ) . '</strong></td>
			<td>' . esc_html__( 'Manufactured in audited cGMP facilities with 3rd-party lab testing', 'supplement-review' ) . '</td>
			<td><span class="sr-badge-pill">5.0 / 5</span></td>
		</tr>
	</tbody>
</table>
</div>',
		)
	);

	// 6. Accessible FAQ Accordion
	register_block_pattern(
		'supplement-review/faq-accordion',
		array(
			'title'       => __( 'FAQ Accordion List', 'supplement-review' ),
			'description' => __( 'Accessible FAQ accordion replicating the target design with exact questions and answers.', 'supplement-review' ),
			'categories'  => array( 'supplement-review' ),
			'content'     => '<!-- wp:pattern {"slug":"supplement-review/faq-accordion"} /-->',
		)
	);

	// 7. Sources & Citations Section
	register_block_pattern(
		'supplement-review/sources',
		array(
			'title'       => __( 'Sources & Citations Card', 'supplement-review' ),
			'description' => __( 'Academic citations card with wrapped external reference URLs.', 'supplement-review' ),
			'categories'  => array( 'supplement-review' ),
			'content'     => '<!-- wp:pattern {"slug":"supplement-review/sources"} /-->',
		)
	);

	// 8. Write a Review Form Section
	register_block_pattern(
		'supplement-review/write-a-review',
		array(
			'title'       => __( 'Write a Review Form', 'supplement-review' ),
			'description' => __( 'Pixel-accurate review submission form matching target design.', 'supplement-review' ),
			'categories'  => array( 'supplement-review' ),
			'content'     => '<!-- wp:pattern {"slug":"supplement-review/write-a-review"} /-->',
		)
	);

	// Register Custom Block Styles
	if ( function_exists( 'register_block_style' ) ) {
		// Group Block Card Style
		register_block_style(
			'core/group',
			array(
				'name'  => 'sr-card',
				'label' => __( 'Supplement Card', 'supplement-review' ),
			)
		);

		// Table Rating Style
		register_block_style(
			'core/table',
			array(
				'name'  => 'sr-rating-table',
				'label' => __( 'Review Rating Table', 'supplement-review' ),
			)
		);

		// List Pros Style
		register_block_style(
			'core/list',
			array(
				'name'  => 'sr-pro-list',
				'label' => __( 'Pros List (Checkmarks)', 'supplement-review' ),
			)
		);

		// List Cons Style
		register_block_style(
			'core/list',
			array(
				'name'  => 'sr-con-list',
				'label' => __( 'Cons List (Crosses)', 'supplement-review' ),
			)
		);

		// Pill Button Style
		register_block_style(
			'core/button',
			array(
				'name'  => 'sr-pill',
				'label' => __( 'Sage Pill Button', 'supplement-review' ),
			)
		);
	}
}
add_action( 'init', 'sr_register_block_patterns' );

