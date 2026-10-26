<?php

namespace ProsCons\Traits;

use ProsCons\Helpers\ProsCons_Helpers;
use ProsCons\Helpers\Elementor\ChildHelper;

defined( 'ABSPATH' ) || exit;

/**
 * Product Table Render Trait
 *
 * @package ProsCons
 */
trait ProductTableRenderTrait {

	protected function get_slug(): string {
		return 'product-table';
	}

	private function render_pt_stars( $rating, $starColor, $starInactiveColor ) {
		$output = '';
		for ( $i = 0; $i < 5; $i++ ) {
			$color   = $i < $rating ? $starColor : $starInactiveColor;
			$output .= sprintf(
				'<span class="affx-star" style="color:%s;width:25px;height:25px;"><svg fill="currentColor" width="25" height="25" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path></svg></span>',
				esc_attr( $color )
			);
		}
		return $output;
	}

	protected function get_fields(): array {
		return array(
			'block_id'                => '',
			'layoutStyle'             => 'layoutOne',
			'imageColTitle'           => 'Image',
			'productColTitle'         => 'Product',
			'featuresColTitle'        => 'Features',
			'ratingColTitle'          => 'Rating',
			'priceColTitle'           => 'Price',
			'edImage'                 => true,
			'edCounter'               => true,
			'edProductName'           => true,
			'edRating'                => true,
			'edRibbon'                => true,
			'edPrice'                 => false,
			'edButton1'               => true,
			'edButton1Icon'           => true,
			'button1Icon'             => array(
				'name'  => 'angle-right',
				'value' => 'fas fa-angle-right',
			),
			'button1IconAlign'        => 'right',
			'edButton2'               => true,
			'edButton2Icon'           => true,
			'button2Icon'             => array(
				'name'  => 'angle-right',
				'value' => 'fas fa-angle-right',
			),
			'button2IconAlign'        => 'right',
			'starColor'               => '#FFB800',
			'starInactiveColor'       => '#A3ACBF',
			'productContentType'      => 'paragraph',
			'contentListType'         => 'unordered',
			'productIconList'         => array(
				'name'  => 'check-circle-outline',
				'value' => 'far fa-check-circle',
			),
			'productNameTag'          => 'h5',
			'productTable'            => array(
				array(
					'imageUrl'         => 'PLUGIN_URL_PLACEHOLDERsrc/images/fallback.jpg',
					'imageId'          => '',
					'imageAlt'         => '',
					'ribbon'           => 'Our Pick',
					'name'             => 'Product Name',
					'features'         => 'Product Features',
					'featuresList'     => array(),
					'offerPrice'       => '$49.00',
					'regularPrice'     => '$59.00',
					'rating'           => '5',
					'button1'          => 'Purchase Now',
					'button1URL'       => '',
					'btn1RelNoFollow'  => false,
					'btn1RelSponsored' => false,
					'btn1OpenInNewTab' => false,
					'btn1Download'     => false,
					'button2'          => 'Check on Amazon',
					'button2URL'       => '',
					'btn2RelNoFollow'  => false,
					'btn2RelSponsored' => false,
					'btn2OpenInNewTab' => false,
					'btn2Download'     => false,
				),
			),
			'button1Border'           => array(
				'width' => '1',
				'style' => 'none',
				'color' => array(
					'color' => '#dddddd',
				),
			),
			'button1borderHoverColor' => '#ffffff',
			'button1Shadow'           => array(
				'enable'   => false,
				'h_offset' => 0,
				'v_offset' => 5,
				'blur'     => 20,
				'spread'   => 0,
				'inset'    => false,
				'color'    => array(
					'color' => 'rgba(210,213,218,0.2)',
				),
			),
			'button2Border'           => array(
				'width' => '1',
				'style' => 'none',
				'color' => array(
					'color' => '#dddddd',
				),
			),
			'button2borderHoverColor' => '#ffffff',
			'button2Shadow'           => array(
				'enable'   => false,
				'h_offset' => 0,
				'v_offset' => 5,
				'blur'     => 20,
				'spread'   => 0,
				'inset'    => false,
				'color'    => array(
					'color' => 'rgba(210,213,218,0.2)',
				),
			),
			'boxShadow'               => array(
				'enable'   => false,
				'h_offset' => 0,
				'v_offset' => 5,
				'blur'     => 20,
				'spread'   => 0,
				'inset'    => false,
				'color'    => array(
					'color' => 'rgba(137,138,140,0.2)',
				),
			),
			'border'                  => array(
				'width' => '1',
				'style' => 'solid',
				'color' => array(
					'color' => '#E6ECF7',
				),
			),
			'ribbonColor'             => '#FFFFFF',
			'ribbonBgColor'           => '#F13A3A',
			'counterColor'            => '#FFFFFF',
			'counterBgColor'          => '#24B644',
			'tableHeaderColor'        => '#FFFFFF',
			'tableHeaderBgColor'      => '#00454A',
			'priceColor'              => '#262B33',
			'ratingColor'             => '#FFFFFF',
			'ratingBgColor'           => '#24B644',
			'rating2Color'            => '#262B33',
			'rating2BgColor'          => '#24B644',
			'contentColor'            => '#292929',
			'titleColor'              => '#292929',
			'bgType'                  => 'solid',
			'bgColorSolid'            => '#FFFFFF',
			'bgColorGradient'         => array(
				'gradient' => 'linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)',
			),
			'button1TextColor'        => '#fff',
			'button1TextHoverColor'   => '#fff',
			'button1BgColor'          => '#00B0B0',
			'button1BgHoverColor'     => '#00454A',
			'button2TextColor'        => '#fff',
			'button2TextHoverColor'   => '#fff',
			'button2BgColor'          => '#FFB800',
			'button2BgHoverColor'     => '#00454A',
			'productIconColor'        => '#24B644',
		);
	}

	public function render_template( array $attributes ): string {
		$attributes = $this->parse_attributes( $attributes );
		extract( $attributes );

		if ( self::IS_ELEMENTOR ) {
			// Elementor Context.
			$wrapper_attributes = "id='prosandcons-pdt-table-style-$block_id'";
		} else {
			// Gutenberg Context.
			$wrapper_attributes = get_block_wrapper_attributes(
				array(
					'id' => "prosandcons-pdt-table-style-$block_id",
				)
			);
		}

		$productNameTag = ProsCons_Helpers::validate_tag( $productNameTag, 'h5' );

		// Process each product's features list
		$processedProductTable = array();
		foreach ( $productTable as $product ) {
			$processedProduct = $product;
			if ( isset( $product['featuresList'] ) ) {
				$featuresList = $product['featuresList'];
				if ( is_array( $featuresList ) && count( $featuresList ) === 1 && isset( $featuresList[0]['list'] ) && has_shortcode( $featuresList[0]['list'], 'prosandcons-product' ) ) {
					$featuresList = json_decode( do_shortcode( $featuresList[0]['list'] ), true );
				}

				$processedProduct['list'] = ProsCons_Helpers::render_list(
					array(
						'listType'      => $contentListType,
						'unorderedType' => 'icon',
						'listItems'     => is_array( $featuresList ) ? $featuresList : array( $featuresList ),
						'iconName'      => $productIconList['value'] ?? '',
					)
				);
			}
			$processedProductTable[] = $processedProduct;
		}

		$productTable = $processedProductTable;

		ob_start();
		include $this->get_template_path();
		return ob_get_clean();
	}
}
