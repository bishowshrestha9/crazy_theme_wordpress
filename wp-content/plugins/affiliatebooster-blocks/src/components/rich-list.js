import PropTypes from 'prop-types';
import { getPasteEventData } from './pasting';
import { sanitizeHtml } from './utils';
const {
    InspectorControls,
    BlockControls,
    RichText
} = wp.blockEditor;
const {pasteHandler} = wp.blocks;
const { ENTER } = wp.keycodes;


const {
    Component,
    Fragment,
    createRef,
    renderToString
} = wp.element;
const ALLOWED_TAGS = ['a', 'strong', 'em', 'i', 'sub', 'sup', 'mark', 's', 'u', 'ol', 'li', 'ul', 'code', 'img', 'kdb'];

class RichList extends Component {
    constructor(props) {
        super(props);
        this.state = {
            contentArray: this.convertToStringArr(props.value),
            prevContent: [],
            focusIndex: null,
            hasRemoved: false,
            isPasting: false,
            isMerge: false,
        };
        this.handleChange = this.handleChange.bind(this);
        this.handleEnterPress = this.handleEnterPress.bind(this);
        this.handleDeletePress = this.handleDeletePress.bind(this);
        this.updateCursorPosition = this.updateCursorPosition.bind(this);
        this.handlePaste = this.handlePaste.bind(this);
        this.handleKeyDown = this.handleKeyDown.bind(this);
    }

    componentDidUpdate(prevProps, prevState) {
        if (this.state.contentArray !== prevState.contentArray) {

            if( this.props.onChange && 'function' === typeof this.props.onChange ) {
                this.props.onChange(this.state.contentArray)
            } else {
                // Update the block attribute when the state changes
                this.props.editProps.setAttributes({ [this.props.attributeKey]: this.state.contentArray });
            }

            // Handle List Paste
            if( ! this.state.isPasting ) {
                this.setState({prevContent: prevState.contentArray})
            } else {
                this.setState({isPasting: false})
            }
        }

        // Focus logic after state update
        if (this.state.focusIndex !== null && this.itemRefs[this.state.focusIndex]) {
            // Deferring the focus to the call stack ensures that the element is rendered
            setTimeout(() => {
                this.updateCursorPosition();
                this.setState({
                    focusIndex: null,
                    hasRemoved: false
                }); // Reset focusIndex
            });
        }
    }

    updateCursorPosition() {
        try {
            if( ! this.state.hasRemoved ) {
                this.itemRefs[this.state.focusIndex].current.focus();
                return;
            }

            if( this.state.isMerge ) {
                this.setState({isMerge: false});
                const element = this.itemRefs[this.state.focusIndex].current;
                const position = this.state.prevContent[this.state.focusIndex] ? this.state.prevContent[this.state.focusIndex].length : 0;

                const range = document.createRange();
                const selection = window.getSelection();
                range.setStart(element.firstChild, position);
                range.collapse(true); // Collapse the range to the start, which moves the cursor there
                
                selection.removeAllRanges(); // Remove any existing selections
                selection.addRange(range); // Add the new range
                element.focus(); // Focus the editable div to make sure the cursor is visible
                return;
            } else {
                
            }
            // this code to focus end of content when deleting any item
            const element = this.itemRefs[this.state.focusIndex].current;
            const range = document.createRange(); // Create a range
            range.selectNodeContents(element); // Select the contents of the element
            range.collapse(false); // Collapse the range to the end point
            const selection = window.getSelection(); // Get the selection object
            selection.removeAllRanges(); // Remove all ranges from the selection
            selection.addRange(range); // Add the newly created range
        } catch(error) {}
    }

    // Create the refs for your items
    componentWillMount() {
        this.itemRefs = this.props.value.map(() => createRef());
    }

    // You should also update the refs when you receive new props, in case the items have changed
    componentWillReceiveProps(nextProps) {
        if (nextProps.value.length !== this.props.value.length) {
            this.itemRefs = nextProps.value.map(() => createRef());
        }
    }
  

    handleEnterPress(splitValue, currentItem, index) {
        splitValue = splitValue || '';
        if( currentItem ) {
            this.handleChange(splitValue, index);
        } else {
            setTimeout( () => {
                const newContentArray = this.state.contentArray.slice();
                newContentArray.splice(index + 1, 0, splitValue);
                this.setState({
                    contentArray: newContentArray,
                    focusIndex: index + 1
                });
            } )
        }
    }

    handleKeyDown(event, index) {

        // We need only keypress Enter to add a new list item
        if ( event.keyCode !== ENTER ) {
			return;
		}

        // return if any combination with ENTER key
        if(event.shiftKey || event.altKey || event.ctrlKey || event.metaKey) {
            return;
        }

        const newContentArray = this.state.contentArray.slice();
        newContentArray.splice(index + 1, 0, '');
        this.setState({
            contentArray: newContentArray,
            focusIndex: index + 1
        });
    }

    handleChange(value = '', index){
        const newContentArray = [...this.state.contentArray];
        newContentArray[index] = value;
        this.setState({ contentArray: newContentArray });
    }

    handleDeletePress (index) {
        if( this.state.contentArray.length <= 1 ) {
            return;
        }
        if (this.state.contentArray[index] === '') {
            const newContentArray = [...this.state.contentArray];
            const nextIndex = index > 0 ? index - 1 : 0; // Calculate next index for focus
            newContentArray.splice(index, 1);
            this.setState({
                contentArray: newContentArray,
                focusIndex: newContentArray.length ? nextIndex : 0, // Update focus or set null if list is empty
                hasRemoved: true,
            });
        } else if (0 !== index) {
            const newContentArray = [...this.state.contentArray];
            newContentArray[index - 1] += newContentArray[index];
            // Remove the next element
            newContentArray.splice(index, 1);

            this.setState({
                contentArray: newContentArray,
                focusIndex: index - 1, // Update focus or set null if list is empty
                hasRemoved: true,
                isMerge: true,
            });
        }
    }

    convertToStringArr(contentArr) {
        if( ! contentArr || 0 === contentArr.length ) {
            return [''];
        }

        if( 'string' === typeof contentArr[0] ) {
            return contentArr;
        }

        contentArr = contentArr.map( content => {
            return renderToString(content.props.children);
        } );

        return contentArr;
    }

    handlePaste(event, index) {
        this.setState({ isPasting: true });
        event.preventDefault();
        const { html, plainText, files: [] } = getPasteEventData(event)
        const blocks = pasteHandler( {
            HTML: html,
            plainText,
            mode: 'inline',
            tagName: 'div',
        } );
        const pastedContent = [];

        blocks.forEach(block => {
            if('core/list' === block.name) {
                block.innerBlocks.forEach( list => {
                    pastedContent.push(list.attributes.content)
                } )
            } else {
                pastedContent.push(block.attributes.content)
            }
        });

        if( ! pastedContent.length ) {
            return;
        }

        if( ! this.state.prevContent[0] && 1 === this.state.prevContent.length ) {
            this.setState({ contentArray: pastedContent });
        } else {
            var startIndex = index;
            var endIndex = index + 1;
            if( this.state.prevContent[index] ) {
                startIndex = startIndex + 1;
            } else {
                endIndex = endIndex + 1; 
            }
            const updatedContent =  [
                ...this.state.prevContent.slice(0, startIndex),
                ...pastedContent,
                ...this.state.prevContent.slice(endIndex)
            ];
            this.setState({ contentArray: updatedContent });
        }

        return;

        // if single list or only content is in paste update in same list item
        if( 1 === pastedContent.length ) {
            this.handleChange(pastedContent[0], index);
        } else {
            if( ! this.state.prevContent[0] ) {
                this.setState({ contentArray: pastedContent });
            } else {
                const updatedContent =  [
                    ...this.state.prevContent.slice(0, index + 1),
                    ...pastedContent,
                    ...this.state.prevContent.slice(index + 1)
                ];
                this.setState({ contentArray: updatedContent });
            }
        }
    }

    render() {
        const { contentArray } = this.state;
        const TagName = this.props.tagName || 'ul';
        return (
            <TagName>
                {
                    contentArray.map((content, index) => (
                        <li key={index} className={this.props.className}>
                            <RichText
                                key={`rich-list-${index}`}
                                tagName="div"
                                value={content}
                                onChange={(value) => this.handleChange(value, index)}
                                placeholder={this.props.placeholder}
                                onKeyDown={(event) => {this.handleKeyDown(event, index)}}
                                onReplace={this.props.editProps.onReplace}
                                ref={this.itemRefs[index]}
                                onMerge={ () => this.handleDeletePress(index) }
                                onPaste={ (event) => this.handlePaste(event, index) }
                                __unstablePastePlainText
                            />
                        </li>
                    ))
                }
            </TagName>
        );
    }
}

class Content extends Component {
    constructor(props) {
        super(props);
        this.state = {};
    }
    render() {
        const Lists = this.props.value

        if( ! Lists ) {
            return <Fragment></Fragment>;
        }
        const TagName = this.props.tagName || 'ul';

        if( Array.isArray( Lists ) && 'string' === typeof Lists[0] ) {
            return(
                <TagName className={this.props.className}>
                    { Lists.map( list => (
                        <RichText.Content
                            value={list}
                            tagName={'li'}
                        />
                    ) ) }
                </TagName>
            )
        } else if( Array.isArray( Lists ) ) {
            if( Lists.length ) {
                return(
                    <TagName className={this.props.className} dangerouslySetInnerHTML={{__html: renderToString(Lists)}}/>
                )
            } else {
                return(<TagName className={this.props.className}><li></li></TagName>)
            }
        } else if ('string' === typeof Lists) {
            return(
                <RichText.Content
                    value={Lists}
                    tagName={TagName}
                    className={this.props.className}
                />
            )
        }
    }
}
RichList.Content = Content;

export default RichList;

RichList.propTypes = {
    value: PropTypes.array.isRequired,
    editProps: PropTypes.object.isRequired,
    attributeKey: PropTypes.string,
    tagName: PropTypes.string,
    placeholder: PropTypes.string,
    className: PropTypes.string,
    onChange: PropTypes.func,
}

RichList.defaultProps = {
    placeholder: 'Enter New Item'
}

/**
 * Convert string type to array type
 * 
 * @param {string} value 
 * @returns {array}
 */
export const getListValue = (value) => {
    if( Array.isArray( value ) ) {
        return value;
    }

    if( null === value || undefined === value ) {
        return [];
    }

    if( 'string' === typeof value ) {
        if( ! value.trim() ) {
            return [];
        } else {
            const list = wp.htmlEntities.decodeEntities(value);
            const listArr = list.split('</li>');
            return listArr.map( item => item.replace('<li>', '') ).filter( item => !! item );
        }
    }
}

/**
 * Set RichList Value in 
 * @param {array} listArray 
 * @returns {string}
 */
export const setListValue = (listArray) => {
    if( ! Array.isArray( listArray ) ) {
        return listArray;
    } else if( listArray.length )  {
        return listArray.map( list => `<li>${list}</li>` ).join('');
    } else {
        return '';
    }

}