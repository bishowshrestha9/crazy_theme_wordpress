<?php
namespace ProsCons\Traits;

use ProsCons\Elementor\Widgets\ElementorBase;
use ProsCons\Helpers\ProsCons_Helpers;
use ProsCons\Helpers\Elementor\WidgetHelper;

defined( 'ABSPATH' ) || exit;

/**
 * This trait is a channel for share rendering methods between Gutenberg and Elementor
 *
 * @package ProsCons
 */
trait NoticeRenderTrait {

	protected function get_slug(): string {
		return 'notice';
	}

	protected function get_fields(): array {
		return array(
			'block_id'            => '',
			'titleTag1'           => 'h2',
			'layoutStyle'         => 'layout-type-1',
			'noticeTitle'         => __( 'Notice', 'prosandcons' ),
			'noticeTitleIcon'     => array(
				'name'  => 'info-circle',
				'value' => 'fa fa-info-circle',
			),
			'noticeListItems'     => array( __( 'List items', 'prosandcons' ) ),
			'noticeListType'      => 'unordered',
			'noticeContent'       => __( 'This is the notice content', 'prosandcons' ),
			'noticeContentType'   => 'list',
			'noticeListIcon'      => array(
				'name'  => 'check-circle',
				'value' => 'far fa-check-circle',
			),
			'noticeunorderedType' => 'icon',
			'edTitleIcon'         => true,
			'titleAlignment'      => 'left',
		);
	}

	protected function get_elements(): array {
		return array(
			'wrapper'          => 'affx-notice-inner-wrapper',
			'layout-1-wrapper' => 'affx-notice-inner-wrapper.layout-type-1',
			'layout-2-wrapper' => 'affx-notice-inner-wrapper.layout-type-2',
			'title'            => 'prosandcons-notice-title',
			'paragraph'        => 'prosandcons-notice-content p',
			'list'             => 'prosandcons-notice-content li',
			'paragraph-list'   => 'prosandcons-notice-content p, {{WRAPPER}} .prosandcons-notice-content li',
			'content'          => 'prosandcons-notice-content',
			'list-icon'        => 'prosandcons-list li:before',
		);
	}

	protected function render(): void {
		$attributes             = $this->get_settings_for_display();
		$attributes             = $this->parse_attributes( $attributes );
		$attributes             = WidgetHelper::process_attributes( $attributes );
		$attributes['block_id'] = $this->get_id();

		if ( ! empty( $attributes['noticeListItems'] ) ) {
			$attributes['noticeListItems'] = WidgetHelper::extract_list_items( $attributes['noticeListItems'] );
		}

		if ( ! empty( $attributes['noticeTitleIcon'] ) ) {
			$attributes['noticeTitleIcon'] = WidgetHelper::extract_icon( $attributes['noticeTitleIcon'] );
		}

		if ( ! empty( $attributes['noticeListIcon'] ) ) {
			$attributes['noticeListIcon'] = WidgetHelper::extract_icon( $attributes['noticeListIcon'] );
		}

		if ( isset( $attributes['noticeListItemsAmazon'] ) && ! empty( $attributes['noticeListItemsAmazon'] ) ) {
			$attributes['noticeListItems'] = $attributes['noticeListItemsAmazon'];
		}

		echo wp_kses_post( $this->render_template( $attributes ) );
	}

	public function render_template( array $attributes ): string {
		$attributes = $this->parse_attributes( $attributes );

		extract( $attributes );

		if ( is_array( $noticeListItems ) && count( $noticeListItems ) === 1 && isset( $noticeListItems[0]['list'] ) && has_shortcode( $noticeListItems[0]['list'], 'prosandcons-product' ) ) {
			$noticeListItems = json_decode( do_shortcode( $noticeListItems[0]['list'] ), true );
		}

		$wrapper_class           = isset( $attributes['wrapper_class'] ) ? $attributes['wrapper_class'] : '';
		$wrapper_attributes_args = array(
			'class' => 'affx-notice-wrapper ' . $wrapper_class,
			'id'    => "prosandcons-notice-style-$block_id",
		);

		// Check if called from  Elementor.
		if ( self::IS_ELEMENTOR ) {
			$wrapper_attributes = ProsCons_Helpers::array_to_attributes( $wrapper_attributes_args );
		} else {
			$wrapper_attributes = get_block_wrapper_attributes( $wrapper_attributes_args );
		}

		$titleTag1 = ProsCons_Helpers::validate_tag( $titleTag1, 'h2' );

		if ( $noticeContentType === 'list' || $noticeContentType === 'amazon' ) {
			$listTag = $noticeListType === 'unordered' ? 'ul' : 'ol';
			$list    = ProsCons_Helpers::render_list(
				array(
					'listType'      => $noticeListType,
					'unorderedType' => $noticeunorderedType,
					'listItems'     => $noticeListItems,
					'iconName'      => $noticeListIcon['value'] ?? '',
				)
			);
		}

		ob_start();
		include $this->get_template_path();

		return ob_get_clean();
	}
}
