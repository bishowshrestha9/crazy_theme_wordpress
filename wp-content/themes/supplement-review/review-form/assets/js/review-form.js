/**
 * Review Submission Form Client Script
 *
 * Handles client-side validation, AJAX submission, loading state,
 * and inline status messages. Zero jQuery dependencies.
 */
(function() {
	'use strict';

	function initReviewForms() {
		const forms = document.querySelectorAll('.rf-form');
		if (!forms.length) return;

		forms.forEach(function(form) {
			const wrapper = form.closest('.rf-form-wrapper');
			const alertSuccess = wrapper ? wrapper.querySelector('.rf-alert-success') : null;
			const alertError = wrapper ? wrapper.querySelector('.rf-alert-error') : null;
			const submitBtn = form.querySelector('.rf-submit-btn');
			const submitBtnText = submitBtn ? submitBtn.querySelector('.rf-btn-text') : null;

			function showAlert(type, message) {
				if (alertSuccess) {
					alertSuccess.classList.remove('rf-show');
					alertSuccess.textContent = '';
				}
				if (alertError) {
					alertError.classList.remove('rf-show');
					alertError.textContent = '';
				}

				const target = (type === 'success') ? alertSuccess : alertError;
				if (target) {
					target.textContent = message;
					target.classList.add('rf-show');
					target.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
				}
			}

			function setLoading(isLoading) {
				if (!submitBtn) return;
				submitBtn.disabled = isLoading;
				let spinner = submitBtn.querySelector('.rf-spinner');

				if (isLoading) {
					if (!spinner) {
						spinner = document.createElement('span');
						spinner.className = 'rf-spinner';
						spinner.setAttribute('aria-hidden', 'true');
						submitBtn.prepend(spinner);
					}
					if (submitBtnText) {
						submitBtnText.setAttribute('data-orig-text', submitBtnText.textContent);
						submitBtnText.textContent = 'Submitting...';
					}
				} else {
					if (spinner) spinner.remove();
					if (submitBtnText && submitBtnText.hasAttribute('data-orig-text')) {
						submitBtnText.textContent = submitBtnText.getAttribute('data-orig-text');
					}
				}
			}

			// Validate file input on change
			const fileInput = form.querySelector('.rf-input-file');
			if (fileInput) {
				fileInput.addEventListener('change', function() {
					if (this.files && this.files[0]) {
						const file = this.files[0];
						const allowedExts = ['image/jpeg', 'image/png', 'image/webp'];
						const maxSize = 2 * 1024 * 1024; // 2MB

						if (file.type && !allowedExts.includes(file.type.toLowerCase())) {
							showAlert('error', 'Only image files (JPG, PNG, WEBP) are allowed.');
							this.value = '';
							return;
						}

						if (file.size > maxSize) {
							showAlert('error', 'File size exceeds 2 MB limit. Please upload a smaller image.');
							this.value = '';
							return;
						}
					}
				});
			}

			// Form submission handler
			form.addEventListener('submit', function(e) {
				e.preventDefault();

				// 1. Client-side field validations
				const headline = form.querySelector('[name="rf_headline"]');
				const name = form.querySelector('[name="rf_name"]');
				const email = form.querySelector('[name="rf_email"]');
				const reviewText = form.querySelector('[name="rf_review_text"]');
				const privacy = form.querySelector('[name="rf_privacy"]');

				if (!headline || !headline.value.trim()) {
					showAlert('error', 'Please enter a review headline.');
					if (headline) headline.focus();
					return;
				}

				if (!name || !name.value.trim()) {
					showAlert('error', 'Please enter your name.');
					if (name) name.focus();
					return;
				}

				if (!email || !email.value.trim()) {
					showAlert('error', 'Please enter your email address.');
					if (email) email.focus();
					return;
				}

				const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
				if (!emailRegex.test(email.value.trim())) {
					showAlert('error', 'Please enter a valid email address.');
					email.focus();
					return;
				}

				if (!reviewText || !reviewText.value.trim()) {
					showAlert('error', 'Please share your experience in the review text.');
					if (reviewText) reviewText.focus();
					return;
				}

				if (!privacy || !privacy.checked) {
					showAlert('error', 'You must agree to the Privacy Policy and Review Guidelines.');
					if (privacy) privacy.focus();
					return;
				}

				// 2. Prepare FormData for AJAX
				const formData = new FormData(form);
				formData.append('action', 'rf_submit_review');

				const ajaxUrl = (typeof rfData !== 'undefined' && rfData.ajaxUrl)
					? rfData.ajaxUrl
					: (form.getAttribute('action') || '/wp-admin/admin-ajax.php');

				setLoading(true);

				fetch(ajaxUrl, {
					method: 'POST',
					body: formData,
					credentials: 'same-origin',
					headers: {
						'X-Requested-With': 'XMLHttpRequest'
					}
				})
				.then(function(response) {
					return response.json();
				})
				.then(function(result) {
					setLoading(false);
					if (result && result.success) {
						showAlert('success', result.data && result.data.message
							? result.data.message
							: 'Thank you! Your review has been submitted and is pending verification.');
						form.reset();
					} else {
						const errorMsg = (result && result.data && result.data.message)
							? result.data.message
							: 'An error occurred while submitting your review. Please try again.';
						showAlert('error', errorMsg);
					}
				})
				.catch(function(err) {
					setLoading(false);
					showAlert('error', 'Connection error. Please check your network connection and try again.');
				});
			});
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initReviewForms);
	} else {
		initReviewForms();
	}
})();
