<?php
/**
 * Safora Restoration Wizard Modal
 *
 * @package Safora
 */

if (!defined('ABSPATH')) die('No direct access allowed');
?>

<div id="safora-restore-modal" class="safora-modal" style="display:none;">
	<div class="safora-modal-overlay"></div>
	<div class="safora-modal-dialog">
		<div class="safora-modal-header">
			<h2><span class="dashicons dashicons-undo"></span> Restore Backup Set</h2>
			<button type="button" class="safora-modal-close">&times;</button>
		</div>
		<div class="safora-modal-body">
			<!-- Step 1: Entity Selection -->
			<div id="safora-restore-step-1">
				<p>Choose which components of this backup set to restore to your website:</p>
				<div class="safora-restore-checkboxes" id="safora-restore-checkboxes-container">
					<!-- Populated dynamically by JS -->
				</div>

				<div class="safora-restore-passphrase-box" id="safora-restore-passphrase-box" style="margin-top: 15px; display:none;">
					<label for="safora_restore_pass"><strong>Decryption Passphrase:</strong></label>
					<input type="password" id="safora_restore_pass" class="regular-text" placeholder="Enter encryption password">
				</div>

				<div class="notice notice-warning inline" style="margin-top: 15px;">
					<p><strong>Warning:</strong> Restoring will overwrite existing website files and database tables. Ensure you have an up-to-date backup before proceeding.</p>
				</div>
			</div>

			<!-- Step 2: Live Restore Progress -->
			<div id="safora-restore-step-2" style="display:none;">
				<div class="safora-progress-bar-container">
					<div class="safora-progress-bar-fill" id="safora-restore-progress-bar" style="width: 50%;"></div>
				</div>
				<p class="safora-live-status-text" id="safora-restore-status-text">
					<span class="spinner is-active" style="float:none; margin:0 5px 0 0;"></span> Restoring components... Please do not close this window.
				</p>
				<pre id="safora-restore-log-console" class="safora-log-console"></pre>
			</div>
		</div>
		<div class="safora-modal-footer">
			<button type="button" class="button safora-modal-close-btn">Cancel</button>
			<button type="button" class="button button-primary" id="safora-confirm-restore-btn">Proceed with Restore</button>
		</div>
	</div>
</div>
