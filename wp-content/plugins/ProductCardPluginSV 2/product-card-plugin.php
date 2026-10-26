<?php
/**
 * Plugin Name: Product Card Plugin - SV
 * Plugin URI: https://yourwebsite.com
 * Description: Dynamic product card Gutenberg block with status-based themes (Green for Legit, Red for Scam)
 * Version: 2.0.0
 * Author: Bishow Shrestha 
 * Author URI: https://yourwebsite.com
 * License: GPL v2 or later
 * Text Domain: product-card-plugin
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('PCP_VERSION', '2.3.0');
define('PCP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PCP_PLUGIN_URL', plugin_dir_url(__FILE__));

class ProductCardPlugin
{

    public function __construct()
    {
        // Enqueue styles and scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));

        // Register Gutenberg block
        add_action('init', array($this, 'register_gutenberg_block'));
    }

    /**
     * Enqueue CSS and JS for frontend
     */
    public function enqueue_assets()
    {
        wp_enqueue_style('product-card-styles', PCP_PLUGIN_URL . 'assets/css/product-card.css', array(), PCP_VERSION);
        wp_enqueue_script('product-card-scripts', PCP_PLUGIN_URL . 'assets/js/product-card.js', array('jquery'), PCP_VERSION, true);
    }

    /**
     * Enqueue block editor assets
     */
    public function enqueue_block_editor_assets()
    {
        wp_enqueue_style('product-card-editor-styles', PCP_PLUGIN_URL . 'assets/css/product-card.css', array(), PCP_VERSION);
    }

    /**
     * Register Gutenberg block
     */
    public function register_gutenberg_block()
    {
        // Register block script
        wp_register_script(
            'product-card-block',
            PCP_PLUGIN_URL . 'assets/js/block.js',
            array('wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n'),
            PCP_VERSION
        );

        // Register the block with all attributes
        register_block_type('product-card-plugin/product-card', array(
            'editor_script' => 'product-card-block',
            'render_callback' => array($this, 'render_product_card_block'),
            'attributes' => array(
                'status' => array(
                    'type' => 'string',
                    'default' => 'legit'
                ),
                'productName' => array(
                    'type' => 'string',
                    'default' => 'Gutoptim'
                ),
                'productCategory' => array(
                    'type' => 'string',
                    'default' => 'Supplement'
                ),
                'imageUrl' => array(
                    'type' => 'string',
                    'default' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=800'
                ),
                'userExperience' => array(
                    'type' => 'string',
                    'default' => 'Fewer night bathroom trips, stronger urine flow, and better sleep quality'
                ),
                'observation' => array(
                    'type' => 'string',
                    'default' => 'Confirms prostate support via DHT (5-alpha reductase) inhibition and anti-inflammatory action'
                ),
                'verdict' => array(
                    'type' => 'string',
                    'default' => 'Gutoptim is a Genuine Prostate Support Supplement'
                ),
                'bestFor' => array(
                    'type' => 'string',
                    'default' => 'People over 50 with Urinary Track Infection'
                ),
                'rating' => array(
                    'type' => 'string',
                    'default' => '5'
                ),
                'certification' => array(
                    'type' => 'string',
                    'default' => 'FDA/GMP Approved'
                ),
                'moneyBackGuarantee' => array(
                    'type' => 'string',
                    'default' => '60 Days'
                ),
                'price' => array(
                    'type' => 'string',
                    'default' => '69'
                ),
                'originalPrice' => array(
                    'type' => 'string',
                    'default' => '99'
                ),
                'currency' => array(
                    'type' => 'string',
                    'default' => '$'
                ),
                'ctaLabel' => array(
                    'type' => 'string',
                    'default' => 'Buy Now'
                ),
                'ctaUrl' => array(
                    'type' => 'string',
                    'default' => '#'
                ),
                'headerColor' => array(
                    'type' => 'string',
                    'default' => '#000000'
                ),
                'reviewSummary' => array(
                    'type' => 'string',
                    'default' => ''
                ),
                'cardLanguage' => array(
                    'type' => 'string',
                    'default' => 'EN'
                )
            )





        ));
    }


    /**
     * Render product card block using attributes
     */
    public function render_product_card_block($attributes)
    {
        // Get attributes with defaults and sanitize
        $status = isset($attributes['status']) ? sanitize_text_field($attributes['status']) : 'legit';
        $productName = isset($attributes['productName']) ? sanitize_text_field($attributes['productName']) : 'Gutoptim';
        $productCategory = isset($attributes['productCategory']) ? sanitize_text_field($attributes['productCategory']) : 'Supplement';
        $imageUrl = isset($attributes['imageUrl']) ? esc_url_raw($attributes['imageUrl']) : '';
        $userExperience = isset($attributes['userExperience']) ? sanitize_text_field($attributes['userExperience']) : '';
        if (mb_strlen($userExperience) > 95) {
            $userExperience = mb_strimwidth($userExperience, 0, 95, '...');
        }

        $observation = isset($attributes['observation']) ? sanitize_text_field($attributes['observation']) : '';
        if (mb_strlen($observation) > 95) {
            $observation = mb_strimwidth($observation, 0, 95, '...');
        }

        $verdict = isset($attributes['verdict']) ? sanitize_text_field($attributes['verdict']) : '';
        if (mb_strlen($verdict) > 70) {
            $verdict = mb_strimwidth($verdict, 0, 70, '...');
        }

        $bestFor = isset($attributes['bestFor']) ? sanitize_text_field($attributes['bestFor']) : '';
        $rating = isset($attributes['rating']) ? sanitize_text_field($attributes['rating']) : '5';
        $certification = isset($attributes['certification']) ? sanitize_text_field($attributes['certification']) : '';
        $moneyBackGuarantee = isset($attributes['moneyBackGuarantee']) ? sanitize_text_field($attributes['moneyBackGuarantee']) : '';
        $price = isset($attributes['price']) ? sanitize_text_field($attributes['price']) : '69';
        $originalPrice = isset($attributes['originalPrice']) ? sanitize_text_field($attributes['originalPrice']) : '99';
        $currency = isset($attributes['currency']) ? sanitize_text_field($attributes['currency']) : '$';
        $ctaLabel = isset($attributes['ctaLabel']) ? sanitize_text_field($attributes['ctaLabel']) : 'Buy Now';
        $ctaUrl = isset($attributes['ctaUrl']) ? esc_url_raw($attributes['ctaUrl']) : '#';
        $headerColor = isset($attributes['headerColor']) ? sanitize_hex_color($attributes['headerColor']) : '#000000';
        $reviewSummary = isset($attributes['reviewSummary']) ? sanitize_text_field($attributes['reviewSummary']) : '';
        $cardLanguage = isset($attributes['cardLanguage']) ? sanitize_text_field($attributes['cardLanguage']) : 'EN';

        // Use ACF fields for all product card values if they exist
        if (function_exists('get_field')) {
            $acfProductName = get_field('product_name');
            $acfImageUrl = get_field('product_image');
            $acfPrice = get_field('price');
            $acfCurrency = get_field('currency');
            $acfRating = get_field('rating');
            $acfCertification = get_field('certification');
            $acfMoneyBack = get_field('return_policy');
            $acfCtaUrl = get_field('buy_link');

            if ($acfProductName) $productName = sanitize_text_field($acfProductName);
            if ($acfImageUrl) $imageUrl = esc_url_raw($acfImageUrl);
            if ($acfPrice) $price = sanitize_text_field($acfPrice);
            if ($acfCurrency) $currency = sanitize_text_field($acfCurrency);
            if ($acfRating) $rating = sanitize_text_field($acfRating);
            if ($acfCertification) $certification = sanitize_text_field($acfCertification);
            if ($acfMoneyBack) $moneyBackGuarantee = sanitize_text_field($acfMoneyBack);
            if ($acfCtaUrl) $ctaUrl = esc_url_raw($acfCtaUrl);
        }

        // Automatically calculate original price (100% increase)
        $numericPrice = floatval(preg_replace('/[^0-9.]/', '', $price));
        $originalPrice = $numericPrice > 0 ? ceil($numericPrice * 2) : '';

        $labels = array(
            'EN' => array(
                'review_summary' => 'Review Summary',
                'user_experience' => 'User Experience',
                'our_observation' => 'Our Observation',
                'verdict' => 'Verdict',
                'expert_rating' => 'Expert Rating',
                'best_for' => 'Best for',
                'certification' => 'Certification',
                'money_back' => 'Money Back Guarantee',
                'buy_now' => 'Buy Now',
                'from_official' => 'from Official Website'
            ),
            'Ge' => array(
                'review_summary' => 'Erfahrungen Zusammenfassung',
                'user_experience' => 'Nutzererfahrung',
                'our_observation' => 'Unsere Beobachtung',
                'verdict' => 'Urteil',
                'expert_rating' => 'Expertenbewertung',
                'best_for' => 'Am besten geeignet für',
                'certification' => 'Zertifizierung',
                'money_back' => 'Geld-zurück-Garantie',
                'buy_now' => 'Jetzt kaufen',
                'from_official' => 'von der offiziellen Website'
            ),
            'Du' => array(
                'review_summary' => 'Samenvatting van de recensie',
                'user_experience' => 'Gebruikerservaring',
                'our_observation' => 'Onze observatie',
                'verdict' => 'Oordeel',
                'expert_rating' => 'Beoordeling van de expert',
                'best_for' => 'Het beste voor',
                'certification' => 'Certificering',
                'money_back' => 'Geld-terug-garantie',
                'buy_now' => 'Nu kopen',
                'from_official' => 'van de officiële website'
            ),
            'SP' => array(
                'review_summary' => 'Resumen de la reseña',
                'user_experience' => 'Experiencia del usuario',
                'our_observation' => 'Nuestra observación',
                'verdict' => 'Veredicto',
                'expert_rating' => 'Calificación del experto',
                'best_for' => 'Ideal para',
                'certification' => 'Certificación',
                'money_back' => 'Garantía de devolución de dinero',
                'buy_now' => 'Comprar ahora',
                'from_official' => 'del sitio web oficial'
            ),
            'FE' => array(
                'review_summary' => 'Résumé de l\'avis',
                'user_experience' => 'Expérience utilisateur',
                'our_observation' => 'Notre observation',
                'verdict' => 'Verdict',
                'expert_rating' => 'Note de l\'expert',
                'best_for' => 'Idéal pour',
                'certification' => 'Certification',
                'money_back' => 'Garantie de remboursement',
                'buy_now' => 'Acheter maintenant',
                'from_official' => 'du site officiel'
            ),
            'ITL' => array(
                'review_summary' => 'Sintesi della recensione',
                'user_experience' => 'Esperienza utente',
                'our_observation' => 'La nostra osservazione',
                'verdict' => 'Verdetto',
                'expert_rating' => 'Valutazione dell\'esperto',
                'best_for' => 'Ideale per',
                'certification' => 'Certificazione',
                'money_back' => 'Garanzia di rimborso',
                'buy_now' => 'Acquista ora',
                'from_official' => 'dal sito ufficiale'
            ),
            'SW' => array(
                'review_summary' => 'Sammanfattning av recensionen',
                'user_experience' => 'Användarupplevelse',
                'our_observation' => 'Vår observation',
                'verdict' => 'Omdöme',
                'expert_rating' => 'Expertbetyg',
                'best_for' => 'Bäst för',
                'certification' => 'Certifiering',
                'money_back' => 'Pengarna tillbaka-garanti',
                'buy_now' => 'Köp nu',
                'from_official' => 'från den officiella webbplatsen'
            )
        );

        $langLabels = isset($labels[$cardLanguage]) ? $labels[$cardLanguage] : $labels['EN'];
        $ctaLabel = $langLabels['buy_now'];
        $ctaSubLabel = $langLabels['from_official'];
        $displayCurrency = ($cardLanguage === 'EN') ? $currency : '€';

        $statusClass = $status === 'scam' ? 'pcpx-status-scam' : 'pcpx-status-legit';
        $statusLabel = ucfirst($status);
        $cardClass = $status === 'scam' ? 'pcpx-card-scam' : 'pcpx-card-legit';
        $reviewHeaderColor = '#000000';
        $reviewHeaderText = ($productName ? $productName . ' ' : '') . $langLabels['review_summary'];

        ob_start();
        ?>
        <div class="pcpx-product-card-outer">
            <div class="pcpx-card <?php echo esc_attr($cardClass); ?>">
                <div class="pcpx-review-summary-header-main" style="color: <?php echo esc_attr($reviewHeaderColor); ?>;">
                    <?php echo esc_html($reviewHeaderText); ?>
                </div>
                <div class="pcpx-card-inner">
                    <div class="pcpx-image-column">
                        <div class="pcpx-image-container">
                            <?php if ($imageUrl): ?>
                                <img src="<?php echo esc_url($imageUrl); ?>" alt="<?php echo esc_attr($productName); ?>"
                                    class="pcpx-product-image" />
                            <?php else: ?>
                                <div class="pcpx-image-placeholder">No Image</div>
                            <?php endif; ?>
                        </div>
                        <div class="pcpx-price-section">
                            <?php if ($originalPrice && $originalPrice !== $price): ?>
                                <span class="pcpx-original-price">
                                    <?php echo esc_html($displayCurrency . $originalPrice); ?>
                                </span>
                            <?php endif; ?>
                            <span class="pcpx-price">
                                <?php echo esc_html((strpos(trim($price), $displayCurrency) === 0 ? '' : $displayCurrency) . $price); ?>
                            </span>
                        </div>
                        <a href="<?php echo esc_url($ctaUrl); ?>" class="pcpx-cta-button" target="_blank" rel="noopener">
                            <span class="pcpx-cta-main-text"><?php echo esc_html($ctaLabel); ?></span>
                            <span class="pcpx-cta-sub-text"><?php echo esc_html($ctaSubLabel); ?></span>
                        </a>
                    </div>
                    <div class="pcpx-content-column">

                        <?php if ($userExperience): ?>
                            <div class="pcpx-section">
                                <div class="pcpx-section-label" data-icon="experience"
                                    style="color: <?php echo esc_attr($headerColor); ?>;"><?php echo esc_html($langLabels['user_experience']); ?></div>
                                <p class="pcpx-section-text"><?php echo esc_html($userExperience); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($observation): ?>
                            <div class="pcpx-section">
                                <div class="pcpx-section-label" data-icon="observation"
                                    style="color: <?php echo esc_attr($headerColor); ?>;"><?php echo esc_html($langLabels['our_observation']); ?></div>
                                <p class="pcpx-section-text"><?php echo esc_html($observation); ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if ($verdict): ?>
                            <div class="pcpx-verdict-box">
                                <span class="pcpx-verdict-icon"><?php echo $status === 'scam' ? '✕' : '✓'; ?></span>
                                <div><strong><?php echo esc_html($langLabels['verdict']); ?>:</strong> <?php echo esc_html($verdict); ?></div>
                            </div>
                        <?php endif; ?>

                        <div class="pcpx-info-grid">
                            <?php if ($rating): ?>
                                <div class="pcpx-info-item">
                                    <div class="pcpx-info-label" data-icon="rating"
                                        style="color: <?php echo esc_attr($headerColor); ?>;"><?php echo esc_html($langLabels['expert_rating']); ?></div>
                                    <div class="pcpx-info-value pcpx-rating-value">
                                        <?php
                                        $ratingValue = floatval($rating);
                                        $fullStars = floor($ratingValue);
                                        $halfStar = ($ratingValue - $fullStars) >= 0.5 ? 1 : 0;
                                        $emptyStars = 5 - $fullStars - $halfStar;
                                        echo '<span class="pcpx-stars">';
                                        echo str_repeat('★', $fullStars);
                                        if ($halfStar) {
                                            echo '<span style="position:relative;display:inline-block;width:1em;">';
                                            echo '<span style="color:#fbbf24;position:absolute;left:0;width:50%;overflow:hidden;">★</span>';
                                            echo '<span style="color:#e5e7eb;">★</span>';
                                            echo '</span>';
                                        }
                                        echo str_repeat('☆', $emptyStars);
                                        echo '</span>';
                                        echo ' ' . $ratingValue . '/5';
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($bestFor): ?>
                                <div class="pcpx-info-item">
                                    <div class="pcpx-info-label" data-icon="people"
                                        style="color: <?php echo esc_attr($headerColor); ?>;"><?php echo esc_html($langLabels['best_for']); ?></div>
                                    <div class="pcpx-info-value"><?php echo esc_html($bestFor); ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if ($certification): ?>
                                <div class="pcpx-info-item" data-certification="true">
                                    <div class="pcpx-info-label" style="color: <?php echo esc_attr($headerColor); ?>;"><?php echo esc_html($langLabels['certification']); ?></div>
                                    <div class="pcpx-info-value"><?php echo esc_html($certification); ?></div>
                                </div>
                            <?php endif; ?>

                            <?php if ($moneyBackGuarantee): ?>
                                <div class="pcpx-info-item">
                                    <div class="pcpx-info-label" data-icon="guarantee"
                                        style="color: <?php echo esc_attr($headerColor); ?>;"><?php echo esc_html($langLabels['money_back']); ?></div>
                                    <div class="pcpx-info-value"><?php echo esc_html($moneyBackGuarantee); ?></div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Initialize the plugin
new ProductCardPlugin();
