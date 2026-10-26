jQuery(document).ready(function($) {
    let scrapedLinks = [];
    let isScraping = false;

    $('#spl-start-scrape').on('click', function() {
        const targetUrl = $('#spl-target-url').val();
        if (!targetUrl) {
            alert('Please enter a valid URL.');
            return;
        }

        resetUI();
        startScrape(targetUrl);
    });

    $('#spl-start-scan-content').on('click', function() {
        resetUI();
        $('#spl-start-scan-content').prop('disabled', true).text('Scanning Database...');
        
        $.ajax({
            url: splData.ajax_url,
            type: 'POST',
            data: {
                action: 'spl_scan_my_content',
                security: splData.nonce
            },
            success: function(response) {
                if (response.success) {
                    const links = response.data;
                    if (links.length === 0) {
                        updateProgress(100, 'No links found in content.');
                        $('#spl-start-scan-content').prop('disabled', false).text('Scan My Content');
                        return;
                    }

                    scrapedLinks = links.map(link => ({
                        source: link,
                        target: 'Pending...',
                        status: 'pending',
                        name: extractNameFromUrl(link)
                    }));

                    renderTable();
                    $('#spl-results-section').show();
                    resolveNextLink(0);
                    $('#spl-start-scan-content').prop('disabled', false).text('Scan My Content');
                } else {
                    alert('Error: ' + response.data);
                    $('#spl-start-scan-content').prop('disabled', false).text('Scan My Content');
                }
            },
            error: function() {
                alert('Connection fatal error.');
                $('#spl-start-scan-content').prop('disabled', false).text('Scan My Content');
            }
        });
    });

    function resetUI() {
        scrapedLinks = [];
        $('#spl-results-body').empty();
        $('#spl-results-section').hide();
        $('#spl-progress-container').show();
        updateProgress(0, 'Starting scrape...');
        $('#spl-start-scrape').prop('disabled', true).text('Working...');
    }

    function startScrape(url) {
        isScraping = true;
        $.ajax({
            url: splData.ajax_url,
            type: 'POST',
            data: {
                action: 'spl_scrape_url',
                security: splData.nonce,
                url: url
            },
            success: function(response) {
                if (response.success) {
                    const links = response.data;
                    if (links.length === 0) {
                        updateProgress(100, 'No links found.');
                        finishScrape();
                        return;
                    }

                    scrapedLinks = links.map(link => ({
                        source: link,
                        target: 'Pending...',
                        status: 'pending',
                        name: extractNameFromUrl(link)
                    }));

                    renderTable();
                    $('#spl-results-section').show();
                    resolveNextLink(0);
                } else {
                    alert('Error: ' + response.data);
                    finishScrape();
                }
            },
            error: function() {
                alert('Connection error.');
                finishScrape();
            }
        });
    }

    function resolveNextLink(index) {
        if (!isScraping || index >= scrapedLinks.length) {
            updateProgress(100, 'Scrape complete!');
            finishScrape();
            return;
        }

        const link = scrapedLinks[index];
        updateLinkRow(index, 'working', 'Resolving...');
        updateProgress((index / scrapedLinks.length) * 100, `Resolving ${index + 1} of ${scrapedLinks.length}...`);

        $.ajax({
            url: splData.ajax_url,
            type: 'POST',
            data: {
                action: 'spl_resolve_link',
                security: splData.nonce,
                url: link.source
            },
            success: function(response) {
                if (response.success) {
                    link.target = response.data.target;
                    link.status = 'success';
                    updateLinkRow(index, 'success', link.target);
                } else {
                    link.target = 'Error';
                    link.status = 'error';
                    updateLinkRow(index, 'error', 'Failed');
                }
                resolveNextLink(index + 1);
            },
            error: function() {
                link.target = 'Network Error';
                link.status = 'error';
                updateLinkRow(index, 'error', 'Failed');
                resolveNextLink(index + 1);
            }
        });
    }

    function renderTable() {
        const body = $('#spl-results-body');
        body.empty();
        scrapedLinks.forEach((link, i) => {
            body.append(`
                <tr id="spl-row-${i}">
                    <td>${link.name}</td>
                    <td class="spl-source-cell">${link.source}</td>
                    <td class="spl-target-cell">${link.target}</td>
                    <td><span class="spl-status-badge spl-status-${link.status}">${link.status}</span></td>
                </tr>
            `);
        });
    }

    function updateLinkRow(index, status, targetText) {
        const row = $(`#spl-row-${index}`);
        row.find('.spl-target-cell').text(targetText);
        row.find('.spl-status-badge')
           .attr('class', `spl-status-badge spl-status-${status}`)
           .text(status);
    }

    function updateProgress(percent, text) {
        $('#spl-progress-fill').css('width', percent + '%');
        $('#spl-status-text').text(text);
    }

    function finishScrape() {
        isScraping = false;
        $('#spl-start-scrape').prop('disabled', false).text('Start Scrape');
    }

    function extractNameFromUrl(url) {
        try {
            const parts = url.split('/');
            const slug = parts[parts.length - 1] || parts[parts.length - 2];
            return slug.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        } catch (e) {
            return 'Untitled Link';
        }
    }

    $('#spl-download-results').on('click', function() {
        if (scrapedLinks.length === 0) return;

        // Official headers from sample: URL,Slug,Name,"Redirection Type","Track Me","No Follow"
        const headers = ['URL', 'Slug', 'Name', 'Redirection Type', 'Track Me', 'No Follow'];
        let csvContent = headers.map(h => escapeCsv(h)).join(',') + '\n';

        scrapedLinks.forEach(link => {
            if (link.status === 'success' && link.target && link.target.startsWith('http')) {
                const url = link.target;
                const name = link.name;
                // Get slug from source URL
                const slugParts = link.source.split('/');
                const slug = slugParts[slugParts.length - 1] || slugParts[slugParts.length - 2];
                
                const row = [
                    escapeCsv(url),
                    escapeCsv(slug),
                    escapeCsv(name),
                    '301', // Redirection Type
                    '1',   // Track Me
                    '0'    // No Follow
                ];
                csvContent += row.join(',') + '\n';
            }
        });

        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const downloadLink = document.createElement("a");
        const url = URL.createObjectURL(blob);
        downloadLink.setAttribute("href", url);
        downloadLink.setAttribute("download", "pretty-links-import-" + new Date().toISOString().slice(0, 10) + ".csv");
        downloadLink.style.visibility = 'hidden';
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    });

    $('#spl-process-csv').on('click', function() {
        const fileInput = document.getElementById('spl-csv-upload');
        const status = $('#spl-csv-status');
        
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Please select a CSV file first.');
            return;
        }

        const file = fileInput.files[0];
        const reader = new FileReader();

        status.text('Parsing file...');

        reader.onload = function(e) {
            const text = e.target.result;
            const rows = text.split(/\r?\n/).filter(line => line.trim() !== '');
            if (rows.length < 2) {
                alert('The CSV file is empty or invalid.');
                return;
            }

            const headerLine = rows[0];
            const headers = headerLine.split(',').map(h => h.trim().replace(/^"|"$/g, ''));
            
            // Map common variants TO the official Mixed Case headers
            const mapper = {
                'url': 'URL',
                'target': 'URL',
                'destination': 'URL',
                'slug': 'Slug',
                'pretty': 'Slug',
                'source': 'Slug',
                'name': 'Name',
                'title': 'Name',
                'redirect_type': 'Redirection Type',
                'type': 'Redirection Type',
                'track_me': 'Track Me',
                'track': 'Track Me',
                'no_follow': 'No Follow'
            };

            const officialHeaders = ['URL', 'Slug', 'Name', 'Redirection Type', 'Track Me', 'No Follow'];
            let officialCsv = officialHeaders.join(',') + '\n';
            
            // For now, let's assume the user uploaded a CSV with SOME compatible columns
            // Simple mapping logic: 
            for (let i = 1; i < rows.length; i++) {
                const values = rows[i].split(',').map(v => v.trim().replace(/^"|"$/g, ''));
                let rowData = {};
                headers.forEach((h, index) => {
                    const mappedKey = mapper[h.toLowerCase()];
                    if (mappedKey) rowData[mappedKey] = values[index];
                });

                // Construct official row with defaults
                const officialRow = [
                    escapeCsv(rowData['URL'] || ''),
                    escapeCsv(rowData['Slug'] || ''),
                    escapeCsv(rowData['Name'] || ''),
                    escapeCsv(rowData['Redirection Type'] || '301'),
                    escapeCsv(rowData['Track Me'] || '1'),
                    escapeCsv(rowData['No Follow'] || '0')
                ];
                officialCsv += officialRow.join(',') + '\n';
            }

            const blob = new Blob([officialCsv], { type: 'text/csv;charset=utf-8;' });
            const dlLink = document.createElement("a");
            const url = URL.createObjectURL(blob);
            dlLink.setAttribute("href", url);
            dlLink.setAttribute("download", "official-" + file.name);
            dlLink.style.visibility = 'hidden';
            document.body.appendChild(dlLink);
            dlLink.click();
            document.body.removeChild(dlLink);
            status.text('Conversion complete! Downloading...');
        };

        reader.readAsText(file);
    });

    $('#spl-csv-upload').on('change', function() {
        if (this.files && this.files[0]) {
            const name = this.files[0].name;
            $('#spl-csv-status').text('Selected: ' + name);
        }
    });

    function escapeCsv(val) {
        if (!val) return '""';
        let str = val.toString();
        if (str.includes(',') || str.includes('"') || str.includes('\n')) {
            str = '"' + str.replace(/"/g, '""') + '"';
        }
        return str;
    }
});
