<?php
namespace AffiliateJourneyTracker;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings Class
 *
 * Handles admin settings registration, rendering, and input sanitization.
 */
class Settings {

	public const OPTION_GROUP = 'affiliate_jt_options_group';

	public function init(): void {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public function register_settings(): void {
		register_setting(
			self::OPTION_GROUP,
			'affiliate_jt_enable_forwarding',
			array(
				'type'              => 'boolean',
				'sanitize_callback' => 'absint',
				'default'           => 1,
			)
		);

		register_setting(
			self::OPTION_GROUP,
			'affiliate_jt_enable_logs',
			array(
				'type'              => 'boolean',
				'sanitize_callback' => 'absint',
				'default'           => 1,
			)
		);

		register_setting(
			self::OPTION_GROUP,
			'affiliate_jt_site_a_domains',
			array(
				'type'              => 'string',
				'sanitize_callback' => array( $this, 'sanitize_textarea_domains' ),
				'default'           => "thebuyersreviews.com\ndailyhealthsupplement.com\nsupplementmag.com",
			)
		);

		register_setting(
			self::OPTION_GROUP,
			'affiliate_jt_site_b_domains',
			array(
				'type'              => 'string',
				'sanitize_callback' => array( $this, 'sanitize_textarea_domains' ),
				'default'           => "bestproductpicks.com\ngetbestsupplements.com\nsiteb.com",
			)
		);

		register_setting(
			self::OPTION_GROUP,
			'affiliate_jt_param_keys',
			array(
				'type'              => 'string',
				'sanitize_callback' => array( $this, 'sanitize_textarea_domains' ),
				'default'           => "ref",
			)
		);

		register_setting(
			self::OPTION_GROUP,
			'affiliate_jt_button_identifiers',
			array(
				'type'              => 'string',
				'sanitize_callback' => array( $this, 'sanitize_textarea_domains' ),
				'default'           => '',
			)
		);

		register_setting(
			self::OPTION_GROUP,
			'affiliate_jt_cookie_expiry',
			array(
				'type'              => 'integer',
				'sanitize_callback' => array( $this, 'sanitize_cookie_expiry' ),
				'default'           => 24,
			)
		);

		add_settings_section(
			'affiliate_jt_main_section',
			__( 'Forwarding & Tracking Settings', 'affiliate-journey-tracker' ),
			array( $this, 'render_section_info' ),
			'affiliate-journey-settings'
		);

		add_settings_field(
			'affiliate_jt_enable_forwarding',
			__( 'Enable Forwarding', 'affiliate-journey-tracker' ),
			array( $this, 'render_field_enable_forwarding' ),
			'affiliate-journey-settings',
			'affiliate_jt_main_section'
		);

		add_settings_field(
			'affiliate_jt_enable_logs',
			__( 'Enable Click & Redirect Logging', 'affiliate-journey-tracker' ),
			array( $this, 'render_field_enable_logs' ),
			'affiliate-journey-settings',
			'affiliate_jt_main_section'
		);

		add_settings_field(
			'affiliate_jt_site_a_domains',
			__( 'Configured Site A Domains', 'affiliate-journey-tracker' ),
			array( $this, 'render_field_site_a_domains' ),
			'affiliate-journey-settings',
			'affiliate_jt_main_section'
		);

		add_settings_field(
			'affiliate_jt_site_b_domains',
			__( 'Target 2nd Page / Bridge Domains', 'affiliate-journey-tracker' ),
			array( $this, 'render_field_site_b_domains' ),
			'affiliate-journey-settings',
			'affiliate_jt_main_section'
		);

		add_settings_field(
			'affiliate_jt_param_keys',
			__( 'Tracking Parameters to Pass', 'affiliate-journey-tracker' ),
			array( $this, 'render_field_param_keys' ),
			'affiliate-journey-settings',
			'affiliate_jt_main_section'
		);

		add_settings_field(
			'affiliate_jt_button_identifiers',
			__( 'Allowed Button Slugs / Block Names', 'affiliate-journey-tracker' ),
			array( $this, 'render_field_button_identifiers' ),
			'affiliate-journey-settings',
			'affiliate_jt_main_section'
		);

		add_settings_field(
			'affiliate_jt_cookie_expiry',
			__( 'Journey Cookie Expiry (Hours)', 'affiliate-journey-tracker' ),
			array( $this, 'render_field_cookie_expiry' ),
			'affiliate-journey-settings',
			'affiliate_jt_main_section'
		);
	}

	public function sanitize_textarea_domains( $input ): string {
		if ( empty( $input ) ) {
			return '';
		}
		$lines = explode( "\n", str_replace( "\r", "", $input ) );
		$clean = array_values( array_filter( array_map( 'sanitize_text_field', $lines ) ) );
		return implode( "\n", $clean );
	}

	public function sanitize_cookie_expiry( $input ): int {
		$value = absint( $input );
		return ( $value > 0 ) ? $value : 24;
	}

	public function render_section_info(): void {
		echo '<p>' . esc_html__( 'Configure Site A origin tracking, 2nd page bridge domains, ref_page parameter forwarding, and cookie lifecycle.', 'affiliate-journey-tracker' ) . '</p>';
	}

	public function render_field_enable_forwarding(): void {
		$value = get_option( 'affiliate_jt_enable_forwarding', 1 );
		printf(
			'<label><input type="checkbox" id="affiliate_jt_enable_forwarding" name="affiliate_jt_enable_forwarding" value="1" %s /> %s</label>',
			checked( 1, $value, false ),
			esc_html__( 'Automatically append ref_page=<origin_slug> to Buy Now buttons and Pretty Links pointing to 2nd page / bridge domains.', 'affiliate-journey-tracker' )
		);
	}

	public function render_field_enable_logs(): void {
		$value = get_option( 'affiliate_jt_enable_logs', 1 );
		printf(
			'<label><input type="checkbox" id="affiliate_jt_enable_logs" name="affiliate_jt_enable_logs" value="1" %s /> %s</label>',
			checked( 1, $value, false ),
			esc_html__( 'Log button click events, redirects, and journey tracking details in real time.', 'affiliate-journey-tracker' )
		);
	}

	public function render_field_site_a_domains(): void {
		$value = get_option( 'affiliate_jt_site_a_domains', "thebuyersreviews.com\ndailyhealthsupplement.com\nsupplementmag.com" );
		printf(
			'<textarea id="affiliate_jt_site_a_domains" name="affiliate_jt_site_a_domains" rows="3" class="large-text code">%s</textarea><p class="description">%s</p>',
			esc_textarea( $value ),
			esc_html__( 'Configured Site A domains (one domain per line).', 'affiliate-journey-tracker' )
		);
	}

	public function render_field_site_b_domains(): void {
		$value = get_option( 'affiliate_jt_site_b_domains', "bestproductpicks.com\ngetbestsupplements.com\nsiteb.com" );
		printf(
			'<textarea id="affiliate_jt_site_b_domains" name="affiliate_jt_site_b_domains" rows="3" class="large-text code">%s</textarea><p class="description">%s</p>',
			esc_textarea( $value ),
			esc_html__( 'Target 2nd Page / Bridge domains (e.g. bestproductpicks.com). Links pointing to these domains will receive ref_page=<origin_slug>. Leave blank to target all external links.', 'affiliate-journey-tracker' )
		);
	}

	public function render_field_param_keys(): void {
		$value = get_option( 'affiliate_jt_param_keys', "ref_page\norigin\nsubid\nsubid1" );
		printf(
			'<textarea id="affiliate_jt_param_keys" name="affiliate_jt_param_keys" rows="3" class="large-text code">%s</textarea><p class="description">%s</p>',
			esc_textarea( $value ),
			esc_html__( 'Parameter names to append with the origin slug (one per line). Default: ref_page, origin, subid, subid1. "ref_page" is always included for the 2nd page bridge.', 'affiliate-journey-tracker' )
		);
	}

	public function render_field_button_identifiers(): void {
		$value = get_option( 'affiliate_jt_button_identifiers', '' );
		printf(
			'<textarea id="affiliate_jt_button_identifiers" name="affiliate_jt_button_identifiers" rows="3" class="large-text code" placeholder="/flexigen&#10;core/button&#10;buy-now-btn">%s</textarea><p class="description">%s</p>',
			esc_textarea( $value ),
			esc_html__( 'Optional: Specify target button path slugs, CSS class names, or Gutenberg block names. Leave blank to match all Pretty Links and Bridge links.', 'affiliate-journey-tracker' )
		);
	}

	public function render_field_cookie_expiry(): void {
		$value = get_option( 'affiliate_jt_cookie_expiry', 24 );
		printf(
			'<input type="number" id="affiliate_jt_cookie_expiry" name="affiliate_jt_cookie_expiry" value="%s" min="1" max="8760" class="small-text" /> <span class="description">%s</span>',
			esc_attr( $value ),
			esc_html__( 'Default: 24 hours. Duration to store the visitor origin slug in the 1st-party tracking cookie.', 'affiliate-journey-tracker' )
		);
	}

	public function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e( 'Affiliate Journey Tracker Settings', 'affiliate-journey-tracker' ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( self::OPTION_GROUP );
				do_settings_sections( 'affiliate-journey-settings' );
				submit_button( __( 'Save Settings', 'affiliate-journey-tracker' ) );
				?>
			</form>
		</div>
		<?php
	}
}
