<?php
/**
 * FAQ Accordion Section Renderer with JSON-LD Schema
 *
 * @package Sources_FAQ_Sections
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SF_FAQ_Handler {

	/**
	 * Default sample questions matching the screenshot.
	 */
	public static function get_default_faqs() {
		return array(
			array(
				'question' => 'What is GlucoReset drops?',
				'answer'   => 'Gluco Reset metabolic formula is a liquid Blood Sugar supplement designed to support healthy metabolism, glucose metabolism, balanced energy and everyday metabolic wellness.',
			),
			array(
				'question' => 'How much does Gluco Reset cost?',
				'answer'   => 'Gluco Reset is offered in several discounted packages starting at $12.99 per bottle for multi-bottle bundles, up to $17.99 for a single starter bottle, all backed by free domestic shipping.',
			),
			array(
				'question' => 'How should I take Gluco Reset glucose support supplement?',
				'answer'   => 'Take 1 full dropper (approximately 1 mL) under the tongue each morning before breakfast. Hold for 30 seconds before swallowing for optimal sublingual absorption.',
			),
			array(
				'question' => 'How long does GlucoReset glucose supplement take to use?',
				'answer'   => 'While many users notice steady morning energy within 7 to 14 days, clinical studies on active ingredients suggest peak metabolic benefits after 60 to 90 days of consistent daily use.',
			),
			array(
				'question' => 'Is there any Gluco Reset money-back guarantee?',
				'answer'   => 'Yes, all orders purchased through the official merchant link are protected by an unconditional 60-day 100% money-back guarantee with zero questions asked.',
			),
			array(
				'question' => 'Can children use Gluco Reset Metabolic support supplement?',
				'answer'   => 'No, Gluco Reset is specifically formulated for adults aged 18 and older. Pregnant or nursing mothers and individuals with known medical conditions should consult a physician.',
			),
		);
	}

	/**
	 * Render the FAQ section shortcode and block.
	 *
	 * @param array $attributes Shortcode or block attributes.
	 * @return string Rendered HTML.
	 */
	public static function render( $attributes = array() ) {
		// Enqueue frontend styles and script on demand
		wp_enqueue_style( 'sources-faq-style' );
		wp_enqueue_script( 'sources-faq-script' );

		$heading     = ! empty( $attributes['heading'] ) ? sanitize_text_field( $attributes['heading'] ) : __( 'How to Use Glucose Reset Metabolic Support?', 'sources-faq-sections' );
		$first_open  = isset( $attributes['firstOpen'] ) ? filter_var( $attributes['firstOpen'], FILTER_VALIDATE_BOOLEAN ) : true;
		if ( isset( $attributes['first_open'] ) ) {
			$first_open = filter_var( $attributes['first_open'], FILTER_VALIDATE_BOOLEAN );
		}

		$single_open = isset( $attributes['singleOpen'] ) ? filter_var( $attributes['singleOpen'], FILTER_VALIDATE_BOOLEAN ) : true;
		if ( isset( $attributes['single_open'] ) ) {
			$single_open = filter_var( $attributes['single_open'], FILTER_VALIDATE_BOOLEAN );
		}

		// Parse items
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
			$post_meta_faqs = get_post_meta( get_the_ID(), '_sf_faq_items', true );
			if ( ! empty( $post_meta_faqs ) ) {
				$decoded = is_array( $post_meta_faqs ) ? $post_meta_faqs : json_decode( $post_meta_faqs, true );
				if ( is_array( $decoded ) ) {
					$items = $decoded;
				}
			}
		}

		if ( empty( $items ) ) {
			$items = self::get_default_faqs();
		}

		// Allowed HTML tags for answers
		$allowed_html = array(
			'strong' => array(),
			'em'     => array(),
			'b'      => array(),
			'i'      => array(),
			'a'      => array(
				'href'   => array(),
				'title'  => array(),
				'target' => array(),
				'rel'    => array(),
			),
			'p'      => array(),
			'br'     => array(),
		);

		// Prepare JSON-LD Schema entities
		$schema_entities = array();
		$accordion_id = 'sf-faq-' . wp_rand( 1000, 9999 );

		ob_start();
		?>
		<section
			class="sf-faq-wrapper"
			id="<?php echo esc_attr( $accordion_id ); ?>"
			data-single-open="<?php echo $single_open ? 'true' : 'false'; ?>"
			aria-labelledby="<?php echo esc_attr( $accordion_id ); ?>-heading"
		>
			<div class="sf-section-header">
				<h2 id="<?php echo esc_attr( $accordion_id ); ?>-heading" class="sf-heading">
					<?php echo esc_html( $heading ); ?>
				</h2>
				<hr class="sf-divider" aria-hidden="true" />
			</div>

			<div class="sf-faq-list" role="region" aria-label="<?php esc_attr_e( 'Frequently Asked Questions', 'sources-faq-sections' ); ?>">
				<?php foreach ( $items as $idx => $item ) :
					$question = ! empty( $item['question'] ) ? sanitize_text_field( $item['question'] ) : '';
					$answer   = ! empty( $item['answer'] ) ? wp_kses( $item['answer'], $allowed_html ) : '';

					if ( empty( $question ) ) {
						continue;
					}

					$is_open = ( 0 === $idx && $first_open );
					$q_id = $accordion_id . '-q-' . $idx;
					$a_id = $accordion_id . '-a-' . $idx;

					// Collect schema
					$schema_entities[] = array(
						'@type'          => 'Question',
						'name'           => $question,
						'acceptedAnswer' => array(
							'@type' => 'Answer',
							'text'  => wp_strip_all_tags( $answer ),
						),
					);
					?>
					<div class="sf-faq-card <?php echo $is_open ? 'sf-open' : ''; ?>">
						<button
							type="button"
							class="sf-faq-button"
							id="<?php echo esc_attr( $q_id ); ?>"
							aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
							aria-controls="<?php echo esc_attr( $a_id ); ?>"
						>
							<span class="sf-faq-icon" aria-hidden="true"></span>
							<span class="sf-faq-question"><?php echo esc_html( $question ); ?></span>
						</button>

						<div
							class="sf-faq-answer-panel"
							id="<?php echo esc_attr( $a_id ); ?>"
							role="region"
							aria-labelledby="<?php echo esc_attr( $q_id ); ?>"
							aria-hidden="<?php echo $is_open ? 'false' : 'true'; ?>"
						>
							<div class="sf-faq-answer-inner">
								<?php
								// Auto-paragraph answer if needed
								echo wpautop( $answer ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<?php if ( ! empty( $schema_entities ) ) :
				$schema = array(
					'@context'   => 'https://schema.org',
					'@type'      => 'FAQPage',
					'mainEntity' => $schema_entities,
				);
				?>
				<!-- FAQPage JSON-LD Schema -->
				<script type="application/ld+json">
				<?php echo wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ); ?>
				</script>
			<?php endif; ?>
		</section>
		<?php
		return ob_get_clean();
	}
}
