/**
 * Affiliate Journey Tracker - Admin JS (Forwarding Engine)
 *
 * Handles AJAX interactions for simulation tester, trace logs, migration tool, and diagnostics.
 * Zero jQuery dependency.
 *
 * @package AffiliateJourneyTracker
 */

(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {

		// 1. Forwarding Simulator Handler
		const runSimBtn = document.getElementById('jt-run-forwarding-sim');
		if (runSimBtn) {
			runSimBtn.addEventListener('click', function () {
				const urlInput = document.getElementById('jt-sim-url-input');
				const cookieInput = document.getElementById('jt-sim-cookie-input');

				const prettyUrl = urlInput ? urlInput.value.trim() : '';
				const cookieSlug = cookieInput ? cookieInput.value.trim() : '';

				if (!prettyUrl) {
					alert('Please enter a Pretty Link or Page URL to simulate.');
					return;
				}

				const nonce = runSimBtn.getAttribute('data-nonce') || window.affiliateJtAdmin.nonce;
				const formData = new FormData();
				formData.append('action', 'affiliate_jt_simulate_forwarding');
				formData.append('nonce', nonce);
				formData.append('url', prettyUrl);
				formData.append('cookie_slug', cookieSlug);

				runSimBtn.disabled = true;
				runSimBtn.textContent = 'Simulating...';

				fetch(window.affiliateJtAdmin.ajaxUrl, {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					runSimBtn.disabled = false;
					runSimBtn.textContent = 'Run Simulation';

					if (data.success) {
						const res = data.data;
						const setEl = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
						setEl('sim-res-req-url', res.page_url);
						setEl('sim-res-orig-dest', res.original_href);
						setEl('sim-res-cookie-json', res.cookie_json);
						setEl('sim-res-journey-slug', res.journey_slug);
						setEl('sim-res-mod-dest', res.modified_href);
						setEl('sim-res-status', res.status);
						if (res.exec_time) setEl('sim-res-time', res.exec_time);
					} else {
						alert(data.data ? data.data.message : 'Simulation failed.');
					}
				})
				.catch(err => {
					runSimBtn.disabled = false;
					runSimBtn.textContent = 'Run Simulation';
					console.error('Simulation error:', err);
				});
			});
		}

		// 2. Interactive URL Sandbox Generator Handler
		const runUrlTestBtn = document.getElementById('jt-run-url-test');
		if (runUrlTestBtn) {
			runUrlTestBtn.addEventListener('click', function () {
				const urlInput = document.getElementById('jt-test-url-input');
				const originInput = document.getElementById('jt-test-origin-input');

				const targetUrl = urlInput ? urlInput.value.trim() : '';
				const originSlug = originInput ? originInput.value.trim() : '';

				if (!targetUrl) {
					alert('Please enter a target URL to test.');
					return;
				}

				const formData = new FormData();
				formData.append('action', 'affiliate_jt_test_url');
				formData.append('nonce', window.affiliateJtAdmin.nonce);
				formData.append('url', targetUrl);
				formData.append('origin', originSlug);

				runUrlTestBtn.disabled = true;
				runUrlTestBtn.textContent = 'Generating...';

				fetch(window.affiliateJtAdmin.ajaxUrl, {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					runUrlTestBtn.disabled = false;
					runUrlTestBtn.textContent = 'Generate Tracked URL';

					if (data.success) {
						const beforeEl = document.getElementById('jt-before-url');
						const afterEl = document.getElementById('jt-after-url');
						if (beforeEl) beforeEl.textContent = data.data.original_url;
						if (afterEl) afterEl.textContent = data.data.generated_url;
					} else {
						alert(data.data ? data.data.message : 'Failed to generate test URL.');
					}
				})
				.catch(err => {
					runUrlTestBtn.disabled = false;
					runUrlTestBtn.textContent = 'Generate Tracked URL';
				});
			});
		}

		// 3. Clear Redirect Traces Handler
		const clearTracesBtn = document.getElementById('jt-clear-traces-btn');
		if (clearTracesBtn) {
			clearTracesBtn.addEventListener('click', function () {
				if (!confirm('Are you sure you want to clear all redirect trace logs?')) {
					return;
				}

				const nonce = clearTracesBtn.getAttribute('data-nonce');
				const formData = new FormData();
				formData.append('action', 'affiliate_jt_clear_traces');
				formData.append('nonce', nonce);

				clearTracesBtn.disabled = true;
				clearTracesBtn.textContent = 'Clearing...';

				fetch(window.affiliateJtAdmin.ajaxUrl, {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					clearTracesBtn.disabled = false;
					clearTracesBtn.textContent = 'Clear Redirect Traces';

					const msgDiv = document.getElementById('jt-trace-message');
					if (data.success) {
						if (msgDiv) {
							msgDiv.innerHTML = '<div class="notice notice-success inline"><p>' + data.data.message + '</p></div>';
						}
						const tbody = document.getElementById('jt-trace-tbody');
						if (tbody) {
							tbody.innerHTML = '<tr><td colspan="9"><em>No redirect trace entries recorded yet.</em></td></tr>';
						}
					} else {
						if (msgDiv) {
							msgDiv.innerHTML = '<div class="notice notice-error inline"><p>' + (data.data ? data.data.message : 'Error clearing trace logs.') + '</p></div>';
						}
					}
				});
			});
		}

		// 4. Clear System Logs Handler
		const clearLogsBtn = document.getElementById('jt-clear-logs-btn');
		if (clearLogsBtn) {
			clearLogsBtn.addEventListener('click', function () {
				if (!confirm('Are you sure you want to clear all system and click logs?')) {
					return;
				}

				const nonce = clearLogsBtn.getAttribute('data-nonce') || window.affiliateJtAdmin.nonce;
				const formData = new FormData();
				formData.append('action', 'affiliate_jt_clear_logs');
				formData.append('nonce', nonce);

				clearLogsBtn.disabled = true;
				clearLogsBtn.textContent = 'Clearing...';

				fetch(window.affiliateJtAdmin.ajaxUrl, {
					method: 'POST',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					clearLogsBtn.disabled = false;
					clearLogsBtn.textContent = 'Clear Logs';

					const msgDiv = document.getElementById('jt-log-message');
					if (data.success) {
						if (msgDiv) {
							msgDiv.innerHTML = '<div class="notice notice-success inline"><p>' + data.data.message + '</p></div>';
						}
						const tbody = document.getElementById('jt-log-tbody');
						if (tbody) {
							tbody.innerHTML = '<tr><td colspan="6"><em>No log entries recorded yet.</em></td></tr>';
						}
					} else {
						if (msgDiv) {
							msgDiv.innerHTML = '<div class="notice notice-error inline"><p>' + (data.data ? data.data.message : 'Error clearing logs.') + '</p></div>';
						}
					}
				});
			});
		}

		// 5. Batch Migration Tool Handler
		const runMigrationBtn = document.getElementById('jt-run-migration-btn');
		if (runMigrationBtn) {
			let totalTopAssigned = 0;
			let totalReviewsAssigned = 0;
			let totalSkipped = 0;
			let totalErrors = 0;

			runMigrationBtn.addEventListener('click', function () {
				if (!confirm('Start automatic batch migration for affiliate_content_type post meta?')) {
					return;
				}

				const nonce = runMigrationBtn.getAttribute('data-nonce');

				runMigrationBtn.disabled = true;
				runMigrationBtn.innerHTML = '<span class="dashicons dashicons-update spin"></span> Migrating...';

				totalTopAssigned = 0;
				totalReviewsAssigned = 0;
				totalSkipped = 0;
				totalErrors = 0;

				function executeBatch() {
					const formData = new FormData();
					formData.append('action', 'affiliate_jt_run_migration_batch');
					formData.append('nonce', nonce);

					fetch(window.affiliateJtAdmin.ajaxUrl, {
						method: 'POST',
						body: formData
					})
					.then(response => response.json())
					.then(data => {
						if (!data.success) {
							alert(data.data ? data.data.message : 'Batch migration failed.');
							runMigrationBtn.disabled = false;
							runMigrationBtn.innerHTML = '<span class="dashicons dashicons-update"></span> Run Migration';
							return;
						}

						const res = data.data;
						totalTopAssigned += res.top_assigned;
						totalReviewsAssigned += res.review_assigned;
						totalSkipped += res.skipped;
						totalErrors += res.errors;

						const stats = res.stats;

						const statTotal = document.getElementById('jt-mig-stat-total');
						const statClassified = document.getElementById('jt-mig-stat-classified');
						const statRemaining = document.getElementById('jt-mig-stat-remaining');
						const progressBarInner = document.getElementById('jt-mig-progress-inner');
						const progressBarText = document.getElementById('jt-mig-progress-text');

						if (statTotal) statTotal.textContent = stats.total;
						if (statClassified) statClassified.textContent = stats.classified;
						if (statRemaining) statRemaining.textContent = stats.remaining;

						const pct = stats.total > 0 ? Math.min(100, Math.round((stats.classified / stats.total) * 100)) : 100;
						if (progressBarInner) progressBarInner.style.width = pct + '%';
						if (progressBarText) progressBarText.textContent = pct + '%';

						if (stats.remaining > 0 && res.batch_processed > 0) {
							setTimeout(executeBatch, 300);
						} else {
							runMigrationBtn.disabled = true;
							runMigrationBtn.innerHTML = '<span class="dashicons dashicons-saved"></span> Migration Complete';

							const summaryCard = document.getElementById('jt-mig-summary-card');
							if (summaryCard) summaryCard.style.display = 'block';
						}
					});
				}

				executeBatch();
			});
		}

	});
})();
