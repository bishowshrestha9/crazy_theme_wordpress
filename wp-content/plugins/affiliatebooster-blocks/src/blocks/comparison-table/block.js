import attributes from './attributes';
import Edit from './Edit.js';
import icons from '../../icons/icons';
import RichList from '../../components/rich-list';
const { Component, Fragment } = wp.element;
const {
	InspectorControls,
	AlignmentToolbar,
	BlockControls,
	PanelColorSettings,
	MediaUpload,
	RichText,
	URLInput,
	InnerBlocks,
	__experimentalLinkControl
} = wp.blockEditor;
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const NEW_TAB_REL = 'noopener noreferrer';

registerBlockType('affiliate-booster/ab-comparison-table', {
	title: __('AB Comparison Table'),
	icon: icons.comparison,
	category: 'affiliate-booster',
	keywords: [
		__('NicheTable'),
		__('Comparison Table'),
		__('Best Responsive Comparison Table for Gutenberg Editor'),
	],
	supports: {
		html: false,
		// className: false, 
		align: true,
		align: ['none', 'wide', 'full'],
		splitting: true,
	},
	attributes,
	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.	
	 */
	edit: Edit,

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.	
	 */
	save: function (props) {
		const { InnerBlocks } = wp.blockEditor
		var { attributes: { block_id, idtbl, designType, tdbox, th1_txt, th2_txt, th3_txt, th4_txt,
			th1_width,
			th2_width,
			th3_width,
			th4_width,
			th1_width_2,
			th2_width_2,
			th3_width_2,
			th4_width_2,
			HTxtColor, HBgColor, tEvenColor, btnRel, titleTag1, tOddColor, btnWidth, btn_Htarget, tableStyle, showItemRating, buttonIcon, iconPosition,
			badgeText, titleText,
			btn_Style, link, tdTxtColor, th_fontsize, td_fontsize, showReadMore,
			enableGlimmerEffect,

			showImage,
			showProductTitle,
			showProductContent,
			showProductPrice,

			btnBg,
			btnBgHover,

		} } = props;
		const Tag1 = titleTag1
		const trDisplay = props.attributes.tdbox.map((location, index) => {
			let newrel;
			if (!location.btn_Htarget) {
				newrel = location.btnRel
			} else if (!location.btn_Htarget && location.btnRel === NEW_TAB_REL) {
				newrel = location.btnRel
			} else if (location.btn_Htarget && !location.btnRel) {
				newrel = NEW_TAB_REL
			} else {
				newrel = NEW_TAB_REL + ' ' + location.btnRel
			}
			function removeDuplicateWords(s) {
				var str = s.split(" ");
				var result = [];
				for (var i = 0; i < str.length; i++) {
					if (result.indexOf(str[i]) === -1) {
						result.push(str[i]);
					}
				}
				return result.join(" ");
			}

			newrel = removeDuplicateWords(newrel).trim()
			let slugName = location.fdprd.toString().toLowerCase().replace(/( |<.+?>|&nbsp;)/g, "-");

			return <tr key={index}>

				{tableStyle && tableStyle == 'tbl-style-1' && showImage && showImage == true &&
					<td data-column={th1_txt} className="affiliate-col-img" style={{ width: `${!!th1_width ? th1_width : 25}%` }}>
						{designType && designType == 'ab-dynamic-design' &&
							<InnerBlocks.Content />
						}
						{designType && designType == 'ab-fixed-design' &&
							<img src={location.mediaURL} alt={location.fdprd} className="affiliate-img" />
						}
						{tableStyle && tableStyle == 'tbl-style-2' && showItemRating == true &&
							<RichText.Content
								tagName={'span'}
								className="affiliate-img-rating"
								placeholder="9.5"
								value={location.itemRating}
							/>
						}
					</td>
				}

				{tableStyle && tableStyle == 'tbl-style-2' && showImage && showImage == true &&
					<td data-column={th1_txt} className="affiliate-col-img" style={{ width: `${!!th1_width_2 ? th1_width_2 : 25}%` }}>
						{designType && designType == 'ab-dynamic-design' &&
							<InnerBlocks.Content />
						}
						{designType && designType == 'ab-fixed-design' &&
							<img src={location.mediaURL} alt={location.fdprd} className="affiliate-img" />
						}
						{tableStyle && tableStyle == 'tbl-style-2' && showItemRating == true &&
							<RichText.Content
								tagName={'span'}
								className="affiliate-img-rating"
								placeholder="9.5"
								value={location.itemRating}
							/>
						}
					</td>
				}

				{tableStyle && tableStyle == 'tbl-style-1' && showProductTitle && showProductTitle == true &&
					<td data-column={th2_txt} className={`affiliate-col-ct1`} style={{ width: `${!!th2_width ? th2_width : 25}%` }}>
						<RichText.Content
							tagName={Tag1}
							className={`affiliate-txtbox-title`}
							value={location.fdprd}
							id={slugName}
						/>
					</td>
				}
				{tableStyle && tableStyle == 'tbl-style-2' && showProductTitle && showProductTitle == true &&
					<td data-column={th2_txt} className={`affiliate-col-ct1`} style={{ width: `${!!th2_width_2 ? th2_width_2 : 25}%` }}>
						{tableStyle && tableStyle == 'tbl-style-2' &&
							<Fragment>
								<Tag1>
									<RichText.Content
										tagName={'span'}
										className={`affiliate-txtbox-title`}
										value={location.fdprd}
										id={slugName}
									/>
									<RichText.Content
										tagName={'span'}
										className="affiliate-item-badge"
										value={location.badgeText}
									/>
								</Tag1>
								<RichList.Content value={location.fddetail} />
							</Fragment>
						}
						{tableStyle && tableStyle == 'tbl-style-1' &&
							<RichText.Content
								tagName={Tag1}
								className={`affiliate-txtbox-title`}
								value={location.fdprd}
								id={slugName}
							/>
						}
					</td>
				}

				{showProductContent && showProductContent == true &&
					<Fragment>
						{tableStyle && tableStyle == 'tbl-style-1' &&
							<td data-column={th3_txt} className={`affiliate-col-ct2`} style={{ width: `${!!th3_width ? th3_width : 25}%` }}>
								<RichList.Content value={location.fddetail} />
							</td>
						}

						{/* {tableStyle && tableStyle == 'tbl-style-2' &&
							<td data-column={th4_txt}>
								<Fragment>
									<a className={`affiliate-compr-btn ${btnWidth}   ${enableGlimmerEffect ? 'glimmer-effect' : ''} ` + btn_Style} href={location.fdbtnurl} title={location.fdbtntitle}
										// rel={location.btnRel } 
										{...(location.btn_Htarget ? { ...location.btn_Htarget && { rel: newrel } } : { rel: newrel })}
										{...(location.btn_Htarget ? { target: '_blank' } : '')}>

										{iconPosition && iconPosition == 'af-icon-is-left' && buttonIcon !== '' &&
											<i className={`${buttonIcon} affiliate-abbtn-icon ${iconPosition}`}></i>
										}
										<RichText.Content
											value={location.fdbtn}
										/>
										{iconPosition && iconPosition == 'af-icon-is-right' && buttonIcon !== '' &&
											<i className={`${buttonIcon} affiliate-abbtn-icon ${iconPosition}`}></i>
										}
									</a>
									<Fragment>
										{tableStyle && tableStyle == 'tbl-style-2' && showReadMore == true &&
											<RichText.Content
												tagName={'span'}
												value={location.readMoreText}
												// href = {location.link}
												className={`affiliate-cmpr-readmore`}
											/>
										}
									</Fragment>
								</Fragment>
							</td>
						} */}
					</Fragment>
				}


				{showProductPrice && showProductPrice == true && tableStyle && tableStyle == 'tbl-style-1' &&
					<td data-column={th4_txt} style={{ width: `${!!th4_width ? th4_width : 25}%` }}>
						<Fragment>
							<a className={`affiliate-compr-btn ${btnWidth}   ${enableGlimmerEffect ? 'glimmer-effect' : ''} ` + btn_Style} href={location.fdbtnurl} title={location.fdbtntitle}
								// rel={location.btnRel } 
								{...(location.btn_Htarget ? { ...location.btn_Htarget && { rel: newrel } } : { rel: newrel })}
								{...(location.btn_Htarget ? { target: '_blank' } : '')}>

								{iconPosition && iconPosition == 'af-icon-is-left' && buttonIcon !== '' &&
									<i className={`${buttonIcon} affiliate-abbtn-icon ${iconPosition}`}></i>
								}
								<RichText.Content
									value={location.fdbtn}
								/>
								{iconPosition && iconPosition == 'af-icon-is-right' && buttonIcon !== '' &&
									<i className={`${buttonIcon} affiliate-abbtn-icon ${iconPosition}`}></i>
								}
							</a>
							<Fragment>
								{tableStyle && tableStyle == 'tbl-style-2' && showReadMore == true &&
									<RichText.Content
										tagName={'span'}
										value={location.readMoreText}
										// href = {location.link}
										className={`affiliate-cmpr-readmore`}
									/>
								}
							</Fragment>
						</Fragment>
					</td>
				}

				{showProductPrice && showProductPrice == true && tableStyle && tableStyle == 'tbl-style-2' &&
					<td data-column={th4_txt} style={{ width: `${!!th4_width_2 ? th4_width_2 : 25}%` }}>
						<Fragment>
							<a className={`affiliate-compr-btn ${btnWidth}   ${enableGlimmerEffect ? 'glimmer-effect' : ''} ` + btn_Style} href={location.fdbtnurl} title={location.fdbtntitle}
								// rel={location.btnRel } 
								{...(location.btn_Htarget ? { ...location.btn_Htarget && { rel: newrel } } : { rel: newrel })}
								{...(location.btn_Htarget ? { target: '_blank' } : '')}>

								{iconPosition && iconPosition == 'af-icon-is-left' && buttonIcon !== '' &&
									<i className={`${buttonIcon} affiliate-abbtn-icon ${iconPosition}`}></i>
								}
								<RichText.Content
									value={location.fdbtn}
								/>
								{iconPosition && iconPosition == 'af-icon-is-right' && buttonIcon !== '' &&
									<i className={`${buttonIcon} affiliate-abbtn-icon ${iconPosition}`}></i>
								}
							</a>
							<Fragment>
								{tableStyle && tableStyle == 'tbl-style-2' && showReadMore == true &&
									<RichText.Content
										tagName={'span'}
										value={location.readMoreText}
										// href = {location.link}
										className={`affiliate-cmpr-readmore`}
									/>
								}
							</Fragment>
						</Fragment>
					</td>
				}
			</tr>;
		});
		return (
			<div id={`affiliate-style-${block_id}`} className={props.className}>
				<table className={`affiliate-cmpr-inner ${tableStyle}`}>
					<thead className={`affiliate-compr-th`}>
						<tr>
							{showImage && showImage == true &&
								<th>{th1_txt}</th>
							}
							{showProductTitle && showProductTitle == true &&
								<th>{th2_txt}</th>
							}
							{tableStyle && tableStyle == 'tbl-style-1' && showProductContent && showProductContent == true &&
								<th>{th3_txt}</th>
							}
							{tableStyle && tableStyle == 'tbl-style-2' && showProductContent && showProductContent == true &&
								<th>{th4_txt}</th>
							}
							{tableStyle && tableStyle == 'tbl-style-1' && showProductPrice && showProductPrice == true &&
								<th>{th4_txt}</th>
							}
						</tr>
					</thead>
					<tbody className={`affiliate-compr-cntn`}>
						{trDisplay}
					</tbody>
				</table>
			</div>
		);
	},
});
