const { __ } = wp.i18n
import '../Select/Select'
import Device from '../Device'
const { Component, Fragment } = wp.element

class Select extends Component {
    constructor(props) {
        super(props)
        this.state = { current: this._filterValue() }
    }
    _filterValue() {
        const { value, responsive, responsiveGroup } = this.props
        return value ? ((responsive || responsiveGroup) ? (value[window.affiliateDevice] || '') : value) : ''
    }
    setSettings(val) {
        const { value, onChange, responsive, responsiveGroup } = this.props
        const final = (responsive || responsiveGroup) ? Object.assign({}, value, { [window.affiliateDevice]: val }) : val
        onChange(final)
        this.setState({ current: final })
    }
    render() {
        const { label, responsive, device, onDeviceChange } = this.props
        const direction = this.props.direction == 'left' ? '  direction-left' : this.props.direction == 'right' ? '  direction-right' : '';

        return (
            <div className={"affiliate-field-select affiliate-field " + (this.props.responsive ? 'affiliate-responsive' : '')}>
                {responsive &&
                    <Fragment>
                        {
                            device ?
                                <Device device={device} commonResponsiveDevice={device} className="affiliate-ml-10" onChange={(val) => onDeviceChange(val)} />
                                :
                                <Device onChange={(val) => this.setState({ current: val })} />
                        }
                    </Fragment>
                }
                {label && <label>{label}</label>}
                
                <div className="affiliate-field-child">
                    <div className={"affiliate-popup-select " + direction} ref={node => this.node = node}>
                        {(this.props.clear && this._filterValue() != '') &&
                            <span className={'affiliate-clear'} onClick={() => this.setSettings("")}><i className="fas fa-times" /></span>
                        }
                        <select value={this._filterValue()} onChange={(e) => this.setSettings(e.target.value)}>
                            {(this.props.noValue || this.props.clear) &&
                                <option value="">{this.props.noValue || this.props.initial ? this.props.initial : __('Select')}</option>
                            }
                            {this.props.options.map(item =>
                                <option value={Array.isArray(item) ? item[0] : item} onClick={() => this.setSettings(Array.isArray(item) ? item[0] : item)}>{Array.isArray(item) ? item[1] : item}</option>
                            )}
                        </select>
                    </div>
                </div>
            </div>
        )
    }
}
export default Select