/**
 * Verified Analysis Summary – Gutenberg Block v1.0
 * Layout: image left | rating + Summary + ✓ bullets + Verdict (+ Buy Now) right
 * Green-themed verified version. No build step required.
 */
(function () {
    'use strict';

    var registerBlockType = wp.blocks.registerBlockType;
    var el                = wp.element.createElement;
    var Fragment          = wp.element.Fragment;
    var InspectorControls = (wp.blockEditor || wp.editor).InspectorControls;
    var MediaUpload       = (wp.blockEditor || wp.editor).MediaUpload;
    var MediaUploadCheck  = (wp.blockEditor || wp.editor).MediaUploadCheck;
    var __                = wp.i18n.__;
    var PanelBody         = wp.components.PanelBody;
    var TextControl       = wp.components.TextControl;
    var TextareaControl   = wp.components.TextareaControl;
    var Button            = wp.components.Button;
    var SelectControl     = wp.components.SelectControl;

    registerBlockType('verified-analysis-plugin/verified-summary', {

        title:    __('Verified Analysis Summary', 'verified-analysis-plugin'),
        icon:     'yes-alt',
        category: 'common',
        keywords: [ 'verified', 'analysis', 'summary', 'supplement', 'review' ],

        attributes: {
            productName: { type: 'string', default: 'Product Name' },
            imageSize:   { type: 'string', default: '400x400' },
            imageUrl:    { type: 'string', default: '' },
            imageId:     { type: 'number', default: 0 },
            rating:      { type: 'string', default: '4.5' },
            bullet1:     { type: 'string', default: 'Clinically tested formula with proven results backed by independent lab reports.' },
            bullet2:     { type: 'string', default: 'Transparent manufacturer details, third-party tested, and FDA-registered facility.' },
            bullet3:     { type: 'string', default: 'Thousands of satisfied customers reporting significant improvements with no serious side effects.' },
            bullet4:     { type: 'string', default: 'Honest pricing, clear refund policy, and genuine customer support with real reviews.' },
            verdict:     { type: 'string', default: 'Verified' },
            buyNowUrl:   { type: 'string', default: '#' },
            buyNowText:  { type: 'string', default: 'Buy Now' },
        },

        // ----------------------------------------------------------------
        // EDIT
        // ----------------------------------------------------------------
        edit: function (props) {
            var a  = props.attributes;
            var sa = props.setAttributes;

            /* helper: a simple star string for the preview */
            function starPreview(r) {
                var val    = parseFloat(r) || 0;
                var full   = Math.floor(val);
                var half   = (val - full) >= 0.5 ? 1 : 0;
                var empty  = 5 - full - half;
                return '★'.repeat(full) + (half ? '½' : '') + '☆'.repeat(empty) + ' ' + val.toFixed(1) + '/5';
            }

            var bullets     = [a.bullet1, a.bullet2, a.bullet3, a.bullet4].filter(Boolean);
            var verdictLow  = (a.verdict || '').trim().toLowerCase();
            var showBuyNow  = verdictLow === 'verified' || verdictLow === 'complete scam';

            return el(
                Fragment,
                {},

                /* ===== SIDEBAR ===== */
                el(InspectorControls, { key: 'inspector' },

                    el(PanelBody,
                        { title: __('Product Settings', 'verified-analysis-plugin'), initialOpen: true, key: 'ps' },
                        el(TextControl, {
                            key: 'pn',
                            label: __('Product Name', 'verified-analysis-plugin'),
                            value: a.productName,
                            onChange: function (v) { sa({ productName: v }); }
                        }),
                        el(TextControl, {
                            key: 'rt',
                            label: __('Rating (0–5, e.g. 4.5)', 'verified-analysis-plugin'),
                            value: a.rating,
                            type: 'number',
                            min: '0', max: '5', step: '0.5',
                            onChange: function (v) { sa({ rating: v }); }
                        })
                    ),

                    el(PanelBody,
                        { title: __('Product Image', 'verified-analysis-plugin'), initialOpen: false, key: 'pi' },
                        el(SelectControl, {
                            key: 'isz',
                            label: __('Image size', 'verified-analysis-plugin'),
                            value: a.imageSize,
                            options: [
                                { label: '320x320 (Compact)', value: '320x320' },
                                { label: '400x400 (Standard)', value: '400x400' },
                                { label: '500x500 (Large)', value: '500x500' }
                            ],
                            onChange: function (v) { sa({ imageSize: v }); }
                        }),
                        el(MediaUploadCheck, { key: 'mc' },
                            el(MediaUpload, {
                                key: 'mu',
                                onSelect: function (m) { sa({ imageUrl: m.url, imageId: m.id }); },
                                allowedTypes: ['image'],
                                value: a.imageId,
                                render: function (ref) {
                                    return el(Button, {
                                        onClick: ref.open,
                                        isPrimary: !a.imageUrl,
                                        isSecondary: !!a.imageUrl
                                    }, a.imageUrl
                                        ? __('Change Image', 'verified-analysis-plugin')
                                        : __('Upload Image', 'verified-analysis-plugin')
                                    );
                                }
                            })
                        ),
                        a.imageUrl && el('img', {
                            key: 'pv',
                            src: a.imageUrl,
                            style: { maxWidth: '100%', marginTop: '10px', borderRadius: '6px' }
                        }),
                        a.imageUrl && el(Button, {
                            key: 'rm',
                            isDestructive: true,
                            style: { marginTop: '8px' },
                            onClick: function () { sa({ imageUrl: '', imageId: 0 }); }
                        }, __('Remove Image', 'verified-analysis-plugin'))
                    ),

                    el(PanelBody,
                        { title: __('Summary Bullet Points', 'verified-analysis-plugin'), initialOpen: false, key: 'sb' },
                        el(TextareaControl, {
                            key: 'b1',
                            label: __('Bullet 1', 'verified-analysis-plugin'),
                            value: a.bullet1,
                            onChange: function (v) { sa({ bullet1: v }); }
                        }),
                        el(TextareaControl, {
                            key: 'b2',
                            label: __('Bullet 2', 'verified-analysis-plugin'),
                            value: a.bullet2,
                            onChange: function (v) { sa({ bullet2: v }); }
                        }),
                        el(TextareaControl, {
                            key: 'b3',
                            label: __('Bullet 3', 'verified-analysis-plugin'),
                            value: a.bullet3,
                            onChange: function (v) { sa({ bullet3: v }); }
                        }),
                        el(TextareaControl, {
                            key: 'b4',
                            label: __('Bullet 4', 'verified-analysis-plugin'),
                            value: a.bullet4,
                            onChange: function (v) { sa({ bullet4: v }); }
                        })
                    ),

                    el(PanelBody,
                        { title: __('Verdict', 'verified-analysis-plugin'), initialOpen: false, key: 'vp' },
                        el(TextControl, {
                            key: 'vt',
                            label: __('Verdict Text', 'verified-analysis-plugin'),
                            value: a.verdict,
                            onChange: function (v) { sa({ verdict: v }); }
                        })
                    ),

                    el(PanelBody,
                        { title: __('Buy Now Button', 'verified-analysis-plugin'), initialOpen: false, key: 'bp' },
                        el('p', { key: 'bpnote', style: { fontSize: '12px', color: '#6b7280', margin: '0 0 8px' } },
                            __('Button appears automatically when Verdict is "Complete Scam".', 'verified-analysis-plugin')
                        ),
                        el(TextControl, {
                            key: 'bu',
                            label: __('Buy Now URL', 'verified-analysis-plugin'),
                            value: a.buyNowUrl,
                            onChange: function (v) { sa({ buyNowUrl: v }); }
                        }),
                        el(TextControl, {
                            key: 'bt',
                            label: __('Buy Now Button Text', 'verified-analysis-plugin'),
                            value: a.buyNowText,
                            onChange: function (v) { sa({ buyNowText: v }); }
                        })
                    )
                ),

                /* ===== EDITOR PREVIEW ===== */
                el('div', {
                    key: 'preview',
                    style: {
                        fontFamily: 'sans-serif',
                        border: '2px solid #16a34a',
                        borderRadius: '12px',
                        overflow: 'hidden',
                        background: '#ffffff',
                        boxShadow: '0 2px 14px rgba(22,163,74,0.12)'
                    }
                },

                    el('div', {
                        key: 'tb',
                        style: {
                            background: '#15803d',
                            padding: '15px 20px',
                            display: 'flex',
                            alignItems: 'center',
                            justifyContent: 'center',
                            gap: '10px',
                            flexWrap: 'wrap',
                            textAlign: 'center'
                        }
                    },
                        el('span', { key: 'tn', style: { fontSize: '22px', fontWeight: '900', color: '#fff', textTransform: 'uppercase', letterSpacing: '1.5px' } }, a.productName || 'Product Name'),
                        el('span', { key: 'ts', style: { fontSize: '22px', fontWeight: '900', color: '#fff', textTransform: 'uppercase', letterSpacing: '1.5px' } }, 'Verified Analysis Summary')
                    ),

                    /* Body */
                    el('div', {
                        key: 'body',
                        style: { display: 'grid', gridTemplateColumns: (a.imageSize === '500x500' ? '500px 1fr' : (a.imageSize === '320x320' ? '320px 1fr' : '400px 1fr')), alignItems: 'stretch' }
                    },

                        /* Left – image */
                        el('div', {
                            key: 'left',
                            style: {
                                borderRight: '2px solid #dcfce7',
                                background: '#f0fdf4',
                                padding: '0',
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'center',
                                minHeight: (a.imageSize === '500x500' ? '500px' : (a.imageSize === '320x320' ? '320px' : '400px'))
                            }
                        },
                            a.imageUrl
                                ? el('img', {
                                    key: 'img',
                                    src: a.imageUrl,
                                    style: { width: '100%', height: '100%', objectFit: 'cover' }
                                })
                                : el('div', {
                                    key: 'ni',
                                    style: {
                                        width: '100%', height: '100%',
                                        background: 'transparent', border: '2px dashed #86efac',
                                        display: 'flex',
                                        alignItems: 'center', justifyContent: 'center',
                                        color: '#9ca3af', fontSize: '12px', textAlign: 'center',
                                        padding: '8px'
                                    }
                                }, 'Upload image in sidebar')
                        ),

                        /* Right – rating + summary + bullets + verdict */
                        el('div', {
                            key: 'right',
                            style: { padding: '20px 24px', display: 'flex', flexDirection: 'column', gap: '14px' }
                        },

                            /* Rating row */
                            el('div', {
                                key: 'rr',
                                style: { display: 'flex', alignItems: 'center', gap: '8px', flexWrap: 'wrap' }
                            },
                                el('span', { key: 'rl', style: { fontSize: '12px', fontWeight: '700', textTransform: 'uppercase', letterSpacing: '0.6px', color: '#6b7280' } }, 'Rating:'),
                                el('span', { key: 'rs', style: { fontSize: '18px', color: '#facc15', letterSpacing: '2px' } }, starPreview(a.rating))
                            ),

                            /* Summary heading */
                            el('div', {
                                key: 'sh',
                                style: {
                                    fontSize: '13px', fontWeight: '800',
                                    textTransform: 'uppercase', letterSpacing: '1px',
                                    color: '#15803d', paddingBottom: '6px',
                                    borderBottom: '2px solid #dcfce7'
                                }
                            }, 'Summary'),

                            /* Bullet points */
                            el('ul', {
                                key: 'ul',
                                style: { listStyle: 'none', margin: '0', padding: '0', display: 'flex', flexDirection: 'column', gap: '16px' }
                            },
                                bullets.length > 0
                                    ? bullets.map(function (txt, i) {
                                        return el('li', {
                                            key: 'li' + i,
                                            style: { display: 'flex', alignItems: 'flex-start', gap: '14px' }
                                        },
                                            el('span', {
                                                key: 'x',
                                                style: {
                                                    flexShrink: '0', width: '20px', height: '20px',
                                                    background: '#16a34a', color: '#fff',
                                                    fontSize: '12px', fontWeight: '700',
                                                    borderRadius: '50%', display: 'flex',
                                                    alignItems: 'center', justifyContent: 'center',
                                                    marginTop: '2px', lineHeight: '1'
                                                }
                                            }, '\u2713'),
                                            el('span', { key: 'bt', style: { fontSize: '14px', lineHeight: '1.5', color: '#000000', fontWeight: '600' } }, txt)
                                        );
                                    })
                                    : el('li', { key: 'empty', style: { color: '#9ca3af', fontSize: '13px' } }, 'Add bullet points in the sidebar')
                            ),

                            /* Verdict */
                            el('div', {
                                key: 'vd',
                                style: {
                                    display: 'flex', alignItems: 'center',
                                    gap: '10px', flexWrap: 'wrap',
                                    background: '#16a34a',
                                    borderRadius: '8px',
                                    padding: '12px 18px', marginTop: '8px'
                                }
                            },
                                el('span', { key: 'vi', style: { fontSize: '18px', lineHeight: '1', flexShrink: '0', color: '#fff' } }, '\u2713'),
                                el('span', { key: 'vl', style: { fontSize: '13px', fontWeight: '800', textTransform: 'uppercase', letterSpacing: '0.8px', color: 'rgba(255,255,255,0.80)' } }, 'Verdict:'),
                                el('span', { key: 'vt', style: { fontSize: '16px', fontWeight: '800', color: '#fff', textTransform: 'uppercase', letterSpacing: '0.8px', flex: '1' } }, a.verdict || 'Verified'),
                                showBuyNow && el('span', {
                                    key: 'bn',
                                    style: {
                                        display: 'inline-block',
                                        padding: '8px 20px',
                                        background: '#ffffff',
                                        color: '#15803d',
                                        fontSize: '14px',
                                        fontWeight: '800',
                                        textTransform: 'uppercase',
                                        letterSpacing: '0.6px',
                                        borderRadius: '6px',
                                        whiteSpace: 'nowrap',
                                        boxShadow: '0 2px 8px rgba(0,0,0,0.15)',
                                        cursor: 'pointer'
                                    }
                                }, a.buyNowText || 'Buy Now')
                            )
                        )
                    )
                )
            );
        },

        // ----------------------------------------------------------------
        // SAVE – server-side render
        // ----------------------------------------------------------------
        save: function () {
            return null;
        }
    });

})();
