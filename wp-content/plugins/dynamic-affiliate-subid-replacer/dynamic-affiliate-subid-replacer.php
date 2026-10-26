<?php
/**
 * Plugin Name: Dynamic Affiliate SubID Replacer
 * Plugin URI: https://example.com/dynamic-affiliate-subid-replacer
 * Description: Automatically replaces affiliate tracking parameters (subid, sub1, sub2, etc.) with site name and page slug on outbound links.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: dynamic-affiliate-subid-replacer
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants
define( 'DASR_VERSION', '1.0.0' );
define( 'DASR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DASR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DASR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Main Plugin Class
 */
class Dynamic_Affiliate_SubID_Replacer {

	/**
	 * Instance of this class
	 */
	private static $instance = null;

	/**
	 * Plugin settings
	 */
	private $settings = array();

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
		$this->load_settings();
		$this->init_hooks();
	}

	/**
	 * Load plugin settings
	 */
	private function load_settings() {
		$defaults = array(
			'enabled'           => true,
			'slot1_override'    => '',
			'slot2_mode'        => 'post_slug',
			'target_only_class' => false,
			'target_class'      => 'affiliate-link',
			'target_rel'        => false,
		);

		$saved_settings = get_option( 'dasr_settings', array() );
		$this->settings = wp_parse_args( $saved_settings, $defaults );
	}

	/**
	 * Initialize WordPress hooks
	 */
	private function init_hooks() {
		// Admin hooks
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_post_dasr_fix_urls', array( $this, 'handle_fix_urls' ) );

		// Frontend hooks
		if ( ! is_admin() && $this->is_enabled() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
		}

		// Plugin action links
		add_filter( 'plugin_action_links_' . DASR_PLUGIN_BASENAME, array( $this, 'add_action_links' ) );
	}

	/**
	 * Check if plugin is enabled
	 */
	private function is_enabled() {
		return ! empty( $this->settings['enabled'] );
	}

	/**
	 * Add admin menu
	 */
	public function add_admin_menu() {
		add_options_page(
			__( 'Dynamic Affiliate SubID Replacer Settings', 'dynamic-affiliate-subid-replacer' ),
			__( 'Affiliate SubID', 'dynamic-affiliate-subid-replacer' ),
			'manage_options',
			'dasr-settings',
			array( $this, 'render_settings_page' )
		);

		add_management_page(
			__( 'URL Syntax Scanner', 'dynamic-affiliate-subid-replacer' ),
			__( 'URL Scanner', 'dynamic-affiliate-subid-replacer' ),
			'manage_options',
			'dasr-url-scanner',
			array( $this, 'render_scanner_page' )
		);
	}

	/**
	 * Register plugin settings
	 */
	public function register_settings() {
		register_setting(
			'dasr_settings_group',
			'dasr_settings',
			array( $this, 'sanitize_settings' )
		);

		// General Settings Section
		add_settings_section(
			'dasr_general_section',
			__( 'General Settings', 'dynamic-affiliate-subid-replacer' ),
			array( $this, 'render_general_section' ),
			'dasr-settings'
		);

		// Enable/Disable
		add_settings_field(
			'dasr_enabled',
			__( 'Enable Plugin', 'dynamic-affiliate-subid-replacer' ),
			array( $this, 'render_checkbox_field' ),
			'dasr-settings',
			'dasr_general_section',
			array(
				'label_for' => 'dasr_enabled',
				'name'      => 'enabled',
				'desc'      => __( 'Enable automatic SubID replacement on outbound links.', 'dynamic-affiliate-subid-replacer' ),
			)
		);

		// Slot 1 Override
		add_settings_field(
			'dasr_slot1_override',
			__( 'Slot 1 Value (SubID)', 'dynamic-affiliate-subid-replacer' ),
			array( $this, 'render_text_field' ),
			'dasr-settings',
			'dasr_general_section',
			array(
				'label_for'   => 'dasr_slot1_override',
				'name'        => 'slot1_override',
				'placeholder' => get_bloginfo( 'name' ),
				'desc'        => __( 'Custom value for Slot 1 (subid, sub1, t1, aff_sub1, source_id). Leave empty to use site name.', 'dynamic-affiliate-subid-replacer' ),
			)
		);

		// Slot 2 Mode
		add_settings_field(
			'dasr_slot2_mode',
			__( 'Slot 2 Mode (SubID2)', 'dynamic-affiliate-subid-replacer' ),
			array( $this, 'render_select_field' ),
			'dasr-settings',
			'dasr_general_section',
			array(
				'label_for' => 'dasr_slot2_mode',
				'name'      => 'slot2_mode',
				'options'   => array(
					'post_slug' => __( 'Post/Page Slug', 'dynamic-affiliate-subid-replacer' ),
					'full_path' => __( 'Full URL Path', 'dynamic-affiliate-subid-replacer' ),
				),
				'desc'      => __( 'Choose how to generate the Slot 2 value (subid2, sub2, t2, aff_sub2).', 'dynamic-affiliate-subid-replacer' ),
			)
		);

		// Advanced Settings Section
		add_settings_section(
			'dasr_advanced_section',
			__( 'Advanced Settings', 'dynamic-affiliate-subid-replacer' ),
			array( $this, 'render_advanced_section' ),
			'dasr-settings'
		);

		// Target Only Class
		add_settings_field(
			'dasr_target_only_class',
			__( 'Target Specific CSS Class', 'dynamic-affiliate-subid-replacer' ),
			array( $this, 'render_checkbox_field' ),
			'dasr-settings',
			'dasr_advanced_section',
			array(
				'label_for' => 'dasr_target_only_class',
				'name'      => 'target_only_class',
				'desc'      => __( 'Only rewrite links with a specific CSS class.', 'dynamic-affiliate-subid-replacer' ),
			)
		);

		// Target Class Name
		add_settings_field(
			'dasr_target_class',
			__( 'Target CSS Class', 'dynamic-affiliate-subid-replacer' ),
			array( $this, 'render_text_field' ),
			'dasr-settings',
			'dasr_advanced_section',
			array(
				'label_for'   => 'dasr_target_class',
				'name'        => 'target_class',
				'placeholder' => 'affiliate-link',
				'desc'        => __( 'CSS class name to target (without the dot).', 'dynamic-affiliate-subid-replacer' ),
			)
		);

		// Target Rel Attribute
		add_settings_field(
			'dasr_target_rel',
			__( 'Target Sponsored Links', 'dynamic-affiliate-subid-replacer' ),
			array( $this, 'render_checkbox_field' ),
			'dasr-settings',
			'dasr_advanced_section',
			array(
				'label_for' => 'dasr_target_rel',
				'name'      => 'target_rel',
				'desc'      => __( 'Only rewrite links with rel="sponsored" or rel="nofollow sponsored".', 'dynamic-affiliate-subid-replacer' ),
			)
		);
	}

	/**
	 * Sanitize settings before saving
	 */
	public function sanitize_settings( $input ) {
		$sanitized = array();

		$sanitized['enabled']           = ! empty( $input['enabled'] );
		$sanitized['slot1_override']    = sanitize_text_field( $input['slot1_override'] ?? '' );
		$sanitized['slot2_mode']        = in_array( $input['slot2_mode'] ?? '', array( 'post_slug', 'full_path' ), true ) ? $input['slot2_mode'] : 'post_slug';
		$sanitized['target_only_class'] = ! empty( $input['target_only_class'] );
		$sanitized['target_class']      = sanitize_html_class( $input['target_class'] ?? 'affiliate-link' );
		$sanitized['target_rel']        = ! empty( $input['target_rel'] );

		return $sanitized;
	}

	/**
	 * Render settings page
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Check if settings were saved
		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error(
				'dasr_messages',
				'dasr_message',
				__( 'Settings saved successfully.', 'dynamic-affiliate-subid-replacer' ),
				'updated'
			);
		}

		settings_errors( 'dasr_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'dasr_settings_group' );
				do_settings_sections( 'dasr-settings' );
				submit_button( __( 'Save Settings', 'dynamic-affiliate-subid-replacer' ) );
				?>
			</form>

			<hr>

			<h2><?php esc_html_e( 'Default Tracking Parameters', 'dynamic-affiliate-subid-replacer' ); ?></h2>
			<p><?php esc_html_e( 'The plugin automatically detects and replaces the following tracking parameters:', 'dynamic-affiliate-subid-replacer' ); ?></p>
			<ul>
				<li><strong><?php esc_html_e( 'Slot 1 (Site/Source):', 'dynamic-affiliate-subid-replacer' ); ?></strong> subid, subid1, sub1, t1, aff_sub1, source_id, sourceA-id, lnk, s</li>
				<li><strong><?php esc_html_e( 'Slot 2 (Page/Content):', 'dynamic-affiliate-subid-replacer' ); ?></strong> subid2, sub2, t2, aff_sub2</li>
			</ul>
			<p>
				<?php
				printf(
					/* translators: %s: Link to documentation */
					esc_html__( 'These parameter keys can be customized using WordPress filters. See the %s for more details.', 'dynamic-affiliate-subid-replacer' ),
					'<a href="' . esc_url( DASR_PLUGIN_URL . 'readme.txt' ) . '" target="_blank">' . esc_html__( 'readme.txt', 'dynamic-affiliate-subid-replacer' ) . '</a>'
				);
				?>
			</p>

			<h3><?php esc_html_e( 'How It Works', 'dynamic-affiliate-subid-replacer' ); ?></h3>
			<p><?php esc_html_e( 'When a visitor clicks an outbound affiliate link:', 'dynamic-affiliate-subid-replacer' ); ?></p>
			<ol>
				<li><?php esc_html_e( 'JavaScript detects the click on external links', 'dynamic-affiliate-subid-replacer' ); ?></li>
				<li><?php esc_html_e( 'Checks if the URL contains any known tracking parameters', 'dynamic-affiliate-subid-replacer' ); ?></li>
				<li><?php esc_html_e( 'Replaces parameter values with site name and current page slug', 'dynamic-affiliate-subid-replacer' ); ?></li>
				<li><?php esc_html_e( 'Preserves all other query parameters and redirects to the updated URL', 'dynamic-affiliate-subid-replacer' ); ?></li>
			</ol>

			<h3><?php esc_html_e( 'Example Usage', 'dynamic-affiliate-subid-replacer' ); ?></h3>
			<p><?php esc_html_e( 'Original link:', 'dynamic-affiliate-subid-replacer' ); ?></p>
			<code>https://affiliate.com/offer?aff_id=123&amp;sub1=default&amp;sub2=test</code>
			<p><?php esc_html_e( 'After replacement (on "About Us" page):', 'dynamic-affiliate-subid-replacer' ); ?></p>
			<code>https://affiliate.com/offer?aff_id=123&amp;sub1=<?php echo esc_html( get_bloginfo( 'name' ) ); ?>&amp;sub2=about-us</code>
		</div>
		<?php
	}

	/**
	 * Render general section description
	 */
	public function render_general_section() {
		echo '<p>' . esc_html__( 'Configure how the plugin replaces affiliate tracking parameters.', 'dynamic-affiliate-subid-replacer' ) . '</p>';
	}

	/**
	 * Render advanced section description
	 */
	public function render_advanced_section() {
		echo '<p>' . esc_html__( 'Advanced options to control which links are rewritten.', 'dynamic-affiliate-subid-replacer' ) . '</p>';
	}

	/**
	 * Render checkbox field
	 */
	public function render_checkbox_field( $args ) {
		$name    = $args['name'];
		$value   = ! empty( $this->settings[ $name ] );
		$desc    = $args['desc'] ?? '';
		?>
		<label>
			<input type="checkbox" 
				   id="<?php echo esc_attr( $args['label_for'] ); ?>" 
				   name="dasr_settings[<?php echo esc_attr( $name ); ?>]" 
				   value="1" 
				   <?php checked( $value, true ); ?>>
			<?php echo esc_html( $desc ); ?>
		</label>
		<?php
	}

	/**
	 * Render text field
	 */
	public function render_text_field( $args ) {
		$name        = $args['name'];
		$value       = $this->settings[ $name ] ?? '';
		$placeholder = $args['placeholder'] ?? '';
		$desc        = $args['desc'] ?? '';
		?>
		<input type="text" 
			   id="<?php echo esc_attr( $args['label_for'] ); ?>" 
			   name="dasr_settings[<?php echo esc_attr( $name ); ?>]" 
			   value="<?php echo esc_attr( $value ); ?>" 
			   placeholder="<?php echo esc_attr( $placeholder ); ?>"
			   class="regular-text">
		<?php if ( $desc ) : ?>
			<p class="description"><?php echo esc_html( $desc ); ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Render select field
	 */
	public function render_select_field( $args ) {
		$name    = $args['name'];
		$value   = $this->settings[ $name ] ?? '';
		$options = $args['options'] ?? array();
		$desc    = $args['desc'] ?? '';
		?>
		<select id="<?php echo esc_attr( $args['label_for'] ); ?>" 
				name="dasr_settings[<?php echo esc_attr( $name ); ?>]">
			<?php foreach ( $options as $key => $label ) : ?>
				<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $value, $key ); ?>>
					<?php echo esc_html( $label ); ?>
				</option>
			<?php endforeach; ?>
		</select>
		<?php if ( $desc ) : ?>
			<p class="description"><?php echo esc_html( $desc ); ?></p>
		<?php endif; ?>
		<?php
	}

	/**
	 * Enqueue frontend assets
	 */
	public function enqueue_frontend_assets() {
		// Always enqueue on frontend and redirect pages
		$should_enqueue = ! is_admin() && $this->is_enabled();
		
		// Also enqueue on meta redirect pages (check for amr_redirect parameter)
		if ( isset( $_GET['amr_redirect'] ) || isset( $_GET['target'] ) ) {
			$should_enqueue = true;
		}
		
		if ( ! $should_enqueue ) {
			return;
		}

		wp_enqueue_script(
			'dasr-rewrite',
			DASR_PLUGIN_URL . 'assets/js/rewrite.js',
			array(),
			DASR_VERSION,
			true
		);

		// Prepare localized data
		$localized_data = array(
			'siteName'         => $this->get_slot1_value(),
			'pageSlug'         => $this->get_slot2_value(),
			'slot1Keys'        => $this->get_slot1_keys(),
			'slot2Keys'        => $this->get_slot2_keys(),
			'currentHost'      => wp_parse_url( home_url(), PHP_URL_HOST ),
			'targetOnlyClass'  => $this->settings['target_only_class'],
			'targetClass'      => $this->settings['target_class'],
			'targetRel'        => $this->settings['target_rel'],
		);

		wp_localize_script( 'dasr-rewrite', 'dasrConfig', $localized_data );
	}

	/**
	 * Get Slot 1 value (site name or custom override)
	 */
	private function get_slot1_value() {
		$value = ! empty( $this->settings['slot1_override'] ) 
			? $this->settings['slot1_override'] 
			: get_bloginfo( 'name' );

		return apply_filters( 'da_subid_slot1_value', $value );
	}

	/**
	 * Get Slot 2 value (page slug or path)
	 */
	private function get_slot2_value() {
		global $post;

		$slug = 'home';

		if ( $this->settings['slot2_mode'] === 'full_path' ) {
			$current_url = home_url( add_query_arg( array(), $_SERVER['REQUEST_URI'] ) );
			$path        = wp_parse_url( $current_url, PHP_URL_PATH );
			$slug        = trim( $path, '/' );
			$slug        = str_replace( '/', '-', $slug );
			$slug        = ! empty( $slug ) ? $slug : 'home';
		} else {
			// Post slug mode (default)
			if ( is_front_page() || is_home() ) {
				$slug = 'home';
			} elseif ( is_singular() && $post ) {
				$slug = $post->post_name;
			} elseif ( is_category() ) {
				$category = get_queried_object();
				$slug     = $category->slug ?? 'category';
			} elseif ( is_tag() ) {
				$tag  = get_queried_object();
				$slug = $tag->slug ?? 'tag';
			} elseif ( is_archive() ) {
				$slug = 'archive';
			} elseif ( is_search() ) {
				$slug = 'search';
			} elseif ( is_404() ) {
				$slug = '404';
			}
		}

		$slug = sanitize_title( $slug );

		return apply_filters( 'da_subid_slot2_value', $slug );
	}

	/**
	 * Get Slot 1 parameter keys
	 */
	private function get_slot1_keys() {
		$default_keys = array( 'subid', 'subid1', 'sub1', 't1', 'aff_sub1', 'source_id', 'sourceA-id', 'lnk', 's' );
		return apply_filters( 'da_subid_slot1_keys', $default_keys );
	}

	/**
	 * Get Slot 2 parameter keys
	 */
	private function get_slot2_keys() {
		$default_keys = array( 'subid2', 'sub2', 't2', 'aff_sub2' );
		return apply_filters( 'da_subid_slot2_keys', $default_keys );
	}

	/**
	 * Add plugin action links
	 */
	public function add_action_links( $links ) {
		$settings_link = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'options-general.php?page=dasr-settings' ),
			__( 'Settings', 'dynamic-affiliate-subid-replacer' )
		);

		$scanner_link = sprintf(
			'<a href="%s">%s</a>',
			admin_url( 'tools.php?page=dasr-url-scanner' ),
			__( 'URL Scanner', 'dynamic-affiliate-subid-replacer' )
		);

		array_unshift( $links, $settings_link, $scanner_link );

		return $links;
	}

	/**
	 * Render URL Scanner page
	 */
	public function render_scanner_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$scan_results = null;

		// Handle scan request
		if ( isset( $_POST['dasr_scan_urls'] ) && check_admin_referer( 'dasr_scan_urls' ) ) {
			$scan_results = $this->scan_urls();
		}

		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Affiliate URL Syntax Scanner', 'dynamic-affiliate-subid-replacer' ); ?></h1>
			
			<div class="notice notice-info">
				<p>
					<strong><?php esc_html_e( 'What this tool does:', 'dynamic-affiliate-subid-replacer' ); ?></strong><br>
					<?php esc_html_e( 'Scans your posts for affiliate URLs with syntax errors (using ? instead of & between parameters) that prevent proper tracking.', 'dynamic-affiliate-subid-replacer' ); ?>
				</p>
			</div>

			<form method="post" action="">
				<?php wp_nonce_field( 'dasr_scan_urls' ); ?>
				<p>
					<button type="submit" name="dasr_scan_urls" class="button button-primary button-large">
						<?php esc_html_e( '🔍 Scan All Posts for URL Errors', 'dynamic-affiliate-subid-replacer' ); ?>
					</button>
				</p>
			</form>

			<?php if ( $scan_results !== null ) : ?>
				<hr>
				<?php $this->render_scan_results( $scan_results ); ?>
			<?php endif; ?>

			<hr>

			<h2><?php esc_html_e( 'Common URL Syntax Errors', 'dynamic-affiliate-subid-replacer' ); ?></h2>
			<table class="widefat" style="max-width: 800px;">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Wrong', 'dynamic-affiliate-subid-replacer' ); ?></th>
						<th><?php esc_html_e( 'Correct', 'dynamic-affiliate-subid-replacer' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><code>?param1=value<strong style="color:red;">?</strong>param2=value</code></td>
						<td><code>?param1=value<strong style="color:green;">&amp;</strong>param2=value</code></td>
					</tr>
					<tr>
						<td><code>?subid=test<strong style="color:red;">?</strong>subid=test2</code></td>
						<td><code>?subid=test<strong style="color:green;">&amp;</strong>subid2=test2</code></td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php
	}

	/**
	 * Scan URLs for syntax errors
	 */
	private function scan_urls() {
		global $wpdb;

		$results = array(
			'total_posts'   => 0,
			'affected_posts' => 0,
			'errors_found'  => array(),
		);

		// Get all published posts with content
		$posts = $wpdb->get_results( "
			SELECT ID, post_title, post_content, post_type, post_date
			FROM {$wpdb->posts}
			WHERE post_status = 'publish'
			AND post_content LIKE '%http%'
			ORDER BY post_date DESC
		" );

		$results['total_posts'] = count( $posts );

		// Pattern to detect syntax errors: ?param=value?param=value
		$error_patterns = array(
			// Match ?...?... pattern in URLs
			'/https?:\/\/[^\s<>"]+\?[^?\s<>"&]+\?[^\s<>"]+/i',
		);

		foreach ( $posts as $post ) {
			$errors_in_post = array();

			foreach ( $error_patterns as $pattern ) {
				if ( preg_match_all( $pattern, $post->post_content, $matches ) ) {
					foreach ( $matches[0] as $bad_url ) {
						$errors_in_post[] = $bad_url;
					}
				}
			}

			if ( ! empty( $errors_in_post ) ) {
				$results['affected_posts']++;
				$results['errors_found'][] = array(
					'post_id'    => $post->ID,
					'post_title' => $post->post_title,
					'post_type'  => $post->post_type,
					'post_date'  => $post->post_date,
					'edit_link'  => get_edit_post_link( $post->ID ),
					'urls'       => array_unique( $errors_in_post ),
				);
			}
		}

		return $results;
	}

	/**
	 * Render scan results
	 */
	private function render_scan_results( $results ) {
		?>
		<div class="notice notice-success" style="padding: 15px;">
			<h2><?php esc_html_e( '✅ Scan Complete', 'dynamic-affiliate-subid-replacer' ); ?></h2>
			<p>
				<?php
				printf(
					esc_html__( 'Scanned %d posts. Found %d posts with URL syntax errors.', 'dynamic-affiliate-subid-replacer' ),
					esc_html( $results['total_posts'] ),
					esc_html( $results['affected_posts'] )
				);
				?>
			</p>
		</div>

		<?php if ( empty( $results['errors_found'] ) ) : ?>
			<div class="notice notice-success">
				<p>
					<strong><?php esc_html_e( '🎉 Great! No URL syntax errors found.', 'dynamic-affiliate-subid-replacer' ); ?></strong><br>
					<?php esc_html_e( 'All your affiliate URLs are properly formatted and will work correctly with the plugin.', 'dynamic-affiliate-subid-replacer' ); ?>
				</p>
			</div>
		<?php else : ?>
			<div class="notice notice-warning">
				<p>
					<strong><?php esc_html_e( '⚠️ Action Required:', 'dynamic-affiliate-subid-replacer' ); ?></strong><br>
					<?php esc_html_e( 'The following posts contain URLs with syntax errors. Fix them to ensure proper tracking.', 'dynamic-affiliate-subid-replacer' ); ?>
				</p>
			</div>

			<h2><?php esc_html_e( 'Posts with URL Errors', 'dynamic-affiliate-subid-replacer' ); ?></h2>

			<?php foreach ( $results['errors_found'] as $error ) : ?>
				<div class="card" style="max-width: 100%; margin-bottom: 20px;">
					<h3 style="margin-top: 0;">
						<?php echo esc_html( $error['post_title'] ); ?>
						<span style="color: #666; font-size: 14px; font-weight: normal;">
							(<?php echo esc_html( ucfirst( $error['post_type'] ) ); ?> - <?php echo esc_html( date( 'M d, Y', strtotime( $error['post_date'] ) ) ); ?>)
						</span>
					</h3>

					<p>
						<a href="<?php echo esc_url( $error['edit_link'] ); ?>" class="button button-primary" target="_blank">
							<?php esc_html_e( '✏️ Edit Post', 'dynamic-affiliate-subid-replacer' ); ?>
						</a>
					</p>

					<h4><?php esc_html_e( 'URLs with Syntax Errors:', 'dynamic-affiliate-subid-replacer' ); ?></h4>
					<div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #dc3232; margin-bottom: 15px;">
						<?php foreach ( $error['urls'] as $url ) : ?>
							<div style="margin-bottom: 10px; font-family: monospace; word-break: break-all;">
								<strong style="color: #dc3232;">❌ Wrong:</strong><br>
								<code style="background: #fff; padding: 5px; display: block; margin: 5px 0;"><?php echo esc_html( $url ); ?></code>
								
								<strong style="color: #46b450;">✅ Should be:</strong><br>
								<code style="background: #fff; padding: 5px; display: block; margin: 5px 0; color: #46b450;">
									<?php echo esc_html( $this->suggest_fix( $url ) ); ?>
								</code>
							</div>
						<?php endforeach; ?>
					</div>

					<details style="margin-top: 10px;">
						<summary style="cursor: pointer; color: #0073aa;">
							<?php esc_html_e( '📝 How to Fix', 'dynamic-affiliate-subid-replacer' ); ?>
						</summary>
						<ol style="margin-top: 10px;">
							<li><?php esc_html_e( 'Click "Edit Post" button above', 'dynamic-affiliate-subid-replacer' ); ?></li>
							<li><?php esc_html_e( 'Use Find & Replace (Ctrl+F or Cmd+F) to find the wrong URL', 'dynamic-affiliate-subid-replacer' ); ?></li>
							<li><?php esc_html_e( 'Replace ? with & between parameters', 'dynamic-affiliate-subid-replacer' ); ?></li>
							<li><?php esc_html_e( 'Consider using different parameter names (subid vs subid2) for clarity', 'dynamic-affiliate-subid-replacer' ); ?></li>
							<li><?php esc_html_e( 'Update the post', 'dynamic-affiliate-subid-replacer' ); ?></li>
						</ol>
					</details>
				</div>
			<?php endforeach; ?>

			<div class="notice notice-info">
				<h3><?php esc_html_e( '💡 Bulk Fix Option', 'dynamic-affiliate-subid-replacer' ); ?></h3>
				<p><?php esc_html_e( 'If you have many posts to fix, consider using a Search & Replace plugin:', 'dynamic-affiliate-subid-replacer' ); ?></p>
				<ul>
					<li><strong>Better Search Replace</strong> - Safe database search and replace</li>
					<li><strong>WP Migrate DB</strong> - Includes find/replace functionality</li>
					<li><strong>phpMyAdmin</strong> - Direct database access (backup first!)</li>
				</ul>
				<p>
					<strong><?php esc_html_e( 'Or use SQL:', 'dynamic-affiliate-subid-replacer' ); ?></strong>
					<code style="display: block; background: #f9f9f9; padding: 10px; margin-top: 10px;">
						-- BACKUP DATABASE FIRST!<br>
						UPDATE wp_posts SET post_content = REPLACE(post_content, '?subid=', '?subid=');<br>
						-- Then manually fix each specific pattern
					</code>
				</p>
			</div>
		<?php endif; ?>
		<?php
	}

	/**
	 * Suggest a fix for a malformed URL
	 */
	private function suggest_fix( $url ) {
		// Replace ? with & after the first ?
		$parts = explode( '?', $url, 2 );
		if ( count( $parts ) === 2 ) {
			$fixed = $parts[0] . '?' . str_replace( '?', '&', $parts[1] );
			
			// Also suggest changing duplicate parameter names
			$fixed = preg_replace( '/([?&])subid=([^&]+)&subid=/i', '$1subid=$2&subid2=', $fixed );
			
			return $fixed;
		}
		
		return $url;
	}

	/**
	 * Handle bulk URL fix
	 */
	public function handle_fix_urls() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'dynamic-affiliate-subid-replacer' ) );
		}

		check_admin_referer( 'dasr_fix_urls' );

		// This would contain the fix logic
		// For safety, we're only scanning and showing results
		// Users should manually fix or use search/replace plugins

		wp_safe_redirect( admin_url( 'tools.php?page=dasr-url-scanner&scan=1' ) );
		exit;
	}
}

/**
 * Initialize the plugin
 */
function dasr_init() {
	return Dynamic_Affiliate_SubID_Replacer::get_instance();
}

// Start the plugin
dasr_init();

/**
 * Activation hook
 */
register_activation_hook( __FILE__, 'dasr_activate' );

function dasr_activate() {
	// Set default options
	$default_settings = array(
		'enabled'           => true,
		'slot1_override'    => '',
		'slot2_mode'        => 'post_slug',
		'target_only_class' => false,
		'target_class'      => 'affiliate-link',
		'target_rel'        => false,
	);

	add_option( 'dasr_settings', $default_settings );
}

/**
 * Deactivation hook
 */
register_deactivation_hook( __FILE__, 'dasr_deactivate' );

function dasr_deactivate() {
	// Cleanup if needed (optional)
}
