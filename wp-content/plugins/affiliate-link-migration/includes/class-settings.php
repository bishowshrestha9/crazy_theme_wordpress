<?php
namespace AffiliateLinkMigration;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings Class
 *
 * Handles plugin configuration options registration, sanitization, and options UI rendering.
 */
class Settings {

	public const OPTION_GROUP = 'affiliate_lm_options_group';

	public function init(): void {
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	public function register_settings(): void {
		register_setting(
			self::OPTION_GROUP,
			'affiliate_lm_site_domains',
			array(
				'type'              => 'string',
				'sanitize_callback' => array( $this, 'sanitize_textarea_domains' ),
				'default'           => "thebuyersreviews.com\ndailyhealthsupplement.com\nsupplementmag.com",
			)
		);

		register_setting(
			self::OPTION_GROUP,
			'affiliate_lm_batch_size',
			array(
				'type'              => 'integer',
				'sanitize_callback' => array( $this, 'sanitize_batch_size' ),
				'default'           => 50,
			)
		);

		register_setting(
			self::OPTION_GROUP,
			'affiliate_lm_dry_run',
			array(
				'type'              => 'boolean',
				'sanitize_callback' => 'absint',
				'default'           => 0,
			)
		);

		register_setting(
			self::OPTION_GROUP,
			'affiliate_lm_enable_logs',
			array(
				'type'              => 'boolean',
				'sanitize_callback' => 'absint',
				'default'           => 1,
			)
		);

		add_settings_section(
			'affiliate_lm_main_section',
			__( 'Migration Engine Configurations', 'affiliate-link-migration' ),
			array( $this, 'render_section_info' ),
			'affiliate-link-migration-settings'
		);

		add_settings_field(
			'affiliate_lm_site_domains',
			__( 'Configured Site A Domains', 'affiliate-link-migration' ),
			array( $this, 'render_field_site_domains' ),
			'affiliate-link-migration-settings',
			'affiliate_lm_main_section'
		);

		add_settings_field(
			'affiliate_lm_batch_size',
			__( 'Batch Processing Size', 'affiliate-link-migration' ),
			array( $this, 'render_field_batch_size' ),
			'affiliate-link-migration-settings',
			'affiliate_lm_main_section'
		);

		add_settings_field(
			'affiliate_lm_dry_run',
			__( 'Dry Run Mode (Simulation Only)', 'affiliate-link-migration' ),
			array( $this, 'render_field_dry_run' ),
			'affiliate-link-migration-settings',
			'affiliate_lm_main_section'
		);

		add_settings_field(
			'affiliate_lm_enable_logs',
			__( 'Enable Modification Logging', 'affiliate-link-migration' ),
			array( $this, 'render_field_enable_logs' ),
			'affiliate-link-migration-settings',
			'affiliate_lm_main_section'
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

	public function sanitize_batch_size( $input ): int {
		$val = absint( $input );
		return ( $val >= 5 && $val <= 250 ) ? $val : 50;
	}

	public function render_section_info(): void {
		echo '<p>' . esc_html__( 'Configure target Site A domains, batch processing limits, and Dry Run simulation mode.', 'affiliate-link-migration' ) . '</p>';
	}

	public function render_field_site_domains(): void {
		$value = get_option( 'affiliate_lm_site_domains', "thebuyersreviews.com\ndailyhealthsupplement.com\nsupplementmag.com" );
		printf(
			'<textarea id="affiliate_lm_site_domains" name="affiliate_lm_site_domains" rows="4" class="large-text code">%s</textarea><p class="description">%s</p>',
			esc_textarea( $value ),
			esc_html__( 'Specify target Site A domains (one per line). Only hyperlinks pointing to these domains will be appended with origin=auto.', 'affiliate-link-migration' )
		);
	}

	public function render_field_batch_size(): void {
		$value = get_option( 'affiliate_lm_batch_size', 50 );
		printf(
			'<input type="number" id="affiliate_lm_batch_size" name="affiliate_lm_batch_size" value="%s" min="5" max="250" class="small-text" /> <span class="description">%s</span>',
			esc_attr( $value ),
			esc_html__( 'Number of published posts to process per AJAX iteration batch (Default: 50).', 'affiliate-link-migration' )
		);
	}

	public function render_field_dry_run(): void {
		$value = get_option( 'affiliate_lm_dry_run', 0 );
		printf(
			'<label><input type="checkbox" id="affiliate_lm_dry_run" name="affiliate_lm_dry_run" value="1" %s /> %s</label>',
			checked( 1, $value, false ),
			esc_html__( 'Enable Dry Run mode to simulate link modifications and record log output without saving post updates to the database.', 'affiliate-link-migration' )
		);
	}

	public function render_field_enable_logs(): void {
		$value = get_option( 'affiliate_lm_enable_logs', 1 );
		printf(
			'<label><input type="checkbox" id="affiliate_lm_enable_logs" name="affiliate_lm_enable_logs" value="1" %s /> %s</label>',
			checked( 1, $value, false ),
			esc_html__( 'Record detailed audit logs of modified links.', 'affiliate-link-migration' )
		);
	}

	public function render_settings_page(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap affiliate-lm-wrap">
			<h1><span class="dashicons dashicons-admin-settings"></span> <?php esc_html_e( 'Affiliate Migration Settings', 'affiliate-link-migration' ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( self::OPTION_GROUP );
				do_settings_sections( 'affiliate-link-migration-settings' );
				submit_button( __( 'Save Settings', 'affiliate-link-migration' ) );
				?>
			</form>
		</div>
		<?php
	}
}
