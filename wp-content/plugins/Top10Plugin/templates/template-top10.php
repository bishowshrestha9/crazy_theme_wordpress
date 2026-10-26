<?php
/**
 * Template Name: Top 10 Page
 * Template for displaying Top 10 product pages
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

global $post;
$post_id = $post->ID;


$hero_title = get_post_meta($post_id, '_top10_hero_title', true);
$hero_subtitle = get_post_meta($post_id, '_top10_hero_subtitle', true);
$sidebar_text = get_post_meta($post_id, '_top10_sidebar_text', true) ?: 'TOP PICKS';
$products = get_post_meta($post_id, '_top10_products', true) ?: array(); // Direct fetch
$faqs = get_post_meta($post_id, '_top10_faqs', true) ?: array();
$buying_guide = get_post_meta($post_id, '_top10_buying_guide', true) ?: array();
$research_intro = get_post_meta($post_id, '_top10_research_intro', true);
$research_criteria = get_post_meta($post_id, '_top10_research_criteria', true) ?: array();
$research_sources = get_post_meta($post_id, '_top10_research_sources', true);
$conclusion = get_post_meta($post_id, '_top10_conclusion', true);
$theme_color = get_post_meta($post_id, '_top10_theme_color', true) ?: 'blue';
$page_layout = get_post_meta($post_id, '_top10_page_layout', true) ?: 'product';

// Theme color mapping
$theme_colors = array(
    'blue' => '#6BA3F8',
    'green' => '#10B981',
    'red' => '#EF4444',
    'black' => '#1F2937',
    'white' => '#FFFFFF'
);
$primary_color = $theme_colors[$theme_color];

// Get section toggles
$show_comparison = get_post_meta($post_id, '_top10_show_comparison', true) !== '0';
$show_faqs = get_post_meta($post_id, '_top10_show_faqs', true) !== '0';
$show_buying_guide = get_post_meta($post_id, '_top10_show_buying_guide', true) !== '0';

get_header();
?>

<style>
    .top10-page-wrapper {
        --primary-color:
            <?php echo esc_attr($primary_color); ?>
        ;
        --primary-color-dark:
            <?php echo esc_attr($theme_color === 'white' ? '#E5E7EB' : $primary_color); ?>
        ;
    }

    /* Apply theme color */
    .table-of-contents,
    .top10-sidebar-box,
    .btn-buy-now,
    .choice-badge,
    .product-card-header {
        background: var(--primary-color) !important;
        <?php if ($theme_color === 'white'): ?>
            color: #1F2937 !important;
        <?php elseif ($theme_color === 'black'): ?>
            color: #FFFFFF !important;
        <?php endif; ?>
    }

    .product-card-header h3,
    .product-card-header .product-price,
    .product-card-header .product-rating {
        <?php if ($theme_color === 'white'): ?>
            color: #1F2937 !important;
        <?php elseif ($theme_color === 'black'): ?>
            color: #FFFFFF !important;
        <?php else: ?>
            color: #FFFFFF !important;
        <?php endif; ?>
    }

    .toc-list li a {
        <?php if ($theme_color === 'white'): ?>
            color: #1F2937 !important;
        <?php elseif ($theme_color === 'black'): ?>
            color: #FFFFFF !important;
        <?php endif; ?>
    }

    .toc-list li a:hover {
        <?php if ($theme_color === 'white'): ?>
            background: #F3F4F6 !important;
        <?php else: ?>
            background: rgba(255, 255, 255, 0.2) !important;
        <?php endif; ?>
    }

    .table-of-contents h3 {
        <?php if ($theme_color === 'white'): ?>
            color: #1F2937 !important;
        <?php elseif ($theme_color === 'black'): ?>
            color: #FFFFFF !important;
        <?php endif; ?>
    }

    <?php if ($theme_color === 'white'): ?>
        .table-of-contents,
        .product-card-header {
            border: 2px solid #E5E7EB !important;
        }

    <?php endif; ?>
</style>

<div class="top10-page-wrapper">

    <!-- Hero Section with Background -->
    <section class="top10-hero"
        style="background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('<?php echo get_the_post_thumbnail_url($post_id, 'full') ?: 'https://picsum.photos/seed/hero-bg/1920/400'; ?>');">
        <div class="top10-hero-content">
            <h1 class="hero-title"><?php echo esc_html($hero_title ?: get_the_title()); ?></h1>
            <?php if ($hero_subtitle): ?>
                <p class="hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
            <?php elseif ($post->post_content): ?>
                <p class="hero-subtitle"><?php echo esc_html(wp_trim_words($post->post_content, 25)); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Main Content with Sidebar -->
    <div class="top10-main-layout">

        <!-- Left Sidebar - Table of Contents -->
        <aside class="top10-sidebar">
            <div class="sidebar-sticky">
                <div class="table-of-contents">
                    <h3>Table Of Content</h3>
                    <ul class="toc-list">
                        <li><a href="#top-choices">Top Choice</a></li>
                        <li><a href="#detailed-analysis">Detailed Analysis</a></li>
                        <li><a href="#comparison">Comparison Table</a></li>
                        <li><a href="#buying-guide">Things to consider before buying</a></li>
                        <li><a href="#research">How We Research</a></li>
                        <li><a href="#conclusion">Conclusion</a></li>
                        <li><a href="#faqs">FAQs</a></li>
                    </ul>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="top10-content">

            <!-- Top Choices Section with Carousel -->
            <?php if (!empty($products)): ?>
                <section id="top-choices" class="top-choices-section">
                    <h2 class="section-title">TOP CHOICES: <?php echo esc_html(get_the_title()); ?></h2>

                    <div class="carousel-container">
                        <div class="top-choices-carousel">
                            <?php foreach ($products as $index => $product):
                                // Skip empty rows
                                if (empty($product['name']))
                                    continue;
                                ?>
                                <div class="choice-card">
                                    <?php if (!empty($product['badge_text'])): ?>
                                        <div class="choice-badge"><?php echo esc_html($product['badge_text']); ?></div>
                                    <?php endif; ?>

                                    <h3 class="choice-title"><?php echo esc_html($product['name']); ?></h3>
                                    <p class="choice-price"><?php echo esc_html($product['price']); ?></p>

                                    <div class="choice-rating">
                                        <span class="stars">★★★★★</span>
                                        <span
                                            class="rating-number"><?php echo number_format((float) $product['rating'], 1); ?>/10</span>
                                        <?php if (!empty($product['review_link'])): ?>
                                            <a href="<?php echo esc_url($product['review_link']); ?>" class="read-review">Read
                                                Review</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Carousel Progress Bar -->
                    <div class="carousel-progress-wrapper">
                        <div class="carousel-progress-bar"></div>
                    </div>

                    <div class="carousel-nav">
                        <button class="carousel-prev">‹</button>
                        <button class="carousel-next">›</button>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Detailed Product Analysis -->
            <?php if (!empty($products)): ?>
                <section id="detailed-analysis" class="detailed-products-section">
                    <h2 class="section-title">Detailed Analysis</h2>

                    <?php foreach ($products as $index => $product):
                        if (empty($product['name']))
                            continue;
                        $rank = $index + 1;
                        ?>
                        <div class="detailed-product-card" id="product-<?php echo $rank; ?>">
                            <!-- Blue Header -->
                            <div class="product-number-badge"><?php echo $rank; ?>. <?php echo esc_html($product['name']); ?>
                                (<?php echo esc_html($product['price']); ?>)</div>

                            <!-- White Content Area -->
                            <div class="product-card-content">
                                <div class="product-two-column">
                                    <!-- Left Column: No Image as requested -->
                                    <div class="product-left-column" style="display:none;"></div>

                                    <!-- Right Column: Description + Badges -->
                                    <div class="product-right-column" style="width: 100%;">
                                        <?php if (!empty($product['best_for'])): ?>
                                            <div class="product-description">
                                                <strong>Overview:</strong>
                                                <p><?php echo nl2br(esc_html($product['best_for'])); ?></p>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Rating Badges -->
                                        <div class="rating-badges-row">
                                            <div class="rating-badge-item">
                                                <div class="badge-stars-yellow">★★★★★</div>
                                                <div class="badge-score"><?php echo esc_html($product['rating']); ?>/10</div>
                                                <div class="badge-platform">Our Rating</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="action-buttons">
                                    <?php if (!empty($product['buy_link'])): ?>
                                        <a href="<?php echo esc_url($product['buy_link']); ?>" class="btn-buy-now" target="_blank"
                                            rel="noopener">
                                            Buy Now
                                        </a>
                                    <?php endif; ?>
                                    <?php if (!empty($product['review_link'])): ?>
                                        <a href="<?php echo esc_url($product['review_link']); ?>" class="btn-read-reviews">Read
                                            Review</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>

            <!-- Comparison Table -->
            <?php if (!empty($products)): ?>
                <section id="comparison" class="comparison-table-section">
                    <h2 class="section-title">Comparison Table</h2>

                    <div class="comparison-table-wrapper">
                        <table class="comparison-table-figma">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Expert Rating</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $index => $product):
                                    if (empty($product['name']))
                                        continue;
                                    ?>
                                    <tr>
                                        <td class="product-name-cell"><?php echo esc_html($product['name']); ?></td>
                                        <td><span class="price-badge"><?php echo esc_html($product['price']); ?></span></td>
                                        <td><span
                                                class="expert-badge blue"><?php echo esc_html($product['rating']); ?>/10</span>
                                        </td>
                                        <td>
                                            <?php if (!empty($product['buy_link'])): ?>
                                                <a href="<?php echo esc_url($product['buy_link']); ?>"
                                                    style="font-weight:bold; color:var(--primary-color);">Buy Now</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Things to Consider Before Buying -->
            <?php if ($show_buying_guide && !empty($buying_guide)): ?>
                <section id="buying-guide" class="buying-guide-section">
                    <h2 class="section-title">THINGS TO CONSIDER BEFORE BUYING <?php echo esc_html(get_the_title()); ?></h2>

                    <?php foreach ($buying_guide as $item): ?>
                        <div class="consideration-item">
                            <div class="consideration-bar"></div>
                            <div class="consideration-content">
                                <h3><?php echo esc_html($item['title']); ?></h3>
                                <p><?php echo esc_html($item['description']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </section>
            <?php endif; ?>

            <!-- How We Researched & Rated -->
            <?php if (!empty($research_intro) || !empty($research_criteria)): ?>
                <section id="research" class="research-methodology-section">
                    <h2 class="section-title">HOW WE RESEARCHED & RATED THE <?php echo esc_html(get_the_title()); ?></h2>

                    <?php if ($research_intro): ?>
                        <p class="methodology-intro"><?php echo esc_html($research_intro); ?></p>
                    <?php endif; ?>

                    <?php if (!empty($research_criteria)): ?>
                        <div class="criteria-table">
                            <h3>Expert Rating Criteria Table</h3>
                            <table class="criteria-grid">
                                <thead>
                                    <tr>
                                        <th>Criteria</th>
                                        <th>Weight</th>
                                        <th>Test Method</th>
                                        <th>Top Score</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($research_criteria as $criteria): ?>
                                        <tr>
                                            <td><?php echo esc_html($criteria['criteria']); ?></td>
                                            <td><?php echo esc_html($criteria['weight']); ?></td>
                                            <td><?php echo esc_html($criteria['method']); ?></td>
                                            <td>10/10</td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php if ($research_sources): ?>
                                <p class="data-sources"><?php echo esc_html($research_sources); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endif; ?>

            <!-- Conclusion -->
            <?php if (!empty($conclusion)): ?>
                <section id="conclusion" class="conclusion-section">
                    <h2 class="section-title">CONCLUSION</h2>

                    <p><?php echo esc_html($conclusion); ?></p>
                </section>
            <?php endif; ?>

            <!-- FAQs Section -->
            <?php if ($show_faqs && !empty($faqs)): ?>
                <section id="faqs" class="faqs-section">
                    <h2 class="section-title">Frequently Asked Questions</h2>

                    <div class="faq-accordion-figma">
                        <?php foreach ($faqs as $faq): ?>
                            <div class="faq-item-figma">
                                <button class="faq-question-figma">
                                    <span><?php echo esc_html($faq['question']); ?></span>
                                    <span class="faq-toggle">›</span>
                                </button>
                                <div class="faq-answer-figma">
                                    <p><?php echo esc_html($faq['answer']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>

        </div><!-- .top10-content -->

    </div><!-- .top10-main-layout -->

</div><!-- .top10-page-wrapper -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // FAQ Accordion
        const faqQuestions = document.querySelectorAll('.faq-question-figma');
        faqQuestions.forEach(question => {
            question.addEventListener('click', function () {
                const faqItem = this.parentElement;
                const isActive = faqItem.classList.contains('active');

                // Close all FAQ items
                document.querySelectorAll('.faq-item-figma').forEach(item => {
                    item.classList.remove('active');
                });

                // Open clicked item if it wasn't active
                if (!isActive) {
                    faqItem.classList.add('active');
                }
            });
        });

        // Carousel functionality
        const carousel = document.querySelector('.top-choices-carousel');
        const prevBtn = document.querySelector('.carousel-prev');
        const nextBtn = document.querySelector('.carousel-next');
        const progressBar = document.querySelector('.carousel-progress-bar');

        if (carousel && prevBtn && nextBtn) {
            const cards = carousel.querySelectorAll('.choice-card');
            const cardCount = cards.length;
            let currentIndex = 0;

            // Calculate how many cards are visible
            function getVisibleCards() {
                const carouselWidth = carousel.offsetWidth;
                const cardWidth = cards[0].offsetWidth;
                const gap = 24;
                return Math.floor(carouselWidth / (cardWidth + gap));
            }

            function updateCarousel() {
                const visibleCards = getVisibleCards();
                const maxIndex = Math.max(0, cardCount - visibleCards);

                // Limit currentIndex
                if (currentIndex > maxIndex) {
                    currentIndex = maxIndex;
                }

                const cardWidth = cards[0].offsetWidth;
                const gap = 24;
                const offset = -(currentIndex * (cardWidth + gap));
                carousel.style.transform = `translateX(${offset}px)`;
                carousel.style.transition = 'transform 0.4s ease-in-out';

                // Update progress bar
                if (progressBar) {
                    const progress = cardCount > visibleCards
                        ? ((currentIndex / maxIndex) * 66.666) + 33.333
                        : 100;
                    progressBar.style.width = `${progress}%`;
                }

                // Update button states
                prevBtn.disabled = currentIndex === 0;
                nextBtn.disabled = currentIndex >= maxIndex || cardCount <= visibleCards;

                prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
                nextBtn.style.opacity = (currentIndex >= maxIndex || cardCount <= visibleCards) ? '0.5' : '1';
                prevBtn.style.cursor = currentIndex === 0 ? 'not-allowed' : 'pointer';
                nextBtn.style.cursor = (currentIndex >= maxIndex || cardCount <= visibleCards) ? 'not-allowed' : 'pointer';
            }

            prevBtn.addEventListener('click', function () {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            });

            nextBtn.addEventListener('click', function () {
                const visibleCards = getVisibleCards();
                const maxIndex = Math.max(0, cardCount - visibleCards);
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateCarousel();
                }
            });

            // Initialize
            updateCarousel();

            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function () {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function () {
                    currentIndex = 0; // Reset to start on resize
                    updateCarousel();
                }, 250);
            });
        }
    });
</script>

<?php get_footer(); ?>