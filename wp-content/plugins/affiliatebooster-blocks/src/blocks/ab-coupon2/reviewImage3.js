class ReviewImage3 extends React.Component {

	render() {

		const { attributes } = this.props 

		let url_chk = ""
		if( typeof attributes.image3 !== "undefined" && attributes.image3 !== null && attributes.image3 !=="" ){
			url_chk = attributes.image3.url
		}
		
		let url = ""
		if( url_chk !== "" ){
			let size = attributes.image3.sizes
			let imageSize3 = attributes.imageSize3

			if ( typeof size !== "undefined" && typeof size[imageSize3] !== "undefined" ) {
			  url = size[imageSize3].url 
			}else{
			  url = url_chk 
			}

			return (				
				<div className="affiliate-coupon-image3">
					<img
						className =""
						src = { url }                        
						alt = { attributes.image3.alt }                    
					/>						
				</div>				                 
			)
		} else {
			return null
		}       
	}
}

export default ReviewImage3
