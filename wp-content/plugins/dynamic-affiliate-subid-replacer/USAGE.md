# Dynamic Affiliate SubID Replacer - Usage Guide

## Quick Start

### 1. Installation
1. Upload the `dynamic-affiliate-subid-replacer` folder to `/wp-content/plugins/`
2. Activate through WordPress admin → Plugins
3. Configure at Settings → Affiliate SubID

### 2. Basic Configuration
- **Enable Plugin**: Check to activate automatic SubID replacement
- **Slot 1 Value**: Leave empty to use your site name, or enter a custom tracking identifier
- **Slot 2 Mode**: Choose "Post/Page Slug" (recommended) or "Full URL Path"

### 3. Add Affiliate Links
No special setup required! Just add your affiliate links normally:

```html
<a href="https://affiliate.com/offer?aff_id=123&sub1=default&sub2=default">
    Buy Now
</a>
```

The plugin automatically rewrites `sub1` and `sub2` when visitors click.

---

## How It Works

### URL Rewriting Process

**Original Link (on "best-products" page):**
```
https://affiliate.com/offer?aff_id=123&sub1=test&sub2=test
```

**After Click (automatic):**
```
https://affiliate.com/offer?aff_id=123&sub1=MySiteName&sub2=best-products
```

### Supported Parameters

**Slot 1** (Site/Source Identifier):
- `subid`, `subid1`, `sub1`
- `t1`, `aff_sub1`, `source_id`
- `sourceA-id`

**Slot 2** (Page/Content Identifier):
- `subid2`, `sub2`
- `t2`, `aff_sub2`

---

## Usage Examples

### Example 1: Standard Affiliate Link

Add a regular text link in any post or page:

```html
<a href="https://shareasale.com/r.cfm?b=123&u=456&m=789&urllink=&afftrack=&sub1=default&sub2=default">
    Check This Product
</a>
```

**Result when clicked from "review-2024" page:**
- `sub1` → Your site name
- `sub2` → "review-2024"

### Example 2: Button with CSS Class

If you enable "Target Specific CSS Class" in settings:

**HTML:**
```html
<a href="https://cj.com/offer?sid=test&sid2=test" 
   class="btn affiliate-link">
    Buy Now
</a>
```

**WordPress Block Editor (Gutenberg):**
1. Add a Button block
2. Set the link URL with tracking parameters
3. In Block settings → Advanced → Additional CSS class(es), add: `affiliate-link`

### Example 3: Sponsored Link with rel Attribute

If you enable "Target Sponsored Links" in settings:

```html
<a href="https://amazon.com/dp/B08N5XYZ?tag=yourtag&sub1=x&sub2=x" 
   rel="sponsored nofollow">
    View on Amazon
</a>
```

Only links with `rel="sponsored"` or `rel="nofollow"` will be rewritten.

### Example 4: Custom HTML Block

In WordPress editor, add a Custom HTML block:

```html
<div class="affiliate-box">
    <h3>Recommended Product</h3>
    <p>This is our top pick...</p>
    <a href="https://partner.com/track?aff=123&sub1=default&sub2=default" 
       class="button affiliate-link" 
       rel="sponsored">
        Get 20% Off Now
    </a>
</div>
```

### Example 5: Multiple Links on Same Page

All links are tracked independently:

```html
<!-- Product A link -->
<a href="https://affiliate.com/product-a?id=1&sub1=x&sub2=x">Product A</a>

<!-- Product B link -->
<a href="https://affiliate.com/product-b?id=2&sub1=x&sub2=x">Product B</a>
```

Both will use the same page slug but track different products via their unique IDs.

---

## Advanced Usage

### Custom Slot 1 Value (Multi-Site Setup)

If you run multiple sites, set a unique Slot 1 value for each:

**Site 1 Settings:**
- Slot 1 Override: "tech-blog"

**Site 2 Settings:**
- Slot 1 Override: "lifestyle-blog"

Now you can differentiate traffic sources in your affiliate dashboard.

### Full Path Mode for Complex URLs

For sites with deep URL structures:

**Enable in Settings:**
- Slot 2 Mode: "Full URL Path"

**Example URLs:**
- `/category/tech/reviews/` → Slot 2 = "category-tech-reviews"
- `/products/electronics/` → Slot 2 = "products-electronics"

---

## Developer Customization

### Add Custom Tracking Parameters

Some affiliate networks use different parameter names. Add yours:

```php
// In your theme's functions.php
add_filter('da_subid_slot1_keys', function($keys) {
    $keys[] = 's1';           // Add ClickBank parameter
    $keys[] = 'tid';          // Add TradeDoubler parameter
    $keys[] = 'source';       // Add custom parameter
    $keys[] = 'sourceB-id';   // Add another custom parameter
    $keys[] = 'affiliate_id'; // Add legacy parameter
    return $keys;
});

add_filter('da_subid_slot2_keys', function($keys) {
    $keys[] = 's2';
    $keys[] = 'landing';
    $keys[] = 'pageID';
    return $keys;
});
```

**Important:** The plugin already includes `sourceA-id` by default. If you have other custom parameters like `sourceB-id`, `sourceC-id`, etc., add them using the filter above.

### Dynamic Value Based on User Role

```php
add_filter('da_subid_slot1_value', function($value) {
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        return $value . '-' . $user->roles[0]; // e.g., "MySite-subscriber"
    }
    return $value;
});
```

### Add Category to Page Slug

```php
add_filter('da_subid_slot2_value', function($slug) {
    if (is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            return $categories[0]->slug . '-' . $slug;
        }
    }
    return $slug;
});
```

### Disable on Specific Pages

```php
add_filter('da_subid_should_rewrite_link', function($should, $url, $context) {
    // Don't rewrite on checkout or cart pages
    if (is_checkout() || is_cart()) {
        return false;
    }
    
    // Don't rewrite links to specific domain
    if (strpos($url, 'example.com') !== false) {
        return false;
    }
    
    return $should;
}, 10, 3);
```

### Programmatically Add CSS Class to Links

```php
// Add affiliate-link class to all external links
add_filter('the_content', function($content) {
    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
    
    $links = $dom->getElementsByTagName('a');
    $site_url = home_url();
    
    foreach ($links as $link) {
        $href = $link->getAttribute('href');
        
        // If external and has tracking params
        if (strpos($href, $site_url) === false && 
            (strpos($href, 'sub1=') || strpos($href, 'subid='))) {
            
            $class = $link->getAttribute('class');
            $link->setAttribute('class', trim($class . ' affiliate-link'));
        }
    }
    
    return $dom->saveHTML();
});
```

---

## Testing Your Setup

### 1. Test Mode Setup

Temporarily add this to see what's happening:

```javascript
// Add to your browser console on any page
document.addEventListener('click', function(e) {
    const link = e.target.closest('a');
    if (link) {
        console.log('Original:', link.href);
        setTimeout(() => {
            console.log('After DASR:', link.href);
        }, 100);
    }
}, true);
```

### 2. Check JavaScript Console

Open browser DevTools (F12) and look for:
- "DASR: Configuration not loaded" → Plugin not active or JS not loaded
- No errors → Everything working correctly

### 3. Verify in Affiliate Dashboard

1. Click a test affiliate link
2. Complete a test action (if possible)
3. Check your affiliate network's reporting
4. Look for your site name and page slug in SubID columns

---

## Common Affiliate Network Parameter Mapping

| Network | Slot 1 Param | Slot 2 Param | Need Custom Filter? |
|---------|--------------|--------------|---------------------|
| ShareASale | `afftrack` or `sub1` | `sub2` | If using `afftrack` |
| CJ Affiliate | `sid` | `sid2` | Yes, add filters |
| Rakuten | `sub1` | `sub2` | No |
| Impact | `subid1` | `subid2` | No |
| ClickBank | `tid` | - | Yes for `tid` |
| Amazon Associates | Uses `tag` (incompatible) | - | Not supported* |

*Note: Amazon Associates doesn't support dynamic SubIDs in the traditional sense. Use different tracking tags instead.

### ShareASale Example with afftrack

```php
add_filter('da_subid_slot1_keys', function($keys) {
    $keys[] = 'afftrack';
    return $keys;
});
```

### CJ Affiliate Example

```php
add_filter('da_subid_slot1_keys', function($keys) {
    $keys[] = 'sid';
    return $keys;
});

add_filter('da_subid_slot2_keys', function($keys) {
    $keys[] = 'sid2';
    return $keys;
});
```

---

## Troubleshooting

### Links with Old/Custom Parameters Not Being Rewritten

**Problem:** You have old affiliate links using custom parameters like `sourceA-id`, `sourceB-id`, or other non-standard names.

**Solution 1 - Use Built-in Support:**
The plugin now includes `sourceA-id` by default. Links like this will work automatically:
```html
<a href="https://affiliate.com/offer?aff_id=123&sourceA-id=test">Link</a>
```

**Solution 2 - Add Your Custom Parameters:**
If you have other custom parameter names, add them via your theme's `functions.php`:

```php
add_filter('da_subid_slot1_keys', function($keys) {
    // Add all your custom parameter variations
    $keys[] = 'sourceB-id';
    $keys[] = 'sourceC-id';
    $keys[] = 'legacy-source';
    $keys[] = 'old_subid';
    $keys[] = 'affiliate_source';
    return $keys;
});
```

**Solution 3 - Test Your Configuration:**
To verify which parameters are active, add this temporary code:

```php
add_action('wp_footer', function() {
    if (current_user_can('manage_options')) {
        $instance = Dynamic_Affiliate_SubID_Replacer::get_instance();
        echo '<!-- DASR Active Keys: ';
        echo 'Slot 1: ' . implode(', ', apply_filters('da_subid_slot1_keys', []));
        echo ' | Slot 2: ' . implode(', ', apply_filters('da_subid_slot2_keys', []));
        echo ' -->';
    }
});
```

Then view page source and check the HTML comment at the bottom.

### Links Not Being Rewritten

**Check:**
1. Plugin is enabled in Settings → Affiliate SubID
2. Links are external (different domain than your site)
3. Links contain one of the supported tracking parameters
4. JavaScript is not blocked by ad blockers
5. No JavaScript errors in browser console (F12)

### Some Links Work, Others Don't

**Possible Causes:**
1. "Target Specific CSS Class" is enabled but links don't have the class
2. "Target Sponsored Links" is enabled but links don't have rel="sponsored"
3. Links are added after page load (AJAX) - should still work with event delegation

### Values Not Showing in Affiliate Dashboard

**Check:**
1. Make sure you're clicking from different pages to test Slot 2
2. Some networks have reporting delays (24-48 hours)
3. Verify parameter names match your network's requirements
4. Check if network has SubID reporting enabled in your account

### Cache Issues

This plugin is cache-friendly, but if you're testing:
1. Clear browser cache (Ctrl+F5 or Cmd+Shift+R)
2. Clear WordPress cache if using W3TC, WP Rocket, etc.
3. Try in incognito/private browsing mode

---

## Best Practices

### 1. Use Consistent Parameter Names
Make sure your affiliate links use consistent parameter names (`sub1`/`sub2` or `subid`/`subid2`).

### 2. Keep Original Values Simple
Use simple placeholders like "default" or "x" in your original links - they'll be replaced anyway.

### 3. Test Before Mass Deployment
Test with a few links first to ensure your affiliate network reports the data correctly.

### 4. Document Your Tracking Strategy
Keep notes on which parameters you're using and what data structure your affiliate dashboards expect.

### 5. Use Meaningful Slugs
Create clear, descriptive page/post slugs since they'll appear in your tracking data.

---

## Support & Resources

- **Settings Page**: WordPress Admin → Settings → Affiliate SubID
- **Plugin Files**: `/wp-content/plugins/dynamic-affiliate-subid-replacer/`
- **Documentation**: See `readme.txt` for full details
- **Filters Reference**: Check `readme.txt` Developer Documentation section

---

## Quick Reference Card

```
┌─────────────────────────────────────────────────────────┐
│  DASR QUICK REFERENCE                                   │
├─────────────────────────────────────────────────────────┤
│  Slot 1 Keys: subid, subid1, sub1, t1,                 │
│               aff_sub1, source_id                       │
│                                                         │
│  Slot 2 Keys: subid2, sub2, t2, aff_sub2               │
│                                                         │
│  Slot 1 Value: Site name (or custom in settings)       │
│  Slot 2 Value: Page slug (or full path)                │
│                                                         │
│  CSS Class: affiliate-link (if targeting enabled)      │
│  Rel Attribute: sponsored/nofollow (if enabled)        │
└─────────────────────────────────────────────────────────┘
```

---

**Need help?** Check the FAQ in `readme.txt` or review the settings page documentation.
