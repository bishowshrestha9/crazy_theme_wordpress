<?php

namespace ProsCons\Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * ProsCons Elementor Helper | Handles template compatibility
 *
 * @package ProsCons
 */
class ElementorHelper {

	/**
	 * Generate child field name with prefix and index
	 *
	 * @param array $config
	 * @param string $field_name
	 * @return string
	 */
	public static function child_field_name( array $config, string $field_name ): string {
		if ( $config['index'] === null && $config['is_child'] === true ) {
			return sprintf( '%s_%s', $config['name_prefix'], $field_name );
		} elseif ( $config['index'] !== null && $config['is_child'] === true ) {
			return sprintf( '%s_%d_%s', $config['name_prefix'], $config['index'], $field_name );
		}

		return $field_name;
	}

	/**
	 * Generates child section label
	 *
	 * @param array $config
	 * @param string $label
	 * @return string
	 */
	public static function child_section_label( array $config, string $label ): string {
		if ( $config['index'] === null && $config['is_child'] === true ) {
			return sprintf( '%s | %s', $config['label_prefix'], $label );
		} elseif ( $config['index'] !== null && $config['is_child'] === true ) {
			return sprintf( '%s %d | %s', $config['label_prefix'], $config['index'], $label );
		}

		return $label;
	}

	/**
	 * Generates child section name
	 *
	 * @param array $config
	 * @param string $section_name
	 * @return string
	 */
	public static function child_section_name( array $config, string $section_name ): string {
		if ( $config['index'] !== null && $config['is_child'] === true ) {
			return sprintf( 'affx_%s_%s', $config['name_prefix'], $section_name );
		} elseif ( $config['index'] === null && $config['is_child'] === true ) {
			return sprintf( 'affx_%s_%d_%s', $config['name_prefix'], $config['index'], $section_name );
		}

		return sprintf( 'affx_%s_%s', $config['name_prefix'], $section_name );
	}

	public static function apply_child_conditions( $config, array $conditions = array() ): array {
		if ( $config['is_child'] === true && $config['conditions'] ) {
			return array_merge( $config['conditions'], $conditions );
		}

		return $conditions;
	}

	/**
	 * Extracts child attributes from an array by replacing 'dynamic' prefix
	 *
	 * @param array $attributes Widget attributes.
	 * @param array $config     Extraction config.
	 * @return array
	 */
	public static function extract_child_attributes( array $attributes, array $config ): array {
		$child_attributes = array();
		$formatted_prefix = sprintf( '%s_%d_', $config['name_prefix'], $config['index'] );

		foreach ( $attributes as $key => $value ) {
			if ( strpos( $key, $formatted_prefix ) === 0 ) {
				$child_attributes[ substr( $key, strlen( $formatted_prefix ) ) ] = $value;
			}
		}

		return $child_attributes;
	}

	/**
	 * Extracts list items for template compatibility
	 *
	 * @param array $list_items
	 * @return array
	 */
	public static function extract_list_items( array $list_items ): array {
		$items = array();

		foreach ( $list_items as $list_item ) {
			$items[] = array(
				'type'  => 'li',
				'props' => array(
					'children' => array( $list_item['content'] ),
				),
			);
		}

		return $items;
	}

	/**
	 * Extracts icon name for template compatibility
	 *
	 * @param array $icon_props
	 * @return array
	 */
	public static function extract_icon( array $icon_props ): array {
		$prefix    = isset( $icon_props['library'] ) ? explode( '-', $icon_props['library'] )[0] : '';
		$icon_name = explode( ' ', $icon_props['value'] )[1];
		$icon_name = str_replace( $prefix . '-', '', $icon_name );

		return array(
			'name'  => $icon_name,
			'value' => $icon_props['value'],
		);
	}
}
