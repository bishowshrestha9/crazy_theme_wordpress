<?php
namespace ProsAndCons\Blocks;

use ProsAndCons\Traits\ProsAndConsRenderTrait;

defined( 'ABSPATH' ) || exit;

class ProsAndConsBlock extends BaseBlock {
	use ProsAndConsRenderTrait;

	public function render_block( array $attributes, string $content ): string {
		$attributes = $this->parse_attributes( $attributes );
		return $this->render_template( $attributes, $content );
	}
}
