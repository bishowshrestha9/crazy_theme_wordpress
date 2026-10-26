const { __ } = wp.i18n
// import './editor.scss'
// import affiliateDevice from './Device'
const { Fragment, Component } = wp.element;
const { Tooltip,TabPanel,Dashicon } = wp.components;

class Alignment extends Component {
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
        
        const { label,flex,disableJustify,disableCenter, onMobileChange, onTabletChange } = this.props
        // const defData = ['left', 'center', 'right', 'justify'];
        // const defData = flex ? ['left', 'center', 'right'] : disableJustify ? (disableCenter ? ['left', 'right'] : ['left', 'center', 'right']) : ['left', 'center', 'right', 'justify'];
        const defData = flex ? ['flex-start', 'center', 'flex-end'] : disableJustify ? (disableCenter ? ['left', 'right'] : ['left', 'center', 'right']) : ['left', 'center', 'right', 'justify'];

        return (
            <div className={'affiliate-alignment-tools'} >
                <div className="">
                    {label && <label>{label}</label>}

                </div>
                <Fragment>
                {defData.map((data, index) => {
                        return (<button className={(this._filterValue() == data ? 'active' : '') + ' affiliate-button'} key={index} onClick={() => this.setSettings(this._filterValue() == data ? '' : data)}>
                            {this.props.alignmentType == 'content' ?
                                <Fragment>
                                    {(data == 'left' || data == 'flex-start') &&
                                        <Tooltip text={__('Left')}>
                                            <svg width="21" height="18" viewBox="0 0 21 18" xmlns="http://www.w3.org/2000/svg"><g transform="translate(-29 -4) translate(29 4)" fill="none"><path d="M1 .708v15.851" className="affiliate-svg-stroke" stroke-linecap="square" /><rect className="affiliate-svg-fill" x="5" y="5" width="16" height="7" rx="1" /></g></svg>
                                        </Tooltip>
                                    }
                                    {data == 'center' &&
                                        <Tooltip text={__('Middle')}>
                                            <svg width="16" height="18" viewBox="0 0 16 18" xmlns="http://www.w3.org/2000/svg"><g transform="translate(-115 -4) translate(115 4)" fill="none"><path d="M8 .708v15.851" className="affiliate-svg-stroke" stroke-linecap="square" /><rect className="affiliate-svg-fill" y="5" width="16" height="7" rx="1" /></g></svg>
                                        </Tooltip>
                                    }
                                    {(data == 'right' || data == 'flex-end') &&
                                        <Tooltip text={__('Right')}>
                                            <svg width="21" height="18" viewBox="0 0 21 18" xmlns="http://www.w3.org/2000/svg"><g transform="translate(0 1) rotate(-180 10.5 8.5)" fill="none"><path d="M1 .708v15.851" className="affiliate-svg-stroke" stroke-linecap="square" /><rect className="affiliate-svg-fill" fill-rule="nonzero" x="5" y="5" width="16" height="7" rx="1" /></g></svg>
                                        </Tooltip>
                                    }
                                </Fragment>
                                :
                                <i className={'fa fa-align-' + data} />
                            }
                        </button>)
                    })}
                </Fragment>  
            </div>
        )
    }
}
export default Alignment