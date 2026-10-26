<?php

/**
 * Blocks Initializer
 *
 * Enqueue CSS/JS of all the blocks.
 *
 * @since   1.0.0
 * @package Affiliate Booster
 */
// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * PHP version fail error
 *
 * @since 1.0.0
 * @package Affiliate Booster
 */
function affiliate_booster_fail_php_version() {
    /* translators: %s: PHP version */
    $message = sprintf(esc_html__('AffiliatePages for Gutenberg requires PHP version %s+, plugin is currently NOT RUNNING.', AFB_DOMAIN), '5.6');
    $html_message = sprintf('<div class="error">%s</div>', wpautop($message));
    echo wp_kses_post($html_message);
}

/**
 * Enqueue assets for frontend and backend
 *
 * @since 1.0.0
 * @package Affiliate Booster
 */
function affiliate_booster_block_assets() {

    if (is_singular()) {
        $id = get_the_ID();
  
        if (has_block('affiliate-booster/ab-advance-button', $id)) {
            wp_enqueue_style(
              'affiliate-block-ab-button_blocks-cgb-style-css',
              plugins_url( 'assets/blocks/ab-btn/style.css', dirname(__FILE__) ),
            );
        }        

        if (has_block('affiliate-booster/ab-callto-action', $id)) {
            wp_enqueue_style(
              'affiliate-block-ab-cta_blocks-cgb-style-css',
              plugins_url( 'assets/blocks/ab-cta/style.css', dirname(__FILE__) ),
            );
        }

        if (has_block('affiliate-booster/ab-comparison-table', $id)) {
          wp_enqueue_style(
            'affiliate-block-ab-comparison-table_blocks-cgb-style-css',
            plugins_url( 'assets/blocks/comparison-table/style.css', dirname(__FILE__) ),
          );
      }

        if (has_block('affiliate-booster/ab-notice-box', $id)) {
            wp_enqueue_style(
              'affiliate-block-ab-notice_blocks-cgb-style-css',
              plugins_url( 'assets/blocks/ab-notice/style.css', dirname(__FILE__) ),
            );
        }

        if (has_block('affiliate-booster/ab-notification-box', $id)) {
            wp_enqueue_style(
              'affiliate-block-ab-notification_blocks-cgb-style-css',
              plugins_url( 'assets/blocks/ab-notification/style.css', dirname(__FILE__) ),
            );
        }

        if (has_block('affiliate-booster/ab-single-product', $id)) {
            wp_enqueue_style(
              'affiliate-block-ab-single-product_blocks-cgb-style-css',
              plugins_url( 'assets/blocks/ab-single-product/style.css', dirname(__FILE__) ),
            );
        }

        if (has_block('affiliate-booster/ab-star-rating', $id)) {
            wp_enqueue_style(
              'affiliate-block-ab-star-rating_blocks-cgb-style-css',
              plugins_url( 'assets/blocks/ab-star-rating/style.css', dirname(__FILE__) ),
            );
        }

        if (has_block('affiliate-booster/propsandcons', $id)) {
            wp_enqueue_style(
              'affiliate-block-propsandcons_blocks-cgb-style-css',
              plugins_url( 'assets/blocks/propsandcons/style.css', dirname(__FILE__) ),
            );
        }

        if (has_block('affiliate-booster/ab-coupon2', $id)) {
              wp_enqueue_style(
                'affiliate-block-ab-coupon2_blocks-cgb-style-css',
                plugins_url( 'assets/blocks/ab-coupon2/style.css', dirname(__FILE__) ),
              );
          }

          if (has_block('affiliate-booster/ab-icon-list', $id)) {
            wp_enqueue_style(
              'affiliate-block-ab-icon-list_blocks-cgb-style-css',
              plugins_url( 'assets/blocks/ab-icon-list/style.css', dirname(__FILE__) ),
            );
        }

        if (has_block('affiliate-booster/ab-coupon4', $id)) {
          wp_enqueue_style(
              'affiliate-block-ab-coupon4_blocks-cgb-style-css',
              plugins_url( 'assets/blocks/ab-coupon4/style.css', dirname(__FILE__) ),
            );
        }
    }

    // Load the FontAwesome icon library
    wp_enqueue_style('affiliate-block-fontawesome', plugins_url('dist/assets/fontawesome/css/all.min.css', dirname(__FILE__)), array(), filemtime(plugin_dir_path(__FILE__) . 'assets/fontawesome/css/all.min.css'));
}

add_action('enqueue_block_assets', 'affiliate_booster_block_assets');

/**
 * Enqueue assets for backend editor
 *
 * @since 1.0.0
 * @package Affiliate Booster
 */
function affiliate_booster_block_editor_assets() {

    // Load the compiled blocks into the editor
    wp_enqueue_script('affiliate-block-js', plugins_url('/dist/blocks.build.js', dirname(__FILE__)), array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor', 'wp-api-fetch' ));

    // Load the compiled styles
    wp_enqueue_style('affiliate-block-style-css', plugins_url('dist/blocks.style.build.css', dirname(__FILE__)), array(), filemtime(plugin_dir_path(__FILE__) . 'blocks.style.build.css'));

    // Load the compiled styles into the editor
    wp_enqueue_style('affiliate-block-editor-css', plugins_url('dist/blocks.editor.build.css', dirname(__FILE__)), array('wp-edit-blocks'), filemtime(plugin_dir_path(__FILE__) . 'blocks.editor.build.css'));
}

add_action('enqueue_block_editor_assets', 'affiliate_booster_block_editor_assets');

/**
 * Enqueue assets for backend editor
 *
 * @since 1.0.0
 * @package Affiliate Booster
 */
function affiliate_booster_admin_assets() {
    
    // Load admin css
    wp_enqueue_style('affiliate-booster-admin-css', plugins_url('dist/assets/admin-style.css', dirname(__FILE__)));
}
add_action('admin_enqueue_scripts', 'affiliate_booster_admin_assets');

/**
 * Load the plugin textdomain
 */
function affiliate_booster_blocks_init() {

    load_plugin_textdomain('affiliate-booster-textdomain', false, basename(dirname(__FILE__)) . '/src/languages');
}

add_action('init', 'affiliate_booster_blocks_init');

// Add custom block category
function affiliate_register_category( $categories, $post ) {
    return array_merge(
        $categories, array(
            array(
                'slug' => 'affiliate-booster',
                'title' => __('AffiliatePages', AFB_DOMAIN),
            ),
        )
    );
}

//PHP version compare
if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) ) {
    add_action('admin_notices', 'affiliate_booster_fail_php_version');
} else {
    require_once ( AFB_DIR . 'src/affiliate-helpers/class-affiliate-loader.php');
}

//remove noreferrer on the frontend, *will still show up in the editor.*
function afffiliate_formatter($content) {
    $replace = array("noreferrer " => "" );
    $new_content = strtr($content, $replace);
    return $new_content;
}

//remove noopener on the frontend, *will still show up in the editor.*
function afffiliate_noopener_formatter($content) {
    $replace = array("noopener " => "" );
    $new_content = strtr($content, $replace);
    return $new_content;
}




//For Widget Loading
function is_active_block_widget_abwba( $blockname ){
    $widget_blocks = get_option( 'widget_block' );
    foreach( (array) $widget_blocks as $widget_block ) {
        if ( ! empty( $widget_block['content'] ) 
             && has_block( $blockname, $widget_block['content'] ) 
        ) {
            return true;
        }
    }
    return false;
}


function affiliate_booster_widget_block_assets() {   

      if (is_active_block_widget_abwba( 'affiliate-booster/ab-advance-button' )) {
          wp_enqueue_style(
            'affiliate-block-ab-button_blocks-cgb-style-css',
            plugins_url( 'assets/blocks/ab-btn/style.css', dirname(__FILE__) ),
          );
      } 

      if (is_active_block_widget_abwba( 'affiliate-booster/ab-callto-action' )) {
          wp_enqueue_style(
            'affiliate-block-ab-cta_blocks-cgb-style-css',
            plugins_url( 'assets/blocks/ab-cta/style.css', dirname(__FILE__) ),
          );
      }

      if (is_active_block_widget_abwba( 'affiliate-booster/ab-comparison-table' )) {
        wp_enqueue_style(
          'affiliate-block-ab-comparison-table_blocks-cgb-style-css',
          plugins_url( 'assets/blocks/comparison-table/style.css', dirname(__FILE__) ),
        );
    }

      if (is_active_block_widget_abwba( 'affiliate-booster/ab-notice-box' )) {
          wp_enqueue_style(
            'affiliate-block-ab-notice_blocks-cgb-style-css',
            plugins_url( 'assets/blocks/ab-notice/style.css', dirname(__FILE__) ),
          );
      }

      if (is_active_block_widget_abwba( 'affiliate-booster/ab-notification-box' )) {
          wp_enqueue_style(
            'affiliate-block-ab-notification_blocks-cgb-style-css',
            plugins_url( 'assets/blocks/ab-notification/style.css', dirname(__FILE__) ),
          );
      }  

      if (is_active_block_widget_abwba( 'affiliate-booster/ab-single-product' )) {
          wp_enqueue_style(
            'affiliate-block-ab-single-product_blocks-cgb-style-css',
            plugins_url( 'assets/blocks/ab-single-product/style.css', dirname(__FILE__) ),
          );
      }

    

      if (is_active_block_widget_abwba( 'affiliate-booster/ab-star-rating' )) {
          wp_enqueue_style(
            'affiliate-block-ab-star-rating_blocks-cgb-style-css',
            plugins_url( 'assets/blocks/ab-star-rating/style.css', dirname(__FILE__) ),
          );
      }
 

      if (is_active_block_widget_abwba( 'affiliate-booster/propsandcons' )) {
          wp_enqueue_style(
            'affiliate-block-propsandcons_blocks-cgb-style-css',
            plugins_url( 'assets/blocks/propsandcons/style.css', dirname(__FILE__) ),
          );
      }


      if (is_active_block_widget_abwba( 'affiliate-booster/ab-coupon2' )) {
          wp_enqueue_style(
            'affiliate-block-ab-coupon2_blocks-cgb-style-css',
            plugins_url( 'assets/blocks/ab-coupon2/style.css', dirname(__FILE__) ),
          );
      }


      if (is_active_block_widget_abwba( 'affiliate-booster/ab-icon-list' )) {
          wp_enqueue_style(
            'affiliate-block-ab-icon-list_blocks-cgb-style-css',
            plugins_url( 'assets/blocks/ab-icon-list/style.css', dirname(__FILE__) ),
          );
      }


      if (is_active_block_widget_abwba( 'affiliate-booster/ab-coupon4' )) {
          wp_enqueue_style(
            'affiliate-block-ab-coupon4_blocks-cgb-style-css',
            plugins_url( 'assets/blocks/ab-coupon4/style.css', dirname(__FILE__) ),
          );
      }
      

}

add_action('enqueue_block_assets', 'affiliate_booster_widget_block_assets');