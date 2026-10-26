# URL Scanner Tool Guide

## 🔍 What is the URL Scanner?

The URL Scanner is a diagnostic tool built into the Dynamic Affiliate SubID Replacer plugin that scans all your published posts to find affiliate URLs with syntax errors that prevent proper tracking.

---

## How to Access

**WordPress Admin → Tools → URL Scanner**

Or click the **"URL Scanner"** link from the plugin's action links on the Plugins page.

---

## What It Scans For

The scanner detects common URL syntax errors, specifically:

### ❌ Wrong Syntax
```
?param1=value?param2=value
```
Uses `?` between parameters (incorrect)

### ✅ Correct Syntax
```
?param1=value&param2=value
```
Uses `&` between parameters (correct)

---

## How to Use

### Step 1: Run the Scanner

1. Go to **Tools → URL Scanner**
2. Click the **"🔍 Scan All Posts for URL Errors"** button
3. Wait for the scan to complete (may take a few seconds for large sites)

### Step 2: Review Results

The scanner will show:

- **Total posts scanned**
- **Number of posts with errors**
- **List of affected posts** with:
  - Post title and date
  - Post type (post, page, etc.)
  - Exact URLs with errors
  - Suggested fixes
  - Edit link to fix the post

### Step 3: Fix the Errors

For each post with errors:

1. Click the **"✏️ Edit Post"** button
2. Use Find & Replace (Ctrl+F or Cmd+F) in your editor
3. Find the wrong URL
4. Replace `?` with `&` between parameters
5. Update the post

---

## Example Results

### If No Errors Found

```
✅ Scan Complete
Scanned 156 posts. Found 0 posts with URL syntax errors.

🎉 Great! No URL syntax errors found.
All your affiliate URLs are properly formatted and will work correctly with the plugin.
```

### If Errors Found

```
⚠️ Action Required:
The following posts contain URLs with syntax errors. Fix them to ensure proper tracking.

Posts with URL Errors:

📄 "Best Health Supplements 2026"
   (Post - Feb 01, 2026)
   [✏️ Edit Post]

   URLs with Syntax Errors:
   
   ❌ Wrong:
   https://tl-track.com/tracker/vWtB?subid=dhs1?subid=dhs2
   
   ✅ Should be:
   https://tl-track.com/tracker/vWtB?subid=dhs1&subid2=dhs2
```

---

## Common URL Errors Detected

### Error 1: Double Question Marks

**Your URL:**
```
https://tl-track.com/tracker/vWtB?subid=dhs1?subid=dhs2
```

**Problem:** Uses `?` twice

**Fix:**
```
https://tl-track.com/tracker/vWtB?subid=dhs1&subid2=dhs2
```

**Also note:** Changed second `subid` to `subid2` for clarity.

### Error 2: Duplicate Parameter Names

**Your URL:**
```
https://example.com/track?subid=test?subid=test2
```

**Problem:** Same parameter name twice + wrong separator

**Fix:**
```
https://example.com/track?subid=test&subid2=test2
```

---

## Fixing Multiple Posts

### Option 1: Manual Fix (Safest)

Fix each post individually:
1. Click "Edit Post" from scanner results
2. Find and replace the wrong URL
3. Update the post

**Pros:** Safe, you control each change  
**Cons:** Time-consuming for many posts

### Option 2: Search & Replace Plugin

Use a WordPress plugin:

**Better Search Replace:**
1. Install from WordPress.org
2. Go to Tools → Better Search Replace
3. Search for: `?subid=dhs1?subid=dhs2`
4. Replace with: `?subid=dhs1&subid2=dhs2`
5. Select tables: `wp_posts`
6. Run as "Dry Run" first to preview
7. If looks good, uncheck "Dry Run" and run

**Pros:** Fast bulk fix  
**Cons:** Need to install plugin

### Option 3: Database SQL (Advanced)

**⚠️ BACKUP YOUR DATABASE FIRST!**

Run in phpMyAdmin:

```sql
-- Fix TerraLeads URLs
UPDATE wp_posts 
SET post_content = REPLACE(
    post_content, 
    '?subid=dhs1?subid=dhs2', 
    '?subid=dhs1&subid2=dhs2'
)
WHERE post_content LIKE '%tl-track.com%';

-- Fix MaxWeb URLs
UPDATE wp_posts 
SET post_content = REPLACE(
    post_content, 
    '?subid1=hsb1?subid2=hsb2', 
    '?subid1=hsb1&subid2=hsb2'
)
WHERE post_content LIKE '%mwebtrackerhq.com%';

-- Verify changes
SELECT ID, post_title, post_content 
FROM wp_posts 
WHERE post_content LIKE '%tl-track.com%' 
OR post_content LIKE '%mwebtrackerhq.com%';
```

**Pros:** Instant bulk fix  
**Cons:** Risky if not backed up, requires database access

---

## Understanding the Results

### Post Information

Each result shows:
- **Post Title** - The name of the post
- **Post Type** - post, page, custom post type
- **Post Date** - When it was published
- **Edit Link** - Quick access to edit

### URL Display

- **❌ Wrong** - Shows the current incorrect URL
- **✅ Should be** - Shows the suggested fixed URL
- **How to Fix** - Step-by-step instructions

---

## After Fixing URLs

### 1. Re-scan to Verify

After fixing URLs:
1. Go back to **Tools → URL Scanner**
2. Click **"🔍 Scan All Posts"** again
3. Verify no errors remain

### 2. Test the Plugin

1. Visit a post with fixed URLs
2. Click an affiliate link
3. Check that parameters are replaced correctly

### 3. Monitor Affiliate Dashboards

- Wait 24-48 hours for tracking data
- Check your affiliate dashboards
- Verify SubIDs show correctly

---

## Troubleshooting

### Scanner Shows "0 posts scanned"

**Possible causes:**
- No published posts yet
- Posts don't contain any URLs

**Solution:**
- Publish some posts with affiliate links
- Run scanner again

### Scanner Runs Slowly

**Cause:** Large site with many posts

**Solution:**
- Be patient, it may take 30-60 seconds
- Consider scanning in batches (manual SQL)

### False Positives

**Cause:** Scanner detects valid URLs that happen to contain `?` in parameter values

**Solution:**
- Review each result manually
- Ignore URLs that are actually correct
- Only fix genuine syntax errors

### Can't Fix URLs in Block Editor

**Cause:** URL might be in a block setting, not visible content

**Solution:**
1. Switch to Code Editor (⋮ → Code Editor)
2. Find the URL in HTML
3. Fix it there
4. Switch back to Visual Editor

---

## Best Practices

### 1. Run Scanner Before Activating Plugin

Check for errors before enabling tracking:
```
1. Install plugin
2. Run URL Scanner
3. Fix any errors found
4. Enable plugin in settings
5. Test on a few posts
```

### 2. Regular Maintenance

Run the scanner periodically:
- After importing old content
- After migrating from another site
- Once a month as maintenance
- Before major campaigns

### 3. Document Your Patterns

Keep a list of your affiliate networks' parameters:
```
TerraLeads: subid / subid2
MaxWeb: subid1 / subid2
ClickBank: aff_sub1 / aff_sub2
etc.
```

This helps identify errors faster.

### 4. Fix Errors Before Publishing

For new posts:
- Double-check affiliate URLs before publishing
- Use `&` between parameters, not `?`
- Use different parameter names (subid vs subid2)

---

## Scanner Limitations

### What It CAN Detect

✅ Double question marks (`?...?...`)  
✅ URLs with obvious syntax errors  
✅ Common affiliate tracking patterns  

### What It CANNOT Detect

❌ URLs with correct syntax but wrong parameter names  
❌ Broken affiliate links (404 errors)  
❌ Expired affiliate programs  
❌ Missing parameters  
❌ URLs in JavaScript code  
❌ URLs in CSS files  
❌ URLs in widgets (unless in post content)  

### Scanner Only Checks Post Content

The scanner only searches:
- Post content (post_content field)
- Published posts

It does NOT check:
- Draft posts
- Widgets
- Theme files
- Plugin files
- Custom fields
- Comments

---

## FAQ

**Q: Will the scanner fix URLs automatically?**  
A: No, for safety reasons. You must manually fix or use bulk replace tools.

**Q: Does scanning affect my site performance?**  
A: No, scanning only happens when you click the button. It doesn't run automatically.

**Q: Will visitors see these errors?**  
A: Yes, if URLs have syntax errors, they may not work correctly. Fix them ASAP.

**Q: Can I download the scan results?**  
A: Currently no, but you can copy/paste from the page or take screenshots.

**Q: How often should I scan?**  
A: After initial setup, then whenever you import old content or notice tracking issues.

**Q: What if I accidentally break more URLs while fixing?**  
A: Always backup your database before making bulk changes. Use "Better Search Replace" with dry run first.

---

## Quick Reference

### Access Scanner
```
WordPress Admin → Tools → URL Scanner
```

### Run Scan
```
Click "🔍 Scan All Posts for URL Errors"
```

### Fix Pattern
```
Find:    ?param1=value?param2=value
Replace: ?param1=value&param2=value
```

### Re-scan After Fixing
```
Run scanner again to verify all fixed
```

---

## Need Help?

- Check the main **USAGE.md** for general plugin help
- See **NETWORK-COMPATIBILITY.md** for your specific networks
- Review **readme.txt** for full documentation
- Check Settings page for supported parameters

---

**Remember:** The scanner is a diagnostic tool. It finds problems but doesn't fix them automatically. Always backup your database before making bulk changes!
