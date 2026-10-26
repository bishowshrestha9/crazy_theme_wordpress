<?php
namespace ProsAndCons\Helpers;

defined( 'ABSPATH' ) || exit;

class HoverStyles {

	private const DEVICES = array( 'desktop', 'tablet', 'mobile' );

	public static function merge_selector( array &$bucket, string $selector, array $styles ): void {
		if ( empty( $styles ) ) {
			return;
		}
		$bucket[ $selector ] = array_merge( $bucket[ $selector ] ?? array(), $styles );
	}

	public static function is_responsive( $value ): bool {
		return is_array( $value ) && array_key_exists( 'desktop', $value );
	}

	public static function to_px_value( $value ) {
		if ( is_numeric( $value ) && ! is_string( $value ) ) {
			return $value . 'px';
		}
		if ( is_array( $value ) ) {
			return array_map( array( self::class, 'to_px_value' ), $value );
		}
		return $value;
	}

	public static function get_transition( array $extras = array() ): string {
		$properties = array_merge( array( 'color', 'background-color', 'border-color', 'box-shadow', 'border-radius' ), $extras );
		return implode(
			', ',
			array_map(
				static function ( string $property ): string {
					return $property . ' .15s ease';
				},
				$properties
			)
		);
	}

	public static function get_spacing_styles( $spacing, string $device, string $property ): array {
		$sides = is_array( $spacing ) ? ( $spacing[ $device ] ?? null ) : null;
		if ( ! is_array( $sides ) ) {
			return array();
		}
		$styles = array();
		foreach ( array( 'top', 'left', 'right', 'bottom' ) as $side ) {
			$value = $sides[ $side ] ?? null;
			if ( ( is_string( $value ) && '' !== $value ) || ( is_numeric( $value ) && 0.0 !== (float) $value ) ) {
				$styles[ $property . '-' . $side ] = $value;
			}
		}
		return $styles;
	}

	public static function get_radius_value( $radius, string $device ): string {
		$sides = is_array( $radius ) ? ( $radius[ $device ] ?? null ) : null;
		if ( ! is_array( $sides ) || empty( $sides ) ) {
			return '';
		}
		$values    = array();
		$has_value = false;
		foreach ( array( 'top', 'right', 'bottom', 'left' ) as $side ) {
			$value = $sides[ $side ] ?? '';
			$value = is_string( $value ) || is_numeric( $value ) ? (string) $value : '';
			$value = '' === $value ? '0' : $value;
			if ( (float) $value > 0 ) {
				$has_value = true;
			}
			$values[] = $value;
		}
		return $has_value ? implode( ' ', $values ) : '';
	}

	public static function get_border_styles( $border, bool $include_color = true ): array {
		if ( ! is_array( $border ) || empty( $border['style'] ) || ! is_string( $border['style'] ) || 'none' === $border['style'] ) {
			return array();
		}
		$styles = array( 'border-style' => $border['style'] );
		if ( $include_color && ! empty( $border['color']['color'] ) && is_string( $border['color']['color'] ) ) {
			$color = Helpers::sanitize_color( $border['color']['color'] );
			if ( '' !== $color ) {
				$styles['border-color'] = $color;
			}
		}
		if ( ! empty( $border['width'] ) && is_numeric( $border['width'] ) ) {
			$styles['border-width'] = $border['width'] . 'px';
		}
		return $styles;
	}

	public static function get_shadow_styles( $shadow ): array {
		if ( ! is_array( $shadow ) || true !== ( $shadow['enable'] ?? false ) ) {
			return array();
		}
		return array( 'box-shadow' => Helpers::get_css_boxshadow( $shadow ) );
	}
}
