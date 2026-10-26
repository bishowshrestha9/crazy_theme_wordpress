# Product Card Plugin v2.0 - Gutenberg Block

## ✅ Complete Redesign

The plugin has been completely redesigned to work as a **standalone Gutenberg block** where all content is editable directly in the WordPress editor sidebar.

## 🎯 How It Works

### Adding a Product Card:

1. **Edit any Post or Page** in WordPress
2. Click the **+** button to add a new block
3. Search for **"Product Card"**
4. Select the block
5. **All settings appear in the right sidebar** (Inspector Controls)

### No More Custom Post Type!

- ❌ No need to create separate "Product Card" posts
- ❌ No need to select from a dropdown
- ✅ Everything is configured directly in the block sidebar
- ✅ Each post/page can have multiple product cards with different settings

## 📝 Sidebar Settings (Inspector Controls)

### 1. Product Settings
- **Product Status**: Legit (Green) or Scam (Red)
- **Product Title**: Name of the product
- **Summary Title**: Main heading for the card

### 2. Product Image
- **Upload Image**: Click to upload product image
- **Change Image**: Replace existing image
- **Remove Image**: Delete the image

### 3. Benefits
- **Add Benefit**: Click to add unlimited benefits
- **Remove Benefit**: Delete individual benefits
- Each benefit has its own text area

### 4. Rating & Reviews
- **Rating**: Slider from 0-5 (supports decimals like 4.2)
- **Number of Reviews**: Total review count
- **Customer Testimonial**: Quote from a customer

### 5. Price & Purchase
- **Currency Symbol**: $, Rs., €, etc.
- **Price**: Product price
- **Buy Now Link**: URL for purchase

## 🎨 Design Features

✅ **Horizontal Layout** - Image on left, content on right
✅ **94% Width** - 3% margins on each side
✅ **White Background** - Only colored borders (no fill)
✅ **280px Image** - Large, prominent product image
✅ **Zoom Hover Effect** - Image scales 1.15x on hover
✅ **Unlimited Benefits** - Add as many as needed
✅ **Responsive** - Works on all devices

## 🚀 Usage Example

```
1. Create a new post
2. Add the "Product Card" block
3. In the sidebar, set:
   - Status: Legit
   - Product Title: Vivalis Pro Pills
   - Summary Title: Vivalis Pro Pills Review Summary:
   - Upload an image
   - Add benefits (as many as you want)
   - Set rating: 4.2
   - Reviews: 1451
   - Add testimonial
   - Price: 3000
   - Currency: Rs.
   - Buy Link: https://example.com
4. Publish!
```

## 📂 File Structure

```
ProductCardPlugin/
├── product-card-plugin.php  (Main plugin - Gutenberg block registration)
├── assets/
│   ├── css/
│   │   └── product-card.css (Horizontal layout styles)
│   └── js/
│       ├── product-card.js  (Frontend interactions)
│       └── block.js         (Gutenberg block with sidebar controls)
├── demo.html
├── README.md
└── QUICK_START.md
```

## 🔄 Changes from v1.0

| v1.0 (Old) | v2.0 (New) |
|------------|------------|
| Custom Post Type | Gutenberg Block |
| Create product → Select in post | Add block → Configure in sidebar |
| Limited to 2 benefits | Unlimited benefits |
| Shortcode: `[product_card id="123"]` | Block: Add via Gutenberg |
| Vertical centered layout | Horizontal side-by-side layout |

## ⚡ Key Advantages

1. **Faster Workflow** - No need to switch between screens
2. **More Flexible** - Add multiple cards per post with different settings
3. **Better UX** - All controls in one place (sidebar)
4. **Unlimited Benefits** - Add/remove as many as needed
5. **Visual Feedback** - See preview while editing

## 🎯 Perfect For

- Product review sites
- Affiliate marketing
- E-commerce comparisons
- Scam alert websites
- Product recommendation blogs

---

**Version**: 2.0.0  
**Last Updated**: 2026-01-18  
**Compatibility**: WordPress 5.0+, Gutenberg Editor
