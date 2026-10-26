const attributes = {
    uniqueId: {
        type: 'string',
        default: ''
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
    listTextItems: {
        type:'array',
        // source: 'children',
        filterElements: true,
        // selector: '.test-list',
        default:[],
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
    designType:{
        type: "string",
        default:'ab-fixed-design',
    },
    listItems: {
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
    customImgWidth:{
        type: "string",
        default:'',
    },
    customImgWidthMobile:{
        type: "string",
        default:'',
    },
    customImgWidthTablet:{
        type: "string",
        default:'',
    },
    listType: {
        type: 'string',
        default: 'unordered',
    },
    btn_Htarget:{
        type: 'boolean',
        default: false
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
    boxBorderType:{
        type: 'string',
        default:'',
    },
    boxBorderColor:{
        type: 'string',
        default:'#006fbf',
    },

    boxBackgroundColor:{
        type: 'string',
        default:'#ffffff',
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
    boxShadow:{
        type: 'object',
        default: {
            openShadow: true,
            inset:'',
            horizontal: 0,
            vertical: 8,
            blur: 35,
            spread: 0,
            color:'rgba(0, 0, 0, 0.14)'
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
    image: { 
        type: 'object', 
        default: {
            id:'',
            url:'',
            alt:'',
        } 
    },
    imageSize:{
		type: "string",
		default: "full",
    },
    imagePadding:{
        type: 'object', 
        default: []
    },
    imagePaddingMobile:{
        type: 'object', 
        default: []
    },
    imagePaddingTablet:{
        type: 'object', 
        default: []
    },
    mediaID: {
        type: 'number',
    },
    mediaURL: {
        type: 'string',
    },
    spTitle:{
        type: 'string',
        default: 'Title'
    },
    spSubTitle:{
        type: 'string',
        default: 'Sub Title'
    },
    spSubTitleColor:{
        type: 'string',
        default: '#000000'
    },
    spContent:{
        type:'array',
        default: [],
    },
    spBtnText:{
        type: 'string',
        default: 'Buy Now'
    },
    spBtnHref:{
        type: 'string',
        default: ''
    },
    titleLoadGoogleFonts:{
        type: 'boolean',
        default: false
    },
    subtitleLoadGoogleFonts:{
        type: 'boolean',
        default: false
    },
    recreateStyles: {
        type: 'boolean',
        default: true,
    },
    titleAlignment: {
        type: 'string',
        default: 'center',
    },
    titleAlignmentMobile: {
        type: 'string',
        default: '',
    },
    titleAlignmentTablet: {
        type: 'string',
        default: '',
    },
    subtitleAlignment: {
        type: 'string',
        default: 'left',
    },
    cntnAlignment:{
        type: 'string',
        default: 'left',
    },
    cntnAlignmentMobile:{
        type: 'string',
        default: '',
    },
    cntnAlignmentTablet:{
        type: 'string',
        default: '',
    },
    subtitleAlignmentMobile: {
        type: 'string',
        default: 'center',
    },
    subtitleAlignmentTablet: {
        type: 'string',
        default: 'left',
    },
    layout: {
        type: 'string',
        default: 'fill'
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
        default: {
            openBorderRadius: 1,
            radiusType: 'global'
        },
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
        default: '32',
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
    // Sub title
    subtitlefontFamily: {
        type: 'string',
        default:'',
    },
    subtitlefontWeight: {
        type: 'string',
        default: '900',
    },
    subtitlefontSubset: {
        type: 'string',
        default: '',
    },
    subtitlefontSizeType: {
        type: 'string',
        default: 'px',
    },
    subtitlefontSizeTypeMobile: {
        type: 'string',
        default: 'px',
    },
    subtitlefontSizeTypeTablet: {
        type: 'string',
        default: 'px',
    },
    subtitlefontSizeDesktop: {
        type: 'number',
        default: '28',
    },
    subtitlefontSizeMobile: {
        type: 'number',
        default: '',
    },
    subtitlefontSizeTablet: {
        type: 'number',
        default: '',
    },
    subtitlelineHeight: {
        type: 'number',
        default: ''
    },
    subtitlelineHeightMobile: {
        type: 'number',
        default: ''
    },
    subtitlelineHeightTablet: {
        type: 'number',
        default: ''
    },
    subtitleletterSpacing: {
        type: 'number',
        default: ''
    },
    subtitleletterSpacingMobile: {
        type: 'number',
        default: ''
    },
    subtitleletterSpacingTablet: {
        type: 'number',
        default: ''
    },
    subtitleletterSpacingType: {
        type: 'string',
        default: 'px'
    },
    subtitleletterSpacingTypeTablet: {
        type: 'string',
        default: 'px'
    },
    subtitleletterSpacingTypeMobile: {
        type: 'srting',
        default: 'px'
    },
    subtitletextTransform: {
        type: 'string',
        default: ''
    },
    subtitletextDecoration: {
        type: 'string',
        default: ''
    },
    subtitlefontStyle: {
        type: 'string',
        default: ''
    },
    subtitleTextColor: {
        type: 'string',
        default: '#000000'
    },
    // CTA BUTTON
    btnShow:{
        type: 'boolean',
        default: true
    },
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
        default:'#F9CC29',
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
        default: '#F9CC29'
	},
	btnBorderHoverColor:{
		type: 'string',
        default: '#61ce70'
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
    mediaID:{
        type:'number',
        default:''
    },
    mediaURL:{
        type:'string',
        default:''
    },
    bntRounded:{
        type: 'boolean',
        default: false
    },
    btnSize:{
        type: 'string',
        default:'small'
    },
    buttonWidth:{
        type: 'string',
        default:'full'
    },
    buttonIcon:{
        type: 'string',
        default:''
    },
    iconPosition:{
        type: 'string',
        default:'af-icon-is-left'
    },
    iconColor:{
        type: 'string',
        default:'#30ac3d'
    },
    btnAlignment: {
        type: 'string',
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
    btnBorderColor:{
		type: 'string',
        default: '#F9CC29'
    },
    btnBorderHoverColor:{
		type: 'string',
        default: '#61ce70'
    },
    btnBorderType:{
		type: 'string',
        default: ''
    },
    contentType:{
        type: 'string',
        default: 'is-list'
    },
    pContent:{
        type: 'string',
        default: 'Cras aliquet, dui quis lobortis dictum, justo nisl interdum diam, eget euismod arcu risus et tellus. Quisque interdum malesuada augue, at tristique odio scelerisque sed.'
    },

    externalImage:{
        type: 'boolean',
        default: false
    },
    externalImageSrc:{
        type: "string",
        default:'',
    },

    externalImageAlt:{
        type: "string",
        default:'',
    },

    showTitle:{
        type: 'boolean',
        default: true
    },
    showSubTitle:{
        type: 'boolean',
        default: true
    },
    showContent:{
        type: 'boolean',
        default: true
    },
}


export default attributes;