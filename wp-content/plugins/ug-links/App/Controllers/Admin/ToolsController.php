<?php
/**
 * Admin Tools Controller.
 *
 * Handles Import and Export of UglyLinks data via CSV.
 *
 * @package UglyLinks\App\Controllers\Admin
 */

declare(strict_types=1);

namespace UglyLinks\App\Controllers\Admin;

use UglyLinks\App\Models\Link;
use UglyLinks\App\Models\Database;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class ToolsController
 */
final class ToolsController {

    /**
     * Register admin-post hooks for import/export.
     * Called from Lifecycle::init().
     *
     * @return void
     */
    public function register(): void {
        add_action( 'admin_post_uglylinks_export', [ $this, 'handleExport' ] );
        add_action( 'admin_post_uglylinks_import', [ $this, 'handleImport' ] );
    }

    /**
     * Handle CSV Export.
     *
     * @return void
     */
    public function handleExport(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Insufficient permissions.', 'ug-links' ) );
        }

        if ( empty( $_POST['uglylinks_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['uglylinks_nonce'] ) ), 'uglylinks_export_nonce' ) ) {
            wp_die( esc_html__( 'Security check failed.', 'ug-links' ) );
        }

        $filename = 'uglylinks-export-' . date( 'Y-m-d' ) . '.csv';
        
        header( 'Content-Type: text/csv; charset=utf-8' );
        header( 'Content-Disposition: attachment; filename=' . $filename );
        header( 'Pragma: no-cache' );
        header( 'Expires: 0' );

        $output = fopen( 'php://output', 'w' );
        if ( false === $output ) {
            wp_die( esc_html__( 'Could not open output stream.', 'ug-links' ) );
        }

        fputcsv( $output, [ 'ID', 'Slug', 'Target URL', 'Title', 'Redirect Type', 'Total Clicks', 'Created At' ] );

        // Stream all links (chunking would be better for scale, but keeping it simple).
        $links = Link::all( 99999, 0 );
        foreach ( $links as $link ) {
            fputcsv( $output, [
                $link->id,
                $link->slug,
                $link->target_url,
                $link->title,
                $link->redirect_type,
                $link->total_clicks,
                $link->created_at,
            ] );
        }

        fclose( $output );
        exit;
    }

    /**
     * Handle CSV Import.
     *
     * @return void
     */
    public function handleImport(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Insufficient permissions.', 'ug-links' ) );
        }

        if ( empty( $_POST['uglylinks_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['uglylinks_nonce'] ) ), 'uglylinks_import_nonce' ) ) {
            wp_die( esc_html__( 'Security check failed.', 'ug-links' ) );
        }

        if ( empty( $_FILES['import_file']['tmp_name'] ) ) {
            $this->redirectWithNotice( 'error' );
        }

        $file = $_FILES['import_file']['tmp_name']; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        
        if ( ! is_readable( $file ) ) {
            $this->redirectWithNotice( 'error' );
        }

        $handle = fopen( $file, 'r' ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_read_fopen
        if ( false === $handle ) {
            $this->redirectWithNotice( 'error' );
        }

        $header = fgetcsv( $handle );
        if ( ! $header || ! in_array( 'Slug', $header, true ) || ! in_array( 'Target URL', $header, true ) ) {
            fclose( $handle );
            $this->redirectWithNotice( 'error', [ 'Line 1: CSV must contain "Slug" and "Target URL" columns.' ] );
        }

        $slug_idx   = array_search( 'Slug', $header, true );
        $target_idx = array_search( 'Target URL', $header, true );
        $title_idx  = array_search( 'Title', $header, true );
        $type_idx   = array_search( 'Redirect Type', $header, true );

        $imported = 0;
        $errors   = [];
        $line     = 1; // Header was line 1

        while ( ( $row = fgetcsv( $handle ) ) !== false ) {
            $line++;
            
            $slug       = isset( $row[ $slug_idx ] ) ? sanitize_title_with_dashes( $row[ $slug_idx ] ) : '';
            $target_url = isset( $row[ $target_idx ] ) ? esc_url_raw( $row[ $target_idx ] ) : '';
            $title      = ( false !== $title_idx && isset( $row[ $title_idx ] ) ) ? sanitize_text_field( $row[ $title_idx ] ) : '';
            $type       = ( false !== $type_idx && isset( $row[ $type_idx ] ) && in_array( $row[ $type_idx ], [ '301', '302' ], true ) ) ? $row[ $type_idx ] : '301';

            if ( empty( $slug ) ) {
                $errors[] = "Line $line: Missing required field (Slug).";
                continue;
            }

            if ( empty( $target_url ) ) {
                $errors[] = "Line $line: Missing required field (Target URL).";
                continue;
            }

            if ( ! Link::slugExists( $slug ) ) {
                $result = Link::create( $slug, $target_url, $title, $type );
                if ( $result ) {
                    $imported++;
                }
            } else {
                $errors[] = "Line $line: Slug '$slug' already exists. Skipped.";
            }
        }

        fclose( $handle );

        $this->redirectWithNotice( 'imported_' . $imported, $errors );
    }

    /**
     * Render the Tools UI page.
     *
     * @return void
     */
    public function renderToolsPage(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions.', 'ug-links' ) );
        }

        $file = UGLYLINKS_VIEWS_DIR . 'admin/tools.php';
        if ( file_exists( $file ) ) {
            include $file;
        }
    }

    /**
     * Redirect back to tools dashboard with a notice and optional errors.
     *
     * @param string $notice Notice key.
     * @param array $errors Array of error strings.
     * @return void
     */
    private function redirectWithNotice( string $notice, array $errors = [] ): void {
        $query_args = [
            'page'             => 'uglylinks-tools',
            'uglylinks_notice' => $notice,
        ];
        
        if ( ! empty( $errors ) ) {
            $transient_key = 'uglylinks_import_errors_' . get_current_user_id();
            set_transient( $transient_key, $errors, 60 ); // Keep for 60 seconds
            $query_args['uglylinks_has_errors'] = 1;
        }

        wp_safe_redirect( add_query_arg( $query_args, admin_url( 'admin.php' ) ) );
        exit;
    }
}
