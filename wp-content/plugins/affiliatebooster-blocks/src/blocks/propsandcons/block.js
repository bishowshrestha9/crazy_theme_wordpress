import './editor.scss';
import './style.scss';
import Edit from './Edit';
import Save from './Save';
import attributes from './attributes';
import icons from '../../icons/icons';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('affiliate-booster/propsandcons', {
    title: __('AB Pros & Cons'),
    description: __(''),
    category: 'affiliate-booster',
    icon: icons.procons,
    keywords: [
        __('Advanced'),
        __('list'),
        __('advanced list'),
        __('Advanced List')
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
