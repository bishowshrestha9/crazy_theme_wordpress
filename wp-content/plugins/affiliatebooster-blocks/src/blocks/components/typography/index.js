/**
 * WordPress dependencies
 */
const { __ } = wp.i18n

/**
 * Internal dependencies
 */
import FontFamilyControl from "./font-typography"
import RangeTypographyControl from "./range-typography"
import TypographyStyles from "./inline-styles"
import "./editor.scss"

const {
	Button,
	Dashicon,
	SelectControl
} = wp.components


// Extend component
const { Component, Fragment } = wp.element

// Export for ease of importing in individual blocks.
export {
	TypographyStyles,
}

class TypographyControl extends Component {

	constructor() {
		super( ...arguments )
		this.onAdvancedControlClick  = this.onAdvancedControlClick.bind( this )
		this.onAdvancedControlReset  = this.onAdvancedControlReset.bind( this )
	}

	onAdvancedControlClick() {

		let control = true
		let label = __( "Hide Advanced" )

		if( this.state !== null && this.state.showAdvancedControls === true ) {
			control = false
			label = __( "Advanced" )
		}

		this.setState(
			{
				showAdvancedControls: control,
				showAdvancedControlsLabel: label
			}
		)
	}

	onAdvancedControlReset() {

		const { setAttributes } = this.props

		// Reset Font family to default.
		setAttributes( { [ this.props.fontFamily.label ]: "" } )
		setAttributes( { [ this.props.fontWeight.label ]: "" } )
		setAttributes( { [ this.props.fontSubset.label ]: "" } )
		setAttributes( { [ this.props.letterSpacing.label ]: "" } )

		// Reset Font Size to default.
		setAttributes( { [ this.props.fontSize.label ]: "" } )
		setAttributes( { [ this.props.fontSizeType.label ]: "px" } )
		setAttributes( { [ this.props.fontSizeTypeTablet.label ]: "px" } )
		setAttributes( { [ this.props.fontSizeTypeMobile.label ]: "px" } )
		setAttributes( { [ this.props.fontSizeMobile.label ]: "" } )
		setAttributes( { [ this.props.fontSizeTablet.label ]: "" } )

		// Reset Line Height to default.
		setAttributes( { [ this.props.lineHeight.label ]: "" } )
		setAttributes( { [ this.props.lineHeightType.label ]: "px" } )
		setAttributes( { [ this.props.lineHeightMobile.label ]: "" } )
		setAttributes( { [ this.props.lineHeightTablet.label ]: "" } )

		// Reset Letter Spacing to default.
		setAttributes( { [ this.props.letterSpacing.label ]: "" } )
		setAttributes( { [ this.props.letterSpacingMobile.label ]: "" } )
		setAttributes( { [ this.props.letterSpacingTablet.label ]: "" } )
		setAttributes( { [ this.props.letterSpacingType.label ]: "em" } )
		setAttributes( { [ this.props.letterSpacingTypeMobile.label ]: "em" } )
		setAttributes( { [ this.props.letterSpacingTypeTablet.label ]: "em" } )


		// Reset Google Fonts to default.
		setAttributes( { [ this.props.loadGoogleFonts.label ]: false } )

	}

	render() {

		let fontSize
		let fontWeight
		let fontFamily
		let letterSpacing
		let textTransforms
		let textDecorations
		let fontStyles
		let fontAdvancedControls
		let fontTypoAdvancedControls
		let showAdvancedFontControls
		let resetFontAdvancedControls

		const {
			disableFontFamily,
			disableFontSize,
			disableLineHeight,
			disableLetterSpacing,
			disableTextTransform,
			disableTextDecoration,
			disableFontStyle,
			textTransform,
			onTextTransform,
			textDecoration,
			onTextDecoration,
			fontStyle,
			onFontStyle,
			disableAdvancedOptions = false
		} = this.props

		const textTransformOptions = [
			{ value: 'None', label: 'None' },
			{ value: 'capitalize', label: 'Capitalize' },
			{ value: 'uppercase', label: 'Uppercase' },
			{ value: 'lowercase', label: 'Lowercase' },
		];

		const textDecorationOptions = [
			{ value: 'default', label: 'Default' },
			{ value: 'none', label: 'none' },
			{ value: 'underline', label: 'Underline' },
			{ value: 'overline', label: 'Overline' },
			{ value: 'line-through', label: 'Line Through' },
		];

		const fontStyleOptions = [
			{ value: 'default', label: 'Default' },
			{ value: 'normal', label: 'Normal' },
			{ value: 'italic', label: 'Italic' },
			{ value: 'oblique', label: 'Oblique' },
		];

		if( true !== disableFontFamily ) {
			fontFamily = (
				<FontFamilyControl
					{ ...this.props }
				/>
			)
		}

		if( true !== disableLineHeight ) {
			fontWeight = (
				<RangeTypographyControl
					sizeTypes = { '' }
					sizeTypesTablet = { '' }
					sizeTypesMobile = { '' }
					typeLabel = { this.props.lineHeightType.label }
					sizeMobile = { this.props.lineHeightMobile }
					sizeMobileLabel = { this.props.lineHeightMobile.label }
					sizeTablet = { this.props.lineHeightTablet }
					sizeTabletLabel = { this.props.lineHeightTablet.label }
					size = { this.props.lineHeight }
					sizeLabel = { this.props.lineHeight.label }
					sizeMobileText = { __( "Line Height" ) }
					sizeTabletText = { __( "Line Height" ) }
					sizeText = { __( "Line Height" ) }
					steps = { 1 }
					{ ...this.props }
				/>
			)
		}

		if( true !== disableLetterSpacing ) {
			letterSpacing = (
				<RangeTypographyControl
					type = { this.props.letterSpacingType }
					typeTablet = { this.props.letterSpacingTypeTablet }
					typeMobile = { this.props.letterSpacingTypeMobile }
					typeLabel = { this.props.letterSpacingType.label }
					typeLabelTablet = { this.props.letterSpacingTypeTablet.label }
					typeLabelMobile = { this.props.letterSpacingTypeMobile.label }
					sizeMobile = { this.props.letterSpacingMobile }
					sizeMobileLabel = { this.props.letterSpacingMobile.label }
					sizeTablet = { this.props.letterSpacingTablet }
					sizeTabletLabel = { this.props.letterSpacingTablet.label }
					size = { this.props.letterSpacing }
					sizeLabel = { this.props.letterSpacing.label }
					sizeMobileText = { __( "Letter Spacing" ) }
					sizeTabletText = { __( "Letter Spacing" ) }
					sizeText = { __( "Letter Spacing" ) }
					steps = { 1 }
					{ ...this.props }
				/>
			)
		}

		if( true !== disableFontSize ) {
			fontSize = (
				<RangeTypographyControl
					type = { this.props.fontSizeType }
					typeTablet = { this.props.fontSizeTypeTablet }
					typeMobile = { this.props.fontSizeTypeMobile }
					typeLabel = { this.props.fontSizeType.label }
					typeLabelTablet = { this.props.fontSizeTypeTablet.label }
					typeLabelMobile = { this.props.fontSizeTypeMobile.label }
					sizeMobile = { this.props.fontSizeMobile }
					sizeMobileLabel = { this.props.fontSizeMobile.label }
					sizeTablet = { this.props.fontSizeTablet }
					sizeTabletLabel = { this.props.fontSizeTablet.label }
					size = { this.props.fontSize }
					sizeLabel = { this.props.fontSize.label }
					sizeMobileText = { ( ! this.props.fontSizeLabel ) ? __( "Font Size" ) : this.props.fontSizeLabel }
					sizeTabletText = { ( ! this.props.fontSizeLabel ) ? __( "Font Size" ) : this.props.fontSizeLabel }
					sizeText = { ( ! this.props.fontSizeLabel ) ? __( "Font Size" ) : this.props.fontSizeLabel }
					steps = { 1 }
					{ ...this.props }
				/>
			)
		}

		if( true !== disableTextTransform ) {
			textTransforms = (
				<SelectControl
					label={ __( 'Text Transform' ) }
					value={ textTransform }
					options={ textTransformOptions }
					onChange={ ( value ) => onTextTransform( value ) }
				/>
			)
		}

		if( true !== disableTextDecoration ) {
			textDecorations = (
				<SelectControl
					label={ __( 'Text Decoration' ) }
					value={ textDecoration }
					options={ textDecorationOptions }
					onChange={ ( value ) => onTextDecoration( value ) }
				/>
			)
		}

		if( true !== disableFontStyle ) {
			fontStyles = (
				<SelectControl
					label={ __( 'Font Style' ) }
					value={ fontStyle }
					options={ fontStyleOptions }
					onChange={ ( value ) => onFontStyle( value ) }
				/>
			)
		}

		if( true !== disableFontFamily && true !== disableFontSize ) {
			fontAdvancedControls =  (
				<Button
					className="affiliate-size-btn affiliate-typography-control-btn"
					isSmall
					aria-pressed={ ( this.state !== null ) }
					onClick={ this.onAdvancedControlClick }
				><Dashicon icon="admin-tools" /></Button>
			)

			resetFontAdvancedControls =  (
				<Button
					className="affiliate-size-btn affiliate-typography-reset-btn"
					isSmall
					aria-pressed={ ( this.state !== null ) }
					onClick={ this.onAdvancedControlReset }
				><Dashicon icon="image-rotate" /></Button>
			)
		} else {
			showAdvancedFontControls = (
				<Fragment>
					{ fontSize }
					{ fontFamily }
					{ fontWeight }
					{ letterSpacing }
					{ fontStyles }
					{ textTransforms }
					{ textDecorations }
				</Fragment>
			)
		}


		if( this.state !== null && this.state.showAdvancedControls === true ) {

			showAdvancedFontControls = (
				<div className="affiliate-typography-advanced">
					{ fontSize }
					{ fontFamily }
					{ fontWeight }
					{ letterSpacing }
					{ fontStyles }
					{ textTransforms }
					{ textDecorations }
				</div>
			)
		}

		if( true !== disableFontFamily && true !== disableFontSize ) {
			fontTypoAdvancedControls =  (
				<div className="aff-typography-option-actions">
					<span>{ this.props.label }</span>
					{ fontAdvancedControls }
					{ resetFontAdvancedControls }
				</div>
			)
		}

		return (
			<div className="aff-typography-options">
				{ !disableAdvancedOptions &&
					<Fragment>
						{ fontTypoAdvancedControls }
						{ showAdvancedFontControls }
					</Fragment>
				}
			</div>
		)
	}
}

export default TypographyControl
