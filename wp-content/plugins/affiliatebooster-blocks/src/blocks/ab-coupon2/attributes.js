const attributes = {
    uniqueId: {
        type: 'string',
        default: ''
    },
    block_id: {
        type: 'string',
        default: ''
    },
    numberOfBlock: {
        type:'number',
        default:2
    }, 

    numberOfCol: {
        type:'number',
        default:2
    }, 

    productColumnStyle: { 
        type: 'string',
        default: 'layout1'
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
    minRate:{
        type: 'string',
        default: '9.5'
    },
    maxRate:{
        type: 'string',
        default: '10'
    },


    boxPadding:{
        type: 'object',
        default: {
            top:'0',
            right:'15',
            bottom:'0',
            left:'15',
            unit:'px',
        }
    },
    boxPaddingMobile:{
        type: 'object', 
        default: {
            top:'0',
            right:'15',
            bottom:'0',
            left:'15',
            unit:'px',
        }
    },
    boxPaddingTablet:{
        type: 'object', 
        default: {
            top:'0',
            right:'15',
            bottom:'0',
            left:'15',
            unit:'px',
        }
    },

    boxMargin:{
        type: 'object', 
        default: {
            top:'0',
            right:'-40',
            bottom:'0',
            left:'-40',
            unit:'px',
        }
    },
    boxMarginMobile:{
        type: 'object', 
        default: {
            top:'0',
            right:'-40',
            bottom:'0',
            left:'-40',
            unit:'px',
        }
    },
    boxMarginTablet:{
        type: 'object', 
        default: {
            top:'0',
            right:'-40',
            bottom:'0',
            left:'-40',
            unit:'px',
        }
    },

    //Box 1
    boxBgColor1: {
        type: 'string',
        default:'#ffffff',
    },
    flagStyle1: {
        type: 'string',
        default: ''
    },
    ratings1:{
        type:'number',
        default:5
    },
    ratingsColor1:{
        type: 'string',
        default: '#ffa500'
    },
    selectedStars1: {
        type: 'number',
        default: 0
    },
    starsSize1:{
        type:'string',
        default: 18
    },
    starsSizeMobile1:{
        type:'string',
        default: 18
    },
    starsSizeTablet1:{
        type:'string',
        default: 18
    },
    
    titleTag1: {
        type: 'string',
        default: 'p'
    }, 
    titleText1:{
        type: 'string',
        default: 'Product Headline Goes Here'
    },
    titleLoadGoogleFonts1:{
        type: 'boolean',
        default: false
    },


    titleTextColor1: {
        type: 'string',
        default:'#000000',
    },
    titlefontFamily1: {
        type: 'string',
        default:'',
    },
    titlefontWeight1: {
        type: 'string',
        default: '900',
    },
    titlefontSubset1: {
        type: 'string',
        default: '',
    },
    titlefontSizeType1: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeMobile1: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeTablet1: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeDesktop1: {
        type: 'number',
        default: '18',
    },
    titlefontSizeMobile1: {
        type: 'number',
        default: '',
    },
    titlefontSizeTablet1: {
        type: 'number',
        default: '',
    },
    titlelineHeight1: {
        type: 'string',
        default: ''
    },
    titlelineHeightMobile1: {
        type: 'string',
        default: ''
    },
    titlelineHeightTablet1: {
        type: 'string',
        default: ''
    },
    titleletterSpacing1: {
        type: 'number',
        default: ''
    },
    titleletterSpacingMobile1: {
        type: 'number',
        default: ''
    },
    titleletterSpacingTablet1: {
        type: 'number',
        default: ''
    },
    titleletterSpacingType1: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeTablet1: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeMobile1: {
        type: 'string',
        default: 'px'
    },
    titletextTransform1: {
        type: 'string',
        default: ''
    },
    titletextDecoration1: {
        type: 'string',
        default: ''
    },
    titlefontStyle1: {
        type: 'string',
        default: ''
    },

    image1: { 
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
    imagePadding1:{
        type: 'object', 
        default: []
    },
    imagePaddingMobile1:{
        type: 'object', 
        default: []
    },
    imagePaddingTablet1:{
        type: 'object', 
        default: []
    },
    customImgWidth1:{
        type: "string",
        default:'',
    },
    customImgWidthMobile1:{
        type: "string",
        default:'',
    },
    customImgWidthTablet1:{
        type: "string",
        default:'',
    },
    imageDesignType1:{
        type: "string",
        default:'ab-fixed-design',
    },
    listItems1: {
        type: 'array',
        default: ['']
    },
    listTextItems1: {
        type:'array',
        filterElements: true, 
        default:[],
    },
    listType1: {
        type: 'string',
        default: 'unordered',
    },    
    alignment1: {
        type: 'string',
        default: 'left',
    },
    listLoadGoogleFonts1:{
        type: 'boolean',
        default: false
    },
    listfontFamily1: {
        type: 'string',
        default:'',
    },
    listfontWeight1: {
        type: 'string',
        default: '',
    },

    listfontSubset1: {
        type: 'string',
        default: '',
    },
    listfontSizeType1: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeMobile1: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeTablet1: {
        type: 'string',
        default: 'px',
    },
    listfontSizeDesktop1: {
        type: 'number',
        default: '16',
    },
    listfontSizeMobile1: {
        type: 'number',
        default: '',
    },
    listfontSizeTablet1: {
        type: 'number',
        default: '',
    },
    listlineHeight1: {
        type: 'string',
        default: ''
    },
    listlineHeightMobile1: {
        type: 'string',
        default: ''
    },
    listlineHeightTablet1: {
        type: 'string',
        default: ''
    },
    listletterSpacing1: {
        type: 'number',
        default: ''
    },
    listletterSpacingMobile1: {
        type: 'number',
        default: ''
    },
    listletterSpacingTablet1: {
        type: 'number',
        default: ''
    },
    listletterSpacingType1: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeTablet1: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeMobile1: {
        type: 'string',
        default: 'px'
    },
    listtextTransform1: {
        type: 'string',
        default: ''
    },
    listtextDecoration1: {
        type: 'string',
        default: ''
    },
    listfontStyle1: {
        type: 'string',
        default: ''
    },


    bulletStyle1: {
        type: 'object',
        default: {
            name: 'check',
            value: 'far fa-check'
        }
    },
    numberCorner1: {
        type: 'string',
        default: '50',
    },
    numberFontSize1: {
        type: 'string',
        default: '14',
    },
    numberBgSize1: {
        type: 'string',
        default: '5',
    },
    useNumberBg1: {
        type: 'boolean',
        default: true
    },
    listTextColor1: {
        type: 'string',
        default: '#000000'
    },
    listIconColor1: {
        type: 'string',
        default: '#2ead3c'
    },
    listColor1: {
        type: 'string',
        default: '#000000'
    },
    recreateStyles1: {
        type: 'boolean',
        default: true,
    },
    btnLoadGoogleFonts1:{
        type: 'boolean',
        default: false
    },
    btnfontFamily1: {
        type: 'string',
        default:'',
    },
    btnfontWeight1: {
        type: 'string',
        default: '',
    },
    btnfontSubset1: {
        type: 'string',
        default: '',
    },
    btnfontSizeType1: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeTypeMobile1: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeTypeTablet1: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeDesktop1: {
        type: 'number',
        default: '18',
    },
    btnfontSizeMobile1: {
        type: 'number',
        default: '',
    },
    btnfontSizeTablet1: {
        type: 'number',
        default: '',
    },
    btnlineHeight1: {
        type: 'string',
        default: ''
    },
    btnlineHeightMobile1: {
        type: 'string',
        default: ''
    },
    btnlineHeightTablet1: {
        type: 'string',
        default: ''
    },
    btnletterSpacing1: {
        type: 'number',
        default: ''
    },
    btnletterSpacingMobile1: {
        type: 'number',
        default: ''
    },
    btnletterSpacingTablet1: {
        type: 'number',
        default: ''
    },
    btnletterSpacingType1: {
        type: 'string',
        default: 'px'
    },
    btnletterSpacingTypeTablet1: {
        type: 'string',
        default: 'px'
    },
    btnletterSpacingTypeMobile1: {
        type: 'string',
        default: 'px'
    },
    btntextTransform1: {
        type: 'string',
        default: ''
    },
    btntextDecoration1: {
        type: 'string',
        default: ''
    },
    btnfontStyle1: {
        type: 'string',
        default: ''
    },
    btnBgColor1:{
        type: 'string',
        default:'#F9CC29',
    },
    btnHoverBgColor1:{
        type: 'string',
        default: '#00ffcc'
    },
    btnTextColor1:{
        type: 'string',
        default: ''
    },
    btnTextHoverColor1:{
        type: 'string',
        default: ''
    },
    btnBorderColor1:{
        type: 'string',
        default: ''
    },
    btnBorderType1:{
        type: 'string',
        default: ''
    },
    btnBorderHoverColor1:{
        type: 'string',
        default: ''
    },
    btn_Htarget1:{
        type: 'boolean',
        default: false
    },
    spBtnText1:{
        type: 'string',
        default: 'Activate Deal'
    },
    spBtnHref1:{
        type: 'string',
        default: ''
    },
    btnPadding1:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnPaddingTablet1:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnPaddingMobile1:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMargin1:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMarginTablet1:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMarginMobile1:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnAlignment1: {
        type: 'string',
    },
    btnAlignmentMobile1: {
        type: 'string',
    },
    btnAlignmentTablet1: {
        type: 'string',
    },
    btnBorder1:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderMobile1:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderTablet1:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadius1:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadiusMobile1:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadiusTablet1:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    iconPosition1:{
        type: 'string',
        default:'af-icon-is-left'
    },
    btnSize1:{
        type: 'string',
        default:'small'
    },
    buttonIcon1:{
        type: 'string',
        default:''
    },
    buttonWidth1:{
        type: 'string',
        default:'full'
    },
    bntRounded1:{
        type: 'boolean',
        default: false
    },
    btnRel1: {
        type: 'string',
        default: 'nofollow'
    },





    //Box 2
    boxBgColor2: {
        type: 'string',
        default:'#ffffff',
    },
    flagStyle2: {
        type: 'string',
        default: ''
    },
    ratings2:{
        type:'number',
        default:5
    },
    ratingsColor2:{
        type: 'string',
        default: '#ffa500'
    },
    selectedStars2: {
        type: 'number',
        default: 0
    },
    starsSize2:{
        type:'string',
        default: 18
    },
    starsSizeMobile2:{
        type:'string',
        default: 18
    },
    starsSizeTablet2:{
        type:'string',
        default: 18
    },
    titleTag2: {
        type: 'string',
        default: 'p'
    }, 
    titleText2:{
        type: 'string',
        default: 'Product Headline Goes Here'
    },
    titleLoadGoogleFonts2:{
        type: 'boolean',
        default: false
    },
    titleTextColor2: {
        type: 'string',
        default:'#000000',
    },
    titlefontFamily2: {
        type: 'string',
        default:'',
    },
    titlefontWeight2: {
        type: 'string',
        default: '900',
    },
    titlefontSubset2: {
        type: 'string',
        default: '',
    },
    titlefontSizeType2: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeMobile2: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeTablet2: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeDesktop2: {
        type: 'number',
        default: '18',
    },
    titlefontSizeMobile2: {
        type: 'number',
        default: '',
    },
    titlefontSizeTablet2: {
        type: 'number',
        default: '',
    },
    titlelineHeight2: {
        type: 'string',
        default: ''
    },
    titlelineHeightMobile2: {
        type: 'string',
        default: ''
    },
    titlelineHeightTablet2: {
        type: 'string',
        default: ''
    },
    titleletterSpacing2: {
        type: 'number',
        default: ''
    },
    titleletterSpacingMobile2: {
        type: 'number',
        default: ''
    },
    titleletterSpacingTablet2: {
        type: 'number',
        default: ''
    },
    titleletterSpacingType2: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeTablet2: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeMobile2: {
        type: 'string',
        default: 'px'
    },
    titletextTransform2: {
        type: 'string',
        default: ''
    },
    titletextDecoration2: {
        type: 'string',
        default: ''
    },
    titlefontStyle2: {
        type: 'string',
        default: ''
    },
    image2: { 
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
    imagePadding2:{
        type: 'object', 
        default: []
    },
    imagePaddingMobile2:{
        type: 'object', 
        default: []
    },
    imagePaddingTablet2:{
        type: 'object', 
        default: []
    },
    customImgWidth2:{
        type: "string",
        default:'',
    },
    customImgWidthMobile2:{
        type: "string",
        default:'',
    },
    customImgWidthTablet2:{
        type: "string",
        default:'',
    },
    imageDesignType2:{
        type: "string",
        default:'ab-fixed-design',
    },
    listItems2: {
        type: 'array',
        default: ['']
    },
    listTextItems2: {
        type:'array',
        filterElements: true,
        default:[],
    },
    listType2: {
        type: 'string',
        default: 'unordered',
    },    
    alignment2: {
        type: 'string',
        default: 'left',
    },
    listLoadGoogleFonts2:{
        type: 'boolean',
        default: false
    },
    listfontFamily2: {
        type: 'string',
        default:'',
    },
    listfontWeight2: {
        type: 'string',
        default: '',
    },

    listfontSubset2: {
        type: 'string',
        default: '',
    },
    listfontSizeType2: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeMobile2: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeTablet2: {
        type: 'string',
        default: 'px',
    },
    listfontSizeDesktop2: {
        type: 'number',
        default: '16',
    },
    listfontSizeMobile2: {
        type: 'number',
        default: '',
    },
    listfontSizeTablet2: {
        type: 'number',
        default: '',
    },
    listlineHeight2: {
        type: 'string',
        default: ''
    },
    listlineHeightMobile2: {
        type: 'string',
        default: ''
    },
    listlineHeightTablet2: {
        type: 'string',
        default: ''
    },
    listletterSpacing2: {
        type: 'number',
        default: ''
    },
    listletterSpacingMobile2: {
        type: 'number',
        default: ''
    },
    listletterSpacingTablet2: {
        type: 'number',
        default: ''
    },
    listletterSpacingType2: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeTablet2: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeMobile2: {
        type: 'string',
        default: 'px'
    },
    listtextTransform2: {
        type: 'string',
        default: ''
    },
    listtextDecoration2: {
        type: 'string',
        default: ''
    },
    listfontStyle2: {
        type: 'string',
        default: ''
    },

    bulletStyle2: {
        type: 'object',
        default: {
            name: 'check',
            value: 'far fa-check'
        }
    },
    numberCorner2: {
        type: 'string',
        default: '50',
    },
    numberFontSize2: {
        type: 'string',
        default: '14',
    },
    numberBgSize2: {
        type: 'string',
        default: '5',
    },
    useNumberBg2: {
        type: 'boolean',
        default: true
    },
    listTextColor2: {
        type: 'string',
        default: '#000000'
    },
    listIconColor2: {
        type: 'string',
        default: '#2ead3c'
    },
    listColor2: {
        type: 'string',
        default: '#000000'
    },
    recreateStyles2: {
        type: 'boolean',
        default: true,
    },
    btnLoadGoogleFonts2:{
        type: 'boolean',
        default: false
    },
    btnfontFamily2: {
        type: 'string',
        default:'',
    },
    btnfontWeight2: {
        type: 'string',
        default: '',
    },
    btnfontSubset2: {
        type: 'string',
        default: '',
    },
    btnfontSizeType2: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeTypeMobile2: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeTypeTablet2: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeDesktop2: {
        type: 'number',
        default: '18',
    },
    btnfontSizeMobile2: {
        type: 'number',
        default: '',
    },
    btnfontSizeTablet2: {
        type: 'number',
        default: '',
    },
    btnlineHeight2: {
        type: 'string',
        default: ''
    },
    btnlineHeightMobile2: {
        type: 'string',
        default: ''
    },
    btnlineHeightTablet2: {
        type: 'string',
        default: ''
    },
    btnletterSpacing2: {
        type: 'number',
        default: ''
    },
    btnletterSpacingMobile2: {
        type: 'number',
        default: ''
    },
    btnletterSpacingTablet2: {
        type: 'number',
        default: ''
    },
    btnletterSpacingType2: {
        type: 'string',
        default: 'px'
    },
    btnletterSpacingTypeTablet2: {
        type: 'string',
        default: 'px'
    },
    btnletterSpacingTypeMobile2: {
        type: 'string',
        default: 'px'
    },
    btntextTransform2: {
        type: 'string',
        default: ''
    },
    btntextDecoration2: {
        type: 'string',
        default: ''
    },
    btnfontStyle2: {
        type: 'string',
        default: ''
    },
    btnBgColor2:{
        type: 'string',
        default:'#F9CC29',
    },
    btnHoverBgColor2:{
        type: 'string',
        default: '#00ffcc'
    },
    btnTextColor2:{
        type: 'string',
        default: ''
    },
    btnTextHoverColor2:{
        type: 'string',
        default: ''
    },
    btnBorderColor2:{
        type: 'string',
        default: ''
    },
    btnBorderType2:{
        type: 'string',
        default: ''
    },
    btnBorderHoverColor2:{
        type: 'string',
        default: ''
    },
    btn_Htarget2:{
        type: 'boolean',
        default: false
    },
    spBtnText2:{
        type: 'string',
        default: 'Activate Deal'
    },
    spBtnHref2:{
        type: 'string',
        default: ''
    },
    btnPadding2:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnPaddingTablet2:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnPaddingMobile2:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMargin2:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMarginTablet2:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMarginMobile2:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnAlignment2: {
        type: 'string',
    },
    btnAlignmentMobile2: {
        type: 'string',
    },
    btnAlignmentTablet2: {
        type: 'string',
    },
    btnBorder2:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderMobile2:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderTablet2:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadius2:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadiusMobile2:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadiusTablet2:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    iconPosition2:{
        type: 'string',
        default:'af-icon-is-left'
    },
    btnSize2:{
        type: 'string',
        default:'small'
    },
    buttonIcon2:{
        type: 'string',
        default:''
    },
    buttonWidth2:{
        type: 'string',
        default:'full'
    },
    bntRounded2:{
        type: 'boolean',
        default: false
    },
    btnRel2: {
        type: 'string',
        default: 'nofollow'
    },




    //Box 3
    boxBgColor3: {
        type: 'string',
        default:'#ffffff',
    },
    flagStyle3: {
        type: 'string',
        default: ''
    },
    ratings3:{
        type:'number',
        default:5
    },
    ratingsColor3:{
        type: 'string',
        default: '#ffa500'
    },
    selectedStars3: {
        type: 'number',
        default: 0
    },
    starsSize3:{
        type:'string',
        default: 18
    },
    starsSizeMobile3:{
        type:'string',
        default: 18
    },
    starsSizeTablet3:{
        type:'string',
        default: 18
    },
    
    titleTag3: {
        type: 'string',
        default: 'p'
    }, 
    titleText3:{
        type: 'string',
        default: 'Product Headline Goes Here'
    },
    titleLoadGoogleFonts3:{
        type: 'boolean',
        default: false
    },


    titleTextColor3: {
        type: 'string',
        default:'#000000',
    },
    titlefontFamily3: {
        type: 'string',
        default:'',
    },
    titlefontWeight3: {
        type: 'string',
        default: '900',
    },
    titlefontSubset3: {
        type: 'string',
        default: '',
    },
    titlefontSizeType3: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeMobile3: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeTablet3: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeDesktop3: {
        type: 'number',
        default: '18',
    },
    titlefontSizeMobile3: {
        type: 'number',
        default: '',
    },
    titlefontSizeTablet3: {
        type: 'number',
        default: '',
    },
    titlelineHeight3: {
        type: 'string',
        default: ''
    },
    titlelineHeightMobile3: {
        type: 'string',
        default: ''
    },
    titlelineHeightTablet3: {
        type: 'string',
        default: ''
    },
    titleletterSpacing3: {
        type: 'number',
        default: ''
    },
    titleletterSpacingMobile3: {
        type: 'number',
        default: ''
    },
    titleletterSpacingTablet3: {
        type: 'number',
        default: ''
    },
    titleletterSpacingType3: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeTablet3: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeMobile3: {
        type: 'string',
        default: 'px'
    },
    titletextTransform3: {
        type: 'string',
        default: ''
    },
    titletextDecoration3: {
        type: 'string',
        default: ''
    },
    titlefontStyle3: {
        type: 'string',
        default: ''
    },

    image3: { 
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
    imagePadding3:{
        type: 'object', 
        default: []
    },
    imagePaddingMobile3:{
        type: 'object', 
        default: []
    },
    imagePaddingTablet3:{
        type: 'object', 
        default: []
    },
    customImgWidth3:{
        type: "string",
        default:'',
    },
    customImgWidthMobile3:{
        type: "string",
        default:'',
    },
    customImgWidthTablet3:{
        type: "string",
        default:'',
    },
    imageDesignType3:{
        type: "string",
        default:'ab-fixed-design',
    },
    listItems3: {
        type: 'array',
        default: ['']
    },
    listTextItems3: {
        type:'array',
        filterElements: true,
        default:[],
    },
    listType3: {
        type: 'string',
        default: 'unordered',
    },    
    alignment3: {
        type: 'string',
        default: 'left',
    },
    listLoadGoogleFonts3:{
        type: 'boolean',
        default: false
    },
    listfontFamily3: {
        type: 'string',
        default:'',
    },
    listfontWeight3: {
        type: 'string',
        default: '',
    },

    listfontSubset3: {
        type: 'string',
        default: '',
    },
    listfontSizeType3: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeMobile3: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeTablet3: {
        type: 'string',
        default: 'px',
    },
    listfontSizeDesktop3: {
        type: 'number',
        default: '16',
    },
    listfontSizeMobile3: {
        type: 'number',
        default: '',
    },
    listfontSizeTablet3: {
        type: 'number',
        default: '',
    },
    listlineHeight3: {
        type: 'string',
        default: ''
    },
    listlineHeightMobile3: {
        type: 'string',
        default: ''
    },
    listlineHeightTablet3: {
        type: 'string',
        default: ''
    },
    listletterSpacing3: {
        type: 'number',
        default: ''
    },
    listletterSpacingMobile3: {
        type: 'number',
        default: ''
    },
    listletterSpacingTablet3: {
        type: 'number',
        default: ''
    },
    listletterSpacingType3: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeTablet3: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeMobile3: {
        type: 'string',
        default: 'px'
    },
    listtextTransform3: {
        type: 'string',
        default: ''
    },
    listtextDecoration3: {
        type: 'string',
        default: ''
    },
    listfontStyle3: {
        type: 'string',
        default: ''
    },

    bulletStyle3: {
        type: 'object',
        default: {
            name: 'check',
            value: 'far fa-check'
        }
    },
    numberCorner3: {
        type: 'string',
        default: '50',
    },
    numberFontSize3: {
        type: 'string',
        default: '14',
    },
    numberBgSize3: {
        type: 'string',
        default: '5',
    },
    useNumberBg3: {
        type: 'boolean',
        default: true
    },
    listTextColor3: {
        type: 'string',
        default: '#000000'
    },
    listIconColor3: {
        type: 'string',
        default: '#2ead3c'
    },
    listColor3: {
        type: 'string',
        default: '#000000'
    },
    recreateStyles3: {
        type: 'boolean',
        default: true,
    },
    btnLoadGoogleFonts3:{
        type: 'boolean',
        default: false
    },
    btnfontFamily3: {
        type: 'string',
        default:'',
    },
    btnfontWeight3: {
        type: 'string',
        default: '',
    },
    btnfontSubset3: {
        type: 'string',
        default: '',
    },
    btnfontSizeType3: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeTypeMobile3: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeTypeTablet3: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeDesktop3: {
        type: 'number',
        default: '18',
    },
    btnfontSizeMobile3: {
        type: 'number',
        default: '',
    },
    btnfontSizeTablet3: {
        type: 'number',
        default: '',
    },
    btnlineHeight3: {
        type: 'string',
        default: ''
    },
    btnlineHeightMobile3: {
        type: 'string',
        default: ''
    },
    btnlineHeightTablet3: {
        type: 'string',
        default: ''
    },
    btnletterSpacing3: {
        type: 'number',
        default: ''
    },
    btnletterSpacingMobile3: {
        type: 'number',
        default: ''
    },
    btnletterSpacingTablet3: {
        type: 'number',
        default: ''
    },
    btnletterSpacingType3: {
        type: 'string',
        default: 'px'
    },
    btnletterSpacingTypeTablet3: {
        type: 'string',
        default: 'px'
    },
    btnletterSpacingTypeMobile3: {
        type: 'string',
        default: 'px'
    },
    btntextTransform3: {
        type: 'string',
        default: ''
    },
    btntextDecoration3: {
        type: 'string',
        default: ''
    },
    btnfontStyle3: {
        type: 'string',
        default: ''
    },
    btnBgColor3:{
        type: 'string',
        default:'#F9CC29',
    },
    btnHoverBgColor3:{
        type: 'string',
        default: '#00ffcc'
    },
    btnTextColor3:{
        type: 'string',
        default: ''
    },
    btnTextHoverColor3:{
        type: 'string',
        default: ''
    },
    btnBorderColor3:{
        type: 'string',
        default: ''
    },
    btnBorderType3:{
        type: 'string',
        default: ''
    },
    btnBorderHoverColor3:{
        type: 'string',
        default: ''
    },
    btn_Htarget3:{
        type: 'boolean',
        default: false
    },
    spBtnText3:{
        type: 'string',
        default: 'Activate Deal'
    },
    spBtnHref3:{
        type: 'string',
        default: ''
    },
    btnPadding3:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnPaddingTablet3:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnPaddingMobile3:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMargin3:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMarginTablet3:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMarginMobile3:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnAlignment3: {
        type: 'string',
    },
    btnAlignmentMobile3: {
        type: 'string',
    },
    btnAlignmentTablet3: {
        type: 'string',
    },
    btnBorder3:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderMobile3:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderTablet3:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadius3:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadiusMobile3:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadiusTablet3:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    iconPosition3:{
        type: 'string',
        default:'af-icon-is-left'
    },
    btnSize3:{
        type: 'string',
        default:'small'
    },
    buttonIcon3:{
        type: 'string',
        default:''
    },
    buttonWidth3:{
        type: 'string',
        default:'full'
    },
    bntRounded3:{
        type: 'boolean',
        default: false
    },
    btnRel3: {
        type: 'string',
        default: 'nofollow'
    },




    //Box 4
    boxBgColor4: {
        type: 'string',
        default:'#ffffff',
    },
    flagStyle4: {
        type: 'string',
        default: ''
    },
    ratings4:{
        type:'number',
        default:5
    },
    ratingsColor4:{
        type: 'string',
        default: '#ffa500'
    },
    selectedStars4: {
        type: 'number',
        default: 0
    },
    starsSize4:{
        type:'string',
        default: 18
    },
    starsSizeMobile4:{
        type:'string',
        default: 18
    },
    starsSizeTablet4:{
        type:'string',
        default: 18
    },
    titleTag4: {
        type: 'string',
        default: 'p'
    }, 
    titleText4:{
        type: 'string',
        default: 'Product Headline Goes Here'
    },
    titleLoadGoogleFonts4:{
        type: 'boolean',
        default: false
    },
    titleTextColor4: {
        type: 'string',
        default:'#000000',
    },
    titlefontFamily4: {
        type: 'string',
        default:'',
    },
    titlefontWeight4: {
        type: 'string',
        default: '900',
    },
    titlefontSubset4: {
        type: 'string',
        default: '',
    },
    titlefontSizeType4: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeMobile4: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeTablet4: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeDesktop4: {
        type: 'number',
        default: '18',
    },
    titlefontSizeMobile4: {
        type: 'number',
        default: '',
    },
    titlefontSizeTablet4: {
        type: 'number',
        default: '',
    },
    titlelineHeight4: {
        type: 'string',
        default: ''
    },
    titlelineHeightMobile4: {
        type: 'string',
        default: ''
    },
    titlelineHeightTablet4: {
        type: 'string',
        default: ''
    },
    titleletterSpacing4: {
        type: 'number',
        default: ''
    },
    titleletterSpacingMobile4: {
        type: 'number',
        default: ''
    },
    titleletterSpacingTablet4: {
        type: 'number',
        default: ''
    },
    titleletterSpacingType4: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeTablet4: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeMobile4: {
        type: 'string',
        default: 'px'
    },
    titletextTransform4: {
        type: 'string',
        default: ''
    },
    titletextDecoration4: {
        type: 'string',
        default: ''
    },
    titlefontStyle4: {
        type: 'string',
        default: ''
    },
    image4: { 
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
    imagePadding4:{
        type: 'object', 
        default: []
    },
    imagePaddingMobile4:{
        type: 'object', 
        default: []
    },
    imagePaddingTablet4:{
        type: 'object', 
        default: []
    },
    customImgWidth4:{
        type: "string",
        default:'',
    },
    customImgWidthMobile4:{
        type: "string",
        default:'',
    },
    customImgWidthTablet4:{
        type: "string",
        default:'',
    },
    imageDesignType4:{
        type: "string",
        default:'ab-fixed-design',
    },
    listItems4: {
        type: 'array',
        default: ['']
    },
    listTextItems4: {
        type:'array',
        filterElements: true,
        default:[],
    },
    listType4: {
        type: 'string',
        default: 'unordered',
    },    
    alignment4: {
        type: 'string',
        default: 'left',
    },
    listLoadGoogleFonts4:{
        type: 'boolean',
        default: false
    },
    listfontFamily4: {
        type: 'string',
        default:'',
    },
    listfontWeight4: {
        type: 'string',
        default: '',
    },

    listfontSubset4: {
        type: 'string',
        default: '',
    },
    listfontSizeType4: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeMobile4: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeTablet4: {
        type: 'string',
        default: 'px',
    },
    listfontSizeDesktop4: {
        type: 'number',
        default: '16',
    },
    listfontSizeMobile4: {
        type: 'number',
        default: '',
    },
    listfontSizeTablet4: {
        type: 'number',
        default: '',
    },
    listlineHeight4: {
        type: 'string',
        default: ''
    },
    listlineHeightMobile4: {
        type: 'string',
        default: ''
    },
    listlineHeightTablet4: {
        type: 'string',
        default: ''
    },
    listletterSpacing4: {
        type: 'number',
        default: ''
    },
    listletterSpacingMobile4: {
        type: 'number',
        default: ''
    },
    listletterSpacingTablet4: {
        type: 'number',
        default: ''
    },
    listletterSpacingType4: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeTablet4: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeMobile4: {
        type: 'string',
        default: 'px'
    },
    listtextTransform4: {
        type: 'string',
        default: ''
    },
    listtextDecoration4: {
        type: 'string',
        default: ''
    },
    listfontStyle4: {
        type: 'string',
        default: ''
    },

    bulletStyle4: {
        type: 'object',
        default: {
            name: 'check',
            value: 'far fa-check'
        }
    },
    numberCorner4: {
        type: 'string',
        default: '50',
    },
    numberFontSize4: {
        type: 'string',
        default: '14',
    },
    numberBgSize4: {
        type: 'string',
        default: '5',
    },
    useNumberBg4: {
        type: 'boolean',
        default: true
    },
    listTextColor4: {
        type: 'string',
        default: '#000000'
    },
    listIconColor4: {
        type: 'string',
        default: '#2ead3c'
    },
    listColor4: {
        type: 'string',
        default: '#000000'
    },
    recreateStyles4: {
        type: 'boolean',
        default: true,
    },
    btnLoadGoogleFonts4:{
        type: 'boolean',
        default: false
    },
    btnfontFamily4: {
        type: 'string',
        default:'',
    },
    btnfontWeight4: {
        type: 'string',
        default: '',
    },
    btnfontSubset4: {
        type: 'string',
        default: '',
    },
    btnfontSizeType4: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeTypeMobile4: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeTypeTablet4: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeDesktop4: {
        type: 'number',
        default: '18',
    },
    btnfontSizeMobile4: {
        type: 'number',
        default: '',
    },
    btnfontSizeTablet4: {
        type: 'number',
        default: '',
    },
    btnlineHeight4: {
        type: 'string',
        default: ''
    },
    btnlineHeightMobile4: {
        type: 'string',
        default: ''
    },
    btnlineHeightTablet4: {
        type: 'string',
        default: ''
    },
    btnletterSpacing4: {
        type: 'number',
        default: ''
    },
    btnletterSpacingMobile4: {
        type: 'number',
        default: ''
    },
    btnletterSpacingTablet4: {
        type: 'number',
        default: ''
    },
    btnletterSpacingType4: {
        type: 'string',
        default: 'px'
    },
    btnletterSpacingTypeTablet4: {
        type: 'string',
        default: 'px'
    },
    btnletterSpacingTypeMobile4: {
        type: 'string',
        default: 'px'
    },
    btntextTransform4: {
        type: 'string',
        default: ''
    },
    btntextDecoration4: {
        type: 'string',
        default: ''
    },
    btnfontStyle4: {
        type: 'string',
        default: ''
    },
    btnBgColor4:{
        type: 'string',
        default:'#F9CC29',
    },
    btnHoverBgColor4:{
        type: 'string',
        default: '#00ffcc'
    },
    btnTextColor4:{
        type: 'string',
        default: ''
    },
    btnTextHoverColor4:{
        type: 'string',
        default: ''
    },
    btnBorderColor4:{
        type: 'string',
        default: ''
    },
    btnBorderType4:{
        type: 'string',
        default: ''
    },
    btnBorderHoverColor4:{
        type: 'string',
        default: ''
    },
    btn_Htarget4:{
        type: 'boolean',
        default: false
    },
    spBtnText4:{
        type: 'string',
        default: 'Activate Deal'
    },
    spBtnHref4:{
        type: 'string',
        default: ''
    },
    btnPadding4:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnPaddingTablet4:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnPaddingMobile4:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMargin4:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMarginTablet4:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMarginMobile4:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnAlignment4: {
        type: 'string',
    },
    btnAlignmentMobile4: {
        type: 'string',
    },
    btnAlignmentTablet4: {
        type: 'string',
    },
    btnBorder4:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderMobile4:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderTablet4:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadius4:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadiusMobile4:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadiusTablet4:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    iconPosition4:{
        type: 'string',
        default:'af-icon-is-left'
    },
    btnSize4:{
        type: 'string',
        default:'small'
    },
    buttonIcon4:{
        type: 'string',
        default:''
    },
    buttonWidth4:{
        type: 'string',
        default:'full'
    },
    bntRounded4:{
        type: 'boolean',
        default: false
    },
    btnRel4: {
        type: 'string',
        default: 'nofollow'
    },




    //Box 5
    boxBgColor5: {
        type: 'string',
        default:'#ffffff',
    },
    flagStyle5: {
        type: 'string',
        default: ''
    },
    ratings5:{
        type:'number',
        default:5
    },
    ratingsColor5:{
        type: 'string',
        default: '#ffa500'
    },
    selectedStars5: {
        type: 'number',
        default: 0
    },
    starsSize5:{
        type:'string',
        default: 18
    },
    starsSizeMobile5:{
        type:'string',
        default: 18
    },
    starsSizeTablet5:{
        type:'string',
        default: 18
    },
    titleTag5: {
        type: 'string',
        default: 'p'
    }, 
    titleText5:{
        type: 'string',
        default: 'Product Headline Goes Here'
    },
    titleLoadGoogleFonts5:{
        type: 'boolean',
        default: false
    },
    titleTextColor5: {
        type: 'string',
        default:'#000000',
    },
    titlefontFamily5: {
        type: 'string',
        default:'',
    },
    titlefontWeight5: {
        type: 'string',
        default: '900',
    },
    titlefontSubset5: {
        type: 'string',
        default: '',
    },
    titlefontSizeType5: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeMobile5: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeTablet5: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeDesktop5: {
        type: 'number',
        default: '18',
    },
    titlefontSizeMobile5: {
        type: 'number',
        default: '',
    },
    titlefontSizeTablet5: {
        type: 'number',
        default: '',
    },
    titlelineHeight5: {
        type: 'string',
        default: ''
    },
    titlelineHeightMobile5: {
        type: 'string',
        default: ''
    },
    titlelineHeightTablet5: {
        type: 'string',
        default: ''
    },
    titleletterSpacing5: {
        type: 'number',
        default: ''
    },
    titleletterSpacingMobile5: {
        type: 'number',
        default: ''
    },
    titleletterSpacingTablet5: {
        type: 'number',
        default: ''
    },
    titleletterSpacingType5: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeTablet5: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeMobile5: {
        type: 'string',
        default: 'px'
    },
    titletextTransform5: {
        type: 'string',
        default: ''
    },
    titletextDecoration5: {
        type: 'string',
        default: ''
    },
    titlefontStyle5: {
        type: 'string',
        default: ''
    },
    image5: { 
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
    imagePadding5:{
        type: 'object', 
        default: []
    },
    imagePaddingMobile5:{
        type: 'object', 
        default: []
    },
    imagePaddingTablet5:{
        type: 'object', 
        default: []
    },
    customImgWidth5:{
        type: "string",
        default:'',
    },
    customImgWidthMobile5:{
        type: "string",
        default:'',
    },
    customImgWidthTablet5:{
        type: "string",
        default:'',
    },
    imageDesignType5:{
        type: "string",
        default:'ab-fixed-design',
    },
    listItems5: {
        type: 'array',
        default: ['']
    },
    listTextItems5: {
        type:'array',
        filterElements: true,
        default:[],
    },
    listType5: {
        type: 'string',
        default: 'unordered',
    },    
    alignment5: {
        type: 'string',
        default: 'left',
    },
    listLoadGoogleFonts5:{
        type: 'boolean',
        default: false
    },
    listfontFamily5: {
        type: 'string',
        default:'',
    },
    listfontWeight5: {
        type: 'string',
        default: '',
    },

    listfontSubset5: {
        type: 'string',
        default: '',
    },
    listfontSizeType5: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeMobile5: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeTablet5: {
        type: 'string',
        default: 'px',
    },
    listfontSizeDesktop5: {
        type: 'number',
        default: '16',
    },
    listfontSizeMobile5: {
        type: 'number',
        default: '',
    },
    listfontSizeTablet5: {
        type: 'number',
        default: '',
    },
    listlineHeight5: {
        type: 'string',
        default: ''
    },
    listlineHeightMobile5: {
        type: 'string',
        default: ''
    },
    listlineHeightTablet5: {
        type: 'string',
        default: ''
    },
    listletterSpacing5: {
        type: 'number',
        default: ''
    },
    listletterSpacingMobile5: {
        type: 'number',
        default: ''
    },
    listletterSpacingTablet5: {
        type: 'number',
        default: ''
    },
    listletterSpacingType5: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeTablet5: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeMobile5: {
        type: 'string',
        default: 'px'
    },
    listtextTransform5: {
        type: 'string',
        default: ''
    },
    listtextDecoration5: {
        type: 'string',
        default: ''
    },
    listfontStyle5: {
        type: 'string',
        default: ''
    },


    bulletStyle5: {
        type: 'object',
        default: {
            name: 'check',
            value: 'far fa-check'
        }
    },
    numberCorner5: {
        type: 'string',
        default: '50',
    },
    numberFontSize5: {
        type: 'string',
        default: '14',
    },
    numberBgSize5: {
        type: 'string',
        default: '5',
    },
    useNumberBg5: {
        type: 'boolean',
        default: true
    },
    listTextColor5: {
        type: 'string',
        default: '#000000'
    },
    listIconColor5: {
        type: 'string',
        default: '#2ead3c'
    },
    listColor5: {
        type: 'string',
        default: '#000000'
    },
    recreateStyles5: {
        type: 'boolean',
        default: true,
    },
    btnLoadGoogleFonts5:{
        type: 'boolean',
        default: false
    },
    btnfontFamily5: {
        type: 'string',
        default:'',
    },
    btnfontWeight5: {
        type: 'string',
        default: '',
    },
    btnfontSubset5: {
        type: 'string',
        default: '',
    },
    btnfontSizeType5: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeTypeMobile5: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeTypeTablet5: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeDesktop5: {
        type: 'number',
        default: '18',
    },
    btnfontSizeMobile5: {
        type: 'number',
        default: '',
    },
    btnfontSizeTablet5: {
        type: 'number',
        default: '',
    },
    btnlineHeight5: {
        type: 'string',
        default: ''
    },
    btnlineHeightMobile5: {
        type: 'string',
        default: ''
    },
    btnlineHeightTablet5: {
        type: 'string',
        default: ''
    },
    btnletterSpacing5: {
        type: 'number',
        default: ''
    },
    btnletterSpacingMobile5: {
        type: 'number',
        default: ''
    },
    btnletterSpacingTablet5: {
        type: 'number',
        default: ''
    },
    btnletterSpacingType5: {
        type: 'string',
        default: 'px'
    },
    btnletterSpacingTypeTablet5: {
        type: 'string',
        default: 'px'
    },
    btnletterSpacingTypeMobile5: {
        type: 'string',
        default: 'px'
    },
    btntextTransform5: {
        type: 'string',
        default: ''
    },
    btntextDecoration5: {
        type: 'string',
        default: ''
    },
    btnfontStyle5: {
        type: 'string',
        default: ''
    },
    btnBgColor5:{
        type: 'string',
        default:'#F9CC29',
    },
    btnHoverBgColor5:{
        type: 'string',
        default: '#00ffcc'
    },
    btnTextColor5:{
        type: 'string',
        default: ''
    },
    btnTextHoverColor5:{
        type: 'string',
        default: ''
    },
    btnBorderColor5:{
        type: 'string',
        default: ''
    },
    btnBorderType5:{
        type: 'string',
        default: ''
    },
    btnBorderHoverColor5:{
        type: 'string',
        default: ''
    },
    btn_Htarget5:{
        type: 'boolean',
        default: false
    },
    spBtnText5:{
        type: 'string',
        default: 'Activate Deal'
    },
    spBtnHref5:{
        type: 'string',
        default: ''
    },
    btnPadding5:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnPaddingTablet5:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnPaddingMobile5:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMargin5:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMarginTablet5:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMarginMobile5:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnAlignment5: {
        type: 'string',
    },
    btnAlignmentMobile5: {
        type: 'string',
    },
    btnAlignmentTablet5: {
        type: 'string',
    },
    btnBorder5:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderMobile5:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderTablet5:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadius5:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadiusMobile5:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadiusTablet5:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    iconPosition5:{
        type: 'string',
        default:'af-icon-is-left'
    },
    btnSize5:{
        type: 'string',
        default:'small'
    },
    buttonIcon5:{
        type: 'string',
        default:''
    },
    buttonWidth5:{
        type: 'string',
        default:'full'
    },
    bntRounded5:{
        type: 'boolean',
        default: false
    },
    btnRel5: {
        type: 'string',
        default: 'nofollow'
    },



    //Box 6
    boxBgColor6: {
        type: 'string',
        default:'#ffffff',
    },
    flagStyle6: {
        type: 'string',
        default: ''
    },
    ratings6:{
        type:'number',
        default:5
    },
    ratingsColor6:{
        type: 'string',
        default: '#ffa500'
    },
    selectedStars6: {
        type: 'number',
        default: 0
    },
    starsSize6:{
        type:'string',
        default: 18
    },
    starsSizeMobile6:{
        type:'string',
        default: 18
    },
    starsSizeTablet6:{
        type:'string',
        default: 18
    },
    titleTag6: {
        type: 'string',
        default: 'p'
    }, 
    titleText6:{
        type: 'string',
        default: 'Product Headline Goes Here'
    },
    titleLoadGoogleFonts6:{
        type: 'boolean',
        default: false
    },
    titleTextColor6: {
        type: 'string',
        default:'#000000',
    },
    titlefontFamily6: {
        type: 'string',
        default:'',
    },
    titlefontWeight6: {
        type: 'string',
        default: '900',
    },
    titlefontSubset6: {
        type: 'string',
        default: '',
    },
    titlefontSizeType6: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeMobile6: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeTablet6: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeDesktop6: {
        type: 'number',
        default: '18',
    },
    titlefontSizeMobile6: {
        type: 'number',
        default: '',
    },
    titlefontSizeTablet6: {
        type: 'number',
        default: '',
    },
    titlelineHeight6: {
        type: 'string',
        default: ''
    },
    titlelineHeightMobile6: {
        type: 'string',
        default: ''
    },
    titlelineHeightTablet6: {
        type: 'string',
        default: ''
    },
    titleletterSpacing6: {
        type: 'number',
        default: ''
    },
    titleletterSpacingMobile6: {
        type: 'number',
        default: ''
    },
    titleletterSpacingTablet6: {
        type: 'number',
        default: ''
    },
    titleletterSpacingType6: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeTablet6: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeMobile6: {
        type: 'string',
        default: 'px'
    },
    titletextTransform6: {
        type: 'string',
        default: ''
    },
    titletextDecoration6: {
        type: 'string',
        default: ''
    },
    titlefontStyle6: {
        type: 'string',
        default: ''
    },
    image6: { 
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
    imagePadding6:{
        type: 'object', 
        default: []
    },
    imagePaddingMobile6:{
        type: 'object', 
        default: []
    },
    imagePaddingTablet6:{
        type: 'object', 
        default: []
    },
    customImgWidth6:{
        type: "string",
        default:'',
    },
    customImgWidthMobile6:{
        type: "string",
        default:'',
    },
    customImgWidthTablet6:{
        type: "string",
        default:'',
    },
    imageDesignType6:{
        type: "string",
        default:'ab-fixed-design',
    },
    listItems6: {
        type: 'array',
        default: ['']
    },
    listTextItems6: {
        type:'array',
        filterElements: true,
        default:[],
    },
    listType6: {
        type: 'string',
        default: 'unordered',
    },    
    alignment6: {
        type: 'string',
        default: 'left',
    },
    listLoadGoogleFonts6:{
        type: 'boolean',
        default: false
    },
    listfontFamily6: {
        type: 'string',
        default:'',
    },
    listfontWeight6: {
        type: 'string',
        default: '',
    },

    listfontSubset6: {
        type: 'string',
        default: '',
    },
    listfontSizeType6: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeMobile6: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeTablet6: {
        type: 'string',
        default: 'px',
    },
    listfontSizeDesktop6: {
        type: 'number',
        default: '16',
    },
    listfontSizeMobile6: {
        type: 'number',
        default: '',
    },
    listfontSizeTablet6: {
        type: 'number',
        default: '',
    },
    listlineHeight6: {
        type: 'string',
        default: ''
    },
    listlineHeightMobile6: {
        type: 'string',
        default: ''
    },
    listlineHeightTablet6: {
        type: 'string',
        default: ''
    },
    listletterSpacing6: {
        type: 'number',
        default: ''
    },
    listletterSpacingMobile6: {
        type: 'number',
        default: ''
    },
    listletterSpacingTablet6: {
        type: 'number',
        default: ''
    },
    listletterSpacingType6: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeTablet6: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeMobile6: {
        type: 'string',
        default: 'px'
    },
    listtextTransform6: {
        type: 'string',
        default: ''
    },
    listtextDecoration6: {
        type: 'string',
        default: ''
    },
    listfontStyle6: {
        type: 'string',
        default: ''
    },

    bulletStyle6: {
        type: 'object',
        default: {
            name: 'check',
            value: 'far fa-check'
        }
    },
    numberCorner6: {
        type: 'string',
        default: '50',
    },
    numberFontSize6: {
        type: 'string',
        default: '14',
    },
    numberBgSize6: {
        type: 'string',
        default: '5',
    },
    useNumberBg6: {
        type: 'boolean',
        default: true
    },
    listTextColor6: {
        type: 'string',
        default: '#000000'
    },
    listIconColor6: {
        type: 'string',
        default: '#2ead3c'
    },
    listColor6: {
        type: 'string',
        default: '#000000'
    },
    recreateStyles6: {
        type: 'boolean',
        default: true,
    },
    btnLoadGoogleFonts6:{
        type: 'boolean',
        default: false
    },
    btnfontFamily6: {
        type: 'string',
        default:'',
    },
    btnfontWeight6: {
        type: 'string',
        default: '',
    },
    btnfontSubset6: {
        type: 'string',
        default: '',
    },
    btnfontSizeType6: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeTypeMobile6: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeTypeTablet6: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeDesktop6: {
        type: 'number',
        default: '18',
    },
    btnfontSizeMobile6: {
        type: 'number',
        default: '',
    },
    btnfontSizeTablet6: {
        type: 'number',
        default: '',
    },
    btnlineHeight6: {
        type: 'string',
        default: ''
    },
    btnlineHeightMobile6: {
        type: 'string',
        default: ''
    },
    btnlineHeightTablet6: {
        type: 'string',
        default: ''
    },
    btnletterSpacing6: {
        type: 'number',
        default: ''
    },
    btnletterSpacingMobile6: {
        type: 'number',
        default: ''
    },
    btnletterSpacingTablet6: {
        type: 'number',
        default: ''
    },
    btnletterSpacingType6: {
        type: 'string',
        default: 'px'
    },
    btnletterSpacingTypeTablet6: {
        type: 'string',
        default: 'px'
    },
    btnletterSpacingTypeMobile6: {
        type: 'string',
        default: 'px'
    },
    btntextTransform6: {
        type: 'string',
        default: ''
    },
    btntextDecoration6: {
        type: 'string',
        default: ''
    },
    btnfontStyle6: {
        type: 'string',
        default: ''
    },
    btnBgColor6:{
        type: 'string',
        default:'#F9CC29',
    },
    btnHoverBgColor6:{
        type: 'string',
        default: '#00ffcc'
    },
    btnTextColor6:{
        type: 'string',
        default: ''
    },
    btnTextHoverColor6:{
        type: 'string',
        default: ''
    },
    btnBorderColor6:{
        type: 'string',
        default: ''
    },
    btnBorderType6:{
        type: 'string',
        default: ''
    },
    btnBorderHoverColor6:{
        type: 'string',
        default: ''
    },
    btn_Htarget6:{
        type: 'boolean',
        default: false
    },
    spBtnText6:{
        type: 'string',
        default: 'Activate Deal'
    },
    spBtnHref6:{
        type: 'string',
        default: ''
    },
    btnPadding6:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnPaddingTablet6:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnPaddingMobile6:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMargin6:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMarginTablet6:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMarginMobile6:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnAlignment6: {
        type: 'string',
    },
    btnAlignmentMobile6: {
        type: 'string',
    },
    btnAlignmentTablet6: {
        type: 'string',
    },
    btnBorder6:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderMobile6:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderTablet6:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadius6:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadiusMobile6:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadiusTablet6:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    iconPosition6:{
        type: 'string',
        default:'af-icon-is-left'
    },
    btnSize6:{
        type: 'string',
        default:'small'
    },
    buttonIcon6:{
        type: 'string',
        default:''
    },
    buttonWidth6:{
        type: 'string',
        default:'full'
    },
    bntRounded6:{
        type: 'boolean',
        default: false
    },
    btnRel6: {
        type: 'string',
        default: 'nofollow'
    },



    //Box 7
    boxBgColor7: {
        type: 'string',
        default:'#ffffff',
    },
    flagStyle7: {
        type: 'string',
        default: ''
    },
    ratings7:{
        type:'number',
        default:5
    },
    ratingsColor7:{
        type: 'string',
        default: '#ffa500'
    },
    selectedStars7: {
        type: 'number',
        default: 0
    },
    starsSize7:{
        type:'string',
        default: 18
    },
    starsSizeMobile7:{
        type:'string',
        default: 18
    },
    starsSizeTablet7:{
        type:'string',
        default: 18
    },
    titleTag7: {
        type: 'string',
        default: 'p'
    }, 
    titleText7:{
        type: 'string',
        default: 'Product Headline Goes Here'
    },
    titleLoadGoogleFonts7:{
        type: 'boolean',
        default: false
    },
    titleTextColor7: {
        type: 'string',
        default:'#000000',
    },
    titlefontFamily7: {
        type: 'string',
        default:'',
    },
    titlefontWeight7: {
        type: 'string',
        default: '900',
    },
    titlefontSubset7: {
        type: 'string',
        default: '',
    },
    titlefontSizeType7: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeMobile7: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeTablet7: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeDesktop7: {
        type: 'number',
        default: '18',
    },
    titlefontSizeMobile7: {
        type: 'number',
        default: '',
    },
    titlefontSizeTablet7: {
        type: 'number',
        default: '',
    },
    titlelineHeight7: {
        type: 'string',
        default: ''
    },
    titlelineHeightMobile7: {
        type: 'string',
        default: ''
    },
    titlelineHeightTablet7: {
        type: 'string',
        default: ''
    },
    titleletterSpacing7: {
        type: 'number',
        default: ''
    },
    titleletterSpacingMobile7: {
        type: 'number',
        default: ''
    },
    titleletterSpacingTablet7: {
        type: 'number',
        default: ''
    },
    titleletterSpacingType7: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeTablet7: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeMobile7: {
        type: 'string',
        default: 'px'
    },
    titletextTransform7: {
        type: 'string',
        default: ''
    },
    titletextDecoration7: {
        type: 'string',
        default: ''
    },
    titlefontStyle7: {
        type: 'string',
        default: ''
    },
    image7: { 
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
    imagePadding7:{
        type: 'object', 
        default: []
    },
    imagePaddingMobile7:{
        type: 'object', 
        default: []
    },
    imagePaddingTablet7:{
        type: 'object', 
        default: []
    },
    customImgWidth7:{
        type: "string",
        default:'',
    },
    customImgWidthMobile7:{
        type: "string",
        default:'',
    },
    customImgWidthTablet7:{
        type: "string",
        default:'',
    },
    imageDesignType7:{
        type: "string",
        default:'ab-fixed-design',
    },
    listItems7: {
        type: 'array',
        default: ['']
    },
    listTextItems7: {
        type:'array',
        filterElements: true,
        default:[],
    },
    listType7: {
        type: 'string',
        default: 'unordered',
    },    
    alignment7: {
        type: 'string',
        default: 'left',
    },
    listLoadGoogleFonts7:{
        type: 'boolean',
        default: false
    },
    listfontFamily7: {
        type: 'string',
        default:'',
    },
    listfontWeight7: {
        type: 'string',
        default: '',
    },


    listfontSubset7: {
        type: 'string',
        default: '',
    },
    listfontSizeType7: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeMobile7: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeTablet7: {
        type: 'string',
        default: 'px',
    },
    listfontSizeDesktop7: {
        type: 'number',
        default: '16',
    },
    listfontSizeMobile7: {
        type: 'number',
        default: '',
    },
    listfontSizeTablet7: {
        type: 'number',
        default: '',
    },
    listlineHeight7: {
        type: 'string',
        default: ''
    },
    listlineHeightMobile7: {
        type: 'string',
        default: ''
    },
    listlineHeightTablet7: {
        type: 'string',
        default: ''
    },
    listletterSpacing7: {
        type: 'number',
        default: ''
    },
    listletterSpacingMobile7: {
        type: 'number',
        default: ''
    },
    listletterSpacingTablet7: {
        type: 'number',
        default: ''
    },
    listletterSpacingType7: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeTablet7: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeMobile7: {
        type: 'string',
        default: 'px'
    },
    listtextTransform7: {
        type: 'string',
        default: ''
    },
    listtextDecoration7: {
        type: 'string',
        default: ''
    },
    listfontStyle7: {
        type: 'string',
        default: ''
    },


    bulletStyle7: {
        type: 'object',
        default: {
            name: 'check',
            value: 'far fa-check'
        }
    },
    numberCorner7: {
        type: 'string',
        default: '50',
    },
    numberFontSize7: {
        type: 'string',
        default: '14',
    },
    numberBgSize7: {
        type: 'string',
        default: '5',
    },
    useNumberBg7: {
        type: 'boolean',
        default: true
    },
    listTextColor7: {
        type: 'string',
        default: '#000000'
    },
    listIconColor7: {
        type: 'string',
        default: '#2ead3c'
    },
    listColor7: {
        type: 'string',
        default: '#000000'
    },
    recreateStyles7: {
        type: 'boolean',
        default: true,
    },
    btnLoadGoogleFonts7:{
        type: 'boolean',
        default: false
    },
    btnfontFamily7: {
        type: 'string',
        default:'',
    },
    btnfontWeight7: {
        type: 'string',
        default: '',
    },
    btnfontSubset7: {
        type: 'string',
        default: '',
    },
    btnfontSizeType7: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeTypeMobile7: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeTypeTablet7: {
        type: 'string',
        default: 'px',
    },
    btnfontSizeDesktop7: {
        type: 'number',
        default: '18',
    },
    btnfontSizeMobile7: {
        type: 'number',
        default: '',
    },
    btnfontSizeTablet7: {
        type: 'number',
        default: '',
    },
    btnlineHeight7: {
        type: 'string',
        default: ''
    },
    btnlineHeightMobile7: {
        type: 'string',
        default: ''
    },
    btnlineHeightTablet7: {
        type: 'string',
        default: ''
    },
    btnletterSpacing7: {
        type: 'number',
        default: ''
    },
    btnletterSpacingMobile7: {
        type: 'number',
        default: ''
    },
    btnletterSpacingTablet7: {
        type: 'number',
        default: ''
    },
    btnletterSpacingType7: {
        type: 'string',
        default: 'px'
    },
    btnletterSpacingTypeTablet7: {
        type: 'string',
        default: 'px'
    },
    btnletterSpacingTypeMobile7: {
        type: 'string',
        default: 'px'
    },
    btntextTransform7: {
        type: 'string',
        default: ''
    },
    btntextDecoration7: {
        type: 'string',
        default: ''
    },
    btnfontStyle7: {
        type: 'string',
        default: ''
    },
    btnBgColor7:{
        type: 'string',
        default:'#F9CC29',
    },
    btnHoverBgColor7:{
        type: 'string',
        default: '#00ffcc'
    },
    btnTextColor7:{
        type: 'string',
        default: ''
    },
    btnTextHoverColor7:{
        type: 'string',
        default: ''
    },
    btnBorderColor7:{
        type: 'string',
        default: ''
    },
    btnBorderType7:{
        type: 'string',
        default: ''
    },
    btnBorderHoverColor7:{
        type: 'string',
        default: ''
    },
    btn_Htarget7:{
        type: 'boolean',
        default: false
    },
    spBtnText7:{
        type: 'string',
        default: 'Activate Deal'
    },
    spBtnHref7:{
        type: 'string',
        default: ''
    },
    btnPadding7:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnPaddingTablet7:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnPaddingMobile7:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMargin7:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMarginTablet7:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnMarginMobile7:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnAlignment7: {
        type: 'string',
    },
    btnAlignmentMobile7: {
        type: 'string',
    },
    btnAlignmentTablet7: {
        type: 'string',
    },
    btnBorder7:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderMobile7:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderTablet7:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadius7:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadiusMobile7:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    btnBorderRadiusTablet7:{
        type: 'object',
        default: {
            unit:'px',
        }
    },
    iconPosition7:{
        type: 'string',
        default:'af-icon-is-left'
    },
    btnSize7:{
        type: 'string',
        default:'small'
    },
    buttonIcon7:{
        type: 'string',
        default:''
    },
    buttonWidth7:{
        type: 'string',
        default:'full'
    },
    bntRounded7:{
        type: 'boolean',
        default: false
    },
    btnRel7: {
        type: 'string',
        default: 'nofollow'
    },


    externalImage1:{
        type: 'boolean',
        default: false
    },
    externalImageSrc1:{
        type: "string",
        default:'',
    },
    externalImageAlt1:{
        type: "string",
        default:'',
    },

    externalImage2:{
        type: 'boolean',
        default: false
    },
    externalImageSrc2:{
        type: "string",
        default:'',
    },
    externalImageAlt2:{
        type: "string",
        default:'',
    },

    externalImage3:{
        type: 'boolean',
        default: false
    },
    externalImageSrc3:{
        type: "string",
        default:'',
    },
    externalImageAlt3:{
        type: "string",
        default:'',
    },

    externalImage4:{
        type: 'boolean',
        default: false
    },
    externalImageSrc4:{
        type: "string",
        default:'',
    },
    externalImageAlt4:{
        type: "string",
        default:'',
    },

    externalImage5:{
        type: 'boolean',
        default: false
    },
    externalImageSrc5:{
        type: "string",
        default:'',
    },
    externalImageAlt5:{
        type: "string",
        default:'',
    },

    externalImage6:{
        type: 'boolean',
        default: false
    },
    externalImageSrc6:{
        type: "string",
        default:'',
    },
    externalImageAlt6:{
        type: "string",
        default:'',
    },

    externalImage7:{
        type: 'boolean',
        default: false
    },
    externalImageSrc7:{
        type: "string",
        default:'',
    },
    externalImageAlt7:{
        type: "string",
        default:'',
    },


    showTitle:{
        type: 'boolean',
        default: true
    },
    showRating:{
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

    btnBg2: {
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

    btnBgHover2: {
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

    btnBg3: {
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

    btnBgHover3: {
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
    btnBg4: {
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

    btnBgHover4: {
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

    btnBg5: {
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

    btnBgHover5: {
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

    btnBg6: {
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

    btnBgHover6: {
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
    btnBg7: {
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

    btnBgHover7: {
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
    

    enableGlimmerEffect:{
        type: 'boolean',
        default: false
    },
    enableGlimmerEffect2:{
        type: 'boolean',
        default: false
    },
    enableGlimmerEffect3:{
        type: 'boolean',
        default: false
    },
    enableGlimmerEffect4:{
        type: 'boolean',
        default: false
    },
    enableGlimmerEffect5:{
        type: 'boolean',
        default: false
    },
    enableGlimmerEffect6:{
        type: 'boolean',
        default: false
    },
    enableGlimmerEffect7:{
        type: 'boolean',
        default: false
    },


    showtopText:{
        type: 'boolean',
        default: true
    }, 
    showbottomText:{
        type: 'boolean',
        default: true
    }, 

    topText1:{
        type: 'string',
        default: 'Best Overall'
    },
    topTextColor1: {
        type: 'string',
        default:'#000000',
    },
    topTextBgColor1: {
        type: 'string',
        default:'#dfdcf4',
    },
    topTextLoadGoogleFonts1:{
        type: 'boolean',
        default: false
    },
    topTextfontFamily1: {
        type: 'string',
        default:'',
    },
    topTextfontWeight1: {
        type: 'string',
        default: '900',
    },
    topTextfontSubset1: {
        type: 'string',
        default: '',
    },
    topTextfontSizeType1: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeTypeMobile1: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeTypeTablet1: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeDesktop1: {
        type: 'number',
        default: '14',
    },
    topTextfontSizeMobile1: {
        type: 'number',
        default: '',
    },
    topTextfontSizeTablet1: {
        type: 'number',
        default: '',
    },
    topTextlineHeight1: {
        type: 'string',
        default: ''
    },
    topTextlineHeightMobile1: {
        type: 'string',
        default: ''
    },
    topTextlineHeightTablet1: {
        type: 'string',
        default: ''
    },
    topTextletterSpacing1: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingMobile1: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingTablet1: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingType1: {
        type: 'string',
        default: 'px'
    },
    topTextletterSpacingTypeTablet1: {
        type: 'string',
        default: 'px'
    },
    topTextletterSpacingTypeMobile1: {
        type: 'string',
        default: 'px'
    },
    topTextTransform1: {
        type: 'string',
        default: ''
    },
    topTextDecoration1: {
        type: 'string',
        default: ''
    },
    topTextfontStyle1: {
        type: 'string',
        default: ''
    },

    bottomText1:{
        type: 'string',
        default: 'Read Review'
    },    
    bottomTextColor1: {
        type: 'string',
        default:'#000000',
    },
    bottomTextLoadGoogleFonts1:{
        type: 'boolean',
        default: false
    },
    bottomTextfontFamily1: {
        type: 'string',
        default:'',
    },
    bottomTextfontWeight1: {
        type: 'string',
        default: '900',
    },
    bottomTextfontSubset1: {
        type: 'string',
        default: '',
    },
    bottomTextfontSizeType1: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeTypeMobile1: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeTypeTablet1: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeDesktop1: {
        type: 'number',
        default: '14',
    },
    bottomTextfontSizeMobile1: {
        type: 'number',
        default: '',
    },
    bottomTextfontSizeTablet1: {
        type: 'number',
        default: '',
    },
    bottomTextlineHeight1: {
        type: 'string',
        default: ''
    },
    bottomTextlineHeightMobile1: {
        type: 'string',
        default: ''
    },
    bottomTextlineHeightTablet1: {
        type: 'string',
        default: ''
    },
    bottomTextletterSpacing1: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingMobile1: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingTablet1: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingType1: {
        type: 'string',
        default: 'px'
    },
    bottomTextletterSpacingTypeTablet1: {
        type: 'string',
        default: 'px'
    },
    bottomTextletterSpacingTypeMobile1: {
        type: 'string',
        default: 'px'
    },
    bottomTextTransform1: {
        type: 'string',
        default: ''
    },
    bottomTextDecoration1: {
        type: 'string',
        default: ''
    },
    bottomTextfontStyle1: {
        type: 'string',
        default: ''
    },


    topText2:{
        type: 'string',
        default: 'Best Overall'
    },
    topTextColor2: {
        type: 'string',
        default:'#000000',
    },
    topTextBgColor2: {
        type: 'string',
        default:'#dfdcf4',
    },
    topTextLoadGoogleFonts2:{
        type: 'boolean',
        default: false
    },
    topTextfontFamily2: {
        type: 'string',
        default:'',
    },
    topTextfontWeight2: {
        type: 'string',
        default: '900',
    },
    topTextfontSubset2: {
        type: 'string',
        default: '',
    },
    topTextfontSizeType2: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeTypeMobile2: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeTypeTablet2: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeDesktop2: {
        type: 'number',
        default: '14',
    },
    topTextfontSizeMobile2: {
        type: 'number',
        default: '',
    },
    topTextfontSizeTablet2: {
        type: 'number',
        default: '',
    },
    topTextlineHeight2: {
        type: 'string',
        default: ''
    },
    topTextlineHeightMobile2: {
        type: 'string',
        default: ''
    },
    topTextlineHeightTablet2: {
        type: 'string',
        default: ''
    },
    topTextletterSpacing2: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingMobile2: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingTablet2: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingType2: {
        type: 'string',
        default: 'px'
    },
    topTextletterSpacingTypeTablet2: {
        type: 'string',
        default: 'px'
    },
    topTextletterSpacingTypeMobile2: {
        type: 'string',
        default: 'px'
    },
    topTextTransform2: {
        type: 'string',
        default: ''
    },
    topTextDecoration2: {
        type: 'string',
        default: ''
    },
    topTextfontStyle2: {
        type: 'string',
        default: ''
    },

    bottomText2:{
        type: 'string',
        default: 'Read Review'
    },    
    bottomTextColor2: {
        type: 'string',
        default:'#000000',
    },
    bottomTextLoadGoogleFonts2:{
        type: 'boolean',
        default: false
    },
    bottomTextfontFamily2: {
        type: 'string',
        default:'',
    },
    bottomTextfontWeight2: {
        type: 'string',
        default: '900',
    },
    bottomTextfontSubset2: {
        type: 'string',
        default: '',
    },
    bottomTextfontSizeType2: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeTypeMobile2: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeTypeTablet2: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeDesktop2: {
        type: 'number',
        default: '14',
    },
    bottomTextfontSizeMobile2: {
        type: 'number',
        default: '',
    },
    bottomTextfontSizeTablet2: {
        type: 'number',
        default: '',
    },
    bottomTextlineHeight2: {
        type: 'string',
        default: ''
    },
    bottomTextlineHeightMobile2: {
        type: 'string',
        default: ''
    },
    bottomTextlineHeightTablet2: {
        type: 'string',
        default: ''
    },
    bottomTextletterSpacing2: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingMobile2: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingTablet2: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingType2: {
        type: 'string',
        default: 'px'
    },
    bottomTextletterSpacingTypeTablet2: {
        type: 'string',
        default: 'px'
    },
    bottomTextletterSpacingTypeMobile2: {
        type: 'string',
        default: 'px'
    },
    bottomTextTransform2: {
        type: 'string',
        default: ''
    },
    bottomTextDecoration2: {
        type: 'string',
        default: ''
    },
    bottomTextfontStyle2: {
        type: 'string',
        default: ''
    },



    topText3:{
        type: 'string',
        default: 'Best Overall'
    },
    topTextColor3: {
        type: 'string',
        default:'#000000',
    },
    topTextBgColor3: {
        type: 'string',
        default:'#dfdcf4',
    },
    topTextLoadGoogleFonts3:{
        type: 'boolean',
        default: false
    },
    topTextfontFamily3: {
        type: 'string',
        default:'',
    },
    topTextfontWeight3: {
        type: 'string',
        default: '900',
    },
    topTextfontSubset3: {
        type: 'string',
        default: '',
    },
    topTextfontSizeType3: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeTypeMobile3: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeTypeTablet3: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeDesktop3: {
        type: 'number',
        default: '14',
    },
    topTextfontSizeMobile3: {
        type: 'number',
        default: '',
    },
    topTextfontSizeTablet3: {
        type: 'number',
        default: '',
    },
    topTextlineHeight3: {
        type: 'string',
        default: ''
    },
    topTextlineHeightMobile3: {
        type: 'string',
        default: ''
    },
    topTextlineHeightTablet3: {
        type: 'string',
        default: ''
    },
    topTextletterSpacing3: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingMobile3: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingTablet3: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingType3: {
        type: 'string',
        default: 'px'
    },
    topTextletterSpacingTypeTablet3: {
        type: 'string',
        default: 'px'
    },
    topTextletterSpacingTypeMobile3: {
        type: 'string',
        default: 'px'
    },
    topTextTransform3: {
        type: 'string',
        default: ''
    },
    topTextDecoration3: {
        type: 'string',
        default: ''
    },
    topTextfontStyle3: {
        type: 'string',
        default: ''
    },

    bottomText3:{
        type: 'string',
        default: 'Read Review'
    },    
    bottomTextColor3: {
        type: 'string',
        default:'#000000',
    },
    bottomTextLoadGoogleFonts3:{
        type: 'boolean',
        default: false
    },
    bottomTextfontFamily3: {
        type: 'string',
        default:'',
    },
    bottomTextfontWeight3: {
        type: 'string',
        default: '900',
    },
    bottomTextfontSubset3: {
        type: 'string',
        default: '',
    },
    bottomTextfontSizeType3: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeTypeMobile3: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeTypeTablet3: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeDesktop3: {
        type: 'number',
        default: '14',
    },
    bottomTextfontSizeMobile3: {
        type: 'number',
        default: '',
    },
    bottomTextfontSizeTablet3: {
        type: 'number',
        default: '',
    },
    bottomTextlineHeight3: {
        type: 'string',
        default: ''
    },
    bottomTextlineHeightMobile3: {
        type: 'string',
        default: ''
    },
    bottomTextlineHeightTablet3: {
        type: 'string',
        default: ''
    },
    bottomTextletterSpacing3: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingMobile3: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingTablet3: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingType3: {
        type: 'string',
        default: 'px'
    },
    bottomTextletterSpacingTypeTablet3: {
        type: 'string',
        default: 'px'
    },
    bottomTextletterSpacingTypeMobile3: {
        type: 'string',
        default: 'px'
    },
    bottomTextTransform3: {
        type: 'string',
        default: ''
    },
    bottomTextDecoration3: {
        type: 'string',
        default: ''
    },
    bottomTextfontStyle3: {
        type: 'string',
        default: ''
    },


    topText4:{
        type: 'string',
        default: 'Best Overall'
    },
    topTextColor4: {
        type: 'string',
        default:'#000000',
    },
    topTextBgColor4: {
        type: 'string',
        default:'#dfdcf4',
    },
    topTextLoadGoogleFonts4:{
        type: 'boolean',
        default: false
    },
    topTextfontFamily4: {
        type: 'string',
        default:'',
    },
    topTextfontWeight4: {
        type: 'string',
        default: '900',
    },
    topTextfontSubset4: {
        type: 'string',
        default: '',
    },
    topTextfontSizeType4: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeTypeMobile4: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeTypeTablet4: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeDesktop4: {
        type: 'number',
        default: '14',
    },
    topTextfontSizeMobile4: {
        type: 'number',
        default: '',
    },
    topTextfontSizeTablet4: {
        type: 'number',
        default: '',
    },
    topTextlineHeight4: {
        type: 'string',
        default: ''
    },
    topTextlineHeightMobile4: {
        type: 'string',
        default: ''
    },
    topTextlineHeightTablet4: {
        type: 'string',
        default: ''
    },
    topTextletterSpacing4: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingMobile4: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingTablet4: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingType4: {
        type: 'string',
        default: 'px'
    },
    topTextletterSpacingTypeTablet4: {
        type: 'string',
        default: 'px'
    },
    topTextletterSpacingTypeMobile4: {
        type: 'string',
        default: 'px'
    },
    topTextTransform4: {
        type: 'string',
        default: ''
    },
    topTextDecoration4: {
        type: 'string',
        default: ''
    },
    topTextfontStyle4: {
        type: 'string',
        default: ''
    },

    bottomText4:{
        type: 'string',
        default: 'Read Review'
    },    
    bottomTextColor4: {
        type: 'string',
        default:'#000000',
    },
    bottomTextLoadGoogleFonts4:{
        type: 'boolean',
        default: false
    },
    bottomTextfontFamily4: {
        type: 'string',
        default:'',
    },
    bottomTextfontWeight4: {
        type: 'string',
        default: '900',
    },
    bottomTextfontSubset4: {
        type: 'string',
        default: '',
    },
    bottomTextfontSizeType4: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeTypeMobile4: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeTypeTablet4: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeDesktop4: {
        type: 'number',
        default: '14',
    },
    bottomTextfontSizeMobile4: {
        type: 'number',
        default: '',
    },
    bottomTextfontSizeTablet4: {
        type: 'number',
        default: '',
    },
    bottomTextlineHeight4: {
        type: 'string',
        default: ''
    },
    bottomTextlineHeightMobile4: {
        type: 'string',
        default: ''
    },
    bottomTextlineHeightTablet4: {
        type: 'string',
        default: ''
    },
    bottomTextletterSpacing4: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingMobile4: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingTablet4: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingType4: {
        type: 'string',
        default: 'px'
    },
    bottomTextletterSpacingTypeTablet4: {
        type: 'string',
        default: 'px'
    },
    bottomTextletterSpacingTypeMobile4: {
        type: 'string',
        default: 'px'
    },
    bottomTextTransform4: {
        type: 'string',
        default: ''
    },
    bottomTextDecoration4: {
        type: 'string',
        default: ''
    },
    bottomTextfontStyle4: {
        type: 'string',
        default: ''
    },


    topText5:{
        type: 'string',
        default: 'Best Overall'
    },
    topTextColor5: {
        type: 'string',
        default:'#000000',
    },
    topTextBgColor5: {
        type: 'string',
        default:'#dfdcf4',
    },
    topTextLoadGoogleFonts5:{
        type: 'boolean',
        default: false
    },
    topTextfontFamily5: {
        type: 'string',
        default:'',
    },
    topTextfontWeight5: {
        type: 'string',
        default: '900',
    },
    topTextfontSubset5: {
        type: 'string',
        default: '',
    },
    topTextfontSizeType5: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeTypeMobile5: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeTypeTablet5: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeDesktop5: {
        type: 'number',
        default: '14',
    },
    topTextfontSizeMobile5: {
        type: 'number',
        default: '',
    },
    topTextfontSizeTablet5: {
        type: 'number',
        default: '',
    },
    topTextlineHeight5: {
        type: 'string',
        default: ''
    },
    topTextlineHeightMobile5: {
        type: 'string',
        default: ''
    },
    topTextlineHeightTablet5: {
        type: 'string',
        default: ''
    },
    topTextletterSpacing5: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingMobile5: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingTablet5: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingType5: {
        type: 'string',
        default: 'px'
    },
    topTextletterSpacingTypeTablet5: {
        type: 'string',
        default: 'px'
    },
    topTextletterSpacingTypeMobile5: {
        type: 'string',
        default: 'px'
    },
    topTextTransform5: {
        type: 'string',
        default: ''
    },
    topTextDecoration5: {
        type: 'string',
        default: ''
    },
    topTextfontStyle5: {
        type: 'string',
        default: ''
    },

    bottomText5:{
        type: 'string',
        default: 'Read Review'
    },    
    bottomTextColor5: {
        type: 'string',
        default:'#000000',
    },
    bottomTextLoadGoogleFonts5:{
        type: 'boolean',
        default: false
    },
    bottomTextfontFamily5: {
        type: 'string',
        default:'',
    },
    bottomTextfontWeight5: {
        type: 'string',
        default: '900',
    },
    bottomTextfontSubset5: {
        type: 'string',
        default: '',
    },
    bottomTextfontSizeType5: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeTypeMobile5: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeTypeTablet5: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeDesktop5: {
        type: 'number',
        default: '14',
    },
    bottomTextfontSizeMobile5: {
        type: 'number',
        default: '',
    },
    bottomTextfontSizeTablet5: {
        type: 'number',
        default: '',
    },
    bottomTextlineHeight5: {
        type: 'string',
        default: ''
    },
    bottomTextlineHeightMobile5: {
        type: 'string',
        default: ''
    },
    bottomTextlineHeightTablet5: {
        type: 'string',
        default: ''
    },
    bottomTextletterSpacing5: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingMobile5: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingTablet5: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingType5: {
        type: 'string',
        default: 'px'
    },
    bottomTextletterSpacingTypeTablet5: {
        type: 'string',
        default: 'px'
    },
    bottomTextletterSpacingTypeMobile5: {
        type: 'string',
        default: 'px'
    },
    bottomTextTransform5: {
        type: 'string',
        default: ''
    },
    bottomTextDecoration5: {
        type: 'string',
        default: ''
    },
    bottomTextfontStyle5: {
        type: 'string',
        default: ''
    },

    topText6:{
        type: 'string',
        default: 'Best Overall'
    },
    topTextColor6: {
        type: 'string',
        default:'#000000',
    },
    topTextBgColor6: {
        type: 'string',
        default:'#dfdcf4',
    },
    topTextLoadGoogleFonts6:{
        type: 'boolean',
        default: false
    },
    topTextfontFamily6: {
        type: 'string',
        default:'',
    },
    topTextfontWeight6: {
        type: 'string',
        default: '900',
    },
    topTextfontSubset6: {
        type: 'string',
        default: '',
    },
    topTextfontSizeType6: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeTypeMobile6: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeTypeTablet6: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeDesktop6: {
        type: 'number',
        default: '14',
    },
    topTextfontSizeMobile6: {
        type: 'number',
        default: '',
    },
    topTextfontSizeTablet6: {
        type: 'number',
        default: '',
    },
    topTextlineHeight6: {
        type: 'string',
        default: ''
    },
    topTextlineHeightMobile6: {
        type: 'string',
        default: ''
    },
    topTextlineHeightTablet6: {
        type: 'string',
        default: ''
    },
    topTextletterSpacing6: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingMobile6: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingTablet6: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingType6: {
        type: 'string',
        default: 'px'
    },
    topTextletterSpacingTypeTablet6: {
        type: 'string',
        default: 'px'
    },
    topTextletterSpacingTypeMobile6: {
        type: 'string',
        default: 'px'
    },
    topTextTransform6: {
        type: 'string',
        default: ''
    },
    topTextDecoration6: {
        type: 'string',
        default: ''
    },
    topTextfontStyle6: {
        type: 'string',
        default: ''
    },

    bottomText6:{
        type: 'string',
        default: 'Read Review'
    },    
    bottomTextColor6: {
        type: 'string',
        default:'#000000',
    },
    bottomTextLoadGoogleFonts6:{
        type: 'boolean',
        default: false
    },
    bottomTextfontFamily6: {
        type: 'string',
        default:'',
    },
    bottomTextfontWeight6: {
        type: 'string',
        default: '900',
    },
    bottomTextfontSubset6: {
        type: 'string',
        default: '',
    },
    bottomTextfontSizeType6: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeTypeMobile6: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeTypeTablet6: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeDesktop6: {
        type: 'number',
        default: '14',
    },
    bottomTextfontSizeMobile6: {
        type: 'number',
        default: '',
    },
    bottomTextfontSizeTablet6: {
        type: 'number',
        default: '',
    },
    bottomTextlineHeight6: {
        type: 'string',
        default: ''
    },
    bottomTextlineHeightMobile6: {
        type: 'string',
        default: ''
    },
    bottomTextlineHeightTablet6: {
        type: 'string',
        default: ''
    },
    bottomTextletterSpacing6: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingMobile6: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingTablet6: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingType6: {
        type: 'string',
        default: 'px'
    },
    bottomTextletterSpacingTypeTablet6: {
        type: 'string',
        default: 'px'
    },
    bottomTextletterSpacingTypeMobile6: {
        type: 'string',
        default: 'px'
    },
    bottomTextTransform6: {
        type: 'string',
        default: ''
    },
    bottomTextDecoration6: {
        type: 'string',
        default: ''
    },
    bottomTextfontStyle6: {
        type: 'string',
        default: ''
    },


    topText7:{
        type: 'string',
        default: 'Best Overall'
    },
    topTextColor7: {
        type: 'string',
        default:'#000000',
    },
    topTextBgColor7: {
        type: 'string',
        default:'#dfdcf4',
    },
    topTextLoadGoogleFonts7:{
        type: 'boolean',
        default: false
    },
    topTextfontFamily7: {
        type: 'string',
        default:'',
    },
    topTextfontWeight7: {
        type: 'string',
        default: '900',
    },
    topTextfontSubset7: {
        type: 'string',
        default: '',
    },
    topTextfontSizeType7: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeTypeMobile7: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeTypeTablet7: {
        type: 'string',
        default: 'px',
    },
    topTextfontSizeDesktop7: {
        type: 'number',
        default: '14',
    },
    topTextfontSizeMobile7: {
        type: 'number',
        default: '',
    },
    topTextfontSizeTablet7: {
        type: 'number',
        default: '',
    },
    topTextlineHeight7: {
        type: 'string',
        default: ''
    },
    topTextlineHeightMobile7: {
        type: 'string',
        default: ''
    },
    topTextlineHeightTablet7: {
        type: 'string',
        default: ''
    },
    topTextletterSpacing7: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingMobile7: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingTablet7: {
        type: 'number',
        default: ''
    },
    topTextletterSpacingType7: {
        type: 'string',
        default: 'px'
    },
    topTextletterSpacingTypeTablet7: {
        type: 'string',
        default: 'px'
    },
    topTextletterSpacingTypeMobile7: {
        type: 'string',
        default: 'px'
    },
    topTextTransform7: {
        type: 'string',
        default: ''
    },
    topTextDecoration7: {
        type: 'string',
        default: ''
    },
    topTextfontStyle7: {
        type: 'string',
        default: ''
    },

    bottomText7:{
        type: 'string',
        default: 'Read Review'
    },    
    bottomTextColor7: {
        type: 'string',
        default:'#000000',
    },
    bottomTextLoadGoogleFonts7:{
        type: 'boolean',
        default: false
    },
    bottomTextfontFamily7: {
        type: 'string',
        default:'',
    },
    bottomTextfontWeight7: {
        type: 'string',
        default: '900',
    },
    bottomTextfontSubset7: {
        type: 'string',
        default: '',
    },
    bottomTextfontSizeType7: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeTypeMobile7: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeTypeTablet7: {
        type: 'string',
        default: 'px',
    },
    bottomTextfontSizeDesktop7: {
        type: 'number',
        default: '14',
    },
    bottomTextfontSizeMobile7: {
        type: 'number',
        default: '',
    },
    bottomTextfontSizeTablet7: {
        type: 'number',
        default: '',
    },
    bottomTextlineHeight7: {
        type: 'string',
        default: ''
    },
    bottomTextlineHeightMobile7: {
        type: 'string',
        default: ''
    },
    bottomTextlineHeightTablet7: {
        type: 'string',
        default: ''
    },
    bottomTextletterSpacing7: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingMobile7: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingTablet7: {
        type: 'number',
        default: ''
    },
    bottomTextletterSpacingType7: {
        type: 'string',
        default: 'px'
    },
    bottomTextletterSpacingTypeTablet7: {
        type: 'string',
        default: 'px'
    },
    bottomTextletterSpacingTypeMobile7: {
        type: 'string',
        default: 'px'
    },
    bottomTextTransform7: {
        type: 'string',
        default: ''
    },
    bottomTextDecoration7: {
        type: 'string',
        default: ''
    },
    bottomTextfontStyle7: {
        type: 'string',
        default: ''
    },



    amazonprice1:{
        type: "string",
        default:'',
    },
    amazonpriceposition1: {
        type: 'string',
        default: 'Below Button'
    },
    amazonrating1:{
        type: "string",
        default:'',
    },
    amazonratingposition1: {
        type: 'string',
        default: 'Below Button'
    },


    amazonprice2:{
        type: "string",
        default:'',
    },
    amazonpriceposition2: {
        type: 'string',
        default: 'Below Button'
    },
    amazonrating2:{
        type: "string",
        default:'',
    },
    amazonratingposition2: {
        type: 'string',
        default: 'Below Button'
    },


    amazonprice3:{
        type: "string",
        default:'',
    },
    amazonpriceposition3: {
        type: 'string',
        default: 'Below Button'
    },
    amazonrating3:{
        type: "string",
        default:'',
    },
    amazonratingposition3: {
        type: 'string',
        default: 'Below Button'
    },


    amazonprice4:{
        type: "string",
        default:'',
    },
    amazonpriceposition4: {
        type: 'string',
        default: 'Below Button'
    },
    amazonrating4:{
        type: "string",
        default:'',
    },
    amazonratingposition4: {
        type: 'string',
        default: 'Below Button'
    },



    amazonprice5:{
        type: "string",
        default:'',
    },
    amazonpriceposition5: {
        type: 'string',
        default: 'Below Button'
    },
    amazonrating5:{
        type: "string",
        default:'',
    },
    amazonratingposition5: {
        type: 'string',
        default: 'Below Button'
    },


    amazonprice6:{
        type: "string",
        default:'',
    },
    amazonpriceposition6: {
        type: 'string',
        default: 'Below Button'
    },
    amazonrating6:{
        type: "string",
        default:'',
    },
    amazonratingposition6: {
        type: 'string',
        default: 'Below Button'
    },    


    amazonprice7:{
        type: "string",
        default:'',
    },
    amazonpriceposition7: {
        type: 'string',
        default: 'Below Button'
    },
    amazonrating7:{
        type: "string",
        default:'',
    },
    amazonratingposition7: {
        type: 'string',
        default: 'Below Button'
    },

   
}

export default attributes;