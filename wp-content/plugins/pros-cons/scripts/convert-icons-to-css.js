const fs = require( 'fs' );
const path = require( 'path' );

// Read the Icons.js file
const iconsFile = path.join( __dirname, '../src/admin/ui-components/Icons.js' );
const iconsContent = fs.readFileSync( iconsFile, 'utf8' );

// Extract SVG content from the icons
const svgRegex = /<svg[^>]*>[\s\S]*?<\/svg>/g;
const svgMatches = iconsContent.match( svgRegex );

if ( ! svgMatches ) {
	console.log( 'No SVG icons found in Icons.js' );
	process.exit( 1 );
}

// Convert SVG to CSS
let cssContent = '/* ProsCons Custom Icons for Elementor */\n\n';

// Define icon mappings
const iconMappings = [
	{ name: 'affx-icon-button', component: 'AffxButton' },
	{ name: 'affx-icon-cta', component: 'AffxCTA' },
	{ name: 'affx-icon-notice', component: 'AffxNotice' },
	{ name: 'affx-icon-product-comparison', component: 'AffxProductComparison' },
	{ name: 'affx-icon-product-table', component: 'AffxProductTable' },
	{ name: 'affx-icon-pros-cons', component: 'AffxProsCons' },
	{ name: 'affx-icon-single-product', component: 'AffxSingleProd' },
	{ name: 'affx-icon-product-spec', component: 'AffxProdSpec' },
	{ name: 'affx-icon-verdict', component: 'AffxVerdict' },
	{ name: 'affx-icon-versus-line', component: 'AffxVersusLine' },
	{ name: 'affx-icon-coupon-grid', component: 'AffxCouponGrid' },
	{ name: 'affx-icon-coupon-list', component: 'AffxCouponList' },
	{ name: 'affx-icon-product-image-button', component: 'AffxProductImageButtonIcon' },
	{ name: 'affx-icon-single-coupon', component: 'AffxSingleCoupon' },
	{ name: 'affx-icon-versus', component: 'AffxVersus' },
	{ name: 'affx-icon-rating-box', component: 'AffxRatingBox' },
	{ name: 'affx-icon-single-product-pros-cons', component: 'AffxSingleProductProsCons' },
	{ name: 'affx-icon-top-product', component: 'AffxTopProductIcon' },
	{ name: 'affx-icon-product-tabs', component: 'AffxProductTabs' },
	{ name: 'affx-icon-dynamic-listing', component: 'AffxDynamicListing' },
	{ name: 'affx-icon-dynamic-listing-white', component: 'AffxDynamicListingWhite' },
	{ name: 'affx-icon-rocket', component: 'RocketIcons' },
	{ name: 'affx-icon-doc', component: 'DocIcon' },
	{ name: 'affx-icon-video', component: 'VideoIcon' },
	{ name: 'affx-icon-bulb', component: 'BulbIcon' },
	{ name: 'affx-icon-help', component: 'HelpIcon' },
	{ name: 'affx-icon-block', component: 'BlockIcon' },
	{ name: 'affx-icon-question', component: 'QuestionIcon' },
	{ name: 'affx-icon-logo', component: 'LogoIcon' },
	{ name: 'affx-icon-modal-rocket', component: 'ModalRocketIcon' },
	{ name: 'affx-icon-close', component: 'CloseIcon' },
	{ name: 'affx-icon-amazon', component: 'AmazonIcon' },
	{ name: 'affx-icon-warning', component: 'WarningIcon' },
	{ name: 'affx-icon-check', component: 'CheckIcon' },
	{ name: 'affx-icon-swap', component: 'SwapIcon' },
	{ name: 'affx-icon-upload', component: 'UploadIcon' },
	{ name: 'affx-icon-plus', component: 'PlusIcon' },
	{ name: 'affx-icon-swap-bold', component: 'SwapBoldIcon' },
	{ name: 'affx-icon-trash', component: 'TrashIcon' },
	{ name: 'affx-icon-up-arrow', component: 'UpArrowIcon' },
	{ name: 'affx-icon-down-arrow', component: 'DownArrowIcon' },
	{ name: 'affx-icon-left-arrow', component: 'LeftArrowIcon' },
	{ name: 'affx-icon-right-arrow', component: 'RightArrowIcon' },
	{ name: 'affx-icon-spinner', component: 'SpinnerIcon' },
	{ name: 'affx-icon-error', component: 'ErrorIcon' },
	{ name: 'affx-icon-search', component: 'SearchIcon' },
	{ name: 'affx-icon-info', component: 'InfoIcon' }
];

// Find SVG content for each icon
iconMappings.forEach( ( mapping ) => {
	// Match both arrow function syntaxes: () => { ... } and () => ( ... )
	const componentRegex = new RegExp( `const ${ mapping.component } = \\(\\) => [\\(\\{][\\s\\S]*?<svg[^>]*>([\\s\\S]*?)<\\/svg>[\\s\\S]*?[\\)\\}];`, 'g' );
	const match = iconsContent.match( componentRegex );

	if ( match ) {
		const svgContent = match[ 0 ].match( /<svg[^>]*>([\s\S]*?)<\/svg>/ )[ 1 ];
		const svgAttributes = match[ 0 ].match( /<svg([^>]*)>/ )[ 1 ];

		// Extract width and height
		const widthMatch = svgAttributes.match( /width="([^"]*)"/ );
		const heightMatch = svgAttributes.match( /height="([^"]*)"/ );
		const viewBoxMatch = svgAttributes.match( /viewBox="([^"]*)"/ );

		const width = widthMatch ? widthMatch[ 1 ] : '24';
		const height = heightMatch ? heightMatch[ 1 ] : '24';
		const viewBox = viewBoxMatch ? viewBoxMatch[ 1 ] : '0 0 24 24';

		// Convert SVG to data URL
		const fullSvg = `<svg width="${ width }" height="${ height }" viewBox="${ viewBox }" fill="none" xmlns="http://www.w3.org/2000/svg">${ svgContent }</svg>`;
		const dataUrl = `data:image/svg+xml,${ encodeURIComponent( fullSvg ) }`;

		cssContent += `/* ${ mapping.component } Icon */\n`;
		cssContent += `.${ mapping.name }::before {\n`;
		cssContent += `    content: '';\n`;
		cssContent += `    display: inline-block;\n`;
		cssContent += `    width: ${ width }px;\n`;
		cssContent += `    height: ${ height }px;\n`;
		cssContent += `    background-image: url("${ dataUrl }");\n`;
		cssContent += `    background-size: contain;\n`;
		cssContent += `    background-repeat: no-repeat;\n`;
		cssContent += `    background-position: center;\n`;
		cssContent += `}\n\n`;

		console.log( `✓ Converted: ${ mapping.component } -> ${ mapping.name }` );
	} else {
		console.log( `✗ Failed to find: ${ mapping.component }` );
	}
} );

// Write the CSS file
const cssFile = path.join( __dirname, '../assets/css/custom-icons.css' );
fs.writeFileSync( cssFile, cssContent );

console.log( 'CSS file generated successfully at:', cssFile );
console.log( 'Icons converted:', iconMappings.length );
