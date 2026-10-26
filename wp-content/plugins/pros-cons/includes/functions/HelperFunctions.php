<?php
/**
 * ProsCons Helper Functions.
 *
 * General helper functions avaiable on both the front-end and backend.
 *
 * @package ProsCons\Functions
 *
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
function affx_clean_vars( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'affx_clean_vars', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}

/**
 * Merge user defined arguments into defaults array.
 * Similar to wp_parse_args() just a bit extended to work with multidimensional arrays.
 *
 * @since 1.0.0
 *
 * @param array $args      (Required) Value to merge with $defaults.
 * @param array $defaults  Array that serves as the defaults. Default value: ''
 *
 * @return array
 */
function affx_wp_parse_args( &$args, $defaults = '' ) {
	$args     = (array) $args;
	$defaults = (array) $defaults;
	$result   = $defaults;

	foreach ( $args as $key => &$value ) {
		if ( is_array( $value ) && ! empty( $value ) && isset( $result[ $key ] ) ) {
			$result[ $key ] = affx_wp_parse_args( $value, $result[ $key ] );
		} else {
			$result[ $key ] = $value;
		}
	}
	return $result;
}

/**
 * ProsCons get block settings.
 *
 * @param bool $encode Whether to JSON encode the result.
 * @return array
 */
function affx_get_block_settings( $encode = false ) {

	$settings = get_option( 'prosandcons_block_settings' ) ? json_decode( get_option( 'prosandcons_block_settings' ), true ) : array();

	$block_defaults = apply_filters(
		'prosandcons_block_settings_defaults',
		array(
			'buttons'                  => true,
			'prosAndCons'              => true,
			'cta'                      => true,
			'notice'                   => true,
			'verdict'                  => true,
			'singleProduct'            => true,
			'specifications'           => true,
			'versusLine'               => true,
			'singleProductProsAndCons' => true,
			'productImageButton'       => true,
			'singleCoupon'             => true,
			'couponGrid'               => true,
			'productTabs'              => true,
			'couponListing'            => true,
			'topProducts'              => true,
			'versus'                   => true,
			'productTable'             => true,
			'productComparison'        => true,
			'ratingBox'                => true,
			'dynamicListing'           => true,
		)
	);

	$settings = affx_wp_parse_args( $settings, $block_defaults );

	return $encode ? wp_json_encode( $settings ) : $settings;
}

/**
 * ProsCons get customization settings.
 *
 * @param bool $encode        Whether to JSON encode the result.
 * @param bool $parse_default Whether to parse defaults.
 * @return array
 */
function affx_get_customization_settings( $encode = false, $parse_default = true ) {

	$settings = get_option( 'prosandcons_customization_settings' ) ? json_decode( get_option( 'prosandcons_customization_settings' ), true ) : array();

	$customization_defaults = apply_filters(
		'prosandcons_customization_settings_defaults',
		array(
			'typography'               => array(
				'family'          => 'Default',
				'variation'       => 'n4',

				'size'            => array(
					'desktop' => '18px',
					'mobile'  => '18px',
					'tablet'  => '18px',
				),
				'line-height'     => array(
					'desktop' => '1.65',
					'mobile'  => '1.65',
					'tablet'  => '1.65',
				),
				'letter-spacing'  => array(
					'desktop' => '0em',
					'mobile'  => '0em',
					'tablet'  => '0em',
				),
				'text-transform'  => 'none',
				'text-decoration' => 'none',
			),
			'fontColor'                => '#292929',
			'btnColor'                 => '#00B0B0',
			'btnHoverColor'            => '#00454A',
			'editorWidth'              => 'inherit',
			'editorCustomWidth'        => '1170',
			'editorCustomSidebarWidth' => '330',
			'editorSidebarWidth'       => 'inherit',
			'templateLibrary'          => true,
		)
	);

	$settings = $parse_default ? affx_wp_parse_args( $settings, $customization_defaults ) : $settings;

	return $encode ? wp_json_encode( $settings ) : $settings;
}

/**
 * Has block function which searches as well in reusable blocks.
 *
 * @param mixed $block_name Full Block type to look for.
 * @return bool
 */
function affx_has_block( $block_name ) {

	if ( has_block( $block_name ) ) {
		return true;
	}

	if ( has_block( 'core/block' ) ) {
		$content = get_post_field( 'post_content' );
		$blocks  = parse_blocks( $content );
		return affx_search_reusable_blocks_within_innerblocks( $blocks, $block_name );
	}

	return false;
}

/**
 * Search for the selected block within inner blocks.
 *
 * The helper function for affx_has_block() function.
 *
 * @param array  $blocks Blocks to loop through.
 * @param string $block_name Full Block type to look for.
 * @return bool
 */
function affx_search_reusable_blocks_within_innerblocks( $blocks, $block_name ) {
	foreach ( $blocks as $block ) {
		if ( isset( $block['innerBlocks'] ) && ! empty( $block['innerBlocks'] ) ) {
			affx_search_reusable_blocks_within_innerblocks( $block['innerBlocks'], $block_name );
		} elseif ( 'core/block' === $block['blockName'] && ! empty( $block['attrs']['ref'] ) && has_block( $block_name, $block['attrs']['ref'] ) ) {
			return true;
		}
	}

	return false;
}

/**
 * ProsCons get disabled blocks.
 *
 * @return array
 */
function affx_get_disabled_blocks() {

	$blocks = array(
		'buttons'                  => 'prosandcons/buttons',
		'prosAndCons'              => 'prosandcons/pros-and-cons',
		'cta'                      => 'prosandcons/cta',
		'notice'                   => 'prosandcons/notice',
		'verdict'                  => 'prosandcons/verdict',
		'singleProduct'            => 'prosandcons/single-product',
		'specifications'           => 'prosandcons/specifications',
		'versusLine'               => 'prosandcons/versus-line',
		'singleProductProsAndCons' => 'prosandcons/single-product-pros-and-cons',
		'productImageButton'       => 'prosandcons/product-image-button',
		'singleCoupon'             => 'prosandcons/single-coupon',
		'couponGrid'               => 'prosandcons/coupon-grid',
		'productTabs'              => 'prosandcons/product-tabs',
		'couponListing'            => 'prosandcons/coupon-listing',
		'topProducts'              => 'prosandcons/top-products',
		'versus'                   => 'prosandcons/versus',
		'productTable'             => 'prosandcons/product-table',
		'productComparison'        => 'prosandcons/product-comparison',
		'ratingBox'                => 'prosandcons/rating-box',
		'dynamicListing'           => 'prosandcons/dynamic-listing',
	);

	$pro_blocks = array( 'singleProductProsAndCons', 'productImageButton', 'singleCoupon', 'couponGrid', 'productTabs', 'couponListing', 'topProducts', 'versus', 'ratingBox', 'dynamicListing' );

	$block_settings = affx_get_block_settings();

	$disabled_blocks = array();

	foreach ( $block_settings as $key => $value ) {
		if ( ! $value ) {
			if ( isset( $blocks[ $key ] ) && ! affx_has_block( $blocks[ $key ] ) ) {
				$disabled_blocks[] = $blocks[ $key ];
			}
		}
	}

	return $disabled_blocks;
}

/**
 * Extract content from a block element.
 *
 * @param $element
 * @return string
 */
if ( ! function_exists( 'affx_extract_child_items' ) ) {
	function affx_extract_child_items( $element ) {
		$content = '';

		if ( is_array( $element ) ) {
			if ( isset( $element['props']['children'] ) && is_array( $element['props']['children'] ) ) {
				foreach ( $element['props']['children'] as $child ) {
					$content .= affx_extract_child_items( $child );
				}
			}
		} elseif ( is_string( $element ) ) {
			$content .= $element;
		}

		return $content;
	}
}

/**
 * Check if the current page is Elementor editor.
 *
 * @return bool
 */
if ( ! function_exists( 'affx_is_elementor_editor' ) ) {
	function affx_is_elementor_editor() {
		return isset( $_GET['action'] ) && $_GET['action'] === 'elementor'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}
}

/**
 * Parse Amazon shortcode if present.
 *
 * @param mixed $data
 * @return mixed
 */
if ( ! function_exists( 'affx_maybe_parse_amazon_shortcode' ) ) {
	function affx_maybe_parse_amazon_shortcode( $data ) {
		if ( is_string( $data ) && has_shortcode( $data, 'prosandcons-product' ) ) {
			$parsed_data = do_shortcode( $data );

			if ( json_decode( $parsed_data ) ) {
				$parsed_data = json_decode( $parsed_data, true );
			}

			return $parsed_data;
		} elseif ( is_array( $data ) ) {
			return array_combine(
				array_keys( $data ),
				array_map(
					function ( $item ) {
						return affx_maybe_parse_amazon_shortcode( $item );
					},
					$data
				)
			);
		}

		return $data;
	}
}
