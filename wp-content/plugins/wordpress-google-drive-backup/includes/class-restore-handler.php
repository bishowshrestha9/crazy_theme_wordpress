<?php
/**
 * Restore Handler for Google Drive Backup
 */

if (!defined('ABSPATH')) {
    exit;
}

class WP_GD_Restore_Handler
{
    private static $instance = null;
    private $oauth_handler;
    private $restore_temp_dir;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $upload_dir = wp_upload_dir();
        $this->restore_temp_dir = $upload_dir['basedir'] . '/wp-gd-restore-temp';
        $this->oauth_handler = WP_GD_OAuth_Handler::get_instance();
    }

    public function list_backups()
    {
        $access_token = $this->oauth_handler->get_access_token();
        if (is_wp_error($access_token)) {
            return $access_token;
        }

        $backup_folder_id = get_option('wp_gd_backup_folder_id');
        if (!$backup_folder_id) {
            return array();
        }

        $url = 'https://www.googleapis.com/drive/v3/files?q=' . urlencode("'" . $backup_folder_id . "' in parents and trashed=false") . '&fields=id,name,size,createdTime,webViewLink&orderBy=createdTime%20desc';

        $response = wp_remote_get($url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
            ),
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($body['error'])) {
            return new WP_Error('list_failed', $body['error']['message']);
        }

        return isset($body['files']) ? $body['files'] : array();
    }

    public function download_backup($file_id)
    {
        $access_token = $this->oauth_handler->get_access_token();
        if (is_wp_error($access_token)) {
            return $access_token;
        }

        try {
            if (!is_dir($this->restore_temp_dir)) {
                wp_mkdir_p($this->restore_temp_dir);
            }

            // Step 1: Get file metadata
            $file_metadata = $this->get_file_metadata($file_id, $access_token);
            if (is_wp_error($file_metadata)) {
                throw new Exception($file_metadata->get_error_message());
            }

            // Step 2: Download file
            $downloaded_file = $this->download_file($file_id, $file_metadata['name'], $access_token);
            if (is_wp_error($downloaded_file)) {
                throw new Exception($downloaded_file->get_error_message());
            }

            return array(
                'success' => true,
                'file_path' => $downloaded_file,
                'file_name' => $file_metadata['name'],
                'file_size' => $file_metadata['size'],
            );
        } catch (Exception $e) {
            $this->cleanup_restore_temp();
            return new WP_Error('download_failed', $e->getMessage());
        }
    }

    private function get_file_metadata($file_id, $access_token)
    {
        $url = 'https://www.googleapis.com/drive/v3/files/' . urlencode($file_id) . '?fields=id,name,size,createdTime&supportsAllDrives=true';

        $response = wp_remote_get($url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
            ),
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($body['error'])) {
            return new WP_Error('metadata_failed', $body['error']['message']);
        }

        return $body;
    }

    private function download_file($file_id, $file_name, $access_token)
    {
        $url = 'https://www.googleapis.com/drive/v3/files/' . urlencode($file_id) . '?alt=media&supportsAllDrives=true';

        $local_file = $this->restore_temp_dir . '/' . sanitize_file_name($file_name);

        $response = wp_remote_get($url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $access_token,
            ),
            'timeout' => 300,
            'stream' => true,
            'filename' => $local_file,
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        if (file_exists($local_file)) {
            return $local_file;
        }

        return new WP_Error('download_failed', 'Failed to download backup file.');
    }

    public function extract_backup($file_path)
    {
        if (!file_exists($file_path)) {
            return new WP_Error('file_not_found', 'Backup file not found.');
        }

        $extract_dir = $this->restore_temp_dir . '/extracted-' . uniqid();

        if (!is_dir($extract_dir)) {
            wp_mkdir_p($extract_dir);
        }

        $zip = new ZipArchive();

        if ($zip->open($file_path) !== true) {
            return new WP_Error('extract_failed', 'Failed to open backup ZIP file.');
        }

        if (!$zip->extractTo($extract_dir)) {
            $zip->close();
            return new WP_Error('extract_failed', 'Failed to extract backup files.');
        }

        $zip->close();

        return $extract_dir;
    }

    public function restore_database($extract_dir)
    {
        $db_file = $extract_dir . '/database.sql';

        if (!file_exists($db_file)) {
            return new WP_Error('db_file_not_found', 'Database file not found in backup.');
        }

        global $wpdb;

        $sql = file_get_contents($db_file);
        if (false === $sql) {
            return new WP_Error('db_read_failed', 'Failed to read database file.');
        }

        // Parse and execute SQL statements
        $statements = $this->parse_sql_statements($sql);

        foreach ($statements as $statement) {
            if (trim($statement)) {
                $result = $wpdb->query($statement);

                if ($result === false) {
                    return new WP_Error('db_restore_failed', 'Database query error: ' . $wpdb->last_error);
                }
            }
        }

        return true;
    }

    private function parse_sql_statements($sql)
    {
        $statements = array();
        $statement = '';
        $lines = explode("\n", $sql);

        foreach ($lines as $line) {
            $line = trim($line);

            // Skip comments and empty lines
            if (empty($line) || substr($line, 0, 2) === '--') {
                continue;
            }

            $statement .= $line . "\n";

            if (substr($line, -1) === ';') {
                $statements[] = $statement;
                $statement = '';
            }
        }

        return $statements;
    }

    public function restore_files($extract_dir)
    {
        $wordpress_dir = $extract_dir . '/wordpress';
        $wp_content_dir = $extract_dir . '/wp-content';

        try {
            if (is_dir($wordpress_dir)) {
                $this->copy_directory($wordpress_dir, ABSPATH, array('wp-content', 'wp-config.php'));
            }

            if (is_dir($wp_content_dir)) {
                $this->copy_directory($wp_content_dir, WP_CONTENT_DIR);
            }

            return true;
        } catch (Exception $e) {
            return new WP_Error('file_restore_failed', $e->getMessage());
        }
    }

    private function copy_directory($src, $dst, $exclude = array())
    {
        if (!is_dir($dst)) {
            wp_mkdir_p($dst);
        }

        $files = new RecursiveDirectoryIterator($src, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($files, RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file) {
            $relative_path = substr($file->getRealPath(), strlen($src) + 1);

            // Check exclusions
            $skip = false;
            foreach ($exclude as $exclude_pattern) {
                if (strpos($relative_path, $exclude_pattern) === 0) {
                    $skip = true;
                    break;
                }
            }

            if ($skip) {
                continue;
            }

            $dst_path = $dst . '/' . $relative_path;

            if ($file->isDir()) {
                if (!is_dir($dst_path)) {
                    wp_mkdir_p($dst_path);
                }
            } else {
                copy($file->getRealPath(), $dst_path);
            }
        }
    }

    public function perform_full_restore($file_id)
    {
        try {
            // Step 1: Download backup
            $download_result = $this->download_backup($file_id);
            if (is_wp_error($download_result)) {
                throw new Exception($download_result->get_error_message());
            }

            $backup_file = $download_result['file_path'];

            // Step 2: Extract backup
            $extract_dir = $this->extract_backup($backup_file);
            if (is_wp_error($extract_dir)) {
                throw new Exception($extract_dir->get_error_message());
            }

            // Step 3: Restore database
            $db_restore = $this->restore_database($extract_dir);
            if (is_wp_error($db_restore)) {
                throw new Exception($db_restore->get_error_message());
            }

            // Step 4: Restore files (optional)
            // Uncomment to restore files
            // $file_restore = $this->restore_files($extract_dir);
            // if (is_wp_error($file_restore)) {
            //     throw new Exception($file_restore->get_error_message());
            // }

            // Step 5: Clean up
            $this->cleanup_restore_temp();

            return array(
                'success' => true,
                'message' => 'Restore completed successfully.',
            );
        } catch (Exception $e) {
            $this->cleanup_restore_temp();
            return new WP_Error('restore_failed', $e->getMessage());
        }
    }

    public function cleanup_restore_temp()
    {
        if (is_dir($this->restore_temp_dir)) {
            $this->remove_directory($this->restore_temp_dir);
        }
    }

    private function remove_directory($dir)
    {
        if (!is_dir($dir)) {
            return;
        }

        $files = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($files, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        rmdir($dir);
    }
}
