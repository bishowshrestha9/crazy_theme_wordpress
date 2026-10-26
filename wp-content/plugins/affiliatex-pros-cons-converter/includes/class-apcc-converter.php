<?php
defined( 'ABSPATH' ) || exit;

class APCC_Converter {

	private static $instance = null;

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'wp_ajax_apcc_get_candidate_posts', array( $this, 'ajax_get_candidate_posts' ) );
		add_action( 'wp_ajax_apcc_process_batch', array( $this, 'ajax_process_batch' ) );
	}

	public function add_admin_menu() {
		add_management_page(
			__( 'AffiliateX Pros & Cons Converter', 'affiliatex-pros-cons-converter' ),
			__( 'AffiliateX Pros & Cons Converter', 'affiliatex-pros-cons-converter' ),
			'manage_options',
			'affiliatex-pros-cons-converter',
			array( $this, 'render_admin_page' )
		);
	}

	public function enqueue_admin_scripts( $hook ) {
		if ( false === strpos( $hook, 'affiliatex-pros-cons-converter' ) ) {
			return;
		}

		wp_enqueue_script(
			'apcc-converter-script',
			APCC_PLUGIN_URL . 'assets/js/converter.js',
			array( 'jquery' ),
			APCC_VERSION,
			true
		);

		wp_localize_script(
			'apcc-converter-script',
			'apccConverterData',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'apcc_converter_action' ),
			)
		);
	}

	public function ajax_get_candidate_posts() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Unauthorized capability.', 'affiliatex-pros-cons-converter' ) );
		}

		check_ajax_referer( 'apcc_converter_action', 'nonce' );

		global $wpdb;

		$post_types = isset( $_POST['post_types'] ) && is_array( $_POST['post_types'] ) ? array_map( 'sanitize_key', $_POST['post_types'] ) : array( 'post', 'page' );

		if ( empty( $post_types ) ) {
			wp_send_json_error( __( 'No post types selected.', 'affiliatex-pros-cons-converter' ) );
		}

		$in_clause  = implode( "', '", array_map( 'esc_sql', $post_types ) );
		$like_affx  = '%' . $wpdb->esc_like( 'affiliatex' ) . '%';
		$like_affx2 = '%' . $wpdb->esc_like( 'affx' ) . '%';

		$sql = "SELECT ID FROM {$wpdb->posts} WHERE (post_content LIKE %s OR post_content LIKE %s) AND post_status = 'publish' AND post_type IN ('{$in_clause}') ORDER BY ID DESC";
		$post_ids = $wpdb->get_col( $wpdb->prepare( $sql, $like_affx, $like_affx2 ) );

		wp_send_json_success( array( 'post_ids' => array_map( 'absint', $post_ids ) ) );
	}

	public function ajax_process_batch() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( __( 'Unauthorized capability.', 'affiliatex-pros-cons-converter' ) );
		}

		check_ajax_referer( 'apcc_converter_action', 'nonce' );

		$raw_post_ids = isset( $_POST['post_ids'] ) && is_array( $_POST['post_ids'] ) ? array_map( 'absint', $_POST['post_ids'] ) : array();
		$post_ids     = array_slice( array_filter( $raw_post_ids ), 0, 50 );
		$dry_run      = ! empty( $_POST['dry_run'] );

		if ( empty( $post_ids ) ) {
			wp_send_json_success( array(
				'converted_posts'  => 0,
				'converted_blocks' => 0,
				'log_items'        => '',
			) );
		}

		$allowed_types    = get_post_types( array( 'public' => true ) );
		$converted_posts  = 0;
		$converted_blocks = 0;
		$log_items        = '';

		foreach ( $post_ids as $post_id ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				continue;
			}

			$post = get_post( $post_id );
			if ( ! $post || 'publish' !== $post->post_status || ! in_array( $post->post_type, $allowed_types, true ) ) {
				continue;
			}

			$blocks         = parse_blocks( $post->post_content );
			$has_changes    = false;
			$blocks_in_post = 0;
			$item_logs      = '';

			$updated_blocks = $this->migrate_notice_blocks( $blocks, $has_changes, $blocks_in_post, $dry_run, $item_logs );

			if ( $has_changes ) {
				$converted_posts++;
				$converted_blocks += $blocks_in_post;

				$edit_link  = esc_url( get_edit_post_link( $post_id ) );
				$post_title = esc_html( $post->post_title );

				if ( ! $dry_run ) {
					$new_content = serialize_blocks( $updated_blocks );
					wp_update_post( array(
						'ID'           => $post_id,
						'post_content' => wp_slash( $new_content ),
					) );
					$log_items .= '<li style="margin-bottom: 12px; padding: 10px 14px; background: #fff; border-left: 4px solid #10b981; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-radius: 4px;"><strong>Converted:</strong> <a href="' . $edit_link . '" target="_blank">' . $post_title . '</a> (' . absint( $blocks_in_post ) . ' block(s) migrated)</li>';
				} else {
					$log_items .= '<li style="margin-bottom: 12px; padding: 10px 14px; background: #fff; border-left: 4px solid #3b82f6; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-radius: 4px;"><strong>Found Match:</strong> <a href="' . $edit_link . '" target="_blank">' . $post_title . '</a> (' . absint( $blocks_in_post ) . ' block(s) to migrate)' . $item_logs . '</li>';
				}
			}
		}

		wp_send_json_success( array(
			'converted_posts'  => $converted_posts,
			'converted_blocks' => $converted_blocks,
			'log_items'        => $log_items,
		) );
	}

	private function is_affiliatex_pros_cons_block( $block_name ) {
		if ( empty( $block_name ) || ! is_string( $block_name ) ) {
			return false;
		}

		$block_name = strtolower( trim( $block_name ) );

		// Child elements to exclude from standalone block migration
		$child_elements = array(
			'affiliatex/pros',
			'affiliatex/cons',
			'affiliatex/pros-title',
			'affiliatex/cons-title',
			'affiliatex/pros-content',
			'affiliatex/cons-content',
			'affiliatex/pros-item',
			'affiliatex/cons-item',
			'affx/pros',
			'affx/cons',
			'affx/pros-title',
			'affx/cons-title',
		);

		if ( in_array( $block_name, $child_elements, true ) ) {
			return false;
		}

		$targets = array(
			'affiliatex/pros-cons',
			'affiliatex/pros-and-cons',
			'affiliatex/proscons',
			'affiliatex/pros-cons-block',
			'affiliatex/pros-cons-box',
			'affx/pros-cons',
			'affx/pros-and-cons',
			'affx/proscons',
		);

		if ( in_array( $block_name, $targets, true ) ) {
			return true;
		}

		return ( strpos( $block_name, 'affiliatex/' ) === 0 || strpos( $block_name, 'affx/' ) === 0 ) && ( strpos( $block_name, 'pros' ) !== false || strpos( $block_name, 'cons' ) !== false );
	}

	private function migrate_notice_blocks( $blocks, &$has_changes, &$blocks_in_post, $dry_run, &$item_logs = '' ) {
		if ( ! is_array( $blocks ) ) {
			return $blocks;
		}

		foreach ( $blocks as &$block ) {
			if ( isset( $block['blockName'] ) && $this->is_affiliatex_pros_cons_block( $block['blockName'] ) ) {
				$has_changes = true;
				$blocks_in_post++;

				$attrs = isset( $block['attrs'] ) && is_array( $block['attrs'] ) ? $block['attrs'] : array();

				$pros_items = $this->extract_list_items( $attrs, $block, 'pros' );
				$cons_items = $this->extract_list_items( $attrs, $block, 'cons' );

				$pros_title = ! empty( $attrs['prosTitle'] ) ? $this->clean_text( $attrs['prosTitle'] ) : ( ! empty( $attrs['pros_title'] ) ? $this->clean_text( $attrs['pros_title'] ) : 'Pros' );
				$cons_title = ! empty( $attrs['consTitle'] ) ? $this->clean_text( $attrs['consTitle'] ) : ( ! empty( $attrs['cons_title'] ) ? $this->clean_text( $attrs['cons_title'] ) : 'Cons' );

				$pros_bg_color     = ! empty( $attrs['prosBgColor'] ) ? $attrs['prosBgColor'] : '#24B644';
				$cons_bg_color     = ! empty( $attrs['consBgColor'] ) ? $attrs['consBgColor'] : '#F13A3A';
				$pros_text_color   = ! empty( $attrs['prosTextColor'] ) ? $attrs['prosTextColor'] : '#ffffff';
				$cons_text_color   = ! empty( $attrs['consTextColor'] ) ? $attrs['consTextColor'] : '#ffffff';
				$pros_list_bg      = ! empty( $attrs['prosListBgColor'] ) ? $attrs['prosListBgColor'] : '#F5FFF8';
				$cons_list_bg      = ! empty( $attrs['consListBgColor'] ) ? $attrs['consListBgColor'] : '#FFF5F5';
				$pros_icon_color   = ! empty( $attrs['prosIconColor'] ) ? $attrs['prosIconColor'] : '#24B644';
				$cons_icon_color   = ! empty( $attrs['consIconColor'] ) ? $attrs['consIconColor'] : '#F13A3A';
				$layout_style      = ! empty( $attrs['layoutStyle'] ) ? $attrs['layoutStyle'] : 'layout-type-1';

				if ( $dry_run ) {
					$item_logs .= '<div style="margin: 8px 0 15px 15px; padding: 12px 16px; background: #f8fafc; border: 1px dashed #3b82f6; border-radius: 6px; font-size: 13px;">';
					$item_logs .= '<strong style="color: #0f172a;">Matched Block:</strong> <code>' . esc_html( $block['blockName'] ) . '</code><br>';
					$item_logs .= '<strong>Pros (' . count( $pros_items ) . '):</strong>';
					if ( empty( $pros_items ) ) {
						$item_logs .= ' <em>None found</em>';
					} else {
						$item_logs .= '<ul style="margin: 4px 0 6px 20px; list-style-type: disc; color: #166534;">';
						foreach ( $pros_items as $pi ) {
							$item_logs .= '<li>' . esc_html( $pi['content'] ) . '</li>';
						}
						$item_logs .= '</ul>';
					}
					$item_logs .= '<strong>Cons (' . count( $cons_items ) . '):</strong>';
					if ( empty( $cons_items ) ) {
						$item_logs .= ' <em>None found</em>';
					} else {
						$item_logs .= '<ul style="margin: 4px 0 4px 20px; list-style-type: disc; color: #991b1b;">';
						foreach ( $cons_items as $ci ) {
							$item_logs .= '<li>' . esc_html( $ci['content'] ) . '</li>';
						}
						$item_logs .= '</ul>';
					}
					$item_logs .= '</div>';
				}

				$new_attrs = array(
					'block_id'          => 'block-' . wp_generate_uuid4(),
					'layoutStyle'       => $layout_style,
					'prosIconStatus'    => true,
					'consIconStatus'    => true,
					'prosTitle'         => $pros_title,
					'consTitle'         => $cons_title,
					'prosContentType'   => 'list',
					'consContentType'   => 'list',
					'prosListType'      => 'unordered',
					'prosUnorderedType' => 'icon',
					'consListType'      => 'unordered',
					'consUnorderedType' => 'icon',
					'prosIconClass'     => 'far fa-circle-check',
					'consIconClass'     => 'far fa-circle-xmark',
					'prosBgColor'       => $pros_bg_color,
					'consBgColor'       => $cons_bg_color,
					'prosTextColor'     => $pros_text_color,
					'consTextColor'     => $cons_text_color,
					'prosListBgColor'   => $pros_list_bg,
					'consListBgColor'   => $cons_list_bg,
					'prosIconColor'     => $pros_icon_color,
					'consIconColor'     => $cons_icon_color,
					'prosListItems'     => $pros_items,
					'consListItems'     => $cons_items,
				);

				$block['blockName']    = 'prosandcons/pros-and-cons';
				$block['attrs']        = $new_attrs;
				$block['innerHTML']    = '';
				$block['innerContent'] = array();
				$block['innerBlocks']  = array();
			} else {
				if ( isset( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
					$block['innerBlocks'] = $this->migrate_notice_blocks( $block['innerBlocks'], $has_changes, $blocks_in_post, $dry_run, $item_logs );
				}
			}
		}

		return $blocks;
	}

	private function clean_text( $text ) {
		if ( empty( $text ) || ! is_string( $text ) ) {
			return '';
		}

		$text = preg_replace( '/\\\?u003c/i', '<', $text );
		$text = preg_replace( '/\\\?u003e/i', '>', $text );
		$text = preg_replace( '/\\\?u0022/i', '"', $text );
		$text = preg_replace( '/\\\?u0027/i', "'", $text );
		$text = preg_replace( '/\\\?u0026/i', '&', $text );

		$text = str_replace( array( '&nbsp;', '&#160;', "\xc2\xa0", 'u0026nbsp;' ), ' ', $text );
		$text = html_entity_decode( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );

		// Strip Table of Contents plugin tags
		$text = preg_replace( '/<span[^>]*class="[^"]*(?:ez-toc|lwptoc|toc)[^"]*"[^>]*>.*?<\/span>/is', '', $text );
		$text = preg_replace( '/<div[^>]*class="[^"]*(?:ez-toc|lwptoc|toc)[^"]*"[^>]*>.*?<\/div>/is', '', $text );

		// Convert raw link tags to plain text
		$text = preg_replace( '/<a\s+[^>]*?>(.*?)<\/a>/is', '$1', $text );

		// Strip remaining HTML tags except basic formatting
		$text = strip_tags( $text, '<strong><em><b><i><code>' );
		$text = preg_replace( '/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $text );
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

	private function extract_text_from_react_children( $children ) {
		if ( empty( $children ) ) {
			return '';
		}

		if ( is_string( $children ) ) {
			return $children;
		}

		if ( is_array( $children ) ) {
			$out = '';
			foreach ( $children as $child ) {
				if ( is_string( $child ) ) {
					$out .= $child;
				} elseif ( is_array( $child ) ) {
					if ( isset( $child['props']['children'] ) ) {
						$out .= $this->extract_text_from_react_children( $child['props']['children'] );
					} elseif ( isset( $child['props'] ) && is_string( $child['props'] ) ) {
						$out .= $child['props'];
					}
				}
			}
			return $out;
		}

		return '';
	}

	private function extract_list_items( $attrs, $block, $type = 'pros' ) {
		$raw_items = array();

		$keys = 'pros' === $type
			? array( 'prosListItems', 'pros_list_items', 'prosItems', 'pros', 'prosList', 'prosContent' )
			: array( 'consListItems', 'cons_list_items', 'consItems', 'cons', 'consList', 'consContent' );

		foreach ( $keys as $key ) {
			if ( isset( $attrs[ $key ] ) ) {
				$val = $attrs[ $key ];
				if ( is_array( $val ) && ! empty( $val ) ) {
					foreach ( $val as $v ) {
						$raw_items[] = $v;
					}
				} elseif ( is_string( $val ) && '' !== trim( $val ) ) {
					$decoded = json_decode( $val, true );
					if ( is_array( $decoded ) && ! empty( $decoded ) ) {
						foreach ( $decoded as $v ) {
							$raw_items[] = $v;
						}
					} else {
						if ( stripos( $val, '<li>' ) !== false ) {
							if ( preg_match_all( '/<li[^>]*>(.*?)<\/li>/is', $val, $m ) ) {
								foreach ( $m[1] as $item_html ) {
									$raw_items[] = $item_html;
								}
							}
						} else {
							$lines = array_filter( array_map( 'trim', explode( "\n", $val ) ) );
							foreach ( $lines as $line ) {
								$raw_items[] = $line;
							}
						}
					}
				}
			}
		}

		if ( empty( $raw_items ) ) {
			$html = ! empty( $block['innerHTML'] ) ? $block['innerHTML'] : '';
			if ( ! empty( $block['innerContent'] ) && is_array( $block['innerContent'] ) ) {
				$html .= implode( "\n", array_filter( $block['innerContent'], 'is_string' ) );
			}

			if ( ! empty( $html ) ) {
				$pattern = 'pros' === $type
					? '/<div[^>]*class="[^"]*(?:pros|pro|advantage)[^"]*"[^>]*>(.*?)<\/div>/is'
					: '/<div[^>]*class="[^"]*(?:cons|con|disadvantage)[^"]*"[^>]*>(.*?)<\/div>/is';

				if ( preg_match( $pattern, $html, $section ) ) {
					if ( preg_match_all( '/<li[^>]*>(.*?)<\/li>/is', $section[1], $m ) ) {
						foreach ( $m[1] as $li ) {
							$raw_items[] = $li;
						}
					}
				} else {
					if ( preg_match_all( '/<li[^>]*>(.*?)<\/li>/is', $html, $m ) ) {
						foreach ( $m[1] as $li ) {
							$raw_items[] = $li;
						}
					}
				}
			}
		}

		$formatted = array();
		foreach ( $raw_items as $item ) {
			$text = '';
			if ( is_array( $item ) ) {
				if ( isset( $item['props'] ) ) {
					$text = $this->extract_text_from_react_children( isset( $item['props']['children'] ) ? $item['props']['children'] : $item['props'] );
				}
				if ( empty( $text ) ) {
					foreach ( array( 'content', 'text', 'item', 'title', 'value', 'label', 'description', 'list' ) as $k ) {
						if ( ! empty( $item[ $k ] ) && is_string( $item[ $k ] ) ) {
							$text = (string) $item[ $k ];
							break;
						}
					}
				}
			} elseif ( is_string( $item ) ) {
				$text = $item;
			}

			$text = trim( $text );
			$text = preg_replace( '/^<li[^>]*>(.*?)<\/li>$/is', '$1', $text );
			$text = preg_replace( '/^<p[^>]*>(.*?)<\/p>$/is', '$1', $text );
			$text = preg_replace( '/<i[^>]*>.*?<\/i>/is', '', $text );
			$text = preg_replace( '/<svg[^>]*>.*?<\/svg>/is', '', $text );

			$text = $this->clean_text( $text );

			if ( $this->is_cta_item( $text ) ) {
				continue;
			}

			if ( '' !== $text ) {
				$formatted[] = array( 'content' => $text );
			}
		}

		return $formatted;
	}

	public function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Unauthorized capability.', 'affiliatex-pros-cons-converter' ) );
		}

		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		?>
		<div class="wrap" style="max-width: 1100px; margin: 25px auto; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
			<div style="background: #ffffff; padding: 28px 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06); border: 1px solid #e2e8f0;">
				<div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 24px;">
					<div>
						<h1 style="margin: 0 0 6px 0; font-size: 26px; font-weight: 700; color: #0f172a;">⚡ AffiliateX Pros & Cons Converter</h1>
						<p style="margin: 0; color: #64748b; font-size: 14px;">Bulk convert legacy <strong>AffiliateX Pros & Cons</strong> blocks into the <strong>Pros and Cons 4</strong> block format across your site.</p>
					</div>
					<span style="background: #e0e7ff; color: #4338ca; padding: 6px 14px; border-radius: 20px; font-weight: 600; font-size: 13px;">v1.0.0</span>
				</div>

				<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 28px;">
					<div style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0;">
						<h3 style="margin-top: 0; color: #1e293b; font-size: 16px;">1. Select Post Types to Scan</h3>
						<div style="display: flex; gap: 16px; margin-bottom: 12px;">
							<?php foreach ( $post_types as $type ) : ?>
								<label style="font-weight: 500; cursor: pointer;">
									<input type="checkbox" class="apcc-post-type" value="<?php echo esc_attr( $type->name ); ?>" <?php checked( in_array( $type->name, array( 'post', 'page' ), true ) ); ?>>
									<?php echo esc_html( $type->label ); ?>
								</label>
							<?php endforeach; ?>
						</div>
						<p style="font-size: 12px; color: #64748b; margin: 0;">Scanning looks exclusively for <code>affiliatex/pros-cons</code> and <code>affx/pros-cons</code> blocks in published content.</p>
					</div>

					<div style="background: #f8fafc; padding: 20px; border-radius: 8px; border: 1px solid #e2e8f0; display: flex; flex-direction: column; justify-content: center; gap: 12px;">
						<div style="display: flex; gap: 12px;">
							<button id="btn-apcc-dry-run" class="button button-secondary button-hero" style="flex: 1; border-color: #3b82f6; color: #2563eb;">🔍 Perform Dry Run Scan</button>
							<button id="btn-apcc-convert" class="button button-primary button-hero" style="flex: 1; background: #10b981; border-color: #059669;">🚀 Convert All Blocks Now</button>
						</div>
					</div>
				</div>

				<div id="apcc-progress-container" style="display: none; margin-bottom: 24px;">
					<div style="display: flex; justify-content: space-between; margin-bottom: 6px; font-weight: 600; font-size: 14px; color: #334155;">
						<span id="apcc-progress-status">Processing posts...</span>
						<span id="apcc-progress-percent">0%</span>
					</div>
					<div style="width: 100%; height: 12px; background: #e2e8f0; border-radius: 6px; overflow: hidden;">
						<div id="apcc-progress-bar" style="width: 0%; height: 100%; background: #10b981; transition: width 0.3s ease;"></div>
					</div>
				</div>

				<div style="background: #0f172a; border-radius: 8px; padding: 20px; color: #f8fafc;">
					<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; border-bottom: 1px solid #334155; padding-bottom: 10px;">
						<h3 style="margin: 0; color: #38bdf8; font-size: 15px;">📊 Scan & Conversion Results</h3>
						<span id="apcc-stats" style="font-size: 13px; color: #94a3b8;">Ready to scan.</span>
					</div>
					<ul id="apcc-log-list" style="margin: 0; padding: 0; list-style: none; max-height: 400px; overflow-y: auto; font-family: monospace; font-size: 13px;">
						<li style="color: #64748b; padding: 12px 0;">Click "Perform Dry Run Scan" to preview matching posts, or "Convert All Blocks Now" to replace blocks.</li>
					</ul>
				</div>
			</div>
		</div>
		<?php
	}
}
