# Top 10 Plugin

A comprehensive WordPress plugin for creating dynamic "Top 10" product showcase pages with a powerful admin interface.

## Features

- **Custom Post Types**: Separate post types for products and Top 10 pages
- **Rich Product Data**: Specifications, features, pros/cons, ratings, pricing, badge text
- **Dynamic Page Builder**: Select and order up to 10 products per page
- **Platform Ratings**: Amazon, Trustpilot, Google/BBB ratings per product
- **Beautiful Templates**: Responsive design matching Figma specifications
- **Interactive Elements**: FAQ accordions, carousel navigation, animated rating bars
- **Flexible Content**: Buying guide, research methodology, conclusion - all admin-editable
- **Complete Admin Control**: All text content editable from WordPress dashboard
- **Section Toggles**: Show/hide comparison tables, FAQs, and buying guides
- **Admin-Friendly**: Intuitive meta boxes with repeatable fields

## Installation

1. Upload the `Top10Plugin` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. You'll see two new menu items: "Top 10 Products" and "Top 10 Pages"

## Usage

### Creating Products

1. Go to **Top 10 Products** → **Add New**
2. Enter the product title and description
3. Set a featured image (product photo)
4. Fill in the **Product Details** meta box:
   - Badge Text (shown in carousel, e.g., "Best Overall Battery Heated Gloves")
   - Price
   - Rating (0-5)
   - Product/affiliate link
   - Button text
5. Add **Specifications** (13+ fields including):
   - Best For
   - Heating Levels
   - Battery & Battery Life
   - Heating Zones
   - Materials & Waterproofing
   - Sizes Available
   - Amazon, Trustpilot, Google/BBB Ratings
   - Research Observation
   - Customer Review Summary
   - Featured Review
6. Add **Features** (key selling points as list)
7. Add **Pros and Cons** (separate lists)
8. Publish the product

### Creating a Top 10 Page

1. Go to **Top 10 Pages** → **Add New**
2. Enter the page title (e.g., "Top 10 Heated Gloves")
3. Configure the **Hero Section**:
   - Hero title (custom or uses page title)
   - Hero subtitle (intro text)
   - Sidebar text (default: "TOP PICKS")
4. **Select Products**:
   - Choose products from the dropdown
   - Click "Add Product"
   - Drag to reorder (max 10 products)
5. Add **Buying Guide** (Things to Consider):
   - Add multiple consideration items
   - Each with title and description
   - Click "+ Add Item" for more
6. Add **Research Methodology**:
   - Introduction text
   - Research criteria table (criteria, weight, test method)
   - Click "+ Add Criterion" for more rows
7. Add **Conclusion** text
8. Add **FAQs** (optional):
   - Add questions and answers
   - These will appear in an accordion
   - Click "+ Add FAQ" for more
9. Configure **Section Settings**:
   - Toggle comparison table
   - Toggle FAQs section
   - Toggle buying guide section
10. Publish the page

### Viewing the Page

Once published, visit the page URL (e.g., `yoursite.com/top10/best-heated-gloves/`) to see the beautiful showcase page with:

- Hero section with blue sidebar
- Carousel with top 3-10 products (with badge labels)
- Detailed product cards showing:
  - Product image, title, rating, price
  - Best For highlight box
  - Description
  - Platform ratings (Amazon, Trustpilot, Google/BBB)
  - Technical specifications grid
  - Features list
  - Pros and Cons columns
  - Our Research & Customer Feedback sections
  - Shop Now button
- Comparison table with animated rating bars
- Things to Consider (Buying Guide) section
- How We Researched section with criteria table
- Conclusion section
- FAQ accordion
- Sticky sidebar with table of contents

## File Structure

```
Top10Plugin/
├── top10-plugin.php          # Main plugin file
├── includes/
│   ├── class-custom-post-types.php
│   ├── class-meta-boxes.php
│   ├── class-template-loader.php
│   └── class-helpers.php
├── templates/
│   └── template-top10.php    # Frontend template
├── assets/
│   ├── css/
│   │   ├── top10-styles.css       # Original styles
│   │   └── top10-figma-styles.css # Figma-matched design (1463 lines)
│   └── js/
│       └── top10-scripts.js       # Carousel, FAQ, smooth scroll
└── admin/
    ├── css/
    │   └── admin-styles.css       # Admin meta box styles
    └── js/
        └── admin-scripts.js       # Admin drag-drop & interactions
└── add-dummy-data.php             # Generate 10 sample products
```

## Customization

### Styling

The plugin uses `assets/css/top10-figma-styles.css` for frontend appearance, matching exact Figma design specifications. The CSS includes:

- Responsive layouts (desktop, tablet, mobile)
- Product card components
- Carousel navigation
- Rating badges and progress bars
- FAQ accordions
- Comparison tables
- Sticky sidebar

Key color scheme:
- Primary Blue: #4A90E2
- Accent Yellow: #FFD700
- Background: #F5F7FA

### Template

The template file is located at `templates/template-top10.php`. All content is dynamically pulled from WordPress admin fields, no hardcoded text.

### Adding Dummy Data

To quickly populate your site with sample products:

1. Navigate to `/wp-content/plugins/Top10Plugin/add-dummy-data.php` in your browser
2. This will create 10 sample products with complete data
3. Then create a Top 10 Page and select these products

## Requirements

- WordPress 5.0 or higher
- PHP 7.2 or higher
- jQuery (included with WordPress)

## Support

For issues or questions, please contact the plugin developer.

## License

GPL v2 or later

## Credits

Developed with ❤️ for creating beautiful product showcase pages.
