<?php

namespace ProsCons\Blocks;

defined( 'ABSPATH' ) || exit;

/**
 * Base class for all Gutenberg blocks
 *
 * @package ProsCons
 */
abstract class BaseBlock {

	/**
	 * Blocks assets path
	 *
	 * @var string
	 */
	protected $blocks_path = '/build/blocks/';

	/**
	 * PHP template path
	 *
	 * @var string
	 */
	protected $template_path = PROSCONS_PLUGIN_DIR . '/templates/blocks/';

	/**
	 * Elementor Flag.
	 *
	 * @var bool
	 */
	protected const IS_ELEMENTOR = false;

	/**
	 * Hook actions and initiates the block
	 */
	public function __construct() {
		$this->blocks_path .= $this->get_slug() . '/';

		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
	}

	/**
	 * Register block in Gutenberg
	 *
	 * @return void
	 */
	public function register_block(): void {
		register_block_type_from_metadata(
			PROSCONS_PLUGIN_DIR . $this->blocks_path,
			array(
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	/**
	 * Enqueue assets used for rendering the block in editor context
	 *
	 * @return void
	 */
	public function enqueue_editor_assets(): void {
		wp_enqueue_script( 'prosandcons-blocks-' . $this->get_slug(), plugin_dir_url( PROSCONS_PLUGIN_FILE ) . $this->blocks_path . '/index.js', array( 'prosandcons' ), PROSCONS_VERSION, true );
	}

	/**
	 * Get frontend template path
	 *
	 * @return string
	 */
	public function get_template_path(): string {
		return $this->template_path . $this->get_slug() . '.php';
	}

	/**
	 * Fix malformed Unicode escapes in content
	 * WordPress saves HTML in block attributes with Unicode escapes, but sometimes
	 * they appear without backslashes (u003c instead of \u003c)
	 *
	 * @param mixed $value The value to fix
	 * @return mixed The fixed value
	 */
	protected function fix_unicode_escapes( $value ) {
		if ( ! is_string( $value ) || empty( $value ) || strpos( $value, 'u003' ) === false ) {
			return $value;
		}

		// Add backslashes and decode
		$fixed = str_replace(
			array( 'u003c', 'u003e', 'u0026', 'u0022', 'u0027' ),
			array( '\u003c', '\u003e', '\u0026', '\u0022', '\u0027' ),
			$value
		);

		$decoded = json_decode( '"' . $fixed . '"' );

		return json_last_error() === JSON_ERROR_NONE ? $decoded : $value;
	}

	/**
	 * Parse block attributes
	 *
	 * @param array $attributes
	 * @return array
	 */
	protected function parse_attributes( array $attributes ): array {
		$fields     = $this->get_fields();
		$attributes = ProsCons_Customization_Helper::apply_customizations( $attributes );
		$attributes = wp_parse_args( $attributes, $fields );

		// Fix Unicode escapes in all string attributes
		foreach ( $attributes as $key => $value ) {
			if ( is_string( $value ) ) {
				$attributes[ $key ] = $this->fix_unicode_escapes( $value );
			} elseif ( is_array( $value ) ) {
				// Recursively fix arrays (for nested attributes)
				$attributes[ $key ] = $this->fix_unicode_array( $value );
			}
		}

		return $attributes;
	}

	/**
	 * Recursively fix Unicode escapes in array values
	 *
	 * @param array $array The array to fix
	 * @return array The fixed array
	 */
	private function fix_unicode_array( array $array ): array {
		foreach ( $array as $key => $value ) {
			if ( is_string( $value ) ) {
				$array[ $key ] = $this->fix_unicode_escapes( $value );
			} elseif ( is_array( $value ) ) {
				$array[ $key ] = $this->fix_unicode_array( $value );
			}
		}
		return $array;
	}

	/**
	 * Get block slug to identify template, metadata and assets path
	 *
	 * @return string
	 */
	abstract protected function get_slug(): string;

	/**
	 * Extract block attributes and render from template
	 *
	 * @param array       $attributes Block attributes.
	 * @param string      $content    Block content.
	 * @param object|null $block      Block instance.
	 * @return string
	 */
	abstract public function render( array $attributes, string $content, $block = null ): string;

	/**
	 * Returns array of fields, organized by key and default value pair (key => default_value)
	 *
	 * @return array
	 */
	abstract protected function get_fields(): array;

	/**
	 * Enqueue styles
	 *
	 * @return void
	 */
	protected function enqueue_styles() {
		wp_enqueue_style(
			'prosandcons-button-style',
			PROSCONS_PLUGIN_URL . 'assets/css/buttons.css',
			array(),
			PROSCONS_VERSION
		);
	}

	protected function is_edit_mode() {
		return false;
	}
}
