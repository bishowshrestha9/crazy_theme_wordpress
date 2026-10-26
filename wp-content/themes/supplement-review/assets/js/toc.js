/**
 * Table of Contents (TOC) Interactive Logic & Compact Sticky Bar
 * Vanilla JavaScript (ES6+), No jQuery, No Frameworks
 * Features:
 *  - Smooth grid expand/collapse animation (300ms)
 *  - Chevron 180deg rotation & Hide/Show text toggle
 *  - LocalStorage persistence with try/catch
 *  - Accessible focus management & inert/aria-hidden on collapse
 *  - IntersectionObserver on TOC card (rootMargin: -111px 0px 0px 0px) to show/hide compact sticky bar
 *  - Column-width floating sticky bar with 12px gap below header
 *  - Active section scroll-spy: updates TOC card, sticky title, counter, progress line, and dropdown
 *  - Dropdown menu with 300ms grid-rows animation, "All sections" / "Close" text toggle, and 180deg chevron
 *  - Smooth scrolling with header + sticky bar clearance offset
 */

(function() {
	'use strict';

	var STORAGE_KEY = 'sr_toc_collapsed';

	function getHeaderHeight() {
		var header = document.querySelector('.site-header');
		if (header) {
			return header.offsetHeight;
		}
		return (window.innerWidth <= 767) ? 96 : 111;
	}

	function getScrollOffset() {
		var headerH = getHeaderHeight();
		var adminBarH = 0;
		if (document.body.classList.contains('admin-bar')) {
			adminBarH = (window.innerWidth <= 782) ? 46 : 32;
		}
		// On desktop/tablet (> 767px) sticky bar is active (47px height + 12px gap + 16px buffer)
		var stickyBarOffset = (window.innerWidth > 767) ? (47 + 12 + 16) : 16;
		return headerH + adminBarH + stickyBarOffset;
	}

	document.addEventListener('DOMContentLoaded', function() {
		var tocCard = document.querySelector('.toc-card') || document.getElementById('tocCard') || document.getElementById('srTocCard');
		var toggleBtn = document.querySelector('.toc-toggle-btn') || document.getElementById('tocToggleBtn');
		var tocBody = document.querySelector('.toc-body') || document.getElementById('tocBody');

		var stickyBar         = document.getElementById('tocStickyBar') || document.getElementById('srStickyContentsBar') || document.querySelector('.toc-bar');
		var stickyTitle       = document.getElementById('tocBarTitle') || document.getElementById('srStickyTitle') || (stickyBar ? stickyBar.querySelector('.toc-bar-title') : null);
		var stickyCounter     = document.getElementById('tocBarCounter') || document.getElementById('srStickyCounter') || (stickyBar ? stickyBar.querySelector('.toc-bar-count') : null);
		var stickyProgress    = document.getElementById('tocBarProgressFill') || document.getElementById('srReadingProgress') || (stickyBar ? stickyBar.querySelector('.toc-bar-progress-fill') : null);
		var dropdownBtn       = document.getElementById('tocBarDropdownBtn') || document.getElementById('srStickyDropdownBtn') || (stickyBar ? stickyBar.querySelector('.toc-bar-main-btn') : null);
		var dropdownMenu      = document.getElementById('tocBarDropdownMenu') || (stickyBar ? stickyBar.querySelector('.toc-bar-dropdown') : null);
		var dropdownToggleTxt = stickyBar ? stickyBar.querySelector('.toc-bar-toggle-text') : null;
		var dropdownItems     = stickyBar ? stickyBar.querySelectorAll('.toc-bar-dropdown-item') : [];

		var isScrollingLocked = false;
		var scrollLockTimeout = null;

		/* ---------------------------------------------------------------------
		 * 1. Expand / Collapse State Management for Main TOC Card
		 * ------------------------------------------------------------------ */
		if (tocCard && toggleBtn && tocBody) {
			var toggleLabel = toggleBtn.querySelector('.toc-toggle-text');

			var setCollapsed = function(collapsed, animate) {
				if (collapsed) {
					tocCard.classList.add('is-collapsed');
					toggleBtn.setAttribute('aria-expanded', 'false');
					if (toggleLabel) {
						toggleLabel.textContent = 'Show';
					}

					if (!animate) {
						tocBody.setAttribute('inert', '');
						tocBody.setAttribute('aria-hidden', 'true');
					} else {
						setTimeout(function() {
							if (tocCard.classList.contains('is-collapsed')) {
								tocBody.setAttribute('inert', '');
								tocBody.setAttribute('aria-hidden', 'true');
							}
						}, 300);
					}
				} else {
					tocCard.classList.remove('is-collapsed');
					toggleBtn.setAttribute('aria-expanded', 'true');
					tocBody.removeAttribute('inert');
					tocBody.setAttribute('aria-hidden', 'false');
					if (toggleLabel) {
						toggleLabel.textContent = 'Hide';
					}
				}

				try {
					localStorage.setItem(STORAGE_KEY, collapsed ? 'true' : 'false');
				} catch (e) {}
			};

			// Restore saved state (default is expanded)
			try {
				var saved = localStorage.getItem(STORAGE_KEY);
				if (saved === 'true') {
					setCollapsed(true, false);
				} else {
					setCollapsed(false, false);
				}
			} catch (e) {
				setCollapsed(false, false);
			}

			toggleBtn.addEventListener('click', function(e) {
				e.preventDefault();
				var isCurrentlyCollapsed = tocCard.classList.contains('is-collapsed');
				setCollapsed(!isCurrentlyCollapsed, true);
			});
		}

		/* ---------------------------------------------------------------------
		 * 2. Active Section & Smooth Scrolling
		 * ------------------------------------------------------------------ */
		var tocItems = tocCard ? tocCard.querySelectorAll('.toc-item') : [];
		var headings = [];

		if (tocItems.length > 0) {
			tocItems.forEach(function(item) {
				var sectionId = item.getAttribute('data-section');
				if (sectionId) {
					var target = document.getElementById(sectionId);
					if (target) {
						headings.push({
							id: sectionId,
							el: target,
							item: item,
							title: item.querySelector('.toc-label') ? item.querySelector('.toc-label').textContent.trim() : target.textContent.trim()
						});
					}
				}
			});
		}

		// Fallback if TOC items did not populate headings: query h2[id] inside content
		if (headings.length === 0) {
			var articleHeadings = document.querySelectorAll('.sr-review-content h2[id], .entry-content h2[id], article h2[id]');
			articleHeadings.forEach(function(h2) {
				var id = h2.getAttribute('id');
				headings.push({
					id: id,
					el: h2,
					item: tocCard ? tocCard.querySelector('.toc-item[data-section="' + id + '"]') : null,
					title: h2.textContent.trim()
				});
			});
		}

		var totalHeadings = headings.length;

		function scrollToTarget(targetEl, href) {
			if (!targetEl) return;

			isScrollingLocked = true;
			if (scrollLockTimeout) clearTimeout(scrollLockTimeout);

			var offset = getScrollOffset();
			var rect = targetEl.getBoundingClientRect();
			var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
			var targetY = scrollTop + rect.top - offset;

			window.scrollTo({
				top: Math.max(0, targetY),
				behavior: 'smooth'
			});

			if (window.history && window.history.pushState) {
				window.history.pushState(null, '', href);
			} else {
				window.location.hash = href;
			}

			scrollLockTimeout = setTimeout(function() {
				isScrollingLocked = false;
			}, 800);
		}

		// Click listener for main TOC card items
		tocItems.forEach(function(item) {
			item.addEventListener('click', function(e) {
				var href = item.getAttribute('href');
				if (!href || href.charAt(0) !== '#') return;

				var targetId = href.substring(1);
				var targetEl = document.getElementById(targetId);
				if (targetEl) {
					e.preventDefault();
					setActiveSection(targetId);
					scrollToTarget(targetEl, href);
				}
			});
		});

		// Click listener for Sticky Bar Dropdown items
		if (dropdownItems.length > 0) {
			dropdownItems.forEach(function(item) {
				item.addEventListener('click', function(e) {
					var href = item.getAttribute('href');
					if (!href || href.charAt(0) !== '#') return;

					var targetId = href.substring(1);
					var targetEl = document.getElementById(targetId);
					if (targetEl) {
						e.preventDefault();
						closeDropdown();
						setActiveSection(targetId);
						scrollToTarget(targetEl, href);
					}
				});
			});
		}

		/* ---------------------------------------------------------------------
		 * 3. Active Section State Sync
		 * ------------------------------------------------------------------ */
		function setActiveSection(activeId) {
			var activeIndex = -1;
			var activeTitle = '';

			for (var i = 0; i < headings.length; i++) {
				if (headings[i].id === activeId) {
					activeIndex = i;
					activeTitle = headings[i].title;
					break;
				}
			}

			if (activeIndex === -1 && headings.length > 0) {
				activeIndex = 0;
				activeTitle = headings[0].title;
			}

			// Update Main TOC Card items
			tocItems.forEach(function(item) {
				if (item.getAttribute('data-section') === activeId) {
					item.classList.add('is-active');
					item.setAttribute('aria-current', 'true');
				} else {
					item.classList.remove('is-active');
					item.removeAttribute('aria-current');
				}
			});

			// Update Sticky Bar Elements
			if (stickyBar && totalHeadings > 0) {
				var currentNum = activeIndex + 1;
				var numStr = (currentNum < 10 ? '0' : '') + currentNum;
				var totalStr = (totalHeadings < 10 ? '0' : '') + totalHeadings;

				if (stickyTitle && activeTitle) {
					stickyTitle.textContent = activeTitle;
				}

				if (stickyCounter) {
					stickyCounter.textContent = numStr + ' / ' + totalStr;
				}

				if (stickyProgress) {
					var pct = Math.max(1, Math.min(100, (currentNum / totalHeadings) * 100));
					stickyProgress.style.width = pct + '%';
				}

				// Update dropdown active highlight
				dropdownItems.forEach(function(item) {
					if (item.getAttribute('data-section') === activeId) {
						item.classList.add('is-active');
						item.setAttribute('aria-current', 'true');
					} else {
						item.classList.remove('is-active');
						item.removeAttribute('aria-current');
					}
				});
			}
		}

		/* ---------------------------------------------------------------------
		 * 4. IntersectionObserver Scroll-Spy for Heading Tracking
		 * ------------------------------------------------------------------ */
		if ('IntersectionObserver' in window && headings.length > 0) {
			var spyObserver = new IntersectionObserver(function(entries) {
				if (isScrollingLocked) return;

				entries.forEach(function(entry) {
					if (entry.isIntersecting) {
						var activeId = entry.target.getAttribute('id');
						if (activeId) {
							setActiveSection(activeId);
						}
					}
				});
			}, {
				rootMargin: '-125px 0px -70% 0px',
				threshold: 0
			});

			headings.forEach(function(h) {
				spyObserver.observe(h.el);
			});
		}

		/* ---------------------------------------------------------------------
		 * 5. Compact Sticky Contents Bar: Trigger & Dropdown Menu
		 * ------------------------------------------------------------------ */
		function openDropdown() {
			if (!stickyBar) return;
			stickyBar.classList.add('dropdown-open');
			if (dropdownBtn) {
				dropdownBtn.setAttribute('aria-expanded', 'true');
			}
			if (dropdownMenu) {
				dropdownMenu.setAttribute('aria-hidden', 'false');
			}
			if (dropdownToggleTxt) {
				dropdownToggleTxt.textContent = 'Close';
			}
		}

		function closeDropdown() {
			if (!stickyBar) return;
			stickyBar.classList.remove('dropdown-open');
			if (dropdownBtn) {
				dropdownBtn.setAttribute('aria-expanded', 'false');
			}
			if (dropdownMenu) {
				dropdownMenu.setAttribute('aria-hidden', 'true');
			}
			if (dropdownToggleTxt) {
				dropdownToggleTxt.textContent = 'All sections';
			}
		}

		function toggleDropdown() {
			if (stickyBar && stickyBar.classList.contains('dropdown-open')) {
				closeDropdown();
			} else {
				openDropdown();
			}
		}

		if (dropdownBtn) {
			dropdownBtn.addEventListener('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				toggleDropdown();
			});
		}

		// Close dropdown on outside click
		document.addEventListener('click', function(e) {
			if (stickyBar && !stickyBar.contains(e.target)) {
				closeDropdown();
			}
		});

		// Close dropdown on Escape key
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' && stickyBar && stickyBar.classList.contains('dropdown-open')) {
				closeDropdown();
				if (dropdownBtn) dropdownBtn.focus();
			}
		});

		// Sticky bar visibility controls
		function showStickyBar() {
			if (!stickyBar) return;
			if (window.innerWidth <= 767) {
				hideStickyBar();
				return;
			}
			stickyBar.classList.add('is-visible');
			stickyBar.setAttribute('aria-hidden', 'false');
			if (dropdownBtn) {
				dropdownBtn.removeAttribute('tabindex');
			}
		}

		function hideStickyBar() {
			if (!stickyBar) return;
			stickyBar.classList.remove('is-visible');
			stickyBar.setAttribute('aria-hidden', 'true');
			closeDropdown();
			if (dropdownBtn) {
				dropdownBtn.setAttribute('tabindex', '-1');
			}
		}

		function updateStickyBarState() {
			if (!tocCard || !stickyBar) return;

			// Mobile <= 767px: never show compact bar
			if (window.innerWidth <= 767) {
				hideStickyBar();
				return;
			}

			var headerBottom = getHeaderHeight();
			if (document.body.classList.contains('admin-bar')) {
				headerBottom += (window.innerWidth <= 782 ? 46 : 32);
			}

			var rect = tocCard.getBoundingClientRect();
			// Shown when main TOC card's bottom edge goes above the header's bottom edge
			if (rect.bottom <= headerBottom) {
				showStickyBar();
			} else {
				hideStickyBar();
			}
		}

		if (tocCard && stickyBar) {
			// Initially hidden by default
			hideStickyBar();

			if ('IntersectionObserver' in window) {
				var tocObserver = new IntersectionObserver(function(entries) {
					entries.forEach(function(entry) {
						updateStickyBarState();
					});
				}, {
					rootMargin: '-111px 0px 0px 0px',
					threshold: 0
				});

				tocObserver.observe(tocCard);
			}

			// Scroll listener for immediate sync across scroll speeds & refreshes
			var ticking = false;
			window.addEventListener('scroll', function() {
				if (!ticking) {
					window.requestAnimationFrame(function() {
						updateStickyBarState();
						ticking = false;
					});
					ticking = true;
				}
			}, { passive: true });

			window.addEventListener('resize', function() {
				updateStickyBarState();
			}, { passive: true });

			// Initial evaluation on load
			updateStickyBarState();
		}
	});
})();
