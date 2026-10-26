<?php
namespace ProsCons\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use ProsCons\Helpers\Elementor\WidgetHelper;
use ProsCons\Traits\ProductComparisonRenderTrait;
use ProsCons\Elementor\ControlsManager;
use ProsCons\Blocks\ProsCons_Customization_Helper;

/**
 * Product Comparison Widget Class
 *
 * @package ProsCons\Elementor\Widgets
 */
class ProductComparisonWidget extends ElementorBase {

	use ProductComparisonRenderTrait;

	public function get_title() {
		return __( 'ProsCons Product Comparison', 'prosandcons' );
	}

	public function get_icon() {
		return 'affx-icon-product-comparison';
	}

	public function get_keywords() {
		return array(
			'product',
			'comparison',
			'ProsCons',
		);
	}

	protected function register_controls() {
		//
		// CONENT TAB
		//
		/**************************************************************
		 * General Settings
		 */
		$this->start_controls_section(
			'affx_pc_general_settings',
			array(
				'label' => __( 'General Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'pcRibbon',
			array(
				'label'        => __( 'Enable Ribbon', 'prosandcons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'prosandcons' ),
				'label_off'    => __( 'No', 'prosandcons' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'pcImage',
			array(
				'label'        => __( 'Enable Images', 'prosandcons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'prosandcons' ),
				'label_off'    => __( 'No', 'prosandcons' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'pcTitle',
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
			'pcPrice',
			array(
				'label'        => __( 'Enable Prices', 'prosandcons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'prosandcons' ),
				'label_off'    => __( 'No', 'prosandcons' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'pcRating',
			array(
				'label'        => __( 'Enable Ratings', 'prosandcons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'prosandcons' ),
				'label_off'    => __( 'No', 'prosandcons' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'pcTitleColumn',
			array(
				'label'        => __( 'Enable Title Column', 'prosandcons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'matchCardHeights',
			array(
				'label'        => __( 'Match Card Heights', 'prosandcons' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'false',
				'description'  => __( 'Make all product cards the same height and align buttons at the bottom.', 'prosandcons' ),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Title Settings
		 */
		$this->start_controls_section(
			'affx_pc_title_settings',
			array(
				'label'     => __( 'Title Settings', 'prosandcons' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'pcTitle' => 'true',
				),
			)
		);

		$this->add_control(
			'pcTitleTag',
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
					'pcTitle' => 'true',
				),
			)
		);

		$this->add_control(
			'pcTitleAlign',
			array(
				'label'       => __( 'Title Alignment', 'prosandcons' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'left'   => array(
						'title' => __( 'Left', 'prosandcons' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'prosandcons' ),
						'icon'  => 'fa fa-align-center',
					),
					'right'  => array(
						'title' => __( 'Right', 'prosandcons' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'     => 'center',
				'toggle'      => false,
				'render_type' => 'template',
				'selectors'   => array(
					$this->select_element( 'product-title' ) => 'text-align: {{VALUE}};',
				),
				'condition'   => array(
					'pcTitle' => 'true',
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Button Settings
		 */
		$this->start_controls_section(
			'affx_button_settings_section',
			array(
				'label' => __( 'Button Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'pcButton',
			array(
				'label'        => __( 'Enable Button', 'prosandcons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'prosandcons' ),
				'label_off'    => __( 'No', 'prosandcons' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'pcButtonIcon',
			array(
				'label'        => __( 'Enable Icon', 'prosandcons' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'prosandcons' ),
				'label_off'    => __( 'No', 'prosandcons' ),
				'return_value' => 'true',
				'default'      => 'true',
				'condition'    => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_control(
			'buttonIcon',
			array(
				'label'     => __( 'Button Icon', 'prosandcons' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => array(
					'value'   => 'fas fa-angle-right',
					'library' => 'fa-solid',
				),
				'condition' => array(
					'pcButton'     => 'true',
					'pcButtonIcon' => 'true',
				),
			)
		);

		$this->add_control(
			'buttonIconAlign',
			array(
				'label'     => __( 'Icon Alignment', 'prosandcons' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'  => array(
						'title' => __( 'Left', 'prosandcons' ),
						'icon'  => 'fa fa-align-left',
					),
					'right' => array(
						'title' => __( 'Right', 'prosandcons' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'default'   => 'right',
				'toggle'    => false,
				'condition' => array(
					'pcButton'     => 'true',
					'pcButtonIcon' => 'true',
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Products
		 */
		$this->start_controls_section(
			'affx_pc_products_section',
			array(
				'label' => __( 'Products Settings', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'productComparisonTable',
			array(
				'label'       => __( 'Products', 'prosandcons' ),
				'type'        => Controls_Manager::REPEATER,
				'title_field' => '{{{ title }}}',
				'fields'      => array(
					array(
						'name'          => 'title',
						'label'         => __( 'Title', 'prosandcons' ),
						'type'          => ControlsManager::TEXT,
						'repeater_name' => 'productComparisonTable',
						'default'       => __( 'Product Title', 'prosandcons' ),
					),
					array(
						'name'    => 'productImageType',
						'label'   => __( 'Image Source', 'prosandcons' ),
						'type'    => Controls_Manager::CHOOSE,
						'default' => 'default',
						'options' => array(
							'default'  => array(
								'title' => __( 'Upload', 'prosandcons' ),
								'icon'  => 'eicon-kit-upload',
							),
							'external' => array(
								'title' => __( 'External', 'prosandcons' ),
								'icon'  => 'eicon-external-link-square',
							),
						),
						'toggle'  => false,
					),
					array(
						'name'      => 'imageUrl',
						'label'     => __( 'Image', 'prosandcons' ),
						'type'      => Controls_Manager::MEDIA,
						'default'   => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'condition' => array(
							'productImageType' => 'default',
						),
					),
					array(
						'name'          => 'productImageExternal',
						'label'         => __( 'External Image URL', 'prosandcons' ),
						'type'          => ControlsManager::TEXT,
						'repeater_name' => 'productComparisonTable',
						'condition'     => array(
							'productImageType' => 'external',
						),
					),
					array(
						'name'  => 'ribbonText',
						'label' => __( 'Ribbon Text', 'prosandcons' ),
						'type'  => Controls_Manager::TEXT,
					),
					array(
						'name'          => 'price',
						'label'         => __( 'Price', 'prosandcons' ),
						'type'          => ControlsManager::TEXT,
						'default'       => '$59.00',
						'repeater_name' => 'productComparisonTable',
					),
					array(
						'name'    => 'rating',
						'label'   => __( 'Rating', 'prosandcons' ),
						'type'    => Controls_Manager::SELECT,
						'default' => 4,
						'options' => array(
							1 => 1,
							2 => 2,
							3 => 3,
							4 => 4,
							5 => 5,
						),
					),
					array(
						'name'    => 'button',
						'label'   => __( 'Button Text', 'prosandcons' ),
						'type'    => Controls_Manager::TEXT,
						'default' => 'Buy Now',
					),
					array(
						'name'          => 'buttonURL',
						'label'         => __( 'Button URL', 'prosandcons' ),
						'type'          => ControlsManager::TEXT,
						'repeater_name' => 'productComparisonTable',
					),
					array(
						'name'         => 'btnOpenInNewTab',
						'label'        => esc_html__( 'Open Link In New Tab', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'false',
					),
					array(
						'name'         => 'btnRelNoFollow',
						'label'        => esc_html__( 'Add Rel="Nofollow"?', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'false',
					),
					array(
						'name'         => 'btnRelSponsored',
						'label'        => esc_html__( 'Add Rel="Sponsored"?', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'false',
					),
					array(
						'name'         => 'btnDownload',
						'label'        => esc_html__( 'Download Button', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => 'false',
					),
				),
				'default'     => array(
					array(
						'title'      => __( 'Product Title', 'prosandcons' ),
						'imageUrl'   => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'ribbonText' => 'Our Pick',
						'price'      => '$59.00',
						'rating'     => 4,
						'button'     => 'Buy Now',
						'buttonURL'  => '',
					),
					array(
						'title'      => __( 'Product Title', 'prosandcons' ),
						'imageUrl'   => array(
							'url' => Utils::get_placeholder_image_src(),
						),
						'ribbonText' => '',
						'price'      => '$59.00',
						'rating'     => 4,
						'button'     => 'Buy Now',
						'buttonURL'  => '',
					),
				),
			)
		);

		$specsRepeater = new \Elementor\Repeater();

		$specsRepeater->add_control(
			'spec',
			array(
				'label'               => __( 'Text', 'prosandcons' ),
				'type'                => ControlsManager::TEXT,
				'default'             => 'Specification',
				'repeater_name'       => 'comparisonSpecs',
				'inner_repeater_name' => 'inner_rows',
			)
		);

		$rowsRepeater = new \Elementor\Repeater();

		$rowsRepeater->add_control(
			'title',
			array(
				'label'   => __( 'Title', 'prosandcons' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => 'Title',
			)
		);

		$rowsRepeater->add_control(
			'inner_rows',
			array(
				'label'       => __( 'Specifications', 'prosandcons' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'title_field' => '{{{ spec }}}',
				'fields'      => $specsRepeater->get_controls(),
				'default'     => array(
					array(
						'spec' => __( 'Specification', 'prosandcons' ),
					),
					array(
						'spec' => __( 'Specification', 'prosandcons' ),
					),
				),
			)
		);

		// Add the outer repeater to the main control
		$this->add_control(
			'comparisonSpecs',
			array(
				'label'       => __( 'Comparision Rows', 'prosandcons' ),
				'type'        => \Elementor\Controls_Manager::REPEATER,
				'fields'      => $rowsRepeater->get_controls(),
				'title_field' => '{{ title }}',
				'classes'     => 'affx-nested-repeater',
				'default'     => array(
					array(
						'row_label'  => __( 'Title', 'prosandcons' ),
						'inner_rows' => array(
							array(
								'spec' => __( 'Specification', 'prosandcons' ),
							),
							array(
								'spec' => __( 'Specification', 'prosandcons' ),
							),
						),
					),
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
							'name'         => 'title',
							'type'         => 'text',
							'repeaterName' => 'productComparisonTable',
							'defaults'     => array(
								'title' => __( 'Product Title', 'prosandcons' ),
							),
						),
						'type'       => 'text',
					),
					array(
						'field'      => 'display_price',
						'blockField' => array(
							'name'         => 'price',
							'type'         => 'text',
							'repeaterName' => 'productComparisonTable',
							'defaults'     => array(
								'price' => '$59.00',
							),
							'conditions'   => array(
								'pcPrice' => 'true',
							),
						),
						'type'       => 'text',
					),
					array(
						'field'      => 'images',
						'blockField' => array(
							'name'         => 'productImageExternal',
							'type'         => 'image',
							'repeaterName' => 'productComparisonTable',
							'defaults'     => array(
								'productImageExternal' => '',
							),
							'conditions'   => array(
								'pcImage' => 'true',
							),
						),
						'type'       => 'image',
					),
					array(
						'field'      => 'url',
						'blockField' => array(
							'name'         => 'buttonURL',
							'type'         => 'link',
							'repeaterName' => 'productComparisonTable',
							'defaults'     => array(
								'buttonURL' => '',
							),
							'conditions'   => array(
								'pcButton' => 'true',
							),
						),
						'type'       => 'link',
					),
					array(
						'field'      => 'features',
						'blockField' => array(
							'name'              => 'spec',
							'type'              => 'text',
							'repeaterName'      => 'comparisonSpecs',
							'innerRepeaterName' => 'inner_rows',
							'defaults'          => array(
								'spec' => __( 'Specification', 'prosandcons' ),
							),
						),
						'type'       => 'text',
					),
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
			'affx_pc_border_section',
			array(
				'label' => __( 'Border', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'           => 'border',
				'label'          => __( 'Border', 'prosandcons' ),
				'responsive'     => true,
				'selector'       => WidgetHelper::select_multiple_elements(
					array(
						$this->select_element( 'container' ),
						$this->select_element( 'table-headings' ),
						$this->select_element( 'table-cells' ),
					)
				),
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
			'borderRadius',
			array(
				'label'      => __( 'Border Radius', 'prosandcons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->select_element( 'container' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'boxShadow',
				'selector' => $this->select_element( 'wrapper' ),
				'label'    => __( 'Box Shadow', 'prosandcons' ),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Color Settings Section
		 */
		$this->start_controls_section(
			'affx_pc_colors_section',
			array(
				'label' => __( 'Colors', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'ribbonColor',
			array(
				'label'     => __( 'Ribbon Background Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F13A3A',
				'selectors' => array(
					$this->select_element( 'product-ribbon' ) => 'background-color: {{VALUE}};',
					$this->select_element( 'product-ribbon' ) . '::before' => 'background-color: {{VALUE}};',
					$this->select_element( 'product-ribbon' ) . '::after' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'pcRibbon' => 'true',
				),
			)
		);

		$this->add_control(
			'ribbonTextColor',
			array(
				'label'     => __( 'Ribbon Text Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					$this->select_element( 'product-ribbon' ) => 'color: {{VALUE}};',
				),
				'condition' => array(
					'pcRibbon' => 'true',
				),
			)
		);

		$this->add_control(
			'titleColor',
			array(
				'label'     => __( 'Title Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#262B33',
				'selectors' => array(
					$this->select_element( 'product-title' ) => 'color: {{VALUE}};',
				),
				'condition' => array(
					'pcTitle' => 'true',
				),
			)
		);

		$this->add_control(
			'priceColor',
			array(
				'label'     => __( 'Price Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#262B33',
				'selectors' => array(
					$this->select_element( 'product-price' ) => 'color: {{VALUE}};',
				),
				'condition' => array(
					'pcPrice' => 'true',
				),
			)
		);

		$this->add_control(
			'starColor',
			array(
				'label'     => __( 'Star Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFB800',
				'condition' => array(
					'pcRating' => 'true',
				),
			)
		);

		$this->add_control(
			'starInactiveColor',
			array(
				'label'     => __( 'Inactive Star Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#A3ACBF',
				'condition' => array(
					'pcRating' => 'true',
				),
			)
		);

		$this->add_control(
			'tableRowBgColor',
			array(
				'label'     => __( 'Alternate Table Row Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#F5F7FA',
				'selectors' => array(
					$this->select_element( 'table-alternate-row' ) => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'contentColor',
			array(
				'label'     => __( 'Content Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => ProsCons_Customization_Helper::get_value( 'fontColor', '#292929' ),
				'selectors' => array(
					$this->select_element( 'product-content' ) => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'pcButtonColorsLabel',
			array(
				'label'     => esc_html__( 'Button', 'prosandcons' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'buttonTextColor',
			array(
				'label'     => __( 'Button Text Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					$this->select_element( 'button' ) => 'color: {{VALUE}};',
				),
				'condition' => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_control(
			'buttonTextHoverColor',
			array(
				'label'     => __( 'Button Text Hover Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#FFFFFF',
				'selectors' => array(
					$this->select_element( 'button:hover' ) => 'color: {{VALUE}};',
				),
				'condition' => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_control(
			'buttonBgColor',
			array(
				'label'     => __( 'Button Background Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => ProsCons_Customization_Helper::get_value( 'btnColor', '#00B0B0' ),
				'selectors' => array(
					$this->select_element( 'button' ) => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_control(
			'buttonBgHoverColor',
			array(
				'label'     => __( 'Button Background Hover Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => ProsCons_Customization_Helper::get_value( 'btnHoverColor', '#00454A' ),
				'selectors' => array(
					$this->select_element( 'button:hover' ) => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_control(
			'pcBackgroundLabel',
			array(
				'label'     => esc_html__( 'Background', 'prosandcons' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'           => 'bgColorSolid',
				'label'          => __( 'Background Color', 'prosandcons' ),
				'types'          => array( 'classic', 'gradient' ),
				'exclude'        => array( 'image' ),
				'selector'       => $this->select_element( 'container' ),
				'fields_options' => array(
					'background' => array(
						'default' => 'classic',
						'options' => array(
							'classic'  => array(
								'title' => esc_html__( 'Color', 'prosandcons' ),
								'icon'  => 'eicon-paint-brush',
							),
							'gradient' => array(
								'title' => esc_html__( 'Gradient', 'prosandcons' ),
								'icon'  => 'eicon-barcode',
							),
						),
					),
					'color'      => array(
						'default' => '#FFFFFF',
						'label'   => __( 'Background Color', 'prosandcons' ),
					),
					'gradient'   => array(
						'default' => 'linear-gradient(135deg,rgb(238,238,238) 0%,rgb(169,184,195) 100%)',
					),
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Typography Section
		 */
		$this->start_controls_section(
			'affx_pc_typography_section',
			array(
				'label' => __( 'Typography', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'titleTypography',
				'label'          => __( 'Title Typography', 'prosandcons' ),
				'selector'       => $this->select_element( 'product-title' ),
				'fields_options' => array(
					'typography'      => array(
						'default' => 'custom',
					),
					'font_family'     => array(
						'default' => ProsCons_Customization_Helper::get_value( 'typography.family', '' ),
					),
					'font_weight'     => array(
						'default' => '500',
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
				'condition'      => array(
					'pcTitle' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'ribbonTypography',
				'label'          => __( 'Ribbon Typography', 'prosandcons' ),
				'selector'       => $this->select_element( 'product-ribbon' ),
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
							'size' => '13',
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
				'condition'      => array(
					'pcRibbon' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'priceTypography',
				'label'          => __( 'Pricing Typography', 'prosandcons' ),
				'selector'       => $this->select_element( 'product-price' ),
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
					'font_style'      => array(
						'default' => 'normal',
					),
					'font_size'       => array(
						'default' => array(
							'unit' => 'px',
							'size' => '20',
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
				'condition'      => array(
					'pcPrice' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'buttonTypography',
				'label'          => __( 'Button Typography', 'prosandcons' ),
				'selector'       => $this->select_element( 'button' ),
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
							'size' => '16',
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
				'condition'      => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'           => 'contentTypography',
				'label'          => __( 'Content Typography', 'prosandcons' ),
				'selector'       => $this->select_element( 'product-table' ),
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
			'affx_pc_spacing_section',
			array(
				'label' => __( 'Spacing', 'prosandcons' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'imagePadding',
			array(
				'label'      => __( 'Image Padding', 'prosandcons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'selectors'  => array(
					$this->select_element( 'product-image' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,

				),
				'condition'  => array(
					'pcImage' => 'true',
				),
			)
		);

		$this->add_responsive_control(
			'margin',
			array(
				'label'      => __( 'Margin', 'prosandcons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
				'selectors'  => array(
					$this->select_element( 'container' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					WidgetHelper::select_multiple_elements(
						array(
							$this->select_element( 'table-headings' ),
							$this->select_element( 'table-cells' ),
						)
					) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '24',
					'right'    => '24',
					'bottom'   => '24',
					'left'     => '24',
					'unit'     => 'px',
					'isLinked' => false,
				),
			)
		);

		$this->end_controls_section();

		/**************************************************************
		 * Button Styling
		 */
		$this->start_controls_section(
			'affx_pc_button_style_section',
			array(
				'label'     => __( 'Button', 'prosandcons' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'       => 'buttonBorder',
				'label'      => __( 'Button Border', 'prosandcons' ),
				'responsive' => true,
				'selector'   => $this->select_element( 'button' ),
				'condition'  => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_control(
			'buttonBorderHoverColor',
			array(
				'label'     => __( 'Border Hover Color', 'prosandcons' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					$this->select_element( 'button:hover' ) => 'border-color: {{VALUE}};',
				),
				'condition' => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_responsive_control(
			'buttonRadius',
			array(
				'label'      => __( 'Border Radius', 'prosandcons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'pt' ),
				'selectors'  => array(
					$this->select_element( 'button' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,
				),
				'condition'  => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'      => 'buttonShadow',
				'label'     => __( 'Box Shadow', 'prosandcons' ),
				'selector'  => $this->select_element( 'button' ),
				'condition' => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_responsive_control(
			'buttonPadding',
			array(
				'label'      => __( 'Padding', 'prosandcons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'pt' ),
				'selectors'  => array(
					$this->select_element( 'button' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '10',
					'right'    => '10',
					'bottom'   => '10',
					'left'     => '10',
					'unit'     => 'px',
					'isLinked' => false,

				),
				'condition'  => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->add_responsive_control(
			'buttonMargin',
			array(
				'label'      => __( 'Margin', 'prosandcons' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					$this->select_element( 'button' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
				'default'    => array(
					'top'      => '0',
					'right'    => '0',
					'bottom'   => '0',
					'left'     => '0',
					'unit'     => 'px',
					'isLinked' => false,

				),
				'condition'  => array(
					'pcButton' => 'true',
				),
			)
		);

		$this->end_controls_section();
	}
}
