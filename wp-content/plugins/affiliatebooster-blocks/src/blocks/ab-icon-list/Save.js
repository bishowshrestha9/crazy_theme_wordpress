const { Component,Fragment } = wp.element;
import RichList from '../../components/rich-list';
const { RichText } = wp.blockEditor;
// const { HelperFunction: { animationAttr, IsInteraction } } = wp.affiliateComponents

class Save extends Component {
    renderListItems = () => {
        const { attributes: { listItems, listItemsTwo } } = this.props

        return listItems.map(item => <li className={`affiliate-list-item`}>{item}</li>)
    
    }

    renderListItemsWithImage = () => {
        const { attributes: { listItems, image } } = this.props

        return listItems.map(item => <li className={`affiliate-list-item`}>  <span className={`affiliate-list-img`}><img src={image.url} alt={image.alt} /> </span> {item}</li>)
    
    }

    render() {
        const { attributes: { uniqueId, block_id, alignment,listTextItems,titlefontWeight, image, iconType, bulletStyle, bulletStyleTwo, listType, animation, interaction, consTitle, propsTitle } } = this.props
        const ListTag = listType == 'ordered' ? 'ol' : 'ul'
        
        return (
            <div id={`affiliate-style-${block_id}`} className={`affiliate-block-${uniqueId} affiliate-iconlist-wrapper`}>
                <div className={`affiliate-iconlist-inner aff-list-is${iconType}`}>
                    <div className={`affiliate-block-advanced-list affiliate-icon-list affiliate-alignment-${alignment}`}>
                        <Fragment>
                            { iconType && iconType == 'show-icon' &&
                                <RichList.Content
                                    className={`affiliate-list affiliate-list-type-unordered affiliate-list-bullet-${bulletStyle.name}`}
                                    value={listTextItems}
                                />
                            }
                            { iconType && iconType == 'show-image' &&
                                <RichList.Content
                                    className={`affiliate-list aff-list-image`}
                                    value={listTextItems}
                                />
                            }
                        </Fragment>
                    </div>
                </div>
            </div>
        );
    }
}

export default Save;