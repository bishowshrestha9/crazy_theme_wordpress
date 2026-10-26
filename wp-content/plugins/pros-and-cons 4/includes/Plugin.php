<?php
namespace ProsAndCons;

use ProsAndCons\Blocks\ProsAndConsBlock;

defined( 'ABSPATH' ) || exit;

final class Plugin {

	protected static $instance = null;

	public $frontend;

	public $block;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		$this->init_hooks();
		$this->frontend = new Frontend();
	}

	public function init_hooks() {
		add_action( 'init', array( $this, 'init' ) );
	}

	public function init() {
		$this->load_plugin_textdomain();
		$this->block = new ProsAndConsBlock();
	}

	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'pros-and-cons', false, dirname( plugin_basename( PROS_CONS_PLUGIN_FILE ) ) . '/languages' );
	}
}
