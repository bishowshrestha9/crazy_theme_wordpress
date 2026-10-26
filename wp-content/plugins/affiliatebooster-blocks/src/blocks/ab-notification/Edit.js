import TypographyControl from '../components/typography/index.js';
import WebfontLoader from "../components/typography/fontloader"
import Alignment from '../../components/fields/Alignment.js';
import BoxShadow  from './../../components/fields/BoxShadow.js';
import Color  from '../../components/fields/Color/Color.js';
import Dimension  from '../../components/fields/Dimension/Dimension.js';
import affiliate_styling  from './styling.js';
import BorderTypes  from './../../components/fields/BorderTypes.js';
import getIcon from '../../icons/get-icon';

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
    }
    
    componentDidMount() {
        this.props.setAttributes({ block_id: this.props.clientId })
        const $style = document.createElement("style")
        $style.setAttribute("id", "affiliate-style-" + this.props.clientId)
        document.head.appendChild($style)
    
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
                noticeContent,
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
                cntnBorder,
                cntnBorderMobile,
                cntnBorderTablet,
                cntnBorderRadius,
                cntnBorderRadiusMobile,
                cntnBorderRadiusTablet,
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
                customWidthType,
                customWidthTypeMobile,
                customWidthTypeTablet,
                customWidth,
                customWidthMobile,
                customWidthTablet,
                boxShadow,
                titleTag1
              }
        } = this.props;
        
        var element = document.getElementById("affiliate-style-" + this.props.clientId)
        if (null != element && "undefined" != typeof element) {
             element.innerHTML = affiliate_styling(this.props, "affiliate-style")
        }
        
        let loadcntnGoogleFonts
   
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
                    
                    
                <BorderTypes label={__('Border Type')} value={cntnBorderType} onChange={(value) => setAttributes({ cntnBorderType: value })} />
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
                              label={__('Border')} value={cntnBorderMobile}
                              onChange={val => setAttributes({ cntnBorderMobile: val })}
                              min={0} max={600} unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else if ('boxtablet' === radiustab.name) {
                          tabout = (
                            <Dimension label={__('Border')} 
                              value={cntnBorderTablet}
                              onChange={val => setAttributes({ cntnBorderTablet: val })}
                              min={0} max={600} unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else {
                          tabout = (
                            <Dimension
                              label={__('Border')} value={cntnBorder}
                              onChange={val => setAttributes({ cntnBorder: val })}
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
                              label={__('Border Radius')} value={cntnBorderRadiusMobile}
                              onChange={val => setAttributes({ cntnBorderRadiusMobile: val })}
                              min={0} max={600} unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else if ('boxtablet' === radiustab.name) {
                          tabout = (
                            <Dimension label={__('Border Radius')} 
                              value={cntnBorderRadiusTablet}
                              onChange={val => setAttributes({ cntnBorderRadiusTablet: val })}
                              min={0} max={600} unit={['px','em','rem','%']}
                              clientId={this.props.clientId}
                            />
                          );
                        } else {
                          tabout = (
                            <Dimension
                              label={__('Border Radius')} value={cntnBorderRadius}
                              onChange={val => setAttributes({ cntnBorderRadius: val })}
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
                <TypographyControl
                      label={__("Typography")}
                      fontSizeLabel ={ __('Custom Width') }
                      attributes={attributes}
                      setAttributes={setAttributes}
                      disableLineHeight
                      disableFontFamily
                      disableLetterSpacing
                      disableTextTransform
                      disableTextDecoration
                      disableFontStyle
                      fontSizeType ={{ value: customWidthType, label: __("customWidthType") }}
                      fontSizeTypeMobile ={{ value: customWidthTypeMobile, label: __("customWidthTypeMobile") }}
                      fontSizeTypeTablet ={{ value: customWidthTypeTablet, label: __("customWidthTypeTablet") }}
                      fontSize={{ value: customWidth, label: __("customWidth") }}
                      fontSizeMobile={{ value: customWidthMobile, label: __("customWidthMobile") }}
                      fontSizeTablet={{ value: customWidthTablet, label: __("customWidthTablet") }}
                /> 
                <BoxShadow label={__('Box-Shadow')} value={boxShadow} onChange={(value) => setAttributes({ boxShadow: value })} />
                </PanelBody>

                <PanelBody title={`Typography`} icon={ getIcon( 'typography' ) } className={ 'abblocks-panel-label' } initialOpen={false}>

                  <SelectControl
                      label={ __( 'Heading Tag' ) }
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
                  <Color label={__('Content Color')} disableAlpha value={cntnTextColor} onChange={val => setAttributes({ cntnTextColor: val })} />
                  <Color label={__('Border Color')} disableAlpha value={cntnBorderColor} onChange={val => setAttributes({ cntnBorderColor: val })} />
                  <Color label={__('Background Color')} disableAlpha value={cntnBgColor} onChange={val => setAttributes({ cntnBgColor: val })} />
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
                  <div id={`affiliate-style-${block_id}`} className={"affiliate-notification-wrapper "}>
                        <div className={`affiliate-notification-innwr`}> 
                          <RichText
                              tagName={Tag1}
                              value={noticeContent}
                              id={`notice-${block_id}`}
                              className="affiliate-notification-content"
                              placeholder={__('Notification Content')}
                              onChange={noticeContent => setAttributes({ noticeContent })}
                          />
                      </div>
                  </div>
                {loadcntnGoogleFonts}
            </Fragment>
        )
    }       
}

export default (Edit);