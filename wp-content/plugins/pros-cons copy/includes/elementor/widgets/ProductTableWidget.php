<?php

namespace ProsCons\Elementor\Widgets;

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use ProsCons\Traits\ButtonRenderTrait;
use ProsCons\Helpers\ProsCons_Helpers;
use ProsCons\Helpers\Elementor\WidgetHelper;
use ProsCons\Traits\ProductTableRenderTrait;
use Elementor\Utils;
use ProsCons\Elementor\ControlsManager;
use ProsCons\Blocks\ProsCons_Customization_Helper;

/**
 * Product Table Widget Class
 *
 * @package ProsCons\Elementor\Widgets
 */
class ProductTableWidget extends ElementorBase {

	use ProductTableRenderTrait;
	use ButtonRenderTrait;

	protected function get_child_slugs(): array {
		return array( 'buttons' );
	}

	public function get_title() {
		return __( 'ProsCons Product Table', 'prosandcons' );
	}

	public function get_icon() {
		return 'affx-icon-product-table';
	}

	public function get_keywords() {
		return array(
			'product',
			'table',
			'ProsCons',
		);
	}

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
			'button_label'            => 'More Details',
			'button_background_color' => '#FFB800',
			'buttonMargin'            => array(
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
			'wrapper'          => 'affx-pdt-table-wrapper',
			'title'            => 'affx-pdt-table-wrapper .affx-pdt-name',
			'button'           => 'affx-pdt-table-wrapper .prosandcons-button',
			'primary-button'   => 'affx-pdt-table-wrapper .prosandcons-button.primary',
			'secondary-button' => 'affx-pdt-table-wrapper .prosandcons-button.secondary',
			'image'            => 'image-wrapper',
			'star-rating'      => 'star-rating-single-wrap',
			'circle-rating'    => 'affx-circle-progress-container .affx-circle-inside',
			'table-single'     => 'affx-pdt-table-single',
			'price'            => 'affx-pdt-table-wrapper .affx-pdt-price-wrap',
			'image-container'  => 'affx-pdt-table-wrapper .affx-pdt-img-container',
		);
	}

	public function get_elementor_controls( $params = array() ) {
		$defaults = $this->get_fields();

		return array(
			'layout_settings_section'           => array(
				'label'  => __( 'Layout Settings', 'prosandcons' ),
				'tab'    => Controls_Manager::TAB_CONTENT,
				'fields' => array(
					'layoutStyle' => array(
						'label'   => __( 'Choose Layout', 'prosandcons' ),
						'type'    => Controls_Manager::SELECT,
						'default' => $defaults['layoutStyle'],
						'options' => array(
							'layoutOne'   => __( 'Layout One', 'prosandcons' ),
							'layoutTwo'   => __( 'Layout Two', 'prosandcons' ),
							'layoutThree' => __( 'Layout Three', 'prosandcons' ),
						),
					),
				),
			),

			'general_settings_section'          => array(
				'label'  => __( 'General Settings', 'prosandcons' ),
				'tab'    => Controls_Manager::TAB_CONTENT,
				'fields' => array(
					'edImage'       => array(
						'label'        => __( 'Enable Image', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => $defaults['edImage'] ? 'true' : 'false',
					),
					'edRibbon'      => array(
						'label'        => __( 'Enable Ribbon', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => $defaults['edRibbon'] ? 'true' : 'false',
					),
					'edProductName' => array(
						'label'        => __( 'Enable Product Name', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => $defaults['edProductName'] ? 'true' : 'false',
					),
					'edRating'      => array(
						'label'        => __( 'Enable Rating', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => $defaults['edRating'] ? 'true' : 'false',
					),
					'edPrice'       => array(
						'label'        => __( 'Enable Price', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => $defaults['edPrice'] ? 'true' : 'false',
					),
					'edCounter'     => array(
						'label'        => __( 'Enable Counter', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => $defaults['edCounter'] ? 'true' : 'false',
					),
				),
			),

			'product_name_settings_section'     => array(
				'label'     => __( 'Title Settings', 'prosandcons' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => array(
					'edProductName' => 'true',
				),
				'fields'    => array(
					'imageColTitle'    => array(
						'label'     => esc_html__( 'Image Column Title', 'prosandcons' ),
						'type'      => Controls_Manager::TEXT,
						'default'   => $defaults['imageColTitle'],
						'condition' => array(
							'edImage' => 'true',
						),
					),
					'productColTitle'  => array(
						'label'     => esc_html__( 'Product Column Title', 'prosandcons' ),
						'type'      => Controls_Manager::TEXT,
						'default'   => $defaults['productColTitle'],
						'condition' => array(
							'edProductName' => 'true',
						),
					),
					'featuresColTitle' => array(
						'label'   => esc_html__( 'Features Column Title', 'prosandcons' ),
						'type'    => Controls_Manager::TEXT,
						'default' => $defaults['featuresColTitle'],
					),
					'ratingColTitle'   => array(
						'label'     => esc_html__( 'Rating Column Title', 'prosandcons' ),
						'type'      => Controls_Manager::TEXT,
						'default'   => $defaults['ratingColTitle'],
						'condition' => array(
							'edRating' => 'true',
						),
					),
					'priceColTitle'    => array(
						'label'   => esc_html__( 'Price Column Title', 'prosandcons' ),
						'type'    => Controls_Manager::TEXT,
						'default' => $defaults['priceColTitle'],
					),
					'productNameTag'   => array(
						'label'     => __( 'Product Name Tag', 'prosandcons' ),
						'type'      => Controls_Manager::SELECT,
						'options'   => array(
							'h2' => 'H2',
							'h3' => 'H3',
							'h4' => 'H4',
							'h5' => 'H5',
							'h6' => 'H6',
							'p'  => 'Paragraph (p)',
						),
						'default'   => $defaults['productNameTag'],
						'condition' => array(
							'edProductName' => 'true',
						),
					),
				),
			),

			'product_settings_section'          => array(
				'label'  => __( 'Products Settings', 'prosandcons' ),
				'tab'    => Controls_Manager::TAB_CONTENT,
				'fields' => array(
					'productContentType' => array(
						'label'   => __( 'Content Type', 'prosandcons' ),
						'type'    => Controls_Manager::CHOOSE,
						'options' => array(
							'paragraph' => array(
								'title' => __( 'Paragraph', 'prosandcons' ),
								'icon'  => 'eicon-editor-paragraph',
							),
							'list'      => array(
								'title' => __( 'List', 'prosandcons' ),
								'icon'  => 'eicon-editor-list-ul',
							),
						),
						'default' => $defaults['productContentType'],
						'toggle'  => false,
					),
					'productTable'       => array(
						'label'       => __( 'Products List', 'prosandcons' ),
						'type'        => Controls_Manager::REPEATER,
						'title_field' => '{{{ name }}}',
						'fields'      => array(
							'imageType'                    => array(
								'name'    => 'imageType',
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
							'imageUrl'                     => array(
								'name'        => 'imageUrl',
								'label'       => __( 'Image', 'prosandcons' ),
								'type'        => Controls_Manager::MEDIA,
								'render_type' => 'template',
								'default'     => array(
									'url' => Utils::get_placeholder_image_src(),
								),
								'condition'   => array(
									'imageType' => 'default',
								),
							),
							'imageExternal'                => array(
								'name'          => 'imageExternal',
								'label'         => __( 'External Image URL', 'prosandcons' ),
								'type'          => ControlsManager::TEXT,
								'repeater_name' => 'productTable',
								'condition'     => array(
									'imageType' => 'external',
								),
							),
							'ribbon'                       => array(
								'name'    => 'ribbon',
								'label'   => __( 'Ribbon Text', 'prosandcons' ),
								'type'    => Controls_Manager::TEXT,
								'default' => '',
							),
							'name'                         => array(
								'name'          => 'name',
								'label'         => __( 'Product Name', 'prosandcons' ),
								'type'          => ControlsManager::TEXT,
								'default'       => $defaults['productTable'][0]['name'],
								'repeater_name' => 'productTable',
							),
							'features'                     => array(
								'name'          => 'features',
								'label'         => __( 'Product Features', 'prosandcons' ),
								'type'          => Controls_Manager::TEXTAREA,
								'default'       => $defaults['productTable'][0]['features'],
								'repeater_name' => 'productTable',
							),
							'featuresListType'             => array(
								'name'    => 'featuresListType',
								'label'   => esc_html__( 'Features List Type', 'prosandcons' ),
								'type'    => Controls_Manager::CHOOSE,
								'options' => array(
									'list'   => array(
										'title' => esc_html__( 'List', 'prosandcons' ),
										'icon'  => 'eicon-editor-list-ul',
									),
									'amazon' => array(
										'title' => esc_html__( 'Amazon', 'prosandcons' ),
										'icon'  => 'fa-brands fa-amazon',
									),
								),
								'default' => 'list',
								'toggle'  => false,
							),
							'featuresList'                 => array(
								'name'        => 'featuresList',
								'label'       => __( 'Features List', 'prosandcons' ),
								'type'        => Controls_Manager::REPEATER,
								'fields'      => array(
									'_id'     => array(
										'name'    => '_id',
										'type'    => Controls_Manager::HIDDEN,
										'default' => '',
									),

									'content' => array(
										'name'    => 'content',
										'type'    => Controls_Manager::TEXT,
										'label'   => __( 'List Item', 'prosandcons' ),
										'default' => 'Enter new item',
									),
								),
								'title_field' => '{{ content }}',
								'classes'     => 'affx-nested-repeater',
								'default'     => array(
									array(
										'content' => 'Enter new item',
									),
								),
								'condition'   => array(
									'featuresListType' => 'list',
								),
							),
							'featuresListAmazon'           => array(
								'name'          => 'featuresListAmazon',
								'label'         => esc_html__( 'Amazon Features List', 'prosandcons' ),
								'type'          => ControlsManager::TEXT,
								'disabled'      => true,
								'placeholder'   => __( 'Click on the button to connect product', 'prosandcons' ),
								'repeater_name' => 'productTable',
								'condition'     => array(
									'featuresListType' => 'amazon',
								),
							),
							'rating'                       => array(
								'name'    => 'rating',
								'label'   => __( 'Rating', 'prosandcons' ),
								'type'    => Controls_Manager::NUMBER,
								'default' => $defaults['productTable'][0]['rating'],
								'min'     => 1,
								'max'     => 10,
							),
							'offerPrice'                   => array(
								'name'          => 'offerPrice',
								'label'         => __( 'Offer Price', 'prosandcons' ),
								'type'          => ControlsManager::TEXT,
								'default'       => $defaults['productTable'][0]['offerPrice'],
								'repeater_name' => 'productTable',
							),
							'regularPrice'                 => array(
								'name'          => 'regularPrice',
								'label'         => __( 'Regular Price', 'prosandcons' ),
								'type'          => ControlsManager::TEXT,
								'default'       => $defaults['productTable'][0]['regularPrice'],
								'repeater_name' => 'productTable',
							),
							'primaryButtonRepeaterLabel'   => array(
								'name'      => 'primaryButtonRepeaterLabel',
								'label'     => esc_html__( 'Primary Button', 'prosandcons' ),
								'type'      => Controls_Manager::HEADING,
								'separator' => 'after',
							),
							'button1'                      => array(
								'name'      => 'button1',
								'label'     => __( 'Button 1 Text', 'prosandcons' ),
								'type'      => Controls_Manager::TEXT,
								'default'   => $defaults['productTable'][0]['button1'],
								'separator' => 'before',
							),
							'button1URL'                   => array(
								'name'          => 'button1URL',
								'label'         => __( 'Button 1 URL', 'prosandcons' ),
								'type'          => ControlsManager::TEXT,
								'repeater_name' => 'productTable',
								'placeholder'   => 'https://example.com',
							),
							'btn1RelNoFollow'              => array(
								'name'        => 'btn1RelNoFollow',
								'label'       => __( 'Button 1 Rel NoFollow', 'prosandcons' ),
								'type'        => Controls_Manager::SWITCHER,
								'return_type' => 'true',
								'default'     => $defaults['productTable'][0]['btn1RelNoFollow'] ? 'true' : 'false',
							),
							'btn1RelSponsored'             => array(
								'name'        => 'btn1RelSponsored',
								'label'       => __( 'Button 1 Rel Sponsored', 'prosandcons' ),
								'type'        => Controls_Manager::SWITCHER,
								'return_type' => 'true',
								'default'     => $defaults['productTable'][0]['btn1RelSponsored'] ? 'true' : 'false',
							),
							'btn1OpenInNewTab'             => array(
								'name'        => 'btn1OpenInNewTab',
								'label'       => __( 'Button 1 Open in New Tab', 'prosandcons' ),
								'type'        => Controls_Manager::SWITCHER,
								'return_type' => 'true',
								'default'     => $defaults['productTable'][0]['btn1OpenInNewTab'] ? 'true' : 'false',
							),
							'btn1Download'                 => array(
								'name'        => 'btn1Download',
								'label'       => __( 'Button 1 Download', 'prosandcons' ),
								'type'        => Controls_Manager::SWITCHER,
								'return_type' => 'true',
								'default'     => $defaults['productTable'][0]['btn1Download'] ? 'true' : 'false',
							),
							'secondaryButtonRepeaterLabel' => array(
								'name'      => 'secondaryButtonRepeaterLabel',
								'label'     => esc_html__( 'Secondary Button', 'prosandcons' ),
								'type'      => Controls_Manager::HEADING,
								'separator' => 'after',
							),
							'button2'                      => array(
								'name'    => 'button2',
								'label'   => __( 'Button 2 Text', 'prosandcons' ),
								'type'    => Controls_Manager::TEXT,
								'default' => $defaults['productTable'][0]['button2'],
							),
							'button2URL'                   => array(
								'name'          => 'button2URL',
								'label'         => __( 'Button 2 URL', 'prosandcons' ),
								'type'          => ControlsManager::TEXT,
								'repeater_name' => 'productTable',
								'placeholder'   => 'https://www.example.com',
							),
							'btn2RelNoFollow'              => array(
								'name'        => 'btn2RelNoFollow',
								'label'       => __( 'Button 2 Rel NoFollow', 'prosandcons' ),
								'type'        => Controls_Manager::SWITCHER,
								'return_type' => 'true',
								'default'     => $defaults['productTable'][0]['btn2RelNoFollow'] ? 'true' : 'false',
							),
							'btn2RelSponsored'             => array(
								'name'        => 'btn2RelSponsored',
								'label'       => __( 'Button 2 Rel Sponsored', 'prosandcons' ),
								'type'        => Controls_Manager::SWITCHER,
								'return_type' => 'true',
								'default'     => $defaults['productTable'][0]['btn2RelSponsored'] ? 'true' : 'false',
							),
							'btn2OpenInNewTab'             => array(
								'name'        => 'btn2OpenInNewTab',
								'label'       => __( 'Button 2 Open in New Tab', 'prosandcons' ),
								'type'        => Controls_Manager::SWITCHER,
								'return_type' => 'true',
								'default'     => $defaults['productTable'][0]['btn2OpenInNewTab'] ? 'true' : 'false',
							),
							'btn2Download'                 => array(
								'name'        => 'btn2Download',
								'label'       => __( 'Button 2 Download', 'prosandcons' ),
								'type'        => Controls_Manager::SWITCHER,
								'return_type' => 'true',
								'default'     => $defaults['productTable'][0]['btn2Download'] ? 'true' : 'false',
							),
						),
						'default'     => array(
							array(
								'imageUrl'         => Utils::get_placeholder_image_src(),
								'ribbon'           => $defaults['productTable'][0]['ribbon'],
								'name'             => $defaults['productTable'][0]['name'],
								'features'         => $defaults['productTable'][0]['features'],
								'featuresList'     => array(
									array(
										'content' => 'Enter new item',
									),
								),
								'offerPrice'       => $defaults['productTable'][0]['offerPrice'],
								'regularPrice'     => $defaults['productTable'][0]['regularPrice'],
								'rating'           => $defaults['productTable'][0]['rating'],
								'button1'          => $defaults['productTable'][0]['button1'],
								'button1URL'       => $defaults['productTable'][0]['button1URL'],
								'btn1RelNoFollow'  => $defaults['productTable'][0]['btn1RelNoFollow'] ? 'true' : 'false',
								'btn1RelSponsored' => $defaults['productTable'][0]['btn1RelSponsored'] ? 'true' : 'false',
								'btn1OpenInNewTab' => $defaults['productTable'][0]['btn1OpenInNewTab'] ? 'true' : 'false',
								'btn1Download'     => $defaults['productTable'][0]['btn1Download'] ? 'true' : 'false',
								'button2'          => $defaults['productTable'][0]['button2'],
								'button2URL'       => $defaults['productTable'][0]['button2URL'],
								'btn2RelNoFollow'  => $defaults['productTable'][0]['btn2RelNoFollow'] ? 'true' : 'false',
								'btn2RelSponsored' => $defaults['productTable'][0]['btn2RelSponsored'] ? 'true' : 'false',
								'btn2OpenInNewTab' => $defaults['productTable'][0]['btn2OpenInNewTab'] ? 'true' : 'false',
								'btn2Download'     => $defaults['productTable'][0]['btn2Download'] ? 'true' : 'false',
							),
						),
					),
					'contentListType'    => array(
						'label'     => __( 'List Type', 'prosandcons' ),
						'type'      => Controls_Manager::CHOOSE,
						'options'   => array(
							'unordered' => array(
								'title' => __( 'Unordered', 'prosandcons' ),
								'icon'  => 'eicon-editor-list-ul',
							),
							'ordered'   => array(
								'title' => __( 'Ordered', 'prosandcons' ),
								'icon'  => 'eicon-editor-list-ol',
							),
						),
						'toggle'    => false,
						'default'   => $defaults['contentListType'],
						'condition' => array(
							'productContentType' => 'list',
						),
					),
					'productIconList'    => array(
						'label'     => __( 'List Icon', 'prosandcons' ),
						'type'      => Controls_Manager::ICONS,
						'default'   => array(
							'value'   => $defaults['productIconList']['value'],
							'library' => 'fa-regular',
						),
						'condition' => array(
							'productContentType' => 'list',
							'contentListType'    => 'unordered',
						),
					),
				),
			),

			'primary_button_settings_section'   => array(
				'label'  => __( 'Primary Button Settings', 'prosandcons' ),
				'tab'    => Controls_Manager::TAB_CONTENT,
				'fields' => array(
					'edButton1'        => array(
						'label'        => __( 'Enable Primary Button', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => $defaults['edButton1'] ? 'true' : 'false',
					),
					'edButton1Icon'    => array(
						'label'        => __( 'Enable Icon', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => $defaults['edButton1Icon'] ? 'true' : 'false',
						'condition'    => array(
							'edButton1' => 'true',
						),
					),
					'button1Icon'      => array(
						'type'      => Controls_Manager::ICONS,
						'default'   => array(
							'value'   => $defaults['button1Icon']['value'],
							'library' => 'fa-regular',
						),
						'condition' => array(
							'edButton1'     => 'true',
							'edButton1Icon' => 'true',
						),
					),
					'button1IconAlign' => array(
						'label'     => __( 'Icon Alignment', 'prosandcons' ),
						'type'      => Controls_Manager::CHOOSE,
						'options'   => array(
							'left'  => array(
								'title' => __( 'Left', 'prosandcons' ),
								'icon'  => 'eicon-text-align-left',
							),
							'right' => array(
								'title' => __( 'Right', 'prosandcons' ),
								'icon'  => 'eicon-text-align-right',
							),
						),
						'default'   => $defaults['button1IconAlign'],
						'toggle'    => false,
						'condition' => array(
							'edButton1'     => 'true',
							'edButton1Icon' => 'true',
						),
					),
				),
			),

			'secondary_button_settings_section' => array(
				'label'  => __( 'Secondary Button Settings', 'prosandcons' ),
				'tab'    => Controls_Manager::TAB_CONTENT,
				'fields' => array(
					'edButton2'        => array(
						'label'        => __( 'Enable Secondary Button', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => $defaults['edButton2'] ? 'true' : 'false',
					),
					'edButton2Icon'    => array(
						'label'        => __( 'Enable Icon', 'prosandcons' ),
						'type'         => Controls_Manager::SWITCHER,
						'return_value' => 'true',
						'default'      => $defaults['edButton2Icon'] ? 'true' : 'false',
						'condition'    => array(
							'edButton2' => 'true',
						),
					),
					'button2Icon'      => array(
						'type'      => Controls_Manager::ICONS,
						'default'   => array(
							'value'   => $defaults['button2Icon']['value'],
							'library' => 'fa-regular',
						),
						'condition' => array(
							'edButton2'     => 'true',
							'edButton2Icon' => 'true',
						),
					),
					'button2IconAlign' => array(
						'label'     => __( 'Icon Alignment', 'prosandcons' ),
						'type'      => Controls_Manager::CHOOSE,
						'options'   => array(
							'left'  => array(
								'title' => __( 'Left', 'prosandcons' ),
								'icon'  => 'eicon-text-align-left',
							),
							'right' => array(
								'title' => __( 'Right', 'prosandcons' ),
								'icon'  => 'eicon-text-align-right',
							),
						),
						'default'   => $defaults['button2IconAlign'],
						'toggle'    => false,
						'condition' => array(
							'edButton2'     => 'true',
							'edButton2Icon' => 'true',
						),
					),
				),
			),

			'border_settings_section'           => array(
				'label'  => __( 'Border Settings', 'prosandcons' ),
				'tab'    => Controls_Manager::TAB_STYLE,
				'fields' => array(
					'primaryButtonBorderLabel'   => array(
						'label'     => esc_html__( 'Primary Button', 'prosandcons' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'after',
						'condition' => array(
							'edButton1' => 'true',
						),
					),
					'button1Border'              => array(
						'label'          => __( 'Border', 'prosandcons' ),
						'type'           => Group_Control_Border::get_type(),
						'responsive'     => true,
						'selector'       => $this->select_element( 'primary-button' ),
						'fields_options' => array(
							'border' => array(
								'default' => $defaults['button1Border']['style'],
								'label'   => __( 'Border Type', 'prosandcons' ),
							),
							'color'  => array(
								'default' => $defaults['button1Border']['color']['color'],
								'label'   => __( 'Border Color', 'prosandcons' ),
							),
							'width'  => array(
								'default' => array(
									'isLinked' => false,
									'unit'     => 'px',
									'top'      => $defaults['button1Border']['width'],
									'right'    => $defaults['button1Border']['width'],
									'bottom'   => $defaults['button1Border']['width'],
									'left'     => $defaults['button1Border']['width'],
								),
								'label'   => __( 'Border Width', 'prosandcons' ),
							),
						),
						'separator'      => 'before',
						'condition'      => array(
							'edButton1' => 'true',
						),
					),
					'button1BorderHoverColor'    => array(
						'label'     => __( 'Border Hover Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							$this->select_element( 'primary-button:hover' ) => 'border-color: {{VALUE}};',
						),
						'condition' => array(
							'edButton1' => 'true',
						),
					),
					'button1Radius'              => array(
						'label'      => __( 'Border Radius', 'prosandcons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'rem', 'em' ),
						'default'    => array(
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '0',
							'left'     => '0',
							'unit'     => 'px',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_element( 'primary-button' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition'  => array(
							'edButton1' => 'true',
						),
					),
					'button1Shadow'              => array(
						'type'           => Group_Control_Box_Shadow::get_type(),
						'selector'       => $this->select_element( 'primary-button' ),
						'label'          => __( 'Box Shadow', 'prosandcons' ),
						'fields_options' => array(
							'box_shadow_type' => array(
								'default' => $defaults['button1Shadow']['enable'] ? 'enable' : '',
							),
							'box_shadow'      => array(
								'default' => array(
									'vertical'   => $defaults['button1Shadow']['v_offset'],
									'horizontal' => $defaults['button1Shadow']['h_offset'],
									'blur'       => $defaults['button1Shadow']['blur'],
									'spread'     => $defaults['button1Shadow']['spread'],
									'color'      => $defaults['button1Shadow']['color']['color'],
									'inset'      => $defaults['button1Shadow']['inset'],
								),
							),
						),
						'condition'      => array(
							'edButton1' => 'true',
						),
					),
					'secondaryButtonBorderLabel' => array(
						'label'     => esc_html__( 'Secondary Button', 'prosandcons' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'after',
						'condition' => array(
							'edButton2' => 'true',
						),
					),
					'button2Border'              => array(
						'label'          => __( 'Border', 'prosandcons' ),
						'type'           => Group_Control_Border::get_type(),
						'responsive'     => true,
						'selector'       => $this->select_element( 'secondary-button' ),
						'fields_options' => array(
							'border' => array(
								'default' => $defaults['button2Border']['style'],
								'label'   => __( 'Border Type', 'prosandcons' ),
							),
							'color'  => array(
								'default' => $defaults['button2Border']['color']['color'],
								'label'   => __( 'Border Color', 'prosandcons' ),
							),
							'width'  => array(
								'default' => array(
									'isLinked' => false,
									'unit'     => 'px',
									'top'      => $defaults['button2Border']['width'],
									'right'    => $defaults['button2Border']['width'],
									'bottom'   => $defaults['button2Border']['width'],
									'left'     => $defaults['button2Border']['width'],
								),
								'label'   => __( 'Border Width', 'prosandcons' ),
							),
						),
						'separator'      => 'before',
						'condition'      => array(
							'edButton2' => 'true',
						),
					),
					'button2BorderHoverColor'    => array(
						'label'     => __( 'Border Hover Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => array(
							$this->select_element( 'secondary-button:hover' ) => 'border-color: {{VALUE}};',
						),
						'condition' => array(
							'edButton2' => 'true',
						),
					),
					'button2Radius'              => array(
						'label'      => __( 'Border Radius', 'prosandcons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'rem', 'em' ),
						'default'    => array(
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '0',
							'left'     => '0',
							'unit'     => 'px',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_element( 'secondary-button' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition'  => array(
							'edButton2' => 'true',
						),
					),
					'button2Shadow'              => array(
						'type'           => Group_Control_Box_Shadow::get_type(),
						'selector'       => $this->select_element( 'secondary-button' ),
						'label'          => __( 'Box Shadow', 'prosandcons' ),
						'fields_options' => array(
							'box_shadow_type' => array(
								'default' => $defaults['button2Shadow']['enable'] ? 'enable' : '',
							),
							'box_shadow'      => array(
								'default' => array(
									'vertical'   => $defaults['button2Shadow']['v_offset'],
									'horizontal' => $defaults['button2Shadow']['h_offset'],
									'blur'       => $defaults['button2Shadow']['blur'],
									'spread'     => $defaults['button2Shadow']['spread'],
									'color'      => $defaults['button2Shadow']['color']['color'],
									'inset'      => $defaults['button2Shadow']['inset'],
								),
							),
						),
						'condition'      => array(
							'edButton2' => 'true',
						),
					),
					'border'                     => array(
						'label'          => __( 'Border', 'prosandcons' ),
						'type'           => Group_Control_Border::get_type(),
						'selector'       => $this->select_element( 'wrapper' ),
						'fields_options' => array(
							'border' => array(
								'default' => 'none',
								'label'   => __( 'Border Type', 'prosandcons' ),
							),
							'color'  => array(
								'default' => '#dddddd',
								'label'   => __( 'Border Color', 'prosandcons' ),
							),
							'width'  => array(
								'default' => array(
									'isLinked' => false,
									'unit'     => 'px',
									'top'      => $defaults['button1Border']['width'],
									'right'    => $defaults['button1Border']['width'],
									'bottom'   => $defaults['button1Border']['width'],
									'left'     => '1',
								),
								'label'   => __( 'Border Width', 'prosandcons' ),
							),
						),
						'separator'      => 'before',
						'condition'      => array(),
					),
					'borderRadius'               => array(
						'label'      => __( 'Border Radius', 'prosandcons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'rem', 'em' ),
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
						'condition'  => array(),
					),
					'boxShadow'                  => array(
						'type'           => Group_Control_Box_Shadow::get_type(),
						'selector'       => $this->select_element( 'wrapper' ),
						'label'          => __( 'Box Shadow', 'prosandcons' ),
						'fields_options' => array(
							'box_shadow_type' => array(
								'default' => $defaults['boxShadow']['enable'] ? 'enable' : '',
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
					),
				),
			),

			'colors_setting_section'            => array(
				'label'  => __( 'Colors', 'prosandcons' ),
				'tab'    => Controls_Manager::TAB_STYLE,
				'fields' => array(
					'ribbonColorSettingsLabel'      => array(
						'label'     => esc_html__( 'Ribbon', 'prosandcons' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'after',
						'condition' => array(
							'edRibbon' => 'true',
						),
					),
					'ribbonColor'                   => array(
						'label'     => __( 'Text Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['ribbonColor'],
						'selectors' => array(
							$this->select_element( array( 'wrapper', ' .affx-pdt-ribbon' ) ) => 'color: {{VALUE}}',
						),
						'condition' => array(
							'edRibbon' => 'true',
						),
					),
					'ribbonBgColor'                 => array(
						'label'     => __( 'Background Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['ribbonBgColor'],
						'selectors' => array(
							$this->select_elements(
								array(
									array( 'wrapper', ' .affx-pdt-ribbon' ),
									array( 'wrapper', ' .affx-pdt-ribbon::before' ),
								)
							) => 'background: {{VALUE}}',
						),
						'condition' => array(
							'edRibbon' => 'true',
						),
					),
					'counterColorSettingsLabel'     => array(
						'label'     => esc_html__( 'Counter', 'prosandcons' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'after',
						'condition' => array(
							'edCounter' => 'true',
						),
					),
					'counterColor'                  => array(
						'label'     => __( 'Text Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['counterColor'],
						'selectors' => array(
							$this->select_element( array( 'wrapper', ' .affx-pdt-counter' ) ) => 'color: {{VALUE}}',
						),
						'condition' => array(
							'edCounter' => 'true',
						),
					),
					'counterBgColor'                => array(
						'label'     => __( 'Background Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['counterBgColor'],
						'selectors' => array(
							$this->select_element( array( 'wrapper', ' .affx-pdt-counter' ) ) => 'background: {{VALUE}}',
						),
						'condition' => array(
							'edCounter' => 'true',
						),
					),
					'ratingColorSettingsLabel'      => array(
						'label'     => esc_html__( 'Rating', 'prosandcons' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'after',
						'condition' => array(
							'edRating'    => 'true',
							'layoutStyle' => 'layoutOne',
						),
					),
					'ratingColor'                   => array(
						'label'     => __( 'Text Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['ratingColor'],
						'selectors' => array(
							$this->select_element( 'star-rating' ) => 'color: {{VALUE}}',
						),
						'condition' => array(
							'edRating'    => 'true',
							'layoutStyle' => 'layoutOne',
						),
					),
					'ratingBgColor'                 => array(
						'label'     => __( 'Background Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['ratingBgColor'],
						'selectors' => array(
							$this->select_element( 'star-rating' ) => 'background: {{VALUE}}',
						),
						'condition' => array(
							'edRating'    => 'true',
							'layoutStyle' => 'layoutOne',
						),
					),
					'rating2ColorSettingsLabel'     => array(
						'label'     => esc_html__( 'Rating', 'prosandcons' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'after',
						'condition' => array(
							'edRating'    => 'true',
							'layoutStyle' => 'layoutTwo',
						),
					),
					'rating2Color'                  => array(
						'label'     => __( 'Text Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['rating2Color'],
						'selectors' => array(
							$this->select_element( 'circle-rating' ) => 'color: {{VALUE}}',
						),
						'condition' => array(
							'edRating'    => 'true',
							'layoutStyle' => 'layoutTwo',
						),
					),
					'rating2BgColor'                => array(
						'label'     => __( 'Background Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['rating2BgColor'],
						'selectors' => array(
							$this->select_element( array( 'wrapper', ' .circle-wrap .circle-mask .fill' ) ) => 'background: {{VALUE}}',
						),
						'condition' => array(
							'edRating'    => 'true',
							'layoutStyle' => 'layoutTwo',
						),
					),
					'starRatingColorSettingsLabel'  => array(
						'label'     => esc_html__( 'Rating', 'prosandcons' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'after',
						'condition' => array(
							'edRating'    => 'true',
							'layoutStyle' => 'layoutThree',
						),
					),
					'starColor'                     => array(
						'label'     => __( 'Star Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['starColor'],
						'condition' => array(
							'edRating'    => 'true',
							'layoutStyle' => 'layoutThree',
						),
					),
					'starInactiveColor'             => array(
						'label'     => __( 'Star Inactive Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['starInactiveColor'],
						'condition' => array(
							'edRating'    => 'true',
							'layoutStyle' => 'layoutThree',
						),
					),
					'tableHeaderColorSettingsLabel' => array(
						'label'     => esc_html__( 'Table Header', 'prosandcons' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'after',
						'condition' => array(
							'layoutStyle!' => 'layoutThree',
						),
					),
					'tableHeaderColor'              => array(
						'label'     => __( 'Text Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['tableHeaderColor'],
						'selectors' => array(
							$this->select_element( array( 'wrapper', ' .affx-pdt-table thead td' ) ) => 'color: {{VALUE}}',
						),
						'condition' => array(
							'layoutStyle!' => 'layoutThree',
						),
					),
					'tableHeaderBgColor'            => array(
						'label'     => __( 'Background Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['tableHeaderBgColor'],
						'selectors' => array(
							$this->select_element( array( 'wrapper', ' .affx-pdt-table thead td' ) ) => 'background: {{VALUE}}; border-color: {{VALUE}}',
						),
						'condition' => array(
							'layoutStyle!' => 'layoutThree',
						),
					),
					'button1TextColorSettingsLabel' => array(
						'label'     => esc_html__( 'Primary Button Text', 'prosandcons' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'after',
						'condition' => array(
							'edButton1' => 'true',
						),
					),
					'button1TextColor'              => array(
						'label'     => __( 'Text Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['button1TextColor'],
						'selectors' => array(
							$this->select_element( 'primary-button' ) => 'color: {{VALUE}}',
						),
						'condition' => array(
							'edButton1' => 'true',
						),
					),
					'button1TextHoverColor'         => array(
						'label'     => __( 'Text Hover Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['button1TextHoverColor'],
						'selectors' => array(
							$this->select_element( array( 'primary-button', ':hover' ) ) => 'color: {{VALUE}}',
						),
						'condition' => array(
							'edButton1' => 'true',
						),
					),
					'button1ColorSettingsLabel'     => array(
						'label'     => esc_html__( 'Primary Button', 'prosandcons' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'after',
						'condition' => array(
							'edButton1' => 'true',
						),
					),
					'button1BgColor'                => array(
						'label'     => __( 'Background Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => ProsCons_Customization_Helper::get_value( 'btnColor', $defaults['button1BgColor'] ),
						'selectors' => array(
							$this->select_element( 'primary-button' ) => 'background: {{VALUE}}',
						),
						'condition' => array(
							'edButton1' => 'true',
						),
					),
					'button1BgHoverColor'           => array(
						'label'     => __( 'Background Hover Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => ProsCons_Customization_Helper::get_value( 'btnHoverColor', $defaults['button1BgHoverColor'] ),
						'selectors' => array(
							$this->select_element( array( 'primary-button', ':hover' ) ) => 'background: {{VALUE}}',
						),
						'condition' => array(
							'edButton1' => 'true',
						),
					),
					'button1borderHoverColor'       => array(
						'label'     => __( 'Border Hover Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['button1borderHoverColor'],
						'selectors' => array(
							$this->select_element( array( 'primary-button', ':hover' ) ) => 'border-color: {{VALUE}}',
						),
						'condition' => array(
							'edButton1' => 'true',
						),
					),
					'button2TextColorSettingsLabel' => array(
						'label'     => esc_html__( 'Secondary Button Text', 'prosandcons' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'after',
						'condition' => array(
							'edButton2' => 'true',
						),
					),
					'button2TextColor'              => array(
						'label'     => __( 'Text Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['button2TextColor'],
						'selectors' => array(
							$this->select_element( 'secondary-button' ) => 'color: {{VALUE}}',
						),
						'condition' => array(
							'edButton2' => 'true',
						),
					),
					'button2TextHoverColor'         => array(
						'label'     => __( 'Text Hover Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['button2TextHoverColor'],
						'selectors' => array(
							$this->select_element( array( 'secondary-button', ':hover' ) ) => 'color: {{VALUE}}',
						),
						'condition' => array(
							'edButton2' => 'true',
						),
					),
					'button2ColorSettingsLabel'     => array(
						'label'     => esc_html__( 'Secondary Button', 'prosandcons' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'after',
						'condition' => array(
							'edButton2' => 'true',
						),
					),
					'button2BgColor'                => array(
						'label'     => __( 'Background Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['button2BgColor'],
						'selectors' => array(
							$this->select_element( 'secondary-button' ) => 'background: {{VALUE}}',
						),
						'condition' => array(
							'edButton2' => 'true',
						),
					),
					'button2BgHoverColor'           => array(
						'label'     => __( 'Background Hover Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['button2BgHoverColor'],
						'selectors' => array(
							$this->select_element( array( 'secondary-button', ':hover' ) ) => 'background: {{VALUE}}',
						),
						'condition' => array(
							'edButton2' => 'true',
						),
					),
					'button2borderHoverColor'       => array(
						'label'     => __( 'Border Hover Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['button2borderHoverColor'],
						'selectors' => array(
							$this->select_element( array( 'secondary-button', ':hover' ) ) => 'border-color: {{VALUE}}',
						),
						'condition' => array(
							'edButton2' => 'true',
						),
					),
					'priceColor'                    => array(
						'label'     => __( 'Price Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['priceColor'],
						'selectors' => array(
							$this->select_element( 'price' ) => 'color: {{VALUE}}',
						),
						'condition' => array(
							'edPrice' => 'true',
						),
					),
					'titleColor'                    => array(
						'label'     => __( 'Title Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => ProsCons_Customization_Helper::get_value( 'fontColor', $defaults['titleColor'] ),
						'selectors' => array(
							$this->select_element( 'title' ) => 'color: {{VALUE}}',
						),
					),
					'contentColor'                  => array(
						'label'     => __( 'Content Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => ProsCons_Customization_Helper::get_value( 'fontColor', $defaults['contentColor'] ),
						'selectors' => array(
							$this->select_elements(
								array(
									'wrapper',
									array( 'wrapper', ' p' ),
									array( 'wrapper', ' li' ),
								)
							) => 'color: {{VALUE}}',
						),
					),
					'productIconColor'              => array(
						'label'     => __( 'List Icon Color', 'prosandcons' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => $defaults['productIconColor'],
						'selectors' => array(
							$this->select_elements(
								array(
									array( 'wrapper', ' .afx-icon-list li:before' ),
									array( 'wrapper', ' .afx-icon-list li i' ),
								)
							) => 'color: {{VALUE}}',
						),
						'condition' => array(
							'productContentType' => 'list',
						),
					),
					'bgColor'                       => array(
						'type'           => Group_Control_Background::get_type(),
						'types'          => array( 'classic', 'gradient' ),
						'selector'       => $this->select_elements(
							array(
								'wrapper',
								array( 'wrapper', ' .affx-pdt-table' ),
								'table-single',
							)
						),
						'exclude'        => array( 'image' ),
						'fields_options' => array(
							'background'     => array(
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
								'label'   => __( 'Background Type', 'prosandcons' ),
								'toggle'  => false,
							),
							'color'          => array(
								'label'   => __( 'Background Color', 'prosandcons' ),
								'default' => $defaults['bgColorSolid'],
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
					),
				),
			),

			'typography_settings_section'       => array(
				'label'  => __( 'Typography', 'prosandcons' ),
				'tab'    => Controls_Manager::TAB_STYLE,
				'fields' => array(
					'ribbonTypography'  => array(
						'label'          => __( 'Ribbon Typography', 'prosandcons' ),
						'type'           => Group_Control_Typography::get_type(),
						'selector'       => $this->select_element( array( 'wrapper', ' .affx-pdt-ribbon' ) ),
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
							'edRibbon' => 'true',
						),
					),
					'counterTypography' => array(
						'label'          => __( 'Counter Typography', 'prosandcons' ),
						'type'           => Group_Control_Typography::get_type(),
						'selector'       => $this->select_element( array( 'wrapper', ' .affx-pdt-counter' ) ),
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
						'condition'      => array(
							'edCounter' => 'true',
						),
					),
					'ratingTypography'  => array(
						'label'          => __( 'Rating Typography', 'prosandcons' ),
						'type'           => Group_Control_Typography::get_type(),
						'selector'       => $this->select_element( 'star-rating' ),
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
							'edRating'    => 'true',
							'layoutStyle' => 'layoutOne',
						),
					),
					'rating2Typography' => array(
						'label'          => __( 'Rating Typography', 'prosandcons' ),
						'type'           => Group_Control_Typography::get_type(),
						'selector'       => $this->select_element( 'circle-rating' ),
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
							'edRating'    => 'true',
							'layoutStyle' => 'layoutTwo',
						),
					),
					'priceTypography'   => array(
						'label'          => __( 'Price Typography', 'prosandcons' ),
						'type'           => Group_Control_Typography::get_type(),
						'selector'       => $this->select_element( 'price' ),
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
									'size' => '22',
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
							'edPrice' => 'true',
						),
					),
					'buttonTypography'  => array(
						'label'          => __( 'Button Typography', 'prosandcons' ),
						'type'           => Group_Control_Typography::get_type(),
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
									'size' => '14',
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
					),
					'headerTypography'  => array(
						'label'          => __( 'Table Header Typography', 'prosandcons' ),
						'type'           => Group_Control_Typography::get_type(),
						'selector'       => $this->select_element( array( 'wrapper', ' .affx-pdt-table thead td' ) ),
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
					),
					'titleTypography'   => array(
						'label'          => __( 'Title Typography', 'prosandcons' ),
						'type'           => Group_Control_Typography::get_type(),
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
									'size' => '22',
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
					),
					'contentTypography' => array(
						'label'          => __( 'Content Typography', 'prosandcons' ),
						'type'           => Group_Control_Typography::get_type(),
						'selector'       => $this->select_elements(
							array(
								'wrapper',
								array( 'wrapper', ' p' ),
								array( 'wrapper', ' li' ),
							)
						),
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
					),
				),
			),

			'spacing_settings_section'          => array(
				'label'  => __( 'Spacing', 'prosandcons' ),
				'tab'    => Controls_Manager::TAB_STYLE,
				'fields' => array(
					'imagePadding'                => array(
						'label'      => __( 'Image Padding', 'prosandcons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
						'default'    => array(
							'unit'     => 'px',
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '0',
							'left'     => '0',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_element( 'image-container' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					),
					'button1SpacingSettingsLabel' => array(
						'label'     => esc_html__( 'Primary Button', 'prosandcons' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'after',
						'condition' => array(
							'edButton1' => 'true',
						),
					),
					'button1Margin'               => array(
						'label'      => __( 'Margin', 'prosandcons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
						'default'    => array(
							'unit'     => 'px',
							'top'      => '5',
							'right'    => '0',
							'bottom'   => '5',
							'left'     => '0',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_element( 'primary-button' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition'  => array(
							'edButton1' => 'true',
						),
					),
					'button1Padding'              => array(
						'label'      => __( 'Padding', 'prosandcons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
						'default'    => array(
							'unit'     => 'px',
							'top'      => '10',
							'right'    => '5',
							'bottom'   => '10',
							'left'     => '5',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_element( 'primary-button' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition'  => array(
							'edButton1' => 'true',
						),
					),
					'button2SpacingSettingsLabel' => array(
						'label'     => esc_html__( 'Secondary Button', 'prosandcons' ),
						'type'      => Controls_Manager::HEADING,
						'separator' => 'after',
						'condition' => array(
							'edButton2' => 'true',
						),
					),
					'button2Margin'               => array(
						'label'      => __( 'Margin', 'prosandcons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
						'default'    => array(
							'unit'     => 'px',
							'top'      => '5',
							'right'    => '0',
							'bottom'   => '5',
							'left'     => '0',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_element( 'secondary-button' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition'  => array(
							'edButton2' => 'true',
						),
					),
					'button2Padding'              => array(
						'label'      => __( 'Padding', 'prosandcons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
						'default'    => array(
							'unit'     => 'px',
							'top'      => '10',
							'right'    => '5',
							'bottom'   => '10',
							'left'     => '5',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_element( 'secondary-button' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
						'condition'  => array(
							'edButton2' => 'true',
						),
					),
					'margin'                      => array(
						'label'      => __( 'Margin', 'prosandcons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
						'default'    => array(
							'unit'     => 'px',
							'top'      => '0',
							'right'    => '0',
							'bottom'   => '30',
							'left'     => '0',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_elements(
								array(
									'wrapper',
									'table-single',
								)
							) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					),
					'padding'                     => array(
						'label'      => __( 'Padding', 'prosandcons' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => array( 'px', '%', 'em', 'rem', 'pt' ),
						'default'    => array(
							'unit'     => 'px',
							'top'      => '24',
							'right'    => '24',
							'bottom'   => '24',
							'left'     => '24',
							'isLinked' => false,
						),
						'selectors'  => array(
							$this->select_elements(
								array(
									array( 'wrapper', ' td:not(.affx-img-col)' ),
									array( 'wrapper', ' th' ),
								)
							) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						),
					),
					// Amazon Attributes Configuration
					'amazonAttributes'            => array(
						'type'    => Controls_Manager::HIDDEN,
						'default' => array(
							array(
								'field'      => 'title',
								'blockField' => array(
									'name'         => 'name',
									'type'         => 'text',
									'repeaterName' => 'productTable',
									'defaults'     => array(
										'name' => $defaults['productTable'][0]['name'],
									),
								),
								'type'       => 'text',
							),
							array(
								'field'      => 'features',
								'blockField' => array(
									'name'         => 'featuresListAmazon',
									'type'         => 'list',
									'repeaterName' => 'productTable',
									'defaults'     => array(
										'featuresListAmazon' => '',
									),
								),
								'type'       => 'list',
							),
							array(
								'field'      => 'display_price',
								'blockField' => array(
									'name'         => 'offerPrice',
									'type'         => 'text',
									'repeaterName' => 'productTable',
									'defaults'     => array(
										'offerPrice' => '$49.00',
									),
								),
								'type'       => 'text',
							),
							array(
								'field'      => 'regular_display_price',
								'blockField' => array(
									'name'         => 'regularPrice',
									'type'         => 'text',
									'repeaterName' => 'productTable',
									'defaults'     => array(
										'regularPrice' => '$59.00',
									),
								),
								'type'       => 'text',
							),
							array(
								'field'      => 'images',
								'blockField' => array(
									'name'         => 'imageExternal',
									'type'         => 'image',
									'repeaterName' => 'productTable',
									'defaults'     => array(
										'imageExternal' => '',
									),
								),
								'type'       => 'image',
							),
							array(
								'field'      => 'url',
								'blockField' => array(
									'name'         => 'button1URL',
									'type'         => 'link',
									'repeaterName' => 'productTable',
									'defaults'     => array(
										'button1URL' => '',
									),
								),
								'type'       => 'link',
							),
							array(
								'field'      => 'url',
								'blockField' => array(
									'name'         => 'button2URL',
									'type'         => 'link',
									'repeaterName' => 'productTable',
									'defaults'     => array(
										'button2URL' => '',
									),
								),
								'type'       => 'link',
							),
						),
					),
				),
			),
		);
	}

	protected function register_controls() {
		WidgetHelper::generate_fields(
			$this,
			$this->get_elementor_controls(),
			'product-table'
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

		$attributes = WidgetHelper::format_boolean_attributes( $attributes );

		foreach ( $attributes['productTable'] as $key => $value ) {
			if ( $attributes['productTable'][ $key ]['imageType'] === 'default' ) {
				$attributes['productTable'][ $key ]['imageUrl'] = isset( $value['imageUrl']['url'] ) ? esc_url( $value['imageUrl']['url'] ) : '';
				$attributes['productTable'][ $key ]['imageAlt'] = isset( $value['imageUrl']['alt'] ) ? esc_attr( $value['imageUrl']['alt'] ) : '';
			} else {
				$attributes['productTable'][ $key ]['imageUrl'] = isset( $value['imageExternal'] ) ? esc_url( $value['imageExternal'] ) : '';
			}

			if ( $attributes['productTable'][ $key ]['featuresListType'] === 'list' ) {
				$attributes['productTable'][ $key ]['featuresList'] = WidgetHelper::format_list_items( $value['featuresList'] );
			} else {
				$attributes['productTable'][ $key ]['featuresList'] = $value['featuresListAmazon'];
			}
		}

		echo ProsCons_Helpers::kses( $this->render_template( $attributes, '' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
