// import './editor.scss'
const { __ } = wp.i18n
const { Component, Fragment } = wp.element
import Toggle from '../Toggle'
import Color from '../Color/Color'
import IconListData from '../assets/IconListData'

class IconList extends Component {

    constructor(props) {
        super(props)
        this.state = {
            isOpen: false,
            filterText: '',
            showIcons: false
        }
    }

    render() {
        const { value, disableToggle, colorSettings, iconColor, onColorChange } = this.props
        const { filterText } = this.state
        var finalData = [];
        if (filterText.length > 2) {
            IconListData.forEach(name => {
                if (name.includes(filterText)) {
                    finalData.push(name)
                }
            })
        } else {
            finalData = IconListData;
        }
        return (
            <div className={`affiliate-field affiliate-field-icon-list ${disableToggle ? '' : 'affiliate-toggle-enabled'}`}>
                {this.props.label &&
                    <Fragment>
                        {!disableToggle ?
                            <Toggle label={this.props.label} className={'affiliate-icon-list-toggle'} value={this.props.value ? true : false} onChange={() => this.props.onChange(this.props.value ? '' : ' ')} />
                            :
                            <label>{this.props.label}</label>
                        }
                    </Fragment>
                }
                {colorSettings && <Color label={__(' Color')} value={iconColor || '#ccc'} onChange={(color) => onColorChange(color)} />}

                {(disableToggle || this.props.value != '') &&
                    <div className="affiliate-icon-list-wrapper">
                        <input type="text" value={this.state.filterText} placeholder="Search..." onChange={e => this.setState({ filterText: e.target.value })} autoComplete="off" />
                        <div className="affiliate-icon-list-icons">
                            {finalData.map(name => { return (<span className={value == name ? 'affiliate-active' : ''} onClick={e => { this.props.onChange(name) }}><span className={name} /></span>) })}
                        </div>
                    </div>
                }
            </div>
        )
    }
}
export default IconList