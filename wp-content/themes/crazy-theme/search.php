<?php
/**
 * The template for displaying search results pages
 */

get_header();
?>

<style>
.search-page-wrapper {
	padding-top: 3rem;
}
.search-results-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}
.search-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    overflow: hidden;
}
.search-card-content {
	padding: 1.5rem;
	display: flex;
	flex-direction: column;
	flex: 1;
}
.search-card-img {
	width: 100%;
	height: 220px;
	object-fit: cover;
	display: block;
}
.search-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}
.search-card-title {
    font-size: 1.4rem;
    font-weight: 800;
    color: #1e293b;
    margin: 0 0 0.5rem 0;
    line-height: 1.3;
}
.search-card-title a {
    color: inherit;
    text-decoration: none;
}
.search-card-title a:hover {
    color: #0284c7;
}
.search-card-meta {
    font-size: 0.85rem;
    color: #64748b;
    margin-bottom: 1rem;
    font-weight: 600;
}
.search-card-excerpt {
    font-size: 1rem;
    color: #475569;
    line-height: 1.6;
    margin-bottom: 1.5rem;
    flex: 1;
}
.search-card-btn {
    display: inline-block;
    background: #0284c7;
    color: white;
    text-decoration: none;
    padding: 0.6rem 1.25rem;
    border-radius: 6px;
    font-weight: 700;
    text-align: center;
    transition: background 0.2s ease;
    align-self: flex-start;
}
.search-card-btn:hover {
    background: #005fa8;
}

[data-theme="dark"] .search-card {
	background: #1e1e1e;
	border-color: #333;
}
[data-theme="dark"] .search-card-title {
	color: #fff;
}
[data-theme="dark"] .search-card-meta, [data-theme="dark"] .search-card-excerpt {
	color: #aaa;
}

@media (max-width: 640px) {
    .search-page-wrapper {
        padding-top: 1.5rem;
    }
    .search-results-grid {
        grid-template-columns: 1fr;
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .search-card-img {
        height: 180px;
    }
    .search-card-content {
        padding: 1.25rem 1rem;
    }
    .search-card-title {
        font-size: 1.2rem;
    }
    .search-card-excerpt {
        font-size: 0.95rem;
        margin-bottom: 1.25rem;
    }
    .search-card-btn {
        width: 100%;
        text-align: center;
        padding: 0.75rem 1rem;
    }
}
</style>

<main id="primary" class="site-main search-page-wrapper">
    <div class="container">
        <?php if ( have_posts() ) : ?>
            
            <div class="search-results-grid">
                <?php
                while ( have_posts() ) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" class="search-card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php echo esc_url( get_permalink() ); ?>" style="display: block;">
                                <?php the_post_thumbnail( array( 500, 500 ), array( 'class' => 'search-card-img' ) ); ?>
                            </a>
                        <?php endif; ?>
                        
                        <div class="search-card-content">
                            <h2 class="search-card-title"><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a></h2>
                            <div class="search-card-meta">
                                By <?php the_author(); ?> &bull; <?php echo get_the_date(); ?>
                            </div>
                            <div class="search-card-excerpt">
                                <?php echo wp_trim_words( get_the_excerpt(), 25, '...' ); ?>
                            </div>
                            <a href="<?php echo esc_url( get_permalink() ); ?>" class="search-card-btn">Read More</a>
                        </div>
                    </article>
                    <?php
                endwhile;
                ?>
            </div>

            <?php 
            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => __( '« Prev', 'crazytheme' ),
                'next_text' => __( 'Next »', 'crazytheme' ),
            ) ); 
            ?>

        <?php else : ?>

            <div class="search-empty-state" style="text-align: center; padding: 6rem 1rem; max-width: 600px; margin: 0 auto;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="1.5" style="margin-bottom: 1.5rem; opacity: 0.8;">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    <line x1="11" y1="8" x2="11" y2="14"></line>
                    <line x1="8" y1="11" x2="14" y2="11"></line>
                </svg>
                <h2 style="font-size: 2rem; color: #1e293b; margin-bottom: 1rem; font-weight: 800;">No results found</h2>
                <p style="color: #64748b; font-size: 1.1rem; line-height: 1.6; margin-bottom: 2.5rem;">We couldn't find anything matching "<strong><?php echo get_search_query(); ?></strong>". Please try again with some different keywords.</p>
                <div style="max-width: 400px; margin: 0 auto;">
                    <?php get_search_form(); ?>
                </div>
            </div>

        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
