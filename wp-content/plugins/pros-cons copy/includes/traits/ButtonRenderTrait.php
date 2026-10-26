<?php

namespace ProsCons\Traits;

use ProsCons\Elementor\ControlsManager;
use ProsCons\Helpers\Elementor\WidgetHelper;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use ProsCons\Blocks\ProsCons_Customization_Helper;

/**
 * This trait is a channel for share rendering methods between Gutenberg and Elementor
 *
 * @package ProsCons
 */
trait ButtonRenderTrait {

	/**
	 * Get the button Elementor fields
	 *
	 * @return array
	 */
	protected function get_button_elementor_fields(): array {
		$defaults = $this->get_button_fields();

		$fields = array(
			'layout_settings'             => array(
				'label'  => __( 'Layout Settings', 'prosandcons' ),
				'tab'    => Controls_Manager::TAB_CONTENT,
				'fields' => array(
					'layoutStyle'      => array(
						'label'   => __( 'Layout Style', 'prosandcons' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'layout-type-1', // "Default Button" in Gutenberg
						'options' => array(
							'layout-type-1' => __( 'Default Button', 'prosandcons' ),
							'layout-type-2' => __( 'Price Button', 'prosandcons' ),
						),
					),
					'priceTagPosition' => array(
						'label'     => __( 'Price Tag Position', 'prosandcons' ),
						'type'      => Controls_Manager::SELECT,
						'default'   => 'tagBtnright', // same default as Gutenberg
						'options'   => array(
							'tagBtnright' => __( 'Right', 'prosandcons' ),
							'tagBtnleft'  => __( 'Left', 'prosandcons' ),
						),
						'condition' => array(
							'layoutStyle' => 'layout-type-2', // only for Price Button
						),
					),
					'productPrice'     => array(
						'label'         => __( 'Product Price', 'prosandcons' ),
						'type'          => ControlsManager::TEXT,
						'amazon_button' => true,
						'default'       => '$145',
						'condition'     => array(
							'layoutStyle' => 'layout-type-2', // only for Price Button
						),
					),
				),
			),
			'button_settings'             => array(
				'label'  => __( 'General Settings', 'prosandcons' ),
				'tab'    => Controls_Manager::TAB_CONTENT,
				'fields' => array(
					'button_label'       => array(
						'label'   => __( 'Button Label', 'prosandcons' ),
						'type'    => Controls_Manager::TEXT,
						'default' => 'Button',
					),
					'buttonLinkNotice'   => array(
						'type'       => \Elementor\Controls_Manager::ALERT,
						'alert_type' => 'warning',
						'content'    => esc_html__( 'Button link settings are disabled because the parent widget is set to be clickable. Configure the link in the parent widget\'s settings instead.', 'prosandcons' ),
					),
					'buttonURL'          => array(
						'label'         => __( 'Button URL', 'prosandcons' ),
						'label_block'   => true,
						'type'          => ControlsManager::TEXT,
						'placeholder'   => __( 'Paste URL or type to search', 'prosandcons' ),
						'input_type'    => 'url',
						'amazon_button' => true,
					),
					'btnRelNoFollow'     => array(
						'label'        => esc_html__( 'Add Rel="Nofollow"?', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'false',
					),
					'btnRelSponsored'    => array(
						'label'        => esc_html__( 'Add Rel="Sponsored"?', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'false',
					),
					'btnDownload'        => array(
						'label'        => esc_html__( 'Add Download Attribute', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'false',
					),
					'openInNewTab'       => array(
						'label'        => esc_html__( 'Open Link In New Window', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'false',
					),
					'buttonSize'         => array(
						'label'   => __( 'Size', 'prosandcons' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'medium',
						'options' => array(
							'small'  => __( 'Small', 'prosandcons' ),
							'medium' => __( 'Medium', 'prosandcons' ),
							'large'  => __( 'Large', 'prosandcons' ),
							'xlarge' => __( 'Extra Large', 'prosandcons' ),
						),
					),
					'buttonWidth'        => array(
						'label'   => __( 'Width', 'prosandcons' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 'flexible',
						'options' => array(
							'fixed'    => __( 'Fixed', 'prosandcons' ),
							'flexible' => __( 'Flexible', 'prosandcons' ),
							'full'     => __( 'Full Width', 'prosandcons' ),
						),
					),
					'button_fixed_width' => array(
						'label'      => __( 'Fixed Width', 'prosandcons' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => array( 'px' ),
						'range'      => array(
							'px' => array(
								'min'  => 50,
								'max'  => 500,
								'step' => 1,
							),
						),
						'default'    => array(
							'unit' => 'px',
							'size' => 200,
						),
						'condition'  => array(
							'buttonWidth!' => array( 'flexible', 'full' ),
						),
						'selectors'  => array(
							'{{WRAPPER}} .prosandcons-button' => 'width: {{SIZE}}{{UNIT}} !important;',
						),
					),
					'buttonAlignment'    => array(
						'label'     => __( 'Alignment', 'prosandcons' ),
						'type'      => Controls_Manager::CHOOSE,
						'toggle'    => true,
						'options'   => array(
							'flex-start' => array(
								'title' => __( 'Left', 'prosandcons' ),
								'icon'  => 'eicon-text-align-left',
							),
							'center'     => array(
								'title' => __( 'Center', 'prosandcons' ),
								'icon'  => 'eicon-text-align-center',
							),
							'flex-end'   => array(
								'title' => __( 'Right', 'prosandcons' ),
								'icon'  => 'eicon-text-align-right',
							),
						),
						'default'   => 'flex-start',
						'selectors' => array(
							'{{WRAPPER}} .affx-btn-inner' => 'justify-content: {{VALUE}};',
						),
					),
				),
			),
			'icon_settings'               => array(
				'label'  => __( 'Icon Settings', 'prosandcons' ),
				'tab'    => Controls_Manager::TAB_CONTENT,
				'fields' => array(
					'edButtonIcon' => array(
						'label'     => __( 'Enable Icon', 'prosandcons' ),
						'type'      => Controls_Manager::SWITCHER,
						'default'   => '',
						'label_on'  => __( 'Yes', 'prosandcons' ),
						'label_off' => __( 'No', 'prosandcons' ),
					),
					'ButtonIcon'   => array(
						'label'     => __( 'Icon', 'prosandcons' ),
						'type'      => Controls_Manager::ICONS,
						'default'   => array(
							'value'   => 'far fa-thumbs-up',
							'library' => 'regular',
						),
						'condition' => array(
							'edButtonIcon' => 'yes',
						),
					),
					'iconPosition' => array(
						'label'     => __( 'Icon Position', 'prosandcons' ),
						'type'      => Controls_Manager::SELECT,
						'default'   => 'axBtnleft',
						'options'   => array(
							'axBtnleft'  => __( 'Left', 'prosandcons' ),
							'axBtnright' => __( 'Right', 'prosandcons' ),
						),
						'condition' => array(
							'edButtonIcon' => 'yes',
						),
					),
					'iconSize'     => array(
						'label'      => __( 'Icon Size', 'prosandcons' ),
						'type'       => Controls_Manager::SLIDER,
						'size_units' => array( 'px' ),
						'range'      => array(
							'px' => array(
								'min'  => 8,
								'max'  => 50,
								'step' => 1,
							),
						),
						'default'    => array(
							'unit' => 'px',
							'size' => 18,
						),
						'condition'  => array(
							'edButtonIcon' => 'yes',
						),
						'selectors'  => array(
							'{{WRAPPER}} .prosandcons-button .button-icon' => 'font-size: {{SIZE}}{{UNIT}};',
						),
					),
				),
			),

			'border_settings_section'     => array(
				'label'  => __( 'Border', 'prosandcons' ),
				'tab'    => Controls_Manager::TAB_STYLE,
				'fields' => array(
					'buttonBorder'           => array(
						'label'      => __( 'Button Border', 'prosandcons' ),
						'type'       => Group_Control_Border::get_type(),
						'responsive' => true,
						'selector'   => '{{WRAPPER}} .affx-btn-inner a',
					),
					'buttonBorderHoverColor' => array(
						'label'     => __( 'Border Hover Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							'{{WRAPPER}} .affx-btn-inner a:hover' => 'border-color: {{VALUE}};',
						),
					),
					'buttonRadius'           => array(
						'label'      => __( 'Border Radius', 'prosandcons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'em' ),
						'default'    => array(
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '0',
							'left'     => '0',
							'unit'     => 'px',
							'isLinked' => true,
						),
						'selectors'  => array(
							'{{WRAPPER}} .affx-btn-inner a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							'{{WRAPPER}} .affx-btn-inner a .price-tag' => '--border-top-right-radius: {{RIGHT}}{{UNIT}}; --border-bottom-right-radius: {{BOTTOM}}{{UNIT}};',
						),
					),
					'buttonShadow'           => array(
						'label'    => __( 'Box Shadow', 'prosandcons' ),
						'type'     => Group_Control_Box_Shadow::get_type(),
						'selector' => '{{WRAPPER}} .affx-btn-inner a',
					),
				),
			),

			'color_settings_section'      => array(
				'label'  => __( 'Colors', 'prosandcons' ),
				'tab'    => Controls_Manager::TAB_STYLE,
				'fields' => array(
					'button_text_color'        => array(
						'label'     => __( 'Text Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'selectors' => array(
							'{{WRAPPER}} .prosandcons-button' => 'color: {{VALUE}};',
						),
					),
					'button_hover_text_color'  => array(
						'label'     => __( 'Text Hover Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'selectors' => array(
							'{{WRAPPER}} .prosandcons-button:hover' => 'color: {{VALUE}};',
						),
					),
					'button_background'        => array(
						'label'          => __( 'Background', 'prosandcons' ),
						'type'           => Group_Control_Background::get_type(),
						'types'          => array( 'classic', 'gradient' ),
						'exclude'        => array( 'image' ),
						'selector'       => '{{WRAPPER}} .prosandcons-button',
						'fields_options' => array(
							'background'     => array(
								'label'   => __( 'Background Type', 'prosandcons' ),
								'default' => 'classic',
								'options' => array(
									'classic'  => array(
										'title' => __( 'Color', 'prosandcons' ),
										'icon'  => 'eicon-global-colors',
									),
									'gradient' => array(
										'title' => __( 'Gradient', 'prosandcons' ),
										'icon'  => 'eicon-barcode',
									),
								),
								'toggle'  => false,
							),
							'color'          => array(
								'label'   => __( 'Background Color', 'prosandcons' ),
								'default' => ProsCons_Customization_Helper::get_value( 'btnColor', '#00B0B0' ),
							),
							'color_b'        => array(
								'default' => '#A9B8C3',
							),
							'color_b_stop'   => array(
								'default' => array(
									'unit' => '%',
									'size' => 100,
								),
							),
							'gradient_angle' => array(
								'default' => array(
									'unit' => 'deg',
									'size' => '135',
								),
							),
						),
					),
					'button_hover_background'  => array(
						'label'          => __( 'Background', 'prosandcons' ),
						'type'           => Group_Control_Background::get_type(),
						'types'          => array( 'classic', 'gradient' ),
						'exclude'        => array( 'image' ),
						'selector'       => '{{WRAPPER}} .prosandcons-button:hover',
						'fields_options' => array(
							'background'     => array(
								'label'   => __( 'Background Hover Type', 'prosandcons' ),
								'default' => 'classic',
								'options' => array(
									'classic'  => array(
										'title' => __( 'Color', 'prosandcons' ),
										'icon'  => 'eicon-global-colors',
									),
									'gradient' => array(
										'title' => __( 'Gradient', 'prosandcons' ),
										'icon'  => 'eicon-barcode',
									),
								),
								'toggle'  => false,
							),
							'color'          => array(
								'label'     => __( 'Background Hover Color', 'prosandcons' ),
								'default'   => ProsCons_Customization_Helper::get_value( 'btnHoverColor', '#00454A' ),
								'selectors' => array(
									'{{WRAPPER}} .prosandcons-button:hover' => 'background-color: {{VALUE}}; background-image: none;',
								),
							),
							'color_b'        => array(
								'default' => '#A9B8C3',
							),
							'color_b_stop'   => array(
								'default' => array(
									'unit' => '%',
									'size' => 100,
								),
							),
							'gradient_angle' => array(
								'default' => array(
									'unit' => 'deg',
									'size' => '135',
								),
							),
						),
					),
					'price_tag_colors_section' => array(
						'label'     => __( 'Price Tag Colors', 'prosandcons' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'before',
						'condition' => array(
							'layoutStyle' => 'layout-type-2',
						),
					),
					'priceTagTextColor'        => array(
						'label'     => __( 'Text Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#00B0B0',
						'condition' => array(
							'layoutStyle' => 'layout-type-2',
						),
						'selectors' => array(
							'{{WRAPPER}} .affx-btn-inner a .price-tag' => 'color: {{VALUE}} !important;',
						),
					),
					'priceTagBgColor'          => array(
						'label'     => __( 'Background Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#FFFFFF',
						'condition' => array(
							'layoutStyle' => 'layout-type-2',
						),
						'selectors' => array(
							'{{WRAPPER}} .affx-btn-inner a .price-tag'        => 'background-color: {{VALUE}} !important;',
							'{{WRAPPER}} .affx-btn-inner a .price-tag:before' => 'background-color: {{VALUE}} !important;',
						),
					),
				),
			),

			'typography_settings_section' => array(
				'label'  => __( 'Typography', 'prosandcons' ),
				'tab'    => Controls_Manager::TAB_STYLE,
				'fields' => array(
					'button_typography' => array(
						'label'          => __( 'Typography', 'prosandcons' ),
						'selector'       => '{{WRAPPER}} .prosandcons-button',
						'type'           => Group_Control_Typography::get_type(),
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
									'size' => 1.65,
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
					),
				),
			),

			'spacing_settings_section'    => array(
				'label'  => __( 'Spacing', 'prosandcons' ),
				'tab'    => Controls_Manager::TAB_STYLE,
				'fields' => array(
					'buttonPadding'    => array(
						'label'      => __( 'Padding', 'prosandcons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', 'em', '%' ),
						'selectors'  => array(
							'{{WRAPPER}} .prosandcons-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					),
					'buttonMargin'     => array(
						'label'      => __( 'Margin', 'prosandcons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', 'em', '%' ),
						'selectors'  => array(
							'{{WRAPPER}} .prosandcons-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'default'    => array(
							'unit'     => 'px',
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '30',
							'left'     => '0',
							'isLinked' => false,
						),
					),
					'amazonAttributes' => array(
						'type'    => Controls_Manager::HIDDEN,
						'default' => array(
							array(
								'field'      => 'display_price',
								'blockField' => array(
									'name'         => 'productPrice',
									'type'         => 'text',
									'format'       => 'price',
									'typeSelector' => 'layoutStyle',
									'defaults'     => array(
										'productPrice' => $defaults['productPrice'],
									),
									'conditions'   => array(
										'layoutStyle' => 'layout-type-2',
									),
								),
								'type'       => 'text',
							),
							array(
								'field'      => 'url',
								'blockField' => array(
									'name'     => 'buttonURL',
									'type'     => 'link',
									'defaults' => array(
										'buttonURL' => $defaults['buttonURL'],
									),
								),
								'type'       => 'link',
							),
						),
					),
				),
			),
		);

		return $fields;
	}

	/**
	 * Generate button class names based on attributes
	 *
	 * @param array $attributes Block attributes.
	 */
	protected function get_button_classes( $attributes ) {
		$classNames = array(
			'prosandcons-button',
			'btn-align-' . ( $attributes['buttonAlignment'] ?? 'flex-start' ),
			'btn-is-' . ( $attributes['buttonSize'] ?? 'medium' ),
			$attributes['buttonWidth'] === 'fixed' ? 'btn-is-fixed' : '',
			$attributes['buttonWidth'] === 'full' ? 'btn-is-fullw' : '',
			$attributes['buttonWidth'] === 'flexible' ? 'btn-is-flex-' . $attributes['buttonSize'] : '',
			$attributes['layoutStyle'] === 'layout-type-2' && $attributes['priceTagPosition'] === 'tagBtnleft' ? 'left-price-tag' : '',
			$attributes['layoutStyle'] === 'layout-type-2' && $attributes['priceTagPosition'] === 'tagBtnright' ? 'right-price-tag' : '',
			$attributes['edButtonIcon'] && $attributes['iconPosition'] === 'axBtnright' ? 'icon-right' : 'icon-left',
		);

		return array_filter( $classNames );
	}

	/**
	 * Prepare attributes for template
	 *
	 * @param array $attributes Block attributes.
	 */
	protected function prepare_template_attributes( $attributes ) {
		// Create wrapper attributes differently based on context
		if ( isset( $attributes['elementorLinkAttributes'] ) ) {
			// Elementor context
			$wrapper_attributes = sprintf(
				'class="affx-btn-wrapper" id="prosandcons-blocks-style-%s"',
				esc_attr( $attributes['block_id'] )
			);
		} else {
			// Gutenberg context
			$wrapper_attributes = get_block_wrapper_attributes(
				array(
					'class' => 'affx-btn-wrapper',
					'id'    => "prosandcons-blocks-style-{$attributes['block_id']}",
				)
			);

			$attributes['classNames'] = implode( ' ', $this->get_button_classes( $attributes ) );

			// Prepare Gutenberg-specific attributes.
			$rel = array( 'noopener' );

			if ( ! empty( $attributes['btnRelNoFollow'] ) ) {
				$rel[] = 'nofollow';
			}

			if ( ! empty( $attributes['btnRelSponsored'] ) ) {
				$rel[] = 'sponsored';
			}

			$attributes['rel'] = implode( ' ', $rel );

			$attributes['target']   = ! empty( $attributes['openInNewTab'] ) ? ' target="_blank"' : '';
			$attributes['download'] = ! empty( $attributes['btnDownload'] );

		}

		// Prepare icon HTML
		$attributes['iconLeft'] = ! empty( $attributes['edButtonIcon'] ) && $attributes['iconPosition'] === 'axBtnleft'
			? '<i class="button-icon ' . esc_attr( $attributes['ButtonIcon']['value'] ) . '"></i>'
			: '';

		$attributes['iconRight'] = ! empty( $attributes['edButtonIcon'] ) && $attributes['iconPosition'] === 'axBtnright'
			? '<i class="button-icon ' . esc_attr( $attributes['ButtonIcon']['value'] ) . '"></i>'
			: '';

		return array_merge( array( 'wrapper_attributes' => $wrapper_attributes ), $attributes );
	}

	protected function get_button_fields(): array {
		return array(
			'buttonLabel'      => 'Button',
			'buttonSize'       => 'medium',
			'buttonWidth'      => 'flexible',
			'buttonURL'        => '',
			'iconPosition'     => 'left',
			'block_id'         => '',
			'ButtonIcon'       => array(
				'name'  => 'thumb-up-simple',
				'value' => 'far fa-thumbs-up',
			),
			'edButtonIcon'     => false,
			'btnRelSponsored'  => false,
			'openInNewTab'     => false,
			'btnRelNoFollow'   => false,
			'buttonAlignment'  => 'flex-start',
			'btnDownload'      => false,
			'layoutStyle'      => 'layout-type-1',
			'priceTagPosition' => 'tagBtnright',
			'productPrice'     => '$145',
		);
	}

	/**
	 * Render function for Elementor
	 *
	 * @param array $settings
	 * @return void
	 */
	public function render_button( array $settings ): void {
		// Merge defaults with actual settings
		$settings = wp_parse_args( $settings, $this->get_button_fields() );
		$settings = WidgetHelper::process_attributes( $settings );

		// Create unique button ID to prevent conflicts when multiple buttons are rendered
		$button_id = 'button-' . ( $settings['name'] ?? '' ) . '-' . $this->get_id() . '-' . uniqid();

		// Build all link attributes using Elementor's helper
		if ( isset( $settings['button_url'] ) ) {
			$this->add_link_attributes( $button_id, $settings['button_url'] );
		} elseif ( isset( $settings['buttonURL'] ) && ! empty( $settings['buttonURL'] ) ) {
			$this->add_render_attribute( $button_id, 'href', apply_filters( 'prosandcons_button_url', esc_url( do_shortcode( $settings['buttonURL'] ) ) ) );
		}

		// Add classes using the shared method
		$this->add_render_attribute( $button_id, 'class', $this->get_button_classes( $settings ) );

		// Add rel attributes if enabled.
		$rel = array();

		if ( isset( $settings['btnRelNoFollow'] ) && $settings['btnRelNoFollow'] ) {
			$rel[] = 'nofollow';
		}

		if ( isset( $settings['btnRelSponsored'] ) && $settings['btnRelSponsored'] ) {
			$rel[] = 'sponsored';
		}

		$this->add_render_attribute( $button_id, 'rel', implode( ' ', $rel ) );

		// Add _target attribute if open in new tab enabled.
		if ( isset( $settings['openInNewTab'] ) && $settings['openInNewTab'] ) {
			$this->add_render_attribute( $button_id, 'target', '_blank' );
		}

		// Add download attribute if enabled.
		if ( isset( $settings['btnDownload'] ) && $settings['btnDownload'] ) {
			$this->add_render_attribute( $button_id, 'download', '' );
		}

		// Convert Elementor icon to our format
		$icon_data = array(
			'name'  => 'thumb-up-simple',
			'value' => '',
		);

		if ( ! empty( $settings['ButtonIcon'] ) && ! empty( $settings['ButtonIcon']['value'] ) ) {
			$icon_data = array(
				'name'  => str_replace( array( 'fas ', 'far ', 'fab ', 'fa-' ), '', $settings['ButtonIcon']['value'] ),
				'value' => $settings['ButtonIcon']['value'],
			);
		}

		$attributes = array(
			'buttonLabel'             => $settings['button_label'] ?? $settings['buttonLabel'],
			'buttonSize'              => $settings['buttonSize'],
			'buttonWidth'             => $settings['buttonWidth'],
			'buttonFixedWidth'        => isset( $settings['button_fixed_width'] ) ? (int) $settings['button_fixed_width'] : 0,
			'buttonAlignment'         => $settings['buttonAlignment'],
			'layoutStyle'             => $settings['layoutStyle'],
			'priceTagPosition'        => $settings['priceTagPosition'],
			'productPrice'            => $settings['productPrice'],
			'edButtonIcon'            => ( $settings['edButtonIcon'] === 'yes' ),
			'ButtonIcon'              => $icon_data,
			'iconPosition'            => $settings['iconPosition'],
			'elementorLinkAttributes' => $this->get_render_attribute_string( $button_id ),
			'block_id'                => 'elementor-' . $this->get_id(),
			'parent_attributes'       => $settings['parent_attributes'] ?? array(),
		);

		echo wp_kses_post( $this->render_button_template( $attributes ) );
	}

	/**
	 * Main template rendering function, can be used by Gutenberg directly
	 *
	 * @param array $attributes
	 * @return string
	 */
	public function render_button_template( array $attributes ): string {
		$template_attributes = $this->prepare_template_attributes( $attributes );
		extract( $template_attributes );

		$parent_block_clickable = isset( $attributes['parent_attributes']['edFullBlockLink'] ) ? $attributes['parent_attributes']['edFullBlockLink'] : false;

		$tag = $parent_block_clickable ? 'div' : 'a';

		// Determine block type for analytics tracking
		$block_type   = $attributes['parent_attributes']['blockName'] ?? 'buttons';
		$resolved_url = do_shortcode( $buttonURL ?? '' );
		$track_attrs  = \ProsCons\Analytics\AnalyticsHelper::get_tracking_attributes( $resolved_url, $block_type, 'button' );

		if ( isset( $elementorLinkAttributes ) ) {
			$link_attributes = $elementorLinkAttributes . ' ' . $track_attrs;
		} elseif ( $tag === 'a' ) {
			$link_attributes = sprintf(
				'href="%s" class="%s" rel="%s" %s %s %s',
				apply_filters( 'prosandcons_button_url', esc_url( $resolved_url ) ),
				esc_attr( $classNames ),
				esc_attr( $rel ),
				esc_html( $target ),
				esc_html( $download ? ' download' : '' ),
				$track_attrs
			);
		} else {
			$link_attributes = sprintf(
				'class="%s"',
				esc_attr( $classNames )
			);
		}

		ob_start();
		include PROSCONS_PLUGIN_DIR . '/templates/blocks/buttons.php';

		return ob_get_clean();
	}
}
