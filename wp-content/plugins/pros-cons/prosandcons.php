<?php

/**
 * Plugin Name:     Pros and Cons Block
 * Description:     A lightweight, standalone Pros and Cons block for Gutenberg, providing beautiful comparison features.
 * Author:          Om Kunwar
 * Text Domain:     prosandcons
 * Domain Path:     /languages
 * Version:         1.0.1
 * Requires at least: 5.8
 * Requires PHP:      7.4
 */
use ProsCons\ProsCons;
defined( 'ABSPATH' ) || exit;

if ( !defined( 'PROSCONS_PLUGIN_FILE' ) ) {
    define( 'PROSCONS_PLUGIN_FILE', __FILE__ );
}

if ( !defined( 'PROSCONS_VERSION' ) ) {
    define( 'PROSCONS_VERSION', '1.0.1' );
}

require_once __DIR__ . '/vendor/autoload.php';



/**
 * Init function
 */
function prosandcons_init() {
    return ProsCons::instance();
}

$GLOBALS['ProsCons'] = prosandcons_init();
// Invokes all functions attached to the 'prosandcons_free_loaded' hook
do_action( 'prosandcons_free_loaded' );