( function( wp ) {
    var el = wp.element.createElement;
    var registerBlockType = wp.blocks.registerBlockType;
    var RichText = wp.blockEditor ? wp.blockEditor.RichText : wp.editor.RichText;

    var blockStyle = {
        backgroundColor: '#10b981',
        color: '#ffffff',
        padding: '2rem',
        borderRadius: '8px',
        margin: '2rem 0',
        boxShadow: '0 4px 6px rgba(0,0,0,0.1)'
    };

    var titleStyle = {
        color: '#ffffff',
        marginTop: 0,
        marginBottom: '1rem',
        fontSize: '1.6rem',
        fontWeight: '600'
    };

    var contentStyle = {
        fontSize: '1.05rem',
        lineHeight: '1.8'
    };

    registerBlockType( 'crazytheme/quick-verdict', {
        title: 'Quick Verdict',
        icon: 'yes-alt',
        category: 'design',
        attributes: {
            title: {
                type: 'string',
                source: 'html',
                selector: 'h3',
                default: 'Quick Verdict'
            },
            content: {
                type: 'string',
                source: 'html',
                selector: '.quick-verdict-content',
                default: 'Enter your verdict description here...'
            }
        },

        edit: function( props ) {
            var attributes = props.attributes;
            
            return el( 'div', { style: blockStyle, className: props.className },
                el( RichText, {
                    tagName: 'h3',
                    style: titleStyle,
                    value: attributes.title,
                    onChange: function( newTitle ) {
                        props.setAttributes( { title: newTitle } );
                    },
                    placeholder: 'Enter Title...'
                } ),
                el( RichText, {
                    tagName: 'div',
                    className: 'quick-verdict-content',
                    style: contentStyle,
                    value: attributes.content,
                    onChange: function( newContent ) {
                        props.setAttributes( { content: newContent } );
                    },
                    placeholder: 'Enter verdict content here...'
                } )
            );
        },

        save: function( props ) {
            var attributes = props.attributes;
            
            return el( 'div', { style: blockStyle },
                el( RichText.Content, {
                    tagName: 'h3',
                    style: titleStyle,
                    value: attributes.title
                } ),
                el( RichText.Content, {
                    tagName: 'div',
                    className: 'quick-verdict-content',
                    style: contentStyle,
                    value: attributes.content
                } )
            );
        }
    } );
} )( window.wp );
