<?php
/**
 * AFFILIATE Customization Helper.
 *
 * @package AFFILIATE
 */

namespace ProsCons\Blocks;

if ( ! class_exists( 'ProsCons_Customization_Helper' ) ) {

	/**
	 * Class ProsCons_Customization_Helper.
	 */
	class ProsCons_Customization_Helper {

		/**
		 * Customization data.
		 *
		 * @var array
		 */
		private static $customization_data;

		/**
		 * Plugin URL.
		 *
		 * @var string
		 */
		private static $plugin_url;

		/**
		 * Initialize the customization helper.
		 *
		 * @return void
		 */
		public static function init() {
			self::$customization_data = get_option( 'prosandcons_customization_data', array() );
			self::$plugin_url         = plugin_dir_url( PROSCONS_PLUGIN_FILE );
		}

		/**
		 * Apply customizations.
		 *
		 * @param array $attributes
		 * @return array
		 */
		public static function apply_customizations( $attributes ) {
			foreach ( $attributes as $key => &$attribute ) {
				// Convert stdClass to array if needed
				if ( is_object( $attribute ) ) {
					$attribute = (array) $attribute;
				}
				if ( isset( $attribute['customizationKey'] ) ) {
					if ( is_array( $attribute['customizationKey'] ) ) {
						foreach ( $attribute['customizationKey'] as $path ) {
							if ( is_object( $path ) ) {
								$path = (array) $path;
							}
							$customization_value = self::get_value_by_path( self::$customization_data, explode( '.', $path['customizationPath'] ) );
							if ( $customization_value !== null ) {
								self::set_value_by_path( $attribute, explode( '.', $path['blockPath'] ), $customization_value );
							}
						}
					} else {
						$customization_value = self::get_value_by_path( self::$customization_data, explode( '.', $attribute['customizationKey'] ) );
						if ( $customization_value !== null ) {
							$attribute['default'] = $customization_value;
						}
					}
				}
				self::replace_plugin_url( $attribute );
			}
			return $attributes;
		}

		/**
		 * Get value by path.
		 *
		 * @param array $array
		 * @param array $path
		 * @return mixed
		 */
		private static function get_value_by_path( $array, $path ) {
			foreach ( $path as $key ) {
				if ( ! isset( $array[ $key ] ) ) {
					return null;
				}
				$array = $array[ $key ];
			}
			return $array;
		}

		/**
		 * Set value by path.
		 *
		 * @param array $array
		 * @param array $path
		 * @param mixed $value
		 * @return void
		 */
		private static function set_value_by_path( &$array, $path, $value ) {
			$current = &$array;
			foreach ( $path as $key ) {
				if ( ! isset( $current[ $key ] ) ) {
					$current[ $key ] = array();
				}
				$current = &$current[ $key ];
			}
			$current = $value;
		}

		/**
		 * Replace plugin URL.
		 *
		 * @param array $attribute
		 * @return void
		 */
		private static function replace_plugin_url( &$attribute ) {
			if ( is_array( $attribute ) ) {
				array_walk_recursive(
					$attribute,
					function ( &$item ) {
						if ( is_string( $item ) && strpos( $item, 'PLUGIN_URL_PLACEHOLDER' ) !== false ) {
							$item = str_replace( 'PLUGIN_URL_PLACEHOLDER', self::$plugin_url, $item );
						}
					}
				);
			} elseif ( is_string( $attribute ) && strpos( $attribute, 'PLUGIN_URL_PLACEHOLDER' ) !== false ) {
				$attribute = str_replace( 'PLUGIN_URL_PLACEHOLDER', self::$plugin_url, $attribute );
			}
		}

		/**
		 * Get specific customization value from the customization settings data.
		 * Direct method without initializing the class with caching support.
		 *
		 * @param string $keys_string Key of required value. Multiple keys can be separated by a dot. E.g. 'typography.family'
		 * @param mixed $default The default fallback value.
		 * @return mixed The value.
		 */
		public static function get_value( $keys_string, $default = null ) {
			$data  = affx_get_customization_settings( false, false );
			$keys  = explode( '.', $keys_string );
			$value = self::get_value_by_path( $data, $keys );

			return null === $value ? $default : $value;
		}
	}

	// Initialize the customization helper
	ProsCons_Customization_Helper::init();
}
