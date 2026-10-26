class ReviewImage6 extends React.Component {

	render() {

		const { attributes } = this.props 

		let url_chk = ""
		if( typeof attributes.image6 !== "undefined" && attributes.image6 !== null && attributes.image6 !=="" ){
			url_chk = attributes.image6.url
		}
		
		let url = ""
		if( url_chk !== "" ){
			let size = attributes.image6.sizes
			let imageSize6 = attributes.imageSize6

			if ( typeof size !== "undefined" && typeof size[imageSize6] !== "undefined" ) {
			  url = size[imageSize6].url 
			}else{
			  url = url_chk 
			}

			return (				
				<div className="affiliate-coupon-image6">
					<img
						className =""
						src = { url }                        
						alt = { attributes.image6.alt }                    
					/>						
				</div>				                 
			)
		} else {
			return null
		}       
	}
}

export default ReviewImage6
