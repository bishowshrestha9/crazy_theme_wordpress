// Supplement Page Builder - Gutenberg Blocks
(function(blocks, element, components, editor, blockEditor) {
    var el = element.createElement;
    var Fragment = element.Fragment;
    var RichText = blockEditor.RichText;
    var InspectorControls = blockEditor.InspectorControls;
    var TextControl = components.TextControl;
    var TextareaControl = components.TextareaControl;
    var PanelBody = components.PanelBody;
    var Button = components.Button;

    // 1. Top Choices Block
    blocks.registerBlockType('supplement-blocks/top-choices', {
        title: 'Top Choices',
        icon: 'star-filled',
        category: 'supplement-blocks',
        keywords: ['top', 'choices', 'products'],
        attributes: {
            sectionTitle: {
                type: 'string',
                default: 'Top Choices: Best Supplements for Memory and Focus'
            },
            items: {
                type: 'array',
                default: [
                    { badge: 'Best Overall', name: '', price: '', rating: '', pros: '', cons: '', reviewLink: '' }
                ]
            }
        },
        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            function updateItem(index, field, value) {
                var newItems = [...attributes.items];
                newItems[index][field] = value;
                setAttributes({ items: newItems });
            }

            function addItem() {
                var newItems = [...attributes.items, { badge: '', name: '', price: '', rating: '', pros: '', cons: '', reviewLink: '' }];
                setAttributes({ items: newItems });
            }

            function removeItem(index) {
                var newItems = attributes.items.filter(function(item, i) { return i !== index; });
                setAttributes({ items: newItems });
            }

            return el(Fragment, {},
                el('div', { 
                    className: 'spb-block-container',
                    style: { 
                        padding: '20px', 
                        background: '#fff', 
                        border: '1px solid #ddd',
                        borderRadius: '8px',
                        marginBottom: '20px'
                    }
                },
                    el('h3', { style: { margin: '0 0 15px 0', fontSize: '18px', fontWeight: '600' } }, '🏆 Top Choices'),
                    
                    el('div', { style: { marginBottom: '20px' } },
                        el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Section Title'),
                        el('input', {
                            type: 'text',
                            value: attributes.sectionTitle,
                            onChange: function(e) { setAttributes({ sectionTitle: e.target.value }); },
                            placeholder: 'Top Choices: Best Supplements for Memory and Focus',
                            style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                        })
                    ),
                    
                    attributes.items.map(function(item, index) {
                        return el('div', {
                            key: index,
                            style: {
                                marginBottom: '20px',
                                padding: '15px',
                                background: '#f9fafb',
                                border: '1px solid #e5e7eb',
                                borderRadius: '6px'
                            }
                        },
                            el('div', { style: { display: 'flex', justifyContent: 'space-between', marginBottom: '10px' } },
                                el('strong', {}, 'Product ' + (index + 1)),
                                el(Button, {
                                    isDestructive: true,
                                    isSmall: true,
                                    onClick: function() { removeItem(index); }
                                }, 'Remove')
                            ),
                            
                            el('div', { style: { marginBottom: '10px' } },
                                el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Badge/Category'),
                                el('input', {
                                    type: 'text',
                                    value: item.badge,
                                    onChange: function(e) { updateItem(index, 'badge', e.target.value); },
                                    placeholder: 'Best Overall',
                                    style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                                })
                            ),
                            
                            el('div', { style: { marginBottom: '10px' } },
                                el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Product Name'),
                                el('input', {
                                    type: 'text',
                                    value: item.name,
                                    onChange: function(e) { updateItem(index, 'name', e.target.value); },
                                    placeholder: 'Memory Lift',
                                    style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                                })
                            ),
                            
                            el('div', { style: { display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: '10px' } },
                                el('div', {},
                                    el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Price Range'),
                                    el('input', {
                                        type: 'text',
                                        value: item.price,
                                        onChange: function(e) { updateItem(index, 'price', e.target.value); },
                                        placeholder: '$49-$39',
                                        style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                                    })
                                ),
                                el('div', {},
                                    el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Rating'),
                                    el('input', {
                                        type: 'text',
                                        value: item.rating,
                                        onChange: function(e) { updateItem(index, 'rating', e.target.value); },
                                        placeholder: '9.4/10',
                                        style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                                    })
                                ),
                                el('div', {},
                                    el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Review Link URL'),
                                    el('input', {
                                        type: 'url',
                                        value: item.reviewLink,
                                        onChange: function(e) { updateItem(index, 'reviewLink', e.target.value); },
                                        placeholder: '#review',
                                        style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                                    })
                                )
                            ),
                            
                            el('div', { style: { marginBottom: '10px' } },
                                el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Pros (comma-separated)'),
                                el('textarea', {
                                    value: item.pros,
                                    onChange: function(e) { updateItem(index, 'pros', e.target.value); },
                                    placeholder: 'Clinically proven ingredients, No caffeine crash, Money-back guarantee',
                                    rows: 2,
                                    style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                                })
                            ),
                            
                            el('div', { style: { marginBottom: '10px' } },
                                el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Cons (comma-separated)'),
                                el('textarea', {
                                    value: item.cons,
                                    onChange: function(e) { updateItem(index, 'cons', e.target.value); },
                                    placeholder: 'Only available online',
                                    rows: 2,
                                    style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                                })
                            )
                        );
                    }),
                    
                    el(Button, {
                        isPrimary: true,
                        onClick: addItem,
                        style: { marginTop: '10px' }
                    }, '+ Add Product')
                )
            );
        },
        save: function() {
            return null;
        }
    });

    // 2. Research Docs Block
    blocks.registerBlockType('supplement-blocks/research-docs', {
        title: 'Research Documents',
        icon: 'media-spreadsheet',
        category: 'supplement-blocks',
        keywords: ['research', 'download', 'documents'],
        attributes: {
            intro: { type: 'string', default: "To maintain transparency, we've made our full research workbook and supporting analysis publicly available." },
            excelUrl: { type: 'string', default: '' },
            excelText: { type: 'string', default: 'Download the Full Research Workbook (Excel)' },
            pdfUrl: { type: 'string', default: '' },
            pdfText: { type: 'string', default: 'Download the Detailed Research Document (PDF)' }
        },
        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return el('div', { 
                className: 'spb-block-container',
                style: { 
                    padding: '20px', 
                    background: '#fff', 
                    border: '1px solid #ddd',
                    borderRadius: '8px',
                    marginBottom: '20px'
                }
            },
                el('h3', { style: { margin: '0 0 20px 0', fontSize: '18px', fontWeight: '600' } }, '📊 Downloadable Research Documents'),
                
                el('div', { style: { marginBottom: '15px' } },
                    el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Introduction Text'),
                    el('textarea', {
                        value: attributes.intro,
                        onChange: function(e) { setAttributes({ intro: e.target.value }); },
                        rows: 3,
                        style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                    })
                ),
                
                el('div', { style: { marginBottom: '15px' } },
                    el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Excel Document URL'),
                    el('input', {
                        type: 'url',
                        value: attributes.excelUrl,
                        onChange: function(e) { setAttributes({ excelUrl: e.target.value }); },
                        placeholder: 'https://example.com/research.xlsx',
                        style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                    })
                ),
                
                el('div', { style: { marginBottom: '15px' } },
                    el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Excel Button Text'),
                    el('input', {
                        type: 'text',
                        value: attributes.excelText,
                        onChange: function(e) { setAttributes({ excelText: e.target.value }); },
                        style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                    })
                ),
                
                el('div', { style: { marginBottom: '15px' } },
                    el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'PDF Document URL'),
                    el('input', {
                        type: 'url',
                        value: attributes.pdfUrl,
                        onChange: function(e) { setAttributes({ pdfUrl: e.target.value }); },
                        placeholder: 'https://example.com/research.pdf',
                        style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                    })
                ),
                
                el('div', { style: { marginBottom: '15px' } },
                    el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'PDF Button Text'),
                    el('input', {
                        type: 'text',
                        value: attributes.pdfText,
                        onChange: function(e) { setAttributes({ pdfText: e.target.value }); },
                        style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                    })
                )
            );
        },
        save: function() {
            return null;
        }
    });

    // 3. Comparison Table Block
    blocks.registerBlockType('supplement-blocks/comparison-table', {
        title: 'Comparison Table',
        icon: 'editor-table',
        category: 'supplement-blocks',
        keywords: ['comparison', 'table', 'paste'],
        attributes: {
            content: { type: 'string', default: '' }
        },
        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return el('div', { 
                className: 'spb-block-container',
                style: { 
                    padding: '20px', 
                    background: '#fff', 
                    border: '1px solid #ddd',
                    borderRadius: '8px',
                    marginBottom: '20px'
                }
            },
                el('h3', { style: { margin: '0 0 15px 0', fontSize: '18px', fontWeight: '600' } }, '📋 Comparison Table (Copy/Paste)'),
                el('div', { style: { marginBottom: '15px', padding: '12px', background: '#FEF3C7', border: '1px solid #F59E0B', borderRadius: '6px' } },
                    el('strong', { style: { display: 'block', marginBottom: '8px', color: '#92400E', fontSize: '13px' } }, '💡 How to paste tables:'),
                    el('ol', { style: { margin: '0', paddingLeft: '20px', fontSize: '12px', color: '#78350F', lineHeight: '1.6' } },
                        el('li', {}, 'From Excel: Copy cells → Paste here (preserves tabs)'),
                        el('li', {}, 'From ChatGPT: Copy table with pipes (|) → Paste here'),
                        el('li', {}, 'From Word: Copy table → Paste to Excel → Copy from Excel → Paste here'),
                        el('li', {}, 'Or use: tablesgenerator.com → HTML Table → Copy generated code → Paste here')
                    )
                ),
                el('p', { style: { margin: '0 0 10px 0', fontSize: '13px', color: '#666' } }, 'Supports: Word tables, Excel, ChatGPT (pipe format |), HTML tables'),
                el('textarea', {
                    value: attributes.content,
                    onChange: function(e) { setAttributes({ content: e.target.value }); },
                    placeholder: '<table>\n  <tr>\n    <th>Product</th>\n    <th>Rating</th>\n  </tr>\n  <tr>\n    <td>Product 1</td>\n    <td>9.5</td>\n  </tr>\n</table>',
                    rows: 15,
                    style: { 
                        width: '100%', 
                        padding: '10px', 
                        fontFamily: 'monospace', 
                        fontSize: '12px',
                        border: '1px solid #ddd', 
                        borderRadius: '4px' 
                    }
                }),
                el('p', { style: { margin: '10px 0 0 0', fontSize: '12px', color: '#059669', fontWeight: '500' } }, '✓ Table will be automatically formatted and styled on the frontend')
            );
        },
        save: function() {
            return null;
        }
    });

    // 4. FAQ Block
    blocks.registerBlockType('supplement-blocks/faq', {
        title: 'FAQ',
        icon: 'editor-help',
        category: 'supplement-blocks',
        keywords: ['faq', 'questions', 'answers'],
        attributes: {
            items: {
                type: 'array',
                default: [{ question: '', answer: '' }]
            }
        },
        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            function updateItem(index, field, value) {
                var newItems = [...attributes.items];
                newItems[index][field] = value;
                setAttributes({ items: newItems });
            }

            function addItem() {
                var newItems = [...attributes.items, { question: '', answer: '' }];
                setAttributes({ items: newItems });
            }

            function removeItem(index) {
                var newItems = attributes.items.filter(function(item, i) { return i !== index; });
                setAttributes({ items: newItems });
            }

            return el('div', { 
                className: 'spb-block-container',
                style: { 
                    padding: '20px', 
                    background: '#fff', 
                    border: '1px solid #ddd',
                    borderRadius: '8px',
                    marginBottom: '20px'
                }
            },
                el('h3', { style: { margin: '0 0 20px 0', fontSize: '18px', fontWeight: '600' } }, '❓ FAQ'),
                
                attributes.items.map(function(item, index) {
                    return el('div', {
                        key: index,
                        style: {
                            marginBottom: '20px',
                            padding: '15px',
                            background: '#f9fafb',
                            border: '1px solid #e5e7eb',
                            borderRadius: '6px'
                        }
                    },
                        el('div', { style: { display: 'flex', justifyContent: 'space-between', marginBottom: '10px' } },
                            el('strong', {}, 'FAQ ' + (index + 1)),
                            el(Button, {
                                isDestructive: true,
                                isSmall: true,
                                onClick: function() { removeItem(index); }
                            }, 'Remove')
                        ),
                        
                        el('div', { style: { marginBottom: '10px' } },
                            el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Question'),
                            el('input', {
                                type: 'text',
                                value: item.question,
                                onChange: function(e) { updateItem(index, 'question', e.target.value); },
                                placeholder: 'Enter question',
                                style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                            })
                        ),
                        
                        el('div', {},
                            el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Answer'),
                            el('textarea', {
                                value: item.answer,
                                onChange: function(e) { updateItem(index, 'answer', e.target.value); },
                                placeholder: 'Enter answer',
                                rows: 4,
                                style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                            })
                        )
                    );
                }),
                
                el(Button, {
                    isPrimary: true,
                    onClick: addItem,
                    style: { marginTop: '10px' }
                }, '+ Add FAQ')
            );
        },
        save: function() {
            return null;
        }
    });

    // 5. Source Documents Block
    blocks.registerBlockType('supplement-blocks/source-docs', {
        title: 'Source Documents',
        icon: 'media-document',
        category: 'supplement-blocks',
        keywords: ['source', 'documents', 'disclosure'],
        attributes: {
            intro: { type: 'string', default: 'As part of our commitment to evidence-based publishing, SupplementMag provides full access to the internal research materials used to create this ranking.' },
            bullets: { type: 'string', default: 'A consolidated comparison sheet ranking all reviewed products\nA weighted scoring model across safety, effectiveness, ingredient quality\nProduct-specific research sheets with detailed analysis\nTransparent formulas and charts supporting all final ratings' },
            excelUrl: { type: 'string', default: '' },
            excelText: { type: 'string', default: 'Download the Full Research Workbook (Excel)' },
            pdfUrl: { type: 'string', default: '' },
            pdfText: { type: 'string', default: 'Download the Detailed Research Document (PDF)' }
        },
        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return el('div', { 
                className: 'spb-block-container',
                style: { 
                    padding: '20px', 
                    background: '#fff', 
                    border: '1px solid #ddd',
                    borderRadius: '8px',
                    marginBottom: '20px'
                }
            },
                el('h3', { style: { margin: '0 0 20px 0', fontSize: '18px', fontWeight: '600' } }, '📄 Source Documents & Disclosure'),
                
                el('div', { style: { marginBottom: '15px' } },
                    el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Introduction Text'),
                    el('textarea', {
                        value: attributes.intro,
                        onChange: function(e) { setAttributes({ intro: e.target.value }); },
                        rows: 3,
                        style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                    })
                ),
                
                el('div', { style: { marginBottom: '15px' } },
                    el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Bullet Points'),
                    el('textarea', {
                        value: attributes.bullets,
                        onChange: function(e) { setAttributes({ bullets: e.target.value }); },
                        rows: 5,
                        placeholder: 'One bullet point per line',
                        style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                    }),
                    el('p', { style: { margin: '5px 0 0 0', fontSize: '12px', color: '#666' } }, 'One bullet point per line')
                ),
                
                el('div', { style: { marginBottom: '15px' } },
                    el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Excel Document URL'),
                    el('input', {
                        type: 'url',
                        value: attributes.excelUrl,
                        onChange: function(e) { setAttributes({ excelUrl: e.target.value }); },
                        placeholder: 'https://example.com/research.xlsx',
                        style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                    })
                ),
                
                el('div', { style: { marginBottom: '15px' } },
                    el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'Excel Button Text'),
                    el('input', {
                        type: 'text',
                        value: attributes.excelText,
                        onChange: function(e) { setAttributes({ excelText: e.target.value }); },
                        style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                    })
                ),
                
                el('div', { style: { marginBottom: '15px' } },
                    el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'PDF Document URL'),
                    el('input', {
                        type: 'url',
                        value: attributes.pdfUrl,
                        onChange: function(e) { setAttributes({ pdfUrl: e.target.value }); },
                        placeholder: 'https://example.com/research.pdf',
                        style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                    })
                ),
                
                el('div', { style: { marginBottom: '15px' } },
                    el('label', { style: { display: 'block', marginBottom: '5px', fontSize: '13px', fontWeight: '500' } }, 'PDF Button Text'),
                    el('input', {
                        type: 'text',
                        value: attributes.pdfText,
                        onChange: function(e) { setAttributes({ pdfText: e.target.value }); },
                        style: { width: '100%', padding: '8px', border: '1px solid #ddd', borderRadius: '4px' }
                    })
                )
            );
        },
        save: function() {
            return null;
        }
    });

    // 6. Disclaimer Block
    blocks.registerBlockType('supplement-blocks/disclaimer', {
        title: 'Disclaimer',
        icon: 'warning',
        category: 'supplement-blocks',
        keywords: ['disclaimer', 'warning', 'legal'],
        attributes: {
            content: { type: 'string', default: '' }
        },
        edit: function(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            return el('div', { 
                className: 'spb-block-container',
                style: { 
                    padding: '20px', 
                    background: '#fef3c7', 
                    border: '1px solid #f59e0b',
                    borderRadius: '8px',
                    marginBottom: '20px'
                }
            },
                el('h3', { style: { margin: '0 0 15px 0', fontSize: '18px', fontWeight: '600' } }, '⚠️ Disclaimer'),
                el(RichText, {
                    tagName: 'div',
                    value: attributes.content,
                    onChange: function(value) { setAttributes({ content: value }); },
                    placeholder: 'Enter disclaimer text here...',
                    style: { minHeight: '100px' }
                })
            );
        },
        save: function() {
            return null;
        }
    });

})(window.wp.blocks, window.wp.element, window.wp.components, window.wp.editor, window.wp.blockEditor);
