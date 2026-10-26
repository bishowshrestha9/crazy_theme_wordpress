<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <div id="page" class="site">
        <header id="masthead" class="site-header">
            <div class="container header-inner">
                <div class="site-branding">
                    <?php
                    if (has_custom_logo()):
                        the_custom_logo();
                    else:
                        ?>
                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                            <?php bloginfo('name'); ?>
                        </a>
                        <?php
                    endif;
                    ?>
                </div><!-- .site-branding -->

                <nav id="site-navigation" class="main-navigation">
                    <?php
                    if (has_nav_menu('primary')) {
                        wp_nav_menu(array(
                            'theme_location' => 'primary',
                            'menu_id' => 'primary-menu',
                            'container' => false,
                        ));
                    } else {
                        // Fallback if no menu created
                        echo '<ul>';
                        echo '<li><a href="' . esc_url(home_url('/')) . '">Home</a></li>';
                        wp_list_pages(array(
                            'title_li' => '',
                            'depth' => 1,
                            'number' => 4,
                        ));
                        // Static fallbacks as purely requested by user
                        echo '<li><a href="' . esc_url(home_url('/blogs')) . '">Blogs</a></li>';
                        echo '<li class="buy-now-btn"><a href="#">Buy Now</a></li>';
                        echo '</ul>';
                    }
                    ?>
                </nav><!-- #site-navigation -->

                <div class="header-search">
                    <?php get_search_form(); ?>
                </div>
            </div>
        </header><!-- #masthead -->

        <main id="primary" class="site-main">
            <div class="container">