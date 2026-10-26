<?php

namespace ProsCons\Traits;

defined( 'ABSPATH' ) || exit;

use ProsCons\Helpers\ElementorHelper;
use ProsCons\Helpers\ProsCons_Helpers;
use ProsCons\Helpers\Elementor\ChildHelper;

/**
 * This trait is a channel for share rendering methods between Gutenberg and Elementor
 *
 * @package ProsCons
 */
trait ProsAndConsRenderTrait {

	use ButtonRenderTrait;

	protected function get_slug(): string {
		return 'pros-and-cons';
	}

	protected function get_fields(): array {
		return array(
			'block_id'          => '',
			'prosTitle'         => 'Pros',
			'consTitle'         => 'Cons',
			'prosIcon'          => array(
				'name'  => 'check-circle',
				'value' => 'far fa-circle-check',
			),
			'consIcon'          => array(
				'name'  => 'times-circle',
				'value' => 'far fa-circle-xmark',
			),
			'titleTag1'         => 'p',
			'layoutStyle'       => 'layout-type-1',
			'prosListItems'     => array(),
			'consListItems'     => array(),
			'prosContent'       => '',
			'consContent'       => '',
			'prosContentType'   => 'list',
			'consContentType'   => 'list',
			'prosListType'      => 'unordered',
			'consListType'      => 'unordered',
			'prosListIcon'      => array(
				'name'  => 'thumb-up-simple',
				'value' => 'far fa-thumbs-up',
			),
			'consListIcon'      => array(
				'name'  => 'thumb-down-simple',
				'value' => 'far fa-thumbs-down',
			),
			'prosunorderedType' => 'icon',
			'consunorderedType' => 'icon',
			'prosIconStatus'    => true,
			'consIconStatus'    => true,
		);
	}

	/**
	 * Parse attributes
	 *
	 * @param array $attributes
	 * @return array
	 */
	protected function parse_attributes( array $attributes ): array {
		$defaults = $this->get_fields();

		return wp_parse_args( $attributes, $defaults );
	}

	/**
	 * Core render function
	 *
	 * @param array $attributes
	 * @return string
	 */
	public function render_template( array $attributes ): string {
		$attributes = $this->parse_attributes( $attributes );
		extract( $attributes );

		if ( self::IS_ELEMENTOR ) {
			// Elementor Context.
			$wrapper_attributes = sprintf(
				" id='prosandcons-pros-cons-style-%s' class='%s'",
				$block_id,
				'affx-pros-cons-wrapper'
			);
		} else {
			// Gutenberg Context.
			$wrapper_attributes = get_block_wrapper_attributes(
				array(
					'id'    => "prosandcons-pros-cons-style-$block_id",
					'class' => 'affx-pros-cons-wrapper',
				)
			);
		}

		$inner_wrapper_classes = "$layoutStyle";

		if ( isset( $contentAlignment ) ) {
			$inner_wrapper_classes .= " content-align-$contentAlignment";
		}

		$titleTag1 = ProsCons_Helpers::validate_tag( $titleTag1, 'p' );

		if ( 'list' === $prosContentType || 'amazon' === $prosContentType ) {
			$prosList = ProsCons_Helpers::render_list(
				array(
					'listType'      => $prosListType,
					'unorderedType' => $prosUnorderedType,
					'listItems'     => $prosListItems,
					'iconName'      => isset( $prosIcon['value'] ) ? $prosIcon['value'] : '',
				)
			);
		}

		if ( 'list' === $consContentType || 'amazon' === $consContentType ) {
			$consList = ProsCons_Helpers::render_list(
				array(
					'listType'      => $consListType,
					'unorderedType' => $consUnorderedType,
					'listItems'     => $consListItems,
					'iconName'      => isset( $consIcon['value'] ) ? $consIcon['value'] : '',
				)
			);
		}

		ob_start();
		include $this->get_template_path();
		return ob_get_clean();
	}
}
