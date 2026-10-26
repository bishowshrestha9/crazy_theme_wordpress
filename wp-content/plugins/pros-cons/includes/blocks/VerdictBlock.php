<?php

namespace ProsCons\Blocks;

use ProsCons\Traits\VerdictRenderTrait;

defined( 'ABSPATH' ) || exit;

/**
 * ProsCons Verdict Block
 *
 * @package ProsCons
 */
class VerdictBlock extends BaseBlock {

	use VerdictRenderTrait;

	/**
	 * Gutenberg block render.
	 *
	 * @param array       $attributes Block attributes.
	 * @param string      $content    Block content.
	 * @param object|null $block      Block instance.
	 * @return string
	 */
	public function render( array $attributes, string $content, $block = null ): string {
		return $this->render_template( $attributes, $content );
	}
}
