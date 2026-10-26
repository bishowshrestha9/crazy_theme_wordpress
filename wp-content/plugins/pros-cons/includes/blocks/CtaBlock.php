<?php

namespace ProsCons\Blocks;

defined( 'ABSPATH' ) || exit;

use ProsCons\Traits\CtaRenderTrait;

/**
 * ProsCons CTA Block
 *
 * @package ProsCons
 */
class CtaBlock extends BaseBlock {

	use CtaRenderTrait;

	public function render( array $attributes, string $content, $block = null ): string {
		return $this->render_template( $attributes, $content );
	}
}
