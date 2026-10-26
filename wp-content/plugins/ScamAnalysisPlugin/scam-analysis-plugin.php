<?php
/**
 * Plugin Name: Scam Analysis Summary
 * Plugin URI: https://supplementdolphin.com
 * Description: Gutenberg block that renders a Scam Analysis Summary card with product image on the left and rating, summary bullets, and verdict on the right. Insert with /scam-summary.
 * Version: 1.1.0
 * Author: Bishow Shrestha
 * Author URI: https://supplementdolphin.com
 * License: GPL v2 or later
 * Text Domain: scam-analysis-plugin
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Plugin constants
define( 'SAP_VERSION',    '1.1.0' );
define( 'SAP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'SAP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

class ScamAnalysisPlugin {

    public function __construct() {
        add_action( 'wp_enqueue_scripts',          [ $this, 'enqueue_assets' ] );
        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );
        add_action( 'init',                        [ $this, 'register_block' ] );
    }

    public function enqueue_assets() {
        wp_enqueue_style(
            'scam-analysis-styles',
            SAP_PLUGIN_URL . 'assets/css/scam-analysis.css',
            [],
            filemtime( SAP_PLUGIN_DIR . 'assets/css/scam-analysis.css' )
        );
    }

    public function enqueue_block_editor_assets() {
        wp_enqueue_style(
            'scam-analysis-editor-styles',
            SAP_PLUGIN_URL . 'assets/css/scam-analysis.css',
            [],
            filemtime( SAP_PLUGIN_DIR . 'assets/css/scam-analysis.css' )
        );
    }

    public function register_block() {

        wp_register_script(
            'scam-analysis-block',
            SAP_PLUGIN_URL . 'assets/js/block.js',
            [ 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ],
            SAP_VERSION
        );

        register_block_type( 'scam-analysis-plugin/scam-summary', [
            'editor_script'   => 'scam-analysis-block',
            'render_callback' => [ $this, 'render_block' ],
            'attributes'      => [
                'productName' => [
                    'type'    => 'string',
                    'default' => 'LipoTrine',
                ],
                'imageSize' => [
                    'type'    => 'string',
                    'default' => '400x400',
                ],
                'imageUrl' => [
                    'type'    => 'string',
                    'default' => '',
                ],
                'imageId' => [
                    'type'    => 'number',
                    'default' => 0,
                ],
                'rating' => [
                    'type'    => 'string',
                    'default' => '1.5',
                ],
                /* Four bullet point texts */
                'bullet1' => [
                    'type'    => 'string',
                    'default' => 'Customers experience zero noticeable results in weight loss, energy boost, or appetite suppression.',
                ],
                'bullet2' => [
                    'type'    => 'string',
                    'default' => 'Completely lacks clinical evidence, third-party testing, and credible manufacturer transparency.',
                ],
                'bullet3' => [
                    'type'    => 'string',
                    'default' => 'Users report severe side effects including jitters, headaches, and digestive issues without any fat-burning benefits.',
                ],
                'bullet4' => [
                    'type'    => 'string',
                    'default' => 'Relies on deceptive marketing, fake endorsements, and shady refund policies to trap buyers.',
                ],
                'verdict' => [
                    'type'    => 'string',
                    'default' => 'Complete Scam',
                ],
            ],
        ] );
    }

    // ---------------------------------------------------------------
    // Server-side render
    // ---------------------------------------------------------------
    public function render_block( $attributes ) {

        $productName = sanitize_text_field( $attributes['productName'] ?? 'LipoTrine' );
        $imageSize   = sanitize_text_field( $attributes['imageSize'] ?? '400x400' );
        $imageUrl    = esc_url_raw( $attributes['imageUrl'] ?? '' );
        $rating      = sanitize_text_field( $attributes['rating'] ?? '1.5' );
        $bullet1     = sanitize_textarea_field( $attributes['bullet1'] ?? '' );
        $bullet2     = sanitize_textarea_field( $attributes['bullet2'] ?? '' );
        $bullet3     = sanitize_textarea_field( $attributes['bullet3'] ?? '' );
        $bullet4     = sanitize_textarea_field( $attributes['bullet4'] ?? '' );
        $verdict     = sanitize_text_field( $attributes['verdict'] ?? 'Complete Scam' );

        /* ACF overrides */
        if ( function_exists( 'get_field' ) ) {
            $acfName  = get_field( 'product_name' );
            $acfImage = get_field( 'product_image' );
            if ( $acfName )  $productName = sanitize_text_field( $acfName );
            if ( $acfImage ) $imageUrl    = esc_url_raw( $acfImage );
        }

        /* Star rating */
        $ratingValue = floatval( $rating );
        $fullStars   = (int) floor( $ratingValue );
        $halfStar    = ( $ratingValue - $fullStars ) >= 0.5 ? 1 : 0;
        $emptyStars  = 5 - $fullStars - $halfStar;

        $starsHtml  = '<span class="sap-stars">';
        $starsHtml .= str_repeat( '&#9733;', $fullStars );
        if ( $halfStar ) {
            $starsHtml .= '<span class="sap-half-star"><span class="sap-half-fill">&#9733;</span><span class="sap-half-empty">&#9733;</span></span>';
        }
        $starsHtml .= str_repeat( '<span class="sap-star-empty">&#9733;</span>', $emptyStars );
        $starsHtml .= '</span>';

        /* Bullets (only non-empty) */
        $bullets = array_filter( [ $bullet1, $bullet2, $bullet3, $bullet4 ] );

        ob_start();
        ?>
<div class="sap-outer">
    <div class="sap-card">

        <!-- Title bar -->
        <div class="sap-title-bar">
            <span class="sap-title-name"><?php echo esc_html( $productName ); ?></span>
            <span class="sap-title-suffix">Scam Analysis Summary</span>
        </div>

        <!-- Body -->
        <div class="sap-body sap-size-<?php echo esc_attr( $imageSize ); ?>">

            <!-- LEFT: image only -->
            <div class="sap-left">
                <div class="sap-image-wrap">
                    <?php if ( $imageUrl ) : ?>
                        <img src="<?php echo esc_url( $imageUrl ); ?>"
                             alt="<?php echo esc_attr( $productName ); ?>"
                             class="sap-product-img" />
                    <?php else : ?>
                        <div class="sap-img-placeholder">No Image</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RIGHT: rating + summary bullets + verdict -->
            <div class="sap-right">

                <!-- Rating -->
                <div class="sap-rating-row">
                    <span class="sap-rating-label">Rating:</span>
                    <?php echo $starsHtml; ?>
                    <span class="sap-rating-number"><?php echo esc_html( number_format( $ratingValue, 1 ) . '/5' ); ?></span>
                </div>

                <!-- Summary subheading -->
                <div class="sap-summary-heading">Summary</div>

                <!-- Bullet list -->
                <?php if ( $bullets ) : ?>
                <ul class="sap-bullet-list">
                    <?php foreach ( $bullets as $bullet ) : ?>
                    <li class="sap-bullet-item">
                        <span class="sap-cross" aria-hidden="true">&#10007;</span>
                        <span class="sap-bullet-text"><?php echo esc_html( $bullet ); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <!-- Verdict -->
                <div class="sap-verdict">
                    <span class="sap-verdict-icon-wrap" aria-hidden="true">&#9888;</span>
                    <span class="sap-verdict-label">Verdict:</span>
                    <span class="sap-verdict-text"><?php echo esc_html( $verdict ); ?></span>
                </div>

            </div><!-- /.sap-right -->

        </div><!-- /.sap-body -->

    </div><!-- /.sap-card -->
</div><!-- /.sap-outer -->
        <?php
        return ob_get_clean();
    }
}

new ScamAnalysisPlugin();
