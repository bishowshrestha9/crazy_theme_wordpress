<?php
/**
 * Safora Storage Driver Interface
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

interface Safora_Storage_Interface {

	/**
	 * Upload files array to remote storage
	 *
	 * @param array $backup_files Array of filenames to upload
	 * @return bool|WP_Error
	 */
	public function backup($backup_files);

	/**
	 * Download a single file from remote storage to local path
	 *
	 * @param string $file Filename to download
	 * @return bool|WP_Error
	 */
	public function download($file);

	/**
	 * Delete files from remote storage
	 *
	 * @param array $files Filenames to delete
	 * @param array $backup_history Backup set metadata
	 * @return bool|WP_Error
	 */
	public function delete($files, $backup_history = array());

	/**
	 * List all backup files available in remote storage
	 *
	 * @return array
	 */
	public function listfiles();

	/**
	 * Test credentials / connection to remote service
	 *
	 * @return array Array with 'result' => bool, 'message' => string
	 */
	public function credentials_test();
}
