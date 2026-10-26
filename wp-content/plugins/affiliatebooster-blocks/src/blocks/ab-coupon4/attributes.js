const attributes = {
    uniqueId: {
        type: 'string',
        default: ''
    },
    block_id: {
        type: 'string',
        default: ''
    },
    boxShadow:{
        type: 'object',
        default: {
            openShadow: true,
            inset:'',
            horizontal: 0,
            vertical: 0,
            blur: 0,
            spread: 0,
            color:'rgba(0, 0, 0, 0.14)'
        }
    },   
    boxBgColor: {
        type: 'string',
        default:'#ffffff',
    },
    boxBorderType:{
        type: 'string',
        default:'dashed',
    },
    boxBorderColor:{
        type: 'string',
        default:'',
    },
    boxBorderWidth:{
        type: 'object',
        default: {
            unit:'px',
            top: 2,
            right: 2,
            bottom: 2,
            left: 2,
        }
    }, 
    designType:{
        type: "string",
        default:'ab-fixed-design',
    },


    image: { 
        type: 'object', 
        default: {
            id:'',
            url:'',
            alt:'',
        } 
    },
    imagePadding:{
        type: 'object', 
        default: []
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
    imagePaddingMobile:{
        type: 'object', 
        default: []
    },
    imagePaddingTablet:{
        type: 'object', 
        default: []
    },
    imageSize:{
		type: "string",
		default: "full",
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


    //
    showTitle:{
        type: 'boolean',
        default: true
    },
    couponMainTitle:{
        type: 'string',
        default: 'Product Headline Goes Here'
    },  
    titleTag1: {
        type: 'string',
        default: 'p'
    }, 
    titleLoadGoogleFonts:{
        type: 'boolean',
        default: false
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
        default: '700',
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
        default: '18',
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


    showContent:{
        type: 'boolean',
        default: true
    }, 
    couponContent: {
        type: 'string',
        default: 'Product description would go here. Edit this area to write your product description.'
    },
    contentLoadGoogleFonts:{
        type: 'boolean',
        default: false
    },
    contentColor:{
        type: 'string',
        default: '#000000'
    },
    contentfontFamily: {
        type: 'string',
        default:'',
    },
    contentfontWeight: {
        type: 'string',
        default: '400',
    },
    contentfontSubset: {
        type: 'string',
        default: '',
    },
    contentfontSizeType: {
        type: 'string',
        default: 'px',
    },
    contentfontSizeTypeMobile: {
        type: 'string',
        default: 'px',
    },
    contentfontSizeTypeTablet: {
        type: 'string',
        default: 'px',
    },
    contentfontSizeDesktop: {
        type: 'number',
        default: '18',
    },
    contentfontSizeMobile: {
        type: 'number',
        default: '',
    },
    contentfontSizeTablet: {
        type: 'number',
        default: '',
    },
    contentlineHeight: {
        type: 'number',
        default: ''
    },
    contentlineHeightMobile: {
        type: 'number',
        default: ''
    },
    contentlineHeightTablet: {
        type: 'number',
        default: ''
    },
    contentletterSpacing: {
        type: 'number',
        default: ''
    },
    contentletterSpacingMobile: {
        type: 'number',
        default: ''
    },
    contentletterSpacingTablet: {
        type: 'number',
        default: ''
    },
    contentletterSpacingType: {
        type: 'string',
        default: 'px'
    },
    contentletterSpacingTypeTablet: {
        type: 'string',
        default: 'px'
    },
    contentletterSpacingTypeMobile: {
        type: 'string',
        default: 'px'
    },
    contenttextTransform: {
        type: 'string',
        default: ''
    },
    contenttextDecoration: {
        type: 'string',
        default: ''
    },
    contentfontStyle: {
        type: 'string',
        default: ''
    },
    contentTextColor: {
        type: 'string',
        default: ''
    },
    contentAlignment: {
        type: 'string',
        default: 'left',
    },
    contentAlignmentMobile: {
        type: 'string',
        default: 'left',
    },
    contentAlignmentTablet: {
        type: 'string',
        default: 'left',
    },

    
    showRating:{
        type: 'boolean',
        default: true
    },
    showRatingNumber:{
        type: 'boolean',
        default: true
    },
    RatingNumber: {
        type: 'string',
        default: '0',
    },
    rateSize:{
        type: 'string',
        default:'20',
    },
    rateColor:{
        type: 'string',
        default:'#000000',
    },
    ratings:{
        type:'number',
        default:5
    },
    starsSize:{
        type:'string',
        default: 18
    },
    starsSizeMobile:{
        type:'string',
        default: 18
    },
    starsSizeTablet:{
        type:'string',
        default: 18
    },
    selectedStars: {
        type: 'number',
        default: 0
    },
    ratingsColor:{
        type: 'string',
        default: '#ffa500'
    },
    ratingAlignemt:{
        type:'string',
        default:'left',
    },
    ratingAlignemtTablet:{
        type:'string',
        default:'left',
    },
    ratingAlignemtMobile:{
        type:'string',
        default:'center',
    }, 
    

    //

    showVerified:{
        type: 'boolean',
        default: true
    },
    Verified: {
        type: 'string',
        default: 'Verified',
    },
    verifiedLoadGoogleFonts:{
        type: 'boolean',
        default: false
    }, 
    verifiedColor: {
        type: 'string',
        default: '#02bb8c'
    },
    verifiedfontFamily: {
        type: 'string',
        default:'',
    },
    verifiedfontWeight: {
        type: 'string',
        default: '',
    },
    verifiedfontSubset: {
        type: 'string',
        default: '',
    },
    verifiedfontSizeType: {
        type: 'string',
        default: 'px',
    },
    verifiedfontSizeTypeMobile: {
        type: 'string',
        default: 'px',
    },
    verifiedfontSizeTypeTablet: {
        type: 'string',
        default: 'px',
    },
    verifiedfontSizeDesktop: {
        type: 'number',
        default: '14',
    },
    verifiedfontSizeMobile: {
        type: 'number',
        default: '12',
    },
    verifiedfontSizeTablet: {
        type: 'number',
        default: '14',
    },
    verifiedlineHeight: {
        type: 'number',
        default: ''
    },
    verifiedlineHeightMobile: {
        type: 'number',
        default: ''
    },
    verifiedlineHeightTablet: {
        type: 'number',
        default: ''
    },
    verifiedletterSpacing: {
        type: 'number',
        default: '0'
    },
    verifiedletterSpacingMobile: {
        type: 'number',
        default: ''
    },
    verifiedletterSpacingTablet: {
        type: 'number',
        default: ''
    },
    verifiedletterSpacingType: {
        type: 'string',
        default: 'px'
    },
    verifiedletterSpacingTypeTablet: {
        type: 'string',
        default: 'px'
    },
    verifiedletterSpacingTypeMobile: {
        type: 'string',
        default: 'px'
    },
    verifiedtextTransform: {
        type: 'string',
        default: ''
    },
    verifiedtextDecoration: {
        type: 'string',
        default: ''
    },
    verifiedfontStyle: {
        type: 'string',
        default: ''
    },
    verifiedTextColor: {
        type: 'string',
        default: ''
    },



    //

    showStaffPick:{
        type: 'boolean',
        default: true
    },
    StaffPick: {
        type: 'string',
        default: 'Staff Pick',
    },
    staffpickLoadGoogleFonts:{
        type: 'boolean',
        default: false
    },    
    staffpickColor: {
        type: 'string',
        default: '#e9385a'
    }, 
    staffpickfontFamily: {
        type: 'string',
        default:'',
    },
    staffpickfontWeight: {
        type: 'string',
        default: '',
    },
    staffpickfontSubset: {
        type: 'string',
        default: '',
    },
    staffpickfontSizeType: {
        type: 'string',
        default: 'px',
    },
    staffpickfontSizeTypeMobile: {
        type: 'string',
        default: 'px',
    },
    staffpickfontSizeTypeTablet: {
        type: 'string',
        default: 'px',
    },
    staffpickfontSizeDesktop: {
        type: 'number',
        default: '14',
    },
    staffpickfontSizeMobile: {
        type: 'number',
        default: '12',
    },
    staffpickfontSizeTablet: {
        type: 'number',
        default: '14',
    },
    staffpicklineHeight: {
        type: 'number',
        default: ''
    },
    staffpicklineHeightMobile: {
        type: 'number',
        default: ''
    },
    staffpicklineHeightTablet: {
        type: 'number',
        default: ''
    },
    staffpickletterSpacing: {
        type: 'number',
        default: '0'
    },
    staffpickletterSpacingMobile: {
        type: 'number',
        default: ''
    },
    staffpickletterSpacingTablet: {
        type: 'number',
        default: ''
    },
    staffpickletterSpacingType: {
        type: 'string',
        default: 'px'
    },
    staffpickletterSpacingTypeTablet: {
        type: 'string',
        default: 'px'
    },
    staffpickletterSpacingTypeMobile: {
        type: 'string',
        default: 'px'
    },
    staffpicktextTransform: {
        type: 'string',
        default: ''
    },
    staffpicktextDecoration: {
        type: 'string',
        default: ''
    },
    staffpickfontStyle: {
        type: 'string',
        default: ''
    },
    staffpickTextColor: {
        type: 'string',
        default: ''
    },


    //
    showPeopleUsed:{
        type: 'boolean',
        default: true
    },
    PeopleUsed: {
        type: 'string',
        default: 'People Used',
    }, 
    peopleUsedLoadGoogleFonts:{
        type: 'boolean',
        default: false
    },    
    peopleUsedColor: {
        type: 'string',
        default: '#000000'
    },
    peopleUsedfontFamily: {
        type: 'string',
        default:'',
    },
    peopleUsedfontWeight: {
        type: 'string',
        default: '',
    },
    peopleUsedfontSubset: {
        type: 'string',
        default: '',
    },
    peopleUsedfontSizeType: {
        type: 'string',
        default: 'px',
    },
    peopleUsedfontSizeTypeMobile: {
        type: 'string',
        default: 'px',
    },
    peopleUsedfontSizeTypeTablet: {
        type: 'string',
        default: 'px',
    },
    peopleUsedfontSizeDesktop: {
        type: 'number',
        default: '14',
    },
    peopleUsedfontSizeMobile: {
        type: 'number',
        default: '12',
    },
    peopleUsedfontSizeTablet: {
        type: 'number',
        default: '14',
    },
    peopleUsedlineHeight: {
        type: 'number',
        default: ''
    },
    peopleUsedlineHeightMobile: {
        type: 'number',
        default: ''
    },
    peopleUsedlineHeightTablet: {
        type: 'number',
        default: ''
    },
    peopleUsedletterSpacing: {
        type: 'number',
        default: '0'
    },
    peopleUsedletterSpacingMobile: {
        type: 'number',
        default: ''
    },
    peopleUsedletterSpacingTablet: {
        type: 'number',
        default: ''
    },
    peopleUsedletterSpacingType: {
        type: 'string',
        default: 'px'
    },
    peopleUsedletterSpacingTypeTablet: {
        type: 'string',
        default: 'px'
    },
    peopleUsedletterSpacingTypeMobile: {
        type: 'string',
        default: 'px'
    },
    peopleUsedtextTransform: {
        type: 'string',
        default: ''
    },
    peopleUsedtextDecoration: {
        type: 'string',
        default: ''
    },
    peopleUsedfontStyle: {
        type: 'string',
        default: ''
    },
    peopleUsedTextColor: {
        type: 'string',
        default: ''
    },


    //    
    showCouponLeft:{
        type: 'boolean',
        default: true
    },
    OnlyLeft: {
        type: 'string',
        default: 'Only Left',
    }, 
    onlyLeftLoadGoogleFonts:{
        type: 'boolean',
        default: false
    },    
    onlyLeftColor: {
        type: 'string',
        default: '#000000'
    },
    onlyLeftfontFamily: {
        type: 'string',
        default:'',
    },
    onlyLeftfontWeight: {
        type: 'string',
        default: '',
    },
    onlyLeftfontSubset: {
        type: 'string',
        default: '',
    },
    onlyLeftfontSizeType: {
        type: 'string',
        default: 'px',
    },
    onlyLeftfontSizeTypeMobile: {
        type: 'string',
        default: 'px',
    },
    onlyLeftfontSizeTypeTablet: {
        type: 'string',
        default: 'px',
    },
    onlyLeftfontSizeDesktop: {
        type: 'number',
        default: '14',
    },
    onlyLeftfontSizeMobile: {
        type: 'number',
        default: '12',
    },
    onlyLeftfontSizeTablet: {
        type: 'number',
        default: '14',
    },
    onlyLeftlineHeight: {
        type: 'number',
        default: ''
    },
    onlyLeftlineHeightMobile: {
        type: 'number',
        default: ''
    },
    onlyLeftlineHeightTablet: {
        type: 'number',
        default: ''
    },
    onlyLeftletterSpacing: {
        type: 'number',
        default: '0'
    },
    onlyLeftletterSpacingMobile: {
        type: 'number',
        default: ''
    },
    onlyLeftletterSpacingTablet: {
        type: 'number',
        default: ''
    },
    onlyLeftletterSpacingType: {
        type: 'string',
        default: 'px'
    },
    onlyLeftletterSpacingTypeTablet: {
        type: 'string',
        default: 'px'
    },
    onlyLeftletterSpacingTypeMobile: {
        type: 'string',
        default: 'px'
    },
    onlyLefttextTransform: {
        type: 'string',
        default: ''
    },
    onlyLefttextDecoration: {
        type: 'string',
        default: ''
    },
    onlyLeftfontStyle: {
        type: 'string',
        default: ''
    },
    onlyLeftTextColor: {
        type: 'string',
        default: ''
    },


    //
    showOnGoingOffer:{
        type: 'boolean',
        default: true 
    },
    onGoingOffer: {
        type: 'string',
        default: 'On-Going Offer',
    }, 
    onGoingOfferLoadGoogleFonts:{
        type: 'boolean',
        default: false
    },    
    onGoingOfferColor: {
        type: 'string',
        default: '#000000'
    },
    onGoingOfferfontFamily: {
        type: 'string',
        default:'',
    },
    onGoingOfferfontWeight: {
        type: 'string',
        default: '',
    },
    onGoingOfferfontSubset: {
        type: 'string',
        default: '',
    },
    onGoingOfferfontSizeType: {
        type: 'string',
        default: 'px',
    },
    onGoingOfferfontSizeTypeMobile: {
        type: 'string',
        default: 'px',
    },
    onGoingOfferfontSizeTypeTablet: {
        type: 'string',
        default: 'px',
    },
    onGoingOfferfontSizeDesktop: {
        type: 'number',
        default: '14',
    },
    onGoingOfferfontSizeMobile: {
        type: 'number',
        default: '12',
    },
    onGoingOfferfontSizeTablet: {
        type: 'number',
        default: '14',
    },
    onGoingOfferlineHeight: {
        type: 'number',
        default: ''
    },
    onGoingOfferlineHeightMobile: {
        type: 'number',
        default: ''
    },
    onGoingOfferlineHeightTablet: {
        type: 'number',
        default: ''
    },
    onGoingOfferletterSpacing: {
        type: 'number',
        default: '0'
    },
    onGoingOfferletterSpacingMobile: {
        type: 'number',
        default: ''
    },
    onGoingOfferletterSpacingTablet: {
        type: 'number',
        default: ''
    },
    onGoingOfferletterSpacingType: {
        type: 'string',
        default: 'px'
    },
    onGoingOfferletterSpacingTypeTablet: {
        type: 'string',
        default: 'px'
    },
    onGoingOfferletterSpacingTypeMobile: {
        type: 'string',
        default: 'px'
    },
    onGoingOffertextTransform: {
        type: 'string',
        default: ''
    },
    onGoingOffertextDecoration: {
        type: 'string',
        default: ''
    },
    onGoingOfferfontStyle: {
        type: 'string',
        default: ''
    },
    onGoingOfferTextColor: {
        type: 'string',
        default: ''
    },
    
    
    
    // CTA BUTTON
    showButton:{
        type: 'boolean',
        default: true
    },
    enableGlimmerEffect:{
        type: 'boolean',
        default: false
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
        default: '18',
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
    btnBorderType:{
        type: 'string',
        default: ''
    },
	btnBorderHoverColor:{
		type: 'string',
        default: ''
    },
    spBtnText:{
        type: 'string',
        default: 'Activate Deal'
    },
    spBtnHref:{
        type: 'string',
        default: ''
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
    btnRel: {
        type: 'string',
        default: 'nofollow'
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
    btn_Htarget:{
        type: 'boolean',
        default: false
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

}

export default attributes;