<?php
defined( 'ABSPATH' ) || exit;
?>
<div <?php echo wp_kses_post( $wrapper_attributes ); ?>>
	<div class="<?php echo esc_attr( $classes ); ?>" <?php echo ( $ctaLayout === 'layoutOne' && ! empty( $inlineImageWrapperStyles ) ) ? esc_html( $inlineImageWrapperStyles ) : ''; ?>>
		<div class="content-wrapper">
			<div class="content-wrap">
				<<?php echo esc_attr( $ctaTitleTag ); ?> class="affliatex-cta-title">
					<?php echo wp_kses_post( $ctaTitle ); ?>
				</<?php echo esc_attr( $ctaTitleTag ); ?>>
				<?php if ( ! empty( $ctaContent ) ) : ?>
					<p class="affliatex-cta-content"><?php echo wp_kses_post( $ctaContent ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $edButtons ) : ?>
				<div class="button-wrapper cta-btn-<?php echo esc_attr( $ctaButtonAlignment ); ?>">
					<?php echo wp_kses_post( $content ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( $ctaLayout === 'layoutTwo' ) : ?>
			<div class="image-wrapper" <?php echo esc_html( $inlineImageWrapperStyles ); ?>></div>
		<?php endif; ?>
	</div>
</div>
