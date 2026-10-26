import './editor.scss';
import './style.scss';
import Edit from './Edit';
import Save from './Save';
import attributes from './attributes';
import icons from '../../icons/icons';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('affiliate-booster/ab-callto-action', {
    title: __('AB Call to action'),
    description: __(''),
    category: 'affiliate-booster',
    icon: icons.cta,
    keywords: [
        __('Call to action'),
        __('CTA'),
        __('advanced CTA'),
    ],
    supports: {
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
