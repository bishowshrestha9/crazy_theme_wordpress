/**
 * Sticky Sidebar Script
 */
(function($) {
    'use strict';
    
    $(document).ready(function() {
        var $sidebar = $('.sidebar-sticky');
        var $sidebarParent = $('.top10-sidebar');
        var $mainLayout = $('.top10-main-layout');
        
        if ($sidebar.length === 0) return;
        
        var sidebarTop = $sidebar.offset().top;
        var sidebarHeight = $sidebar.outerHeight();
        
        $(window).scroll(function() {
            var scrollTop = $(window).scrollTop();
            var windowHeight = $(window).height();
            var mainLayoutBottom = $mainLayout.offset().top + $mainLayout.outerHeight();
            
            if (scrollTop >= sidebarTop - 20) {
                // Calculate if sidebar should stick
                var maxScroll = mainLayoutBottom - sidebarHeight - 20;
                
                if (scrollTop < maxScroll) {
                    $sidebar.css({
                        'position': 'fixed',
                        'top': '20px',
                        'width': $sidebarParent.width() + 'px'
                    });
                } else {
                    // At bottom
                    $sidebar.css({
                        'position': 'absolute',
                        'top': 'auto',
                        'bottom': '0',
                        'width': '100%'
                    });
                }
            } else {
                // Reset
                $sidebar.css({
                    'position': 'relative',
                    'top': 'auto',
                    'width': '100%'
                });
            }
        });
        
        // Update on resize
        $(window).resize(function() {
            if ($sidebar.css('position') === 'fixed') {
                $sidebar.css('width', $sidebarParent.width() + 'px');
            }
        });
    });
})(jQuery);
