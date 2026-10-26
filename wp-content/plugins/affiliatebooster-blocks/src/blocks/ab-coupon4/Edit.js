import RadioAdvanced from '../../components/fields/RadioAdvanced.js';
import BorderTypes from '../../components/fields/BorderTypes.js';
import Alignment from '../../components/fields/Alignment.js';
import Dimension from '../../components/fields/Dimension/Dimension.js';
import IconSelector from '../../components/fields/IconSelector/IconSelector.js';
import TypographyControl from '../components/typography/index.js';
import WebfontLoader from "../components/typography/fontloader"
import Range from '../../components/fields/Range/Range.js';
import Color  from '../../components/fields/Color/Color.js';
import affiliate_styling  from './styling.js';
import Media from './../../components/fields/Media/Media.js';
import IconList from '../../components/fields/IconList/IconList.js';
import BoxShadow  from './../../components/fields/BoxShadow.js';
import Background  from './../../components/fields/Background/Background.js';
import Tabs from '../../components/fields/Tabs/Tabs.js';
import Tab from '../../components/fields/Tab/Tab.js'; 
import ReviewImage from "./reviewImage.js" 
import Toggle from '../../components/fields/Toggle.js';
import { EmptyStar, FullStar, HalfStar } from "./icons";
import getIcon from '../../icons/get-icon';


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

let imageSizeOptions = [
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
            removeItemViaBackSpace: 999,
            isVisible:false,
            
            highlightedStars : 0
        }

        this.onSelectImage    = this.onSelectImage.bind(this)  
        this.getImageSize  	  = this.getImageSize.bind(this)  
        this.getSlug     = this.getSlug.bind(this)
    }

      /*
	 * Event to set Image as while adding.
	 */
	onSelectImage( media ) {
		const { image } = this.props.attributes
		const { setAttributes } = this.props

		if ( ! media || ! media.url ) {
			setAttributes( { image: null } )
			return
		}

		if ( ! media.type || "image" !== media.type ) {
			setAttributes( { image: null } )
			return
		}
		setAttributes( { image: media } )
	}

     
	getImageSize( sizes ) {
		var size_arr = []
		$.each(sizes, function (index, item) {
		  var name = index
		  	var p = { "value" : name, "label": name }
		  	size_arr.push(p)
		})
		return(size_arr)
	}

   
    getSlug( title ) {
      return title.toString().toLowerCase().replace(/( |<.+?>|&nbsp;)/g, "-");
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
      onRemoveImage() {
        const { image } = this.props.attributes
        const { setAttributes } = this.props
        setAttributes( { image: null } )
      }
  
      removeImage( url ){
        const { multiple, onChangem,setAttributes } = this.props
        const { image } = this.props.attributes
          setAttributes( { image: {} } )
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
            highlightedStars,
            hasInnerBlocks,
            attributes: {
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

            }
        } = this.props;
        const { isVisible } = this.state
        
        var element = document.getElementById("affiliate-style-" + this.props.clientId)
        if (null != element && "undefined" != typeof element) {
             element.innerHTML = affiliate_styling(this.props, "affiliate-style")
        }

        // imageSizeOptions = this.getImageSize(image["sizes"]

        let loadtitleGoogleFonts 
        let loadverifiedGoogleFonts
        let loadstaffpickGoogleFonts
        let loadpeopleUsedGoogleFonts
        let loadonlyLeftGoogleFonts
        let loadonGoingOfferGoogleFonts
        let loadsubTitleGoogleFonts
        let loadbtnGoogleFotns

        if( titleLoadGoogleFonts == true ) {
					
			const titleconfig = {
				google: {
					families: [ titlefontFamily + ( titlefontWeight ? ":" + titlefontWeight : "" ) ],
				},
			}

			loadtitleGoogleFonts = (
				<WebfontLoader config={ titleconfig }>
				</WebfontLoader>
			)
        }
        
        if( contentLoadGoogleFonts == true ) {
					
			const contentconfig = {
				google: {
					families: [ contentfontFamily + ( contentfontWeight ? ":" + contentfontWeight : "" ) ],
				},
			}

			loadsubTitleGoogleFonts = (
				<WebfontLoader config={ contentconfig }>
				</WebfontLoader>
			)
        }

        if( btnLoadGoogleFonts == true ) {
					
			const btnconfig = {
				google: {
					families: [ btnfontFamily + ( btnfontWeight ? ":" + btnfontWeight : "" ) ],
				},
			}

			loadbtnGoogleFotns = (
				<WebfontLoader config={ btnconfig }>
				</WebfontLoader>
			)
        }
 

        if( verifiedLoadGoogleFonts == true ) {
                    
            const verifiedconfig = {
                google: {
                    families: [ verifiedfontFamily + ( verifiedfontWeight ? ":" + verifiedfontWeight : "" ) ],
                },
            }

            loadverifiedGoogleFonts = (
                <WebfontLoader config={ verifiedconfig }>
                </WebfontLoader>
            )
        }


        if( staffpickLoadGoogleFonts == true ) {
                    
            const staffpickconfig = {
                google: {
                    families: [ staffpickfontFamily + ( staffpickfontWeight ? ":" + staffpickfontWeight : "" ) ],
                },
            }

            loadstaffpickGoogleFonts = (
                <WebfontLoader config={ staffpickconfig }>
                </WebfontLoader>
            )
        }


        if( peopleUsedLoadGoogleFonts == true ) {
                    
            const peopleUsedconfig = {
                google: {
                    families: [ peopleUsedfontFamily + ( peopleUsedfontWeight ? ":" + peopleUsedfontWeight : "" ) ],
                },
            }

            loadpeopleUsedGoogleFonts = (
                <WebfontLoader config={ peopleUsedconfig }>
                </WebfontLoader>
            )
        }

        if( onlyLeftLoadGoogleFonts == true ) {
                    
            const onlyLeftconfig = {
                google: {
                    families: [ onlyLeftfontFamily + ( onlyLeftfontWeight ? ":" + onlyLeftfontWeight : "" ) ],
                },
            }

            loadonlyLeftGoogleFonts = (
                <WebfontLoader config={ onlyLeftconfig }>
                </WebfontLoader>
            )
        }

        if( onGoingOfferLoadGoogleFonts == true ) {
                    
            const onGoingOfferconfig = {
                google: {
                    families: [ onGoingOfferfontFamily + ( onGoingOfferfontWeight ? ":" + onGoingOfferfontWeight : "" ) ],
                },
            }

            loadonGoingOfferGoogleFonts = (
                <WebfontLoader config={ onGoingOfferconfig }>
                </WebfontLoader>
            )
        }

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
        
        return (
            <Fragment>
                <InspectorControls key="inspector">

                <PanelBody title={`General Settings`} initialOpen={false}  icon={ getIcon( 'documentation' ) } className={ 'abblocks-panel-label' }>

                    <ToggleControl
                        label={ __( 'Show Verified' ) }                        
                        checked={ showVerified }                                      
                        onChange={ ( ) => setAttributes( { showVerified: ! showVerified } ) }
                    />

                    <ToggleControl
                        label={ __( 'Show Staff Pick' ) }                        
                        checked={ showStaffPick }                                      
                        onChange={ ( ) => setAttributes( { showStaffPick: ! showStaffPick } ) }
                    />

                    <ToggleControl
                        label={ __( 'Show Title' ) }                        
                        checked={ showTitle }                                      
                        onChange={ ( ) => setAttributes( { showTitle: ! showTitle } ) }
                    />

                    <ToggleControl
                        label={ __( 'Show Content' ) }                        
                        checked={ showContent }                                      
                        onChange={ ( ) => setAttributes( { showContent: ! showContent } ) }
                    />
                 
                    <ToggleControl
                        label={ __( 'Show People Used' ) }                        
                        checked={ showPeopleUsed }                                      
                        onChange={ ( ) => setAttributes( { showPeopleUsed: ! showPeopleUsed } ) }
                    />

                    <ToggleControl
                        label={ __( 'Show Coupon Left' ) }                        
                        checked={ showCouponLeft }                                      
                        onChange={ ( ) => setAttributes( { showCouponLeft: ! showCouponLeft } ) }
                    />

                    <ToggleControl
                        label={ __( 'Show Rating' ) }                        
                        checked={ showRating }                                      
                        onChange={ ( ) => setAttributes( { showRating: ! showRating } ) }
                    />

                    <ToggleControl
                        label={ __( 'Show On Going Offer' ) }                        
                        checked={ showOnGoingOffer }                                      
                        onChange={ ( ) => setAttributes( { showOnGoingOffer: ! showOnGoingOffer } ) }
                    />          


                    <BorderTypes label={__('Border Type')} value={boxBorderType} onChange={(value) => setAttributes({ boxBorderType: value })} />

                      <Dimension
                        label={__('Border Width')}
                        value={boxBorderWidth}
                        onChange={val => setAttributes({ boxBorderWidth: val })}
                        min={0}
                        max={600}
                        unit={['px','em','rem','%']}
                        clientId={this.props.clientId}
                      />          
                                    
                    <RadioAdvanced
                        label={__('Design Type')}
                        options={[
                            { label: 'Fixed', value: 'ab-fixed-design', title: 'Fixed' },
                            { label: 'Dynamic', value: 'ab-dynamic-design', title: 'Dynamic' },
                        ]}
                        value={designType}
                        onChange={(value) => setAttributes({ designType: value })} />	


                    { designType && designType == 'ab-fixed-design' &&    
                        <ToggleControl
                          label={ __( 'External Image?' ) }                        
                          checked={ externalImage }                                      
                          onChange={ ( ) => setAttributes( { externalImage: ! externalImage } ) }
                        />
                    }

                    { designType && designType == 'ab-fixed-design' &&  externalImage && externalImage == true &&
                    <Fragment>
                      <TextareaControl              
                          label={ __( 'External Image Src' ) }         
                          value={ externalImageSrc }                       
                          onChange={ ( value ) => setAttributes( { externalImageSrc: value } ) }
                      />
                      <TextControl              
                            label={ __( 'External Image Alt' ) }         
                            value={ externalImageAlt }                       
                            onChange={ ( value ) => setAttributes( { externalImageAlt: value } ) }
                        />
                        </Fragment>
                    }
                    
                    <RangeControl
                        label={__("Number of stars")}
                        value={ratings}
                        onChange={value =>
                            setAttributes({
                                ratings: value,
                                selectedStars: value < selectedStars ? value : selectedStars
                            })
                        }
                        min={5}
                        step={1}
                        max={10}
                        // beforeIcon="star-empty"
                        allowReset
                    />

                    <ToggleControl
                        label={ __( 'Show Rating Number' ) }                       
                        checked={ showRatingNumber }                                     
                        onChange={ ( ) => setAttributes( { showRatingNumber: ! showRatingNumber } ) }
                    />

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
                                        <Range
                                            label={__('Stars Size')}
                                            value={starsSizeMobile} 
                                            onChange={(value) => setAttributes({ starsSizeMobile: value })}
                                            unit={[]}
                                            min={10}
                                            step={1}
                                            max={60}
                                        />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                        <Range
                                            label={__('Stars Size')}
                                            value={starsSizeTablet} 
                                            onChange={(value) => setAttributes({ starsSizeTablet: value })}
                                            unit={[]}
                                            min={10}
                                            step={1}
                                            max={60}
                                        />
                                    );
                                  } else {
                                    tabout = (
                                        <Range
                                            label={__('Stars Size')}
                                            value={starsSize} 
                                            onChange={(value) => setAttributes({ starsSize: value })}
                                            unit={[]}
                                            min={10}
                                            step={1}
                                            max={60}
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
                                        label = {__('Star Alignment')}
                                        disableJustify
                                        alignmentType='content'
                                        flex
                                        value={ratingAlignemtMobile}
                                        onChange={(value) => setAttributes({ ratingAlignemtMobile: value })}/>
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                        <Alignment
                                            label = {__('Star Alignment')}
                                            disableJustify
                                            alignmentType='content'
                                            flex
                                            value={ratingAlignemtTablet}
                                            onChange={(value) => setAttributes({ ratingAlignemtTablet: value })}/>
                                    );
                                  } else {
                                    tabout = (
                                    <Alignment
                                        label = {__('Star Alignment')}
                                        disableJustify
                                        alignmentType='content'
                                        flex
                                        value={ratingAlignemt}
                                        onChange={(value) => setAttributes({ ratingAlignemt: value })}/>
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                          </TabPanel>

                          
                     
                                        
                    <BoxShadow label={__('Box-Shadow')} value={boxShadow} onChange={(value) => setAttributes({ boxShadow: value })} />
                </PanelBody>

                <PanelBody title={ __( 'Typography' ) } initialOpen={false}  icon={ getIcon( 'typography' ) } className={ 'abblocks-panel-label' }>

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

                    <TypographyControl
                        label={__("Verified Typography")}
                        attributes={attributes}
                        setAttributes={setAttributes}
                        loadGoogleFonts={{ value:verifiedLoadGoogleFonts, label: __("verifiedLoadGoogleFonts") }}
                        fontFamily={{ value: verifiedfontFamily, label: __("verifiedfontFamily") }}
                        fontWeight={{ value: verifiedfontWeight, label: __("verifiedfontWeight") }}
                        fontSubset={{ value: verifiedfontSubset, label: __("verifiedfontSubset") }}
                        fontSizeType={{ value: verifiedfontSizeType, label: __("verifiedfontSizeType") }}
                        fontSizeTypeMobile={{ value: verifiedfontSizeTypeMobile, label: __("verifiedfontSizeTypeMobile") }}
                        fontSizeTypeTablet={{ value: verifiedfontSizeTypeTablet, label: __("verifiedfontSizeTypeTablet") }}
                        fontSize={{ value: verifiedfontSizeDesktop, label: __("verifiedfontSizeDesktop") }}
                        fontSizeMobile={{ value: verifiedfontSizeMobile, label: __("verifiedfontSizeMobile") }}
                        fontSizeTablet={{ value: verifiedfontSizeTablet, label: __("verifiedfontSizeTablet") }}
                        lineHeightType={{ value: '', label: __(" ") }}
                        lineHeight={{ value: verifiedlineHeight, label: __("verifiedlineHeight") }}
                        lineHeightMobile={{ value: verifiedlineHeightMobile, label: __("verifiedlineHeightMobile") }}
                        lineHeightTablet={{ value: verifiedlineHeightTablet, label: __("verifiedlineHeightTablet") }}
                        letterSpacing={{ value: verifiedletterSpacing, label: __("verifiedletterSpacing") }}
                        letterSpacingMobile={{ value: verifiedletterSpacingMobile, label: __("verifiedletterSpacingMobile") }}
                        letterSpacingTablet={{ value: verifiedletterSpacingTablet, label: __("verifiedletterSpacingTablet") }}
                        letterSpacingType={{ value: verifiedletterSpacingType, label: __("verifiedletterSpacingType") }}
                        letterSpacingTypeTablet={{ value: verifiedletterSpacingTypeTablet, label: __("verifiedletterSpacingTypeTablet") }}
                        letterSpacingTypeMobile={{ value: verifiedletterSpacingTypeMobile, label: __("verifiedletterSpacingTypeMobile") }}
                        textTransform={verifiedtextTransform}
                        onTextTransform={(value) => setAttributes({ verifiedtextTransform: value })}
                        textDecoration={verifiedtextDecoration}
                        onTextDecoration={(value) => setAttributes({ verifiedtextDecoration: value })}
                        fontStyle={verifiedfontStyle}
                        onFontStyle={(value) => setAttributes({ verifiedfontStyle: value })}
                    />


                    <TypographyControl
                        label={__("Staff Pick Typography")}
                        attributes={attributes}
                        setAttributes={setAttributes}
                        loadGoogleFonts={{ value:staffpickLoadGoogleFonts, label: __("staffpickLoadGoogleFonts") }}
                        fontFamily={{ value: staffpickfontFamily, label: __("staffpickfontFamily") }}
                        fontWeight={{ value: staffpickfontWeight, label: __("staffpickfontWeight") }}
                        fontSubset={{ value: staffpickfontSubset, label: __("staffpickfontSubset") }}
                        fontSizeType={{ value: staffpickfontSizeType, label: __("staffpickfontSizeType") }}
                        fontSizeTypeMobile={{ value: staffpickfontSizeTypeMobile, label: __("staffpickfontSizeTypeMobile") }}
                        fontSizeTypeTablet={{ value: staffpickfontSizeTypeTablet, label: __("staffpickfontSizeTypeTablet") }}
                        fontSize={{ value: staffpickfontSizeDesktop, label: __("staffpickfontSizeDesktop") }}
                        fontSizeMobile={{ value: staffpickfontSizeMobile, label: __("staffpickfontSizeMobile") }}
                        fontSizeTablet={{ value: staffpickfontSizeTablet, label: __("staffpickfontSizeTablet") }}
                        lineHeightType={{ value: '', label: __(" ") }}
                        lineHeight={{ value: staffpicklineHeight, label: __("staffpicklineHeight") }}
                        lineHeightMobile={{ value: staffpicklineHeightMobile, label: __("staffpicklineHeightMobile") }}
                        lineHeightTablet={{ value: staffpicklineHeightTablet, label: __("staffpicklineHeightTablet") }}
                        letterSpacing={{ value: staffpickletterSpacing, label: __("staffpickletterSpacing") }}
                        letterSpacingMobile={{ value: staffpickletterSpacingMobile, label: __("staffpickletterSpacingMobile") }}
                        letterSpacingTablet={{ value: staffpickletterSpacingTablet, label: __("staffpickletterSpacingTablet") }}
                        letterSpacingType={{ value: staffpickletterSpacingType, label: __("staffpickletterSpacingType") }}
                        letterSpacingTypeTablet={{ value: staffpickletterSpacingTypeTablet, label: __("staffpickletterSpacingTypeTablet") }}
                        letterSpacingTypeMobile={{ value: staffpickletterSpacingTypeMobile, label: __("staffpickletterSpacingTypeMobile") }}
                        textTransform={staffpicktextTransform}
                        onTextTransform={(value) => setAttributes({ staffpicktextTransform: value })}
                        textDecoration={staffpicktextDecoration}
                        onTextDecoration={(value) => setAttributes({ staffpicktextDecoration: value })}
                        fontStyle={staffpickfontStyle}
                        onFontStyle={(value) => setAttributes({ staffpickfontStyle: value })}
                    />

                    <TypographyControl
                        label={__("Title Typography")}
                        attributes={attributes}
                        setAttributes={setAttributes}
                        loadGoogleFonts={{ value:titleLoadGoogleFonts, label: __("titleLoadGoogleFonts") }}
                        fontFamily={{ value: titlefontFamily, label: __("titlefontFamily") }}
                        fontWeight={{ value: titlefontWeight, label: __("titlefontWeight") }}
                        fontSubset={{ value: titlefontSubset, label: __("titlefontSubset") }}
                        fontSizeType={{ value: titlefontSizeType, label: __("titlefontSizeType") }}
                        fontSizeTypeMobile={{ value: titlefontSizeTypeMobile, label: __("titlefontSizeTypeMobile") }}
                        fontSizeTypeTablet={{ value: titlefontSizeTypeTablet, label: __("titlefontSizeTypeTablet") }}
                        fontSize={{ value: titlefontSizeDesktop, label: __("titlefontSizeDesktop") }}
                        fontSizeMobile={{ value: titlefontSizeMobile, label: __("titlefontSizeMobile") }}
                        fontSizeTablet={{ value: titlefontSizeTablet, label: __("titlefontSizeTablet") }}
                        lineHeightType={{ value: '', label: __(" ") }}
                        lineHeight={{ value: titlelineHeight, label: __("titlelineHeight") }}
                        lineHeightMobile={{ value: titlelineHeightMobile, label: __("titlelineHeightMobile") }}
                        lineHeightTablet={{ value: titlelineHeightTablet, label: __("titlelineHeightTablet") }}
                        letterSpacing={{ value: titleletterSpacing, label: __("titleletterSpacing") }}
                        letterSpacingMobile={{ value: titleletterSpacingMobile, label: __("titleletterSpacingMobile") }}
                        letterSpacingTablet={{ value: titleletterSpacingTablet, label: __("titleletterSpacingTablet") }}
                        letterSpacingType={{ value: titleletterSpacingType, label: __("titleletterSpacingType") }}
                        letterSpacingTypeTablet={{ value: titleletterSpacingTypeTablet, label: __("titleletterSpacingTypeTablet") }}
                        letterSpacingTypeMobile={{ value: titleletterSpacingTypeMobile, label: __("titleletterSpacingTypeMobile") }}
                        textTransform={titletextTransform}
                        onTextTransform={(value) => setAttributes({ titletextTransform: value })}
                        textDecoration={titletextDecoration}
                        onTextDecoration={(value) => setAttributes({ titletextDecoration: value })}
                        fontStyle={titlefontStyle}
                        onFontStyle={(value) => setAttributes({ titlefontStyle: value })}
                    />

                           
                    <TypographyControl
                        label={__("Content Typography")}
                        attributes={attributes}
                        setAttributes={setAttributes}
                        loadGoogleFonts={{ value:contentLoadGoogleFonts, label: __("contentLoadGoogleFonts") }}
                        fontFamily={{ value: contentfontFamily, label: __("contentfontFamily") }}
                        fontWeight={{ value: contentfontWeight, label: __("contentfontWeight") }}
                        fontSubset={{ value: contentfontSubset, label: __("contentfontSubset") }}
                        fontSizeType={{ value: contentfontSizeType, label: __("contentfontSizeType") }}
                        fontSizeTypeMobile={{ value: contentfontSizeTypeMobile, label: __("contentfontSizeTypeMobile") }}
                        fontSizeTypeTablet={{ value: contentfontSizeTypeTablet, label: __("contentfontSizeTypeTablet") }}
                        fontSize={{ value: contentfontSizeDesktop, label: __("contentfontSizeDesktop") }}
                        fontSizeMobile={{ value: contentfontSizeMobile, label: __("contentfontSizeMobile") }}
                        fontSizeTablet={{ value: contentfontSizeTablet, label: __("contentfontSizeTablet") }}
                        lineHeightType={{ value: '', label: __(" ") }}
                        lineHeight={{ value: contentlineHeight, label: __("contentlineHeight") }}
                        lineHeightMobile={{ value: contentlineHeightMobile, label: __("contentlineHeightMobile") }}
                        lineHeightTablet={{ value: contentlineHeightTablet, label: __("contentlineHeightTablet") }}
                        letterSpacing={{ value: contentletterSpacing, label: __("contentletterSpacing") }}
                        letterSpacingMobile={{ value: contentletterSpacingMobile, label: __("contentletterSpacingMobile") }}
                        letterSpacingTablet={{ value: contentletterSpacingTablet, label: __("contentletterSpacingTablet") }}
                        letterSpacingType={{ value: contentletterSpacingType, label: __("contentletterSpacingType") }}
                        letterSpacingTypeTablet={{ value: contentletterSpacingTypeTablet, label: __("contentletterSpacingTypeTablet") }}
                        letterSpacingTypeMobile={{ value: contentletterSpacingTypeMobile, label: __("contentletterSpacingTypeMobile") }}
                        textTransform={contenttextTransform}
                        onTextTransform={(value) => setAttributes({ contenttextTransform: value })}
                        textDecoration={contenttextDecoration}
                        onTextDecoration={(value) => setAttributes({ contenttextDecoration: value })}
                        fontStyle={contentfontStyle}
                        onFontStyle={(value) => setAttributes({ contentfontStyle: value })}
                    />  

                    <TypographyControl
                        label={__("People Used Typography")}
                        attributes={attributes}
                        setAttributes={setAttributes}
                        loadGoogleFonts={{ value:peopleUsedLoadGoogleFonts, label: __("peopleUsedLoadGoogleFonts") }}
                        fontFamily={{ value: peopleUsedfontFamily, label: __("peopleUsedfontFamily") }}
                        fontWeight={{ value: peopleUsedfontWeight, label: __("peopleUsedfontWeight") }}
                        fontSubset={{ value: peopleUsedfontSubset, label: __("peopleUsedfontSubset") }}
                        fontSizeType={{ value: peopleUsedfontSizeType, label: __("peopleUsedfontSizeType") }}
                        fontSizeTypeMobile={{ value: peopleUsedfontSizeTypeMobile, label: __("peopleUsedfontSizeTypeMobile") }}
                        fontSizeTypeTablet={{ value: peopleUsedfontSizeTypeTablet, label: __("peopleUsedfontSizeTypeTablet") }}
                        fontSize={{ value: peopleUsedfontSizeDesktop, label: __("peopleUsedfontSizeDesktop") }}
                        fontSizeMobile={{ value: peopleUsedfontSizeMobile, label: __("peopleUsedfontSizeMobile") }}
                        fontSizeTablet={{ value: peopleUsedfontSizeTablet, label: __("peopleUsedfontSizeTablet") }}
                        lineHeightType={{ value: '', label: __(" ") }}
                        lineHeight={{ value: peopleUsedlineHeight, label: __("peopleUsedlineHeight") }}
                        lineHeightMobile={{ value: peopleUsedlineHeightMobile, label: __("peopleUsedlineHeightMobile") }}
                        lineHeightTablet={{ value: peopleUsedlineHeightTablet, label: __("peopleUsedlineHeightTablet") }}
                        letterSpacing={{ value: peopleUsedletterSpacing, label: __("peopleUsedletterSpacing") }}
                        letterSpacingMobile={{ value: peopleUsedletterSpacingMobile, label: __("peopleUsedletterSpacingMobile") }}
                        letterSpacingTablet={{ value: peopleUsedletterSpacingTablet, label: __("peopleUsedletterSpacingTablet") }}
                        letterSpacingType={{ value: peopleUsedletterSpacingType, label: __("peopleUsedletterSpacingType") }}
                        letterSpacingTypeTablet={{ value: peopleUsedletterSpacingTypeTablet, label: __("peopleUsedletterSpacingTypeTablet") }}
                        letterSpacingTypeMobile={{ value: peopleUsedletterSpacingTypeMobile, label: __("peopleUsedletterSpacingTypeMobile") }}
                        textTransform={peopleUsedtextTransform}
                        onTextTransform={(value) => setAttributes({ peopleUsedtextTransform: value })}
                        textDecoration={peopleUsedtextDecoration}
                        onTextDecoration={(value) => setAttributes({ peopleUsedtextDecoration: value })}
                        fontStyle={peopleUsedfontStyle}
                        onFontStyle={(value) => setAttributes({ peopleUsedfontStyle: value })}
                    />

                    <TypographyControl
                        label={__("Only Left Typography")}
                        attributes={attributes}
                        setAttributes={setAttributes}
                        loadGoogleFonts={{ value:onlyLeftLoadGoogleFonts, label: __("onlyLeftLoadGoogleFonts") }}
                        fontFamily={{ value: onlyLeftfontFamily, label: __("onlyLeftfontFamily") }}
                        fontWeight={{ value: onlyLeftfontWeight, label: __("onlyLeftfontWeight") }}
                        fontSubset={{ value: onlyLeftfontSubset, label: __("onlyLeftfontSubset") }}
                        fontSizeType={{ value: onlyLeftfontSizeType, label: __("onlyLeftfontSizeType") }}
                        fontSizeTypeMobile={{ value: onlyLeftfontSizeTypeMobile, label: __("onlyLeftfontSizeTypeMobile") }}
                        fontSizeTypeTablet={{ value: onlyLeftfontSizeTypeTablet, label: __("onlyLeftfontSizeTypeTablet") }}
                        fontSize={{ value: onlyLeftfontSizeDesktop, label: __("onlyLeftfontSizeDesktop") }}
                        fontSizeMobile={{ value: onlyLeftfontSizeMobile, label: __("onlyLeftfontSizeMobile") }}
                        fontSizeTablet={{ value: onlyLeftfontSizeTablet, label: __("onlyLeftfontSizeTablet") }}
                        lineHeightType={{ value: '', label: __(" ") }}
                        lineHeight={{ value: onlyLeftlineHeight, label: __("onlyLeftlineHeight") }}
                        lineHeightMobile={{ value: onlyLeftlineHeightMobile, label: __("onlyLeftlineHeightMobile") }}
                        lineHeightTablet={{ value: onlyLeftlineHeightTablet, label: __("onlyLeftlineHeightTablet") }}
                        letterSpacing={{ value: onlyLeftletterSpacing, label: __("onlyLeftletterSpacing") }}
                        letterSpacingMobile={{ value: onlyLeftletterSpacingMobile, label: __("onlyLeftletterSpacingMobile") }}
                        letterSpacingTablet={{ value: onlyLeftletterSpacingTablet, label: __("onlyLeftletterSpacingTablet") }}
                        letterSpacingType={{ value: onlyLeftletterSpacingType, label: __("onlyLeftletterSpacingType") }}
                        letterSpacingTypeTablet={{ value: onlyLeftletterSpacingTypeTablet, label: __("onlyLeftletterSpacingTypeTablet") }}
                        letterSpacingTypeMobile={{ value: onlyLeftletterSpacingTypeMobile, label: __("onlyLeftletterSpacingTypeMobile") }}
                        textTransform={onlyLefttextTransform}
                        onTextTransform={(value) => setAttributes({ onlyLefttextTransform: value })}
                        textDecoration={onlyLefttextDecoration}
                        onTextDecoration={(value) => setAttributes({ onlyLefttextDecoration: value })}
                        fontStyle={onlyLeftfontStyle}
                        onFontStyle={(value) => setAttributes({ onlyLeftfontStyle: value })}
                    />


                    <TypographyControl
                        label={__("On Going Offer Typography")}
                        attributes={attributes}
                        setAttributes={setAttributes}
                        loadGoogleFonts={{ value:onGoingOfferLoadGoogleFonts, label: __("onGoingOfferLoadGoogleFonts") }}
                        fontFamily={{ value: onGoingOfferfontFamily, label: __("onGoingOfferfontFamily") }}
                        fontWeight={{ value: onGoingOfferfontWeight, label: __("onGoingOfferfontWeight") }}
                        fontSubset={{ value: onGoingOfferfontSubset, label: __("onGoingOfferfontSubset") }}
                        fontSizeType={{ value: onGoingOfferfontSizeType, label: __("onGoingOfferfontSizeType") }}
                        fontSizeTypeMobile={{ value: onGoingOfferfontSizeTypeMobile, label: __("onGoingOfferfontSizeTypeMobile") }}
                        fontSizeTypeTablet={{ value: onGoingOfferfontSizeTypeTablet, label: __("onGoingOfferfontSizeTypeTablet") }}
                        fontSize={{ value: onGoingOfferfontSizeDesktop, label: __("onGoingOfferfontSizeDesktop") }}
                        fontSizeMobile={{ value: onGoingOfferfontSizeMobile, label: __("onGoingOfferfontSizeMobile") }}
                        fontSizeTablet={{ value: onGoingOfferfontSizeTablet, label: __("onGoingOfferfontSizeTablet") }}
                        lineHeightType={{ value: '', label: __(" ") }}
                        lineHeight={{ value: onGoingOfferlineHeight, label: __("onGoingOfferlineHeight") }}
                        lineHeightMobile={{ value: onGoingOfferlineHeightMobile, label: __("onGoingOfferlineHeightMobile") }}
                        lineHeightTablet={{ value: onGoingOfferlineHeightTablet, label: __("onGoingOfferlineHeightTablet") }}
                        letterSpacing={{ value: onGoingOfferletterSpacing, label: __("onGoingOfferletterSpacing") }}
                        letterSpacingMobile={{ value: onGoingOfferletterSpacingMobile, label: __("onGoingOfferletterSpacingMobile") }}
                        letterSpacingTablet={{ value: onGoingOfferletterSpacingTablet, label: __("onGoingOfferletterSpacingTablet") }}
                        letterSpacingType={{ value: onGoingOfferletterSpacingType, label: __("onGoingOfferletterSpacingType") }}
                        letterSpacingTypeTablet={{ value: onGoingOfferletterSpacingTypeTablet, label: __("onGoingOfferletterSpacingTypeTablet") }}
                        letterSpacingTypeMobile={{ value: onGoingOfferletterSpacingTypeMobile, label: __("onGoingOfferletterSpacingTypeMobile") }}
                        textTransform={onGoingOffertextTransform}
                        onTextTransform={(value) => setAttributes({ onGoingOffertextTransform: value })}
                        textDecoration={onGoingOffertextDecoration}
                        onTextDecoration={(value) => setAttributes({ onGoingOffertextDecoration: value })}
                        fontStyle={onGoingOfferfontStyle}
                        onFontStyle={(value) => setAttributes({ onGoingOfferfontStyle: value })}
                    />

                </PanelBody>

                <PanelBody title={ __( 'Colors' ) } initialOpen={false}  icon={ getIcon( 'colors' ) } className={ 'abblocks-panel-label' }>

                    <Color label={__('Border Color')} disableAlpha value={boxBorderColor} onChange={val => setAttributes({ boxBorderColor: val })} />
                    <Color label={__('Box Background Color')} disableAlpha value={boxBgColor} onChange={val => setAttributes({ boxBgColor: val })} />    
                    <Color label={__('Main Title Color')} disableAlpha value={titleTextColor} onChange={val => setAttributes({ titleTextColor: val })} />
                    <Color label={__('Rating Color')} value={ratingsColor} onChange={(value) => setAttributes({ ratingsColor: value })} />

                    <Color label={__('Verified Color')} value={verifiedColor} onChange={(value) => setAttributes({ verifiedColor: value })} />
                    <Color label={__('Staff Pick Background Color')} value={staffpickColor} onChange={(value) => setAttributes({ staffpickColor: value })} />
                    <Color label={__('People Used Color')} value={peopleUsedColor} onChange={(value) => setAttributes({ peopleUsedColor: value })} />
                    <Color label={__('Only Left Color')} value={onlyLeftColor} onChange={(value) => setAttributes({ onlyLeftColor: value })} />
                    <Color label={__('On Going Offer Color')} value={onGoingOfferColor} onChange={(value) => setAttributes({ onGoingOfferColor: value })} />
                    
                </PanelBody>
              
                <PanelBody title={ __( 'Button Settings' ) } initialOpen={false}  icon={ getIcon( 'button' ) } className={ 'abblocks-panel-label' }>	
                        <ToggleControl
                            label={ __( 'Show Button' ) }                        
                            checked={ showButton }                                      
                            onChange={ ( ) => setAttributes( { showButton: ! showButton } ) }
                        />    

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
                                    <IconList value={buttonIcon} disableToggle onChange={val => setAttributes({ buttonIcon: val })} />
                                </div>
                            ) }
                        />
                      <RadioAdvanced
                          label={__('Icon Position')}
                          options={[
                              { label: 'Left', value: 'af-icon-is-left', title: 'Left' },
                              { label: 'Right', value: 'af-icon-is-right', title: 'Right' },
                          ]}
                          value={iconPosition}
                          onChange={(value) => setAttributes({ iconPosition: value })} />					
                    <TextControl		
                        label = { __( 'Button Text' ) }				
                        value={ spBtnText }						
                        onChange={ ( value ) => setAttributes( { spBtnText: value } ) }
                    />
                    <TextControl
                        label = { __( 'Button URL' ) }						
                        value={ spBtnHref }						
                        onChange={ ( value ) => setAttributes( { spBtnHref: value } ) }
                    />
                    <TextControl
                        label = { __( 'Button Rel' ) }						
                        value={ btnRel }						
                        onChange={ ( value ) => setAttributes( { btnRel: value } ) }
                    />
                    <ToggleControl
                        label={ __( 'Open link in new Tab' ) }						
                        checked={ btn_Htarget }										
                        onChange={ ( ) => setAttributes( { btn_Htarget: ! btn_Htarget } ) }
                    />
                    <ToggleControl
                        label={ __( 'Rounded Button' ) }						
                        checked={ bntRounded }										
                        onChange={ ( ) => setAttributes( { bntRounded: ! bntRounded } ) }
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
                            value={btnSize}
                            onChange={(value) => setAttributes({ btnSize: value })} />
                    <RadioAdvanced
                            label={__('Button Width')}
                            options={[
                                { label: 'Fixed', value: 'fixed', title: 'Fixed' },
                                { label: 'Flexible', value: 'flex', title: 'Flexible' },
                                { label: 'Full', value: 'full', title: 'Full' },
                            ]}
                            value={buttonWidth}
                            onChange={(value) => setAttributes({ buttonWidth: value })} />
                    <TypographyControl
                            label={__("Button Typography")}
                            attributes={attributes}
                            setAttributes={setAttributes}
                            loadGoogleFonts={{ value:btnLoadGoogleFonts, label: __("btnLoadGoogleFonts") }}
                            fontFamily={{ value: btnfontFamily, label: __("btnfontFamily") }}
                            fontWeight={{ value: btnfontWeight, label: __("btnfontWeight") }}
                            fontSubset={{ value: btnfontSubset, label: __("btnfontSubset") }}
                            fontSizeType={{ value: btnfontSizeType, label: __("btnfontSizeType") }}
                            fontSizeTypeMobile={{ value: btnfontSizeTypeMobile, label: __("btnfontSizeTypeMobile") }}
                            fontSizeTypeTablet={{ value: btnfontSizeTypeTablet, label: __("btnfontSizeTypeTablet") }}
                            fontSize={{ value: btnfontSizeDesktop, label: __("btnfontSizeDesktop") }}
                            fontSizeMobile={{ value: btnfontSizeMobile, label: __("btnfontSizeMobile") }}
                            fontSizeTablet={{ value: btnfontSizeTablet, label: __("btnfontSizeTablet") }}
                            lineHeightType={{ value: '', label: __(" ") }}
                            lineHeight={{ value: btnlineHeight, label: __("btnlineHeight") }}
                            lineHeightMobile={{ value: btnlineHeightMobile, label: __("btnlineHeightMobile") }}
                            lineHeightTablet={{ value: btnlineHeightTablet, label: __("btnlineHeightTablet") }}
                            letterSpacing={{ value: btnletterSpacing, label: __("btnletterSpacing") }}
                            letterSpacingMobile={{ value: btnletterSpacingMobile, label: __("btnletterSpacingMobile") }}
                            letterSpacingTablet={{ value: btnletterSpacingTablet, label: __("btnletterSpacingTablet") }}
                            letterSpacingType={{ value: btnletterSpacingType, label: __("btnletterSpacingType") }}
                            letterSpacingTypeTablet={{ value: btnletterSpacingTypeTablet, label: __("btnletterSpacingTypeTablet") }}
                            letterSpacingTypeMobile={{ value: btnletterSpacingTypeMobile, label: __("btnletterSpacingTypeMobile") }}
                            textTransform={btntextTransform}
                            onTextTransform={(value) => setAttributes({ btntextTransform: value })}
                            textDecoration={btntextDecoration}
                            onTextDecoration={(value) => setAttributes({ btntextDecoration: value })}
                            fontStyle={btnfontStyle}
                            onFontStyle={(value) => setAttributes({ btnfontStyle: value })}
                        />
                        <Tabs>
                            <Tab tabTitle={__('Normal')}>
                              <Color label={__('Text Color')} disableAlpha value={btnTextColor} onChange={val => setAttributes({ btnTextColor: val })} />
                              <Color label={__('Border Color')} disableAlpha value={btnBorderColor} onChange={val => setAttributes({ btnBorderColor: val })} />
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
                              <Color label={__('Hover Text Color')} disableAlpha value={btnTextHoverColor} onChange={val => setAttributes({ btnTextHoverColor: val })} />
                              <Color label={__('Border Hover Color')} disableAlpha value={btnBorderHoverColor} onChange={val => setAttributes({ btnBorderHoverColor: val })} />
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
                                        label={__('Padding')} value={btnPaddingMobile}
                                        onChange={val => setAttributes({ btnPaddingMobile: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Padding')} value={btnPaddingTablet}
                                        onChange={val => setAttributes({ btnPaddingTablet: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Padding')} value={btnPadding}
                                        onChange={val => setAttributes({ btnPadding: val })}
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
                                        value={btnMarginMobile}
                                        onChange={val => setAttributes({ btnMarginMobile: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Margin')} value={btnMarginTablet}
                                        onChange={val => setAttributes({ btnMarginMobile: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Margin')} value={btnMargin}
                                        onChange={val => setAttributes({ btnMargin: val })}
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
                          <BorderTypes label={__('Border Type')} value={btnBorderType} onChange={(value) => setAttributes({ btnBorderType: value })} />
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
                                        label={__('Border Width')} value={btnBorderMobile}
                                        onChange={val => setAttributes({ btnBorderMobile: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Border Width')} 
                                        value={btnBorderTablet}
                                        onChange={val => setAttributes({ btnBorderTablet: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Width')} value={btnBorder}
                                        onChange={val => setAttributes({ btnBorder: val })}
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
                                        label={__('Border Radius')} value={btnBorderRadiusMobile}
                                        onChange={val => setAttributes({ btnBorderRadiusMobile: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Border Radius')} 
                                        value={btnBorderRadiusTablet}
                                        onChange={val => setAttributes({ btnBorderRadiusTablet: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Border Radius')} value={btnBorderRadius}
                                        onChange={val => setAttributes({ btnBorderRadius: val })}
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
                                          value={btnAlignmentMobile}
                                          onChange={(value) => setAttributes({ btnAlignmentMobile: value })}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignmentTablet}
                                          onChange={(value) => setAttributes({ btnAlignmentTablet: value })}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Alignment
                                          label = {__('Alignment')}
                                          disableJustify
                                          alignmentType='content'
                                          flex
                                          value={btnAlignment}
                                          onChange={(value) => setAttributes({ btnAlignment: value })}
                                      />
                                    );
                                  }
                                }
                                return <div>{tabout}</div>;
                              }
                            }
                          </TabPanel>     
                </PanelBody>
                
                <PanelBody title={`Spacing`} icon={ getIcon( 'spacing' ) } className={ 'abblocks-panel-label' } initialOpen={false}>
                     
                       
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
                                        label={__('Main Image Padding')} value={imagePaddingMobile}
                                        onChange={val => setAttributes({ imagePaddingMobile: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else if ('boxtablet' === radiustab.name) {
                                    tabout = (
                                      <Dimension label={__('Main Image Padding')} value={imagePaddingTablet}
                                        onChange={val => setAttributes({ imagePaddingTablet: val })}
                                        min={0} max={600} unit={['px','em','rem','%']}
                                        clientId={this.props.clientId}
                                      />
                                    );
                                  } else {
                                    tabout = (
                                      <Dimension
                                        label={__('Main Image Padding')} value={imagePadding}
                                        onChange={val => setAttributes({ imagePadding: val })}
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
           
                <a href="https://www.affiliatebooster.com/#price" target="_blank" className={"BuyPremiumSettings"}>Buy Premium to Unlock More Settings</a>           
                </InspectorControls>
                
                <div id={`affiliate-style-${block_id}`} className={`affiliate-block-${block_id}${className ? ` ${className}` : ''} affiliate-coupon-wrapper style4`}>
                    <div className={`affiliate-coupon-inner`}> 
                        <div className={`affiliate-coupon-content-wrapper`}>
                              
                              
                            <div className={`affiliate-coupon-content-inner`}>
                                <div className={`affiliate-col firstcol`}>
                                  <Fragment>
                                        { designType && designType == 'ab-dynamic-design' &&
                                           <div className={`aff-inner-block`}>
                                             <InnerBlocks
                                                renderAppender={ () => (
                                                  <InnerBlocks.ButtonBlockAppender />
                                                ) }
                                              />
                                           </div>   
                                        }
                                       

                                        { designType && designType == 'ab-fixed-design' && !externalImage && !externalImage == true &&
                                          <MediaUpload				
                                              onSelect={ this.onSelectImage }
                                              allowedTypes= { [ "image" ] }
                                              value={image}				
                                              render={ ( { open } ) => (					
                                                <a className="block-editor-button" onClick={ open }>
                                                {/* <img  src={image.url} alt={image.url ? couponMainTitle : ''} />
                                                */}
                                                <ReviewImage attributes={attributes} />
                                                
                                                    {__("Add Image")}						
                                                </a>
                                              ) }
                                            />
                                        }

                                        { designType && designType == 'ab-fixed-design' &&  externalImage && externalImage == true &&
                                              <div className={`affiliate-coupon-image`}>
                                                  <img src={externalImageSrc}  alt={externalImageAlt} />
                                              </div>
                                          }

                                         
                                  </Fragment>
                                </div>
                                <div className={`affiliate-col seccol`}>

                                    { showVerified && showVerified == true &&

                                        <RichText 
                                            value={Verified}
                                            className="affiliate-coupon-Verified"
                                            placeholder={__('Verified')}
                                            onChange={Verified => setAttributes({ Verified })}
                                            
                                        />
                                    }

                                    { showStaffPick && showStaffPick == true &&
                                    <RichText 
                                        value={StaffPick}
                                        className="affiliate-coupon-StaffPick"
                                        placeholder={__('Staff Pick')}
                                        onChange={StaffPick => setAttributes({ StaffPick })}
                                        
                                    />
                                    }

                                    { showTitle && showTitle == true &&
                                        <RichText
                                            tagName={Tag1}
                                            value={couponMainTitle} 
                                            className="affiliate-coupon-maintitle"
                                            id={`getSlug(${uniqueId})`}
                                            placeholder={__('Product Title')}
                                            onChange={couponMainTitle => setAttributes({ couponMainTitle })}
                                            
                                        />    
                                    }


                                    { showContent && showContent == true &&
                                        <RichText                                    
                                            value={couponContent}
                                            className="affiliate-coupon-content"
                                            placeholder={__('Content')}
                                            onChange={couponContent => setAttributes({ couponContent })}
                                            
                                        />
                                    }

                                   { showPeopleUsed && showPeopleUsed == true &&
                                        
                                        <RichText 
                                            value={PeopleUsed}
                                            className="affiliate-coupon-PeopleUsed"
                                            placeholder={__('People Used')}
                                            onChange={PeopleUsed => setAttributes({ PeopleUsed })}
                                            
                                        />
                                                                
                                    }

                                    { showCouponLeft && showCouponLeft == true &&

                                        <RichText 
                                            value={OnlyLeft}
                                            className="affiliate-coupon-onlyLeft"
                                            placeholder={__('Only Left')}
                                            onChange={OnlyLeft => setAttributes({ OnlyLeft })}
                                            
                                        />
                                    }
                                     
                                </div>
                                <div className={`affiliate-col thirdcol`}>

                                    { showRating && showRating == true &&
                                        <Fragment>

                                        <div className="affiliate-coupon-starrating-outer">

                                        <div className="affiliate-coupon-starratingTitle">Rating</div>

                                        { showRatingNumber && showRatingNumber == true &&
                                            <div className="affiliate-coupon-ratingnumber">{selectedStars}</div>
                                        } 

                                        <div className="affiliate-coupon-starrating"
                                            onMouseLeave={() => this.setState({ highlightedStars: 0 })} >
                                            {[...Array(ratings)].map((e, i) => (
                                               <div className={`affiliate-star-item`} key={i}
                                                    onMouseEnter={() => {
                                                        this.setState({ highlightedStars: i + 1 });
                                                    }}
                                                    onClick={() => {
                                                        if (selectedStars % 1 === 0) {
                                                            setAttributes({
                                                                selectedStars: i + (selectedStars - 1 === i ? 0.5 : 1)
                                                            });
                                                        } else {
                                                            setAttributes({
                                                                selectedStars: i + (selectedStars - 0.5 === i ? 1 : 0.5)
                                                            });
                                                        }
                                                    }}
                                                >
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
                                            <div className={`affiliate-coupon-btn-wrapper`}  onClick={() => { this.setState({ isVisible: !isVisible })}}>
                                                <span className={`affiliate-btn  btn-is-${btnSize} ${
                                                          buttonWidth === "full" ? "btn-is-fullw" : buttonWidth === "flex" ? `btn-is-flex-${btnSize}` : "" } ${bntRounded ? 'btn-is-rounded' : ''} ${enableGlimmerEffect ? 'glimmer-effect' : ''}`}>
                                                    { iconPosition && iconPosition == 'af-icon-is-left' &&
                                                          <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                                    }
                                                    { iconPosition && iconPosition != 'af-icon-is-left' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-left`}></i>
                                                    }
                                                    <RichText
                                                        value={spBtnText}
                                                        placeholder={__('Button text')}
                                                        onChange={spBtnText => setAttributes({ spBtnText })}
                                                        allowedFormats={ ['bold', 'italic', 'underline'] }
                                                        href = {btn_Htarget}
                                                        {...(btn_Htarget ? {...btn_Htarget && { rel: newrel }} : { rel: newrel }  )}
                                                        {...(btn_Htarget ? { target: '_blank' } : '' )}
                                                    />
                                                    { iconPosition && iconPosition == 'af-icon-is-right' &&
                                                          <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                                    }
                                                    { iconPosition && iconPosition != 'af-icon-is-right' &&
                                                          <i className={`affiliate-abbtn-icon af-icon-is-right`}></i>
                                                    }
                                                </span>    
                                            </div>
                                        </div>
                                    }


                                    { showOnGoingOffer && showOnGoingOffer == true &&

                                        <RichText 
                                            value={onGoingOffer}
                                            className="affiliate-coupon-onGoingOffer"
                                            placeholder={__('onGoingOffer')}
                                            onChange={onGoingOffer => setAttributes({ onGoingOffer })}
                                            
                                        />
                                    }

                                </div>
                            </div>
                                              
                       
                        
                        </div>
                    </div>
                </div>
                {loadtitleGoogleFonts} 
                {loadonlyLeftGoogleFonts}
                {loadstaffpickGoogleFonts}
                {loadpeopleUsedGoogleFonts}
                {loadbtnGoogleFotns}
                {loadsubTitleGoogleFonts}
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