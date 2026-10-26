<?php
/**
 * Safora WP-CLI Commands Integration
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

if (defined('WP_CLI') && WP_CLI) {

	class Safora_CLI_Commands extends WP_CLI_Command {

		/**
		 * Run a new backup job
		 *
		 * ## OPTIONS
		 *
		 * [--files-only]
		 * : Backup files only
		 *
		 * [--db-only]
		 * : Backup database only
		 *
		 * ## EXAMPLES
		 *
		 *     wp safora backup
		 *     wp safora backup --db-only
		 */
		public function backup($args, $assoc_args) {
			global $safora_core;

			$files = !isset($assoc_args['db-only']);
			$db = !isset($assoc_args['files-only']);

			WP_CLI::log("Initiating Safora backup job...");
			$nonce = $safora_core->boot_backup($files, $db);

			if (is_wp_error($nonce)) {
				WP_CLI::error($nonce->get_error_message());
			}

			WP_CLI::success("Backup job completed successfully! (Nonce: $nonce)");
		}

		/**
		 * List backup history
		 *
		 * ## EXAMPLES
		 *
		 *     wp safora list
		 */
		public function list($args, $assoc_args) {
			$history = Safora_Backup_History::always_get_from_db();
			if (empty($history)) {
				WP_CLI::log("No backups found in history.");
				return;
			}

			$rows = array();
			foreach ($history as $ts => $b) {
				$entities = array();
				if (!empty($b['db'])) $entities[] = 'DB';
				foreach (array('plugins', 'themes', 'uploads', 'others', 'wpcore') as $e) {
					if (!empty($b[$e])) $entities[] = ucfirst($e);
				}

				$rows[] = array(
					'Timestamp' => $ts,
					'Date'      => date('Y-m-d H:i:s', $ts),
					'Nonce'     => isset($b['nonce']) ? $b['nonce'] : '',
					'Entities'  => implode(', ', $entities),
					'Services'  => !empty($b['service']) ? implode(', ', (array) $b['service']) : 'Local',
				);
			}

			WP_CLI\Utils\format_items('table', $rows, array('Timestamp', 'Date', 'Nonce', 'Entities', 'Services'));
		}

		/**
		 * Restore a backup set from history
		 *
		 * ## OPTIONS
		 *
		 * <timestamp>
		 * : Unix timestamp of backup set
		 *
		 * [--entities=<entities>]
		 * : Comma separated list of entities (db,plugins,themes,uploads,others)
		 *
		 * [--passphrase=<passphrase>]
		 * : Decryption passphrase if encrypted
		 *
		 * ## EXAMPLES
		 *
		 *     wp safora restore 1672531199 --entities=db,plugins
		 */
		public function restore($args, $assoc_args) {
			$ts = (int) $args[0];
			$history = Safora_Backup_History::always_get_from_db();

			if (!isset($history[$ts])) {
				WP_CLI::error("Backup with timestamp $ts not found in history.");
			}

			$entities_str = isset($assoc_args['entities']) ? $assoc_args['entities'] : 'db,plugins,themes,uploads,others';
			$entities = array_map('trim', explode(',', $entities_str));
			$passphrase = isset($assoc_args['passphrase']) ? $assoc_args['passphrase'] : '';

			WP_CLI::log("Starting restoration for backup set $ts...");

			$restorer = new Safora_Restorer($history[$ts], array(
				'entities'   => $entities,
				'passphrase' => $passphrase,
			));

			$res = $restorer->perform_restore();
			if (is_wp_error($res)) {
				WP_CLI::error($res->get_error_message());
			}

			WP_CLI::success("Site restoration completed successfully!");
		}

		/**
		 * Delete a backup set from history and disk
		 *
		 * ## OPTIONS
		 *
		 * <timestamp>
		 * : Unix timestamp of backup set
		 */
		public function delete($args, $assoc_args) {
			global $safora_core;
			$ts = (int) $args[0];
			$history = Safora_Backup_History::always_get_from_db();

			if (!isset($history[$ts])) {
				WP_CLI::error("Backup $ts not found.");
			}

			$b = $history[$ts];
			$bdir = $safora_core->backups_dir_location();

			if (!empty($b['db'])) @unlink($bdir . '/' . $b['db']);
			foreach (array('plugins', 'themes', 'uploads', 'others', 'wpcore') as $e) {
				if (!empty($b[$e])) {
					foreach ((array) $b[$e] as $fn) @unlink($bdir . '/' . $fn);
				}
			}

			Safora_Backup_History::delete_backup($ts);
			WP_CLI::success("Backup $ts deleted.");
		}
	}

	WP_CLI::add_command('safora', 'Safora_CLI_Commands');
}
