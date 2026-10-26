# Quick Start Guide

## ✅ You're All Set!

Your plugin is **100% ready** for all your affiliate networks. No configuration needed!

---

## Installation (3 Steps)

### 1. Activate Plugin
```
WordPress Admin → Plugins → Activate "Dynamic Affiliate SubID Replacer"
```

### 2. Scan for URL Errors (Recommended)
```
Tools → URL Scanner → Click "🔍 Scan All Posts"
Fix any URLs with syntax errors (see results page for instructions)
```

### 3. Enable Settings
```
Settings → Affiliate SubID
✅ Check "Enable Plugin"
📝 Slot 1 Value: Leave empty (uses site name automatically)
📝 Slot 2 Mode: Post/Page Slug
💾 Click "Save Settings"
```

---

## What Happens Now?

When visitors click your affiliate links:

**Direct Links:**
```
https://affiliate.com/offer?aff_id=123&sub1=test&sub2=test
         ↓
https://affiliate.com/offer?aff_id=123&sub1=YourSiteName&sub2=best-products
```

**With Meta Redirect (Countdown Page):**
```
https://yoursite.com/?target=https://affiliate.com/offer?sub1=test&sub2=test
         ↓ (rewrites target parameter)
https://yoursite.com/?target=https://affiliate.com/offer?sub1=YourSiteName&sub2=best-products
         ↓ (shows countdown)
https://affiliate.com/offer?sub1=YourSiteName&sub2=best-products
```

Now you can see **which pages drive conversions** in your affiliate dashboards!

---

## Your Networks Status

### ✅ All 12 Networks Supported

- **BuyGoods** - `subid`, `subid2` ✅
- **SmashLoud** - `sub1`, `sub2` ✅
- **SmartAdv** - `source_id`, `sub1` ✅
- **ClickHunts** - `sub1`, `sub2` ✅
- **MediaScalers** - `sub1`, `sub2` ✅
- **SellHealth** - `t1`, `t2` ✅
- **ClickBank** - `aff_sub1`, `aff_sub2` ✅
- **TerraLeads** - `subid`, `subid` ⚠️ *Fix URL syntax*
- **MaxWeb** - `subid1`, `subid2` ⚠️ *Fix URL syntax*
- **ClickAdv** - `sub1`, `sub2` ✅
- **Traffic Light** - `lnk`, `s`, `sub1`, `sub2` ✅
- **LeadBit** - `sub1`, `sub2` ✅

---

## Old Posts with source_id?

### ✅ No Action Needed!

Your old posts using `source_id` will work **automatically**. The parameter is already supported by default.

Just install, activate, and your old links work immediately.

---

## Test Your Setup

### Quick Test (2 minutes)

1. **Create test post** with one affiliate link:
   ```html
   <a href="https://example.com/offer?aff=123&sub1=test&sub2=test">Test</a>
   ```

2. **View the post** and click the link

3. **Check address bar** - should show your site name and page slug

4. **Open browser console** (F12) and type:
   ```javascript
   dasrConfig.slot1Keys
   ```
   Should show: `["subid", "subid1", "sub1", "t1", "aff_sub1", "source_id", ...]`

---

## Documentation

- **NETWORK-COMPATIBILITY.md** - Detailed network-by-network analysis
- **USAGE.md** - Comprehensive usage examples
- **CUSTOM-PARAMETERS.md** - Add more custom parameters
- **INSTALL.md** - Detailed installation instructions
- **readme.txt** - Full plugin documentation

---

## Support

**Settings Page:** WordPress Admin → Settings → Affiliate SubID  
**Check Parameters:** All supported parameters listed on settings page  
**Test Console:** Open F12 → type `window.DASR.version` (should show "1.0.0")

---

## Summary

✅ Install plugin  
✅ Enable in settings  
✅ Fix 2 URL syntax errors (TerraLeads, MaxWeb)  
✅ Your old posts work automatically  
✅ All 12 networks supported  
✅ No manual link editing needed  
✅ No ongoing maintenance required  

**That's it! You're done.** 🎉

Now you'll see which pages drive conversions in your affiliate dashboards!
