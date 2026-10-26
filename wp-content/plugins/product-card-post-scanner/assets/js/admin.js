/**
 * Standalone Product Card Post Scanner JavaScript Handler
 */
(function ($) {
    'use strict';

    var pcpsScannedData = [];

    $(document).ready(function () {
        initEvents();
    });

    function initEvents() {
        $('#pcps-btn-scan').on('click', function (e) {
            e.preventDefault();
            runPostScan();
        });

        $('#pcps-filter-post-type, #pcps-filter-post-status').on('change', function () {
            updateExportForm();
            if (pcpsScannedData.length > 0) {
                renderFilteredTable();
            }
        });

        $('#pcps-search-input').on('keyup input', function () {
            updateExportForm();
            if (pcpsScannedData.length > 0) {
                renderFilteredTable();
            }
        });
    }

    function updateExportForm() {
        $('#pcps-export-post-type').val($('#pcps-filter-post-type').val());
        $('#pcps-export-post-status').val($('#pcps-filter-post-status').val());
        $('#pcps-export-search').val($('#pcps-search-input').val());
    }

    function runPostScan() {
        var $btn = $('#pcps-btn-scan');
        var $btnExport = $('#pcps-btn-export');
        var $progress = $('#pcps-progress-container');
        var $progressBar = $('#pcps-progress-bar');
        var $statusText = $('#pcps-progress-status');

        $btn.prop('disabled', true).html('<span class="dashicons dashicons-update spin"></span> Scanning...');
        $btnExport.prop('disabled', true);
        $progress.slideDown(200);
        $progressBar.css('width', '20%');
        $statusText.text('Querying WordPress database and parsing post blocks...');

        var postType = $('#pcps-filter-post-type').val();
        var postStatus = $('#pcps-filter-post-status').val();
        var search = $('#pcps-search-input').val();

        updateExportForm();

        $.ajax({
            url: pcpsData.ajaxUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'pcps_scan_posts',
                nonce: pcpsData.nonce,
                post_type: postType,
                post_status: postStatus,
                search: search
            },
            success: function (response) {
                $progressBar.css('width', '100%');

                if (response.success && response.data) {
                    var data = response.data;
                    pcpsScannedData = data.posts || [];

                    $('#pcps-stat-scanned').text(data.summary.total_scanned_posts.toLocaleString());
                    $('#pcps-stat-matched').text(data.summary.matched_posts_count.toLocaleString());
                    $('#pcps-stat-cards-count').text(data.summary.total_cards_found.toLocaleString());
                    $('#pcps-stat-products-count').text(data.summary.unique_products.toLocaleString());
                    $('#pcps-last-scanned-time').text('Last scanned: ' + data.timestamp);

                    $statusText.text('Scan complete! Found ' + data.summary.matched_posts_count + ' matching posts.');

                    setTimeout(function () {
                        $progress.slideUp(300);
                    }, 1200);

                    renderFilteredTable();

                    if (pcpsScannedData.length > 0) {
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
                $btn.prop('disabled', false).html('<span class="dashicons dashicons-search"></span> Scan Site Posts');
            }
        });
    }

    function renderFilteredTable() {
        var postTypeFilter = $('#pcps-filter-post-type').val();
        var postStatusFilter = $('#pcps-filter-post-status').val();
        var searchQuery = $('#pcps-search-input').val().toLowerCase().trim();

        var filtered = pcpsScannedData.filter(function (post) {
            if (postTypeFilter !== 'all' && post.post_type !== postTypeFilter) {
                return false;
            }
            if (postStatusFilter !== 'all' && post.post_status !== postStatusFilter) {
                return false;
            }
            if (searchQuery !== '') {
                var titleMatch = post.title.toLowerCase().indexOf(searchQuery) !== -1;
                var idMatch = post.id.toString().indexOf(searchQuery) !== -1;
                var productMatch = post.product_list.toLowerCase().indexOf(searchQuery) !== -1;
                if (!titleMatch && !idMatch && !productMatch) {
                    return false;
                }
            }
            return true;
        });

        $('#pcps-results-count').text(filtered.length);
        var $tbody = $('#pcps-results-tbody');
        $tbody.empty();

        if (filtered.length === 0) {
            $tbody.html(
                '<tr class="pcps-empty-row">' +
                '<td colspan="9">' +
                '<div class="pcps-empty-state">' +
                '<span class="dashicons dashicons-warning" style="font-size: 36px; width: 36px; height: 36px; color: #f59e0b;"></span>' +
                '<h3>No matching posts found</h3>' +
                '<p>No posts matching your active filters contain the Product Card block.</p>' +
                '</div>' +
                '</td>' +
                '</tr>'
            );
            $('#pcps-btn-export').prop('disabled', true);
            return;
        }

        $('#pcps-btn-export').prop('disabled', false);

        $.each(filtered, function (idx, item) {
            var indexNum = idx + 1;
            var statusBadgeClass = 'pcps-badge-status-other';
            if (item.post_status === 'publish') {
                statusBadgeClass = 'pcps-badge-status-publish';
            } else if (item.post_status === 'draft') {
                statusBadgeClass = 'pcps-badge-status-draft';
            }

            var productsHtml = '';
            if (item.products && item.products.length > 0) {
                $.each(item.products, function (i, pname) {
                    productsHtml += '<span class="pcps-product-tag">' + escapeHtml(pname) + '</span>';
                });
            } else {
                productsHtml = '<span style="color:#94a3b8; font-style:italic;">Not Specified</span>';
            }

            var rowHtml = '<tr>' +
                '<td>' + indexNum + '</td>' +
                '<td><strong>#' + item.id + '</strong></td>' +
                '<td>' +
                    '<a href="' + escapeHtml(item.url) + '" target="_blank" class="pcps-post-title-link">' + escapeHtml(item.title) + '</a>' +
                    '<div class="pcps-post-url-sub">' + escapeHtml(item.url) + '</div>' +
                '</td>' +
                '<td><span class="pcps-badge pcps-badge-type">' + escapeHtml(item.post_type) + '</span></td>' +
                '<td><span class="pcps-badge ' + statusBadgeClass + '">' + escapeHtml(item.post_status) + '</span></td>' +
                '<td><span class="pcps-card-count-badge">' + item.card_count + '</span></td>' +
                '<td>' + productsHtml + '</td>' +
                '<td>' + escapeHtml(item.date) + '</td>' +
                '<td>' +
                    '<div class="pcps-action-links">' +
                        '<a href="' + escapeHtml(item.edit_url) + '" target="_blank" class="pcps-action-btn pcps-action-edit">' +
                            '<span class="dashicons dashicons-edit"></span> Edit' +
                        '</a>' +
                        '<a href="' + escapeHtml(item.url) + '" target="_blank" class="pcps-action-btn pcps-action-view">' +
                            '<span class="dashicons dashicons-external"></span> View' +
                        '</a>' +
                    '</div>' +
                '</td>' +
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
