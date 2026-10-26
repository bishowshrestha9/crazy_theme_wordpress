( function( wp ) {
    var el = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var RichText = wp.blockEditor ? wp.blockEditor.RichText : wp.editor.RichText;

    var containerStyle = {
        display: 'grid',
        gridTemplateColumns: '1fr 1fr',
        gap: '2rem',
        margin: '4rem 0 2rem 0',
        alignItems: 'stretch'
    };

    var boxStyle = {
        position: 'relative',
        border: '1px solid #e2e8f0',
        borderRadius: '12px',
        padding: '3rem 2rem 2rem 2rem',
        background: '#ffffff'
    };

    var iconBoxStyle = {
        position: 'absolute',
        top: '-40px',
        left: '50%',
        transform: 'translateX(-50%)',
        width: '80px',
        height: '80px',
        borderRadius: '50%',
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        boxShadow: '0 4px 10px rgba(0,0,0,0.1)'
    };

    var thumbsUpSVG = el('svg', { xmlns: 'http://www.w3.org/2000/svg', width: '40', height: '40', viewBox: '0 0 24 24', fill: 'white' },
        el('path', { d: 'M1 21h4V9H1v12zm22-11c0-1.1-.9-2-2-2h-6.31l.95-4.57.03-.32c0-.41-.17-.79-.44-1.06L14.17 1 7.59 7.59C7.22 7.95 7 8.45 7 9v10c0 1.1.9 2 2 2h9c.83 0 1.54-.5 1.84-1.22l3.02-7.05c.09-.23.14-.47.14-.73v-1z' })
    );

    var thumbsDownSVG = el('svg', { xmlns: 'http://www.w3.org/2000/svg', width: '40', height: '40', viewBox: '0 0 24 24', fill: 'white' },
        el('path', { d: 'M15 3H6c-.83 0-1.54.5-1.84 1.22l-3.02 7.05c-.09.23-.14.47-.14.73v1c0 1.1.9 2 2 2h6.31l-.95 4.57-.03.32c0 .41.17.79.44 1.06L9.83 23l6.59-6.59c.36-.36.58-.86.58-1.41V5c0-1.1-.9-2-2-2zm4 0v12h4V3h-4z' })
    );

    var injectedCSS = `
        .crazy-pros-cons-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 4rem;
            margin: 4rem 0 2rem 0;
            align-items: stretch;
        }
        @media(min-width: 768px) {
            .crazy-pros-cons-grid { grid-template-columns: 1fr 1fr; gap: 2rem; }
        }
        .crazy-pros-box, .crazy-cons-box {
            position: relative;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 3.5rem 1.5rem 1.5rem 1.5rem;
            background: #ffffff;
        }
        .crazy-pros-box {
            box-shadow: 0 14px 0 0 #3bda5c;
        }
        .crazy-cons-box {
            box-shadow: 0 14px 0 0 #f9844a;
        }
        .crazy-pros-cons-list {
            list-style: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        .crazy-pros-cons-list li {
            position: relative;
            padding-left: 1.8rem;
            margin-bottom: 1.25rem;
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.5;
        }
        .crazy-pros-box .crazy-pros-cons-list li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 3px;
            width: 18px;
            height: 18px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%2334d399" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M8 12l3 3 5-6"></path></svg>');
            background-repeat: no-repeat;
        }
        .crazy-cons-box .crazy-pros-cons-list li::before {
            content: '';
            position: absolute;
            left: 0;
            top: 3px;
            width: 18px;
            height: 18px;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="%23fb923c" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M15 9l-6 6"></path><path d="M9 9l6 6"></path></svg>');
            background-repeat: no-repeat;
        }
    `;

    registerBlockType( 'crazytheme/pros-cons', {
        title: 'Pros and Cons',
        icon: 'columns',
        category: 'design',
        attributes: {
            pros: {
                type: 'string',
                source: 'html',
                selector: '.crazy-pros-box ul',
                default: '<li>Pro item 1</li><li>Pro item 2</li>'
            },
            cons: {
                type: 'string',
                source: 'html',
                selector: '.crazy-cons-box ul',
                default: '<li>Con item 1</li><li>Con item 2</li>'
            }
        },

        edit: function( props ) {
            var attributes = props.attributes;

            return el( 'div', { className: props.className },
                el( 'style', null, injectedCSS ),
                el( 'div', { className: 'crazy-pros-cons-grid' },
                    // Pros Box
                    el( 'div', { className: 'crazy-pros-box' },
                        el( 'div', { style: Object.assign({}, iconBoxStyle, { background: '#3bda5c' }) }, thumbsUpSVG ),
                        el( RichText, {
                            tagName: 'ul',
                            multiline: 'li',
                            className: 'crazy-pros-cons-list',
                            value: attributes.pros,
                            onChange: function( val ) { props.setAttributes( { pros: val } ); },
                            placeholder: 'Add pros here...'
                        } )
                    ),
                    // Cons Box
                    el( 'div', { className: 'crazy-cons-box' },
                        el( 'div', { style: Object.assign({}, iconBoxStyle, { background: '#f9844a' }) }, thumbsDownSVG ),
                        el( RichText, {
                            tagName: 'ul',
                            multiline: 'li',
                            className: 'crazy-pros-cons-list',
                            value: attributes.cons,
                            onChange: function( val ) { props.setAttributes( { cons: val } ); },
                            placeholder: 'Add cons here...'
                        } )
                    )
                )
            );
        },

        save: function( props ) {
            var attributes = props.attributes;

            return el( 'div', null,
                el( 'style', null, injectedCSS ),
                el( 'div', { className: 'crazy-pros-cons-grid' },
                    // Pros Box
                    el( 'div', { className: 'crazy-pros-box' },
                        el( 'div', { style: Object.assign({}, iconBoxStyle, { background: '#3bda5c' }) }, thumbsUpSVG ),
                        el( RichText.Content, {
                            tagName: 'ul',
                            className: 'crazy-pros-cons-list',
                            value: attributes.pros
                        } )
                    ),
                    // Cons Box
                    el( 'div', { className: 'crazy-cons-box' },
                        el( 'div', { style: Object.assign({}, iconBoxStyle, { background: '#f9844a' }) }, thumbsDownSVG ),
                        el( RichText.Content, {
                            tagName: 'ul',
                            className: 'crazy-pros-cons-list',
                            value: attributes.cons
                        } )
                    )
                )
            );
        }
    } );
} )( window.wp );
