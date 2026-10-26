<?php
/**
 * Create Test Supplement Page with Dummy Data
 * Run this file once by visiting: http://redirect.local/wp-content/plugins/SupplementPageBuilder/create-test-page.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Create a new supplement page
$post_content = <<<'CONTENT'
<!-- wp:paragraph -->
<p>Forgetting names, losing focus during important tasks, and experiencing mental fog are frustrating challenges millions face daily. People turn to various solutions including meditation, brain exercises, dietary changes, and memory supplements to address these issues. Brain health supplementation is one of the most popular methods for supporting cognitive function and mental clarity.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>However, concerns about effectiveness, safety, side effects, and scam products flood the supplement market. To ensure you don\'t fall victim to ineffective or questionable products, our team conducted an in-depth analysis of the cognitive enhancement supplement industry. We analyzed over 150 products and sorted the 10 best supplements for memory and focus in 2026 based on criteria including safety, effectiveness, value for money, certifications and approvals, ingredient quality, side effects, manufacturer claims and track record, clinical trials, and other key decision-making factors.</p>
<!-- /wp:paragraph -->

<!-- wp:supplement-blocks/top-choices {"items":[{"name":"NooCube Brain Productivity","rating":"9.8","pros":"Clinically proven ingredients, No caffeine crash, Money-back guarantee","cons":"Only available online","reviewLink":"#noocube"},{"name":"Mind Lab Pro","rating":"9.6","pros":"Universal nootropic formula, Clean label, Vegan-friendly","cons":"Premium pricing","reviewLink":"#mindlabpro"},{"name":"Qualia Mind","rating":"9.4","pros":"Comprehensive ingredient list, Fast-acting, Trusted brand","cons":"Large pill size","reviewLink":"#qualia"},{"name":"Alpha Brain","rating":"9.2","pros":"Well-researched, Popular choice, Good customer support","cons":"Contains caffeine","reviewLink":"#alphabrain"},{"name":"Performance Lab Mind","rating":"9.0","pros":"Simple clean formula, Good value, Easy to take","cons":"Fewer ingredients than competitors","reviewLink":"#performancelab"}]} /-->

<!-- wp:heading -->
<h2>Understanding Cognitive Enhancement</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Cognitive enhancement supplements, also known as nootropics, are designed to support brain function, improve memory, enhance focus, and promote overall mental clarity. These supplements typically contain a blend of vitamins, minerals, herbs, and amino acids that work synergistically to optimize brain health.</p>
<!-- /wp:paragraph -->

<!-- wp:supplement-blocks/research-docs {"intro":"To maintain transparency, weve made our full research workbook and supporting analysis publicly available. These documents detail the scoring methodology, evidence standards, ingredient evaluation, and ranking logic used in this article.","excelUrl":"https://example.com/research-workbook.xlsx","excelText":"Download the Full Research Workbook (Excel)","pdfUrl":"https://example.com/research-summary.pdf","pdfText":"Download Research Summary (PDF)"} /-->

<!-- wp:heading -->
<h2>Detailed Product Analysis</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our research team spent over 6 months analyzing the cognitive supplement market. We evaluated product formulations, clinical evidence, user reviews, manufacturing standards, and pricing to identify the most effective and trustworthy options available.</p>
<!-- /wp:paragraph -->

<!-- wp:supplement-blocks/comparison-table {"tableHtml":"<table>\n<tr><th>Product</th><th>Key Ingredients</th><th>Price</th><th>Rating</th></tr>\n<tr><td>NooCube</td><td>Bacopa, Alpha GPC, Cats Claw</td><td>$59.99</td><td>9.8/10</td></tr>\n<tr><td>Mind Lab Pro</td><td>Citicoline, Phosphatidylserine, Lion's Mane</td><td>$69.00</td><td>9.6/10</td></tr>\n<tr><td>Qualia Mind</td><td>28 Premium Ingredients</td><td>$139.00</td><td>9.4/10</td></tr>\n<tr><td>Alpha Brain</td><td>L-Theanine, L-Tyrosine, Oat Straw</td><td>$79.95</td><td>9.2/10</td></tr>\n<tr><td>Performance Lab</td><td>Citicoline, Phosphatidylserine, Maritime Pine Bark</td><td>$49.00</td><td>9.0/10</td></tr>\n</table>"} /-->

<!-- wp:supplement-blocks/source-docs {"intro":"All research findings and product evaluations are backed by peer-reviewed studies, clinical trials, and manufacturer documentation.","bullets":["FDA facility certifications","Third-party lab testing results","Published clinical research","Ingredient safety data sheets","Customer satisfaction surveys"],"excelUrl":"https://example.com/source-documents.zip","excelText":"Download All Source Documents (ZIP)","pdfUrl":"https://example.com/methodology.pdf","pdfText":"View Research Methodology (PDF)"} /-->

<!-- wp:supplement-blocks/faq {"faqs":[{"question":"How long does it take to see results from brain supplements?","answer":"Most users report noticing improvements in focus and mental clarity within 2-4 weeks of consistent use. However, optimal benefits typically appear after 8-12 weeks of regular supplementation."},{"question":"Are cognitive enhancement supplements safe?","answer":"When purchased from reputable manufacturers and used as directed, most nootropic supplements are safe. Always consult your healthcare provider before starting any new supplement, especially if you have medical conditions or take medications."},{"question":"Can I take brain supplements with my current medications?","answer":"Some ingredients may interact with medications. Always consult your doctor or pharmacist before combining supplements with prescription drugs."},{"question":"What's the difference between synthetic and natural nootropics?","answer":"Natural nootropics are derived from plants and herbs, while synthetic ones are created in laboratories. Both can be effective, but natural options often have fewer side effects."},{"question":"Do I need to cycle brain supplements?","answer":"Some supplements benefit from cycling (taking breaks), while others can be taken continuously. Follow the manufacturer's recommendations or consult a healthcare professional."}]} /-->

<!-- wp:supplement-blocks/disclaimer {"content":"These statements have not been evaluated by the Food and Drug Administration. These products are not intended to diagnose, treat, cure, or prevent any disease. Individual results may vary.\n\nAlways consult with a qualified healthcare professional before starting any new supplement regimen, especially if you have pre-existing medical conditions or are taking medications. The information provided is for educational purposes only."} /-->
CONTENT;

$post_id = wp_insert_post(array(
    'post_title'    => 'Top 5 Brain Health Supplements 2026',
    'post_content'  => $post_content,
    'post_excerpt'  => 'Our comprehensive analysis of the top cognitive enhancement supplements, backed by scientific research and expert evaluation.',
    'post_status'   => 'publish',
    'post_type'     => 'supplement_page',
));

if ($post_id) {
    // Add page settings
    update_post_meta($post_id, '_spb_theme_color', 'green');
    update_post_meta($post_id, '_spb_header_bg_url', '');
    
    echo '<h1>✅ Test Page Created Successfully!</h1>';
    echo '<p><strong>Page ID:</strong> ' . $post_id . '</p>';
    echo '<p><a href="' . get_permalink($post_id) . '" target="_blank" style="display: inline-block; padding: 15px 30px; background: #10B981; color: white; text-decoration: none; border-radius: 8px; font-weight: bold;">View Test Page</a></p>';
    echo '<p><a href="' . admin_url('post.php?post=' . $post_id . '&action=edit') . '" target="_blank" style="display: inline-block; padding: 15px 30px; background: #3B82F6; color: white; text-decoration: none; border-radius: 8px; font-weight: bold;">Edit in Admin</a></p>';
    echo '<p style="margin-top: 30px; color: #666;"><em>You can delete this file after viewing the test page.</em></p>';
} else {
    echo '<h1>❌ Error creating test page</h1>';
}
