import './editor.scss';
import './style.scss';
import Edit from './Edit';
import Save from './Save';
import attributes from './attributes';
import icons from '../../icons/icons';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('affiliate-booster/ab-single-product', {
    title: __('AB Single Product'),
    description: __(''),
    category: 'affiliate-booster',
    icon: icons.single,
    keywords: [
        __('Single Product'),
        __('Single Product Showcase'),
        __('AB Product showcase'),
    ],
    supports: {
        splitting: true,
    },
    example: {
        attributes: {
            background: '#fff'
        },
    },
    attributes,
    edit: Edit,
    save: Save,
});
