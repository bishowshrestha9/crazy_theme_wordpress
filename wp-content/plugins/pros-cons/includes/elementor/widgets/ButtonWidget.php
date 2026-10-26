<?php

namespace ProsCons\Elementor\Widgets;

use ProsCons\Helpers\Elementor\WidgetHelper;
use ProsCons\Traits\ButtonRenderTrait;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( '\Elementor\Widget_Base' ) ) {
	return;
}

/**
 * Button Widget Class
 *
 * @package ProsCons\Elementor\Widgets
 */
class ButtonWidget extends ElementorBase {

	use ButtonRenderTrait;

	protected function get_slug(): string {
		return 'buttons';
	}

	public function get_title() {
		return __( 'ProsCons Button', 'prosandcons' );
	}

	public function get_icon() {
		return 'affx-icon-button';
	}

	public function get_keywords() {
		return array(
			'Buttons',
			'ProsCons Buttons',
			'ProsCons',
		);
	}

	protected function register_controls() {
		WidgetHelper::generate_fields(
			$this,
			$this->get_button_elementor_fields(),
			'btn'
		);
	}

	protected function render(): void {
		$settings = $this->get_settings_for_display();
		echo wp_kses_post( $this->render_button( $settings ) ?? '' );
	}
}
