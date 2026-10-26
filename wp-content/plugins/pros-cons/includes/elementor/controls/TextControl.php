<?php

namespace ProsCons\Elementor\Controls;

use Elementor\Control_Text;

/**
 * Text Control Class
 *
 * @package ProsCons\Elementor\Controls
 */
class TextControl extends Control_Text {

	public function get_type() {
		return 'prosandcons_text';
	}

	protected function get_default_settings() {
		return array(
			'label_block'         => true,
			'amazon_button'       => true,
			'search_button'       => false,
			'repeater_name'       => null,
			'inner_repeater_name' => null,
		);
	}

	public function content_template() {
		include PROSCONS_PLUGIN_DIR . '/templates/elementor/controls/text-content.php';
	}
}
