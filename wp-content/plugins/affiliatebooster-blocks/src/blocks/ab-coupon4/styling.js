/**
 * Set inline styles.
 * @param  {object} props - The block object.
 * @return {object} The inline background type CSS.
 */

import affiliate_generateCSS from "./generatecss"
import { cssGradient, cssBoxShadow,cssWithoutSelector,cssTextShadow, cssBackground } from '../components/cssHelper'
function affiliate_styling( props, id ) {

	const {
              className,
              uniqueId, 
              block_id,
              
              boxShadow,   
              boxBgColor,
              boxBorderType,
              boxBorderColor,
              boxBorderWidth, 
              designType,
              image,
              imagePadding,
              customImgWidth,
              customImgWidthMobile,
              customImgWidthTablet,
              imagePaddingMobile,
              imagePaddingTablet,
              imageSize,
              externalImage,
              externalImageSrc,
              showTitle,
              couponMainTitle,  
              titleTag1, 
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
              showContent, 
              couponContent,
              contentLoadGoogleFonts,
              contentColor,
              contentfontFamily,
              contentfontWeight,
              contentfontSubset,
              contentfontSizeType,
              contentfontSizeTypeMobile,
              contentfontSizeTypeTablet,
              contentfontSizeDesktop,
              contentfontSizeMobile,
              contentfontSizeTablet,
              contentlineHeight,
              contentlineHeightMobile,
              contentlineHeightTablet,
              contentletterSpacing,
              contentletterSpacingMobile,
              contentletterSpacingTablet,
              contentletterSpacingType,
              contentletterSpacingTypeTablet,
              contentletterSpacingTypeMobile,
              contenttextTransform,
              contenttextDecoration,
              contentfontStyle,
              contentTextColor,
              contentAlignment,
              contentAlignmentMobile,
              contentAlignmentTablet,    
              showRating,
              showRatingNumber,
              RatingNumber,
              rateSize,
              rateColor,
              ratings,
              starsSize,
              starsSizeMobile,
              starsSizeTablet,
              selectedStars,
              ratingsColor,
              ratingAlignemt,
              ratingAlignemtTablet,
              ratingAlignemtMobile, 
              showVerified,
              Verified,
              verifiedLoadGoogleFonts, 
              verifiedColor,
              verifiedfontFamily,
              verifiedfontWeight,
              verifiedfontSubset,
              verifiedfontSizeType,
              verifiedfontSizeTypeMobile,
              verifiedfontSizeTypeTablet,
              verifiedfontSizeDesktop,
              verifiedfontSizeMobile,
              verifiedfontSizeTablet,
              verifiedlineHeight,
              verifiedlineHeightMobile,
              verifiedlineHeightTablet,
              verifiedletterSpacing,
              verifiedletterSpacingMobile,
              verifiedletterSpacingTablet,
              verifiedletterSpacingType,
              verifiedletterSpacingTypeTablet,
              verifiedletterSpacingTypeMobile,
              verifiedtextTransform,
              verifiedtextDecoration,
              verifiedfontStyle,
              verifiedTextColor,
              showStaffPick,
              StaffPick,
              staffpickLoadGoogleFonts,    
              staffpickColor, 
              staffpickfontFamily,
              staffpickfontWeight,
              staffpickfontSubset,
              staffpickfontSizeType,
              staffpickfontSizeTypeMobile,
              staffpickfontSizeTypeTablet,
              staffpickfontSizeDesktop,
              staffpickfontSizeMobile,
              staffpickfontSizeTablet,
              staffpicklineHeight,
              staffpicklineHeightMobile,
              staffpicklineHeightTablet,
              staffpickletterSpacing,
              staffpickletterSpacingMobile,
              staffpickletterSpacingTablet,
              staffpickletterSpacingType,
              staffpickletterSpacingTypeTablet,
              staffpickletterSpacingTypeMobile,
              staffpicktextTransform,
              staffpicktextDecoration,
              staffpickfontStyle,
              staffpickTextColor,
              showPeopleUsed,
              PeopleUsed, 
              peopleUsedLoadGoogleFonts,    
              peopleUsedColor,
              peopleUsedfontFamily,
              peopleUsedfontWeight,
              peopleUsedfontSubset,
              peopleUsedfontSizeType,
              peopleUsedfontSizeTypeMobile,
              peopleUsedfontSizeTypeTablet,
              peopleUsedfontSizeDesktop,
              peopleUsedfontSizeMobile,
              peopleUsedfontSizeTablet,
              peopleUsedlineHeight,
              peopleUsedlineHeightMobile,
              peopleUsedlineHeightTablet,
              peopleUsedletterSpacing,
              peopleUsedletterSpacingMobile,
              peopleUsedletterSpacingTablet,
              peopleUsedletterSpacingType,
              peopleUsedletterSpacingTypeTablet,
              peopleUsedletterSpacingTypeMobile,
              peopleUsedtextTransform,
              peopleUsedtextDecoration,
              peopleUsedfontStyle,
              peopleUsedTextColor,
              showCouponLeft,
              OnlyLeft, 
              onlyLeftLoadGoogleFonts,    
              onlyLeftColor,
              onlyLeftfontFamily,
              onlyLeftfontWeight,
              onlyLeftfontSubset,
              onlyLeftfontSizeType,
              onlyLeftfontSizeTypeMobile,
              onlyLeftfontSizeTypeTablet,
              onlyLeftfontSizeDesktop,
              onlyLeftfontSizeMobile,
              onlyLeftfontSizeTablet,
              onlyLeftlineHeight,
              onlyLeftlineHeightMobile,
              onlyLeftlineHeightTablet,
              onlyLeftletterSpacing,
              onlyLeftletterSpacingMobile,
              onlyLeftletterSpacingTablet,
              onlyLeftletterSpacingType,
              onlyLeftletterSpacingTypeTablet,
              onlyLeftletterSpacingTypeMobile,
              onlyLefttextTransform,
              onlyLefttextDecoration,
              onlyLeftfontStyle,
              onlyLeftTextColor,
              showOnGoingOffer,
              onGoingOffer, 
              onGoingOfferLoadGoogleFonts,    
              onGoingOfferColor,
              onGoingOfferfontFamily,
              onGoingOfferfontWeight,
              onGoingOfferfontSubset,
              onGoingOfferfontSizeType,
              onGoingOfferfontSizeTypeMobile,
              onGoingOfferfontSizeTypeTablet,
              onGoingOfferfontSizeDesktop,
              onGoingOfferfontSizeMobile,
              onGoingOfferfontSizeTablet,
              onGoingOfferlineHeight,
              onGoingOfferlineHeightMobile,
              onGoingOfferlineHeightTablet,
              onGoingOfferletterSpacing,
              onGoingOfferletterSpacingMobile,
              onGoingOfferletterSpacingTablet,
              onGoingOfferletterSpacingType,
              onGoingOfferletterSpacingTypeTablet,
              onGoingOfferletterSpacingTypeMobile,
              onGoingOffertextTransform,
              onGoingOffertextDecoration,
              onGoingOfferfontStyle,
              onGoingOfferTextColor,
              showButton,
              btnLoadGoogleFonts,
              btnBgColor,
              btnTextColor,
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
              btnHoverBgColor, 
              btnTextHoverColor,
              btnBorderColor,
              btnBorderType,
              btnBorderHoverColor,
              spBtnText,
              spBtnHref,
              btnAlignment,
              btnAlignmentMobile,
              btnAlignmentTablet,
              btnPadding,
              btnPaddingTablet,
              btnPaddingMobile,
              btnMargin,
              btnMarginTablet,
              btnMarginMobile,
              btnBorder,
              btnBorderMobile,
              btnBorderTablet,
              btnBorderRadius,
              btnBorderRadiusMobile,
              btnBorderRadiusTablet,
              btnRel,
              bntRounded,
              btnSize,
              buttonWidth,
              buttonIcon,
              iconPosition,
              btn_Htarget,
              btnBg,
              btnBgHover,

      } = props.attributes
      
      const Tag1 = titleTag1

      var mobile_selectors = {}
      var tablet_selectors = {}
      var style = {}

     
      var styleBtnBg = cssBackground(btnBg);
      var styleBtnBgHover = cssBackground(btnBgHover);


      if(ratingAlignemt == 'flex-start'){ 
            var center = 'left'
      }
      if(ratingAlignemt == 'center'){ 
            var center = 'center'
      }
      if(ratingAlignemt == 'flex-end'){ 
            var center = 'right'
      }


        var selectors = {

            " .affiliate-coupon-inner .affiliate-coupon-content-wrapper":{
                  "border-style":boxBorderType,
                  "border-color":boxBorderColor,
                  "border-width" : ( boxBorderWidth && undefined !== boxBorderWidth[ 'top' ] && null !== boxBorderWidth[ 'top' ] ? boxBorderWidth[ 'top' ] + boxBorderWidth['unit'] + ' ' + boxBorderWidth[ 'right' ] + boxBorderWidth['unit'] + ' ' + boxBorderWidth[ 'bottom' ] + boxBorderWidth['unit'] + ' ' + boxBorderWidth[ 'left' ] + boxBorderWidth['unit'] : '' ),
            },
            " .affiliate-coupon-inner":{
                  "box-shadow":cssBoxShadow(boxShadow),
                  "background": boxBgColor,
            },
            " .affiliate-coupon-image":{
                  "padding" : ( imagePadding && undefined !== imagePadding[ 'top' ] && null !== imagePadding[ 'top' ] ? imagePadding[ 'top' ] + imagePadding['unit'] + ' ' + imagePadding[ 'right' ] + imagePadding['unit'] + ' ' + imagePadding[ 'bottom' ] + imagePadding['unit'] + ' ' + imagePadding[ 'left' ] + imagePadding['unit'] : '' ),
            },
            " .affiliate-coupon-image img":{
                  "width": customImgWidth + 'px'                  
            },
 
 

            " .affiliate-coupon-starrating-outer":{
                  'justify-content':ratingAlignemt
              }, 

              " .affiliate-coupon-starratingTitle":{
                  'text-align':center
              }, 

              " .affiliate-coupon-starrating .affiliate-star-item svg":{
                  "height": starsSize + 'px',
                  "width": starsSize + 'px',
              },

            " .affiliate-coupon-btn-wrapper":{
                  "justify-content":btnAlignment
              },
             
            
            
            " .affiliate-coupon-inner .affiliate-coupon-content":{
                  "font-family": contentfontFamily,
                  "font-size": contentfontSizeDesktop + contentfontSizeType,
                  "font-style":contentfontStyle,
                  "font-weight":contentfontWeight,
                  "letter-spacing":contentletterSpacing + contentletterSpacingType,
                  "line-height":contentlineHeight,
                  "text-decoration":contenttextDecoration,
                  "text-transform":contenttextTransform,
            },
            

            " .affiliate-coupon-inner .seccol .affiliate-coupon-Verified":{
                  "font-family": verifiedfontFamily,
                  "font-size": verifiedfontSizeDesktop + verifiedfontSizeType,
                  "font-style":verifiedfontStyle,
                  "font-weight":verifiedfontWeight,
                  "letter-spacing":verifiedletterSpacing + verifiedletterSpacingType,
                  "line-height":verifiedlineHeight,
                  "text-decoration":verifiedtextDecoration,
                  "text-transform":verifiedtextTransform,
                  "color":verifiedColor,
            },


            " .affiliate-coupon-inner .seccol .affiliate-coupon-StaffPick":{
                  "font-family": staffpickfontFamily,
                  "font-size": staffpickfontSizeDesktop + staffpickfontSizeType,
                  "font-style":staffpickfontStyle,
                  "font-weight":staffpickfontWeight,
                  "letter-spacing":staffpickletterSpacing + staffpickletterSpacingType,
                  "line-height":staffpicklineHeight,
                  "text-decoration":staffpicktextDecoration,
                  "text-transform":staffpicktextTransform,
                  "background":staffpickColor,
            },


            " .affiliate-coupon-inner .seccol .affiliate-coupon-PeopleUsed":{
                  "font-family": peopleUsedfontFamily,
                  "font-size": peopleUsedfontSizeDesktop + peopleUsedfontSizeType,
                  "font-style":peopleUsedfontStyle,
                  "font-weight":peopleUsedfontWeight,
                  "letter-spacing":peopleUsedletterSpacing + peopleUsedletterSpacingType,
                  "line-height":peopleUsedlineHeight,
                  "text-decoration":peopleUsedtextDecoration,
                  "text-transform":peopleUsedtextTransform,
                  "color":peopleUsedColor,
            },


            " .affiliate-coupon-inner .seccol .affiliate-coupon-onlyLeft":{
                  "font-family": onlyLeftfontFamily,
                  "font-size": onlyLeftfontSizeDesktop + onlyLeftfontSizeType,
                  "font-style":onlyLeftfontStyle,
                  "font-weight":onlyLeftfontWeight,
                  "letter-spacing":onlyLeftletterSpacing + onlyLeftletterSpacingType,
                  "line-height":onlyLeftlineHeight,
                  "text-decoration":onlyLefttextDecoration,
                  "text-transform":onlyLefttextTransform,
                  "color":onlyLeftColor,
            },


            " .affiliate-coupon-inner .thirdcol .affiliate-coupon-onGoingOffer":{
                  "font-family": onGoingOfferfontFamily,
                  "font-size": onGoingOfferfontSizeDesktop + onGoingOfferfontSizeType,
                  "font-style":onGoingOfferfontStyle,
                  "font-weight":onGoingOfferfontWeight,
                  "letter-spacing":onGoingOfferletterSpacing + onGoingOfferletterSpacingType,
                  "line-height":onGoingOfferlineHeight,
                  "text-decoration":onGoingOffertextDecoration,
                  "text-transform":onGoingOffertextTransform,
                  "color":onGoingOfferColor,
            },


            " .affiliate-coupon-btn-wrapper .affiliate-btn":{
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
                  "border-color":btnBorderColor,
                  "border-style":btnBorderType,
                  "border-radius":( btnBorderRadius && undefined !== btnBorderRadius[ 'top' ] && null !== btnBorderRadius[ 'top' ] ? btnBorderRadius[ 'top' ] + btnBorderRadius['unit'] + ' ' + btnBorderRadius[ 'right' ] + btnBorderRadius['unit'] + ' ' + btnBorderRadius[ 'bottom' ] + btnBorderRadius['unit'] + ' ' + btnBorderRadius[ 'left' ] + btnBorderRadius['unit'] : '' ),
                  "border-width" : ( btnBorder && undefined !== btnBorder[ 'top' ] && null !== btnBorder[ 'top' ] ? btnBorder[ 'top' ] + btnBorder['unit'] + ' ' + btnBorder[ 'right' ] + btnBorder['unit'] + ' ' + btnBorder[ 'bottom' ] + btnBorder['unit'] + ' ' + btnBorder[ 'left' ] + btnBorder['unit'] : '' ),
                  "padding" : ( btnPadding && undefined !== btnPadding[ 'top' ] && null !== btnPadding[ 'top' ] ? btnPadding[ 'top' ] + btnPadding['unit'] + ' ' + btnPadding[ 'right' ] + btnPadding['unit'] + ' ' + btnPadding[ 'bottom' ] + btnPadding['unit'] + ' ' + btnPadding[ 'left' ] + btnPadding['unit'] : '' ),
                  "margin" : ( btnMargin && undefined !== btnMargin[ 'top' ] && null !== btnMargin[ 'top' ] ? btnMargin[ 'top' ] + btnMargin['unit'] + ' ' + btnMargin[ 'right' ] + btnMargin['unit'] + ' ' + btnMargin[ 'bottom' ] + btnMargin['unit'] + ' ' + btnMargin[ 'left' ] + btnMargin['unit'] : '' ),
            },
            " .affiliate-coupon-btn-wrapper .affiliate-btn:hover":{
                  "background":styleBtnBgHover,
                  "border-color":btnBorderHoverColor,
                  "color":btnTextHoverColor
            }
      }

      selectors[' .affiliate-coupon-maintitle '] = {
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
