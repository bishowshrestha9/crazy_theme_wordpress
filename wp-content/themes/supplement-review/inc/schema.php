<?php
/**
 * Structured Data (JSON-LD) and SEO Enhancements
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Checks if a third-party SEO plugin is active.
 *
 * @return bool True if Yoast, Rank Math, or AIOSEO is active.
 */
function sr_is_seo_plugin_active() {
	return (
		defined( 'WPSEO_VERSION' ) ||
		class_exists( 'RankMath' ) ||
		defined( 'AIOSEO_VERSION' )
	);
}

/**
 * Generate and output JSON-LD Structured Data in head.
 */
function sr_output_structured_data() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}

	global $post;
	$post_id = $post->ID;

	$graph = array();

	// 1. BreadcrumbList Schema
	$crumbs = array();
	$crumbs[] = array(
		'@type'    => 'ListItem',
		'position' => 1,
		'name'     => __( 'Home', 'supplement-review' ),
		'item'     => home_url( '/' ),
	);

	$categories = get_the_category( $post_id );
	if ( ! empty( $categories ) ) {
		$crumbs[] = array(
			'@type'    => 'ListItem',
			'position' => 2,
			'name'     => $categories[0]->name,
			'item'     => get_category_link( $categories[0]->term_id ),
		);
	}

	$crumbs[] = array(
		'@type'    => 'ListItem',
		'position' => count( $crumbs ) + 1,
		'name'     => get_the_title( $post_id ),
		'item'     => get_permalink( $post_id ),
	);

	$graph[] = array(
		'@type'           => 'BreadcrumbList',
		'@id'             => get_permalink( $post_id ) . '#breadcrumb',
		'itemListElement' => $crumbs,
	);

	// 2. Article / MedicalWebPage Schema
	$author_id   = $post->post_author;
	$author_name = get_the_author_meta( 'display_name', $author_id );
	$author_role = get_post_meta( $post_id, '_sr_author_role', true );
	if ( empty( $author_role ) ) {
		$author_role = get_the_author_meta( 'sr_user_role_title', $author_id );
	}
	$reviewed_by = get_post_meta( $post_id, '_sr_reviewed_by', true );

	$article_schema = array(
		'@type'            => 'MedicalWebPage',
		'@id'              => get_permalink( $post_id ) . '#article',
		'isPartOf'         => array( '@id' => home_url( '/#website' ) ),
		'headline'         => get_the_title( $post_id ),
		'datePublished'    => get_the_date( 'c', $post_id ),
		'dateModified'     => get_the_modified_date( 'c', $post_id ),
		'description'      => wp_strip_all_tags( get_the_excerpt( $post_id ) ),
		'author'           => array(
			'@type'    => 'Person',
			'name'     => $author_name,
			'jobTitle' => ! empty( $author_role ) ? $author_role : __( 'Medical Researcher', 'supplement-review' ),
		),
	);

	if ( ! empty( $reviewed_by ) ) {
		$article_schema['reviewedBy'] = array(
			'@type' => 'Person',
			'name'  => $reviewed_by,
		);
	}

	$graph[] = $article_schema;

	// 3. Product & Review Schema
	$product_name  = get_post_meta( $post_id, '_sr_product_name', true );
	if ( empty( $product_name ) ) {
		$product_name = get_the_title( $post_id );
	}

	$product_img_id = (int) get_post_meta( $post_id, '_sr_product_image_id', true );
	$product_img    = $product_img_id ? wp_get_attachment_image_url( $product_img_id, 'full' ) : get_the_post_thumbnail_url( $post_id, 'full' );
	if ( empty( $product_img ) ) {
		$product_img = get_template_directory_uri() . '/assets/images/product-sample.png';
	}

	$rating_val = (float) get_post_meta( $post_id, '_sr_product_rating', true );
	if ( $rating_val <= 0 ) {
		$rating_val = 4.9;
	}

	$price_val = get_post_meta( $post_id, '_sr_product_price', true );
	$aff_url   = get_post_meta( $post_id, '_sr_affiliate_url', true );

	$product_schema = array(
		'@type'       => 'Product',
		'@id'         => get_permalink( $post_id ) . '#product',
		'name'        => $product_name,
		'image'       => $product_img,
		'description' => wp_strip_all_tags( get_the_excerpt( $post_id ) ),
		'review'      => array(
			'@type'         => 'Review',
			'reviewRating'  => array(
				'@type'       => 'Rating',
				'ratingValue' => number_format( $rating_val, 1 ),
				'bestRating'  => '5',
				'worstRating' => '1',
			),
			'author'        => array(
				'@type' => 'Person',
				'name'  => $author_name,
			),
			'reviewBody'    => wp_strip_all_tags( get_the_excerpt( $post_id ) ),
		),
	);

	if ( ! empty( $price_val ) ) {
		$clean_num = (float) preg_replace( '/[^0-9.]/', '', $price_val );
		$product_schema['offers'] = array(
			'@type'         => 'Offer',
			'price'         => number_format( $clean_num, 2, '.', '' ),
			'priceCurrency' => 'USD',
			'availability'  => 'https://schema.org/InStock',
			'url'           => ! empty( $aff_url ) ? $aff_url : get_permalink( $post_id ),
		);
	}

	// Real Approved Aggregate Rating Schema - ONLY outputted if real approved reviews exist!
	$aggregate = sr_get_approved_reviews_aggregate( $post_id );
	if ( ! empty( $aggregate ) && $aggregate['reviewCount'] > 0 ) {
		$product_schema['aggregateRating'] = array(
			'@type'       => 'AggregateRating',
			'ratingValue' => number_format( $aggregate['ratingValue'], 1 ),
			'reviewCount' => absint( $aggregate['reviewCount'] ),
			'bestRating'  => '5',
			'worstRating' => '1',
		);
	}

	$graph[] = $product_schema;

	// 4. FAQ Schema if FAQ elements exist
	if ( preg_match_all( '/<summary[^>]*class=[\'"][^\'"]*sr-faq-question[^\'"]*[\'"][^>]*>(.*?)<\/summary>\s*<div[^>]*class=[\'"][^\'"]*sr-faq-answer[^\'"]*[\'"][^>]*>(.*?)<\/div>/is', $post->post_content, $faq_matches, PREG_SET_ORDER ) ) {
		$faq_items = array();
		foreach ( $faq_matches as $faq_m ) {
			$q = trim( wp_strip_all_tags( $faq_m[1] ) );
			$a = trim( wp_strip_all_tags( $faq_m[2] ) );
			if ( ! empty( $q ) && ! empty( $a ) ) {
				$faq_items[] = array(
					'@type'          => 'Question',
					'name'           => $q,
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => $a,
					),
				);
			}
		}

		if ( ! empty( $faq_items ) ) {
			$graph[] = array(
				'@type'      => 'FAQPage',
				'mainEntity' => $faq_items,
			);
		}
	}

	// Output JSON-LD
	$schema_data = array(
		'@context' => 'https://schema.org',
		'@graph'   => $graph,
	);

	echo "\n<!-- Supplement Review JSON-LD Schema -->\n";
	echo '<script type="application/ld+json">' . wp_json_encode( $schema_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . "</script>\n";
}
add_action( 'wp_head', 'sr_output_structured_data', 20 );

/**
 * Fallback OpenGraph and Canonical meta tags if no SEO plugin is active.
 */
function sr_fallback_meta_tags() {
	if ( sr_is_seo_plugin_active() ) {
		return;
	}

	if ( is_singular() ) {
		$title = get_the_title();
		$url   = get_permalink();
		$desc  = wp_strip_all_tags( get_the_excerpt() );
		$img   = get_the_post_thumbnail_url( get_the_ID(), 'large' );
		?>
		<link rel="canonical" href="<?php echo esc_url( $url ); ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:title" content="<?php echo esc_attr( $title ); ?>" />
		<meta property="og:description" content="<?php echo esc_attr( $desc ); ?>" />
		<meta property="og:url" content="<?php echo esc_url( $url ); ?>" />
		<meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
		<?php if ( $img ) : ?>
			<meta property="og:image" content="<?php echo esc_url( $img ); ?>" />
		<?php endif; ?>
		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:title" content="<?php echo esc_attr( $title ); ?>" />
		<meta name="twitter:description" content="<?php echo esc_attr( $desc ); ?>" />
		<?php
	}
}
add_action( 'wp_head', 'sr_fallback_meta_tags', 5 );
