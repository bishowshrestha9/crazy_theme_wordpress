# Caution Card Plugin

Gutenberg block that replaces a discontinued product card with a high-alert caution card.

## Setup
1. Activate the plugin.
2. Go to **Settings → Caution Card** (optional global defaults).
3. Edit a post and add the **Caution Card** block near your product card.
4. Fill the block sidebar fields (content + links) and publish.

## Block & Shortcode
- **Block:** Add the "Caution Card" block and fill sidebar fields.
- **Detection:** The frontend script watches `.pcpx-cta-button`. If the `href` is empty, `#`, or invalid, it hides `.pcpx-product-card-outer` and shows the caution card in its place.
- **Placement:** Insert the block near the product card you want to replace.
- **Shortcode:** `[caution_card]` still works (uses settings defaults).

## ACF Integration
The card uses the ACF field `product_name` from the current post (if available) to show the discontinued product name.
