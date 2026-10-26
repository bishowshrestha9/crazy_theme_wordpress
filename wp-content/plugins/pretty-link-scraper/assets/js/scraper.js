jQuery(document).ready(function ($) {
    let scrapedLinks = [];
    let resolvedResults = [];
    let isScraping = false;

    $('#plsc-start-scrape').on('click', function () {
        if (isScraping) return;

        const targetUrl = $('#plsc-target-url').val();
        if (!targetUrl) {
            alert('Please enter a valid URL');
            return;
        }

        startScrape(targetUrl);
    });

    function startScrape(url) {
        isScraping = true;
        $('#plsc-start-scrape').prop('disabled', true);
        $('#plsc-results').hide();
        $('#plsc-progress').show();
        $('#plsc-progress-fill').css('width', '5%');
        $('#plsc-status-text').text('Fetching page content...');
        $('#plsc-results-body').empty();

        scrapedLinks = [];
        resolvedResults = [];

        $.ajax({
            url: plscData.ajax_url,
            type: 'POST',
            data: {
                action: 'plsc_scrape_url',
                url: url,
                security: plscData.nonce
            },
            success: function (response) {
                if (response.success) {
                    scrapedLinks = response.data;
                    if (scrapedLinks.length === 0) {
                        $('#plsc-status-text').text('No pretty links found on this page.');
                        isScraping = false;
                        $('#plsc-start-scrape').prop('disabled', false);
                    } else {
                        $('#plsc-status-text').text(`Found ${scrapedLinks.length} potential links. Resolving targets...`);
                        $('#plsc-results').show();
                        resolveLinksSequentially(0);
                    }
                } else {
                    $('#plsc-status-text').text('Error: ' + response.data);
                    isScraping = false;
                    $('#plsc-start-scrape').prop('disabled', false);
                }
            }
        });
    }

    function resolveLinksSequentially(index) {
        if (index >= scrapedLinks.length) {
            $('#plsc-status-text').text('All links resolved.');
            isScraping = false;
            $('#plsc-start-scrape').prop('disabled', false);
            $('#plsc-progress-fill').css('width', '100%');
            return;
        }

        const link = scrapedLinks[index];
        const progress = 10 + (index / scrapedLinks.length) * 90;
        $('#plsc-progress-fill').css('width', progress + '%');

        // Add row to table as pending
        const rowId = `plsc-row-${index}`;
        $('#plsc-results-body').append(`
            <tr id="${rowId}">
                <td><code>${link}</code></td>
                <td class="target-cell">Resolving...</td>
                <td><span class="plsc-status-tag pending">PENDING</span></td>
            </tr>
        `);

        // Scroll to the new row
        $('.plsc-table-container').scrollTop($('#plsc-results-body').height());

        $.ajax({
            url: plscData.ajax_url,
            type: 'POST',
            data: {
                action: 'plsc_resolve_link',
                url: link,
                security: plscData.nonce
            },
            success: function (response) {
                if (response.success) {
                    const data = response.data;
                    resolvedResults.push(data);
                    $(`#${rowId} .target-cell`).html(`<code>${data.target}</code>`);
                    $(`#${rowId} .plsc-status-tag`).removeClass('pending').addClass('success').text('RESOLVED');
                } else {
                    $(`#${rowId} .target-cell`).text('Failed to resolve');
                    $(`#${rowId} .plsc-status-tag`).removeClass('pending').addClass('error').text('ERROR');
                }

                // Continue to next
                setTimeout(() => resolveLinksSequentially(index + 1), 100);
            }
        });
    }

    $('#plsc-download-results').on('click', function () {
        if (resolvedResults.length === 0) return;

        let csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Pretty Link (Source),Target URL\n";

        resolvedResults.forEach(function (row) {
            csvContent += `"${row.source}","${row.target}"\n`;
        });

        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", "scraped-pretty-links.csv");
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});
