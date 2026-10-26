=== Supplement Review ===
Contributors: antigravity
Tags: blog, custom-background, custom-colors, custom-header, custom-logo, custom-menu, editor-style, featured-images, full-width-template, rtl-language-support, sticky-post, theme-options, threaded-comments, translation-ready
Requires at least: 6.4
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A production-ready, high-performance, accessible WordPress theme for health supplement editorial evaluations, clinical reviews, and affiliate comparison sites.

== Description ==

Supplement Review is a classic PHP theme engineered specifically for health, wellness, and dietary supplement review publications. It provides a complete editorial layout matching modern high-converting review websites without relying on heavy page builders, external icon fonts, or mandatory third-party plugins.

Key features include:
* **Server-side 3-Column Table of Contents:** Automatically generates unique heading IDs with customizable group labels (e.g. OVERVIEW, THE FORMULA, BUYING GUIDE).
* **IntersectionObserver-Powered Sticky Progress Bar:** Automatically tracks reading progress with zero scroll event overhead and an accessible sections dropdown.
* **Sticky Product Summary Card:** Showcases product imagery, gold star ratings, dynamic editorial score progress bars, auto-calculated discount percentages, verified verdicts, and affiliate CTA buttons.
* **Gutenberg Block Patterns:** Ready-to-use patterns for Quick Verdict, Review Summary, Pros & Cons, Key Ingredients Grid, Rating Tables, and HTML5 FAQ Accordions.
* **Verified Customer Review Form:** Integrated with the native WordPress comment system featuring star rating selectors, honeypot spam traps, time-delay verification, IP rate limiting, and optional photo uploads.
* **SEO & JSON-LD Structured Data:** Generates BreadcrumbList, Product, Review, MedicalWebPage, and FAQPage schemas safely. Output of AggregateRating strictly reflects real approved user reviews.
* **Native Dark Mode:** High-contrast dark theme with pre-paint script to eliminate flash of unstyled theme (FOUC) and localStorage preference sync.
* **WCAG 2.1 AA Compliant:** Accessible focus indicators, keyboard navigation, screen reader landmarks, and contrast ratios exceeding 4.5:1.
* **Zero jQuery Dependency:** 100% modern vanilla ES6+ JavaScript under 30 KB total, and modular CSS under 60 KB.

== Installation ==

1. Download the `supplement-review` theme directory.
2. In your WordPress admin dashboard, navigate to **Appearance > Themes > Add New > Upload Theme**.
3. Choose the zip file or copy the folder into your `wp-content/themes/` directory.
4. Click **Activate**.
5. Configure your site logo, announcement bar, and trust badges via **Appearance > Customize**.

== Security & OWASP Top 10 Measures ==

This theme has been architected to meet rigorous OWASP Top 10 security standards:

* **A01 Broken Access Control:** Strict `current_user_can()` capability checks on every administrative action and metadata save handler.
* **A02 Cryptographic Failures:** Built exclusively on native WordPress hashing and security APIs (`wp_hash`, `wp_verify_nonce`). No custom cryptographic routines.
* **A03 Injection:** 100% escaping on all template outputs (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`), strict input sanitization, and no raw SQL or `eval()` statements.
* **A04 Insecure Design:** Rate limiting per IP on review submissions, honeypot spam protection, time-based bot mitigation, and mandatory privacy consent.
* **A05 Security Misconfiguration:** `if ( ! defined( 'ABSPATH' ) ) exit;` guards at the top of every PHP file. Directory browsing blocked via silence `index.php` files in every subdirectory. Core emoji scripts and generator meta tags cleanly stripped.
* **A06 Vulnerable Components:** Zero third-party JavaScript or CSS libraries bundled. No jQuery dependency, no external font CDNs, no external tracking scripts.
* **A07 Authentication Failures:** Completely relies on core WordPress authentication and user roles.
* **A08 Software/Data Integrity:** Fonts (Inter & Montserrat) are self-hosted in WOFF2 format locally in `assets/fonts/` for strict GDPR compliance and zero external render-blocking network requests.
* **A09 Logging:** Sensitive credentials and PII are never logged.
* **A10 SSRF:** Server never makes outbound network requests based on arbitrary user input.
* **File Upload Security:** Customer review image uploads are restricted to `jpg`, `png`, and `webp` only, capped at 2 MB, verified via `wp_check_filetype_and_ext()`, and stored securely through the WordPress Media Library.
* **Affiliate Link Safety:** All external and merchant links are marked with `rel="noopener noreferrer sponsored nofollow"` and `target="_blank"`.

== Fonts & Assets License ==

* Fonts: Inter and Montserrat are open-source typefaces licensed under the SIL Open Font License, Version 1.1 (https://openfontlicense.org/).
* SVGs: Custom crafted inlined SVG symbols licensed under GNU General Public License v2 or later.

== Changelog ==

= 1.0.0 =
* Initial production release.
* Added 3-column Table of Contents and sticky IntersectionObserver progress bar.
* Added sticky product review scorecard with customizable editorial score progress bars.
* Added native review submission system with star ratings and secure media uploads.
* Added dark mode toggle with zero-FOUC inline pre-paint script.
* Added Gutenberg block patterns and block styles.
* Added complete JSON-LD Structured Data engine.
