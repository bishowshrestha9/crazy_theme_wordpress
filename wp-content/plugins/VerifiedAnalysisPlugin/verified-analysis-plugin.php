<?php
/**
 * Plugin Name: Verified Analysis Summary
 * Plugin URI: https://supplementdolphin.com
 * Description: Gutenberg block that renders a Verified Analysis Summary card with product image on the left and rating, summary bullets, and verdict (+ Buy Now button) on the right. Insert with /verified-summary.
 * Version: 1.0.0
 * Author: Bishow Shrestha
 * Author URI: https://supplementdolphin.com
 * License: GPL v2 or later
 * Text Domain: verified-analysis-plugin
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Plugin constants – guarded so the plugin survives accidental double-loading
if ( ! defined( 'VAP_VERSION' ) )    define( 'VAP_VERSION',    '1.0.0' );
if ( ! defined( 'VAP_PLUGIN_DIR' ) ) define( 'VAP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
if ( ! defined( 'VAP_PLUGIN_URL' ) ) define( 'VAP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

class VerifiedAnalysisPlugin {

    public function __construct() {
        add_action( 'wp_enqueue_scripts',          [ $this, 'enqueue_assets' ] );
        add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_block_editor_assets' ] );
        add_action( 'init',                        [ $this, 'register_block' ] );
    }

    public function enqueue_assets() {
        $css_file = VAP_PLUGIN_DIR . 'assets/css/verified-analysis.css';
        wp_enqueue_style(
            'verified-analysis-styles',
            VAP_PLUGIN_URL . 'assets/css/verified-analysis.css',
            [],
            file_exists( $css_file ) ? filemtime( $css_file ) : VAP_VERSION
        );
    }

    public function enqueue_block_editor_assets() {
        // Only load editor assets for users who can actually edit content
        if ( ! current_user_can( 'edit_posts' ) ) {
            return;
        }
        $css_file = VAP_PLUGIN_DIR . 'assets/css/verified-analysis.css';
        wp_enqueue_style(
            'verified-analysis-editor-styles',
            VAP_PLUGIN_URL . 'assets/css/verified-analysis.css',
            [],
            file_exists( $css_file ) ? filemtime( $css_file ) : VAP_VERSION
        );
    }

    // ---------------------------------------------------------------
    // Strict URL helper – allows only http / https; returns '' otherwise
    // ---------------------------------------------------------------
    private function safe_url( $raw ) {
        $url = esc_url_raw( trim( $raw ), [ 'http', 'https' ] );
        // esc_url_raw with an allowlist still lets empty '#' through; keep it
        if ( '' === $url && '#' === trim( $raw ) ) {
            return '#';
        }
        return $url;
    }

    public function register_block() {

        $js_file = VAP_PLUGIN_DIR . 'assets/js/block.js';
        wp_register_script(
            'verified-analysis-block',
            VAP_PLUGIN_URL . 'assets/js/block.js',
            [ 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ],
            file_exists( $js_file ) ? filemtime( $js_file ) : VAP_VERSION,
            true  // load in footer – non-blocking
        );

        register_block_type( 'verified-analysis-plugin/verified-summary', [
            'editor_script'   => 'verified-analysis-block',
            'render_callback' => [ $this, 'render_block' ],
            'attributes'      => [
                'productName' => [
                    'type'    => 'string',
                    'default' => 'Product Name',
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
                    'default' => '4.5',
                ],
                /* Four bullet point texts */
                'bullet1' => [
                    'type'    => 'string',
                    'default' => 'Clinically tested formula with proven results backed by independent lab reports.',
                ],
                'bullet2' => [
                    'type'    => 'string',
                    'default' => 'Transparent manufacturer details, third-party tested, and FDA-registered facility.',
                ],
                'bullet3' => [
                    'type'    => 'string',
                    'default' => 'Thousands of satisfied customers reporting significant improvements with no serious side effects.',
                ],
                'bullet4' => [
                    'type'    => 'string',
                    'default' => 'Honest pricing, clear refund policy, and genuine customer support with real reviews.',
                ],
                'verdict' => [
                    'type'    => 'string',
                    'default' => 'Verified',
                ],
                'buyNowUrl' => [
                    'type'    => 'string',
                    'default' => '#',
                ],
                'buyNowText' => [
                    'type'    => 'string',
                    'default' => 'Buy Now',
                ],
            ],
        ] );
    }

    // ---------------------------------------------------------------
    // Server-side render
    // ---------------------------------------------------------------
    public function render_block( $attributes ) {

        // Ensure $attributes is an array (defensive check)
        if ( ! is_array( $attributes ) ) {
            $attributes = [];
        }

        $productName = sanitize_text_field( $attributes['productName'] ?? 'Product Name' );

        // Whitelist imageSize to known values only – prevents arbitrary CSS class injection
        $allowed_sizes = [ '320x320', '400x400', '500x500' ];
        $imageSize     = in_array( $attributes['imageSize'] ?? '', $allowed_sizes, true )
                         ? $attributes['imageSize']
                         : '400x400';

        // Only allow http/https image URLs – blocks javascript: and data: URIs
        $imageUrl   = $this->safe_url( $attributes['imageUrl'] ?? '' );

        // Clamp rating to valid 0–5 range with 0.5-step precision
        $ratingRaw  = floatval( $attributes['rating'] ?? 4.5 );
        $ratingRaw  = max( 0.0, min( 5.0, $ratingRaw ) );
        $rating     = number_format( round( $ratingRaw * 2 ) / 2, 1 ); // snap to nearest 0.5

        $bullet1    = sanitize_textarea_field( $attributes['bullet1'] ?? '' );
        $bullet2    = sanitize_textarea_field( $attributes['bullet2'] ?? '' );
        $bullet3    = sanitize_textarea_field( $attributes['bullet3'] ?? '' );
        $bullet4    = sanitize_textarea_field( $attributes['bullet4'] ?? '' );
        $verdict    = sanitize_text_field( $attributes['verdict'] ?? 'Verified' );

        // Only allow http/https buy-now URLs; fall back to '#'
        $buyNowUrl  = $this->safe_url( $attributes['buyNowUrl'] ?? '#' );
        if ( '' === $buyNowUrl ) $buyNowUrl = '#';
        $buyNowText = sanitize_text_field( $attributes['buyNowText'] ?? 'Buy Now' );

        /* ACF overrides */
        if ( function_exists( 'get_field' ) ) {
            $acfName  = get_field( 'product_name' );
            $acfImage = get_field( 'product_image' );
            if ( $acfName )  $productName = sanitize_text_field( $acfName );
            if ( $acfImage ) $imageUrl    = esc_url_raw( $acfImage );
        }

        /* Star rating – $rating is already clamped and snapped to 0.5 steps */
        $ratingValue = floatval( $rating );
        $fullStars   = (int) floor( $ratingValue );
        $halfStar    = ( $ratingValue - $fullStars ) >= 0.5 ? 1 : 0;
        $emptyStars  = max( 0, 5 - $fullStars - $halfStar ); // floor to 0 defensively

        $starsHtml  = '<span class="vap-stars">';
        $starsHtml .= str_repeat( '&#9733;', $fullStars );
        if ( $halfStar ) {
            $starsHtml .= '<span class="vap-half-star"><span class="vap-half-fill">&#9733;</span><span class="vap-half-empty">&#9733;</span></span>';
        }
        $starsHtml .= str_repeat( '<span class="vap-star-empty">&#9733;</span>', $emptyStars );
        $starsHtml .= '</span>';

        /* Bullets (only non-empty) */
        $bullets = array_filter( [ $bullet1, $bullet2, $bullet3, $bullet4 ] );

        /* Show Buy Now button when verdict is "Verified" or "Complete Scam" */
        $verdictLower = strtolower( trim( $verdict ) );
        $showBuyNow   = in_array( $verdictLower, [ 'verified', 'complete scam' ], true );

        ob_start();
        ?>
<div class="vap-outer">
    <div class="vap-card">

        <!-- Title bar -->
        <div class="vap-title-bar">
            <span class="vap-title-name"><?php echo esc_html( $productName ); ?></span>
            <span class="vap-title-suffix">Verified Analysis Summary</span>
        </div>

        <!-- Body -->
        <div class="vap-body vap-size-<?php echo esc_attr( $imageSize ); ?>">

            <!-- LEFT: image only -->
            <div class="vap-left">
                <div class="vap-image-wrap">
                    <?php if ( $imageUrl ) : ?>
                        <img src="<?php echo esc_url( $imageUrl ); ?>"
                             alt="<?php echo esc_attr( $productName ); ?>"
                             class="vap-product-img" />
                    <?php else : ?>
                        <div class="vap-img-placeholder">No Image</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- RIGHT: rating + summary bullets + verdict -->
            <div class="vap-right">

                <!-- Rating -->
                <div class="vap-rating-row">
                    <span class="vap-rating-label">Rating:</span>
                    <?php echo $starsHtml; ?>
                    <span class="vap-rating-number"><?php echo esc_html( number_format( $ratingValue, 1 ) . '/5' ); ?></span>
                </div>

                <!-- Summary subheading -->
                <div class="vap-summary-heading">Summary</div>

                <!-- Bullet list -->
                <?php if ( $bullets ) : ?>
                <ul class="vap-bullet-list">
                    <?php foreach ( $bullets as $bullet ) : ?>
                    <li class="vap-bullet-item">
                        <span class="vap-check" aria-hidden="true">&#10003;</span>
                        <span class="vap-bullet-text"><?php echo esc_html( $bullet ); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <!-- Verdict -->
                <div class="vap-verdict">
                    <span class="vap-verdict-icon-wrap" aria-hidden="true">&#10003;</span>
                    <span class="vap-verdict-label">Verdict:</span>
                    <span class="vap-verdict-text"><?php echo esc_html( $verdict ); ?></span>
                    <?php if ( $showBuyNow ) : ?>
                    <a href="<?php echo esc_url( $buyNowUrl ); ?>"
                       class="vap-buy-now"
                       target="_blank"
                       rel="noopener noreferrer"><?php echo esc_html( $buyNowText ); ?></a>
                    <?php endif; ?>
                </div>

            </div><!-- /.vap-right -->

        </div><!-- /.vap-body -->

    </div><!-- /.vap-card -->
</div><!-- /.vap-outer -->
        <?php
        return ob_get_clean();
    }
}

// Singleton guard – prevents duplicate instantiation if plugin is included more than once
if ( ! isset( $GLOBALS['verified_analysis_plugin_instance'] ) ) {
    $GLOBALS['verified_analysis_plugin_instance'] = new VerifiedAnalysisPlugin();
}
