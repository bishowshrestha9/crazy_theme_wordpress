/**
 * Affiliate Network Tracker - Frontend JavaScript
 * 
 * Automatically detects affiliate network by URL pattern and replaces
 * tracking parameters with site name and page slug.
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
	 * Initialize the tracker
	 */
	function init() {
		// Verify config is available
		if (typeof antConfig === 'undefined') {
			console.warn('ANT: Configuration not loaded');
			return;
		}

		// Use event delegation on document level
		document.addEventListener('click', handleLinkClick, true);
	}

	/**
	 * Handle click events on links
	 */
	function handleLinkClick(event) {
		const link = event.target.closest('a');
		
		if (!link || !link.href) {
			return;
		}

		try {
			const originalUrl = link.href;
			let rewrittenUrl = null;

			// Check if this is a meta redirect link
			const linkUrl = new URL(originalUrl);
			const targetParam = linkUrl.searchParams.get('target');
			
			if (targetParam) {
				// Meta redirect link - rewrite the target parameter
				const decodedTarget = decodeURIComponent(targetParam);
				const rewrittenTarget = rewriteUrl(decodedTarget);
				
				if (rewrittenTarget && rewrittenTarget !== decodedTarget) {
					linkUrl.searchParams.set('target', encodeURIComponent(rewrittenTarget));
					rewrittenUrl = linkUrl.toString();
				}
			} else {
				// Regular external link
				rewrittenUrl = rewriteUrl(originalUrl);
			}

			// Only intervene if URL was changed
			if (rewrittenUrl && rewrittenUrl !== originalUrl) {
				event.preventDefault();
				
				// Log the click
				logClick(rewrittenUrl, originalUrl);
				
				const target = link.getAttribute('target');
				if (target === '_blank' || target === '_new') {
					window.open(rewrittenUrl, target);
				} else {
					window.location.href = rewrittenUrl;
				}
			}
		} catch (error) {
			console.error('ANT: Error rewriting URL', error);
		}
	}

	/**
	 * Rewrite URL with network-specific parameters
	 */
	function rewriteUrl(originalUrl) {
		try {
			// Clean up URL - fix common malformed URL issues
			let cleanUrl = originalUrl
				.replace(/%20&/g, '&')       // Remove encoded space before &
				.replace(/%20\?/g, '?')      // Remove encoded space before ?
				.replace(/ &/g, '&')         // Remove literal space before &
				.replace(/ \?/g, '?')        // Remove literal space before ?
				.replace(/\?&/g, '?')        // Fix ?& to just ?
				.replace(/\?\?/g, '?')       // Fix ?? to single ?
				.replace(/&&/g, '&')         // Fix double &&
				.replace(/&\?/g, '&')        // Fix &? to just &
				.replace(/\?([^?&]*)\?/g, '?$1&'); // Fix ? in middle of params to &
			
			const url = new URL(cleanUrl);
			
			// Skip internal links
			if (url.hostname === antConfig.currentHost || url.hostname === window.location.hostname) {
				return null;
			}

			// Detect which network this URL belongs to
			const network = detectNetwork(url);
			
			if (!network) {
				return null;
			}

			let modified = false;
			let hasSlot1 = false;
			let hasSlot2 = false;

			// Check and replace Slot 1 parameter (site name)
			if (network.slot1_param) {
				if (url.searchParams.has(network.slot1_param)) {
					// Parameter exists - replace it
					url.searchParams.set(network.slot1_param, antConfig.slot1Value);
					modified = true;
					hasSlot1 = true;
				}
			}

			// Check and replace Slot 2 parameter (page slug)
			if (network.slot2_param) {
				if (url.searchParams.has(network.slot2_param)) {
					// Parameter exists - replace it
					url.searchParams.set(network.slot2_param, antConfig.slot2Value);
					modified = true;
					hasSlot2 = true;
				}
			}

			// If URL has Slot 1 but missing Slot 2, add it
			if (hasSlot1 && !hasSlot2 && network.slot2_param) {
				url.searchParams.set(network.slot2_param, antConfig.slot2Value);
				modified = true;
				console.log('ANT: Added missing Slot 2 parameter:', network.slot2_param);
			}
			
			// If URL has neither Slot 1 nor Slot 2 (but matched network), add both
			if (!hasSlot1 && !hasSlot2) {
				if (network.slot1_param) {
					url.searchParams.set(network.slot1_param, antConfig.slot1Value);
					modified = true;
					console.log('ANT: Added Slot 1 parameter:', network.slot1_param);
				}
				if (network.slot2_param) {
					url.searchParams.set(network.slot2_param, antConfig.slot2Value);
					modified = true;
					console.log('ANT: Added Slot 2 parameter:', network.slot2_param);
				}
			}

			if (modified) {
				console.log('ANT: URL transformed:', originalUrl, '→', url.toString());
			}

			return modified ? url.toString() : null;

		} catch (error) {
			console.error('ANT: Error parsing URL:', originalUrl, error);
			return null;
		}
	}

	/**
	 * Detect which affiliate network a URL belongs to
	 */
	function detectNetwork(url) {
		const hostname = url.hostname;
		const searchParams = url.searchParams;
		
		for (const network of antConfig.networks) {
			// Match by URL parameters (recommended for networks with changing domains)
			if (network.match_by === 'params') {
				const patterns = network.pattern.split(',');
				let matchCount = 0;
				
				for (const param of patterns) {
					if (searchParams.has(param.trim())) {
						matchCount++;
					}
				}
				
				// If URL has at least one of the specified parameters, it's a match
				if (matchCount > 0) {
					return network;
				}
			} 
			// Match by domain name
			else if (network.match_by === 'domain') {
				if (hostname.includes(network.pattern)) {
					return network;
				}
			}
		}
		
		return null;
	}

	/**
	 * Log click to database via AJAX
	 */
	function logClick(affiliateUrl, originalUrl) {
		try {
			const url = new URL(affiliateUrl);
			const network = detectNetwork(url);
			
			const data = {
				action: 'ant_log_click',
				affiliate_url: affiliateUrl,
				network_name: network ? getNetworkName(network.pattern) : 'Unknown',
				subid: url.searchParams.get(network?.slot1_param || 'subid') || '',
				subid2: url.searchParams.get(network?.slot2_param || 'subid2') || '',
				source_page: window.location.href
			};

			// Send beacon (doesn't block navigation)
			if (navigator.sendBeacon) {
				const formData = new FormData();
				for (const key in data) {
					formData.append(key, data[key]);
				}
				navigator.sendBeacon(antConfig.ajaxUrl || '/wp-admin/admin-ajax.php', formData);
			} else {
				// Fallback to async XHR
				fetch(antConfig.ajaxUrl || '/wp-admin/admin-ajax.php', {
					method: 'POST',
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
					body: new URLSearchParams(data),
					keepalive: true
				}).catch(e => console.error('ANT: Failed to log click', e));
			}
		} catch (error) {
			console.error('ANT: Error logging click', error);
		}
	}

	/**
	 * Get friendly network name from pattern
	 */
	function getNetworkName(pattern) {
		const networkMap = {
			'aff_id,subid': 'BuyGoods',
			'sub1,sub2': 'SmashLoud',
			'uid': 'SmartAdv',
			'cid': 'ClickHunts',
			'aid': 'MediaScalers',
			't1,t2': 'SellHealth',
			'clickbank.net': 'ClickBank',
			'terraleads.com': 'TerraLeads',
			'maxweb.com': 'MaxWeb',
			'clickadv.com': 'ClickAdv',
			'traffic-light.biz': 'Traffic Light',
			'leadbit.com': 'LeadBit'
		};
		return networkMap[pattern] || 'Unknown';
	}

	/**
	 * Public API for programmatic rewriting
	 */
	window.ANT = {
		rewriteUrl: rewriteUrl,
		detectNetwork: function(urlString) {
			try {
				return detectNetwork(new URL(urlString));
			} catch (e) {
				return null;
			}
		},
		version: '1.0.0'
	};

})();
