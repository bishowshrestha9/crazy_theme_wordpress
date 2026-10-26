/**
 * Product Card Gutenberg Block
 * All fields editable in sidebar (Inspector Controls)
 */

const { registerBlockType } = wp.blocks;
const { InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor || wp.editor;
const { PanelBody, TextControl, TextareaControl, SelectControl, Button } = wp.components;
const { __ } = wp.i18n;
const { createElement: el, Fragment } = wp.element;

registerBlockType('product-card-plugin/product-card', {
    title: __('Product Card', 'product-card-plugin'),
    icon: 'products',
    category: 'common',
    attributes: {
        status: {
            type: 'string',
            default: 'legit'
        },
        productName: {
            type: 'string',
            default: 'Gutoptim'
        },
        productCategory: {
            type: 'string',
            default: 'Supplement'
        },
        imageUrl: {
            type: 'string',
            default: 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?w=800'
        },
        imageId: {
            type: 'number',
            default: 0
        },
        userExperience: {
            type: 'string',
            default: 'Fewer night bathroom trips, stronger urine flow, and better sleep quality'
        },
        expertReview1: {
            type: 'string',
            default: 'Triple Therapy combines heat, massage, and compression to improve circulation, ease soreness, and support daily recovery over time.'
        },
        expertReview2: {
            type: 'string',
            default: 'Customizable settings with five heat levels and three massage modes tailor relief intensity to your comfort and routine.'
        },
        expertReview3: {
            type: 'string',
            default: 'Portable rechargeable design makes consistent use easy at home, at work, or while traveling between daily appointments.'
        },
        verdict: {
            type: 'string',
            default: 'A reliable pick with consistent results, practical value, and noticeable improvements for daily relief.'
        },
        rating: {
            type: 'string',
            default: '4.9'
        },
        readReviewsUrl: {
            type: 'string',
            default: '#'
        },
        price: {
            type: 'string',
            default: '69'
        },
        originalPrice: {
            type: 'string',
            default: '99'
        },
        currency: {
            type: 'string',
            default: '$'
        },
        ctaLabel: {
            type: 'string',
            default: 'Buy Now'
        },
        ctaSubText: {
            type: 'string',
            default: ''
        },
        ctaUrl: {
            type: 'string',
            default: '#'
        },
        buyLaterLabel: {
            type: 'string',
            default: 'Buy Later'
        },
        headerColor: {
            type: 'string',
            default: '#000000'
        },
        cardTheme: {
            type: 'string',
            default: 'default'
        }
    },

    edit: function (props) {
        const { attributes, setAttributes } = props;
        const {
            status,
            productName,
            productCategory,
            imageUrl,
            imageId,
            userExperience,
            expertReview1,
            expertReview2,
            expertReview3,
            verdict,
            rating,
            readReviewsUrl,
            price,
            originalPrice,
            currency,
            ctaLabel,
            buyLaterLabel,
            ctaSubText,
            ctaUrl,
            headerColor,
            cardTheme
        } = attributes;

        return el(
            Fragment,
            {},
            [
                // Inspector Controls (Sidebar)
                el(
                    InspectorControls,
                    { key: 'inspector' },
                    [
                        // Product Settings Panel
                        el(
                            PanelBody,
                            {
                                title: __('Product Settings', 'product-card-plugin'),
                                initialOpen: true,
                                key: 'product-settings'
                            },
                            [
                                el(SelectControl, {
                                    key: 'status',
                                    label: __('Product Status', 'product-card-plugin'),
                                    value: status,
                                    options: [
                                        { label: 'Legit (Green)', value: 'legit' },
                                        { label: 'Scam (Red)', value: 'scam' }
                                    ],
                                    onChange: (value) => setAttributes({ status: value })
                                }),
                                el(SelectControl, {
                                    key: 'cardTheme',
                                    label: __('Color Theme Preset', 'product-card-plugin'),
                                    value: cardTheme || 'default',
                                    options: [
                                        { label: 'Default (Status Theme)', value: 'default' },
                                        { label: 'Emerald Mint (Green)', value: 'emerald' },
                                        { label: 'Crimson Flame (Red)', value: 'crimson' },
                                        { label: 'Sapphire Ocean (Blue)', value: 'sapphire' },
                                        { label: 'Amethyst Violet (Purple)', value: 'violet' },
                                        { label: 'Sunset Amber (Gold)', value: 'amber' },
                                        { label: 'Midnight Obsidian (Dark)', value: 'midnight' }
                                    ],
                                    onChange: (value) => setAttributes({ cardTheme: value })
                                }),
                                el(TextControl, {
                                    key: 'productName',
                                    label: __('Product Name', 'product-card-plugin'),
                                    value: productName,
                                    onChange: (value) => setAttributes({ productName: value })
                                }),
                                el(TextControl, {
                                    key: 'productCategory',
                                    label: __('Product Category', 'product-card-plugin'),
                                    value: productCategory,
                                    onChange: (value) => setAttributes({ productCategory: value })
                                })
                            ]
                        ),

                        // Product Image Panel
                        el(
                            PanelBody,
                            {
                                title: __('Product Image', 'product-card-plugin'),
                                initialOpen: false,
                                key: 'product-image'
                            },
                            [
                                el(MediaUploadCheck, { key: 'media-check' },
                                    el(MediaUpload, {
                                        key: 'media-upload',
                                        onSelect: (media) => setAttributes({
                                            imageUrl: media.url,
                                            imageId: media.id
                                        }),
                                        allowedTypes: ['image'],
                                        value: imageId,
                                        render: ({ open }) => el(
                                            Button,
                                            {
                                                onClick: open,
                                                isPrimary: !imageUrl,
                                                isSecondary: !!imageUrl
                                            },
                                            imageUrl ? __('Change Image', 'product-card-plugin') : __('Upload Image', 'product-card-plugin')
                                        )
                                    })
                                ),
                                imageUrl && el('img', {
                                    key: 'preview',
                                    src: imageUrl,
                                    style: { maxWidth: '100%', marginTop: '10px' }
                                }),
                                imageUrl && el(Button, {
                                    key: 'remove',
                                    isDestructive: true,
                                    onClick: () => setAttributes({ imageUrl: '', imageId: 0 }),
                                    style: { marginTop: '10px' }
                                }, __('Remove Image', 'product-card-plugin'))
                            ]
                        ),

                        // Content Panel
                        el(
                            PanelBody,
                            {
                                title: __('Content', 'product-card-plugin'),
                                initialOpen: false,
                                key: 'content'
                            },
                            [
                                el(TextareaControl, {
                                    key: 'userExperience',
                                    label: __('User Experience (Max 95 chars)', 'product-card-plugin'),
                                    value: userExperience,
                                    help: (userExperience ? userExperience.length : 0) + '/95 characters',
                                    onChange: (value) => setAttributes({ userExperience: value.substr(0, 95) })
                                }),
                                el(TextareaControl, {
                                    key: 'expertReview1',
                                    label: __('Expert Reviews - Bullet 1 (Max 61 chars)', 'product-card-plugin'),
                                    value: expertReview1,
                                    help: (expertReview1 ? expertReview1.length : 0) + '/61 characters',
                                    maxLength: 61,
                                    onChange: (value) => setAttributes({ expertReview1: value.substr(0, 61) })
                                }),
                                el(TextareaControl, {
                                    key: 'expertReview2',
                                    label: __('Expert Reviews - Bullet 2 (Max 61 chars)', 'product-card-plugin'),
                                    value: expertReview2,
                                    help: (expertReview2 ? expertReview2.length : 0) + '/61 characters',
                                    maxLength: 61,
                                    onChange: (value) => setAttributes({ expertReview2: value.substr(0, 61) })
                                }),
                                el(TextareaControl, {
                                    key: 'expertReview3',
                                    label: __('Expert Reviews - Bullet 3 (Max 61 chars)', 'product-card-plugin'),
                                    value: expertReview3,
                                    help: (expertReview3 ? expertReview3.length : 0) + '/61 characters',
                                    maxLength: 61,
                                    onChange: (value) => setAttributes({ expertReview3: value.substr(0, 61) })
                                }),
                                el(TextareaControl, {
                                    key: 'verdict',
                                    label: __('Verdict (Max 70 chars)', 'product-card-plugin'),
                                    value: verdict,
                                    help: (verdict ? verdict.length : 0) + '/70 characters',
                                    onChange: (value) => setAttributes({ verdict: value.substr(0, 70) })
                                })
                            ]
                        ),

                        // Product Info Panel
                        el(
                            PanelBody,
                            {
                                title: __('Product Information', 'product-card-plugin'),
                                initialOpen: false,
                                key: 'product-info'
                            },
                            [
                                el(TextControl, {
                                    key: 'rating',
                                    label: __('Expert Rating (out of 5)', 'product-card-plugin'),
                                    value: rating,
                                    onChange: (value) => setAttributes({ rating: value })
                                }),
                                el(TextControl, {
                                    key: 'readReviewsUrl',
                                    label: __('Read Reviews URL', 'product-card-plugin'),
                                    type: 'url',
                                    value: readReviewsUrl,
                                    onChange: (value) => setAttributes({ readReviewsUrl: value })
                                }),
                                
                            ]
                        ),

                        // Price & CTA Panel
                        el(
                            PanelBody,
                            {
                                title: __('Price & Call to Action', 'product-card-plugin'),
                                initialOpen: false,
                                key: 'price-cta'
                            },
                            [
                                el(TextControl, {
                                    key: 'currency',
                                    label: __('Currency Symbol', 'product-card-plugin'),
                                    value: currency,
                                    onChange: (value) => setAttributes({ currency: value })
                                }),
                                el(TextControl, {
                                    key: 'price',
                                    label: __('Price', 'product-card-plugin'),
                                    value: price,
                                    onChange: (value) => setAttributes({ price: value })
                                }),
                                el(TextControl, {
                                    key: 'originalPrice',
                                    label: __('Original Price (for strikethrough)', 'product-card-plugin'),
                                    value: originalPrice,
                                    onChange: (value) => setAttributes({ originalPrice: value })
                                }),
                                el(TextControl, {
                                    key: 'ctaLabel',
                                    label: __('Buy Now Button Main Text', 'product-card-plugin'),
                                    value: ctaLabel,
                                    onChange: (value) => setAttributes({ ctaLabel: value })
                                }),
                                el(TextControl, {
                                    key: 'buyLaterLabel',
                                    label: __('Buy Later Button Text', 'product-card-plugin'),
                                    value: buyLaterLabel,
                                    onChange: (value) => setAttributes({ buyLaterLabel: value })
                                }),
                                el(TextControl, {
                                    key: 'ctaSubText',
                                    label: __('Buy Now Button Subtext', 'product-card-plugin'),
                                    value: ctaSubText,
                                    onChange: (value) => setAttributes({ ctaSubText: value })
                                }),
                                el(TextControl, {
                                    key: 'ctaUrl',
                                    label: __('Button URL', 'product-card-plugin'),
                                    type: 'url',
                                    value: ctaUrl,
                                    onChange: (value) => setAttributes({ ctaUrl: value })
                                })
                            ]
                        ),

                        // Header Color Panel
                        el(
                            PanelBody,
                            {
                                title: __('Header Color', 'product-card-plugin'),
                                initialOpen: false,
                                key: 'header-color'
                            },
                            [
                                el(wp.blockEditor.ColorPalette, {
                                    key: 'headerColor',
                                    value: headerColor,
                                    onChange: (color) => setAttributes({ headerColor: color }),
                                    label: __('Header Color', 'product-card-plugin')
                                })
                            ]
                        )
                    ]
                ),

                // Editor Preview
                el(
                    'div',
                    {
                        key: 'preview',
                        className: 'pcpx-block-editor-preview',
                        style: {
                            padding: '24px',
                            background: '#ffffff',
                            border: '1px solid #e2e8f0',
                            borderRadius: '12px',
                            textAlign: 'center',
                            maxWidth: '420px',
                            margin: '0 auto'
                        }
                    },
                    [
                        el('div', {
                            key: 'icon',
                            className: 'dashicons dashicons-products',
                            style: {
                                fontSize: '40px',
                                color: '#0f172a',
                                marginBottom: '8px'
                            }
                        }),
                        el('h3', { key: 'title', style: { margin: '0 0 4px 0', fontSize: '18px' } }, productName || __('Product Card', 'product-card-plugin')),
                        el('div', {
                            key: 'price-tag',
                            style: {
                                fontSize: '20px',
                                fontWeight: 'bold',
                                color: '#0f172a',
                                marginBottom: '12px'
                            }
                        }, (originalPrice ? el('span', { style: { textDecoration: 'line-through', color: '#94a3b8', fontSize: '15px', marginRight: '8px' } }, (currency || '$') + originalPrice) : null), (currency || '$') + (price || '69')),
                        el('p', {
                            key: 'status', style: {
                                color: status === 'scam' ? '#dc2626' : '#16a34a',
                                fontWeight: 'bold',
                                margin: '0 0 16px 0',
                                fontSize: '13px'
                            }
                        },
                            status === 'legit' ? __('✓ LEGIT Product', 'product-card-plugin') : __('✗ SCAM Product', 'product-card-plugin')
                        ),
                        // Primary Buy Now button (Exact reference design: left cart icon, center bold text, right chevron)
                        el('div', {
                            key: 'btn-preview-primary',
                            style: {
                                position: 'relative',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                padding: '0 54px',
                                height: '80px',
                                minHeight: '80px',
                                maxHeight: '100px',
                                background: '#000000',
                                color: '#ffffff',
                                border: '1px solid #1c1c1f',
                                borderRadius: '10px',
                                width: '100%',
                                maxWidth: '100%',
                                margin: '0 auto 14px auto',
                                cursor: 'pointer',
                                boxSizing: 'border-box',
                                boxShadow: '0 8px 24px -2px rgba(0, 0, 0, 0.35), 0 3px 8px -2px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.16)'
                            }
                        }, [
                            el('span', { key: 'l-icon', style: { position: 'absolute', left: '100px', top: '50%', transform: 'translateY(-50%)', display: 'flex', alignItems: 'center' } }, [
                                el('svg', { key: 'cart-svg', width: 40, height: 40, viewBox: '0 0 24 24', fill: '#ffffff' }, [
                                    el('path', { key: 'p1', d: 'M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z' })
                                ])
                            ]),
                            el('div', { key: 'txt-col', style: { display: 'flex', flexDirection: 'column', alignItems: 'center', textAlign: 'center', lineHeight: 1.15 } }, [
                                el('span', { key: 'txt', style: { fontSize: '26px', fontWeight: '800', color: '#ffffff', letterSpacing: '-0.02em', marginBottom: '2px' } }, ctaLabel || __('Buy Now', 'product-card-plugin')),
                                el('span', { key: 'sub', style: { fontSize: '15px', fontWeight: '500', color: 'rgba(255, 255, 255, 0.95)' } }, ctaSubText || __('Visit Official Website', 'product-card-plugin'))
                            ]),
                            el('span', { key: 'r-chev', style: { position: 'absolute', right: '24px', top: '50%', transform: 'translateY(-50%)', display: 'flex', alignItems: 'center' } }, [
                                el('svg', { key: 'chev-svg', width: 22, height: 22, viewBox: '0 0 24 24', fill: 'none', stroke: '#ffffff', strokeWidth: 3, strokeLinecap: 'round', strokeLinejoin: 'round' }, [
                                    el('polyline', { key: 'poly', points: '9 18 15 12 9 6' })
                                ])
                            ])
                        ]),
                        // Secondary Buy Later button (Exact reference design: left email icon, center bold text, right chevron)
                        el('div', {
                            key: 'btn-preview-secondary',
                            style: {
                                position: 'relative',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                padding: '0 54px',
                                height: '80px',
                                minHeight: '80px',
                                maxHeight: '100px',
                                background: '#ffffff',
                                color: '#0f172a',
                                border: '1.5px solid #e2e8f0',
                                borderRadius: '10px',
                                width: '100%',
                                maxWidth: '100%',
                                margin: '0 auto',
                                cursor: 'pointer',
                                boxSizing: 'border-box',
                                boxShadow: '0 8px 22px -3px rgba(0, 0, 0, 0.08), 0 3px 8px -2px rgba(0, 0, 0, 0.04)'
                            }
                        }, [
                            el('span', { key: 'l-icon', style: { position: 'absolute', left: '100px', top: '50%', transform: 'translateY(-50%)', display: 'flex', alignItems: 'center' } }, [
                                el('svg', { key: 'email-svg', width: 40, height: 40, viewBox: '0 0 24 24', fill: 'none', stroke: '#1e293b', strokeWidth: 2.2, strokeLinecap: 'round', strokeLinejoin: 'round' }, [
                                    el('rect', { key: 'r1', x: 2, y: 4, width: 20, height: 16, rx: 2 }),
                                    el('path', { key: 'p2', d: 'm22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7' })
                                ])
                            ]),
                            el('div', { key: 'txt-col', style: { display: 'flex', flexDirection: 'column', alignItems: 'center', textAlign: 'center', lineHeight: 1.15 } }, [
                                el('span', { key: 'txt', style: { fontSize: '26px', fontWeight: '800', color: '#0f172a', letterSpacing: '-0.02em', marginBottom: '2px' } }, buyLaterLabel || __('Buy Later', 'product-card-plugin')),
                                el('span', { key: 'sub', style: { fontSize: '15px', color: '#475569', fontWeight: '500' } }, __('Save Official Link to Email', 'product-card-plugin'))
                            ]),
                            el('span', { key: 'r-chev', style: { position: 'absolute', right: '24px', top: '50%', transform: 'translateY(-50%)', display: 'flex', alignItems: 'center' } }, [
                                el('svg', { key: 'chev-svg', width: 22, height: 22, viewBox: '0 0 24 24', fill: 'none', stroke: '#334155', strokeWidth: 3, strokeLinecap: 'round', strokeLinejoin: 'round' }, [
                                    el('polyline', { key: 'poly', points: '9 18 15 12 9 6' })
                                ])
                            ])
                        ])
                    ]
                )
            ]
        );
    },

    save: function () {
        // Server-side rendering
        return null;
    }
});