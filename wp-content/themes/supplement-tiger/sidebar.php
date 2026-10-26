<?php
/**
 * The sidebar containing the main widget area.
 */
?>

<aside id="secondary" class="site-sidebar widget-area">
    <?php if (is_single()): ?>
        <section class="sidebar-widget widget-related-posts">
            <h3 class="widget-title">Related Posts</h3>
            <ul class="widget-post-list">
                <?php
                global $post;
                $categories = get_the_category($post->ID);
                if ($categories) {
                    $category_ids = array();
                    foreach ($categories as $individual_category)
                        $category_ids[] = $individual_category->term_id;
                    $args = array(
                        'category__in' => $category_ids,
                        'post__not_in' => array($post->ID),
                        'posts_per_page' => 4,
                        'ignore_sticky_posts' => 1,
                        'orderby' => 'rand'
                    );
                    $my_query = new WP_Query($args);
                    if ($my_query->have_posts()) {
                        while ($my_query->have_posts()) {
                            $my_query->the_post();
                            ?>
                            <li class="widget-list-item">
                                <?php if (has_post_thumbnail()): ?>
                                    <a href="<?php the_permalink(); ?>" class="widget-thumb">
                                        <?php the_post_thumbnail('thumbnail'); ?>
                                    </a>
                                <?php else: ?>
                                    <div class="widget-thumb widget-thumb-placeholder"></div>
                                <?php endif; ?>
                                <div class="widget-post-info">
                                    <h4><a href="<?php the_permalink(); ?>">
                                            <?php echo wp_trim_words(get_the_title(), 7); ?>
                                        </a></h4>
                                    <span class="widget-date">
                                        <?php echo get_the_date('M d, Y'); ?>
                                    </span>
                                </div>
                            </li>
                            <?php
                        }
                        wp_reset_postdata();
                    } else {
                        echo '<li>No related posts found.</li>';
                    }
                }
                ?>
            </ul>
        </section>
    <?php endif; ?>

    <section class="sidebar-widget widget-latest-posts">
        <h3 class="widget-title">
            <?php echo is_single() ? 'Latest Articles' : 'Recent Updates'; ?>
        </h3>
        <ul class="widget-post-list">
            <?php
            $recent_posts = new WP_Query(array(
                'posts_per_page' => 5,
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1
            ));
            if ($recent_posts->have_posts()):
                while ($recent_posts->have_posts()):
                    $recent_posts->the_post();
                    ?>
                    <li class="widget-list-item">
                        <?php if (has_post_thumbnail()): ?>
                            <a href="<?php the_permalink(); ?>" class="widget-thumb">
                                <?php the_post_thumbnail('thumbnail'); ?>
                            </a>
                        <?php else: ?>
                            <div class="widget-thumb widget-thumb-placeholder"></div>
                        <?php endif; ?>
                        <div class="widget-post-info">
                            <h4><a href="<?php the_permalink(); ?>">
                                    <?php echo wp_trim_words(get_the_title(), 7); ?>
                                </a></h4>
                            <span class="widget-date">
                                <?php echo get_the_date('M d, Y'); ?>
                            </span>
                        </div>
                    </li>
                    <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </ul>
    </section>

    <?php if (is_active_sidebar('main-sidebar')): ?>
        <?php dynamic_sidebar('main-sidebar'); ?>
    <?php endif; ?>
</aside><!-- #secondary -->