<?php
namespace ProsCons\Helpers\Elementor;

use ProsCons\Elementor\Widgets\ElementorBase;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Helper Class to handle child Widgets as repeater field in Elementor
 *
 * @package ProsCons
 */
class RepeaterHelper {
	/**
	 * Configuration contains prefix, label, index, default values and conditions
	 *
	 * @var array
	 */
	protected $config = array();

	/**
	 * Elementor Widget base to hook controls
	 *
	 * @var ElementorBase
	 */
	protected $controller;

	/**
	 * Repeater sections
	 *
	 * @var array
	 */
	protected $sections = array();

	/**
	 * Repeater widgets contrusctor
	 *
	 * @param ElementorBase $controller
	 * @param array $sections
	 * @param array $config
	 */
	public function __construct( ElementorBase $controller, array $sections, array $config = array() ) {
		$this->config     = wp_parse_args( $config, $this->config );
		$this->controller = $controller;
		$this->sections   = $sections;
	}

	/**
	 * Generates child repeater fields
	 *
	 * @return void
	 */
	public function generate_fields() {
		foreach ( $this->sections as $section_id => $section ) {
			$this->controller->add_control(
				$section_id,
				array(
					'label'     => $section['label'],
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				)
			);

			foreach ( $section['fields'] as $field_id => $field ) {
				$this->controller->add_control(
					$field_id,
					$field
				);
			}
		}
	}
}
