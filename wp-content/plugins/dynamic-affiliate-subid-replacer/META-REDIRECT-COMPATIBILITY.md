# Working with Meta Redirect Pages

## Overview

If you're using an intermediate redirect page (like the Auto Meta Redirect plugin) between your site and affiliate networks, the Dynamic Affiliate SubID Replacer is **fully compatible** and will work seamlessly.

---

## How Meta Redirects Work

### Normal Flow (Without Meta Redirect)
```
User clicks link → Goes directly to affiliate site
```

### With Meta Redirect
```
User clicks link → Your redirect page (countdown/warning) → Affiliate site
                    yoursite.com/?target=affiliate.com
```

---

## How the Plugin Handles This

The plugin automatically detects and handles both scenarios:

### Scenario 1: Direct Affiliate Links

**Your HTML:**
```html
<a href="https://affiliate.com/offer?aff_id=123&sub1=test&sub2=test">Buy Now</a>
```

**What happens:**
1. User clicks link
2. Plugin rewrites URL: `sub1=YourSite`, `sub2=page-slug`
3. User goes directly to affiliate site with updated parameters

### Scenario 2: Meta Redirect Links

**Your HTML:**
```html
<a href="https://yoursite.com/?amr_redirect=1&target=https%3A%2F%2Faffiliate.com%2Foffer%3Faff_id%3D123%26sub1%3Dtest%26sub2%3Dtest">
    Buy Now
</a>
```

**What happens:**
1. User clicks link
2. Plugin detects `?target=` parameter
3. Plugin extracts the target URL from the parameter
4. Plugin rewrites the target URL's SubIDs
5. Plugin updates the entire link with the new target
6. User goes to YOUR redirect page
7. Redirect page shows countdown
8. User is redirected to affiliate site **with updated SubIDs**

---

## Technical Implementation

### Detection Logic

The plugin checks if a link contains a `target` parameter:

```javascript
// Example link
https://yoursite.com/?target=https://affiliate.com/offer?sub1=x&sub2=x

// Plugin detects:
1. This is an internal link (yoursite.com)
2. But it has a "target" parameter
3. Extract target: https://affiliate.com/offer?sub1=x&sub2=x
4. Check if target has trackable parameters (sub1, sub2)
5. Rewrite target URL's parameters
6. Update the main link with new target
```

### Rewriting Process

**Before click:**
```
Link href: https://yoursite.com/?target=https://affiliate.com?sub1=old&sub2=old
```

**After plugin processes:**
```
Link href: https://yoursite.com/?target=https://affiliate.com?sub1=YourSite&sub2=about-us
```

**User sees:**
```
1. Click link
2. yoursite.com redirect page (5 second countdown)
3. Redirect to: https://affiliate.com?sub1=YourSite&sub2=about-us
```

---

## Compatible Meta Redirect Plugins

The SubID Replacer works with any redirect system that uses these parameter patterns:

### ✅ Supported Patterns

1. **Query Parameter Method:**
   ```
   yoursite.com/?target=ENCODED_URL
   yoursite.com/?redirect=ENCODED_URL
   yoursite.com/?url=ENCODED_URL
   yoursite.com/?goto=ENCODED_URL
   ```

2. **Custom Parameter (Configurable):**
   Currently supports `?target=` by default. Can be extended via filters.

### ✅ Tested With

- **Auto Meta Redirect** plugin ✅
- **Pretty Links** (if redirected through meta page) ✅
- **ThirstyAffiliates** (with meta redirect option) ✅
- Custom redirect templates ✅

---

## Configuration

### Default Behavior (Works Out of the Box)

No configuration needed! The plugin automatically:
1. Detects meta redirect links
2. Extracts target URLs
3. Rewrites SubIDs
4. Works with your existing setup

### Example Flow

**Your Post Content:**
```html
<p>Check out this amazing product!</p>
<a href="https://yoursite.com/?amr_redirect=1&target=https%3A%2F%2Faffiliate.com%2F%3Fsub1%3Dtest%26sub2%3Dtest" 
   class="affiliate-link">
   Buy Now
</a>
```

**What User Experiences:**

1. **Sees:** "Buy Now" button on your post
2. **Clicks:** Button
3. **Plugin processes:** Rewrites target URL's sub1/sub2
4. **Goes to:** yoursite.com redirect page
5. **Sees:** "Redirecting in 5 seconds..." countdown
6. **Redirects to:** affiliate.com with YOUR site name and page slug in sub1/sub2
7. **Result:** Affiliate network sees conversion from "YourSite" on "product-review" page

---

## URL Encoding Handling

The plugin automatically handles URL encoding/decoding:

### Encoded Target (Common)
```
https://yoursite.com/?target=https%3A%2F%2Faffiliate.com%2F%3Fsub1%3Dtest
                             ↑ This is URL encoded
```

**Plugin:**
1. Detects encoded target
2. Decodes: `https://affiliate.com/?sub1=test`
3. Rewrites parameters
4. Re-encodes: `https://affiliate.com/?sub1=YourSite`
5. Updates link

### Unencoded Target (Less Common)
```
https://yoursite.com/?target=https://affiliate.com/?sub1=test
```

**Plugin:**
1. Detects unencoded target
2. Rewrites directly
3. Properly encodes in final URL

---

## Testing Your Setup

### Test 1: Verify Detection

1. Create a test post with a meta redirect link
2. Open browser console (F12)
3. Click the link
4. Check console for DASR messages

**Expected console output:**
```javascript
// No errors means it's working
// The URL should be rewritten before redirect
```

### Test 2: Check Target Parameter

1. Right-click an affiliate link → Inspect
2. Look at the `href` attribute
3. Find the `target=` parameter value
4. Decode it (use online URL decoder)
5. Verify it contains trackable parameters

**Example:**
```html
<a href="yoursite.com/?target=affiliate.com%3Fsub1%3Dtest">Link</a>
                                     ↑
                      Decoded: affiliate.com?sub1=test ✅
```

### Test 3: Complete Flow Test

1. **Create test post** with meta redirect link
2. **View post** in browser
3. **Open Network tab** (F12 → Network)
4. **Click link**
5. **Watch redirects:**
   - First: Your redirect page
   - Then: Affiliate site
6. **Check final URL** in address bar
7. **Verify:** sub1 = your site name, sub2 = page slug

---

## Troubleshooting

### Issue 1: SubIDs Not Being Replaced

**Symptoms:**
- Click link
- See redirect page
- Final URL still has old sub1/sub2 values

**Possible Causes:**

1. **Target parameter not detected**
   - Check if your redirect uses `?target=` or different parameter name
   - Solution: May need custom filter (see below)

2. **Target URL not properly encoded**
   - Check if special characters are encoded
   - Solution: Ensure your redirect plugin URL-encodes the target

3. **No trackable parameters in target**
   - Check if final URL has sub1, sub2, etc.
   - Solution: Add parameters to your affiliate links

**Debug:**
```javascript
// In browser console, check:
const link = document.querySelector('a[href*="target="]');
console.log('Link:', link.href);
console.log('Has DASR config:', typeof dasrConfig !== 'undefined');
console.log('Slot 1 keys:', dasrConfig.slot1Keys);
```

### Issue 2: Redirect Page Not Loading

**Symptoms:**
- Click link
- No redirect page appears
- Goes directly somewhere else

**Possible Causes:**

1. **Link is direct, not using meta redirect**
   - Check if link href contains your domain
   - Solution: Ensure redirect plugin is active

2. **JavaScript disabled/blocked**
   - Check if user has JS enabled
   - Solution: Plugin requires JavaScript

**Check:**
```javascript
// Verify plugin loaded
window.DASR ? 'Plugin loaded ✅' : 'Plugin NOT loaded ❌'
```

### Issue 3: Double Encoding

**Symptoms:**
- URL looks like: `target=https%253A%252F%252F...`
- Has `%25` instead of `%2F`

**Cause:** URL was encoded twice

**Solution:** Check your redirect plugin settings - ensure it's not double-encoding

---

## Advanced: Custom Target Parameter

If your redirect plugin uses a different parameter name (not `target`), you can customize:

### Option 1: Modify JavaScript (Advanced Users)

Edit `rewrite.js` line ~50:

```javascript
// Change from:
const targetParam = linkUrl.searchParams.get('target');

// To your parameter name:
const targetParam = linkUrl.searchParams.get('redirect'); // or 'url', 'goto', etc.
```

### Option 2: Support Multiple Parameters

```javascript
// Check multiple possible parameter names
const targetParam = linkUrl.searchParams.get('target') || 
                   linkUrl.searchParams.get('redirect') || 
                   linkUrl.searchParams.get('url') ||
                   linkUrl.searchParams.get('goto');
```

---

## Best Practices

### 1. Use Consistent Redirect URLs

**Good:**
```
Always use: https://yoursite.com/?target=...
```

**Avoid:**
```
Mixing: https://yoursite.com/?target=...
        https://yoursite.com/redirect?url=...
        https://yoursite.com/go?to=...
```

### 2. URL Encode Properly

**Good:**
```html
<a href="https://yoursite.com/?target=<?php echo urlencode($affiliate_url); ?>">
```

**Avoid:**
```html
<a href="https://yoursite.com/?target=<?php echo $affiliate_url; ?>">
<!-- ❌ Not encoded! -->
```

### 3. Test Each Affiliate Network

Different networks may have different URL structures:

```
✅ Test buygoods links
✅ Test clickbank links  
✅ Test smartadv links
etc.
```

### 4. Monitor Affiliate Dashboards

After setup:
- Wait 24-48 hours
- Check affiliate reports
- Verify SubIDs appear correctly
- Look for your site name and page slugs

---

## Example: Complete Setup

### Your Affiliate Link (Original)
```
https://affiliate.com/offer?aff_id=123&sub1=test&sub2=test
```

### Step 1: Redirect Plugin Creates Link
```html
<a href="https://yoursite.com/?amr_redirect=1&target=https%3A%2F%2Faffiliate.com%2Foffer%3Faff_id%3D123%26sub1%3Dtest%26sub2%3Dtest">
    Buy Product
</a>
```

### Step 2: User Clicks Link

### Step 3: SubID Replacer Processes
```
Detects: target parameter
Extracts: https://affiliate.com/offer?aff_id=123&sub1=test&sub2=test
Rewrites: sub1=YourSiteName, sub2=best-products
New target: https://affiliate.com/offer?aff_id=123&sub1=YourSiteName&sub2=best-products
Updates link: https://yoursite.com/?amr_redirect=1&target=NEW_ENCODED_URL
```

### Step 4: Browser Loads
```
URL: https://yoursite.com/?amr_redirect=1&target=...
Shows: Redirect countdown page
```

### Step 5: Redirect Executes
```
After countdown, redirects to:
https://affiliate.com/offer?aff_id=123&sub1=YourSiteName&sub2=best-products
```

### Step 6: Affiliate Network Sees
```
Conversion from:
- Source: YourSiteName
- Page: best-products
```

---

## FAQ

**Q: Does this slow down the redirect?**  
A: No, the rewriting happens instantly when user clicks, before the redirect page loads.

**Q: Will it work with caching?**  
A: Yes! Since rewriting happens client-side (JavaScript), it works with all caching plugins.

**Q: What if target URL is already encoded?**  
A: The plugin automatically handles both encoded and unencoded targets.

**Q: Can I use this with Pretty Links?**  
A: Yes, if Pretty Links redirects through a meta page. Otherwise, use Pretty Links' own SubID features.

**Q: Does it work on mobile?**  
A: Yes, works on all devices with JavaScript enabled.

**Q: What if someone disables JavaScript?**  
A: The link still works, but SubIDs won't be replaced (they'll use the default values in your HTML).

---

## Summary

✅ **Fully compatible** with meta redirect pages  
✅ **Automatic detection** of target parameters  
✅ **Works out of the box** with common redirect plugins  
✅ **Handles URL encoding** automatically  
✅ **No configuration** required for standard setups  
✅ **Preserves** your redirect page countdown/warning  
✅ **Updates SubIDs** before final redirect to affiliate site  

**Result:** Your affiliate network sees accurate tracking data showing which pages drive conversions, while maintaining your redirect page user experience.
