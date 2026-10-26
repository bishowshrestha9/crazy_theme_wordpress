=== Sources & FAQ Accordion Sections ===
Contributors: Antigravity Team
Tags: sources, citations, faq, accordion, references, faqpage schema
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Pixel-accurate Sources citation cards and interactive FAQ accordion components with Gutenberg blocks (/sources, /faq), shortcodes, FAQPage JSON-LD schema, and zero jQuery dependencies.

== Description ==

Sources & FAQ Accordion Sections provides clean, accessible, academic citation and FAQ accordion components matching high-standard medical review designs.

Features:
* **Section 1: Sources Card**
  - Bold 24px heading with a clean divider
  - Generous white card with light-grey border (10px radius)
  - Custom bulleted list with clear vertical spacing
  - Automatic "Available at" link formatting in forest green (#3f7f66)
  - Full wrapping on long URLs (overflow-wrap: anywhere) with target="_blank" and rel="noopener noreferrer nofollow"
* **Section 2: FAQ Accordion**
  - Separate white cards for each question-answer pair (10px radius, subtle shadow)
  - Smooth animated height transitions using scrollHeight calculations
  - Crisp + and − toggle icons
  - "First item open by default" setting
  - "Single open mode" (closing others on click) setting
  - Automatic schema.org/FAQPage JSON-LD generation for Google Search Rich Results
* **Accessibility First**
  - Real button elements with aria-expanded and aria-controls
  - Full keyboard support (Enter, Space, ArrowUp, ArrowDown, Home, End)
  - Visible focus rings with high contrast
  - Honors prefers-reduced-motion
* **Dual Insertion**
  - Gutenberg Blocks: Insert by typing `/sources` or `/faq`
  - Shortcodes: `[sources_section]` and `[faq_section]`
* **Zero jQuery Dependency & Scoped CSS**
  - Scoped CSS under `.sf-sources-wrapper` and `.sf-faq-wrapper` with `.sf-` prefix
  - No global style leakage or !important rules

== Installation ==

1. Upload the `sources-faq-plugin` folder to your `/wp-content/plugins/` directory (or upload `sources-faq.zip` via Plugins > Add New > Upload Plugin).
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Insert by typing `/sources` or `/faq` into the Gutenberg editor, or use `[sources_section]` and `[faq_section]` shortcodes.

== Shortcodes ==

= [sources_section] =
Displays the Sources citation card.
* `heading`: Custom section heading (default: "Sources")

= [faq_section] =
Displays the FAQ accordion.
* `heading`: Custom heading (default: "How to Use Glucose Reset Metabolic Support?")
* `first_open`: "true" or "false" (default: "true")
* `single_open`: "true" or "false" (default: "true")
