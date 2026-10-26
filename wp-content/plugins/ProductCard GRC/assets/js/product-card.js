/**
 * Product Card Plugin JavaScript
 * Handles interactive features and animations
 */

(function ($) {
    'use strict';

    $(document).ready(function () {

        function scrollToReviewsHeading() {
            const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
            for (const heading of headings) {
                if (heading.textContent && heading.textContent.toLowerCase().includes('reviews')) {
                    const offset = 80;
                    const top = heading.getBoundingClientRect().top + window.pageYOffset - offset;
                    window.scrollTo({ top, behavior: 'smooth' });
                    heading.setAttribute('tabindex', '-1');
                    heading.focus({ preventScroll: true });
                    return true;
                }
            }
            return false;
        }

        $('.pcpx-read-reviews').on('click', function (e) {
            const href = $(this).attr('href') || '';
            let isSamePage = false;

            if (href === '' || href === '#') {
                isSamePage = true;
            } else {
                try {
                    const url = new URL(href, window.location.href);
                    isSamePage = url.origin === window.location.origin && url.pathname === window.location.pathname;
                } catch (error) {
                    isSamePage = false;
                }
            }

            if (isSamePage) {
                e.preventDefault();
                scrollToReviewsHeading();
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
