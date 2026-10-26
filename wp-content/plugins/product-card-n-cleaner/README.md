# Product Card 'n' Cleaner WordPress Plugin

A companion WordPress plugin designed to scan, inspect, preview, and clean unwanted trailing `.n` or `n` characters appended after sentence fullstops in Product Card blocks across all WordPress posts and pages.

## Features

1. **Comprehensive Deep Scanner**:
   - Scans all public, private, and draft posts & pages for Product Card blocks (`product-card-plugin/product-card`).
   - Specifically checks fields:
     - **User Experience** (`userExperience`)
     - **Verdict** (`verdict`)
     - **Feature Bullet Points** (`expertReview1`, `expertReview2`, `expertReview3`)

2. **Live Side-by-Side Card Preview**:
   - Interactive modal rendering exact before/after card previews.
   - Visually highlights `.n` badges in red on the "Before" preview.

3. **One-Click Single Fix & Bulk Action**:
   - Individual "Remove 'n'" button for instant row updating.
   - Bulk "Fix All Selected Posts" with batching and progress tracking.

4. **Safety & Restoration**:
   - Automatic `_pcpc_content_backup` meta stored before any post edit.
   - Includes a 1-click **"Restore Original"** button to revert content if needed.

5. **Frontend Output Safeguard**:
   - Includes a dynamic `render_block` filter to automatically strip trailing `.n` on the frontend as an extra safety net.

## Installation

1. Copy or upload the `product-card-n-cleaner` folder to `/wp-content/plugins/` on your WordPress site.
2. Activate **Product Card 'n' Cleaner** from **WP Admin > Plugins**.
3. Access the dashboard from **Tools > Product Card 'n' Cleaner** or the main sidebar menu item **Card 'n' Cleaner**.
4. Click **"Scan Site Now"**.
