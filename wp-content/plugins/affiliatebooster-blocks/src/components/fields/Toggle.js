// import '../css/toggle.scss'
import Device from './Device'
const { Component, Fragment } = wp.element
const { ToggleControl } = wp.components

class Toggle extends Component {
    constructor(props) {
        super(props)
        this.state = { current: this._filterValue() }
    }

    _filterValue() {
        return this.props.value ? (this.props.responsive ? (this.props.value[window.affiliateDevice] || '') : this.props.value) : ''
    }

    setSettings(val) {
        const { value, responsive, onChange } = this.props
        let final = responsive ? Object.assign({}, value, { [window.affiliateDevice]: val }) : val
        onChange(final)
        this.setState({ current: val })
    }

    render() {
        const { label, customClassName, responsive, device, onDeviceChange } = this.props
        return (
            <div className={'affiliate-field-toggle affiliate-field' + (this.props.responsive ? ' affiliate-responsive' : '') + (customClassName ? ` ${customClassName}` : '')}>
                <label>
                    {label && label}
                    {responsive &&
                        <Fragment>
                            {
                                device ?
                                    <Device device={device} commonResponsiveDevice={device} className="affiliate-ml-10" onChange={val => onDeviceChange(val)} />
                                    :
                                    <Device onChange={val => this.setState({ current: val })} />
                            }

                        </Fragment>
                    }
                </label>
                <ToggleControl
                    checked={this._filterValue()}
                    onChange={(val) => this.setSettings(val)}
                />
            </div>
        )
    }
}
export default Toggle