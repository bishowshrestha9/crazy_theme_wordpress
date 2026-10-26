=== Write a Review Form ===
Contributors: Antigravity Team
Tags: review form, write a review, reviews, customer feedback, review submission
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Pixel-accurate "Write a Review" submission form replicating screenshot specifications with AJAX, secure file upload, Custom Post Type storage, and an admin moderation queue.

== Description ==

Write a Review Form provides a modern, pixel-accurate review submission experience for your WordPress site.

Features:
* Pixel-accurate design matching specifications:
  - Section heading "Write a Review" with a subtle divider
  - Review Headline input
  - Two-column responsive Name & Email row
  - Review Headline textarea with exact placeholder
  - Bordered attachment file upload input
  - Full-width soft green submit button (#6fa68f)
  - Privacy policy agreement checkbox
  - Legal disclaimer with Review Guidelines and Terms of Use links
* Dual insertion: Use shortcode `[review_form]` or the Gutenberg block (type `/review form`).
* Asynchronous AJAX submission with graceful no-JS fallback.
* Custom Post Type `user_review` saves submissions as `pending`.
* Built-in Admin Moderation Queue (`User Reviews > Moderation Queue`) to approve, reject, or delete submissions.
* Top-tier security: Nonce verification, capabilities checks, strict MIME & file extension validation, 2MB size cap, rate limiting per IP, honeypot anti-spam, and zero public exposure of submitter emails.
* Lightweight & decoupled: No jQuery dependency, scoped CSS under `.rf-form-wrapper`, no CSS framework.

== Installation ==

1. Upload the `review-form` folder to your `/wp-content/plugins/` directory (or upload `review-form.zip` via Plugins > Add New > Upload Plugin).
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add the shortcode `[review_form]` or insert the Gutenberg block by typing `/review form` into any post or page.

== Frequently Asked Questions ==

= How do I moderate submitted reviews? =
Navigate to `User Reviews > Moderation Queue` in your WordPress dashboard to approve, reject, or permanently delete submitted reviews.

= What image formats can users upload? =
Only JPG, PNG, and WEBP image files under 2 MB are permitted. Executable and non-image files are strictly blocked.
