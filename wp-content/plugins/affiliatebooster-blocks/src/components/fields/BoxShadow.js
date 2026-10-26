const { __ } = wp.i18n
import Color from './Color/Color'
import Toggle from './Toggle'
import RadioAdvanced from './RadioAdvanced'

const { TextControl } = wp.components
const { Component } = wp.element

const defaultData = {
    openShadow: 1,
    inset:'',
    horizontal: 0,
    vertical: 8,
    blur: 35,
    spread: 0,
    color:'rgba(0, 0, 0, 0.14)'
};

class BoxShadow extends Component {
    componentWillMount(){
        this.props.onChange( Object.assign( {}, defaultData, ( this.props.value || {} ) ));
	}

    setSettings( type, value ){
        this.props.onChange( Object.assign( {}, this.props.value , {[type]:value||0} ) )
    }

    render(){
        const { value,istextShadow } = this.props;
        
        return(
            <div className="affiliate-field affiliate-field-boxshadow">
                <Toggle
                    value={value.openShadow}
                    label={this.props.label}
                    onChange={ val => this.setSettings( 'openShadow', val ) }
                />
                
                { (value.openShadow == 1) &&
                    <div className="affiliate-field affiliate-d-flex affiliate-align-justified">
                        <Color
                            label={__('Color')}
                            value={ value.color || '' }
                            onChange={ val => this.setSettings( 'color', val ) }
                        />
                        <TextControl
                            label={__('X')}
                            type="number"
                            value={value.horizontal}
                            onChange={ val => this.setSettings( 'horizontal', val ) }
                        />
                        <TextControl
                            label={__( 'Y' )}
                            type="number"
                            value={value.vertical} 
                            onChange={ val => this.setSettings( 'vertical', val ) }
                        />
                        <TextControl
                            label={__( 'Blur' )}
                            type="number"
                            value={value.blur} 
                            onChange={ val => this.setSettings( 'blur', val ) }
                        />
                      {!this.props.istextShadow &&  
                        <TextControl 
                            label={__( 'Spread' )}
                            type="number"
                            value={value.spread} 
                            onChange={ val => this.setSettings( 'spread', val ) }
                        />}
                    </div>
                }

                { (value.openShadow == 1 && !this.props.istextShadow && !this.props.disableInset) && !this.props.istextShadow &&
                    
                    <RadioAdvanced label={__('Inset')} value={value.inset} onChange={ val => this.setSettings( 'inset', val ) }
                                    options={[
                                        { label: __('Inset'), value: 'inset', title: __('Inset') },
                                        { label: __('Outset'), value: '', title: __('Outset') }
                                    ]}
                                />
                }
            </div>
        )
    }
}
export default BoxShadow