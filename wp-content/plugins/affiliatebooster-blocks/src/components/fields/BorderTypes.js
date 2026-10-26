const { __ } = wp.i18n
const { Fragment, Component } = wp.element;
const { Tooltip,TabPanel,Dashicon } = wp.components;

class BorderTypes extends Component {
    constructor(props) {
        super(props)
        this.state = { current: this._filterValue() }
    }
    _filterValue() {
        const { value,Tabletvalue,Mobilevalue } = this.props
        return value 
    }

    setSettings(val) {
        
        const { value, onChange,onTabletChange,onMobileChange } = this.props
        const final = val
        onChange(final)
        this.setState({ current: final })
        
    }
    render() { 
        
        const { value,label, onMobileChange, onTabletChange } = this.props
        const defData = [['solid', __('Solid')], ['dotted', __('Dotted')], ['dashed', __('Dashed')], ['double', __('Double')]]
        
        return (
            <div className="affiliate-field affiliate-field-border affiliate-d-flex affiliate-align-center">
                <div className="">
                    {label && <label>{label}</label>}

                </div>
                    <div className="affiliate-field-button-list affiliate-ml-auto">
                        {
                            defData.map((data, index) => {
                                return (
                                    <Tooltip text={data[1]}>
                                        <button className={(value == data[0] ? 'active' : '') + ' affiliate-button'} key={index} onClick={() => this.setSettings(data[0])}>
                                            <span className={`affiliate-field-border-type affiliate-field-border-type-${data[0]}`} />
                                        </button>
                                    </Tooltip>
                                )
                            })
                        }
                        {(value) &&
                        <Tooltip text={__('Clear')}>
                            <div className="affiliate-ml-10">
                                <span className="affiliate-border-clear" onClick={() => this.setSettings('')} role="button"><i className="fas fa-undo"/></span>
                            </div>
                        </Tooltip>
                    }
                    </div>
            </div>
        )
    }
}
export default BorderTypes