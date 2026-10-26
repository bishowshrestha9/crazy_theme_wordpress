<?php
/**
 * Admin Settings Controller.
 *
 * Renders and saves ShortLinks general settings.
 *
 * @package ShortLinks\App\Controllers\Admin
 */

declare(strict_types=1);

namespace ShortLinks\App\Controllers\Admin;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class SettingsController
 */
final class SettingsController {

    public const OPTION_GROUP = 'shortlinks_settings_group';

    /**
     * Register settings and handlers.
     *
     * @return void
     */
    public function register(): void {
        add_action( 'admin_init', [ $this, 'registerSettings' ] );
        add_action( 'admin_post_shortlinks_sync_ip2location', [ $this, 'handleSync' ] );
    }

    /**
     * Register WordPress settings options.
     *
     * @return void
     */
    public function registerSettings(): void {
        register_setting( self::OPTION_GROUP, 'shortlinks_default_redirect_type', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '301',
        ] );

        register_setting( self::OPTION_GROUP, 'shortlinks_enable_tracking', [
            'type'              => 'boolean',
            'sanitize_callback' => 'rest_sanitize_boolean',
            'default'           => true,
        ] );

        register_setting( self::OPTION_GROUP, 'shortlinks_ip2location_token', [
            'type'              => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default'           => '',
        ] );
    }

    /**
     * Render the Settings view.
     *
     * @return void
     */
    public function renderSettingsPage(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions.', 'short-links' ) );
        }

        $file = SHORTLINKS_VIEWS_DIR . 'admin/settings.php';
        if ( file_exists( $file ) ) {
            include $file;
        }
    }

    /**
     * Perform the IP2Location DB download and extraction.
     * 
     * @return bool True on success, false on failure.
     */
    public static function performSync(): bool {
        $token = get_option( 'shortlinks_ip2location_token', '' );
        if ( empty( $token ) ) {
            return false;
        }

        // IP2Location LITE DB1 download URL
        $download_url = "https://www.ip2location.com/download?token={$token}&file=DB1LITEBIN";

        require_once ABSPATH . 'wp-admin/includes/file.php';
        
        $tmp_file = download_url( $download_url, 300 );
        
        if ( is_wp_error( $tmp_file ) ) {
            return false;
        }

        WP_Filesystem();
        global $wp_filesystem;

        $upload_dir = wp_upload_dir();
        $target_dir = trailingslashit( $upload_dir['basedir'] ) . 'short-links';

        if ( ! $wp_filesystem->is_dir( $target_dir ) ) {
            $wp_filesystem->mkdir( $target_dir );
        }

        $unzip_result = unzip_file( $tmp_file, $target_dir );
        unlink( $tmp_file );

        if ( is_wp_error( $unzip_result ) ) {
            return false;
        }

        return true;
    }

    /**
     * Handle the IP2Location DB sync manually from settings.
     *
     * @return void
     */
    public function handleSync(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions.', 'short-links' ) );
        }

        if ( ! isset( $_POST['shortlinks_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['shortlinks_nonce'] ) ), 'shortlinks_sync_ip2location_nonce' ) ) {
            wp_die( esc_html__( 'Security check failed.', 'short-links' ) );
        }

        if ( self::performSync() ) {
            // phpcs:ignore WordPress.Security.SafeRedirect.wp_safe_redirect_Fallback
            wp_safe_redirect( admin_url( 'admin.php?page=shortlinks-settings&sync=success' ) );
        } else {
            // phpcs:ignore WordPress.Security.SafeRedirect.wp_safe_redirect_Fallback
            wp_safe_redirect( admin_url( 'admin.php?page=shortlinks-settings&sync=error' ) );
        }
        exit;
    }
}
