/**
 * Single Review Interactive Features
 * Table of Contents, Sticky Progress Bar, Web Share API, Star Rating
 * Pure Vanilla ES6+, No jQuery, IntersectionObserver-based
 */
(function() {
	'use strict';

	document.addEventListener('DOMContentLoaded', function() {

		/* ---------------------------------------------------------------------
		 * 1. TABLE OF CONTENTS TOGGLE (HIDE / SHOW)
		 * ------------------------------------------------------------------ */
		var tocCard      = document.getElementById('srTocCard') || document.getElementById('tocCard') || document.querySelector('.toc-card');
		var tocToggleBtn = document.getElementById('srTocToggleBtn');
		var tocGrid      = document.getElementById('srTocGrid');

		if (tocCard && tocToggleBtn && tocGrid) {
			tocToggleBtn.addEventListener('click', function() {
				var isCollapsed = tocCard.classList.contains('is-collapsed');
				var textSpan = tocToggleBtn.querySelector('.sr-toc-toggle-text');

				if (isCollapsed) {
					tocCard.classList.remove('is-collapsed');
					tocToggleBtn.setAttribute('aria-expanded', 'true');
					if (textSpan) textSpan.textContent = 'Hide';
				} else {
					tocCard.classList.add('is-collapsed');
					tocToggleBtn.setAttribute('aria-expanded', 'false');
					if (textSpan) textSpan.textContent = 'Show';
				}
			});
		}

		/* ---------------------------------------------------------------------
		 * 2. STICKY CONTENTS PROGRESS BAR & INTERSECTION OBSERVER
		 * ------------------------------------------------------------------ */
		var stickyBar      = document.getElementById('srStickyContentsBar');
		var stickyTitle    = document.getElementById('srStickyTitle');
		var stickyCounter  = document.getElementById('srStickyCounter');
		var progressFill   = document.getElementById('srReadingProgress');
		var dropdownBtn    = document.getElementById('srStickyDropdownBtn');
		var contentArticle = document.querySelector('.sr-review-content');

		if (stickyBar && tocCard) {
			// Show sticky bar once TOC card is scrolled past
			var tocObserver = new IntersectionObserver(function(entries) {
				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						stickyBar.classList.remove('is-visible');
						stickyBar.setAttribute('aria-hidden', 'true');
					} else {
						// Only show if user has scrolled past TOC, not above it
						var rect = tocCard.getBoundingClientRect();
						if (rect.top < 0) {
							stickyBar.classList.add('is-visible');
							stickyBar.setAttribute('aria-hidden', 'false');
						} else {
							stickyBar.classList.remove('is-visible');
							stickyBar.setAttribute('aria-hidden', 'true');
						}
					}
				});
			}, {
				threshold: 0
			});

			tocObserver.observe(tocCard);

			// Track active H2 and H3 sections
			var headings = contentArticle ? contentArticle.querySelectorAll('h2[id], h3[id]') : [];
			var tocItems = document.querySelectorAll('.sr-toc-item');
			var totalHeadings = headings.length;

			if (headings.length > 0) {
				var headingObserver = new IntersectionObserver(function(entries) {
					entries.forEach(function(entry) {
						if (entry.isIntersecting) {
							var id = entry.target.getAttribute('id');
							var title = entry.target.textContent.trim();

							// Find corresponding index
							var activeIndex = 1;
							for (var i = 0; i < headings.length; i++) {
								if (headings[i].getAttribute('id') === id) {
									activeIndex = i + 1;
									break;
								}
							}

							var numStr = (activeIndex < 10 ? '0' : '') + activeIndex;
							var totalStr = (totalHeadings < 10 ? '0' : '') + totalHeadings;

							if (stickyTitle) stickyTitle.textContent = title;
							if (stickyCounter) stickyCounter.textContent = numStr + ' / ' + totalStr;

							// Update TOC active highlight
							tocItems.forEach(function(item) {
								if (item.getAttribute('data-section') === id) {
									item.classList.add('is-active');
								} else {
									item.classList.remove('is-active');
								}
							});
						}
					});
				}, {
					rootMargin: '-80px 0px -70% 0px',
					threshold: 0
				});

				headings.forEach(function(h2) {
					headingObserver.observe(h2);
				});
			}

			// Reading Progress Bar calculation
			if (contentArticle && progressFill) {
				var ticking = false;
				window.addEventListener('scroll', function() {
					if (!ticking) {
						window.requestAnimationFrame(function() {
							var rect = contentArticle.getBoundingClientRect();
							var totalHeight = rect.height;
							var scrolled = -rect.top + 100;
							var percent = Math.max(0, Math.min(100, (scrolled / totalHeight) * 100));

							progressFill.style.width = percent + '%';
							ticking = false;
						});
						ticking = true;
					}
				}, { passive: true });
			}

			// Dropdown Menu Toggle
			if (dropdownBtn) {
				dropdownBtn.addEventListener('click', function(e) {
					e.stopPropagation();
					var isOpen = stickyBar.classList.contains('dropdown-open');
					if (isOpen) {
						stickyBar.classList.remove('dropdown-open');
						dropdownBtn.setAttribute('aria-expanded', 'false');
					} else {
						stickyBar.classList.add('dropdown-open');
						dropdownBtn.setAttribute('aria-expanded', 'true');
					}
				});

				// Close dropdown when clicking an item or outside
				document.addEventListener('click', function(e) {
					if (!stickyBar.contains(e.target)) {
						stickyBar.classList.remove('dropdown-open');
						dropdownBtn.setAttribute('aria-expanded', 'false');
					}
				});

				var menuLinks = stickyBar.querySelectorAll('.sr-sticky-dropdown-menu a');
				menuLinks.forEach(function(link) {
					link.addEventListener('click', function() {
						stickyBar.classList.remove('dropdown-open');
						dropdownBtn.setAttribute('aria-expanded', 'false');
					});
				});
			}
		}

		/* ---------------------------------------------------------------------
		 * 3. NATIVE WEB SHARE API WITH CLIPBOARD FALLBACK
		 * ------------------------------------------------------------------ */
		var shareBtn = document.getElementById('srShareBtn');
		if (shareBtn) {
			shareBtn.addEventListener('click', function(e) {
				e.preventDefault();
				var pageTitle = document.title;
				var pageUrl   = window.location.href;

				if (navigator.share) {
					navigator.share({
						title: pageTitle,
						url: pageUrl
					}).catch(function() {
						/* Share cancelled or not supported */
					});
				} else if (navigator.clipboard && navigator.clipboard.writeText) {
					navigator.clipboard.writeText(pageUrl).then(function() {
						var originalTitle = shareBtn.getAttribute('title') || 'Share review';
						shareBtn.setAttribute('title', 'Link copied to clipboard!');
						alert('Review URL copied to clipboard!');
						setTimeout(function() {
							shareBtn.setAttribute('title', originalTitle);
						}, 3000);
					}).catch(function() {
						prompt('Copy this link:', pageUrl);
					});
				} else {
					prompt('Copy this link:', pageUrl);
				}
			});
		}

		/* ---------------------------------------------------------------------
		 * 4. INTERACTIVE STAR RATING SELECTOR FOR COMMENT FORM
		 * ------------------------------------------------------------------ */
		var starButtons = document.querySelectorAll('.sr-star-btn');
		var ratingInput = document.getElementById('srReviewRatingInput');

		if (starButtons.length > 0 && ratingInput) {
			var currentRating = parseInt(ratingInput.value, 10) || 5;

			function updateStarVisuals(val) {
				starButtons.forEach(function(btn) {
					var starVal = parseInt(btn.getAttribute('data-value'), 10);
					if (starVal <= val) {
						btn.classList.add('is-active');
					} else {
						btn.classList.remove('is-active');
					}
				});
			}

			// Initialize
			updateStarVisuals(currentRating);

			starButtons.forEach(function(btn) {
				btn.addEventListener('mouseenter', function() {
					var hoverVal = parseInt(this.getAttribute('data-value'), 10);
					starButtons.forEach(function(b) {
						var bVal = parseInt(b.getAttribute('data-value'), 10);
						if (bVal <= hoverVal) {
							b.classList.add('is-hover');
						} else {
							b.classList.remove('is-hover');
						}
					});
				});

				btn.addEventListener('click', function(e) {
					e.preventDefault();
					currentRating = parseInt(this.getAttribute('data-value'), 10);
					ratingInput.value = currentRating;
					updateStarVisuals(currentRating);
				});
			});

			var starContainer = document.querySelector('.sr-star-rating-buttons');
			if (starContainer) {
				starContainer.addEventListener('mouseleave', function() {
					starButtons.forEach(function(b) {
						b.classList.remove('is-hover');
					});
					updateStarVisuals(currentRating);
				});
			}
		}

		/* ---------------------------------------------------------------------
		 * 4b. REVIEW SUBMISSION FORM VALIDATION & UI FEEDBACK
		 * ------------------------------------------------------------------ */
		var commentForm = document.getElementById('commentform');
		if (commentForm) {
			commentForm.addEventListener('submit', function(e) {
				var headline = document.getElementById('sr_review_headline');
				var author = document.getElementById('author');
				var email = document.getElementById('email');
				var comment = document.getElementById('comment');
				var fileInput = document.getElementById('sr_review_image');
				var privacy = document.getElementById('sr_privacy_consent');
				var submitBtn = commentForm.querySelector('.rf-submit-btn');

				if (headline && !headline.value.trim()) {
					e.preventDefault();
					alert('Please enter a review headline.');
					headline.focus();
					return;
				}

				if (author && !author.value.trim()) {
					e.preventDefault();
					alert('Please enter your name.');
					author.focus();
					return;
				}

				if (email && !email.value.trim()) {
					e.preventDefault();
					alert('Please enter your email.');
					email.focus();
					return;
				}

				if (comment && !comment.value.trim()) {
					e.preventDefault();
					alert('Please write your review experience.');
					comment.focus();
					return;
				}

				if (fileInput && fileInput.files && fileInput.files[0]) {
					var file = fileInput.files[0];
					if (file.size > 2097152) {
						e.preventDefault();
						alert('The attached file exceeds 2 MB. Please select a smaller image.');
						return;
					}
					var validTypes = ['image/jpeg', 'image/png', 'image/webp'];
					if (validTypes.indexOf(file.type) === -1) {
						e.preventDefault();
						alert('Please upload an image file (JPG, PNG, or WEBP).');
						return;
					}
				}

				if (privacy && !privacy.checked) {
					e.preventDefault();
					alert('Please agree to the Privacy Policy to submit your review.');
					privacy.focus();
					return;
				}

				if (submitBtn) {
					submitBtn.disabled = true;
					var btnText = submitBtn.querySelector('.rf-btn-text');
					if (btnText) {
						btnText.textContent = 'Submitting...';
					}
				}
			});
		}

		/* ---------------------------------------------------------------------
		 * 5. MOBILE STICKY BOTTOM BUY BAR
		 * ------------------------------------------------------------------ */
		var productCard   = document.getElementById('srProductCard');
		var mobileBuyBar  = document.getElementById('srMobileStickyCta');

		if (productCard && mobileBuyBar && window.innerWidth <= 768) {
			var cardObserver = new IntersectionObserver(function(entries) {
				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						mobileBuyBar.style.display = 'none';
					} else {
						var rect = productCard.getBoundingClientRect();
						if (rect.top < 0) {
							mobileBuyBar.style.display = 'flex';
						} else {
							mobileBuyBar.style.display = 'none';
						}
					}
				});
			}, {
				threshold: 0
			});

			cardObserver.observe(productCard);
		}

	});
})();
