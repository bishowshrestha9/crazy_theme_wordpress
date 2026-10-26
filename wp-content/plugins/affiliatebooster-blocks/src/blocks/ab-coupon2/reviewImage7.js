class ReviewImage7 extends React.Component {

	render() {

		const { attributes } = this.props 

		let url_chk = ""
		if( typeof attributes.image7 !== "undefined" && attributes.image7 !== null && attributes.image7 !=="" ){
			url_chk = attributes.image7.url
		}
		
		let url = ""
		if( url_chk !== "" ){
			let size = attributes.image7.sizes
			let imageSize7 = attributes.imageSize7

			if ( typeof size !== "undefined" && typeof size[imageSize7] !== "undefined" ) {
			  url = size[imageSize7].url 
			}else{
			  url = url_chk 
			}

			return (				
				<div className="affiliate-coupon-image7">
					<img
						className =""
						src = { url }                        
						alt = { attributes.image7.alt }                    
					/>						
				</div>				                 
			)
		} else {
			return null
		}       
	}
}

export default ReviewImage7
