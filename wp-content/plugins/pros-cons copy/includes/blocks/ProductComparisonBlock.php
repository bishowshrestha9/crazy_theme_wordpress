<?php

namespace ProsCons\Blocks;

use ProsCons\Traits\ProductComparisonRenderTrait;

defined( 'ABSPATH' ) || exit;

/**
 * ProsCons Button Block
 *
 * @package ProsCons
 */
class ProductComparisonBlock extends BaseBlock {

	use ProductComparisonRenderTrait;

	public function render( array $attributes, string $content, $block = null ): string {
		return $this->render_template( $attributes, $content );
	}
}
