const { Component,Fragment } = wp.element;
import classnames from "classnames"
const { RichText } = wp.blockEditor;
const NEW_TAB_REL = 'noopener noreferrer';
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
        const { attributes: { uniqueId,btnRel, block_id,showButton,titleTag1, showContent, showTitle, boxStyle, ctaTitle,ctaBtnText,ctaContent,iconPosition,btn_Htarget,ctaBtnHref,bntRounded,buttonWidth,buttonIcon,btnSize,alignment,titlefontWeight, bulletStyle, bulletStyleTwo, listType, animation, interaction, consTitle, propsTitle } } = this.props
        const ListTag = listType == 'ordered' ? 'ol' : 'ul'
        const Tag1  = titleTag1
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
        let ctaTitleSlug = ctaTitle.toString().toLowerCase().replace(/( |<.+?>|&nbsp;)/g, "-");

        return (
            <Fragment>
                { boxStyle && boxStyle == 'box-style-1' &&
                    <div id={`affiliate-style-${block_id}`} className={`affiliate-block-${uniqueId} affiliate-cta-wrapper`}>
                        <div className={`affiliate-cta-inner`}>    
                        <div className={`affiliate-cta-overlay`}></div>
                            { showTitle && showTitle == true &&
                                <RichText.Content
                                    tagName={Tag1}
                                    value={ctaTitle}
                                    id={ctaTitleSlug}
                                    className="affiliate-cta-title"
                                /> 
                            }
                            { showContent && showContent == true &&
                                <RichText.Content
                                    tagName="p"
                                    value={ctaContent}
                                    className="affiliate-cta-content"
                                />
                            }
                             { showButton && showButton == true &&
                                    <div className={`affiliate-cta-btn-wrapper`}>
                                        <a href = {ctaBtnHref} className={classnames(`affiliate-cta-btn  btn-is-${btnSize} ${buttonWidth === "full"? "btn-is-fullw": buttonWidth === "flex"? `btn-is-flex-${btnSize}`: ""} ${bntRounded ? 'btn-is-rounded' : ''}`).trim()}  
                                            {...(btn_Htarget ? {...btn_Htarget && { rel: newrel }} : { rel: newrel }  )}
                                            {...(btn_Htarget ? { target: '_blank' } : '' )}
                                            
                                            >
                                            { iconPosition && iconPosition == 'af-icon-is-left' &&
                                                <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                            }
                                            <RichText.Content value={ctaBtnText} />
                                            { iconPosition && iconPosition == 'af-icon-is-right' &&
                                                <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                            }
                                        </a>
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
                                <RichText.Content
                                    tagName={Tag1}
                                    value={ctaTitle}
                                    id={ctaTitleSlug}
                                    className="affiliate-cta-title"
                                />
                            }
                            { showContent && showContent == true &&
                                <RichText.Content
                                    tagName="p"
                                    value={ctaContent}
                                    className="affiliate-cta-content"
                                />
                            }
                            { showButton && showButton == true &&
                                <div className={`affiliate-cta-btn-wrapper`}>
                                    {/* <a href={ctaBtnHref} rel={``} className={`affiliate-cta-btn`}>
                                        { ctaBtnText }
                                    </a> */}
                                    <a href = {ctaBtnHref} className={classnames(`affiliate-cta-btn  btn-is-${btnSize} ${buttonWidth === "full"? "btn-is-fullw": buttonWidth === "flex"? `btn-is-flex-${btnSize}`: ""} ${bntRounded ? 'btn-is-rounded' : ''}`).trim()}  
                                            {...(btn_Htarget ? {...btn_Htarget && { rel: newrel }} : { rel: newrel }  )}
                                            {...(btn_Htarget ? { target: '_blank' } : '' )}
                                        >
                                        { iconPosition && iconPosition == 'af-icon-is-left' &&
                                            <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                        }
                                        <RichText.Content value={ctaBtnText} />
                                        { iconPosition && iconPosition == 'af-icon-is-right' &&
                                            <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                        }
                                    </a>
                                </div>
                            }
                        </div>
                    </div>
                }   
            </Fragment>
        );
    }
}

export default Save;