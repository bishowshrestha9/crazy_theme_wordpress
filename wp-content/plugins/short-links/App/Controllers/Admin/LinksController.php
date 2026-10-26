<?php
/**
 * Admin Links Controller.
 *
 * Registers dashboard menu pages and handles all secure
 * form submissions (create, update, delete) for link records.
 *
 * @package ShortLinks\App\Controllers\Admin
 */

declare(strict_types=1);

namespace ShortLinks\App\Controllers\Admin;

use ShortLinks\App\Models\Link;
use ShortLinks\App\Controllers\Admin\ProController;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class LinksController
 *
 * MVC controller for the admin "ShortLinks" menu section.
 */
final class LinksController {

    // ── Menu / Page Constants ─────────────────────────────────────────────────

    private const MENU_SLUG      = 'shortlinks';
    private const NONCE_ACTION   = 'shortlinks_action';
    private const NONCE_FIELD    = 'shortlinks_nonce';
    private const PER_PAGE       = 20;

    // ── Registration ─────────────────────────────────────────────────────────

    /**
     * Register all admin hooks owned by this controller.
     * Called from Lifecycle::init() inside is_admin() guard.
     *
     * @return void
     */
    public function register(): void {
        add_action( 'admin_menu',    [ $this, 'registerMenuPages' ] );
        add_action( 'admin_post_shortlinks_save',   [ $this, 'handleSave' ] );
        add_action( 'admin_post_shortlinks_delete', [ $this, 'handleDelete' ] );
        add_action( 'admin_enqueue_scripts',       [ $this, 'enqueueAssets' ] );
    }

    // ── Menu Registration ─────────────────────────────────────────────────────

    /**
     * Register the top-level menu page and a submenu for adding new links.
     *
     * @return void
     */
    public function registerMenuPages(): void {
        add_menu_page(
            __( 'ShortLinks', 'short-links' ),
            __( 'ShortLinks', 'short-links' ),
            'manage_options',
            self::MENU_SLUG,
            [ $this, 'renderListPage' ],
            'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9Ii01MCAtNTAgMTAwIDEwMCIgZmlsbD0iY3VycmVudENvbG9yIj48Zz48cGF0aCBkPSJNIDEwLDAgQyAyMCwtMjAgNDAsLTMwIDUwLC0xMCBDIDMwLDAgMjAsMTAgMTAsMCBaIiB0cmFuc2Zvcm09InJvdGF0ZSgwKSIgLz48cGF0aCBkPSJNIDEwLDAgQyAyMCwtMjAgNDAsLTMwIDUwLC0xMCBDIDMwLDAgMjAsMTAgMTAsMCBaIiB0cmFuc2Zvcm09InJvdGF0ZSg2MCkiIC8+PHBhdGggZD0iTSAxMCwwIEMgMjAsLTIwIDQwLC0zMCA1MCwtMTAgQyAzMCwwIDIwLDEwIDEwLDAgWiIgdHJhbnNmb3JtPSJyb3RhdGUoMTIwKSIgLz48cGF0aCBkPSJNIDEwLDAgQyAyMCwtMjAgNDAsLTMwIDUwLC0xMCBDIDMwLDAgMjAsMTAgMTAsMCBaIiB0cmFuc2Zvcm09InJvdGF0ZSgxODApIiAvPjxwYXRoIGQ9Ik0gMTAsMCBDIDIwLC0yMCA0MCwtMzAgNTAsLTEwIEMgMzAsMCAyMCwxMCAxMCwwIFoiIHRyYW5zZm9ybT0icm90YXRlKDI0MCkiIC8+PHBhdGggZD0iTSAxMCwwIEMgMjAsLTIwIDQwLC0zMCA1MCwtMTAgQyAzMCwwIDIwLDEwIDEwLDAgWiIgdHJhbnNmb3JtPSJyb3RhdGUoMzAwKSIgLz48Y2lyY2xlIGN4PSIwIiBjeT0iMCIgcj0iMTIiIC8+PC9nPjwvc3ZnPg==',
            58
        );

        add_submenu_page(
            self::MENU_SLUG,
            __( 'All Links', 'short-links' ),
            __( 'All Links', 'short-links' ),
            'manage_options',
            self::MENU_SLUG,
            [ $this, 'renderListPage' ]
        );

        add_submenu_page(
            self::MENU_SLUG,
            __( 'Add New Link', 'short-links' ),
            __( 'Add New', 'short-links' ),
            'manage_options',
            'shortlinks-new',
            [ $this, 'renderFormPage' ]
        );

        add_submenu_page(
            self::MENU_SLUG,
            __( 'Upgrade to Pro 🚀', 'short-links' ),
            '<span style="color: #f59e0b; font-weight: 600;">' . __( 'Upgrade to Pro', 'short-links' ) . '</span>',
            'manage_options',
            'shortlinks-pro',
            [ new ProController(), 'renderProPage' ]
        );

        add_submenu_page(
            self::MENU_SLUG,
            __( 'Settings', 'short-links' ),
            __( 'Settings', 'short-links' ),
            'manage_options',
            'shortlinks-settings',
            [ new \ShortLinks\App\Controllers\Admin\SettingsController(), 'renderSettingsPage' ]
        );

        add_submenu_page(
            self::MENU_SLUG,
            __( 'Tools', 'short-links' ),
            __( 'Tools', 'short-links' ),
            'manage_options',
            'shortlinks-tools',
            [ new \ShortLinks\App\Controllers\Admin\ToolsController(), 'renderToolsPage' ]
        );
    }

    // ── Asset Enqueueing ──────────────────────────────────────────────────────

    /**
     * Enqueue admin-only CSS and JS scoped to ShortLinks pages.
     *
     * The JS config object (nonce, REST root, i18n strings) is injected
     * directly by the list view via a <script id="shortlinks-config"> block
     * so wp_localize_script is not needed here.
     *
     * @param string $hook_suffix The current admin page hook suffix.
     * @return void
     */
    public function enqueueAssets( string $hook_suffix ): void {
        $shortlinks_pages = [
            'toplevel_page_' . self::MENU_SLUG,
            self::MENU_SLUG . '_page_shortlinks-new',
            self::MENU_SLUG . '_page_shortlinks-edit',
            self::MENU_SLUG . '_page_shortlinks-tools',
            self::MENU_SLUG . '_page_shortlinks-settings',
            self::MENU_SLUG . '_page_shortlinks-pro',
        ];

        if ( ! in_array( $hook_suffix, $shortlinks_pages, true ) ) {
            return;
        }

        wp_enqueue_style(
            'shortlinks-admin',
            SHORTLINKS_URL . 'assets/css/admin.css',
            [],
            SHORTLINKS_VERSION
        );

        wp_enqueue_script(
            'shortlinks-admin',
            SHORTLINKS_URL . 'assets/js/admin.js',
            [],             // No jQuery dependency.
            SHORTLINKS_VERSION,
            true            // Load in footer.
        );
    }

    // ── View Renderers ────────────────────────────────────────────────────────

    /**
     * Render the links overview list-table view.
     *
     * @return void
     */
    public function renderListPage(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions.', 'short-links' ) );
        }

        $current_page = max( 1, (int) ( $_GET['paged'] ?? 1 ) );
        $offset       = ( $current_page - 1 ) * self::PER_PAGE;
        $links        = Link::all( self::PER_PAGE, $offset );
        $total        = Link::count();
        $total_pages  = (int) ceil( $total / self::PER_PAGE );

        // Pass scoped variables into the view.
        $view_data = compact( 'links', 'total', 'total_pages', 'current_page' );

        $this->renderView( 'admin/list', $view_data );
    }

    /**
     * Render the link create/edit form view.
     *
     * @return void
     */
    public function renderFormPage(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'You do not have sufficient permissions.', 'short-links' ) );
        }

        $link_id = isset( $_GET['id'] ) ? absint( $_GET['id'] ) : 0;
        $link    = $link_id > 0 ? Link::findById( $link_id ) : null;

        // Surface notices from redirects.
        $notice = $_GET['shortlinks_notice'] ?? '';

        $view_data = [
            'link'         => $link,
            'nonce_field'  => self::NONCE_FIELD,
            'nonce_action' => self::NONCE_ACTION,
            'notice'       => sanitize_text_field( wp_unslash( $notice ) ),
        ];

        $this->renderView( 'admin/form', $view_data );
    }

    // ── Form Handlers (admin-post callbacks) ──────────────────────────────────

    /**
     * Handle create/update form submissions.
     *
     * POST action: shortlinks_save
     *
     * @return void
     */
    public function handleSave(): void {
        // 1. Capability check.
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Insufficient permissions.', 'short-links' ) );
        }

        // 2. Nonce verification.
        if (
            ! isset( $_POST[ self::NONCE_FIELD ] ) ||
            ! wp_verify_nonce(
                sanitize_text_field( wp_unslash( $_POST[ self::NONCE_FIELD ] ) ),
                self::NONCE_ACTION
            )
        ) {
            wp_die( esc_html__( 'Security check failed.', 'short-links' ) );
        }

        // 3. Sanitize inputs.
        $id           = isset( $_POST['link_id'] ) ? absint( $_POST['link_id'] ) : 0;
        $slug         = sanitize_title_with_dashes( wp_unslash( $_POST['slug']         ?? '' ) );
        $target_url   = esc_url_raw( wp_unslash( $_POST['target_url']   ?? '' ) );
        $title        = sanitize_text_field( wp_unslash( $_POST['title']        ?? '' ) );
        $redirect_type = in_array( $_POST['redirect_type'] ?? '307', [ '301', '302', '307' ], true )
            ? sanitize_text_field( wp_unslash( $_POST['redirect_type'] ) )
            : '307';

        // Pro Fields
        $ab_target          = ! empty( $_POST['ab_target'] ) ? esc_url_raw( wp_unslash( $_POST['ab_target'] ) ) : null;
        // geo_rules is expected to be a JSON string of a map. We can verify it's valid JSON.
        $geo_rules = ! empty( $_POST['geo_rules'] ) ? wp_unslash( $_POST['geo_rules'] ) : null;
        if ( $geo_rules ) {
            $decoded = json_decode( $geo_rules, true );
            if ( ! is_array( $decoded ) ) {
                $decoded = json_decode( html_entity_decode( $geo_rules, ENT_QUOTES ), true );
            }
            $geo_rules = is_array( $decoded ) ? wp_json_encode( $decoded ) : null;
        }
        // retargeting_pixels can contain HTML/JS. Verify unfiltered_html capability to prevent XSS.
        $retargeting_pixels = null;
        if ( ! empty( $_POST['retargeting_pixels'] ) ) {
            $raw_pixels = wp_unslash( $_POST['retargeting_pixels'] );
            if ( current_user_can( 'unfiltered_html' ) ) {
                $retargeting_pixels = $raw_pixels;
            } else {
                $retargeting_pixels = wp_kses_post( $raw_pixels );
            }
        }

        // 4. Validate required fields.
        if ( '' === $slug || '' === $target_url ) {
            $this->redirectToForm( $id, 'missing_fields' );
            return;
        }

        // 5. Duplicate-slug guard.
        if ( Link::slugExists( $slug, $id > 0 ? $id : null ) ) {
            $this->redirectToForm( $id, 'slug_taken' );
            return;
        }

        // 6. Persist.
        if ( $id > 0 ) {
            $success = Link::update( $id, $slug, $target_url, $title, $redirect_type, $ab_target, $geo_rules, $retargeting_pixels );
        } else {
            $result  = Link::create( $slug, $target_url, $title, $redirect_type, $ab_target, $geo_rules, $retargeting_pixels );
            $success = false !== $result;
            $id      = (int) $result;
        }

        $notice = $success ? 'saved' : 'error';
        $this->redirectToForm( $id, $notice );
    }

    /**
     * Handle delete form submissions.
     *
     * POST action: shortlinks_delete
     *
     * @return void
     */
    public function handleDelete(): void {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( esc_html__( 'Insufficient permissions.', 'short-links' ) );
        }

        if (
            ! isset( $_POST[ self::NONCE_FIELD ] ) ||
            ! wp_verify_nonce(
                sanitize_text_field( wp_unslash( $_POST[ self::NONCE_FIELD ] ) ),
                self::NONCE_ACTION
            )
        ) {
            wp_die( esc_html__( 'Security check failed.', 'short-links' ) );
        }

        $id = isset( $_POST['link_id'] ) ? absint( $_POST['link_id'] ) : 0;

        if ( $id > 0 ) {
            Link::delete( $id );
        }

        wp_safe_redirect(
            add_query_arg(
                [ 'page' => self::MENU_SLUG, 'shortlinks_notice' => 'deleted' ],
                admin_url( 'admin.php' )
            )
        );
        exit;
    }

    // ── Private Helpers ───────────────────────────────────────────────────────

    /**
     * Load a PHP view file with extracted variables.
     *
     * @param string               $view      View name relative to /views/ (no .php).
     * @param array<string, mixed> $view_data Variables to extract into view scope.
     * @return void
     */
    private function renderView( string $view, array $view_data = [] ): void {
        $file = SHORTLINKS_VIEWS_DIR . $view . '.php';

        if ( ! file_exists( $file ) ) {
            wp_die(
                esc_html(
                    sprintf(
                    /* translators: %s: view file path */
                        __( 'View not found: %s', 'short-links' ),
                        $file
                    )
                )
            );
        }

        // Extract variables into the local scope of the include.
        extract( $view_data, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract.extract_extract

        include $file;
    }

    /**
     * Redirect back to the form page with a notice query arg.
     *
     * @param int    $link_id Link ID (0 for new).
     * @param string $notice  Notice key to pass in the URL.
     * @return void
     */
    private function redirectToForm( int $link_id, string $notice ): void {
        $args = [
            'page'             => 'shortlinks-new',
            'shortlinks_notice' => $notice,
        ];

        if ( $link_id > 0 ) {
            $args['id'] = $link_id;
        }

        wp_safe_redirect( add_query_arg( $args, admin_url( 'admin.php' ) ) );
        exit;
    }
}
