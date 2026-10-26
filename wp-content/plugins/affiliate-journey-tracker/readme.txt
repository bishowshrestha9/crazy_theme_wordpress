=== Affiliate Journey Tracker ===
Contributors: DeepMind WordPress Team
Tags: affiliate, tracking, journey, Gutenberg, pretty links
Requires at least: 5.8
Tested up to: 6.6
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Track affiliate journey origins from Top Listing pages through Review pages to Buy Buttons, appending origin parameters to Site B URLs.

== Description ==

**Affiliate Journey Tracker** is a production-ready WordPress plugin designed specifically for Site A (dailyhealthsupplement.com). It tracks the visitor's journey across Top Listing pages and Individual Review pages, ensuring that the Top Listing writer receives commission credit when a visitor purchases on Site B.

### Features
* **Top Listing Detection**: Centralized `is_top_listing($post)` detection helper function.
* **Smart Cookie Management**: Automatically stores `listing_id`, `listing_slug`, and `timestamp` in `affiliate_origin` cookie for 24 hours when visiting a Top Listing. Read More / Review page visits preserve the existing cookie without overwriting it.
* **Gutenberg Buy Button Integration**: Automatically appends `origin=listing-slug` to Site B links when clicked without requiring editor changes or modifying Pretty Links structure.
* **Direct Review Support**: If a visitor lands directly on a Review page without prior Top Listing cookie, origin defaults to `direct-{review-slug}`.
* **Zero jQuery Dependency**: Built with fast, lightweight, standard Vanilla JS (ES6+).
* **Comprehensive Admin Settings**: Configure cookie expiration hours, target Site B domain pattern, debug mode, and logging options.
* **Built-in Debug Suite**: Live cookie status inspector, page context auto-detector, dynamic origin resolver preview, URL builder generator, and log viewer.

== Installation ==

1. Upload the `affiliate-journey-tracker` folder to your `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress Admin.
3. Configure settings under **Settings > Affiliate Journey Tracker**.
4. Use **Tools > Journey Tracker Debug** to inspect cookies, test URL generation, and view logs.

== Frequently Asked Questions ==

= Does this plugin touch Pretty Links? =
No. Pretty Links structure on Site A is strictly preserved. Tracking query strings are appended to the target URL before redirecting to Site B.

= What happens when a user visits multiple Top Listings? =
The cookie is automatically updated with the latest Top Listing slug, preserving the 24-hour cookie expiration window.

== Changelog ==

= 1.0.0 =
* Initial production release.
