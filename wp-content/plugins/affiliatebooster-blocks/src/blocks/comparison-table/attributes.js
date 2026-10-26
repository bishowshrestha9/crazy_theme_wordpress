const attributes = {
	idtbl: {
		type: 'string',
	},
	block_id:{
		type: 'string',
		default: ''
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
            bgDefaultColor: '',   
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
            bgDefaultColor: '',
            bgGradient:{
                color1:'#aefa57',
                color2:'#f7da1e',
            },
        },
    },
    titleTag1: {
        type: 'string',
        default: 'p'
    },
    titleTag2: {
        type: 'string',
        default: 'p'
    },
    designType:{
        type: "string",
        default:'ab-fixed-design',
    },
    buttonIcon:{
        type: 'string',
        default:''
    },
    btnWidth:{
        type: 'string',
        default:'af-is-fullw'
    },
    iconPosition:{
        type: 'string',
        default:'af-icon-is-left'
    },
    tableStyle:{
        type: 'string',
		default: 'tbl-style-1'
    },
    showItemRating:{
        type: 'boolean',
        default: true
    },
    cntnLoadGoogleFonts:{
        type: 'boolean',
        default: false
	},
	thLoadGoogleFonts:{
        type: 'boolean',
        default: false
    },	
    showReadMore:{
        type: 'boolean',
        default: true
    },	
	tdbox: {
		type: 'array',
        default: [{fdimg: 'Image',fdprd: 'Electric Triviac ',fddetail: 'Detail',btnRel: 'nofollow',
            fdbtn: 'Price',fdbtnlref:'nofollow',itemRating:'9.5',badgeText:'Badge Text',titleText:'Donec blandit condimentum posuere',
            readMoreText:'Read our Review',btn_Htarget:false,link:'#',fdbtnurl:'#' }],
	},
	th1_txt: { 
		type: 'text',
		default: 'Image'
	},
	th2_txt: { 
		type: 'text',
		default: 'Product' 
	},
	th3_txt: { 
		type: 'text',
		default: 'Detail' 
	},
	th4_txt: { 
		type: 'text',
		default: 'Price' 
	},
    th1_width: { 
		type: 'text', default: '25'
	},
    th2_width: { 
		type: 'text',default: '25'
	},
    th3_width: { 
		type: 'text',default: '25'
	},
    th4_width: { 
		type: 'text',default: '25'
	},
    th1_width_2: { 
		type: 'text', default: '25'
	},
    th2_width_2: { 
		type: 'text', default: '25'
	},
    th3_width_2: { 
		type: 'text', default: '25'
	},
    th4_width_2: { 
		type: 'text', default: '25'
	},
	HTxtColor : { 
		type: 'string',	
		default: '#ffffff', 		
	},
	HBgColor : { 
		type: 'string',	
		default: '#30ac3d', 		
	},
	tOddColor : { 
		type: 'string',	
		default: '#f9f5e9', 		
	},
	tEvenColor : { 
		type: 'string',	
		default: '#ffffff', 		
	},
	btn_Htarget: {
		type: 'boolean',
		default: false
	},
	btn_Style: { 
		type: 'string',	
		default: 'btn-style-1', 		
	},
	tdTxtColor: { 
		type: 'string',	
		default: '#000000', 		
	},
	th_fontsize: {
		type: 'number',
		default: '18', 		
	},
	td_fontsize: {
		type: 'number',	
		default: '16', 			
	},
    enableGlimmerEffect:{
        type: 'boolean',
        default: false
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
        type: 'number',
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
        default: '#00ffcc'
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
    btnTarget:{
        type: 'boolean',
        default: false
    },
	// table heading
	thfontFamily: {
        type: 'string',
        default:'',
    },
    thfontWeight: {
        type: 'number',
        default: '',
    },
    thfontSubset: {
        type: 'string',
        default: '',
    },
    thfontSizeType: {
        type: 'string',
        default: 'px',
    },
    thfontSizeTypeMobile: {
        type: 'string',
        default: 'px',
    },
    thfontSizeTypeTablet: {
        type: 'string',
        default: 'px',
    },
    thfontSizeDesktop: {
        type: 'number',
        default: '',
    },
    thfontSizeMobile: {
        type: 'number',
        default: '',
    },
    thfontSizeTablet: {
        type: 'number',
        default: '',
    },
    thlineHeight: {
        type: 'string',
        default: ''
    },
    thlineHeightMobile: {
        type: 'string',
        default: ''
    },
    thlineHeightTablet: {
        type: 'string',
        default: ''
    },
    thletterSpacing: {
        type: 'number',
        default: ''
    },
    thletterSpacingMobile: {
        type: 'number',
        default: ''
    },
    thletterSpacingTablet: {
        type: 'number',
        default: ''
    },
    thletterSpacingType: {
        type: 'string',
        default: 'px'
    },
    thletterSpacingTypeTablet: {
        type: 'string',
        default: 'px'
    },
    thletterSpacingTypeMobile: {
        type: 'string',
        default: 'px'
    },
    thtextTransform: {
        type: 'string',
        default: ''
    },
    thtextDecoration: {
        type: 'string',
        default: ''
    },
    thfontStyle: {
        type: 'string',
        default: ''
	},
	thLoadGoogleFonts:{
		type: 'boolean',
        default: false
	},
	thBgColor:{
		type: 'string',
        default: ''
	},
	thHoverBgColor:{
		type: 'string',
        default: ''
	},
	thTextColor:{
		type: 'string',
        default: ''
	},
	thTextHoverColor:{
		type: 'string',
        default: ''
	},
	thBorderColor:{
		type: 'string',
        default: ''
	},
	thBorderHoverColor:{
		type: 'string',
        default: ''
	},
	// content 
	cntnfontFamily: {
        type: 'string',
        default:'',
    },
    cntnfontWeight: {
        type: 'number',
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
        default: '18',
    },
    cntnfontSizeMobile: {
        type: 'number',
        default: '18',
    },
    cntnfontSizeTablet: {
        type: 'number',
        default: '18',
    },
    cntnlineHeight: {
        type: 'number',
        default: ''
    },
    cntnlineHeightMobile: {
        type: 'number',
        default: ''
    },
    cntnlineHeightTablet: {
        type: 'number',
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
	btnBorderType:{
		type: 'string',
        default: ''
	},
	btnAlignment: {
        type: 'string',
        default:'',
    },
    btnAlignmentMobile: {
        type: 'string',
        default:'',
    },
    btnAlignmentTablet: {
        type: 'string',
        default:'',
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
    // Title settings
    titleLoadGoogleFonts:{
        type: 'boolean',
        default: false
    },
    titleBgColor: {
        type: 'string',
        default:'',
    },
    titleTextColor: {
        type: 'string',
        default:'#ec4422',
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
        default: '22',
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
    // badge setting
    badgeLoadGoogleFonts:{
        type: 'boolean',
        default: false
    },
    badgefontFamily: {
        type: 'string',
        default:'',
    },
    badgefontWeight: {
        type: 'number',
        default: '',
    },
    badgefontSubset: {
        type: 'string',
        default: '',
    },
    badgefontSizeType: {
        type: 'string',
        default: 'px',
    },
    badgefontSizeTypeMobile: {
        type: 'string',
        default: 'px',
    },
    badgefontSizeTypeTablet: {
        type: 'string',
        default: 'px',
    },
    badgefontSizeDesktop: {
        type: 'number',
        default: '',
    },
    badgefontSizeMobile: {
        type: 'number',
        default: '',
    },
    badgefontSizeTablet: {
        type: 'number',
        default: '',
    },
    badgelineHeight: {
        type: 'string',
        default: ''
    },
    badgelineHeightMobile: {
        type: 'string',
        default: ''
    },
    badgelineHeightTablet: {
        type: 'string',
        default: ''
    },
    badgeletterSpacing: {
        type: 'number',
        default: ''
    },
    badgeletterSpacingMobile: {
        type: 'number',
        default: ''
    },
    badgeletterSpacingTablet: {
        type: 'number',
        default: ''
    },
    badgeletterSpacingType: {
        type: 'string',
        default: 'px'
    },
    badgeletterSpacingTypeTablet: {
        type: 'string',
        default: 'px'
    },
    badgeletterSpacingTypeMobile: {
        type: 'string',
        default: 'px'
    },
    badgetextTransform: {
        type: 'string',
        default: ''
    },
    badgetextDecoration: {
        type: 'string',
        default: ''
    },
    badgefontStyle: {
        type: 'string',
        default: ''
    },
    showBadge:{
        type:'boolean',
        default:true
    },
    badgeTextColor:{
        type:'string',
        default:'#000000'
    },
    badgeBgColor:{
        type:'string',
        default:'#d2cfcf'
    },
    badgefontStyle:{
        type:'string',
        default:''
    },
    // readmore 
    readMoreLoadGoogleFonts:{
        type:'boolean',
        default:'false'
    },
    readMoreColor:{
        type:'string',
        default:'#000000'
    },
    readMorefontFamily: {
        type: 'string',
        default:'',
    },
    readMorefontWeight: {
        type: 'number',
        default: '',
    },
    readMorefontSubset: {
        type: 'string',
        default: '',
    },
    readMorefontSizeType: {
        type: 'string',
        default: 'px',
    },
    readMorefontSizeTypeMobile: {
        type: 'string',
        default: 'px',
    },
    readMorefontSizeTypeTablet: {
        type: 'string',
        default: 'px',
    },
    readMorefontSizeDesktop: {
        type: 'number',
        default: '',
    },
    readMorefontSizeMobile: {
        type: 'number',
        default: '',
    },
    readMorefontSizeTablet: {
        type: 'number',
        default: '',
    },
    readMorelineHeight: {
        type: 'string',
        default: ''
    },
    readMorelineHeightMobile: {
        type: 'string',
        default: ''
    },
    readMorelineHeightTablet: {
        type: 'string',
        default: ''
    },
    readMoreletterSpacing: {
        type: 'number',
        default: ''
    },
    readMoreletterSpacingMobile: {
        type: 'number',
        default: ''
    },
    readMoreletterSpacingTablet: {
        type: 'number',
        default: ''
    },
    readMoreletterSpacingType: {
        type: 'string',
        default: 'px'
    },
    readMoreletterSpacingTypeTablet: {
        type: 'string',
        default: 'px'
    },
    readMoreletterSpacingTypeMobile: {
        type: 'string',
        default: 'px'
    },
    readMoretextTransform: {
        type: 'string',
        default: ''
    },
    readMoretextDecoration: {
        type: 'string',
        default: ''
    },
    readMorefontStyle: {
        type: 'string',
        default: ''
    },
    // rating
    itemRatingBgColor: {
        type: 'string',
        default: '#30ac3d'
    },
	itemRatingTextColor: {
        type: 'string',
        default: '#ffffff'
    },


    showImage:{
        type: 'boolean',
        default: true
    },
    showProductTitle:{
        type: 'boolean',
        default: true
    },
    showProductContent:{
        type: 'boolean',
        default: true
    },
    showProductPrice:{
        type: 'boolean',
        default: true
    },

};

export default attributes;