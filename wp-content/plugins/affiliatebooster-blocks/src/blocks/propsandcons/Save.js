const { Component, Fragment } = wp.element;
// const { HelperFunction: { animationAttr, IsInteraction } } = wp.affiliateComponents
import RichList from '../../components/rich-list';
const {
    InspectorControls,
    BlockControls,
    RichText,
} = wp.blockEditor;

class Save extends Component {
    renderListItems = () => {
        const { attributes: { listItems, listItemsTwo } } = this.props

        return listItems.map(item => <li className={`affiliate-list-item`}>{item}</li>)
    
    }
    renderListItemsTwo = () => {
        const { attributes: {listItemsTwo } } = this.props

        return listItemsTwo.map(item => <li className={`affiliate-list-item`}>{item}</li>)
    
    }


    renderPositiveNotes = () => {
        const { attributes: { schemaPositiveNotes } } = this.props
        const items = schemaPositiveNotes.split(/\n/); 
        return items.map(function (item,n) {
                  return ([
                    <span itemprop="itemListElement" itemscope itemtype={`https://schema.org/ListItem`}>, 
                    <span itemprop="name">{item}</span>,
                    <span itemprop="position">{n+1}</span>,
                    </span>,
                  ]);
                })
    
    }
    renderNegativeNotes = () => {
        const { attributes: { schemaNegativeNotes } } = this.props
        const items = schemaNegativeNotes.split(/\n/);
        return items.map(function (item,n) {
                  return ([
                    <span itemprop="itemListElement" itemscope itemtype={`https://schema.org/ListItem`}>, 
                    <span itemprop="name">{item}</span>,
                    <span itemprop="position">{n+1}</span>,
                    </span>,
                  ]);
                })
    }

    render() {
        const { attributes: { uniqueId, block_id,titleTag1, listTextItems, listTextItemsTwo, alignment,titlefontWeight, bulletStyle, bulletStyleTwo, listType, animation, interaction, consTitle, propsTitle,


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


         } } = this.props
        const Tag1 = this.props.attributes.titleTag1
        // const interactionClass = IsInteraction(interaction) ? 'qubley-block-interaction' : '';
        const ListTag = listType == 'ordered' ? 'ol' : 'ul'
        return (
            <div id={`affiliate-style-${block_id}`} className={`affiliate-block-${uniqueId} affiliate-wrapper`}>

                { enableSchema && enableSchema == true && schemaType && schemaType == 'Product' &&  
                        <Fragment>
                        <asside style="visibility: hidden;height: 0px !important;display: inherit;" itemprop="Product" itemscope itemtype="https://schema.org/Product">
                            { schemaIdentifierType && schemaIdentifierType == 'gtin' && 
                                <span itemprop="gtin">{schemaIdentifier}</span>
                            }
                            { schemaIdentifierType && schemaIdentifierType == 'mpn' &&  
                                <span itemprop="mpn">{schemaIdentifier}</span>
                            }
                            { schemaIdentifierType && schemaIdentifierType == 'isbn' && 
                                <span itemprop="isbn">{schemaIdentifier}</span>
                            }
                            <span itemprop="name">{schemaTitle}</span>
                            <span itemprop="image">{schemaImage.url}</span>     
                            <span itemprop="description">{schemaDescription}</span>
                            <span itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                                <span itemprop="url">{schemaUrl}</span>
                                <span itemprop="availability">{schemaOffer}</span>
                                <span itemprop="price">{schemaOfferPrice}</span> 
                                <span itemprop="priceCurrency">{schemaOfferPriceCurrency}</span>
                                <span itemprop="priceValidUntil">{schemapriceValidUntil}</span>
                            </span>
                            <span itemprop="aggregateRating" itemscope itemtype={`https://schema.org/AggregateRating`}> 
                                <span itemprop="reviewCount">{schemaReviewCount}</span> 
                                <span itemprop="ratingValue">{schemaRating}</span>
                            </span>

                            <span itemprop="review" itemscope itemtype={`https://schema.org/Review`}>
                                <span itemprop="name">{schemaReviewTitle}</span>
                                <span itemprop="author" itemscope itemtype="https://schema.org/Person">
                                    <span itemprop="name">{schemaAuthor}</span>
                                </span>
                                <span itemprop="reviewRating" itemscope itemtype="https://schema.org/Rating">
                                    <span itemprop="ratingValue">{schemaRating}</span>  
                                    <span itemprop="bestRating">5</span>
                                </span>     
                            </span>

                            <span itemprop="positiveNotes" itemscope itemtype={`https://schema.org/ItemList`}>                                
                                {this.renderPositiveNotes()}  
                            </span>

                            <span itemprop="negativeNotes" itemscope itemtype={`https://schema.org/ItemList`}>
                                <span itemprop="itemListElement" itemscope itemtype={`https://schema.org/ListItem`}>
                                    {this.renderNegativeNotes()}   
                                </span> 
                            </span>


                            <span itemprop="sku">{schemaSku}</span>     
                            <span itemprop="brand" itemscope itemtype="https://schema.org/Brand">   
                                <span itemprop="name">{schemaBrand}</span>  
                            </span>                             
                        </asside>
                        </Fragment>
                    }


                <div className={`affiliate-d-table affiliate-procon-inner`}>
                    <div className={`affiliate-block-advanced-list affiliate-props-list affiliate-alignment-${alignment}`}>
                        <Tag1 className={`affiliate-props-title affiliate-propcon-title`}> { propsTitle } </Tag1>
                         <RichList.Content
                            className={`affiliate-list affiliate-list-type-unordered affiliate-list-bullet-${bulletStyle.name}`}
                            value={listTextItems}
                        />
                    </div>
                    <div className={`affiliate-block-advanced-list affiliate-cons-list affiliate-alignment-${alignment}`}>
                        <Tag1 className={`affiliate-const-title affiliate-propcon-title`}> { consTitle } </Tag1>
                         <RichList.Content
                            className={`affiliate-list affiliate-list-type-unordered affiliate-list-bullet-${bulletStyleTwo.name}`}
                            value={listTextItemsTwo}
                        /> 
                    </div>
                </div>
            </div>
        );
    }
}

export default Save;