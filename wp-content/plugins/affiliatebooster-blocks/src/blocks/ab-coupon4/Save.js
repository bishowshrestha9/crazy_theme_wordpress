const { Component,Fragment } = wp.element;
import classnames from "classnames" 
import ReviewImage from "./reviewImage.js" 
import { EmptyStar, FullStar, HalfStar } from "./icons";
const { RichText,InnerBlocks } = wp.blockEditor;
const NEW_TAB_REL = 'noopener noreferrer';
class Save extends Component {
     
    render() {
        const { highlightedStars,attributes: { 
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
                    externalImageAlt,
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
                    enableGlimmerEffect,
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

         } } = this.props
        // const interactionClass = IsInteraction(interaction) ? 'qubley-block-interaction' : '';
        
        const Tag1  = titleTag1 
        let newrel;
        if( !btn_Htarget){
            newrel = btnRel
        } else  if( !btn_Htarget && btnRel === NEW_TAB_REL){
            newrel = btnRel
        } else if ( btn_Htarget && !btnRel ) {
          newrel = NEW_TAB_REL
        } else {
          newrel = NEW_TAB_REL + ' ' + btnRel
        }

        function removeDuplicateWords (s) {
            var str = s.split(" ");
            var result = [];
            for(var i =0; i < str.length ; i++){
                if(result.indexOf(str[i]) === -1){
                  result.push(str[i]);
                } 
            }
             return result.join(" ");
            }
            
        newrel = removeDuplicateWords(newrel).trim()

        let couponMainTitleSlug = couponMainTitle.toString().toLowerCase().replace(/( |<.+?>|&nbsp;)/g, "-");

        return (
            <div id={`affiliate-style-${block_id}`} className={`affiliate-block-${block_id} affiliate-coupon-wrapper  style4`}>
                <div className={`affiliate-coupon-inner`}>                        
                        <div className={`affiliate-coupon-content-wrapper`}>
                            
                            <div className={`affiliate-coupon-content-inner`}>
                                <div className={`affiliate-col firstcol`}>
                                    
                                    { designType && designType == 'ab-fixed-design' && !externalImage && !externalImage == true &&
                                        <ReviewImage attributes={this.props.attributes} />
                                    }
                                    { designType && designType == 'ab-fixed-design' &&  externalImage && externalImage == true &&
                                        <div className={`affiliate-coupon-image`}>
                                            <img src={externalImageSrc} alt={externalImageAlt} />
                                        </div>
                                    }
                                    
                                    { designType && designType == 'ab-dynamic-design' &&
                                        <div className={`aff-inner-block`}>
                                            <InnerBlocks.Content/>
                                        </div>    
                                    }
                                </div>
                                <div className={`affiliate-col seccol`}>

                                    { showVerified && showVerified == true &&
                                        <Fragment>
                                            <div className={`affiliate-coupon-Verified`}>
                                                <RichText.Content
                                                    value={Verified} 
                                                    className={`affiliate-coupon-Verified`}
                                                />
                                            </div>
                                             
                                        </Fragment>
                                    }

                                    { showStaffPick && showStaffPick == true &&
                                        <Fragment>
                                             
                                            <div className={`affiliate-coupon-StaffPick`}>
                                                <RichText.Content
                                                    value={StaffPick} 
                                                    className={`affiliate-coupon-StaffPick`}
                                                />
                                            </div>
                                             
                                        </Fragment>
                                    }

                                    { showTitle && showTitle == true &&
                                        <RichText.Content
                                            tagName={Tag1}
                                            value={couponMainTitle}
                                            className="affiliate-coupon-maintitle"
                                            id={couponMainTitleSlug}
                                        />     
                                    }

                                    { showContent && showContent == true &&
                                        <div className={`affiliate-coupon-content`}>
                                            <RichText.Content
                                                value={couponContent} 
                                                className="affiliate-coupon-content"
                                            />
                                        </div>
                                    }

                                    { showPeopleUsed && showPeopleUsed == true &&
                                        <Fragment>
                                            <div className={`affiliate-coupon-PeopleUsed`}>
                                                <RichText.Content
                                                    value={PeopleUsed} 
                                                    className={`affiliate-coupon-PeopleUsed`}
                                                />
                                            </div>
                                        </Fragment>
                                    }

                                    { showCouponLeft && showCouponLeft == true &&
                                        <Fragment>
                                            <div className={`affiliate-coupon-onlyLeft`}>
                                                <RichText.Content
                                                    value={OnlyLeft} 
                                                    className={`affiliate-coupon-onlyLeft`}
                                                />
                                            </div>
                                             
                                        </Fragment>
                                    }
                                    
                                                          
                                </div>
                                <div className={`affiliate-col thirdcol`}>

                                    { showRating && showRating == true &&

                                        <Fragment>

                                        <div className="affiliate-coupon-starrating-outer">

                                        <div className="affiliate-coupon-starratingTitle">Rating</div>

                                        { showRatingNumber && showRatingNumber == true &&
                                            <div className={`affiliate-coupon-ratingnumber`}>
                                            {selectedStars}
                                            </div>
                                        }

                                        <div className="affiliate-coupon-starrating"
                                            onMouseLeave={() => this.setState({ highlightedStars: 0 })} >
                                            {[...Array(ratings)].map((e, i) => (
                                              <div className={`affiliate-star-item`} key={i}>
                                                    {i < (highlightedStars ? highlightedStars : selectedStars) ? (
                                                        highlightedStars ? (
                                                            highlightedStars - 1 === i ? (
                                                                selectedStars % 1 > 0 ? (
                                                                    highlightedStars - selectedStars - 0.5 !== 0 ? (
                                                                        <HalfStar fillColor={ratingsColor} />
                                                                    ) : (
                                                                        <FullStar fillColor={ratingsColor} />
                                                                    )
                                                                ) : highlightedStars - selectedStars !== 0 ? (
                                                                    <FullStar fillColor={ratingsColor} />
                                                                ) : (
                                                                    <HalfStar fillColor={ratingsColor} />
                                                                )
                                                            ) : (
                                                                <FullStar fillColor={ratingsColor} />
                                                            )
                                                        ) : selectedStars - i >= 1 ? (
                                                            <FullStar fillColor={ratingsColor} />
                                                        ) : (
                                                            <HalfStar fillColor={ratingsColor} />
                                                        )
                                                    ) : (
                                                        <EmptyStar fillColor={ratingsColor} />
                                                    )}
                                                </div>
                                            ))}                                            

                                        </div>

                                         </div>

                                        </Fragment>
                                    }

                                    

                                    { showButton && showButton == true &&
                                        <div className={`affiliate-coupon-btn`}>
                                            <div className={`affiliate-coupon-btn-wrapper`}>
                                                <a href = {spBtnHref} className={classnames(`affiliate-btn  btn-is-${btnSize} ${buttonWidth === "full"? "btn-is-fullw": buttonWidth === "flex"? `btn-is-flex-${btnSize}`: ""} ${bntRounded ? 'btn-is-rounded' : ''} ${enableGlimmerEffect ? 'glimmer-effect' : ''}`).trim()}  
                                                    {...(btn_Htarget ? {...btn_Htarget && { rel: newrel }} : { rel: newrel }  )}
                                                    {...(btn_Htarget ? { target: '_blank' } : '' )}
                                                    >
                                                    { iconPosition && iconPosition == 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                                    }
                                                    { iconPosition && iconPosition != 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText.Content value={spBtnText} />
                                                    { iconPosition && iconPosition == 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                                    }
                                                    { iconPosition && iconPosition != 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </a>
                                            </div>
                                        </div> 
                                    }


                                    { showOnGoingOffer && showOnGoingOffer == true &&
                                        <Fragment>
                                            <div className={`affiliate-coupon-onGoingOffer`}>
                                                <RichText.Content
                                                    value={onGoingOffer} 
                                                    className={`affiliate-coupon-onGoingOffer`}
                                                />
                                            </div>
                                             
                                        </Fragment>
                                    }

                                </div>
                            </div>
                        
                       
                        </div>        
                </div>
            </div>
        );
    }
}

export default Save;