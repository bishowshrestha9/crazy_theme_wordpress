/**
 * Dynamic Affiliate SubID Replacer - Frontend Script
 * 
 * Intercepts clicks on outbound affiliate links and dynamically replaces
 * tracking parameter values with site name and current page slug.
 */

(function() {
	'use strict';

	// Wait for DOM to be ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}

	/**
	 * Initialize the click handler
	 */
	function init() {
		// Verify config is available
		if (typeof dasrConfig === 'undefined') {
			console.warn('DASR: Configuration not loaded');
			return;
		}

		// Use event delegation on document level for dynamic content
		document.addEventListener('click', handleLinkClick, true);
	}

	/**
	 * Handle click events on links
	 * 
	 * @param {Event} event - The click event
	 */
	function handleLinkClick(event) {
		// Find the closest anchor element
		const link = event.target.closest('a');
		
		if (!link || !link.href) {
			return;
		}

		// Check if we should process this link
		if (!shouldRewriteLink(link)) {
			return;
		}

		try {
			const originalUrl = link.href;
			let rewrittenUrl = null;

			// Check if this is a meta redirect link (contains ?target= parameter)
			const linkUrl = new URL(originalUrl);
			const targetParam = linkUrl.searchParams.get('target');
			
			if (targetParam) {
				// This is a meta redirect link - rewrite the target parameter
				const decodedTarget = decodeURIComponent(targetParam);
				const rewrittenTarget = rewriteUrl(decodedTarget);
				
				if (rewrittenTarget && rewrittenTarget !== decodedTarget) {
					linkUrl.searchParams.set('target', encodeURIComponent(rewrittenTarget));
					rewrittenUrl = linkUrl.toString();
				}
			} else {
				// Regular external link - rewrite directly
				rewrittenUrl = rewriteUrl(originalUrl);
			}

			// Only intervene if URL was actually changed
			if (rewrittenUrl && rewrittenUrl !== originalUrl) {
				event.preventDefault();
				
				// Handle target attribute
				const target = link.getAttribute('target');
				if (target === '_blank' || target === '_new') {
					window.open(rewrittenUrl, target);
				} else {
					window.location.href = rewrittenUrl;
				}
			}
		} catch (error) {
			console.error('DASR: Error rewriting URL', error);
			// Let the link work normally if there's an error
		}
	}

	/**
	 * Determine if a link should be rewritten
	 * 
	 * @param {HTMLAnchorElement} link - The link element
	 * @returns {boolean}
	 */
	function shouldRewriteLink(link) {
		try {
			const url = new URL(link.href, window.location.href);
			
			// Check if this is a meta redirect link (internal link with ?target= parameter)
			const targetParam = url.searchParams.get('target');
			if (targetParam) {
				// This is a meta redirect link - check if target URL has trackable params
				try {
					const targetUrl = new URL(decodeURIComponent(targetParam));
					return hasTrackableParams(targetUrl);
				} catch (e) {
					return false;
				}
			}
			
			// Skip internal links (same host) unless they have target parameter
			if (url.hostname === dasrConfig.currentHost || url.hostname === window.location.hostname) {
				return false;
			}

			// Skip non-http(s) protocols
			if (url.protocol !== 'http:' && url.protocol !== 'https:') {
				return false;
			}

			// Check if we should only target specific class
			if (dasrConfig.targetOnlyClass) {
				if (!link.classList.contains(dasrConfig.targetClass)) {
					return false;
				}
			}

			// Check if we should only target sponsored/nofollow links
			if (dasrConfig.targetRel) {
				const rel = link.getAttribute('rel') || '';
				if (!rel.includes('sponsored') && !rel.includes('nofollow')) {
					return false;
				}
			}

			// Check if URL contains any trackable parameters
			if (!hasTrackableParams(url)) {
				return false;
			}

			return true;

		} catch (error) {
			// Invalid URL, skip it
			return false;
		}
	}

	/**
	 * Check if URL contains any trackable parameters
	 * 
	 * @param {URL} url - The URL object
	 * @returns {boolean}
	 */
	function hasTrackableParams(url) {
		const allKeys = [...dasrConfig.slot1Keys, ...dasrConfig.slot2Keys];
		
		for (const key of allKeys) {
			if (url.searchParams.has(key)) {
				return true;
			}
		}
		
		return false;
	}

	/**
	 * Rewrite URL with new SubID values
	 * 
	 * @param {string} originalUrl - The original URL string
	 * @returns {string|null} - The rewritten URL or null if no changes
	 */
	function rewriteUrl(originalUrl) {
		try {
			const url = new URL(originalUrl);
			let modified = false;
			let hasSlot1 = false;
			let hasSlot2 = false;

			// Replace Slot 1 parameters (site name / source)
			for (const key of dasrConfig.slot1Keys) {
				if (url.searchParams.has(key)) {
					url.searchParams.set(key, dasrConfig.siteName);
					modified = true;
					hasSlot1 = true;
				}
			}

			// Replace Slot 2 parameters (page slug / content)
			for (const key of dasrConfig.slot2Keys) {
				if (url.searchParams.has(key)) {
					url.searchParams.set(key, dasrConfig.pageSlug);
					modified = true;
					hasSlot2 = true;
				}
			}

			// If URL has Slot 1 parameter but no Slot 2 parameter, add it
			if (hasSlot1 && !hasSlot2 && dasrConfig.slot2Keys.length > 0) {
				// Use the first Slot 2 key (most common: subid2, sub2, etc.)
				const slot2Key = dasrConfig.slot2Keys[0];
				url.searchParams.set(slot2Key, dasrConfig.pageSlug);
				modified = true;
			}

			return modified ? url.toString() : null;

		} catch (error) {
			console.error('DASR: Error parsing URL', error);
			return null;
		}
	}

	/**
	 * Public API for programmatic rewriting (optional)
	 * Exposed for developers who want to manually rewrite URLs
	 */
	window.DASR = {
		rewriteUrl: rewriteUrl,
		version: '1.0.0'
	};

})();
