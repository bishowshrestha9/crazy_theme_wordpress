<?php
defined( 'ABSPATH' ) || exit;
?>
<div id="affiliatex-pros-cons-style-<?php echo esc_attr( $block_id ); ?>" class="affx-pros-cons-wrapper">
	<div class="affx-pros-cons-inner-wrapper <?php echo esc_attr( $layoutStyle ); ?>">
		<div class="affx-pros-inner">
			<div class="pros-icon-title-wrap">
				<div class="affiliatex-block-pros">
					<?php if ( $prosIconStatus ) : ?>
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.58 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/></svg>
					<?php endif; ?>
					<p class="affiliatex-title"><?php echo esc_html( $prosTitle ); ?></p>
				</div>
			</div>
			<div class="affiliatex-pros">
				<?php echo wp_kses_post( $prosList ); ?>
			</div>
		</div>

		<div class="affx-cons-inner">
			<div class="cons-icon-title-wrap">
				<div class="affiliatex-block-cons">
					<?php if ( $consIconStatus ) : ?>
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z"/></svg>
					<?php endif; ?>
					<p class="affiliatex-title"><?php echo esc_html( $consTitle ); ?></p>
				</div>
			</div>
			<div class="affiliatex-cons">
				<?php echo wp_kses_post( $consList ); ?>
			</div>
		</div>
	</div>
</div>
