<?php
/**
 * Link Model – CRUD operations with Object-Cache layer.
 *
 * v2 changes:
 *   - findBySlug()   — check wp_cache_get before hitting DB; populate on miss.
 *   - update()       — invalidate both slug and ID cache keys on write.
 *   - delete()       — invalidate caches and delegate click cleanup to Click model.
 *   - all()          — rewritten to SUM(agg.click_count) from aggregation table.
 *   - forApi()       — serialises a single row to a plain array for REST responses.
 *
 * @package UglyLinks\App\Models
 */

declare(strict_types=1);

namespace UglyLinks\App\Models;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Link
 *
 * Provides type-safe, prepared-statement–driven CRUD for link records,
 * backed by WordPress Object Cache (Redis/Memcached transparent).
 *
 * @phpstan-type LinkRow array{
 *     id: int,
 *     slug: string,
 *     target_url: string,
 *     title: string,
 *     redirect_type: string,
 *     created_at: string
 * }
 */
final class Link {

    // ── Cache Key Helpers ────────────────────────────────────────────────────

    /**
     * Object-cache key for a slug → link object lookup.
     *
     * @param string $slug
     * @return string
     */
    private static function cacheKeyForSlug( string $slug ): string {
        return 'slug_' . md5( $slug );
    }

    /**
     * Object-cache key for an ID → link object lookup.
     *
     * @param int $id
     * @return string
     */
    private static function cacheKeyForId( int $id ): string {
        return 'id_' . $id;
    }

    /**
     * Bust all object-cache entries associated with a link record.
     *
     * Called after any write operation so stale data is never served.
     *
     * @param object $link Existing link row (must have ->id and ->slug).
     * @return void
     */
    private static function bustCache( object $link ): void {
        wp_cache_delete( self::cacheKeyForSlug( $link->slug ), Database::CACHE_GROUP );
        wp_cache_delete( self::cacheKeyForId( (int) $link->id ), Database::CACHE_GROUP );
    }

    // ── Retrieval ────────────────────────────────────────────────────────────

    /**
     * Find a single link record by its slug.
     *
     * Cache flow:
     *   1. wp_cache_get  → hit  → return cached object (zero DB query).
     *   2. wp_cache_get  → miss → SELECT from DB → wp_cache_set (24 h TTL).
     *
     * @param string $slug The short slug to look up.
     * @return object|null The row as a stdClass object, or null if not found.
     */
    public static function findBySlug( string $slug ): ?object {
        $cache_key = self::cacheKeyForSlug( $slug );

        // 1. Try object cache first.
        $cached = wp_cache_get( $cache_key, Database::CACHE_GROUP );

        if ( false !== $cached ) {
            // 'null_sentinel' means we previously confirmed this slug doesn't exist.
            return 'null_sentinel' === $cached ? null : $cached;
        }

        // 2. Cache miss — query the DB.
        $db         = Database::wpdb();
        $links_table = Database::linksTable();

        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $row = $db->get_row(
            $db->prepare(
                "SELECT * FROM {$links_table} WHERE slug = %s LIMIT 1",
                $slug
            )
        );
        // phpcs:enable

        // 3. Populate cache. Cache a sentinel string for missing slugs to
        //    prevent repeated DB hits for unknown slugs (negative caching).
        if ( null === $row ) {
            wp_cache_set(
                $cache_key,
                'null_sentinel',
                Database::CACHE_GROUP,
                Database::CACHE_TTL_SLUG
            );
            return null;
        }

        wp_cache_set( $cache_key, $row, Database::CACHE_GROUP, Database::CACHE_TTL_SLUG );

        return $row;
    }

    /**
     * Find a single link record by its primary key.
     *
     * Also cache-backed with a 24 h TTL.
     *
     * @param int $id The record ID.
     * @return object|null
     */
    public static function findById( int $id ): ?object {
        $cache_key = self::cacheKeyForId( $id );

        $cached = wp_cache_get( $cache_key, Database::CACHE_GROUP );

        if ( false !== $cached ) {
            return 'null_sentinel' === $cached ? null : $cached;
        }

        $db          = Database::wpdb();
        $links_table = Database::linksTable();

        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $row = $db->get_row(
            $db->prepare(
                "SELECT * FROM {$links_table} WHERE id = %d LIMIT 1",
                $id
            )
        );
        // phpcs:enable

        if ( null === $row ) {
            wp_cache_set( $cache_key, 'null_sentinel', Database::CACHE_GROUP, Database::CACHE_TTL_SLUG );
            return null;
        }

        wp_cache_set( $cache_key, $row, Database::CACHE_GROUP, Database::CACHE_TTL_SLUG );

        return $row;
    }

    /**
     * Return all link rows (newest first) joined with aggregated click totals.
     *
     * Click totals come from SUM(agg.click_count) rather than COUNT(row)
     * so they remain accurate as the aggregation table is the source of truth.
     *
     * @param int $limit  Max rows to return.
     * @param int $offset Pagination offset.
     * @return object[] Array of stdClass rows (each has a `total_clicks` property).
     */
    public static function all( int $limit = 50, int $offset = 0 ): array {
        $db          = Database::wpdb();
        $links_table = Database::linksTable();
        $agg_table   = Database::clicksAggTable();

        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $results = $db->get_results(
            $db->prepare(
                "SELECT l.*, COALESCE(SUM(a.click_count), 0) AS total_clicks
                 FROM {$links_table} l
                 LEFT JOIN {$agg_table} a ON a.link_id = l.id
                 GROUP BY l.id
                 ORDER BY l.created_at DESC
                 LIMIT %d OFFSET %d",
                $limit,
                $offset
            )
        );
        // phpcs:enable

        return $results ?: [];
    }

    /**
     * Total number of link records (for pagination).
     *
     * @return int
     */
    public static function count(): int {
        $db = Database::wpdb();
        return (int) $db->get_var( 'SELECT COUNT(*) FROM ' . Database::linksTable() );
    }

    // ── Write Operations ─────────────────────────────────────────────────────

    /**
     * Insert a new link record.
     *
     * No cache population needed on create — the slug is unknown to the
     * cache until its first request.
     *
     * @param string $slug          Short slug (unique).
     * @param string $target_url    Destination URL.
     * @param string $title         Human-readable label.
     * @param string $redirect_type HTTP redirect code ('301' or '302').
     * @return int|false The new record ID, or false on failure.
     */
    public static function create(
        string $slug,
        string $target_url,
        string $title              = '',
        string $redirect_type      = '301',
        ?string $ab_target         = null,
        ?string $geo_rules         = null,
        ?string $retargeting_pixels= null
    ): int|false {
        $db     = Database::wpdb();
        $result = $db->insert(
            Database::linksTable(),
            [
                'slug'               => $slug,
                'target_url'         => $target_url,
                'title'              => $title,
                'redirect_type'      => $redirect_type,
                'ab_target'          => $ab_target,
                'geo_rules'          => $geo_rules,
                'retargeting_pixels' => $retargeting_pixels,
                'created_at'         => current_time( 'mysql' ),
            ],
            [ '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s' ]
        );

        if ( false === $result ) {
            return false;
        }

        return (int) $db->insert_id;
    }

    /**
     * Update an existing link record by ID.
     *
     * Busts the object-cache entries for both the OLD slug (loaded by ID before
     * update) and the NEW slug so stale redirect definitions are never served.
     *
     * @param int    $id            Record ID.
     * @param string $slug          New slug value.
     * @param string $target_url    New destination URL.
     * @param string $title         New title.
     * @param string $redirect_type New redirect type.
     * @return bool True on success, false on failure.
     */
    public static function update(
        int    $id,
        string $slug,
        string $target_url,
        string $title              = '',
        string $redirect_type      = '301',
        ?string $ab_target         = null,
        ?string $geo_rules         = null,
        ?string $retargeting_pixels= null
    ): bool {
        // Fetch before writing so we can bust the OLD slug cache key.
        $existing = self::findById( $id );

        $db            = Database::wpdb();
        $rows_affected = $db->update(
            Database::linksTable(),
            [
                'slug'               => $slug,
                'target_url'         => $target_url,
                'title'              => $title,
                'redirect_type'      => $redirect_type,
                'ab_target'          => $ab_target,
                'geo_rules'          => $geo_rules,
                'retargeting_pixels' => $retargeting_pixels,
            ],
            [ 'id' => $id ],
            [ '%s', '%s', '%s', '%s', '%s', '%s', '%s' ],
            [ '%d' ]
        );

        if ( false === $rows_affected ) {
            return false;
        }

        // Bust old slug cache.
        if ( null !== $existing ) {
            self::bustCache( $existing );
        }

        // Also bust the new slug (might have been a negative sentinel).
        wp_cache_delete( self::cacheKeyForSlug( $slug ), Database::CACHE_GROUP );

        return true;
    }

    /**
     * Delete a link record and all its aggregated click data.
     *
     * @param int $id Record ID.
     * @return bool
     */
    public static function delete( int $id ): bool {
        $existing = self::findById( $id );

        // Bust cache before deleting so no stale entries remain.
        if ( null !== $existing ) {
            self::bustCache( $existing );
        }

        // Remove aggregated click data via Click model.
        Click::deleteForLink( $id );

        $db     = Database::wpdb();
        $result = $db->delete(
            Database::linksTable(),
            [ 'id' => $id ],
            [ '%d' ]
        );

        return false !== $result;
    }

    // ── Serialisation ─────────────────────────────────────────────────────────

    /**
     * Serialise a link row to a clean array for REST API responses.
     *
     * @param object   $link        Link DB row.
     * @param int|null $totalClicks Pre-computed click total (skips extra query if provided).
     * @return array<string, mixed>
     */
    public static function forApi( object $link, ?int $totalClicks = null ): array {
        return [
            'id'            => (int) $link->id,
            'slug'          => $link->slug,
            'short_url'     => home_url( '/' . $link->slug ),
            'target_url'    => $link->target_url,
            'title'         => $link->title,
            'redirect_type' => $link->redirect_type,
            'created_at'    => $link->created_at,
            'total_clicks'  => $totalClicks ?? Click::countForLink( (int) $link->id ),
        ];
    }

    // ── Validation Helpers ────────────────────────────────────────────────────

    /**
     * Determine whether a slug is already taken by a different record.
     *
     * @param string   $slug       Slug to test.
     * @param int|null $exclude_id Optionally exclude a specific record ID.
     * @return bool
     */
    public static function slugExists( string $slug, ?int $exclude_id = null ): bool {
        $db = Database::wpdb();

        $links_table = Database::linksTable();

        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        if ( null !== $exclude_id ) {
            $count = $db->get_var(
                $db->prepare(
                    "SELECT COUNT(*) FROM {$links_table} WHERE slug = %s AND id != %d",
                    $slug,
                    $exclude_id
                )
            );
        } else {
            $count = $db->get_var(
                $db->prepare(
                    "SELECT COUNT(*) FROM {$links_table} WHERE slug = %s",
                    $slug
                )
            );
        }
        // phpcs:enable

        return (int) $count > 0;
    }
}
