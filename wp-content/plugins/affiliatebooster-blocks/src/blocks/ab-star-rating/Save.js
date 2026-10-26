const { Component,Fragment } = wp.element;
import { EmptyStar, FullStar, HalfStar } from "./icons";
const { RichText } = wp.blockEditor;
class Save extends Component {
    
    render() {
        const { name,
            clientId,
            isSelected,
            attributes,
            setAttributes,
            highlightedStars,
            attributes: {
                block_id,
                starAligment,
                starAligmentMobile,
                starAligmentTable,
                starsSize,
                starsSizeMobile,
                starsSizeTablet,
                ratings,
                ratingsColor,
                selectedStars,
                ratingAlignemt
            } } = this.props
      
        return (
            <Fragment>
                 <div id={`affiliate-style-${block_id}`} className={`affiliate-starating-wrapper`} >
                <div className="affiliate-star-outer-container">
                    <div
                        className="affiliate-star-inner-container"
                        onMouseLeave={() => this.setState({ highlightedStars: 0 })} >
                        {[...Array(ratings)].map((e, i) => (
                          <div className={`affiliate-star-item`} key={i}>
                                {i < (highlightedStars ? highlightedStars : selectedStars) ? (
                                    highlightedStars ? (
                                        highlightedStars - 1 === i ? (
                                            selectedStars % 1 > 0 ? (
                                                highlightedStars - selectedStars - 0.5 !== 0 ? (
                                                    <HalfStar fillColor={ratingsColor} />
                                                ) : (
                                                    <FullStar fillColor={ratingsColor} />
                                                )
                                            ) : highlightedStars - selectedStars !== 0 ? (
                                                <FullStar fillColor={ratingsColor} />
                                            ) : (
                                                <HalfStar fillColor={ratingsColor} />
                                            )
                                        ) : (
                                            <FullStar fillColor={ratingsColor} />
                                        )
                                    ) : selectedStars - i >= 1 ? (
                                        <FullStar fillColor={ratingsColor} />
                                    ) : (
                                        <HalfStar fillColor={ratingsColor} />
                                    )
                                ) : (
                                    <EmptyStar fillColor={ratingsColor} />
                                )}
                            </div>
                        ))}
                    </div>
                </div>
                </div>
            </Fragment>
        );
    }
}

export default Save;