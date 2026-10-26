<?php
/**
 * Displays header site branding
 *
 * @package CrazyTheme
 */
?>
<div class="site-branding">
	<?php if ( has_custom_logo() ) : ?>
		<div class="site-logo"><?php the_custom_logo(); ?></div>
	<?php else : ?>
		<?php if ( is_front_page() && is_home() ) : ?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		<?php else : ?>
			<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
		<?php endif; ?>
		<?php
		$crazytheme_description = get_bloginfo( 'description', 'display' );
		if ( $crazytheme_description || is_customize_preview() ) :
			?>
			<p class="site-description"><?php echo esc_html( $crazytheme_description ); ?></p>
		<?php endif; ?>
	<?php endif; ?>
</div><!-- .site-branding -->
