(function () {
  "use strict";

  function isBlockEditor() {
    return (
      document.body &&
      (document.body.classList.contains("block-editor-page") ||
        document.body.classList.contains("wp-admin"))
    );
  }

  function isValidUrl(href) {
    if (!href) return false;
    const trimmed = href.trim();
    if (!trimmed || trimmed === "#") return false;
    if (/^javascript:/i.test(trimmed)) return false;
    try {
      const url = new URL(trimmed, window.location.origin);
      return url.protocol === "http:" || url.protocol === "https:";
    } catch (error) {
      return false;
    }
  }

  function findTargetContainer(card, selector) {
    if (!selector) return null;
    const localMatch = card.closest(selector);
    if (localMatch) return localMatch;
    const prev = card.previousElementSibling;
    if (prev && prev.matches && prev.matches(selector)) return prev;
    if (prev && prev.closest) {
      const prevMatch = prev.closest(selector);
      if (prevMatch) return prevMatch;
    }
    return document.querySelector(selector);
  }

  function showCard(card, container) {
    if (container && card.parentNode !== container.parentNode) {
      container.parentNode.insertBefore(card, container.nextSibling);
    }

    if (container) {
      container.style.display = "none";
      container.classList.add("caution-card-hidden");
    }

    card.classList.add("is-visible");
  }

  function shouldShowCard(container, triggerSelector) {
    const trigger = container
      ? container.querySelector(triggerSelector)
      : document.querySelector(triggerSelector);
    const href = trigger ? trigger.getAttribute("href") : "";
    return !isValidUrl(href);
  }

  function handleClick(event, card) {
    if (isBlockEditor()) return;
    const triggerSelector = card.getAttribute("data-trigger-selector") || ".pcpx-cta-button";
    const containerSelector = card.getAttribute("data-container-selector") || ".pcpx-product-card-outer";
    const container = findTargetContainer(card, containerSelector);

    const path = event.composedPath ? event.composedPath() : [];
    const hit = path.find((node) => node && node.closest && node.closest(triggerSelector));
    if (!hit) return;

    if (shouldShowCard(container, triggerSelector)) {
      event.preventDefault();
      event.stopPropagation();
      if (event.stopImmediatePropagation) event.stopImmediatePropagation();
      showCard(card, container);
    }
  }

  function initCard(card) {
    window.addEventListener("click", (event) => handleClick(event, card), true);
  }

  document.addEventListener("DOMContentLoaded", function () {
    if (isBlockEditor()) return;
    document.querySelectorAll(".caution-card-inline").forEach(initCard);
  });
})();
