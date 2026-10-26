(function () {
    'use strict';

    if (!window.wp || !window.wp.blocks) {
        return;
    }

    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var Fragment = wp.element.Fragment;
    var useState = wp.element.useState;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var useBlockProps = wp.blockEditor.useBlockProps;
    var PanelBody = wp.components.PanelBody;
    var TextControl = wp.components.TextControl;
    var ColorPicker = wp.components.ColorPicker;
    var BaseControl = wp.components.BaseControl;
    var Button = wp.components.Button;
    var __ = wp.i18n.__;

    /* ── Inline list editor ───────────────────────────── */
    function ListEditor(props) {
        var items = props.items;
        var onChange = props.onChange;
        var icon = props.icon;
        var iconColor = props.iconColor;
        var colClass = props.colClass;

        function updateItem(index, value) {
            var next = items.slice();
            next[index] = value;
            onChange(next);
        }

        function removeItem(index) {
            var next = items.filter(function (_, i) { return i !== index; });
            onChange(next.length > 0 ? next : ['']);
        }

        function addItem() {
            onChange(items.concat(['']));
        }

        var rows = items.map(function (item, index) {
            return el(
                'div',
                { key: index, className: 'spc-item-row' },
                el('span', { className: 'spc-item-icon', style: { color: iconColor } }, icon),
                el('input', {
                    type: 'text',
                    value: item,
                    placeholder: __('Enter item…', 'simple-pros-cons'),
                    onChange: function (e) { updateItem(index, e.target.value); },
                    className: 'spc-item-input',
                }),
                el(
                    'button',
                    {
                        type: 'button',
                        className: 'spc-remove-btn',
                        onClick: function () { removeItem(index); },
                        title: __('Remove', 'simple-pros-cons'),
                    },
                    '×'
                )
            );
        });

        return el(
            'div',
            { className: 'spc-list-editor spc-list ' + colClass },
            rows,
            el(
                'button',
                { type: 'button', className: 'spc-add-btn', onClick: addItem },
                '+ ' + __('Add Item', 'simple-pros-cons')
            )
        );
    }

    /* ── Block registration ───────────────────────────── */
    registerBlockType('simple-pros-cons/pros-cons', {

        title: __('Pros & Cons', 'simple-pros-cons'),
        description: __('Add a Pros & Cons block to your post.', 'simple-pros-cons'),
        icon: 'yes-alt',
        category: 'simple-pros-cons',
        keywords: ['pros', 'cons', 'list', 'review'],

        /* Attributes are declared in PHP registration; mirrored here for IDE hints */
        attributes: {
            prosTitle: { type: 'string', default: 'Pros' },
            consTitle: { type: 'string', default: 'Cons' },
            prosItems: { type: 'array', default: [''] },
            consItems: { type: 'array', default: [''] },
            prosBgColor: { type: 'string', default: '#00bf08' },
            consBgColor: { type: 'string', default: '#eb3903' },
            prosIconColor: { type: 'string', default: '#30ac3d' },
            consIconColor: { type: 'string', default: '#ff1818' },
            prosTextColor: { type: 'string', default: '#ffffff' },
            consTextColor: { type: 'string', default: '#ffffff' },
            prosListColor: { type: 'string', default: '#333333' },
            consListColor: { type: 'string', default: '#333333' },
        },

        /* ── Edit (Gutenberg editor) ──────────────────── */
        edit: function Edit(props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            var prosTitle = attributes.prosTitle;
            var consTitle = attributes.consTitle;
            var prosItems = attributes.prosItems;
            var consItems = attributes.consItems;
            var prosBgColor = attributes.prosBgColor;
            var consBgColor = attributes.consBgColor;
            var prosIconColor = attributes.prosIconColor;
            var consIconColor = attributes.consIconColor;
            var prosTextColor = attributes.prosTextColor;
            var consTextColor = attributes.consTextColor;

            var blockProps = useBlockProps({ className: 'spc-wrapper spc-editor' });

            /* Inspector sidebar */
            var inspector = el(
                InspectorControls,
                null,

                // ─ Pros panel ─
                el(PanelBody, { title: __('✓ Pros Settings', 'simple-pros-cons'), initialOpen: true },
                    el(TextControl, {
                        label: __('Pros Title', 'simple-pros-cons'),
                        value: prosTitle,
                        onChange: function (v) { setAttributes({ prosTitle: v }); },
                    }),
                    el(BaseControl, { label: __('Header Background', 'simple-pros-cons') },
                        el(ColorPicker, {
                            color: prosBgColor,
                            onChange: function (v) { setAttributes({ prosBgColor: v }); },
                            enableAlpha: false,
                            copyFormat: 'hex',
                        })
                    ),
                    el(BaseControl, { label: __('Header Text Colour', 'simple-pros-cons') },
                        el(ColorPicker, {
                            color: prosTextColor,
                            onChange: function (v) { setAttributes({ prosTextColor: v }); },
                            enableAlpha: false,
                            copyFormat: 'hex',
                        })
                    ),
                    el(BaseControl, { label: __('Icon Colour', 'simple-pros-cons') },
                        el(ColorPicker, {
                            color: prosIconColor,
                            onChange: function (v) { setAttributes({ prosIconColor: v }); },
                            enableAlpha: false,
                            copyFormat: 'hex',
                        })
                    )
                ),

                // ─ Cons panel ─
                el(PanelBody, { title: __('✗ Cons Settings', 'simple-pros-cons'), initialOpen: false },
                    el(TextControl, {
                        label: __('Cons Title', 'simple-pros-cons'),
                        value: consTitle,
                        onChange: function (v) { setAttributes({ consTitle: v }); },
                    }),
                    el(BaseControl, { label: __('Header Background', 'simple-pros-cons') },
                        el(ColorPicker, {
                            color: consBgColor,
                            onChange: function (v) { setAttributes({ consBgColor: v }); },
                            enableAlpha: false,
                            copyFormat: 'hex',
                        })
                    ),
                    el(BaseControl, { label: __('Header Text Colour', 'simple-pros-cons') },
                        el(ColorPicker, {
                            color: consTextColor,
                            onChange: function (v) { setAttributes({ consTextColor: v }); },
                            enableAlpha: false,
                            copyFormat: 'hex',
                        })
                    ),
                    el(BaseControl, { label: __('Icon Colour', 'simple-pros-cons') },
                        el(ColorPicker, {
                            color: consIconColor,
                            onChange: function (v) { setAttributes({ consIconColor: v }); },
                            enableAlpha: false,
                            copyFormat: 'hex',
                        })
                    )
                )
            );

            /* Editor canvas */
            var canvas = el(
                'div',
                blockProps,
                el(
                    'div',
                    { className: 'spc-inner' },

                    /* Pros column */
                    el(
                        'div',
                        { className: 'spc-pros-col' },
                        el(
                            'div',
                            { className: 'spc-header spc-pros-header', style: { backgroundColor: prosBgColor, color: prosTextColor } },
                            el('span', { className: 'spc-header-icon' }, '👍'),
                            el('input', {
                                type: 'text',
                                value: prosTitle,
                                onChange: function (e) { setAttributes({ prosTitle: e.target.value }); },
                                className: 'spc-title-input',
                                style: { color: prosTextColor },
                            })
                        ),
                        el(ListEditor, {
                            items: prosItems,
                            onChange: function (v) { setAttributes({ prosItems: v }); },
                            icon: '✓',
                            iconColor: prosIconColor,
                            colClass: 'spc-pros-list',
                        })
                    ),

                    /* Cons column */
                    el(
                        'div',
                        { className: 'spc-cons-col' },
                        el(
                            'div',
                            { className: 'spc-header spc-cons-header', style: { backgroundColor: consBgColor, color: consTextColor } },
                            el('span', { className: 'spc-header-icon' }, '👎'),
                            el('input', {
                                type: 'text',
                                value: consTitle,
                                onChange: function (e) { setAttributes({ consTitle: e.target.value }); },
                                className: 'spc-title-input',
                                style: { color: consTextColor },
                            })
                        ),
                        el(ListEditor, {
                            items: consItems,
                            onChange: function (v) { setAttributes({ consItems: v }); },
                            icon: '✗',
                            iconColor: consIconColor,
                            colClass: 'spc-cons-list',
                        })
                    )
                )
            );

            return el(Fragment, null, inspector, canvas);
        },

        /* Dynamic block — PHP handles the save output */
        save: function () {
            return null;
        },
    });

}());
