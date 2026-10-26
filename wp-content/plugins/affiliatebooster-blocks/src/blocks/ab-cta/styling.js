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
    btnBg,
    btnBgHover,
    boxBg,
    ctaTitle,
    titleLoadGoogleFonts,
    ctaBtnText,
    ctaContent,
    ctaBtnHref,
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
    // CTA BUTTON
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
    btn_Htarget,
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
    boxBorderType,
    boxBorderColor,
    boxBorderWidth,
    boxStyle,
    boxPadding,
    boxPaddingMobile,
    boxPaddingTablet,
    boxMargin,
    boxMarginMobile,
    boxMarginTablet,
    boxAlignment,
    boxAlignmentMobile,
    boxAlignmentTablet,
    bgOverlay,
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
    btnSize,
    buttonIcon,
    iconPosition,
    buttonWidth,
    bntRounded,
    boxShadow
	} = props.attributes

  var mobile_selectors = {}
  var tablet_selectors = {}
  var style = {}
  var styleBackground = cssBackground(boxBg);

  var styleBtnBg = cssBackground(btnBg);
  var styleBtnBgHover = cssBackground(btnBgHover);
  
        var selectors = {
          " .affiliate-cta-overlay":{
              'background-color':bgOverlay
          },
          " .affiliate-cta-btn-wrapper":{
              "justify-content":btnAlignment
          },
          " .affiliate-cta-title":{
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
          " .affiliate-cta-inner":{
              "padding" : ( boxPadding && undefined !== boxPadding[ 'top' ] && null !== boxPadding[ 'top' ] ? boxPadding[ 'top' ] + boxPadding['unit'] + ' ' + boxPadding[ 'right' ] + boxPadding['unit'] + ' ' + boxPadding[ 'bottom' ] + boxPadding['unit'] + ' ' + boxPadding[ 'left' ] + boxPadding['unit'] : '' ),
              "margin" : ( boxMargin && undefined !== boxMargin[ 'top' ] && null !== boxMargin[ 'top' ] ? boxMargin[ 'top' ] + boxMargin['unit'] + ' ' + boxMargin[ 'right' ] + boxMargin['unit'] + ' ' + boxMargin[ 'bottom' ] + boxMargin['unit'] + ' ' + boxMargin[ 'left' ] + boxMargin['unit'] : '' ),
              'text-align':boxAlignment,
              "box-shadow":cssBoxShadow(boxShadow),
              'cstm':styleBackground,
              "border-style":boxBorderType,
              "border-color":boxBorderColor,
              "border-width" : ( boxBorderWidth && undefined !== boxBorderWidth[ 'top' ] && null !== boxBorderWidth[ 'top' ] ? boxBorderWidth[ 'top' ] + boxBorderWidth['unit'] + ' ' + boxBorderWidth[ 'right' ] + boxBorderWidth['unit'] + ' ' + boxBorderWidth[ 'bottom' ] + boxBorderWidth['unit'] + ' ' + boxBorderWidth[ 'left' ] + boxBorderWidth['unit'] : '' ),                
          },
          " .affiliate-cta-content":{
              "font-family": cntnfontFamily,
              "font-size": cntnfontSizeDesktop + cntnfontSizeType,
              "font-style":cntnfontStyle,
              "font-weight":cntnfontWeight,
              "letter-spacing":cntnletterSpacing + cntnletterSpacingType,
              "line-height":cntnlineHeight,
              "text-decoration":cntntextDecoration,
              "text-transform":cntntextTransform,
              "color":cntnTextColor
        },

        " .affiliate-cta-btn":{
            "font-family": btnfontFamily,
            "font-size": btnfontSizeDesktop + btnfontSizeType,
            "font-style":btnfontStyle,
            "font-weight":btnfontWeight,
            "letter-spacing":btnletterSpacing + btnletterSpacingType,
            "line-height":btnlineHeight,
            "text-decoration":btntextDecoration,
            "text-transform":btntextTransform,
            "background":styleBtnBg,
            "border-color":btnBorderColor,
            "border-style":btnBorderType,
            "color":btnTextColor,
            "border-radius":( btnBorderRadius && undefined !== btnBorderRadius[ 'top' ] && null !== btnBorderRadius[ 'top' ] ? btnBorderRadius[ 'top' ] + btnBorderRadius['unit'] + ' ' + btnBorderRadius[ 'right' ] + btnBorderRadius['unit'] + ' ' + btnBorderRadius[ 'bottom' ] + btnBorderRadius['unit'] + ' ' + btnBorderRadius[ 'left' ] + btnBorderRadius['unit'] : '' ),
            "border-width" : ( btnBorder && undefined !== btnBorder[ 'top' ] && null !== btnBorder[ 'top' ] ? btnBorder[ 'top' ] + btnBorder['unit'] + ' ' + btnBorder[ 'right' ] + btnBorder['unit'] + ' ' + btnBorder[ 'bottom' ] + btnBorder['unit'] + ' ' + btnBorder[ 'left' ] + btnBorder['unit'] : '' ),
            "padding" : ( btnPadding && undefined !== btnPadding[ 'top' ] && null !== btnPadding[ 'top' ] ? btnPadding[ 'top' ] + btnPadding['unit'] + ' ' + btnPadding[ 'right' ] + btnPadding['unit'] + ' ' + btnPadding[ 'bottom' ] + btnPadding['unit'] + ' ' + btnPadding[ 'left' ] + btnPadding['unit'] : '' ),
            "margin" : ( btnMargin && undefined !== btnMargin[ 'top' ] && null !== btnMargin[ 'top' ] ? btnMargin[ 'top' ] + btnMargin['unit'] + ' ' + btnMargin[ 'right' ] + btnMargin['unit'] + ' ' + btnMargin[ 'bottom' ] + btnMargin['unit'] + ' ' + btnMargin[ 'left' ] + btnMargin['unit'] : '' ),
      },
      " .affiliate-cta-btn:hover":{
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
