/**
 * Set inline styles.
 * @param  {object} props - The block object.
 * @return {object} The inline background type CSS.
 */

import affiliate_generateCSS from "./generatecss"
import { cssGradient, cssBoxShadow,cssWithoutSelector,cssTextShadow, cssBackground } from '../components/cssHelper'
function affiliate_styling( props, id ) {

	const {
    uniqueId,
    block_id,
    className,
    btnBg,
    btnBgHover,
    spTitle,
    spSubTitle,
    titleLoadGoogleFonts,
    spBtnText,
    spContent,
    spBtnHref,
    titleTextColor,
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
    titleAlignment,
    titleAlignmentMobile,
    titleAlignmentTablet,
    // CTA BUTTON
    btnLoadGoogleFonts,
    btnfontFamily,
    btnfontWeight,
    listItems,
    bulletStyle,
    listType,
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
    btn_Htarget,
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
    // CTA content
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
    cntnTextColor,
    // Box
    boxPadding,
    boxPaddingMobile,
    boxPaddingTablet,
    boxMargin,
    boxMarginMobile,
    boxMarginTablet,
    image,
    // Sub Title
    subtitleLoadGoogleFonts,
    subtitleTextColor,
    subtitlefontFamily,
    subtitlefontWeight,
    subtitlefontSubset,
    subtitlefontSizeType,
    subtitlefontSizeTypeMobile,
    subtitlefontSizeTypeTablet,
    subtitlefontSizeDesktop,
    subtitlefontSizeMobile,
    subtitlefontSizeTablet,
    subtitlelineHeight,
    subtitlelineHeightMobile,
    subtitlelineHeightTablet,
    subtitleletterSpacing,
    subtitleletterSpacingMobile,
    subtitleletterSpacingTablet,
    subtitleletterSpacingType,
    subtitleletterSpacingTypeTablet,
    subtitleletterSpacingTypeMobile,
    subtitletextTransform,
    subtitletextDecoration,
    subtitlefontStyle,
    subtitleAlignment,
    subtitleAlignmentMobile,
    subtitleAlignmentTablet,
    boxBorderType,
    boxBorderColor,
    boxBorderWidth,
    boxShadow,
    cntnAlignment,
    cntnAlignmentMobile,
    cntnAlignmentTablet,
    btnSize,
    buttonIcon,
    iconPosition,
    buttonWidth,
    bntRounded,
    imagePadding,
    imagePaddingMobile,
    imagePaddingTablet,
    customImgWidth,
    customImgWidthMobile,
    customImgWidthTablet,
    iconColor,

    boxBackgroundColor,

	} = props.attributes

  var mobile_selectors = {}
  var tablet_selectors = {}
  var style = {}
  // var styleBackground = cssBackground(boxBg);

  var styleBtnBg = cssBackground(btnBg);
  var styleBtnBgHover = cssBackground(btnBgHover);
  
        var selectors = {
          " .affiliate-sp-wrapper":{
                "padding" : ( boxPadding && undefined !== boxPadding[ 'top' ] && null !== boxPadding[ 'top' ] ? boxPadding[ 'top' ] + boxPadding['unit'] + ' ' + boxPadding[ 'right' ] + boxPadding['unit'] + ' ' + boxPadding[ 'bottom' ] + boxPadding['unit'] + ' ' + boxPadding[ 'left' ] + boxPadding['unit'] : '' ),
                "margin" : ( boxMargin && undefined !== boxMargin[ 'top' ] && null !== boxMargin[ 'top' ] ? boxMargin[ 'top' ] + boxMargin['unit'] + ' ' + boxMargin[ 'right' ] + boxMargin['unit'] + ' ' + boxMargin[ 'bottom' ] + boxMargin['unit'] + ' ' + boxMargin[ 'left' ] + boxMargin['unit'] : '' ),
                "border-style":boxBorderType,
                "border-color":boxBorderColor,
                "border-width" : ( boxBorderWidth && undefined !== boxBorderWidth[ 'top' ] && null !== boxBorderWidth[ 'top' ] ? boxBorderWidth[ 'top' ] + boxBorderWidth['unit'] + ' ' + boxBorderWidth[ 'right' ] + boxBorderWidth['unit'] + ' ' + boxBorderWidth[ 'bottom' ] + boxBorderWidth['unit'] + ' ' + boxBorderWidth[ 'left' ] + boxBorderWidth['unit'] : '' ),
                "box-shadow":cssBoxShadow(boxShadow),
                "background":boxBackgroundColor,
            },
          " .affiliate-sp-title":{
                "font-family": titlefontFamily,
                "font-size": titlefontSizeDesktop + titlefontSizeType,
                "font-style":titlefontStyle,
                "font-weight":titlefontWeight,
                "letter-spacing":titleletterSpacing + titleletterSpacingType,
                "line-height":titlelineHeight,
                "text-decoration":titletextDecoration,
                "text-transform":titletextTransform,
                "color":titleTextColor,
                "text-align":titleAlignment,
          },
          " .affiliate-list:before":{
                "color":iconColor
          },
          " .affiliate-sp-subtitle":{
              "font-family": subtitlefontFamily,
              "font-size": subtitlefontSizeDesktop + subtitlefontSizeType,
              "font-style":subtitlefontStyle,
              "font-weight":subtitlefontWeight,
              "letter-spacing":subtitleletterSpacing + subtitleletterSpacingType,
              "line-height":subtitlelineHeight,
              "text-decoration":subtitletextDecoration,
              "text-transform":subtitletextTransform,
              "color":subtitleTextColor,
              "text-align":subtitleAlignment,
          },
          " .affiliate-sp-content li, .affiliate-pr-pcontent":{
              "font-family": cntnfontFamily,
              "font-size": cntnfontSizeDesktop + cntnfontSizeType,
              "font-style":cntnfontStyle,
              "font-weight":cntnfontWeight,
              "letter-spacing":cntnletterSpacing + cntnletterSpacingType,
              "line-height":cntnlineHeight,
              "text-decoration":cntntextDecoration,
              "text-transform":cntntextTransform,
              "color":cntnTextColor,
              "text-align":cntnAlignment,
        },
        " .affiliate-sp-btn-wrapper":{
              "justify-content":btnAlignment,
        },
        " .affiliate-sp-image":{
              "padding" : ( imagePadding && undefined !== imagePadding[ 'top' ] && null !== imagePadding[ 'top' ] ? imagePadding[ 'top' ] + imagePadding['unit'] + ' ' + imagePadding[ 'right' ] + imagePadding['unit'] + ' ' + imagePadding[ 'bottom' ] + imagePadding['unit'] + ' ' + imagePadding[ 'left' ] + imagePadding['unit'] : '' ),
        },
        " .affiliate-sp-image img":{
              "width" : customImgWidth + 'px',
        },
        " .affiliate-sp-btn":{
            "font-family": btnfontFamily,
            "font-size": btnfontSizeDesktop + btnfontSizeType,
            "font-style":btnfontStyle,
            "font-weight":btnfontWeight,
            "letter-spacing":btnletterSpacing + btnletterSpacingType,
            "line-height":btnlineHeight,
            "text-decoration":btntextDecoration,
            "text-transform":btntextTransform,
            "background":styleBtnBg,
            "color":btnTextColor,
            "border-style":btnBorderType,
            "border-radius":( btnBorderRadius && undefined !== btnBorderRadius[ 'top' ] && null !== btnBorderRadius[ 'top' ] ? btnBorderRadius[ 'top' ] + btnBorderRadius['unit'] + ' ' + btnBorderRadius[ 'right' ] + btnBorderRadius['unit'] + ' ' + btnBorderRadius[ 'bottom' ] + btnBorderRadius['unit'] + ' ' + btnBorderRadius[ 'left' ] + btnBorderRadius['unit'] : '' ),
            "border-color":btnBorderColor,
            "border-width" : ( btnBorder && undefined !== btnBorder[ 'top' ] && null !== btnBorder[ 'top' ] ? btnBorder[ 'top' ] + btnBorder['unit'] + ' ' + btnBorder[ 'right' ] + btnBorder['unit'] + ' ' + btnBorder[ 'bottom' ] + btnBorder['unit'] + ' ' + btnBorder[ 'left' ] + btnBorder['unit'] : '' ),
            "padding" : ( btnPadding && undefined !== btnPadding[ 'top' ] && null !== btnPadding[ 'top' ] ? btnPadding[ 'top' ] + btnPadding['unit'] + ' ' + btnPadding[ 'right' ] + btnPadding['unit'] + ' ' + btnPadding[ 'bottom' ] + btnPadding['unit'] + ' ' + btnPadding[ 'left' ] + btnPadding['unit'] : '' ),
            "margin" : ( btnMargin && undefined !== btnMargin[ 'top' ] && null !== btnMargin[ 'top' ] ? btnMargin[ 'top' ] + btnMargin['unit'] + ' ' + btnMargin[ 'right' ] + btnMargin['unit'] + ' ' + btnMargin[ 'bottom' ] + btnMargin['unit'] + ' ' + btnMargin[ 'left' ] + btnMargin['unit'] : '' ),
      },
      " .affiliate-sp-btn:hover":{
            "background":styleBtnBgHover,
            "border-color":btnBorderHoverColor,
            "color":btnTextHoverColor
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
