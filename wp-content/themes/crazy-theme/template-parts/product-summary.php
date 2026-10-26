<?php
/**
 * Product Summary Sticky Sidebar Card Component
 *
 * Scoped, accessible, dynamic template part.
 * Matches design screenshot with pixel accuracy.
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_the_ID();

// Exit early if product sidebar card is disabled for this post
if ( function_exists( 'crazytheme_has_product_sidebar' ) && ! crazytheme_has_product_sidebar( $post_id ) ) {
	return;
}

// 1. Product Name (ACF: product_name)
$product_name = function_exists( 'get_field' ) ? get_field( 'product_name', $post_id ) : '';
if ( empty( $product_name ) ) {
	$product_name = get_post_meta( $post_id, 'product_name', true );
}
if ( empty( $product_name ) ) {
	$product_name = get_post_meta( $post_id, '_sidebar_product_name', true );
}
if ( empty( $product_name ) ) {
	$product_name = get_post_meta( $post_id, '_sr_product_name', true );
}
if ( empty( $product_name ) ) {
	$product_name = get_the_title( $post_id );
}
if ( empty( $product_name ) ) {
	$product_name = __( 'Supplement Product', 'crazytheme' );
}

// 2. Category Tags / Meta (ACF: product_category, product_tags, or WP category)
$product_tags = '';
$acf_cat      = function_exists( 'get_field' ) ? get_field( 'product_category', $post_id ) : '';
if ( empty( $acf_cat ) ) {
	$acf_cat = get_post_meta( $post_id, 'product_category', true );
}
if ( is_array( $acf_cat ) ) {
	$cat_names = array();
	foreach ( $acf_cat as $c ) {
		if ( is_object( $c ) && isset( $c->name ) ) {
			$cat_names[] = $c->name;
		} elseif ( is_string( $c ) ) {
			$cat_names[] = $c;
		}
	}
	$product_tags = implode( ' • ', $cat_names );
} elseif ( is_object( $acf_cat ) && isset( $acf_cat->name ) ) {
	$product_tags = $acf_cat->name;
} elseif ( is_string( $acf_cat ) && ! empty( $acf_cat ) ) {
	$product_tags = $acf_cat;
}

if ( empty( $product_tags ) && function_exists( 'get_field' ) ) {
	$product_tags = get_field( 'product_tags', $post_id );
}
if ( empty( $product_tags ) ) {
	$product_tags = get_post_meta( $post_id, '_sr_product_tags', true );
}
if ( empty( $product_tags ) ) {
	$wp_cats = get_the_category( $post_id );
	if ( ! empty( $wp_cats ) && ! is_wp_error( $wp_cats ) ) {
		$names        = wp_list_pluck( $wp_cats, 'name' );
		$product_tags = implode( ' • ', $names );
	}
}
if ( empty( $product_tags ) ) {
	$product_tags = __( 'Dietary Supplement', 'crazytheme' );
}

// 3. Product Image (ACF: product_image)
$image_url = '';
$acf_img   = function_exists( 'get_field' ) ? get_field( 'product_image', $post_id ) : '';
if ( empty( $acf_img ) ) {
	$acf_img = get_post_meta( $post_id, 'product_image', true );
}
if ( is_array( $acf_img ) ) {
	if ( ! empty( $acf_img['url'] ) ) {
		$image_url = $acf_img['url'];
	} elseif ( ! empty( $acf_img['ID'] ) ) {
		$image_url = wp_get_attachment_image_url( (int) $acf_img['ID'], 'large' );
	} elseif ( ! empty( $acf_img['id'] ) ) {
		$image_url = wp_get_attachment_image_url( (int) $acf_img['id'], 'large' );
	}
} elseif ( is_numeric( $acf_img ) && (int) $acf_img > 0 ) {
	$image_url = wp_get_attachment_image_url( (int) $acf_img, 'large' );
} elseif ( is_string( $acf_img ) && ! empty( $acf_img ) ) {
	if ( ctype_digit( $acf_img ) ) {
		$image_url = wp_get_attachment_image_url( (int) $acf_img, 'large' );
	} else {
		$image_url = $acf_img;
	}
}
if ( empty( $image_url ) ) {
	$image_id = (int) get_post_meta( $post_id, '_thumbnail_id', true );
	if ( $image_id ) {
		$image_url = wp_get_attachment_image_url( $image_id, 'large' );
	}
}
if ( empty( $image_url ) ) {
	$side_img = get_post_meta( $post_id, '_sidebar_product_image', true );
	if ( ! empty( $side_img ) ) {
		$image_url = $side_img;
	}
}
if ( empty( $image_url ) ) {
	$thumb = get_the_post_thumbnail_url( $post_id, 'large' );
	if ( ! empty( $thumb ) ) {
		$image_url = $thumb;
	}
}
if ( empty( $image_url ) ) {
	$image_url = get_template_directory_uri() . '/assets/images/product-sample.png';
}

// 4. Overall Star Rating (ACF: rating)
$raw_rating = function_exists( 'get_field' ) ? get_field( 'rating', $post_id ) : '';
if ( empty( $raw_rating ) ) {
	$raw_rating = get_post_meta( $post_id, 'rating', true );
}
if ( empty( $raw_rating ) && function_exists( 'get_field' ) ) {
	$raw_rating = get_field( 'product_rating', $post_id );
}
if ( empty( $raw_rating ) ) {
	$raw_rating = get_post_meta( $post_id, '_sidebar_product_rating', true );
}
if ( empty( $raw_rating ) ) {
	$raw_rating = get_post_meta( $post_id, '_sr_product_rating', true );
}
$rating = (float) preg_replace( '/[^0-9.]/', '', (string) $raw_rating );
if ( $rating > 5.0 && $rating <= 10.0 ) {
	$rating = round( $rating / 2, 1 );
}
if ( $rating <= 0 ) {
	$rating = 4.9;
}
$rating = max( 1.0, min( 5.0, $rating ) );

// 5. Pricing & Discount (ACF: price)
$raw_price = function_exists( 'get_field' ) ? get_field( 'price', $post_id ) : '';
if ( empty( $raw_price ) ) {
	$raw_price = get_post_meta( $post_id, 'price', true );
}
if ( empty( $raw_price ) && function_exists( 'get_field' ) ) {
	$raw_price = get_field( 'product_price', $post_id );
}
if ( empty( $raw_price ) ) {
	$raw_price = get_post_meta( $post_id, '_sidebar_product_disc_price', true );
}
if ( empty( $raw_price ) ) {
	$raw_price = get_post_meta( $post_id, '_sr_product_price', true );
}
$num_price = (float) preg_replace( '/[^0-9.]/', '', (string) $raw_price );
$price     = ( $num_price > 0 ) ? number_format( $num_price, ( floor( $num_price ) == $num_price ? 0 : 2 ) ) : '49';

// Original / Old Price
$raw_old_price = function_exists( 'get_field' ) ? get_field( 'original_price', $post_id ) : '';
if ( empty( $raw_old_price ) ) {
	$raw_old_price = get_post_meta( $post_id, 'original_price', true );
}
if ( empty( $raw_old_price ) && function_exists( 'get_field' ) ) {
	$raw_old_price = get_field( 'product_old_price', $post_id );
}
if ( empty( $raw_old_price ) ) {
	$raw_old_price = get_post_meta( $post_id, '_sidebar_product_orig_price', true );
}
$num_old_price = (float) preg_replace( '/[^0-9.]/', '', (string) $raw_old_price );
if ( $num_old_price <= 0 && $num_price > 0 ) {
	$num_old_price = round( $num_price * 1.5, 0 );
}
$old_price = ( $num_old_price > 0 ) ? number_format( $num_old_price, ( floor( $num_old_price ) == $num_old_price ? 0 : 2 ) ) : '';

// Discount Percentage
$discount_pct = 0;
if ( $num_old_price > $num_price && $num_old_price > 0 ) {
	$discount_pct = (int) round( ( ( $num_old_price - $num_price ) / $num_old_price ) * 100 );
}
if ( $discount_pct <= 0 ) {
	$discount_pct = 25;
}

// 6. Buy Link & CTA (ACF: buy_link)
$affiliate_url = function_exists( 'get_field' ) ? get_field( 'buy_link', $post_id ) : '';
if ( empty( $affiliate_url ) ) {
	$affiliate_url = get_post_meta( $post_id, 'buy_link', true );
}
if ( empty( $affiliate_url ) && function_exists( 'get_field' ) ) {
	$affiliate_url = get_field( 'affiliate_url', $post_id );
}
if ( empty( $affiliate_url ) ) {
	$affiliate_url = get_post_meta( $post_id, '_sidebar_product_buy_url', true );
}
if ( empty( $affiliate_url ) ) {
	$affiliate_url = get_post_meta( $post_id, '_sr_affiliate_url', true );
}
$affiliate_url = trim( (string) $affiliate_url );
if ( ! empty( $affiliate_url ) ) {
	// If URL doesn't have a protocol (e.g. www.domain.com/path), prepend https://
	if ( ! preg_match( '#^https?://#i', $affiliate_url ) && strpos( $affiliate_url, '#' ) !== 0 ) {
		$affiliate_url = 'https://' . ltrim( $affiliate_url, '/' );
	}
} else {
	$affiliate_url = '#';
}

$btn_text = function_exists( 'get_field' ) ? get_field( 'affiliate_btn_text', $post_id ) : '';
if ( empty( $btn_text ) ) {
	$btn_text = get_post_meta( $post_id, '_sr_affiliate_btn_text', true );
}
if ( empty( $btn_text ) ) {
	$btn_text = __( 'Buy Now', 'crazytheme' );
}

// 7. Verdict (ACF: verdict) - max 40 chars
$verdict_text = function_exists( 'get_field' ) ? get_field( 'verdict', $post_id ) : '';
if ( empty( $verdict_text ) ) {
	$verdict_text = get_post_meta( $post_id, 'verdict', true );
}
if ( empty( $verdict_text ) && function_exists( 'get_field' ) ) {
	$verdict_text = get_field( 'verdict_text', $post_id );
}
if ( empty( $verdict_text ) ) {
	$verdict_text = get_post_meta( $post_id, '_sidebar_product_desc', true );
}
if ( empty( $verdict_text ) ) {
	$verdict_text = get_post_meta( $post_id, '_sr_verdict_text', true );
}
if ( empty( $verdict_text ) ) {
	$verdict_text = __( 'Highly Recommended formula for daily consistency', 'crazytheme' );
}

// Strip leading "is a " / "is an " if present
$verdict_text = trim( (string) $verdict_text );
if ( preg_match( '/^is\s+(an?|the)?\s*/i', $verdict_text ) ) {
	$verdict_text = preg_replace( '/^is\s+(an?|the)?\s*/i', '', $verdict_text );
	$verdict_text = ucfirst( $verdict_text );
}

// Truncate to maximum 40 characters
if ( mb_strlen( $verdict_text ) > 40 ) {
	$verdict_text = mb_substr( $verdict_text, 0, 37 ) . '...';
}

// 8. Editorial Scores / Dynamic Bars (ACF: effectiveness, results)
$score_eff = '4.9';
$fill_eff  = 96;
$eff_val   = function_exists( 'get_field' ) ? get_field( 'effectiveness', $post_id ) : '';
if ( empty( $eff_val ) ) {
	$eff_val = get_post_meta( $post_id, 'effectiveness', true );
}
if ( ! empty( $eff_val ) ) {
	if ( is_numeric( $eff_val ) ) {
		$score_eff = number_format( (float) $eff_val, 1 );
		$fill_eff  = min( 100, max( 10, (int) round( ( (float) $eff_val / 5 ) * 100 ) ) );
	} else {
		$score_eff = '4.9';
		$fill_eff  = 96;
	}
}

$score_ing = '4.9';
$fill_ing  = 94;
$results_val = function_exists( 'get_field' ) ? get_field( 'results', $post_id ) : '';
if ( empty( $results_val ) ) {
	$results_val = get_post_meta( $post_id, 'results', true );
}
if ( ! empty( $results_val ) && is_numeric( $results_val ) ) {
	$score_ing = number_format( (float) $results_val, 1 );
	$fill_ing  = min( 100, max( 10, (int) round( ( (float) $results_val / 5 ) * 100 ) ) );
}

$editorial_scores = array(
	array(
		'label' => __( 'Effectiveness', 'crazytheme' ),
		'score' => $score_eff,
		'fill'  => $fill_eff,
	),
	array(
		'label' => __( 'Ingredients', 'crazytheme' ),
		'score' => $score_ing,
		'fill'  => $fill_ing,
	),
	array(
		'label' => __( 'Customer Reviews', 'crazytheme' ),
		'score' => '4.8',
		'fill'  => 88,
	),
);

// 9. Footer Note (ACF: return_policy, certification, manufacturer)
$editorial_note = '';
$ret_policy     = function_exists( 'get_field' ) ? get_field( 'return_policy', $post_id ) : '';
if ( empty( $ret_policy ) ) {
	$ret_policy = get_post_meta( $post_id, 'return_policy', true );
}
$cert = function_exists( 'get_field' ) ? get_field( 'certification', $post_id ) : '';
if ( empty( $cert ) ) {
	$cert = get_post_meta( $post_id, 'certification', true );
}

if ( ! empty( $ret_policy ) && ! empty( $cert ) ) {
	$editorial_note = esc_html( $cert . ' • ' . $ret_policy );
} elseif ( ! empty( $ret_policy ) ) {
	$editorial_note = esc_html( $ret_policy );
} else {
	$editorial_note = __( 'Editorial rating based on our review.', 'crazytheme' );
}

// Helper: Render Star Icons
$stars_html = '';
for ( $i = 1; $i <= 5; $i++ ) {
	if ( $rating >= $i ) {
		// Full Star
		$stars_html .= '<svg class="ps-star-icon ps-star--full" width="18" height="18" viewBox="0 0 24 24" fill="#FFC83D" aria-hidden="true" focusable="false"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>';
	} elseif ( $rating >= ( $i - 0.5 ) ) {
		// Half Star
		$stars_html .= '<svg class="ps-star-icon ps-star--half" width="18" height="18" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path fill="#FFC83D" d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77V2Z"/><path fill="#E5E5E5" d="M12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2V17.77Z"/></svg>';
	} else {
		// Empty Star
		$stars_html .= '<svg class="ps-star-icon ps-star--empty" width="18" height="18" viewBox="0 0 24 24" fill="#E5E5E5" aria-hidden="true" focusable="false"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>';
	}
}
?>
<aside class="ps-card ps-card--sticky" aria-label="<?php esc_attr_e( 'Product summary', 'supplement-review' ); ?>">
	<!-- 1. Header Row -->
	<div class="ps-header-row">
		<span class="ps-header-pill">
			<?php esc_html_e( 'Product Summary', 'supplement-review' ); ?>
		</span>
		<button type="button" class="ps-share-btn" aria-label="<?php esc_attr_e( 'Share this review', 'supplement-review' ); ?>" title="<?php esc_attr_e( 'Share this review', 'supplement-review' ); ?>">
			<svg class="ps-share-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
				<circle cx="18" cy="5" r="3"></circle>
				<circle cx="6" cy="12" r="3"></circle>
				<circle cx="18" cy="19" r="3"></circle>
				<line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
				<line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
			</svg>
		</button>
	</div>

	<!-- 2. Image Box -->
	<div class="ps-image-box">
		<img
			src="<?php echo esc_url( $image_url ); ?>"
			alt="<?php echo esc_attr( $product_name ); ?>"
			class="ps-product-img"
			width="328"
			height="180"
			loading="lazy"
		/>
	</div>

	<!-- 3. Title Row -->
	<div class="ps-title-row">
		<h2 class="ps-title"><?php echo esc_html( $product_name ); ?></h2>
		<div class="ps-rating-wrap">
			<div class="ps-stars" aria-label="<?php printf( esc_attr__( 'Rating: %1$s out of 5 stars', 'supplement-review' ), esc_attr( number_format( $rating, 1 ) ) ); ?>">
				<?php echo $stars_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<span class="ps-rating-val"><?php echo esc_html( number_format( $rating, 1 ) ); ?>/5</span>
		</div>
	</div>

	<!-- 4. Meta Line -->
	<div class="ps-meta"><?php echo esc_html( $product_tags ); ?></div>

	<!-- 5. Divider -->
	<hr class="ps-divider" aria-hidden="true" />

	<!-- 6. Editorial Score Heading -->
	<div class="ps-editorial-title"><?php esc_html_e( 'Editorial Score', 'supplement-review' ); ?></div>

	<!-- 7. Score Rows -->
	<div class="ps-score-rows">
		<?php foreach ( $editorial_scores as $score_row ) :
			$row_label = isset( $score_row['label'] ) ? sanitize_text_field( $score_row['label'] ) : '';
			$row_score = isset( $score_row['score'] ) ? (float) $score_row['score'] : 4.9;
			if ( isset( $score_row['fill'] ) && is_numeric( $score_row['fill'] ) ) {
				$row_pct = max( 0, min( 100, (int) $score_row['fill'] ) );
			} else {
				$row_pct = max( 0, min( 100, (int) round( ( $row_score / 5.0 ) * 100 ) ) );
			}
			?>
			<div class="ps-score-row">
				<span class="ps-score-label"><?php echo esc_html( $row_label ); ?></span>
				<div class="ps-score-track" role="progressbar" aria-valuenow="<?php echo esc_attr( $row_pct ); ?>" aria-valuemin="0" aria-valuemax="100" aria-label="<?php printf( esc_attr__( '%1$s score: %2$s out of 5', 'supplement-review' ), esc_attr( $row_label ), esc_attr( number_format( $row_score, 1 ) ) ); ?>">
					<div class="ps-score-fill" style="width: <?php echo esc_attr( $row_pct ); ?>%;"></div>
				</div>
				<span class="ps-score-val"><?php echo esc_html( number_format( $row_score, 1 ) ); ?>/5</span>
			</div>
		<?php endforeach; ?>
	</div>

	<!-- 8. Divider -->
	<hr class="ps-divider" aria-hidden="true" />

	<!-- 9. Price Row -->
	<div class="ps-price-row">
		<span class="ps-price-current">$<?php echo esc_html( $price ); ?></span>
		<?php if ( ! empty( $old_price ) ) : ?>
			<span class="ps-price-old">$<?php echo esc_html( $old_price ); ?></span>
		<?php endif; ?>
		<?php if ( $discount_pct > 0 ) : ?>
			<span class="ps-discount"><?php echo esc_html( $discount_pct ); ?>% Off</span>
		<?php endif; ?>
	</div>

	<!-- 10. Buy Now Button -->
	<a href="<?php echo esc_url( $affiliate_url ); ?>" class="ps-buy-btn" target="_blank" rel="sponsored noopener noreferrer">
		<span class="ps-buy-line1">
			<svg class="ps-buy-cart-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
				<circle cx="9" cy="21" r="1"></circle>
				<circle cx="20" cy="21" r="1"></circle>
				<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
			</svg>
			<?php echo esc_html( $btn_text ); ?>
		</span>
		<span class="ps-buy-line2">(<?php esc_html_e( 'Official Website', 'supplement-review' ); ?>)</span>
	</a>

	<!-- 11. Verdict Box -->
	<div class="ps-verdict-box">
		<svg class="ps-verdict-icon" width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
			<path d="M21.375 13.125C20.625 16.875 17.7977 20.4054 13.829 21.1949C9.8603 21.9843 5.83298 20.1383 3.84043 16.6164C1.84787 13.0947 2.33978 8.69181 5.06047 5.69658C7.78115 2.70133 12.375 1.87501 16.125 3.37501" stroke="#6EA68F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
			<path d="M8.625 11.625L12.375 15.375L21.375 5.625" stroke="#6EA68F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
		<p class="ps-verdict-text">
			<strong class="ps-verdict-label"><?php esc_html_e( 'Verdict:', 'supplement-review' ); ?></strong> <?php echo esc_html( $verdict_text ); ?>
		</p>
	</div>

	<!-- 12. Footer Note -->
	<div class="ps-footer-note"><?php echo esc_html( $editorial_note ); ?></div>
</aside>
