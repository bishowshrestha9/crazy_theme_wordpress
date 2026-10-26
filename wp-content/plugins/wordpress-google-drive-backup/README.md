# WordPress Google Drive Backup

A professional WordPress backup plugin that securely backs up your WordPress site (files + database) to Google Drive with OAuth 2.0 authentication, automated scheduling, and one-click restore functionality.

## Features

✅ **Google Drive Integration**
- OAuth 2.0 authentication (no plaintext credentials)
- Secure token storage and auto-refresh
- Automatic folder creation on Google Drive

✅ **Backup Types**
- Full Backup (files + database)
- Database Only
- Files Only

✅ **Automated Scheduling**
- Hourly, Daily, Weekly, Monthly schedules
- WP-Cron integration
- Backup history tracking
- Retention policy (auto-cleanup old backups)

✅ **File Handling**
- ZIP compression with excluded directories (.git, cache, node_modules, etc.)
- Support for large files (resumable uploads via chunking)
- Multipart uploads for optimal performance

✅ **Database Export**
- SQL dump with DROP TABLE statements
- Safe, prepared SQL statements
- Preserved database structure and data

✅ **Restore Functionality**
- Download backups from Google Drive
- Restore database from SQL dump
- Extract and restore files (optional)
- One-click restore with confirmation

✅ **Admin Dashboard**
- Dashboard with backup statistics
- Backup history table
- Google Drive file listing
- Manual backup trigger
- Restore actions per backup

✅ **Security**
- WordPress nonces for all actions
- Capability checks (manage_options)
- Input sanitization and validation
- Escaped output (esc_html, esc_attr, esc_url)
- Safe database operations (wpdb->prepare)

## Installation

### Prerequisites
- WordPress 5.9+
- PHP 8.0+
- Google Cloud Project with Drive API enabled
- OAuth 2.0 credentials

### Setup Steps

1. **Upload plugin to `/wp-content/plugins/`:**
   ```bash
   git clone https://github.com/yourusername/wordpress-google-drive-backup.git wp-content/plugins/wordpress-google-drive-backup
   ```

2. **Activate plugin in WordPress admin**

3. **Configure Google OAuth:**
   - Go to `GD Backup > Settings`
   - Enter your Google OAuth Client ID
   - Enter your Google OAuth Client Secret
   - Save settings

4. **Connect Google Drive:**
   - Go to `GD Backup` main page
   - Click "Connect Google Drive"
   - Authorize the plugin to access your Google Drive
   - You'll be redirected back to confirm connection

5. **Configure Backup Settings:**
   - Choose backup type (Full, Database, or Files)
   - Select backup frequency (Hourly, Daily, Weekly, Monthly)
   - Set retention period in days
   - Save settings

## Google Cloud Setup

### Create a Google Cloud Project

1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Create a new project
3. Enable Google Drive API:
   - Search for "Google Drive API"
   - Click "Enable"
4. Create OAuth 2.0 Credentials:
   - Go to Credentials
   - Click "Create Credentials" → "OAuth 2.0 Client ID"
   - Choose "Web application"
   - Add authorized redirect URI: `YOUR_SITE/wp-admin/admin.php?page=wp-gd-backup&oauth_callback=1`
   - Copy Client ID and Client Secret

## Usage

### Dashboard
- **Status:** Shows connection status to Google Drive
- **Quick Actions:** Manual backup trigger
- **Statistics:** Shows total, successful, and failed backups

### Backup History
- View all local backups with status, size, and notes
- See Google Drive files
- Restore individual backups

### Settings
- Configure OAuth credentials
- Choose backup type
- Select backup frequency
- Set retention period

### Manual Backups
1. Click "Backup Now" button
2. Wait for completion
3. Check backup history for status

### Restore from Backup
1. Go to `GD Backup > Backup History`
2. Click "Restore" on desired backup
3. Confirm the restore action
4. Wait for completion (database only by default)
5. Plugin will optionally restore files if configured

## Database Schema

### Backup History Table (`wp_gd_backup_history`)

```sql
CREATE TABLE wp_gd_backup_history (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    backup_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
    backup_size bigint(20) DEFAULT 0,
    file_name varchar(255) NOT NULL,
    file_id varchar(255),
    status varchar(50) DEFAULT 'pending',
    backup_type varchar(50) DEFAULT 'full',
    notes text,
    PRIMARY KEY (id),
    KEY backup_date (backup_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## File Structure

```
wordpress-google-drive-backup/
├── wordpress-google-drive-backup.php    # Main plugin file
├── includes/
│   ├── class-oauth-handler.php          # OAuth 2.0 authentication
│   ├── class-backup-generator.php       # ZIP creation & database export
│   ├── class-google-drive-uploader.php  # Google Drive API integration
│   ├── class-scheduler.php              # WP-Cron scheduling
│   ├── class-restore-handler.php        # Backup restoration
│   └── class-admin-page.php             # Admin interface
└── README.md                            # This file
```

## Class Architecture

### WP_GD_OAuth_Handler
Manages Google OAuth 2.0 authentication.

**Key Methods:**
- `get_auth_url()` - Generate OAuth login URL
- `handle_oauth_callback()` - Process OAuth callback
- `get_access_token()` - Get valid access token (auto-refreshes)
- `is_authenticated()` - Check if connected
- `disconnect()` - Revoke connection

### WP_GD_Backup_Generator
Creates backup files (ZIP + SQL dump).

**Key Methods:**
- `generate_backup($type)` - Create backup file
- `export_database()` - Export MySQL database
- `create_zip($db_file, $type)` - Create ZIP archive
- `cleanup_old_backups($days)` - Delete old backups

### WP_GD_Drive_Uploader
Handles Google Drive API interactions.

**Key Methods:**
- `upload_backup($file, $metadata)` - Upload backup to Google Drive
- `simple_upload()` - Multipart upload for small files
- `resumable_upload()` - Chunked upload for large files

### WP_GD_Scheduler
Manages backup scheduling and execution.

**Key Methods:**
- `schedule_backup($frequency)` - Schedule automatic backups
- `run_scheduled_backup()` - Execute scheduled backup
- `perform_backup($type)` - Generate, upload, and record backup
- `get_backup_statistics()` - Return backup stats

### WP_GD_Restore_Handler
Handles backup restoration.

**Key Methods:**
- `list_backups()` - List backups from Google Drive
- `download_backup($file_id)` - Download backup file
- `extract_backup($file)` - Extract ZIP archive
- `restore_database($dir)` - Restore database from SQL
- `restore_files($dir)` - Restore WordPress files
- `perform_full_restore($file_id)` - One-click restore

### WP_GD_Admin_Page
Renders admin interface and handles AJAX.

**Key Methods:**
- `render_admin_page()` - Main dashboard
- `render_backup_history()` - History & restore interface
- `render_settings()` - Settings page
- `ajax_backup_now()` - AJAX manual backup
- `ajax_restore_backup()` - AJAX restore backup
- `handle_disconnect()` - Secure disconnect action

## Security Considerations

1. **OAuth Credentials**
   - Never share Client ID/Secret
   - Rotate credentials regularly
   - Use OAuth instead of API keys

2. **Token Storage**
   - Tokens stored in WordPress options (wp_gd_backup_oauth_token)
   - Consider encrypting sensitive options in production

3. **Access Control**
   - Only admins can manage backups
   - Capability checks on all admin pages
   - AJAX actions include nonce validation

4. **Database Operations**
   - All queries use wpdb->prepare()
   - Input sanitized with sanitize_text_field()
   - Output escaped with esc_html(), esc_attr(), etc.

5. **File Operations**
   - Excluded directories: .git, .svn, .hg, cache, node_modules
   - Safe file permissions preserved
   - Temporary files cleaned up after backup

## Troubleshooting

### OAuth Connection Issues
- Verify Client ID and Secret are correct
- Check redirect URI matches exactly: `YOUR_SITE/wp-admin/admin.php?page=wp-gd-backup&oauth_callback=1`
- Ensure Google Drive API is enabled in Cloud Console
- Check OAuth Consent Screen is configured

### Backup Failures
- Check WordPress disk space
- Verify Google Drive storage quota
- Review error logs in `/wp-content/wp-gd-backup.log`
- Check PHP memory limit (increase if needed)

### Large Backups
- Use Database Only backup type first
- Configure chunked uploads for files > 256MB
- Increase PHP timeout: `set_time_limit(0)` in wp-config.php

### Restore Issues
- Database-only restore is safe (files optional)
- Backup files must be valid ZIP archives
- Database.sql must contain valid SQL statements
- Check file permissions on WordPress directories

## Support

For issues, questions, or contributions:
- [GitHub Issues](https://github.com/yourusername/wordpress-google-drive-backup/issues)
- [Email Support](mailto:support@example.com)

## License

GNU General Public License v2 or later. See LICENSE file for details.

## Credits

Built with ❤️ by Bishow Shrestha

---

**Last Updated:** June 2026  
**Version:** 1.0.0
