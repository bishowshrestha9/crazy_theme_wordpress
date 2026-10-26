<?php
namespace AffiliateLinkMigration;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Plugin Singleton Core Container Class
 */
class Plugin {

	private static ?self $instance = null;

	public Logger $logger;
	public Settings $settings;
	public Migration_Engine $migration_engine;
	public Dashboard $dashboard;
	public Admin_Menu $admin_menu;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->logger           = new Logger();
		$this->settings         = new Settings();
		$this->migration_engine = new Migration_Engine();
		$this->dashboard        = new Dashboard();
		$this->admin_menu       = new Admin_Menu();

		$this->logger->init();
		$this->settings->init();
		$this->migration_engine->init();
		$this->admin_menu->init();

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
	}

	public function enqueue_admin_assets( string $hook_suffix ): void {
		if ( false === strpos( $hook_suffix, 'affiliate-link-migration' ) ) {
			return;
		}

		wp_enqueue_style(
			'affiliate-lm-admin-css',
			AFFILIATE_LM_URL . 'assets/css/admin.css',
			array(),
			AFFILIATE_LM_VERSION
		);

		wp_enqueue_script(
			'affiliate-lm-admin-js',
			AFFILIATE_LM_URL . 'assets/js/admin.js',
			array(),
			AFFILIATE_LM_VERSION,
			true
		);

		wp_localize_script(
			'affiliate-lm-admin-js',
			'affiliateLmAdmin',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'affiliate_lm_nonce' ),
			)
		);
	}
}
