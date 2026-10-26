import IconSelector from '../../components/fields/IconSelector/IconSelector.js';
import RadioAdvanced from '../../components/fields/RadioAdvanced.js';
import TypographyControl from '../components/typography/index.js';
import WebfontLoader from "../components/typography/fontloader"
import Alignment from '../../components/fields/Alignment.js';
import Color  from '../../components/fields/Color/Color.js';
import Dimension  from '../../components/fields/Dimension/Dimension.js';
import affiliate_styling  from './styling.js';
import Background  from './../../components/fields/Background/Background.js';
import IconList  from './../../components/fields/IconList/IconList.js';
import BorderTypes  from './../../components/fields/BorderTypes.js';
import Tabs from '../../components/fields/Tabs/Tabs.js';
import Tab from '../../components/fields/Tab/Tab.js';
import Url from '../../components/fields/Url/Url.js';
import BoxShadow from './../../components/fields/BoxShadow.js';
import getIcon from '../../icons/get-icon';

import classnames from "classnames"

const { __ } = wp.i18n;
const {
    InspectorControls,
    BlockControls,
    RichText
} = wp.blockEditor;

const {
    Component,
    Fragment,
    useCallback
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
} = wp.components;

const NEW_TAB_REL = 'noopener noreferrer';

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
                btnBg,
                btnBgHover,
                btnShadow,
                btnTitle,
                btnTarget,
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
                titlePadding,
                titlePaddingMobile,
                titlePaddingTablet,
                titleMargin,
                titleMarginMobile,
                titleMarginTablet,
                titleAlignment,
                titleAlignmentMobile,
                titleAlignmentTablet,
                titleBorder,
                titleBorderType,
                titleBorderMobile,
                titleBorderTablet,
                titleBorderRadius,
                titleBorderRadiusMobile,
                titleBorderRadiusTablet,
                titleBorderColor,
                btnBgColor,
                btnBgHoverColor,
                titleBorderHoverColor,
                titleTextHoverColor,
                btnHref,
                btn_Htarget,
                btnSize,
                buttonIcon,
                iconPosition,
                buttonWidth,
                bntRounded,
                btnRel
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
      
      let newrel;
      let Isconcated = false
      if( !btnTarget){
          newrel = btnRel
          Isconcated = false
      } else  if( !btnTarget && btnRel === NEW_TAB_REL){
          newrel = btnRel
          Isconcated = false
      } else if ( btnTarget && !btnRel ) {
        newrel = NEW_TAB_REL
        Isconcated = true
      } else {
        newrel = NEW_TAB_REL + ' ' + btnRel
        Isconcated = true
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

      // =======================
      
      return (
            <Fragment>
                <InspectorControls key="inspector">
                <PanelBody title={`Button Settings`} icon={ getIcon( 'button' ) } className={ 'abblocks-panel-label' } initialOpen={false}>
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
                    label = { __( 'Button URL' ) }
                    placeholder	= { __( 'https://www.example.com' ) }		
                    help = { __( 'Please Enter http or https in url' ) }			
                    disableAdvanced
                    value={ btnHref }						
                    onChange={ ( value ) => setAttributes( { btnHref: value } ) }
                />
                <TextControl
                    label = { __( 'Button Rel' ) }
                    disableAdvanced
                    value={ btnRel }						
                    onChange={ ( value ) => setAttributes( { btnRel: value } ) }
                />
                <ToggleControl
                    label={ __( 'Open link in new window' ) }						
                    checked={ btnTarget }										
                    onChange={ ( ) => setAttributes( { btnTarget: ! btnTarget } ) }
                    
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
                                value={titleAlignmentMobile}
                                onChange={(value) => setAttributes({ titleAlignmentMobile: value })}
                            />
                          );
                        } else if ('boxtablet' === radiustab.name) {
                          tabout = (
                            <Alignment
                                label = {__('Alignment')}
                                disableJustify
                                alignmentType='content'
                                flex
                                value={titleAlignmentTablet}
                                onChange={(value) => setAttributes({ titleAlignmentTablet: value })}
                            />
                          );
                        } else {
                          tabout = (
                            <Alignment
                                label = {__('Alignment')}
                                disableJustify
                                alignmentType='content'
                                flex
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
                <BoxShadow label={__('Button Shadow')} value={btnShadow} onChange={(value) => setAttributes({ btnShadow: value })} />
                </PanelBody>    

                <PanelBody title={`Typography`} icon={ getIcon( 'typography' ) } className={ 'abblocks-panel-label' } initialOpen={false}>
                <TypographyControl
                        label={__("Button Typography")}
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
                </PanelBody>

                <PanelBody title={`Colors`} icon={ getIcon( 'colors' ) } className={ 'abblocks-panel-label' } initialOpen={false}>
                <h2>{__('Backgound & Text Color')}</h2>
                  <Tabs>
                    <Tab tabTitle={__('Normal')}>
                        <Color label={__('Text Color')} disableAlpha value={titleTextColor} onChange={val => setAttributes({ titleTextColor: val })} /> 
                        <Color label={__('Border Color')} disableAlpha value={titleBorderColor} onChange={val => setAttributes({ titleBorderColor: val })} /> 
                        
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
                        <Color label={__('Hover Color')} disableAlpha value={titleTextHoverColor} onChange={val => setAttributes({ titleTextHoverColor: val })} /> 
                        <Color label={__('Hover Border Color')} disableAlpha value={titleBorderHoverColor} onChange={val => setAttributes({ titleBorderHoverColor: val })} /> 
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

                <a href="https://www.affiliatebooster.com/#price" target="_blank" className={"BuyPremiumSettings"}>Buy Premium to Unlock More Settings</a>

                </InspectorControls>
                  <div id={`affiliate-style-${block_id}`} className={"affiliate-abbtn-wrapper "}>
                      <div className={`affiliate-abbtn-inner`} data-concate={Isconcated}>
                            <Fragment>
                            <span className={classnames(`affiliate-abbtn  btn-is-${btnSize} ${buttonWidth === "full" ? "btn-is-fullw" : buttonWidth === "flex" ? `btn-is-flex-${btnSize}` : "" } ${bntRounded ? 'btn-is-rounded' : ''}`)}>
                                { iconPosition && iconPosition == 'af-icon-is-left' &&
                                      <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                }
                                <RichText
                                    value={btnTitle}
                                    placeholder={__('Button text')}
                                    onChange={btnTitle => setAttributes({ btnTitle })}
                                    allowedFormats={ ['bold', 'italic', 'underline'] }
                                    href = {btnHref}
                                    {...(btnTarget ? {...btnTarget && { rel: newrel }} : { rel: newrel })}
                                    {...(btnTarget ? { target: '_blank' } : '')}
                                  
                                />
                                { iconPosition && iconPosition == 'af-icon-is-right' &&
                                      <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                }
                            </span>
                        </Fragment>    
                      </div>
                  </div>
                {loadtitleGoogleFonts}
                {loadcntnGoogleFonts}
            </Fragment>
        )
    }       
}

export default (Edit);