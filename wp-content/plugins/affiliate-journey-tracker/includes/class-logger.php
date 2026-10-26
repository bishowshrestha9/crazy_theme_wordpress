<?php
namespace AffiliateJourneyTracker;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Logger Class
 *
 * Handles debugging, click tracking, and event logging for Affiliate Journey Tracker.
 */
class Logger {

	/**
	 * Option key where recent log entries are cached.
	 */
	private const LOG_OPTION = 'affiliate_jt_logs';

	/**
	 * Maximum number of logs stored in wp_options.
	 */
	private const MAX_LOG_ENTRIES = 200;

	/**
	 * Maximum file size before log rotation (5 MB).
	 */
	private const MAX_LOG_FILE_SIZE = 5242880;

	/**
	 * Log a structured journey or click event.
	 *
	 * @param array  $data  Event data array (event_type, origin_slug, source_url, target_url, message).
	 * @param string $level Log level ('info', 'warning', 'debug', 'error').
	 * @return bool True if logged, false otherwise.
	 */
	public static function log_event( array $data, string $level = 'info' ): bool {
		$enabled = function_exists( 'get_option' ) ? \get_option( 'affiliate_jt_enable_logs', 1 ) : 1;
		if ( ! $enabled ) {
			return false;
		}

		$raw_event_type = isset( $data['event_type'] ) ? sanitize_text_field( $data['event_type'] ) : 'General';
		$allowed_events = array( 'Button Click', 'Pretty Link Redirect', 'System', 'General', 'HTML Render', 'Test', 'Impression', 'Migration' );
		$event_type     = in_array( $raw_event_type, $allowed_events, true ) ? $raw_event_type : 'General';

		$raw_origin  = isset( $data['origin_slug'] ) ? sanitize_title( $data['origin_slug'] ) : 'N/A';
		$origin_slug = substr( ! empty( $raw_origin ) ? $raw_origin : 'N/A', 0, 200 );

		$raw_source = isset( $data['source_url'] ) ? esc_url_raw( $data['source_url'] ) : '';
		$source_url = substr( $raw_source, 0, 2048 );

		$raw_target = isset( $data['target_url'] ) ? esc_url_raw( $data['target_url'] ) : '';
		$target_url = substr( $raw_target, 0, 2048 );

		$raw_msg = isset( $data['message'] ) ? sanitize_text_field( $data['message'] ) : '';
		$message = substr( $raw_msg, 0, 1000 );

		$timestamp  = function_exists( 'current_time' ) ? \current_time( 'mysql' ) : date( 'Y-m-d H:i:s' );
		$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ), 0, 500 ) : '';

		$entry = array(
			'timestamp'   => $timestamp,
			'level'       => strtoupper( substr( $level, 0, 10 ) ),
			'event_type'  => $event_type,
			'origin_slug' => $origin_slug,
			'source_url'  => $source_url,
			'target_url'  => $target_url,
			'message'     => $message,
			'ip'          => self::get_user_ip(),
			'user_agent'  => $user_agent,
		);

		// Store in wp_options log buffer.
		if ( function_exists( 'get_option' ) && function_exists( 'update_option' ) ) {
			$logs = \get_option( self::LOG_OPTION, array() );
			if ( ! is_array( $logs ) ) {
				$logs = array();
			}

			array_unshift( $logs, $entry );

			if ( count( $logs ) > self::MAX_LOG_ENTRIES ) {
				$logs = array_slice( $logs, 0, self::MAX_LOG_ENTRIES );
			}

			\update_option( self::LOG_OPTION, $logs, false );
		}

		// Write to upload directory log file.
		self::write_to_file( $entry );

		return true;
	}

	/**
	 * Log a message if logging is enabled (backward compatibility).
	 *
	 * @param string $message Log message.
	 * @param string $level   Log level ('info', 'warning', 'debug', 'error').
	 * @return bool True if logged, false otherwise.
	 */
	public static function log( string $message, string $level = 'info' ): bool {
		return self::log_event(
			array(
				'event_type' => 'System',
				'message'    => $message,
			),
			$level
		);
	}

	/**
	 * Retrieve or generate secret hash for protected log filenames.
	 *
	 * @return string 16-character hex hash string.
	 */
	private static function get_log_secret_hash(): string {
		if ( function_exists( 'get_option' ) && function_exists( 'update_option' ) ) {
			$hash = \get_option( 'affiliate_jt_log_hash', '' );
			if ( empty( $hash ) || ! is_string( $hash ) || strlen( $hash ) < 16 ) {
				$hash = function_exists( 'wp_generate_password' )
					? wp_generate_password( 16, false, false )
					: substr( md5( uniqid( (string) wp_rand(), true ) ), 0, 16 );
				$hash = strtolower( $hash );
				\update_option( 'affiliate_jt_log_hash', $hash, false );
			}
			return $hash;
		}
		return 'default';
	}

	/**
	 * Write log entry to upload folder file safely.
	 *
	 * @param array $entry Log entry array.
	 */
	private static function write_to_file( array $entry ): void {
		if ( ! function_exists( 'wp_upload_dir' ) ) {
			return;
		}

		$upload_dir = \wp_upload_dir();
		$log_dir    = $upload_dir['basedir'] . '/affiliate-journey-tracker';

		if ( ! file_exists( $log_dir ) ) {
			if ( function_exists( 'wp_mkdir_p' ) ) {
				\wp_mkdir_p( $log_dir );
			} else {
				@mkdir( $log_dir, 0755, true );
			}
		}

		if ( file_exists( $log_dir ) ) {
			if ( ! file_exists( $log_dir . '/index.html' ) ) {
				@file_put_contents( $log_dir . '/index.html', '' );
			}
			if ( ! file_exists( $log_dir . '/index.php' ) ) {
				@file_put_contents( $log_dir . '/index.php', "<?php\nif ( function_exists( 'status_header' ) ) {\n\tstatus_header( 403 );\n} else {\n\theader( 'HTTP/1.0 403 Forbidden' );\n}\nexit;\n" );
			}
			if ( ! file_exists( $log_dir . '/.htaccess' ) ) {
				@file_put_contents( $log_dir . '/.htaccess', "Deny from all\n" );
			}
		}

		$hash     = self::get_log_secret_hash();
		$log_file = $log_dir . '/tracker-' . $hash . '.log';
		$rot_file = $log_dir . '/tracker-' . $hash . '.log.1';

		// Perform log rotation if file exceeds 5 MB
		if ( file_exists( $log_file ) && @filesize( $log_file ) >= self::MAX_LOG_FILE_SIZE ) {
			@rename( $log_file, $rot_file );
		}

		$line = sprintf(
			"[%s] [%s] [%s] (IP: %s) [Ref: %s] %s | Source: %s -> Target: %s\n",
			$entry['timestamp'],
			$entry['level'],
			$entry['event_type'],
			$entry['ip'],
			$entry['origin_slug'],
			$entry['message'],
			$entry['source_url'],
			$entry['target_url']
		);

		@file_put_contents( $log_file, $line, FILE_APPEND | LOCK_EX );
	}

	/**
	 * Get recent log entries.
	 *
	 * @param int $limit Max items to retrieve.
	 * @return array Array of log entries.
	 */
	public static function get_logs( int $limit = 100 ): array {
		if ( ! function_exists( 'get_option' ) ) {
			return array();
		}
		$logs = \get_option( self::LOG_OPTION, array() );
		if ( ! is_array( $logs ) ) {
			return array();
		}

		return array_slice( $logs, 0, $limit );
	}

	/**
	 * Clear all stored logs.
	 *
	 * @return bool True on success.
	 */
	public static function clear_logs(): bool {
		if ( function_exists( 'delete_option' ) ) {
			\delete_option( self::LOG_OPTION );
		}

		if ( function_exists( 'wp_upload_dir' ) ) {
			$upload_dir = \wp_upload_dir();
			$log_dir    = $upload_dir['basedir'] . '/affiliate-journey-tracker';

			if ( file_exists( $log_dir ) ) {
				$hash     = self::get_log_secret_hash();
				$log_file = $log_dir . '/tracker-' . $hash . '.log';
				$rot_file = $log_dir . '/tracker-' . $hash . '.log.1';
				$old_file = $log_dir . '/tracker.log';

				if ( file_exists( $log_file ) ) {
					@file_put_contents( $log_file, '' );
				}
				if ( file_exists( $rot_file ) ) {
					@unlink( $rot_file );
				}
				if ( file_exists( $old_file ) ) {
					@file_put_contents( $old_file, '' );
				}
			}
		}

		return true;
	}

	/**
	 * Helper to safely sanitize client IP address.
	 *
	 * @return string User IP address.
	 */
	private static function get_user_ip(): string {
		$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '127.0.0.1';
		return filter_var( $ip, FILTER_VALIDATE_IP ) ? $ip : '127.0.0.1';
	}
}
