import IconSelector from '../../components/fields/IconSelector/IconSelector.js';
import TypographyControl from '../components/typography/index.js';
import WebfontLoader from "../components/typography/fontloader"
import Range from '../../components/fields/Range/Range.js';
import BoxShadow  from './../../components/fields/BoxShadow.js';
import Color  from '../../components/fields/Color/Color.js';
import Dimension  from '../../components/fields/Dimension/Dimension.js';
import affiliate_styling  from './styling.js';
import RadioAdvanced from '../../components/fields/RadioAdvanced.js';
import Media from '../../components/fields/Media/Media.js';
import getIcon from '../../icons/get-icon';
import RichList from '../../components/rich-list';

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
    TabPanel,
    Dashicon
} = wp.components;

class Edit extends Component {

    constructor(props) {
        super(props)
        this.state = {
        }
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
                titleLoadGoogleFonts,
                listLoadGoogleFonts,
                className,
                recreateStyles,
                alignment,
                listType,
                bulletStyle,
                numberCorner,
                numberFontSize,
                numberBgSize,
                useNumberBg,
                titlefontFamily,
                titlefontWeight,
                
                // 
                listfontFamily,
                listfontWeight,
                listfontSubset,
                listfontSizeType,
                listfontSizeTypeMobile,
                listfontSizeTypeTablet,
                listfontSizeDesktop,
                listfontSizeMobile,
                listfontSizeTablet,
                listlineHeight,
                listlineHeightMobile,
                listlineHeightTablet,
                listletterSpacing,
                listletterSpacingMobile,
                listletterSpacingTablet,
                listletterSpacingType,
                listletterSpacingTypeTablet,
                listletterSpacingTypeMobile,
                listtextTransform,
                listtextDecoration,
                listfontStyle,
                listTextColor,
                numberofColumns,
                numberofColumnsMobile,
                numberofColumnsTablet,
                boxShadow,
                listIconColor,
                iconSpacing,
                iconSpacingMobile,
                iconSpacingTablet,
                itemSpacing,
                itemSpacingMobile,
                itemSpacingTablet,
                boxPadding,
                boxPaddingMobile,
                boxPaddingTablet,
                boxMargin,
                boxMarginMobile,
                boxMarginTablet,
                iconSize,
                iconSizeMobile,
                iconSizeTablet,
                iconType,
                image,
                listTextItems,
                bgColor
            }
        } = this.props;
        const { device } = this.state;
        var element = document.getElementById("affiliate-style-" + this.props.clientId)
        if (null != element && "undefined" != typeof element) {
             element.innerHTML = affiliate_styling(this.props, "affiliate-style")
        }
        
        let loadtitleGoogleFonts
        let loadlistGoogleFonts

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
        
        if( listLoadGoogleFonts == true ) {
					
			const listConfig = {
				google: {
					families: [ listfontFamily + ( listfontWeight ? ":" + listfontWeight : "" ) ],
				},
			}

			loadtitleGoogleFonts = (
				<WebfontLoader config={ listConfig }>
				</WebfontLoader>
			)
        }
        
        return (
            <Fragment>
                <InspectorControls key="inspector">

                <PanelBody title={`General Settings`} initialOpen={false}  icon={ getIcon( 'documentation' ) } className={ 'abblocks-panel-label' }>

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
                                <Range label={__('Number of Columns')} value={numberofColumnsMobile} onChange={(value) => setAttributes({ numberofColumnsMobile: value })} min={1} max={4} />
                                );
                            } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                <Range label={__('Number of Columns')} value={numberofColumnsTablet} onChange={(value) => setAttributes({ numberofColumnsTablet: value })} min={1} max={4} />
                                );
                            } else {
                                tabout = (
                                <Range label={__('Number of Columns')} value={numberofColumns} onChange={(value) => setAttributes({ numberofColumns: value })} min={1} max={4} />
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
                                <Range label={__('Icon Spacing')} value={iconSpacingMobile} onChange={(value) => setAttributes({ iconSpacingMobile: value })} min={1} max={60} />
                                );
                            } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                <Range label={__('Icon Spacing')} value={iconSpacingTablet} onChange={(value) => setAttributes({ iconSpacingTablet: value })} min={1} max={60} />
                                );
                            } else {
                                tabout = (
                                <Range label={__('Icon Spacing')} value={iconSpacing} onChange={(value) => setAttributes({ iconSpacing: value })} min={1} max={60} />
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
                                <Range label={__('Icon Size')} value={iconSizeMobile} onChange={(value) => setAttributes({ iconSizeMobile: value })} min={1} max={40} />
                                );
                            } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                <Range label={__('Icon Size')} value={iconSizeTablet} onChange={(value) => setAttributes({ iconSizeTablet: value })} min={1} max={40} />
                                );
                            } else {
                                tabout = (
                                <Range label={__('Icon Size')} value={iconSize} onChange={(value) => setAttributes({ iconSize: value })} min={1} max={40} />
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
                                <Range label={__('List Item Gap')} value={itemSpacingMobile} onChange={(value) => setAttributes({ itemSpacingMobile: value })} min={1} max={60} />
                                );
                            } else if ('boxtablet' === radiustab.name) {
                                tabout = (
                                <Range label={__('List Item Gap')} value={itemSpacingTablet} onChange={(value) => setAttributes({ itemSpacingTablet: value })} min={1} max={60} />
                                );
                            } else {
                                tabout = (
                                <Range label={__('List Item Gap')} value={itemSpacing} onChange={(value) => setAttributes({ itemSpacing: value })} min={1} max={60} />
                                );
                            }
                            }
                            return <div>{tabout}</div>;
                        }
                        }
                    </TabPanel>

                    <RadioAdvanced
                        label={__('Icon Type')}
                        options={[
                            { label: 'Icon', value: 'show-icon', title: 'Icon' },
                            { label: 'Image', value: 'show-image', title: 'Image' },
                        ]}
                        value={iconType}
                        onChange={(value) => setAttributes({ iconType: value })} />
                    { iconType == 'show-image' &&
                        <Media label={__('Image')} multiple={false} type={['image']} panel={true} value={image} onChange={val => setAttributes({ image: val })} />
                    }
                    {listType == 'unordered' && iconType == 'show-icon' &&
                        <Fragment>
                            <IconSelector
                                label="List Icon"
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

                    <BoxShadow label={__('Box-Shadow')} value={boxShadow} onChange={(value) => setAttributes({ boxShadow: value })} />
                </PanelBody>

                <PanelBody title={ __( 'Typography' ) } initialOpen={false}  icon={ getIcon( 'typography' ) } className={ 'abblocks-panel-label' }>
                    <TypographyControl
                        label={__("List Typography")}
                        attributes={attributes}
                        setAttributes={setAttributes}
                        loadGoogleFonts={{ value:listLoadGoogleFonts, label: __("listLoadGoogleFonts") }}
                        fontFamily={{ value: listfontFamily, label: __("listfontFamily") }}
                        fontWeight={{ value: listfontWeight, label: __("listfontWeight") }}
                        fontSubset={{ value: listfontSubset, label: __("listfontSubset") }}
                        fontSizeType={{ value: listfontSizeType, label: __("listfontSizeType") }}
                        fontSizeTypeMobile={{ value: listfontSizeTypeMobile, label: __("listfontSizeTypeMobile") }}
                        fontSizeTypeTablet={{ value: listfontSizeTypeTablet, label: __("listfontSizeTypeTablet") }}
                        fontSize={{ value: listfontSizeDesktop, label: __("listfontSizeDesktop") }}
                        fontSizeMobile={{ value: listfontSizeMobile, label: __("listfontSizeMobile") }}
                        fontSizeTablet={{ value: listfontSizeTablet, label: __("listfontSizeTablet") }}
                        lineHeightType={{ value: '', label: __(" ") }}
                        lineHeight={{ value: listlineHeight, label: __("listlineHeight") }}
                        lineHeightMobile={{ value: listlineHeightMobile, label: __("listlineHeightMobile") }}
                        lineHeightTablet={{ value: listlineHeightTablet, label: __("listlineHeightTablet") }}
                        letterSpacing={{ value: listletterSpacing, label: __("listletterSpacing") }}
                        letterSpacingMobile={{ value: listletterSpacingMobile, label: __("listletterSpacingMobile") }}
                        letterSpacingTablet={{ value: listletterSpacingTablet, label: __("listletterSpacingTablet") }}
                        letterSpacingType={{ value: listletterSpacingType, label: __("listletterSpacingType") }}
                        letterSpacingTypeTablet={{ value: listletterSpacingTypeTablet, label: __("listletterSpacingTypeTablet") }}
                        letterSpacingTypeMobile={{ value: listletterSpacingTypeMobile, label: __("listletterSpacingTypeMobile") }}
                        textTransform={listtextTransform}
                        onTextTransform={(value) => setAttributes({ listtextTransform: value })}
                        textDecoration={listtextDecoration}
                        onTextDecoration={(value) => setAttributes({ listtextDecoration: value })}
                        fontStyle={listfontStyle}
                        onFontStyle={(value) => setAttributes({ listfontStyle: value })}
                    />
                </PanelBody>

                <PanelBody title={ __( 'Colors' ) } initialOpen={false}  icon={ getIcon( 'colors' ) } className={ 'abblocks-panel-label' }>
                    <Color label={__('Box Background Color')} disableAlpha value={bgColor} onChange={val => setAttributes({ bgColor: val })} /> 

                    { iconType && iconType == 'show-icon' &&
                        <Color label={__('List Icon Color')} disableAlpha value={listIconColor} onChange={val => setAttributes({ listIconColor: val })} />
                    }
                    <Color label={__('List Color')} disableAlpha value={listTextColor} onChange={val => setAttributes({ listTextColor: val })} /> 

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

                <a href="https://www.affiliatebooster.com/#price" target="_blank" className={"BuyPremiumSettings"}>Buy Premium to Unlock More Settings</a>
                
                </InspectorControls>
                
                <div id={`affiliate-style-${block_id}`} className={`affiliate-block-${block_id}${className ? ` ${className}` : ''} affiliate-iconlist-wrapper`}>
                    <div className={`affiliate-iconlist-inner aff-list-is${iconType}`}>
                        <div className={`affiliate-block-advanced-list affiliate-icon-list affiliate-alignment-${alignment}`}>
                            { iconType && iconType == 'show-icon' &&
                                <RichList
                                    key={'content-list-icon'}
                                    value={listTextItems}
                                    setAttributes={setAttributes}
                                    attributeKey={'listTextItems'}
                                    className={`affiliate-list affiliate-list-type-unordered affiliate-list-bullet-${bulletStyle.name}`}
                                    editProps={this.props}
                                />
                            }
                            { iconType && iconType == 'show-image' &&
                                <RichList
                                    key={'content-list-image'}
                                    value={listTextItems}
                                    setAttributes={setAttributes}
                                    attributeKey={'listTextItems'}
                                    className={`affiliate-list aff-list-image`}
                                    editProps={this.props}
                                />
                            }
                        </div>
                    </div>
                </div>
                {loadtitleGoogleFonts}
                {loadlistGoogleFonts}
            </Fragment>
        )
    }       
}

export default (Edit);