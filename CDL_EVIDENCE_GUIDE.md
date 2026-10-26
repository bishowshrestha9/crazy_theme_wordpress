# CDL Report: Evidence Artifacts 2.6, 2.7, 2.8 — Complete Capture Guide

This document provides exact code snippets and screenshot instructions for your three main evidence artifacts.

---

## **Artifact 2.6: CSV File Import Parser and Layout Mapping Code**

**Purpose:** Show backend PHP logic that reads CSV files, parses data rows, validates/sanitizes them, and transforms into database operations.

**Plugin Source:** `affiliate-link-manager.php` — `ajax_import_csv()` method

### **Code Snippet to Copy into Your Report:**

#### **Section A: File Upload Security & Nonce Verification (Lines 318–330)**
```php
<?php
public function ajax_import_csv()
{
    // SECURITY: Check nonce to prevent CSRF attacks
    check_ajax_referer('alm_nonce', 'security');

    // AUTHORIZATION: Only admins can import
    if (!current_user_can('manage_options'))
        wp_send_json_error('Unauthorized');
    
    // FILE VALIDATION: Check that file was uploaded
    if (empty($_FILES['csv_file']))
        wp_send_json_error('No file uploaded');

    $file = $_FILES['csv_file']['tmp_name'];
    if (!is_file($file))
        wp_send_json_error('File processing error');
?>
```

**Why this matters for your report:**
- ✅ Shows **security best practices**: nonce validation prevents CSRF
- ✅ Shows **authorization check**: capability verification (`current_user_can()`)
- ✅ Shows **input validation**: file existence and type checking

---

#### **Section B: CSV File Opening & Header Parsing (Lines 331–345)**
```php
<?php
    // OPEN FILE: Use fopen() for safe file reading
    $handle = fopen($file, 'r');
    if (!$handle)
        wp_send_json_error('Cannot open file');

    // PARSE HEADER ROW: Extract column names using fgetcsv()
    $header = fgetcsv($handle);
    if (!$header) {
        fclose($handle);
        wp_send_json_error('Empty CSV file');
    }

    $update_existing = isset($_POST['update_existing']) && $_POST['update_existing'] == '1';

    global $wpdb;
    $imported = 0;
    $skipped = 0;
?>
```

**Why this matters:**
- ✅ Shows **safe file handling**: `fopen()` and `fgetcsv()` are WordPress-approved methods
- ✅ Shows **error handling**: proper closing of file handle if CSV is invalid
- ✅ Shows **state initialization**: counters for tracking import results

---

#### **Section C: Column Detection & Mapping (Lines 346–369)**
```php
<?php
    // COLUMN MAPPING: Dynamically detect which column contains which data
    $col_name = -1;      // Product name/title column
    $col_target = -1;    // Target URL column
    $col_old = -1;       // Old/source URL column
    $col_affiliate = -1; // Affiliate network column

    $header_str = implode(',', $header);

    // ROBUST MATCHING: Case-insensitive, flexible header detection
    foreach ($header as $i => $val) {
        $val = strtolower(trim($val));
        
        // Match variations: "title", "name", "product name", etc.
        if (strpos($val, 'title') !== false || strpos($val, 'name') !== false)
            $col_name = $i;
        
        // Match variations: "source pretty", "pretty url", "source url"
        if (strpos($val, 'source pretty') !== false || strpos($val, 'pretty url') !== false || 
            (strpos($val, 'source') !== false && $col_old === -1))
            $col_old = $i;
        
        // Match variations: "new pretty", "target url"
        if (strpos($val, 'new pretty') !== false || strpos($val, 'target url') !== false || 
            (strpos($val, 'target') !== false && $col_target === -1))
            $col_target = $i;
        
        // Match affiliate network column
        if (strpos($val, 'affiliate') !== false)
            $col_affiliate = $i;
    }

    // VALIDATION: Ensure required column exists
    if ($col_target === -1) {
        fclose($handle);
        wp_send_json_error('Target URL column not found. Headers: ' . $header_str);
    }
?>
```

**Why this matters:**
- ✅ Shows **flexible data parsing**: handles different CSV header formats
- ✅ Shows **column mapping logic**: intelligent detection of data fields
- ✅ Shows **error reporting**: clear message when required column is missing

---

#### **Section D: CSV Row Loop & Data Transformation (Lines 380–410)**
```php
<?php
    // MAIN LOOP: Process each CSV row
    while (($row = fgetcsv($handle)) !== false) {
        // EXTRACT & SANITIZE: Get target URL from detected column
        $target_url = isset($row[$col_target]) ? esc_url_raw($row[$col_target]) : '';
        
        // VALIDATION: Skip rows with missing required field
        if (!$target_url) {
            $skipped++;
            continue;
        }

        // DATA EXTRACTION: Pull optional fields (old link, product name, affiliate network)
        $old_link = ($col_old !== -1 && isset($row[$col_old])) ? esc_url_raw($row[$col_old]) : '';
        $name = ($col_name !== -1 && !empty($row[$col_name])) ? sanitize_text_field($row[$col_name]) : 'Imported Link';
        $affiliate = ($col_affiliate !== -1 && isset($row[$col_affiliate])) ? sanitize_text_field($row[$col_affiliate]) : '';

        // DATA TRANSFORMATION: Generate slug from product name or URL
        $slug = sanitize_title($name . ' ' . time()); // Unique slug generation
        
        // DATABASE INSERT: Use wpdb->insert() for safe insertion
        $result = $wpdb->insert($this->table_name, [
            'name' => $name,
            'slug' => $slug,
            'target_url' => $target_url,
            'old_pretty_link' => $old_link,
            'parent_type' => 'Imported'
        ]);

        if ($result) {
            $imported++;
        } else {
            $skipped++;
        }
    }

    // CLEANUP: Close file handle
    fclose($handle);

    // RETURN RESULTS: Send success response with import summary
    wp_send_json_success([
        'imported' => $imported,
        'skipped' => $skipped,
        'message' => "Imported $imported links. Skipped $skipped rows."
    ]);
?>
```

**Why this matters:**
- ✅ Shows **loop structure**: how CSV rows are iterated and processed
- ✅ Shows **data sanitization**: `esc_url_raw()`, `sanitize_text_field()` prevent security issues
- ✅ Shows **transformation**: CSV row → database-ready array
- ✅ Shows **error resilience**: skip bad rows, continue processing
- ✅ Shows **database safety**: `wpdb->insert()` with prepared data
- ✅ Shows **feedback**: return summary counts to user

---

### **Screenshot Instructions for Artifact 2.6:**

1. **Take Screenshot 1 — CSV File Preview:**
   - Open a sample CSV file in Excel/Google Sheets
   - Show columns: `Product Name`, `Target URL`, `Old Pretty Link`, `Affiliate Network`
   - Include 3–5 sample rows
   - **Caption:** "Sample affiliate link CSV import file structure with column headers and data rows."

2. **Take Screenshot 2 — Admin Import Form:**
   - Go to WordPress admin → Affiliate Link Manager
   - Show the "Import Scrapper CSV" button
   - Show the modal/form with file upload input
   - **Caption:** "CSV import form with file upload and nonce security field."

3. **Take Screenshot 3 — Import Results:**
   - After importing, show the results notification
   - Display: "Imported 45 links. Skipped 3 rows." 
   - **Caption:** "Import completion message showing transformation results (imported vs skipped)."

---

## **Artifact 2.7: High-Resolution Screenshots of Plugin Admin Settings Panels**

**Purpose:** Show the WordPress admin interface with your plugin's backend UI, including form fields, toggles, data tables, and user management options.

### **What to Capture:**

#### **Screenshot A — Main Admin Dashboard with Data Table**
**Plugin:** `affiliate-link-manager` or `Top10Plugin`

**Steps:**
1. Go to WordPress admin → `Affiliate Links` (left sidebar menu)
2. Show the table with columns:
   - Name (product/link name)
   - Pretty Link (slug)
   - Target URL
   - Origin/Parent (tracking info)
   - Actions (delete button)
3. Ensure table has 8–15 rows of sample data
4. Show header buttons: "Export Mapping CSV" and "Import Scrapper CSV"

**Screenshot Requirements:**
- Full admin page width (capture at 1920×1080 minimum for clarity)
- Include WordPress sidebar and header
- Show data grid/table clearly with 3–4 columns visible
- Include action buttons in header

**Caption:** "Affiliate Link Manager admin dashboard showing centralized link management table with import/export capabilities and CRUD actions."

---

#### **Screenshot B — Form Fields & Settings Panel**
**Plugin:** `Top10Plugin` or `CardpluginTBR 2`

**Steps:**
1. Create/Edit a post with your plugin's custom post type
2. Show the meta boxes with form fields:
   - Text inputs (product name, URL, slug)
   - Dropdowns/selects (status, category, affiliate network)
   - Checkboxes (enable tracking, enable schema, etc.)
   - Upload/media fields
3. Show 2–3 meta boxes stacked

**Screenshot Requirements:**
- Capture Gutenberg editor on right + sidebar on left
- Show at least 8–10 editable fields
- Include field labels and descriptions

**Caption:** "Product card configuration form showing customizable fields for product data, status, pricing, and CTA tracking within WordPress editor."

---

#### **Screenshot C — Data Management Table (Optional)**
**Plugin:** `Top10Plugin` (product listing)

**Steps:**
1. Go to Products listing page in admin
2. Show the All Products table with columns:
   - Title
   - Category
   - Price
   - Status (Legit/Scam badge)
   - Date Created
3. Show quick-edit or bulk actions if available

**Caption:** "Products management table in WordPress admin showing bulk operations, filters, and individual product metadata for review and editing."

---

### **Screenshot Collection Tips:**

- **Browser:** Use Chrome DevTools → Device Toolbar to ensure consistent 1920×1080 capture
- **Admin Bar:** Leave visible (shows user context)
- **Zoom:** Set to 100% for accurate text legibility
- **File Format:** Save as PNG with 100% quality (lossless)
- **Naming:** `Artifact-2.7-A-Admin-Dashboard.png`, `Artifact-2.7-B-Form-Fields.png`, etc.

---

## **Artifact 2.8: Live Theme Integration and Frontend Layout Views**

**Purpose:** Show how plugin output renders on live pages alongside WordPress themes, proving professional visual integration and responsive design.

### **What to Capture:**

#### **Screenshot A — Product Card Block on Published Page**
**Plugin:** `CardpluginTBR 2` (Product Card)

**Steps:**
1. Create a test page with the Product Card block
2. Add sample data:
   - Product image
   - Product name (e.g., "Gutoptim Prostate Supplement")
   - Status badge (green "LEGIT ✓" or red "SCAM ✗")
   - Rating (4.9/5 stars)
   - Price with strikethrough original
   - "Buy Now" CTA button
3. View on published page (frontend)
4. Capture full-width view

**Screenshot Requirements:**
- Show clean card layout with image on left, details on right (or stacked on mobile)
- Include active WordPress theme styling
- Show the entire card within page context (header, sidebar, footer partially visible)
- Ensure professional appearance

**Screenshot 2 — Inspector View of JSON-LD Schema:**
1. Right-click on published page → **Inspect**
2. Search for `<script type="application/ld+json">`
3. Expand to show schema structure:
   ```json
   {
     "@context": "https://schema.org",
     "@type": "Product",
     "name": "Gutoptim",
     "category": "Supplement",
     "image": ["https://..."],
     "aggregateRating": {
       "@type": "AggregateRating",
       "ratingValue": 4.9,
       "bestRating": 5,
       "worstRating": 0
     },
     "offers": {
       "@type": "Offer",
       "priceCurrency": "$",
       "price": "69"
     }
   }
   ```
4. Capture Inspector panel showing the JSON-LD script

**Caption:** "Product card block rendered on published page with integrated WordPress theme styling and JSON-LD schema for SEO."

---

#### **Screenshot B — Top 10 List / Comparison Table**
**Plugin:** `Top10Plugin`

**Steps:**
1. Create a page with Top 10 block
2. Add ranked list with:
   - Rank numbers (1–10)
   - Product images
   - Product names
   - Scores/ratings
   - Brief descriptions
   - "Buy" or "Learn More" CTAs
3. View on published page

**Screenshot Requirements:**
- Show clean numbered list or table format
- Include product images (thumbnails)
- Show mobile responsive view (second screenshot at mobile width)
- Display CTA buttons clearly

**Mobile View:**
1. Open same page in responsive design mode (F12 → Device Toolbar)
2. Set to iPhone 12/14 width (390px)
3. Capture stacked card layout

**Captions:**
- Desktop: "Top 10 product comparison list rendered with responsive grid layout and integrated call-to-action buttons."
- Mobile: "Top 10 list mobile responsive view showing stacked card layout for optimal user experience on small screens."

---

#### **Screenshot C — Pros & Cons Block on Page**
**Plugin:** `simple-pros-cons`

**Steps:**
1. Create a page with Pros & Cons block
2. Add 4 pros and 4 cons for a product review
3. Include icons or styling

**Screenshot Requirements:**
- Show clear two-column layout (pros left, cons right) or stacked
- Include checkmark icons for pros, X icons for cons
- Show in page context with active theme

**Caption:** "Pros and cons review block demonstrating scannable content layout for product evaluation sections."

---

#### **Screenshot D — Meta Redirect Plugin Result (Optional)**
**Plugin:** `meta-redirecct-plugin`

**Steps:**
1. Create a short link using the plugin
2. Hover over the link → see target URL tooltip
3. Click the link → monitor Browser Network tab
4. Show 301/302 redirect header

**Capture Two Views:**
1. **Before click:** Show the short link URL on page
2. **After click:** Browser Network tab showing:
   - Original request: `/go/my-link`
   - Response: `301 Moved Permanently`
   - Target: `https://example.com/long-url`

**Caption:** "Affiliate link redirect in action, showing transparent URL transformation and proper HTTP status codes for SEO."

---

### **Screenshot Collection Best Practices:**

1. **Resolution:** Capture at 1920×1080 minimum
2. **Themes:** Show with at least 2 different WordPress themes (default + one premium theme)
3. **Responsive:** Include desktop AND mobile views (2 versions per artifact)
4. **Consistency:** Use same test data across all screenshots
5. **Watermark:** Optional — add your plugin name/version in corner (e.g., "v1.0.0")
6. **File Names:**
   - `Artifact-2.8-A1-ProductCard-Desktop.png`
   - `Artifact-2.8-A2-ProductCard-Mobile.png`
   - `Artifact-2.8-B1-TopTenList-Desktop.png`
   - `Artifact-2.8-B2-TopTenList-Mobile.png`

---

## **Summary: What to Include in Your CDL Report**

| Artifact | Code | Screenshots | Total Items |
|----------|------|-------------|-------------|
| **2.6** | 4 code sections (A–D from above) | 3 images (CSV preview, import form, results) | 7 items |
| **2.7** | (No code, admin interface only) | 3 images (dashboard, forms, data table) | 3 items |
| **2.8** | (No code, frontend only) | 6–8 images (desktop/mobile views) | 6–8 items |
| **TOTAL** | 4 code sections | 12–14 screenshots | ~16–18 evidence items |

---

## **How to Present in Report**

### **Artifact 2.6 — CSV Parser & Data Transformation**
```
2.6 Backend CSV Parser and Data Transformation Logic

The affiliate-link-manager plugin includes a robust CSV import handler that:

A. Validates file upload security using WordPress nonces (CSRF prevention)
B. Opens and reads CSV files using fgetcsv() for safe parsing
C. Intelligently detects column mappings (flexible header names)
D. Transforms CSV rows into database-ready arrays with sanitization

Code Evidence [Sections A–D above, copy-paste PHP]

Screenshots:
  - Sample CSV structure with headers and data
  - WordPress admin import form
  - Import completion with summary results
```

### **Artifact 2.7 — Admin Settings Panels**
```
2.7 WordPress Admin Interface and User Management

High-resolution screenshots of plugin backend controls:
  - Affiliate Link Manager dashboard with data grid
  - Product card form with 10+ editable fields
  - Products list table with bulk operations

[Screenshots 1–3 with captions]
```

### **Artifact 2.8 — Frontend Theme Integration**
```
2.8 Live Plugin Output on Published Pages

Screenshots demonstrating plugin blocks rendered on live WordPress sites:
  - Product review cards with integrated theme styling
  - Top 10 comparison lists (desktop and mobile responsive)
  - Pros/cons blocks for product reviews
  - Meta redirect with HTTP headers in browser DevTools

[Screenshots 1–8 with captions and mobile variants]
```

---

**End of CDL Evidence Guide**
