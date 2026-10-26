import './editor.scss'
// import Device from './Device'
const { useState } = wp.element
const { Button, ButtonGroup } = wp.components

export default function ({ label, options, value, onChange, additionalClass, responsive, device: activeDevice, onDeviceChange }) {

    const [device, setDevice] = useState('md')
    let responsiveDevice = responsive ? activeDevice ? activeDevice : device : window.affiliateDevice

    const getValue = () => value ? (responsive ? (value[responsiveDevice] || '') : value) : '';
    const onButtonClick = val => onChange(responsive ? Object.assign({}, value, { [responsiveDevice]: val }) : val);

    const updateDevice = newDevice => {
        if (typeof activeDevice !== 'undefined') onChange({ ...value, device: newDevice });
        setDevice(newDevice);
    }

    return (
        <div className={'affiliate-field-group-btn affiliate-field ' + (responsive ? 'affiliate-responsive' : 'affiliate-d-flex')}>

            {responsive &&
                <div className="affiliate-d-flex affiliate-align-center affiliate-mb-10">
                    {label && <label> {label} </label>}
                    {/* {responsive && <Device device={responsiveDevice} commonResponsiveDevice={device} className="affiliate-ml-10" onChange={val => { device && onDeviceChange ? onDeviceChange(val) : updateDevice(val) }} />} */}
                </div>
            }

            {!responsive && label && <label> {label} </label>}

            <ButtonGroup className="affiliate-field-child affiliate-d-flex">
                {options.map(([title, option]) => {
                    const activeBtn = option === getValue() ? 'qubley-active-group-btn' : ''
                    return (<Button className={`qubley-group-button ${activeBtn}${additionalClass ? ` ${additionalClass}` : ''}`} onClick={() => onButtonClick(option)}>{title}</Button>)
                })}
            </ButtonGroup>
        </div>
    )
}