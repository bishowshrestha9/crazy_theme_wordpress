import TypographyControl from '../components/typography/index.js';
import WebfontLoader from "../components/typography/fontloader"
import Color from '../../components/fields/Color/Color.js';
import affiliate_styling from './styling.js';
import BorderTypes from '../../components/fields/BorderTypes.js';
import IconList from '../../components/fields/IconList/IconList.js';
import Dimension from '../../components/fields/Dimension/Dimension.js';
import Alignment from '../../components/fields/Alignment.js';
import Tabs from '../../components/fields/Tabs/Tabs.js';
import Background from './../../components/fields/Background/Background.js';
import Tab from '../../components/fields/Tab/Tab.js';
import Select from './../../components/fields/Select/Select.js';
import RadioAdvanced from '../../components/fields/RadioAdvanced.js';
import Range from '../../components/fields/Range/Range.js';
import BoxShadow from "../../components/fields/BoxShadow"
import attributes from './attributes';
import icons from '../../icons/icons';
import times from 'lodash/times';
import memoize from 'memize';
import { keyboardReturn } from '@wordpress/icons';
import getIcon from '../../icons/get-icon';

import './editor.scss';
import './style.scss';
import RichList, {getListValue, setListValue} from '../../components/rich-list';
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { Component, Fragment } = wp.element;

const { withSelect, withDispatch } = wp.data
const { compose } = wp.compose

const getPanesTemplate = memoize((panes) => {
	return '';
});

const {
	InspectorControls,
	AlignmentToolbar,
	BlockControls,
	PanelColorSettings,
	MediaUpload,
	RichText,
	URLInput,
	InnerBlocks,
	URLPopover,
	__experimentalLinkControl
} = wp.blockEditor;
// Register components
const {
	Button,
	PanelBody,
	IconButton,
	SelectControl,
	PanelRow,
	RangeControl,
	ToggleControl,
	TextControl,
	TextareaControl,
	Dashicon,
	Tooltip,
	Dropdown,
	Popover,
	TabPanel,
	ToolbarButton,
	ToolbarGroup
} = wp.components;

const fd_btnstyle = [
	{ value: 'btn-style-1', label: __('Style 1') },
	{ value: 'btn-style-2', label: __('Style 2') },
];
const NEW_TAB_REL = 'noopener noreferrer';
class Edit extends Component {
	constructor() {
		super(...arguments);

		this.onClickLinkSettings = this.onClickLinkSettings.bind(this)
		this.onChangeOpensInNewTab = this.onChangeOpensInNewTab.bind(this)
		this.state = { isURLPickerOpen: false }
		this.state = { selectedBox: null }
		this.getSlug = this.getSlug.bind(this)
	}

	componentDidMount() {
		const { attributes, setAttributes, clientId } = this.props;
		if (!attributes.idtbl) {
			setAttributes({ idtbl: "fdtable-" + clientId });
		}

		this.props.setAttributes({ block_id: this.props.clientId })
		const $style = document.createElement("style")
		$style.setAttribute("id", "affiliate-style-" + this.props.clientId)
		document.head.appendChild($style)
	}

	getImageSize(sizes) {
		var size_arr = []
		$.each(sizes, function (index, item) {
			var name = index
			var p = { "value": name, "label": name }
			size_arr.push(p)
		})
		return (size_arr)
	}

	getSlug(title) {
		return title.toString().toLowerCase().replace(/( |<.+?>|&nbsp;)/g, "-");
	}

	onClickLinkSettings(value, index) {

		const { attributes, setAttributes } = this.props
		const { target } = attributes
		if ("_self" === target) {
			setAttributes({ opensInNewTab: false })
		} else if ("_blank" === target) {
			setAttributes({ opensInNewTab: true })
		}

		this.setState({
			isURLPickerOpen: true
		})
	}

	onChangeOpensInNewTab(value) {
		if (true === value) {
			this.props.setAttributes({ target: '_blank' })
		} else {
			this.props.setAttributes({ target: '_self' })
		}
	}

	render() {
		const { idtbl } = this.props.attributes;
		const { isSelected, realPaneCount, abcompareBlocks, setAttributes, clientId } = this.props;
		const { attributes: { tdbox, th1_txt, th2_txt, th3_txt, th4_txt,
			th1_width,
			th2_width,
			th3_width,
			th4_width,
			th1_width_2,
			th2_width_2,
			th3_width_2,
			th4_width_2,
			btnRel, HTxtColor, itemCount, HBgColor, tEvenColor, tOddColor, btn_Htarget, btn_Style, tdTxtColor, th_fontsize, td_fontsize,
			block_id,
			btnLoadGoogleFonts,
			btnfontFamily,
			btnfontWeight,
			btnfontSubset,
			btnfontSizeType,
			btnfontSizeTypeMobile,
			btnfontSizeTypeTablet,
			btnfontSizeDesktop,
			btnfontSizeMobile,
			btnfontSizeTablet,
			btnlineHeight,
			btnlineHeightMobile,
			btnlineHeightTablet,
			btnletterSpacing,
			btnletterSpacingMobile,
			btnletterSpacingTablet,
			btnletterSpacingType,
			btnletterSpacingTypeTablet,
			btnletterSpacingTypeMobile,
			btntextTransform,
			btntextDecoration,
			btnfontStyle,
			btnBgColor,
			btnHoverBgColor,
			btnTextColor,
			btnTextHoverColor,
			btnBorderColor,
			btnBorderHoverColor,
			enableGlimmerEffect,
			// table heading
			thLoadGoogleFonts,
			thfontFamily,
			thfontWeight,
			thfontSubset,
			thfontSizeType,
			thfontSizeTypeMobile,
			thfontSizeTypeTablet,
			thfontSizeDesktop,
			thfontSizeMobile,
			thfontSizeTablet,
			thlineHeight,
			thlineHeightMobile,
			thlineHeightTablet,
			thletterSpacing,
			thletterSpacingMobile,
			thletterSpacingTablet,
			thletterSpacingType,
			thletterSpacingTypeTablet,
			thletterSpacingTypeMobile,
			thtextTransform,
			thtextDecoration,
			thfontStyle,
			thBgColor,
			thHoverBgColor,
			thTextColor,
			thTextHoverColor,
			thBorderColor,
			thBorderHoverColor,
			// content
			cntnLoadGoogleFonts,
			cntnfontFamily,
			cntnfontWeight,
			cntnfontSubset,
			cntnfontSizeType,
			cntnfontSizeTypeMobile,
			cntnfontSizeTypeTablet,
			cntnfontSizeDesktop,
			cntnfontSizeMobile,
			cntnfontSizeTablet,
			cntnlineHeight,
			cntnlineHeightMobile,
			cntnlineHeightTablet,
			cntnletterSpacing,
			cntnletterSpacingMobile,
			cntnletterSpacingTablet,
			cntnletterSpacingType,
			cntnletterSpacingTypeTablet,
			cntnletterSpacingTypeMobile,
			cntntextTransform,
			cntntextDecoration,
			cntnfontStyle,
			btnPadding,
			btnPaddingMobile,
			btnPaddingTablet,
			btnMargin,
			btnMarginMobile,
			btnMarginTablet,
			btnAlignment,
			btnAlignmentMobile,
			btnAlignmentTablet,
			btnBorder,
			btnBorderType,
			btnBorderMobile,
			btnBorderTablet,
			btnBorderRadius,
			btnBorderRadiusMobile,
			btnBorderRadiusTablet,
			btnWidth,
			btnCustomWidth,
			boxShadow,
			tableStyle,
			itemRating,
			showItemRating,
			itemRatingSize,
			itemRatingSizeTablet,
			itemRatingSizeMobile,
			itemRatingSizeType,
			itemRatingSizeTypeMobile,
			itemRatingSizeTypeTablet,
			itemRatingTextColor,
			itemRatingBgColor,
			// readmore
			readMoreColor,
			readMoreHoverColor,
			readMoreText,
			readMoreLoadGoogleFonts,
			readMorefontFamily,
			readMorefontWeight,
			readMorefontSubset,
			readMorefontSizeType,
			readMorefontSizeTypeMobile,
			readMorefontSizeTypeTablet,
			readMorefontSizeDesktop,
			readMorefontSizeMobile,
			readMorefontSizeTablet,
			readMorelineHeight,
			readMorelineHeightMobile,
			readMorelineHeightTablet,
			readMoreletterSpacing,
			readMoreletterSpacingMobile,
			readMoreletterSpacingTablet,
			readMoreletterSpacingType,
			readMoreletterSpacingTypeTablet,
			readMoreletterSpacingTypeMobile,
			readMoretextTransform,
			readMoretextDecoration,
			readMorefontStyle,
			// Title 
			titleLoadGoogleFonts,
			titleTextColor,
			titlefontFamily,
			titlefontWeight,
			titlefontSubset,
			titlefontSizeType,
			titlefontSizeTypeMobile,
			titlefontSizeTypeTablet,
			titlefontSizeDesktop,
			titlefontSizeMobile,
			titlefontSizeTablet,
			titlelineHeight,
			titlelineHeightMobile,
			titlelineHeightTablet,
			titleletterSpacing,
			titleletterSpacingMobile,
			titleletterSpacingTablet,
			titleletterSpacingType,
			titleletterSpacingTypeTablet,
			titleletterSpacingTypeMobile,
			titletextTransform,
			titletextDecoration,
			titlefontStyle,
			// Badge
			showBadge,
			badgeText,
			badgeBgColor,
			badgeTextColor,
			badgeLoadGoogleFonts,
			badgefontFamily,
			badgefontWeight,
			badgefontSubset,
			badgefontSizeType,
			badgefontSizeTypeMobile,
			badgefontSizeTypeTablet,
			badgefontSizeDesktop,
			badgefontSizeMobile,
			badgefontSizeTablet,
			badgelineHeight,
			badgelineHeightMobile,
			badgelineHeightTablet,
			badgeletterSpacing,
			badgeletterSpacingMobile,
			badgeletterSpacingTablet,
			badgeletterSpacingType,
			badgeletterSpacingTypeTablet,
			badgeletterSpacingTypeMobile,
			badgetextTransform,
			badgetextDecoration,
			badgefontStyle,
			buttonIcon,
			iconPosition,
			showReadMore,
			designType,
			block,
			titleTag1,


			showImage,
			showProductTitle,
			showProductContent,
			showProductPrice,


			btnBg,
			btnBgHover,

		} } = this.props;
		const handletr = () => {
			const tdbox = [...this.props.attributes.tdbox];
			let itemPushCount = 0
			tdbox.push({

				fdprd: 'Product',
				fddetail: 'Detail',
				fdbtn: 'Price',
				fdbtnlref: 'nofollow',
				btnRel: ''
			});
			this.props.setAttributes({ tdbox });
			itemPushCount = tdbox.length
			this.props.setAttributes({ itemCount: itemPushCount });

		};
		const handleRemovetr = (index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox.splice(index, 1);
			this.props.setAttributes({ tdbox });
		};

		/**   content   ***/
		const thandletxtChange = (fdprd, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].fdprd = fdprd;
			this.props.setAttributes({ tdbox });
		};
		const handleBadgeChange = (badgeText, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].badgeText = badgeText;
			this.props.setAttributes({ tdbox });
		};
		const handleTitleChange = (titleText, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].titleText = titleText;
			this.props.setAttributes({ tdbox });
		};
		const handleRatingChange = (itemRating, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].itemRating = itemRating;
			this.props.setAttributes({ tdbox });
		};
		const thandletxtareaChange = (fddetail, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].fddetail = fddetail;
			this.props.setAttributes({ tdbox });
		};
		const thandlebtnChange = (fdbtn, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].fdbtn = fdbtn;
			this.props.setAttributes({ tdbox });
		};
		const thandlebtnurlChange = (fdbtnurl, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].fdbtnurl = fdbtnurl;
			this.props.setAttributes({ tdbox });
		};
		const thandlebtnTitle = (fdbtntitle, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].fdbtntitle = fdbtntitle;
			this.props.setAttributes({ tdbox });
		};
		const handleReadMore = (readMoreText, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].readMoreText = readMoreText;
			this.props.setAttributes({ tdbox });
		};

		const thandlebtnRel = (btnRel, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].btnRel = btnRel;
			this.props.setAttributes({ tdbox });
		};

		const saveButton = (link, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].link = link;
			this.props.setAttributes({ tdbox });
		}

		const saveButton2 = (fdbtnurl, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].fdbtnurl = fdbtnurl;
			this.props.setAttributes({ tdbox });
		}

		const handleLinks = (link, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].link = link;
			this.props.setAttributes({ tdbox });
		};

		const handleBtnRel = (btnRel, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].btnRel = btnRel;
			this.props.setAttributes({ tdbox });
		};

		const handleUrlSubmit = (selectedBox, index) => {
			this.setState({
				selectedBox: null
			})
		};

		const handleUrlSubmit1 = (selectedBox1, index) => {
			this.setState({
				selectedBox1: null
			})
		};

		const saveButton3 = (mediaURL, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].mediaURL = mediaURL;
			this.props.setAttributes({ tdbox });
		}

		const handleNewWindow = (btn_Htarget, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].btn_Htarget = btn_Htarget;
			this.props.setAttributes({ tdbox });
		};

		const linkControl = this.state.isURLPickerOpen && (

			<Popover
				position="top left"
				onClose={() => this.setState({
					isURLPickerOpen: false
				})}
			>
				<__experimentalLinkControl
					value={{ url: this.props.tdbox }}
					onChange={({
						url: newURL = '',
						opensInNewTab: newOpensInNewTab,
					}) => {
						this.props.setAttributes({ tdbox });

					}}
				/>
			</Popover>
		);

		const onSelectImage = (mediaURL, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			tdbox[index].mediaURL = mediaURL.url;
			this.props.setAttributes({ tdbox });

		};

		const onRemoveImage = (mediaURL, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			const { setAttributes } = this.props
			tdbox[index].mediaURL = mediaURL.url;
			this.props.setAttributes({ tdbox: null })
		};

		const removeImage = (mediaURL, index) => {
			const tdbox = [...this.props.attributes.tdbox];
			const newItems = tdbox.map((item, thisIndex) => {
				if (index === thisIndex) {
					item.mediaURL = ''
				}
				return item
			})
			setAttributes({
				tdbox: newItems,
			})
			this.props.setAttributes({ tdbox })
		};

		const isUrl = (url, index) => {
			if (['wbm', 'jpg', 'jpeg', 'gif', 'png', 'svg'].indexOf(url.split('.').pop().toLowerCase()) != -1) {
				return url;
			}
		};



		var element = document.getElementById("affiliate-style-" + this.props.clientId)
		if (null != element && "undefined" != typeof element) {
			element.innerHTML = affiliate_styling(this.props, "affiliate-style")
		}
		let loadbtnGoogleFonts
		let loadthGoogleFonts
		let loadcntnGoogleFonts
		let loadBadgeGoogleFonts
		let loadReadMoreGoogleFonts
		let loadTitleGoogleFonts

		// Button Google Font
		if (btnLoadGoogleFonts == true) {

			const btnconfig = {
				google: {
					families: [btnfontFamily + (btnfontWeight ? ":" + btnfontWeight : "")],
				},
			}

			loadbtnGoogleFonts = (
				<WebfontLoader config={btnconfig}>
				</WebfontLoader>
			)
		}

		// Content Google Font
		if (cntnLoadGoogleFonts == true) {

			const cntnconfig = {
				google: {
					families: [cntnfontFamily + (cntnfontWeight ? ":" + cntnfontWeight : "")],
				},
			}

			loadcntnGoogleFonts = (
				<WebfontLoader config={cntnconfig}>
				</WebfontLoader>
			)
		}

		// Table Heading Google Font
		if (thLoadGoogleFonts == true) {

			const thconfig = {
				google: {
					families: [thfontFamily + (thfontWeight ? ":" + thfontWeight : "")],
				},
			}

			loadthGoogleFonts = (
				<WebfontLoader config={thconfig}>
				</WebfontLoader>
			)
		}

		// Style 2 Title Google Font
		if (titleLoadGoogleFonts == true) {

			const titleConfig = {
				google: {
					families: [titlefontFamily + (titlefontWeight ? ":" + titlefontWeight : "")],
				},
			}

			loadTitleGoogleFonts = (
				<WebfontLoader config={titleConfig}>
				</WebfontLoader>
			)
		}

		// Badge Title Google Font
		if (badgeLoadGoogleFonts == true) {

			const badgeConfig = {
				google: {
					families: [badgefontFamily + (badgefontWeight ? ":" + badgefontWeight : "")],
				},
			}

			loadBadgeGoogleFonts = (
				<WebfontLoader config={badgeConfig}>
				</WebfontLoader>
			)
		}

		// Read More Google Font
		if (readMoreLoadGoogleFonts == true) {

			const readMoreConfig = {
				google: {
					families: [readMorefontFamily + (readMorefontWeight ? ":" + readMorefontWeight : "")],
				},
			}

			loadReadMoreGoogleFonts = (
				<WebfontLoader config={readMoreConfig}>
				</WebfontLoader>
			)
		}
		const Tag1 = titleTag1
		let trFields1, trDisplay;
		if (this.props.attributes.tdbox.length) {
			trFields1 = this.props.attributes.tdbox.map((location, index) => {
				return <Fragment key={index}>
					<div key={index} className={'table-contentbox' + ' ' + 'tdatabox-' + (index + 1)}>
						{tableStyle && tableStyle == 'tbl-style-2' &&
							<Fragment>
								<div className="components-panel_main_title">
									<b>{'Row Content-' + (index + 1)}</b>
								</div>
								<div className="components_panel_title readmore_link_input"><b>{__('Read More item ' + (index + 1)) + ' URL'}</b>
									<URLInput
										value={this.props.attributes.tdbox[index].link}
										onChange={(link) => saveButton(link, index)}
									/>
								</div>
							</Fragment>
						}
						<div className="components-panel_main_title">
							<b>{'Row Content-' + (index + 1)}</b>
						</div>
					</div>
				</Fragment>;
			});

			// Display in Editor
			trDisplay = this.props.attributes.tdbox.map((location, index) => {
				let newrel;
				if (!this.props.attributes.tdbox[index].btn_Htarget) {
					newrel = btnRel
				} else if (!this.props.attributes.tdbox[index].btn_Htarget && btnRel === NEW_TAB_REL) {
					newrel = btnRel
				} else if (this.props.attributes.tdbox[index].btn_Htarget && !this.props.attributes.tdbox[index].btnRel) {
					newrel = NEW_TAB_REL
				} else {
					newrel = NEW_TAB_REL + ' ' + this.props.attributes.tdbox[index].btnRel
				}
				function removeDuplicateWords(s) {
					if (undefined !== s) {
						var str = s.split(" ");
						var result = [];
						for (var i = 0; i < str.length; i++) {
							if (result.indexOf(str[i]) === -1) {
								result.push(str[i]);
							}
						}
						return result.join(" ");
					}
				}
				if (undefined !== newrel) {
					newrel = removeDuplicateWords(newrel).trim()
				}

				return <tr key={index}>
					{tableStyle && tableStyle == 'tbl-style-1' && showImage && showImage == true &&
						<td data-column={th1_txt} className="affiliate-col-img" style={{ width: `${!!th1_width ? th1_width : 25}%` }}>

							<MediaUpload
								onSelect={(mediaURL) => onSelectImage(mediaURL, index)}
								value={this.props.attributes.tdbox[index].mediaURL}
								className="affiliate-cmpr-img"
								render={({ open }) => (
									<Button className="block-editor-button-block-appender" onClick={open}>
										<img src={this.props.attributes.tdbox[index].mediaURL} />
										{__("Add-Image")}
									</Button>
								)}
							/>

							<span className="insertfromurl" onMouseEnter={() => this.setState({ selectedBox1: index })}>Insert from URL</span>

							{this.state.selectedBox1 == index &&
								<Fragment>
									<div className={'components-base-control__field'}>
										<URLPopover
										>
											<div className={'affiliate-url-input'}
												onMouseLeave={() => this.setState({ selectedBox1: null })}
											>
												<form
													onSubmit={(selectedBox1) => handleUrlSubmit1(selectedBox1, index)}
												>
													<URLInput placeholder={__('https://www.example.com')}
														className="button-url"
														value={this.props.attributes.tdbox[index].mediaURL}
														onChange={(mediaURL) => saveButton3(mediaURL, index)}
														autoFocus={false}
														__nextHasNoMarginBottom
													/>
													<Button
														icon={keyboardReturn}
														label={__('Apply')}
														type="submit"
													/>
												</form>

											</div>

										</URLPopover>
									</div>
								</Fragment>
							}

							{tableStyle && tableStyle == 'tbl-style-2' && showItemRating == true &&
								<RichText
									label={__('Add Rating')}
									className="affiliate-img-rating"
									placeholder="9.5"
									value={this.props.attributes.tdbox[index].itemRating}
									onChange={(itemRating) => handleRatingChange(itemRating, index)}
								/>
							}
						</td>
					}
					{tableStyle && tableStyle == 'tbl-style-2' && showImage && showImage == true &&
						<td data-column={th1_txt} className="affiliate-col-img" style={{ width: `${!!th1_width_2 ? th1_width_2 : 25}%` }}>

							<MediaUpload
								onSelect={(mediaURL) => onSelectImage(mediaURL, index)}
								value={this.props.attributes.tdbox[index].mediaURL}
								className="affiliate-cmpr-img"
								render={({ open }) => (
									<Button className="block-editor-button-block-appender" onClick={open}>
										<img src={this.props.attributes.tdbox[index].mediaURL} />
										{__("Add-Image")}
									</Button>
								)}
							/>

							<span className="insertfromurl" onMouseEnter={() => this.setState({ selectedBox1: index })}>Insert from URL</span>

							{this.state.selectedBox1 == index &&
								<Fragment>
									<div className={'components-base-control__field'}>
										<URLPopover
										>
											<div className={'affiliate-url-input'}
												onMouseLeave={() => this.setState({ selectedBox1: null })}
											>
												<form
													onSubmit={(selectedBox1) => handleUrlSubmit1(selectedBox1, index)}
												>
													<URLInput placeholder={__('https://www.example.com')}
														className="button-url"
														value={this.props.attributes.tdbox[index].mediaURL}
														onChange={(mediaURL) => saveButton3(mediaURL, index)}
														autoFocus={false}
														__nextHasNoMarginBottom
													/>
													<Button
														icon={keyboardReturn}
														label={__('Apply')}
														type="submit"
													/>
												</form>

											</div>

										</URLPopover>
									</div>
								</Fragment>
							}

							{tableStyle && tableStyle == 'tbl-style-2' && showItemRating == true &&
								<RichText
									label={__('Add Rating')}
									className="affiliate-img-rating"
									placeholder="9.5"
									value={this.props.attributes.tdbox[index].itemRating}
									onChange={(itemRating) => handleRatingChange(itemRating, index)}
								/>
							}
						</td>
					}

					{tableStyle && tableStyle == 'tbl-style-1' && showProductTitle && showProductTitle == true &&
						<td data-column={th2_txt} className="affiliate-col-ct1" style={{ width: `${!!th2_width ? th2_width : 25}%` }}>

							{tableStyle && tableStyle == 'tbl-style-1' &&
								<RichText
									tagName={Tag1}
									label={__('Add Title')}
									className={"affiliate-txtbox-title"}
									placeholder={__("Product Title")}
									id={`getSlug(this.props.attributes.tdbox[ index ].fdprd)`}
									value={this.props.attributes.tdbox[index].fdprd}
									onChange={(fdprd) => thandletxtChange(fdprd, index)}
									allowedFormats={["bold", "italic", "strikethrough"]}
								/>
							}
						</td>
					}
					{tableStyle && tableStyle == 'tbl-style-2' && showProductTitle && showProductTitle == true &&
						<td data-column={th2_txt} className="affiliate-col-ct1" style={{ width: `${!!th2_width_2 ? th2_width_2 : 25}%` }}>
							<Tag1 className="affiliate-txtbox-title">
								<Fragment>
									<RichText
										tagName={'span'}
										label={__('Add Title')}
										placeholder={__("Item Title")}
										className={`affiliate-txtbox-title`}
										id={`getSlug(this.props.attributes.tdbox[ index ].fdprd)`}
										value={this.props.attributes.tdbox[index].fdprd}
										onChange={(fdprd) => thandletxtChange(fdprd, index)}
										allowedFormats={["bold", "italic", "strikethrough"]}
									/>
									<RichText
										tagName={'span'}
										label={__('Add Badge')}
										className="affiliate-item-badge"
										placeholder={__("Item Badge")}
										value={this.props.attributes.tdbox[index].badgeText}
										onChange={(badgeText) => handleBadgeChange(badgeText, index)}
										allowedFormats={["bold", "italic", "strikethrough"]}
									/>
								</Fragment>
							</Tag1>
							<RichList
								key={`tbl-style-2-${index}`}
								value={getListValue(this.props.attributes.tdbox[index].fddetail)}
								onChange={ (updateValue) => {
									const newValue = setListValue(updateValue)
									thandletxtareaChange(newValue, index);
								} }
								className="fd-txtbox-title"
								editProps={this.props}
								placeholder="Item Descirption"
							/>

							{/* {tableStyle && tableStyle == 'tbl-style-1' &&
								<RichText
									tagName={Tag1}
									label={__('Add Title')}
									className={"affiliate-txtbox-title"}
									placeholder={__("Item Description")}
									id={`getSlug(this.props.attributes.tdbox[ index ].fdprd)`}
									value={this.props.attributes.tdbox[index].fdprd}
									onChange={(fdprd) => thandletxtChange(fdprd, index)}
									allowedFormats={["bold", "italic", "strikethrough"]}
								/>
							} */}
						</td>
					}

					{tableStyle && tableStyle == 'tbl-style-1' && showProductContent && showProductContent == true &&
						<Fragment>
							{tableStyle && tableStyle == 'tbl-style-1' &&
								<td data-column={th3_txt} className="affiliate-col-ct2" style={{ width: `${!!th3_width ? th3_width : 25}%` }}>
									<RichList
										key={`tbl-style-1-${index}`}
										value={getListValue(this.props.attributes.tdbox[index].fddetail)}
										onChange={ (updateValue) => {
											const newValue = setListValue(updateValue)
											thandletxtareaChange(newValue, index);
										} }
										className="fd-txtbox-title"
										editProps={this.props}
										placeholder="Item Descirption"
									/>
								</td>
							}

							
						</Fragment>
					}
					{tableStyle && tableStyle == 'tbl-style-2' && showProductContent && showProductContent == true &&
						<Fragment>
							{tableStyle && tableStyle == 'tbl-style-2' &&

								<td data-column={th4_txt} className="affiliate-col-btn" style={{ width: `${!!th3_width_2 ? th3_width_2 : 25}%` }}>
									<span className={`affiliate-compr-btn ${btnWidth} ${btn_Style}   ${enableGlimmerEffect ? 'glimmer-effect' : ''}`}
										onMouseEnter={() => this.setState({ selectedBox: index })}
									>

										{iconPosition && iconPosition == 'af-icon-is-left' &&
											<i className={`${buttonIcon} affiliate-abbtn-icon ${iconPosition}`}></i>
										}
										<RichText
											tagName={'span'}
											value={this.props.attributes.tdbox[index].fdbtn}
											placeholder={__('Button Text')}
											onChange={(fdbtn) => thandlebtnChange(fdbtn, index)}
											allowedFormats={["bold", "italic", "strikethrough"]}
											{...(this.props.attributes.tdbox[index].btn_Htarget ? { ...this.props.attributes.tdbox[index].btn_Htarget && { rel: newrel } } : { rel: newrel })}
										/>
										{this.state.selectedBox == index &&
											<Fragment>
												<div className={'components-base-control__field'}>
													<URLPopover

													>
														<div className={'affiliate-url-input'}
															onMouseLeave={() => this.setState({ selectedBox: null })}
														>
															<form
																onSubmit={(selectedBox) => handleUrlSubmit(selectedBox, index)}
															>
																<URLInput placeholder={__('https://www.example.com')}
																	className="button-url"
																	value={this.props.attributes.tdbox[index].fdbtnurl}
																	onChange={(fdbtnurl) => saveButton2(fdbtnurl, index)}
																	autoFocus={false}
																	__nextHasNoMarginBottom
																/>
																<Button
																	icon={keyboardReturn}
																	label={__('Apply')}
																	type="submit"
																/>
															</form>
															<ToggleControl
																label={__('Open in new tab')}
																checked={this.props.attributes.tdbox[index].btn_Htarget}
																onChange={(btn_Htarget) => handleNewWindow(btn_Htarget, index)}
															/>
															<label className={`affiliate-rel-btn-label`}>{__('Link Rel')}</label>
															<TextControl
																value={this.props.attributes.tdbox[index].btnRel}
																onChange={(btnRel) => handleBtnRel(btnRel, index)}
															/>
														</div>

													</URLPopover>
												</div>
											</Fragment>
										}
										{iconPosition && iconPosition == 'af-icon-is-right' &&
											<i className={`${buttonIcon} affiliate-abbtn-icon ${iconPosition}`}></i>
										}
									</span>
									{tableStyle && tableStyle == 'tbl-style-2' && showReadMore == true &&
										<Fragment>
											<RichText
												tagName={'span'}
												href={this.props.attributes.tdbox[index].link}
												value={this.props.attributes.tdbox[index].readMoreText}
												placeholder={__('Read Our Review')}
												className={`affiliate-cmpr-readmore`}
												onChange={(readMoreText) => handleReadMore(readMoreText, index)}
											/>

										</Fragment>
									}
								</td>

							}
						</Fragment>
					}

					{tableStyle && tableStyle == 'tbl-style-1' && showProductPrice && showProductPrice == true &&
						<td data-column={th4_txt} className="affiliate-col-btn" style={{ width: `${!!th4_width ? th4_width : 25}%` }}>
							<span className={`affiliate-compr-btn ${btnWidth} ${btn_Style}   ${enableGlimmerEffect ? 'glimmer-effect' : ''}`}
								onMouseEnter={() => this.setState({ selectedBox: index })}
							>

								{iconPosition && iconPosition == 'af-icon-is-left' &&
									<i className={`${buttonIcon} affiliate-abbtn-icon ${iconPosition}`}></i>
								}
								<RichText
									tagName={'span'}
									value={this.props.attributes.tdbox[index].fdbtn}
									placeholder={__('Button Text')}
									onChange={(fdbtn) => thandlebtnChange(fdbtn, index)}
									allowedFormats={["bold", "italic", "strikethrough"]}
									{...(this.props.attributes.tdbox[index].btn_Htarget ? { ...this.props.attributes.tdbox[index].btn_Htarget && { rel: newrel } } : { rel: newrel })}
								/>
								{this.state.selectedBox == index &&
									<Fragment>
										<div className={'components-base-control__field'}>
											<URLPopover

											>
												<div className={'affiliate-url-input'}
													onMouseLeave={() => this.setState({ selectedBox: null })}
												>
													<form
														onSubmit={(selectedBox) => handleUrlSubmit(selectedBox, index)}
													>
														<URLInput placeholder={__('https://www.example.com')}
															className="button-url"
															value={this.props.attributes.tdbox[index].fdbtnurl}
															onChange={(fdbtnurl) => saveButton2(fdbtnurl, index)}
															autoFocus={false}
															__nextHasNoMarginBottom
														/>
														<Button
															icon={keyboardReturn}
															label={__('Apply')}
															type="submit"
														/>
													</form>
													<ToggleControl
														label={__('Open in new tab')}
														checked={this.props.attributes.tdbox[index].btn_Htarget}
														onChange={(btn_Htarget) => handleNewWindow(btn_Htarget, index)}
													/>
													<label className={`affiliate-rel-btn-label`}>{__('Link Rel')}</label>
													<TextControl
														value={this.props.attributes.tdbox[index].btnRel}
														onChange={(btnRel) => handleBtnRel(btnRel, index)}
													/>
												</div>

											</URLPopover>
										</div>
									</Fragment>
								}
								{iconPosition && iconPosition == 'af-icon-is-right' &&
									<i className={`${buttonIcon} affiliate-abbtn-icon ${iconPosition}`}></i>
								}
							</span>
						</td>
					}
					
					<td className={`fd_remove-bullet-items`}>
						{/* <IconButton
							className="fd_remove-bullet-btn"
							icon="no-alt"
							label="Delete row"
							onClick={() => handleRemovetr(index)}
						/> */}
						<Button
							className="fd_remove-bullet-btn"
							icon="no-alt"
							label="Delete row"
							onClick={() => handleRemovetr(index)}
						/>
					</td>
				</tr>;
			});
		}

		return [
			<Fragment>
				<InspectorControls key="inspector">

					<PanelBody title={`General Settings`} initialOpen={false} icon={getIcon('documentation')} className={'abblocks-panel-label'}>

						<Select
							label={__('Table Style')}
							options={[['tbl-style-1', __('Style 1')], ['tbl-style-2', __('Style 2')]]}
							value={tableStyle}
							onChange={(value) => setAttributes({ tableStyle: value })}
						/>

						<ToggleControl
							label={__('Show Column One')}
							checked={showImage}
							onChange={() => setAttributes({ showImage: !showImage })}
						/>

						{tableStyle && tableStyle == 'tbl-style-1' && showImage && showImage == true &&
							<Fragment>
								<label class="components-base-control__label"><b>{__('Set the width of Column One')}</b></label>
								<TextControl
									value={th1_width}
									onChange={(value) => setAttributes({ th1_width: value })}
								/>
							</Fragment>
						}
						{tableStyle && tableStyle == 'tbl-style-2' && showImage && showImage == true &&
							<Fragment>
								<label class="components-base-control__label"><b>{__('Set the width of Column One')}</b></label>
								<TextControl
									value={th1_width_2}
									onChange={(value) => setAttributes({ th1_width_2: value })}
								/>
							</Fragment>
						}

						<ToggleControl
							label={__('Show Column Two')}
							checked={showProductTitle}
							onChange={() => setAttributes({ showProductTitle: !showProductTitle })}
						/>

						{tableStyle && tableStyle == 'tbl-style-1' && showProductTitle && showProductTitle == true &&
							<Fragment>
								<label class="components-base-control__label"><b>{__('Set the width of Column Two')}</b></label>
								<TextControl
									value={th2_width}
									onChange={(value) => setAttributes({ th2_width: value })}
								/>
							</Fragment>
						}

						{tableStyle && tableStyle == 'tbl-style-2' && showProductTitle && showProductTitle == true &&
							<Fragment>
								<label class="components-base-control__label"><b>{__('Set the width of Column Two')}</b></label>
								<TextControl
									value={th2_width_2}
									onChange={(value) => setAttributes({ th2_width_2: value })}
								/>
							</Fragment>
						}
						
							<ToggleControl
								label={__('Show Column Three')}
								checked={showProductContent}
								onChange={() => setAttributes({ showProductContent: !showProductContent })}
							/>
						
						{tableStyle && tableStyle == 'tbl-style-1' && showProductContent && showProductContent == true &&
							<Fragment>
								<label class="components-base-control__label"><b>{__('Set the width of Column Three')}</b></label>
								<TextControl
									value={th3_width}
									onChange={(value) => setAttributes({ th3_width: value })}
								/>
							</Fragment>
						}
						{tableStyle && tableStyle == 'tbl-style-2' && showProductContent && showProductContent == true &&
							<Fragment>
								<label class="components-base-control__label"><b>{__('Set the width of Column Three')}</b></label>
								<TextControl
									value={th3_width_2}
									onChange={(value) => setAttributes({ th3_width_2: value })}
								/>
							</Fragment>
						}


						{tableStyle && tableStyle == 'tbl-style-1' &&
							<ToggleControl
								label={__('Show Column Four')}
								checked={showProductPrice}
								onChange={() => setAttributes({ showProductPrice: !showProductPrice })}
							/>
						}
						
						{tableStyle && tableStyle == 'tbl-style-1' && showProductPrice && showProductPrice == true &&
							<Fragment>
								<label class="components-base-control__label"><b>{__('Set the width of Column Four')}</b></label>
								<TextControl
									value={th4_width}
									onChange={(value) => setAttributes({ th4_width: value })}
								/>
							</Fragment>
						}

						
						{showImage && showImage == true &&
							<Fragment>
								<label class="components-base-control__label"><b>{__('Header - 1')}</b></label>
								<TextControl
									value={th1_txt}
									onChange={(value) => setAttributes({ th1_txt: value })}
								/>
							</Fragment>
						}



						{showProductTitle && showProductTitle == true &&
							<Fragment>
								<label class="components-base-control__label"><b>{__('Header - 2')}</b></label>
								<TextControl
									value={th2_txt}
									onChange={(value) => setAttributes({ th2_txt: value })}
								/>
							</Fragment>
						}
						{tableStyle && tableStyle == 'tbl-style-1' && showProductContent && showProductContent == true &&
							<Fragment>
								<label class="components-base-control__label"><b>{__('Header - 3')}</b></label>
								<TextControl
									value={th3_txt}
									onChange={(value) => setAttributes({ th3_txt: value })}
								/>
							</Fragment>
						}

						{showProductPrice && showProductPrice == true &&
							<Fragment>
								<label class="components-base-control__label"><b>{__('Header - 4')}</b></label>
								<TextControl
									value={th4_txt}
									onChange={(value) => setAttributes({ th4_txt: value })}
								/>
							</Fragment>
						}
						{tableStyle && tableStyle == 'tbl-style-2' &&
							<Fragment>
								<ToggleControl
									label={__('Show Rating')}
									checked={showItemRating}
									onChange={() => setAttributes({ showItemRating: !showItemRating })}
								/>
								<ToggleControl
									label={__('Show Read More')}
									checked={showReadMore}
									onChange={() => setAttributes({ showReadMore: !showReadMore })}
								/>
							</Fragment>
						}

						<BoxShadow label={__('Box-Shadow')} value={boxShadow} onChange={(value) => setAttributes({ boxShadow: value })} />
					</PanelBody>

					<PanelBody title={__('Typography')} initialOpen={false} icon={getIcon('typography')} className={'abblocks-panel-label'}>
						<SelectControl
							label={__('Title Heading Tag')}
							value={titleTag1}
							options={[
								{ value: 'h2', label: 'Heading 2 (h2)' },
								{ value: 'h3', label: 'Heading 3 (h3)' },
								{ value: 'h4', label: 'Heading 4 (h4)' },
								{ value: 'h5', label: 'Heading 5 (h5)' },
								{ value: 'h6', label: 'Heading 6 (h6)' },
								{ value: 'p', label: 'Paragraph (p)' },
							]}
							onChange={(value) => setAttributes({ titleTag1: value })}
						/>

						<TypographyControl
							label={__("Header Typography")}
							attributes={attributes}
							setAttributes={setAttributes}
							loadGoogleFonts={{ value: thLoadGoogleFonts, label: __("thLoadGoogleFonts") }}
							fontFamily={{ value: thfontFamily, label: __("thfontFamily") }}
							fontWeight={{ value: thfontWeight, label: __("thfontWeight") }}
							fontSubset={{ value: thfontSubset, label: __("thfontSubset") }}
							fontSizeType={{ value: thfontSizeType, label: __("thfontSizeType") }}
							fontSizeTypeMobile={{ value: thfontSizeTypeMobile, label: __("thfontSizeTypeMobile") }}
							fontSizeTypeTablet={{ value: thfontSizeTypeTablet, label: __("thfontSizeTypeTablet") }}
							fontSize={{ value: thfontSizeDesktop, label: __("thfontSizeDesktop") }}
							fontSizeMobile={{ value: thfontSizeMobile, label: __("thfontSizeMobile") }}
							fontSizeTablet={{ value: thfontSizeTablet, label: __("thfontSizeTablet") }}
							lineHeightType={{ value: '', label: __(" ") }}
							lineHeight={{ value: thlineHeight, label: __("thlineHeight") }}
							lineHeightMobile={{ value: thlineHeightMobile, label: __("thlineHeightMobile") }}
							lineHeightTablet={{ value: thlineHeightTablet, label: __("thlineHeightTablet") }}
							letterSpacing={{ value: thletterSpacing, label: __("thletterSpacing") }}
							letterSpacingMobile={{ value: thletterSpacingMobile, label: __("thletterSpacingMobile") }}
							letterSpacingTablet={{ value: thletterSpacingTablet, label: __("thletterSpacingTablet") }}
							letterSpacingType={{ value: thletterSpacingType, label: __("thletterSpacingType") }}
							letterSpacingTypeTablet={{ value: thletterSpacingTypeTablet, label: __("thletterSpacingTypeTablet") }}
							letterSpacingTypeMobile={{ value: thletterSpacingTypeMobile, label: __("thletterSpacingTypeMobile") }}
							textTransform={thtextTransform}
							onTextTransform={(value) => setAttributes({ thtextTransform: value })}
							textDecoration={thtextDecoration}
							onTextDecoration={(value) => setAttributes({ thtextDecoration: value })}
							fontStyle={thfontStyle}
							onFontStyle={(value) => setAttributes({ thfontStyle: value })}
						/>
						<TypographyControl
							label={__("Title Typography")}
							attributes={attributes}
							setAttributes={setAttributes}
							loadGoogleFonts={{ value: titleLoadGoogleFonts, label: __("titleLoadGoogleFonts") }}
							fontFamily={{ value: titlefontFamily, label: __("titlefontFamily") }}
							fontWeight={{ value: titlefontWeight, label: __("titlefontWeight") }}
							fontSubset={{ value: titlefontSubset, label: __("titlefontSubset") }}
							fontSizeType={{ value: titlefontSizeType, label: __("titlefontSizeType") }}
							fontSizeTypeMobile={{ value: titlefontSizeTypeMobile, label: __("titlefontSizeTypeMobile") }}
							fontSizeTypeTablet={{ value: titlefontSizeTypeTablet, label: __("titlefontSizeTypeTablet") }}
							fontSize={{ value: titlefontSizeDesktop, label: __("titlefontSizeDesktop") }}
							fontSizeMobile={{ value: titlefontSizeMobile, label: __("titlefontSizeMobile") }}
							fontSizeTablet={{ value: titlefontSizeTablet, label: __("titlefontSizeTablet") }}
							lineHeightType={{ value: '', label: __(" ") }}
							lineHeight={{ value: titlelineHeight, label: __("titlelineHeight") }}
							lineHeightMobile={{ value: titlelineHeightMobile, label: __("titlelineHeightMobile") }}
							lineHeightTablet={{ value: titlelineHeightTablet, label: __("titlelineHeightTablet") }}
							letterSpacing={{ value: titleletterSpacing, label: __("titleletterSpacing") }}
							letterSpacingMobile={{ value: titleletterSpacingMobile, label: __("titleletterSpacingMobile") }}
							letterSpacingTablet={{ value: titleletterSpacingTablet, label: __("titleletterSpacingTablet") }}
							letterSpacingType={{ value: titleletterSpacingType, label: __("titleletterSpacingType") }}
							letterSpacingTypeTablet={{ value: titleletterSpacingTypeTablet, label: __("titleletterSpacingTypeTablet") }}
							letterSpacingTypeMobile={{ value: titleletterSpacingTypeMobile, label: __("titleletterSpacingTypeMobile") }}
							textTransform={titletextTransform}
							onTextTransform={(value) => setAttributes({ titletextTransform: value })}
							textDecoration={titletextDecoration}
							onTextDecoration={(value) => setAttributes({ titletextDecoration: value })}
							fontStyle={titlefontStyle}
							onFontStyle={(value) => setAttributes({ titlefontStyle: value })}
						/>
						<TypographyControl
							label={__("Content Typography")}
							attributes={attributes}
							setAttributes={setAttributes}
							loadGoogleFonts={{ value: cntnLoadGoogleFonts, label: __("cntnLoadGoogleFonts") }}
							fontFamily={{ value: cntnfontFamily, label: __("cntnfontFamily") }}
							fontWeight={{ value: cntnfontWeight, label: __("cntnfontWeight") }}
							fontSubset={{ value: cntnfontSubset, label: __("cntnfontSubset") }}
							fontSizeType={{ value: cntnfontSizeType, label: __("cntnfontSizeType") }}
							fontSizeTypeMobile={{ value: cntnfontSizeTypeMobile, label: __("cntnfontSizeTypeMobile") }}
							fontSizeTypeTablet={{ value: cntnfontSizeTypeTablet, label: __("cntnfontSizeTypeTablet") }}
							fontSize={{ value: cntnfontSizeDesktop, label: __("cntnfontSizeDesktop") }}
							fontSizeMobile={{ value: cntnfontSizeMobile, label: __("cntnfontSizeMobile") }}
							fontSizeTablet={{ value: cntnfontSizeTablet, label: __("cntnfontSizeTablet") }}
							lineHeightType={{ value: '', label: __(" ") }}
							lineHeight={{ value: cntnlineHeight, label: __("cntnlineHeight") }}
							lineHeightMobile={{ value: cntnlineHeightMobile, label: __("cntnlineHeightMobile") }}
							lineHeightTablet={{ value: cntnlineHeightTablet, label: __("cntnlineHeightTablet") }}
							letterSpacing={{ value: cntnletterSpacing, label: __("cntnletterSpacing") }}
							letterSpacingMobile={{ value: cntnletterSpacingMobile, label: __("cntnletterSpacingMobile") }}
							letterSpacingTablet={{ value: cntnletterSpacingTablet, label: __("cntnletterSpacingTablet") }}
							letterSpacingType={{ value: cntnletterSpacingType, label: __("cntnletterSpacingType") }}
							letterSpacingTypeTablet={{ value: cntnletterSpacingTypeTablet, label: __("cntnletterSpacingTypeTablet") }}
							letterSpacingTypeMobile={{ value: cntnletterSpacingTypeMobile, label: __("cntnletterSpacingTypeMobile") }}
							textTransform={cntntextTransform}
							onTextTransform={(value) => setAttributes({ cntntextTransform: value })}
							textDecoration={cntntextDecoration}
							onTextDecoration={(value) => setAttributes({ cntntextDecoration: value })}
							fontStyle={cntnfontStyle}
							onFontStyle={(value) => setAttributes({ cntnfontStyle: value })}
						/>

						<TypographyControl
							label={__("Badge Typography")}
							attributes={attributes}
							setAttributes={setAttributes}
							loadGoogleFonts={{ value: badgeLoadGoogleFonts, label: __("badgeLoadGoogleFonts") }}
							fontFamily={{ value: badgefontFamily, label: __("badgefontFamily") }}
							fontWeight={{ value: badgefontWeight, label: __("badgefontWeight") }}
							fontSubset={{ value: badgefontSubset, label: __("badgefontSubset") }}
							fontSizeType={{ value: badgefontSizeType, label: __("badgefontSizeType") }}
							fontSizeTypeMobile={{ value: badgefontSizeTypeMobile, label: __("badgefontSizeTypeMobile") }}
							fontSizeTypeTablet={{ value: badgefontSizeTypeTablet, label: __("badgefontSizeTypeTablet") }}
							fontSize={{ value: badgefontSizeDesktop, label: __("badgefontSizeDesktop") }}
							fontSizeMobile={{ value: badgefontSizeMobile, label: __("badgefontSizeMobile") }}
							fontSizeTablet={{ value: badgefontSizeTablet, label: __("badgefontSizeTablet") }}
							lineHeightType={{ value: '', label: __(" ") }}
							lineHeight={{ value: badgelineHeight, label: __("badgelineHeight") }}
							lineHeightMobile={{ value: badgelineHeightMobile, label: __("badgelineHeightMobile") }}
							lineHeightTablet={{ value: badgelineHeightTablet, label: __("badgelineHeightTablet") }}
							letterSpacing={{ value: badgeletterSpacing, label: __("badgeletterSpacing") }}
							letterSpacingMobile={{ value: badgeletterSpacingMobile, label: __("badgeletterSpacingMobile") }}
							letterSpacingTablet={{ value: badgeletterSpacingTablet, label: __("badgeletterSpacingTablet") }}
							letterSpacingType={{ value: badgeletterSpacingType, label: __("badgeletterSpacingType") }}
							letterSpacingTypeTablet={{ value: badgeletterSpacingTypeTablet, label: __("badgeletterSpacingTypeTablet") }}
							letterSpacingTypeMobile={{ value: badgeletterSpacingTypeMobile, label: __("badgeletterSpacingTypeMobile") }}
							textTransform={badgetextTransform}
							onTextTransform={(value) => setAttributes({ badgetextTransform: value })}
							textDecoration={badgetextDecoration}
							onTextDecoration={(value) => setAttributes({ badgetextDecoration: value })}
							fontStyle={badgefontStyle}
							onFontStyle={(value) => setAttributes({ badgefontStyle: value })}
						/>
						<TypographyControl
							label={__("Readmore Typography")}
							attributes={attributes}
							setAttributes={setAttributes}
							loadGoogleFonts={{ value: readMoreLoadGoogleFonts, label: __("readMoreLoadGoogleFonts") }}
							fontFamily={{ value: readMorefontFamily, label: __("readMorefontFamily") }}
							fontWeight={{ value: readMorefontWeight, label: __("readMorefontWeight") }}
							fontSubset={{ value: readMorefontSubset, label: __("readMorefontSubset") }}
							fontSizeType={{ value: readMorefontSizeType, label: __("readMorefontSizeType") }}
							fontSizeTypeMobile={{ value: readMorefontSizeTypeMobile, label: __("readMorefontSizeTypeMobile") }}
							fontSizeTypeTablet={{ value: readMorefontSizeTypeTablet, label: __("readMorefontSizeTypeTablet") }}
							fontSize={{ value: readMorefontSizeDesktop, label: __("readMorefontSizeDesktop") }}
							fontSizeMobile={{ value: readMorefontSizeMobile, label: __("readMorefontSizeMobile") }}
							fontSizeTablet={{ value: readMorefontSizeTablet, label: __("readMorefontSizeTablet") }}
							lineHeightType={{ value: '', label: __(" ") }}
							lineHeight={{ value: readMorelineHeight, label: __("readMorelineHeight") }}
							lineHeightMobile={{ value: readMorelineHeightMobile, label: __("readMorelineHeightMobile") }}
							lineHeightTablet={{ value: readMorelineHeightTablet, label: __("readMorelineHeightTablet") }}
							letterSpacing={{ value: readMoreletterSpacing, label: __("readMoreletterSpacing") }}
							letterSpacingMobile={{ value: readMoreletterSpacingMobile, label: __("readMoreletterSpacingMobile") }}
							letterSpacingTablet={{ value: readMoreletterSpacingTablet, label: __("readMoreletterSpacingTablet") }}
							letterSpacingType={{ value: readMoreletterSpacingType, label: __("readMoreletterSpacingType") }}
							letterSpacingTypeTablet={{ value: readMoreletterSpacingTypeTablet, label: __("readMoreletterSpacingTypeTablet") }}
							letterSpacingTypeMobile={{ value: readMoreletterSpacingTypeMobile, label: __("readMoreletterSpacingTypeMobile") }}
							textTransform={readMoretextTransform}
							onTextTransform={(value) => setAttributes({ readMoretextTransform: value })}
							textDecoration={readMoretextDecoration}
							onTextDecoration={(value) => setAttributes({ readMoretextDecoration: value })}
							fontStyle={readMorefontStyle}
							onFontStyle={(value) => setAttributes({ readMorefontStyle: value })}
						/>
					</PanelBody>

					<PanelBody title={__('Colors')} initialOpen={false} icon={getIcon('colors')} className={'abblocks-panel-label'}>
						<Color label={__('Header Background Color')} disableAlpha value={HBgColor} onChange={val => setAttributes({ HBgColor: val })} />
						<Color label={__('Content title Color')} disableAlpha value={titleTextColor} onChange={val => setAttributes({ titleTextColor: val })} />
						<Color label={__('Header Text Color')} disableAlpha value={HTxtColor} onChange={val => setAttributes({ HTxtColor: val })} />
						<Color label={__('Content Text Color')} disableAlpha value={tdTxtColor} onChange={val => setAttributes({ tdTxtColor: val })} />
						<Color label={__('Table Odd-Row Color')} disableAlpha value={tOddColor} onChange={val => setAttributes({ tOddColor: val })} />
						<Color label={__('Table Even-Row Color')} disableAlpha value={tEvenColor} onChange={val => setAttributes({ tEvenColor: val })} />

						<Color label={__('Badge Text Color')} disableAlpha value={badgeTextColor} onChange={val => setAttributes({ badgeTextColor: val })} />
						<Color label={__('Badge Background Color')} disableAlpha value={badgeBgColor} onChange={val => setAttributes({ badgeBgColor: val })} />
						<Color label={__('Reeadmore Text Color')} disableAlpha value={readMoreColor} onChange={val => setAttributes({ readMoreColor: val })} />
						<Color label={__('Rating Text Color')} disableAlpha value={itemRatingTextColor} onChange={val => setAttributes({ itemRatingTextColor: val })} />
						<Color label={__('Rating Background Color')} disableAlpha value={itemRatingBgColor} onChange={val => setAttributes({ itemRatingBgColor: val })} />
					</PanelBody>

					<PanelBody title={__('Button Settings')} initialOpen={false} icon={getIcon('button')} className={'abblocks-panel-label'}>

						<Dropdown
							className="affiliate-icon-popover"
							contentClassName="my-popover-content-classname"
							popoverProps={ { placement: 'bottom-start' } }
							renderToggle={({ isOpen, onToggle }) => (
								<Fragment>
									<h2>{__('Tag Icon')}</h2>
									<Button className="affiliate-size-btn" onClick={onToggle} aria-expanded={isOpen}>
										<Dashicon icon="admin-tools" />
									</Button>
								</Fragment>
							)}
							renderContent={() => (
								<div className="affiliate-iconlist-popwrapper">
									<IconList value={buttonIcon} disableToggle onChange={val => setAttributes({ buttonIcon: val })} />
								</div>
							)}
						/>
						<RadioAdvanced
							label={__('Icon Position')}
							options={[
								{ label: 'Left', value: 'af-icon-is-left', title: 'Left' },
								{ label: 'Right', value: 'af-icon-is-right', title: 'Right' },
							]}
							value={iconPosition}
							onChange={(value) => setAttributes({ iconPosition: value })} />
						<ToggleControl
							label={__('Open link in new Tab')}
							checked={btn_Htarget}
							onChange={() => setAttributes({ btn_Htarget: !btn_Htarget })}
						/>

						<ToggleControl
							label={__('Enable Glimmer Effect')}
							checked={enableGlimmerEffect}
							onChange={() => setAttributes({ enableGlimmerEffect: !enableGlimmerEffect })}
						/>

						<RadioAdvanced
							label={__('Button width')}
							options={[
								{ label: 'Full Width', value: 'af-is-fullw', title: 'Full Width' },
								{ label: 'Custom', value: 'af-is-custom', title: 'Custom' },
							]}
							value={btnWidth}
							onChange={(value) => setAttributes({ btnWidth: value })} />
						{btnWidth == 'af-is-custom' &&
							<Range label={__('Custom Width')} value={btnCustomWidth} onChange={(value) => setAttributes({ btnCustomWidth: value })} min={0} max={1000} unit={['']} />
						}
						<TypographyControl
							label={__("Button Typography")}
							attributes={attributes}
							setAttributes={setAttributes}
							loadGoogleFonts={{ value: btnLoadGoogleFonts, label: __("btnLoadGoogleFonts") }}
							fontFamily={{ value: btnfontFamily, label: __("btnfontFamily") }}
							fontWeight={{ value: btnfontWeight, label: __("btnfontWeight") }}
							fontSubset={{ value: btnfontSubset, label: __("btnfontSubset") }}
							fontSizeType={{ value: btnfontSizeType, label: __("btnfontSizeType") }}
							fontSizeTypeMobile={{ value: btnfontSizeTypeMobile, label: __("btnfontSizeTypeMobile") }}
							fontSizeTypeTablet={{ value: btnfontSizeTypeTablet, label: __("btnfontSizeTypeTablet") }}
							fontSize={{ value: btnfontSizeDesktop, label: __("btnfontSizeDesktop") }}
							fontSizeMobile={{ value: btnfontSizeMobile, label: __("btnfontSizeMobile") }}
							fontSizeTablet={{ value: btnfontSizeTablet, label: __("btnfontSizeTablet") }}
							lineHeightType={{ value: '', label: __(" ") }}
							lineHeight={{ value: btnlineHeight, label: __("btnlineHeight") }}
							lineHeightMobile={{ value: btnlineHeightMobile, label: __("btnlineHeightMobile") }}
							lineHeightTablet={{ value: btnlineHeightTablet, label: __("btnlineHeightTablet") }}
							letterSpacing={{ value: btnletterSpacing, label: __("btnletterSpacing") }}
							letterSpacingMobile={{ value: btnletterSpacingMobile, label: __("btnletterSpacingMobile") }}
							letterSpacingTablet={{ value: btnletterSpacingTablet, label: __("btnletterSpacingTablet") }}
							letterSpacingType={{ value: btnletterSpacingType, label: __("btnletterSpacingType") }}
							letterSpacingTypeTablet={{ value: btnletterSpacingTypeTablet, label: __("btnletterSpacingTypeTablet") }}
							letterSpacingTypeMobile={{ value: btnletterSpacingTypeMobile, label: __("btnletterSpacingTypeMobile") }}
							textTransform={btntextTransform}
							onTextTransform={(value) => setAttributes({ btntextTransform: value })}
							textDecoration={btntextDecoration}
							onTextDecoration={(value) => setAttributes({ btntextDecoration: value })}
							fontStyle={btnfontStyle}
							onFontStyle={(value) => setAttributes({ btnfontStyle: value })}
						/>
						<h2>{__('Background & Text Color')}</h2>
						<Tabs>
							<Tab tabTitle={__('Normal')}>
								<Color label={__('Text Color')} disableAlpha value={btnTextColor} onChange={val => setAttributes({ btnTextColor: val })} />
								<Color label={__('Border Color')} disableAlpha value={btnBorderColor} onChange={val => setAttributes({ btnBorderColor: val })} />


								<Background
									parallax
									value={btnBg}
									label={__('Background')}
									externalImage
									sources={['color', 'gradient']}
									onChange={val => setAttributes({ btnBg: val })}
								/>

							</Tab>
							<Tab tabTitle={__('Hover')}>
								<Color label={__('Hover Text Color')} disableAlpha value={btnTextHoverColor} onChange={val => setAttributes({ btnTextHoverColor: val })} />
								<Color label={__('Border Hover Color')} disableAlpha value={btnBorderHoverColor} onChange={val => setAttributes({ btnBorderHoverColor: val })} />

								<Background
									parallax
									value={btnBgHover}
									label={__('Background')}
									externalImage
									sources={['color', 'gradient']}
									onChange={val => setAttributes({ btnBgHover: val })}
								/>

							</Tab>
						</Tabs>

						<TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
							activeClass="active-tab"
							tabs={[
								{
									name: 'boxdesk',
									title: <Dashicon icon="desktop" />,
									className: 'affiliate-responsive-tabs',
								},
								{
									name: 'boxtablet',
									title: <Dashicon icon="tablet" />,
									className: 'affiliate-responsive-tabs',
								},
								{
									name: 'boxmobile',
									title: <Dashicon icon="smartphone" />,
									className: 'affiliate-responsive-tabs',
								},
							]}>
							{
								(radiustab) => {
									let tabout;
									if (radiustab.name) {
										if ('boxmobile' === radiustab.name) {
											tabout = (
												<Dimension
													label={__('Padding')} value={btnPaddingMobile}
													onChange={val => setAttributes({ btnPaddingMobile: val })}
													min={0} max={600} unit={['px', 'em', 'rem', '%']}
													clientId={this.props.clientId}
												/>
											);
										} else if ('boxtablet' === radiustab.name) {
											tabout = (
												<Dimension label={__('Padding')} value={btnPaddingTablet}
													onChange={val => setAttributes({ btnPaddingTablet: val })}
													min={0} max={600} unit={['px', 'em', 'rem', '%']}
													clientId={this.props.clientId}
												/>
											);
										} else {
											tabout = (
												<Dimension
													label={__('Padding')} value={btnPadding}
													onChange={val => setAttributes({ btnPadding: val })}
													min={0} max={600} unit={['px', 'em', 'rem', '%']}
													clientId={this.props.clientId}
												/>
											);
										}
									}
									return <div>{tabout}</div>;
								}
							}
						</TabPanel>
						<TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
							activeClass="active-tab"
							tabs={[
								{
									name: 'boxdesk',
									title: <Dashicon icon="desktop" />,
									className: 'affiliate-responsive-tabs',
								},
								{
									name: 'boxtablet',
									title: <Dashicon icon="tablet" />,
									className: 'affiliate-responsive-tabs',
								},
								{
									name: 'boxmobile',
									title: <Dashicon icon="smartphone" />,
									className: 'affiliate-responsive-tabs',
								},
							]}>
							{
								(radiustab) => {
									let tabout;
									if (radiustab.name) {
										if ('boxmobile' === radiustab.name) {
											tabout = (
												<Dimension
													label={__('Margin')}
													value={btnMarginMobile}
													onChange={val => setAttributes({ btnMarginMobile: val })}
													min={0} max={600} unit={['px', 'em', 'rem', '%']}
													clientId={this.props.clientId}
												/>
											);
										} else if ('boxtablet' === radiustab.name) {
											tabout = (
												<Dimension label={__('Margin')} value={btnMarginTablet}
													onChange={val => setAttributes({ btnMarginMobile: val })}
													min={0} max={600} unit={['px', 'em', 'rem', '%']}
													clientId={this.props.clientId}
												/>
											);
										} else {
											tabout = (
												<Dimension
													label={__('Margin')} value={btnMargin}
													onChange={val => setAttributes({ btnMargin: val })}
													min={0} max={600} unit={['px', 'em', 'rem', '%']}
													clientId={this.props.clientId}
												/>
											);
										}
									}
									return <div>{tabout}</div>;
								}
							}
						</TabPanel>
						<BorderTypes label={__('Border Type')} value={btnBorderType} onChange={(value) => setAttributes({ btnBorderType: value })} />
						<TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
							activeClass="active-tab"
							tabs={[
								{
									name: 'boxdesk',
									title: <Dashicon icon="desktop" />,
									className: 'affiliate-responsive-tabs',
								},
								{
									name: 'boxtablet',
									title: <Dashicon icon="tablet" />,
									className: 'affiliate-responsive-tabs',
								},
								{
									name: 'boxmobile',
									title: <Dashicon icon="smartphone" />,
									className: 'affiliate-responsive-tabs',
								},
							]}>
							{
								(radiustab) => {
									let tabout;
									if (radiustab.name) {
										if ('boxmobile' === radiustab.name) {
											tabout = (
												<Dimension
													label={__('Border Width')} value={btnBorderMobile}
													onChange={val => setAttributes({ btnBorderMobile: val })}
													min={0} max={600} unit={['px', 'em', 'rem', '%']}
													clientId={this.props.clientId}
												/>
											);
										} else if ('boxtablet' === radiustab.name) {
											tabout = (
												<Dimension label={__('Border Width')}
													value={btnBorderTablet}
													onChange={val => setAttributes({ btnBorderTablet: val })}
													min={0} max={600} unit={['px', 'em', 'rem', '%']}
													clientId={this.props.clientId}
												/>
											);
										} else {
											tabout = (
												<Dimension
													label={__('Border Width')} value={btnBorder}
													onChange={val => setAttributes({ btnBorder: val })}
													min={0} max={600} unit={['px', 'em', 'rem', '%']}
													clientId={this.props.clientId}
												/>
											);
										}
									}
									return <div>{tabout}</div>;
								}
							}
						</TabPanel>
						<TabPanel className="affiliate-size-type-field-tabs affiliate-dimension-field mt-align-tab"
							activeClass="active-tab"
							tabs={[
								{
									name: 'boxdesk',
									title: <Dashicon icon="desktop" />,
									className: 'affiliate-responsive-tabs',
								},
								{
									name: 'boxtablet',
									title: <Dashicon icon="tablet" />,
									className: 'affiliate-responsive-tabs',
								},
								{
									name: 'boxmobile',
									title: <Dashicon icon="smartphone" />,
									className: 'affiliate-responsive-tabs',
								},
							]}>
							{
								(radiustab) => {
									let tabout;
									if (radiustab.name) {
										if ('boxmobile' === radiustab.name) {
											tabout = (
												<Dimension
													label={__('Border Radius')} value={btnBorderRadiusMobile}
													onChange={val => setAttributes({ btnBorderRadiusMobile: val })}
													min={0} max={600} unit={['px', 'em', 'rem', '%']}
													clientId={this.props.clientId}
												/>
											);
										} else if ('boxtablet' === radiustab.name) {
											tabout = (
												<Dimension label={__('Border Radius')}
													value={btnBorderRadiusTablet}
													onChange={val => setAttributes({ btnBorderRadiusTablet: val })}
													min={0} max={600} unit={['px', 'em', 'rem', '%']}
													clientId={this.props.clientId}
												/>
											);
										} else {
											tabout = (
												<Dimension
													label={__('Border Radius')} value={btnBorderRadius}
													onChange={val => setAttributes({ btnBorderRadius: val })}
													min={0} max={600} unit={['px', 'em', 'rem', '%']}
													clientId={this.props.clientId}
												/>
											);
										}
									}
									return <div>{tabout}</div>;
								}
							}
						</TabPanel>

					</PanelBody>
				</InspectorControls>
			</Fragment>
			,
			<div id={`affiliate-style-${block_id}`} className={this.props.className}>
				<div className={`affiliate-cmpr-inner ${tableStyle}`}>
					{tableStyle && tableStyle == 'tbl-style-1' &&
						<table>
							<thead className={`affiliate-compr-th`}>
								<tr>
									{showImage && showImage == true &&
										<th>{th1_txt}</th>
									}
									{showProductTitle && showProductTitle == true &&
										<th>{th2_txt}</th>
									}
									{showProductContent && showProductContent == true &&
										<th>{th3_txt}</th>
									}
									{showProductPrice && showProductPrice == true &&
										<th>{th4_txt}</th>
									}
									<th>Delete</th>
								</tr>
							</thead>
							<tbody className={`affiliate-compr-cntn`}>
								{trDisplay}
							</tbody>
						</table>
					}

					{tableStyle && tableStyle == 'tbl-style-2' &&
						<table>
							<thead className={`affiliate-compr-th`}>
								<tr>
									{showImage && showImage == true &&
										<th>{th1_txt}</th>
									}
									{showProductTitle && showProductTitle == true &&
										<th>{th2_txt}</th>
									}
									{showProductContent && showProductContent == true &&
										<th>{th4_txt}</th>
									}
									<th >Delete</th>
								</tr>
							</thead>
							<tbody className={`affiliate-compr-cntn`}>
								{trDisplay}
							</tbody>
						</table>
					}
				</div>
				<Button variant={'secondary'} className=" components_panel_btn" onClick={handletr.bind(this)} >
					<span class="screen-reader-text">Add Row</span>
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="-2 -2 24 24" width="24" height="24" role="img" aria-hidden="true" focusable="false"><path d="M10 1c-5 0-9 4-9 9s4 9 9 9 9-4 9-9-4-9-9-9zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7 7 3.1 7 7-3.1 7-7 7zm1-11H9v3H6v2h3v3h2v-3h3V9h-3V6zM10 1c-5 0-9 4-9 9s4 9 9 9 9-4 9-9-4-9-9-9zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7 7 3.1 7 7-3.1 7-7 7zm1-11H9v3H6v2h3v3h2v-3h3V9h-3V6z"></path></svg>
				</Button>
				{loadbtnGoogleFonts}
				{loadthGoogleFonts}
				{loadcntnGoogleFonts}
				{loadTitleGoogleFonts}
				{loadBadgeGoogleFonts}
				{loadReadMoreGoogleFonts}
			</div>
		]
	}
}

export default (Edit);