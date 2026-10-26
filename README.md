# Crazy Theme WordPress

A custom WordPress setup featuring **Crazy Theme**, **Supplement Review**, and custom block plugins for affiliate marketing, product reviews, and comparison cards.

---

## 📋 Prerequisites

Before you begin, ensure you have the following installed on your machine:
- **[LocalWP](https://localwp.com/)** (Free local WordPress development tool for macOS, Windows, Linux)
- **[Git](https://git-scm.com/)** (v2.30+ recommended)
- **Node.js & npm** (v18+ or v20+ LTS — only needed if modifying or building custom block assets)

---

## 🚀 Setup & Installation with LocalWP

Follow these steps to clone and run the site locally.

### Step 1: Create a New Site in LocalWP

1. Open **LocalWP**.
2. Click the **`+` (Add Site)** button in the bottom-left corner.
3. Select **"Create a new site"** and click **Continue**.
4. Set the site name (e.g. `crazy-theme` or `redirect`).
5. Choose **Preferred** environment (PHP 8.1/8.2, Nginx, MySQL 8.0) and click **Continue**.
6. Set temporary WordPress admin credentials (e.g., username `admin`, password `password`, email `admin@local.dev`) and click **Add Site**.
7. Once provisioned, click **"Stop Site"** in LocalWP before replacing the files.

---

### Step 2: Clone this Repository into `app/public`

1. In LocalWP, click the small arrow next to the **Site Path** (or right-click the site in the sidebar and choose **"Go to Site Folder"**).
2. Open your terminal and navigate to the site's `app` folder:

   ```bash
   # Replace <your-site-name> with the exact folder name created by LocalWP
   cd ~/Local\ Sites/<your-site-name>/app
   ```

3. Remove the default `public` directory created by LocalWP:

   ```bash
   rm -rf public
   ```

4. Clone this repository directly as the `public` directory:

   ```bash
   git clone https://github.com/bishowshrestha9/crazy_theme_wordpress.git public
   ```

---

### Step 3: Verify Database Credentials (`wp-config.php`)

LocalWP uses standardized database credentials by default. Ensure the database constants in `wp-config.php` match your LocalWP environment:

```php
// app/public/wp-config.php
define( 'DB_NAME', 'local' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'root' );
define( 'DB_HOST', 'localhost' );
$table_prefix = 'wp_';
```

> **Note:** If LocalWP assigned a socket or custom port, check the **Database** tab in LocalWP for the exact socket path or port.

---

### Step 4: Import Database (If you have a `.sql` backup)

If you have a database backup dump (such as `local.sql` from your export):

#### Method A: Using LocalWP Site Shell (Recommended)
1. In LocalWP, select your site and click **"Open Site Shell"**.
2. Run the WP-CLI database import:
   ```bash
   wp db import /path/to/your-database.sql
   ```
3. Update the URLs to match your local site domain:
   ```bash
   wp search-replace 'https://old-domain.local' 'http://crazy-theme.local' --all-tables
   ```

#### Method B: Using Adminer
1. In LocalWP, click the **"Database"** tab.
2. Click **"Open Adminer"**.
3. Click **Import** in the sidebar, select your `.sql` file, and click **Execute**.

---

### Step 5: Start & Run the Site

1. In LocalWP, click the **"Start Site"** button in the top-right corner.
2. Click **"Open Site"** to view the frontend in your browser.
3. Click **"WP Admin"** to access the dashboard (`/wp-admin`).

---

## 🎨 Theme & Plugins Overview

### Themes
- **`wp-content/themes/crazy-theme`**: Custom theme with high-converting single post layouts, custom headers, and clean responsive CSS.
- **`wp-content/themes/supplement-review`**: Supplement review and comparison theme featuring interactive Table of Contents and styled review sections.

### Key Custom Plugins
- **`top-choices-block`**: Gutenberg block for showcasing top product picks with ratings, pros/cons, and CTA links.
- **`ProductCardPlugin-TBR`**: Custom interactive product cards with affiliate link integration.
- **`crazy-pros-cons`**: Block for styling pros & cons breakdown cards.
- **`pretty-link-geo-detector`**: Geolocation link redirection and tracking tool.

---

## 🛠️ Plugin Asset Development (Optional)

If you plan to modify or recompile JavaScript/React blocks (e.g., Gutenberg blocks):

```bash
cd wp-content/plugins/top-choices-block
npm install
npm run build   # Or: npm start (for hot-reloading dev mode)
```

*(Repeat inside any block plugin directory with a `package.json`)*.

---

## 💡 Troubleshooting

- **404s on Inner Pages / Posts**: Go to **WP Admin > Settings > Permalinks** and click **Save Changes** to regenerate `.htaccess` rewrite rules.
- **Plugin Blocks Not Rendering**: Ensure block assets are compiled (`npm run build` in the plugin directory if you altered `src/`).
- **Database Connection Error**: Verify `DB_NAME`, `DB_USER`, and `DB_PASSWORD` in `wp-config.php` match the credentials in LocalWP's **Database** tab.
