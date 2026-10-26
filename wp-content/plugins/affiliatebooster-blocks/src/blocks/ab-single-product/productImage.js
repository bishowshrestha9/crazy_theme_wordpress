class ProductImage extends React.Component {

	render() {

		const { attributes } = this.props 

		let url_chk = ""
		if( typeof attributes.image !== "undefined" && attributes.image !== null && attributes.image !=="" ){
			url_chk = attributes.image.url
		}
		
		let url = ""
		if( url_chk !== "" ){
			let size = attributes.image.sizes
			let imageSize = attributes.imageSize

			if ( typeof size !== "undefined" && typeof size[imageSize] !== "undefined" ) {
			  url = size[imageSize].url 
			}else{
			  url = url_chk 
			}

			return (				
				<div className="affiliate-sp-image">
					<img
						className =""
						src = { url }                        
						alt = { attributes.image.alt }                    
					/>						
				</div>				                 
			)
		}else{
			return null
		}       
		
	}
}

export default ProductImage
