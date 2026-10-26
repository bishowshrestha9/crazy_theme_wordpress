import IconSelector from '../../components/fields/IconSelector/IconSelector.js';
import IconList  from './../../components/fields/IconList/IconList.js';
import RadioAdvanced from '../../components/fields/RadioAdvanced.js';
import TypographyControl from '../components/typography/index.js';
import WebfontLoader from "../components/typography/fontloader"
import Background  from './../../components/fields/Background/Background.js';
import Alignment from '../../components/fields/Alignment.js';
import BorderTypes  from './../../components/fields/BorderTypes.js';
import Color  from '../../components/fields/Color/Color.js';
import Dimension  from '../../components/fields/Dimension/Dimension.js';
import affiliate_styling  from './styling.js';
import Select  from './../../components/fields/Select/Select.js';
import BoxShadow  from './../../components/fields/BoxShadow.js';
import Tabs from '../../components/fields/Tabs/Tabs.js';
import Tab from '../../components/fields/Tab/Tab.js';
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
    Dropdown,
    Button,
    SelectControl
} = wp.components;
const NEW_TAB_REL = 'noopener noreferrer';
class Edit extends Component {

    constructor(props) {
        super(props)

        this.getSlug     = this.getSlug.bind(this)
    }
    
    componentDidMount() {
        const { clientId, isSelected, setAttributes, attributes: {uniqueId } } = this.props;
        const _client = clientId.substr(0, 6);
 
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
                uniqueId,
                block_id,
                boxBg,
                btnBg,
                btnBgHover,
                ctaTitle,
                titleLoadGoogleFonts,
                ctaBtnText,
                ctaContent,
                ctaBtnHref,
                boxBorderType,
                boxBorderColor,
                boxBorderWidth,
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
                // CTA BUTTON
                btnLoadGoogleFonts,
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
                btnBgColor,
                btnHoverBgColor,
                btnTextColor,
                btnTextHoverColor,
                btnBorderColor,
                btnBorderHoverColor,
                btn_Htarget,
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
                boxStyle,
                boxPadding,
                boxPaddingMobile,
                boxPaddingTablet,
                boxMargin,
                boxMarginMobile,
                boxMarginTablet,
                boxAlignment,
                boxAlignmentMobile,
                boxAlignmentTablet,
                bgOverlay,
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
                btnSize,
                buttonIcon,
                iconPosition,
                buttonWidth,
                bntRounded,
                boxShadow,
                showButton,
                showContent,
                showTitle,
                titleTag1,
                btnRel,
 
            }
        } = this.props;
        
        var element = document.getElementById("affiliate-style-" + this.props.clientId)
        if (null != element && "undefined" != typeof element) {
             element.innerHTML = affiliate_styling(this.props, "affiliate-style")
        }
        
        let loadtitleGoogleFonts
        let loadcntnGoogleFonts
        let loadbtnGoogleFonts

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

    const Tag1  = titleTag1
    let newrel;
    if( !btn_Htarget){
        newrel = btnRel
    } else  if( !btn_Htarget && btnRel === NEW_TAB_REL){
        newrel = btnRel
    } else if ( btn_Htarget && !btnRel ) {
      newrel = NEW_TAB_REL
    } else {
      newrel = NEW_TAB_REL + '' + btnRel
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
                    <Select
                      label={__('Box Style')}
                      options={[['box-style-1',__('Style 1')], ['box-style-2',__('Style 2')]]}
                      value={boxStyle}
                      onChange={(value) => setAttributes({ boxStyle: value })} />  
                  

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
                                  label={__('Content Alignment')}
                                  disableJustify
                                  value={boxAlignmentMobile}
                                  onChange={(value) => setAttributes({ boxAlignmentMobile: value })}
                              />
                            );
                          } else if ('boxtablet' === radiustab.name) {
                            tabout = (
                              <Alignment
                                  label={__('Content Alignment')}
                                  disableJustify
                                  value={boxAlignmentTablet}
                                  onChange={(value) => setAttributes({ boxAlignmentTablet: value })}
                              />
                            );
                          } else {
                            tabout = (
                              <Alignment
                                  label={__('Content Alignment')}
                                  disableJustify
                                  value={boxAlignment}
                                  onChange={(value) => setAttributes({ boxAlignment: value })}
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

                <PanelBody title={ __( 'Typography' ) } initialOpen={false}  icon={ getIcon( 'typography' ) } className={ 'abblocks-panel-label' }>
                  { showTitle && showTitle == true &&
                    <Fragment>
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
                      
                    </Fragment>
                  }  
 
                </PanelBody>

                

                  
              { showButton && showButton == true &&
                  <PanelBody title={ __( 'Button Settings' ) } initialOpen={false} icon={ getIcon( 'button' ) } className={ 'abblocks-panel-label' }>	
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
                            value={ ctaBtnText }						
                            onChange={ ( value ) => setAttributes( { ctaBtnText: value } ) }
                        />
                        <TextControl
                            label = { __( 'Button URL' ) }						
                            value={ ctaBtnHref }						
                            onChange={ ( value ) => setAttributes( { ctaBtnHref: value } ) }
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
              }

              <a href="https://www.affiliatebooster.com/#price" target="_blank" className={"BuyPremiumSettings"}>Buy Premium to Unlock More Settings</a>

                </InspectorControls>
                 { boxStyle && boxStyle == 'box-style-1' &&
                    <div id={`affiliate-style-${block_id}`} className={"affiliate-cta-wrapper affiliate-cta-style1"}>
                      <div className={`affiliate-cta-inner`}>
                      <div className={`affiliate-cta-overlay`}></div>  
                          { showTitle && showTitle == true &&
                              <RichText
                                  tagName={Tag1}
                                  value={ctaTitle}
                                  id={`getSlug(${ctaTitle})`}  
                                  className="affiliate-cta-title"
                                  placeholder={__('CTA Heading')}
                                  onChange={ctaTitle => setAttributes({ ctaTitle })}
                              />
                          }      
                          { showContent && showContent == true &&
                            <RichText
                                value={ctaContent}
                                className="affiliate-cta-content"
                                placeholder={__('CTA Content')}
                                onChange={ctaContent => setAttributes({ ctaContent })}
                            />
                          }
                          { showButton && showButton == true &&
                              <div className={`affiliate-cta-btn-wrapper`}>
                                      <span className={`affiliate-cta-btn  btn-is-${btnSize} ${
                                                buttonWidth === "full" ? "btn-is-fullw" : buttonWidth === "flex" ? `btn-is-flex-${btnSize}` : "" } ${bntRounded ? 'btn-is-rounded' : ''}`}>
                                        { iconPosition && iconPosition == 'af-icon-is-left' &&
                                                <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                          }
                                          <RichText
                                              value={ctaBtnText}
                                              placeholder={__('Button text')}
                                              onChange={ctaBtnText => setAttributes({ ctaBtnText })}
                                              onChange={ctaBtnText => setAttributes({ ctaBtnText })}
                                              allowedFormats={ ['bold', 'italic', 'underline'] }
                                              href = {ctaBtnHref}
                                              {...(btn_Htarget ? {...btn_Htarget && { rel: newrel }} : { rel: newrel }  )}
                                              {...(btn_Htarget ? { target: '_blank' } : '' )}
                                          />
                                          { iconPosition && iconPosition == 'af-icon-is-right' &&
                                                <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                          }
                                      </span>
                              </div>
                          }
                      </div>
                  </div>
                 }
                 { boxStyle && boxStyle == 'box-style-2' &&
                  <div id={`affiliate-style-${block_id}`} className={"affiliate-cta-wrapper affiliate-cta-style2"}>
                      <div className={`affiliate-cta-inner`}>
                      <div className={`affiliate-cta-overlay`}></div>
                          { showTitle && showTitle == true && 
                              <RichText
                                tagName={Tag1}
                                value={ctaTitle}
                                id={`getSlug(${ctaTitle})`}
                                className="affiliate-cta-title"
                                placeholder={__('CTA Heading')}
                                onChange={ctaTitle => setAttributes({ ctaTitle })}
                            />
                          }
                          { showContent && showContent == true &&
                              <RichText
                                value={ctaContent}
                                className="affiliate-cta-content"
                                placeholder={__('CTA Content')}
                                onChange={ctaContent => setAttributes({ ctaContent })}
                            />
                          }
                          { showButton && showButton == true &&
                              <div className={`affiliate-cta-btn-wrapper`}>
                                <span className={`affiliate-cta-btn btn-is-${btnSize} ${buttonWidth === "full" ? "btn-is-fullw" : buttonWidth === "flex" ? `btn-is-flex-${btnSize}` : "" } ${bntRounded ? 'btn-is-rounded' : ''}`}>
                                { iconPosition && iconPosition == 'af-icon-is-left' &&
                                      <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                }
                                <RichText
                                    value={ctaBtnText}
                                    placeholder={__('Button text')}
                                    onChange={ctaBtnText => setAttributes({ ctaBtnText })}
                                    allowedFormats={ ['bold', 'italic', 'underline'] }
                                    href = {ctaBtnHref}
                                    {...(btn_Htarget ? {...btn_Htarget && { rel: newrel }} : { rel: newrel }  )}
                                    {...(btn_Htarget ? { target: '_blank' } : '' )}
                                />
                                { iconPosition && iconPosition == 'af-icon-is-right' &&
                                      <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                }
                              </span>
                            </div>
                          }
                      </div>
                  </div>
                 }
                {loadtitleGoogleFonts}
                {loadcntnGoogleFonts}
                {loadbtnGoogleFonts}
            </Fragment>
        )
    }       
}

export default (Edit);