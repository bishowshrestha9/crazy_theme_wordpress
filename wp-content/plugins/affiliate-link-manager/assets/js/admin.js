jQuery(document).ready(function ($) {
    const modal = $('#alm-modal');
    const form = $('#alm-link-form');
    const urlInput = $('#alm-target');
    const statusMsg = $('#alm-url-check-status');
    const parentBox = $('#alm-parent-info');
    const parentLabel = $('#alm-parent-label');
    const parentIdInput = $('#alm-parent-id');
    const parentTypeInput = $('#alm-parent-type');

    // Auto-generate slug from name
    $('#alm-name').on('input', function () {
        if ($('#alm-slug').val() === '') {
            const name = $(this).val();
            const slug = name.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_]+/g, '-')
                .replace(/^-+|-+$/g, '');
            $('#alm-slug').val(slug);
        }
    });

    // Open Modals
    $('#alm-add-new-btn').on('click', function () {
        form[0].reset();
        parentBox.hide();
        statusMsg.text('').removeClass('found error');
        modal.fadeIn(200).css('display', 'flex');
    });

    $('#alm-import-btn').on('click', function () {
        $('#alm-import-form')[0].reset();
        $('#alm-import-status').text('').removeClass('found error');
        $('#alm-import-modal').fadeIn(200).css('display', 'flex');
    });

    // Close Modals
    $('.alm-close, .alm-modal').on('click', function (e) {
        if (e.target === this || $(e.target).hasClass('alm-close')) {
            modal.fadeOut(200);
            $('#alm-import-modal').fadeOut(200);
        }
    });

    // Simple de-bounce for URL check
    let checkTimeout;
    urlInput.on('input', function () {
        const url = $(this).val();
        clearTimeout(checkTimeout);

        if (url.length < 5) {
            parentBox.hide();
            statusMsg.text('');
            return;
        }

        statusMsg.text('Checking for origin...').addClass('loading');

        checkTimeout = setTimeout(() => {
            $.ajax({
                url: almData.ajax_url,
                type: 'POST',
                data: {
                    action: 'alm_check_url',
                    security: almData.nonce,
                    url: url
                },
                success: function (response) {
                    statusMsg.removeClass('loading');
                    if (response.success) {
                        const data = response.data;
                        statusMsg.text('✓ Pretty Link Identified').addClass('found');
                        parentLabel.text(`${data.type}: ${data.label}`);
                        parentIdInput.val(data.id);
                        parentTypeInput.val(data.type);
                        parentBox.slideDown(200);
                    } else {
                        statusMsg.text('');
                        parentBox.slideUp(200);
                        parentIdInput.val('');
                        parentTypeInput.val('');
                    }
                }
            });
        }, 600);
    });

    // Save Link
    form.on('submit', function (e) {
        e.preventDefault();
        const data = form.serialize() + '&action=alm_save_link&security=' + almData.nonce;

        $.ajax({
            url: almData.ajax_url,
            type: 'POST',
            data: data,
            success: function (response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.data || 'Error saving link');
                }
            }
        });
    });

    // Handle Import
    $('#alm-import-form').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'alm_import_csv');
        formData.append('security', almData.nonce);

        const status = $('#alm-import-status');
        status.text('Importing... Please wait.').addClass('loading');

        $(this).find('button').prop('disabled', true);

        $.ajax({
            url: almData.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    status.text(response.data).removeClass('loading').addClass('found');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    status.text(response.data).removeClass('loading').addClass('error');
                    $('#alm-import-form').find('button').prop('disabled', false);
                }
            }
        });
    });

    // Delete Link
    $(document).on('click', '.alm-delete', function () {
        if (!confirm('Are you sure you want to delete this link?')) return;

        const btn = $(this);
        const id = btn.data('id');

        $.ajax({
            url: almData.ajax_url,
            type: 'POST',
            data: {
                action: 'alm_delete_link',
                id: id,
                security: almData.nonce
            },
            success: function (response) {
                if (response.success) {
                    btn.closest('tr').fadeOut(300, function () { $(this).remove(); });
                }
            }
        });
    });
});
