<?php
/**
 * Backup Generator for Google Drive Backup
 */

if (!defined('ABSPATH')) {
    exit;
}

class WP_GD_Backup_Generator
{
    private static $instance = null;
    private $backup_dir;
    private $temp_dir;

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
        $this->backup_dir = $upload_dir['basedir'] . '/wp-gd-backups';
        $this->temp_dir = $this->backup_dir . '/temp-' . uniqid();

        if (!is_dir($this->backup_dir)) {
            wp_mkdir_p($this->backup_dir);
        }
    }

    public function generate_backup($backup_type = 'full')
    {
        global $wpdb;

        try {
            // Create temp directory for backup files
            if (!is_dir($this->temp_dir)) {
                wp_mkdir_p($this->temp_dir);
            }

            // Step 1: Export database
            $db_file = $this->export_database();
            if (is_wp_error($db_file)) {
                throw new Exception($db_file->get_error_message());
            }

            // Step 2: Create ZIP archive
            $zip_file = $this->create_zip($db_file, $backup_type);
            if (is_wp_error($zip_file)) {
                throw new Exception($zip_file->get_error_message());
            }

            // Step 3: Clean up temp files
            $this->cleanup_temp();

            return array(
                'success' => true,
                'file_path' => $zip_file,
                'file_size' => filesize($zip_file),
                'backup_type' => $backup_type,
            );
        } catch (Exception $e) {
            $this->cleanup_temp();
            return new WP_Error('backup_failed', $e->getMessage());
        }
    }

    private function export_database()
    {
        global $wpdb;

        $db_file = $this->temp_dir . '/database.sql';
        $db_handle = fopen($db_file, 'w');

        if (!$db_handle) {
            return new WP_Error('db_export_failed', 'Failed to create database export file.');
        }

        // Write SQL header
        fwrite($db_handle, "-- WordPress Database Export\n");
        fwrite($db_handle, "-- Database: " . DB_NAME . "\n");
        fwrite($db_handle, "-- Export Date: " . current_time('mysql') . "\n\n");

        // Get all tables
        $tables = $wpdb->get_results('SHOW TABLES', ARRAY_N);

        if (!$tables) {
            fclose($db_handle);
            return new WP_Error('db_export_failed', 'No tables found in database.');
        }

        foreach ($tables as $table) {
            $table_name = $table[0];

            // Write DROP TABLE statement
            fwrite($db_handle, "\nDROP TABLE IF EXISTS `" . $table_name . "`;\n");

            // Write CREATE TABLE statement
            $create_table = $wpdb->get_results("SHOW CREATE TABLE `" . $table_name . "`", ARRAY_N);
            if ($create_table) {
                fwrite($db_handle, $create_table[0][1] . ";\n");
            }

            // Write INSERT statements
            $rows = $wpdb->get_results("SELECT * FROM `" . $table_name . "`", ARRAY_A);

            if ($rows) {
                foreach ($rows as $row) {
                    $values = array();
                    foreach ($row as $value) {
                        if ($value === null) {
                            $values[] = 'NULL';
                        } else {
                            $values[] = "'" . esc_sql($value) . "'";
                        }
                    }

                    $columns = array_keys($row);
                    $columns_str = '`' . implode('`,`', $columns) . '`';
                    $values_str = implode(',', $values);

                    fwrite($db_handle, "INSERT INTO `" . $table_name . "` (" . $columns_str . ") VALUES (" . $values_str . ");\n");
                }
            }
        }

        fclose($db_handle);

        return $db_file;
    }

    private function create_zip($db_file, $backup_type)
    {
        $zip_file = $this->backup_dir . '/wordpress-backup-' . date('Y-m-d-H-i-s') . '.zip';

        $zip = new ZipArchive();

        if ($zip->open($zip_file, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return new WP_Error('zip_creation_failed', 'Failed to create ZIP archive.');
        }

        // Add database export to ZIP
        if (file_exists($db_file)) {
            $zip->addFile($db_file, 'database.sql');
        }

        // Add WordPress core files and directories
        if ('full' === $backup_type || 'files' === $backup_type) {
            $this->add_directory_to_zip($zip, ABSPATH, 'wordpress');
        }

        // Add wp-content directory
        if ('full' === $backup_type || 'files' === $backup_type) {
            $wp_content = WP_CONTENT_DIR;
            $this->add_directory_to_zip($zip, $wp_content, 'wp-content');
        }

        // Add wp-config.php if not in ABSPATH
        if (file_exists(dirname(ABSPATH) . '/wp-config.php')) {
            $zip->addFile(dirname(ABSPATH) . '/wp-config.php', 'wp-config.php');
        }

        if ($zip->close() !== true) {
            return new WP_Error('zip_close_failed', 'Failed to close ZIP archive.');
        }

        return $zip_file;
    }

    private function add_directory_to_zip(&$zip, $dir, $zip_path = '')
    {
        $files = new RecursiveDirectoryIterator($dir);
        $files = new RecursiveIteratorIterator($files);

        $exclude_dirs = array('.git', '.svn', '.hg', 'wp-gd-backups', 'cache', 'node_modules');

        foreach ($files as $file) {
            // Skip excluded directories
            $skip = false;
            foreach ($exclude_dirs as $exclude) {
                if (strpos($file->getRealPath(), DIRECTORY_SEPARATOR . $exclude . DIRECTORY_SEPARATOR) !== false) {
                    $skip = true;
                    break;
                }
            }

            if ($skip) {
                continue;
            }

            if ($file->isFile()) {
                $file_path = $file->getRealPath();
                $relative_path = $zip_path . DIRECTORY_SEPARATOR . substr($file_path, strlen($dir) + 1);
                $relative_path = str_replace(DIRECTORY_SEPARATOR, '/', $relative_path);

                $zip->addFile($file_path, $relative_path);
            }
        }
    }

    private function cleanup_temp()
    {
        if (is_dir($this->temp_dir)) {
            $this->remove_directory($this->temp_dir);
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

    public function cleanup_old_backups($retention_days = 30)
    {
        if (!is_dir($this->backup_dir)) {
            return;
        }

        $files = scandir($this->backup_dir);

        foreach ($files as $file) {
            if ('.' === $file || '..' === $file) {
                continue;
            }

            $file_path = $this->backup_dir . '/' . $file;
            $file_time = filemtime($file_path);
            $current_time = time();
            $age_in_seconds = $current_time - $file_time;
            $age_in_days = $age_in_seconds / (60 * 60 * 24);

            if ($age_in_days > $retention_days) {
                unlink($file_path);
            }
        }
    }

    public function get_backup_dir()
    {
        return $this->backup_dir;
    }
}
