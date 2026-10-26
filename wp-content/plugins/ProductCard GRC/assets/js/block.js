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
            default: 'from Official Website'
        },
        ctaUrl: {
            type: 'string',
            default: '#'
        },
        headerColor: {
            type: 'string',
            default: '#000000'
        },
        
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
            ctaSubText,
            ctaUrl,
            headerColor
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
                            padding: '20px',
                            background: '#f0f0f0',
                            borderRadius: '8px',
                            textAlign: 'center'
                        }
                    },
                    [
                        el('div', {
                            key: 'icon',
                            className: 'dashicons dashicons-products',
                            style: {
                                fontSize: '48px',
                                color: '#000000',
                                marginBottom: '10px'
                            }
                        }),
                        el('h3', { key: 'title' }, productName || __('Product Card', 'product-card-plugin')),
                        el('p', { key: 'desc', style: { color: '#666' } },
                            __('Configure all settings in the sidebar →', 'product-card-plugin')
                        ),
                        el('p', {
                            key: 'status', style: {
                                color: '#000000',
                                fontWeight: 'bold'
                            }
                        },
                            status === 'legit' ? __('✓ LEGIT Product', 'product-card-plugin') : __('✗ SCAM Product', 'product-card-plugin')
                        ),
                        el('div', {
                            key: 'btn-preview',
                            style: {
                                marginTop: '15px',
                                padding: '10px 20px',
                                background: status === 'scam' ? '#dc2626' : '#16a34a',
                                color: '#ffffff',
                                borderRadius: '6px',
                                display: 'inline-block',
                                fontWeight: 'bold'
                            }
                        }, [
                            el('div', { key: 'main', style: { fontSize: '16px' } }, ctaLabel || __('Buy Now', 'product-card-plugin')),
                            ctaSubText ? el('div', { key: 'sub', style: { fontSize: '11px', opacity: 0.85, fontWeight: 'normal' } }, ctaSubText) : null
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