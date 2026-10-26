<?php
/**
 * AJAX Handler for Pretty Links Target Domain Scanner.
 */

if (!defined('ABSPATH')) {
	exit;
}

class PRLI_TDS_Ajax_Handler {

	public static function init(): void {
		add_action('wp_ajax_prli_tds_scan_domain', array(__CLASS__, 'handle_scan_domain'));
	}

	public static function handle_scan_domain(): void {
		if (!current_user_can('manage_options')) {
			wp_send_json_error(array('message' => 'Permission denied. You do not have permission to run this scan.'));
		}

		check_ajax_referer('prli_tds_nonce', 'security');

		$domain = isset($_POST['domain']) ? sanitize_text_field(wp_unslash($_POST['domain'])) : '';
		$allow_subdomains = isset($_POST['allow_subdomains']) && ($_POST['allow_subdomains'] === '1' || $_POST['allow_subdomains'] === 'true');
		$filter_type = isset($_POST['filter_type']) ? sanitize_key($_POST['filter_type']) : 'mismatches';

		if (empty(trim($domain))) {
			wp_send_json_error(array('message' => 'Please enter a target domain to match against.'));
		}

		$results = PRLI_TDS_Engine::scan_links($domain, $allow_subdomains);

		if (!$results['success']) {
			wp_send_json_error(array('message' => $results['error']));
		}

		// Filter items based on client requested filter
		$filtered_items = array();
		foreach ($results['items'] as $item) {
			if ($filter_type === 'mismatches') {
				if ($item['status'] === 'mismatched' || $item['status'] === 'invalid') {
					$filtered_items[] = $item;
				}
			} elseif ($filter_type === 'matches') {
				if ($item['status'] === 'matched') {
					$filtered_items[] = $item;
				}
			} elseif ($filter_type === 'invalid') {
				if ($item['status'] === 'invalid') {
					$filtered_items[] = $item;
				}
			} else {
				// 'all'
				$filtered_items[] = $item;
			}
		}

		wp_send_json_success(array(
			'domain'              => $domain,
			'normalized_expected' => $results['normalized_expected'],
			'stats'               => $results['stats'],
			'items'               => $filtered_items,
			'total_returned'      => count($filtered_items),
		));
	}
}
