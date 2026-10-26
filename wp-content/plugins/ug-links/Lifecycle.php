<?php
/**
 * Plugin Lifecycle – Singleton bootstrap orchestrator.
 *
 * v2: Adds REST API registration on rest_api_init.
 *
 * @package UglyLinks
 */

declare(strict_types=1);

namespace UglyLinks;

use UglyLinks\App\Api\LinksEndpoint;
use UglyLinks\App\Core\Router;
use UglyLinks\App\Controllers\Admin\LinksController;
use UglyLinks\App\Controllers\Admin\ToolsController;
use UglyLinks\App\Controllers\Admin\SettingsController;
use UglyLinks\App\Controllers\Admin\BackupController;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Lifecycle
 *
 * Singleton that owns plugin-wide hook registrations.
 */
final class Lifecycle {

    // ── Singleton ─────────────────────────────────────────────────────────────

    /** @var self|null Sole instance. */
    private static ?self $instance = null;

    public static function instance(): self {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {}
    private function __clone() {}

    // ── Bootstrap ─────────────────────────────────────────────────────────────

    /**
     * Wire up all WordPress hooks.
     *
     * @return void
     */
    public function init(): void {
        // Self-healing schema upgrade — runs on every load; provision() is
        // skipped via fast-path if the DB version and table both already match.
        \UglyLinks\App\Models\Database::maybeUpgrade();

        // Front-end redirect router (priority 1 = runs before theme templates).
        add_action( 'template_redirect', [ Router::class, 'dispatch' ], 1 );


        // REST API (loaded on both front-end and admin contexts via rest_api_init).
        add_action( 'rest_api_init', [ $this, 'registerRestRoutes' ] );

        // Cron
        add_filter( 'cron_schedules', [ $this, 'addCronSchedules' ] );
        add_action( 'uglylinks_sync_ip2location_cron', [ $this, 'runSyncCron' ] );

        // Admin UI (only in wp-admin context).
        if ( is_admin() ) {
            $admin_controller = new LinksController();
            $admin_controller->register();
            
            $tools_controller = new ToolsController();
            $tools_controller->register();

            $settings_controller = new SettingsController();
            $settings_controller->register();

            $backup_controller = new BackupController();
            $backup_controller->register();
        }

        // i18n.
        add_action( 'init', [ $this, 'loadTextdomain' ] );
    }

    /**
     * Register all REST API routes under uglylinks/v1.
     *
     * @return void
     */
    public function registerRestRoutes(): void {
        ( new LinksEndpoint() )->register();
    }

    /**
     * Load plugin text domain.
     *
     * @return void
     */
    public function loadTextdomain(): void {
        load_plugin_textdomain(
            'ug-links',
            false,
            dirname( plugin_basename( UGLYLINKS_FILE ) ) . '/languages'
        );
    }

    /**
     * Add custom cron schedules.
     *
     * @param array $schedules
     * @return array
     */
    public function addCronSchedules( array $schedules ): array {
        $schedules['two_weeks'] = [
            'interval' => 14 * DAY_IN_SECONDS,
            'display'  => esc_html__( 'Every Two Weeks', 'ug-links' )
        ];
        return $schedules;
    }

    /**
     * Run the IP2Location sync from cron.
     *
     * @return void
     */
    public function runSyncCron(): void {
        \UglyLinks\App\Controllers\Admin\SettingsController::performSync();
    }
}
