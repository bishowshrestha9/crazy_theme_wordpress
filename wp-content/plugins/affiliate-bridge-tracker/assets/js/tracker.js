/**
 * Affiliate Referrer Tracker - Client-Side Tracking Script
 *
 * Implements:
 * 1. Storage & Isolation using sessionStorage.
 * 2. Referrer Tracking Logic (Direct Article Landing vs. Homepage Funnel).
 * 3. Clearing Logic (homepage, external referrer, and external outbound link clicks).
 * 4. Dynamic Link Modification (static rewrite on load + event delegation on click).
 */

(function() {
    'use strict';

    // Verify localized data from WordPress is available
    if (typeof artData === 'undefined') {
        return;
    }

    var domain1     = artData.domain1 || '';
    var domain2     = artData.domain2 || '';
    var isHomepage  = !!artData.isHomepage;
    var currentSlug = artData.slug || '';

    /**
     * Helper to check if a hostname matches a target domain (exact or subdomain)
     *
     * @param {string} hostname - The hostname to check (e.g. www.sitea.com)
     * @param {string} targetDomain - The configured domain (e.g. sitea.com)
     * @return {boolean} True if matched
     */
    function isMatch(hostname, targetDomain) {
        if (!hostname || !targetDomain) {
            return false;
        }
        hostname     = hostname.toLowerCase().trim();
        targetDomain = targetDomain.toLowerCase().trim();

        // Exact match
        if (hostname === targetDomain) {
            return true;
        }

        // Subdomain match (e.g., www.targetDomain ends with .targetDomain)
        if (hostname.endsWith('.' + targetDomain)) {
            return true;
        }

        return false;
    }

    /**
     * Check if a host matches Domain 1 (either the configured one or the current window location)
     *
     * @param {string} hostname - The hostname to check
     * @return {boolean} True if matches Domain 1
     */
    function isDomain1(hostname) {
        return isMatch(hostname, domain1) || isMatch(hostname, window.location.hostname);
    }

    /**
     * Clear referrer-related state if visitor came from a domain that is neither Domain 1 nor Domain 2.
     */
    function checkReferrerClearing() {
        var referrer = document.referrer;
        if (referrer) {
            try {
                var refUrl = new URL(referrer);
                var refHost = refUrl.hostname;

                if (!isDomain1(refHost) && !isMatch(refHost, domain2)) {
                    // Referrer domain is external: clear tracking state
                    sessionStorage.removeItem('ref_page');
                    sessionStorage.removeItem('came_from_homepage');
                }
            } catch (e) {
                // Ignore parsing errors for malformed referrers
            }
        }
    }

    /**
     * Run referrer tracking logic based on page type (Homepage vs Article/Post page).
     */
    function processTrackingLogic() {
        if (isHomepage) {
            // Homepage: clear ref_page and set came_from_homepage flag
            sessionStorage.removeItem('ref_page');
            sessionStorage.setItem('came_from_homepage', 'true');
        } else if (currentSlug) {
            // Article/Post Page: resolve funnel state
            var cameFromHomepage = sessionStorage.getItem('came_from_homepage') === 'true';

            if (cameFromHomepage) {
                // Homepage Funnel: Lock onto the first post visited after the homepage
                var savedSlug = sessionStorage.getItem('ref_page');
                if (!savedSlug) {
                    sessionStorage.setItem('ref_page', currentSlug);
                }
            } else {
                // Direct Article Landing: Save slug, update on subsequent direct article navigations
                sessionStorage.setItem('ref_page', currentSlug);
            }
        }
    }

    /**
     * Statically scan and append ref_page parameter to all elements matching Domain 2.
     */
    function modifyStaticLinks() {
        var savedSlug = sessionStorage.getItem('ref_page');
        if (!savedSlug) {
            return;
        }

        var anchors = document.querySelectorAll('a[href]');
        for (var i = 0; i < anchors.length; i++) {
            var anchor = anchors[i];
            try {
                var url = new URL(anchor.href, window.location.origin);
                if (url.protocol === 'http:' || url.protocol === 'https:') {
                    if (isMatch(url.hostname, domain2)) {
                        url.searchParams.set('ref_page', savedSlug);
                        anchor.href = url.toString();
                    }
                }
            } catch (e) {
                // Skip invalid URLs
            }
        }
    }

    /**
     * Listen to link clicks for:
     * 1. Outbound external links: clear ref_page.
     * 2. Affiliate links (Domain 2): inject ref_page right before navigation.
     */
    function registerClickListeners() {
        document.addEventListener('click', function(e) {
            var anchor = e.target.closest('a');
            if (anchor && anchor.href) {
                try {
                    var url = new URL(anchor.href, window.location.origin);
                    if (url.protocol === 'http:' || url.protocol === 'https:') {
                        var hostname = url.hostname;

                        if (isMatch(hostname, domain2)) {
                            // Target domain matched: dynamically inject ref_page parameter
                            var savedSlug = sessionStorage.getItem('ref_page');
                            if (savedSlug) {
                                url.searchParams.set('ref_page', savedSlug);
                                anchor.href = url.toString();
                            }
                        } else if (!isDomain1(hostname)) {
                            // External outbound link clicked: clear ref_page from sessionStorage
                            sessionStorage.removeItem('ref_page');
                        }
                    }
                } catch (err) {
                    // Skip invalid URLs
                }
            }
        }, true); // Use capturing phase to execute early
    }

    // Execute immediately on script load
    checkReferrerClearing();
    processTrackingLogic();
    registerClickListeners();

    // Scan DOM for link modifications
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', modifyStaticLinks);
    } else {
        modifyStaticLinks();
    }

})();
