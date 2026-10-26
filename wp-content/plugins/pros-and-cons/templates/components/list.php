<?php
defined( 'ABSPATH' ) || exit;
?>
<<?php echo esc_html( $listTag ); ?> class="<?php echo esc_attr( implode( ' ', $wrapperClasses ) ); ?>">
<?php if ( ! empty( $listItems ) && is_array( $listItems ) ) : ?>
	<?php foreach ( $listItems as $item ) : ?>
		<?php $content = affx_extract_child_items( $item ); ?>
		<li>
			<span><?php echo wp_kses_post( $content ); ?></span>
		</li>
	<?php endforeach; ?>
<?php endif; ?>
</<?php echo esc_html( $listTag ); ?>>
