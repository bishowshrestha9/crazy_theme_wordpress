<?php

namespace ProsCons\Elementor\Controls;

use Elementor\Control_Textarea;
use Elementor\Modules\DynamicTags\Module as TagsModule;

/**
 * Text Area Control Class
 *
 * @package ProsCons\Elementor\Controls
 */
class TextAreaControl extends Control_Textarea {

	public function get_type() {
		return 'prosandcons_textarea';
	}

	protected function get_default_settings() {
		return array(
			'label_block'         => true,
			'rows'                => 4,
			'placeholder'         => '',
			'ai'                  => array(
				'active' => false,
				'type'   => 'textarea',
			),
			'dynamic'             => array(
				'categories' => array( TagsModule::TEXT_CATEGORY ),
			),
			'amazon_button'       => true,
			'repeater_name'       => null,
			'inner_repeater_name' => null,
		);
	}

	public function content_template() {
		include PROSCONS_PLUGIN_DIR . '/templates/elementor/controls/textarea-content.php';
	}
}
