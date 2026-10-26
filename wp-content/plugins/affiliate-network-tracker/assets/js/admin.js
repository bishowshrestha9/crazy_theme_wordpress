/**
 * Affiliate Network Tracker - Admin JavaScript
 */

(function($) {
	'use strict';

	$(document).ready(function() {
		
		// Toggle network fields when checkbox is clicked
		$('.ant-network-header input[type="checkbox"]').on('change', function() {
			const $card = $(this).closest('.ant-network-card');
			const $body = $card.find('.ant-network-body');
			
			if ($(this).is(':checked')) {
				$card.removeClass('disabled');
				$body.find('input').prop('disabled', false);
			} else {
				$card.addClass('disabled');
				$body.find('input').prop('disabled', true);
			}
		});

		// Initialize disabled state on page load
		$('.ant-network-header input[type="checkbox"]').each(function() {
			if (!$(this).is(':checked')) {
				const $card = $(this).closest('.ant-network-card');
				$card.addClass('disabled');
				$card.find('.ant-network-body input').prop('disabled', true);
			}
		});

		// Show success message if updated
		if (window.location.search.indexOf('updated=true') !== -1) {
			const $notice = $('<div class="notice notice-success is-dismissible"><p>Network settings saved successfully!</p></div>');
			$('.ant-dashboard h1').after($notice);
			
			// Auto-dismiss after 3 seconds
			setTimeout(function() {
				$notice.fadeOut(function() {
					$(this).remove();
				});
			}, 3000);
		}

		// Add validation before submit
		$('form[action*="admin-post.php"]').on('submit', function(e) {
			let hasEnabled = false;
			
			$('.ant-network-header input[type="checkbox"]:checked').each(function() {
				hasEnabled = true;
			});
			
			if (!hasEnabled) {
				e.preventDefault();
				alert('Please enable at least one affiliate network.');
				return false;
			}

			// Validate that enabled networks have patterns and params
			let isValid = true;
			$('.ant-network-header input[type="checkbox"]:checked').each(function() {
				const $card = $(this).closest('.ant-network-card');
				const pattern = $card.find('input[name*="[pattern]"]').val().trim();
				const slot1 = $card.find('input[name*="[slot1_param]"]').val().trim();
				const slot2 = $card.find('input[name*="[slot2_param]"]').val().trim();
				
				if (!pattern || !slot1 || !slot2) {
					isValid = false;
					$card.css('border-color', '#d63638');
				}
			});
			
			if (!isValid) {
				e.preventDefault();
				alert('Please fill in all fields (Pattern, Slot 1, Slot 2) for enabled networks.');
				return false;
			}
		});

		// Remove red border on input focus
		$('.ant-network-card input').on('focus', function() {
			$(this).closest('.ant-network-card').css('border-color', '');
		});

	});

})(jQuery);
