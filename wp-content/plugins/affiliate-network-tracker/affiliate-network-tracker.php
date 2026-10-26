<?php
/**
 * Plugin Name: Affiliate Network Tracker
 * Plugin URI: https://example.com
 * Description: Track conversions across 12 affiliate networks with custom SubID parameter mapping. Auto-detects network patterns and replaces SubIDs with site name and page slug.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: affiliate-network-tracker
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
	
}

// Register activation hook
register_activation_hook( __FILE__, array( 'Affiliate_Network_Tracker', 'activate' ) );

/**
 * Main Plugin Class
 */
class Affiliate_Network_Tracker {

	/**
	 * Plugin version
	 */
	const VERSION = '1.0.0';

	/**
	 * Singleton instance
	 */
	private static $instance = null;

	/**
	 * Default network configurations
	 * Pattern matching: Checks if URL contains ANY of the patterns (comma-separated)
	 * Also checks for parameter combinations to identify networks
	 */
	private $default_networks = array(
		'buygoods' => array(
			'name'        => 'BuyGoods',
			'pattern'     => 'aff_id,subid',
			'slot1_param' => 'subid',   // Will REPLACE random value with site name
			'slot2_param' => 'subid2',  // Will REPLACE random value with page slug
			'enabled'     => true,
			'match_by'    => 'params',
		),
		'smashloud' => array(
			'name'        => 'SmashLoud',
			'pattern'     => 'sub1,sub2',
			'slot1_param' => 'sub1',
			'slot2_param' => 'sub2',
			'enabled'     => true,
			'match_by'    => 'params',
		),
		'smartadv' => array(
			'name'        => 'SmartAdv',
			'pattern'     => 'uid,source_id',
			'slot1_param' => 'source_id',
			'slot2_param' => 'sub1',
			'enabled'     => true,
			'match_by'    => 'params',
		),
		'clickhunts' => array(
			'name'        => 'ClickHunts',
			'pattern'     => 'ch8gs4fh.com',
			'slot1_param' => 'sub1',
			'slot2_param' => 'sub2',
			'enabled'     => true,
			'match_by'    => 'domain',
		),
		'mediascalers' => array(
			'name'        => 'MediaScalers',
			'pattern'     => 'uid,sub1',
			'slot1_param' => 'sub1',
			'slot2_param' => 'sub2',
			'enabled'     => true,
			'match_by'    => 'params',
		),
		'sellhealth' => array(
			'name'        => 'SellHealth',
			'pattern'     => 't1,t2',
			'slot1_param' => 't1',  // Will REPLACE random value with site name
			'slot2_param' => 't2',  // Will REPLACE random value with page slug
			'enabled'     => true,
			'match_by'    => 'params',
		),
		'clickbank' => array(
			'name'        => 'ClickBank',
			'pattern'     => 'clickbank.net',
			'slot1_param' => 'aff_sub1',
			'slot2_param' => 'aff_sub2',
			'enabled'     => true,
			'match_by'    => 'domain',
		),
		'terraleads' => array(
			'name'        => 'TerraLeads',
			'pattern'     => 'tl-track.com',
			'slot1_param' => 'subid',
			'slot2_param' => 'subid2',
			'enabled'     => true,
			'match_by'    => 'domain',
		),
		'maxweb' => array(
			'name'        => 'MaxWeb',
			'pattern'     => 'mwebtrackerhq.com',
			'slot1_param' => 'subid1',
			'slot2_param' => 'subid2',
			'enabled'     => true,
			'match_by'    => 'domain',
		),
		'clickadv' => array(
			'name'        => 'ClickAdv',
			'pattern'     => 'chffn8trk.com',
			'slot1_param' => 'sub1',
			'slot2_param' => 'sub2',
			'enabled'     => true,
			'match_by'    => 'domain',
		),
		'trafficlight' => array(
			'name'        => 'Traffic Light',
			'pattern'     => 'lnk,sub1',
			'slot1_param' => 'sub1',
			'slot2_param' => 'sub2',
			'enabled'     => true,
			'match_by'    => 'params',
		),
		'leadbit' => array(
			'name'        => 'LeadBit',
			'pattern'     => 'keonv.com',
			'slot1_param' => 'sub1',
			'slot2_param' => 'sub2',
			'enabled'     => true,
			'match_by'    => 'domain',
		),
	);

	/**
	 * Get singleton instance
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
		add_action( 'admin_post_ant_save_networks', array( $this, 'save_networks' ) );
		add_action( 'admin_post_ant_clean_urls', array( $this, 'process_clean_urls' ) );
		add_action( 'admin_post_ant_export_acf_csv', array( $this, 'handle_export_acf_csv' ) );
		
		// AJAX actions for click tracking
		add_action( 'wp_ajax_ant_log_click', array( $this, 'ajax_log_click' ) );
		add_action( 'wp_ajax_nopriv_ant_log_click', array( $this, 'ajax_log_click' ) );
		
		// Postback receiver (for affiliate networks that support S2S tracking)
		add_action( 'init', array( $this, 'handle_postback' ) );
		add_action( 'init', array( $this, 'maybe_export_acf_via_secret_url' ) );
		
		// Initialize default networks if not set
		add_action( 'admin_init', array( $this, 'initialize_defaults' ), 5 );
	}

	/**
	 * Initialize default network settings
	 */
	public function initialize_defaults() {
		$networks = get_option( 'ant_networks' );
		if ( false === $networks ) {
			update_option( 'ant_networks', $this->default_networks );
		}
	}

	/**
	 * Add admin menu
	 */
	public function add_admin_menu() {
		add_menu_page(
			__( 'Affiliate Networks', 'affiliate-network-tracker' ),
			__( 'Affiliate Networks', 'affiliate-network-tracker' ),
			'manage_options',
			'affiliate-network-tracker',
			array( $this, 'render_dashboard' ),
			'dashicons-chart-line',
			30
		);

		add_submenu_page(
			'affiliate-network-tracker',
			__( 'Network Settings', 'affiliate-network-tracker' ),
			__( 'Networks', 'affiliate-network-tracker' ),
			'manage_options',
			'affiliate-network-tracker',
			array( $this, 'render_dashboard' )
		);

		add_submenu_page(
			'affiliate-network-tracker',
			__( 'General Settings', 'affiliate-network-tracker' ),
			__( 'Settings', 'affiliate-network-tracker' ),
			'manage_options',
			'ant-settings',
			array( $this, 'render_settings_page' )
		);

		add_submenu_page(
			'affiliate-network-tracker',
			__( 'Clean Pretty Links', 'affiliate-network-tracker' ),
			__( 'Clean URLs', 'affiliate-network-tracker' ),
			'manage_options',
			'ant-clean-urls',
			array( $this, 'render_clean_urls_page' )
		);

		add_submenu_page(
			'affiliate-network-tracker',
			__( 'Click Tracking', 'affiliate-network-tracker' ),
			__( 'Click Tracker', 'affiliate-network-tracker' ),
			'manage_options',
			'ant-click-tracker',
			array( $this, 'render_click_tracker_page' )
		);

		add_submenu_page(
			'affiliate-network-tracker',
			__( 'Postback Settings', 'affiliate-network-tracker' ),
			__( 'Postback URLs', 'affiliate-network-tracker' ),
			'manage_options',
			'ant-postback',
			array( $this, 'render_postback_page' )
		);

		add_submenu_page(
			'affiliate-network-tracker',
			__( 'ACF CSV Export', 'affiliate-network-tracker' ),
			__( 'ACF Export', 'affiliate-network-tracker' ),
			'manage_options',
			'ant-acf-export',
			array( $this, 'render_acf_export_page' )
		);
	}

	/**
	 * Register settings
	 */
	public function register_settings() {
		register_setting( 'ant_settings_group', 'ant_enabled' );
		register_setting( 'ant_settings_group', 'ant_slot1_value' );
		register_setting( 'ant_settings_group', 'ant_slot2_value' );
		register_setting( 'ant_settings_group', 'ant_site_name' );
	}

	/**
	 * Enqueue admin assets
	 */
	public function enqueue_admin_assets( $hook ) {
		if ( strpos( $hook, 'affiliate-network-tracker' ) === false && $hook !== 'toplevel_page_affiliate-network-tracker' ) {
			return;
		}

		wp_enqueue_style(
			'ant-admin-css',
			plugin_dir_url( __FILE__ ) . 'assets/css/admin.css',
			array(),
			self::VERSION
		);

		wp_enqueue_script(
			'ant-admin-js',
			plugin_dir_url( __FILE__ ) . 'assets/js/admin.js',
			array( 'jquery' ),
			self::VERSION,
			true
		);
	}

	/**
	 * Enqueue frontend assets
	 */
	public function enqueue_frontend_assets() {
		$enabled = get_option( 'ant_enabled', true );
		
		// Check if we're on a redirect page
		$is_redirect_page = isset( $_GET['amr_redirect'] ) || isset( $_GET['target'] );
		
		if ( ! $enabled && ! $is_redirect_page ) {
			return;
		}

		wp_enqueue_script(
			'ant-tracker',
			plugin_dir_url( __FILE__ ) . 'assets/js/tracker.js',
			array(),
			self::VERSION,
			true
		);

		// Get networks configuration
		$networks = get_option( 'ant_networks', $this->default_networks );
		
		// Build parameter map for JavaScript
		$param_map = array();
		foreach ( $networks as $key => $network ) {
			if ( $network['enabled'] ) {
				$param_map[] = array(
					'pattern'     => $network['pattern'],
					'slot1_param' => $network['slot1_param'],
					'slot2_param' => $network['slot2_param'],
					'match_by'    => isset( $network['match_by'] ) ? $network['match_by'] : 'params',
				);
			}
		}

		// Get values
		$slot1_value = $this->get_slot1_value();
		$slot2_value = $this->get_slot2_value();

		wp_localize_script(
			'ant-tracker',
			'antConfig',
			array(
				'networks'   => $param_map,
				'slot1Value' => $slot1_value,
				'slot2Value' => $slot2_value,
				'currentHost' => parse_url( home_url(), PHP_URL_HOST ),
				'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			)
		);
	}

	/**
	 * Get Slot 1 value (site name)
	 */
	private function get_slot1_value() {
		$setting = get_option( 'ant_slot1_value', 'site_name' );
		
		if ( $setting === 'custom' ) {
			$custom = get_option( 'ant_site_name', '' );
			return ! empty( $custom ) ? sanitize_text_field( $custom ) : $this->get_sanitized_site_name();
		}
		
		return $this->get_sanitized_site_name();
	}

	/**
	 * Get Slot 2 value (page slug)
	 */
	private function get_slot2_value() {
		$setting = get_option( 'ant_slot2_value', 'post_slug' );
		
		global $post;
		
		if ( $setting === 'post_slug' && $post ) {
			return sanitize_title( $post->post_name );
		} elseif ( $setting === 'post_title' && $post ) {
			return sanitize_title( $post->post_title );
		} elseif ( $setting === 'full_path' ) {
			$path = trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );
			return sanitize_title( str_replace( '/', '-', $path ) );
		}
		
		return $post ? sanitize_title( $post->post_name ) : 'homepage';
	}

	/**
	 * Get sanitized site name
	 */
	private function get_sanitized_site_name() {
		$site_name = get_bloginfo( 'name' );
		return sanitize_title( $site_name );
	}

	/**
	 * Render dashboard page
	 */
	public function render_dashboard() {
		$networks = get_option( 'ant_networks', $this->default_networks );
		?>
		<div class="wrap ant-dashboard">
			<h1><?php _e( 'Affiliate Network Tracker', 'affiliate-network-tracker' ); ?></h1>
			
			<div class="ant-intro">
				<p><?php _e( 'Manage your affiliate network tracking parameters. Each network can have custom parameter names for Slot 1 (site name) and Slot 2 (page slug).', 'affiliate-network-tracker' ); ?></p>
			</div>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'ant_save_networks', 'ant_nonce' ); ?>
				<input type="hidden" name="action" value="ant_save_networks">

				<div class="ant-networks-grid">
					<?php foreach ( $networks as $key => $network ) : ?>
						<div class="ant-network-card">
							<div class="ant-network-header">
								<h2>
									<input type="checkbox" 
										   name="networks[<?php echo esc_attr( $key ); ?>][enabled]" 
										   value="1" 
										   <?php checked( $network['enabled'], true ); ?>
										   id="network_<?php echo esc_attr( $key ); ?>">
									<label for="network_<?php echo esc_attr( $key ); ?>">
										<?php echo esc_html( $network['name'] ); ?>
									</label>
								</h2>
							</div>

							<div class="ant-network-body">
								<div class="ant-field">
									<label><?php _e( 'Match By:', 'affiliate-network-tracker' ); ?></label>
									<select name="networks[<?php echo esc_attr( $key ); ?>][match_by]" class="regular-text">
										<option value="params" <?php selected( isset( $network['match_by'] ) ? $network['match_by'] : 'params', 'params' ); ?>>
											<?php _e( 'URL Parameters (recommended)', 'affiliate-network-tracker' ); ?>
										</option>
										<option value="domain" <?php selected( isset( $network['match_by'] ) ? $network['match_by'] : 'params', 'domain' ); ?>>
											<?php _e( 'Domain Name', 'affiliate-network-tracker' ); ?>
										</option>
									</select>
									<p class="description"><?php _e( 'How to identify this network', 'affiliate-network-tracker' ); ?></p>
								</div>

								<div class="ant-field">
									<label><?php _e( 'Pattern:', 'affiliate-network-tracker' ); ?></label>
									<input type="text" 
										   name="networks[<?php echo esc_attr( $key ); ?>][pattern]" 
										   value="<?php echo esc_attr( $network['pattern'] ); ?>"
										   class="regular-text"
										   placeholder="aff_id,subid or example.com">
									<p class="description">
										<?php _e( 'If "Parameters": comma-separated param names (e.g., aff_id,subid)', 'affiliate-network-tracker' ); ?><br>
										<?php _e( 'If "Domain": domain to match (e.g., endopeak24.com)', 'affiliate-network-tracker' ); ?>
									</p>
								</div>

							<div class="ant-field">
								<label><?php _e( 'Slot 1 Parameter (Site Name):', 'affiliate-network-tracker' ); ?></label>
								<input type="text" 
									   name="networks[<?php echo esc_attr( $key ); ?>][slot1_param]" 
									   value="<?php echo esc_attr( $network['slot1_param'] ); ?>"
									   placeholder="subid, sub1, t1"
									   class="regular-text">
								<p class="description">
									<?php _e( 'Parameter for site tracking. Plugin will REPLACE existing value with your site name.', 'affiliate-network-tracker' ); ?>
								</p>
							</div>

							<div class="ant-field">
								<label><?php _e( 'Slot 2 Parameter (Page Slug):', 'affiliate-network-tracker' ); ?></label>
								<input type="text" 
									   name="networks[<?php echo esc_attr( $key ); ?>][slot2_param]" 
									   value="<?php echo esc_attr( $network['slot2_param'] ); ?>"
									   placeholder="subid2, sub2, t2"
									   class="regular-text">
								<p class="description">
									<?php _e( 'Parameter for page tracking. Plugin will REPLACE existing value with page slug or ADD if missing.', 'affiliate-network-tracker' ); ?>
								</p>
							</div>								<input type="hidden" 
									   name="networks[<?php echo esc_attr( $key ); ?>][name]" 
									   value="<?php echo esc_attr( $network['name'] ); ?>">
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="ant-submit-wrap">
					<?php submit_button( __( 'Save Network Settings', 'affiliate-network-tracker' ), 'primary large' ); ?>
				</div>
			</form>

			<div class="ant-help-section">
				<h2><?php _e( 'How It Works', 'affiliate-network-tracker' ); ?></h2>
				<ol>
					<li><?php _e( 'Plugin detects affiliate links by matching URL parameters or domain', 'affiliate-network-tracker' ); ?></li>
					<li><?php _e( 'Automatically identifies which network based on your settings', 'affiliate-network-tracker' ); ?></li>
					<li><?php _e( 'Replaces Slot 1 parameter with your site name', 'affiliate-network-tracker' ); ?></li>
					<li><?php _e( 'Replaces Slot 2 parameter with the current page slug', 'affiliate-network-tracker' ); ?></li>
					<li><?php _e( 'Adds missing Slot 2 parameter if URL only has Slot 1', 'affiliate-network-tracker' ); ?></li>
				</ol>

				<h3><?php _e( 'Matching Methods', 'affiliate-network-tracker' ); ?></h3>
				<p><strong><?php _e( 'Parameters (Recommended):', 'affiliate-network-tracker' ); ?></strong> <?php _e( 'Matches by URL parameter names. Perfect for networks with changing domains (e.g., BuyGoods uses different domains for each product).', 'affiliate-network-tracker' ); ?></p>
				<p><strong><?php _e( 'Domain:', 'affiliate-network-tracker' ); ?></strong> <?php _e( 'Matches by domain name. Use for networks with consistent tracking domains (e.g., ClickBank always uses clickbank.net).', 'affiliate-network-tracker' ); ?></p>

				<h3><?php _e( 'Replacing Random Values', 'affiliate-network-tracker' ); ?></h3>
				<p><?php _e( 'If your URLs have random/hash values in subid or subid2, the plugin will REPLACE them with your tracking:', 'affiliate-network-tracker' ); ?></p>
				<ul style="list-style: disc; margin-left: 25px;">
					<li><?php _e( 'Random hash in subid → Replaced with your site name', 'affiliate-network-tracker' ); ?></li>
					<li><?php _e( 'Random value in subid2 → Replaced with page slug', 'affiliate-network-tracker' ); ?></li>
					<li><?php _e( 'Missing subid2 → Added with page slug', 'affiliate-network-tracker' ); ?></li>
				</ul>
				<p><strong><?php _e( 'Result:', 'affiliate-network-tracker' ); ?></strong> <?php _e( 'You\'ll see your site name and page slugs in affiliate dashboard reports (Sales by SubID / SubID2).', 'affiliate-network-tracker' ); ?></p>

				<h3><?php _e( 'Example Transformation', 'affiliate-network-tracker' ); ?></h3>
				<div class="ant-example">
					<strong><?php _e( 'Before (with random values):', 'affiliate-network-tracker' ); ?></strong>
					<code>https://product.com/order?aff_id=171&subid=9143ee8aadb64fbe</code>
					
					<strong><?php _e( 'After (values replaced):', 'affiliate-network-tracker' ); ?></strong>
					<code>https://product.com/order?aff_id=171&subid=YourSiteName&subid2=bio-peak-reviews</code>
					
					<p style="margin-top: 10px; color: #646970;">
						<?php _e( '✓ Random hash replaced with meaningful tracking data', 'affiliate-network-tracker' ); ?>
					</p>
				</div>

				<h3><?php _e( 'In Your Affiliate Dashboard', 'affiliate-network-tracker' ); ?></h3>
				<p><?php _e( 'You will see conversions with:', 'affiliate-network-tracker' ); ?></p>
				<ul>
					<li><strong>Slot 1 / SubID1:</strong> <?php echo esc_html( $this->get_slot1_value() ); ?> <em>(your site)</em></li>
					<li><strong>Slot 2 / SubID2:</strong> product-review, about-us, homepage <em>(which page converted)</em></li>
				</ul>
			</div>
		</div>
		<?php
	}

	/**
	 * Render settings page
	 */
	public function render_settings_page() {
		$enabled = get_option( 'ant_enabled', true );
		$slot1_value = get_option( 'ant_slot1_value', 'site_name' );
		$slot2_value = get_option( 'ant_slot2_value', 'post_slug' );
		$site_name = get_option( 'ant_site_name', '' );
		?>
		<div class="wrap">
			<h1><?php _e( 'General Settings', 'affiliate-network-tracker' ); ?></h1>

			<form method="post" action="options.php">
				<?php settings_fields( 'ant_settings_group' ); ?>

				<table class="form-table">
					<tr>
						<th scope="row"><?php _e( 'Enable Tracking', 'affiliate-network-tracker' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="ant_enabled" value="1" <?php checked( $enabled, true ); ?>>
								<?php _e( 'Enable automatic SubID replacement', 'affiliate-network-tracker' ); ?>
							</label>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Slot 1 Value (Site Name)', 'affiliate-network-tracker' ); ?></th>
						<td>
							<select name="ant_slot1_value">
								<option value="site_name" <?php selected( $slot1_value, 'site_name' ); ?>>
									<?php _e( 'Site Name (from WordPress settings)', 'affiliate-network-tracker' ); ?>
								</option>
								<option value="custom" <?php selected( $slot1_value, 'custom' ); ?>>
									<?php _e( 'Custom Value', 'affiliate-network-tracker' ); ?>
								</option>
							</select>
							<p class="description">
								<?php _e( 'Current site name:', 'affiliate-network-tracker' ); ?> 
								<strong><?php echo esc_html( $this->get_sanitized_site_name() ); ?></strong>
							</p>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Custom Site Name', 'affiliate-network-tracker' ); ?></th>
						<td>
							<input type="text" 
								   name="ant_site_name" 
								   value="<?php echo esc_attr( $site_name ); ?>" 
								   class="regular-text"
								   placeholder="<?php echo esc_attr( $this->get_sanitized_site_name() ); ?>">
							<p class="description"><?php _e( 'Only used if "Custom Value" is selected above', 'affiliate-network-tracker' ); ?></p>
						</td>
					</tr>

					<tr>
						<th scope="row"><?php _e( 'Slot 2 Value (Page Identifier)', 'affiliate-network-tracker' ); ?></th>
						<td>
							<select name="ant_slot2_value">
								<option value="post_slug" <?php selected( $slot2_value, 'post_slug' ); ?>>
									<?php _e( 'Post/Page Slug', 'affiliate-network-tracker' ); ?>
								</option>
								<option value="post_title" <?php selected( $slot2_value, 'post_title' ); ?>>
									<?php _e( 'Post/Page Title (sanitized)', 'affiliate-network-tracker' ); ?>
								</option>
								<option value="full_path" <?php selected( $slot2_value, 'full_path' ); ?>>
									<?php _e( 'Full URL Path', 'affiliate-network-tracker' ); ?>
								</option>
							</select>
							<p class="description">
								<?php _e( 'Current page value:', 'affiliate-network-tracker' ); ?> 
								<strong><?php echo esc_html( $this->get_slot2_value() ); ?></strong>
							</p>
						</td>
					</tr>
				</table>

				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Save networks configuration
	 */
	public function save_networks() {
		if ( ! isset( $_POST['ant_nonce'] ) || ! wp_verify_nonce( $_POST['ant_nonce'], 'ant_save_networks' ) ) {
			wp_die( __( 'Security check failed', 'affiliate-network-tracker' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Permission denied', 'affiliate-network-tracker' ) );
		}

		$networks = array();

		if ( isset( $_POST['networks'] ) && is_array( $_POST['networks'] ) ) {
			foreach ( $_POST['networks'] as $key => $network ) {
				$networks[ $key ] = array(
					'name'        => sanitize_text_field( $network['name'] ),
					'pattern'     => sanitize_text_field( $network['pattern'] ),
					'slot1_param' => sanitize_text_field( $network['slot1_param'] ),
					'slot2_param' => sanitize_text_field( $network['slot2_param'] ),
					'enabled'     => isset( $network['enabled'] ) && $network['enabled'] == '1',
					'match_by'    => isset( $network['match_by'] ) ? sanitize_text_field( $network['match_by'] ) : 'params',
				);
			}
		}

		update_option( 'ant_networks', $networks );

		wp_redirect( add_query_arg(
			array( 'page' => 'affiliate-network-tracker', 'updated' => 'true' ),
			admin_url( 'admin.php' )
		) );
		exit;
	}

	/**
	 * Render Clean URLs page
	 */
	public function render_clean_urls_page() {
		global $wpdb;

		// Check if Pretty Links is active
		$prli_table = $wpdb->prefix . 'prli_links';
		$table_exists = $wpdb->get_var( "SHOW TABLES LIKE '$prli_table'" ) === $prli_table;

		if ( ! $table_exists ) {
			?>
			<div class="wrap">
				<h1><?php _e( 'Clean Pretty Links URLs', 'affiliate-network-tracker' ); ?></h1>
				<div class="notice notice-error">
					<p><?php _e( 'Pretty Links plugin is not installed or activated.', 'affiliate-network-tracker' ); ?></p>
				</div>
			</div>
			<?php
			return;
		}

		// Get all Pretty Links
		$links = $wpdb->get_results( "SELECT id, name, slug, url FROM $prli_table ORDER BY id DESC" );
		$networks = get_option( 'ant_networks', $this->default_networks );

		// Analyze URLs
		$analyzed_links = array();
		foreach ( $links as $link ) {
			$analysis = $this->analyze_url( $link->url, $networks );
			if ( $analysis['has_issues'] || $analysis['network'] ) {
				$analyzed_links[] = array(
					'id'       => $link->id,
					'name'     => $link->name,
					'slug'     => $link->slug,
					'url'      => $link->url,
					'analysis' => $analysis,
				);
			}
		}

		?>
		<div class="wrap ant-clean-urls">
			<h1><?php _e( 'Clean Pretty Links URLs', 'affiliate-network-tracker' ); ?></h1>
			
			<div class="ant-intro">
				<p><?php _e( 'This tool scans your Pretty Links and cleans affiliate URLs by:', 'affiliate-network-tracker' ); ?></p>
				<ul style="list-style: disc; margin-left: 25px;">
					<li><?php _e( 'Removing unnecessary parameters (subid4, utm_source, etc.)', 'affiliate-network-tracker' ); ?></li>
					<li><?php _e( 'Fixing malformed URLs (spaces, double separators)', 'affiliate-network-tracker' ); ?></li>
					<li><?php _e( 'Keeping only essential tracking params (aff_id, subid, subid2)', 'affiliate-network-tracker' ); ?></li>
					<li><?php _e( 'Adding missing subid2 with placeholder value', 'affiliate-network-tracker' ); ?></li>
				</ul>
				<p><strong><?php _e( 'Note:', 'affiliate-network-tracker' ); ?></strong> <?php _e( 'The plugin will automatically replace placeholder values when users click the links.', 'affiliate-network-tracker' ); ?></p>
			</div>

			<?php if ( empty( $analyzed_links ) ) : ?>
				<div class="notice notice-success">
					<p><?php _e( '✓ All Pretty Links URLs look good! No issues found.', 'affiliate-network-tracker' ); ?></p>
				</div>
			<?php else : ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'ant_clean_urls', 'ant_clean_nonce' ); ?>
					<input type="hidden" name="action" value="ant_clean_urls">

					<p><?php printf( __( 'Found %d URLs that can be cleaned:', 'affiliate-network-tracker' ), count( $analyzed_links ) ); ?></p>

					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th width="30"><input type="checkbox" id="select-all"></th>
								<th width="15%"><?php _e( 'Link Name', 'affiliate-network-tracker' ); ?></th>
								<th width="15%"><?php _e( 'Network', 'affiliate-network-tracker' ); ?></th>
								<th width="35%"><?php _e( 'Current URL', 'affiliate-network-tracker' ); ?></th>
								<th width="35%"><?php _e( 'Cleaned URL', 'affiliate-network-tracker' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $analyzed_links as $item ) : ?>
								<tr>
									<td>
										<input type="checkbox" name="links[]" value="<?php echo esc_attr( $item['id'] ); ?>" class="link-checkbox" checked>
									</td>
									<td>
										<strong><?php echo esc_html( $item['name'] ); ?></strong><br>
										<small>/<?php echo esc_html( $item['slug'] ); ?></small>
									</td>
									<td>
										<?php if ( $item['analysis']['network'] ) : ?>
											<span class="dashicons dashicons-yes-alt" style="color: #46b450;"></span>
											<?php echo esc_html( $item['analysis']['network']['name'] ); ?>
										<?php else : ?>
											<span style="color: #999;">—</span>
										<?php endif; ?>
									</td>
									<td>
										<code style="font-size: 11px; word-break: break-all;">
											<?php echo esc_html( $this->truncate_url( $item['url'] ) ); ?>
										</code>
										<?php if ( ! empty( $item['analysis']['issues'] ) ) : ?>
											<div style="margin-top: 5px;">
												<?php foreach ( $item['analysis']['issues'] as $issue ) : ?>
													<span class="dashicons dashicons-warning" style="color: #f0b849; font-size: 14px;" title="<?php echo esc_attr( $issue ); ?>"></span>
												<?php endforeach; ?>
												<small style="color: #f0b849;"><?php echo esc_html( implode( ', ', $item['analysis']['issues'] ) ); ?></small>
											</div>
										<?php endif; ?>
									</td>
									<td>
										<code style="font-size: 11px; word-break: break-all; color: #46b450;">
											<?php echo esc_html( $this->truncate_url( $item['analysis']['cleaned_url'] ) ); ?>
										</code>
										<?php if ( $item['analysis']['params_removed'] > 0 ) : ?>
											<div style="margin-top: 5px;">
												<small style="color: #46b450;">
													<?php printf( __( '✓ %d unnecessary parameters removed', 'affiliate-network-tracker' ), $item['analysis']['params_removed'] ); ?>
												</small>
											</div>
										<?php endif; ?>
									</td>
								</tr>
								<input type="hidden" name="cleaned_urls[<?php echo esc_attr( $item['id'] ); ?>]" value="<?php echo esc_attr( $item['analysis']['cleaned_url'] ); ?>">
							<?php endforeach; ?>
						</tbody>
					</table>

					<p class="submit">
						<button type="submit" class="button button-primary button-large">
							<?php _e( 'Clean Selected URLs', 'affiliate-network-tracker' ); ?>
						</button>
						<span style="margin-left: 15px; color: #666;">
							<?php _e( 'This will update your Pretty Links database.', 'affiliate-network-tracker' ); ?>
						</span>
					</p>
				</form>

				<script>
				jQuery(document).ready(function($) {
					$('#select-all').on('change', function() {
						$('.link-checkbox').prop('checked', $(this).prop('checked'));
					});
				});
				</script>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Analyze URL for issues and cleaning
	 */
	private function analyze_url( $url, $networks ) {
		$issues = array();
		$has_issues = false;
		$network = null;
		$params_removed = 0;

		// Check for malformed URL issues
		if ( strpos( $url, '%20&' ) !== false || strpos( $url, ' &' ) !== false ) {
			$issues[] = __( 'Has spaces before parameters', 'affiliate-network-tracker' );
			$has_issues = true;
		}
		if ( strpos( $url, '??' ) !== false || strpos( $url, '&&' ) !== false ) {
			$issues[] = __( 'Has double separators', 'affiliate-network-tracker' );
			$has_issues = true;
		}

		try {
			$parsed = parse_url( $url );
			if ( ! isset( $parsed['query'] ) ) {
				return array(
					'has_issues'     => false,
					'issues'         => array(),
					'network'        => null,
					'cleaned_url'    => $url,
					'params_removed' => 0,
				);
			}

			parse_str( $parsed['query'], $params );

			// Detect network
			foreach ( $networks as $key => $net ) {
				if ( ! $net['enabled'] ) continue;

				if ( $net['match_by'] === 'params' ) {
					$patterns = explode( ',', $net['pattern'] );
					$match_count = 0;
					foreach ( $patterns as $pattern ) {
						if ( isset( $params[ trim( $pattern ) ] ) ) {
							$match_count++;
						}
					}
					if ( $match_count > 0 ) {
						$network = $net;
						break;
					}
				} elseif ( $net['match_by'] === 'domain' && isset( $parsed['host'] ) ) {
					if ( strpos( $parsed['host'], $net['pattern'] ) !== false ) {
						$network = $net;
						break;
					}
				}
			}

			// Clean URL
			$cleaned_params = array();
			// Essential params that should always be kept
			$essential_params = array( 
				'aff_id', 'uid', 'cid', 'aid', 'lid', 'tid', 'pid',  // IDs
				'cur', 'currency',                                     // Currency
				'hostid'                                               // Host identifier
			);
			
			if ( $network ) {
				$essential_params[] = $network['slot1_param'];
				$essential_params[] = $network['slot2_param'];
			}

			foreach ( $params as $key => $value ) {
				if ( in_array( $key, $essential_params ) ) {
					$cleaned_params[ $key ] = $value;
				} else {
					$params_removed++;
				}
			}

			// Add missing Slot 2 if needed
			if ( $network && isset( $cleaned_params[ $network['slot1_param'] ] ) && ! isset( $cleaned_params[ $network['slot2_param'] ] ) ) {
				$cleaned_params[ $network['slot2_param'] ] = 'placeholder';
			}

			// Rebuild URL
			$cleaned_url = $parsed['scheme'] . '://' . $parsed['host'];
			if ( isset( $parsed['path'] ) ) {
				$cleaned_url .= $parsed['path'];
			}
			if ( ! empty( $cleaned_params ) ) {
				$cleaned_url .= '?' . http_build_query( $cleaned_params );
			}

			// Clean malformed separators
			$cleaned_url = str_replace( array( '%20&', ' &', '??', '&&', '?&' ), array( '&', '&', '?', '&', '?' ), $cleaned_url );

			if ( $params_removed > 0 ) {
				$issues[] = sprintf( __( '%d unnecessary parameters', 'affiliate-network-tracker' ), $params_removed );
				$has_issues = true;
			}

		} catch ( Exception $e ) {
			$cleaned_url = $url;
		}

		return array(
			'has_issues'     => $has_issues,
			'issues'         => $issues,
			'network'        => $network,
			'cleaned_url'    => $cleaned_url,
			'params_removed' => $params_removed,
		);
	}

	/**
	 * Truncate URL for display
	 */
	private function truncate_url( $url, $length = 80 ) {
		if ( strlen( $url ) > $length ) {
			return substr( $url, 0, $length ) . '...';
		}
		return $url;
	}

	/**
	 * Process URL cleaning
	 */
	public function process_clean_urls() {
		if ( ! isset( $_POST['ant_clean_nonce'] ) || ! wp_verify_nonce( $_POST['ant_clean_nonce'], 'ant_clean_urls' ) ) {
			wp_die( __( 'Security check failed', 'affiliate-network-tracker' ) );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'Permission denied', 'affiliate-network-tracker' ) );
		}

		global $wpdb;
		$prli_table = $wpdb->prefix . 'prli_links';
		$updated = 0;

		if ( isset( $_POST['links'] ) && is_array( $_POST['links'] ) && isset( $_POST['cleaned_urls'] ) ) {
			foreach ( $_POST['links'] as $link_id ) {
				$link_id = intval( $link_id );
				if ( isset( $_POST['cleaned_urls'][ $link_id ] ) ) {
					$cleaned_url = esc_url_raw( $_POST['cleaned_urls'][ $link_id ] );
					
					$wpdb->update(
						$prli_table,
						array( 'url' => $cleaned_url ),
						array( 'id' => $link_id ),
						array( '%s' ),
						array( '%d' )
					);
					
					$updated++;
				}
			}
		}

		wp_redirect( add_query_arg(
			array( 
				'page' => 'ant-clean-urls', 
				'updated' => $updated 
			),
			admin_url( 'admin.php' )
		) );
		exit;
	}

	/**
	 * Plugin activation - create database table
	 */
	public static function activate() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'ant_clicks';
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id bigint(20) NOT NULL AUTO_INCREMENT,
			click_time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
			affiliate_url text NOT NULL,
			network_name varchar(100) DEFAULT '',
			subid varchar(255) DEFAULT '',
			subid2 varchar(255) DEFAULT '',
			source_page text DEFAULT '',
			user_ip varchar(100) DEFAULT '',
			user_agent text DEFAULT '',
			conversion_status varchar(20) DEFAULT 'pending',
			conversion_time datetime DEFAULT NULL,
			product_name varchar(255) DEFAULT '',
			commission decimal(10,2) DEFAULT 0.00,
			PRIMARY KEY  (id),
			KEY click_time (click_time),
			KEY conversion_status (conversion_status)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
	}

	/**
	 * AJAX handler to log click
	 */
	public function ajax_log_click() {
		global $wpdb;
		
		$affiliate_url = isset( $_POST['affiliate_url'] ) ? esc_url_raw( $_POST['affiliate_url'] ) : '';
		$network_name = isset( $_POST['network_name'] ) ? sanitize_text_field( $_POST['network_name'] ) : '';
		$subid = isset( $_POST['subid'] ) ? sanitize_text_field( $_POST['subid'] ) : '';
		$subid2 = isset( $_POST['subid2'] ) ? sanitize_text_field( $_POST['subid2'] ) : '';
		$source_page = isset( $_POST['source_page'] ) ? esc_url_raw( $_POST['source_page'] ) : '';
		
		if ( empty( $affiliate_url ) ) {
			wp_send_json_error( 'Missing affiliate URL' );
		}

		$table_name = $wpdb->prefix . 'ant_clicks';
		$wpdb->insert(
			$table_name,
			array(
				'affiliate_url' => $affiliate_url,
				'network_name' => $network_name,
				'subid' => $subid,
				'subid2' => $subid2,
				'source_page' => $source_page,
				'user_ip' => $this->get_user_ip(),
				'user_agent' => isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( $_SERVER['HTTP_USER_AGENT'] ) : '',
			),
			array( '%s', '%s', '%s', '%s', '%s', '%s', '%s' )
		);

		wp_send_json_success( array( 'click_id' => $wpdb->insert_id ) );
	}

	/**
	 * Get user IP address
	 */
	private function get_user_ip() {
		if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : '';
		}
		return sanitize_text_field( $ip );
	}

	/**
	 * Handle postback from affiliate networks
	 */
	public function handle_postback() {
		// Check if this is a postback request
		if ( ! isset( $_REQUEST['ant_postback'] ) ) {
			return;
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'ant_clicks';

		// Get security token
		$token = get_option( 'ant_postback_token', '' );
		$request_token = isset( $_REQUEST['token'] ) ? sanitize_text_field( $_REQUEST['token'] ) : '';

		// Verify token if set
		if ( ! empty( $token ) && $token !== $request_token ) {
			wp_die( 'Invalid token', 'Unauthorized', array( 'response' => 401 ) );
		}

		// Get postback parameters (standard names across networks)
		$subid = isset( $_REQUEST['subid'] ) ? sanitize_text_field( $_REQUEST['subid'] ) : '';
		$subid2 = isset( $_REQUEST['subid2'] ) ? sanitize_text_field( $_REQUEST['subid2'] ) : '';
		$transaction_id = isset( $_REQUEST['transaction_id'] ) ? sanitize_text_field( $_REQUEST['transaction_id'] ) : '';
		$product_name = isset( $_REQUEST['product'] ) ? sanitize_text_field( $_REQUEST['product'] ) : '';
		$commission = isset( $_REQUEST['commission'] ) ? floatval( $_REQUEST['commission'] ) : 0.00;
		$status = isset( $_REQUEST['status'] ) ? sanitize_text_field( $_REQUEST['status'] ) : 'converted';

		// Try to find matching click by SubID values
		$click = $wpdb->get_row( $wpdb->prepare(
			"SELECT * FROM $table_name WHERE subid = %s AND subid2 = %s AND conversion_status = 'pending' ORDER BY click_time DESC LIMIT 1",
			$subid,
			$subid2
		) );

		if ( $click ) {
			// Update existing click with conversion data
			$wpdb->update(
				$table_name,
				array(
					'conversion_status' => $status,
					'conversion_time' => current_time( 'mysql' ),
					'product_name' => $product_name,
					'commission' => $commission,
				),
				array( 'id' => $click->id ),
				array( '%s', '%s', '%s', '%f' ),
				array( '%d' )
			);

			// Log successful postback
			$this->log_postback( 'success', "Matched click ID {$click->id} with transaction {$transaction_id}" );
			
			wp_die( 'OK', 'Success', array( 'response' => 200 ) );
		} else {
			// No matching click found - create new conversion record
			$wpdb->insert(
				$table_name,
				array(
					'click_time' => current_time( 'mysql' ),
					'affiliate_url' => '',
					'network_name' => isset( $_REQUEST['network'] ) ? sanitize_text_field( $_REQUEST['network'] ) : 'Unknown',
					'subid' => $subid,
					'subid2' => $subid2,
					'source_page' => '',
					'user_ip' => '',
					'user_agent' => '',
					'conversion_status' => $status,
					'conversion_time' => current_time( 'mysql' ),
					'product_name' => $product_name,
					'commission' => $commission,
				),
				array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%f' )
			);

			$this->log_postback( 'warning', "No matching click found for SubID={$subid}, SubID2={$subid2}. Created new record." );
			
			wp_die( 'OK - No click match', 'Success', array( 'response' => 200 ) );
		}
	}

	/**
	 * Log postback activity
	 */
	private function log_postback( $level, $message ) {
		$log_file = WP_CONTENT_DIR . '/ant-postback.log';
		$timestamp = current_time( 'Y-m-d H:i:s' );
		$log_entry = "[{$timestamp}] [{$level}] {$message}\n";
		file_put_contents( $log_file, $log_entry, FILE_APPEND );
	}

	/**
	 * Render Postback Settings page
	 */
	public function render_postback_page() {
		// Save settings if posted
		if ( isset( $_POST['ant_save_postback'] ) && check_admin_referer( 'ant_postback_settings' ) ) {
			update_option( 'ant_postback_token', sanitize_text_field( $_POST['postback_token'] ) );
			echo '<div class="notice notice-success"><p>' . __( 'Postback settings saved!', 'affiliate-network-tracker' ) . '</p></div>';
		}

		// Generate token if not exists
		$token = get_option( 'ant_postback_token', '' );
		if ( empty( $token ) ) {
			$token = bin2hex( random_bytes( 16 ) );
			update_option( 'ant_postback_token', $token );
		}

		// Build postback URL
		$postback_url = home_url( '/?ant_postback=1&token=' . $token );

		?>
		<div class="wrap">
			<h1><?php _e( 'Postback URL Settings', 'affiliate-network-tracker' ); ?></h1>
			
			<div class="ant-postback-info" style="background: #fff; padding: 20px; border-radius: 8px; margin: 20px 0;">
				<h2><?php _e( '🔗 Your Postback URL', 'affiliate-network-tracker' ); ?></h2>
				<p><?php _e( 'Copy this URL and paste it into your affiliate network\'s postback settings:', 'affiliate-network-tracker' ); ?></p>
				
				<div style="background: #f0f0f1; padding: 15px; border-radius: 4px; margin: 15px 0; font-family: monospace; font-size: 14px; word-break: break-all;">
					<strong><?php echo esc_html( $postback_url ); ?>&subid={subid}&subid2={subid2}&transaction_id={transaction_id}&product={product_name}&commission={commission}</strong>
				</div>

				<button type="button" class="button button-primary" onclick="navigator.clipboard.writeText('<?php echo esc_js( $postback_url ); ?>&subid={subid}&subid2={subid2}&transaction_id={transaction_id}&product={product_name}&commission={commission}'); alert('Copied to clipboard!');">
					<?php _e( '📋 Copy Postback URL', 'affiliate-network-tracker' ); ?>
				</button>
			</div>

			<!-- Network-Specific Instructions -->
			<div class="ant-network-instructions" style="background: #fff; padding: 20px; border-radius: 8px; margin: 20px 0;">
				<h2><?php _e( '📚 Setup Instructions by Network', 'affiliate-network-tracker' ); ?></h2>
				
				<div class="ant-accordion">
					<!-- BuyGoods -->
					<details style="margin-bottom: 15px; border: 1px solid #ddd; padding: 15px; border-radius: 4px;">
						<summary style="cursor: pointer; font-weight: bold; font-size: 16px;">BuyGoods</summary>
						<div style="margin-top: 15px;">
							<ol>
								<li>Login to <strong>BuyGoods Dashboard</strong></li>
								<li>Go to <strong>Settings → Postback URL</strong></li>
								<li>Paste your postback URL with these macros:</li>
							</ol>
							<code style="display: block; background: #f0f0f1; padding: 10px; margin: 10px 0; border-radius: 4px;">
								<?php echo esc_html( $postback_url ); ?>&subid={subid}&subid2={subid2}&transaction_id={transaction_id}&product={product_name}&commission={commission}
							</code>
							<p><strong>Note:</strong> BuyGoods uses {subid}, {subid2}, {transaction_id}, {product_name}, {commission} as macros.</p>
						</div>
					</details>

					<!-- SellHealth -->
					<details style="margin-bottom: 15px; border: 1px solid #ddd; padding: 15px; border-radius: 4px;">
						<summary style="cursor: pointer; font-weight: bold; font-size: 16px;">SellHealth</summary>
						<div style="margin-top: 15px;">
							<ol>
								<li>Login to <strong>SellHealth Dashboard</strong></li>
								<li>Go to <strong>Tracking → S2S Postback</strong></li>
								<li>Paste your postback URL:</li>
							</ol>
							<code style="display: block; background: #f0f0f1; padding: 10px; margin: 10px 0; border-radius: 4px;">
								<?php echo esc_html( $postback_url ); ?>&subid={t1}&subid2={t2}&transaction_id={orderid}&product={productname}&commission={payout}
							</code>
							<p><strong>Note:</strong> SellHealth uses {t1}, {t2} instead of {subid}, {subid2}. Adjust parameter names accordingly.</p>
						</div>
					</details>

					<!-- ClickBank -->
					<details style="margin-bottom: 15px; border: 1px solid #ddd; padding: 15px; border-radius: 4px;">
						<summary style="cursor: pointer; font-weight: bold; font-size: 16px;">ClickBank</summary>
						<div style="margin-top: 15px;">
							<ol>
								<li>Login to <strong>ClickBank Account</strong></li>
								<li>Go to <strong>Settings → My Site → Advanced Tools</strong></li>
								<li>Set <strong>Secret Key</strong> (optional)</li>
								<li>Add <strong>Instant Notification URL</strong>:</li>
							</ol>
							<code style="display: block; background: #f0f0f1; padding: 10px; margin: 10px 0; border-radius: 4px;">
								<?php echo esc_html( $postback_url ); ?>&subid={ctid}&subid2={ctid2}&transaction_id={receipt}&product={item}&commission={amount}
							</code>
							<p><strong>Note:</strong> ClickBank uses {ctid} for tracking ID.</p>
						</div>
					</details>

					<!-- Generic Network -->
					<details style="margin-bottom: 15px; border: 1px solid #ddd; padding: 15px; border-radius: 4px;">
						<summary style="cursor: pointer; font-weight: bold; font-size: 16px;">Other Networks (Generic)</summary>
						<div style="margin-top: 15px;">
							<p>Most affiliate networks support postback URLs. Common parameter names:</p>
							<ul style="list-style: disc; margin-left: 25px;">
								<li><strong>SubID:</strong> {subid}, {sub_id}, {sid}, {tracking_id}, {aff_sub}</li>
								<li><strong>SubID2:</strong> {subid2}, {sub_id2}, {sid2}, {tracking_id2}, {aff_sub2}</li>
								<li><strong>Transaction ID:</strong> {transaction_id}, {order_id}, {orderid}, {receipt}</li>
								<li><strong>Product:</strong> {product}, {product_name}, {productname}, {item}</li>
								<li><strong>Commission:</strong> {commission}, {payout}, {amount}, {sale_amount}</li>
							</ul>
							<p><strong>Template:</strong></p>
							<code style="display: block; background: #f0f0f1; padding: 10px; margin: 10px 0; border-radius: 4px;">
								<?php echo esc_html( $postback_url ); ?>&subid=[THEIR_SUBID_MACRO]&subid2=[THEIR_SUBID2_MACRO]&transaction_id=[THEIR_TXN_MACRO]&product=[THEIR_PRODUCT_MACRO]&commission=[THEIR_COMMISSION_MACRO]
							</code>
							<p>Replace the bracketed values with your network's actual macro names.</p>
						</div>
					</details>
				</div>
			</div>

			<!-- Security Settings -->
			<div class="ant-security-settings" style="background: #fff; padding: 20px; border-radius: 8px; margin: 20px 0;">
				<h2><?php _e( '🔐 Security Token', 'affiliate-network-tracker' ); ?></h2>
				<form method="post" action="">
					<?php wp_nonce_field( 'ant_postback_settings' ); ?>
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="postback_token"><?php _e( 'Security Token', 'affiliate-network-tracker' ); ?></label>
							</th>
							<td>
								<input type="text" id="postback_token" name="postback_token" value="<?php echo esc_attr( $token ); ?>" class="regular-text" readonly>
								<p class="description">
									<?php _e( 'This token is automatically included in your postback URL to prevent unauthorized access.', 'affiliate-network-tracker' ); ?>
								</p>
								<button type="button" class="button" onclick="if(confirm('Generate new token? You will need to update all your affiliate network postback URLs!')) { document.getElementById('postback_token').value = '<?php echo esc_js( bin2hex( random_bytes( 16 ) ) ); ?>'; document.getElementById('postback_token').readOnly = false; }">
									<?php _e( 'Generate New Token', 'affiliate-network-tracker' ); ?>
								</button>
							</td>
						</tr>
					</table>
					<p class="submit">
						<button type="submit" name="ant_save_postback" class="button button-primary"><?php _e( 'Save Settings', 'affiliate-network-tracker' ); ?></button>
					</p>
				</form>
			</div>

			<!-- Testing -->
			<div class="ant-postback-test" style="background: #f0f6fc; border-left: 4px solid #2271b1; padding: 20px; margin-top: 20px; border-radius: 4px;">
				<h3><?php _e( '🧪 Test Your Postback', 'affiliate-network-tracker' ); ?></h3>
				<p><?php _e( 'To test if postback is working, manually trigger it:', 'affiliate-network-tracker' ); ?></p>
				<ol>
					<li><?php _e( 'Click an affiliate link on your site (this logs the click)', 'affiliate-network-tracker' ); ?></li>
					<li><?php _e( 'Copy the test URL below and open it in a new browser tab:', 'affiliate-network-tracker' ); ?></li>
				</ol>
				<code style="display: block; background: #fff; padding: 10px; margin: 10px 0; border-radius: 4px; word-break: break-all;">
					<?php echo esc_html( $postback_url ); ?>&subid=TestSite&subid2=test-page&transaction_id=TEST123&product=TestProduct&commission=99.99
				</code>
				<p><?php _e( 'If you see "OK", check Click Tracker - the test click should be marked as converted!', 'affiliate-network-tracker' ); ?></p>
			</div>

			<!-- Postback Log -->
			<div class="ant-postback-log" style="background: #fff; padding: 20px; border-radius: 8px; margin: 20px 0;">
				<h2><?php _e( '📋 Recent Postback Activity', 'affiliate-network-tracker' ); ?></h2>
				<?php
				$log_file = WP_CONTENT_DIR . '/ant-postback.log';
				if ( file_exists( $log_file ) ) {
					$logs = array_slice( array_reverse( file( $log_file ) ), 0, 20 );
					if ( ! empty( $logs ) ) {
						echo '<pre style="background: #f0f0f1; padding: 15px; border-radius: 4px; overflow-x: auto; max-height: 300px;">';
						foreach ( $logs as $log ) {
							echo esc_html( $log );
						}
						echo '</pre>';
					} else {
						echo '<p>' . __( 'No postback activity yet.', 'affiliate-network-tracker' ) . '</p>';
					}
				} else {
					echo '<p>' . __( 'No postback activity yet. Log file will be created after first postback.', 'affiliate-network-tracker' ) . '</p>';
				}
				?>
			</div>

			<!-- Help -->
			<div class="ant-help" style="background: #fff; padding: 20px; border-radius: 8px; margin-top: 20px;">
				<h3><?php _e( '❓ FAQ', 'affiliate-network-tracker' ); ?></h3>
				<dl>
					<dt><strong><?php _e( 'Q: Which networks support postback?', 'affiliate-network-tracker' ); ?></strong></dt>
					<dd><?php _e( 'Most major networks support it: BuyGoods, SellHealth, ClickBank, MaxBounty, etc. Check your network\'s "Settings" or "Tracking" section.', 'affiliate-network-tracker' ); ?></dd>
					
					<dt><strong><?php _e( 'Q: What if my network doesn\'t support postback?', 'affiliate-network-tracker' ); ?></strong></dt>
					<dd><?php _e( 'You\'ll need to manually match conversions using the Click Tracker page by comparing SubID values with your affiliate dashboard.', 'affiliate-network-tracker' ); ?></dd>
					
					<dt><strong><?php _e( 'Q: Is postback instant?', 'affiliate-network-tracker' ); ?></strong></dt>
					<dd><?php _e( 'Usually yes! Most networks fire postback within seconds of a sale. Some may delay until payment is verified.', 'affiliate-network-tracker' ); ?></dd>
					
					<dt><strong><?php _e( 'Q: Can I use the same postback URL for all networks?', 'affiliate-network-tracker' ); ?></strong></dt>
					<dd><?php _e( 'Yes! Just adjust the parameter names (macros) to match each network\'s format.', 'affiliate-network-tracker' ); ?></dd>
				</dl>
			</div>
		</div>
		<?php
	}

	/**
	 * Render Click Tracker dashboard page
	 */
	public function render_click_tracker_page() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'ant_clicks';

		// Get filter parameters
		$filter_network = isset( $_GET['filter_network'] ) ? sanitize_text_field( $_GET['filter_network'] ) : '';
		$filter_status = isset( $_GET['filter_status'] ) ? sanitize_text_field( $_GET['filter_status'] ) : '';
		$date_from = isset( $_GET['date_from'] ) ? sanitize_text_field( $_GET['date_from'] ) : '';
		$date_to = isset( $_GET['date_to'] ) ? sanitize_text_field( $_GET['date_to'] ) : '';

		// Build query
		$where = array( '1=1' );
		if ( $filter_network ) {
			$where[] = $wpdb->prepare( 'network_name = %s', $filter_network );
		}
		if ( $filter_status ) {
			$where[] = $wpdb->prepare( 'conversion_status = %s', $filter_status );
		}
		if ( $date_from ) {
			$where[] = $wpdb->prepare( 'DATE(click_time) >= %s', $date_from );
		}
		if ( $date_to ) {
			$where[] = $wpdb->prepare( 'DATE(click_time) <= %s', $date_to );
		}

		$where_sql = implode( ' AND ', $where );
		
		// Get clicks
		$clicks = $wpdb->get_results( "SELECT * FROM $table_name WHERE $where_sql ORDER BY click_time DESC LIMIT 100" );
		
		// Get statistics
		$total_clicks = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE $where_sql" );
		$total_conversions = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name WHERE conversion_status = 'converted' AND $where_sql" );
		$conversion_rate = $total_clicks > 0 ? round( ( $total_conversions / $total_clicks ) * 100, 2 ) : 0;
		$total_commission = $wpdb->get_var( "SELECT SUM(commission) FROM $table_name WHERE conversion_status = 'converted' AND $where_sql" );

		// Get unique networks
		$networks = $wpdb->get_col( "SELECT DISTINCT network_name FROM $table_name WHERE network_name != '' ORDER BY network_name" );
		?>
		<div class="wrap">
			<h1><?php _e( 'Click Tracking Dashboard', 'affiliate-network-tracker' ); ?></h1>
			
			<!-- Statistics Cards -->
			<div class="ant-stats-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin: 20px 0;">
				<div class="ant-stat-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<h3 style="margin: 0 0 10px; color: #666; font-size: 14px;"><?php _e( 'Total Clicks', 'affiliate-network-tracker' ); ?></h3>
					<p style="margin: 0; font-size: 32px; font-weight: bold; color: #2271b1;"><?php echo number_format( $total_clicks ); ?></p>
				</div>
				<div class="ant-stat-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<h3 style="margin: 0 0 10px; color: #666; font-size: 14px;"><?php _e( 'Conversions', 'affiliate-network-tracker' ); ?></h3>
					<p style="margin: 0; font-size: 32px; font-weight: bold; color: #00a32a;"><?php echo number_format( $total_conversions ); ?></p>
				</div>
				<div class="ant-stat-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<h3 style="margin: 0 0 10px; color: #666; font-size: 14px;"><?php _e( 'Conversion Rate', 'affiliate-network-tracker' ); ?></h3>
					<p style="margin: 0; font-size: 32px; font-weight: bold; color: #f0c33c;"><?php echo $conversion_rate; ?>%</p>
				</div>
				<div class="ant-stat-card" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
					<h3 style="margin: 0 0 10px; color: #666; font-size: 14px;"><?php _e( 'Total Commission', 'affiliate-network-tracker' ); ?></h3>
					<p style="margin: 0; font-size: 32px; font-weight: bold; color: #d63638;">$<?php echo number_format( $total_commission, 2 ); ?></p>
				</div>
			</div>

			<!-- Filters -->
			<div class="ant-filters" style="background: #fff; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
				<form method="get" action="">
					<input type="hidden" name="page" value="ant-click-tracker">
					<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px;">
						<div>
							<label><?php _e( 'Network', 'affiliate-network-tracker' ); ?></label>
							<select name="filter_network" class="regular-text">
								<option value=""><?php _e( 'All Networks', 'affiliate-network-tracker' ); ?></option>
								<?php foreach ( $networks as $network ) : ?>
									<option value="<?php echo esc_attr( $network ); ?>" <?php selected( $filter_network, $network ); ?>>
										<?php echo esc_html( $network ); ?>
									</option>
								<?php endforeach; ?>
							</select>
						</div>
						<div>
							<label><?php _e( 'Status', 'affiliate-network-tracker' ); ?></label>
							<select name="filter_status" class="regular-text">
								<option value=""><?php _e( 'All Status', 'affiliate-network-tracker' ); ?></option>
								<option value="pending" <?php selected( $filter_status, 'pending' ); ?>><?php _e( 'Pending', 'affiliate-network-tracker' ); ?></option>
								<option value="converted" <?php selected( $filter_status, 'converted' ); ?>><?php _e( 'Converted', 'affiliate-network-tracker' ); ?></option>
							</select>
						</div>
						<div>
							<label><?php _e( 'Date From', 'affiliate-network-tracker' ); ?></label>
							<input type="date" name="date_from" value="<?php echo esc_attr( $date_from ); ?>" class="regular-text">
						</div>
						<div>
							<label><?php _e( 'Date To', 'affiliate-network-tracker' ); ?></label>
							<input type="date" name="date_to" value="<?php echo esc_attr( $date_to ); ?>" class="regular-text">
						</div>
					</div>
					<p>
						<button type="submit" class="button button-primary"><?php _e( 'Apply Filters', 'affiliate-network-tracker' ); ?></button>
						<a href="<?php echo admin_url( 'admin.php?page=ant-click-tracker' ); ?>" class="button"><?php _e( 'Reset', 'affiliate-network-tracker' ); ?></a>
					</p>
				</form>
			</div>

			<!-- Clicks Table -->
			<div class="ant-clicks-table" style="background: #fff; padding: 20px; border-radius: 8px;">
				<h2><?php _e( 'Recent Clicks', 'affiliate-network-tracker' ); ?></h2>
				<?php if ( empty( $clicks ) ) : ?>
					<p><?php _e( 'No clicks tracked yet. Make sure the plugin is enabled and try clicking an affiliate link.', 'affiliate-network-tracker' ); ?></p>
				<?php else : ?>
					<table class="wp-list-table widefat fixed striped" style="margin-top: 20px;">
						<thead>
							<tr>
								<th><?php _e( 'Time', 'affiliate-network-tracker' ); ?></th>
								<th><?php _e( 'Network', 'affiliate-network-tracker' ); ?></th>
								<th><?php _e( 'Site (SubID)', 'affiliate-network-tracker' ); ?></th>
								<th><?php _e( 'Page (SubID2)', 'affiliate-network-tracker' ); ?></th>
								<th><?php _e( 'Source Page', 'affiliate-network-tracker' ); ?></th>
								<th><?php _e( 'Status', 'affiliate-network-tracker' ); ?></th>
								<th><?php _e( 'Commission', 'affiliate-network-tracker' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $clicks as $click ) : ?>
								<tr>
									<td><?php echo esc_html( date( 'M j, Y H:i', strtotime( $click->click_time ) ) ); ?></td>
									<td><strong><?php echo esc_html( $click->network_name ); ?></strong></td>
									<td><?php echo esc_html( $click->subid ); ?></td>
									<td><?php echo esc_html( $click->subid2 ); ?></td>
									<td><a href="<?php echo esc_url( $click->source_page ); ?>" target="_blank"><?php echo esc_html( basename( $click->source_page ) ); ?></a></td>
									<td>
										<?php if ( $click->conversion_status === 'converted' ) : ?>
											<span style="color: #00a32a; font-weight: bold;">✓ <?php _e( 'Converted', 'affiliate-network-tracker' ); ?></span>
										<?php else : ?>
											<span style="color: #dba617;">⏳ <?php _e( 'Pending', 'affiliate-network-tracker' ); ?></span>
										<?php endif; ?>
									</td>
									<td>
										<?php if ( $click->commission > 0 ) : ?>
											<strong>$<?php echo number_format( $click->commission, 2 ); ?></strong>
										<?php else : ?>
											—
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
			</div>

			<!-- Help Section -->
			<div class="ant-help" style="background: #f0f6fc; border-left: 4px solid #2271b1; padding: 20px; margin-top: 20px; border-radius: 4px;">
				<h3><?php _e( '📊 How Click Tracking Works', 'affiliate-network-tracker' ); ?></h3>
				<ol>
					<li><strong><?php _e( 'Automatic Logging:', 'affiliate-network-tracker' ); ?></strong> <?php _e( 'Every affiliate link click is logged with SubID and SubID2 values', 'affiliate-network-tracker' ); ?></li>
					<li><strong><?php _e( 'Match with Dashboard:', 'affiliate-network-tracker' ); ?></strong> <?php _e( 'Check your affiliate network dashboard → Reports → Sales by SubID', 'affiliate-network-tracker' ); ?></li>
					<li><strong><?php _e( 'Find Conversion:', 'affiliate-network-tracker' ); ?></strong> <?php _e( 'Match the SubID/SubID2 from affiliate dashboard with this table to see which click converted', 'affiliate-network-tracker' ); ?></li>
					<li><strong><?php _e( 'Manual Update (Optional):', 'affiliate-network-tracker' ); ?></strong> <?php _e( 'You can manually mark clicks as "converted" and add commission amounts', 'affiliate-network-tracker' ); ?></li>
				</ol>
				<p><strong><?php _e( 'Example:', 'affiliate-network-tracker' ); ?></strong> <?php _e( 'If you see a sale in BuyGoods with SubID="MySite" and SubID2="product-review", search for those values in this table to find the original click details.', 'affiliate-network-tracker' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Render ACF export page
	 */
	public function render_acf_export_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$post_type_objects = get_post_types( array( 'public' => true ), 'objects' );
		$selected_post_type = isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( $_GET['post_type'] ) ) : 'post';
		if ( empty( $selected_post_type ) || ! post_type_exists( $selected_post_type ) ) {
			$selected_post_type = 'post';
		}

		$secret = $this->get_acf_export_secret();
		$secret_url = add_query_arg(
			array(
				'ant_export_acf' => $secret,
				'post_type'      => $selected_post_type,
			),
			home_url( '/' )
		);
		?>
		<div class="wrap">
			<h1><?php _e( 'ACF CSV Export', 'affiliate-network-tracker' ); ?></h1>
			<p><?php _e( 'Scan published posts and export all detected ACF/meta fields to CSV.', 'affiliate-network-tracker' ); ?></p>

			<div style="background:#fff;padding:20px;border-radius:8px;max-width:900px;">
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<?php wp_nonce_field( 'ant_export_acf_csv_action', 'ant_export_acf_csv_nonce' ); ?>
					<input type="hidden" name="action" value="ant_export_acf_csv">
					<table class="form-table" role="presentation">
						<tr>
							<th scope="row"><label for="ant_export_post_type"><?php _e( 'Post Type', 'affiliate-network-tracker' ); ?></label></th>
							<td>
								<select id="ant_export_post_type" name="post_type">
									<?php foreach ( $post_type_objects as $post_type_key => $object ) : ?>
										<option value="<?php echo esc_attr( $post_type_key ); ?>" <?php selected( $selected_post_type, $post_type_key ); ?>>
											<?php echo esc_html( $object->labels->singular_name . ' (' . $post_type_key . ')' ); ?>
										</option>
									<?php endforeach; ?>
								</select>
								<p class="description"><?php _e( 'Example: post, product. Export includes Post ID, Post Title, product_name, product_image, and all detected fields.', 'affiliate-network-tracker' ); ?></p>
							</td>
						</tr>
					</table>
					<p>
						<button type="submit" class="button button-primary"><?php _e( 'Export CSV', 'affiliate-network-tracker' ); ?></button>
					</p>
				</form>
			</div>

			<div style="background:#fff;padding:20px;border-radius:8px;max-width:900px;margin-top:20px;">
				<h2><?php _e( 'Secret URL Trigger', 'affiliate-network-tracker' ); ?></h2>
				<p><?php _e( 'You can trigger export by URL with a secret key:', 'affiliate-network-tracker' ); ?></p>
				<code style="display:block;background:#f0f0f1;padding:10px;border-radius:4px;word-break:break-all;">
					<?php echo esc_html( $secret_url ); ?>
				</code>
			</div>
		</div>
		<?php
	}

	/**
	 * Handle CSV export from admin button
	 */
	public function handle_export_acf_csv() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'affiliate-network-tracker' ) );
		}

		check_admin_referer( 'ant_export_acf_csv_action', 'ant_export_acf_csv_nonce' );

		$post_type = isset( $_POST['post_type'] ) ? sanitize_key( wp_unslash( $_POST['post_type'] ) ) : 'post';
		$this->stream_acf_csv( $post_type );
	}

	/**
	 * Handle secret URL export trigger
	 */
	public function maybe_export_acf_via_secret_url() {
		if ( ! isset( $_GET['ant_export_acf'] ) ) {
			return;
		}

		$provided_secret = sanitize_text_field( wp_unslash( $_GET['ant_export_acf'] ) );
		$stored_secret = $this->get_acf_export_secret();

		if ( ! hash_equals( $stored_secret, $provided_secret ) ) {
			wp_die( esc_html__( 'Invalid export key.', 'affiliate-network-tracker' ), 403 );
		}

		$post_type = isset( $_GET['post_type'] ) ? sanitize_key( wp_unslash( $_GET['post_type'] ) ) : 'post';
		$this->stream_acf_csv( $post_type );
	}

	/**
	 * Generate or retrieve export secret key
	 */
	private function get_acf_export_secret() {
		$secret = get_option( 'ant_acf_export_secret', '' );
		if ( empty( $secret ) ) {
			$secret = wp_generate_password( 40, false, false );
			update_option( 'ant_acf_export_secret', $secret );
		}

		return (string) $secret;
	}

	/**
	 * Stream scanned ACF/meta data as CSV
	 */
	private function stream_acf_csv( $post_type ) {
		if ( empty( $post_type ) || ! post_type_exists( $post_type ) ) {
			wp_die( esc_html__( 'Invalid post type.', 'affiliate-network-tracker' ) );
		}

		@set_time_limit( 0 );

		$batch_size = 200;
		$field_headers = array(
			'product_name'  => 'product_name',
			'product_image' => 'product_image',
		);
		$image_fields = array(
			'product_image' => true,
		);

		// Pass 1: discover field names across published posts.
		$paged = 1;
		do {
			$scan_query = new WP_Query(
				array(
					'post_type'              => $post_type,
					'post_status'            => 'publish',
					'posts_per_page'         => $batch_size,
					'paged'                  => $paged,
					'fields'                 => 'ids',
					'orderby'                => 'ID',
					'order'                  => 'ASC',
					'no_found_rows'          => false,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'cache_results'          => false,
				)
			);

			if ( empty( $scan_query->posts ) ) {
				break;
			}

			foreach ( $scan_query->posts as $post_id ) {
				$post_id = (int) $post_id;

				if ( function_exists( 'get_field_objects' ) ) {
					$acf_fields = get_field_objects( $post_id, false, false );
					if ( is_array( $acf_fields ) ) {
						foreach ( $acf_fields as $acf_field ) {
							if ( empty( $acf_field['name'] ) ) {
								continue;
							}
							$field_name = (string) $acf_field['name'];
							if ( ! isset( $field_headers[ $field_name ] ) ) {
								$field_headers[ $field_name ] = $field_name;
							}
							if ( ! empty( $acf_field['type'] ) && in_array( $acf_field['type'], array( 'image', 'file', 'gallery' ), true ) ) {
								$image_fields[ $field_name ] = true;
							}
						}
					}
				}

				$meta_keys = get_post_custom_keys( $post_id );
				if ( is_array( $meta_keys ) ) {
					foreach ( $meta_keys as $meta_key ) {
						if ( strpos( $meta_key, '_' ) === 0 ) {
							continue;
						}
						if ( ! isset( $field_headers[ $meta_key ] ) ) {
							$field_headers[ $meta_key ] = $meta_key;
						}
					}
				}
			}

			$paged++;
			wp_cache_flush();
		} while ( $paged <= (int) $scan_query->max_num_pages );

		ksort( $field_headers );

		$headers = array_merge(
			array(
				'Post ID',
				'Post Title',
			),
			array_values( $field_headers )
		);

		$filename = 'acf-export-' . $post_type . '-' . gmdate( 'Ymd-His' ) . '.csv';
		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=' . $filename );

		$output = fopen( 'php://output', 'w' );
		if ( false === $output ) {
			wp_die( esc_html__( 'Could not open output stream.', 'affiliate-network-tracker' ) );
		}

		fwrite( $output, "\xEF\xBB\xBF" );
		fputcsv( $output, $headers );

		// Pass 2: output data rows.
		$paged = 1;
		do {
			$data_query = new WP_Query(
				array(
					'post_type'              => $post_type,
					'post_status'            => 'publish',
					'posts_per_page'         => $batch_size,
					'paged'                  => $paged,
					'fields'                 => 'ids',
					'orderby'                => 'ID',
					'order'                  => 'ASC',
					'no_found_rows'          => false,
					'update_post_meta_cache' => false,
					'update_post_term_cache' => false,
					'cache_results'          => false,
				)
			);

			if ( empty( $data_query->posts ) ) {
				break;
			}

			foreach ( $data_query->posts as $post_id ) {
				$post_id = (int) $post_id;
				$row = array(
					$post_id,
					get_the_title( $post_id ),
				);

				foreach ( array_keys( $field_headers ) as $field_name ) {
					$value = null;
					if ( function_exists( 'get_field' ) ) {
						$value = get_field( $field_name, $post_id );
					}
					if ( null === $value || false === $value || '' === $value ) {
						$value = get_post_meta( $post_id, $field_name, true );
					}

					if ( isset( $image_fields[ $field_name ] ) ) {
						$row[] = $this->normalize_acf_image_value( $value );
					} else {
						$row[] = $this->flatten_acf_export_value( $value );
					}
				}

				fputcsv( $output, $row );
			}

			$paged++;
			wp_cache_flush();
		} while ( $paged <= (int) $data_query->max_num_pages );

		wp_reset_postdata();
		fclose( $output );
		exit;
	}

	/**
	 * Convert ACF image-like value to absolute URL string.
	 */
	private function normalize_acf_image_value( $value ) {
		if ( empty( $value ) ) {
			return '';
		}

		if ( is_array( $value ) ) {
			if ( isset( $value['url'] ) && ! empty( $value['url'] ) ) {
				return esc_url_raw( (string) $value['url'] );
			}

			if ( isset( $value['ID'] ) && ! empty( $value['ID'] ) ) {
				$url = wp_get_attachment_url( (int) $value['ID'] );
				return $url ? esc_url_raw( $url ) : '';
			}

			if ( isset( $value['id'] ) && ! empty( $value['id'] ) ) {
				$url = wp_get_attachment_url( (int) $value['id'] );
				return $url ? esc_url_raw( $url ) : '';
			}

			$urls = array();
			foreach ( $value as $item ) {
				$single_url = $this->normalize_acf_image_value( $item );
				if ( ! empty( $single_url ) ) {
					$urls[] = $single_url;
				}
			}

			return implode( ' | ', array_unique( $urls ) );
		}

		if ( is_numeric( $value ) ) {
			$url = wp_get_attachment_url( (int) $value );
			return $url ? esc_url_raw( $url ) : '';
		}

		if ( is_string( $value ) ) {
			if ( filter_var( $value, FILTER_VALIDATE_URL ) ) {
				return esc_url_raw( $value );
			}

			if ( ctype_digit( $value ) ) {
				$url = wp_get_attachment_url( (int) $value );
				return $url ? esc_url_raw( $url ) : '';
			}
		}

		return '';
	}

	/**
	 * Flatten any value to string for CSV cells.
	 */
	private function flatten_acf_export_value( $value ) {
		if ( is_array( $value ) || is_object( $value ) ) {
			$encoded = wp_json_encode( $value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
			return false !== $encoded ? $encoded : '';
		}

		if ( is_bool( $value ) ) {
			return $value ? '1' : '0';
		}

		if ( null === $value ) {
			return '';
		}

		return (string) $value;
	}
}

// Initialize plugin
function ant_init() {
	return Affiliate_Network_Tracker::get_instance();
}
add_action( 'plugins_loaded', 'ant_init' );
