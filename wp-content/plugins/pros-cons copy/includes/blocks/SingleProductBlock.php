<?php

namespace ProsCons\Blocks;

defined( 'ABSPATH' ) || exit;

use ProsCons\Helpers\ProsCons_Helpers;
use ProsCons\Traits\SingleProductRenderTrait;

/**
 * ProsCons Single Product Block
 *
 * @package ProsCons
 */
class SingleProductBlock extends BaseBlock {

	use SingleProductRenderTrait;

	protected function get_slug(): string {
		return 'single-product';
	}

	public function render( array $attributes, string $content, $block = null ): string {
		return $this->render_sp_template( $attributes, $content );
	}
}
