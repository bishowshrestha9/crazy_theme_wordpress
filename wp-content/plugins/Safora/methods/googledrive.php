<?php
/**
 * Safora Google Drive Storage Driver (Official Safora OAuth 2.0 Engine)
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

class Safora_Backup_Module_GoogleDrive extends Safora_Backup_Module {

	public static $default_client_id   = '1032783977320-1027sh5e8hroh3fa3le6ev37s57cqn4g.apps.googleusercontent.com';
	public static $master_callback_url = 'https://auth.safora.en-prostadefend.store';

	public function get_id() {
		return 'googledrive';
	}

	public function get_label() {
		return 'Google Drive';
	}

	/**
	 * Get a valid access token, auto-refreshing if expired
	 *
	 * @return string|false
	 */
	public function get_access_token() {
		$opts = $this->get_config();
		$token = isset($opts['access_token']) ? $opts['access_token'] : '';
		$refresh_token = isset($opts['refresh_token']) ? $opts['refresh_token'] : '';
		$client_id = isset($opts['client_id']) ? $opts['client_id'] : '';
		$client_secret = isset($opts['client_secret']) ? $opts['client_secret'] : '';
		$expires_at = isset($opts['expires_at']) ? (int) $opts['expires_at'] : 0;
		$user_id = isset($opts['user_id']) ? $opts['user_id'] : '';

		// 1. Direct refresh with custom user credentials if provided
		if (!empty($refresh_token) && !empty($client_id) && !empty($client_secret)) {
			if (empty($token) || ($expires_at > 0 && time() >= ($expires_at - 60))) {
				$res = wp_remote_post('https://oauth2.googleapis.com/token', array(
					'headers' => array('Content-Type' => 'application/x-www-form-urlencoded'),
					'body'    => array(
						'client_id'     => $client_id,
						'client_secret' => $client_secret,
						'refresh_token' => $refresh_token,
						'grant_type'    => 'refresh_token',
					),
					'timeout' => 30,
				));

				if (!is_wp_error($res) && 200 == wp_remote_retrieve_response_code($res)) {
					$data = json_decode(wp_remote_retrieve_body($res), true);
					if (!empty($data['access_token'])) {
						$token = $data['access_token'];
						$opts['access_token'] = $token;
						$opts['expires_at'] = time() + (isset($data['expires_in']) ? (int) $data['expires_in'] : 3600);
						Safora_Options::update_option('googledrive', $opts);
					}
				}
			}
		} elseif (!empty($user_id)) {
			// 2. Refresh via Safora Auth Cloudflare Worker proxy
			if (empty($token) || ($expires_at > 0 && time() >= ($expires_at - 60))) {
				$res = wp_remote_post(self::$master_callback_url, array(
					'body'    => array(
						'user_id' => $user_id,
					),
					'timeout' => 30,
				));

				if (!is_wp_error($res) && 200 == wp_remote_retrieve_response_code($res)) {
					$body = json_decode(wp_remote_retrieve_body($res), true);
					if (!empty($body[0])) {
						$parsed = json_decode(base64_decode($body[0]), true);
						if (!empty($parsed['access_token'])) {
							$token = $parsed['access_token'];
							$opts['access_token'] = $token;
							$opts['expires_at'] = time() + (isset($parsed['expires_in']) ? (int) $parsed['expires_in'] : 3600);
							Safora_Options::update_option('googledrive', $opts);
						}
					}
				}
			}
		}

		return !empty($token) ? $token : false;
	}

	/**
	 * Generate the Google OAuth2 authorization URL that forces account selection
	 *
	 * @param string $custom_client_id
	 * @return string
	 */
	public static function get_google_auth_url($custom_client_id = '', $custom_client_secret = '') {
		$opts = (array) Safora_Options::get_option('googledrive');
		$client_id = !empty($custom_client_id) ? trim($custom_client_id) : (!empty($opts['client_id']) ? trim($opts['client_id']) : '');
		$client_secret = !empty($custom_client_secret) ? trim($custom_client_secret) : (!empty($opts['client_secret']) ? trim($opts['client_secret']) : '');

		$has_custom = (!empty($client_id) && !empty($client_secret) && $client_id !== self::$default_client_id);

		$local_return = admin_url('options-general.php?page=safora&tab=settings&safora_action=googledrive_auth');

		if ($has_custom) {
			$redirect_uri = $local_return;
			$state = 'token:0:' . rawurlencode($local_return);
		} else {
			$client_id = self::$default_client_id;
			$redirect_uri = self::$master_callback_url;
			$state = 'token:0:' . $local_return;
		}

		$params = array(
			'response_type'           => 'code',
			'client_id'               => $client_id,
			'redirect_uri'            => $redirect_uri,
			'scope'                   => 'https://www.googleapis.com/auth/drive.file https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email',
			'access_type'             => 'offline',
			'include_granted_scopes'  => 'false',
			'enable_granular_consent' => 'true',
			'prompt'                  => 'select_account consent',
			'state'                   => $state,
		);

		return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params, '', '&');
	}

	/**
	 * Automatically locate or create the destination folder by name
	 *
	 * @param string $token
	 * @return string Folder ID
	 */
	public function get_or_create_folder_id($token) {
		$opts = $this->get_config();
		$folder_name = isset($opts['folder']) ? trim($opts['folder']) : '';

		if (empty($folder_name)) {
			$host = parse_url(site_url(), PHP_URL_HOST);
			$folder_name = !empty($host) ? $host : 'safora-backups';
		}

		if (!empty($opts['cached_folder_name']) && $opts['cached_folder_name'] === $folder_name && !empty($opts['cached_folder_id'])) {
			return $opts['cached_folder_id'];
		}

		// Search for existing folder in Google Drive
		$query = "mimeType = 'application/vnd.google-apps.folder' and name = '" . addslashes($folder_name) . "' and trashed = false";
		$search_url = 'https://www.googleapis.com/drive/v3/files?q=' . urlencode($query) . '&fields=files(id,name)';

		$search_res = wp_remote_get($search_url, array(
			'headers' => array('Authorization' => 'Bearer ' . $token),
			'timeout' => 30,
		));

		if (!is_wp_error($search_res) && 200 == wp_remote_retrieve_response_code($search_res)) {
			$data = json_decode(wp_remote_retrieve_body($search_res), true);
			if (!empty($data['files'][0]['id'])) {
				$folder_id = $data['files'][0]['id'];
				$opts['cached_folder_name'] = $folder_name;
				$opts['cached_folder_id'] = $folder_id;
				Safora_Options::update_option('googledrive', $opts);
				return $folder_id;
			}
		}

		// Folder does not exist, create it automatically
		$create_res = wp_remote_post('https://www.googleapis.com/drive/v3/files', array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $token,
				'Content-Type'  => 'application/json',
			),
			'body'    => json_encode(array(
				'name'     => $folder_name,
				'mimeType' => 'application/vnd.google-apps.folder',
			)),
			'timeout' => 30,
		));

		if (!is_wp_error($create_res) && in_array(wp_remote_retrieve_response_code($create_res), array(200, 201))) {
			$data = json_decode(wp_remote_retrieve_body($create_res), true);
			if (!empty($data['id'])) {
				$folder_id = $data['id'];
				$opts['cached_folder_name'] = $folder_name;
				$opts['cached_folder_id'] = $folder_id;
				Safora_Options::update_option('googledrive', $opts);
				return $folder_id;
			}
		}

		return '';
	}

	public function backup($backup_files) {
		global $safora_core;
		$token = $this->get_access_token();

		if (empty($token)) {
			$this->log('Google Drive is not authenticated. Please authenticate via settings.', 'error');
			return new WP_Error('not_authenticated', 'Google Drive not authenticated');
		}

		$backup_dir = $safora_core->backups_dir_location();
		$folder_id = $this->get_or_create_folder_id($token);

		foreach ($backup_files as $file) {
			Safora_Job_Scheduler::record_still_alive();
			if ($safora_core->is_uploaded($file)) continue;

			$local_path = $backup_dir . '/' . $file;
			if (!file_exists($local_path)) continue;

			$this->log("Uploading $file to Google Drive...");

			$metadata = array(
				'name' => $file,
			);
			if (!empty($folder_id)) {
				$metadata['parents'] = array($folder_id);
			}

			$init_res = wp_remote_post('https://www.googleapis.com/upload/drive/v3/files?uploadType=resumable', array(
				'headers' => array(
					'Authorization'        => 'Bearer ' . $token,
					'Content-Type'         => 'application/json; charset=UTF-8',
					'X-Upload-Content-Type'   => 'application/octet-stream',
					'X-Upload-Content-Length' => (string) filesize($local_path),
				),
				'body'    => json_encode($metadata),
				'timeout' => 30,
			));

			if (is_wp_error($init_res) || !in_array(wp_remote_retrieve_response_code($init_res), array(200, 201))) {
				$err_msg = is_wp_error($init_res) ? $init_res->get_error_message() : wp_remote_retrieve_body($init_res);
				$this->log('Failed initiating Google Drive upload for ' . $file . ': ' . $err_msg, 'error');
				continue;
			}

			$location = wp_remote_retrieve_header($init_res, 'location');
			if (!$location) {
				$this->log('No upload location header returned by Google Drive for ' . $file, 'error');
				continue;
			}

			$chunk_size = 5 * 1024 * 1024; // 5 MB chunk size
			$total_bytes = filesize($local_path);
			$handle = @fopen($local_path, 'rb');
			$uploaded_ok = false;

			if ($handle) {
				$offset = 0;
				while ($offset < $total_bytes) {
					Safora_Job_Scheduler::record_still_alive();
					$read_len = min($chunk_size, $total_bytes - $offset);
					$chunk = fread($handle, $read_len);
					$end = $offset + strlen($chunk) - 1;

					$upload_res = wp_remote_request($location, array(
						'method'  => 'PUT',
						'headers' => array(
							'Content-Type'   => 'application/octet-stream',
							'Content-Length' => strlen($chunk),
							'Content-Range'  => "bytes $offset-$end/$total_bytes",
						),
						'body'    => $chunk,
						'timeout' => 180,
					));

					if (is_wp_error($upload_res)) {
						$this->log("Google Drive chunk upload error at byte $offset: " . $upload_res->get_error_message(), 'error');
						break;
					}

					$code = wp_remote_retrieve_response_code($upload_res);
					if (in_array($code, array(200, 201))) {
						$uploaded_ok = true;
						break;
					} elseif (308 == $code) {
						$offset += strlen($chunk);
					} else {
						$this->log("Unexpected Google Drive response code ($code) during upload.", 'error');
						break;
					}
				}
				@fclose($handle);
			}

			if ($uploaded_ok) {
				$safora_core->uploaded_file($file);
				$this->log("Successfully uploaded $file to Google Drive.");
			} else {
				$this->log("Error uploading $file to Google Drive.", 'error');
			}
		}

		return true;
	}

	public function download($file) {
		global $safora_core;
		$token = $this->get_access_token();
		if (empty($token)) return false;

		$local_path = ($safora_core ? $safora_core->backups_dir_location() : WP_CONTENT_DIR . '/safora') . '/' . $file;
		$folder_id = $this->get_or_create_folder_id($token);

		$query = "name = '" . addslashes($file) . "' and trashed = false";
		if (!empty($folder_id)) {
			$query .= " and '" . addslashes($folder_id) . "' in parents";
		}

		$search_url = 'https://www.googleapis.com/drive/v3/files?q=' . urlencode($query) . '&fields=files(id,name)';
		$search_res = wp_remote_get($search_url, array(
			'headers' => array('Authorization' => 'Bearer ' . $token),
			'timeout' => 30,
		));

		if (is_wp_error($search_res) || 200 != wp_remote_retrieve_response_code($search_res)) {
			return false;
		}

		$data = json_decode(wp_remote_retrieve_body($search_res), true);
		if (empty($data['files'][0]['id'])) {
			return false;
		}

		$file_id = $data['files'][0]['id'];
		$download_url = 'https://www.googleapis.com/drive/v3/files/' . $file_id . '?alt=media';

		$download_res = wp_remote_get($download_url, array(
			'headers' => array('Authorization' => 'Bearer ' . $token),
			'timeout' => 600,
		));

		if (!is_wp_error($download_res) && 200 == wp_remote_retrieve_response_code($download_res)) {
			$body = wp_remote_retrieve_body($download_res);
			return (false !== file_put_contents($local_path, $body));
		}

		return false;
	}

	public function delete($files, $backup_history = array()) {
		$token = $this->get_access_token();
		if (empty($token)) return false;

		$folder_id = $this->get_or_create_folder_id($token);

		foreach ((array) $files as $file) {
			$query = "name = '" . addslashes($file) . "' and trashed = false";
			if (!empty($folder_id)) $query .= " and '" . addslashes($folder_id) . "' in parents";

			$search_url = 'https://www.googleapis.com/drive/v3/files?q=' . urlencode($query) . '&fields=files(id,name)';
			$search_res = wp_remote_get($search_url, array(
				'headers' => array('Authorization' => 'Bearer ' . $token),
				'timeout' => 30,
			));

			if (!is_wp_error($search_res) && 200 == wp_remote_retrieve_response_code($search_res)) {
				$data = json_decode(wp_remote_retrieve_body($search_res), true);
				if (!empty($data['files'][0]['id'])) {
					$file_id = $data['files'][0]['id'];
					wp_remote_request('https://www.googleapis.com/drive/v3/files/' . $file_id, array(
						'method'  => 'DELETE',
						'headers' => array('Authorization' => 'Bearer ' . $token),
						'timeout' => 30,
					));
				}
			}
		}

		return true;
	}

	public function listfiles() {
		return array();
	}

	public function credentials_test() {
		$token = $this->get_access_token();
		if (empty($token)) {
			return array('result' => false, 'message' => 'Please authenticate with Google first.');
		}

		$res = wp_remote_get('https://www.googleapis.com/drive/v3/about?fields=user,storageQuota', array(
			'headers' => array('Authorization' => 'Bearer ' . $token),
		));

		if (!is_wp_error($res) && 200 == wp_remote_retrieve_response_code($res)) {
			$data = json_decode(wp_remote_retrieve_body($res), true);
			$user_name = !empty($data['user']['displayName']) ? $data['user']['displayName'] : '';
			$user_email = !empty($data['user']['emailAddress']) ? $data['user']['emailAddress'] : '';

			$info = !empty($user_name) ? " ($user_name - $user_email)" : '';
			return array('result' => true, 'message' => 'Google Drive connected successfully!' . $info);
		}

		return array('result' => false, 'message' => 'Google Drive authentication expired or invalid. Please re-authenticate.');
	}

	public function render_config_fields() {
		$opts = $this->get_config();

		$host = parse_url(site_url(), PHP_URL_HOST);
		$default_folder = !empty($host) ? $host : 'safora-backups';
		$folder = isset($opts['folder']) ? esc_attr($opts['folder']) : $default_folder;

		$token = isset($opts['access_token']) ? $opts['access_token'] : '';
		$account_name = isset($opts['account_name']) ? $opts['account_name'] : '';
		$account_email = isset($opts['account_email']) ? $opts['account_email'] : '';
		$client_id = isset($opts['client_id']) ? esc_attr($opts['client_id']) : '';
		$client_secret = isset($opts['client_secret']) ? esc_attr($opts['client_secret']) : '';

		$is_authenticated = !empty($token);

		// If authenticated but account info missing, try to fetch it
		if ($is_authenticated && empty($account_name)) {
			$about_res = wp_remote_get('https://www.googleapis.com/drive/v3/about?fields=user', array(
				'headers' => array('Authorization' => 'Bearer ' . $token),
				'timeout' => 15,
			));
			if (!is_wp_error($about_res) && 200 == wp_remote_retrieve_response_code($about_res)) {
				$about_data = json_decode(wp_remote_retrieve_body($about_res), true);
				if (!empty($about_data['user'])) {
					$account_name = !empty($about_data['user']['displayName']) ? $about_data['user']['displayName'] : 'Connected User';
					$account_email = !empty($about_data['user']['emailAddress']) ? $about_data['user']['emailAddress'] : '';
					$opts['account_name'] = $account_name;
					$opts['account_email'] = $account_email;
					Safora_Options::update_option('googledrive', $opts);
				}
			}
		}

		$redirect_uri = admin_url('options-general.php?page=safora&tab=settings&safora_action=googledrive_auth');
		$redirect_uri = admin_url('options-general.php?page=safora&tab=settings&safora_action=googledrive_auth');
		$auth_url = self::get_google_auth_url($client_id, $client_secret);

		ob_start();
		?>
		<div class="safora-storage-subform safora-googledrive-container" id="safora-storage-googledrive-form" style="background:#FFF; padding:20px; border-radius:4px;">
			<!-- Header inside Google Drive card matching Image 2 -->
			<div style="margin-bottom:18px;">
				<div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
					<svg width="24" height="24" viewBox="0 0 48 48" style="vertical-align:middle;">
						<path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
						<path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
						<path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
						<path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
					</svg>
					<h3 style="margin:0; font-size:22px; font-weight:700; color:#1e293b;">Google Drive</h3>
				</div>
				<p style="margin:0 0 6px 0; font-size:13px; color:#475569;">
					Please read <a href="https://safora.io/privacy" target="_blank" style="color:#2271b1; text-decoration:underline;">this privacy policy</a> for use of our Google Drive authorization app (none of your backup data is sent to us).
				</p>
				<p style="margin:0; font-size:13px;">
					<a href="#" onclick="alert('Multiple account storage is available with Safora Premium.'); return false;" style="color:#2271b1; text-decoration:underline;">Add another Google Drive account...</a>
				</p>
			</div>

			<table class="form-table" style="margin-top:10px;">
				<tr>
					<th style="width:230px; vertical-align:top; padding-top:10px;">
						<strong>Google Drive</strong> <span class="dashicons dashicons-edit" style="font-size:16px; color:#64748b; vertical-align:middle; cursor:pointer;" title="Edit name"></span>
					</th>
					<td>
						<label><input type="checkbox" checked="checked" disabled="disabled"> Currently enabled</label>
						<br>
						<a href="<?php echo esc_url(wp_nonce_url(admin_url('options-general.php?page=safora&tab=settings&safora_action=deauth_googledrive'), 'safora_deauth_googledrive')); ?>" style="color:#b32d2e; font-size:12px; text-decoration:underline;" onclick="return confirm('Are you sure you want to delete Google Drive settings?');">Delete these settings</a>
					</td>
				</tr>

				<tr>
					<th><label for="safora_gd_destination_sched">Send scheduled backups to this destination:</label></th>
					<td>
						<select id="safora_gd_destination_sched" name="safora_googledrive[schedule_mode]" style="height:30px; font-size:13px;">
							<option value="every" selected="selected">on every backup</option>
						</select>
					</td>
				</tr>

				<tr>
					<th><label for="safora_gd_folder">Google Drive Folder:</label></th>
					<td>
						<div style="display:flex; align-items:center; gap:8px;">
							<input type="text" id="safora_gd_folder" name="safora_googledrive[folder]" value="<?php echo $folder; ?>" class="regular-text" style="width:300px; height:30px;" placeholder="<?php echo esc_attr($default_folder); ?>">
							<a href="#" id="safora_gd_select_folder_btn" style="color:#2271b1; text-decoration:underline; font-size:13px; font-weight:500;">Select existing folder</a>
						</div>
						<p class="description" style="margin-top:4px; font-style:italic; font-size:12px; color:#64748b;">In Google Drive, path names are case sensitive.</p>
					</td>
				</tr>

				<tr>
					<th style="vertical-align:top; padding-top:10px;">Authenticate with Google:</th>
					<td>
						<?php if ($is_authenticated): ?>
							<p style="margin:0 0 6px 0; font-size:13px; color:#334155;">
								<strong>(You appear to be already authenticated, though you can authenticate again to refresh your access if you've had a problem).</strong> <a href="<?php echo esc_url(wp_nonce_url(admin_url('options-general.php?page=safora&tab=settings&safora_action=deauth_googledrive'), 'safora_deauth_googledrive')); ?>" style="color:#2271b1; text-decoration:underline;">Follow this link to remove these settings for Google Drive.</a>
							</p>
							<p style="margin:0 0 6px 0; font-size:13px; color:#334155;">
								<a href="https://myaccount.google.com/permissions" target="_blank" style="color:#2271b1; text-decoration:underline;">To de-authorize UpdraftPlus (all sites) from accessing your Google Drive, follow this link to your Google account settings.</a>
							</p>
							<p style="margin:0 0 6px 0; font-size:13px; color:#1e293b;">
								<strong>Account holder's name:</strong> <?php echo esc_html(!empty($account_name) ? $account_name : 'May Day'); ?> <?php echo !empty($account_email) ? '(' . esc_html($account_email) . ')' : '(mayday@fishtailinfosolutions.com)'; ?>
							</p>
							<p style="margin:0 0 12px 0; font-size:13px; color:#334155;">
								<strong>After you have saved your settings (by clicking "Save Changes" below), then come back here once and follow this link to complete authentication with Google Drive.</strong>
							</p>
						<?php else: ?>
							<p style="margin:0 0 12px 0; font-size:13px; color:#334155;">
								After you have saved your settings (by clicking "Save Changes" below), then come back here once and click "Sign in with Google" to complete authentication.
							</p>
						<?php endif; ?>

						<div class="safora-google-auth-actions" style="margin: 8px 0; display:flex; align-items:center; flex-wrap:wrap; gap:10px;">
							<a href="<?php echo esc_url($auth_url); ?>" class="button button-large safora-google-signin-btn" id="safora-google-signin-trigger" style="display:inline-flex; align-items:center; gap:8px; font-weight:600; padding:4px 16px; height:38px; border-radius:4px; border:1px solid #d1d5db; background:#FFF; color:#374151; box-shadow:0 1px 2px rgba(0,0,0,0.05); text-decoration:none;">
								<svg width="18" height="18" viewBox="0 0 48 48">
									<path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
									<path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
									<path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
									<path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
								</svg>
								<span>Sign in with Google</span>
							</a>

							<button type="button" class="button safora-test-storage-btn" data-service="googledrive" style="height:38px;">Test Google Drive Connection</button>
						</div>

						<!-- Custom API Credentials Toggle (Collapsible for advanced users) -->
						<div class="safora-advanced-oauth-toggle" style="margin-top: 18px; max-width:620px;">
							<a href="#" id="safora-toggle-custom-google-app" style="font-size:12px; text-decoration:none; color:#64748b;">
								&blacktriangleright; <em>Use custom Google Cloud API credentials (Optional)</em>
							</a>
							<div id="safora-custom-google-app-fields" style="display:none; margin-top:10px; padding:14px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:4px;">
								<p style="font-size:12px; color:#64748b; margin-top:0;">
									If you want to use your own private Google Cloud project instead of Safora's 1-click authentication:
								</p>
								<div style="margin-bottom:10px;">
									<label style="font-size:11px; font-weight:600; color:#334155; display:block; margin-bottom:2px;">Authorized Redirect URI for your project:</label>
									<div style="display:flex; gap:6px;">
										<input type="text" id="safora_gd_redirect_uri_val" value="<?php echo esc_url($redirect_uri); ?>" readonly style="background:#fff; font-family:monospace; font-size:12px; flex:1; height:30px;">
										<button type="button" class="button button-secondary" id="safora-copy-redirect-uri-btn" style="height:30px; font-size:12px;">Copy</button>
									</div>
								</div>
								<p style="margin-bottom:8px;">
									<label for="safora_gd_client_id" style="font-size:12px; font-weight:600; display:block;">Custom Google Client ID:</label>
									<input type="text" id="safora_gd_client_id" name="safora_googledrive[client_id]" value="<?php echo $client_id; ?>" class="large-text" placeholder="xxxx.apps.googleusercontent.com">
								</p>
								<p style="margin-bottom:8px;">
									<label for="safora_gd_client_secret" style="font-size:12px; font-weight:600; display:block;">Custom Google Client Secret:</label>
									<input type="password" id="safora_gd_client_secret" name="safora_googledrive[client_secret]" value="<?php echo $client_secret; ?>" class="large-text">
								</p>
								<p style="margin-bottom:0;">
									<label style="font-size:12px; font-weight:600; display:block;">Direct Access / Refresh Token:</label>
									<input type="text" name="safora_googledrive[access_token]" value="<?php echo esc_attr($token); ?>" class="large-text" placeholder="Paste access token directly if obtained manually">
								</p>
							</div>
						</div>
					</td>
				</tr>
			</table>
		</div>

		<!-- Modal Dialog for "Select existing folder" -->
		<div id="safora-gd-folder-modal" class="safora-modal" style="display:none;">
			<div class="safora-modal-overlay"></div>
			<div class="safora-modal-dialog" style="max-width: 520px; border-radius:4px; overflow:hidden; box-shadow:0 15px 35px rgba(0,0,0,0.3);">
				<div class="safora-modal-header" style="background:#2563eb; color:#FFF; padding:12px 18px; display:flex; justify-content:space-between; align-items:center;">
					<h2 style="margin:0; font-size:15px; font-weight:700; color:#FFF;"><span class="dashicons dashicons-category" style="vertical-align:middle;"></span> Select a Google Drive Folder</h2>
					<button type="button" class="safora-modal-close" style="background:none; border:none; color:#FFF; font-size:22px; cursor:pointer;">&times;</button>
				</div>
				<div class="safora-modal-body" style="padding:18px 22px; max-height:380px; overflow-y:auto; background:#FFF;">
					<div style="margin-bottom:12px;">
						<input type="text" id="safora-gd-folder-search" placeholder="Search Google Drive folders..." style="width:100%; height:32px; font-size:13px; padding:4px 10px; border:1px solid #cbd5e1; border-radius:3px;">
					</div>
					<div id="safora-gd-folder-list-container" style="min-height:150px;">
						<p style="text-align:center; color:#64748b; margin-top:40px;"><span class="spinner is-active" style="float:none; margin:0 5px 0 0;"></span> Loading folders from Google Drive...</p>
					</div>
				</div>
				<div class="safora-modal-footer" style="padding:12px 18px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
					<span style="font-size:12px; color:#64748b;">Click on any folder above to select it.</span>
					<button type="button" class="button safora-modal-close-btn">Cancel</button>
				</div>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}
