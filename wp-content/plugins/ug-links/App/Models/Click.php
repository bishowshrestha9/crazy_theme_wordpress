<?php
/**
 * Click Model – High-performance aggregated click-event logging.
 *
 * Architecture change from v1:
 *   Instead of one DB row per click (unbounded table growth), this model
 *   groups events by (link_id, date, referrer_domain) and atomically
 *   increments a click_count counter via INSERT … ON DUPLICATE KEY UPDATE.
 *
 * Benefits:
 *   - O(1) storage growth per link/day/domain combination.
 *   - Single index scan for total-count lookups (SUM vs COUNT(*)).
 *   - Compatible with MySQL, MariaDB, and Percona without transactions.
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
 * Class Click
 *
 * Provides aggregated, upsert-driven click tracking.
 */
final class Click {

    /** Maximum byte length for stored referrer domain strings. */
    private const MAX_DOMAIN_LENGTH = 255;

    // ── Public API ───────────────────────────────────────────────────────────

    /**
     * Atomically increment the click counter for a link/date/referrer bucket.
     *
     * Uses MySQL's INSERT … ON DUPLICATE KEY UPDATE idiom so no SELECT is
     * needed first — a single round-trip to the DB regardless of whether
     * the bucket row already exists.
     *
     * This method is designed to be called post-response (after
     * fastcgi_finish_request) so it never blocks the HTTP redirect.
     *
     * @param int $linkId The primary key of the clicked link.
     * @return bool True on success (insert or update), false on DB error.
     */
    public static function logClick( int $linkId ): bool {
        $db             = Database::wpdb();
        $agg_table      = Database::clicksAggTable();
        $today          = gmdate( 'Y-m-d' );
        $referrer_domain = self::resolveReferrerDomain();

        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $result = $db->query(
            $db->prepare(
                "INSERT INTO {$agg_table}
                    (link_id, date, referrer_domain, click_count)
                 VALUES
                    (%d, %s, %s, 1)
                 ON DUPLICATE KEY UPDATE
                    click_count = click_count + 1",
                $linkId,
                $today,
                $referrer_domain
            )
        );
        // phpcs:enable

        return false !== $result;
    }

    /**
     * Retrieve the total click count for a specific link.
     *
     * Sums all aggregated buckets for the given link_id — a single index
     * scan against the composite PK rather than a full table COUNT.
     *
     * @param int $linkId The link primary key.
     * @return int
     */
    public static function countForLink( int $linkId ): int {
        $db        = Database::wpdb();
        $agg_table = Database::clicksAggTable();

        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $total = $db->get_var(
            $db->prepare(
                "SELECT COALESCE(SUM(click_count), 0)
                 FROM {$agg_table}
                 WHERE link_id = %d",
                $linkId
            )
        );
        // phpcs:enable

        return (int) $total;
    }

    /**
     * Return daily click totals for a link over the past N days.
     *
     * Returns an array of stdClass objects with `date` and `clicks` properties,
     * ordered ascending by date — ready for chart rendering.
     *
     * @param int $linkId The link primary key.
     * @param int $days   How many trailing days to include (default 30).
     * @return object[]
     */
    public static function statsByDay( int $linkId, int $days = 30 ): array {
        $db        = Database::wpdb();
        $agg_table = Database::clicksAggTable();
        $from      = gmdate( 'Y-m-d', strtotime( "-{$days} days" ) );

        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $rows = $db->get_results(
            $db->prepare(
                "SELECT date, SUM(click_count) AS clicks
                 FROM {$agg_table}
                 WHERE link_id = %d
                   AND date >= %s
                 GROUP BY date
                 ORDER BY date ASC",
                $linkId,
                $from
            )
        );
        // phpcs:enable

        return $rows ?: [];
    }

    /**
     * Return top referrer domains for a link, ordered by total clicks.
     *
     * @param int $linkId The link primary key.
     * @param int $limit  Max rows to return.
     * @return object[] Array of stdClass with `referrer_domain` and `clicks`.
     */
    public static function topReferrers( int $linkId, int $limit = 10 ): array {
        $db        = Database::wpdb();
        $agg_table = Database::clicksAggTable();

        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $rows = $db->get_results(
            $db->prepare(
                "SELECT referrer_domain, SUM(click_count) AS clicks
                 FROM {$agg_table}
                 WHERE link_id = %d
                 GROUP BY referrer_domain
                 ORDER BY clicks DESC
                 LIMIT %d",
                $linkId,
                $limit
            )
        );
        // phpcs:enable

        return $rows ?: [];
    }

    /**
     * Delete all aggregated click rows for a link (called on link deletion).
     *
     * @param int $linkId The link primary key.
     * @return void
     */
    public static function deleteForLink( int $linkId ): void {
        $db = Database::wpdb();
        $db->delete(
            Database::clicksAggTable(),
            [ 'link_id' => $linkId ],
            [ '%d' ]
        );
    }

    // ── Private Resolvers ─────────────────────────────────────────────────────

    /**
     * Extract and normalise the referrer's registrable domain.
     *
     * Falls back to an empty string for direct / no-referrer traffic,
     * which is stored as '' in the aggregation bucket.
     *
     * @return string
     */
    private static function resolveReferrerDomain(): string {
        $raw = $_SERVER['HTTP_REFERER'] ?? '';

        if ( '' === $raw ) {
            return '';
        }

        $sanitized = sanitize_text_field( wp_unslash( $raw ) );
        $host      = wp_parse_url( $sanitized, PHP_URL_HOST );

        if ( ! is_string( $host ) || '' === $host ) {
            return '';
        }

        // Strip www. prefix for canonical grouping.
        $domain = preg_replace( '/^www\./i', '', strtolower( $host ) ) ?? $host;

        return substr( $domain, 0, self::MAX_DOMAIN_LENGTH );
    }
}
