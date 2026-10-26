<?php
/**
 * Safora Admin UI Controller & Menu Manager
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Admin {

	public static function init() {
		add_action('admin_menu', array(__CLASS__, 'add_menu_pages'));
		add_action('network_admin_menu', array(__CLASS__, 'add_network_menu_pages'));
		add_action('admin_enqueue_scripts', array(__CLASS__, 'enqueue_assets'));
		add_action('admin_init', array(__CLASS__, 'handle_settings_save'));
	}

	public static function add_menu_pages() {
		// Top-level menu page
		add_menu_page(
			'Safora Backup',
			'Safora Backup',
			'manage_options',
			'safora',
			array(__CLASS__, 'render_admin_page'),
			'dashicons-cloud-saved',
			99
		);

		// Settings submenu page (options-general.php)
		add_options_page(
			'Safora Backup',
			'Safora Backup',
			'manage_options',
			'safora',
			array(__CLASS__, 'render_admin_page')
		);
	}

	public static function add_network_menu_pages() {
		add_menu_page(
			'Safora Backup',
			'Safora Backup',
			'manage_options',
			'safora',
			array(__CLASS__, 'render_admin_page'),
			'dashicons-cloud-saved',
			99
		);

		add_submenu_page(
			'settings.php',
			'Safora Backup',
			'Safora Backup',
			'manage_options',
			'safora',
			array(__CLASS__, 'render_admin_page')
		);
	}

	public static function enqueue_assets($hook) {
		if (false === strpos($hook, 'safora')) return;

		wp_enqueue_style('safora-admin-css', plugin_dir_url(__FILE__) . 'css/admin.css', array(), defined('SAFORA_VERSION') ? SAFORA_VERSION : '1.4.1');
		wp_enqueue_script('safora-admin-js', plugin_dir_url(__FILE__) . 'js/admin-common.js', array('jquery'), defined('SAFORA_VERSION') ? SAFORA_VERSION : '1.4.1', true);

		wp_localize_script('safora-admin-js', 'safora_vars', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce'    => wp_create_nonce('safora_admin_nonce'),
		));
	}

	public static function handle_settings_save() {
		// Handle Google Drive deauthorization
		if (isset($_GET['safora_action']) && 'deauth_googledrive' === $_GET['safora_action'] && current_user_can('manage_options')) {
			check_admin_referer('safora_deauth_googledrive');
			Safora_Options::delete_option('googledrive');
			$services = (array) Safora_Options::get_setting('service');
			$services = array_diff($services, array('googledrive'));
			Safora_Options::update_option('service', array_values($services));
			wp_redirect(add_query_arg(array('page' => 'safora', 'tab' => 'settings', 'googledrive_deauthed' => '1'), admin_url('options-general.php')));
			exit;
		}

		// Handle Google Drive OAuth authorization callback
		if (isset($_GET['safora_action']) && 'googledrive_auth' === $_GET['safora_action'] && current_user_can('manage_options')) {
			$opts = (array) Safora_Options::get_option('googledrive');

			// 1. Auth proxy response (user_id and/or access_token)
			if (isset($_GET['user_id']) || isset($_GET['access_token']) || isset($_POST['user_id']) || isset($_POST['access_token'])) {
				$raw_uid = isset($_GET['user_id']) ? $_GET['user_id'] : (isset($_POST['user_id']) ? $_POST['user_id'] : '');
				$raw_tok = isset($_GET['access_token']) ? $_GET['access_token'] : (isset($_POST['access_token']) ? $_POST['access_token'] : '');

				$user_id = base64_decode($raw_uid, true);
				if (false === $user_id || !preg_match('/^[a-zA-Z0-9_\-\.]+$/', $user_id)) {
					$user_id = sanitize_text_field($raw_uid);
				}

				$access_token = base64_decode($raw_tok, true);
				if (false === $access_token || strpos($access_token, 'ya29.') === false) {
					$access_token = sanitize_text_field($raw_tok);
				}

				if (!empty($user_id)) $opts['user_id'] = $user_id;
				if (!empty($access_token)) $opts['access_token'] = $access_token;
				$opts['expires_at'] = time() + 3600;

				if (!empty($access_token)) {
					$about_res = wp_remote_get('https://www.googleapis.com/drive/v3/about?fields=user', array(
						'headers' => array('Authorization' => 'Bearer ' . $access_token),
						'timeout' => 15,
					));
					if (!is_wp_error($about_res) && 200 == wp_remote_retrieve_response_code($about_res)) {
						$about_data = json_decode(wp_remote_retrieve_body($about_res), true);
						if (!empty($about_data['user'])) {
							$opts['account_name'] = !empty($about_data['user']['displayName']) ? $about_data['user']['displayName'] : '';
							$opts['account_email'] = !empty($about_data['user']['emailAddress']) ? $about_data['user']['emailAddress'] : '';
						}
					}
				}

				Safora_Options::update_option('googledrive', $opts);

				$services = (array) Safora_Options::get_setting('service');
				if (!in_array('googledrive', $services)) {
					$services[] = 'googledrive';
					Safora_Options::update_option('service', array_values($services));
				}

				wp_redirect(add_query_arg(array('page' => 'safora', 'tab' => 'settings', 'googledrive_authed' => '1'), admin_url('options-general.php')));
				exit;
			}

			// Direct authorization code from Google OAuth 2.0
			if (isset($_GET['code'])) {
				$code = sanitize_text_field($_GET['code']);
				$client_id = !empty($opts['client_id']) ? trim($opts['client_id']) : '';
				$client_secret = !empty($opts['client_secret']) ? trim($opts['client_secret']) : '';
				$redirect_uri = admin_url('options-general.php?page=safora&tab=settings&safora_action=googledrive_auth');

				if (!empty($client_id) && !empty($client_secret)) {
					$token_res = wp_remote_post('https://oauth2.googleapis.com/token', array(
						'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'),
						'body'    => array(
							'code'          => $code,
							'client_id'     => $client_id,
							'client_secret' => $client_secret,
							'redirect_uri'  => $redirect_uri,
							'grant_type'    => 'authorization_code',
						),
						'timeout' => 30,
					));

					if (!is_wp_error($token_res) && 200 == wp_remote_retrieve_response_code($token_res)) {
						$token_data = json_decode(wp_remote_retrieve_body($token_res), true);
						if (!empty($token_data['access_token'])) {
							$opts['access_token'] = $token_data['access_token'];
							if (!empty($token_data['refresh_token'])) {
								$opts['refresh_token'] = $token_data['refresh_token'];
							}
							$opts['expires_at'] = time() + (isset($token_data['expires_in']) ? (int) $token_data['expires_in'] : 3600);

							// Fetch account profile information
							$about_res = wp_remote_get('https://www.googleapis.com/drive/v3/about?fields=user', array(
								'headers' => array('Authorization' => 'Bearer ' . $opts['access_token']),
								'timeout' => 15,
							));
							if (!is_wp_error($about_res) && 200 == wp_remote_retrieve_response_code($about_res)) {
								$about_data = json_decode(wp_remote_retrieve_body($about_res), true);
								if (!empty($about_data['user'])) {
									$opts['account_name'] = !empty($about_data['user']['displayName']) ? $about_data['user']['displayName'] : '';
									$opts['account_email'] = !empty($about_data['user']['emailAddress']) ? $about_data['user']['emailAddress'] : '';
								}
							}

							Safora_Options::update_option('googledrive', $opts);

							$services = (array) Safora_Options::get_setting('service');
							if (!in_array('googledrive', $services)) {
								$services[] = 'googledrive';
								Safora_Options::update_option('service', array_values($services));
							}

							wp_redirect(add_query_arg(array('page' => 'safora', 'tab' => 'settings', 'googledrive_authed' => '1'), admin_url('options-general.php')));
							exit;
						}
					}
				}
			}
		}

		if (!isset($_POST['safora_settings_nonce']) || !wp_verify_nonce($_POST['safora_settings_nonce'], 'safora_save_settings')) {
			return;
		}

		if (!current_user_can('manage_options')) return;

		// Save global settings
		if (isset($_POST['safora_settings'])) {
			foreach ($_POST['safora_settings'] as $k => $v) {
				Safora_Options::update_option($k, sanitize_text_field($v));
			}
		}

		// Save checkboxes explicitly
		$checkbox_keys = array('include_plugins', 'include_themes', 'include_uploads', 'include_others', 'include_wpcore', 'binzip', 'binmysqldump', 'delete_local');
		foreach ($checkbox_keys as $chk) {
			$val = isset($_POST['safora_settings'][$chk]) ? 1 : 0;
			Safora_Options::update_option($chk, $val);
		}

		// Save additional retention rules
		if (isset($_POST['safora_retain_extrarules']) && is_array($_POST['safora_retain_extrarules'])) {
			$cleaned_rules = array('files' => array(), 'db' => array());
			foreach (array('files', 'db') as $type) {
				if (!empty($_POST['safora_retain_extrarules'][$type]) && is_array($_POST['safora_retain_extrarules'][$type])) {
					foreach ($_POST['safora_retain_extrarules'][$type] as $r) {
						if (isset($r['after_howmany'], $r['every_howmany'])) {
							$cleaned_rules[$type][] = array(
								'after_howmany' => max(1, absint($r['after_howmany'])),
								'after_period'  => sanitize_text_field($r['after_period']),
								'every_howmany' => max(1, absint($r['every_howmany'])),
								'every_period'  => sanitize_text_field($r['every_period']),
							);
						}
					}
				}
			}
			Safora_Options::update_option('retain_extrarules', $cleaned_rules);
		} else {
			Safora_Options::update_option('retain_extrarules', array('files' => array(), 'db' => array()));
		}

		// Save enabled storage services
		$services = isset($_POST['safora_service']) ? (array) $_POST['safora_service'] : array();

		// Save individual driver settings (merge with existing so tokens and credentials are never erased)
		$drivers = array('s3', 'googledrive', 'dropbox', 'ftp', 'sftp', 'email', 'onedrive', 'webdav', 'remotesend');
		foreach ($drivers as $d) {
			if (isset($_POST['safora_' . $d])) {
				$existing_opts = (array) Safora_Options::get_option($d, array());
				$merged = array_merge($existing_opts, (array) $_POST['safora_' . $d]);
				Safora_Options::update_option($d, $merged);
			}
		}

		// Auto-preserve Google Drive in active services if authenticated
		$gd_opts = (array) Safora_Options::get_option('googledrive', array());
		if (!empty($gd_opts['access_token']) || !empty($gd_opts['refresh_token']) || !empty($gd_opts['user_id'])) {
			if (!in_array('googledrive', $services)) {
				$services[] = 'googledrive';
			}
		}

		Safora_Options::update_option('service', array_values(array_unique($services)));

		// Reschedule WP-Cron backup hooks according to new schedule intervals and start-times
		global $safora_core;
		if ($safora_core && method_exists($safora_core, 'reschedule_cron_backups')) {
			$safora_core->reschedule_cron_backups();
		}

		wp_redirect(add_query_arg(array('page' => 'safora', 'tab' => 'settings', 'settings_saved' => '1'), admin_url('options-general.php')));
		exit;
	}

	public static function render_admin_page() {
		global $title;
		if (empty($title)) {
			$title = 'Safora Backup';
		}

		$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'dashboard';
		$rescan_msg = '';
		if (isset($_GET['rescan']) && current_user_can('manage_options')) {
			$total = Safora_Backup_History::rescan_local_backups();
			$rescan_msg = "Rescan complete: found $total backup sets on disk.";
		}

		$main_tabs = array(
			'dashboard' => 'Backup / Restore',
			'migration' => 'Migrate / Clone',
			'settings'  => 'Settings',
			'tools'     => 'Advanced Tools',
			'addons'    => 'Premium / Extensions',
		);
		?>
		<div class="wrap safora-wrap">
			<div class="safora-top-sublinks" style="font-size:12px; color:#2271b1; margin-bottom:12px; line-height:1.6;">
				<a href="#" style="text-decoration:underline;">TeamSafora</a> | 
				<a href="#" style="text-decoration:underline;">Blogs</a> | 
				<a href="#" style="text-decoration:underline;">Twitter / X</a> | 
				<a href="#" style="text-decoration:underline;">Support</a> | 
				<a href="#" style="text-decoration:underline;">Lead developer's homepage</a> | 
				<a href="#" style="text-decoration:underline;">Documentation</a> | 
				<a href="#" style="text-decoration:underline;">More plugins</a> - 
				<span style="color:#64748b;">Version: <?php echo defined('SAFORA_VERSION') ? esc_html(SAFORA_VERSION) : '1.4.1'; ?></span>
			</div>

			<?php if (isset($_GET['settings_saved'])): ?>
				<div class="notice notice-success is-dismissible"><p>Settings saved successfully.</p></div>
			<?php endif; ?>

			<?php if (isset($_GET['googledrive_authed'])): ?>
				<div class="notice notice-success is-dismissible"><p><strong>Success:</strong> Google Drive has been successfully authenticated and connected!</p></div>
			<?php endif; ?>

			<?php if (isset($_GET['googledrive_deauthed'])): ?>
				<div class="notice notice-info is-dismissible"><p>Google Drive settings and authentication have been removed.</p></div>
			<?php endif; ?>

			<?php if (!empty($rescan_msg)): ?>
				<div class="notice notice-success is-dismissible"><p><?php echo esc_html($rescan_msg); ?></p></div>
			<?php endif; ?>

			<h2 class="nav-tab-wrapper safora-nav-tabs">
				<?php foreach ($main_tabs as $tab_slug => $tab_label): ?>
					<a class="nav-tab <?php echo ($active_tab === $tab_slug) ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url(add_query_arg(array('page' => 'safora', 'tab' => $tab_slug), admin_url('options-general.php'))); ?>">
						<?php echo esc_html($tab_label); ?>
					</a>
				<?php endforeach; ?>
			</h2>

			<div class="safora-tab-content" style="margin-top: 15px;">
				<?php
				switch ($active_tab) {
					case 'settings':
						include_once plugin_dir_path(__FILE__) . 'templates/settings.php';
						break;
					case 'migration':
						include_once plugin_dir_path(__FILE__) . 'templates/migration.php';
						break;
					case 'tools':
						include_once plugin_dir_path(__FILE__) . 'templates/advanced-tools.php';
						break;
					case 'addons':
						include_once plugin_dir_path(__FILE__) . 'templates/addons.php';
						break;
					case 'dashboard':
					default:
						include_once plugin_dir_path(__FILE__) . 'templates/dashboard.php';
						break;
				}
				?>
			</div>
		</div>
		<?php
	}
}
