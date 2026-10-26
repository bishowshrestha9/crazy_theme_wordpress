<?php

namespace ProsCons\Blocks;

use ProsCons\Traits\ButtonRenderTrait;

defined( 'ABSPATH' ) || exit;

/**
 * ProsCons Button Block
 *
 * @package ProsCons
 */
class ButtonBlock extends BaseBlock {

	use ButtonRenderTrait;

	protected function get_slug(): string {
		return 'buttons';
	}

	protected function get_fields(): array {
		return $this->get_button_fields();
	}

	public function render( array $attributes, string $content, $block = null ): string {
		$attributes = $this->parse_attributes( $attributes );

		if ( ! is_null( $block ) ) {
			$context                         = $block->context;
			$attributes['parent_attributes'] = array(
				'edFullBlockLink' => $context['prosandcons/edFullBlockLink'] ?? false,
				'blockName'       => $context['prosandcons/parentBlockName'] ?? 'buttons',
			);
		}

		return $this->render_button_template( $attributes, $content );
	}
}
