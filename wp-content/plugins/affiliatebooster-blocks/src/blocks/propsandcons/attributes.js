const attributes = {
    uniqueId: {
        type: 'string',
        default: ''
    },
    block_id: {
        type: 'string',
        default: ''
    },
    boxShadow:{
        type: 'object',
        default: {
            openShadow: true,
            blur: 35,
            color:"rgba(0,0,0,0.10)",
            inset : 0,
            spread : 0,
            horizontal:0,
            vertical : 8
        }
    },
    listTextItems: {
        type:'array',
        // source: 'children',
        filterElements: true,
        // selector: '.test-list',
        default:[],
    },
    listTextItemsTwo: {
        type:'array',
        // source: 'children',
        filterElements: true,
        // selector: '.test-list',
        default:[],
	},
    titleLoadGoogleFonts:{
        type: 'boolean',
        default: false
    },
    listLoadGoogleFonts:{
        type: 'boolean',
        default: false
    },
    titleTag1: {
        type: 'string',
        default: 'p'
    },
    titleTag2: {
        type: 'string',
        default: 'p'
    },
    propsTitle: {
        type: 'string',
        default: 'Pros'
    },
    consTitle: {
        type: 'string',
        default: 'Cons',
    },
    recreateStyles: {
        type: 'boolean',
        default: true,
    },
    listType: {
        type: 'string',
        default: 'unordered',
    },
    alignment: {
        type: 'string',
        default: 'left',
    },
    layout: {
        type: 'string',
        default: 'fill'
    },
    listItems: {
        type: 'array',
        default: ['']
    },
    listItemsTwo: {
        type: 'array',
        default: ['']
    },
    bulletStyle: {
        type: 'object',
        default: {
            name: 'check-circle',
            value: 'far fa-check-circle'
        }
    },
    bulletStyleTwo: {
        type: 'object',
        default: {
            name: 'times-circle',
            value: 'far fa-times-circle'
        }
    },
    bulletSize: {
        type: 'string',
        default: '16px',
    },
    bulletSizeCustom: {
        type: 'object',
        default: {
            md: 18,
            unit: 'px'
        },
    },
    bulletColor: {
        type: 'string',
        default: '',
    },
    bulletColorHover: {
        type: 'string',
        default: '',
    },
    useNumberBg: {
        type: 'boolean',
        default: true
    },
    numberFontSize: {
        type: 'string',
        default: '14',
    },
    numberBgSize: {
        type: 'string',
        default: '5',
    },
    numberBg: {
        type: 'string',
        default: '#c2e5ff',
    },
    numberBgHover: {
        type: 'string',
        default: '',
    },
    numberCorner: {
        type: 'string',
        default: '50',
    },
    color: {
        type: 'string',
        default: '#333',
    },
    colorHover: {
        type: 'string',
        default: '',
    },
    spacing: {
        type: 'object',
        default: {
            md: 5, unit: 'px'
        },
    },
    backgroundSize: {
        type: 'object',
        default: {
            openPadding: 1,
            paddingType: 'global',
            global: {
                md: '10',
                unit: 'px'
            }
        },
    },
    background: {
        type: 'string',
        default: '#f5f5f5',
    },
    backgroundHover: {
        type: 'string',
        default: '',
    },
    borderRadius: {
        type: 'object',
        default: {
            openBorderRadius: 1,
            radiusType: 'global'
        },
    },
    titleBgColor: {
        type: 'string',
        default:'',
    },
    titleTextColor: {
        type: 'string',
        default:'',
    },
    titlefontFamily: {
        type: 'string',
        default:'',
    },
    titlefontWeight: {
        type: 'string',
        default: '900',
    },
    titlefontSubset: {
        type: 'string',
        default: '',
    },
    titlefontSizeType: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeMobile: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeTypeTablet: {
        type: 'string',
        default: 'px',
    },
    titlefontSizeDesktop: {
        type: 'number',
        default: '20',
    },
    titlefontSizeMobile: {
        type: 'number',
        default: '',
    },
    titlefontSizeTablet: {
        type: 'number',
        default: '',
    },
    titlelineHeight: {
        type: 'string',
        default: ''
    },
    titlelineHeightMobile: {
        type: 'string',
        default: ''
    },
    titlelineHeightTablet: {
        type: 'string',
        default: ''
    },
    titleletterSpacing: {
        type: 'number',
        default: ''
    },
    titleletterSpacingMobile: {
        type: 'number',
        default: ''
    },
    titleletterSpacingTablet: {
        type: 'number',
        default: ''
    },
    titleletterSpacingType: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeTablet: {
        type: 'string',
        default: 'px'
    },
    titleletterSpacingTypeMobile: {
        type: 'string',
        default: 'px'
    },
    titletextTransform: {
        type: 'string',
        default: ''
    },
    titletextDecoration: {
        type: 'string',
        default: ''
    },
    titlefontStyle: {
        type: 'string',
        default: ''
    },
    // 
    listfontFamily: {
        type: 'string',
        default:'',
    },
    listfontWeight: {
        type: 'string',
        default: '',
    },
    listfontSubset: {
        type: 'string',
        default: '',
    },
    listfontSizeType: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeMobile: {
        type: 'string',
        default: 'px',
    },
    listfontSizeTypeTablet: {
        type: 'string',
        default: 'px',
    },
    listfontSizeDesktop: {
        type: 'number',
        default: '',
    },
    listfontSizeMobile: {
        type: 'number',
        default: '',
    },
    listfontSizeTablet: {
        type: 'number',
        default: '',
    },
    listlineHeight: {
        type: 'number',
        default: ''
    },
    listlineHeightMobile: {
        type: 'number',
        default: ''
    },
    listlineHeightTablet: {
        type: 'number',
        default: ''
    },
    listletterSpacing: {
        type: 'number',
        default: ''
    },
    listletterSpacingMobile: {
        type: 'number',
        default: ''
    },
    listletterSpacingTablet: {
        type: 'number',
        default: ''
    },
    listletterSpacingType: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeTablet: {
        type: 'string',
        default: 'px'
    },
    listletterSpacingTypeMobile: {
        type: 'string',
        default: 'px'
    },
    listtextTransform: {
        type: 'string',
        default: ''
    },
    listtextDecoration: {
        type: 'string',
        default: ''
    },
    listfontStyle: {
        type: 'string',
        default: ''
    },
    listTextColor: {
        type: 'string',
        default: ''
    },
    propsBgColor: {
        type: 'string',
        default: '#00bf08'
    },
    propsIconColor: {
        type: 'string',
        default: '#30ac3d'
    },
    propsTextColor: {
        type: 'string',
        default: '#ffffff'
    },
    consBgColor: {
        type: 'string',
        default: '#eb3903'
    },
    consTextColor: {
        type: 'string',
        default: '#ffffff'
    },
    consIconColor: {
        type: 'string',
        default: '#ff1818'
    },
    consListColor: {
        type: 'string',
        default: '#000000'
    },
    propsListColor: {
        type: 'string',
        default: '#000000'
    },


    enableSchema:{
        type: 'boolean',
        default: false
    },
    schemaType: {
        type: 'string',
        default: 'Product'
    },
    schemaSubType: {
        type: 'string',
        default: ''
    },
    schemaTitle: {
        type: 'string',
        default: ''
    },
    schemaDescription: {
        type: 'string',
        default: ''
    },
    schemaRating:{
        type: 'string',
        default: ''
    }, 
    schemaReviewTitle: {
        type: 'string',
        default: ''
    },
    schemaReviewCount:{
        type: 'string',
        default: ''
    }, 
    schemaApplicationCategory:{
        type: 'string',
        default: ''
    },
    schemaOperatingSystem:{
        type: 'string', 
        default: ''
    },
    schemaOffer:{
        type: 'string',
        default: 'InStock'
    },
    schemaOfferPrice:{
        type: 'string',
        default: ''
    }, 
    schemaOfferPriceCurrency:{
        type: 'string',
        default: 'USD'
    }, 
    schemaBrand:{
        type: 'string',
        default: ''
    },
    schemaSku:{
        type: 'string',
        default: ''
    },
    schemaIdentifierType:{
        type: 'string',
        default: 'gtin'
    },
    schemaIdentifier:{
        type: 'string',
        default: ''
    },
    schemaImage: { 
        type: 'object', 
        default: {
            id:'',
            url:'',
            alt:'',
        } 
    },
    schemaUrl:{
        type: 'string',
        default: ''
    },
    schemapriceValidUntil:{
        type: 'string',
        default: ''
    },
    schemaAuthor:{
        type: 'string',
        default: ''
    },

    schemaPositiveNotes: {
        type: 'string',
        default: ''
    },
    schemaNegativeNotes: {
        type: 'string',
        default: ''
    },

}

export default attributes;