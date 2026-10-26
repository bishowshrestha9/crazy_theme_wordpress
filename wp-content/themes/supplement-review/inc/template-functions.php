<?php
/**
 * Template Functions and Utility Helpers
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Calculates estimated reading time for post content.
 *
 * @param string|int|null $content Post content or Post ID.
 * @return int Minutes to read.
 */
function sr_get_reading_time( $content = null ) {
	if ( is_numeric( $content ) ) {
		$post = get_post( $content );
		$content = $post ? $post->post_content : '';
	} elseif ( null === $content ) {
		global $post;
		$content = $post ? $post->post_content : '';
	}

	$clean_text = wp_strip_all_tags( (string) $content );
	$word_count = str_word_count( $clean_text );
	$words_per_minute = 200;

	return max( 1, (int) ceil( $word_count / $words_per_minute ) );
}

/**
 * Calculates auto-discount percentage between old and current price.
 *
 * @param float|string $price Current price.
 * @param float|string $old_price Original / Old price.
 * @return int Discount percentage (0-100).
 */
function sr_calculate_discount_percentage( $price, $old_price ) {
	$clean_price = (float) preg_replace( '/[^0-9.]/', '', (string) $price );
	$clean_old   = (float) preg_replace( '/[^0-9.]/', '', (string) $old_price );

	if ( $clean_old > $clean_price && $clean_old > 0 ) {
		return (int) round( ( ( $clean_old - $clean_price ) / $clean_old ) * 100 );
	}

	return 0;
}

/**
 * Renders star rating HTML using SVG stars.
 *
 * @param float $rating Rating value between 0 and 5.
 * @param bool  $show_number Whether to show the numeric rating e.g. 4.9/5.
 * @return string HTML output.
 */
function sr_get_star_rating_html( $rating, $show_number = true ) {
	$rating = floatval( $rating );
	$rating = max( 0, min( 5, $rating ) );

	$full_stars  = (int) floor( $rating );
	$has_half    = ( $rating - $full_stars ) >= 0.3 && ( $rating - $full_stars ) <= 0.8;
	$empty_stars = 5 - $full_stars - ( $has_half ? 1 : 0 );

	$output = '<div class="sr-stars" aria-label="' . sprintf( esc_attr__( 'Rated %1$.1f out of 5 stars', 'supplement-review' ), $rating ) . '">';

	for ( $i = 0; $i < $full_stars; $i++ ) {
		$output .= sr_get_svg( 'star', array( 'width' => 16, 'height' => 16 ) );
	}

	if ( $has_half ) {
		$output .= sr_get_svg( 'star-half', array( 'width' => 16, 'height' => 16 ) );
	}

	for ( $i = 0; $i < $empty_stars; $i++ ) {
		$output .= sr_get_svg( 'star-empty', array( 'width' => 16, 'height' => 16 ) );
	}

	if ( $show_number ) {
		$output .= '<span class="sr-rating-num">' . esc_html( number_format( $rating, 1 ) ) . '/5</span>';
	}

	$output .= '</div>';

	return $output;
}

/**
 * Renders breadcrumb navigation with Schema.org BreadcrumbList.
 */
function sr_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}

	$items = array();

	// Home
	$items[] = array(
		'title' => __( 'Home', 'supplement-review' ),
		'url'   => home_url( '/' ),
	);

	if ( is_singular( 'post' ) ) {
		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			$category = $categories[0];
			$items[] = array(
				'title' => $category->name,
				'url'   => get_category_link( $category->term_id ),
			);
		}
		$items[] = array(
			'title'   => get_the_title(),
			'current' => true,
		);
	} elseif ( is_category() ) {
		$items[] = array(
			'title'   => single_cat_title( '', false ),
			'current' => true,
		);
	} elseif ( is_page() ) {
		global $post;
		if ( $post->post_parent ) {
			$parent_id = $post->post_parent;
			$items[] = array(
				'title' => get_the_title( $parent_id ),
				'url'   => get_permalink( $parent_id ),
			);
		}
		$items[] = array(
			'title'   => get_the_title(),
			'current' => true,
		);
	} elseif ( is_search() ) {
		$items[] = array(
			'title'   => sprintf( __( 'Search: %s', 'supplement-review' ), get_search_query() ),
			'current' => true,
		);
	} elseif ( is_404() ) {
		$items[] = array(
			'title'   => __( '404 Not Found', 'supplement-review' ),
			'current' => true,
		);
	}

	if ( empty( $items ) ) {
		return;
	}

	echo '<nav class="sr-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'supplement-review' ) . '">';
	echo '<ol class="sr-breadcrumb-list" style="display:flex;align-items:center;list-style:none;padding:0;margin:0;gap:8px;">';

	$count = count( $items );
	foreach ( $items as $index => $item ) {
		echo '<li class="sr-breadcrumb-item" style="display:inline-flex;align-items:center;gap:8px;">';
		if ( ! empty( $item['current'] ) ) {
			echo '<span class="sr-breadcrumb-current" aria-current="page">' . esc_html( $item['title'] ) . '</span>';
		} else {
			echo '<a href="' . esc_url( $item['url'] ) . '">' . esc_html( $item['title'] ) . '</a>';
			echo '<span class="sr-breadcrumb-sep" aria-hidden="true">&#8250;</span>';
		}
		echo '</li>';
	}

	echo '</ol>';
	echo '</nav>';
}

/**
 * Output affiliate link safely with rel="noopener noreferrer sponsored nofollow".
 *
 * @param string $url Target affiliate URL.
 * @param string $text Anchor text.
 * @param string $class CSS classes.
 * @param string $subtext Optional subtext.
 * @return string HTML button.
 */
function sr_get_affiliate_button( $url, $text, $class = 'sr-buy-btn', $subtext = '' ) {
	$clean_url = ! empty( $url ) ? esc_url( $url ) : '#';
	$clean_text = ! empty( $text ) ? esc_html( $text ) : __( 'Buy Now (Official Website)', 'supplement-review' );

	$html = sprintf(
		'<a href="%1$s" class="%2$s" target="_blank" rel="noopener noreferrer sponsored nofollow">',
		$clean_url,
		esc_attr( $class )
	);

	$html .= '<span class="sr-btn-main-text" style="display:inline-flex;align-items:center;gap:8px;">' . sr_get_svg( 'cart', array( 'width' => 18, 'height' => 18 ) ) . ' ' . $clean_text . '</span>';

	if ( ! empty( $subtext ) ) {
		$html .= '<span class="sr-buy-subtext">' . esc_html( $subtext ) . '</span>';
	}

	$html .= '</a>';

	return $html;
}

/**
 * Clean up WordPress head to remove unnecessary generator tags and emojis safely.
 */
function sr_cleanup_head() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'sr_cleanup_head' );

/**
 * Adds Medical Researcher & Credential fields to WordPress user profile.
 *
 * @param WP_User $user Current user object.
 */
function sr_user_profile_fields( $user ) {
	?>
	<h3><?php esc_html_e( 'Editorial & Supplement Review Credentials', 'supplement-review' ); ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="sr_user_role_title"><?php esc_html_e( 'Professional Title / Role', 'supplement-review' ); ?></label></th>
			<td>
				<input type="text" name="sr_user_role_title" id="sr_user_role_title" value="<?php echo esc_attr( get_the_author_meta( 'sr_user_role_title', $user->ID ) ); ?>" class="regular-text" />
				<p class="description"><?php esc_html_e( 'Example: Medical Researcher & Contributor, PharmD, Clinical Nutritionist', 'supplement-review' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="sr_user_verified"><?php esc_html_e( 'Verified Fact-Checker', 'supplement-review' ); ?></label></th>
			<td>
				<label>
					<input type="checkbox" name="sr_user_verified" id="sr_user_verified" value="1" <?php checked( get_the_author_meta( 'sr_user_verified', $user->ID ), 1 ); ?> />
					<?php esc_html_e( 'Show verified expert badge beside author name', 'supplement-review' ); ?>
				</label>
			</td>
		</tr>
	</table>
	<?php
}
add_action( 'show_user_profile', 'sr_user_profile_fields' );
add_action( 'edit_user_profile', 'sr_user_profile_fields' );

/**
 * Saves user profile credential fields.
 *
 * @param int $user_id User ID being saved.
 */
function sr_save_user_profile_fields( $user_id ) {
	if ( ! current_user_can( 'edit_user', $user_id ) ) {
		return false;
	}

	if ( isset( $_POST['sr_user_role_title'] ) ) {
		update_user_meta( $user_id, 'sr_user_role_title', sanitize_text_field( wp_unslash( $_POST['sr_user_role_title'] ) ) );
	}

	$verified = isset( $_POST['sr_user_verified'] ) ? 1 : 0;
	update_user_meta( $user_id, 'sr_user_verified', $verified );
}
add_action( 'personal_options_update', 'sr_save_user_profile_fields' );
add_action( 'edit_user_profile_update', 'sr_save_user_profile_fields' );
