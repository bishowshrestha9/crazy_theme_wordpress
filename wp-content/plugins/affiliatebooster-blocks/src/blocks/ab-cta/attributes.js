const attributes = {
    uniqueId: {
        type: 'string',
        default: ''
    },
    boxBg: {
        type: 'object',
        default: {
            openBg : false,
            bgimgPosition: 'center center',
            bgimgSize: 'cover',
            bgimgRepeat: 'no-repeat',
            bgDefaultColor: '#f5f5f5',
            bgimageSource: 'local',
            externalImageUrl: {}
        },
    },
    boxShadow:{
        type: 'object',
        default: {
            openShadow: true,
            blur:35,
            color:"rgba(0, 0, 0, 0.14)",
            inset : 0,
            spread : 0,
            horizontal: 0,
            vertical : 8
        }
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
    block_id: {
        type: 'string',
    },
    titleTag1: {
        type: 'string',
        default: 'p'
    },
    titleTag2: {
        type: 'string',
        default: 'p'
    },
    btnRel: {
        type: 'string',
        default: 'nofollow'
    },

    boxBorderType:{
        type: 'string',
        default:'',
    },
    boxBorderColor:{
        type: 'string',
        default:'#006fbf',
    },
    boxBorderWidth:{
        type: 'object',
        default: {
            unit:'px',
            top: 8,
            right: 8,
            bottom: 8,
            left: 8,
        }
    },

    boxAlignment: {
        type: 'string',
    },
    boxAlignmentMobile: {
        type: 'string',
    },
    boxAlignmentTablet: {
        type: 'string',
    },
    boxStyle: {
        type: 'string',
        default:'box-style-1',
    },
    boxPadding:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    boxPaddingTablet:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    boxPaddingMobile:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    boxMargin:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    boxMarginTablet:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    boxMarginMobile:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    ctaTitle:{
        type: 'string',
        default: 'CTA Title'
    },
    ctaContent:{
        type: 'string',
        default: 'Nullam et nibh ac nulla commodo ultricies ac in dui. Mauris mollis leo vel nulla scelerisque lobortis. Nam ultrices dui eget suscipit euismod. Duis id metus condimentum, interdum mauris a, venenatis sapien. Mauris felis libero, ultricies vitae dolor sit amet,'
    },
    ctaBtnText:{
        type: 'string',
        default: 'Buy Now'
    },
    ctaBtnHref:{
        type: 'string',
        default: ''
    },
    titleLoadGoogleFonts:{
        type: 'boolean',
        default: false
    },
    listLoadGoogleFonts:{
        type: 'boolean',
        default: false
    },
    recreateStyles: {
        type: 'boolean',
        default: true,
    },
    listType: {
        type: 'string',
        default: 'unordered',
    },
    alignment: {
        type: 'string',
        default: 'left',
    },
    layout: {
        type: 'string',
        default: 'fill'
    },
    listItems: {
        type: 'array',
        default: ['']
    },
    listItemsTwo: {
        type: 'array',
        default: ['']
    },
    bulletStyle: {
        type: 'object',
        default: {
            name: 'check-circle-outline',
            value: 'far fa-check-circle'
        }
    },
    bulletStyleTwo: {
        type: 'object',
        default: {
            name: 'check-circle-outline',
            value: 'far fa-check-circle'
        }
    },
    bulletSize: {
        type: 'string',
        default: '16px',
    },
    bulletSizeCustom: {
        type: 'object',
        default: {
            md: 18,
            unit: 'px'
        },
    },
    bulletColor: {
        type: 'string',
        default: '',
    },
    bulletColorHover: {
        type: 'string',
        default: '',
    },
    useNumberBg: {
        type: 'boolean',
        default: true
    },
    numberFontSize: {
        type: 'string',
        default: '14',
    },
    numberBgSize: {
        type: 'string',
        default: '5',
    },
    numberBg: {
        type: 'string',
        default: '#c2e5ff',
    },
    numberBgHover: {
        type: 'string',
        default: '',
    },
    numberCorner: {
        type: 'string',
        default: '50',
    },
    color: {
        type: 'string',
        default: '#333',
    },
    colorHover: {
        type: 'string',
        default: '',
    },
    spacing: {
        type: 'object',
        default: {
            md: 5, unit: 'px'
        },
    },
    backgroundSize: {
        type: 'object',
        default: {
            openPadding: 1,
            paddingType: 'global',
            global: {
                md: '10',
                unit: 'px'
            }
        },
    },
    background: {
        type: 'string',
        default: '#f5f5f5',
    },
    backgroundHover: {
        type: 'string',
        default: '',
    },
    borderRadius: {
        type: 'object',
        default: '',
    },
    titleBgColor: {
        type: 'string',
        default:'',
    },
    titleTextColor: {
        type: 'string',
        default:'#000000',
    },
    titlefontFamily: {
        type: 'string',
        default:'',
    },
    titlefontWeight: {
        type: 'string',
        default: '900',
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
        default: '36',
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
    // 
    listfontFamily: {
        type: 'string',
        default:'',
    },
    listfontWeight: {
        type: 'string',
        default: '',
    },
    listfontSubset: {
        type: 'string',
        default: '',
    },
    listfontSizeType: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeMobile: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeTablet: {
        type: 'string',
        default: 'px',
    },
    listfontSizeDesktop: {
        type: 'number',
        default: '',
    },
    listfontSizeMobile: {
        type: 'number',
        default: '',
    },
    listfontSizeTablet: {
        type: 'number',
        default: '',
    },
    listlineHeight: {
        type: 'number',
        default: ''
    },
    listlineHeightMobile: {
        type: 'number',
        default: ''
    },
    listlineHeightTablet: {
        type: 'number',
        default: ''
    },
    listletterSpacing: {
        type: 'number',
        default: ''
    },
    listletterSpacingMobile: {
        type: 'number',
        default: ''
    },
    listletterSpacingTablet: {
        type: 'number',
        default: ''
    },
    listletterSpacingType: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeTablet: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeMobile: {
        type: 'string',
        default: 'px'
    },
    listtextTransform: {
        type: 'string',
        default: ''
    },
    listtextDecoration: {
        type: 'string',
        default: ''
    },
    listfontStyle: {
        type: 'string',
        default: ''
    },
    listTextColor: {
        type: 'string',
        default: ''
    },
    propsBgColor: {
        type: 'string',
        default: ''
    },
    propsIconColor: {
        type: 'string',
        default: ''
    },
    propsTextColor: {
        type: 'string',
        default: ''
    },
    consBgColor: {
        type: 'string',
        default: ''
    },
    consTextColor: {
        type: 'string',
        default: ''
    },
    consIconColor: {
        type: 'string',
        default: ''
    },
    consListColor: {
        type: 'string',
        default: ''
    },
    propsListColor: {
        type: 'string',
        default: ''
    },
    // CTA BUTTON
    btnLoadGoogleFonts:{
        type: 'boolean',
        default: false
	},
    btnBgColor: {
        type: 'string',
        default:'#F9CC29',
    },
    btnTextColor: {
        type: 'string',
        default:'#000000',
    },
    btnfontFamily: {
        type: 'string',
        default:'',
    },
    btnfontWeight: {
        type: 'string',
        default: '',
    },
    btnfontSubset: {
        type: 'string',
        default: '',
    },
    btnfontSizeType: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeTypeMobile: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeTypeTablet: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeDesktop: {
        type: 'number',
        default: '',
    },
    btnfontSizeMobile: {
        type: 'number',
        default: '',
    },
    btnfontSizeTablet: {
        type: 'number',
        default: '',
    },
    btnlineHeight: {
        type: 'string',
        default: ''
    },
    btnlineHeightMobile: {
        type: 'string',
        default: ''
    },
    btnlineHeightTablet: {
        type: 'string',
        default: ''
    },
    btnletterSpacing: {
        type: 'number',
        default: ''
    },
    btnletterSpacingMobile: {
        type: 'number',
        default: ''
    },
    btnletterSpacingTablet: {
        type: 'number',
        default: ''
    },
    btnletterSpacingType: {
        type: 'string',
        default: 'px'
    },
    btnletterSpacingTypeTablet: {
        type: 'string',
        default: 'px'
    },
    btnletterSpacingTypeMobile: {
        type: 'string',
        default: 'px'
    },
    btntextTransform: {
        type: 'string',
        default: ''
    },
    btntextDecoration: {
        type: 'string',
        default: ''
    },
    btnfontStyle: {
        type: 'string',
        default: ''
	},
	btnLoadGoogleFonts:{
		type: 'boolean',
        default: false
	},
	btnBgColor:{
		type: 'string',
        default: '#F9CC29'
	},
	btnHoverBgColor:{
		type: 'string',
        default: '#61ce70'
	},
	btnTextColor:{
		type: 'string',
        default: '#000000'
	},
	btnTextHoverColor:{
		type: 'string',
        default: '#000000'
	},
	btnBorderColor:{
		type: 'string',
        default: '#61ce70'
	},
	btnBorderHoverColor:{
		type: 'string',
        default: '#F9CC29'
    },
    btn_Htarget:{
        type: 'boolean',
        default: false
    },
    showTitle:{
        type: 'boolean',
        default: true
    },
    showContent:{
        type: 'boolean',
        default: true
    },
    showButton:{
        type: 'boolean',
        default: true
    },
    // CTA Content
	cntnfontFamily: {
        type: 'string',
        default:'',
    },
    cntnfontWeight: {
        type: 'string',
        default: '',
    },
    cntnfontSubset: {
        type: 'string',
        default: '',
    },
    cntnfontSizeType: {
        type: 'string',
        default: 'px',
    },
    cntnfontSizeTypeMobile: {
        type: 'string',
        default: 'px',
    },
    cntnfontSizeTypeTablet: {
        type: 'string',
        default: 'px',
    },
    cntnfontSizeDesktop: {
        type: 'number',
        default: '',
    },
    cntnfontSizeMobile: {
        type: 'number',
        default: '',
    },
    cntnfontSizeTablet: {
        type: 'number',
        default: '',
    },
    cntnlineHeight: {
        type: 'string',
        default: ''
    },
    cntnlineHeightMobile: {
        type: 'string',
        default: ''
    },
    cntnlineHeightTablet: {
        type: 'string',
        default: ''
    },
    cntnletterSpacing: {
        type: 'number',
        default: ''
    },
    cntnletterSpacingMobile: {
        type: 'number',
        default: ''
    },
    cntnletterSpacingTablet: {
        type: 'number',
        default: ''
    },
    cntnletterSpacingType: {
        type: 'string',
        default: 'px'
    },
    cntnletterSpacingTypeTablet: {
        type: 'string',
        default: 'px'
    },
    cntnletterSpacingTypeMobile: {
        type: 'string',
        default: 'px'
    },
    cntntextTransform: {
        type: 'string',
        default: ''
    },
    cntntextDecoration: {
        type: 'string',
        default: ''
    },
    cntnfontStyle: {
        type: 'string',
        default: ''
	},
	cntnLoadGoogleFonts:{
		type: 'boolean',
        default: false
	},
	cntnBgColor:{
		type: 'string',
        default: ''
	},
	cntnHoverBgColor:{
		type: 'string',
        default: ''
	},
	cntnTextColor:{
		type: 'string',
        default: '#000000'
	},
	cntnTextHoverColor:{
		type: 'string',
        default: ''
	},
	cntnBorderColor:{
		type: 'string',
        default: ''
	},
	cntnBorderHoverColor:{
		type: 'string',
        default: ''
    },
    bgOverlay:{
		type: 'string',
        default: ''
    },
    btnAlignment: {
        type: 'string',
        default:'center'
    },
    btnAlignmentMobile: {
        type: 'string',
    },
    btnAlignmentTablet: {
        type: 'string',
    },
    btnPadding:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnPaddingTablet:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnPaddingMobile:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMargin:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMarginTablet:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMarginMobile:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorder:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderMobile:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderTablet:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadius:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadiusMobile:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadiusTablet:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderType:{
		type: 'string',
        default: ''
    },
    btnSize:{
        type: 'string',
        default:'small'
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
        default:'af-icon-is-left'
    },


}

export default attributes;