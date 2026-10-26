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
    listIconColor,
    listTextColor,
    consListColor,
    numberofColumns,
    numberofColumnsMobile,
    numberofColumnsTablet,
    iconSpacing,
    iconSpacingMobile,
    iconSpacingTablet,
    itemSpacing,
    itemSpacingMobile,
    itemSpacingTablet,
    iconSize,
    iconSizeMobile,
    iconSizeTablet,
    boxMargin,
    boxMarginMobile,
    boxMarginTablet,
    boxPadding,
    boxPaddingMobile,
    boxPaddingTablet,
    image,
    bgColor
	} = props.attributes

  var mobile_selectors = {}
  var tablet_selectors = {}
  var style = {}
  // const columnsNum = attrName  ? parseInt( attrName, 10 ) : 1
        var selectors = {
          " .affiliate-iconlist-inner":{
              "padding" : (  boxPadding && undefined !==  boxPadding[ 'top' ] && null !==  boxPadding[ 'top' ] ?  boxPadding[ 'top' ] +  boxPadding['unit'] + ' ' +  boxPadding[ 'right' ] +  boxPadding['unit'] + ' ' +  boxPadding[ 'bottom' ] +  boxPadding['unit'] + ' ' +  boxPadding[ 'left' ] +  boxPadding['unit'] : '' ),
              "margin" : ( boxMargin && undefined !== boxMargin[ 'top' ] && null !== boxMargin[ 'top' ] ? boxMargin[ 'top' ] + boxMargin['unit'] + ' ' + boxMargin[ 'right' ] + boxMargin['unit'] + ' ' + boxMargin[ 'bottom' ] + boxMargin['unit'] + ' ' + boxMargin[ 'left' ] + boxMargin['unit'] : '' ),
              "background":bgColor,  
              "box-shadow":cssBoxShadow(boxShadow)
          },
          " .affiliate-block-advanced-list ul":{
               "columns":numberofColumns,
          },
          " .affiliate-iconlist-inner li":{
              "font-family": listfontFamily,
              "font-size": listfontSizeDesktop + listfontSizeType,
              "font-style":listfontStyle,
              "font-weight":listfontWeight,
              "letter-spacing":listletterSpacing + listletterSpacingType,
              "line-height":listlineHeight,
              "text-decoration":listtextDecoration,
              "text-transform":listtextTransform,
              "color":listTextColor,
              "padding-bottom":itemSpacing + 'px'
        },
        " .affiliate-iconlist-inner li:before":{
            "color":listIconColor,
            "padding-right":iconSpacing + 'px',
            "font-size" :iconSize + 'px',
        },
        // " .affiliate-list-img" :{
        //     "padding-right":iconSpacing + 'px',
        // },
        // " .affiliate-list-img img" :{
        //     "width":iconSize + 'px',
        //     "max-width":iconSize + 'px',
        // },
        " .aff-list-image li:before":{
            "background-image" : `url(`+ image.url + ')',
            "height":iconSize + 'px',
            "width":iconSize + 'px',
            "padding-right":iconSpacing + 'px',
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
