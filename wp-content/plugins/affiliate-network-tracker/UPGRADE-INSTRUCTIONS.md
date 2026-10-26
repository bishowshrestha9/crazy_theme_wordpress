# Upgrade Instructions - Click Tracking Feature

## What's New
This update adds a **Click Tracking Dashboard** that logs every affiliate link click and helps you match conversions with your affiliate dashboard reports.

## Activation Required
To enable click tracking, you need to **deactivate and reactivate** the plugin:

1. Go to **Plugins** in WordPress admin
2. Find **Affiliate Network Tracker**
3. Click **Deactivate**
4. Click **Activate** again

This will create the database table needed for click tracking.

## New Features

### 📊 Click Tracking Dashboard
- **Location:** Affiliate Networks → Click Tracker
- **Features:**
  - View all affiliate link clicks in real-time
  - See which site and page each click came from
  - Track SubID and SubID2 values for each click
  - Filter by network, date range, and conversion status
  - Statistics: Total clicks, conversions, conversion rate, commission

### 🔍 How to Match Conversions

1. **Wait for a conversion** in your affiliate dashboard
2. **Find the sale:** Go to affiliate network → Reports → Sales by SubID
3. **Note the SubID values:** e.g., SubID="MySite", SubID2="product-review"
4. **Search in Click Tracker:** Go to Affiliate Networks → Click Tracker
5. **Find matching click:** Look for the row with matching SubID and SubID2
6. **See details:** You'll see which page the click came from, when it happened, and visitor info

### Example Workflow

**Affiliate Dashboard Shows:**
- Network: BuyGoods
- Date: Feb 2, 2026
- SubID: MySite
- SubID2: bio-peak-reviews
- Commission: $89.00

**In Click Tracker:**
- Filter by: Network = BuyGoods
- Find row with: SubID = MySite, SubID2 = bio-peak-reviews
- Result: Click came from https://mysite.com/bio-peak-reviews/ at 10:23 AM

### Manual Tracking (Optional)
You can export clicks as CSV and match them with your affiliate dashboard reports in Excel/Google Sheets.

## Database Details
Table created: `wp_ant_clicks`
Columns: click_time, affiliate_url, network_name, subid, subid2, source_page, user_ip, user_agent, conversion_status, conversion_time, product_name, commission

## Support
If you have any issues, deactivate/reactivate the plugin or contact support.
