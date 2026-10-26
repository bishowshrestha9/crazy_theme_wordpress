<?php
/**
 * Helper Functions Class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Top10_Helpers
{

    /**
     * Render star rating
     */
    public static function render_stars($rating, $max = 5)
    {
        $rating = floatval($rating);
        $full_stars = floor($rating);
        $half_star = ($rating - $full_stars) >= 0.5;
        $empty_stars = $max - $full_stars - ($half_star ? 1 : 0);

        $output = '<div class="top10-star-rating" data-rating="' . esc_attr($rating) . '">';

        // Full stars
        for ($i = 0; $i < $full_stars; $i++) {
            $output .= '<span class="star star-full">★</span>';
        }

        // Half star
        if ($half_star) {
            $output .= '<span class="star star-half">★</span>';
        }

        // Empty stars
        for ($i = 0; $i < $empty_stars; $i++) {
            $output .= '<span class="star star-empty">☆</span>';
        }

        $output .= '<span class="rating-number">' . number_format($rating, 1) . '</span>';
        $output .= '</div>';

        return $output;
    }

    /**
     * Render rating bar for comparison table
     */
    public static function render_rating_bar($rating, $max = 5)
    {
        $percentage = ($rating / $max) * 100;

        $output = '<div class="top10-rating-bar">';
        $output .= '<div class="rating-bar-fill" style="width: ' . esc_attr($percentage) . '%"></div>';
        $output .= '<span class="rating-value">' . number_format($rating, 1) . '</span>';
        $output .= '</div>';

        return $output;
    }

    /**
     * Format price
     */
    public static function format_price($price)
    {
        if (empty($price)) {
            return '';
        }

        // If price already has currency symbol, return as is
        if (preg_match('/[$€£¥]/', $price)) {
            return $price;
        }

        // Otherwise, add dollar sign
        return '$' . $price;
    }

    /**
     * Get product data
     */
    public static function get_product_data($product_id)
    {
        $product = get_post($product_id);

        if (!$product || $product->post_type !== 'top10_product') {
            return null;
        }

        // Get meta data
        $features = get_post_meta($product->ID, '_top10_features', true);
        $pros = get_post_meta($product->ID, '_top10_pros', true);
        $cons = get_post_meta($product->ID, '_top10_cons', true);
        
        // Convert string to array if needed (backward compatibility)
        if (is_string($features)) {
            $features = array_filter(explode("\n", $features));
        }
        if (is_string($pros)) {
            $pros = array_filter(explode("\n", $pros));
        }
        if (is_string($cons)) {
            $cons = array_filter(explode("\n", $cons));
        }
        
        return array(
            'id' => $product->ID,
            'title' => $product->post_title,
            'content' => $product->post_content,
            'thumbnail' => get_the_post_thumbnail_url($product->ID, 'medium'),
            'price' => get_post_meta($product->ID, '_top10_price', true),
            'rating' => get_post_meta($product->ID, '_top10_rating', true),
            'affiliate_link' => get_post_meta($product->ID, '_top10_affiliate_link', true),
            'button_text' => get_post_meta($product->ID, '_top10_button_text', true) ?: 'Check Price',
            'badge_text' => get_post_meta($product->ID, '_top10_badge_text', true),
            'specs' => get_post_meta($product->ID, '_top10_specs', true) ?: array(),
            'features' => is_array($features) ? $features : array(),
            'pros' => is_array($pros) ? $pros : array(),
            'cons' => is_array($cons) ? $cons : array(),
        );
    }

    /**
     * Sanitize repeatable field
     */
    public static function sanitize_repeatable($data, $callback = 'sanitize_text_field')
    {
        if (!is_array($data)) {
            return array();
        }

        return array_filter(array_map($callback, $data));
    }
}
