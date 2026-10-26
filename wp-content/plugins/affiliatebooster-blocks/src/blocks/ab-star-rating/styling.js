/**
 * Set inline styles.
 * @param  {object} props - The block object.
 * @return {object} The inline background type CSS.
 */

import affiliate_generateCSS from "./generatecss"
// import { cssGradient, cssBoxShadow,cssWithoutSelector,cssTextShadow, cssBackground } from '../components/cssHelper'
function affiliate_styling( props, id ) {

	const {
        block_id,
        starAligment,
        starAligmentMobile,
        starAligmentTable,
        starsSize,
        starsSizeMobile,
        starsSizeTablet,
        ratings,
        ratingsColor,
        selectedStars,
        itemPadding,
        ratingAlignemt
	} = props.attributes

  var mobile_selectors = {}
  var tablet_selectors = {}
  var style = {}
  // var styleBackground = cssBackground(boxBg);
  
        var selectors = {
        " .affiliate-star-inner-container":{
            'justify-content':ratingAlignemt
        }, 
        " .affiliate-star-item svg":{
            "height": starsSize + 'px',
            "width": starsSize + 'px',
        },
        " .affiliate-star-item:not(:last-child)":{
            "padding-right": itemPadding + 'px',
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
