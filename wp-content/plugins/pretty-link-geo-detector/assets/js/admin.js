jQuery(document).ready(function ($) {
    let allLinksData = [];

    $('#plgd-start-scan').on('click', function () {
        const $btn = $(this);
        $btn.prop('disabled', true);
        $('#plgd-progress-container').show();
        $('#plgd-progress-bar').css('width', '30%');
        $('#plgd-progress-status').text('Scanning Pretty Links database for GEO rules...');

        $.ajax({
            url: plgdData.ajax_url,
            type: 'POST',
            data: {
                action: 'plgd_scan_links',
                security: plgdData.nonce
            },
            success: function (response) {
                $('#plgd-progress-bar').css('width', '100%');

                if (response.success && response.data) {
                    const data = response.data;
                    allLinksData = data.links || [];

                    $('#stat-total-count').text(data.total);
                    $('#stat-geo-count').text(data.geo_count);
                    $('#stat-standard-count').text(data.standard_count);

                    renderTable(allLinksData);

                    $('#plgd-progress-status').text('Scan completed! Discovered ' + data.geo_count + ' GEO-redirect links out of ' + data.total + ' total links.');
                    $('#plgd-summary-cards, #plgd-results-card, #plgd-filter-container').show();
                    $('#plgd-export-btn').prop('disabled', false);
                } else {
                    $('#plgd-progress-status').text('Scan error: ' + (response.data || 'Unknown error'));
                }
            },
            error: function () {
                $('#plgd-progress-bar').css('width', '100%');
                $('#plgd-progress-status').text('An error occurred during AJAX request.');
            },
            complete: function () {
                $btn.prop('disabled', false);
            }
        });
    });

    // Handle CSV Export
    $('#plgd-export-btn').on('click', function () {
        window.location.href = plgdData.download_url;
    });

    // Search and Status Filters
    $('#plgd-search-input, #plgd-status-filter').on('input change', function () {
        filterTable();
    });

    function filterTable() {
        const query = $('#plgd-search-input').val().toLowerCase();
        const filterStatus = $('#plgd-status-filter').val();

        const filtered = allLinksData.filter(function (link) {
            const matchesSearch = (link.slug && link.slug.toLowerCase().includes(query)) ||
                (link.target_url && link.target_url.toLowerCase().includes(query)) ||
                (link.name && link.name.toLowerCase().includes(query));

            let matchesStatus = true;
            if (filterStatus === 'geo') {
                matchesStatus = (link.has_geo === 'YES');
            } else if (filterStatus === 'none') {
                matchesStatus = (link.has_geo === 'NO');
            }

            return matchesSearch && matchesStatus;
        });

        renderTable(filtered);
    }

    function renderTable(links) {
        const $tbody = $('#plgd-results-body');
        $tbody.empty();

        if (links.length === 0) {
            $tbody.append('<tr><td colspan="5" style="text-align:center; padding: 20px; color: #64748b;">No links found matching criteria.</td></tr>');
            return;
        }

        links.forEach(function (link) {
            const row = `
                <tr>
                    <td>${link.id}</td>
                    <td><strong>${escapeHtml(link.name)}</strong></td>
                    <td><span class="plgd-code">${escapeHtml(link.slug)}</span></td>
                    <td><a href="${escapeHtml(link.target_url)}" target="_blank" rel="noopener">${escapeHtml(link.target_url)}</a></td>
                    <td>${escapeHtml(link.geo_details)}</td>
                </tr>
            `;
            $tbody.append(row);
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text.toString()
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
});
