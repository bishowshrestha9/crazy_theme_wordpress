<?php
/**
 * Title: FAQ Accordion List
 * Slug: supplement-review/faq-accordion
 * Categories: supplement-review
 * Description: Accessible FAQ accordion replicating the target design with exact questions and answers.
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section class="sf-faq-wrapper" data-single-open="true" aria-label="<?php esc_attr_e( 'Frequently Asked Questions', 'supplement-review' ); ?>">
	<div class="sf-section-header">
		<h2 class="sf-heading"><?php esc_html_e( 'How to Use Glucose Reset Metabolic Support?', 'supplement-review' ); ?></h2>
		<hr class="sf-divider" aria-hidden="true" />
	</div>

	<div class="sf-faq-list" role="region">
		<!-- FAQ 1 (Open by Default) -->
		<div class="sf-faq-card sf-open">
			<button type="button" class="sf-faq-button" aria-expanded="true">
				<span class="sf-faq-icon" aria-hidden="true"></span>
				<span class="sf-faq-question"><?php esc_html_e( 'What is GlucoReset drops?', 'supplement-review' ); ?></span>
			</button>
			<div class="sf-faq-answer-panel" aria-hidden="false">
				<div class="sf-faq-answer-inner">
					<p><?php esc_html_e( 'Gluco Reset metabolic formula is a liquid Blood Sugar supplement designed to support healthy metabolism, glucose metabolism, balanced energy and everyday metabolic wellness.', 'supplement-review' ); ?></p>
				</div>
			</div>
		</div>

		<!-- FAQ 2 -->
		<div class="sf-faq-card">
			<button type="button" class="sf-faq-button" aria-expanded="false">
				<span class="sf-faq-icon" aria-hidden="true"></span>
				<span class="sf-faq-question"><?php esc_html_e( 'How much does Gluco Reset cost?', 'supplement-review' ); ?></span>
			</button>
			<div class="sf-faq-answer-panel" aria-hidden="true">
				<div class="sf-faq-answer-inner">
					<p><?php esc_html_e( 'Gluco Reset is offered in several discounted packages starting at $12.99 per bottle for multi-bottle bundles, up to $17.99 for a single starter bottle, all backed by free domestic shipping.', 'supplement-review' ); ?></p>
				</div>
			</div>
		</div>

		<!-- FAQ 3 -->
		<div class="sf-faq-card">
			<button type="button" class="sf-faq-button" aria-expanded="false">
				<span class="sf-faq-icon" aria-hidden="true"></span>
				<span class="sf-faq-question"><?php esc_html_e( 'How should I take Gluco Reset glucose support supplement?', 'supplement-review' ); ?></span>
			</button>
			<div class="sf-faq-answer-panel" aria-hidden="true">
				<div class="sf-faq-answer-inner">
					<p><?php esc_html_e( 'Take 1 full dropper (approximately 1 mL) under the tongue each morning before breakfast. Hold for 30 seconds before swallowing for optimal sublingual absorption.', 'supplement-review' ); ?></p>
				</div>
			</div>
		</div>

		<!-- FAQ 4 -->
		<div class="sf-faq-card">
			<button type="button" class="sf-faq-button" aria-expanded="false">
				<span class="sf-faq-icon" aria-hidden="true"></span>
				<span class="sf-faq-question"><?php esc_html_e( 'How long does GlucoReset glucose supplement take to use?', 'supplement-review' ); ?></span>
			</button>
			<div class="sf-faq-answer-panel" aria-hidden="true">
				<div class="sf-faq-answer-inner">
					<p><?php esc_html_e( 'While many users notice steady morning energy within 7 to 14 days, clinical studies on active ingredients suggest peak metabolic benefits after 60 to 90 days of consistent daily use.', 'supplement-review' ); ?></p>
				</div>
			</div>
		</div>

		<!-- FAQ 5 -->
		<div class="sf-faq-card">
			<button type="button" class="sf-faq-button" aria-expanded="false">
				<span class="sf-faq-icon" aria-hidden="true"></span>
				<span class="sf-faq-question"><?php esc_html_e( 'Is there any Gluco Reset money-back guarantee?', 'supplement-review' ); ?></span>
			</button>
			<div class="sf-faq-answer-panel" aria-hidden="true">
				<div class="sf-faq-answer-inner">
					<p><?php esc_html_e( 'Yes, all orders purchased through the official merchant link are protected by an unconditional 60-day 100% money-back guarantee with zero questions asked.', 'supplement-review' ); ?></p>
				</div>
			</div>
		</div>

		<!-- FAQ 6 -->
		<div class="sf-faq-card">
			<button type="button" class="sf-faq-button" aria-expanded="false">
				<span class="sf-faq-icon" aria-hidden="true"></span>
				<span class="sf-faq-question"><?php esc_html_e( 'Can children use Gluco Reset Metabolic support supplement?', 'supplement-review' ); ?></span>
			</button>
			<div class="sf-faq-answer-panel" aria-hidden="true">
				<div class="sf-faq-answer-inner">
					<p><?php esc_html_e( 'No, Gluco Reset is specifically formulated for adults aged 18 and older. Pregnant or nursing mothers and individuals with known medical conditions should consult a physician.', 'supplement-review' ); ?></p>
				</div>
			</div>
		</div>
	</div>
</section>
