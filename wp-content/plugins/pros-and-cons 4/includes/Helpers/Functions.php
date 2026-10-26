<?php
defined( 'ABSPATH' ) || exit;

function prosandcons_clean_content( $content ) {
	if ( ! is_string( $content ) || '' === trim( $content ) ) {
		return is_string( $content ) ? $content : '';
	}

	$replacements = array(
		'\\u003c' => '<',
		'\\u003C' => '<',
		'u003c'   => '<',
		'u003C'   => '<',
		'\\u003e' => '>',
		'\\u003E' => '>',
		'u003e'   => '>',
		'u003E'   => '>',
		'\\u0022' => '"',
		'u0022'   => '"',
		'\\u0026' => '&',
		'u0026'   => '&',
		'\\u0027' => "'",
		'u0027'   => "'",
		'\\u002F' => '/',
		'\\u002f' => '/',
		'u002F'   => '/',
		'u002f'   => '/',
	);

	$content = str_ireplace( array_keys( $replacements ), array_values( $replacements ), $content );

	$patterns = array(
		'/\s*data-rich-text-line-break=(["\']?)true\1/i' => '',
		'/(?>\s*<br\s*\/?>\s*)+(<\/(?:span|li|p|div)>)/i' => '$1',
		'/(<(?:span|li|p|div)[^>]*>)(?>\s*<br\s*\/?>\s*)+/i' => '$1',
		'/(?>\s*<br\s*\/?>\s*)+$/i'                      => '',
		'/^(?>\s*<br\s*\/?>\s*)+/i'                      => '',
	);

	foreach ( $patterns as $pattern => $replacement ) {
		$res = preg_replace( $pattern, $replacement, $content );
		if ( null !== $res ) {
			$content = $res;
		}
	}

	return $content;
}

function prosandcons_extract_child_items( $element, $is_root = true, int $depth = 0 ) {
	if ( $depth > 10 ) {
		return '';
	}

	$content = '';

	if ( is_array( $element ) ) {
		if ( isset( $element['content'] ) ) {
			if ( is_string( $element['content'] ) ) {
				$content .= $element['content'];
			} elseif ( is_scalar( $element['content'] ) ) {
				$content .= (string) $element['content'];
			} elseif ( is_array( $element['content'] ) ) {
				$content .= prosandcons_extract_child_items( $element['content'], false, $depth + 1 );
			}
		} elseif ( isset( $element['text'] ) ) {
			$content .= is_string( $element['text'] ) ? $element['text'] : (string) $element['text'];
		} elseif ( isset( $element['list'] ) ) {
			$content .= is_string( $element['list'] ) ? $element['list'] : (string) $element['list'];
		} elseif ( isset( $element['props']['children'] ) ) {
			if ( is_array( $element['props']['children'] ) ) {
				foreach ( $element['props']['children'] as $child ) {
					$content .= prosandcons_extract_child_items( $child, false, $depth + 1 );
				}
			} else {
				$content .= prosandcons_extract_child_items( $element['props']['children'], false, $depth + 1 );
			}
		} else {
			// In case it's a numeric array of strings or sub-elements
			foreach ( $element as $child ) {
				$content .= prosandcons_extract_child_items( $child, false, $depth + 1 );
			}
		}
	} elseif ( is_string( $element ) ) {
		$content .= $element;
	} elseif ( is_scalar( $element ) ) {
		$content .= (string) $element;
	}

	return $is_root ? prosandcons_clean_content( $content ) : $content;
}

function prosandcons_get_customization_settings( $encode = false, $parse_default = true ) {
	$defaults = array(
		'fontColor' => '#292929',
		'btnColor'  => '#00B0B0',
	);
	return $encode ? wp_json_encode( $defaults ) : $defaults;
}

