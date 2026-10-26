<?php

namespace ProsCons\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Utils;
use Elementor\Controls_Manager;
use ProsCons\Traits\CtaRenderTrait;
use Elementor\Group_Control_Background;
use ProsCons\Traits\ButtonRenderTrait;
use ProsCons\Helpers\Elementor\ChildHelper;
use ProsCons\Elementor\ControlsManager;
use ProsCons\Blocks\ProsCons_Customization_Helper;

/**
 * Cta Widget Class
 *
 * @package ProsCons\Elementor\Widgets
 */
class CtaWidget extends ElementorBase {

	use CtaRenderTrait;
	use ButtonRenderTrait;

	protected function get_slug(): string {
		return 'cta';
	}

	protected function get_child_slugs(): array {
		return array( 'buttons' );
	}

	public function get_title() {
		return __( 'ProsCons Call To Action', 'prosandcons' );
	}

	public function get_icon() {
		return 'affx-icon-cta';
	}

	public function get_keywords() {
		return array(
			'CTA',
			'affliatex',
		);
	}

	protected function register_controls() {
		//
		// CONTENT TAB
		//
		/**************************************************************
		 * Layout Settings
		 */
		$this->start_controls_section(
			'affx_cta_layout_settings',
			array(
				'label' => __( 'Layout Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'ctaLayout',
			array(
				'label'   => __( 'Select Layout', 'prosandcons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'layoutOne' => __( 'Layout One', 'prosandcons' ),
					'layoutTwo' => __( 'Layout Two', 'prosandcons' ),
				),
				'default' => 'layoutOne',
			)
		);

		$this->add_control(
			'ctaAlignment',
			array(
				'label'     => __( 'Alignment', 'prosandcons' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'center',
				'toggle'    => false,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'prosandcons' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'prosandcons' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'prosandcons' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'condition' => array(
					'ctaLayout' => 'layoutOne',
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * CTA Image
		 */
		$this->start_controls_section(
			'affx_cta_image_settings',
			array(
				'label'     => __( 'CTA Image', 'prosandcons' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'ctaLayout' => 'layoutTwo',
				),
			)
		);

		$this->add_control(
			'imageType',
			array(
				'name'      => 'imageType',
				'label'     => __( 'Image Source', 'prosandcons' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'default',
				'options'   => array(
					'default'  => array(
						'title' => __( 'Upload', 'prosandcons' ),
						'icon'  => 'eicon-kit-upload',
					),
					'external' => array(
						'title' => __( 'External', 'prosandcons' ),
						'icon'  => 'eicon-external-link-square',
					),
				),
				'toggle'    => false,
				'condition' => array(
					'ctaLayout' => 'layoutTwo',
				),
			)
		);

		$this->add_control(
			'imgURL',
			array(
				'label'     => __( 'Image', 'prosandcons' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'condition' => array(
					'ctaLayout' => 'layoutTwo',
					'imageType' => 'default',
				),
				'selectors' => array(
					$this->select_element( 'image' ) => 'background-image: url({{URL}})',
				),
			)
		);

		$this->add_control(
			'imageExternal',
			array(
				'label'     => __( 'External Image URL', 'prosandcons' ),
				'type'      => ControlsManager::TEXT,
				'classes'   => 'affx-cta-image-external',
				'condition' => array(
					'ctaLayout' => 'layoutTwo',
					'imageType' => 'external',
				),
				'selectors' => array(
					$this->select_element( 'image' ) => 'background-image: url({{VALUE}})',
				),
			)
		);

		$this->add_control(
			'imagePosition',
			array(
				'label'     => __( 'Image Position', 'prosandcons' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'center center' => __( 'Center Center', 'prosandcons' ),
					'center left'   => __( 'Center Left', 'prosandcons' ),
					'center right'  => __( 'Center Right', 'prosandcons' ),
					'top center'    => __( 'Top Center', 'prosandcons' ),
					'top left'      => __( 'Top Left', 'prosandcons' ),
					'top right'     => __( 'Top Right', 'prosandcons' ),
					'bottom center' => __( 'Bottom Center', 'prosandcons' ),
					'bottom left'   => __( 'Bottom Left', 'prosandcons' ),
					'bottom right'  => __( 'Bottom Right', 'prosandcons' ),
				),
				'default'   => 'center center',
				'selectors' => array(
					$this->select_element( 'image' ) => 'background-position: {{VALUE}}; align-items: flex-end; background-repeat: no-repeat; background-size: cover; display: flex; flex: 0 0 50%; justify-content: flex-end;',
				),
				'condition' => array(
					'ctaLayout' => 'layoutTwo',
				),
			)
		);

		$this->add_control(
			'columnReverse',
			array(
				'label'        => __( 'Enable Column Reverse', 'prosandcons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'prosandcons' ),
				'label_off'    => __( 'No', 'prosandcons' ),
				'return_value' => 'true',
				'default'      => '',
				'condition'    => array(
					'ctaLayout' => 'layoutTwo',
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Title Settings
		 */
		$this->start_controls_section(
			'affx_cta_title',
			array(
				'label' => __( 'Title Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'ctaTitle',
			array(
				'label'       => esc_html__( 'Title', 'prosandcons' ),
				'type'        => ControlsManager::TEXT,
				'default'     => esc_html__( 'Call to Action Title.', 'prosandcons' ),
				'placeholder' => esc_html__( 'Type your title here', 'prosandcons' ),
			)
		);

		$this->add_control(
			'ctaTitleTag',
			array(
				'label'   => __( 'Title Heading Tag', 'prosandcons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h2' => __( 'Heading 2 (h2)', 'prosandcons' ),
					'h3' => __( 'Heading 3 (h3)', 'prosandcons' ),
					'h4' => __( 'Heading 4 (h4)', 'prosandcons' ),
					'h5' => __( 'Heading 5 (h5)', 'prosandcons' ),
					'h6' => __( 'Heading 6 (h6)', 'prosandcons' ),
					'p'  => __( 'Paragraph (p)', 'prosandcons' ),

				),
				'default' => 'h2',
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Content Settings
		 */
		$this->start_controls_section(
			'affx_cta_content',
			array(
				'label' => __( 'Content Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'ctaContent',
			array(
				'label'       => esc_html__( 'Description', 'prosandcons' ),
				'type'        => ControlsManager::TEXTAREA,
				'default'     => esc_html__( 'Start creating CTAs in seconds, and convert more of your visitors into leads.', 'prosandcons' ),
				'placeholder' => esc_html__( 'Type your description here', 'prosandcons' ),
				'rows'        => 4,
			)
		);

		$this->add_control(
			'contentAlignment',
			array(
				'label'     => __( 'Content Alignment', 'prosandcons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'prosandcons' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'prosandcons' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'prosandcons' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => false,
				'selectors' => array(
					$this->select_element( 'title' )   => 'text-align: {{VALUE}}',
					$this->select_element( 'content' ) => 'text-align: {{VALUE}}',
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Button Settings
		 */
		$this->start_controls_section(
			'affx_cta_button_settings',
			array(
				'label' => __( 'Button Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'edButtons',
			array(
				'label'        => __( 'Enable Buttons', 'prosandcons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'prosandcons' ),
				'label_off'    => __( 'No', 'prosandcons' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'edButtonTwo',
			array(
				'label'        => __( 'Enable Button Two', 'prosandcons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'prosandcons' ),
				'label_off'    => __( 'No', 'prosandcons' ),
				'return_value' => 'true',
				'default'      => 'true',
				'condition'    => array(
					'edButtons' => 'true',
				),
			)
		);

		$this->add_control(
			'ctaButtonAlignment',
			array(
				'label'     => __( 'Button Alignment', 'prosandcons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => __( 'Left', 'prosandcons' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'prosandcons' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'prosandcons' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'toggle'    => false,
				'selectors' => array(
					$this->select_element( 'buttons' ) => 'display: flex; flex-wrap: wrap; width: 100%; justify-content: {{VALUE}};',
				),
				'condition' => array(
					'edButtons' => 'true',
				),
			)
		);

		$this->end_controls_section();

		//
		// STYLE TAB
		//

		/**************************************************************
		 * Border Settings
		 */

		$this->start_controls_section(
			'affx_cta_border_settings',
			array(
				'label' => __( 'Border', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'           => 'ctaBorder',
				'label'          => __( 'Border', 'prosandcons' ),
				'responsive'     => true,
				'selector'       => $this->select_element( 'wrapper' ),
				'fields_options' => array(
					'border' => array(
						'default' => 'solid',
					),
					'color'  => array(
						'default' => '#E6ECF7',
					),
					'width'  => array(
						'default' => array(
							'isLinked' => true,
							'unit'     => 'px',
							'top'      => '1',
							'right'    => '1',
							'bottom'   => '1',
							'left'     => '1',
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'ctaBorderRadius',
			array(
				'label'      => __( 'Border Radius', 'prosandcons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'rem', 'em' ),
				'default'    => array(
					'top'    => 8,
					'right'  => 8,
					'bottom' => 8,
					'left'   => 8,
					'unit'   => 'px',
				),
				'selectors'  => array(
					$this->select_element( 'wrapper' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'ctaBoxShadow',
				'label'    => __( 'Box Shadow', 'prosandcons' ),
				'selector' => $this->select_element( 'wrapper' ),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Color Settings
		 */
		$this->start_controls_section(
			'affx_cta_colors_section',
			array(
				'label' => __( 'Colors', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'ctaTitleColor',
			array(
				'label'     => __( 'Title Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#262b33',
				'selectors' => array(
					$this->select_element( 'title' ) => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ctaTextColor',
			array(
				'label'     => __( 'Text Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => ProsCons_Customization_Helper::get_value( 'fontColor', '#292929' ),
				'selectors' => array(
					$this->select_element( 'content' ) => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'ctaLayout2BGType',
			array(
				'label'     => esc_html__( 'Background Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					$this->select_element( 'wrapper' ) => 'background-color: {{VALUE}}',
				),
				'condition' => array(
					'ctaLayout' => 'layoutTwo',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'ctaBGType',
				'label'          => __( 'Background', 'prosandcons' ),
				'types'          => array( 'classic', 'image' ),
				'exclude'        => array( 'gradient' ),
				'selector'       => $this->select_element( 'wrapper' ),
				'fields_options' => array(
					'background'       => array(
						'label'       => __( 'Background Type', 'prosandcons' ),
						'default'     => 'classic',
						'options'     => array(
							'classic' => array(
								'title' => __( 'Color', 'prosandcons' ),
								'icon'  => 'eicon-global-colors',
							),
							'image'   => array(
								'title' => __( 'Image', 'prosandcons' ),
								'icon'  => 'eicon-e-image',
							),
						),
						'toggle'      => false,
						'render_type' => 'template',
					),
					'color'            => array(
						'label'     => __( 'Background Color', 'prosandcons' ),
						'default'   => '#fff',
						'condition' => array(
							'background' => 'classic',
						),
					),
					'image'            => array(
						'label'     => __( 'Background Image', 'prosandcons' ),
						'condition' => array(
							'background' => 'image',
						),
					),
					'position'         => array(
						'default'   => 'center center',
						'condition' => array(
							'background'  => 'image',
							'image[url]!' => '',
						),
					),
					'xpos'             => array(
						'condition' => array(
							'background'  => array( 'image' ),
							'position'    => array( 'initial' ),
							'image[url]!' => '',
						),
					),
					'ypos'             => array(
						'condition' => array(
							'background'  => array( 'image' ),
							'position'    => array( 'initial' ),
							'image[url]!' => '',
						),
					),
					'attachment'       => array(
						'condition' => array(
							'background'  => 'image',
							'image[url]!' => '',
						),
					),
					'attachment_alert' => array(
						'condition' => array(
							'background'  => 'image',
							'image[url]!' => '',
							'attachment'  => 'fixed',
						),
					),
					'repeat'           => array(
						'default'   => 'no-repeat',
						'condition' => array(
							'background'  => 'image',
							'image[url]!' => '',
						),
					),
					'size'             => array(
						'default'   => 'cover',
						'condition' => array(
							'background'  => 'image',
							'image[url]!' => '',
						),
					),
					'bg_width'         => array(
						'condition' => array(
							'background'  => 'image',
							'size'        => 'initial',
							'image[url]!' => '',
						),
					),
				),
				'condition'      => array(
					'ctaLayout' => 'layoutOne',
				),
			)
		);

		$this->add_control(
			'ctaExternalBgImage',
			array(
				'label'     => __( 'External Image URL', 'prosandcons' ),
				'type'      => ControlsManager::TEXT,
				'condition' => array(
					'ctaLayout'            => 'layoutOne',
					'ctaBGType_background' => 'image',
				),
			)
		);

		$this->add_control(
			'overlayOpacity',
			array(
				'label'      => esc_html__( 'Overlay Opacity', 'prosandcons' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%' ),
				'range'      => array(
					'%' => array(
						'min'  => 0,
						'max'  => 1,
						'step' => 0.1,
					),
				),
				'default'    => array(
					'unit' => '%',
					'size' => 0.1,
				),
				'selectors'  => array(
					$this->select_element( 'wrapper' ) . '::before' => 'opacity: {{SIZE}};',
				),
				'condition'  => array(
					'ctaLayout'            => 'layoutOne',
					'ctaBGType_background' => 'image',
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Typography Section
		 */
		$this->start_controls_section(
			'affx_cta_section_typography',
			array(
				'label' => __( 'Typography', 'prosandcons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'ctaTitleTypography',
				'label'          => __( 'Title Typography', 'prosandcons' ),
				'selector'       => $this->select_element( 'title' ),
				'fields_options' => array(
					'typography'      => array(
						'default' => 'custom',
					),
					'font_family'     => array(
						'default' => ProsCons_Customization_Helper::get_value( 'typography.family', '' ),
					),
					'font_weight'     => array(
						'default' => '400',
					),
					'font_size'       => array(
						'default' => array(
							'unit' => 'px',
							'size' => 40,
						),
					),
					'line_height'     => array(
						'default' => array(
							'unit' => '',
							'size' => 1.5,
						),
					),
					'letter_spacing'  => array(
						'default' => array(
							'unit' => 'em',
							'size' => 0,
						),
					),
					'text_transform'  => array(
						'default' => 'none',
					),
					'text_decoration' => array(
						'default' => 'none',
					),
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'           => 'ctaContentTypography',
				'label'          => __( 'Content Typography', 'prosandcons' ),
				'selector'       => $this->select_element( 'content' ),
				'fields_options' => array(
					'typography'      => array(
						'default' => 'custom',
					),
					'font_family'     => array(
						'default' => ProsCons_Customization_Helper::get_value( 'typography.family', '' ),
					),
					'font_weight'     => array(
						'default' => '400',
					),
					'font_size'       => array(
						'default' => array(
							'unit' => 'px',
							'size' => 18,
						),
					),
					'line_height'     => array(
						'default' => array(
							'unit' => '',
							'size' => 1.5,
						),
					),
					'letter_spacing'  => array(
						'default' => array(
							'unit' => 'em',
							'size' => 0,
						),
					),
					'text_transform'  => array(
						'default' => 'none',
					),
					'text_decoration' => array(
						'default' => 'none',
					),
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Spacing Section
		 */
		$this->start_controls_section(
			'affx_cta_section_spacing',
			array(
				'label' => __( 'Spacing', 'prosandcons' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'ctaBoxPadding',
			array(
				'label'      => __( 'Padding', 'prosandcons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => '60',
					'right'    => '30',
					'bottom'   => '60',
					'left'     => '30',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					$this->select_element( 'wrapper' ) . '.layout-type-1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					$this->select_element( 'wrapper' ) . '.layout-type-2 .content-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'ctaMargin',
			array(
				'label'      => __( 'Margin', 'prosandcons' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '30',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					$this->select_element( 'wrapper' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		// Amazon Attributes Configuration
		$this->add_control(
			'amazonAttributes',
			array(
				'type'    => Controls_Manager::HIDDEN,
				'default' => array(
					array(
						'field'      => 'title',
						'blockField' => array(
							'name'     => 'ctaTitle',
							'type'     => 'text',
							'defaults' => array(
								'ctaTitle' => esc_html__( 'Call to Action Title.', 'prosandcons' ),
							),
						),
						'type'       => 'text',
					),
					array(
						'blockField' => array(
							'name'     => 'ctaContent',
							'type'     => 'text',
							'defaults' => array(
								'ctaContent' => esc_html__( 'Start creating CTAs in seconds, and convert more of your visitors into leads.', 'prosandcons' ),
							),
						),
						'type'       => 'text',
					),
					array(
						'field'      => 'display_price',
						'blockField' => array(
							'name'       => 'button_child1_1_productPrice',
							'type'       => 'text',
							'defaults'   => array(
								'button_child1_1_productPrice' => '$145',
							),
							'conditions' => array(
								'button_child1_1_layoutStyle' => 'layout-type-2',
							),
						),
						'type'       => 'text',
					),
					array(
						'field'      => 'images',
						'blockField' => array(
							'name'       => 'imageExternal',
							'type'       => 'image',
							'defaults'   => array(
								'imageExternal' => '',
								'imageType'     => 'default',
							),
							'conditions' => array(
								'imageType' => 'external',
							),
						),
						'type'       => 'image',
					),
					array(
						'field'      => 'images',
						'blockField' => array(
							'name'     => 'ctaExternalBgImage',
							'type'     => 'image',
							'defaults' => array(
								'ctaExternalBgImage' => '',
							),
						),
						'type'       => 'image',
					),
					array(
						'field'      => 'url',
						'blockField' => array(
							'name'     => 'button_child1_1_buttonURL',
							'type'     => 'link',
							'defaults' => array(
								'button_child1_1_buttonURL' => '',
							),
						),
						'type'       => 'link',
					),
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Button 1 & 2 fields
		 */
		$child1 = new ChildHelper( $this, $this->get_button_elementor_fields(), self::$button1_config );
		$child2 = new ChildHelper( $this, $this->get_button_elementor_fields(), self::$button2_config );

		$child1->generate_fields();
		$child2->generate_fields();
	}
}
