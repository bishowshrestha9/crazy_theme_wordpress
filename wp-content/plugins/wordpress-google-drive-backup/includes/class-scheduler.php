<?php
/**
 * Scheduler for Google Drive Backup
 */

if (!defined('ABSPATH')) {
    exit;
}

class WP_GD_Scheduler
{
    private static $instance = null;
    private $hook = 'wp_gd_backup_scheduled_event';

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action($this->hook, array($this, 'run_scheduled_backup'));
    }

    public function schedule_backup($frequency = 'daily')
    {
        // Clear existing schedule
        $this->unschedule_backup();

        $recurrence = $this->get_recurrence($frequency);

        if (!$recurrence) {
            return new WP_Error('invalid_frequency', 'Invalid backup frequency.');
        }

        $scheduled = wp_schedule_event(time(), $recurrence, $this->hook);

        if ($scheduled) {
            update_option('wp_gd_backup_schedule_frequency', $frequency);
            return true;
        }

        return new WP_Error('schedule_failed', 'Failed to schedule backup.');
    }

    private function get_recurrence($frequency)
    {
        $allowed = array('hourly', 'daily', 'weekly', 'monthly');

        if (!in_array($frequency, $allowed, true)) {
            return false;
        }

        if ('monthly' === $frequency) {
            add_filter('cron_schedules', array($this, 'add_monthly_schedule'));
            return 'monthly';
        }

        return $frequency;
    }

    public function add_monthly_schedule($schedules)
    {
        if (!isset($schedules['monthly'])) {
            $schedules['monthly'] = array(
                'interval' => 30 * DAY_IN_SECONDS,
                'display' => __('Once Monthly', 'wp-gd-backup'),
            );
        }

        return $schedules;
    }

    public function unschedule_backup()
    {
        wp_clear_scheduled_hook($this->hook);
        delete_option('wp_gd_backup_schedule_frequency');
    }

    public function get_schedule_frequency()
    {
        return get_option('wp_gd_backup_schedule_frequency', 'daily');
    }

    public function get_next_scheduled_time()
    {
        return wp_next_scheduled($this->hook);
    }

    public function run_scheduled_backup()
    {
        $backup_type = get_option('wp_gd_backup_type', 'full');
        $this->perform_backup($backup_type);
    }

    public function perform_backup($backup_type = 'full')
    {
        global $wpdb;

        $allowed_backup_types = array('full', 'database', 'files');
        if (!in_array($backup_type, $allowed_backup_types, true)) {
            return new WP_Error('invalid_backup_type', 'Invalid backup type requested.');
        }

        $backup_generator = WP_GD_Backup_Generator::get_instance();
        $uploader = WP_GD_Drive_Uploader::get_instance();

        $status = 'completed';
        $notes = '';

        try {
            // Step 1: Generate backup file
            $backup_result = $backup_generator->generate_backup($backup_type);
            if (is_wp_error($backup_result)) {
                throw new Exception($backup_result->get_error_message());
            }

            $backup_file = $backup_result['file_path'];
            $backup_size = $backup_result['file_size'];

            // Step 2: Upload to Google Drive
            $upload_result = $uploader->upload_backup($backup_file, array(
                'description' => 'WordPress backup - ' . $backup_type . ' - ' . current_time('mysql'),
            ));

            if (is_wp_error($upload_result)) {
                throw new Exception($upload_result->get_error_message());
            }

            $file_id = $upload_result['file_id'];
            $file_name = $upload_result['file_name'];

            // Step 3: Record backup in history
            $record_result = $this->record_backup($file_id, $file_name, $backup_size, $backup_type, 'success');

            if (is_wp_error($record_result)) {
                throw new Exception($record_result->get_error_message());
            }

            $notes = 'Backup successfully uploaded to Google Drive.';

            // Step 4: Clean up local backup file
            if (file_exists($backup_file)) {
                unlink($backup_file);
            }

            // Step 5: Clean up old backups
            $retention_days = intval(get_option('wp_gd_backup_retention_days', 30));
            $backup_generator->cleanup_old_backups($retention_days);

            // Log backup success
            $this->log_backup('success', $notes, $backup_type);

        } catch (Exception $e) {
            $status = 'failed';
            $notes = $e->getMessage();

            // Record failed backup
            $this->record_backup('', '', 0, $backup_type, 'failed', $notes);

            // Log backup failure
            $this->log_backup('failed', $notes, $backup_type);

            return new WP_Error('backup_failed', $notes);
        }

        return array(
            'success' => true,
            'status' => $status,
            'notes' => $notes,
        );
    }

    private function record_backup($file_id, $file_name, $backup_size, $backup_type, $status, $notes = '')
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'gd_backup_history';

        $result = $wpdb->insert($table_name, array(
            'backup_date' => current_time('mysql'),
            'backup_size' => $backup_size,
            'file_name' => sanitize_text_field($file_name),
            'file_id' => sanitize_text_field($file_id),
            'status' => sanitize_text_field($status),
            'backup_type' => sanitize_text_field($backup_type),
            'notes' => sanitize_textarea_field($notes),
        ), array('%s', '%d', '%s', '%s', '%s', '%s', '%s'));

        if ($result === false) {
            return new WP_Error('db_insert_failed', 'Failed to record backup in history.');
        }

        return true;
    }

    private function log_backup($status, $message, $backup_type)
    {
        $log_message = sprintf(
            '[%s] Backup (%s): %s',
            strtoupper($status),
            $backup_type,
            $message
        );

        error_log($log_message, 3, WP_CONTENT_DIR . '/wp-gd-backup.log');
    }

    public function get_backup_statistics()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'gd_backup_history';

        $stats = array(
            'total_backups' => 0,
            'successful_backups' => 0,
            'failed_backups' => 0,
            'total_size' => 0,
            'last_backup' => null,
        );

        // Total backups
        $stats['total_backups'] = intval($wpdb->get_var("SELECT COUNT(*) FROM {$table_name}"));

        // Successful backups
        $stats['successful_backups'] = intval($wpdb->get_var(
            $wpdb->prepare("SELECT COUNT(*) FROM {$table_name} WHERE status = %s", 'success')
        ));

        // Failed backups
        $stats['failed_backups'] = intval($wpdb->get_var(
            $wpdb->prepare("SELECT COUNT(*) FROM {$table_name} WHERE status = %s", 'failed')
        ));

        // Total size
        $total_size_result = $wpdb->get_var("SELECT SUM(backup_size) FROM {$table_name} WHERE status = 'success'");
        $stats['total_size'] = intval($total_size_result ?? 0);

        // Last backup
        $last_backup = $wpdb->get_row("SELECT * FROM {$table_name} ORDER BY backup_date DESC LIMIT 1");
        $stats['last_backup'] = $last_backup;

        return $stats;
    }
}
