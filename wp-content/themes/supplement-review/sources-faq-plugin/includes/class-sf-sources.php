<?php
/**
 * Sources & Citations Section Renderer
 *
 * @package Sources_FAQ_Sections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SF_Sources_Handler {

	/**
	 * Default sample citations matching the screenshot.
	 */
	public static function get_default_sources() {
		return array(
			array(
				'citation' => 'Schwarz, P.E., Timpel, P., Harst, L., Greaves, C.J., Ali, M.K., Lambert, J., Weber, M.B., Almedawar, M.M. and Morawietz, H., 2018. Blood sugar regulation for cardiovascular health promotion and disease prevention: JACC health promotion series. Journal of the American College of Cardiology, 72(15), pp.1829-1844.',
				'url'      => 'https://www.jacc.org/doi/abs/10.1016/j.jacc.2018.07.081',
			),
			array(
				'citation' => 'Raj, S., Toporski, K., Garrity, A., Lee, J.M. and Newman, M.W., 2019, May. ” My blood sugar is higher on the weekends” Finding a Role for Context and Context-Awareness in the Design of Health Self-Management Technology. In Proceedings of the 2019 CHI Conference on Human Factors in Computing Systems (pp. 1-13).',
				'url'      => 'https://dl.acm.orgdoiabs/10.1145/3290605.3300349casa_token=GW2iRLArmjoAAAAA:43mecdUTeDr9i_ulgDBVXcj1EY3t8FSNQs7hlX6AJyNSR1FcGVj1LUyl8r5RGsUzFjqfW2KiO7NFpw',
			),
			array(
				'citation' => 'Chih, A.H., Jan, C.F., Shu, S.G. and Lue, B.H., 2010. Self-efficacy affects blood sugar control among adolescents with type I diabetes mellitus. Journal of the Formosan Medical Association, 109(7), pp.503-510.',
				'url'      => 'https://www.sciencedirect.com/science/article/pii/S0929664610600848',
			),
			array(
				'citation' => 'Numsang, P., Oumtanee, A., Kurat, S., Sananok, R., Kraichan, S. and Sarapoke, P., 2023. “Failure to control blood sugar” experiences of persons with type 2 diabetes mellitus. International Journal of Nursing Sciences, 10(4), pp.527-532.',
				'url'      => 'https://www.sciencedirect.com/science/article/pii/S2352013223000972',
			),
			array(
				'citation' => 'Navicharern, R., 2012. Diabetes self-management, fasting blood sugar and quality of life among type 2 diabetic patients with foot ulcers. Journal of the Medical Association of Thailand= Chotmaihet thangphaet, 95(2), pp.156-162.',
				'url'      => 'https://europepmc.org/articlemed/22435243utm_medium=email&utm_source=transaction&client=bot&client=bot&client=bot',
			),
		);
	}

	/**
	 * Shortcode and block render callback.
	 *
	 * @param array $attributes Shortcode or block attributes.
	 * @return string Rendered HTML.
	 */
	public static function render( $attributes = array() ) {
		// Enqueue frontend styles on demand
		wp_enqueue_style( 'sources-faq-style' );

		$heading = ! empty( $attributes['heading'] ) ? sanitize_text_field( $attributes['heading'] ) : __( 'Sources', 'sources-faq-sections' );

		// Parse items from attributes, post meta, or defaults
		$items = array();
		if ( ! empty( $attributes['items'] ) ) {
			if ( is_array( $attributes['items'] ) ) {
				$items = $attributes['items'];
			} elseif ( is_string( $attributes['items'] ) ) {
				$decoded = json_decode( $attributes['items'], true );
				if ( is_array( $decoded ) ) {
					$items = $decoded;
				}
			}
		}

		if ( empty( $items ) && is_singular() ) {
			$post_meta_sources = get_post_meta( get_the_ID(), '_sf_sources_items', true );
			if ( ! empty( $post_meta_sources ) ) {
				$decoded = is_array( $post_meta_sources ) ? $post_meta_sources : json_decode( $post_meta_sources, true );
				if ( is_array( $decoded ) ) {
					$items = $decoded;
				}
			}
		}

		if ( empty( $items ) ) {
			$items = self::get_default_sources();
		}

		ob_start();
		?>
		<section class="sf-sources-wrapper" aria-labelledby="sf-sources-heading">
			<div class="sf-section-header">
				<h2 id="sf-sources-heading" class="sf-heading"><?php echo esc_html( $heading ); ?></h2>
				<hr class="sf-divider" aria-hidden="true" />
			</div>

			<div class="sf-sources-card">
				<ul class="sf-sources-list">
					<?php foreach ( $items as $item ) :
						$citation = ! empty( $item['citation'] ) ? sanitize_text_field( $item['citation'] ) : '';
						$raw_url  = ! empty( $item['url'] ) ? trim( $item['url'] ) : '';

						// Security: Validate protocol (http/https only) to block javascript: and data: URLs
						$valid_url = '';
						if ( ! empty( $raw_url ) ) {
							$parsed = wp_parse_url( $raw_url );
							if ( isset( $parsed['scheme'] ) && in_array( strtolower( $parsed['scheme'] ), array( 'http', 'https' ), true ) ) {
								$valid_url = esc_url_raw( $raw_url );
							}
						}

						if ( empty( $citation ) && empty( $valid_url ) ) {
							continue;
						}
						?>
						<li class="sf-source-item">
							<span class="sf-source-text"><?php echo esc_html( $citation ); ?></span>
							<?php if ( ! empty( $valid_url ) ) : ?>
								<span class="sf-source-avail"> <?php esc_html_e( 'Available at', 'sources-faq-sections' ); ?> </span>
								<a
									href="<?php echo esc_url( $valid_url ); ?>"
									class="sf-source-url"
									target="_blank"
									rel="noopener noreferrer nofollow"
								><?php echo esc_html( $valid_url ); ?></a>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</section>
		<?php
		return ob_get_clean();
	}
}
