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

// 1. Product Name
$product_name = function_exists( 'get_field' ) ? get_field( 'product_name', $post_id ) : '';
if ( empty( $product_name ) ) {
	$product_name = get_post_meta( $post_id, '_sr_product_name', true );
}
if ( empty( $product_name ) ) {
	$product_name = get_the_title( $post_id );
}
if ( empty( $product_name ) ) {
	$product_name = __( 'Gluco Reset', 'supplement-review' );
}

// 2. Category Tags / Meta
$product_tags = function_exists( 'get_field' ) ? get_field( 'product_tags', $post_id ) : '';
if ( empty( $product_tags ) ) {
	$product_tags = get_post_meta( $post_id, '_sr_product_tags', true );
}
if ( empty( $product_tags ) ) {
	$product_tags = __( 'Blood Sugar • Liquid Drops', 'supplement-review' );
}

// 3. Product Image
$image_url = '';
if ( function_exists( 'get_field' ) ) {
	$acf_img = get_field( 'product_image', $post_id );
	if ( is_array( $acf_img ) && ! empty( $acf_img['url'] ) ) {
		$image_url = $acf_img['url'];
	} elseif ( is_numeric( $acf_img ) ) {
		$image_url = wp_get_attachment_image_url( (int) $acf_img, 'medium' );
	} elseif ( is_string( $acf_img ) ) {
		$image_url = $acf_img;
	}
}
if ( empty( $image_url ) ) {
	$image_id = (int) get_post_meta( $post_id, '_sr_product_image_id', true );
	if ( $image_id ) {
		$image_url = wp_get_attachment_image_url( $image_id, 'medium' );
	}
}
if ( empty( $image_url ) ) {
	$raw_img_url = get_post_meta( $post_id, '_sr_product_image_url', true );
	if ( ! empty( $raw_img_url ) ) {
		$image_url = $raw_img_url;
	}
}
if ( empty( $image_url ) ) {
	$thumb = get_the_post_thumbnail_url( $post_id, 'medium' );
	if ( ! empty( $thumb ) ) {
		$image_url = $thumb;
	}
}
if ( empty( $image_url ) ) {
	$image_url = get_template_directory_uri() . '/assets/images/product-sample.png';
}

// 4. Overall Star Rating
$rating = 0.0;
if ( function_exists( 'get_field' ) ) {
	$rating = (float) get_field( 'product_rating', $post_id );
}
if ( $rating <= 0 ) {
	$rating = (float) get_post_meta( $post_id, '_sr_product_rating', true );
}
if ( $rating <= 0 ) {
	$rating = 4.9;
}
$rating = max( 0.0, min( 5.0, $rating ) );

// 5. Editorial Scores
$editorial_scores = array();
if ( function_exists( 'get_field' ) ) {
	$acf_scores = get_field( 'editorial_scores', $post_id );
	if ( is_array( $acf_scores ) && ! empty( $acf_scores ) ) {
		$editorial_scores = $acf_scores;
	}
}
if ( empty( $editorial_scores ) ) {
	$scores_json = get_post_meta( $post_id, '_sr_editorial_scores', true );
	if ( ! empty( $scores_json ) ) {
		$decoded = json_decode( $scores_json, true );
		if ( is_array( $decoded ) ) {
			$editorial_scores = $decoded;
		}
	}
}
if ( empty( $editorial_scores ) ) {
	$editorial_scores = array(
		array(
			'label' => __( 'Effectiveness', 'supplement-review' ),
			'score' => '4.9',
			'fill'  => 94,
		),
		array(
			'label' => __( 'Ingredients', 'supplement-review' ),
			'score' => '4.9',
			'fill'  => 94,
		),
		array(
			'label' => __( 'Customer Reviews', 'supplement-review' ),
			'score' => '4.8',
			'fill'  => 87,
		),
	);
}

// 6. Pricing & Discount
$price = function_exists( 'get_field' ) ? get_field( 'product_price', $post_id ) : '';
if ( empty( $price ) ) {
	$price = get_post_meta( $post_id, '_sr_product_price', true );
}
if ( empty( $price ) ) {
	$price = '12.99';
}

$old_price = function_exists( 'get_field' ) ? get_field( 'product_old_price', $post_id ) : '';
if ( empty( $old_price ) ) {
	$old_price = get_post_meta( $post_id, '_sr_product_old_price', true );
}
if ( empty( $old_price ) ) {
	$old_price = '17.99';
}

$discount_pct = function_exists( 'get_field' ) ? (int) get_field( 'discount_percentage', $post_id ) : 0;
if ( $discount_pct <= 0 ) {
	$discount_pct = (int) get_post_meta( $post_id, '_sr_discount_percentage', true );
}
if ( $discount_pct <= 0 && ! empty( $old_price ) && ! empty( $price ) ) {
	$num_old = (float) preg_replace( '/[^0-9.]/', '', $old_price );
	$num_cur = (float) preg_replace( '/[^0-9.]/', '', $price );
	if ( $num_old > $num_cur && $num_old > 0 ) {
		$discount_pct = (int) round( ( ( $num_old - $num_cur ) / $num_old ) * 100 );
	}
}
if ( $discount_pct <= 0 ) {
	$discount_pct = 25; // Default matching screenshot
}

// 7. Affiliate Link & CTA
$affiliate_url = function_exists( 'get_field' ) ? get_field( 'affiliate_url', $post_id ) : '';
if ( empty( $affiliate_url ) ) {
	$affiliate_url = get_post_meta( $post_id, '_sr_affiliate_url', true );
}
if ( empty( $affiliate_url ) ) {
	$affiliate_url = '#';
}

$btn_text = function_exists( 'get_field' ) ? get_field( 'affiliate_btn_text', $post_id ) : '';
if ( empty( $btn_text ) ) {
	$btn_text = get_post_meta( $post_id, '_sr_affiliate_btn_text', true );
}
if ( empty( $btn_text ) ) {
	$btn_text = __( 'Buy Now', 'supplement-review' );
}

// 8. Verdict Box Text
$verdict_text = function_exists( 'get_field' ) ? get_field( 'verdict_text', $post_id ) : '';
if ( empty( $verdict_text ) ) {
	$verdict_text = get_post_meta( $post_id, '_sr_verdict_text', true );
}
if ( empty( $verdict_text ) ) {
	$verdict_text = __( 'Legit, convenient option for adults seeking daily glucose support', 'supplement-review' );
}

// 9. Editorial Note
$editorial_note = function_exists( 'get_field' ) ? get_field( 'editorial_note', $post_id ) : '';
if ( empty( $editorial_note ) ) {
	$editorial_note = get_post_meta( $post_id, '_sr_editorial_note', true );
}
if ( empty( $editorial_note ) ) {
	$editorial_note = __( 'Editorial rating based on our review.', 'supplement-review' );
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
			<strong class="ps-verdict-label"><?php esc_html_e( 'Verdict:', 'supplement-review' ); ?></strong><?php echo esc_html( $verdict_text ); ?>
		</p>
	</div>

	<!-- 12. Footer Note -->
	<div class="ps-footer-note"><?php echo esc_html( $editorial_note ); ?></div>
</aside>
