const attributes = {
    block_id: {
        type: 'string',
    },
    boxBg: {
        type: 'object',
        default: {
            bgimgPosition: 'center center',
            bgimgSize: 'cover',
            bgimgRepeat: 'no-repeat',
            bgDefaultColor: '#f5f5f5',
            bgimageSource: 'local',
            externalImageUrl: {}
        },
    },
    btnBg: {
        type: 'object',
        default: {
            openBg : true, 
            bgDefaultColor: '#ffc207',   
            bgGradient:{
                color1:'#35c9ff',
                color2:'#39f768',
            },         
        },
    },
    btnBgHover: {
        type: 'object',
        default: {
            openBg : true,              
            bgDefaultColor: '#00ffcc',
            bgGradient:{
                color1:'#aefa57',
                color2:'#f7da1e',
            },
        },
    },

    btnShadow:{
        type: 'object',
        default: {
            openShadow:true,
            blur:35,
            color:"rgba(0, 0, 0, 0.14)",
            inset : 0,
            spread : 0,
            horizontal: 0,
            vertical : 8
        }
    },
    btnBgColor:{
        type: 'string',
        default:'#F9CC29'
    },
    btnSize:{
        type: 'string',
        default:'small'
    },
    btnRel: {
        type: 'string',
        default: 'nofollow'
    },
    buttonWidth:{
        type: 'string',
        default:''
    },
    buttonIcon:{
        type: 'string',
        default:''
    },
    iconPosition:{
        type: 'string',
        default:''
    },
    btnTitle:{
        type:'string',
        default:'Button Text'
    },
    btnBgHoverColor:{
        type: 'string',
        default:'#61ce70'
    },
    titleAlignment: {
        type: 'string',
        default:''
    },
    titleAlignmentMobile: {
        type: 'string',
        default:''
    },
    titleAlignmentTablet: {
        type: 'string',
        default:''
    },
    titlePadding:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    titlePaddingTablet:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    titlePaddingMobile:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    titleMargin:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    titleMarginTablet:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    titleMarginMobile:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    titleLoadGoogleFonts:{
        type: 'boolean',
        default: false
    },
    btnTarget:{
        type: 'boolean',
        default: false
    },
    bntRounded:{
        type: 'boolean',
        default: false
    },
    titleBgColor: {
        type: 'string',
        default:'',
    },
    titleTextColor: {
        type: 'string',
        default:'#000000',
    },
    titleTextHoverColor: {
        type: 'string',
        default:'#000000',
    },
    btnHref:{
        type:'string',
    },
    titlefontFamily: {
        type: 'string',
        default:'',
    },
    titlefontWeight: {
        type: 'string',
        default: '',
    },
    titlefontSubset: {
        type: 'string',
        default: '',
    },
    titlefontSizeType: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeMobile: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeTablet: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeDesktop: {
        type: 'number',
        default: '',
    },
    titlefontSizeMobile: {
        type: 'number',
        default: '',
    },
    titlefontSizeTablet: {
        type: 'number',
        default: '',
    },
    titlelineHeight: {
        type: 'string',
        default: ''
    },
    titlelineHeightMobile: {
        type: 'string',
        default: ''
    },
    titlelineHeightTablet: {
        type: 'string',
        default: ''
    },
    titleletterSpacing: {
        type: 'number',
        default: ''
    },
    titleletterSpacingMobile: {
        type: 'number',
        default: ''
    },
    titleletterSpacingTablet: {
        type: 'number',
        default: ''
    },
    titleletterSpacingType: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeTablet: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeMobile: {
        type: 'string',
        default: 'px'
    },
    titletextTransform: {
        type: 'string',
        default: ''
    },
    titletextDecoration: {
        type: 'string',
        default: ''
    },
    titlefontStyle: {
        type: 'string',
        default: ''
    },
    titleBorder:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    titleBorderMobile:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    titleBorderTablet:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    titleBorderRadius:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    titleBorderRadiusMobile:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    titleBorderRadiusTablet:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    titleBorderColor:{
		type: 'string',
        default: '#F9CC29',
    },
    titleBorderHoverColor:{
		type: 'string',
        default: '#61ce70'
    },
    titleBorderType:{
		type: 'string',
        default: ''
    },
}

export default attributes;