<?php
namespace ProsAndCons;

use ProsAndCons\Helpers\BlockHelper;
use ProsAndCons\Helpers\Helpers;

defined( 'ABSPATH' ) || exit;

class FontsManager {

	public static $page_blocks;

	public static $stylesheet;

	public function get_assets( $blocks ) {
		$desktop = '';
		$tablet  = '';
		$mobile  = '';

		$tab_styling_css = '';
		$mob_styling_css = '';

		foreach ( $blocks as $block ) {
			if ( is_array( $block ) ) {
				if ( empty( $block['blockName'] ) ) {
					continue;
				}

				if ( 'prosandcons/pros-and-cons' === $block['blockName'] || 'affiliatex/pros-and-cons' === $block['blockName'] ) {
					$blockattr = $block['attrs'] ?? array();
					$block_id  = Helpers::sanitize_block_id( $blockattr['block_id'] ?? '' );

					$css = BlockHelper::get_block_css( 'Pros and Cons', $blockattr, $block_id );

					if ( isset( $css['desktop'] ) ) {
						$desktop .= $css['desktop'];
						$tablet  .= $css['tablet'];
						$mobile  .= $css['mobile'];
					}
				}

				if ( ! empty( $block['innerBlocks'] ) ) {
					$inner_assets = $this->get_assets( $block['innerBlocks'] );
					$desktop     .= $inner_assets['css'];
				}
			}
		}

		if ( ! empty( $tablet ) ) {
			$tab_styling_css .= '@media only screen and (max-width: ' . PROS_CONS_TABLET_BREAKPOINT . 'px) {';
			$tab_styling_css .= $tablet;
			$tab_styling_css .= '}';
		}

		if ( ! empty( $mobile ) ) {
			$mob_styling_css .= '@media only screen and (max-width: ' . PROS_CONS_MOBILE_BREAKPOINT . 'px) {';
			$mob_styling_css .= $mobile;
			$mob_styling_css .= '}';
		}

		return array(
			'css' => $desktop . $tab_styling_css . $mob_styling_css,
		);
	}

	public function generate_assets() {
		if ( is_single() || is_page() || is_404() ) {
			global $post;
			if ( ! is_object( $post ) || ! isset( $post->ID ) ) {
				return;
			}

			if ( has_blocks( $post->ID ) && isset( $post->post_content ) ) {
				$blocks            = parse_blocks( $post->post_content );
				self::$page_blocks = $blocks;

				if ( ! is_array( $blocks ) || empty( $blocks ) ) {
					return;
				}

				$assets            = $this->get_assets( $blocks );
				self::$stylesheet .= $assets['css'];
			}
		}
	}
}
