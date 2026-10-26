# Dynamic Affiliate SubID Replacer

A powerful WordPress plugin that automatically tracks which pages drive affiliate conversions by dynamically replacing tracking parameter values in your affiliate links.

## Features

✅ **Automatic Tracking** - No manual link editing required  
✅ **Smart Detection** - Recognizes common affiliate tracking parameters  
✅ **Meta Redirect Compatible** - Works with intermediate redirect/countdown pages  
✅ **URL Scanner Tool** - Find and fix URL syntax errors in your posts  
✅ **Flexible Configuration** - Customize values and targeting options  
✅ **Cache-Friendly** - Works with all caching plugins  
✅ **Zero Dependencies** - No jQuery, pure vanilla JavaScript  
✅ **Developer-Friendly** - Multiple WordPress hooks for customization  
✅ **Privacy-Focused** - No external API calls or data collection  

## Installation

1. Download or clone this repository
2. Upload the `dynamic-affiliate-subid-replacer` folder to `/wp-content/plugins/`
3. Activate the plugin through WordPress admin → Plugins
4. Configure at Settings → Affiliate SubID

## Quick Start

### Basic Example

Add any affiliate link with tracking parameters:

```html
<a href="https://affiliate.com/offer?aff_id=123&sub1=default&sub2=default">
    Buy Now
</a>
```

When clicked from the "best-products" page, it automatically becomes:

```
https://affiliate.com/offer?aff_id=123&sub1=YourSiteName&sub2=best-products
```

### Supported Tracking Parameters

**Slot 1** (Site/Source):
- `subid`, `subid1`, `sub1`, `t1`, `aff_sub1`, `source_id`, `sourceA-id`

**Slot 2** (Page/Content):
- `subid2`, `sub2`, `t2`, `aff_sub2`

## Configuration

Go to **Settings → Affiliate SubID** in WordPress admin:

| Setting | Description |
|---------|-------------|
| Enable Plugin | Toggle automatic SubID replacement |
| Slot 1 Value | Custom identifier (or leave empty for site name) |
| Slot 2 Mode | Post/Page Slug (default) or Full URL Path |
| Target CSS Class | Only rewrite links with specific class |
| Target Sponsored Links | Only rewrite links with rel="sponsored" |

### URL Scanner Tool

Go to **Tools → URL Scanner** to scan your posts for affiliate URLs with syntax errors:

```
Tools → URL Scanner → Click "Scan All Posts"
```

The scanner finds URLs like:
- `?param1=value?param2=value` ❌ (wrong)

And suggests fixes:
- `?param1=value&param2=value` ✅ (correct)

See **URL-SCANNER-GUIDE.md** for detailed instructions.

## Usage Examples

### Example 1: Button with CSS Class

```html
<a href="https://example.com/offer?sub1=x&sub2=x" 
   class="btn affiliate-link">
    Click Here
</a>
```

### Example 2: Gutenberg Button Block

1. Add a Button block
2. Set the URL with tracking parameters
3. Add "affiliate-link" to Additional CSS class(es)

### Example 3: Sponsored Link

```html
<a href="https://partner.com/product?subid=x&subid2=x" 
   rel="sponsored nofollow">
    View Product
</a>
```

## Developer Hooks

### Add Custom Tracking Parameters

```php
// Add custom parameter keys
add_filter('da_subid_slot1_keys', function($keys) {
    $keys[] = 'custom_source';
    return $keys;
});

add_filter('da_subid_slot2_keys', function($keys) {
    $keys[] = 'custom_page';
    return $keys;
});
```

### Customize Values Dynamically

```php
// Add user role to site name
add_filter('da_subid_slot1_value', function($value) {
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        return $value . '-' . $user->roles[0];
    }
    return $value;
});

// Add category prefix to page slug
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

### Conditional Link Rewriting

```php
// Don't rewrite on specific pages
add_filter('da_subid_should_rewrite_link', function($should, $url, $context) {
    if (is_checkout() || is_cart()) {
        return false;
    }
    return $should;
}, 10, 3);
```

## JavaScript API

The plugin exposes a public API for manual URL rewriting:

```javascript
// Manually rewrite a URL
const originalUrl = 'https://affiliate.com/offer?sub1=test&sub2=test';
const rewrittenUrl = window.DASR.rewriteUrl(originalUrl);
console.log(rewrittenUrl);
```

## File Structure

```
dynamic-affiliate-subid-replacer/
├── dynamic-affiliate-subid-replacer.php    # Main plugin file
├── assets/
│   └── js/
│       └── rewrite.js                      # Frontend JavaScript
├── readme.txt                              # WordPress.org format readme
├── USAGE.md                                # Detailed usage guide
├── README.md                               # This file
└── LICENSE                                 # GPL v2 license
```

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- Modern browser with JavaScript enabled

## Compatibility

✅ Works with caching plugins (WP Rocket, W3 Total Cache, etc.)  
✅ Compatible with all page builders  
✅ Works with Classic Editor and Gutenberg  
✅ Mobile-friendly  
✅ GDPR compliant (no data collection)  

## Common Affiliate Networks

| Network | Slot 1 | Slot 2 | Custom Filter Needed? |
|---------|--------|--------|----------------------|
| ShareASale | `sub1` | `sub2` | No (or use `afftrack`) |
| CJ Affiliate | `sid` | `sid2` | Yes |
| Rakuten | `sub1` | `sub2` | No |
| Impact | `subid1` | `subid2` | No |
| ClickBank | `tid` | - | Yes |

See `USAGE.md` for network-specific examples.

## Troubleshooting

**Links not rewriting?**
1. Check plugin is enabled in settings
2. Verify links are external (different domain)
3. Ensure links contain supported tracking parameters
4. Check browser console (F12) for errors

**Values not in affiliate dashboard?**
1. Verify parameter names match your network
2. Check if SubID reporting is enabled in your account
3. Some networks have 24-48 hour reporting delays

See `USAGE.md` for more troubleshooting tips.

## Support

- **Documentation**: See `USAGE.md` for comprehensive guide
- **Settings**: WordPress Admin → Settings → Affiliate SubID
- **Developer Docs**: See `readme.txt` filters section

## License

GPL v2 or later. See LICENSE file.

## Credits

Developed for affiliate marketers and content creators who want to track which pages drive conversions.

---

**Ready to track your best-performing content?** Install and activate the plugin, then check Settings → Affiliate SubID to get started!
