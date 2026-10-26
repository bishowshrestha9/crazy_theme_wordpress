<?php
/**
 * Core Domain Scanner Engine for Pretty Links Target Domain Scanner.
 */

if (!defined('ABSPATH')) {
	exit;
}

class PRLI_TDS_Engine {

	/**
	 * Normalize user input domain or host.
	 *
	 * @param string $input
	 * @return string Clean host domain (e.g. "example.com")
	 */
	public static function normalize_domain(string $input): string {
		$input = trim($input);
		if (empty($input)) {
			return '';
		}

		// Remove whitespace
		$input = preg_replace('/\s+/', '', $input);
		$input = strtolower($input);

		// If missing scheme, add temporary http:// for parse_url
		if (!preg_match('#^https?://#i', $input)) {
			$input = 'http://' . ltrim($input, '/');
		}

		$host = wp_parse_url($input, PHP_URL_HOST);
		if (!$host) {
			// Fallback string cleaning if parse_url fails
			$host = preg_replace('#^https?://#i', '', $input);
			$host = strtok($host, '/');
			$host = strtok($host, '?');
			$host = strtok($host, ':');
		}

		$host = strtolower((string) $host);
		$host = preg_replace('/^www\./', '', $host);

		return trim($host);
	}

	/**
	 * Extract host from a target URL.
	 *
	 * @param string $url
	 * @return string Host domain or empty string if invalid
	 */
	public static function extract_host(string $url): string {
		$url = trim($url);
		if (empty($url)) {
			return '';
		}

		// Prepend scheme if missing for proper parse_url evaluation
		if (!preg_match('#^https?://#i', $url)) {
			$url = 'http://' . ltrim($url, '/');
		}

		$host = wp_parse_url($url, PHP_URL_HOST);
		if (!$host) {
			return '';
		}

		$host = strtolower((string) $host);
		$host = preg_replace('/^www\./', '', $host);

		return $host;
	}

	/**
	 * Compare host against expected domain.
	 *
	 * @param string $host Target URL's host
	 * @param string $expected_domain Input domain to check against
	 * @param bool   $allow_subdomains Whether subdomains count as a match
	 * @return bool True if matched, false otherwise
	 */
	public static function check_match(string $host, string $expected_domain, bool $allow_subdomains = true): bool {
		$host = strtolower(trim($host));
		$expected_domain = strtolower(trim($expected_domain));

		if (empty($host) || empty($expected_domain)) {
			return false;
		}

		if ($host === $expected_domain) {
			return true;
		}

		if ($allow_subdomains) {
			if (str_ends_with($host, '.' . $expected_domain)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if Pretty Links table exists in the database.
	 *
	 * @return bool
	 */
	public static function is_pretty_links_table_present(): bool {
		global $wpdb;
		$table_name = $wpdb->prefix . 'prli_links';
		$found = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table_name));
		return $found === $table_name;
	}

	/**
	 * Fetch count of all Pretty Links.
	 *
	 * @return int
	 */
	public static function get_total_links_count(): int {
		global $wpdb;
		if (!self::is_pretty_links_table_present()) {
			return 0;
		}
		$table_name = $wpdb->prefix . 'prli_links';
		return (int) $wpdb->get_var("SELECT COUNT(id) FROM {$table_name}");
	}

	/**
	 * Scan all Pretty Links against expected domain.
	 *
	 * @param string $input_domain The domain entered by user
	 * @param bool   $allow_subdomains
	 * @param int    $limit Optional limit for batch scanning
	 * @param int    $offset Optional offset for batch scanning
	 * @return array
	 */
	public static function scan_links(string $input_domain, bool $allow_subdomains = true, int $limit = 0, int $offset = 0): array {
		global $wpdb;

		if (!self::is_pretty_links_table_present()) {
			return array(
				'success' => false,
				'error'   => 'Pretty Links database table (' . esc_html($wpdb->prefix . 'prli_links') . ') was not found. Please verify that Pretty Links plugin is installed and activated.',
				'items'   => array(),
				'stats'   => array('total' => 0, 'matched' => 0, 'mismatched' => 0, 'invalid' => 0),
			);
		}

		$normalized_expected = self::normalize_domain($input_domain);
		if (empty($normalized_expected)) {
			return array(
				'success' => false,
				'error'   => 'Please provide a valid domain to match against.',
				'items'   => array(),
				'stats'   => array('total' => 0, 'matched' => 0, 'mismatched' => 0, 'invalid' => 0),
			);
		}

		@set_time_limit(0);

		$table_name = $wpdb->prefix . 'prli_links';
		$sql = "SELECT id, name, slug, url, redirect_type, created_at FROM {$table_name} ORDER BY id DESC";

		if ($limit > 0) {
			$sql .= $wpdb->prepare(" LIMIT %d OFFSET %d", $limit, $offset);
		}

		$rows = $wpdb->get_results($sql, ARRAY_A);

		$items = array();
		$stats = array(
			'total'      => count($rows),
			'matched'    => 0,
			'mismatched' => 0,
			'invalid'    => 0,
		);

		$home_url = home_url('/');

		foreach ($rows as $row) {
			$id = (int) ($row['id'] ?? 0);
			$name = !empty($row['name']) ? (string) $row['name'] : ('Link #' . $id);
			$slug = (string) ($row['slug'] ?? '');
			$url = isset($row['url']) ? trim((string) $row['url']) : '';
			$redirect_type = (string) ($row['redirect_type'] ?? '307');
			$created_at = (string) ($row['created_at'] ?? '');

			$host = self::extract_host($url);
			$is_invalid = empty($url) || empty($host);

			if ($is_invalid) {
				$status = 'invalid';
				$is_match = false;
				$stats['invalid']++;
			} else {
				$is_match = self::check_match($host, $normalized_expected, $allow_subdomains);
				if ($is_match) {
					$status = 'matched';
					$stats['matched']++;
				} else {
					$status = 'mismatched';
					$stats['mismatched']++;
				}
			}

			// Edit URL in WP Admin for Pretty Links
			$edit_url = admin_url('admin.php?page=pretty-link&action=edit&id=' . $id);
			$pretty_url = $home_url . ltrim($slug, '/');

			$items[] = array(
				'id'            => $id,
				'name'          => $name,
				'slug'          => $slug,
				'pretty_url'    => $pretty_url,
				'target_url'    => $url,
				'detected_host' => $host,
				'status'        => $status,
				'is_match'      => $is_match,
				'redirect_type' => $redirect_type,
				'created_at'    => $created_at,
				'edit_url'      => $edit_url,
			);
		}

		return array(
			'success'             => true,
			'error'               => '',
			'normalized_expected' => $normalized_expected,
			'items'               => $items,
			'stats'               => $stats,
		);
	}
}
