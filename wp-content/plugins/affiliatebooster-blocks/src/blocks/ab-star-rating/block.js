import './editor.scss';
import './style.scss';
import Edit from './Edit';
import Save from './Save';
import attributes from './attributes';
import icons from '../../icons/icons';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('affiliate-booster/ab-star-rating', {
    title: __('AB Star Rating'),
    description: __(''),
    category: 'affiliate-booster',
    icon: icons.star,
    keywords: [
        __('Rating'),
        __('Star Rating'),
        __('Star/Review Rating'),
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