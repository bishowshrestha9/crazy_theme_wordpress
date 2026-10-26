<?php defined( 'ABSPATH' ) || exit; if ( $edverdictTotalScore ) : ?>
	<div class="affx-verdict-rating-number<?php echo esc_attr( $ratingClass ); ?> <?php echo esc_attr( $ratingAlignment === 'right' ? 'align-right' : 'align-left' ); ?>">
		<span class="num"><?php echo wp_kses_post( $verdictTotalScore ); ?></span>
		<div class="rich-content"><?php echo wp_kses_post( $ratingContent ); ?></div>
	</div>
<?php endif; ?>
