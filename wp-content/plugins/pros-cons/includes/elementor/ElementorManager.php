<?php
namespace ProsCons\Elementor;

defined( 'ABSPATH' ) || exit;


/**
 * Elementor Manager Class.
 *
 * This class is responsible for enqueuing the assets for the Elementor editor.
 *
 * @package ProsCons\Elementor
 * @since 1.0.0
 * @version 1.0.0
 */
class ElementorManager {
	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	public function __construct() {
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'elementor/icons_manager/additional_tabs', array( $this, 'add_custom_icons_tab' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue_custom_icons_css' ) );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'enqueue_custom_icons_css' ) );
	}

	/**
	 * Enqueue Assets.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	public function enqueue_assets() {
		if ( affx_is_elementor_editor() ) {
			wp_enqueue_script(
				'prosandcons-elementor-editor',
				PROSCONS_PLUGIN_URL . '/build/elementorEditor.js',
				array( 'jquery', 'prosandcons', 'prosandcons-block-export' ),
				PROSCONS_VERSION,
				array()
			);
		}
	}

	/**
	 * Add custom icons tab to Elementor icon picker
	 *
	 * @param array $tabs Existing icon tabs.
	 */
	public function add_custom_icons_tab( $tabs ) {
		$tabs['prosandcons-icons'] = array(
			'name'          => 'prosandcons-icons',
			'label'         => __( 'ProsCons Icons', 'prosandcons' ),
			'url'           => PROSCONS_PLUGIN_URL . 'assets/css/custom-icons.css',
			'enqueue'       => array( PROSCONS_PLUGIN_URL . 'assets/css/custom-icons.css' ),
			'prefix'        => 'affx-',
			'displayPrefix' => 'affx',
			'labelIcon'     => 'affx-button',
			'ver'           => PROSCONS_VERSION,
			'fetchJson'     => PROSCONS_PLUGIN_URL . 'assets/js/custom-icons.json',
			'native'        => true,
		);
		return $tabs;
	}
	/**
	 * Enqueue custom icons CSS
	 */
	public function enqueue_custom_icons_css() {
		wp_enqueue_style(
			'prosandcons-custom-icons',
			PROSCONS_PLUGIN_URL . 'assets/css/custom-icons.css',
			array(),
			PROSCONS_VERSION
		);
	}
}
