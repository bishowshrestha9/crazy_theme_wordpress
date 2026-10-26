<?php
/**
 * Plugin Name: Product Card Plugin
 * Plugin URI: https://yourwebsite.com
 * Description: Dynamic product card Gutenberg block with status-based themes (Green for Legit, Red for Scam)
 * Version: 2.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * Text Domain: product-card-plugin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PCP_VERSION', '2.2.4');
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
                'observation' => array(
                    'type' => 'string',
                    'default' => 'Confirms prostate support via DHT (5-alpha reductase) inhibition and anti-inflammatory action'
                ),
                'verdict' => array(
                    'type' => 'string',
                    'default' => 'Gutoptim is a Genuine Prostate Support Supplement'
                ),
                'bestFor' => array(
                    'type' => 'string',
                    'default' => 'People over 50 with Urinary Track Infection'
                ),
                'rating' => array(
                    'type' => 'string',
                    'default' => '5'
                ),
                'certification' => array(
                    'type' => 'string',
                    'default' => 'FDA/GMP Approved'
                ),
                'moneyBackGuarantee' => array(
                    'type' => 'string',
                    'default' => '60 Days'
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
                    'default' => '#2563eb'
                ),
                'reviewSummary' => array(
                    'type' => 'string',
                    'default' => ''
                )
            )





        ));
    }


    /**
     * Render product card block using attributes
     */
    public function render_product_card_block($attributes)
    {
        // 1. Sitemap & Feed Guard: Do not render HTML on sitemaps, RSS feeds, or XML requests
        if (is_feed() || (function_exists('is_sitemap') && is_sitemap())) {
            return '';
        }

        if (isset($_SERVER['REQUEST_URI'])) {
            $request_uri = strtolower($_SERVER['REQUEST_URI']);
            if (strpos($request_uri, 'sitemap') !== false || strpos($request_uri, '.xml') !== false) {
                return '';
            }
        }

        // 2. Post Type Guard: Render on single posts, pages, or singular views
        $post_type = get_post_type();
        if ($post_type && !in_array($post_type, array('post', 'page'), true) && !is_singular()) {
            return '';
        }

        // Get attributes with defaults
        $status = isset($attributes['status']) ? $attributes['status'] : 'legit';
        $productName = isset($attributes['productName']) ? $attributes['productName'] : 'Gutoptim';
        $productCategory = isset($attributes['productCategory']) ? $attributes['productCategory'] : 'Supplement';
        $imageUrl = isset($attributes['imageUrl']) ? $attributes['imageUrl'] : '';
        $userExperience = isset($attributes['userExperience']) ? $attributes['userExperience'] : '';
        if (mb_strlen($userExperience) > 95) {
            $userExperience = mb_strimwidth($userExperience, 0, 95, '...');
        }

        $observation = isset($attributes['observation']) ? $attributes['observation'] : '';
        if (mb_strlen($observation) > 95) {
            $observation = mb_strimwidth($observation, 0, 95, '...');
        }

        $verdict = isset($attributes['verdict']) ? $attributes['verdict'] : '';
        if (mb_strlen($verdict) > 70) {
            $verdict = mb_strimwidth($verdict, 0, 70, '...');
        }

        $bestFor = isset($attributes['bestFor']) ? $attributes['bestFor'] : '';
        $rating = isset($attributes['rating']) ? $attributes['rating'] : '5';
        $certification = isset($attributes['certification']) ? $attributes['certification'] : '';
        $moneyBackGuarantee = isset($attributes['moneyBackGuarantee']) ? $attributes['moneyBackGuarantee'] : '';
        $price = isset($attributes['price']) ? $attributes['price'] : '69';
        $originalPrice = isset($attributes['originalPrice']) ? $attributes['originalPrice'] : '99';
        $currency = isset($attributes['currency']) ? $attributes['currency'] : '$';
        $ctaLabel = isset($attributes['ctaLabel']) ? $attributes['ctaLabel'] : 'Buy Now';
        $ctaUrl = isset($attributes['ctaUrl']) ? $attributes['ctaUrl'] : '#';
        $headerColor = isset($attributes['headerColor']) ? $attributes['headerColor'] : '#2563eb';
        $reviewSummary = isset($attributes['reviewSummary']) ? $attributes['reviewSummary'] : '';

        // Use ACF fields for all product card values, falling back to block attributes if empty
        $acfProductName = function_exists('get_field') ? get_field('product_name') : '';
        if ($acfProductName) $productName = $acfProductName;

        $acfImageUrl = function_exists('get_field') ? get_field('product_image') : '';
        if ($acfImageUrl) $imageUrl = $acfImageUrl;

        $acfPrice = function_exists('get_field') ? get_field('price') : '';
        if ($acfPrice) {
            $price = $acfPrice;
            // Automatically calculate original price (100% increase)
            $numericPrice = floatval(preg_replace('/[^0-9.]/', '', $price));
            $originalPrice = $numericPrice > 0 ? ceil($numericPrice * 2) : '';
        }

        $currencyValue = function_exists('get_field') ? get_field('currency') : '';
        if ($currencyValue) {
            $currency = $currencyValue;
        }

        $acfRating = function_exists('get_field') ? get_field('rating') : '';
        if ($acfRating) $rating = $acfRating;

        $acfCertification = function_exists('get_field') ? get_field('certification') : '';
        if ($acfCertification) $certification = $acfCertification;

        $acfReturnPolicy = function_exists('get_field') ? get_field('return_policy') : '';
        if ($acfReturnPolicy) $moneyBackGuarantee = $acfReturnPolicy;

        $acfBuyLink = function_exists('get_field') ? get_field('buy_link') : '';
        if ($acfBuyLink) $ctaUrl = $acfBuyLink;

        $statusClass = $status === 'scam' ? 'pcpx-status-scam' : 'pcpx-status-legit';
        $statusLabel = ucfirst($status);
        $cardClass = $status === 'scam' ? 'pcpx-card-scam' : 'pcpx-card-legit';
        $reviewHeaderColor = $status === 'scam' ? '#ef4444' : '#10b981';
        $reviewHeaderText = ($productName ? $productName . ' ' : '') . 'Review Summary';

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
                        <a href="<?php echo esc_url($ctaUrl); ?>" class="pcpx-cta-button" target="_blank" rel="noopener">
                            <span class="pcpx-cta-main-text"><?php echo esc_html($ctaLabel); ?></span>
                            <span class="pcpx-cta-sub-text">from Official Website</span>
                        </a>
                    </div>
                    <div class="pcpx-content-column">

                        <?php if ($userExperience): ?>
                            <div class="pcpx-section">
                                <div class="pcpx-section-label" data-icon="experience"
                                    style="color: <?php echo esc_attr($headerColor); ?>;">User Experience</div>
                                <p class="pcpx-section-text"><?php echo esc_html($userExperience); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($observation): ?>
                            <div class="pcpx-section">
                                <div class="pcpx-section-label" data-icon="observation"
                                    style="color: <?php echo esc_attr($headerColor); ?>;">Our Observation</div>
                                <p class="pcpx-section-text"><?php echo esc_html($observation); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($verdict): ?>
                            <div class="pcpx-verdict-box">
                                <span class="pcpx-verdict-icon"><?php echo $status === 'scam' ? '✕' : '✓'; ?></span>
                                <div><strong>Verdict:</strong> <?php echo esc_html($verdict); ?></div>
                            </div>
                        <?php endif; ?>

                        <div class="pcpx-info-grid">
                            <?php if ($rating): ?>
                                <div class="pcpx-info-item">
                                    <div class="pcpx-info-label" data-icon="rating"
                                        style="color: <?php echo esc_attr($headerColor); ?>;">Expert Rating</div>
                                    <div class="pcpx-info-value pcpx-rating-value">
                                        <?php
                                        $ratingValue = min(5.0, max(0.0, floatval($rating)));
                                        $fullStars = min(5, max(0, (int) floor($ratingValue)));
                                        $halfStar = ($ratingValue - $fullStars) >= 0.5 && $fullStars < 5 ? 1 : 0;
                                        $emptyStars = min(5, max(0, 5 - $fullStars - $halfStar));
                                        echo '<span class="pcpx-stars">';
                                        echo str_repeat('★', $fullStars);
                                        if ($halfStar) {
                                            echo '<span style="position:relative;display:inline-block;width:1em;">';
                                            echo '<span style="color:#fbbf24;position:absolute;left:0;width:50%;overflow:hidden;">★</span>';
                                            echo '<span style="color:#e5e7eb;">★</span>';
                                            echo '</span>';
                                        }
                                        echo str_repeat('☆', $emptyStars);
                                        echo '</span>';
                                        echo ' ' . $ratingValue . '/5';
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($bestFor): ?>
                                <div class="pcpx-info-item">
                                    <div class="pcpx-info-label" data-icon="people"
                                        style="color: <?php echo esc_attr($headerColor); ?>;">Best for</div>
                                    <div class="pcpx-info-value"><?php echo esc_html($bestFor); ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if ($certification): ?>
                                <div class="pcpx-info-item" data-certification="true">
                                    <div class="pcpx-info-label" style="color: <?php echo esc_attr($headerColor); ?>;">Certification
                                    </div>
                                    <div class="pcpx-info-value"><?php echo esc_html($certification); ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if ($moneyBackGuarantee): ?>
                                <div class="pcpx-info-item">
                                    <div class="pcpx-info-label" data-icon="guarantee"
                                        style="color: <?php echo esc_attr($headerColor); ?>;">Money Back Guarantee</div>
                                    <div class="pcpx-info-value"><?php echo esc_html($moneyBackGuarantee); ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Initialize the plugin
new ProductCardPlugin();
