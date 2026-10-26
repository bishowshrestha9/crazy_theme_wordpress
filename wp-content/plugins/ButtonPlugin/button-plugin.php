<?php
/**
 * Plugin Name: Button Plugin
 * Description: A custom Gutenberg block that shows a button which reveals an image when clicked.
 * Version: 1.0.0
 * Author: Bishow Shrestha
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Custom shortcode to fetch product fields reliably
function button_plugin_product_field_shortcode($atts)
{
    $atts = shortcode_atts([
        'name' => 'product_name',
        'post_id' => null
    ], $atts);

    $post_id = $atts['post_id'] ? intval($atts['post_id']) : get_the_ID();
    if (!$post_id) {
        $post_id = get_queried_object_id();
    }

    $field_name = $atts['name'];
    $value = '';

    // Try ACF first
    if (function_exists('get_field')) {
        $raw_value = get_field($field_name, $post_id);

        // Handle different return types
        if (is_string($raw_value)) {
            $value = $raw_value;
        } elseif (is_numeric($raw_value)) {
            $term = get_term($raw_value);
            if ($term && !is_wp_error($term)) {
                $value = $term->name;
            }
        } elseif (is_object($raw_value) && isset($raw_value->name)) {
            $value = $raw_value->name;
        } elseif (is_array($raw_value) && !empty($raw_value)) {
            $first = reset($raw_value);
            if (is_object($first) && isset($first->name)) {
                $value = $first->name;
            }
        }
    }

    // Fallback to post meta
    if (empty($value)) {
        $value = get_post_meta($post_id, $field_name, true);
    }

    return esc_html($value);
}
add_shortcode('product_field', 'button_plugin_product_field_shortcode');


function button_plugin_render_callback($attributes, $content)
{
    // 1. Fetch Context / Post ID
    $post_id = get_the_ID();
    if (!$post_id) {
        $post_id = get_queried_object_id();
    }

    // 2. Helper to extract string from ACF potential object/array/ID
    $extract_string = function ($val) {
        if (empty($val))
            return '';

        // Check if it's numeric (including numeric strings like "126")
        if (is_numeric($val)) {
            // Try as term first (common for categories)
            $term = get_term($val);
            if ($term && !is_wp_error($term)) {
                return $term->name;
            }
            // Try as post
            $post_title = get_the_title($val);
            if ($post_title && $post_title !== '') {
                return $post_title;
            }
            // Return the ID as string if nothing else works
            return (string) $val;
        }

        // String (non-numeric) - return as is
        if (is_string($val))
            return trim($val);

        // Object - extract name or title
        if (is_object($val)) {
            if (isset($val->name))
                return $val->name;
            if (isset($val->post_title))
                return $val->post_title;
            if (isset($val->label))
                return $val->label;
        }

        // Array - get first element
        if (is_array($val)) {
            $first = reset($val);
            if (is_object($first)) {
                if (isset($first->name))
                    return $first->name;
                if (isset($first->post_title))
                    return $first->post_title;
            }
            if (is_string($first))
                return trim($first);
            if (is_numeric($first)) {
                $term = get_term($first);
                if ($term && !is_wp_error($term))
                    return $term->name;
            }
        }

        return '';
    };

    // 3. Fetch Product Name
    $product_name = '';

    // Try ACF (let it return formatted value)
    if (function_exists('get_field')) {
        $raw_name = get_field('product_name', $post_id);
        $product_name = $extract_string($raw_name);
    }

    // Fallback to post meta
    if (empty($product_name)) {
        $product_name = get_post_meta($post_id, 'product_name', true);
        $product_name = $extract_string($product_name);
    }

    // Fallback to page title
    if (empty($product_name)) {
        $product_name = get_the_title($post_id);
    }

    // Fallback to attribute
    if (empty($product_name)) {
        $product_name = $attributes['productName'] ?? 'this product';
    }

    // 4. Fetch Product Category  
    $product_category = '';

    // Try ACF with multiple return format attempts
    if (function_exists('get_field')) {
        // First attempt: default format
        $raw_cat = get_field('product_category', $post_id);
        $product_category = $extract_string($raw_cat);

        // Second attempt: force object return
        if (empty($product_category) || is_numeric($product_category)) {
            $raw_cat = get_field('product_category', $post_id, true); // true = format value
            $product_category = $extract_string($raw_cat);
        }

        // Third attempt: get field object to check return format
        if (empty($product_category) || is_numeric($product_category)) {
            $field_obj = get_field_object('product_category', $post_id);
            if ($field_obj && isset($field_obj['value'])) {
                $product_category = $extract_string($field_obj['value']);
            }
        }
    }

    // Fallback to post meta
    if (empty($product_category) || is_numeric($product_category)) {
        $meta_cat = get_post_meta($post_id, 'product_category', true);
        if (!empty($meta_cat)) {
            $product_category = $extract_string($meta_cat);
        }
    }

    // Try WordPress taxonomy as fallback (common for categories)
    if (empty($product_category) || is_numeric($product_category)) {
        $terms = get_the_terms($post_id, 'product_category');
        if ($terms && !is_wp_error($terms)) {
            $first_term = reset($terms);
            $product_category = $first_term->name;
        }
    }

    // Last resort: if still numeric, try to convert it
    if (is_numeric($product_category)) {
        $term = get_term($product_category);
        if ($term && !is_wp_error($term)) {
            $product_category = $term->name;
        }
    }

    // Final fallback to attribute
    if (empty($product_category)) {
        $product_category = $attributes['productCategory'] ?? 'supplements';
    }

    // 3. Prepare Replacements
    // Ensure we don't break JSON with quotes
    $product_name_safe = htmlspecialchars_decode($product_name); // simpler for str_replace
    $product_category_safe = htmlspecialchars_decode($product_category);

    // 4. Logic for Loading Texts
    $loading_preset = $attributes['loadingPreset'] ?? 'set1';

    $presets = [
        'set1' => [
            "Finding better options than [Product Name] in [Product Category]…",
            "Checking what actually works (not what sounds good)…",
            "Shortlisting alternatives with clearer labels and dosages…",
            "Loading your recommended picks…"
        ],
        'set2' => [
            "Reviewing [Product Name] for red flags…",
            "Matching safer [Product Category] alternatives…",
            "Prioritizing transparency and real-world results…",
            "Loading trusted options…"
        ],
        'set3' => [
            "Finding better options than [Product Name] in [Product Category]…",
            "Checking what actually works (not what sounds good)…",
            "Shortlisting alternatives with clearer labels and dosages…",
            "Loading your recommended picks…"
        ],
        'set4' => [
            "Checking [Product Name]…",
            "Comparing top [Product Category] alternatives…",
            "Loading recommendations…"
        ]
    ];

    $raw_texts = $presets[$loading_preset] ?? $presets['set1'];
    $processed_texts = [];

    foreach ($raw_texts as $text) {
        $text = str_replace('[Product Name]', $product_name_safe, $text);
        $text = str_replace('[Product Category]', $product_category_safe, $text);
        $processed_texts[] = $text;
    }

    // JSON Encode for JS
    $loading_texts_json = htmlspecialchars(json_encode($processed_texts), ENT_QUOTES, 'UTF-8');

    // 5. Styles
    $button_color = $attributes['buttonColor'] ?? '#10B981';
    $button_text_color = $attributes['buttonTextColor'] ?? '#ffffff';

    // 6. Button Text Replacements
    // If the user hasn't set a custom text, default to using the placeholder so they see it works
    $raw_button_text = $attributes['buttonText'];
    if (empty($raw_button_text) || $raw_button_text === 'Find Alternatives') {
        $raw_button_text = 'Find Better Alternative to [Product Name]';
    }

    // IMPORTANT: Process shortcodes FIRST, then do placeholder replacements
    // This allows [acf field="product_name"] to work correctly
    $button_text = do_shortcode($raw_button_text);
    $button_text = str_replace('[Product Name]', $product_name_safe, $button_text);
    $button_text = str_replace('[Product Category]', $product_category_safe, $button_text);

    // 7. Output HTML
    $wrapper_attributes = get_block_wrapper_attributes(['class' => 'button-plugin-wrapper']);

    // Process Content Replacements (Enable Shortcodes & Placeholders inside Inner Blocks)
    // IMPORTANT: Process shortcodes FIRST, then do placeholder replacements
    $content = do_shortcode($content);
    $content = str_replace('[Product Name]', $product_name_safe, $content);
    $content = str_replace('[Product Category]', $product_category_safe, $content);

    ob_start();
    ?>
    <div <?php echo $wrapper_attributes; ?>>
        <div class="bp-button-wrapper" style="position: relative;">
            <button class="bp-reveal-button" data-loading-texts="<?php echo $loading_texts_json; ?>"
                style="background-color: <?php echo esc_attr($button_color); ?>; color: <?php echo esc_attr($button_text_color); ?>;">
                <span><?php echo $button_text; // Allowed html for shortcodes ?></span>
                <svg class="bp-reveal-icon" width="20" height="20" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" style="min-width: 20px;">
                    <path d="M6 9L12 15L18 9" stroke="currentColor" strokeWidth="3" strokeLinecap="round"
                        strokeLinejoin="round" />
                </svg>
            </button>
            <div class="bp-loading-container" style="display: none;">
                <div class="bp-progress-bar">
                    <div class="bp-progress-fill"></div>
                </div>
                <p class="bp-loading-text"></p>
            </div>
        </div>
        <div class="bp-reveal-content" style="display: none;">
            <?php echo $content; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

function button_plugin_block_init()
{
    register_block_type(__DIR__ . '/build', [
        'render_callback' => 'button_plugin_render_callback'
    ]);
}
add_action('init', 'button_plugin_block_init');
