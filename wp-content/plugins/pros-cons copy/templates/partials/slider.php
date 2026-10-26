<?php defined( 'ABSPATH' ) || exit; ?>
<div class="affx-slider glide"
	role="region"
	aria-roledescription="carousel"
	aria-label="<?php esc_attr_e( 'Product images', 'prosandcons' ); ?>"
	data-glide-config="<?php echo esc_attr( wp_json_encode( $sliderConfig ) ); ?>"
	style="--affx-arrow-color: <?php echo esc_attr( $sliderConfig['arrowColor'] ); ?>; --affx-arrow-hover-color: <?php echo esc_attr( $sliderConfig['arrowHvrColor'] ); ?>;"
>
	<div class="glide__track" data-glide-el="track">
		<ul class="glide__slides">
			<?php foreach ( $sliderImages as $index => $slide ) : ?>
				<?php /* translators: 1: current slide number, 2: total number of slides */ ?>
			<li class="glide__slide" role="tabpanel" aria-roledescription="slide" aria-label="<?php echo esc_attr( sprintf( __( 'Slide %1$d of %2$d', 'prosandcons' ), $index + 1, count( $sliderImages ) ) ); ?>">
				<?php
				if ( ! empty( $slide['id'] ) && wp_attachment_is_image( $slide['id'] ) ) {
					echo wp_get_attachment_image( $slide['id'], 'full', false, array( 'style' => 'height: auto;' ) );
				} elseif ( ! empty( $slide['url'] ) ) {
					printf(
						'<img src="%s" alt="%s" style="height: auto;" />',
						esc_url( $slide['url'] ),
						esc_attr( $slide['alt'] )
					);
				}
				?>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php if ( ! empty( $sliderConfig['arrows'] ) ) : ?>
	<div class="glide__arrows" data-glide-el="controls">
		<button class="glide__arrow glide__arrow--left" data-glide-dir="&lt;" aria-label="<?php esc_attr_e( 'Previous slide', 'prosandcons' ); ?>"></button>
		<button class="glide__arrow glide__arrow--right" data-glide-dir="&gt;" aria-label="<?php esc_attr_e( 'Next slide', 'prosandcons' ); ?>"></button>
	</div>
	<?php endif; ?>
	<?php if ( ! empty( $sliderConfig['pagination'] ) ) : ?>
	<div class="glide__bullets" data-glide-el="controls[nav]">
		<?php foreach ( $sliderImages as $index => $slide ) : ?>
			<?php /* translators: %d: slide number */ ?>
		<button class="glide__bullet" data-glide-dir="=<?php echo absint( $index ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'prosandcons' ), $index + 1 ) ); ?>"></button>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
</div>
