/**
 * Affiliate Link Migration - Admin JS
 *
 * Handles AJAX batch processing loop, live progress bar updates, ETA calculation,
 * progress reset, and log clearing. Zero jQuery dependency.
 *
 * @package AffiliateLinkMigration
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {

		const startBtn = document.getElementById('lm-start-migration-btn');
		const resetBtn = document.getElementById('lm-reset-migration-btn');
		const clearLogsBtn = document.getElementById('lm-clear-logs-btn');

		let isMigrating = false;

		// Helper to update text in DOM elements safely
		function setEl(id, val) {
			const el = document.getElementById(id);
			if (el) {
				el.textContent = val;
			}
		}

		// 1. Batch Migration AJAX Loop
		if (startBtn) {
			startBtn.addEventListener('click', function () {
				if (isMigrating) {
					return;
				}

				const nonce = startBtn.getAttribute('data-nonce') || window.affiliateLmAdmin.nonce;

				isMigrating = true;
				startBtn.disabled = true;
				startBtn.innerHTML = '<span class="dashicons dashicons-update spin" style="margin-top:6px;"></span> Migrating...';
				setEl('lm-stat-status', 'Migrating in progress...');

				function runBatch() {
					const formData = new FormData();
					formData.append('action', 'affiliate_lm_run_batch');
					formData.append('nonce', nonce);

					fetch(window.affiliateLmAdmin.ajaxUrl, {
						method: 'POST',
						body: formData
					})
					.then(response => response.json())
					.then(data => {
						if (!data.success) {
							alert(data.data ? data.data.message : 'Batch migration encounter an error.');
							isMigrating = false;
							startBtn.disabled = false;
							startBtn.innerHTML = '<span class="dashicons dashicons-controls-play" style="margin-top:6px;"></span> Start Batch Migration';
							setEl('lm-stat-status', 'Paused on Error');
							return;
						}

						const res = data.data;

						// Update Stats & Progress Bar
						setEl('lm-stat-total', res.total);
						setEl('lm-stat-processed', res.processed);
						setEl('lm-stat-remaining', res.remaining);
						setEl('lm-stat-updated', res.updated_links);
						setEl('lm-stat-skipped', res.skipped);
						setEl('lm-stat-elapsed', res.elapsed_formatted);
						setEl('lm-stat-eta', res.eta_formatted);

						setEl('lm-progress-pct-text', res.percent + '%');

						const progressBar = document.getElementById('lm-progress-inner');
						if (progressBar) {
							progressBar.style.width = res.percent + '%';
						}

						const statusBadge = document.getElementById('lm-stat-status');
						if (statusBadge) {
							statusBadge.textContent = res.dry_run ? 'Dry Run Simulating...' : 'Migrating batch...';
							statusBadge.className = res.dry_run ? 'badge badge-info' : 'badge badge-warning';
						}

						// Check completion criteria
						if (!res.is_complete && res.batch_processed > 0) {
							setTimeout(runBatch, 250);
						} else {
							isMigrating = false;
							startBtn.disabled = true;
							startBtn.innerHTML = '<span class="dashicons dashicons-saved" style="margin-top:6px;"></span> Migration Complete';

							if (statusBadge) {
								statusBadge.textContent = res.dry_run ? 'Dry Run Simulation Complete 🎉' : 'Migration Completed Successfully 🎉';
								statusBadge.className = 'badge badge-success';
							}
						}
					})
					.catch(err => {
						isMigrating = false;
						startBtn.disabled = false;
						startBtn.innerHTML = '<span class="dashicons dashicons-controls-play" style="margin-top:6px;"></span> Resume Migration';
						setEl('lm-stat-status', 'Connection Error - Click Resume');
						console.error('Migration AJAX Error:', err);
					});
				}

				runBatch();
			});
		}

		// 2. Reset Migration State
		if (resetBtn) {
			resetBtn.addEventListener('click', function () {
				if (!confirm('Are you sure you want to reset migration progress? This allows re-running the migration from post 1.')) {
					return;
				}

				const nonce = resetBtn.getAttribute('data-nonce') || window.affiliateLmAdmin.nonce;
				const formData = new FormData();
				formData.append('action', 'affiliate_lm_reset_migration');
				formData.append('nonce', nonce);

				resetBtn.disabled = true;
				resetBtn.textContent = 'Resetting...';

				fetch(window.affiliateLmAdmin.ajaxUrl, {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					resetBtn.disabled = false;
					resetBtn.textContent = 'Reset Progress';

					if (data.success) {
						location.reload();
					} else {
						alert(data.data ? data.data.message : 'Reset failed.');
					}
				});
			});
		}

		// 3. Clear Logs Handler
		if (clearLogsBtn) {
			clearLogsBtn.addEventListener('click', function () {
				if (!confirm('Are you sure you want to clear all migration log entries?')) {
					return;
				}

				const nonce = clearLogsBtn.getAttribute('data-nonce') || window.affiliateLmAdmin.nonce;
				const formData = new FormData();
				formData.append('action', 'affiliate_lm_clear_logs');
				formData.append('nonce', nonce);

				clearLogsBtn.disabled = true;
				clearLogsBtn.textContent = 'Clearing...';

				fetch(window.affiliateLmAdmin.ajaxUrl, {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					clearLogsBtn.disabled = false;
					clearLogsBtn.textContent = 'Clear Logs';

					const msgDiv = document.getElementById('lm-log-message');
					if (data.success) {
						if (msgDiv) {
							msgDiv.innerHTML = '<div class="notice notice-success inline"><p>' + data.data.message + '</p></div>';
						}
						const tbody = document.getElementById('lm-log-tbody');
						if (tbody) {
							tbody.innerHTML = '<tr><td colspan="6"><em>No migration log entries recorded yet.</em></td></tr>';
						}
					}
				});
			});
		}

	});
})();
