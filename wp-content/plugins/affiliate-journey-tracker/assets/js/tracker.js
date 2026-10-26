/**
 * Affiliate Journey Tracker - Frontend Vanilla JS
 *
 * Attribution State Model:
 * 1. NORMAL: Current independently selected product review receives attribution.
 * 2. LISTICLE-PRESERVED: User discovered product through a Listicle and clicked "Read More",
 *    preserving the Listicle as attribution source across intermediate review page & refreshes.
 * 3. RESET: Selecting another independently recommended product (sidebar, footer, after-post)
 *    resets attribution to the newly selected product review.
 *
 * Zero jQuery dependency.
 *
 * @package AffiliateJourneyTracker
 */

(function () {
	'use strict';

	if (typeof window.affiliateJtData === 'undefined') {
		return;
	}

	const config = window.affiliateJtData;
	const STATE_STORAGE_KEY = 'affiliate_attribution_state';

	/**
	 * Read stored attribution state object.
	 *
	 * @return {object|null} State object or null.
	 */
	function getStoredState() {
		try {
			const raw = sessionStorage.getItem(STATE_STORAGE_KEY);
			if (raw) {
				const parsed = JSON.parse(raw);
				if (parsed && parsed.attributionSlug) {
					return parsed;
				}
			}
		} catch (e) {}

		// Fallback to server-provided cookie state if available
		if (config.attributionState && typeof config.attributionState === 'object' && config.attributionState.attribution_slug) {
			return {
				mode: config.attributionState.mode || 'NORMAL',
				attributionSlug: config.attributionState.attribution_slug,
				listicleSlug: config.attributionState.listicle_slug || null,
				intendedReviewSlug: config.attributionState.intended_review_slug || null,
				timestamp: config.attributionState.timestamp || Date.now()
			};
		}

		return null;
	}

	/**
	 * Save attribution state to sessionStorage and 1st-party cookies.
	 *
	 * @param {object} state Attribution state object.
	 */
	function saveState(state) {
		if (!state || !state.attributionSlug) {
			return;
		}

		try {
			sessionStorage.setItem(STATE_STORAGE_KEY, JSON.stringify(state));
			sessionStorage.setItem('ref_page', state.attributionSlug);
		} catch (e) {}

		setOriginCookie(state.attributionSlug, state);
	}

	/**
	 * Set 1st-party cookie with expiry hours.
	 *
	 * @param {string} slug  Origin slug.
	 * @param {object} state State object.
	 */
	function setOriginCookie(slug, state) {
		if (!slug || slug === 'home' || config.isHomeOrFront) {
			return;
		}
		const hours = config.cookieExpiryHours || 24;
		const date = new Date();
		date.setTime(date.getTime() + (hours * 60 * 60 * 1000));
		const expires = '; expires=' + date.toUTCString();
		document.cookie = 'ref_page=' + encodeURIComponent(slug) + expires + '; path=/; SameSite=Lax';

		if (state) {
			const stateCookieData = {
				mode: state.mode,
				attribution_slug: state.attributionSlug,
				listicle_slug: state.listicleSlug,
				intended_review_slug: state.intendedReviewSlug,
				timestamp: state.timestamp
			};
			document.cookie = 'affiliate_jt_state=' + encodeURIComponent(JSON.stringify(stateCookieData)) + expires + '; path=/; SameSite=Lax';
		}
	}

	/**
	 * Helper: Clean and extract slug from a value or URL. Returns empty string if home/invalid.
	 *
	 * @param {string} val Value or URL.
	 * @return {string} Sanitized slug or empty string.
	 */
	function sanitizeSlug(val) {
		if (!val || val === 'home') {
			return '';
		}
		if (val.indexOf('http://') === 0 || val.indexOf('https://') === 0) {
			try {
				const parsed = new URL(val, window.location.origin);
				const parts = parsed.pathname.replace(/^\/|\/$/g, '').split('/').filter(Boolean);
				const slug = parts.pop() || '';
				if (!slug || slug === 'home') {
					return '';
				}
				return slug.replace(/[^a-zA-Z0-9_-]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '').toLowerCase();
			} catch (e) {
				return '';
			}
		}
		const clean = val.replace(/[^a-zA-Z0-9_-]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '').toLowerCase();
		return clean === 'home' ? '' : clean;
	}

	/**
	 * Read current page slug reliably from config or window.location.pathname.
	 *
	 * @return {string} Current page slug or empty string.
	 */
	function getCurrentPageSlug() {
		if (config.currentSlug && config.currentSlug !== 'home') {
			return sanitizeSlug(config.currentSlug);
		}
		const parts = window.location.pathname.replace(/^\/|\/$/g, '').split('/').filter(Boolean);
		const last = parts.pop() || '';
		return sanitizeSlug(last);
	}

	/**
	 * Clear all attribution state and cookies.
	 */
	function clearAttributionState() {
		try {
			sessionStorage.removeItem(STATE_STORAGE_KEY);
			sessionStorage.removeItem('ref_page');
			localStorage.removeItem('ref_page');
			localStorage.removeItem('affiliate_origin');
			document.cookie = 'ref_page=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; SameSite=Lax';
			document.cookie = 'affiliate_jt_state=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; SameSite=Lax';
			document.cookie = 'affiliate_origin=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; SameSite=Lax';
		} catch (e) {}
	}

	/**
	 * Initialize page attribution state machine on load.
	 */
	function initAttributionState() {
		const currentSlug = getCurrentPageSlug();

		// 1. Homepage: Excluded from attribution
		if (config.isHomeOrFront || !currentSlug || window.location.pathname === '/') {
			clearAttributionState();
			return;
		}

		const existingState = getStoredState();

		// 2. If Current Page is a Listicle (WordPress Category "Listicle")
		if (config.isListicle) {
			const listicleState = {
				mode: 'NORMAL',
				attributionSlug: currentSlug,
				listicleSlug: currentSlug,
				intendedReviewSlug: null,
				timestamp: Date.now()
			};
			saveState(listicleState);
			return;
		}

		// 3. If Current Page is a Product Review (Not a Listicle)
		if (existingState && existingState.mode === 'LISTICLE-PRESERVED') {
			// Check if this page is the intended review page from the Listicle's Read More click
			if (existingState.intendedReviewSlug && existingState.intendedReviewSlug === currentSlug) {
				// PRESERVE LISTICLE ATTRIBUTION (Survives page load and refreshes!)
				const preservedState = {
					mode: 'LISTICLE-PRESERVED',
					attributionSlug: sanitizeSlug(existingState.listicleSlug || existingState.attributionSlug),
					listicleSlug: sanitizeSlug(existingState.listicleSlug),
					intendedReviewSlug: currentSlug,
					timestamp: existingState.timestamp || Date.now()
				};
				saveState(preservedState);
				return;
			}
		}

		// 4. Direct visit to Product Review OR Independent recommendation navigation
		const normalReviewState = {
			mode: 'NORMAL',
			attributionSlug: currentSlug,
			listicleSlug: null,
			intendedReviewSlug: null,
			timestamp: Date.now()
		};
		saveState(normalReviewState);
	}

	/**
	 * Determine origin slug for current visitor context.
	 *
	 * @return {string} Resolved origin slug.
	 */
	function getCalculatedOrigin() {
		if (config.isHomeOrFront || window.location.pathname === '/') {
			return '';
		}

		const state = getStoredState();
		if (state && state.attributionSlug) {
			const clean = sanitizeSlug(state.attributionSlug);
			if (clean) return clean;
		}

		if (config.calculatedOrigin) {
			const clean = sanitizeSlug(config.calculatedOrigin);
			if (clean) return clean;
		}

		return getCurrentPageSlug();
	}

	/**
	 * Extract post slug from an internal URL.
	 *
	 * @param {string} url Internal link URL.
	 * @return {string|null} Post slug or null.
	 */
	function extractSlugFromUrl(url) {
		if (!url) {
			return null;
		}
		return sanitizeSlug(url) || null;
	}

	/**
	 * Check if an anchor points to an internal page on the same domain (not home, not anchor hash).
	 *
	 * @param {HTMLAnchorElement} anchor Anchor element.
	 * @return {boolean} True if internal content link.
	 */
	function isInternalContentLink(anchor) {
		if (!anchor || !anchor.href) {
			return false;
		}

		const href = anchor.getAttribute('href');
		if (!href || href.startsWith('#') || href.startsWith('javascript:') || href.startsWith('mailto:') || href.startsWith('tel:')) {
			return false;
		}

		// Check if it's a /go/ redirect link (not content link)
		if (anchor.pathname && anchor.pathname.indexOf('/go/') !== -1) {
			return false;
		}

		try {
			const parsed = new URL(anchor.href, window.location.origin);
			const isSameHost = (parsed.hostname === window.location.hostname);
			const path = parsed.pathname.replace(/^\/|\/$/g, '');

			// Exclude homepage
			if (!path) {
				return false;
			}

			return isSameHost;
		} catch (e) {
			return false;
		}
	}

	/**
	 * Check if an anchor element points to a target Site B / Bridge domain or Pretty Link.
	 *
	 * @param {HTMLAnchorElement} anchor Anchor element.
	 * @return {boolean} True if link qualifies as Buy / Bridge link.
	 */
	function isBridgeOrBuyLink(anchor) {
		if (!anchor || !anchor.href) {
			return false;
		}

		const href = anchor.href.toLowerCase();
		if (href.indexOf('javascript:') === 0 || href.indexOf('#') === 0 || href.indexOf('mailto:') === 0 || href.indexOf('tel:') === 0) {
			return false;
		}

		// 1. Check if path has /go/ (Pretty Link redirect format)
		if (anchor.pathname && anchor.pathname.toLowerCase().indexOf('/go/') !== -1) {
			return true;
		}

		// 2. Check configured Site B / Bridge domains
		const rawDomains = config.siteBDomain || 'bestproductpicks.com, getbestsupplements.com, siteb.com, links.theunbiasedreviews.org';
		const domains = rawDomains.split(/[\s,]+/);
		for (let i = 0; i < domains.length; i++) {
			const d = domains[i].trim().toLowerCase();
			if (d && href.indexOf(d) !== -1) {
				return true;
			}
		}

		// 3. Check external hostnames (excluding generic search/social)
		if (anchor.hostname && anchor.hostname !== window.location.hostname) {
			const excluded = ['amazon.', 'ebay.', 'wikipedia.', 'google.', 'facebook.', 'twitter.', 'youtube.', 'pinterest.', 'instagram.'];
			for (let i = 0; i < excluded.length; i++) {
				if (href.indexOf(excluded[i]) !== -1) {
					return false;
				}
			}
			return true;
		}

		return false;
	}

	/**
	 * Append ONLY ref=<slug> parameter to target URL, strictly stripping all subid/origin/ref_page variations.
	 *
	 * @param {string} url    Original URL.
	 * @param {string} origin Origin slug.
	 * @return {string} Transformed URL.
	 */
	function appendOriginToUrl(url, origin) {
		if (!url || url.indexOf('javascript:') === 0 || url.indexOf('#') === 0 || url.indexOf('mailto:') === 0 || url.indexOf('tel:') === 0) {
			return url;
		}

		origin = sanitizeSlug(origin);

		try {
			const parsed = new URL(url, window.location.origin);

			// Delete ALL unwanted tracking keys regardless of casing or numbering
			const allKeys = Array.from(parsed.searchParams.keys());
			allKeys.forEach(function (k) {
				const lower = k.toLowerCase();
				if (lower === 'ref' || lower === 'ref_page' || lower === 'origin' || lower === 'sub_id' || lower.indexOf('subid') === 0 || lower.indexOf('ref_') === 0 || lower.indexOf('affiliate_') === 0) {
					parsed.searchParams.delete(k);
				}
			});

			if (config.isHomeOrFront || !origin || origin === 'home' || window.location.pathname === '/') {
				if (url.startsWith('/')) {
					return parsed.pathname + (parsed.search ? parsed.search : '') + parsed.hash;
				}
				return parsed.href;
			}

			// Attach ONLY ref=<slug>
			parsed.searchParams.set('ref', origin);

			if (url.startsWith('/')) {
				return parsed.pathname + parsed.search + parsed.hash;
			}
			return parsed.href;
		} catch (e) {
			return url;
		}
	}

	/**
	 * Process and update all Buy button links in the DOM.
	 */
	function updateBuyButtonLinks() {
		const origin = getCalculatedOrigin();
		if (!origin) {
			return;
		}

		const anchors = document.querySelectorAll('a');
		anchors.forEach(function (anchor) {
			if (isBridgeOrBuyLink(anchor)) {
				const currentHref = anchor.getAttribute('href');
				if (currentHref) {
					const updatedHref = appendOriginToUrl(currentHref, origin);
					if (updatedHref !== currentHref) {
						anchor.setAttribute('href', updatedHref);
					}
				}
			}
		});
	}

	/**
	 * Log click event to Site A admin logger asynchronously.
	 *
	 * @param {string} origin    Origin slug.
	 * @param {string} targetUrl Destination URL.
	 * @param {string} eventType Event type description.
	 */
	function logClickEvent(origin, targetUrl, eventType) {
		if (!config.ajaxUrl || !config.enableLogs) {
			return;
		}

		const data = new FormData();
		data.append('action', 'affiliate_jt_log_click');
		data.append('nonce', config.nonce || '');
		data.append('origin_slug', origin || '');
		data.append('source_url', window.location.href);
		data.append('target_url', targetUrl || '');
		data.append('event_type', eventType || 'Button Click');

		if (navigator.sendBeacon) {
			try {
				navigator.sendBeacon(config.ajaxUrl, data);
				return;
			} catch (e) {}
		}

		try {
			fetch(config.ajaxUrl, {
				method: 'POST',
				body: data,
				keepalive: true
			}).catch(function () {});
		} catch (e) {}
	}

	/**
	 * Attach global click interceptor for Buy buttons, Listicle Read More links, and Recommendation resets.
	 */
	function attachClickInterceptor() {
		document.addEventListener('click', function (event) {
			const anchor = event.target.closest('a');
			if (!anchor || !anchor.href) {
				return;
			}

			// Case 1: Click on a Buy Button / Bridge Link pointing to Site B
			if (isBridgeOrBuyLink(anchor)) {
				const origin = getCalculatedOrigin();
				const currentHref = anchor.getAttribute('href');
				if (currentHref && origin) {
					const updatedHref = appendOriginToUrl(currentHref, origin);
					anchor.setAttribute('href', updatedHref);
				}

				// Log click event
				logClickEvent(origin, anchor.href, 'Button Click');
				return;
			}

			// Case 2: Click on an Internal Link from a Listicle (Read More)
			if (config.isListicle && isInternalContentLink(anchor)) {
				const targetSlug = extractSlugFromUrl(anchor.href);
				if (targetSlug && targetSlug !== config.currentSlug) {
					// Set LISTICLE-PRESERVED state targeting this specific product review
					const preservedState = {
						mode: 'LISTICLE-PRESERVED',
						attributionSlug: config.currentSlug, // Listicle is the attribution!
						listicleSlug: config.currentSlug,
						intendedReviewSlug: targetSlug,
						timestamp: Date.now()
					};
					saveState(preservedState);
				}
				return;
			}

			// Case 3: Click on an Internal Link from a Normal Product Review (Sidebar/Footer/Recommendation)
			if (!config.isListicle && isInternalContentLink(anchor)) {
				const targetSlug = extractSlugFromUrl(anchor.href);
				if (targetSlug && targetSlug !== config.currentSlug) {
					// Reset state to NORMAL for the newly selected recommended product
					const resetState = {
						mode: 'NORMAL',
						attributionSlug: targetSlug,
						listicleSlug: null,
						intendedReviewSlug: null,
						timestamp: Date.now()
					};
					saveState(resetState);
				}
				return;
			}

		}, true); // Capture phase
	}

	// Execute on DOMContentLoaded
	document.addEventListener('DOMContentLoaded', function () {
		initAttributionState();
		updateBuyButtonLinks();
		attachClickInterceptor();
	});

})();


