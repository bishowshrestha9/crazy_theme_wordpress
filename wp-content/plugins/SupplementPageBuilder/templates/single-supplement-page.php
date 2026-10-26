<?php
/**
 * Template for Single Supplement Page
 */

get_header();

$post_id = get_the_ID();
$theme_color = get_post_meta($post_id, '_spb_theme_color', true) ?: 'green';
$header_bg_url = get_post_meta($post_id, '_spb_header_bg_url', true);

// Theme color mapping
$theme_colors = array(
    'blue' => '#6BA3F8',
    'green' => '#10B981',
    'red' => '#EF4444',
    'black' => '#1F2937',
    'white' => '#FFFFFF'
);
$primary_color = $theme_colors[$theme_color];
?>

<style>
    body {
        margin: 0;
        padding: 0;
    }

    .t10supp-page-wrapper {
        --primary-color: <?php echo esc_attr($primary_color); ?>;
        font-family: Georgia, 'Times New Roman', serif;
        background: #F9FAFB;
        color: #111827;
        line-height: 1.8;
        width: 100%;
        overflow-x: hidden;
    }

    .t10supp-hero {
        <?php if (!empty($header_bg_url)): ?>
            background-image: url('<?php echo esc_url($header_bg_url); ?>');
            background-size: cover;
            background-position: center;
        <?php else: ?>
            background: linear-gradient(135deg, var(--primary-color) 0%, rgba(16, 185, 129, 0.85) 100%);
        <?php endif; ?>
        color: #FFFFFF;
        padding: 60px 20px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        min-height: 220px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
    }

    .t10supp-hero h1 {
        font-size: 44px;
        font-weight: 800;
        margin: 0 0 15px 0;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        letter-spacing: -0.8px;
        max-width: 1200px;
        line-height: 1.2;
    }

    .t10supp-hero p {
        font-size: 20px;
        max-width: 1000px;
        margin: 0 auto;
        line-height: 1.5;
        font-weight: 400;
        opacity: 0.95;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }

    .t10supp-main-layout {
        max-width: 1400px;
        margin: 0 auto;
        padding: 50px 30px;
        background: #FFFFFF;
        box-shadow: 0 0 30px rgba(0, 0, 0, 0.05);
    }

    .t10supp-content {
        max-width: 100%;
        width: 100%;
    }

    .t10supp-content section {
        margin-bottom: 70px;
        scroll-margin-top: 100px;
    }

    .t10supp-section-title {
        font-size: 42px;
        font-weight: 800;
        color: #111827;
        margin: 0 0 30px 0;
        padding-bottom: 20px;
        border-bottom: 4px solid var(--primary-color);
        letter-spacing: -0.5px;
    }

    .t10supp-content p {
        font-size: 20px;
        line-height: 2;
        color: #374151;
        margin-bottom: 24px;
    }

    .t10supp-content h3 {
        font-size: 32px;
        font-weight: 700;
        color: #1F2937;
        margin: 40px 0 24px 0;
        line-height: 1.3;
    }

    .t10supp-intro-section {
        margin-bottom: 60px;
        line-height: 2;
        padding: 30px;
        background: #F9FAFB;
        border-radius: 12px;
        border-left: 5px solid var(--primary-color);
    }

    .t10supp-intro-section p {
        margin-bottom: 24px;
        font-size: 21px;
    }

    /* Top 5 Table */
    .t10supp-top5-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 40px 0;
        background: #FFFFFF;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        border-radius: 16px;
        overflow: hidden;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        border: 2px solid #E5E7EB;
    }

    .t10supp-top5-table tr {
        border-bottom: 2px solid #E5E7EB;
        transition: all 0.2s ease;
    }

    .t10supp-top5-table tr:last-child {
        border-bottom: none;
    }

    .t10supp-top5-table th,
    .t10supp-top5-table td {
        padding: 28px 32px;
        text-align: left;
        font-size: 19px;
    }

    .t10supp-top5-table th {
        background: linear-gradient(135deg, var(--primary-color) 0%, rgba(16, 185, 129, 0.85) 100%);
        color: #FFFFFF;
        font-weight: 800;
        font-size: 20px;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        padding: 24px 32px;
    }

    .t10supp-top5-table td {
        color: #374151;
        line-height: 1.8;
        font-size: 19px;
    }

    .t10supp-top5-table tbody tr:nth-child(even) {
        background: #F0FDF4;
    }

    .t10supp-top5-table tbody tr:hover {
        background: #ECFDF5;
        transform: scale(1.005);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .t10supp-product-name {
        font-weight: 800;
        color: #111827;
        font-size: 21px;
    }

    .t10supp-top5-table td strong {
        color: var(--primary-color);
        font-weight: 800;
        font-size: 20px;
    }

    .t10supp-product-number {
        display: inline-block;
        background: linear-gradient(135deg, var(--primary-color) 0%, rgba(16, 185, 129, 0.85) 100%);
        color: #FFFFFF;
        width: 42px;
        height: 42px;
        line-height: 42px;
        text-align: center;
        border-radius: 50%;
        font-weight: 900;
        font-size: 20px;
        margin-right: 16px;
        vertical-align: middle;
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3);
    }

    .t10supp-product-category {
        color: var(--primary-color);
        font-weight: 800;
        font-size: 20px;
        margin-right: 10px;
        text-transform: uppercase;
    }

    .t10supp-top5-table a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 700;
        font-size: 18px;
        border-bottom: 3px solid var(--primary-color);
        transition: all 0.3s ease;
        padding-bottom: 2px;
    }

    .t10supp-top5-table a:hover {
        color: #065F46;
        border-bottom-color: #065F46;
        background: rgba(16, 185, 129, 0.1);
        padding: 4px 8px;
        border-radius: 4px;
    }

    /* Research Documents */
    .t10supp-research-docs {
        background: linear-gradient(135deg, #F0FDF4 0%, #D1FAE5 100%);
        border-left: 6px solid var(--primary-color);
        padding: 40px 45px;
        margin: 50px 0;
        border-radius: 16px;
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.15);
        border: 2px solid #D1FAE5;
    }

    .t10supp-research-docs h3 {
        font-size: 32px;
        margin: 0 0 24px 0;
        color: #065F46;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .t10supp-research-docs p {
        margin-bottom: 24px;
        line-height: 2;
        font-size: 20px;
        color: #065F46;
    }

    .t10supp-download-links {
        display: flex !important;
        flex-direction: row !important;
        gap: 24px;
        margin-top: 32px;
        flex-wrap: wrap;
        align-items: flex-start;
    }

    .t10supp-download-btn {
        display: inline-flex !important;
        align-items: center;
        padding: 16px 32px;
        background: linear-gradient(135deg, var(--primary-color) 0%, rgba(16, 185, 129, 0.85) 100%);
        color: #FFFFFF;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 16px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        border: 2px solid rgba(255, 255, 255, 0.3);
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
        flex-shrink: 0;
        width: auto !important;
    }

    .t10supp-download-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.95) 0%, var(--primary-color) 100%);
    }

    /* Comparison Table */
    .t10supp-comparison-table-wrapper {
        overflow-x: auto;
        margin: 50px 0;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        border: 2px solid #E5E7EB;
        background: #FFFFFF;
    }

    .t10supp-comparison-table-wrapper table {
        width: 100%;
        border-collapse: collapse;
        background: #FFFFFF;
        min-width: 600px;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    /* Handle any table structure pasted from ChatGPT/Excel */
    .t10supp-comparison-table-wrapper table,
    .t10supp-comparison-table-wrapper table * {
        border-collapse: collapse !important;
    }

    .t10supp-comparison-table-wrapper table th {
        background: linear-gradient(135deg, var(--primary-color) 0%, rgba(16, 185, 129, 0.85) 100%);
        color: #FFFFFF;
        padding: 14px 16px;
        font-size: 14px;
        font-weight: 700;
        text-align: left;
        letter-spacing: 0.3px;
        border-right: 2px solid rgba(255, 255, 255, 0.2);
        text-transform: uppercase;
    }

    .t10supp-comparison-table-wrapper table th:last-child {
        border-right: none;
    }

    .t10supp-comparison-table-wrapper table td {
        padding: 12px 14px;
        border-bottom: 2px solid #E5E7EB;
        border-right: 1px solid #E5E7EB;
        font-size: 14px;
        line-height: 1.6;
        color: #374151;
    }

    .t10supp-comparison-table-wrapper table td:last-child {
        border-right: none;
    }

    .t10supp-comparison-table-wrapper table tbody tr:last-child td {
        border-bottom: none;
    }

    .t10supp-comparison-table-wrapper table tbody tr:nth-child(even) {
        background: #F0FDF4;
    }

    .t10supp-comparison-table-wrapper table tbody tr:hover {
        background: #ECFDF5;
        transform: scale(1.002);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* Disclaimer */
    .t10supp-disclaimer-box {
        background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
        border-left: 6px solid #F59E0B;
        padding: 40px 45px;
        margin: 50px 0;
        border-radius: 16px;
        font-size: 19px;
        line-height: 2;
        box-shadow: 0 6px 20px rgba(245, 158, 11, 0.2);
        border: 2px solid #FDE68A;
    }

    .t10supp-disclaimer-box h4 {
        margin: 0 0 24px 0;
        color: #92400E;
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .t10supp-disclaimer-box p {
        color: #78350F;
        font-size: 19px;
        margin-bottom: 20px;
    }

    .t10supp-disclaimer-box ul {
        margin: 24px 0;
        padding-left: 32px;
    }

    .t10supp-disclaimer-box li {
        margin-bottom: 16px;
        color: #78350F;
        font-size: 19px;
    }

    /* FAQs Section */
    .t10supp-faqs-section {
        margin-top: 70px;
    }

    .t10supp-faq-item {
        background: #FFFFFF;
        border: 2px solid #E5E7EB;
        border-radius: 12px;
        margin-bottom: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .t10supp-faq-item:hover {
        border-color: var(--primary-color);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.15);
        transform: translateY(-2px);
    }

    .t10supp-faq-question-btn {
        width: 100%;
        background: linear-gradient(135deg, #F9FAFB 0%, #F3F4F6 100%);
        border: none;
        padding: 24px 28px;
        text-align: left;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        transition: background 0.2s;
    }

    .t10supp-faq-question-btn:hover {
        background: linear-gradient(135deg, #F0FDF4 0%, #D1FAE5 100%);
    }

    .t10supp-faq-arrow {
        font-size: 24px;
        transition: transform 0.3s ease;
        color: var(--primary-color);
        font-weight: 900;
        min-width: 30px;
        text-align: center;
    }

    .t10supp-faq-arrow.active {
        transform: rotate(180deg);
    }

    .t10supp-faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
        padding: 0 28px;
        font-size: 18px;
        line-height: 2;
        color: #4B5563;
        background: #FFFFFF;
    }

    .t10supp-faq-answer.active {
        max-height: 1500px;
        padding: 24px 28px 28px 28px;
    }

    @media (max-width: 1024px) {
        .t10supp-main-layout {
            padding: 40px 25px;
        }

        .t10supp-section-title {
            font-size: 36px;
        }
    }

    @media (max-width: 768px) {
        .t10supp-hero {
            padding: 60px 20px;
            min-height: 250px;
        }

        .t10supp-hero h1 {
            font-size: 36px;
            line-height: 1.3;
        }

        .t10supp-hero p {
            font-size: 20px;
        }

        .t10supp-main-layout {
            padding: 30px 20px;
        }

        .t10supp-section-title {
            font-size: 32px;
        }

        .t10supp-content p {
            font-size: 18px;
        }

        .t10supp-top5-table th,
        .t10supp-top5-table td {
            padding: 20px 16px;
            font-size: 17px;
        }

        .t10supp-product-number {
            width: 36px;
            height: 36px;
            line-height: 36px;
            font-size: 18px;
        }

        .t10supp-research-docs,
        .t10supp-disclaimer-box {
            padding: 30px 25px;
        }

        .t10supp-download-links {
            flex-direction: column;
        }

        .t10supp-download-btn {
            padding: 16px 24px;
            font-size: 17px;
            width: 100%;
            text-align: center;
            justify-content: center;
        }

        .t10supp-comparison-table-wrapper {
            overflow-x: scroll;
            -webkit-overflow-scrolling: touch;
        }

        .t10supp-faq-question-btn {
            font-size: 18px;
            padding: 20px;
        }

        .t10supp-faq-answer {
            font-size: 17px;
        }
    }

    @media (max-width: 480px) {
        .t10supp-hero h1 {
            font-size: 28px;
        }

        .t10supp-hero p {
            font-size: 18px;
        }

        .t10supp-section-title {
            font-size: 28px;
        }

        .t10supp-main-layout {
            padding: 20px 15px;
        }

        .t10supp-research-docs h3,
        .t10supp-disclaimer-box h4 {
            font-size: 24px;
        }
    }
</style>

<div class="t10supp-page-wrapper">
    <!-- Hero Section -->
    <div class="t10supp-hero">
        <h1><?php the_title(); ?></h1>
        <?php if (has_excerpt()): ?>
            <p><?php echo get_the_excerpt(); ?></p>
        <?php endif; ?>
    </div>

    <!-- Main Content Layout -->
    <div class="t10supp-main-layout">
        <div class="t10supp-content">
            <?php
            while (have_posts()) : the_post();
                the_content();
            endwhile;
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
