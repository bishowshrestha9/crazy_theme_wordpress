<?php
/**
 * Admin Backup Controller.
 *
 * Manages automated CSV backups of all links via WordPress Cron.
 *
 * @package ShortLinks\App\Controllers\Admin
 */

declare(strict_types=1);

namespace ShortLinks\App\Controllers\Admin;

use ShortLinks\App\Models\Database;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class BackupController
 */
final class BackupController {

    public const CRON_HOOK = 'shortlinks_daily_backup_event';
    public const BACKUP_DIR_NAME = 'backups';

    /**
     * Register hooks.
     *
     * @return void
     */
    public function register(): void {
        add_action( 'admin_init', [ $this, 'scheduleCron' ] );
        add_action( self::CRON_HOOK, [ $this, 'runBackup' ] );
        
        // Revert action
        add_action( 'admin_post_shortlinks_revert', [ $this, 'handleRevert' ] );

        // Ensure cleanup on plugin deactivation
        register_deactivation_hook( SHORTLINKS_FILE, [ $this, 'unscheduleCron' ] );
    }

    /**
     * Schedule the daily backup cron if not already scheduled.
     *
     * @return void
     */
    public function scheduleCron(): void {
        if ( ! wp_next_scheduled( self::CRON_HOOK ) ) {
            wp_schedule_event( time(), 'daily', self::CRON_HOOK );
        }
    }

    /**
     * Remove the cron job.
     *
     * @return void
     */
    public function unscheduleCron(): void {
        wp_clear_scheduled_hook( self::CRON_HOOK );
    }

    /**
     * Execute the backup routine.
     *
     * @return void
     */
    public function runBackup(): void {
        $backup_dir = plugin_dir_path( SHORTLINKS_FILE ) . self::BACKUP_DIR_NAME;

        // 1. Ensure directory exists and is secured.
        if ( ! file_exists( $backup_dir ) ) {
            wp_mkdir_p( $backup_dir );
        }

        $htaccess_file = $backup_dir . '/.htaccess';
        if ( ! file_exists( $htaccess_file ) ) {
            file_put_contents( $htaccess_file, "Deny from all\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
        }

        $index_file = $backup_dir . '/index.php';
        if ( ! file_exists( $index_file ) ) {
            file_put_contents( $index_file, "<?php\n// Silence is golden.\n" ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_file_put_contents
        }

        // 2. Query all links.
        global $wpdb;
        $table_name = Database::linksTable();
        $links = $wpdb->get_results( "SELECT * FROM {$table_name} ORDER BY id ASC" ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared

        if ( empty( $links ) ) {
            return; // Nothing to backup
        }

        // 3. Create the CSV file.
        $date = wp_date( 'Y-m-d' );
        $filename = "shortlinks-backup-{$date}.csv";
        $filepath = $backup_dir . '/' . $filename;

        $handle = fopen( $filepath, 'w' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fopen
        if ( ! $handle ) {
            return;
        }

        // Write header
        fputcsv( $handle, [ 'ID', 'Slug', 'Target URL', 'Title', 'Redirect Type', 'Total Clicks', 'Created At', 'AB Target', 'Geo Rules', 'Retargeting Pixels' ] );

        // Helper to prevent CSV formula injection
        $sanitize_csv = function( $value ) {
            $value = (string) $value;
            if ( preg_match( '/^[=\-+@]/', $value ) ) {
                return "'" . $value;
            }
            return $value;
        };

        // Write rows
        foreach ( $links as $link ) {
            $clicks = \ShortLinks\App\Models\Click::countForLink( (int) $link->id );
            
            fputcsv( $handle, [
                $sanitize_csv( $link->id ),
                $sanitize_csv( $link->slug ),
                $sanitize_csv( $link->target_url ),
                $sanitize_csv( $link->title ),
                $sanitize_csv( $link->redirect_type ),
                $sanitize_csv( $clicks ),
                $sanitize_csv( $link->created_at ),
                $sanitize_csv( $link->ab_target ?? '' ),
                $sanitize_csv( $link->geo_rules ?? '' ),
                $sanitize_csv( $link->retargeting_pixels ?? '' ),
            ] );
        }

        fclose( $handle );
    }

    /**
     * Retrieve a list of available backups.
     *
     * @return array Array of backup details.
     */
    public static function getBackups(): array {
        $backup_dir = plugin_dir_path( SHORTLINKS_FILE ) . self::BACKUP_DIR_NAME;
        if ( ! file_exists( $backup_dir ) ) {
            return [];
        }

        $files = glob( $backup_dir . '/*.csv' );
        if ( ! $files ) {
            return [];
        }

        $backups = [];
        foreach ( $files as $file ) {
            // Count total links (lines - 1 header row)
            $lines = 0;
            $handle = fopen( $file, 'r' ); // phpcs:ignore
            if ( $handle ) {
                while ( ! feof( $handle ) ) {
                    if ( fgets( $handle ) !== false ) {
                        $lines++;
                    }
                }
                fclose( $handle );
            }
            $total_links = max( 0, $lines - 1 );

            $backups[] = [
                'filename'    => basename( $file ),
                'filepath'    => $file,
                'timestamp'   => filemtime( $file ),
                'total_links' => $total_links,
                'size'        => size_format( filesize( $file ) ),
            ];
        }

        // Sort by timestamp descending
        usort( $backups, function( $a, $b ) {
            return $b['timestamp'] <=> $a['timestamp'];
        } );

        return $backups;
    }

    /**
     * Handle the Revert action from the Tools page.
     *
     * @return void
     */
    public function handleRevert(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Unauthorized', 'short-links' ) );
        }

        check_admin_referer( 'shortlinks_revert_nonce', 'shortlinks_nonce' );

        $filename = isset( $_POST['backup_file'] ) ? sanitize_file_name( wp_unslash( $_POST['backup_file'] ) ) : '';
        if ( empty( $filename ) ) {
            wp_safe_redirect( add_query_arg( [ 'page' => 'shortlinks-tools', 'notice' => 'revert_error' ], admin_url( 'admin.php' ) ) );
            exit;
        }

        $filepath = plugin_dir_path( SHORTLINKS_FILE ) . self::BACKUP_DIR_NAME . '/' . $filename;
        if ( ! file_exists( $filepath ) ) {
            wp_safe_redirect( add_query_arg( [ 'page' => 'shortlinks-tools', 'notice' => 'revert_error' ], admin_url( 'admin.php' ) ) );
            exit;
        }

        $handle = fopen( $filepath, 'r' ); // phpcs:ignore
        if ( ! $handle ) {
            wp_safe_redirect( add_query_arg( [ 'page' => 'shortlinks-tools', 'notice' => 'revert_error' ], admin_url( 'admin.php' ) ) );
            exit;
        }

        global $wpdb;
        $table_name = Database::linksTable();

        // 1. Wipe the current links table
        $wpdb->query( "TRUNCATE TABLE {$table_name}" ); // phpcs:ignore

        // 2. Restore links from CSV
        $header = fgetcsv( $handle ); // Skip header
        if ( $header === false ) {
            fclose( $handle );
            wp_safe_redirect( add_query_arg( [ 'page' => 'shortlinks-tools', 'notice' => 'revert_error' ], admin_url( 'admin.php' ) ) );
            exit;
        }

        while ( ( $row = fgetcsv( $handle ) ) !== false ) {
            if ( count( $row ) < 7 ) {
                continue; // Invalid row format
            }

            // Remove CSV injection prefix if present
            $row = array_map( function( $val ) {
                if ( is_string( $val ) && str_starts_with( $val, "'" ) && preg_match( '/^\'[=\-+@]/', $val ) ) {
                    return substr( $val, 1 );
                }
                return $val;
            }, $row );
            
            $ab_target = $row[7] ?? null;
            $geo_rules = $row[8] ?? null;
            $retargeting_pixels = $row[9] ?? null;

            $wpdb->insert(
                $table_name,
                [
                    'id'                 => (int) $row[0],
                    'slug'               => $row[1],
                    'target_url'         => $row[2],
                    'title'              => $row[3],
                    'redirect_type'      => $row[4],
                    'created_at'         => $row[6],
                    'ab_target'          => $ab_target,
                    'geo_rules'          => $geo_rules,
                    'retargeting_pixels' => $retargeting_pixels,
                ],
                [ '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ]
            );
        }

        fclose( $handle );

        wp_safe_redirect( add_query_arg( [ 'page' => 'shortlinks-tools', 'notice' => 'revert_success' ], admin_url( 'admin.php' ) ) );
        exit;
    }
}
