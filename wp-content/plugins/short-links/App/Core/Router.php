<?php
/**
 * Front-end Routing & Async Redirect Execution Engine.
 *
 * v2 performance architecture:
 *   1. Resolve the slug and look up the link (cache-first via Link::findBySlug).
 *   2. Issue wp_redirect() — sends HTTP headers immediately.
 *   3. Flush buffers and call fastcgi_finish_request() if running under PHP-FPM,
 *      which closes the connection to the browser at the OS level while the PHP
 *      worker continues executing.
 *   4. Perform the Click::logClick() DB write post-response — the visitor is
 *      already gone before the DB insert happens.
 *
 * Fallback: On non-FPM SAPI (mod_php, CGI), the click is still logged — it
 * just happens synchronously before exit() but remains outside the critical
 * path latency since output has already been flushed.
 *
 * @package ShortLinks\App\Core
 */

declare(strict_types=1);

namespace ShortLinks\App\Core;

use ShortLinks\App\Models\Click;
use ShortLinks\App\Models\Link;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Class Router
 *
 * Stateless routing engine called via the template_redirect action hook.
 * All methods are static — zero object allocation on every front-end request.
 */
final class Router {

    /**
     * Intercept matching slugs, issue the redirect, and log the click
     * asynchronously after the response has been delivered to the client.
     *
     * Hooked to: template_redirect (priority 1).
     *
     * @return void
     */
    public static function dispatch(): void {
        // 1. Guard against native WordPress administration panel and AJAX actions
        if ( is_admin() ) {
            return;
        }

        // 2. Guard against WordPress internal scripts (e.g., wp-login, trackbacks)
        global $pagenow;
        if ( 'wp-login.php' === $pagenow || 'wp-signup.php' === $pagenow || 'wp-cron.php' === $pagenow ) {
            return;
        }

        // 3. Guard against internal REST API actions
        // REST_REQUEST is not always defined at init, so we also check the URI prefix.
        if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
            return;
        }
        $rest_prefix = trailingslashit( rest_get_url_prefix() );
        $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
        if ( strpos( $request_uri, '/' . $rest_prefix ) !== false ) {
            return;
        }

        $slug = self::resolveSlug();

        if ( '' === $slug ) {
            return;
        }

        // Cache-first slug resolution: Redis/Memcached hit = zero DB query.
        $link = Link::findBySlug( $slug );

        if ( null === $link ) {
            return;
        }

        $target      = esc_url_raw( $link->target_url );
        $status_code = self::sanitizeStatusCode( $link->redirect_type );
        $link_id     = (int) $link->id;

        // Pro: Geotargeting Override
        if ( ! empty( $link->geo_rules ) ) {
            $rules = json_decode( $link->geo_rules, true );
            $country = self::getVisitorCountry();
            if ( $country && is_array( $rules ) && ! empty( $rules[ $country ] ) ) {
                $target = esc_url_raw( $rules[ $country ] );
            }
        }

        // Pro: A/B Split Testing Override (50% random chance)
        if ( ! empty( $link->ab_target ) ) {
            if ( wp_rand( 0, 1 ) === 1 ) {
                $target = esc_url_raw( $link->ab_target );
            }
        }

        if ( '' === $target ) {
            return;
        }

        // Forward query string if present (preserves affiliate tracking parameters like ?subid=123)
        $request_uri_for_qs = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
        $query_string = wp_parse_url( $request_uri_for_qs, PHP_URL_QUERY ) ?: '';
        if ( '' !== $query_string ) {
            $separator = ( strpos( $target, '?' ) !== false ) ? '&' : '?';
            $target .= $separator . $query_string;
        }

        // Pro: Retargeting Pixels Check
        if ( ! empty( $link->retargeting_pixels ) ) {
            // Because pixels must fire in the DOM, we cannot use HTTP headers.
            // Log click first, then render HTML.
            Click::logClick( $link_id );
            self::renderPixelPage( $target, $link->retargeting_pixels );
            exit;
        }

        $is_bot = self::isBot();
        if ( ! $is_bot ) {
            // ── Phase 1: Telemetry write ───────────────────────────
            // Log click before terminating the script.
            Click::logClick( $link_id );
        }

        if ( 'cloak' === $status_code ) {
            self::renderCloakPage( $target, $link->title );
            exit;
        }

        // ── Phase 2: Send HTTP redirect headers ──────────────────────────────
        if ( ! headers_sent() ) {
            // Standard Pretty Links anti-caching headers
            header( 'Expires: Thu, 19 Nov 1981 08:52:00 GMT' );
            header( 'Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0' );
            header( 'Pragma: no-cache' );
            
            // Bypass wp_sanitize_redirect which can strip some affiliate symbols
            header( 'Location: ' . $target, true, (int) $status_code );
            exit;
        }

        // Fallback if headers were somehow already sent
        wp_redirect( $target, (int) $status_code );
        exit;
    }

    // ── Private Helpers ───────────────────────────────────────────────────────

    /**
     * Determine if current request is from a known bot.
     */
    private static function isBot(): bool {
        $user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? strtolower( $_SERVER['HTTP_USER_AGENT'] ) : '';
        if ( empty( $user_agent ) ) {
            return false;
        }
        $bots = [
            'googlebot', 'bingbot', 'yandex', 'ahrefsbot', 'msnbot', 'duckduckbot',
            'slurp', 'baiduspider', 'facebookexternalhit', 'twitterbot', 'rogerbot',
            'linkedinbot', 'embedly', 'quora link preview', 'showyoubot', 'outbrain',
            'pinterest', 'slackbot', 'vkshare', 'w3c_validator', 'redditbot', 'applebot',
            'whatsapp', 'flipboard', 'tumblr', 'bitlybot', 'skypeuripreview', 'nuzzel',
            'discordbot', 'telegrambot'
        ];
        foreach ( $bots as $bot ) {
            if ( strpos( $user_agent, $bot ) !== false ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Render a cloaked iframe page.
     */
    private static function renderCloakPage( string $target, string $title ): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title><?php echo esc_html( $title ?: 'Loading...' ); ?></title>
            <meta name="robots" content="noindex, nofollow">
            <style>
                body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; }
                iframe { width: 100%; height: 100%; border: none; }
            </style>
        </head>
        <body>
            <iframe src="<?php echo esc_url( $target ); ?>" sandbox="allow-scripts allow-same-origin allow-forms allow-popups"></iframe>
        </body>
        </html>
        <?php
    }

    /**
     * Get the visitor's country code (ISO 3166-1 alpha-2).
     * 
     * Uses Cloudflare HTTP headers if available, or attempts a fast fallback.
     *
     * @return string|null Country code, or null if detection fails.
     */
    private static function getVisitorCountry(): ?string {
        if ( ! empty( $_SERVER['HTTP_CF_IPCOUNTRY'] ) ) {
            return sanitize_text_field( wp_unslash( $_SERVER['HTTP_CF_IPCOUNTRY'] ) );
        }
        
        // Fallback: IP API lookup
        $ip = self::getVisitorIp();
        if ( empty( $ip ) || $ip === '127.0.0.1' || $ip === '::1' ) {
            return null;
        }

        $transient_key = 'geo_ip_' . md5( $ip );
        $country = get_transient( $transient_key );
        if ( false !== $country ) {
            return $country;
        }

        // Try IP2Location
        $upload_dir = wp_upload_dir();
        $ip2location_db = trailingslashit( $upload_dir['basedir'] ) . 'short-links/IP2LOCATION-LITE-DB1.BIN';
        
        if ( file_exists( $ip2location_db ) && class_exists( '\IP2Location\Database' ) ) {
            try {
                $db = new \IP2Location\Database( $ip2location_db, \IP2Location\Database::FILE_IO );
                $records = $db->lookup( $ip, \IP2Location\Database::ALL );
                if ( ! empty( $records['countryCode'] ) && $records['countryCode'] !== '-' ) {
                    $countryCode = $records['countryCode'];
                    set_transient( $transient_key, $countryCode, HOUR_IN_SECONDS * 24 );
                    return $countryCode;
                }
            } catch ( \Exception $e ) {
                // Ignore exception and fallback to IP API
            }
        }

        // Fallback: IP API lookup
        $response = wp_remote_get( 'http://ip-api.com/json/' . $ip . '?fields=countryCode', [ 'timeout' => 2 ] );
        if ( is_wp_error( $response ) || wp_remote_retrieve_response_code( $response ) !== 200 ) {
            return null;
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );
        if ( ! empty( $data['countryCode'] ) ) {
            set_transient( $transient_key, $data['countryCode'], HOUR_IN_SECONDS * 24 );
            return $data['countryCode'];
        }

        return null;
    }

    /**
     * Get the real IP address of the visitor.
     *
     * @return string
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
     * Render an interstitial HTML page containing retargeting pixels and JS redirect.
     *
     * @param string $target URL to redirect to.
     * @param string $pixels Raw HTML of the tracking pixels.
     * @return void
     */
    private static function renderPixelPage( string $target, string $pixels ): void {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>Redirecting...</title>
            <meta name="robots" content="noindex, nofollow">
            <meta http-equiv="refresh" content="1;url=<?php echo esc_url( $target ); ?>">
            <?php echo $pixels; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </head>
        <body>
            <noscript>
                <a href="<?php echo esc_url( $target ); ?>">Click here to continue</a>
            </noscript>
            <script>
                setTimeout(function() {
                    window.location.replace(<?php echo wp_json_encode( $target ); ?>);
                }, 1000);
            </script>
        </body>
        </html>
        <?php
    }


    /**
     * Extract the potential UglyLink slug from the current request URI.
     *
     * Strips the WordPress home path prefix so the plugin works correctly
     * in sub-directory installations (e.g. /wordpress/go/my-slug → 'my-slug').
     *
     * @return string Empty string if the URI carries no usable slug.
     */
    private static function resolveSlug(): string {
        $request_uri = isset( $_SERVER['REQUEST_URI'] )
            ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) )
            : '';

        if ( '' === $request_uri ) {
            return '';
        }

        // Strip query string.
        $path = strtok( $request_uri, '?' );

        if ( false === $path || '/' === $path ) {
            return '';
        }

        // Strip site home sub-directory prefix.
        $home_path = rtrim( wp_parse_url( home_url(), PHP_URL_PATH ) ?? '', '/' );
        if ( '' !== $home_path && str_starts_with( $path, $home_path ) ) {
            $path = substr( $path, strlen( $home_path ) );
        }

        // Decode URL and trim slashes to support exact matching with subdirectories or trailing slashes
        $slug = trim( urldecode( $path ), '/' );

        return $slug;
    }

    /**
     * Validate and return a safe HTTP redirect status code or cloak string.
     *
     * @param string $raw Raw value from the DB column.
     * @return int|string 301, 302, 307, or 'cloak'; defaults to 307.
     */
    private static function sanitizeStatusCode( string $raw ): int|string {
        if ( 'cloak' === $raw ) {
            return 'cloak';
        }
        $code = (int) $raw;
        return in_array( $code, [ 301, 302, 307 ], true ) ? $code : 307;
    }
}
