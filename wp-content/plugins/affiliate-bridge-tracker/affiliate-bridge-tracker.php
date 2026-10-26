<?php
/**
 * Plugin Name:       Affiliate Referrer Tracker
 * Plugin URI:        https://github.com/google-deepmind/antigravity
 * Description:       Tracks original page slugs for affiliate clicks, isolating sessions in sessionStorage and appending ref_page parameter to Domain 2 links.
 * Version:           1.0.0
 * Author:            Antigravity
 * Text Domain:       affiliate-referrer-tracker
 * License:           GPL-2.0+
 */

// Prevent direct file access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Class for Affiliate Referrer Tracker
 */
final class ART_Affiliate_Referrer_Tracker {

	/**
	 * Single instance of the class
	 *
	 * @var ART_Affiliate_Referrer_Tracker|null
	 */
	private static $instance = null;

	/**
	 * Settings page hook suffix
	 *
	 * @var string
	 */
	private $settings_page_hook = '';

	/**
	 * Option names constants
	 */
	const OPTION_DOMAIN_1 = 'art_domain_1';
	const OPTION_DOMAIN_2 = 'art_domain_2';

	/**
	 * Get instance of class
	 *
	 * @return ART_Affiliate_Referrer_Tracker
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor to register WP actions & filters
	 */
	private function __construct() {
		// Admin panel actions
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_plugin_action_links' ) );

		// Frontend script enqueue
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_script' ) );
	}

	/**
	 * Resolve current page/post slug
	 *
	 * @return string Page slug, or empty string if homepage or unresolved
	 */
	public function get_current_slug() {
		if ( is_front_page() || is_home() ) {
			return '';
		}

		$slug = '';
		if ( is_singular() ) {
			$queried_id = get_queried_object_id();
			if ( $queried_id ) {
				$slug = get_post_field( 'post_name', $queried_id );
			}
		}

		return sanitize_title( (string) $slug );
	}

	/**
	 * Sanitizer for domain settings inputs
	 *
	 * @param string $domain
	 * @return string Sanitized hostname
	 */
	public function sanitize_domain( $domain ) {
		$domain = sanitize_text_field( $domain );
		$domain = trim( $domain );

		if ( empty( $domain ) ) {
			return '';
		}

		// Remove protocol if entered (e.g. http:// or https://)
		$domain = preg_replace( '#^https?://#i', '', $domain );

		// Remove paths, ports, or query string (e.g. site.com/abc?xyz -> site.com)
		$domain = strtok( $domain, '/:?' );

		return strtolower( trim( $domain ) );
	}

	/**
	 * Enqueue front-end JavaScript and pass required config parameters
	 */
	public function enqueue_frontend_script() {
		if ( is_admin() ) {
			return;
		}

		wp_enqueue_script(
			'art-tracker',
			plugins_url( 'assets/js/tracker.js', __FILE__ ),
			array(),
			'1.0.0',
			true // Enqueue in footer
		);

		// Domain 1 config - Fallback to current site host if blank
		$domain1 = get_option( self::OPTION_DOMAIN_1, '' );
		if ( empty( $domain1 ) ) {
			$domain1 = wp_parse_url( home_url(), PHP_URL_HOST );
		}

		// Domain 2 config
		$domain2 = get_option( self::OPTION_DOMAIN_2, '' );

		// Localize frontend script with configuration data
		wp_localize_script(
			'art-tracker',
			'artData',
			array(
				'domain1'    => esc_js( $domain1 ),
				'domain2'    => esc_js( $domain2 ),
				'isHomepage' => (is_front_page() || is_home()),
				'slug'       => esc_js( $this->get_current_slug() ),
			)
		);
	}

	/**
	 * Add administration page hook
	 */
	public function add_admin_menu() {
		$this->settings_page_hook = add_options_page(
			__( 'Affiliate Referrer Tracker', 'affiliate-referrer-tracker' ),
			__( 'Affiliate Referrer Tracker', 'affiliate-referrer-tracker' ),
			'manage_options',
			'affiliate-referrer-tracker',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register settings fields
	 */
	public function register_settings() {
		register_setting(
			'art_settings_group',
			self::OPTION_DOMAIN_1,
			array(
				'type'              => 'string',
				'sanitize_callback' => array( $this, 'sanitize_domain' ),
				'default'           => '',
			)
		);

		register_setting(
			'art_settings_group',
			self::OPTION_DOMAIN_2,
			array(
				'type'              => 'string',
				'sanitize_callback' => array( $this, 'sanitize_domain' ),
				'default'           => '',
			)
		);
	}

	/**
	 * Enqueue admin stylesheet only on our specific settings page
	 *
	 * @param string $hook Current admin page hook
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( $hook !== $this->settings_page_hook ) {
			return;
		}

		wp_enqueue_style(
			'art-admin-style',
			plugins_url( 'assets/css/admin.css', __FILE__ ),
			array(),
			'1.0.0'
		);
	}

	/**
	 * Add Settings action link to the Plugins list table
	 *
	 * @param array $links
	 * @return array
	 */
	public function add_plugin_action_links( $links ) {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'options-general.php?page=affiliate-referrer-tracker' ),
			__( 'Settings', 'affiliate-referrer-tracker' )
		);
		array_unshift( $links, $settings_link );
		return $links;
	}

	/**
	 * Render settings page template
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$current_site_domain = wp_parse_url( home_url(), PHP_URL_HOST );
		$domain1             = get_option( self::OPTION_DOMAIN_1, '' );
		$domain2             = get_option( self::OPTION_DOMAIN_2, '' );

		$domain1_display     = ! empty( $domain1 ) ? $domain1 : $current_site_domain;
		$is_fallback_active  = empty( $domain1 );
		?>
		<div class="art-admin-wrap">
			<div class="art-admin-header">
				<span class="dashicons dashicons-admin-links"></span>
				<h1><?php esc_html_e( 'Affiliate Referrer Tracker Settings', 'affiliate-referrer-tracker' ); ?></h1>
			</div>

			<!-- Status Dashboard Card -->
			<div class="art-admin-card info-card">
				<h2>
					<span class="dashicons dashicons-dashboard" style="color: #10b981;"></span>
					<?php esc_html_e( 'System & Integration Status', 'affiliate-referrer-tracker' ); ?>
				</h2>
				<table class="art-status-table">
					<tbody>
						<tr>
							<td class="label-col"><strong><?php esc_html_e( 'Domain 1 (Blog Source Site A):', 'affiliate-referrer-tracker' ); ?></strong></td>
							<td>
								<code style="font-weight: 600; padding: 2px 6px; background: #e2e8f0; border-radius: 4px;"><?php echo esc_html( $domain1_display ); ?></code>
								<?php if ( $is_fallback_active ) : ?>
									<span class="art-badge art-badge-info" style="margin-left: 8px;"><?php esc_html_e( 'Site Fallback Active', 'affiliate-referrer-tracker' ); ?></span>
								<?php else : ?>
									<span class="art-badge art-badge-success" style="margin-left: 8px;"><?php esc_html_e( 'Custom Domain', 'affiliate-referrer-tracker' ); ?></span>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td class="label-col"><strong><?php esc_html_e( 'Domain 2 (Affiliate Redirection Site B):', 'affiliate-referrer-tracker' ); ?></strong></td>
							<td>
								<?php if ( ! empty( $domain2 ) ) : ?>
									<code style="font-weight: 600; padding: 2px 6px; background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 4px;"><?php echo esc_html( $domain2 ); ?></code>
									<span class="art-badge art-badge-success" style="margin-left: 8px;"><?php esc_html_e( 'Tracking Configured', 'affiliate-referrer-tracker' ); ?></span>
								<?php else : ?>
									<em style="color: #b91c1c; font-weight: 500;"><?php esc_html_e( 'Not configured yet. Set Domain 2 below.', 'affiliate-referrer-tracker' ); ?></em>
									<span class="art-badge art-badge-warning" style="margin-left: 8px;"><?php esc_html_e( 'Awaiting Config', 'affiliate-referrer-tracker' ); ?></span>
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td class="label-col"><strong><?php esc_html_e( 'Current Tracker State Type:', 'affiliate-referrer-tracker' ); ?></strong></td>
							<td>
								<span class="art-badge art-badge-info">sessionStorage (Tab-Isolated)</span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<!-- Configuration Settings Form -->
			<form method="post" action="options.php">
				<?php settings_fields( 'art_settings_group' ); ?>
				
				<div class="art-admin-card">
					<h2>
						<span class="dashicons dashicons-admin-generic" style="color: #3858e9;"></span>
						<?php esc_html_e( 'Configuration Parameters', 'affiliate-referrer-tracker' ); ?>
					</h2>

					<!-- Domain 1 Field -->
					<div class="art-admin-form-group">
						<label class="art-admin-label" for="<?php echo esc_attr( self::OPTION_DOMAIN_1 ); ?>">
							<?php esc_html_e( 'Domain 1: The Blog Source Domain (Site A)', 'affiliate-referrer-tracker' ); ?>
						</label>
						<input 
							type="text" 
							id="<?php echo esc_attr( self::OPTION_DOMAIN_1 ); ?>" 
							name="<?php echo esc_attr( self::OPTION_DOMAIN_1 ); ?>" 
							value="<?php echo esc_attr( $domain1 ); ?>" 
							class="art-admin-input" 
							placeholder="e.g. bestproductpicks.com" 
						/>
						<p class="art-admin-desc">
							<?php esc_html_e( 'The domain representing your blog (Site A). If left blank, it defaults to the current WordPress site host (', 'affiliate-referrer-tracker' ); ?>
							<code><?php echo esc_html( $current_site_domain ); ?></code>).
						</p>
					</div>

					<!-- Domain 2 Field -->
					<div class="art-admin-form-group" style="margin-top: 25px;">
						<label class="art-admin-label" for="<?php echo esc_attr( self::OPTION_DOMAIN_2 ); ?>">
							<?php esc_html_e( 'Domain 2: The Affiliate Redirection Domain (Site B)', 'affiliate-referrer-tracker' ); ?>
						</label>
						<input 
							type="text" 
							id="<?php echo esc_attr( self::OPTION_DOMAIN_2 ); ?>" 
							name="<?php echo esc_attr( self::OPTION_DOMAIN_2 ); ?>" 
							value="<?php echo esc_attr( $domain2 ); ?>" 
							class="art-admin-input" 
							placeholder="e.g. buy.bestproductpicks.com" 
							required
						/>
						<p class="art-admin-desc">
							<?php esc_html_e( 'The affiliate bridge/redirection domain (Site B). Outbound links pointing to this domain will automatically have the page slug appended (e.g. ', 'affiliate-referrer-tracker' ); ?>
							<code>?ref_page=SAVED_SLUG</code>).
						</p>
					</div>
				</div>

				<!-- Submit Button -->
				<div class="art-submit-section">
					<?php submit_button( __( 'Save Configuration', 'affiliate-referrer-tracker' ), 'primary', 'submit', false ); ?>
				</div>
			</form>
		</div>
		<?php
	}
}

/**
 * Initialize plugin instance
 */
function art_initialize_referrer_tracker() {
	ART_Affiliate_Referrer_Tracker::get_instance();
}
add_action( 'plugins_loaded', 'art_initialize_referrer_tracker' );
