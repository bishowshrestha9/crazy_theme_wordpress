<?php
/**
 * Click Model – High-Information Metadata Assembler and Raw SQL insert.
 *
 * @package ShortLinks\App\Models
 */

declare(strict_types=1);

namespace ShortLinks\App\Models;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Click
 */
final class Click {

    /**
     * Run High-Information Metadata Assembler and insert into shortlinks_clicks.
     * Fires `shortlinks_record_click` action afterwards.
     *
     * @param int $linkId The primary key of the clicked link.
     * @return bool True on success, false on DB error.
     */
    public static function logClick( int $linkId ): bool {
        $db         = Database::wpdb();
        $prli_table = Database::shortLinksClicksTable();

        $ip         = self::getVisitorIp();
        $geo        = self::getVisitorCountry( $ip );
        $referer    = isset( $_SERVER['HTTP_REFERER'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : '';
        $browser_os = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';
        
        $inserted = $db->insert(
            $prli_table,
            [
                'link_id'    => $linkId,
                'ip'         => $ip,
                'geo'        => $geo,
                'referer'    => $referer,
                'browser_os' => $browser_os,
                'created_at' => current_time( 'mysql' ),
            ],
            [ '%d', '%s', '%s', '%s', '%s', '%s' ]
        );

        if ( $inserted ) {
            $click_id = $db->insert_id;
            do_action( 'shortlinks_record_click', $click_id, $linkId );
            return true;
        }

        return false;
    }

    /**
     * Retrieve the total click count for a specific link.
     *
     * @param int $linkId The link primary key.
     * @return int
     */
    public static function countForLink( int $linkId ): int {
        $db         = Database::wpdb();
        $prli_table = Database::shortLinksClicksTable();

        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $total = $db->get_var(
            $db->prepare(
                "SELECT COUNT(*) FROM {$prli_table} WHERE link_id = %d",
                $linkId
            )
        );
        // phpcs:enable

        return (int) $total;
    }

    /**
     * Return daily click totals for a link over the past N days.
     *
     * @param int $linkId The link primary key.
     * @param int $days   How many trailing days to include.
     * @return object[]
     */
    public static function statsByDay( int $linkId, int $days = 30 ): array {
        $db         = Database::wpdb();
        $prli_table = Database::shortLinksClicksTable();
        $from       = gmdate( 'Y-m-d H:i:s', strtotime( "-{$days} days" ) );

        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $rows = $db->get_results(
            $db->prepare(
                "SELECT DATE(created_at) as date, COUNT(*) AS clicks
                 FROM {$prli_table}
                 WHERE link_id = %d
                   AND created_at >= %s
                 GROUP BY DATE(created_at)
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
        $db         = Database::wpdb();
        $prli_table = Database::shortLinksClicksTable();

        // phpcs:disable WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $rows = $db->get_results(
            $db->prepare(
                "SELECT referer as referrer_domain, COUNT(*) AS clicks
                 FROM {$prli_table}
                 WHERE link_id = %d AND referer != ''
                 GROUP BY referer
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
     * Delete all clicks for a link.
     *
     * @param int $linkId The link primary key.
     * @return void
     */
    public static function deleteForLink( int $linkId ): void {
        $db = Database::wpdb();
        $db->delete(
            Database::shortLinksClicksTable(),
            [ 'link_id' => $linkId ],
            [ '%d' ]
        );
    }

    /**
     * Extract real IP.
     */
    private static function getVisitorIp(): string {
        $headers = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        ];
        foreach ( $headers as $key ) {
            if ( array_key_exists( $key, $_SERVER ) === true ) {
                foreach ( explode( ',', $_SERVER[ $key ] ) as $ip ) {
                    $ip = trim( $ip ); 
                    if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false ) {
                        return $ip;
                    }
                }
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? '';
    }

    /**
     * Geolocation using transient cache + IP-API.
     */
    private static function getVisitorCountry( string $ip ): string {
        if ( empty( $ip ) || $ip === '127.0.0.1' || $ip === '::1' ) {
            return '';
        }
        $transient_key = 'geo_ip_' . md5( $ip );
        $country = get_transient( $transient_key );
        if ( false !== $country ) {
            return $country;
        }
        $response = wp_remote_get( 'http://ip-api.com/json/' . $ip . '?fields=countryCode', [ 'timeout' => 2 ] );
        if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
            $body = wp_remote_retrieve_body( $response );
            $data = json_decode( $body, true );
            if ( ! empty( $data['countryCode'] ) ) {
                set_transient( $transient_key, $data['countryCode'], HOUR_IN_SECONDS * 24 );
                return $data['countryCode'];
            }
        }
        return '';
    }
}
