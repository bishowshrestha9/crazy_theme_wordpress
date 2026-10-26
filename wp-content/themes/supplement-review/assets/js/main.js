/**
 * Main JavaScript for Supplement Review Theme
 * Pure Vanilla ES6+, No jQuery, WCAG 2.1 AA Accessible
 */
(function() {
	'use strict';

	document.addEventListener('DOMContentLoaded', function() {

		/* ---------------------------------------------------------------------
		 * 1. DARK MODE TOGGLE
		 * ------------------------------------------------------------------ */
		var themeToggleBtn = document.getElementById('srThemeToggleBtn');
		var htmlEl = document.documentElement;

		if (themeToggleBtn) {
			themeToggleBtn.addEventListener('click', function() {
				var currentTheme = htmlEl.getAttribute('data-theme') || 'light';
				var nextTheme = (currentTheme === 'dark') ? 'light' : 'dark';

				htmlEl.setAttribute('data-theme', nextTheme);
				try {
					localStorage.setItem('sr_theme_mode', nextTheme);
				} catch (e) {
					/* localStorage disabled */
				}

				themeToggleBtn.setAttribute('aria-pressed', nextTheme === 'dark' ? 'true' : 'false');
			});
		}

		/* ---------------------------------------------------------------------
		 * 2. ACCESSIBLE SEARCH OVERLAY MODAL
		 * ------------------------------------------------------------------ */
		var searchOpenBtn = document.getElementById('srSearchOpenBtn');
		var searchModal   = document.getElementById('srSearchModal');
		var searchCloseBtn = document.getElementById('srSearchCloseBtn');
		var searchInput   = document.getElementById('srSearchInput');
		var searchBackdrop = document.getElementById('srSearchBackdrop');
		var lastActiveElement = null;

		function openSearchModal() {
			if (!searchModal) return;
			lastActiveElement = document.activeElement;
			searchModal.classList.add('is-open');
			searchModal.setAttribute('aria-hidden', 'false');
			if (searchOpenBtn) searchOpenBtn.setAttribute('aria-expanded', 'true');
			document.body.style.overflow = 'hidden';
			setTimeout(function() {
				if (searchInput) searchInput.focus();
			}, 100);
		}

		function closeSearchModal() {
			if (!searchModal) return;
			searchModal.classList.remove('is-open');
			searchModal.setAttribute('aria-hidden', 'true');
			if (searchOpenBtn) searchOpenBtn.setAttribute('aria-expanded', 'false');
			document.body.style.overflow = '';
			if (lastActiveElement && typeof lastActiveElement.focus === 'function') {
				lastActiveElement.focus();
			}
		}

		if (searchOpenBtn) {
			searchOpenBtn.addEventListener('click', openSearchModal);
		}
		if (searchCloseBtn) {
			searchCloseBtn.addEventListener('click', closeSearchModal);
		}
		if (searchBackdrop) {
			searchBackdrop.addEventListener('click', closeSearchModal);
		}

		/* ---------------------------------------------------------------------
		 * 3. MOBILE OFF-CANVAS NAVIGATION DRAWER
		 * ------------------------------------------------------------------ */
		var menuToggleBtn = document.getElementById('srMenuToggleBtn');
		var mobileDrawer  = document.getElementById('srMobileDrawer');
		var drawerCloseBtn = document.getElementById('srDrawerCloseBtn');
		var drawerOverlay = document.getElementById('srDrawerOverlay');

		function openMobileDrawer() {
			if (!mobileDrawer) return;
			lastActiveElement = document.activeElement;
			mobileDrawer.classList.add('is-open');
			if (drawerOverlay) drawerOverlay.classList.add('is-open');
			mobileDrawer.setAttribute('aria-hidden', 'false');
			if (menuToggleBtn) menuToggleBtn.setAttribute('aria-expanded', 'true');
			document.body.style.overflow = 'hidden';
			if (drawerCloseBtn) drawerCloseBtn.focus();
		}

		function closeMobileDrawer() {
			if (!mobileDrawer) return;
			mobileDrawer.classList.remove('is-open');
			if (drawerOverlay) drawerOverlay.classList.remove('is-open');
			mobileDrawer.setAttribute('aria-hidden', 'true');
			if (menuToggleBtn) menuToggleBtn.setAttribute('aria-expanded', 'false');
			document.body.style.overflow = '';
			if (lastActiveElement && typeof lastActiveElement.focus === 'function') {
				lastActiveElement.focus();
			}
		}

		if (menuToggleBtn) {
			menuToggleBtn.addEventListener('click', openMobileDrawer);
		}
		if (drawerCloseBtn) {
			drawerCloseBtn.addEventListener('click', closeMobileDrawer);
		}
		if (drawerOverlay) {
			drawerOverlay.addEventListener('click', closeMobileDrawer);
		}

		// Escape key closes modals and drawers
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' || e.keyCode === 27) {
				if (searchModal && searchModal.classList.contains('is-open')) {
					closeSearchModal();
				}
				if (mobileDrawer && mobileDrawer.classList.contains('is-open')) {
					closeMobileDrawer();
				}
			}
		});

		/* ---------------------------------------------------------------------
		 * 4. BACK TO TOP BUTTON
		 * ------------------------------------------------------------------ */
		var backToTopBtn = document.getElementById('srBackToTopBtn');
		if (backToTopBtn) {
			var isScrolling = false;

			window.addEventListener('scroll', function() {
				if (!isScrolling) {
					window.requestAnimationFrame(function() {
						if (window.scrollY > 400) {
							backToTopBtn.classList.add('is-visible');
						} else {
							backToTopBtn.classList.remove('is-visible');
						}
						isScrolling = false;
					});
					isScrolling = true;
				}
			}, { passive: true });

			backToTopBtn.addEventListener('click', function(e) {
				e.preventDefault();
				window.scrollTo({
					top: 0,
					behavior: 'smooth'
				});
			});
		}

	});
})();
