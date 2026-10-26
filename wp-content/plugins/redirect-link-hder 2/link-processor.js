// Meta Redirect - Client-side link processor for dynamically revealed content
(function () {
    'use strict';

    const siteUrl = window.location.origin;
    const homeUrl = (typeof amrData !== 'undefined' && amrData.homeUrl) ? amrData.homeUrl : window.location.origin + '/';
    const allowedHosts = (typeof amrData !== 'undefined' && Array.isArray(amrData.allowedHosts)) ? amrData.allowedHosts : [];

    // Pretty link prefixes to detect
    const prettyLinkPrefixes = [
        '/go/', '/recommends/', '/refer/', '/out/', '/link/',
        '/product/', '/visit/', '/offer/', '/get/', '/click/',
        '/review/', '/ext/'
    ];

    function isPrettyLink(url) {
        try {
            const urlObj = new URL(url, window.location.href);
            const path = urlObj.pathname;

            // Check prefixes
            if (prettyLinkPrefixes.some(prefix => path.startsWith(prefix))) {
                return true;
            }

            // Check suffixes
            return /-(dtc|tsl|offer|deal|discount)$/.test(path);
        } catch (e) {
            return false;
        }
    }

    function isExternalLink(url) {
        try {
            const urlObj = new URL(url, window.location.href);
            return urlObj.origin !== window.location.origin &&
                !url.startsWith('mailto:') &&
                !url.startsWith('#') &&
                !url.startsWith('javascript:');
        } catch (e) {
            return false;
        }
    }

    function hostIsAllowed(host) {
        if (!host) return false;
        const needle = String(host).toLowerCase();
        return allowedHosts.some((allowed) => {
            const a = String(allowed || '').toLowerCase();
            if (!a) return false;
            return needle === a || needle.endsWith('.' + a);
        });
    }

    function isAllowedExternalLink(url) {
        if (!isExternalLink(url)) return false;
        try {
            const urlObj = new URL(url, window.location.href);
            return hostIsAllowed(urlObj.hostname);
        } catch (e) {
            return false;
        }
    }

    function processLink(link) {
        const href = link.getAttribute('href');
        if (!href) return;

        // Skip if already processed
        if (href.includes('amr_redirect=1')) return;

        const shouldRedirect = isAllowedExternalLink(href) ||
            (href.startsWith(siteUrl) && isPrettyLink(href));

        if (shouldRedirect) {
            // Get current UTM parameters
            const urlParams = new URLSearchParams(window.location.search);
            const utmParams = {};
            const trackingParams = [
                'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
                'utm_id', 'gclid', 'fbclid', 'msclkid', 'ttclid', 'li_fat_id', 'twclid',
                'ref', 'source', 'campaign'
            ];

            trackingParams.forEach(param => {
                if (urlParams.has(param)) {
                    utmParams[param] = urlParams.get(param);
                }
            });

            // Build redirect URL first
            const redirectUrl = new URL(homeUrl);
            redirectUrl.searchParams.set('amr_redirect', '1');
            redirectUrl.searchParams.set('target', href);

            // Set utm_source to redirect URL if not present
            if (!utmParams.utm_source) {
                utmParams.utm_source = redirectUrl.toString();
            }

            // Add UTM params to target URL
            const targetUrl = new URL(href);
            Object.keys(utmParams).forEach(key => {
                if (!targetUrl.searchParams.has(key)) {
                    targetUrl.searchParams.set(key, utmParams[key]);
                }
            });

            // Rebuild final redirect URL with UTM-enhanced target
            const finalRedirectUrl = new URL(homeUrl);
            finalRedirectUrl.searchParams.set('amr_redirect', '1');
            finalRedirectUrl.searchParams.set('target', targetUrl.toString());

            link.setAttribute('href', finalRedirectUrl.toString());
            // Strip current page's referrer so a.com never leaks at the first hop
            link.setAttribute('rel', 'noreferrer noopener');
        }
    }

    function processAllLinks(container) {
        const links = container.querySelectorAll('a[href]');
        links.forEach(processLink);
    }

    // Process links on page load
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            processAllLinks(document.body);
        });
    } else {
        processAllLinks(document.body);
    }

    // Watch for dynamically added content (like Button Plugin reveals)
    const observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            mutation.addedNodes.forEach(function (node) {
                if (node.nodeType === 1) { // Element node
                    if (node.tagName === 'A') {
                        processLink(node);
                    } else if (node.querySelectorAll) {
                        processAllLinks(node);
                    }
                }
            });

            // Also check for style changes that reveal hidden content
            if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                const target = mutation.target;
                if (target.style.display !== 'none' && target.querySelectorAll) {
                    processAllLinks(target);
                }
            }
        });
    });

    // Start observing
    observer.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['style', 'class']
    });

})();
