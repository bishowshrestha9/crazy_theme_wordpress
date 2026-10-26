const { Component,Fragment } = wp.element;
import classnames from "classnames"
import ReviewImage1 from "./reviewImage1.js"
import ReviewImage2 from "./reviewImage2.js"
import ReviewImage3 from "./reviewImage3.js"
import ReviewImage4 from "./reviewImage4.js"
import ReviewImage5 from "./reviewImage5.js"
import ReviewImage6 from "./reviewImage6.js"
import ReviewImage7 from "./reviewImage7.js"

import { EmptyStar, FullStar, HalfStar } from "./icons";
import RichList from '../../components/rich-list';
const { RichText,InnerBlocks } = wp.blockEditor;
const NEW_TAB_REL = 'noopener noreferrer';

class Save extends Component {

    renderListItems1 = () => {
        const { attributes: { listItems1 } } = this.props
        return listItems1.map(item => <li>{item}</li>)
    }

    renderListItems2 = () => {
        const { attributes: { listItems2 } } = this.props
        return listItems2.map(item => <li>{item}</li>)
    }

    renderListItems3 = () => {
        const { attributes: { listItems3 } } = this.props
        return listItems3.map(item => <li>{item}</li>)
    }
    
    renderListItems4 = () => {
        const { attributes: { listItems4 } } = this.props
        return listItems4.map(item => <li>{item}</li>)
    }

    renderListItems5 = () => {
        const { attributes: { listItems5 } } = this.props
        return listItems5.map(item => <li>{item}</li>)
    }

    renderListItems6 = () => {
        const { attributes: { listItems6 } } = this.props
        return listItems6.map(item => <li>{item}</li>)
    }

    renderListItems7 = () => {
        const { attributes: { listItems7 } } = this.props
        return listItems7.map(item => <li>{item}</li>)
    }

    render() {
        const { 
            name,
            clientId,
            isSelected,
            attributes,
            setAttributes,
            highlightedStars1,
            highlightedStars2,
            highlightedStars3,
            highlightedStars4,
            highlightedStars5,
            highlightedStars6,
            highlightedStars7,
            hasInnerBlocks,
            attributes: { 
                
                uniqueId,
                block_id,
                className, 
                numberOfBlock,      
                numberOfCol, 
                productColumnStyle,         
                boxShadow,
                minRate,
                maxRate,      
                boxBgColor1,
                flagStyle1,
                ratings1,
                ratingsColor1,
                selectedStars1, 
                starsSize1,
                starsSizeMobile1,
                starsSizeTablet1,
                titleTag1,
                titleText1,
                titleLoadGoogleFonts1,
                titleTextColor1,
                titlefontFamily1,
                titlefontWeight1,
                titlefontSubset1,
                titlefontSizeType1,
                titlefontSizeTypeMobile1,
                titlefontSizeTypeTablet1,
                titlefontSizeDesktop1,
                titlefontSizeMobile1,
                titlefontSizeTablet1,
                titlelineHeight1,
                titlelineHeightMobile1,
                titlelineHeightTablet1,
                titleletterSpacing1,
                titleletterSpacingMobile1,
                titleletterSpacingTablet1,
                titleletterSpacingType1,
                titleletterSpacingTypeTablet1,
                titleletterSpacingTypeMobile1,
                titletextTransform1,
                titletextDecoration1,
                titlefontStyle1,
                image1,
                imageSize1,
                imagePadding1,
                imagePaddingMobile1,
                imagePaddingTablet1,
                customImgWidth1,
                customImgWidthMobile1,
                customImgWidthTablet1,
                imageDesignType1,
                listItems1,
                listTextItems1,
                listType1,
                alignment1,
                listTextColor1,
                listLoadGoogleFonts1,
                listfontFamily1,
                listfontWeight1,  
                listfontSubset1,
                listfontSizeType1,
                listfontSizeTypeMobile1,
                listfontSizeTypeTablet1,
                listfontSizeDesktop1,
                listfontSizeMobile1,
                listfontSizeTablet1,
                listlineHeight1,
                listlineHeightMobile1,
                listlineHeightTablet1,
                listletterSpacing1,
                listletterSpacingMobile1,
                listletterSpacingTablet1,
                listletterSpacingType1,
                listletterSpacingTypeTablet1,
                listletterSpacingTypeMobile1,
                listtextTransform1,
                listtextDecoration1,
                listfontStyle1,
                bulletStyle1,
                numberCorner1,
                numberFontSize1,
                numberBgSize1,
                useNumberBg1,  
                listIconColor1,
                listColor1,
                recreateStyles1,
                btnLoadGoogleFonts1,
                btnfontFamily1,
                btnfontWeight1,                
                btnfontSubset1,
                btnfontSizeType1,
                btnfontSizeTypeMobile1,
                btnfontSizeTypeTablet1,
                btnfontSizeDesktop1,
                btnfontSizeMobile1,
                btnfontSizeTablet1,
                btnlineHeight1,
                btnlineHeightMobile1,
                btnlineHeightTablet1,
                btnletterSpacing1,
                btnletterSpacingMobile1,
                btnletterSpacingTablet1,
                btnletterSpacingType1,
                btnletterSpacingTypeTablet1,
                btnletterSpacingTypeMobile1,
                btntextTransform1,
                btntextDecoration1,
                btnfontStyle1,
                btnBgColor1,
                btnHoverBgColor1,
                btnTextColor1,
                btnTextHoverColor1,
                btnBorderColor1,
                btnBorderHoverColor1,
                btn_Htarget1,
                spBtnText1,
                spBtnHref1,
                btnPadding1,
                btnPaddingMobile1,
                btnPaddingTablet1,
                btnMargin1,
                btnMarginMobile1,
                btnMarginTablet1,
                btnAlignment1,
                btnAlignmentMobile1,
                btnAlignmentTablet1,
                btnBorder1,
                btnBorderType1,
                btnBorderMobile1,
                btnBorderTablet1,
                btnBorderRadius1,
                btnBorderRadiusMobile1,
                btnBorderRadiusTablet1,                
                iconPosition1,
                btnSize1,
                buttonIcon1,
                buttonWidth1,
                bntRounded1,                
                btnRel1, 


                boxBgColor2,
                flagStyle2,
                ratings2,
                ratingsColor2,
                selectedStars2, 
                starsSize2,
                starsSizeMobile2,
                starsSizeTablet2,
                titleTag2,
                titleText2,
                titleLoadGoogleFonts2,
                titleTextColor2,
                titlefontFamily2,
                titlefontWeight2,
                titlefontSubset2,
                titlefontSizeType2,
                titlefontSizeTypeMobile2,
                titlefontSizeTypeTablet2,
                titlefontSizeDesktop2,
                titlefontSizeMobile2,
                titlefontSizeTablet2,
                titlelineHeight2,
                titlelineHeightMobile2,
                titlelineHeightTablet2,
                titleletterSpacing2,
                titleletterSpacingMobile2,
                titleletterSpacingTablet2,
                titleletterSpacingType2,
                titleletterSpacingTypeTablet2,
                titleletterSpacingTypeMobile2,
                titletextTransform2,
                titletextDecoration2,
                titlefontStyle2,
                image2,
                imageSize2,
                imagePadding2,
                imagePaddingMobile2,
                imagePaddingTablet2,
                customImgWidth2,
                customImgWidthMobile2,
                customImgWidthTablet2,
                imageDesignType2,
                listItems2,
                listTextItems2,
                listType2,
                alignment2,
                listTextColor2,
                listLoadGoogleFonts2,
                listfontFamily2,
                listfontWeight2,    
                listfontSubset2,
                listfontSizeType2,
                listfontSizeTypeMobile2,
                listfontSizeTypeTablet2,
                listfontSizeDesktop2,
                listfontSizeMobile2,
                listfontSizeTablet2,
                listlineHeight2,
                listlineHeightMobile2,
                listlineHeightTablet2,
                listletterSpacing2,
                listletterSpacingMobile2,
                listletterSpacingTablet2,
                listletterSpacingType2,
                listletterSpacingTypeTablet2,
                listletterSpacingTypeMobile2,
                listtextTransform2,
                listtextDecoration2,
                listfontStyle2,
                bulletStyle2,
                numberCorner2,
                numberFontSize2,
                numberBgSize2,
                useNumberBg2,  
                listIconColor2,
                listColor2,
                recreateStyles2,
                btnLoadGoogleFonts2,
                btnfontFamily2,
                btnfontWeight2,                
                btnfontSubset2,
                btnfontSizeType2,
                btnfontSizeTypeMobile2,
                btnfontSizeTypeTablet2,
                btnfontSizeDesktop2,
                btnfontSizeMobile2,
                btnfontSizeTablet2,
                btnlineHeight2,
                btnlineHeightMobile2,
                btnlineHeightTablet2,
                btnletterSpacing2,
                btnletterSpacingMobile2,
                btnletterSpacingTablet2,
                btnletterSpacingType2,
                btnletterSpacingTypeTablet2,
                btnletterSpacingTypeMobile2,
                btntextTransform2,
                btntextDecoration2,
                btnfontStyle2,
                btnBgColor2,
                btnHoverBgColor2,
                btnTextColor2,
                btnTextHoverColor2,
                btnBorderColor2,
                btnBorderHoverColor2,
                btn_Htarget2,
                spBtnText2,
                spBtnHref2,
                btnPadding2,
                btnPaddingMobile2,
                btnPaddingTablet2,
                btnMargin2,
                btnMarginMobile2,
                btnMarginTablet2,
                btnAlignment2,
                btnAlignmentMobile2,
                btnAlignmentTablet2,
                btnBorder2,
                btnBorderType2,
                btnBorderMobile2,
                btnBorderTablet2,
                btnBorderRadius2,
                btnBorderRadiusMobile2,
                btnBorderRadiusTablet2,                
                iconPosition2,
                btnSize2,
                buttonIcon2,
                buttonWidth2,
                bntRounded2,                
                btnRel2, 


                boxBgColor3,
                flagStyle3,
                ratings3,
                ratingsColor3,
                selectedStars3, 
                starsSize3,
                starsSizeMobile3,
                starsSizeTablet3,
                titleTag3,
                titleText3,
                titleLoadGoogleFonts3,
                titleTextColor3,
                titlefontFamily3,
                titlefontWeight3,
                titlefontSubset3,
                titlefontSizeType3,
                titlefontSizeTypeMobile3,
                titlefontSizeTypeTablet3,
                titlefontSizeDesktop3,
                titlefontSizeMobile3,
                titlefontSizeTablet3,
                titlelineHeight3,
                titlelineHeightMobile3,
                titlelineHeightTablet3,
                titleletterSpacing3,
                titleletterSpacingMobile3,
                titleletterSpacingTablet3,
                titleletterSpacingType3,
                titleletterSpacingTypeTablet3,
                titleletterSpacingTypeMobile3,
                titletextTransform3,
                titletextDecoration3,
                titlefontStyle3,
                image3,
                imageSize3,
                imagePadding3,
                imagePaddingMobile3,
                imagePaddingTablet3,
                customImgWidth3, 
                customImgWidthMobile3,
                customImgWidthTablet3,
                imageDesignType3,
                listItems3,
                listTextItems3,
                listType3,
                alignment3,
                listTextColor3,
                listLoadGoogleFonts3,
                listfontFamily3,
                listfontWeight3,   
                listfontSubset3,
                listfontSizeType3,
                listfontSizeTypeMobile3,
                listfontSizeTypeTablet3,
                listfontSizeDesktop3,
                listfontSizeMobile3,
                listfontSizeTablet3,
                listlineHeight3,
                listlineHeightMobile3,
                listlineHeightTablet3,
                listletterSpacing3,
                listletterSpacingMobile3,
                listletterSpacingTablet3,
                listletterSpacingType3,
                listletterSpacingTypeTablet3,
                listletterSpacingTypeMobile3,
                listtextTransform3,
                listtextDecoration3,
                listfontStyle3, 
                bulletStyle3,
                numberCorner3,
                numberFontSize3,
                numberBgSize3,
                useNumberBg3,  
                listIconColor3,
                listColor3,
                recreateStyles3,
                btnLoadGoogleFonts3,
                btnfontFamily3,
                btnfontWeight3,                
                btnfontSubset3,
                btnfontSizeType3,
                btnfontSizeTypeMobile3,
                btnfontSizeTypeTablet3,
                btnfontSizeDesktop3,
                btnfontSizeMobile3,
                btnfontSizeTablet3,
                btnlineHeight3,
                btnlineHeightMobile3,
                btnlineHeightTablet3,
                btnletterSpacing3,
                btnletterSpacingMobile3,
                btnletterSpacingTablet3,
                btnletterSpacingType3,
                btnletterSpacingTypeTablet3,
                btnletterSpacingTypeMobile3,
                btntextTransform3,
                btntextDecoration3,
                btnfontStyle3,
                btnBgColor3,
                btnHoverBgColor3,
                btnTextColor3,
                btnTextHoverColor3,
                btnBorderColor3,
                btnBorderHoverColor3,
                btn_Htarget3,
                spBtnText3,
                spBtnHref3,
                btnPadding3,
                btnPaddingMobile3,
                btnPaddingTablet3,
                btnMargin3,
                btnMarginMobile3,
                btnMarginTablet3,
                btnAlignment3,
                btnAlignmentMobile3,
                btnAlignmentTablet3,
                btnBorder3,
                btnBorderType3,
                btnBorderMobile3,
                btnBorderTablet3,
                btnBorderRadius3,
                btnBorderRadiusMobile3,
                btnBorderRadiusTablet3,                
                iconPosition3,
                btnSize3,
                buttonIcon3,
                buttonWidth3,
                bntRounded3,                
                btnRel3,


                boxBgColor4,
                flagStyle4,
                ratings4,
                ratingsColor4,
                selectedStars4, 
                starsSize4,
                starsSizeMobile4,
                starsSizeTablet4,
                titleTag4,
                titleText4,
                titleLoadGoogleFonts4,
                titleTextColor4,
                titlefontFamily4,
                titlefontWeight4,
                titlefontSubset4,
                titlefontSizeType4,
                titlefontSizeTypeMobile4,
                titlefontSizeTypeTablet4,
                titlefontSizeDesktop4,
                titlefontSizeMobile4,
                titlefontSizeTablet4,
                titlelineHeight4,
                titlelineHeightMobile4,
                titlelineHeightTablet4,
                titleletterSpacing4,
                titleletterSpacingMobile4,
                titleletterSpacingTablet4,
                titleletterSpacingType4,
                titleletterSpacingTypeTablet4,
                titleletterSpacingTypeMobile4,
                titletextTransform4,
                titletextDecoration4,
                titlefontStyle4,
                image4,
                imageSize4,
                imagePadding4,
                imagePaddingMobile4,
                imagePaddingTablet4,
                customImgWidth4,
                customImgWidthMobile4,
                customImgWidthTablet4,
                imageDesignType4,
                listItems4,
                listTextItems4,
                listType4,
                alignment4,
                listTextColor4,
                listLoadGoogleFonts4,
                listfontFamily4,
                listfontWeight4,    
                listfontSubset4,
                listfontSizeType4,
                listfontSizeTypeMobile4,
                listfontSizeTypeTablet4,
                listfontSizeDesktop4,
                listfontSizeMobile4,
                listfontSizeTablet4,
                listlineHeight4,
                listlineHeightMobile4,
                listlineHeightTablet4,
                listletterSpacing4,
                listletterSpacingMobile4,
                listletterSpacingTablet4,
                listletterSpacingType4,
                listletterSpacingTypeTablet4,
                listletterSpacingTypeMobile4,
                listtextTransform4,
                listtextDecoration4,
                listfontStyle4,
                bulletStyle4,
                numberCorner4,
                numberFontSize4,
                numberBgSize4,
                useNumberBg4,  
                listIconColor4,
                listColor4,
                recreateStyles4,
                btnLoadGoogleFonts4,
                btnfontFamily4,
                btnfontWeight4,                
                btnfontSubset4,
                btnfontSizeType4,
                btnfontSizeTypeMobile4,
                btnfontSizeTypeTablet4,
                btnfontSizeDesktop4,
                btnfontSizeMobile4,
                btnfontSizeTablet4,
                btnlineHeight4,
                btnlineHeightMobile4,
                btnlineHeightTablet4,
                btnletterSpacing4,
                btnletterSpacingMobile4,
                btnletterSpacingTablet4,
                btnletterSpacingType4,
                btnletterSpacingTypeTablet4,
                btnletterSpacingTypeMobile4,
                btntextTransform4,
                btntextDecoration4,
                btnfontStyle4,
                btnBgColor4,
                btnHoverBgColor4,
                btnTextColor4,
                btnTextHoverColor4,
                btnBorderColor4,
                btnBorderHoverColor4,
                btn_Htarget4,
                spBtnText4,
                spBtnHref4,
                btnPadding4,
                btnPaddingMobile4,
                btnPaddingTablet4,
                btnMargin4,
                btnMarginMobile4,
                btnMarginTablet4,
                btnAlignment4,
                btnAlignmentMobile4,
                btnAlignmentTablet4,
                btnBorder4,
                btnBorderType4,
                btnBorderMobile4,
                btnBorderTablet4,
                btnBorderRadius4,
                btnBorderRadiusMobile4,
                btnBorderRadiusTablet4,                
                iconPosition4,
                btnSize4,
                buttonIcon4,
                buttonWidth4,
                bntRounded4,                
                btnRel4,


                boxBgColor5,
                flagStyle5,
                ratings5,
                ratingsColor5,
                selectedStars5, 
                starsSize5,
                starsSizeMobile5,
                starsSizeTablet5,
                titleTag5,
                titleText5,
                titleLoadGoogleFonts5,
                titleTextColor5,
                titlefontFamily5,
                titlefontWeight5,
                titlefontSubset5,
                titlefontSizeType5,
                titlefontSizeTypeMobile5,
                titlefontSizeTypeTablet5,
                titlefontSizeDesktop5,
                titlefontSizeMobile5,
                titlefontSizeTablet5,
                titlelineHeight5,
                titlelineHeightMobile5,
                titlelineHeightTablet5,
                titleletterSpacing5,
                titleletterSpacingMobile5,
                titleletterSpacingTablet5,
                titleletterSpacingType5,
                titleletterSpacingTypeTablet5,
                titleletterSpacingTypeMobile5,
                titletextTransform5,
                titletextDecoration5,
                titlefontStyle5,
                image5,
                imageSize5,
                imagePadding5,
                imagePaddingMobile5,
                imagePaddingTablet5,
                customImgWidth5,
                customImgWidthMobile5,
                customImgWidthTablet5,
                imageDesignType5,
                listItems5,
                listTextItems5,
                listType5,
                alignment5,
                listTextColor5,
                listLoadGoogleFonts5,
                listfontFamily5,
                listfontWeight5,    
                listfontSubset5,
                listfontSizeType5,
                listfontSizeTypeMobile5,
                listfontSizeTypeTablet5,
                listfontSizeDesktop5,
                listfontSizeMobile5,
                listfontSizeTablet5,
                listlineHeight5,
                listlineHeightMobile5,
                listlineHeightTablet5,
                listletterSpacing5,
                listletterSpacingMobile5,
                listletterSpacingTablet5,
                listletterSpacingType5,
                listletterSpacingTypeTablet5,
                listletterSpacingTypeMobile5,
                listtextTransform5,
                listtextDecoration5,
                listfontStyle5,
                bulletStyle5,
                numberCorner5,
                numberFontSize5,
                numberBgSize5,
                useNumberBg5,  
                listIconColor5,
                listColor5,
                recreateStyles5,
                btnLoadGoogleFonts5,
                btnfontFamily5,
                btnfontWeight5,                
                btnfontSubset5,
                btnfontSizeType5,
                btnfontSizeTypeMobile5,
                btnfontSizeTypeTablet5,
                btnfontSizeDesktop5,
                btnfontSizeMobile5,
                btnfontSizeTablet5,
                btnlineHeight5,
                btnlineHeightMobile5,
                btnlineHeightTablet5,
                btnletterSpacing5,
                btnletterSpacingMobile5,
                btnletterSpacingTablet5,
                btnletterSpacingType5,
                btnletterSpacingTypeTablet5,
                btnletterSpacingTypeMobile5,
                btntextTransform5,
                btntextDecoration5,
                btnfontStyle5,
                btnBgColor5,
                btnHoverBgColor5,
                btnTextColor5,
                btnTextHoverColor5,
                btnBorderColor5,
                btnBorderHoverColor5,
                btn_Htarget5,
                spBtnText5,
                spBtnHref5,
                btnPadding5,
                btnPaddingMobile5,
                btnPaddingTablet5,
                btnMargin5,
                btnMarginMobile5,
                btnMarginTablet5,
                btnAlignment5,
                btnAlignmentMobile5,
                btnAlignmentTablet5,
                btnBorder5,
                btnBorderType5,
                btnBorderMobile5,
                btnBorderTablet5,
                btnBorderRadius5,
                btnBorderRadiusMobile5,
                btnBorderRadiusTablet5,                
                iconPosition5,
                btnSize5,
                buttonIcon5,
                buttonWidth5,
                bntRounded5,                
                btnRel5,


                boxBgColor6,
                flagStyle6,
                ratings6,
                ratingsColor6,
                selectedStars6, 
                starsSize6,
                starsSizeMobile6,
                starsSizeTablet6,
                titleTag6,
                titleText6,
                titleLoadGoogleFonts6,
                titleTextColor6,
                titlefontFamily6,
                titlefontWeight6,
                titlefontSubset6,
                titlefontSizeType6,
                titlefontSizeTypeMobile6,
                titlefontSizeTypeTablet6,
                titlefontSizeDesktop6,
                titlefontSizeMobile6,
                titlefontSizeTablet6,
                titlelineHeight6,
                titlelineHeightMobile6,
                titlelineHeightTablet6,
                titleletterSpacing6,
                titleletterSpacingMobile6,
                titleletterSpacingTablet6,
                titleletterSpacingType6,
                titleletterSpacingTypeTablet6,
                titleletterSpacingTypeMobile6,
                titletextTransform6,
                titletextDecoration6,
                titlefontStyle6,
                image6,
                imageSize6,
                imagePadding6,
                imagePaddingMobile6,
                imagePaddingTablet6,
                customImgWidth6,
                customImgWidthMobile6,
                customImgWidthTablet6,
                imageDesignType6,
                listItems6,
                listTextItems6,
                listType6,
                alignment6,
                listTextColor6,
                listLoadGoogleFonts6,
                listfontFamily6,
                listfontWeight6,   
                listfontSubset6,
                listfontSizeType6,
                listfontSizeTypeMobile6,
                listfontSizeTypeTablet6,
                listfontSizeDesktop6,
                listfontSizeMobile6,
                listfontSizeTablet6,
                listlineHeight6,
                listlineHeightMobile6,
                listlineHeightTablet6,
                listletterSpacing6,
                listletterSpacingMobile6,
                listletterSpacingTablet6,
                listletterSpacingType6,
                listletterSpacingTypeTablet6,
                listletterSpacingTypeMobile6,
                listtextTransform6,
                listtextDecoration6,
                listfontStyle6, 
                bulletStyle6,
                numberCorner6,
                numberFontSize6,
                numberBgSize6,
                useNumberBg6,  
                listIconColor6,
                listColor6,
                recreateStyles6,
                btnLoadGoogleFonts6,
                btnfontFamily6,
                btnfontWeight6,                
                btnfontSubset6,
                btnfontSizeType6,
                btnfontSizeTypeMobile6,
                btnfontSizeTypeTablet6,
                btnfontSizeDesktop6,
                btnfontSizeMobile6,
                btnfontSizeTablet6,
                btnlineHeight6,
                btnlineHeightMobile6,
                btnlineHeightTablet6,
                btnletterSpacing6,
                btnletterSpacingMobile6,
                btnletterSpacingTablet6,
                btnletterSpacingType6,
                btnletterSpacingTypeTablet6,
                btnletterSpacingTypeMobile6,
                btntextTransform6,
                btntextDecoration6,
                btnfontStyle6,
                btnBgColor6,
                btnHoverBgColor6,
                btnTextColor6,
                btnTextHoverColor6,
                btnBorderColor6,
                btnBorderHoverColor6,
                btn_Htarget6,
                spBtnText6,
                spBtnHref6,
                btnPadding6,
                btnPaddingMobile6,
                btnPaddingTablet6,
                btnMargin6,
                btnMarginMobile6,
                btnMarginTablet6,
                btnAlignment6,
                btnAlignmentMobile6,
                btnAlignmentTablet6,
                btnBorder6,
                btnBorderType6,
                btnBorderMobile6,
                btnBorderTablet6,
                btnBorderRadius6,
                btnBorderRadiusMobile6,
                btnBorderRadiusTablet6,                
                iconPosition6,
                btnSize6,
                buttonIcon6,
                buttonWidth6,
                bntRounded6,                
                btnRel6,


                boxBgColor7,
                flagStyle7,
                ratings7,
                ratingsColor7,
                selectedStars7, 
                starsSize7,
                starsSizeMobile7,
                starsSizeTablet7,
                titleTag7,
                titleText7,
                titleLoadGoogleFonts7,
                titleTextColor7,
                titlefontFamily7,
                titlefontWeight7,
                titlefontSubset7,
                titlefontSizeType7,
                titlefontSizeTypeMobile7,
                titlefontSizeTypeTablet7,
                titlefontSizeDesktop7,
                titlefontSizeMobile7,
                titlefontSizeTablet7,
                titlelineHeight7,
                titlelineHeightMobile7,
                titlelineHeightTablet7,
                titleletterSpacing7,
                titleletterSpacingMobile7,
                titleletterSpacingTablet7,
                titleletterSpacingType7,
                titleletterSpacingTypeTablet7,
                titleletterSpacingTypeMobile7,
                titletextTransform7,
                titletextDecoration7,
                titlefontStyle7,
                image7,
                imageSize7,
                imagePadding7,
                imagePaddingMobile7,
                imagePaddingTablet7,
                customImgWidth7,
                customImgWidthMobile7,
                customImgWidthTablet7,
                imageDesignType7,
                listItems7,
                listTextItems7,
                listType7,
                alignment7,
                listTextColor7,
                listLoadGoogleFonts7,
                listfontFamily7,
                listfontWeight7,    
                listfontSubset7,
                listfontSizeType7,
                listfontSizeTypeMobile7,
                listfontSizeTypeTablet7,
                listfontSizeDesktop7,
                listfontSizeMobile7,
                listfontSizeTablet7,
                listlineHeight7,
                listlineHeightMobile7,
                listlineHeightTablet7,
                listletterSpacing7,
                listletterSpacingMobile7,
                listletterSpacingTablet7,
                listletterSpacingType7,
                listletterSpacingTypeTablet7,
                listletterSpacingTypeMobile7,
                listtextTransform7,
                listtextDecoration7,
                listfontStyle7,
                bulletStyle7,
                numberCorner7,
                numberFontSize7,
                numberBgSize7,
                useNumberBg7,  
                listIconColor7,
                listColor7,
                recreateStyles7,
                btnLoadGoogleFonts7,
                btnfontFamily7,
                btnfontWeight7,                
                btnfontSubset7,
                btnfontSizeType7,
                btnfontSizeTypeMobile7,
                btnfontSizeTypeTablet7,
                btnfontSizeDesktop7,
                btnfontSizeMobile7,
                btnfontSizeTablet7,
                btnlineHeight7,
                btnlineHeightMobile7,
                btnlineHeightTablet7,
                btnletterSpacing7,
                btnletterSpacingMobile7,
                btnletterSpacingTablet7,
                btnletterSpacingType7,
                btnletterSpacingTypeTablet7,
                btnletterSpacingTypeMobile7,
                btntextTransform7,
                btntextDecoration7,
                btnfontStyle7,
                btnBgColor7,
                btnHoverBgColor7,
                btnTextColor7,
                btnTextHoverColor7,
                btnBorderColor7,
                btnBorderHoverColor7,
                btn_Htarget7,
                spBtnText7,
                spBtnHref7,
                btnPadding7,
                btnPaddingMobile7,
                btnPaddingTablet7,
                btnMargin7,
                btnMarginMobile7,
                btnMarginTablet7,
                btnAlignment7,
                btnAlignmentMobile7,
                btnAlignmentTablet7,
                btnBorder7,
                btnBorderType7,
                btnBorderMobile7,
                btnBorderTablet7,
                btnBorderRadius7,
                btnBorderRadiusMobile7,
                btnBorderRadiusTablet7,                
                iconPosition7,
                btnSize7,
                buttonIcon7,
                buttonWidth7,
                bntRounded7,                
                btnRel7,


                externalImage1,
                externalImageSrc1,
                externalImageAlt1,

                externalImage2,
                externalImageSrc2,
                externalImageAlt2,

                externalImage3,
                externalImageSrc3,
                externalImageAlt3,

                externalImage4,
                externalImageSrc4,
                externalImageAlt4,

                externalImage5,
                externalImageSrc5,
                externalImageAlt5,

                externalImage6,
                externalImageSrc6,
                externalImageAlt6,

                externalImage7,
                externalImageSrc7,
                externalImageAlt7,


                showTitle,
                showRating,
                showContent, 
                showButton,

                enableGlimmerEffect,
                enableGlimmerEffect2,
                enableGlimmerEffect3,
                enableGlimmerEffect4,
                enableGlimmerEffect5,
                enableGlimmerEffect6,
                enableGlimmerEffect7,

                showtopText,
                showbottomText,

                topText1,                
                topTextColor1,
                topTextBgColor1, 

                topTextLoadGoogleFonts1,
                topTextfontFamily1,
                topTextfontWeight1,
                topTextfontSubset1,
                topTextfontSizeType1,
                topTextfontSizeTypeMobile1,
                topTextfontSizeTypeTablet1,
                topTextfontSizeDesktop1,
                topTextfontSizeMobile1,
                topTextfontSizeTablet1,
                topTextlineHeight1,
                topTextlineHeightMobile1,
                topTextlineHeightTablet1,
                topTextletterSpacing1,
                topTextletterSpacingMobile1,
                topTextletterSpacingTablet1,
                topTextletterSpacingType1,
                topTextletterSpacingTypeTablet1,
                topTextletterSpacingTypeMobile1,
                topTextTransform1,
                topTextDecoration1,
                topTextfontStyle1,

                bottomText1,
                bottomTextColor1, 

                bottomTextLoadGoogleFonts1,
                bottomTextfontFamily1,
                bottomTextfontWeight1,
                bottomTextfontSubset1,
                bottomTextfontSizeType1,
                bottomTextfontSizeTypeMobile1,
                bottomTextfontSizeTypeTablet1,
                bottomTextfontSizeDesktop1,
                bottomTextfontSizeMobile1,
                bottomTextfontSizeTablet1,
                bottomTextlineHeight1,
                bottomTextlineHeightMobile1,
                bottomTextlineHeightTablet1,
                bottomTextletterSpacing1,
                bottomTextletterSpacingMobile1,
                bottomTextletterSpacingTablet1,
                bottomTextletterSpacingType1,
                bottomTextletterSpacingTypeTablet1,
                bottomTextletterSpacingTypeMobile1,
                bottomTextTransform1,
                bottomTextDecoration1,
                bottomTextfontStyle1,

                topText2,                
                topTextColor2,
                topTextBgColor2,  

                topTextLoadGoogleFonts2,
                topTextfontFamily2,
                topTextfontWeight2,
                topTextfontSubset2,
                topTextfontSizeType2,
                topTextfontSizeTypeMobile2,
                topTextfontSizeTypeTablet2,
                topTextfontSizeDesktop2,
                topTextfontSizeMobile2,
                topTextfontSizeTablet2,
                topTextlineHeight2,
                topTextlineHeightMobile2,
                topTextlineHeightTablet2,
                topTextletterSpacing2,
                topTextletterSpacingMobile2,
                topTextletterSpacingTablet2,
                topTextletterSpacingType2,
                topTextletterSpacingTypeTablet2,
                topTextletterSpacingTypeMobile2,
                topTextTransform2,
                topTextDecoration2,
                topTextfontStyle2,


                bottomText2,
                bottomTextColor2, 

                bottomTextLoadGoogleFonts2,
                bottomTextfontFamily2,
                bottomTextfontWeight2,
                bottomTextfontSubset2,
                bottomTextfontSizeType2,
                bottomTextfontSizeTypeMobile2,
                bottomTextfontSizeTypeTablet2,
                bottomTextfontSizeDesktop2,
                bottomTextfontSizeMobile2,
                bottomTextfontSizeTablet2,
                bottomTextlineHeight2,
                bottomTextlineHeightMobile2,
                bottomTextlineHeightTablet2,
                bottomTextletterSpacing2,
                bottomTextletterSpacingMobile2,
                bottomTextletterSpacingTablet2,
                bottomTextletterSpacingType2,
                bottomTextletterSpacingTypeTablet2,
                bottomTextletterSpacingTypeMobile2,
                bottomTextTransform2,
                bottomTextDecoration2,
                bottomTextfontStyle2,


                topText3,                
                topTextColor3,
                topTextBgColor3,  

                topTextLoadGoogleFonts3,
                topTextfontFamily3,
                topTextfontWeight3,
                topTextfontSubset3,
                topTextfontSizeType3,
                topTextfontSizeTypeMobile3,
                topTextfontSizeTypeTablet3,
                topTextfontSizeDesktop3,
                topTextfontSizeMobile3,
                topTextfontSizeTablet3,
                topTextlineHeight3,
                topTextlineHeightMobile3,
                topTextlineHeightTablet3,
                topTextletterSpacing3,
                topTextletterSpacingMobile3,
                topTextletterSpacingTablet3,
                topTextletterSpacingType3,
                topTextletterSpacingTypeTablet3,
                topTextletterSpacingTypeMobile3,
                topTextTransform3,
                topTextDecoration3,
                topTextfontStyle3,


                bottomText3,
                bottomTextColor3, 

                bottomTextLoadGoogleFonts3,
                bottomTextfontFamily3,
                bottomTextfontWeight3,
                bottomTextfontSubset3,
                bottomTextfontSizeType3,
                bottomTextfontSizeTypeMobile3,
                bottomTextfontSizeTypeTablet3,
                bottomTextfontSizeDesktop3,
                bottomTextfontSizeMobile3,
                bottomTextfontSizeTablet3,
                bottomTextlineHeight3,
                bottomTextlineHeightMobile3,
                bottomTextlineHeightTablet3,
                bottomTextletterSpacing3,
                bottomTextletterSpacingMobile3,
                bottomTextletterSpacingTablet3,
                bottomTextletterSpacingType3,
                bottomTextletterSpacingTypeTablet3,
                bottomTextletterSpacingTypeMobile3,
                bottomTextTransform3,
                bottomTextDecoration3,
                bottomTextfontStyle3,

                topText4,                
                topTextColor4,
                topTextBgColor4,  

                topTextLoadGoogleFonts4,
                topTextfontFamily4,
                topTextfontWeight4,
                topTextfontSubset4,
                topTextfontSizeType4,
                topTextfontSizeTypeMobile4,
                topTextfontSizeTypeTablet4,
                topTextfontSizeDesktop4,
                topTextfontSizeMobile4,
                topTextfontSizeTablet4,
                topTextlineHeight4,
                topTextlineHeightMobile4,
                topTextlineHeightTablet4,
                topTextletterSpacing4,
                topTextletterSpacingMobile4,
                topTextletterSpacingTablet4,
                topTextletterSpacingType4,
                topTextletterSpacingTypeTablet4,
                topTextletterSpacingTypeMobile4,
                topTextTransform4,
                topTextDecoration4,
                topTextfontStyle4,


                bottomText4,
                bottomTextColor4, 

                bottomTextLoadGoogleFonts4,
                bottomTextfontFamily4,
                bottomTextfontWeight4,
                bottomTextfontSubset4,
                bottomTextfontSizeType4,
                bottomTextfontSizeTypeMobile4,
                bottomTextfontSizeTypeTablet4,
                bottomTextfontSizeDesktop4,
                bottomTextfontSizeMobile4,
                bottomTextfontSizeTablet4,
                bottomTextlineHeight4,
                bottomTextlineHeightMobile4,
                bottomTextlineHeightTablet4,
                bottomTextletterSpacing4,
                bottomTextletterSpacingMobile4,
                bottomTextletterSpacingTablet4,
                bottomTextletterSpacingType4,
                bottomTextletterSpacingTypeTablet4,
                bottomTextletterSpacingTypeMobile4,
                bottomTextTransform4,
                bottomTextDecoration4,
                bottomTextfontStyle4,


                topText5,                
                topTextColor5,
                topTextBgColor5,  

                topTextLoadGoogleFonts5,
                topTextfontFamily5,
                topTextfontWeight5,
                topTextfontSubset5,
                topTextfontSizeType5,
                topTextfontSizeTypeMobile5,
                topTextfontSizeTypeTablet5,
                topTextfontSizeDesktop5,
                topTextfontSizeMobile5,
                topTextfontSizeTablet5,
                topTextlineHeight5,
                topTextlineHeightMobile5,
                topTextlineHeightTablet5,
                topTextletterSpacing5,
                topTextletterSpacingMobile5,
                topTextletterSpacingTablet5,
                topTextletterSpacingType5,
                topTextletterSpacingTypeTablet5,
                topTextletterSpacingTypeMobile5,
                topTextTransform5,
                topTextDecoration5,
                topTextfontStyle5,


                bottomText5,
                bottomTextColor5, 

                bottomTextLoadGoogleFonts5,
                bottomTextfontFamily5,
                bottomTextfontWeight5,
                bottomTextfontSubset5,
                bottomTextfontSizeType5,
                bottomTextfontSizeTypeMobile5,
                bottomTextfontSizeTypeTablet5,
                bottomTextfontSizeDesktop5,
                bottomTextfontSizeMobile5,
                bottomTextfontSizeTablet5,
                bottomTextlineHeight5,
                bottomTextlineHeightMobile5,
                bottomTextlineHeightTablet5,
                bottomTextletterSpacing5,
                bottomTextletterSpacingMobile5,
                bottomTextletterSpacingTablet5,
                bottomTextletterSpacingType5,
                bottomTextletterSpacingTypeTablet5,
                bottomTextletterSpacingTypeMobile5,
                bottomTextTransform5,
                bottomTextDecoration5,
                bottomTextfontStyle5,

                topText6,                
                topTextColor6,
                topTextBgColor6,  

                topTextLoadGoogleFonts6,
                topTextfontFamily6,
                topTextfontWeight6,
                topTextfontSubset6,
                topTextfontSizeType6,
                topTextfontSizeTypeMobile6,
                topTextfontSizeTypeTablet6,
                topTextfontSizeDesktop6,
                topTextfontSizeMobile6,
                topTextfontSizeTablet6,
                topTextlineHeight6,
                topTextlineHeightMobile6,
                topTextlineHeightTablet6,
                topTextletterSpacing6,
                topTextletterSpacingMobile6,
                topTextletterSpacingTablet6,
                topTextletterSpacingType6,
                topTextletterSpacingTypeTablet6,
                topTextletterSpacingTypeMobile6,
                topTextTransform6,
                topTextDecoration6,
                topTextfontStyle6,


                bottomText6,
                bottomTextColor6, 

                bottomTextLoadGoogleFonts6,
                bottomTextfontFamily6,
                bottomTextfontWeight6,
                bottomTextfontSubset6,
                bottomTextfontSizeType6,
                bottomTextfontSizeTypeMobile6,
                bottomTextfontSizeTypeTablet6,
                bottomTextfontSizeDesktop6,
                bottomTextfontSizeMobile6,
                bottomTextfontSizeTablet6,
                bottomTextlineHeight6,
                bottomTextlineHeightMobile6,
                bottomTextlineHeightTablet6,
                bottomTextletterSpacing6,
                bottomTextletterSpacingMobile6,
                bottomTextletterSpacingTablet6,
                bottomTextletterSpacingType6,
                bottomTextletterSpacingTypeTablet6,
                bottomTextletterSpacingTypeMobile6,
                bottomTextTransform6,
                bottomTextDecoration6,
                bottomTextfontStyle6,

                topText7,                
                topTextColor7,
                topTextBgColor7,  

                topTextLoadGoogleFonts7,
                topTextfontFamily7,
                topTextfontWeight7,
                topTextfontSubset7,
                topTextfontSizeType7,
                topTextfontSizeTypeMobile7,
                topTextfontSizeTypeTablet7,
                topTextfontSizeDesktop7,
                topTextfontSizeMobile7,
                topTextfontSizeTablet7,
                topTextlineHeight7,
                topTextlineHeightMobile7,
                topTextlineHeightTablet7,
                topTextletterSpacing7,
                topTextletterSpacingMobile7,
                topTextletterSpacingTablet7,
                topTextletterSpacingType7,
                topTextletterSpacingTypeTablet7,
                topTextletterSpacingTypeMobile7,
                topTextTransform7,
                topTextDecoration7,
                topTextfontStyle7,


                bottomText7,
                bottomTextColor7, 

                bottomTextLoadGoogleFonts7,
                bottomTextfontFamily7,
                bottomTextfontWeight7,
                bottomTextfontSubset7,
                bottomTextfontSizeType7,
                bottomTextfontSizeTypeMobile7,
                bottomTextfontSizeTypeTablet7,
                bottomTextfontSizeDesktop7,
                bottomTextfontSizeMobile7,
                bottomTextfontSizeTablet7,
                bottomTextlineHeight7,
                bottomTextlineHeightMobile7,
                bottomTextlineHeightTablet7,
                bottomTextletterSpacing7,
                bottomTextletterSpacingMobile7,
                bottomTextletterSpacingTablet7,
                bottomTextletterSpacingType7,
                bottomTextletterSpacingTypeTablet7,
                bottomTextletterSpacingTypeMobile7,
                bottomTextTransform7,
                bottomTextDecoration7,
                bottomTextfontStyle7,


                amazonprice1,
                amazonpriceposition1,
                amazonrating1,
                amazonratingposition1,

                amazonprice2,
                amazonpriceposition2,
                amazonrating2,
                amazonratingposition2,

                amazonprice3,
                amazonpriceposition3,
                amazonrating3,
                amazonratingposition3,

                amazonprice4,
                amazonpriceposition4,
                amazonrating4,
                amazonratingposition4,

                amazonprice5,
                amazonpriceposition5,
                amazonrating5,
                amazonratingposition5,

                amazonprice6,
                amazonpriceposition6,
                amazonrating6,
                amazonratingposition6,

                amazonprice7,
                amazonpriceposition7,
                amazonrating7,
                amazonratingposition7,

            } 
        } = this.props
        
        // const interactionClass = IsInteraction(interaction) ? 'qubley-block-interaction' : '';
        const ListTag1 = listType1 == 'ordered' ? 'ol' : 'ul'
        const ListTag2 = listType2 == 'ordered' ? 'ol' : 'ul'
        const ListTag3 = listType3 == 'ordered' ? 'ol' : 'ul'
        const ListTag4 = listType4 == 'ordered' ? 'ol' : 'ul'
        const ListTag5 = listType5 == 'ordered' ? 'ol' : 'ul'
        const ListTag6 = listType6 == 'ordered' ? 'ol' : 'ul'
        const ListTag7 = listType7 == 'ordered' ? 'ol' : 'ul'        


        const Tag1  = titleTag1
        const Tag2  = titleTag2
        const Tag3  = titleTag3
        const Tag4  = titleTag4
        const Tag5  = titleTag5
        const Tag6  = titleTag6
        const Tag7  = titleTag7


        let newrel1;
        if( !btn_Htarget1){
            newrel1 = btnRel1
        } else  if( !btn_Htarget1 && btnRel1 === NEW_TAB_REL){
            newrel1 = btnRel1
        } else if ( btn_Htarget1 && !btnRel1 ) {
          newrel1 = NEW_TAB_REL
        } else {
          newrel1 = NEW_TAB_REL + ' ' + btnRel1
        }


        let newrel2;
        if( !btn_Htarget2){
            newrel2 = btnRel2
        } else  if( !btn_Htarget2 && btnRel2 === NEW_TAB_REL){
            newrel2 = btnRel2
        } else if ( btn_Htarget2 && !btnRel2 ) {
          newrel2 = NEW_TAB_REL
        } else {
          newrel2 = NEW_TAB_REL + ' ' + btnRel2
        }


        let newrel3;
        if( !btn_Htarget3){
            newrel3 = btnRel3
        } else  if( !btn_Htarget3 && btnRel3 === NEW_TAB_REL){
            newrel3 = btnRel3
        } else if ( btn_Htarget3 && !btnRel3 ) {
          newrel3 = NEW_TAB_REL
        } else {
          newrel3 = NEW_TAB_REL + ' ' + btnRel3
        }


        let newrel4;
        if( !btn_Htarget4){
            newrel4 = btnRel4
        } else  if( !btn_Htarget4 && btnRel4 === NEW_TAB_REL){
            newrel4 = btnRel4
        } else if ( btn_Htarget4 && !btnRel4 ) {
          newrel4 = NEW_TAB_REL
        } else {
          newrel4 = NEW_TAB_REL + ' ' + btnRel4
        }


        let newrel5;
        if( !btn_Htarget5){
            newrel5 = btnRel5
        } else  if( !btn_Htarget5 && btnRel5 === NEW_TAB_REL){
            newrel5 = btnRel5
        } else if ( btn_Htarget5 && !btnRel5 ) {
          newrel5 = NEW_TAB_REL
        } else {
          newrel5 = NEW_TAB_REL + ' ' + btnRel5
        }


        let newrel6;
        if( !btn_Htarget6){
            newrel6 = btnRel6
        } else  if( !btn_Htarget6 && btnRel6 === NEW_TAB_REL){
            newrel6 = btnRel6
        } else if ( btn_Htarget6 && !btnRel6 ) {
          newrel6 = NEW_TAB_REL
        } else {
          newrel6 = NEW_TAB_REL + ' ' + btnRel6
        }


        let newrel7;
        if( !btn_Htarget7){
            newrel7 = btnRel7
        } else  if( !btn_Htarget7 && btnRel7 === NEW_TAB_REL){
            newrel7 = btnRel7
        } else if ( btn_Htarget7 && !btnRel7 ) {
          newrel7 = NEW_TAB_REL
        } else {
          newrel7 = NEW_TAB_REL + ' ' + btnRel7
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
            
        newrel1 = removeDuplicateWords(newrel1).trim()
        newrel2 = removeDuplicateWords(newrel2).trim()
        newrel3 = removeDuplicateWords(newrel3).trim()
        newrel4 = removeDuplicateWords(newrel4).trim()
        newrel5 = removeDuplicateWords(newrel5).trim()
        newrel6 = removeDuplicateWords(newrel6).trim()
        newrel7 = removeDuplicateWords(newrel7).trim()
 
        return (
            <div id={`affiliate-style-${block_id}`} className={`affiliate-block-${block_id}${className ? ` ${className}` : ''} affiliate-coupon-wrapper ${productColumnStyle} style2`}>
                <ul className={`affiliate-coupon-inner block${numberOfCol} colblock${numberOfBlock} style2`}>

                    { productColumnStyle && productColumnStyle == 'layout1' &&

                        <Fragment key={'layout1'}>
                            { numberOfBlock && numberOfBlock >= '1' &&
                                <li className={`affiliate-block-col box1`}>
                                    { flagStyle1 && flagStyle1 != '' && 
                                      <div className={`triangle1 ${flagStyle1}`}>
                                      { flagStyle1 && flagStyle1 == 'flag1' && 
                                        <span>
                                            <p>Budget <span>Pick</span></p>
                                            <i class="fas fa-trophy"></i>
                                        </span>
                                      }
                                      { flagStyle1 && flagStyle1 == 'flag2' && 
                                        <span>
                                            <p>Best <span>Value</span></p>
                                            <i class="fas fa-trophy"></i>
                                        </span>
                                      }
                                      { flagStyle1 && flagStyle1 == 'flag3' && 
                                        <span>
                                            <p>Premium <span>Pick</span></p>
                                            <i class="fas fa-trophy"></i>
                                        </span>
                                      }
                                      </div>
                                    }

                                    { showRating && showRating == true &&
                                        <div className="affiliate-coupon-starrating1"
                                            onMouseLeave={() => this.setState({ highlightedStars1: 0 })} >
                                            {[...Array(ratings1)].map((e, i) => (
                                              <div className={`affiliate-star-item`} key={i}>
                                                    {i < (highlightedStars1 ? highlightedStars1 : selectedStars1) ? (
                                                        highlightedStars1 ? (
                                                            highlightedStars1 - 1 === i ? (
                                                                selectedStars1 % 1 > 0 ? (
                                                                    highlightedStars1 - selectedStars1 - 0.5 !== 0 ? (
                                                                        <HalfStar fillColor={ratingsColor1} />
                                                                    ) : (
                                                                        <FullStar fillColor={ratingsColor1} />
                                                                    )
                                                                ) : highlightedStars1 - selectedStars1 !== 0 ? (
                                                                    <FullStar fillColor={ratingsColor1} />
                                                                ) : (
                                                                    <HalfStar fillColor={ratingsColor1} />
                                                                )
                                                            ) : (
                                                                <FullStar fillColor={ratingsColor1} />
                                                            )
                                                        ) : selectedStars1 - i >= 1 ? (
                                                            <FullStar fillColor={ratingsColor1} />
                                                        ) : (
                                                            <HalfStar fillColor={ratingsColor1} />
                                                        )
                                                    ) : (
                                                        <EmptyStar fillColor={ratingsColor1} />
                                                    )}
                                                </div>
                                            ))}
                                        </div>
                                    }

                                    { showTitle && showTitle == true &&
                                        <RichText.Content
                                            tagName={Tag1}
                                            value={titleText1}
                                            className="affiliate-coupon-title1"
                                        />     
                                    }

                                    { imageDesignType1 && imageDesignType1 == 'ab-fixed-design' && !externalImage1 && !externalImage1 == true &&
                                        <ReviewImage1 attributes={this.props.attributes} />
                                    }

                                    { imageDesignType1 && imageDesignType1 == 'ab-fixed-design' &&  externalImage1 && externalImage1 == true &&
                                        <div className={`affiliate-coupon-image1`}>
                                            <img src={externalImageSrc1} alt={externalImageAlt1} />
                                        </div>
                                    }


                                    { imageDesignType1 && imageDesignType1 == 'ab-dynamic-design' &&
                                        <div className={`aff-inner-block`}>
                                            <InnerBlocks.Content/>
                                        </div>    
                                    }

                                    { showContent && showContent == true &&
                                        <div className={`affiliate-block-advanced-list affiliate-coupon-list1`}>
                                                 <RichList.Content
                                                    className={`affiliate-list1 affiliate-list-type-unordered  affiliate-alignment-${alignment1} affiliate-list-bullet-${bulletStyle1.name}`}
                                                    value={listTextItems1}
                                                />                     
                                         </div> 
                                     }


                                     { amazonpriceposition1 && amazonpriceposition1 == "Above Button" &&
                                          <Fragment>
                                            <div className={`affiliate-productcol1-amzon-price above`}>{amazonprice1}</div>
                                          </Fragment>
                                          }

                                         { amazonratingposition1 && amazonratingposition1 == "Above Button" &&
                                          <Fragment>
                                            <div className={`affiliate-productcol1-amzon-rating above`}>{amazonrating1}</div>
                                          </Fragment>
                                          } 

                                     { showButton && showButton == true &&
                                         <div className={`affiliate-coupon-btn`}> 
                                            <div className={`affiliate-coupon-btn-wrapper1`}>
                                                                           
                                                <a href = {spBtnHref1} className={classnames(`affiliate-btn  btn-is-${btnSize1} ${buttonWidth1 === "full"? "btn-is-fullw": buttonWidth1 === "flex"? `btn-is-flex-${btnSize1}`: ""} ${bntRounded1 ? 'btn-is-rounded' : ''} ${enableGlimmerEffect ? 'glimmer-effect' : ''}`).trim()}  
                                                    {...(btn_Htarget1 ? {...btn_Htarget1 && { rel: newrel1 }} : { rel: newrel1 }  )}
                                                    {...(btn_Htarget1 ? { target: '_blank' } : '' )}
                                                    >
                                                    { iconPosition1 && iconPosition1 == 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon1} ${iconPosition1}`}></i>
                                                    }
                                                    { iconPosition1 && iconPosition1 != 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText.Content value={spBtnText1} />
                                                    { iconPosition1 && iconPosition1 == 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon1} ${iconPosition1}`}></i>
                                                    }
                                                    { iconPosition1 && iconPosition1 != 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </a>
                                            </div>
                                        </div> 
                                    }

                                    { amazonpriceposition1 && amazonpriceposition1 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol1-amzon-price below`}>{amazonprice1}</div>
                                      </Fragment>
                                      }

                                    { amazonratingposition1 && amazonratingposition1 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol1-amzon-rating below`}>{amazonrating1}</div>
                                      </Fragment>
                                      }     

                                </li>
                            }

                            { numberOfBlock && numberOfBlock >= '2' &&
                                <li className={`affiliate-block-col box2`}>
                                    { flagStyle2 && flagStyle2 != '' && 
                                      <div className={`triangle2 ${flagStyle2}`}>

                                      { flagStyle2 && flagStyle2 == 'flag1' && 
                                        <span>
                                            <p>Budget <span>Pick</span></p>
                                            <i class="fas fa-trophy"></i>
                                        </span>
                                      }
                                      { flagStyle2 && flagStyle2 == 'flag2' && 
                                        <span>
                                            <p>Best <span>Value</span></p>
                                            <i class="fas fa-trophy"></i>
                                        </span>
                                      }
                                      { flagStyle2 && flagStyle2 == 'flag3' && 
                                        <span>
                                            <p>Premium <span>Pick</span></p>
                                            <i class="fas fa-trophy"></i>
                                        </span>
                                      }

                                      </div>
                                    }

                                    { showRating && showRating == true &&
                                        <div className="affiliate-coupon-starrating2"
                                            onMouseLeave={() => this.setState({ highlightedStars2: 0 })} >
                                            {[...Array(ratings2)].map((e, i) => (
                                              <div className={`affiliate-star-item`} key={i}>
                                                    {i < (highlightedStars2 ? highlightedStars2 : selectedStars2) ? (
                                                        highlightedStars2 ? (
                                                            highlightedStars2 - 1 === i ? (
                                                                selectedStars2 % 1 > 0 ? (
                                                                    highlightedStars2 - selectedStars2 - 0.5 !== 0 ? (
                                                                        <HalfStar fillColor={ratingsColor2} />
                                                                    ) : (
                                                                        <FullStar fillColor={ratingsColor2} />
                                                                    )
                                                                ) : highlightedStars2 - selectedStars2 !== 0 ? (
                                                                    <FullStar fillColor={ratingsColor2} />
                                                                ) : (
                                                                    <HalfStar fillColor={ratingsColor2} />
                                                                )
                                                            ) : (
                                                                <FullStar fillColor={ratingsColor2} />
                                                            )
                                                        ) : selectedStars2 - i >= 1 ? (
                                                            <FullStar fillColor={ratingsColor2} />
                                                        ) : (
                                                            <HalfStar fillColor={ratingsColor2} />
                                                        )
                                                    ) : (
                                                        <EmptyStar fillColor={ratingsColor2} />
                                                    )}
                                                </div>
                                            ))}
                                        </div>
                                    }

                                    { showTitle && showTitle == true &&
                                        <RichText.Content
                                            tagName={Tag2}
                                            value={titleText2}
                                            className="affiliate-coupon-title2"
                                        />   
                                    }  

                                    { imageDesignType2 && imageDesignType2 == 'ab-fixed-design' &&  !externalImage2 && !externalImage2 == true &&
                                        <ReviewImage2 attributes={this.props.attributes} />
                                    }

                                    { imageDesignType2 && imageDesignType2 == 'ab-fixed-design' &&  externalImage2 && externalImage2 == true &&
                                        <div className={`affiliate-coupon-image2`}>
                                            <img src={externalImageSrc2} alt={externalImageAlt2} />
                                        </div>
                                    }

                                    { imageDesignType2 && imageDesignType2 == 'ab-dynamic-design' &&
                                        <div className={`aff-inner-block`}>
                                            <InnerBlocks.Content/>
                                        </div>    
                                    }

                                    { showContent && showContent == true &&
                                        <div className={`affiliate-block-advanced-list affiliate-coupon-list2`}>
                                                 <RichList.Content
                                                    className={`affiliate-list2 affiliate-list-type-unordered  affiliate-alignment-${alignment2} affiliate-list-bullet-${bulletStyle2.name}`}
                                                    value={listTextItems2}
                                                />                  
                                         </div> 
                                     }


                                     { amazonpriceposition2 && amazonpriceposition2 == "Above Button" &&
                                          <Fragment>
                                            <div className={`affiliate-productcol2-amzon-price above`}>{amazonprice2}</div>
                                          </Fragment>
                                          }

                                         { amazonratingposition2 && amazonratingposition2 == "Above Button" &&
                                          <Fragment>
                                            <div className={`affiliate-productcol2-amzon-rating above`}>{amazonrating2}</div>
                                          </Fragment>
                                          } 

                                     { showButton && showButton == true &&
                                         <div className={`affiliate-coupon-btn`}>  
                                            <div className={`affiliate-coupon-btn-wrapper2`}>
                                                                          
                                                <a href = {spBtnHref2} className={classnames(`affiliate-btn  btn-is-${btnSize2} ${buttonWidth2 === "full"? "btn-is-fullw": buttonWidth2 === "flex"? `btn-is-flex-${btnSize2}`: ""} ${bntRounded2 ? 'btn-is-rounded' : ''} ${enableGlimmerEffect2 ? 'glimmer-effect' : ''}`).trim()}  
                                                    {...(btn_Htarget2 ? {...btn_Htarget2 && { rel: newrel2 }} : { rel: newrel2 }  )}
                                                    {...(btn_Htarget2 ? { target: '_blank' } : '' )}
                                                    >
                                                    { iconPosition2 && iconPosition2 == 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon2} ${iconPosition2}`}></i>
                                                    }
                                                    { iconPosition2 && iconPosition2 != 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText.Content value={spBtnText2} />
                                                    { iconPosition2 && iconPosition2 == 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon2} ${iconPosition2}`}></i>
                                                    }
                                                    { iconPosition2 && iconPosition2 != 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </a>
                                            </div>
                                        </div> 
                                    }


                                    { amazonpriceposition2 && amazonpriceposition2 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol2-amzon-price below`}>{amazonprice2}</div>
                                      </Fragment>
                                      }

                                    { amazonratingposition2 && amazonratingposition2 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol2-amzon-rating below`}>{amazonrating2}</div>
                                      </Fragment>
                                      }  

                                </li>
                            }

                            { numberOfBlock && numberOfBlock >= '3' &&
                                <li className={`affiliate-block-col box3`}>
                                    { flagStyle3 && flagStyle3 != '' && 
                                      <div className={`triangle3 ${flagStyle3}`}>

                                        { flagStyle3 && flagStyle3 == 'flag1' && 
                                            <span>
                                                <p>Budget <span>Pick</span></p>
                                                <i class="fas fa-trophy"></i>
                                            </span>
                                          }
                                          { flagStyle3 && flagStyle3 == 'flag2' && 
                                            <span>
                                                <p>Best <span>Value</span></p>
                                                <i class="fas fa-trophy"></i>
                                            </span>
                                          }
                                          { flagStyle3 && flagStyle3 == 'flag3' && 
                                            <span>
                                                <p>Premium <span>Pick</span></p>
                                                <i class="fas fa-trophy"></i>
                                            </span>
                                          }

                                      </div>
                                    }

                                    { showRating && showRating == true &&
                                        <div className="affiliate-coupon-starrating3"
                                            onMouseLeave={() => this.setState({ highlightedStars3: 0 })} >
                                            {[...Array(ratings3)].map((e, i) => (
                                              <div className={`affiliate-star-item`} key={i}>
                                                    {i < (highlightedStars3 ? highlightedStars3 : selectedStars3) ? (
                                                        highlightedStars3 ? (
                                                            highlightedStars3 - 1 === i ? (
                                                                selectedStars3 % 1 > 0 ? (
                                                                    highlightedStars3 - selectedStars3 - 0.5 !== 0 ? (
                                                                        <HalfStar fillColor={ratingsColor3} />
                                                                    ) : (
                                                                        <FullStar fillColor={ratingsColor3} />
                                                                    )
                                                                ) : highlightedStars3 - selectedStars3 !== 0 ? (
                                                                    <FullStar fillColor={ratingsColor3} />
                                                                ) : (
                                                                    <HalfStar fillColor={ratingsColor3} />
                                                                )
                                                            ) : (
                                                                <FullStar fillColor={ratingsColor3} />
                                                            )
                                                        ) : selectedStars3 - i >= 1 ? (
                                                            <FullStar fillColor={ratingsColor3} />
                                                        ) : (
                                                            <HalfStar fillColor={ratingsColor3} />
                                                        )
                                                    ) : (
                                                        <EmptyStar fillColor={ratingsColor3} />
                                                    )}
                                                </div>
                                            ))}
                                        </div>
                                    }

                                    { showTitle && showTitle == true &&
                                        <RichText.Content
                                            tagName={Tag3}
                                            value={titleText3}
                                            className="affiliate-coupon-title3"
                                        />  
                                    }   

                                    { imageDesignType3 && imageDesignType3 == 'ab-fixed-design' &&  !externalImage3 && !externalImage3 == true &&
                                        <ReviewImage3 attributes={this.props.attributes} />
                                    }

                                    { imageDesignType3 && imageDesignType3 == 'ab-fixed-design' &&  externalImage3 && externalImage3 == true &&
                                        <div className={`affiliate-coupon-image3`}>
                                            <img src={externalImageSrc3} alt={externalImageAlt3} />
                                        </div>
                                    }

                                    { imageDesignType3 && imageDesignType3 == 'ab-dynamic-design' &&
                                        <div className={`aff-inner-block`}>
                                            <InnerBlocks.Content/>
                                        </div>    
                                    }

                                    { showContent && showContent == true &&
                                        <div className={`affiliate-block-advanced-list affiliate-coupon-list3`}>
                                                 <RichList.Content
                                                  className={`affiliate-list3 affiliate-list-type-unordered  affiliate-alignment-${alignment3} affiliate-list-bullet-${bulletStyle3.name}`}
                                                  value={listTextItems3}
                                                />                      
                                         </div> 
                                     }


                                     { amazonpriceposition3 && amazonpriceposition3 == "Above Button" &&
                                          <Fragment>
                                            <div className={`affiliate-productcol3-amzon-price above`}>{amazonprice3}</div>
                                          </Fragment>
                                          }

                                         { amazonratingposition3 && amazonratingposition3 == "Above Button" &&
                                          <Fragment>
                                            <div className={`affiliate-productcol3-amzon-rating above`}>{amazonrating3}</div>
                                          </Fragment>
                                          } 

                                     { showButton && showButton == true &&
                                         <div className={`affiliate-coupon-btn`}>     
                                            <div className={`affiliate-coupon-btn-wrapper3`}>
                                                                       
                                                <a href = {spBtnHref3} className={classnames(`affiliate-btn  btn-is-${btnSize3} ${buttonWidth3 === "full"? "btn-is-fullw": buttonWidth3 === "flex"? `btn-is-flex-${btnSize3}`: ""} ${bntRounded3 ? 'btn-is-rounded' : ''} ${enableGlimmerEffect3 ? 'glimmer-effect' : ''}`).trim()}  
                                                    {...(btn_Htarget3 ? {...btn_Htarget3 && { rel: newrel3 }} : { rel: newrel3 }  )}
                                                    {...(btn_Htarget3 ? { target: '_blank' } : '' )}
                                                    >
                                                    { iconPosition3 && iconPosition3 == 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon3} ${iconPosition3}`}></i>
                                                    }
                                                    { iconPosition3 && iconPosition3 != 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText.Content value={spBtnText3} />
                                                    { iconPosition3 && iconPosition3 == 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon3} ${iconPosition3}`}></i>
                                                    }
                                                    { iconPosition3 && iconPosition3 != 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </a>
                                            </div>
                                        </div> 
                                    }


                                    { amazonpriceposition3 && amazonpriceposition3 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol3-amzon-price below`}>{amazonprice3}</div>
                                      </Fragment>
                                      }

                                    { amazonratingposition3 && amazonratingposition3 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol3-amzon-rating below`}>{amazonrating3}</div>
                                      </Fragment>
                                      }  

                                </li>
                            }

                            { numberOfBlock && numberOfBlock >= '4' &&
                                <li className={`affiliate-block-col box4`}>
                                    { flagStyle4 && flagStyle4 != '' && 
                                      <div className={`triangle4 ${flagStyle4}`}>

                                        { flagStyle4 && flagStyle4 == 'flag1' && 
                                            <span>
                                                <p>Budget <span>Pick</span></p>
                                                <i class="fas fa-trophy"></i>
                                            </span>
                                          }
                                          { flagStyle4 && flagStyle4 == 'flag2' && 
                                            <span>
                                                <p>Best <span>Value</span></p>
                                                <i class="fas fa-trophy"></i>
                                            </span>
                                          }
                                          { flagStyle4 && flagStyle4 == 'flag3' && 
                                            <span>
                                                <p>Premium <span>Pick</span></p>
                                                <i class="fas fa-trophy"></i>
                                            </span>
                                          }

                                      </div>
                                    }

                                    { showRating && showRating == true &&
                                        <div className="affiliate-coupon-starrating4"
                                            onMouseLeave={() => this.setState({ highlightedStars4: 0 })} >
                                            {[...Array(ratings4)].map((e, i) => (
                                              <div className={`affiliate-star-item`} key={i}>
                                                    {i < (highlightedStars4 ? highlightedStars4 : selectedStars4) ? (
                                                        highlightedStars4 ? (
                                                            highlightedStars4 - 1 === i ? (
                                                                selectedStars4 % 1 > 0 ? (
                                                                    highlightedStars4 - selectedStars4 - 0.5 !== 0 ? (
                                                                        <HalfStar fillColor={ratingsColor4} />
                                                                    ) : (
                                                                        <FullStar fillColor={ratingsColor4} />
                                                                    )
                                                                ) : highlightedStars4 - selectedStars4 !== 0 ? (
                                                                    <FullStar fillColor={ratingsColor4} />
                                                                ) : (
                                                                    <HalfStar fillColor={ratingsColor4} />
                                                                )
                                                            ) : (
                                                                <FullStar fillColor={ratingsColor4} />
                                                            )
                                                        ) : selectedStars4 - i >= 1 ? (
                                                            <FullStar fillColor={ratingsColor4} />
                                                        ) : (
                                                            <HalfStar fillColor={ratingsColor4} />
                                                        )
                                                    ) : (
                                                        <EmptyStar fillColor={ratingsColor4} />
                                                    )}
                                                </div>
                                            ))}
                                        </div>
                                    }

                                    { showTitle && showTitle == true &&
                                        <RichText.Content
                                            tagName={Tag4}
                                            value={titleText4}
                                            className="affiliate-coupon-title4"
                                        />     
                                    }

                                    { imageDesignType4 && imageDesignType4 == 'ab-fixed-design' &&  !externalImage4 && !externalImage4 == true &&
                                        <ReviewImage4 attributes={this.props.attributes} />
                                    }

                                    { imageDesignType4 && imageDesignType4 == 'ab-fixed-design' &&  externalImage4 && externalImage4 == true &&
                                        <div className={`affiliate-coupon-image4`}>
                                            <img src={externalImageSrc4} alt={externalImageAlt4} />
                                        </div>
                                    }

                                    { imageDesignType4 && imageDesignType4 == 'ab-dynamic-design' &&
                                        <div className={`aff-inner-block`}>
                                            <InnerBlocks.Content/>
                                        </div>    
                                    }

                                    { showContent && showContent == true &&
                                        <div className={`affiliate-block-advanced-list affiliate-coupon-list4`}>
                                                 <RichList.Content
                                                  className={`affiliate-list4 affiliate-list-type-unordered  affiliate-alignment-${alignment4} affiliate-list-bullet-${bulletStyle4.name}`}
                                                  value={listTextItems4}
                                                />                           
                                         </div> 
                                     }


                                     { amazonpriceposition4 && amazonpriceposition4 == "Above Button" &&
                                          <Fragment>
                                            <div className={`affiliate-productcol4-amzon-price above`}>{amazonprice4}</div>
                                          </Fragment>
                                          }

                                         { amazonratingposition4 && amazonratingposition4 == "Above Button" &&
                                          <Fragment>
                                            <div className={`affiliate-productcol4-amzon-rating above`}>{amazonrating4}</div>
                                          </Fragment>
                                          } 

                                     { showButton && showButton == true &&
                                         <div className={`affiliate-coupon-btn`}>     
                                            <div className={`affiliate-coupon-btn-wrapper4`}>
                                                                       
                                                <a href = {spBtnHref4} className={classnames(`affiliate-btn  btn-is-${btnSize4} ${buttonWidth4 === "full"? "btn-is-fullw": buttonWidth4 === "flex"? `btn-is-flex-${btnSize4}`: ""} ${bntRounded4 ? 'btn-is-rounded' : ''} ${enableGlimmerEffect4 ? 'glimmer-effect' : ''}`).trim()}  
                                                    {...(btn_Htarget4 ? {...btn_Htarget4 && { rel: newrel4 }} : { rel: newrel4 }  )}
                                                    {...(btn_Htarget4 ? { target: '_blank' } : '' )}
                                                    >
                                                    { iconPosition4 && iconPosition4 == 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon4} ${iconPosition4}`}></i>
                                                    }
                                                    { iconPosition4 && iconPosition4 != 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText.Content value={spBtnText4} />
                                                    { iconPosition4 && iconPosition4 == 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon4} ${iconPosition4}`}></i>
                                                    }
                                                    { iconPosition4 && iconPosition4 != 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </a>
                                            </div>
                                        </div> 
                                    }


                                    { amazonpriceposition4 && amazonpriceposition4 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol4-amzon-price below`}>{amazonprice4}</div>
                                      </Fragment>
                                      }

                                    { amazonratingposition4 && amazonratingposition4 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol4-amzon-rating below`}>{amazonrating4}</div>
                                      </Fragment>
                                      }  

                                </li>
                            }

                            { numberOfBlock && numberOfBlock >= '5' &&
                                <li className={`affiliate-block-col box5`}>
                                    { flagStyle5 && flagStyle5 != '' && 
                                      <div className={`triangle5 ${flagStyle5}`}>

                                        { flagStyle5 && flagStyle5 == 'flag1' && 
                                            <span>
                                                <p>Budget <span>Pick</span></p>
                                                <i class="fas fa-trophy"></i>
                                            </span>
                                          }
                                          { flagStyle5 && flagStyle5 == 'flag2' && 
                                            <span>
                                                <p>Best <span>Value</span></p>
                                                <i class="fas fa-trophy"></i>
                                            </span>
                                          }
                                          { flagStyle5 && flagStyle5 == 'flag3' && 
                                            <span>
                                                <p>Premium <span>Pick</span></p>
                                                <i class="fas fa-trophy"></i>
                                            </span>
                                          }

                                      </div>
                                    }

                                    { showRating && showRating == true &&
                                        <div className="affiliate-coupon-starrating5"
                                            onMouseLeave={() => this.setState({ highlightedStars5: 0 })} >
                                            {[...Array(ratings5)].map((e, i) => (
                                              <div className={`affiliate-star-item`} key={i}>
                                                    {i < (highlightedStars5 ? highlightedStars5 : selectedStars5) ? (
                                                        highlightedStars5 ? (
                                                            highlightedStars5 - 1 === i ? (
                                                                selectedStars5 % 1 > 0 ? (
                                                                    highlightedStars5 - selectedStars5 - 0.5 !== 0 ? (
                                                                        <HalfStar fillColor={ratingsColor5} />
                                                                    ) : (
                                                                        <FullStar fillColor={ratingsColor5} />
                                                                    )
                                                                ) : highlightedStars5 - selectedStars5 !== 0 ? (
                                                                    <FullStar fillColor={ratingsColor5} />
                                                                ) : (
                                                                    <HalfStar fillColor={ratingsColor5} />
                                                                )
                                                            ) : (
                                                                <FullStar fillColor={ratingsColor5} />
                                                            )
                                                        ) : selectedStars5 - i >= 1 ? (
                                                            <FullStar fillColor={ratingsColor5} />
                                                        ) : (
                                                            <HalfStar fillColor={ratingsColor5} />
                                                        )
                                                    ) : (
                                                        <EmptyStar fillColor={ratingsColor5} />
                                                    )}
                                                </div>
                                            ))}
                                        </div>
                                    }

                                    { showTitle && showTitle == true &&
                                        <RichText.Content
                                            tagName={Tag5}
                                            value={titleText5}
                                            className="affiliate-coupon-title5"
                                        />    
                                    } 

                                    { imageDesignType5 && imageDesignType5 == 'ab-fixed-design' &&  !externalImage5 && !externalImage5 == true &&
                                        <ReviewImage5 attributes={this.props.attributes} />
                                    }

                                    { imageDesignType5 && imageDesignType5 == 'ab-fixed-design' &&  externalImage5 && externalImage5 == true &&
                                        <div className={`affiliate-coupon-image5`}>
                                            <img src={externalImageSrc5} alt={externalImageAlt5} />
                                        </div>
                                    }

                                    { imageDesignType5 && imageDesignType5 == 'ab-dynamic-design' &&
                                        <div className={`aff-inner-block`}>
                                            <InnerBlocks.Content/>
                                        </div>    
                                    }

                                    { showContent && showContent == true &&
                                        <div className={`affiliate-block-advanced-list affiliate-coupon-list5`}>
                                                 <RichList.Content
                                                  className={`affiliate-list5 affiliate-list-type-unordered  affiliate-alignment-${alignment5} affiliate-list-bullet-${bulletStyle5.name}`}
                                                  value={listTextItems5}
                                                />                     
                                         </div> 
                                     }

                                     { amazonpriceposition5 && amazonpriceposition5 == "Above Button" &&
                                          <Fragment>
                                            <div className={`affiliate-productcol5-amzon-price above`}>{amazonprice5}</div>
                                          </Fragment>
                                          }

                                         { amazonratingposition5 && amazonratingposition5 == "Above Button" &&
                                          <Fragment>
                                            <div className={`affiliate-productcol5-amzon-rating above`}>{amazonrating5}</div>
                                          </Fragment>
                                          } 

                                     { showButton && showButton == true &&
                                         <div className={`affiliate-coupon-btn`}>     
                                            <div className={`affiliate-coupon-btn-wrapper5`}>
                                                                       
                                                <a href = {spBtnHref5} className={classnames(`affiliate-btn  btn-is-${btnSize5} ${buttonWidth5 === "full"? "btn-is-fullw": buttonWidth5 === "flex"? `btn-is-flex-${btnSize5}`: ""} ${bntRounded5 ? 'btn-is-rounded' : ''} ${enableGlimmerEffect5 ? 'glimmer-effect' : ''}`).trim()}  
                                                    {...(btn_Htarget5 ? {...btn_Htarget5 && { rel: newrel5 }} : { rel: newrel5 }  )}
                                                    {...(btn_Htarget5 ? { target: '_blank' } : '' )}
                                                    >
                                                    { iconPosition5 && iconPosition5 == 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon5} ${iconPosition5}`}></i>
                                                    }
                                                    { iconPosition5 && iconPosition5 != 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText.Content value={spBtnText5} />
                                                    { iconPosition5 && iconPosition5 == 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon5} ${iconPosition5}`}></i>
                                                    }
                                                    { iconPosition5 && iconPosition5 != 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </a>
                                            </div>
                                        </div>  
                                    }


                                    { amazonpriceposition5 && amazonpriceposition5 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol5-amzon-price below`}>{amazonprice5}</div>
                                      </Fragment>
                                      }

                                    { amazonratingposition5 && amazonratingposition5 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol5-amzon-rating below`}>{amazonrating5}</div>
                                      </Fragment>
                                      }  

                                </li>
                            }

                            { numberOfBlock && numberOfBlock >= '6' &&
                                <li className={`affiliate-block-col box6`}>
                                    { flagStyle6 && flagStyle6 != '' && 
                                      <div className={`triangle6 ${flagStyle6}`}>

                                        { flagStyle6 && flagStyle6 == 'flag1' && 
                                            <span>
                                                <p>Budget <span>Pick</span></p>
                                                <i class="fas fa-trophy"></i>
                                            </span>
                                          }
                                          { flagStyle6 && flagStyle6 == 'flag2' && 
                                            <span>
                                                <p>Best <span>Value</span></p>
                                                <i class="fas fa-trophy"></i>
                                            </span>
                                          }
                                          { flagStyle6 && flagStyle6 == 'flag3' && 
                                            <span>
                                                <p>Premium <span>Pick</span></p>
                                                <i class="fas fa-trophy"></i>
                                            </span>
                                          }

                                      </div>
                                    }

                                    { showRating && showRating == true &&
                                        <div className="affiliate-coupon-starrating6"
                                            onMouseLeave={() => this.setState({ highlightedStars6: 0 })} >
                                            {[...Array(ratings6)].map((e, i) => (
                                              <div className={`affiliate-star-item`} key={i}>
                                                    {i < (highlightedStars6 ? highlightedStars6 : selectedStars6) ? (
                                                        highlightedStars6 ? (
                                                            highlightedStars6 - 1 === i ? (
                                                                selectedStars6 % 1 > 0 ? (
                                                                    highlightedStars6 - selectedStars6 - 0.5 !== 0 ? (
                                                                        <HalfStar fillColor={ratingsColor6} />
                                                                    ) : (
                                                                        <FullStar fillColor={ratingsColor6} />
                                                                    )
                                                                ) : highlightedStars6 - selectedStars6 !== 0 ? (
                                                                    <FullStar fillColor={ratingsColor6} />
                                                                ) : (
                                                                    <HalfStar fillColor={ratingsColor6} />
                                                                )
                                                            ) : (
                                                                <FullStar fillColor={ratingsColor6} />
                                                            )
                                                        ) : selectedStars6 - i >= 1 ? (
                                                            <FullStar fillColor={ratingsColor6} />
                                                        ) : (
                                                            <HalfStar fillColor={ratingsColor6} />
                                                        )
                                                    ) : (
                                                        <EmptyStar fillColor={ratingsColor6} />
                                                    )}
                                                </div>
                                            ))}
                                        </div>
                                    }

                                    { showTitle && showTitle == true &&
                                        <RichText.Content
                                            tagName={Tag6}
                                            value={titleText6}
                                            className="affiliate-coupon-title6"
                                        />    
                                    } 

                                    { imageDesignType6 && imageDesignType6 == 'ab-fixed-design' &&  !externalImage6 && !externalImage6 == true &&
                                        <ReviewImage6 attributes={this.props.attributes} />
                                    }

                                    { imageDesignType6 && imageDesignType6 == 'ab-fixed-design' &&  externalImage6 && externalImage6 == true &&
                                        <div className={`affiliate-coupon-image6`}>
                                            <img src={externalImageSrc6} alt={externalImageAlt6} />
                                        </div>
                                    }

                                    { imageDesignType6 && imageDesignType6 == 'ab-dynamic-design' &&
                                        <div className={`aff-inner-block`}>
                                            <InnerBlocks.Content/>
                                        </div>    
                                    }

                                    { showContent && showContent == true &&
                                        <div className={`affiliate-block-advanced-list affiliate-coupon-list6`}>
                                                 <RichList.Content
                                                  className={`affiliate-list6 affiliate-list-type-unordered  affiliate-alignment-${alignment6} affiliate-list-bullet-${bulletStyle6.name}`}
                                                  value={listTextItems6}
                                                />                      
                                         </div>
                                     } 


                                     { amazonpriceposition6 && amazonpriceposition6 == "Above Button" &&
                                          <Fragment>
                                            <div className={`affiliate-productcol6-amzon-price above`}>{amazonprice6}</div>
                                          </Fragment>
                                          }

                                         { amazonratingposition6 && amazonratingposition6 == "Above Button" &&
                                          <Fragment>
                                            <div className={`affiliate-productcol6-amzon-rating above`}>{amazonrating6}</div>
                                          </Fragment>
                                          } 

                                     { showButton && showButton == true &&
                                         <div className={`affiliate-coupon-btn`}>     
                                            <div className={`affiliate-coupon-btn-wrapper6`}>
                                                                       
                                                <a href = {spBtnHref6} className={classnames(`affiliate-btn  btn-is-${btnSize6} ${buttonWidth6 === "full"? "btn-is-fullw": buttonWidth6 === "flex"? `btn-is-flex-${btnSize6}`: ""} ${bntRounded6 ? 'btn-is-rounded' : ''} ${enableGlimmerEffect6 ? 'glimmer-effect' : ''}`).trim()}  
                                                    {...(btn_Htarget6 ? {...btn_Htarget6 && { rel: newrel6 }} : { rel: newrel6 }  )}
                                                    {...(btn_Htarget6 ? { target: '_blank' } : '' )}
                                                    >
                                                    { iconPosition6 && iconPosition6 == 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon6} ${iconPosition6}`}></i>
                                                    }
                                                    { iconPosition6 && iconPosition6 != 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText.Content value={spBtnText6} />
                                                    { iconPosition6 && iconPosition6 == 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon6} ${iconPosition6}`}></i>
                                                    }
                                                    { iconPosition6 && iconPosition6 != 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </a>
                                            </div>
                                        </div> 
                                    }



                                    { amazonpriceposition6 && amazonpriceposition6 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol6-amzon-price below`}>{amazonprice6}</div>
                                      </Fragment>
                                      }

                                    { amazonratingposition6 && amazonratingposition6 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol6-amzon-rating below`}>{amazonrating6}</div>
                                      </Fragment>
                                      }  

                                </li>
                            }

                            { numberOfBlock && numberOfBlock >= '7' &&
                                <li className={`affiliate-block-col box7`}>
                                    { flagStyle7 && flagStyle7 != '' && 
                                      <div className={`triangle7 ${flagStyle7}`}>

                                        { flagStyle7 && flagStyle7 == 'flag1' && 
                                            <span>
                                                <p>Budget <span>Pick</span></p>
                                                <i class="fas fa-trophy"></i>
                                            </span>
                                          }
                                          { flagStyle7 && flagStyle7 == 'flag2' && 
                                            <span>
                                                <p>Best <span>Value</span></p>
                                                <i class="fas fa-trophy"></i>
                                            </span>
                                          }
                                          { flagStyle7 && flagStyle7 == 'flag3' && 
                                            <span>
                                                <p>Premium <span>Pick</span></p>
                                                <i class="fas fa-trophy"></i>
                                            </span>
                                          }

                                      </div>
                                    }

                                    { showRating && showRating == true &&
                                        <div className="affiliate-coupon-starrating7"
                                            onMouseLeave={() => this.setState({ highlightedStars7: 0 })} >
                                            {[...Array(ratings7)].map((e, i) => (
                                              <div className={`affiliate-star-item`} key={i}>
                                                    {i < (highlightedStars7 ? highlightedStars7 : selectedStars7) ? (
                                                        highlightedStars7 ? (
                                                            highlightedStars7 - 1 === i ? (
                                                                selectedStars7 % 1 > 0 ? (
                                                                    highlightedStars7 - selectedStars7 - 0.5 !== 0 ? (
                                                                        <HalfStar fillColor={ratingsColor7} />
                                                                    ) : (
                                                                        <FullStar fillColor={ratingsColor7} />
                                                                    )
                                                                ) : highlightedStars7 - selectedStars7 !== 0 ? (
                                                                    <FullStar fillColor={ratingsColor7} />
                                                                ) : (
                                                                    <HalfStar fillColor={ratingsColor7} />
                                                                )
                                                            ) : (
                                                                <FullStar fillColor={ratingsColor7} />
                                                            )
                                                        ) : selectedStars7 - i >= 1 ? (
                                                            <FullStar fillColor={ratingsColor7} />
                                                        ) : (
                                                            <HalfStar fillColor={ratingsColor7} />
                                                        )
                                                    ) : (
                                                        <EmptyStar fillColor={ratingsColor7} />
                                                    )}
                                                </div>
                                            ))}
                                        </div>
                                    }

                                    { showTitle && showTitle == true &&
                                        <RichText.Content
                                            tagName={Tag7}
                                            value={titleText7}
                                            className="affiliate-coupon-title7"
                                        />     
                                    }

                                    { imageDesignType7 && imageDesignType7 == 'ab-fixed-design' &&  !externalImage7 && !externalImage7 == true &&
                                        <ReviewImage7 attributes={this.props.attributes} />
                                    }

                                    { imageDesignType7 && imageDesignType7 == 'ab-fixed-design' &&  externalImage7 && externalImage7 == true &&
                                        <div className={`affiliate-coupon-image7`}>
                                            <img src={externalImageSrc7} alt={externalImageAlt7} />
                                        </div>
                                    }

                                    { imageDesignType7 && imageDesignType7 == 'ab-dynamic-design' &&
                                        <div className={`aff-inner-block`}>
                                            <InnerBlocks.Content/>
                                        </div>    
                                    }

                                    { showContent && showContent == true &&
                                        <div className={`affiliate-block-advanced-list affiliate-coupon-list7`}>
                                                 <RichList.Content
                                                  className={`affiliate-list7 affiliate-list-type-unordered  affiliate-alignment-${alignment7} affiliate-list-bullet-${bulletStyle7.name}`}
                                                  value={listTextItems7}
                                                />            
                                         </div> 
                                     }


                                     { amazonpriceposition7 && amazonpriceposition7 == "Above Button" &&
                                          <Fragment>
                                            <div className={`affiliate-productcol7-amzon-price above`}>{amazonprice7}</div>
                                          </Fragment>
                                          }

                                         { amazonratingposition7 && amazonratingposition7 == "Above Button" &&
                                          <Fragment>
                                            <div className={`affiliate-productcol7-amzon-rating above`}>{amazonrating7}</div>
                                          </Fragment>
                                          } 

                                     { showButton && showButton == true &&
                                         <div className={`affiliate-coupon-btn`}>     
                                            <div className={`affiliate-coupon-btn-wrapper7`}>
                                                                       
                                                <a href = {spBtnHref7} className={classnames(`affiliate-btn  btn-is-${btnSize7} ${buttonWidth7 === "full"? "btn-is-fullw": buttonWidth7 === "flex"? `btn-is-flex-${btnSize7}`: ""} ${bntRounded7 ? 'btn-is-rounded' : ''} ${enableGlimmerEffect7 ? 'glimmer-effect' : ''}`).trim()}  
                                                    {...(btn_Htarget7 ? {...btn_Htarget7 && { rel: newrel7 }} : { rel: newrel7 }  )}
                                                    {...(btn_Htarget7 ? { target: '_blank' } : '' )}
                                                    >
                                                    { iconPosition7 && iconPosition7 == 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon7} ${iconPosition7}`}></i>
                                                    }
                                                    { iconPosition7 && iconPosition7 != 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText.Content value={spBtnText7} />
                                                    { iconPosition7 && iconPosition7 == 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon7} ${iconPosition7}`}></i>
                                                    }
                                                    { iconPosition7 && iconPosition7 != 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </a>
                                            </div>
                                        </div> 
                                    }


                                    { amazonpriceposition7 && amazonpriceposition7 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol7-amzon-price below`}>{amazonprice7}</div>
                                      </Fragment>
                                      }

                                    { amazonratingposition7 && amazonratingposition7 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol7-amzon-rating below`}>{amazonrating7}</div>
                                      </Fragment>
                                      }  

                                </li>
                            }
                        </Fragment>

                    }


                    { productColumnStyle && productColumnStyle == 'layout2' &&


                        <Fragment key={'layout2'}>
                            { numberOfBlock && numberOfBlock >= '1' &&
                                <li className={`affiliate-block-col box1`}>

                                    { showtopText && showtopText == true &&
                                        <RichText.Content
                                          tagName="div"
                                          className="affiliate-coupon-toptext1"
                                          value={ topText1 }
                                        />
                                    }

                                    { imageDesignType1 && imageDesignType1 == 'ab-fixed-design' && !externalImage1 && !externalImage1 == true &&
                                        <ReviewImage1 attributes={this.props.attributes} />
                                    }

                                    { imageDesignType1 && imageDesignType1 == 'ab-fixed-design' &&  externalImage1 && externalImage1 == true &&
                                        <div className={`affiliate-coupon-image1`}>
                                            <img src={externalImageSrc1} alt={externalImageAlt1} />
                                        </div>
                                    }


                                    { imageDesignType1 && imageDesignType1 == 'ab-dynamic-design' &&
                                        <div className={`aff-inner-block`}>
                                            <InnerBlocks.Content/>
                                        </div>    
                                    }

                                    
                                    { showTitle && showTitle == true &&
                                        <RichText.Content
                                            tagName={Tag1}
                                            value={titleText1}
                                            className="affiliate-coupon-title1"
                                        />     
                                    }

                                    { showContent && showContent == true &&
                                        <RichText.Content
                                          tagName="p"
                                          className="affiliate-coupon-content1"
                                          value={ listTextItems1 }
                                        /> 
                                    }


                                    { amazonpriceposition1 && amazonpriceposition1 == "Above Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol1-amzon-price above`}>{amazonprice1}</div>
                                      </Fragment>
                                      }

                                     { amazonratingposition1 && amazonratingposition1 == "Above Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol1-amzon-rating above`}>{amazonrating1}</div>
                                      </Fragment>
                                      } 


                                    { showButton && showButton == true &&
                                         <div className={`affiliate-coupon-btn`}> 
                                            <div className={`affiliate-coupon-btn-wrapper1`}>
                                                                           
                                                <a href = {spBtnHref1} className={classnames(`affiliate-btn  btn-is-${btnSize1} ${buttonWidth1 === "full"? "btn-is-fullw": buttonWidth1 === "flex"? `btn-is-flex-${btnSize1}`: ""} ${bntRounded1 ? 'btn-is-rounded' : ''} ${enableGlimmerEffect ? 'glimmer-effect' : ''}`).trim()}  
                                                    {...(btn_Htarget1 ? {...btn_Htarget1 && { rel: newrel1 }} : { rel: newrel1 }  )}
                                                    {...(btn_Htarget1 ? { target: '_blank' } : '' )}
                                                    >
                                                    { iconPosition1 && iconPosition1 == 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon1} ${iconPosition1}`}></i>
                                                    }
                                                    { iconPosition1 && iconPosition1 != 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText.Content value={spBtnText1} />
                                                    { iconPosition1 && iconPosition1 == 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon1} ${iconPosition1}`}></i>
                                                    }
                                                    { iconPosition1 && iconPosition1 != 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </a>
                                            </div>
                                        </div> 
                                    }

                                    { amazonpriceposition1 && amazonpriceposition1 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol1-amzon-price below`}>{amazonprice1}</div>
                                      </Fragment>
                                      }

                                    { amazonratingposition1 && amazonratingposition1 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol1-amzon-rating below`}>{amazonrating1}</div>
                                      </Fragment>
                                      }  


                                    { showbottomText && showbottomText == true &&
                                        <RichText.Content
                                          tagName="div"
                                          className="affiliate-coupon-bottomtext1"
                                          value={ bottomText1 }
                                        />
                                    }

                                </li>
                            }

                            { numberOfBlock && numberOfBlock >= '2' &&
                                <li className={`affiliate-block-col box2`}>

                                    { showtopText && showtopText == true &&
                                        <RichText.Content
                                          tagName="div"
                                          className="affiliate-coupon-toptext2"
                                          value={ topText2 }
                                        />
                                    }
                                     
                                    { imageDesignType2 && imageDesignType2 == 'ab-fixed-design' &&  !externalImage2 && !externalImage2 == true &&
                                        <ReviewImage2 attributes={this.props.attributes} />
                                    }

                                    { imageDesignType2 && imageDesignType2 == 'ab-fixed-design' &&  externalImage2 && externalImage2 == true &&
                                        <div className={`affiliate-coupon-image2`}>
                                            <img src={externalImageSrc2} alt={externalImageAlt2} />
                                        </div>
                                    }

                                    { imageDesignType2 && imageDesignType2 == 'ab-dynamic-design' &&
                                        <div className={`aff-inner-block`}>
                                            <InnerBlocks.Content/>
                                        </div>    
                                    }
                                    
                                    { showTitle && showTitle == true &&
                                        <RichText.Content
                                            tagName={Tag2}
                                            value={titleText2}
                                            className="affiliate-coupon-title2"
                                        />   
                                    }  

                                    

                                    { showContent && showContent == true &&
                                        <RichText.Content
                                          tagName="p"
                                          className="affiliate-coupon-content2"
                                          value={ listTextItems2 }
                                        />
                                     }

                                     { amazonpriceposition2 && amazonpriceposition2 == "Above Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol2-amzon-price above`}>{amazonprice2}</div>
                                      </Fragment>
                                      }

                                     { amazonratingposition2 && amazonratingposition2 == "Above Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol2-amzon-rating above`}>{amazonrating2}</div>
                                      </Fragment>
                                      } 

                                     { showButton && showButton == true &&
                                         <div className={`affiliate-coupon-btn`}>  
                                            <div className={`affiliate-coupon-btn-wrapper2`}>
                                                                          
                                                <a href = {spBtnHref2} className={classnames(`affiliate-btn  btn-is-${btnSize2} ${buttonWidth2 === "full"? "btn-is-fullw": buttonWidth2 === "flex"? `btn-is-flex-${btnSize2}`: ""} ${bntRounded2 ? 'btn-is-rounded' : ''} ${enableGlimmerEffect2 ? 'glimmer-effect' : ''}`).trim()}  
                                                    {...(btn_Htarget2 ? {...btn_Htarget2 && { rel: newrel2 }} : { rel: newrel2 }  )}
                                                    {...(btn_Htarget2 ? { target: '_blank' } : '' )}
                                                    >
                                                    { iconPosition2 && iconPosition2 == 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon2} ${iconPosition2}`}></i>
                                                    }
                                                    { iconPosition2 && iconPosition2 != 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText.Content value={spBtnText2} />
                                                    { iconPosition2 && iconPosition2 == 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon2} ${iconPosition2}`}></i>
                                                    }
                                                    { iconPosition2 && iconPosition2 != 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </a>
                                            </div>
                                        </div> 
                                    }


                                    { amazonpriceposition2 && amazonpriceposition2 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol2-amzon-price below`}>{amazonprice2}</div>
                                      </Fragment>
                                      }

                                    { amazonratingposition2 && amazonratingposition2 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol2-amzon-rating below`}>{amazonrating2}</div>
                                      </Fragment>
                                      }  


                                    { showbottomText && showbottomText == true &&
                                        <RichText.Content
                                          tagName="div"
                                          className="affiliate-coupon-bottomtext2"
                                          value={ bottomText2 }
                                        />
                                    }

                                </li>
                            }

                            { numberOfBlock && numberOfBlock >= '3' &&
                                <li className={`affiliate-block-col box3`}>

                                    { showtopText && showtopText == true &&
                                        <RichText.Content
                                          tagName="div"
                                          className="affiliate-coupon-toptext3"
                                          value={ topText3 }
                                        />
                                    }

                                    { imageDesignType3 && imageDesignType3 == 'ab-fixed-design' &&  !externalImage3 && !externalImage3 == true &&
                                        <ReviewImage3 attributes={this.props.attributes} />
                                    }

                                    { imageDesignType3 && imageDesignType3 == 'ab-fixed-design' &&  externalImage3 && externalImage3 == true &&
                                        <div className={`affiliate-coupon-image3`}>
                                            <img src={externalImageSrc3} alt={externalImageAlt3} />
                                        </div>
                                    }

                                    { imageDesignType3 && imageDesignType3 == 'ab-dynamic-design' &&
                                        <div className={`aff-inner-block`}>
                                            <InnerBlocks.Content/>
                                        </div>    
                                    }
                                     
 
                                    { showTitle && showTitle == true &&
                                        <RichText.Content
                                            tagName={Tag3}
                                            value={titleText3}
                                            className="affiliate-coupon-title3"
                                        />  
                                    }   

                                    { showContent && showContent == true &&
                                        <RichText.Content
                                          tagName="p"
                                          className="affiliate-coupon-content3"
                                          value={ listTextItems3 }
                                        /> 
                                     }


                                     { amazonpriceposition3 && amazonpriceposition3 == "Above Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol3-amzon-price above`}>{amazonprice3}</div>
                                      </Fragment>
                                      }

                                     { amazonratingposition3 && amazonratingposition3 == "Above Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol3-amzon-rating above`}>{amazonrating3}</div>
                                      </Fragment>
                                      } 

                                     { showButton && showButton == true &&
                                         <div className={`affiliate-coupon-btn`}>     
                                            <div className={`affiliate-coupon-btn-wrapper3`}>
                                                                       
                                                <a href = {spBtnHref3} className={classnames(`affiliate-btn  btn-is-${btnSize3} ${buttonWidth3 === "full"? "btn-is-fullw": buttonWidth3 === "flex"? `btn-is-flex-${btnSize3}`: ""} ${bntRounded3 ? 'btn-is-rounded' : ''} ${enableGlimmerEffect3 ? 'glimmer-effect' : ''}`).trim()}  
                                                    {...(btn_Htarget3 ? {...btn_Htarget3 && { rel: newrel3 }} : { rel: newrel3 }  )}
                                                    {...(btn_Htarget3 ? { target: '_blank' } : '' )}
                                                    >
                                                    { iconPosition3 && iconPosition3 == 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon3} ${iconPosition3}`}></i>
                                                    }
                                                    { iconPosition3 && iconPosition3 != 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText.Content value={spBtnText3} />
                                                    { iconPosition3 && iconPosition3 == 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon3} ${iconPosition3}`}></i>
                                                    }
                                                    { iconPosition3 && iconPosition3 != 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </a>
                                            </div>
                                        </div> 
                                    }


                                    { amazonpriceposition3 && amazonpriceposition3 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol3-amzon-price below`}>{amazonprice3}</div>
                                      </Fragment>
                                      }

                                    { amazonratingposition3 && amazonratingposition3 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol3-amzon-rating below`}>{amazonrating3}</div>
                                      </Fragment>
                                      }  

                                    { showbottomText && showbottomText == true &&
                                        <RichText.Content
                                          tagName="div"
                                          className="affiliate-coupon-bottomtext3"
                                          value={ bottomText3 }
                                        />
                                    }

                                </li>
                            }

                            { numberOfBlock && numberOfBlock >= '4' &&
                                <li className={`affiliate-block-col box4`}>

                                    { showtopText && showtopText == true &&
                                        <RichText.Content
                                          tagName="div"
                                          className="affiliate-coupon-toptext4"
                                          value={ topText4 }
                                        />
                                    }

                                    { imageDesignType4 && imageDesignType4 == 'ab-fixed-design' &&  !externalImage4 && !externalImage4 == true &&
                                        <ReviewImage4 attributes={this.props.attributes} />
                                    }

                                    { imageDesignType4 && imageDesignType4 == 'ab-fixed-design' &&  externalImage4 && externalImage4 == true &&
                                        <div className={`affiliate-coupon-image4`}>
                                            <img src={externalImageSrc4} alt={externalImageAlt4} />
                                        </div>
                                    }

                                    { imageDesignType4 && imageDesignType4 == 'ab-dynamic-design' &&
                                        <div className={`aff-inner-block`}>
                                            <InnerBlocks.Content/>
                                        </div>    
                                    }
                                    

                                    { showTitle && showTitle == true &&
                                        <RichText.Content
                                            tagName={Tag4}
                                            value={titleText4}
                                            className="affiliate-coupon-title4"
                                        />     
                                    }

                                    { showContent && showContent == true &&
                                        <RichText.Content
                                          tagName="p"
                                          className="affiliate-coupon-content4"
                                          value={ listTextItems4 }
                                        /> 
                                     }


                                     { amazonpriceposition4 && amazonpriceposition4 == "Above Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol4-amzon-price above`}>{amazonprice4}</div>
                                      </Fragment>
                                      }

                                     { amazonratingposition4 && amazonratingposition4 == "Above Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol4-amzon-rating above`}>{amazonrating4}</div>
                                      </Fragment>
                                      } 

                                     { showButton && showButton == true &&
                                         <div className={`affiliate-coupon-btn`}>     
                                            <div className={`affiliate-coupon-btn-wrapper4`}>
                                                                       
                                                <a href = {spBtnHref4} className={classnames(`affiliate-btn  btn-is-${btnSize4} ${buttonWidth4 === "full"? "btn-is-fullw": buttonWidth4 === "flex"? `btn-is-flex-${btnSize4}`: ""} ${bntRounded4 ? 'btn-is-rounded' : ''} ${enableGlimmerEffect4 ? 'glimmer-effect' : ''}`).trim()}  
                                                    {...(btn_Htarget4 ? {...btn_Htarget4 && { rel: newrel4 }} : { rel: newrel4 }  )}
                                                    {...(btn_Htarget4 ? { target: '_blank' } : '' )}
                                                    >
                                                    { iconPosition4 && iconPosition4 == 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon4} ${iconPosition4}`}></i>
                                                    }
                                                    { iconPosition4 && iconPosition4 != 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText.Content value={spBtnText4} />
                                                    { iconPosition4 && iconPosition4 == 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon4} ${iconPosition4}`}></i>
                                                    }
                                                    { iconPosition4 && iconPosition4 != 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </a>
                                            </div>
                                        </div> 
                                    }


                                    { amazonpriceposition4 && amazonpriceposition4 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol4-amzon-price below`}>{amazonprice4}</div>
                                      </Fragment>
                                      }

                                    { amazonratingposition4 && amazonratingposition4 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol4-amzon-rating below`}>{amazonrating4}</div>
                                      </Fragment>
                                      }  

                                    { showbottomText && showbottomText == true &&
                                        <RichText.Content
                                          tagName="div"
                                          className="affiliate-coupon-bottomtext4"
                                          value={ bottomText4 }
                                        />
                                    }

                                </li>
                            }

                            { numberOfBlock && numberOfBlock >= '5' &&
                                <li className={`affiliate-block-col box5`}>

                                    { showtopText && showtopText == true &&
                                        <RichText.Content
                                          tagName="div"
                                          className="affiliate-coupon-toptext5"
                                          value={ topText5 }
                                        />
                                    }
                                     
                                    { imageDesignType5 && imageDesignType5 == 'ab-fixed-design' &&  !externalImage5 && !externalImage5 == true &&
                                        <ReviewImage5 attributes={this.props.attributes} />
                                    }

                                    { imageDesignType5 && imageDesignType5 == 'ab-fixed-design' &&  externalImage5 && externalImage5 == true &&
                                        <div className={`affiliate-coupon-image5`}>
                                            <img src={externalImageSrc5} alt={externalImageAlt5} />
                                        </div>
                                    }

                                    { imageDesignType5 && imageDesignType5 == 'ab-dynamic-design' &&
                                        <div className={`aff-inner-block`}>
                                            <InnerBlocks.Content/>
                                        </div>    
                                    }
                                     

                                    { showTitle && showTitle == true &&
                                        <RichText.Content
                                            tagName={Tag5}
                                            value={titleText5}
                                            className="affiliate-coupon-title5"
                                        />    
                                    } 


                                    { showContent && showContent == true &&
                                        <RichText.Content
                                          tagName="p"
                                          className="affiliate-coupon-content5"
                                          value={ listTextItems5 }
                                        />
                                     }


                                     { amazonpriceposition5 && amazonpriceposition5 == "Above Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol5-amzon-price above`}>{amazonprice5}</div>
                                      </Fragment>
                                      }

                                     { amazonratingposition5 && amazonratingposition5 == "Above Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol5-amzon-rating above`}>{amazonrating5}</div>
                                      </Fragment>
                                      } 

                                     { showButton && showButton == true &&
                                         <div className={`affiliate-coupon-btn`}>     
                                            <div className={`affiliate-coupon-btn-wrapper5`}>
                                                                       
                                                <a href = {spBtnHref5} className={classnames(`affiliate-btn  btn-is-${btnSize5} ${buttonWidth5 === "full"? "btn-is-fullw": buttonWidth5 === "flex"? `btn-is-flex-${btnSize5}`: ""} ${bntRounded5 ? 'btn-is-rounded' : ''} ${enableGlimmerEffect5 ? 'glimmer-effect' : ''}`).trim()}  
                                                    {...(btn_Htarget5 ? {...btn_Htarget5 && { rel: newrel5 }} : { rel: newrel5 }  )}
                                                    {...(btn_Htarget5 ? { target: '_blank' } : '' )}
                                                    >
                                                    { iconPosition5 && iconPosition5 == 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon5} ${iconPosition5}`}></i>
                                                    }
                                                    { iconPosition5 && iconPosition5 != 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText.Content value={spBtnText5} />
                                                    { iconPosition5 && iconPosition5 == 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon5} ${iconPosition5}`}></i>
                                                    }
                                                    { iconPosition5 && iconPosition5 != 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </a>
                                            </div>
                                        </div>  
                                    }


                                    { amazonpriceposition5 && amazonpriceposition5 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol5-amzon-price below`}>{amazonprice5}</div>
                                      </Fragment>
                                      }

                                    { amazonratingposition5 && amazonratingposition5 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol5-amzon-rating below`}>{amazonrating5}</div>
                                      </Fragment>
                                      }  

                                    { showbottomText && showbottomText == true &&
                                        <RichText.Content
                                          tagName="div"
                                          className="affiliate-coupon-bottomtext5"
                                          value={ bottomText5 }
                                        />
                                    }

                                </li>
                            }

                            { numberOfBlock && numberOfBlock >= '6' &&
                                <li className={`affiliate-block-col box6`}>

                                    { showtopText && showtopText == true &&
                                        <RichText.Content
                                          tagName="div"
                                          className="affiliate-coupon-toptext6"
                                          value={ topText6 }
                                        />
                                    }

                                    { imageDesignType6 && imageDesignType6 == 'ab-fixed-design' &&  !externalImage6 && !externalImage6 == true &&
                                        <ReviewImage6 attributes={this.props.attributes} />
                                    }

                                    { imageDesignType6 && imageDesignType6 == 'ab-fixed-design' &&  externalImage6 && externalImage6 == true &&
                                        <div className={`affiliate-coupon-image6`}>
                                            <img src={externalImageSrc6} alt={externalImageAlt6} />
                                        </div>
                                    }

                                    { imageDesignType6 && imageDesignType6 == 'ab-dynamic-design' &&
                                        <div className={`aff-inner-block`}>
                                            <InnerBlocks.Content/>
                                        </div>    
                                    }

                                    { showTitle && showTitle == true &&
                                        <RichText.Content
                                            tagName={Tag6}
                                            value={titleText6}
                                            className="affiliate-coupon-title6"
                                        />    
                                    } 

                                    

                                    { showContent && showContent == true &&
                                        <RichText.Content
                                          tagName="p"
                                          className="affiliate-coupon-content6"
                                          value={ listTextItems6 }
                                        />
                                     } 


                                     { amazonpriceposition6 && amazonpriceposition6 == "Above Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol6-amzon-price above`}>{amazonprice6}</div>
                                      </Fragment>
                                      }

                                     { amazonratingposition6 && amazonratingposition6 == "Above Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol6-amzon-rating above`}>{amazonrating6}</div>
                                      </Fragment>
                                      } 

                                     { showButton && showButton == true &&
                                         <div className={`affiliate-coupon-btn`}>     
                                            <div className={`affiliate-coupon-btn-wrapper6`}>
                                                                       
                                                <a href = {spBtnHref6} className={classnames(`affiliate-btn  btn-is-${btnSize6} ${buttonWidth6 === "full"? "btn-is-fullw": buttonWidth6 === "flex"? `btn-is-flex-${btnSize6}`: ""} ${bntRounded6 ? 'btn-is-rounded' : ''} ${enableGlimmerEffect6 ? 'glimmer-effect' : ''}`).trim()}  
                                                    {...(btn_Htarget6 ? {...btn_Htarget6 && { rel: newrel6 }} : { rel: newrel6 }  )}
                                                    {...(btn_Htarget6 ? { target: '_blank' } : '' )}
                                                    >
                                                    { iconPosition6 && iconPosition6 == 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon6} ${iconPosition6}`}></i>
                                                    }
                                                    { iconPosition6 && iconPosition6 != 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText.Content value={spBtnText6} />
                                                    { iconPosition6 && iconPosition6 == 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon6} ${iconPosition6}`}></i>
                                                    }
                                                    { iconPosition6 && iconPosition6 != 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </a>
                                            </div>
                                        </div> 
                                    }


                                    { amazonpriceposition6 && amazonpriceposition6 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol6-amzon-price below`}>{amazonprice6}</div>
                                      </Fragment>
                                      }

                                    { amazonratingposition6 && amazonratingposition6 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol6-amzon-rating below`}>{amazonrating6}</div>
                                      </Fragment>
                                      }  

                                    { showbottomText && showbottomText == true &&
                                        <RichText.Content
                                          tagName="div"
                                          className="affiliate-coupon-bottomtext6"
                                          value={ bottomText6 }
                                        />
                                    }

                                </li>
                            }

                            { numberOfBlock && numberOfBlock >= '7' &&
                                <li className={`affiliate-block-col box7`}>

                                    { showtopText && showtopText == true &&
                                        <RichText.Content
                                          tagName="div"
                                          className="affiliate-coupon-toptext7"
                                          value={ topText7 }
                                        />
                                    }
                                     
                                    { imageDesignType7 && imageDesignType7 == 'ab-fixed-design' &&  !externalImage7 && !externalImage7 == true &&
                                        <ReviewImage7 attributes={this.props.attributes} />
                                    }

                                    { imageDesignType7 && imageDesignType7 == 'ab-fixed-design' &&  externalImage7 && externalImage7 == true &&
                                        <div className={`affiliate-coupon-image7`}>
                                            <img src={externalImageSrc7} alt={externalImageAlt7} />
                                        </div>
                                    }

                                    { imageDesignType7 && imageDesignType7 == 'ab-dynamic-design' &&
                                        <div className={`aff-inner-block`}>
                                            <InnerBlocks.Content/>
                                        </div>    
                                    }
 

                                    { showTitle && showTitle == true &&
                                        <RichText.Content
                                            tagName={Tag7}
                                            value={titleText7}
                                            className="affiliate-coupon-title7"
                                        />     
                                    }

                                    

                                    { showContent && showContent == true &&
                                        <RichText.Content
                                          tagName="p"
                                          className="affiliate-coupon-content7"
                                          value={ listTextItems7 }
                                        /> 
                                     }


                                     { amazonpriceposition7 && amazonpriceposition7 == "Above Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol7-amzon-price above`}>{amazonprice7}</div>
                                      </Fragment>
                                      }

                                     { amazonratingposition7 && amazonratingposition7 == "Above Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol7-amzon-rating above`}>{amazonrating7}</div>
                                      </Fragment>
                                      } 

                                     { showButton && showButton == true &&
                                         <div className={`affiliate-coupon-btn`}>     
                                            <div className={`affiliate-coupon-btn-wrapper7`}>
                                                                       
                                                <a href = {spBtnHref7} className={classnames(`affiliate-btn  btn-is-${btnSize7} ${buttonWidth7 === "full"? "btn-is-fullw": buttonWidth7 === "flex"? `btn-is-flex-${btnSize7}`: ""} ${bntRounded7 ? 'btn-is-rounded' : ''} ${enableGlimmerEffect7 ? 'glimmer-effect' : ''}`).trim()}  
                                                    {...(btn_Htarget7 ? {...btn_Htarget7 && { rel: newrel7 }} : { rel: newrel7 }  )}
                                                    {...(btn_Htarget7 ? { target: '_blank' } : '' )}
                                                    >
                                                    { iconPosition7 && iconPosition7 == 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon7} ${iconPosition7}`}></i>
                                                    }
                                                    { iconPosition7 && iconPosition7 != 'af-icon-is-left' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText.Content value={spBtnText7} />
                                                    { iconPosition7 && iconPosition7 == 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon ${buttonIcon7} ${iconPosition7}`}></i>
                                                    }
                                                    { iconPosition7 && iconPosition7 != 'af-icon-is-right' &&
                                                        <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </a>
                                            </div>
                                        </div> 
                                    }


                                    { amazonpriceposition7 && amazonpriceposition7 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol7-amzon-price below`}>{amazonprice7}</div>
                                      </Fragment>
                                      }

                                    { amazonratingposition7 && amazonratingposition7 == "Below Button" &&
                                      <Fragment>
                                        <div className={`affiliate-productcol7-amzon-rating below`}>{amazonrating7}</div>
                                      </Fragment>
                                      }  

                                    { showbottomText && showbottomText == true &&
                                        <RichText.Content
                                          tagName="div"
                                          className="affiliate-coupon-bottomtext7"
                                          value={ bottomText7 }
                                        />
                                    }

                                </li>
                            }
                        </Fragment>


                    }

                </ul>
            </div>
        );
    }
}

export default Save;