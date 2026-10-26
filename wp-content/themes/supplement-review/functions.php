<?php
/**
 * Supplement Review functions and definitions
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SR_VERSION', '1.0.0' );
define( 'SR_DIR', get_template_directory() );
define( 'SR_URI', get_template_directory_uri() );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function sr_setup() {
	// Make theme available for translation
	load_theme_textdomain( 'supplement-review', SR_DIR . '/languages' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages
	add_theme_support( 'post-thumbnails' );

	// Custom Image Sizes
	add_image_size( 'sr-card', 600, 450, true );
	add_image_size( 'sr-product-thumb', 400, 500, false );

	// Custom Logo Support
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 60,
			'width'       => 220,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	// Register Navigation Menus
	register_nav_menus(
		array(
			'primary'           => esc_html__( 'Primary Menu', 'supplement-review' ),
			'footer_links'      => esc_html__( 'Footer Quick Links', 'supplement-review' ),
			'footer_categories' => esc_html__( 'Footer Categories', 'supplement-review' ),
			'footer_legal'      => esc_html__( 'Footer Legal Bar', 'supplement-review' ),
		)
	);

	// Switch default core markup for search form, comment form, etc. to HTML5
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
		)
	);

	// Gutenberg / Block Editor Support
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-style.css' );
	// WooCommerce Support (Basic compatibility)
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'sr_setup' );

/**
 * Register widget areas.
 */
function sr_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Main Sidebar', 'supplement-review' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'supplement-review' ),
			'before_widget' => '<section id="%1$s" class="widget sr-card %2$s" style="margin-bottom:24px;">',
			'after_widget'  => '</section>',
			'before_title'  => '<h3 class="widget-title" style="font-size:1.15rem;margin-bottom:14px;">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Column 1 (About/Contact)', 'supplement-review' ),
			'id'            => 'footer-1',
			'description'   => esc_html__( 'Appears in the first column of the footer.', 'supplement-review' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Column 2 (Links)', 'supplement-review' ),
			'id'            => 'footer-2',
			'description'   => esc_html__( 'Appears in the second column of the footer.', 'supplement-review' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Column 3 (Categories)', 'supplement-review' ),
			'id'            => 'footer-3',
			'description'   => esc_html__( 'Appears in the third column of the footer.', 'supplement-review' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Column 4 (Newsletter/Legal)', 'supplement-review' ),
			'id'            => 'footer-4',
			'description'   => esc_html__( 'Appears in the fourth column of the footer.', 'supplement-review' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}
add_action( 'widgets_init', 'sr_widgets_init' );

/**
 * Preload self-hosted Latin WOFF2 fonts in head for lightning-fast first paint.
 */
function sr_preload_fonts() {
	?>
	<link rel="preload" href="<?php echo esc_url( SR_URI . '/assets/fonts/inter-400.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
	<link rel="preload" href="<?php echo esc_url( SR_URI . '/assets/fonts/montserrat-600.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
	<?php
}
add_action( 'wp_head', 'sr_preload_fonts', 1 );

/**
 * Inline dark-mode pre-paint script to prevent Flash of Unstyled Theme (FOUC).
 */
function sr_inline_dark_mode_script() {
	$nonce      = apply_filters( 'sr_csp_nonce', '' );
	$nonce_attr = ! empty( $nonce ) ? ' nonce="' . esc_attr( $nonce ) . '"' : '';
	?>
	<script<?php echo $nonce_attr; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
		(function(){
			try {
				var saved = localStorage.getItem('sr_theme_mode');
				if (saved) {
					document.documentElement.setAttribute('data-theme', saved);
				} else if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
					document.documentElement.setAttribute('data-theme', 'dark');
				}
			} catch(e) {}
		})();
	</script>
	<?php
}
add_action( 'wp_head', 'sr_inline_dark_mode_script', 1 );

/**
 * Enqueue scripts and styles.
 */
function sr_scripts() {
	// Main theme stylesheet
	wp_enqueue_style( 'supplement-review-style', get_stylesheet_uri(), array(), SR_VERSION );

	// RTL support
	wp_style_add_data( 'supplement-review-style', 'rtl', 'replace' );

	// Main JavaScript (Vanilla ES6+ with defer)
	wp_enqueue_script(
		'supplement-review-main',
		SR_URI . '/assets/js/main.js',
		array(),
		SR_VERSION,
		array(
			'strategy'  => 'defer',
			'in_footer' => true,
		)
	);

	// Conditional Review Page Assets (Performance: only load on single review)
	if ( is_singular( 'post' ) ) {
		wp_enqueue_style(
			'supplement-review-single',
			SR_URI . '/assets/css/single-review.css',
			array( 'supplement-review-style' ),
			SR_VERSION
		);

		wp_enqueue_style(
			'supplement-review-product-summary',
			SR_URI . '/assets/css/product-summary.css',
			array( 'supplement-review-style' ),
			SR_VERSION
		);

		wp_enqueue_style(
			'supplement-review-author-card',
			SR_URI . '/assets/css/author-card.css',
			array( 'supplement-review-style' ),
			SR_VERSION
		);

		wp_enqueue_style(
			'supplement-review-toc',
			SR_URI . '/assets/css/toc.css',
			array( 'supplement-review-style' ),
			SR_VERSION
		);

		wp_enqueue_script(
			'supplement-review-toc-js',
			SR_URI . '/assets/js/toc.js',
			array( 'supplement-review-main' ),
			SR_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		wp_enqueue_script(
			'supplement-review-single-js',
			SR_URI . '/assets/js/single-review.js',
			array( 'supplement-review-main' ),
			SR_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		wp_enqueue_script(
			'supplement-review-product-summary-js',
			SR_URI . '/assets/js/product-summary.js',
			array(),
			SR_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);
	}

	// Threaded comments reply script
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'sr_scripts' );

/**
 * Includes modular theme components
 */
require_once SR_DIR . '/inc/svg-icons.php';
require_once SR_DIR . '/inc/template-functions.php';
require_once SR_DIR . '/inc/template-tags.php';
require_once SR_DIR . '/inc/author-meta.php';
require_once SR_DIR . '/inc/customizer.php';
require_once SR_DIR . '/inc/meta-boxes.php';
require_once SR_DIR . '/inc/toc.php';
require_once SR_DIR . '/inc/review-comments.php';
require_once SR_DIR . '/inc/schema.php';
require_once SR_DIR . '/inc/patterns.php';
