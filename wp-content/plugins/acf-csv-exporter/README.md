# ACF CSV Exporter

A standalone WordPress plugin to export published posts and specific ACF fields to CSV.

## Features
- Exports in CSV using `fputcsv`
- Uses batched `WP_Query` to avoid memory exhaustion
- Fixed to export published `post` items (one post = one product)
- Exports only these columns: `Post ID`, `Post Title`, `Category`, `product_name`, `rating`, `price`, `product_image`, `product_category`, `effectiveness`, `buy_link`, `certification`, `return_policy`, `manufacturer`, `dosage`, `verdict`, `results`
- Converts image field values (ID/array/url) to absolute URL
- Supports admin button export and secret URL export

## Usage
1. Activate plugin in `Plugins`.
2. Go to `Tools -> ACF CSV Exporter`.
3. Click **Export CSV**.

Secret URL format:
`https://your-site.com/?acf_csv_export=1&key=YOUR_SECRET`
