<?php

namespace ProsCons\Platforms;

use ProsCons\Platforms\Admin\PlatformSettings;

defined( 'ABSPATH' ) || exit;

/**
 * This controller is responsible to initialize all Amazon classes and methods
 *
 * @package ProsCons
 */
class PlatformController {
	public function __construct() {
		new PlatformSettings();
	}
}
