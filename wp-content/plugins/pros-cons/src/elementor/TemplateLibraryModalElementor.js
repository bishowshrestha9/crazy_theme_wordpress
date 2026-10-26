import React, { useEffect, useState } from 'react';
import ReactDOM from 'react-dom';
import TemplateLibraryModalBase from '../blocks/ui-components/TemplateLibraryModalBase';
import SaveTemplateModal from '../blocks/ui-components/SaveTemplateModal';

const TemplateLibraryModalElementor = () => {
	const [ isOpen, setIsOpen ] = useState( false );
	const [ selectedWidget, setSelectedWidget ] = useState( '' );
	const [ editingWidget, setEditingWidget ] = useState( null );
	const [ editingWidgetType, setEditingWidgetType ] = useState( null );
	const [ affxWidgets, setAffxWidgets ] = useState( [] );

	const isPremium = window.ProsCons && window.ProsCons.proActive === 'true';

	const getProsConsWidgets = () => {
		if ( ! window.elementor || ! window.elementor.widgetsCache ) {
			return [];
		}

		const widgets = [];
		for ( const [ key, widget ] of Object.entries( window.elementor.widgetsCache ) ) {
			if ( key.startsWith( 'prosandcons-' ) ) {
				widgets.push( {
					name: key,
					title: widget.title || key,
					icon: widget.icon || 'eicon-plug'
				} );
			}
		}

		return widgets;
	};

	useEffect( () => {
		let allWidgets = getProsConsWidgets();

		if ( ! isPremium && window.proBlocks ) {
			const proWidgets = window.proBlocks.map( ( block ) => {
				const widgetName = block.name.replace( 'prosandcons/', 'prosandcons-' );
				return {
					name: widgetName,
					title: block.title,
					icon: 'eicon-plug',
					isPro: true
				};
			} );
			allWidgets = [ ...allWidgets, ...proWidgets ];
		}

		setAffxWidgets( allWidgets );
	}, [ isPremium ] );

	useEffect( () => {
		window.ProsConsElementorTemplateLibrary = {
			open: ( widgetType, widgetModel ) => {
				if ( widgetType ) {
					setSelectedWidget( widgetType );
					if ( widgetModel ) {
						setEditingWidgetType( widgetType );
					}
				}
				if ( widgetModel ) {
					setEditingWidget( widgetModel );
				}
				setIsOpen( true );
			},
			close: () => {
				setIsOpen( false );
				setEditingWidget( null );
				setEditingWidgetType( null );
			}
		};
	}, [] );

	const insertTemplate = ( template ) => {
		let templateData;
		try {
			templateData = typeof template.content === 'string' ? JSON.parse( template.content ) : template;
		} catch ( e ) {
			console.error( 'ProsCons: Failed to parse template content', e );
			return;
		}

		const widgetData = {
			id: generateUniqueId(),
			elType: 'widget',
			widgetType: `prosandcons-${ selectedWidget || getWidgetTypeFromTemplate( template ) }`,
			settings: templateData.settings || template.settings || {},
			elements: templateData.elements || template.elements || []
		};

		insertElementorWidget( widgetData );
	};

	const focusWidget = ( widgetContainer ) => {
		if ( ! widgetContainer || ! widgetContainer.view ) {
			return;
		}

		const waitForView = () => {
			if ( widgetContainer.view.$el && widgetContainer.view.$el[ 0 ] ) {
				widgetContainer.view.$el[ 0 ].scrollIntoView( {
					behavior: 'smooth',
					block: 'center',
					inline: 'nearest'
				} );

				setTimeout( () => {
					widgetContainer.view.model.trigger( 'request:edit' );
				}, 100 );
			} else {
				requestAnimationFrame( waitForView );
			}
		};

		requestAnimationFrame( waitForView );
	};

	const replaceTemplate = ( template ) => {
		if ( ! editingWidget ) {
			return;
		}

		const elementId = editingWidget.get( 'id' );
		const container = elementor.getContainer( elementId );

		if ( ! container ) {
			return;
		}

		let templateData;
		try {
			templateData = typeof template.content === 'string' ? JSON.parse( template.content ) : template;
		} catch ( e ) {
			console.error( 'ProsCons: Failed to parse template content', e );
			return;
		}

		const widgetType = editingWidget.get( 'widgetType' );
		const parent = container.parent;
		const index = container.view._index;

		const newWidgetData = {
			elType: 'widget',
			widgetType,
			settings: templateData.settings || template.settings || {}
		};

		$e.run( 'document/elements/delete', { container } );
		$e.run( 'document/elements/create', {
			container: parent,
			model: newWidgetData,
			options: { at: index }
		} );

		const waitForWidget = () => {
			const newWidget = parent.children[ index ];
			if ( newWidget ) {
				focusWidget( newWidget );
			} else {
				requestAnimationFrame( waitForWidget );
			}
		};

		requestAnimationFrame( waitForWidget );

		setEditingWidget( null );
		setEditingWidgetType( null );
	};

	const generateUniqueId = () => {
		return Math.random().toString( 36 ).substr( 2, 8 );
	};

	const getWidgetTypeFromTemplate = () => {
		return 'single-product'; // Default fallback
	};

	const insertElementorWidget = ( widgetData ) => {
		if ( ! window.elementor || ! window.$e ) {
			return;
		}

		const sectionData = {
			elType: 'section',
			elements: [
				{
					elType: 'column',
					elements: [ widgetData ]
				}
			]
		};

		const documentContainer = elementor.documents.getCurrent().container;
		const sectionIndex = documentContainer.children.length;

		$e.run( 'document/elements/create', {
			model: sectionData,
			container: documentContainer,
			options: { at: sectionIndex }
		} );

		const waitForWidget = () => {
			const newSection = documentContainer.children[ sectionIndex ];
			const column = newSection?.children?.[ 0 ];
			const widget = column?.children?.[ 0 ];

			if ( widget ) {
				focusWidget( widget );
			} else {
				requestAnimationFrame( waitForWidget );
			}
		};

		requestAnimationFrame( waitForWidget );
	};

	const handleClose = () => {
		setIsOpen( false );
		setEditingWidget( null );
		setEditingWidgetType( null );
	};

	return (
		<>
			<TemplateLibraryModalBase
				isOpen={ isOpen }
				onClose={ handleClose }
				widgets={ affxWidgets }
				selectedWidget={ selectedWidget }
				onWidgetSelect={ setSelectedWidget }
				ajaxAction="get_elementor_template_library"
				ajaxNonce={ window.ProsCons?.ajax_nonce || '' }
				onInsertTemplate={ insertTemplate }
				onReplaceTemplate={ replaceTemplate }
				editingWidgetType={ editingWidgetType }
				editorType="elementor"
			/>
			<SaveTemplateModal />
		</>
	);
};

export const initElementorTemplateLibrary = () => {
	let modalRoot = document.getElementById( 'affx-elementor-template-library-root' );

	if ( ! modalRoot ) {
		modalRoot = document.createElement( 'div' );
		modalRoot.id = 'affx-elementor-template-library-root';
		document.body.appendChild( modalRoot );
	}

	ReactDOM.createRoot( modalRoot ).render( <TemplateLibraryModalElementor /> );
};

export default TemplateLibraryModalElementor;
