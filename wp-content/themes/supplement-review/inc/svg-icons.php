<?php
/**
 * SVG Icons Helper & Inline Sprite
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns HTML for an SVG icon referencing an inline symbol.
 *
 * @param string $icon Icon name.
 * @param array  $args Optional arguments (class, width, height, aria_hidden, title).
 * @return string HTML SVG element.
 */
function sr_get_svg( $icon, $args = array() ) {
	$defaults = array(
		'class'       => 'sr-icon sr-icon-' . esc_attr( $icon ),
		'width'       => 20,
		'height'      => 20,
		'aria_hidden' => true,
		'title'       => '',
	);

	$args = wp_parse_args( $args, $defaults );

	$aria_attr = $args['aria_hidden'] ? 'aria-hidden="true" focusable="false"' : '';
	$title_xml = '';

	if ( ! empty( $args['title'] ) ) {
		$aria_attr = 'role="img" aria-label="' . esc_attr( $args['title'] ) . '"';
		$title_xml = '<title>' . esc_html( $args['title'] ) . '</title>';
	}

	return sprintf(
		'<svg class="%s" width="%d" height="%d" %s>%s<use href="#sr-icon-%s" xlink:href="#sr-icon-%s"></use></svg>',
		esc_attr( $args['class'] ),
		absint( $args['width'] ),
		absint( $args['height'] ),
		$aria_attr,
		$title_xml,
		esc_attr( $icon ),
		esc_attr( $icon )
	);
}

/**
 * Echoes the SVG icon HTML.
 *
 * @param string $icon Icon name.
 * @param array  $args Optional arguments.
 */
function sr_svg( $icon, $args = array() ) {
	echo sr_get_svg( $icon, $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints the SVG Sprite definitions at the bottom of the page in wp_footer.
 */
function sr_print_svg_sprite() {
	?>
	<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
		<defs>
			<!-- Search Icon -->
			<symbol id="sr-icon-search" viewBox="0 0 24 24">
				<path fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
			</symbol>

			<!-- Moon (Dark mode) -->
			<symbol id="sr-icon-moon" viewBox="0 0 24 24">
				<path fill="currentColor" d="M12.3 2a10 10 0 0 0-1.9 19.8 10 10 0 0 0 10.9-10.9A10.02 10.02 0 0 1 12.3 2z"/>
			</symbol>

			<!-- Sun (Light mode) -->
			<symbol id="sr-icon-sun" viewBox="0 0 24 24">
				<path fill="currentColor" d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58a.996.996 0 0 0-1.41 0 .996.996 0 0 0 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41L5.99 4.58zm12.37 12.37a.996.996 0 0 0-1.41 0 .996.996 0 0 0 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41l-1.06-1.06zm1.06-10.96a.996.996 0 0 0 0-1.41.996.996 0 0 0-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06zM7.05 18.36a.996.996 0 0 0 0-1.41.996.996 0 0 0-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06z"/>
			</symbol>

			<!-- Checkmark Circle -->
			<symbol id="sr-icon-check-circle" viewBox="0 0 24 24">
				<path fill="currentColor" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
			</symbol>

			<!-- Simple Checkmark -->
			<symbol id="sr-icon-check" viewBox="0 0 24 24">
				<path fill="currentColor" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/>
			</symbol>

			<!-- Cross / Close -->
			<symbol id="sr-icon-close" viewBox="0 0 24 24">
				<path fill="currentColor" d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
			</symbol>

			<!-- Star (Full) -->
			<symbol id="sr-icon-star" viewBox="0 0 24 24">
				<path fill="currentColor" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27z"/>
			</symbol>

			<!-- Star (Half) -->
			<symbol id="sr-icon-star-half" viewBox="0 0 24 24">
				<path fill="currentColor" d="M22 9.24l-7.19-.62L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27 18.18 21l-1.63-7.03L22 9.24zM12 15.4V6.1l1.71 4.04 4.38.38-3.32 2.88 1 4.28L12 15.4z"/>
			</symbol>

			<!-- Star (Empty) -->
			<symbol id="sr-icon-star-empty" viewBox="0 0 24 24">
				<path fill="currentColor" d="M22 9.24l-7.19-.62L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21 12 17.27 18.18 21l-1.63-7.03L22 9.24zM12 15.4l-3.76 2.27 1-4.28-3.32-2.88 4.38-.38L12 6.1l1.71 4.04 4.38.38-3.32 2.88 1 4.28L12 15.4z"/>
			</symbol>

			<!-- Share Icon -->
			<symbol id="sr-icon-share" viewBox="0 0 24 24">
				<path fill="currentColor" d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92-1.31-2.92-2.92-2.92z"/>
			</symbol>

			<!-- Cart Icon -->
			<symbol id="sr-icon-cart" viewBox="0 0 24 24">
				<path fill="currentColor" d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
			</symbol>

			<!-- Chevrons -->
			<symbol id="sr-icon-chevron-down" viewBox="0 0 24 24">
				<path fill="currentColor" d="M7.41 8.59L12 13.17l4.59-4.58L18 10l-6 6-6-6 1.41-1.41z"/>
			</symbol>

			<symbol id="sr-icon-chevron-up" viewBox="0 0 24 24">
				<path fill="currentColor" d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/>
			</symbol>

			<symbol id="sr-icon-chevron-right" viewBox="0 0 24 24">
				<path fill="currentColor" d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/>
			</symbol>

			<!-- Thumbs Up / Down -->
			<symbol id="sr-icon-thumbs-up" viewBox="0 0 24 24">
				<path fill="currentColor" d="M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z"/>
			</symbol>

			<symbol id="sr-icon-thumbs-down" viewBox="0 0 24 24">
				<path fill="currentColor" d="M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z"/>
			</symbol>

			<!-- Hamburger Menu -->
			<symbol id="sr-icon-menu" viewBox="0 0 24 24">
				<path fill="currentColor" d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
			</symbol>

			<!-- Arrow Up (Back to Top) -->
			<symbol id="sr-icon-arrow-up" viewBox="0 0 24 24">
				<path fill="currentColor" d="M4 12l1.41 1.41L11 7.83V20h2V7.83l5.58 5.59L20 12l-8-8-8 8z"/>
			</symbol>

			<!-- Arrow Right -->
			<symbol id="sr-icon-arrow-right" viewBox="0 0 24 24">
				<path fill="currentColor" d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
			</symbol>

			<!-- Shield Check -->
			<symbol id="sr-icon-shield-check" viewBox="0 0 24 24">
				<path fill="currentColor" d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
			</symbol>

			<!-- Award / Certification -->
			<symbol id="sr-icon-award" viewBox="0 0 24 24">
				<path fill="currentColor" d="M12 15c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm0-8c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zm-1 10.9V21l-3-2-3 2v-3.1c1.19.69 2.56 1.1 4 1.1s2.81-.41 4-1.1zm6 0c1.19-.69 2.56-1.1 4-1.1V21l-3-2-3 2v-3.1z"/>
			</symbol>

			<!-- Leaf (Ingredients) -->
			<symbol id="sr-icon-leaf" viewBox="0 0 24 24">
				<path fill="currentColor" d="M17 8C8 10 5.9 16.17 3.82 21.34L5.71 22l1-2.3A9.87 9.87 0 0 0 10 20c7 0 11-8 11-15 0-.55-.45-1-1-1-1.6 0-3 .4-4 1zm-4 4c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3z"/>
			</symbol>

			<!-- Phone -->
			<symbol id="sr-icon-phone" viewBox="0 0 24 24">
				<path fill="currentColor" d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
			</symbol>

			<!-- Email / Envelope -->
			<symbol id="sr-icon-envelope" viewBox="0 0 24 24">
				<path fill="currentColor" d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
			</symbol>

			<!-- Clock -->
			<symbol id="sr-icon-clock" viewBox="0 0 24 24">
				<path fill="currentColor" d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
			</symbol>
		</defs>
	</svg>
	<?php
}
add_action( 'wp_footer', 'sr_print_svg_sprite', 999 );
