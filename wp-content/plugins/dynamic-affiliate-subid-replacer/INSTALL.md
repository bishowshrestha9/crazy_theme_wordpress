# Installation Instructions

## Method 1: WordPress Admin Upload (Recommended)

1. **Download the Plugin**
   - Download or create a ZIP file of the `dynamic-affiliate-subid-replacer` folder

2. **Upload via WordPress Admin**
   - Log in to your WordPress admin dashboard
   - Navigate to **Plugins → Add New**
   - Click **Upload Plugin** button at the top
   - Click **Choose File** and select the ZIP file
   - Click **Install Now**

3. **Activate**
   - Click **Activate Plugin** after installation completes

4. **Configure**
   - Go to **Settings → Affiliate SubID**
   - Enable the plugin
   - Configure your settings (see below)

---

## Method 2: Manual FTP/SFTP Upload

1. **Upload Files**
   - Upload the `dynamic-affiliate-subid-replacer` folder to:
     ```
     /wp-content/plugins/
     ```
   - Ensure the folder structure is:
     ```
     /wp-content/plugins/dynamic-affiliate-subid-replacer/
         ├── dynamic-affiliate-subid-replacer.php
         ├── assets/
         │   └── js/
         │       └── rewrite.js
         └── [other files]
     ```

2. **Activate**
   - Log in to WordPress admin
   - Go to **Plugins**
   - Find "Dynamic Affiliate SubID Replacer"
   - Click **Activate**

3. **Configure**
   - Go to **Settings → Affiliate SubID**

---

## Method 3: WP-CLI

If you have WP-CLI installed:

```bash
# Navigate to WordPress root directory
cd /path/to/wordpress

# Create plugins directory if needed
mkdir -p wp-content/plugins

# Copy plugin folder
cp -r /path/to/dynamic-affiliate-subid-replacer wp-content/plugins/

# Activate plugin
wp plugin activate dynamic-affiliate-subid-replacer

# Check status
wp plugin list | grep dynamic-affiliate
```

---

## Initial Configuration

After activation, configure the plugin:

### 1. Access Settings
- Go to **Settings → Affiliate SubID** in WordPress admin

### 2. Basic Settings

**Enable Plugin**
- ✅ Check to activate automatic SubID replacement
- Leave unchecked to disable without deactivating plugin

**Slot 1 Value (SubID)**
- Leave **empty** to use your site name automatically
- Or enter a **custom value** like "myblog" or "tech-reviews"
- This value replaces: `subid`, `subid1`, `sub1`, `t1`, `aff_sub1`, `source_id`

**Slot 2 Mode (SubID2)**
- **Post/Page Slug** (recommended)
  - Uses the post/page slug: "about-us", "product-review-2024"
  - Homepage shows as "home"
- **Full URL Path**
  - Uses the complete path: "category-tech-reviews"
  - For complex URL structures

### 3. Advanced Settings (Optional)

**Target Specific CSS Class**
- ✅ Enable to only rewrite links with a specific CSS class
- Enter class name (default: `affiliate-link`)
- Useful if you want to manually control which links are tracked

**Target Sponsored Links**
- ✅ Enable to only rewrite links with `rel="sponsored"` or `rel="nofollow"`
- Helps target only affiliate links that are properly marked

### 4. Save Settings
- Click **Save Settings**
- You should see "Settings saved successfully" message

---

## Verify Installation

### 1. Check Plugin is Active
```
WordPress Admin → Plugins
Look for "Dynamic Affiliate SubID Replacer" with blue "Deactivate" link
```

### 2. Check Settings Page Exists
```
WordPress Admin → Settings → Affiliate SubID
Should load the settings page
```

### 3. Check JavaScript is Loading

Visit any page on your site and:
1. Right-click → Inspect (or press F12)
2. Go to **Console** tab
3. Type: `window.DASR`
4. Press Enter
5. Should show: `{rewriteUrl: ƒ, version: "1.0.0"}`

### 4. Test with a Sample Link

Create a test post with this link:
```html
<a href="https://example.com/test?aff_id=123&sub1=test&sub2=test">Test Link</a>
```

1. View the post
2. Open browser console (F12)
3. Click the link
4. Check the URL in address bar - should show your site name and page slug

---

## Troubleshooting Installation

### Plugin Not Showing in Plugins List

**Check:**
- Folder name is exactly: `dynamic-affiliate-subid-replacer`
- Main file exists: `dynamic-affiliate-subid-replacer.php`
- Folder is in: `/wp-content/plugins/`
- File permissions are correct (755 for folders, 644 for files)

**Fix:**
```bash
# Set correct permissions
chmod 755 /path/to/wp-content/plugins/dynamic-affiliate-subid-replacer
chmod 644 /path/to/wp-content/plugins/dynamic-affiliate-subid-replacer/*.php
chmod 644 /path/to/wp-content/plugins/dynamic-affiliate-subid-replacer/assets/js/*.js
```

### "Plugin could not be activated"

**Possible causes:**
1. PHP version too old (need 7.2+)
2. PHP syntax error
3. Plugin conflict

**Check PHP version:**
```
WordPress Admin → Tools → Site Health → Info → Server → PHP version
```

**Check for errors:**
```
WordPress Admin → Tools → Site Health
Look for critical issues
```

### Settings Page Not Showing

**Check:**
- User has "Administrator" role
- Plugin is activated
- No JavaScript errors in console

**Try:**
- Clear browser cache (Ctrl+F5 or Cmd+Shift+R)
- Try different browser
- Disable other plugins temporarily

### JavaScript Not Loading

**Check browser console for:**
- 404 error on `rewrite.js`
- "dasrConfig is not defined"

**Verify file exists:**
```
/wp-content/plugins/dynamic-affiliate-subid-replacer/assets/js/rewrite.js
```

**Clear caches:**
- Browser cache
- WordPress cache (if using W3TC, WP Rocket, etc.)
- CDN cache (if using Cloudflare, etc.)

### Plugin Active But Links Not Rewriting

**Checklist:**
1. ✅ Plugin enabled in Settings → Affiliate SubID
2. ✅ Links are external (different domain)
3. ✅ Links contain tracking parameters (sub1, sub2, etc.)
4. ✅ JavaScript not blocked by ad blocker
5. ✅ No JavaScript errors in console

**Test in incognito/private mode** to rule out extensions.

---

## Creating a ZIP for Distribution

To create a ZIP file for sharing or uploading:

### On macOS/Linux:
```bash
cd /path/to/wp-content/plugins
zip -r dynamic-affiliate-subid-replacer.zip dynamic-affiliate-subid-replacer -x "*.git*" -x "*node_modules*" -x "*.DS_Store"
```

### On Windows:
```powershell
# Using PowerShell
Compress-Archive -Path "dynamic-affiliate-subid-replacer" -DestinationPath "dynamic-affiliate-subid-replacer.zip"
```

### Or manually:
1. Right-click the `dynamic-affiliate-subid-replacer` folder
2. Select "Compress" (macOS) or "Send to → Compressed folder" (Windows)
3. Rename to `dynamic-affiliate-subid-replacer.zip`

---

## Uninstallation

### Remove Plugin

**Method 1: WordPress Admin**
1. Go to **Plugins**
2. Find "Dynamic Affiliate SubID Replacer"
3. Click **Deactivate**
4. Click **Delete**
5. Confirm deletion

**Method 2: Manual**
1. Deactivate in WordPress admin
2. Delete folder: `/wp-content/plugins/dynamic-affiliate-subid-replacer/`

### Remove Settings (Optional)

Plugin settings remain in database after deletion. To remove:

```sql
-- Run in phpMyAdmin or database tool
DELETE FROM wp_options WHERE option_name = 'dasr_settings';
```

Or use WP-CLI:
```bash
wp option delete dasr_settings
```

---

## Next Steps

After installation:

1. **Read the documentation**: See `USAGE.md` for examples
2. **Test with sample links**: Create a test post with affiliate links
3. **Check affiliate dashboard**: Verify SubIDs appear correctly
4. **Customize if needed**: Use filters to adjust behavior (see `readme.txt`)

**Need help?** Check the FAQ in `readme.txt` or review the comprehensive `USAGE.md` guide.
