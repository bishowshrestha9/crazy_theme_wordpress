import RichList from "../../components/rich-list";

const { Component,Fragment, renderToString } = wp.element;
// const { HelperFunction: { animationAttr, IsInteraction } } = wp.affiliateComponents
const { RichText } = wp.blockEditor;

class Save extends Component {
    renderListItems = () => {
        const { attributes: { listItems, listItemsTwo } } = this.props

        return listItems.map(item => <li className = {`affiliate-list-item affiliate-notice-content`}>{item}</li>)
    
    }

    render() {
        const { attributes: { uniqueId, block_id, boxStyle,titleTag1,contentType,noticeTitle,listTextItems,noticeBtnText,noticeContent,alignment,titlefontWeight, bulletStyle, bulletStyleTwo, listType, animation, interaction, consTitle, propsTitle } } = this.props
        const ListTag = listType == 'ordered' ? 'ol' : 'ul'
        const Tag1 = titleTag1
        let noticeTitleSlug = noticeTitle.toString().toLowerCase().replace(/( |<.+?>|&nbsp;)/g, "-");
        return (
            <Fragment>
                    <div id={`affiliate-style-${block_id}`} className={`affiliate-block-${uniqueId} affiliate-notice-wrapper`}>
                        <div className={`affiliate-notice-inner affiliate-block-advanced-list`}>
                            <div className={`affiliate-notice-title`}>    
                                <RichText.Content
                                    tagName={Tag1}
                                    value={noticeTitle}
                                    id={noticeTitleSlug}
                                />
                            </div>
                            <div className={`affiliate-notice-cntn-wrapper`}> 
                            { contentType && contentType == 'is-list' && (
                                    <RichList.Content
                                        className={`affiliate-list affiliate-list-type-unordered affiliate-list-bullet-${bulletStyle.name} affiliate-notice-content`}
                                        value={listTextItems}
                                    />
                                )
                            }
                            { contentType && contentType == 'is-p' &&    
                                <RichText.Content
                                    tagName="p"
                                    value={noticeContent}
                                    className="affiliate-notice-content"
                                />
                            }
                            </div>    
                        </div>    
                    </div>
            </Fragment>
        );
    }
}

export default Save;