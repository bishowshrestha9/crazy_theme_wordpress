import './editor.scss';
import './style.scss';
import Edit from './Edit';
import Save from './Save';
import attributes from './attributes';
import icons from '../../icons/icons';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('affiliate-booster/ab-coupon2', {
    title: __('AB Products Column'),
    description: __(''),
    category: 'affiliate-booster',
    icon: icons.coupan,
    keywords: [
        __('AB Coupon Block')
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
