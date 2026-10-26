<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RSN_Block {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
	}

	public function enqueue_frontend_assets() {
		wp_enqueue_style(
			'rsn-notice-style',
			RSN_PLUGIN_URL . 'assets/css/notice-style.css',
			array(),
			RSN_VERSION
		);
	}

	public function enqueue_editor_assets() {
		wp_enqueue_style(
			'rsn-notice-editor-style',
			RSN_PLUGIN_URL . 'assets/css/notice-style.css',
			array(),
			RSN_VERSION
		);
	}

	public function register_block() {
		wp_register_script(
			'rsn-notice-block-script',
			RSN_PLUGIN_URL . 'assets/js/notice-block.js',
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
			RSN_VERSION,
			true
		);

		register_block_type( 'review-summary-notice/notice-card', array(
			'editor_script'   => 'rsn-notice-block-script',
			'editor_style'    => 'rsn-notice-editor-style',
			'style'           => 'rsn-notice-style',
			'render_callback' => array( $this, 'render_notice_block' ),
			'attributes'      => array(
				'blockId' => array(
					'type'    => 'string',
					'default' => '',
				),
				'title' => array(
					'type'    => 'string',
					'default' => 'OptiCycle Review Summary',
				),
				'items' => array(
					'type'    => 'array',
					'default' => array(
						array( 'content' => 'Customer experiences highlight quick air freshening, quiet operation, and allergy relief.' ),
						array( 'content' => 'Our experts confirm OptiCycle Air Cleaner effectively purifies small spaces.' ),
						array( 'content' => 'Overall Rating: 4.2/5 a worthwhile buy for personal use.' ),
					),
				),
				'headerBgColor' => array(
					'type'    => 'string',
					'default' => '#5ea3e1',
				),
				'headerTextColor' => array(
					'type'    => 'string',
					'default' => '#ffffff',
				),
				'iconColor' => array(
					'type'    => 'string',
					'default' => '#5ea3e1',
				),
				'borderColor' => array(
					'type'    => 'string',
					'default' => '#e1ebf5',
				),
				'cardBgColor' => array(
					'type'    => 'string',
					'default' => '#ffffff',
				),
				'textColor' => array(
					'type'    => 'string',
					'default' => '#333333',
				),
				'showIcon' => array(
					'type'    => 'boolean',
					'default' => true,
				),
			),
		) );
	}

	private function sanitize_color( $color, $default = '' ) {
		if ( empty( $color ) || ! is_string( $color ) ) {
			return $default;
		}

		$color = trim( $color );

		if ( function_exists( 'sanitize_hex_color' ) ) {
			$hex = sanitize_hex_color( $color );
		} else {
			$hex = preg_match( '/^#([a-f0-9]{3}){1,2}$/i', $color ) ? $color : '';
		}

		if ( ! empty( $hex ) ) {
			return $hex;
		}

		if ( preg_match( '/^rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*(?:,\s*(?:0|1|0?\.\d+)\s*)?\)$/i', $color ) ) {
			return $color;
		}
		if ( preg_match( '/^hsla?\(\s*\d+\s*,\s*\d+%\s*,\s*\d+%\s*(?:,\s*(?:0|1|0?\.\d+)\s*)?\)$/i', $color ) ) {
			return $color;
		}

		return $default;
	}

	private function get_allowed_html() {
		return array(
			'strong' => array(),
			'em'     => array(),
			'b'      => array(),
			'i'      => array(),
			'span'   => array( 'class' => array() ),
			'code'   => array(),
			'a'      => array(
				'href'   => array(),
				'title'  => array(),
				'target' => array(),
				'rel'    => array(),
			),
		);
	}

	private function sanitize_allowed_html( $content ) {
		$clean = wp_kses( $content, $this->get_allowed_html() );
		$clean = preg_replace_callback( '/<a\s+([^>]*?)>/i', function( $matches ) {
			$attrs = $matches[1];
			if ( preg_match( '/rel=["\']([^"\']*)["\']/i', $attrs, $rel_match ) ) {
				$rel_tokens = array_filter( array_map( 'trim', explode( ' ', strtolower( $rel_match[1] ) ) ) );
				$required   = array( 'nofollow', 'ugc', 'noopener' );
				$merged     = array_unique( array_merge( $rel_tokens, $required ) );
				$attrs      = preg_replace( '/rel=["\']([^"\']*)["\']/i', 'rel="' . esc_attr( implode( ' ', $merged ) ) . '"', $attrs );
			} else {
				$attrs .= ' rel="nofollow ugc noopener"';
			}
			return '<a ' . trim( $attrs ) . '>';
		}, $clean );

		return $clean;
	}

	private function clean_extracted_text( $text ) {
		if ( empty( $text ) || ! is_string( $text ) ) {
			return '';
		}

		// 1. Convert any raw / escaped unicode hex entities (u003c, u003e, u0022, u0027, u0026, u0026nbsp;)
		$text = preg_replace( '/\\\?u003c/i', '<', $text );
		$text = preg_replace( '/\\\?u003e/i', '>', $text );
		$text = preg_replace( '/\\\?u0022/i', '"', $text );
		$text = preg_replace( '/\\\?u0027/i', "'", $text );
		$text = preg_replace( '/\\\?u0026/i', '&', $text );

		// 2. Normalize non-breaking space entity variants to standard space
		$text = str_replace( array( '&nbsp;', '&#160;', "\xc2\xa0", 'u0026nbsp;' ), ' ', $text );

		// 3. Decode HTML entities safely
		$text = html_entity_decode( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );

		// 3.5. Strip Table of Contents plugin tags (ez-toc, lwptoc, cm-toc, toc-container)
		$text = preg_replace( '/<span[^>]*class="[^"]*(?:ez-toc|lwptoc|toc)[^"]*"[^>]*>.*?<\/span>/is', '', $text );
		$text = preg_replace( '/<div[^>]*class="[^"]*(?:ez-toc|lwptoc|toc)[^"]*"[^>]*>.*?<\/div>/is', '', $text );

		// 4. Convert raw link tags and exposed internal link markup into clean plain text
		$text = preg_replace( '/<a\s+[^>]*?>(.*?)<\/a>/is', '$1', $text );

		// 5. Strip all remaining HTML tags except basic inline formatting (strong, em, b, i, code)
		$text = strip_tags( $text, '<strong><em><b><i><code>' );

		// 6. Remove zero-width spaces and control characters
		$text = preg_replace( '/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $text );

		// 7. Clean up extra spacing and wrapping quotes
		$text = preg_replace( '/[ \t]+/', ' ', $text );
		$text = trim( preg_replace( '/^["\']\s*(.*?)\s*["\']$/s', '$1', trim( $text ) ) );

		return trim( $text );
	}

	private function is_cta_item( $text ) {
		if ( empty( $text ) || ! is_string( $text ) ) {
			return false;
		}

		$plain_text = strtolower( trim( wp_strip_all_tags( $text ) ) );

		$cta_phrases = array(
			'click here',
			'check the latest price',
			'check latest price',
			'official website',
			'check price',
			'buy now',
			'order now',
			'get started',
			'visit official',
			'discount',
			'special offer',
		);

		foreach ( $cta_phrases as $phrase ) {
			if ( strpos( $plain_text, $phrase ) !== false ) {
				return true;
			}
		}

		return false;
	}

	public function render_notice_block( $attributes, $content = '' ) {
		$raw_title = ! empty( $attributes['title'] ) ? $attributes['title'] : 'OptiCycle Review Summary';
		$title     = $this->clean_extracted_text( $raw_title );

		$items = array();
		if ( ! empty( $attributes['items'] ) && is_array( $attributes['items'] ) ) {
			foreach ( $attributes['items'] as $item ) {
				$text = '';
				if ( is_array( $item ) && isset( $item['content'] ) ) {
					$text = (string) $item['content'];
				} elseif ( is_string( $item ) ) {
					$text = $item;
				}

				$clean_item = $this->clean_extracted_text( $text );
				if ( '' !== $clean_item && ! $this->is_cta_item( $clean_item ) ) {
					$items[] = $clean_item;
				}
			}
		}

		if ( empty( $items ) && ! empty( $content ) ) {
			if ( preg_match_all( '/<li[^>]*>(.*?)<\/li>/is', $content, $matches ) ) {
				foreach ( $matches[1] as $m ) {
					$clean_item = $this->clean_extracted_text( $m );
					if ( '' !== $clean_item ) {
						$items[] = $clean_item;
					}
				}
			} else {
				$clean = trim( strip_tags( $content, '<strong><em><a><span>' ) );
				if ( ! empty( $clean ) ) {
					$lines = array_filter( array_map( 'trim', explode( "\n", $clean ) ) );
					foreach ( $lines as $line ) {
						$clean_item = $this->clean_extracted_text( $line );
						if ( '' !== $clean_item ) {
							$items[] = $clean_item;
						}
					}
				}
			}
		}

		$raw_block_id      = ! empty( $attributes['blockId'] ) ? $attributes['blockId'] : '';
		$clean_block_id    = preg_replace( '/[^a-zA-Z0-9_-]/', '', $raw_block_id );
		$block_id          = ! empty( $clean_block_id ) ? $clean_block_id : 'rsn-' . wp_generate_uuid4();

		$header_bg_color   = $this->sanitize_color( isset( $attributes['headerBgColor'] ) ? $attributes['headerBgColor'] : '', '#5ea3e1' );
		$header_text_color = $this->sanitize_color( isset( $attributes['headerTextColor'] ) ? $attributes['headerTextColor'] : '', '#ffffff' );
		$icon_color        = $this->sanitize_color( isset( $attributes['iconColor'] ) ? $attributes['iconColor'] : '', '#5ea3e1' );
		$border_color      = $this->sanitize_color( isset( $attributes['borderColor'] ) ? $attributes['borderColor'] : '', '#e1ebf5' );
		$card_bg_color     = $this->sanitize_color( isset( $attributes['cardBgColor'] ) ? $attributes['cardBgColor'] : '', '#ffffff' );
		$text_color        = $this->sanitize_color( isset( $attributes['textColor'] ) ? $attributes['textColor'] : '', '#333333' );
		$show_icon         = isset( $attributes['showIcon'] ) ? (bool) $attributes['showIcon'] : true;

		$wrapper_styles = sprintf(
			'border-color: %s; background-color: %s; color: %s;',
			esc_attr( $border_color ),
			esc_attr( $card_bg_color ),
			esc_attr( $text_color )
		);

		$header_styles = sprintf(
			'background-color: %s; color: %s;',
			esc_attr( $header_bg_color ),
			esc_attr( $header_text_color )
		);

		$icon_styles = sprintf(
			'color: %s;',
			esc_attr( $icon_color )
		);

		ob_start();
		?>
		<div class="rsn-notice-wrapper ez-toc-disabled no-toc lwptoc-disable ez-toc-ignore cm-toc-ignore" id="<?php echo esc_attr( $block_id ); ?>" style="<?php echo esc_attr( $wrapper_styles ); ?>" data-no-toc="true">
			<div class="rsn-notice-header" style="<?php echo esc_attr( $header_styles ); ?>">
				<?php if ( $show_icon ) : ?>
					<span class="rsn-header-icon">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
							<line x1="12" y1="16" x2="12" y2="12"></line>
							<line x1="12" y1="8" x2="12.01" y2="8"></line>
						</svg>
					</span>
				<?php endif; ?>
				<div class="rsn-header-title" style="color: <?php echo esc_attr( $header_text_color ); ?>;"><?php echo $this->sanitize_allowed_html( $title ); ?></div>
			</div>

			<div class="rsn-notice-body">
				<?php if ( ! empty( $items ) ) : ?>
					<ul class="rsn-notice-list">
						<?php foreach ( $items as $item_text ) : ?>
							<li class="rsn-notice-item">
								<span class="rsn-item-icon" style="<?php echo esc_attr( $icon_styles ); ?>">
									<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
										<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
									</svg>
								</span>
								<span class="rsn-item-text"><?php echo $this->sanitize_allowed_html( $item_text ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
