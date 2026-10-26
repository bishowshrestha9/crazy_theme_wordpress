/**
 * JavaScript for Pretty Links Target Domain Checker Admin UI
 */

jQuery(document).ready(function ($) {
	'use strict';

	let currentItems = [];
	let currentPage = 1;
	const itemsPerPage = 25;

	const $form = $('#prli-tds-form');
	const $domainInput = $('#prli-tds-domain');
	const $allowSubdomains = $('#prli-tds-allow_subdomains');
	const $filterType = $('#prli-tds-filter_type');
	const $submitBtn = $('#prli-tds-submit-btn');
	const $resetBtn = $('#prli-tds-reset-btn');
	const $spinner = $('#prli-tds-spinner');
	const $statsContainer = $('#prli-tds -stats-container');
	const $resultsContainer = $('#prli-tds-results-container');
	const $noticeBox = $('#prli-tds-notice-box');
	const $tableBody = $('#prli-tds-table-body');
	const $searchInput = $('#prli-tds-table-search');
	const $exportBtn = $('#prli-tds-export-csv');

	// Stats elements
	const $statTotal = $('#prli-stat-total');
	const $statMatched = $('#prli-stat-matched');
	const $statMismatched = $('#prli-stat-mismatched');
	const $statInvalid = $('#prli-stat-invalid');
	const $showingCount = $('#prli-showing-count');

	// Pagination elements
	const $currPageSpan = $('#prli-current-page');
	const $totalPagesSpan = $('#prli-total-pages');
	const $prevBtn = $('#prli-pg-prev');
	const $nextBtn = $('#prli-pg-next');

	// Handle AJAX submit
	$form.on('submit', function (e) {
		e.preventDefault();

		const domain = $.trim($domainInput.val());
		if (!domain) {
			showNotice('Please enter an expected target domain.', 'error');
			return;
		}

		$submitBtn.prop('disabled', true);
		$spinner.addClass('is-active');
		clearNotice();

		const payload = {
			action: 'prli_tds_scan_domain',
			security: prliTdsConfig.nonce,
			domain: domain,
			allow_subdomains: $allowSubdomains.is(':checked') ? '1' : '0',
			filter_type: $filterType.val()
		};

		$.ajax({
			url: prliTdsConfig.ajaxUrl,
			type: 'POST',
			data: payload,
			dataType: 'json'
		}).done(function (response) {
			$submitBtn.prop('disabled', false);
			$spinner.removeClass('is-active');

			if (!response.success) {
				showNotice(response.data ? response.data.message : 'Scan failed.', 'error');
				$resultsContainer.hide();
				$statsContainer.hide();
				return;
			}

			const data = response.data;
			currentItems = data.items || [];
			currentPage = 1;

			// Update Stats
			if (data.stats) {
				$statTotal.text(data.stats.total || 0);
				$statMatched.text(data.stats.matched || 0);
				$statMismatched.text(data.stats.mismatched || 0);
				$statInvalid.text(data.stats.invalid || 0);
			}

			$statsContainer.slideDown(200);
			$resultsContainer.slideDown(200);

			renderTable();

			if (currentItems.length === 0) {
				showNotice('No items matched the current filter criteria.', 'info');
			}
		}).fail(function () {
			$submitBtn.prop('disabled', false);
			$spinner.removeClass('is-active');
			showNotice('Server request failed. Please try again.', 'error');
		});
	});

	// Reset Form
	$resetBtn.on('click', function () {
		$domainInput.val('');
		$searchInput.val('');
		$resultsContainer.hide();
		$statsContainer.hide();
		clearNotice();
		currentItems = [];
	});

	// Search filter inside results table
	$searchInput.on('keyup input', function () {
		currentPage = 1;
		renderTable();
	});

	// Pagination Prev / Next
	$prevBtn.on('click', function () {
		if (currentPage > 1) {
			currentPage--;
			renderTable();
		}
	});

	$nextBtn.on('click', function () {
		const totalPages = getTotalPages(getFilteredItems());
		if (currentPage < totalPages) {
			currentPage++;
			renderTable();
		}
	});

	// CSV Export
	$exportBtn.on('click', function () {
		const items = getFilteredItems();
		if (items.length === 0) {
			alert('No items available to export.');
			return;
		}

		let csvContent = 'data:text/csv;charset=utf-8,';
		csvContent += 'ID,Pretty Link Name,Slug,Target URL,Detected Host,Status,Edit Link\n';

		items.forEach(function (item) {
			const row = [
				item.id,
				'"' + (item.name || '').replace(/"/g, '""') + '"',
				'"' + (item.slug || '').replace(/"/g, '""') + '"',
				'"' + (item.target_url || '').replace(/"/g, '""') + '"',
				'"' + (item.detected_host || '').replace(/"/g, '""') + '"',
				'"' + (item.status || '').replace(/"/g, '""') + '"',
				'"' + (item.edit_url || '').replace(/"/g, '""') + '"'
			];
			csvContent += row.join(',') + '\n';
		});

		const encodedUri = encodeURI(csvContent);
		const link = document.createElement('a');
		link.setAttribute('href', encodedUri);
		link.setAttribute('download', 'pretty-links-mismatches-' + new Date().toISOString().slice(0, 10) + '.csv');
		document.body.appendChild(link);
		link.click();
		document.body.removeChild(link);
	});

	function getFilteredItems() {
		const query = $.trim($searchInput.val()).toLowerCase();
		if (!query) {
			return currentItems;
		}

		return currentItems.filter(function (item) {
			return (
				(item.id + '').indexOf(query) !== -1 ||
				(item.name || '').toLowerCase().indexOf(query) !== -1 ||
				(item.slug || '').toLowerCase().indexOf(query) !== -1 ||
				(item.target_url || '').toLowerCase().indexOf(query) !== -1 ||
				(item.detected_host || '').toLowerCase().indexOf(query) !== -1
			);
		});
	}

	function getTotalPages(items) {
		return Math.ceil(items.length / itemsPerPage) || 1;
	}

	function renderTable() {
		const filtered = getFilteredItems();
		$showingCount.text(filtered.length);

		const totalPages = getTotalPages(filtered);
		if (currentPage > totalPages) {
			currentPage = totalPages;
		}

		$currPageSpan.text(currentPage);
		$totalPagesSpan.text(totalPages);

		$prevBtn.prop('disabled', currentPage <= 1);
		$nextBtn.prop('disabled', currentPage >= totalPages);

		$tableBody.empty();

		if (filtered.length === 0) {
			$tableBody.append(
				'<tr><td colspan="6" class="prli-empty-state"><span class="dashicons dashicons-search prli-empty-icon"></span><p>No matching results found.</p></td></tr>'
			);
			return;
		}

		const startIndex = (currentPage - 1) * itemsPerPage;
		const pageItems = filtered.slice(startIndex, startIndex + itemsPerPage);

		pageItems.forEach(function (item) {
			let statusHtml = '';
			if (item.status === 'matched') {
				statusHtml = '<span class="prli-status-badge status-matched"><span class="dashicons dashicons-yes"></span> Matched</span>';
			} else if (item.status === 'mismatched') {
				statusHtml = '<span class="prli-status-badge status-mismatched"><span class="dashicons dashicons-warning"></span> Mismatch</span>';
			} else {
				statusHtml = '<span class="prli-status-badge status-invalid"><span class="dashicons dashicons-dismiss"></span> Invalid URL</span>';
			}

			const targetUrlHtml = item.target_url
				? '<a href="' + escapeHtml(item.target_url) + '" target="_blank" rel="noopener" class="prli-target-link">' + escapeHtml(item.target_url) + '</a>'
				: '<em class="prli-text-muted">(Empty Target URL)</em>';

			const hostHtml = item.detected_host
				? '<code class="prli-host-badge">' + escapeHtml(item.detected_host) + '</code>'
				: '<span class="prli-text-muted">&mdash;</span>';

			const rowHtml =
				'<tr>' +
				'<td><strong>#' + item.id + '</strong></td>' +
				'<td><strong class="prli-item-name">' + escapeHtml(item.name) + '</strong><br /><code class="prli-slug">/' + escapeHtml(item.slug) + '</code> <a href="' + escapeHtml(item.pretty_url) + '" target="_blank" class="dashicons dashicons-external" title="Open Pretty Link"></a></td>' +
				'<td class="prli-target-url-cell">' + targetUrlHtml + '</td>' +
				'<td>' + hostHtml + '</td>' +
				'<td>' + statusHtml + '</td>' +
				'<td style="text-align: right;"><a href="' + escapeHtml(item.edit_url) + '" target="_blank" class="button button-small button-secondary"><span class="dashicons dashicons-edit"></span> Edit</a></td>' +
				'</tr>';

			$tableBody.append(rowHtml);
		});
	}

	function showNotice(msg, type) {
		const classMap = {
			error: 'notice-error',
			warning: 'notice-warning',
			info: 'notice-info',
			success: 'notice-success'
		};
		const noticeClass = classMap[type] || 'notice-info';
		$noticeBox.html('<div class="notice ' + noticeClass + ' prli-tds-notice"><p>' + escapeHtml(msg) + '</p></div>');
	}

	function clearNotice() {
		$noticeBox.empty();
	}

	function escapeHtml(text) {
		if (!text) return '';
		return String(text)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}
});
