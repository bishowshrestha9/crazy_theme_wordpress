<?php
/**
 * Safora Premium & Extensions Template (UpdraftPlus Parity)
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');
?>

<div class="updraft_premium safora_premium">
	<!-- Hero CTA Section -->
	<section>
		<div class="updraft_premium_cta udpdraft__lifted" style="background:#FFF; border:1px solid #c3c4c7; border-radius:4px; padding:24px; margin-bottom:20px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
			<div class="updraft_premium_cta__top" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:20px;">
				<div class="updraft_premium_cta__summary">
					<h2 id="premium-upgrade-header" style="margin:0 0 10px 0; font-size:22px; color:#1e293b;">
						Safora <strong>Premium</strong>
					</h2>
					<ul class="updraft_premium_description_list" style="display:flex; flex-wrap:wrap; gap:15px; margin:0; padding:0; list-style:none; font-size:13px;">
						<li><a target="_blank" href="https://safora.io" style="color:#2271b1; text-decoration:underline;">Full feature list</a></li>
						<li><a target="_blank" href="https://safora.io" style="color:#2271b1; text-decoration:underline;">Ask a pre-sales question</a></li>
						<li><a target="_blank" href="https://safora.io" style="color:#2271b1; text-decoration:underline;">Support</a></li>
						<li><a target="_blank" href="https://safora.io" style="color:#2271b1; text-decoration:underline;">Other great tools</a></li>
						<li><a target="_blank" href="https://safora.io" style="color:#2271b1; text-decoration:underline;">Documentation</a></li>
					</ul>
				</div>
				<div class="updraft_premium_cta__action">
					<a target="_blank" class="button button-primary button-hero" href="https://safora.io" style="background:#2563eb; border-color:#1d4ed8; font-size:15px; padding:8px 24px; height:auto; font-weight:600; text-decoration:none; display:inline-block;">
						<?php esc_html_e('Get it here', 'safora'); ?>
					</a>
					<div style="font-size:11px; color:#64748b; margin-top:4px; text-align:center;">
						<span class="dashicons dashicons-external" style="font-size:13px; vertical-align:middle;"></span> Goes to safora.io checkout page
					</div>
				</div>
			</div>
			<div class="updraft_premium_cta__bottom" style="margin-top:16px; padding-top:12px; border-top:1px solid #f1f5f9;">
				<p class="premium-upgrade-prompt" style="margin:0; font-size:13px; color:#475569;">
					<?php esc_html_e('You are currently using the community edition of Safora.', 'safora'); ?> 
					<a target="_blank" href="https://safora.io" style="color:#2271b1; text-decoration:underline;">
						<?php esc_html_e('If you have purchased from safora.io please follow our instructions on how to activate Safora Premium.', 'safora'); ?>
					</a>
				</p>
			</div>
		</div>
	</section>

	<!-- Feature Comparison Table -->
	<section>
		<h2 class="updraft_feat_table__title" style="font-size:18px; font-weight:700; color:#1e293b; margin:20px 0 12px 0;">
			<?php esc_html_e('Features comparison', 'safora'); ?>
		</h2>
		<table class="wp-list-table widefat striped updraft_feat_table" style="background:#FFF; border:1px solid #c3c4c7; border-collapse:collapse;">
			<thead>
				<tr class="updraft_feat_table__header" style="background:#f8fafc;">
					<th style="padding:15px; font-size:15px; font-weight:700; color:#1e293b; width:60%;">Feature</th>
					<th style="padding:15px; text-align:center; font-size:14px; font-weight:700; color:#1e293b; width:20%;">Free</th>
					<th style="padding:15px; text-align:center; font-size:14px; font-weight:700; color:#1e293b; width:20%;">Premium</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td></td>
					<td style="text-align:center;">
						<span class="installed" style="color:#16a34a; font-weight:600; display:inline-flex; align-items:center; gap:4px;">
							<span class="dashicons dashicons-yes"></span> Installed
						</span>
					</td>
					<td style="text-align:center;">
						<a class="button button-primary" href="https://safora.io" target="_blank" style="background:#2563eb; border-color:#1d4ed8; font-size:12px; padding:2px 14px;">
							Upgrade now
						</a>
					</td>
				</tr>
				<tr>
					<td style="padding:15px;">
						<h4 style="margin:0 0 6px 0; font-size:14px; font-weight:700; color:#1e293b;">Backup to remote storage locations</h4>
						<p style="margin:0; font-size:13px; color:#64748b; line-height:1.5;">
							To avoid server-wide risks, always backup to remote cloud storage. Free includes Dropbox, Google Drive, Amazon S3, Rackspace, FTP and more.
						</p>
					</td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-yes" style="color:#16a34a; font-size:22px;"></span></td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-yes" style="color:#16a34a; font-size:22px;"></span></td>
				</tr>
				<tr>
					<td style="padding:15px;">
						<h4 style="margin:0 0 6px 0; font-size:14px; font-weight:700; color:#1e293b;">Clone and migrate (SaforaClone &amp; Migrator)</h4>
						<p style="margin:0; font-size:13px; color:#64748b; line-height:1.5;">
							Clone your site in minutes directly onto temporary test servers or directly migrate between two hosts without file downloads.
						</p>
					</td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-no" style="color:#dc2626; font-size:22px;"></span></td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-yes" style="color:#16a34a; font-size:22px;"></span></td>
				</tr>
				<tr>
					<td style="padding:15px;">
						<h4 style="margin:0 0 6px 0; font-size:14px; font-weight:700; color:#1e293b;">Fast, personal support</h4>
						<p style="margin:0; font-size:13px; color:#64748b; line-height:1.5;">
							Direct ticketing access to core developers and engineers for rapid issue resolution.
						</p>
					</td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-no" style="color:#dc2626; font-size:22px;"></span></td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-yes" style="color:#16a34a; font-size:22px;"></span></td>
				</tr>
				<tr>
					<td style="padding:15px;">
						<h4 style="margin:0 0 6px 0; font-size:14px; font-weight:700; color:#1e293b;">Incremental backups</h4>
						<p style="margin:0; font-size:13px; color:#64748b; line-height:1.5;">
							Allows you to only backup files (e.g. new images or files) that have been modified since the last backup set.
						</p>
					</td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-no" style="color:#dc2626; font-size:22px;"></span></td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-yes" style="color:#16a34a; font-size:22px;"></span></td>
				</tr>
				<tr>
					<td style="padding:15px;">
						<h4 style="margin:0 0 6px 0; font-size:14px; font-weight:700; color:#1e293b;">Extended remote storage providers</h4>
						<p style="margin:0; font-size:13px; color:#64748b; line-height:1.5;">
							Includes Microsoft OneDrive, SFTP / SCP, WebDAV, Microsoft Azure, Backblaze B2, Google Cloud Storage, and pCloud.
						</p>
					</td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-no" style="color:#dc2626; font-size:22px;"></span></td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-yes" style="color:#16a34a; font-size:22px;"></span></td>
				</tr>
				<tr>
					<td style="padding:15px;">
						<h4 style="margin:0 0 6px 0; font-size:14px; font-weight:700; color:#1e293b;">Automatic backup before updates</h4>
						<p style="margin:0; font-size:13px; color:#64748b; line-height:1.5;">
							Automatically takes a safety snapshot before any WordPress core, plugin, or theme update is executed.
						</p>
					</td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-no" style="color:#dc2626; font-size:22px;"></span></td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-yes" style="color:#16a34a; font-size:22px;"></span></td>
				</tr>
				<tr>
					<td style="padding:15px;">
						<h4 style="margin:0 0 6px 0; font-size:14px; font-weight:700; color:#1e293b;">Non-WordPress files and external databases</h4>
						<p style="margin:0; font-size:13px; color:#64748b; line-height:1.5;">
							Backup external MySQL databases and non-WordPress directories on your web server into your backup sets.
						</p>
					</td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-no" style="color:#dc2626; font-size:22px;"></span></td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-yes" style="color:#16a34a; font-size:22px;"></span></td>
				</tr>
				<tr>
					<td style="padding:15px;">
						<h4 style="margin:0 0 6px 0; font-size:14px; font-weight:700; color:#1e293b;">WordPress Multisite / Network support</h4>
						<p style="margin:0; font-size:13px; color:#64748b; line-height:1.5;">
							Backup and restore full WordPress multisite installations, or backup individual sub-sites as standalone sites.
						</p>
					</td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-no" style="color:#dc2626; font-size:22px;"></span></td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-yes" style="color:#16a34a; font-size:22px;"></span></td>
				</tr>
				<tr>
					<td style="padding:15px;">
						<h4 style="margin:0 0 6px 0; font-size:14px; font-weight:700; color:#1e293b;">WP-CLI command line interface</h4>
						<p style="margin:0; font-size:13px; color:#64748b; line-height:1.5;">
							Run backups, restorations, and cloud transfers directly from your server SSH terminal.
						</p>
					</td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-yes" style="color:#16a34a; font-size:22px;"></span></td>
					<td style="text-align:center; vertical-align:middle;"><span class="dashicons dashicons-yes" style="color:#16a34a; font-size:22px;"></span></td>
				</tr>
			</tbody>
		</table>
	</section>
</div>
