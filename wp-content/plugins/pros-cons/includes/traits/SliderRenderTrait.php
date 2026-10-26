<?php
/**
 * Shared slider rendering logic for blocks with multi-image gallery support.
 *
 * @package ProsCons
 */

namespace ProsCons\Traits;

defined( 'ABSPATH' ) || exit;

trait SliderRenderTrait {

	/**
	 * Default slider field values.
	 *
	 * @return array
	 */
	protected function get_slider_defaults(): array {
		return array(
			'useMultipleImages'     => false,
			'galleryImages'         => array(),
			'sliderShowPagination'  => true,
			'sliderShowArrows'      => true,
			'sliderArrowColor'      => '#000000',
			'sliderArrowHoverColor' => '#333333',
			'sliderAutoplay'        => false,
			'sliderAutoplaySpeed'   => 3000,
		);
	}

	/**
	 * Build slider data from block attributes.
	 *
	 * Returns an array with keys: useSlider, sliderImages, sliderConfig.
	 *
	 * @param bool   $use_multiple_images Whether multiple images are enabled.
	 * @param array  $gallery_images      Array of gallery image objects.
	 * @param bool   $show_arrows         Whether to show navigation arrows.
	 * @param bool   $show_pagination     Whether to show pagination dots.
	 * @param string $arrow_color         Arrow color hex string.
	 * @param string $arrow_hover_color   Arrow hover color hex string.
	 * @param bool   $autoplay            Whether to autoplay the slider.
	 * @param int    $autoplay_speed      Autoplay speed in milliseconds.
	 *
	 * @return array{useSlider: bool, sliderImages: array, sliderConfig: array}
	 */
	protected function build_slider_data(
		$use_multiple_images,
		$gallery_images,
		$show_arrows,
		$show_pagination,
		$arrow_color,
		$arrow_hover_color,
		$autoplay,
		$autoplay_speed
	): array {
		$use_slider    = false;
		$slider_images = array();
		$slider_config = array();

		if ( empty( $use_multiple_images ) || empty( $gallery_images ) || ! is_array( $gallery_images ) || count( $gallery_images ) <= 1 ) {
			return compact( 'use_slider', 'slider_images', 'slider_config' );
		}

		$use_slider = true;

		foreach ( $gallery_images as $img ) {
			if ( ! is_array( $img ) ) {
				continue;
			}

			$img_id  = isset( $img['id'] ) ? absint( $img['id'] ) : 0;
			$img_url = isset( $img['url'] ) ? $img['url'] : '';
			$img_alt = isset( $img['alt'] ) ? esc_attr( $img['alt'] ) : '';

			$img_url = $this->resolve_shortcode_image_url( $img_url, $img );

			if ( ! empty( $img_url ) ) {
				$slider_images[] = array(
					'id'  => $img_id,
					'url' => $img_url,
					'alt' => $img_alt,
				);
			}
		}

		if ( count( $slider_images ) < 2 ) {
			$use_slider = false;
		}

		$sanitized_arrow_color = sanitize_hex_color( $arrow_color ?? '#000000' );
		$sanitized_hover_color = sanitize_hex_color( $arrow_hover_color ?? '#333333' );

		$slider_config = array(
			'autoplay'      => ! empty( $autoplay ) ? absint( $autoplay_speed ) : false,
			'arrows'        => ! empty( $show_arrows ),
			'pagination'    => ! empty( $show_pagination ),
			'arrowColor'    => $sanitized_arrow_color ? $sanitized_arrow_color : '#000000',
			'arrowHvrColor' => $sanitized_hover_color ? $sanitized_hover_color : '#333333',
		);

		return compact( 'use_slider', 'slider_images', 'slider_config' );
	}

	/**
	 * Get Elementor controls for multi-image gallery/slider settings.
	 *
	 * Returns an associative array of control definitions that can be merged
	 * into any widget's controls array.
	 *
	 * @param string $image_toggle_key The control key for the "Enable Product Image" toggle (e.g. 'edProductImage', 'SCProductImage', 'ProductImage').
	 *
	 * @return array Associative array of Elementor control definitions.
	 */
	protected function get_slider_elementor_controls( string $image_toggle_key = 'edProductImage' ): array {
		return array(
			'useMultipleImages'     => array(
				'label'        => __( 'Use Multiple Images', 'prosandcons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => '',
				'condition'    => array(
					$image_toggle_key  => 'true',
					'productImageType' => 'default',
				),
			),
			'galleryImages'         => array(
				'label'     => __( 'Gallery Images', 'prosandcons' ),
				'type'      => \Elementor\Controls_Manager::GALLERY,
				'default'   => array(),
				'condition' => array(
					$image_toggle_key   => 'true',
					'productImageType'  => 'default',
					'useMultipleImages' => 'true',
				),
			),
			'galleryAmazonButton'   => array(
				'type'      => \Elementor\Controls_Manager::RAW_HTML,
				'raw'       => \ProsCons\Helpers\Elementor\WidgetHelper::get_amazon_button_html(),
				'condition' => array(
					$image_toggle_key   => 'true',
					'productImageType'  => 'default',
					'useMultipleImages' => 'true',
				),
			),
			'sliderShowArrows'      => array(
				'label'        => __( 'Show Arrows', 'prosandcons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
				'condition'    => array(
					$image_toggle_key   => 'true',
					'useMultipleImages' => 'true',
				),
			),
			'sliderShowPagination'  => array(
				'label'        => __( 'Show Pagination', 'prosandcons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
				'condition'    => array(
					$image_toggle_key   => 'true',
					'useMultipleImages' => 'true',
				),
			),
			'sliderArrowColor'      => array(
				'label'     => __( 'Arrow Color', 'prosandcons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#000000',
				'condition' => array(
					$image_toggle_key   => 'true',
					'useMultipleImages' => 'true',
				),
			),
			'sliderArrowHoverColor' => array(
				'label'     => __( 'Arrow Hover Color', 'prosandcons' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#333333',
				'condition' => array(
					$image_toggle_key   => 'true',
					'useMultipleImages' => 'true',
				),
			),
			'sliderAutoplay'        => array(
				'label'        => __( 'Autoplay', 'prosandcons' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => '',
				'condition'    => array(
					$image_toggle_key   => 'true',
					'useMultipleImages' => 'true',
				),
			),
			'sliderAutoplaySpeed'   => array(
				'label'     => __( 'Autoplay Speed (ms)', 'prosandcons' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 3000,
				'min'       => 1000,
				'max'       => 10000,
				'step'      => 500,
				'condition' => array(
					$image_toggle_key   => 'true',
					'useMultipleImages' => 'true',
					'sliderAutoplay'    => 'true',
				),
			),
		);
	}

	/**
	 * Resolve a gallery image URL that may contain an Amazon shortcode.
	 *
	 * Checks both the dedicated shortcode field and the url field for shortcodes,
	 * resolving them to actual image URLs via do_shortcode().
	 *
	 * @param string $img_url The image URL (may contain a shortcode).
	 * @param array  $img     The full image array (may have a 'shortcode' key).
	 *
	 * @return string Resolved and escaped image URL.
	 */
	protected function resolve_shortcode_image_url( string $img_url, array $img ): string {
		$shortcode_src = isset( $img['shortcode'] ) ? $img['shortcode'] : '';

		if ( ! empty( $shortcode_src ) && strpos( $shortcode_src, '[prosandcons-product' ) !== false ) {
			$resolved = do_shortcode( $shortcode_src );
			if ( ! empty( $resolved ) && $resolved !== $shortcode_src ) {
				$img_url = $resolved;
			}
		} elseif ( ! empty( $img_url ) && strpos( $img_url, '[prosandcons-product' ) !== false ) {
			$resolved = do_shortcode( $img_url );
			if ( ! empty( $resolved ) && $resolved !== $img_url ) {
				$img_url = $resolved;
			}
		}

		return esc_url( $img_url );
	}
}
