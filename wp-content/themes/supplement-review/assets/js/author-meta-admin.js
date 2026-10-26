/**
 * Author Photo Media Picker Admin Script
 */
(function($) {
	'use strict';

	$(document).ready(function() {
		var uploadBtn = $('#sr-upload-author-photo-btn');
		var removeBtn = $('#sr-remove-author-photo-btn');
		var inputUrl = $('#sr_author_photo_url');
		var previewBox = $('#sr-author-photo-preview');
		var fileFrame;

		if (!uploadBtn.length) {
			return;
		}

		uploadBtn.on('click', function(e) {
			e.preventDefault();

			if (fileFrame) {
				fileFrame.open();
				return;
			}

			fileFrame = wp.media({
				title: 'Select or Upload Author Portrait',
				button: {
					text: 'Use this photo'
				},
				multiple: false,
				library: {
					type: 'image'
				}
			});

			fileFrame.on('select', function() {
				var attachment = fileFrame.state().get('selection').first().toJSON();
				inputUrl.val(attachment.url);
				previewBox.html('<img src="' + attachment.url + '" alt="" style="width:100%;height:100%;object-fit:cover;object-position:top center;display:block;" />');
				removeBtn.show();
			});

			fileFrame.open();
		});

		removeBtn.on('click', function(e) {
			e.preventDefault();
			inputUrl.val('');
			previewBox.html('<div style="display:flex;align-items:center;justify-content:center;height:100%;color:#8c8f94;font-size:11px;text-align:center;padding:4px;">No Photo</div>');
			$(this).hide();
		});
	});
})(jQuery);
