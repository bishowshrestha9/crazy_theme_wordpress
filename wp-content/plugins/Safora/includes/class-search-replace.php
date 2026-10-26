<?php
/**
 * Safora Serialization-Safe Recursive Search and Replace Engine
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Search_Replace {

	private $search = array();
	private $replace = array();
	private $max_depth = 20;
	private $page_size = 2000;
	private $report = array(
		'tables'  => 0,
		'rows'    => 0,
		'change'  => 0,
		'updates' => 0,
		'errors'  => array(),
	);

	/**
	 * Tables that do not contain URLs and can be safely skipped for performance and timeout avoidance
	 */
	private $skip_tables = array(
		'term_relationships',
		'slim_stats',
		'statpress',
		'redirection_logs',
		'redirection_404',
		'wfhits',
		'wfHits',
		'wffilemods',
		'wfFileMods',
		'tts_trafficstats',
		'relevanssi',
		'itsec_log',
		'woocommerce_sessions',
		'woocommerce_order_items',
	);

	public function __construct($search = array(), $replace = array()) {
		$this->search = (array) $search;
		$this->replace = (array) $replace;
	}

	/**
	 * Perform search and replace across specified tables
	 *
	 * @param array $tables
	 * @return array
	 */
	public function run($tables = array()) {
		global $wpdb;

		if (empty($this->search) || empty($this->replace)) {
			return $this->report;
		}

		foreach ($tables as $table) {
			$this->replace_table($table);
		}

		return $this->report;
	}

	/**
	 * Run replacement on a single table with support for single PK, composite PK, and tables without PK
	 *
	 * @param string $table
	 * @return void
	 */
	private function replace_table($table) {
		global $wpdb;

		// Check if table should be skipped
		$unprefixed = preg_replace('/^' . preg_quote($wpdb->prefix, '/') . '/i', '', $table);
		if (in_array(strtolower($unprefixed), array_map('strtolower', $this->skip_tables))) {
			return;
		}

		$this->report['tables']++;

		$columns_info = $wpdb->get_results("DESCRIBE `$table`", ARRAY_A);
		if (empty($columns_info)) return;

		$columns = array();
		$blob_cols = array();
		$pri_cols = array();

		foreach ($columns_info as $col) {
			$field = $col['Field'];
			$type = strtolower($col['Type']);
			$columns[] = $field;

			if ('pri' === strtolower($col['Key'])) {
				$pri_cols[] = $field;
			}

			if (preg_match('/(blob|bytea|binary|varbinary)/i', $type)) {
				$blob_cols[] = $field;
			}
		}

		$has_single_pk = (1 === count($pri_cols));
		$single_pk = $has_single_pk ? reset($pri_cols) : false;

		$last_id = 0;
		$offset = 0;
		$continue = true;

		while ($continue) {
			Safora_Job_Scheduler::record_still_alive();
			@set_time_limit(300);

			if ($single_pk) {
				$query = $wpdb->prepare(
					"SELECT * FROM `$table` WHERE `$single_pk` > %d ORDER BY `$single_pk` ASC LIMIT %d",
					$last_id,
					$this->page_size
				);
			} else {
				$query = $wpdb->prepare(
					"SELECT * FROM `$table` LIMIT %d OFFSET %d",
					$this->page_size,
					$offset
				);
			}

			$rows = $wpdb->get_results($query, ARRAY_A);
			if (empty($rows)) break;

			foreach ($rows as $row) {
				$this->report['rows']++;
				if ($single_pk && isset($row[$single_pk])) {
					$last_id = $row[$single_pk];
				}

				$update_needed = false;
				$new_data = array();

				foreach ($row as $col_name => $val) {
					if (in_array($col_name, $blob_cols)) continue; // Skip binary blobs
					if (null === $val || '' === $val || is_numeric($val)) continue;

					$edited = $this->recursive_unserialize_replace($val);
					if ($edited !== $val) {
						$update_needed = true;
						$new_data[$col_name] = $edited;
						$this->report['change']++;
					}
				}

				if ($update_needed) {
					// Build WHERE clause
					if ($single_pk && isset($row[$single_pk])) {
						$where = array($single_pk => $row[$single_pk]);
					} elseif (!empty($pri_cols)) {
						$where = array();
						foreach ($pri_cols as $pcol) {
							$where[$pcol] = $row[$pcol];
						}
					} else {
						// Fallback: match entire original row
						$where = $row;
					}

					$res = $wpdb->update($table, $new_data, $where);
					if (false !== $res) {
						$this->report['updates']++;
					} else {
						$this->report['errors'][] = "Failed updating table $table: " . $wpdb->last_error;
					}
				}
			}

			$row_count = count($rows);
			$offset += $row_count;

			if ($row_count < $this->page_size) {
				$continue = false;
			}
		}
	}

	/**
	 * Recursively unserialize and replace values in arrays, objects, JSON, and serialized strings
	 *
	 * @param mixed $data
	 * @param int $depth
	 * @param array $visited
	 * @return mixed
	 */
	public function recursive_unserialize_replace($data, $depth = 0, $visited = array()) {
		if ($depth > $this->max_depth) return $data;

		// Guard circular object references
		if (is_object($data)) {
			if (in_array($data, $visited, true)) return $data;
			$visited[] = $data;
		}

		if (is_string($data)) {
			// Check if serialized PHP string
			if ($this->is_serialized_string($data)) {
				// Use safe unserialization without constructing foreign class instances
				$unserialized = @unserialize($data, array('allowed_classes' => false));
				if (false !== $unserialized || 'b:0;' === $data) {
					$processed = $this->recursive_unserialize_replace($unserialized, $depth + 1, $visited);
					return serialize($processed);
				}
			}

			// Check if JSON
			if (('{' === $data[0] || '[' === $data[0]) && strlen($data) > 1) {
				$json = json_decode($data, true);
				if (null !== $json && JSON_ERROR_NONE === json_last_error()) {
					$processed = $this->recursive_unserialize_replace($json, $depth + 1, $visited);
					return json_encode($processed);
				}
			}

			// Plain string replacement
			return str_replace($this->search, $this->replace, $data);
		}

		if (is_array($data)) {
			$new_array = array();
			foreach ($data as $key => $val) {
				$new_key = is_string($key) ? str_replace($this->search, $this->replace, $key) : $key;
				$new_array[$new_key] = $this->recursive_unserialize_replace($val, $depth + 1, $visited);
			}
			return $new_array;
		}

		if (is_object($data)) {
			$new_obj = clone $data;
			$props = get_object_vars($data);
			foreach ($props as $prop => $val) {
				$new_obj->$prop = $this->recursive_unserialize_replace($val, $depth + 1, $visited);
			}
			return $new_obj;
		}

		return $data;
	}

	/**
	 * Check if a string is serialized data
	 *
	 * @param string $val
	 * @return bool
	 */
	private function is_serialized_string($val) {
		if (!is_string($val)) return false;
		$val = trim($val);
		if ('N;' === $val) return true;
		if (strlen($val) < 4) return false;
		if (':' !== $val[1]) return false;
		$lastc = substr($val, -1);
		if (';' !== $lastc && '}' !== $lastc) return false;
		$token = $val[0];
		switch ($token) {
			case 's':
				if ('"' !== substr($val, -2, 1)) return false;
			case 'a':
			case 'O':
				return (bool) preg_match("/^{$token}:[0-9]+:/s", $val);
			case 'b':
			case 'i':
			case 'd':
				return (bool) preg_match("/^{$token}:[0-9.E-]+;\$/si", $val);
		}
		return false;
	}
}
