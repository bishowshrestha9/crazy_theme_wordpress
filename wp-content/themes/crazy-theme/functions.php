<?php
/**
 * CrazyTheme functions and definitions
 *
 * @package CrazyTheme
 */

if ( ! defined( 'CRAZYTHEME_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( 'CRAZYTHEME_VERSION', '1.0.2' );
}

function crazytheme_setup() {
	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails on posts and pages.
	add_theme_support( 'post-thumbnails' );

	// Register theme navigation menus
	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary', 'crazytheme' ),
			'menu-1'  => esc_html__( 'Primary (Legacy/Menu 1)', 'crazytheme' ),
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	// Add support for core custom logo.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	// Add HTML5 support
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
		)
	);

	// Custom Image Sizes for Supplement Review single post page
	add_image_size( 'sr-card', 600, 450, true );
	add_image_size( 'sr-product-thumb', 400, 500, false );
}
add_action( 'after_setup_theme', 'crazytheme_setup' );

/**
 * Enqueue scripts and styles.
 */
function crazytheme_scripts() {
	// Enqueue Google Fonts (Inter and Roboto)
	wp_enqueue_style( 'crazytheme-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Roboto:wght@300;400;500;700;900&display=swap', array(), null );

	wp_enqueue_style( 'crazytheme-style', get_stylesheet_uri(), array(), CRAZYTHEME_VERSION );
	
	// Enqueue header assets
	wp_enqueue_style( 'crazytheme-header-style', get_template_directory_uri() . '/assets/css/header.css', array(), CRAZYTHEME_VERSION );
	wp_enqueue_script( 'crazytheme-header-script', get_template_directory_uri() . '/assets/js/header.js', array(), CRAZYTHEME_VERSION, true );

	// Enqueue hero/homepage assets
	wp_enqueue_style( 'crazytheme-hero-style', get_template_directory_uri() . '/assets/css/hero.css', array(), CRAZYTHEME_VERSION );
	if ( is_front_page() || is_home() ) {
		wp_enqueue_style( 'crazytheme-categories-style', get_template_directory_uri() . '/assets/css/front-categories.css', array(), CRAZYTHEME_VERSION );
	}

	// Conditional Single Review Page Assets (Same to same as supplement-review)
	if ( is_singular( 'post' ) ) {
		wp_enqueue_style(
			'crazytheme-sr-fonts',
			'https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Montserrat:ital,wght@0,500;0,600;0,700;1,600&display=swap',
			array(),
			null
		);

		wp_enqueue_style(
			'crazytheme-single-base',
			get_template_directory_uri() . '/assets/css/single-base.css',
			array( 'crazytheme-style' ),
			CRAZYTHEME_VERSION
		);

		wp_enqueue_style(
			'crazytheme-single-review',
			get_template_directory_uri() . '/assets/css/single-review.css',
			array( 'crazytheme-single-base' ),
			CRAZYTHEME_VERSION
		);

		wp_enqueue_style(
			'crazytheme-product-summary',
			get_template_directory_uri() . '/assets/css/product-summary.css',
			array( 'crazytheme-single-review' ),
			CRAZYTHEME_VERSION
		);

		wp_enqueue_style(
			'crazytheme-author-card',
			get_template_directory_uri() . '/assets/css/author-card.css',
			array( 'crazytheme-single-review' ),
			CRAZYTHEME_VERSION
		);

		wp_enqueue_style(
			'crazytheme-toc',
			get_template_directory_uri() . '/assets/css/toc.css',
			array( 'crazytheme-single-review' ),
			CRAZYTHEME_VERSION
		);

		wp_enqueue_script(
			'crazytheme-toc-js',
			get_template_directory_uri() . '/assets/js/toc.js',
			array(),
			CRAZYTHEME_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		wp_enqueue_script(
			'crazytheme-single-review-js',
			get_template_directory_uri() . '/assets/js/single-review.js',
			array(),
			CRAZYTHEME_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		wp_enqueue_script(
			'crazytheme-product-summary-js',
			get_template_directory_uri() . '/assets/js/product-summary.js',
			array(),
			CRAZYTHEME_VERSION,
			array(
				'strategy'  => 'defer',
				'in_footer' => true,
			)
		);

		if ( comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'crazytheme_scripts' );

/**
 * Customizer settings for header.
 */
function crazytheme_customize_register( $wp_customize ) {
	// Hero Section
	$wp_customize->add_section( 'crazytheme_hero_options', array(
		'title'       => __( 'Hero Section', 'crazytheme' ),
		'priority'    => 35,
	) );

	// Hero Badge Text
	$wp_customize->add_setting( 'hero_badge_text', array(
		'default'           => '100% Transparent Data',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'hero_badge_text', array(
		'label'    => __( 'Badge Text', 'crazytheme' ),
		'section'  => 'crazytheme_hero_options',
		'type'     => 'text',
	) );

	// Hero Title
	$wp_customize->add_setting( 'hero_title', array(
		'default'           => 'Cut Through The Noise.<br><span class="text-highlight">Find What Works.</span>',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'hero_title', array(
		'label'    => __( 'Hero Title (HTML allowed)', 'crazytheme' ),
		'section'  => 'crazytheme_hero_options',
		'type'     => 'textarea',
	) );

	// Hero Description
	$wp_customize->add_setting( 'hero_description', array(
		'default'           => 'We aggressively analyze supplements, bio-hacking tools, and longevity protocols so you don\'t have to guess. Real data. Real results.',
		'sanitize_callback' => 'sanitize_textarea_field',
	) );
	$wp_customize->add_control( 'hero_description', array(
		'label'    => __( 'Hero Description', 'crazytheme' ),
		'section'  => 'crazytheme_hero_options',
		'type'     => 'textarea',
	) );
	$wp_customize->add_setting( 'header_background_color', array(
		'default'           => '#ffffff',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_background_color', array(
		'label'   => esc_html__( 'Header Background Color', 'crazytheme' ),
		'section' => 'colors',
	) ) );

	$wp_customize->add_setting( 'header_text_color', array(
		'default'           => '#333333',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_text_color', array(
		'label'   => esc_html__( 'Header Text Color', 'crazytheme' ),
		'section' => 'colors',
	) ) );

	// Top Supplements Section
	$wp_customize->add_section( 'crazytheme_top_supplements_options', array(
		'title'       => __( 'Top Supplements Section', 'crazytheme' ),
		'priority'    => 40,
		'description' => __( 'Manage the Top Supplements section on the front page.', 'crazytheme' ),
	) );

	// Post Source (Latest Posts vs Top Clicks)
	$wp_customize->add_setting( 'top_supplements_source', array(
		'default'           => 'latest',
		'sanitize_callback' => 'sanitize_key',
	) );
	$wp_customize->add_control( 'top_supplements_source', array(
		'label'       => __( 'Show Posts By', 'crazytheme' ),
		'description' => __( 'Choose whether to display Latest posts or Top Clicks.', 'crazytheme' ),
		'section'     => 'crazytheme_top_supplements_options',
		'type'        => 'select',
		'choices'     => array(
			'latest'     => __( 'Latest Posts', 'crazytheme' ),
			'top_clicks' => __( 'Top Clicks', 'crazytheme' ),
		),
	) );

	// Section Title
	$wp_customize->add_setting( 'top_supplements_title', array(
		'default'           => 'Top Supplements',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'top_supplements_title', array(
		'label'    => __( 'Section Title', 'crazytheme' ),
		'section'  => 'crazytheme_top_supplements_options',
		'type'     => 'text',
	) );

	// Section Subtitle
	$wp_customize->add_setting( 'top_supplements_subtitle', array(
		'default'           => 'Science-backed components with proven wellness benefits',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'top_supplements_subtitle', array(
		'label'    => __( 'Section Subtitle', 'crazytheme' ),
		'section'  => 'crazytheme_top_supplements_options',
		'type'     => 'text',
	) );

	// View All URL
	$wp_customize->add_setting( 'top_supplements_view_all_url', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
	) );
	$wp_customize->add_control( 'top_supplements_view_all_url', array(
		'label'    => __( 'View All URL', 'crazytheme' ),
		'section'  => 'crazytheme_top_supplements_options',
		'type'     => 'url',
	) );

	// Number of items to display
	$wp_customize->add_setting( 'top_supplements_count', array(
		'default'           => 5,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'top_supplements_count', array(
		'label'       => __( 'Number of Items', 'crazytheme' ),
		'section'     => 'crazytheme_top_supplements_options',
		'type'        => 'number',
		'input_attrs' => array(
			'min'  => 1,
			'max'  => 20,
			'step' => 1,
		),
	) );

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'header_background_color', array(
			'selector'        => '.site-header',
			'render_callback' => '__return_false',
			'fallback_refresh' => true,
		) );
		$wp_customize->selective_refresh->add_partial( 'header_text_color', array(
			'selector'        => '.site-header',
			'render_callback' => '__return_false',
			'fallback_refresh' => true,
		) );
	}
}
add_action( 'customize_register', 'crazytheme_customize_register' );

/**
 * Output Customizer inline CSS
 */
function crazytheme_customizer_css() {
	$bg_color   = get_theme_mod( 'header_background_color', '#ffffff' );
	$text_color = get_theme_mod( 'header_text_color', '#333333' );

	$css = "
		:root {
			--crazytheme-header-bg: " . esc_attr( $bg_color ) . ";
			--crazytheme-header-color: " . esc_attr( $text_color ) . ";
		}
	";
	wp_add_inline_style( 'crazytheme-header-style', wp_strip_all_tags( $css ) );
}
add_action( 'wp_enqueue_scripts', 'crazytheme_customizer_css', 20 );

/**
 * Cron algorithm to calculate "Hot Topics" (tags) daily
 * Based on post count and comment count
 */
function crazytheme_calculate_hot_tags() {
	$tags = get_tags(array('hide_empty' => true));
	$tag_scores = array();

	foreach ($tags as $tag) {
		// Exclude spotlight
		if ($tag->slug === 'spotlight') {
			continue;
		}

		// Base score: 10 points per post
		$score = $tag->count * 10;

		// Add engagement points (comments)
		$posts = get_posts(array(
			'tag_id' => $tag->term_id,
			'posts_per_page' => -1,
			'fields' => 'ids'
		));

		$total_engagement = 0;
		foreach ($posts as $post_id) {
			$post = get_post($post_id);
			if ($post) {
				// 5 points per comment
				$total_engagement += ($post->comment_count * 5);
				
				// Support for custom 'rating' meta field if added later
				$rating = get_post_meta($post_id, 'rating', true);
				if ($rating) {
					$total_engagement += ((int)$rating * 10); 
				}
			}
		}

		$score += $total_engagement;
		$tag_scores[$tag->term_id] = $score;
	}

	// Sort descending by score
	arsort($tag_scores);

	// Get top 4 tag IDs
	$top_tag_ids = array_slice(array_keys($tag_scores), 0, 4);

	$top_tags = array();
	foreach ($top_tag_ids as $tag_id) {
		$top_tags[] = get_term($tag_id);
	}

	// Cache the result
	update_option('crazytheme_hot_tags', $top_tags);
}
add_action( 'crazytheme_daily_hot_tags_event', 'crazytheme_calculate_hot_tags' );

// Schedule the event if it isn't already scheduled
if ( ! wp_next_scheduled( 'crazytheme_daily_hot_tags_event' ) ) {
	wp_schedule_event( time(), 'daily', 'crazytheme_daily_hot_tags_event' );
}


/**
 * Register post meta for Gutenberg REST API compatibility
 */
function crazytheme_register_sidebar_product_meta() {
    register_post_meta('post', '_sidebar_product_enable', array(
        'show_in_rest' => true,
        'single'       => true,
        'type'         => 'string',
        'auth_callback' => function() {
            return current_user_can('edit_posts');
        }
    ));
}
add_action('init', 'crazytheme_register_sidebar_product_meta');

/**
 * Check if the sidebar product summary card should be displayed on a post.
 *
 * @param int|null $post_id Post ID.
 * @return bool True if sidebar product summary card should be shown, false otherwise.
 */
function crazytheme_has_product_sidebar( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    if ( ! $post_id ) {
        return false;
    }

    // 1. Check meta key `_sidebar_product_enable`
    $enable_meta = get_post_meta( $post_id, '_sidebar_product_enable', true );
    if ( $enable_meta === 'no' || $enable_meta === '0' || $enable_meta === 'off' ) {
        return false;
    }
    if ( $enable_meta === 'yes' || $enable_meta === '1' || $enable_meta === 'on' ) {
        return true;
    }

    // 2. Check meta key `_show_sidebar_product` or `_disable_sidebar_product`
    $show_meta = get_post_meta( $post_id, '_show_sidebar_product', true );
    if ( $show_meta === 'no' || $show_meta === '0' ) {
        return false;
    }
    $disable_meta = get_post_meta( $post_id, '_disable_sidebar_product', true );
    if ( $disable_meta === 'yes' || $disable_meta === '1' ) {
        return false;
    }

    // 3. Check ACF fields if present
    if ( function_exists( 'get_field' ) ) {
        $acf_enable = get_field( 'enable_sidebar_product', $post_id );
        if ( $acf_enable === false || $acf_enable === '0' || $acf_enable === 0 ) {
            return false;
        }
        $acf_show = get_field( 'show_sidebar_product', $post_id );
        if ( $acf_show === false || $acf_show === '0' || $acf_show === 0 ) {
            return false;
        }
        $acf_disable = get_field( 'disable_sidebar_product', $post_id );
        if ( $acf_disable === true || $acf_disable === '1' || $acf_disable === 1 ) {
            return false;
        }
    }

    return true;
}

/* Sidebar Product Meta Box */
function crazytheme_add_product_meta_box() {
    add_meta_box(
        'crazytheme_product_sidebar',
        'Sidebar Product Settings',
        'crazytheme_product_meta_box_html',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'crazytheme_add_product_meta_box');

function crazytheme_product_meta_box_html($post) {
    wp_enqueue_media();
    $name = get_post_meta($post->ID, '_sidebar_product_name', true);
    $image = get_post_meta($post->ID, '_sidebar_product_image', true);
    $rating = get_post_meta($post->ID, '_sidebar_product_rating', true);
    $desc = get_post_meta($post->ID, '_sidebar_product_desc', true);
    $readmore = get_post_meta($post->ID, '_sidebar_product_readmore', true);
    $orig_price = get_post_meta($post->ID, '_sidebar_product_orig_price', true);
    $disc_price = get_post_meta($post->ID, '_sidebar_product_disc_price', true);
    $buy_url = get_post_meta($post->ID, '_sidebar_product_buy_url', true);
    
    $enable_sidebar = get_post_meta($post->ID, '_sidebar_product_enable', true);
    // Explicitly uncheck if set to 'no' or '0'; default to checked for new or existing posts
    $is_enabled = ($enable_sidebar !== 'no' && $enable_sidebar !== '0');
    
    wp_nonce_field('crazytheme_product_meta_box', 'crazytheme_product_meta_box_nonce');
    ?>
    <!-- Hidden flag ensuring checkbox submission is always detectable -->
    <input type="hidden" name="crazytheme_product_sidebar_present" value="1" />

    <div style="background:#f0fdf4; border:1.5px solid #86efac; border-radius:8px; padding:12px; margin-bottom:14px;">
        <label for="sidebar_product_enable" style="font-weight:700; color:#065f46; display:flex; align-items:center; gap:8px; cursor:pointer; font-size:13px;">
            <input type="checkbox" name="sidebar_product_enable" id="sidebar_product_enable" value="yes" <?php checked($is_enabled, true); ?> style="margin:0;" />
            <span>Show Sidebar Product Card</span>
        </label>
        <p style="margin:6px 0 0 24px; font-size:11.5px; color:#166534; line-height:1.4;">
            Uncheck this to hide the product summary card from the sidebar for this post.
        </p>
    </div>

    <div id="sidebar_product_fields_wrap" style="<?php echo ! $is_enabled ? 'opacity:0.4; pointer-events:none;' : ''; ?> transition: opacity 0.2s ease;">
        <p><label for="sidebar_product_name">Product Name</label><br>
        <input type="text" name="sidebar_product_name" id="sidebar_product_name" value="<?php echo esc_attr($name); ?>" style="width:100%;" /></p>
        
        <p><label for="sidebar_product_image">Product Image</label><br>
        <div style="display:flex; gap:10px;">
            <input type="text" name="sidebar_product_image" id="sidebar_product_image" value="<?php echo esc_attr($image); ?>" style="flex:1;" />
            <button type="button" class="button" id="sidebar_product_image_button">Upload / Choose Image</button>
        </div>
        <div id="sidebar_product_image_preview" style="margin-top:10px;">
            <?php if($image): ?><img src="<?php echo esc_url($image); ?>" style="max-width:100%; height:auto;" /><?php endif; ?>
        </div>
        </p>
        
        <p><label for="sidebar_product_rating">Rating (1-5)</label><br>
        <input type="number" step="0.1" name="sidebar_product_rating" id="sidebar_product_rating" value="<?php echo esc_attr($rating); ?>" style="width:100%;" /></p>
        
        <p><label for="sidebar_product_desc">Description</label><br>
        <textarea name="sidebar_product_desc" id="sidebar_product_desc" style="width:100%;" rows="3"><?php echo esc_textarea($desc); ?></textarea></p>
        
        <p><label for="sidebar_product_readmore">Read More URL</label><br>
        <input type="text" name="sidebar_product_readmore" id="sidebar_product_readmore" value="<?php echo esc_attr($readmore); ?>" style="width:100%;" /></p>
        
        <p><label for="sidebar_product_orig_price">Original Price ($)</label><br>
        <input type="number" step="0.01" name="sidebar_product_orig_price" id="sidebar_product_orig_price" value="<?php echo esc_attr($orig_price); ?>" style="width:100%;" /></p>
        
        <p><label for="sidebar_product_disc_price">Discounted Price ($)</label><br>
        <input type="number" step="0.01" name="sidebar_product_disc_price" id="sidebar_product_disc_price" value="<?php echo esc_attr($disc_price); ?>" style="width:100%;" /></p>
        
        <p><label for="sidebar_product_buy_url">Buy Now URL</label><br>
        <input type="text" name="sidebar_product_buy_url" id="sidebar_product_buy_url" value="<?php echo esc_attr($buy_url); ?>" style="width:100%;" /></p>

        <?php $post_clicks = get_post_meta($post->ID, '_post_clicks', true); ?>
        <p><label for="sidebar_product_clicks">Click Count (Used for "Top Clicks" sorting)</label><br>
        <input type="number" name="sidebar_product_clicks" id="sidebar_product_clicks" value="<?php echo esc_attr($post_clicks); ?>" placeholder="0" style="width:100%;" /></p>
    </div>

    <script>
    jQuery(document).ready(function($){
        var custom_uploader;
        $('#sidebar_product_image_button').click(function(e) {
            e.preventDefault();
            if (custom_uploader) {
                custom_uploader.open();
                return;
            }
            custom_uploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Image',
                button: { text: 'Choose Image' },
                multiple: false
            });
            custom_uploader.on('select', function() {
                var attachment = custom_uploader.state().get('selection').first().toJSON();
                $('#sidebar_product_image').val(attachment.url);
                $('#sidebar_product_image_preview').html('<img src="' + attachment.url + '" style="max-width:100%; height:auto;" />');
            });
            custom_uploader.open();
        });

        $('#sidebar_product_enable').on('change', function() {
            if ($(this).is(':checked')) {
                $('#sidebar_product_fields_wrap').css({ 'opacity': '1', 'pointer-events': 'auto' });
            } else {
                $('#sidebar_product_fields_wrap').css({ 'opacity': '0.4', 'pointer-events': 'none' });
            }
        });
    });
    </script>
    <?php
}

function crazytheme_save_product_meta_box($post_id) {
    if (!isset($_POST['crazytheme_product_meta_box_nonce'])) return;
    if (!wp_verify_nonce($_POST['crazytheme_product_meta_box_nonce'], 'crazytheme_product_meta_box')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Handle revisions: make sure we update the parent post ID
    if ($parent_id = wp_is_post_revision($post_id)) {
        $post_id = $parent_id;
    }

    // Save Enable/Disable toggle reliably
    if (isset($_POST['crazytheme_product_sidebar_present'])) {
        $enable_val = !empty($_POST['sidebar_product_enable']) ? 'yes' : 'no';
        update_post_meta($post_id, '_sidebar_product_enable', $enable_val);
        update_post_meta($post_id, '_show_sidebar_product', $enable_val);
    }

    $fields = [
        'name' => 'sanitize_text_field',
        'image' => 'sanitize_url',
        'rating' => 'floatval',
        'desc' => 'sanitize_textarea_field',
        'readmore' => 'sanitize_url',
        'orig_price' => 'floatval',
        'disc_price' => 'floatval',
        'buy_url' => 'sanitize_url'
    ];

    foreach ($fields as $field => $sanitize_func) {
        if (isset($_POST['sidebar_product_' . $field])) {
            update_post_meta($post_id, '_sidebar_product_' . $field, $sanitize_func($_POST['sidebar_product_' . $field]));
        }
    }

    if (isset($_POST['sidebar_product_clicks'])) {
        update_post_meta($post_id, '_post_clicks', absint($_POST['sidebar_product_clicks']));
    }
}
add_action('save_post', 'crazytheme_save_product_meta_box');

/**
 * Track post views / clicks for "Top Clicks"
 */
function crazytheme_track_post_clicks() {
    if ( is_single() ) {
        global $post;
        if ( $post && ! empty( $post->ID ) ) {
            $clicks = (int) get_post_meta( $post->ID, '_post_clicks', true );
            update_post_meta( $post->ID, '_post_clicks', $clicks + 1 );
        }
    }
}
add_action( 'wp_head', 'crazytheme_track_post_clicks' );

/**
 * Category Image Fields in WordPress Admin (Posts -> Categories)
 */
function crazytheme_add_category_image_field() {
    ?>
    <div class="form-field term-group">
        <label for="category_image"><?php _e( 'Category Image / Icon', 'crazytheme' ); ?></label>
        <input type="hidden" id="category_image" name="category_image" class="custom_media_url" value="">
        <div id="category_image_preview" style="margin: 10px 0;"></div>
        <p>
            <input type="button" class="button button-secondary crazytheme_media_button" id="crazytheme_media_button" value="<?php _e( 'Upload / Choose Image', 'crazytheme' ); ?>" />
            <input type="button" class="button button-secondary crazytheme_media_remove" id="crazytheme_media_remove" value="<?php _e( 'Remove Image', 'crazytheme' ); ?>" style="display:none;" />
        </p>
    </div>
    <?php
}
add_action( 'category_add_form_fields', 'crazytheme_add_category_image_field', 10, 2 );

function crazytheme_edit_category_image_field( $term ) {
    $image_url = get_term_meta( $term->term_id, 'category_image', true );
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row"><label for="category_image"><?php _e( 'Category Image / Icon', 'crazytheme' ); ?></label></th>
        <td>
            <input type="hidden" id="category_image" name="category_image" class="custom_media_url" value="<?php echo esc_attr( $image_url ); ?>">
            <div id="category_image_preview" style="margin: 10px 0;">
                <?php if ( ! empty( $image_url ) ) : ?>
                    <img src="<?php echo esc_url( $image_url ); ?>" style="max-width: 100px; max-height: 100px; border-radius: 50%; object-fit: cover; border: 2px solid #ccc;" />
                <?php endif; ?>
            </div>
            <p>
                <input type="button" class="button button-secondary crazytheme_media_button" id="crazytheme_media_button" value="<?php echo empty( $image_url ) ? __( 'Upload / Choose Image', 'crazytheme' ) : __( 'Change Image', 'crazytheme' ); ?>" />
                <input type="button" class="button button-secondary crazytheme_media_remove" id="crazytheme_media_remove" value="<?php _e( 'Remove Image', 'crazytheme' ); ?>" <?php echo empty( $image_url ) ? 'style="display:none;"' : ''; ?> />
            </p>
        </td>
    </tr>
    <?php
}
add_action( 'category_edit_form_fields', 'crazytheme_edit_category_image_field', 10, 2 );

function crazytheme_save_category_image( $term_id ) {
    if ( isset( $_POST['category_image'] ) ) {
        update_term_meta( $term_id, 'category_image', esc_url_raw( $_POST['category_image'] ) );
    }
}
add_action( 'created_category', 'crazytheme_save_category_image', 10, 2 );
add_action( 'edited_category', 'crazytheme_save_category_image', 10, 2 );

function crazytheme_admin_category_image_script( $hook ) {
    if ( 'edit-tags.php' === $hook || 'term.php' === $hook ) {
        wp_enqueue_media();
        ?>
        <script>
        jQuery(document).ready(function($){
            var file_frame;
            $(document).on('click', '.crazytheme_media_button', function(e){
                e.preventDefault();
                if (file_frame) {
                    file_frame.open();
                    return;
                }
                file_frame = wp.media.frames.file_frame = wp.media({
                    title: 'Select or Upload Category Image',
                    button: { text: 'Use this image' },
                    multiple: false
                });
                file_frame.on('select', function(){
                    var attachment = file_frame.state().get('selection').first().toJSON();
                    $('#category_image').val(attachment.url);
                    $('#category_image_preview').html('<img src="' + attachment.url + '" style="max-width: 100px; max-height: 100px; border-radius: 50%; object-fit: cover; border: 2px solid #ccc;" />');
                    $('.crazytheme_media_remove').show();
                    $('.crazytheme_media_button').val('Change Image');
                });
                file_frame.open();
            });
            $(document).on('click', '.crazytheme_media_remove', function(e){
                e.preventDefault();
                $('#category_image').val('');
                $('#category_image_preview').html('');
                $(this).hide();
                $('.crazytheme_media_button').val('Upload / Choose Image');
            });
        });
        </script>
        <?php
    }
}
add_action( 'admin_footer', 'crazytheme_admin_category_image_script' );

/**
 * Supplement Review Single Post Components & Helpers
 */
if ( ! defined( 'SR_VERSION' ) ) {
	define( 'SR_VERSION', CRAZYTHEME_VERSION );
}
if ( ! defined( 'SR_DIR' ) ) {
	define( 'SR_DIR', get_template_directory() );
}
if ( ! defined( 'SR_URI' ) ) {
	define( 'SR_URI', get_template_directory_uri() );
}

require_once SR_DIR . '/inc/svg-icons.php';
require_once SR_DIR . '/inc/template-functions.php';
require_once SR_DIR . '/inc/template-tags.php';
require_once SR_DIR . '/inc/author-meta.php';
require_once SR_DIR . '/inc/meta-boxes.php';
require_once SR_DIR . '/inc/toc.php';
require_once SR_DIR . '/inc/review-comments.php';
require_once SR_DIR . '/inc/schema.php';


