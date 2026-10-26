(function (wp) {
  const { registerBlockType } = wp.blocks;
  const { InspectorControls, MediaUpload, MediaUploadCheck } = wp.blockEditor;
  const { PanelBody, TextControl, Button } = wp.components;
  const { createElement: el, Fragment } = wp.element;

  registerBlockType("caution-card/block", {
    edit: function (props) {
      const { attributes, setAttributes } = props;
      const previewOldProduct = "[ACF: product_name]";
      const newProduct = attributes.newProductName || "New Product";
      const ratingValue = attributes.ratingValue || "4.9";
      const reviewCount = attributes.reviewCount || "12598";
      const feature1 = attributes.feature1 || "Designed for harder erection, higher stamina, more libido";
      const feature2 = attributes.feature2 || "All Natural Ingredients with No Side Effects";
      const feature3 = attributes.feature3 || "98% effective and results within 4 weeks";
      const reviewAvatar1 = attributes.reviewAvatar1Url || "";
      const reviewAvatar2 = attributes.reviewAvatar2Url || "";
      const reviewAvatar3 = attributes.reviewAvatar3Url || "";

      const topRatedBadgeData =
        "data:image/svg+xml;utf8," +
        encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120"><defs><radialGradient id="g" cx="35%" cy="30%" r="80%"><stop offset="0" stop-color="#ffe986"/><stop offset="0.55" stop-color="#f3c80f"/><stop offset="1" stop-color="#d89a00"/></radialGradient></defs><path fill="url(#g)" d="M60 2 69 12l13-6 7 12 14-1 2 14 13 5-4 13 10 9-9 10 4 13-13 5-2 14-14-1-7 12-13-6-9 10-10-10-13 6-7-12-14 1-2-14-13-5 4-13-9-10 9-9-4-13 13-5 2-14 14 1 7-12 13 6z"/><circle cx="60" cy="60" r="41" fill="none" stroke="#fff4bf" stroke-width="3" stroke-dasharray="3.2 3.2"/><g fill="#fff" font-family="Arial,sans-serif" font-weight="700" text-anchor="middle"><text x="60" y="45" font-size="11">★★★★★</text><text x="60" y="63" font-size="16">TOP RATED</text><text x="60" y="78" font-size="9">QUALITY VERIFIED</text></g></svg>');
      const cautionIconData =
        "data:image/svg+xml;utf8," +
        encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><circle cx="14" cy="14" r="13" fill="#fff4d9" stroke="#ffc34d" stroke-width="1.5"/><path d="M14 5.2 5.5 20.3h17L14 5.2Zm0 4.4c.7 0 1.2.5 1.2 1.2v4.9c0 .7-.5 1.2-1.2 1.2s-1.2-.5-1.2-1.2v-4.9c0-.7.5-1.2 1.2-1.2Zm0 10a1.35 1.35 0 1 1 0-2.7 1.35 1.35 0 0 1 0 2.7Z" fill="#f97316"/></svg>');
      const checkIconData =
        "data:image/svg+xml;utf8," +
        encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><defs><radialGradient id="checkGrad" cx="35%" cy="30%" r="75%"><stop offset="0" stop-color="#f4fff8"/><stop offset="1" stop-color="#d9f7e5"/></radialGradient></defs><circle cx="12" cy="12" r="10.5" fill="url(#checkGrad)" stroke="#9dd9b5" stroke-width="1.5"/><path d="M9.3 15.9 6 12.6a1 1 0 0 1 1.4-1.4l2.2 2.2 6-6a1 1 0 1 1 1.4 1.4l-6.7 6.7a1 1 0 0 1-1.4 0Z" fill="#159447"/></svg>');
      const certIconData =
        "data:image/svg+xml;utf8," +
        encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><circle cx="14" cy="14" r="13" fill="#ecfdf3" stroke="#86efac" stroke-width="1.5"/><path d="M14 6.1 8.2 8.5v5.8c0 3.9 2.4 7.3 5.8 8.6 3.4-1.3 5.8-4.7 5.8-8.6V8.5L14 6.1Zm-1.4 10.7-2.3-2.3a1 1 0 0 1 1.4-1.4l1.6 1.6 3.2-3.2a1 1 0 1 1 1.4 1.4l-3.9 3.9a1 1 0 0 1-1.4 0Z" fill="#16a34a"/></svg>');

      const stars = Math.max(0, Math.min(5, Math.round(parseFloat(ratingValue) || 0)));
      const starOutput = "★".repeat(stars) + "☆".repeat(5 - stars);

      return el(
        Fragment,
        null,
        el(
          InspectorControls,
          null,
          el(
            PanelBody,
            { title: "Caution Card Settings", initialOpen: true },
            el(TextControl, {
              label: "New Product Name",
              value: attributes.newProductName,
              onChange: (value) => setAttributes({ newProductName: value })
            }),
            el("div", { className: "caution-card-media" },
              el("label", { style: { display: "block", marginBottom: "6px", fontWeight: 600 } }, "New Product Image"),
              el(MediaUploadCheck, {},
                el(MediaUpload, {
                  onSelect: (media) => {
                    if (!media) return;
                    setAttributes({
                      newProductImageId: media.id || 0,
                      newProductImageUrl: media.url || "",
                      newProductImage: media.url || ""
                    });
                  },
                  allowedTypes: ["image"],
                  value: attributes.newProductImageId,
                  render: ({ open }) => el(Button, { onClick: open, isSecondary: true }, attributes.newProductImageUrl ? "Change Image" : "Select Image")
                })
              )
            ),
            el(TextControl, {
              label: "Feature 1",
              value: attributes.feature1,
              onChange: (value) => setAttributes({ feature1: value })
            }),
            el(TextControl, {
              label: "Feature 2",
              value: attributes.feature2,
              onChange: (value) => setAttributes({ feature2: value })
            }),
            el(TextControl, {
              label: "Feature 3",
              value: attributes.feature3,
              onChange: (value) => setAttributes({ feature3: value })
            }),
            el("div", { className: "caution-card-media", style: { marginTop: "12px" } },
              el("label", { style: { display: "block", marginBottom: "6px", fontWeight: 600 } }, "Review Avatar 1"),
              el(MediaUploadCheck, {},
                el(MediaUpload, {
                  onSelect: (media) => {
                    if (!media) return;
                    setAttributes({ reviewAvatar1Id: media.id || 0, reviewAvatar1Url: media.url || "" });
                  },
                  allowedTypes: ["image"],
                  value: attributes.reviewAvatar1Id,
                  render: ({ open }) => el(Button, { onClick: open, isSecondary: true }, reviewAvatar1 ? "Change Avatar 1" : "Select Avatar 1")
                })
              )
            ),
            el("div", { className: "caution-card-media", style: { marginTop: "12px" } },
              el("label", { style: { display: "block", marginBottom: "6px", fontWeight: 600 } }, "Review Avatar 2"),
              el(MediaUploadCheck, {},
                el(MediaUpload, {
                  onSelect: (media) => {
                    if (!media) return;
                    setAttributes({ reviewAvatar2Id: media.id || 0, reviewAvatar2Url: media.url || "" });
                  },
                  allowedTypes: ["image"],
                  value: attributes.reviewAvatar2Id,
                  render: ({ open }) => el(Button, { onClick: open, isSecondary: true }, reviewAvatar2 ? "Change Avatar 2" : "Select Avatar 2")
                })
              )
            ),
            el("div", { className: "caution-card-media", style: { marginTop: "12px" } },
              el("label", { style: { display: "block", marginBottom: "6px", fontWeight: 600 } }, "Review Avatar 3"),
              el(MediaUploadCheck, {},
                el(MediaUpload, {
                  onSelect: (media) => {
                    if (!media) return;
                    setAttributes({ reviewAvatar3Id: media.id || 0, reviewAvatar3Url: media.url || "" });
                  },
                  allowedTypes: ["image"],
                  value: attributes.reviewAvatar3Id,
                  render: ({ open }) => el(Button, { onClick: open, isSecondary: true }, reviewAvatar3 ? "Change Avatar 3" : "Select Avatar 3")
                })
              )
            ),
            el(TextControl, {
              label: "Rating Stars (e.g., 4.9)",
              value: attributes.ratingValue,
              onChange: (value) => setAttributes({ ratingValue: value })
            }),
            el(TextControl, {
              label: "Review Count",
              value: attributes.reviewCount,
              onChange: (value) => setAttributes({ reviewCount: value })
            }),
            el(TextControl, {
              label: "Read More URL",
              value: attributes.readMoreUrl,
              onChange: (value) => setAttributes({ readMoreUrl: value })
            }),
            el(TextControl, {
              label: "Look Other Alternative URL",
              value: attributes.alternativesUrl,
              onChange: (value) => setAttributes({ alternativesUrl: value })
            }),
            el(TextControl, {
              label: "Contact URL",
              value: attributes.contactUrl,
              onChange: (value) => setAttributes({ contactUrl: value })
            }),
            el(TextControl, {
              label: "Trigger Selector (.pcpx-cta-button)",
              value: attributes.triggerSelector,
              onChange: (value) => setAttributes({ triggerSelector: value })
            }),
            el(TextControl, {
              label: "Container Selector (.pcpx-product-card-outer)",
              value: attributes.containerSelector,
              onChange: (value) => setAttributes({ containerSelector: value })
            })
          )
        ),
        el(
          "div",
          { className: "caution-card" },
          el(
            "div",
            { className: "caution-card__warning" },
            el("span", { className: "caution-card__warning-icon" }, el("img", { src: cautionIconData, alt: "" })),
            el(
              "p",
              { className: "caution-card__warning-text" },
              el("strong", null, "Caution:"),
              ` Due to quality-control and counterfeit concerns, ${previewOldProduct} has been discontinued.`
            )
          ),
          el(
            "div",
            { className: "caution-card__content" },
            el(
              "div",
              { className: "caution-card__body" },
              el(
                "div",
                { className: "caution-card__left" },
                el(
                  "p",
                  { className: "caution-card__recommend" },
                  el("span", null, "We recommend "),
                  el("span", { className: "caution-card__highlight" }, newProduct),
                  " as the safer, more reliable alternative to ",
                  el("span", { className: "caution-card__highlight caution-card__highlight--danger" }, previewOldProduct),
                  "."
                ),
                el(
                  "ul",
                  { className: "caution-card__features" },
                  el("li", null, el("span", { className: "caution-card__check", "aria-hidden": true }, el("img", { src: checkIconData, alt: "" })), el("span", null, feature1)),
                  el("li", null, el("span", { className: "caution-card__check", "aria-hidden": true }, el("img", { src: checkIconData, alt: "" })), el("span", null, feature2)),
                  el("li", null, el("span", { className: "caution-card__check", "aria-hidden": true }, el("img", { src: checkIconData, alt: "" })), el("span", null, feature3))
                ),
                
              ),
              el(
                "div",
                { className: "caution-card__right" },
                el(
                  "div",
                  { className: "caution-card__image-wrap" },
                  attributes.newProductImageUrl
                    ? el("img", { src: attributes.newProductImageUrl, alt: newProduct })
                    : el("div", { className: "caution-card__image-placeholder" }, "Image"),
                  el("span", { className: "caution-card__badge" }, el("img", { src: topRatedBadgeData, alt: "Top Rated" }))
                ),
                el(
                  "div",
                  { className: "caution-card__right-actions" },
                  el(
                    "div",
                    { className: "caution-card__actions" },
                    el("span", { className: "caution-card__button" }, `Read More about ${newProduct}`),
                    el("span", { className: "caution-card__link" }, "Look Other Alternative")
                  ),
                  el(
                    "div",
                    { className: "caution-card__rating" },
                    el(
                      "div",
                      { className: "caution-card__avatars" },
                      el("span", { className: "caution-card__avatar" }, reviewAvatar1 ? el("img", { src: reviewAvatar1, alt: "" }) : null),
                      el("span", { className: "caution-card__avatar" }, reviewAvatar2 ? el("img", { src: reviewAvatar2, alt: "" }) : null),
                      el("span", { className: "caution-card__avatar" }, reviewAvatar3 ? el("img", { src: reviewAvatar3, alt: "" }) : null)
                    ),
                    el(
                      "div",
                      { className: "caution-card__rating-info" },
                      el("div", { className: "caution-card__stars" }, starOutput, el("span", { className: "caution-card__rating-value" }, `${ratingValue}/5`)),
                      el("span", { className: "caution-card__rating-text" }, `${reviewCount}+ user reviews`)
                    )
                  ),
                  el(
                    "div",
                    { className: "caution-card__certs" },
                    el("span", { className: "caution-card__cert" }, el("span", { className: "caution-card__cert-icon" }, el("img", { src: certIconData, alt: "" })), "FDA Approved"),
                    el("span", { className: "caution-card__cert" }, el("span", { className: "caution-card__cert-icon" }, el("img", { src: certIconData, alt: "" })), "GMP Certified")
                  )
                )
              )
            ),
            el(
              "div",
              { className: "caution-card__footer" },
              el("p", { className: "caution-card__note" }, "*Our recommendation is based on formula quality, consistency, and availability."),
              el("p", { className: "caution-card__contact" }, `Owner of ${newProduct} `, el("span", { className: "caution-card__contact-link" }, "Contact Us"))
            )
          )
        )
      );
    },
    save: function () {
      return null;
    }
  });
})(window.wp);
