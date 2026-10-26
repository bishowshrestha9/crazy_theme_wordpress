<?php
/**
 * Template Name: Top 10 Supplement Style
 * Template for supplement/health product listings
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get post data
global $post;
$post_id = $post->ID;

// Get page meta
$hero_title = get_post_meta($post_id, '_top10_hero_title', true);
$hero_subtitle = get_post_meta($post_id, '_top10_hero_subtitle', true);
$products = get_post_meta($post_id, '_top10_products', true) ?: array();
$research_intro = get_post_meta($post_id, '_top10_research_intro', true);
$theme_color = get_post_meta($post_id, '_top10_theme_color', true) ?: 'green';
$excel_url = get_post_meta($post_id, '_research_doc_excel_url', true);
$pdf_url = get_post_meta($post_id, '_research_doc_pdf_url', true);
$excel_link_text = get_post_meta($post_id, '_research_doc_excel_link_text', true);
$pdf_link_text = get_post_meta($post_id, '_research_doc_pdf_link_text', true);
$research_intro_text = get_post_meta($post_id, '_research_intro_text', true);
$source_docs_intro = get_post_meta($post_id, '_source_docs_intro', true);
$source_docs_bullets = get_post_meta($post_id, '_source_docs_bullets', true);
$source_docs_excel_url = get_post_meta($post_id, '_source_docs_excel_url', true);
$source_docs_pdf_url = get_post_meta($post_id, '_source_docs_pdf_url', true);
$source_docs_excel_link_text = get_post_meta($post_id, '_source_docs_excel_link_text', true);
$source_docs_pdf_link_text = get_post_meta($post_id, '_source_docs_pdf_link_text', true);
$disclaimer_text = get_post_meta($post_id, '_disclaimer_text', true);
$header_bg_url = get_post_meta($post_id, '_header_bg_url', true);
$comparison_table_html = get_post_meta($post_id, '_comparison_table_html', true);
$faqs = get_post_meta($post_id, '_top10_faqs', true);

// Top 5 section fields
$top5_title_prefix = get_post_meta($post_id, '_top5_title_prefix', true);
$top5_step1_label = get_post_meta($post_id, '_top5_step1_label', true);
$top5_step2_label = get_post_meta($post_id, '_top5_step2_label', true);
$top5_step3_label = get_post_meta($post_id, '_top5_step3_label', true);
$top5_step4_label = get_post_meta($post_id, '_top5_step4_label', true);
$top5_step5_label = get_post_meta($post_id, '_top5_step5_label', true);

// Default link text
if (empty($excel_link_text)) {
    $excel_link_text = 'Download the Full Research Workbook (Excel)';
}
if (empty($pdf_link_text)) {
    $pdf_link_text = 'Download the Detailed Research Document (PDF)';
}
if (empty($source_docs_excel_link_text)) {
    $source_docs_excel_link_text = 'Download the Full Research Workbook (Excel)';
}
if (empty($source_docs_pdf_link_text)) {
    $source_docs_pdf_link_text = 'Download the Detailed Research Document (PDF)';
}

// Default research intro text if not set
if (empty($research_intro_text)) {
    $research_intro_text = "To maintain transparency, we've made our full research workbook and supporting analysis publicly available. These documents detail the scoring methodology, evidence standards, ingredient evaluation, and ranking logic used in this article.";
}

// Default source docs intro if not set
if (empty($source_docs_intro)) {
    $source_docs_intro = 'As part of our commitment to evidence-based publishing, SupplementMag provides full access to the internal research materials used to create this ranking.';
}

// Default source docs bullets if not set
if (empty($source_docs_bullets)) {
    $source_docs_bullets = "A consolidated comparison sheet ranking all reviewed products\nA weighted scoring model across safety, effectiveness, ingredient quality, certifications, value, evidence strength, user experience, and versatility\nProduct-specific research sheets detailing: Ingredient-level analysis, Scientific rationale behind each score, Use-case suitability and limitations, Pros, cons, and safety considerations\nTransparent formulas and charts supporting all final ratings";
}

// Default Top 5 fields if not set
if (empty($top5_title_prefix)) {
    $top5_title_prefix = 'Top Choices:';
}
if (empty($top5_step1_label)) {
    $top5_step1_label = 'Product Category/Badge';
}
if (empty($top5_step2_label)) {
    $top5_step2_label = 'Product Name';
}
if (empty($top5_step3_label)) {
    $top5_step3_label = 'Price Range';
}
if (empty($top5_step4_label)) {
    $top5_step4_label = 'Rating Score';
}
if (empty($top5_step5_label)) {
    $top5_step5_label = 'Read Review';
}

// Theme color mapping
$theme_colors = array(
    'blue' => '#6BA3F8',
    'green' => '#10B981',
    'red' => '#EF4444',
    'black' => '#1F2937',
    'white' => '#FFFFFF'
);
$primary_color = $theme_colors[$theme_color];

// Product data already fetched


get_header();
?>

<style>
    .t10supp-page-wrapper {
        --primary-color:
            <?php echo esc_attr($primary_color); ?>
        ;
        font-family: Georgia, 'Times New Roman', serif;
        background: #F9FAFB;
        color: #111827;
        line-height: 1.8;
    }

    .t10supp-hero {
        <?php if (!empty($header_bg_url)): ?>
            background-image: url('<?php echo esc_url($header_bg_url); ?>');
            background-size: cover;
            background-position: center;
        <?php else: ?>
            background: var(--primary-color);
        <?php endif; ?>
        color: #FFFFFF;
        padding: 120px 20px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        min-height: 400px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .t10supp-hero h1 {
        font-size: 48px;
        font-weight: 700;
        margin: 0 0 20px 0;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        letter-spacing: -0.5px;
    }

    .t10supp-hero p {
        font-size: 22px;
        max-width: 900px;
        margin: 0 auto;
        line-height: 1.6;
        font-weight: 400;
    }

    .t10supp-main-layout {
        max-width: 1600px;
        margin: 0 auto;
        padding: 60px 40px;
    }

    /* Content - Enhanced for mature audience */
    .t10supp-content {
        max-width: 100%;
    }

    .t10supp-content section {
        margin-bottom: 60px;
    }

    .t10supp-section-title {
        font-size: 38px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 24px 0;
        padding-bottom: 16px;
        border-bottom: 3px solid var(--primary-color);
    }

    .t10supp-content p {
        font-size: 19px;
        line-height: 1.9;
        color: #374151;
        margin-bottom: 20px;
    }

    .t10supp-content h3 {
        font-size: 28px;
        font-weight: 600;
        color: #1F2937;
        margin: 32px 0 20px 0;
    }

    .t10supp-intro-section {
        margin-bottom: 50px;
        line-height: 1.9;
    }

    .t10supp-intro-section p {
        margin-bottom: 20px;
    }

    /* Top 5 Table - Enhanced for readability */
    .t10supp-top5-table {
        width: 100%;
        border-collapse: collapse;
        margin: 32px 0;
        background: #FFFFFF;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        border-radius: 12px;
        overflow: hidden;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .t10supp-top5-table tr {
        border-bottom: 2px solid #E5E7EB;
    }

    .t10supp-top5-table tr:last-child {
        border-bottom: none;
    }

    .t10supp-top5-table th,
    .t10supp-top5-table td {
        padding: 20px 24px;
        text-align: left;
        font-size: 17px;
    }

    .t10supp-top5-table th {
        background: var(--primary-color);
        color: #FFFFFF;
        font-weight: 700;
        font-size: 18px;
        letter-spacing: 0.3px;
    }

    .t10supp-top5-table td {
        color: #374151;
        line-height: 1.6;
    }

    .t10supp-top5-table tbody tr:nth-child(even) {
        background: #F9FAFB;
    }

    .t10supp-top5-table tbody tr:hover {
        background: #F3F4F6;
    }

    .t10supp-product-name {
        font-weight: 700;
        color: #111827;
        font-size: 19px;
    }

    .t10supp-top5-table td strong {
        color: var(--primary-color);
        font-weight: 700;
        font-size: 18px;
    }

    .t10supp-product-number {
        display: inline-block;
        background: var(--primary-color);
        color: #FFFFFF;
        width: 32px;
        height: 32px;
        line-height: 32px;
        text-align: center;
        border-radius: 50%;
        font-weight: 700;
        font-size: 16px;
        margin-right: 12px;
        vertical-align: middle;
    }

    .t10supp-product-category {
        color: var(--primary-color);
        font-weight: 700;
        font-size: 18px;
        margin-right: 8px;
    }

    .t10supp-top5-table a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        border-bottom: 2px solid var(--primary-color);
        transition: all 0.3s ease;
    }

    .t10supp-top5-table a:hover {
        color: #065F46;
        border-bottom-color: #065F46;
    }

    .t10supp-review-link {
        display: inline-block;
        background: var(--primary-color);
        color: #FFFFFF;
        padding: 10px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.3s ease;
    }

    .review-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* Research Documents - Enhanced */
    .t10supp-research-docs {
        background: #F0FDF4;
        border-left: 5px solid var(--primary-color);
        padding: 32px;
        margin: 40px 0;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .t10supp-research-docs h3 {
        font-size: 26px;
        margin: 0 0 20px 0;
        color: #111827;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .t10supp-research-docs p {
        margin-bottom: 20px;
        line-height: 1.8;
        font-size: 18px;
        color: #374151;
    }

    .t10supp-download-links {
        display: flex;
        gap: 20px;
        margin-top: 24px;
        flex-wrap: wrap;
    }

    .t10supp-download-btn {
        display: inline-block;
        padding: 16px 32px;
        background: var(--primary-color);
        color: #FFFFFF;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 17px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
    }

    .t10supp-download-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    }

    /* Comparison Table - Enhanced for mature audience */
    .t10supp-comparison-table-wrapper {
        overflow-x: auto;
        margin: 40px 0;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }

    /* Style any table inside the wrapper */
    .t10supp-comparison-table-wrapper table,
    .t10supp-comparison-table {
        width: 100%;
        border-collapse: collapse;
        background: #FFFFFF;
        min-width: 900px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .t10supp-comparison-table-wrapper table th,
    .t10supp-comparison-table th {
        background: var(--primary-color);
        color: #FFFFFF;
        padding: 20px 18px;
        font-size: 17px;
        font-weight: 700;
        text-align: left;
        letter-spacing: 0.3px;
        border-right: 2px solid rgba(255, 255, 255, 0.1);
    }

    .t10supp-comparison-table-wrapper table th:last-child,
    .t10supp-comparison-table th:last-child {
        border-right: none;
    }

    .t10supp-comparison-table-wrapper table td,
    .t10supp-comparison-table td {
        padding: 18px;
        border-bottom: 2px solid #E5E7EB;
        border-right: 1px solid #E5E7EB;
        font-size: 16px;
        line-height: 1.7;
        color: #374151;
    }

    .t10supp-comparison-table-wrapper table td:last-child,
    .t10supp-comparison-table td:last-child {
        border-right: none;
    }

    .t10supp-comparison-table-wrapper table tbody tr:last-child td,
    .t10supp-comparison-table tbody tr:last-child td {
        border-bottom: none;
    }

    .t10supp-comparison-table-wrapper table tbody tr:nth-child(even),
    .t10supp-comparison-table tbody tr:nth-child(even) {
        background: #F9FAFB;
    }

    .t10supp-comparison-table-wrapper table tbody tr:hover,
    .t10supp-comparison-table tbody tr:hover {
        background: #F3F4F6;
    }

    .t10supp-comparison-table-wrapper table .t10supp-product-name,
    .t10supp-comparison-table .t10supp-product-name {
        font-weight: 700;
        color: #111827;
        font-size: 18px;
    }

    .t10supp-buy-now-btn {
        display: inline-block;
        background: var(--primary-color);
        color: #FFFFFF !important;
        padding: 10px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .t10supp-buy-now-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        opacity: 0.9;
    }

    /* Disclaimer - Enhanced */
    .t10supp-disclaimer-box {
        background: #FEF3C7;
        border-left: 5px solid #F59E0B;
        padding: 32px;
        margin: 40px 0;
        border-radius: 12px;
        font-size: 17px;
        line-height: 1.8;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .t10supp-disclaimer-box h4 {
        margin: 0 0 20px 0;
        color: #92400E;
        font-size: 24px;
        font-weight: 700;
    }

    .t10supp-disclaimer-box p {
        color: #78350F;
        font-size: 17px;
    }

    .t10supp-disclaimer-box ul {
        margin: 20px 0;
        padding-left: 28px;
    }

    .t10supp-disclaimer-box li {
        margin-bottom: 12px;
        color: #78350F;
    }

    /* FAQs Section */
    .t10supp-faqs-section {
        margin-top: 60px;
    }

    .t10supp-faq-item {
        background: #FFFFFF;
        border: 1px solid #E5E7EB;
        border-radius: 8px;
        margin-bottom: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .t10supp-faq-item:hover {
        border-color: var(--primary-color);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .t10supp-faq-question-btn {
        width: 100%;
        background: none;
        border: none;
        padding: 16px 20px;
        text-align: left;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 17px;
        font-weight: 600;
        color: #111827;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        transition: background 0.2s;
    }

    .t10supp-faq-question-btn:hover {
        background: #F9FAFB;
    }

    .t10supp-faq-arrow {
        font-size: 20px;
        transition: transform 0.3s ease;
        color: var(--primary-color);
        font-weight: bold;
    }

    .t10supp-faq-arrow.active {
        transform: rotate(180deg);
    }

    .t10supp-faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
        padding: 0 20px;
        font-size: 15px;
        line-height: 1.7;
        color: #4B5563;
    }

    .t10supp-faq-answer.active {
        max-height: 1000px;
        padding: 0 20px 16px 20px;
    }

    @media (max-width: 968px) {
        .t10supp-main-layout {
            grid-template-columns: 1fr;
            gap: 40px;
        }

        .t10supp-hero h1 {
            font-size: 36px;
        }

        .t10supp-hero p {
            font-size: 18px;
        }

        .t10supp-comparison-table-wrapper {
            overflow-x: scroll;
        }
    }
</style>

<div class="t10supp-page-wrapper">

    <!-- Hero Section -->
    <section class="t10supp-hero">
        <h1><?php echo esc_html($hero_title ?: get_the_title()); ?></h1>
        <?php if ($hero_subtitle): ?>
            <p><?php echo esc_html($hero_subtitle); ?></p>
        <?php endif; ?>
    </section>

    <!-- Main Content with Sidebar -->
    <div class="t10supp-main-layout">

        <!-- Main Content Area -->
        <div class="t10supp-content">

            <!-- 1. Introduction -->
            <div class="t10supp-intro-section">
                <?php echo wpautop($post->post_content); ?>
            </div>

            <!-- 2. Top 5 Choices Table -->
            <section id="top-choices">
                <h2 class="t10supp-section-title"><?php echo esc_html($top5_title_prefix); ?>
                    <?php echo esc_html(get_the_title()); ?>
                </h2>

                <table class="t10supp-top5-table">
                    <?php
                    $top_products = array_slice($products, 0, 5);
                    foreach ($top_products as $index => $product):
                        if (empty($product['name']))
                            continue;
                        // Use the badge text (category) instead of numbering
                        $badge = !empty($product['badge_text']) ? $product['badge_text'] : 'Featured Product';
                        ?>
                        <tr id="product-<?php echo ($index + 1); ?>">
                            <td>
                                <span class="t10supp-product-number"><?php echo ($index + 1); ?></span>
                                <strong class="t10supp-product-category"><?php echo esc_html($badge); ?>:</strong>
                                <?php echo esc_html($product['name']); ?> |
                                <?php echo esc_html($product['price']); ?> |
                                <?php echo esc_html($product['rating']); ?>/10 |
                                <?php if (!empty($product['buy_link'])): ?>
                                    <a href="<?php echo esc_url($product['buy_link']); ?>" target="_blank"
                                        style="margin-right:10px; font-weight: bold; text-decoration: underline; color: var(--primary-color);">Buy Now</a>
                                <?php endif; ?>
                                <?php if (!empty($product['review_link'])): ?>
                                    <a href="<?php echo esc_url($product['review_link']); ?>" style="font-weight: bold; text-decoration: underline; color: var(--primary-color);">Read Review</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </section>

            <!-- 3. Research Documents -->
            <section id="research-docs">
                <div class="t10supp-research-docs">
                    <h3>Downloadable Research Transparency Document</h3>
                    <p><?php echo esc_html($research_intro_text); ?></p>
                    <div class="t10supp-download-links">
                        <?php if (!empty($excel_url)): ?>
                            <a href="<?php echo esc_url($excel_url); ?>" class="t10supp-download-btn" target="_blank"
                                rel="noopener"><?php echo esc_html($excel_link_text); ?></a>
                        <?php endif; ?>
                        <?php if (!empty($pdf_url)): ?>
                            <a href="<?php echo esc_url($pdf_url); ?>" class="t10supp-download-btn" target="_blank"
                                rel="noopener"><?php echo esc_html($pdf_link_text); ?></a>
                        <?php endif; ?>
                        <?php if (empty($excel_url) && empty($pdf_url)): ?>
                            <p style="font-style: italic; color: #6B7280;"><em>Research documents will be available
                                    soon.</em></p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <!-- 4. Comparison Table -->
            <section id="comparison">
                <h2 class="t10supp-section-title">Top Products: Comparison Table</h2>
                <p>Each product offers distinct advantages based on your specific goals and health profile. This
                    comparison highlights key decision factors to help you select the most suitable option.</p>

                <?php if (!empty($comparison_table_html)): ?>
                    <div class="t10supp-comparison-table-wrapper">
                        <?php echo wp_kses_post($comparison_table_html); ?>
                    </div>
                <?php else: ?>
                    <div class="t10supp-comparison-table-wrapper">
                        <p style="padding: 40px; text-align: center; background: #f9f9f9; border-radius: 8px; color: #666;">
                            <em>No comparison table added yet. Please add table content in the page editor.</em>
                        </p>
                    </div>
                <?php endif; ?>
            </section>

            <!-- 5. Source Documents & Disclosure -->
            <section id="disclosure">
                <h2 class="t10supp-section-title">Source Documents & Disclosure</h2>

                <div class="t10supp-research-docs">
                    <p><?php echo esc_html($source_docs_intro); ?></p>

                    <h4 style="font-size: 22px; margin-top: 28px; margin-bottom: 16px; color: #111827;">The research
                        document includes</h4>
                    <ul style="font-size: 17px; line-height: 1.9;">
                        <?php
                        $bullets = explode("\n", $source_docs_bullets);
                        foreach ($bullets as $bullet) {
                            $bullet = trim($bullet);
                            if (!empty($bullet)) {
                                echo '<li>' . esc_html($bullet) . '</li>';
                            }
                        }
                        ?>
                    </ul>

                    <h4 style="font-size: 22px; margin-top: 28px; margin-bottom: 20px; color: #111827;">Research Files
                        [Downloadable]</h4>
                    <div class="t10supp-download-links">
                        <?php if (!empty($source_docs_excel_url)): ?>
                            <a href="<?php echo esc_url($source_docs_excel_url); ?>" class="t10supp-download-btn"
                                target="_blank" rel="noopener"><?php echo esc_html($source_docs_excel_link_text); ?></a>
                        <?php endif; ?>
                        <?php if (!empty($source_docs_pdf_url)): ?>
                            <a href="<?php echo esc_url($source_docs_pdf_url); ?>" class="t10supp-download-btn"
                                target="_blank" rel="noopener"><?php echo esc_html($source_docs_pdf_link_text); ?></a>
                        <?php endif; ?>
                        <?php if (empty($source_docs_excel_url) && empty($source_docs_pdf_url)): ?>
                            <p style="font-style: italic; color: #6B7280;"><em>Research documents will be available
                                    soon.</em></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="t10supp-disclaimer-box">
                    <h4>Disclaimer & Usage Rights</h4>
                    <?php if (!empty($disclaimer_text)): ?>
                        <div class="disclaimer-content">
                            <?php echo wp_kses_post($disclaimer_text); ?>
                        </div>
                    <?php else: ?>
                        <p>This research workbook and accompanying documentation were created by and are the intellectual
                            property of <?php echo esc_html(get_bloginfo('name')); ?>.</p>
                        <ul>
                            <li>The materials are provided for personal, educational, and informational use only</li>
                            <li>Unauthorized modification, redistribution, publishing, or commercial use is discouraged</li>
                            <li>The data, scoring models, and narratives may not be repurposed or presented as original
                                research by third parties</li>
                            <li>Product formulations, pricing, and availability may change over time; readers should verify
                                details directly with manufacturers</li>
                            <li>By publishing these materials, <?php echo esc_html(get_bloginfo('name')); ?> aims to promote
                                transparency, informed decision-making, and responsible product research (not to replace
                                professional medical advice)</li>
                        </ul>
                    <?php endif; ?>
                </div>
            </section>

            <!-- 6. FAQs Section -->
            <?php if (!empty($faqs) && is_array($faqs)): ?>
                <section id="faqs" class="t10supp-faqs-section">
                    <h2 class="t10supp-section-title">Frequently Asked Questions</h2>

                    <?php foreach ($faqs as $index => $faq): ?>
                        <?php if (!empty($faq['question']) && !empty($faq['answer'])): ?>
                            <div class="t10supp-faq-item">
                                <button class="t10supp-faq-question-btn" onclick="toggleFaq(<?php echo $index; ?>)">
                                    <span><?php echo esc_html($faq['question']); ?></span>
                                    <span class="t10supp-faq-arrow" id="arrow-<?php echo $index; ?>">▼</span>
                                </button>
                                <div class="t10supp-faq-answer" id="answer-<?php echo $index; ?>">
                                    <p><?php echo esc_html($faq['answer']); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </section>

                <script>
                    function toggleFaq(index) {
                        const answer = document.getElementById('answer-' + index);
                        const arrow = document.getElementById('arrow-' + index);

                        answer.classList.toggle('active');
                        arrow.classList.toggle('active');
                    }

                    // Sticky sidebar functionality
                    document.addEventListener('DOMContentLoaded', function () {
                        const sidebar = document.querySelector('.sidebar-sticky');
                        const sidebarParent = document.querySelector('.supplement-sidebar');

                        if (!sidebar || !sidebarParent) return;

                        const sidebarTop = sidebarParent.offsetTop;
                        const adminBarHeight = document.body.classList.contains('admin-bar') ? 32 : 0;
                        const stickyOffset = adminBarHeight + 20;

                        function handleSticky() {
                            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                            if (scrollTop > sidebarTop - stickyOffset) {
                                sidebar.style.position = 'fixed';
                                sidebar.style.top = stickyOffset + 'px';
                                sidebar.style.width = sidebarParent.offsetWidth + 'px';
                            } else {
                                sidebar.style.position = 'relative';
                                sidebar.style.top = '0';
                                sidebar.style.width = 'auto';
                            }
                        }

                        window.addEventListener('scroll', handleSticky);
                        window.addEventListener('resize', handleSticky);
                        handleSticky();
                    });
                </script>
            <?php endif; ?>

        </div><!-- .t10supp-content -->

    </div><!-- .t10supp-main-layout -->

</div><!-- .t10supp-page-wrapper -->

<?php
get_footer();
