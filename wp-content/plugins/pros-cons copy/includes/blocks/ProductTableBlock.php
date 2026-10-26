<?php

namespace ProsCons\Blocks;

use ProsCons\Traits\ProductTableRenderTrait;

defined( 'ABSPATH' ) || exit;

use ProsCons\Helpers\ProsCons_Helpers;

/**
 * ProsCons Product Table Block
 *
 * @package ProsCons
 */
class ProductTableBlock extends BaseBlock {

	use ProductTableRenderTrait;

	public function render( array $attributes, string $content, $block = null ): string {
		return $this->render_template( $attributes, $content );
	}
}
