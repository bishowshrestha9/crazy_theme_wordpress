class ReviewImage4 extends React.Component {

	render() {

		const { attributes } = this.props 

		let url_chk = ""
		if( typeof attributes.image4 !== "undefined" && attributes.image4 !== null && attributes.image4 !=="" ){
			url_chk = attributes.image4.url
		}
		
		let url = ""
		if( url_chk !== "" ){
			let size = attributes.image4.sizes
			let imageSize4 = attributes.imageSize4

			if ( typeof size !== "undefined" && typeof size[imageSize4] !== "undefined" ) {
			  url = size[imageSize4].url 
			}else{
			  url = url_chk 
			}

			return (				
				<div className="affiliate-coupon-image4">
					<img
						className =""
						src = { url }                        
						alt = { attributes.image4.alt }                    
					/>						
				</div>				                 
			)
		} else {
			return null
		}       
	}
}

export default ReviewImage4
