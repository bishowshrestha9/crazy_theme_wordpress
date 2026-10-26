/**
 * WordPress dependencies
 */
const { __ } = wp.i18n

const {
	RangeControl,
	ButtonGroup,
	Button,
	TabPanel,
	Dashicon,
	Flex,
	FlexBlock,
	FlexItem
} = wp.components
const { useInstanceId } = wp.compose;
// Extend component
const { Component, Fragment } = wp.element
const { TextControl } = wp.components;

/**
 * Internal dependencies
 */
import map from "lodash/map"


function RangeTypographyControl( props ) {

	const uniqueId = useInstanceId(
		RangeTypographyControl,
		'afb-font-size-control'
	);


	let sizeTypes

	if( "sizeTypes" in props ) {
		sizeTypes = props.sizeTypes
	} else {
		sizeTypes = [
			{ key: "px", name: __( "px" ) },
			{ key: "em", name: __( "em" ) },
			{ key: "rem", name: __( "rem" ) },
			{ key: "%", name: __( "%" ) },
		]
	}

	const sizeTypesControls = (
		<ButtonGroup className="affiliate-size-type-field" aria-label={ __( "Size Type" ) }>
			{ map( sizeTypes, ( { name, key } ) => (
				<Button
					key={ key }
					className="affiliate-size-btn"
					isSmall
					isPrimary={ props.type.value === key }
					aria-pressed={ props.type.value === key }
					onClick={ () => props.setAttributes( { [props.typeLabel]: key } ) }
				>
					{ name }
				</Button>
			) ) }
		</ButtonGroup>
	)
	return (
		<div className="aff-typography-range-options" key={uniqueId}>

			<TabPanel className="affiliate-size-type-field-tabs" activeClass="active-tab"
				tabs={ [
					{
						name: "desktop",
						title: <Dashicon icon="desktop" />,
						className: "affiliate-desktop-tab affiliate-responsive-tabs",
					},
					{
						name: "tablet",
						title: <Dashicon icon="tablet" />,
						className: "affiliate-tablet-tab affiliate-responsive-tabs",
					},
					{
						name: "mobile",
						title: <Dashicon icon="smartphone" />,
						className: "affiliate-mobile-tab affiliate-responsive-tabs",
					},
				] }>
				{
					( tab ) => {
						let tabout

						if ( "mobile" === tab.name ) {
							tabout = (
								<Fragment>
									<Flex align={'flex-end'}>
										<FlexBlock><label for={`${uniqueId}--mobile`}>{__( props.sizeMobileText )}</label>
										</FlexBlock>
										<FlexItem>{sizeTypesControls}</FlexItem>
									</Flex>
									<br />
									<Flex>
										<FlexItem>
											<TextControl
												type={'number'}
												key={`${uniqueId}-mobile-desktop`}
												value={ props.sizeMobile.value || '' }
												onChange={ ( value ) => props.setAttributes( { [props.sizeMobileLabel]: value } ) }
												min={ 0 }
												max={ props.maxVal = props.maxVal ? props.maxVal : 100 }
												id={`${uniqueId}--mobile`}
											/>
										</FlexItem>
										<FlexBlock>
											<RangeControl
												key={`${uniqueId}--mobile`}
												value={ props.sizeMobile.value }
												onChange={ ( value ) => props.setAttributes( { [props.sizeMobileLabel]: value } ) }
												min={ 0 }
												max={ props.maxVal = props.maxVal ? props.maxVal : 100 }
												step={ props.steps }
												withInputField={ false }
												// beforeIcon="editor-textcolor"
												allowReset
												// initialPosition={30}
											/>
										</FlexBlock>
									</Flex>
								</Fragment>
							)
						} else if ( "tablet" === tab.name ) {
							tabout = (
								<Fragment>
									<Flex align={'flex-end'}>
										<FlexBlock><label for={`${uniqueId}--tablet`}>{__( props.sizeTabletText )}</label>
										</FlexBlock>
										<FlexItem>{sizeTypesControls}</FlexItem>
									</Flex>
									<br />
									<Flex>
										<FlexItem>
										<TextControl
												type={'number'}
												key={`${uniqueId}-custom-tablet`}
												value={ props.sizeTablet.value || '' }
												onChange={ ( value ) => props.setAttributes( { [props.sizeTabletLabel]: value } ) }
												min={ 0 }
												max={ props.maxVal = props.maxVal ? props.maxVal : 100 }
												id={`${uniqueId}--tablet`}

											/>
										</FlexItem>
										<FlexBlock>
											<RangeControl
												key={`${uniqueId}--tablet`}
												value={ props.sizeTablet.value }
												onChange={ ( value ) => props.setAttributes( { [props.sizeTabletLabel]: value } ) }
												min={ 0 }
												max={ props.maxVal = props.maxVal ? props.maxVal : 100 }
												step={ props.steps }
												withInputField={ false }
												allowReset
												// initialPosition={30}
											/>
										</FlexBlock>
									</Flex>
								</Fragment>
							)
						} else {
							tabout = (
								<Fragment>
									<Flex align={'flex-end'}>
										<FlexBlock><label for={`${uniqueId}--desktop`}>{__( props.sizeText )}</label>
										</FlexBlock>
										<FlexItem>{sizeTypesControls}</FlexItem>
									</Flex>
									<br />
									<Flex>
										<FlexItem>
											<TextControl
												key={`${uniqueId}-custom-desktop`}
												type={'number'}
												value={ props.size.value || '' }
												onChange={ ( value ) => props.setAttributes( { [props.sizeLabel]: value } ) }
												min={ 0 }
												max={ props.maxVal = props.maxVal ? props.maxVal : 100 }
												id={`${uniqueId}--desktop`}
											/>
										</FlexItem>
										<FlexBlock>
											<RangeControl
												__nextHasNoMarginBottom
												key={`${uniqueId}--desktop`}
												value={ props.size.value }
												onChange={ ( value ) => props.setAttributes( { [props.sizeLabel]: value } ) }
												min={ 0 }
												max={ props.maxVal = props.maxVal ? props.maxVal : 100 }
												step={ props.steps }
												withInputField={ false }
												allowReset
												// initialPosition={30}
											/>
										</FlexBlock>
									</Flex>
								</Fragment>
							)
						}

						return <div>{ tabout }</div>
					}
				}
			</TabPanel>
		</div>
	)
}

export default RangeTypographyControl