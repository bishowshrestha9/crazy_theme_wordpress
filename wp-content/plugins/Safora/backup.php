<?php
/**
 * Safora Main Backup Engine (Files & Database)
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Backup {

	private $backup_files = true;
	private $backup_db = true;
	private $altered_since = -1;
	private $created_files = array();
	private $checksums = array('sha1' => array(), 'sha256' => array());
	private $zip_engine = 'ziparchive';
	private $binzip_path = '';

	public function __construct($backup_files = true, $backup_db = true, $altered_since = -1) {
		$this->backup_files = $backup_files;
		$this->backup_db = $backup_db;
		$this->altered_since = $altered_since;
		$this->detect_zip_engine();
	}

	/**
	 * Detect best available zip engine: BinZip -> ZipArchive -> PclZip
	 */
	private function detect_zip_engine() {
		global $safora_core;
		$use_binzip = (bool) Safora_Options::get_setting('binzip');
		if ($use_binzip) {
			$possible_binaries = array('/usr/bin/zip', '/usr/local/bin/zip', '/bin/zip');
			$bin = Safora_Filesystem_Functions::find_working_binary($possible_binaries);
			if ($bin) {
				$this->zip_engine = 'binzip';
				$this->binzip_path = $bin;
				if ($safora_core) $safora_core->log("Zip engine selected: High-speed BinZip binary ($bin)");
				return;
			}
		}

		if (class_exists('ZipArchive')) {
			$this->zip_engine = 'ziparchive';
			if ($safora_core) $safora_core->log("Zip engine selected: PHP ZipArchive extension");
			return;
		}

		$this->zip_engine = 'pclzip';
		if ($safora_core) $safora_core->log("Zip engine selected: PclZip fallback");
	}

	/**
	 * Instantiate zip object based on detected engine
	 */
	private function get_zip_instance() {
		if ($this->zip_engine === 'binzip' && !empty($this->binzip_path)) {
			return new Safora_BinZip($this->binzip_path);
		} elseif ($this->zip_engine === 'ziparchive' && class_exists('Safora_ZipArchive')) {
			return new Safora_ZipArchive();
		}
		return new Safora_PclZip();
	}

	/**
	 * Execute file entity archiving
	 *
	 * @return array Array of created zip filenames
	 */
	public function backup_files() {
		global $safora_core;
		if (!$this->backup_files) return array();

		$entities = $safora_core->get_backupable_file_entities();
		$nonce = $safora_core->get_job_nonce();
		$timestamp = $safora_core->get_job_timestamp();

		$safora_core->log("Beginning file backups for entities: " . implode(', ', array_keys($entities)));

		foreach ($entities as $entity_name => $dir_path) {
			Safora_Job_Scheduler::record_still_alive();

			// Check if setting includes this entity
			if (!Safora_Options::get_setting('include_' . $entity_name)) {
				$safora_core->log("Entity '$entity_name' is excluded by settings. Skipping.");
				continue;
			}

			if (!is_dir($dir_path)) {
				$safora_core->log("Directory for entity '$entity_name' not found: $dir_path", 'warning');
				continue;
			}

			$this->create_entity_zips($entity_name, $dir_path, $timestamp, $nonce);
		}

		return $this->created_files;
	}

	/**
	 * Archive a single entity directory with splitting support
	 */
	private function create_entity_zips($entity_name, $dir_path, $timestamp, $nonce) {
		global $safora_core;
		$backup_dir = $safora_core->backups_dir_location();
		$real_backup_dir = realpath($backup_dir) ? realpath($backup_dir) : $backup_dir;
		$split_every_mb = max(25, (int) Safora_Options::get_setting('split_every'));
		$split_every_bytes = $split_every_mb * 1024 * 1024;

		$index = 0;
		$prefix = Safora_Options::get_backup_name_prefix();
		$base_name = "{$prefix}_{$timestamp}_{$nonce}-{$entity_name}";
		$zip_file = $backup_dir . "/{$base_name}.zip";

		// Check if already completed in previous resumption
		if (file_exists($zip_file) && $safora_core->jobdata_get("entity_done_{$entity_name}")) {
			$safora_core->log("Entity '$entity_name' was already archived in a previous resumption.");
			$this->created_files[] = basename($zip_file);
			return;
		}

		$safora_core->log("Archiving entity '$entity_name' from $dir_path (Engine: {$this->zip_engine}, Split threshold: {$split_every_mb}MB)...");

		$temp_zip = $backup_dir . "/{$base_name}.zip.tmp";
		$zip = $this->get_zip_instance();
		$res = $zip->open($temp_zip, 1); // 1 = CREATE
		if (true !== $res && 1 !== $res) {
			$safora_core->log("Failed to open temporary zip archive $temp_zip: " . (isset($zip->last_error) ? $zip->last_error : 'Unknown error'), 'error');
			return;
		}

		$files_batched = 0;
		$bytes_batched = 0;
		$total_entity_files = 0;
		$total_entity_bytes = 0;

		$exclusions = $this->get_exclusions_for_entity($entity_name);

		try {
			$dir_iterator = new RecursiveDirectoryIterator($dir_path, RecursiveDirectoryIterator::SKIP_DOTS);
			$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);

			foreach ($iterator as $item) {
				$real_path = $item->getRealPath();
				if (!$real_path) continue;

				// Critical guard: never scan or archive the backup directory itself
				if (0 === strpos($real_path, $real_backup_dir)) {
					continue;
				}

				$sub_path = substr($real_path, strlen(untrailingslashit($dir_path)) + 1);

				// Exclusions check
				if ($this->is_excluded($sub_path, $exclusions, $real_path)) {
					continue;
				}

				// Donotbackup check
				if ($item->isDir() && file_exists($real_path . '/.donotbackup')) {
					continue;
				}

				if ($item->isFile()) {
					// Incremental check
					if ($this->altered_since > 0 && $item->getMTime() <= $this->altered_since) {
						continue;
					}

					$fsize = $item->getSize();
					$zip->addFile($real_path, $sub_path);

					$files_batched++;
					$bytes_batched += $fsize;
					$total_entity_files++;
					$total_entity_bytes += $fsize;

					// Heartbeat pulse every 500 files
					if ($files_batched % 500 === 0) {
						Safora_Job_Scheduler::record_still_alive();
						Safora_Job_Scheduler::something_useful_happened();
					}

					// Check split threshold
					if ($bytes_batched >= $split_every_bytes) {
						$zip->close();
						$part_filename = (0 === $index) ? "{$base_name}.zip" : "{$base_name}" . ($index + 1) . ".zip";
						$final_part = $backup_dir . "/{$part_filename}";
						@rename($temp_zip, $final_part);

						$this->compute_checksums($final_part, $part_filename);
						$this->created_files[] = $part_filename;

						$safora_core->log("Created split archive part: $part_filename (" . Safora_Manipulation_Functions::format_bytes(filesize($final_part)) . ")");

						$index++;
						$bytes_batched = 0;
						$temp_zip = $backup_dir . "/{$base_name}" . ($index + 1) . ".zip.tmp";
						$zip = $this->get_zip_instance();
						$zip->open($temp_zip, 1);
					}
				}
			}
		} catch (Exception $e) {
			$safora_core->log("Notice during directory scan for entity '$entity_name': " . $e->getMessage(), 'warning');
		}

		$zip->close();

		if (file_exists($temp_zip) && filesize($temp_zip) > 0) {
			$final_name = (0 === $index) ? "{$base_name}.zip" : "{$base_name}" . ($index + 1) . ".zip";
			$final_path = $backup_dir . "/{$final_name}";
			@rename($temp_zip, $final_path);

			$this->compute_checksums($final_path, $final_name);
			$this->created_files[] = $final_name;

			$safora_core->log("Completed entity '$entity_name': $final_name (" . Safora_Manipulation_Functions::format_bytes(filesize($final_path)) . ", $total_entity_files files)");
		} elseif (file_exists($temp_zip)) {
			@unlink($temp_zip);
		}

		$safora_core->jobdata_set("entity_done_{$entity_name}", 1);
		Safora_Job_Scheduler::something_useful_happened();
	}

	/**
	 * Compute SHA1 and SHA256 checksums
	 */
	private function compute_checksums($filepath, $filename) {
		if (!file_exists($filepath)) return;
		$this->checksums['sha1'][$filename] = sha1_file($filepath);
		$this->checksums['sha256'][$filename] = hash_file('sha256', $filepath);
	}

	/**
	 * Build regex exclusion list for an entity including built-in sanity guards
	 */
	private function get_exclusions_for_entity($entity_name) {
		global $safora_core;
		$exclusions = array('*.tmp', '*deleteflag-*', '*.crypt');

		// Built-in entity containment guards
		if ('others' === $entity_name) {
			$exclusions[] = 'plugins';
			$exclusions[] = 'themes';
			$exclusions[] = 'uploads';
			$exclusions[] = 'upgrade';
			$exclusions[] = 'cache';
			$exclusions[] = 'updraft';
			$exclusions[] = 'safora';
		} elseif ('wpcore' === $entity_name) {
			$content_dir_name = defined('WP_CONTENT_DIR') ? basename(WP_CONTENT_DIR) : 'wp-content';
			$exclusions[] = $content_dir_name;
		}

		if ($safora_core) {
			$custom_backup_dir = basename($safora_core->backups_dir_location());
			if (!empty($custom_backup_dir)) {
				$exclusions[] = $custom_backup_dir;
			}
		}

		$raw = (string) Safora_Options::get_setting('exclude_' . $entity_name);
		$lines = explode("\n", $raw);
		foreach ($lines as $line) {
			$line = trim($line);
			if (!empty($line)) $exclusions[] = $line;
		}
		return array_unique($exclusions);
	}

	/**
	 * Test if a path matches exclusion patterns
	 */
	private function is_excluded($rel_path, $patterns, $real_path) {
		$rel_path = str_replace('\\', '/', $rel_path);
		foreach ($patterns as $pattern) {
			$pattern = trim($pattern, '/');
			if (fnmatch($pattern, $rel_path) || fnmatch($pattern, basename($rel_path)) || 0 === strpos($rel_path, $pattern . '/') || $rel_path === $pattern) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Execute database dump with native mysqldump and fast multi-row fallback
	 *
	 * @return string|false Created database dump filename
	 */
	public function backup_database() {
		global $wpdb, $safora_core;
		if (!$this->backup_db) return false;

		$nonce = $safora_core->get_job_nonce();
		$timestamp = $safora_core->get_job_timestamp();
		$backup_dir = $safora_core->backups_dir_location();

		$prefix = Safora_Options::get_backup_name_prefix();
		$db_filename = "{$prefix}_{$timestamp}_{$nonce}-db.gz";
		$final_db_path = $backup_dir . "/{$db_filename}";

		if (file_exists($final_db_path) && $safora_core->jobdata_get('db_done')) {
			$safora_core->log("Database backup already completed in previous run.");
			$this->created_files[] = $db_filename;
			return $db_filename;
		}

		$safora_core->log("Beginning database dump (WordPress table prefix: '{$wpdb->prefix}')...");

		// Gather tables
		$tables = $wpdb->get_col("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'");
		if (empty($tables)) {
			$safora_core->log("No database tables found to backup!", 'error');
			return false;
		}

		// Sort tables so critical tables come first
		usort($tables, array('Safora_Database_Utility', 'sort_tables'));

		// Adjust MySQL session sql_mode
		Safora_Database_Utility::set_sql_mode('NO_AUTO_VALUE_ON_ZERO', 'ANSI_QUOTES');

		$tmp_db_file = $backup_dir . "/{$db_filename}.tmp";
		$gz_main = function_exists('gzopen') ? @gzopen($tmp_db_file, 'wb6') : false;

		if (!$gz_main) {
			$safora_core->log("Could not open gzip stream for database dump at $tmp_db_file. Check directory permissions and PHP zlib extension.", 'error');
			return false;
		}

		// Detect native mysqldump binary
		$use_bin_mysqldump = (bool) Safora_Options::get_setting('binmysqldump');
		$mysqldump_bin = $use_bin_mysqldump ? Safora_Database_Utility::find_working_mysqldump() : false;

		if ($mysqldump_bin) {
			$safora_core->log("High-speed mysqldump binary detected: $mysqldump_bin");
		} else {
			$safora_core->log("Using high-speed multi-row PHP database exporter");
		}

		// Write header comments (compatible with both Safora and UpdraftPlus restorer parsers)
		$header = "-- Safora WordPress Database Backup\n"
			. "-- Generated: " . current_time('mysql') . "\n"
			. "-- Site URL: " . site_url() . "\n"
			. "-- Home URL: " . home_url() . "\n"
			. "-- Table Prefix: " . $wpdb->prefix . "\n"
			. "# Backup of: " . site_url() . "\n"
			. "# Home URL: " . home_url() . "\n"
			. "# Table prefix: " . $wpdb->prefix . "\n"
			. "# ABSPATH: " . ABSPATH . "\n"
			. "-- MySQL Server Version: " . $wpdb->db_version() . "\n"
			. "-- Engine: " . ($mysqldump_bin ? "Native mysqldump ($mysqldump_bin)" : "PHP Multi-Row Stream") . "\n\n"
			. "SET FOREIGN_KEY_CHECKS=0;\n"
			. "SET SQL_MODE=\"NO_AUTO_VALUE_ON_ZERO\";\n"
			. "SET time_zone = \"+00:00\";\n\n";

		gzwrite($gz_main, $header);

		try {
			foreach ($tables as $table) {
				Safora_Job_Scheduler::record_still_alive();
				$safora_core->log("Dumping table: $table");

				$table_dumped = false;

				// 1. Try native mysqldump first if available
				if ($mysqldump_bin) {
					$table_dumped = Safora_Database_Utility::dump_table_mysqldump($table, $gz_main, $mysqldump_bin, $backup_dir);
				}

				// 2. Fast PHP Multi-row fallback
				if (!$table_dumped) {
					Safora_Database_Utility::dump_table_php_fast($table, $gz_main);
				}

				Safora_Job_Scheduler::something_useful_happened();
			}

			gzwrite($gz_main, "SET FOREIGN_KEY_CHECKS=1;\n");
		} catch (Exception $e) {
			$safora_core->log("Exception dumping database: " . $e->getMessage(), 'error');
		}

		if (is_resource($gz_main)) {
			gzclose($gz_main);
		}

		if (file_exists($tmp_db_file) && filesize($tmp_db_file) > 0) {
			@rename($tmp_db_file, $final_db_path);
		} else {
			$safora_core->log("Database backup file was empty or missing.", 'error');
			return false;
		}

		// Optional Encryption
		$passphrase = Safora_Options::get_setting('encryption_passphrase');
		if (!empty($passphrase)) {
			$safora_core->log("Encrypting database archive with AES-256-CBC...");
			$crypt_file = $final_db_path . '.crypt';
			Safora_Encryption::encrypt_file($final_db_path, $crypt_file, $passphrase);
			@unlink($final_db_path);
			$db_filename .= '.crypt';
			$final_db_path = $crypt_file;
		}

		$this->compute_checksums($final_db_path, $db_filename);
		$this->created_files[] = $db_filename;
		$safora_core->jobdata_set('db_done', 1);

		$safora_core->log("Database backup completed: $db_filename (" . Safora_Manipulation_Functions::format_bytes(filesize($final_db_path)) . ")");
		return $db_filename;
	}

	public function get_created_files() {
		return $this->created_files;
	}

	public function get_checksums() {
		return $this->checksums;
	}
}
