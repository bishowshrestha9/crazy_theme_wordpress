(function ($) {
    'use strict';

    let currentPage = 1;
    let selectedPostIds = [];
    let activePreviewPostId = null;
    let isEmergencyStopped = false;

    $(document).ready(function () {
        // Initial scan on load & check slow queue status
        scanPosts(1);
        checkSlowQueueStatus();
        startStatusPolling();

        // Scan button click
        $(document).on('click', '#twr-btn-scan', function (e) {
            e.preventDefault();
            currentPage = 1;
            scanPosts(1);
        });

        // Filter / Search input change
        $(document).on('keyup search', '#twr-search-input', debounce(function () {
            currentPage = 1;
            scanPosts(1);
        }, 400));

        $(document).on('change', '#twr-post-type-filter', function () {
            currentPage = 1;
            scanPosts(1);
        });

        // Select All Checkbox
        $(document).on('change', '#twr-cb-select-all', function () {
            const isChecked = $(this).is(':checked');
            $('.twr-cb-post').prop('checked', isChecked);
            updateSelectedCounter();
        });

        // Individual Post Checkbox
        $(document).on('change', '.twr-cb-post', function () {
            updateSelectedCounter();
        });

        // Single Remove Click
        $(document).on('click', '.twr-btn-remove-single', function (e) {
            e.preventDefault();
            const postId = $(this).data('id');
            if (confirm(twrData.strings.confirmSingle)) {
                removeSinglePost(postId);
            }
        });

        // Single Preview Click
        $(document).on('click', '.twr-btn-preview-single', function (e) {
            e.preventDefault();
            const postId = $(this).data('id');
            openPreviewModal(postId);
        });

        // Global Replace ALL Click
        $(document).on('click', '#twr-btn-replace-all', function (e) {
            e.preventDefault();
            isEmergencyStopped = false;
            let replacement = $('#twr-replacement-input').val().trim();
            if (!replacement) {
                replacement = prompt(twrData.strings.promptReplacement, 'Consumer reviews');
                if (replacement === null) {
                    return; // user cancelled prompt
                }
                $('#twr-replacement-input').val(replacement);
            }

            fetchAndProcessAll(replacement, true);
        });

        // Global Remove ALL Click
        $(document).on('click', '#twr-btn-remove-all', function (e) {
            e.preventDefault();
            isEmergencyStopped = false;
            fetchAndProcessAll('', false);
        });

        // Bulk Replace Selected Click
        $(document).on('click', '#twr-btn-bulk-replace', function (e) {
            e.preventDefault();
            isEmergencyStopped = false;
            const selected = getSelectedPostIds();
            if (selected.length === 0) {
                alert(twrData.strings.noSelection);
                return;
            }

            let replacement = $('#twr-replacement-input').val().trim();
            if (!replacement) {
                replacement = prompt(twrData.strings.promptReplacement, 'Consumer reviews');
                if (replacement === null) {
                    return; // user cancelled prompt
                }
                $('#twr-replacement-input').val(replacement);
            }

            const confirmMsg = twrData.strings.confirmBulkReplace
                .replace('%s', replacement)
                .replace('%d', selected.length);

            if (confirm(confirmMsg)) {
                removeBulkPosts(selected, replacement);
            }
        });

        // Bulk Remove Selected Click
        $(document).on('click', '#twr-btn-bulk-remove', function (e) {
            e.preventDefault();
            isEmergencyStopped = false;
            const selected = getSelectedPostIds();
            if (selected.length === 0) {
                alert(twrData.strings.noSelection);
                return;
            }

            const confirmMsg = twrData.strings.confirmBulkRemove.replace('%d', selected.length);
            if (confirm(confirmMsg)) {
                removeBulkPosts(selected, '');
            }
        });

        // Modal Close Buttons
        $(document).on('click', '#twr-modal-close, #twr-modal-cancel', function () {
            closeModal();
        });

        // Modal Confirm Remove
        $(document).on('click', '#twr-modal-confirm-remove', function () {
            if (activePreviewPostId) {
                removeSinglePost(activePreviewPostId, function () {
                    closeModal();
                });
            }
        });

        // Export CSV
        $(document).on('click', '#twr-btn-export-csv', function (e) {
            e.preventDefault();
            const form = $('<form>', {
                action: twrData.ajaxUrl,
                method: 'POST',
                target: '_blank'
            });

            form.append($('<input>', { type: 'hidden', name: 'action', value: 'twr_export_csv' }));
            form.append($('<input>', { type: 'hidden', name: 'nonce', value: twrData.nonce }));
            $('body').append(form);
            form.submit();
            form.remove();
        });

        // Queue Banner Controls
        $(document).on('click', '#twr-btn-queue-pause', function (e) {
            e.preventDefault();
            controlSlowQueue('pause');
        });

        $(document).on('click', '#twr-btn-queue-resume', function (e) {
            e.preventDefault();
            controlSlowQueue('resume');
        });

        $(document).on('click', '#twr-btn-queue-cancel', function (e) {
            e.preventDefault();
            if (confirm('Are you sure you want to CANCEL the active background slow replacement job?')) {
                controlSlowQueue('cancel');
            }
        });

        // 🚨 EMERGENCY STOP BUTTON CLICK
        $(document).on('click', '.twr-btn-emergency-stop', function (e) {
            e.preventDefault();
            if (confirm('🚨 EMERGENCY STOP: Are you sure you want to FORCEFULLY HALT all background jobs, cron schedules, and active replacements IMMEDIATELY?')) {
                executeEmergencyStop();
            }
        });
    });

    /**
     * Fetch & Scan Posts via AJAX
     */
    function scanPosts(page) {
        currentPage = page;
        const postType = $('#twr-post-type-filter').val();
        const search = $('#twr-search-input').val();

        $('#twr-table-body').html(
            `<tr>
                <td colspan="7">
                    <div class="twr-empty-container">
                        <span class="spinner is-active" style="float:none;margin:0 0 12px 0;width:32px;height:32px;"></span>
                        <h4 style="margin:0;color:#334155;">Scanning database for "Trustpilot"...</h4>
                        <p style="margin:4px 0 0 0;color:#64748b;font-size:13px;">Checking post content, titles, and excerpts across all post types.</p>
                    </div>
                </td>
            </tr>`
        );

        $.ajax({
            url: twrData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'twr_scan_posts',
                nonce: twrData.nonce,
                paged: page,
                post_type: postType,
                search: search
            },
            success: function (res) {
                if (res.success) {
                    renderTable(res.data.items);
                    renderStats(res.data.stats);
                    renderPagination(res.data.paged, res.data.max_pages, res.data.total);
                } else {
                    alert(res.data.message || 'Error scanning posts.');
                }
            },
            error: function () {
                $('#twr-table-body').html(`<tr><td colspan="7" class="twr-empty-row" style="color:red;">Error connecting to server.</td></tr>`);
            }
        });
    }

    /**
     * Render Table Rows with Pixel-Perfect UI
     */
    function renderTable(items) {
        const $tbody = $('#twr-table-body');
        $tbody.empty();
        $('#twr-cb-select-all').prop('checked', false);
        updateSelectedCounter();

        if (!items || items.length === 0) {
            $tbody.html(`
                <tr>
                    <td colspan="7">
                        <div class="twr-empty-container">
                            <span class="dashicons dashicons-yes-alt twr-empty-icon" style="color:#10b981;"></span>
                            <h3 class="twr-empty-title">All Clean! No "Trustpilot" occurrences found.</h3>
                            <p class="twr-empty-desc">Your content is clean or matches were successfully updated.</p>
                        </div>
                    </td>
                </tr>
            `);
            return;
        }

        items.forEach(function (item) {
            let snippetsHtml = '';
            if (item.snippets && item.snippets.length > 0) {
                item.snippets.forEach(function (snip) {
                    snippetsHtml += `<div class="twr-snippet-card">${snip}</div>`;
                });
            } else {
                snippetsHtml = '<span class="description" style="color:#94a3b8;font-style:italic;">Matched in title or body text</span>';
            }

            const row = `
                <tr id="twr-post-row-${item.id}">
                    <th scope="row" class="check-column">
                        <input type="checkbox" class="twr-cb-post" value="${item.id}">
                    </th>
                    <td><span class="twr-id-pill">#${item.id}</span></td>
                    <td>
                        <div class="twr-title-container">
                            <a class="twr-post-title-link" href="${item.edit_link}" target="_blank">${escapeHtml(item.title)}</a>
                            <div class="twr-post-meta-row">
                                <span class="twr-status-badge twr-status-${item.post_status}">
                                    <span class="twr-status-dot"></span>${item.post_status}
                                </span>
                                <span style="color:#cbd5e1;">•</span>
                                <a class="twr-meta-link" href="${item.edit_link}" target="_blank">Edit ✎</a>
                                <span style="color:#cbd5e1;">•</span>
                                <a class="twr-meta-link" href="${item.view_link}" target="_blank">View ↗</a>
                            </div>
                        </div>
                    </td>
                    <td><span class="twr-badge-posttype">${item.post_type}</span></td>
                    <td>
                        <span class="twr-badge-occurrences">
                            <span class="dashicons dashicons-warning" style="font-size:14px;width:14px;height:14px;line-height:14px;"></span>
                            ${item.occurrences} Match${item.occurrences > 1 ? 'es' : ''}
                        </span>
                    </td>
                    <td>
                        <div class="twr-snippets-wrapper">
                            ${snippetsHtml}
                        </div>
                    </td>
                    <td>
                        <div class="twr-actions-cell">
                            <button type="button" class="button twr-btn-action twr-btn-preview twr-btn-preview-single" data-id="${item.id}">
                                <span class="dashicons dashicons-visibility"></span> Preview
                            </button>
                            <button type="button" class="button twr-btn-action twr-btn-remove twr-btn-remove-single" data-id="${item.id}">
                                <span class="dashicons dashicons-trash"></span> Remove
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            $tbody.append(row);
        });
    }

    /**
     * Render Stats Cards
     */
    function renderStats(stats) {
        if (!stats) return;
        $('#twr-stat-scanned').text(formatNumber(stats.total_scanned));
        $('#twr-stat-matched').text(formatNumber(stats.total_matched));
        $('#twr-stat-occurrences').text(formatNumber(stats.total_occurrences));
    }

    /**
     * Render Pagination Links
     */
    function renderPagination(paged, maxPages, totalItems) {
        const $container = $('#twr-pagination-container');
        const $info = $('#twr-pagination-info');
        const $links = $('#twr-pagination-links');

        if (maxPages <= 1) {
            $container.hide();
            return;
        }

        $container.show();
        $info.text(`Showing page ${paged} of ${maxPages} (${totalItems} total posts matched)`);
        $links.empty();

        if (paged > 1) {
            $links.append(`<button type="button" class="button twr-page-btn" data-page="${paged - 1}">« Prev</button>`);
        }

        for (let i = 1; i <= maxPages; i++) {
            if (i === 1 || i === maxPages || (i >= paged - 2 && i <= paged + 2)) {
                const activeClass = (i === paged) ? 'button-primary' : 'button-secondary';
                $links.append(`<button type="button" class="button ${activeClass} twr-page-btn" data-page="${i}">${i}</button>`);
            } else if (i === paged - 3 || i === paged + 3) {
                $links.append(`<span style="padding:0 4px;color:#94a3b8;">...</span>`);
            }
        }

        if (paged < maxPages) {
            $links.append(`<button type="button" class="button twr-page-btn" data-page="${paged + 1}">Next »</button>`);
        }

        $('.twr-page-btn').on('click', function () {
            const pageNum = $(this).data('page');
            scanPosts(pageNum);
        });
    }

    /**
     * Single Post Remove Action
     */
    function removeSinglePost(postId, callback) {
        const replacement = $('#twr-replacement-input').val();
        const $row = $(`#twr-post-row-${postId}`);

        $row.css('opacity', '0.4');

        $.ajax({
            url: twrData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'twr_remove_single',
                nonce: twrData.nonce,
                post_id: postId,
                replacement: replacement
            },
            success: function (res) {
                if (res.success) {
                    if (callback) callback();
                    scanPosts(currentPage);
                } else {
                    alert(res.data.message || 'Error removing word.');
                    $row.css('opacity', '1');
                }
            },
            error: function () {
                alert('Connection error.');
                $row.css('opacity', '1');
            }
        });
    }

    /**
     * Fetch all matching post IDs and trigger global batch processing
     */
    function fetchAndProcessAll(replacement, isReplaceMode) {
        const postType = $('#twr-post-type-filter').val();
        const search = $('#twr-search-input').val();

        $('#twr-progress-container').show();
        $('#twr-progress-bar-fill').css('width', '5%');
        $('#twr-progress-percentage').text('5%');
        $('#twr-progress-status').text('Finding all matching posts across database...');

        $.ajax({
            url: twrData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'twr_get_all_matching_ids',
                nonce: twrData.nonce,
                post_type: postType,
                search: search
            },
            success: function (res) {
                if (res.success && res.data.post_ids && res.data.post_ids.length > 0) {
                    const postIds = res.data.post_ids;
                    let confirmMsg = '';

                    if (isReplaceMode) {
                        confirmMsg = twrData.strings.confirmReplaceAll
                            .replace('%s', replacement)
                            .replace('%d', postIds.length);
                    } else {
                        confirmMsg = twrData.strings.confirmRemoveAll
                            .replace('%d', postIds.length);
                    }

                    $('#twr-progress-container').hide();

                    const pace = $('#twr-pace-selector').val();
                    if (pace === '4hours' || pace === '1hour') {
                        const hours = (pace === '4hours') ? 4 : 1;
                        let modeStr = isReplaceMode ? `replace "Trustpilot" with "${replacement}"` : 'remove "Trustpilot"';
                        let slowConfirmMsg = `Schedule background process to ${modeStr} across ${postIds.length} matching post(s) spreading slowly over ${hours} hour(s)?\n\nWP-Cron will run in the background so you can safely close your browser tab.`;
                        
                        if (confirm(slowConfirmMsg)) {
                            startSlowQueue(postIds, replacement, hours);
                        }
                        return;
                    }

                    if (confirm(confirmMsg)) {
                        processAllInChunks(postIds, replacement);
                    }
                } else {
                    $('#twr-progress-container').hide();
                    alert(twrData.strings.noMatchingPosts || 'No matching posts found.');
                }
            },
            error: function () {
                $('#twr-progress-container').hide();
                alert('Error fetching matching posts list.');
            }
        });
    }

    /**
     * Process bulk posts (uses chunking for large lists)
     */
    function removeBulkPosts(postIds, replacement) {
        if (postIds.length > 20) {
            processAllInChunks(postIds, replacement);
        } else {
            processAllInChunks(postIds, replacement);
        }
    }

    /**
     * Sequential Chunked Batch Processing to avoid PHP limits
     */
    function processAllInChunks(postIds, replacement) {
        if (typeof replacement === 'undefined') {
            replacement = $('#twr-replacement-input').val();
        }

        const chunkSize = 20;
        const totalBatches = Math.ceil(postIds.length / chunkSize);
        const $progressContainer = $('#twr-progress-container');
        const $progressBarFill = $('#twr-progress-bar-fill');
        const $progressPercentage = $('#twr-progress-percentage');
        const $progressStatus = $('#twr-progress-status');

        $progressContainer.show();
        $progressBarFill.css('width', '0%');
        $progressPercentage.text('0%');

        let batchIndex = 0;
        let totalCleaned = 0;
        let totalOccurrencesRemoved = 0;
        let errors = [];

        function executeBatch() {
        if (isEmergencyStopped) {
            $progressContainer.hide();
            return;
        }

        const start = batchIndex * chunkSize;
            const chunk = postIds.slice(start, start + chunkSize);
            const pct = Math.round((batchIndex / totalBatches) * 100);

            $progressBarFill.css('width', pct + '%');
            $progressPercentage.text(pct + '%');
            $progressStatus.text(`Processing batch ${batchIndex + 1} of ${totalBatches} (${chunk.length} posts)...`);

            $.ajax({
                url: twrData.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'twr_remove_bulk',
                    nonce: twrData.nonce,
                    post_ids: chunk,
                    replacement: replacement
                },
                success: function (res) {
                    if (res.success) {
                        totalCleaned += res.data.cleaned_count || 0;
                        totalOccurrencesRemoved += res.data.total_occurrences_removed || 0;
                        if (res.data.errors && res.data.errors.length > 0) {
                            errors = errors.concat(res.data.errors);
                        }

                        batchIndex++;
                        if (batchIndex < totalBatches) {
                            executeBatch();
                        } else {
                            $progressBarFill.css('width', '100%');
                            $progressPercentage.text('100%');
                            let completionMsg = `Completed! Processed ${postIds.length} post(s), updated ${totalCleaned} post(s) (${totalOccurrencesRemoved} occurrence(s) replaced/removed).`;
                            if (errors.length > 0) {
                                completionMsg += ` Encountered ${errors.length} error(s).`;
                            }
                            $progressStatus.text(completionMsg);

                            setTimeout(function () {
                                $progressContainer.fadeOut();
                                scanPosts(1);
                            }, 2000);
                        }
                    } else {
                        alert(res.data.message || 'Error processing batch.');
                        $progressContainer.hide();
                    }
                },
                error: function () {
                    alert(`Connection error on batch ${batchIndex + 1}. Stopping processing.`);
                    $progressContainer.hide();
                }
            });
        }

        executeBatch();
    }

    /**
     * Start Background Slow Queue (4 Hours or 1 Hour)
     */
    function startSlowQueue(postIds, replacement, durationHours) {
        const $progressContainer = $('#twr-progress-container');
        $progressContainer.show();
        $('#twr-progress-bar-fill').css('width', '10%');
        $('#twr-progress-percentage').text('10%');
        $('#twr-progress-status').text(`Starting ${durationHours}-hour background slow queue...`);

        $.ajax({
            url: twrData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'twr_start_slow_queue',
                nonce: twrData.nonce,
                post_ids: postIds,
                replacement: replacement,
                duration_hours: durationHours
            },
            success: function (res) {
                $progressContainer.hide();
                if (res.success) {
                    renderSlowBanner(res.data);
                    startStatusPolling();
                    scanPosts(1);
                } else {
                    alert(res.data.message || 'Error starting background queue.');
                }
            },
            error: function () {
                $progressContainer.hide();
                alert('Error starting background queue.');
            }
        });
    }

    /**
     * Queue Status Polling & Banner Updater
     */
    let queuePollTimer = null;

    function checkSlowQueueStatus() {
        $.ajax({
            url: twrData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'twr_get_queue_status',
                nonce: twrData.nonce
            },
            success: function (res) {
                if (res.success && res.data) {
                    renderSlowBanner(res.data);
                }
            }
        });
    }

    function renderSlowBanner(data) {
        const $banner = $('#twr-slow-job-banner');
        if (!data || !data.active) {
            $banner.slideUp();
            stopStatusPolling();
            return;
        }

        $banner.slideDown();
        const isPaused = (data.status === 'paused');

        $('#twr-slow-banner-title').text(isPaused ? 'Background Job Paused' : `Background Slow Replacement Active (${data.duration_hours} Hours)`);
        $('#twr-slow-banner-desc').text(
            `Processed ${data.processed_count} of ${data.total_posts} post(s) (${data.percent}% complete). Total occurrences replaced/removed: ${data.total_occurrences_removed}`
        );
        $('#twr-slow-eta-badge').text(isPaused ? 'PAUSED' : `ETA: ${data.eta_formatted}`);
        $('#twr-slow-progress-fill').css('width', `${data.percent}%`);

        if (isPaused) {
            $('#twr-slow-pulse').removeClass('twr-pulse-active').addClass('twr-pulse-paused');
            $('#twr-btn-queue-pause').hide();
            $('#twr-btn-queue-resume').show();
        } else {
            $('#twr-slow-pulse').removeClass('twr-pulse-paused').addClass('twr-pulse-active');
            $('#twr-btn-queue-pause').show();
            $('#twr-btn-queue-resume').hide();
        }
    }

    function startStatusPolling() {
        if (!queuePollTimer) {
            queuePollTimer = setInterval(checkSlowQueueStatus, 10000);
        }
    }

    function stopStatusPolling() {
        if (queuePollTimer) {
            clearInterval(queuePollTimer);
            queuePollTimer = null;
        }
    }

    function controlSlowQueue(action) {
        $.ajax({
            url: twrData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'twr_control_queue',
                nonce: twrData.nonce,
                queue_action: action
            },
            success: function (res) {
                if (res.success) {
                    renderSlowBanner(res.data);
                    scanPosts(1);
                } else {
                    alert(res.data.message || 'Action failed.');
                }
            }
        });
    }

    /**
     * 🚨 EMERGENCY STOP FUNCTION
     */
    function executeEmergencyStop() {
        isEmergencyStopped = true;
        stopStatusPolling();

        $('#twr-progress-container').hide();
        $('#twr-slow-job-banner').slideUp();

        $.ajax({
            url: twrData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'twr_emergency_stop',
                nonce: twrData.nonce
            },
            success: function (res) {
                alert(res.data && res.data.message ? res.data.message : '🚨 Emergency stop executed!');
                scanPosts(1);
            },
            error: function () {
                alert('🚨 Emergency stop command sent!');
                scanPosts(1);
            }
        });
    }

    /**
     * Preview Diff Modal
     */
    function openPreviewModal(postId) {
        activePreviewPostId = postId;
        const replacement = $('#twr-replacement-input').val();
        const $modal = $('#twr-modal-overlay');
        const $modalBody = $('#twr-modal-body');

        $modalBody.html('<p><span class="spinner is-active" style="float:none;margin-right:8px;"></span>Loading preview...</p>');
        $modal.show();

        $.ajax({
            url: twrData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'twr_get_preview',
                nonce: twrData.nonce,
                post_id: postId,
                replacement: replacement
            },
            success: function (res) {
                if (res.success) {
                    const data = res.data;
                    let originalHtml = '';
                    if (data.original_snippets && data.original_snippets.length > 0) {
                        data.original_snippets.forEach(function (snip) {
                            originalHtml += `<div class="twr-snippet-card" style="margin-bottom:8px;">${snip}</div>`;
                        });
                    } else {
                        originalHtml = '<p class="description">Matched in title or body text.</p>';
                    }

                    $modalBody.html(`
                        <div class="twr-diff-box">
                            <div class="twr-diff-label">Post Title & ID</div>
                            <div style="font-weight:700;font-size:15px;color:#0f172a;">${escapeHtml(data.post_title)} <span class="twr-id-pill">#${data.post_id}</span></div>
                        </div>
                        <div class="twr-diff-box">
                            <div class="twr-diff-label">Original Content Snippet (With "Trustpilot")</div>
                            <div class="twr-diff-content">${originalHtml}</div>
                        </div>
                        <div class="twr-diff-box">
                            <div class="twr-diff-label">Cleaned / Replaced Excerpt</div>
                            <div class="twr-diff-content" style="background:#f0fdf4;border-color:#bbf7d0;color:#166534;">
                                ${escapeHtml(data.cleaned_content)}
                            </div>
                        </div>
                    `);
                } else {
                    $modalBody.html(`<p style="color:red;">${res.data.message || 'Error fetching preview.'}</p>`);
                }
            },
            error: function () {
                $modalBody.html('<p style="color:red;">Error connecting to server.</p>');
            }
        });
    }

    function closeModal() {
        $('#twr-modal-overlay').hide();
        activePreviewPostId = null;
    }

    function getSelectedPostIds() {
        const ids = [];
        $('.twr-cb-post:checked').each(function () {
            ids.push(parseInt($(this).val()));
        });
        return ids;
    }

    function updateSelectedCounter() {
        const count = getSelectedPostIds().length;
        $('#twr-selected-count').text(`${count} post${count === 1 ? '' : 's'} selected`);
    }

    function formatNumber(num) {
        return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
    }

    function escapeHtml(str) {
        return $('<div>').text(str).html();
    }

    function debounce(func, wait) {
        let timeout;
        return function () {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                func.apply(context, args);
            }, wait);
        };
    }
})(jQuery);
