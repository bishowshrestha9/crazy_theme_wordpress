import IconSelector from '../../components/fields/IconSelector/IconSelector.js';
import TypographyControl from '../components/typography/index.js';
import WebfontLoader from "../components/typography/fontloader"
import Range from '../../components/fields/Range/Range.js';
import BoxShadow  from './../../components/fields/BoxShadow.js';
import Color  from '../../components/fields/Color/Color.js';
import affiliate_styling  from './styling.js';
import getIcon from '../../icons/get-icon';
import Media from './../../components/fields/Media/Media.js';
import Select  from './../../components/fields/Select/Select.js';
import RichList from '../../components/rich-list';

const { __ } = wp.i18n;
const {
    InspectorControls,
    BlockControls,
    RichText,
} = wp.blockEditor;

const {
    Component,
    Fragment
} = wp.element;

const {
    Button,
    PanelBody,
    IconButton,
    SelectControl,
    PanelRow,
    RangeControl,
    ToggleControl,
    TextControl,
    TextareaControl,
    Dashicon,
    Tooltip,
    Dropdown,
    Popover,
    TabPanel,
    ToolbarButton,
    ToolbarGroup
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
                propsTitle,
                consTitle,
                className,
                recreateStyles,
                alignment,
                listType,
                bulletStyle,
                bulletStyleTwo,
                numberCorner,
                numberFontSize,
                numberBgSize,
                useNumberBg,
                numberBg,
                numberBgHover,
                titleBgColor,
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
                propsBgColor,
                propsTextColor,
                propsIconColor,
                consBgColor,
                consTextColor,
                consIconColor,
                consListColor,
                propsListColor,
                boxShadow,
                listTextItems,
                listTextItemsTwo,
                titleTag1,
                titleTag2,

                enableSchema,
                schemaType,
                schemaSubType,
                schemaTitle,
                schemaDescription,
                schemaRating, 
                schemaReviewTitle,
                schemaReviewCount,
                schemaApplicationCategory,
                schemaOperatingSystem,
                schemaOffer,
                schemaOfferPrice, 
                schemaOfferPriceCurrency, 
                schemaBrand,
                schemaSku,
                schemaIdentifierType,
                schemaIdentifier,
                schemaUrl,
                schemaImage,
                schemapriceValidUntil,
                schemaAuthor,
                schemaPositiveNotes,
                schemaNegativeNotes,


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
        
        const Tag1 = titleTag1
        
        return (
            <Fragment>
                <InspectorControls key="inspector">
                <PanelBody title={`General Settings`} icon={ getIcon( 'layout' ) } className={ 'abblocks-panel-label' } initialOpen={false}>

                    <ToggleControl
                        label={ __( 'Show Schema' ) }                        
                        checked={ enableSchema }                                      
                        onChange={ ( ) => setAttributes( { enableSchema: ! enableSchema } ) }
                    />


                     <SelectControl
                        label={ __( 'Pros & Cons Heading Tag' ) }
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
                    

                    
                   
                    <BoxShadow label={__('Box-Shadow')} value={boxShadow} onChange={(value) => setAttributes({ boxShadow: value })} />
                </PanelBody>
                <PanelBody title={(listType == 'unordered') ? __('Pros Settings') : __('Number')} icon={ getIcon( 'advanced' ) } className={ 'abblocks-panel-label' } initialOpen={false}>

                        <TextControl        
                        label = { __( 'Pros Title' ) }              
                        value={ propsTitle }                        
                        onChange={ ( value ) => setAttributes( { propsTitle: value } ) }
                    />

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
                        
                        </PanelBody>
                    <PanelBody title={(listType == 'unordered') ? __('Cons Settings') : __('Number')} icon={ getIcon( 'advanced' ) } className={ 'abblocks-panel-label' } initialOpen={false}>

                     <TextControl
                        label = { __( 'Cons Title' ) }                      
                        value={ consTitle }                     
                        onChange={ ( value ) => setAttributes( { consTitle: value } ) }
                    />
                    
                        {listType == 'unordered' &&
                            <Fragment>
                                <IconSelector
                                    label="Cons Icon"
                                    value={bulletStyleTwo.name}
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
                                    onChange={val => setAttributes({ bulletStyleTwo: val })}
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
                    <PanelBody title={`Colors`} icon={ getIcon( 'colors' ) } className={ 'abblocks-panel-label' } initialOpen={false}>                

                        <Color label={__('Pros Background Color')} disableAlpha value={propsBgColor} onChange={val => setAttributes({ propsBgColor: val })} />
                        <Color label={__('Pros Text Color')} disableAlpha value={propsTextColor} onChange={val => setAttributes({ propsTextColor: val })} />
                        <Color label={__('Pros Icon Color')} disableAlpha value={propsIconColor} onChange={val => setAttributes({ propsIconColor: val })} />
                        <Color label={__('Pros List Color')} disableAlpha value={propsListColor} onChange={val => setAttributes({ propsListColor: val })} />

                        <Color label={__('Cons Background Color')} disableAlpha value={consBgColor} onChange={val => setAttributes({ consBgColor: val })} />
                        <Color label={__('Cons text Color')} disableAlpha value={consTextColor} onChange={val => setAttributes({ consTextColor: val })} />
                        <Color label={__('Cons Icon Color')} disableAlpha value={consIconColor} onChange={val => setAttributes({ consIconColor: val })} />        
                        <Color label={__('Cons List Color')} disableAlpha value={consListColor} onChange={val => setAttributes({ consListColor: val })} />
                    </PanelBody>


                    { enableSchema && enableSchema == true &&
              <PanelBody title={ __( 'Schema Settings' ) } initialOpen={false}  icon={ getIcon( 'documentation' ) } className={ 'abblocks-panel-label' }> 
                  <SelectControl
                    label={ __( 'Item type' ) }
                    value={ schemaType }
                    options= {[
                        { value: 'Product', label: 'Product' },
                                   
                      ]}
                      onChange={ ( value ) => setAttributes( { schemaType: value } ) }
                  />          

                          { schemaType && schemaType == 'Product' &&  
                            <Fragment>

                               <SelectControl
                        label={ __( 'Identifier type' ) }
                        value={ schemaIdentifierType }
                        options= {[
                            { value: 'gtin', label: 'GTIN' },
                                        { value: 'mpn', label: 'MPN' },
                                        { value: 'isbn', label: 'ISBN' },
                          ]}
                          onChange={ ( value ) => setAttributes( { schemaIdentifierType: value } ) }
                      />  

                      <TextControl    
                                  label = { __( 'Identifier' ) }        
                                  value={ schemaIdentifier }            
                                  onChange={ ( value ) => setAttributes( { schemaIdentifier: value } ) }
                              />  

                      <TextControl    
                                  label = { __( 'Title' ) }       
                                  value={ schemaTitle }           
                                  onChange={ ( value ) => setAttributes( { schemaTitle: value } ) }
                              />

                              <Media label={__('Image')} multiple={false} type={['image']} panel={true} value={schemaImage} onChange={val => setAttributes({ schemaImage: val })} />

                              <TextareaControl    
                                  label = { __( 'Description' ) }       
                                  value={ schemaDescription }           
                                  onChange={ ( value ) => setAttributes( { schemaDescription: value } ) }
                              />   

                              <SelectControl
                                label={ __( 'Offer Status' ) }
                                value={ schemaOffer }
                                options= {[
                                    { value: 'InStock', label: 'In Stock' },
                                                { value: 'Discontinued', label: 'Discontinued' },
                                                { value: 'InStoreOnly', label: 'In Store Only' },
                                                { value: 'LimitedAvailability', label: 'Limited Availability' },
                                                { value: 'OnlineOnly', label: 'Online Only' }, 
                                                { value: 'OutOfStock', label: 'Out Of Stock' },
                                                { value: 'PreOrder', label: 'Pre Order' },
                                                { value: 'PreSale', label: 'Pre Sale' },
                                                { value: 'SoldOut', label: 'Sold Out' },
                                  ]}
                                  onChange={ ( value ) => setAttributes( { schemaOffer: value } ) }
                              /> 

                              <TextControl    
                                  label = { __( 'Offer URL' ) }       
                                  value={ schemaUrl }           
                                  onChange={ ( value ) => setAttributes( { schemaUrl: value } ) }
                              />

                              <TextControl    
                                  label = { __( 'Offer Currency' ) }        
                                  value={ schemaOfferPriceCurrency }            
                                  onChange={ ( value ) => setAttributes( { schemaOfferPriceCurrency: value } ) }
                              />

                              <TextControl    
                                  label = { __( 'Offer Price' ) }       
                                  value={ schemaOfferPrice }            
                                  onChange={ ( value ) => setAttributes( { schemaOfferPrice: value } ) }
                              />

                              <TextControl    
                                  label = { __( 'Valid Until (yyyy-mm-dd)' ) }        
                                  value={ schemapriceValidUntil }           
                                  onChange={ ( value ) => setAttributes( { schemapriceValidUntil: value } ) }
                              />

                              <TextControl    
                                  label = { __( 'Rating (out of 5)' ) }       
                                  value={ schemaRating }            
                                  onChange={ ( value ) => setAttributes( { schemaRating: value } ) }
                              />


                              <TextControl    
                                  label = { __( 'Review Title' ) }       
                                  value={ schemaReviewTitle }           
                                  onChange={ ( value ) => setAttributes( { schemaReviewTitle: value } ) }
                              />

                              <TextControl    
                                  label = { __( 'Review Count' ) }        
                                  value={ schemaReviewCount }           
                                  onChange={ ( value ) => setAttributes( { schemaReviewCount: value } ) }
                              />

                              <TextControl    
                                  label = { __( 'SKU' ) }       
                                  value={ schemaSku }           
                                  onChange={ ( value ) => setAttributes( { schemaSku: value } ) }
                              />

                              <TextControl    
                                  label = { __( 'Brand' ) }       
                                  value={ schemaBrand }           
                                  onChange={ ( value ) => setAttributes( { schemaBrand: value } ) }
                              />
                              
                              <TextControl    
                                  label = { __( 'Review Author' ) }       
                                  value={ schemaAuthor }            
                                  onChange={ ( value ) => setAttributes( { schemaAuthor: value } ) }
                              /> 

                              <TextareaControl    
                                  label = { __( 'Pros (Please add each "Pro" item in new line)' ) }       
                                  value={ schemaPositiveNotes }           
                                  onChange={ ( value ) => setAttributes( { schemaPositiveNotes: value } ) }
                              />   

                              <TextareaControl    
                                  label = { __( 'Cons (Please add each "Cons" item in new line)' ) }       
                                  value={ schemaNegativeNotes }           
                                  onChange={ ( value ) => setAttributes( { schemaNegativeNotes: value } ) }
                              />  
                
                            </Fragment>
                  }
 
                  

              </PanelBody>
          }

            <a href="https://www.affiliatebooster.com/#price" target="_blank" className={"BuyPremiumSettings"}>Buy Premium to Unlock More Settings</a>

                </InspectorControls>
                
                <div id={`affiliate-style-${block_id}`} className={`affiliate-block-${block_id}${className ? ` ${className}` : ''} affiliate-wrapper`}>
                    <div className={`affiliate-d-table affiliate-procon-inner`}>
                        <div className={`affiliate-block-advanced-list affiliate-props-list affiliate-alignment-${alignment}`}>
                        <Tag1 className={`affiliate-props-title affiliate-propcon-title`}> { propsTitle } </Tag1>            
                            {/* {this.renderListItems()} */}
                            <RichList
                                key={'content-list-pros'}
                                value={listTextItems}
                                attributeKey={'listTextItems'}
                                className={`affiliate-list affiliate-list-type-unordered affiliate-list-bullet-${bulletStyle.name}`}
                                editProps={this.props}
                            />
                        </div>
                        <div className={`affiliate-block-advanced-list affiliate-cons-list affiliate-alignment-${alignment}`}>
                        <Tag1 className={`affiliate-const-title affiliate-propcon-title`}> { consTitle } </Tag1>            
                           <div className={`affiliate-block-${uniqueId}${className ? ` ${className}` : ''} affiliate-list-two-wrspper`}>
                                <RichList
                                    key={'content-list-cons'}
                                    value={listTextItemsTwo}
                                    attributeKey={'listTextItemsTwo'}
                                    className={`affiliate-list affiliate-list-type-unordered affiliate-list-bullet-${bulletStyleTwo.name}`}
                                    editProps={this.props}
                                />
                           </div> 
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