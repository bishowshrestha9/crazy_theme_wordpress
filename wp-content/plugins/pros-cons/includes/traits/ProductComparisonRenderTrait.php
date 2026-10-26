<?php
namespace ProsCons\Traits;

use ProsCons\Helpers\ProsCons_Helpers;
use ProsCons\Helpers\Elementor\WidgetHelper;

defined( 'ABSPATH' ) || exit;

/**
 * This trait is a channel for share rendering methods between Gutenberg and Elementor
 *
 * @package ProsCons/Traits
 */
trait ProductComparisonRenderTrait {

	protected function get_elements(): array {
		return array(
			'wrapper'                       => 'wp-block-prosandcons-product-comparison',
			'container'                     => 'affx-product-comparison-block-container',
			'product-table'                 => 'affx-versus-table-wrap .affx-product-versus-table',
			'table-headings'                => 'affx-versus-table-wrap .affx-product-versus-table th',
			'table-cells'                   => 'affx-versus-table-wrap .affx-product-versus-table td',
			'table-alternate-row'           => 'affx-versus-table-wrap .affx-product-versus-table tbody tr:nth-child(odd)',
			'product-title'                 => 'affx-versus-table-wrap .affx-product-versus-table .affx-comparison-title',
			'product-ribbon'                => 'affx-versus-table-wrap .affx-product-versus-table .affx-pc-ribbon',
			'product-ribbon:hover'          => 'affx-versus-table-wrap .affx-product-versus-table .affx-pc-ribbon:hover',
			'product-price'                 => 'affx-versus-table-wrap .affx-product-versus-table .affx-price',
			'product-content'               => 'affx-versus-table-wrap .affx-product-versus-table td',
			'product-rating-stars-active'   => '',
			'product-rating-stars-inactive' => '',
			'product-image'                 => 'affx-versus-table-wrap .affx-product-versus-table .affx-versus-product-img',
			'button'                        => 'prosandcons-button',
			'button:hover'                  => 'prosandcons-button:hover',
		);
	}

	protected function get_slug(): string {
		return 'product-comparison';
	}

	private function render_pc_stars( $rating, $starColor, $starInactiveColor ) {
		$full_star  = '<span class="affx-star" style="color:' . esc_attr( $starColor ) . ';width:25px;height:25px;"><svg fill="currentColor" width="25" height="25" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path></svg></span>';
		$empty_star = '<span class="affx-star" style="color:' . esc_attr( $starInactiveColor ) . ';width:25px;height:25px;"><svg fill="currentColor" width="25" height="25" viewBox="0 0 24 24"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"></path></svg></span>';

		$stars = '';
		for ( $i = 0; $i < 5; $i++ ) {
			if ( $i < $rating ) {
				$stars .= $full_star;
			} else {
				$stars .= $empty_star;
			}
		}

		return '<span class="rating-stars">' . $stars . '</span>';
	}

	protected function get_fields(): array {
		return array(
			'block_id'               => '',
			'productComparisonTable' => array(),
			'comparisonSpecs'        => array(),
			'pcRibbon'               => true,
			'pcTitle'                => true,
			'starColor'              => '#FFB800',
			'starInactiveColor'      => '#A3ACBF',
			'pcImage'                => true,
			'pcRating'               => true,
			'pcPrice'                => true,
			'pcButton'               => true,
			'pcTitleColumn'          => true,
			'pcButtonIcon'           => true,
			'buttonIconAlign'        => 'right',
			'buttonIcon'             => array(
				'name'  => 'angle-right',
				'value' => 'fas fa-angle-right',
			),
			'pcTitleTag'             => 'h2',
			'pcTitleAlign'           => 'center',
			'matchCardHeights'       => false,
		);
	}

	/**
	 * Parse and format comparison table data from Repeater fields
	 *
	 * @param array $products
	 * @return array
	 */
	protected function parse_comparison_table( array $products ): array {
		foreach ( $products as $index => $product ) {
			if ( isset( $product['productImageType'] ) ) {
				if ( $product['productImageType'] === 'external' ) {
					$product['imageUrl'] = $product['productImageExternal'];
				} else {
					$product['imageUrl'] = $product['imageUrl']['url'];
				}
			}

			$products[ $index ] = $product;
		}

		return $products;
	}

	/**
	 * Parse and format comparison specs data from Repeater fields
	 *
	 * @param array $specs
	 * @return array
	 */
	protected function parse_comparison_specs( array $specs ): array {
		$formatted_specs = array();

		foreach ( $specs as $row ) {
			$dataRow          = array();
			$rowTitle         = $row['title'];
			$dataRow['title'] = $rowTitle;
			$specs            = array();

			foreach ( $row['inner_rows'] as $productSpecification ) {
				$specs[] = $productSpecification['spec'];
			}
			$dataRow['specs'] = $specs;

			$formatted_specs[] = $dataRow;
		}

		return $formatted_specs;
	}

	protected function render(): void {
		$attributes             = $this->get_settings_for_display();
		$attributes             = $this->parse_attributes( $attributes );
		$attributes             = WidgetHelper::process_attributes( $attributes );
		$attributes['block_id'] = $this->get_id();

		if ( ! empty( $attributes['productComparisonTable'] ) ) {
			$attributes['productComparisonTable'] = $this->parse_comparison_table( $attributes['productComparisonTable'] );
		}

		if ( ! empty( $attributes['comparisonSpecs'] ) ) {
			$attributes['comparisonSpecs'] = $this->parse_comparison_specs( $attributes['comparisonSpecs'] );
		}

		if ( ! empty( $attributes['buttonIcon'] ) ) {
			$attributes['buttonIcon'] = WidgetHelper::extract_icon( $attributes['buttonIcon'] );
		}

		echo ProsCons_Helpers::kses( $this->render_template( $attributes ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function render_template( array $attributes ): string {
		$attributes = $this->parse_attributes( $attributes );
		extract( $attributes );

		if ( self::IS_ELEMENTOR ) {
			// Elementor Context.
			$wrapper_attributes = sprintf(
				" id='prosandcons-product-comparison-blocks-style-%s' class='%s'",
				$block_id,
				$attributes['wrapper_class'] ?? ''
			);
		} else {
			// Gutenberg Context.
			$wrapper_attributes = get_block_wrapper_attributes(
				array(
					'class' => $attributes['wrapper_class'] ?? '',
					'id'    => "prosandcons-product-comparison-blocks-style-$block_id",
				)
			);
		}

		foreach ( $productComparisonTable as $key => $product ) {
			$product['rel'] = array();

			if ( $product['btnRelNoFollow'] ) {
				$product['rel'][] = 'nofollow';
			}

			if ( $product['btnRelSponsored'] ) {
				$product['rel'][] = 'sponsored';
			}

			$product['rel']      = ! empty( $product['rel'] ) ? "rel='" . implode( ' ', $product['rel'] ) . "'" : '';
			$product['target']   = $product['btnOpenInNewTab'] ? 'target="_blank"' : '';
			$product['download'] = $product['btnDownload'] ? 'download' : '';

			$productComparisonTable[ $key ] = $product;
		}

		$pcTitleTag = ProsCons_Helpers::validate_tag( $pcTitleTag, 'h2' );

		ob_start();
		include $this->get_template_path();
		return ob_get_clean();
	}
}
