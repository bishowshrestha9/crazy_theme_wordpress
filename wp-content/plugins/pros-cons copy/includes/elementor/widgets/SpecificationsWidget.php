<?php
namespace ProsCons\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use ProsCons\Helpers\Elementor\WidgetHelper;
use ProsCons\Traits\SpecificationsRenderTrait;
use ProsCons\Elementor\ControlsManager;
use ProsCons\Blocks\ProsCons_Customization_Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Specifications Widget Class
 *
 * @package ProsCons\Elementor\Widgets
 */
class SpecificationsWidget extends ElementorBase {

	use SpecificationsRenderTrait;

	public function get_title(): string {
		return __( 'ProsCons Specifications', 'prosandcons' );
	}

	public function get_icon(): string {
		return 'affx-icon-product-spec';
	}

	public function get_keywords() {
		return array(
			'specifications',
			'ProsCons',
		);
	}

	protected function register_controls() {
		//
		// Content
		//
		/**************************************************************
		 * Layout settings
		 */
		$this->start_controls_section(
			'affx_specifications_layout_settings',
			array(
				'label' => __( 'Layout Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'layoutStyle',
			array(
				'label'   => __( 'Layout Style', 'prosandcons' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'layout-1',
				'options' => array(
					'layout-1' => __( 'Layout 1', 'prosandcons' ),
					'layout-2' => __( 'Layout 2', 'prosandcons' ),
					'layout-3' => __( 'Layout 3', 'prosandcons' ),
				),
			)
		);

		$this->add_control(
			'specificationColumnWidth',
			array(
				'label'        => esc_html__( 'Table Column', 'prosandcons' ),
				'type'         => Controls_Manager::VISUAL_CHOICE,
				'label_block'  => true,
				'options'      => array(
					'style-one'   => array(
						'title' => esc_attr__( '33 | 66', 'prosandcons' ),
						'image' => PROSCONS_PLUGIN_URL . '/assets/icons/layout/33-66.svg',
					),
					'style-two'   => array(
						'title' => esc_attr__( '50 | 50', 'prosandcons' ),
						'image' => PROSCONS_PLUGIN_URL . '/assets/icons/layout/50-50.svg',
					),
					'style-three' => array(
						'title' => esc_attr__( '66 | 33', 'prosandcons' ),
						'image' => PROSCONS_PLUGIN_URL . '/assets/icons/layout/66-33.svg',
					),
				),
				'default'      => 'style-one',
				'columns'      => 3,
				'prefix_class' => 'col-width-',
				'render_type'  => 'template',
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Title Settings
		 */
		$this->start_controls_section(
			'affx_title_settings',
			array(
				'label' => __( 'Title Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'edSpecificationTitle',
			array(
				'label'        => __( 'Enable Title', 'prosandcons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'prosandcons' ),
				'label_off'    => __( 'No', 'prosandcons' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'specificationTitle',
			array(
				'label'   => __( 'Title', 'prosandcons' ),
				'type'    => ControlsManager::TEXT,
				'default' => __( 'Specifications', 'prosandcons' ),
			)
		);

		$this->add_control(
			'productTitleAlign',
			array(
				'label'     => __( 'Title Alignment', 'prosandcons' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
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
					'edSpecificationTitle' => 'true',
				),
				'toggle'    => false,
				'selectors' => array(
					$this->select_element( 'title' ) => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'specificationTitleTag',
			array(
				'label'     => __( 'Title Tag', 'prosandcons' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h2',
				'options'   => array(
					'h2' => __( 'Heading 2 (h2)', 'prosandcons' ),
					'h3' => __( 'Heading 3 (h3)', 'prosandcons' ),
					'h4' => __( 'Heading 4 (h4)', 'prosandcons' ),
					'h5' => __( 'Heading 5 (h5)', 'prosandcons' ),
					'h6' => __( 'Heading 6 (h6)', 'prosandcons' ),
					'p'  => __( 'Paragraph (p)', 'prosandcons' ),
				),
				'condition' => array(
					'edSpecificationTitle' => 'true',
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Specifications
		 */
		$this->start_controls_section(
			'affx_specifications_settings',
			array(
				'label' => __( 'Specifications Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'specificationTable',
			array(
				'label'       => __( 'Specifications', 'prosandcons' ),
				'type'        => Controls_Manager::REPEATER,
				'title_field' => '{{{ specificationLabel }}}',
				'fields'      => array(
					array(
						'name'          => 'specificationLabel',
						'label'         => __( 'Label', 'prosandcons' ),
						'type'          => ControlsManager::TEXT,
						'repeater_name' => 'specificationTable',
						'default'       => __( 'Specification Label', 'prosandcons' ),
					),
					array(
						'name'          => 'specificationValue',
						'label'         => __( 'Value', 'prosandcons' ),
						'type'          => ControlsManager::TEXTAREA,
						'repeater_name' => 'specificationTable',
						'default'       => __( 'Specification Value', 'prosandcons' ),
					),
				),
				'default'     => array(
					array(
						'specificationLabel' => __( 'Specification Label', 'prosandcons' ),
						'specificationValue' => __( 'Specification Value', 'prosandcons' ),
					),
				),
			)
		);

		$this->add_control(
			'specificationLabelAlign',
			array(
				'label'     => __( 'Label Alignment', 'prosandcons' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
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
				'toggle'    => false,
				'selectors' => array(
					$this->select_element( 'label' ) => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'specificationValueAlign',
			array(
				'label'     => __( 'Value Alignment', 'prosandcons' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'left',
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
				'toggle'    => false,
				'selectors' => array(
					$this->select_element( 'value' ) => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		//
		// Style tab
		//
		/**************************************************************
		 * Border Settings
		 */
		$this->start_controls_section(
			'affx_specifications_border_settings',
			array(
				'label' => __( 'Border Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'specificationBorder',
				'label'          => __( 'Border', 'prosandcons' ),
				'responsive'     => true,
				'selector'       => $this->select_element( 'container' ),
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
			'specificationBorderRadius',
			array(
				'label'      => __( 'Border Radius', 'prosandcons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->select_element( 'container' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'specificationBoxShadow',
				'selector' => $this->select_element( 'wrapper' ),
				'label'    => __( 'Box Shadow', 'prosandcons' ),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Colors
		 */
		$this->start_controls_section(
			'affx_specifications_colors',
			array(
				'label' => __( 'Colors', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'specificationTitleColor',
			array(
				'label'     => __( 'Title Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#292929',
				'selectors' => array(
					$this->select_element( 'title' ) => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'specificationTitleBgColor',
			array(
				'label'     => __( 'Title Background Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					$this->select_element( 'table-heading' ) => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'specificationLabelColor',
			array(
				'label'     => __( 'Label Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => array(
					$this->select_element( 'label' ) => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'specificationValueColor',
			array(
				'label'     => __( 'Value Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => ProsCons_Customization_Helper::get_value( 'fontColor', '#292929' ),
				'selectors' => array(
					$this->select_element( 'value' ) => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'specificationRowColor',
			array(
				'label'     => __( 'Table Row Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F5F7FA',
				'selectors' => array(
					$this->select_element( 'table' ) . '.layout-2 .affx-spec-label' => 'background: {{VALUE}};',
					$this->select_element( 'table' ) . '.layout-3 tbody tr:nth-child(2n) td' => 'background: {{VALUE}};',
				),
				'condition' => array(
					'layoutStyle' => array( 'layout-2', 'layout-3' ),
				),
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'specificationBgColorSolid',
				'label'          => __( 'Background', 'prosandcons' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => $this->select_element( 'table' ),
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
						'label'     => __( 'Background Color', 'prosandcons' ),
						'default'   => '#fff',
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
		 * Typography
		 */
		$this->start_controls_section(
			'affx_specifications_typography',
			array(
				'label' => __( 'Typography', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'specificationTitleTypography',
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
							'size' => '24',
						),
					),
					'line_height'     => array(
						'default' => array(
							'unit' => 'custom',
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
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'specificationLabelTypography',
				'label'          => __( 'Label Typography', 'prosandcons' ),
				'selector'       => $this->select_element( 'label' ),
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
							'size' => '18',
						),
					),
					'line_height'     => array(
						'default' => array(
							'unit' => 'custom',
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
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'specificationValueTypography',
				'label'          => __( 'Value Typography', 'prosandcons' ),
				'selector'       => $this->select_element( 'value' ),
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
							'size' => '18',
						),
					),
					'line_height'     => array(
						'default' => array(
							'unit' => 'custom',
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
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Spacing
		 */
		$this->start_controls_section(
			'affx_specifications_spacing',
			array(
				'label' => __( 'Spacing', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'specificationMargin',
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
			'specificationPadding',
			array(
				'label'      => __( 'Padding', 'prosandcons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'selectors'  => array(
					WidgetHelper::select_multiple_elements(
						array(
							$this->select_element( 'table-cell' ),
							$this->select_element( 'table-heading' ),
						)
					) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '16',
					'right'    => '24',
					'bottom'   => '16',
					'left'     => '24',
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
							'name'     => 'specificationTitle',
							'type'     => 'text',
							'defaults' => array(
								'specificationTitle' => __( 'Specifications', 'prosandcons' ),
							),
						),
						'type'       => 'text',
					),
					array(
						'blockField' => array(
							'name'         => 'specificationLabel',
							'type'         => 'text',
							'repeaterName' => 'specificationTable',
							'defaults'     => array(
								'specificationLabel' => __( 'Specification Label', 'prosandcons' ),
							),
						),
						'type'       => 'text',
					),
					array(
						'blockField' => array(
							'name'         => 'specificationValue',
							'type'         => 'text',
							'repeaterName' => 'specificationTable',
							'defaults'     => array(
								'specificationValue' => __( 'Specification Value', 'prosandcons' ),
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
