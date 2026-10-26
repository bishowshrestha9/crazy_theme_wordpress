<?php
get_header();
?>

<div class="main-content">
    <?php
    while (have_posts()):
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('single-content-wrap'); ?>>
            <header class="single-header">
                <div class="post-category" style="margin-bottom: 20px;">
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)) {
                        $cat_out = array();
                        foreach ($categories as $category) {
                            $cat_out[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
                        }
                        echo implode(', ', $cat_out);
                    } else {
                        echo 'Uncategorized';
                    }
                    ?>
                </div>

                <h1 class="entry-title" style="font-size: 2.8rem; margin-bottom: 15px;"><?php the_title(); ?></h1>

                <div class="single-meta">
                    <span>By <?php the_author(); ?></span>
                    <span>•</span>
                    <span><?php echo get_the_date(); ?></span>
                </div>
            </header>

            <?php if (has_post_thumbnail()): ?>
                <div class="post-thumbnail-single"
                    style="margin-bottom: 40px; border-radius: var(--radius); overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                    <?php the_post_thumbnail('full', array('style' => 'width: 100%; height: auto; display: block;')); ?>
                </div>
            <?php endif; ?>

            <div class="entry-content">
                <?php the_content(); ?>
            </div><!-- .entry-content -->
        </article><!-- #post-<?php the_ID(); ?> -->
        <?php
        // Comments
        if (comments_open() || get_comments_number()):
            // comments_template();
        endif;

    endwhile; // End of the loop.
    ?>
</div><!-- .main-content -->

<?php get_sidebar(); ?>

<?php
get_footer();
