<?php
/**
 * Plugin Name: Product Card Plugin
 * Description: Dynamic product card Gutenberg block with status-based themes (Green for Legit, Red for Scam)
 * Version: 2.2.5
 * Author: Bishow Shrestha
 * License: GPL v2 or later
 * Text Domain: product-card-plugin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PCP_VERSION', '2.2.5');
define('PCP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PCP_PLUGIN_URL', plugin_dir_url(__FILE__));

class ProductCardPlugin
{

    public function __construct()
    {
        // Enqueue styles and scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));

        // Register Gutenberg block
        add_action('init', array($this, 'register_gutenberg_block'));
    }

    /**
     * Enqueue CSS and JS for frontend
     */
    public function enqueue_assets()
    {
        wp_enqueue_style('product-card-styles', PCP_PLUGIN_URL . 'assets/css/product-card.css', array(), PCP_VERSION);
        wp_enqueue_script('product-card-scripts', PCP_PLUGIN_URL . 'assets/js/product-card.js', array('jquery'), PCP_VERSION, true);
    }

    /**
     * Enqueue block editor assets
     */
    public function enqueue_block_editor_assets()
    {
        wp_enqueue_style('product-card-editor-styles', PCP_PLUGIN_URL . 'assets/css/product-card.css', array(), PCP_VERSION);
    }

    /**
     * Register Gutenberg block
     */
    public function register_gutenberg_block()
    {
        // Register block script
        wp_register_script(
            'product-card-block',
            PCP_PLUGIN_URL . 'assets/js/block.js',
            array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
            PCP_VERSION
        );

        // Register the block with all attributes
        register_block_type('product-card-plugin/product-card', array(
            'editor_script' => 'product-card-block',
            'render_callback' => array($this, 'render_product_card_block'),
            'attributes' => array(
                'status' => array(
                    'type' => 'string',
                    'default' => 'legit'
                ),
                'productName' => array(
                    'type' => 'string',
                    'default' => 'Gutoptim'
                ),
                'productCategory' => array(
                    'type' => 'string',
                    'default' => 'Supplement'
                ),
                'imageUrl' => array(
                    'type' => 'string',
                    'default' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=800'
                ),
                'userExperience' => array(
                    'type' => 'string',
                    'default' => 'Fewer night bathroom trips, stronger urine flow, and better sleep quality'
                ),
                'expertReview1' => array(
                    'type' => 'string',
                    'default' => 'Triple Therapy combines heat, massage, and compression to improve circulation, ease soreness, and support daily recovery over time.'
                ),
                'expertReview2' => array(
                    'type' => 'string',
                    
                    'default' => 'Customizable settings with five heat levels and three massage modes tailor relief intensity to your comfort and routine.'
                ),
                'expertReview3' => array(
                    'type' => 'string',
                    'default' => 'Portable rechargeable design makes consistent use easy at home, at work, or while traveling between daily appointments.'
                ),
                'verdict' => array(
                    'type' => 'string',
                    'default' => 'A reliable pick with consistent results, practical value, and noticeable improvements for daily relief.'
                ),
                'rating' => array(
                    'type' => 'string',
                    'default' => '4.9'
                ),
                'readReviewsUrl' => array(
                    'type' => 'string',
                    'default' => '#'
                ),
                'price' => array(
                    'type' => 'string',
                    'default' => '69'
                ),
                'originalPrice' => array(
                    'type' => 'string',
                    'default' => '99'
                ),
                'currency' => array(
                    'type' => 'string',
                    'default' => '$'
                ),
                'ctaLabel' => array(
                    'type' => 'string',
                    'default' => 'Buy Now'
                ),
                'ctaUrl' => array(
                    'type' => 'string',
                    'default' => '#'
                ),
                'headerColor' => array(
                    'type' => 'string',
                    'default' => '#000000'
                ),
                'reviewSummary' => array(
                    'type' => 'string',
                    'default' => ''
                ),
            )





        ));
    }


    /**
     * Render product card block using attributes
     */
    public function render_product_card_block($attributes)
    {
        // Get attributes with defaults
        $status = isset($attributes['status']) ? $attributes['status'] : 'legit';
        $productName = isset($attributes['productName']) ? $attributes['productName'] : 'Gutoptim';
        $productCategory = isset($attributes['productCategory']) ? $attributes['productCategory'] : 'Supplement';
        $imageUrl = isset($attributes['imageUrl']) ? $attributes['imageUrl'] : '';
        $userExperience = isset($attributes['userExperience']) ? $attributes['userExperience'] : '';
        $userExperience = $this->trim_words($userExperience, 20);

        $expertReview1 = isset($attributes['expertReview1']) ? $attributes['expertReview1'] : '';
        $expertReview2 = isset($attributes['expertReview2']) ? $attributes['expertReview2'] : '';
        $expertReview3 = isset($attributes['expertReview3']) ? $attributes['expertReview3'] : '';
        $expertReview1 = $this->trim_words($expertReview1, 20);
        $expertReview2 = $this->trim_words($expertReview2, 20);
        $expertReview3 = $this->trim_words($expertReview3, 20);

        $verdict = isset($attributes['verdict']) ? $attributes['verdict'] : '';

        $rating = isset($attributes['rating']) ? $attributes['rating'] : '4.9';
        $readReviewsUrl = isset($attributes['readReviewsUrl']) ? $attributes['readReviewsUrl'] : '#';
        $price = isset($attributes['price']) ? $attributes['price'] : '69';
        $originalPrice = isset($attributes['originalPrice']) ? $attributes['originalPrice'] : '99';
        $currency = isset($attributes['currency']) ? $attributes['currency'] : '$';
        $ctaLabel = isset($attributes['ctaLabel']) ? $attributes['ctaLabel'] : 'Buy Now';
        $ctaUrl = isset($attributes['ctaUrl']) ? $attributes['ctaUrl'] : '#';
        $ctaHref = (!empty($ctaUrl) && $ctaUrl !== '#') ? $ctaUrl : 'javascript:void(0);';
        $headerColor = isset($attributes['headerColor']) ? $attributes['headerColor'] : '#000000';
        $reviewSummary = isset($attributes['reviewSummary']) ? $attributes['reviewSummary'] : '';

        $statusClass = $status === 'scam' ? 'pcpx-status-scam' : 'pcpx-status-legit';
        $statusLabel = ucfirst($status);
        $cardClass = $status === 'scam' ? 'pcpx-card-scam' : 'pcpx-card-legit';
        $reviewHeaderColor = '#ffffff';
        $reviewHeaderText = ($productName ? $productName . ' ' : '') . 'Review Summary';
        $schemaMarkup = $this->build_product_card_schema(array(
            'status' => $status,
            'productName' => $productName,
            'productCategory' => $productCategory,
            'imageUrl' => $imageUrl,
            'userExperience' => $userExperience,
            'expertReview1' => $expertReview1,
            'expertReview2' => $expertReview2,
            'expertReview3' => $expertReview3,
            'verdict' => $verdict,
            'rating' => $rating,
            'readReviewsUrl' => $readReviewsUrl,
            'price' => $price,
            'originalPrice' => $originalPrice,
            'currency' => $currency,
            'ctaLabel' => $ctaLabel,
            'ctaUrl' => $ctaUrl,
            'headerColor' => $headerColor,
            'reviewSummary' => $reviewSummary,
        ));

        ob_start();
        ?>
        <div class="pcpx-product-card-outer">
            <div class="pcpx-card <?php echo esc_attr($cardClass); ?>">
                <div class="pcpx-review-summary-header-main" style="color: <?php echo esc_attr($reviewHeaderColor); ?>;">
                    <?php echo esc_html($reviewHeaderText); ?>
                </div>
                <div class="pcpx-card-inner">
                    <div class="pcpx-image-column">
                        <div class="pcpx-image-container">
                            <?php if ($imageUrl): ?>
                                <img src="<?php echo esc_url($imageUrl); ?>" alt="<?php echo esc_attr($productName); ?>"
                                    class="pcpx-product-image" />
                            <?php else: ?>
                                <div class="pcpx-image-placeholder">No Image</div>
                            <?php endif; ?>
                        </div>
                        <div class="pcpx-price-section">
                            <?php if ($originalPrice && $originalPrice !== $price): ?>
                                <span class="pcpx-original-price">
                                    <?php echo esc_html($currency . $originalPrice); ?>
                                </span>
                            <?php endif; ?>
                            <span class="pcpx-price">
                                <?php echo esc_html((strpos(trim($price), $currency) === 0 ? '' : $currency) . $price); ?>
                            </span>
                        </div>
                        <a href="<?php echo esc_attr($ctaHref); ?>" class="pcpx-cta-button" target="_blank" rel="noopener">
                            <span class="pcpx-cta-main-text"><?php echo esc_html($ctaLabel); ?></span>
                            <span class="pcpx-cta-sub-text">from Official Website</span>
                        </a>
                    </div>
                    <div class="pcpx-content-column">
                        <div class="pcpx-rating-row">
                            <?php if ($rating): ?>
                                <div class="pcpx-rating-stars">
                                    <?php
                                    $ratingValue = max(0, min(5, floatval($rating)));
                                    echo '<span class="pcpx-stars">';
                                    for ($i = 1; $i <= 5; $i++) {
                                        $fill = max(0, min(1, $ratingValue - ($i - 1)));
                                        $percent = (int) round($fill * 100);
                                        echo '<span class="pcpx-star">';
                                        echo '<span class="pcpx-star-fill" style="width:' . $percent . '%">★</span>';
                                        echo '<span class="pcpx-star-outline">★</span>';
                                        echo '</span>';
                                    }
                                    echo '</span>';
                                    echo ' ' . rtrim(rtrim(number_format($ratingValue, 1, '.', ''), '0'), '.') . '/5';
                                    ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($readReviewsUrl): ?>
                                <a class="pcpx-read-reviews" href="<?php echo esc_url($readReviewsUrl); ?>" target="_blank" rel="noopener">Read reviews</a>
                            <?php endif; ?>
                        </div>

                        <?php if ($userExperience): ?>
                            <div class="pcpx-section">
                                <div class="pcpx-section-label" data-icon="experience"
                                    style="color: <?php echo esc_attr($headerColor); ?>;">User Experience</div>
                                <p class="pcpx-section-text"><?php echo esc_html($userExperience); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($expertReview1 || $expertReview2 || $expertReview3): ?>
                            <div class="pcpx-section">
                                <div class="pcpx-section-label" data-icon="reviews"
                                    style="color: <?php echo esc_attr($headerColor); ?>;">Expert Reviews</div>
                                <ul class="pcpx-expert-reviews-list">
                                    <?php if ($expertReview1): ?>
                                        <li><?php echo esc_html($expertReview1); ?></li>
                                    <?php endif; ?>
                                    <?php if ($expertReview2): ?>
                                        <li><?php echo esc_html($expertReview2); ?></li>
                                    <?php endif; ?>
                                    <?php if ($expertReview3): ?>
                                        <li><?php echo esc_html($expertReview3); ?></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php if ($verdict): ?>
                            <div class="pcpx-verdict-box">
                                <span class="pcpx-verdict-icon"></span>
                                <div><strong>Final Verdict:</strong> <?php echo esc_html($verdict); ?></div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
        <?php if ($schemaMarkup): ?>
            <script type="application/ld+json"><?php echo $schemaMarkup; ?></script>
        <?php endif; ?>
        <?php
        return ob_get_clean();
    }

    private function build_product_card_schema($attributes)
    {
        $productName = isset($attributes['productName']) ? trim(wp_strip_all_tags((string) $attributes['productName'])) : '';
        if ($productName === '') {
            return '';
        }

        $productCategory = isset($attributes['productCategory']) ? trim(wp_strip_all_tags((string) $attributes['productCategory'])) : '';
        $imageUrl = isset($attributes['imageUrl']) ? esc_url_raw((string) $attributes['imageUrl']) : '';
        $status = isset($attributes['status']) ? trim(wp_strip_all_tags((string) $attributes['status'])) : '';
        $userExperience = isset($attributes['userExperience']) ? trim(wp_strip_all_tags((string) $attributes['userExperience'])) : '';
        $expertReview1 = isset($attributes['expertReview1']) ? trim(wp_strip_all_tags((string) $attributes['expertReview1'])) : '';
        $expertReview2 = isset($attributes['expertReview2']) ? trim(wp_strip_all_tags((string) $attributes['expertReview2'])) : '';
        $expertReview3 = isset($attributes['expertReview3']) ? trim(wp_strip_all_tags((string) $attributes['expertReview3'])) : '';
        $verdict = isset($attributes['verdict']) ? trim(wp_strip_all_tags((string) $attributes['verdict'])) : '';
        $rating = isset($attributes['rating']) ? floatval($attributes['rating']) : 0;
        $readReviewsUrl = isset($attributes['readReviewsUrl']) ? esc_url_raw((string) $attributes['readReviewsUrl']) : '';
        $price = isset($attributes['price']) ? trim(wp_strip_all_tags((string) $attributes['price'])) : '';
        $originalPrice = isset($attributes['originalPrice']) ? trim(wp_strip_all_tags((string) $attributes['originalPrice'])) : '';
        $currency = isset($attributes['currency']) ? trim(wp_strip_all_tags((string) $attributes['currency'])) : '$';
        $ctaLabel = isset($attributes['ctaLabel']) ? trim(wp_strip_all_tags((string) $attributes['ctaLabel'])) : '';
        $ctaUrl = isset($attributes['ctaUrl']) ? esc_url_raw((string) $attributes['ctaUrl']) : '';
        $headerColor = isset($attributes['headerColor']) ? trim(wp_strip_all_tags((string) $attributes['headerColor'])) : '';
        $reviewSummary = isset($attributes['reviewSummary']) ? trim(wp_strip_all_tags((string) $attributes['reviewSummary'])) : '';

        $descriptionParts = array_filter(array(
            $reviewSummary,
            $userExperience,
            $expertReview1,
            $expertReview2,
            $expertReview3,
            $verdict,
        ));

        $additionalProperties = array();
        foreach (array(
            'status' => $status,
            'productCategory' => $productCategory,
            'reviewSummary' => $reviewSummary,
            'userExperience' => $userExperience,
            'expertReview1' => $expertReview1,
            'expertReview2' => $expertReview2,
            'expertReview3' => $expertReview3,
            'verdict' => $verdict,
            'readReviewsUrl' => $readReviewsUrl,
            'ctaLabel' => $ctaLabel,
            'headerColor' => $headerColor,
            'originalPrice' => $originalPrice,
        ) as $name => $value) {
            if ($value === '') {
                continue;
            }

            $additionalProperties[] = array(
                '@type' => 'PropertyValue',
                'name' => $name,
                'value' => $value,
            );
        }

        $schema = array(
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $productName,
            'category' => $productCategory,
        );

        if ($imageUrl !== '') {
            $schema['image'] = array($imageUrl);
        }

        $description = implode(' ', $descriptionParts);
        if ($description !== '') {
            $schema['description'] = $description;
        }

        if ($rating > 0) {
            $schema['aggregateRating'] = array(
                '@type' => 'AggregateRating',
                'ratingValue' => max(0, min(5, $rating)),
                'bestRating' => 5,
                'worstRating' => 0,
            );
        }

        if ($price !== '') {
            $offer = array(
                '@type' => 'Offer',
                'priceCurrency' => $currency,
                'price' => preg_replace('/[^0-9.]/', '', $price),
                'availability' => 'https://schema.org/InStock',
            );

            if ($ctaUrl !== '') {
                $offer['url'] = $ctaUrl;
            }

            $schema['offers'] = $offer;
        }

        if (!empty($additionalProperties)) {
            $schema['additionalProperty'] = $additionalProperties;
        }

        return wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private function trim_words($text, $max_words) {
        $text = trim(wp_strip_all_tags((string) $text));
        if ($text === '') {
            return '';
        }
        $words = preg_split('/\s+/', $text);
        if (!$words || count($words) <= $max_words) {
            return $text;
        }
        $trimmed = array_slice($words, 0, $max_words);
        return implode(' ', $trimmed);
    }
}

// Initialize the plugin
new ProductCardPlugin();
