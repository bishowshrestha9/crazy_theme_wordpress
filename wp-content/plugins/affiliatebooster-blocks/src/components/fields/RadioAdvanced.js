const { Component } = wp.element
const { Tooltip } = wp.components

class RadioAdvanced extends Component {

    setSettings(val){
        this.props.onChange( val )
    }

    render() {
        const { value, label, options } = this.props;
        return (
            <div className="affiliate-field affiliate-field-radio-advanced affiliate-d-flex affiliate-align-center">
                { label &&
                    <span>{label}</span>
                }
                <div className={`affiliate-field-button-list${label && ' affiliate-ml-auto'}`}>
                    {
                        options.map((data, index) => {
                            return (
                                <Tooltip text={ data.title || data.value }>
                                    <button className={(value == data.value ? 'active' : '') + ' affiliate-button'} key={index} onClick={ () => this.setSettings(data.value) }>
                                        {data.icon ? (<i className={ data.icon } />)
                                        : data.svg ? (<span className='affiliate-option-svg'>{data.svg}</span>)
                                        : data.label}
                                    </button>
                                </Tooltip>
                            )
                        })
                    }
                </div>
            </div>
        )
    }
}
export default RadioAdvanced