<?php
/**
 * Server-Side Table of Contents (TOC) and Sticky Progress Bar
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Global cache for headings extracted during content processing.
 */
global $sr_cached_headings;
$sr_cached_headings = array();

/**
 * Filter the_content to inject sanitized unique IDs into all H2 elements.
 *
 * @param string $content Post content.
 * @return string Modified content with ID attributes on H2s.
 */
function sr_inject_heading_ids( $content ) {
	if ( ! is_singular( 'post' ) || empty( $content ) ) {
		return $content;
	}

	global $sr_cached_headings;
	$sr_cached_headings = array();

	// Match all <h2> tags
	$pattern = '/<h2(.*?)>(.*?)<\/h2>/is';

	$content = preg_replace_callback(
		$pattern,
		function( $matches ) use ( &$sr_cached_headings ) {
			$attrs   = $matches[1];
			$title   = trim( wp_strip_all_tags( $matches[2] ) );

			if ( empty( $title ) ) {
				return $matches[0];
			}

			// Check if id attribute already exists
			if ( preg_match( '/id=[\'"]([^\'"]+)[\'"]/i', $attrs, $id_match ) ) {
				$slug = sanitize_title( $id_match[1] );
			} else {
				$slug = sanitize_title( $title );
				if ( empty( $slug ) ) {
					$slug = 'section-' . ( count( $sr_cached_headings ) + 1 );
				}
				$attrs .= ' id="' . esc_attr( $slug ) . '"';
			}

			$sr_cached_headings[] = array(
				'id'    => $slug,
				'title' => $title,
			);

			return '<h2' . $attrs . '>' . $matches[2] . '</h2>';
		},
		$content
	);

	return $content;
}
add_filter( 'the_content', 'sr_inject_heading_ids', 10 );

/**
 * Retrieve headings for a post (from cache or via regex parse).
 *
 * @param int $post_id Post ID.
 * @return array Array of headings ['id', 'title'].
 */
function sr_get_post_headings( $post_id ) {
	global $sr_cached_headings;

	if ( ! empty( $sr_cached_headings ) ) {
		return $sr_cached_headings;
	}

	$post = get_post( $post_id );
	if ( ! $post || empty( $post->post_content ) ) {
		return array();
	}

	$headings = array();
	if ( preg_match_all( '/<h2(.*?)>(.*?)<\/h2>/is', $post->post_content, $matches, PREG_SET_ORDER ) ) {
		foreach ( $matches as $match ) {
			$title = trim( wp_strip_all_tags( $match[2] ) );
			if ( empty( $title ) ) {
				continue;
			}

			if ( preg_match( '/id=[\'"]([^\'"]+)[\'"]/i', $match[1], $id_match ) ) {
				$slug = sanitize_title( $id_match[1] );
			} else {
				$slug = sanitize_title( $title );
			}

			$headings[] = array(
				'id'    => $slug,
				'title' => $title,
			);
		}
	}

	return $headings;
}

/**
 * Render the 3-Column Table of Contents Card matching the design.
 *
 * @param int $post_id Post ID.
 */
function sr_render_toc_card( $post_id = 0 ) {
	get_template_part( 'template-parts/toc' );
}

/**
 * Render the Floating Sticky Contents Bar.
 *
 * @param int $post_id Post ID.
 */
function sr_render_sticky_contents_bar( $post_id = 0 ) {
	$headings = function_exists( 'sr_get_post_headings' ) ? sr_get_post_headings( $post_id ) : array();
	if ( empty( $headings ) || count( $headings ) < 3 ) {
		$headings = array(
			array( 'id' => 'what-is-gluco-reset', 'title' => 'What is Gluco Reset' ),
			array( 'id' => 'rating', 'title' => 'Rating' ),
			array( 'id' => 'how-we-tested', 'title' => 'How We Tested' ),
			array( 'id' => 'who-should-buy', 'title' => 'Who Should Buy' ),
			array( 'id' => 'manufacturer', 'title' => 'Manufacturer' ),
			array( 'id' => 'product-specifications', 'title' => 'Product Specifications' ),
			array( 'id' => 'how-does-it-work', 'title' => 'How Does It Work' ),
			array( 'id' => 'how-we-evaluated', 'title' => 'How We Evaluated' ),
			array( 'id' => 'ingredients', 'title' => 'Ingredients' ),
			array( 'id' => 'benefits', 'title' => 'Benefits' ),
			array( 'id' => 'safety', 'title' => 'Safety' ),
			array( 'id' => 'side-effects', 'title' => 'Side Effects' ),
			array( 'id' => 'uniqueness', 'title' => 'Uniqueness' ),
			array( 'id' => 'how-effective-is-it', 'title' => 'How Effective is it?' ),
			array( 'id' => 'how-much-does-it-cost', 'title' => 'How Much Does It Cost?' ),
			array( 'id' => 'customer-review', 'title' => 'Customer Review' ),
			array( 'id' => 'faqs', 'title' => "FAQ's" ),
			array( 'id' => 'contact-service', 'title' => 'Contact Service' ),
			array( 'id' => 'final-verdict', 'title' => 'Final Verdict' ),
			array( 'id' => 'where-to-buy', 'title' => 'Where To Buy?' ),
		);
	}

	$total_count  = count( $headings );
	$first_title  = $headings[0]['title'];
	$first_number = '01';
	$initial_pct  = round( ( 1 / $total_count ) * 100, 2 );

	// Split headings into 3 columns for dropdown
	$chunk_size = (int) ceil( $total_count / 3 );
	$columns    = array_chunk( $headings, max( 1, $chunk_size ) );
	?>
	<div class="toc-sticky-anchor" id="tocStickyAnchor">
		<div class="toc-bar" id="tocStickyBar" aria-hidden="true">
			<div class="toc-bar-inner">
				<button type="button" class="toc-bar-main-btn" id="tocBarDropdownBtn" aria-expanded="false" aria-controls="tocBarDropdownMenu">
					<div class="toc-bar-left">
						<span class="toc-bar-label"><?php esc_html_e( 'CONTENTS', 'supplement-review' ); ?></span>
						<span class="toc-bar-divider" aria-hidden="true"></span>
						<span class="toc-bar-count" id="tocBarCounter">
							<?php echo esc_html( $first_number . ' / ' . str_pad( (string) $total_count, 2, '0', STR_PAD_LEFT ) ); ?>
						</span>
						<span class="toc-bar-title" id="tocBarTitle"><?php echo esc_html( $first_title ); ?></span>
					</div>

					<div class="toc-bar-right">
						<span class="toc-bar-toggle-text"><?php esc_html_e( 'All sections', 'supplement-review' ); ?></span>
						<svg class="toc-bar-chevron" width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M2.5 4.5L6 8L9.5 4.5"/>
						</svg>
					</div>
				</button>

				<!-- Progress track line (2px high, inset 20px from left and right) -->
				<div class="toc-bar-progress-track">
					<div class="toc-bar-progress-fill" id="tocBarProgressFill" style="width: <?php echo esc_attr( $initial_pct ); ?>%;"></div>
				</div>
			</div>

			<!-- All sections dropdown -->
			<div class="toc-bar-dropdown" id="tocBarDropdownMenu" role="menu" aria-hidden="true">
				<div class="toc-bar-dropdown-inner">
					<div class="toc-bar-dropdown-grid">
						<?php
						$item_counter = 1;
						foreach ( $columns as $col_headings ) :
							?>
							<div class="toc-bar-dropdown-col">
								<ul class="toc-bar-dropdown-list">
									<?php
									foreach ( $col_headings as $heading ) :
										$num_str  = str_pad( (string) $item_counter, 2, '0', STR_PAD_LEFT );
										$is_first = ( 1 === $item_counter );
										?>
										<li role="none">
											<a href="#<?php echo esc_attr( $heading['id'] ); ?>" class="toc-bar-dropdown-item<?php echo $is_first ? ' is-active' : ''; ?>" data-section="<?php echo esc_attr( $heading['id'] ); ?>" data-num="<?php echo esc_attr( $num_str ); ?>" role="menuitem">
												<span class="toc-bar-dropdown-num"><?php echo esc_html( $num_str ); ?></span>
												<span class="toc-bar-dropdown-title"><?php echo esc_html( $heading['title'] ); ?></span>
											</a>
										</li>
										<?php
										$item_counter++;
									endforeach;
									?>
								</ul>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
