<?php
get_header();
?>

<div class="main-content">
    <header class="page-header" style="text-align:center; margin-bottom: 50px;">
        <h1 class="page-title">
            <?php
            printf(esc_html__('Search Results for: %s', 'supplement-tiger'), '<span>' . get_search_query() . '</span>');
            ?>
        </h1>
    </header>

    <div class="posts-grid">
        <?php if (have_posts()): ?>
            <?php
            while (have_posts()):
                the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
                    <?php if (has_post_thumbnail()): ?>
                        <a class="post-thumbnail" href="<?php the_permalink(); ?>">
                            <?php the_post_thumbnail('large'); ?>
                        </a>
                    <?php else: ?>
                        <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                            <img src="https://via.placeholder.com/600x400/F1F5F9/94A3B8?text=Supplement+Tiger" alt="Placeholder">
                        </a>
                    <?php endif; ?>

                    <div class="post-content">
                        <div class="post-category">
                            <?php
                            $categories = get_the_category();
                            if (!empty($categories)) {
                                echo esc_html($categories[0]->name);
                            } else {
                                echo 'Uncategorized';
                            }
                            ?>
                        </div>
                        <h2 class="post-title" style="font-size:1.2rem;"><a
                                href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="post-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                    </div>
                </article>
                <?php
            endwhile;

            echo '<div class="pagination-wrapper">';
            the_posts_navigation();
            echo '</div>';

        else:
            echo '<p style="text-align:center; padding: 50px;">Sorry, but nothing matched your search terms. Please try again with some different keywords.</p>';
            echo '<div style="max-width: 400px; margin: 0 auto;">';
            get_search_form();
            echo '</div>';
        endif;
        ?>
    </div><!-- .posts-grid -->
</div><!-- .main-content -->

<?php get_sidebar(); ?>

<?php
get_footer();
