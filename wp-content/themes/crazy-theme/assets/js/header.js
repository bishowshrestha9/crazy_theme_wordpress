/**
 * Header interactions: Theme toggle, Expandable search, Mobile navigation
 *
 * @package CrazyTheme
 */
(function() {
	if (window.__crazyHeaderInitialized) return;
	window.__crazyHeaderInitialized = true;

	function initHeaderInteractions() {
		// Clean up any previously stored dark theme preference
		try {
			localStorage.removeItem('theme');
			localStorage.removeItem('crazytheme_theme');
		} catch(e) {}
		document.documentElement.removeAttribute('data-theme');
		if (document.body) {
			document.body.classList.remove('dark-mode');
		}

		// --- SEARCH TOGGLE ---
		var searchWrap = document.getElementById('header-search-wrap');
		var searchBtn = document.getElementById('search-toggle-btn');
		var searchInput = document.getElementById('header-search-input');
		var searchOpenIcon = searchBtn ? searchBtn.querySelector('.search-open-icon') : null;
		var searchCloseIcon = searchBtn ? searchBtn.querySelector('.search-close-icon') : null;

		function setSearchState(open) {
			if (!searchWrap) return;
			if (open) {
				searchWrap.classList.add('is-open');
				if (searchOpenIcon) searchOpenIcon.style.display = 'none';
				if (searchCloseIcon) searchCloseIcon.style.display = 'block';
				if (searchInput) {
					setTimeout(function() { searchInput.focus(); }, 60);
				}
			} else {
				searchWrap.classList.remove('is-open');
				if (searchOpenIcon) searchOpenIcon.style.display = 'block';
				if (searchCloseIcon) searchCloseIcon.style.display = 'none';
			}
		}

		if (searchBtn && searchWrap) {
			searchBtn.addEventListener('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				var isOpen = searchWrap.classList.contains('is-open');
				setSearchState(!isOpen);
			});

			// Prevent clicks inside search form from bubbling and closing it
			var searchForm = document.getElementById('header-search-form');
			if (searchForm) {
				searchForm.addEventListener('click', function(e) {
					e.stopPropagation();
				});
			}

			// Close on click outside
			document.addEventListener('click', function(e) {
				if (searchWrap && !searchWrap.contains(e.target)) {
					setSearchState(false);
				}
			});

			// Close on Escape key
			document.addEventListener('keydown', function(e) {
				if (e.key === 'Escape' || e.keyCode === 27) {
					setSearchState(false);
				}
			});
		}

		// --- MOBILE NAVIGATION TOGGLE ---
		var mobileBtn = document.getElementById('mobile-menu-btn');
		var navMenu = document.getElementById('site-navigation');
		if (mobileBtn && navMenu) {
			mobileBtn.addEventListener('click', function(e) {
				e.preventDefault();
				e.stopPropagation();
				navMenu.classList.toggle('is-active');
			});

			document.addEventListener('click', function(e) {
				if (!navMenu.contains(e.target) && !mobileBtn.contains(e.target)) {
					navMenu.classList.remove('is-active');
				}
			});
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initHeaderInteractions);
	} else {
		initHeaderInteractions();
	}
})();
