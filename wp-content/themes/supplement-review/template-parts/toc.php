<?php
/**
 * Template part for displaying the Table of Contents card
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$post_id = get_the_ID();

// Extract headings from post content if available
$headings = function_exists( 'sr_get_post_headings' ) ? sr_get_post_headings( $post_id ) : array();

// If fewer than 3 headings exist in post, use standard 20-item demo structure
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

$total_sections = count( $headings );
$reading_time   = function_exists( 'sr_get_reading_time' ) ? sr_get_reading_time( $post_id ) : 10;
if ( $reading_time < 1 ) {
	$reading_time = 10;
}

// Optional group labels from post meta or customizer (exclude former defaults)
$group_str = get_post_meta( $post_id, '_sr_toc_groups', true );
if ( empty( $group_str ) ) {
	$group_str = get_theme_mod( 'sr_toc_groups', '' );
}

$groups = array();
if ( ! empty( $group_str ) ) {
	$raw_groups = array_map( 'trim', explode( ',', (string) $group_str ) );
	$excluded   = array( 'overview', 'the formula', 'buying guide' );
	foreach ( $raw_groups as $g ) {
		if ( ! empty( $g ) && ! in_array( strtolower( $g ), $excluded, true ) ) {
			$groups[] = $g;
		}
	}
}

// Split into columns (1 column if fewer than 4 items, otherwise 3 columns filled evenly)
if ( $total_sections < 4 ) {
	$columns = array( $headings );
} else {
	$chunk_size = (int) ceil( $total_sections / 3 );
	$columns    = array_chunk( $headings, max( 1, $chunk_size ) );
}
?>

<nav class="toc-card" id="tocCard" aria-label="<?php esc_attr_e( 'Table of contents', 'supplement-review' ); ?>">
	<!-- Header row (always visible) -->
	<div class="toc-header">
		<div class="toc-header-left">
			<h2 class="toc-title"><?php esc_html_e( 'Table of Content', 'supplement-review' ); ?></h2>
			<span class="toc-meta">
				<?php printf( esc_html__( '%1$d sections · %2$d min read', 'supplement-review' ), absint( $total_sections ), absint( $reading_time ) ); ?>
			</span>
		</div>
		<button type="button" class="toc-toggle-btn" id="tocToggleBtn" aria-expanded="true" aria-controls="tocBody">
			<span class="toc-toggle-text"><?php esc_html_e( 'Hide', 'supplement-review' ); ?></span>
			<svg class="toc-toggle-chevron" width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<path d="M2.5 7.5L6 4L9.5 7.5"/>
			</svg>
		</button>
	</div>

	<!-- Expanded body wrapper -->
	<div class="toc-body" id="tocBody">
		<div class="toc-body-inner">
			<div class="toc-content">
				<div class="toc-grid">
					<?php
					$item_counter = 1;
					foreach ( $columns as $col_idx => $col_headings ) :
						$group_label = isset( $groups[ $col_idx ] ) ? $groups[ $col_idx ] : '';
						?>
						<div class="toc-col">
							<?php if ( ! empty( $group_label ) ) : ?>
								<h3 class="toc-col-label"><?php echo esc_html( $group_label ); ?></h3>
							<?php endif; ?>
							<ul class="toc-list">
								<?php
								foreach ( $col_headings as $heading ) :
									$num_formatted = str_pad( (string) $item_counter, 2, '0', STR_PAD_LEFT );
									$is_first      = ( 1 === $item_counter );
									?>
									<li class="toc-list-item">
										<a href="#<?php echo esc_attr( $heading['id'] ); ?>" class="toc-item<?php echo $is_first ? ' is-active' : ''; ?>" data-section="<?php echo esc_attr( $heading['id'] ); ?>" data-num="<?php echo esc_attr( $num_formatted ); ?>"<?php echo $is_first ? ' aria-current="true"' : ''; ?>>
											<span class="toc-number"><?php echo esc_html( $num_formatted ); ?></span>
											<span class="toc-label"><?php echo esc_html( $heading['title'] ); ?></span>
											<svg class="toc-item-chevron" width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="#6EA590" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
												<path d="M4.5 2.5L8 6L4.5 9.5"/>
											</svg>
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
</nav>
