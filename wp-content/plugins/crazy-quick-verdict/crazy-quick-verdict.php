<?php
/**
 * Plugin Name: Crazy Quick Verdict Block
 * Description: Adds a Gutenberg block to display a beautifully styled green Quick Verdict box.
 * Version: 2.0
 * Author: Antigravity
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function crazy_quick_verdict_block_init() {
    wp_register_script(
        'crazy-quick-verdict-block-editor',
        plugins_url( 'block.js', __FILE__ ),
        array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-block-editor', 'wp-components' ),
        filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
    );

    register_block_type( 'crazytheme/quick-verdict', array(
        'editor_script' => 'crazy-quick-verdict-block-editor',
    ) );
}
add_action( 'init', 'crazy_quick_verdict_block_init' );
