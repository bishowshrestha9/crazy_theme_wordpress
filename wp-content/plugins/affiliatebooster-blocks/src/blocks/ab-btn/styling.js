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
    btnTitle,
    btnBg,
    btnBgHover,
    btnShadow,
    titleLoadGoogleFonts,
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
    titlePadding,
    titlePaddingMobile,
    titlePaddingTablet,
    titleMargin,
    titleMarginMobile,
    titleMarginTablet,
    titleAlignment,
    titleAlignmentMobile,
    titleAlignmentTablet,
    titleBorder,
    titleBorderType,
    titleBorderMobile,
    titleBorderTablet,
    titleBorderRadius,
    titleBorderRadiusMobile,
    titleBorderRadiusTablet,
    titleBorderColor,
    btnBgColor,
    btnBgHoverColor,
    titleBorderHoverColor,
    titleTextHoverColor,
	} = props.attributes

  var mobile_selectors = {}
  var tablet_selectors = {}
  var style = {}

  var styleBtnBg = cssBackground(btnBg);
  var styleBtnBgHover = cssBackground(btnBgHover);

        var selectors = {
          " .affiliate-abbtn":{
              "box-shadow":cssBoxShadow(btnShadow),
              "font-family": titlefontFamily,
              "font-size": titlefontSizeDesktop + titlefontSizeType,
              "font-style":titlefontStyle,
              "font-weight":titlefontWeight,
              "letter-spacing":titleletterSpacing + titleletterSpacingType,
              "line-height": titlelineHeight,
              "text-transform": titletextTransform,
              "text-decoration": titletextDecoration,
              "color":titleTextColor,
              "text-align":titleAlignment,
              "background":styleBtnBg,
              "border-style":titleBorderType,
              "border-radius":( titleBorderRadius && undefined !== titleBorderRadius[ 'top' ] && null !== titleBorderRadius[ 'top' ] ? titleBorderRadius[ 'top' ] + titleBorderRadius['unit'] + ' ' + titleBorderRadius[ 'right' ] + titleBorderRadius['unit'] + ' ' + titleBorderRadius[ 'bottom' ] + titleBorderRadius['unit'] + ' ' + titleBorderRadius[ 'left' ] + titleBorderRadius['unit'] : '' ),
              "border-color":titleBorderColor,
              "border-width" : ( titleBorder && undefined !== titleBorder[ 'top' ] && null !== titleBorder[ 'top' ] ? titleBorder[ 'top' ] + titleBorder['unit'] + ' ' + titleBorder[ 'right' ] + titleBorder['unit'] + ' ' + titleBorder[ 'bottom' ] + titleBorder['unit'] + ' ' + titleBorder[ 'left' ] + titleBorder['unit'] : '' ),
              "padding" : ( titlePadding && undefined !== titlePadding[ 'top' ] && null !== titlePadding[ 'top' ] ? titlePadding[ 'top' ] + titlePadding['unit'] + ' ' + titlePadding[ 'right' ] + titlePadding['unit'] + ' ' + titlePadding[ 'bottom' ] + titlePadding['unit'] + ' ' + titlePadding[ 'left' ] + titlePadding['unit'] : '' ),
              "margin" : ( titleMargin && undefined !== titleMargin[ 'top' ] && null !== titleMargin[ 'top' ] ? titleMargin[ 'top' ] + titleMargin['unit'] + ' ' + titleMargin[ 'right' ] + titleMargin['unit'] + ' ' + titleMargin[ 'bottom' ] + titleMargin['unit'] + ' ' + titleMargin[ 'left' ] + titleMargin['unit'] : '' ),
          },
          " .affiliate-abbtn-inner":{
              "justify-content":titleAlignment
          },
          " .affiliate-abbtn:hover":{
              "color":titleTextHoverColor,
              "background":styleBtnBgHover,
              "border-color":titleBorderHoverColor,
          },
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
