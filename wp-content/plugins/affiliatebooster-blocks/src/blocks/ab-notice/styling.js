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
    boxBg,
    noticeTitle,
    titleLoadGoogleFonts,
    noticeBtnText,
    noticeContent,
    noticeBtnHref,
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
    // notice content
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
    boxStyle,
    titlePadding,
    titlePaddingMobile,
    titlePaddingTablet,
    titleMargin,
    titleMarginMobile,
    titleMarginTablet,
    titleAlignment,
    titleAlignmentMobile,
    titleAlignmentTablet,
    noticeTitleBgColor,
    titleBorder,
    titleBorderType,
    titleBorderMobile,
    titleBorderTablet,
    titleBorderRadius,
    titleBorderRadiusMobile,
    titleBorderRadiusTablet,
    cntnBorder,
    cntnBorderMobile,
    cntnBorderTablet,
    cntnBorderRadius,
    cntnBorderRadiusMobile,
    cntnBorderRadiusTablet,
    titleBorderColor,
    cntnBorderColor,
    cntnAlignment,
    cntnAlignmentMobile,
    cntnAlignmentTablet,
    cntnBorderType,
    cntnMargin,
    cntnMarginMobile,
    cntnMarginTablet,
    cntnPadding,
    cntnPaddingMobile,
    cntnPaddingTablet,
    cntnBgColor,
    boxShadow,
    iconColor,
    titleTag1
	} = props.attributes

  var mobile_selectors = {}
  var tablet_selectors = {}
  var style = {}
  var styleBackground = cssBackground(boxBg);
  const Tag1 = titleTag1
        var selectors = {
          " .affiliate-notice-inner":{ 
              "box-shadow":cssBoxShadow(boxShadow),
              "border-style":titleBorderType,
              "border-radius":( titleBorderRadius && undefined !== titleBorderRadius[ 'top' ] && null !== titleBorderRadius[ 'top' ] ? titleBorderRadius[ 'top' ] + titleBorderRadius['unit'] + ' ' + titleBorderRadius[ 'right' ] + titleBorderRadius['unit'] + ' ' + titleBorderRadius[ 'bottom' ] + titleBorderRadius['unit'] + ' ' + titleBorderRadius[ 'left' ] + titleBorderRadius['unit'] : '' ),
              "border-color":titleBorderColor,
              "border-width" : ( titleBorder && undefined !== titleBorder[ 'top' ] && null !== titleBorder[ 'top' ] ? titleBorder[ 'top' ] + titleBorder['unit'] + ' ' + titleBorder[ 'right' ] + titleBorder['unit'] + ' ' + titleBorder[ 'bottom' ] + titleBorder['unit'] + ' ' + titleBorder[ 'left' ] + titleBorder['unit'] : '' ),        
          },
          " .affiliate-notice-title ":{
             "background":noticeTitleBgColor,
          },
          " .affiliate-notice-content:before":{
              "color":iconColor,
          },
          " .affiliate-notice-cntn-wrapper":{
               "background":cntnBgColor,
          },
          " .affiliate-notice-content":{
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
              "border-style":cntnBorderType,
              "border-radius":( cntnBorderRadius && undefined !== cntnBorderRadius[ 'top' ] && null !== cntnBorderRadius[ 'top' ] ? cntnBorderRadius[ 'top' ] + cntnBorderRadius['unit'] + ' ' + cntnBorderRadius[ 'right' ] + cntnBorderRadius['unit'] + ' ' + cntnBorderRadius[ 'bottom' ] + cntnBorderRadius['unit'] + ' ' + cntnBorderRadius[ 'left' ] + cntnBorderRadius['unit'] : '' ),
              "border-color":cntnBorderColor,
              "border-width" : ( cntnBorder && undefined !== cntnBorder[ 'top' ] && null !== cntnBorder[ 'top' ] ? cntnBorder[ 'top' ] + cntnBorder['unit'] + ' ' + cntnBorder[ 'right' ] + cntnBorder['unit'] + ' ' + cntnBorder[ 'bottom' ] + cntnBorder['unit'] + ' ' + cntnBorder[ 'left' ] + cntnBorder['unit'] : '' ),
              "padding" : ( cntnPadding && undefined !== cntnPadding[ 'top' ] && null !== cntnPadding[ 'top' ] ? cntnPadding[ 'top' ] + cntnPadding['unit'] + ' ' + cntnPadding[ 'right' ] + cntnPadding['unit'] + ' ' + cntnPadding[ 'bottom' ] + cntnPadding['unit'] + ' ' + cntnPadding[ 'left' ] + cntnPadding['unit'] : '' ),
              "margin" : ( cntnMargin && undefined !== cntnMargin[ 'top' ] && null !== cntnMargin[ 'top' ] ? cntnMargin[ 'top' ] + cntnMargin['unit'] + ' ' + cntnMargin[ 'right' ] + cntnMargin['unit'] + ' ' + cntnMargin[ 'bottom' ] + cntnMargin['unit'] + ' ' + cntnMargin[ 'left' ] + cntnMargin['unit'] : '' ),
        },
      }

      selectors[" .affiliate-notice-title " + Tag1] = {
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
        
        "padding" : ( titlePadding && undefined !== titlePadding[ 'top' ] && null !== titlePadding[ 'top' ] ? titlePadding[ 'top' ] + titlePadding['unit'] + ' ' + titlePadding[ 'right' ] + titlePadding['unit'] + ' ' + titlePadding[ 'bottom' ] + titlePadding['unit'] + ' ' + titlePadding[ 'left' ] + titlePadding['unit'] : '' ),
        "margin" : ( titleMargin && undefined !== titleMargin[ 'top' ] && null !== titleMargin[ 'top' ] ? titleMargin[ 'top' ] + titleMargin['unit'] + ' ' + titleMargin[ 'right' ] + titleMargin['unit'] + ' ' + titleMargin[ 'bottom' ] + titleMargin['unit'] + ' ' + titleMargin[ 'left' ] + titleMargin['unit'] : '' ),
    },
  
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
