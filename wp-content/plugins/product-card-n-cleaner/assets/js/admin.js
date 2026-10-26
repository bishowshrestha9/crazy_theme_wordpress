jQuery(document).ready(function ($) {
    'use strict';

    let scannedData = [];
    let currentPreviewPostId = null;

    const $btnScan = $('#pcpc-btn-scan');
    const $loader = $('#pcpc-loader');
    const $loaderText = $('#pcpc-loader-text');
    const $progressBar = $('#pcpc-progress-bar');
    const $statsGrid = $('#pcpc-stats-grid');
    const $resultsPanel = $('#pcpc-results-panel');
    const $tableBody = $('#pcpc-table-body');
    const $emptyState = $('#pcpc-empty-state');
    const $bulkPanel = $('#pcpc-bulk-panel');
    const $btnFixSelected = $('#pcpc-btn-fix-selected');
    const $selectAllCb = $('#pcpc-select-all-checkbox');
    const $selectAllHeader = $('#pcpc-select-all-header');
    const $selectedCount = $('#pcpc-selected-count');
    const $searchInput = $('#pcpc-search-input');
    const $modalPreview = $('#pcpc-modal-preview');

    $btnScan.on('click', function (e) {
        e.preventDefault();
        runSiteScan();
    });

    function runSiteScan() {
        $btnScan.prop('disabled', true).addClass('updating-message');
        $resultsPanel.hide();
        $emptyState.hide();
        $bulkPanel.hide();
        $statsGrid.hide();

        $loader.show();
        $loaderText.text('Scanning all WordPress posts & pages for Product Cards...');
        $progressBar.css('width', '30%');

        $.ajax({
            url: pcpcData.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'pcpc_scan_posts',
                nonce: pcpcData.nonce
            },
            success: function (res) {
                $progressBar.css('width', '100%');
                setTimeout(() => {
                    $loader.hide();
                    $btnScan.prop('disabled', false).removeClass('updating-message');

                    if (res.success) {
                        const data = res.data;
                        scannedData = data.posts || [];

                        $('#stat-posts-scanned').text(data.total_posts_scanned || 0);
                        $('#stat-flagged-posts').text(data.flagged_posts_count || 0);
                        $('#stat-total-cards').text(data.total_cards_scanned || 0);
                        $('#stat-flagged-cards').text(data.flagged_cards_count || 0);
                        $statsGrid.show();

                        if (scannedData.length > 0) {
                            renderResultsTable(scannedData);
                            $resultsPanel.show();
                            $bulkPanel.show();
                            updateSelectedCount();
                        } else {
                            $emptyState.show();
                        }
                    } else {
                        alert('Scan error: ' + (res.data ? res.data.message : 'Unknown error'));
                    }
                }, 400);
            },
            error: function (xhr, status, error) {
                $loader.hide();
                $btnScan.prop('disabled', false).removeClass('updating-message');
                alert('Scan failed due to server error: ' + error);
            }
        });
    }

    function renderResultsTable(posts) {
        $tableBody.empty();

        posts.forEach(function (post) {
            let fieldsHtml = '';

            post.cards.forEach(function (card, cardIdx) {
                if (!card.has_issue) return;

                card.affected_fields.forEach(function (f) {
                    if (!f.has_issue) return;

                    let origEscaped = escapeHtml(f.original_val);
                    // Highlight .n, !n, ?n, .nn, !nnn, and repeated n's
                    origEscaped = origEscaped.replace(/([\.\!\?]\s*n+)/gi, '<mark class="pcpc-n-highlight">$1</mark>');
                    origEscaped = origEscaped.replace(/(?<!<mark class="pcpc-n-highlight">)(n{2,})(?=[\s"'\`\.\,\?\!]|$)/gi, '<mark class="pcpc-n-highlight">$1</mark>');

                    fieldsHtml += `
                        <div class="pcpc-field-box">
                            <div class="pcpc-field-label">${escapeHtml(f.label)} (Card #${cardIdx + 1})</div>
                            <div class="pcpc-diff-wrapper">
                                <div class="pcpc-diff-orig">
                                    <strong>Before:</strong> ${origEscaped}
                                </div>
                                <div class="pcpc-diff-clean">
                                    <strong>After Clean:</strong> ${escapeHtml(f.clean_val)}
                                </div>
                            </div>
                        </div>
                    `;
                });
            });

            let restoreBtnHtml = '';
            if (post.has_backup) {
                restoreBtnHtml = `<button type="button" class="button btn-restore-post" data-id="${post.post_id}"><span class="dashicons dashicons-undo" style="margin-top:2px;"></span> Restore Original</button>`;
            }

            const rowHtml = `
                <tr id="pcpc-row-${post.post_id}" data-id="${post.post_id}" data-title="${escapeHtml(post.title.toLowerCase())}">
                    <th scope="row" class="check-column">
                        <input type="checkbox" class="pcpc-post-checkbox" value="${post.post_id}" checked />
                    </th>
                    <td>
                        <div class="pcpc-post-title">${escapeHtml(post.title)}</div>
                        <div class="pcpc-post-links">
                            <a href="${post.edit_url}" target="_blank">Edit Post ↗</a>
                            <a href="${post.view_url}" target="_blank">View Post ↗</a>
                        </div>
                    </td>
                    <td><span class="pcpc-selected-count-badge">${escapeHtml(post.post_type)}</span></td>
                    <td>${fieldsHtml}</td>
                    <td style="text-align: right;">
                        <div class="pcpc-row-actions">
                            <button type="button" class="button btn-preview-card" data-id="${post.post_id}">
                                <span class="dashicons dashicons-visibility" style="margin-top:2px;"></span> Preview Card
                            </button>
                            <button type="button" class="button button-primary btn-fix-single" data-id="${post.post_id}">
                                <span class="dashicons dashicons-yes-alt" style="margin-top:2px;"></span> Remove 'n'
                            </button>
                            ${restoreBtnHtml}
                        </div>
                    </td>
                </tr>
            `;

            $tableBody.append(rowHtml);
        });
    }

    $selectAllCb.add($selectAllHeader).on('change', function () {
        const isChecked = $(this).is(':checked');
        $selectAllCb.prop('checked', isChecked);
        $selectAllHeader.prop('checked', isChecked);
        $('.pcpc-post-checkbox:visible').prop('checked', isChecked);
        updateSelectedCount();
    });

    $(document).on('change', '.pcpc-post-checkbox', function () {
        updateSelectedCount();
    });

    function updateSelectedCount() {
        const count = $('.pcpc-post-checkbox:checked').length;
        $selectedCount.text(count + ' selected');
        $btnFixSelected.prop('disabled', count === 0);
    }

    $searchInput.on('keyup', function () {
        const term = $(this).val().toLowerCase().trim();
        $('#pcpc-table-body tr').each(function () {
            const title = $(this).data('title') || '';
            if (title.includes(term)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        updateSelectedCount();
    });

    $(document).on('click', '.btn-fix-single', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const postId = $btn.data('id');

        $btn.prop('disabled', true).text('Fixing...');

        $.ajax({
            url: pcpcData.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'pcpc_fix_post',
                nonce: pcpcData.nonce,
                post_id: postId
            },
            success: function (res) {
                if (res.success) {
                    const $row = $('#pcpc-row-' + postId);
                    $row.css('background-color', '#f0fdf4');
                    $btn.replaceWith('<span class="pcpc-selected-count-badge" style="background:#dcfce7; color:#16a34a; padding:6px 12px;"><span class="dashicons dashicons-yes"></span> Cleaned</span>');
                    
                    if ($row.find('.btn-restore-post').length === 0) {
                        $row.find('.pcpc-row-actions').append(`<button type="button" class="button btn-restore-post" data-id="${postId}"><span class="dashicons dashicons-undo" style="margin-top:2px;"></span> Restore Original</button>`);
                    }

                    let currentFlagged = parseInt($('#stat-flagged-posts').text()) || 0;
                    if (currentFlagged > 0) {
                        $('#stat-flagged-posts').text(currentFlagged - 1);
                    }
                } else {
                    alert('Error: ' + (res.data ? res.data.message : 'Could not update post'));
                    $btn.prop('disabled', false).text('Remove \'n\'');
                }
            },
            error: function () {
                alert('Server error fixing post #' + postId);
                $btn.prop('disabled', false).text('Remove \'n\'');
            }
        });
    });

    $(document).on('click', '.btn-restore-post', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const postId = $btn.data('id');

        if (!confirm('Are you sure you want to restore Post #' + postId + ' to its original state before cleaning?')) {
            return;
        }

        $btn.prop('disabled', true).text('Restoring...');

        $.ajax({
            url: pcpcData.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'pcpc_restore_post',
                nonce: pcpcData.nonce,
                post_id: postId
            },
            success: function (res) {
                if (res.success) {
                    alert(res.data.message);
                    runSiteScan();
                } else {
                    alert('Error: ' + res.data.message);
                    $btn.prop('disabled', false).text('Restore Original');
                }
            }
        });
    });

    $btnFixSelected.on('click', function (e) {
        e.preventDefault();
        const selectedIds = [];
        $('.pcpc-post-checkbox:checked').each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) {
            alert('Please select at least one post to fix.');
            return;
        }

        if (!confirm(`Are you sure you want to strip unwanted 'n' artifacts (.n, !n, ?n, nn, nnn) from all ${selectedIds.length} selected post(s)? Backups will be saved automatically.`)) {
            return;
        }

        $btnFixSelected.prop('disabled', true);
        $loader.show();
        $loaderText.text(`Fixing ${selectedIds.length} posts...`);
        $progressBar.css('width', '50%');

        $.ajax({
            url: pcpcData.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'pcpc_fix_all',
                nonce: pcpcData.nonce,
                post_ids: selectedIds
            },
            success: function (res) {
                $progressBar.css('width', '100%');
                setTimeout(() => {
                    $loader.hide();
                    $btnFixSelected.prop('disabled', false);

                    if (res.success) {
                        alert(res.data.message);
                        runSiteScan();
                    } else {
                        alert('Bulk fix error: ' + (res.data ? res.data.message : 'Unknown error'));
                    }
                }, 300);
            },
            error: function () {
                $loader.hide();
                $btnFixSelected.prop('disabled', false);
                alert('Server error executing bulk fix.');
            }
        });
    });

    $(document).on('click', '.btn-preview-card', function (e) {
        e.preventDefault();
        const postId = $(this).data('id');
        currentPreviewPostId = postId;

        $('#modal-post-title').text('Loading Preview...');
        $('#modal-preview-before').html('<p style="text-align:center;">Loading card preview...</p>');
        $('#modal-preview-after').html('<p style="text-align:center;">Loading card preview...</p>');
        $modalPreview.fadeIn(200);

        $.ajax({
            url: pcpcData.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'pcpc_preview_card',
                nonce: pcpcData.nonce,
                post_id: postId,
                block_index: 0
            },
            success: function (res) {
                if (res.success) {
                    $('#modal-post-title').text('Post: ' + res.data.post_title);
                    $('#modal-preview-before').html(res.data.html_before);
                    $('#modal-preview-after').html(res.data.html_after);
                } else {
                    $('#modal-preview-before').html('<p style="color:red;">Failed to render preview.</p>');
                }
            }
        });
    });

    $('.pcpc-modal-close, .pcpc-modal-overlay').on('click', function () {
        $modalPreview.fadeOut(200);
    });

    $('#modal-btn-fix-this').on('click', function () {
        if (currentPreviewPostId) {
            $modalPreview.fadeOut(200);
            $(`#pcpc-row-${currentPreviewPostId} .btn-fix-single`).trigger('click');
        }
    });

    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }
});
