import classnames from "classnames"
const { Component,Fragment } = wp.element;
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
        const { attributes: { uniqueId,btnRel, block_id, boxStyle,buttonWidth,buttonIcon,bntRounded,iconPosition,conPosition,btnSize, btnTarget,btnTitle,noticeBtnText,btnHref,noticeContent,alignment,titlefontWeight, bulletStyle, bulletStyleTwo, listType, animation, interaction, consTitle, propsTitle } } = this.props
        const ListTag = listType == 'ordered' ? 'ol' : 'ul'
    
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
         
        return (
            <Fragment>
                    <div id={`affiliate-style-${block_id}`} className={"affiliate-abbtn-wrapper "}>
                      <div className={`affiliate-abbtn-inner`} data-concate={Isconcated}>
                            <a href = {btnHref} className={classnames(`affiliate-abbtn btn-is-${btnSize} ${buttonWidth === "full"? "btn-is-fullw":buttonWidth === "flex" ? `btn-is-flex-${btnSize}`:""} ${bntRounded ? 'btn-is-rounded':''}`).trim()}
                                {...(btnTarget ? {...btnTarget && { rel: newrel }} : { rel: newrel }  )}
                                {...(btnTarget ? { target: '_blank' } : '' )}
                                >
                                { iconPosition && iconPosition == 'af-icon-is-left' &&
                                    <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                }
                                <RichText.Content value={btnTitle} />
                                { iconPosition && iconPosition == 'af-icon-is-right' &&
                                    <i className={`affiliate-abbtn-icon ${buttonIcon} ${iconPosition}`}></i>
                                }
                            </a>
                      </div>
                  </div>
            </Fragment>
        );
    }
}

export default Save;