<?php
namespace AffiliateJourneyTracker;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin Menu Class
 *
 * Registers the dedicated top-level admin menu and child pages for Affiliate Journey Tracker.
 */
class Admin_Menu {

	public function init(): void {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
	}

	public function register_menu(): void {
		// Top-Level Parent Menu
		add_menu_page(
			__( 'Affiliate Journey Tracker', 'affiliate-journey-tracker' ),
			__( 'Affiliate Journey', 'affiliate-journey-tracker' ),
			'manage_options',
			'affiliate-journey-tracker',
			array( $this, 'render_dashboard_page' ),
			'dashicons-randomize',
			30
		);

		// Submenu 1: Dashboard (Health Check Widget)
		add_submenu_page(
			'affiliate-journey-tracker',
			__( 'Dashboard - Affiliate Journey Tracker', 'affiliate-journey-tracker' ),
			__( 'Dashboard', 'affiliate-journey-tracker' ),
			'manage_options',
			'affiliate-journey-tracker',
			array( $this, 'render_dashboard_page' )
		);

		// Submenu 2: Settings (Forwarding Settings)
		add_submenu_page(
			'affiliate-journey-tracker',
			__( 'Settings - Affiliate Journey Tracker', 'affiliate-journey-tracker' ),
			__( 'Settings', 'affiliate-journey-tracker' ),
			'manage_options',
			'affiliate-journey-settings',
			array( $this, 'render_settings_page' )
		);

		// Submenu 3: Forwarding Tester (Test Mode Simulator)
		add_submenu_page(
			'affiliate-journey-tracker',
			__( 'Forwarding Tester - Affiliate Journey Tracker', 'affiliate-journey-tracker' ),
			__( 'Forwarding Tester', 'affiliate-journey-tracker' ),
			'manage_options',
			'affiliate-journey-tester',
			array( $this, 'render_tester_page' )
		);

		// Submenu 4: Migration (Post Meta Migration Tool)
		add_submenu_page(
			'affiliate-journey-tracker',
			__( 'Migration - Affiliate Journey Tracker', 'affiliate-journey-tracker' ),
			__( 'Migration', 'affiliate-journey-tracker' ),
			'manage_options',
			'affiliate-journey-migration',
			array( $this, 'render_migration_page' )
		);

		// Submenu 5: Debug (Forwarding Debug)
		add_submenu_page(
			'affiliate-journey-tracker',
			__( 'Debug - Affiliate Journey Tracker', 'affiliate-journey-tracker' ),
			__( 'Debug', 'affiliate-journey-tracker' ),
			'manage_options',
			'affiliate-journey-debug',
			array( $this, 'render_debug_page' )
		);
	}

	public function render_dashboard_page(): void {
		Plugin::get_instance()->health_dashboard->render_dashboard_page();
	}

	public function render_settings_page(): void {
		Plugin::get_instance()->settings->render_settings_page();
	}

	public function render_tester_page(): void {
		Plugin::get_instance()->forwarding_tester->render_tester_page();
	}

	public function render_migration_page(): void {
		Plugin::get_instance()->migration->render_migration_page();
	}

	public function render_debug_page(): void {
		Plugin::get_instance()->debug_page->render_debug_page();
	}
}
