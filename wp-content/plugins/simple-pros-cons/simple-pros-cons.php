<?php
/**
 * Plugin Name:       Simple Pros & Cons
 * Description:       A lightweight, PHP 8 compatible Gutenberg block for adding beautiful Pros & Cons lists to your posts.
 * Version:           1.0.0
 * Requires PHP:      7.4
 * Author:            Bishow Shrestha
 * License:           GPL-2.0-or-later
 * Text Domain:       simple-pros-cons
 */

if (!defined('ABSPATH')) {
    exit;
}

define('SPC_VERSION', '1.0.0');
define('SPC_DIR', plugin_dir_path(__FILE__));
define('SPC_URL', plugins_url('/', __FILE__));

// Load converter and admin classes
require_once SPC_DIR . 'includes/class-spc-converter.php';
require_once SPC_DIR . 'includes/class-spc-admin.php';

// Boot admin tools
add_action('plugins_loaded', function () {
    if (is_admin()) {
        new SPC_Admin(new SPC_Converter());
    }
});

/**
 * Register the Gutenberg block on init.
 */
function spc_register_block()
{
    if (!function_exists('register_block_type')) {
        return;
    }

    // Register editor script
    wp_register_script(
        'spc-editor-js',
        SPC_URL . 'assets/js/editor.js',
        array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
        SPC_VERSION,
        true
    );

    // Register editor style
    wp_register_style(
        'spc-editor-css',
        SPC_URL . 'assets/css/editor.css',
        array('wp-edit-blocks'),
        SPC_VERSION
    );

    // Register frontend style
    wp_register_style(
        'spc-style-css',
        SPC_URL . 'assets/css/style.css',
        array(),
        SPC_VERSION
    );

    register_block_type('simple-pros-cons/pros-cons', array(
        'editor_script' => 'spc-editor-js',
        'editor_style' => 'spc-editor-css',
        'style' => 'spc-style-css',
        'render_callback' => 'spc_render_block',
        'attributes' => array(
            'prosTitle' => array(
                'type' => 'string',
                'default' => 'Pros',
            ),
            'consTitle' => array(
                'type' => 'string',
                'default' => 'Cons',
            ),
            'prosItems' => array(
                'type' => 'array',
                'items' => array('type' => 'string'),
                'default' => array(''),
            ),
            'consItems' => array(
                'type' => 'array',
                'items' => array('type' => 'string'),
                'default' => array(''),
            ),
            'prosBgColor' => array(
                'type' => 'string',
                'default' => '#00bf08',
            ),
            'consBgColor' => array(
                'type' => 'string',
                'default' => '#eb3903',
            ),
            'prosIconColor' => array(
                'type' => 'string',
                'default' => '#30ac3d',
            ),
            'consIconColor' => array(
                'type' => 'string',
                'default' => '#ff1818',
            ),
            'prosTextColor' => array(
                'type' => 'string',
                'default' => '#ffffff',
            ),
            'consTextColor' => array(
                'type' => 'string',
                'default' => '#ffffff',
            ),
            'prosListColor' => array(
                'type' => 'string',
                'default' => '#333333',
            ),
            'consListColor' => array(
                'type' => 'string',
                'default' => '#333333',
            ),
        ),
    ));
}
add_action('init', 'spc_register_block');

/**
 * Server-side render callback for the Pros & Cons block.
 *
 * @param array $attributes Block attributes.
 * @return string HTML output.
 */
function spc_render_block(array $attributes): string
{
    $pros_title = isset($attributes['prosTitle']) ? $attributes['prosTitle'] : 'Pros';
    $cons_title = isset($attributes['consTitle']) ? $attributes['consTitle'] : 'Cons';
    $pros_items = isset($attributes['prosItems']) && is_array($attributes['prosItems']) ? $attributes['prosItems'] : array();
    $cons_items = isset($attributes['consItems']) && is_array($attributes['consItems']) ? $attributes['consItems'] : array();
    $pros_bg = isset($attributes['prosBgColor']) ? $attributes['prosBgColor'] : '#00bf08';
    $cons_bg = isset($attributes['consBgColor']) ? $attributes['consBgColor'] : '#eb3903';
    $pros_icon_color = isset($attributes['prosIconColor']) ? $attributes['prosIconColor'] : '#30ac3d';
    $cons_icon_color = isset($attributes['consIconColor']) ? $attributes['consIconColor'] : '#ff1818';
    $pros_text_color = isset($attributes['prosTextColor']) ? $attributes['prosTextColor'] : '#ffffff';
    $cons_text_color = isset($attributes['consTextColor']) ? $attributes['consTextColor'] : '#ffffff';
    $pros_list_color = isset($attributes['prosListColor']) ? $attributes['prosListColor'] : '#333333';
    $cons_list_color = isset($attributes['consListColor']) ? $attributes['consListColor'] : '#333333';

    // Build pros items HTML
    $pros_html = '';
    foreach ($pros_items as $item) {
        $item = trim((string) $item);
        if ($item === '') {
            continue;
        }
        $pros_html .= sprintf(
            '<li style="color:%s;--spc-check-color:%s;">%s</li>',
            esc_attr($pros_list_color),
            esc_attr($pros_icon_color),
            esc_html($item)
        );
    }

    // Build cons items HTML
    $cons_html = '';
    foreach ($cons_items as $item) {
        $item = trim((string) $item);
        if ($item === '') {
            continue;
        }
        $cons_html .= sprintf(
            '<li style="color:%s;--spc-cross-color:%s;">%s</li>',
            esc_attr($cons_list_color),
            esc_attr($cons_icon_color),
            esc_html($item)
        );
    }

    ob_start();
    ?>
    <div class="spc-wrapper">
        <div class="spc-inner">
            <div class="spc-pros-col">
                <div class="spc-header spc-pros-header"
                    style="background-color:<?php echo esc_attr($pros_bg); ?>;color:<?php echo esc_attr($pros_text_color); ?>;">
                    <span class="spc-header-icon">&#128077;</span>
                    <span class="spc-header-title">
                        <?php echo esc_html($pros_title); ?>
                    </span>
                </div>
                <ul class="spc-list spc-pros-list" style="--spc-check-color:<?php echo esc_attr($pros_icon_color); ?>;">
                    <?php echo $pros_html; // phpcs:ignore WordPress.Security.EscapeOutput ?>
                </ul>
            </div>
            <div class="spc-cons-col">
                <div class="spc-header spc-cons-header"
                    style="background-color:<?php echo esc_attr($cons_bg); ?>;color:<?php echo esc_attr($cons_text_color); ?>;">
                    <span class="spc-header-icon">&#128078;</span>
                    <span class="spc-header-title">
                        <?php echo esc_html($cons_title); ?>
                    </span>
                </div>
                <ul class="spc-list spc-cons-list" style="--spc-cross-color:<?php echo esc_attr($cons_icon_color); ?>;">
                    <?php echo $cons_html; // phpcs:ignore WordPress.Security.EscapeOutput ?>
                </ul>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Add block category for the plugin.
 */
function spc_block_categories(array $categories): array
{
    return array_merge(
        array(
            array(
                'slug' => 'simple-pros-cons',
                'title' => __('Simple Pros & Cons', 'simple-pros-cons'),
                'icon' => 'yes-alt',
            ),
        ),
        $categories
    );
}
add_filter('block_categories_all', 'spc_block_categories');
