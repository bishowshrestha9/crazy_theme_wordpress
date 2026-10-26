<?php
/**
 * The template for displaying the Homepage / Front Page
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

// Query for Featured Review (checked via _sr_is_featured or latest post)
$featured_args = array(
	'post_type'      => 'post',
	'posts_per_page' => 1,
	'meta_key'       => '_sr_is_featured',
	'meta_value'     => '1',
);
$featured_query = new WP_Query( $featured_args );
if ( ! $featured_query->have_posts() ) {
	$featured_query = new WP_Query(
		array(
			'post_type'      => 'post',
			'posts_per_page' => 1,
		)
	);
}
?>

<main id="primary" class="site-main">
	<div class="sr-container">

		<!-- HERO SECTION: FEATURED REVIEW -->
		<?php if ( $featured_query->have_posts() ) : ?>
			<?php while ( $featured_query->have_posts() ) : $featured_query->the_post();
				$featured_id  = get_the_ID();
				$product_name = get_post_meta( $featured_id, '_sr_product_name', true );
				if ( empty( $product_name ) ) $product_name = get_the_title();
				$product_tags = get_post_meta( $featured_id, '_sr_product_tags', true );
				$rating       = (float) get_post_meta( $featured_id, '_sr_product_rating', true );
				if ( $rating <= 0 ) $rating = 4.9;
				$price        = get_post_meta( $featured_id, '_sr_product_price', true );
				$verdict      = get_post_meta( $featured_id, '_sr_verdict_text', true );
				$thumb_url    = get_the_post_thumbnail_url( $featured_id, 'large' );
				if ( empty( $thumb_url ) ) {
					$img_id = (int) get_post_meta( $featured_id, '_sr_product_image_id', true );
					$thumb_url = $img_id ? wp_get_attachment_image_url( $img_id, 'medium' ) : get_template_directory_uri() . '/assets/images/product-sample.png';
				}
				?>
				<section class="sr-hero-featured sr-card" style="margin-bottom:48px;background:linear-gradient(135deg, var(--sr-color-bg-card) 0%, var(--sr-color-primary-light) 100%);padding:40px;border-radius:var(--sr-radius-xl);" aria-label="<?php esc_attr_e( 'Featured Supplement Review', 'supplement-review' ); ?>">
					<div style="display:grid;grid-template-columns:1.2fr 1fr;gap:40px;align-items:center;">
						<div>
							<div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
								<span class="sr-badge-pill">
									<?php sr_svg( 'award', array( 'width' => 14, 'height' => 14 ) ); ?>
									<?php esc_html_e( 'Featured Editorial Evaluation', 'supplement-review' ); ?>
								</span>
								<span style="font-size:0.8125rem;color:var(--sr-color-text-muted);">
									<?php echo esc_html( get_the_date( 'M j, Y' ) ); ?>
								</span>
							</div>

							<h1 style="font-size:clamp(1.8rem, 3.5vw, 2.5rem);margin-bottom:16px;">
								<a href="<?php the_permalink(); ?>" style="color:var(--sr-color-text-main);text-decoration:none;">
									<?php the_title(); ?>
								</a>
							</h1>

							<div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
								<?php echo sr_get_star_rating_html( $rating, true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php if ( ! empty( $product_tags ) ) : ?>
									<span style="color:var(--sr-color-text-muted);font-size:0.875rem;">• <?php echo esc_html( $product_tags ); ?></span>
								<?php endif; ?>
							</div>

							<p style="font-size:1.05rem;line-height:1.6;color:var(--sr-color-text-body);margin-bottom:24px;">
								<?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?>
							</p>

							<div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
								<a href="<?php the_permalink(); ?>" class="sr-btn sr-btn-primary sr-btn-lg">
									<span><?php esc_html_e( 'Read Full Breakdown', 'supplement-review' ); ?></span>
									<?php sr_svg( 'chevron-right', array( 'width' => 16, 'height' => 16 ) ); ?>
								</a>
								<?php if ( ! empty( $price ) ) : ?>
									<span style="font-weight:700;font-size:1.25rem;color:var(--sr-color-text-main);">
										<?php printf( esc_html__( 'From $%s', 'supplement-review' ), esc_html( $price ) ); ?>
									</span>
								<?php endif; ?>
							</div>
						</div>

						<div style="display:flex;justify-content:center;align-items:center;">
							<a href="<?php the_permalink(); ?>" style="display:inline-block;max-width:280px;filter:drop-shadow(0 15px 25px rgba(0,0,0,0.12));">
								<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $product_name ); ?>" style="max-height:340px;width:auto;object-fit:contain;" />
							</a>
						</div>
					</div>
				</section>
			<?php endwhile; wp_reset_postdata(); ?>
		<?php endif; ?>

		<!-- CATEGORY FILTER PILLS -->
		<section class="sr-categories-bar" style="margin-bottom:36px;" aria-label="<?php esc_attr_e( 'Filter by Category', 'supplement-review' ); ?>">
			<div style="display:flex;align-items:center;gap:12px;overflow-x:auto;padding-bottom:10px;">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="sr-badge-pill" style="font-size:0.9375rem;padding:8px 18px;background:var(--sr-color-primary);color:#ffffff;border-color:var(--sr-color-primary);">
					<?php esc_html_e( 'All Reviews', 'supplement-review' ); ?>
				</a>
				<?php
				$categories = get_categories( array( 'hide_empty' => false, 'number' => 6 ) );
				foreach ( $categories as $cat ) :
					?>
					<a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" class="sr-badge-pill" style="font-size:0.9375rem;padding:8px 18px;">
						<?php echo esc_html( $cat->name ); ?> (<?php echo absint( $cat->count ); ?>)
					</a>
				<?php endforeach; ?>
			</div>
		</section>

		<!-- LATEST SUPPLEMENT REVIEWS GRID -->
		<section class="sr-latest-reviews-section" style="margin-bottom:56px;" aria-label="<?php esc_attr_e( 'Latest Reviews', 'supplement-review' ); ?>">
			<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
				<div>
					<h2 style="margin:0;font-size:1.85rem;"><?php esc_html_e( 'Latest Supplement Reviews', 'supplement-review' ); ?></h2>
					<p style="color:var(--sr-color-text-muted);margin:4px 0 0;"><?php esc_html_e( 'Independent clinical evaluations, ingredients analyses, and consumer warnings.', 'supplement-review' ); ?></p>
				</div>
			</div>

			<?php
			$recent_query = new WP_Query(
				array(
					'post_type'           => 'post',
					'posts_per_page'      => 8,
					'ignore_sticky_posts' => 1,
				)
			);

			if ( $recent_query->have_posts() ) :
				?>
				<div class="sr-cards-grid-4">
					<?php
					while ( $recent_query->have_posts() ) :
						$recent_query->the_post();
						get_template_part( 'template-parts/content-card' );
					endwhile;
					wp_reset_postdata();
					?>
				</div>
				<?php
			else :
				get_template_part( 'template-parts/content-none' );
			endif;
			?>
		</section>

		<!-- WHY TRUST OUR REVIEWS FEATURE TILES -->
		<section class="sr-trust-features-section" style="margin-bottom:64px;" aria-label="<?php esc_attr_e( 'Review Methodology', 'supplement-review' ); ?>">
			<div style="text-align:center;max-width:700px;margin:0 auto 36px;">
				<h2 style="font-size:2rem;margin-bottom:12px;"><?php esc_html_e( 'Why Trust Our Health Reviews?', 'supplement-review' ); ?></h2>
				<p style="color:var(--sr-color-text-muted);font-size:1.05rem;"><?php esc_html_e( 'Our editorial integrity is guarded by healthcare professionals and clinical fact-checkers.', 'supplement-review' ); ?></p>
			</div>

			<div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:24px;">
				<div class="sr-card" style="text-align:center;padding:32px 24px;">
					<div style="width:54px;height:54px;border-radius:var(--sr-radius-full);background:var(--sr-color-primary-light);color:var(--sr-color-primary);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
						<?php sr_svg( 'shield-check', array( 'width' => 26, 'height' => 26 ) ); ?>
					</div>
					<h3 style="font-size:1.2rem;margin-bottom:10px;"><?php esc_html_e( '100% Independent Testing', 'supplement-review' ); ?></h3>
					<p style="color:var(--sr-color-text-muted);font-size:0.875rem;line-height:1.6;margin:0;">
						<?php esc_html_e( 'We never accept paid placements from supplement manufacturers. Our ratings are based strictly on formula purity and clinical proof.', 'supplement-review' ); ?>
					</p>
				</div>

				<div class="sr-card" style="text-align:center;padding:32px 24px;">
					<div style="width:54px;height:54px;border-radius:var(--sr-radius-full);background:var(--sr-color-primary-light);color:var(--sr-color-primary);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
						<?php sr_svg( 'leaf', array( 'width' => 26, 'height' => 26 ) ); ?>
					</div>
					<h3 style="font-size:1.2rem;margin-bottom:10px;"><?php esc_html_e( 'Peer-Reviewed Science', 'supplement-review' ); ?></h3>
					<p style="color:var(--sr-color-text-muted);font-size:0.875rem;line-height:1.6;margin:0;">
						<?php esc_html_e( 'Every active ingredient is verified against clinical databases like PubMed and NIH to verify clinical efficacy and dosage safety.', 'supplement-review' ); ?>
					</p>
				</div>

				<div class="sr-card" style="text-align:center;padding:32px 24px;">
					<div style="width:54px;height:54px;border-radius:var(--sr-radius-full);background:var(--sr-color-primary-light);color:var(--sr-color-primary);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
						<?php sr_svg( 'award', array( 'width' => 26, 'height' => 26 ) ); ?>
					</div>
					<h3 style="font-size:1.2rem;margin-bottom:10px;"><?php esc_html_e( 'Medical Fact-Check', 'supplement-review' ); ?></h3>
					<p style="color:var(--sr-color-text-muted);font-size:0.875rem;line-height:1.6;margin:0;">
						<?php esc_html_e( 'Articles and product verdicts are thoroughly scrutinized by licensed medical contributors before being published.', 'supplement-review' ); ?>
					</p>
				</div>
			</div>
		</section>

	</div><!-- .sr-container -->
</main><!-- #primary -->

<?php
get_footer();
