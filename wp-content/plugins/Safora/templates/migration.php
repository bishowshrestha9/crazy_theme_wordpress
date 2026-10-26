<?php
/**
 * Safora Migration & Cloning Template (UpdraftPlus Parity)
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');

$site_key = get_option('safora_migration_secret_key');
if (!$site_key) {
	$site_key = wp_generate_password(32, false);
	update_option('safora_migration_secret_key', $site_key);
}
?>

<div class="safora-migration-container">

	<!-- 1. Temporary Clone Section (UpdraftClone Parity) -->
	<h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:15px 0 8px 0;">Create a temporary clone on our servers (SaforaClone)</h3>
	<div class="safora-clone-box postbox" style="background:#FFF; border:1px solid #c3c4c7; padding:22px 25px; border-radius:3px; margin-bottom:25px;">
		<div style="font-size:15px; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:6px; margin-bottom:12px;">
			<span class="dashicons dashicons-media-document" style="font-size:18px;"></span> SaforaClone
		</div>

		<p style="font-size:13px; color:#334155; line-height:1.6; margin-top:0;">
			A temporary clone is an instant copy of this website, running on our servers. Rather than test things on your live site, you can SaforaClone it, and then throw away your clone when done. 
			<a href="#" onclick="alert('SaforaClone documentation and guide.'); return false;" style="color:#2271b1; text-decoration:underline;">Find out more here</a>. 
			<a href="#" onclick="alert('Frequently Asked Questions.'); return false;" style="color:#2271b1; text-decoration:underline;">Read FAQs here</a>. 
			<a href="#" onclick="alert('Account token shop.'); return false;" style="color:#2271b1; text-decoration:underline;">You can buy SaforaClone tokens from our shop here</a>.
		</p>

		<ul style="font-size:13px; color:#334155; line-height:1.8; margin:14px 0 18px 18px; list-style-type:disc;">
			<li><strong>Easy:</strong> Press the buttons... SaforaClone does the work.</li>
			<li><strong>Reliable:</strong> Runs on capacity from a leading cloud computing provider.</li>
			<li><strong>Secure:</strong> One VPS (Virtual Private Server) per clone, shared with nobody.</li>
			<li><strong>Fast:</strong> Takes just the time needed to create a backup and send it.</li>
			<li><strong>Flexible:</strong> If you want, test upgrading to a different PHP or WP version.</li>
		</ul>

		<div style="margin-bottom:14px;">
			<button type="button" class="button button-primary" id="safora-start-clone-btn" onclick="alert('SaforaClone: Connecting to cloud cluster to initiate instant staging clone...');" style="background:#2563eb; border-color:#1d4ed8; font-size:14px; padding:6px 20px; height:auto; font-weight:600; border-radius:4px; display:inline-flex; align-items:center; gap:6px;">
				<span class="dashicons dashicons-media-document"></span> Create a temporary clone on our servers (SaforaClone)
			</button>
		</div>

		<p style="font-size:12px; color:#64748b; margin:0;">
			To create a temporary clone you need credit in your account. <a href="#" style="color:#2271b1; text-decoration:underline;" onclick="alert('Token shop'); return false;">You can buy SaforaClone tokens from our shop here</a>.
		</p>
	</div>

	<!-- 2. Migration Section (Hosting You Control) -->
	<h3 style="font-size:16px; font-weight:600; color:#1e293b; margin:0 0 8px 0;">Migrate (create a copy of a site on hosting you control)</h3>
	<div class="safora-migrate-box postbox" style="background:#FFF; border:1px solid #c3c4c7; padding:22px 25px; border-radius:3px;">
		<p style="font-size:13px; color:#334155; line-height:1.6; margin-top:0;">
			A "migration" is ultimately the same as a restoration - but using backup archives that you import from another site. The Safora Migrator modifies the restoration operation appropriately, to fit the backup data to the new site. 
			<a href="#" onclick="alert('Step-by-step site migration tutorial.'); return false;" style="color:#2271b1; text-decoration:underline;">Read this article to see step-by-step how it's done</a>.
		</p>

		<!-- 3 Big Action Buttons (Screenshot 3 Parity) -->
		<div class="safora-migrate-buttons-row" style="display:flex; flex-wrap:wrap; gap:12px; margin-top:18px;">
			<button type="button" class="button button-primary safora-migrate-action-btn" id="safora-migrate-btn-restore" style="background:#2563eb; border-color:#1d4ed8; font-size:13px; padding:8px 18px; height:auto; font-weight:600; border-radius:4px; display:inline-flex; align-items:center; gap:8px;">
				<span class="dashicons dashicons-undo"></span> Restore an existing backup set onto this site
			</button>

			<button type="button" class="button button-primary safora-migrate-action-btn" id="safora-migrate-btn-send" style="background:#2563eb; border-color:#1d4ed8; font-size:13px; padding:8px 18px; height:auto; font-weight:600; border-radius:4px; display:inline-flex; align-items:center; gap:8px;">
				<span class="dashicons dashicons-upload"></span> Send a backup to another site
			</button>

			<button type="button" class="button button-primary safora-migrate-action-btn" id="safora-migrate-btn-receive" style="background:#2563eb; border-color:#1d4ed8; font-size:13px; padding:8px 18px; height:auto; font-weight:600; border-radius:4px; display:inline-flex; align-items:center; gap:8px;">
				<span class="dashicons dashicons-download"></span> Receive a backup from a remote site
			</button>
		</div>

		<!-- Panel: Send backup to another site (Interactive Drawer) -->
		<div id="safora-migrate-panel-send" style="display:none; margin-top:20px; background:#f8fafc; border:1px solid #cbd5e1; border-radius:4px; padding:20px;">
			<h4 style="margin-top:0; font-size:14px; font-weight:600; color:#1e293b;"><span class="dashicons dashicons-upload"></span> Push / Send Backup Directly to Remote Destination</h4>
			<p style="font-size:13px; color:#475569;">Enter the Destination Site URL and Secret Migration Key from the receiving site, then click "Push &amp; Migrate Site":</p>

			<form method="post" action="" id="safora-remote-push-form">
				<table class="form-table" style="margin-top:0;">
					<tr>
						<th style="width:180px;"><label for="safora_remote_target">Destination Site URL:</label></th>
						<td><input type="url" id="safora_remote_target" class="regular-text" placeholder="https://staging.example.com" required></td>
					</tr>
					<tr>
						<th><label for="safora_remote_key">Destination Migration Key:</label></th>
						<td><input type="password" id="safora_remote_key" class="regular-text" placeholder="Paste migration key" required></td>
					</tr>
				</table>
				<p class="submit" style="margin-bottom:0; padding-bottom:0;">
					<button type="button" class="button button-primary button-large" id="safora-send-remote-btn">Push &amp; Migrate Site</button>
				</p>
			</form>

			<div id="safora-migration-live-status" style="display:none; margin-top:20px; border-top:1px solid #e2e8f0; padding-top:15px;">
				<div style="font-weight:600; font-size:13px; color:#1e293b; margin-bottom:6px;" id="safora-migration-status-msg">Starting migration job...</div>
				<div style="height:10px; background:#e2e8f0; border-radius:5px; overflow:hidden; margin-bottom:10px;">
					<div id="safora-migration-progress-bar" style="width:10%; height:100%; background:#2563eb; transition:width 0.3s;"></div>
				</div>
				<textarea id="safora-migration-live-console" readonly style="width:100%; height:180px; font-family:monospace; font-size:12px; background:#0f172a; color:#f8fafc; padding:10px; border-radius:4px; line-height:1.4;"></textarea>
			</div>
		</div>

		<!-- Panel: Receive backup from a remote site (Interactive Drawer) -->
		<div id="safora-migrate-panel-receive" style="display:none; margin-top:20px; background:#f8fafc; border:1px solid #cbd5e1; border-radius:4px; padding:20px;">
			<h4 style="margin-top:0; font-size:14px; font-weight:600; color:#1e293b;"><span class="dashicons dashicons-download"></span> Credentials to Receive Backups on this Site</h4>
			<p style="font-size:13px; color:#475569;">To receive a direct clone or backup onto this site from another WordPress installation, copy these credentials into the sender site:</p>

			<table class="form-table" style="margin-top:0;">
				<tr>
					<th style="width:180px;">This Site URL:</th>
					<td><code><?php echo esc_url(site_url()); ?></code></td>
				</tr>
				<tr>
					<th>Migration Key:</th>
					<td>
						<code style="font-size:13px; padding:4px 8px;"><?php echo esc_html($site_key); ?></code>
						<button type="button" class="button button-small" onclick="navigator.clipboard.writeText('<?php echo esc_js($site_key); ?>'); alert('Key copied to clipboard!');">Copy Key</button>
					</td>
				</tr>
			</table>
		</div>

		<!-- Panel: Restore existing backup (Interactive Drawer) -->
		<div id="safora-migrate-panel-restore" style="display:none; margin-top:20px; background:#f8fafc; border:1px solid #cbd5e1; border-radius:4px; padding:20px;">
			<h4 style="margin-top:0; font-size:14px; font-weight:600; color:#1e293b;"><span class="dashicons dashicons-undo"></span> Restore Backup Set Onto This Site</h4>
			<p style="font-size:13px; color:#475569;">You can upload archives exported from another WordPress site, or select existing backup sets from the dashboard table:</p>
			<a href="<?php echo esc_url(admin_url('options-general.php?page=safora&tab=dashboard')); ?>" class="button button-secondary">Go to Backup / Restore Dashboard</a>
		</div>

	</div>
</div>
