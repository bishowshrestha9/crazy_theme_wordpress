const { Component,Fragment } = wp.element;
import classnames from "classnames"
import ProductImage from './productImage';
import RichList from '../../components/rich-list';
const { RichText,InnerBlocks } = wp.blockEditor;
const NEW_TAB_REL = 'noopener noreferrer';
class Save extends Component {
    renderListItems = () => {
        const { attributes: { listItems, listItemsTwo } } = this.props
        return listItems.map(item => <li>{item}</li>)
    }
    
    render() {
        const { attributes: { uniqueId,designType,btnRel, block_id,titleTag1,titleTag2,pContent,contentType,boxStyle,listTextItems,spSubTitle,spBtnHref,btn_Htarget,image,iconPosition,buttonIcon,buttonWidth,btnShow,btnSize,bntRounded,spTitle,spBtnText,spContent,alignment,titlefontWeight, bulletStyle, listType, animation, interaction, consTitle, propsTitle, externalImage, externalImageSrc, externalImageAlt, showTitle, showSubTitle, showContent } } = this.props
        const ListTag = listType == 'ordered' ? 'ol' : 'ul'
        const Tag1  = titleTag1
        const Tag2  = titleTag2

        let newrel;
        if( !btn_Htarget){
            newrel = btnRel
        } else  if( !btn_Htarget && btnRel === NEW_TAB_REL){
            newrel = btnRel
        } else if ( btn_Htarget && !btnRel ) {
        newrel = NEW_TAB_REL
        } else {
        newrel = NEW_TAB_REL + ' ' + btnRel
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
             let spTitleSlug = spTitle.toString().toLowerCase().replace(/( |<.+?>|&nbsp;)/g, "-");
             let spSubTitleSlug = spSubTitle.toString().toLowerCase().replace(/( |<.+?>|&nbsp;)/g, "-");

        return (
            <Fragment>
                <div id={`affiliate-style-${block_id}`}>
                <div className={"affiliate-sp-wrapper"}>
                    <div className={`affiliate-sp-inner`}>
                    { showTitle && showTitle == true &&
                        <RichText.Content
                            tagName={Tag1}
                            value={spTitle}
                            id={spTitleSlug}
                            className="affiliate-sp-title"
                        />
                    }
                        <div className={`affiliate-sp-content`}>
                        <div className={`affiliate-sp-img-wrapper`}>

                            { designType && designType == 'ab-fixed-design' && !externalImage && !externalImage == true &&
                                <ProductImage attributes={this.props.attributes} />
                            }

                            { designType && designType == 'ab-fixed-design' &&  externalImage && externalImage == true &&
                                <div className={`affiliate-sp-image`}>
                                    <img src={externalImageSrc}  alt={externalImageAlt} />
                                </div>
                            }

                            { designType && designType == 'ab-dynamic-design' &&
                                <div className={`aff-inner-block`}>
                                    <InnerBlocks.Content/>
                                </div>
                            }

                        </div>
                        <div className={`affiliate-sp-cntn-wrapper affiliate-block-advanced-list`}>
                            { showSubTitle && showSubTitle == true &&
                                <RichText.Content
                                    tagName={Tag2}
                                    value={spSubTitle}
                                    id={spSubTitleSlug}
                                    className="affiliate-sp-subtitle"
                                />
                            }

                            { showContent && showContent == true &&
                                        <Fragment>
                            { contentType && contentType == 'is-list' &&
                                <RichList.Content
                                    className={`affiliate-list affiliate-list-type-unordered affiliate-list-bullet-${bulletStyle.name}`}
                                    value={listTextItems}
                                />
                            }
                            { contentType && contentType == 'is-p' &&
                                <RichText.Content
                                    tagName={'p'}
                                    value={pContent}
                                    className={'affiliate-pr-pcontent'}
                                />    
                            }  
                            </Fragment>
                                    } 
                            { btnShow && btnShow == true &&
                                <div className={`affiliate-sp-btn-wrapper`}>
                                    <a href = {spBtnHref} className={classnames(`affiliate-sp-btn  btn-is-${btnSize} ${buttonWidth === "full" ? "btn-is-fullw": buttonWidth === "flex"? `btn-is-flex-${btnSize}`: ""} ${bntRounded ? 'btn-is-rounded' : ''}`).trim()}  
                                        {...(btn_Htarget ? {...btn_Htarget && { rel: newrel }} : { rel: newrel }  )}
                                        {...(btn_Htarget ? { target: '_blank' } : '' )}
                                        >
                                        { iconPosition && iconPosition == 'af-icon-is-left' &&
                                            <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                        }
                                        <RichText.Content value={spBtnText} />
                                        { iconPosition && iconPosition == 'af-icon-is-right' &&
                                            <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                        }
                                    </a>
                                </div>
                            }
                        
                          </div>  
                        </div>
                    </div>
                  </div>  
                </div>
            </Fragment>            
        );
    }
}

export default Save;