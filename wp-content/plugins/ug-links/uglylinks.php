<?php
/**
 * Plugin Name:       UglyLinks
 * Plugin URI:        https://example.com/ug-links
 * Description:       High-performance, OOP link shortening and click-tracking plugin.
 * Version:           1.1.0
 * Requires at least: 6.0
 * Requires PHP:      8.1
 * Author:             Bishow Shrestha
 * Author URI:        https://bishow-three.vercel.app/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ug-links
 * Domain Path:       /languages
 *
 * @package UglyLinks
 */

declare(strict_types=1);

namespace UglyLinks;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// ── Constants ──────────────────────────────────────────────────────────────────
define( 'UGLYLINKS_VERSION',   '1.1.0' );
define( 'UGLYLINKS_FILE',      __FILE__ );
define( 'UGLYLINKS_DIR',       plugin_dir_path( __FILE__ ) );
define( 'UGLYLINKS_URL',       plugin_dir_url( __FILE__ ) );
define( 'UGLYLINKS_VIEWS_DIR', UGLYLINKS_DIR . 'views/' );

// ── Composer Autoloader ──────────────────────────────────────────────────────
$composer_autoload = UGLYLINKS_DIR . 'vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
    require_once $composer_autoload;
}

// ── PSR-4 Autoloader ──────────────────────────────────────────────────────────
spl_autoload_register( static function ( string $class ): void {
    // Only handle classes under the UglyLinks namespace.
    $prefix = 'UglyLinks\\';
    $len    = strlen( $prefix );

    if ( strncmp( $prefix, $class, $len ) !== 0 ) {
        return;
    }

    $relative_class = substr( $class, $len );
    $file           = UGLYLINKS_DIR . str_replace( '\\', DIRECTORY_SEPARATOR, $relative_class ) . '.php';

    if ( file_exists( $file ) ) {
        require $file;
    }
} );

// ── Activation / Deactivation Hooks ──────────────────────────────────────────
register_activation_hook( UGLYLINKS_FILE, static function (): void {
    \UglyLinks\App\Models\Database::provision();
    if ( ! wp_next_scheduled( 'uglylinks_sync_ip2location_cron' ) ) {
        wp_schedule_event( time(), 'two_weeks', 'uglylinks_sync_ip2location_cron' );
    }
    flush_rewrite_rules();
} );

register_deactivation_hook( UGLYLINKS_FILE, static function (): void {
    wp_clear_scheduled_hook( 'uglylinks_sync_ip2location_cron' );
    flush_rewrite_rules();
} );

// ── Bootstrap ────────────────────────────────────────────────────────────────
add_action( 'plugins_loaded', static function (): void {
    Lifecycle::instance()->init();
} );
