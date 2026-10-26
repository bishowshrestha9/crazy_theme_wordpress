# Affiliate Network Tracker

> Track conversions across 12 affiliate networks with custom SubID parameter mapping. Know exactly which site and page drove each sale.

## 🎯 Features

- **12 Pre-Configured Networks** - BuyGoods, SmashLoud, SmartAdv, and 9 more
- **Custom Parameter Mapping** - Define Slot 1 and Slot 2 parameter names per network
- **Automatic Detection** - Plugin recognizes networks by URL patterns
- **Visual Dashboard** - Manage all networks from one clean interface
- **Auto-Add Missing Params** - Adds Slot 2 parameter if URL only has Slot 1
- **Pretty Links Compatible** - Works with Pretty Links plugin
- **Meta Redirect Support** - Handles countdown pages between site and affiliate

## 📦 Installation

1. Upload to `/wp-content/plugins/affiliate-network-tracker/`
2. Activate through WordPress admin
3. Go to **Affiliate Networks** menu
4. Review network settings (pre-configured with defaults)
5. Done! Plugin works automatically

## 🚀 Quick Start

### Default Configuration

The plugin comes pre-configured with 12 networks:

| Network | Pattern | Slot 1 | Slot 2 |
|---------|---------|--------|--------|
| BuyGoods | endopeak24.com | subid | subid2 |
| SmashLoud | premierdiscountlink.com | sub1 | sub2 |
| SmartAdv | knownwalk.com | source_id | sub1 |
| ClickHunts | ch8gs4fh.com | sub1 | sub2 |
| MediaScalers | clickrtrckr.com | sub1 | sub2 |
| SellHealth | testosil.com | t1 | t2 |
| ClickBank | clickbank.net | aff_sub1 | aff_sub2 |
| TerraLeads | tl-track.com | subid | subid2 |
| MaxWeb | mwebtrackerhq.com | subid1 | subid2 |
| ClickAdv | chffn8trk.com | sub1 | sub2 |
| Traffic Light | xcartpro.com | sub1 | sub2 |
| LeadBit | keonv.com | sub1 | sub2 |

### What Gets Tracked

**Slot 1 (Site Identifier)**
- Default: Your site name (e.g., "YourSiteName")
- Customizable in Settings

**Slot 2 (Page Identifier)**
- Default: Current page slug (e.g., "product-review")
- Options: Page slug, Page title, or Full URL path

## 📊 How It Works

### URL Transformation

**Before:**
```
https://endopeak24.com/b/order-now.php?aff_id=129183&subid=dhs&subid2=dhs2
```

**After:**
```
https://endopeak24.com/b/order-now.php?aff_id=129183&subid=YourSiteName&subid2=product-review
```

### In Your Affiliate Dashboard

```
Conversion #12345
SubID: YourSiteName
SubID2: product-review
```

Now you know: This sale came from your "product-review" page!

## 🎛️ Dashboard Usage

### Network Management

1. Go to **Affiliate Networks** in WordPress admin
2. See all 12 networks in a grid layout
3. Each network card shows:
   - Enable/Disable checkbox
   - URL Pattern (domain to match)
   - Slot 1 Parameter name
   - Slot 2 Parameter name

### Customizing a Network

**Example: Changing BuyGoods parameters**

1. Find "BuyGoods" card
2. Change "Slot 1 Parameter" from `subid` to `sub1`
3. Change "Slot 2 Parameter" from `subid2` to `sub2`
4. Click "Save Network Settings"

Now all BuyGoods URLs will use `sub1` and `sub2` instead!

### Adding a New Pattern

**Example: You have multiple BuyGoods domains**

1. Pick an unused network card (or modify existing)
2. Set Pattern: `anotherdomain.com`
3. Set Slot 1: `subid`
4. Set Slot 2: `subid2`
5. Enable the network
6. Save

### Disabling a Network

Simply uncheck the checkbox next to the network name and save.

## ⚙️ Settings

Go to **Affiliate Networks → Settings**

### Enable/Disable Tracking

Turn the entire plugin on/off without deactivating.

### Slot 1 Value (Site Name)

Choose what gets sent as Slot 1:
- **Site Name** - WordPress site name (auto-sanitized)
- **Custom Value** - Enter your own identifier

### Slot 2 Value (Page Identifier)

Choose what gets sent as Slot 2:
- **Post/Page Slug** - e.g., "best-products-2024" (recommended)
- **Post/Page Title** - e.g., "Best Products 2024" (sanitized)
- **Full URL Path** - e.g., "category-best-products-2024"

## 🔧 Advanced Usage

### Auto-Adding Missing Parameters

If your affiliate URL has Slot 1 but is missing Slot 2:

**Input:**
```
https://endopeak24.com/offer?aff_id=12345&subid=test
```

**Output:**
```
https://endopeak24.com/offer?aff_id=12345&subid=YourSiteName&subid2=page-slug
```

The plugin automatically adds `subid2` with the page slug!

### Pretty Links Integration

**Your Setup:**
```
Pretty Link: yoursite.com/go/product
Target URL: https://endopeak24.com/offer?subid=test&subid2=test
```

**User clicks link:**
```
yoursite.com/go/product
  ↓
Meta Redirect Page (countdown)
  ↓
Plugin rewrites SubIDs
  ↓
https://endopeak24.com/offer?subid=YourSiteName&subid2=product-page
```

### Meta Redirect Compatibility

If you use a countdown page that redirects via `?target=` parameter:

1. Plugin detects `target` parameter
2. Extracts affiliate URL from it
3. Rewrites SubIDs in the affiliate URL
4. Final redirect includes correct tracking

## 🧪 Testing

### Test in Browser Console

1. Visit any page on your site
2. Press **F12** to open console
3. Type:
```javascript
// Check if plugin loaded
window.ANT

// Test URL rewriting
window.ANT.rewriteUrl('https://endopeak24.com/offer?subid=test&subid2=test')

// Detect network
window.ANT.detectNetwork('https://endopeak24.com/offer')
```

### Test a Real Link

1. Create a test post with an affiliate link
2. Click the link
3. Check the final URL in browser address bar
4. Verify SubIDs show your site name and page slug

### Check Affiliate Dashboard

1. Wait 24-48 hours for tracking data
2. Login to your affiliate dashboard
3. View conversions by SubID
4. Should see your site name and page slugs

## 📋 Network-Specific Notes

### BuyGoods
- Pattern: `endopeak24.com` or your specific domain
- Multiple domains? Add them to separate network cards

### SmartAdv
- Uses `source_id` for Slot 1 (not `sub1`)
- Also has `uid` parameter (leave unchanged)

### ClickBank
- Pattern works for all ClickBank hop links
- Format: `*.hop.clickbank.net`

### TerraLeads & MaxWeb
- May have URL syntax errors in old posts
- Use URL Scanner tool from other plugin to detect issues

### Traffic Light
- Pattern: `xcartpro.com` (matches subdomain too)
- Uses both `lnk` and `sub1`/`sub2` parameters

## 🐛 Troubleshooting

### SubIDs Not Being Replaced

**Check:**
1. Is plugin enabled? (**Settings** → Enable Tracking)
2. Is network enabled? (Checkbox in network card)
3. Does URL match pattern? (Pattern must appear in URL)
4. Do parameter names match? (Check affiliate URL parameters)

### Wrong Parameters

**Solution:**
1. Check your actual affiliate URL
2. Find the parameter names (e.g., `?sub1=x&sub2=y`)
3. Update network card with correct names
4. Save settings

### Network Not Detected

**Solution:**
1. Check URL pattern in network card
2. Pattern should match domain exactly
3. Example: `endopeak24.com` matches `https://endopeak24.com/anything`

### JavaScript Not Loading

**Check:**
1. Open Console (F12)
2. Type `window.ANT`
3. If undefined, check for JavaScript errors
4. Verify plugin is active

## 🔌 API Reference

### JavaScript API

```javascript
// Rewrite any URL
const newUrl = window.ANT.rewriteUrl(originalUrl);

// Detect network from URL
const network = window.ANT.detectNetwork(url);
// Returns: { pattern: 'endopeak24.com', slot1_param: 'subid', slot2_param: 'subid2' }

// Check version
console.log(window.ANT.version); // "1.0.0"
```

### PHP Hooks

```php
// Modify networks programmatically
add_filter('ant_networks', function($networks) {
    $networks['custom'] = array(
        'name' => 'Custom Network',
        'pattern' => 'custom.com',
        'slot1_param' => 'sid1',
        'slot2_param' => 'sid2',
        'enabled' => true,
    );
    return $networks;
});
```

## 📈 Analytics Strategy

### Tracking Different Content Types

**Product Reviews:**
- Slug: "best-protein-powder-2024"
- Shows which review drives most sales

**Comparison Posts:**
- Slug: "product-a-vs-product-b"
- Track comparison content performance

**Landing Pages:**
- Slug: "special-offer-2024"
- Measure campaign effectiveness

### Multi-Site Tracking

If you have multiple sites promoting same offers:

1. Each site sends its own name as Slot 1
2. Page slugs as Slot 2
3. In affiliate dashboard, filter by site name
4. Compare performance across sites

## 📄 License

GPL v2 or later

## 🤝 Support

For issues or questions:
- Check documentation first
- Review troubleshooting section
- Contact plugin support

## 📝 Changelog

### Version 1.0.0
- Initial release
- 12 pre-configured networks
- Visual dashboard
- Auto-add missing parameters
- Pretty Links support
- Meta redirect compatibility
