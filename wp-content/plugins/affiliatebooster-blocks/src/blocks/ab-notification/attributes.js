const attributes = {
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
    block_id: {
        type: 'string',
    },
    titleAlignment: {
        type: 'string',
    },
    titleAlignmentMobile: {
        type: 'string',
    },
    titleAlignmentTablet: {
        type: 'string',
    },
    cntnAlignment: {
        type: 'string',
    },
    cntnAlignmentMobile: {
        type: 'string',
    },
    cntnAlignmentTablet: {
        type: 'string',
    },
    boxStyle: {
        type: 'string',
        default:'box-style-1',
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
    noticeTitleBgColor:{
        type: 'string',
        default: ''
    },
    noticeContent:{
        type: 'string',
        default: 'Nullam et nibh ac nulla commodo ultricies ac in dui. Mauris mollis leo vel nulla scelerisque lobortis. Nam ultrices dui eget suscipit euismod. Duis id metus condimentum, interdum mauris a, venenatis sapien. Mauris felis libero, ultricies vitae dolor sit amet,'
    },
    noticeBtnText:{
        type: 'string',
        default: 'notice Button'
    },
    titleTag1: {
        type: 'string',
        default: 'p'
    },
    noticeBtnHref:{
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
    titleBgColor: {
        type: 'string',
        default:'',
    },
    titleTextColor: {
        type: 'string',
        default:'',
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
        default:''
    },
    listletterSpacingTypeMobile: {
        type: 'string',
        default: ''
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
    // notice BUTTON
    btnLoadGoogleFonts:{
        type: 'boolean',
        default: false
	},
    btnBgColor: {
        type: 'string',
        default:'',
    },
    btnTextColor: {
        type: 'string',
        default:'',
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
        default: ''
	},
	btnHoverBgColor:{
		type: 'string',
        default: ''
	},
	btnTextColor:{
		type: 'string',
        default: ''
	},
	btnTextHoverColor:{
		type: 'string',
        default: ''
	},
	btnBorderColor:{
		type: 'string',
        default: ''
	},
	btnBorderHoverColor:{
		type: 'string',
        default: ''
    },
    // notice Content
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
        default: '#fff5b3'
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
        default: '#e9df9a'
    },
    cntnBorderType:{
        type: 'string',
        default: 'solid'
    },
	cntnBorderHoverColor:{
		type: 'string',
        default: ''
    },
    cntnMargin:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    cntnMarginMobile:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    cntnMarginTablet:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    cntnPadding:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    cntnPaddingMobile:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    cntnPaddingTablet:{
        type: 'object',
        default: {
            unit:'px',
        }
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
        default: ''
    },
    titleBorderType:{
		type: 'string',
        default: ''
    },
    // Content
    cntnBorder:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    cntnBorderMobile:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    cntnBorderTablet:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    cntnBorderRadius:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    cntnBorderRadiusMobile:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    cntnBorderRadiusTablet:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    customWidth:{
        type:'number',
        default:'',
    },
    customWidthMobile:{
        type:'number',
        default:'',
    },
    customWidthTablet:{
        type:'number',
        default:'',
    },
    customWidthType:{
        type:'string',
        default:'%',
    },
    customWidthTypeMobile:{
        type:'string',
        default:'%',
    },
    customWidthTypeTablet:{
        type:'string',
        default:'%',
    }
}

export default attributes;