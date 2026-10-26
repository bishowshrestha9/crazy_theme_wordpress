<?php
namespace ProsAndCons;

defined( 'ABSPATH' ) || exit;

class Frontend {

	public function __construct() {
		$this->init_hooks();
	}

	public function init_hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp', array( $this, 'generate_assets' ), 99 );
		add_action( 'wp_head', array( $this, 'generate_stylesheet' ), 80 );
	}

	public function generate_assets() {
		$m = new FontsManager();
		$m->generate_assets();
	}

	public function enqueue_styles() {
		if ( has_block( 'prosandcons/pros-and-cons' ) ) {
			wp_enqueue_style(
				'pros-and-cons-public',
				PROS_CONS_PLUGIN_URL . 'build/blocks/pros-and-cons/style-index.css',
				array(),
				PROS_CONS_VERSION
			);
		}
	}

	public function generate_stylesheet() {
		$m          = new FontsManager();
		$stylesheet = $m::$stylesheet;

		if ( is_null( $stylesheet ) || '' === trim( (string) $stylesheet ) ) {
			return;
		}

		$clean_stylesheet = preg_replace( '/<\/\s*style\s*>/i', '', $stylesheet );

		ob_start();
		?>
		<style id="pros-and-cons-styles-frontend"><?php echo $clean_stylesheet; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
		<?php
		ob_end_flush();
	}
}
