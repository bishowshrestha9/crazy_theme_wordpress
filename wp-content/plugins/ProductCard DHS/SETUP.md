# Product Card Plugin - Setup Guide

## Installation Instructions

### 1. Activate the Plugin
1. Go to your WordPress admin dashboard
2. Navigate to **Plugins → Installed Plugins**
3. Find "Product Card Plugin"
4. Click **Activate**

### 2. Build the Gutenberg Block (Required for Block Editor)

The plugin needs to compile the JavaScript for the Gutenberg block. Run these commands:

```bash
cd /Users/bishowshrestha/Local\ Sites/redirect/app/public/wp-content/plugins/ProductCardPlugin
npm install
npm run build
```

This will create the `build/` directory with compiled assets.

### 3. Using the Plugin

#### Option A: Gutenberg Block (Recommended)
1. Edit any page or post in WordPress
2. Click the **+** button to add a new block
3. Search for "Product Card"
4. Click to insert the block
5. Use the **right sidebar** to customize all fields:
   - Product Name
   - Status (Legit/Scam)
   - Image
   - User Experience
   - Our Observation
   - Verdict
   - Best For
   - Expert Rating
   - Certification
   - Money Back Guarantee
   - Price & Original Price
   - CTA Button

#### Option B: Shortcode
You can also use the shortcode (legacy):

```
[product_card 
    status="legit"
    productName="Gutoptim"
    imageUrl="https://example.com/image.jpg"
    userExperience="Fewer night bathroom trips, stronger urine flow, and better sleep quality"
    observation="Confirms prostate support via DHT inhibition"
    verdict="Gutoptim is a Genuine Prostate Support Supplement"
    bestFor="People over 50 with Urinary Track Infection"
    rating="9.5"
    certification="FDA/GMP Approved"
    moneyBackGuarantee="60 Days"
    price="69"
    originalPrice="99"
    currency="$"
    ctaLabel="Buy Now"
    ctaUrl="https://example.com/buy"
]
```

## Default Dummy Data (Gutoptim)

The block comes pre-filled with Gutoptim product data:

- **Product Name**: Gutoptim
- **Status**: Legit (Green badge)
- **Image**: Professional supplement bottle
- **User Experience**: Fewer night bathroom trips, stronger urine flow, and better sleep quality
- **Observation**: Confirms prostate support via DHT (5-alpha reductase) inhibition and anti-inflammatory action
- **Verdict**: Gutoptim is a Genuine Prostate Support Supplement
- **Best For**: People over 50 with Urinary Track Infection
- **Expert Rating**: 9.5/10
- **Certification**: FDA/GMP Approved
- **Money Back Guarantee**: 60 Days
- **Price**: $69 (was $99)

## Customization

### Card Layout
- **Desktop**: Two columns (image left, content right)
- **Mobile**: Stacked (image top, content bottom)
- **Max Width**: 1100px, centered
- **Image Hover**: 1.06x zoom effect

### Typography
- **Title**: 30-32px, bold
- **Body**: 18-19px
- **Section Labels**: 16px, uppercase
- **CTA Button**: 18-19px, min-height 56px

### Status Badges
- **Legit**: Green gradient with shadow
- **Scam**: Red gradient with shadow

## Viewing the Demo

Open `demo.html` in your browser to see both status examples:
```
/wp-content/plugins/ProductCardPlugin/demo.html
```

## Troubleshooting

### Block doesn't appear in editor
1. Make sure you ran `npm install` and `npm run build`
2. Clear WordPress cache
3. Refresh the editor page

### Styles not loading
1. Check that CSS file exists: `assets/css/product-card.css`
2. Clear browser cache
3. Check WordPress > Settings > General > WordPress Address matches your site

### Build errors
If you get errors during `npm run build`:
```bash
# Remove node_modules and try again
rm -rf node_modules package-lock.json
npm install
npm run build
```

## Support

For issues or feature requests, check the plugin documentation or contact support.

---

**Version**: 2.0.0  
**Last Updated**: January 19, 2026
