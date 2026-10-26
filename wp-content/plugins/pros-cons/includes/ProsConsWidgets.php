<?php

namespace ProsCons;

use ProsCons\Elementor\WidgetManager;
use ProsCons\Elementor\Widgets\ButtonWidget;
use ProsCons\Elementor\Widgets\CtaWidget;
use ProsCons\Elementor\Widgets\ProsAndConsWidget;
use ProsCons\Elementor\Widgets\NoticeWidget;
use ProsCons\Elementor\Widgets\ProductComparisonWidget;
use ProsCons\Elementor\Widgets\ProductTableWidget;
use ProsCons\Elementor\Widgets\SingleProductWidget;
use ProsCons\Elementor\Widgets\VerdictWidget;
use ProsCons\Elementor\Widgets\SpecificationsWidget;
use ProsCons\Elementor\Widgets\VersusLineWidget;

defined( 'ABSPATH' ) || exit;

/**
 * Elementor Widgets class
 *
 * @package ProsCons
 */
class ProsConsWidgets {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'elementor/init', array( $this, 'init' ) );
		add_filter( 'prosandcons_widgets_before_init', array( $this, 'disable_widgets' ) );
	}

	public function init() {
		// Check if Elementor is installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			return;
		}

		$widgets = apply_filters(
			'prosandcons_widgets_before_init',
			array(
				'prosandcons/buttons'            => ButtonWidget::class,
				'prosandcons/cta'                => CtaWidget::class,
				'prosandcons/notice'             => NoticeWidget::class,
				'prosandcons/product-comparison' => ProductComparisonWidget::class,
				'prosandcons/product-table'      => ProductTableWidget::class,
				'prosandcons/pros-and-cons'      => ProsAndConsWidget::class,
				'prosandcons/single-product'     => SingleProductWidget::class,
				'prosandcons/specifications'     => SpecificationsWidget::class,
				'prosandcons/verdict'            => VerdictWidget::class,
				'prosandcons/versus-line'        => VersusLineWidget::class,
			)
		);

		new WidgetManager( $widgets );
	}

	/**
	 * Disable widgets that are disabled in the block settings.
	 *
	 * @param array $widgets Widgets array.
	 * @return array
	 */
	public function disable_widgets( $widgets ) {
		$disabled_widgets = affx_get_disabled_blocks();

		if ( ! empty( $disabled_widgets ) ) {
			foreach ( $disabled_widgets as $widget ) {
				unset( $widgets[ $widget ] );
			}
		}

		return $widgets;
	}
}
