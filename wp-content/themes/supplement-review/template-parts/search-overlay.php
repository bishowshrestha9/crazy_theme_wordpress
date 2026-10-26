<?php
/**
 * Accessible Search Overlay Modal Template Part
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="sr-search-modal" id="srSearchModal" aria-hidden="true" role="dialog" aria-modal="true" aria-labelledby="srSearchTitle">
	<div class="sr-search-backdrop" id="srSearchBackdrop"></div>
	<div class="sr-search-dialog">
		<div class="sr-search-dialog-header">
			<h3 class="sr-search-dialog-title" id="srSearchTitle"><?php esc_html_e( 'Search Supplement Reviews', 'supplement-review' ); ?></h3>
			<button type="button" class="sr-icon-btn" id="srSearchCloseBtn" aria-label="<?php esc_attr_e( 'Close search dialog', 'supplement-review' ); ?>">
				<?php sr_svg( 'close', array( 'width' => 20, 'height' => 20 ) ); ?>
			</button>
		</div>

		<form role="search" method="get" class="sr-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label for="srSearchInput" class="screen-reader-text"><?php esc_html_e( 'Search query', 'supplement-review' ); ?></label>
			<input type="search" id="srSearchInput" class="sr-search-input" placeholder="<?php esc_attr_e( 'Type supplement name, brand, or condition...', 'supplement-review' ); ?>" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
			<button type="submit" class="sr-search-btn">
				<?php sr_svg( 'search', array( 'width' => 18, 'height' => 18 ) ); ?>
				<span><?php esc_html_e( 'Search', 'supplement-review' ); ?></span>
			</button>
		</form>
	</div>
</div>
