<?php
namespace ProsCons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use ProsCons\Traits\VersusLineRenderTrait;
use ProsCons\Helpers\Elementor\WidgetHelper;
use ProsCons\Elementor\ControlsManager;
use ProsCons\Blocks\ProsCons_Customization_Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Versus Line Widget Class
 *
 * @package ProsCons\Elementor\Widgets
 */
class VersusLineWidget extends ElementorBase {

	use VersusLineRenderTrait;

	public function get_title(): string {
		return __( 'ProsCons Versus Line', 'prosandcons' );
	}

	public function get_icon(): string {
		return 'affx-icon-versus-line';
	}

	public function get_keywords() {
		return array(
			'Versus Line',
			'versus',
			'ProsCons',
		);
	}

	public function get_elements(): array {
		return array(
			'wrapper'       => 'affx-versus-table-wrap',
			'title'         => 'affx-versus-table-wrap .affx-versus-title',
			'vs-icon'       => 'affx-versus-table-wrap .affx-vs-icon',
			'table-odd-row' => 'affx-product-versus-table tbody tr:nth-child(odd) td',
		);
	}

	protected function register_controls() {
		$defaults = $this->get_fields();

		//
		// Content Tab
		//
		/**************************************************************
		 * Title Settings
		 */
		$this->start_controls_section(
			'affx_vl_title_settings',
			array(
				'label' => __( 'Title Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'versusTitleTag',
			array(
				'label'   => __( 'Title Tag', 'prosandcons' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'h2' => 'h2',
					'h3' => 'h3',
					'h4' => 'h4',
					'h5' => 'h5',
					'h6' => 'h6',
					'p'  => 'p',
				),
				'default' => $defaults['versusTitleTag'],
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Content Settings
		 */
		$this->start_controls_section(
			'affx_vl_content_settings',
			array(
				'label' => __( 'Content Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'vsLabel',
			array(
				'label'       => __( 'VS Label', 'prosandcons' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => $defaults['vsLabel'],
			)
		);

		$this->add_control(
			'versusTable',
			array(
				'label'       => __( 'Table Items', 'prosandcons' ),
				'type'        => Controls_Manager::REPEATER,
				'title_field' => '{{{ versusTitle }}}',
				'fields'      => array(
					array(
						'name'          => 'versusTitle',
						'label'         => __( 'Title', 'prosandcons' ),
						'type'          => ControlsManager::TEXT,
						'repeater_name' => 'versusTable',
						'default'       => __( 'Title', 'prosandcons' ),
					),
					array(
						'name'          => 'versusSubTitle',
						'label'         => __( 'Subtitle', 'prosandcons' ),
						'type'          => ControlsManager::TEXT,
						'repeater_name' => 'versusTable',
						'default'       => __( 'Subtitle', 'prosandcons' ),
					),
					array(
						'name'          => 'versusValue1',
						'label'         => __( 'Value 1', 'prosandcons' ),
						'type'          => ControlsManager::TEXT,
						'label_block'   => true,
						'repeater_name' => 'versusTable',
						'default'       => __( 'Value 1', 'prosandcons' ),
					),
					array(
						'name'          => 'versusValue2',
						'label'         => __( 'Value 2', 'prosandcons' ),
						'type'          => ControlsManager::TEXT,
						'repeater_name' => 'versusTable',
						'default'       => __( 'Value 2', 'prosandcons' ),
					),
				),
				'default'     => array(
					array(
						'versusTitle'    => __( 'Title', 'prosandcons' ),
						'versusSubTitle' => __( 'Subtitle', 'prosandcons' ),
						'versusValue1'   => __( 'Value 1', 'prosandcons' ),
						'versusValue2'   => __( 'Value 2', 'prosandcons' ),
					),
				),
			)
		);

		$this->end_controls_section();

		//
		// Style Tab
		//
		/**************************************************************
		 * Border settings
		 */
		$this->start_controls_section(
			'affx_vl_border_settings',
			array(
				'label' => __( 'Border Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'border',
				'label'          => __( 'Border', 'prosandcons' ),
				'responsive'     => true,
				'selector'       => $this->select_element( 'wrapper' ),
				'fields_options' => array(
					'border' => array(
						'default' => '',
					),
					'color'  => array(
						'default' => $defaults['border']['color']['color'],
					),
					'width'  => array(
						'default' => array(
							'isLinked' => true,
							'unit'     => 'px',
							'top'      => $defaults['border']['width'],
							'right'    => $defaults['border']['width'],
							'bottom'   => $defaults['border']['width'],
							'left'     => $defaults['border']['width'],
						),
					),
				),
			)
		);

		$this->add_responsive_control(
			'borderRadius',
			array(
				'label'      => __( 'Border Radius', 'prosandcons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'selectors'  => array(
					$this->select_element( 'wrapper' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'           => 'boxShadow',
				'selector'       => $this->select_element( 'wrapper' ),
				'label'          => __( 'Box Shadow', 'prosandcons' ),
				'fields_options' => array(
					'box_shadow_type' => array(
						'default' => '',
					),
					'box_shadow'      => array(
						'default' => array(
							'vertical'   => $defaults['boxShadow']['v_offset'],
							'horizontal' => $defaults['boxShadow']['h_offset'],
							'blur'       => $defaults['boxShadow']['blur'],
							'spread'     => $defaults['boxShadow']['spread'],
							'color'      => $defaults['boxShadow']['color']['color'],
							'inset'      => $defaults['boxShadow']['inset'],
						),
					),
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Color settings
		 */
		$this->start_controls_section(
			'affx_vl_color_settings',
			array(
				'label' => __( 'Color Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'vsTextColor',
			array(
				'label'     => __( 'VS Text Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $defaults['vsTextColor'],
				'selectors' => array(
					$this->select_element( 'vs-icon' ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'vsBgColor',
			array(
				'label'     => __( 'VS Background Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $defaults['vsBgColor'],
				'selectors' => array(
					$this->select_element( 'vs-icon' ) => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'contentColor',
			array(
				'label'     => __( 'Content Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => ProsCons_Customization_Helper::get_value( 'fontColor', $defaults['contentColor'] ),
				'selectors' => array(
					$this->select_elements( array( 'wrapper', 'title' ) ) => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'versusRowColor',
			array(
				'label'     => __( 'Row Background Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => $defaults['versusRowColor'],
				'selectors' => array(
					$this->select_element( 'table-odd-row' ) => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'bgColorSolid',
				'label'          => __( 'Background', 'prosandcons' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => $this->select_element( 'wrapper' ),
				'fields_options' => array(
					'background'     => array(
						'label'       => __( 'Background Type', 'prosandcons' ),
						'default'     => 'classic',
						'options'     => array(
							'classic'  => array(
								'title' => __( 'Color', 'prosandcons' ),
								'icon'  => 'eicon-global-colors',
							),
							'gradient' => array(
								'title' => __( 'Gradient', 'prosandcons' ),
								'icon'  => 'eicon-barcode',
							),
						),
						'toggle'      => false,
						'render_type' => 'template',
					),
					'color'          => array(
						'label'     => __( 'Background Color', 'prosandcons' ),
						'default'   => $defaults['bgColorSolid'],
						'condition' => array(
							'background' => 'classic',
						),
					),
					'color_b'        => array(
						'default' => '#A9B8C3',
					),
					'color_b_stop'   => array(
						'default' => array(
							'unit' => '%',
							'size' => 60,
						),
					),
					'gradient_angle' => array(
						'default' => array(
							'unit' => 'deg',
							'size' => '135',
						),
					),
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Typography settings
		 */
		$this->start_controls_section(
			'affx_vl_typography_settings',
			array(
				'label' => __( 'Typography Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'vsTypography',
				'label'          => __( 'VS Label Typography', 'prosandcons' ),
				'selector'       => $this->select_element( 'vs-icon' ),
				'fields_options' => array(
					'typography'  => array(
						'default' => 'yes',
					),
					'font_family' => array(
						'default' => ProsCons_Customization_Helper::get_value( 'typography.family', '' ),
					),
					'font_weight' => array(
						'default' => '400',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => '18',
						),
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'custom',
							'size' => 1.65,
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'versusTitleTypography',
				'label'          => __( 'Title Typography', 'prosandcons' ),
				'selector'       => $this->select_element( 'title' ),
				'fields_options' => array(
					'typography'  => array(
						'default' => 'yes',
					),
					'font_family' => array(
						'default' => ProsCons_Customization_Helper::get_value( 'typography.family', '' ),
					),
					'font_weight' => array(
						'default' => '400',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => '18',
						),
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'custom',
							'size' => 1.65,
						),
					),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'versusContentTypography',
				'label'          => __( 'Content Typography', 'prosandcons' ),
				'selector'       => $this->select_element( 'wrapper' ),
				'fields_options' => array(
					'typography'  => array(
						'default' => 'yes',
					),
					'font_family' => array(
						'default' => ProsCons_Customization_Helper::get_value( 'typography.family', '' ),
					),
					'font_weight' => array(
						'default' => '400',
					),
					'font_size'   => array(
						'default' => array(
							'unit' => 'px',
							'size' => '18',
						),
					),
					'line_height' => array(
						'default' => array(
							'unit' => 'custom',
							'size' => 1.65,
						),
					),
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Spacing
		 */
		$this->start_controls_section(
			'affx_vl_spacing_settings',
			array(
				'label' => __( 'Spacing Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'margin',
			array(
				'label'      => __( 'Margin', 'prosandcons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'selectors'  => array(
					$this->select_element( 'wrapper' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '30',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,

				),
			)
		);

		$this->add_responsive_control(
			'padding',
			array(
				'label'      => __( 'Padding', 'prosandcons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'selectors'  => array(
					$this->select_element( 'wrapper' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
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
							'name'         => 'versusTitle',
							'type'         => 'text',
							'repeaterName' => 'versusTable',
							'defaults'     => array(
								'versusTitle' => __( 'Title', 'prosandcons' ),
							),
						),
						'type'       => 'text',
					),
					array(
						'blockField' => array(
							'name'         => 'versusSubTitle',
							'type'         => 'text',
							'repeaterName' => 'versusTable',
							'defaults'     => array(
								'versusSubTitle' => __( 'Subtitle', 'prosandcons' ),
							),
						),
						'type'       => 'text',
					),
					array(
						'blockField' => array(
							'name'         => 'versusValue1',
							'type'         => 'text',
							'repeaterName' => 'versusTable',
							'defaults'     => array(
								'versusValue1' => '$145',
							),
						),
						'type'       => 'text',
					),
					array(
						'blockField' => array(
							'name'         => 'versusValue2',
							'type'         => 'text',
							'repeaterName' => 'versusTable',
							'defaults'     => array(
								'versusValue2' => '$195',
							),
						),
						'type'       => 'text',
					),
				),
			)
		);

		$this->end_controls_section();
	}
}
