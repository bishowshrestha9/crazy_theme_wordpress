// import '../css/range.scss';
import Device from '../Device';
const { Component } = wp.element;

class Range extends Component {
    constructor(props) {
        super(props);
        this.state = {
            current: '',
            device: 'md'
        };
    }

    _filterValue(type) {
        const { value, responsive } = this.props
        if (type == 'unit') {
            return value ? (value.unit || 'px') : 'px'
        } else {
            return value ? (responsive ? (value[window.affiliateDevice] || '') : value) : ''
        }
    }

    setSettings(val, type) {

        const {
            min,
            max,
            unit,
            value,
            onChange,
            responsive
        } = this.props;

        let newValue = {};

        if (typeof value === 'object' && Object.keys(value).length > 0) {
            newValue = JSON.parse(JSON.stringify(value));
        }

        if (unit && !newValue.hasOwnProperty('unit')) {
            newValue.unit = 'px';
        }

        if (type === 'unit' && responsive) {
            newValue.unit = val;
        } else {
            newValue = responsive ? Object.assign(newValue, value, { [window.affiliateDevice]: val }) : val;
            newValue = min ? (newValue < min ? min : newValue) : (newValue < 0 ? 0 : newValue);
            newValue = max ? (newValue > max ? max : newValue) : (newValue > 1000 ? 1000 : newValue);
        }
        onChange(newValue);
        this.setState({ current: newValue });
    }

    _minMax(type) {
        let unit = this._filterValue('unit')
        return (this.props[type] && this.props[type] != 0) ? (unit == 'em' ? Math.round(this.props[type] / 16) : this.props[type]) : 0
    }

    _steps() {
        let unit = this._filterValue('unit')
        return unit == 'em' ? .001 : (this.props.step || 1)
    }

    updateDevice(updatedDevice) {
        let { value, onChange, device } = this.props
        if (typeof device !== 'undefined') {
            onChange({ ...value, device: updatedDevice })
        }
        this.setState({ device: updatedDevice })
    }

    render() {
        const { unit, label, responsive, device, onDeviceChange } = this.props
        let responsiveDevice = responsive ? device ? device : this.state.device : window.affiliateDevice
        return (
            <div className={'affiliate-field-range affiliate-field ' + (responsive ? 'affiliate-responsive' : '')}>
                {(label || unit || responsive) &&
                    <div className="affiliate-d-flex affiliate-align-center affiliate-mb-10">
                        {label &&
                            <div>
                                <label htmlFor={'input'}>
                                    {label}
                                </label>
                            </div>
                        }

                        {responsive && <Device device={responsiveDevice} commonResponsiveDevice={device} className="affiliate-ml-10" onChange={(val) => { device && onDeviceChange ? onDeviceChange(val) : this.updateDevice(val) }} />}

                        {unit &&
                            <div className="affiliate-unit-btn-group affiliate-ml-auto">
                                {(typeof unit == 'object' ? unit : ['px', 'em', '%']).map((value) => (
                                    <button className={(this.props.value && value == this.props.value.unit) ? 'active' : ''}
                                        onClick={() => {
                                            this.setSettings(value, 'unit');
                                            // this.setSettings(this._filterValue(), 'range');
                                        }}
                                    >
                                        {value}
                                    </button>
                                ))}
                            </div>
                        }
                    </div>
                }

                <div className="affiliate-field-child">
                    <div className="affiliate-input-range">
                        <input
                            type="range"
                            min={this._minMax('min')}
                            max={this._minMax('max')}
                            value={this._filterValue()}
                            step={this._steps()}
                            onChange={e => this.setSettings(this._filterValue() == e.target.value ? '' : e.target.value, 'range')}
                        />
                        <input type="number" step={this._steps()} onChange={v => this.setSettings(v.target.value, 'range')} value={this._filterValue() + (this.props.suffix ? this.props.suffix : '')} {...(this.props.suffix && { disabled: true })} />
                    </div>
                </div>
            </div>
        )
    }
}
export default Range