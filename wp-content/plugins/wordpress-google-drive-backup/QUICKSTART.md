# Quick Start Guide - WordPress Google Drive Backup

## 5-Minute Setup

### Prerequisites
- WordPress 5.9+ installed
- Google account
- Google Cloud project access

### Step 1: Get Google Credentials (2 min)

1. Go to [Google Cloud Console](https://console.cloud.google.com)
2. Create new project → Name it "WordPress Backups"
3. Search for "Google Drive API" → Enable it
4. Go to Credentials → Create OAuth 2.0 Web App credentials
5. Authorized redirect URI: **Replace YOUR_SITE below**
   ```
   http://YOUR_SITE/wp-admin/admin.php?page=wp-gd-backup&oauth_callback=1
   ```
6. Copy **Client ID** and **Client Secret**

### Step 2: Activate Plugin (1 min)

1. WordPress Admin → Plugins → Installed Plugins
2. Find "WordPress Google Drive Backup" → Click Activate

### Step 3: Configure Settings (1 min)

1. Admin Menu → **GD Backup** → **Settings**
2. Paste Google Client ID
3. Paste Google Client Secret
4. Select backup type: **Full Backup** (recommended)
5. Select frequency: **Daily** (recommended)
6. Set retention: **30 days** (recommended)
7. Click **Save Settings**

### Step 4: Connect Google Drive (1 min)

1. Admin Menu → **GD Backup** (main page)
2. Click blue "Connect Google Drive" button
3. Log in with your Google account
4. Click "Allow" to grant permissions
5. Confirm connection ✓

### Step 5: Test Backup (1 min)

1. Click **Backup Now** button
2. Wait 1-5 minutes (depends on site size)
3. See green checkmark ✓
4. View **Backup History** to confirm

## Done! 🎉

Your WordPress site will now backup automatically every day to Google Drive.

---

## Common Tasks

### Manual Backup
- Admin → GD Backup → Click **Backup Now**
- Wait for completion
- Check status message

### View Backup History
- Admin → GD Backup → **Backup History**
- See all backups with dates and sizes
- Click **Restore** to restore any backup

### Change Schedule
- Admin → GD Backup → **Settings**
- Change frequency (Hourly/Daily/Weekly/Monthly)
- Click **Save Settings**

### Restore from Backup
- Admin → GD Backup → **Backup History**
- Find desired backup
- Click **Restore** button
- Confirm (replaces current database)
- Wait for completion

### Disconnect Google Drive
- Admin → GD Backup
- Click **Disconnect** button
- Confirm

---

## Troubleshooting

### "Connect Google Drive" button not working
→ Check Client ID/Secret in Settings (copy/paste exactly)

### Backup takes too long
→ Use "Database Only" backup type in Settings

### "Not Connected" message
→ Click "Connect Google Drive" and complete OAuth flow

### Backups not running automatically
→ Install a WordPress cron runner (e.g., wp-cron.php every 5 min)

### Large backup fails
→ Increase PHP timeout in wp-config.php:
```php
define('WP_CRON_LOCK_TIMEOUT', 300);
```

---

## Important Notes

✓ Backups are encrypted during upload to Google Drive  
✓ Only admins can access backup settings  
✓ Old backups auto-delete after 30 days (configurable)  
✓ Restore is database-only by default (safe)  
✓ First backup may take longer depending on site size  

---

## Need Help?

- Check **Backup History** for backup status
- Look for logs in `/wp-content/wp-gd-backup.log`
- Review full documentation in `README.md`

---

**Start Time:** < 5 minutes  
**Status:** ✅ Ready to backup!
