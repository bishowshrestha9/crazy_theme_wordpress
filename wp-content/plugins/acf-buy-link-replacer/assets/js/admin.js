jQuery(document).ready(function ($) {
    'use strict';

    // Select All Checkbox Handler
    $('#ablr-select-all').on('change', function () {
        const isChecked = $(this).is(':checked');
        $('.ablr-row-checkbox:not(:disabled)').prop('checked', isChecked);
        updateBulkButtonState();
    });

    $(document).on('change', '.ablr-row-checkbox', function () {
        updateBulkButtonState();
    });

    function updateBulkButtonState() {
        const checkedCount = $('.ablr-row-checkbox:checked').length;
        $('#ablr-selected-count').text(checkedCount);
        if (checkedCount > 0) {
            $('#ablr-btn-replace-selected').prop('disabled', false);
        } else {
            $('#ablr-btn-replace-selected').prop('disabled', true);
        }
    }

    // Single Replace Handler
    $(document).on('click', '.ablr-btn-replace-single', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const postId = $btn.data('id');
        const searchPattern = $('#ablr_search_pattern').val();
        const replacePattern = $('#ablr_replace_pattern').val();
        const textTarget = $('#ablr_text_target').val();
        const $row = $('#ablr-row-' + postId);

        if (!postId) return;

        $btn.prop('disabled', true).html('<span class="ablr-btn-spinner"></span> Replacing...');

        $.ajax({
            url: ablrData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'ablr_replace_single',
                nonce: ablrData.nonce,
                post_id: postId,
                search_pattern: searchPattern,
                replace_pattern: replacePattern,
                text_target: textTarget
            },
            success: function (res) {
                if (res.success) {
                    $row.addClass('row-replaced');
                    $row.find('.ablr-status-cell').html('<span class="ablr-badge ablr-badge-success">Updated</span>');
                    if (res.data.snippet) {
                        $row.find('.ablr-snippet-cell').html(res.data.snippet);
                    }
                    $row.find('.ablr-row-checkbox').prop('checked', false).prop('disabled', true);
                    $btn.replaceWith('<span class="dashicons dashicons-yes-alt" style="color: #16a34a; font-size: 20px;"></span>');

                    let replacedVal = parseInt($('#ablr-stat-replaced').text(), 10) || 0;
                    $('#ablr-stat-replaced').text(replacedVal + 1);
                    updateBulkButtonState();
                } else {
                    alert('Error: ' + (res.data || 'Failed to replace link in post.'));
                    $btn.prop('disabled', false).text('Replace');
                }
            },
            error: function () {
                alert('Server or network error while replacing post link.');
                $btn.prop('disabled', false).text('Replace');
            }
        });
    });

    // Bulk Selected Replace Handler
    $('#ablr-btn-replace-selected').on('click', function (e) {
        e.preventDefault();

        const selectedIds = [];
        $('.ablr-row-checkbox:checked').each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) return;

        if (!confirm('Are you sure you want to replace links in ' + selectedIds.length + ' selected post(s)?')) {
            return;
        }

        const $bulkBtn = $(this);
        const searchPattern = $('#ablr_search_pattern').val();
        const replacePattern = $('#ablr_replace_pattern').val();
        const textTarget = $('#ablr_text_target').val();

        $bulkBtn.prop('disabled', true).html('<span class="ablr-btn-spinner"></span> Processing...');

        $.ajax({
            url: ablrData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'ablr_replace_bulk',
                nonce: ablrData.nonce,
                post_ids: selectedIds,
                search_pattern: searchPattern,
                replace_pattern: replacePattern,
                text_target: textTarget
            },
            success: function (res) {
                if (res.success) {
                    if (res.data.updated_items && res.data.updated_items.length > 0) {
                        res.data.updated_items.forEach(function (item) {
                            const $row = $('#ablr-row-' + item.post_id);
                            $row.addClass('row-replaced');
                            $row.find('.ablr-status-cell').html('<span class="ablr-badge ablr-badge-success">Updated</span>');
                            if (item.snippet) {
                                $row.find('.ablr-snippet-cell').html(item.snippet);
                            }
                            $row.find('.ablr-row-checkbox').prop('checked', false).prop('disabled', true);
                            $row.find('.ablr-btn-replace-single').replaceWith('<span class="dashicons dashicons-yes-alt" style="color: #16a34a; font-size: 20px;"></span>');
                        });
                    }

                    let replacedVal = parseInt($('#ablr-stat-replaced').text(), 10) || 0;
                    $('#ablr-stat-replaced').text(replacedVal + (res.data.updated_count || 0));
                    alert('Successfully updated ' + (res.data.updated_count || 0) + ' post(s).');
                } else {
                    alert('Error: ' + (res.data || 'Bulk replace failed.'));
                }
                $bulkBtn.html('<span class="dashicons dashicons-update" style="font-size:16px; vertical-align:middle;"></span> Replace Selected (<span id="ablr-selected-count">0</span>)');
                updateBulkButtonState();
            },
            error: function () {
                alert('Server error occurred during bulk update.');
                $bulkBtn.prop('disabled', false).html('<span class="dashicons dashicons-update" style="font-size:16px; vertical-align:middle;"></span> Replace Selected (<span id="ablr-selected-count">0</span>)');
                updateBulkButtonState();
            }
        });
    });

    // Replace All Handler
    $('#ablr-btn-replace-all').on('click', function (e) {
        e.preventDefault();

        const allIds = [];
        $('.ablr-row-checkbox:not(:disabled)').each(function () {
            allIds.push($(this).val());
        });

        if (allIds.length === 0) {
            alert('No pending posts available to replace.');
            return;
        }

        if (!confirm('Are you sure you want to replace links in ALL ' + allIds.length + ' matched post(s)?')) {
            return;
        }

        const $allBtn = $(this);
        const searchPattern = $('#ablr_search_pattern').val();
        const replacePattern = $('#ablr_replace_pattern').val();
        const textTarget = $('#ablr_text_target').val();

        $allBtn.prop('disabled', true).html('<span class="ablr-btn-spinner"></span> Processing All...');

        $.ajax({
            url: ablrData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'ablr_replace_bulk',
                nonce: ablrData.nonce,
                post_ids: allIds,
                search_pattern: searchPattern,
                replace_pattern: replacePattern,
                text_target: textTarget
            },
            success: function (res) {
                if (res.success) {
                    if (res.data.updated_items && res.data.updated_items.length > 0) {
                        res.data.updated_items.forEach(function (item) {
                            const $row = $('#ablr-row-' + item.post_id);
                            $row.addClass('row-replaced');
                            $row.find('.ablr-status-cell').html('<span class="ablr-badge ablr-badge-success">Updated</span>');
                            if (item.snippet) {
                                $row.find('.ablr-snippet-cell').html(item.snippet);
                            }
                            $row.find('.ablr-row-checkbox').prop('checked', false).prop('disabled', true);
                            $row.find('.ablr-btn-replace-single').replaceWith('<span class="dashicons dashicons-yes-alt" style="color: #16a34a; font-size: 20px;"></span>');
                        });
                    }

                    let replacedVal = parseInt($('#ablr-stat-replaced').text(), 10) || 0;
                    $('#ablr-stat-replaced').text(replacedVal + (res.data.updated_count || 0));
                    alert('Successfully updated ALL ' + (res.data.updated_count || 0) + ' post(s).');
                } else {
                    alert('Error: ' + (res.data || 'Replace all failed.'));
                }
                $allBtn.prop('disabled', false).html('<span class="dashicons dashicons-redo" style="font-size:16px; vertical-align:middle;"></span> Replace All Matched');
                updateBulkButtonState();
            },
            error: function () {
                alert('Server error while performing bulk update.');
                $allBtn.prop('disabled', false).html('<span class="dashicons dashicons-redo" style="font-size:16px; vertical-align:middle;"></span> Replace All Matched');
                updateBulkButtonState();
            }
        });
    });
});
