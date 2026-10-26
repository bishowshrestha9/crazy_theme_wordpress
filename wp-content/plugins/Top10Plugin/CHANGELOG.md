# Changelog

All notable changes to the Top 10 Plugin.

## [2.1.0] - 2025-01-05

### Added - Sticky Table of Contents & Dynamic FAQs

#### Sticky Sidebar
- **Enhanced sticky behavior** with improved positioning
- Top offset increased to 80px for better visibility
- Added max-height with overflow for long content
- Smooth scroll behavior maintained

#### Dynamic FAQs Section
- FAQs now completely admin-editable
- Removed all hardcoded FAQ content
- Template loops through admin-defined questions/answers
- Conditional display based on section toggle

#### Dynamic Data Sources
- Added "Data Sources Text" field to Research Methodology meta box
- Allows custom text below criteria table
- Default placeholder text provided
- Fully integrated with save function

#### Dummy Data Enhancements
- Added complete buying guide (5 items) to sample page
- Added research methodology with intro and 5 criteria
- Added research sources text
- Added conclusion text
- Added 5 complete FAQs
- All section toggles enabled by default

### Changed
- "Best For" box moved to full-width below image/description
- Improved padding and spacing (20px 24px)
- Font size increased to 15px for better readability
- Better visual hierarchy in product cards

### Fixed
- Removed duplicate/leftover hardcoded table rows
- Cleaned up template structure
- FAQs now properly conditional on toggle setting

## [2.0.0] - 2025-01-XX

### Added - Complete Admin Control & Dynamic Content

#### Product Fields
- **Badge Text field** - Custom badge shown in carousel (e.g., "Best Overall Battery Heated Gloves")
- **13+ Specification fields** including:
  - Best For
  - Heating Levels
  - Battery & Battery Life
  - Heating Zones
  - Materials
  - Waterproofing
  - Sizes Available
  - **Platform Ratings**: Amazon, Trustpilot, Google/BBB ratings
  - Research Observation (textarea)
  - Customer Review Summary (textarea)
  - Featured Review (textarea)
- Dynamic features, pros, and cons arrays

#### Page-Level Content Fields
- **Buying Guide section** (repeatable fields):
  - Title + Description pairs
  - Add/remove items dynamically
  - Displays as "Things to Consider Before Buying"
  
- **Research Methodology section**:
  - Introduction text field
  - Criteria table (repeatable):
    - Criteria name
    - Weight percentage
    - Test method
  - Displays as "How We Researched & Rated"
  
- **Conclusion section**:
  - Textarea for summary/conclusion text
  - Displays at bottom before FAQs

#### Template Updates
- Removed ALL hardcoded content
- Everything now pulls from WordPress admin fields
- Dynamic carousel badges (uses product badge_text field)
- Dynamic buying guide items loop
- Dynamic research methodology with criteria table
- Dynamic conclusion text
- Platform ratings (Amazon, Trustpilot, BBB) display per product
- Comprehensive specs grid with 13+ fields
- Research observations and customer reviews sections

#### Data & Files
- **10 Complete Dummy Products** in `add-dummy-data.php`:
  - All 13+ specification fields populated
  - Unique badge text for each product
  - Full features, pros, cons arrays
  - Platform ratings for each
- Updated `class-meta-boxes.php` with 3 new meta boxes
- Updated `class-helpers.php` to include badge_text in product data
- Updated `template-top10.php` with all dynamic sections

### Changed
- Product meta box now shows badge text field first
- Specifications meta box expanded to 13+ fields
- Page meta boxes reorganized for better workflow
- Dummy data reflects real-world heated gloves products
- README.md updated with complete feature list

### Technical Details
- Meta boxes use proper nonce verification
- All user input sanitized (text_field, textarea_field, url)
- Arrays properly saved and retrieved
- Backward compatibility maintained
- No database migrations required

### Files Modified
1. `/includes/class-meta-boxes.php`
   - Added `render_page_buying_guide_meta_box()`
   - Added `render_page_research_meta_box()`
   - Added `render_page_conclusion_meta_box()`
   - Updated `save_page_meta()` for new fields
   - Added badge_text field to product details
   - Updated `save_product_meta()` for badge_text

2. `/templates/template-top10.php`
   - Added retrieval of buying_guide, research_intro, research_criteria, conclusion
   - Replaced hardcoded buying guide with dynamic loop
   - Replaced hardcoded research section with dynamic content
   - Replaced hardcoded conclusion with dynamic text
   - Updated carousel to use product badge_text

3. `/includes/class-helpers.php`
   - Added badge_text to `get_product_data()` return array

4. `/add-dummy-data.php`
   - Added badge_text to all 10 products
   - Added comprehensive specs arrays
   - Added save logic for badge_text meta

5. `/README.md`
   - Updated features list
   - Updated usage instructions
   - Added complete field documentation

## [1.0.0] - Previous Version

### Initial Features
- Custom post types (top10_product, top10_page)
- Basic product details (price, rating, link)
- Product selection interface
- FAQ system
- Comparison table
- Hero section
- Figma-matched design system
- Responsive layouts
- Carousel navigation

---

## Upgrade Notes

### From 1.0.0 to 2.0.0
- No breaking changes
- Existing products will work but won't have badge text or extended specs
- Existing pages will work but won't have buying guide, research, or conclusion content
- **Recommended**: Run `add-dummy-data.php` to see all new features
- **Optional**: Edit existing products to add badge text and complete specifications
- **Optional**: Edit existing pages to add buying guide, research methodology, and conclusion

## Future Roadmap

### Planned Features
- [ ] JavaScript for add/remove buttons in admin meta boxes
- [ ] Drag-and-drop reordering for buying guide items
- [ ] Rich text editor for conclusion section
- [ ] Import/export functionality for products
- [ ] Shortcode support for embedding products anywhere
- [ ] Category/taxonomy system for products
- [ ] Product comparison widget
- [ ] Related products feature
- [ ] Schema.org markup for SEO
- [ ] Analytics integration
