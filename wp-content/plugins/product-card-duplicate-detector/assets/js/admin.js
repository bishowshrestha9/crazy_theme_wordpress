/**
 * Product Card Duplicate Detector Admin JavaScript Handler
 */
(function ($) {
    'use strict';

    var pcddScannedDuplicates = [];

    $(document).ready(function () {
        initEvents();
    });

    function initEvents() {
        $('#pcdd-btn-scan').on('click', function (e) {
            e.preventDefault();
            runDuplicateScan();
        });

        $('#pcdd-filter-post-type, #pcdd-filter-min-occurrences').on('change', function () {
            updateExportForm();
            if (pcddScannedDuplicates.length > 0) {
                renderFilteredTable();
            }
        });

        $('#pcdd-search-input').on('keyup input', function () {
            updateExportForm();
            if (pcddScannedDuplicates.length > 0) {
                renderFilteredTable();
            }
        });
    }

    function updateExportForm() {
        $('#pcdd-export-post-type').val($('#pcdd-filter-post-type').val());
        $('#pcdd-export-min-occurrences').val($('#pcdd-filter-min-occurrences').val());
        $('#pcdd-export-search').val($('#pcdd-search-input').val());
    }

    function runDuplicateScan() {
        var $btn = $('#pcdd-btn-scan');
        var $btnExport = $('#pcdd-btn-export');
        var $progress = $('#pcdd-progress-container');
        var $progressBar = $('#pcdd-progress-bar');
        var $statusText = $('#pcdd-progress-status');

        $btn.prop('disabled', true).html('<span class="dashicons dashicons-update spin"></span> Scanning...');
        $btnExport.prop('disabled', true);
        $progress.slideDown(200);
        $progressBar.css('width', '25%');
        $statusText.text('Querying posts and matching product names...');

        var postType = $('#pcdd-filter-post-type').val();
        var minOccurrences = $('#pcdd-filter-min-occurrences').val();
        var search = $('#pcdd-search-input').val();

        updateExportForm();

        $.ajax({
            url: pcddData.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'pcdd_scan_duplicates',
                nonce: pcddData.nonce,
                post_type: postType,
                min_occurrences: minOccurrences,
                search: search
            },
            success: function (response) {
                $progressBar.css('width', '100%');

                if (response.success && response.data) {
                    var data = response.data;
                    pcddScannedDuplicates = data.duplicates || [];

                    $('#pcdd-stat-scanned').text(data.summary.total_scanned_posts.toLocaleString());
                    $('#pcdd-stat-cards').text(data.summary.total_cards_found.toLocaleString());
                    $('#pcdd-stat-duplicate-names').text(data.summary.total_duplicate_products.toLocaleString());
                    $('#pcdd-stat-duplicate-occurrences').text(data.summary.total_duplicate_occurrences.toLocaleString());
                    $('#pcdd-last-scanned-time').text('Last scanned: ' + data.timestamp);

                    $statusText.text('Scan complete! Found ' + data.summary.total_duplicate_products + ' duplicate product names.');

                    setTimeout(function () {
                        $progress.slideUp(300);
                    }, 1200);

                    renderFilteredTable();

                    if (pcddScannedDuplicates.length > 0) {
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
                $btn.prop('disabled', false).html('<span class="dashicons dashicons-search"></span> Scan Duplicates');
            }
        });
    }

    function renderFilteredTable() {
        var minOccurrencesFilter = parseInt($('#pcdd-filter-min-occurrences').val(), 10) || 2;
        var searchQuery = $('#pcdd-search-input').val().toLowerCase().trim();

        var filtered = pcddScannedDuplicates.filter(function (item) {
            if (item.count < minOccurrencesFilter) {
                return false;
            }
            if (searchQuery !== '') {
                var nameMatch = item.product_name.toLowerCase().indexOf(searchQuery) !== -1;
                var postMatch = false;
                if (item.posts && item.posts.length > 0) {
                    $.each(item.posts, function (i, p) {
                        if (p.title.toLowerCase().indexOf(searchQuery) !== -1 || p.id.toString().indexOf(searchQuery) !== -1) {
                            postMatch = true;
                            return false;
                        }
                    });
                }
                if (!nameMatch && !postMatch) {
                    return false;
                }
            }
            return true;
        });

        $('#pcdd-results-count').text(filtered.length);
        var $tbody = $('#pcdd-results-tbody');
        $tbody.empty();

        if (filtered.length === 0) {
            $tbody.html(
                '<tr class="pcdd-empty-row">' +
                '<td colspan="6">' +
                '<div class="pcdd-empty-state">' +
                '<span class="dashicons dashicons-yes-alt" style="font-size: 36px; width: 36px; height: 36px; color: #10b981;"></span>' +
                '<h3>No duplicate product names found</h3>' +
                '<p>Great news! No product names match your active filter threshold.</p>' +
                '</div>' +
                '</td>' +
                '</tr>'
            );
            $('#pcdd-btn-export').prop('disabled', true);
            return;
        }

        $('#pcdd-btn-export').prop('disabled', false);

        $.each(filtered, function (idx, item) {
            var indexNum = idx + 1;
            var detailRowId = 'pcdd-detail-row-' + idx;

            var postsChipsHtml = '<div class="pcdd-post-chips">';
            $.each(item.posts, function (pIdx, p) {
                postsChipsHtml += '<div class="pcdd-post-chip">' +
                    '<a href="' + escapeHtml(p.url) + '" target="_blank" class="pcdd-post-chip-title">#' + p.id + ' - ' + escapeHtml(p.title) + '</a>' +
                    '<div class="pcdd-post-chip-meta">' +
                        '<span class="pcdd-chip-badge">' + escapeHtml(p.post_type) + '</span>' +
                        '<a href="' + escapeHtml(p.edit_url) + '" target="_blank" style="color:#2563eb; text-decoration:none; font-weight:600;">Edit</a>' +
                    '</div>' +
                '</div>';
            });
            postsChipsHtml += '</div>';

            var rowHtml = '<tr>' +
                '<td>' + indexNum + '</td>' +
                '<td><span class="pcdd-product-name-title">' + escapeHtml(item.product_name) + '</span></td>' +
                '<td><span class="pcdd-badge-count">' + item.count + ' cards</span></td>' +
                '<td><span class="pcdd-badge-posts">' + item.posts_count + ' posts</span></td>' +
                '<td>' + postsChipsHtml + '</td>' +
                '<td>' +
                    '<button type="button" class="pcdd-btn-toggle-details" data-target="#' + detailRowId + '">' +
                        '<span class="dashicons dashicons-arrow-down-alt2"></span> View' +
                    '</button>' +
                '</td>' +
            '</tr>';

            $tbody.append(rowHtml);
        });

        $('.pcdd-btn-toggle-details').on('click', function () {
            var targetId = $(this).data('target');
            $(targetId).toggle();
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
