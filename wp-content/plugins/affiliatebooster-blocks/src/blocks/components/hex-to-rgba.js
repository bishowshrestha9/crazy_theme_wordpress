/**
 * function to return string with capital letter.
 * @param {string} hex the hex hex.
 * @param {number} alpha the alpha number.
 * @returns {string} rgba hex.
 */
// export default function hexToRGBA( hex, alpha ) {
// 	if ( null === hex ) {
// 		return '';
// 	}
// 	hex = hex.replace( '#', '' );
// 	const r = parseInt( hex.length === 3 ? hex.slice( 0, 1 ).repeat( 2 ) : hex.slice( 0, 2 ), 16 );
// 	const g = parseInt( hex.length === 3 ? hex.slice( 1, 2 ).repeat( 2 ) : hex.slice( 2, 4 ), 16 );
// 	const b = parseInt( hex.length === 3 ? hex.slice( 2, 3 ).repeat( 2 ) : hex.slice( 4, 6 ), 16 );
// 	return 'rgba(' + r + ', ' + g + ', ' + b + ', ' + alpha + ')';
// }

/**
 * Get HEX hex and return RGBA. Default return RGB hex.
 * @param {string} hex - The hex string.
 * @return {boolean} alpha The inline CSS class.
 */

function hexToRGBA ( hex, alpha ) {
	if ( undefined == hex || "" == hex ) {
		return ""
	}

	if ( undefined == alpha || "" == alpha ) {
		alpha = 100
	}

	hex = hex.replace( "#", "" )

	alpha = ( typeof alpha != "undefined" ) ? alpha: 1

	// Expand shorthand form (e.g. "03F") to full form (e.g. "0033FF")
	let shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i
	hex = hex.replace(shorthandRegex, function(m, r, g, b) {
		return r + r + g + g + b + b
	})

	let result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex)

	let parsed_hex = result ? {
		r: parseInt( result[1], 16 ),
		g: parseInt( result[2], 16 ),
		b: parseInt( result[3], 16 )
	} : null

	return "rgba(" + parsed_hex.r + "," + parsed_hex.g + "," + parsed_hex.b + "," + alpha + ")"

}

export default hexToRGBA