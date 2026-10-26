<?php
/**
 * Blocks Class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Top10_Blocks
{

    /**
     * Instance of this class
     */
    private static $instance = null;

    /**
     * Get instance
     */
    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct()
    {
        add_action('init', array($this, 'register_blocks'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));
    }

    /**
     * Enqueue block editor assets
     */
    public function enqueue_block_editor_assets()
    {
        wp_enqueue_script(
            'top10-blocks-js',
            TOP10_PLUGIN_URL . 'assets/js/blocks.js',
            array('wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-data'),
            TOP10_VERSION,
            true
        );

        // Pass products to JS for the selector
        $products = get_posts(array(
            'post_type' => 'top10_product',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ));

        $product_choices = array(array('label' => 'Select a Product', 'value' => 0));
        foreach ($products as $p) {
            $product_choices[] = array(
                'label' => $p->post_title,
                'value' => $p->ID
            );
        }

        wp_localize_script('top10-blocks-js', 'top10BlockData', array(
            'products' => $product_choices
        ));
    }

    /**
     * Register blocks
     */
    public function register_blocks()
    {
        // Section 1: Hero/Introduction
        register_block_type('top10/hero', array(
            'attributes' => array(
                'title' => array('type' => 'string', 'default' => ''),
                'subtitle' => array('type' => 'string', 'default' => ''),
                'bgImageUrl' => array('type' => 'string', 'default' => ''),
            ),
            'render_callback' => array($this, 'render_hero_block')
        ));

        // Section 2: Select Products (Previously main list, splitting to blocks)
        // Since blocks allow placement anywhere, we don't have a "Section 2" that behaves as a global data store.
        // However, user requested "7 sections with same labels".
        // Using "Detailed Analysis" block as the main product list.

        // Section 3: Top 5 Choices Display Settings -> "Section 3: Top 5 Picks"
        register_block_type('top10/top-picks', array(
            'attributes' => array(
                'product1' => array('type' => 'integer', 'default' => 0),
                'product2' => array('type' => 'integer', 'default' => 0),
                'product3' => array('type' => 'integer', 'default' => 0),
                'product4' => array('type' => 'integer', 'default' => 0),
                'product5' => array('type' => 'integer', 'default' => 0),
                'titlePrefix' => array('type' => 'string', 'default' => 'Top Choices:'),
            ),
            'render_callback' => array($this, 'render_top_picks_block')
        ));

        // Section 4: Downloadable Research Documents -> "Section 4: Research Docs"
        register_block_type('top10/research-docs', array(
            'attributes' => array(
                'header' => array('type' => 'string', 'default' => 'Download our Research Documents'),
                'intro' => array('type' => 'string', 'default' => ''),
                'bullets' => array('type' => 'string', 'default' => ''),
                'excelUrl' => array('type' => 'string', 'default' => ''),
                'pdfUrl' => array('type' => 'string', 'default' => ''),
                'excelText' => array('type' => 'string', 'default' => 'Download the Full Research Workbook (Excel)'),
                'pdfText' => array('type' => 'string', 'default' => 'Download the Detailed Research Document (PDF)'),
            ),
            'render_callback' => array($this, 'render_research_docs_block')
        ));

        // Section 5: Comparison Table -> "Section 5: Comparison Table"
        register_block_type('top10/comparison-table', array(
            'attributes' => array(
                'tableHtml' => array('type' => 'string', 'default' => ''),
            ),
            'render_callback' => array($this, 'render_comparison_table_block')
        ));

        // Section 6: FAQs -> "Section 6: FAQs"
        register_block_type('top10/faqs', array(
            'attributes' => array(
                'items' => array('type' => 'string', 'default' => '[]'), // JSON string of [{question, answer}]
            ),
            'render_callback' => array($this, 'render_faqs_block')
        ));

        // Section 7: Source Documents & Disclosure -> "Section 7: Sources & Disclosure"
        register_block_type('top10/source-docs', array(
            'attributes' => array(
                'intro' => array('type' => 'string', 'default' => ''),
                'criteria' => array('type' => 'string', 'default' => '[]'), // JSON string for table rows
                'sources' => array('type' => 'string', 'default' => ''),
            ),
            'render_callback' => array($this, 'render_source_docs_block')
        ));

        // Extra: Detailed Analysis Block (Not strictly numbered in original meta boxes as separate section from "Products", but logically it's the main content)
        // Let's call it "Product Analysis List"
        register_block_type('top10/detailed-analysis', array(
            'attributes' => array(
                'product1' => array('type' => 'integer', 'default' => 0),
                'product2' => array('type' => 'integer', 'default' => 0),
                'product3' => array('type' => 'integer', 'default' => 0),
                'product4' => array('type' => 'integer', 'default' => 0),
                'product5' => array('type' => 'integer', 'default' => 0),
                'product6' => array('type' => 'integer', 'default' => 0),
                'product7' => array('type' => 'integer', 'default' => 0),
                'product8' => array('type' => 'integer', 'default' => 0),
                'product9' => array('type' => 'integer', 'default' => 0),
                'product10' => array('type' => 'integer', 'default' => 0),
            ),
            'render_callback' => array($this, 'render_detailed_analysis_block')
        ));

        // Extra: Buying Guide
        register_block_type('top10/buying-guide', array(
            'attributes' => array(
                'items' => array('type' => 'string', 'default' => '[]'),
            ),
            'render_callback' => array($this, 'render_buying_guide_block')
        ));

        // Extra: Disclaimer
        register_block_type('top10/disclaimer', array(
            'attributes' => array(
                'text' => array('type' => 'string', 'default' => ''),
            ),
            'render_callback' => array($this, 'render_disclaimer_block')
        ));
    }

    /**
     * Helper: Get Product Data
     */
    private function get_product_data($id)
    {
        $p = get_post($id);
        if (!$p)
            return null;

        return array(
            'name' => get_the_title($id) ?: get_post_meta($id, '_top10_product_name', true),
            'badge_text' => get_post_meta($id, '_top10_badge_text', true),
            'price' => get_post_meta($id, '_top10_price', true),
            'rating' => get_post_meta($id, '_top10_rating', true),
            'buy_link' => get_post_meta($id, '_top10_affiliate_link', true),
            'review_link' => get_permalink($id),
            'best_for' => get_post_meta($id, '_top10_best_for', true),
        );
    }

    /**
     * Helper: Get Products from Attributes
     */
    private function get_products_from_attributes($attributes, $count = 10)
    {
        $products_data = array();
        for ($i = 1; $i <= $count; $i++) {
            if (!empty($attributes['product' . $i])) {
                $data = $this->get_product_data($attributes['product' . $i]);
                if ($data) {
                    $products_data[] = $data;
                }
            }
        }
        return $products_data;
    }

    /**
     * Render Hero Block (Section 1)
     */
    public function render_hero_block($attributes)
    {
        $bg_image = !empty($attributes['bgImageUrl']) ? $attributes['bgImageUrl'] : 'https://picsum.photos/seed/hero-bg/1920/400';
        $title = !empty($attributes['title']) ? $attributes['title'] : get_the_title();

        ob_start();
        ?>
        <section class="top10-hero"
            style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('<?php echo esc_url($bg_image); ?>');">
            <div class="top10-hero-content">
                <h1 class="hero-title"><?php echo esc_html($title); ?></h1>
                <?php if (!empty($attributes['subtitle'])): ?>
                    <p class="hero-subtitle"><?php echo esc_html($attributes['subtitle']); ?></p>
                <?php endif; ?>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Top Picks Block (Section 3)
     */
    public function render_top_picks_block($attributes)
    {
        // Decode manually
        $json = isset($attributes['manualProducts']) ? $attributes['manualProducts'] : '[]';
        // Sometimes WP adds slashes
        if (is_string($json)) {
            $json = stripslashes($json);
        }
        $products_data = json_decode($json, true);

        // DEBUG: Force output so we can see if the block is even running
        ob_start();
        echo '<div style="border: 2px solid red; padding: 10px; margin: 10px; background: #fffcfc; color: #333;">';
        echo '<strong>DEBUG: Top Picks Block</strong><br>';
        echo 'Raw JSON: ' . htmlspecialchars(substr($json, 0, 100)) . '...<br>';
        echo 'Decoded Count: ' . (is_array($products_data) ? count($products_data) : 'Not an Array') . '<br>';

        if (!is_array($products_data) || empty($products_data)) {
            // Check if it's the old 'products' attribute just in case they haven't updated the block fully
            if (!empty($attributes['products'])) {
                return '<div class="top10-block-message">Top 5 Choices: Please update this block to use the new manual entry fields.</div>';
            }
            return '<div class="top10-block-message">Section 3: Top Picks - Please add products manually.</div>';
        }

        ob_start();
        ?>
        <section class="top10-top-choices-section">
            <h2 class="section-title"><?php echo esc_html($attributes['titlePrefix']); ?></h2>
            <div class="carousel-container"> <!-- Using simple list fallback if JS carousel not init -->
                <div class="top-choices-carousel" style="display: flex; gap: 20px; overflow-x: auto;">
                    <?php foreach ($products_data as $product): ?>
                        <div class="choice-card" style="min-width: 280px;">
                            <?php if (!empty($product['badge'])): ?>
                                <div class="choice-badge"><?php echo esc_html($product['badge']); ?></div>
                            <?php endif; ?>

                            <div class="choice-image">
                                <?php if (!empty($product['imageUrl'])): ?>
                                    <img src="<?php echo esc_url($product['imageUrl']); ?>"
                                        alt="<?php echo esc_attr($product['name']); ?>">
                                <?php else: ?>
                                    <!-- Placeholder could go here or keep empty to maintain layout height -->
                                    <div
                                        style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#ccc;">
                                        No Image</div>
                                <?php endif; ?>
                            </div>

                            <h3 class="choice-title"><?php echo esc_html($product['name']); ?></h3>
                            <p class="choice-price"><?php echo esc_html($product['price']); ?></p>

                            <div class="choice-rating">
                                <span class="stars">★★★★★</span>
                                <span
                                    class="rating-number"><?php echo number_format((float) ($product['rating'] ?? 0), 1); ?>/10</span>
                                <?php if (!empty($product['link'])): ?>
                                    <a href="<?php echo esc_url($product['link']); ?>" class="read-review">Read Review</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Detailed Analysis Block 
     */
    public function render_detailed_analysis_block($attributes)
    {
        $products_data = $this->get_products_from_attributes($attributes, 10);

        if (empty($products_data)) {
            return '<div class="top10-block-message">Detailed Analysis: Please select products.</div>';
        }

        ob_start();
        ?>
        <section class="detailed-products-section">
            <h2 class="section-title">Detailed Analysis</h2>
            <?php foreach ($products_data as $index => $product):
                $rank = $index + 1; ?>
                <div class="detailed-product-card">
                    <div class="product-number-badge"><?php echo $rank; ?>. <?php echo esc_html($product['name']); ?>
                        (<?php echo esc_html($product['price']); ?>)</div>

                    <div class="product-card-content">
                        <div class="product-two-column">
                            <div class="product-right-column" style="width: 100%;">
                                <?php if (!empty($product['best_for'])): ?>
                                    <div class="product-description">
                                        <strong>Overview:</strong>
                                        <p><?php echo nl2br(esc_html($product['best_for'])); ?></p>
                                    </div>
                                <?php endif; ?>

                                <div class="rating-badges-row">
                                    <div class="rating-badge-item">
                                        <div class="badge-stars-yellow">★★★★★</div>
                                        <div class="badge-score"><?php echo esc_html($product['rating']); ?>/10</div>
                                        <div class="badge-platform">Our Rating</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="action-buttons">
                            <?php if (!empty($product['buy_link'])): ?>
                                <a href="<?php echo esc_url($product['buy_link']); ?>" class="btn-buy-now" target="_blank">Buy Now</a>
                            <?php endif; ?>
                            <?php if (!empty($product['review_link'])): ?>
                                <a href="<?php echo esc_url($product['review_link']); ?>" class="btn-read-reviews">Read Review</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Comparison Table Block (Section 5)
     */
    public function render_comparison_table_block($attributes)
    {
        $table_html = isset($attributes['tableHtml']) ? $attributes['tableHtml'] : '';

        if (empty($table_html)) {
            return '<div class="top10-block-message">Comparison Table: Please paste your table in the editor.</div>';
        }

        ob_start();
        ?>
        <section class="comparison-table-section">
            <h2 class="section-title">Comparison Table</h2>
            <div class="comparison-table-wrapper">
                <?php echo wp_kses_post($table_html); ?>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Buying Guide Block
     */
    public function render_buying_guide_block($attributes)
    {
        $items = json_decode($attributes['items'], true);
        if (empty($items)) {
            return '';
        }

        ob_start();
        ?>
        <section class="buying-guide-section">
            <h2 class="section-title">Things to Consider Before Buying</h2>
            <?php foreach ($items as $item): ?>
                <div class="consideration-item">
                    <div class="consideration-bar"></div>
                    <div class="consideration-content">
                        <h3><?php echo esc_html($item['title']); ?></h3>
                        <p><?php echo esc_html($item['description']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Research Docs Block (Section 4)
     */
    public function render_research_docs_block($attributes)
    {
        ob_start();
        ?>
        <section class="top10-research-docs-section"
            style="background: #f9f9f9; padding: 30px; border-radius: 8px; margin: 30px 0;">
            <div class="research-header">
                <h3><?php echo esc_html($attributes['header']); ?></h3>
                <?php if (!empty($attributes['intro'])): ?>
                    <p><?php echo wp_kses_post($attributes['intro']); ?></p>
                <?php endif; ?>
            </div>

            <div class="research-content" style="display: flex; gap: 30px; flex-wrap: wrap;">
                <?php if (!empty($attributes['bullets'])): ?>
                    <div class="research-bullets" style="flex: 1; min-width: 250px;">
                        <strong>The research document includes:</strong>
                        <ul style="margin-top: 10px; list-style-type: disc; padding-left: 20px;">
                            <?php
                            $lines = explode("\n", $attributes['bullets']);
                            foreach ($lines as $line) {
                                if (trim($line)) {
                                    echo '<li>' . esc_html(trim($line)) . '</li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="research-actions"
                    style="flex: 1; min-width: 250px; display: flex; flex-direction: column; gap: 15px;">
                    <?php if (!empty($attributes['excelUrl'])): ?>
                        <a href="<?php echo esc_url($attributes['excelUrl']); ?>" class="research-download-btn excel-btn"
                            style="display: block; padding: 15px; background: #217346; color: white; text-align: center; border-radius: 5px; text-decoration: none;">
                            <span class="dashicons dashicons-media-spreadsheet" style="margin-right: 5px;"></span>
                            <?php echo esc_html($attributes['excelText']); ?>
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($attributes['pdfUrl'])): ?>
                        <a href="<?php echo esc_url($attributes['pdfUrl']); ?>" class="research-download-btn pdf-btn"
                            style="display: block; padding: 15px; background: #b30b00; color: white; text-align: center; border-radius: 5px; text-decoration: none;">
                            <span class="dashicons dashicons-pdf" style="margin-right: 5px;"></span>
                            <?php echo esc_html($attributes['pdfText']); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render FAQs Block (Section 6)
     */
    public function render_faqs_block($attributes)
    {
        $items = json_decode($attributes['items'], true);
        if (empty($items)) {
            return '';
        }

        ob_start();
        ?>
        <section class="faqs-section">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <div class="faq-accordion-figma">
                <?php foreach ($items as $faq): ?>
                    <div class="faq-item-figma">
                        <button class="faq-question-figma" type="button" onclick="this.parentElement.classList.toggle('active')">
                            <span><?php echo esc_html($faq['question']); ?></span>
                            <span class="faq-toggle">›</span>
                        </button>
                        <div class="faq-answer-figma">
                            <p><?php echo esc_html($faq['answer']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Source Docs Block (Section 7)
     */
    public function render_source_docs_block($attributes)
    {
        $title = 'HOW WE RESEARCHED & RATED THE ' . get_the_title();
        $intro = !empty($attributes['intro']) ? $attributes['intro'] : '';
        $criteria_rows = json_decode($attributes['criteria'], true);
        $sources = !empty($attributes['sources']) ? $attributes['sources'] : '';

        ob_start();
        ?>
        <section class="research-methodology-section">
            <h2 class="section-title"><?php echo esc_html($title); ?></h2>

            <?php if ($intro): ?>
                <p class="methodology-intro"><?php echo esc_html($intro); ?></p>
            <?php endif; ?>

            <?php if (!empty($criteria_rows) && is_array($criteria_rows)): ?>
                <div class="criteria-table">
                    <h3>Expert Rating Criteria Table</h3>
                    <table class="criteria-grid">
                        <thead>
                            <tr>
                                <th>Criteria</th>
                                <th>Weight</th>
                                <th>Test Method</th>
                                <th>Top Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($criteria_rows as $row): ?>
                                <tr>
                                    <td><?php echo esc_html($row['criteria']); ?></td>
                                    <td><?php echo esc_html($row['weight']); ?></td>
                                    <td><?php echo esc_html($row['method']); ?></td>
                                    <td>10/10</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>

            <?php if ($sources): ?>
                <div class="data-sources" style="margin-top: 20px;">
                    <strong>Sources:</strong> <?php echo esc_html($sources); ?>
                </div>
            <?php endif; ?>
        </section>
        <?php
        return ob_get_clean();
    }

    /**
     * Render Disclaimer Block (Section 8)
     */
    public function render_disclaimer_block($attributes)
    {
        $text = !empty($attributes['text']) ? $attributes['text'] : '';
        if (empty($text))
            return '';

        ob_start();
        ?>
        <section class="top10-disclaimer"
            style="font-size: 0.85em; color: #666; margin-top: 40px; border-top: 1px solid #ddd; padding-top: 20px;">
            <?php echo wp_kses_post($text); ?>
        </section>
        <?php
        return ob_get_clean();
    }

}
