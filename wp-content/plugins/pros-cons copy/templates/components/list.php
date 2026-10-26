<?php
defined( 'ABSPATH' ) || exit;
?>
	<<?php echo esc_html( $listTag ); ?> class="<?php echo esc_attr( implode( ' ', $wrapperClasses ) ); ?>">
	<?php if ( ! empty( $listItems ) && is_array( $listItems ) ) : ?>
		<?php foreach ( $listItems as $item ) : ?>
			<?php if ( isset( $item['props'] ) && is_array( $item ) ) : ?>
				<?php $content = affx_extract_child_items( $item ); ?>
				<li>
					<?php if ( $listType === 'unordered' && $unorderedType === 'icon' && $iconName ) : ?>
						<i class="<?php echo esc_attr( $iconName ); ?>"></i>
					<?php endif; ?>
					<span><?php echo wp_kses_post( $content ); ?></span>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php endif; ?>
	</<?php echo esc_html( $listTag ); ?>>
