<?php
namespace ProsAndCons\Blocks;

defined( 'ABSPATH' ) || exit;

abstract class BaseBlock {

	public function __construct() {
		$this->register_block();
	}

	abstract public function get_slug(): string;

	public function get_fields(): array {
		return array();
	}

	public function register_block(): void {
		$block_dir = PROS_CONS_PLUGIN_DIR . 'build/blocks/' . $this->get_slug();
		if ( file_exists( $block_dir . '/block.json' ) ) {
			register_block_type_from_metadata(
				$block_dir,
				array(
					'render_callback' => array( $this, 'render_block' ),
				)
			);
		}
	}

	public function parse_attributes( array $attributes ): array {
		$defaults = array();
		foreach ( $this->get_fields() as $key => $field ) {
			if ( isset( $field['default'] ) ) {
				$defaults[ $key ] = $field['default'];
			}
		}

		return wp_parse_args( $attributes, $defaults );
	}

	abstract public function render_block( array $attributes, string $content ): string;
}
