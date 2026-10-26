<?php
/**
 * Safora Zip Engine Abstraction Layer (BinZip, ZipArchive, PclZip)
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

/**
 * Enhanced ZipArchive wrapper with error tracking
 */
if (class_exists('ZipArchive')) {
	class Safora_ZipArchive extends ZipArchive {
		public $last_error = '';

		#[\ReturnTypeWillChange]
		public function open($filename, $flags = 0) {
			$res = parent::open($filename, $flags);
			if (true !== $res) {
				$this->last_error = $this->get_error_description($res);
				return $res;
			}
			return true;
		}

		public function get_error_description($code) {
			switch ($code) {
				case ZipArchive::ER_EXISTS: return 'File already exists';
				case ZipArchive::ER_INCONS: return 'Zip archive inconsistent';
				case ZipArchive::ER_INVAL: return 'Invalid argument';
				case ZipArchive::ER_MEMORY: return 'Malloc failure';
				case ZipArchive::ER_NOENT: return 'No such file';
				case ZipArchive::ER_NOZIP: return 'Not a zip archive';
				case ZipArchive::ER_OPEN: return "Can't open file";
				case ZipArchive::ER_READ: return 'Read error';
				case ZipArchive::ER_SEEK: return 'Seek error';
				default: return 'Unknown zip error (' . $code . ')';
			}
		}
	}
}

/**
 * Native System Binary Zip Engine (proc_open pipe streaming)
 */
class Safora_BinZip {
	private $binzip_path = '';
	private $zip_path = '';
	private $addfiles = array();
	private $adddirs = array();
	public $last_error = '';
	public $numFiles = 0;

	public function __construct($binzip_path = '/usr/bin/zip') {
		$this->binzip_path = $binzip_path;
	}

	public function open($filename, $flags = 0) {
		$this->zip_path = $filename;
		$this->addfiles = array();
		$this->adddirs = array();
		$this->numFiles = 0;
		$this->last_error = '';
		return true;
	}

	public function addFile($filepath, $entryname) {
		$filepath = str_replace('\\', '/', $filepath);
		$entryname = str_replace('\\', '/', $entryname);

		// Calculate base directory from filepath and entryname
		$pos = strrpos($filepath, $entryname);
		if (false !== $pos) {
			$base = substr($filepath, 0, $pos);
			$base = rtrim($base, '/\\');
		} else {
			$base = dirname($filepath);
			$entryname = basename($filepath);
		}

		$this->addfiles[$base][] = $entryname;
		$this->numFiles++;
		return true;
	}

	public function addEmptyDir($entryname) {
		$this->adddirs[] = rtrim($entryname, '/\\');
		return true;
	}

	public function close() {
		if ($this->numFiles === 0 && empty($this->adddirs)) {
			return true;
		}

		if (file_exists($this->zip_path) && 0 === filesize($this->zip_path)) {
			@unlink($this->zip_path);
		}

		$descriptorspec = array(
			0 => array('pipe', 'r'), // stdin
			1 => array('pipe', 'w'), // stdout
			2 => array('pipe', 'w')  // stderr
		);

		foreach ($this->addfiles as $base_dir => $files) {
			if (!is_dir($base_dir)) continue;

			$cmd = escapeshellcmd($this->binzip_path) . ' -v -@ ' . escapeshellarg($this->zip_path);
			$process = @proc_open($cmd, $descriptorspec, $pipes, $base_dir);

			if (!is_resource($process)) {
				$this->last_error = 'proc_open failed to spawn zip binary: ' . $this->binzip_path;
				return false;
			}

			// Stream directories first
			if (!empty($this->adddirs)) {
				foreach ($this->adddirs as $d) {
					@fwrite($pipes[0], $d . "/\n");
				}
				$this->adddirs = array();
			}

			// Stream files in batches
			$buffer = '';
			foreach ($files as $f) {
				$buffer .= $f . "\n";
				if (strlen($buffer) > 65536) {
					@fwrite($pipes[0], $buffer);
					$buffer = '';
				}
			}
			if (!empty($buffer)) {
				@fwrite($pipes[0], $buffer);
			}

			@fclose($pipes[0]);

			$stdout = @stream_get_contents($pipes[1]);
			@fclose($pipes[1]);

			$stderr = @stream_get_contents($pipes[2]);
			@fclose($pipes[2]);

			$return_code = @proc_close($process);

			if ($return_code !== 0 && !file_exists($this->zip_path)) {
				$this->last_error = 'BinZip error (code ' . $return_code . '): ' . trim($stderr . ' ' . $stdout);
				return false;
			}
		}

		return file_exists($this->zip_path) && filesize($this->zip_path) > 0;
	}

	public function extractTo($destination) {
		// Try system unzip first
		$possible_unzip = array('/usr/bin/unzip', '/usr/local/bin/unzip', '/bin/unzip');
		$unzip = Safora_Filesystem_Functions::find_working_binary($possible_unzip);
		if ($unzip && function_exists('proc_open')) {
			$cmd = escapeshellcmd($unzip) . ' -o ' . escapeshellarg($this->zip_path) . ' -d ' . escapeshellarg($destination);
			$descriptorspec = array(
				0 => array('pipe', 'r'),
				1 => array('pipe', 'w'),
				2 => array('pipe', 'w')
			);
			$process = @proc_open($cmd, $descriptorspec, $pipes);
			if (is_resource($process)) {
				@fclose($pipes[0]);
				$stdout = @stream_get_contents($pipes[1]);
				@fclose($pipes[1]);
				$stderr = @stream_get_contents($pipes[2]);
				@fclose($pipes[2]);
				$ret = @proc_close($process);
				if (0 === $ret) return true;
			}
		}

		// Fallback to ZipArchive or PclZip
		if (class_exists('ZipArchive')) {
			$zip = new ZipArchive();
			if (true === $zip->open($this->zip_path)) {
				$res = $zip->extractTo($destination);
				$zip->close();
				return $res;
			}
		}

		if (!class_exists('PclZip')) {
			require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');
		}
		$pclzip = new PclZip($this->zip_path);
		$res = $pclzip->extract(PCLZIP_OPT_PATH, $destination);
		return (0 !== $res);
	}
}

/**
 * PclZip Adapter providing ZipArchive-compatible interface with batch optimization
 */
class Safora_PclZip {
	private $zip_path = '';
	private $files_to_add = array();
	public $last_error = '';
	public $numFiles = 0;

	public function open($filename, $flags = 0) {
		$this->zip_path = $filename;
		$this->files_to_add = array();
		$this->numFiles = 0;

		if (!defined('PCLZIP_TEMPORARY_DIR')) {
			define('PCLZIP_TEMPORARY_DIR', dirname($filename) . '/');
		}

		if (!class_exists('PclZip')) {
			require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');
		}

		return true;
	}

	public function addFile($filepath, $entryname) {
		$this->files_to_add[] = array(
			'real' => $filepath,
			'stored' => $entryname,
		);
		$this->numFiles++;
		return true;
	}

	public function addEmptyDir($entryname) {
		return true;
	}

	public function close() {
		if (empty($this->files_to_add)) return true;

		if (!class_exists('PclZip')) {
			require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');
		}

		$pclzip = new PclZip($this->zip_path);

		// Group files by base directory to add in bulk instead of 1-by-1
		$bulk_groups = array();
		foreach ($this->files_to_add as $item) {
			$base_remove = dirname($item['real']);
			$base_add = dirname($item['stored']);
			$group_key = $base_remove . '|' . $base_add;
			$bulk_groups[$group_key]['remove'] = $base_remove;
			$bulk_groups[$group_key]['add'] = $base_add;
			$bulk_groups[$group_key]['files'][] = $item['real'];
		}

		foreach ($bulk_groups as $group) {
			$res = $pclzip->add($group['files'], PCLZIP_OPT_REMOVE_PATH, $group['remove'], PCLZIP_OPT_ADD_PATH, $group['add']);
			if (0 == $res) {
				$this->last_error = $pclzip->errorInfo(true);
				return false;
			}
		}

		$this->files_to_add = array();
		return true;
	}

	public function extractTo($destination) {
		if (!class_exists('PclZip')) {
			require_once(ABSPATH . 'wp-admin/includes/class-pclzip.php');
		}
		$pclzip = new PclZip($this->zip_path);
		$res = $pclzip->extract(PCLZIP_OPT_PATH, $destination);
		if (0 == $res) {
			$this->last_error = $pclzip->errorInfo(true);
			return false;
		}
		return true;
	}
}
