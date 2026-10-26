/**
 * Set inline styles.
 * @param  {object} props - The block object.
 * @return {object} The inline background type CSS.
 */

import affiliate_generateCSS from "./generatecss"
import { cssGradient, cssBoxShadow,cssWithoutSelector,cssTextShadow, cssBackground } from '../components/cssHelper'

function affiliate_styling( props, id ) {

	const {
    block_id,
    tdbox, th1_txt, th2_txt, th3_txt, th4_txt, HTxtColor, HBgColor, tEvenColor, tOddColor, btn_Htarget, btn_Style, tdTxtColor, th_fontsize, td_fontsize,
	btnLoadGoogleFonts,
	btnfontFamily,
	btnfontWeight,
	btnfontSubset,
	btnfontSizeType,
	btnfontSizeTypeMobile,
	btnfontSizeTypeTablet,
	btnfontSizeDesktop,
	btnfontSizeMobile,
	btnfontSizeTablet,
	btnlineHeight,
	btnlineHeightMobile,
	btnlineHeightTablet,
	btnletterSpacing,
	btnletterSpacingMobile,
	btnletterSpacingTablet,
	btnletterSpacingType,
	btnletterSpacingTypeTablet,
	btnletterSpacingTypeMobile,
	btntextTransform,
	btntextDecoration,
	btnfontStyle,
	btnBgColor,
	btnHoverBgColor,
	btnTextColor,
	btnTextHoverColor,
	btnBorderColor,
	btnBorderHoverColor,
	btnPadding,
	btnPaddingMobile,
	btnPaddingTablet,
	btnMargin,
	btnMarginMobile,
	btnMarginTablet,
	btnAlignment,
	btnAlignmentMobile,
	btnAlignmentTablet,
	btnBorder,
	btnBorderType,
	btnBorderMobile,
	btnBorderTablet,
	btnBorderRadius,
	btnBorderRadiusMobile,
	btnBorderRadiusTablet,
      // table heading
	thLoadGoogleFonts,
	thfontFamily,
	thfontWeight,
	thfontSubset,
	thfontSizeType,
	thfontSizeTypeMobile,
	thfontSizeTypeTablet,
	thfontSizeDesktop,
	thfontSizeMobile,
	thfontSizeTablet,
	thlineHeight,
	thlineHeightMobile,
	thlineHeightTablet,
	thletterSpacing,
	thletterSpacingMobile,
	thletterSpacingTablet,
	thletterSpacingType,
	thletterSpacingTypeTablet,
	thletterSpacingTypeMobile,
	thtextTransform,
	thtextDecoration,
	thfontStyle,
	thBgColor,
	thHoverBgColor,
	thTextColor,
	thTextHoverColor,
	thBorderColor,
	thBorderHoverColor,
      // content
    cntnLoadGoogleFonts,
	cntnfontFamily,
	cntnfontWeight,
	cntnfontSubset,
	cntnfontSizeType,
	cntnfontSizeTypeMobile,
	cntnfontSizeTypeTablet,
	cntnfontSizeDesktop,
	cntnfontSizeMobile,
	cntnfontSizeTablet,
	cntnlineHeight,
	cntnlineHeightMobile,
	cntnlineHeightTablet,
	cntnletterSpacing,
	cntnletterSpacingMobile,
	cntnletterSpacingTablet,
	cntnletterSpacingType,
	cntnletterSpacingTypeTablet,
	cntnletterSpacingTypeMobile,
	cntntextTransform,
	cntntextDecoration,
	cntnfontStyle,
	btnCustomWidth,
	boxShadow,
	badgeTextColor,
	badgeBgColor,
	readMoreColor,
	// title
	titlefontFamily,
	titlefontWeight,
	titlefontSubset,
	titlefontSizeType,
	titlefontSizeTypeMobile,
	titlefontSizeTypeTablet,
	titlefontSizeDesktop,
	titlefontSizeMobile,
	titlefontSizeTablet,
	titlelineHeight,
	titlelineHeightMobile,
	titlelineHeightTablet,
	titleletterSpacing,
	titleletterSpacingMobile,
	titleletterSpacingTablet,
	titleletterSpacingType,
	titleletterSpacingTypeTablet,
	titleletterSpacingTypeMobile,
	titletextTransform,
	titletextDecoration,
	titlefontStyle,
	// badge
	badgefontFamily,
	badgefontWeight,
	badgefontSubset,
	badgefontSizeType,
	badgefontSizeTypeMobile,
	badgefontSizeTypeTablet,
	badgefontSizeDesktop,
	badgefontSizeMobile,
	badgefontSizeTablet,
	badgelineHeight,
	badgelineHeightMobile,
	badgelineHeightTablet,
	badgeletterSpacing,
	badgeletterSpacingMobile,
	badgeletterSpacingTablet,
	badgeletterSpacingType,
	badgeletterSpacingTypeTablet,
	badgeletterSpacingTypeMobile,
	badgetextTransform,
	badgetextDecoration,
	badgefontStyle,
	//Read More
	readMorefontFamily,
	readMorefontWeight,
	readMorefontSubset,
	readMorefontSizeType,
	readMorefontSizeTypeMobile,
	readMorefontSizeTypeTablet,
	readMorefontSizeDesktop,
	readMorefontSizeMobile,
	readMorefontSizeTablet,
	readMorelineHeight,
	readMorelineHeightMobile,
	readMorelineHeightTablet,
	readMoreletterSpacing,
	readMoreletterSpacingMobile,
	readMoreletterSpacingTablet,
	readMoreletterSpacingType,
	readMoreletterSpacingTypeTablet,
	readMoreletterSpacingTypeMobile,
	readMoretextTransform,
	readMoretextDecoration,
	readMorefontStyle,
	itemRatingBgColor,
	itemRatingTextColor,
	titleTextColor,
	tableStyle,
	titleTag1,

	btnBg,
    btnBgHover,


	} = props.attributes

	const Tag1 = titleTag1
  var mobile_selectors = {}
  var tablet_selectors = {}
  var style = {}


  if(btnBg.bgDefaultColor == ''){
      btnBg.bgDefaultColor = abglobalsetting.data_btnbg;
  }
  if(btnBgHover.bgDefaultColor == ''){
      btnBgHover.bgDefaultColor = abglobalsetting.data_btnhoverbg;
  }

  var styleBtnBg = cssBackground(btnBg);
  var styleBtnBgHover = cssBackground(btnBgHover);
  
        var selectors = {
			" .affiliate-cmpr-inner":{
				"box-shadow":cssBoxShadow(boxShadow)
			},
			" span.affiliate-txtbox-title":{
				"font-family": titlefontFamily,
				"font-size": titlefontSizeDesktop + titlefontSizeType,
				"font-style":titlefontStyle,
				"font-weight":titlefontWeight,
				"letter-spacing":titleletterSpacing + titleletterSpacingType,
				"line-height":titlelineHeight,
				"text-decoration":titletextDecoration,
				"text-transform":titletextTransform,
				"color":titleTextColor,
			},
			" .affiliate-item-badge":{
				"font-family": badgefontFamily,
				"font-size": badgefontSizeDesktop + badgefontSizeType,
				"font-style":badgefontStyle,
				"font-weight":badgefontWeight,
				"letter-spacing":badgeletterSpacing + badgeletterSpacingType,
				"line-height":badgelineHeight,
				"text-decoration":badgetextDecoration,
				"text-transform":badgetextTransform,
				"color": badgeTextColor,
				"background": badgeBgColor
			},
			" .affiliate-cmpr-readmore":{
				"font-family": readMorefontFamily,
				"font-size": readMorefontSizeDesktop + readMorefontSizeType,
				"font-style":readMorefontStyle,
				"font-weight":readMorefontWeight,
				"letter-spacing":readMoreletterSpacing + readMoreletterSpacingType,
				"line-height":readMorelineHeight,
				"text-decoration":readMoretextDecoration,
				"text-transform":readMoretextTransform,
				"color": readMoreColor,
			},
			" .affiliate-compr-btn":{
				"font-family": btnfontFamily,
				"font-size": btnfontSizeDesktop + btnfontSizeType,
				"font-style":btnfontStyle,
				"font-weight":btnfontWeight,
				"letter-spacing":btnletterSpacing + btnletterSpacingType,
				"line-height":btnlineHeight,
				"text-decoration":btntextDecoration,
				"text-transform":btntextTransform,
				"background":styleBtnBg,
				"color":( btnTextColor && undefined !== btnTextColor && null !== btnTextColor)?btnTextColor:abglobalsetting.data_btntext,
	            "border-color":( btnBorderColor && undefined !== btnBorderColor && null !== btnBorderColor)?btnBorderColor:abglobalsetting.data_btnborder,
	            "border-style":( btnBorderType && undefined !== btnBorderType && null !== btnBorderType)?btnBorderType:abglobalsetting.data_btnbordertype,
				"border-radius":( btnBorderRadius && undefined !== btnBorderRadius[ 'top' ] && null !== btnBorderRadius[ 'top' ] ? btnBorderRadius[ 'top' ] + btnBorderRadius['unit'] + ' ' + btnBorderRadius[ 'right' ] + btnBorderRadius['unit'] + ' ' + btnBorderRadius[ 'bottom' ] + btnBorderRadius['unit'] + ' ' + btnBorderRadius[ 'left' ] + btnBorderRadius['unit'] : '' ),
				"border-width" : ( btnBorder && undefined !== btnBorder[ 'top' ] && null !== btnBorder[ 'top' ] ? btnBorder[ 'top' ] + btnBorder['unit'] + ' ' + btnBorder[ 'right' ] + btnBorder['unit'] + ' ' + btnBorder[ 'bottom' ] + btnBorder['unit'] + ' ' + btnBorder[ 'left' ] + btnBorder['unit'] : '' ),
				"padding" : ( btnPadding && undefined !== btnPadding[ 'top' ] && null !== btnPadding[ 'top' ] ? btnPadding[ 'top' ] + btnPadding['unit'] + ' ' + btnPadding[ 'right' ] + btnPadding['unit'] + ' ' + btnPadding[ 'bottom' ] + btnPadding['unit'] + ' ' + btnPadding[ 'left' ] + btnPadding['unit'] : '' ),
				"margin" : ( btnMargin && undefined !== btnMargin[ 'top' ] && null !== btnMargin[ 'top' ] ? btnMargin[ 'top' ] + btnMargin['unit'] + ' ' + btnMargin[ 'right' ] + btnMargin['unit'] + ' ' + btnMargin[ 'bottom' ] + btnMargin['unit'] + ' ' + btnMargin[ 'left' ] + btnMargin['unit'] : '' ),
		  },
		  " .af-is-custom":{
			"width":btnCustomWidth + 'px',
		  },
		  " .affiliate-col-btn":{
			  "text-align":btnAlignment
		  },
          " .affiliate-compr-th th":{
              "font-family": thfontFamily,
              "font-size": thfontSizeDesktop + thfontSizeType,
              "font-style":thfontStyle,
              "font-weight":thfontWeight,
              "letter-spacing":thletterSpacing + thletterSpacingType,
              "line-height":thlineHeight,
              "text-decoration":thtextDecoration,
              "text-transform":thtextTransform,
              "background":HBgColor,
              "color":HTxtColor, 
          },
          " .affiliate-compr-cntn .affiliate-col-ct1 li, .affiliate-compr-cntn .affiliate-col-ct2 li":{
              "font-family": cntnfontFamily,
              "font-size": cntnfontSizeDesktop + cntnfontSizeType,
              "font-style":cntnfontStyle,
              "font-weight":cntnfontWeight,
              "letter-spacing":cntnletterSpacing + cntnletterSpacingType,
              "line-height":cntnlineHeight,
              "text-decoration":cntntextDecoration,
              "text-transform":cntntextTransform,
              "color":tdTxtColor,
        },
          " .affiliate-compr-btn:hover":{
              "background":styleBtnBgHover,
              "border-color":( btnBorderHoverColor && undefined !== btnBorderHoverColor && null !== btnBorderHoverColor)?btnBorderHoverColor:abglobalsetting.data_btnborder,
              "color":( btnTextHoverColor && undefined !== btnTextHoverColor && null !== btnTextHoverColor)?btnTextHoverColor:abglobalsetting.data_btntext
        },
        " tr:nth-of-type(odd)":{
          "background":tOddColor,
        },
        " tr:nth-of-type(even)":{
          "background":tEvenColor,
		},
		" .affiliate-img-rating":{
			"color":itemRatingTextColor,
			"background":itemRatingBgColor,
		},
     }
	 
	   if( 'tbl-style-1' === tableStyle) {
			selectors[" .affiliate-txtbox-title"] = {
				"font-family": titlefontFamily,
				"font-size": titlefontSizeDesktop + titlefontSizeType,
				"font-style":titlefontStyle,
				"font-weight":titlefontWeight,
				"letter-spacing":titleletterSpacing + titleletterSpacingType,
				"line-height":titlelineHeight,
				"text-decoration":titletextDecoration,
				"text-transform":titletextTransform,
				"color":titleTextColor,
			}
	}
	 	
        tablet_selectors = {
          
       }

        mobile_selectors = {
          
        }



	var affiliate_styling_css = ""

	affiliate_styling_css = affiliate_generateCSS( selectors, `#${id}-${ props.clientId }` )

  affiliate_styling_css += affiliate_generateCSS( tablet_selectors, `#${id}-${ props.clientId }`, true, "tablet" )

  affiliate_styling_css += affiliate_generateCSS( mobile_selectors, `#${id}-${ props.clientId }`, true, "mobile" )

	return affiliate_styling_css
}

export default affiliate_styling
