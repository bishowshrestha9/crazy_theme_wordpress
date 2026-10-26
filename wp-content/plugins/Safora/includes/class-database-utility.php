<?php
/**
 * Safora Database Utilities (mysqldump & Fast Multi-Row PHP Exporter)
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Database_Utility {

	/**
	 * Sort tables so vital tables (options, sitemeta, users, usermeta) are dumped first
	 *
	 * @param string $a Table name
	 * @param string $b Table name
	 * @return int
	 */
	public static function sort_tables($a, $b) {
		global $wpdb;
		$priority_tables = array(
			$wpdb->prefix . 'options',
			$wpdb->prefix . 'sitemeta',
			$wpdb->prefix . 'users',
			$wpdb->prefix . 'usermeta',
		);

		$pos_a = array_search($a, $priority_tables);
		$pos_b = array_search($b, $priority_tables);

		if (false !== $pos_a && false !== $pos_b) {
			return $pos_a - $pos_b;
		}
		if (false !== $pos_a) return -1;
		if (false !== $pos_b) return 1;

		return strcmp($a, $b);
	}

	/**
	 * Locate a working mysqldump or mariadb-dump binary on the system
	 *
	 * @return string|false
	 */
	public static function find_working_mysqldump() {
		if (!function_exists('proc_open')) return false;

		$possible_bins = array(
			'/usr/bin/mysqldump',
			'/usr/local/bin/mysqldump',
			'/usr/bin/mariadb-dump',
			'/usr/local/bin/mariadb-dump',
			'/bin/mysqldump',
			'/usr/local/mysql/bin/mysqldump',
			'/opt/local/bin/mysqldump',
		);

		$bin = Safora_Filesystem_Functions::find_working_binary($possible_bins);
		if (!$bin) return false;

		// Verify binary executes properly
		$descriptorspec = array(
			0 => array('pipe', 'r'),
			1 => array('pipe', 'w'),
			2 => array('pipe', 'w'),
		);

		$process = @proc_open(escapeshellcmd($bin) . ' --version', $descriptorspec, $pipes);
		if (!is_resource($process)) return false;

		@fclose($pipes[0]);
		$out = @stream_get_contents($pipes[1]);
		@fclose($pipes[1]);
		@fclose($pipes[2]);
		$status = @proc_close($process);

		return (0 === $status) ? $bin : false;
	}

	/**
	 * Dump a table using native mysqldump binary directly into a gzip stream
	 *
	 * @param string $table
	 * @param resource $gz_handle Open gzip file handle
	 * @param string $mysqldump_bin
	 * @param string $backup_dir
	 * @return bool
	 */
	public static function dump_table_mysqldump($table, $gz_handle, $mysqldump_bin, $backup_dir) {
		global $wpdb;

		// Create temporary config file for secure credential passing without CLI exposure
		$cnf_file = $backup_dir . '/.safora_my_' . md5(uniqid(rand(), true)) . '.cnf';
		$db_host = DB_HOST;
		$db_port = 3306;
		$db_socket = '';

		// Handle host:port or host:socket format
		if (false !== strpos($db_host, ':')) {
			$parts = explode(':', $db_host, 2);
			$db_host = $parts[0];
			if (is_numeric($parts[1])) {
				$db_port = (int) $parts[1];
			} elseif (!empty($parts[1])) {
				$db_socket = $parts[1];
			}
		}

		$cnf_content = "[mysqldump]\n"
			. "user=\"" . addcslashes(DB_USER, "\\\"\n\r\t") . "\"\n"
			. "password=\"" . addcslashes(DB_PASSWORD, "\\\"\n\r\t") . "\"\n"
			. "host=\"" . addcslashes($db_host, "\\\"\n\r\t") . "\"\n";

		if (!empty($db_port)) {
			$cnf_content .= "port=" . $db_port . "\n";
		}
		if (!empty($db_socket)) {
			$cnf_content .= "socket=\"" . addcslashes($db_socket, "\\\"\n\r\t") . "\"\n";
		}

		@file_put_contents($cnf_file, $cnf_content);
		@chmod($cnf_file, 0600);

		$cmd = escapeshellcmd($mysqldump_bin)
			. " --defaults-extra-file=" . escapeshellarg($cnf_file)
			. " --quick"
			. " --single-transaction"
			. " --hex-blob"
			. " --no-tablespaces"
			. " --skip-comments"
			. " --skip-opt"
			. " --add-drop-table"
			. " --add-locks"
			. " --create-options"
			. " --disable-keys"
			. " --extended-insert"
			. " --set-charset"
			. " " . escapeshellarg(DB_NAME)
			. " " . escapeshellarg($table);

		$descriptorspec = array(
			0 => array('pipe', 'r'),
			1 => array('pipe', 'w'),
			2 => array('pipe', 'w'),
		);

		$process = @proc_open($cmd, $descriptorspec, $pipes);
		$success = false;

		if (is_resource($process)) {
			@fclose($pipes[0]);

			while (!feof($pipes[1])) {
				$chunk = @fread($pipes[1], 65536);
				if (false !== $chunk && strlen($chunk) > 0) {
					gzwrite($gz_handle, $chunk);
				}
			}
			@fclose($pipes[1]);

			$err = @stream_get_contents($pipes[2]);
			@fclose($pipes[2]);

			$ret = @proc_close($process);
			$success = (0 === $ret);
		}

		@unlink($cnf_file);
		return $success;
	}

	/**
	 * Fast PHP fallback database table dump with multi-row batched INSERT statements
	 *
	 * @param string $table
	 * @param resource $gz_handle
	 * @return bool
	 */
	public static function dump_table_php_fast($table, $gz_handle) {
		global $wpdb;

		$create_sql = self::get_create_table($table);
		if (!$create_sql) return false;

		gzwrite($gz_handle, "DROP TABLE IF EXISTS `$table`;\n");
		gzwrite($gz_handle, $create_sql . ";\n\n");

		$struct = self::get_table_structure($table);
		$pk = $struct['primary_key'];
		$columns = $struct['columns'];
		$blob_cols = $struct['blob_columns'];
		$bit_cols = $struct['bit_columns'];
		$numeric_cols = $struct['numeric_columns'];

		$last_id = 0;
		$continue = true;
		$chunk_size = 1000;
		$table_rows = 0;
		$batch_insert_size = 100; // 100 rows per INSERT statement for high speed

		$col_list = "`" . implode('`, `', $columns) . "`";

		while ($continue) {
			Safora_Job_Scheduler::record_still_alive();

			if ($pk) {
				$query = $wpdb->prepare(
					"SELECT * FROM `$table` WHERE `$pk` > %d ORDER BY `$pk` ASC LIMIT %d",
					$last_id,
					$chunk_size
				);
			} else {
				$query = sprintf("SELECT * FROM `%s` LIMIT %d, %d", $table, $table_rows, $chunk_size);
			}

			$rows = $wpdb->get_results($query, ARRAY_A);
			if (empty($rows)) break;

			$insert_batch = array();

			foreach ($rows as $row) {
				$table_rows++;
				if ($pk && isset($row[$pk])) {
					$last_id = $row[$pk];
				}

				$values = array();
				foreach ($columns as $col) {
					$val = isset($row[$col]) ? $row[$col] : null;

					if (null === $val) {
						$values[] = "NULL";
					} elseif (in_array($col, $blob_cols)) {
						$values[] = "0x" . bin2hex($val);
					} elseif (in_array($col, $bit_cols)) {
						$values[] = "b'" . decbin(ord($val)) . "'";
					} elseif (in_array($col, $numeric_cols) && is_numeric($val)) {
						$values[] = $val;
					} else {
						if (!empty($wpdb->dbh) && is_object($wpdb->dbh) && function_exists('mysqli_real_escape_string')) {
							$escaped = mysqli_real_escape_string($wpdb->dbh, $val);
						} else {
							$escaped = $wpdb->_real_escape($val);
						}
						$values[] = "'" . $escaped . "'";
					}
				}

				$insert_batch[] = "(" . implode(', ', $values) . ")";

				if (count($insert_batch) >= $batch_insert_size) {
					$sql = "INSERT INTO `$table` ($col_list) VALUES\n" . implode(",\n", $insert_batch) . ";\n";
					gzwrite($gz_handle, $sql);
					$insert_batch = array();
				}
			}

			if (!empty($insert_batch)) {
				$sql = "INSERT INTO `$table` ($col_list) VALUES\n" . implode(",\n", $insert_batch) . ";\n";
				gzwrite($gz_handle, $sql);
			}

			if (count($rows) < $chunk_size) {
				$continue = false;
			}
		}

		gzwrite($gz_handle, "\n");
		return true;
	}

	/**
	 * Set or modify MySQL session SQL mode
	 *
	 * @param string $add Comma-separated modes to add
	 * @param string $remove Comma-separated modes to remove
	 * @return void
	 */
	public static function set_sql_mode($add = '', $remove = '') {
		global $wpdb;
		$current_mode = $wpdb->get_var("SELECT @@SESSION.sql_mode");
		if (null === $current_mode) return;

		$modes = explode(',', $current_mode);
		$modes = array_map('trim', $modes);

		if (!empty($remove)) {
			$to_remove = array_map('trim', explode(',', $remove));
			$modes = array_diff($modes, $to_remove);
		}

		if (!empty($add)) {
			$to_add = array_map('trim', explode(',', $add));
			$modes = array_unique(array_merge($modes, $to_add));
		}

		$new_mode = implode(',', array_filter($modes));
		$wpdb->query($wpdb->prepare("SET SESSION sql_mode = %s", $new_mode));
	}

	/**
	 * Introspect table columns to extract metadata for chunked dumping
	 *
	 * @param string $table
	 * @return array
	 */
	public static function get_table_structure($table) {
		global $wpdb;
		$columns = $wpdb->get_results("DESCRIBE `$table`", ARRAY_A);
		if (empty($columns)) return array();

		$info = array(
			'primary_key' => false,
			'has_composite_pk' => false,
			'columns' => array(),
			'blob_columns' => array(),
			'bit_columns' => array(),
			'numeric_columns' => array(),
		);

		$pk_count = 0;
		foreach ($columns as $col) {
			$field = $col['Field'];
			$type = strtolower($col['Type']);
			$key = $col['Key'];

			$info['columns'][] = $field;

			if ('pri' === strtolower($key)) {
				$pk_count++;
				if (1 === $pk_count) {
					$info['primary_key'] = $field;
				}
			}

			if (preg_match('/(blob|bytea|binary|varbinary)/i', $type)) {
				$info['blob_columns'][] = $field;
			} elseif (0 === strpos($type, 'bit')) {
				$info['bit_columns'][] = $field;
			} elseif (preg_match('/(int|float|double|decimal)/i', $type)) {
				$info['numeric_columns'][] = $field;
			}
		}

		if ($pk_count > 1) {
			$info['has_composite_pk'] = true;
			$info['primary_key'] = false; // Primary key pointer paging requires single column PK
		}

		return $info;
	}

	/**
	 * Get CREATE TABLE statement
	 *
	 * @param string $table
	 * @return string|false
	 */
	public static function get_create_table($table) {
		global $wpdb;
		$row = $wpdb->get_row("SHOW CREATE TABLE `$table`", ARRAY_N);
		return isset($row[1]) ? $row[1] : false;
	}

	/**
	 * Check if stored routines / triggers are supported
	 *
	 * @return bool
	 */
	public static function is_stored_routine_supported() {
		global $wpdb;
		$res = $wpdb->get_var("SHOW VARIABLES LIKE 'log_bin_trust_function_creators'");
		return ('ON' === strtoupper($res));
	}
}
