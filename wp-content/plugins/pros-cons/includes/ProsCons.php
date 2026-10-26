<?php
/**
 * Main Plugin class
 *
 * @package ProsCons
 */

namespace ProsCons;

defined( 'ABSPATH' ) || exit;

/**
 * Final ProsCons class.
 *
 * @package ProsCons
 */
final class ProsCons {

	/**
	 * Single instance of the class
	 *
	 * @var ProsCons|null
	 */
	protected static $instance = null;

	/**
	 * Holds the admin settings instance.
	 *
	 * @var ProsConsAdmin
	 */
	public $admin_settings;

	/**
	 * Holds the public instance.
	 *
	 * @var ProsConsPublic
	 */
	public $public;

	/**
	 * Holds the blocks instance.
	 *
	 * @var ProsConsBlocks
	 */
	public $blocks;

	/**
	 * Holds the widgets instance.
	 *
	 * @var ProsConsWidgets
	 */
	public $widgets;

	/**
	 * Class Instance
	 *
	 * @return class instance
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Plugin constructor
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();

		$this->admin_settings = new ProsConsAdmin();
		$this->public         = new ProsConsPublic();
		$this->blocks         = new ProsConsBlocks();
		$this->widgets        = new ProsConsWidgets();
	}

	/**
	 * Fires during plugin activation
	 *
	 * @return void
	 */
	public function activate() {
	}

	/**
	 * Fires during plugin deactivation
	 *
	 * @return void
	 */
	public function deactivate() {
	}

	/**
	 * Init plugin hooks.
	 *
	 * @return void
	 */
	public function init_hooks() {
		register_activation_hook( PROSCONS_PLUGIN_FILE, array( $this, 'activate' ) );
		register_deactivation_hook( PROSCONS_PLUGIN_FILE, array( $this, 'deactivate' ) );

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Init Wheel_Of_Life when WordPress initializes.
	 *
	 * @since 1.0.0
	 * @access public
	 */ 	public function init() {
		// Before init action.
		do_action( 'before_prosandcons_init' );

		// Set up localization.
		$this->load_plugin_textdomain();
	}

	/**
	 * Define constants
	 *
	 * @return void
	 */
	public function define_constants() {
		$this->define( 'PROSCONS_PLUGIN_NAME', 'prosandcons' );
		$this->define( 'PROSCONS_ABSPATH', dirname( PROSCONS_PLUGIN_FILE ) . '/' );
		$this->define( 'PROSCONS_PLUGIN_DIR', dirname( PROSCONS_PLUGIN_FILE ) );
		$this->define( 'PROSCONS_PLUGIN_URL', plugin_dir_url( PROSCONS_PLUGIN_FILE ) );
		$this->define( 'PROSCONS_TABLET_BREAKPOINT', '1024' );
		$this->define( 'PROSCONS_MOBILE_BREAKPOINT', '767' );
	}

	/**
	 * Plugin includes
	 *
	 * @return void
	 */
	public function includes() {
		// Load helper functions first - these are required by other classes
		require plugin_dir_path( PROSCONS_PLUGIN_FILE ) . 'includes/functions/HelperFunctions.php';
		require plugin_dir_path( PROSCONS_PLUGIN_FILE ) . 'includes/functions/AjaxFunctions.php';
		require plugin_dir_path( PROSCONS_PLUGIN_FILE ) . 'includes/helpers/class-prosandcons-helpers.php';
		require plugin_dir_path( PROSCONS_PLUGIN_FILE ) . 'includes/helpers/class-prosandcons-block-helpers.php';
		require plugin_dir_path( PROSCONS_PLUGIN_FILE ) . 'includes/helpers/class-prosandcons-customization-helper.php';
		require plugin_dir_path( PROSCONS_PLUGIN_FILE ) . 'includes/classes/class-ab-fonts-manager.php';
		require plugin_dir_path( PROSCONS_PLUGIN_FILE ) . 'includes/notice/NoticeHandler.php';
		require plugin_dir_path( PROSCONS_PLUGIN_FILE ) . 'includes/ProsConsTemplateLibrary.php';
		require plugin_dir_path( PROSCONS_PLUGIN_FILE ) . 'includes/migration/MigrationManager.php';
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name       Constant name.
	 * @param string|bool $value      Constant value.
	 * @return void
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 *
	 * Note: the first-loaded translation file overrides any following ones -
	 * - if the same translation is present.
	 *
	 * Locales found in:
	 *      - WP_LANG_DIR/prosandcons/prosandcons-LOCALE.mo
	 *      - WP_LANG_DIR/plugins/prosandcons-LOCALE.mo
	 */
	public function load_plugin_textdomain() {
		if ( function_exists( 'determine_locale' ) ) {
			$locale = determine_locale();
		} else {
			$locale = is_admin() ? get_user_locale() : get_locale();
		}

		$locale = apply_filters( 'plugin_locale', $locale, 'prosandcons' );

		unload_textdomain( 'prosandcons' );
		load_textdomain( 'prosandcons', WP_LANG_DIR . '/prosandcons/prosandcons-' . $locale . '.mo' );
	}
}
