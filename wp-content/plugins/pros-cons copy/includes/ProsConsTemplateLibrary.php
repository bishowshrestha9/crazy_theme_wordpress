<?php
/**
 * Template Library Handler
 *
 * @package ProsCons
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Template Library Handler
 *
 * @package ProsCons
 */
class ProsConsTemplateLibrary {
	/**
	 * The single instance of the class.
	 *
	 * @var ProsConsTemplateLibrary|null
	 */
	protected static $instance = null;

	/**
	 * Option name for storing templates
	 */
	const TEMPLATE_OPTION_KEY = 'prosandcons_template_library';

	/**
	 * Option name for storing Elementor templates
	 */
	const ELEMENTOR_TEMPLATE_OPTION_KEY = 'prosandcons_elementor_template_library';

	/**
	 * Option name for storing user templates
	 */
	const USER_TEMPLATES_KEY = 'prosandcons_user_templates';

	/**
	 * Domain for template library
	 */
	const TEMPLATE_LIBRARY_DOMAIN = 'https://prosandconsblocks.com';

	/**
	 * Transient key to store template auto fetch cooldown flag.
	 */
	const AUTOFETCH_TRANSIENT_KEY = 'prosandcons_template_auto_fetch_cooldown';

	/**
	 * Main Instance.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'schedule_template_update' ) );
		add_action( 'init', array( $this, 'maybe_fetch_missing_templates' ) );
		add_action( 'prosandcons_daily_template_update', array( $this, 'update_template_library' ) );
		add_action( 'wp_ajax_get_template_library', array( $this, 'get_template_library' ) );

		// Elementor template endpoints
		add_action( 'wp_ajax_get_elementor_template_library', array( $this, 'get_elementor_template_library' ) );

		// User template endpoints
		add_action( 'wp_ajax_affx_save_user_template', array( $this, 'save_user_template' ) );
		add_action( 'wp_ajax_affx_get_user_templates', array( $this, 'get_user_templates' ) );
		add_action( 'wp_ajax_affx_delete_user_template', array( $this, 'delete_user_template' ) );
	}

	/**
	 * Check if templates exist, if not fetch them immediately (rate-limited to once per hour).
	 */
	public function maybe_fetch_missing_templates() {
		$recently_fetched = get_transient( self::AUTOFETCH_TRANSIENT_KEY );

		if ( $recently_fetched ) {
			return;
		}

		if ( empty( get_option( self::TEMPLATE_OPTION_KEY ) ) ) {
			$this->update_template_library();
		}

		if ( empty( get_option( self::ELEMENTOR_TEMPLATE_OPTION_KEY ) ) ) {
			$this->update_elementor_template_library();
		}

		set_transient( self::AUTOFETCH_TRANSIENT_KEY, true, HOUR_IN_SECONDS );
	}

	/**
	 * Schedule daily template update
	 */
	public function schedule_template_update() {
		if ( ! wp_next_scheduled( 'prosandcons_daily_template_update' ) ) {
			wp_schedule_event( time(), 'daily', 'prosandcons_daily_template_update' );
		}
	}

	/**
	 * Update template library from remote source
	 */
	public function update_template_library() {
		return $this->update_templates_from_remote( '/template-library.json', self::TEMPLATE_OPTION_KEY );
	}

	/**
	 * Ajax handler for getting template library
	 */
	public function get_template_library() {
		$this->get_templates_ajax( self::TEMPLATE_OPTION_KEY, 'update_template_library' );
	}

	/**
	 * Update Elementor template library from remote source
	 */
	public function update_elementor_template_library() {
		return $this->update_templates_from_remote( '/elementor-template-library.json', self::ELEMENTOR_TEMPLATE_OPTION_KEY );
	}

	/**
	 * Ajax handler for getting Elementor template library
	 */
	public function get_elementor_template_library() {
		$this->get_templates_ajax( self::ELEMENTOR_TEMPLATE_OPTION_KEY, 'update_elementor_template_library' );
	}

	/**
	 * Generic method to handle template library AJAX requests
	 *
	 * @param string $option_key The option key to retrieve templates from
	 * @param string $update_method The method name to call if templates are empty
	 */
	private function get_templates_ajax( $option_key, $update_method ) {
		check_ajax_referer( 'prosandcons_ajax_nonce', 'security' );

		$templates = get_option( $option_key, array() );

		if ( empty( $templates ) ) {
			$this->$update_method();
			$templates = get_option( $option_key, array() );
		}

		wp_send_json_success( $templates );
	}

	/**
	 * Generic method to update templates from remote source
	 *
	 * @param string $json_file The JSON file path on the remote server
	 * @param string $option_key The option key to store templates
	 * @return bool True on success, false on failure
	 */
	private function update_templates_from_remote( $json_file, $option_key ) {
		$response = wp_remote_get( self::TEMPLATE_LIBRARY_DOMAIN . $json_file );

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $response );
		if ( empty( $body ) ) {
			return false;
		}

		$data = json_decode( $body, true );

		if ( json_last_error() === JSON_ERROR_NONE ) {
			update_option( $option_key, $data );
			return true;
		}

		return false;
	}

	/**
	 * Check if Elementor templates exist
	 */
	public function has_elementor_templates() {
		$templates = get_option( self::ELEMENTOR_TEMPLATE_OPTION_KEY, array() );
		return ! empty( $templates );
	}

	/**
	 * Validate AJAX request with nonce and capability check
	 */
	private function validate_ajax_request() {
		check_ajax_referer( 'prosandcons_ajax_nonce', 'security' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'Insufficient permissions' ) );
		}
	}

	/**
	 * Filter templates by editor type
	 *
	 * @param array  $templates   Array of templates
	 * @param string $editor_type Editor type to filter by
	 * @return array Filtered templates
	 */
	private function filter_templates_by_editor( $templates, $editor_type ) {
		$filtered = array_filter(
			$templates,
			function ( $template ) use ( $editor_type ) {
				return isset( $template['editorType'] ) && $template['editorType'] === $editor_type;
			}
		);

		return array_values( $filtered );
	}

	/**
	 * Save user template
	 */
	public function save_user_template() {
		$this->validate_ajax_request(); // Nonce verified here

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce verified in validate_ajax_request()
		if ( ! isset( $_POST['name'] ) || ! isset( $_POST['blockType'] ) || ! isset( $_POST['content'] ) || ! isset( $_POST['editorType'] ) ) {
			wp_send_json_error( array( 'message' => 'Missing required fields' ) );
		}

		$name        = sanitize_text_field( wp_unslash( $_POST['name'] ) );
		$block_type  = sanitize_text_field( wp_unslash( $_POST['blockType'] ) );
		$editor_type = sanitize_text_field( wp_unslash( $_POST['editorType'] ) );

		// Sanitize content based on editor type
		if ( $editor_type === 'elementor' ) {
			// Elementor stores JSON - validate and sanitize
			$content = sanitize_textarea_field( wp_unslash( $_POST['content'] ) );
			if ( json_decode( $content ) === null && json_last_error() !== JSON_ERROR_NONE ) {
				wp_send_json_error( array( 'message' => 'Invalid template data' ) );
			}
		} else {
			// Gutenberg blocks
			$content = wp_kses_post( wp_unslash( $_POST['content'] ) );
		}
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$templates = get_option( self::USER_TEMPLATES_KEY, array() );

		if ( count( $templates ) >= 100 ) {
			wp_send_json_error( array( 'message' => 'Template limit reached (100). Please delete some templates first.' ) );
		}

		$new_template = array(
			'id'         => wp_generate_uuid4(),
			'name'       => $name,
			'blockType'  => $block_type,
			'content'    => $content,
			'editorType' => $editor_type,
			'timestamp'  => time(),
		);

		$templates[] = $new_template;

		update_option( self::USER_TEMPLATES_KEY, $templates, 'no' );

		wp_send_json_success(
			array(
				'message'  => 'Template saved successfully',
				'template' => $new_template,
			)
		);
	}

	/**
	 * Get user templates
	 */
	public function get_user_templates() {
		$this->validate_ajax_request(); // Nonce verified here

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Nonce verified in validate_ajax_request()
		$editor_type   = isset( $_POST['editorType'] ) ? sanitize_text_field( wp_unslash( $_POST['editorType'] ) ) : 'gutenberg';
		$all_templates = get_option( self::USER_TEMPLATES_KEY, array() );

		$filtered_templates = $this->filter_templates_by_editor( $all_templates, $editor_type );

		wp_send_json_success( $filtered_templates );
	}

	/**
	 * Delete user template
	 */
	public function delete_user_template() {
		$this->validate_ajax_request(); // Nonce verified here

		// phpcs:disable WordPress.Security.NonceVerification.Missing -- Nonce verified in validate_ajax_request()
		if ( ! isset( $_POST['templateId'] ) ) {
			wp_send_json_error( array( 'message' => 'Template ID is required' ) );
		}

		$template_id = sanitize_text_field( wp_unslash( $_POST['templateId'] ) );
		// phpcs:enable WordPress.Security.NonceVerification.Missing
		$templates = get_option( self::USER_TEMPLATES_KEY, array() );

		$templates = array_filter(
			$templates,
			function ( $template ) use ( $template_id ) {
				return $template['id'] !== $template_id;
			}
		);

		$templates = array_values( $templates );

		update_option( self::USER_TEMPLATES_KEY, $templates, 'no' );

		wp_send_json_success(
			array(
				'message'   => 'Template deleted successfully',
				'templates' => $templates,
			)
		);
	}
}

ProsConsTemplateLibrary::instance();
