<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class RSN_Converter {

	private static $instance = null;

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'wp_ajax_rsn_get_candidate_posts', array( $this, 'ajax_get_candidate_posts' ) );
		add_action( 'wp_ajax_rsn_process_batch', array( $this, 'ajax_process_batch' ) );
	}

	public function add_admin_menu() {
		add_submenu_page(
			'tools.php',
			'AffiliateX Notice Converter',
			'AffiliateX Notice Converter',
			'manage_options',
			'affiliatex-notice-converter',
			array( $this, 'render_admin_page' )
		);
	}

	public function enqueue_admin_scripts( $hook ) {
		if ( 'tools_page_affiliatex-notice-converter' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'dashicons' );
	}

	public function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'review-summary-notice' ) );
		}

		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		?>
		<div class="wrap">
			<h1 style="margin-bottom: 20px;">AffiliateX Notice Block Converter</h1>

			<div class="card" style="max-width: 900px; padding: 25px; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); background: #fff;">
				<h2 style="margin-top:0;">Scan & Bulk Replace AffiliateX Notice Blocks</h2>
				<p>This bulk replacer tool scans published posts, pages, and custom post types for any <strong>AffiliateX Notice</strong> block (e.g. <code>affiliatex/notice</code>, <code>affx/notice</code>, <code>affiliatex-notice-title</code>) and converts them into the <strong>Review Summary Notice</strong> block (<code>review-summary-notice/notice-card</code>).</p>
				
				<div style="background: #eef6fc; padding: 15px 20px; border-left: 4px solid #5ea3e1; margin: 18px 0; border-radius: 4px;">
					<strong>Recommended Workflow:</strong>
					<ol style="margin: 8px 0 0 20px;">
						<li>Click <strong>Perform Dry Run Scan</strong> to inspect all matching posts and preview extracted titles & list items without modifying the database.</li>
						<li>Click <strong>Convert All Blocks Now</strong> to execute batch replacements across all matching posts.</li>
					</ol>
				</div>

				<div style="margin-top: 20px; padding: 15px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px;">
					<strong>Target Post Types:</strong>
					<div style="margin-top: 8px; display: flex; gap: 16px; flex-wrap: wrap;">
						<?php foreach ( $post_types as $pt ) : ?>
							<label style="display: flex; align-items: center; gap: 6px; cursor: pointer;">
								<input type="checkbox" class="rsn-pt-checkbox" value="<?php echo esc_attr( $pt->name ); ?>" checked>
								<?php echo esc_html( $pt->label ); ?> (<code><?php echo esc_html( $pt->name ); ?></code>)
							</label>
						<?php endforeach; ?>
					</div>
				</div>

				<div style="margin-top: 22px;">
					<?php wp_nonce_field( 'rsn_converter_action', 'rsn_converter_nonce' ); ?>
					<button type="button" id="rsn-btn-dryrun" class="button button-secondary button-large" style="margin-right: 12px;">
						<span class="dashicons dashicons-search" style="margin-top: 4px;"></span> Perform Dry Run Scan
					</button>
					<button type="button" id="rsn-btn-convert" class="button button-primary button-large">
						<span class="dashicons dashicons-update" style="margin-top: 4px;"></span> Convert All Blocks Now
					</button>
				</div>

				<!-- Progress Container -->
				<div id="rsn-progress-wrapper" style="display:none; margin-top: 25px;">
					<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
						<strong id="rsn-progress-status">Initializing scan...</strong>
						<span id="rsn-progress-percent" style="font-weight: 600; color: #5ea3e1;">0%</span>
					</div>
					<div style="background: #e2e8f0; border-radius: 6px; height: 16px; overflow: hidden;">
						<div id="rsn-progress-bar" style="background: #5ea3e1; width: 0%; height: 100%; transition: width 0.3s ease;"></div>
					</div>
				</div>
			</div>

			<!-- Results Output Log -->
			<div id="rsn-log-wrapper" style="display:none; margin-top: 25px; background: #fff; padding: 25px; border: 1px solid #ccd0d4; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); max-width: 900px;">
				<h2 id="rsn-log-title" style="margin-top: 0;">Scan Results</h2>
				<div id="rsn-log-content"></div>
			</div>
		</div>

		<script>
		jQuery(document).ready(function($) {
			const nonce = $('#rsn_converter_nonce').val();

			function getSelectedPostTypes() {
				const types = [];
				$('.rsn-pt-checkbox:checked').each(function() {
					types.push($(this).val());
				});
				return types;
			}

			function runBatchConverter(dryRun) {
				const postTypes = getSelectedPostTypes();
				if (!postTypes.length) {
					alert('Please select at least one post type.');
					return;
				}

				if (!dryRun && !confirm('Are you sure you want to convert all matching AffiliateX Notice blocks? This will update post contents in the database.')) {
					return;
				}

				$('#rsn-btn-dryrun, #rsn-btn-convert').prop('disabled', true);
				$('#rsn-progress-wrapper').show();
				$('#rsn-progress-bar').css('width', '0%');
				$('#rsn-progress-percent').text('0%');
				$('#rsn-progress-status').text('Scanning database for candidate posts...');
				$('#rsn-log-wrapper').show();
				$('#rsn-log-title').text(dryRun ? 'Dry Run Scan Results' : 'Conversion Progress & Results');
				$('#rsn-log-content').html('<p style="color: #64748b;">Finding matching posts...</p>');

				$.post(ajaxurl, {
					action: 'rsn_get_candidate_posts',
					rsn_nonce: nonce,
					post_types: postTypes
				}, function(res) {
					if (!res.success) {
						const errText = $('<div>').text(res.data || 'Failed to scan candidate posts.').html();
						$('#rsn-progress-status').text('Error scanning posts.');
						$('#rsn-log-content').html('<p style="color: #dc2626;">Error: ' + errText + '</p>');
						$('#rsn-btn-dryrun, #rsn-btn-convert').prop('disabled', false);
						return;
					}

					const postIds = res.data.post_ids || [];
					if (!postIds.length) {
						$('#rsn-progress-bar').css('width', '100%');
						$('#rsn-progress-percent').text('100%');
						$('#rsn-progress-status').text('Scan completed. No matching posts found.');
						$('#rsn-log-content').html('<p>No published posts containing candidate AffiliateX Notice blocks were found.</p>');
						$('#rsn-btn-dryrun, #rsn-btn-convert').prop('disabled', false);
						return;
					}

					const totalPosts = postIds.length;
					const batchSize = 5;
					let processedPosts = 0;
					let totalConvertedPosts = 0;
					let totalConvertedBlocks = 0;
					let logHtml = '<p>Found <strong>' + totalPosts + '</strong> post(s) containing candidate AffiliateX blocks.</p><ol style="margin-left: 20px;">';

					function processNextBatch(startIndex) {
						if (startIndex >= totalPosts) {
							logHtml += '</ol><p style="font-size: 16px; margin-top: 20px; padding-top: 15px; border-top: 1px solid #e2e8f0;"><strong>Summary:</strong> ' + (dryRun ? 'Would convert ' : 'Successfully converted ') + totalConvertedBlocks + ' block(s) across ' + totalConvertedPosts + ' post(s).</p>';
							$('#rsn-log-content').html(logHtml);
							$('#rsn-progress-bar').css('width', '100%');
							$('#rsn-progress-percent').text('100%');
							$('#rsn-progress-status').text(dryRun ? 'Dry run scan completed!' : 'Bulk conversion completed successfully!');
							$('#rsn-btn-dryrun, #rsn-btn-convert').prop('disabled', false);
							return;
						}

						const batchIds = postIds.slice(startIndex, startIndex + batchSize);
						const percent = Math.round((startIndex / totalPosts) * 100);
						$('#rsn-progress-bar').css('width', percent + '%');
						$('#rsn-progress-percent').text(percent + '%');
						$('#rsn-progress-status').text('Processing batch ' + (Math.floor(startIndex / batchSize) + 1) + ' of ' + Math.ceil(totalPosts / batchSize) + '...');

						$.post(ajaxurl, {
							action: 'rsn_process_batch',
							rsn_nonce: nonce,
							post_ids: batchIds,
							dry_run: dryRun ? 1 : 0
						}, function(batchRes) {
							if (batchRes.success && batchRes.data) {
								totalConvertedPosts += batchRes.data.converted_posts || 0;
								totalConvertedBlocks += batchRes.data.converted_blocks || 0;
								if (batchRes.data.log_items) {
									logHtml += batchRes.data.log_items;
								}
								$('#rsn-log-content').html(logHtml + '</ol>');
							}
							processNextBatch(startIndex + batchSize);
						}).fail(function() {
							logHtml += '<li style="color:#dc2626;">Batch processing failed for items starting at index ' + startIndex + '</li>';
							processNextBatch(startIndex + batchSize);
						});
					}

					processNextBatch(0);
				});
			}

			$('#rsn-btn-dryrun').on('click', function() {
				runBatchConverter(true);
			});

			$('#rsn-btn-convert').on('click', function() {
				runBatchConverter(false);
			});
		});
		</script>
		<?php
	}

	public function ajax_get_candidate_posts() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized capability.' );
		}

		check_ajax_referer( 'rsn_converter_action', 'rsn_nonce' );

		global $wpdb;
		$post_types = isset( $_POST['post_types'] ) && is_array( $_POST['post_types'] ) ? array_map( 'sanitize_key', $_POST['post_types'] ) : array( 'post', 'page' );

		if ( empty( $post_types ) ) {
			wp_send_json_error( 'No post types selected.' );
		}

		$in_clause  = implode( "', '", array_map( 'esc_sql', $post_types ) );
		$like_query_affx = '%' . $wpdb->esc_like( 'affiliatex' ) . '%';
		$like_query_rsn  = '%' . $wpdb->esc_like( 'review-summary-notice' ) . '%';

		$sql = "SELECT ID FROM {$wpdb->posts} WHERE (post_content LIKE %s OR post_content LIKE %s) AND post_status = 'publish' AND post_type IN ('{$in_clause}') ORDER BY ID DESC";
		$post_ids = $wpdb->get_col( $wpdb->prepare( $sql, $like_query_affx, $like_query_rsn ) );

		wp_send_json_success( array( 'post_ids' => array_map( 'absint', $post_ids ) ) );
	}

	public function ajax_process_batch() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized capability.' );
		}

		check_ajax_referer( 'rsn_converter_action', 'rsn_nonce' );

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

				$edit_link = esc_url( get_edit_post_link( $post_id ) );
				$post_title = esc_html( $post->post_title );

				if ( ! $dry_run ) {
					$new_content = serialize_blocks( $updated_blocks );
					wp_update_post( array(
						'ID'           => $post_id,
						'post_content' => wp_slash( $new_content ),
					) );
					$log_items .= '<li style="margin-bottom: 12px;"><strong>Converted:</strong> <a href="' . $edit_link . '" target="_blank">' . $post_title . '</a> (' . absint( $blocks_in_post ) . ' block(s) migrated)</li>';
				} else {
					$log_items .= '<li style="margin-bottom: 12px;"><strong>Found Match:</strong> <a href="' . $edit_link . '" target="_blank">' . $post_title . '</a> (' . absint( $blocks_in_post ) . ' block(s) to migrate)' . $item_logs . '</li>';
				}
			}
		}

		wp_send_json_success( array(
			'converted_posts'  => $converted_posts,
			'converted_blocks' => $converted_blocks,
			'log_items'        => $log_items,
		) );
	}

	private function is_affiliatex_notice_block( $block_name ) {
		if ( empty( $block_name ) || ! is_string( $block_name ) ) {
			return false;
		}

		$block_name = strtolower( trim( $block_name ) );

		// Exclude child element blocks so they aren't parsed as standalone notice cards
		$child_elements = array(
			'affiliatex/notice-title',
			'affiliatex/notice-heading',
			'affiliatex/notice-content',
			'affiliatex/notice-item',
			'affiliatex/notice-items',
			'affiliatex/notice-list',
			'affiliatex/notice-text',
			'affiliatex/notice-header',
			'affiliatex/notice-icon',
			'affiliatex/notice-button',
			'affx/notice-title',
			'affx/notice-heading',
			'affx/notice-content',
			'affx/notice-item',
			'affx/notice-items',
			'affx/notice-list',
			'affx/notice-text',
			'affx/notice-header',
			'affx/notice-icon',
			'affx/notice-button',
		);

		if ( in_array( $block_name, $child_elements, true ) ) {
			return false;
		}

		$target_names = array(
			'affiliatex/notice',
			'affiliatex/notice-block',
			'affiliatex/notice-box',
			'affiliatex/notice-card',
			'affx/notice',
			'affx/notice-box',
			'affx/notice-card',
			'review-summary-notice/notice-card',
		);

		if ( in_array( $block_name, $target_names, true ) ) {
			return true;
		}

		return ( strpos( $block_name, 'affiliatex/' ) === 0 || strpos( $block_name, 'affx/' ) === 0 ) && strpos( $block_name, 'notice' ) !== false;
	}

	private function migrate_notice_blocks( $blocks, &$has_changes, &$blocks_in_post, $dry_run, &$item_logs = '' ) {
		if ( ! is_array( $blocks ) ) {
			return $blocks;
		}

		foreach ( $blocks as &$block ) {
			if ( isset( $block['blockName'] ) && $this->is_affiliatex_notice_block( $block['blockName'] ) ) {
				$has_changes = true;
				$blocks_in_post++;

				$attrs = isset( $block['attrs'] ) ? $block['attrs'] : array();

				// Extract title and list items from AffiliateX block
				$extracted_title = $this->extract_notice_title( $attrs, $block );
				$extracted_items = $this->extract_notice_items( $attrs, $block );

				$final_title = ! empty( $extracted_title ) ? $extracted_title : 'OptiCycle Review Summary';

				if ( $dry_run ) {
					$item_logs .= '<div style="margin: 8px 0 15px 15px; padding: 12px 16px; background: #f8fafc; border: 1px dashed #5ea3e1; border-radius: 6px; font-size: 13px;">';
					$item_logs .= '<strong style="color: #0f172a;">Parsed AffiliateX Notice Block Diagnostic:</strong><br>';
					$item_logs .= '<strong>Block Name:</strong> <code>' . esc_html( isset( $block['blockName'] ) ? $block['blockName'] : 'Unknown' ) . '</code><br>';
					$item_logs .= '<strong>Attributes JSON:</strong> <code style="word-break: break-all;">' . esc_html( wp_json_encode( $attrs ) ) . '</code><br>';
					$item_logs .= '<strong>Title Extracted:</strong> ' . esc_html( $final_title ) . '<br>';
					$item_logs .= '<strong>Extracted List Items (' . count( $extracted_items ) . '):</strong>';
					if ( empty( $extracted_items ) ) {
						$item_logs .= ' <em>None found</em>';
					} else {
						$item_logs .= '<ul style="margin: 4px 0 4px 20px; list-style-type: disc;">';
						foreach ( $extracted_items as $item ) {
							$item_logs .= '<li>' . esc_html( $item['content'] ) . '</li>';
						}
						$item_logs .= '</ul>';
					}
					if ( ! empty( $block['innerHTML'] ) ) {
						$item_logs .= '<details style="margin-top: 6px;"><summary><strong>Raw innerHTML snippet</strong></summary><pre style="font-size: 11px; white-space: pre-wrap; max-height: 140px; overflow: auto; background: #fff; padding: 8px; border: 1px solid #cbd5e1;">' . esc_html( substr( $block['innerHTML'], 0, 1000 ) ) . '</pre></details>';
					}
					$item_logs .= '</div>';
				}

				$header_bg_color = ! empty( $attrs['noticeBgColor'] ) ? $attrs['noticeBgColor'] : ( ! empty( $attrs['headerBgColor'] ) ? $attrs['headerBgColor'] : '#5ea3e1' );
				$icon_color      = ! empty( $attrs['noticeIconColor'] ) ? $attrs['noticeIconColor'] : ( ! empty( $attrs['iconColor'] ) ? $attrs['iconColor'] : '#5ea3e1' );

				// Construct new attributes for review-summary-notice/notice-card
				$new_attrs = array(
					'blockId'         => 'rsn-' . wp_generate_uuid4(),
					'title'           => $final_title,
					'items'           => $extracted_items,
					'headerBgColor'   => $header_bg_color,
					'headerTextColor' => '#ffffff',
					'iconColor'       => $icon_color,
					'borderColor'     => '#e1ebf5',
					'cardBgColor'     => '#ffffff',
					'textColor'       => '#333333',
					'showIcon'        => true,
				);

				// Replace block name and attributes
				$block['blockName']    = 'review-summary-notice/notice-card';
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
		$text = preg_replace( '/<a\s+([^>]*?>(.*?)<\/a>/is', '$1', $text );

		// 5. Strip all remaining HTML tags except basic inline formatting (strong, em, b, i, code)
		$text = strip_tags( $text, '<strong><em><b><i><code>' );

		// 6. Remove zero-width spaces and control characters
		$text = preg_replace( '/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $text );

		// 7. Clean up extra spacing and wrapping quotes
		$text = preg_replace( '/[ \t]+/', ' ', $text );
		$text = trim( preg_replace( '/^["\']\s*(.*?)\s*["\']$/s', '$1', trim( $text ) ) );

		return trim( $text );
	}

	private function is_affiliatex_cta_item( $text ) {
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

	private function sanitize_extracted_markup( $text ) {
		$allowed = array(
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
		$clean = wp_kses( $text, $allowed );
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

	private function extract_notice_title( $attrs, $block ) {
		$title = '';
		$keys  = array( 'noticeTitle', 'title', 'heading', 'notice_title', 'headerText', 'noticeHeader', 'notice_heading', 'boxTitle', 'cardTitle', 'name' );
		foreach ( $keys as $key ) {
			if ( ! empty( $attrs[ $key ] ) && is_string( $attrs[ $key ] ) ) {
				$title = trim( strip_tags( $attrs[ $key ] ) );
				break;
			}
		}

		if ( empty( $title ) && ! empty( $block['innerHTML'] ) ) {
			if ( preg_match( '/<h[1-6][^>]*class="[^"]*affiliatex-notice-title[^"]*"[^>]*>(.*?)<\/h[1-6]>/is', $block['innerHTML'], $match ) ) {
				$title = trim( strip_tags( $match[1] ) );
			} elseif ( preg_match( '/<h[1-6][^>]*class="[^"]*notice-title[^"]*"[^>]*>(.*?)<\/h[1-6]>/is', $block['innerHTML'], $match ) ) {
				$title = trim( strip_tags( $match[1] ) );
			} elseif ( preg_match( '/<h[1-6][^>]*>(.*?)<\/h[1-6]>/is', $block['innerHTML'], $match ) ) {
				$title = trim( strip_tags( $match[1] ) );
			}
		}

		$clean = $this->clean_extracted_text( $title );
		$clean = preg_replace( '/<i[^>]*>.*?<\/i>/is', '', $clean );
		$clean = preg_replace( '/<svg[^>]*>.*?<\/svg>/is', '', $clean );
		$clean = wp_strip_all_tags( $clean );
		$clean = trim( preg_replace( '/^["\']\s*(.*?)\s*["\']$/s', '$1', trim( $clean ) ) );

		return $clean;
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

	private function collect_raw_items_from_block( $block, &$raw_items ) {
		if ( ! is_array( $block ) ) {
			return;
		}

		// 1. Direct attributes search (placed AffiliateX noticeListItems first)
		$attrs = isset( $block['attrs'] ) && is_array( $block['attrs'] ) ? $block['attrs'] : array();
		$keys  = array( 'noticeListItems', 'notice_list_items', 'noticeList', 'noticeItems', 'items', 'listItems', 'content', 'noticeContent', 'text', 'list', 'boxItems', 'notice_items', 'notice_list', 'noticeText', 'bullets', 'points', 'features', 'options', 'list_items', 'notice_box_items', 'notice_card_items', 'description', 'noticeDescription', 'desc', 'notice_desc' );
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

		// 2. Check innerHTML and innerContent HTML string chunks
		$html_chunks = array();
		if ( ! empty( $block['innerHTML'] ) && is_string( $block['innerHTML'] ) ) {
			$html_chunks[] = $block['innerHTML'];
		}
		if ( ! empty( $block['innerContent'] ) && is_array( $block['innerContent'] ) ) {
			foreach ( $block['innerContent'] as $chunk ) {
				if ( is_string( $chunk ) && '' !== trim( $chunk ) ) {
					$html_chunks[] = $chunk;
				}
			}
		}

		$combined_html = implode( "\n", $html_chunks );
		if ( ! empty( $combined_html ) ) {
			if ( preg_match_all( '/<li[^>]*>(.*?)<\/li>/is', $combined_html, $matches ) ) {
				foreach ( $matches[1] as $m ) {
					$raw_items[] = $m;
				}
			} elseif ( preg_match_all( '/<p[^>]*>(.*?)<\/p>/is', $combined_html, $matches ) ) {
				foreach ( $matches[1] as $m ) {
					$raw_items[] = $m;
				}
			} elseif ( preg_match_all( '/<div[^>]*class="[^"]*(?:item|bullet|list|notice)[^"]*"[^>]*>(.*?)<\/div>/is', $combined_html, $matches ) ) {
				foreach ( $matches[1] as $m ) {
					$raw_items[] = $m;
				}
			}
		}

		if ( empty( $raw_items ) && ! empty( $combined_html ) ) {
			$clean_html = preg_replace( '/<h[1-6][^>]*>.*?<\/h[1-6]>/is', '', $combined_html );
			$clean_html = preg_replace( '/<i[^>]*>.*?<\/i>/is', '', $clean_html );
			$clean_html = preg_replace( '/<svg[^>]*>.*?<\/svg>/is', '', $clean_html );
			$clean_html = preg_replace( '/<br\s*\/?>/i', "\n", $clean_html );
			$clean_html = preg_replace( '/<\/(?:p|div|li|span)>/i', "\n", $clean_html );
			$clean_html = wp_strip_all_tags( $clean_html );

			$lines = array_filter( array_map( 'trim', explode( "\n", $clean_html ) ) );
			foreach ( $lines as $line ) {
				$line = preg_replace( '/^(?:[\x{2022}\x{2023}\x{25E6}\x{2043}\x{2219}\-*\d+\.]\s*)+/u', '', $line );
				$line = trim( $line );
				if ( strlen( $line ) > 2 ) {
					$raw_items[] = $line;
				}
			}
		}

		// 3. Recurse into innerBlocks
		if ( isset( $block['innerBlocks'] ) && is_array( $block['innerBlocks'] ) ) {
			foreach ( $block['innerBlocks'] as $inner_block ) {
				$this->collect_raw_items_from_block( $inner_block, $raw_items );
			}
		}
	}

	private function extract_notice_items( $attrs, $block ) {
		$raw_items = array();
		$this->collect_raw_items_from_block( $block, $raw_items );

		// Format into array of objects: [ ['content' => '...'], ... ]
		$formatted = array();
		foreach ( $raw_items as $item ) {
			$text = '';
			if ( is_array( $item ) ) {
				if ( isset( $item['props'] ) ) {
					$text = $this->extract_text_from_react_children( isset( $item['props']['children'] ) ? $item['props']['children'] : $item['props'] );
				}
				if ( empty( $text ) ) {
					foreach ( array( 'content', 'text', 'item', 'title', 'value', 'label', 'description', 'desc', 'name' ) as $k ) {
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
			$text = preg_replace( '/^<span[^>]*>(.*?)<\/span>$/is', '$1', $text );

			// Strip icons (FontAwesome, SVG, dashicons) from extracted list item
			$text = preg_replace( '/<i[^>]*>.*?<\/i>/is', '', $text );
			$text = preg_replace( '/<svg[^>]*>.*?<\/svg>/is', '', $text );
			$text = preg_replace( '/<span[^>]*class="[^"]*(?:icon|dashicon|fa)[^"]*"[^>]*>.*?<\/span>/is', '', $text );

			$text = $this->clean_extracted_text( $text );

			// Skip AffiliateX promotional CTA links (e.g., "Click Here to Check the Latest Price...")
			if ( $this->is_affiliatex_cta_item( $text ) ) {
				continue;
			}

			$text = $this->sanitize_extracted_markup( $text );

			// Strip wrapping quotes around bullet text if present (e.g. "Customer experiences...")
			$text = trim( preg_replace( '/^["\']\s*(.*?)\s*["\']$/s', '$1', trim( $text ) ) );

			if ( '' !== $text ) {
				$formatted[] = array( 'content' => $text );
			}
		}

		return $formatted;
	}
}

