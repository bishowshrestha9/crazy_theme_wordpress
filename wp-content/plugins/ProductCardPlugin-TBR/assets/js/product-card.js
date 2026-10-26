/**
 * Product Card Plugin JavaScript
 * Handles interactive features and animations
 */

(function ($) {
    'use strict';

    $(document).ready(function () {

        function scrollToReviewsHeading(productName, cardElement) {
            var targetText = (productName || '').toLowerCase().trim() + ' reviews and complaints';
            var offset = 80;

            // Candidate content containers — ordered from most specific to least
            var contentSelectors = [
                '.entry-content',
                '.post-content',
                '.wp-block-post-content',
                'article .content',
                'article',
                'main',
                'body'
            ];

            // Classes/roles that mark a heading as a page title — skip these
            function isPageTitleHeading(el) {
                if (!el) return false;
                var cls = (el.className || '').toLowerCase();
                if (
                    cls.includes('entry-title') ||
                    cls.includes('post-title') ||
                    cls.includes('page-title') ||
                    cls.includes('site-title')
                ) return true;
                // Skip if the heading is inside a <header> element
                var parent = el.parentElement;
                while (parent) {
                    if (parent.tagName === 'HEADER') return true;
                    parent = parent.parentElement;
                }
                return false;
            }

            // Get the Y position of the card so we can skip headings above it
            var cardTop = cardElement
                ? cardElement.getBoundingClientRect().top + window.pageYOffset
                : 0;

            function doScroll(el) {
                var top = el.getBoundingClientRect().top + window.pageYOffset - offset;
                window.scrollTo({ top: top, behavior: 'smooth' });
                el.setAttribute('tabindex', '-1');
                el.focus({ preventScroll: true });
            }

            // Search inside content containers first
            for (var s = 0; s < contentSelectors.length; s++) {
                var container = document.querySelector(contentSelectors[s]);
                if (!container) continue;

                var h2s = container.querySelectorAll('h2');

                // Pass 1 — exact match inside content, below card, not a title heading
                for (var i = 0; i < h2s.length; i++) {
                    var el = h2s[i];
                    var elTop = el.getBoundingClientRect().top + window.pageYOffset;
                    if (isPageTitleHeading(el)) continue;
                    if (elTop <= cardTop) continue; // must be below the card
                    if (el.textContent && el.textContent.toLowerCase().trim() === targetText) {
                        doScroll(el);
                        return true;
                    }
                }

                // Pass 2 — partial match ("reviews and complaints") inside content, below card
                for (var j = 0; j < h2s.length; j++) {
                    var el2 = h2s[j];
                    var el2Top = el2.getBoundingClientRect().top + window.pageYOffset;
                    if (isPageTitleHeading(el2)) continue;
                    if (el2Top <= cardTop) continue;
                    if (el2.textContent && el2.textContent.toLowerCase().includes('reviews and complaints')) {
                        doScroll(el2);
                        return true;
                    }
                }

                // Only try other containers if the narrow ones found nothing
                if (container !== document.querySelector('body')) break;
            }

            // Final fallback — any heading with "reviews" that is below the card
            var allH = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
            for (var k = 0; k < allH.length; k++) {
                var hEl = allH[k];
                var hTop = hEl.getBoundingClientRect().top + window.pageYOffset;
                if (isPageTitleHeading(hEl)) continue;
                if (hTop <= cardTop) continue;
                if (hEl.textContent && hEl.textContent.toLowerCase().includes('reviews')) {
                    doScroll(hEl);
                    return true;
                }
            }

            return false;
        }


        $('.pcpx-read-reviews').on('click', function (e) {
            var href = $(this).attr('href') || '';
            var isSamePage = false;

            if (href === '' || href === '#') {
                isSamePage = true;
            } else {
                try {
                    var url = new URL(href, window.location.href);
                    isSamePage = url.origin === window.location.origin && url.pathname === window.location.pathname;
                } catch (error) {
                    isSamePage = false;
                }
            }

            if (isSamePage) {
                e.preventDefault();
                // Read the product name and pass the card element so we skip headings above the card
                var $card = $(this).closest('.pcpx-product-card-outer');
                var productName = $card.data('product-name') || '';
                scrollToReviewsHeading(productName, $card[0]);
            }
        });


        // Smooth scroll for "Read All Reviews" link
        $('.pcp-read-reviews').on('click', function (e) {
            e.preventDefault();

            // Add a subtle animation
            $(this).closest('.pcp-rating-section').addClass('pcp-pulse');
            setTimeout(function () {
                $('.pcp-rating-section').removeClass('pcp-pulse');
            }, 600);

            // You can add custom behavior here, like opening a modal or scrolling to reviews section
            console.log('Read All Reviews clicked');
        });

        // Add hover effect for product image
        $('.pcp-product-image').on('mouseenter', function () {
            $(this).addClass('pcp-image-hover');
        }).on('mouseleave', function () {
            $(this).removeClass('pcp-image-hover');
        });

        // Track Buy Now button clicks
        $('.pcp-buy-button').on('click', function (e) {
            var productCard = $(this).closest('.pcp-product-card');
            var status = productCard.hasClass('pcp-theme-legit') ? 'legit' : 'scam';

            // Add click animation
            $(this).addClass('pcp-button-clicked');
            setTimeout(() => {
                $(this).removeClass('pcp-button-clicked');
            }, 300);

            // Log analytics (you can integrate with Google Analytics or other tracking)
            console.log('Buy Now clicked for ' + status + ' product');

            // Optional: Add confirmation for scam products
            if (status === 'scam') {
                var confirmed = confirm('Warning: This product has been marked as a potential scam. Are you sure you want to proceed?');
                if (!confirmed) {
                    e.preventDefault();
                    return false;
                }
            }
        });

        // Animate benefits on scroll (Intersection Observer)
        if ('IntersectionObserver' in window) {
            const benefitObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('pcp-benefit-visible');
                    }
                });
            }, {
                threshold: 0.1
            });

            document.querySelectorAll('.pcp-benefit-item').forEach(benefit => {
                benefitObserver.observe(benefit);
            });
        }

        // Add dynamic star rating animation
        $('.pcp-stars').each(function () {
            var stars = $(this).find('.pcp-star');
            stars.each(function (index) {
                $(this).css('animation-delay', (index * 0.1) + 's');
            });
        });

        // Lazy load images if needed
        if ('loading' in HTMLImageElement.prototype) {
            $('.pcp-product-image').attr('loading', 'lazy');
        }

        // Add pulse animation class
        var style = document.createElement('style');
        style.textContent = `
            .pcp-pulse {
                animation: pcpPulseEffect 0.6s ease-in-out;
            }
            
            @keyframes pcpPulseEffect {
                0%, 100% { transform: scale(1); }
                50% { transform: scale(1.02); }
            }
            
            .pcp-button-clicked {
                transform: scale(0.95);
            }
            
            .pcp-benefit-visible {
                animation: pcpSlideIn 0.5s ease-out forwards;
            }
            
            @keyframes pcpSlideIn {
                from {
                    opacity: 0;
                    transform: translateX(-20px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }
            
            .pcp-star {
                animation: pcpStarPop 0.3s ease-out forwards;
                opacity: 0;
                transform: scale(0);
            }
            
            @keyframes pcpStarPop {
                0% {
                    opacity: 0;
                    transform: scale(0) rotate(-180deg);
                }
                100% {
                    opacity: 1;
                    transform: scale(1) rotate(0deg);
                }
            }
        `;
        document.head.appendChild(style);

    });

})(jQuery);
