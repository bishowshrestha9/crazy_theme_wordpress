/**
 * Standalone Duplicate Post Detector Admin JavaScript Handler
 */
(function ($) {
    'use strict';

    var dpdScannedGroups = [];

    $(document).ready(function () {
        initEvents();
    });

    function initEvents() {
        $('#dpd-btn-scan').on('click', function (e) {
            e.preventDefault();
            runDuplicateScan();
        });

        $('#dpd-filter-mode, #dpd-filter-post-type, #dpd-filter-post-status').on('change', function () {
            updateExportForm();
            if (dpdScannedGroups.length > 0) {
                renderFilteredTable();
            }
        });

        $('#dpd-search-input').on('keyup input', function () {
            updateExportForm();
            if (dpdScannedGroups.length > 0) {
                renderFilteredTable();
            }
        });
    }

    function updateExportForm() {
        $('#dpd-export-mode').val($('#dpd-filter-mode').val());
        $('#dpd-export-post-type').val($('#dpd-filter-post-type').val());
        $('#dpd-export-post-status').val($('#dpd-filter-post-status').val());
        $('#dpd-export-search').val($('#dpd-search-input').val());
    }

    function runDuplicateScan() {
        var $btn = $('#dpd-btn-scan');
        var $btnExport = $('#dpd-btn-export');
        var $progress = $('#dpd-progress-container');
        var $progressBar = $('#dpd-progress-bar');
        var $statusText = $('#dpd-progress-status');

        $btn.prop('disabled', true).html('<span class="dashicons dashicons-update spin"></span> Scanning...');
        $btnExport.prop('disabled', true);
        $progress.slideDown(200);
        $progressBar.css('width', '25%');
        $statusText.text('Analyzing post slugs across database...');

        var mode = $('#dpd-filter-mode').val();
        var postType = $('#dpd-filter-post-type').val();
        var postStatus = $('#dpd-filter-post-status').val();
        var search = $('#dpd-search-input').val();

        updateExportForm();

        $.ajax({
            url: dpdData.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'dpd_scan_duplicates',
                nonce: dpdData.nonce,
                mode: mode,
                post_type: postType,
                post_status: postStatus,
                search: search
            },
            success: function (response) {
                $progressBar.css('width', '100%');

                if (response.success && response.data) {
                    var data = response.data;
                    dpdScannedGroups = data.duplicate_groups || [];

                    $('#dpd-stat-scanned').text(data.summary.total_scanned_posts.toLocaleString());
                    $('#dpd-stat-duplicate-groups').text(data.summary.duplicate_groups_count.toLocaleString());
                    $('#dpd-stat-total-duplicates').text(data.summary.total_duplicate_posts.toLocaleString());
                    $('#dpd-stat-max-duplication').text(data.summary.max_duplication_count.toLocaleString());
                    $('#dpd-last-scanned-time').text('Last scanned: ' + data.timestamp);

                    $statusText.text('Scan complete! Found ' + data.summary.duplicate_groups_count + ' duplicate slug groups (' + data.summary.total_duplicate_posts + ' total duplicate posts).');

                    setTimeout(function () {
                        $progress.slideUp(300);
                    }, 1200);

                    renderFilteredTable();

                    if (dpdScannedGroups.length > 0) {
                        $btnExport.prop('disabled', false);
                    }
                } else {
                    $statusText.text('Scan failed: ' + (response.data ? response.data.message : 'Unknown error'));
                }
            },
            error: function (xhr, status, error) {
                $progressBar.css('width', '100%');
                $statusText.text('AJAX error occurred: ' + error);
            },
            complete: function () {
                $btn.prop('disabled', false).html('<span class="dashicons dashicons-search"></span> Scan Duplicate Posts');
            }
        });
    }

    function renderFilteredTable() {
        var searchQuery = $('#dpd-search-input').val().toLowerCase().trim();

        var filtered = dpdScannedGroups.filter(function (group) {
            if (searchQuery !== '') {
                var groupMatch = group.group_key.toLowerCase().indexOf(searchQuery) !== -1;
                var postMatch = false;
                if (group.posts && group.posts.length > 0) {
                    $.each(group.posts, function (i, p) {
                        if (p.title.toLowerCase().indexOf(searchQuery) !== -1 ||
                            p.slug.toLowerCase().indexOf(searchQuery) !== -1 ||
                            p.id.toString().indexOf(searchQuery) !== -1) {
                            postMatch = true;
                            return false;
                        }
                    });
                }
                if (!groupMatch && !postMatch) {
                    return false;
                }
            }
            return true;
        });

        $('#dpd-results-count').text(filtered.length);
        var $tbody = $('#dpd-results-tbody');
        $tbody.empty();

        if (filtered.length === 0) {
            $tbody.html(
                '<tr class="dpd-empty-row">' +
                '<td colspan="4">' +
                '<div class="dpd-empty-state">' +
                '<span class="dashicons dashicons-yes-alt" style="font-size: 36px; width: 36px; height: 36px; color: #10b981;"></span>' +
                '<h3>No duplicate post slugs found</h3>' +
                '<p>Great news! No duplicate post slugs match your active search filters.</p>' +
                '</div>' +
                '</td>' +
                '</tr>'
            );
            $('#dpd-btn-export').prop('disabled', true);
            return;
        }

        $('#dpd-btn-export').prop('disabled', false);

        $.each(filtered, function (idx, group) {
            var indexNum = idx + 1;

            var postsHtml = '<div class="dpd-post-list">';
            $.each(group.posts, function (pIdx, p) {
                var statusClass = 'dpd-badge-status-other';
                if (p.post_status === 'publish') {
                    statusClass = 'dpd-badge-status-publish';
                } else if (p.post_status === 'draft') {
                    statusClass = 'dpd-badge-status-draft';
                }

                postsHtml += '<div class="dpd-post-card">' +
                    '<div>' +
                        '<a href="' + escapeHtml(p.url) + '" target="_blank" class="dpd-post-card-title">#' + p.id + ' - ' + escapeHtml(p.title) + '</a>' +
                        '<div class="dpd-post-card-slug">Slug: ' + escapeHtml(p.slug) + '</div>' +
                    '</div>' +
                    '<div class="dpd-post-card-meta">' +
                        '<span class="dpd-badge dpd-badge-type">' + escapeHtml(p.post_type) + '</span>' +
                        '<span class="dpd-badge ' + statusClass + '">' + escapeHtml(p.post_status) + '</span>' +
                        '<a href="' + escapeHtml(p.edit_url) + '" target="_blank" class="dpd-action-link dpd-action-edit">' +
                            '<span class="dashicons dashicons-edit"></span> Edit' +
                        '</a>' +
                        '<a href="' + escapeHtml(p.url) + '" target="_blank" class="dpd-action-link dpd-action-view">' +
                            '<span class="dashicons dashicons-external"></span> View' +
                        '</a>' +
                    '</div>' +
                '</div>';
            });
            postsHtml += '</div>';

            var rowHtml = '<tr>' +
                '<td>' + indexNum + '</td>' +
                '<td><span class="dpd-group-slug">' + escapeHtml(group.group_key) + '</span></td>' +
                '<td><span class="dpd-badge-count">' + group.count + ' posts</span></td>' +
                '<td>' + postsHtml + '</td>' +
            '</tr>';

            $tbody.append(rowHtml);
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

})(jQuery);
