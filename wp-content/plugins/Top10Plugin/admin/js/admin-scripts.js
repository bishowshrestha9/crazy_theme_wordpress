/**
 * Top 10 Plugin - Admin JavaScript
 * Interactive functionality for admin meta boxes
 */

(function ($) {
    'use strict';

    $(document).ready(function () {

        /**
         * Specifications Repeater
         */
        let specIndex = $('.top10-spec-row').length;

        $('.top10-add-spec').on('click', function () {
            const $container = $('#top10-specs-container');
            const $newRow = $('<div class="top10-spec-row" data-index="' + specIndex + '">' +
                '<input type="text" name="top10_specs[' + specIndex + '][label]" placeholder="Label (e.g., Weight)" class="regular-text">' +
                '<input type="text" name="top10_specs[' + specIndex + '][value]" placeholder="Value (e.g., 2.5 lbs)" class="regular-text">' +
                '<button type="button" class="button top10-remove-spec">Remove</button>' +
                '</div>');
            $container.append($newRow);
            specIndex++;
        });

        $(document).on('click', '.top10-remove-spec', function () {
            $(this).closest('.top10-spec-row').remove();
        });

        /**
         * Features Repeater
         */
        $('.top10-add-feature').on('click', function () {
            const $container = $('#top10-features-container');
            const $newRow = $('<div class="top10-feature-row">' +
                '<input type="text" name="top10_features[]" class="widefat" placeholder="Feature description">' +
                '<button type="button" class="button top10-remove-feature">Remove</button>' +
                '</div>');
            $container.append($newRow);
        });

        $(document).on('click', '.top10-remove-feature', function () {
            $(this).closest('.top10-feature-row').remove();
        });

        /**
         * Pros Repeater
         */
        $('.top10-add-pro').on('click', function () {
            const $container = $('#top10-pros-container');
            const $newRow = $('<div class="top10-pro-row">' +
                '<input type="text" name="top10_pros[]" class="widefat" placeholder="Pro">' +
                '<button type="button" class="button top10-remove-pro">Remove</button>' +
                '</div>');
            $container.append($newRow);
        });

        $(document).on('click', '.top10-remove-pro', function () {
            $(this).closest('.top10-pro-row').remove();
        });

        /**
         * Cons Repeater
         */
        $('.top10-add-con').on('click', function () {
            const $container = $('#top10-cons-container');
            const $newRow = $('<div class="top10-con-row">' +
                '<input type="text" name="top10_cons[]" class="widefat" placeholder="Con">' +
                '<button type="button" class="button top10-remove-con">Remove</button>' +
                '</div>');
            $container.append($newRow);
        });

        $(document).on('click', '.top10-remove-con', function () {
            $(this).closest('.top10-con-row').remove();
        });

        /**
         * FAQ Repeater
         */
        let faqIndex = $('.top10-faq-row').length;

        $('.top10-add-faq').on('click', function () {
            const $container = $('#top10-faqs-container');
            const $newRow = $('<div class="top10-faq-row" data-index="' + faqIndex + '" style="margin-bottom: 15px; padding: 15px; border: 1px solid #ddd;">' +
                '<p><label><strong>Question</strong></label>' +
                '<input type="text" name="top10_faqs[' + faqIndex + '][question]" class="widefat"></p>' +
                '<p><label><strong>Answer</strong></label>' +
                '<textarea name="top10_faqs[' + faqIndex + '][answer]" rows="3" class="widefat"></textarea></p>' +
                '<button type="button" class="button top10-remove-faq">Remove FAQ</button>' +
                '</div>');
            $container.append($newRow);
            faqIndex++;
        });

        $(document).on('click', '.top10-remove-faq', function () {
            $(this).closest('.top10-faq-row').remove();
        });

        /**
         * Product Table - Sortable
         */
        $('#top10-products-list').sortable({
            handle: '.row-mover',
            cursor: 'move',
            opacity: 0.8,
            axis: 'y'
        });

        /**
         * Product Table - Add Product
         */
        $('.top10-add-product-row').on('click', function () {
            const template = $('#top10-product-row-template').html();
            const index = new Date().getTime();
            const rowHtml = template.replace(/{{INDEX}}/g, index);

            $('#top10-products-list').append(rowHtml);
        });

        /**
         * Product Table - Remove Product
         */
        $(document).on('click', '.top10-remove-product-row', function () {
            if (confirm('Remove this product?')) {
                $(this).closest('tr').remove();
            }
        });

        /**
         * Product Table - Image Upload
         */
        $(document).on('click', '.upload-product-image', function (e) {
            e.preventDefault();
            const $button = $(this);
            const $row = $button.closest('td');
            const $hidden = $row.find('.product-image-url');
            const $preview = $row.find('.image-preview');

            // Re-use or create new uploader
            // To be safe with multiple rows, better create new instance or store it on the button
            const imageUploader = wp.media({
                title: 'Select Product Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });

            imageUploader.on('select', function () {
                const attachment = imageUploader.state().get('selection').first().toJSON();
                $hidden.val(attachment.url);
                $preview.html('<img src="' + attachment.url + '" style="max-width: 100%; max-height: 100%;">');
            });

            imageUploader.open();
        });

    });

})(jQuery);
