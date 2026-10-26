=== Affiliate Network Tracker ===
Contributors: yourname
Tags: affiliate, tracking, subid, conversion, analytics
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.0.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Track conversions across 12 affiliate networks with custom SubID parameter mapping. Know exactly which site and page drove each sale.

== Description ==

**Affiliate Network Tracker** helps you identify exactly which pages on your site are driving affiliate conversions. The plugin automatically replaces affiliate tracking parameters (SubIDs) with your site name and page slug, giving you detailed insights in your affiliate dashboards.

### 🎯 Key Features

* **12 Pre-Configured Networks**: BuyGoods, SmashLoud, SmartAdv, ClickHunts, MediaScalers, SellHealth, ClickBank, TerraLeads, MaxWeb, ClickAdv, Traffic Light, LeadBit
* **Custom Parameter Mapping**: Define Slot 1 and Slot 2 parameter names for each network
* **Automatic URL Detection**: Plugin recognizes affiliate networks by URL patterns
* **Auto-Add Missing Parameters**: Automatically adds Slot 2 parameter if URL only has Slot 1
* **Visual Dashboard**: Manage all networks from a clean, intuitive interface
* **Pretty Links Compatible**: Works seamlessly with Pretty Links plugin
* **Meta Redirect Support**: Handles countdown/redirect pages between your site and affiliates
* **Zero Configuration**: Pre-configured with sensible defaults, ready to use immediately

### 📊 What You'll Track

**Slot 1 (Site Identifier)**
* Your site name (e.g., "YourSiteName")
* Helps identify traffic source when you have multiple sites

**Slot 2 (Page Identifier)**  
* Current page slug (e.g., "best-products-2024")
* Shows exactly which content drove the conversion

### 🔧 Supported Networks

Each network has customizable parameter names:

1. **BuyGoods** - subid, subid2
2. **SmashLoud** - sub1, sub2
3. **SmartAdv** - source_id, sub1
4. **ClickHunts** - sub1, sub2
5. **MediaScalers** - sub1, sub2
6. **SellHealth** - t1, t2
7. **ClickBank** - aff_sub1, aff_sub2
8. **TerraLeads** - subid, subid2
9. **MaxWeb** - subid1, subid2
10. **ClickAdv** - sub1, sub2
11. **Traffic Light** - sub1, sub2
12. **LeadBit** - sub1, sub2

### 💡 How It Works

1. User clicks your affiliate link
2. Plugin detects which network based on URL pattern
3. Automatically replaces SubID parameters with:
   - **Slot 1**: Your site name
   - **Slot 2**: Current page slug
4. User redirects to affiliate with tracking info
5. In your affiliate dashboard, you see conversions by site and page

### 📈 Example Transformation

**Before:**
```
https://endopeak24.com/order?aff_id=12345&subid=test&subid2=test
```

**After:**
```
https://endopeak24.com/order?aff_id=12345&subid=YourSiteName&subid2=product-review
```

**In Affiliate Dashboard:**
* SubID: YourSiteName
* SubID2: product-review

Now you know this sale came from your "product-review" page!

== Installation ==

1. Upload the plugin files to `/wp-content/plugins/affiliate-network-tracker/`
2. Activate the plugin through the 'Plugins' menu
3. Go to **Affiliate Networks** in WordPress admin
4. Review and customize network settings if needed
5. Go to **Affiliate Networks → Settings** to configure tracking options

That's it! The plugin works automatically with default settings.

== Frequently Asked Questions ==

= Do I need to configure anything? =

No! The plugin comes pre-configured with 12 popular affiliate networks. Just activate and it works. You can customize parameter names if your network uses different ones.

= What if my network isn't listed? =

You can modify any of the 12 pre-configured networks to match your network's URL pattern and parameter names.

= Does it work with Pretty Links? =

Yes! The plugin is fully compatible with Pretty Links and meta redirect pages.

= Will it add missing SubID2 parameters? =

Yes! If a URL has Slot 1 parameter but is missing Slot 2, the plugin automatically adds it.

= Can I disable specific networks? =

Yes! Each network has an enable/disable checkbox in the dashboard.

= How do I customize parameter names? =

Go to **Affiliate Networks** dashboard and edit the "Slot 1 Parameter" and "Slot 2 Parameter" fields for any network.

= What gets sent as Slot 1 value? =

By default, your WordPress site name (sanitized). You can change this in **Affiliate Networks → Settings**.

= What gets sent as Slot 2 value? =

By default, the current page/post slug. You can choose from:
- Post/Page Slug
- Post/Page Title (sanitized)
- Full URL Path

= Does it slow down my site? =

No! The JavaScript is lightweight (~3KB) and only processes clicks on external affiliate links.

= Can I see what's being tracked? =

Yes! Open browser console (F12) and type `window.ANT` to access the tracking API and test URLs.

== Screenshots ==

1. Network Dashboard - Manage all 12 affiliate networks
2. Each network card with pattern and parameter settings
3. General Settings page
4. Example of URL transformation

== Changelog ==

= 1.0.0 =
* Initial release
* 12 pre-configured affiliate networks
* Custom parameter mapping per network
* Automatic URL pattern detection
* Auto-add missing Slot 2 parameters
* Pretty Links and meta redirect support
* Visual dashboard interface

== Upgrade Notice ==

= 1.0.0 =
Initial release of Affiliate Network Tracker.

== Additional Info ==

### Network Pattern Matching

The plugin matches URLs by checking if the affiliate URL contains the pattern you specify. For example:

* Pattern: `endopeak24.com` matches `https://endopeak24.com/b/order-now.php`
* Pattern: `clickbank.net` matches `https://abc123.hop.clickbank.net/`

### Parameter Replacement Logic

1. Plugin checks if URL matches any enabled network pattern
2. Finds Slot 1 parameter (e.g., `subid`, `sub1`, `t1`) and replaces value
3. Finds Slot 2 parameter (e.g., `subid2`, `sub2`, `t2`) and replaces value
4. If Slot 1 exists but Slot 2 is missing, adds Slot 2 parameter automatically

### Integration with Meta Redirect

If you use a countdown/redirect page between your site and the affiliate:

1. Plugin detects `?target=` parameter in redirect URL
2. Extracts the affiliate URL from target parameter
3. Rewrites SubIDs in the target URL
4. Final redirect goes to affiliate with correct tracking

### Developer API

JavaScript API available for custom implementations:

```javascript
// Rewrite any URL
const rewritten = window.ANT.rewriteUrl('https://affiliate.com/offer?subid=test');

// Detect which network a URL belongs to
const network = window.ANT.detectNetwork('https://endopeak24.com/offer');

// Check plugin version
console.log(window.ANT.version); // "1.0.0"
```

### Support

For issues, questions, or feature requests, please contact support or visit the plugin documentation.
