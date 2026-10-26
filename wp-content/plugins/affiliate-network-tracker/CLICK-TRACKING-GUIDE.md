# Click Tracking Dashboard - Complete Guide

## 🎯 What It Does

This feature answers the question: **"Which click converted into a sale?"**

Every time someone clicks an affiliate link on your site, the plugin logs:
- ✅ Exact time of click
- ✅ Affiliate URL clicked
- ✅ Network name (BuyGoods, SellHealth, etc.)
- ✅ SubID (your site name)
- ✅ SubID2 (page slug where they clicked)
- ✅ Source page URL
- ✅ User's IP address

## 📋 Setup Steps

### 1. Activate the Database Table
**IMPORTANT:** You must deactivate and reactivate the plugin once:

```
WordPress Admin → Plugins → Affiliate Network Tracker
1. Click "Deactivate"
2. Click "Activate"
```

This creates the `wp_ant_clicks` database table.

### 2. Access the Dashboard
```
WordPress Admin → Affiliate Networks → Click Tracker
```

## 🔍 How to Track Conversions

### Method 1: Match SubID Values

**Step 1:** Check your affiliate dashboard
```
BuyGoods Dashboard → Reports → Sales by SubID
You see:
- Date: Feb 2, 2026 10:45 AM
- SubID: MySite
- SubID2: bio-peak-reviews  
- Product: BioStamina
- Commission: $89.00
```

**Step 2:** Go to Click Tracker
```
Affiliate Networks → Click Tracker
Filter by: Network = BuyGoods
Look for row with: SubID = MySite, SubID2 = bio-peak-reviews
```

**Step 3:** Found it!
```
Click Time: Feb 2, 2026 10:23 AM (22 minutes before sale)
Network: BuyGoods
SubID: MySite
SubID2: bio-peak-reviews
Source Page: https://mysite.com/bio-peak-reviews/
User IP: 203.45.67.89
```

**Result:** You now know the sale came from someone who read your "bio-peak-reviews" page!

### Method 2: Filter by Date & Network

**Scenario:** You have 5 BuyGoods sales today

```
1. In Click Tracker, filter:
   - Network: BuyGoods
   - Date From: Today
   - Date To: Today
   
2. You'll see all BuyGoods clicks from today

3. Compare SubID/SubID2 with your affiliate dashboard sales report

4. Match each sale to its click
```

## 📊 Dashboard Features

### Statistics Cards (Top Row)
- **Total Clicks:** All affiliate links clicked
- **Conversions:** Marked as converted (manual)
- **Conversion Rate:** Percentage that converted
- **Total Commission:** Sum of all commissions

### Filters
- **Network:** Filter by specific affiliate network
- **Status:** Pending or Converted
- **Date Range:** See clicks from specific dates

### Click Table Columns
| Column | Description | Example |
|--------|-------------|---------|
| Time | When clicked | Feb 2, 2026 10:23 AM |
| Network | Affiliate network | BuyGoods |
| Site (SubID) | Your site name | MySite |
| Page (SubID2) | Page slug | bio-peak-reviews |
| Source Page | Full URL | https://mysite.com/bio-peak-reviews/ |
| Status | Pending/Converted | ⏳ Pending |
| Commission | Amount earned | $89.00 |

## 💡 Real-World Examples

### Example 1: Multiple Products on Same Page

**Your affiliate dashboard shows:**
```
Sale 1: SubID=MySite, SubID2=weight-loss-guide → Product A ($45)
Sale 2: SubID=MySite, SubID2=weight-loss-guide → Product B ($67)
```

**In Click Tracker:**
```
Click 1: 9:15 AM - SubID=MySite, SubID2=weight-loss-guide
Click 2: 9:47 AM - SubID=MySite, SubID2=weight-loss-guide
Click 3: 10:32 AM - SubID=MySite, SubID2=weight-loss-guide
```

**Insight:** You had 3 clicks on your weight-loss-guide page, 2 converted. 66% conversion rate on that page!

### Example 2: Cross-Network Comparison

**Filter by different networks:**
```
BuyGoods: 45 clicks → Check affiliate dashboard → 3 sales = 6.7% conversion
SellHealth: 32 clicks → Check affiliate dashboard → 5 sales = 15.6% conversion
```

**Insight:** SellHealth converts better. Focus more content on SellHealth products!

### Example 3: Page Performance

**Filter clicks, group by SubID2 (page):**
```
bio-peak-reviews: 20 clicks → 2 sales = 10% conversion
testosterone-guide: 15 clicks → 0 sales = 0% conversion
```

**Insight:** "bio-peak-reviews" page performs well. "testosterone-guide" needs improvement!

## 🎯 Best Practices

### 1. Check Daily
Review clicks every morning to see which pages got traffic yesterday.

### 2. Wait 24-48 Hours
Conversions don't happen instantly. Someone might click today, buy tomorrow.

### 3. Manual Matching
Since most affiliate networks don't send conversion webhooks, you'll manually match:
1. Check affiliate dashboard for new sales
2. Note the SubID and SubID2
3. Find matching click in your tracker
4. Optionally mark as "Converted" and add commission

### 4. Export for Analysis
Copy click data to Excel/Google Sheets to track:
- Best performing pages
- Best performing networks
- Time-to-conversion patterns
- Geographic performance (by IP)

## 🔧 Technical Details

### Database Schema
```sql
CREATE TABLE wp_ant_clicks (
  id bigint(20) AUTO_INCREMENT PRIMARY KEY,
  click_time datetime,
  affiliate_url text,
  network_name varchar(100),
  subid varchar(255),
  subid2 varchar(255),
  source_page text,
  user_ip varchar(100),
  user_agent text,
  conversion_status varchar(20) DEFAULT 'pending',
  conversion_time datetime,
  product_name varchar(255),
  commission decimal(10,2)
);
```

### AJAX Endpoint
- **Action:** `ant_log_click`
- **Method:** POST
- **Endpoint:** `/wp-admin/admin-ajax.php`
- **Data:** affiliate_url, network_name, subid, subid2, source_page

### Performance
- Uses `navigator.sendBeacon()` for non-blocking logging
- Doesn't slow down page navigation
- Table indexed on click_time and conversion_status

## 🚀 Future Enhancements (Coming Soon)

1. **CSV Export:** Download all clicks as spreadsheet
2. **Auto-Detection:** Match conversions automatically via API
3. **Charts:** Visual graphs of conversion rates over time
4. **Email Alerts:** Get notified when conversion detected
5. **A/B Testing:** Compare different page versions

## ❓ FAQ

**Q: Does this track actual conversions?**
A: Not automatically. It logs clicks. You manually match clicks with sales from your affiliate dashboard.

**Q: Why not auto-match?**
A: Most affiliate networks don't provide real-time webhooks. You need to check their dashboard and match SubID values.

**Q: Can I delete old clicks?**
A: Currently manual via phpMyAdmin. Auto-cleanup coming in v2.0.

**Q: What if I have multiple sites?**
A: Each site logs to its own database. SubID (Slot 1) identifies which site.

**Q: Does this affect page speed?**
A: No. Logging happens via background beacon. Page loads normally.

## 📞 Support

Need help? Check these:
1. Make sure plugin is deactivated/reactivated (to create table)
2. Check browser console (F12) for "ANT: URL transformed" logs
3. Verify AJAX URL is correct: `console.log(antConfig.ajaxUrl)`
4. Check database: Table `wp_ant_clicks` should exist

---

**Version:** 1.0.0  
**Last Updated:** February 2, 2026
