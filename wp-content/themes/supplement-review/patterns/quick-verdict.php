<?php
/**
 * Title: Quick Verdict Box
 * Slug: supplement-review/quick-verdict
 * Categories: supplement-review
 * Description: Highlighted sage green verdict container with summary text.
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!-- wp:group {"className":"sr-quick-verdict","layout":{"type":"constrained"}} -->
<div class="wp-block-group sr-quick-verdict">
<h3 class="wp-block-heading"><?php esc_html_e( 'Quick Verdict', 'supplement-review' ); ?></h3>
<p><?php esc_html_e( 'We also researched product reviews and complaints across available clinical information and consumer sources to provide a broader picture of purchasing considerations, product claims, reported experiences, and factors consumers should consider before choosing a liquid metabolic-support supplement.', 'supplement-review' ); ?></p>
</div>
<!-- /wp:group -->
