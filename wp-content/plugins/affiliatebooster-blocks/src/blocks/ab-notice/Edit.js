import IconSelector from '../../components/fields/IconSelector/IconSelector.js';
import RadioAdvanced from '../../components/fields/RadioAdvanced.js';
import TypographyControl from '../components/typography/index.js';
import WebfontLoader from "../components/typography/fontloader"
import Alignment from '../../components/fields/Alignment.js';
import Color  from '../../components/fields/Color/Color.js';
import Dimension  from '../../components/fields/Dimension/Dimension.js';
import affiliate_styling  from './styling.js';
import Background  from './../../components/fields/Background/Background.js';
import Select  from './../../components/fields/Select/Select.js';
import BorderTypes  from './../../components/fields/BorderTypes.js';
import BoxShadow  from './../../components/fields/BoxShadow.js';
import getIcon from '../../icons/get-icon';
import RichList from '../../components/rich-list.js';

const { __ } = wp.i18n;
const {
    InspectorControls,
    BlockControls,
    RichText
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
    ToggleControl,
    TabPanel,
    Dashicon,
    SelectControl
} = wp.components;

class Edit extends Component {

    constructor(props) {
        super(props)
        this.state = {
      }
      this.getSlug     = this.getSlug.bind(this)
    }
    
    componentDidMount() {
      const { clientId, isSelected, setAttributes, attributes: { uniqueId, block_id } } = this.props;
        this.props.setAttributes({ block_id: this.props.clientId })
        const $style = document.createElement("style")
        $style.setAttribute("id", "affiliate-style-" + this.props.clientId)
        document.head.appendChild($style)
    }

    getSlug( title ) {
      return title.toString().toLowerCase().replace(/( |<.+?>|&nbsp;)/g, "-");
    }

    render() {
        const {
            name,
            clientId,
            isSelected,
            attributes,
            setAttributes,
            attributes: {
                block_id,
                noticeTitle,
                titleLoadGoogleFonts,
                noticeContent,
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
                // notice content
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
                titlePadding,
                titlePaddingMobile,
                titlePaddingTablet,
                titleMargin,
                titleMarginMobile,
                titleMarginTablet,
                titleAlignment,
                titleAlignmentMobile,
                titleAlignmentTablet,
                noticeTitleBgColor,
                titleBorder,
                titleBorderType,
                titleBorderMobile,
                titleBorderTablet,
                titleBorderRadius,
                titleBorderRadiusMobile,
                titleBorderRadiusTablet,
                cntnBorder,
                cntnBorderMobile,
                cntnBorderTablet,
                cntnBorderRadius,
                cntnBorderRadiusMobile,
                cntnBorderRadiusTablet,
                titleBorderColor,
                cntnBorderColor,
                cntnAlignment,
                cntnAlignmentMobile,
                cntnAlignmentTablet,
                cntnBorderType,
                cntnMargin,
                cntnMarginMobile,
                cntnMarginTablet,
                cntnPadding,
                cntnPaddingMobile,
                cntnPaddingTablet,
                cntnBgColor,
                boxShadow,
                contentType,
                listType,
                bulletStyle,
                iconColor,
                listTextItems,
                titleTag1
              }
        } = this.props;
        
        var element = document.getElementById("affiliate-style-" + this.props.clientId)
        if (null != element && "undefined" != typeof element) {
             element.innerHTML = affiliate_styling(this.props, "affiliate-style")
        }
        
        let loadtitleGoogleFonts
        let loadcntnGoogleFonts
   
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
        
        if( cntnLoadGoogleFonts == true ) {
            
            const cntnconfig = {
              google: {
                families: [ cntnfontFamily + ( cntnfontWeight ? ":" + cntnfontWeight : "" ) ],
              },
            }

          loadcntnGoogleFonts = (
            <WebfontLoader config={ cntnconfig }>
            </WebfontLoader>
          )
        }
        const Tag1 = titleTag1
        return (
            <Fragment>
                <InspectorControls key="inspector">

                <PanelBody title={`General Settings`} icon={ getIcon( 'layout' ) } className={ 'abblocks-panel-label' } initialOpen={false}>                
                  <BorderTypes label={__('Border Type')} value={titleBorderType} onChange={(value) => setAttributes({ titleBorderType: value })} />
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
                              label={__('Border')} value={titleBorderMobile}
                              onChange={val => setAttributes({ titleBorderMobile: val })}
                              min={0} max={600} unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else if ('boxtablet' === radiustab.name) {
                          tabout = (
                            <Dimension label={__('Border')} 
                              value={titleBorderTablet}
                              onChange={val => setAttributes({ titleBorderTablet: val })}
                              min={0} max={600} unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else {
                          tabout = (
                            <Dimension
                              label={__('Border')} value={titleBorder}
                              onChange={val => setAttributes({ titleBorder: val })}
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
                              label={__('Border Radius')} value={titleBorderRadiusMobile}
                              onChange={val => setAttributes({ titleBorderRadiusMobile: val })}
                              min={0} max={600} unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else if ('boxtablet' === radiustab.name) {
                          tabout = (
                            <Dimension label={__('Border Radius')} 
                              value={titleBorderRadiusTablet}
                              onChange={val => setAttributes({ titleBorderRadiusTablet: val })}
                              min={0} max={600} unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else {
                          tabout = (
                            <Dimension
                              label={__('Border Radius')} value={titleBorderRadius}
                              onChange={val => setAttributes({ titleBorderRadius: val })}
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
                  <BoxShadow label={__('Box-Shadow')} value={boxShadow} onChange={(value) => setAttributes({ boxShadow: value })} />
                </PanelBody>
                  
                <PanelBody title={`Title Settings`} icon={ getIcon( 'headline' ) } className={ 'abblocks-panel-label' } initialOpen={false}>
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
                                value={titleAlignmentMobile}
                                onChange={(value) => setAttributes({ titleAlignmentMobile: value })}
                            />
                          );
                        } else if ('boxtablet' === radiustab.name) {
                          tabout = (
                            <Alignment
                                label = {__('Alignment')}
                                disableJustify
                                value={titleAlignmentTablet}
                                onChange={(value) => setAttributes({ titleAlignmentTablet: value })}
                            />
                          );
                        } else {
                          tabout = (
                            <Alignment
                                label = {__('Alignment')}
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
                </PanelBody>    
                <PanelBody title={`Content Settings`} icon={ getIcon( 'paragraph' ) } className={ 'abblocks-panel-label' } initialOpen={false}>
                <RadioAdvanced
                  label={__('Content Type')}
                  options={[
                      { label: 'List Item', value: 'is-list', title: 'List Item' },
                      { label: 'Paragraph', value: 'is-p', title: 'Paragraph' },
                  ]}
                  value={contentType}
                  onChange={(value) => setAttributes({ contentType: value })} />
                { contentType && contentType == 'is-list' &&
                      <Fragment>
                        {listType == 'unordered' &&
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
                        { contentType && contentType == 'is-list' &&
                              <Color label={__('Icon Color')} disableAlpha value={iconColor} onChange={val => setAttributes({ iconColor: val })} />
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
                            <Alignment
                                label = {__('Alignment')}
                                disableJustify
                                value={cntnAlignmentMobile}
                                onChange={(value) => setAttributes({ cntnAlignmentMobile: value })}
                            />
                          );
                        } else if ('boxtablet' === radiustab.name) {
                          tabout = (
                            <Alignment
                                label = {__('Alignment')}
                                disableJustify
                                value={cntnAlignmentTablet}
                                onChange={(value) => setAttributes({ cntnAlignmentTablet: value })}
                            />
                          );
                        } else {
                          tabout = (
                            <Alignment
                                label = {__('Alignment')}
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
                  <Color label={__('Notice Color')} disableAlpha value={noticeTitleBgColor} onChange={val => setAttributes({ noticeTitleBgColor: val })} /> 

                  <Color label={__('Content Color')} disableAlpha value={cntnTextColor} onChange={val => setAttributes({ cntnTextColor: val })} />
                  <Color label={__('Border Color')} disableAlpha value={titleBorderColor} onChange={val => setAttributes({ titleBorderColor: val })} />                   
                <Color label={__('Background Color')} disableAlpha value={cntnBgColor} onChange={val => setAttributes({ cntnBgColor: val })} />
                </PanelBody>

                <PanelBody title={`Title Spacing`} icon={ getIcon( 'spacing' ) } className={ 'abblocks-panel-label' } initialOpen={false}>
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
                              label={__('Padding')} value={titlePaddingMobile}
                              onChange={val => setAttributes({ titlePaddingMobile: val })}
                              min={0} max={600} unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else if ('boxtablet' === radiustab.name) {
                          tabout = (
                            <Dimension label={__('Padding')} value={titlePaddingTablet}
                              onChange={val => setAttributes({ titlePaddingTablet: val })}
                              min={0} max={600} unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else {
                          tabout = (
                            <Dimension
                              label={__('Padding')} value={titlePadding}
                              onChange={val => setAttributes({ titlePadding: val })}
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
                              value={titleMarginMobile}
                              onChange={val => setAttributes({ titleMarginMobile: val })}
                              min={0} max={600} unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else if ('boxtablet' === radiustab.name) {
                          tabout = (
                            <Dimension label={__('Margin')} value={titleMarginTablet}
                              onChange={val => setAttributes({ titleMarginMobile: val })}
                              min={0} max={600} unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else {
                          tabout = (
                            <Dimension
                              label={__('Margin')} value={titleMargin}
                              onChange={val => setAttributes({ titleMargin: val })}
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

                <PanelBody title={`Content Spacing`} icon={ getIcon( 'spacing' ) } className={ 'abblocks-panel-label' } initialOpen={false}>
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
                              label={__('Padding')} value={cntnPaddingMobile}
                              onChange={val => setAttributes({ cntnPaddingMobile: val })}
                              min={0} max={600} unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else if ('boxtablet' === radiustab.name) {
                          tabout = (
                            <Dimension label={__('Padding')} value={cntnPaddingTablet}
                              onChange={val => setAttributes({ cntnPaddingTablet: val })}
                              min={0} max={600} unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else {
                          tabout = (
                            <Dimension
                              label={__('Padding')} value={cntnPadding}
                              onChange={val => setAttributes({ cntnPadding: val })}
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
                              value={cntnMarginMobile}
                              onChange={val => setAttributes({ cntnMarginMobile: val })}
                              min={0} max={600} unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else if ('boxtablet' === radiustab.name) {
                          tabout = (
                            <Dimension label={__('Margin')} value={cntnMarginTablet}
                              onChange={val => setAttributes({ cntnMarginMobile: val })}
                              min={0} max={600} unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else {
                          tabout = (
                            <Dimension
                              label={__('Margin')} value={cntnMargin}
                              onChange={val => setAttributes({ cntnMargin: val })}
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
                  <div id={`affiliate-style-${block_id}`} className={`affiliate-notice-wrapper affiliate-block-${block_id}`}>
                      <div className={`affiliate-notice-inner`}>
                        <div className={`affiliate-notice-title`}>
                            <RichText
                                key={'heading'}
                                tagName={Tag1}
                                value={noticeTitle}
                                id={`getSlug(${noticeTitle})`}     
                                placeholder={__('Notice Heading')}
                                onChange={noticeTitle => setAttributes({ noticeTitle })}
                            />
                          </div>
                          <div className={`affiliate-notice-cntn-wrapper`}> 
                          { contentType && contentType == 'is-list' && 
                                <div className={`affiliate-block-advanced-list`}>
                                  <RichList
                                    key={'content-list'}
                                    value={listTextItems}
                                    attributeKey={'listTextItems'}
                                    className={`affiliate-list affiliate-list-type-unordered affiliate-list-bullet-${bulletStyle.name} affiliate-notice-content`}
                                    editProps={this.props}
                                  />
                                </div>
                            }
                          
                            { contentType && contentType == 'is-p' && 
                                <RichText
                                  key={'content'}
                                  tagName="p"
                                  value={noticeContent}
                                  className="affiliate-notice-content"
                                  placeholder={__('Notice Content')}
                                  onChange={noticeContent => setAttributes({ noticeContent })}
                              />
                            }
                        </div>
                      </div>
                  </div>
                {loadtitleGoogleFonts}
                {loadcntnGoogleFonts}
            </Fragment>
        )
    }       
}

export default (Edit);