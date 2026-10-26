( function( wp ) {
	var registerBlockType = wp.blocks.registerBlockType;
	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var __ = wp.i18n.__;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var RichText = wp.blockEditor.RichText;
	var PanelBody = wp.components.PanelBody;
	var SelectControl = wp.components.SelectControl;
	var ToggleControl = wp.components.ToggleControl;
	var ColorPalette = wp.components.ColorPalette || wp.blockEditor.ColorPalette;
	var Button = wp.components.Button;

	function generateUniqueId() {
		return Math.random().toString( 36 ).substring( 2, 9 );
	}

	var defaultProsItems = [
		{ content: 'Quick and hassle-free three-step setup.' },
		{ content: 'Cost-effective alternative to new routers.' },
		{ content: 'Compact design does not block adjacent outlets.' },
		{ content: 'Effectively eliminates moderate dead zones.' }
	];

	var defaultConsItems = [
		{ content: 'Stock can be limited due to high demand' },
		{ content: 'Available only through official website' },
		{ content: 'Delivery time can varies by region' }
	];

	function parseHtmlToListItems( htmlString ) {
		if ( ! htmlString || typeof htmlString !== 'string' ) return [];
		var matches = htmlString.match( /<li[^>]*>(.*?)<\/li>/gi );
		if ( ! matches ) return [];
		return matches.map( function( item ) {
			return { content: item.replace( /<\/?li[^>]*>/gi, '' ) };
		} );
	}

	function itemsToHtmlString( items ) {
		if ( ! Array.isArray( items ) ) return '';
		return items.map( function( item ) {
			return '<li>' + ( item.content || '' ) + '</li>';
		} ).join( '' );
	}

	function sanitizeColor( color, fallback ) {
		if ( typeof color !== 'string' ) return fallback;
		var trimmed = color.trim();
		if ( /^#([0-9a-fA-F]{3,4}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/.test( trimmed ) ) return trimmed;
		if ( /^rgba?\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*(?:,\s*(?:0|1|0?\.\d+)\s*)?\)$/i.test( trimmed ) ) return trimmed;
		if ( /^hsla?\(\s*\d+\s*,\s*\d+%\s*,\s*\d+%\s*(?:,\s*(?:0|1|0?\.\d+)\s*)?\)$/i.test( trimmed ) ) return trimmed;
		if ( trimmed.toLowerCase() === 'transparent' ) return 'transparent';
		return fallback;
	}

	function sanitizeBlockId( id ) {
		if ( typeof id !== 'string' || ! /^[a-zA-Z0-9_-]{1,64}$/.test( id ) ) {
			return 'preview';
		}
		return id;
	}

	function sanitizeLayout( layout ) {
		var allowed = [ 'layout-type-1', 'layout-type-2', 'layout-type-3', 'layout-type-4' ];
		return allowed.indexOf( layout ) !== -1 ? layout : 'layout-type-1';
	}

	function EditComponent( props ) {
		var attributes = props.attributes;
		var setAttributes = props.setAttributes;

		if ( ! attributes.block_id ) {
			setAttributes( { block_id: generateUniqueId() } );
		}

		var layoutStyle = sanitizeLayout( attributes.layoutStyle || 'layout-type-1' );
		var prosIconStatus = attributes.prosIconStatus !== false;
		var consIconStatus = attributes.consIconStatus !== false;
		var prosBgColor = sanitizeColor( attributes.prosBgColor, '#24B644' );
		var consBgColor = sanitizeColor( attributes.consBgColor, '#F13A3A' );
		var prosTextColor = sanitizeColor( attributes.prosTextColor, '#ffffff' );
		var consTextColor = sanitizeColor( attributes.consTextColor, '#ffffff' );
		var prosListBgColor = sanitizeColor( attributes.prosListBgColor, '#F5FFF8' );
		var consListBgColor = sanitizeColor( attributes.consListBgColor, '#FFF5F5' );
		var prosIconColor = sanitizeColor( attributes.prosIconColor, '#24B644' );
		var consIconColor = sanitizeColor( attributes.consIconColor, '#F13A3A' );

		var blockId = sanitizeBlockId( attributes.block_id || 'preview' );

		// Resolve Pros items array strictly
		var prosItems;
		if ( Array.isArray( attributes.prosListItems ) && attributes.prosListItems.length > 0 ) {
			prosItems = attributes.prosListItems;
		} else if ( typeof attributes.prosListContent === 'string' && attributes.prosListContent.trim() !== '' ) {
			prosItems = parseHtmlToListItems( attributes.prosListContent );
		} else {
			prosItems = defaultProsItems;
		}

		// Resolve Cons items array strictly
		var consItems;
		if ( Array.isArray( attributes.consListItems ) && attributes.consListItems.length > 0 ) {
			consItems = attributes.consListItems;
		} else if ( typeof attributes.consListContent === 'string' && attributes.consListContent.trim() !== '' ) {
			consItems = parseHtmlToListItems( attributes.consListContent );
		} else {
			consItems = defaultConsItems;
		}

		var dynamicCss =
			'#affiliatex-pros-cons-style-' + blockId + ' { width: 100%; max-width: 1120px; margin-left: auto; margin-right: auto; box-sizing: border-box; } ' +
			'#affiliatex-pros-cons-style-' + blockId + ' .affiliatex-block-pros { background: ' + prosBgColor + '; color: ' + prosTextColor + '; padding: 12px 20px; border-top-left-radius: 4px; border-top-right-radius: 4px; display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 18px; } ' +
			'#affiliatex-pros-cons-style-' + blockId + ' .affiliatex-block-cons { background: ' + consBgColor + '; color: ' + consTextColor + '; padding: 12px 20px; border-top-left-radius: 4px; border-top-right-radius: 4px; display: flex; align-items: center; gap: 8px; font-weight: 600; font-size: 18px; } ' +
			'#affiliatex-pros-cons-style-' + blockId + ' .affiliatex-block-pros .affiliatex-title { color: ' + prosTextColor + '; margin: 0; font-size: 18px; font-weight: 600; line-height: 1.2; } ' +
			'#affiliatex-pros-cons-style-' + blockId + ' .affiliatex-block-cons .affiliatex-title { color: ' + consTextColor + '; margin: 0; font-size: 18px; font-weight: 600; line-height: 1.2; } ' +
			'#affiliatex-pros-cons-style-' + blockId + ' .affx-pros-inner .affiliatex-pros { background: ' + prosListBgColor + '; padding: 20px; min-height: 140px; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px; } ' +
			'#affiliatex-pros-cons-style-' + blockId + ' .affx-cons-inner .affiliatex-cons { background: ' + consListBgColor + '; padding: 20px; min-height: 140px; border-bottom-left-radius: 4px; border-bottom-right-radius: 4px; } ' +
			'#affiliatex-pros-cons-style-' + blockId + ' .affiliatex-pros ul { list-style: none; margin: 0; padding: 0; } ' +
			'#affiliatex-pros-cons-style-' + blockId + ' .affiliatex-cons ul { list-style: none; margin: 0; padding: 0; } ' +
			'#affiliatex-pros-cons-style-' + blockId + ' .affiliatex-pros ul li { list-style: none; position: relative; padding-left: 28px; margin-bottom: 12px; color: #292929; font-size: 15px; line-height: 1.5; display: flex; align-items: center; } ' +
			'#affiliatex-pros-cons-style-' + blockId + ' .affiliatex-cons ul li { list-style: none; position: relative; padding-left: 28px; margin-bottom: 12px; color: #292929; font-size: 15px; line-height: 1.5; display: flex; align-items: center; } ' +
			'#affiliatex-pros-cons-style-' + blockId + ' .affiliatex-pros ul li::before { content: ""; position: absolute; left: 0; top: 3px; width: 18px; height: 18px; border-radius: 50%; background: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%2324B644\' stroke-width=\'2.2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Ccircle cx=\'12\' cy=\'12\' r=\'10\'/%3E%3Cpolyline points=\'8 12 11 15 16 9\'/%3E%3C/svg%3E") no-repeat center center / contain; border: none; } ' +
			'#affiliatex-pros-cons-style-' + blockId + ' .affiliatex-cons ul li::before { content: ""; position: absolute; left: 0; top: 3px; width: 18px; height: 18px; border-radius: 50%; background: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23F13A3A\' stroke-width=\'2.2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Ccircle cx=\'12\' cy=\'12\' r=\'10\'/%3E%3Cline x1=\'15\' y1=\'9\' x2=\'9\' y2=\'15\'/%3E%3Cline x1=\'9\' y1=\'9\' x2=\'15\' y2=\'15\'/%3E%3C/svg%3E") no-repeat center center / contain; border: none; } ' +
			'.afx-item-row { display: flex; align-items: center; justify-content: space-between; width: 100%; gap: 6px; } ' +
			'.afx-item-row .rich-text { flex-grow: 1; } ' +
			'.afx-delete-btn { opacity: 0.3; transition: opacity 0.2s ease; cursor: pointer; color: #cc0000; border: none; background: transparent; padding: 2px 6px; font-size: 14px; } ' +
			'.afx-item-row:hover .afx-delete-btn { opacity: 1; } ' +
			'.afx-add-btn { margin-top: 10px; display: inline-flex; align-items: center; gap: 4px; font-size: 13px; font-weight: 500; }';

		// Thumbs Up SVG
		var thumbsUpIcon = el( 'svg', { width: '20', height: '20', viewBox: '0 0 24 24', fill: 'currentColor' },
			el( 'path', { d: 'M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.58 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-2z' } )
		);

		// Thumbs Down SVG
		var thumbsDownIcon = el( 'svg', { width: '20', height: '20', viewBox: '0 0 24 24', fill: 'currentColor' },
			el( 'path', { d: 'M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v2c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z' } )
		);

		function updateProsItem( index, newText ) {
			var updated = prosItems.map( function( item, i ) {
				if ( i === index ) {
					return { content: newText };
				}
				return item;
			} );
			setAttributes( {
				prosListItems: updated,
				prosListContent: itemsToHtmlString( updated )
			} );
		}

		function addProsItem() {
			var updated = prosItems.concat( [ { content: '' } ] );
			setAttributes( {
				prosListItems: updated,
				prosListContent: itemsToHtmlString( updated )
			} );
		}

		function removeProsItem( index ) {
			var updated = prosItems.filter( function( _, i ) {
				return i !== index;
			} );
			setAttributes( {
				prosListItems: updated,
				prosListContent: itemsToHtmlString( updated )
			} );
		}

		function updateConsItem( index, newText ) {
			var updated = consItems.map( function( item, i ) {
				if ( i === index ) {
					return { content: newText };
				}
				return item;
			} );
			setAttributes( {
				consListItems: updated,
				consListContent: itemsToHtmlString( updated )
			} );
		}

		function addConsItem() {
			var updated = consItems.concat( [ { content: '' } ] );
			setAttributes( {
				consListItems: updated,
				consListContent: itemsToHtmlString( updated )
			} );
		}

		function removeConsItem( index ) {
			var updated = consItems.filter( function( _, i ) {
				return i !== index;
			} );
			setAttributes( {
				consListItems: updated,
				consListContent: itemsToHtmlString( updated )
			} );
		}

		return el(
			Fragment,
			{},
			el(
				InspectorControls,
				{},
				// Layout Panel
				el(
					PanelBody,
					{ title: __( 'Layout Settings', 'pros-and-cons' ), initialOpen: true },
					el( SelectControl, {
						label: __( 'Choose Layout', 'pros-and-cons' ),
						value: layoutStyle,
						options: [
							{ label: __( 'Layout One (Classic Side-by-Side)', 'pros-and-cons' ), value: 'layout-type-1' },
							{ label: __( 'Layout Two (Boxed Separated)', 'pros-and-cons' ), value: 'layout-type-2' },
							{ label: __( 'Layout Three (Badge Style)', 'pros-and-cons' ), value: 'layout-type-3' },
							{ label: __( 'Layout Four (Modern Clean)', 'pros-and-cons' ), value: 'layout-type-4' }
						],
						onChange: function( val ) { setAttributes( { layoutStyle: val } ); }
					} ),
					el( ToggleControl, {
						label: __( 'Show Pros Header Icon', 'pros-and-cons' ),
						checked: prosIconStatus,
						onChange: function( val ) { setAttributes( { prosIconStatus: val } ); }
					} ),
					el( ToggleControl, {
						label: __( 'Show Cons Header Icon', 'pros-and-cons' ),
						checked: consIconStatus,
						onChange: function( val ) { setAttributes( { consIconStatus: val } ); }
					} )
				),
				// Colors Panel
				el(
					PanelBody,
					{ title: __( 'Colors & Backgrounds', 'pros-and-cons' ), initialOpen: false },
					el( 'p', { style: { fontWeight: 'bold' } }, __( 'Pros Header Background Color', 'pros-and-cons' ) ),
					el( ColorPalette, {
						value: prosBgColor,
						onChange: function( val ) { setAttributes( { prosBgColor: val } ); }
					} ),
					el( 'p', { style: { fontWeight: 'bold' } }, __( 'Pros Header Text Color', 'pros-and-cons' ) ),
					el( ColorPalette, {
						value: prosTextColor,
						onChange: function( val ) { setAttributes( { prosTextColor: val } ); }
					} ),
					el( 'p', { style: { fontWeight: 'bold' } }, __( 'Pros List Background Color', 'pros-and-cons' ) ),
					el( ColorPalette, {
						value: prosListBgColor,
						onChange: function( val ) { setAttributes( { prosListBgColor: val } ); }
					} ),
					el( 'p', { style: { fontWeight: 'bold' } }, __( 'Pros Check Icon Color', 'pros-and-cons' ) ),
					el( ColorPalette, {
						value: prosIconColor,
						onChange: function( val ) { setAttributes( { prosIconColor: val } ); }
					} ),
					el( 'hr', {} ),
					el( 'p', { style: { fontWeight: 'bold' } }, __( 'Cons Header Background Color', 'pros-and-cons' ) ),
					el( ColorPalette, {
						value: consBgColor,
						onChange: function( val ) { setAttributes( { consBgColor: val } ); }
					} ),
					el( 'p', { style: { fontWeight: 'bold' } }, __( 'Cons Header Text Color', 'pros-and-cons' ) ),
					el( ColorPalette, {
						value: consTextColor,
						onChange: function( val ) { setAttributes( { consTextColor: val } ); }
					} ),
					el( 'p', { style: { fontWeight: 'bold' } }, __( 'Cons List Background Color', 'pros-and-cons' ) ),
					el( ColorPalette, {
						value: consListBgColor,
						onChange: function( val ) { setAttributes( { consListBgColor: val } ); }
					} ),
					el( 'p', { style: { fontWeight: 'bold' } }, __( 'Cons Cross Icon Color', 'pros-and-cons' ) ),
					el( ColorPalette, {
						value: consIconColor,
						onChange: function( val ) { setAttributes( { consIconColor: val } ); }
					} )
				)
			),
			// Direct Interactive Block Canvas
			el(
				'div',
				{
					id: 'affiliatex-pros-cons-style-' + blockId,
					className: 'affx-pros-cons-wrapper'
				},
				el( 'style', {}, dynamicCss ),
				el(
					'div',
					{ className: 'affx-pros-cons-inner-wrapper ' + layoutStyle },
					// Pros Column (Left)
					el(
						'div',
						{ className: 'affx-pros-inner' },
						el(
							'div',
							{ className: 'pros-icon-title-wrap' },
							el(
								'div',
								{ className: 'affiliatex-block-pros' },
								prosIconStatus && thumbsUpIcon,
								el( 'p', { className: 'affiliatex-title' }, __( 'Pros', 'pros-and-cons' ) )
							)
						),
						el(
							'div',
							{ className: 'affiliatex-pros' },
							el(
								'ul',
								{ className: 'affiliatex-list affiliatex-list-type-unordered afx-icon-list pros-list-wrapper' },
								prosItems.map( function( item, index ) {
									return el(
										'li',
										{ key: 'pros-item-' + index },
										el(
											'div',
											{ className: 'afx-item-row' },
											el( RichText, {
												key: 'rt-pros-' + index,
												tagName: 'span',
												value: item.content || '',
												onChange: function( val ) {
													updateProsItem( index, val );
												},
												placeholder: __( 'Type Pros item here...', 'pros-and-cons' ),
												ariaLabel: __( 'Pros Item', 'pros-and-cons' )
											} ),
											prosItems.length > 1 && el(
												Button,
												{
													className: 'afx-delete-btn',
													onClick: function() { removeProsItem( index ); },
													title: __( 'Remove item', 'pros-and-cons' )
												},
												'✕'
											)
										)
									);
								} )
							),
							el(
								Button,
								{
									variant: 'secondary',
									className: 'afx-add-btn',
									onClick: addProsItem
								},
								'+ ' + __( 'Add Pros Item', 'pros-and-cons' )
							)
						)
					),
					// Cons Column (Right)
					el(
						'div',
						{ className: 'affx-cons-inner' },
						el(
							'div',
							{ className: 'cons-icon-title-wrap' },
							el(
								'div',
								{ className: 'affiliatex-block-cons' },
								consIconStatus && thumbsDownIcon,
								el( 'p', { className: 'affiliatex-title' }, __( 'Cons', 'pros-and-cons' ) )
							)
						),
						el(
							'div',
							{ className: 'affiliatex-cons' },
							el(
								'ul',
								{ className: 'affiliatex-list affiliatex-list-type-unordered afx-icon-list cons-list-wrapper' },
								consItems.map( function( indexItem, index ) {
									return el(
										'li',
										{ key: 'cons-item-' + index },
										el(
											'div',
											{ className: 'afx-item-row' },
											el( RichText, {
												key: 'rt-cons-' + index,
												tagName: 'span',
												value: indexItem.content || '',
												onChange: function( val ) {
													updateConsItem( index, val );
												},
												placeholder: __( 'Type Cons item here...', 'pros-and-cons' ),
												ariaLabel: __( 'Cons Item', 'pros-and-cons' )
											} ),
											consItems.length > 1 && el(
												Button,
												{
													className: 'afx-delete-btn',
													onClick: function() { removeConsItem( index ); },
													title: __( 'Remove item', 'pros-and-cons' )
												},
												'✕'
											)
										)
									);
								} )
							),
							el(
								Button,
								{
									variant: 'secondary',
									className: 'afx-add-btn',
									onClick: addConsItem
								},
								'+ ' + __( 'Add Cons Item', 'pros-and-cons' )
							)
						)
					)
				)
			)
		);
	}

	var blockConfig = {
		title: __( 'Pros and Cons Block', 'pros-and-cons' ),
		description: __( 'Present product advantages and drawbacks with an easy-to-read layout.', 'pros-and-cons' ),
		icon: 'thumbs-up',
		category: 'widgets',
		keywords: [
			__( 'Pros and Cons', 'pros-and-cons' ),
			__( 'Pros', 'pros-and-cons' ),
			__( 'Cons', 'pros-and-cons' ),
			__( 'Review', 'pros-and-cons' )
		],
		supports: {
			html: false,
			align: [ 'wide', 'full' ]
		},
		edit: EditComponent,
		save: function() {
			return null;
		}
	};

	registerBlockType( 'prosandcons/pros-and-cons', blockConfig );

} )( window.wp );
