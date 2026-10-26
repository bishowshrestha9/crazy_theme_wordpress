<?php
/**
 * Admin Page for Google Drive Backup
 */

if (!defined('ABSPATH')) {
    exit;
}

class WP_GD_Admin_Page
{
    private static $instance = null;
    private $oauth_handler;
    private $scheduler;
    private $restore_handler;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->oauth_handler = WP_GD_OAuth_Handler::get_instance();
        $this->scheduler = WP_GD_Scheduler::get_instance();
        $this->restore_handler = WP_GD_Restore_Handler::get_instance();

        add_action('admin_menu', array($this, 'register_admin_menu'));
        add_action('admin_post_wp_gd_backup_settings', array($this, 'save_settings'));
        add_action('admin_post_wp_gd_disconnect', array($this, 'handle_disconnect'));
        add_action('wp_ajax_wp_gd_backup_now', array($this, 'ajax_backup_now'));
        add_action('wp_ajax_wp_gd_restore_backup', array($this, 'ajax_restore_backup'));
    }

    public function register_admin_menu()
    {
        add_menu_page(
            'Google Drive Backup',
            'GD Backup',
            'manage_options',
            'wp-gd-backup',
            array($this, 'render_admin_page'),
            'dashicons-cloud'
        );

        add_submenu_page(
            'wp-gd-backup',
            'Backup History',
            'Backup History',
            'manage_options',
            'wp-gd-backup-history',
            array($this, 'render_backup_history')
        );

        add_submenu_page(
            'wp-gd-backup',
            'Settings',
            'Settings',
            'manage_options',
            'wp-gd-backup-settings',
            array($this, 'render_settings')
        );
    }

    public function render_admin_page()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Access denied.');
        }

        $is_authenticated = $this->oauth_handler->is_authenticated();
        $auth_url = $this->oauth_handler->get_auth_url();
        $stats = $this->scheduler->get_backup_statistics();
        ?>
        <div class="wrap">
            <h1>Google Drive Backup</h1>

            <div class="card">
                <h2>Status</h2>
                <?php if ($is_authenticated): ?>
                    <p style="color: green;">✓ <strong>Connected to Google Drive</strong></p>
                    <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=wp_gd_disconnect'), 'wp_gd_disconnect')); ?>" class="button button-secondary">Disconnect</a>
                <?php else: ?>
                    <p style="color: red;">✗ <strong>Not Connected</strong></p>
                    <?php if (is_wp_error($auth_url)): ?>
                        <p><?php echo esc_html($auth_url->get_error_message()); ?></p>
                    <?php else: ?>
                        <a href="<?php echo esc_url($auth_url); ?>" class="button button-primary">Connect Google Drive</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <?php if ($is_authenticated): ?>
                <div class="card">
                    <h2>Quick Actions</h2>
                    <button class="button button-primary" id="wp-gd-backup-now">Backup Now</button>
                    <span id="wp-gd-backup-status"></span>
                </div>

                <div class="card">
                    <h2>Statistics</h2>
                    <ul>
                        <li><strong>Total Backups:</strong> <?php echo intval($stats['total_backups']); ?></li>
                        <li><strong>Successful:</strong> <?php echo intval($stats['successful_backups']); ?></li>
                        <li><strong>Failed:</strong> <?php echo intval($stats['failed_backups']); ?></li>
                        <li><strong>Total Size:</strong> <?php echo size_format($stats['total_size']); ?></li>
                        <?php if ($stats['last_backup']): ?>
                            <li><strong>Last Backup:</strong> <?php echo esc_html($stats['last_backup']->backup_date); ?> (<?php echo esc_html($stats['last_backup']->status); ?>)</li>
                        <?php endif; ?>
                    </ul>
                </div>

                <script>
                    jQuery(document).ready(function($) {
                        $('#wp-gd-backup-now').on('click', function() {
                            var $button = $(this);
                            $button.prop('disabled', true);
                            $('#wp-gd-backup-status').text('Starting backup...');

                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'wp_gd_backup_now',
                                    nonce: '<?php echo wp_create_nonce('wp_gd_backup_nonce'); ?>'
                                },
                                success: function(response) {
                                    if (response.success) {
                                        $('#wp-gd-backup-status').html('<span style="color: green;">✓ Backup completed successfully!</span>');
                                    } else {
                                        $('#wp-gd-backup-status').html('<span style="color: red;">✗ Backup failed: ' + response.data.message + '</span>');
                                    }
                                    setTimeout(function() {
                                        location.reload();
                                    }, 3000);
                                },
                                error: function() {
                                    $('#wp-gd-backup-status').html('<span style="color: red;">✗ Error communicating with server</span>');
                                    $button.prop('disabled', false);
                                }
                            });
                        });
                    });
                </script>
            <?php endif; ?>
        </div>
        <?php
    }

    public function render_backup_history()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Access denied.');
        }

        global $wpdb;

        $table_name = $wpdb->prefix . 'gd_backup_history';
        $backups = $wpdb->get_results("SELECT * FROM {$table_name} ORDER BY backup_date DESC");

        $drive_files = $this->restore_handler->list_backups();
        ?>
        <div class="wrap">
            <h1>Backup History</h1>

            <table class="wp-list-table widefat striped">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Size</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($backups): ?>
                        <?php foreach ($backups as $backup): ?>
                            <tr>
                                <td><?php echo esc_html($backup->backup_date); ?></td>
                                <td><?php echo esc_html($backup->backup_type); ?></td>
                                <td><span style="color: <?php echo 'success' === $backup->status ? 'green' : 'red'; ?>;"><?php echo esc_html(ucfirst($backup->status)); ?></span></td>
                                <td><?php echo esc_html(size_format($backup->backup_size)); ?></td>
                                <td><?php echo esc_html($backup->notes); ?></td>
                                <td>
                                    <?php if ('success' === $backup->status): ?>
                                        <button class="button button-small wp-gd-restore-backup" data-file-id="<?php echo esc_attr($backup->file_id); ?>">Restore</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No backups found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <h2>Google Drive Files</h2>
            <table class="wp-list-table widefat striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Size</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!is_wp_error($drive_files) && is_array($drive_files)): ?>
                        <?php foreach ($drive_files as $file): ?>
                            <tr>
                                <td><?php echo esc_html($file['name']); ?></td>
                                <td><?php echo esc_html(size_format($file['size'])); ?></td>
                                <td><?php echo esc_html(substr($file['createdTime'], 0, 10)); ?></td>
                                <td>
                                    <button class="button button-small wp-gd-restore-backup" data-file-id="<?php echo esc_attr($file['id']); ?>">Restore</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No files found in Google Drive backup folder.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <script>
                jQuery(document).ready(function($) {
                    $('.wp-gd-restore-backup').on('click', function() {
                        var fileId = $(this).data('file-id');
                        if (confirm('Restore from this backup? This will replace your current data.')) {
                            $.ajax({
                                url: ajaxurl,
                                type: 'POST',
                                data: {
                                    action: 'wp_gd_restore_backup',
                                    file_id: fileId,
                                    nonce: '<?php echo wp_create_nonce('wp_gd_restore_nonce'); ?>'
                                },
                                success: function(response) {
                                    if (response.success) {
                                        alert('Restore completed. Redirecting...');
                                        location.reload();
                                    } else {
                                        alert('Restore failed: ' + response.data.message);
                                    }
                                }
                            });
                        }
                    });
                });
            </script>
        </div>
        <?php
    }

    public function render_settings()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Access denied.');
        }

        $client_id = get_option('wp_gd_backup_client_id');
        $client_secret = get_option('wp_gd_backup_client_secret');
        $backup_type = get_option('wp_gd_backup_type', 'full');
        $frequency = $this->scheduler->get_schedule_frequency();
        $retention_days = get_option('wp_gd_backup_retention_days', 30);
        $next_scheduled = $this->scheduler->get_next_scheduled_time();
        ?>
        <div class="wrap">
            <h1>Google Drive Backup Settings</h1>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <?php wp_nonce_field('wp_gd_backup_settings_nonce'); ?>
                <input type="hidden" name="action" value="wp_gd_backup_settings">

                <table class="form-table">
                    <tr>
                        <th><label for="client_id">Google OAuth Client ID</label></th>
                        <td><input type="text" id="client_id" name="client_id" value="<?php echo esc_attr($client_id); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="client_secret">Google OAuth Client Secret</label></th>
                        <td><input type="password" id="client_secret" name="client_secret" value="<?php echo esc_attr($client_secret); ?>" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="backup_type">Backup Type</label></th>
                        <td>
                            <select id="backup_type" name="backup_type">
                                <option value="full" <?php selected($backup_type, 'full'); ?>>Full Backup</option>
                                <option value="database" <?php selected($backup_type, 'database'); ?>>Database Only</option>
                                <option value="files" <?php selected($backup_type, 'files'); ?>>Files Only</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="frequency">Backup Frequency</label></th>
                        <td>
                            <select id="frequency" name="frequency">
                                <option value="hourly" <?php selected($frequency, 'hourly'); ?>>Hourly</option>
                                <option value="daily" <?php selected($frequency, 'daily'); ?>>Daily</option>
                                <option value="weekly" <?php selected($frequency, 'weekly'); ?>>Weekly</option>
                                <option value="monthly" <?php selected($frequency, 'monthly'); ?>>Monthly</option>
                            </select>
                            <?php if ($next_scheduled): ?>
                                <p class="description">Next scheduled backup: <?php echo esc_html(date('Y-m-d H:i:s', $next_scheduled)); ?></p>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="retention_days">Retention Period (days)</label></th>
                        <td><input type="number" id="retention_days" name="retention_days" value="<?php echo esc_attr($retention_days); ?>" min="1"></td>
                    </tr>
                </table>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    public function save_settings()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Access denied.');
        }

        check_admin_referer('wp_gd_backup_settings_nonce');

        $client_id = sanitize_text_field($_POST['client_id'] ?? '');
        $client_secret = sanitize_text_field($_POST['client_secret'] ?? '');
        $backup_type = sanitize_key($_POST['backup_type'] ?? 'full');
        $frequency = sanitize_key($_POST['frequency'] ?? 'daily');
        $retention_days = intval($_POST['retention_days'] ?? 30);

        $allowed_backup_types = array('full', 'database', 'files');
        $allowed_frequencies = array('hourly', 'daily', 'weekly', 'monthly');

        if (!in_array($backup_type, $allowed_backup_types, true)) {
            $backup_type = 'full';
        }

        if (!in_array($frequency, $allowed_frequencies, true)) {
            $frequency = 'daily';
        }

        update_option('wp_gd_backup_client_id', $client_id);
        if ('' !== $client_secret) {
            update_option('wp_gd_backup_client_secret', $client_secret, false);
        }
        update_option('wp_gd_backup_type', $backup_type);
        update_option('wp_gd_backup_retention_days', max(1, $retention_days));

        $schedule_result = $this->scheduler->schedule_backup($frequency);
        if (is_wp_error($schedule_result)) {
            wp_safe_redirect(admin_url('admin.php?page=wp-gd-backup-settings&settings-updated=0&error=' . rawurlencode($schedule_result->get_error_message())));
            exit;
        }

        wp_safe_redirect(admin_url('admin.php?page=wp-gd-backup-settings&settings-updated=1'));
        exit;
    }

    public function ajax_backup_now()
    {
        check_ajax_referer('wp_gd_backup_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Access denied.'));
        }

        $rate_limit_key = 'wp_gd_backup_lock_' . get_current_user_id();
        if (get_transient($rate_limit_key)) {
            wp_send_json_error(array('message' => 'Backup is already running. Please wait.'));
        }

        set_transient($rate_limit_key, 1, 60);
        $scheduler = WP_GD_Scheduler::get_instance();

        $backup_type = sanitize_key(get_option('wp_gd_backup_type', 'full'));
        $result = $scheduler->perform_backup($backup_type);
        delete_transient($rate_limit_key);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success($result);
    }

    public function ajax_restore_backup()
    {
        check_ajax_referer('wp_gd_restore_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Access denied.'));
        }

        $file_id = sanitize_text_field($_POST['file_id'] ?? '');

        if ('' === $file_id || !preg_match('/^[a-zA-Z0-9_-]+$/', $file_id)) {
            wp_send_json_error(array('message' => 'Invalid backup ID.'));
        }

        $rate_limit_key = 'wp_gd_restore_lock_' . get_current_user_id();
        if (get_transient($rate_limit_key)) {
            wp_send_json_error(array('message' => 'Restore is already running. Please wait.'));
        }

        set_transient($rate_limit_key, 1, 120);
        $result = $this->restore_handler->perform_full_restore($file_id);
        delete_transient($rate_limit_key);

        if (is_wp_error($result)) {
            wp_send_json_error(array('message' => $result->get_error_message()));
        }

        wp_send_json_success($result);
    }

    public function handle_disconnect()
    {
        if (!current_user_can('manage_options')) {
            wp_die('Access denied.');
        }

        check_admin_referer('wp_gd_disconnect');
        $this->oauth_handler->disconnect();

        wp_safe_redirect(admin_url('admin.php?page=wp-gd-backup&disconnected=1'));
        exit;
    }
}
