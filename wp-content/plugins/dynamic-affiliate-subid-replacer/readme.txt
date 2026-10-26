=== Dynamic Affiliate SubID Replacer ===
Contributors: yourname
Tags: affiliate, tracking, subid, links, conversion
Requires at least: 5.0
Tested up to: 6.7
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Automatically replaces affiliate tracking parameters (subid, sub1, sub2, etc.) with site name and page slug on outbound links.

== Description ==

**Dynamic Affiliate SubID Replacer** is a powerful WordPress plugin that automatically tracks which pages drive affiliate conversions by dynamically replacing tracking parameter values in your affiliate links.

= How It Works =

When a visitor clicks an outbound affiliate link on your site, the plugin:

1. Detects the click on external links using JavaScript
2. Checks if the URL contains known tracking parameters (subid, sub1, sub2, etc.)
3. Replaces the parameter values with:
   - **Slot 1**: Your site name (or custom value)
   - **Slot 2**: Current page/post slug
4. Preserves all other query parameters and redirects to the updated URL

= Key Features =

* **Automatic Tracking**: No manual link editing required
* **Smart Detection**: Recognizes common affiliate tracking parameters
* **Flexible Configuration**: Customize Slot 1 value and Slot 2 mode
* **Selective Targeting**: Option to only rewrite links with specific CSS classes or rel attributes
* **Cache-Friendly**: Works with caching plugins (no server-side sessions)
* **Zero jQuery**: Vanilla JavaScript with minimal footprint
* **Developer-Friendly**: Multiple WordPress hooks for customization
* **Privacy-Focused**: No external API calls or data collection

= Supported Tracking Parameters =

**Slot 1 (Site/Source) - Default Keys:**
- subid
- subid1
- sub1
- t1
- aff_sub1
- source_id
- sourceA-id
- lnk
- s

**Slot 2 (Page/Content) - Default Keys:**
- subid2
- sub2
- t2
- aff_sub2

= Example =

**Original Link on "Best Products" page:**
```
https://affiliate.com/offer?aff_id=123&sub1=test&sub2=default
```

**After Automatic Replacement:**
```
https://affiliate.com/offer?aff_id=123&sub1=YourSiteName&sub2=best-products
```

Now you can see in your affiliate dashboard that conversions came from the "best-products" page on "YourSiteName"!

= Use Cases =

* Track which blog posts generate the most affiliate sales
* Compare conversion rates between different landing pages
* Identify your highest-performing content
* A/B test different page layouts and messaging
* Optimize your content strategy based on real conversion data

= Developer Features =

The plugin provides several WordPress filters for advanced customization:

* `da_subid_slot1_keys` - Modify Slot 1 parameter keys
* `da_subid_slot2_keys` - Modify Slot 2 parameter keys
* `da_subid_slot1_value` - Customize Slot 1 value dynamically
* `da_subid_slot2_value` - Customize Slot 2 value dynamically
* `da_subid_should_rewrite_link` - Control which links get rewritten

== Installation ==

= Automatic Installation =

1. Log in to your WordPress admin panel
2. Navigate to Plugins → Add New
3. Search for "Dynamic Affiliate SubID Replacer"
4. Click "Install Now" and then "Activate"
5. Go to Settings → Affiliate SubID to configure

= Manual Installation =

1. Download the plugin ZIP file
2. Log in to your WordPress admin panel
3. Navigate to Plugins → Add New → Upload Plugin
4. Choose the ZIP file and click "Install Now"
5. Click "Activate Plugin"
6. Go to Settings → Affiliate SubID to configure

= Configuration =

After activation, go to **Settings → Affiliate SubID** to configure:

1. **Enable Plugin**: Toggle on/off
2. **Slot 1 Value**: Leave empty for site name, or enter custom value
3. **Slot 2 Mode**: Choose "Post/Page Slug" or "Full URL Path"
4. **Advanced Options**: Target specific CSS classes or rel attributes

== Frequently Asked Questions ==

= Does this work with caching plugins? =

Yes! The plugin uses client-side JavaScript to rewrite URLs, so it works perfectly with all caching plugins (WP Rocket, W3 Total Cache, WP Super Cache, etc.).

= Will this slow down my site? =

No. The JavaScript file is very small (~3KB) and uses efficient event delegation. There's no performance impact.

= Does it work with block editor (Gutenberg)? =

Yes! The plugin works with any link on your WordPress site, regardless of how it was created (Classic Editor, Gutenberg, page builders, widgets, etc.).

= Can I use custom tracking parameter names? =

Yes! Use the WordPress filters `da_subid_slot1_keys` and `da_subid_slot2_keys` to add or modify parameter keys.

= What if my affiliate network uses different parameter names? =

You can customize the parameter keys using WordPress filters. See the "Developer Documentation" section below.

= Does it work with Pretty Links or similar plugins? =

Yes, as long as the final destination URL (after redirect) contains the tracking parameters.

= Will internal links be affected? =

No. The plugin only processes external links (links to different domains).

= Can I target only specific affiliate buttons? =

Yes! Enable "Target Specific CSS Class" in settings and add that class to your affiliate links.

= Does it work on mobile devices? =

Yes! The plugin works on all devices and browsers.

= Is there any analytics or tracking? =

No. The plugin doesn't collect or send any data. All processing happens in the visitor's browser.

== Developer Documentation ==

= Available Filters =

**Customize Slot 1 Parameter Keys:**
```php
add_filter('da_subid_slot1_keys', function($keys) {
    $keys[] = 'custom_source';
    return $keys;
});
```

**Customize Slot 2 Parameter Keys:**
```php
add_filter('da_subid_slot2_keys', function($keys) {
    $keys[] = 'custom_page';
    return $keys;
});
```

**Customize Slot 1 Value Dynamically:**
```php
add_filter('da_subid_slot1_value', function($value) {
    // Use different value for logged-in users
    if (is_user_logged_in()) {
        return 'member-' . $value;
    }
    return $value;
});
```

**Customize Slot 2 Value Dynamically:**
```php
add_filter('da_subid_slot2_value', function($slug) {
    // Add category prefix
    if (is_single()) {
        $categories = get_the_category();
        if ($categories) {
            return $categories[0]->slug . '-' . $slug;
        }
    }
    return $slug;
});
```

**Conditionally Control Link Rewriting:**
```php
add_filter('da_subid_should_rewrite_link', function($should_rewrite, $url, $context) {
    // Don't rewrite links on checkout pages
    if (is_checkout()) {
        return false;
    }
    return $should_rewrite;
}, 10, 3);
```

= JavaScript API =

The plugin exposes a JavaScript API for manual URL rewriting:

```javascript
// Manually rewrite a URL
const originalUrl = 'https://affiliate.com/offer?sub1=test&sub2=test';
const rewrittenUrl = window.DASR.rewriteUrl(originalUrl);
console.log(rewrittenUrl);
// Output: https://affiliate.com/offer?sub1=YourSite&sub2=current-page
```

= Adding CSS Class to Links =

To target only specific affiliate links, add the CSS class in your HTML:

```html
<!-- Standard link -->
<a href="https://affiliate.com/offer?sub1=x&sub2=y" class="affiliate-link">
    Click Here
</a>

<!-- Gutenberg button block -->
Add "affiliate-link" to the "Additional CSS class(es)" field in block settings
```

Or programmatically in your theme:

```php
// Add class to specific links
add_filter('the_content', function($content) {
    // Add affiliate-link class to links containing affiliate.com
    $content = str_replace(
        'href="https://affiliate.com',
        'class="affiliate-link" href="https://affiliate.com',
        $content
    );
    return $content;
});
```

== Screenshots ==

1. Settings page - General configuration
2. Advanced targeting options
3. Default tracking parameters reference
4. Example of URL rewriting in action

== Changelog ==

= 1.0.0 - 2026-02-02 =
* Initial release
* Automatic SubID replacement for outbound links
* Support for common affiliate tracking parameters
* Configurable Slot 1 and Slot 2 values
* Advanced targeting options (CSS class, rel attribute)
* Multiple WordPress filters for customization
* Cache-friendly JavaScript implementation
* Zero dependencies (no jQuery)

== Upgrade Notice ==

= 1.0.0 =
Initial release of Dynamic Affiliate SubID Replacer.

== Privacy Policy ==

This plugin does not:
- Collect any user data
- Track user behavior
- Make external API calls
- Use cookies
- Store visitor information
- Send data to third parties

All URL rewriting happens client-side in the visitor's browser. No data is transmitted or stored.

== Support ==

For support, feature requests, or bug reports:
- Visit the plugin support forum
- Check the documentation in Settings → Affiliate SubID
- Review the developer filters in readme.txt

== Credits ==

Developed with ❤️ for affiliate marketers and content creators.
