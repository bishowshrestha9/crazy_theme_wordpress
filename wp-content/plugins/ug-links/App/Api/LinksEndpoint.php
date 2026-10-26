<?php
/**
 * REST API Endpoint Controller – uglylinks/v1/links.
 *
 * Exposes a full CRUD REST namespace for the admin SPA.
 *
 * Routes:
 *   GET    /wp-json/uglylinks/v1/links          — paginated list
 *   POST   /wp-json/uglylinks/v1/links          — create
 *   GET    /wp-json/uglylinks/v1/links/{id}     — single record
 *   PUT    /wp-json/uglylinks/v1/links/{id}     — full update
 *   PATCH  /wp-json/uglylinks/v1/links/{id}     — partial update (target_url only)
 *   DELETE /wp-json/uglylinks/v1/links/{id}     — delete
 *   GET    /wp-json/uglylinks/v1/links/{id}/stats — aggregated daily click stats
 *
 * All write endpoints require the `manage_options` capability.
 * Read endpoints also require authentication to prevent enumeration.
 *
 * @package UglyLinks\App\Api
 */

declare(strict_types=1);

namespace UglyLinks\App\Api;

use UglyLinks\App\Models\Click;
use UglyLinks\App\Models\Link;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class LinksEndpoint
 *
 * Registers and handles all REST routes under the uglylinks/v1 namespace.
 */
final class LinksEndpoint {

    /** REST namespace. */
    private const NAMESPACE = 'uglylinks/v1';

    /** Base route for links collection. */
    private const ROUTE_LINKS = '/links';

    /** Base route for a single link resource. */
    private const ROUTE_LINK = '/links/(?P<id>[\d]+)';

    /** Route for per-link stats. */
    private const ROUTE_STATS = '/links/(?P<id>[\d]+)/stats';

    // ── Registration ──────────────────────────────────────────────────────────

    /**
     * Register all routes on rest_api_init.
     *
     * @return void
     */
    public function register(): void {
        // ── Collection ───────────────────────────────────────────────────────
        register_rest_route(
            self::NAMESPACE,
            self::ROUTE_LINKS,
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'index' ],
                    'permission_callback' => [ $this, 'requireAuth' ],
                    'args'                => [
                        'page'     => [
                            'type'              => 'integer',
                            'default'           => 1,
                            'minimum'           => 1,
                            'sanitize_callback' => 'absint',
                        ],
                        'per_page' => [
                            'type'              => 'integer',
                            'default'           => 20,
                            'minimum'           => 1,
                            'maximum'           => 100,
                            'sanitize_callback' => 'absint',
                        ],
                    ],
                ],
                [
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'create' ],
                    'permission_callback' => [ $this, 'requireManageOptions' ],
                    'args'                => $this->linkWriteArgs( required: true ),
                ],
            ]
        );

        // ── Single Resource ───────────────────────────────────────────────────
        register_rest_route(
            self::NAMESPACE,
            self::ROUTE_LINK,
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'show' ],
                    'permission_callback' => [ $this, 'requireAuth' ],
                ],
                [
                    'methods'             => WP_REST_Server::EDITABLE, // PUT + PATCH
                    'callback'            => [ $this, 'update' ],
                    'permission_callback' => [ $this, 'requireManageOptions' ],
                    'args'                => $this->linkWriteArgs( required: false ),
                ],
                [
                    'methods'             => WP_REST_Server::DELETABLE,
                    'callback'            => [ $this, 'delete' ],
                    'permission_callback' => [ $this, 'requireManageOptions' ],
                ],
            ]
        );

        // ── Stats sub-resource ────────────────────────────────────────────────
        register_rest_route(
            self::NAMESPACE,
            self::ROUTE_STATS,
            [
                'methods'             => WP_REST_Server::READABLE,
                'callback'            => [ $this, 'stats' ],
                'permission_callback' => [ $this, 'requireAuth' ],
                'args'                => [
                    'days' => [
                        'type'              => 'integer',
                        'default'           => 30,
                        'minimum'           => 1,
                        'maximum'           => 365,
                        'sanitize_callback' => 'absint',
                    ],
                ],
            ]
        );
    }

    // ── Permission Callbacks ──────────────────────────────────────────────────

    /**
     * Require any authenticated user (nonce or application password).
     *
     * @return bool|WP_Error
     */
    public function requireAuth(): bool|WP_Error {
        if ( ! is_user_logged_in() ) {
            return new WP_Error(
                'rest_forbidden',
                __( 'Authentication required.', 'ug-links' ),
                [ 'status' => 401 ]
            );
        }

        return true;
    }

    /**
     * Require the manage_options capability for write operations.
     *
     * @return bool|WP_Error
     */
    public function requireManageOptions(): bool|WP_Error {
        if ( ! current_user_can( 'manage_options' ) ) {
            return new WP_Error(
                'rest_forbidden',
                __( 'You do not have permission to manage links.', 'ug-links' ),
                [ 'status' => 403 ]
            );
        }

        return true;
    }

    // ── Route Handlers ────────────────────────────────────────────────────────

    /**
     * GET /uglylinks/v1/links — paginated link list.
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response
     */
    public function index( WP_REST_Request $request ): WP_REST_Response {
        $page     = (int) $request->get_param( 'page' );
        $per_page = (int) $request->get_param( 'per_page' );
        $offset   = ( $page - 1 ) * $per_page;

        $rows  = Link::all( $per_page, $offset );
        $total = Link::count();

        $data = array_map(
            static fn( object $row ) => Link::forApi( $row, (int) ( $row->total_clicks ?? 0 ) ),
            $rows
        );

        $response = new WP_REST_Response( $data, 200 );
        $response->header( 'X-UglyLinks-Total',      (string) $total );
        $response->header( 'X-UglyLinks-TotalPages', (string) (int) ceil( $total / $per_page ) );

        return $response;
    }

    /**
     * POST /uglylinks/v1/links — create a new link.
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function create( WP_REST_Request $request ): WP_REST_Response|WP_Error {
        $slug         = sanitize_title_with_dashes( (string) $request->get_param( 'slug' ) );
        $target_url   = esc_url_raw( (string) $request->get_param( 'target_url' ) );
        $title        = sanitize_text_field( (string) $request->get_param( 'title' ) );
        $redirect_type = $this->validateRedirectType( (string) $request->get_param( 'redirect_type' ) );

        if ( '' === $slug || '' === $target_url ) {
            return new WP_Error(
                'uglylinks_invalid_params',
                __( 'slug and target_url are required.', 'ug-links' ),
                [ 'status' => 422 ]
            );
        }

        if ( Link::slugExists( $slug ) ) {
            return new WP_Error(
                'uglylinks_slug_taken',
                __( 'This slug is already in use.', 'ug-links' ),
                [ 'status' => 409 ]
            );
        }

        $ab_target = $request->get_param( 'ab_target' ) !== null
            ? esc_url_raw( (string) $request->get_param( 'ab_target' ) )
            : null;
            
        $geo_rules = $request->get_param( 'geo_rules' ) !== null
            ? wp_unslash( (string) $request->get_param( 'geo_rules' ) )
            : null;
        if ( $geo_rules && ! is_array( json_decode( $geo_rules, true ) ) ) {
            $geo_rules = null;
        }

        $retargeting_pixels = null;
        if ( $request->get_param( 'retargeting_pixels' ) !== null ) {
            $raw_pixels = wp_unslash( (string) $request->get_param( 'retargeting_pixels' ) );
            if ( current_user_can( 'unfiltered_html' ) ) {
                $retargeting_pixels = $raw_pixels;
            } else {
                $retargeting_pixels = wp_kses_post( $raw_pixels );
            }
        }

        $new_id = Link::create( $slug, $target_url, $title, $redirect_type, $ab_target, $geo_rules, $retargeting_pixels );

        if ( false === $new_id ) {
            return new WP_Error(
                'uglylinks_create_failed',
                __( 'Could not create the link. Please try again.', 'ug-links' ),
                [ 'status' => 500 ]
            );
        }

        $link = Link::findById( $new_id );

        if ( null === $link ) {
            return new WP_Error(
                'uglylinks_create_failed',
                __( 'Link created but could not be retrieved.', 'ug-links' ),
                [ 'status' => 500 ]
            );
        }

        return new WP_REST_Response( Link::forApi( $link ), 201 );
    }

    /**
     * GET /uglylinks/v1/links/{id} — single link record.
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function show( WP_REST_Request $request ): WP_REST_Response|WP_Error {
        $link = $this->resolveLink( (int) $request->get_param( 'id' ) );

        if ( is_wp_error( $link ) ) {
            return $link;
        }

        return new WP_REST_Response( Link::forApi( $link ), 200 );
    }

    /**
     * PUT|PATCH /uglylinks/v1/links/{id} — full or partial update.
     *
     * PATCH supports updating only `target_url` (inline editor use-case).
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function update( WP_REST_Request $request ): WP_REST_Response|WP_Error {
        $id   = (int) $request->get_param( 'id' );
        $link = $this->resolveLink( $id );

        if ( is_wp_error( $link ) ) {
            return $link;
        }

        // For PATCH, fall back to existing values for omitted fields.
        $is_patch = 'PATCH' === $request->get_method();

        $slug = $request->get_param( 'slug' ) !== null
            ? sanitize_title_with_dashes( (string) $request->get_param( 'slug' ) )
            : ( $is_patch ? $link->slug : '' );

        $target_url = $request->get_param( 'target_url' ) !== null
            ? esc_url_raw( (string) $request->get_param( 'target_url' ) )
            : ( $is_patch ? $link->target_url : '' );

        $title = $request->get_param( 'title' ) !== null
            ? sanitize_text_field( (string) $request->get_param( 'title' ) )
            : ( $is_patch ? $link->title : '' );

        $redirect_type = $request->get_param( 'redirect_type' ) !== null
            ? $this->validateRedirectType( (string) $request->get_param( 'redirect_type' ) )
            : ( $is_patch ? $link->redirect_type : '301' );

        if ( '' === $slug || '' === $target_url ) {
            return new WP_Error(
                'uglylinks_invalid_params',
                __( 'slug and target_url cannot be empty.', 'ug-links' ),
                [ 'status' => 422 ]
            );
        }

        if ( $slug !== $link->slug && Link::slugExists( $slug, $id ) ) {
            return new WP_Error(
                'uglylinks_slug_taken',
                __( 'This slug is already in use.', 'ug-links' ),
                [ 'status' => 409 ]
            );
        }

        $ab_target = $request->get_param( 'ab_target' ) !== null
            ? esc_url_raw( (string) $request->get_param( 'ab_target' ) )
            : ( $is_patch ? $link->ab_target : null );

        $geo_rules = $request->get_param( 'geo_rules' ) !== null
            ? wp_unslash( (string) $request->get_param( 'geo_rules' ) )
            : ( $is_patch ? $link->geo_rules : null );
        if ( $request->get_param( 'geo_rules' ) !== null && $geo_rules && ! is_array( json_decode( $geo_rules, true ) ) ) {
            $geo_rules = null;
        }

        $retargeting_pixels = $is_patch ? $link->retargeting_pixels : null;
        if ( $request->get_param( 'retargeting_pixels' ) !== null ) {
            $raw_pixels = wp_unslash( (string) $request->get_param( 'retargeting_pixels' ) );
            if ( current_user_can( 'unfiltered_html' ) ) {
                $retargeting_pixels = $raw_pixels;
            } else {
                $retargeting_pixels = wp_kses_post( $raw_pixels );
            }
        }

        $success = Link::update( $id, $slug, $target_url, $title, $redirect_type, $ab_target, $geo_rules, $retargeting_pixels );

        if ( ! $success ) {
            return new WP_Error(
                'uglylinks_update_failed',
                __( 'Could not update the link.', 'ug-links' ),
                [ 'status' => 500 ]
            );
        }

        $updated = Link::findById( $id );

        return new WP_REST_Response( Link::forApi( $updated ), 200 );
    }

    /**
     * DELETE /uglylinks/v1/links/{id} — delete link and click data.
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function delete( WP_REST_Request $request ): WP_REST_Response|WP_Error {
        $id   = (int) $request->get_param( 'id' );
        $link = $this->resolveLink( $id );

        if ( is_wp_error( $link ) ) {
            return $link;
        }

        $success = Link::delete( $id );

        if ( ! $success ) {
            return new WP_Error(
                'uglylinks_delete_failed',
                __( 'Could not delete the link.', 'ug-links' ),
                [ 'status' => 500 ]
            );
        }

        return new WP_REST_Response( [ 'deleted' => true, 'id' => $id ], 200 );
    }

    /**
     * GET /uglylinks/v1/links/{id}/stats — aggregated daily click data.
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function stats( WP_REST_Request $request ): WP_REST_Response|WP_Error {
        $id   = (int) $request->get_param( 'id' );
        $link = $this->resolveLink( $id );

        if ( is_wp_error( $link ) ) {
            return $link;
        }

        $days     = (int) $request->get_param( 'days' );
        $by_day   = Click::statsByDay( $id, $days );
        $referrers = Click::topReferrers( $id );
        $total    = Click::countForLink( $id );

        return new WP_REST_Response(
            [
                'link_id'   => $id,
                'total'     => $total,
                'days'      => $days,
                'by_day'    => $by_day,
                'referrers' => $referrers,
            ],
            200
        );
    }

    // ── Private Helpers ───────────────────────────────────────────────────────

    /**
     * Resolve a link by ID, returning a WP_Error on miss.
     *
     * @param int $id
     * @return object|WP_Error
     */
    private function resolveLink( int $id ): object {
        $link = Link::findById( $id );

        if ( null === $link ) {
            return new WP_Error(
                'uglylinks_not_found',
                __( 'Link not found.', 'ug-links' ),
                [ 'status' => 404 ]
            );
        }

        return $link;
    }

    /**
     * Ensure redirect_type is a valid code string.
     *
     * @param string $raw
     * @return string '301', '302', or '307'
     */
    private function validateRedirectType( string $raw ): string {
        return in_array( $raw, [ '301', '302', '307' ], true ) ? $raw : '307';
    }

    /**
     * Build shared arg schema for write endpoints.
     *
     * @param bool $required Whether fields are required (true for POST).
     * @return array<string, array<string, mixed>>
     */
    private function linkWriteArgs( bool $required ): array {
        return [
            'slug'          => [
                'type'              => 'string',
                'required'          => $required,
                'sanitize_callback' => static fn( $v ) => sanitize_title_with_dashes( (string) $v ),
                'validate_callback' => static fn( $v ) => '' !== trim( (string) $v ),
            ],
            'target_url'    => [
                'type'              => 'string',
                'required'          => $required,
                'sanitize_callback' => static fn( $v ) => esc_url_raw( (string) $v ),
                'validate_callback' => static fn( $v ) => filter_var( (string) $v, FILTER_VALIDATE_URL ) !== false,
            ],
            'title'         => [
                'type'              => 'string',
                'required'          => false,
                'default'           => '',
                'sanitize_callback' => static fn( $v ) => sanitize_text_field( (string) $v ),
            ],
            'redirect_type' => [
                'type'              => 'string',
                'required'          => false,
                'default'           => '307',
                'enum'              => [ '301', '302', '307' ],
            ],
            'ab_target' => [
                'type'              => 'string',
                'required'          => false,
            ],
            'geo_rules' => [
                'type'              => 'string',
                'required'          => false,
            ],
            'retargeting_pixels' => [
                'type'              => 'string',
                'required'          => false,
            ],
        ];
    }
}
