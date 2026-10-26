<?php

namespace ProsCons\Blocks;

defined( 'ABSPATH' ) || exit;

use ProsCons\Helpers\ProsCons_Helpers;
use ProsCons\Traits\SpecificationsRenderTrait;

/**
 * ProsCons Specifications Block
 *
 * @package ProsCons
 */
class SpecificationsBlock extends BaseBlock {

	use SpecificationsRenderTrait;

	public function render( array $attributes, string $content, $block = null ): string {
		return $this->render_template( $attributes, $content );
	}
}
