<?php
/**
 * The header for our theme
 *
 * Displays all of the <head> section and everything up until <div id="content">
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Accessibility Skip to Main Content Link -->
<a class="skip-link screen-reader-text" href="#primary">
	<?php esc_html_e( 'Skip to content', 'supplement-review' ); ?>
</a>

<!-- Sticky Main Header (Announcement Bar + Navbar) -->
<header id="masthead" class="site-header" role="banner">
	<!-- Top Announcement Bar -->
	<?php get_template_part( 'template-parts/announcement-bar' ); ?>

	<!-- Main Navbar -->
	<div class="sr-header-navbar">
		<div class="sr-container">
			<div class="sr-header-inner">
			<!-- Site Branding / Logo -->
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) :
					the_custom_logo();
				else :
					?>
					<div class="site-title">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
					</div>
					<?php
				endif;
				?>
			</div>

			<!-- Centered Primary Navigation -->
			<nav id="site-navigation" class="sr-main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'supplement-review' ); ?>">
				<?php
				if ( has_nav_menu( 'primary' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'sr-nav-menu',
							'container'      => false,
							'fallback_cb'    => false,
						)
					);
				} else {
					// Default Navigation matching design screenshot
					?>
					<ul id="primary-menu" class="sr-nav-menu">
						<li class="<?php echo is_front_page() ? 'current-menu-item' : ''; ?>">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'supplement-review' ); ?></a>
						</li>
						<li>
							<a href="<?php echo esc_url( home_url( '/category/weight-loss/' ) ); ?>"><?php esc_html_e( 'Weight Loss', 'supplement-review' ); ?></a>
						</li>
						<li>
							<a href="<?php echo esc_url( home_url( '/category/male-enhancement/' ) ); ?>"><?php esc_html_e( 'Male Enhancement', 'supplement-review' ); ?></a>
						</li>
						<li>
							<a href="<?php echo esc_url( home_url( '/category/brain-enhancement/' ) ); ?>"><?php esc_html_e( 'Brain Enhancement', 'supplement-review' ); ?></a>
						</li>
						<li>
							<a href="<?php echo esc_url( home_url( '/category/joint-pain-relief/' ) ); ?>"><?php esc_html_e( 'Joint Pain Relief', 'supplement-review' ); ?></a>
						</li>
					</ul>
					<?php
				}
				?>
			</nav>

			<!-- Right Action Tools: Search, Dark Mode, Mobile Hamburger -->
			<div class="sr-header-actions">
				<!-- Search Modal Trigger -->
				<button type="button" class="sr-icon-btn" id="srSearchOpenBtn" aria-label="<?php esc_attr_e( 'Open Search dialog', 'supplement-review' ); ?>" aria-haspopup="dialog" aria-expanded="false">
					<?php sr_svg( 'search', array( 'width' => 20, 'height' => 20 ) ); ?>
				</button>


				<!-- Mobile Hamburger Toggle -->
				<button type="button" class="sr-icon-btn sr-menu-toggle" id="srMenuToggleBtn" aria-label="<?php esc_attr_e( 'Open mobile navigation menu', 'supplement-review' ); ?>" aria-expanded="false" aria-controls="srMobileDrawer">
					<?php sr_svg( 'menu', array( 'width' => 22, 'height' => 22 ) ); ?>
				</button>
			</div><!-- .sr-header-actions -->
		</div><!-- .sr-header-inner -->
	</div><!-- .sr-container -->
</div><!-- .sr-header-navbar -->
</header>

<!-- Mobile Navigation Drawer -->
<div class="sr-drawer-overlay" id="srDrawerOverlay"></div>
<aside class="sr-mobile-drawer" id="srMobileDrawer" aria-hidden="true" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'Mobile Menu', 'supplement-review' ); ?>">
	<div class="sr-drawer-header">
		<span class="site-title" style="font-size:1.25rem;">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
		</span>
		<button type="button" class="sr-icon-btn" id="srDrawerCloseBtn" aria-label="<?php esc_attr_e( 'Close mobile menu', 'supplement-review' ); ?>">
			<?php sr_svg( 'close', array( 'width' => 20, 'height' => 20 ) ); ?>
		</button>
	</div>

	<nav class="sr-mobile-nav" role="navigation" aria-label="<?php esc_attr_e( 'Mobile Navigation', 'supplement-review' ); ?>">
		<?php
		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'menu_id'        => 'mobile-menu',
					'container'      => false,
					'fallback_cb'    => false,
				)
			);
		} else {
			?>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'supplement-review' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/category/weight-loss/' ) ); ?>"><?php esc_html_e( 'Weight Loss', 'supplement-review' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/category/male-enhancement/' ) ); ?>"><?php esc_html_e( 'Male Enhancement', 'supplement-review' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/category/brain-enhancement/' ) ); ?>"><?php esc_html_e( 'Brain Enhancement', 'supplement-review' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/category/joint-pain-relief/' ) ); ?>"><?php esc_html_e( 'Joint Pain Relief', 'supplement-review' ); ?></a></li>
			</ul>
			<?php
		}
		?>
	</nav>
</aside>

<!-- Search Overlay Modal -->
<?php get_template_part( 'template-parts/search-overlay' ); ?>

<div id="content" class="site-content">
