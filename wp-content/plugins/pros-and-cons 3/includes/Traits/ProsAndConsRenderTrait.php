<?php
namespace ProsAndCons\Traits;

use ProsAndCons\Helpers\Helpers;

defined( 'ABSPATH' ) || exit;

trait ProsAndConsRenderTrait {

	public function get_slug(): string {
		return 'pros-and-cons';
	}

	public function get_fields(): array {
		return array(
			'block_id'           => array( 'type' => 'string' ),
			'layoutStyle'        => array( 'type' => 'string', 'default' => 'layout-type-1' ),
			'prosIconStatus'     => array( 'type' => 'boolean', 'default' => true ),
			'consIconStatus'     => array( 'type' => 'boolean', 'default' => true ),
			'prosTitle'          => array( 'type' => 'string', 'default' => 'Pros' ),
			'consTitle'          => array( 'type' => 'string', 'default' => 'Cons' ),
			'prosContentType'    => array( 'type' => 'string', 'default' => 'list' ),
			'consContentType'    => array( 'type' => 'string', 'default' => 'list' ),
			'prosListType'       => array( 'type' => 'string', 'default' => 'unordered' ),
			'prosUnorderedType'  => array( 'type' => 'string', 'default' => 'icon' ),
			'consListType'       => array( 'type' => 'string', 'default' => 'unordered' ),
			'consUnorderedType'  => array( 'type' => 'string', 'default' => 'icon' ),
			'prosIconClass'      => array( 'type' => 'string', 'default' => 'far fa-circle-check' ),
			'consIconClass'      => array( 'type' => 'string', 'default' => 'far fa-circle-xmark' ),
			'prosBgColor'        => array( 'type' => 'string', 'default' => '#24B644' ),
			'consBgColor'        => array( 'type' => 'string', 'default' => '#F13A3A' ),
			'prosTextColor'      => array( 'type' => 'string', 'default' => '#ffffff' ),
			'consTextColor'      => array( 'type' => 'string', 'default' => '#ffffff' ),
			'prosListBgColor'    => array( 'type' => 'string', 'default' => '#F5FFF8' ),
			'consListBgColor'    => array( 'type' => 'string', 'default' => '#FFF5F5' ),
			'prosIconColor'      => array( 'type' => 'string', 'default' => '#24B644' ),
			'consIconColor'      => array( 'type' => 'string', 'default' => '#F13A3A' ),
		);
	}

	public function render_template( array $attributes, string $content ): string {
		$block_id = Helpers::sanitize_block_id( $attributes['block_id'] ?? '' );

		$allowed_layouts = array( 'layout-type-1', 'layout-type-2', 'layout-type-3', 'layout-type-4' );
		$layoutStyle     = $attributes['layoutStyle'] ?? 'layout-type-1';
		$layoutStyle     = in_array( $layoutStyle, $allowed_layouts, true ) ? $layoutStyle : 'layout-type-1';

		$prosIconStatus = $attributes['prosIconStatus'] ?? true;
		$consIconStatus = $attributes['consIconStatus'] ?? true;

		$prosTitle = $attributes['prosTitle'] ?? __( 'Pros', 'pros-and-cons' );
		$consTitle = $attributes['consTitle'] ?? __( 'Cons', 'pros-and-cons' );

		$prosIconClass = $attributes['prosIconClass'] ?? 'far fa-circle-check';
		$consIconClass = $attributes['consIconClass'] ?? 'far fa-circle-xmark';

		$prosList = '';
		if ( ! empty( $attributes['prosListItems'] ) && is_array( $attributes['prosListItems'] ) ) {
			$prosList = Helpers::render_list(
				array(
					'listType'      => $attributes['prosListType'] ?? 'unordered',
					'unorderedType' => $attributes['prosUnorderedType'] ?? 'icon',
					'listItems'     => $attributes['prosListItems'],
					'iconName'      => $prosIconClass,
				)
			);
		} elseif ( ! empty( $attributes['prosListContent'] ) ) {
			$prosList = wp_kses_post( prosandcons_clean_content( (string) $attributes['prosListContent'] ) );
		}

		$consList = '';
		if ( ! empty( $attributes['consListItems'] ) && is_array( $attributes['consListItems'] ) ) {
			$consList = Helpers::render_list(
				array(
					'listType'      => $attributes['consListType'] ?? 'unordered',
					'unorderedType' => $attributes['consUnorderedType'] ?? 'icon',
					'listItems'     => $attributes['consListItems'],
					'iconName'      => $consIconClass,
				)
			);
		} elseif ( ! empty( $attributes['consListContent'] ) ) {
			$consList = wp_kses_post( prosandcons_clean_content( (string) $attributes['consListContent'] ) );
		}

		ob_start();
		include PROS_CONS_PLUGIN_DIR . 'templates/blocks/pros-and-cons.php';
		return ob_get_clean();
	}
}
