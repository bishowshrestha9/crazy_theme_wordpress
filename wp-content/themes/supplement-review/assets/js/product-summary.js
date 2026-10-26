/**
 * Product Summary Component Script
 * Handles Web Share API and Clipboard Copy fallback for the share button.
 * No dependencies, no inline scripts, safe & accessible.
 */
(function() {
	'use strict';

	function initProductSummaryShare() {
		const shareButtons = document.querySelectorAll('.ps-share-btn');
		if (!shareButtons.length) return;

		shareButtons.forEach(btn => {
			btn.addEventListener('click', async function(e) {
				e.preventDefault();

				const card = btn.closest('.ps-card');
				const titleElem = card ? card.querySelector('.ps-title') : null;
				const shareTitle = titleElem ? titleElem.textContent.trim() : document.title;
				const shareUrl = window.location.href;

				// 1. Try Native Web Share API if supported
				if (navigator.share && navigator.canShare && navigator.canShare({ url: shareUrl })) {
					try {
						await navigator.share({
							title: shareTitle,
							text: shareTitle + ' - Comprehensive Editorial Supplement Review',
							url: shareUrl
						});
						return;
					} catch (err) {
						// User cancelled or share failed, fallback to clipboard if not AbortError
						if (err.name === 'AbortError') return;
					}
				}

				// 2. Fallback: Copy to Clipboard
				try {
					if (navigator.clipboard && navigator.clipboard.writeText) {
						await navigator.clipboard.writeText(shareUrl);
						showToast(btn, 'Link copied!');
					} else {
						// ExecCommand fallback for older browsers
						const tempInput = document.createElement('input');
						tempInput.value = shareUrl;
						document.body.appendChild(tempInput);
						tempInput.select();
						document.execCommand('copy');
						document.body.removeChild(tempInput);
						showToast(btn, 'Link copied!');
					}
				} catch (clipErr) {
					showToast(btn, 'Copy link manually');
				}
			});
		});
	}

	function showToast(btn, message) {
		let toast = btn.parentElement.querySelector('.ps-share-toast');
		if (!toast) {
			toast = document.createElement('span');
			toast.className = 'ps-share-toast';
			toast.setAttribute('role', 'status');
			toast.setAttribute('aria-live', 'polite');
			btn.parentElement.appendChild(toast);
		}

		toast.textContent = message;
		toast.classList.add('ps-toast--show');

		clearTimeout(btn._toastTimeout);
		btn._toastTimeout = setTimeout(() => {
			toast.classList.remove('ps-toast--show');
		}, 2200);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initProductSummaryShare);
	} else {
		initProductSummaryShare();
	}
})();
