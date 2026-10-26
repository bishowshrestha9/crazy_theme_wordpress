const { Component } = wp.element
class Dimension extends Component {

    constructor(props) {
        super(props);
        this.state = { lock: false, current: this._filterValue() }
    }

    componentDidMount() {
        if ( this.props.value.top === this.props.value.right && this.props.value.top === this.props.value.bottom && this.props.value.top === this.props.value.left) {
            this.setState( { lock: true } )
        } else{
             this.setState( { lock: false } )
        }
    }

    _filterValue(val) {

        // typeof Object.keys(value) === 'undefined' ? 'md' : window.affiliateDevice,
        const { value } = this.props
        if (typeof value == 'object' && typeof value !== 'undefined' && Object.keys(value).length > 0) {
            
            if (val) {
                return value[val]
            } else {
                return value
            }
        } else {
            return ''
        }
    }

    setSettings(action, position) {

        const { value, onChange, max, min,noLock } = this.props
        let newVal = action;
        if (typeof min != undefined) { newVal = action < min ? min : newVal }
        if (max) { newVal = action > max ? max : newVal }
        let data = (this.state.lock && position != 'unit') ? { 'top': newVal, 'right': newVal, 'bottom': newVal, 'left': newVal } : { [position]: newVal }
        data = Object.assign({}, value, data)
        const final = Object.assign({}, value, data)
        final.unit = data.unit || 'px'
        onChange(final)
        this.setState({ current: final })
    }

    render() {
        const { unit, label,noLock, device, onDeviceChange, clientId, min, max } = this.props;
        // let responsiveDevice = responsive ? device ? device : this.state.device : window.affiliateDevice;
        return (
            <div className={'affiliate-field-dimension affiliate-field '}>
                <div className="affiliate-d-flex affiliate-align-center affiliate-mb-10">
                    {label &&
                        <div>
                            <label htmlFor={'input'}>
                                {label}
                            </label>
                        </div>
                    }

                    {unit &&
                        <div className="affiliate-unit-btn-group affiliate-ml-auto">
                            {(typeof unit == 'object' ? unit : ['px', 'em', '%']).map((value) => (
                                <button className={(this.props.value && value == this.props.value.unit) ? 'active' : ''} onClick={() => {
                                    this.setSettings(value, 'unit');
                                }}>{value}</button>
                            ))}
                        </div>
                    }
                </div>
                <div className="affiliate-field-child">
                    <div className={"affiliate-dimension-input-group" + (!this.props.noLock ? ' hasLock' : '')}>
                        {['top', 'right', 'bottom', 'left'].map((val, index) => (<span>
                            <input type='number' {...typeof min != undefined ? { min } : ''} {...max ? { max } : ''} value={this._filterValue(val)}
                            onChange={
                                (v) => {
                                    this.setSettings(v.target.value, val)
                                    if (clientId) {
                                       // $('#block-' + clientId + ' .affiliate-section').addClass('active');
                                    }
                                }}
                            onBlur={
                                (v) => {
                                    if (clientId) {
                                        //$('#block-' + clientId + ' .affiliate-section').removeClass('active');
                                    }
                                }
                            } /><span>{this.props.dataLabel ? this.props.dataLabel[index] : val}</span></span>))}
                        {!this.props.noLock &&
                            <button className={(this.state.lock ? 'active ' : '') + 'affiliate-button'} onClick={() => this.setState({ lock: !this.state.lock })}>
                                {this.state.lock ? <span className={'dashicons dashicons-admin-links'} /> : <span className={'dashicons dashicons-editor-unlink'} />}
                            </button>
                        }
                    </div>
                </div>
            </div>
        )
    }
}
export default Dimension;