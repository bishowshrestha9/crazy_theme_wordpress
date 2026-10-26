/**
 * Gutenberg Blocks Registration for Sources and FAQ Accordion
 * Registered for typing /sources and /faq in the block editor.
 */
(function(wp) {
	'use strict';

	if (!wp || !wp.blocks || !wp.element) {
		return;
	}

	const { registerBlockType } = wp.blocks;
	const { createElement: el, Fragment } = wp.element;
	const { InspectorControls } = wp.blockEditor || wp.editor;
	const { PanelBody, TextControl, TextareaControl, ToggleControl, Button } = wp.components;

	// Default Sample Sources from Screenshot
	const defaultSources = [
		{
			citation: 'Schwarz, P.E., Timpel, P., Harst, L., Greaves, C.J., Ali, M.K., Lambert, J., Weber, M.B., Almedawar, M.M. and Morawietz, H., 2018. Blood sugar regulation for cardiovascular health promotion and disease prevention: JACC health promotion series. Journal of the American College of Cardiology, 72(15), pp.1829-1844.',
			url: 'https://www.jacc.org/doi/abs/10.1016/j.jacc.2018.07.081'
		},
		{
			citation: 'Raj, S., Toporski, K., Garrity, A., Lee, J.M. and Newman, M.W., 2019, May. ” My blood sugar is higher on the weekends” Finding a Role for Context and Context-Awareness in the Design of Health Self-Management Technology. In Proceedings of the 2019 CHI Conference on Human Factors in Computing Systems (pp. 1-13).',
			url: 'https://dl.acm.orgdoiabs/10.1145/3290605.3300349casa_token=GW2iRLArmjoAAAAA:43mecdUTeDr9i_ulgDBVXcj1EY3t8FSNQs7hlX6AJyNSR1FcGVj1LUyl8r5RGsUzFjqfW2KiO7NFpw'
		},
		{
			citation: 'Chih, A.H., Jan, C.F., Shu, S.G. and Lue, B.H., 2010. Self-efficacy affects blood sugar control among adolescents with type I diabetes mellitus. Journal of the Formosan Medical Association, 109(7), pp.503-510.',
			url: 'https://www.sciencedirect.com/science/article/pii/S0929664610600848'
		},
		{
			citation: 'Numsang, P., Oumtanee, A., Kurat, S., Sananok, R., Kraichan, S. and Sarapoke, P., 2023. “Failure to control blood sugar” experiences of persons with type 2 diabetes mellitus. International Journal of Nursing Sciences, 10(4), pp.527-532.',
			url: 'https://www.sciencedirect.com/science/article/pii/S2352013223000972'
		},
		{
			citation: 'Navicharern, R., 2012. Diabetes self-management, fasting blood sugar and quality of life among type 2 diabetic patients with foot ulcers. Journal of the Medical Association of Thailand= Chotmaihet thangphaet, 95(2), pp.156-162.',
			url: 'https://europepmc.org/articlemed/22435243utm_medium=email&utm_source=transaction&client=bot&client=bot&client=bot'
		}
	];

	// Default Sample FAQs from Screenshot
	const defaultFaqs = [
		{
			question: 'What is GlucoReset drops?',
			answer: 'Gluco Reset metabolic formula is a liquid Blood Sugar supplement designed to support healthy metabolism, glucose metabolism, balanced energy and everyday metabolic wellness.'
		},
		{
			question: 'How much does Gluco Reset cost?',
			answer: 'Gluco Reset is offered in several discounted packages starting at $12.99 per bottle for multi-bottle bundles, up to $17.99 for a single starter bottle, all backed by free domestic shipping.'
		},
		{
			question: 'How should I take Gluco Reset glucose support supplement?',
			answer: 'Take 1 full dropper (approximately 1 mL) under the tongue each morning before breakfast. Hold for 30 seconds before swallowing for optimal sublingual absorption.'
		},
		{
			question: 'How long does GlucoReset glucose supplement take to use?',
			answer: 'While many users notice steady morning energy within 7 to 14 days, clinical studies on active ingredients suggest peak metabolic benefits after 60 to 90 days of consistent daily use.'
		},
		{
			question: 'Is there any Gluco Reset money-back guarantee?',
			answer: 'Yes, all orders purchased through the official merchant link are protected by an unconditional 60-day 100% money-back guarantee with zero questions asked.'
		},
		{
			question: 'Can children use Gluco Reset Metabolic support supplement?',
			answer: 'No, Gluco Reset is specifically formulated for adults aged 18 and older. Pregnant or nursing mothers and individuals with known medical conditions should consult a physician.'
		}
	];

	/* ==========================================================================
	   1. Sources Block (/sources)
	   ========================================================================== */
	registerBlockType('sources-faq/sources', {
		title: 'Sources & Citations',
		icon: 'media-document',
		category: 'widgets',
		keywords: ['sources', 'references', 'citations', 'bibliography'],
		description: 'Display an academic/medical citations card with verified URLs.',
		attributes: {
			heading: {
				type: 'string',
				default: 'Sources'
			},
			items: {
				type: 'array',
				default: defaultSources
			}
		},
		edit: function(props) {
			const { attributes, setAttributes } = props;
			const { heading, items } = attributes;

			function updateItem(index, key, value) {
				const newItems = items.map(function(item, i) {
					if (i === index) {
						const updated = Object.assign({}, item);
						updated[key] = value;
						return updated;
					}
					return item;
				});
				setAttributes({ items: newItems });
			}

			function addItem() {
				setAttributes({
					items: items.concat([{ citation: '', url: 'https://' }])
				});
			}

			function removeItem(index) {
				setAttributes({
					items: items.filter(function(_, i) { return i !== index; })
				});
			}

			function moveItem(index, direction) {
				const newIndex = index + direction;
				if (newIndex < 0 || newIndex >= items.length) return;
				const newItems = items.slice();
				const temp = newItems[index];
				newItems[index] = newItems[newIndex];
				newItems[newIndex] = temp;
				setAttributes({ items: newItems });
			}

			return el(Fragment, null,
				el(InspectorControls, null,
					el(PanelBody, { title: 'Sources Settings', initialOpen: true },
						el(TextControl, {
							label: 'Section Heading',
							value: heading,
							onChange: function(val) { setAttributes({ heading: val }); }
						}),
						el(Button, {
							isSecondary: true,
							style: { marginTop: '10px' },
							onClick: function() { setAttributes({ items: defaultSources }); }
						}, 'Reset to Default Sample Sources')
					)
				),
				el('div', { style: { padding: '20px', border: '1px solid #e2e8f0', borderRadius: '10px', background: '#ffffff', fontFamily: 'Inter, sans-serif' } },
					el('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '14px' } },
						el('h3', { style: { margin: 0, fontSize: '22px', fontWeight: 700, color: '#111111' } }, heading || 'Sources'),
						el(Button, { isPrimary: true, onClick: addItem }, '+ Add Citation')
					),
					el('hr', { style: { border: 'none', height: '1px', backgroundColor: '#e5e7eb', margin: '0 0 20px 0' } }),
					items.map(function(item, idx) {
						return el('div', { key: idx, style: { padding: '14px', border: '1px solid #edf2f7', borderRadius: '8px', marginBottom: '14px', background: '#f8fafc' } },
							el('div', { style: { display: 'flex', justifyContent: 'space-between', marginBottom: '8px' } },
								el('strong', { style: { fontSize: '13px', color: '#4a5568' } }, 'Citation #' + (idx + 1)),
								el('div', { style: { display: 'flex', gap: '4px' } },
									el(Button, { isSmall: true, disabled: idx === 0, onClick: function() { moveItem(idx, -1); } }, '↑'),
									el(Button, { isSmall: true, disabled: idx === items.length - 1, onClick: function() { moveItem(idx, 1); } }, '↓'),
									el(Button, { isDestructive: true, isSmall: true, onClick: function() { removeItem(idx); } }, 'Remove')
								)
							),
							el(TextareaControl, {
								label: 'Citation Details',
								value: item.citation,
								rows: 3,
								placeholder: 'Authors, Year, Title, Journal/Publisher info...',
								onChange: function(val) { updateItem(idx, 'citation', val); }
							}),
							el(TextControl, {
								label: 'Available at (URL)',
								value: item.url,
								placeholder: 'https://...',
								onChange: function(val) { updateItem(idx, 'url', val); }
							})
						);
					}),
					el(Button, { isSecondary: true, isFullWidth: true, onClick: addItem }, '+ Add Another Citation')
				)
			);
		},
		save: function() {
			return null; // Server-side rendering
		}
	});

	/* ==========================================================================
	   2. FAQ Accordion Block (/faq)
	   ========================================================================== */
	registerBlockType('sources-faq/faq', {
		title: 'FAQ Accordion',
		icon: 'list-view',
		category: 'widgets',
		keywords: ['faq', 'accordion', 'questions', 'answers'],
		description: 'Display an interactive, accessible FAQ accordion with FAQPage Schema.',
		attributes: {
			heading: {
				type: 'string',
				default: 'How to Use Glucose Reset Metabolic Support?'
			},
			firstOpen: {
				type: 'boolean',
				default: true
			},
			singleOpen: {
				type: 'boolean',
				default: true
			},
			items: {
				type: 'array',
				default: defaultFaqs
			}
		},
		edit: function(props) {
			const { attributes, setAttributes } = props;
			const { heading, firstOpen, singleOpen, items } = attributes;

			function updateItem(index, key, value) {
				const newItems = items.map(function(item, i) {
					if (i === index) {
						const updated = Object.assign({}, item);
						updated[key] = value;
						return updated;
					}
					return item;
				});
				setAttributes({ items: newItems });
			}

			function addItem() {
				setAttributes({
					items: items.concat([{ question: '', answer: '' }])
				});
			}

			function removeItem(index) {
				setAttributes({
					items: items.filter(function(_, i) { return i !== index; })
				});
			}

			function moveItem(index, direction) {
				const newIndex = index + direction;
				if (newIndex < 0 || newIndex >= items.length) return;
				const newItems = items.slice();
				const temp = newItems[index];
				newItems[index] = newItems[newIndex];
				newItems[newIndex] = temp;
				setAttributes({ items: newItems });
			}

			return el(Fragment, null,
				el(InspectorControls, null,
					el(PanelBody, { title: 'FAQ Settings', initialOpen: true },
						el(TextControl, {
							label: 'Section Heading',
							value: heading,
							onChange: function(val) { setAttributes({ heading: val }); }
						}),
						el(ToggleControl, {
							label: 'First Item Open by Default',
							help: 'Open the first question automatically when the page loads.',
							checked: firstOpen,
							onChange: function(val) { setAttributes({ firstOpen: val }); }
						}),
						el(ToggleControl, {
							label: 'Single Open Mode (Accordion)',
							help: 'Automatically close other items when a question is clicked.',
							checked: singleOpen,
							onChange: function(val) { setAttributes({ singleOpen: val }); }
						}),
						el(Button, {
							isSecondary: true,
							style: { marginTop: '10px' },
							onClick: function() { setAttributes({ items: defaultFaqs }); }
						}, 'Reset to Default Questions')
					)
				),
				el('div', { style: { padding: '20px', border: '1px solid #e2e8f0', borderRadius: '10px', background: '#ffffff', fontFamily: 'Inter, sans-serif' } },
					el('div', { style: { display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '14px' } },
						el('h3', { style: { margin: 0, fontSize: '22px', fontWeight: 700, color: '#111111' } }, heading || 'FAQ Accordion'),
						el(Button, { isPrimary: true, onClick: addItem }, '+ Add Question')
					),
					el('hr', { style: { border: 'none', height: '1px', backgroundColor: '#e5e7eb', margin: '0 0 20px 0' } }),
					items.map(function(item, idx) {
						return el('div', { key: idx, style: { padding: '14px', border: '1px solid #edf2f7', borderRadius: '8px', marginBottom: '14px', background: '#f8fafc' } },
							el('div', { style: { display: 'flex', justifyContent: 'space-between', marginBottom: '8px' } },
								el('strong', { style: { fontSize: '13px', color: '#2d3748' } }, (idx === 0 && firstOpen ? '[-] ' : '[+] ') + 'Question #' + (idx + 1)),
								el('div', { style: { display: 'flex', gap: '4px' } },
									el(Button, { isSmall: true, disabled: idx === 0, onClick: function() { moveItem(idx, -1); } }, '↑'),
									el(Button, { isSmall: true, disabled: idx === items.length - 1, onClick: function() { moveItem(idx, 1); } }, '↓'),
									el(Button, { isDestructive: true, isSmall: true, onClick: function() { removeItem(idx); } }, 'Remove')
								)
							),
							el(TextControl, {
								label: 'Question',
								value: item.question,
								placeholder: 'e.g. How much does Gluco Reset cost?',
								onChange: function(val) { updateItem(idx, 'question', val); }
							}),
							el(TextareaControl, {
								label: 'Answer',
								value: item.answer,
								rows: 3,
								placeholder: 'Enter answer details...',
								onChange: function(val) { updateItem(idx, 'answer', val); }
							})
						);
					}),
					el(Button, { isSecondary: true, isFullWidth: true, onClick: addItem }, '+ Add Another Question')
				)
			);
		},
		save: function() {
			return null; // Server-side rendering
		}
	});

})(window.wp);
