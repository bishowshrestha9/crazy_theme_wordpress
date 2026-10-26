<?php
/**
 * OAuth 2.0 Handler for Google Drive
 */

if (!defined('ABSPATH')) {
    exit;
}

class WP_GD_OAuth_Handler
{
    private static $instance = null;
    private $client_id;
    private $client_secret;
    private $redirect_uri;
    private $option_key = 'wp_gd_backup_oauth_token';
    private $oauth_state_key = 'wp_gd_backup_oauth_state';

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->client_id = get_option('wp_gd_backup_client_id');
        $this->client_secret = get_option('wp_gd_backup_client_secret');
        $this->redirect_uri = admin_url('admin.php?page=wp-gd-backup&oauth_callback=1');

        add_action('admin_init', array($this, 'handle_oauth_callback'));
    }

    public function get_auth_url()
    {
        if (!$this->client_id || !$this->client_secret) {
            return new WP_Error('no_credentials', 'Google OAuth credentials not configured.');
        }

        $scopes = implode(' ', array(
            'https://www.googleapis.com/auth/drive.file',
            'https://www.googleapis.com/auth/drive.metadata.readonly',
        ));

        $state = wp_generate_password(48, false, false);
        set_transient($this->oauth_state_key, $state, 10 * MINUTE_IN_SECONDS);

        $params = array(
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect_uri,
            'response_type' => 'code',
            'scope' => $scopes,
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => $state,
        );

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params);
    }

    public function handle_oauth_callback()
    {
        if (!isset($_GET['oauth_callback'])) {
            return;
        }

        if (!current_user_can('manage_options')) {
            wp_die('Access denied.');
        }

        if (!isset($_GET['code']) || !isset($_GET['state'])) {
            wp_die('OAuth error: Invalid callback payload.');
        }

        $received_state = sanitize_text_field(wp_unslash($_GET['state']));
        $expected_state = get_transient($this->oauth_state_key);
        delete_transient($this->oauth_state_key);

        if (!$expected_state || !hash_equals($expected_state, $received_state)) {
            wp_die('OAuth error: Invalid state.');
        }

        $code = sanitize_text_field(wp_unslash($_GET['code']));

        $token_response = $this->exchange_code_for_token($code);

        if (is_wp_error($token_response)) {
            wp_die('OAuth error: ' . $token_response->get_error_message());
        }

        if (!isset($token_response['access_token'])) {
            wp_die('OAuth error: Invalid token response.');
        }

        $token_response['created_at'] = time();
        update_option($this->option_key, $token_response, false);

        wp_safe_redirect(admin_url('admin.php?page=wp-gd-backup&oauth_success=1'));
        exit;
    }

    private function exchange_code_for_token($code)
    {
        $url = 'https://oauth2.googleapis.com/token';

        $body = array(
            'code' => $code,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'grant_type' => 'authorization_code',
        );

        $response = wp_remote_post($url, array(
            'body' => $body,
            'timeout' => 30,
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($body['error'])) {
            $error_message = isset($body['error_description']) ? $body['error_description'] : $body['error'];
            return new WP_Error('oauth_error', $error_message);
        }

        return $body;
    }

    public function get_access_token()
    {
        $token_data = get_option($this->option_key);

        if (!$token_data) {
            return new WP_Error('no_token', 'No OAuth token found.');
        }

        if (empty($token_data['access_token'])) {
            return new WP_Error('no_access_token', 'Invalid token data.');
        }

        if (!isset($token_data['created_at'])) {
            $token_data['created_at'] = time();
            update_option($this->option_key, $token_data, false);
        }

        // Check if token is expired with a small safety buffer
        if (isset($token_data['expires_in']) && isset($token_data['created_at'])) {
            $expiration_time = (int) $token_data['created_at'] + (int) $token_data['expires_in'] - 60;
            if (time() > $expiration_time) {
                if (empty($token_data['refresh_token'])) {
                    return new WP_Error('no_refresh_token', 'Refresh token missing. Reconnect Google Drive.');
                }

                $token_data = $this->refresh_token($token_data['refresh_token']);
                if (is_wp_error($token_data)) {
                    return $token_data;
                }
            }
        }

        return $token_data['access_token'];
    }

    private function refresh_token($refresh_token)
    {
        $url = 'https://oauth2.googleapis.com/token';

        $body = array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'refresh_token' => $refresh_token,
            'grant_type' => 'refresh_token',
        );

        $response = wp_remote_post($url, array(
            'body' => $body,
            'timeout' => 30,
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($body['error'])) {
            $error_message = is_array($body['error']) && isset($body['error']['message'])
                ? $body['error']['message']
                : (is_string($body['error']) ? $body['error'] : 'Token refresh failed.');
            return new WP_Error('oauth_refresh_error', $error_message);
        }

        if (isset($body['access_token'])) {
            $existing = get_option($this->option_key, array());
            if (!empty($existing['refresh_token']) && empty($body['refresh_token'])) {
                $body['refresh_token'] = $existing['refresh_token'];
            }

            $body['created_at'] = time();
            update_option($this->option_key, $body, false);
            return $body;
        }

        return new WP_Error('oauth_refresh_error', 'Token refresh returned no access token.');
    }

    public function is_authenticated()
    {
        $token_data = get_option($this->option_key);
        return !empty($token_data['access_token']);
    }

    public function disconnect()
    {
        delete_option($this->option_key);
    }
}
