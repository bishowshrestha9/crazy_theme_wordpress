import IconSelector from '../../components/fields/IconSelector/IconSelector.js';
import TypographyControl from '../components/typography/index.js';
import RadioAdvanced from '../../components/fields/RadioAdvanced.js';
import Background  from './../../components/fields/Background/Background.js';
import IconList  from '../../components/fields/IconList/IconList.js';
import WebfontLoader from "../components/typography/fontloader"
import Color  from '../../components/fields/Color/Color.js';
import Dimension  from '../../components/fields/Dimension/Dimension.js';
import affiliate_styling  from './styling.js';
import Media  from '../../components/fields/Media/Media.js';
import BorderTypes  from '../../components/fields/BorderTypes.js';
import BoxShadow  from '../../components/fields/BoxShadow.js';
import Alignment from '../../components/fields/Alignment.js';
import Range from '../../components/fields/Range/Range.js';
import icons from '../../components/icons.js';
import Tabs from '../../components/fields/Tabs/Tabs.js';
import Tab from '../../components/fields/Tab/Tab.js';
import ProductImage from './productImage';

import getIcon from '../../icons/get-icon';
import RichList from '../../components/rich-list';

const { __ } = wp.i18n;
const {
    InspectorControls,
    InspectorAdvancedControls,
    BlockControls,
    RichText,
    MediaUpload,
    URLInput,
    URLInputButton,
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
    Dropdown,
    SelectControl 
} = wp.components;

$ = jQuery;

let imageSizeOptions = [
	{ value: "thumbnail", label: __( "Thumbnail" ) },
	{ value: "medium", label: __( "Medium" ) },
	{ value: "full", label: __( "Large" ) }
]

const NEW_TAB_REL = 'noopener noreferrer';

class Edit extends Component {

    constructor(props) {
        super(props)
        this.state = {
          // removeItemViaBackSpace: 999,
          // isVisible:false,
          // focusedItem: this.props.attributes.listItems.length - 1,
      }

        this.onSelectImage    = this.onSelectImage.bind( this )
        this.getImageSize  	  = this.getImageSize.bind(this)
        this.getSlug     = this.getSlug.bind(this)
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
      * Event to set Image as while adding.
      */
     onSelectImage( media ) {
      const { image } = this.props.attributes
      const { setAttributes } = this.props

      if ( ! media || ! media.url ) {
        setAttributes( { image: {} } )
        return
      }

      if ( ! media.type || "image" !== media.type ) {
        setAttributes( { image: {} } )
        return
      }
      // if ( media["sizes"] ) {
      //   var new_img = this.getImageSize(media["sizes"])
      //     imageSizeOptions = new_img
      // }
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
            attributes: {
                uniqueId,
                block_id,
                className,
                btnBg,
                btnBgHover,
                spTitle,
                spSubTitle,
                titleLoadGoogleFonts,
                spBtnText,
                spContent,
                spBtnHref,
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
                titleAlignment,
                titleAlignmentMobile,
                titleAlignmentTablet,
                // CTA BUTTON
                btnLoadGoogleFonts,
                btnfontFamily,
                btnfontWeight,
                listItems,
                bulletStyle,
                listType,
                btnShow,
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
                boxPadding,
                boxPaddingMobile,
                boxPaddingTablet,
                boxMargin,
                boxMarginMobile,
                boxMarginTablet,
                image,
                // Sub Title
                subtitleLoadGoogleFonts,
                subtitleTextColor,
                subtitlefontFamily,
                subtitlefontWeight,
                subtitlefontSubset,
                subtitlefontSizeType,
                subtitlefontSizeTypeMobile,
                subtitlefontSizeTypeTablet,
                subtitlefontSizeDesktop,
                subtitlefontSizeMobile,
                subtitlefontSizeTablet,
                subtitlelineHeight,
                subtitlelineHeightMobile,
                subtitlelineHeightTablet,
                subtitleletterSpacing,
                subtitleletterSpacingMobile,
                subtitleletterSpacingTablet,
                subtitleletterSpacingType,
                subtitleletterSpacingTypeTablet,
                subtitleletterSpacingTypeMobile,
                subtitletextTransform,
                subtitletextDecoration,
                subtitlefontStyle,
                subtitleAlignment,
                subtitleAlignmentMobile,
                subtitleAlignmentTablet,
                boxBorderType,
                boxBorderColor,
                boxBorderWidth,
                boxBackgroundColor,
                boxShadow,
                cntnAlignment,
                cntnAlignmentMobile,
                cntnAlignmentTablet,
                btnSize,
                buttonIcon,
                iconPosition,
                buttonWidth,
                bntRounded,
                contentType,
                pContent,
                imageSize,
                imagePadding,
                imagePaddingMobile,
                imagePaddingTablet,
                customImgWidth,
                customImgWidthMobile,
                customImgWidthTablet,
                iconColor,
                designType,
                listTextItems,
                titleTag1,
                titleTag2,
                btnRel,

                externalImage,
                externalImageSrc,
                externalImageAlt,
                
                showTitle,
                showSubTitle,
                showContent,
            }
        } = this.props;

        const { isVisible } = this.state
        // imageSizeOptions = this.getImageSize(image["sizes"])
        var element = document.getElementById("affiliate-style-" + this.props.clientId)
        if (null != element && "undefined" != typeof element) {
             element.innerHTML = affiliate_styling(this.props, "affiliate-style")
        }
        
        let loadtitleGoogleFonts
        let loadcntnGoogleFonts
        let loadbtnGoogleFonts
        let loadsubTitleGoogleFonts

        if( btnLoadGoogleFonts == true ) {
					
			const btnconfig = {
				google: {
					families: [ btnfontFamily + ( btnfontWeight ? ":" + btnfontWeight : "" ) ],
				},
			}

			loadbtnGoogleFonts = (
				<WebfontLoader config={ btnconfig }>
				</WebfontLoader>
			)
		}

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

        if( subtitleLoadGoogleFonts == true ) {
					
          const subtitleconfig = {
            google: {
              families: [ subtitlefontFamily + ( subtitlefontWeight ? ":" + subtitlefontWeight : "" ) ],
            },
          }

          loadsubTitleGoogleFonts = (
            <WebfontLoader config={ subtitleconfig }>
            </WebfontLoader>
          )
        } 

        const Tag1  = titleTag1
        const Tag2  = titleTag2

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


                <PanelBody title={__('Image Settings')} icon={ getIcon( 'backgrounds' ) } className={ 'abblocks-panel-label' } initialOpen={false} >
                 <RadioAdvanced
                      label={__('Design Type')}
                      options={[
                          { label: 'Fixed', value: 'ab-fixed-design', title: 'Fixed' },
                          { label: 'Dynamic', value: 'ab-dynamic-design', title: 'Dynamic' },
                      ]}
                      value={designType}
                      onChange={(value) => setAttributes({ designType: value })} />	

                      <ToggleControl
                            label={ __( 'External Image?' ) }                        
                            checked={ externalImage }                                      
                            onChange={ ( ) => setAttributes( { externalImage: ! externalImage } ) }
                        />

                      { designType && designType == 'ab-fixed-design' &&  !externalImage && !externalImage == true &&
                          <Media label={__('Image')} multiple={false} type={['image']} panel={true} value={image} onChange={val => setAttributes({ image: val })} />
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
                                <Range label={__('Custom Image Width')} value={customImgWidthMobile} onChange={(value) => setAttributes({ customImgWidthMobile: value })} min={0} max={1000} unit={['']} />
                              );
                            } else if ('boxtablet' === radiustab.name) {
                              tabout = (
                                <Range label={__('Custom Image Width')} value={customImgWidthTablet} onChange={(value) => setAttributes({ customImgWidthTablet: value })} min={0} max={1000} unit={['']} />
                              );
                            } else {
                              tabout = (
                                <Range label={__('Custom Image Width')} value={customImgWidth} onChange={(value) => setAttributes({ customImgWidth: value })} min={0} max={1000} unit={['']} />
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
                                    label={__('Image Padding')} value={imagePaddingMobile}
                                    onChange={val => setAttributes({ imagePaddingMobile: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                  <Dimension label={__('Image Padding')} value={imagePaddingTablet}
                                    onChange={val => setAttributes({ imagePaddingTablet: val })}
                                    min={0} max={600} unit={['px','em','rem','%']}
                                    clientId={this.props.clientId}
                                  />
                                );
                              } else {
                                tabout = (
                                  <Dimension
                                    label={__('Image Padding')} value={imagePadding}
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

                <PanelBody title={`Title/Subtitle Settings`} icon={ getIcon( 'headline' ) } className={ 'abblocks-panel-label' }  initialOpen={false}>

                    <ToggleControl
                        label={ __( 'Show Title' ) }                        
                        checked={ showTitle }                                      
                        onChange={ ( ) => setAttributes( { showTitle: ! showTitle } ) }
                    />

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
                  <h2 className="alignment-heading-size-title">{__('Title Alignment')}</h2>
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
                                  disableJustify
                                  value={titleAlignmentMobile}
                                  onChange={(value) => setAttributes({ titleAlignmentMobile: value })}
                              />
                            );
                          } else if ('boxtablet' === radiustab.name) {
                            tabout = (
                              <Alignment
                                  disableJustify
                                  value={titleAlignmentTablet}
                                  onChange={(value) => setAttributes({ titleAlignmentTablet: value })}
                              />
                            );
                          } else {
                            tabout = (
                              <Alignment
                                  disableJustify
                                  value={titleAlignment}
                                  onChange={(value) => setAttributes({ titleAlignment: value })}
                              />
                            );
                          }
                        }
                        return <div>{tabout}</div>;
                      }
                    }
                  </TabPanel>
                  
                  <ToggleControl
                        label={ __( 'Show Sub Title' ) }                        
                        checked={ showSubTitle }                                      
                        onChange={ ( ) => setAttributes( { showSubTitle: ! showSubTitle } ) }
                    />

                  <SelectControl
                        label={ __( 'Subtitle Heading Tag' ) }
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
                <h2 className="alignment-heading-size-title">{__('Subtitle Alignment')}</h2>
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
                                  disableJustify
                                  value={subtitleAlignmentMobile}
                                  onChange={(value) => setAttributes({ subtitleAlignmentMobile: value })}
                              />
                            );
                          } else if ('boxtablet' === radiustab.name) {
                            tabout = (
                              <Alignment
                                  disableJustify
                                  value={subtitleAlignmentTablet}
                                  onChange={(value) => setAttributes({ subtitleAlignmentTablet: value })}
                              />
                            );
                          } else {
                            tabout = (
                              <Alignment
                                  disableJustify
                                  value={subtitleAlignment}
                                  onChange={(value) => setAttributes({ subtitleAlignment: value })}
                              />
                            );
                          }
                        }
                        return <div>{tabout}</div>;
                      }
                    }
                  </TabPanel> 
                  
                </PanelBody>
                
                <PanelBody title={`Content Settings`}  icon={ getIcon( 'paragraph' ) } className={ 'abblocks-panel-label' } initialOpen={false}>

                <ToggleControl
                        label={ __( 'Show Content' ) }                        
                        checked={ showContent }                                      
                        onChange={ ( ) => setAttributes( { showContent: ! showContent } ) }
                    />

                <RadioAdvanced
                    label={__('Content Type')}
                    options={[
                        { label: 'List Item', value: 'is-list', title: 'List Item' },
                        { label: 'Paragraph', value: 'is-p', title: 'Paragraph' },
                    ]}
                    value={contentType}
                    onChange={(value) => setAttributes({ contentType: value })} />
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
                                  disableJustify
                                  value={cntnAlignmentMobile}
                                  onChange={(value) => setAttributes({ cntnAlignmentMobile: value })}
                              />
                            );
                          } else if ('boxtablet' === radiustab.name) {
                            tabout = (
                              <Alignment
                                  label= {__('Alignment')}
                                  disableJustify
                                  value={cntnAlignmentTablet}
                                  onChange={(value) => setAttributes({ cntnAlignmentTablet: value })}
                              />
                            );
                          } else {
                            tabout = (
                              <Alignment
                                  label= {__('Alignment')}  
                                  disableJustify
                                  value={cntnAlignment}
                                  onChange={(value) => setAttributes({ cntnAlignment: value })}
                              />
                            );
                          }
                        }
                        return <div>{tabout}</div>;
                      }
                    }
                  </TabPanel>
                  {listType == 'unordered' && contentType == 'is-list' &&
                                    <Fragment>
                                        <IconSelector
                                            label="Pros Icon"
                                            value={bulletStyle.name}
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
                                            onChange={val => setAttributes({ bulletStyle: val })}
                                        />
                                    </Fragment>
                                }
                                {listType == 'ordered' &&
                                    <Fragment>
                                        <Range label={__('Font Size')} value={numberFontSize} onChange={(value) => setAttributes({ numberFontSize: value })} min={10} max={100} />
                                        <Toggle
                                            value={useNumberBg}
                                            label={__('Use Background')}
                                            onChange={val => setAttributes({ useNumberBg: val, recreateStyles: !recreateStyles })}
                                        />
                                        {
                                            useNumberBg == 1 &&
                                            <Fragment>
                                                <Range label={__('Background Size')} value={numberBgSize} onChange={(value) => setAttributes({ numberBgSize: value })} min={1} max={15} />
                                                <Range label={__('Corner')} value={numberCorner} onChange={(value) => setAttributes({ numberCorner: value })} min={0} max={100} />
                                            </Fragment>
                                        }
                                    </Fragment>
                                }
                    { contentType && contentType == 'is-list' &&
                        <Color label={__('Icon Color')} disableAlpha value={iconColor} onChange={val => setAttributes({ iconColor: val })} />
                    }
                    
                </PanelBody>
                

                  <PanelBody title={`Typography`} icon={ getIcon( 'typography' ) } className={ 'abblocks-panel-label' } initialOpen={false}>                

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
                        label={__("Sub Title Typography")}
                        attributes={attributes}
                        setAttributes={setAttributes}
                        loadGoogleFonts={{ value:subtitleLoadGoogleFonts, label: __("subtitleLoadGoogleFonts") }}
                        fontFamily={{ value: subtitlefontFamily, label: __("subtitlefontFamily") }}
                        fontWeight={{ value: subtitlefontWeight, label: __("subtitlefontWeight") }}
                        fontSubset={{ value: subtitlefontSubset, label: __("subtitlefontSubset") }}
                        fontSizeType={{ value: subtitlefontSizeType, label: __("subtitlefontSizeType") }}
                        fontSizeTypeMobile={{ value: subtitlefontSizeTypeMobile, label: __("subtitlefontSizeTypeMobile") }}
                        fontSizeTypeTablet={{ value: subtitlefontSizeTypeTablet, label: __("subtitlefontSizeTypeTablet") }}
                        fontSize={{ value: subtitlefontSizeDesktop, label: __("subtitlefontSizeDesktop") }}
                        fontSizeMobile={{ value: subtitlefontSizeMobile, label: __("subtitlefontSizeMobile") }}
                        fontSizeTablet={{ value: subtitlefontSizeTablet, label: __("subtitlefontSizeTablet") }}
                        lineHeightType={{ value: '', label: __(" ") }}
                        lineHeight={{ value: subtitlelineHeight, label: __("subtitlelineHeight") }}
                        lineHeightMobile={{ value: subtitlelineHeightMobile, label: __("subtitlelineHeightMobile") }}
                        lineHeightTablet={{ value: subtitlelineHeightTablet, label: __("subtitlelineHeightTablet") }}
                        letterSpacing={{ value: subtitleletterSpacing, label: __("subtitleletterSpacing") }}
                        letterSpacingMobile={{ value: subtitleletterSpacingMobile, label: __("subtitleletterSpacingMobile") }}
                        letterSpacingTablet={{ value: subtitleletterSpacingTablet, label: __("subtitleletterSpacingTablet") }}
                        letterSpacingType={{ value: subtitleletterSpacingType, label: __("subtitleletterSpacingType") }}
                        letterSpacingTypeTablet={{ value: subtitleletterSpacingTypeTablet, label: __("subtitleletterSpacingTypeTablet") }}
                        letterSpacingTypeMobile={{ value: subtitleletterSpacingTypeMobile, label: __("subtitleletterSpacingTypeMobile") }}
                        textTransform={subtitletextTransform}
                        onTextTransform={(value) => setAttributes({ subtitletextTransform: value })}
                        textDecoration={subtitletextDecoration}
                        onTextDecoration={(value) => setAttributes({ subtitletextDecoration: value })}
                        fontStyle={subtitlefontStyle}
                        onFontStyle={(value) => setAttributes({ subtitlefontStyle: value })}
                    />

                    <TypographyControl
                        label={__("Content Typography")}
                        attributes={attributes}
                        setAttributes={setAttributes}
                        loadGoogleFonts={{ value:cntnLoadGoogleFonts, label: __("cntnLoadGoogleFonts") }}
                        fontFamily={{ value: cntnfontFamily, label: __("cntnfontFamily") }}
                        fontWeight={{ value: cntnfontWeight, label: __("cntnfontWeight") }}
                        fontSubset={{ value: cntnfontSubset, label: __("cntnfontSubset") }}
                        fontSizeType={{ value: cntnfontSizeType, label: __("cntnfontSizeType") }}
                        fontSizeTypeMobile={{ value: cntnfontSizeTypeMobile, label: __("cntnfontSizeTypeMobile") }}
                        fontSizeTypeTablet={{ value: cntnfontSizeTypeTablet, label: __("cntnfontSizeTypeTablet") }}
                        fontSize={{ value: cntnfontSizeDesktop, label: __("cntnfontSizeDesktop") }}
                        fontSizeMobile={{ value: cntnfontSizeMobile, label: __("cntnfontSizeMobile") }}
                        fontSizeTablet={{ value: cntnfontSizeTablet, label: __("cntnfontSizeTablet") }}
                        lineHeightType={{ value: '', label: __(" ") }}
                        lineHeight={{ value: cntnlineHeight, label: __("cntnlineHeight") }}
                        lineHeightMobile={{ value: cntnlineHeightMobile, label: __("cntnlineHeightMobile") }}
                        lineHeightTablet={{ value: cntnlineHeightTablet, label: __("cntnlineHeightTablet") }}
                        letterSpacing={{ value: cntnletterSpacing, label: __("cntnletterSpacing") }}
                        letterSpacingMobile={{ value: cntnletterSpacingMobile, label: __("cntnletterSpacingMobile") }}
                        letterSpacingTablet={{ value: cntnletterSpacingTablet, label: __("cntnletterSpacingTablet") }}
                        letterSpacingType={{ value: cntnletterSpacingType, label: __("cntnletterSpacingType") }}
                        letterSpacingTypeTablet={{ value: cntnletterSpacingTypeTablet, label: __("cntnletterSpacingTypeTablet") }}
                        letterSpacingTypeMobile={{ value: cntnletterSpacingTypeMobile, label: __("cntnletterSpacingTypeMobile") }}
                        textTransform={cntntextTransform}
                        onTextTransform={(value) => setAttributes({ cntntextTransform: value })}
                        textDecoration={cntntextDecoration}
                        onTextDecoration={(value) => setAttributes({ cntntextDecoration: value })}
                        fontStyle={cntnfontStyle}
                        onFontStyle={(value) => setAttributes({ cntnfontStyle: value })}
                    />
                  </PanelBody> 

                  <PanelBody title={`Colors`} icon={ getIcon( 'colors' ) } className={ 'abblocks-panel-label' } initialOpen={false}>  
                    <Color label={__('Title Color')} disableAlpha value={titleTextColor} onChange={val => setAttributes({ titleTextColor: val })} />              
                    <Color label={__('Sub Title Color')} disableAlpha value={subtitleTextColor} onChange={val => setAttributes({ subtitleTextColor: val })} />
                    <Color label={__('Content Color')} disableAlpha value={cntnTextColor} onChange={val => setAttributes({ cntnTextColor: val })} />          
                    <Color label={__('Box Background Color')} disableAlpha value={boxBackgroundColor} onChange={val => setAttributes({ boxBackgroundColor: val })} />
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
                              label={__('Box Padding')}
                              value={boxPaddingMobile}
                              onChange={val => setAttributes({ boxPaddingMobile: val })}
                              min={0}
                              max={600}
                              unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else if ('boxtablet' === radiustab.name) {
                          tabout = (
                            <Dimension label={__('Box Padding')} value={boxPaddingTablet}
                              onChange={val => setAttributes({ boxPaddingTablet: val })}
                              min={0}
                              max={600}
                              unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else {
                          tabout = (
                            <Dimension
                              label={__('Box Padding')}
                              value={boxPadding}
                              onChange={val => setAttributes({ boxPadding: val })}
                              min={0}
                              max={600}
                              unit={['px','em','rem','%']}
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
                              label={__('Box Margin')}
                              value={boxMarginMobile}
                              onChange={val => setAttributes({ boxMarginMobile: val })}
                              min={0}
                              max={600}
                              unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else if ('boxtablet' === radiustab.name) {
                          tabout = (
                            <Dimension label={__('Box Margin')} value={boxMarginTablet}
                              onChange={val => setAttributes({ boxMarginTablet: val })}
                              min={0}
                              max={600}
                              unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else {
                          tabout = (
                            <Dimension
                              label={__('Box Margin')}
                              value={boxMargin}
                              onChange={val => setAttributes({ boxMargin: val })}
                              min={0}
                              max={600}
                              unit={['px','em','rem','%']}
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

                  <PanelBody title={ __( 'Button Settings' ) } icon={ getIcon( 'button' ) } className={ 'abblocks-panel-label' }  initialOpen={false}>

                    <ToggleControl
                        label={ __( 'Show Button' ) }                        
                        checked={ btnShow }                                      
                        onChange={ ( ) => setAttributes( { btnShow: ! btnShow } ) }
                    />

                      <Dropdown
                              className="affiliate-icon-popover"
                              contentClassName="my-popover-content-classname"
                              popoverProps={ { placement: 'bottom-start' } }
                              renderToggle={ ( { isOpen, onToggle } ) => (
                                <Fragment>
                                  <h2>{__('Tag Icon')}</h2>
                                  <Button className="affiliate-size-btn" isPrimary onClick={ onToggle } aria-expanded={ isOpen }>
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
                            label={ __( 'Open link in new window' ) }           
                            checked={ btn_Htarget }                   
                            onChange={ ( ) => setAttributes( { btn_Htarget: ! btn_Htarget } ) }
                        />
                        <ToggleControl
                            label={ __( 'Rounded Button' ) }            
                            checked={ bntRounded }                    
                            onChange={ ( ) => setAttributes( { bntRounded: ! bntRounded } ) }
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
                  
                  <a href="https://www.affiliatebooster.com/#price" target="_blank" className={"BuyPremiumSettings"}>Buy Premium to Unlock More Settings</a>

                </InspectorControls>


                          <InspectorAdvancedControls>
                            
                          </InspectorAdvancedControls>


                <BlockControls>
                      <Fragment>
                                
                      </Fragment>          
                </BlockControls>
                    <div id={`affiliate-style-${block_id}`} className={`affiliate-block-${block_id}${className ? ` ${className}` : ''}`}>
                        <div className={"affiliate-sp-wrapper"}>
                          <div className={`affiliate-sp-inner`}>

                            { showTitle && showTitle == true &&
                              <RichText
                                  tagName={Tag1}
                                  value={spTitle}
                                  className="affiliate-sp-title"
                                  id={`getSlug(${spTitle})`}                                
                                  placeholder={__('Title')}
                                  onChange={spTitle => setAttributes({ spTitle })}
                              />
                            }
                            <div className={`affiliate-sp-content`}>
                              <div className={`affiliate-sp-img-wrapper`}>
                                <Fragment>

                                  { designType && designType == 'ab-fixed-design' &&  !externalImage && !externalImage == true &&
                                      <MediaUpload				
                                        onSelect={ this.onSelectImage }				
                                        value={image}				
                                        render={ ( { open } ) => (					
                                          <a className="block-editor-button" onClick={ open }>
                                            <ProductImage attributes={attributes} />
                                              {__("Add-Image")}						
                                          </a>
                                        ) }
                                      />
                                  }

                                  { designType && designType == 'ab-fixed-design' &&  externalImage && externalImage == true &&
                                        <div className={`affiliate-sp-image`}>
                                            <img src={externalImageSrc}  alt={externalImageAlt} />
                                        </div>
                                    }

                                  { designType && designType == 'ab-dynamic-design' &&
                                      <div className={`aff-inner-block`}>
                                      <InnerBlocks
                                          renderAppender={ () => (
                                            <InnerBlocks.ButtonBlockAppender />
                                          ) }
                                        />
                                      </div>   
                                  }
                                </Fragment>
                                </div>
                              <div className={`affiliate-sp-cntn-wrapper`}>
                                  { showSubTitle && showSubTitle == true &&
                                    <RichText
                                        tagName={Tag2}
                                        value={spSubTitle}
                                        className="affiliate-sp-subtitle"
                                        id={`getSlug(${spSubTitle})`}
                                        placeholder={__('Sub Title')}
                                        onChange={spSubTitle => setAttributes({ spSubTitle })}
                                    />
                                  }

                                  { showContent && showContent == true &&
                                        <Fragment>
                                    { contentType == 'is-p' &&
                                      <RichText
                                        value={pContent}
                                        placeholder={__('Button text')}
                                        onChange={pContent => setAttributes({ pContent })}
                                        className={'affiliate-pr-pcontent'}
                                    />
                                    }
                                    { contentType == 'is-list' &&
                                      <div className={`affiliate-block-advanced-list`}>
                                         <RichList
                                            value={listTextItems}
                                            attributeKey={'listTextItems'}
                                            className={`affiliate-list affiliate-list-type-unordered affiliate-list-bullet-${bulletStyle.name}`}
                                            editProps={this.props}
                                          />
                                      </div>
                                    }   
                                    </Fragment>
                                    }     

                                    { btnShow && btnShow == true &&
                                    <div className={`affiliate-sp-btn-wrapper`}  onClick={() => { this.setState({ isVisible: !isVisible })}}>
                                      <Fragment>
                                          <span  className={`affiliate-sp-btn  btn-is-${btnSize} ${
                                                      buttonWidth === "full" ? "btn-is-fullw" : buttonWidth === "flex" ? `btn-is-flex-${btnSize}` : "" } ${bntRounded ? 'btn-is-rounded' : ''}`} >
                                             { iconPosition && iconPosition == 'af-icon-is-left' &&
                                                      <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                                }
                                                <RichText
                                                    value={spBtnText}
                                                    placeholder={__('Button text')}                                                    
                                                    allowedFormats={ ['bold', 'italic', 'underline'] }
                                                    onChange={spBtnText => setAttributes({ spBtnText })}
                                                    href = {btn_Htarget}
                                                    {...(btn_Htarget ? {...btn_Htarget && { rel: newrel }} : { rel: newrel }  )}
                                                    {...(btn_Htarget ? { target: '_blank' } : '' )}
                                                />
                                                { iconPosition && iconPosition == 'af-icon-is-right' &&
                                                      <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                                }
                                            </span>
                                      </Fragment>
                                  </div>
                                }

                                </div>    
                            </div>
                        </div>                         
                        </div>
                    </div>
                {loadtitleGoogleFonts}
                {loadcntnGoogleFonts}
                {loadbtnGoogleFonts}
                {loadsubTitleGoogleFonts}
            </Fragment>
        )
    }       
}

export default (Edit);