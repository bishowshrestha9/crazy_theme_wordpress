<?php
/**
 * Template Library Button for Elementor Editor
 *
 * @package ProsCons
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="affx-template-library-button-wrapper">
	<a class="affx-button-primary affx-template-library-button">
		<svg width="20" height="20" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
			<g clipPath="url(#clip0)">
				<path d="M31 19.4826L25 9.09011L24 7.36011L23 9.09011L17 19.4826L3.99998 42.0001H18L24 31.6076L30 42.0001H44L31 19.4826ZM24 27.6076L19.31 19.4826L22.36 14.2001L24 11.3601L28.5475 19.2326L28.69 19.4826L24 27.6076ZM22.2675 30.6076L16.845 40.0001H7.46498L18.155 21.4826L22.845 29.6076L22.2675 30.6076ZM32 40.0001H31.155L25.7325 30.6076L25.155 29.6076L29.845 21.4826L40.535 40.0001H32Z" fill="white"/>
			</g>
			<defs>
				<clipPath id="clip0">
					<rect width="40" height="34.64" fill="white" transform="translate(4 7.36011)"/>
				</clipPath>
			</defs>
		</svg>
		<?php esc_html_e( 'Template Library', 'prosandcons' ); ?>
	</a>
</div>
