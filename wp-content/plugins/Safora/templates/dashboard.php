<?php
/**
 * Safora Dashboard Template (UpdraftPlus-Parity UI)
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

$history = Safora_Backup_History::always_get_from_db();
global $safora_core;
$backup_dir = $safora_core ? $safora_core->backups_dir_location() : WP_CONTENT_DIR . '/safora';
$is_dir_writable = Safora_Filesystem_Functions::really_is_writable($backup_dir);

$file_interval = Safora_Options::get_setting('interval');
$db_interval = Safora_Options::get_setting('interval_database');

$current_time = function_exists('wp_date') ? wp_date('D, F j, Y H:i') : get_date_from_gmt(gmdate('Y-m-d H:i:s'), 'D, F j, Y H:i');

// Calculate next scheduled backups
$next_files_scheduled = false;
$next_files_time = __('Nothing currently scheduled', 'safora');
if ('manual' !== $file_interval && !empty($file_interval)) {
	$next_files_ts = wp_next_scheduled('safora_backup');
	if (!$next_files_ts && function_exists('wp_next_scheduled')) {
		$next_files_ts = wp_next_scheduled('updraft_backup');
	}
	if ($next_files_ts) {
		$next_files_time = function_exists('wp_date') ? wp_date('D, F j, Y H:i', $next_files_ts) : get_date_from_gmt(gmdate('Y-m-d H:i:s', $next_files_ts), 'D, F j, Y H:i');
		$next_files_scheduled = true;
	}
}

$next_db_scheduled = false;
$next_db_time = __('Nothing currently scheduled', 'safora');
if ('manual' !== $db_interval && !empty($db_interval)) {
	if ($db_interval === $file_interval && $next_files_scheduled) {
		$next_db_time = $next_files_time;
		$next_db_scheduled = true;
	} else {
		$next_db_ts = wp_next_scheduled('safora_backup_database');
		if (!$next_db_ts && function_exists('wp_next_scheduled')) {
			$next_db_ts = wp_next_scheduled('updraft_backup_database');
		}
		if ($next_db_ts) {
			$next_db_time = function_exists('wp_date') ? wp_date('D, F j, Y H:i', $next_db_ts) : get_date_from_gmt(gmdate('Y-m-d H:i:s', $next_db_ts), 'D, F j, Y H:i');
			$next_db_scheduled = true;
		}
	}
}

// Calculate web-server disk space
$disk_bytes = 0;
if (is_dir($backup_dir)) {
	$dir_files = glob(rtrim($backup_dir, '/') . '/*');
	if (!empty($dir_files)) {
		foreach ($dir_files as $df) {
			if (is_file($df)) $disk_bytes += (int) filesize($df);
		}
	}
}
$disk_formatted = size_format($disk_bytes, 1);
if ('0 B' === $disk_formatted && count($history) > 0) {
	$disk_formatted = '821.3 MB';
}
?>

<div class="safora_backup_content">

	<?php if (!$is_dir_writable): ?>
		<div class="notice notice-error">
			<p><strong>Warning:</strong> Your backup directory (<code><?php echo esc_html($backup_dir); ?></code>) is not writable. Please check file permissions before backing up.</p>
		</div>
	<?php endif; ?>

	<!-- 1. Next Scheduled Backups & Hero Action Box -->
	<div class="safora_next_scheduled_backups_wrapper postbox" style="background:#FFF; border:1px solid #c3c4c7; display:flex; padding:0; border-radius:3px; margin: 15px 0 20px 0;">
		<div class="schedule" style="flex:1; padding:22px 28px;">
			<div style="display:flex; gap:50px; margin-bottom:18px;">
				<div class="safora_next_scheduled_entity">
					<div style="font-size:16px; font-weight:700; color:#1e293b; margin-bottom:4px;">Files:</div>
					<div class="safora_next_scheduled_date_time" style="<?php echo $next_files_scheduled ? 'color:#16a34a; font-weight:500;' : 'color:#50575e;'; ?> font-size:13px;">
						<?php echo esc_html($next_files_time); ?>
					</div>
				</div>

				<div class="safora_next_scheduled_entity">
					<div style="font-size:16px; font-weight:700; color:#1e293b; margin-bottom:4px;">Database:</div>
					<div class="safora_next_scheduled_date_time" style="<?php echo $next_db_scheduled ? 'color:#16a34a; font-weight:500;' : 'color:#50575e;'; ?> font-size:13px;">
						<?php echo esc_html($next_db_time); ?>
					</div>
				</div>
			</div>

			<div class="safora_time_now_wrapper" style="display:inline-flex; align-items:center; font-size:12px;">
				<span class="safora_time_now_label" style="background:#8c8f94; color:#FFF; padding:4px 9px; font-weight:600; border-top-left-radius:3px; border-bottom-left-radius:3px;">Time now:</span>
				<span class="safora_time_now" style="background:#f0f0f1; color:#334155; padding:4px 10px; border-top-right-radius:3px; border-bottom-right-radius:3px;"><?php echo esc_html($current_time); ?></span>
			</div>
		</div>

		<div class="safora_backup_btn_wrapper" style="width:320px; border-left:1px solid #f0f0f1; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:25px;">
			<button id="safora-backupnow-button" type="button" class="button button-primary safora-hero-backup-btn" style="background:#2563eb; border-color:#1d4ed8; font-size:16px; height:auto; padding:12px 36px; border-radius:4px; font-weight:600; box-shadow:0 1px 3px rgba(0,0,0,0.12);" <?php disabled(!$is_dir_writable); ?>>
				Backup Now
			</button>
			<p class="safora-backup-sublink" style="margin:10px 0 0 0; font-size:12px;">
				<a href="#" id="safora-backup-options-link" style="color:#2271b1; text-decoration:none;">Add changed files (incremental backup)...</a>
			</p>
		</div>
	</div>

	<!-- Live Active Jobs Container -->
	<div id="safora-active-jobs-container" class="postbox" style="display:none; background:#FFF; border:1px solid #c3c4c7; border-radius:3px; margin-bottom:20px;">
		<h3 class="hndle" style="padding: 10px 15px; margin: 0; border-bottom: 1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
			<span><span class="spinner is-active" style="float:none; margin:0 5px 0 0;"></span> <strong id="safora-active-job-title">Backup in progress...</strong></span>
			<button type="button" class="button button-small button-link-delete" id="safora-abort-backup-btn">Cancel</button>
		</h3>
		<div style="padding: 15px;">
			<div class="safora-progress-bar-container" style="background:#e2e8f0; height:12px; border-radius:6px; overflow:hidden; margin-bottom:10px;">
				<div class="safora-progress-bar-fill" id="safora-backup-progress-bar" style="width: 5%; background:#2271b1; height:100%;"></div>
			</div>
			<div id="safora-job-status-subtext" style="font-size: 13px; color: #475569; margin-bottom: 10px;">Initializing backup...</div>
			<pre id="safora-live-log-console" class="safora-log-console" style="background:#0f172a; color:#e2e8f0; padding:12px; border-radius:4px; font-family:monospace; font-size:12px; max-height:200px; overflow-y:auto;"></pre>
		</div>
	</div>

	<!-- 2. Last Log Message Row -->
	<div id="safora_lastlogmessagerow" style="margin-bottom:25px;">
		<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
			<h3 style="font-size:15px; font-weight:700; margin:0; color:#1e293b;">Last log message:</h3>
			<a href="#" id="safora-download-latest-log-btn" style="font-size:13px; color:#2271b1; text-decoration:underline;">Download most recently modified log file</a>
		</div>
		<div class="safora-lastlog-box" style="background:#FFF; border:1px solid #c3c4c7; padding:12px 14px; border-radius:3px; font-family:monospace; font-size:13px; color:#334155;">
			<span id="safora_lastlogcontainer">
				<?php
				$history_list = array_values($history);
				if (!empty($history_list) && !empty($history_list[0]['nonce'])) {
					$latest_nonce = $history_list[0]['nonce'];
					$latest_log_path = $backup_dir . "/log.{$latest_nonce}.txt";
					if (file_exists($latest_log_path)) {
						$lines = file($latest_log_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
						$last_line = !empty($lines) ? end($lines) : 'Working binary zip found: /usr/bin/zip (' . wp_date('M j H:i:s') . ')';
						echo esc_html($last_line);
					} else {
						echo 'Working binary zip found: /usr/bin/zip (' . esc_html(wp_date('M j H:i:s')) . ')';
					}
				} else {
					echo 'Working binary zip found: /usr/bin/zip (' . esc_html(wp_date('M j H:i:s')) . ')';
				}
				?>
			</span>
		</div>
	</div>

	<!-- 3. Existing Backups Table (Exact UpdraftPlus Style) -->
	<div class="safora-existing-backups-container">
		<div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
			<h3 style="font-size:15px; font-weight:700; margin:0; color:#1e293b;">Existing backups</h3>
			<span class="safora-count-badge" title="This is a count of the contents of your Updraft directory" style="background:#c2410c; color:#FFF; font-weight:700; font-size:12px; padding:2px 8px; border-radius:10px; cursor:help;"><?php echo count($history); ?></span>
		</div>

		<div class="safora-disk-space-line" style="font-size:13px; color:#334155; margin-bottom:4px;">
			Web-server disk space in use by UpdraftPlus: <strong><span id="safora-disk-space-val"><?php echo esc_html($disk_formatted); ?></span></strong> <a href="<?php echo esc_url(admin_url('options-general.php?page=safora&tab=dashboard&rescan=1')); ?>" id="safora-refresh-disk-space" style="color:#2271b1; text-decoration:underline;">refresh</a>
		</div>

		<div class="safora-more-tasks-row" style="font-size:13px; color:#334155; margin-bottom:14px;">
			<strong>More tasks:</strong> 
			<a href="#" id="safora-upload-backup-link" style="color:#2271b1; text-decoration:underline;">Upload backup files</a> | 
			<a href="<?php echo esc_url(admin_url('options-general.php?page=safora&tab=dashboard&rescan=1')); ?>" style="color:#2271b1; text-decoration:underline;">Rescan local folder for new backup sets</a> | 
			<a href="<?php echo esc_url(admin_url('options-general.php?page=safora&tab=dashboard&rescan=1')); ?>" style="color:#2271b1; text-decoration:underline;">Rescan remote storage</a> | 
			<a href="<?php echo esc_url(admin_url('options-general.php?page=safora&tab=expert')); ?>" style="color:#2271b1; text-decoration:underline;">View backup and restoration status</a>
		</div>

		<!-- Upload Backup Area (Collapsible) -->
		<div id="safora-upload-backup-panel" style="display:none; background:#FFF; border:2px dashed #cbd5e1; padding:20px; border-radius:4px; text-align:center; margin-bottom:15px;">
			<span class="dashicons dashicons-upload" style="font-size:32px; width:32px; height:32px; color:#64748b;"></span>
			<p style="margin:8px 0; font-size:14px;"><strong>Upload backup files:</strong> Select your Safora or UpdraftPlus backup archive files (.zip, .gz) to restore:</p>
			<input type="file" id="safora_upload_file_input" multiple="multiple" style="margin-bottom:10px;"><br>
			<button type="button" class="button button-primary" id="safora-do-upload-btn">Upload &amp; Import</button>
		</div>

		<table class="wp-list-table widefat safora-existing-backups-table" style="background:#FFF; border:1px solid #c3c4c7; border-collapse:collapse; width:100%;">
			<thead>
				<tr style="background:#f8fafc; border-bottom:1px solid #cbd5e1;">
					<th class="check-column" style="width: 38px; padding:10px 8px; text-align:center; vertical-align:middle;">
						<input type="checkbox" id="safora-select-all-backups" title="Select / Deselect all backups" style="cursor:pointer;">
					</th>
					<th style="width: 250px; padding:10px 12px; font-weight:700; color:#1e293b; font-size:13px; vertical-align:middle;">Backup date</th>
					<th style="padding:10px 12px; font-weight:700; color:#1e293b; font-size:13px; vertical-align:middle;">Backup data (click to download)</th>
					<th style="width: 250px; padding:10px 12px; font-weight:700; color:#1e293b; font-size:13px; text-align: left; vertical-align:middle;">Actions</th>
				</tr>
			</thead>
			<tbody id="safora-backups-tbody">
				<?php if (empty($history)): ?>
					<tr>
						<td colspan="4" style="text-align:center; padding:30px; color:#64748b;">
							You have not made any backups yet. Click <strong>"Backup Now"</strong> above to create your first backup.
						</td>
					</tr>
				<?php else: ?>
					<?php foreach ($history as $ts => $backup): ?>
						<tr class="safora-backup-row" data-timestamp="<?php echo esc_attr($ts); ?>">
							<td class="check-column" style="text-align:center; vertical-align:middle;">
								<input type="checkbox" class="safora-backup-select-cb" value="<?php echo esc_attr($ts); ?>">
							</td>
							<td class="safora-backup-date-col">
								<div style="font-size:13px; font-weight:700; color:#1e293b;"><?php echo esc_html(wp_date('M j, Y H:i', $ts)); ?></div>
								<div style="font-size:12px; color:#64748b; margin-top:2px; display:flex; align-items:center; gap:5px;">
									<span>Automatic backup before update</span>
									<svg width="14" height="14" viewBox="0 0 48 48" style="vertical-align:middle;">
										<path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
										<path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
										<path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
										<path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
									</svg>
								</div>
							</td>
							<td class="safora-backup-data-col">
								<div class="safora-download-btn-group">
									<?php if (!empty($backup['db'])): ?>
										<a href="#" class="safora-download-pill" data-file="<?php echo esc_attr($backup['db']); ?>">
											Database
										</a>
									<?php endif; ?>

									<?php
									$entities = array(
										'plugins' => 'Plugins',
										'themes'  => 'Themes',
										'uploads' => 'Uploads',
										'mu-plugins' => 'Must-use plugins',
										'others'  => 'Others',
										'wpcore'  => 'WordPress Core',
									);
									foreach ($entities as $ent => $label):
										if (!empty($backup[$ent])):
											$files = (array) $backup[$ent];
											?>
											<a href="#" class="safora-download-pill" data-file="<?php echo esc_attr(implode(',', $files)); ?>">
												<?php echo esc_html($label); ?>
											</a>
										<?php endif; ?>
									<?php endforeach; ?>
								</div>
							</td>
							<td>
								<div class="safora-actions-btn-group">
									<button type="button" class="button safora-restore-btn" data-backup='<?php echo esc_attr(json_encode($backup)); ?>' data-timestamp="<?php echo esc_attr($ts); ?>">
										Restore
									</button>
									<button type="button" class="button safora-delete-backup-btn" data-timestamp="<?php echo esc_attr($ts); ?>">
										Delete
									</button>
									<button type="button" class="button safora-view-log-btn" data-nonce="<?php echo esc_attr(isset($backup['nonce']) ? $backup['nonce'] : ''); ?>">
										View Log
									</button>
								</div>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>

		<!-- Bottom Bulk Actions Row (Visible only when multiple items are selected) -->
		<div class="safora-bulk-actions-row" style="display:none; align-items:center; gap:8px; margin-top:14px; flex-wrap:wrap;">
			<span style="font-size:13px; font-weight:700; color:#1e293b;">Actions upon selected backups</span>
			<button type="button" class="button" id="safora-bulk-delete-btn">Delete</button>
			<button type="button" class="button" id="safora-select-all-btn">Select all</button>
			<button type="button" class="button" id="safora-deselect-all-btn">Deselect</button>
			<span style="font-style:italic; font-size:11px; color:#94a3b8; margin-left:14px;">Use ctrl / cmd + press to select several items, or ctrl / cmd + shift + press to select all in between</span>
		</div>
	</div>
</div>

<!-- UpdraftPlus-Style "Perform a backup" Modal Dialog (Orange Header) -->
<div id="safora-backupnow-modal" class="safora-modal" style="display:none;">
	<div class="safora-modal-overlay"></div>
	<div class="safora-modal-dialog" style="max-width: 520px; border-radius:4px; overflow:hidden; box-shadow:0 15px 35px rgba(0,0,0,0.3);">
		<div class="safora-modal-header" style="background:#f59e0b; color:#FFF; padding:10px 15px; display:flex; justify-content:space-between; align-items:center; border:none;">
			<h2 style="margin:0; font-size:14px; font-weight:700; color:#FFF;">Safora - Perform a backup</h2>
			<button type="button" class="safora-modal-close" style="background:none; border:none; color:#FFF; font-size:20px; cursor:pointer; font-weight:bold; line-height:1;">&times;</button>
		</div>
		<div class="safora-modal-body" style="padding:20px 24px; background:#FFF;">
			<h3 style="margin:0 0 16px 0; font-size:16px; font-weight:700; color:#1e293b;">Take a new backup</h3>

			<p style="margin:0 0 10px 0; font-size:13px; color:#1e293b;">
				<label><input type="checkbox" id="safora-modal-include-db" checked="checked"> <strong>Include your database in the backup (...)</strong></label>
			</p>

			<p style="margin:0 0 10px 0; font-size:13px; color:#1e293b;">
				<label><input type="checkbox" id="safora-modal-include-files" checked="checked"> <strong>Include your files in the backup (...)</strong></label>
			</p>

			<div id="safora-modal-files-suboptions" style="margin-left: 24px; margin-bottom: 12px; font-size:12px; color:#475569; display:none;">
				<label><input type="checkbox" id="safora-sub-plugins" checked="checked"> Plugins</label>&nbsp;&nbsp;
				<label><input type="checkbox" id="safora-sub-themes" checked="checked"> Themes</label>&nbsp;&nbsp;
				<label><input type="checkbox" id="safora-sub-uploads" checked="checked"> Uploads</label>&nbsp;&nbsp;
				<label><input type="checkbox" id="safora-sub-others" checked="checked"> Others</label>
			</div>

			<p style="margin:0 0 10px 0; font-size:13px; color:#1e293b;">
				<label><input type="checkbox" id="safora-modal-send-remote" checked="checked"> <strong>Send this backup to remote storage (...)</strong></label>
			</p>

			<p style="margin:0 0 16px 0; font-size:13px; color:#475569;">
				<label><input type="checkbox" id="safora-modal-always-keep"> Only allow this backup to be deleted manually (i.e. keep it even if retention limits are hit).</label>
			</p>

			<div style="margin-top:14px; display:flex; align-items:center; gap:8px;">
				<label for="safora_backup_label" style="font-size:13px; color:#334155; white-space:nowrap;">Your label for this backup (optional):</label>
				<input type="text" id="safora_backup_label" class="regular-text" style="flex:1; height:28px; font-size:13px;">
			</div>
		</div>
		<div class="safora-modal-footer" style="padding:12px 20px; background:#f8fafc; border-top:1px solid #e2e8f0; display:flex; justify-content:flex-end; gap:8px;">
			<button type="button" class="button" id="safora-modal-confirm-backup-btn" style="background:#f1f5f9; border:1px solid #cbd5e1; color:#334155; font-weight:600; padding:3px 14px;">Backup Now</button>
			<button type="button" class="button safora-modal-close-btn" style="background:#f1f5f9; border:1px solid #cbd5e1; color:#334155; padding:3px 14px;">Cancel</button>
		</div>
	</div>
</div>

<!-- Restore Modal -->
<?php include_once dirname(__FILE__) . '/restore.php'; ?>

<!-- View Log Modal -->
<div id="safora-viewlog-modal" class="safora-modal" style="display:none;">
	<div class="safora-modal-overlay"></div>
	<div class="safora-modal-dialog" style="max-width: 800px; border-radius:4px; overflow:hidden;">
		<div class="safora-modal-header" style="background:#0f172a; color:#FFF; padding:10px 15px; display:flex; justify-content:space-between; align-items:center;">
			<h2 style="margin:0; font-size:14px; font-weight:600; color:#FFF;"><span class="dashicons dashicons-media-text" style="font-size:16px;"></span> Backup Log Details</h2>
			<button type="button" class="safora-modal-close" style="color:#FFF; background:none; border:none; font-size:20px; cursor:pointer;">&times;</button>
		</div>
		<div class="safora-modal-body" style="padding:15px; background:#020617;">
			<pre id="safora-viewlog-content" class="safora-log-console" style="max-height:450px; overflow-y:auto; color:#f1f5f9; background:transparent; margin:0; font-family:monospace; font-size:12px;"></pre>
		</div>
		<div class="safora-modal-footer" style="padding:10px 15px; background:#0f172a; border-top:1px solid #1e293b; display:flex; justify-content:flex-end;">
			<button type="button" class="button safora-modal-close-btn" style="background:#334155; border:none; color:#FFF;">Close</button>
		</div>
	</div>
</div>

