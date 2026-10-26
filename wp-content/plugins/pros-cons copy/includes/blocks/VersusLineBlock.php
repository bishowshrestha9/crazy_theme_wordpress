<?php

namespace ProsCons\Blocks;

defined( 'ABSPATH' ) || exit;

use ProsCons\Traits\VersusLineRenderTrait;

/**
 * ProsCons Versus Line Block
 *
 * @package ProsCons
 */
class VersusLineBlock extends BaseBlock {

	use VersusLineRenderTrait;

	public function render( array $attributes, string $content, $block = null ): string {
		return $this->render_template( $attributes, $content );
	}
}
