/**
 * Top 10 Plugin - Frontend JavaScript
 * Interactive functionality for accordions and smooth scrolling
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        
        /**
         * FAQ Accordion
         */
        $('.faq-question').on('click', function() {
            const $question = $(this);
            const $answer = $question.next('.faq-answer');
            const isExpanded = $question.attr('aria-expanded') === 'true';
            
            // Close all other FAQs
            $('.faq-question').attr('aria-expanded', 'false');
            $('.faq-answer').css('max-height', '0');
            
            // Toggle current FAQ
            if (!isExpanded) {
                $question.attr('aria-expanded', 'true');
                $answer.css('max-height', $answer[0].scrollHeight + 'px');
            }
        });
        
        /**
         * Smooth scroll to product details
         */
        window.scrollToProduct = function(rank) {
            const $product = $('#product-' + rank);
            if ($product.length) {
                $('html, body').animate({
                    scrollTop: $product.offset().top - 100
                }, 800);
            }
        };
        
        /**
         * Animate rating bars on scroll
         */
        function animateRatingBars() {
            $('.top10-rating-bar').each(function() {
                const $bar = $(this);
                const $fill = $bar.find('.rating-bar-fill');
                const targetWidth = $fill.css('width');
                
                // Check if element is in viewport
                const elementTop = $bar.offset().top;
                const elementBottom = elementTop + $bar.outerHeight();
                const viewportTop = $(window).scrollTop();
                const viewportBottom = viewportTop + $(window).height();
                
                if (elementBottom > viewportTop && elementTop < viewportBottom) {
                    if (!$bar.hasClass('animated')) {
                        $fill.css('width', '0');
                        setTimeout(function() {
                            $fill.css('width', targetWidth);
                        }, 100);
                        $bar.addClass('animated');
                    }
                }
            });
        }
        
        // Trigger on scroll
        $(window).on('scroll', animateRatingBars);
        
        // Trigger on load
        animateRatingBars();
        
        /**
         * Product card hover effects
         */
        $('.product-card').hover(
            function() {
                $(this).find('.product-image img').css('transform', 'scale(1.05)');
            },
            function() {
                $(this).find('.product-image img').css('transform', 'scale(1)');
            }
        );
        
    });

})(jQuery);
