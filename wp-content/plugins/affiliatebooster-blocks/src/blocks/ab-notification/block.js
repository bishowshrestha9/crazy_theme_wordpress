import './style.scss';
import Edit from './Edit';
import Save from './Save';
import attributes from './attributes';
import icons from '../../icons/icons';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('affiliate-booster/ab-notification-box', {
    title: __('AB Notification Block'),
    description: __(''),
    category: 'affiliate-booster',
    icon: icons.notification,
    keywords: [
        __('Notification'),
        __('Notification Box'),
        __('Advanced Notification Block'),
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
