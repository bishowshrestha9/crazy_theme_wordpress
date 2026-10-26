<?php
/**
 * Safora Backup History Registry & Retention Manager
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Backup_History {

	const OPTION_NAME = 'backup_history';

	/**
	 * Retrieve all backup history entries
	 *
	 * @param bool $force_db
	 * @return array
	 */
	public static function get_history($force_db = false) {
		if ($force_db) {
			return self::always_get_from_db();
		}
		$history = Safora_Options::get_option(self::OPTION_NAME, array());
		if (!is_array($history)) return array();
		krsort($history); // Sort newest to oldest
		return $history;
	}

	/**
	 * Read backup history directly from the DB bypassing object cache
	 *
	 * @return array
	 */
	public static function always_get_from_db() {
		global $wpdb;
		$table = is_multisite() ? $wpdb->sitemeta : $wpdb->options;
		$key_col = is_multisite() ? 'meta_key' : 'option_name';
		$val_col = is_multisite() ? 'meta_value' : 'option_value';
		$prefixed_key = Safora_Options::PREFIX . self::OPTION_NAME;

		$raw = $wpdb->get_var($wpdb->prepare(
			"SELECT `$val_col` FROM `$table` WHERE `$key_col` = %s LIMIT 1",
			$prefixed_key
		));

		if (empty($raw)) return array();
		$history = @maybe_unserialize($raw);
		if (!is_array($history)) return array();
		krsort($history);
		return $history;
	}

	/**
	 * Save or update a backup record in the history registry
	 *
	 * @param int $timestamp
	 * @param array $backup_data
	 * @return bool
	 */
	public static function save_backup($timestamp, $backup_data) {
		$history = self::always_get_from_db();
		$history[$timestamp] = $backup_data;
		krsort($history);
		return Safora_Options::update_option(self::OPTION_NAME, $history);
	}

	/**
	 * Delete a single backup record from history
	 *
	 * @param int $timestamp
	 * @return bool
	 */
	public static function delete_backup($timestamp) {
		$history = self::always_get_from_db();
		if (isset($history[$timestamp])) {
			unset($history[$timestamp]);
			return Safora_Options::update_option(self::OPTION_NAME, $history);
		}
		return false;
	}

	/**
	 * Group and prune backups according to retention rules
	 *
	 * @param array $services Enabled remote services
	 * @return array List of pruned backup files
	 */
	public static function prune_retained_backups($services = array()) {
		global $safora_core;
		$history = self::always_get_from_db();
		if (empty($history)) return array();

		$retain_files = max(1, (int) Safora_Options::get_setting('retain'));
		$retain_db = max(1, (int) Safora_Options::get_setting('retain_db'));

		$file_backups = array();
		$db_backups = array();

		foreach ($history as $ts => $backup) {
			// Skip backups flagged with "always_keep"
			if (!empty($backup['always_keep'])) continue;

			$has_files = false;
			$has_db = !empty($backup['db']);

			foreach (array('plugins', 'themes', 'uploads', 'others', 'wpcore') as $entity) {
				if (!empty($backup[$entity])) {
					$has_files = true;
					break;
				}
			}

			if ($has_files) $file_backups[$ts] = $backup;
			if ($has_db) $db_backups[$ts] = $backup;
		}

		$to_prune = array();

		// Check file retention quota
		if (count($file_backups) > $retain_files) {
			$excess_files = array_slice(array_keys($file_backups), $retain_files);
			foreach ($excess_files as $ts) {
				$to_prune[$ts]['files'] = true;
			}
		}

		// Check DB retention quota
		if (count($db_backups) > $retain_db) {
			$excess_db = array_slice(array_keys($db_backups), $retain_db);
			foreach ($excess_db as $ts) {
				$to_prune[$ts]['db'] = true;
			}
		}

		// Extra retention rules evaluation (time-based age buckets)
		$extra_rules = (array) Safora_Options::get_option('retain_extrarules', array('files' => array(), 'db' => array()));
		$period_map = array(
			'hours'      => 3600,
			'days'       => 86400,
			'weeks'      => 604800,
			'fortnights' => 1209600,
			'months'     => 2592000,
			'years'      => 31536000,
		);
		$now = time();

		if (!empty($extra_rules['files']) && is_array($extra_rules['files'])) {
			foreach ($extra_rules['files'] as $rule) {
				$after_unit = isset($rule['after_period']) && isset($period_map[$rule['after_period']]) ? $period_map[$rule['after_period']] : 604800;
				$every_unit = isset($rule['every_period']) && isset($period_map[$rule['every_period']]) ? $period_map[$rule['every_period']] : 604800;
				$after_seconds = (isset($rule['after_howmany']) ? absint($rule['after_howmany']) : 12) * $after_unit;
				$every_seconds = (isset($rule['every_howmany']) ? absint($rule['every_howmany']) : 1) * $every_unit;
				if ($after_seconds <= 0 || $every_seconds <= 0) continue;

				$kept_buckets = array();
				foreach ($file_backups as $ts => $bk) {
					$age = $now - (int) $ts;
					if ($age >= $after_seconds) {
						$bucket = (int) floor($age / $every_seconds);
						if (isset($kept_buckets[$bucket])) {
							$to_prune[$ts]['files'] = true;
						} else {
							$kept_buckets[$bucket] = true;
						}
					}
				}
			}
		}

		if (!empty($extra_rules['db']) && is_array($extra_rules['db'])) {
			foreach ($extra_rules['db'] as $rule) {
				$after_unit = isset($rule['after_period']) && isset($period_map[$rule['after_period']]) ? $period_map[$rule['after_period']] : 604800;
				$every_unit = isset($rule['every_period']) && isset($period_map[$rule['every_period']]) ? $period_map[$rule['every_period']] : 604800;
				$after_seconds = (isset($rule['after_howmany']) ? absint($rule['after_howmany']) : 12) * $after_unit;
				$every_seconds = (isset($rule['every_howmany']) ? absint($rule['every_howmany']) : 1) * $every_unit;
				if ($after_seconds <= 0 || $every_seconds <= 0) continue;

				$kept_buckets = array();
				foreach ($db_backups as $ts => $bk) {
					$age = $now - (int) $ts;
					if ($age >= $after_seconds) {
						$bucket = (int) floor($age / $every_seconds);
						if (isset($kept_buckets[$bucket])) {
							$to_prune[$ts]['db'] = true;
						} else {
							$kept_buckets[$bucket] = true;
						}
					}
				}
			}
		}

		$pruned_files = array();
		$backup_dir = $safora_core ? $safora_core->backups_dir_location() : '';

		foreach ($to_prune as $ts => $prune_spec) {
			if (!isset($history[$ts])) continue;
			$b = $history[$ts];

			// Delete files locally and remotely
			$files_to_delete = array();

			if (!empty($prune_spec['files'])) {
				foreach (array('plugins', 'themes', 'uploads', 'others', 'wpcore') as $entity) {
					if (!empty($b[$entity])) {
						$arr = is_array($b[$entity]) ? $b[$entity] : array($b[$entity]);
						foreach ($arr as $fn) {
							$files_to_delete[] = $fn;
							unset($history[$ts][$entity]);
						}
					}
				}
			}

			if (!empty($prune_spec['db'])) {
				if (!empty($b['db'])) {
					$files_to_delete[] = $b['db'];
					unset($history[$ts]['db']);
				}
			}

			// Perform local deletion
			if (!empty($backup_dir)) {
				foreach ($files_to_delete as $fn) {
					$local_path = $backup_dir . '/' . $fn;
					if (file_exists($local_path)) {
						@unlink($local_path);
					}
					$pruned_files[] = $fn;
				}
			}

			// Clean remote storage
			if ($safora_core && !empty($files_to_delete)) {
				$storage_modules = $safora_core->get_storage_modules();
				foreach ($storage_modules as $mod_name => $mod_obj) {
					try {
						$mod_obj->delete($files_to_delete, $history[$ts]);
					} catch (Exception $e) {
						if ($safora_core) $safora_core->log("Error pruning remote files on {$mod_name}: " . $e->getMessage());
					}
				}
			}

			// If no backup parts remain, remove history entry completely
			$has_any = !empty($history[$ts]['db']);
			foreach (array('plugins', 'themes', 'uploads', 'others', 'wpcore') as $ent) {
				if (!empty($history[$ts][$ent])) {
					$has_any = true;
					break;
				}
			}

			if (!$has_any) {
				unset($history[$ts]);
			}
		}

		Safora_Options::update_option(self::OPTION_NAME, $history);
		return $pruned_files;
	}

	/**
	 * Rescan local backup directory (and legacy updraft folder) to discover and rebuild backup history
	 *
	 * @return int Total number of backup sets found
	 */
	public static function rescan_local_backups() {
		global $safora_core;
		$history = self::always_get_from_db();
		$safora_dir = $safora_core ? $safora_core->backups_dir_location() : WP_CONTENT_DIR . '/safora';
		$updraft_dir = WP_CONTENT_DIR . '/updraft';

		$directories_to_scan = array($safora_dir);
		if (is_dir($updraft_dir)) {
			$directories_to_scan[] = $updraft_dir;
		}

		$discovered_files = array();

		foreach ($directories_to_scan as $dir) {
			if (!is_dir($dir)) continue;
			$handle = @opendir($dir);
			if (!$handle) continue;

			while (false !== ($file = @readdir($handle))) {
				if ('.' === $file || '..' === $file) continue;
				$filepath = $dir . '/' . $file;
				if (!is_file($filepath)) continue;

				// Skip temporary files
				if (preg_match('/(\.tmp|deleteflag-|\.table\.tmp)/i', $file)) continue;

				// Only consider backup archives
				if (preg_match('/(\.zip|\.gz|\.crypt|\.gz\.crypt)$/i', $file)) {
					// If found in updraft directory and not in safora directory, copy over to safora directory
					if ($dir === $updraft_dir && is_dir($safora_dir)) {
						$dest = $safora_dir . '/' . $file;
						if (!file_exists($dest)) {
							@copy($filepath, $dest);
						}
					}
					$discovered_files[] = $file;
				}
			}
			@closedir($handle);
		}

		$discovered_files = array_unique($discovered_files);
		if (empty($discovered_files)) {
			return count($history);
		}

		// Group files into backup sets
		$parsed_sets = array();

		foreach ($discovered_files as $fn) {
			$timestamp = 0;
			$nonce = '';
			$entity = '';

			// 1. Safora format: [prefix]_TIMESTAMP_NONCE-entity...
			if (preg_match('/^([a-zA-Z0-9_\-]+)_(\d{9,12})_([a-f0-9]{8,16})-(db|[a-z]+)(\d*)(\.zip|\.gz|\.crypt|\.gz\.crypt)$/i', $fn, $m)) {
				$timestamp = (int) $m[2];
				$nonce     = $m[3];
				$entity    = strtolower($m[4]);
			}
			// 2. UpdraftPlus format: [prefix]_YYYY-MM-DD-HHMM_[Sitename_]NONCE-entity...
			elseif (preg_match('/^([a-zA-Z0-9_\-]+)_(\d{4}-\d{2}-\d{2}-\d{4})_(?:.*_)?([a-f0-9]{8,16})-(db|[a-z]+)(\d*)(\.zip|\.gz|\.crypt|\.gz\.crypt)$/i', $fn, $m)) {
				$datestr = $m[2];
				$ts_parts = explode('-', $datestr);
				if (count($ts_parts) === 4) {
					$hour = substr($ts_parts[3], 0, 2);
					$min  = substr($ts_parts[3], 2, 2);
					$timestamp = strtotime("{$ts_parts[0]}-{$ts_parts[1]}-{$ts_parts[2]} {$hour}:{$min}:00");
				}
				if (!$timestamp) {
					$timestamp = filemtime($safora_dir . '/' . $fn);
				}
				$nonce  = $m[3];
				$entity = strtolower($m[4]);
			}
			// 3. Fallback timestamped backup: [prefix]_TIMESTAMP-entity...
			elseif (preg_match('/^([a-zA-Z0-9_\-]+)_(\d{9,12})-(db|[a-z]+)(\d*)(\.zip|\.gz|\.crypt|\.gz\.crypt)$/i', $fn, $m)) {
				$timestamp = (int) $m[2];
				$nonce     = substr(md5($fn), 0, 12);
				$entity    = strtolower($m[3]);
			} else {
				// General fallback
				$timestamp = filemtime($safora_dir . '/' . $fn);
				$nonce = substr(md5($fn), 0, 12);
				if (preg_match('/-db(\.gz|\.crypt)/i', $fn)) {
					$entity = 'db';
				} elseif (preg_match('/-([a-z]+)(\d*)\.zip/i', $fn, $em)) {
					$entity = strtolower($em[1]);
				}
			}

			if (!$timestamp || empty($entity)) continue;

			if (!isset($parsed_sets[$timestamp])) {
				$parsed_sets[$timestamp] = array(
					'timestamp'   => $timestamp,
					'nonce'       => $nonce,
					'service'     => array('local'),
					'always_keep' => false,
				);
			}

			if ('db' === $entity) {
				$parsed_sets[$timestamp]['db'] = $fn;
			} else {
				if (!isset($parsed_sets[$timestamp][$entity])) {
					$parsed_sets[$timestamp][$entity] = array();
				}
				if (!in_array($fn, $parsed_sets[$timestamp][$entity])) {
					$parsed_sets[$timestamp][$entity][] = $fn;
				}
			}
		}

		// Merge parsed sets with existing history
		foreach ($parsed_sets as $ts => $bset) {
			if (!isset($history[$ts])) {
				$history[$ts] = $bset;
			} else {
				// Merge entities
				if (!empty($bset['db'])) $history[$ts]['db'] = $bset['db'];
				foreach (array('plugins', 'themes', 'uploads', 'others', 'wpcore') as $ent) {
					if (!empty($bset[$ent])) {
						$existing_ent = isset($history[$ts][$ent]) ? (array) $history[$ts][$ent] : array();
						$history[$ts][$ent] = array_unique(array_merge($existing_ent, (array) $bset[$ent]));
					}
				}
			}
		}

		krsort($history);
		Safora_Options::update_option(self::OPTION_NAME, $history);
		return count($history);
	}
}
