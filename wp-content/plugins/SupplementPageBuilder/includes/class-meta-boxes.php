<?php
/**
 * Meta Boxes Class - Helper Only
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class SPB_Meta_Boxes
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
        add_action('save_post', array($this, 'save_meta_boxes'));
    }

    /**
     * Add meta boxes
     */
    public function add_meta_boxes()
    {
        // Page Settings Box
        add_meta_box(
            'spb_page_settings',
            __('⚙️ Page Settings', 'supplement-page-builder'),
            array($this, 'render_page_settings_meta_box'),
            'supplement_page',
            'side',
            'high'
        );
        
        // Helper/Instructions Box
        add_meta_box(
            'spb_instructions',
            __('📖 How to Use Block Components', 'supplement-page-builder'),
            array($this, 'render_instructions_meta_box'),
            'supplement_page',
            'side',
            'default'
        );
    }

    /**
     * Render page settings meta box
     */
    public function render_page_settings_meta_box($post)
    {
        wp_nonce_field('spb_page_settings_nonce', 'spb_page_settings_nonce');
        
        $header_bg_url = get_post_meta($post->ID, '_spb_header_bg_url', true);
        $theme_color = get_post_meta($post->ID, '_spb_theme_color', true) ?: 'green';
        ?>
        <div style="padding: 10px;">
            <p>
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">
                    <strong>Header Background Image URL:</strong>
                </label>
                <input type="url" name="spb_header_bg_url" value="<?php echo esc_attr($header_bg_url); ?>" 
                       style="width: 100%;" placeholder="https://example.com/image.jpg">
                <small style="display: block; margin-top: 5px; color: #666;">Leave empty for solid color background</small>
            </p>
            
            <p style="margin-top: 20px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 600;">
                    <strong>Theme Color:</strong>
                </label>
                <select name="spb_theme_color" style="width: 100%;">
                    <option value="green" <?php selected($theme_color, 'green'); ?>>Green (Default)</option>
                    <option value="blue" <?php selected($theme_color, 'blue'); ?>>Blue</option>
                    <option value="red" <?php selected($theme_color, 'red'); ?>>Red</option>
                    <option value="black" <?php selected($theme_color, 'black'); ?>>Black</option>
                </select>
            </p>
        </div>
        <?php
    }

    /**
     * Save meta boxes
     */
    public function save_meta_boxes($post_id)
    {
        // Check nonce
        if (!isset($_POST['spb_page_settings_nonce']) || !wp_verify_nonce($_POST['spb_page_settings_nonce'], 'spb_page_settings_nonce')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save header background URL
        if (isset($_POST['spb_header_bg_url'])) {
            update_post_meta($post_id, '_spb_header_bg_url', esc_url_raw($_POST['spb_header_bg_url']));
        }

        // Save theme color
        if (isset($_POST['spb_theme_color'])) {
            update_post_meta($post_id, '_spb_theme_color', sanitize_text_field($_POST['spb_theme_color']));
        }
    }

    /**
     * Render instructions meta box
     */
    public function render_instructions_meta_box($post)
    {
        ?>
        <div style="padding: 10px;">
            <p><strong>📌 Quick Start Guide:</strong></p>
            
            <ol style="margin: 10px 0; padding-left: 20px; line-height: 1.8;">
                <li><strong>Header Image & Color:</strong> Use "Page Settings" box above to set header background image URL and theme color</li>
                <li><strong>Add Blocks:</strong> Type <code>/</code> in the editor to see all available blocks:
                    <ul style="margin-top: 5px; padding-left: 20px;">
                        <li><code>/top-choices</code> - Product table</li>
                        <li><code>/research</code> - Research docs</li>
                        <li><code>/comparison</code> - Comparison table</li>
                        <li><code>/faq</code> - FAQ section</li>
                        <li><code>/source</code> - Source documents</li>
                        <li><code>/disclaimer</code> - Disclaimer box</li>
                    </ul>
                </li>
                <li><strong>Add Content:</strong> Fill in the fields in each block</li>
                <li><strong>Reorder:</strong> Drag blocks to change order</li>
                <li><strong>Publish:</strong> Click "Publish" when done</li>
            </ol>
            
            <p style="margin-top: 15px; padding: 10px; background: #E0F2FE; border-radius: 5px; font-size: 13px;">
                💡 <strong>Tip:</strong> For comparison tables, you can paste HTML from Excel/Google Sheets directly into the table block!
            </p>
        </div>
        <?php
    }
}
