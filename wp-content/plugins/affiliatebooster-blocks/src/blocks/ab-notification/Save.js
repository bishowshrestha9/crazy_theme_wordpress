const { Component,Fragment } = wp.element;
const { RichText } = wp.blockEditor;
class Save extends Component {
    renderListItems = () => {
        const { attributes: { listItems, listItemsTwo } } = this.props

        return listItems.map(item => <li>{item}</li>)
    
    }
    renderListItemsTwo = () => {
        const { attributes: {listItemsTwo } } = this.props

        return listItemsTwo.map(item => <li>{item}</li>)
    
    }
    render() {
        const { attributes: { uniqueId, block_id, boxStyle,titleTag1, noticeBtnText,noticeContent,alignment,titlefontWeight, bulletStyle, bulletStyleTwo, listType, animation, interaction, consTitle, propsTitle } } = this.props
        const ListTag = listType == 'ordered' ? 'ol' : 'ul'
        const Tag1 = titleTag1        
        return (
            <Fragment>
                    <div id={`affiliate-style-${block_id}`} className={`affiliate-block-${uniqueId} affiliate-notification-wrapper`}>
                        <div className={`affiliate-notification-inner`}>    
                            <RichText.Content
                                tagName={Tag1}
                                value={noticeContent}
                                className="affiliate-notification-content"
                                id={`notice-${block_id}`}
                            />
                        </div>    
                    </div>
            </Fragment>
        );
    }
}

export default Save;