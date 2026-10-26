<?php
get_header();
?>

<div class="main-content">
    <header class="page-header" style="text-align: center; margin-bottom: 50px;">
        <?php
        the_archive_title('<h1 class="page-title">', '</h1>');
        the_archive_description('<div class="archive-description" style="color:var(--text-muted); font-size:1.1rem;">', '</div>');
        ?>
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
                        <!-- Placeholder if no image -->
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
                        <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <div class="post-excerpt">
                            <?php the_excerpt(); ?>
                        </div>
                        <a href="<?php the_permalink(); ?>" class="read-more">Read Article</a>
                    </div>
                </article>
                <?php
            endwhile;

            echo '<div class="pagination-wrapper">';
            the_posts_navigation();
            echo '</div>';

        else:
            echo '<p>No content found in this category.</p>';
        endif;
        ?>
    </div><!-- .posts-grid -->
</div><!-- .main-content -->

<?php get_sidebar(); ?>

<?php
get_footer();
