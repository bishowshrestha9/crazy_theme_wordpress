import './editor.scss';
import './style.scss';
import Edit from './Edit';
import Save from './Save';
import attributes from './attributes';
import icons from '../../icons/icons';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

registerBlockType('affiliate-booster/ab-notice-box', {
    title: __('AB Notice Block'),
    description: __(''),
    category: 'affiliate-booster',
    icon: icons.notice,
    keywords: [
        __('Notice'),
        __('Message Box'),
        __('Advanced Notice Block'),
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
