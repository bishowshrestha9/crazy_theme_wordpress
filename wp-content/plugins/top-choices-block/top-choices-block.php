<?php
/**
 * Plugin Name: Top Choices Block
 * Plugin URI: https://yourwebsite.com
 * Description: Premium product comparison list block with accurate dimensions, badge dropdowns, and anchor scrolling.
 * Version: 1.0.0
 * Author: Antigravity
 * License: GPL v2 or later
 * Text Domain: top-choices-block
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class TopChoicesBlockPlugin
{
    public function __construct()
    {
        add_action('init', array($this, 'register_block'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
    }

    /**
     * Register Gutenberg block using block.json metadata
     */
    public function register_block()
    {
        if (!function_exists('register_block_type')) {
            return;
        }

        register_block_type(plugin_dir_path(__FILE__), array(
            'render_callback' => array($this, 'render_block_callback')
        ));
    }

    /**
     * Enqueue assets for front-end
     */
    public function enqueue_frontend_assets()
    {
        wp_enqueue_style(
            'top-choices-google-fonts',
            'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap',
            array(),
            null
        );

        wp_enqueue_script(
            'top-choices-frontend-js',
            plugin_dir_url(__FILE__) . 'assets/js/frontend.js',
            array(),
            '1.0.0',
            true
        );
    }

    /**
     * Render callback for the Gutenberg block
     */
    public function render_block_callback($attributes)
    {
        $cards = isset($attributes['cards']) && is_array($attributes['cards']) ? $attributes['cards'] : array();

        if (empty($cards)) {
            return '';
        }

        // Limit the number of cards to prevent memory/resource exhaustion
        $max_cards = 100;
        if (count($cards) > $max_cards) {
            $cards = array_slice($cards, 0, $max_cards);
        }

        // Helper to format feature text (no bolding)
        $format_feature = function($text) {
            return trim($text);
        };

        // Perform strict backend sanitization on all card attributes to prevent XSS and TypeErrors
        $sanitized_cards = array();
        foreach ($cards as $card) {
            if (!is_array($card)) {
                continue;
            }
            $sanitized_card = array(
                'tag' => isset($card['tag']) && is_string($card['tag']) ? sanitize_text_field($card['tag']) : 'none',
                'productName' => isset($card['productName']) && is_string($card['productName']) ? sanitize_text_field($card['productName']) : '',
                'subTitle' => isset($card['subTitle']) && is_string($card['subTitle']) ? sanitize_text_field($card['subTitle']) : '',
                'imageUrl' => isset($card['imageUrl']) && is_string($card['imageUrl']) ? esc_url_raw($card['imageUrl']) : '',
                'feature1' => isset($card['feature1']) && is_string($card['feature1']) ? sanitize_text_field($card['feature1']) : '',
                'feature2' => isset($card['feature2']) && is_string($card['feature2']) ? sanitize_text_field($card['feature2']) : '',
                'feature3' => isset($card['feature3']) && is_string($card['feature3']) ? sanitize_text_field($card['feature3']) : '',
                'rating' => isset($card['rating']) && is_string($card['rating']) ? sanitize_text_field($card['rating']) : '5/5',
                'price' => isset($card['price']) && is_string($card['price']) ? sanitize_text_field($card['price']) : '',
                'btnText' => isset($card['btnText']) && is_string($card['btnText']) ? sanitize_textarea_field($card['btnText']) : 'Read More',
                'btnUrl' => isset($card['btnUrl']) && is_string($card['btnUrl']) ? esc_url_raw($card['btnUrl']) : '#'
            );
            $sanitized_cards[] = $sanitized_card;
        }
        $cards = $sanitized_cards;

        ob_start();
        ?>
        <div class="top-choices-container">
            <?php foreach ($cards as $index => $card) : 
                $number = $index + 1;
                $tag = $card['tag'];
                $productName = esc_html($card['productName']);
                $subTitle = esc_html($card['subTitle']);
                $imageUrl = esc_url($card['imageUrl']);
                $feature1 = esc_html($card['feature1']);
                if (mb_strlen($feature1) > 35) {
                    $feature1 = mb_strimwidth($feature1, 0, 35, '...');
                }
                $feature2 = esc_html($card['feature2']);
                if (mb_strlen($feature2) > 35) {
                    $feature2 = mb_strimwidth($feature2, 0, 35, '...');
                }
                $feature3 = esc_html($card['feature3']);
                if (mb_strlen($feature3) > 35) {
                    $feature3 = mb_strimwidth($feature3, 0, 35, '...');
                }
                $rating = esc_html($card['rating']);
                $price = esc_html($card['price']);
                $btnText = esc_html($card['btnText']);
                $btnUrl = esc_url($card['btnUrl']);

                
                // Determine clean rating number for stars
                $ratingNum = 5.0;
                if (preg_match('/([0-9.]+)/', $rating, $matches)) {
                    $val = floatval($matches[1]);
                    // Check if rating is out of 10
                    if (strpos($rating, '/10') !== false || ($val > 5 && $val <= 10)) {
                        $ratingNum = $val / 2;
                    } elseif ($val <= 5) {
                        $ratingNum = $val;
                    }
                }
                
                // Format multi-line button text
                $btnTextLines = explode("\n", $btnText);
            ?>
                <div class="top-choices-card <?php echo esc_attr('tag-' . $tag); ?>">
                    
                    <!-- Top Right Badge/Tag -->
                    <?php if ($tag !== 'none') : ?>
                        <div class="top-choices-badge">
                            <span><?php echo esc_html($tag); ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="top-choices-card-inner">
                        <!-- Number Circle -->
                        <div class="top-choices-number">
                            <span><?php echo esc_html($number); ?></span>
                        </div>

                        <!-- Image Section -->
                        <div class="top-choices-image-wrap">
                            <?php if ($imageUrl) : ?>
                                <img src="<?php echo $imageUrl; ?>" alt="<?php echo $productName; ?>" />
                            <?php else : ?>
                                <div class="top-choices-image-placeholder">
                                    <svg viewBox="0 0 24 24" width="36" height="36" fill="currentColor">
                                        <path d="M19 5v14H5V5h14m0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-4.86 8.86l-3 3.87L9 13.14 6 17h12l-3.86-5.14z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Main Content Info -->
                        <div class="top-choices-info">
                            <div class="top-choices-title"><?php echo $productName; ?></div>
                            <div class="top-choices-subtitle"><?php echo $subTitle; ?></div>
                            
                            <ul class="top-choices-features">
                                 <?php if ($feature1) : ?>
                                     <li>
                                         <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                             <polyline points="20 6 9 17 4 12"></polyline>
                                         </svg>
                                         <span><?php echo $format_feature($feature1); ?></span>
                                     </li>
                                 <?php endif; ?>
                                 <?php if ($feature2) : ?>
                                     <li>
                                         <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                             <polyline points="20 6 9 17 4 12"></polyline>
                                         </svg>
                                         <span><?php echo $format_feature($feature2); ?></span>
                                     </li>
                                 <?php endif; ?>
                                 <?php if ($feature3) : ?>
                                     <li>
                                         <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                             <polyline points="20 6 9 17 4 12"></polyline>
                                         </svg>
                                         <span><?php echo $format_feature($feature3); ?></span>
                                     </li>
                                 <?php endif; ?>
                            </ul>

                            <a href="#" class="top-choices-see-more" data-product-name="<?php echo esc_attr($productName); ?>">See More</a>
                        </div>

                        <!-- Review & Price Container (99x91) -->
                        <div class="top-choices-review-price-wrap">
                            <div class="top-choices-review-container">
                                <div class="top-choices-rating-num"><?php echo $rating; ?></div>
                                <div class="top-choices-stars">
                                    <?php 
                                    for ($i = 1; $i <= 5; $i++) {
                                        $diff = $ratingNum - ($i - 1);
                                        if ($diff >= 1) {
                                            // Fully filled star
                                            echo '<svg viewBox="0 0 24 24" width="12" height="12" fill="#ffbe00"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>';
                                        } elseif ($diff > 0) {
                                            // Partially filled star
                                            $percentage = round($diff * 100);
                                            $grad_id = 'star-grad-' . $index . '-' . $i . '-' . $percentage;
                                            echo '<svg viewBox="0 0 24 24" width="12" height="12">';
                                            echo '<defs>';
                                            echo '<linearGradient id="' . $grad_id . '">';
                                            echo '<stop offset="' . $percentage . '%" stop-color="#ffbe00" />';
                                            echo '<stop offset="' . $percentage . '%" stop-color="#e2e8f0" />';
                                            echo '</linearGradient>';
                                            echo '</defs>';
                                            echo '<path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" fill="url(#' . $grad_id . ')" />';
                                            echo '</svg>';
                                        } else {
                                            // Empty star
                                            echo '<svg viewBox="0 0 24 24" width="12" height="12" fill="#e2e8f0"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="top-choices-price"><?php echo $price; ?></div>
                        </div>

                        <!-- Buy Now Button (200x52) -->
                        <div class="top-choices-btn-wrap">
                            <a href="<?php echo $btnUrl; ?>" target="_blank" rel="noopener noreferrer" class="top-choices-btn">
                                <span class="btn-line">Read More</span>
                                <span class="btn-line">On Official Website</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php
            // Generate Schema.org JSON-LD markup
            $itemListElements = array();
            foreach ($cards as $index => $card) {
                $number = $index + 1;
                $productName = isset($card['productName']) ? esc_html($card['productName']) : '';
                $subTitle = isset($card['subTitle']) ? esc_html($card['subTitle']) : '';
                $imageUrl = isset($card['imageUrl']) ? esc_url($card['imageUrl']) : '';
                $rating = isset($card['rating']) ? esc_html($card['rating']) : '5/5';
                $price = isset($card['price']) ? esc_html($card['price']) : '';
                $btnUrl = isset($card['btnUrl']) ? esc_url($card['btnUrl']) : '#';

                // Parse rating value and scale
                $ratingValue = 5.0;
                $bestRating = 5.0;
                if (preg_match('/([0-9.]+)/', $rating, $matches)) {
                    $val = floatval($matches[1]);
                    if (strpos($rating, '/10') !== false || ($val > 5 && $val <= 10)) {
                        $ratingValue = $val;
                        $bestRating = 10.0;
                    } else {
                        $ratingValue = $val;
                        $bestRating = 5.0;
                    }
                }

                // Parse price numerical value
                $priceValue = 0.0;
                if (preg_match('/([0-9.]+)/', $price, $matches)) {
                    $priceValue = floatval($matches[1]);
                }

                // Parse currency symbol
                $currency = 'USD';
                if (strpos($price, '€') !== false) {
                    $currency = 'EUR';
                } elseif (strpos($price, '£') !== false) {
                    $currency = 'GBP';
                }

                $productItem = array(
                    '@type' => 'ListItem',
                    'position' => $number,
                    'item' => array(
                        '@type' => 'Product',
                        'name' => $productName ?: 'Product ' . $number,
                        'description' => $subTitle ?: 'Product choice #' . $number,
                        'offers' => array(
                            '@type' => 'Offer',
                            'price' => $priceValue ? number_format($priceValue, 2, '.', '') : '0.00',
                            'priceCurrency' => $currency,
                            'url' => $btnUrl
                        ),
                        'aggregateRating' => array(
                            '@type' => 'AggregateRating',
                            'ratingValue' => (string)$ratingValue,
                            'bestRating' => (string)$bestRating,
                            'ratingCount' => '97'
                        )
                    )
                );

                if ($imageUrl) {
                    $productItem['item']['image'] = $imageUrl;
                }

                $itemListElements[] = $productItem;
            }

            $schema = array(
                '@context' => 'https://schema.org',
                '@type' => 'ItemList',
                'numberOfItems' => count($itemListElements),
                'itemListElement' => $itemListElements
            );
            ?>
            <script type="application/ld+json">
                <?php echo json_encode($schema, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); ?>
            </script>
        </div>
        <?php
        return ob_get_clean();
    }
}

new TopChoicesBlockPlugin();
