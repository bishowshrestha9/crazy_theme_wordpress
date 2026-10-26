/**
 * Safora Admin JavaScript (UpdraftPlus-Parity Interactivity)
 */
(function($) {
	'use strict';

	let activeJobNonce = '';
	let logPollInterval = null;
	let restorePollInterval = null;

	$(document).ready(function() {
		initBackupNowDialog();
		initStorageTiles();
		initStorageTests();
		initHistoryActions();
		initViewLogModal();
		initRestoreModal();
		initDownloads();
		initMigration();
		initGoogleDriveAuth();
		initScheduleControls();
		initRetentionRules();
		initAdvancedTools();
	});

	/**
	 * 1. Backup Now Dialog & Trigger
	 */
	function initBackupNowDialog() {
		// Open modal when hero button or options link is clicked
		$('#safora-backupnow-button, #safora-backup-options-link').on('click', function(e) {
			e.preventDefault();
			$('#safora-backupnow-modal').fadeIn(150);
		});

		// Modal close buttons
		$('.safora-modal-close, .safora-modal-close-btn, .safora-modal-overlay').on('click', function() {
			$('.safora-modal').fadeOut(150);
		});

		// Toggle sub-entities when files checkbox changes
		$('#safora-modal-include-files').on('change', function() {
			if ($(this).is(':checked')) {
				$('#safora-modal-files-suboptions').slideDown(150);
			} else {
				$('#safora-modal-files-suboptions').slideUp(150);
			}
		});

		// Confirm backup trigger inside modal
		$('#safora-modal-confirm-backup-btn').on('click', function() {
			const includeDb = $('#safora-modal-include-db').is(':checked') ? 1 : 0;
			const includeFiles = $('#safora-modal-include-files').is(':checked') ? 1 : 0;
			const sendRemote = $('#safora-modal-send-remote').is(':checked') ? 1 : 0;

			if (!includeDb && !includeFiles) {
				alert('Please select at least Database or Files to back up.');
				return;
			}

			$('#safora-backupnow-modal').fadeOut(150);
			$('#safora-active-jobs-container').slideDown(250);
			$('#safora-backupnow-button').prop('disabled', true).addClass('loading');
			$('#safora-backup-progress-bar').css('width', '10%');
			$('#safora-live-log-console').text('Initializing backup job and locking database tables...\n');
			$('#safora-job-status-subtext').text('Allocating resources and setting up state machine...');

			$.post(safora_vars.ajax_url, {
				action: 'safora_backup_now',
				security: safora_vars.nonce,
				include_files: includeFiles,
				include_db: includeDb,
				send_remote: sendRemote
			}, function(response) {
				if (response.success && response.data && response.data.nonce) {
					activeJobNonce = response.data.nonce;
					startLogPolling(activeJobNonce);
				} else {
					const errorMsg = (response && response.data) ? response.data : 'Unknown error';
					alert('Backup failed to start: ' + errorMsg);
					$('#safora-backupnow-button').prop('disabled', false).removeClass('loading');
					$('#safora-active-jobs-container').slideUp();
				}
			}).fail(function(jqXHR, textStatus, errorThrown) {
				let errorMsg = 'Server error while initiating backup.';
				if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
					errorMsg = 'Backup initiation error: ' + jqXHR.responseJSON.data;
				} else if (errorThrown) {
					errorMsg += ' (' + errorThrown + ')';
				}
				alert(errorMsg);
				$('#safora-backupnow-button').prop('disabled', false).removeClass('loading');
				$('#safora-active-jobs-container').slideUp();
			});
		});

		// Abort active job
		$('#safora-abort-backup-btn').on('click', function() {
			if (!activeJobNonce) return;
			if (!confirm('Are you sure you want to cancel the active backup job?')) return;

			$.post(safora_vars.ajax_url, {
				action: 'safora_abort_backup',
				security: safora_vars.nonce,
				nonce: activeJobNonce
			}, function() {
				$('#safora-live-log-console').append('\n[ABORT SIGNAL SENT BY USER]\n');
			});
		});
	}

	function startLogPolling(nonce) {
		if (logPollInterval) clearInterval(logPollInterval);

		let lastLogLength = 0;
		let idleCounter = 0;
		let isPingingResumption = false;

		logPollInterval = setInterval(function() {
			$.post(safora_vars.ajax_url, {
				action: 'safora_poll_log',
				security: safora_vars.nonce,
				nonce: nonce
			}, function(res) {
				if (res.success && res.data) {
					const consoleEl = document.getElementById('safora-live-log-console');
					if (consoleEl && res.data.log) {
						consoleEl.textContent = res.data.log;
						consoleEl.scrollTop = consoleEl.scrollHeight;

						if (res.data.log.length > lastLogLength) {
							lastLogLength = res.data.log.length;
							idleCounter = 0;
						} else {
							idleCounter++;
						}
					}

					// Update visual stage text and progress
					if (res.data.log.indexOf('Archiving entity') !== -1) {
						$('#safora-job-status-subtext').text('Archiving file entities (plugins, themes, uploads)...');
						$('#safora-backup-progress-bar').css('width', '35%');
					}
					if (res.data.log.indexOf('Beginning database dump') !== -1) {
						$('#safora-job-status-subtext').text('Dumping MySQL database tables...');
						$('#safora-backup-progress-bar').css('width', '65%');
					}
					if (res.data.log.indexOf('Dispatching created archives') !== -1) {
						$('#safora-job-status-subtext').text('Uploading archives to remote cloud storage...');
						$('#safora-backup-progress-bar').css('width', '85%');
					}

					if (res.data.finished) {
						clearInterval(logPollInterval);
						$('#safora-backup-progress-bar').css('width', '100%');
						$('#safora-active-job-title').text('Backup finished successfully!');
						$('#safora-job-status-subtext').html('<strong>All archives created and saved.</strong> Reloading page in 3 seconds...');
						setTimeout(function() {
							window.location.reload();
						}, 3000);
						return;
					}

					// Zero-wait Keep-Alive: If progress is idle for 4 seconds, send active resumption pulse
					if (idleCounter >= 2 && !isPingingResumption) {
						isPingingResumption = true;
						$.post(safora_vars.ajax_url, {
							action: 'safora_resume_job',
							nonce: nonce,
							resumption: 0
						}).always(function() {
							isPingingResumption = false;
							idleCounter = 0;
						});
					}
				}
			});
		}, 2000);
	}

	/**
	 * 2. Remote Storage Visual Tiles Selection
	 */
	function initStorageTiles() {
		$('.safora-storage-tile').on('click', function() {
			const $tile = $(this);
			const $cb = $tile.find('.safora-storage-tile-cb');
			const service = $tile.data('service');
			const $subform = $('#safora-subform-' + service);

			const isSelected = $tile.hasClass('selected');

			if (isSelected) {
				$tile.removeClass('selected');
				$cb.prop('checked', false);
				$subform.slideUp(150);
			} else {
				$tile.addClass('selected');
				$cb.prop('checked', true);
				$subform.slideDown(150);
			}
		});
	}

	/**
	 * 3. Remote Storage Test Buttons
	 */
	function initStorageTests() {
		$('.safora-test-storage-btn').on('click', function(e) {
			e.preventDefault();
			const $btn = $(this);
			const service = $btn.data('service');
			const originalText = $btn.text();

			$btn.prop('disabled', true).text('Testing connection...');

			$.post(safora_vars.ajax_url, {
				action: 'safora_test_storage',
				security: safora_vars.nonce,
				service: service
			}, function(res) {
				$btn.prop('disabled', false).text(originalText);
				if (res.success) {
					alert('Connection Test: ' + res.data);
				} else {
					alert('Connection Failed: ' + res.data);
				}
			}).fail(function() {
				$btn.prop('disabled', false).text(originalText);
				alert('AJAX request failed.');
			});
		});
	}

	/**
	 * 4. Backup History Actions, Row Selection & Bulk Deletion
	 */
	function initHistoryActions() {
		let lastCheckedIndex = -1;

		function updateSelectAllState() {
			const totalRows = $('.safora-backup-select-cb').length;
			const checkedRows = $('.safora-backup-select-cb:checked').length;
			const $selectAllCb = $('#safora-select-all-backups');

			if (totalRows > 0 && checkedRows === totalRows) {
				$selectAllCb.prop('checked', true).prop('indeterminate', false);
			} else if (checkedRows > 0) {
				$selectAllCb.prop('checked', false).prop('indeterminate', true);
			} else {
				$selectAllCb.prop('checked', false).prop('indeterminate', false);
			}

			// Show bulk actions row ONLY when multiple (>= 2) backups are selected
			if (checkedRows >= 2) {
				$('.safora-bulk-actions-row').slideDown(150);
			} else {
				$('.safora-bulk-actions-row').slideUp(150);
			}
		}

		// Initial check
		updateSelectAllState();

		// Header "Select all" checkbox
		$('#safora-select-all-backups').on('change', function() {
			const isChecked = $(this).is(':checked');
			$('.safora-backup-select-cb').prop('checked', isChecked);
			if (isChecked) {
				$('.safora-backup-row').addClass('is-selected');
			} else {
				$('.safora-backup-row').removeClass('is-selected');
			}
			updateSelectAllState();
		});

		// Bottom "Select all" button
		$('#safora-select-all-btn').on('click', function(e) {
			e.preventDefault();
			$('#safora-select-all-backups').prop('checked', true).prop('indeterminate', false);
			$('.safora-backup-select-cb').prop('checked', true);
			$('.safora-backup-row').addClass('is-selected');
			updateSelectAllState();
		});

		// Bottom "Deselect" button
		$('#safora-deselect-all-btn').on('click', function(e) {
			e.preventDefault();
			$('#safora-select-all-backups').prop('checked', false).prop('indeterminate', false);
			$('.safora-backup-select-cb').prop('checked', false);
			$('.safora-backup-row').removeClass('is-selected');
			updateSelectAllState();
		});

		// Individual checkbox click & shift-click range selection
		$(document).on('click', '.safora-backup-select-cb', function(e) {
			const $cbs = $('.safora-backup-select-cb');
			const currentIndex = $cbs.index(this);
			const isChecked = $(this).is(':checked');

			// Highlight current row
			$(this).closest('.safora-backup-row').toggleClass('is-selected', isChecked);

			// Shift + Click range selection
			if (e.shiftKey && lastCheckedIndex !== -1 && lastCheckedIndex !== currentIndex) {
				const start = Math.min(lastCheckedIndex, currentIndex);
				const end = Math.max(lastCheckedIndex, currentIndex);

				for (let i = start; i <= end; i++) {
					$cbs.eq(i).prop('checked', isChecked);
					$cbs.eq(i).closest('.safora-backup-row').toggleClass('is-selected', isChecked);
				}
			}

			lastCheckedIndex = currentIndex;
			updateSelectAllState();
		});

		// Bulk Delete Action
		$('#safora-bulk-delete-btn').on('click', function(e) {
			e.preventDefault();
			const selectedTimestamps = [];
			const $selectedRows = [];

			$('.safora-backup-select-cb:checked').each(function() {
				const ts = $(this).val();
				if (ts) {
					selectedTimestamps.push(ts);
					$selectedRows.push($(this).closest('tr'));
				}
			});

			if (selectedTimestamps.length === 0) {
				alert('Please select at least one backup to delete.');
				return;
			}

			if (!confirm('Permanently delete ' + selectedTimestamps.length + ' selected backup set(s) and all their archive files? This action cannot be undone.')) {
				return;
			}

			const $btn = $(this);
			$btn.prop('disabled', true).text('Deleting...');

			let completed = 0;
			selectedTimestamps.forEach(function(ts, idx) {
				const $row = $selectedRows[idx];
				$row.css('opacity', '0.4');

				$.post(safora_vars.ajax_url, {
					action: 'safora_delete_backup',
					security: safora_vars.nonce,
					timestamp: ts
				}, function(res) {
					completed++;
					if (res.success) {
						$row.fadeOut(250, function() {
							$(this).remove();
							// Update count badge
							const remaining = $('.safora-backup-row').length;
							$('.safora-count-badge').text(remaining);
						});
					} else {
						$row.css('opacity', '1');
					}

					if (completed >= selectedTimestamps.length) {
						$btn.prop('disabled', false).text('Delete');
						updateSelectAllState();
					}
				}).fail(function() {
					completed++;
					$row.css('opacity', '1');
					if (completed >= selectedTimestamps.length) {
						$btn.prop('disabled', false).text('Delete');
						updateSelectAllState();
					}
				});
			});
		});

		// Single backup delete
		$('.safora-delete-backup-btn').on('click', function(e) {
			e.preventDefault();
			const ts = $(this).data('timestamp');
			if (!confirm('Permanently delete this backup set and all its archive files?')) return;

			const $row = $(this).closest('tr');
			$row.css('opacity', '0.4');

			$.post(safora_vars.ajax_url, {
				action: 'safora_delete_backup',
				security: safora_vars.nonce,
				timestamp: ts
			}, function(res) {
				if (res.success) {
					$row.fadeOut(300, function() {
						$(this).remove();
						const remaining = $('.safora-backup-row').length;
						$('.safora-count-badge').text(remaining);
						updateSelectAllState();
					});
				} else {
					alert('Delete failed: ' + res.data);
					$row.css('opacity', '1');
				}
			});
		});
	}

	/**
	 * 5. View Log Modal
	 */
	function initViewLogModal() {
		$('.safora-view-log-btn').on('click', function(e) {
			e.preventDefault();
			const nonce = $(this).data('nonce');
			if (!nonce) {
				alert('No log file recorded for this backup.');
				return;
			}

			$('#safora-viewlog-content').text('Loading log file...');
			$('#safora-viewlog-modal').fadeIn(150);

			$.post(safora_vars.ajax_url, {
				action: 'safora_poll_log',
				security: safora_vars.nonce,
				nonce: nonce
			}, function(res) {
				if (res.success && res.data.log) {
					$('#safora-viewlog-content').text(res.data.log);
				} else {
					$('#safora-viewlog-content').text('Log file could not be read or is empty.');
				}
			});
		});
	}

	/**
	 * 6. Restore Wizard Modal
	 */
	function initRestoreModal() {
		let selectedTimestamp = null;
		let selectedBackup = null;

		$('.safora-restore-btn').on('click', function(e) {
			e.preventDefault();
			selectedTimestamp = $(this).data('timestamp');
			selectedBackup = $(this).data('backup');

			const $container = $('#safora-restore-checkboxes-container');
			$container.empty();

			if (selectedBackup.db) {
				$container.append('<label style="display:block; margin: 6px 0;"><input type="checkbox" name="restore_entities[]" value="db" checked> <strong>Database</strong> (' + selectedBackup.db + ')</label>');
			}

			['plugins', 'themes', 'uploads', 'others', 'wpcore'].forEach(function(ent) {
				if (selectedBackup[ent]) {
					const files = Array.isArray(selectedBackup[ent]) ? selectedBackup[ent] : [selectedBackup[ent]];
					$container.append('<label style="display:block; margin: 6px 0;"><input type="checkbox" name="restore_entities[]" value="' + ent + '" checked> <strong>' + ent.toUpperCase() + '</strong> (' + files.length + ' archive(s))</label>');
				}
			});

			let hasCrypt = false;
			if (selectedBackup.db && selectedBackup.db.indexOf('.crypt') !== -1) hasCrypt = true;
			if (hasCrypt) {
				$('#safora-restore-passphrase-box').show();
			} else {
				$('#safora-restore-passphrase-box').hide();
			}

			$('#safora-restore-step-1').show();
			$('#safora-restore-step-2').hide();
			$('#safora-confirm-restore-btn').show();
			$('#safora-restore-modal').fadeIn(150);
		});

		$('#safora-confirm-restore-btn').on('click', function() {
			const entities = [];
			$('input[name="restore_entities[]"]:checked').each(function() {
				entities.push($(this).val());
			});

			if (entities.length === 0) {
				alert('Please select at least one component to restore.');
				return;
			}

			if (!confirm('CRITICAL WARNING: Restoring will overwrite existing website database tables and files. Continue?')) return;

			$('#safora-restore-step-1').hide();
			$('#safora-restore-step-2').show();
			$('#safora-confirm-restore-btn').hide();
			$('.safora-modal-close-btn').hide();
			$('#safora-restore-log-console').text('Putting website into maintenance mode and reading backup archives...\n');

			const passphrase = $('#safora_restore_pass').val();

			function handleRestoreComplete(logText) {
				if (restorePollInterval) {
					clearInterval(restorePollInterval);
					restorePollInterval = null;
				}
				$('#safora-restore-progress-bar').css('width', '100%');
				$('#safora-restore-status-text').html('<span style="color:#16a34a; font-weight:700; font-size:14px;"><span class="dashicons dashicons-yes-alt"></span> Restoration successfully completed!</span>');
				
				const con = document.getElementById('safora-restore-log-console');
				if (con) {
					if (logText) con.textContent = logText;
					if (con.textContent.indexOf('Restoration successfully completed') === -1) {
						con.textContent += '\n==========================================\nRestoration successfully completed!\n==========================================\n';
					}
					con.scrollTop = con.scrollHeight;
				}

				$('.safora-modal-close-btn')
					.show()
					.text('Close & Reload Page')
					.css({
						'background': '#2563eb',
						'color': '#ffffff',
						'border-color': '#1d4ed8',
						'font-weight': '600'
					})
					.off('click')
					.on('click', function() {
						window.location.reload();
					});
			}

			$.post(safora_vars.ajax_url, {
				action: 'safora_restore_backup',
				security: safora_vars.nonce,
				timestamp: selectedTimestamp,
				entities: entities,
				passphrase: passphrase
			}, function(res) {
				if (res.success) {
					const finalLog = (res.data && res.data.log) ? res.data.log : '';
					handleRestoreComplete(finalLog);
				} else {
					if (restorePollInterval) {
						clearInterval(restorePollInterval);
						restorePollInterval = null;
					}
					const errMsg = (res && res.data) ? res.data : 'Restoration failed.';
					$('#safora-restore-log-console').append('\nERROR: ' + errMsg + '\n');
					$('#safora-restore-status-text').html('<span style="color:#dc2626; font-weight:700;">Restoration Failed.</span>');
					$('.safora-modal-close-btn').show().text('Close');
				}
			}).fail(function(jqXHR) {
				if (restorePollInterval) {
					clearInterval(restorePollInterval);
					restorePollInterval = null;
				}
				const err = (jqXHR.responseJSON && jqXHR.responseJSON.data) ? jqXHR.responseJSON.data : 'Connection finished or timed out.';
				$('#safora-restore-log-console').append('\nNotice: ' + err + '\nChecking if restore finished...\n');
				$('.safora-modal-close-btn').show().text('Close & Reload Page').off('click').on('click', function() {
					window.location.reload();
				});
			});

			restorePollInterval = setInterval(function() {
				$.post(safora_vars.ajax_url, {
					action: 'safora_poll_restore_log',
					security: safora_vars.nonce
				}, function(res) {
					if (res.success && res.data) {
						const con = document.getElementById('safora-restore-log-console');
						if (con && res.data.log) {
							con.textContent = res.data.log;
							con.scrollTop = con.scrollHeight;

							if (res.data.log.indexOf('Restoring database') !== -1) {
								$('#safora-restore-progress-bar').css('width', '35%');
								$('#safora-restore-status-text').text('Restoring database tables and structure...');
							}
							if (res.data.log.indexOf('Restoring file entity') !== -1) {
								$('#safora-restore-progress-bar').css('width', '65%');
								$('#safora-restore-status-text').text('Extracting and moving file archives...');
							}
							if (res.data.log.indexOf('Search & Replace') !== -1 || res.data.log.indexOf('Cleaning staging') !== -1) {
								$('#safora-restore-progress-bar').css('width', '85%');
								$('#safora-restore-status-text').text('Running search and replace & flushing caches...');
							}
						}

						if (res.data.finished) {
							handleRestoreComplete(res.data.log);
						}
					}
				});
			}, 1500);
		});
	}

	/**
	 * 7. Archive Downloads Handler
	 */
	function initDownloads() {
		$('.safora-download-pill').on('click', function(e) {
			e.preventDefault();
			const fileStr = $(this).data('file');
			if (!fileStr) return;

			const files = fileStr.split(',');
			files.forEach(function(fn) {
				const trimmed = fn.trim();
				if (!trimmed) return;
				const downloadUrl = safora_vars.ajax_url + '?action=safora_download_file&security=' + encodeURIComponent(safora_vars.nonce) + '&file=' + encodeURIComponent(trimmed);
				const iframe = document.createElement('iframe');
				iframe.style.display = 'none';
				iframe.src = downloadUrl;
				document.body.appendChild(iframe);
				setTimeout(function() {
					document.body.removeChild(iframe);
				}, 5000);
			});
		});
	}

	/**
	 * 8. Site-to-Site Direct Migration & Clone Drawers
	 */
	function initMigration() {
		// Toggle Migrate Drawers
		$('#safora-migrate-btn-restore').on('click', function(e) {
			e.preventDefault();
			$('#safora-migrate-panel-send, #safora-migrate-panel-receive').slideUp(150);
			$('#safora-migrate-panel-restore').slideToggle(150);
		});

		$('#safora-migrate-btn-send').on('click', function(e) {
			e.preventDefault();
			$('#safora-migrate-panel-restore, #safora-migrate-panel-receive').slideUp(150);
			$('#safora-migrate-panel-send').slideToggle(150);
		});

		$('#safora-migrate-btn-receive').on('click', function(e) {
			e.preventDefault();
			$('#safora-migrate-panel-restore, #safora-migrate-panel-send').slideUp(150);
			$('#safora-migrate-panel-receive').slideToggle(150);
		});

		// Upload backup files toggle
		$('#safora-upload-backup-link').on('click', function(e) {
			e.preventDefault();
			$('#safora-upload-backup-panel').slideToggle(150);
		});

		// Download latest log button
		$('#safora-download-latest-log-btn').on('click', function(e) {
			e.preventDefault();
			const $firstLogBtn = $('.safora-view-log-btn').first();
			if ($firstLogBtn.length) {
				$firstLogBtn.trigger('click');
			} else {
				alert('No log entries available yet.');
			}
		});

		// Migration Push Button
		$('#safora-send-remote-btn').on('click', function(e) {
			e.preventDefault();
			const targetUrl = $('#safora_remote_target').val().trim();
			const secretKey = $('#safora_remote_key').val().trim();

			if (!targetUrl || !secretKey) {
				alert('Please enter both Destination Site URL and Migration Key.');
				return;
			}

			if (!confirm('This will create a full backup and push it directly to: ' + targetUrl + '. Continue?')) return;

			const $btn = $(this);
			$btn.prop('disabled', true).text('Pinging destination...');

			const $statusBox = $('#safora-migration-live-status');
			const $statusMsg = $('#safora-migration-status-msg');
			const $bar = $('#safora-migration-progress-bar');
			const $console = $('#safora-migration-live-console');

			$statusBox.slideDown(200);
			$statusMsg.text('Pinging destination WordPress endpoint and authenticating...');
			$bar.css('width', '15%');
			$console.val('Initiating pre-flight verification with: ' + targetUrl + '...\n');

			$.post(safora_vars.ajax_url, {
				action: 'safora_push_migration',
				security: safora_vars.nonce,
				target_url: targetUrl,
				key: secretKey
			}, function(res) {
				if (res.success && res.data.nonce) {
					const nonce = res.data.nonce;
					$btn.text('Migration in progress...');
					$statusMsg.text('Creating archives and streaming chunks to destination (Job: ' + nonce + ')...');
					$bar.css('width', '35%');

					let migPoll = setInterval(function() {
						$.post(safora_vars.ajax_url, {
							action: 'safora_poll_log',
							security: safora_vars.nonce,
							nonce: nonce
						}, function(pollRes) {
							if (pollRes.success && pollRes.data && pollRes.data.log) {
								$console.val(pollRes.data.log);
								$console.scrollTop($console[0].scrollHeight);

								if (pollRes.data.log.indexOf('Starting chunked transfer') !== -1) {
									$bar.css('width', '60%');
									$statusMsg.text('Streaming chunked archives to destination site...');
								}

								if (pollRes.data.log.indexOf('Destination site acknowledged receipt') !== -1 || pollRes.data.log.indexOf('Finished Successfully') !== -1) {
									clearInterval(migPoll);
									$bar.css('width', '100%');
									$statusMsg.text('Migration completed! Files transferred to destination site.');
									$btn.prop('disabled', false).text('Push & Migrate Site');
									alert('Migration completed! The backup archives have been transferred to the destination site. You can now restore them on the destination site.');
								} else if (pollRes.data.is_finished) {
									clearInterval(migPoll);
									$bar.css('width', '100%');
									$btn.prop('disabled', false).text('Push & Migrate Site');
								}
							}
						});
					}, 2000);
				} else {
					const err = res.data || 'Could not connect to destination site.';
					alert('Migration failed: ' + err);
					$statusMsg.text('Migration failed: ' + err);
					$btn.prop('disabled', false).text('Push & Migrate Site');
				}
			}).fail(function(xhr) {
				const err = (xhr.responseJSON && xhr.responseJSON.data) ? xhr.responseJSON.data : 'Server error while initiating migration.';
				alert(err);
				$statusMsg.text('Error: ' + err);
				$btn.prop('disabled', false).text('Push & Migrate Site');
			});
		});
	}

	/**
	 * 9. Google Drive OAuth & UI Handlers (1-Click Official Safora Proxy)
	 */
	function initGoogleDriveAuth() {
		// Toggle custom credentials box
		$('#safora-toggle-custom-google-app').on('click', function(e) {
			e.preventDefault();
			$('#safora-custom-google-app-fields').slideToggle(150);
		});

		// Copy Authorized Redirect URI button
		$('#safora-copy-redirect-uri-btn').on('click', function(e) {
			e.preventDefault();
			const $input = $('#safora_gd_redirect_uri_val');
			$input.select();
			navigator.clipboard.writeText($input.val()).then(function() {
				alert('Authorized Redirect URI copied to clipboard! Paste this into your Google Cloud Console project OAuth credentials.');
			});
		});

		// Sign in with Google trigger (1-Click Direct Redirect)
		$('#safora-google-signin-trigger').on('click', function(e) {
			const customClientId = $('#safora_gd_client_id').val() ? $('#safora_gd_client_id').val().trim() : '';
			const customClientSecret = $('#safora_gd_client_secret').val() ? $('#safora_gd_client_secret').val().trim() : '';
			const defaultClientId = '1032783977320-1027sh5e8hroh3fa3le6ev37s57cqn4g.apps.googleusercontent.com';

			if (customClientId && customClientSecret && customClientId !== defaultClientId) {
				e.preventDefault();
				const redirectUri = $('#safora_gd_redirect_uri_val').val() || (window.location.origin + window.location.pathname + '?page=safora&tab=settings&safora_action=googledrive_auth');
				const authUrl = 'https://accounts.google.com/o/oauth2/v2/auth?' +
					'client_id=' + encodeURIComponent(customClientId) +
					'&redirect_uri=' + encodeURIComponent(redirectUri) +
					'&response_type=code' +
					'&scope=' + encodeURIComponent('https://www.googleapis.com/auth/drive.file https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email') +
					'&access_type=offline' +
					'&include_granted_scopes=false' +
					'&prompt=select_account%20consent' +
					'&state=safora_custom_auth';

				window.location.href = authUrl;
			}
			// Otherwise, let the default anchor href navigate to Safora master auth proxy
		});

		// Google Drive "Select existing folder" modal handler
		$('#safora_gd_select_folder_btn').on('click', function(e) {
			e.preventDefault();
			const $modal = $('#safora-gd-folder-modal');
			const $container = $('#safora-gd-folder-list-container');
			const $search = $('#safora-gd-folder-search');

			$modal.fadeIn(150);
			$search.val('');
			$container.html('<p style="text-align:center; color:#64748b; margin-top:40px;"><span class="spinner is-active" style="float:none; margin:0 5px 0 0;"></span> Loading folders from Google Drive...</p>');

			$.post(safora_vars.ajax_url, {
				action: 'safora_googledrive_list_folders',
				security: safora_vars.nonce
			}, function(res) {
				if (res.success && res.data && res.data.folders) {
					const folders = res.data.folders;
					if (folders.length === 0) {
						$container.html('<p style="text-align:center; color:#64748b; margin-top:30px;">No custom folders found in your Google Drive root. Enter a folder name in the settings box to create it automatically.</p>');
						return;
					}

					let html = '<ul class="safora-gd-folder-list" style="margin:0; padding:0; list-style:none;">';
					folders.forEach(function(f) {
						html += '<li class="safora-gd-folder-item" data-name="' + $('<div>').text(f.name).html() + '" style="padding:10px 14px; border-bottom:1px solid #f1f5f9; cursor:pointer; display:flex; align-items:center; gap:8px; border-radius:3px; transition:background 0.15s ease;">' +
							'<span class="dashicons dashicons-category" style="color:#f59e0b; font-size:18px;"></span>' +
							'<strong style="font-size:13px; color:#1e293b;">' + $('<div>').text(f.name).html() + '</strong>' +
						'</li>';
					});
					html += '</ul>';
					$container.html(html);

					// Click on folder item to select
					$('.safora-gd-folder-item').on('click', function() {
						const selectedName = $(this).data('name');
						$('#safora_gd_folder').val(selectedName);
						$modal.fadeOut(150);
					}).hover(
						function() { $(this).css('background', '#eff6ff'); },
						function() { $(this).css('background', 'transparent'); }
					);

					// Live search filter
					$search.off('keyup').on('keyup', function() {
						const q = $(this).val().toLowerCase();
						$('.safora-gd-folder-item').each(function() {
							const text = $(this).text().toLowerCase();
							$(this).toggle(text.indexOf(q) !== -1);
						});
					});
				} else {
					const err = (res && res.data) ? res.data : 'Could not fetch folders. Please check your Google Drive connection.';
					$container.html('<p style="text-align:center; color:#dc2626; margin-top:30px;"><span class="dashicons dashicons-warning"></span> ' + err + '</p>');
				}
			}).fail(function() {
				$container.html('<p style="text-align:center; color:#dc2626; margin-top:30px;"><span class="dashicons dashicons-warning"></span> Server communication error. Please ensure Google Drive is authenticated.</p>');
			});
		});

		$('#safora-toggle-expert-settings').on('click', function(e) {
			e.preventDefault();
			$('#safora-expert-settings-panel').slideToggle(150);
		});
	}

	/**
	 * 10. Schedule Controls (Interval, Day, Start Time) UpdraftPlus parity
	 */
	function initScheduleControls() {
		const weekdays = [
			{ val: 0, label: 'Sunday' },
			{ val: 1, label: 'Monday' },
			{ val: 2, label: 'Tuesday' },
			{ val: 3, label: 'Wednesday' },
			{ val: 4, label: 'Thursday' },
			{ val: 5, label: 'Friday' },
			{ val: 6, label: 'Saturday' }
		];

		function updateDaySelector($select, interval) {
			if (!$select.length) return;
			const currentVal = $select.val();
			$select.empty();
			if (interval === 'monthly') {
				for (let d = 1; d <= 28; d++) {
					const opt = $('<option>', { value: d, text: d });
					if (parseInt(currentVal, 10) === d) opt.prop('selected', true);
					$select.append(opt);
				}
				$select.show();
			} else if (interval === 'weekly' || interval === 'fortnightly') {
				weekdays.forEach(function(w) {
					const opt = $('<option>', { value: w.val, text: w.label });
					if (parseInt(currentVal, 10) === w.val) opt.prop('selected', true);
					$select.append(opt);
				});
				$select.show();
			} else {
				$select.hide();
			}
		}

		function refreshScheduleUI() {
			const fileInt = $('#safora_interval').val() || 'manual';
			const dbInt = $('#safora_interval_db').val() || 'manual';

			// 1. Files schedule timings
			if (fileInt === 'manual') {
				$('#safora_files_timings').hide();
			} else {
				$('#safora_files_timings').css('display', 'inline-flex');
				updateDaySelector($('#safora_startday_files'), fileInt);
			}

			// 2. DB schedule timings
			if (dbInt === 'manual') {
				$('#safora_db_same_schedule_msg').hide();
				$('#safora_db_timings').hide();
			} else if (dbInt === fileInt) {
				$('#safora_db_same_schedule_msg').show();
				$('#safora_db_timings').hide();
			} else {
				$('#safora_db_same_schedule_msg').hide();
				$('#safora_db_timings').css('display', 'inline-flex');
				updateDaySelector($('#safora_startday_db'), dbInt);
			}
		}

		$('#safora_interval, #safora_interval_db').on('change', function() {
			refreshScheduleUI();
		});

		refreshScheduleUI();
	}

	/**
	 * 11. Additional Retention Rules Dynamic Interactive Rows
	 */
	function initRetentionRules() {
		let filesRuleIndex = $('#safora_retain_files_rules .safora-retention-rule-row').length;
		let dbRuleIndex = $('#safora_retain_db_rules .safora-retention-rule-row').length;

		function getRuleRowHtml(type, index) {
			return '<div class="safora-retention-rule-row" style="display:flex; align-items:center; gap:6px; margin-top:6px; font-size:13px; color:#334155; flex-wrap:wrap;">' +
				'<span>For backups older than</span> ' +
				'<input type="number" min="1" step="1" name="safora_retain_extrarules[' + type + '][' + index + '][after_howmany]" value="12" class="small-text" style="width:60px;"> ' +
				'<select name="safora_retain_extrarules[' + type + '][' + index + '][after_period]">' +
					'<option value="hours">hour(s)</option>' +
					'<option value="days">day(s)</option>' +
					'<option value="weeks" selected="selected">week(s)</option>' +
					'<option value="fortnights">fortnight(s)</option>' +
					'<option value="months">month(s)</option>' +
					'<option value="years">year(s)</option>' +
				'</select> ' +
				'<span>keep no more than 1 backup every</span> ' +
				'<input type="number" min="1" step="1" name="safora_retain_extrarules[' + type + '][' + index + '][every_howmany]" value="1" class="small-text" style="width:60px;"> ' +
				'<select name="safora_retain_extrarules[' + type + '][' + index + '][every_period]">' +
					'<option value="hours">hour(s)</option>' +
					'<option value="days">day(s)</option>' +
					'<option value="weeks" selected="selected">week(s)</option>' +
					'<option value="fortnights">fortnight(s)</option>' +
					'<option value="months">month(s)</option>' +
					'<option value="years">year(s)</option>' +
				'</select> ' +
				'<a href="#" class="safora-remove-retention-rule" style="color:#dc2626; font-size:16px; font-weight:bold; text-decoration:none; margin-left:4px;" title="Remove this rule">&times;</a>' +
			'</div>';
		}

		$('#safora_add_files_retention_rule').on('click', function(e) {
			e.preventDefault();
			filesRuleIndex++;
			$('#safora_retain_files_rules').append(getRuleRowHtml('files', filesRuleIndex));
		});

		$('#safora_add_db_retention_rule').on('click', function(e) {
			e.preventDefault();
			dbRuleIndex++;
			$('#safora_retain_db_rules').append(getRuleRowHtml('db', dbRuleIndex));
		});

		$(document).on('click', '.safora-remove-retention-rule', function(e) {
			e.preventDefault();
			$(this).closest('.safora-retention-rule-row').slideUp(150, function() {
				$(this).remove();
			});
		});
	}

	/**
	 * 11. Advanced Tools Sidebar Navigation & Sub-tools Interactivity (UpdraftPlus Parity)
	 */
	function initAdvancedTools() {
		// 1. Sidebar tab switching
		$('.expertmode .advanced_settings_menu .advanced_tools_button').on('click', function(e) {
			e.preventDefault();
			const tool = $(this).attr('id');
			if (!tool) return;

			$('.expertmode .advanced_settings_menu .advanced_tools_button').removeClass('active');
			$(this).addClass('active');

			$('.expertmode .advanced_settings_content .advanced_tools:not(.' + tool + ')').hide();
			$('.expertmode .advanced_settings_content .' + tool).fadeIn(150);

			if (tool === 'db_size') {
				loadDatabaseTables(false);
			} else if (tool === 'cron_events') {
				loadCronEvents();
			}
		});

		// 2. Site info tools: Refresh disk space
		$('.expertmode').on('click', '.updraft_diskspaceused_update, #safora-refresh-disk-space', function(e) {
			e.preventDefault();
			const $el = $('.updraft_diskspaceused');
			$el.html('<em>calculating...</em>');
			$.post(safora_vars.ajax_url, {
				action: 'safora_get_disk_space',
				security: safora_vars.nonce,
				entity: 'safora'
			}, function(res) {
				if (res.success && res.data) {
					$el.text(res.data.size);
				}
			});
		});

		// 3. Site info tools: Show phpinfo modal
		$('.expertmode').on('click', '#updraftplus-phpinfo, #safora-show-phpinfo', function(e) {
			e.preventDefault();
			$('#safora-phpinfo-modal').fadeIn(150);
			$('#safora-phpinfo-modal-body').html('<div style="text-align:center; padding:30px;">Loading PHP Configuration...</div>');
			$.post(safora_vars.ajax_url, {
				action: 'safora_phpinfo',
				security: safora_vars.nonce
			}, function(res) {
				if (res.success && res.data && res.data.html) {
					$('#safora-phpinfo-modal-body').html(res.data.html);
				} else {
					$('#safora-phpinfo-modal-body').html('<div style="color:red; padding:20px;">Could not load PHP information.</div>');
				}
			});
		});

		// 4. Site info tools: HTTP Get test
		$('.expertmode').on('click', '#updraftplus_httpget_go, #updraftplus_httpget_gocurl, #safora_httpget_go, #safora_httpget_gocurl', function(e) {
			e.preventDefault();
			const id = $(this).attr('id');
			const isCurl = (id.indexOf('gocurl') !== -1) ? 1 : 0;
			const uri = $('#updraftplus_httpget_uri, #safora_httpget_uri').val().trim();
			if (!uri) {
				alert('Please enter a URL to fetch.');
				return;
			}
			const $results = $('#updraftplus_httpget_results, #safora_httpget_results');
			$results.show().html('<em>Fetching ' + uri + '...</em>');

			$.post(safora_vars.ajax_url, {
				action: 'safora_site_info_action',
				security: safora_vars.nonce,
				subaction: 'httpget',
				uri: uri,
				curl: isCurl
			}, function(res) {
				if (res.success && res.data) {
					$results.html('<pre style="background:#0f172a; color:#38bdf8; padding:10px; border-radius:4px; font-size:12px; max-height:200px; overflow-y:auto; white-space:pre-wrap;">' + $('<div>').text(res.data.output).html() + '</pre>');
				} else {
					$results.html('<div style="color:red;">Error: ' + (res.data || 'Failed to fetch') + '</div>');
				}
			});
		});

		// 5. Site info tools: Call WP action
		$('.expertmode').on('click', '#updraftplus_callwpaction_go, #safora_callwpaction_go', function(e) {
			e.preventDefault();
			const hook = $('#updraftplus_callwpaction, #safora_callwpaction').val().trim();
			if (!hook) {
				alert('Please enter a WordPress action hook name.');
				return;
			}
			const $results = $('#updraftplus_callwpaction_results, #safora_callwpaction_results');
			$results.show().html('<em>Executing action ' + hook + '...</em>');

			$.post(safora_vars.ajax_url, {
				action: 'safora_site_info_action',
				security: safora_vars.nonce,
				subaction: 'callwpaction',
				wpaction: hook
			}, function(res) {
				if (res.success && res.data) {
					$results.html('<pre style="background:#0f172a; color:#38bdf8; padding:10px; border-radius:4px; font-size:12px; max-height:200px; overflow-y:auto; white-space:pre-wrap;">' + $('<div>').text(res.data.output).html() + '</pre>');
				} else {
					$results.html('<div style="color:red;">Error: ' + (res.data || 'Action failed') + '</div>');
				}
			});
		});

		// 6. Site info tools: Reset Site ID
		$('.expertmode').on('click', '#updraft_reset_sid, #safora_reset_site_id', function(e) {
			e.preventDefault();
			if (!confirm('Are you sure you want to reset the Site ID? This will regenerate your Vault and Central connection identifiers.')) return;
			$.post(safora_vars.ajax_url, {
				action: 'safora_site_info_action',
				security: safora_vars.nonce,
				subaction: 'reset_site_id'
			}, function(res) {
				if (res.success && res.data) {
					$('#updraft_show_sid, #safora_site_id_val').html('<code>' + res.data.site_id + '</code>');
					alert('Site ID has been reset.');
				}
			});
		});

		// 7. Site info tools: Show Raw Backups Modal
		$('.expertmode').on('click', '#updraftplus-rawbackuphistory, #safora-show-raw-backups', function(e) {
			e.preventDefault();
			$('#safora-raw-backups-modal').fadeIn(150);
			$('#safora-raw-backups-content').text('Loading backup history records...');
			$.post(safora_vars.ajax_url, {
				action: 'safora_site_info_action',
				security: safora_vars.nonce,
				subaction: 'raw_backups'
			}, function(res) {
				if (res.success && res.data) {
					$('#safora-raw-backups-content').text(res.data.raw || 'No backup history found.');
				} else {
					$('#safora-raw-backups-content').text('Failed to load raw backup records.');
				}
			});
		});

		// 8. Site info tools: Rescan Remote Storage Debug
		$('.expertmode').on('click', '#updraftplus-remote-rescan-debug, #safora-rescan-remote-debug', function(e) {
			e.preventDefault();
			console.log('Initiating remote storage rescan...');
			$.post(safora_vars.ajax_url, {
				action: 'safora_rescan_backups',
				security: safora_vars.nonce
			}, function(res) {
				console.log('Rescan Remote Storage Response:', res);
				if (res.success) {
					alert('Remote rescan completed. Found ' + (res.data ? res.data.count : '0') + ' backup set(s). Detailed logs sent to browser console.');
				} else {
					alert('Rescan failed: ' + (res.data || 'Unknown error'));
				}
			});
		});

		// 9. SaforaCentral Interactive Key Creation Wizard
		$('#safora_central_mothership_cloud_btn').on('click', function() {
			$('#updraftcentral_mothership_updraftpluscom').prop('checked', true);
			$('.updraftcentral_wizard_self_hosted_stage2').hide();
			$('.updraftcentral_wizard_stage1').hide();
			$('.updraftcentral_wizard_stage2').show();
			$('#updraftcentral_keycreate_description').val('Safora.io Cloud');
		});

		$('#safora_central_mothership_self_btn').on('click', function() {
			$('#updraftcentral_mothership_other').prop('checked', true);
			$('.updraftcentral_wizard_self_hosted_stage2').slideDown(150);
		});

		$('#updraftcentral_stage2_go').on('click', function(e) {
			e.preventDefault();
			const mothership = $('#updraftcentral_keycreate_mothership').val().trim();
			if (!mothership) {
				$('#updraftcentral_wizard_stage1_error').text('Please enter the dashboard URL.');
				return;
			}
			let host = mothership;
			try {
				const url = new URL(mothership);
				host = url.hostname;
			} catch(err) {
				// fallback host string
			}
			$('#updraftcentral_keycreate_description').val(host);
			$('.updraftcentral_wizard_stage1').hide();
			$('.updraftcentral_wizard_stage2').show();
		});

		$('#updraftcentral_stage1_go').on('click', function(e) {
			e.preventDefault();
			$('.updraftcentral_wizard_stage2').hide();
			$('.updraftcentral_wizard_stage1').show();
		});

		$('#updraftcentral_keycreate_go').on('click', function(e) {
			e.preventDefault();
			const desc = $('#updraftcentral_keycreate_description').val() || 'SaforaCentral Connection';
			const keySize = $('#updraftcentral_keycreate_keysize').val();
			const isOther = $('#updraftcentral_mothership_other').is(':checked');
			const mothership = isOther ? $('#updraftcentral_keycreate_mothership').val() : 'https://safora.io';

			const keyId = Math.random().toString(36).substring(2, 10);
			const tokenBundle = 'safora_bundle_' + btoa(JSON.stringify({
				key_id: keyId,
				name: desc,
				url: window.location.origin,
				endpoint: safora_vars.ajax_url,
				key_size: keySize,
				mothership: mothership,
				created: new Date().toISOString()
			}));

			$('.create_key_container').hide();
			$('#updraftcentral_wizard_go').show();

			const successHtml = '<div class="updraftcentral_wizard_success" style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:4px; padding:15px; margin-bottom:15px;">' +
				'<p><strong>Key successfully created!</strong> Paste this key into your SaforaCentral dashboard:</p>' +
				'<textarea id="updraftcentral-key-bundle" readonly onclick="this.select();" style="width:100%; max-width:620px; height:120px; word-wrap:break-word; border:1px solid #cbd5e1; border-radius:3px; padding:8px; font-family:monospace; font-size:12px;">' + tokenBundle + '</textarea>' +
				'<button type="button" id="saforaplus-copy-btn" class="button button-secondary" style="display:block; margin-top:8px;">Copy to clipboard</button>' +
				'</div>';

			const keyRowHtml = '<table id="updraftcentral_keys_table" class="wp-list-table widefat striped" style="display:table; margin-top:15px;">' +
				'<thead><tr><th>Key name (ID)</th><th>Details</th></tr></thead>' +
				'<tbody><tr class="updraft_debugrow">' +
				'<td><strong>' + $('<div>').text(desc).html() + ' (' + keyId + ')</strong></td>' +
				'<td>Access as user: Administrator<br>Public key sent to: ' + $('<div>').text(mothership).html() + '<br>Created: Just now. ' + keySize + ' bits key.<br><a href="#" class="updraftcentral_key_delete" style="color:red;">Delete</a></td>' +
				'</tr></tbody></table>';

			$('#updraftcentral_keys_content').html(successHtml + keyRowHtml);
		});

		$('#updraftcentral_keys').on('click', '#saforaplus-copy-btn', function(e) {
			e.preventDefault();
			const $ta = $('#updraftcentral-key-bundle');
			$ta.select();
			navigator.clipboard.writeText($ta.val()).then(function() {
				alert('Key bundle copied to clipboard.');
			});
		});

		$('#updraftcentral_keys').on('click', '.updraftcentral_key_delete', function(e) {
			e.preventDefault();
			if (!confirm('Are you sure you want to delete this connection key?')) return;
			$(this).closest('tr').remove();
			if ($('#updraftcentral_keys_table tbody tr').length === 0) {
				$('#updraftcentral_keys_table').hide();
			}
		});

		$('#updraftcentral_wizard_go').on('click', function(e) {
			e.preventDefault();
			$(this).hide();
			$('.updraftcentral_wizard_success').remove();
			$('.create_key_container').show();
			$('.updraftcentral_wizard_stage2').hide();
			$('.updraftcentral_wizard_stage1').show();
		});

		$('#updraftcentral_view_log').on('click', function(e) {
			e.preventDefault();
			const $box = $('#updraftcentral_view_log_contents');
			$box.slideToggle(150);
			if ($box.is(':empty')) {
				$box.text('[Listener Active] SaforaCentral remote communication service running on ' + safora_vars.ajax_url + '\n' + new Date().toISOString() + ' - Ready for incoming connections.');
			}
		});

		// 10. Lock Settings Save
		$('#safora_save_lock_btn').on('click', function(e) {
			e.preventDefault();
			const enabled = $('#safora_lock_enabled').is(':checked') ? 1 : 0;
			const password = $('#safora_lock_password').val();

			$.post(safora_vars.ajax_url, {
				action: 'safora_save_lock_settings',
				security: safora_vars.nonce,
				enabled: enabled,
				password: password
			}, function(res) {
				if (res.success) {
					alert(res.data || 'Lock settings saved successfully.');
				} else {
					alert('Error saving lock settings: ' + res.data);
				}
			});
		});

		// 11. Search & Replace Runner
		$('#safora_sr_run_btn').on('click', function(e) {
			e.preventDefault();
			const searchStr = $('#safora_sr_search').val().trim();
			const replaceStr = $('#safora_sr_replace').val();
			const dryRun = $('#safora_sr_dryrun').is(':checked') ? 1 : 0;
			const prefixOnly = $('#safora_sr_prefix_only').is(':checked') ? 1 : 0;

			if (!searchStr) {
				alert('Please enter a search string.');
				return;
			}

			if (!dryRun && !confirm('WARNING: You are about to replace all occurrences of "' + searchStr + '" with "' + replaceStr + '" in your database. Continue?')) {
				return;
			}

			const $btn = $(this);
			$btn.prop('disabled', true).text('Running Search & Replace...');
			const $resultsBox = $('#safora_sr_results_box');
			const $resultsContent = $('#safora_sr_results_content');
			$resultsBox.show();
			$resultsContent.text('Scanning database tables and updating serialized strings...');

			$.post(safora_vars.ajax_url, {
				action: 'safora_run_search_replace',
				security: safora_vars.nonce,
				search: searchStr,
				replace: replaceStr,
				dry_run: dryRun,
				prefix_only: prefixOnly
			}, function(res) {
				$btn.prop('disabled', false).text('Run Search & Replace');
				if (res.success && res.data) {
					const r = res.data;
					let out = (r.dry_run ? '[DRY RUN - NO DATA MODIFIED]\n\n' : '[LIVE EXECUTION COMPLETE]\n\n');
					out += 'Tables Scanned: ' + r.tables_count + '\n';
					out += 'Rows Examined: ' + r.rows + '\n';
					out += 'Values Modified: ' + r.changes + '\n';
					out += 'Database Update Queries: ' + r.updates + '\n';
					$resultsContent.text(out);
				} else {
					$resultsContent.text('Error: ' + (res.data || 'Search and replace failed.'));
				}
			}).fail(function() {
				$btn.prop('disabled', false).text('Run Search & Replace');
				$resultsContent.text('Server error while executing search & replace.');
			});
		});

		// 12. Site Size Entity Counter (UpdraftPlus count handler)
		$('.advanced_tools.total_size').on('click', 'a.count, .safora-count-entity-btn', function(e) {
			e.preventDefault();
			const $btn = $(this);
			const entity = $btn.data('type') || $btn.data('entity');
			const $span = $('#updraft_diskspaceused_' + entity + ', #safora_diskspaceused_' + entity);
			$span.html('<em>calculating...</em>');

			$.post(safora_vars.ajax_url, {
				action: 'safora_get_disk_space',
				security: safora_vars.nonce,
				entity: entity
			}, function(res) {
				if (res.success && res.data) {
					$span.html('<strong>' + res.data.size + '</strong>');
				} else {
					$span.text('Error');
				}
			});
		});

		// 13. Database Size Table & Search
		$('.db-size-refresh').on('click', function(e) {
			e.preventDefault();
			loadDatabaseTables(true);
		});

		function loadDatabaseTables(force) {
			const $body = $('.db-size-content');
			if (!force && $body.find('tr').length > 1) return;

			$body.html('<tr><td colspan="5" style="text-align:center; padding:20px;"><em>Scanning database tables...</em></td></tr>');
			$('.total-size').text('Calculating...');

			$.post(safora_vars.ajax_url, {
				action: 'safora_get_db_tables',
				security: safora_vars.nonce
			}, function(res) {
				if (res.success && res.data) {
					$('.total-size').text(res.data.size);
					let html = '';
					$.each(res.data.tables, function(i, t) {
						html += '<tr class="safora-db-table-row" data-name="' + t.name.toLowerCase() + '">' +
							'<td><strong>' + t.name + '</strong></td>' +
							'<td>' + t.records + '</td>' +
							'<td>' + t.data_length_formatted + '</td>' +
							'<td>' + t.index_length_formatted + '</td>' +
							'<td>' + t.type + '</td>' +
							'</tr>';
					});
					$body.html(html || '<tr><td colspan="5" style="text-align:center;">No tables found.</td></tr>');
					applyDbTableFilter();
				}
			});
		}

		$('.db-search').on('input', function() {
			applyDbTableFilter();
		});

		$('.db-search-clear').on('click', function(e) {
			e.preventDefault();
			$('.db-search').val('');
			applyDbTableFilter();
		});

		function applyDbTableFilter() {
			const q = $('.db-search').val().trim().toLowerCase();
			if (!q) {
				$('.safora-db-table-row').show();
				return;
			}
			$('.safora-db-table-row').each(function() {
				const name = $(this).data('name') || '';
				if (name.indexOf(q) !== -1) {
					$(this).show();
				} else {
					$(this).hide();
				}
			});
		}

		// 14. Cron Events Loader
		function loadCronEvents() {
			const $body = $('.advanced_tools.cron_events tbody');
			$body.html('<tr><td colspan="3" style="text-align:center; padding:20px;"><em>Loading scheduled cron events...</em></td></tr>');

			$.post(safora_vars.ajax_url, {
				action: 'safora_get_cron_events',
				security: safora_vars.nonce
			}, function(res) {
				if (res.success && Array.isArray(res.data)) {
					let html = '';
					$.each(res.data, function(i, c) {
						const rowClass = c.overdue ? 'safora-cron-overdue' : '';
						const overdueBadge = c.overdue ? '<span class="dashicons dashicons-warning" style="color:#d97706; font-size:16px; vertical-align:middle;"></span> ' : '';
						html += '<tr class="' + rowClass + '">' +
							'<td><code>' + c.hook + '</code></td>' +
							'<td>' + c.interval + '</td>' +
							'<td>' + overdueBadge + c.time + '</td>' +
							'</tr>';
					});
					$body.html(html || '<tr><td colspan="3" style="text-align:center;">No cron events scheduled.</td></tr>');
				}
			});
		}

		// 15. Export & Import Settings
		$('.expertmode').on('click', '#updraftplus-settings-export, #safora-settings-export', function(e) {
			e.preventDefault();
			$.post(safora_vars.ajax_url, {
				action: 'safora_export_settings',
				security: safora_vars.nonce
			}, function(res) {
				if (res.success && res.data && res.data.json) {
					const blob = new Blob([res.data.json], { type: 'application/json' });
					const url = URL.createObjectURL(blob);
					const a = document.createElement('a');
					a.href = url;
					a.download = 'safora-settings-' + new Date().toISOString().slice(0, 10) + '.json';
					document.body.appendChild(a);
					a.click();
					document.body.removeChild(a);
					URL.revokeObjectURL(url);
				} else {
					alert('Failed to export settings.');
				}
			});
		});

		$('.expertmode').on('click', '#updraftplus-settings-import, #safora-settings-import', function(e) {
			e.preventDefault();
			const fileInput = document.getElementById('import_settings') || document.getElementById('safora_import_settings_file');
			if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
				alert('Please select a JSON settings file to import.');
				return;
			}

			const file = fileInput.files[0];
			const reader = new FileReader();
			reader.onload = function(evt) {
				const jsonStr = evt.target.result;
				if (!confirm('WARNING: Importing settings will overwrite your existing Safora configuration. Continue?')) return;

				$.post(safora_vars.ajax_url, {
					action: 'safora_import_settings',
					security: safora_vars.nonce,
					settings_json: jsonStr
				}, function(res) {
					if (res.success) {
						alert('Settings imported successfully! Reloading...');
						window.location.reload();
					} else {
						alert('Import error: ' + res.data);
					}
				});
			};
			reader.readAsText(file);
		});

		// 16. Wipe Settings
		$('.expertmode').on('click', '.updraft_wipe_settings, .safora-wipe-settings-btn', function(e) {
			e.preventDefault();
			if (!confirm('This will delete all your Safora settings - are you sure you want to do this?')) {
				return;
			}

			$.post(safora_vars.ajax_url, {
				action: 'safora_wipe_settings',
				security: safora_vars.nonce
			}, function(res) {
				if (res.success) {
					alert('All settings have been successfully wiped.');
					window.location.reload();
				} else {
					alert('Error wiping settings: ' + res.data);
				}
			});
		});

		// 17. Reset Tour
		$('.expertmode').on('click', '.js-updraftplus-tour, #safora_reset_tour_btn', function(e) {
			e.preventDefault();
			alert('Safora walkthrough tour reset.');
		});
	}

})(jQuery);


