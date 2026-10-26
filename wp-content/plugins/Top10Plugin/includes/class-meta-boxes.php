<?php
/**
 * Meta Boxes Class
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Top10_Meta_Boxes
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
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_product_meta'), 10, 2);
        add_action('save_post', array($this, 'save_page_meta'), 10, 2);
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook)
    {
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }

        global $post;
        if (!$post || $post->post_type !== 'top10_page') {
            return;
        }

        wp_add_inline_script('jquery', "
            jQuery(document).ready(function($) {
                // Reorder meta boxes on page load
                var metaboxOrder = [
                    'top10_page_hero',
                    'top10_page_products',
                    'top10_top5_choices',
                    'top10_research_docs',
                    'top10_comparison_columns',
                    'top10_page_faqs',
                    'top10_source_docs',
                    'top10_disclaimer'
                ];
                
                var container = $('#normal-sortables');
                metaboxOrder.forEach(function(id) {
                    var box = $('#' + id);
                    if (box.length) {
                        container.append(box);
                    }
                });
            });
        ");
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes()
    {
        // Product meta boxes
        add_meta_box(
            'top10_product_details',
            __('Product Details', 'top10-plugin'),
            array($this, 'render_product_details_meta_box'),
            'top10_product',
            'normal',
            'high'
        );

        // Page meta boxes - In Sequential Order
        // Section 1
        add_meta_box(
            'top10_page_hero',
            __('Section 1: Hero/Introduction', 'top10-plugin'),
            array($this, 'render_page_hero_meta_box'),
            'top10_page',
            'normal',
            'high'
        );

        // Section 2
        add_meta_box(
            'top10_page_products',
            __('Section 2: Select Products for Top 5', 'top10-plugin'),
            array($this, 'render_page_products_meta_box'),
            'top10_page',
            'normal',
            'high'
        );

        // Section 3
        add_meta_box(
            'top10_top5_choices',
            __('Section 3: Top 5 Choices Display Settings', 'top10-plugin'),
            array($this, 'render_top5_choices_meta_box'),
            'top10_page',
            'normal',
            'high'
        );

        // Section 4
        add_meta_box(
            'top10_research_docs',
            __('Section 4: Downloadable Research Documents', 'top10-plugin'),
            array($this, 'render_research_docs_meta_box'),
            'top10_page',
            'normal',
            'high'
        );

        // Section 5
        add_meta_box(
            'top10_comparison_columns',
            __('Section 5: Comparison Table (Copy/Paste)', 'top10-plugin'),
            array($this, 'render_comparison_columns_meta_box'),
            'top10_page',
            'normal',
            'high'
        );

        // Section 6
        add_meta_box(
            'top10_page_faqs',
            __('Section 6: FAQs', 'top10-plugin'),
            array($this, 'render_page_faqs_meta_box'),
            'top10_page',
            'normal',
            'high'
        );

        // Section 7
        add_meta_box(
            'top10_source_docs',
            __('Section 7: Source Documents & Disclosure', 'top10-plugin'),
            array($this, 'render_source_docs_meta_box'),
            'top10_page',
            'normal',
            'high'
        );

        // Section 8
        add_meta_box(
            'top10_disclaimer',
            __('Section 8: Disclaimer', 'top10-plugin'),
            array($this, 'render_disclaimer_meta_box'),
            'top10_page',
            'normal',
            'high'
        );

        // Other Settings
        add_meta_box(
            'top10_page_sections',
            __('Section Settings', 'top10-plugin'),
            array($this, 'render_page_sections_meta_box'),
            'top10_page',
            'side',
            'default'
        );
        
        add_meta_box(
            'top10_page_theme',
            __('Color Theme', 'top10-plugin'),
            array($this, 'render_page_theme_meta_box'),
            'top10_page',
            'side',
            'default'
        );
        
        add_meta_box(
            'top10_page_layout',
            __('Page Layout Style', 'top10-plugin'),
            array($this, 'render_page_layout_meta_box'),
            'top10_page',
            'side',
            'high'
        );
        
        add_meta_box(
            'top10_header_bg',
            __('Header Background (Supplement Layout)', 'top10-plugin'),
            array($this, 'render_header_bg_meta_box'),
            'top10_page',
            'side',
            'default'
        );
    }

    /**
     * Render product details meta box
     */
    public function render_product_details_meta_box($post)
    {
        wp_nonce_field('top10_product_details_nonce', 'top10_product_details_nonce');

        $badge_text = get_post_meta($post->ID, '_top10_badge_text', true);
        $price = get_post_meta($post->ID, '_top10_price', true);
        $rating = get_post_meta($post->ID, '_top10_rating', true);
        $affiliate_link = get_post_meta($post->ID, '_top10_affiliate_link', true);
        $best_for = get_post_meta($post->ID, '_top10_best_for', true);
        $ingredient_quality = get_post_meta($post->ID, '_top10_ingredient_quality', true);
        $certifications = get_post_meta($post->ID, '_top10_certifications', true);
        $safety_profile = get_post_meta($post->ID, '_top10_safety_profile', true);
        $user_rating = get_post_meta($post->ID, '_top10_user_rating', true);
        $expert_rating = get_post_meta($post->ID, '_top10_expert_rating', true);
        ?>
        <table class="form-table">
            <tr>
                <th><label for="top10_badge_text">
                        <?php _e('Product Badge Text', 'top10-plugin'); ?>
                    </label></th>
                <td>
                    <input type="text" id="top10_badge_text" name="top10_badge_text" value="<?php echo esc_attr($badge_text); ?>"
                        class="regular-text" placeholder="Best Overall">
                    <p class="description">
                        <?php _e('Badge text (e.g., "Best Overall", "Editor\'s Choice")', 'top10-plugin'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th><label for="top10_product_name">
                        <?php _e('Product Name', 'top10-plugin'); ?>
                    </label></th>
                <td>
                    <input type="text" id="top10_product_name" value="<?php echo esc_attr(get_the_title($post->ID)); ?>"
                        class="regular-text" disabled>
                    <p class="description">
                        <?php _e('Edit product name in the title field above', 'top10-plugin'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th><label for="top10_price">
                        <?php _e('Price', 'top10-plugin'); ?>
                    </label></th>
                <td>
                    <input type="text" id="top10_price" name="top10_price" value="<?php echo esc_attr($price); ?>"
                        class="regular-text" placeholder="$49-$89/bottle">
                    <p class="description">
                        <?php _e('Product price or price range', 'top10-plugin'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th><label for="top10_rating">
                        <?php _e('Overall Rating /10', 'top10-plugin'); ?>
                    </label></th>
                <td>
                    <input type="number" id="top10_rating" name="top10_rating" value="<?php echo esc_attr($rating); ?>" min="0"
                        max="10" step="0.1" class="small-text">
                    <p class="description">
                        <?php _e('Overall rating from 0 to 10 (e.g., 9.4)', 'top10-plugin'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th><label for="top10_affiliate_link">
                        <?php _e('Review Link', 'top10-plugin'); ?>
                    </label></th>
                <td>
                    <input type="url" id="top10_affiliate_link" name="top10_affiliate_link"
                        value="<?php echo esc_url($affiliate_link); ?>" class="regular-text"
                        placeholder="https://example.com/product-review">
                    <p class="description">
                        <?php _e('Link to product review or purchase page', 'top10-plugin'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th><label for="top10_best_for">
                        <?php _e('Best For', 'top10-plugin'); ?>
                    </label></th>
                <td>
                    <textarea id="top10_best_for" name="top10_best_for" rows="2" class="large-text"><?php echo esc_textarea($best_for); ?></textarea>
                    <p class="description">
                        <?php _e('Who or what this product is best suited for', 'top10-plugin'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th><label for="top10_ingredient_quality">
                        <?php _e('Ingredient Quality', 'top10-plugin'); ?>
                    </label></th>
                <td>
                    <input type="text" id="top10_ingredient_quality" name="top10_ingredient_quality" value="<?php echo esc_attr($ingredient_quality); ?>"
                        class="regular-text" placeholder="Premium, organic sources">
                    <p class="description">
                        <?php _e('Quality of ingredients used', 'top10-plugin'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th><label for="top10_certifications">
                        <?php _e('Certifications', 'top10-plugin'); ?>
                    </label></th>
                <td>
                    <textarea id="top10_certifications" name="top10_certifications" rows="2" class="large-text"><?php echo esc_textarea($certifications); ?></textarea>
                    <p class="description">
                        <?php _e('Product certifications (one per line or comma-separated)', 'top10-plugin'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th><label for="top10_safety_profile">
                        <?php _e('Safety Profile', 'top10-plugin'); ?>
                    </label></th>
                <td>
                    <textarea id="top10_safety_profile" name="top10_safety_profile" rows="3" class="large-text"><?php echo esc_textarea($safety_profile); ?></textarea>
                    <p class="description">
                        <?php _e('Safety information and considerations', 'top10-plugin'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th><label for="top10_user_rating">
                        <?php _e('User Rating', 'top10-plugin'); ?>
                    </label></th>
                <td>
                    <input type="text" id="top10_user_rating" name="top10_user_rating" value="<?php echo esc_attr($user_rating); ?>"
                        class="regular-text" placeholder="4.5/5 (2,341 reviews)">
                    <p class="description">
                        <?php _e('User rating with review count', 'top10-plugin'); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th><label for="top10_expert_rating">
                        <?php _e('Expert Rating', 'top10-plugin'); ?>
                    </label></th>
                <td>
                    <input type="text" id="top10_expert_rating" name="top10_expert_rating" value="<?php echo esc_attr($expert_rating); ?>"
                        class="regular-text" placeholder="9.2/10">
                    <p class="description">
                        <?php _e('Expert or editorial rating', 'top10-plugin'); ?>
                    </p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Render product specifications meta box
     */
    public function render_product_specs_meta_box($post)
    {
        wp_nonce_field('top10_product_specs_nonce', 'top10_product_specs_nonce');

        $specs = get_post_meta($post->ID, '_top10_specs', true);
        if (!is_array($specs)) {
            $specs = array();
        }
        ?>
        <style>
            .top10-spec-field { margin-bottom: 15px; }
            .top10-spec-field label { display: block; margin-bottom: 5px; font-weight: 600; }
        </style>
        
        <div class="top10-spec-field">
            <label><?php _e('Best For:', 'top10-plugin'); ?></label>
            <input type="text" name="top10_specs[best_for]" value="<?php echo esc_attr($specs['best_for'] ?? ''); ?>" class="widefat" placeholder="e.g., daily commuting, resort skiing, casual winter tasks">
        </div>
        
        <div class="top10-spec-field">
            <label><?php _e('Heating Levels:', 'top10-plugin'); ?></label>
            <input type="text" name="top10_specs[heating_levels]" value="<?php echo esc_attr($specs['heating_levels'] ?? ''); ?>" class="widefat" placeholder="e.g., 3 (Low/Mid/High)">
        </div>
        
        <div class="top10-spec-field">
            <label><?php _e('Battery:', 'top10-plugin'); ?></label>
            <input type="text" name="top10_specs[battery]" value="<?php echo esc_attr($specs['battery'] ?? ''); ?>" class="widefat" placeholder="e.g., 7.4V 3000mAh lithium-ion">
        </div>
        
        <div class="top10-spec-field">
            <label><?php _e('Battery Life:', 'top10-plugin'); ?></label>
            <input type="text" name="top10_specs[battery_life]" value="<?php echo esc_attr($specs['battery_life'] ?? ''); ?>" class="widefat" placeholder="e.g., 3-8 hours">
        </div>
        
        <div class="top10-spec-field">
            <label><?php _e('Heating Zones:', 'top10-plugin'); ?></label>
            <input type="text" name="top10_specs[heating_zones]" value="<?php echo esc_attr($specs['heating_zones'] ?? ''); ?>" class="widefat" placeholder="e.g., Fingers, Back of hand">
        </div>
        
        <div class="top10-spec-field">
            <label><?php _e('Materials:', 'top10-plugin'); ?></label>
            <input type="text" name="top10_specs[materials]" value="<?php echo esc_attr($specs['materials'] ?? ''); ?>" class="widefat" placeholder="e.g., Softshell, synthetic leather palm">
        </div>
        
        <div class="top10-spec-field">
            <label><?php _e('Waterproofing:', 'top10-plugin'); ?></label>
            <input type="text" name="top10_specs[waterproofing]" value="<?php echo esc_attr($specs['waterproofing'] ?? ''); ?>" class="widefat" placeholder="e.g., DWR membrane">
        </div>
        
        <div class="top10-spec-field">
            <label><?php _e('Sizes:', 'top10-plugin'); ?></label>
            <input type="text" name="top10_specs[sizes]" value="<?php echo esc_attr($specs['sizes'] ?? ''); ?>" class="widefat" placeholder="e.g., S-XXL">
        </div>
        
        <hr style="margin: 20px 0;">
        
        <h3><?php _e('Platform Ratings', 'top10-plugin'); ?></h3>
        
        <div class="top10-spec-field">
            <label><?php _e('Amazon Rating:', 'top10-plugin'); ?></label>
            <input type="text" name="top10_specs[amazon_rating]" value="<?php echo esc_attr($specs['amazon_rating'] ?? ''); ?>" class="regular-text" placeholder="e.g., 4.5">
        </div>
        
        <div class="top10-spec-field">
            <label><?php _e('Trustpilot Rating:', 'top10-plugin'); ?></label>
            <input type="text" name="top10_specs[trustpilot_rating]" value="<?php echo esc_attr($specs['trustpilot_rating'] ?? ''); ?>" class="regular-text" placeholder="e.g., 4.7">
        </div>
        
        <div class="top10-spec-field">
            <label><?php _e('BBB/Google Rating:', 'top10-plugin'); ?></label>
            <input type="text" name="top10_specs[google_rating]" value="<?php echo esc_attr($specs['google_rating'] ?? ''); ?>" class="regular-text" placeholder="e.g., 4.3">
        </div>
        
        <hr style="margin: 20px 0;">
        
        <div class="top10-spec-field">
            <label><?php _e('Research Observation:', 'top10-plugin'); ?></label>
            <textarea name="top10_specs[research_observation]" class="widefat" rows="4" placeholder="Your research findings about this product"><?php echo esc_textarea($specs['research_observation'] ?? ''); ?></textarea>
        </div>
        
        <div class="top10-spec-field">
            <label><?php _e('Customer Review Summary:', 'top10-plugin'); ?></label>
            <textarea name="top10_specs[customer_review_summary]" class="widefat" rows="3" placeholder="Summary of customer feedback"><?php echo esc_textarea($specs['customer_review_summary'] ?? ''); ?></textarea>
        </div>
        
        <div class="top10-spec-field">
            <label><?php _e('Featured Customer Review:', 'top10-plugin'); ?></label>
            <textarea name="top10_specs[featured_review]" class="widefat" rows="2" placeholder="A featured customer quote (without quotes)"><?php echo esc_textarea($specs['featured_review'] ?? ''); ?></textarea>
        </div>
        <?php
    }

    /**
     * Render product features meta box
     */
    public function render_product_features_meta_box($post)
    {
        wp_nonce_field('top10_product_features_nonce', 'top10_product_features_nonce');

        $features = get_post_meta($post->ID, '_top10_features', true);
        $pros = get_post_meta($post->ID, '_top10_pros', true);
        $cons = get_post_meta($post->ID, '_top10_cons', true);

        if (!is_array($features))
            $features = array('');
        if (!is_array($pros))
            $pros = array('');
        if (!is_array($cons))
            $cons = array('');
        ?>
        <div class="top10-features-section">
            <h4>
                <?php _e('Features', 'top10-plugin'); ?>
            </h4>
            <div id="top10-features-container">
                <?php foreach ($features as $index => $feature): ?>
                    <div class="top10-feature-row">
                        <input type="text" name="top10_features[]" value="<?php echo esc_attr($feature); ?>" class="widefat"
                            placeholder="<?php _e('Feature description', 'top10-plugin'); ?>">
                        <button type="button" class="button top10-remove-feature">
                            <?php _e('Remove', 'top10-plugin'); ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button top10-add-feature">
                <?php _e('Add Feature', 'top10-plugin'); ?>
            </button>
        </div>

        <div class="top10-pros-section" style="margin-top: 20px;">
            <h4>
                <?php _e('Pros', 'top10-plugin'); ?>
            </h4>
            <div id="top10-pros-container">
                <?php foreach ($pros as $index => $pro): ?>
                    <div class="top10-pro-row">
                        <input type="text" name="top10_pros[]" value="<?php echo esc_attr($pro); ?>" class="widefat"
                            placeholder="<?php _e('Pro', 'top10-plugin'); ?>">
                        <button type="button" class="button top10-remove-pro">
                            <?php _e('Remove', 'top10-plugin'); ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button top10-add-pro">
                <?php _e('Add Pro', 'top10-plugin'); ?>
            </button>
        </div>

        <div class="top10-cons-section" style="margin-top: 20px;">
            <h4>
                <?php _e('Cons', 'top10-plugin'); ?>
            </h4>
            <div id="top10-cons-container">
                <?php foreach ($cons as $index => $con): ?>
                    <div class="top10-con-row">
                        <input type="text" name="top10_cons[]" value="<?php echo esc_attr($con); ?>" class="widefat"
                            placeholder="<?php _e('Con', 'top10-plugin'); ?>">
                        <button type="button" class="button top10-remove-con">
                            <?php _e('Remove', 'top10-plugin'); ?>
                        </button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="button top10-add-con">
                <?php _e('Add Con', 'top10-plugin'); ?>
            </button>
        </div>
        <?php
    }

    /**
     * Render page hero meta box
     */
    public function render_page_hero_meta_box($post)
    {
        wp_nonce_field('top10_page_hero_nonce', 'top10_page_hero_nonce');

        $hero_title = get_post_meta($post->ID, '_top10_hero_title', true);
        $hero_subtitle = get_post_meta($post->ID, '_top10_hero_subtitle', true);
        ?>
        <p class="description"><?php _e('Leave Hero Title empty to use the page title automatically.', 'top10-plugin'); ?></p>
        <table class="form-table">
            <tr>
                <th><label for="top10_hero_title">
                        <?php _e('Hero Title (Main Heading)', 'top10-plugin'); ?>
                    </label></th>
                <td>
                    <input type="text" id="top10_hero_title" name="top10_hero_title"
                        value="<?php echo esc_attr($hero_title); ?>" class="widefat" placeholder="<?php echo esc_attr(get_the_title($post->ID)); ?>">
                    <p class="description"><?php _e('Leave empty to use page title', 'top10-plugin'); ?></p>
                </td>
            </tr>
            <tr>
                <th><label for="top10_hero_subtitle">
                        <?php _e('Hero Subtitle (Description)', 'top10-plugin'); ?>
                    </label></th>
                <td>
                    <textarea id="top10_hero_subtitle" name="top10_hero_subtitle" rows="3"
                        class="widefat" placeholder="Optional subtitle text"><?php echo esc_textarea($hero_subtitle); ?></textarea>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Render page products meta box
     */
    public function render_page_products_meta_box($post)
    {
        wp_nonce_field('top10_page_products_nonce', 'top10_page_products_nonce');

        $selected_products = get_post_meta($post->ID, '_top10_selected_products', true);
        if (!is_array($selected_products)) {
            $selected_products = array();
        }

        // Get all products
        $products = get_posts(array(
            'post_type' => 'top10_product',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ));
        ?>
        <div class="top10-product-selector">
            <p>
                <?php _e('Select up to 10 products and drag to reorder:', 'top10-plugin'); ?>
            </p>
            <div id="top10-selected-products" class="top10-sortable">
                <?php foreach ($selected_products as $product_id):
                    $product = get_post($product_id);
                    if ($product):
                        ?>
                        <div class="top10-selected-product" data-product-id="<?php echo $product_id; ?>">
                            <span class="dashicons dashicons-menu"></span>
                            <span class="product-title">
                                <?php echo esc_html($product->post_title); ?>
                            </span>
                            <button type="button" class="button-link top10-remove-product">
                                <span class="dashicons dashicons-no"></span>
                            </button>
                            <input type="hidden" name="top10_selected_products[]" value="<?php echo $product_id; ?>">
                        </div>
                    <?php
                    endif;
                endforeach; ?>
            </div>

            <div class="top10-product-dropdown" style="margin-top: 15px;">
                <select id="top10-product-select" class="widefat">
                    <option value="">
                        <?php _e('-- Select a product to add --', 'top10-plugin'); ?>
                    </option>
                    <?php foreach ($products as $product): ?>
                        <option value="<?php echo $product->ID; ?>">
                            <?php echo esc_html($product->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="button" class="button top10-add-product" style="margin-top: 10px;">
                    <?php _e('Add Product', 'top10-plugin'); ?>
                </button>
            </div>
        </div>
        <?php
    }

    /**
     * Render page FAQs meta box
     */
    public function render_page_faqs_meta_box($post)
    {
        wp_nonce_field('top10_page_faqs_nonce', 'top10_page_faqs_nonce');

        $faqs = get_post_meta($post->ID, '_top10_faqs', true);
        if (!is_array($faqs)) {
            $faqs = array(array('question' => '', 'answer' => ''));
        }
        ?>
        <div id="top10-faqs-container">
            <?php foreach ($faqs as $index => $faq): ?>
                <div class="top10-faq-row" data-index="<?php echo $index; ?>"
                    style="margin-bottom: 15px; padding: 15px; border: 1px solid #ddd;">
                    <p>
                        <label><strong>
                                <?php _e('Question', 'top10-plugin'); ?>
                            </strong></label>
                        <input type="text" name="top10_faqs[<?php echo $index; ?>][question]"
                            value="<?php echo esc_attr($faq['question']); ?>" class="widefat">
                    </p>
                    <p>
                        <label><strong>
                                <?php _e('Answer', 'top10-plugin'); ?>
                            </strong></label>
                        <textarea name="top10_faqs[<?php echo $index; ?>][answer]" rows="3"
                            class="widefat"><?php echo esc_textarea($faq['answer']); ?></textarea>
                    </p>
                    <button type="button" class="button top10-remove-faq">
                        <?php _e('Remove FAQ', 'top10-plugin'); ?>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button top10-add-faq">
            <?php _e('Add FAQ', 'top10-plugin'); ?>
        </button>
        <?php
    }

    /**
     * Render buying guide meta box
     */
    public function render_page_buying_guide_meta_box($post)
    {
        wp_nonce_field('top10_page_buying_guide_nonce', 'top10_page_buying_guide_nonce');

        $buying_guide = get_post_meta($post->ID, '_top10_buying_guide', true);
        if (!is_array($buying_guide)) {
            $buying_guide = array(array('title' => '', 'description' => ''));
        }
        ?>
        <div id="top10-buying-guide-container">
            <?php foreach ($buying_guide as $index => $item): ?>
                <div class="top10-guide-row" data-index="<?php echo $index; ?>"
                    style="margin-bottom: 15px; padding: 15px; border: 1px solid #ddd;">
                    <p>
                        <label><strong><?php _e('Title', 'top10-plugin'); ?></strong></label>
                        <input type="text" name="top10_buying_guide[<?php echo $index; ?>][title]"
                            value="<?php echo esc_attr($item['title']); ?>" class="widefat"
                            placeholder="e.g., Battery Capacity vs Weight">
                    </p>
                    <p>
                        <label><strong><?php _e('Description', 'top10-plugin'); ?></strong></label>
                        <textarea name="top10_buying_guide[<?php echo $index; ?>][description]" rows="3"
                            class="widefat"><?php echo esc_textarea($item['description']); ?></textarea>
                    </p>
                    <button type="button" class="button top10-remove-guide-item">
                        <?php _e('Remove Item', 'top10-plugin'); ?>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button top10-add-guide-item">
            <?php _e('Add Item', 'top10-plugin'); ?>
        </button>
        <?php
    }

    /**
     * Render research methodology meta box
     */
    public function render_page_research_meta_box($post)
    {
        wp_nonce_field('top10_page_research_nonce', 'top10_page_research_nonce');

        $research_intro = get_post_meta($post->ID, '_top10_research_intro', true);
        $research_criteria = get_post_meta($post->ID, '_top10_research_criteria', true);
        if (!is_array($research_criteria)) {
            $research_criteria = array(array('criteria' => '', 'weight' => '', 'method' => ''));
        }
        ?>
        <p>
            <label><strong><?php _e('Introduction Text:', 'top10-plugin'); ?></strong></label>
            <textarea name="top10_research_intro" rows="3" class="widefat"><?php echo esc_textarea($research_intro); ?></textarea>
        </p>
        
        <h4><?php _e('Expert Rating Criteria', 'top10-plugin'); ?></h4>
        <div id="top10-research-criteria-container">
            <?php foreach ($research_criteria as $index => $item): ?>
                <div class="top10-criteria-row" data-index="<?php echo $index; ?>"
                    style="margin-bottom: 15px; padding: 15px; border: 1px solid #ddd;">
                    <p>
                        <label><strong><?php _e('Criteria', 'top10-plugin'); ?></strong></label>
                        <input type="text" name="top10_research_criteria[<?php echo $index; ?>][criteria]"
                            value="<?php echo esc_attr($item['criteria']); ?>" class="widefat"
                            placeholder="e.g., Warmth Output">
                    </p>
                    <p>
                        <label><strong><?php _e('Weight (%)', 'top10-plugin'); ?></strong></label>
                        <input type="text" name="top10_research_criteria[<?php echo $index; ?>][weight]"
                            value="<?php echo esc_attr($item['weight']); ?>" class="regular-text"
                            placeholder="e.g., 30%">
                    </p>
                    <p>
                        <label><strong><?php _e('Test Method', 'top10-plugin'); ?></strong></label>
                        <input type="text" name="top10_research_criteria[<?php echo $index; ?>][method]"
                            value="<?php echo esc_attr($item['method']); ?>" class="widefat"
                            placeholder="e.g., Thermal sensor mapping">
                    </p>
                    <button type="button" class="button top10-remove-criteria-item">
                        <?php _e('Remove Criteria', 'top10-plugin'); ?>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
        <button type="button" class="button top10-add-criteria-item">
            <?php _e('Add Criteria', 'top10-plugin'); ?>
        </button>
        
        <hr style="margin: 20px 0;">
        
        <p>
            <label><strong><?php _e('Data Sources Text:', 'top10-plugin'); ?></strong></label>
            <textarea name="top10_research_sources" rows="2" class="widefat" placeholder="Data sources included manufacturer specs, independent lab tests..."><?php echo esc_textarea(get_post_meta($post->ID, '_top10_research_sources', true)); ?></textarea>
            <span class="description"><?php _e('Text describing your data sources and validation process.', 'top10-plugin'); ?></span>
        </p>
        <?php
    }

    /**
     * Render conclusion meta box
     */
    public function render_page_conclusion_meta_box($post)
    {
        wp_nonce_field('top10_page_conclusion_nonce', 'top10_page_conclusion_nonce');

        $conclusion = get_post_meta($post->ID, '_top10_conclusion', true);
        ?>
        <p>
            <label><strong><?php _e('Conclusion Text:', 'top10-plugin'); ?></strong></label>
            <textarea name="top10_conclusion" rows="6" class="widefat"><?php echo esc_textarea($conclusion); ?></textarea>
            <span class="description"><?php _e('Write your final thoughts and recommendations here.', 'top10-plugin'); ?></span>
        </p>
        <?php
    }

    /**
     * Render page sections settings meta box
     */
    public function render_page_sections_meta_box($post)
    {
        wp_nonce_field('top10_page_sections_nonce', 'top10_page_sections_nonce');

        $show_comparison = get_post_meta($post->ID, '_top10_show_comparison', true) !== '0';
        $show_faqs = get_post_meta($post->ID, '_top10_show_faqs', true) !== '0';
        $show_buying_guide = get_post_meta($post->ID, '_top10_show_buying_guide', true) !== '0';
        ?>
        <p>
            <label>
                <input type="checkbox" name="top10_show_comparison" value="1" <?php checked($show_comparison); ?>>
                <?php _e('Show Comparison Table', 'top10-plugin'); ?>
            </label>
        </p>
        <p>
            <label>
                <input type="checkbox" name="top10_show_faqs" value="1" <?php checked($show_faqs); ?>>
                <?php _e('Show FAQs Section', 'top10-plugin'); ?>
            </label>
        </p>
        <p>
            <label>
                <input type="checkbox" name="top10_show_buying_guide" value="1" <?php checked($show_buying_guide); ?>>
                <?php _e('Show Buying Guide', 'top10-plugin'); ?>
            </label>
        </p>
        <?php
    }
    
    /**
     * Render page theme meta box
     */
    public function render_page_theme_meta_box($post)
    {
        wp_nonce_field('top10_page_theme_nonce', 'top10_page_theme_nonce');

        $theme_color = get_post_meta($post->ID, '_top10_theme_color', true) ?: 'blue';
        
        $themes = array(
            'blue' => array('label' => 'Blue (Default)', 'color' => '#6BA3F8'),
            'green' => array('label' => 'Green', 'color' => '#10B981'),
            'red' => array('label' => 'Red', 'color' => '#EF4444'),
            'black' => array('label' => 'Black', 'color' => '#1F2937'),
            'white' => array('label' => 'White', 'color' => '#FFFFFF')
        );
        ?>
        <p><strong><?php _e('Select Color Theme:', 'top10-plugin'); ?></strong></p>
        <?php foreach ($themes as $key => $theme): ?>
            <p>
                <label style="display: flex; align-items: center; gap: 10px;">
                    <input type="radio" name="top10_theme_color" value="<?php echo esc_attr($key); ?>" <?php checked($theme_color, $key); ?>>
                    <span style="display: inline-block; width: 24px; height: 24px; border-radius: 4px; background: <?php echo esc_attr($theme['color']); ?>; border: 1px solid #ddd;"></span>
                    <?php echo esc_html($theme['label']); ?>
                </label>
            </p>
        <?php endforeach; ?>
        <p class="description"><?php _e('Choose the color scheme for hero section, sidebar, and buttons.', 'top10-plugin'); ?></p>
        <?php
    }
    
    /**
     * Render page layout meta box
     */
    public function render_page_layout_meta_box($post)
    {
        wp_nonce_field('top10_page_layout_nonce', 'top10_page_layout_nonce');

        $page_layout = get_post_meta($post->ID, '_top10_page_layout', true) ?: 'product';
        ?>
        <p><strong><?php _e('Select Layout Style:', 'top10-plugin'); ?></strong></p>
        <p>
            <label>
                <input type="radio" name="top10_page_layout" value="product" <?php checked($page_layout, 'product'); ?>>
                <?php _e('Product Style (with images, detailed cards)', 'top10-plugin'); ?>
            </label>
        </p>
        <p>
            <label>
                <input type="radio" name="top10_page_layout" value="supplement" <?php checked($page_layout, 'supplement'); ?>>
                <?php _e('Supplement/Table Style (top 5 table, comparison, research docs)', 'top10-plugin'); ?>
            </label>
        </p>
        <p class="description"><?php _e('Choose the layout format for this page.', 'top10-plugin'); ?></p>
        <?php
    }

    /**
     * Render research documents meta box
     */
    public function render_research_docs_meta_box($post)
    {
        wp_nonce_field('top10_research_docs_nonce', 'top10_research_docs_nonce');

        $excel_url = get_post_meta($post->ID, '_research_doc_excel_url', true);
        $pdf_url = get_post_meta($post->ID, '_research_doc_pdf_url', true);
        $research_intro_text = get_post_meta($post->ID, '_research_intro_text', true);
        
        // Default text
        if (empty($research_intro_text)) {
            $research_intro_text = "To maintain transparency, we've made our full research workbook and supporting analysis publicly available. These documents detail the scoring methodology, evidence standards, ingredient evaluation, and ranking logic used in this article.";
        }
        ?>
        <p class="description"><?php _e('Customize the research transparency section (shown only in Supplement layout)', 'top10-plugin'); ?></p>
        
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="research_intro_text"><?php _e('Introduction Text', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <textarea id="research_intro_text" name="research_intro_text" rows="4" class="large-text"><?php echo esc_textarea($research_intro_text); ?></textarea>
                    <p class="description"><?php _e('This text appears above the download buttons', 'top10-plugin'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="research_doc_excel_url"><?php _e('Excel Document URL', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <input type="url" id="research_doc_excel_url" name="research_doc_excel_url" value="<?php echo esc_attr($excel_url); ?>" class="regular-text" placeholder="https://example.com/research-data.xlsx">
                    <p class="description"><?php _e('URL to Excel spreadsheet with research data', 'top10-plugin'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="research_doc_pdf_url"><?php _e('PDF Document URL', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <input type="url" id="research_doc_pdf_url" name="research_doc_pdf_url" value="<?php echo esc_attr($pdf_url); ?>" class="regular-text" placeholder="https://example.com/research-report.pdf">
                    <p class="description"><?php _e('URL to PDF report with detailed research findings', 'top10-plugin'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="research_doc_excel_link_text"><?php _e('Excel Button Text', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <input type="text" id="research_doc_excel_link_text" name="research_doc_excel_link_text" value="<?php echo esc_attr(get_post_meta($post->ID, '_research_doc_excel_link_text', true) ?: 'Download the Full Research Workbook (Excel)'); ?>" class="regular-text">
                    <p class="description"><?php _e('Text for the Excel download button', 'top10-plugin'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="research_doc_pdf_link_text"><?php _e('PDF Button Text', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <input type="text" id="research_doc_pdf_link_text" name="research_doc_pdf_link_text" value="<?php echo esc_attr(get_post_meta($post->ID, '_research_doc_pdf_link_text', true) ?: 'Download the Detailed Research Document (PDF)'); ?>" class="regular-text">
                    <p class="description"><?php _e('Text for the PDF download button', 'top10-plugin'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Render source documents meta box
     */
    public function render_source_docs_meta_box($post)
    {
        wp_nonce_field('top10_source_docs_nonce', 'top10_source_docs_nonce');

        $source_intro = get_post_meta($post->ID, '_source_docs_intro', true);
        if (empty($source_intro)) {
            $source_intro = 'As part of our commitment to evidence-based publishing, SupplementMag provides full access to the internal research materials used to create this ranking.';
        }

        $source_bullets = get_post_meta($post->ID, '_source_docs_bullets', true);
        if (empty($source_bullets)) {
            $source_bullets = "A consolidated comparison sheet ranking all reviewed products\nA weighted scoring model across safety, effectiveness, ingredient quality, certifications, value, evidence strength, user experience, and versatility\nProduct-specific research sheets detailing: Ingredient-level analysis, Scientific rationale behind each score, Use-case suitability and limitations, Pros, cons, and safety considerations\nTransparent formulas and charts supporting all final ratings";
        }
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="source_docs_intro"><?php _e('Introduction Text', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <textarea 
                        name="source_docs_intro" 
                        id="source_docs_intro" 
                        class="large-text" 
                        rows="3"
                        style="width: 100%;"><?php echo esc_textarea($source_intro); ?></textarea>
                    <p class="description"><?php _e('Intro paragraph for Source Documents & Disclosure section', 'top10-plugin'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="source_docs_bullets"><?php _e('Bullet Points', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <textarea 
                        name="source_docs_bullets" 
                        id="source_docs_bullets" 
                        class="large-text" 
                        rows="6"
                        style="width: 100%;"><?php echo esc_textarea($source_bullets); ?></textarea>
                    <p class="description"><?php _e('One bullet point per line (items under "The research document includes:")', 'top10-plugin'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="source_docs_excel_url"><?php _e('Excel Document URL', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <input type="url" id="source_docs_excel_url" name="source_docs_excel_url" value="<?php echo esc_attr(get_post_meta($post->ID, '_source_docs_excel_url', true)); ?>" class="regular-text" placeholder="https://example.com/research-workbook.xlsx">
                    <p class="description"><?php _e('URL to Excel file for this section', 'top10-plugin'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="source_docs_pdf_url"><?php _e('PDF Document URL', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <input type="url" id="source_docs_pdf_url" name="source_docs_pdf_url" value="<?php echo esc_attr(get_post_meta($post->ID, '_source_docs_pdf_url', true)); ?>" class="regular-text" placeholder="https://example.com/research-document.pdf">
                    <p class="description"><?php _e('URL to PDF file for this section', 'top10-plugin'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="source_docs_excel_link_text"><?php _e('Excel Button Text', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <input type="text" id="source_docs_excel_link_text" name="source_docs_excel_link_text" value="<?php echo esc_attr(get_post_meta($post->ID, '_source_docs_excel_link_text', true) ?: 'Download the Full Research Workbook (Excel)'); ?>" class="regular-text">
                    <p class="description"><?php _e('Text for the Excel download button', 'top10-plugin'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="source_docs_pdf_link_text"><?php _e('PDF Button Text', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <input type="text" id="source_docs_pdf_link_text" name="source_docs_pdf_link_text" value="<?php echo esc_attr(get_post_meta($post->ID, '_source_docs_pdf_link_text', true) ?: 'Download the Detailed Research Document (PDF)'); ?>" class="regular-text">
                    <p class="description"><?php _e('Text for the PDF download button', 'top10-plugin'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Render top 5 choices meta box
     */
    public function render_top5_choices_meta_box($post)
    {
        wp_nonce_field('top10_top5_choices_nonce', 'top10_top5_choices_nonce');

        $top5_title_prefix = get_post_meta($post->ID, '_top5_title_prefix', true);
        if (empty($top5_title_prefix)) {
            $top5_title_prefix = 'Top Choices:';
        }

        $top5_step1_label = get_post_meta($post->ID, '_top5_step1_label', true);
        if (empty($top5_step1_label)) {
            $top5_step1_label = 'Product Category/Badge';
        }

        $top5_step2_label = get_post_meta($post->ID, '_top5_step2_label', true);
        if (empty($top5_step2_label)) {
            $top5_step2_label = 'Product Name';
        }

        $top5_step3_label = get_post_meta($post->ID, '_top5_step3_label', true);
        if (empty($top5_step3_label)) {
            $top5_step3_label = 'Price Range';
        }

        $top5_step4_label = get_post_meta($post->ID, '_top5_step4_label', true);
        if (empty($top5_step4_label)) {
            $top5_step4_label = 'Rating Score';
        }

        $top5_step5_label = get_post_meta($post->ID, '_top5_step5_label', true);
        if (empty($top5_step5_label)) {
            $top5_step5_label = 'Review Link Text';
        }
        ?>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="top5_title_prefix"><?php _e('Section Title Prefix', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <input 
                        type="text" 
                        name="top5_title_prefix" 
                        id="top5_title_prefix" 
                        class="regular-text" 
                        value="<?php echo esc_attr($top5_title_prefix); ?>" />
                    <p class="description"><?php _e('Text before page title (e.g., "Top Choices:", "Best Picks:", "Our Top 5:")', 'top10-plugin'); ?></p>
                </td>
            </tr>
            <tr>
                <th colspan="2" style="background: #f0f0f1; padding: 12px;"><strong><?php _e('Step-by-Step Display Labels', 'top10-plugin'); ?></strong></th>
            </tr>
            <tr>
                <th scope="row">
                    <label for="top5_step1_label"><?php _e('Step 1 Label', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <input 
                        type="text" 
                        name="top5_step1_label" 
                        id="top5_step1_label" 
                        class="regular-text" 
                        value="<?php echo esc_attr($top5_step1_label); ?>" />
                    <p class="description"><?php _e('Label for category/badge (e.g., "Best Overall", "Editor\'s Choice")', 'top10-plugin'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="top5_step2_label"><?php _e('Step 2 Label', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <input 
                        type="text" 
                        name="top5_step2_label" 
                        id="top5_step2_label" 
                        class="regular-text" 
                        value="<?php echo esc_attr($top5_step2_label); ?>" />
                    <p class="description"><?php _e('Label for product name display', 'top10-plugin'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="top5_step3_label"><?php _e('Step 3 Label', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <input 
                        type="text" 
                        name="top5_step3_label" 
                        id="top5_step3_label" 
                        class="regular-text" 
                        value="<?php echo esc_attr($top5_step3_label); ?>" />
                    <p class="description"><?php _e('Label for price information', 'top10-plugin'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="top5_step4_label"><?php _e('Step 4 Label', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <input 
                        type="text" 
                        name="top5_step4_label" 
                        id="top5_step4_label" 
                        class="regular-text" 
                        value="<?php echo esc_attr($top5_step4_label); ?>" />
                    <p class="description"><?php _e('Label for rating/score display', 'top10-plugin'); ?></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="top5_step5_label"><?php _e('Step 5 Label', 'top10-plugin'); ?></label>
                </th>
                <td>
                    <input 
                        type="text" 
                        name="top5_step5_label" 
                        id="top5_step5_label" 
                        class="regular-text" 
                        value="<?php echo esc_attr($top5_step5_label); ?>" />
                    <p class="description"><?php _e('Label/text for review link (e.g., "Read Review", "Full Details", "Learn More")', 'top10-plugin'); ?></p>
                </td>
            </tr>
        </table>
        <?php
    }

    /**
     * Render disclaimer meta box
     */
    public function render_disclaimer_meta_box($post)
    {
        wp_nonce_field('top10_disclaimer_nonce', 'top10_disclaimer_nonce');

        $disclaimer_text = get_post_meta($post->ID, '_disclaimer_text', true);
        ?>
        <p class="description"><?php _e('Disclaimer text to appear at the bottom of supplement pages', 'top10-plugin'); ?></p>
        <?php
        wp_editor($disclaimer_text, 'disclaimer_text', array(
            'textarea_name' => 'disclaimer_text',
            'textarea_rows' => 6,
            'media_buttons' => false,
            'teeny' => true,
            'quicktags' => true
        ));
    }

    /**
     * Render header background meta box
     */
    public function render_header_bg_meta_box($post)
    {
        wp_nonce_field('top10_header_bg_nonce', 'top10_header_bg_nonce');

        $header_bg_url = get_post_meta($post->ID, '_header_bg_url', true);
        ?>
        <div class="header-bg-upload">
            <input type="hidden" id="header_bg_url" name="header_bg_url" value="<?php echo esc_attr($header_bg_url); ?>">
            <div class="header-bg-preview" style="margin-bottom: 10px;">
                <?php if (!empty($header_bg_url)): ?>
                    <img src="<?php echo esc_url($header_bg_url); ?>" style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 4px;">
                <?php else: ?>
                    <p style="color: #666; font-style: italic;">No image selected</p>
                <?php endif; ?>
            </div>
            <button type="button" class="button button-primary" id="upload_header_bg_button">
                <?php _e('Upload/Select Image', 'top10-plugin'); ?>
            </button>
            <?php if (!empty($header_bg_url)): ?>
                <button type="button" class="button" id="remove_header_bg_button" style="margin-left: 5px;">
                    <?php _e('Remove Image', 'top10-plugin'); ?>
                </button>
            <?php endif; ?>
            <p class="description" style="margin-top: 10px;">
                <?php _e('Header background image for supplement pages (recommended: 1920x400px)', 'top10-plugin'); ?>
            </p>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            var mediaUploader;
            
            $('#upload_header_bg_button').on('click', function(e) {
                e.preventDefault();
                
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }
                
                mediaUploader = wp.media({
                    title: 'Select Header Background Image',
                    button: {
                        text: 'Use this image'
                    },
                    multiple: false
                });
                
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#header_bg_url').val(attachment.url);
                    $('.header-bg-preview').html('<img src="' + attachment.url + '" style="max-width: 100%; height: auto; border: 1px solid #ddd; border-radius: 4px;">');
                    if ($('#remove_header_bg_button').length === 0) {
                        $('#upload_header_bg_button').after('<button type="button" class="button" id="remove_header_bg_button" style="margin-left: 5px;">Remove Image</button>');
                    }
                });
                
                mediaUploader.open();
            });
            
            $(document).on('click', '#remove_header_bg_button', function(e) {
                e.preventDefault();
                $('#header_bg_url').val('');
                $('.header-bg-preview').html('<p style="color: #666; font-style: italic;">No image selected</p>');
                $(this).remove();
            });
        });
        </script>
        <?php
    }

    /**
     * Render comparison columns meta box
     */
    public function render_comparison_columns_meta_box($post)
    {
        wp_nonce_field('top10_comparison_columns_nonce', 'top10_comparison_columns_nonce');

        $comparison_table_html = get_post_meta($post->ID, '_comparison_table_html', true);
        ?>
        <div style="margin-bottom: 20px;">
            <p class="description"><strong>Instructions:</strong></p>
            <ol style="margin-left: 20px; line-height: 1.8;">
                <li>Copy your table from Word, Excel, or Google Docs</li>
                <li>Paste it in the box below (press Ctrl+V or Cmd+V)</li>
                <li>The table will be automatically converted to HTML</li>
                <li>Click "Update" to save</li>
            </ol>
            <p class="description" style="margin-top: 10px;"><strong>Note:</strong> The Top 5 Choices table and this Comparison Table are separate sections.</p>
        </div>
        
        <div id="table-paste-area" contenteditable="true" style="min-height: 200px; padding: 15px; border: 2px dashed #ccc; background: #f9f9f9; margin-bottom: 15px; overflow-x: auto;">
            <p style="color: #999; font-style: italic;">Paste your table here from Word, Excel, or Google Docs...</p>
        </div>
        
        <div style="margin: 15px 0;">
            <button type="button" id="clear-table-btn" class="button">Clear Table</button>
            <button type="button" id="preview-table-btn" class="button">Preview</button>
        </div>
        
        <p><strong>Generated HTML:</strong> <span style="color: #999; font-size: 12px;">(This will be saved - do not edit unless you know HTML)</span></p>
        <textarea id="comparison_table_html" name="comparison_table_html" rows="10" class="large-text code" style="font-family: monospace; width: 100%; display: none;"><?php echo esc_textarea($comparison_table_html); ?></textarea>
        <div id="html-preview" style="padding: 10px; background: #f5f5f5; border: 1px solid #ddd; font-family: monospace; font-size: 12px; max-height: 200px; overflow-y: auto; white-space: pre-wrap; word-wrap: break-word;"><?php echo esc_html($comparison_table_html); ?></div>
        
        <script>
        jQuery(document).ready(function($) {
            const pasteArea = document.getElementById('table-paste-area');
            const htmlTextarea = document.getElementById('comparison_table_html');
            const htmlPreview = document.getElementById('html-preview');
            
            // Load existing table if any
            if (htmlTextarea.value.trim()) {
                pasteArea.innerHTML = htmlTextarea.value;
            }
            
            // Handle paste event
            pasteArea.addEventListener('paste', function(e) {
                e.preventDefault();
                
                // Get pasted data
                const clipboardData = e.clipboardData || window.clipboardData;
                const pastedHTML = clipboardData.getData('text/html');
                const pastedText = clipboardData.getData('text/plain');
                
                let tableHTML = '';
                
                if (pastedHTML && pastedHTML.includes('<table')) {
                    // Already has table HTML (from Excel/Word)
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = pastedHTML;
                    const table = tempDiv.querySelector('table');
                    
                    if (table) {
                        tableHTML = convertToCleanTable(table);
                    }
                } else if (pastedText) {
                    // Plain text with tabs/newlines (from copying cells)
                    tableHTML = convertTextToTable(pastedText);
                }
                
                if (tableHTML) {
                    pasteArea.innerHTML = tableHTML;
                    htmlTextarea.value = tableHTML;
                    htmlPreview.textContent = tableHTML;
                }
            });
            
            // Monitor changes
            pasteArea.addEventListener('input', function() {
                const html = pasteArea.innerHTML;
                htmlTextarea.value = html;
                htmlPreview.textContent = html;
            });
            
            // Clear button
            $('#clear-table-btn').on('click', function() {
                pasteArea.innerHTML = '<p style="color: #999; font-style: italic;">Paste your table here from Word, Excel, or Google Docs...</p>';
                htmlTextarea.value = '';
                htmlPreview.textContent = '';
            });
            
            // Preview button
            $('#preview-table-btn').on('click', function() {
                const preview = window.open('', 'Table Preview', 'width=800,height=600');
                preview.document.write('<html><head><title>Table Preview</title></head><body>');
                preview.document.write(pasteArea.innerHTML);
                preview.document.write('</body></html>');
                preview.document.close();
            });
            
            function convertToCleanTable(table) {
                let html = '<table>';
                const rows = table.querySelectorAll('tr');
                
                rows.forEach((row, index) => {
                    html += '<tr>';
                    const cells = row.querySelectorAll('td, th');
                    const isHeader = index === 0 || row.querySelectorAll('th').length > 0;
                    const tag = isHeader ? 'th' : 'td';
                    
                    cells.forEach(cell => {
                        html += '<' + tag + '>' + cell.textContent.trim() + '</' + tag + '>';
                    });
                    html += '</tr>';
                });
                
                html += '</table>';
                return html;
            }
            
            function convertTextToTable(text) {
                const lines = text.trim().split('\n');
                if (lines.length === 0) return '';
                
                let html = '<table>';
                
                lines.forEach((line, index) => {
                    const cells = line.split('\t');
                    if (cells.length > 1) {
                        html += '<tr>';
                        const tag = index === 0 ? 'th' : 'td';
                        cells.forEach(cell => {
                            html += '<' + tag + '>' + cell.trim() + '</' + tag + '>';
                        });
                        html += '</tr>';
                    }
                });
                
                html += '</table>';
                return html;
            }
        });
        </script>
        <?php
    }

    /**
     * Save product meta
     */
    public function save_product_meta($post_id, $post)
    {
        // Check if it's a product
        if ($post->post_type !== 'top10_product') {
            return;
        }

        // Verify nonces and permissions
        if (
            !isset($_POST['top10_product_details_nonce']) ||
            !wp_verify_nonce($_POST['top10_product_details_nonce'], 'top10_product_details_nonce')
        ) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save product details
        if (isset($_POST['top10_badge_text'])) {
            update_post_meta($post_id, '_top10_badge_text', sanitize_text_field($_POST['top10_badge_text']));
        }

        if (isset($_POST['top10_price'])) {
            update_post_meta($post_id, '_top10_price', sanitize_text_field($_POST['top10_price']));
        }

        if (isset($_POST['top10_rating'])) {
            update_post_meta($post_id, '_top10_rating', floatval($_POST['top10_rating']));
        }

        if (isset($_POST['top10_affiliate_link'])) {
            update_post_meta($post_id, '_top10_affiliate_link', esc_url_raw($_POST['top10_affiliate_link']));
        }

        if (isset($_POST['top10_best_for'])) {
            update_post_meta($post_id, '_top10_best_for', sanitize_textarea_field($_POST['top10_best_for']));
        }

        if (isset($_POST['top10_ingredient_quality'])) {
            update_post_meta($post_id, '_top10_ingredient_quality', sanitize_text_field($_POST['top10_ingredient_quality']));
        }

        if (isset($_POST['top10_certifications'])) {
            update_post_meta($post_id, '_top10_certifications', sanitize_textarea_field($_POST['top10_certifications']));
        }

        if (isset($_POST['top10_safety_profile'])) {
            update_post_meta($post_id, '_top10_safety_profile', sanitize_textarea_field($_POST['top10_safety_profile']));
        }

        if (isset($_POST['top10_user_rating'])) {
            update_post_meta($post_id, '_top10_user_rating', sanitize_text_field($_POST['top10_user_rating']));
        }

        if (isset($_POST['top10_expert_rating'])) {
            update_post_meta($post_id, '_top10_expert_rating', sanitize_text_field($_POST['top10_expert_rating']));
        }

        // Save specifications
        if (isset($_POST['top10_specs']) && is_array($_POST['top10_specs'])) {
            $specs = array();
            foreach ($_POST['top10_specs'] as $key => $value) {
                if (in_array($key, ['research_observation', 'customer_review_summary', 'featured_review'])) {
                    $specs[$key] = sanitize_textarea_field($value);
                } else {
                    $specs[$key] = sanitize_text_field($value);
                }
            }
            update_post_meta($post_id, '_top10_specs', $specs);
        }

        // Save features
        if (isset($_POST['top10_features']) && is_array($_POST['top10_features'])) {
            $features = array_filter(array_map('sanitize_text_field', $_POST['top10_features']));
            update_post_meta($post_id, '_top10_features', $features);
        }

        // Save pros
        if (isset($_POST['top10_pros']) && is_array($_POST['top10_pros'])) {
            $pros = array_filter(array_map('sanitize_text_field', $_POST['top10_pros']));
            update_post_meta($post_id, '_top10_pros', $pros);
        }

        // Save cons
        if (isset($_POST['top10_cons']) && is_array($_POST['top10_cons'])) {
            $cons = array_filter(array_map('sanitize_text_field', $_POST['top10_cons']));
            update_post_meta($post_id, '_top10_cons', $cons);
        }
    }

    /**
     * Save page meta
     */
    public function save_page_meta($post_id, $post)
    {
        // Check if it's a Top 10 page
        if ($post->post_type !== 'top10_page') {
            return;
        }

        // Verify nonces and permissions
        if (
            !isset($_POST['top10_page_hero_nonce']) ||
            !wp_verify_nonce($_POST['top10_page_hero_nonce'], 'top10_page_hero_nonce')
        ) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save hero section
        if (isset($_POST['top10_hero_title'])) {
            update_post_meta($post_id, '_top10_hero_title', sanitize_text_field($_POST['top10_hero_title']));
        }

        if (isset($_POST['top10_hero_subtitle'])) {
            update_post_meta($post_id, '_top10_hero_subtitle', sanitize_textarea_field($_POST['top10_hero_subtitle']));
        }

        if (isset($_POST['top10_sidebar_text'])) {
            update_post_meta($post_id, '_top10_sidebar_text', sanitize_text_field($_POST['top10_sidebar_text']));
        }

        // Save selected products
        if (isset($_POST['top10_selected_products']) && is_array($_POST['top10_selected_products'])) {
            $products = array_map('intval', $_POST['top10_selected_products']);
            update_post_meta($post_id, '_top10_selected_products', $products);
        } else {
            update_post_meta($post_id, '_top10_selected_products', array());
        }

        // Save FAQs
        if (isset($_POST['top10_faqs']) && is_array($_POST['top10_faqs'])) {
            $faqs = array();
            foreach ($_POST['top10_faqs'] as $faq) {
                if (!empty($faq['question']) || !empty($faq['answer'])) {
                    $faqs[] = array(
                        'question' => sanitize_text_field($faq['question']),
                        'answer' => sanitize_textarea_field($faq['answer'])
                    );
                }
            }
            update_post_meta($post_id, '_top10_faqs', $faqs);
        }

        // Save Buying Guide
        if (isset($_POST['top10_buying_guide']) && is_array($_POST['top10_buying_guide'])) {
            $buying_guide = array();
            foreach ($_POST['top10_buying_guide'] as $item) {
                if (!empty($item['title']) || !empty($item['description'])) {
                    $buying_guide[] = array(
                        'title' => sanitize_text_field($item['title']),
                        'description' => sanitize_textarea_field($item['description'])
                    );
                }
            }
            update_post_meta($post_id, '_top10_buying_guide', $buying_guide);
        }

        // Save Research Methodology
        if (isset($_POST['top10_research_intro'])) {
            update_post_meta($post_id, '_top10_research_intro', sanitize_textarea_field($_POST['top10_research_intro']));
        }

        if (isset($_POST['top10_research_criteria']) && is_array($_POST['top10_research_criteria'])) {
            $criteria = array();
            foreach ($_POST['top10_research_criteria'] as $item) {
                if (!empty($item['criteria'])) {
                    $criteria[] = array(
                        'criteria' => sanitize_text_field($item['criteria']),
                        'weight' => sanitize_text_field($item['weight']),
                        'method' => sanitize_text_field($item['method'])
                    );
                }
            }
            update_post_meta($post_id, '_top10_research_criteria', $criteria);
        }
        
        if (isset($_POST['top10_research_sources'])) {
            update_post_meta($post_id, '_top10_research_sources', sanitize_textarea_field($_POST['top10_research_sources']));
        }

        // Save Conclusion
        if (isset($_POST['top10_conclusion'])) {
            update_post_meta($post_id, '_top10_conclusion', sanitize_textarea_field($_POST['top10_conclusion']));
        }

        // Save section toggles
        update_post_meta($post_id, '_top10_show_comparison', isset($_POST['top10_show_comparison']) ? '1' : '0');
        update_post_meta($post_id, '_top10_show_faqs', isset($_POST['top10_show_faqs']) ? '1' : '0');
        update_post_meta($post_id, '_top10_show_buying_guide', isset($_POST['top10_show_buying_guide']) ? '1' : '0');
        
        // Save theme color
        if (isset($_POST['top10_theme_color'])) {
            $allowed_themes = array('blue', 'green', 'red', 'black', 'white');
            $theme = sanitize_text_field($_POST['top10_theme_color']);
            if (in_array($theme, $allowed_themes)) {
                update_post_meta($post_id, '_top10_theme_color', $theme);
            }
        }
        
        // Save page layout
        if (isset($_POST['top10_page_layout'])) {
            $allowed_layouts = array('product', 'supplement');
            $layout = sanitize_text_field($_POST['top10_page_layout']);
            if (in_array($layout, $allowed_layouts)) {
                update_post_meta($post_id, '_top10_page_layout', $layout);
            }
        }
        
        // Save research document URLs
        if (isset($_POST['research_doc_excel_url'])) {
            update_post_meta($post_id, '_research_doc_excel_url', esc_url_raw($_POST['research_doc_excel_url']));
        }

        if (isset($_POST['research_doc_pdf_url'])) {
            update_post_meta($post_id, '_research_doc_pdf_url', esc_url_raw($_POST['research_doc_pdf_url']));
        }
        
        if (isset($_POST['research_doc_excel_link_text'])) {
            update_post_meta($post_id, '_research_doc_excel_link_text', sanitize_text_field($_POST['research_doc_excel_link_text']));
        }
        
        if (isset($_POST['research_doc_pdf_link_text'])) {
            update_post_meta($post_id, '_research_doc_pdf_link_text', sanitize_text_field($_POST['research_doc_pdf_link_text']));
        }
        
        if (isset($_POST['research_intro_text'])) {
            update_post_meta($post_id, '_research_intro_text', sanitize_textarea_field($_POST['research_intro_text']));
        }
        
        // Save source documents content
        if (isset($_POST['source_docs_intro'])) {
            update_post_meta($post_id, '_source_docs_intro', sanitize_textarea_field($_POST['source_docs_intro']));
        }
        
        if (isset($_POST['source_docs_bullets'])) {
            update_post_meta($post_id, '_source_docs_bullets', sanitize_textarea_field($_POST['source_docs_bullets']));
        }
        
        if (isset($_POST['source_docs_excel_url'])) {
            update_post_meta($post_id, '_source_docs_excel_url', esc_url_raw($_POST['source_docs_excel_url']));
        }
        
        if (isset($_POST['source_docs_pdf_url'])) {
            update_post_meta($post_id, '_source_docs_pdf_url', esc_url_raw($_POST['source_docs_pdf_url']));
        }
        
        if (isset($_POST['source_docs_excel_link_text'])) {
            update_post_meta($post_id, '_source_docs_excel_link_text', sanitize_text_field($_POST['source_docs_excel_link_text']));
        }
        
        if (isset($_POST['source_docs_pdf_link_text'])) {
            update_post_meta($post_id, '_source_docs_pdf_link_text', sanitize_text_field($_POST['source_docs_pdf_link_text']));
        }
        
        // Save Top 5 choices fields
        if (isset($_POST['top5_title_prefix'])) {
            update_post_meta($post_id, '_top5_title_prefix', sanitize_text_field($_POST['top5_title_prefix']));
        }
        if (isset($_POST['top5_step1_label'])) {
            update_post_meta($post_id, '_top5_step1_label', sanitize_text_field($_POST['top5_step1_label']));
        }
        if (isset($_POST['top5_step2_label'])) {
            update_post_meta($post_id, '_top5_step2_label', sanitize_text_field($_POST['top5_step2_label']));
        }
        if (isset($_POST['top5_step3_label'])) {
            update_post_meta($post_id, '_top5_step3_label', sanitize_text_field($_POST['top5_step3_label']));
        }
        if (isset($_POST['top5_step4_label'])) {
            update_post_meta($post_id, '_top5_step4_label', sanitize_text_field($_POST['top5_step4_label']));
        }
        if (isset($_POST['top5_step5_label'])) {
            update_post_meta($post_id, '_top5_step5_label', sanitize_text_field($_POST['top5_step5_label']));
        }
        
        // Save disclaimer text
        if (isset($_POST['disclaimer_text'])) {
            update_post_meta($post_id, '_disclaimer_text', wp_kses_post($_POST['disclaimer_text']));
        }
        
        // Save header background URL
        if (isset($_POST['header_bg_url'])) {
            update_post_meta($post_id, '_header_bg_url', esc_url_raw($_POST['header_bg_url']));
        }
        
        // Save comparison table HTML
        if (isset($_POST['comparison_table_html'])) {
            update_post_meta($post_id, '_comparison_table_html', wp_kses_post($_POST['comparison_table_html']));
        }
    }
}
