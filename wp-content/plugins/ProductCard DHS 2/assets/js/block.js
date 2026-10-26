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
        observation: {
            type: 'string',
            default: 'Confirms prostate support via DHT (5-alpha reductase) inhibition and anti-inflammatory action'
        },
        verdict: {
            type: 'string',
            default: 'Gutoptim is a Genuine Prostate Support Supplement'
        },
        bestFor: {
            type: 'string',
            default: 'People over 50 with Urinary Track Infection'
        },
        rating: {
            type: 'string',
            default: '9.5'
        },
        certification: {
            type: 'string',
            default: 'FDA/GMP Approved'
        },
        moneyBackGuarantee: {
            type: 'string',
            default: '60 Days'
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
        ctaUrl: {
            type: 'string',
            default: '#'
        },
        headerColor: {
            type: 'string',
            default: '#2563eb'
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
            observation,
            verdict,
            bestFor,
            rating,
            certification,
            moneyBackGuarantee,
            price,
            originalPrice,
            currency,
            ctaLabel,
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
                                    key: 'observation',
                                    label: __('Our Observation (Max 95 chars)', 'product-card-plugin'),
                                    value: observation,
                                    help: (observation ? observation.length : 0) + '/95 characters',
                                    onChange: (value) => setAttributes({ observation: value.substr(0, 95) })
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
                                el(TextareaControl, {
                                    key: 'bestFor',
                                    label: __('Best For', 'product-card-plugin'),
                                    value: bestFor,
                                    onChange: (value) => setAttributes({ bestFor: value })
                                }),
                                el(TextControl, {
                                    key: 'rating',
                                    label: __('Expert Rating (out of 5)', 'product-card-plugin'),
                                    value: rating,
                                    onChange: (value) => setAttributes({ rating: value })
                                }),
                                el(TextControl, {
                                    key: 'certification',
                                    label: __('Certification', 'product-card-plugin'),
                                    value: certification,
                                    onChange: (value) => setAttributes({ certification: value })
                                }),
                                el(TextControl, {
                                    key: 'moneyBackGuarantee',
                                    label: __('Money Back Guarantee', 'product-card-plugin'),
                                    value: moneyBackGuarantee,
                                    onChange: (value) => setAttributes({ moneyBackGuarantee: value })
                                })
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
                                    label: __('Button Text', 'product-card-plugin'),
                                    value: ctaLabel,
                                    onChange: (value) => setAttributes({ ctaLabel: value })
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
                                color: status === 'legit' ? '#10b981' : '#ef4444',
                                marginBottom: '10px'
                            }
                        }),
                        el('h3', { key: 'title' }, productName || __('Product Card', 'product-card-plugin')),
                        el('p', { key: 'desc', style: { color: '#666' } },
                            __('Configure all settings in the sidebar →', 'product-card-plugin')
                        ),
                        el('p', {
                            key: 'status', style: {
                                color: status === 'legit' ? '#10b981' : '#ef4444',
                                fontWeight: 'bold'
                            }
                        },
                            status === 'legit' ? __('✓ LEGIT Product', 'product-card-plugin') : __('✗ SCAM Product', 'product-card-plugin')
                        )
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