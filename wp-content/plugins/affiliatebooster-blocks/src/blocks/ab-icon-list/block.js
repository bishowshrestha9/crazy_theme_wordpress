import './editor.scss';
import './style.scss';
import Edit from './Edit';
import Save from './Save';
import attributes from './attributes';
import icons from '../../icons/icons';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('affiliate-booster/ab-icon-list', {
    title: __('AB Icon List'),
    description: __(''),
    category: 'affiliate-booster',
    icon: icons.list,
    keywords: [
        __('Advanced Icon List'),
        __('Icon List'),
        __('Advanced list'),
    ],
    supports: {
        align:true,
        align:['none','wide','full'],
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
