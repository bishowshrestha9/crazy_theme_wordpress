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
import Range  from './../../components/fields/Range/Range.js';
import { EmptyStar, FullStar, HalfStar } from "./icons";
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
    RangeControl 
} = wp.components;

class Edit extends Component {

    constructor(props) {
        super(props)
        this.state = { highlightedStars : 0 }
    }

    componentDidMount() {
        const {
            clientId,
            isSelected,
            setAttributes,
            attributes: {
                block_id
            }
        } = this.props;
        
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
            highlightedStars,
            attributes: {
                block_id,
                ratings,
                ratingsColor,
                ratingsSpacing,
                ratingsSize,
                selectedStars,
                itemPadding,
                itemPaddingMobile,
                itemPaddingTablet,
                ratingAlignemt,
                ratingAlignemtMobile,
                ratingAlignemtTablet,
                starsSize,
                starsSizeMobile,
                starsSizeTablet
              }
        } = this.props;
        
        var element = document.getElementById("affiliate-style-" + this.props.clientId)
        if (null != element && "undefined" != typeof element) {
             element.innerHTML = affiliate_styling(this.props, "affiliate-style")
        }
   
        return (
            <Fragment>
                <InspectorControls key="inspector">
                   <PanelBody title={__('Rating Settings')} icon={ getIcon( 'icons' ) } className={ 'abblocks-panel-label' } initialOpen={false}>
                   
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
                                            <Range
                                            label={__('Spacebetween Star')}
                                            value={itemPaddingMobile} 
                                            onChange={(value) => setAttributes({ itemPaddingMobile: value })}
                                            unit={[]}
                                            min={1}
                                            step={1}
                                            max={60}
                                        />
                                        );
                                      } else if ('boxtablet' === radiustab.name) {
                                        tabout = (
                                            <Range
                                                label={__('Spacebetween Star')}
                                                value={itemPaddingTablet} 
                                                onChange={(value) => setAttributes({ itemPaddingTablet: value })}
                                                unit={[]}
                                                min={1}
                                                step={1}
                                                max={60}
                                            />
                                        );
                                      } else {
                                        tabout = (
                                            <Range
                                                label={__('Spacebetween Star')}
                                                value={itemPadding} 
                                                onChange={(value) => setAttributes({ itemPadding: value })}
                                                unit={[]}
                                                min={1}
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
                                            label = {__('Alignment')}
                                            disableJustify
                                            alignmentType='content'
                                            flex
                                            value={ratingAlignemtMobile}
                                            onChange={(value) => setAttributes({ ratingAlignemtMobile: value })}/>
                                        );
                                      } else if ('boxtablet' === radiustab.name) {
                                        tabout = (
                                            <Alignment
                                                label = {__('Alignment')}
                                                disableJustify
                                                alignmentType='content'
                                                flex
                                                value={ratingAlignemtTablet}
                                                onChange={(value) => setAttributes({ ratingAlignemtTablet: value })}/>
                                        );
                                      } else {
                                        tabout = (
                                        <Alignment
                                            label = {__('Alignment')}
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
                   </PanelBody> 

                   <PanelBody title={`Colors`} icon={ getIcon( 'colors' ) } className={ 'abblocks-panel-label' } initialOpen={false}>
                    <Color label={__('Color')} value={ratingsColor} onChange={(value) => setAttributes({ ratingsColor: value })} />
                    </PanelBody>

                    <a href="https://www.affiliatebooster.com/#price" target="_blank" className={"BuyPremiumSettings"}>Buy Premium to Unlock More Settings</a>

                </InspectorControls>
                <div id={`affiliate-style-${block_id}`} className={`affiliate-starating-wrapper`} >
                <div className="affiliate-star-outer-container">
                    <div className="affiliate-star-inner-container"
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
                 
                </div>  
            </Fragment>
        )
    }       
}

export default (Edit);