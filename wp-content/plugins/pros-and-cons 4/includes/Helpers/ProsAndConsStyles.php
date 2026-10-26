<?php
namespace ProsAndCons\Helpers;

defined( 'ABSPATH' ) || exit;

class ProsAndConsStyles extends BlockStylesBase {

	protected static function css_id_prefix(): string {
		return 'proscons-pros-cons-style-';
	}

	public static function get_selectors( array $attr ): array {
		$selectors = array();

		$prosBgColor     = Helpers::sanitize_color( $attr['prosBgColor'] ?? '#24B644', '#24B644' );
		$consBgColor     = Helpers::sanitize_color( $attr['consBgColor'] ?? '#F13A3A', '#F13A3A' );
		$prosTextColor   = Helpers::sanitize_color( $attr['prosTextColor'] ?? '#ffffff', '#ffffff' );
		$consTextColor   = Helpers::sanitize_color( $attr['consTextColor'] ?? '#ffffff', '#ffffff' );
		$prosListBgColor = Helpers::sanitize_color( $attr['prosListBgColor'] ?? '#F5FFF8', '#F5FFF8' );
		$consListBgColor = Helpers::sanitize_color( $attr['consListBgColor'] ?? '#FFF5F5', '#FFF5F5' );
		$prosIconColor   = Helpers::sanitize_color( $attr['prosIconColor'] ?? '#24B644', '#24B644' );
		$consIconColor   = Helpers::sanitize_color( $attr['consIconColor'] ?? '#F13A3A', '#F13A3A' );

		$selectors['.proscons-block-pros'] = array(
			'background-color' => $prosBgColor,
			'color'            => $prosTextColor,
		);

		$selectors['.proscons-block-cons'] = array(
			'background-color' => $consBgColor,
			'color'            => $consTextColor,
		);

		$selectors['.proscons-block-pros .proscons-title'] = array(
			'color' => $prosTextColor,
		);

		$selectors['.proscons-block-cons .proscons-title'] = array(
			'color' => $consTextColor,
		);

		$selectors['.proscons-pros-inner .proscons-pros'] = array(
			'background-color' => $prosListBgColor,
		);

		$selectors['.proscons-cons-inner .proscons-cons'] = array(
			'background-color' => $consListBgColor,
		);

		$selectors['.proscons-pros-inner ul.proscons-list li::before'] = array(
			'color'        => $prosIconColor,
			'border-color' => $prosIconColor,
		);

		$selectors['.proscons-cons-inner ul.proscons-list li::before'] = array(
			'color'        => $consIconColor,
			'border-color' => $consIconColor,
		);

		return $selectors;
	}

	public static function get_tabletselectors( array $attr ): array {
		return array();
	}

	public static function get_mobileselectors( array $attr ): array {
		return array();
	}
}
