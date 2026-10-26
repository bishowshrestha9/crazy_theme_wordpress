(function (blocks, element, components, data) {
    var el = element.createElement;
    var registerBlockType = blocks.registerBlockType;
    var SelectControl = components.SelectControl;
    var TextControl = components.TextControl;
    var TextareaControl = components.TextareaControl;
    var PanelBody = components.PanelBody;
    var Button = components.Button;
    var RichText = blocks.RichText || wp.blockEditor.RichText;
    var MediaUpload = wp.blockEditor.MediaUpload || wp.editor.MediaUpload;

    // Get products from localized script
    var productOptions = window.top10BlockData ? window.top10BlockData.products : [];

    // --- Helper: Product Selectors (up to 10) ---
    function renderProductSelectors(attributes, setAttributes, count) {
        var params = [];

        function updateProduct(index, value) {
            var update = {};
            update['product' + index] = parseInt(value, 10);
            setAttributes(update);
        }

        for (var i = 1; i <= count; i++) {
            params.push(el(SelectControl, {
                key: 'prod-select-' + i,
                label: 'Product #' + i,
                value: attributes['product' + i],
                options: productOptions,
                onChange: (function (index) {
                    return function (val) { updateProduct(index, val); };
                })(i)
            }));
        }
        return params;
    }

    // --- Section 1: Hero Block ---
    registerBlockType('top10/hero', {
        title: 'Section 1: Hero/Introduction',
        icon: 'cover-image',
        category: 'common',
        attributes: {
            title: { type: 'string', default: '' },
            subtitle: { type: 'string', default: '' },
            bgImageUrl: { type: 'string', default: '' },
        },
        edit: function (props) {
            var attributes = props.attributes;
            return el('div', { className: props.className, style: { padding: '20px', border: '1px solid #ddd' } },
                el('h3', {}, 'Section 1: Hero/Introduction'),
                el(TextControl, {
                    label: 'Hero Title',
                    value: attributes.title,
                    placeholder: 'Page Title',
                    onChange: function (val) { props.setAttributes({ title: val }); }
                }),
                el(TextareaControl, {
                    label: 'Hero Subtitle',
                    value: attributes.subtitle,
                    onChange: function (val) { props.setAttributes({ subtitle: val }); }
                }),
                el(TextControl, {
                    label: 'Background Image URL',
                    value: attributes.bgImageUrl,
                    onChange: function (val) { props.setAttributes({ bgImageUrl: val }); }
                })
            );
        },
        save: function () { return null; }
    });

    // --- Section 3: Top 5 Choices (Manual Entry) ---
    registerBlockType('top10/top-picks', {
        title: 'Section 3: Top 5 Choices',
        icon: 'star-filled',
        category: 'common',
        description: 'Manually add your top choices with images, ratings, and links.',
        attributes: {
            titlePrefix: { type: 'string', default: 'Top Choices:' },
            manualProducts: { type: 'string', default: '[]' }, // JSON
        },
        edit: function (props) {
            var products = [];
            try { products = JSON.parse(props.attributes.manualProducts); } catch (e) { }
            if (!Array.isArray(products)) products = [];

            function updateProducts(newProds) {
                props.setAttributes({ manualProducts: JSON.stringify(newProds) });
            }

            function addProduct() {
                updateProducts(products.concat([{
                    name: '',
                    price: '',
                    rating: '',
                    badge: '',
                    imageUrl: '',
                    imageId: 0,
                    affiliateLink: '',
                    reviewLink: '',
                    bestFor: ''
                }]));
            }

            function removeProduct(index) {
                var newProds = products.slice();
                newProds.splice(index, 1);
                updateProducts(newProds);
            }

            function updateProduct(index, key, val) {
                var newProds = products.slice();
                newProds[index][key] = val;
                updateProducts(newProds);
            }

            return el('div', { className: props.className, style: { padding: '20px', border: '1px solid #ddd' } },
                el('h3', {}, 'Section 3: Top 5 Choices (Manual)'),
                el(TextControl, {
                    label: 'Section Title',
                    value: props.attributes.titlePrefix,
                    onChange: function (val) { props.setAttributes({ titlePrefix: val }); }
                }),
                el('div', { style: { marginBottom: '15px' } },
                    products.map(function (prod, index) {
                        return el('div', { key: index, style: { border: '1px solid #999', padding: '15px', marginBottom: '15px', background: '#fff', borderRadius: '4px' } },
                            el('div', { style: { display: 'flex', justifyContent: 'space-between', marginBottom: '10px', paddingBottom: '5px', borderBottom: '1px solid #eee' } },
                                el('strong', {}, 'Choice #' + (index + 1)),
                                el(Button, { isDestructive: true, isSmall: true, onClick: function () { removeProduct(index); } }, 'Remove Product')
                            ),

                            // Image Upload
                            el('div', { style: { marginBottom: '15px', border: '1px dashed #ccc', padding: '10px', textAlign: 'center' } },
                                el('label', { style: { display: 'block', marginBottom: '5px', fontWeight: 'bold' } }, 'Product Image'),
                                el(MediaUpload, {
                                    onSelect: function (media) {
                                        var newProds = products.slice();
                                        newProds[index].imageUrl = (media.sizes && media.sizes.medium) ? media.sizes.medium.url : media.url;
                                        newProds[index].imageId = media.id;
                                        updateProducts(newProds);
                                    },
                                    allowedTypes: ['image'],
                                    value: prod.imageId,
                                    render: function (obj) {
                                        return el('div', {},
                                            prod.imageUrl ? el('img', { src: prod.imageUrl, style: { maxWidth: '100px', display: 'block', margin: '0 auto 10px auto' } }) : null,
                                            el(Button, { isSecondary: true, onClick: obj.open }, prod.imageUrl ? 'Change Image' : 'Upload Image')
                                        );
                                    }
                                })
                            ),

                            // Fields
                            el(TextControl, { label: 'Product Name', value: prod.name, onChange: function (v) { updateProduct(index, 'name', v); } }),
                            el(TextControl, { label: 'Badge Text (e.g. Best Overall)', value: prod.badge, onChange: function (v) { updateProduct(index, 'badge', v); } }),
                            el('div', { style: { display: 'flex', gap: '15px' } },
                                el('div', { style: { flex: 1 } },
                                    el(TextControl, { label: 'Price', value: prod.price, onChange: function (v) { updateProduct(index, 'price', v); } })
                                ),
                                el('div', { style: { flex: 1 } },
                                    el(TextControl, { label: 'Rating (0.0 - 10.0)', value: prod.rating, type: 'number', step: '0.1', onChange: function (v) { updateProduct(index, 'rating', v); } })
                                )
                            ),
                            el('div', { style: { display: 'flex', gap: '15px' } },
                                el('div', { style: { flex: 1 } },
                                    el(TextControl, { label: 'Affiliate/Buy Link', value: prod.affiliateLink, onChange: function (v) { updateProduct(index, 'affiliateLink', v); } })
                                ),
                                el('div', { style: { flex: 1 } },
                                    el(TextControl, { label: 'Read Review Link', value: prod.reviewLink, onChange: function (v) { updateProduct(index, 'reviewLink', v); } })
                                )
                            ),
                            el(TextareaControl, { label: 'Best For / Description', value: prod.bestFor, onChange: function (v) { updateProduct(index, 'bestFor', v); } })
                        );
                    })
                ),
                el(Button, { isPrimary: true, onClick: addProduct }, 'Add Product Card')
            );
        },
        save: function () { return null; }
    });

    // --- Section 4: Research Docs ---
    registerBlockType('top10/research-docs', {
        title: 'Section 4: Research Documents',
        icon: 'media-document',
        category: 'common',
        attributes: {
            header: { type: 'string', default: 'Download our Research Documents' },
            intro: { type: 'string', default: '' },
            bullets: { type: 'string', default: '' },
            excelUrl: { type: 'string', default: '' },
            pdfUrl: { type: 'string', default: '' },
            excelText: { type: 'string', default: 'Download the Full Research Workbook (Excel)' },
            pdfText: { type: 'string', default: 'Download the Detailed Research Document (PDF)' }
        },
        edit: function (props) {
            var atts = props.attributes;
            return el('div', { className: props.className, style: { padding: '20px', border: '1px solid #ddd' } },
                el('h3', {}, 'Section 4: Research Documents'),
                el(TextControl, { label: 'Header', value: atts.header, onChange: function (val) { props.setAttributes({ header: val }); } }),
                el(TextareaControl, { label: 'Intro', value: atts.intro, onChange: function (val) { props.setAttributes({ intro: val }); } }),
                el(TextareaControl, { label: 'Bullet Points (one per line)', value: atts.bullets, rows: 4, onChange: function (val) { props.setAttributes({ bullets: val }); } }),
                el(TextControl, { label: 'Excel URL', value: atts.excelUrl, onChange: function (val) { props.setAttributes({ excelUrl: val }); } }),
                el(TextControl, { label: 'PDF URL', value: atts.pdfUrl, onChange: function (val) { props.setAttributes({ pdfUrl: val }); } })
            );
        },
        save: function () { return null; }
    });

    // --- Section 5: Comparison Table (Copy/Paste) ---
    registerBlockType('top10/comparison-table', {
        title: 'Section 5: Comparison Table',
        icon: 'editor-table',
        category: 'common',
        description: 'Paste your table from Word/Excel/Docs here.',
        attributes: {
            tableHtml: { type: 'string', default: '' },
        },
        edit: function (props) {
            return el('div', { className: props.className, style: { padding: '20px', border: '1px solid #ddd' } },
                el('h3', {}, 'Section 5: Comparison Table'),
                el('p', { style: { fontStyle: 'italic', marginBottom: '10px' } }, 'Copy your table from Excel/Word and paste it below:'),
                el(RichText, {
                    tagName: 'div',
                    className: 'comparison-paste-area',
                    style: { border: '2px dashed #ccc', padding: '10px', minHeight: '150px', background: '#f9f9f9' },
                    value: props.attributes.tableHtml,
                    onChange: function (content) {
                        props.setAttributes({ tableHtml: content });
                    },
                    multiline: 'p',
                    placeholder: 'Paste table here...'
                })
            );
        },
        save: function () { return null; }
    });

    // --- Section 6: FAQs ---
    registerBlockType('top10/faqs', {
        title: 'Section 6: FAQs',
        icon: 'editor-help',
        category: 'common',
        attributes: {
            items: { type: 'string', default: '[]' }
        },
        edit: function (props) {
            var items = [];
            try { items = JSON.parse(props.attributes.items); } catch (e) { }
            if (!Array.isArray(items)) items = [];

            function updateItems(newItems) {
                props.setAttributes({ items: JSON.stringify(newItems) });
            }

            function addItem() {
                updateItems(items.concat([{ question: '', answer: '' }]));
            }

            function updateItem(index, key, value) {
                var newItems = items.slice();
                newItems[index][key] = value;
                updateItems(newItems);
            }

            function removeItem(index) {
                var newItems = items.slice();
                newItems.splice(index, 1);
                updateItems(newItems);
            }

            var itemElements = items.map(function (item, index) {
                return el('div', { key: index, style: { marginBottom: '15px', padding: '10px', border: '1px solid #eee', background: '#fff' } },
                    el('div', { style: { display: 'flex', justifyContent: 'space-between' } },
                        el('strong', {}, 'FAQ #' + (index + 1)),
                        el(Button, { isDestructive: true, isSmall: true, onClick: function () { removeItem(index); } }, 'Remove')
                    ),
                    el(TextControl, {
                        label: 'Question',
                        value: item.question,
                        onChange: function (val) { updateItem(index, 'question', val); }
                    }),
                    el(TextareaControl, {
                        label: 'Answer',
                        value: item.answer,
                        onChange: function (val) { updateItem(index, 'answer', val); }
                    })
                );
            });

            return el('div', { className: props.className, style: { padding: '20px', border: '1px solid #ddd', background: '#f9f9f9' } },
                el('h3', {}, 'Section 6: FAQs'),
                itemElements,
                el(Button, { isPrimary: true, onClick: addItem }, 'Add FAQ')
            );
        },
        save: function () { return null; }
    });

    // --- Section 7: Source Docs ---
    registerBlockType('top10/source-docs', {
        title: 'Section 7: Source Docs & Disclosure',
        icon: 'analytics',
        category: 'common',
        attributes: {
            intro: { type: 'string', default: '' },
            criteria: { type: 'string', default: '[]' },
            sources: { type: 'string', default: '' },
        },
        edit: function (props) {
            var criteria = [];
            try { criteria = JSON.parse(props.attributes.criteria); } catch (e) { }
            if (!Array.isArray(criteria)) criteria = [];

            function updateCriteria(newVal) {
                props.setAttributes({ criteria: JSON.stringify(newVal) });
            }

            function addRow() {
                updateCriteria(criteria.concat([{ criteria: '', weight: '', method: '' }]));
            }

            function updateRow(index, key, value) {
                var newC = criteria.slice();
                newC[index][key] = value;
                updateCriteria(newC);
            }
            function removeRow(index) {
                var newC = criteria.slice();
                newC.splice(index, 1);
                updateCriteria(newC);
            }

            return el('div', { className: props.className, style: { padding: '20px', border: '1px solid #ddd' } },
                el('h3', {}, 'Section 7: Sources & Disclosure'),
                el(TextareaControl, { label: 'Intro Text', value: props.attributes.intro, onChange: function (val) { props.setAttributes({ intro: val }); } }),
                el('h4', {}, 'Criteria Table'),
                criteria.map(function (row, i) {
                    return el('div', { key: i, style: { display: 'flex', gap: '5px', marginBottom: '5px' } },
                        el(TextControl, { placeholder: 'Criteria', value: row.criteria, onChange: function (v) { updateRow(i, 'criteria', v); } }),
                        el(TextControl, { placeholder: 'Weight', value: row.weight, onChange: function (v) { updateRow(i, 'weight', v); } }),
                        el(TextControl, { placeholder: 'Test Method', value: row.method, onChange: function (v) { updateRow(i, 'method', v); } }),
                        el(Button, { isDestructive: true, isSmall: true, onClick: function () { removeRow(i); } }, 'X')
                    );
                }),
                el(Button, { isPrimary: true, onClick: addRow, style: { marginBottom: '15px' } }, 'Add Criteria Row'),
                el(TextareaControl, { label: 'Data Sources (Text)', value: props.attributes.sources, onChange: function (val) { props.setAttributes({ sources: val }); } })
            );
        },
        save: function () { return null; }
    });

    // --- Detailed Attributes (Extra Section) ---
    // Mapping this to "Section 2" conceptually if user wants, but keeping separate name for clarity
    registerBlockType('top10/detailed-analysis', {
        title: 'Detailed Analysis List',
        icon: 'list-view',
        category: 'common',
        attributes: {
            product1: { type: 'integer', default: 0 },
            product2: { type: 'integer', default: 0 },
            product3: { type: 'integer', default: 0 },
            product4: { type: 'integer', default: 0 },
            product5: { type: 'integer', default: 0 },
            product6: { type: 'integer', default: 0 },
            product7: { type: 'integer', default: 0 },
            product8: { type: 'integer', default: 0 },
            product9: { type: 'integer', default: 0 },
            product10: { type: 'integer', default: 0 },
        },
        edit: function (props) {
            return el('div', { className: props.className, style: { padding: '20px', border: '1px solid #ddd' } },
                el('h3', {}, 'Detailed Analysis List'),
                renderProductSelectors(props.attributes, props.setAttributes, 10)
            );
        },
        save: function () { return null; }
    });

    // --- Section 8: Disclaimer ---
    registerBlockType('top10/disclaimer', {
        title: 'Section 8: Disclaimer',
        icon: 'info',
        category: 'common',
        attributes: {
            text: { type: 'string', default: '' }
        },
        edit: function (props) {
            return el('div', { className: props.className, style: { padding: '20px', border: '1px solid #ddd' } },
                el('h3', {}, 'Section 8: Disclaimer'),
                el(RichText, {
                    tagName: 'div',
                    value: props.attributes.text,
                    onChange: function (val) { props.setAttributes({ text: val }); },
                    placeholder: 'Enter disclaimer text...'
                })
            );
        },
        save: function () { return null; }
    });

    // --- Buying Guide (Extra) ---
    registerBlockType('top10/buying-guide', {
        title: 'Buying Guide',
        icon: 'book',
        category: 'common',
        attributes: {
            items: { type: 'string', default: '[]' }
        },
        edit: function (props) {
            // ... reusing Repeater logic
            var items = [];
            try { items = JSON.parse(props.attributes.items); } catch (e) { }
            if (!Array.isArray(items)) items = [];

            function updateItems(newItems) {
                props.setAttributes({ items: JSON.stringify(newItems) });
            }
            function addItem() { updateItems(items.concat([{ title: '', description: '' }])); }
            function removeItem(i) {
                var n = items.slice(); n.splice(i, 1); updateItems(n);
            }
            function updateItem(i, k, v) {
                var n = items.slice(); n[i][k] = v; updateItems(n);
            }

            return el('div', { className: props.className, style: { padding: '20px', border: '1px solid #ddd', background: '#f9f9f9' } },
                el('h3', {}, 'Buying Guide'),
                items.map(function (item, index) {
                    return el('div', { key: index, style: { marginBottom: '10px', padding: '10px', background: '#fff', border: '1px solid #eee' } },
                        el('div', { style: { display: 'flex', justifyContent: 'space-between' } },
                            el('strong', {}, 'Item #' + (index + 1)),
                            el(Button, { isDestructive: true, isSmall: true, onClick: function () { removeItem(index); } }, 'Remove')
                        ),
                        el(TextControl, { label: 'Title', value: item.title, onChange: function (v) { updateItem(index, 'title', v); } }),
                        el(TextareaControl, { label: 'Description', value: item.description, onChange: function (v) { updateItem(index, 'description', v); } })
                    );
                }),
                el(Button, { isPrimary: true, onClick: addItem }, 'Add Item')
            );
        },
        save: function () { return null; }
    });

})(window.wp.blocks, window.wp.element, window.wp.components, window.wp.data);
