<?php

namespace ProsCons\Blocks;

use ProsCons\Traits\NoticeRenderTrait;
use ProsCons\Helpers\ProsCons_Helpers;

defined( 'ABSPATH' ) || exit;

/**
 * ProsCons Notice Block
 *
 * @package ProsCons
 */
class NoticeBlock extends BaseBlock {

	use NoticeRenderTrait;

	public function render( array $attributes, string $content, $block = null ): string {
		return $this->render_template( $attributes, $content );
	}
}
