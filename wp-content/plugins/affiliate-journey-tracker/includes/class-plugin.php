<?php
namespace AffiliateJourneyTracker;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main Plugin Class (Singleton)
 */
class Plugin {

	private static ?self $instance = null;

	public Cookie_Manager $cookie_manager;
	public Settings $settings;
	public Debug_Page $debug_page;
	public Migration $migration;
	public Admin_Menu $admin_menu;
	public BuyButtonForwarder $buy_button_forwarder;
	public PrettyLinkForwarder $pretty_link_forwarder;
	public ForwardingTester $forwarding_tester;
	public HealthDashboard $health_dashboard;

	public static function get_instance(): self {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->cookie_manager        = new Cookie_Manager();
		$this->settings              = new Settings();
		$this->debug_page            = new Debug_Page();
		$this->migration             = new Migration();
		$this->admin_menu            = new Admin_Menu();
		$this->buy_button_forwarder  = new BuyButtonForwarder();
		$this->pretty_link_forwarder = new PrettyLinkForwarder();
		$this->forwarding_tester     = new ForwardingTester();
		$this->health_dashboard     = new HealthDashboard();

		$this->cookie_manager->init();
		$this->settings->init();
		$this->debug_page->init();
		$this->migration->init();
		$this->admin_menu->init();
		$this->buy_button_forwarder->init();
		$this->pretty_link_forwarder->init();
		$this->forwarding_tester->init();

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'wp_ajax_affiliate_jt_log_click', array( $this, 'ajax_log_click' ) );
		add_action( 'wp_ajax_nopriv_affiliate_jt_log_click', array( $this, 'ajax_log_click' ) );
	}

	/**
	 * Handle AJAX click logging from frontend buttons.
	 */
	public function ajax_log_click(): void {
		$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'affiliate_jt_frontend_nonce' ) ) {
			wp_send_json_error( array( 'message' => 'Invalid security token' ), 403 );
		}

		$ip            = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '127.0.0.1';
		$rate_key      = 'affiliate_jt_rate_' . md5( $ip );
		$request_count = (int) get_transient( $rate_key );

		if ( $request_count >= 30 ) {
			wp_send_json_error( array( 'message' => 'Rate limit exceeded' ), 429 );
		}
		set_transient( $rate_key, $request_count + 1, 60 );

		$origin_slug = isset( $_POST['origin_slug'] ) ? sanitize_title( wp_unslash( $_POST['origin_slug'] ) ) : '';
		$source_url  = isset( $_POST['source_url'] ) ? esc_url_raw( wp_unslash( $_POST['source_url'] ) ) : '';
		$target_url  = isset( $_POST['target_url'] ) ? esc_url_raw( wp_unslash( $_POST['target_url'] ) ) : '';
		$event_type  = isset( $_POST['event_type'] ) ? sanitize_text_field( wp_unslash( $_POST['event_type'] ) ) : 'Button Click';

		if ( empty( $target_url ) ) {
			wp_send_json_error( array( 'message' => 'Empty target URL' ) );
		}

		$origin_slug = substr( $origin_slug, 0, 200 );
		$source_url  = substr( $source_url, 0, 2048 );
		$target_url  = substr( $target_url, 0, 2048 );
		$event_type  = substr( $event_type, 0, 50 );

		Logger::log_event(
			array(
				'event_type'  => $event_type,
				'origin_slug' => $origin_slug,
				'source_url'  => $source_url,
				'target_url'  => $target_url,
				'message'     => sprintf( 'User clicked %s on page: %s (ref_page: %s)', $event_type, $source_url, $origin_slug ),
			),
			'info'
		);

		wp_send_json_success( array( 'logged' => true ) );
	}

	public function enqueue_frontend_scripts(): void {
		$post                = get_post();
		$is_top_listing      = $post ? is_top_listing( $post ) : false;
		$current_slug        = $post ? $post->post_name : '';
		$current_id          = $post ? $post->ID : 0;
		$is_home_or_front    = is_front_page() || is_home();
		$is_admin_logged_in  = is_user_logged_in() && current_user_can( 'manage_options' );
		$should_track_cookie = ! $is_home_or_front && ! empty( $current_slug );

		wp_enqueue_script(
			'affiliate-jt-tracker',
			AFFILIATE_JT_URL . 'assets/js/tracker.js',
			array(),
			AFFILIATE_JT_VERSION,
			true
		);

		$is_listicle         = $post ? is_listicle( $post ) : false;
		$attribution_state   = Cookie_Manager::get_attribution_state();

		$script_data = array(
			'ajaxUrl'           => admin_url( 'admin-ajax.php' ),
			'nonce'             => wp_create_nonce( 'affiliate_jt_frontend_nonce' ),
			'isListicle'        => $is_listicle,
			'isTopListing'      => $is_listicle,
			'isHomeOrFront'     => $is_home_or_front,
			'isAdminLoggedIn'   => $is_admin_logged_in,
			'shouldTrackCookie' => $should_track_cookie,
			'currentPostId'     => $current_id,
			'currentSlug'       => $current_slug,
			'cookieExpiryHours' => (int) get_option( 'affiliate_jt_cookie_expiry', 24 ),
			'siteADomains'      => sanitize_text_field( get_option( 'affiliate_jt_site_a_domains', "thebuyersreviews.com\ndailyhealthsupplement.com\nsupplementmag.com" ) ),
			'siteBDomain'       => sanitize_text_field( get_option( 'affiliate_jt_site_b_domains', "bestproductpicks.com\ngetbestsupplements.com\nsiteb.com" ) ),
			'cookieName'        => Cookie_Manager::COOKIE_NAME,
			'storedCookieSlug'  => Cookie_Manager::get_stored_slug(),
			'calculatedOrigin'  => Url_Builder::resolve_origin( $post ),
			'attributionState'  => $attribution_state,
			'enableLogs'        => (int) get_option( 'affiliate_jt_enable_logs', 1 ),
		);

		wp_localize_script( 'affiliate-jt-tracker', 'affiliateJtData', $script_data );
	}

	public function enqueue_admin_scripts( string $hook_suffix ): void {
		if ( false === strpos( $hook_suffix, 'affiliate-journey' ) ) {
			return;
		}

		wp_enqueue_style(
			'affiliate-jt-admin-css',
			AFFILIATE_JT_URL . 'assets/css/admin.css',
			array(),
			AFFILIATE_JT_VERSION
		);

		wp_enqueue_script(
			'affiliate-jt-admin-js',
			AFFILIATE_JT_URL . 'assets/js/admin.js',
			array(),
			AFFILIATE_JT_VERSION,
			true
		);

		wp_localize_script(
			'affiliate-jt-admin-js',
			'affiliateJtAdmin',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'affiliate_jt_debug_nonce' ),
			)
		);
	}
}
