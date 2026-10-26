/* global spcAdmin, jQuery */
(function ($) {
    'use strict';

    var posts = [];     // results from scan
    var doneCount = 0;
    var errorCount = 0;

    /* ── Scan ──────────────────────────────────────────────────── */
    $('#spc-scan-btn').on('click', function () {
        var $btn = $(this);
        $btn.prop('disabled', true);
        $('#spc-scan-spinner').show();
        $('#spc-scan-status').text('Scanning posts…');
        $('#spc-results').hide();
        $('#spc-empty').hide();

        $.post(spcAdmin.ajaxUrl, {
            action: 'spc_scan',
            nonce: spcAdmin.nonce,
        })
            .done(function (res) {
                if (!res.success) {
                    $('#spc-scan-status').text('Error: ' + (res.data.message || 'Unknown error.'));
                    return;
                }

                posts = res.data.posts;
                doneCount = 0;
                errorCount = 0;

                updateStats(posts.length, 0, 0);
                $('#spc-scan-status').text('Scan complete. Found ' + posts.length + ' post(s).');

                if (posts.length === 0) {
                    $('#spc-empty').show();
                } else {
                    renderPosts(posts);
                    $('#spc-results').show();
                    $('#spc-convert-all-card').show();
                }
            })
            .fail(function () {
                $('#spc-scan-status').text('Request failed. Please try again.');
            })
            .always(function () {
                $btn.prop('disabled', false);
                $('#spc-scan-spinner').hide();
            });
    });

    /* ── Render post list ──────────────────────────────────────── */
    function renderPosts(list) {
        var $container = $('#spc-posts-list').empty();

        if (list.length === 0) {
            $container.html('<p style="padding:24px;color:#888;">No posts to display.</p>');
            return;
        }

        $.each(list, function (_, post) {
            $container.append(buildPostRow(post));
        });
    }

    function buildPostRow(post) {
        var pairsHtml = '';

        $.each(post.pairs, function (_, pair) {
            var prosList = '';
            $.each(pair.pros_items, function (_, item) {
                prosList += '<li>' + item + '</li>';
            });
            if (pair.pros_total > 3) {
                prosList += '<li class="spc-preview-more">+ ' + (pair.pros_total - 3) + ' more…</li>';
            }

            var consList = '';
            $.each(pair.cons_items, function (_, item) {
                consList += '<li>' + item + '</li>';
            });
            if (pair.cons_total > 3) {
                consList += '<li class="spc-preview-more">+ ' + (pair.cons_total - 3) + ' more…</li>';
            }

            pairsHtml +=
                '<div class="spc-preview-pair">' +
                '<div class="spc-preview-pros">' +
                '<div class="spc-preview-header">✓ ' + pair.pros_title + '</div>' +
                '<ul>' + (prosList || '<li style="color:#bbb;">No items detected</li>') + '</ul>' +
                '</div>' +
                '<div class="spc-preview-cons">' +
                '<div class="spc-preview-header">✗ ' + pair.cons_title + '</div>' +
                '<ul>' + (consList || '<li style="color:#bbb;">No items detected</li>') + '</ul>' +
                '</div>' +
                '</div>';
        });

        var sections = post.count === 1 ? '1 section' : post.count + ' sections';

        return $(
            '<div class="spc-post-row" data-id="' + post.id + '">' +
            '<div class="spc-post-info">' +
            '<p class="spc-post-title">' + post.title + '</p>' +
            '<p class="spc-post-meta">' + post.post_type + ' &nbsp;|&nbsp; ID: ' + post.id + ' &nbsp;|&nbsp; <strong>' + sections + ' detected</strong></p>' +
            '<div class="spc-post-links">' +
            '<a href="' + post.edit_link + '" target="_blank">✏️ Edit</a>' +
            '<a href="' + post.view_link + '" target="_blank">👁 View</a>' +
            '</div>' +
            pairsHtml +
            '</div>' +
            '<div class="spc-post-actions">' +
            '<button class="spc-btn spc-btn-primary spc-btn-sm spc-convert-btn" data-id="' + post.id + '">' +
            '⚡ Convert' +
            '</button>' +
            '<div class="spc-convert-status" style="display:none;"></div>' +
            '</div>' +
            '</div>'
        );
    }

    /* ── Convert single post ───────────────────────────────────── */
    $(document).on('click', '.spc-convert-btn', function () {
        var $btn = $(this);
        var postId = $btn.data('id');
        convertPost(postId, $btn);
    });

    function convertPost(postId, $btn, callback) {
        var $row = $('.spc-post-row[data-id="' + postId + '"]');
        var $status = $row.find('.spc-convert-status');

        if ($btn) {
            $btn.prop('disabled', true).text('⏳ Converting…');
        }

        $.post(spcAdmin.ajaxUrl, {
            action: 'spc_convert',
            nonce: spcAdmin.nonce,
            post_id: postId,
        })
            .done(function (res) {
                if (res.success) {
                    $row.addClass('spc-row-done');
                    $status.show().addClass('spc-status-success').text('✓ ' + res.data.message);
                    if ($btn) { $btn.hide(); }
                    doneCount++;
                    updateStats(posts.length, doneCount, errorCount);
                    if (callback) { callback(true); }
                } else {
                    $row.addClass('spc-row-error');
                    $status.show().addClass('spc-status-error').text('✗ ' + (res.data.message || 'Error.'));
                    if ($btn) { $btn.prop('disabled', false).text('⚡ Retry'); }
                    errorCount++;
                    updateStats(posts.length, doneCount, errorCount);
                    if (callback) { callback(false); }
                }
            })
            .fail(function () {
                $status.show().addClass('spc-status-error').text('✗ Request failed.');
                if ($btn) { $btn.prop('disabled', false).text('⚡ Retry'); }
                errorCount++;
                updateStats(posts.length, doneCount, errorCount);
                if (callback) { callback(false); }
            });
    }

    /* ── Convert All ───────────────────────────────────────────── */
    $('#spc-convert-all-btn').on('click', function () {
        var $btn = $(this).prop('disabled', true).text('⏳ Converting…');
        var pending = [];
        var total = 0;
        var processed = 0;

        // Collect unconverted rows
        $('.spc-post-row').each(function () {
            var $row = $(this);
            if (!$row.hasClass('spc-row-done')) {
                pending.push({
                    id: $row.data('id'),
                    btn: $row.find('.spc-convert-btn'),
                });
            }
        });

        total = pending.length;

        if (total === 0) {
            $btn.prop('disabled', false).text('⚡ Convert All');
            return;
        }

        $('#spc-progress-bar-wrap').show();
        updateProgress(0, total);

        function processNext(index) {
            if (index >= pending.length) {
                $btn.prop('disabled', false).text('⚡ Convert All');
                updateProgress(total, total);
                $('#spc-progress-label').text('All done! ' + doneCount + ' converted, ' + errorCount + ' errors.');
                return;
            }

            var item = pending[index];
            convertPost(item.id, item.btn, function () {
                processed++;
                updateProgress(processed, total);
                processNext(index + 1);
            });
        }

        processNext(0);
    });

    /* ── Helpers ───────────────────────────────────────────────── */
    function updateStats(found, done, error) {
        $('#spc-stat-found .spc-stat-num').text(found);
        $('#spc-stat-done  .spc-stat-num').text(done);
        $('#spc-stat-error .spc-stat-num').text(error);
    }

    function updateProgress(processed, total) {
        var pct = total > 0 ? Math.round((processed / total) * 100) : 0;
        $('#spc-progress-fill').css('width', pct + '%');
        $('#spc-progress-label').text('Converting… ' + processed + ' / ' + total + ' (' + pct + '%)');
    }

})(jQuery);
