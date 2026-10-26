<?php
/**
 * Database Abstraction Layer.
 *
 * Provisions custom tables on activation and provides
 * a thin wrapper around the global $wpdb instance.
 *
 * Schema v2:
 *   - ugly_links         — link definitions (unchanged)
 *   - ugly_clicks_agg    — aggregated click counters
 *                          (link_id + date + referrer_domain composite PK)
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
 * Class Database
 *
 * Responsible for schema provisioning and table-name resolution.
 */
final class Database {

    // ── Object-Cache Constants ────────────────────────────────────────────────

    /** Cache group shared by all UglyLinks object-cache entries. */
    public const CACHE_GROUP = 'uglylinks';

    /** TTL in seconds for slug → link object cache entries (24 h). */
    public const CACHE_TTL_SLUG = 86_400;

    // ── Table Names ──────────────────────────────────────────────────────────

    /**
     * Fully-prefixed links table name.
     *
     * @return string
     */
    public static function linksTable(): string {
        global $wpdb;
        return $wpdb->prefix . 'ugly_links';
    }

    /**
     * Fully-prefixed aggregated clicks table name.
     *
     * Replaces the legacy per-row ugly_clicks table with an
     * INSERT … ON DUPLICATE KEY UPDATE aggregation approach.
     *
     * @return string
     */
    public static function clicksAggTable(): string {
        global $wpdb;
        return $wpdb->prefix . 'ugly_clicks_agg';
    }

    // ── Schema Provisioning ──────────────────────────────────────────────────

    /**
     * Run dbDelta() to create or upgrade plugin tables.
     *
     * Called on plugin activation via register_activation_hook.
     *
     * @return void
     */
    public static function provision(): void {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $links_table = self::linksTable();
        $agg_table   = self::clicksAggTable();

        // ── Links table (unchanged from v1) ───────────────────────────────────
        $sql_links = "CREATE TABLE {$links_table} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            slug VARCHAR(100) NOT NULL DEFAULT '',
            target_url TEXT NOT NULL,
            title VARCHAR(255) NOT NULL DEFAULT '',
            redirect_type VARCHAR(3) NOT NULL DEFAULT '301',
            ab_target TEXT,
            geo_rules TEXT,
            retargeting_pixels TEXT,
            created_at DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
            PRIMARY KEY  (id),
            UNIQUE KEY slug (slug)
        ) {$charset_collate};";

        // ── Aggregated clicks table (v2) ──────────────────────────────────────
        // Composite PRIMARY KEY eliminates duplicate rows per (link, day, source).
        // click_count is incremented atomically via INSERT … ON DUPLICATE KEY UPDATE.
        $sql_agg = "CREATE TABLE {$agg_table} (
            link_id BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
            date DATE NOT NULL DEFAULT '0000-00-00',
            referrer_domain VARCHAR(255) NOT NULL DEFAULT '',
            click_count BIGINT(20) UNSIGNED NOT NULL DEFAULT 0,
            PRIMARY KEY  (link_id, date, referrer_domain),
            KEY date (date)
        ) {$charset_collate};";

        if ( ! function_exists( 'dbDelta' ) ) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }

        dbDelta( $sql_links );
        dbDelta( $sql_agg );

        update_option( 'uglylinks_db_version', UGLYLINKS_VERSION );
    }

    /**
     * Run provision() only when the stored DB version doesn't match the
     * plugin version OR when the aggregated clicks table is missing.
     *
     * Called on plugins_loaded so it self-heals after file-only upgrades
     * (situations where the activation hook never re-fired).
     *
     * @return void
     */
    public static function maybeUpgrade(): void {
        // Fast path: version option matches and key table exists.
        if (
            get_option( 'uglylinks_db_version' ) === UGLYLINKS_VERSION
            && self::tableExists( self::clicksAggTable() )
        ) {
            return;
        }

        self::provision();
    }

    /**
     * Check whether a table exists in the current DB.
     *
     * @param string $table Fully-prefixed table name.
     * @return bool
     */
    public static function tableExists( string $table ): bool {
        global $wpdb;
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $result = $wpdb->get_var(
            $wpdb->prepare( 'SHOW TABLES LIKE %s', $table )
        );
        return $result === $table;
    }

    // ── Query Helpers ────────────────────────────────────────────────────────

    /**
     * Return the global $wpdb instance for controlled external access.
     *
     * @return \wpdb
     */
    public static function wpdb(): \wpdb {
        global $wpdb;
        return $wpdb;
    }
}
