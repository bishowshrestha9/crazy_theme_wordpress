<?php
/**
 * The template for displaying 404 pages (not found)
 */

get_header();
?>

<style>
.error-404 {
    text-align: center;
    padding: 6rem 1rem;
}
.error-404-title {
    font-size: clamp(3.75rem, 18vw, 7.5rem);
    font-weight: 800;
    color: #0284c7;
    margin: 0 0 1rem;
    line-height: 1;
}
.error-404-subtitle {
    font-size: clamp(1.4rem, 5vw, 2rem);
    color: #1e293b;
    margin: 0 0 1.5rem;
    font-weight: 700;
}
.error-404-desc {
    font-size: 1.1rem;
    color: #64748b;
    max-width: 500px;
    margin: 0 auto 2.5rem;
    line-height: 1.6;
}
.error-404-btn {
    display: inline-block;
    background: #0284c7;
    color: white;
    text-decoration: none;
    padding: 0.8rem 2rem;
    border-radius: 6px;
    font-weight: 700;
    font-size: 1.1rem;
    transition: background 0.2s ease;
}
.error-404-btn:hover {
    background: #0274b3;
    color: white;
}

@media (max-width: 640px) {
    .error-404 {
        padding: 3.5rem 1rem;
    }
    .error-404-desc {
        font-size: 1rem;
        margin-bottom: 2rem;
    }
    .error-404-btn {
        width: 100%;
        max-width: 280px;
        box-sizing: border-box;
    }
}

[data-theme="dark"] .error-404-subtitle {
	color: #fff;
}
[data-theme="dark"] .error-404-desc {
	color: #aaa;
}
</style>

<main id="primary" class="site-main">
    <div class="container">
        <section class="error-404 not-found">
            <h1 class="error-404-title">404</h1>
            <h2 class="error-404-subtitle">Oops! Page not found</h2>
            <p class="error-404-desc">It looks like nothing was found at this location. The page might have been removed, had its name changed, or is temporarily unavailable.</p>
            <div style="margin-bottom: 2rem; max-width: 400px; margin-left: auto; margin-right: auto;">
                <?php get_search_form(); ?>
            </div>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="error-404-btn">Back to Homepage</a>
        </section>
    </div>
</main>

<?php
get_footer();
