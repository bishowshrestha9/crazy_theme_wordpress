(function ($) {
	'use strict';

	$(document).ready(function () {
		var $btnDryRun = $('#btn-apcc-dry-run');
		var $btnConvert = $('#btn-apcc-convert');
		var $progressContainer = $('#apcc-progress-container');
		var $progressBar = $('#apcc-progress-bar');
		var $progressPercent = $('#apcc-progress-percent');
		var $progressStatus = $('#apcc-progress-status');
		var $logList = $('#apcc-log-list');
		var $stats = $('#apcc-stats');

		function getSelectedPostTypes() {
			var types = [];
			$('.apcc-post-type:checked').each(function () {
				types.push($(this).val());
			});
			return types;
		}

		function runConversion(dryRun) {
			var postTypes = getSelectedPostTypes();
			if (postTypes.length === 0) {
				alert('Please select at least one post type to scan.');
				return;
			}

			$btnDryRun.prop('disabled', true);
			$btnConvert.prop('disabled', true);
			$logList.empty();
			$progressContainer.show();
			$progressBar.css('width', '0%');
			$progressPercent.text('0%');
			$progressStatus.text(dryRun ? 'Scanning for matching AffiliateX Pros & Cons blocks...' : 'Converting AffiliateX Pros & Cons blocks...');
			$stats.text('Initializing scan...');

			$.ajax({
				url: apccConverterData.ajax_url,
				type: 'POST',
				data: {
					action: 'apcc_get_candidate_posts',
					nonce: apccConverterData.nonce,
					post_types: postTypes
				},
				success: function (res) {
					if (!res.success || !res.data.post_ids) {
						var err = res.data ? res.data : 'Failed to scan candidate posts.';
						$logList.html('<li style="color: #ef4444; padding: 8px 0;">Error: ' + err + '</li>');
						$btnDryRun.prop('disabled', false);
						$btnConvert.prop('disabled', false);
						return;
					}

					var postIds = res.data.post_ids;
					var totalPosts = postIds.length;

					if (totalPosts === 0) {
						$logList.html('<li style="color: #94a3b8; padding: 12px 0;">No posts found matching legacy AffiliateX Pros & Cons blocks.</li>');
						$progressBar.css('width', '100%');
						$progressPercent.text('100%');
						$progressStatus.text('Scan complete.');
						$stats.text('0 candidate posts found.');
						$btnDryRun.prop('disabled', false);
						$btnConvert.prop('disabled', false);
						return;
					}

					$stats.text('Found ' + totalPosts + ' candidate post(s). Processing batches...');
					processBatches(postIds, totalPosts, 0, 0, 0, dryRun);
				},
				error: function () {
					$logList.html('<li style="color: #ef4444; padding: 8px 0;">AJAX Server Connection Error.</li>');
					$btnDryRun.prop('disabled', false);
					$btnConvert.prop('disabled', false);
				}
			});
		}

		function processBatches(allPostIds, totalPostsCount, processedCount, totalConvertedPosts, totalConvertedBlocks, dryRun) {
			if (allPostIds.length === 0) {
				$progressBar.css('width', '100%');
				$progressPercent.text('100%');
				$progressStatus.text(dryRun ? 'Dry run scan completed.' : 'Conversion batch completed successfully!');
				$stats.text((dryRun ? 'Scan completed: ' : 'Migration completed: ') + totalConvertedPosts + ' post(s) with ' + totalConvertedBlocks + ' Pros & Cons block(s).');
				$btnDryRun.prop('disabled', false);
				$btnConvert.prop('disabled', false);
				return;
			}

			var batch = allPostIds.slice(0, 50);
			var remaining = allPostIds.slice(50);

			$.ajax({
				url: apccConverterData.ajax_url,
				type: 'POST',
				data: {
					action: 'apcc_process_batch',
					nonce: apccConverterData.nonce,
					post_ids: batch,
					dry_run: dryRun ? 1 : 0
				},
				success: function (res) {
					if (res.success && res.data) {
						totalConvertedPosts += res.data.converted_posts || 0;
						totalConvertedBlocks += res.data.converted_blocks || 0;
						if (res.data.log_items) {
							$logList.append(res.data.log_items);
						}
					}

					var newProcessed = processedCount + batch.length;
					var percent = Math.min(100, Math.round((newProcessed / totalPostsCount) * 100));

					$progressBar.css('width', percent + '%');
					$progressPercent.text(percent + '%');
					$progressStatus.text('Processed ' + newProcessed + ' of ' + totalPostsCount + ' posts...');

					processBatches(remaining, totalPostsCount, newProcessed, totalConvertedPosts, totalConvertedBlocks, dryRun);
				},
				error: function () {
					$logList.append('<li style="color: #ef4444; padding: 8px 0;">Error processing batch starting at ID ' + batch[0] + '</li>');
					processBatches(remaining, totalPostsCount, processedCount + batch.length, totalConvertedPosts, totalConvertedBlocks, dryRun);
				}
			});
		}

		$btnDryRun.on('click', function (e) {
			e.preventDefault();
			runConversion(true);
		});

		$btnConvert.on('click', function (e) {
			e.preventDefault();
			if (confirm('Are you sure you want to bulk convert all AffiliateX Pros & Cons blocks into Pros & Cons 4 blocks? This will update post contents in your database.')) {
				runConversion(false);
			}
		});
	});
})(jQuery);
