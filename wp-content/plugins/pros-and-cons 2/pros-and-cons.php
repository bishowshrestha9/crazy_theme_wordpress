<?php
/**
 * Plugin Name: Pros and Cons Block
 * Plugin URI: https://wordpress.org/plugins/pros-and-cons-block/
 * Description: Add responsive, beautifully styled Pros and Cons boxes to your posts and pages using Gutenberg.
 * Version: 2.0.8
 * Author: Om Kunwar & Bishow Shrestha
 * Author URI: https://wordpress.org
 * Text Domain: pros-and-cons
 * License: GPLv2 or later
 *
 * @package ProsAndCons
 */

use ProsAndCons\Plugin;

defined( 'ABSPATH' ) || exit;

define( 'PROS_CONS_VERSION', '2.0.3' );
define( 'PROS_CONS_PLUGIN_FILE', __FILE__ );
define( 'PROS_CONS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PROS_CONS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PROS_CONS_TABLET_BREAKPOINT', 1024 );
define( 'PROS_CONS_MOBILE_BREAKPOINT', 767 );

// Load Helper & Core Files
require_once PROS_CONS_PLUGIN_DIR . 'includes/Helpers/Functions.php';
require_once PROS_CONS_PLUGIN_DIR . 'includes/Helpers/Helpers.php';
require_once PROS_CONS_PLUGIN_DIR . 'includes/Helpers/HoverStyles.php';
require_once PROS_CONS_PLUGIN_DIR . 'includes/Helpers/BlockStylesBase.php';
require_once PROS_CONS_PLUGIN_DIR . 'includes/Helpers/ProsAndConsStyles.php';
require_once PROS_CONS_PLUGIN_DIR . 'includes/Helpers/BlockHelper.php';

require_once PROS_CONS_PLUGIN_DIR . 'includes/FontsManager.php';
require_once PROS_CONS_PLUGIN_DIR . 'includes/Frontend.php';
require_once PROS_CONS_PLUGIN_DIR . 'includes/Traits/ProsAndConsRenderTrait.php';
require_once PROS_CONS_PLUGIN_DIR . 'includes/Blocks/BaseBlock.php';
require_once PROS_CONS_PLUGIN_DIR . 'includes/Blocks/ProsAndConsBlock.php';
require_once PROS_CONS_PLUGIN_DIR . 'includes/Plugin.php';

function pros_and_cons_init() {
	return Plugin::instance();
}

$GLOBALS['ProsAndCons'] = pros_and_cons_init();
