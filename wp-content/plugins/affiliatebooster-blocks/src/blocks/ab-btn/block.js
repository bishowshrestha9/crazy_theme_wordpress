import './style.scss';
import Edit from './Edit';
import Save from './Save';
import attributes from './attributes';
import icons from '../../icons/icons';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('affiliate-booster/ab-advance-button', {
    title: __('AB Button'),
    description: __(''),
    category: 'affiliate-booster',
    icon: icons.button,
    keywords: [
        __('Button'),
        __('Button Block'),
        __('Advanced Button'),
    ],
    example: {
        attributes: {
            background: '#fff'
        },
    },
    attributes,
    edit: Edit,
    save: Save,
});
