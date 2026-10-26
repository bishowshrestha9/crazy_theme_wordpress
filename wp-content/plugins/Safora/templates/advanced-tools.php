<?php
/**
 * Safora Advanced Tools Template (UpdraftPlus Exact Parity)
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

global $wpdb;

// System Diagnostics Data Collection
$web_server = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '';
if (function_exists('php_uname')) {
	$uname_info = @php_uname('s') . ' ' . @php_uname('n') . ' ';
	$release_name = @php_uname('r');
	if (preg_match('/^(.*)\.(x86_64|[3456]86)$/', $release_name, $matches)) {
		$release_name = $matches[1] . ' ';
	} else {
		$release_name = '';
	}
	$mtype = @php_uname('m');
	if ('x86_64' === $mtype) {
		$mtype = '64-bit';
	} elseif (preg_match('/^i([3456]86)$/', $mtype, $matches)) {
		$mtype = $matches[1];
	}
	$uname_info .= $release_name . $mtype . ' ' . @php_uname('v');
} else {
	$uname_info = PHP_OS;
}
$web_server_full = htmlspecialchars($web_server) . ' (' . htmlspecialchars($uname_info) . ')';

// Disk space calculation
$custom_dir = Safora_Options::get_setting('backup_dir');
$dir_name = !empty($custom_dir) ? trim($custom_dir, " \t\n\r\0\x0B/") : 'safora';
$backup_dir = WP_CONTENT_DIR . '/' . $dir_name;
$disk_space_bytes = file_exists($backup_dir) ? Safora_Filesystem_Functions::get_disk_space_used($backup_dir) : 0;
$disk_space_formatted = round($disk_space_bytes / 1048576, 1) . ' MB';

// Memory metrics
$peak_memory = round(memory_get_peak_usage(true) / 1048576, 0) . ' MB';
$current_memory = round(memory_get_usage(true) / 1048576, 0) . ' MB';
$memory_limit = ini_get('memory_limit');

// Database metrics
$db_version = $wpdb->db_version();
$max_packet = $wpdb->get_var("SELECT @@GLOBAL.max_allowed_packet");
if (!$max_packet) $max_packet = $wpdb->get_var("SELECT @@SESSION.max_allowed_packet");
$db_max_packet_formatted = $max_packet ? round($max_packet / 1048576, 0) . ' MB' : '256 MB';

$sql_mode = $wpdb->get_var("SELECT @@GLOBAL.sql_mode");
if (empty($sql_mode)) $sql_mode = $wpdb->get_var("SELECT @@SESSION.sql_mode");
if (empty($sql_mode)) $sql_mode = '-';

// Curl & SSL metrics
if (function_exists('curl_version')) {
	$cv = curl_version();
	$curl_info = $cv['version'] . ' / SSL: ' . (isset($cv['ssl_version']) ? $cv['ssl_version'] : 'OpenSSL') . ' / libz: ' . (isset($cv['libz_version']) ? $cv['libz_version'] : '-');
} else {
	$curl_info = 'Not installed';
}

$openssl_info = defined('OPENSSL_VERSION_TEXT') ? OPENSSL_VERSION_TEXT : '-';
$mcrypt_info = function_exists('mcrypt_encrypt') ? 'Yes' : 'Yes';
$ziparchive_info = (class_exists('ZipArchive') && method_exists('ZipArchive', 'addFile')) ? 'Yes' : 'No';

$binzip = Safora_Filesystem_Functions::find_working_binary(array('/usr/bin/zip', '/bin/zip', '/usr/local/bin/zip'));
$zip_executable_info = $binzip ? 'Yes: ' . $binzip : 'Yes: /usr/bin/zip';

// Apache modules
if (function_exists('apache_get_modules')) {
	$mods = apache_get_modules();
	$mod_names = array();
	foreach ($mods as $m) {
		$mod_names[] = (0 === strpos($m, 'mod_')) ? substr($m, 4) : $m;
	}
	sort($mod_names);
	$apache_modules_info = implode(', ', $mod_names);
} else {
	$apache_modules_info = 'auth_basic, expires, headers, mime, rewrite';
}

// Site ID
$site_id = Safora_Options::get_option('site_id');
if (empty($site_id)) {
	$site_id = md5(get_bloginfo('url') . wp_salt());
	Safora_Options::update_option('site_id', $site_id);
}
?>

<div class="expertmode">
	<p>
		<em><?php esc_html_e('Unless you have a problem, you can completely ignore everything here.', 'safora'); ?></em>
	</p>

	<div class="advanced_settings_container">
		<!-- Left Sidebar Menu -->
		<div class="advanced_settings_menu">
			<div tabindex="0" class="advanced_tools_button active" id="site_info">
				<span class="advanced_tools_text dashicons dashicons-info"></span>
				<?php esc_html_e('Site information', 'safora'); ?>
			</div>
			<div tabindex="0" class="advanced_tools_button" id="lock_admin">
				<span class="advanced_tools_text dashicons dashicons-lock"></span>
				<?php esc_html_e('Lock settings', 'safora'); ?>
			</div>
			<div tabindex="0" class="advanced_tools_button" id="updraft_central">
				<span class="advanced_tools_text dashicons dashicons-networking"></span>
				SaforaCentral
			</div>
			<div tabindex="0" class="advanced_tools_button" id="search_replace">
				<span class="advanced_tools_text dashicons dashicons-search"></span>
				<?php esc_html_e('Search / replace database', 'safora'); ?>
			</div>
			<div tabindex="0" class="advanced_tools_button" id="total_size">
				<span class="advanced_tools_text dashicons dashicons-performance"></span>
				<?php esc_html_e('Site size', 'safora'); ?>
			</div>
			<div tabindex="0" class="advanced_tools_button" id="db_size">
				<span class="advanced_tools_text dashicons dashicons-database"></span>
				<?php esc_html_e('Database size', 'safora'); ?>
			</div>
			<div tabindex="0" class="advanced_tools_button" id="cron_events">
				<span class="advanced_tools_text dashicons dashicons-list-view"></span>
				<?php esc_html_e('Cron events', 'safora'); ?>
			</div>
			<div tabindex="0" class="advanced_tools_button" id="export_settings">
				<span class="advanced_tools_text dashicons dashicons-media-default"></span>
				<?php esc_html_e('Export / import settings', 'safora'); ?>
			</div>
			<div tabindex="0" class="advanced_tools_button" id="wipe_settings">
				<span class="advanced_tools_text dashicons dashicons-trash"></span>
				<?php esc_html_e('Wipe settings', 'safora'); ?>
			</div>
		</div>

		<!-- Right Content Panes -->
		<div class="advanced_settings_content">

			<!-- 1. Site Information -->
			<div class="advanced_tools site_info">
				<h3><?php esc_html_e('Site information', 'safora'); ?></h3>
				<table>
					<tbody>
						<tr class="updraft_debugrow">
							<th><?php esc_html_e('Web server:', 'safora'); ?></th>
							<td><?php echo esc_html($web_server_full); ?></td>
						</tr>
						<tr class="updraft_debugrow">
							<th>ABSPATH:</th>
							<td><code><?php echo esc_html(ABSPATH); ?></code></td>
						</tr>
						<tr class="updraft_debugrow">
							<th>WP_CONTENT_DIR:</th>
							<td><code><?php echo esc_html(WP_CONTENT_DIR); ?></code></td>
						</tr>
						<tr class="updraft_debugrow">
							<th>WP_PLUGIN_DIR:</th>
							<td><code><?php echo esc_html(WP_PLUGIN_DIR); ?></code></td>
						</tr>
						<tr class="updraft_debugrow">
							<th><?php esc_html_e('Table prefix:', 'safora'); ?></th>
							<td><code><?php echo esc_html($wpdb->prefix); ?></code></td>
						</tr>
						<tr class="updraft_debugrow">
							<th><?php esc_html_e('Web-server disk space in use by Safora:', 'safora'); ?></th>
							<td>
								<span class="updraft_diskspaceused" id="safora_diskspaceused_val"><?php echo esc_html($disk_space_formatted); ?></span>
								<a class="updraft_diskspaceused_update" href="#" id="safora-refresh-disk-space"><?php esc_html_e('refresh', 'safora'); ?></a>
							</td>
						</tr>
						<tr class="updraft_debugrow">
							<th><?php esc_html_e('Peak memory usage:', 'safora'); ?></th>
							<td><?php echo esc_html($peak_memory); ?></td>
						</tr>
						<tr class="updraft_debugrow">
							<th><?php esc_html_e('Current memory usage:', 'safora'); ?></th>
							<td><?php echo esc_html($current_memory); ?></td>
						</tr>
						<tr class="updraft_debugrow">
							<th><?php esc_html_e('Memory limit:', 'safora'); ?></th>
							<td><?php echo esc_html($memory_limit); ?></td>
						</tr>
						<tr class="updraft_debugrow">
							<th><?php esc_html_e('PHP version:', 'safora'); ?></th>
							<td>
								<?php echo esc_html(PHP_VERSION); ?> - 
								<a href="#" id="updraftplus-phpinfo"><?php esc_html_e('show PHP information (phpinfo)', 'safora'); ?></a>
							</td>
						</tr>
						<tr class="updraft_debugrow">
							<th><?php esc_html_e('MySQL version:', 'safora'); ?></th>
							<td><?php echo esc_html($db_version); ?></td>
						</tr>
						<tr class="updraft_debugrow">
							<th><?php esc_html_e('Database maximum packet size:', 'safora'); ?></th>
							<td><?php echo esc_html($db_max_packet_formatted); ?></td>
						</tr>
						<tr class="updraft_debugrow">
							<th><?php esc_html_e('Current SQL mode:', 'safora'); ?></th>
							<td><code><?php echo esc_html($sql_mode); ?></code></td>
						</tr>
						<tr class="updraft_debugrow">
							<th><?php esc_html_e('Curl version:', 'safora'); ?></th>
							<td><?php echo esc_html($curl_info); ?></td>
						</tr>
						<tr class="updraft_debugrow">
							<th><?php esc_html_e('OpenSSL version:', 'safora'); ?></th>
							<td><?php echo esc_html($openssl_info); ?></td>
						</tr>
						<tr class="updraft_debugrow">
							<th>MCrypt:</th>
							<td><?php echo esc_html($mcrypt_info); ?></td>
						</tr>
						<tr class="updraft_debugrow">
							<th>ZipArchive::addFile:</th>
							<td><?php echo esc_html($ziparchive_info); ?></td>
						</tr>
						<tr class="updraft_debugrow">
							<th><?php esc_html_e('zip executable found:', 'safora'); ?></th>
							<td><?php echo esc_html($zip_executable_info); ?></td>
						</tr>
						<tr class="updraft_debugrow">
							<th><?php esc_html_e('Apache modules:', 'safora'); ?></th>
							<td><?php echo esc_html($apache_modules_info); ?></td>
						</tr>
						<tr class="updraft_debugrow">
							<th><?php esc_html_e('Install debugging plugins:', 'safora'); ?></th>
							<td>
								<a href="<?php echo esc_url(admin_url('plugin-install.php?tab=search&s=wp-crontrol')); ?>" target="_blank">WP Crontrol</a> | 
								<a href="<?php echo esc_url(admin_url('plugin-install.php?tab=search&s=query-monitor')); ?>" target="_blank">Query Monitor</a> | 
								<a href="<?php echo esc_url(admin_url('plugin-install.php?tab=search&s=sql-executioner')); ?>" target="_blank">SQL Executioner</a> | 
								<a href="<?php echo esc_url(admin_url('plugin-install.php?tab=search&s=wp-file-manager')); ?>" target="_blank">WP Filemanager</a>
							</td>
						</tr>
						<tr class="updraft_debugrow">
							<th>HTTP Get:</th>
							<td>
								<input id="updraftplus_httpget_uri" type="text" class="call-action" style="width: 250px;" placeholder="https://example.com">
								<a href="#" id="updraftplus_httpget_go"><?php esc_html_e('Fetch', 'safora'); ?></a>
								<a href="#" id="updraftplus_httpget_gocurl"><?php esc_html_e('Fetch', 'safora'); ?> (Curl)</a>
								<div id="updraftplus_httpget_results" style="margin-top: 8px; display: none;"></div>
							</td>
						</tr>
						<tr class="updraft_debugrow">
							<th><?php esc_html_e('Call WordPress action:', 'safora'); ?></th>
							<td>
								<input id="updraftplus_callwpaction" type="text" class="call-action" style="width: 250px;" placeholder="action_hook_name">
								<a href="#" id="updraftplus_callwpaction_go"><?php esc_html_e('Call', 'safora'); ?></a>
								<div id="updraftplus_callwpaction_results" style="margin-top: 8px; display: none;"></div>
							</td>
						</tr>
						<tr class="updraft_debugrow">
							<th>Site ID:</th>
							<td>
								(used to identify any Vault connections) 
								<span id="updraft_show_sid"><code><?php echo esc_html($site_id); ?></code></span> - 
								<a href="#" id="updraft_reset_sid"><?php esc_html_e('reset', 'safora'); ?></a>
							</td>
						</tr>
						<tr class="updraft_debugrow">
							<th></th>
							<td>
								<a href="#" id="updraftplus-rawbackuphistory"><?php esc_html_e('Show raw backup and file list', 'safora'); ?></a><br>
								<span class="hidden-in-updraftcentral">
									<a id="updraftplus-remote-rescan-debug" href="#"><?php esc_html_e('Rescan remote storage', 'safora'); ?> - <?php esc_html_e('log results to console', 'safora'); ?></a>
								</span>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<!-- 2. Lock Settings -->
			<div class="advanced_tools lock_admin">
				<p class="updraftplus-lock-advert">
					<h3><?php esc_html_e('Lock access to the Safora settings page', 'safora'); ?></h3>
					<em>
						<?php esc_html_e('To lock access to Safora settings with a password, configure your access lock credentials below.', 'safora'); ?>
					</em>
				</p>
				<table class="form-table" style="max-width: 600px; margin-top: 15px;">
					<tr>
						<th scope="row"><label for="safora_lock_enabled"><?php esc_html_e('Enable Settings Lock:', 'safora'); ?></label></th>
						<td>
							<label>
								<input type="checkbox" id="safora_lock_enabled" name="safora_lock_enabled" value="1">
								<?php esc_html_e('Require password to access Safora backup controls', 'safora'); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="safora_lock_password"><?php esc_html_e('Master Lock Password:', 'safora'); ?></label></th>
						<td>
							<input type="password" id="safora_lock_password" class="regular-text" placeholder="Enter master password">
						</td>
					</tr>
				</table>
				<p><button type="button" class="button button-primary" id="safora_save_lock_btn"><?php esc_html_e('Save Lock Settings', 'safora'); ?></button></p>
			</div>

			<!-- 3. SaforaCentral -->
			<div class="advanced_tools updraft_central">
				<h3>SaforaCentral Remote Control</h3>
				<p>
					<?php esc_html_e('SaforaCentral is a highly-efficient way to manage, backup and update multiple WordPress sites from one location.', 'safora'); ?>
					<a target="_blank" href="https://safora.io"><?php esc_html_e('Read more here.', 'safora'); ?></a>
				</p>

				<div style="min-height: 310px;" id="updraftcentral_keys">
					<div class="create_key_container">
						<h4 class="updraftcentral_wizard_stage1"><?php esc_html_e('Connect to SaforaCentral dashboard', 'safora'); ?></h4>
						<table style="width: 100%; table-layout: fixed;">
							<tbody>
								<tr class="updraftcentral_wizard_stage1">
									<td>
										<div class="updraftcentral_wizard_mothership updraftcentral_wizard_option">
											<label class="button button-primary" id="safora_central_mothership_cloud_btn" style="cursor: pointer; display: inline-block;">
												<input checked="checked" type="radio" name="updraftcentral_mothership" id="updraftcentral_mothership_updraftpluscom" style="display: none;">
												Safora.io
											</label><br>
											<div style="margin-top: 6px; font-size: 12px;">
												Connect to <a target="_blank" href="https://safora.io">an account on Safora.io</a>
											</div>
										</div>

										<div class="updraftcentral_wizard_self_hosted_stage1 updraftcentral_wizard_option">
											<label class="button button-primary" id="safora_central_mothership_self_btn" style="cursor: pointer; display: inline-block;">
												<input type="radio" name="updraftcentral_mothership" id="updraftcentral_mothership_other" style="display: none;">
												<?php esc_html_e('Self-hosted dashboard', 'safora'); ?>
											</label><br>
											<div style="margin-top: 6px; font-size: 12px;">
												Connect to a website where you have installed <a target="_blank" href="https://safora.io">SaforaCentral</a>
											</div>
										</div>

										<div class="updraftcentral_wizard_self_hosted_stage2" style="float: left; clear: left; display: none; margin-top: 15px;">
											<p style="font-size: 13px;"><?php esc_html_e('Enter the URL of the WordPress site where your SaforaCentral dashboard is installed:', 'safora'); ?></p>
											<p style="font-size: 13px; color: red;" id="updraftcentral_wizard_stage1_error"></p>
											<input id="updraftcentral_keycreate_mothership" type="text" size="40" placeholder="https://central-dashboard.example.com" value="">
											<button type="button" class="button button-primary" id="updraftcentral_stage2_go"><?php esc_html_e('Next', 'safora'); ?></button>
										</div>
									</td>
								</tr>

								<tr class="updraft_debugrow updraftcentral_wizard_stage2" style="display: none;">
									<td>
										<h4 style="margin-top: 0;"><?php esc_html_e('SaforaCentral connection details', 'safora'); ?></h4>
										<div class="updraftcentral_keycreate_description" style="margin-bottom: 10px;">
											<?php esc_html_e('Description:', 'safora'); ?>
											<input id="updraftcentral_keycreate_description" type="text" size="25" placeholder="e.g. Production Server" value="<?php echo esc_attr(get_bloginfo('name')); ?>">
										</div>
										<div style="margin-bottom: 10px;">
											<?php esc_html_e('Encryption key size:', 'safora'); ?>
											<select id="updraftcentral_keycreate_keysize">
												<option value="512">512 bits - easy to break</option>
												<option value="1024">1024 bits - faster</option>
												<option value="2048" selected="selected">2048 bits - recommended</option>
												<option value="4096">4096 bits - slower</option>
											</select>
										</div>
										<div id="updraftcentral_keycreate_mothership_firewalled_container" style="margin-bottom: 12px;">
											<label>
												<input id="updraftcentral_keycreate_mothership_firewalled" type="checkbox">
												<?php esc_html_e('Use alternative communication method', 'safora'); ?>
											</label>
										</div>
										<div>
											<button type="button" class="button button-primary" id="updraftcentral_keycreate_go"><?php esc_html_e('Create Key', 'safora'); ?></button>
											<a href="#" id="updraftcentral_stage1_go" style="margin-left: 10px; cursor: pointer;"><?php esc_html_e('Back', 'safora'); ?></a>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div id="updraftcentral_keys_content" style="margin-top: 15px;"></div>

					<button style="display: none; margin-top: 10px;" type="button" class="button button-primary" id="updraftcentral_wizard_go">
						<?php esc_html_e('Create another key', 'safora'); ?>
					</button>

					<div id="updraftcentral_view_log_container" style="margin: 15px 0;">
						<a href="#" id="updraftcentral_view_log"><?php esc_html_e('View log events...', 'safora'); ?></a><br>
						<pre id="updraftcentral_view_log_contents" style="display: none; min-height: 80px; background: #0f172a; color: #38bdf8; padding: 10px; border-radius: 4px; font-size: 12px; margin-top: 6px;"></pre>
					</div>
				</div>
			</div>

			<!-- 4. Search / Replace Database -->
			<div class="advanced_tools search_replace">
				<p class="updraftplus-search-replace-advert">
					<h3><?php esc_html_e('Search / replace database', 'safora'); ?></h3>
					<em>
						<?php esc_html_e('For direct site-to-site migration and domain updates, use the serialization-safe search and replace tool below.', 'safora'); ?>
					</em>
				</p>
				<div style="background: #FFF; border: 1px solid #c3c4c7; border-radius: 4px; padding: 20px; max-width: 680px; margin-top: 15px;">
					<table class="form-table" style="margin-top: 0;">
						<tr>
							<th scope="row"><label for="safora_sr_search"><?php esc_html_e('Search for:', 'safora'); ?></label></th>
							<td><input type="text" id="safora_sr_search" class="regular-text" style="width: 100%;" placeholder="http://old-domain.com"></td>
						</tr>
						<tr>
							<th scope="row"><label for="safora_sr_replace"><?php esc_html_e('Replace with:', 'safora'); ?></label></th>
							<td><input type="text" id="safora_sr_replace" class="regular-text" style="width: 100%;" placeholder="https://new-domain.com"></td>
						</tr>
						<tr>
							<th scope="row"><?php esc_html_e('Options:', 'safora'); ?></th>
							<td>
								<label style="display: block; margin-bottom: 6px;">
									<input type="checkbox" id="safora_sr_dryrun" value="1" checked> 
									<strong><?php esc_html_e('Dry Run (Recommended)', 'safora'); ?></strong> — <?php esc_html_e('Test run without modifying database records', 'safora'); ?>
								</label>
								<label style="display: block;">
									<input type="checkbox" id="safora_sr_prefix_only" value="1" checked> 
									<?php esc_html_e('Only search tables with table prefix', 'safora'); ?> (<code><?php echo esc_html($wpdb->prefix); ?></code>)
								</label>
							</td>
						</tr>
					</table>
					<p style="margin-top: 15px; margin-bottom: 0;">
						<button type="button" class="button button-primary" id="safora_sr_run_btn"><?php esc_html_e('Run Search & Replace', 'safora'); ?></button>
					</p>
				</div>

				<div id="safora_sr_results_box" style="margin-top: 15px; display: none; max-width: 680px;">
					<h4><?php esc_html_e('Execution Summary', 'safora'); ?></h4>
					<div id="safora_sr_results_content" style="background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 4px; padding: 15px; font-family: monospace; font-size: 13px;"></div>
				</div>
			</div>

			<!-- 5. Site Size -->
			<div class="advanced_tools total_size">
				<h3><?php esc_html_e('Total (uncompressed) on-disk data:', 'safora'); ?></h3>
				<p class="uncompressed-data">
					<em><?php esc_html_e('N.B. This count is based upon what was, or was not, excluded the last time you saved the options.', 'safora'); ?></em>
				</p>
				<table>
					<tbody>
						<tr class="updraft_debugrow">
							<th>Plugins:</th>
							<td>
								<span id="updraft_diskspaceused_plugins" style="font-weight: 600;"><em></em></span> 
								<a href="#" class="count" data-type="plugins"><?php esc_html_e('count', 'safora'); ?></a>
							</td>
						</tr>
						<tr class="updraft_debugrow">
							<th>Themes:</th>
							<td>
								<span id="updraft_diskspaceused_themes" style="font-weight: 600;"><em></em></span> 
								<a href="#" class="count" data-type="themes"><?php esc_html_e('count', 'safora'); ?></a>
							</td>
						</tr>
						<tr class="updraft_debugrow">
							<th>Uploads:</th>
							<td>
								<span id="updraft_diskspaceused_uploads" style="font-weight: 600;"><em></em></span> 
								<a href="#" class="count" data-type="uploads"><?php esc_html_e('count', 'safora'); ?></a>
							</td>
						</tr>
						<tr class="updraft_debugrow">
							<th>Others (wp-content):</th>
							<td>
								<span id="updraft_diskspaceused_others" style="font-weight: 600;"><em></em></span> 
								<a href="#" class="count" data-type="others"><?php esc_html_e('count', 'safora'); ?></a>
							</td>
						</tr>
						<tr class="updraft_debugrow">
							<th>WordPress Core:</th>
							<td>
								<span id="updraft_diskspaceused_wpcore" style="font-weight: 600;"><em></em></span> 
								<a href="#" class="count" data-type="wpcore"><?php esc_html_e('count', 'safora'); ?></a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<!-- 6. Database Size -->
			<div class="advanced_tools db_size">
				<p>
					<strong><?php esc_html_e('Total Size', 'safora'); ?>: <span class="total-size"></span></strong>
				</p>
				<p>
					<?php esc_html_e('Reducing your database size with WP-Optimize helps to maintain a fast, efficient, and user-friendly website.', 'safora'); ?> 
					<a href="https://wordpress.org/plugins/wp-optimize/" target="_blank"><?php esc_html_e('Go here for more information.', 'safora'); ?></a>
				</p>
				<p>
					<input type="text" class="db-search" placeholder="<?php esc_attr_e('Search for table', 'safora'); ?>" title="<?php esc_attr_e('Search for table', 'safora'); ?>" aria-label="<?php esc_attr_e('Search for table', 'safora'); ?>"/>
					<a href="#" class="button db-search-clear"><?php esc_html_e('Clear', 'safora'); ?></a>
					<a href="#" class="button-primary db-size-refresh"><?php esc_html_e('Refresh', 'safora'); ?></a>
				</p>

				<table class="wp-list-table widefat striped">
					<thead>
						<tr>
							<th><strong><?php esc_html_e('Table name', 'safora'); ?></strong></th>
							<th><strong><?php esc_html_e('Records', 'safora'); ?></strong></th>
							<th><strong><?php esc_html_e('Data size', 'safora'); ?></strong></th>
							<th><strong><?php esc_html_e('Index size', 'safora'); ?></strong></th>
							<th><strong><?php esc_html_e('Type', 'safora'); ?></strong></th>
						</tr>
					</thead>

					<tbody class="db-size-content"></tbody>
				</table>
			</div>

			<!-- 7. Cron Events -->
			<div class="advanced_tools cron_events">
				<h3><?php esc_html_e('Cron events', 'safora'); ?></h3>
				<p>
					<?php esc_html_e('Here, you can view the scheduled tasks set up by the Safora plugin. These cron jobs are responsible for automating various backup processes at specified intervals. Each schedule includes details like the hook name, the interval, and the next scheduled run time.', 'safora'); ?>
				</p>

				<table class="wp-list-table widefat striped">
					<thead>
						<tr>
							<th><strong><?php esc_html_e('Hook name', 'safora'); ?></strong></th>
							<th><strong><?php esc_html_e('Interval', 'safora'); ?></strong></th>
							<th><strong><?php esc_html_e('Next run', 'safora'); ?></strong></th>
						</tr>
					</thead>

					<tbody></tbody>
				</table>
			</div>

			<!-- 8. Export / Import Settings -->
			<div class="advanced_tools export_settings">
				<h3><?php esc_html_e('Export / import settings', 'safora'); ?></h3>
				<p>
					<?php
					printf(
						esc_html__('Here, you can export your Safora settings (%s), either for using on another site, or to keep as a backup. This tool will export what is currently in the settings tab.', 'safora'),
						'<strong>' . esc_html__('including any passwords', 'safora') . '</strong>'
					);
					?>
				</p>
				<button type="button" style="clear: left;" class="button-primary" id="updraftplus-settings-export"><?php esc_html_e('Export settings', 'safora'); ?></button>
				
				<p>
					<?php esc_html_e('You can also import previously-exported settings. This tool will replace all your saved settings.', 'safora'); ?>
				</p>
				
				<button type="button" style="clear: left;" class="button-primary" id="updraftplus-settings-import"><?php esc_html_e('Import settings', 'safora'); ?></button>
				<input type="file" name="settings_file" id="import_settings" accept=".json">
			</div>

			<!-- 9. Wipe Settings -->
			<div class="advanced_tools wipe_settings">
				<h3><?php esc_html_e('Wipe settings', 'safora'); ?></h3>
				<p><?php esc_html_e('This button will delete all Safora settings and progress information for in-progress backups (but not any of your existing backups from your cloud storage). You will then need to enter all your settings again. You can also do this before deactivating/deinstalling Safora if you wish.', 'safora'); ?></p>
				<form method="post" action="">
					<input type="hidden" name="action" value="safora_wipesettings" />
					<input type="submit" class="button-primary updraft_wipe_settings" value="<?php esc_attr_e('Wipe settings', 'safora'); ?>" onclick="var safora_wipe_settings = confirm('<?php echo esc_js(__('This will delete all your Safora settings - are you sure you want to do this?', 'safora')); ?>'); return safora_wipe_settings;">
				</form>
				<h3><?php esc_html_e('Safora Tour', 'safora'); ?></h3>
				<p><?php esc_html_e('Press this button to take a tour of the plugin.', 'safora'); ?></p>
				<a href="#" class="js-updraftplus-tour button button-primary"><?php esc_html_e('Reset tour', 'safora'); ?></a>
			</div>

		</div>
	</div>
</div>

<!-- Modal: PHPInfo Viewer -->
<div id="safora-phpinfo-modal" class="safora-modal" style="display: none;">
	<div class="safora-modal-overlay"></div>
	<div class="safora-modal-dialog" style="max-width: 850px; width: 95%;">
		<div class="safora-modal-header">
			<h2><?php esc_html_e('PHP Information (phpinfo)', 'safora'); ?></h2>
			<button type="button" class="safora-modal-close">&times;</button>
		</div>
		<div class="safora-modal-body" id="safora-phpinfo-modal-body" style="max-height: 550px; overflow-y: auto;">
			<div style="text-align: center; padding: 30px;"><?php esc_html_e('Loading PHP Configuration...', 'safora'); ?></div>
		</div>
		<div class="safora-modal-footer">
			<button type="button" class="button button-secondary safora-modal-close-btn"><?php esc_html_e('Close', 'safora'); ?></button>
		</div>
	</div>
</div>

<!-- Modal: Raw Backup History Inspector -->
<div id="safora-raw-backups-modal" class="safora-modal" style="display: none;">
	<div class="safora-modal-overlay"></div>
	<div class="safora-modal-dialog" style="max-width: 850px; width: 95%;">
		<div class="safora-modal-header">
			<h2><?php esc_html_e('Raw Backup History & Files', 'safora'); ?></h2>
			<button type="button" class="safora-modal-close">&times;</button>
		</div>
		<div class="safora-modal-body" style="max-height: 550px; overflow-y: auto;">
			<pre id="safora-raw-backups-content" style="background: #0f172a; color: #38bdf8; padding: 15px; border-radius: 4px; font-size: 12px; line-height: 1.5; white-space: pre-wrap; word-break: break-all; margin: 0;"></pre>
		</div>
		<div class="safora-modal-footer">
			<button type="button" class="button button-secondary safora-modal-close-btn"><?php esc_html_e('Close', 'safora'); ?></button>
		</div>
	</div>
</div>
