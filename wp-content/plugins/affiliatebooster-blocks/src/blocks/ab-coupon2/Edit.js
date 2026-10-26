import RadioAdvanced from '../../components/fields/RadioAdvanced.js';
import BorderTypes from '../../components/fields/BorderTypes.js';
import Alignment from '../../components/fields/Alignment.js';
import Dimension from '../../components/fields/Dimension/Dimension.js';
import IconSelector from '../../components/fields/IconSelector/IconSelector.js';
import TypographyControl from '../components/typography/index.js';
import WebfontLoader from "../components/typography/fontloader"
import Range from '../../components/fields/Range/Range.js';
import Background  from './../../components/fields/Background/Background.js';
import Color  from '../../components/fields/Color/Color.js';
import affiliate_styling  from './styling.js';
import IconList from '../../components/fields/IconList/IconList.js';
import BoxShadow  from './../../components/fields/BoxShadow.js';
import Tabs from '../../components/fields/Tabs/Tabs.js';
import Tab from '../../components/fields/Tab/Tab.js';
import ReviewImage1 from "./reviewImage1.js"
import ReviewImage2 from "./reviewImage2.js"
import ReviewImage3 from "./reviewImage3.js"
import ReviewImage4 from "./reviewImage4.js"
import ReviewImage5 from "./reviewImage5.js"
import ReviewImage6 from "./reviewImage6.js"
import ReviewImage7 from "./reviewImage7.js"

import Toggle from '../../components/fields/Toggle.js';
import { EmptyStar, FullStar, HalfStar } from "./icons";
import getIcon from '../../icons/get-icon';
import RichList from '../../components/rich-list';

const { __ } = wp.i18n;
const {
	withSelect,
	useDispatch,
	withDispatch
} = wp.data
const {
	compose,
} = wp.compose
const {
    InspectorControls,
    BlockControls,
    MediaUpload,
    URLInput,
    URLInputButton,
    RichText,
    InnerB,
    InnerBlocks
} = wp.blockEditor;
const {
    Component,
    Fragment
} = wp.element;
const {
    PanelBody,
    Toolbar,
    Tooltip,
    TextControl,
    TextareaControl,
    ToggleControl,
    TabPanel,
    Dashicon,
    Button,
    IconButton,
    Popover,
    RangeControl,
    Dropdown,
    SelectControl,
    withNotices
} = wp.components;

let imageSize1Options = [
	{ value: "thumbnail", label: __( "Thumbnail" ) },
	{ value: "medium", label: __( "Medium" ) },
	{ value: "full", label: __( "Large" ) }
]
let imageSize2Options = [
    { value: "thumbnail", label: __( "Thumbnail" ) },
    { value: "medium", label: __( "Medium" ) },
    { value: "full", label: __( "Large" ) }
]
let imageSize3Options = [
    { value: "thumbnail", label: __( "Thumbnail" ) },
    { value: "medium", label: __( "Medium" ) },
    { value: "full", label: __( "Large" ) }
]
let imageSize4Options = [
    { value: "thumbnail", label: __( "Thumbnail" ) },
    { value: "medium", label: __( "Medium" ) },
    { value: "full", label: __( "Large" ) }
]
let imageSize5Options = [
    { value: "thumbnail", label: __( "Thumbnail" ) },
    { value: "medium", label: __( "Medium" ) },
    { value: "full", label: __( "Large" ) }
]
let imageSize6Options = [
    { value: "thumbnail", label: __( "Thumbnail" ) },
    { value: "medium", label: __( "Medium" ) },
    { value: "full", label: __( "Large" ) }
]
let imageSize7Options = [
    { value: "thumbnail", label: __( "Thumbnail" ) },
    { value: "medium", label: __( "Medium" ) },
    { value: "full", label: __( "Large" ) }
]

$ = jQuery;
const NEW_TAB_REL = 'noopener noreferrer';
class Edit extends Component {
    constructor(props) {
        super(props)
        this.state = {
            removeItemViaBackSpace1: 999,
            isVisible1:false,
            focusedItem1: this.props.attributes.listItems1.length - 1,
            highlightedStars1 : 0,
            removeItemViaBackSpace2: 999,
            isVisible2:false,
            focusedItem2: this.props.attributes.listItems2.length - 1,
            highlightedStars2 : 0,
            removeItemViaBackSpace3: 999,
            isVisible3:false,
            focusedItem3: this.props.attributes.listItems3.length - 1,
            highlightedStars3 : 0,
            removeItemViaBackSpace4: 999,
            isVisible4:false,
            focusedItem4: this.props.attributes.listItems4.length - 1,
            highlightedStars4 : 0,
            removeItemViaBackSpace5: 999,
            isVisible5:false,
            focusedItem5: this.props.attributes.listItems5.length - 1,
            highlightedStars5 : 0,
            removeItemViaBackSpace6: 999,
            isVisible6:false,
            focusedItem6: this.props.attributes.listItems6.length - 1,
            highlightedStars6 : 0,
            removeItemViaBackSpace7: 999,
            isVisible7:false,
            focusedItem7: this.props.attributes.listItems7.length - 1,
            highlightedStars7 : 0,
            
        }

        this.onSelectImage1    = this.onSelectImage1.bind(this)
        this.getImageSize1  	  = this.getImageSize1.bind(this)
        this.onSelectImage2    = this.onSelectImage2.bind(this)
        this.getImageSize2        = this.getImageSize2.bind(this)
        this.onSelectImage3    = this.onSelectImage3.bind(this)
        this.getImageSize3        = this.getImageSize3.bind(this)
        this.onSelectImage4    = this.onSelectImage4.bind(this)
        this.getImageSize4        = this.getImageSize4.bind(this)
        this.onSelectImage5    = this.onSelectImage5.bind(this)
        this.getImageSize5        = this.getImageSize5.bind(this)
        this.onSelectImage6    = this.onSelectImage6.bind(this)
        this.getImageSize6        = this.getImageSize6.bind(this)
        this.onSelectImage7    = this.onSelectImage7.bind(this)
        this.getImageSize7        = this.getImageSize7.bind(this)
        
    }

      /*
	 * Event to set Image as while adding.
	 */
	onSelectImage1( media ) {
		const { image1 } = this.props.attributes
		const { setAttributes } = this.props
		if ( ! media || ! media.url ) {
			setAttributes( { image1: null } )
			return
		}
		if ( ! media.type || "image" !== media.type ) {
			setAttributes( { image1: null } )
			return
		}
		setAttributes( { image1: media } )
	}
	getImageSize1( sizes ) {
		var size_arr = []
		$.each(sizes, function (index, item) {
		  var name = index
		  	var p = { "value" : name, "label": name }
		  	size_arr.push(p)
		})
		return(size_arr)
	}


    onSelectImage2( media ) {
        const { image2 } = this.props.attributes
        const { setAttributes } = this.props
        if ( ! media || ! media.url ) {
            setAttributes( { image2: null } )
            return
        }
        if ( ! media.type || "image" !== media.type ) {
            setAttributes( { image2: null } )
            return
        }
        setAttributes( { image2: media } )
    }
    getImageSize2( sizes ) {
        var size_arr = []
        $.each(sizes, function (index, item) {
          var name = index
            var p = { "value" : name, "label": name }
            size_arr.push(p)
        })
        return(size_arr)
    }


    onSelectImage3( media ) {
        const { image3 } = this.props.attributes
        const { setAttributes } = this.props
        if ( ! media || ! media.url ) {
            setAttributes( { image3: null } )
            return
        }
        if ( ! media.type || "image" !== media.type ) {
            setAttributes( { image3: null } )
            return
        }
        setAttributes( { image3: media } )
    }
    getImageSize3( sizes ) {
        var size_arr = []
        $.each(sizes, function (index, item) {
          var name = index
            var p = { "value" : name, "label": name }
            size_arr.push(p)
        })
        return(size_arr)
    }


    onSelectImage4( media ) {
        const { image4 } = this.props.attributes
        const { setAttributes } = this.props
        if ( ! media || ! media.url ) {
            setAttributes( { image4: null } )
            return
        }
        if ( ! media.type || "image" !== media.type ) {
            setAttributes( { image4: null } )
            return
        }
        setAttributes( { image4: media } )
    }
    getImageSize4( sizes ) {
        var size_arr = []
        $.each(sizes, function (index, item) {
          var name = index
            var p = { "value" : name, "label": name }
            size_arr.push(p)
        })
        return(size_arr)
    }


    onSelectImage5( media ) {
        const { image5 } = this.props.attributes
        const { setAttributes } = this.props
        if ( ! media || ! media.url ) {
            setAttributes( { image5: null } )
            return
        }
        if ( ! media.type || "image" !== media.type ) {
            setAttributes( { image5: null } )
            return
        }
        setAttributes( { image5: media } )
    }
    getImageSize5( sizes ) {
        var size_arr = []
        $.each(sizes, function (index, item) {
          var name = index
            var p = { "value" : name, "label": name }
            size_arr.push(p)
        })
        return(size_arr)
    }


    onSelectImage6( media ) {
        const { image6 } = this.props.attributes
        const { setAttributes } = this.props
        if ( ! media || ! media.url ) {
            setAttributes( { image6: null } )
            return
        }
        if ( ! media.type || "image" !== media.type ) {
            setAttributes( { image6: null } )
            return
        }
        setAttributes( { image6: media } )
    }
    getImageSize6( sizes ) {
        var size_arr = []
        $.each(sizes, function (index, item) {
          var name = index
            var p = { "value" : name, "label": name }
            size_arr.push(p)
        })
        return(size_arr)
    }


    onSelectImage7( media ) {
        const { image7 } = this.props.attributes
        const { setAttributes } = this.props
        if ( ! media || ! media.url ) {
            setAttributes( { image7: null } )
            return
        }
        if ( ! media.type || "image" !== media.type ) {
            setAttributes( { image7: null } )
            return
        }
        setAttributes( { image7: media } )
    }
    getImageSize7( sizes ) {
        var size_arr = []
        $.each(sizes, function (index, item) {
          var name = index
            var p = { "value" : name, "label": name }
            size_arr.push(p)
        })
        return(size_arr)
    }


    componentDidMount() {
        const {
            clientId,
            isSelected,
            setAttributes,
            attributes: {
                uniqueId,
                block_id
            }
        } = this.props;
        const _client = clientId.substr(0, 6);

        if (!uniqueId) {
            setAttributes({ uniqueId: _client });
        } else if (uniqueId && uniqueId != _client) {
            setAttributes({ uniqueId: _client });
        }
        this.props.setAttributes({ block_id: this.props.clientId })
        const $style = document.createElement("style")
        $style.setAttribute("id", "affiliate-style-" + this.props.clientId)
        document.head.appendChild($style)
    
    }

      /*
      * Event to set Image as null while removing.
      */
      onRemoveImage1() {
        const { image1 } = this.props.attributes
        const { setAttributes } = this.props
        setAttributes( { image: null } )
      }
      removeImage1( url ){
        const { multiple, onChangem,setAttributes } = this.props
        const { image1 } = this.props.attributes
          setAttributes( { image1: {} } )
      }

      onRemoveImage2() {
        const { image2 } = this.props.attributes
        const { setAttributes } = this.props
  
        setAttributes( { image: null } )
      }
      removeImage2( url ){
        const { multiple, onChangem,setAttributes } = this.props
        const { image2 } = this.props.attributes
          setAttributes( { image2: {} } )
      }

      onRemoveImage3() {
        const { image3 } = this.props.attributes
        const { setAttributes } = this.props
        setAttributes( { image: null } )
      }
      removeImage3( url ){
        const { multiple, onChangem,setAttributes } = this.props
        const { image3 } = this.props.attributes
          setAttributes( { image3: {} } )
      }

      onRemoveImage4() {
        const { image4 } = this.props.attributes
        const { setAttributes } = this.props
        setAttributes( { image: null } )
      }
      removeImage4( url ){
        const { multiple, onChangem,setAttributes } = this.props
        const { image4 } = this.props.attributes
          setAttributes( { image4: {} } )
      }

      onRemoveImage5() {
        const { image5 } = this.props.attributes
        const { setAttributes } = this.props
        setAttributes( { image: null } )
      }
      removeImage5( url ){
        const { multiple, onChangem,setAttributes } = this.props
        const { image5 } = this.props.attributes
          setAttributes( { image5: {} } )
      }


      onRemoveImage6() {
        const { image6 } = this.props.attributes
        const { setAttributes } = this.props
        setAttributes( { image: null } )
      }
      removeImage6( url ){
        const { multiple, onChangem,setAttributes } = this.props
        const { image6 } = this.props.attributes
          setAttributes( { image6: {} } )
      }


      onRemoveImage7() {
        const { image7 } = this.props.attributes
        const { setAttributes } = this.props
        setAttributes( { image: null } )
      }
      removeImage7( url ){
        const { multiple, onChangem,setAttributes } = this.props
        const { image7 } = this.props.attributes
          setAttributes( { image7: {} } )
      }   
      
      isUrl(url){
        if( ['wbm','jpg','jpeg','gif','png', 'svg'].indexOf( url.split('.').pop().toLowerCase() ) != -1 ){
          return url;
        }
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

                boxPadding,
                boxPaddingMobile,
                boxPaddingTablet, 

                boxMargin,
                boxMarginMobile,
                boxMarginTablet,               

                //Box 1
                boxBgColor1,flagStyle1,ratings1,ratingsColor1,selectedStars1,starsSize1,starsSizeMobile1,starsSizeTablet1,titleTag1,titleText1,titleLoadGoogleFonts1,titleTextColor1,titlefontFamily1,titlefontWeight1,titlefontSubset1,titlefontSizeType1,titlefontSizeTypeMobile1,titlefontSizeTypeTablet1,titlefontSizeDesktop1,titlefontSizeMobile1,titlefontSizeTablet1,titlelineHeight1,titlelineHeightMobile1,titlelineHeightTablet1,titleletterSpacing1,titleletterSpacingMobile1,titleletterSpacingTablet1,titleletterSpacingType1,titleletterSpacingTypeTablet1,titleletterSpacingTypeMobile1,titletextTransform1,titletextDecoration1,titlefontStyle1,image1,imageSize1,imagePadding1,imagePaddingMobile1,imagePaddingTablet1,customImgWidth1,customImgWidthMobile1,customImgWidthTablet1,imageDesignType1,listItems1,listTextItems1,listType1,alignment1,listTextColor1,listLoadGoogleFonts1,listfontFamily1,listfontWeight1,listfontSubset1,listfontSizeType1,listfontSizeTypeMobile1,listfontSizeTypeTablet1,listfontSizeDesktop1,listfontSizeMobile1,listfontSizeTablet1,listlineHeight1,listlineHeightMobile1,listlineHeightTablet1,listletterSpacing1,listletterSpacingMobile1,listletterSpacingTablet1,listletterSpacingType1,listletterSpacingTypeTablet1,listletterSpacingTypeMobile1,listtextTransform1,listtextDecoration1,listfontStyle1,bulletStyle1,numberCorner1,numberFontSize1,numberBgSize1,useNumberBg1,listIconColor1,listColor1,recreateStyles1,btnLoadGoogleFonts1,btnfontFamily1,btnfontWeight1,btnfontSubset1,btnfontSizeType1,btnfontSizeTypeMobile1,btnfontSizeTypeTablet1,btnfontSizeDesktop1,btnfontSizeMobile1,btnfontSizeTablet1,btnlineHeight1,btnlineHeightMobile1,btnlineHeightTablet1,btnletterSpacing1,btnletterSpacingMobile1,btnletterSpacingTablet1,btnletterSpacingType1,btnletterSpacingTypeTablet1,btnletterSpacingTypeMobile1,btntextTransform1,btntextDecoration1,btnfontStyle1,btnBgColor1,btnHoverBgColor1,btnTextColor1,btnTextHoverColor1,btnBorderColor1,btnBorderHoverColor1,btn_Htarget1,spBtnText1,spBtnHref1,btnPadding1,btnPaddingMobile1,btnPaddingTablet1,btnMargin1,btnMarginMobile1,btnMarginTablet1,btnAlignment1,btnAlignmentMobile1,btnAlignmentTablet1,btnBorder1,btnBorderType1,btnBorderMobile1,btnBorderTablet1,btnBorderRadius1,btnBorderRadiusMobile1,btnBorderRadiusTablet1,iconPosition1,btnSize1,buttonIcon1,buttonWidth1,bntRounded1,btnRel1,          

                //Box 2
                boxBgColor2,flagStyle2,ratings2,ratingsColor2,selectedStars2, starsSize2,starsSizeMobile2,starsSizeTablet2,titleTag2,titleText2,titleLoadGoogleFonts2,titleTextColor2,titlefontFamily2,titlefontWeight2,titlefontSubset2,titlefontSizeType2,titlefontSizeTypeMobile2,titlefontSizeTypeTablet2,titlefontSizeDesktop2,titlefontSizeMobile2,titlefontSizeTablet2,titlelineHeight2,titlelineHeightMobile2,titlelineHeightTablet2,titleletterSpacing2,titleletterSpacingMobile2,titleletterSpacingTablet2,titleletterSpacingType2,titleletterSpacingTypeTablet2,titleletterSpacingTypeMobile2,titletextTransform2,titletextDecoration2,titlefontStyle2,image2,imageSize2,imagePadding2,imagePaddingMobile2,imagePaddingTablet2,customImgWidth2,customImgWidthMobile2,customImgWidthTablet2,imageDesignType2,listItems2,listTextItems2,listType2,alignment2,listTextColor2,listLoadGoogleFonts2,listfontFamily2,listfontWeight2,listfontSubset2,listfontSizeType2,listfontSizeTypeMobile2,listfontSizeTypeTablet2,listfontSizeDesktop2,listfontSizeMobile2,listfontSizeTablet2,listlineHeight2,listlineHeightMobile2,listlineHeightTablet2,listletterSpacing2,listletterSpacingMobile2,listletterSpacingTablet2,listletterSpacingType2,listletterSpacingTypeTablet2,listletterSpacingTypeMobile2,listtextTransform2,listtextDecoration2,listfontStyle2,bulletStyle2,numberCorner2,numberFontSize2,numberBgSize2,useNumberBg2,listIconColor2,listColor2,recreateStyles2,btnLoadGoogleFonts2,btnfontFamily2,btnfontWeight2,btnfontSubset2,btnfontSizeType2,btnfontSizeTypeMobile2,btnfontSizeTypeTablet2,btnfontSizeDesktop2,btnfontSizeMobile2,btnfontSizeTablet2,btnlineHeight2,btnlineHeightMobile2,btnlineHeightTablet2,btnletterSpacing2,btnletterSpacingMobile2,btnletterSpacingTablet2,btnletterSpacingType2,btnletterSpacingTypeTablet2,btnletterSpacingTypeMobile2,btntextTransform2,btntextDecoration2,btnfontStyle2,btnBgColor2,btnHoverBgColor2,btnTextColor2,btnTextHoverColor2,btnBorderColor2,btnBorderHoverColor2,btn_Htarget2,spBtnText2,spBtnHref2,btnPadding2,btnPaddingMobile2,btnPaddingTablet2,btnMargin2,btnMarginMobile2,btnMarginTablet2,btnAlignment2,btnAlignmentMobile2,btnAlignmentTablet2,btnBorder2,btnBorderType2,btnBorderMobile2,btnBorderTablet2,btnBorderRadius2,btnBorderRadiusMobile2,btnBorderRadiusTablet2,iconPosition2,btnSize2,buttonIcon2,buttonWidth2,bntRounded2,btnRel2,          


                //Box 3
                boxBgColor3,flagStyle3,ratings3,ratingsColor3,selectedStars3,starsSize3,starsSizeMobile3,starsSizeTablet3,titleTag3,titleText3,titleLoadGoogleFonts3,titleTextColor3,titlefontFamily3,titlefontWeight3,titlefontSubset3,titlefontSizeType3,titlefontSizeTypeMobile3,titlefontSizeTypeTablet3,titlefontSizeDesktop3,titlefontSizeMobile3,titlefontSizeTablet3,titlelineHeight3,titlelineHeightMobile3,titlelineHeightTablet3,titleletterSpacing3,titleletterSpacingMobile3,titleletterSpacingTablet3,titleletterSpacingType3,titleletterSpacingTypeTablet3,titleletterSpacingTypeMobile3,titletextTransform3,titletextDecoration3,titlefontStyle3,image3,imageSize3,imagePadding3,imagePaddingMobile3,imagePaddingTablet3,customImgWidth3,customImgWidthMobile3,customImgWidthTablet3,imageDesignType3,listItems3,listTextItems3,listType3,alignment3,listTextColor3,listLoadGoogleFonts3,listfontFamily3,listfontWeight3,listfontSubset3,listfontSizeType3,listfontSizeTypeMobile3,listfontSizeTypeTablet3,listfontSizeDesktop3,listfontSizeMobile3,listfontSizeTablet3,listlineHeight3,listlineHeightMobile3,listlineHeightTablet3,listletterSpacing3,listletterSpacingMobile3,listletterSpacingTablet3,listletterSpacingType3,listletterSpacingTypeTablet3,listletterSpacingTypeMobile3,listtextTransform3,listtextDecoration3,listfontStyle3,bulletStyle3,numberCorner3,numberFontSize3,numberBgSize3,useNumberBg3,listIconColor3,listColor3,recreateStyles3,btnLoadGoogleFonts3,btnfontFamily3,btnfontWeight3,btnfontSubset3,btnfontSizeType3,btnfontSizeTypeMobile3,btnfontSizeTypeTablet3,btnfontSizeDesktop3,btnfontSizeMobile3,btnfontSizeTablet3,btnlineHeight3,btnlineHeightMobile3,btnlineHeightTablet3,btnletterSpacing3,btnletterSpacingMobile3,btnletterSpacingTablet3,btnletterSpacingType3,btnletterSpacingTypeTablet3,btnletterSpacingTypeMobile3,btntextTransform3,btntextDecoration3,btnfontStyle3,btnBgColor3,btnHoverBgColor3,btnTextColor3,btnTextHoverColor3,btnBorderColor3,btnBorderHoverColor3,btn_Htarget3,spBtnText3,spBtnHref3,btnPadding3,btnPaddingMobile3,btnPaddingTablet3,btnMargin3,btnMarginMobile3,btnMarginTablet3,btnAlignment3,btnAlignmentMobile3,btnAlignmentTablet3,btnBorder3,btnBorderType3,btnBorderMobile3,btnBorderTablet3,btnBorderRadius3,btnBorderRadiusMobile3,btnBorderRadiusTablet3,iconPosition3,btnSize3,buttonIcon3,buttonWidth3,bntRounded3,btnRel3,    


                //Box 4
                boxBgColor4,flagStyle4,ratings4,ratingsColor4,selectedStars4,starsSize4,starsSizeMobile4,starsSizeTablet4,titleTag4,titleText4,titleLoadGoogleFonts4,titleTextColor4,titlefontFamily4,titlefontWeight4,titlefontSubset4,titlefontSizeType4,titlefontSizeTypeMobile4,titlefontSizeTypeTablet4,titlefontSizeDesktop4,titlefontSizeMobile4,titlefontSizeTablet4,titlelineHeight4,titlelineHeightMobile4,titlelineHeightTablet4,titleletterSpacing4,titleletterSpacingMobile4,titleletterSpacingTablet4,titleletterSpacingType4,titleletterSpacingTypeTablet4,titleletterSpacingTypeMobile4,titletextTransform4,titletextDecoration4,titlefontStyle4,image4,imageSize4,imagePadding4,imagePaddingMobile4,imagePaddingTablet4,customImgWidth4,customImgWidthMobile4,customImgWidthTablet4,imageDesignType4,listItems4,listTextItems4,listType4,alignment4,listTextColor4,listLoadGoogleFonts4,listfontFamily4,listfontWeight4,listfontSubset4,listfontSizeType4,listfontSizeTypeMobile4,listfontSizeTypeTablet4,listfontSizeDesktop4,listfontSizeMobile4,listfontSizeTablet4,listlineHeight4,listlineHeightMobile4,listlineHeightTablet4,listletterSpacing4,listletterSpacingMobile4,listletterSpacingTablet4,listletterSpacingType4,listletterSpacingTypeTablet4,listletterSpacingTypeMobile4,listtextTransform4,listtextDecoration4,listfontStyle4,bulletStyle4,numberCorner4,numberFontSize4,numberBgSize4,useNumberBg4,listIconColor4,listColor4,recreateStyles4,btnLoadGoogleFonts4,btnfontFamily4,btnfontWeight4,btnfontSubset4,btnfontSizeType4,btnfontSizeTypeMobile4,btnfontSizeTypeTablet4,btnfontSizeDesktop4,btnfontSizeMobile4,btnfontSizeTablet4,btnlineHeight4,btnlineHeightMobile4,btnlineHeightTablet4,btnletterSpacing4,btnletterSpacingMobile4,btnletterSpacingTablet4,btnletterSpacingType4,btnletterSpacingTypeTablet4,btnletterSpacingTypeMobile4,btntextTransform4,btntextDecoration4,btnfontStyle4,btnBgColor4,btnHoverBgColor4,btnTextColor4,btnTextHoverColor4,btnBorderColor4,btnBorderHoverColor4,btn_Htarget4,spBtnText4,spBtnHref4,btnPadding4,btnPaddingMobile4,btnPaddingTablet4,btnMargin4,btnMarginMobile4,btnMarginTablet4,btnAlignment4,btnAlignmentMobile4,btnAlignmentTablet4,btnBorder4,btnBorderType4,btnBorderMobile4,btnBorderTablet4,btnBorderRadius4,btnBorderRadiusMobile4,btnBorderRadiusTablet4,iconPosition4,btnSize4,buttonIcon4,buttonWidth4,bntRounded4,btnRel4, 


                //Box 5
                boxBgColor5,flagStyle5,ratings5,ratingsColor5,selectedStars5,starsSize5,starsSizeMobile5,starsSizeTablet5,titleTag5,titleText5,titleLoadGoogleFonts5,titleTextColor5,titlefontFamily5,titlefontWeight5,titlefontSubset5,titlefontSizeType5,titlefontSizeTypeMobile5,titlefontSizeTypeTablet5,titlefontSizeDesktop5,titlefontSizeMobile5,titlefontSizeTablet5,titlelineHeight5,titlelineHeightMobile5,titlelineHeightTablet5,titleletterSpacing5,titleletterSpacingMobile5,titleletterSpacingTablet5,titleletterSpacingType5,titleletterSpacingTypeTablet5,titleletterSpacingTypeMobile5,titletextTransform5,titletextDecoration5,titlefontStyle5,image5,imageSize5,imagePadding5,imagePaddingMobile5,imagePaddingTablet5,customImgWidth5,customImgWidthMobile5,customImgWidthTablet5,imageDesignType5,listItems5,listTextItems5,listType5,alignment5,listTextColor5,listLoadGoogleFonts5,listfontFamily5,listfontWeight5,listfontSubset5,listfontSizeType5,listfontSizeTypeMobile5,listfontSizeTypeTablet5,listfontSizeDesktop5,listfontSizeMobile5,listfontSizeTablet5,listlineHeight5,listlineHeightMobile5,listlineHeightTablet5,listletterSpacing5,listletterSpacingMobile5,listletterSpacingTablet5,listletterSpacingType5,listletterSpacingTypeTablet5,listletterSpacingTypeMobile5,listtextTransform5,listtextDecoration5,listfontStyle5,bulletStyle5,numberCorner5,numberFontSize5,numberBgSize5,useNumberBg5,listIconColor5,listColor5,recreateStyles5,btnLoadGoogleFonts5,btnfontFamily5,btnfontWeight5,btnfontSubset5,btnfontSizeType5,btnfontSizeTypeMobile5,btnfontSizeTypeTablet5,btnfontSizeDesktop5,btnfontSizeMobile5,btnfontSizeTablet5,btnlineHeight5,btnlineHeightMobile5,btnlineHeightTablet5,btnletterSpacing5,btnletterSpacingMobile5,btnletterSpacingTablet5,btnletterSpacingType5,btnletterSpacingTypeTablet5,btnletterSpacingTypeMobile5,btntextTransform5,btntextDecoration5,btnfontStyle5,btnBgColor5,btnHoverBgColor5,btnTextColor5,btnTextHoverColor5,btnBorderColor5,btnBorderHoverColor5,btn_Htarget5,spBtnText5,spBtnHref5,btnPadding5,btnPaddingMobile5,btnPaddingTablet5,btnMargin5,btnMarginMobile5,btnMarginTablet5,btnAlignment5,btnAlignmentMobile5,btnAlignmentTablet5,btnBorder5,btnBorderType5,btnBorderMobile5,btnBorderTablet5,btnBorderRadius5,btnBorderRadiusMobile5,btnBorderRadiusTablet5,iconPosition5,btnSize5,buttonIcon5,buttonWidth5,bntRounded5,btnRel5,


                //Box 6
                boxBgColor6,flagStyle6,ratings6,ratingsColor6,selectedStars6,starsSize6,starsSizeMobile6,starsSizeTablet6,titleTag6,titleText6,titleLoadGoogleFonts6,titleTextColor6,titlefontFamily6,titlefontWeight6,titlefontSubset6,titlefontSizeType6,titlefontSizeTypeMobile6,titlefontSizeTypeTablet6,titlefontSizeDesktop6,titlefontSizeMobile6,titlefontSizeTablet6,titlelineHeight6,titlelineHeightMobile6,titlelineHeightTablet6,titleletterSpacing6,titleletterSpacingMobile6,titleletterSpacingTablet6,titleletterSpacingType6,titleletterSpacingTypeTablet6,titleletterSpacingTypeMobile6,titletextTransform6,titletextDecoration6,titlefontStyle6,image6,imageSize6,imagePadding6,imagePaddingMobile6,imagePaddingTablet6,customImgWidth6,customImgWidthMobile6,customImgWidthTablet6,imageDesignType6,listItems6,listTextItems6,listType6,alignment6,listTextColor6,listLoadGoogleFonts6,listfontFamily6,listfontWeight6,listfontSubset6,listfontSizeType6,listfontSizeTypeMobile6,listfontSizeTypeTablet6,listfontSizeDesktop6,listfontSizeMobile6,listfontSizeTablet6,listlineHeight6,listlineHeightMobile6,listlineHeightTablet6,listletterSpacing6,listletterSpacingMobile6,listletterSpacingTablet6,listletterSpacingType6,listletterSpacingTypeTablet6,listletterSpacingTypeMobile6,listtextTransform6,listtextDecoration6,listfontStyle6,bulletStyle6,numberCorner6,numberFontSize6,numberBgSize6,useNumberBg6,listIconColor6,listColor6,recreateStyles6,btnLoadGoogleFonts6,btnfontFamily6,btnfontWeight6,btnfontSubset6,btnfontSizeType6,btnfontSizeTypeMobile6,btnfontSizeTypeTablet6,btnfontSizeDesktop6,btnfontSizeMobile6,btnfontSizeTablet6,btnlineHeight6,btnlineHeightMobile6,btnlineHeightTablet6,btnletterSpacing6,btnletterSpacingMobile6,btnletterSpacingTablet6,btnletterSpacingType6,btnletterSpacingTypeTablet6,btnletterSpacingTypeMobile6,btntextTransform6,btntextDecoration6,btnfontStyle6,btnBgColor6,btnHoverBgColor6,btnTextColor6,btnTextHoverColor6,btnBorderColor6,btnBorderHoverColor6,btn_Htarget6,spBtnText6,spBtnHref6,btnPadding6,btnPaddingMobile6,btnPaddingTablet6,btnMargin6,btnMarginMobile6,btnMarginTablet6,btnAlignment6,btnAlignmentMobile6,btnAlignmentTablet6,btnBorder6,btnBorderType6,btnBorderMobile6,btnBorderTablet6,btnBorderRadius6,btnBorderRadiusMobile6,btnBorderRadiusTablet6,iconPosition6,btnSize6,buttonIcon6,buttonWidth6,bntRounded6,btnRel6,


                //Box 7
                boxBgColor7,flagStyle7,ratings7,ratingsColor7,selectedStars7,starsSize7,starsSizeMobile7,starsSizeTablet7,titleTag7,titleText7,titleLoadGoogleFonts7,titleTextColor7,titlefontFamily7,titlefontWeight7,titlefontSubset7,titlefontSizeType7,titlefontSizeTypeMobile7,titlefontSizeTypeTablet7,titlefontSizeDesktop7,titlefontSizeMobile7,titlefontSizeTablet7,titlelineHeight7,titlelineHeightMobile7,titlelineHeightTablet7,titleletterSpacing7,titleletterSpacingMobile7,titleletterSpacingTablet7,titleletterSpacingType7,titleletterSpacingTypeTablet7,titleletterSpacingTypeMobile7,titletextTransform7,titletextDecoration7,titlefontStyle7,image7,imageSize7,imagePadding7,imagePaddingMobile7,imagePaddingTablet7,customImgWidth7,customImgWidthMobile7,customImgWidthTablet7,imageDesignType7,listItems7,listTextItems7,listType7,alignment7,listTextColor7,listLoadGoogleFonts7,listfontFamily7,listfontWeight7,listfontSubset7,listfontSizeType7,listfontSizeTypeMobile7,listfontSizeTypeTablet7,listfontSizeDesktop7,listfontSizeMobile7,listfontSizeTablet7,listlineHeight7,listlineHeightMobile7,listlineHeightTablet7,listletterSpacing7,listletterSpacingMobile7,listletterSpacingTablet7,listletterSpacingType7,listletterSpacingTypeTablet7,listletterSpacingTypeMobile7,listtextTransform7,listtextDecoration7,listfontStyle7,bulletStyle7,numberCorner7,numberFontSize7,numberBgSize7,useNumberBg7,listIconColor7,listColor7,recreateStyles7,btnLoadGoogleFonts7,btnfontFamily7,btnfontWeight7,btnfontSubset7,btnfontSizeType7,btnfontSizeTypeMobile7,btnfontSizeTypeTablet7,btnfontSizeDesktop7,btnfontSizeMobile7,btnfontSizeTablet7,btnlineHeight7,btnlineHeightMobile7,btnlineHeightTablet7,btnletterSpacing7,btnletterSpacingMobile7,btnletterSpacingTablet7,btnletterSpacingType7,btnletterSpacingTypeTablet7,btnletterSpacingTypeMobile7,btntextTransform7,btntextDecoration7,btnfontStyle7,btnBgColor7,btnHoverBgColor7,btnTextColor7,btnTextHoverColor7,btnBorderColor7,btnBorderHoverColor7,btn_Htarget7,spBtnText7,spBtnHref7,btnPadding7,btnPaddingMobile7,btnPaddingTablet7,btnMargin7,btnMarginMobile7,btnMarginTablet7,btnAlignment7,btnAlignmentMobile7,btnAlignmentTablet7,btnBorder7,btnBorderType7,btnBorderMobile7,btnBorderTablet7,btnBorderRadius7,btnBorderRadiusMobile7,btnBorderRadiusTablet7,iconPosition7,btnSize7,buttonIcon7,buttonWidth7,bntRounded7,btnRel7,


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


                btnBg,
                btnBgHover,
                btnBg2,
                btnBgHover2,
                btnBg3,
                btnBgHover3,
                btnBg4,
                btnBgHover4,
                btnBg5,
                btnBgHover5,
                btnBg6,
                btnBgHover6,
                btnBg7,
                btnBgHover7,


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
        } = this.props;
        const { isVisible1,isVisible2,isVisible3,isVisible4,isVisible5,isVisible6,isVisible7} = this.state
        
        var element = document.getElementById("affiliate-style-" + this.props.clientId)
        if (null != element && "undefined" != typeof element) {
             element.innerHTML = affiliate_styling(this.props, "affiliate-style")
        }

        let loadtitleGoogleFonts1
        let loadlistGoogleFonts1
        let loadbtnGoogleFotns1
        let loadtopGoogleFotns1
        let loadbottomGoogleFotns1

        let loadtitleGoogleFonts2
        let loadlistGoogleFonts2
        let loadbtnGoogleFotns2
        let loadtopGoogleFotns2
        let loadbottomGoogleFotns2

        let loadtitleGoogleFonts3
        let loadlistGoogleFonts3
        let loadbtnGoogleFotns3
        let loadtopGoogleFotns3
        let loadbottomGoogleFotns3

        let loadtitleGoogleFonts4
        let loadlistGoogleFonts4
        let loadbtnGoogleFotns4
        let loadtopGoogleFotns4
        let loadbottomGoogleFotns4

        let loadtitleGoogleFonts5
        let loadlistGoogleFonts5
        let loadbtnGoogleFotns5
        let loadtopGoogleFotns5
        let loadbottomGoogleFotns5

        let loadtitleGoogleFonts6
        let loadlistGoogleFonts6
        let loadbtnGoogleFotns6
        let loadtopGoogleFotns6
        let loadbottomGoogleFotns6

        let loadtitleGoogleFonts7
        let loadlistGoogleFonts7
        let loadbtnGoogleFotns7
        let loadtopGoogleFotns7
        let loadbottomGoogleFotns7

        if( titleLoadGoogleFonts1 == true ) {			
			const titleconfig1 = {
				google: {
					families: [ titlefontFamily1 + ( titlefontWeight1 ? ":" + titlefontWeight1 : "" ) ],
				},
			}
			loadtitleGoogleFonts1 = (
				<WebfontLoader config={ titleconfig1 }>
				</WebfontLoader>
			)
        }
        if( listLoadGoogleFonts1 == true ) {    
            const listconfig1 = {
                google: {
                    families: [ listfontFamily1 + ( listfontWeight1 ? ":" + listfontWeight1 : "" ) ],
                },
            }
            loadlistGoogleFonts1 = (
                <WebfontLoader config={ listconfig1 }>
                </WebfontLoader>
            )
        }        
        if( btnLoadGoogleFonts1 == true ) {	
			const btnconfig1 = {
				google: {
					families: [ btnfontFamily1 + ( btnfontWeight1 ? ":" + btnfontWeight1 : "" ) ],
				},
			}
			loadbtnGoogleFotns1 = (
				<WebfontLoader config={ btnconfig1 }>
				</WebfontLoader>
			)
        }

        if( topTextLoadGoogleFonts1 == true ) { 
            const toptextconfig1 = {
                google: {
                    families: [ topTextfontFamily1 + ( topTextfontWeight1 ? ":" + topTextfontWeight1 : "" ) ],
                },
            }
            loadtopGoogleFotns1 = (
                <WebfontLoader config={ toptextconfig1 }>
                </WebfontLoader>
            )
        }

        if( bottomTextLoadGoogleFonts1 == true ) { 
            const bottomtextconfig1 = {
                google: {
                    families: [ bottomTextfontFamily1 + ( bottomTextfontWeight1 ? ":" + bottomTextfontWeight1 : "" ) ],
                },
            }
            loadbottomGoogleFotns1 = (
                <WebfontLoader config={ bottomtextconfig1 }>
                </WebfontLoader>
            )
        }


        if( titleLoadGoogleFonts2 == true ) {  
            const titleconfig2 = {
                google: {
                    families: [ titlefontFamily2 + ( titlefontWeight2 ? ":" + titlefontWeight2 : "" ) ],
                },
            }
            loadtitleGoogleFonts2 = (
                <WebfontLoader config={ titleconfig2 }>
                </WebfontLoader>
            )
        }
        if( listLoadGoogleFonts2 == true ) {      
            const listconfig2 = {
                google: {
                    families: [ listfontFamily2 + ( listfontWeight2 ? ":" + listfontWeight2 : "" ) ],
                },
            }
            loadlistGoogleFonts2 = (
                <WebfontLoader config={ listconfig2 }>
                </WebfontLoader>
            )
        }        
        if( btnLoadGoogleFonts2 == true ) {         
            const btnconfig2 = {
                google: {
                    families: [ btnfontFamily2 + ( btnfontWeight2 ? ":" + btnfontWeight2 : "" ) ],
                },
            }
            loadbtnGoogleFotns2 = (
                <WebfontLoader config={ btnconfig2 }>
                </WebfontLoader>
            )
        }

        if( topTextLoadGoogleFonts2 == true ) { 
            const toptextconfig2 = {
                google: {
                    families: [ topTextfontFamily2 + ( topTextfontWeight2 ? ":" + topTextfontWeight2 : "" ) ],
                },
            }
            loadtopGoogleFotns2 = (
                <WebfontLoader config={ toptextconfig2 }>
                </WebfontLoader>
            )
        }

        if( bottomTextLoadGoogleFonts2 == true ) { 
            const bottomtextconfig2 = {
                google: {
                    families: [ bottomTextfontFamily2 + ( bottomTextfontWeight2 ? ":" + bottomTextfontWeight2 : "" ) ],
                },
            }
            loadbottomGoogleFotns2 = (
                <WebfontLoader config={ bottomtextconfig2 }>
                </WebfontLoader>
            )
        }


        if( titleLoadGoogleFonts3 == true ) {       
            const titleconfig3 = {
                google: {
                    families: [ titlefontFamily3 + ( titlefontWeight3 ? ":" + titlefontWeight3 : "" ) ],
                },
            }
            loadtitleGoogleFonts3 = (
                <WebfontLoader config={ titleconfig3 }>
                </WebfontLoader>
            )
        }
        if( listLoadGoogleFonts3 == true ) {      
            const listconfig3 = {
                google: {
                    families: [ listfontFamily3 + ( listfontWeight3 ? ":" + listfontWeight3 : "" ) ],
                },
            }
            loadlistGoogleFonts3 = (
                <WebfontLoader config={ listconfig3 }>
                </WebfontLoader>
            )
        }        
        if( btnLoadGoogleFonts3 == true ) {      
            const btnconfig3 = {
                google: {
                    families: [ btnfontFamily3 + ( btnfontWeight3 ? ":" + btnfontWeight3 : "" ) ],
                },
            }
            loadbtnGoogleFotns3 = (
                <WebfontLoader config={ btnconfig3 }>
                </WebfontLoader>
            )
        }
        if( topTextLoadGoogleFonts3 == true ) { 
            const toptextconfig3 = {
                google: {
                    families: [ topTextfontFamily3 + ( topTextfontWeight3 ? ":" + topTextfontWeight3 : "" ) ],
                },
            }
            loadtopGoogleFotns3 = (
                <WebfontLoader config={ toptextconfig3 }>
                </WebfontLoader>
            )
        }

        if( bottomTextLoadGoogleFonts3 == true ) { 
            const bottomtextconfig3 = {
                google: {
                    families: [ bottomTextfontFamily3 + ( bottomTextfontWeight3 ? ":" + bottomTextfontWeight3 : "" ) ],
                },
            }
            loadbottomGoogleFotns3 = (
                <WebfontLoader config={ bottomtextconfig3 }>
                </WebfontLoader>
            )
        }


        if( titleLoadGoogleFonts4 == true ) {     
            const titleconfig4 = {
                google: {
                    families: [ titlefontFamily4 + ( titlefontWeight4 ? ":" + titlefontWeight4 : "" ) ],
                },
            }
            loadtitleGoogleFonts4 = (
                <WebfontLoader config={ titleconfig4 }>
                </WebfontLoader>
            )
        }
        if( listLoadGoogleFonts4 == true ) {      
            const listconfig4 = {
                google: {
                    families: [ listfontFamily4 + ( listfontWeight4 ? ":" + listfontWeight4 : "" ) ],
                },
            }
            loadlistGoogleFonts4 = (
                <WebfontLoader config={ listconfig4 }>
                </WebfontLoader>
            )
        }        
        if( btnLoadGoogleFonts4 == true ) {      
            const btnconfig4 = {
                google: {
                    families: [ btnfontFamily4 + ( btnfontWeight4 ? ":" + btnfontWeight4 : "" ) ],
                },
            }
            loadbtnGoogleFotns4 = (
                <WebfontLoader config={ btnconfig4 }>
                </WebfontLoader>
            )
        }
        if( topTextLoadGoogleFonts4 == true ) { 
            const toptextconfig4 = {
                google: {
                    families: [ topTextfontFamily4 + ( topTextfontWeight4 ? ":" + topTextfontWeight4 : "" ) ],
                },
            }
            loadtopGoogleFotns4 = (
                <WebfontLoader config={ toptextconfig4 }>
                </WebfontLoader>
            )
        }

        if( bottomTextLoadGoogleFonts4 == true ) { 
            const bottomtextconfig4 = {
                google: {
                    families: [ bottomTextfontFamily4 + ( bottomTextfontWeight4 ? ":" + bottomTextfontWeight4 : "" ) ],
                },
            }
            loadbottomGoogleFotns4 = (
                <WebfontLoader config={ bottomtextconfig4 }>
                </WebfontLoader>
            )
        }

        if( titleLoadGoogleFonts5 == true ) {  
            const titleconfig5 = {
                google: {
                    families: [ titlefontFamily5 + ( titlefontWeight5 ? ":" + titlefontWeight5 : "" ) ],
                },
            }
            loadtitleGoogleFonts5 = (
                <WebfontLoader config={ titleconfig5 }>
                </WebfontLoader>
            )
        }

        if( listLoadGoogleFonts5 == true ) {   
            const listconfig5 = {
                google: {
                    families: [ listfontFamily5 + ( listfontWeight5 ? ":" + listfontWeight5 : "" ) ],
                },
            }
            loadlistGoogleFonts5 = (
                <WebfontLoader config={ listconfig5 }>
                </WebfontLoader>
            )
        }        
        if( btnLoadGoogleFonts5 == true ) {  
            const btnconfig5 = {
                google: {
                    families: [ btnfontFamily5 + ( btnfontWeight5 ? ":" + btnfontWeight5 : "" ) ],
                },
            }
            loadbtnGoogleFotns5 = (
                <WebfontLoader config={ btnconfig5 }>
                </WebfontLoader>
            )
        }
        if( topTextLoadGoogleFonts5 == true ) { 
            const toptextconfig5 = {
                google: {
                    families: [ topTextfontFamily5 + ( topTextfontWeight5 ? ":" + topTextfontWeight5 : "" ) ],
                },
            }
            loadtopGoogleFotns5 = (
                <WebfontLoader config={ toptextconfig5 }>
                </WebfontLoader>
            )
        }

        if( bottomTextLoadGoogleFonts5 == true ) { 
            const bottomtextconfig5 = {
                google: {
                    families: [ bottomTextfontFamily5 + ( bottomTextfontWeight5 ? ":" + bottomTextfontWeight5 : "" ) ],
                },
            }
            loadbottomGoogleFotns5 = (
                <WebfontLoader config={ bottomtextconfig5 }>
                </WebfontLoader>
            )
        }


        if( titleLoadGoogleFonts6 == true ) {   
            const titleconfig6 = {
                google: {
                    families: [ titlefontFamily6 + ( titlefontWeight6 ? ":" + titlefontWeight6 : "" ) ],
                },
            }
            loadtitleGoogleFonts6 = (
                <WebfontLoader config={ titleconfig6 }>
                </WebfontLoader>
            )
        }
        if( listLoadGoogleFonts6 == true ) {    
            const listconfig6 = {
                google: {
                    families: [ listfontFamily6 + ( listfontWeight6 ? ":" + listfontWeight6 : "" ) ],
                },
            }
            loadlistGoogleFonts6 = (
                <WebfontLoader config={ listconfig6 }>
                </WebfontLoader>
            )
        }        
        if( btnLoadGoogleFonts6 == true ) {        
            const btnconfig6 = {
                google: {
                    families: [ btnfontFamily6 + ( btnfontWeight6 ? ":" + btnfontWeight6 : "" ) ],
                },
            }
            loadbtnGoogleFotns6 = (
                <WebfontLoader config={ btnconfig6 }>
                </WebfontLoader>
            )
        }
        if( topTextLoadGoogleFonts6 == true ) { 
            const toptextconfig6 = {
                google: {
                    families: [ topTextfontFamily6 + ( topTextfontWeight6 ? ":" + topTextfontWeight6 : "" ) ],
                },
            }
            loadtopGoogleFotns6 = (
                <WebfontLoader config={ toptextconfig6 }>
                </WebfontLoader>
            )
        }

        if( bottomTextLoadGoogleFonts6 == true ) { 
            const bottomtextconfig6 = {
                google: {
                    families: [ bottomTextfontFamily6 + ( bottomTextfontWeight6 ? ":" + bottomTextfontWeight6 : "" ) ],
                },
            }
            loadbottomGoogleFotns6 = (
                <WebfontLoader config={ bottomtextconfig6 }>
                </WebfontLoader>
            )
        }


        if( titleLoadGoogleFonts7 == true ) {      
            const titleconfig7 = {
                google: {
                    families: [ titlefontFamily7 + ( titlefontWeight7 ? ":" + titlefontWeight7 : "" ) ],
                },
            }
            loadtitleGoogleFonts7 = (
                <WebfontLoader config={ titleconfig7 }>
                </WebfontLoader>
            )
        }
        if( listLoadGoogleFonts7 == true ) {    
            const listconfig7 = {
                google: {
                    families: [ listfontFamily7 + ( listfontWeight7 ? ":" + listfontWeight7 : "" ) ],
                },
            }
            loadlistGoogleFonts7 = (
                <WebfontLoader config={ listconfig7 }>
                </WebfontLoader>
            )
        }        
        if( btnLoadGoogleFonts7 == true ) {       
            const btnconfig7 = {
                google: {
                    families: [ btnfontFamily7 + ( btnfontWeight7 ? ":" + btnfontWeight7 : "" ) ],
                },
            }
            loadbtnGoogleFotns7 = (
                <WebfontLoader config={ btnconfig7 }>
                </WebfontLoader>
            )
        }
        if( topTextLoadGoogleFonts7 == true ) { 
            const toptextconfig7 = {
                google: {
                    families: [ topTextfontFamily7 + ( topTextfontWeight7 ? ":" + topTextfontWeight7 : "" ) ],
                },
            }
            loadtopGoogleFotns7 = (
                <WebfontLoader config={ toptextconfig7 }>
                </WebfontLoader>
            )
        }

        if( bottomTextLoadGoogleFonts7 == true ) { 
            const bottomtextconfig7 = {
                google: {
                    families: [ bottomTextfontFamily7 + ( bottomTextfontWeight7 ? ":" + bottomTextfontWeight7 : "" ) ],
                },
            }
            loadbottomGoogleFotns7 = (
                <WebfontLoader config={ bottomtextconfig7 }>
                </WebfontLoader>
            )
        }

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
            <Fragment>
                <InspectorControls key="inspector">
                <PanelBody title={`General Settings`} initialOpen={false}  icon={ getIcon( 'documentation' ) } className={ 'abblocks-panel-label' }>

                    <SelectControl
                        label={ __( 'Product Column Layout' ) }
                        value={ productColumnStyle }
                        options= {[
                                { value: 'layout1', label: 'Layout 1' },
                                { value: 'layout2', label: 'Layout 2' },
                            ]}
                            onChange={ ( value ) => setAttributes( { productColumnStyle: value } ) }
                    />    


                    { productColumnStyle && productColumnStyle == 'layout2' &&

                        <ToggleControl
                            label={ __( 'Show Top Text' ) }                        
                            checked={ showtopText }                                      
                            onChange={ ( ) => setAttributes( { showtopText: ! showtopText } ) }
                        />

                    }
                    
                    <ToggleControl
                        label={ __( 'Show Title' ) }                        
                        checked={ showTitle }                                      
                        onChange={ ( ) => setAttributes( { showTitle: ! showTitle } ) }
                    />

                    { productColumnStyle && productColumnStyle == 'layout1' &&

                        <ToggleControl
                            label={ __( 'Show Rating' ) }                        
                            checked={ showRating }                                      
                            onChange={ ( ) => setAttributes( { showRating: ! showRating } ) }
                        />

                    }

                    <ToggleControl
                        label={ __( 'Show Content' ) }                        
                        checked={ showContent }                                      
                        onChange={ ( ) => setAttributes( { showContent: ! showContent } ) }
                    />

                    <ToggleControl
                        label={ __( 'Show Button' ) }                        
                        checked={ showButton }                                      
                        onChange={ ( ) => setAttributes( { showButton: ! showButton } ) }
                    />

                    { productColumnStyle && productColumnStyle == 'layout2' &&

                        <ToggleControl
                            label={ __( 'Show Bottom Text' ) }                        
                            checked={ showbottomText }                                      
                            onChange={ ( ) => setAttributes( { showbottomText: ! showbottomText } ) }
                        />

                    }

                    <RangeControl label={__("Number of block")} value={numberOfBlock} onChange={value => setAttributes({ numberOfBlock: value }) } min={1} step={1} max={7} />     
                    <BoxShadow label={__('Box-Shadow')} value={boxShadow} onChange={(value) => setAttributes({ boxShadow: value })} />   

                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                        activeClass="active-tab"
                        tabs={[
                          {
                            name: 'boxdesk',
                            title: <Dashicon icon="desktop" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxtablet',
                            title: <Dashicon icon="tablet" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxmobile',
                            title: <Dashicon icon="smartphone" />,
                            className: 'affiliate-responsive-tabs',
                          },
                        ]}>
                        {
                          (radiustab) => {
                            let tabout;
                            if (radiustab.name) {
                              if ('boxmobile' === radiustab.name) {
                                tabout = (
                                  <Dimension
                                    label={__('Align Full Width Padding')} value={boxPaddingMobile}
                                    onChange={val => setAttributes({ boxPaddingMobile: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                  <Dimension label={__('Align Full Width Padding')} value={boxPaddingTablet}
                                    onChange={val => setAttributes({ boxPaddingTablet: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else {
                                tabout = (
                                  <Dimension
                                    label={__('Align Full Width Padding')} value={boxPadding}
                                    onChange={val => setAttributes({ boxPadding: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              }
                            }
                            return <div>{tabout}</div>;
                          }
                        }
                    </TabPanel>   


                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                        activeClass="active-tab"
                        tabs={[
                          {
                            name: 'boxdesk',
                            title: <Dashicon icon="desktop" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxtablet',
                            title: <Dashicon icon="tablet" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxmobile',
                            title: <Dashicon icon="smartphone" />,
                            className: 'affiliate-responsive-tabs',
                          },
                        ]}>
                        {
                          (radiustab) => {
                            let tabout;
                            if (radiustab.name) {
                              if ('boxmobile' === radiustab.name) {
                                tabout = (
                                  <Dimension
                                    label={__('Align Wide Width Margin')} value={boxMarginMobile}
                                    onChange={val => setAttributes({ boxMarginMobile: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                  <Dimension label={__('Align Wide Width Margin')} value={boxMarginTablet}
                                    onChange={val => setAttributes({ boxMarginTablet: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else {
                                tabout = (
                                  <Dimension
                                    label={__('Align Wide Width Margin')} value={boxMargin}
                                    onChange={val => setAttributes({ boxMargin: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              }
                            }
                            return <div>{tabout}</div>;
                          }
                        }
                    </TabPanel> 

                </PanelBody>
 
                { numberOfBlock && numberOfBlock >= '1' &&
                <PanelBody title={`Box 1 Settings`} initialOpen={false} icon={ getIcon( 'layout' ) } className={ 'abblocks-panel-label' }>

                    <ToggleControl
                          label={ __( 'External Image?' ) }                        
                          checked={ externalImage1 }                                      
                          onChange={ ( ) => setAttributes( { externalImage1: ! externalImage1 } ) }
                      />

                    { externalImage1 && externalImage1 == true &&
                         <Fragment>
                      <TextareaControl              
                          label={ __( 'External Image Src' ) }         
                          value={ externalImageSrc1 }                       
                          onChange={ ( value ) => setAttributes( { externalImageSrc1: value } ) }
                      />
                      <TextControl              
                            label={ __( 'External Image Alt' ) }         
                            value={ externalImageAlt1 }                       
                            onChange={ ( value ) => setAttributes( { externalImageAlt1: value } ) }
                        />
                        </Fragment>
                    }


                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                          activeClass="active-tab"
                          tabs={[
                            {
                              name: 'boxdesk',
                              title: <Dashicon icon="desktop" />,
                              className: 'affiliate-responsive-tabs',
                            },
                            {
                              name: 'boxtablet',
                              title: <Dashicon icon="tablet" />,
                              className: 'affiliate-responsive-tabs',
                            },
                            {
                              name: 'boxmobile',
                              title: <Dashicon icon="smartphone" />,
                              className: 'affiliate-responsive-tabs',
                            },
                          ]}>
                          {
                            (radiustab) => {
                              let tabout;
                              if (radiustab.name) {
                                if ('boxmobile' === radiustab.name) {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidthMobile1} onChange={(value) => setAttributes({ customImgWidthMobile1: value })} min={0} max={1000} unit={['']} />
                                  );
                                } else if ('boxtablet' === radiustab.name) {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidthTablet1} onChange={(value) => setAttributes({ customImgWidthTablet1: value })} min={0} max={1000} unit={['']} />
                                  );
                                } else {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidth1} onChange={(value) => setAttributes({ customImgWidth1: value })} min={0} max={1000} unit={['']} />
                                  );
                                }
                              }
                              return <div>{tabout}</div>;
                            }
                          }
                    </TabPanel>
                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                        activeClass="active-tab"
                        tabs={[
                          {
                            name: 'boxdesk',
                            title: <Dashicon icon="desktop" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxtablet',
                            title: <Dashicon icon="tablet" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxmobile',
                            title: <Dashicon icon="smartphone" />,
                            className: 'affiliate-responsive-tabs',
                          },
                        ]}>
                        {
                          (radiustab) => {
                            let tabout;
                            if (radiustab.name) {
                              if ('boxmobile' === radiustab.name) {
                                tabout = (
                                  <Dimension
                                    label={__('Image Padding')} value={imagePaddingMobile1}
                                    onChange={val => setAttributes({ imagePaddingMobile1: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                  <Dimension label={__('Image Padding')} value={imagePaddingTablet1}
                                    onChange={val => setAttributes({ imagePaddingTablet1: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else {
                                tabout = (
                                  <Dimension
                                    label={__('Image Padding')} value={imagePadding1}
                                    onChange={val => setAttributes({ imagePadding1: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              }
                            }
                            return <div>{tabout}</div>;
                          }
                        }
                    </TabPanel>   


                    <Color label={__('Background Color')} disableAlpha value={boxBgColor1} onChange={val => setAttributes({ boxBgColor1: val })} />    

                    { productColumnStyle && productColumnStyle == 'layout1' &&
                        <SelectControl
                            label={ __( 'Flag Style' ) }
                            value={ flagStyle1 }
                            options= {[
                                    { value: '', label: 'Select' },
                                    { value: 'flag1', label: 'Budget Pick' },
                                    { value: 'flag2', label: 'Best Value' },
                                    { value: 'flag3', label: 'Premium Pick' },
                                ]}
                                onChange={ ( value ) => setAttributes( { flagStyle1: value } ) }
                        />    
                    }

                    <SelectControl
                        label={ __( 'Title Heading Tag' ) }
                        value={ titleTag1 }
                        options= {[
                                { value: 'h2', label: 'Heading 2 (h2)' },
                                { value: 'h3', label: 'Heading 3 (h3)' },
                                { value: 'h4', label: 'Heading 4 (h4)' },
                                { value: 'h5', label: 'Heading 5 (h5)' },
                                { value: 'h6', label: 'Heading 6 (h6)' },
                                { value: 'p', label: 'Paragraph (p)' },
                            ]}
                            onChange={ ( value ) => setAttributes( { titleTag1: value } ) }
                    />   
                    
                     

                    
                    
                    { productColumnStyle && productColumnStyle == 'layout1' &&
                    <Fragment>
                    
                    <RangeControl label={__("Number of stars")} value={ratings1} onChange={value => setAttributes({ ratings1: value, selectedStars1: value < selectedStars1 ? value : selectedStars1 }) } min={5} step={1} max={10} allowReset />
                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                        activeClass="active-tab"
                        tabs={[
                          {
                            name: 'boxdesk',
                            title: <Dashicon icon="desktop" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxtablet',
                            title: <Dashicon icon="tablet" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxmobile',
                            title: <Dashicon icon="smartphone" />,
                            className: 'affiliate-responsive-tabs',
                          },
                        ]}>
                        {
                          (radiustab) => {
                            let tabout;
                            if (radiustab.name) {
                              if ('boxmobile' === radiustab.name) {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSizeMobile1}  onChange={(value) => setAttributes({ starsSizeMobile1: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSizeTablet1} onChange={(value) => setAttributes({ starsSizeTablet1: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              } else {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSize1}  onChange={(value) => setAttributes({ starsSize1: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              }
                            }
                            return <div>{tabout}</div>;
                          }
                        }
                    </TabPanel>

                    {listType1 == 'unordered' &&
                        <Fragment>
                            <IconSelector
                                label="Top List Icon"
                                value={bulletStyle1.name}
                                enableSearch
                                icons={[
                                    { name: 'check', value: 'fas fa-check' },
                                    { name: 'check-square', value: 'fas fa-check-square' },
                                    { name: 'check-square-outline', value: 'far fa-check-square' },
                                    { name: 'check-double', value: 'fas fa-check-double' },
                                    { name: 'check-circle', value: 'fas fa-check-circle' },
                                    { name: 'check-circle-outline', value: 'far fa-check-circle' },
                                    { name: 'square', value: 'fas fa-square' },
                                    { name: 'square-outline', value: 'far fa-square' },
                                    { name: 'circle', value: 'fas fa-circle' },
                                    { name: 'circle-outline', value: 'far fa-circle' },
                                    { name: 'arrow-right', value: 'fas fa-arrow-right' },
                                    { name: 'arrow-left', value: 'fas fa-arrow-left' },
                                    { name: 'arrow-circle-right', value: 'fas fa-arrow-circle-right' },
                                    { name: 'arrow-circle-left', value: 'fas fa-arrow-circle-left' },
                                    { name: 'arrow-alt-circle-right', value: 'far fa-arrow-alt-circle-right' },
                                    { name: 'arrow-alt-circle-left', value: 'far fa-arrow-alt-circle-left' },
                                    { name: 'long-arrow-alt-right', value: 'fas fa-long-arrow-alt-right' },
                                    { name: 'long-arrow-alt-left', value: 'fas fa-long-arrow-alt-left' },
                                    { name: 'chevron-right', value: 'fas fa-chevron-right' },
                                    { name: 'chevron-left', value: 'fas fa-chevron-left' },
                                    { name: 'angle-right', value: 'fas fa-angle-right' },
                                    { name: 'angle-left', value: 'fas fa-angle-left' },
                                    { name: 'star', value: 'fas fa-star' },
                                    { name: 'star-outline', value: 'far fa-star' },
                                    { name: 'windows-close-fill', value: 'fas fa-window-close' },
                                    { name: 'ban', value: 'fas fa-ban' },
                                    { name: 'window-close-simple', value: 'far fa-window-close' },
                                    { name: 'times', value: 'fas fa-times' },
                                    { name: 'times-circle', value: 'fas fa-times-circle' },
                                    { name: 'times-circle-simple', value: 'far fa-times-circle' },
                                    { name: 'dot-circle-fill', value: 'fas fa-dot-circle' },
                                    { name: 'dot-circle-simple', value: 'far fa-dot-circle' },
                                    { name: 'thumb-up-fill', value: 'fas fa-thumbs-up' },
                                    { name: 'thumb-up-simple', value: 'far fa-thumbs-up' },
                                    { name: 'thumb-down-fill', value: 'fas fa-thumbs-down' },
                                    { name: 'thumb-down-simple', value: 'far fa-thumbs-down' }, 
                                ]}
                                onChange={val => setAttributes({ bulletStyle1: val })}
                            />
                        </Fragment>
                    }
                    {listType1 == 'ordered' &&
                        <Fragment>
                            <Range label={__('Font Size')} value={numberFontSize1} onChange={(value) => setAttributes({ numberFontSize1: value })} min={10} max={100} />
                            <Toggle
                                value={useNumberBg1}
                                label={__('Use Background')}
                                onChange={val => setAttributes({ useNumberBg1: val, recreateStyles1: !recreateStyles1 })}
                            />
                            {
                                useNumberBg1 == 1 &&
                                <Fragment>
                                    <Range label={__('Background Size')} value={numberBgSize1} onChange={(value) => setAttributes({ numberBgSize1: value })} min={1} max={15} />
                                    <Range label={__('Corner')} value={numberCorner1} onChange={(value) => setAttributes({ numberCorner1: value })} min={0} max={100} />
                                </Fragment>
                            }
                        </Fragment>
                    }
                    
                    <Dropdown
                        className="affiliate-icon-popover"
                        contentClassName="my-popover-content-classname"
                        popoverProps={ { placement: 'bottom-start' } }
                        renderToggle={ ( { isOpen, onToggle } ) => (
                        <Fragment>
                            <h2>{__('Tag Icon')}</h2>
                            <Button className="affiliate-size-btn"  onClick={ onToggle } aria-expanded={ isOpen }>
                                <Dashicon icon="admin-tools" />
                            </Button>
                        </Fragment> 
                        ) }
                        renderContent={ () => (
                            <div className="affiliate-iconlist-popwrapper">
                                <IconList value={buttonIcon1} disableToggle onChange={val => setAttributes({ buttonIcon1: val })} />
                            </div>
                        ) }
                    />
                    <RadioAdvanced
                          label={__('Icon Position')}
                          options={[
                              { label: 'Left', value: 'af-icon-is-left', title: 'Left' },
                              { label: 'Right', value: 'af-icon-is-right', title: 'Right' },
                          ]}
                          value={iconPosition1}
                          onChange={(value) => setAttributes({ iconPosition1: value })} />   

                    </Fragment>
                    }

                    <TextControl        
                        label = { __( 'Button Text' ) }             
                        value={ spBtnText1 }                        
                        onChange={ ( value ) => setAttributes( { spBtnText1: value } ) }
                    />
                    <TextControl
                        label = { __( 'Button URL' ) }                      
                        value={ spBtnHref1 }                        
                        onChange={ ( value ) => setAttributes( { spBtnHref1: value } ) }
                    />
                    <TextControl
                        label = { __( 'Button Rel' ) }                      
                        value={ btnRel1 }                       
                        onChange={ ( value ) => setAttributes( { btnRel1: value } ) }
                    />
                    <ToggleControl
                        label={ __( 'Open link in new Tab' ) }                       
                        checked={ btn_Htarget1 }                                        
                        onChange={ ( ) => setAttributes( { btn_Htarget1: ! btn_Htarget1 } ) }
                    />
                    <ToggleControl
                        label={ __( 'Rounded Button' ) }                        
                        checked={ bntRounded1 }                                     
                        onChange={ ( ) => setAttributes( { bntRounded1: ! bntRounded1 } ) }
                    />
                    <ToggleControl
                          label={ __( 'Enable Glimmer Effect' ) }            
                          checked={ enableGlimmerEffect }                    
                          onChange={ ( ) => setAttributes( { enableGlimmerEffect: ! enableGlimmerEffect } ) }
                      />
                    <RadioAdvanced
                            label={__('Button Size')}
                            options={[
                                { label: 'S', value: 'small', title: 'Small' },
                                { label: 'M', value: 'medium', title: 'Medium' },
                                { label: 'L', value: 'large', title: 'Large' },
                                { label: 'XL', value: 'extra-large', title: 'Extra Large' },
                            ]}
                            value={btnSize1}
                            onChange={(value) => setAttributes({ btnSize1: value })} />
                    <RadioAdvanced
                            label={__('Button Width')}
                            options={[
                                { label: 'Fixed', value: 'fixed', title: 'Fixed' },
                                { label: 'Flexible', value: 'flex', title: 'Flexible' },
                                { label: 'Full', value: 'full', title: 'Full' },
                            ]}
                            value={buttonWidth1}
                            onChange={(value) => setAttributes({ buttonWidth1: value })} />
                    <TypographyControl
                            label={__("Button Typography")}
                            attributes={attributes}
                            setAttributes={setAttributes}
                            loadGoogleFonts={{ value:btnLoadGoogleFonts1, label: __("btnLoadGoogleFonts1") }}
                            fontFamily={{ value: btnfontFamily1, label: __("btnfontFamily1") }}
                            fontWeight={{ value: btnfontWeight1, label: __("btnfontWeight1") }}
                            fontSubset={{ value: btnfontSubset1, label: __("btnfontSubset1") }}
                            fontSizeType={{ value: btnfontSizeType1, label: __("btnfontSizeType1") }}
                            fontSizeTypeMobile={{ value: btnfontSizeTypeMobile1, label: __("btnfontSizeTypeMobile1") }}
                            fontSizeTypeTablet={{ value: btnfontSizeTypeTablet1, label: __("btnfontSizeTypeTablet1") }}
                            fontSize={{ value: btnfontSizeDesktop1, label: __("btnfontSizeDesktop1") }}
                            fontSizeMobile={{ value: btnfontSizeMobile1, label: __("btnfontSizeMobile1") }}
                            fontSizeTablet={{ value: btnfontSizeTablet1, label: __("btnfontSizeTablet1") }}
                            lineHeightType={{ value: '', label: __(" ") }}
                            lineHeight={{ value: btnlineHeight1, label: __("btnlineHeight1") }}
                            lineHeightMobile={{ value: btnlineHeightMobile1, label: __("btnlineHeightMobile1") }}
                            lineHeightTablet={{ value: btnlineHeightTablet1, label: __("btnlineHeightTablet1") }}
                            letterSpacing={{ value: btnletterSpacing1, label: __("btnletterSpacing1") }}
                            letterSpacingMobile={{ value: btnletterSpacingMobile1, label: __("btnletterSpacingMobile1") }}
                            letterSpacingTablet={{ value: btnletterSpacingTablet1, label: __("btnletterSpacingTablet1") }}
                            letterSpacingType={{ value: btnletterSpacingType1, label: __("btnletterSpacingType1") }}
                            letterSpacingTypeTablet={{ value: btnletterSpacingTypeTablet1, label: __("btnletterSpacingTypeTablet1") }}
                            letterSpacingTypeMobile={{ value: btnletterSpacingTypeMobile1, label: __("btnletterSpacingTypeMobile1") }}
                            textTransform={btntextTransform1}
                            onTextTransform={(value) => setAttributes({ btntextTransform1: value })}
                            textDecoration={btntextDecoration1}
                            onTextDecoration={(value) => setAttributes({ btntextDecoration1: value })}
                            fontStyle={btnfontStyle1}
                            onFontStyle={(value) => setAttributes({ btnfontStyle1: value })}
                        />
                        <Tabs>
                            <Tab tabTitle={__('Normal')}>
                              <Color label={__('Text Color')} disableAlpha value={btnTextColor1} onChange={val => setAttributes({ btnTextColor1: val })} />
                              <Color label={__('Border Color')} disableAlpha value={btnBorderColor1} onChange={val => setAttributes({ btnBorderColor1: val })} />
                              <Background
                                      parallax
                                      value={btnBg}
                                      label={__('Background')}
                                      externalImage
                                      sources={['color','gradient']}
                                      onChange={val => setAttributes({ btnBg: val })} 
                                  />
                            </Tab>
                            <Tab tabTitle={__('Hover')}>
                              <Color label={__('Hover Text Color')} disableAlpha value={btnTextHoverColor1} onChange={val => setAttributes({ btnTextHoverColor1: val })} />
                              <Color label={__('Border Hover Color')} disableAlpha value={btnBorderHoverColor1} onChange={val => setAttributes({ btnBorderHoverColor1: val })} />
                              <Background
                                      parallax
                                      value={btnBgHover}
                                      label={__('Background')}
                                      externalImage
                                      sources={['color','gradient']}
                                      onChange={val => setAttributes({ btnBgHover: val })}
                                    />
                            </Tab>
                        </Tabs>
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Padding')} value={btnPaddingMobile1}
                                        onChange={val => setAttributes({ btnPaddingMobile1: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Padding')} value={btnPaddingTablet1}
                                        onChange={val => setAttributes({ btnPaddingTablet1: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Padding')} value={btnPadding1}
                                        onChange={val => setAttributes({ btnPadding1: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Margin')}
                                        value={btnMarginMobile1}
                                        onChange={val => setAttributes({ btnMarginMobile1: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Margin')} value={btnMarginTablet1}
                                        onChange={val => setAttributes({ btnMarginMobile1: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Margin')} value={btnMargin1}
                                        onChange={val => setAttributes({ btnMargin1: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>
                        <BorderTypes label={__('Border Type')} value={btnBorderType1} onChange={(value) => setAttributes({ btnBorderType1: value })} />
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Width')} value={btnBorderMobile1}
                                        onChange={val => setAttributes({ btnBorderMobile1: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Border Width')} 
                                        value={btnBorderTablet1}
                                        onChange={val => setAttributes({ btnBorderTablet1: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Width')} value={btnBorder1}
                                        onChange={val => setAttributes({ btnBorder1: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Radius')} value={btnBorderRadiusMobile1}
                                        onChange={val => setAttributes({ btnBorderRadiusMobile1: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Border Radius')} 
                                        value={btnBorderRadiusTablet1}
                                        onChange={val => setAttributes({ btnBorderRadiusTablet1: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Radius')} value={btnBorderRadius1}
                                        onChange={val => setAttributes({ btnBorderRadius1: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignmentMobile1}
                                          onChange={(value) => setAttributes({ btnAlignmentMobile1: value })}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignmentTablet1}
                                          onChange={(value) => setAttributes({ btnAlignmentTablet1: value })}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignment1}
                                          onChange={(value) => setAttributes({ btnAlignment1: value })}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>                          


                        <TextareaControl              
                            label={ __( 'Amazon Price Shortcode' ) }         
                            value={ amazonprice1 }                       
                            onChange={ ( value ) => setAttributes( { amazonprice1: value } ) }
                        />
                        <SelectControl
                            label={ __( 'Show Amazon Price Position' ) }
                            value={ amazonpriceposition1 }
                            options= {[
                                    { value: 'Above Button', label: 'Above Button' },
                                    { value: 'Below Button', label: 'Below Button' },
                                ]}
                                onChange={ ( value ) => setAttributes( { amazonpriceposition1: value } ) }
                          />
                        <TextareaControl              
                            label={ __( 'Amazon Rating Shortcode' ) }         
                            value={ amazonrating1 }                       
                            onChange={ ( value ) => setAttributes( { amazonrating1: value } ) }
                        />
                        <SelectControl
                            label={ __( 'Show Amazon Rating Position' ) }
                            value={ amazonratingposition1 }
                            options= {[
                                    { value: 'Above Button', label: 'Above Button' },
                                    { value: 'Below Button', label: 'Below Button' },
                                ]}
                                onChange={ ( value ) => setAttributes( { amazonratingposition1: value } ) }
                          />
                        

                </PanelBody>
                }
                
             

                { numberOfBlock && numberOfBlock >= '2' &&
                <PanelBody title={`Box 2 Settings`} initialOpen={false} icon={ getIcon( 'layout' ) } className={ 'abblocks-panel-label' }>

                    <ToggleControl
                          label={ __( 'External Image?' ) }                        
                          checked={ externalImage2 }                                      
                          onChange={ ( ) => setAttributes( { externalImage2: ! externalImage2 } ) }
                      />

                    { externalImage2 && externalImage2 == true &&
                        <Fragment>
                      <TextareaControl              
                          label={ __( 'External Image Src' ) }         
                          value={ externalImageSrc2 }                       
                          onChange={ ( value ) => setAttributes( { externalImageSrc2: value } ) }
                      />
                      <TextControl              
                            label={ __( 'External Image Alt' ) }         
                            value={ externalImageAlt2 }                       
                            onChange={ ( value ) => setAttributes( { externalImageAlt2: value } ) }
                        />
                        </Fragment>
                    }

                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                          activeClass="active-tab"
                          tabs={[
                            {
                              name: 'boxdesk',
                              title: <Dashicon icon="desktop" />,
                              className: 'affiliate-responsive-tabs',
                            },
                            {
                              name: 'boxtablet',
                              title: <Dashicon icon="tablet" />,
                              className: 'affiliate-responsive-tabs',
                            },
                            {
                              name: 'boxmobile',
                              title: <Dashicon icon="smartphone" />,
                              className: 'affiliate-responsive-tabs',
                            },
                          ]}>
                          {
                            (radiustab) => {
                              let tabout;
                              if (radiustab.name) {
                                if ('boxmobile' === radiustab.name) {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidthMobile2} onChange={(value) => setAttributes({ customImgWidthMobile2: value })} min={0} max={1000} unit={['']} />
                                  );
                                } else if ('boxtablet' === radiustab.name) {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidthTablet2} onChange={(value) => setAttributes({ customImgWidthTablet2: value })} min={0} max={1000} unit={['']} />
                                  );
                                } else {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidth2} onChange={(value) => setAttributes({ customImgWidth2: value })} min={0} max={1000} unit={['']} />
                                  );
                                }
                              }
                              return <div>{tabout}</div>;
                            }
                          }
                    </TabPanel>
                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                        activeClass="active-tab"
                        tabs={[
                          {
                            name: 'boxdesk',
                            title: <Dashicon icon="desktop" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxtablet',
                            title: <Dashicon icon="tablet" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxmobile',
                            title: <Dashicon icon="smartphone" />,
                            className: 'affiliate-responsive-tabs',
                          },
                        ]}>
                        {
                          (radiustab) => {
                            let tabout;
                            if (radiustab.name) {
                              if ('boxmobile' === radiustab.name) {
                                tabout = (
                                  <Dimension
                                    label={__('Image Padding')} value={imagePaddingMobile2}
                                    onChange={val => setAttributes({ imagePaddingMobile2: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                  <Dimension label={__('Image Padding')} value={imagePaddingTablet2}
                                    onChange={val => setAttributes({ imagePaddingTablet2: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else {
                                tabout = (
                                  <Dimension
                                    label={__('Image Padding')} value={imagePadding2}
                                    onChange={val => setAttributes({ imagePadding2: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              }
                            }
                            return <div>{tabout}</div>;
                          }
                        }
                    </TabPanel> 

                    <Color label={__('Background Color')} disableAlpha value={boxBgColor2} onChange={val => setAttributes({ boxBgColor2: val })} />    

                    { productColumnStyle && productColumnStyle == 'layout1' &&
                    <SelectControl
                        label={ __( 'Flag Style' ) }
                        value={ flagStyle2 }
                        options= {[
                                { value: '', label: 'Select' },
                                { value: 'flag1', label: 'Budget Pick' },
                                { value: 'flag2', label: 'Best Value' },
                                { value: 'flag3', label: 'Premium Pick' },
                            ]}
                            onChange={ ( value ) => setAttributes( { flagStyle2: value } ) }
                    />   
                    } 

                    <SelectControl
                        label={ __( 'Title Heading Tag' ) }
                        value={ titleTag2 }
                        options= {[
                                { value: 'h2', label: 'Heading 2 (h2)' },
                                { value: 'h3', label: 'Heading 3 (h3)' },
                                { value: 'h4', label: 'Heading 4 (h4)' },
                                { value: 'h5', label: 'Heading 5 (h5)' },
                                { value: 'h6', label: 'Heading 6 (h6)' },
                                { value: 'p', label: 'Paragraph (p)' },
                            ]}
                            onChange={ ( value ) => setAttributes( { titleTag2: value } ) }
                    />   

                     

                    { productColumnStyle && productColumnStyle == 'layout1' &&
                    <Fragment>
                    
                   <RangeControl label={__("Number of stars")} value={ratings2} onChange={value => setAttributes({ ratings2: value, selectedStars2: value < selectedStars2 ? value : selectedStars2 }) } min={5} step={1} max={10} allowReset />
                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                        activeClass="active-tab"
                        tabs={[
                          {
                            name: 'boxdesk',
                            title: <Dashicon icon="desktop" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxtablet',
                            title: <Dashicon icon="tablet" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxmobile',
                            title: <Dashicon icon="smartphone" />,
                            className: 'affiliate-responsive-tabs',
                          },
                        ]}>
                        {
                          (radiustab) => {
                            let tabout;
                            if (radiustab.name) {
                              if ('boxmobile' === radiustab.name) {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSizeMobile2}  onChange={(value) => setAttributes({ starsSizeMobile2: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSizeTablet2}  onChange={(value) => setAttributes({ starsSizeTablet2: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              } else {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSize2}  onChange={(value) => setAttributes({ starsSize2: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              }
                            }
                            return <div>{tabout}</div>;
                          }
                        }
                    </TabPanel>

                    {listType2 == 'unordered' &&
                        <Fragment>
                            <IconSelector
                                label="Top List Icon"
                                value={bulletStyle2.name}
                                enableSearch
                                icons={[
                                    { name: 'check', value: 'fas fa-check' },
                                    { name: 'check-square', value: 'fas fa-check-square' },
                                    { name: 'check-square-outline', value: 'far fa-check-square' },
                                    { name: 'check-double', value: 'fas fa-check-double' },
                                    { name: 'check-circle', value: 'fas fa-check-circle' },
                                    { name: 'check-circle-outline', value: 'far fa-check-circle' },
                                    { name: 'square', value: 'fas fa-square' },
                                    { name: 'square-outline', value: 'far fa-square' },
                                    { name: 'circle', value: 'fas fa-circle' },
                                    { name: 'circle-outline', value: 'far fa-circle' },
                                    { name: 'arrow-right', value: 'fas fa-arrow-right' },
                                    { name: 'arrow-left', value: 'fas fa-arrow-left' },
                                    { name: 'arrow-circle-right', value: 'fas fa-arrow-circle-right' },
                                    { name: 'arrow-circle-left', value: 'fas fa-arrow-circle-left' },
                                    { name: 'arrow-alt-circle-right', value: 'far fa-arrow-alt-circle-right' },
                                    { name: 'arrow-alt-circle-left', value: 'far fa-arrow-alt-circle-left' },
                                    { name: 'long-arrow-alt-right', value: 'fas fa-long-arrow-alt-right' },
                                    { name: 'long-arrow-alt-left', value: 'fas fa-long-arrow-alt-left' },
                                    { name: 'chevron-right', value: 'fas fa-chevron-right' },
                                    { name: 'chevron-left', value: 'fas fa-chevron-left' },
                                    { name: 'angle-right', value: 'fas fa-angle-right' },
                                    { name: 'angle-left', value: 'fas fa-angle-left' },
                                    { name: 'star', value: 'fas fa-star' },
                                    { name: 'star-outline', value: 'far fa-star' },
                                    { name: 'windows-close-fill', value: 'fas fa-window-close' },
                                    { name: 'ban', value: 'fas fa-ban' },
                                    { name: 'window-close-simple', value: 'far fa-window-close' },
                                    { name: 'times', value: 'fas fa-times' },
                                    { name: 'times-circle', value: 'fas fa-times-circle' },
                                    { name: 'times-circle-simple', value: 'far fa-times-circle' },
                                    { name: 'dot-circle-fill', value: 'fas fa-dot-circle' },
                                    { name: 'dot-circle-simple', value: 'far fa-dot-circle' },
                                    { name: 'thumb-up-fill', value: 'fas fa-thumbs-up' },
                                    { name: 'thumb-up-simple', value: 'far fa-thumbs-up' },
                                    { name: 'thumb-down-fill', value: 'fas fa-thumbs-down' },
                                    { name: 'thumb-down-simple', value: 'far fa-thumbs-down' }, 
                                ]}
                                onChange={val => setAttributes({ bulletStyle2: val })}
                            />
                        </Fragment>
                    }
                    {listType2 == 'ordered' &&
                        <Fragment>
                            <Range label={__('Font Size')} value={numberFontSize2} onChange={(value) => setAttributes({ numberFontSize2: value })} min={10} max={100} />
                            <Toggle
                                value={useNumberBg2}
                                label={__('Use Background')}
                                onChange={val => setAttributes({ useNumberBg2: val, recreateStyles2: !recreateStyles2 })}
                            />
                            {
                                useNumberBg2 == 1 &&
                                <Fragment>
                                    <Range label={__('Background Size')} value={numberBgSize2} onChange={(value) => setAttributes({ numberBgSize2: value })} min={1} max={15} />
                                    <Range label={__('Corner')} value={numberCorner2} onChange={(value) => setAttributes({ numberCorner2: value })} min={0} max={100} />
                                </Fragment>
                            }
                        </Fragment>
                    }
                    

                    <Dropdown
                        className="affiliate-icon-popover"
                        contentClassName="my-popover-content-classname"
                        popoverProps={ { placement: 'bottom-start' } }
                        renderToggle={ ( { isOpen, onToggle } ) => (
                        <Fragment>
                            <h2>{__('Tag Icon')}</h2>
                            <Button className="affiliate-size-btn"  onClick={ onToggle } aria-expanded={ isOpen }>
                                <Dashicon icon="admin-tools" />
                            </Button>
                        </Fragment> 
                        ) }
                        renderContent={ () => (
                            <div className="affiliate-iconlist-popwrapper">
                                <IconList value={buttonIcon2} disableToggle onChange={val => setAttributes({ buttonIcon2: val })} />
                            </div>
                        ) }
                    />
                    
                    <RadioAdvanced
                          label={__('Icon Position')}
                          options={[
                              { label: 'Left', value: 'af-icon-is-left', title: 'Left' },
                              { label: 'Right', value: 'af-icon-is-right', title: 'Right' },
                          ]}
                          value={iconPosition2}
                          onChange={(value) => setAttributes({ iconPosition2: value })} />     
                    </Fragment>
                    }     

                    <TextControl        
                        label = { __( 'Button Text' ) }             
                        value={ spBtnText2 }                        
                        onChange={ ( value ) => setAttributes( { spBtnText2: value } ) }
                    />
                    <TextControl
                        label = { __( 'Button URL' ) }                      
                        value={ spBtnHref2 }                        
                        onChange={ ( value ) => setAttributes( { spBtnHref2: value } ) }
                    />
                    <TextControl
                        label = { __( 'Button Rel' ) }                      
                        value={ btnRel2 }                       
                        onChange={ ( value ) => setAttributes( { btnRel2: value } ) }
                    />
                    <ToggleControl
                        label={ __( 'Open link in new Tab' ) }                       
                        checked={ btn_Htarget2 }                                        
                        onChange={ ( ) => setAttributes( { btn_Htarget2: ! btn_Htarget2 } ) }
                    />
                    <ToggleControl
                        label={ __( 'Rounded Button' ) }                        
                        checked={ bntRounded2 }                                     
                        onChange={ ( ) => setAttributes( { bntRounded2: ! bntRounded2 } ) }
                    />
                    <ToggleControl
                          label={ __( 'Enable Glimmer Effect' ) }            
                          checked={ enableGlimmerEffect2 }                    
                          onChange={ ( ) => setAttributes( { enableGlimmerEffect2: ! enableGlimmerEffect2 } ) }
                      />
                    <RadioAdvanced
                            label={__('Button Size')}
                            options={[
                                { label: 'S', value: 'small', title: 'Small' },
                                { label: 'M', value: 'medium', title: 'Medium' },
                                { label: 'L', value: 'large', title: 'Large' },
                                { label: 'XL', value: 'extra-large', title: 'Extra Large' },
                            ]}
                            value={btnSize2}
                            onChange={(value) => setAttributes({ btnSize2: value })} />
                    <RadioAdvanced
                            label={__('Button Width')}
                            options={[
                                { label: 'Fixed', value: 'fixed', title: 'Fixed' },
                                { label: 'Flexible', value: 'flex', title: 'Flexible' },
                                { label: 'Full', value: 'full', title: 'Full' },
                            ]}
                            value={buttonWidth2}
                            onChange={(value) => setAttributes({ buttonWidth2: value })} />
                    <TypographyControl
                            label={__("Button Typography")}
                            attributes={attributes}
                            setAttributes={setAttributes}
                            loadGoogleFonts={{ value:btnLoadGoogleFonts2, label: __("btnLoadGoogleFonts2") }}
                            fontFamily={{ value: btnfontFamily2, label: __("btnfontFamily2") }}
                            fontWeight={{ value: btnfontWeight2, label: __("btnfontWeight2") }}
                            fontSubset={{ value: btnfontSubset2, label: __("btnfontSubset2") }}
                            fontSizeType={{ value: btnfontSizeType2, label: __("btnfontSizeType2") }}
                            fontSizeTypeMobile={{ value: btnfontSizeTypeMobile2, label: __("btnfontSizeTypeMobile2") }}
                            fontSizeTypeTablet={{ value: btnfontSizeTypeTablet2, label: __("btnfontSizeTypeTablet2") }}
                            fontSize={{ value: btnfontSizeDesktop2, label: __("btnfontSizeDesktop2") }}
                            fontSizeMobile={{ value: btnfontSizeMobile2, label: __("btnfontSizeMobile2") }}
                            fontSizeTablet={{ value: btnfontSizeTablet2, label: __("btnfontSizeTablet2") }}
                            lineHeightType={{ value: '', label: __(" ") }}
                            lineHeight={{ value: btnlineHeight2, label: __("btnlineHeight2") }}
                            lineHeightMobile={{ value: btnlineHeightMobile2, label: __("btnlineHeightMobile2") }}
                            lineHeightTablet={{ value: btnlineHeightTablet2, label: __("btnlineHeightTablet2") }}
                            letterSpacing={{ value: btnletterSpacing2, label: __("btnletterSpacing2") }}
                            letterSpacingMobile={{ value: btnletterSpacingMobile2, label: __("btnletterSpacingMobile2") }}
                            letterSpacingTablet={{ value: btnletterSpacingTablet2, label: __("btnletterSpacingTablet2") }}
                            letterSpacingType={{ value: btnletterSpacingType2, label: __("btnletterSpacingType2") }}
                            letterSpacingTypeTablet={{ value: btnletterSpacingTypeTablet2, label: __("btnletterSpacingTypeTablet2") }}
                            letterSpacingTypeMobile={{ value: btnletterSpacingTypeMobile2, label: __("btnletterSpacingTypeMobile2") }}
                            textTransform={btntextTransform2}
                            onTextTransform={(value) => setAttributes({ btntextTransform2: value })}
                            textDecoration={btntextDecoration2}
                            onTextDecoration={(value) => setAttributes({ btntextDecoration2: value })}
                            fontStyle={btnfontStyle2}
                            onFontStyle={(value) => setAttributes({ btnfontStyle2: value })}
                        />
                        <Tabs>
                            <Tab tabTitle={__('Normal')}>
                              <Color label={__('Text Color')} disableAlpha value={btnTextColor2} onChange={val => setAttributes({ btnTextColor2: val })} />
                              <Color label={__('Border Color')} disableAlpha value={btnBorderColor2} onChange={val => setAttributes({ btnBorderColor2: val })} />
                              <Background
                                      parallax
                                      value={btnBg2}
                                      label={__('Background')}
                                      externalImage
                                      sources={['color','gradient']}
                                      onChange={val => setAttributes({ btnBg2: val })} 
                                  />
                            </Tab>
                            <Tab tabTitle={__('Hover')}>
                              <Color label={__('Hover Text Color')} disableAlpha value={btnTextHoverColor2} onChange={val => setAttributes({ btnTextHoverColor2: val })} />
                              <Color label={__('Border Hover Color')} disableAlpha value={btnBorderHoverColor2} onChange={val => setAttributes({ btnBorderHoverColor2: val })} />
                              <Background
                                      parallax
                                      value={btnBgHover2}
                                      label={__('Background')}
                                      externalImage
                                      sources={['color','gradient']}
                                      onChange={val => setAttributes({ btnBgHover2: val })}
                                    />
                            </Tab>
                        </Tabs>
                            
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Padding')} value={btnPaddingMobile2}
                                        onChange={val => setAttributes({ btnPaddingMobile2: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Padding')} value={btnPaddingTablet2}
                                        onChange={val => setAttributes({ btnPaddingTablet2: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Padding')} value={btnPadding2}
                                        onChange={val => setAttributes({ btnPadding2: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Margin')}
                                        value={btnMarginMobile2}
                                        onChange={val => setAttributes({ btnMarginMobile2: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Margin')} value={btnMarginTablet2}
                                        onChange={val => setAttributes({ btnMarginMobile2: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Margin')} value={btnMargin2}
                                        onChange={val => setAttributes({ btnMargin2: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <BorderTypes label={__('Border Type')} value={btnBorderType2} onChange={(value) => setAttributes({ btnBorderType2: value })} />
                        
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Width')} value={btnBorderMobile2}
                                        onChange={val => setAttributes({ btnBorderMobile2: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Border Width')} 
                                        value={btnBorderTablet2}
                                        onChange={val => setAttributes({ btnBorderTablet2: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Width')} value={btnBorder2}
                                        onChange={val => setAttributes({ btnBorder2: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Radius')} value={btnBorderRadiusMobile2}
                                        onChange={val => setAttributes({ btnBorderRadiusMobile2: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Border Radius')} 
                                        value={btnBorderRadiusTablet2}
                                        onChange={val => setAttributes({ btnBorderRadiusTablet2: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Radius')} value={btnBorderRadius2}
                                        onChange={val => setAttributes({ btnBorderRadius2: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignmentMobile2}
                                          onChange={(value) => setAttributes({ btnAlignmentMobile2: value })}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignmentTablet2}
                                          onChange={(value) => setAttributes({ btnAlignmentTablet2: value })}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignment2}
                                          onChange={(value) => setAttributes({ btnAlignment2: value })}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>     


                        <TextareaControl              
                            label={ __( 'Amazon Price Shortcode' ) }         
                            value={ amazonprice2 }                       
                            onChange={ ( value ) => setAttributes( { amazonprice2: value } ) }
                        />
                        <SelectControl
                            label={ __( 'Show Amazon Price Position' ) }
                            value={ amazonpriceposition2 }
                            options= {[
                                    { value: 'Above Button', label: 'Above Button' },
                                    { value: 'Below Button', label: 'Below Button' },
                                ]}
                                onChange={ ( value ) => setAttributes( { amazonpriceposition2: value } ) }
                          />
                        <TextareaControl              
                            label={ __( 'Amazon Rating Shortcode' ) }         
                            value={ amazonrating2 }                       
                            onChange={ ( value ) => setAttributes( { amazonrating2: value } ) }
                        />
                        <SelectControl
                            label={ __( 'Show Amazon Rating Position' ) }
                            value={ amazonratingposition2 }
                            options= {[
                                    { value: 'Above Button', label: 'Above Button' },
                                    { value: 'Below Button', label: 'Below Button' },
                                ]}
                                onChange={ ( value ) => setAttributes( { amazonratingposition2: value } ) }
                          />
                        

                </PanelBody>
                }


                { numberOfBlock && numberOfBlock >= '3' &&
                <PanelBody title={`Box 3 Settings`} initialOpen={false} icon={ getIcon( 'layout' ) } className={ 'abblocks-panel-label' }>

                    <ToggleControl
                          label={ __( 'External Image?' ) }                        
                          checked={ externalImage3 }                                      
                          onChange={ ( ) => setAttributes( { externalImage3: ! externalImage3 } ) }
                      />

                    { externalImage3 && externalImage3 == true &&
                        <Fragment>
                      <TextareaControl              
                          label={ __( 'External Image Src' ) }         
                          value={ externalImageSrc3 }                       
                          onChange={ ( value ) => setAttributes( { externalImageSrc3: value } ) }
                      />
                      <TextControl              
                            label={ __( 'External Image Alt' ) }         
                            value={ externalImageAlt3 }                       
                            onChange={ ( value ) => setAttributes( { externalImageAlt3: value } ) }
                        />
                        </Fragment>
                    }

                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                          activeClass="active-tab"
                          tabs={[
                            {
                              name: 'boxdesk',
                              title: <Dashicon icon="desktop" />,
                              className: 'affiliate-responsive-tabs',
                            },
                            {
                              name: 'boxtablet',
                              title: <Dashicon icon="tablet" />,
                              className: 'affiliate-responsive-tabs',
                            },
                            {
                              name: 'boxmobile',
                              title: <Dashicon icon="smartphone" />,
                              className: 'affiliate-responsive-tabs',
                            },
                          ]}>
                          {
                            (radiustab) => {
                              let tabout;
                              if (radiustab.name) {
                                if ('boxmobile' === radiustab.name) {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidthMobile3} onChange={(value) => setAttributes({ customImgWidthMobile3: value })} min={0} max={1000} unit={['']} />
                                  );
                                } else if ('boxtablet' === radiustab.name) {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidthTablet3} onChange={(value) => setAttributes({ customImgWidthTablet3: value })} min={0} max={1000} unit={['']} />
                                  );
                                } else {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidth3} onChange={(value) => setAttributes({ customImgWidth3: value })} min={0} max={1000} unit={['']} />
                                  );
                                }
                              }
                              return <div>{tabout}</div>;
                            }
                          }
                    </TabPanel>
                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                        activeClass="active-tab"
                        tabs={[
                          {
                            name: 'boxdesk',
                            title: <Dashicon icon="desktop" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxtablet',
                            title: <Dashicon icon="tablet" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxmobile',
                            title: <Dashicon icon="smartphone" />,
                            className: 'affiliate-responsive-tabs',
                          },
                        ]}>
                        {
                          (radiustab) => {
                            let tabout;
                            if (radiustab.name) {
                              if ('boxmobile' === radiustab.name) {
                                tabout = (
                                  <Dimension
                                    label={__('Image Padding')} value={imagePaddingMobile3}
                                    onChange={val => setAttributes({ imagePaddingMobile3: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                  <Dimension label={__('Image Padding')} value={imagePaddingTablet3}
                                    onChange={val => setAttributes({ imagePaddingTablet3: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else {
                                tabout = (
                                  <Dimension
                                    label={__('Image Padding')} value={imagePadding3}
                                    onChange={val => setAttributes({ imagePadding3: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              }
                            }
                            return <div>{tabout}</div>;
                          }
                        }
                    </TabPanel> 

                    <Color label={__('Background Color')} disableAlpha value={boxBgColor3} onChange={val => setAttributes({ boxBgColor3: val })} />    

                    { productColumnStyle && productColumnStyle == 'layout1' &&
                        <SelectControl
                            label={ __( 'Flag Style' ) }
                            value={ flagStyle3 }
                            options= {[
                                    { value: '', label: 'Select' },
                                    { value: 'flag1', label: 'Budget Pick' },
                                    { value: 'flag2', label: 'Best Value' },
                                    { value: 'flag3', label: 'Premium Pick' },
                                ]}
                                onChange={ ( value ) => setAttributes( { flagStyle3: value } ) }
                        />    
                    }

                    <SelectControl
                        label={ __( 'Title Heading Tag' ) }
                        value={ titleTag3 }
                        options= {[
                                { value: 'h2', label: 'Heading 2 (h2)' },
                                { value: 'h3', label: 'Heading 3 (h3)' },
                                { value: 'h4', label: 'Heading 4 (h4)' },
                                { value: 'h5', label: 'Heading 5 (h5)' },
                                { value: 'h6', label: 'Heading 6 (h6)' },
                                { value: 'p', label: 'Paragraph (p)' },
                            ]}
                            onChange={ ( value ) => setAttributes( { titleTag3: value } ) }
                    />   

                     
                    
                    { productColumnStyle && productColumnStyle == 'layout1' &&
                    <Fragment>
                    
                   <RangeControl label={__("Number of stars")} value={ratings3} onChange={value => setAttributes({ ratings3: value, selectedStars3: value < selectedStars3 ? value : selectedStars3 }) } min={5} step={1} max={10} allowReset />
                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                        activeClass="active-tab"
                        tabs={[
                          {
                            name: 'boxdesk',
                            title: <Dashicon icon="desktop" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxtablet',
                            title: <Dashicon icon="tablet" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxmobile',
                            title: <Dashicon icon="smartphone" />,
                            className: 'affiliate-responsive-tabs',
                          },
                        ]}>
                        {
                          (radiustab) => {
                            let tabout;
                            if (radiustab.name) {
                              if ('boxmobile' === radiustab.name) {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSizeMobile3}  onChange={(value) => setAttributes({ starsSizeMobile3: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSizeTablet3} onChange={(value) => setAttributes({ starsSizeTablet3: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              } else {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSize3}  onChange={(value) => setAttributes({ starsSize3: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              }
                            }
                            return <div>{tabout}</div>;
                          }
                        }
                    </TabPanel>

                    {listType3 == 'unordered' &&
                        <Fragment>
                            <IconSelector
                                label="Top List Icon"
                                value={bulletStyle3.name}
                                enableSearch
                                icons={[
                                    { name: 'check', value: 'fas fa-check' },
                                    { name: 'check-square', value: 'fas fa-check-square' },
                                    { name: 'check-square-outline', value: 'far fa-check-square' },
                                    { name: 'check-double', value: 'fas fa-check-double' },
                                    { name: 'check-circle', value: 'fas fa-check-circle' },
                                    { name: 'check-circle-outline', value: 'far fa-check-circle' },
                                    { name: 'square', value: 'fas fa-square' },
                                    { name: 'square-outline', value: 'far fa-square' },
                                    { name: 'circle', value: 'fas fa-circle' },
                                    { name: 'circle-outline', value: 'far fa-circle' },
                                    { name: 'arrow-right', value: 'fas fa-arrow-right' },
                                    { name: 'arrow-left', value: 'fas fa-arrow-left' },
                                    { name: 'arrow-circle-right', value: 'fas fa-arrow-circle-right' },
                                    { name: 'arrow-circle-left', value: 'fas fa-arrow-circle-left' },
                                    { name: 'arrow-alt-circle-right', value: 'far fa-arrow-alt-circle-right' },
                                    { name: 'arrow-alt-circle-left', value: 'far fa-arrow-alt-circle-left' },
                                    { name: 'long-arrow-alt-right', value: 'fas fa-long-arrow-alt-right' },
                                    { name: 'long-arrow-alt-left', value: 'fas fa-long-arrow-alt-left' },
                                    { name: 'chevron-right', value: 'fas fa-chevron-right' },
                                    { name: 'chevron-left', value: 'fas fa-chevron-left' },
                                    { name: 'angle-right', value: 'fas fa-angle-right' },
                                    { name: 'angle-left', value: 'fas fa-angle-left' },
                                    { name: 'star', value: 'fas fa-star' },
                                    { name: 'star-outline', value: 'far fa-star' },
                                    { name: 'windows-close-fill', value: 'fas fa-window-close' },
                                    { name: 'ban', value: 'fas fa-ban' },
                                    { name: 'window-close-simple', value: 'far fa-window-close' },
                                    { name: 'times', value: 'fas fa-times' },
                                    { name: 'times-circle', value: 'fas fa-times-circle' },
                                    { name: 'times-circle-simple', value: 'far fa-times-circle' },
                                    { name: 'dot-circle-fill', value: 'fas fa-dot-circle' },
                                    { name: 'dot-circle-simple', value: 'far fa-dot-circle' },
                                    { name: 'thumb-up-fill', value: 'fas fa-thumbs-up' },
                                    { name: 'thumb-up-simple', value: 'far fa-thumbs-up' },
                                    { name: 'thumb-down-fill', value: 'fas fa-thumbs-down' },
                                    { name: 'thumb-down-simple', value: 'far fa-thumbs-down' }, 
                                ]}
                                onChange={val => setAttributes({ bulletStyle3: val })}
                            />
                        </Fragment>
                    }
                    {listType3 == 'ordered' &&
                        <Fragment>
                            <Range label={__('Font Size')} value={numberFontSize3} onChange={(value) => setAttributes({ numberFontSize3: value })} min={10} max={100} />
                            <Toggle
                                value={useNumberBg3}
                                label={__('Use Background')}
                                onChange={val => setAttributes({ useNumberBg3: val, recreateStyles3: !recreateStyles3 })}
                            />
                            {
                                useNumberBg3 == 1 &&
                                <Fragment>
                                    <Range label={__('Background Size')} value={numberBgSize3} onChange={(value) => setAttributes({ numberBgSize3: value })} min={1} max={15} />
                                    <Range label={__('Corner')} value={numberCorner3} onChange={(value) => setAttributes({ numberCorner3: value })} min={0} max={100} />
                                </Fragment>
                            }
                        </Fragment>
                    }
                     
                    <Dropdown
                        className="affiliate-icon-popover"
                        contentClassName="my-popover-content-classname"
                        popoverProps={ { placement: 'bottom-start' } }
                        renderToggle={ ( { isOpen, onToggle } ) => (
                        <Fragment>
                            <h2>{__('Tag Icon')}</h2>
                            <Button className="affiliate-size-btn"  onClick={ onToggle } aria-expanded={ isOpen }>
                                <Dashicon icon="admin-tools" />
                            </Button>
                        </Fragment> 
                        ) }
                        renderContent={ () => (
                            <div className="affiliate-iconlist-popwrapper">
                                <IconList value={buttonIcon3} disableToggle onChange={val => setAttributes({ buttonIcon3: val })} />
                            </div>
                        ) }
                    />
                    
                    <RadioAdvanced
                          label={__('Icon Position')}
                          options={[
                              { label: 'Left', value: 'af-icon-is-left', title: 'Left' },
                              { label: 'Right', value: 'af-icon-is-right', title: 'Right' },
                          ]}
                          value={iconPosition3}
                          onChange={(value) => setAttributes({ iconPosition3: value })} />       

                    </Fragment>
                    }   

                    <TextControl        
                        label = { __( 'Button Text' ) }             
                        value={ spBtnText3 }                        
                        onChange={ ( value ) => setAttributes( { spBtnText3: value } ) }
                    />
                    <TextControl
                        label = { __( 'Button URL' ) }                      
                        value={ spBtnHref3 }                        
                        onChange={ ( value ) => setAttributes( { spBtnHref3: value } ) }
                    />
                    <TextControl
                        label = { __( 'Button Rel' ) }                      
                        value={ btnRel3 }                       
                        onChange={ ( value ) => setAttributes( { btnRel3: value } ) }
                    />
                    <ToggleControl
                        label={ __( 'Open link in new Tab' ) }                       
                        checked={ btn_Htarget3 }                                        
                        onChange={ ( ) => setAttributes( { btn_Htarget3: ! btn_Htarget3 } ) }
                    />
                    <ToggleControl
                        label={ __( 'Rounded Button' ) }                        
                        checked={ bntRounded3 }                                     
                        onChange={ ( ) => setAttributes( { bntRounded3: ! bntRounded3 } ) }
                    />
                    <ToggleControl
                  label={ __( 'Enable Glimmer Effect' ) }            
                  checked={ enableGlimmerEffect3 }                    
                  onChange={ ( ) => setAttributes( { enableGlimmerEffect3: ! enableGlimmerEffect3 } ) }
              />
                    <RadioAdvanced
                            label={__('Button Size')}
                            options={[
                                { label: 'S', value: 'small', title: 'Small' },
                                { label: 'M', value: 'medium', title: 'Medium' },
                                { label: 'L', value: 'large', title: 'Large' },
                                { label: 'XL', value: 'extra-large', title: 'Extra Large' },
                            ]}
                            value={btnSize3}
                            onChange={(value) => setAttributes({ btnSize3: value })} />
                    <RadioAdvanced
                            label={__('Button Width')}
                            options={[
                                { label: 'Fixed', value: 'fixed', title: 'Fixed' },
                                { label: 'Flexible', value: 'flex', title: 'Flexible' },
                                { label: 'Full', value: 'full', title: 'Full' },
                            ]}
                            value={buttonWidth3}
                            onChange={(value) => setAttributes({ buttonWidth3: value })} />
                    <TypographyControl
                            label={__("Button Typography")}
                            attributes={attributes}
                            setAttributes={setAttributes}
                            loadGoogleFonts={{ value:btnLoadGoogleFonts3, label: __("btnLoadGoogleFonts3") }}
                            fontFamily={{ value: btnfontFamily3, label: __("btnfontFamily3") }}
                            fontWeight={{ value: btnfontWeight3, label: __("btnfontWeight3") }}
                            fontSubset={{ value: btnfontSubset3, label: __("btnfontSubset3") }}
                            fontSizeType={{ value: btnfontSizeType3, label: __("btnfontSizeType3") }}
                            fontSizeTypeMobile={{ value: btnfontSizeTypeMobile3, label: __("btnfontSizeTypeMobile3") }}
                            fontSizeTypeTablet={{ value: btnfontSizeTypeTablet3, label: __("btnfontSizeTypeTablet3") }}
                            fontSize={{ value: btnfontSizeDesktop3, label: __("btnfontSizeDesktop3") }}
                            fontSizeMobile={{ value: btnfontSizeMobile3, label: __("btnfontSizeMobile3") }}
                            fontSizeTablet={{ value: btnfontSizeTablet3, label: __("btnfontSizeTablet3") }}
                            lineHeightType={{ value: '', label: __(" ") }}
                            lineHeight={{ value: btnlineHeight3, label: __("btnlineHeight3") }}
                            lineHeightMobile={{ value: btnlineHeightMobile3, label: __("btnlineHeightMobile3") }}
                            lineHeightTablet={{ value: btnlineHeightTablet3, label: __("btnlineHeightTablet3") }}
                            letterSpacing={{ value: btnletterSpacing3, label: __("btnletterSpacing3") }}
                            letterSpacingMobile={{ value: btnletterSpacingMobile3, label: __("btnletterSpacingMobile3") }}
                            letterSpacingTablet={{ value: btnletterSpacingTablet3, label: __("btnletterSpacingTablet3") }}
                            letterSpacingType={{ value: btnletterSpacingType3, label: __("btnletterSpacingType3") }}
                            letterSpacingTypeTablet={{ value: btnletterSpacingTypeTablet3, label: __("btnletterSpacingTypeTablet3") }}
                            letterSpacingTypeMobile={{ value: btnletterSpacingTypeMobile3, label: __("btnletterSpacingTypeMobile3") }}
                            textTransform={btntextTransform3}
                            onTextTransform={(value) => setAttributes({ btntextTransform3: value })}
                            textDecoration={btntextDecoration3}
                            onTextDecoration={(value) => setAttributes({ btntextDecoration3: value })}
                            fontStyle={btnfontStyle3}
                            onFontStyle={(value) => setAttributes({ btnfontStyle3: value })}
                        />
                        <Tabs>
                            <Tab tabTitle={__('Normal')}>
                              <Color label={__('Text Color')} disableAlpha value={btnTextColor3} onChange={val => setAttributes({ btnTextColor3: val })} />
                              <Color label={__('Border Color')} disableAlpha value={btnBorderColor3} onChange={val => setAttributes({ btnBorderColor3: val })} />
                              <Background
                                      parallax
                                      value={btnBg3}
                                      label={__('Background')}
                                      externalImage
                                      sources={['color','gradient']}
                                      onChange={val => setAttributes({ btnBg3: val })} 
                                  />
                            </Tab>
                            <Tab tabTitle={__('Hover')}>
                              <Color label={__('Hover Text Color')} disableAlpha value={btnTextHoverColor3} onChange={val => setAttributes({ btnTextHoverColor3: val })} />
                              <Color label={__('Border Hover Color')} disableAlpha value={btnBorderHoverColor3} onChange={val => setAttributes({ btnBorderHoverColor3: val })} />
                              <Background
                                      parallax
                                      value={btnBgHover3}
                                      label={__('Background')}
                                      externalImage
                                      sources={['color','gradient']}
                                      onChange={val => setAttributes({ btnBgHover3: val })}
                                    />
                            </Tab>
                        </Tabs>
                            
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Padding')} value={btnPaddingMobile3}
                                        onChange={val => setAttributes({ btnPaddingMobile3: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Padding')} value={btnPaddingTablet3}
                                        onChange={val => setAttributes({ btnPaddingTablet3: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Padding')} value={btnPadding3}
                                        onChange={val => setAttributes({ btnPadding3: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Margin')}
                                        value={btnMarginMobile3}
                                        onChange={val => setAttributes({ btnMarginMobile3: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Margin')} value={btnMarginTablet3}
                                        onChange={val => setAttributes({ btnMarginMobile3: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Margin')} value={btnMargin3}
                                        onChange={val => setAttributes({ btnMargin3: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <BorderTypes label={__('Border Type')} value={btnBorderType3} onChange={(value) => setAttributes({ btnBorderType3: value })} />
                        
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Width')} value={btnBorderMobile3}
                                        onChange={val => setAttributes({ btnBorderMobile3: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Border Width')} 
                                        value={btnBorderTablet3}
                                        onChange={val => setAttributes({ btnBorderTablet3: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Width')} value={btnBorder3}
                                        onChange={val => setAttributes({ btnBorder3: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Radius')} value={btnBorderRadiusMobile3}
                                        onChange={val => setAttributes({ btnBorderRadiusMobile3: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Border Radius')} 
                                        value={btnBorderRadiusTablet3}
                                        onChange={val => setAttributes({ btnBorderRadiusTablet3: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Radius')} value={btnBorderRadius3}
                                        onChange={val => setAttributes({ btnBorderRadius3: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignmentMobile3}
                                          onChange={(value) => setAttributes({ btnAlignmentMobile3: value })}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignmentTablet3}
                                          onChange={(value) => setAttributes({ btnAlignmentTablet3: value })}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignment3}
                                          onChange={(value) => setAttributes({ btnAlignment3: value })}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>


                        <TextareaControl              
                            label={ __( 'Amazon Price Shortcode' ) }         
                            value={ amazonprice3 }                       
                            onChange={ ( value ) => setAttributes( { amazonprice3: value } ) }
                        />
                        <SelectControl
                            label={ __( 'Show Amazon Price Position' ) }
                            value={ amazonpriceposition3 }
                            options= {[
                                    { value: 'Above Button', label: 'Above Button' },
                                    { value: 'Below Button', label: 'Below Button' },
                                ]}
                                onChange={ ( value ) => setAttributes( { amazonpriceposition3: value } ) }
                          />
                        <TextareaControl              
                            label={ __( 'Amazon Rating Shortcode' ) }         
                            value={ amazonrating3 }                       
                            onChange={ ( value ) => setAttributes( { amazonrating3: value } ) }
                        />
                        <SelectControl
                            label={ __( 'Show Amazon Rating Position' ) }
                            value={ amazonratingposition3 }
                            options= {[
                                    { value: 'Above Button', label: 'Above Button' },
                                    { value: 'Below Button', label: 'Below Button' },
                                ]}
                                onChange={ ( value ) => setAttributes( { amazonratingposition3: value } ) }
                          />
                        

                </PanelBody>
                }



                { numberOfBlock && numberOfBlock >= '4' &&
                <PanelBody title={`Box 4 Settings`} initialOpen={false} icon={ getIcon( 'layout' ) } className={ 'abblocks-panel-label' }>

                    <ToggleControl
                          label={ __( 'External Image?' ) }                        
                          checked={ externalImage4 }                                      
                          onChange={ ( ) => setAttributes( { externalImage4: ! externalImage4 } ) }
                      />

                    { externalImage4 && externalImage4 == true &&
                        <Fragment>
                      <TextareaControl              
                          label={ __( 'External Image Src' ) }         
                          value={ externalImageSrc4 }                       
                          onChange={ ( value ) => setAttributes( { externalImageSrc4: value } ) }
                      />
                      <TextControl              
                            label={ __( 'External Image Alt' ) }         
                            value={ externalImageAlt4 }                       
                            onChange={ ( value ) => setAttributes( { externalImageAlt4: value } ) }
                        />
                        </Fragment>
                    }

                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                          activeClass="active-tab"
                          tabs={[
                            {
                              name: 'boxdesk',
                              title: <Dashicon icon="desktop" />,
                              className: 'affiliate-responsive-tabs',
                            },
                            {
                              name: 'boxtablet',
                              title: <Dashicon icon="tablet" />,
                              className: 'affiliate-responsive-tabs',
                            },
                            {
                              name: 'boxmobile',
                              title: <Dashicon icon="smartphone" />,
                              className: 'affiliate-responsive-tabs',
                            },
                          ]}>
                          {
                            (radiustab) => {
                              let tabout;
                              if (radiustab.name) {
                                if ('boxmobile' === radiustab.name) {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidthMobile4} onChange={(value) => setAttributes({ customImgWidthMobile4: value })} min={0} max={1000} unit={['']} />
                                  );
                                } else if ('boxtablet' === radiustab.name) {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidthTablet4} onChange={(value) => setAttributes({ customImgWidthTablet4: value })} min={0} max={1000} unit={['']} />
                                  );
                                } else {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidth4} onChange={(value) => setAttributes({ customImgWidth4: value })} min={0} max={1000} unit={['']} />
                                  );
                                }
                              }
                              return <div>{tabout}</div>;
                            }
                          }
                    </TabPanel>
                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                        activeClass="active-tab"
                        tabs={[
                          {
                            name: 'boxdesk',
                            title: <Dashicon icon="desktop" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxtablet',
                            title: <Dashicon icon="tablet" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxmobile',
                            title: <Dashicon icon="smartphone" />,
                            className: 'affiliate-responsive-tabs',
                          },
                        ]}>
                        {
                          (radiustab) => {
                            let tabout;
                            if (radiustab.name) {
                              if ('boxmobile' === radiustab.name) {
                                tabout = (
                                  <Dimension
                                    label={__('Image Padding')} value={imagePaddingMobile4}
                                    onChange={val => setAttributes({ imagePaddingMobile4: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                  <Dimension label={__('Image Padding')} value={imagePaddingTablet4}
                                    onChange={val => setAttributes({ imagePaddingTablet4: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else {
                                tabout = (
                                  <Dimension
                                    label={__('Image Padding')} value={imagePadding4}
                                    onChange={val => setAttributes({ imagePadding4: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              }
                            }
                            return <div>{tabout}</div>;
                          }
                        }
                    </TabPanel> 

                    <Color label={__('Background Color')} disableAlpha value={boxBgColor4} onChange={val => setAttributes({ boxBgColor4: val })} />    

                    { productColumnStyle && productColumnStyle == 'layout1' &&
                    <SelectControl
                        label={ __( 'Flag Style' ) }
                        value={ flagStyle4 }
                        options= {[
                                { value: '', label: 'Select' },
                                { value: 'flag1', label: 'Budget Pick' },
                                { value: 'flag2', label: 'Best Value' },
                                { value: 'flag3', label: 'Premium Pick' },
                            ]}
                            onChange={ ( value ) => setAttributes( { flagStyle4: value } ) }
                    />   
                    } 

                    <SelectControl
                        label={ __( 'Title Heading Tag' ) }
                        value={ titleTag4 }
                        options= {[
                                { value: 'h2', label: 'Heading 2 (h2)' },
                                { value: 'h3', label: 'Heading 3 (h3)' },
                                { value: 'h4', label: 'Heading 4 (h4)' },
                                { value: 'h5', label: 'Heading 5 (h5)' },
                                { value: 'h6', label: 'Heading 6 (h6)' },
                                { value: 'p', label: 'Paragraph (p)' },
                            ]}
                            onChange={ ( value ) => setAttributes( { titleTag4: value } ) }
                    />   

                     
                        
                    { productColumnStyle && productColumnStyle == 'layout1' &&
                    <Fragment>
                    
                    <RangeControl label={__("Number of stars")} value={ratings4} onChange={value => setAttributes({ ratings4: value, selectedStars4: value < selectedStars4 ? value : selectedStars4 }) } min={5} step={1} max={10} allowReset />
                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                        activeClass="active-tab"
                        tabs={[
                          {
                            name: 'boxdesk',
                            title: <Dashicon icon="desktop" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxtablet',
                            title: <Dashicon icon="tablet" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxmobile',
                            title: <Dashicon icon="smartphone" />,
                            className: 'affiliate-responsive-tabs',
                          },
                        ]}>
                        {
                          (radiustab) => {
                            let tabout;
                            if (radiustab.name) {
                              if ('boxmobile' === radiustab.name) {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSizeMobile4}  onChange={(value) => setAttributes({ starsSizeMobile4: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSizeTablet4}  onChange={(value) => setAttributes({ starsSizeTablet4: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              } else {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSize4}  onChange={(value) => setAttributes({ starsSize4: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              }
                            }
                            return <div>{tabout}</div>;
                          }
                        }
                    </TabPanel>

                    {listType4 == 'unordered' &&
                        <Fragment>
                            <IconSelector
                                label="Top List Icon"
                                value={bulletStyle4.name}
                                enableSearch
                                icons={[
                                    { name: 'check', value: 'fas fa-check' },
                                    { name: 'check-square', value: 'fas fa-check-square' },
                                    { name: 'check-square-outline', value: 'far fa-check-square' },
                                    { name: 'check-double', value: 'fas fa-check-double' },
                                    { name: 'check-circle', value: 'fas fa-check-circle' },
                                    { name: 'check-circle-outline', value: 'far fa-check-circle' },
                                    { name: 'square', value: 'fas fa-square' },
                                    { name: 'square-outline', value: 'far fa-square' },
                                    { name: 'circle', value: 'fas fa-circle' },
                                    { name: 'circle-outline', value: 'far fa-circle' },
                                    { name: 'arrow-right', value: 'fas fa-arrow-right' },
                                    { name: 'arrow-left', value: 'fas fa-arrow-left' },
                                    { name: 'arrow-circle-right', value: 'fas fa-arrow-circle-right' },
                                    { name: 'arrow-circle-left', value: 'fas fa-arrow-circle-left' },
                                    { name: 'arrow-alt-circle-right', value: 'far fa-arrow-alt-circle-right' },
                                    { name: 'arrow-alt-circle-left', value: 'far fa-arrow-alt-circle-left' },
                                    { name: 'long-arrow-alt-right', value: 'fas fa-long-arrow-alt-right' },
                                    { name: 'long-arrow-alt-left', value: 'fas fa-long-arrow-alt-left' },
                                    { name: 'chevron-right', value: 'fas fa-chevron-right' },
                                    { name: 'chevron-left', value: 'fas fa-chevron-left' },
                                    { name: 'angle-right', value: 'fas fa-angle-right' },
                                    { name: 'angle-left', value: 'fas fa-angle-left' },
                                    { name: 'star', value: 'fas fa-star' },
                                    { name: 'star-outline', value: 'far fa-star' },
                                    { name: 'windows-close-fill', value: 'fas fa-window-close' },
                                    { name: 'ban', value: 'fas fa-ban' },
                                    { name: 'window-close-simple', value: 'far fa-window-close' },
                                    { name: 'times', value: 'fas fa-times' },
                                    { name: 'times-circle', value: 'fas fa-times-circle' },
                                    { name: 'times-circle-simple', value: 'far fa-times-circle' },
                                    { name: 'dot-circle-fill', value: 'fas fa-dot-circle' },
                                    { name: 'dot-circle-simple', value: 'far fa-dot-circle' },
                                    { name: 'thumb-up-fill', value: 'fas fa-thumbs-up' },
                                    { name: 'thumb-up-simple', value: 'far fa-thumbs-up' },
                                    { name: 'thumb-down-fill', value: 'fas fa-thumbs-down' },
                                    { name: 'thumb-down-simple', value: 'far fa-thumbs-down' }, 
                                ]}
                                onChange={val => setAttributes({ bulletStyle4: val })}
                            />
                        </Fragment>
                    }
                    {listType4 == 'ordered' &&
                        <Fragment>
                            <Range label={__('Font Size')} value={numberFontSize4} onChange={(value) => setAttributes({ numberFontSize4: value })} min={10} max={100} />
                            <Toggle
                                value={useNumberBg4}
                                label={__('Use Background')}
                                onChange={val => setAttributes({ useNumberBg4: val, recreateStyles4: !recreateStyles4 })}
                            />
                            {
                                useNumberBg4 == 1 &&
                                <Fragment>
                                    <Range label={__('Background Size')} value={numberBgSize4} onChange={(value) => setAttributes({ numberBgSize4: value })} min={1} max={15} />
                                    <Range label={__('Corner')} value={numberCorner4} onChange={(value) => setAttributes({ numberCorner4: value })} min={0} max={100} />
                                </Fragment>
                            }
                        </Fragment>
                    }
                     

                    <Dropdown
                        className="affiliate-icon-popover"
                        contentClassName="my-popover-content-classname"
                        popoverProps={ { placement: 'bottom-start' } }
                        renderToggle={ ( { isOpen, onToggle } ) => (
                        <Fragment>
                            <h2>{__('Tag Icon')}</h2>
                            <Button className="affiliate-size-btn"  onClick={ onToggle } aria-expanded={ isOpen }>
                                <Dashicon icon="admin-tools" />
                            </Button>
                        </Fragment> 
                        ) }
                        renderContent={ () => (
                            <div className="affiliate-iconlist-popwrapper">
                                <IconList value={buttonIcon4} disableToggle onChange={val => setAttributes({ buttonIcon4: val })} />
                            </div>
                        ) }
                    />
                    
                    <RadioAdvanced
                          label={__('Icon Position')}
                          options={[
                              { label: 'Left', value: 'af-icon-is-left', title: 'Left' },
                              { label: 'Right', value: 'af-icon-is-right', title: 'Right' },
                          ]}
                          value={iconPosition4}
                          onChange={(value) => setAttributes({ iconPosition4: value })} />    

                    </Fragment>
                }      

                    <TextControl        
                        label = { __( 'Button Text' ) }             
                        value={ spBtnText4 }                        
                        onChange={ ( value ) => setAttributes( { spBtnText4: value } ) }
                    />
                    <TextControl
                        label = { __( 'Button URL' ) }                      
                        value={ spBtnHref4 }                        
                        onChange={ ( value ) => setAttributes( { spBtnHref4: value } ) }
                    />
                    <TextControl
                        label = { __( 'Button Rel' ) }                      
                        value={ btnRel4 }                       
                        onChange={ ( value ) => setAttributes( { btnRel4: value } ) }
                    />
                    <ToggleControl
                        label={ __( 'Open link in new Tab' ) }                       
                        checked={ btn_Htarget4 }                                        
                        onChange={ ( ) => setAttributes( { btn_Htarget4: ! btn_Htarget4 } ) }
                    />
                    <ToggleControl
                        label={ __( 'Rounded Button' ) }                        
                        checked={ bntRounded4 }                                     
                        onChange={ ( ) => setAttributes( { bntRounded4: ! bntRounded4 } ) }
                    />
                    <ToggleControl
                  label={ __( 'Enable Glimmer Effect' ) }            
                  checked={ enableGlimmerEffect4 }                    
                  onChange={ ( ) => setAttributes( { enableGlimmerEffect4: ! enableGlimmerEffect4 } ) }
              />
                    <RadioAdvanced
                            label={__('Button Size')}
                            options={[
                                { label: 'S', value: 'small', title: 'Small' },
                                { label: 'M', value: 'medium', title: 'Medium' },
                                { label: 'L', value: 'large', title: 'Large' },
                                { label: 'XL', value: 'extra-large', title: 'Extra Large' },
                            ]}
                            value={btnSize4}
                            onChange={(value) => setAttributes({ btnSize4: value })} />
                    <RadioAdvanced
                            label={__('Button Width')}
                            options={[
                                { label: 'Fixed', value: 'fixed', title: 'Fixed' },
                                { label: 'Flexible', value: 'flex', title: 'Flexible' },
                                { label: 'Full', value: 'full', title: 'Full' },
                            ]}
                            value={buttonWidth4}
                            onChange={(value) => setAttributes({ buttonWidth4: value })} />
                    <TypographyControl
                            label={__("Button Typography")}
                            attributes={attributes}
                            setAttributes={setAttributes}
                            loadGoogleFonts={{ value:btnLoadGoogleFonts4, label: __("btnLoadGoogleFonts4") }}
                            fontFamily={{ value: btnfontFamily4, label: __("btnfontFamily4") }}
                            fontWeight={{ value: btnfontWeight4, label: __("btnfontWeight4") }}
                            fontSubset={{ value: btnfontSubset4, label: __("btnfontSubset4") }}
                            fontSizeType={{ value: btnfontSizeType4, label: __("btnfontSizeType4") }}
                            fontSizeTypeMobile={{ value: btnfontSizeTypeMobile4, label: __("btnfontSizeTypeMobile4") }}
                            fontSizeTypeTablet={{ value: btnfontSizeTypeTablet4, label: __("btnfontSizeTypeTablet4") }}
                            fontSize={{ value: btnfontSizeDesktop4, label: __("btnfontSizeDesktop4") }}
                            fontSizeMobile={{ value: btnfontSizeMobile4, label: __("btnfontSizeMobile4") }}
                            fontSizeTablet={{ value: btnfontSizeTablet4, label: __("btnfontSizeTablet4") }}
                            lineHeightType={{ value: '', label: __(" ") }}
                            lineHeight={{ value: btnlineHeight4, label: __("btnlineHeight4") }}
                            lineHeightMobile={{ value: btnlineHeightMobile4, label: __("btnlineHeightMobile4") }}
                            lineHeightTablet={{ value: btnlineHeightTablet4, label: __("btnlineHeightTablet4") }}
                            letterSpacing={{ value: btnletterSpacing4, label: __("btnletterSpacing4") }}
                            letterSpacingMobile={{ value: btnletterSpacingMobile4, label: __("btnletterSpacingMobile4") }}
                            letterSpacingTablet={{ value: btnletterSpacingTablet4, label: __("btnletterSpacingTablet4") }}
                            letterSpacingType={{ value: btnletterSpacingType4, label: __("btnletterSpacingType4") }}
                            letterSpacingTypeTablet={{ value: btnletterSpacingTypeTablet4, label: __("btnletterSpacingTypeTablet4") }}
                            letterSpacingTypeMobile={{ value: btnletterSpacingTypeMobile4, label: __("btnletterSpacingTypeMobile4") }}
                            textTransform={btntextTransform4}
                            onTextTransform={(value) => setAttributes({ btntextTransform4: value })}
                            textDecoration={btntextDecoration4}
                            onTextDecoration={(value) => setAttributes({ btntextDecoration4: value })}
                            fontStyle={btnfontStyle4}
                            onFontStyle={(value) => setAttributes({ btnfontStyle4: value })}
                        />
                        <Tabs>
                            <Tab tabTitle={__('Normal')}>
                              <Color label={__('Text Color')} disableAlpha value={btnTextColor4} onChange={val => setAttributes({ btnTextColor4: val })} />
                              <Color label={__('Border Color')} disableAlpha value={btnBorderColor4} onChange={val => setAttributes({ btnBorderColor4: val })} />
                              <Background
                                      parallax
                                      value={btnBg4}
                                      label={__('Background')}
                                      externalImage
                                      sources={['color','gradient']}
                                      onChange={val => setAttributes({ btnBg4: val })} 
                                  />
                            </Tab>
                            <Tab tabTitle={__('Hover')}>
                              <Color label={__('Hover Text Color')} disableAlpha value={btnTextHoverColor4} onChange={val => setAttributes({ btnTextHoverColor4: val })} />
                              <Color label={__('Border Hover Color')} disableAlpha value={btnBorderHoverColor4} onChange={val => setAttributes({ btnBorderHoverColor4: val })} />
                              <Background
                                      parallax
                                      value={btnBgHover4}
                                      label={__('Background')}
                                      externalImage
                                      sources={['color','gradient']}
                                      onChange={val => setAttributes({ btnBgHover4: val })}
                                    />
                            </Tab>
                        </Tabs>
                            
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Padding')} value={btnPaddingMobile4}
                                        onChange={val => setAttributes({ btnPaddingMobile4: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Padding')} value={btnPaddingTablet4}
                                        onChange={val => setAttributes({ btnPaddingTablet4: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Padding')} value={btnPadding4}
                                        onChange={val => setAttributes({ btnPadding4: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Margin')}
                                        value={btnMarginMobile4}
                                        onChange={val => setAttributes({ btnMarginMobile4: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Margin')} value={btnMarginTablet4}
                                        onChange={val => setAttributes({ btnMarginMobile4: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Margin')} value={btnMargin4}
                                        onChange={val => setAttributes({ btnMargin4: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <BorderTypes label={__('Border Type')} value={btnBorderType4} onChange={(value) => setAttributes({ btnBorderType4: value })} />
                        
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Width')} value={btnBorderMobile4}
                                        onChange={val => setAttributes({ btnBorderMobile4: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Border Width')} 
                                        value={btnBorderTablet4}
                                        onChange={val => setAttributes({ btnBorderTablet4: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Width')} value={btnBorder4}
                                        onChange={val => setAttributes({ btnBorder4: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Radius')} value={btnBorderRadiusMobile4}
                                        onChange={val => setAttributes({ btnBorderRadiusMobile4: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Border Radius')} 
                                        value={btnBorderRadiusTablet4}
                                        onChange={val => setAttributes({ btnBorderRadiusTablet4: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Radius')} value={btnBorderRadius4}
                                        onChange={val => setAttributes({ btnBorderRadius4: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignmentMobile4}
                                          onChange={(value) => setAttributes({ btnAlignmentMobile4: value })}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignmentTablet4}
                                          onChange={(value) => setAttributes({ btnAlignmentTablet4: value })}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignment4}
                                          onChange={(value) => setAttributes({ btnAlignment4: value })}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TextareaControl              
                            label={ __( 'Amazon Price Shortcode' ) }         
                            value={ amazonprice4 }                       
                            onChange={ ( value ) => setAttributes( { amazonprice4: value } ) }
                        />
                        <SelectControl
                            label={ __( 'Show Amazon Price Position' ) }
                            value={ amazonpriceposition4 }
                            options= {[
                                    { value: 'Above Button', label: 'Above Button' },
                                    { value: 'Below Button', label: 'Below Button' },
                                ]}
                                onChange={ ( value ) => setAttributes( { amazonpriceposition4: value } ) }
                          />
                        <TextareaControl              
                            label={ __( 'Amazon Rating Shortcode' ) }         
                            value={ amazonrating4 }                       
                            onChange={ ( value ) => setAttributes( { amazonrating4: value } ) }
                        />
                        <SelectControl
                            label={ __( 'Show Amazon Rating Position' ) }
                            value={ amazonratingposition4 }
                            options= {[
                                    { value: 'Above Button', label: 'Above Button' },
                                    { value: 'Below Button', label: 'Below Button' },
                                ]}
                                onChange={ ( value ) => setAttributes( { amazonratingposition4: value } ) }
                          />

                </PanelBody>
                }


                { numberOfBlock && numberOfBlock >= '5' &&
                <PanelBody title={`Box 5 Settings`} initialOpen={false} icon={ getIcon( 'layout' ) } className={ 'abblocks-panel-label' }>

                    <ToggleControl
                          label={ __( 'External Image?' ) }                        
                          checked={ externalImage5 }                                      
                          onChange={ ( ) => setAttributes( { externalImage5: ! externalImage5 } ) }
                      />

                    { externalImage5 && externalImage5 == true &&
                        <Fragment>
                      <TextareaControl              
                          label={ __( 'External Image Src' ) }         
                          value={ externalImageSrc5 }                       
                          onChange={ ( value ) => setAttributes( { externalImageSrc5: value } ) }
                      />
                      <TextControl              
                            label={ __( 'External Image Alt' ) }         
                            value={ externalImageAlt5 }                       
                            onChange={ ( value ) => setAttributes( { externalImageAlt5: value } ) }
                        />
                        </Fragment>
                    }

                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                          activeClass="active-tab"
                          tabs={[
                            {
                              name: 'boxdesk',
                              title: <Dashicon icon="desktop" />,
                              className: 'affiliate-responsive-tabs',
                            },
                            {
                              name: 'boxtablet',
                              title: <Dashicon icon="tablet" />,
                              className: 'affiliate-responsive-tabs',
                            },
                            {
                              name: 'boxmobile',
                              title: <Dashicon icon="smartphone" />,
                              className: 'affiliate-responsive-tabs',
                            },
                          ]}>
                          {
                            (radiustab) => {
                              let tabout;
                              if (radiustab.name) {
                                if ('boxmobile' === radiustab.name) {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidthMobile5} onChange={(value) => setAttributes({ customImgWidthMobile5: value })} min={0} max={1000} unit={['']} />
                                  );
                                } else if ('boxtablet' === radiustab.name) {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidthTablet5} onChange={(value) => setAttributes({ customImgWidthTablet5: value })} min={0} max={1000} unit={['']} />
                                  );
                                } else {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidth5} onChange={(value) => setAttributes({ customImgWidth5: value })} min={0} max={1000} unit={['']} />
                                  );
                                }
                              }
                              return <div>{tabout}</div>;
                            }
                          }
                    </TabPanel>
                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                        activeClass="active-tab"
                        tabs={[
                          {
                            name: 'boxdesk',
                            title: <Dashicon icon="desktop" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxtablet',
                            title: <Dashicon icon="tablet" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxmobile',
                            title: <Dashicon icon="smartphone" />,
                            className: 'affiliate-responsive-tabs',
                          },
                        ]}>
                        {
                          (radiustab) => {
                            let tabout;
                            if (radiustab.name) {
                              if ('boxmobile' === radiustab.name) {
                                tabout = (
                                  <Dimension
                                    label={__('Image Padding')} value={imagePaddingMobile5}
                                    onChange={val => setAttributes({ imagePaddingMobile5: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                  <Dimension label={__('Image Padding')} value={imagePaddingTablet5}
                                    onChange={val => setAttributes({ imagePaddingTablet5: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else {
                                tabout = (
                                  <Dimension
                                    label={__('Image Padding')} value={imagePadding5}
                                    onChange={val => setAttributes({ imagePadding5: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              }
                            }
                            return <div>{tabout}</div>;
                          }
                        }
                    </TabPanel> 

                    <Color label={__('Background Color')} disableAlpha value={boxBgColor5} onChange={val => setAttributes({ boxBgColor5: val })} />    

                    { productColumnStyle && productColumnStyle == 'layout1' &&
                        <SelectControl
                            label={ __( 'Flag Style' ) }
                            value={ flagStyle5 }
                            options= {[
                                    { value: '', label: 'Select' },
                                    { value: 'flag1', label: 'Budget Pick' },
                                    { value: 'flag2', label: 'Best Value' },
                                    { value: 'flag3', label: 'Premium Pick' },
                                ]}
                                onChange={ ( value ) => setAttributes( { flagStyle5: value } ) }
                        />    
                    }

                    <SelectControl
                        label={ __( 'Title Heading Tag' ) }
                        value={ titleTag5 }
                        options= {[
                                { value: 'h2', label: 'Heading 2 (h2)' },
                                { value: 'h3', label: 'Heading 3 (h3)' },
                                { value: 'h4', label: 'Heading 4 (h4)' },
                                { value: 'h5', label: 'Heading 5 (h5)' },
                                { value: 'h6', label: 'Heading 6 (h6)' },
                                { value: 'p', label: 'Paragraph (p)' },
                            ]}
                            onChange={ ( value ) => setAttributes( { titleTag5: value } ) }
                    />   

                     
                    
                    { productColumnStyle && productColumnStyle == 'layout1' &&
                    <Fragment>
                    
                    <RangeControl label={__("Number of stars")} value={ratings5} onChange={value => setAttributes({ ratings5: value, selectedStars5: value < selectedStars5 ? value : selectedStars5 }) } min={5} step={1} max={10}  allowReset />
                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                        activeClass="active-tab"
                        tabs={[
                          {
                            name: 'boxdesk',
                            title: <Dashicon icon="desktop" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxtablet',
                            title: <Dashicon icon="tablet" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxmobile',
                            title: <Dashicon icon="smartphone" />,
                            className: 'affiliate-responsive-tabs',
                          },
                        ]}>
                        {
                          (radiustab) => {
                            let tabout;
                            if (radiustab.name) {
                              if ('boxmobile' === radiustab.name) {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSizeMobile5}  onChange={(value) => setAttributes({ starsSizeMobile5: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSizeTablet5}  onChange={(value) => setAttributes({ starsSizeTablet5: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              } else {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSize5}  onChange={(value) => setAttributes({ starsSize5: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              }
                            }
                            return <div>{tabout}</div>;
                          }
                        }
                    </TabPanel>

                    {listType5 == 'unordered' &&
                        <Fragment>
                            <IconSelector
                                label="Top List Icon"
                                value={bulletStyle5.name}
                                enableSearch
                                icons={[
                                    { name: 'check', value: 'fas fa-check' },
                                    { name: 'check-square', value: 'fas fa-check-square' },
                                    { name: 'check-square-outline', value: 'far fa-check-square' },
                                    { name: 'check-double', value: 'fas fa-check-double' },
                                    { name: 'check-circle', value: 'fas fa-check-circle' },
                                    { name: 'check-circle-outline', value: 'far fa-check-circle' },
                                    { name: 'square', value: 'fas fa-square' },
                                    { name: 'square-outline', value: 'far fa-square' },
                                    { name: 'circle', value: 'fas fa-circle' },
                                    { name: 'circle-outline', value: 'far fa-circle' },
                                    { name: 'arrow-right', value: 'fas fa-arrow-right' },
                                    { name: 'arrow-left', value: 'fas fa-arrow-left' },
                                    { name: 'arrow-circle-right', value: 'fas fa-arrow-circle-right' },
                                    { name: 'arrow-circle-left', value: 'fas fa-arrow-circle-left' },
                                    { name: 'arrow-alt-circle-right', value: 'far fa-arrow-alt-circle-right' },
                                    { name: 'arrow-alt-circle-left', value: 'far fa-arrow-alt-circle-left' },
                                    { name: 'long-arrow-alt-right', value: 'fas fa-long-arrow-alt-right' },
                                    { name: 'long-arrow-alt-left', value: 'fas fa-long-arrow-alt-left' },
                                    { name: 'chevron-right', value: 'fas fa-chevron-right' },
                                    { name: 'chevron-left', value: 'fas fa-chevron-left' },
                                    { name: 'angle-right', value: 'fas fa-angle-right' },
                                    { name: 'angle-left', value: 'fas fa-angle-left' },
                                    { name: 'star', value: 'fas fa-star' },
                                    { name: 'star-outline', value: 'far fa-star' },
                                    { name: 'windows-close-fill', value: 'fas fa-window-close' },
                                    { name: 'ban', value: 'fas fa-ban' },
                                    { name: 'window-close-simple', value: 'far fa-window-close' },
                                    { name: 'times', value: 'fas fa-times' },
                                    { name: 'times-circle', value: 'fas fa-times-circle' },
                                    { name: 'times-circle-simple', value: 'far fa-times-circle' },
                                    { name: 'dot-circle-fill', value: 'fas fa-dot-circle' },
                                    { name: 'dot-circle-simple', value: 'far fa-dot-circle' },
                                    { name: 'thumb-up-fill', value: 'fas fa-thumbs-up' },
                                    { name: 'thumb-up-simple', value: 'far fa-thumbs-up' },
                                    { name: 'thumb-down-fill', value: 'fas fa-thumbs-down' },
                                    { name: 'thumb-down-simple', value: 'far fa-thumbs-down' }, 
                                ]}
                                onChange={val => setAttributes({ bulletStyle5: val })}
                            />
                        </Fragment>
                    }
                    {listType5 == 'ordered' &&
                        <Fragment>
                            <Range label={__('Font Size')} value={numberFontSize5} onChange={(value) => setAttributes({ numberFontSize5: value })} min={10} max={100} />
                            <Toggle
                                value={useNumberBg5}
                                label={__('Use Background')}
                                onChange={val => setAttributes({ useNumberBg5: val, recreateStyles5: !recreateStyles5 })}
                            />
                            {
                                useNumberBg5 == 1 &&
                                <Fragment>
                                    <Range label={__('Background Size')} value={numberBgSize5} onChange={(value) => setAttributes({ numberBgSize5: value })} min={1} max={15} />
                                    <Range label={__('Corner')} value={numberCorner5} onChange={(value) => setAttributes({ numberCorner5: value })} min={0} max={100} />
                                </Fragment>
                            }
                        </Fragment>
                    }
                    

                    <Dropdown
                        className="affiliate-icon-popover"
                        contentClassName="my-popover-content-classname"
                        popoverProps={ { placement: 'bottom-start' } }
                        renderToggle={ ( { isOpen, onToggle } ) => (
                        <Fragment>
                            <h2>{__('Tag Icon')}</h2>
                            <Button className="affiliate-size-btn"  onClick={ onToggle } aria-expanded={ isOpen }>
                                <Dashicon icon="admin-tools" />
                            </Button>
                        </Fragment> 
                        ) }
                        renderContent={ () => (
                            <div className="affiliate-iconlist-popwrapper">
                                <IconList value={buttonIcon5} disableToggle onChange={val => setAttributes({ buttonIcon5: val })} />
                            </div>
                        ) }
                    />
                    
                    <RadioAdvanced
                          label={__('Icon Position')}
                          options={[
                              { label: 'Left', value: 'af-icon-is-left', title: 'Left' },
                              { label: 'Right', value: 'af-icon-is-right', title: 'Right' },
                          ]}
                          value={iconPosition5}
                          onChange={(value) => setAttributes({ iconPosition5: value })} />          

                    </Fragment>
                }

                    <TextControl        
                        label = { __( 'Button Text' ) }             
                        value={ spBtnText5 }                        
                        onChange={ ( value ) => setAttributes( { spBtnText5: value } ) }
                    />
                    <TextControl
                        label = { __( 'Button URL' ) }                      
                        value={ spBtnHref5 }                        
                        onChange={ ( value ) => setAttributes( { spBtnHref5: value } ) }
                    />
                    <TextControl
                        label = { __( 'Button Rel' ) }                      
                        value={ btnRel5 }                       
                        onChange={ ( value ) => setAttributes( { btnRel5: value } ) }
                    />
                    <ToggleControl
                        label={ __( 'Open link in new Tab' ) }                       
                        checked={ btn_Htarget5 }                                        
                        onChange={ ( ) => setAttributes( { btn_Htarget5: ! btn_Htarget5 } ) }
                    />
                    <ToggleControl
                        label={ __( 'Rounded Button' ) }                        
                        checked={ bntRounded5 }                                     
                        onChange={ ( ) => setAttributes( { bntRounded5: ! bntRounded5 } ) }
                    />
                    <ToggleControl
                  label={ __( 'Enable Glimmer Effect' ) }            
                  checked={ enableGlimmerEffect5 }                    
                  onChange={ ( ) => setAttributes( { enableGlimmerEffect5: ! enableGlimmerEffect5 } ) }
              />
                    <RadioAdvanced
                            label={__('Button Size')}
                            options={[
                                { label: 'S', value: 'small', title: 'Small' },
                                { label: 'M', value: 'medium', title: 'Medium' },
                                { label: 'L', value: 'large', title: 'Large' },
                                { label: 'XL', value: 'extra-large', title: 'Extra Large' },
                            ]}
                            value={btnSize5}
                            onChange={(value) => setAttributes({ btnSize5: value })} />
                    <RadioAdvanced
                            label={__('Button Width')}
                            options={[
                                { label: 'Fixed', value: 'fixed', title: 'Fixed' },
                                { label: 'Flexible', value: 'flex', title: 'Flexible' },
                                { label: 'Full', value: 'full', title: 'Full' },
                            ]}
                            value={buttonWidth5}
                            onChange={(value) => setAttributes({ buttonWidth5: value })} />
                    <TypographyControl
                            label={__("Button Typography")}
                            attributes={attributes}
                            setAttributes={setAttributes}
                            loadGoogleFonts={{ value:btnLoadGoogleFonts5, label: __("btnLoadGoogleFonts5") }}
                            fontFamily={{ value: btnfontFamily5, label: __("btnfontFamily5") }}
                            fontWeight={{ value: btnfontWeight5, label: __("btnfontWeight5") }}
                            fontSubset={{ value: btnfontSubset5, label: __("btnfontSubset5") }}
                            fontSizeType={{ value: btnfontSizeType5, label: __("btnfontSizeType5") }}
                            fontSizeTypeMobile={{ value: btnfontSizeTypeMobile5, label: __("btnfontSizeTypeMobile5") }}
                            fontSizeTypeTablet={{ value: btnfontSizeTypeTablet5, label: __("btnfontSizeTypeTablet5") }}
                            fontSize={{ value: btnfontSizeDesktop5, label: __("btnfontSizeDesktop5") }}
                            fontSizeMobile={{ value: btnfontSizeMobile5, label: __("btnfontSizeMobile5") }}
                            fontSizeTablet={{ value: btnfontSizeTablet5, label: __("btnfontSizeTablet5") }}
                            lineHeightType={{ value: '', label: __(" ") }}
                            lineHeight={{ value: btnlineHeight5, label: __("btnlineHeight5") }}
                            lineHeightMobile={{ value: btnlineHeightMobile5, label: __("btnlineHeightMobile5") }}
                            lineHeightTablet={{ value: btnlineHeightTablet5, label: __("btnlineHeightTablet5") }}
                            letterSpacing={{ value: btnletterSpacing5, label: __("btnletterSpacing5") }}
                            letterSpacingMobile={{ value: btnletterSpacingMobile5, label: __("btnletterSpacingMobile5") }}
                            letterSpacingTablet={{ value: btnletterSpacingTablet5, label: __("btnletterSpacingTablet5") }}
                            letterSpacingType={{ value: btnletterSpacingType5, label: __("btnletterSpacingType5") }}
                            letterSpacingTypeTablet={{ value: btnletterSpacingTypeTablet5, label: __("btnletterSpacingTypeTablet5") }}
                            letterSpacingTypeMobile={{ value: btnletterSpacingTypeMobile5, label: __("btnletterSpacingTypeMobile5") }}
                            textTransform={btntextTransform5}
                            onTextTransform={(value) => setAttributes({ btntextTransform5: value })}
                            textDecoration={btntextDecoration5}
                            onTextDecoration={(value) => setAttributes({ btntextDecoration5: value })}
                            fontStyle={btnfontStyle5}
                            onFontStyle={(value) => setAttributes({ btnfontStyle5: value })}
                        />
                        <Tabs>
                            <Tab tabTitle={__('Normal')}>
                              <Color label={__('Text Color')} disableAlpha value={btnTextColor5} onChange={val => setAttributes({ btnTextColor5: val })} />
                              <Color label={__('Border Color')} disableAlpha value={btnBorderColor5} onChange={val => setAttributes({ btnBorderColor5: val })} />
                              <Background
                                      parallax
                                      value={btnBg5}
                                      label={__('Background')}
                                      externalImage
                                      sources={['color','gradient']}
                                      onChange={val => setAttributes({ btnBg5: val })} 
                                  />
                            </Tab>
                            <Tab tabTitle={__('Hover')}>
                              <Color label={__('Hover Text Color')} disableAlpha value={btnTextHoverColor5} onChange={val => setAttributes({ btnTextHoverColor5: val })} />
                              <Color label={__('Border Hover Color')} disableAlpha value={btnBorderHoverColor5} onChange={val => setAttributes({ btnBorderHoverColor5: val })} />
                              <Background
                                      parallax
                                      value={btnBgHover5}
                                      label={__('Background')}
                                      externalImage
                                      sources={['color','gradient']}
                                      onChange={val => setAttributes({ btnBgHover5: val })}
                                    />
                            </Tab>
                        </Tabs>
                            
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Padding')} value={btnPaddingMobile5}
                                        onChange={val => setAttributes({ btnPaddingMobile5: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Padding')} value={btnPaddingTablet5}
                                        onChange={val => setAttributes({ btnPaddingTablet5: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Padding')} value={btnPadding5}
                                        onChange={val => setAttributes({ btnPadding5: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Margin')}
                                        value={btnMarginMobile5}
                                        onChange={val => setAttributes({ btnMarginMobile5: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Margin')} value={btnMarginTablet5}
                                        onChange={val => setAttributes({ btnMarginMobile5: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Margin')} value={btnMargin5}
                                        onChange={val => setAttributes({ btnMargin5: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <BorderTypes label={__('Border Type')} value={btnBorderType5} onChange={(value) => setAttributes({ btnBorderType5: value })} />
                        
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Width')} value={btnBorderMobile5}
                                        onChange={val => setAttributes({ btnBorderMobile5: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Border Width')} 
                                        value={btnBorderTablet5}
                                        onChange={val => setAttributes({ btnBorderTablet5: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Width')} value={btnBorder5}
                                        onChange={val => setAttributes({ btnBorder5: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Radius')} value={btnBorderRadiusMobile5}
                                        onChange={val => setAttributes({ btnBorderRadiusMobile5: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Border Radius')} 
                                        value={btnBorderRadiusTablet5}
                                        onChange={val => setAttributes({ btnBorderRadiusTablet5: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Radius')} value={btnBorderRadius5}
                                        onChange={val => setAttributes({ btnBorderRadius5: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignmentMobile5}
                                          onChange={(value) => setAttributes({ btnAlignmentMobile5: value })}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignmentTablet5}
                                          onChange={(value) => setAttributes({ btnAlignmentTablet5: value })}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignment5}
                                          onChange={(value) => setAttributes({ btnAlignment5: value })}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TextareaControl              
                            label={ __( 'Amazon Price Shortcode' ) }         
                            value={ amazonprice5 }                       
                            onChange={ ( value ) => setAttributes( { amazonprice5: value } ) }
                        />
                        <SelectControl
                            label={ __( 'Show Amazon Price Position' ) }
                            value={ amazonpriceposition5 }
                            options= {[
                                    { value: 'Above Button', label: 'Above Button' },
                                    { value: 'Below Button', label: 'Below Button' },
                                ]}
                                onChange={ ( value ) => setAttributes( { amazonpriceposition5: value } ) }
                          />
                        <TextareaControl              
                            label={ __( 'Amazon Rating Shortcode' ) }         
                            value={ amazonrating5 }                       
                            onChange={ ( value ) => setAttributes( { amazonrating5: value } ) }
                        />
                        <SelectControl
                            label={ __( 'Show Amazon Rating Position' ) }
                            value={ amazonratingposition5 }
                            options= {[
                                    { value: 'Above Button', label: 'Above Button' },
                                    { value: 'Below Button', label: 'Below Button' },
                                ]}
                                onChange={ ( value ) => setAttributes( { amazonratingposition5: value } ) }
                          />
                        

                </PanelBody>
                }



                { numberOfBlock && numberOfBlock >= '6' &&
                <PanelBody title={`Box 6 Settings`} initialOpen={false} icon={ getIcon( 'layout' ) } className={ 'abblocks-panel-label' }>

                    <ToggleControl
                          label={ __( 'External Image?' ) }                        
                          checked={ externalImage6 }                                      
                          onChange={ ( ) => setAttributes( { externalImage6: ! externalImage6 } ) }
                      />

                    { externalImage6 && externalImage6 == true &&
                        <Fragment>
                      <TextareaControl              
                          label={ __( 'External Image Src' ) }         
                          value={ externalImageSrc6 }                       
                          onChange={ ( value ) => setAttributes( { externalImageSrc6: value } ) }
                      />
                      <TextControl              
                            label={ __( 'External Image Alt' ) }         
                            value={ externalImageAlt6 }                       
                            onChange={ ( value ) => setAttributes( { externalImageAlt6: value } ) }
                        />
                        </Fragment>
                    }

                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                          activeClass="active-tab"
                          tabs={[
                            {
                              name: 'boxdesk',
                              title: <Dashicon icon="desktop" />,
                              className: 'affiliate-responsive-tabs',
                            },
                            {
                              name: 'boxtablet',
                              title: <Dashicon icon="tablet" />,
                              className: 'affiliate-responsive-tabs',
                            },
                            {
                              name: 'boxmobile',
                              title: <Dashicon icon="smartphone" />,
                              className: 'affiliate-responsive-tabs',
                            },
                          ]}>
                          {
                            (radiustab) => {
                              let tabout;
                              if (radiustab.name) {
                                if ('boxmobile' === radiustab.name) {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidthMobile6} onChange={(value) => setAttributes({ customImgWidthMobile6: value })} min={0} max={1000} unit={['']} />
                                  );
                                } else if ('boxtablet' === radiustab.name) {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidthTablet6} onChange={(value) => setAttributes({ customImgWidthTablet6: value })} min={0} max={1000} unit={['']} />
                                  );
                                } else {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidth6} onChange={(value) => setAttributes({ customImgWidth6: value })} min={0} max={1000} unit={['']} />
                                  );
                                }
                              }
                              return <div>{tabout}</div>;
                            }
                          }
                    </TabPanel>
                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                        activeClass="active-tab"
                        tabs={[
                          {
                            name: 'boxdesk',
                            title: <Dashicon icon="desktop" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxtablet',
                            title: <Dashicon icon="tablet" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxmobile',
                            title: <Dashicon icon="smartphone" />,
                            className: 'affiliate-responsive-tabs',
                          },
                        ]}>
                        {
                          (radiustab) => {
                            let tabout;
                            if (radiustab.name) {
                              if ('boxmobile' === radiustab.name) {
                                tabout = (
                                  <Dimension
                                    label={__('Image Padding')} value={imagePaddingMobile6}
                                    onChange={val => setAttributes({ imagePaddingMobile6: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                  <Dimension label={__('Image Padding')} value={imagePaddingTablet6}
                                    onChange={val => setAttributes({ imagePaddingTablet6: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else {
                                tabout = (
                                  <Dimension
                                    label={__('Image Padding')} value={imagePadding6}
                                    onChange={val => setAttributes({ imagePadding6: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              }
                            }
                            return <div>{tabout}</div>;
                          }
                        }
                    </TabPanel> 

                    <Color label={__('Background Color')} disableAlpha value={boxBgColor6} onChange={val => setAttributes({ boxBgColor6: val })} />    

                    { productColumnStyle && productColumnStyle == 'layout1' &&
                        <SelectControl
                            label={ __( 'Flag Style' ) }
                            value={ flagStyle6 }
                            options= {[
                                    { value: '', label: 'Select' },
                                    { value: 'flag1', label: 'Budget Pick' },
                                    { value: 'flag2', label: 'Best Value' },
                                    { value: 'flag3', label: 'Premium Pick' },
                                ]}
                                onChange={ ( value ) => setAttributes( { flagStyle6: value } ) }
                        />    
                    }

                    <SelectControl
                        label={ __( 'Title Heading Tag' ) }
                        value={ titleTag6 }
                        options= {[
                                { value: 'h2', label: 'Heading 2 (h2)' },
                                { value: 'h3', label: 'Heading 3 (h3)' },
                                { value: 'h4', label: 'Heading 4 (h4)' },
                                { value: 'h5', label: 'Heading 5 (h5)' },
                                { value: 'h6', label: 'Heading 6 (h6)' },
                                { value: 'p', label: 'Paragraph (p)' },
                            ]}
                            onChange={ ( value ) => setAttributes( { titleTag6: value } ) }
                    />   

                     
                    
                    { productColumnStyle && productColumnStyle == 'layout1' &&
                    <Fragment>
                    
                    <RangeControl label={__("Number of stars")} value={ratings6} onChange={value => setAttributes({ ratings6: value, selectedStars6: value < selectedStars6 ? value : selectedStars6 }) } min={5} step={1} max={10} allowReset />
                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                        activeClass="active-tab"
                        tabs={[
                          {
                            name: 'boxdesk',
                            title: <Dashicon icon="desktop" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxtablet',
                            title: <Dashicon icon="tablet" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxmobile',
                            title: <Dashicon icon="smartphone" />,
                            className: 'affiliate-responsive-tabs',
                          },
                        ]}>
                        {
                          (radiustab) => {
                            let tabout;
                            if (radiustab.name) {
                              if ('boxmobile' === radiustab.name) {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSizeMobile6}  onChange={(value) => setAttributes({ starsSizeMobile6: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSizeTablet6}  onChange={(value) => setAttributes({ starsSizeTablet6: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              } else {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSize6}  onChange={(value) => setAttributes({ starsSize6: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              }
                            }
                            return <div>{tabout}</div>;
                          }
                        }
                    </TabPanel>

                    {listType6 == 'unordered' &&
                        <Fragment>
                            <IconSelector
                                label="Top List Icon"
                                value={bulletStyle6.name}
                                enableSearch
                                icons={[
                                    { name: 'check', value: 'fas fa-check' },
                                    { name: 'check-square', value: 'fas fa-check-square' },
                                    { name: 'check-square-outline', value: 'far fa-check-square' },
                                    { name: 'check-double', value: 'fas fa-check-double' },
                                    { name: 'check-circle', value: 'fas fa-check-circle' },
                                    { name: 'check-circle-outline', value: 'far fa-check-circle' },
                                    { name: 'square', value: 'fas fa-square' },
                                    { name: 'square-outline', value: 'far fa-square' },
                                    { name: 'circle', value: 'fas fa-circle' },
                                    { name: 'circle-outline', value: 'far fa-circle' },
                                    { name: 'arrow-right', value: 'fas fa-arrow-right' },
                                    { name: 'arrow-left', value: 'fas fa-arrow-left' },
                                    { name: 'arrow-circle-right', value: 'fas fa-arrow-circle-right' },
                                    { name: 'arrow-circle-left', value: 'fas fa-arrow-circle-left' },
                                    { name: 'arrow-alt-circle-right', value: 'far fa-arrow-alt-circle-right' },
                                    { name: 'arrow-alt-circle-left', value: 'far fa-arrow-alt-circle-left' },
                                    { name: 'long-arrow-alt-right', value: 'fas fa-long-arrow-alt-right' },
                                    { name: 'long-arrow-alt-left', value: 'fas fa-long-arrow-alt-left' },
                                    { name: 'chevron-right', value: 'fas fa-chevron-right' },
                                    { name: 'chevron-left', value: 'fas fa-chevron-left' },
                                    { name: 'angle-right', value: 'fas fa-angle-right' },
                                    { name: 'angle-left', value: 'fas fa-angle-left' },
                                    { name: 'star', value: 'fas fa-star' },
                                    { name: 'star-outline', value: 'far fa-star' },
                                    { name: 'windows-close-fill', value: 'fas fa-window-close' },
                                    { name: 'ban', value: 'fas fa-ban' },
                                    { name: 'window-close-simple', value: 'far fa-window-close' },
                                    { name: 'times', value: 'fas fa-times' },
                                    { name: 'times-circle', value: 'fas fa-times-circle' },
                                    { name: 'times-circle-simple', value: 'far fa-times-circle' },
                                    { name: 'dot-circle-fill', value: 'fas fa-dot-circle' },
                                    { name: 'dot-circle-simple', value: 'far fa-dot-circle' },
                                    { name: 'thumb-up-fill', value: 'fas fa-thumbs-up' },
                                    { name: 'thumb-up-simple', value: 'far fa-thumbs-up' },
                                    { name: 'thumb-down-fill', value: 'fas fa-thumbs-down' },
                                    { name: 'thumb-down-simple', value: 'far fa-thumbs-down' }, 
                                ]}
                                onChange={val => setAttributes({ bulletStyle6: val })}
                            />
                        </Fragment>
                    }
                    {listType6 == 'ordered' &&
                        <Fragment>
                            <Range label={__('Font Size')} value={numberFontSize6} onChange={(value) => setAttributes({ numberFontSize6: value })} min={10} max={100} />
                            <Toggle
                                value={useNumberBg6}
                                label={__('Use Background')}
                                onChange={val => setAttributes({ useNumberBg6: val, recreateStyles6: !recreateStyles6 })}
                            />
                            {
                                useNumberBg6 == 1 &&
                                <Fragment>
                                    <Range label={__('Background Size')} value={numberBgSize6} onChange={(value) => setAttributes({ numberBgSize6: value })} min={1} max={15} />
                                    <Range label={__('Corner')} value={numberCorner6} onChange={(value) => setAttributes({ numberCorner6: value })} min={0} max={100} />
                                </Fragment>
                            }
                        </Fragment>
                    }
                   

                    <Dropdown
                        className="affiliate-icon-popover"
                        contentClassName="my-popover-content-classname"
                        popoverProps={ { placement: 'bottom-start' } }
                        renderToggle={ ( { isOpen, onToggle } ) => (
                        <Fragment>
                            <h2>{__('Tag Icon')}</h2>
                            <Button className="affiliate-size-btn"  onClick={ onToggle } aria-expanded={ isOpen }>
                                <Dashicon icon="admin-tools" />
                            </Button>
                        </Fragment> 
                        ) }
                        renderContent={ () => (
                            <div className="affiliate-iconlist-popwrapper">
                                <IconList value={buttonIcon6} disableToggle onChange={val => setAttributes({ buttonIcon6: val })} />
                            </div>
                        ) }
                    />
                    
                    <RadioAdvanced
                          label={__('Icon Position')}
                          options={[
                              { label: 'Left', value: 'af-icon-is-left', title: 'Left' },
                              { label: 'Right', value: 'af-icon-is-right', title: 'Right' },
                          ]}
                          value={iconPosition6}
                          onChange={(value) => setAttributes({ iconPosition6: value })} />     

                    </Fragment>
                    }     

                    <TextControl        
                        label = { __( 'Button Text' ) }             
                        value={ spBtnText6 }                        
                        onChange={ ( value ) => setAttributes( { spBtnText6: value } ) }
                    />
                    <TextControl
                        label = { __( 'Button URL' ) }                      
                        value={ spBtnHref6 }                        
                        onChange={ ( value ) => setAttributes( { spBtnHref6: value } ) }
                    />
                    <TextControl
                        label = { __( 'Button Rel' ) }                      
                        value={ btnRel6 }                       
                        onChange={ ( value ) => setAttributes( { btnRel6: value } ) }
                    />
                    <ToggleControl
                        label={ __( 'Open link in new Tab' ) }                       
                        checked={ btn_Htarget6 }                                        
                        onChange={ ( ) => setAttributes( { btn_Htarget6: ! btn_Htarget6 } ) }
                    />
                    <ToggleControl
                        label={ __( 'Rounded Button' ) }                        
                        checked={ bntRounded6 }                                     
                        onChange={ ( ) => setAttributes( { bntRounded6: ! bntRounded6 } ) }
                    />
                    <ToggleControl
                  label={ __( 'Enable Glimmer Effect' ) }            
                  checked={ enableGlimmerEffect6 }                    
                  onChange={ ( ) => setAttributes( { enableGlimmerEffect6: ! enableGlimmerEffect6 } ) }
              />
                    <RadioAdvanced
                            label={__('Button Size')}
                            options={[
                                { label: 'S', value: 'small', title: 'Small' },
                                { label: 'M', value: 'medium', title: 'Medium' },
                                { label: 'L', value: 'large', title: 'Large' },
                                { label: 'XL', value: 'extra-large', title: 'Extra Large' },
                            ]}
                            value={btnSize6}
                            onChange={(value) => setAttributes({ btnSize6: value })} />
                    <RadioAdvanced
                            label={__('Button Width')}
                            options={[
                                { label: 'Fixed', value: 'fixed', title: 'Fixed' },
                                { label: 'Flexible', value: 'flex', title: 'Flexible' },
                                { label: 'Full', value: 'full', title: 'Full' },
                            ]}
                            value={buttonWidth6}
                            onChange={(value) => setAttributes({ buttonWidth6: value })} />
                    <TypographyControl
                            label={__("Button Typography")}
                            attributes={attributes}
                            setAttributes={setAttributes}
                            loadGoogleFonts={{ value:btnLoadGoogleFonts6, label: __("btnLoadGoogleFonts6") }}
                            fontFamily={{ value: btnfontFamily6, label: __("btnfontFamily6") }}
                            fontWeight={{ value: btnfontWeight6, label: __("btnfontWeight6") }}
                            fontSubset={{ value: btnfontSubset6, label: __("btnfontSubset6") }}
                            fontSizeType={{ value: btnfontSizeType6, label: __("btnfontSizeType6") }}
                            fontSizeTypeMobile={{ value: btnfontSizeTypeMobile6, label: __("btnfontSizeTypeMobile6") }}
                            fontSizeTypeTablet={{ value: btnfontSizeTypeTablet6, label: __("btnfontSizeTypeTablet6") }}
                            fontSize={{ value: btnfontSizeDesktop6, label: __("btnfontSizeDesktop6") }}
                            fontSizeMobile={{ value: btnfontSizeMobile6, label: __("btnfontSizeMobile6") }}
                            fontSizeTablet={{ value: btnfontSizeTablet6, label: __("btnfontSizeTablet6") }}
                            lineHeightType={{ value: '', label: __(" ") }}
                            lineHeight={{ value: btnlineHeight6, label: __("btnlineHeight6") }}
                            lineHeightMobile={{ value: btnlineHeightMobile6, label: __("btnlineHeightMobile6") }}
                            lineHeightTablet={{ value: btnlineHeightTablet6, label: __("btnlineHeightTablet6") }}
                            letterSpacing={{ value: btnletterSpacing6, label: __("btnletterSpacing6") }}
                            letterSpacingMobile={{ value: btnletterSpacingMobile6, label: __("btnletterSpacingMobile6") }}
                            letterSpacingTablet={{ value: btnletterSpacingTablet6, label: __("btnletterSpacingTablet6") }}
                            letterSpacingType={{ value: btnletterSpacingType6, label: __("btnletterSpacingType6") }}
                            letterSpacingTypeTablet={{ value: btnletterSpacingTypeTablet6, label: __("btnletterSpacingTypeTablet6") }}
                            letterSpacingTypeMobile={{ value: btnletterSpacingTypeMobile6, label: __("btnletterSpacingTypeMobile6") }}
                            textTransform={btntextTransform6}
                            onTextTransform={(value) => setAttributes({ btntextTransform6: value })}
                            textDecoration={btntextDecoration6}
                            onTextDecoration={(value) => setAttributes({ btntextDecoration6: value })}
                            fontStyle={btnfontStyle6}
                            onFontStyle={(value) => setAttributes({ btnfontStyle6: value })}
                        />
                        <Tabs>
                            <Tab tabTitle={__('Normal')}>
                              <Color label={__('Text Color')} disableAlpha value={btnTextColor6} onChange={val => setAttributes({ btnTextColor6: val })} />
                              <Color label={__('Border Color')} disableAlpha value={btnBorderColor6} onChange={val => setAttributes({ btnBorderColor6: val })} />
                              <Background
                                      parallax
                                      value={btnBg6}
                                      label={__('Background')}
                                      externalImage
                                      sources={['color','gradient']}
                                      onChange={val => setAttributes({ btnBg6: val })} 
                                  />
                            </Tab>
                            <Tab tabTitle={__('Hover')}>
                              <Color label={__('Hover Text Color')} disableAlpha value={btnTextHoverColor6} onChange={val => setAttributes({ btnTextHoverColor6: val })} />
                              <Color label={__('Border Hover Color')} disableAlpha value={btnBorderHoverColor6} onChange={val => setAttributes({ btnBorderHoverColor6: val })} />
                              <Background
                                      parallax
                                      value={btnBgHover6}
                                      label={__('Background')}
                                      externalImage
                                      sources={['color','gradient']}
                                      onChange={val => setAttributes({ btnBgHover6: val })}
                                    />
                            </Tab>
                        </Tabs>
                            
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Padding')} value={btnPaddingMobile6}
                                        onChange={val => setAttributes({ btnPaddingMobile6: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Padding')} value={btnPaddingTablet6}
                                        onChange={val => setAttributes({ btnPaddingTablet6: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Padding')} value={btnPadding6}
                                        onChange={val => setAttributes({ btnPadding6: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Margin')}
                                        value={btnMarginMobile6}
                                        onChange={val => setAttributes({ btnMarginMobile6: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Margin')} value={btnMarginTablet6}
                                        onChange={val => setAttributes({ btnMarginMobile6: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Margin')} value={btnMargin6}
                                        onChange={val => setAttributes({ btnMargin6: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <BorderTypes label={__('Border Type')} value={btnBorderType6} onChange={(value) => setAttributes({ btnBorderType6: value })} />
                        
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Width')} value={btnBorderMobile6}
                                        onChange={val => setAttributes({ btnBorderMobile6: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Border Width')} 
                                        value={btnBorderTablet6}
                                        onChange={val => setAttributes({ btnBorderTablet6: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Width')} value={btnBorder6}
                                        onChange={val => setAttributes({ btnBorder6: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Radius')} value={btnBorderRadiusMobile6}
                                        onChange={val => setAttributes({ btnBorderRadiusMobile6: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Border Radius')} 
                                        value={btnBorderRadiusTablet6}
                                        onChange={val => setAttributes({ btnBorderRadiusTablet6: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Radius')} value={btnBorderRadius6}
                                        onChange={val => setAttributes({ btnBorderRadius6: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignmentMobile6}
                                          onChange={(value) => setAttributes({ btnAlignmentMobile6: value })}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignmentTablet6}
                                          onChange={(value) => setAttributes({ btnAlignmentTablet6: value })}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignment6}
                                          onChange={(value) => setAttributes({ btnAlignment6: value })}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TextareaControl              
                            label={ __( 'Amazon Price Shortcode' ) }         
                            value={ amazonprice6 }                       
                            onChange={ ( value ) => setAttributes( { amazonprice6: value } ) }
                        />
                        <SelectControl
                            label={ __( 'Show Amazon Price Position' ) }
                            value={ amazonpriceposition6 }
                            options= {[
                                    { value: 'Above Button', label: 'Above Button' },
                                    { value: 'Below Button', label: 'Below Button' },
                                ]}
                                onChange={ ( value ) => setAttributes( { amazonpriceposition6: value } ) }
                          />
                        <TextareaControl              
                            label={ __( 'Amazon Rating Shortcode' ) }         
                            value={ amazonrating6 }                       
                            onChange={ ( value ) => setAttributes( { amazonrating6: value } ) }
                        />
                        <SelectControl
                            label={ __( 'Show Amazon Rating Position' ) }
                            value={ amazonratingposition6 }
                            options= {[
                                    { value: 'Above Button', label: 'Above Button' },
                                    { value: 'Below Button', label: 'Below Button' },
                                ]}
                                onChange={ ( value ) => setAttributes( { amazonratingposition6: value } ) }
                          />

                </PanelBody>
                }


                { numberOfBlock && numberOfBlock >= '7' &&
                <PanelBody title={`Box 7 Settings`} initialOpen={false} icon={ getIcon( 'layout' ) } className={ 'abblocks-panel-label' }>

                    <ToggleControl
                          label={ __( 'External Image?' ) }                        
                          checked={ externalImage7 }                                      
                          onChange={ ( ) => setAttributes( { externalImage7: ! externalImage7 } ) }
                      />

                    { externalImage7 && externalImage7 == true &&
                        <Fragment>
                      <TextareaControl              
                          label={ __( 'External Image Src' ) }         
                          value={ externalImageSrc7 }                       
                          onChange={ ( value ) => setAttributes( { externalImageSrc7: value } ) }
                      />
                      <TextControl              
                            label={ __( 'External Image Alt' ) }         
                            value={ externalImageAlt7 }                       
                            onChange={ ( value ) => setAttributes( { externalImageAlt7: value } ) }
                        />
                        </Fragment>
                    }

                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                          activeClass="active-tab"
                          tabs={[
                            {
                              name: 'boxdesk',
                              title: <Dashicon icon="desktop" />,
                              className: 'affiliate-responsive-tabs',
                            },
                            {
                              name: 'boxtablet',
                              title: <Dashicon icon="tablet" />,
                              className: 'affiliate-responsive-tabs',
                            },
                            {
                              name: 'boxmobile',
                              title: <Dashicon icon="smartphone" />,
                              className: 'affiliate-responsive-tabs',
                            },
                          ]}>
                          {
                            (radiustab) => {
                              let tabout;
                              if (radiustab.name) {
                                if ('boxmobile' === radiustab.name) {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidthMobile7} onChange={(value) => setAttributes({ customImgWidthMobile7: value })} min={0} max={1000} unit={['']} />
                                  );
                                } else if ('boxtablet' === radiustab.name) {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidthTablet7} onChange={(value) => setAttributes({ customImgWidthTablet7: value })} min={0} max={1000} unit={['']} />
                                  );
                                } else {
                                  tabout = (
                                    <Range label={__('Custom Image Width')} value={customImgWidth7} onChange={(value) => setAttributes({ customImgWidth7: value })} min={0} max={1000} unit={['']} />
                                  );
                                }
                              }
                              return <div>{tabout}</div>;
                            }
                          }
                    </TabPanel>
                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                        activeClass="active-tab"
                        tabs={[
                          {
                            name: 'boxdesk',
                            title: <Dashicon icon="desktop" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxtablet',
                            title: <Dashicon icon="tablet" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxmobile',
                            title: <Dashicon icon="smartphone" />,
                            className: 'affiliate-responsive-tabs',
                          },
                        ]}>
                        {
                          (radiustab) => {
                            let tabout;
                            if (radiustab.name) {
                              if ('boxmobile' === radiustab.name) {
                                tabout = (
                                  <Dimension
                                    label={__('Image Padding')} value={imagePaddingMobile7}
                                    onChange={val => setAttributes({ imagePaddingMobile7: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                  <Dimension label={__('Image Padding')} value={imagePaddingTablet7}
                                    onChange={val => setAttributes({ imagePaddingTablet7: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else {
                                tabout = (
                                  <Dimension
                                    label={__('Image Padding')} value={imagePadding7}
                                    onChange={val => setAttributes({ imagePadding7: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              }
                            }
                            return <div>{tabout}</div>;
                          }
                        }
                    </TabPanel> 

                    <Color label={__('Background Color')} disableAlpha value={boxBgColor7} onChange={val => setAttributes({ boxBgColor7: val })} />    

                    { productColumnStyle && productColumnStyle == 'layout1' &&
                        <SelectControl
                            label={ __( 'Flag Style' ) }
                            value={ flagStyle7 }
                            options= {[
                                    { value: '', label: 'Select' },
                                    { value: 'flag1', label: 'Budget Pick' },
                                    { value: 'flag2', label: 'Best Value' },
                                    { value: 'flag3', label: 'Premium Pick' },
                                ]}
                                onChange={ ( value ) => setAttributes( { flagStyle7: value } ) }
                        />    
                    }

                    <SelectControl
                        label={ __( 'Title Heading Tag' ) }
                        value={ titleTag7 }
                        options= {[
                                { value: 'h2', label: 'Heading 2 (h2)' },
                                { value: 'h3', label: 'Heading 3 (h3)' },
                                { value: 'h4', label: 'Heading 4 (h4)' },
                                { value: 'h5', label: 'Heading 5 (h5)' },
                                { value: 'h6', label: 'Heading 6 (h6)' },
                                { value: 'p', label: 'Paragraph (p)' },
                            ]}
                            onChange={ ( value ) => setAttributes( { titleTag7: value } ) }
                    />   

                    
                    
                    { productColumnStyle && productColumnStyle == 'layout1' &&
                    <Fragment>
                    
                    <RangeControl label={__("Number of stars")} value={ratings7} onChange={value => setAttributes({ ratings7: value, selectedStars7: value < selectedStars7 ? value : selectedStars7 }) } min={5} step={1} max={10} allowReset />
                    <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                        activeClass="active-tab"
                        tabs={[
                          {
                            name: 'boxdesk',
                            title: <Dashicon icon="desktop" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxtablet',
                            title: <Dashicon icon="tablet" />,
                            className: 'affiliate-responsive-tabs',
                          },
                          {
                            name: 'boxmobile',
                            title: <Dashicon icon="smartphone" />,
                            className: 'affiliate-responsive-tabs',
                          },
                        ]}>
                        {
                          (radiustab) => {
                            let tabout;
                            if (radiustab.name) {
                              if ('boxmobile' === radiustab.name) {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSizeMobile7}  onChange={(value) => setAttributes({ starsSizeMobile7: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSizeTablet7}  onChange={(value) => setAttributes({ starsSizeTablet7: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              } else {
                                tabout = (
                                    <Range label={__('Stars Size')} value={starsSize7}  onChange={(value) => setAttributes({ starsSize7: value })} unit={[]} min={10} step={1} max={60} />
                                );
                              }
                            }
                            return <div>{tabout}</div>;
                          }
                        }
                    </TabPanel>

                    {listType7 == 'unordered' &&
                        <Fragment>
                            <IconSelector
                                label="Top List Icon"
                                value={bulletStyle7.name}
                                enableSearch
                                icons={[
                                    { name: 'check', value: 'fas fa-check' },
                                    { name: 'check-square', value: 'fas fa-check-square' },
                                    { name: 'check-square-outline', value: 'far fa-check-square' },
                                    { name: 'check-double', value: 'fas fa-check-double' },
                                    { name: 'check-circle', value: 'fas fa-check-circle' },
                                    { name: 'check-circle-outline', value: 'far fa-check-circle' },
                                    { name: 'square', value: 'fas fa-square' },
                                    { name: 'square-outline', value: 'far fa-square' },
                                    { name: 'circle', value: 'fas fa-circle' },
                                    { name: 'circle-outline', value: 'far fa-circle' },
                                    { name: 'arrow-right', value: 'fas fa-arrow-right' },
                                    { name: 'arrow-left', value: 'fas fa-arrow-left' },
                                    { name: 'arrow-circle-right', value: 'fas fa-arrow-circle-right' },
                                    { name: 'arrow-circle-left', value: 'fas fa-arrow-circle-left' },
                                    { name: 'arrow-alt-circle-right', value: 'far fa-arrow-alt-circle-right' },
                                    { name: 'arrow-alt-circle-left', value: 'far fa-arrow-alt-circle-left' },
                                    { name: 'long-arrow-alt-right', value: 'fas fa-long-arrow-alt-right' },
                                    { name: 'long-arrow-alt-left', value: 'fas fa-long-arrow-alt-left' },
                                    { name: 'chevron-right', value: 'fas fa-chevron-right' },
                                    { name: 'chevron-left', value: 'fas fa-chevron-left' },
                                    { name: 'angle-right', value: 'fas fa-angle-right' },
                                    { name: 'angle-left', value: 'fas fa-angle-left' },
                                    { name: 'star', value: 'fas fa-star' },
                                    { name: 'star-outline', value: 'far fa-star' },
                                    { name: 'windows-close-fill', value: 'fas fa-window-close' },
                                    { name: 'ban', value: 'fas fa-ban' },
                                    { name: 'window-close-simple', value: 'far fa-window-close' },
                                    { name: 'times', value: 'fas fa-times' },
                                    { name: 'times-circle', value: 'fas fa-times-circle' },
                                    { name: 'times-circle-simple', value: 'far fa-times-circle' },
                                    { name: 'dot-circle-fill', value: 'fas fa-dot-circle' },
                                    { name: 'dot-circle-simple', value: 'far fa-dot-circle' },
                                    { name: 'thumb-up-fill', value: 'fas fa-thumbs-up' },
                                    { name: 'thumb-up-simple', value: 'far fa-thumbs-up' },
                                    { name: 'thumb-down-fill', value: 'fas fa-thumbs-down' },
                                    { name: 'thumb-down-simple', value: 'far fa-thumbs-down' }, 
                                ]}
                                onChange={val => setAttributes({ bulletStyle7: val })}
                            />
                        </Fragment>
                    }
                    {listType7 == 'ordered' &&
                        <Fragment>
                            <Range label={__('Font Size')} value={numberFontSize7} onChange={(value) => setAttributes({ numberFontSize7: value })} min={10} max={100} />
                            <Toggle
                                value={useNumberBg7}
                                label={__('Use Background')}
                                onChange={val => setAttributes({ useNumberBg7: val, recreateStyles7: !recreateStyles7 })}
                            />
                            {
                                useNumberBg7 == 1 &&
                                <Fragment>
                                    <Range label={__('Background Size')} value={numberBgSize7} onChange={(value) => setAttributes({ numberBgSize7: value })} min={1} max={15} />
                                    <Range label={__('Corner')} value={numberCorner7} onChange={(value) => setAttributes({ numberCorner7: value })} min={0} max={100} />
                                </Fragment>
                            }
                        </Fragment>
                    }
                    

                    <Dropdown
                        className="affiliate-icon-popover"
                        contentClassName="my-popover-content-classname"
                        popoverProps={ { placement: 'bottom-start' } }
                        renderToggle={ ( { isOpen, onToggle } ) => (
                        <Fragment>
                            <h2>{__('Tag Icon')}</h2>
                            <Button className="affiliate-size-btn"  onClick={ onToggle } aria-expanded={ isOpen }>
                                <Dashicon icon="admin-tools" />
                            </Button>
                        </Fragment> 
                        ) }
                        renderContent={ () => (
                            <div className="affiliate-iconlist-popwrapper">
                                <IconList value={buttonIcon7} disableToggle onChange={val => setAttributes({ buttonIcon7: val })} />
                            </div>
                        ) }
                    />
                    
                    <RadioAdvanced
                          label={__('Icon Position')}
                          options={[
                              { label: 'Left', value: 'af-icon-is-left', title: 'Left' },
                              { label: 'Right', value: 'af-icon-is-right', title: 'Right' },
                          ]}
                          value={iconPosition7}
                          onChange={(value) => setAttributes({ iconPosition7: value })} />   

                    </Fragment>
                    }       

                    <TextControl        
                        label = { __( 'Button Text' ) }             
                        value={ spBtnText7 }                        
                        onChange={ ( value ) => setAttributes( { spBtnText7: value } ) }
                    />
                    <TextControl
                        label = { __( 'Button URL' ) }                      
                        value={ spBtnHref7 }                        
                        onChange={ ( value ) => setAttributes( { spBtnHref7: value } ) }
                    />
                    <TextControl
                        label = { __( 'Button Rel' ) }                      
                        value={ btnRel7 }                       
                        onChange={ ( value ) => setAttributes( { btnRel7: value } ) }
                    />
                    <ToggleControl
                        label={ __( 'Open link in new Tab' ) }                       
                        checked={ btn_Htarget7 }                                        
                        onChange={ ( ) => setAttributes( { btn_Htarget7: ! btn_Htarget7 } ) }
                    />
                    <ToggleControl
                        label={ __( 'Rounded Button' ) }                        
                        checked={ bntRounded7 }                                     
                        onChange={ ( ) => setAttributes( { bntRounded7: ! bntRounded7 } ) }
                    />
                    <ToggleControl
                  label={ __( 'Enable Glimmer Effect' ) }            
                  checked={ enableGlimmerEffect7 }                    
                  onChange={ ( ) => setAttributes( { enableGlimmerEffect7: ! enableGlimmerEffect7 } ) }
              />
                    <RadioAdvanced
                            label={__('Button Size')}
                            options={[
                                { label: 'S', value: 'small', title: 'Small' },
                                { label: 'M', value: 'medium', title: 'Medium' },
                                { label: 'L', value: 'large', title: 'Large' },
                                { label: 'XL', value: 'extra-large', title: 'Extra Large' },
                            ]}
                            value={btnSize7}
                            onChange={(value) => setAttributes({ btnSize7: value })} />
                    <RadioAdvanced
                            label={__('Button Width')}
                            options={[
                                { label: 'Fixed', value: 'fixed', title: 'Fixed' },
                                { label: 'Flexible', value: 'flex', title: 'Flexible' },
                                { label: 'Full', value: 'full', title: 'Full' },
                            ]}
                            value={buttonWidth7}
                            onChange={(value) => setAttributes({ buttonWidth7: value })} />
                    <TypographyControl
                            label={__("Button Typography")}
                            attributes={attributes}
                            setAttributes={setAttributes}
                            loadGoogleFonts={{ value:btnLoadGoogleFonts7, label: __("btnLoadGoogleFonts7") }}
                            fontFamily={{ value: btnfontFamily7, label: __("btnfontFamily7") }}
                            fontWeight={{ value: btnfontWeight7, label: __("btnfontWeight7") }}
                            fontSubset={{ value: btnfontSubset7, label: __("btnfontSubset7") }}
                            fontSizeType={{ value: btnfontSizeType7, label: __("btnfontSizeType7") }}
                            fontSizeTypeMobile={{ value: btnfontSizeTypeMobile7, label: __("btnfontSizeTypeMobile7") }}
                            fontSizeTypeTablet={{ value: btnfontSizeTypeTablet7, label: __("btnfontSizeTypeTablet7") }}
                            fontSize={{ value: btnfontSizeDesktop7, label: __("btnfontSizeDesktop7") }}
                            fontSizeMobile={{ value: btnfontSizeMobile7, label: __("btnfontSizeMobile7") }}
                            fontSizeTablet={{ value: btnfontSizeTablet7, label: __("btnfontSizeTablet7") }}
                            lineHeightType={{ value: '', label: __(" ") }}
                            lineHeight={{ value: btnlineHeight7, label: __("btnlineHeight7") }}
                            lineHeightMobile={{ value: btnlineHeightMobile7, label: __("btnlineHeightMobile7") }}
                            lineHeightTablet={{ value: btnlineHeightTablet7, label: __("btnlineHeightTablet7") }}
                            letterSpacing={{ value: btnletterSpacing7, label: __("btnletterSpacing7") }}
                            letterSpacingMobile={{ value: btnletterSpacingMobile7, label: __("btnletterSpacingMobile7") }}
                            letterSpacingTablet={{ value: btnletterSpacingTablet7, label: __("btnletterSpacingTablet7") }}
                            letterSpacingType={{ value: btnletterSpacingType7, label: __("btnletterSpacingType7") }}
                            letterSpacingTypeTablet={{ value: btnletterSpacingTypeTablet7, label: __("btnletterSpacingTypeTablet7") }}
                            letterSpacingTypeMobile={{ value: btnletterSpacingTypeMobile7, label: __("btnletterSpacingTypeMobile7") }}
                            textTransform={btntextTransform7}
                            onTextTransform={(value) => setAttributes({ btntextTransform7: value })}
                            textDecoration={btntextDecoration7}
                            onTextDecoration={(value) => setAttributes({ btntextDecoration7: value })}
                            fontStyle={btnfontStyle7}
                            onFontStyle={(value) => setAttributes({ btnfontStyle7: value })}
                        />
                        <Tabs>
                            <Tab tabTitle={__('Normal')}>
                              <Color label={__('Text Color')} disableAlpha value={btnTextColor7} onChange={val => setAttributes({ btnTextColor7: val })} />
                              <Color label={__('Border Color')} disableAlpha value={btnBorderColor7} onChange={val => setAttributes({ btnBorderColor7: val })} />
                              <Background
                                      parallax
                                      value={btnBg7}
                                      label={__('Background')}
                                      externalImage
                                      sources={['color','gradient']}
                                      onChange={val => setAttributes({ btnBg7: val })} 
                                  />
                            </Tab>
                            <Tab tabTitle={__('Hover')}>
                              <Color label={__('Hover Text Color')} disableAlpha value={btnTextHoverColor7} onChange={val => setAttributes({ btnTextHoverColor7: val })} />
                              <Color label={__('Border Hover Color')} disableAlpha value={btnBorderHoverColor7} onChange={val => setAttributes({ btnBorderHoverColor7: val })} />
                              <Background
                                      parallax
                                      value={btnBgHover7}
                                      label={__('Background')}
                                      externalImage
                                      sources={['color','gradient']}
                                      onChange={val => setAttributes({ btnBgHover7: val })}
                                    />
                            </Tab>
                        </Tabs>
                            
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Padding')} value={btnPaddingMobile7}
                                        onChange={val => setAttributes({ btnPaddingMobile7: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Padding')} value={btnPaddingTablet7}
                                        onChange={val => setAttributes({ btnPaddingTablet7: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Padding')} value={btnPadding7}
                                        onChange={val => setAttributes({ btnPadding7: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Margin')}
                                        value={btnMarginMobile7}
                                        onChange={val => setAttributes({ btnMarginMobile7: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Margin')} value={btnMarginTablet7}
                                        onChange={val => setAttributes({ btnMarginMobile7: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Margin')} value={btnMargin7}
                                        onChange={val => setAttributes({ btnMargin7: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <BorderTypes label={__('Border Type')} value={btnBorderType7} onChange={(value) => setAttributes({ btnBorderType7: value })} />
                        
                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Width')} value={btnBorderMobile7}
                                        onChange={val => setAttributes({ btnBorderMobile7: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Border Width')} 
                                        value={btnBorderTablet7}
                                        onChange={val => setAttributes({ btnBorderTablet7: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Width')} value={btnBorder7}
                                        onChange={val => setAttributes({ btnBorder7: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Radius')} value={btnBorderRadiusMobile7}
                                        onChange={val => setAttributes({ btnBorderRadiusMobile7: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Border Radius')} 
                                        value={btnBorderRadiusTablet7}
                                        onChange={val => setAttributes({ btnBorderRadiusTablet7: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Radius')} value={btnBorderRadius7}
                                        onChange={val => setAttributes({ btnBorderRadius7: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>

                        <TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
                            activeClass="active-tab"
                            tabs={[
                              {
                                name: 'boxdesk',
                                title: <Dashicon icon="desktop" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxtablet',
                                title: <Dashicon icon="tablet" />,
                                className: 'affiliate-responsive-tabs',
                              },
                              {
                                name: 'boxmobile',
                                title: <Dashicon icon="smartphone" />,
                                className: 'affiliate-responsive-tabs',
                              },
                            ]}>
                            {
                              (radiustab) => {
                                let tabout;
                                if (radiustab.name) {
                                  if ('boxmobile' === radiustab.name) {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignmentMobile7}
                                          onChange={(value) => setAttributes({ btnAlignmentMobile7: value })}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignmentTablet7}
                                          onChange={(value) => setAttributes({ btnAlignmentTablet7: value })}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignment7}
                                          onChange={(value) => setAttributes({ btnAlignment7: value })}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                        </TabPanel>


                        <TextareaControl              
                            label={ __( 'Amazon Price Shortcode' ) }         
                            value={ amazonprice7 }                       
                            onChange={ ( value ) => setAttributes( { amazonprice7: value } ) }
                        />
                        <SelectControl
                            label={ __( 'Show Amazon Price Position' ) }
                            value={ amazonpriceposition7 }
                            options= {[
                                    { value: 'Above Button', label: 'Above Button' },
                                    { value: 'Below Button', label: 'Below Button' },
                                ]}
                                onChange={ ( value ) => setAttributes( { amazonpriceposition7: value } ) }
                          />
                        <TextareaControl              
                            label={ __( 'Amazon Rating Shortcode' ) }         
                            value={ amazonrating7 }                       
                            onChange={ ( value ) => setAttributes( { amazonrating7: value } ) }
                        />
                        <SelectControl
                            label={ __( 'Show Amazon Rating Position' ) }
                            value={ amazonratingposition7 }
                            options= {[
                                    { value: 'Above Button', label: 'Above Button' },
                                    { value: 'Below Button', label: 'Below Button' },
                                ]}
                                onChange={ ( value ) => setAttributes( { amazonratingposition7: value } ) }
                          />
                        

                </PanelBody>
                }
 
                 <a href="https://www.affiliatebooster.com/#price" target="_blank" className={"BuyPremiumSettings"}>Buy Premium to Unlock More Settings</a>
   
            </InspectorControls>
                
                <div id={`affiliate-style-${block_id}`} className={`affiliate-block-${block_id}${className ? ` ${className}` : ''} affiliate-coupon-wrapper ${productColumnStyle} block${numberOfCol}`}>
                    
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
                                                   <div className={`affiliate-star-item`} key={i}
                                                        onMouseEnter={() => {
                                                            this.setState({ highlightedStars1: i + 1 });
                                                        }}
                                                        onClick={() => {
                                                            if (selectedStars1 % 1 === 0) {
                                                                setAttributes({
                                                                    selectedStars1: i + (selectedStars1 - 1 === i ? 0.5 : 1)
                                                                });
                                                            } else {
                                                                setAttributes({
                                                                    selectedStars1: i + (selectedStars1 - 0.5 === i ? 1 : 0.5)
                                                                });
                                                            }
                                                        }}
                                                    >
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
                                            <RichText
                                                tagName={Tag1}
                                                value={titleText1} 
                                                className="affiliate-coupon-title1"
                                                placeholder={__('Product Title')}
                                                onChange={titleText1 => setAttributes({ titleText1 })}
                                            />    
                                        }


                                        <Fragment>                                    
                                            { imageDesignType1 && imageDesignType1 == 'ab-fixed-design' && !externalImage1 && !externalImage1 == true &&  
                                              <MediaUpload              
                                                  onSelect={ this.onSelectImage1 }
                                                  allowedTypes= { [ "image" ] }
                                                  value={image1}             
                                                  render={ ( { open } ) => (                    
                                                    <a className="block-editor-button" onClick={ open }>
                                                    {/* <img  src={image1.url} alt={image1.url ? titleText1 : ''} />
                                                    */}
                                                    <ReviewImage1 attributes={attributes} />                                            
                                                        {__("Add-Image")}                       
                                                    </a>
                                                  ) }
                                                />
                                            }             


                                            { externalImage1 && externalImage1 == true &&
                                                  <div className={`affiliate-coupon-image1`}>
                                                      <img src={externalImageSrc1}  alt={externalImageAlt1} />
                                                  </div>
                                              }                       
                                        </Fragment>

                                        { showContent && showContent == true &&
                                            <div className={`affiliate-block-advanced-list affiliate-coupon-list1`}>
                                                <RichList
                                                    key={'listTextItems1'}
                                                    value={ listTextItems1 }
                                                    attributeKey={'listTextItems1'}
                                                    className={`affiliate-list1 affiliate-list-type-unordered affiliate-alignment-${alignment1} affiliate-list-bullet-${bulletStyle1.name}`}
                                                    editProps={this.props}
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
                                                <div className={`affiliate-coupon-btn-wrapper1`}  onClick={() => { this.setState({ isVisible1: !isVisible1 })}}>
                                                    <span className={`affiliate-btn  btn-is-${btnSize1} ${
                                                              buttonWidth1 === "full" ? "btn-is-fullw" : buttonWidth1 === "flex" ? `btn-is-flex-${btnSize1}` : "" } ${bntRounded1 ? 'btn-is-rounded' : ''}   ${enableGlimmerEffect ? 'glimmer-effect' : ''}`}>
                                                        { iconPosition1 && iconPosition1 == 'af-icon-is-left' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon1} ${iconPosition1}`}></i>
                                                        }
                                                        { iconPosition1 && iconPosition1 != 'af-icon-is-left' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                        }
                                                        <RichText
                                                            value={spBtnText1}
                                                            placeholder={__('Button text')}
                                                            onChange={spBtnText1 => setAttributes({ spBtnText1 })}
                                                            allowedFormats={ ['bold', 'italic', 'underline'] }
                                                            href = {btn_Htarget1}
                                                            {...(btn_Htarget1 ? {...btn_Htarget1 && { rel: newrel1 }} : { rel: newrel1 }  )}
                                                            {...(btn_Htarget1 ? { target: '_blank' } : '' )}
                                                        />
                                                        { iconPosition1 && iconPosition1 == 'af-icon-is-right' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon1} ${iconPosition1}`}></i>
                                                        }
                                                        { iconPosition1 && iconPosition1 != 'af-icon-is-right' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                        }
                                                    </span>    
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
                                                   <div className={`affiliate-star-item`} key={i}
                                                        onMouseEnter={() => {
                                                            this.setState({ highlightedStars2: i + 1 });
                                                        }}
                                                        onClick={() => {
                                                            if (selectedStars2 % 1 === 0) {
                                                                setAttributes({
                                                                    selectedStars2: i + (selectedStars2 - 1 === i ? 0.5 : 1)
                                                                });
                                                            } else {
                                                                setAttributes({
                                                                    selectedStars2: i + (selectedStars2 - 0.5 === i ? 1 : 0.5)
                                                                });
                                                            }
                                                        }}
                                                    >
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
                                            <RichText
                                                tagName={Tag2}
                                                value={titleText2} 
                                                className="affiliate-coupon-title2"
                                                placeholder={__('Product Title')}
                                                onChange={titleText2 => setAttributes({ titleText2 })}
                                                
                                            />    
                                        }

                                        <Fragment>                                    
                                            { imageDesignType2 && imageDesignType2 == 'ab-fixed-design' &&  !externalImage2 && !externalImage2 == true &&  
                                              <MediaUpload              
                                                  onSelect={ this.onSelectImage2 }
                                                  allowedTypes= { [ "image" ] }
                                                  value={image2}             
                                                  render={ ( { open } ) => (                    
                                                    <a className="block-editor-button" onClick={ open }>
                                                    {/* <img  src={image2.url} alt={image2.url ? titleText2 : ''} />
                                                    */}
                                                    <ReviewImage2 attributes={attributes} />                                            
                                                        {__("Add-Image")}                       
                                                    </a>
                                                  ) }
                                                />
                                            }         

                                            { externalImage2 && externalImage2 == true &&
                                                  <div className={`affiliate-coupon-image2`}>
                                                      <img src={externalImageSrc2}  alt={externalImageAlt2} />
                                                  </div>
                                              }                              
                                      </Fragment>


                                        { showContent && showContent == true &&
                                            <div className={`affiliate-block-advanced-list affiliate-coupon-list2`}>
                                                 <RichList
                                                    key={'listTextItems2'}
                                                    value={listTextItems2}
                                                    attributeKey={'listTextItems2'}
                                                    className={`affiliate-list2 affiliate-list-type-unordered affiliate-alignment-${alignment2} affiliate-list-bullet-${bulletStyle2.name}`}
                                                    editProps={this.props}
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
                                                <div className={`affiliate-coupon-btn-wrapper2`}  onClick={() => { this.setState({ isVisible2: !isVisible2 })}}>
                                                    <span className={`affiliate-btn  btn-is-${btnSize2} ${
                                                              buttonWidth2 === "full" ? "btn-is-fullw" : buttonWidth2 === "flex" ? `btn-is-flex-${btnSize2}` : "" } ${bntRounded2 ? 'btn-is-rounded' : ''}   ${enableGlimmerEffect2 ? 'glimmer-effect' : ''}`}>
                                                        { iconPosition2 && iconPosition2 == 'af-icon-is-left' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon2} ${iconPosition2}`}></i>
                                                        }
                                                        { iconPosition2 && iconPosition2 != 'af-icon-is-left' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                        }
                                                        <RichText
                                                            value={spBtnText2}
                                                            placeholder={__('Button text')}
                                                            onChange={spBtnText2 => setAttributes({ spBtnText2 })}
                                                            allowedFormats={ ['bold', 'italic', 'underline'] }
                                                            href = {btn_Htarget2}
                                                            {...(btn_Htarget2 ? {...btn_Htarget2 && { rel: newrel2 }} : { rel: newrel2 }  )}
                                                            {...(btn_Htarget2 ? { target: '_blank' } : '' )}
                                                        />
                                                        { iconPosition2 && iconPosition2 == 'af-icon-is-right' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon2} ${iconPosition2}`}></i>
                                                        }
                                                        { iconPosition2 && iconPosition2 != 'af-icon-is-right' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                        }
                                                    </span>    
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
                                                   <div className={`affiliate-star-item`} key={i}
                                                        onMouseEnter={() => {
                                                            this.setState({ highlightedStars3: i + 1 });
                                                        }}
                                                        onClick={() => {
                                                            if (selectedStars3 % 1 === 0) {
                                                                setAttributes({
                                                                    selectedStars3: i + (selectedStars3 - 1 === i ? 0.5 : 1)
                                                                });
                                                            } else {
                                                                setAttributes({
                                                                    selectedStars3: i + (selectedStars3 - 0.5 === i ? 1 : 0.5)
                                                                });
                                                            }
                                                        }}
                                                    >
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
                                            <RichText
                                                tagName={Tag3}
                                                value={titleText3} 
                                                className="affiliate-coupon-title3"
                                                placeholder={__('Product Title')}
                                                onChange={titleText3 => setAttributes({ titleText3 })}
                                                
                                            />    
                                        }

                                        <Fragment>
                                            { imageDesignType3 && imageDesignType3 == 'ab-fixed-design' &&  !externalImage3 && !externalImage3 == true &&  
                                              <MediaUpload              
                                                  onSelect={ this.onSelectImage3 }
                                                  allowedTypes= { [ "image" ] }
                                                  value={image3}             
                                                  render={ ( { open } ) => (                    
                                                    <a className="block-editor-button" onClick={ open }>
                                                    {/* <img  src={image3.url} alt={image3.url ? titleText3 : ''} />
                                                    */}
                                                    <ReviewImage3 attributes={attributes} />                                            
                                                        {__("Add-Image")}                       
                                                    </a>
                                                  ) }
                                                />
                                            }     

                                            { externalImage3 && externalImage3 == true &&
                                                  <div className={`affiliate-coupon-image3`}>
                                                      <img src={externalImageSrc3}  alt={externalImageAlt3} />
                                                  </div>
                                              }                                   
                                      </Fragment>


                                      { showContent && showContent == true &&
                                          <div className={`affiliate-block-advanced-list affiliate-coupon-list3`}>
                                             <RichList
                                                  key={'listTextItems3'}
                                                  value={ listTextItems3 }
                                                  attributeKey={'listTextItems3'}
                                                  className={`affiliate-list3 affiliate-list-type-unordered affiliate-alignment-${alignment3} affiliate-list-bullet-${bulletStyle3.name}`}
                                                  editProps={this.props}
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
                                            <div className={`affiliate-coupon-btn-wrapper3`}  onClick={() => { this.setState({ isVisible3: !isVisible3 })}}>
                                                <span className={`affiliate-btn  btn-is-${btnSize3} ${
                                                          buttonWidth3 === "full" ? "btn-is-fullw" : buttonWidth3 === "flex" ? `btn-is-flex-${btnSize3}` : "" } ${bntRounded3 ? 'btn-is-rounded' : ''}   ${enableGlimmerEffect3 ? 'glimmer-effect' : ''}`}>
                                                    { iconPosition3 && iconPosition3 == 'af-icon-is-left' &&
                                                          <i className={`affiliate-abbtn-icon ${buttonIcon3} ${iconPosition3}`}></i>
                                                    }
                                                    { iconPosition3 && iconPosition3 != 'af-icon-is-left' &&
                                                      <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText
                                                        value={spBtnText3}
                                                        placeholder={__('Button text')}
                                                        onChange={spBtnText3 => setAttributes({ spBtnText3 })}
                                                        allowedFormats={ ['bold', 'italic', 'underline'] }
                                                        href = {btn_Htarget3}
                                                        {...(btn_Htarget3 ? {...btn_Htarget3 && { rel: newrel3 }} : { rel: newrel3 }  )}
                                                        {...(btn_Htarget3 ? { target: '_blank' } : '' )}
                                                    />
                                                    { iconPosition3 && iconPosition3 == 'af-icon-is-right' &&
                                                          <i className={`affiliate-abbtn-icon ${buttonIcon3} ${iconPosition3}`}></i>
                                                    }
                                                    { iconPosition3 && iconPosition3 != 'af-icon-is-right' &&
                                                      <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </span>    
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
                                                   <div className={`affiliate-star-item`} key={i}
                                                        onMouseEnter={() => {
                                                            this.setState({ highlightedStars4: i + 1 });
                                                        }}
                                                        onClick={() => {
                                                            if (selectedStars4 % 1 === 0) {
                                                                setAttributes({
                                                                    selectedStars4: i + (selectedStars4 - 1 === i ? 0.5 : 1)
                                                                });
                                                            } else {
                                                                setAttributes({
                                                                    selectedStars4: i + (selectedStars4 - 0.5 === i ? 1 : 0.5)
                                                                });
                                                            }
                                                        }}
                                                    >
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
                                            <RichText
                                                tagName={Tag4}
                                                value={titleText4} 
                                                className="affiliate-coupon-title4"
                                                placeholder={__('Product Title')}
                                                onChange={titleText4 => setAttributes({ titleText4 })}
                                                
                                            />    
                                        }

                                        <Fragment>
                                            { imageDesignType4 && imageDesignType4 == 'ab-fixed-design' && !externalImage4 && !externalImage4 == true &&    
                                              <MediaUpload              
                                                  onSelect={ this.onSelectImage4 }
                                                  allowedTypes= { [ "image" ] }
                                                  value={image4}             
                                                  render={ ( { open } ) => (                    
                                                    <a className="block-editor-button" onClick={ open }>
                                                    {/* <img  src={image4.url} alt={image4.url ? titleText4 : ''} />
                                                    */}
                                                    <ReviewImage4 attributes={attributes} />                                            
                                                        {__("Add-Image")}                       
                                                    </a>
                                                  ) }
                                                />
                                            }      

                                            { externalImage4 && externalImage4 == true &&
                                                  <div className={`affiliate-coupon-image4`}>
                                                      <img src={externalImageSrc4}  alt={externalImageAlt4} />
                                                  </div>
                                              }                                  
                                      </Fragment>

                                      { showContent && showContent == true &&
                                        <div className={`affiliate-block-advanced-list affiliate-coupon-list4`}>
                                             <RichList
                                                  key={'listTextItems4'}
                                                  value={ listTextItems4 }
                                                  attributeKey={'listTextItems4'}
                                                  className={`affiliate-list4 affiliate-list-type-unordered affiliate-alignment-${alignment4} affiliate-list-bullet-${bulletStyle4.name}`}
                                                  editProps={this.props}
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
                                            <div className={`affiliate-coupon-btn-wrapper4`}  onClick={() => { this.setState({ isVisible4: !isVisible4 })}}>
                                                <span className={`affiliate-btn  btn-is-${btnSize4} ${
                                                          buttonWidth4 === "full" ? "btn-is-fullw" : buttonWidth4 === "flex" ? `btn-is-flex-${btnSize4}` : "" } ${bntRounded4 ? 'btn-is-rounded' : ''}   ${enableGlimmerEffect4 ? 'glimmer-effect' : ''}`}>
                                                    { iconPosition4 && iconPosition4 == 'af-icon-is-left' &&
                                                          <i className={`affiliate-abbtn-icon ${buttonIcon4} ${iconPosition4}`}></i>
                                                    }
                                                    { iconPosition4 && iconPosition4 != 'af-icon-is-left' &&
                                                      <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText
                                                        value={spBtnText4}
                                                        placeholder={__('Button text')}
                                                        onChange={spBtnText4 => setAttributes({ spBtnText4 })}
                                                        allowedFormats={ ['bold', 'italic', 'underline'] }
                                                        href = {btn_Htarget4}
                                                        {...(btn_Htarget4 ? {...btn_Htarget4 && { rel: newrel4 }} : { rel: newrel4 }  )}
                                                        {...(btn_Htarget4 ? { target: '_blank' } : '' )}
                                                    />
                                                    { iconPosition4 && iconPosition4 == 'af-icon-is-right' &&
                                                          <i className={`affiliate-abbtn-icon ${buttonIcon4} ${iconPosition4}`}></i>
                                                    }
                                                    { iconPosition4 && iconPosition4 != 'af-icon-is-right' &&
                                                      <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </span>    
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
                                                   <div className={`affiliate-star-item`} key={i}
                                                        onMouseEnter={() => {
                                                            this.setState({ highlightedStars5: i + 1 });
                                                        }}
                                                        onClick={() => {
                                                            if (selectedStars5 % 1 === 0) {
                                                                setAttributes({
                                                                    selectedStars5: i + (selectedStars5 - 1 === i ? 0.5 : 1)
                                                                });
                                                            } else {
                                                                setAttributes({
                                                                    selectedStars5: i + (selectedStars5 - 0.5 === i ? 1 : 0.5)
                                                                });
                                                            }
                                                        }}
                                                    >
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
                                            <RichText
                                                tagName={Tag5}
                                                value={titleText5} 
                                                className="affiliate-coupon-title5"
                                                placeholder={__('Product Title')}
                                                onChange={titleText5 => setAttributes({ titleText5 })}
                                                
                                            />    
                                        }

                                        <Fragment>
                                            { imageDesignType5 && imageDesignType5 == 'ab-fixed-design' && !externalImage5 && !externalImage5 == true &&   
                                              <MediaUpload              
                                                  onSelect={ this.onSelectImage5 }
                                                  allowedTypes= { [ "image" ] }
                                                  value={image5}             
                                                  render={ ( { open } ) => (                    
                                                    <a className="block-editor-button" onClick={ open }>
                                                    {/* <img  src={image5.url} alt={image5.url ? titleText5 : ''} />
                                                    */}
                                                    <ReviewImage5 attributes={attributes} />                                            
                                                        {__("Add-Image")}                       
                                                    </a>
                                                  ) }
                                                />
                                            }       

                                            { externalImage5 && externalImage5 == true &&
                                                  <div className={`affiliate-coupon-image5`}>
                                                      <img src={externalImageSrc5}  alt={externalImageAlt5} />
                                                  </div>
                                              }                              
                                      </Fragment>


                                        { showContent && showContent == true &&
                                              <div className={`affiliate-block-advanced-list affiliate-coupon-list5`}>
                                                 <RichList
                                                  key={'listTextItems5'}
                                                  value={ listTextItems5 }
                                                  attributeKey={'listTextItems5'}
                                                  className={`affiliate-list5 affiliate-list-type-unordered affiliate-alignment-${alignment5} affiliate-list-bullet-${bulletStyle5.name}`}
                                                  editProps={this.props}
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
                                            <div className={`affiliate-coupon-btn-wrapper5`}  onClick={() => { this.setState({ isVisible5: !isVisible5 })}}>
                                                <span className={`affiliate-btn  btn-is-${btnSize5} ${
                                                          buttonWidth5 === "full" ? "btn-is-fullw" : buttonWidth5 === "flex" ? `btn-is-flex-${btnSize5}` : "" } ${bntRounded5 ? 'btn-is-rounded' : ''}   ${enableGlimmerEffect5 ? 'glimmer-effect' : ''}`}>
                                                    { iconPosition5 && iconPosition5 == 'af-icon-is-left' &&
                                                          <i className={`affiliate-abbtn-icon ${buttonIcon5} ${iconPosition5}`}></i>
                                                    }
                                                    { iconPosition5 && iconPosition5 != 'af-icon-is-left' &&
                                                      <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText
                                                        value={spBtnText5}
                                                        placeholder={__('Button text')}
                                                        onChange={spBtnText5 => setAttributes({ spBtnText5 })}
                                                        allowedFormats={ ['bold', 'italic', 'underline'] }
                                                        href = {btn_Htarget5}
                                                        {...(btn_Htarget5 ? {...btn_Htarget5 && { rel: newrel5 }} : { rel: newrel5 }  )}
                                                        {...(btn_Htarget5 ? { target: '_blank' } : '' )}
                                                    />
                                                    { iconPosition5 && iconPosition5 == 'af-icon-is-right' &&
                                                          <i className={`affiliate-abbtn-icon ${buttonIcon5} ${iconPosition5}`}></i>
                                                    }
                                                    { iconPosition5 && iconPosition5 != 'af-icon-is-right' &&
                                                      <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </span>    
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
                                                   <div className={`affiliate-star-item`} key={i}
                                                        onMouseEnter={() => {
                                                            this.setState({ highlightedStars6: i + 1 });
                                                        }}
                                                        onClick={() => {
                                                            if (selectedStars6 % 1 === 0) {
                                                                setAttributes({
                                                                    selectedStars6: i + (selectedStars6 - 1 === i ? 0.5 : 1)
                                                                });
                                                            } else {
                                                                setAttributes({
                                                                    selectedStars6: i + (selectedStars6 - 0.5 === i ? 1 : 0.5)
                                                                });
                                                            }
                                                        }}
                                                    >
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
                                            <RichText
                                                tagName={Tag6}
                                                value={titleText6} 
                                                className="affiliate-coupon-title6"
                                                placeholder={__('Product Title')}
                                                onChange={titleText6 => setAttributes({ titleText6 })}
                                                
                                            />    
                                        }

                                        <Fragment>
                                            { imageDesignType6 && imageDesignType6 == 'ab-fixed-design' && !externalImage6 && !externalImage6 == true &&  
                                              <MediaUpload              
                                                  onSelect={ this.onSelectImage6 }
                                                  allowedTypes= { [ "image" ] }
                                                  value={image6}             
                                                  render={ ( { open } ) => (                    
                                                    <a className="block-editor-button" onClick={ open }>
                                                    {/* <img  src={image6.url} alt={image6.url ? titleText6 : ''} />
                                                    */}
                                                    <ReviewImage6 attributes={attributes} />                                            
                                                        {__("Add-Image")}                       
                                                    </a>
                                                  ) }
                                                />
                                            }

                                            { externalImage6 && externalImage6 == true &&
                                                  <div className={`affiliate-coupon-image6`}>
                                                      <img src={externalImageSrc6}  alt={externalImageAlt6} />
                                                  </div>
                                              }                                       
                                      </Fragment>

                                      { showContent && showContent == true &&
                                          <div className={`affiliate-block-advanced-list affiliate-coupon-list6`}>
                                             <RichList
                                                  key={'listTextItems6'}
                                                  value={ listTextItems6 }
                                                  attributeKey={'listTextItems6'}
                                                  className={`affiliate-list6 affiliate-list-type-unordered affiliate-alignment-${alignment6} affiliate-list-bullet-${bulletStyle6.name}`}
                                                  editProps={this.props}
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
                                            <div className={`affiliate-coupon-btn-wrapper6`}  onClick={() => { this.setState({ isVisible6: !isVisible6 })}}>
                                                <span className={`affiliate-btn  btn-is-${btnSize6} ${
                                                          buttonWidth6 === "full" ? "btn-is-fullw" : buttonWidth6 === "flex" ? `btn-is-flex-${btnSize6}` : "" } ${bntRounded6 ? 'btn-is-rounded' : ''}   ${enableGlimmerEffect6 ? 'glimmer-effect' : ''}`}>
                                                    { iconPosition6 && iconPosition6 == 'af-icon-is-left' &&
                                                          <i className={`affiliate-abbtn-icon ${buttonIcon6} ${iconPosition6}`}></i>
                                                    }
                                                    { iconPosition6 && iconPosition6 != 'af-icon-is-left' &&
                                                      <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText
                                                        value={spBtnText6}
                                                        placeholder={__('Button text')}
                                                        onChange={spBtnText6 => setAttributes({ spBtnText6 })}
                                                        allowedFormats={ ['bold', 'italic', 'underline'] }
                                                        href = {btn_Htarget6}
                                                        {...(btn_Htarget6 ? {...btn_Htarget6 && { rel: newrel6 }} : { rel: newrel6 }  )}
                                                        {...(btn_Htarget6 ? { target: '_blank' } : '' )}
                                                    />
                                                    { iconPosition6 && iconPosition6 == 'af-icon-is-right' &&
                                                          <i className={`affiliate-abbtn-icon ${buttonIcon6} ${iconPosition6}`}></i>
                                                    }
                                                    { iconPosition6 && iconPosition6 != 'af-icon-is-right' &&
                                                      <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </span>    
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
                                                   <div className={`affiliate-star-item`} key={i}
                                                        onMouseEnter={() => {
                                                            this.setState({ highlightedStars7: i + 1 });
                                                        }}
                                                        onClick={() => {
                                                            if (selectedStars7 % 1 === 0) {
                                                                setAttributes({
                                                                    selectedStars7: i + (selectedStars7 - 1 === i ? 0.5 : 1)
                                                                });
                                                            } else {
                                                                setAttributes({
                                                                    selectedStars7: i + (selectedStars7 - 0.5 === i ? 1 : 0.5)
                                                                });
                                                            }
                                                        }}
                                                    >
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
                                            <RichText
                                                tagName={Tag7}
                                                value={titleText7} 
                                                className="affiliate-coupon-title7"
                                                placeholder={__('Product Title')}
                                                onChange={titleText7 => setAttributes({ titleText7 })}
                                                
                                            />    
                                        }

                                        <Fragment>
                                            { imageDesignType7 && imageDesignType7 == 'ab-fixed-design' && !externalImage7 && !externalImage7 == true &&   
                                              <MediaUpload              
                                                  onSelect={ this.onSelectImage7 }
                                                  allowedTypes= { [ "image" ] }
                                                  value={image7}             
                                                  render={ ( { open } ) => (                    
                                                    <a className="block-editor-button" onClick={ open }>
                                                    {/* <img  src={image7.url} alt={image7.url ? titleText7 : ''} />
                                                    */}
                                                    <ReviewImage7 attributes={attributes} />                                            
                                                        {__("Add-Image")}                       
                                                    </a>
                                                  ) }
                                                />
                                            }       

                                            { externalImage7 && externalImage7 == true &&
                                                  <div className={`affiliate-coupon-image7`}>
                                                      <img src={externalImageSrc7}  alt={externalImageAlt7} />
                                                  </div>
                                              }                               
                                      </Fragment>

                                      { showContent && showContent == true &&
                                          <div className={`affiliate-block-advanced-list affiliate-coupon-list7`}>
                                             <RichList
                                                  key={'listTextItems7'}
                                                  value={ listTextItems7 }
                                                  attributeKey={'listTextItems7'}
                                                  className={`affiliate-list7 affiliate-list-type-unordered affiliate-alignment-${alignment7} affiliate-list-bullet-${bulletStyle7.name}`}
                                                  editProps={this.props}
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
                                            <div className={`affiliate-coupon-btn-wrapper7`}  onClick={() => { this.setState({ isVisible7: !isVisible7 })}}>
                                                <span className={`affiliate-btn  btn-is-${btnSize7} ${
                                                          buttonWidth7 === "full" ? "btn-is-fullw" : buttonWidth7 === "flex" ? `btn-is-flex-${btnSize7}` : "" } ${bntRounded7 ? 'btn-is-rounded' : ''}   ${enableGlimmerEffect7 ? 'glimmer-effect' : ''}`}>
                                                    { iconPosition7 && iconPosition7 == 'af-icon-is-left' &&
                                                          <i className={`affiliate-abbtn-icon ${buttonIcon7} ${iconPosition7}`}></i>
                                                    }
                                                    { iconPosition7 && iconPosition7 != 'af-icon-is-left' &&
                                                      <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText
                                                        value={spBtnText7}
                                                        placeholder={__('Button text')}
                                                        onChange={spBtnText7 => setAttributes({ spBtnText7 })}
                                                        allowedFormats={ ['bold', 'italic', 'underline'] }
                                                        href = {btn_Htarget7}
                                                        {...(btn_Htarget7 ? {...btn_Htarget7 && { rel: newrel7 }} : { rel: newrel7 }  )}
                                                        {...(btn_Htarget7 ? { target: '_blank' } : '' )}
                                                    />
                                                    { iconPosition7 && iconPosition7 == 'af-icon-is-right' &&
                                                          <i className={`affiliate-abbtn-icon ${buttonIcon7} ${iconPosition7}`}></i>
                                                    }
                                                    { iconPosition7 && iconPosition7 != 'af-icon-is-right' &&
                                                      <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </span>    
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
                                            <RichText
                                                tagName="div"
                                                value={topText1} 
                                                className="affiliate-coupon-toptext1"
                                                onChange={topText1 => setAttributes({ topText1 })}
                                            />
                                        }

                                        <Fragment>                                    
                                            { imageDesignType1 && imageDesignType1 == 'ab-fixed-design' && !externalImage1 && !externalImage1 == true &&  
                                              <MediaUpload              
                                                  onSelect={ this.onSelectImage1 }
                                                  allowedTypes= { [ "image" ] }
                                                  value={image1}             
                                                  render={ ( { open } ) => (                    
                                                    <a className="block-editor-button" onClick={ open }>
                                                    {/* <img  src={image1.url} alt={image1.url ? titleText1 : ''} />
                                                    */}
                                                    <ReviewImage1 attributes={attributes} />                                            
                                                        {__("Add-Image")}                       
                                                    </a>
                                                  ) }
                                                />
                                            }             


                                            { externalImage1 && externalImage1 == true &&
                                                  <div className={`affiliate-coupon-image1`}>
                                                      <img src={externalImageSrc1}  alt={externalImageAlt1} />
                                                  </div>
                                              }                       
                                        </Fragment>  

                                        { showTitle && showTitle == true &&
                                            <RichText
                                                tagName={Tag1}
                                                value={titleText1} 
                                                className="affiliate-coupon-title1"
                                                placeholder={__('Product Title')}
                                                onChange={titleText1 => setAttributes({ titleText1 })}
                                                
                                            />    
                                        }


                                        { showContent && showContent == true &&
                                            <RichText
                                                tagName="p"
                                                value={listTextItems1} 
                                                className="affiliate-coupon-content1"                                                
                                                placeholder={__('Nullam et nibh ac nulla commodo ultricies ac in dui. Mauris mollis leo vel nulla scelerisque lobortis.')}
                                                onChange={listTextItems1 => setAttributes({ listTextItems1 })}
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
                                                <div className={`affiliate-coupon-btn-wrapper1`}  onClick={() => { this.setState({ isVisible1: !isVisible1 })}}>
                                                    <span className={`affiliate-btn  btn-is-${btnSize1} ${
                                                              buttonWidth1 === "full" ? "btn-is-fullw" : buttonWidth1 === "flex" ? `btn-is-flex-${btnSize1}` : "" } ${bntRounded1 ? 'btn-is-rounded' : ''}   ${enableGlimmerEffect ? 'glimmer-effect' : ''}`}>
                                                        { iconPosition1 && iconPosition1 == 'af-icon-is-left' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon1} ${iconPosition1}`}></i>
                                                        }
                                                        { iconPosition1 && iconPosition1 != 'af-icon-is-left' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                        }
                                                        <RichText
                                                            value={spBtnText1}
                                                            placeholder={__('Button text')}
                                                            onChange={spBtnText1 => setAttributes({ spBtnText1 })}
                                                            allowedFormats={ ['bold', 'italic', 'underline'] }
                                                            href = {btn_Htarget1}
                                                            {...(btn_Htarget1 ? {...btn_Htarget1 && { rel: newrel1 }} : { rel: newrel1 }  )}
                                                            {...(btn_Htarget1 ? { target: '_blank' } : '' )}
                                                        />
                                                        { iconPosition1 && iconPosition1 == 'af-icon-is-right' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon1} ${iconPosition1}`}></i>
                                                        }
                                                        { iconPosition1 && iconPosition1 != 'af-icon-is-right' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                        }
                                                    </span>    
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
                                            <RichText
                                                tagName="div"
                                                value={bottomText1} 
                                                className="affiliate-coupon-bottomtext1"
                                                onChange={bottomText1 => setAttributes({ bottomText1 })}
                                            />
                                        }
                                   
                                </li>
                                }

                                { numberOfBlock && numberOfBlock >= '2' &&
                                <li className={`affiliate-block-col box2`}>

                                        { showtopText && showtopText == true &&
                                            <RichText
                                                tagName="div"
                                                value={topText2} 
                                                className="affiliate-coupon-toptext2"
                                                onChange={topText2 => setAttributes({ topText2 })}
                                            />
                                        }

                                        <Fragment>                                    
                                            { imageDesignType2 && imageDesignType2 == 'ab-fixed-design' &&  !externalImage2 && !externalImage2 == true &&  
                                              <MediaUpload              
                                                  onSelect={ this.onSelectImage2 }
                                                  allowedTypes= { [ "image" ] }
                                                  value={image2}             
                                                  render={ ( { open } ) => (                    
                                                    <a className="block-editor-button" onClick={ open }>
                                                    {/* <img  src={image2.url} alt={image2.url ? titleText2 : ''} />
                                                    */}
                                                    <ReviewImage2 attributes={attributes} />                                            
                                                        {__("Add-Image")}                       
                                                    </a>
                                                  ) }
                                                />
                                            }         

                                            { externalImage2 && externalImage2 == true &&
                                                  <div className={`affiliate-coupon-image2`}>
                                                      <img src={externalImageSrc2}  alt={externalImageAlt2} />
                                                  </div>
                                              }                              
                                        </Fragment>
                                          
                                        { showTitle && showTitle == true &&
                                            <RichText
                                                tagName={Tag2}
                                                value={titleText2} 
                                                className="affiliate-coupon-title2"
                                                placeholder={__('Product Title')}
                                                onChange={titleText2 => setAttributes({ titleText2 })}
                                                
                                            />    
                                        }

                                        { showContent && showContent == true &&
                                            <RichText
                                                tagName="p"
                                                value={listTextItems2} 
                                                className="affiliate-coupon-content2"                                                
                                                placeholder={__('Nullam et nibh ac nulla commodo ultricies ac in dui. Mauris mollis leo vel nulla scelerisque lobortis.')}
                                                onChange={listTextItems2 => setAttributes({ listTextItems2 })}
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
                                                <div className={`affiliate-coupon-btn-wrapper2`}  onClick={() => { this.setState({ isVisible2: !isVisible2 })}}>
                                                    <span className={`affiliate-btn  btn-is-${btnSize2} ${
                                                              buttonWidth2 === "full" ? "btn-is-fullw" : buttonWidth2 === "flex" ? `btn-is-flex-${btnSize2}` : "" } ${bntRounded2 ? 'btn-is-rounded' : ''}   ${enableGlimmerEffect2 ? 'glimmer-effect' : ''}`}>
                                                        { iconPosition2 && iconPosition2 == 'af-icon-is-left' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon2} ${iconPosition2}`}></i>
                                                        }
                                                        { iconPosition2 && iconPosition2 != 'af-icon-is-left' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                        }
                                                        <RichText
                                                            value={spBtnText2}
                                                            placeholder={__('Button text')}
                                                            onChange={spBtnText2 => setAttributes({ spBtnText2 })}
                                                            allowedFormats={ ['bold', 'italic', 'underline'] }
                                                            href = {btn_Htarget2}
                                                            {...(btn_Htarget2 ? {...btn_Htarget2 && { rel: newrel2 }} : { rel: newrel2 }  )}
                                                            {...(btn_Htarget2 ? { target: '_blank' } : '' )}
                                                        />
                                                        { iconPosition2 && iconPosition2 == 'af-icon-is-right' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon2} ${iconPosition2}`}></i>
                                                        }
                                                        { iconPosition2 && iconPosition2 != 'af-icon-is-right' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                        }
                                                    </span>    
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
                                            <RichText
                                                tagName="div"
                                                value={bottomText2} 
                                                className="affiliate-coupon-bottomtext2"
                                                onChange={bottomText2 => setAttributes({ bottomText2 })}
                                            />
                                        }
                                   
                                </li>
                                }

                                { numberOfBlock && numberOfBlock >= '3' &&
                                <li className={`affiliate-block-col box3`}>

                                        { showtopText && showtopText == true &&
                                            <RichText
                                                tagName="div"
                                                value={topText3} 
                                                className="affiliate-coupon-toptext3"
                                                onChange={topText3 => setAttributes({ topText3 })}
                                            />
                                        }

                                        <Fragment>
                                            { imageDesignType3 && imageDesignType3 == 'ab-fixed-design' &&  !externalImage3 && !externalImage3 == true &&  
                                              <MediaUpload              
                                                  onSelect={ this.onSelectImage3 }
                                                  allowedTypes= { [ "image" ] }
                                                  value={image3}             
                                                  render={ ( { open } ) => (                    
                                                    <a className="block-editor-button" onClick={ open }>
                                                    {/* <img  src={image3.url} alt={image3.url ? titleText3 : ''} />
                                                    */}
                                                    <ReviewImage3 attributes={attributes} />                                            
                                                        {__("Add-Image")}                       
                                                    </a>
                                                  ) }
                                                />
                                            }     

                                            { externalImage3 && externalImage3 == true &&
                                                  <div className={`affiliate-coupon-image3`}>
                                                      <img src={externalImageSrc3}  alt={externalImageAlt3} />
                                                  </div>
                                              }                                   
                                        </Fragment>
                                      

                                        { showTitle && showTitle == true &&
                                            <RichText
                                                tagName={Tag3}
                                                value={titleText3} 
                                                className="affiliate-coupon-title3"
                                                placeholder={__('Product Title')}
                                                onChange={titleText3 => setAttributes({ titleText3 })}
                                                
                                            />    
                                        }                                       


                                        { showContent && showContent == true &&
                                            <RichText
                                                tagName="p"
                                                value={listTextItems3} 
                                                className="affiliate-coupon-content3"                                                
                                                placeholder={__('Nullam et nibh ac nulla commodo ultricies ac in dui. Mauris mollis leo vel nulla scelerisque lobortis.')}
                                                onChange={listTextItems3 => setAttributes({ listTextItems3 })}
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
                                                <div className={`affiliate-coupon-btn-wrapper3`}  onClick={() => { this.setState({ isVisible3: !isVisible3 })}}>
                                                    <span className={`affiliate-btn  btn-is-${btnSize3} ${
                                                              buttonWidth3 === "full" ? "btn-is-fullw" : buttonWidth3 === "flex" ? `btn-is-flex-${btnSize3}` : "" } ${bntRounded3 ? 'btn-is-rounded' : ''}   ${enableGlimmerEffect3 ? 'glimmer-effect' : ''}`}>
                                                        { iconPosition3 && iconPosition3 == 'af-icon-is-left' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon3} ${iconPosition3}`}></i>
                                                        }
                                                        { iconPosition3 && iconPosition3 != 'af-icon-is-left' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                        }
                                                        <RichText
                                                            value={spBtnText3}
                                                            placeholder={__('Button text')}
                                                            onChange={spBtnText3 => setAttributes({ spBtnText3 })}
                                                            allowedFormats={ ['bold', 'italic', 'underline'] }
                                                            href = {btn_Htarget3}
                                                            {...(btn_Htarget3 ? {...btn_Htarget3 && { rel: newrel3 }} : { rel: newrel3 }  )}
                                                            {...(btn_Htarget3 ? { target: '_blank' } : '' )}
                                                        />
                                                        { iconPosition3 && iconPosition3 == 'af-icon-is-right' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon3} ${iconPosition3}`}></i>
                                                        }
                                                        { iconPosition3 && iconPosition3 != 'af-icon-is-right' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                        }
                                                    </span>    
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
                                            <RichText
                                                tagName="div"
                                                value={bottomText3} 
                                                className="affiliate-coupon-bottomtext3"
                                                onChange={bottomText3 => setAttributes({ bottomText3 })}
                                            />
                                        }
                                   
                                </li>
                                }

                                { numberOfBlock && numberOfBlock >= '4' &&
                                <li className={`affiliate-block-col box4`}>

                                        { showtopText && showtopText == true &&
                                            <RichText
                                                tagName="div"
                                                value={topText4} 
                                                className="affiliate-coupon-toptext4"
                                                onChange={topText4 => setAttributes({ topText4 })}
                                            />
                                        }
                                        
                                        <Fragment>
                                            { imageDesignType4 && imageDesignType4 == 'ab-fixed-design' && !externalImage4 && !externalImage4 == true &&    
                                              <MediaUpload              
                                                  onSelect={ this.onSelectImage4 }
                                                  allowedTypes= { [ "image" ] }
                                                  value={image4}             
                                                  render={ ( { open } ) => (                    
                                                    <a className="block-editor-button" onClick={ open }>
                                                    {/* <img  src={image4.url} alt={image4.url ? titleText4 : ''} />
                                                    */}
                                                    <ReviewImage4 attributes={attributes} />                                            
                                                        {__("Add-Image")}                       
                                                    </a>
                                                  ) }
                                                />
                                            }      

                                            { externalImage4 && externalImage4 == true &&
                                                  <div className={`affiliate-coupon-image4`}>
                                                      <img src={externalImageSrc4}  alt={externalImageAlt4} />
                                                  </div>
                                              }                                  
                                        </Fragment>

                                        { showTitle && showTitle == true &&
                                            <RichText
                                                tagName={Tag4}
                                                value={titleText4} 
                                                className="affiliate-coupon-title4"
                                                placeholder={__('Product Title')}
                                                onChange={titleText4 => setAttributes({ titleText4 })}
                                                
                                            />    
                                        }

                                        

                                        { showContent && showContent == true &&
                                            <RichText
                                                tagName="p"
                                                value={listTextItems4} 
                                                className="affiliate-coupon-content4"                                                
                                                placeholder={__('Nullam et nibh ac nulla commodo ultricies ac in dui. Mauris mollis leo vel nulla scelerisque lobortis.')}
                                                onChange={listTextItems4 => setAttributes({ listTextItems4 })}
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
                                                <div className={`affiliate-coupon-btn-wrapper4`}  onClick={() => { this.setState({ isVisible4: !isVisible4 })}}>
                                                    <span className={`affiliate-btn  btn-is-${btnSize4} ${
                                                              buttonWidth4 === "full" ? "btn-is-fullw" : buttonWidth4 === "flex" ? `btn-is-flex-${btnSize4}` : "" } ${bntRounded4 ? 'btn-is-rounded' : ''}   ${enableGlimmerEffect4 ? 'glimmer-effect' : ''}`}>
                                                        { iconPosition4 && iconPosition4 == 'af-icon-is-left' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon4} ${iconPosition4}`}></i>
                                                        }
                                                        { iconPosition4 && iconPosition4 != 'af-icon-is-left' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                        }
                                                        <RichText
                                                            value={spBtnText4}
                                                            placeholder={__('Button text')}
                                                            onChange={spBtnText4 => setAttributes({ spBtnText4 })}
                                                            allowedFormats={ ['bold', 'italic', 'underline'] }
                                                            href = {btn_Htarget4}
                                                            {...(btn_Htarget4 ? {...btn_Htarget4 && { rel: newrel4 }} : { rel: newrel4 }  )}
                                                            {...(btn_Htarget4 ? { target: '_blank' } : '' )}
                                                        />
                                                        { iconPosition4 && iconPosition4 == 'af-icon-is-right' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon4} ${iconPosition4}`}></i>
                                                        }
                                                        { iconPosition4 && iconPosition4 != 'af-icon-is-right' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                        }
                                                    </span>    
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
                                            <RichText
                                                tagName="div"
                                                value={bottomText4} 
                                                className="affiliate-coupon-bottomtext4"
                                                onChange={bottomText4 => setAttributes({ bottomText4 })}
                                            />
                                        }
                                   
                                </li>
                                }

                                { numberOfBlock && numberOfBlock >= '5' &&
                                <li className={`affiliate-block-col box5`}>

                                        { showtopText && showtopText == true &&
                                            <RichText
                                                tagName="div"
                                                value={topText5} 
                                                className="affiliate-coupon-toptext5"
                                                onChange={topText5 => setAttributes({ topText5 })}
                                            />
                                        }

                                        <Fragment>
                                            { imageDesignType5 && imageDesignType5 == 'ab-fixed-design' && !externalImage5 && !externalImage5 == true &&   
                                              <MediaUpload              
                                                  onSelect={ this.onSelectImage5 }
                                                  allowedTypes= { [ "image" ] }
                                                  value={image5}             
                                                  render={ ( { open } ) => (                    
                                                    <a className="block-editor-button" onClick={ open }>
                                                    {/* <img  src={image5.url} alt={image5.url ? titleText5 : ''} />
                                                    */}
                                                    <ReviewImage5 attributes={attributes} />                                            
                                                        {__("Add-Image")}                       
                                                    </a>
                                                  ) }
                                                />
                                            }       

                                            { externalImage5 && externalImage5 == true &&
                                                  <div className={`affiliate-coupon-image5`}>
                                                      <img src={externalImageSrc5}  alt={externalImageAlt5} />
                                                  </div>
                                              }                              
                                        </Fragment>
                                            
                                        { showTitle && showTitle == true &&
                                            <RichText
                                                tagName={Tag5}
                                                value={titleText5} 
                                                className="affiliate-coupon-title5"
                                                placeholder={__('Product Title')}
                                                onChange={titleText5 => setAttributes({ titleText5 })}
                                                
                                            />    
                                        }


                                        { showContent && showContent == true &&
                                              <RichText
                                                    tagName="p"
                                                    value={listTextItems1} 
                                                    className="affiliate-coupon-content5"                                                
                                                    placeholder={__('Nullam et nibh ac nulla commodo ultricies ac in dui. Mauris mollis leo vel nulla scelerisque lobortis.')}
                                                    onChange={listTextItems5 => setAttributes({ listTextItems5 })}
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
                                                <div className={`affiliate-coupon-btn-wrapper5`}  onClick={() => { this.setState({ isVisible5: !isVisible5 })}}>
                                                    <span className={`affiliate-btn  btn-is-${btnSize5} ${
                                                              buttonWidth5 === "full" ? "btn-is-fullw" : buttonWidth5 === "flex" ? `btn-is-flex-${btnSize5}` : "" } ${bntRounded5 ? 'btn-is-rounded' : ''}   ${enableGlimmerEffect5 ? 'glimmer-effect' : ''}`}>
                                                        { iconPosition5 && iconPosition5 == 'af-icon-is-left' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon5} ${iconPosition5}`}></i>
                                                        }
                                                        { iconPosition5 && iconPosition5 != 'af-icon-is-left' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                        }
                                                        <RichText
                                                            value={spBtnText5}
                                                            placeholder={__('Button text')}
                                                            onChange={spBtnText5 => setAttributes({ spBtnText5 })}
                                                            allowedFormats={ ['bold', 'italic', 'underline'] }
                                                            href = {btn_Htarget5}
                                                            {...(btn_Htarget5 ? {...btn_Htarget5 && { rel: newrel5 }} : { rel: newrel5 }  )}
                                                            {...(btn_Htarget5 ? { target: '_blank' } : '' )}
                                                        />
                                                        { iconPosition5 && iconPosition5 == 'af-icon-is-right' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon5} ${iconPosition5}`}></i>
                                                        }
                                                        { iconPosition5 && iconPosition5 != 'af-icon-is-right' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                        }
                                                    </span>    
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
                                            <RichText
                                                tagName="div"
                                                value={bottomText5} 
                                                className="affiliate-coupon-bottomtext5"
                                                onChange={bottomText5 => setAttributes({ bottomText5 })}
                                            />
                                        }
                                   
                                </li>
                                }

                                { numberOfBlock && numberOfBlock >= '6' &&
                                <li className={`affiliate-block-col box6`}>

                                        { showtopText && showtopText == true &&
                                            <RichText
                                                tagName="div"
                                                value={topText6} 
                                                className="affiliate-coupon-toptext6"
                                                onChange={topText6 => setAttributes({ topText6 })}
                                            />
                                        }
                                        
                                        <Fragment>
                                            { imageDesignType6 && imageDesignType6 == 'ab-fixed-design' && !externalImage6 && !externalImage6 == true &&  
                                              <MediaUpload              
                                                  onSelect={ this.onSelectImage6 }
                                                  allowedTypes= { [ "image" ] }
                                                  value={image6}             
                                                  render={ ( { open } ) => (                    
                                                    <a className="block-editor-button" onClick={ open }>
                                                    {/* <img  src={image6.url} alt={image6.url ? titleText6 : ''} />
                                                    */}
                                                    <ReviewImage6 attributes={attributes} />                                            
                                                        {__("Add-Image")}                       
                                                    </a>
                                                  ) }
                                                />
                                            }

                                            { externalImage6 && externalImage6 == true &&
                                                  <div className={`affiliate-coupon-image6`}>
                                                      <img src={externalImageSrc6}  alt={externalImageAlt6} />
                                                  </div>
                                              }                                       
                                        </Fragment>

                                        { showTitle && showTitle == true &&
                                            <RichText
                                                tagName={Tag6}
                                                value={titleText6} 
                                                className="affiliate-coupon-title6"
                                                placeholder={__('Product Title')}
                                                onChange={titleText6 => setAttributes({ titleText6 })}
                                                
                                            />    
                                        }

                                        

                                        { showContent && showContent == true &&
                                              <RichText
                                                tagName="p"
                                                value={listTextItems6} 
                                                className="affiliate-coupon-content6"                                                
                                                placeholder={__('Nullam et nibh ac nulla commodo ultricies ac in dui. Mauris mollis leo vel nulla scelerisque lobortis.')}
                                                onChange={listTextItems6 => setAttributes({ listTextItems6 })}
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
                                                <div className={`affiliate-coupon-btn-wrapper6`}  onClick={() => { this.setState({ isVisible6: !isVisible6 })}}>
                                                    <span className={`affiliate-btn  btn-is-${btnSize6} ${
                                                              buttonWidth6 === "full" ? "btn-is-fullw" : buttonWidth6 === "flex" ? `btn-is-flex-${btnSize6}` : "" } ${bntRounded6 ? 'btn-is-rounded' : ''}   ${enableGlimmerEffect6 ? 'glimmer-effect' : ''}`}>
                                                        { iconPosition6 && iconPosition6 == 'af-icon-is-left' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon6} ${iconPosition6}`}></i>
                                                        }
                                                        { iconPosition6 && iconPosition6 != 'af-icon-is-left' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                        }
                                                        <RichText
                                                            value={spBtnText6}
                                                            placeholder={__('Button text')}
                                                            onChange={spBtnText6 => setAttributes({ spBtnText6 })}
                                                            allowedFormats={ ['bold', 'italic', 'underline'] }
                                                            href = {btn_Htarget6}
                                                            {...(btn_Htarget6 ? {...btn_Htarget6 && { rel: newrel6 }} : { rel: newrel6 }  )}
                                                            {...(btn_Htarget6 ? { target: '_blank' } : '' )}
                                                        />
                                                        { iconPosition6 && iconPosition6 == 'af-icon-is-right' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon6} ${iconPosition6}`}></i>
                                                        }
                                                        { iconPosition6 && iconPosition6 != 'af-icon-is-right' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                        }
                                                    </span>    
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
                                            <RichText
                                                tagName="div"
                                                value={bottomText6} 
                                                className="affiliate-coupon-bottomtext6"
                                                onChange={bottomText6 => setAttributes({ bottomText6 })}
                                            />
                                        }
                                   
                                </li>
                                }

                                { numberOfBlock && numberOfBlock >= '7' &&
                                <li className={`affiliate-block-col box7`}>

                                        { showtopText && showtopText == true &&
                                            <RichText
                                                tagName="div"
                                                value={topText7} 
                                                className="affiliate-coupon-toptext7"
                                                onChange={topText7 => setAttributes({ topText7 })}
                                            />
                                        }

                                        <Fragment>
                                            { imageDesignType7 && imageDesignType7 == 'ab-fixed-design' && !externalImage7 && !externalImage7 == true &&   
                                              <MediaUpload              
                                                  onSelect={ this.onSelectImage7 }
                                                  allowedTypes= { [ "image" ] }
                                                  value={image7}             
                                                  render={ ( { open } ) => (                    
                                                    <a className="block-editor-button" onClick={ open }>
                                                    {/* <img  src={image7.url} alt={image7.url ? titleText7 : ''} />
                                                    */}
                                                    <ReviewImage7 attributes={attributes} />                                            
                                                        {__("Add-Image")}                       
                                                    </a>
                                                  ) }
                                                />
                                            }       

                                            { externalImage7 && externalImage7 == true &&
                                                  <div className={`affiliate-coupon-image7`}>
                                                      <img src={externalImageSrc7}  alt={externalImageAlt7} />
                                                  </div>
                                              }                               
                                        </Fragment>
                                          

                                        { showTitle && showTitle == true &&
                                            <RichText
                                                tagName={Tag7}
                                                value={titleText7} 
                                                className="affiliate-coupon-title7"
                                                placeholder={__('Product Title')}
                                                onChange={titleText7 => setAttributes({ titleText7 })}
                                                
                                            />    
                                        }
                                       

                                        { showContent && showContent == true &&
                                          <RichText
                                                tagName="p"
                                                value={listTextItems7} 
                                                className="affiliate-coupon-content7"                                                
                                                placeholder={__('Nullam et nibh ac nulla commodo ultricies ac in dui. Mauris mollis leo vel nulla scelerisque lobortis.')}
                                                onChange={listTextItems7 => setAttributes({ listTextItems7 })}
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
                                                <div className={`affiliate-coupon-btn-wrapper7`}  onClick={() => { this.setState({ isVisible7: !isVisible7 })}}>
                                                    <span className={`affiliate-btn  btn-is-${btnSize7} ${
                                                              buttonWidth7 === "full" ? "btn-is-fullw" : buttonWidth7 === "flex" ? `btn-is-flex-${btnSize7}` : "" } ${bntRounded7 ? 'btn-is-rounded' : ''}   ${enableGlimmerEffect7 ? 'glimmer-effect' : ''}`}>
                                                        { iconPosition7 && iconPosition7 == 'af-icon-is-left' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon7} ${iconPosition7}`}></i>
                                                        }
                                                        { iconPosition7 && iconPosition7 != 'af-icon-is-left' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                        }
                                                        <RichText
                                                            value={spBtnText7}
                                                            placeholder={__('Button text')}
                                                            onChange={spBtnText7 => setAttributes({ spBtnText7 })}
                                                            allowedFormats={ ['bold', 'italic', 'underline'] }
                                                            href = {btn_Htarget7}
                                                            {...(btn_Htarget7 ? {...btn_Htarget7 && { rel: newrel7 }} : { rel: newrel7 }  )}
                                                            {...(btn_Htarget7 ? { target: '_blank' } : '' )}
                                                        />
                                                        { iconPosition7 && iconPosition7 == 'af-icon-is-right' &&
                                                              <i className={`affiliate-abbtn-icon ${buttonIcon7} ${iconPosition7}`}></i>
                                                        }
                                                        { iconPosition7 && iconPosition7 != 'af-icon-is-right' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                        }
                                                    </span>    
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
                                            <RichText
                                                tagName="div"
                                                value={bottomText7} 
                                                className="affiliate-coupon-bottomtext7"
                                                onChange={bottomText7 => setAttributes({ bottomText7 })}
                                            />
                                        }
                                   
                                </li>
                                }
                            </Fragment>
                        }


                    </ul>                        
                    
                </div>
                {loadtitleGoogleFonts1}
                {loadlistGoogleFonts1}
                {loadbtnGoogleFotns1}
                {loadtopGoogleFotns1}
                {loadbottomGoogleFotns1}

                {loadtitleGoogleFonts2}
                {loadlistGoogleFonts2}
                {loadbtnGoogleFotns2}
                {loadtopGoogleFotns2}
                {loadbottomGoogleFotns2}

                {loadtitleGoogleFonts3}
                {loadlistGoogleFonts3}
                {loadbtnGoogleFotns3}
                {loadtopGoogleFotns3}
                {loadbottomGoogleFotns3}

                {loadtitleGoogleFonts4}
                {loadlistGoogleFonts4}
                {loadbtnGoogleFotns4}
                {loadtopGoogleFotns4}
                {loadbottomGoogleFotns4}

                {loadtitleGoogleFonts5}
                {loadlistGoogleFonts5}
                {loadbtnGoogleFotns5}
                {loadtopGoogleFotns5}
                {loadbottomGoogleFotns5}

                {loadtitleGoogleFonts6}
                {loadlistGoogleFonts6}
                {loadbtnGoogleFotns6}
                {loadtopGoogleFotns6}
                {loadbottomGoogleFotns6}

                {loadtitleGoogleFonts7}
                {loadlistGoogleFonts7}
                {loadbtnGoogleFotns7}
                {loadtopGoogleFotns7}
                {loadbottomGoogleFotns7}


            </Fragment>
        )
    }       
}

const applyWithSelect = withSelect( ( select, props ) => {
  const { getBlocks, getBlocksByClientId } = select( 'core/block-editor' );
	const innerBlocks = getBlocks( props.clientId );
    return {
      innerBlocks,
      hasInnerBlocks: select( 'core/block-editor' ).getBlocks( props.clientId ).length > 1,
    }
} );
export default compose( withNotices, applyWithSelect )( Edit );