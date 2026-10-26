# Pretty Links + Meta Redirect + SubID Replacer Integration

## 🎯 Your Complete Setup

You're using a sophisticated three-layer affiliate link management system:

```
Layer 1: Pretty Links (yoursite.com/go/product)
    ↓
Layer 2: Meta Redirect Page (countdown/warning)
    ↓
Layer 3: Affiliate Network (affiliate.com?subid1=X&subid2=Y)
```

The SubID Replacer is now **fully integrated** with all three layers!

---

## How Your Setup Works

### Layer 1: Pretty Links Management

**What you do:**
1. Create Pretty Link in WordPress admin
2. Slug: `/go/product`
3. Target URL: `https://affiliate.com/offer?subid1=test&subid2=test`

**What users see in your posts:**
```html
<a href="https://yoursite.com/go/product">Buy Now</a>
```

Clean, simple, branded URL!

### Layer 2: Meta Redirect Interception

**What happens:**
1. User clicks Pretty Link (`/go/product`)
2. Your Auto Meta Redirect plugin intercepts it
3. Gets target URL from Pretty Links database
4. Redirects to: `yoursite.com/?amr_redirect=1&target=ENCODED_AFFILIATE_URL`
5. Shows countdown page

### Layer 3: SubID Replacement

**What happens on redirect page:**
1. Target URL is in the page: `https://affiliate.com/offer?subid1=test&subid2=test`
2. SubID Replacer JavaScript loads
3. Before countdown completes, it rewrites: `subid1=YourSiteName`, `subid2=page-slug`
4. Final redirect goes to: `https://affiliate.com/offer?subid1=YourSiteName&subid2=product-review`

---

## Complete Flow Example

### Step 1: You Create Pretty Link

**WordPress Admin → Pretty Links:**
```
Slug: /go/best-supplement
Target: https://buygoods.com/offer?aff_id=12345&subid=default&subid2=default
```

### Step 2: You Add Link to Post

**In "Best Health Products 2024" post:**
```html
<a href="https://yoursite.com/go/best-supplement">
    Try This Amazing Supplement
</a>
```

### Step 3: User Clicks Link

**Browser goes to:**
```
https://yoursite.com/go/best-supplement
```

### Step 4: Auto Meta Redirect Intercepts

**Looks up Pretty Link, redirects to:**
```
https://yoursite.com/?amr_redirect=1&target=https%3A%2F%2Fbuygoods.com%2Foffer%3Faff_id%3D12345%26subid%3Ddefault%26subid2%3Ddefault
```

### Step 5: Countdown Page Loads

**User sees:**
- "Redirecting in 5 seconds..."
- Countdown timer
- Loading messages

**Behind the scenes:**
- SubID Replacer script loads
- Extracts target URL
- Detects `subid` and `subid2` parameters

### Step 6: SubIDs Get Replaced

**Original target:**
```
https://buygoods.com/offer?aff_id=12345&subid=default&subid2=default
```

**Rewritten target:**
```
https://buygoods.com/offer?aff_id=12345&subid=YourSiteName&subid2=best-health-products-2024
```

### Step 7: User Redirects to Affiliate

**After countdown:**
```
https://buygoods.com/offer?aff_id=12345&subid=YourSiteName&subid2=best-health-products-2024
```

### Step 8: BuyGoods Sees Conversion

**In BuyGoods dashboard:**
```
Conversion
SubID: YourSiteName
SubID2: best-health-products-2024

You now know this sale came from your "Best Health Products 2024" post!
```

---

## Why Your Current SubIDs Are Empty

Looking at your URL:
```
https://herbcare.club/index.php?lid=89&cid=78&aid=56&csize=4&subid1=22179&subid2=&subid3=6cd1acea16204664909513c1d3c3d9bd
```

**Issues:**
1. ❌ `subid2=` is empty (no value after `=`)
2. ✅ `subid1=22179` has a value
3. ⚠️ This network uses `subid1`, `subid2`, `subid3` (not `sub1`, `sub2`)

**Solutions:**

### Solution 1: Verify Plugin is Active & Enabled

```
WordPress Admin → Plugins → Check "Dynamic Affiliate SubID Replacer" is active
WordPress Admin → Settings → Affiliate SubID → Ensure "Enable Plugin" is checked
```

### Solution 2: Check Your Pretty Link Target URL

Your Pretty Link target should have placeholder values:
```
✅ Good: ?subid1=test&subid2=test&subid3=test
❌ Bad:  ?subid1=22179&subid2=&subid3=xxx
```

If `subid2=` is already empty in your Pretty Link, the plugin can't replace nothing!

**Fix:**
1. Go to Pretty Links → Edit your link
2. Update target URL to include: `&subid2=placeholder`
3. Save

### Solution 3: Verify Parameters Are Supported

Check if `subid1`, `subid2`, `subid3` are in the supported list:

**Built-in Slot 1 parameters:**
- `subid` ✅
- `subid1` ✅
- `sub1` ✅
- `t1`, `aff_sub1`, `source_id`, `sourceA-id`, `lnk`, `s`

**Built-in Slot 2 parameters:**
- `subid2` ✅
- `sub2` ✅
- `t2`, `aff_sub2`

**For `subid3` (Slot 2):**
Add via filter in `functions.php`:

```php
add_filter('da_subid_slot2_keys', function($keys) {
    $keys[] = 'subid3';
    return $keys;
});
```

---

## Testing Your Complete Setup

### Test 1: Verify Pretty Link Target

1. Go to **Pretty Links** in WordPress admin
2. Find one of your links
3. Check the **Target URL**
4. Verify it has SubID parameters with values:
   ```
   ✅ ?subid1=test&subid2=test
   ❌ ?subid1=&subid2=
   ```

### Test 2: Check Redirect Page

1. Visit a Pretty Link directly: `yoursite.com/go/product`
2. Should redirect to countdown page
3. Open browser console (F12)
4. Type: `dasrConfig`
5. Should show configuration with your parameters

### Test 3: Watch the Rewrite

1. Visit Pretty Link from a post page
2. On countdown page, open console (F12)
3. Type:
   ```javascript
   // Get the target URL from page
   const targetUrl = new URL(window.location.href).searchParams.get('target');
   console.log('Original:', decodeURIComponent(targetUrl));
   
   // Test rewrite
   const rewritten = window.DASR.rewriteUrl(decodeURIComponent(targetUrl));
   console.log('Rewritten:', rewritten);
   ```
4. Should show SubIDs replaced

### Test 4: Complete Flow

1. **Create test post** titled "Test Product Review"
2. **Add Pretty Link:** `/go/test-product`
3. **Target URL:** Your affiliate URL with `subid1=test&subid2=test`
4. **Add link to post**
5. **View post**
6. **Click link**
7. **Watch countdown**
8. **Check final URL** - should have your site name and "test-product-review"

---

## Troubleshooting

### Issue: SubIDs Still Not Replaced

**Check 1: Is plugin enabled?**
```
Settings → Affiliate SubID → "Enable Plugin" should be checked
```

**Check 2: Does target URL have SubID parameters?**
```
Pretty Link target must include: ?subid1=something&subid2=something
Not: ?subid1=&subid2=
```

**Check 3: Is JavaScript loading?**
```
On countdown page, F12 → Console
Type: window.DASR
Should show: {rewriteUrl: ƒ, version: "1.0.0"}
If undefined: Plugin script not loading
```

**Check 4: Are parameters supported?**
```
Check Settings → Affiliate SubID page
Lists all supported parameters
If yours not listed, add via filter
```

### Issue: subid2 is Empty

**Cause:** Your Pretty Link target has `&subid2=` with no value

**Fix:**
1. Edit Pretty Link
2. Change target from:
   ```
   ?subid1=xxx&subid2=&subid3=xxx
   ```
   To:
   ```
   ?subid1=xxx&subid2=test&subid3=xxx
   ```
3. Save
4. Plugin will now replace "test" with page slug

### Issue: subid3 Not Being Replaced

**Cause:** `subid3` not in default parameters list

**Fix:** Add to `functions.php`:
```php
add_filter('da_subid_slot2_keys', function($keys) {
    $keys[] = 'subid3';
    return $keys;
});
```

Or if it's a Slot 1 parameter:
```php
add_filter('da_subid_slot1_keys', function($keys) {
    $keys[] = 'subid3';
    return $keys;
});
```

---

## Pretty Links Best Practices

### 1. Use Descriptive Slugs
```
✅ /go/weight-loss-supplement
✅ /go/protein-powder
❌ /go/link1
❌ /go/aff123
```

Helps you identify which Pretty Link is which.

### 2. Include SubID Placeholders in Target
```
✅ ?subid1=test&subid2=test
✅ ?sub1=placeholder&sub2=placeholder
❌ ?subid1=&subid2=
```

### 3. Organize by Category
```
/go/supplements/protein-powder
/go/supplements/vitamins
/go/fitness/gym-equipment
```

### 4. Document Your Links
Keep a spreadsheet:
```
Slug | Target URL | Network | Post Used In | Conversions
/go/product-a | affiliate.com/a | BuyGoods | Review Post | 47
/go/product-b | partner.com/b | ClickBank | About Page | 12
```

### 5. Test Each New Link
Before using in posts:
1. Create Pretty Link
2. Test the link
3. Verify SubIDs are replaced
4. Check countdown works
5. Confirm final destination correct

---

## Updating Existing Pretty Links

If you have existing Pretty Links with empty or wrong SubID values:

### Bulk Update via Database (Advanced)

**⚠️ BACKUP FIRST!**

```sql
-- View current Pretty Links
SELECT id, name, slug, url 
FROM wp_prli_links 
LIMIT 10;

-- Update links that are missing subid2
UPDATE wp_prli_links 
SET url = REPLACE(url, '&subid2=&', '&subid2=test&')
WHERE url LIKE '%&subid2=&%';

-- Update links that end with empty subid2
UPDATE wp_prli_links 
SET url = REPLACE(url, '&subid2=', '&subid2=test')
WHERE url LIKE '%&subid2=' 
AND url NOT LIKE '%&subid2=%';

-- Verify changes
SELECT id, name, url 
FROM wp_prli_links 
WHERE url LIKE '%subid%';
```

### Manual Update via WordPress Admin

1. Go to **Pretty Links**
2. For each link:
   - Click **Edit**
   - Update target URL to include SubID parameters with test values
   - Save
3. Test the link

---

## Expected Results

### Before Setup
```
Pretty Link: /go/product → Redirect Page → affiliate.com?subid1=22179&subid2=
```
**Problem:** Can't track which page drove conversion

### After Setup
```
Pretty Link: /go/product → Redirect Page → affiliate.com?subid1=YourSite&subid2=product-review
```
**Result:** Know exactly which post converted!

### In Affiliate Dashboard
```
Week 1:
- product-review: 15 conversions
- about-us: 3 conversions
- comparison-post: 8 conversions

Week 2:
- best-supplements-2024: 22 conversions
- product-review: 12 conversions
- homepage: 5 conversions
```

**Actionable Insights:**
- "best-supplements-2024" is your top performer → create more similar content
- Homepage only 5 conversions → optimize homepage CTAs
- "about-us" page converting → highlight it more

---

## Summary

✅ **Pretty Links** - Manage affiliate URLs in one place  
✅ **Meta Redirect** - Show countdown/warning to users  
✅ **SubID Replacer** - Track which pages drive conversions  

**Together they give you:**
- Clean, branded URLs (`/go/product`)
- User warnings before leaving site (countdown page)
- Detailed conversion tracking (SubID reporting)
- Easy link management (change once, updates everywhere)
- No manual link editing (automatic SubID replacement)

**Bottom line:** You get professional affiliate link management with detailed tracking, all automated!

---

## Quick Checklist

- [ ] Pretty Links plugin active
- [ ] Auto Meta Redirect plugin active
- [ ] SubID Replacer plugin active and enabled
- [ ] All Pretty Link targets have SubID parameters with placeholder values
- [ ] Tested one link through complete flow
- [ ] Verified SubIDs replaced on countdown page (check console)
- [ ] Final affiliate URL has correct SubIDs
- [ ] Waited 24-48 hours and checked affiliate dashboard for tracking data

---

**Need help?** See the included documentation files or check the plugin settings pages for detailed information about supported parameters and configuration options.
