class ReviewImage1 extends React.Component {

	render() {

		const { attributes } = this.props 

		let url_chk = ""
		if( typeof attributes.image1 !== "undefined" && attributes.image1 !== null && attributes.image1 !=="" ){
			url_chk = attributes.image1.url
		}
		
		let url = ""
		if( url_chk !== "" ){
			let size = attributes.image1.sizes
			let imageSize1 = attributes.imageSize1

			if ( typeof size !== "undefined" && typeof size[imageSize1] !== "undefined" ) {
			  url = size[imageSize1].url 
			}else{
			  url = url_chk 
			}

			return (				
				<div className="affiliate-coupon-image1">
					<img
						className =""
						src = { url }                        
						alt = { attributes.image1.alt }                    
					/>						
				</div>				                 
			)
		} else {
			return null
		}       
	}
}

export default ReviewImage1
