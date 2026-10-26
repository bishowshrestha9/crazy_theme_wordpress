<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Pre-footer Call to Action section (unless 404 page)
if ( ! is_404() ) {
	get_template_part( 'template-parts/cta-section' );
}

$phone     = get_theme_mod( 'sr_footer_phone', '+1 (800) 555-0199' );
$whatsapp  = get_theme_mod( 'sr_footer_whatsapp', '+1 (800) 555-0199' );
$hours     = get_theme_mod( 'sr_footer_hours', 'Mon - Fri: 9:00 AM - 6:00 PM EST' );
$email     = get_theme_mod( 'sr_footer_email', 'support@example.com' );
$copyright = get_theme_mod( 'sr_footer_copyright', sprintf( __( '© %s Supplement Review. All rights reserved.', 'supplement-review' ), date( 'Y' ) ) );
$medical   = get_theme_mod( 'sr_medical_disclaimer', __( 'Medical Disclaimer: Statements on this website have not been evaluated by the Food and Drug Administration. These products are not intended to diagnose, treat, cure, or prevent any disease. Always consult your physician before starting any diet or dietary supplement program.', 'supplement-review' ) );
?>
</div><!-- #content -->

<footer id="colophon" class="site-footer" role="contentinfo">
	<div class="sr-container">
		<div class="sr-footer-grid">
			<!-- Column 1: Site Branding & Direct Contact -->
			<div class="sr-footer-col">
				<?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
					<?php dynamic_sidebar( 'footer-1' ); ?>
				<?php else : ?>
					<h3 class="site-title">
						<?php bloginfo( 'name' ); ?>
					</h3>
					<p class="sr-footer-brand-desc">
						<?php bloginfo( 'description' ); ?>
					</p>
					<ul class="sr-footer-contact-list">
						<?php if ( ! empty( $phone ) ) : ?>
							<li class="sr-footer-contact-item">
								<?php sr_svg( 'phone', array( 'width' => 16, 'height' => 16 ) ); ?>
								<span><?php echo esc_html( $phone ); ?></span>
							</li>
						<?php endif; ?>

						<?php if ( ! empty( $whatsapp ) ) : ?>
							<li class="sr-footer-contact-item">
								<?php sr_svg( 'phone', array( 'width' => 16, 'height' => 16 ) ); ?>
								<span><?php printf( esc_html__( 'WhatsApp: %s', 'supplement-review' ), esc_html( $whatsapp ) ); ?></span>
							</li>
						<?php endif; ?>

						<?php if ( ! empty( $hours ) ) : ?>
							<li class="sr-footer-contact-item">
								<?php sr_svg( 'clock', array( 'width' => 16, 'height' => 16 ) ); ?>
								<span><?php echo esc_html( $hours ); ?></span>
							</li>
						<?php endif; ?>

						<?php if ( ! empty( $email ) ) : ?>
							<li class="sr-footer-contact-item">
								<?php sr_svg( 'envelope', array( 'width' => 16, 'height' => 16 ) ); ?>
								<a href="<?php echo esc_url( 'mailto:' . antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a>
							</li>
						<?php endif; ?>
					</ul>
				<?php endif; ?>
			</div>

			<!-- Column 2: Quick Links -->
			<div class="sr-footer-col">
				<?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
					<?php dynamic_sidebar( 'footer-2' ); ?>
				<?php else : ?>
					<h3><?php esc_html_e( 'Quick Links', 'supplement-review' ); ?></h3>
					<?php
					if ( has_nav_menu( 'footer_links' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'footer_links',
								'container'      => false,
								'fallback_cb'    => false,
							)
						);
					} else {
						?>
						<ul>
							<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'supplement-review' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>"><?php esc_html_e( 'About Our Lab', 'supplement-review' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/review-process/' ) ); ?>"><?php esc_html_e( 'How We Review', 'supplement-review' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/editorial-policy/' ) ); ?>"><?php esc_html_e( 'Editorial Policy', 'supplement-review' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact Team', 'supplement-review' ); ?></a></li>
						</ul>
						<?php
					}
					?>
				<?php endif; ?>
			</div>

			<!-- Column 3: Categories -->
			<div class="sr-footer-col">
				<?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
					<?php dynamic_sidebar( 'footer-3' ); ?>
				<?php else : ?>
					<h3><?php esc_html_e( 'Categories', 'supplement-review' ); ?></h3>
					<?php
					if ( has_nav_menu( 'footer_categories' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'footer_categories',
								'container'      => false,
								'fallback_cb'    => false,
							)
						);
					} else {
						?>
						<ul>
							<li><a href="<?php echo esc_url( home_url( '/category/weight-loss/' ) ); ?>"><?php esc_html_e( 'Weight Loss', 'supplement-review' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/category/male-enhancement/' ) ); ?>"><?php esc_html_e( 'Male Enhancement', 'supplement-review' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/category/brain-enhancement/' ) ); ?>"><?php esc_html_e( 'Brain Enhancement', 'supplement-review' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/category/joint-pain-relief/' ) ); ?>"><?php esc_html_e( 'Joint Pain Relief', 'supplement-review' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/category/blood-sugar/' ) ); ?>"><?php esc_html_e( 'Blood Sugar Support', 'supplement-review' ); ?></a></li>
						</ul>
						<?php
					}
					?>
				<?php endif; ?>
			</div>

			<!-- Column 4: Newsletter / Certifications -->
			<div class="sr-footer-col">
				<?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
					<?php dynamic_sidebar( 'footer-4' ); ?>
				<?php else : ?>
					<h3><?php esc_html_e( 'Trust & Standards', 'supplement-review' ); ?></h3>
					<p class="sr-footer-trust-desc">
						<?php esc_html_e( 'All our reviews are conducted by certified medical researchers and clinical contributors following strict editorial standards.', 'supplement-review' ); ?>
					</p>
					<div class="sr-footer-trust-badge">
						<?php sr_svg( 'shield-check', array( 'width' => 24, 'height' => 24 ) ); ?>
						<span><?php esc_html_e( 'Independent Lab Tested & Fact-Checked', 'supplement-review' ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- Footer Bottom Bar -->
		<div class="sr-footer-bottom">
			<div class="sr-footer-bottom-inner">
				<div class="sr-copyright-text">
					<?php echo esc_html( $copyright ); ?>
				</div>

				<nav class="sr-footer-legal-nav" aria-label="<?php esc_attr_e( 'Legal Links', 'supplement-review' ); ?>">
					<?php
					if ( has_nav_menu( 'footer_legal' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'footer_legal',
								'menu_class'     => 'sr-footer-legal-links',
								'container'      => false,
								'fallback_cb'    => false,
							)
						);
					} else {
						?>
						<ul class="sr-footer-legal-links">
							<li><a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'supplement-review' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/terms-of-service/' ) ); ?>"><?php esc_html_e( 'Terms of Service', 'supplement-review' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/disclaimer/' ) ); ?>"><?php esc_html_e( 'Disclaimer', 'supplement-review' ); ?></a></li>
							<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Contact Us', 'supplement-review' ); ?></a></li>
						</ul>
						<?php
					}
					?>
				</nav>
			</div>

			<?php if ( ! empty( $medical ) ) : ?>
				<div class="sr-medical-disclaimer">
					<?php echo esc_html( $medical ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</footer>

<!-- Floating Circular Back to Top Button -->
<button type="button" class="sr-back-to-top" id="srBackToTopBtn" aria-label="<?php esc_attr_e( 'Back to top', 'supplement-review' ); ?>">
	<?php sr_svg( 'arrow-up', array( 'width' => 20, 'height' => 20 ) ); ?>
</button>

<!-- Mobile Sticky Bottom Buy Bar (Single Review Only) -->
<?php if ( is_singular( 'post' ) ) :
	$product_name  = get_post_meta( get_the_ID(), '_sr_product_name', true );
	$price         = get_post_meta( get_the_ID(), '_sr_product_price', true );
	$aff_url       = get_post_meta( get_the_ID(), '_sr_affiliate_url', true );
	$btn_txt       = get_post_meta( get_the_ID(), '_sr_affiliate_btn_text', true );
	?>
	<div class="sr-mobile-sticky-cta" id="srMobileStickyCta" aria-hidden="true">
		<div>
			<strong class="sr-mobile-sticky-name"><?php echo esc_html( ! empty( $product_name ) ? $product_name : get_the_title() ); ?></strong>
			<span class="sr-mobile-price">$<?php echo esc_html( ! empty( $price ) ? $price : '12.99' ); ?></span>
			<span class="sr-mobile-discount">25% Off</span>
		</div>
		<a href="<?php echo esc_url( ! empty( $aff_url ) ? $aff_url : '#' ); ?>" class="sr-mobile-cta-btn" target="_blank" rel="noopener noreferrer sponsored nofollow">
			<?php echo esc_html( ! empty( $btn_txt ) ? $btn_txt : __( 'Buy Now', 'supplement-review' ) ); ?>
		</a>
	</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
