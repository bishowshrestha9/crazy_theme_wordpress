<?php
/**
 * Title: Full Review Summary Box
 * Slug: supplement-review/review-summary
 * Categories: supplement-review
 * Description: Two-column box featuring product thumbnail, pricing, Buy button, observations, and certifications.
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"className":"sr-review-summary-box","layout":{"type":"constrained"}} -->
<div class="wp-block-group sr-review-summary-box">
<div class="sr-summary-banner"><?php esc_html_e( 'Product Review Summary', 'supplement-review' ); ?></div>
<div class="sr-summary-body-grid">
	<div class="sr-summary-col-left">
		<img class="sr-summary-product-img" src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-sample.png' ); ?>" alt="Product Bottle" />
		<div class="sr-summary-price"><del>$98</del> $49</div>
		<a href="#" class="sr-btn sr-btn-primary sr-btn-lg" target="_blank" rel="noopener noreferrer sponsored nofollow"><?php esc_html_e( 'Buy Now from Official Website', 'supplement-review' ); ?></a>
	</div>
	<div class="sr-summary-col-right">
		<div>
			<div class="sr-summary-section-label"><?php esc_html_e( 'USER EXPERIENCE', 'supplement-review' ); ?></div>
			<p><?php esc_html_e( 'Users report steadier energy and improved daily metabolic support within 4-8 weeks of use.', 'supplement-review' ); ?></p>
		</div>
		<div>
			<div class="sr-summary-section-label"><?php esc_html_e( 'OUR OBSERVATION', 'supplement-review' ); ?></div>
			<p><?php esc_html_e( 'Chromium picolinate combined with a broad botanical and nutritional blend for metabolic support.', 'supplement-review' ); ?></p>
		</div>
		<div class="sr-verdict-pill-box">
			<?php sr_svg( 'check-circle', array( 'width' => 16, 'height' => 16 ) ); ?>
			<span><strong><?php esc_html_e( 'Verdict:', 'supplement-review' ); ?></strong> <?php esc_html_e( 'Legit, convenient option for adults seeking daily glucose support.', 'supplement-review' ); ?></span>
		</div>
		<div class="sr-summary-tiles-grid">
			<div class="sr-summary-tile">
				<div class="sr-summary-section-label"><?php esc_html_e( 'EXPERT RATING', 'supplement-review' ); ?></div>
				<strong>★★★★★ 4.9/5</strong>
			</div>
			<div class="sr-summary-tile">
				<div class="sr-summary-section-label"><?php esc_html_e( 'BEST FOR', 'supplement-review' ); ?></div>
				<span><?php esc_html_e( 'Adults seeking metabolic balance & steady energy.', 'supplement-review' ); ?></span>
			</div>
			<div class="sr-summary-tile">
				<div class="sr-summary-section-label"><?php esc_html_e( 'CERTIFICATION', 'supplement-review' ); ?></div>
				<div class="sr-tile-badge-icon"><?php sr_svg( 'award', array( 'width' => 14, 'height' => 14 ) ); ?> <?php esc_html_e( 'FDA & GMP-Certified Facility', 'supplement-review' ); ?></div>
			</div>
			<div class="sr-summary-tile">
				<div class="sr-summary-section-label"><?php esc_html_e( 'MONEY BACK GUARANTEE', 'supplement-review' ); ?></div>
				<strong><?php esc_html_e( '60 Days 100% Refund Policy', 'supplement-review' ); ?></strong>
			</div>
		</div>
	</div>
</div>
</div>
<!-- /wp:group -->
