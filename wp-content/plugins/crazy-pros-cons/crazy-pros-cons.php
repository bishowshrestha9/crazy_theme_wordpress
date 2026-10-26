<?php
/**
 * Plugin Name: Crazy Pros and Cons Block
 * Description: Adds a Gutenberg block to display a beautifully styled Pros and Cons section.
 * Version: 1.0
 * Author: Antigravity
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function crazy_pros_cons_block_init() {
    wp_register_script(
        'crazy-pros-cons-block-editor',
        plugins_url( 'block.js', __FILE__ ),
        array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-block-editor', 'wp-components' ),
        filemtime( plugin_dir_path( __FILE__ ) . 'block.js' )
    );

    register_block_type( 'crazytheme/pros-cons', array(
        'editor_script' => 'crazy-pros-cons-block-editor',
    ) );
}
add_action( 'init', 'crazy_pros_cons_block_init' );
