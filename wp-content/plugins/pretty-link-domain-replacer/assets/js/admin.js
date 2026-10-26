jQuery(document).ready(function ($) {
    'use strict';

    // Select All Checkbox Handler
    $('#pldr-select-all').on('change', function () {
        const isChecked = $(this).is(':checked');
        $('.pldr-row-checkbox:not(:disabled)').prop('checked', isChecked);
        updateBulkButtonState();
    });

    $(document).on('change', '.pldr-row-checkbox', function () {
        updateBulkButtonState();
    });

    function updateBulkButtonState() {
        const checkedCount = $('.pldr-row-checkbox:checked').length;
        $('#pldr-selected-count').text(checkedCount);
        if (checkedCount > 0) {
            $('#pldr-btn-replace-selected').prop('disabled', false);
        } else {
            $('#pldr-btn-replace-selected').prop('disabled', true);
        }
    }

    // Single Link Replace Handler
    $(document).on('click', '.pldr-btn-replace-single', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const linkId = $btn.data('id');
        const searchDomain = $('#pldr_search_domain').val();
        const replaceDomain = $('#pldr_replace_domain').val();
        const $row = $('#pldr-row-' + linkId);

        if (!linkId) return;

        $btn.prop('disabled', true).html('<span class="pldr-btn-spinner"></span> Replacing...');

        $.ajax({
            url: pldrData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'pldr_replace_single',
                nonce: pldrData.nonce,
                id: linkId,
                search_domain: searchDomain,
                replace_domain: replaceDomain
            },
            success: function (res) {
                if (res.success) {
                    $row.addClass('row-replaced');
                    $row.find('.pldr-status-cell').html('<span class="pldr-badge pldr-badge-success">Updated</span>');
                    $row.find('.pldr-target-cell').html('<code>' + escapeHtml(res.data.new_url) + '</code>');
                    $row.find('.pldr-row-checkbox').prop('checked', false).prop('disabled', true);
                    $btn.replaceWith('<span class="dashicons dashicons-yes-alt" style="color: #16a34a; font-size: 20px;"></span>');

                    // Increment stats badge if present
                    let replacedVal = parseInt($('#pldr-stat-replaced').text(), 10) || 0;
                    $('#pldr-stat-replaced').text(replacedVal + 1);
                    updateBulkButtonState();
                } else {
                    alert('Error: ' + (res.data || 'Failed to replace link.'));
                    $btn.prop('disabled', false).text('Replace');
                }
            },
            error: function () {
                alert('Server or connection error occurred.');
                $btn.prop('disabled', false).text('Replace');
            }
        });
    });

    // Bulk Selected Replace Handler
    $('#pldr-btn-replace-selected').on('click', function (e) {
        e.preventDefault();

        const selectedIds = [];
        $('.pldr-row-checkbox:checked').each(function () {
            selectedIds.push($(this).val());
        });

        if (selectedIds.length === 0) return;

        if (!confirm('Are you sure you want to replace ' + selectedIds.length + ' selected Pretty Link(s)?')) {
            return;
        }

        const $bulkBtn = $(this);
        const searchDomain = $('#pldr_search_domain').val();
        const replaceDomain = $('#pldr_replace_domain').val();

        $bulkBtn.prop('disabled', true).html('<span class="pldr-btn-spinner"></span> Processing...');

        $.ajax({
            url: pldrData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'pldr_replace_bulk',
                nonce: pldrData.nonce,
                ids: selectedIds,
                search_domain: searchDomain,
                replace_domain: replaceDomain
            },
            success: function (res) {
                if (res.success) {
                    if (res.data.updated_items && res.data.updated_items.length > 0) {
                        res.data.updated_items.forEach(function (item) {
                            const $row = $('#pldr-row-' + item.id);
                            $row.addClass('row-replaced');
                            $row.find('.pldr-status-cell').html('<span class="pldr-badge pldr-badge-success">Updated</span>');
                            $row.find('.pldr-target-cell').html('<code>' + escapeHtml(item.new_url) + '</code>');
                            $row.find('.pldr-row-checkbox').prop('checked', false).prop('disabled', true);
                            $row.find('.pldr-btn-replace-single').replaceWith('<span class="dashicons dashicons-yes-alt" style="color: #16a34a; font-size: 20px;"></span>');
                        });
                    }

                    let replacedVal = parseInt($('#pldr-stat-replaced').text(), 10) || 0;
                    $('#pldr-stat-replaced').text(replacedVal + (res.data.updated_count || 0));
                    alert('Successfully updated ' + (res.data.updated_count || 0) + ' Pretty Link(s).');
                } else {
                    alert('Error: ' + (res.data || 'Bulk replace failed.'));
                }
                $bulkBtn.html('<span class="dashicons dashicons-update" style="font-size:16px; vertical-align:middle;"></span> Replace Selected (<span id="pldr-selected-count">0</span>)');
                updateBulkButtonState();
            },
            error: function () {
                alert('Server or connection error occurred during bulk update.');
                $bulkBtn.prop('disabled', false).html('<span class="dashicons dashicons-update" style="font-size:16px; vertical-align:middle;"></span> Replace Selected (<span id="pldr-selected-count">0</span>)');
                updateBulkButtonState();
            }
        });
    });

    // Replace All Handler
    $('#pldr-btn-replace-all').on('click', function (e) {
        e.preventDefault();

        const allIds = [];
        $('.pldr-row-checkbox:not(:disabled)').each(function () {
            allIds.push($(this).val());
        });

        if (allIds.length === 0) {
            alert('No pending links available to replace.');
            return;
        }

        if (!confirm('Are you sure you want to replace ALL ' + allIds.length + ' matched Pretty Link(s)? This will update their target domains.')) {
            return;
        }

        const $allBtn = $(this);
        const searchDomain = $('#pldr_search_domain').val();
        const replaceDomain = $('#pldr_replace_domain').val();

        $allBtn.prop('disabled', true).html('<span class="pldr-btn-spinner"></span> Processing All...');

        $.ajax({
            url: pldrData.ajaxUrl,
            type: 'POST',
            data: {
                action: 'pldr_replace_bulk',
                nonce: pldrData.nonce,
                ids: allIds,
                search_domain: searchDomain,
                replace_domain: replaceDomain
            },
            success: function (res) {
                if (res.success) {
                    if (res.data.updated_items && res.data.updated_items.length > 0) {
                        res.data.updated_items.forEach(function (item) {
                            const $row = $('#pldr-row-' + item.id);
                            $row.addClass('row-replaced');
                            $row.find('.pldr-status-cell').html('<span class="pldr-badge pldr-badge-success">Updated</span>');
                            $row.find('.pldr-target-cell').html('<code>' + escapeHtml(item.new_url) + '</code>');
                            $row.find('.pldr-row-checkbox').prop('checked', false).prop('disabled', true);
                            $row.find('.pldr-btn-replace-single').replaceWith('<span class="dashicons dashicons-yes-alt" style="color: #16a34a; font-size: 20px;"></span>');
                        });
                    }

                    let replacedVal = parseInt($('#pldr-stat-replaced').text(), 10) || 0;
                    $('#pldr-stat-replaced').text(replacedVal + (res.data.updated_count || 0));
                    alert('Successfully updated ALL ' + (res.data.updated_count || 0) + ' Pretty Link(s).');
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

    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});
