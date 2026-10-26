<?php
/**
 * Displays header site navigation
 *
 * @package CrazyTheme
 */
?>
<nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e( 'Primary menu', 'crazytheme' ); ?>">
	<button id="menu-toggle" class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
		<span class="screen-reader-text"><?php esc_html_e( 'Primary menu', 'crazytheme' ); ?></span>
		<svg class="icon-menu" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
		<svg class="icon-close" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
	</button>

	<div class="menu-wrapper">
		<?php
		$theme_location = has_nav_menu( 'primary' ) ? 'primary' : ( has_nav_menu( 'menu-1' ) ? 'menu-1' : 'primary' );
		wp_nav_menu(
			array(
				'theme_location' => $theme_location,
				'menu_id'        => 'primary-menu',
				'menu_class'     => 'primary-menu',
				'container'      => false,
				'fallback_cb'    => 'wp_page_menu',
			)
		);
		?>
	</div>
</nav><!-- #site-navigation -->
