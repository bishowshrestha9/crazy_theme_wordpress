const fs = require( 'fs' );
const path = require( 'path' );
const yaml = require( 'js-yaml' );

// Read the Font Awesome metadata
const iconsYml = fs.readFileSync( path.join( process.cwd(), 'node_modules/@fortawesome/fontawesome-free/metadata/icons.yml' ), 'utf8' );

// Parse YAML
const iconsData = yaml.load( iconsYml );

// Style priority for sorting
const stylePriority = {
	solid: 1,
	regular: 2,
	brands: 3
};

// Process icons into our format
const processedIcons = Object.entries( iconsData )
	.filter( ( [ name, data ] ) => { // eslint-disable-line
		// Filter out icons that don't have any styles
		return data.styles && data.styles.length > 0;
	} )
	.map( ( [ name, data ] ) => {
		// Get all available styles for this icon
		const styles = data.styles;

		// Create entries for each style
		return styles.map( ( style ) => {
			const prefix = style === 'regular' ? 'far' : style === 'solid' ? 'fas' : style === 'brands' ? 'fab' : 'fa';

			// Use the label for display name, fallback to the name
			const displayName = data.label || name;

			// Create a unique name by combining the display name and style
			const uniqueName = `${ displayName }-${ style }`;

			return {
				name: uniqueName,
				baseName: displayName,
				value: `${ prefix } fa-${ name }`,
				unicode: data.unicode,
				searchTerms: data.search?.terms || [],
				style,
				stylePriority: stylePriority[ style ] || 4
			};
		} );
	} )
	.flat();

// Write the processed icons to a JSON file
fs.writeFileSync( path.join( process.cwd(), 'src/blocks/ui-components/icon-picker/icons.json' ), JSON.stringify( processedIcons, null, 2 ) );

console.log( `Generated ${ processedIcons.length } icons successfully!` );
