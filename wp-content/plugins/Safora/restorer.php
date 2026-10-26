<?php
/**
 * Safora Restoration Engine (UpdraftPlus-Parity Database & File Engine)
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Restorer {

	private $backup_set = array();
	private $options = array();
	private $log_entries = array();
	private $staging_dir = '';

	public function __construct($backup_set = array(), $options = array()) {
		$this->backup_set = $backup_set;
		$this->options = wp_parse_args($options, array(
			'entities'       => array('db', 'plugins', 'themes', 'uploads', 'others'),
			'passphrase'     => '',
			'search_replace' => true,
		));
	}

	/**
	 * Log restore progress to disk file and transient for real-time UI streaming
	 */
	public function log($msg, $level = 'notice') {
		$timestamp = date('Y-m-d H:i:s');
		$formatted = "[$timestamp] [$level] $msg";
		$this->log_entries[] = $formatted;

		global $safora_core;
		$backup_dir = $safora_core ? $safora_core->backups_dir_location() : WP_CONTENT_DIR . '/safora';
		if (!is_dir($backup_dir)) {
			@wp_mkdir_p($backup_dir);
		}
		$log_file = $backup_dir . '/restore.log';
		@file_put_contents($log_file, $formatted . "\n", FILE_APPEND | LOCK_EX);

		// Also update transient as secondary fallback
		$existing = (array) get_transient('safora_restore_log');
		$existing[] = $formatted;
		@set_transient('safora_restore_log', $existing, 600);
	}

	/**
	 * Perform complete restoration with UpdraftPlus parity
	 *
	 * @return bool|WP_Error
	 */
	public function perform_restore() {
		global $wpdb, $safora_core;

		@set_time_limit(1800);
		if (function_exists('wp_raise_memory_limit')) {
			wp_raise_memory_limit('admin');
		}

		$backup_dir = $safora_core ? $safora_core->backups_dir_location() : WP_CONTENT_DIR . '/safora';
		$log_file = $backup_dir . '/restore.log';
		if (file_exists($log_file)) {
			@unlink($log_file);
		}

		$this->log("==========================================");
		$this->log("Starting Safora UpdraftPlus-Parity Restoration");
		$this->log("==========================================");

		// 1. Enter Maintenance Mode
		$this->enable_maintenance_mode();

		$this->staging_dir = WP_CONTENT_DIR . '/upgrade/safora-staging-' . md5(uniqid(rand(), true));
		wp_mkdir_p($this->staging_dir);

		$entities = (array) $this->options['entities'];

		// 2. Restore Database first if selected
		if (in_array('db', $entities) && !empty($this->backup_set['db'])) {
			$db_file = $backup_dir . '/' . $this->backup_set['db'];
			$res = $this->restore_database($db_file);
			if (is_wp_error($res)) {
				$this->log("Database restoration error: " . $res->get_error_message(), 'error');
				$this->disable_maintenance_mode();
				return $res;
			}
		}

		// 3. Restore File Entities
		$file_entities = array('plugins', 'themes', 'uploads', 'others', 'wpcore');
		foreach ($file_entities as $entity) {
			if (in_array($entity, $entities) && !empty($this->backup_set[$entity])) {
				$files = is_array($this->backup_set[$entity]) ? $this->backup_set[$entity] : array($this->backup_set[$entity]);
				foreach ($files as $fn) {
					$archive_path = $backup_dir . '/' . $fn;
					$res = $this->restore_file_archive($entity, $archive_path);
					if (is_wp_error($res)) {
						$this->log("File restore error on entity $entity: " . $res->get_error_message(), 'error');
					}
				}
			}
		}

		// 4. Post-restore cache and permalinks cleanup
		$this->post_restore_cleanup();

		// 5. Disable Maintenance Mode
		$this->disable_maintenance_mode();

		$this->log("==========================================");
		$this->log("Restoration successfully completed!");
		$this->log("==========================================");

		return true;
	}

	/**
	 * Restore a single file entity archive
	 */
	private function restore_file_archive($entity, $archive_path) {
		global $safora_core;
		$this->log("Restoring file entity: $entity (archive: " . basename($archive_path) . ")...");

		// Download from remote cloud storage if not present locally
		if (!file_exists($archive_path) && $safora_core) {
			$services = !empty($this->backup_set['service']) ? (array) $this->backup_set['service'] : (array) Safora_Options::get_setting('service');
			$storage_modules = $safora_core->get_storage_modules();
			foreach ($services as $srv) {
				if (isset($storage_modules[$srv])) {
					$this->log("Local archive missing. Attempting download of " . basename($archive_path) . " from {$srv}...");
					$downloaded = $storage_modules[$srv]->download(basename($archive_path));
					if ($downloaded && file_exists($archive_path)) {
						$this->log("Successfully downloaded " . basename($archive_path) . " from {$srv}.");
						break;
					}
				}
			}
		}

		if (!file_exists($archive_path)) {
			return new WP_Error('missing_file', "Archive file not found: $archive_path");
		}

		// Decrypt if encrypted
		$file_to_unzip = $archive_path;
		if (substr($archive_path, -6) === '.crypt') {
			$this->log("Decrypting archive with provided passphrase...");
			$decrypted_tmp = $this->staging_dir . '/' . basename($archive_path, '.crypt');
			try {
				Safora_Encryption::decrypt_file($archive_path, $decrypted_tmp, $this->options['passphrase']);
				$file_to_unzip = $decrypted_tmp;
			} catch (Exception $e) {
				return new WP_Error('decrypt_failed', 'Decryption failed: ' . $e->getMessage());
			}
		}

		// Unpack archive to staging directory
		$unpack_dir = $this->staging_dir . '/' . $entity;
		wp_mkdir_p($unpack_dir);

		if (class_exists('ZipArchive')) {
			$zip = new ZipArchive();
			if (true === $zip->open($file_to_unzip)) {
				$zip->extractTo($unpack_dir);
				$zip->close();
			} else {
				return new WP_Error('unzip_failed', "Could not extract archive: $file_to_unzip");
			}
		} else {
			$zip = new Safora_PclZip();
			$zip->open($file_to_unzip);
			$zip->extractTo($unpack_dir);
		}

		// Determine target destination directory
		$target_dir = '';
		switch ($entity) {
			case 'plugins': $target_dir = WP_PLUGIN_DIR; break;
			case 'themes':  $target_dir = get_theme_root(); break;
			case 'uploads': $upload_info = wp_upload_dir(); $target_dir = $upload_info['basedir']; break;
			case 'others':  $target_dir = WP_CONTENT_DIR; break;
			case 'wpcore':  $target_dir = ABSPATH; break;
		}

		if (empty($target_dir)) {
			return new WP_Error('invalid_entity', "Unknown entity destination: $entity");
		}

		// Normalize source folder (handles flat Safora archives and nested UpdraftPlus archives)
		$source_dir = $unpack_dir;
		if (is_dir($unpack_dir . '/' . $entity)) {
			$source_dir = $unpack_dir . '/' . $entity;
		} elseif ('others' === $entity && is_dir($unpack_dir . '/wp-content')) {
			$source_dir = $unpack_dir . '/wp-content';
		} elseif ('wpcore' === $entity && is_dir($unpack_dir . '/wordpress')) {
			$source_dir = $unpack_dir . '/wordpress';
		}

		// Move files from staging to target directory
		$this->log("Moving restored files into $target_dir...");
		$this->fast_move_recursive($source_dir, $target_dir);

		return true;
	}

	/**
	 * Restore database from SQL dump with UpdraftPlus parity
	 *
	 * @param string $db_path Path to .sql or .gz dump
	 * @return bool|WP_Error
	 */
	private function restore_database($db_path) {
		global $wpdb, $safora_core;
		$this->log("Restoring database from " . basename($db_path) . "...");

		// Download from remote cloud storage if not present locally
		if (!file_exists($db_path) && $safora_core) {
			$services = !empty($this->backup_set['service']) ? (array) $this->backup_set['service'] : (array) Safora_Options::get_setting('service');
			$storage_modules = $safora_core->get_storage_modules();
			foreach ($services as $srv) {
				if (isset($storage_modules[$srv])) {
					$this->log("Local database dump missing. Attempting download of " . basename($db_path) . " from {$srv}...");
					$downloaded = $storage_modules[$srv]->download(basename($db_path));
					if ($downloaded && file_exists($db_path)) {
						$this->log("Successfully downloaded " . basename($db_path) . " from {$srv}.");
						break;
					}
				}
			}
		}

		if (!file_exists($db_path)) {
			return new WP_Error('missing_db', "Database archive not found: $db_path");
		}

		// Decrypt if encrypted
		$file_to_import = $db_path;
		if (substr($db_path, -6) === '.crypt') {
			$this->log("Decrypting database dump with passphrase...");
			$decrypted_tmp = $this->staging_dir . '/' . basename($db_path, '.crypt');
			try {
				Safora_Encryption::decrypt_file($db_path, $decrypted_tmp, $this->options['passphrase']);
				$file_to_import = $decrypted_tmp;
			} catch (Exception $e) {
				return new WP_Error('decrypt_failed', 'Decryption failed: ' . $e->getMessage());
			}
		}

		$is_gz = (substr($file_to_import, -3) === '.gz');
		$handle = $is_gz ? @gzopen($file_to_import, 'rb') : @fopen($file_to_import, 'r');

		if (!$handle) {
			return new WP_Error('read_error', "Cannot open database dump for reading.");
		}

		$old_site_url = '';
		$old_home_url = '';
		$old_abspath = '';
		$old_table_prefix = null;
		$target_table_prefix = $wpdb->prefix;

		// Set MySQL session variables for high-speed, constraint-safe import
		$wpdb->query("SET FOREIGN_KEY_CHECKS=0");
		$wpdb->query("SET UNIQUE_CHECKS=0");
		$wpdb->query("SET AUTOCOMMIT=0");
		$wpdb->query("SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\"");
		$wpdb->query("SET NAMES utf8mb4");
		$wpdb->query("START TRANSACTION");

		// UpdraftPlus SQL state machine
		$sql_line = '';
		$sql_type = -1;
		$delimiter = ';';
		$delimiter_regex = ';';
		$lines_executed = 0;
		$max_allowed_packet = 16777216; // 16MB default limit

		// Try to read actual max_allowed_packet
		$packet_res = $wpdb->get_var("SELECT @@SESSION.max_allowed_packet");
		if ($packet_res && is_numeric($packet_res) && (int)$packet_res > 1048576) {
			$max_allowed_packet = (int)$packet_res;
		}

		$insert_prefix = '';
		$current_table = '';
		$tables_processed = array();

		while (!($is_gz ? gzeof($handle) : feof($handle))) {
			$buffer = $is_gz ? gzgets($handle, 1048576) : fgets($handle, 1048576);
			if (false === $buffer) break;

			$buffer_trimmed = rtrim($buffer);

			// Extract metadata and discard comments when buffer is empty
			if (empty($sql_line) && (empty($buffer_trimmed) || '#' === substr($buffer_trimmed, 0, 1) || 0 === strpos($buffer_trimmed, '--'))) {
				if (empty($old_site_url)) {
					if (preg_match('/^--\s*site\s*url:\s*(.+)$/i', $buffer_trimmed, $m) || preg_match('/^\#\s*Backup of:\s*(.+)$/i', $buffer_trimmed, $m)) {
						$old_site_url = untrailingslashit(trim($m[1]));
						$this->log("Detected source site URL: " . $old_site_url);
					}
				}
				if (empty($old_home_url)) {
					if (preg_match('/^--\s*home\s*url:\s*(.+)$/i', $buffer_trimmed, $m) || preg_match('/^\#\s*Home URL:\s*(.+)$/i', $buffer_trimmed, $m)) {
						$old_home_url = untrailingslashit(trim($m[1]));
						$this->log("Detected source home URL: " . $old_home_url);
					}
				}
				if (null === $old_table_prefix) {
					if (preg_match('/^--\s*table\s*prefix:\s*(.+)$/i', $buffer_trimmed, $m) || preg_match('/^\#\s*Table prefix:\s*(\S*)$/i', $buffer_trimmed, $m)) {
						$old_table_prefix = trim($m[1]);
						$this->log("Detected source table prefix: " . $old_table_prefix);
					}
				}
				if (empty($old_abspath)) {
					if (preg_match('/^\#\s*ABSPATH:\s*(.+)$/i', $buffer_trimmed, $m)) {
						$old_abspath = untrailingslashit(trim($m[1]));
					}
				}
				continue;
			}

			// Early statement type classification (UpdraftPlus-parity)
			$combined_peek = $sql_line . $buffer_trimmed;

			if (preg_match('/^\s*insert(?:\s\s*ignore)?\s\s*into(?:\s*`(.+?)`|[^\(]+)(?:\s*\(.+?\))?\s*(?:values|\()/i', $combined_peek, $matches)) {
				$sql_type = 3;
				$current_table = isset($matches[1]) ? $matches[1] : '';
				$insert_prefix = $matches[0];
			} elseif (preg_match('/^\s*delimiter\s+(\S+)\s*$/i', $combined_peek, $matches)) {
				$sql_type = 10;
				$delimiter = $matches[1];
				$delimiter_regex = preg_quote($delimiter, '/');
			} elseif (preg_match('/^\s*create\s+trigger\s/i', $combined_peek)) {
				$sql_type = 9;
			} elseif (preg_match('/^\s*create\s+table\s+`?([^`\(\s]+)`?\s*\(/i', $combined_peek, $matches)) {
				$sql_type = 2;
				$current_table = isset($matches[1]) ? $matches[1] : '';
			} elseif (preg_match('/^\s*drop\s+table\s+(?:if\s+exists\s+)?`?([^`\s;]+)`?/i', $combined_peek, $matches)) {
				$sql_type = 1;
				$current_table = isset($matches[1]) ? $matches[1] : '';
			} elseif (preg_match('/^\s*drop\s+view\s/i', $combined_peek)) {
				$sql_type = 16;
			} elseif (preg_match("/^\s*CREATE\s+(?:DEFINER\s*=\s*\S+\s+)?(?:PROCEDURE|FUNCTION)/is", $combined_peek)) {
				$sql_type = 12;
			}

			// Fallback: Detect old table prefix from the first table name if missing from header comments
			if (null === $old_table_prefix && !empty($current_table)) {
				if (preg_match('/^([a-zA-Z0-9]+_)/', $current_table, $pm)) {
					$old_table_prefix = $pm[1];
					$this->log("Detected source table prefix from table structure: " . $old_table_prefix);
				}
			}

			// UpdraftPlus Packet Splitting: Prevent exceeding max_allowed_packet on multi-row INSERTs
			if (3 === $sql_type && !empty($sql_line) && strlen($sql_line . $buffer_trimmed) > ($max_allowed_packet - 200) && preg_match('/,\s*$/', $sql_line) && preg_match('/^\s*\(/', $buffer_trimmed)) {
				// Close the current batch cleanly with a semicolon
				$sql_to_run = substr(rtrim($sql_line), 0, -1) . ';';
				if (!empty($old_table_prefix) && $old_table_prefix !== $target_table_prefix) {
					$sql_to_run = self::replace_table_prefix($sql_to_run, $old_table_prefix, $target_table_prefix);
				}

				$wpdb->query($sql_to_run);
				$lines_executed++;

				// Reset to continue with next rows
				$sql_line = $insert_prefix . " ";
			}

			$sql_line .= (9 === $sql_type && !empty($sql_line)) ? ' ' . $buffer_trimmed : $buffer_trimmed;

			// UpdraftPlus Statement Completeness Gate
			// 1. INSERT (type 3): Must end with closing parenthesis followed by delimiter ');'
			// 2. TRIGGER (type 9): Must end with 'END;'
			// 3. Other statements: Must end with delimiter
			$is_complete = false;
			if (3 === $sql_type) {
				if (preg_match('/\)\s*' . $delimiter_regex . '$/', substr($sql_line, -10, 10))) {
					$is_complete = true;
				}
			} elseif (9 === $sql_type) {
				if (preg_match('/(?:END)?\s*' . $delimiter_regex . '\s*$/i', $sql_line)) {
					$is_complete = true;
				}
			} elseif (in_array($sql_type, array(10, 12))) {
				if (substr(rtrim($sql_line), -strlen($delimiter)) === $delimiter) {
					$is_complete = true;
				}
			} else {
				if (substr(rtrim($sql_line), -strlen($delimiter)) === $delimiter) {
					$is_complete = true;
				}
			}

			if (!$is_complete) {
				$sql_line .= "\n";
				continue;
			}

			// DELIMITER directive handling
			if (10 === $sql_type) {
				$sql_line = '';
				$sql_type = -1;
				continue;
			}

			// Table Prefix Re-mapping
			$sql_to_execute = $sql_line;
			if (!empty($old_table_prefix) && $old_table_prefix !== $target_table_prefix) {
				$sql_to_execute = self::replace_table_prefix($sql_to_execute, $old_table_prefix, $target_table_prefix);
			}

			// Execute query
			$res = $wpdb->query($sql_to_execute);
			if (false === $res && $wpdb->last_error) {
				if (!preg_match('/(already exists|Duplicate entry|Unknown table)/i', $wpdb->last_error)) {
					$this->log("SQL Notice (" . (!empty($current_table) ? $current_table : 'general') . "): " . $wpdb->last_error, 'warning');
				}
			}

			$lines_executed++;
			if (!empty($current_table) && !isset($tables_processed[$current_table])) {
				$tables_processed[$current_table] = true;
			}

			// Batch commit every 1000 queries for ultra-fast InnoDB insertion
			if (0 === ($lines_executed % 1000)) {
				$wpdb->query("COMMIT");
				$wpdb->query("START TRANSACTION");
				Safora_Job_Scheduler::record_still_alive();
				@set_time_limit(300);
			}

			// Reset statement buffer
			$sql_line = '';
			$sql_type = -1;
		}

		$is_gz ? gzclose($handle) : fclose($handle);

		// Final transaction commit and session restoration
		$wpdb->query("COMMIT");
		$wpdb->query("SET FOREIGN_KEY_CHECKS=1");
		$wpdb->query("SET UNIQUE_CHECKS=1");
		$wpdb->query("SET AUTOCOMMIT=1");

		$this->log("Executed $lines_executed SQL statements across " . count($tables_processed) . " tables.");

		// UpdraftPlus Parity: Usermeta and Options Prefix Migration
		if (!empty($old_table_prefix) && $old_table_prefix !== $target_table_prefix) {
			$this->log("Migrating user capabilities and options prefix ($old_table_prefix -> $target_table_prefix)...");

			// Update usermeta capabilities and user_level
			$wpdb->query($wpdb->prepare(
				"UPDATE `{$target_table_prefix}usermeta` SET meta_key = REPLACE(meta_key, %s, %s) WHERE meta_key LIKE %s",
				$old_table_prefix,
				$target_table_prefix,
				$wpdb->esc_like($old_table_prefix) . '%'
			));

			// Update options table user roles
			$wpdb->query($wpdb->prepare(
				"UPDATE `{$target_table_prefix}options` SET option_name = REPLACE(option_name, %s, %s) WHERE option_name LIKE %s",
				$old_table_prefix,
				$target_table_prefix,
				$wpdb->esc_like($old_table_prefix) . '%'
			));
		}

		// Clean active cron entries in restored options so old background jobs don't crash
		$crons = get_option('cron');
		if (is_array($crons)) {
			foreach ($crons as $ts => $hook) {
				if (!is_array($hook)) continue;
				foreach (array_keys($hook) as $hook_name) {
					if (0 === strpos($hook_name, 'safora_') || 0 === strpos($hook_name, 'updraft_')) {
						unset($crons[$ts][$hook_name]);
						if (empty($crons[$ts])) unset($crons[$ts]);
					}
				}
			}
			update_option('cron', $crons);
		}

		// Serialization-Safe Search & Replace for Site URLs
		$new_site_url = untrailingslashit(site_url());
		$new_home_url = untrailingslashit(home_url());

		if (empty($old_site_url)) {
			$db_site_url = $wpdb->get_var("SELECT option_value FROM `{$target_table_prefix}options` WHERE option_name = 'siteurl' LIMIT 1");
			if (!empty($db_site_url) && untrailingslashit($db_site_url) !== $new_site_url) {
				$old_site_url = untrailingslashit($db_site_url);
			}
		}

		$is_domain_migration = (!empty($old_site_url) && $old_site_url !== $new_site_url);

		if ($is_domain_migration && !empty($this->options['search_replace'])) {
			$this->log("Site URL migration detected ($old_site_url -> $new_site_url). Running serialization-safe Search & Replace...");
			$search_targets = array($old_site_url);
			$replace_targets = array($new_site_url);

			if (!empty($old_home_url) && $old_home_url !== $new_home_url) {
				$search_targets[] = $old_home_url;
				$replace_targets[] = $new_home_url;
			}

			// Also replace old ABSPATH if known and different
			if (!empty($old_abspath) && untrailingslashit(ABSPATH) !== $old_abspath) {
				$search_targets[] = $old_abspath;
				$replace_targets[] = untrailingslashit(ABSPATH);
			}

			$sr = new Safora_Search_Replace($search_targets, $replace_targets);
			$tables = $wpdb->get_col("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
			$report = $sr->run($tables);
			$this->log("Search & Replace completed: updated {$report['change']} cells across {$report['tables']} tables.");

			// Deactivate caching plugins to prevent stale redirect loops post-migration
			$active_plugins = get_option('active_plugins');
			if (is_array($active_plugins)) {
				$cache_plugins = array(
					'w3-total-cache/w3-total-cache.php',
					'wp-super-cache/wp-cache.php',
					'wp-fastest-cache/wpFastestCache.php',
					'litespeed-cache/litespeed-cache.php',
					'autoptimize/autoptimize.php',
					'sg-cachepress/sg-cachepress.php',
					'wp-rocket/wp-rocket.php',
				);

				$deactivated = array();
				foreach ($cache_plugins as $cp) {
					$key = array_search($cp, $active_plugins);
					if (false !== $key) {
						unset($active_plugins[$key]);
						$deactivated[] = dirname($cp);
					}
				}

				if (!empty($deactivated)) {
					update_option('active_plugins', array_values($active_plugins));
					$this->log("Temporarily deactivated caching plugins post-migration (" . implode(', ', $deactivated) . "). Re-enable them when ready.");
				}
			}
		}

		return true;
	}

	/**
	 * Replace table prefix in SQL statements cleanly
	 *
	 * @param string $sql
	 * @param string $old_prefix
	 * @param string $new_prefix
	 * @return string
	 */
	public static function replace_table_prefix($sql, $old_prefix, $new_prefix) {
		if (empty($old_prefix) || empty($new_prefix) || $old_prefix === $new_prefix) {
			return $sql;
		}

		// 1. Backtick enclosed table names: `wp_posts` -> `wp2_posts`
		$pattern_bt = '/`' . preg_quote($old_prefix, '/') . '([a-zA-Z0-9_]+)`/';
		$sql = preg_replace($pattern_bt, '`' . $new_prefix . '$1`', $sql);

		// 2. Unquoted statement prefixes (e.g. TABLE wp_posts, INTO wp_posts)
		$pattern_unq = '/\b(TABLE|INTO|FROM|JOIN|UPDATE|DESCRIBE|EXPLAIN)\s+' . preg_quote($old_prefix, '/') . '([a-zA-Z0-9_]+)/i';
		$sql = preg_replace($pattern_unq, '$1 ' . $new_prefix . '$2', $sql);

		return $sql;
	}

	/**
	 * Fast recursive directory mover using atomic filesystem renames
	 *
	 * @param string $src
	 * @param string $dst
	 * @return void
	 */
	private function fast_move_recursive($src, $dst) {
		if (!is_dir($src)) return;
		wp_mkdir_p($dst);
		$dir = @opendir($src);
		if (!$dir) return;

		while (false !== ($file = readdir($dir))) {
			if ('.' === $file || '..' === $file) continue;
			$src_path = "$src/$file";
			$dst_path = "$dst/$file";

			if (is_dir($src_path)) {
				// If destination folder does not exist yet, rename moves the entire subtree instantly!
				if (!file_exists($dst_path)) {
					if (@rename($src_path, $dst_path)) {
						continue;
					}
				}
				$this->fast_move_recursive($src_path, $dst_path);
				@rmdir($src_path);
			} else {
				if (!@rename($src_path, $dst_path)) {
					@copy($src_path, $dst_path);
					@unlink($src_path);
				}
			}
		}
		closedir($dir);
		@rmdir($src);
	}

	/**
	 * Enable WordPress maintenance mode
	 */
	private function enable_maintenance_mode() {
		$file = ABSPATH . '.maintenance';
		$content = '<?php $upgrading = ' . time() . '; ?>';
		@file_put_contents($file, $content);
		$this->log("Maintenance mode enabled.");
	}

	/**
	 * Disable WordPress maintenance mode
	 */
	private function disable_maintenance_mode() {
		$file = ABSPATH . '.maintenance';
		if (file_exists($file)) {
			@unlink($file);
			$this->log("Maintenance mode disabled.");
		}
	}

	/**
	 * Clean staging files and flush all page, option, and object caches (UpdraftPlus-parity)
	 */
	private function post_restore_cleanup() {
		$this->log("Cleaning staging directory and flushing caches...");

		if (!empty($this->staging_dir) && is_dir($this->staging_dir)) {
			Safora_Filesystem_Functions::recursive_delete($this->staging_dir);
		}

		// Flush object cache
		if (function_exists('wp_cache_flush')) {
			wp_cache_flush();
		}

		// Reload all options into WordPress memory
		if (function_exists('wp_load_alloptions')) {
			wp_load_alloptions(true);
		}

		// Flush rewrite rules / permalinks
		if (function_exists('flush_rewrite_rules')) {
			flush_rewrite_rules(true);
		}

		// Flush 3rd party cache plugins
		if (function_exists('w3tc_flush_all')) w3tc_flush_all();
		if (function_exists('wp_cache_clear_cache')) wp_cache_clear_cache();
		if (function_exists('rocket_clean_domain')) rocket_clean_domain();
		if (function_exists('litespeed_purge_all')) litespeed_purge_all();
	}
}
