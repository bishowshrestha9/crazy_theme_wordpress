<?php
defined( 'ABSPATH' ) || exit;
/**
 * Fonts Manager Class
 *
 * @package ProsCons
 */
class AB_Fonts_Manager {

	/**
	 * Page Blocks Variable
	 *
	 * @since 1.6.0
	 * @var instance
	 */
	public static $page_blocks;


	/**
	 * Stylesheet
	 *
	 * @since 1.13.4
	 * @var stylesheet
	 */
	public static $stylesheet;

	/**
	 * Script
	 *
	 * @since 1.13.4
	 * @var script
	 */
	public static $script;

	public function get_all_fonts() {
		return apply_filters(
			'prosandcons_typography_font_sources',
			array(
				'system' => array(
					'type'     => 'system',
					'families' => $this->get_system_fonts(),
				),

				'google' => array(
					'type'     => 'google',
					'families' => $this->get_googgle_fonts(),
				),
			)
		);
	}

	public function get_static_fonts_ids() {
		$font_ids = array();
		if ( is_single() || is_page() || is_404() ) {
			global $post;
			$this_post = $post;
			if ( has_blocks( $this_post->ID ) && isset( $this_post->post_content ) ) {

				$blocks = $this->parse( $this_post->post_content );

				if ( ! is_array( $blocks ) || empty( $blocks ) ) {
					return array();
				}

				$font_ids = $this->get_blocks_fonts( $blocks );

			}
			if ( ! is_object( $post ) ) {
				return array();
			}
		}
		return $font_ids;
	}

	/**
	 * Generates stylesheet for reusable blocks.
	 *
	 * @param array $blocks Blocks array.
	 * @since 1.1.0
	 */
	public function get_assets( $blocks ) {

		$desktop = '';
		$tablet  = '';
		$mobile  = '';

		$tab_styling_css = '';
		$mob_styling_css = '';

		$js = '';

		foreach ( $blocks as $i => $block ) {

			if ( is_array( $block ) ) {

				if ( '' === $block['blockName'] ) {
					continue;
				}
				if ( 'core/block' === $block['blockName'] ) {
					$id = ( isset( $block['attrs']['ref'] ) ) ? $block['attrs']['ref'] : 0;

					if ( $id ) {
						$content = get_post_field( 'post_content', $id );

						$reusable_blocks = $this->parse( $content );

						$assets = $this->get_assets( $reusable_blocks );

						self::$stylesheet .= $assets['css'];
						self::$script     .= $assets['js'];

					}
				} else {

					$block_assets = $this->get_block_fonts( $block, true );

					// Get CSS for the Block.
					$css = isset( $block_assets['css'] ) ? $block_assets['css'] : '';

					if ( isset( $css['desktop'] ) ) {
						$desktop .= $css['desktop'];
						$tablet  .= $css['tablet'];
						$mobile  .= $css['mobile'];
					}

					$js .= isset( $block_assets['js'] ) ? $block_assets['js'] : '';
				}
			}
		}

		if ( ! empty( $tablet ) ) {
			$tab_styling_css .= '@media only screen and (max-width: ' . PROSCONS_TABLET_BREAKPOINT . 'px) {';
			$tab_styling_css .= $tablet;
			$tab_styling_css .= '}';
		}

		if ( ! empty( $mobile ) ) {
			$mob_styling_css .= '@media only screen and (max-width: ' . PROSCONS_MOBILE_BREAKPOINT . 'px) {';
			$mob_styling_css .= $mobile;
			$mob_styling_css .= '}';
		}

		return array(
			'css' => $desktop . $tab_styling_css . $mob_styling_css,
			'js'  => $js,
		);
	}

	/**
	 * Generates stylesheet and appends in head tag.
	 *
	 * @since 0.0.1
	 */
	public function generate_assets() {

		$this_post = array();

		if ( is_single() || is_page() || is_404() ) {

			global $post;
			$this_post = $post;

			if ( ! is_object( $this_post ) ) {
				return;
			}

			/**
			 * Filters the post to build stylesheet for.
			 *
			 * @param \WP_Post $this_post The global post.
			 */
			$this_post = apply_filters( 'prosandcons_post_for_stylesheet', $this_post );

			$this->get_generated_stylesheet( $this_post );
		}

		// Generate stylesheet for widget blocks on all pages that might display widgets
		// This includes home, archive, category, tag, author, search pages etc.
		if ( ! is_admin() ) {
			$this->get_generated_widget_stylesheet();
		}
	}

	/**
	 * Generates stylesheet in loop.
	 *
	 * @param object $this_post Current Post Object.
	 * @since 1.7.0
	 */
	public function get_generated_stylesheet( $this_post ) {

		if ( ! is_object( $this_post ) ) {
			return;
		}

		if ( ! isset( $this_post->ID ) ) {
			return;
		}

		if ( has_blocks( $this_post->ID ) && isset( $this_post->post_content ) ) {

			$blocks            = $this->parse( $this_post->post_content );
			self::$page_blocks = $blocks;

			if ( ! is_array( $blocks ) || empty( $blocks ) ) {
				return;
			}

			$assets = $this->get_assets( $blocks );

			self::$stylesheet .= $assets['css'];
			self::$script     .= $assets['js'];
		}
	}

	/**
	 * Generates stylesheet for widget blocks.
	 *
	 * @since 1.7.0
	 */
	public function get_generated_widget_stylesheet() {
		// Get block widgets
		$block_widgets = get_option( 'widget_block' );
		if ( empty( $block_widgets ) || ! is_array( $block_widgets ) ) {
			return;
		}

		// Process each widget's blocks
		foreach ( $block_widgets as $widget ) {
			if ( empty( $widget['content'] ) ) {
				continue;
			}

			$blocks = $this->parse( $widget['content'] );
			if ( is_array( $blocks ) && ! empty( $blocks ) ) {
				$assets            = $this->get_assets( $blocks );
				self::$stylesheet .= $assets['css'];
				self::$script     .= $assets['js'];
			}
		}
	}

	/**
	 * Generates stylesheet for reusable blocks.
	 *
	 * @param array $blocks Blocks array.
	 * @since 1.1.0
	 */
	public function get_blocks_fonts( $blocks ) {

		$blocks_fonts = array();

		foreach ( $blocks as $i => $block ) {

			if ( is_array( $block ) ) {

				if ( '' === $block['blockName'] ) {
					continue;
				}
				if ( 'core/block' === $block['blockName'] ) {
					$id = ( isset( $block['attrs']['ref'] ) ) ? $block['attrs']['ref'] : 0;

					if ( $id ) {
						$content = get_post_field( 'post_content', $id );

						$reusable_blocks = $this->parse( $content );

						$assets[ $i ] = $this->get_blocks_fonts( $reusable_blocks );

					}
				} else {
					$_block_fonts = $this->get_block_fonts( $block );
					if ( is_array( $_block_fonts ) ) {
						foreach ( $_block_fonts as $_block_font ) {
							if ( ! is_array( $_block_font ) ) {
								continue;
							}

							$blocks_fonts = array_merge( $blocks_fonts, $_block_font );
						}
					}
				}
			}
		}

		return $blocks_fonts;
	}

	/**
	 * Flatten an array.
	 *
	 * @param array $array
	 * @return array
	 */
	public function flatten( array $array ) {
		$return = array();
		array_walk_recursive(
			$array,
			function ( $a ) use ( &$return ) {
				$return[] = $a;
			}
		);
		return $return;
	}

	/**
	 * Parse Guten Block.
	 *
	 * @param string $content the content string.
	 * @since 1.1.0
	 */
	public function parse( $content ) {

		global $wp_version;

		return ( version_compare( $wp_version, '5', '>=' ) ) ? parse_blocks( $content ) : gutenberg_parse_blocks( $content );
	}

	/**
	 * Get Blocks Fonts.
	 *
	 * @param object $block        The block object.
	 * @param bool   $css_js_only  Whether to only return CSS/JS.
	 * @since 1.0.0
	 */
	public function get_block_fonts( $block, $css_js_only = false ) {

		// @codingStandardsIgnoreStart

		$block = (array) $block;

		$block_fonts = [];
		$assets = [];
		$inner_assets = [];

		$css_desktop = '';
		$css_tablet  = '';
		$css_mobile  = '';

		$name = $block['blockName'];
		$css  = array();
		$js   = '';
		$block_id = '';

		if ( ! isset( $name ) ) {
			return array();
        }

		if (isset($block['attrs']) && is_array($block['attrs'])) {
			$blockattr = $block['attrs'];
			if (isset($blockattr['block_id'])) {
				$block_id = $blockattr['block_id'];
			}
		}

		switch ($name) {

			case 'prosandcons/buttons':
				$button_fonts = ProsCons_Block_Helper::get_block_fonts( 'Button', $blockattr );
				$css+= ProsCons_Block_Helper::get_block_css( 'Button', $blockattr, $block_id );
				array_push( $block_fonts, $button_fonts );
                break;

			case 'prosandcons/verdict':
				$verdict_fonts = ProsCons_Block_Helper::get_block_fonts( 'Verdict', $blockattr );
				$css+= ProsCons_Block_Helper::get_block_css( 'Verdict', $blockattr, $block_id );
				array_push( $block_fonts, $verdict_fonts );
                break;

			case 'prosandcons/single-product':
				$verdict_fonts = ProsCons_Block_Helper::get_block_fonts( 'Single Product', $blockattr );
				$css+= ProsCons_Block_Helper::get_block_css( 'Single Product', $blockattr, $block_id );
				array_push( $block_fonts, $verdict_fonts );
                break;

			case 'prosandcons/pros-and-cons':
				$pros_cons_fonts = ProsCons_Block_Helper::get_block_fonts( 'Pros and Cons', $blockattr );
				$css+= ProsCons_Block_Helper::get_block_css( 'Pros and Cons', $blockattr, $block_id );
				array_push( $block_fonts, $pros_cons_fonts );
                break;

			case 'prosandcons/notice':
				$notice_fonts = ProsCons_Block_Helper::get_block_fonts( 'Notice', $blockattr );
				$css+= ProsCons_Block_Helper::get_block_css( 'Notice', $blockattr, $block_id );
				array_push( $block_fonts, $notice_fonts );
                break;

			case 'prosandcons/cta':
				$cta_fonts = ProsCons_Block_Helper::get_block_fonts( 'CTA', $blockattr );
				$css+= ProsCons_Block_Helper::get_block_css( 'CTA', $blockattr, $block_id );
				array_push( $block_fonts, $cta_fonts );
                break;

			case 'prosandcons/specifications':
				$specifications_fonts = ProsCons_Block_Helper::get_block_fonts( 'Specifications', $blockattr );
				$css+= ProsCons_Block_Helper::get_block_css( 'Specifications', $blockattr, $block_id );
				array_push( $block_fonts, $specifications_fonts );
                break;

			case 'prosandcons/versus-line':
				$versus_line_fonts = ProsCons_Block_Helper::get_block_fonts( 'Versus Line', $blockattr );
				$css+= ProsCons_Block_Helper::get_block_css( 'Versus Line', $blockattr, $block_id );
				array_push( $block_fonts, $versus_line_fonts );
                break;

			case 'prosandcons/product-comparison':
				$product_comparison_fonts = ProsCons_Block_Helper::get_block_fonts( 'Product Comparison', $blockattr );
				$css+= ProsCons_Block_Helper::get_block_css( 'Product Comparison', $blockattr, $block_id );
				array_push( $block_fonts, $product_comparison_fonts );
				break;

			case 'prosandcons/product-table':
				$product_tbl_fonts = ProsCons_Block_Helper::get_block_fonts( 'Product Table', $blockattr );
				$css+= ProsCons_Block_Helper::get_block_css( 'Product Table', $blockattr, $block_id );
				array_push( $block_fonts, $product_tbl_fonts );
				break;

			default:
				// Nothing to do here.
                break;
        }

		$block_fonts = apply_filters( 'prosandcons_get_block_fonts', $block_fonts, $name, $blockattr );
		$css         = apply_filters( 'prosandcons_get_block_css', $css, $name, $blockattr, $block_id );

		if ( isset( $block['innerBlocks'] ) ) {
			foreach ( $block['innerBlocks'] as $j => $inner_block ) {
				if ( 'core/block' === $inner_block['blockName'] ) {
					$id = ( isset( $inner_block['attrs']['ref'] ) ) ? $inner_block['attrs']['ref'] : 0;

					if ( $id ) {
						$content = get_post_field( 'post_content', $id );

						$reusable_blocks = $this->parse( $content );

						$assets[$j] = $this->get_blocks_fonts( $reusable_blocks );

						$assets = $this->get_assets( $reusable_blocks );

						self::$stylesheet .= $assets['css'];
						self::$script     .= $assets['js'];

                    }
				} else {
					// Get CSS for the Block.
					$inner_assets = $this->get_block_fonts( $inner_block );
					$inner_assets_css = $this->get_block_fonts( $inner_block, true );
					$inner_block_css = isset( $inner_assets_css['css'] ) ? $inner_assets_css['css'] : '';

					$css_desktop = ( isset( $css['desktop'] ) ? $css['desktop'] : '' );
					$css_tablet  = ( isset( $css['tablet'] ) ? $css['tablet'] : '' );
					$css_mobile  = ( isset( $css['mobile'] ) ? $css['mobile'] : '' );

					if ( isset( $inner_block_css['desktop'] ) ) {
						$css['desktop'] = $css_desktop . $inner_block_css['desktop'];
						$css['tablet']  = $css_tablet . $inner_block_css['tablet'];
						$css['mobile']  = $css_mobile . $inner_block_css['mobile'];
					}
				}
			}
		}

		$block_fonts_merged = array_merge( $block_fonts, $assets, $inner_assets );

		$block_fonts = is_array( $block_fonts_merged ) ? array_unique( $block_fonts_merged, SORT_REGULAR ) : [];

		if ( $css_js_only ) {
			return [
				'css' => $css
			];
		}

		return $block_fonts;
		// @codingStandardsIgnoreEnd
	}

	public function load_dynamic_google_fonts() {
		$has_dynamic_google_fonts = apply_filters(
			'prosandcons_typography_google_use_remote',
			true
		);

		if ( ! $has_dynamic_google_fonts ) {
			return;
		}

		$static = $this->get_static_fonts_ids();

		$url = $this->get_google_fonts_url(
			array_merge(
				$this->get_static_fonts_ids(),
				array()
			)
		);

		if ( ! empty( $url ) ) {
			wp_register_style( 'prosandcons-fonts-font-source-google', $url, array(), PROSCONS_VERSION, null );
			wp_enqueue_style( 'prosandcons-fonts-font-source-google' );
		}
	}

	private function get_google_fonts_url( $fonts_ids = array() ) {
		$all_fonts = $this->get_system_fonts();

		$system_fonts_families = array();

		foreach ( $all_fonts as $single_google_font ) {
			$system_fonts_families[] = $single_google_font['family'];
		}

		$to_enqueue = array();

		$default_family = get_theme_mod(
			'rootTypography',
			prosandcons_typography_default_values(
				array(
					'family'          => 'System Default',
					'variation'       => 'n4',
					'size'            => '17px',
					'line-height'     => '1.65',
					'letter-spacing'  => '0em',
					'text-transform'  => 'none',
					'text-decoration' => 'none',
				)
			)
		);

		$default_variation = $default_family['variation'];
		$default_family    = $default_family['family'];

		$all_google_fonts = $this->get_googgle_fonts( true );

		foreach ( $fonts_ids as $font_id ) {
			if ( is_array( $font_id ) ) {
				$value = $font_id;
			} else {
				$value = get_theme_mod( $font_id, null );
			}

			if ( $value && $value['family'] === 'Default' ) {
				$value['family'] = $default_family;
			}

			if ( $value && $value['variation'] === 'Default' ) {
				$value['variation'] = $default_variation;
			}

			if (
				! $value
				||
				! isset( $value['family'] )
				||
				in_array( $value['family'], $system_fonts_families, true )
				||
				$value['family'] === 'Default'
				||
				! isset( $all_google_fonts[ $value['family'] ] )
			) {
				continue;
			}

			if ( ! isset( $to_enqueue[ $value['family'] ] ) ) {
				$to_enqueue[ $value['family'] ] = array( $value['variation'] );
			} else {
				$to_enqueue[ $value['family'] ][] = $value['variation'];
			}

			$to_enqueue[ $value['family'] ] = array_unique(
				$to_enqueue[ $value['family'] ]
			);
		}

		$url = 'https://fonts.googleapis.com/css2?';

		$families = array();

		foreach ( $to_enqueue as $family => $variations ) {
			$to_push = 'family=' . $family . ':';

			$ital_vars = array();
			$wght_vars = array();

			foreach ( $variations as $variation ) {
				$var_to_push  = intval( $variation[1] ) * 100;
				$var_to_push .= $variation[0] === 'i' ? 'i' : '';

				if ( $variation[0] === 'i' ) {
					$ital_vars[] = intval( $variation[1] ) * 100;
				} else {
					$wght_vars[] = intval( $variation[1] ) * 100;
				}
			}

			sort( $ital_vars );
			sort( $wght_vars );

			$axis_tag_list = array();

			if ( count( $ital_vars ) > 0 ) {
				$axis_tag_list[] = 'ital';
			}

			if ( count( $wght_vars ) > 0 ) {
				$axis_tag_list[] = 'wght';
			}

			$to_push .= implode( ',', $axis_tag_list );
			$to_push .= '@';

			$all_vars = array();

			foreach ( $ital_vars as $ital_var ) {
				$all_vars[] = '0,' . $ital_var;
			}

			foreach ( $wght_vars as $wght_var ) {
				if ( count( $axis_tag_list ) > 1 ) {
					$all_vars[] = '1,' . $wght_var;
				} else {
					$all_vars[] = $wght_var;
				}
			}

			$to_push .= implode( ';', $all_vars );

			$families[] = $to_push;
		}

		$families = implode( '&', $families );

		if ( ! empty( $families ) ) {
			$url .= $families;
			$url .= '&display=swap';

			return $url;
		}

		return false;
	}

	public function get_system_fonts() {
		$system = array(
			'System Default',
			'Arial',
			'Verdana',
			'Trebuchet',
			'Georgia',
			'Times New Roman',
			'Palatino',
			'Helvetica',
			'Myriad Pro',
			'Lucida',
			'Gill Sans',
			'Impact',
			'Serif',
			'monospace',
		);

		$result = array();

		foreach ( $system as $font ) {
			$result[] = array(
				'source'         => 'system',
				'family'         => $font,
				'variations'     => array(),
				'all_variations' => $this->get_standard_variations_descriptors(),
			);
		}

		return $result;
	}

	public function get_standard_variations_descriptors() {
		return array(
			'n1',
			'i1',
			'n2',
			'i2',
			'n3',
			'i3',
			'n4',
			'i4',
			'n5',
			'i5',
			'n6',
			'i6',
			'n7',
			'i7',
			'n8',
			'i8',
			'n9',
			'i9',
		);
	}

	public function all_google_fonts() {
		$saved_data = get_option( 'prosandcons_google_fonts', false );
		$ttl        = 7 * DAY_IN_SECONDS;

		if (
			false === $saved_data
			||
			( ( $saved_data['last_update'] + $ttl ) < time() )
			||
			! is_array( $saved_data )
			||
			! isset( $saved_data['fonts'] )
			||
			empty( $saved_data['fonts'] )
		) {
			$response = wp_remote_get(
				plugin_dir_url( PROSCONS_PLUGIN_FILE ) . 'includes/google-fonts/google-fonts.json'
			);

			$body = wp_remote_retrieve_body( $response );

			if (
				200 === wp_remote_retrieve_response_code( $response )
				&&
				! is_wp_error( $body ) && ! empty( $body )
			) {
				update_option(
					'prosandcons_google_fonts',
					array(
						'last_update' => time(),
						'fonts'       => $body,
					),
					false
				);

				return $body;
			} else {
				if ( empty( $saved_data['fonts'] ) ) {
					$saved_data['fonts'] = wp_json_encode( array( 'items' => array() ) );
				}

				update_option(
					'prosandcons_google_fonts',
					array(
						'last_update' => time() - $ttl + MINUTE_IN_SECONDS,
						'fonts'       => $saved_data['fonts'],
					),
					false
				);
			}
		}

		return $saved_data['fonts'];
	}

	public function get_googgle_fonts( $as_keys = false ) {
		$maybe_custom_source = apply_filters_deprecated(
			'ab_typography_google_fonts_source',
			array( null ),
			'1.3.9',
			'prosandcons_typography_google_fonts_source',
			'Please use prosandcons_typography_google_fonts_source instead.'
		);

		$maybe_custom_source = apply_filters( 'prosandcons_typography_google_fonts_source', $maybe_custom_source );

		if ( $maybe_custom_source ) {
			return $maybe_custom_source;
		}

		$response = $this->all_google_fonts();
		$response = json_decode( $response, true );

		if ( ! isset( $response['items'] ) ) {
			return false;
		}

		if ( ! is_array( $response['items'] ) || ! count( $response['items'] ) ) {
			return false;
		}

		foreach ( $response['items'] as $key => $row ) {
			$response['items'][ $key ] = $this->prepare_font_data( $row );
		}

		if ( ! $as_keys ) {
			return $response['items'];
		}

		$result = array();

		foreach ( $response['items'] as $single_item ) {
			$result[ $single_item['family'] ] = true;
		}

		return $result;
	}

	private function prepare_font_data( $font ) {
		$font['source'] = 'google';

		$font['variations'] = array();

		if ( isset( $font['variants'] ) ) {
			$font['all_variations'] = $this->change_variations_structure( $font['variants'] );
		}

		unset( $font['variants'] );
		return $font;
	}

	private function change_variations_structure( $structure ) {
		$result = array();

		foreach ( $structure as $weight ) {
			$result[] = $this->get_weight_and_style_key( $weight );
		}

		return $result;
	}

	private function get_weight_and_style_key( $code ) {
		$prefix = 'n'; // Font style: italic = `i`, regular = n.
		$sufix  = '4';  // Font weight: 1 -> 9.

		$value = strtolower( trim( $code ) );
		$value = str_replace( ' ', '', $value );

		// Only number.
		if ( is_numeric( $value ) && isset( $value[0] ) ) {
			$sufix  = $value[0];
			$prefix = 'n';
		}

		// Italic.
		if ( preg_match( '#italic#', $value ) ) {
			if ( 'italic' === $value ) {
				$sufix  = 4;
				$prefix = 'i';
			} else {
				$value = trim( str_replace( 'italic', '', $value ) );
				if ( is_numeric( $value ) && isset( $value[0] ) ) {
					$sufix  = $value[0];
					$prefix = 'i';
				}
			}
		}

		// Regular.
		if ( preg_match( '#regular|normal#', $value ) ) {
			if ( 'regular' === $value ) {
				$sufix  = 4;
				$prefix = 'n';
			} else {
				$value = trim( str_replace( array( 'regular', 'normal' ), '', $value ) );
				if ( is_numeric( $value ) && isset( $value[0] ) ) {
					$sufix  = $value[0];
					$prefix = 'n';
				}
			}
		}

		return "{$prefix}{$sufix}";
	}
}

if ( ! function_exists( 'prosandcons_typography_default_values' ) ) {
	/**
	 * ProsCons typography default values.
	 *
	 * @param array $values
	 * @return array
	 */
	function prosandcons_typography_default_values( $values = array() ) { // phpcs:ignore Universal.Files.SeparateFunctionsFromOO.Mixed
		return array_merge(
			array(
				'family'          => 'Default',
				'variation'       => 'Default',
				'size'            => 'CT_CSS_SKIP_RULE',
				'line-height'     => 'CT_CSS_SKIP_RULE',
				'letter-spacing'  => 'CT_CSS_SKIP_RULE',
				'text-transform'  => 'CT_CSS_SKIP_RULE',
				'text-decoration' => 'CT_CSS_SKIP_RULE',
			),
			$values
		);
	}
}

add_action(
	'wp_ajax_prosandcons_get_fonts_list',
	function () {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_send_json_error();
		}

		$m = new AB_Fonts_Manager();

		wp_send_json_success(
			array(
				'fonts' => $m->get_all_fonts(),
			)
		);
	}
);
