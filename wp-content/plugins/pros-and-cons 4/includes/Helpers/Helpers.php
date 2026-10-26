<?php
namespace ProsAndCons\Helpers;

defined( 'ABSPATH' ) || exit;

class Helpers {

	public static function sanitize_color( $color, string $default = '' ): string {
		if ( ! is_string( $color ) || '' === trim( $color ) ) {
			return $default;
		}
		$color = trim( $color );
		if ( preg_match( '/^#([0-9a-fA-F]{3,4}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/', $color ) ) {
			return $color;
		}
		if ( preg_match( '/^rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*(?:,\s*(?:0|1|0?\.\d+)\s*)?\)$/i', $color ) ) {
			return $color;
		}
		if ( preg_match( '/^hsla?\(\s*\d+\s*,\s*\d+%\s*,\s*\d+%\s*(?:,\s*(?:0|1|0?\.\d+)\s*)?\)$/i', $color ) ) {
			return $color;
		}
		if ( 'transparent' === strtolower( $color ) ) {
			return 'transparent';
		}
		return $default;
	}

	public static function sanitize_block_id( $id, string $default = '' ): string {
		if ( ! is_string( $id ) || ! preg_match( '/^[a-zA-Z0-9_-]{1,64}$/', $id ) ) {
			return $default;
		}
		return $id;
	}

	public static function generate_css( array $selectors, string $id_prefix ): string {
		$css          = '';
		$clean_prefix = preg_replace( '/[^a-zA-Z0-9_-]/', '', $id_prefix );
		foreach ( $selectors as $selector => $properties ) {
			if ( empty( $properties ) || ! is_array( $properties ) ) {
				continue;
			}
			$clean_props = array();
			foreach ( $properties as $prop => $val ) {
				if ( null !== $val && '' !== $val ) {
					$clean_prop = preg_replace( '/[^a-zA-Z0-9_-]/', '', (string) $prop );
					$clean_val  = (string) $val;
					do {
						$prev      = $clean_val;
						$clean_val = preg_replace( '/[;{}]|<\/?style[^>]*>/i', '', $clean_val );
					} while ( null !== $clean_val && $clean_val !== $prev );

					if ( '' !== $clean_prop && null !== $clean_val && '' !== trim( $clean_val ) ) {
						$clean_props[] = "{$clean_prop}: {$clean_val};";
					}
				}
			}
			if ( ! empty( $clean_props ) ) {
				$clean_selector = (string) $selector;
				do {
					$prev           = $clean_selector;
					$clean_selector = preg_replace( '/[;{}]|<\/?style[^>]*>/i', '', $clean_selector );
				} while ( null !== $clean_selector && $clean_selector !== $prev );

				if ( null !== $clean_selector && '' !== trim( $clean_selector ) ) {
					$full_selector = '#' . $clean_prefix . ' ' . $clean_selector;
					$css          .= $full_selector . ' { ' . implode( ' ', $clean_props ) . ' } ';
				}
			}
		}
		return $css;
	}

	public static function get_css_value( $value, string $unit = 'px' ): string {
		if ( empty( $value ) && '0' !== (string) $value ) {
			return '';
		}
		if ( is_numeric( $value ) ) {
			return $value . $unit;
		}
		$clean_val = (string) $value;
		do {
			$prev      = $clean_val;
			$clean_val = preg_replace( '/[;{}]|<\/?style[^>]*>/i', '', $clean_val );
		} while ( null !== $clean_val && $clean_val !== $prev );

		return null !== $clean_val ? $clean_val : '';
	}

	public static function get_css_boxshadow( array $shadow ): string {
		if ( empty( $shadow['enable'] ) ) {
			return 'none';
		}
		$h         = self::get_css_value( $shadow['h_offset'] ?? 0 );
		$v         = self::get_css_value( $shadow['v_offset'] ?? 0 );
		$blur      = self::get_css_value( $shadow['blur'] ?? 0 );
		$spread    = self::get_css_value( $shadow['spread'] ?? 0 );
		$raw_color = $shadow['color']['color'] ?? 'rgba(0,0,0,0.1)';
		$color     = self::sanitize_color( $raw_color, 'rgba(0,0,0,0.1)' );
		$inset     = ! empty( $shadow['inset'] ) ? 'inset ' : '';

		return "{$inset}{$h} {$v} {$blur} {$spread} {$color}";
	}

	public static function get_fontweight_variation( string $variation ): string {
		if ( strlen( $variation ) >= 2 ) {
			$weight_num = (int) $variation[1] * 100;
			return (string) $weight_num;
		}
		return '400';
	}

	public static function get_font_style( string $variation ): string {
		if ( isset( $variation[0] ) && 'i' === $variation[0] ) {
			return 'italic';
		}
		return 'normal';
	}

	public static function validate_tag( string $tag, string $default = 'div' ): string {
		$allowed = array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div', 'ul', 'ol', 'li' );
		return in_array( strtolower( $tag ), $allowed, true ) ? strtolower( $tag ) : $default;
	}

	public static function render_list( array $args ): string {
		$rawListType      = is_string( $args['listType'] ?? null ) ? $args['listType'] : 'unordered';
		$rawUnorderedType = is_string( $args['unorderedType'] ?? null ) ? $args['unorderedType'] : 'icon';

		$listType      = in_array( $rawListType, array( 'ordered', 'unordered' ), true ) ? $rawListType : 'unordered';
		$unorderedType = in_array( $rawUnorderedType, array( 'icon', 'bullet' ), true ) ? $rawUnorderedType : 'icon';
		$listItems     = isset( $args['listItems'] ) && is_array( $args['listItems'] ) ? $args['listItems'] : array();
		$iconName      = is_string( $args['iconName'] ?? null ) ? $args['iconName'] : '';

		$listTag        = 'ordered' === $listType ? 'ol' : 'ul';
		$wrapperClasses = array( 'proscons-list', 'proscons-list-type-' . $listType );
		if ( 'unordered' === $listType && 'icon' === $unorderedType ) {
			$wrapperClasses[] = 'proscons-icon-list';
		}

		ob_start();
		include PROS_CONS_PLUGIN_DIR . 'templates/components/list.php';
		return ob_get_clean();
	}
}
