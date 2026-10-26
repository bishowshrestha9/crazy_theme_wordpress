# Quick Start Guide - Product Card Plugin

## 🚀 Installation Steps

### Step 1: Activate the Plugin
1. Go to your WordPress admin dashboard
2. Navigate to **Plugins > Installed Plugins**
3. Find "Product Card Plugin"
4. Click **Activate**

### Step 2: Create Your First Product Card

1. In your WordPress admin, go to **Product Cards > Add New**
2. Enter a title (e.g., "Vivalis Pro Pills")
3. Fill in the product details:

#### Required Fields:
- **Product Status**: Choose "Legit" or "Scam"
- **Review Summary Title**: "Vivalis Pro Pills Review Summary:"
- **Rating**: 4.2
- **Number of Reviews**: 1451
- **Price**: 3000
- **Currency**: Rs.
- **Buy Now Link**: https://example.com/buy

#### Optional Fields:
- **Benefit 1**: "Customers report boosted water pressure, softer skin & hair, and easy installation."
- **Benefit 2**: "Experts confirm the filter effectively removes chlorine and enhances water quality."
- **Customer Testimonial**: "A worthwhile upgrade for homes dealing with hard water."

4. Set a **Featured Image** (click "Set featured image" in the right sidebar)
5. Click **Publish**

### Step 3: Display the Product Card

1. After publishing, note the Post ID (shown in the URL or in the product cards list)
2. Add the shortcode to any page or post:
   ```
   [product_card id="YOUR_POST_ID"]
   ```
3. Replace `YOUR_POST_ID` with the actual ID

## 📝 Example Shortcodes

### Legit Product (Green Theme)
```
[product_card id="1"]
```

### Scam Product (Red Theme)
```
[product_card id="2"]
```

## 🎨 Customization Tips

### Change Colors
Edit `assets/css/product-card.css`:
- Line 27-30: Legit theme background
- Line 37-40: Scam theme background
- Line 64-68: Legit status badge
- Line 70-74: Scam status badge

### Modify Animations
Edit `assets/js/product-card.js`:
- Add custom click handlers
- Modify animation timings
- Add analytics tracking

## 🔧 Troubleshooting

### Product card not displaying?
1. Check that the plugin is activated
2. Verify the post ID is correct
3. Ensure the post type is "product_card"
4. Clear your cache

### Styles not loading?
1. Hard refresh your browser (Cmd+Shift+R on Mac, Ctrl+Shift+R on Windows)
2. Check if CSS file exists at `wp-content/plugins/ProductCardPlugin/assets/css/product-card.css`
3. Verify file permissions

### Images not showing?
1. Make sure you've set a Featured Image
2. Check image file permissions
3. Verify the image URL is accessible

## 💡 Pro Tips

1. **Use High-Quality Images**: Product images should be at least 600x800px for best results
2. **Keep Benefits Concise**: 2-3 lines maximum for each benefit
3. **Honest Reviews**: Use the scam theme responsibly for genuinely problematic products
4. **Mobile Testing**: Always test on mobile devices
5. **A/B Testing**: Try different testimonials and benefits to see what converts better

## 📱 Responsive Design

The plugin automatically adjusts for:
- **Desktop**: Full two-column layout
- **Tablet**: Stacked layout with optimized spacing
- **Mobile**: Single column, touch-friendly buttons

## 🎯 Best Practices

### For Legit Products:
- Use genuine customer testimonials
- Include specific, measurable benefits
- Link to a secure checkout page
- Keep rating honest (3.5-5.0 range)

### For Scam Products:
- Document specific complaints
- Include warning signs
- Use low ratings (0-2.5 range)
- Consider adding a warning modal (already built-in)

## 📊 Tracking & Analytics

The plugin includes basic click tracking in the console. To add Google Analytics:

1. Edit `assets/js/product-card.js`
2. Find the "Buy Now" click handler (line 28)
3. Add your GA tracking code:
   ```javascript
   gtag('event', 'purchase_click', {
       'product_status': status,
       'product_id': productCard.data('id')
   });
   ```

## 🆘 Support

For issues or questions:
1. Check the README.md file
2. Review the demo.html for examples
3. Contact your developer

## 📦 File Structure

```
ProductCardPlugin/
├── product-card-plugin.php  (Main file - DO NOT DELETE)
├── assets/
│   ├── css/
│   │   └── product-card.css (Styles)
│   └── js/
│       └── product-card.js  (Interactions)
├── demo.html               (Preview file)
├── README.md              (Documentation)
└── QUICK_START.md         (This file)
```

## ✅ Checklist

Before going live:
- [ ] Plugin activated
- [ ] Test product card created
- [ ] Featured image set
- [ ] All fields filled in
- [ ] Shortcode added to page
- [ ] Tested on desktop
- [ ] Tested on mobile
- [ ] Buy link verified
- [ ] Cache cleared
- [ ] Live preview checked

---

**You're all set! 🎉**

Your product cards are now ready to convert visitors into customers!
