<?php
/**
 * Supplement Review Theme Customizer Settings
 *
 * @package Supplement_Review
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register customizer options and controls.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function sr_customize_register( $wp_customize ) {

	// Enable selective refresh for widgets
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

	/* -------------------------------------------------------------------------
	 * SECTION: Announcement Bar
	 * ---------------------------------------------------------------------- */
	$wp_customize->add_section(
		'sr_announcement_section',
		array(
			'title'       => __( 'Top Announcement Bar', 'supplement-review' ),
			'priority'    => 25,
			'description' => __( 'Configure the announcement banner displayed at the very top of the site.', 'supplement-review' ),
		)
	);

	// On / Off Switch
	$wp_customize->add_setting(
		'sr_announcement_enabled',
		array(
			'default'           => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'sr_announcement_enabled',
		array(
			'label'    => __( 'Enable Announcement Bar', 'supplement-review' ),
			'section'  => 'sr_announcement_section',
			'type'     => 'checkbox',
		)
	);

	// Text
	$wp_customize->add_setting(
		'sr_announcement_text',
		array(
			'default'           => __( 'Special Christmas Offer - 50%', 'supplement-review' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'sr_announcement_text',
		array(
			'label'    => __( 'Announcement Text', 'supplement-review' ),
			'section'  => 'sr_announcement_section',
			'type'     => 'text',
		)
	);

	// Link
	$wp_customize->add_setting(
		'sr_announcement_link',
		array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'sr_announcement_link',
		array(
			'label'    => __( 'Announcement Link URL', 'supplement-review' ),
			'section'  => 'sr_announcement_section',
			'type'     => 'url',
		)
	);

	// Background Color
	$wp_customize->add_setting(
		'sr_announcement_bg',
		array(
			'default'           => '#6fa692',
			'sanitize_callback' => 'sanitize_hex_color',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'sr_announcement_bg',
			array(
				'label'    => __( 'Background Color', 'supplement-review' ),
				'section'  => 'sr_announcement_section',
			)
		)
	);

	/* -------------------------------------------------------------------------
	 * SECTION: Trust Badges URLs
	 * ---------------------------------------------------------------------- */
	$wp_customize->add_section(
		'sr_trust_badges_section',
		array(
			'title'       => __( 'Trust Badges Links', 'supplement-review' ),
			'priority'    => 26,
			'description' => __( 'Set page links for the Disclaimer, Fact Check, and Review Process trust badges.', 'supplement-review' ),
		)
	);

	$wp_customize->add_setting(
		'sr_badge_disclaimer_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'sr_badge_disclaimer_url',
		array(
			'label'    => __( 'Disclaimer Page URL', 'supplement-review' ),
			'section'  => 'sr_trust_badges_section',
			'type'     => 'url',
		)
	);

	$wp_customize->add_setting(
		'sr_badge_factcheck_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'sr_badge_factcheck_url',
		array(
			'label'    => __( 'Fact Check Page URL', 'supplement-review' ),
			'section'  => 'sr_trust_badges_section',
			'type'     => 'url',
		)
	);

	$wp_customize->add_setting(
		'sr_badge_process_url',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'sr_badge_process_url',
		array(
			'label'    => __( 'Review Process Page URL', 'supplement-review' ),
			'section'  => 'sr_trust_badges_section',
			'type'     => 'url',
		)
	);

	/* -------------------------------------------------------------------------
	 * SECTION: Pre-Footer CTA
	 * ---------------------------------------------------------------------- */
	$wp_customize->add_section(
		'sr_cta_section',
		array(
			'title'       => __( 'Pre-Footer Call to Action', 'supplement-review' ),
			'priority'    => 27,
			'description' => __( 'Configure the dark pre-footer CTA section.', 'supplement-review' ),
		)
	);

	$wp_customize->add_setting(
		'sr_cta_enabled',
		array(
			'default'           => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'sr_cta_enabled',
		array(
			'label'    => __( 'Show Pre-Footer CTA Section', 'supplement-review' ),
			'section'  => 'sr_cta_section',
			'type'     => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'sr_cta_heading',
		array(
			'default'           => __( "Know What You're Taking", 'supplement-review' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'sr_cta_heading',
		array(
			'label'    => __( 'CTA Heading', 'supplement-review' ),
			'section'  => 'sr_cta_section',
			'type'     => 'text',
		)
	);

	$wp_customize->add_setting(
		'sr_cta_description',
		array(
			'default'           => __( 'We dive deep into clinical studies, ingredient purity, and genuine user reports to help you choose supplements with complete confidence.', 'supplement-review' ),
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'sr_cta_description',
		array(
			'label'    => __( 'CTA Description', 'supplement-review' ),
			'section'  => 'sr_cta_section',
			'type'     => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'sr_cta_btn_text',
		array(
			'default'           => __( 'Get your - 50% off', 'supplement-review' ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'sr_cta_btn_text',
		array(
			'label'    => __( 'Button Text', 'supplement-review' ),
			'section'  => 'sr_cta_section',
			'type'     => 'text',
		)
	);

	$wp_customize->add_setting(
		'sr_cta_btn_link',
		array(
			'default'           => '#',
			'sanitize_callback' => 'esc_url_raw',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'sr_cta_btn_link',
		array(
			'label'    => __( 'Button Link URL', 'supplement-review' ),
			'section'  => 'sr_cta_section',
			'type'     => 'url',
		)
	);

	/* -------------------------------------------------------------------------
	 * SECTION: Footer & Compliance Settings
	 * ---------------------------------------------------------------------- */
	$wp_customize->add_section(
		'sr_footer_section',
		array(
			'title'       => __( 'Footer & Disclaimers', 'supplement-review' ),
			'priority'    => 28,
			'description' => __( 'Manage footer contact details and medical disclaimer text.', 'supplement-review' ),
		)
	);

	$wp_customize->add_setting(
		'sr_footer_phone',
		array(
			'default'           => '+1 (800) 555-0199',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'sr_footer_phone',
		array(
			'label'    => __( 'Phone Number', 'supplement-review' ),
			'section'  => 'sr_footer_section',
			'type'     => 'text',
		)
	);

	$wp_customize->add_setting(
		'sr_footer_whatsapp',
		array(
			'default'           => '+1 (800) 555-0199',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'sr_footer_whatsapp',
		array(
			'label'    => __( 'WhatsApp Contact', 'supplement-review' ),
			'section'  => 'sr_footer_section',
			'type'     => 'text',
		)
	);

	$wp_customize->add_setting(
		'sr_footer_hours',
		array(
			'default'           => 'Mon - Fri: 9:00 AM - 6:00 PM EST',
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'sr_footer_hours',
		array(
			'label'    => __( 'Support Hours', 'supplement-review' ),
			'section'  => 'sr_footer_section',
			'type'     => 'text',
		)
	);

	$wp_customize->add_setting(
		'sr_footer_email',
		array(
			'default'           => 'support@example.com',
			'sanitize_callback' => 'sanitize_email',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'sr_footer_email',
		array(
			'label'    => __( 'Support Email', 'supplement-review' ),
			'section'  => 'sr_footer_section',
			'type'     => 'email',
		)
	);

	$wp_customize->add_setting(
		'sr_affiliate_disclosure',
		array(
			'default'           => __( 'Affiliate Disclosure: When you purchase through links on our site, we may earn an affiliate commission at no extra charge to you. We independently research and test all reviewed products.', 'supplement-review' ),
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'sr_affiliate_disclosure',
		array(
			'label'    => __( 'Affiliate Disclosure Note', 'supplement-review' ),
			'section'  => 'sr_footer_section',
			'type'     => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'sr_medical_disclaimer',
		array(
			'default'           => __( 'Medical Disclaimer: Statements on this website have not been evaluated by the Food and Drug Administration. These products are not intended to diagnose, treat, cure, or prevent any disease. Always consult your physician before starting any diet or dietary supplement program.', 'supplement-review' ),
			'sanitize_callback' => 'sanitize_textarea_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'sr_medical_disclaimer',
		array(
			'label'    => __( 'Medical Disclaimer Text', 'supplement-review' ),
			'section'  => 'sr_footer_section',
			'type'     => 'textarea',
		)
	);

	$wp_customize->add_setting(
		'sr_footer_copyright',
		array(
			'default'           => sprintf( __( '© %s Supplement Review. All rights reserved.', 'supplement-review' ), date( 'Y' ) ),
			'sanitize_callback' => 'sanitize_text_field',
			'transport'         => 'postMessage',
		)
	);
	$wp_customize->add_control(
		'sr_footer_copyright',
		array(
			'label'    => __( 'Copyright Notice', 'supplement-review' ),
			'section'  => 'sr_footer_section',
			'type'     => 'text',
		)
	);
}
add_action( 'customize_register', 'sr_customize_register' );
