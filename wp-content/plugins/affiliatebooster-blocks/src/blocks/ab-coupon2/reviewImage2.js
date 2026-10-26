class ReviewImage2 extends React.Component {

	render() {

		const { attributes } = this.props 

		let url_chk = ""
		if( typeof attributes.image2 !== "undefined" && attributes.image2 !== null && attributes.image2 !=="" ){
			url_chk = attributes.image2.url
		}
		
		let url = ""
		if( url_chk !== "" ){
			let size = attributes.image2.sizes
			let imageSize2 = attributes.imageSize2

			if ( typeof size !== "undefined" && typeof size[imageSize2] !== "undefined" ) {
			  url = size[imageSize2].url 
			}else{
			  url = url_chk 
			}

			return (				
				<div className="affiliate-coupon-image2">
					<img
						className =""
						src = { url }                        
						alt = { attributes.image2.alt }                    
					/>						
				</div>				                 
			)
		} else {
			return null
		}       
	}
}

export default ReviewImage2
