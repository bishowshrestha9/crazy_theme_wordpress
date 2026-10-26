/**
 * Gutenberg Block Registration for "Review Form"
 * Accessible in block editor by typing "/review form"
 */
(function(wp) {
	'use strict';

	if (!wp || !wp.blocks || !wp.element) {
		return;
	}

	const { registerBlockType } = wp.blocks;
	const { createElement: el } = wp.element;

	registerBlockType('review-form/submission-form', {
		title: 'Review Form',
		icon: 'feedback',
		category: 'widgets',
		keywords: ['review', 'review form', 'write a review'],
		description: 'Insert the Write a Review submission form into any post or page.',
		supports: {
			html: false,
			align: ['wide', 'full']
		},
		edit: function() {
			return el(
				'div',
				{
					style: {
						padding: '28px',
						border: '2px dashed #6fa68f',
						borderRadius: '10px',
						backgroundColor: '#f7faf8',
						textAlign: 'center',
						fontFamily: 'Inter, sans-serif'
					}
				},
				el('div', {
					style: {
						display: 'inline-flex',
						alignItems: 'center',
						justifyContent: 'center',
						width: '40px',
						height: '40px',
						borderRadius: '50%',
						backgroundColor: '#6fa68f',
						color: '#ffffff',
						fontSize: '20px',
						marginBottom: '10px'
					}
				}, '✍️'),
				el(
					'h4',
					{ style: { margin: '0 0 6px 0', fontSize: '16px', fontWeight: 600, color: '#1a202c' } },
					'Write a Review Form'
				),
				el(
					'p',
					{ style: { margin: 0, fontSize: '13px', color: '#6b7280' } },
					'The full "Write a Review" form will render here on the front end.'
				)
			);
		},
		save: function() {
			// Rendered server-side via PHP callback
			return null;
		}
	});
})(window.wp);
