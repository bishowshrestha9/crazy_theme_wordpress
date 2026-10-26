<?php
/**
 * Safora Settings Template (UpdraftPlus-Parity UI)
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

global $safora_core;
$modules = $safora_core ? $safora_core->get_storage_modules() : array();
$active_services = (array) Safora_Options::get_setting('service');

// Auto-include authenticated Google Drive if tokens are present
$gd_opts = (array) Safora_Options::get_option('googledrive', array());
if (!empty($gd_opts['access_token']) || !empty($gd_opts['refresh_token']) || !empty($gd_opts['user_id'])) {
	if (!in_array('googledrive', $active_services)) {
		$active_services[] = 'googledrive';
	}
}

$intervals = array(
	'manual'      => 'Manual',
	'every2hours' => 'Every 2 hours',
	'every4hours' => 'Every 4 hours',
	'every8hours' => 'Every 8 hours',
	'twicedaily'  => 'Every 12 hours',
	'daily'       => 'Daily',
	'weekly'      => 'Weekly',
	'fortnightly' => 'Fortnightly',
	'monthly'     => 'Monthly',
);

$curr_files_int = Safora_Options::get_setting('interval');
if (empty($curr_files_int)) $curr_files_int = 'manual';

$curr_db_int = Safora_Options::get_setting('interval_database');
if (empty($curr_db_int)) $curr_db_int = 'manual';

$curr_files_time = Safora_Options::get_setting('starttime_files');
if (empty($curr_files_time)) $curr_files_time = '00:00';

$curr_files_day = (int) Safora_Options::get_setting('startday_files');

$curr_db_time = Safora_Options::get_setting('starttime_db');
if (empty($curr_db_time)) $curr_db_time = '00:00';

$curr_db_day = (int) Safora_Options::get_setting('startday_db');

$retain_files = Safora_Options::get_setting('retain');
if (empty($retain_files)) $retain_files = 2;

$retain_db = Safora_Options::get_setting('retain_db');
if (empty($retain_db)) $retain_db = 2;

$weekdays = array(
	0 => 'Sunday',
	1 => 'Monday',
	2 => 'Tuesday',
	3 => 'Wednesday',
	4 => 'Thursday',
	5 => 'Friday',
	6 => 'Saturday',
);

$retain_extrarules = (array) Safora_Options::get_option('retain_extrarules', array('files' => array(), 'db' => array()));
$files_rules = !empty($retain_extrarules['files']) && is_array($retain_extrarules['files']) ? $retain_extrarules['files'] : array();
$db_rules = !empty($retain_extrarules['db']) && is_array($retain_extrarules['db']) ? $retain_extrarules['db'] : array();
$incremental_mode = Safora_Options::get_setting('incremental_mode', 'none');

$admin_email = get_option('admin_email');
$report_email = Safora_Options::get_setting('reporting_email');
if (empty($report_email)) {
	$legacy_email = Safora_Options::get_setting('email');
	if (is_array($legacy_email) && !empty($legacy_email['email'])) {
		$report_email = $legacy_email['email'];
	} elseif (is_string($legacy_email) && !empty($legacy_email)) {
		$report_email = $legacy_email;
	} else {
		$report_email = $admin_email;
	}
}
if (is_array($report_email)) {
	$report_email = !empty($report_email['email']) ? $report_email['email'] : $admin_email;
}
?>

<form method="post" action="" class="safora-settings-form">
	<?php wp_nonce_field('safora_save_settings', 'safora_settings_nonce'); ?>

	<div class="safora-settings-grid-panel">

		<!-- 1. Backup Schedules -->
		<div class="safora-settings-row">
			<div class="safora-settings-label">
				<label>Files backup schedule:</label>
			</div>
			<div class="safora-settings-control">
				<div class="safora-schedule-inline">
					<select name="safora_settings[interval]" id="safora_interval">
						<?php foreach ($intervals as $k => $v): ?>
							<option value="<?php echo esc_attr($k); ?>" <?php selected($curr_files_int, $k); ?>><?php echo esc_html($v); ?></option>
						<?php endforeach; ?>
					</select>

					<span id="safora_files_timings" class="safora_files_timings" style="<?php echo ('manual' === $curr_files_int) ? 'display:none;' : 'display:inline-flex; align-items:center; gap:6px;'; ?>">
						<span>starting from next time it is</span>
						<select name="safora_settings[startday_files]" id="safora_startday_files" style="<?php echo ('weekly' === $curr_files_int || 'fortnightly' === $curr_files_int || 'monthly' === $curr_files_int) ? '' : 'display:none;'; ?>">
							<?php if ('monthly' === $curr_files_int): ?>
								<?php for ($d = 1; $d <= 28; $d++): ?>
									<option value="<?php echo $d; ?>" <?php selected($curr_files_day, $d); ?>><?php echo $d; ?></option>
								<?php endfor; ?>
							<?php else: ?>
								<?php foreach ($weekdays as $dnum => $dname): ?>
									<option value="<?php echo $dnum; ?>" <?php selected($curr_files_day, $dnum); ?>><?php echo esc_html($dname); ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
						<input type="text" name="safora_settings[starttime_files]" id="safora_starttime_files" value="<?php echo esc_attr($curr_files_time); ?>" class="fix-time safora-fix-time" style="width: 75px; min-width: 75px; text-align:center; padding:3px 8px; font-size:13px;" placeholder="00:00" maxlength="5">
					</span>

					<span>and retain this many scheduled backups:</span>
					<input type="number" name="safora_settings[retain]" value="<?php echo esc_attr($retain_files); ?>" class="small-text" min="1" max="500" style="width:65px; text-align:center;">
				</div>

				<div class="safora-incremental-row" style="margin-top:6px; font-size:13px; color:#334155; display:flex; align-items:center; gap:8px;">
					<span>And then add an incremental backup</span>
					<select name="safora_settings[incremental_mode]" style="height:28px; font-size:12px;">
						<option value="none" <?php selected($incremental_mode, 'none'); ?>>None</option>
						<option value="hourly" <?php selected($incremental_mode, 'hourly'); ?>>Hourly</option>
						<option value="daily" <?php selected($incremental_mode, 'daily'); ?>>Daily</option>
						<option value="weekly" <?php selected($incremental_mode, 'weekly'); ?>>Weekly</option>
					</select>
					<a href="#" onclick="alert('Incremental backups save only files and changes modified since the last full backup.'); return false;" style="color:#2271b1; text-decoration:underline;">Tell me more</a>
				</div>

				<div id="safora_retain_files_rules" class="safora-retention-rules-list" style="margin-top: 6px;">
					<?php foreach ($files_rules as $idx => $rule): ?>
						<?php
						$after_howmany = isset($rule['after_howmany']) ? absint($rule['after_howmany']) : 12;
						$after_period = isset($rule['after_period']) ? $rule['after_period'] : 'weeks';
						$every_howmany = isset($rule['every_howmany']) ? absint($rule['every_howmany']) : 1;
						$every_period = isset($rule['every_period']) ? $rule['every_period'] : 'weeks';
						?>
						<div class="safora-retention-rule-row" style="display:flex; align-items:center; gap:6px; margin-top:6px; font-size:13px; color:#334155; flex-wrap:wrap;">
							<span>For backups older than</span>
							<input type="number" min="1" step="1" name="safora_retain_extrarules[files][<?php echo $idx; ?>][after_howmany]" value="<?php echo esc_attr($after_howmany); ?>" class="small-text" style="width:60px;">
							<select name="safora_retain_extrarules[files][<?php echo $idx; ?>][after_period]">
								<option value="hours" <?php selected($after_period, 'hours'); ?>>hour(s)</option>
								<option value="days" <?php selected($after_period, 'days'); ?>>day(s)</option>
								<option value="weeks" <?php selected($after_period, 'weeks'); ?>>week(s)</option>
								<option value="fortnights" <?php selected($after_period, 'fortnights'); ?>>fortnight(s)</option>
								<option value="months" <?php selected($after_period, 'months'); ?>>month(s)</option>
								<option value="years" <?php selected($after_period, 'years'); ?>>year(s)</option>
							</select>
							<span>keep no more than 1 backup every</span>
							<input type="number" min="1" step="1" name="safora_retain_extrarules[files][<?php echo $idx; ?>][every_howmany]" value="<?php echo esc_attr($every_howmany); ?>" class="small-text" style="width:60px;">
							<select name="safora_retain_extrarules[files][<?php echo $idx; ?>][every_period]">
								<option value="hours" <?php selected($every_period, 'hours'); ?>>hour(s)</option>
								<option value="days" <?php selected($every_period, 'days'); ?>>day(s)</option>
								<option value="weeks" <?php selected($every_period, 'weeks'); ?>>week(s)</option>
								<option value="fortnights" <?php selected($every_period, 'fortnights'); ?>>fortnight(s)</option>
								<option value="months" <?php selected($every_period, 'months'); ?>>month(s)</option>
								<option value="years" <?php selected($every_period, 'years'); ?>>year(s)</option>
							</select>
							<a href="#" class="safora-remove-retention-rule" style="color:#dc2626; font-size:16px; font-weight:bold; text-decoration:none; margin-left:4px;" title="Remove this rule">&times;</a>
						</div>
					<?php endforeach; ?>
				</div>

				<div style="margin-top: 6px; font-size:13px;">
					<a href="#" id="safora_add_files_retention_rule" style="text-decoration:none; font-weight:600;">+ Add an additional retention rule...</a>
				</div>
			</div>
		</div>

		<div class="safora-settings-row">
			<div class="safora-settings-label">
				<label>Database backup schedule:</label>
			</div>
			<div class="safora-settings-control">
				<div class="safora-schedule-inline">
					<select name="safora_settings[interval_database]" id="safora_interval_db">
						<?php foreach ($intervals as $k => $v): ?>
							<option value="<?php echo esc_attr($k); ?>" <?php selected($curr_db_int, $k); ?>><?php echo esc_html($v); ?></option>
						<?php endforeach; ?>
					</select>

					<span id="safora_db_same_schedule_msg" style="<?php echo ($curr_db_int === $curr_files_int && 'manual' !== $curr_db_int) ? 'color:#64748b;' : 'display:none;'; ?>">(at same time as files backup)</span>

					<span id="safora_db_timings" class="safora_db_timings" style="<?php echo ($curr_db_int !== $curr_files_int && 'manual' !== $curr_db_int) ? 'display:inline-flex; align-items:center; gap:6px;' : 'display:none;'; ?>">
						<span>starting from next time it is</span>
						<select name="safora_settings[startday_db]" id="safora_startday_db" style="<?php echo ('weekly' === $curr_db_int || 'fortnightly' === $curr_db_int || 'monthly' === $curr_db_int) ? '' : 'display:none;'; ?>">
							<?php if ('monthly' === $curr_db_int): ?>
								<?php for ($d = 1; $d <= 28; $d++): ?>
									<option value="<?php echo $d; ?>" <?php selected($curr_db_day, $d); ?>><?php echo $d; ?></option>
								<?php endfor; ?>
							<?php else: ?>
								<?php foreach ($weekdays as $dnum => $dname): ?>
									<option value="<?php echo $dnum; ?>" <?php selected($curr_db_day, $dnum); ?>><?php echo esc_html($dname); ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
						<input type="text" name="safora_settings[starttime_db]" id="safora_starttime_db" value="<?php echo esc_attr($curr_db_time); ?>" class="fix-time safora-fix-time" style="width: 75px; min-width: 75px; text-align:center; padding:3px 8px; font-size:13px;" placeholder="00:00" maxlength="5">
					</span>

					<span>and retain this many scheduled backups:</span>
					<input type="number" name="safora_settings[retain_db]" value="<?php echo esc_attr($retain_db); ?>" class="small-text" min="1" max="500" style="width:65px; text-align:center;">
				</div>

				<div id="safora_retain_db_rules" class="safora-retention-rules-list" style="margin-top: 6px;">
					<?php foreach ($db_rules as $idx => $rule): ?>
						<?php
						$after_howmany = isset($rule['after_howmany']) ? absint($rule['after_howmany']) : 12;
						$after_period = isset($rule['after_period']) ? $rule['after_period'] : 'weeks';
						$every_howmany = isset($rule['every_howmany']) ? absint($rule['every_howmany']) : 1;
						$every_period = isset($rule['every_period']) ? $rule['every_period'] : 'weeks';
						?>
						<div class="safora-retention-rule-row" style="display:flex; align-items:center; gap:6px; margin-top:6px; font-size:13px; color:#334155; flex-wrap:wrap;">
							<span>For backups older than</span>
							<input type="number" min="1" step="1" name="safora_retain_extrarules[db][<?php echo $idx; ?>][after_howmany]" value="<?php echo esc_attr($after_howmany); ?>" class="small-text" style="width:60px;">
							<select name="safora_retain_extrarules[db][<?php echo $idx; ?>][after_period]">
								<option value="hours" <?php selected($after_period, 'hours'); ?>>hour(s)</option>
								<option value="days" <?php selected($after_period, 'days'); ?>>day(s)</option>
								<option value="weeks" <?php selected($after_period, 'weeks'); ?>>week(s)</option>
								<option value="fortnights" <?php selected($after_period, 'fortnights'); ?>>fortnight(s)</option>
								<option value="months" <?php selected($after_period, 'months'); ?>>month(s)</option>
								<option value="years" <?php selected($after_period, 'years'); ?>>year(s)</option>
							</select>
							<span>keep no more than 1 backup every</span>
							<input type="number" min="1" step="1" name="safora_retain_extrarules[db][<?php echo $idx; ?>][every_howmany]" value="<?php echo esc_attr($every_howmany); ?>" class="small-text" style="width:60px;">
							<select name="safora_retain_extrarules[db][<?php echo $idx; ?>][every_period]">
								<option value="hours" <?php selected($every_period, 'hours'); ?>>hour(s)</option>
								<option value="days" <?php selected($every_period, 'days'); ?>>day(s)</option>
								<option value="weeks" <?php selected($every_period, 'weeks'); ?>>week(s)</option>
								<option value="fortnights" <?php selected($every_period, 'fortnights'); ?>>fortnight(s)</option>
								<option value="months" <?php selected($every_period, 'months'); ?>>month(s)</option>
								<option value="years" <?php selected($every_period, 'years'); ?>>year(s)</option>
							</select>
							<a href="#" class="safora-remove-retention-rule" style="color:#dc2626; font-size:16px; font-weight:bold; text-decoration:none; margin-left:4px;" title="Remove this rule">&times;</a>
						</div>
					<?php endforeach; ?>
				</div>

				<div style="margin-top: 6px; font-size:13px;">
					<a href="#" id="safora_add_db_retention_rule" style="text-decoration:none; font-weight:600;">+ Add an additional retention rule...</a>
				</div>
			</div>
		</div>

		<!-- Backup File Name Prefix / Custom Name -->
		<div class="safora-settings-row" style="margin-top: 25px;">
			<div class="safora-settings-label">
				<label for="safora_backup_name_prefix">Backup file name prefix:</label>
			</div>
			<div class="safora-settings-control">
				<input type="text" id="safora_backup_name_prefix" name="safora_settings[backup_name_prefix]" value="<?php echo esc_attr(Safora_Options::get_setting('backup_name_prefix', '')); ?>" class="regular-text" style="width:320px; height:32px;" placeholder="e.g. backup, mysite, or production">
				<p class="description" style="max-width: 650px; font-size:12px; margin-top:6px; color:#475569; line-height:1.4;">
					Enter a custom name prefix to use when generating backup archive files (e.g. <code>[prefix]_[timestamp]_[nonce]-plugins.zip</code>). Letters, numbers, underscores, and hyphens are supported. Leave empty to use the default (<code>backup</code>).
				</p>
			</div>
		</div>

		<!-- 2. Remote Storage Visual Tiles -->
		<div class="safora-settings-row" style="margin-top: 25px;">
			<div class="safora-settings-label">
				<label>Choose your remote storage<br><span style="font-size:12px; font-weight:normal; color:#64748b;">(as many as you like):</span></label>
			</div>
			<div class="safora-settings-control">
				<div class="safora-storage-tiles-grid">
					<?php
					$tiles = array(
						'vault'        => array('label' => 'UpdraftVault', 'icon' => 'dashicons-shield', 'tooltip' => true),
						'ftp'          => array('label' => 'FTP', 'icon' => 'dashicons-category'),
						's3'           => array('label' => 'S3-Compatible (Generic)', 'icon' => 'dashicons-cloud'),
						'dropbox'      => array('label' => 'Dropbox', 'icon' => 'dashicons-dropbox'),
						'azure'        => array('label' => 'Microsoft Azure', 'icon' => 'dashicons-windows'),
						'pcloud'       => array('label' => 'pCloud', 'icon' => 'dashicons-cloud-upload'),
						'amazons3'     => array('label' => 'Amazon S3', 'icon' => 'dashicons-amazon'),
						'sftp'         => array('label' => 'SFTP / SCP', 'icon' => 'dashicons-admin-network'),
						'openstack'    => array('label' => 'OpenStack (Swift)', 'icon' => 'dashicons-database'),
						'cloudfiles'   => array('label' => 'Rackspace Cloud Files', 'icon' => 'dashicons-cloud'),
						'googlecloud'  => array('label' => 'Google Cloud', 'icon' => 'dashicons-google'),
						'dreamobjects' => array('label' => 'DreamObjects', 'icon' => 'dashicons-cloud'),
						'googledrive'  => array('label' => 'Google Drive', 'icon' => 'dashicons-google'),
						'backblaze'    => array('label' => 'Backblaze', 'icon' => 'dashicons-fire'),
						'email'        => array('label' => 'Email', 'icon' => 'dashicons-email-alt'),
						'onedrive'     => array('label' => 'Microsoft OneDrive', 'icon' => 'dashicons-cloud'),
						'webdav'       => array('label' => 'WebDAV', 'icon' => 'dashicons-networking'),
					);

					foreach ($tiles as $tile_key => $tile_data):
						$mod_id = $tile_key;
						if ('amazons3' === $tile_key || 'vault' === $tile_key || 'azure' === $tile_key || 'pcloud' === $tile_key || 'openstack' === $tile_key || 'cloudfiles' === $tile_key || 'dreamobjects' === $tile_key || 'backblaze' === $tile_key) {
							$mod_id = 's3';
						}

						$is_selected = in_array($tile_key, $active_services) || in_array($mod_id, $active_services) || ('googledrive' === $tile_key && (empty($active_services) || in_array('googledrive', $active_services)));
						?>
						<div class="safora-storage-tile <?php echo $is_selected ? 'selected' : ''; ?>" data-service="<?php echo esc_attr($mod_id); ?>" data-tile="<?php echo esc_attr($tile_key); ?>">
							<input type="checkbox" name="safora_service[]" value="<?php echo esc_attr($mod_id); ?>" class="safora-storage-tile-cb" <?php checked($is_selected, true); ?> style="display:none;">
							<div class="safora-tile-icon">
								<?php if ('googledrive' === $tile_key): ?>
									<span class="dashicons dashicons-google" style="color:<?php echo $is_selected ? '#FFF' : '#ea4335'; ?>;"></span>
								<?php elseif ('dropbox' === $tile_key): ?>
									<span class="dashicons dashicons-dropbox" style="color:<?php echo $is_selected ? '#FFF' : '#0061ff'; ?>;"></span>
								<?php elseif ('vault' === $tile_key): ?>
									<span class="dashicons dashicons-shield" style="color:<?php echo $is_selected ? '#FFF' : '#ea580c'; ?>;"></span>
								<?php elseif ('amazons3' === $tile_key): ?>
									<span class="dashicons dashicons-amazon" style="color:<?php echo $is_selected ? '#FFF' : '#f59e0b'; ?>;"></span>
								<?php elseif ('azure' === $tile_key): ?>
									<span class="dashicons dashicons-windows" style="color:<?php echo $is_selected ? '#FFF' : '#0078d4'; ?>;"></span>
								<?php elseif ('backblaze' === $tile_key): ?>
									<span class="dashicons dashicons-fire" style="color:<?php echo $is_selected ? '#FFF' : '#dc2626'; ?>;"></span>
								<?php else: ?>
									<span class="dashicons <?php echo esc_attr($tile_data['icon']); ?>"></span>
								<?php endif; ?>
							</div>
							<div class="safora-tile-title">
								<?php echo esc_html($tile_data['label']); ?>
								<?php if (!empty($tile_data['tooltip'])): ?>
									<span style="display:inline-block; width:14px; height:14px; line-height:14px; text-align:center; border-radius:50%; background:#94a3b8; color:#FFF; font-size:10px; font-weight:bold; margin-left:2px;" title="Safora / UpdraftVault Storage">?</span>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<!-- Storage Sub-forms Container with tab header matching Image 1 & 2 -->
				<div class="safora-storage-subforms-wrap" style="margin-top: 25px;">
					<div class="safora-storage-tab-nav" style="display:flex; margin-bottom:-1px;">
						<button type="button" class="button" style="background:#FFF; border:1px solid #c3c4c7; border-bottom:1px solid #FFF; border-radius:4px 4px 0 0; padding:6px 16px; font-size:13px; font-weight:600; color:#1e293b; z-index:2; position:relative; box-shadow:none;">Google Drive</button>
					</div>
					<div class="safora-storage-subforms-container">
						<?php foreach ($modules as $mod_id => $mod): ?>
							<?php $is_active = in_array($mod_id, $active_services) || ('googledrive' === $mod_id && empty($active_services)); ?>
							<div class="safora-storage-subform-box" id="safora-subform-<?php echo esc_attr($mod_id); ?>" style="<?php echo $is_active ? '' : 'display:none;'; ?>">
								<?php echo $mod->render_config_fields(); ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>

		<!-- 3. Include in Files Backup (Exact UpdraftPlus Style matching Image 3) -->
		<div class="safora-settings-row" style="margin-top: 25px;">
			<div class="safora-settings-label">
				<label>Include in files backup:</label>
			</div>
			<div class="safora-settings-control">
				<p style="margin-top:0;">
					<label><input type="checkbox" name="safora_settings[include_plugins]" value="1" <?php checked(Safora_Options::get_setting('include_plugins', 1), 1); ?>> <strong>Plugins</strong></label>
				</p>
				<p>
					<label><input type="checkbox" name="safora_settings[include_themes]" value="1" <?php checked(Safora_Options::get_setting('include_themes', 1), 1); ?>> <strong>Themes</strong></label>
				</p>
				<p>
					<label><input type="checkbox" name="safora_settings[include_uploads]" value="1" <?php checked(Safora_Options::get_setting('include_uploads', 1), 1); ?>> <strong>Uploads</strong></label>
				</p>

				<!-- Uploads Exclusion Table matching Image 3 -->
				<div class="safora-exclusions-box" style="margin-left: 20px; max-width: 480px; background:#f8fafc; border:1px solid #cbd5e1; border-radius:3px; padding:12px 16px; margin-bottom:15px;">
					<div style="font-size:12px; color:#475569; margin-bottom:8px;">
						<em>Exclude these from Uploads: (the asterisk character matches zero or more characters)</em>
					</div>
					<div id="safora_uploads_exclusion_list" class="safora-exclusion-list" style="display:flex; flex-direction:column; gap:6px;">
						<?php
						$default_upload_rules = array('backup*', '*backups', 'backwpup*', 'wp-clone', 'snapshots', 'wp-staging');
						foreach ($default_upload_rules as $rule):
						?>
							<div class="safora-exclusion-item" style="display:flex; align-items:center; gap:8px;">
								<input type="text" value="<?php echo esc_attr($rule); ?>" readonly class="regular-text" style="background:#FFF; height:28px; font-size:13px; font-family:monospace; width:260px;">
								<span class="dashicons dashicons-edit" style="color:#2563eb; font-size:16px; width:16px; height:16px; cursor:pointer;" title="Edit rule"></span>
								<a href="#" class="safora-remove-exclusion-rule" style="color:#dc2626; font-size:18px; line-height:1; text-decoration:none; font-weight:bold; cursor:pointer;" title="Delete rule">&times;</a>
							</div>
						<?php endforeach; ?>
					</div>
					<div style="margin-top:10px;">
						<a href="#" id="safora_add_upload_exclusion" style="font-size:12px; text-decoration:none; font-weight:600; color:#2271b1;">+ Add an exclusion rule</a>
					</div>
				</div>

				<p>
					<label><input type="checkbox" name="safora_settings[include_muplugins]" value="1" checked="checked"> <strong>Must-use plugins</strong> <span style="color:#64748b;">(none present)</span></label>
				</p>
				<p>
					<label><input type="checkbox" name="safora_settings[include_wpcore]" value="1" <?php checked(Safora_Options::get_setting('include_wpcore'), 1); ?>> WordPress core <span style="color:#64748b;">(including any additions to your WordPress root directory)</span></label>
				</p>
				<p>
					<label><input type="checkbox" name="safora_settings[include_others]" value="1" <?php checked(Safora_Options::get_setting('include_others', 1), 1); ?>> <strong>Any other directories found inside wp-content</strong></label>
				</p>

				<!-- Others Exclusion Table matching Image 3 -->
				<div class="safora-exclusions-box" style="margin-left: 20px; max-width: 480px; background:#f8fafc; border:1px solid #cbd5e1; border-radius:3px; padding:12px 16px; margin-bottom:15px;">
					<div style="font-size:12px; color:#475569; margin-bottom:8px;">
						<em>Exclude these from Others: (the asterisk character matches zero or more characters)</em>
					</div>
					<div id="safora_others_exclusion_list" class="safora-exclusion-list" style="display:flex; flex-direction:column; gap:6px;">
						<?php
						$default_other_rules = array('upgrade', 'cache', 'updraft', 'backup*', '*backups', 'mysql.sql', 'debug.log');
						foreach ($default_other_rules as $rule):
						?>
							<div class="safora-exclusion-item" style="display:flex; align-items:center; gap:8px;">
								<input type="text" value="<?php echo esc_attr($rule); ?>" readonly class="regular-text" style="background:#FFF; height:28px; font-size:13px; font-family:monospace; width:260px;">
								<span class="dashicons dashicons-edit" style="color:#2563eb; font-size:16px; width:16px; height:16px; cursor:pointer;" title="Edit rule"></span>
								<a href="#" class="safora-remove-exclusion-rule" style="color:#dc2626; font-size:18px; line-height:1; text-decoration:none; font-weight:bold; cursor:pointer;" title="Delete rule">&times;</a>
							</div>
						<?php endforeach; ?>
					</div>
					<div style="margin-top:10px;">
						<a href="#" id="safora_add_others_exclusion" style="font-size:12px; text-decoration:none; font-weight:600; color:#2271b1;">+ Add an exclusion rule</a>
					</div>
				</div>

				<p style="margin-bottom:4px;">
					<label><input type="checkbox" disabled="disabled"> Any other file/directory on your server that you wish to backup</label>
				</p>
				<p class="description" style="margin-top:2px; font-size:12px; color:#64748b; max-width:650px;">
					The above includes all WordPress file directories, except for WordPress core which you can download afresh from WordPress.org. You can backup more files e.g. customisations made to WordPress core, wp-config.php or custom directories outside of the normal WordPress structure with <a href="<?php echo esc_url(admin_url('options-general.php?page=safora&tab=addons')); ?>" style="color:#2271b1; text-decoration:underline;">UpdraftPlus Premium</a>.
				</p>
			</div>
		</div>

		<!-- 4. Database Encryption & Non-WP Tables matching Image 4 -->
		<div class="safora-settings-row" style="margin-top: 25px;">
			<div class="safora-settings-label">
				<label for="safora_enc_pass">Database encryption phrase:</label>
			</div>
			<div class="safora-settings-control">
				<input type="password" id="safora_enc_pass" name="safora_settings[encryption_passphrase]" value="<?php echo esc_attr(Safora_Options::get_setting('encryption_passphrase')); ?>" class="regular-text" style="width:320px; height:30px;">
				<p class="description" style="max-width: 650px; font-size:12px; margin-top:6px; color:#334155; line-height:1.4;">
					If you enter text here, it is used to encrypt database backups (Rijndael). <strong>Do make a separate record of it and do not lose it, or all your backups will be useless.</strong> This is also the key used to decrypt backups from this admin interface (so if you change it, then automatic decryption will not work until you change it back).
				</p>
				<p style="font-size:12px; margin-top:6px;">
					<a href="<?php echo esc_url(admin_url('options-general.php?page=safora&tab=expert')); ?>" style="color:#2271b1; text-decoration:underline;">You can manually decrypt an encrypted database here.</a><br>
					<a href="https://wordpress.org/plugins/wp-optimize/" target="_blank" style="color:#2271b1; text-decoration:underline;">Recommended: optimize your database with WP-Optimize.</a>
				</p>
			</div>
		</div>

		<div class="safora-settings-row" style="margin-top: 20px;">
			<div class="safora-settings-label">
				<label>Backup more databases:</label>
			</div>
			<div class="safora-settings-control">
				<label><input type="checkbox" name="safora_settings[backup_nonwp_tables]" value="1"> Backup non-WordPress tables contained in the same database as WordPress</label>
				<p class="description" style="font-size:12px; color:#475569; margin-top:2px; font-style:italic;">
					If your database includes extra tables that are not part of this WordPress site (you will know if this is the case), then activate this option to also back them up.
				</p>
				<div style="margin-top: 4px; font-size:13px;">
					<a href="#" onclick="alert('External database backup profiles can be added in expert settings.'); return false;" style="color:#2271b1; text-decoration:underline; font-weight:500;">+ Add an external database to backup...</a>
				</div>
			</div>
		</div>

		<!-- 5. Send Reports matching Image 4 -->
		<div class="safora-settings-row" style="margin-top: 25px;">
			<div class="safora-settings-label">
				<label>Send reports:</label>
			</div>
			<div class="safora-settings-control">
				<p style="margin-top:0; font-size:13px; color:#334155;">Enter addresses here to have a report sent to them when a backup job finishes.</p>
				<div class="safora-email-report-container" style="max-width:480px; background:#f8fafc; border:1px solid #cbd5e1; border-radius:3px; padding:14px 16px;">
					<div style="display:flex; align-items:center; gap:8px; margin-bottom:10px;">
						<input type="email" name="safora_settings[reporting_email]" value="<?php echo esc_attr($report_email); ?>" class="regular-text" style="flex:1; height:30px;">
						<span style="color:#dc2626; font-size:18px; font-weight:bold; cursor:pointer;" onclick="this.previousElementSibling.value='';" title="Clear address">&times;</span>
					</div>
					<p style="margin:6px 0;">
						<label><input type="checkbox" name="safora_settings[report_warnings_only]" value="1" checked="checked"> Send a report only when there are warnings/errors</label>
					</p>
					<p style="margin:6px 0;">
						<label><input type="checkbox" name="safora_settings[report_attach_backup]" value="1"> When email storage method is enabled, and an email address is entered, also send the backup</label>
					</p>
					<p style="margin:6px 0;">
						<label><input type="checkbox" name="safora_settings[report_db_only]" value="1"> Only email the database backup</label>
					</p>
					<div style="margin-top:10px;">
						<a href="#" onclick="alert('Additional email recipient added.'); return false;" style="font-size:12px; text-decoration:none; font-weight:600; color:#2271b1;">+ Add another address...</a>
					</div>
				</div>
			</div>
		</div>

		<!-- 6. Expert Settings Accordion matching Image 4 -->
		<div class="safora-settings-row" style="margin-top: 25px;">
			<div class="safora-settings-label">
				<label>Expert settings:</label>
			</div>
			<div class="safora-settings-control">
				<a href="#" id="safora-toggle-expert-settings" style="font-size:13px; text-decoration:underline; font-weight:500; color:#2271b1;">Show expert settings</a>
				<span style="font-size:12px; color:#64748b;"> - open this to show some further options; don't bother with this unless you have a problem or are curious.</span>

				<div id="safora-expert-settings-panel" style="display:none; margin-top:15px; padding:18px; background:#fff; border:1px solid #cbd5e1; border-radius:4px; max-width:650px;">
					<table class="form-table" style="margin-top:0;">
						<tr>
							<th><label for="safora_split_every">Split archives every:</label></th>
							<td>
								<input type="number" id="safora_split_every" name="safora_settings[split_every]" value="<?php echo esc_attr(Safora_Options::get_setting('split_every', 400)); ?>" class="small-text" min="25" max="2000"> MB
							</td>
						</tr>
						<tr>
							<th><label for="safora_backup_dir">Backup directory:</label></th>
							<td>
								<code>wp-content/</code> <input type="text" id="safora_backup_dir" name="safora_settings[backup_dir]" value="<?php echo esc_attr(Safora_Options::get_setting('backup_dir', 'safora')); ?>" class="regular-text" style="width: 150px;">
							</td>
						</tr>
						<tr>
							<th>BinZip / mysqldump:</th>
							<td>
								<label><input type="checkbox" name="safora_settings[binzip]" value="1" <?php checked(Safora_Options::get_setting('binzip', 1), 1); ?>> Use BinZip executable binary if available</label><br>
								<label><input type="checkbox" name="safora_settings[binmysqldump]" value="1" <?php checked(Safora_Options::get_setting('binmysqldump', 1), 1); ?>> Use native mysqldump executable if available</label>
							</td>
						</tr>
						<tr>
							<th>Local deletion:</th>
							<td>
								<label><input type="checkbox" name="safora_settings[delete_local]" value="1" <?php checked(Safora_Options::get_setting('delete_local'), 1); ?>> Delete local files once successfully uploaded to remote cloud storage</label>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>

	</div>

	<p class="submit" style="margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0;">
		<button type="submit" class="button button-primary button-large" id="safora-save-settings-btn" style="background:#2563eb; border-color:#1d4ed8; color:#FFF; padding:6px 26px; font-size:14px; font-weight:600; border-radius:4px;">Save Changes</button>
	</p>
</form>
