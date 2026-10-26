( function( wp ) {
	var registerBlockType = wp.blocks.registerBlockType;
	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var __ = wp.i18n.__;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var useBlockProps = wp.blockEditor.useBlockProps;
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
		{ content: '' }
	];

	var defaultConsItems = [
		{ content: '' }
	];

	function cleanItemContent( str ) {
		if ( typeof str !== 'string' ) return '';
		var cleaned = str
			.replace( /\\?u003c/gi, '<' )
			.replace( /\\?u003e/gi, '>' )
			.replace( /\\?u0022/gi, '"' )
			.replace( /\\?u0026/gi, '&' )
			.replace( /\\?u0027/gi, "'" )
			.replace( /\\?u002f/gi, '/' )
			.replace( /\s*data-rich-text-line-break=(["']?)true\1/gi, '' )
			.replace( /(?:\s*<br\s*\/?>\s*)+$/gi, '' )
			.replace( /^(?:\s*<br\s*\/?>\s*)+/gi, '' );
		return cleaned.trim();
	}

	function extractContentFromItem( item ) {
		if ( item === null || item === undefined ) return '';
		if ( typeof item === 'string' ) return cleanItemContent( item );
		if ( typeof item === 'number' || typeof item === 'boolean' ) return String( item );
		if ( typeof item === 'object' ) {
			if ( typeof item.content === 'string' ) return cleanItemContent( item.content );
			if ( typeof item.text === 'string' ) return cleanItemContent( item.text );
			if ( typeof item.list === 'string' ) return cleanItemContent( item.list );
			if ( item.props && item.props.children ) {
				if ( Array.isArray( item.props.children ) ) {
					return cleanItemContent( item.props.children.map( extractContentFromItem ).join( '' ) );
				}
				return cleanItemContent( extractContentFromItem( item.props.children ) );
			}
			if ( Array.isArray( item.content ) ) {
				return cleanItemContent( item.content.map( extractContentFromItem ).join( '' ) );
			}
		}
		return '';
	}

	function parseHtmlToListItems( htmlString ) {
		if ( ! htmlString || typeof htmlString !== 'string' ) return [];
		var matches = htmlString.match( /<li[^>]*>(.*?)<\/li>/gi );
		if ( ! matches ) return [];
		return matches.map( function( item ) {
			return { content: cleanItemContent( item.replace( /<\/?li[^>]*>/gi, '' ) ) };
		} );
	}

	function itemsToHtmlString( items ) {
		if ( ! Array.isArray( items ) ) return '';
		return items.map( function( item ) {
			var text = cleanItemContent( item && item.content ? item.content : '' );
			return '<li>' + text + '</li>';
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

		var blockProps = useBlockProps ? useBlockProps( {
			id: 'proscons-pros-cons-style-' + blockId,
			className: 'proscons-pros-cons-wrapper'
		} ) : {
			id: 'proscons-pros-cons-style-' + blockId,
			className: 'proscons-pros-cons-wrapper'
		};

		// Resolve Pros items array strictly with persistent unique IDs
		var prosItems;
		if ( Array.isArray( attributes.prosListItems ) && attributes.prosListItems.length > 0 ) {
			prosItems = attributes.prosListItems.map( function( item ) {
				if ( typeof item === 'object' && item !== null ) {
					return {
						id: item.id || generateUniqueId(),
						content: extractContentFromItem( item )
					};
				}
				return {
					id: generateUniqueId(),
					content: extractContentFromItem( item )
				};
			} );
		} else if ( typeof attributes.prosListContent === 'string' && attributes.prosListContent.trim() !== '' ) {
			prosItems = parseHtmlToListItems( attributes.prosListContent ).map( function( item ) {
				return { id: generateUniqueId(), content: item.content };
			} );
		} else if ( typeof attributes.prosContent === 'string' && attributes.prosContent.trim() !== '' ) {
			prosItems = parseHtmlToListItems( attributes.prosContent );
			if ( prosItems.length === 0 ) {
				prosItems = [ { id: generateUniqueId(), content: cleanItemContent( attributes.prosContent ) } ];
			}
		} else {
			prosItems = [ { id: generateUniqueId(), content: '' } ];
		}

		// Resolve Cons items array strictly with persistent unique IDs
		var consItems;
		if ( Array.isArray( attributes.consListItems ) && attributes.consListItems.length > 0 ) {
			consItems = attributes.consListItems.map( function( item ) {
				if ( typeof item === 'object' && item !== null ) {
					return {
						id: item.id || generateUniqueId(),
						content: extractContentFromItem( item )
					};
				}
				return {
					id: generateUniqueId(),
					content: extractContentFromItem( item )
				};
			} );
		} else if ( typeof attributes.consListContent === 'string' && attributes.consListContent.trim() !== '' ) {
			consItems = parseHtmlToListItems( attributes.consListContent ).map( function( item ) {
				return { id: generateUniqueId(), content: item.content };
			} );
		} else if ( typeof attributes.consContent === 'string' && attributes.consContent.trim() !== '' ) {
			consItems = parseHtmlToListItems( attributes.consContent );
			if ( consItems.length === 0 ) {
				consItems = [ { id: generateUniqueId(), content: cleanItemContent( attributes.consContent ) } ];
			}
		} else {
			consItems = [ { id: generateUniqueId(), content: '' } ];
		}

		var dynamicCss =
			'#proscons-pros-cons-style-' + blockId + ' { width: 100%; max-width: 100%; margin-left: 0; margin-right: 0; box-sizing: border-box; } ' +
			'#proscons-pros-cons-style-' + blockId + ' .proscons-block-pros { background: ' + prosBgColor + '; color: ' + prosTextColor + '; padding: 14px 20px; border-top-left-radius: 4px; border-top-right-radius: 4px; display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 20px; } ' +
			'#proscons-pros-cons-style-' + blockId + ' .proscons-block-cons { background: ' + consBgColor + '; color: ' + consTextColor + '; padding: 14px 20px; border-top-left-radius: 4px; border-top-right-radius: 4px; display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 20px; } ' +
			'#proscons-pros-cons-style-' + blockId + ' .proscons-block-pros .proscons-title { color: ' + prosTextColor + '; margin: 0; font-size: 20px; font-weight: 700; line-height: 1.2; } ' +
			'#proscons-pros-cons-style-' + blockId + ' .proscons-block-cons .proscons-title { color: ' + consTextColor + '; margin: 0; font-size: 20px; font-weight: 700; line-height: 1.2; } ' +
			'#proscons-pros-cons-style-' + blockId + ' .proscons-pros-inner .proscons-pros { background: ' + prosListBgColor + '; padding: 18px 22px; min-height: 60px; border-bottom-left-radius: 4px; } ' +
			'#proscons-pros-cons-style-' + blockId + ' .proscons-cons-inner .proscons-cons { background: ' + consListBgColor + '; padding: 18px 22px; min-height: 60px; border-bottom-right-radius: 4px; } ' +
			'#proscons-pros-cons-style-' + blockId + ' .proscons-pros ul { list-style: none; margin: 0; padding: 0; width: 100%; } ' +
			'#proscons-pros-cons-style-' + blockId + ' .proscons-cons ul { list-style: none; margin: 0; padding: 0; width: 100%; } ' +
			'#proscons-pros-cons-style-' + blockId + ' .proscons-pros ul li { list-style: none; position: relative; padding-left: 28px; margin-bottom: 10px; color: #292929; font-size: 16px; line-height: 1.5; display: block; } ' +
			'#proscons-pros-cons-style-' + blockId + ' .proscons-cons ul li { list-style: none; position: relative; padding-left: 28px; margin-bottom: 10px; color: #292929; font-size: 16px; line-height: 1.5; display: block; } ' +
			'#proscons-pros-cons-style-' + blockId + ' .proscons-pros ul li span, #proscons-pros-cons-style-' + blockId + ' .proscons-cons ul li span { font-size: 16px; line-height: 1.5; color: #292929; display: inline; } ' +
			'#proscons-pros-cons-style-' + blockId + ' .proscons-pros ul li a, #proscons-pros-cons-style-' + blockId + ' .proscons-cons ul li a { font-size: 16px; line-height: 1.5; color: #0284c7; text-decoration: underline; display: inline; } ' +
			'#proscons-pros-cons-style-' + blockId + ' .proscons-pros ul li:last-child { margin-bottom: 0; } ' +
			'#proscons-pros-cons-style-' + blockId + ' .proscons-cons ul li:last-child { margin-bottom: 0; } ' +
			'#proscons-pros-cons-style-' + blockId + ' .proscons-pros ul li::before { content: ""; position: absolute; left: 0; top: 8px; width: 18px; height: 18px; border-radius: 50%; background: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%2324B644\' stroke-width=\'2.2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Ccircle cx=\'12\' cy=\'12\' r=\'10\'/%3E%3Cpolyline points=\'8 12 11 15 16 9\'/%3E%3C/svg%3E") no-repeat center center / contain; border: none; box-sizing: border-box; } ' +
			'#proscons-pros-cons-style-' + blockId + ' .proscons-cons ul li::before { content: ""; position: absolute; left: 0; top: 8px; width: 18px; height: 18px; border-radius: 50%; background: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23F13A3A\' stroke-width=\'2.2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3E%3Ccircle cx=\'12\' cy=\'12\' r=\'10\'/%3E%3Cline x1=\'15\' y1=\'9\' x2=\'9\' y2=\'15\'/%3E%3Cline x1=\'9\' y1=\'9\' x2=\'15\' y2=\'15\'/%3E%3C/svg%3E") no-repeat center center / contain; border: none; box-sizing: border-box; } ' +
			'.proscons-item-row { display: flex; align-items: flex-start; justify-content: space-between; width: 100%; gap: 6px; } ' +
			'.proscons-item-row .rich-text { flex-grow: 1; outline: none; } ' +
			'.proscons-delete-btn { opacity: 0; transition: opacity 0.2s ease; cursor: pointer; color: #94a3b8; border: none; background: transparent; padding: 0 4px; font-size: 13px; line-height: 1.5; } ' +
			'.proscons-item-row:hover .proscons-delete-btn { opacity: 0.7; } ' +
			'.proscons-item-row:hover .proscons-delete-btn:hover { opacity: 1; color: #ef4444; }';

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
					return { id: item.id, content: cleanItemContent( newText ) };
				}
				return item;
			} );
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

	function getCaretOffset( containerEl ) {
		if ( ! containerEl ) return -1;
		var editableNode = containerEl.getAttribute( 'contenteditable' ) === 'true'
			? containerEl
			: containerEl.querySelector( '[contenteditable="true"]' );

		if ( ! editableNode ) return -1;

		var sel = window.getSelection();
		if ( ! sel || sel.rangeCount === 0 ) return -1;

		try {
			var range = sel.getRangeAt( 0 );
			if ( ! editableNode.contains( range.startContainer ) ) return -1;

			var preCaretRange = range.cloneRange();
			preCaretRange.selectNodeContents( editableNode );
			preCaretRange.setEnd( range.startContainer, range.startOffset );
			return preCaretRange.toString().length;
		} catch ( err ) {
			return -1;
		}
	}

	function focusRichTextItem( targetId ) {
		function tryFocus() {
			var container = document.getElementById( targetId );
			if ( ! container ) return false;

			var editable = container.getAttribute( 'contenteditable' ) === 'true'
				? container
				: container.querySelector( '[contenteditable="true"]' ) || container;

			if ( editable && typeof editable.focus === 'function' ) {
				try {
					editable.focus();
					var range = document.createRange();
					var sel = window.getSelection();
					range.selectNodeContents( editable );
					range.collapse( true );
					sel.removeAllRanges();
					sel.addRange( range );

					var focusEvt = new Event( 'focus', { bubbles: true } );
					editable.dispatchEvent( focusEvt );
				} catch ( err ) {}
				return true;
			}
			return false;
		}

		if ( document.activeElement && typeof document.activeElement.blur === 'function' ) {
			document.activeElement.blur();
		}

		if ( ! tryFocus() ) {
			if ( typeof requestAnimationFrame === 'function' ) {
				requestAnimationFrame( function() {
					if ( ! tryFocus() ) {
						setTimeout( tryFocus, 20 );
						setTimeout( tryFocus, 60 );
						setTimeout( tryFocus, 120 );
					}
				} );
			} else {
				setTimeout( tryFocus, 30 );
			}
		} else {
			setTimeout( tryFocus, 30 );
			setTimeout( tryFocus, 80 );
		}
	}

		function handleProsKeyDown( e, index ) {
			if ( e.key === 'Enter' && ! e.shiftKey ) {
				e.preventDefault();
				var activeContainer = document.getElementById( 'rt-pros-' + prosItems[index].id );
				var currentText = prosItems[index] && prosItems[index].content ? prosItems[index].content : '';
				var caretPos = getCaretOffset( activeContainer );

				var beforeText = currentText;
				var afterText = '';

				if ( caretPos > 0 && caretPos < currentText.length ) {
					beforeText = currentText.slice( 0, caretPos );
					afterText = currentText.slice( caretPos );
				}

				var newId = generateUniqueId();
				var updated = prosItems.slice();
				updated[index] = { id: prosItems[index].id, content: cleanItemContent( beforeText ) };
				updated.splice( index + 1, 0, { id: newId, content: cleanItemContent( afterText ) } );

				if ( document.activeElement && typeof document.activeElement.blur === 'function' ) {
					document.activeElement.blur();
				}

				setAttributes( {
					prosListItems: updated,
					prosListContent: itemsToHtmlString( updated )
				} );

				focusRichTextItem( 'rt-pros-' + newId );
				return;
			}

			if ( e.key === 'Tab' ) {
				e.preventDefault();
				var updatedCons = consItems.slice();
				var targetIndex = index;
				var targetId;

				while ( updatedCons.length <= targetIndex ) {
					updatedCons.push( { id: generateUniqueId(), content: '' } );
				}

				targetId = updatedCons[targetIndex].id;

				if ( document.activeElement && typeof document.activeElement.blur === 'function' ) {
					document.activeElement.blur();
				}

				if ( updatedCons.length !== consItems.length ) {
					setAttributes( {
						consListItems: updatedCons,
						consListContent: itemsToHtmlString( updatedCons )
					} );
				}

				focusRichTextItem( 'rt-cons-' + targetId );
				return;
			}

			if ( e.key === 'Backspace' ) {
				var currentContent = cleanItemContent( prosItems[index] ? prosItems[index].content : '' );
				if ( currentContent === '' && prosItems.length > 1 ) {
					e.preventDefault();
					removeProsItem( index );
					var prevIndex = Math.max( 0, index - 1 );
					var prevId = prosItems[prevIndex] ? prosItems[prevIndex].id : '';
					if ( prevId ) {
						focusRichTextItem( 'rt-pros-' + prevId );
					}
					return;
				}
			}
		}

		function updateConsItem( index, newText ) {
			var updated = consItems.map( function( item, i ) {
				if ( i === index ) {
					return { id: item.id, content: cleanItemContent( newText ) };
				}
				return item;
			} );
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

		function handleConsKeyDown( e, index ) {
			if ( e.key === 'Enter' && ! e.shiftKey ) {
				e.preventDefault();
				var activeContainer = document.getElementById( 'rt-cons-' + consItems[index].id );
				var currentText = consItems[index] && consItems[index].content ? consItems[index].content : '';
				var caretPos = getCaretOffset( activeContainer );

				var beforeText = currentText;
				var afterText = '';

				if ( caretPos > 0 && caretPos < currentText.length ) {
					beforeText = currentText.slice( 0, caretPos );
					afterText = currentText.slice( caretPos );
				}

				var newId = generateUniqueId();
				var updated = consItems.slice();
				updated[index] = { id: consItems[index].id, content: cleanItemContent( beforeText ) };
				updated.splice( index + 1, 0, { id: newId, content: cleanItemContent( afterText ) } );

				if ( document.activeElement && typeof document.activeElement.blur === 'function' ) {
					document.activeElement.blur();
				}

				setAttributes( {
					consListItems: updated,
					consListContent: itemsToHtmlString( updated )
				} );

				focusRichTextItem( 'rt-cons-' + newId );
				return;
			}

			if ( e.key === 'Tab' ) {
				e.preventDefault();
				var updatedPros = prosItems.slice();
				var targetIndex = index;
				var targetId;

				while ( updatedPros.length <= targetIndex ) {
					updatedPros.push( { id: generateUniqueId(), content: '' } );
				}

				targetId = updatedPros[targetIndex].id;

				if ( document.activeElement && typeof document.activeElement.blur === 'function' ) {
					document.activeElement.blur();
				}

				if ( updatedPros.length !== prosItems.length ) {
					setAttributes( {
						prosListItems: updatedPros,
						prosListContent: itemsToHtmlString( updatedPros )
					} );
				}

				focusRichTextItem( 'rt-pros-' + targetId );
				return;
			}

			if ( e.key === 'Backspace' ) {
				var currentContent = cleanItemContent( consItems[index] ? consItems[index].content : '' );
				if ( currentContent === '' && consItems.length > 1 ) {
					e.preventDefault();
					removeConsItem( index );
					var prevIndex = Math.max( 0, index - 1 );
					var prevId = consItems[prevIndex] ? consItems[prevIndex].id : '';
					if ( prevId ) {
						focusRichTextItem( 'rt-cons-' + prevId );
					}
					return;
				}
			}
		}

		function parsePastedProsAndCons( pastedText, pastedHtml ) {
			var result = {
				isStructured: false,
				isMultiLine: false,
				pros: [],
				cons: [],
				lines: []
			};

			pastedText = pastedText ? pastedText.trim() : '';
			pastedHtml = pastedHtml ? pastedHtml.trim() : '';

			if ( ! pastedText && ! pastedHtml ) {
				return result;
			}

			function cleanBullet( str ) {
				if ( ! str || typeof str !== 'string' ) return '';
				return str
					.replace( /^[\s\-*•+–—\d\.\)\(\[\]✓✔✕✖❌✅]+\s*/i, '' )
					.replace( /\\?u003c/gi, '<' )
					.replace( /\\?u003e/gi, '>' )
					.replace( /\\?u0022/gi, '"' )
					.replace( /\\?u0027/gi, "'" )
					.trim();
			}

			// 1. Check HTML <table> structure (pasted directly from ChatGPT UI)
			if ( pastedHtml && ( pastedHtml.indexOf( '<table' ) !== -1 || pastedHtml.indexOf( '<tr' ) !== -1 ) ) {
				try {
					var parser = new DOMParser();
					var doc = parser.parseFromString( pastedHtml, 'text/html' );
					var rows = doc.querySelectorAll( 'tr' );

					if ( rows && rows.length > 0 ) {
						var prosCol = 0;
						var consCol = 1;

						var headerCells = rows[0].querySelectorAll( 'th, td' );
						if ( headerCells.length >= 2 ) {
							var h0 = headerCells[0].textContent.toLowerCase();
							var h1 = headerCells[1].textContent.toLowerCase();
							if ( h0.indexOf( 'con' ) !== -1 && h1.indexOf( 'pro' ) !== -1 ) {
								prosCol = 1;
								consCol = 0;
							}
						}

						var startRow = ( headerCells.length >= 2 && ( headerCells[0].textContent.toLowerCase().indexOf( 'pro' ) !== -1 || headerCells[0].textContent.toLowerCase().indexOf( 'con' ) !== -1 ) ) ? 1 : 0;

						for ( var r = startRow; r < rows.length; r++ ) {
							var cells = rows[r].querySelectorAll( 'td, th' );
							if ( cells.length >= 2 ) {
								var pText = cleanBullet( cells[prosCol].textContent );
								var cText = cleanBullet( cells[consCol].textContent );
								if ( pText ) result.pros.push( pText );
								if ( cText ) result.cons.push( cText );
							}
						}

						if ( result.pros.length > 0 || result.cons.length > 0 ) {
							result.isStructured = true;
							return result;
						}
					}
				} catch ( err ) {}
			}

			// 2. Check Pipe Table (Markdown pipe table: | Pros | Cons |)
			var lines = pastedText.split( /\r?\n/ );
			var pipeRows = [];
			for ( var i = 0; i < lines.length; i++ ) {
				var line = lines[i].trim();
				if ( line.indexOf( '|' ) !== -1 ) {
					var parts = line.split( '|' ).map( function( p ) { return p.trim(); } ).filter( function( p, idx, arr ) {
						return idx > 0 && idx < arr.length - 1;
					} );

					if ( parts.length >= 2 ) {
						if ( /^[:\-\s|]+$/.test( line ) ) {
							continue;
						}
						pipeRows.push( parts );
					}
				}
			}

			if ( pipeRows.length > 0 ) {
				var pCol = 0;
				var cCol = 1;
				var firstRow0 = pipeRows[0][0].toLowerCase();
				var firstRow1 = pipeRows[0][1].toLowerCase();

				var startIdx = 0;
				if ( firstRow0.indexOf( 'pro' ) !== -1 || firstRow0.indexOf( 'con' ) !== -1 ) {
					if ( firstRow0.indexOf( 'con' ) !== -1 && firstRow1.indexOf( 'pro' ) !== -1 ) {
						pCol = 1;
						cCol = 0;
					}
					startIdx = 1;
				}

				for ( var k = startIdx; k < pipeRows.length; k++ ) {
					var proVal = cleanBullet( pipeRows[k][pCol] );
					var conVal = cleanBullet( pipeRows[k][cCol] );
					if ( proVal ) result.pros.push( proVal );
					if ( conVal ) result.cons.push( conVal );
				}

				if ( result.pros.length > 0 || result.cons.length > 0 ) {
					result.isStructured = true;
					return result;
				}
			}

			// 3. Check Tab-Separated Table text
			var tabRows = [];
			for ( var t = 0; t < lines.length; t++ ) {
				var tLine = lines[t].trim();
				if ( tLine.indexOf( '\t' ) !== -1 ) {
					var tParts = tLine.split( '\t' ).map( function( p ) { return p.trim(); } );
					if ( tParts.length >= 2 ) {
						tabRows.push( tParts );
					}
				}
			}

			if ( tabRows.length > 0 ) {
				var tpCol = 0;
				var tcCol = 1;
				var tHead0 = tabRows[0][0].toLowerCase();
				var tHead1 = tabRows[0][1].toLowerCase();
				var tStart = 0;

				if ( tHead0.indexOf( 'pro' ) !== -1 || tHead0.indexOf( 'con' ) !== -1 ) {
					if ( tHead0.indexOf( 'con' ) !== -1 && tHead1.indexOf( 'pro' ) !== -1 ) {
						tpCol = 1;
						tcCol = 0;
					}
					tStart = 1;
				}

				for ( var m = tStart; m < tabRows.length; m++ ) {
					var pV = cleanBullet( tabRows[m][tpCol] );
					var cV = cleanBullet( tabRows[m][tcCol] );
					if ( pV ) result.pros.push( pV );
					if ( cV ) result.cons.push( cV );
				}

				if ( result.pros.length > 0 || result.cons.length > 0 ) {
					result.isStructured = true;
					return result;
				}
			}

			// 4. Check "Pros:" and "Cons:" Section Headers
			var currentSection = null;
			for ( var s = 0; s < lines.length; s++ ) {
				var sLine = lines[s].trim();
				if ( ! sLine ) continue;

				var lower = sLine.toLowerCase().replace( /[^a-z]/g, '' );
				if ( lower === 'pros' || lower === 'pro' || lower === 'advantages' ) {
					currentSection = 'pros';
					continue;
				} else if ( lower === 'cons' || lower === 'con' || lower === 'disadvantages' || lower === 'drawbacks' ) {
					currentSection = 'cons';
					continue;
				}

				var cleanedItem = cleanBullet( sLine );
				if ( cleanedItem ) {
					if ( currentSection === 'pros' ) {
						result.pros.push( cleanedItem );
					} else if ( currentSection === 'cons' ) {
						result.cons.push( cleanedItem );
					}
				}
			}

			if ( result.pros.length > 0 || result.cons.length > 0 ) {
				result.isStructured = true;
				return result;
			}

			// 5. Fallback: Multi-line plain text paste
			var multiLines = [];
			for ( var j = 0; j < lines.length; j++ ) {
				var itemVal = cleanBullet( lines[j] );
				if ( itemVal ) {
					multiLines.push( itemVal );
				}
			}

			if ( multiLines.length > 1 ) {
				result.isMultiLine = true;
				result.lines = multiLines;
			}

			return result;
		}

		function handleProsPaste( e, index ) {
			var clipboardData = e.clipboardData || ( e.originalEvent && e.originalEvent.clipboardData ) || window.clipboardData;
			if ( ! clipboardData ) return;

			var pastedText = clipboardData.getData( 'text/plain' ) || '';
			var pastedHtml = clipboardData.getData( 'text/html' ) || '';

			var parsed = parsePastedProsAndCons( pastedText, pastedHtml );

			if ( parsed.isStructured && ( parsed.pros.length > 0 || parsed.cons.length > 0 ) ) {
				e.preventDefault();
				if ( typeof e.stopPropagation === 'function' ) e.stopPropagation();

				var newPros = parsed.pros.map( function( text ) {
					return { id: generateUniqueId(), content: cleanItemContent( text ) };
				} );

				var newCons = parsed.cons.map( function( text ) {
					return { id: generateUniqueId(), content: cleanItemContent( text ) };
				} );

				setAttributes( {
					prosListItems: newPros.length > 0 ? newPros : prosItems,
					prosListContent: itemsToHtmlString( newPros.length > 0 ? newPros : prosItems ),
					consListItems: newCons.length > 0 ? newCons : consItems,
					consListContent: itemsToHtmlString( newCons.length > 0 ? newCons : consItems )
				} );
				return;
			}

			if ( parsed.isMultiLine && parsed.lines.length > 1 ) {
				e.preventDefault();
				if ( typeof e.stopPropagation === 'function' ) e.stopPropagation();

				var updatedPros = prosItems.slice();
				updatedPros[index] = { id: prosItems[index].id, content: cleanItemContent( parsed.lines[0] ) };

				for ( var p = 1; p < parsed.lines.length; p++ ) {
					updatedPros.splice( index + p, 0, { id: generateUniqueId(), content: cleanItemContent( parsed.lines[p] ) } );
				}

				setAttributes( {
					prosListItems: updatedPros,
					prosListContent: itemsToHtmlString( updatedPros )
				} );
				return;
			}
		}

		function handleConsPaste( e, index ) {
			var clipboardData = e.clipboardData || ( e.originalEvent && e.originalEvent.clipboardData ) || window.clipboardData;
			if ( ! clipboardData ) return;

			var pastedText = clipboardData.getData( 'text/plain' ) || '';
			var pastedHtml = clipboardData.getData( 'text/html' ) || '';

			var parsed = parsePastedProsAndCons( pastedText, pastedHtml );

			if ( parsed.isStructured && ( parsed.pros.length > 0 || parsed.cons.length > 0 ) ) {
				e.preventDefault();
				if ( typeof e.stopPropagation === 'function' ) e.stopPropagation();

				var newPros = parsed.pros.map( function( text ) {
					return { id: generateUniqueId(), content: cleanItemContent( text ) };
				} );

				var newCons = parsed.cons.map( function( text ) {
					return { id: generateUniqueId(), content: cleanItemContent( text ) };
				} );

				setAttributes( {
					prosListItems: newPros.length > 0 ? newPros : prosItems,
					prosListContent: itemsToHtmlString( newPros.length > 0 ? newPros : prosItems ),
					consListItems: newCons.length > 0 ? newCons : consItems,
					consListContent: itemsToHtmlString( newCons.length > 0 ? newCons : consItems )
				} );
				return;
			}

			if ( parsed.isMultiLine && parsed.lines.length > 1 ) {
				e.preventDefault();
				if ( typeof e.stopPropagation === 'function' ) e.stopPropagation();

				var updatedCons = consItems.slice();
				updatedCons[index] = { id: consItems[index].id, content: cleanItemContent( parsed.lines[0] ) };

				for ( var c = 1; c < parsed.lines.length; c++ ) {
					updatedCons.splice( index + c, 0, { id: generateUniqueId(), content: cleanItemContent( parsed.lines[c] ) } );
				}

				setAttributes( {
					consListItems: updatedCons,
					consListContent: itemsToHtmlString( updatedCons )
				} );
				return;
			}
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
				blockProps,
				el( 'style', {}, dynamicCss ),
				el(
					'div',
					{ className: 'proscons-pros-cons-inner-wrapper ' + layoutStyle },
					// Pros Column (Left)
					el(
						'div',
						{ className: 'proscons-pros-inner' },
						el(
							'div',
							{ className: 'pros-icon-title-wrap' },
							el(
								'div',
								{ className: 'proscons-block-pros' },
								prosIconStatus && thumbsUpIcon,
								el( 'p', { className: 'proscons-title' }, __( 'Pros', 'pros-and-cons' ) )
							)
						),
						el(
							'div',
							{ className: 'proscons-pros' },
							el(
								'ul',
								{ className: 'proscons-list proscons-list-type-unordered proscons-icon-list pros-list-wrapper' },
								prosItems.map( function( item, index ) {
									return el(
										'li',
										{ key: 'pros-item-' + item.id },
										el(
											'div',
											{ className: 'proscons-item-row' },
											el( RichText, {
												id: 'rt-pros-' + item.id,
												key: 'rt-pros-' + item.id,
												tagName: 'span',
												value: item.content || '',
												onChange: function( val ) {
													updateProsItem( index, val );
												},
												onKeyDown: function( e ) {
													handleProsKeyDown( e, index );
												},
												onPaste: function( e ) {
													handleProsPaste( e, index );
												},
												placeholder: __( 'Pros item...', 'pros-and-cons' ),
												ariaLabel: __( 'Pros Item', 'pros-and-cons' )
											} ),
											prosItems.length > 1 && el(
												Button,
												{
													className: 'proscons-delete-btn',
													onClick: function() { removeProsItem( index ); },
													title: __( 'Remove item', 'pros-and-cons' )
												},
												'✕'
											)
										)
									);
								} )
							)
						)
					),
					// Cons Column (Right)
					el(
						'div',
						{ className: 'proscons-cons-inner' },
						el(
							'div',
							{ className: 'cons-icon-title-wrap' },
							el(
								'div',
								{ className: 'proscons-block-cons' },
								consIconStatus && thumbsDownIcon,
								el( 'p', { className: 'proscons-title' }, __( 'Cons', 'pros-and-cons' ) )
							)
						),
						el(
							'div',
							{ className: 'proscons-cons' },
							el(
								'ul',
								{ className: 'proscons-list proscons-list-type-unordered proscons-icon-list cons-list-wrapper' },
								consItems.map( function( indexItem, index ) {
									return el(
										'li',
										{ key: 'cons-item-' + indexItem.id },
										el(
											'div',
											{ className: 'proscons-item-row' },
											el( RichText, {
												id: 'rt-cons-' + indexItem.id,
												key: 'rt-cons-' + indexItem.id,
												tagName: 'span',
												value: indexItem.content || '',
												onChange: function( val ) {
													updateConsItem( index, val );
												},
												onKeyDown: function( e ) {
													handleConsKeyDown( e, index );
												},
												onPaste: function( e ) {
													handleConsPaste( e, index );
												},
												placeholder: __( 'Cons item...', 'pros-and-cons' ),
												ariaLabel: __( 'Cons Item', 'pros-and-cons' )
											} ),
											consItems.length > 1 && el(
												Button,
												{
													className: 'proscons-delete-btn',
													onClick: function() { removeConsItem( index ); },
													title: __( 'Remove item', 'pros-and-cons' )
												},
												'✕'
											)
										)
									);
								} )
							)
						)
					)
				)
			)
		);
	}

	var blockConfig = {
		title: __( 'Pros and Cons Block Mag', 'pros-and-cons' ),
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
