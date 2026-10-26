<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:ital,wght@0,400;0,700;1,400;1,700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<style>
/* Header Core Styles */
.site-header {
	background-color: #ffffff;
	border-bottom: 1px solid #e5e7eb;
	position: -webkit-sticky;
	position: sticky;
	top: 0;
	z-index: 999;
	transition: background-color 0.2s ease, border-color 0.2s ease;
}
body.admin-bar .site-header {
	top: 32px;
}
@media (max-width: 782px) {
	body.admin-bar .site-header {
		top: 46px;
	}
}
@media (max-width: 600px) {
	body.admin-bar .site-header {
		top: 0 !important;
	}
}
.site-header-inner {
	display: flex;
	align-items: center;
	justify-content: space-between;
	max-width: 1400px;
	margin: 0 auto;
	padding: 1.25rem 1.5rem;
	min-height: 82px;
	gap: 2rem;
	position: relative;
}
.site-branding {
	display: flex;
	align-items: center;
	flex-shrink: 0;
}
.site-branding img,
.site-branding .custom-logo {
	height: 52px;
	width: auto;
	max-width: 220px;
	display: block;
	object-fit: contain;
}
.site-branding a {
	display: inline-flex;
	align-items: center;
	text-decoration: none;
}

/* Dynamic Primary Navigation */
.main-navigation {
	display: flex;
	align-items: center;
	flex: 1;
	justify-content: flex-start;
	margin-left: 1.5rem;
	font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}
.primary-menu,
.main-navigation ul {
	display: flex;
	align-items: center;
	list-style: none;
	margin: 0;
	padding: 0;
	gap: 2rem;
	flex-wrap: wrap;
	font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}
.primary-menu li,
.main-navigation li {
	position: relative;
	margin: 0;
	padding: 0;
}
.primary-menu li a,
.main-navigation li a {
	font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
	font-size: 0.95rem;
	font-weight: 400;
	color: #1f2937;
	text-decoration: none;
	white-space: nowrap;
	padding: 0.5rem 0;
	display: inline-block;
	transition: color 0.2s ease;
	background: transparent;
	border: none;
}
.primary-menu li a:hover,
.primary-menu li.current-menu-item > a,
.primary-menu li.current_page_item > a,
.main-navigation li a:hover,
.main-navigation li.current-menu-item > a,
.main-navigation li.current_page_item > a {
	color: #0284c7;
	font-weight: 400;
}

/* Dropdown Sub-menus */
.primary-menu .sub-menu,
.main-navigation .children,
.main-navigation .sub-menu {
	display: none;
	position: absolute;
	top: 100%;
	left: 0;
	background: #ffffff;
	min-width: 200px;
	padding: 0.5rem 0;
	border-radius: 8px;
	box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
	border: 1px solid #e5e7eb;
	z-index: 100;
	flex-direction: column;
	gap: 0;
}
.primary-menu li:hover > .sub-menu,
.main-navigation li:hover > .children,
.main-navigation li:hover > .sub-menu {
	display: flex;
}
.primary-menu .sub-menu li a,
.main-navigation .children li a,
.main-navigation .sub-menu li a {
	padding: 0.6rem 1.2rem;
	font-size: 0.9rem;
	color: #374151;
	display: block;
	width: 100%;
	box-sizing: border-box;
}
.primary-menu .sub-menu li a:hover,
.main-navigation .children li a:hover,
.main-navigation .sub-menu li a:hover {
	background-color: #f3f4f6;
	color: #0284c7;
}

/* Header Right Actions */
.header-actions {
	display: flex;
	align-items: center;
	gap: 14px;
	flex-shrink: 0;
	position: relative;
}

/* Search Icon Button */
.search-toggle-btn {
	background: none;
	border: none;
	padding: 6px;
	cursor: pointer;
	color: #111827;
	display: flex;
	align-items: center;
	justify-content: center;
	border-radius: 50%;
	transition: opacity 0.2s, background-color 0.2s, transform 0.15s;
}
.search-toggle-btn svg,
.theme-toggle-btn svg,
.theme-toggle-circle {
	pointer-events: none;
}
.search-toggle-btn:hover {
	background-color: rgba(0, 0, 0, 0.05);
	opacity: 0.85;
}
.search-toggle-btn:active {
	transform: scale(0.95);
}

/* Expandable Search Dropdown */
.header-search-wrap {
	position: relative;
	display: flex;
	align-items: center;
}
.header-search-dropdown {
	position: absolute;
	right: 0;
	top: calc(100% + 12px);
	background: #ffffff;
	border: 1px solid #e5e7eb;
	border-radius: 30px;
	box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.06);
	padding: 5px 6px 5px 14px;
	display: flex;
	align-items: center;
	min-width: 280px;
	opacity: 0;
	visibility: hidden;
	pointer-events: none;
	transform: translateY(-8px) scale(0.96);
	transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
	z-index: 9999;
}
.header-search-wrap.is-open .header-search-dropdown {
	opacity: 1 !important;
	visibility: visible !important;
	pointer-events: auto !important;
	transform: translateY(0) scale(1) !important;
}
.header-search-dropdown input {
	border: none;
	background: transparent;
	outline: none;
	flex: 1;
	font-size: 0.9rem;
	color: #111827;
	width: 100%;
	padding: 6px 4px;
}
.header-search-dropdown input::placeholder {
	color: #9ca3af;
}
.header-search-dropdown .search-submit-icon {
	background: #0284c7;
	border: none;
	color: #ffffff;
	border-radius: 50%;
	width: 32px;
	height: 32px;
	display: flex;
	align-items: center;
	justify-content: center;
	cursor: pointer;
	flex-shrink: 0;
	transition: background-color 0.2s, transform 0.15s;
}
.header-search-dropdown .search-submit-icon:hover {
	background-color: #005fa8;
}
.header-search-dropdown .search-submit-icon:active {
	transform: scale(0.95);
}

/* Theme Toggle Button (Circular soft-grey circle + crescent moon matching screenshot) */
.theme-toggle-btn {
	background: none;
	border: none;
	padding: 0;
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: center;
}
.theme-toggle-circle {
	width: 30px;
	height: 30px;
	border-radius: 50%;
	background-color: #cbd5e1;
	display: flex;
	align-items: center;
	justify-content: center;
	color: #ffffff;
	transition: all 0.2s ease;
}
.theme-toggle-btn:hover .theme-toggle-circle {
	background-color: #94a3b8;
	transform: scale(1.06);
}

/* Mobile Menu Button */
.mobile-menu-btn {
	display: none;
	background: none;
	border: none;
	color: #111827;
	padding: 6px;
	cursor: pointer;
	border-radius: 6px;
	align-items: center;
	justify-content: center;
}

/* Dark Mode Overrides */
[data-theme="dark"] body, 
[data-theme="dark"] .site-header { 
	background-color: #121212 !important; 
	color: #f1f1f1 !important;
}
[data-theme="dark"] .site-header { border-bottom-color: #27272a !important; }
[data-theme="dark"] a:not(.footer-top-btn):not(.product-btn):not(.scorecard-btn) { color: #f1f1f1 !important; }
[data-theme="dark"] .primary-menu li a,
[data-theme="dark"] .main-navigation li a,
body.dark-mode .primary-menu li a,
body.dark-mode .main-navigation li a {
	color: #e5e7eb !important;
	font-weight: 400 !important;
}
[data-theme="dark"] .primary-menu li a:hover,
[data-theme="dark"] .primary-menu li.current-menu-item > a,
[data-theme="dark"] .main-navigation li a:hover,
[data-theme="dark"] .main-navigation li.current-menu-item > a,
body.dark-mode .primary-menu li a:hover,
body.dark-mode .main-navigation li a:hover {
	color: #34d399 !important;
	font-weight: 400 !important;
}
[data-theme="dark"] .search-toggle-btn,
body.dark-mode .search-toggle-btn {
	color: #f3f4f6;
}
[data-theme="dark"] .search-toggle-btn:hover,
body.dark-mode .search-toggle-btn:hover {
	background-color: rgba(255, 255, 255, 0.1);
}
[data-theme="dark"] .header-search-dropdown,
body.dark-mode .header-search-dropdown {
	background: #1f2937;
	border-color: #374151;
	box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.6);
}
[data-theme="dark"] .header-search-dropdown input,
body.dark-mode .header-search-dropdown input {
	color: #f3f4f6;
}
[data-theme="dark"] .theme-toggle-circle,
body.dark-mode .theme-toggle-circle {
	background-color: #374151;
	color: #fbbf24;
}
[data-theme="dark"] .theme-toggle-btn:hover .theme-toggle-circle,
body.dark-mode .theme-toggle-btn:hover .theme-toggle-circle {
	background-color: #4b5563;
}
[data-theme="dark"] .mobile-menu-btn,
body.dark-mode .mobile-menu-btn {
	color: #f3f4f6;
}
[data-theme="dark"] .cat-card,
[data-theme="dark"] .scorecard-wrapper,
[data-theme="dark"] .scorecard-header,
[data-theme="dark"] .scorecard-bars,
[data-theme="dark"] .scorecard-footer,
[data-theme="dark"] .scorecard-info,
[data-theme="dark"] .compare-table,
[data-theme="dark"] .toc-box,
[data-theme="dark"] .neu-sidebar-card,
[data-theme="dark"] .social-mockup {
	background-color: #1e1e1e !important;
	border-color: #333 !important;
	color: #e0e0e0 !important;
}
[data-theme="dark"] .score-bar-head,
[data-theme="dark"] h1, [data-theme="dark"] h2, [data-theme="dark"] h3, [data-theme="dark"] .scorecard-title {
	color: #ffffff !important;
}
[data-theme="dark"] .top-bar { background-color: #0284c7 !important; color: #fff !important; }
[data-theme="dark"] .pros-box { background: #052e16 !important; border-color: #166534 !important; }
[data-theme="dark"] .cons-box { background: #431407 !important; border-color: #9a3412 !important; }
[data-theme="dark"] .pros-list li { color: #bbf7d0 !important; }
[data-theme="dark"] .cons-list li { color: #fed7aa !important; }

/* Responsive adjustments */
@media (max-width: 1024px) {
	.mobile-menu-btn { display: flex; }
	.main-navigation {
		display: none;
		position: absolute;
		top: 100%;
		left: 0;
		right: 0;
		background: #ffffff;
		border-top: 1px solid #e5e7eb;
		box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
		padding: 1.25rem 1.5rem;
		margin-left: 0;
		z-index: 100;
	}
	.main-navigation.is-active { display: block; }
	.primary-menu,
	.main-navigation ul {
		flex-direction: column;
		align-items: flex-start;
		gap: 0.75rem;
		width: 100%;
	}
	.primary-menu li,
	.main-navigation li { width: 100%; }
	.primary-menu li a,
	.main-navigation li a {
		padding: 0.5rem 0;
		font-size: 1.05rem;
		width: 100%;
	}
	[data-theme="dark"] .main-navigation,
	body.dark-mode .main-navigation {
		background: #18181b;
		border-top-color: #27272a;
	}
	.header-search-dropdown {
		min-width: 250px;
		right: 0;
	}
}

@media (max-width: 768px) {
	.site-header-inner {
		padding: 0.65rem 1rem;
		min-height: 64px;
		gap: 0.75rem;
	}
	.site-branding img,
	.site-branding .custom-logo {
		height: 38px;
		max-width: 170px;
	}
	.header-actions {
		gap: 8px;
	}
	.header-search-dropdown {
		position: fixed;
		top: 64px;
		left: 10px;
		right: 10px;
		width: auto;
		min-width: 0;
		border-radius: 12px;
		box-shadow: 0 10px 25px rgba(0,0,0,0.18);
		z-index: 1000;
	}
	.header-search-dropdown input {
		width: 100%;
		font-size: 16px !important;
	}
	.top-bar {
		font-size: 0.78rem !important;
		padding: 6px 10px !important;
	}
}

@media (max-width: 480px) {
	.site-branding img,
	.site-branding .custom-logo {
		height: 32px;
		max-width: 135px;
	}
}
</style>
<div class="top-bar" style="background-color: #0284c7; text-align: center; padding: 8px; font-size: 0.85rem; color: #ffffff; font-weight: 700;">
	Special Christmas Offer - 50%
</div>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'crazytheme' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="site-header-inner container">
			<div class="site-branding">
				<?php if ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="custom-logo-link">
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo.png" alt="<?php bloginfo( 'name' ); ?>" class="custom-logo" style="height: 48px; width: auto; display: block;">
					</a>
				<?php endif; ?>
			</div>

			<!-- Dynamic WordPress Primary Menu -->
			<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'crazytheme' ); ?>">
				<?php
				$theme_location = has_nav_menu( 'primary' ) ? 'primary' : ( has_nav_menu( 'menu-1' ) ? 'menu-1' : '' );
				if ( $theme_location ) {
					wp_nav_menu(
						array(
							'theme_location' => $theme_location,
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'primary-menu',
							'container'      => false,
							'fallback_cb'    => false,
						)
					);
				} else {
					wp_nav_menu(
						array(
							'theme_location' => 'primary',
							'menu_id'        => 'primary-menu',
							'menu_class'     => 'primary-menu',
							'container'      => false,
							'fallback_cb'    => function() {
								$menus = wp_get_nav_menus();
								if ( ! empty( $menus ) ) {
									wp_nav_menu( array(
										'menu'       => $menus[0]->term_id,
										'menu_id'    => 'primary-menu',
										'menu_class' => 'primary-menu',
										'container'  => false,
									) );
								} else {
									wp_page_menu( array(
										'menu_class' => 'primary-menu',
										'show_home'  => true,
									) );
								}
							},
						)
					);
				}
				?>
			</nav>

			<!-- Header Right Actions: Search Icon + Theme Toggle (Matching Screenshot) -->
			<div class="header-actions">
				<!-- Search -->
				<div class="header-search-wrap" id="header-search-wrap">
					<button id="search-toggle-btn" class="header-icon-btn search-toggle-btn" aria-label="<?php esc_attr_e( 'Search', 'crazytheme' ); ?>" title="<?php esc_attr_e( 'Search', 'crazytheme' ); ?>" type="button">
						<svg class="search-open-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="11" cy="11" r="8"></circle>
							<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
						</svg>
						<svg class="search-close-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
							<line x1="18" y1="6" x2="6" y2="18"></line>
							<line x1="6" y1="6" x2="18" y2="18"></line>
						</svg>
					</button>
					<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="header-search-dropdown" id="header-search-form">
						<input type="text" name="s" id="header-search-input" placeholder="<?php esc_attr_e( 'Search product reviews...', 'crazytheme' ); ?>" value="<?php echo get_search_query(); ?>" autocomplete="off">
						<button type="submit" class="search-submit-icon" aria-label="<?php esc_attr_e( 'Submit search', 'crazytheme' ); ?>" title="<?php esc_attr_e( 'Submit search', 'crazytheme' ); ?>">
							<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="11" cy="11" r="8"></circle>
								<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
							</svg>
						</button>
					</form>
				</div>

				<!-- Mobile Hamburger Button -->
				<button id="mobile-menu-btn" class="mobile-menu-btn" aria-label="<?php esc_attr_e( 'Toggle navigation menu', 'crazytheme' ); ?>" type="button">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<line x1="3" y1="12" x2="21" y2="12"></line>
						<line x1="3" y1="6" x2="21" y2="6"></line>
						<line x1="3" y1="18" x2="21" y2="18"></line>
					</svg>
				</button>
			</div>
		</div>
	</header><!-- #masthead -->
