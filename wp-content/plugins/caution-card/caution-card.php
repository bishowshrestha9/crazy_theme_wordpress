<?php
/**
 * Plugin Name: Caution Card
 * Description: Gutenberg Caution Card block for discontinued products with ACF integration.
 * Version: 1.5.0
 * Author: Bishow Shrestha
 * Text Domain: caution-card
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Caution_Card_Plugin {
	const OPTION_KEY = 'caution_card_settings';
	const PAGE_SLUG = 'caution-card';

	public static function init() {
		$instance = new self();
		$instance->hooks();
	}

	public static function activate() {
		$instance = new self();
		$instance->create_page_if_missing();
		$instance->ensure_default_options();
	}

	private function hooks() {
		add_action( 'admin_menu', array( $this, 'register_admin_page' ) );
		add_action( 'admin_post_caution_card_save', array( $this, 'save_settings' ) );
		add_shortcode( 'caution_card', array( $this, 'render_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'init', array( $this, 'register_block' ) );
	}

	private function ensure_default_options() {
		$defaults = array(
			'new_product_name' => '',
			'new_product_image' => '',
			'feature_1' => '',
			'feature_2' => '',
			'feature_3' => '',
			'rating_value' => '4.9',
			'review_count' => '12598',
			'rating_stars' => '5',
			'read_more_url' => '',
			'contact_url' => '',
			'show_alternatives_link' => '',
		);

		$current = get_option( self::OPTION_KEY );
		if ( ! is_array( $current ) ) {
			update_option( self::OPTION_KEY, $defaults );
		}
	}

	private function create_page_if_missing() {
		$page = get_page_by_path( self::PAGE_SLUG );
		if ( $page ) {
			return;
		}

		$page_id = wp_insert_post(
			array(
				'post_title' => 'Caution Card',
				'post_name' => self::PAGE_SLUG,
				'post_status' => 'publish',
				'post_type' => 'page',
				'post_content' => '[caution_card]',
			)
		);

		if ( $page_id && ! is_wp_error( $page_id ) ) {
			update_option( 'caution_card_page_id', (int) $page_id );
		}
	}

	public function register_admin_page() {
		add_options_page(
			__( 'Caution Card', 'caution-card' ),
			__( 'Caution Card', 'caution-card' ),
			'manage_options',
			'caution-card',
			array( $this, 'render_admin_page' )
		);
	}

	public function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$options = $this->get_settings();
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Caution Card Settings', 'caution-card' ); ?></h1>
			<p><?php esc_html_e( 'Fill out the fields below. The card will render on /caution-card.', 'caution-card' ); ?></p>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<input type="hidden" name="action" value="caution_card_save">
				<?php wp_nonce_field( 'caution_card_save', 'caution_card_nonce' ); ?>

				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="new_product_name"><?php esc_html_e( 'New Product Name', 'caution-card' ); ?></label></th>
						<td><input type="text" class="regular-text" id="new_product_name" name="new_product_name" value="<?php echo esc_attr( $options['new_product_name'] ); ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="new_product_image"><?php esc_html_e( 'New Product Image URL', 'caution-card' ); ?></label></th>
						<td><input type="url" class="regular-text" id="new_product_image" name="new_product_image" value="<?php echo esc_attr( $options['new_product_image'] ); ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="feature_1"><?php esc_html_e( 'Feature Bullet 1', 'caution-card' ); ?></label></th>
						<td><input type="text" class="regular-text" id="feature_1" name="feature_1" value="<?php echo esc_attr( $options['feature_1'] ); ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="feature_2"><?php esc_html_e( 'Feature Bullet 2', 'caution-card' ); ?></label></th>
						<td><input type="text" class="regular-text" id="feature_2" name="feature_2" value="<?php echo esc_attr( $options['feature_2'] ); ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="feature_3"><?php esc_html_e( 'Feature Bullet 3', 'caution-card' ); ?></label></th>
						<td><input type="text" class="regular-text" id="feature_3" name="feature_3" value="<?php echo esc_attr( $options['feature_3'] ); ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="rating_value"><?php esc_html_e( 'Rating Value', 'caution-card' ); ?></label></th>
						<td><input type="text" class="regular-text" id="rating_value" name="rating_value" value="<?php echo esc_attr( $options['rating_value'] ); ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="review_count"><?php esc_html_e( 'Review Count', 'caution-card' ); ?></label></th>
						<td><input type="number" class="regular-text" id="review_count" name="review_count" value="<?php echo esc_attr( $options['review_count'] ); ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="rating_stars"><?php esc_html_e( 'Rating Stars (1-5)', 'caution-card' ); ?></label></th>
						<td><input type="number" min="1" max="5" class="regular-text" id="rating_stars" name="rating_stars" value="<?php echo esc_attr( $options['rating_stars'] ); ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="read_more_url"><?php esc_html_e( 'Read More URL', 'caution-card' ); ?></label></th>
						<td><input type="url" class="regular-text" id="read_more_url" name="read_more_url" value="<?php echo esc_attr( $options['read_more_url'] ); ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="show_alternatives_link"><?php esc_html_e( 'Look Other Alternative URL', 'caution-card' ); ?></label></th>
						<td><input type="url" class="regular-text" id="show_alternatives_link" name="show_alternatives_link" value="<?php echo esc_attr( $options['show_alternatives_link'] ); ?>"></td>
					</tr>
					<tr>
						<th scope="row"><label for="contact_url"><?php esc_html_e( 'Contact URL', 'caution-card' ); ?></label></th>
						<td><input type="url" class="regular-text" id="contact_url" name="contact_url" value="<?php echo esc_attr( $options['contact_url'] ); ?>"></td>
					</tr>
				</table>

				<p class="submit">
					<button type="submit" class="button button-primary"><?php esc_html_e( 'Save Settings', 'caution-card' ); ?></button>
				</p>
			</form>
		</div>
		<?php
	}

	public function save_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'caution-card' ), 403 );
		}

		check_admin_referer( 'caution_card_save', 'caution_card_nonce' );

		$settings = array(
			'new_product_name' => sanitize_text_field( wp_unslash( $_POST['new_product_name'] ?? '' ) ),
			'new_product_image' => esc_url_raw( wp_unslash( $_POST['new_product_image'] ?? '' ) ),
			'feature_1' => sanitize_text_field( wp_unslash( $_POST['feature_1'] ?? '' ) ),
			'feature_2' => sanitize_text_field( wp_unslash( $_POST['feature_2'] ?? '' ) ),
			'feature_3' => sanitize_text_field( wp_unslash( $_POST['feature_3'] ?? '' ) ),
			'rating_value' => sanitize_text_field( wp_unslash( $_POST['rating_value'] ?? '' ) ),
			'review_count' => sanitize_text_field( wp_unslash( $_POST['review_count'] ?? '' ) ),
			'rating_stars' => sanitize_text_field( wp_unslash( $_POST['rating_stars'] ?? '' ) ),
			'read_more_url' => esc_url_raw( wp_unslash( $_POST['read_more_url'] ?? '' ) ),
			'contact_url' => esc_url_raw( wp_unslash( $_POST['contact_url'] ?? '' ) ),
			'show_alternatives_link' => esc_url_raw( wp_unslash( $_POST['show_alternatives_link'] ?? '' ) ),
		);

		update_option( self::OPTION_KEY, $settings );

		wp_redirect( add_query_arg( array( 'page' => 'caution-card', 'updated' => 'true' ), admin_url( 'options-general.php' ) ) );
		exit;
	}

	private function get_settings() {
		$options = get_option( self::OPTION_KEY, array() );
		return wp_parse_args(
			$options,
			array(
				'new_product_name' => '',
				'new_product_image' => '',
				'feature_1' => '',
				'feature_2' => '',
				'feature_3' => '',
				'rating_value' => '4.9',
				'review_count' => '12598',
				'rating_stars' => '5',
				'read_more_url' => '',
				'contact_url' => '',
				'show_alternatives_link' => '',
			)
		);
	}

	public function enqueue_assets() {
		if ( ! is_singular() ) {
			return;
		}

		global $post;
		if ( ! $post || ! has_shortcode( $post->post_content, 'caution_card' ) ) {
			if ( ! has_block( 'caution-card/block', $post ) ) {
				return;
			}
		}

		wp_enqueue_style(
			'caution-card-style',
			plugin_dir_url( __FILE__ ) . 'assets/caution-card.css',
			array(),
			'1.0.0'
		);

		wp_enqueue_script(
			'caution-card-script',
			plugin_dir_url( __FILE__ ) . 'assets/caution-card.js',
			array(),
			'1.0.0',
			true
		);
	}

	public function register_block() {
		wp_register_style(
			'caution-card-style',
			plugin_dir_url( __FILE__ ) . 'assets/caution-card.css',
			array(),
			'1.0.0'
		);

		wp_register_script(
			'caution-card-block',
			plugin_dir_url( __FILE__ ) . 'blocks/caution-card/editor.js',
			array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components' ),
			'1.0.0',
			true
		);

		register_block_type(
			plugin_dir_path( __FILE__ ) . 'blocks/caution-card',
			array(
				'render_callback' => array( $this, 'render_block' ),
			)
		);
	}

	public function render_shortcode() {
		return $this->render_card_markup( array(), true );
	}

	public function render_block( $attributes ) {
		return $this->render_card_markup( (array) $attributes, false );
	}

	private function render_card_markup( $attributes, $use_settings_fallback ) {
		$settings = $this->get_settings();

		$acf_product_name = '';
		if ( function_exists( 'get_field' ) ) {
			$acf_product_name = get_field( 'product_name' );
		}

		$old_product_name = $acf_product_name ? $acf_product_name : __( 'this product', 'caution-card' );

		$new_product_name = $this->pick_value( $attributes, 'newProductName', $use_settings_fallback ? $settings['new_product_name'] : '' );
		$new_product_name = $new_product_name ? $new_product_name : __( 'New Product', 'caution-card' );

		$new_product_image = $this->pick_value( $attributes, 'newProductImageUrl', '' );
		$new_product_image_id = $this->pick_value( $attributes, 'newProductImageId', 0 );
		if ( ! $new_product_image && $new_product_image_id ) {
			$new_product_image = wp_get_attachment_url( (int) $new_product_image_id );
		}
		if ( ! $new_product_image ) {
			$new_product_image = $this->pick_value( $attributes, 'newProductImage', $use_settings_fallback ? $settings['new_product_image'] : '' );
		}
		$feature_1 = $this->pick_value( $attributes, 'feature1', $use_settings_fallback ? $settings['feature_1'] : '' );
		$feature_2 = $this->pick_value( $attributes, 'feature2', $use_settings_fallback ? $settings['feature_2'] : '' );
		$feature_3 = $this->pick_value( $attributes, 'feature3', $use_settings_fallback ? $settings['feature_3'] : '' );

		$feature_1 = $feature_1 ? $feature_1 : __( 'Designed for harder erection, higher stamina, more libido', 'caution-card' );
		$feature_2 = $feature_2 ? $feature_2 : __( 'All Natural Ingredients with No Side Effects', 'caution-card' );
		$feature_3 = $feature_3 ? $feature_3 : __( '98% effective and results within 4 Weeks', 'caution-card' );

		$rating_value = $this->pick_value( $attributes, 'ratingValue', $use_settings_fallback ? $settings['rating_value'] : '' );
		$review_count = $this->pick_value( $attributes, 'reviewCount', $use_settings_fallback ? $settings['review_count'] : '' );
		$rating_value = $rating_value ? $rating_value : '4.9';
		$review_count = $review_count ? $review_count : '12598';

		$rating_value_float = (float) $rating_value;
		$rating_value_float = max( 0, min( 5, $rating_value_float ) );
		$rating_stars = (int) round( $rating_value_float );
		$rating_stars = max( 0, min( 5, $rating_stars ) );

		$read_more_url = $this->pick_value( $attributes, 'readMoreUrl', $use_settings_fallback ? $settings['read_more_url'] : '' );
		$contact_url = $this->pick_value( $attributes, 'contactUrl', $use_settings_fallback ? $settings['contact_url'] : '' );
		$alternatives_url = $this->pick_value( $attributes, 'alternativesUrl', $use_settings_fallback ? $settings['show_alternatives_link'] : '' );
		$fda_gmp_text = 'FDA Approved | GMP Certified';
		$trigger_selector = $this->pick_value( $attributes, 'triggerSelector', '.pcpx-cta-button' );
		$container_selector = $this->pick_value( $attributes, 'containerSelector', '.pcpx-product-card-outer' );

		$assets_base_url = trailingslashit( plugin_dir_url( __FILE__ ) ) . 'assets/icons/';
		$caution_icon_url = $assets_base_url . 'caution-stamp.svg';
		$check_icon_url = $assets_base_url . 'check-stamp.svg';
		$cert_icon_url = $assets_base_url . 'cert-stamp.svg';

		$avatar_1_url = $this->pick_value( $attributes, 'reviewAvatar1Url', '' );
		$avatar_2_url = $this->pick_value( $attributes, 'reviewAvatar2Url', '' );
		$avatar_3_url = $this->pick_value( $attributes, 'reviewAvatar3Url', '' );
		$avatar_1_id = (int) $this->pick_value( $attributes, 'reviewAvatar1Id', 0 );
		$avatar_2_id = (int) $this->pick_value( $attributes, 'reviewAvatar2Id', 0 );
		$avatar_3_id = (int) $this->pick_value( $attributes, 'reviewAvatar3Id', 0 );

		if ( ! $avatar_1_url && $avatar_1_id ) {
			$avatar_1_url = wp_get_attachment_url( $avatar_1_id );
		}
		if ( ! $avatar_2_url && $avatar_2_id ) {
			$avatar_2_url = wp_get_attachment_url( $avatar_2_id );
		}
		if ( ! $avatar_3_url && $avatar_3_id ) {
			$avatar_3_url = wp_get_attachment_url( $avatar_3_id );
		}

		$avatar_1_url = $avatar_1_url ? $avatar_1_url : $assets_base_url . 'reviewer-1.svg';
		$avatar_2_url = $avatar_2_url ? $avatar_2_url : $assets_base_url . 'reviewer-2.svg';
		$avatar_3_url = $avatar_3_url ? $avatar_3_url : $assets_base_url . 'reviewer-3.svg';

		$stars_output = str_repeat( '★', $rating_stars ) . str_repeat( '☆', 5 - $rating_stars );
		$recommendation_text = sprintf(
			'We recommend <span class="caution-card__highlight">%s</span> as the safer, more reliable alternative to <span class="caution-card__highlight caution-card__highlight--danger">%s</span>.',
			esc_html( $new_product_name ),
			esc_html( $old_product_name )
		);

		ob_start();
		?>
		<div class="caution-card-inline" data-trigger-selector="<?php echo esc_attr( $trigger_selector ); ?>" data-container-selector="<?php echo esc_attr( $container_selector ); ?>">
			<div class="caution-card">
				<div class="caution-card__warning">
					<span class="caution-card__warning-icon" aria-hidden="true">
						<img src="<?php echo esc_url( $caution_icon_url ); ?>" alt="" loading="lazy" decoding="async">
					</span>
					<p class="caution-card__warning-text">
						<strong><?php esc_html_e( 'Caution:', 'caution-card' ); ?></strong>
						<?php echo esc_html( sprintf( ' Due to quality-control and counterfeit concerns, %s has been discontinued.', $old_product_name ) ); ?>
					</p>
				</div>
				<div class="caution-card__content">
					<div class="caution-card__body">
						<div class="caution-card__left">
							<p class="caution-card__recommend">
								<?php echo wp_kses_post( $recommendation_text ); ?>
							</p>

							<ul class="caution-card__features">
								<li><span class="caution-card__check" aria-hidden="true"><img src="<?php echo esc_url( $check_icon_url ); ?>" alt="" loading="lazy" decoding="async"></span><?php echo esc_html( $feature_1 ); ?></li>
								<li><span class="caution-card__check" aria-hidden="true"><img src="<?php echo esc_url( $check_icon_url ); ?>" alt="" loading="lazy" decoding="async"></span><?php echo esc_html( $feature_2 ); ?></li>
								<li><span class="caution-card__check" aria-hidden="true"><img src="<?php echo esc_url( $check_icon_url ); ?>" alt="" loading="lazy" decoding="async"></span><?php echo esc_html( $feature_3 ); ?></li>
							</ul>

							<div class="caution-card__actions">
								<?php if ( ! empty( $read_more_url ) ) : ?>
									<a class="caution-card__button" href="<?php echo esc_url( $read_more_url ); ?>" target="_blank" rel="noopener">
										<?php echo esc_html( sprintf( 'Read More about %s', $new_product_name ) ); ?>
										<span class="caution-card__button-arrow" aria-hidden="true">➜</span>
									</a>
								<?php endif; ?>
								<?php if ( ! empty( $alternatives_url ) ) : ?>
									<a class="caution-card__link" href="<?php echo esc_url( $alternatives_url ); ?>" target="_blank" rel="noopener">
										<?php esc_html_e( 'Look Other Alternative', 'caution-card' ); ?>
									</a>
								<?php endif; ?>
							</div>
						</div>

						<div class="caution-card__right">
							<div class="caution-card__image-wrap">
								<?php if ( ! empty( $new_product_image ) ) : ?>
									<img src="<?php echo esc_url( $new_product_image ); ?>" alt="<?php echo esc_attr( $new_product_name ); ?>">
								<?php endif; ?>
								<span class="caution-card__badge" aria-hidden="true"></span>
							</div>

							<div class="caution-card__right-actions">
								<div class="caution-card__rating">
									<div class="caution-card__avatars" aria-hidden="true">
										<span class="caution-card__avatar"><img src="<?php echo esc_url( $avatar_1_url ); ?>" alt="" loading="lazy" decoding="async"></span>
										<span class="caution-card__avatar"><img src="<?php echo esc_url( $avatar_2_url ); ?>" alt="" loading="lazy" decoding="async"></span>
										<span class="caution-card__avatar"><img src="<?php echo esc_url( $avatar_3_url ); ?>" alt="" loading="lazy" decoding="async"></span>
									</div>
									<div class="caution-card__rating-info">
										<div class="caution-card__stars" aria-label="<?php echo esc_attr( $rating_value ); ?> rating">
											<?php echo esc_html( $stars_output ); ?>
											<span class="caution-card__rating-value"><?php echo esc_html( sprintf( '%s/5', $rating_value ) ); ?></span>
										</div>
										<span class="caution-card__rating-text"><?php echo esc_html( sprintf( '%s+ user reviews', number_format_i18n( (int) $review_count ) ) ); ?></span>
									</div>
								</div>

								<div class="caution-card__certs">
									<span class="caution-card__cert"><span class="caution-card__cert-icon" aria-hidden="true"><img src="<?php echo esc_url( $cert_icon_url ); ?>" alt="" loading="lazy" decoding="async"></span><?php esc_html_e( 'FDA Approved', 'caution-card' ); ?></span>
									<span class="caution-card__cert"><span class="caution-card__cert-icon" aria-hidden="true"><img src="<?php echo esc_url( $cert_icon_url ); ?>" alt="" loading="lazy" decoding="async"></span><?php esc_html_e( 'GMP Certified', 'caution-card' ); ?></span>
								</div>
							</div>
						</div>
					</div>

					<div class="caution-card__footer">
						<p class="caution-card__note">*Our recommendation is based on formula quality, consistency, and availability.</p>

						<?php if ( ! empty( $contact_url ) ) : ?>
							<p class="caution-card__contact">
								<?php echo esc_html( sprintf( 'Owner of %s', $new_product_name ) ); ?>
								<a href="<?php echo esc_url( $contact_url ); ?>" target="_blank" rel="noopener">
									<?php esc_html_e( 'Contact Us', 'caution-card' ); ?>
								</a>
							</p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	private function pick_value( $attributes, $key, $fallback ) {
		if ( isset( $attributes[ $key ] ) && '' !== $attributes[ $key ] && null !== $attributes[ $key ] ) {
			return $attributes[ $key ];
		}

		return $fallback;
	}
}

register_activation_hook( __FILE__, array( 'Caution_Card_Plugin', 'activate' ) );
add_action( 'plugins_loaded', array( 'Caution_Card_Plugin', 'init' ) );
