<?php
get_header();
?>

<div class="main-content">
    <?php
    while (have_posts()):
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('page-content-wrap'); ?>>
            <header class="single-header">
                <h1 class="entry-title page-title"><?php the_title(); ?></h1>
            </header>

            <?php if (has_post_thumbnail()): ?>
                <div class="page-thumbnail" style="margin-bottom: 40px; border-radius: var(--radius); overflow: hidden;">
                    <?php the_post_thumbnail('full', array('style' => 'width: 100%; height: auto; display: block;')); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content">
                <?php the_content(); ?>
            </div><!-- .entry-content -->
        </article><!-- #post-<?php the_ID(); ?> -->
        <?php
    endwhile;
    ?>
</div><!-- .main-content -->

<?php get_sidebar(); ?>

<?php
get_footer();
