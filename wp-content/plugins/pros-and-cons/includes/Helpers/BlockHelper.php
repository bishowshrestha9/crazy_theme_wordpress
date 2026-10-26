<?php
namespace ProsAndCons\Helpers;

defined( 'ABSPATH' ) || exit;

class BlockHelper {

	public static function get_block_css( $blockname, $attr, $id ) {
		$block_css = array( 'desktop' => '', 'tablet' => '', 'mobile' => '' );
		if ( 'Pros and Cons' === $blockname || 'pros-and-cons' === $blockname ) {
			$block_css = ProsAndConsStyles::block_css( $attr, $id );
		}
		return $block_css;
	}
}
