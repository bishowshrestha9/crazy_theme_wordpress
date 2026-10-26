import './editor.scss';
import './style.scss';
import Edit from './Edit';
import Save from './Save';
import attributes from './attributes';
import icons from '../../icons/icons';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('affiliate-booster/ab-coupon4', {
    title: __('AB Coupons Box 2'),
    description: __(''),
    category: 'affiliate-booster',
    icon: icons.coupan,
    keywords: [
        __('AB Coupon Block')
    ],
    supports: {
        align:true,
        align:['none','wide','full'],
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
