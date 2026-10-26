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
    boxShadow,
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
    // 
    listfontFamily,
    listfontWeight,
    listfontSubset,
    listfontSizeType,
    listfontSizeTypeMobile,
    listfontSizeTypeTablet,
    listfontSizeDesktop,
    listfontSizeMobile,
    listfontSizeTablet,
    listlineHeight,
    listlineHeightMobile,
    listlineHeightTablet,
    listletterSpacing,
    listletterSpacingMobile,
    listletterSpacingTablet,
    listletterSpacingType,
    listletterSpacingTypeTablet,
    listletterSpacingTypeMobile,
    listtextTransform,
    listtextDecoration,
    listfontStyle,
    propsBgColor,
    propsTextColor,
    consBgColor,
    consTextColor,
    consIconColor,
    propsIconColor,
    propsListColor,
    consListColor,

	} = props.attributes

  var mobile_selectors = {}
  var tablet_selectors = {}
  var style = {}
  
        var selectors = {
          " .affiliate-procon-inner":{
              "box-shadow":cssBoxShadow(boxShadow)
          },
          " .affiliate-props-title":{
             "background": propsBgColor,
             "color": propsTextColor,
          },
          " .affiliate-const-title":{
             "background": consBgColor,
             "color": consTextColor,
          },
          " .affiliate-propcon-title":{
              "font-family": titlefontFamily,
              "font-size": titlefontSizeDesktop + titlefontSizeType,
              "font-style":titlefontStyle,
              "font-weight":titlefontWeight,
              "letter-spacing":titleletterSpacing + titleletterSpacingType,
              "line-height":titlelineHeight,
              "text-decoration":titletextDecoration,
              "text-transform":titletextTransform,
          },

          " .affiliate-list":{
              "font-family": listfontFamily,
              "font-size": listfontSizeDesktop + listfontSizeType,
              "font-style":listfontStyle,
              "font-weight":listfontWeight,
              "letter-spacing":listletterSpacing + listletterSpacingType,
              "line-height":listlineHeight,
              "text-decoration":listtextDecoration,
              "text-transform":listtextTransform,
        },
        " .affiliate-props-list li:before":{
            "color":propsIconColor,
        },
        " .affiliate-cons-list li:before":{
            "color":consIconColor,
        },
        " .affiliate-props-list li":{
          "color":propsListColor,
        },
        " .affiliate-cons-list li":{
          "color":consListColor,
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
