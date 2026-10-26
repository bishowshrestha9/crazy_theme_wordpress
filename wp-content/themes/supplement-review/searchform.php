<?php
/**
 * Accessible Search Form Template
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$unique_id = wp_unique_id( 'search-form-' );
?>
<form role="search" method="get" class="sr-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="<?php echo esc_attr( $unique_id ); ?>" class="screen-reader-text">
		<?php esc_html_e( 'Search for supplement reviews:', 'supplement-review' ); ?>
	</label>
	<input type="search" id="<?php echo esc_attr( $unique_id ); ?>" class="sr-search-input" placeholder="<?php esc_attr_e( 'Search reviews, ingredients, brands...', 'supplement-review' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<button type="submit" class="sr-search-btn" aria-label="<?php esc_attr_e( 'Submit search', 'supplement-review' ); ?>">
		<?php sr_svg( 'search', array( 'width' => 18, 'height' => 18 ) ); ?>
		<span><?php esc_html_e( 'Search', 'supplement-review' ); ?></span>
	</button>
</form>
