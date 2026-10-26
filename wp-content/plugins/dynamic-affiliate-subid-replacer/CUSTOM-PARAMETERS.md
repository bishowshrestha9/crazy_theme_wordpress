# Custom & Legacy Parameter Support

## Problem: Old Affiliate Links with Non-Standard Parameters

If your existing affiliate links use custom parameter names like:
- `sourceA-id`, `sourceB-id`, `sourceC-id`
- `legacy_sub`, `old_subid`
- Network-specific names: `clickbank_id`, `cj_sid`, etc.

## Solution: Built-in + Custom Configuration

### Already Supported (No Configuration Needed)

The plugin **already includes** these parameters by default:

**Slot 1 (Site/Source):**
- `subid`, `subid1`, `sub1`
- `t1`, `aff_sub1`
- `source_id`
- `sourceA-id` ✅ **Already included!**

**Slot 2 (Page/Content):**
- `subid2`, `sub2`
- `t2`, `aff_sub2`

### Example: Working Automatically

These links work **without any configuration**:

```html
<!-- Standard parameters -->
<a href="https://affiliate.com/offer?id=123&sub1=x&sub2=x">Link 1</a>

<!-- Custom parameter (already supported) -->
<a href="https://affiliate.com/offer?id=123&sourceA-id=x&sub2=x">Link 2</a>

<!-- Mixed parameters -->
<a href="https://partner.com/track?aff=456&source_id=x&subid2=x">Link 3</a>
```

All of these will automatically replace `sub1`, `sub2`, `sourceA-id`, `source_id`, and `subid2` values when clicked.

---

## Adding More Custom Parameters

### Step 1: Identify Your Parameter Names

Check your existing affiliate links to find all parameter variations:

```bash
# Search your content for parameter patterns
grep -r "sourceB-id" wp-content/
grep -r "legacy_sub" wp-content/
grep -r "old_subid" wp-content/
```

Or use WordPress search:
1. Go to Posts → Search
2. Search for common patterns: `source`, `subid`, `affiliate`

### Step 2: Add Custom Parameters

Add this code to your theme's `functions.php`:

```php
/**
 * Add custom affiliate tracking parameters
 * for legacy and non-standard affiliate links
 */
add_filter('da_subid_slot1_keys', function($keys) {
    // Add your custom Slot 1 parameters here
    $keys[] = 'sourceB-id';      // Old affiliate links
    $keys[] = 'sourceC-id';      // Another variation
    $keys[] = 'legacy_source';   // Legacy parameter name
    $keys[] = 'old_subid';       // Deprecated parameter
    $keys[] = 'affiliate_id';    // Custom name
    $keys[] = 's1';              // ClickBank
    $keys[] = 'tid';             // TradeDoubler
    $keys[] = 'clickbank_tid';   // ClickBank TID
    $keys[] = 'cj_sid';          // CJ Affiliate
    
    return $keys;
}, 10, 1);

add_filter('da_subid_slot2_keys', function($keys) {
    // Add your custom Slot 2 parameters here
    $keys[] = 's2';
    $keys[] = 'page_id';
    $keys[] = 'landing';
    $keys[] = 'content_id';
    
    return $keys;
}, 10, 1);
```

### Step 3: Verify Configuration

After adding the filter, check that it's working:

**Method 1: View Source**
1. Visit any page on your site (while logged in as admin)
2. View page source (Ctrl+U or Cmd+Option+U)
3. Search for `dasrConfig`
4. You should see your custom keys in the JavaScript config:
   ```javascript
   var dasrConfig = {
       "slot1Keys": ["subid","subid1","sub1","t1","aff_sub1","source_id","sourceA-id","sourceB-id",...],
       "slot2Keys": ["subid2","sub2","t2","aff_sub2","s2","page_id",...]
   }
   ```

**Method 2: Browser Console**
1. Open browser console (F12)
2. Type: `dasrConfig.slot1Keys`
3. Press Enter
4. Should show array with all your parameters

**Method 3: Test Link**
1. Create a test post with your custom parameter:
   ```html
   <a href="https://example.com/test?aff=123&sourceB-id=old&page_id=old">Test</a>
   ```
2. Click the link
3. Check the URL - parameters should be replaced

---

## Common Scenarios

### Scenario 1: Multiple Legacy Parameters

**Problem:** You have links with `sourceA-id`, `sourceB-id`, `sourceC-id` from different campaigns.

**Solution:**
```php
add_filter('da_subid_slot1_keys', function($keys) {
    // sourceA-id is already included by default
    $keys[] = 'sourceB-id';
    $keys[] = 'sourceC-id';
    $keys[] = 'sourceD-id';
    return $keys;
});
```

**Example Links:**
```html
<!-- Campaign A (works by default) -->
<a href="https://partner.com?id=1&sourceA-id=x">Campaign A</a>

<!-- Campaign B (needs filter above) -->
<a href="https://partner.com?id=2&sourceB-id=x">Campaign B</a>

<!-- Campaign C (needs filter above) -->
<a href="https://partner.com?id=3&sourceC-id=x">Campaign C</a>
```

### Scenario 2: Network-Specific Parameters

**Problem:** Different affiliate networks use different parameter names.

**Solution:**
```php
add_filter('da_subid_slot1_keys', function($keys) {
    // ClickBank
    $keys[] = 'tid';
    $keys[] = 'cbskin';
    
    // CJ Affiliate
    $keys[] = 'sid';
    $keys[] = 'cjevent';
    
    // ShareASale
    $keys[] = 'afftrack';
    
    // TradeDoubler
    $keys[] = 'tduid';
    
    // Rakuten
    $keys[] = 'ranSiteID';
    
    return $keys;
});
```

### Scenario 3: Migrating from Old System

**Problem:** You're migrating from an old tracking system that used different parameter names.

**Solution:**
```php
add_filter('da_subid_slot1_keys', function($keys) {
    // Keep supporting old parameter names during migration
    $keys[] = 'old_source';
    $keys[] = 'legacy_tracker';
    $keys[] = 'campaign_id';
    $keys[] = 'utm_source';  // If you were using UTM parameters
    
    return $keys;
});
```

Then gradually update your links over time while both old and new parameters are supported.

---

## Testing Your Configuration

### Test 1: Quick Console Test

```javascript
// Open browser console (F12) on any page
// Check configuration loaded
console.log('Slot 1 Keys:', dasrConfig.slot1Keys);
console.log('Slot 2 Keys:', dasrConfig.slot2Keys);

// Test manual rewrite
const testUrl = 'https://example.com/offer?id=123&sourceB-id=test&page_id=test';
console.log('Original:', testUrl);
console.log('Rewritten:', window.DASR.rewriteUrl(testUrl));
```

### Test 2: Create Test Page

Create a WordPress page with various parameter combinations:

```html
<h2>Test Links</h2>

<p><a href="https://example.com/1?aff=1&sub1=test&sub2=test">Standard: sub1/sub2</a></p>
<p><a href="https://example.com/2?aff=2&sourceA-id=test&subid2=test">Built-in: sourceA-id</a></p>
<p><a href="https://example.com/3?aff=3&sourceB-id=test&page_id=test">Custom: sourceB-id/page_id</a></p>
<p><a href="https://example.com/4?aff=4&legacy_source=test&content_id=test">Legacy: custom params</a></p>
```

Click each link and verify the parameters are replaced in the browser address bar.

### Test 3: Affiliate Dashboard

1. Click test links from different pages
2. Wait for tracking to register (usually 24-48 hours)
3. Check your affiliate network's reporting
4. Verify that SubID columns show:
   - Slot 1: Your site name (or custom value)
   - Slot 2: Page slugs (home, about-us, product-review, etc.)

---

## Advanced: Dynamic Parameter Detection

If you have hundreds of legacy links with varying parameter names, you can create a dynamic detection system:

```php
/**
 * Automatically detect and add custom parameters from existing content
 * Run this once to discover all parameter names in your content
 */
add_action('admin_init', function() {
    if (!current_user_can('manage_options')) return;
    
    // Only run when explicitly requested
    if (!isset($_GET['dasr_scan_params'])) return;
    
    global $wpdb;
    
    // Search all post content for affiliate links
    $posts = $wpdb->get_results("
        SELECT post_content 
        FROM {$wpdb->posts} 
        WHERE post_status = 'publish' 
        AND post_content LIKE '%http%'
    ");
    
    $found_params = [];
    
    foreach ($posts as $post) {
        // Find all URLs with query parameters
        preg_match_all('/https?:\/\/[^\s<>"]+\?[^\s<>"]+/', $post->post_content, $urls);
        
        foreach ($urls[0] as $url) {
            $parsed = parse_url($url);
            if (isset($parsed['query'])) {
                parse_str($parsed['query'], $params);
                $found_params = array_merge($found_params, array_keys($params));
            }
        }
    }
    
    $found_params = array_unique($found_params);
    sort($found_params);
    
    echo '<pre>';
    echo "Found parameters in your content:\n\n";
    foreach ($found_params as $param) {
        echo "- {$param}\n";
    }
    echo "\nAdd relevant ones to your filter!";
    echo '</pre>';
    exit;
});

// Visit: yoursite.com/wp-admin/?dasr_scan_params=1
```

---

## Quick Reference

### Default Slot 1 Parameters (No Configuration Needed)
```
subid, subid1, sub1, t1, aff_sub1, source_id, sourceA-id
```

### Default Slot 2 Parameters (No Configuration Needed)
```
subid2, sub2, t2, aff_sub2
```

### Add Custom Parameters
```php
// In functions.php
add_filter('da_subid_slot1_keys', function($keys) {
    $keys[] = 'your-custom-param';
    return $keys;
});
```

### Check Active Configuration
```javascript
// In browser console
console.log(dasrConfig.slot1Keys);
console.log(dasrConfig.slot2Keys);
```

---

## Need Help?

**Not sure which parameters to add?**
1. Check your affiliate network's documentation
2. View existing affiliate links in your content
3. Check your affiliate dashboard - it usually shows parameter names
4. Contact your affiliate manager for their preferred parameter names

**Still having issues?**
- Make sure the filter is in your active theme's `functions.php`
- Clear all caches (WordPress, browser, CDN)
- Check browser console for JavaScript errors
- Test in incognito mode to rule out extensions
- Verify the plugin is enabled in Settings → Affiliate SubID
