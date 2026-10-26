(function () {
	const { registerBlockType } = wp.blocks;
	const { InspectorControls, RichText } = wp.blockEditor || wp.editor;
	const { PanelBody, TextControl, ToggleControl, Button } = wp.components;
	const { __ } = wp.i18n;
	const { createElement: el, Fragment } = wp.element;

	registerBlockType('review-summary-notice/notice-card', {
		title: __('Review Summary Notice', 'review-summary-notice'),
		description: __('Display an editable Product Review Summary notice box.', 'review-summary-notice'),
		icon: 'info-outline',
		category: 'common',
		keywords: [__('notice', 'review-summary-notice'), __('review summary', 'review-summary-notice'), __('affiliatex', 'review-summary-notice')],
		attributes: {
			blockId: { type: 'string', default: '' },
			title: { type: 'string', default: 'OptiCycle Review Summary' },
			items: {
				type: 'array',
				default: [
					{ content: 'Customer experiences highlight quick air freshening, quiet operation, and allergy relief.' },
					{ content: 'Our experts confirm OptiCycle Air Cleaner effectively purifies small spaces.' },
					{ content: 'Overall Rating: 4.2/5 a worthwhile buy for personal use.' }
				]
			},
			headerBgColor: { type: 'string', default: '#5ea3e1' },
			headerTextColor: { type: 'string', default: '#ffffff' },
			iconColor: { type: 'string', default: '#5ea3e1' },
			borderColor: { type: 'string', default: '#e1ebf5' },
			cardBgColor: { type: 'string', default: '#ffffff' },
			textColor: { type: 'string', default: '#333333' },
			showIcon: { type: 'boolean', default: true }
		},

		edit: function (props) {
			const { attributes, setAttributes } = props;
			const {
				title,
				items,
				headerBgColor,
				headerTextColor,
				iconColor,
				borderColor,
				cardBgColor,
				textColor,
				showIcon
			} = attributes;

			// Item manipulation helpers
			const updateItemContent = (index, newContent) => {
				const newItems = [...items];
				newItems[index] = { ...newItems[index], content: newContent };
				setAttributes({ items: newItems });
			};

			const addItem = () => {
				const newItems = [...items, { content: 'New review summary point...' }];
				setAttributes({ items: newItems });
			};

			const removeItem = (index) => {
				const newItems = items.filter((_, i) => i !== index);
				setAttributes({ items: newItems });
			};

			const moveItem = (index, direction) => {
				const targetIndex = index + direction;
				if (targetIndex < 0 || targetIndex >= items.length) return;
				const newItems = [...items];
				const temp = newItems[index];
				newItems[index] = newItems[targetIndex];
				newItems[targetIndex] = temp;
				setAttributes({ items: newItems });
			};

			return el(
				Fragment,
				{},
				el(
					InspectorControls,
					{},
					// 1. Title Settings Panel
					el(
						PanelBody,
						{ title: __('Header Settings', 'review-summary-notice'), initialOpen: true },
						el(ToggleControl, {
							label: __('Show Header Info Icon', 'review-summary-notice'),
							checked: showIcon,
							onChange: (val) => setAttributes({ showIcon: val })
						})
					),
					// 2. Color Settings Panel
					el(
						PanelBody,
						{ title: __('Colors & Styling', 'review-summary-notice'), initialOpen: false },
						el('p', { style: { marginBottom: '6px', fontWeight: '500' } }, __('Header Background Color', 'review-summary-notice')),
						el(TextControl, {
							value: headerBgColor,
							onChange: (val) => setAttributes({ headerBgColor: val })
						}),
						el('p', { style: { marginBottom: '6px', fontWeight: '500' } }, __('Header Text Color', 'review-summary-notice')),
						el(TextControl, {
							value: headerTextColor,
							onChange: (val) => setAttributes({ headerTextColor: val })
						}),
						el('p', { style: { marginBottom: '6px', fontWeight: '500' } }, __('Checkmark Icon Color', 'review-summary-notice')),
						el(TextControl, {
							value: iconColor,
							onChange: (val) => setAttributes({ iconColor: val })
						}),
						el('p', { style: { marginBottom: '6px', fontWeight: '500' } }, __('Card Border Color', 'review-summary-notice')),
						el(TextControl, {
							value: borderColor,
							onChange: (val) => setAttributes({ borderColor: val })
						})
					)
				),

				// Main Canvas Editor Block UI
				el(
					'div',
					{
						className: 'rsn-notice-wrapper',
						style: {
							borderColor: borderColor,
							backgroundColor: cardBgColor,
							color: textColor
						}
					},
					// Header Banner (Editable Title)
					el(
						'div',
						{
							className: 'rsn-notice-header',
							style: {
								backgroundColor: headerBgColor,
								color: headerTextColor
							}
						},
						showIcon &&
							el(
								'span',
								{ className: 'rsn-header-icon' },
								el(
									'svg',
									{ width: 18, height: 18, viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', strokeWidth: '2.5', strokeLinecap: 'round', strokeLinejoin: 'round' },
									el('circle', { cx: 12, cy: 12, r: 10 }),
									el('line', { x1: 12, y1: 16, x2: 12, y2: 12 }),
									el('line', { x1: 12, y1: 8, x2: 12.01, y2: 8 })
								)
							),
						el(RichText, {
							tagName: 'h3',
							className: 'rsn-header-title',
							value: title,
							onChange: (val) => setAttributes({ title: val }),
							placeholder: __('OptiCycle Review Summary', 'review-summary-notice'),
							style: { color: headerTextColor },
							allowedFormats: ['core/bold', 'core/italic']
						})
					),

					// Body Section (List Items)
					el(
						'div',
						{ className: 'rsn-notice-body' },
						el(
							'ul',
							{ className: 'rsn-notice-list' },
							items.map((item, index) =>
								el(
									'li',
									{ key: index, className: 'rsn-notice-item', style: { alignItems: 'flex-start' } },
									el(
										'span',
										{ className: 'rsn-item-icon', style: { color: iconColor } },
										el(
											'svg',
											{ width: 18, height: 18, viewBox: '0 0 24 24', fill: 'currentColor' },
											el('path', { d: 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z' })
										)
									),
									el(
										'div',
										{ className: 'rsn-item-content-wrapper', style: { flex: 1, display: 'flex', alignItems: 'flex-start', gap: '8px' } },
										el(RichText, {
											tagName: 'span',
											className: 'rsn-item-text',
											value: item.content || '',
											onChange: (newVal) => updateItemContent(index, newVal),
											placeholder: __('Enter summary bullet point...', 'review-summary-notice'),
											allowedFormats: ['core/bold', 'core/italic', 'core/link']
										}),
										el(
											'div',
											{ className: 'rsn-item-actions', style: { display: 'flex', gap: '2px', flexShrink: 0, marginTop: '2px' } },
											el(Button, {
												icon: 'arrow-up-alt2',
												label: __('Move Up', 'review-summary-notice'),
												isSmall: true,
												onClick: () => moveItem(index, -1),
												disabled: index === 0
											}),
											el(Button, {
												icon: 'arrow-down-alt2',
												label: __('Move Down', 'review-summary-notice'),
												isSmall: true,
												onClick: () => moveItem(index, 1),
												disabled: index === items.length - 1
											}),
											el(Button, {
												icon: 'no-alt',
												label: __('Remove Item', 'review-summary-notice'),
												isDestructive: true,
												isSmall: true,
												onClick: () => removeItem(index)
											})
										)
									)
								)
							)
						),
						el(
							'div',
							{ style: { marginTop: '16px' } },
							el(
								Button,
								{
									variant: 'secondary',
									icon: 'plus',
									onClick: addItem
								},
								__('Add Summary Item', 'review-summary-notice')
							)
						)
					)
				)
			);
		},

		save: function () {
			return null;
		}
	});
})();
