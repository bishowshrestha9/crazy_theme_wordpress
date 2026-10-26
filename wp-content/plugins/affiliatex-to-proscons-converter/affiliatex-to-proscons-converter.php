<?php
/**
 * Plugin Name: AffiliateX to ProsCons Converter
 * Description: Scans post content for AffiliateX Pros and Cons blocks and converts them to ProsCons Pros and Cons blocks (pros-and-cons 2).
 * Version: 1.1.0
 * Author: Antigravity
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AffiliateX_To_ProsCons_Converter {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	public function add_admin_menu() {
		add_submenu_page(
			'tools.php',
			'AffiliateX Converter',
			'AffiliateX Converter',
			'manage_options',
			'affiliatex-to-proscons',
			array( $this, 'render_admin_page' )
		);
	}

	public function render_admin_page() {
		?>
		<div class="wrap">
			<h1>AffiliateX to ProsCons Converter</h1>

			<div class="card" style="max-width: 850px; padding: 25px; margin-top: 20px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background: #fff;">
				<h2 style="margin-top:0;">Scan & Exchange Blocks</h2>
				<p>This tool scans all published posts and pages for any <strong>AffiliateX Pros and Cons</strong> block (e.g. <code>affiliatex/pros-and-cons</code>, <code>affiliatex/pros-cons</code>) and replaces them with the custom <strong>Pros and Cons Block</strong> (<code>prosandcons/pros-and-cons</code>).</p>
				
				<div style="background: #f0f6fc; padding: 15px 20px; border-left: 4px solid #2271b1; margin: 15px 0; border-radius: 4px;">
					<strong>Recommended Workflow:</strong> Run a <strong>Dry Run Scan</strong> first to preview matching posts and inspect the extracted Pros &amp; Cons items. Once verified, click <strong>Convert All Blocks Now</strong>.
				</div>

				<form method="post" style="margin-top: 20px;">
					<?php wp_nonce_field( 'ax_to_pc_action', 'ax_to_pc_nonce' ); ?>
					<input type="submit" name="ax_to_pc_dry_run" class="button button-secondary button-large" value="Perform Dry Run Scan" style="margin-right: 12px;">
					<input type="submit" name="ax_to_pc_convert" class="button button-primary button-large" value="Convert All Blocks Now" onclick="return confirm('Are you sure you want to convert all matching AffiliateX blocks? This will update post content in the database.');">
				</form>
			</div>

			<?php
			if ( isset( $_POST['ax_to_pc_dry_run'] ) && check_admin_referer( 'ax_to_pc_action', 'ax_to_pc_nonce' ) ) {
				$this->handle_processing( true );
			} elseif ( isset( $_POST['ax_to_pc_convert'] ) && check_admin_referer( 'ax_to_pc_action', 'ax_to_pc_nonce' ) ) {
				$this->handle_processing( false );
			}
			?>
		</div>
		<?php
	}

	private function handle_processing( $dry_run = true ) {
		global $wpdb;
		$like_query = '%affiliatex/%';
		
		// Query published posts and pages containing affiliatex blocks
		$post_ids = $wpdb->get_col( $wpdb->prepare(
			"SELECT ID FROM {$wpdb->posts} WHERE post_content LIKE %s AND post_status = 'publish' AND post_type IN ('post', 'page')",
			$like_query
		) );

		echo '<div style="margin-top: 25px; background: #fff; padding: 25px; border: 1px solid #ccd0d4; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); max-width: 850px;">';
		echo '<h2>' . ( $dry_run ? 'Dry Run Scan Results' : 'Conversion Results' ) . '</h2>';
		
		if ( empty( $post_ids ) ) {
			echo '<p>No published posts or pages containing AffiliateX blocks were found.</p>';
			echo '</div>';
			return;
		}

		echo '<p>Found <strong>' . count( $post_ids ) . '</strong> post(s) containing candidate AffiliateX blocks.</p>';
		echo '<ol style="margin-left: 20px;">';

		$converted_posts = 0;
		$converted_blocks = 0;

		foreach ( $post_ids as $post_id ) {
			$post = get_post( $post_id );
			if ( ! $post ) {
				continue;
			}

			$blocks = parse_blocks( $post->post_content );
			$has_changes = false;
			$blocks_in_post = 0;

			$updated_blocks = $this->migrate_blocks( $blocks, $has_changes, $blocks_in_post, $dry_run );

			if ( $has_changes ) {
				$converted_posts++;
				$converted_blocks += $blocks_in_post;

				if ( ! $dry_run ) {
					$new_content = serialize_blocks( $updated_blocks );
					wp_update_post( array(
						'ID'           => $post_id,
						'post_content' => $new_content,
					) );
					echo '<li><strong>Converted:</strong> <a href="' . esc_url( get_edit_post_link( $post_id ) ) . '" target="_blank">' . esc_html( $post->post_title ) . '</a> (' . $blocks_in_post . ' block(s) migrated)</li>';
				} else {
					echo '<li><strong>Found Match:</strong> <a href="' . esc_url( get_edit_post_link( $post_id ) ) . '" target="_blank">' . esc_html( $post->post_title ) . '</a> (' . $blocks_in_post . ' block(s) to migrate)</li>';
				}
			}
		}

		echo '</ol>';
		echo '<p style="font-size: 16px; margin-top: 20px;"><strong>Summary:</strong> ' . ( $dry_run ? 'Would convert ' : 'Successfully converted ' ) . $converted_blocks . ' block(s) across ' . $converted_posts . ' post(s).</p>';
		echo '</div>';
	}

	private function is_affiliatex_pros_cons_block( $block_name ) {
		if ( empty( $block_name ) || ! is_string( $block_name ) ) {
			return false;
		}

		$block_name = strtolower( $block_name );
		$target_names = array(
			'affiliatex/pros-and-cons',
			'affiliatex/pros-cons',
			'affiliatex/proscons',
			'affiliatex/pros-and-cons-block',
			'affiliatex/pros-cons-block',
			'affiliatex/pros-and-cons-box',
		);

		if ( in_array( $block_name, $target_names, true ) ) {
			return true;
		}

		return strpos( $block_name, 'affiliatex/pros' ) === 0;
	}

	private function migrate_blocks( $blocks, &$has_changes, &$blocks_in_post, $dry_run ) {
		if ( ! is_array( $blocks ) ) {
			return $blocks;
		}

		foreach ( $blocks as &$block ) {
			if ( isset( $block['blockName'] ) && $this->is_affiliatex_pros_cons_block( $block['blockName'] ) ) {
				$has_changes = true;
				$blocks_in_post++;

				$attrs = isset( $block['attrs'] ) ? $block['attrs'] : array();

				// 1. Extract titles
				$pros_title = $this->extract_title( $attrs, 'pros', 'Pros' );
				$cons_title = $this->extract_title( $attrs, 'cons', 'Cons' );

				// 2. Extract list items (from attributes or inner blocks)
				$raw_pros = $this->extract_list_items( $block, 'pros' );
				$formatted_pros = $this->format_list_items( $raw_pros );

				$raw_cons = $this->extract_list_items( $block, 'cons' );
				$formatted_cons = $this->format_list_items( $raw_cons );

				// 3. Extract styling options if present
				$pros_bg_color      = isset( $attrs['prosBgColor'] ) ? $attrs['prosBgColor'] : ( isset( $attrs['prosHeaderBgColor'] ) ? $attrs['prosHeaderBgColor'] : '#24B644' );
				$cons_bg_color      = isset( $attrs['consBgColor'] ) ? $attrs['consBgColor'] : ( isset( $attrs['consHeaderBgColor'] ) ? $attrs['consHeaderBgColor'] : '#F13A3A' );
				$pros_text_color    = isset( $attrs['prosTextColor'] ) ? $attrs['prosTextColor'] : '#ffffff';
				$cons_text_color    = isset( $attrs['consTextColor'] ) ? $attrs['consTextColor'] : '#ffffff';
				$pros_list_bg_color = isset( $attrs['prosListBgColor'] ) ? $attrs['prosListBgColor'] : '#F5FFF8';
				$cons_list_bg_color = isset( $attrs['consListBgColor'] ) ? $attrs['consListBgColor'] : '#FFF5F5';
				$layout_style       = isset( $attrs['layoutStyle'] ) ? $attrs['layoutStyle'] : ( isset( $attrs['layout'] ) ? $attrs['layout'] : 'layout-type-1' );

				if ( $dry_run ) {
					echo '<div style="margin: 10px 0 20px 20px; padding: 12px 16px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 6px; font-size: 13px;">';
					echo '<strong style="color: #0f172a;">Parsed AffiliateX Block Attributes:</strong><br>';
					echo '<strong>Pros Title:</strong> ' . esc_html( $pros_title ) . '<br>';
					echo '<strong>Pros Items (' . count( $formatted_pros ) . '):</strong>';
					if ( empty( $formatted_pros ) ) {
						echo ' <em>None found</em>';
					} else {
						echo '<ul style="margin: 4px 0 10px 20px; list-style-type: disc;">';
						foreach ( $formatted_pros as $p ) {
							echo '<li>' . esc_html( $p['content'] ) . '</li>';
						}
						echo '</ul>';
					}
					echo '<strong>Cons Title:</strong> ' . esc_html( $cons_title ) . '<br>';
					echo '<strong>Cons Items (' . count( $formatted_cons ) . '):</strong>';
					if ( empty( $formatted_cons ) ) {
						echo ' <em>None found</em>';
					} else {
						echo '<ul style="margin: 4px 0 4px 20px; list-style-type: disc;">';
						foreach ( $formatted_cons as $c ) {
							echo '<li>' . esc_html( $c['content'] ) . '</li>';
						}
						echo '</ul>';
					}
					echo '</div>';
				}

				// 4. Construct attributes matching prosandcons/pros-and-cons schema (pros-and-cons 2)
				$new_attrs = array(
					'block_id'          => wp_generate_uuid4(),
					'prosTitle'         => $pros_title,
					'consTitle'         => $cons_title,
					'prosListItems'     => $formatted_pros,
					'consListItems'     => $formatted_cons,
					'layoutStyle'       => $layout_style,
					'prosContentType'   => 'list',
					'consContentType'   => 'list',
					'prosListType'      => 'unordered',
					'consListType'      => 'unordered',
					'prosIconStatus'    => true,
					'consIconStatus'    => true,
					'prosIconClass'     => 'far fa-circle-check',
					'consIconClass'     => 'far fa-circle-xmark',
					'prosUnorderedType' => 'icon',
					'consUnorderedType' => 'icon',
					'prosBgColor'       => $pros_bg_color,
					'consBgColor'       => $cons_bg_color,
					'prosTextColor'     => $pros_text_color,
					'consTextColor'     => $cons_text_color,
					'prosListBgColor'   => $pros_list_bg_color,
					'consListBgColor'   => $cons_list_bg_color,
					'prosIconColor'     => $pros_bg_color,
					'consIconColor'     => $cons_bg_color,
				);

				// Replace block name and attributes
				$block['blockName']    = 'prosandcons/pros-and-cons';
				$block['attrs']        = $new_attrs;
				$block['innerHTML']    = '';
				$block['innerContent'] = array();
				$block['innerBlocks']  = array();
			}

			if ( isset( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
				$block['innerBlocks'] = $this->migrate_blocks( $block['innerBlocks'], $has_changes, $blocks_in_post, $dry_run );
			}
		}

		return $blocks;
	}

	private function extract_title( $attrs, $type, $default ) {
		$keys = array(
			$type . 'Title',
			$type . 'Header',
			$type . 'Heading',
			$type . 'Text',
			$type . '_title',
			$type . '_header',
		);

		foreach ( $keys as $key ) {
			if ( ! empty( $attrs[ $key ] ) && is_string( $attrs[ $key ] ) ) {
				return trim( $attrs[ $key ] );
			}
		}

		return $default;
	}

	private function extract_list_items( $block, $type ) {
		$attrs = isset( $block['attrs'] ) ? $block['attrs'] : array();
		$keys = array(
			$type . 'ListItems',
			$type . 'List',
			$type . 'Items',
			$type,
			$type . '_list',
			$type . '_items',
		);

		// 1. Direct attribute extraction
		foreach ( $keys as $key ) {
			if ( isset( $attrs[ $key ] ) ) {
				$val = $attrs[ $key ];
				if ( is_array( $val ) && ! empty( $val ) ) {
					return $val;
				} elseif ( is_string( $val ) && '' !== trim( $val ) ) {
					$decoded = json_decode( $val, true );
					if ( is_array( $decoded ) && ! empty( $decoded ) ) {
						return $decoded;
					}
					
					// HTML <li> extraction
					if ( stripos( $val, '<li>' ) !== false ) {
						preg_match_all( '/<li>(.*?)<\/li>/is', $val, $matches );
						if ( ! empty( $matches[1] ) ) {
							return $matches[1];
						}
					}

					return array_filter( array_map( 'trim', explode( "\n", $val ) ) );
				}
			}
		}

		// 2. Extract from innerBlocks if present
		$extracted_items = array();
		if ( isset( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
			foreach ( $block['innerBlocks'] as $inner_block ) {
				$inner_name = strtolower( isset( $inner_block['blockName'] ) ? $inner_block['blockName'] : '' );
				
				if ( strpos( $inner_name, $type ) !== false || strpos( $inner_name, 'list-item' ) !== false || strpos( $inner_name, 'item' ) !== false ) {
					$text = '';
					if ( ! empty( $inner_block['attrs']['content'] ) ) {
						$text = $inner_block['attrs']['content'];
					} elseif ( ! empty( $inner_block['attrs']['text'] ) ) {
						$text = $inner_block['attrs']['text'];
					} elseif ( ! empty( $inner_block['innerHTML'] ) ) {
						$text = trim( strip_tags( $inner_block['innerHTML'], '<strong><em><a><span>' ) );
					}

					if ( ! empty( $text ) ) {
						$extracted_items[] = $text;
					}
				}
			}
		}

		return $extracted_items;
	}

	private function format_list_items( $raw_items ) {
		$formatted = array();
		if ( ! is_array( $raw_items ) ) {
			return $formatted;
		}

		foreach ( $raw_items as $item ) {
			$content = '';

			if ( is_array( $item ) ) {
				if ( isset( $item['content'] ) ) {
					$content = is_string( $item['content'] ) ? $item['content'] : ( is_scalar( $item['content'] ) ? (string) $item['content'] : '' );
				} elseif ( isset( $item['props']['children'] ) && is_array( $item['props']['children'] ) ) {
					$content = isset( $item['props']['children'][0] ) ? (string) $item['props']['children'][0] : '';
				} elseif ( isset( $item['list'] ) ) {
					$content = is_string( $item['list'] ) ? $item['list'] : '';
				} elseif ( isset( $item['text'] ) ) {
					$content = is_string( $item['text'] ) ? $item['text'] : '';
				} elseif ( isset( $item['label'] ) ) {
					$content = is_string( $item['label'] ) ? $item['label'] : '';
				}
			} elseif ( is_string( $item ) ) {
				$content = $item;
			} elseif ( is_scalar( $item ) ) {
				$content = (string) $item;
			}

			// Clean HTML wrappers like <li> or <p> while retaining inner text
			$content = trim( $content );
			$content = preg_replace( '/^<li[^>]*>(.*?)<\/li>$/is', '$1', $content );
			$content = preg_replace( '/^<p[^>]*>(.*?)<\/p>$/is', '$1', $content );
			$content = trim( $content );

			if ( '' !== $content ) {
				$formatted[] = array(
					'content' => $content,
				);
			}
		}

		return $formatted;
	}
}

new AffiliateX_To_ProsCons_Converter();
