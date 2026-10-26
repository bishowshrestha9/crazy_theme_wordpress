# Product Card Plugin

A dynamic WordPress plugin for creating beautiful product review cards with status-based themes (Green for Legit products, Red for Scam products).

## Features

✅ **Gutenberg Block** - Easy drag-and-drop block for the WordPress editor
✅ **Dynamic Content** - All content is fully customizable through WordPress admin
✅ **Two Status Themes** - Green theme for legit products, Red theme for scam products
✅ **Side-by-Side Layout** - Product image and details displayed side-by-side
✅ **Zoom Hover Effect** - Interactive zoom-in effect on product images
✅ **Responsive Design** - Works perfectly on all devices
✅ **Premium Aesthetics** - Modern, beautiful design with smooth animations
✅ **Custom Post Type** - Easy management through WordPress dashboard
✅ **Shortcode Support** - Also available as shortcode for flexibility
✅ **Interactive Elements** - Hover effects, animations, and click tracking
✅ **SEO Friendly** - Proper HTML structure and semantic markup

## Installation

1. Upload the `ProductCardPlugin` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You'll see a new "Product Cards" menu item in your WordPress admin

## Usage

### Creating a Product Card

1. Go to **Product Cards > Add New** in your WordPress admin
2. Enter the product title
3. Fill in all the product details:
   - **Product Status**: Choose "Legit" (green theme) or "Scam" (red theme)
   - **Review Summary Title**: Main heading for the card
   - **Benefit 1 & 2**: Key product benefits
   - **Rating**: Product rating (0-5)
   - **Number of Reviews**: Total review count
   - **Customer Testimonial**: A customer quote
   - **Price**: Product price
   - **Currency**: Currency symbol (e.g., Rs., $, €)
   - **Buy Now Link**: URL for the purchase page
4. Set a **Featured Image** for the product
5. Click **Publish**

### Displaying a Product Card

**Method 1: Using Gutenberg Block (Recommended)**

1. Edit any page or post in WordPress
2. Click the **+** button to add a new block
3. Search for "Product Card"
4. Select the **Product Card** block
5. In the block settings sidebar (right side), select your product from the dropdown
6. The card will automatically display on your page

**Method 2: Using Shortcode**

Use the shortcode with the product card ID:

```
[product_card id="123"]
```

Replace `123` with your actual product card post ID.

### Finding the Product Card ID

1. Go to **Product Cards** in your admin
2. Hover over the product card you want to use
3. Look at the URL in your browser's status bar
4. The number after `post=` is your ID

## Customization

### Theme Colors

The plugin includes two pre-built themes:

**Legit Theme (Green)**
- Background: Light green gradient
- Status badge: Green with checkmark
- Buy button: Green gradient
- Border: Light green

**Scam Theme (Red)**
- Background: Light red gradient
- Status badge: Red with X mark
- Buy button: Red gradient
- Border: Light red

### Modifying Styles

Edit `/assets/css/product-card.css` to customize:
- Colors and gradients
- Fonts and typography
- Spacing and layout
- Animations and effects

### Adding Custom JavaScript

Edit `/assets/js/product-card.js` to add:
- Custom interactions
- Analytics tracking
- Form validations
- API integrations

## Examples

### Legit Product Example

```
[product_card id="1"]
```

This will display a product card with:
- Green theme
- "LEGIT ✓" badge
- Green "Buy Now" button
- Light green background

### Scam Product Example

```
[product_card id="2"]
```

This will display a product card with:
- Red theme
- "SCAM ✗" badge
- Red "Buy Now" button
- Light red background
- Warning confirmation before purchase

## Features in Detail

### Status Badge
- Animated pulse effect
- Positioned at top-left of product image
- Clear visual indicator

### Product Image
- Supports any image size
- Automatic scaling and centering
- Hover zoom effect
- Drop shadow for depth

### Benefits Section
- Checkmark icons
- Smooth hover animations
- Clean, readable layout

### Rating Display
- Star rating visualization
- Supports half stars
- Review count display
- "Read All Reviews" link

### Testimonial
- Quote icon
- Italic styling
- Themed background
- Left border accent

### Call-to-Action
- Large, prominent price display
- Gradient button with hover effects
- Shimmer animation on hover
- Scam warning for red-themed products

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Responsive Breakpoints

- Desktop: 1000px+
- Tablet: 768px - 999px
- Mobile: < 768px
- Small Mobile: < 480px

## Developer Notes

### File Structure

```
ProductCardPlugin/
├── product-card-plugin.php (Main plugin file)
├── assets/
│   ├── css/
│   │   └── product-card.css (Styles)
│   └── js/
│       └── product-card.js (Scripts)
├── Legit.png (Status image)
├── Scam.png (Status image)
└── README.md (This file)
```

### Hooks and Filters

The plugin uses standard WordPress hooks:
- `wp_enqueue_scripts` - Load assets
- `init` - Register post type
- `add_meta_boxes` - Add admin fields
- `save_post` - Save meta data

### Custom Post Type

- **Post Type**: `product_card`
- **Slug**: `product-card`
- **Supports**: Title, Featured Image
- **Public**: Yes
- **Has Archive**: Yes

### Meta Fields

All meta fields are prefixed with `_pcp_`:
- `_pcp_status` - Product status (legit/scam)
- `_pcp_rating` - Rating value
- `_pcp_reviews_count` - Number of reviews
- `_pcp_price` - Product price
- `_pcp_currency` - Currency symbol
- `_pcp_buy_link` - Purchase URL
- `_pcp_summary_title` - Review summary heading
- `_pcp_benefit_1` - First benefit
- `_pcp_benefit_2` - Second benefit
- `_pcp_testimonial` - Customer quote

## Version History

### 1.0.0
- Initial release
- Two status themes (Legit/Scam)
- Custom post type
- Shortcode support
- Responsive design
- Interactive animations

## Support

For support, please contact your developer or visit the plugin documentation.

## License

GPL v2 or later

---

**Made with ❤️ for WordPress**
