<?php

namespace ProsCons\Traits;

use ProsCons\Helpers\ProsCons_Helpers;
use ProsCons\Helpers\Elementor\ChildHelper;
use ProsCons\Helpers\Elementor\WidgetHelper;

defined( 'ABSPATH' ) || exit;

/**
 * Cta Render Trait
 *
 * @package ProsCons
 */
trait CtaRenderTrait {

	/**
	 * Child button 1 config
	 *
	 * @var array
	 */
	protected static $button1_config = array(
		'name_prefix'  => 'button_child1',
		'label_prefix' => 'Button',
		'index'        => 1,
		'is_child'     => true,
		'conditions'   => array( 'edButtons' => 'true' ),
		'wrapper'      => 'affx-btn-wrapper',
		'defaults'     => array(
			'button_label' => 'Buy Now',
			'buttonMargin' => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
				'unit'   => 'px',
			),
		),
	);

	/**
	 * Child button 2 config
	 *
	 * @var array
	 */
	protected static $button2_config = array(
		'name_prefix'  => 'button_child2',
		'label_prefix' => 'Button',
		'index'        => 2,
		'is_child'     => true,
		'wrapper'      => 'affx-btn-wrapper',
		'conditions'   => array(
			'edButtons'   => 'true',
			'edButtonTwo' => 'true',
		),
		'defaults'     => array(
			'button_label'      => 'More Details',
			'button_background' => array(
				'color' => array(
					'default' => '#FFB800',
				),
			),
			'buttonMargin'      => array(
				'top'    => 0,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
				'unit'   => 'px',
			),
		),
	);

	protected function get_elements(): array {
		return array(
			'wrapper'         => 'affblk-cta-wrapper > div',
			'title'           => 'affliatex-cta-title',
			'content'         => 'affliatex-cta-content',
			'buttons'         => 'button-wrapper',
			'image'           => 'image-wrapper',
			'content_wrapper' => 'content-wrapper',
		);
	}

	protected function get_slug(): string {
		return 'cta';
	}

	protected function get_fields(): array {
		return array(
			'block_id'           => '',
			'ctaTitle'           => 'Call to Action Title.',
			'ctaTitleTag'        => 'h2',
			'ctaContent'         => 'Start creating CTAs in seconds, and convert more of your visitors into leads.',
			'ctaBGType'          => 'color',
			'ctaLayout'          => 'layoutOne',
			'ctaAlignment'       => 'center',
			'columnReverse'      => false,
			'ctaButtonAlignment' => 'center',
			'edButtons'          => true,
		);
	}

	/**
	 * Elementor render
	 *
	 * @return void
	 */
	protected function render(): void {
		$attributes             = $this->get_settings_for_display();
		$attributes             = WidgetHelper::process_attributes( $attributes );
		$attributes['block_id'] = $this->get_id();

		$button1 = '';
		$button2 = '';

		if ( isset( $attributes['edButtons'] ) && $attributes['edButtons'] === true ) {
			$child_attributes                      = ChildHelper::extract_attributes( $attributes, self::$button1_config );
			$child_attributes['parent_attributes'] = array( 'blockName' => 'cta' );

			ob_start();
			$this->render_button( $child_attributes );
			$button1 = ob_get_clean();
		}

		if ( isset( $attributes['edButtons'] ) && isset( $attributes['edButtonTwo'] ) && $attributes['edButtonTwo'] === true ) {
			$child_attributes                      = ChildHelper::extract_attributes( $attributes, self::$button2_config );
			$child_attributes['parent_attributes'] = array( 'blockName' => 'cta' );

			ob_start();
			$this->render_button( $child_attributes );
			$button2 = ob_get_clean();
		}

		if ( isset( $attributes['ctaContent'] ) && json_decode( $attributes['ctaContent'] ) ) {
			$attributes['ctaContent'] = ProsCons_Helpers::render_list(
				array(
					'listType'      => 'ordered',
					'unorderedType' => 'icon',
					'listItems'     => json_decode( $attributes['ctaContent'], true ) ?? array(),
					'iconName'      => '',
				)
			);
		}

		echo wp_kses_post( $this->render_template( $attributes, $button1 . $button2 ) );
	}

	/**
	 * Core render template
	 *
	 * @param array $attributes
	 * @param string $content
	 * @return string
	 */
	public function render_template( array $attributes, string $content = '' ): string {
		$attributes = $this->parse_attributes( $attributes );
		extract( $attributes );

		$wrapper_attributes = 'class="affblk-cta-wrapper"';

		if ( self::IS_ELEMENTOR ) {
			$wrapper_attributes = sprintf(
				'class="affblk-cta-wrapper %s" id="prosandcons-style-%s"',
				$attributes['wrapper_class'] ?? '',
				$attributes['block_id']
			);
		} else {
			// Use get_block_wrapper_attributes to get the class names and other attributes.
			$wrapper_attributes = get_block_wrapper_attributes(
				array(
					'class' => 'affblk-cta-wrapper ' . ( $attributes['wrapper_class'] ?? '' ),
					'id'    => "prosandcons-style-$block_id",
				)
			);
		}

		$layoutClass        = ( $ctaLayout === 'layoutOne' ) ? ' layout-type-1' : ( ( $ctaLayout === 'layoutTwo' ) ? ' layout-type-2' : '' );
		$columnReverseClass = ( $columnReverse && $ctaLayout !== 'layoutOne' ) ? ' col-reverse' : '';
		$ctaTitleTag        = ProsCons_Helpers::validate_tag( $ctaTitleTag, 'h2' );

		if ( ( isset( $attributes['ctaBGType_background'] ) && $attributes['ctaBGType_background'] === 'image' ) || ( isset( $attributes['ctaBGType'] ) && $attributes['ctaBGType'] === 'image' ) ) {
			$bgClass = ' img-opacity';
		} else {
			$bgClass = ' bg-color';
		}

		if ( str_contains( $content, $layoutClass ) ) {
			return $content;
		}

		$inlineImageWrapperStyles = '';
		if ( isset( $attributes['imageType'] ) && $attributes['imageType'] === 'external' && ! empty( $attributes['imageExternal'] ) ) {
			$inlineImageWrapperStyles = 'style="background-image: url(' . esc_url( $attributes['imageExternal'] ) . ')"';
		} elseif ( isset( $attributes['useExternalImage'] ) && $attributes['useExternalImage'] && ! empty( $attributes['ctaExternalBgImage'] ) ) {
			$inlineImageWrapperStyles = 'style="background-image: url(' . esc_url( $attributes['ctaExternalBgImage'] ) . ')"';
		}

		$classes = implode( ' ', array( esc_attr( $layoutClass ), esc_attr( $ctaAlignment ), esc_attr( $columnReverseClass ), esc_attr( $bgClass ) ) );

		ob_start();
		include $this->get_template_path();

		return ob_get_clean();
	}
}
