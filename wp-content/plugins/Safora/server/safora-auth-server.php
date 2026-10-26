<?php
/**
 * Safora Central OAuth 2.0 Authentication Proxy Server
 *
 * Deploy this single PHP file on your server (e.g., at https://auth.yourdomain.com/googledrive/index.php
 * or https://yourdomain.com/auth/googledrive.php).
 *
 * It securely manages your master Google OAuth credentials and enables 1-click Google Drive
 * authentication for all Safora WordPress plugin users with 100% Safora branding.
 *
 * @package Safora
 */

// ==========================================
// 1. CONFIGURATION (Fill with your Google Cloud credentials)
// ==========================================
define('SAFORA_CLIENT_ID', getenv('SAFORA_CLIENT_ID') ?: 'YOUR_GOOGLE_CLIENT_ID_HERE');
define('SAFORA_CLIENT_SECRET', getenv('SAFORA_CLIENT_SECRET') ?: 'YOUR_GOOGLE_CLIENT_SECRET_HERE');

// The full public URL where this script is hosted on your domain:
// Example: 'https://auth.safora.io/googledrive/' or 'https://yourdomain.com/auth/googledrive.php'
define('SAFORA_AUTH_CALLBACK_URL', 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME']);

// Allowed CORS / Security headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	http_response_code(200);
	exit;
}

// ==========================================
// 2. HANDLE TOKEN REFRESH REQUESTS (POST)
// Called automatically by WordPress sites during scheduled backups when access_token expires
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$code = isset($_POST['code']) ? $_POST['code'] : '';
	$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';

	if (empty($user_id)) {
		http_response_code(400);
		echo json_encode(array('result' => 'error', 'error_description' => 'Missing user_id / refresh_token'));
		exit;
	}

	// Decrypt/decode the refresh token passed in user_id
	$refresh_token = base64_decode($user_id);
	if (false === $refresh_token || strlen($refresh_token) < 10) {
		$refresh_token = $user_id;
	}

	// Request fresh access token directly from Google
	$ch = curl_init('https://oauth2.googleapis.com/token');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
		'client_id'     => SAFORA_CLIENT_ID,
		'client_secret' => SAFORA_CLIENT_SECRET,
		'refresh_token' => $refresh_token,
		'grant_type'    => 'refresh_token',
	)));
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	$response = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

	if ($http_code === 200) {
		$data = json_decode($response, true);
		if (!empty($data['access_token'])) {
			header('Content-Type: application/json');
			// Return encoded token payload matching WordPress driver format
			echo json_encode(array(
				base64_encode(json_encode(array(
					'access_token' => $data['access_token'],
					'expires_in'   => isset($data['expires_in']) ? (int) $data['expires_in'] : 3600,
				)))
			));
			exit;
		}
	}

	http_response_code(400);
	echo json_encode(array('result' => 'error', 'error_description' => 'Failed to refresh token with Google'));
	exit;
}

// ==========================================
// 3. HANDLE GOOGLE OAUTH CALLBACK REDIRECT (GET)
// Called when the user clicks "Allow" on Google's consent screen
// ==========================================
$auth_code = isset($_GET['code']) ? $_GET['code'] : '';
$state = isset($_GET['state']) ? $_GET['state'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Handle cancellation or permission denied
if (!empty($error)) {
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title>Safora Authentication</title>
		<style>
			body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #0f172a; color: #f8fafc; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
			.card { background: #1e293b; padding: 32px 40px; border-radius: 12px; max-width: 480px; text-align: center; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.5); border: 1px solid #334155; }
			h2 { color: #f87171; margin-top: 0; }
			p { color: #94a3b8; font-size: 14px; line-height: 1.6; }
			.btn { display: inline-block; margin-top: 15px; padding: 10px 24px; background: #3b82f6; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 14px; }
		</style>
	</head>
	<body>
		<div class="card">
			<h2>Authentication Cancelled</h2>
			<p>Google returned an error: <strong><?php echo htmlspecialchars($error); ?></strong></p>
			<p>You can close this window and try again from your WordPress dashboard.</p>
		</div>
	</body>
	</html>
	<?php
	exit;
}

if (empty($auth_code) || empty($state)) {
	header('Content-Type: application/json');
	echo json_encode(array(
		'status' => 'online',
		'service' => 'Safora Google Drive OAuth Proxy Server',
		'version' => '1.0.0'
	));
	exit;
}

// Exchange the single-use authorization code with Google for tokens
$ch = curl_init('https://oauth2.googleapis.com/token');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
	'code'          => $auth_code,
	'client_id'     => SAFORA_CLIENT_ID,
	'client_secret' => SAFORA_CLIENT_SECRET,
	'redirect_uri'  => SAFORA_AUTH_CALLBACK_URL,
	'grant_type'    => 'authorization_code',
)));
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code !== 200) {
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<title>Safora Authentication Error</title>
		<style>
			body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #0f172a; color: #f8fafc; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
			.card { background: #1e293b; padding: 32px 40px; border-radius: 12px; max-width: 480px; text-align: center; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.5); border: 1px solid #334155; }
			h2 { color: #ef4444; margin-top: 0; }
			p { color: #94a3b8; font-size: 14px; line-height: 1.6; }
		</style>
	</head>
	<body>
		<div class="card">
			<h2>Token Exchange Failed</h2>
			<p>Could not exchange authorization code with Google. Please verify that SAFORA_CLIENT_SECRET and SAFORA_CLIENT_ID are correct.</p>
		</div>
	</body>
	</html>
	<?php
	exit;
}

$token_data = json_decode($response, true);
$access_token = isset($token_data['access_token']) ? $token_data['access_token'] : '';
$refresh_token = isset($token_data['refresh_token']) ? $token_data['refresh_token'] : '';

// Parse the destination WordPress return URL from state parameter
// State format: 'token:0:https://user-site.com/wp-admin/options-general.php?page=safora&tab=settings&safora_action=googledrive_auth'
$parts = explode(':', $state);
if (count($parts) >= 3) {
	// Extract the full URL after 'token:instance_id:'
	$return_url = implode(':', array_slice($parts, 2));
} else {
	$return_url = $state;
}

// Ensure the return URL is clean
$parsed_url = parse_url($return_url);
if (empty($parsed_url['host'])) {
	die('Invalid return URL in state parameter.');
}

// Build the final redirect URL back to the user's WordPress website
$query_args = array(
	'state'        => 'success:0',
	'user_id'      => base64_encode(!empty($refresh_token) ? $refresh_token : $access_token),
	'access_token' => base64_encode($access_token),
);

// Append query parameters to destination URL
$delimiter = (strpos($return_url, '?') !== false) ? '&' : '?';
$final_destination = $return_url . $delimiter . http_build_query($query_args);

// Redirect the user back to their WordPress dashboard
header('Location: ' . $final_destination, true, 302);
exit;
