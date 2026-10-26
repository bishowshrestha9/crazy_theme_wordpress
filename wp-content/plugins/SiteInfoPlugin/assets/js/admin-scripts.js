jQuery(document).ready(function($) {
    'use strict';

    // Fetch Site Info Button Click
    $('#sip-fetch-btn').on('click', function(e) {
        e.preventDefault();

        var $button = $(this);
        var $spinner = $('.sip-button-group .spinner');
        var $message = $('#sip-fetch-message');

        // Clear previous messages
        $message.removeClass('success error').html('');

        // Show loading state
        $button.prop('disabled', true);
        $spinner.addClass('is-active');
        $button.find('.dashicons').addClass('spinning');

        // AJAX request
        $.ajax({
            url: sipAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'sip_fetch_site_info',
                nonce: sipAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $message.addClass('success').html('<strong>✓ Success!</strong> ' + response.data.message);
                    
                    // Reload page after 2 seconds to show results
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    $message.addClass('error').html('<strong>✗ Error!</strong> ' + response.data.message);
                    $button.prop('disabled', false);
                    $spinner.removeClass('is-active');
                    $button.find('.dashicons').removeClass('spinning');
                }
            },
            error: function(xhr, status, error) {
                $message.addClass('error').html('<strong>✗ Error!</strong> An unexpected error occurred. Please try again.');
                console.error('AJAX Error:', error);
                $button.prop('disabled', false);
                $spinner.removeClass('is-active');
                $button.find('.dashicons').removeClass('spinning');
            }
        });
    });
});
