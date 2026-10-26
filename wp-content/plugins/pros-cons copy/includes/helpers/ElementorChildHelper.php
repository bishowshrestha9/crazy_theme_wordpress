<?php

namespace ProsCons\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * Helper Class to handle child Widgets in Elementor
 *
 * @package ProsCons
 */
class ElementorChildHelper {

	/**
	 * Config of child information
	 *
	 * @var array
	 */
	protected $config = array(
		'name_prefix'  => 'child_',
		'label_prefix' => '',
		'index'        => null,
		'is_child'     => false,
		'defaults'     => array(),
		'conditions'   => array(),
	);

	/**
	 * Constructor, receives configs
	 *
	 * @param array $config
	 */
	public function __construct( array $config = array() ) {
		$this->config = wp_parse_args( $config, $this->config );
	}

	/**
	 * Generate child field name with prefix and index
	 *
	 * @param string $field_name
	 * @return string
	 */
	public function field_name( string $field_name ): string {
		if ( $this->config['index'] === null && $this->config['is_child'] === true ) {
			return sprintf( '%s_%s', $this->config['name_prefix'], $field_name );
		} elseif ( $this->config['index'] !== null && $this->config['is_child'] === true ) {
			return sprintf( '%s_%d_%s', $this->config['name_prefix'], $this->config['index'], $field_name );
		}

		return $field_name;
	}

	/**
	 * Generates child section label
	 *
	 * @param string $label
	 * @return string
	 */
	public function section_label( string $label ): string {
		if ( $this->config['index'] === null && $this->config['is_child'] === true ) {
			return sprintf( '%s | %s', $this->config['label_prefix'], $label );
		} elseif ( $this->config['index'] !== null && $this->config['is_child'] === true ) {
			return sprintf( '%s %d | %s', $this->config['label_prefix'], $this->config['index'], $label );
		}

		return $label;
	}

	/**
	 * Generates child section name
	 *
	 * @param string $section_name
	 * @return string
	 */
	public function section_name( string $section_name ): string {
		if ( $this->config['index'] !== null && $this->config['is_child'] === true ) {
			return sprintf( 'affx_%s_%s', $this->config['name_prefix'], $section_name );
		} elseif ( $this->config['index'] === null && $this->config['is_child'] === true ) {
			return sprintf( 'affx_%s_%d_%s', $this->config['name_prefix'], $this->config['index'], $section_name );
		}

		return sprintf( 'affx_%s_%s', $this->config['name_prefix'], $section_name );
	}

	/**
	 * Apply combined conditions with child logic
	 *
	 * @param array $conditions
	 * @return array
	 */
	public function apply_conditions( array $conditions = array() ): array {
		if ( $this->config['is_child'] === true && $this->config['conditions'] ) {
			return array_merge( $this->config['conditions'], $conditions );
		}

		return $conditions;
	}
}
