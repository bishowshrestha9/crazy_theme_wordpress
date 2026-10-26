<?php

namespace ProsCons\Helpers\Elementor;

use ProsCons\Helpers\Elementor\WidgetHelper;
use Elementor\Widget_Base;

defined( 'ABSPATH' ) || exit;

/**
 * Helper Class to handle child Widgets in Elementor
 *
 * @package ProsCons
 */
class ChildHelper {

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
	 * Elementor Widget base to hook controls
	 *
	 * @var ElementorBase
	 */
	protected $controller;

	/**
	 * Child sections containing fields
	 *
	 * @var array
	 */
	protected $fields;

	/**
	 * Constructor, receives configs
	 *
	 * @param Widget_Base $controller
	 * @param array $fields
	 * @param array $config
	 */
	public function __construct( Widget_Base $controller, array $fields, array $config = array() ) {
		$this->config     = wp_parse_args( $config, $this->config );
		$this->controller = $controller;
		$this->fields     = $fields;
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
			return sprintf( 'affx_%s_%d_%s', $this->config['name_prefix'], $this->config['index'], $section_name );
		} elseif ( $this->config['index'] === null && $this->config['is_child'] === true ) {
			return sprintf( 'affx_%s_%s', $this->config['name_prefix'], $section_name );
		}

		return sprintf( 'affx_%s_%s', $this->config['name_prefix'], $section_name );
	}

	/**
	 * Generates child fields
	 *
	 * @return void
	 */
	public function generate_fields(): void {
		foreach ( $this->fields as $section_id => $section ) {
			$section_fields = $section['fields'];

			// Process section-level conditions
			$section_conditions = array();
			if ( isset( $section['condition'] ) ) {
				foreach ( $section['condition'] as $conditional_field_id => $conditional_value ) {
					$section_conditions[ $this->field_name( $conditional_field_id ) ] = $conditional_value;
				}
			}

			$section_conditions = array_merge( $section_conditions, $this->config['conditions'] ?? array() );

			$this->controller->start_controls_section(
				$this->section_name( $section_id ),
				array(
					'label'     => $this->section_label( $section['label'] ),
					'tab'       => $section['tab'],
					'condition' => $section_conditions,
				)
			);

			foreach ( $section_fields as $field_id => $field ) {
				$conditions = array();

				// merge child and parent conditions
				if ( isset( $field['condition'] ) ) {
					foreach ( $field['condition'] as $conditional_field_id => $conditional_value ) {
						$conditions[ $this->field_name( $conditional_field_id ) ] = $conditional_value;
					}
				}

				// Merge field specific conditions
				if ( isset( $this->config['field_conditions'] ) ) {
					if ( isset( $this->config['field_conditions'][ $this->field_name( $field_id ) ] ) ) {
						foreach ( $this->config['field_conditions'][ $this->field_name( $field_id ) ] as $condition_field_id => $condition_value ) {
							$conditions[ $condition_field_id ] = $condition_value;
						}
					}
				}

				$conditions         = array_merge( $conditions, $this->config['conditions'] ?? array() );
				$field['condition'] = $conditions;

				// handle default values
				$field['default'] = isset( $this->config['defaults'][ $field_id ] ) ? $this->config['defaults'][ $field_id ] : $field['default'] ?? null;

				if ( WidgetHelper::is_group_control( $field ) ) {
					$field['fields_options'] = wp_parse_args( $field['default'] ?? array(), $field['fields_options'] ?? array() );
				}

				// replace selector to handle nested styles
				if ( isset( $field['selector'] ) ) {
					$field['selector'] = $this->element_selector( $field['selector'] );
				} elseif ( isset( $field['selectors'] ) && is_array( $field['selectors'] ) ) {
					$selectors = array();
					foreach ( $field['selectors'] as $selector => $style_props ) {
						$selectors[ $this->element_selector( $selector ) ] = $style_props;
					}

					$field['selectors'] = $selectors;
				}

				if ( WidgetHelper::is_group_control( $field ) ) {
					$this->controller->add_group_control(
						$field['type'],
						array_merge(
							$field,
							array(
								'name' => $this->field_name( $field_id ),
							)
						),
					);
				} elseif ( WidgetHelper::is_responsive_control( $field ) ) {
					if ( isset( $field['responsive'] ) ) {
						unset( $field['responsive'] );
					}

					$this->controller->add_responsive_control(
						$this->field_name( $field_id ),
						$field
					);
				} else {
					$this->controller->add_control(
						$this->field_name( $field_id ),
						$field
					);
				}
			}

			$this->controller->end_controls_section();
		}
	}

	/**
	 * Extracts child attributes from an array by replacing 'dynamic' prefix
	 *
	 * @param array $attributes Widget attributes.
	 * @param array $config     Extraction config.
	 * @return array
	 */
	public static function extract_attributes( array $attributes, array $config ): array {
		$child_attributes = array( 'name' => $config['name_prefix'] );
		$formatted_prefix = sprintf( '%s_%s', $config['name_prefix'], empty( $config['index'] ) ? '' : $config['index'] . '_' );

		foreach ( $attributes as $key => $value ) {
			if ( strpos( $key, $formatted_prefix ) === 0 ) {
				$child_attributes[ substr( $key, strlen( $formatted_prefix ) ) ] = $value;
			}
		}

		return $child_attributes;
	}

	/**
	 * Generates child element selector
	 *
	 * @param string $selector
	 * @return string
	 */
	public function element_selector( string $selector ): string {
		$wrapper            = isset( $this->config['wrapper'] ) ? sprintf( '{{WRAPPER}} .%s', $this->config['wrapper'] ) : '{{WRAPPER}}';
		$formatted_selector = '';

		if ( $this->config['index'] === null || $this->config['is_child'] !== true ) {
			return $selector;
		}

		if ( is_integer( $this->config['index'] ) && $this->config['index'] > 0 && isset( $this->config['wrapper'] ) ) {
			$wrapper = sprintf( '%s:nth-child(%d)', $wrapper, $this->config['index'] );
		}

		if ( strpos( $selector, '{{WRAPPER}}' ) !== false ) {
			$formatted_selector = str_replace( '{{WRAPPER}}', $wrapper, $selector );
		}

		return $formatted_selector;
	}
}
