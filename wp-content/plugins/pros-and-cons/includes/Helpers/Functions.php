<?php
defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'affx_extract_child_items' ) ) {
	function affx_extract_child_items( $element ) {
		$content = '';

		if ( is_array( $element ) ) {
			if ( isset( $element['content'] ) ) {
				$content .= $element['content'];
			} elseif ( isset( $element['props']['children'] ) && is_array( $element['props']['children'] ) ) {
				foreach ( $element['props']['children'] as $child ) {
					$content .= affx_extract_child_items( $child );
				}
			}
		} elseif ( is_string( $element ) ) {
			$content .= $element;
		}

		return $content;
	}
}

if ( ! function_exists( 'affx_get_customization_settings' ) ) {
	function affx_get_customization_settings( $encode = false, $parse_default = true ) {
		$defaults = array(
			'fontColor' => '#292929',
			'btnColor'  => '#00B0B0',
		);
		return $encode ? wp_json_encode( $defaults ) : $defaults;
	}
}
