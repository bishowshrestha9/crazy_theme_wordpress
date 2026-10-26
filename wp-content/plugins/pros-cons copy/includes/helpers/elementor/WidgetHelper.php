<?php

namespace ProsCons\Helpers\Elementor;

use ProsCons\Helpers\ElementorHelper;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Flex_Item;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || exit;

/**
 * Widget Helper Class
 *
 * @package ProsCons\Helpers\Elementor
 */
class WidgetHelper {

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
		$prefix    = explode( '-', $icon_props['library'] )[0];
		$icon_name = explode( ' ', $icon_props['value'] )[1];
		$icon_name = str_replace( $prefix . '-', '', $icon_name );

		return array(
			'name'  => $icon_name,
			'value' => $icon_props['value'],
		);
	}

	/**
	 * Generate Elementor widget sections and fields from array
	 *
	 * @param ElementorBase $controller
	 * @param array $sections
	 * @param string $prefix
	 * @return void
	 */
	public static function generate_fields( $controller, array $sections, string $prefix ): void {
		foreach ( $sections as $section_id => $section ) {
			$section_fields = $section['fields'];

			$controller->start_controls_section(
				sprintf( 'affx_%s_%s', $prefix, $section_id ),
				$section
			);

			foreach ( $section_fields as $field_id => $field ) {
				if ( self::is_group_control( $field ) ) {
					// Handle Group Controls.
					$type = $field['type'];

					unset( $field['type'] );

					$controller->add_group_control(
						$type,
						array_merge(
							array(
								'name' => $field_id,
							),
							$field
						)
					);
				} elseif ( self::is_responsive_control( $field ) ) {
					$controller->add_responsive_control(
						$field_id,
						$field
					);
				} else {
					$controller->add_control(
						$field_id,
						$field
					);
				}
			}

			$controller->end_controls_section();
		}
	}

	/**
	 * Checks if field is group control in Elementor
	 *
	 * @param array $field
	 * @return boolean
	 */
	public static function is_group_control( array $field ): bool {
		$group_controls = array(
			Group_Control_Typography::get_type(),
			Group_Control_Border::get_type(),
			Group_Control_Background::get_type(),
			Group_Control_Box_Shadow::get_type(),
			Group_Control_Flex_Item::get_type(),
			Group_Control_Image_Size::get_type(),
			Group_Control_Css_Filter::get_type(),
		);

		return in_array( $field['type'], $group_controls, true );
	}

	/**
	 * Checks if field is responsive control in Elementor
	 *
	 * @param array $field
	 * @return boolean
	 */
	public static function is_responsive_control( array $field ): bool {
		return $field['type'] === Controls_Manager::DIMENSIONS || ( isset( $field['responsive'] ) && ( $field['responsive'] === true ) );
	}

	/**
	 * Returns a wrapper class for Elementor to make compatible with Gutenberg templates
	 *
	 * @param string $slug
	 * @return string
	 */
	public static function get_wrapper_class( string $slug ): string {
		return sprintf( 'wp-block-prosandcons-%s', $slug );
	}

	/**
	 * Convert list items from Elementor REPEATER control format to gutenberg block list structure.
	 *
	 * @param array $list
	 * @return array
	 */
	public static function format_list_items( array $list ): array {
		$formatted = array();

		if ( ! empty( $list ) ) {
			foreach ( $list as $item ) {
				$formatted[] = array(
					'type'  => 'li',
					'props' => array(
						'children' => array(
							(string) $item['content'],
						),
					),
				);
			}
		}

		return $formatted;
	}

	/**
	 * Allows to apply same style from group control to multiple elements.
	 *
	 * @param array $elements
	 * @return string
	 */
	public static function select_multiple_elements( array $elements ): string {
		return implode( ', ', $elements );
	}

	/**
	 * Convert attribute values of 'true' into boolean true and 'false' into boolean false.
	 *
	 * @param array $attributes Attributes array.
	 * @return array
	 */
	public static function format_boolean_attributes( array $attributes ): array {
		foreach ( $attributes as $key => $value ) {
			if ( is_string( $value ) ) {
				if ( 'true' === $value ) {
					$attributes[ $key ] = true;
				} elseif ( 'false' === $value ) {
					$attributes[ $key ] = false;
				}
			} elseif ( is_array( $value ) ) {
				$attributes[ $key ] = self::format_boolean_attributes( $value );
			}
		}

		return $attributes;
	}

	/**
	 * Process attributes for Elementor compatibility.
	 *
	 * @param array $attributes
	 * @return array
	 */
	public static function process_attributes( array $attributes ): array {
		$attributes = self::format_boolean_attributes( $attributes );
		$attributes = affx_maybe_parse_amazon_shortcode( $attributes );

		return $attributes;
	}

	/**
	 * Get Amazon button HTML.
	 *
	 * @return string
	 */
	public static function get_amazon_button_html(): string {
		ob_start();
		include PROSCONS_PLUGIN_DIR . '/templates/elementor/amazon/button.php';
		return ob_get_clean();
	}

	public static function get_connect_all_button_html(): string {
		ob_start();
		include PROSCONS_PLUGIN_DIR . '/templates/elementor/amazon/connect-all-button.php';
		return ob_get_clean();
	}

	/**
	 * Get Search button HTML.
	 *
	 * @return string
	 */
	public static function get_search_button_html(): string {
		ob_start();
		include PROSCONS_PLUGIN_DIR . '/templates/elementor/amazon/search-button.php';
		return ob_get_clean();
	}

	/**
	 * Get Template Library button HTML.
	 *
	 * @return string
	 */
	public static function get_template_library_button_html(): string {
		ob_start();
		include PROSCONS_PLUGIN_DIR . '/templates/elementor/template-library-button.php';
		return ob_get_clean();
	}

	/**
	 * Parse Amazon list field.
	 *
	 * @param mixed $data
	 * @return mixed
	 */
	public static function parse_amazon_list_field( $data ) {
		if ( $data ) {
			if ( ! empty( $data ) && is_string( $data ) ) {
				$data = json_decode( $data, true );
			} elseif ( ! empty( $data ) && is_array( $data ) ) {
				$data = ElementorHelper::extract_list_items( $data );
			}
		}

		return $data;
	}

	/**
	 * Extract amazonAttributes value from Elementor controls array.
	 * Recursively searches through the array structure to find amazonAttributes.
	 *
	 * @param array $data The Elementor controls array
	 * @return array The amazonAttributes array or empty array if not found
	 */
	public static function get_amazon_attributes( array $data ) {
		// First, check if amazonAttributes exists at the current level
		if ( isset( $data['amazonAttributes'] ) ) {
			return $data['amazonAttributes'];
		}

		// Recursively search through all array values
		foreach ( $data as $key => $value ) {
			if ( is_array( $value ) ) {
				$result = self::get_amazon_attributes( $value );
				if ( ! empty( $result ) ) {
					return $result;
				}
			}
		}

		return array();
	}

	/**
	 * Prefix Amazon attributes with the given prefix.
	 * Handles the transformation of field names, defaults, and conditions.
	 *
	 * @param array $amazonAttributes The Amazon attributes to process
	 * @param string $prefix The prefix to apply
	 * @return array Prefixed Amazon attributes
	 */
	public static function prefix_amazon_attributes( array $amazonAttributes, string $prefix ): array {
		foreach ( $amazonAttributes as $key => $control ) {

			// Prefix the blockField name
			if ( isset( $control['blockField']['name'] ) ) {
				$amazonAttributes[ $key ]['blockField']['name'] = $prefix . $control['blockField']['name'];
			}

			// Process defaults with prefixing
			if ( isset( $control['blockField']['defaults'] ) ) {
				$prefixed_defaults = array();
				foreach ( $control['blockField']['defaults'] as $blockFieldKey => $default ) {
					$prefixed_defaults[ $prefix . $blockFieldKey ] = $default;
				}
				$amazonAttributes[ $key ]['blockField']['defaults'] = $prefixed_defaults;
			}

			// Process conditions with prefixing
			if ( isset( $control['blockField']['conditions'] ) ) {
				$prefixed_conditions = array();
				foreach ( $control['blockField']['conditions'] as $blockFieldKey => $condition ) {
					$prefixed_conditions[ $prefix . $blockFieldKey ] = $condition;
				}
				$amazonAttributes[ $key ]['blockField']['conditions'] = $prefixed_conditions;
			}
		}

		return $amazonAttributes;
	}

	/**
	 * Extract value from slider formatted data.
	 *
	 * @param mixed $data
	 * @return int|null
	 */
	public static function extract_value_from_slider_data( $data ) {
		if ( is_numeric( $data ) ) {
			return $data;
		} elseif ( is_array( $data ) ) {
			if ( isset( $data['size'] ) && is_int( $data['size'] ) ) {
				return $data['size'];
			} else {
				return null;
			}
		}

		return null;
	}
}
