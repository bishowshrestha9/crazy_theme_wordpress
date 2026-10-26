class ReviewImage5 extends React.Component {

	render() {

		const { attributes } = this.props 

		let url_chk = ""
		if( typeof attributes.image5 !== "undefined" && attributes.image5 !== null && attributes.image5 !=="" ){
			url_chk = attributes.image5.url
		}
		
		let url = ""
		if( url_chk !== "" ){
			let size = attributes.image5.sizes
			let imageSize5 = attributes.imageSize5

			if ( typeof size !== "undefined" && typeof size[imageSize5] !== "undefined" ) {
			  url = size[imageSize5].url 
			}else{
			  url = url_chk 
			}

			return (				
				<div className="affiliate-coupon-image5">
					<img
						className =""
						src = { url }                        
						alt = { attributes.image5.alt }                    
					/>						
				</div>				                 
			)
		} else {
			return null
		}       
	}
}

export default ReviewImage5
