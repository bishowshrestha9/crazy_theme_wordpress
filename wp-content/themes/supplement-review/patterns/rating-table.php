<?php
/**
 * Title: Editorial Rating Table
 * Slug: supplement-review/rating-table
 * Categories: supplement-review
 * Description: Responsive evaluation table with Key Parameters, Description, and Score.
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="sr-rating-table-wrap">
<table class="sr-rating-table">
	<thead>
		<tr>
			<th><?php esc_html_e( 'Key Parameter', 'supplement-review' ); ?></th>
			<th><?php esc_html_e( 'Editorial Description', 'supplement-review' ); ?></th>
			<th><?php esc_html_e( 'Score', 'supplement-review' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><strong><?php esc_html_e( 'Ingredient Quality', 'supplement-review' ); ?></strong></td>
			<td><?php esc_html_e( 'Standardized extracts, zero fillers, non-GMO verified', 'supplement-review' ); ?></td>
			<td><span class="sr-badge-pill">4.9 / 5</span></td>
		</tr>
		<tr>
			<td><strong><?php esc_html_e( 'Efficacy & Science', 'supplement-review' ); ?></strong></td>
			<td><?php esc_html_e( 'Clinically validated dosages supported by peer-reviewed research', 'supplement-review' ); ?></td>
			<td><span class="sr-badge-pill">4.8 / 5</span></td>
		</tr>
		<tr>
			<td><strong><?php esc_html_e( 'Value for Money', 'supplement-review' ); ?></strong></td>
			<td><?php esc_html_e( 'Competitive tiered pricing with discounts on multi-bottle bundles', 'supplement-review' ); ?></td>
			<td><span class="sr-badge-pill">4.7 / 5</span></td>
		</tr>
		<tr>
			<td><strong><?php esc_html_e( 'Consumer Safety', 'supplement-review' ); ?></strong></td>
			<td><?php esc_html_e( 'Manufactured in audited cGMP facilities with 3rd-party lab testing', 'supplement-review' ); ?></td>
			<td><span class="sr-badge-pill">5.0 / 5</span></td>
		</tr>
	</tbody>
</table>
</div>
