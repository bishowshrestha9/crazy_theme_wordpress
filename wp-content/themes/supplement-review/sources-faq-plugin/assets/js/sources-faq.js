/**
 * FAQ Accordion Vanilla JS Controller
 *
 * Implements WAI-ARIA accordion pattern with keyboard navigation,
 * smooth height transitions, and zero jQuery dependencies.
 */
(function() {
	'use strict';

	function initFaqAccordions() {
		const accordions = document.querySelectorAll('.sf-faq-wrapper');
		if (!accordions.length) return;

		accordions.forEach(function(accordion) {
			const isSingleOpen = accordion.getAttribute('data-single-open') === 'true';
			const cards = accordion.querySelectorAll('.sf-faq-card');
			const buttons = Array.from(accordion.querySelectorAll('.sf-faq-button'));

			// Initial state setup
			cards.forEach(function(card) {
				const button = card.querySelector('.sf-faq-button');
				const panel = card.querySelector('.sf-faq-answer-panel');
				if (!button || !panel) return;

				const isOpen = card.classList.contains('sf-open') || button.getAttribute('aria-expanded') === 'true';
				if (isOpen) {
					card.classList.add('sf-open');
					button.setAttribute('aria-expanded', 'true');
					panel.setAttribute('aria-hidden', 'false');
					panel.style.maxHeight = panel.scrollHeight + 'px';
				} else {
					card.classList.remove('sf-open');
					button.setAttribute('aria-expanded', 'false');
					panel.setAttribute('aria-hidden', 'true');
					panel.style.maxHeight = '0px';
				}
			});

			// Toggle item function
			function toggleCard(card, shouldOpen) {
				const button = card.querySelector('.sf-faq-button');
				const panel = card.querySelector('.sf-faq-answer-panel');
				if (!button || !panel) return;

				const currentlyOpen = card.classList.contains('sf-open');
				const openState = (typeof shouldOpen === 'boolean') ? shouldOpen : !currentlyOpen;

				if (openState) {
					// If single open mode is enabled, close other cards first
					if (isSingleOpen) {
						cards.forEach(function(otherCard) {
							if (otherCard !== card && otherCard.classList.contains('sf-open')) {
								toggleCard(otherCard, false);
							}
						});
					}

					card.classList.add('sf-open');
					button.setAttribute('aria-expanded', 'true');
					panel.setAttribute('aria-hidden', 'false');
					panel.style.maxHeight = panel.scrollHeight + 'px';
				} else {
					card.classList.remove('sf-open');
					button.setAttribute('aria-expanded', 'false');
					panel.setAttribute('aria-hidden', 'true');
					panel.style.maxHeight = '0px';
				}
			}

			// Click listener
			cards.forEach(function(card) {
				const button = card.querySelector('.sf-faq-button');
				if (!button) return;

				button.addEventListener('click', function(e) {
					e.preventDefault();
					toggleCard(card);
				});
			});

			// WAI-ARIA Keyboard Navigation: ArrowUp, ArrowDown, Home, End
			accordion.addEventListener('keydown', function(e) {
				const target = e.target;
				if (!target || !target.classList.contains('sf-faq-button')) return;

				const currentIndex = buttons.indexOf(target);
				if (currentIndex === -1) return;

				let newIndex = null;

				switch (e.key) {
					case 'ArrowDown':
						e.preventDefault();
						newIndex = (currentIndex + 1) % buttons.length;
						break;
					case 'ArrowUp':
						e.preventDefault();
						newIndex = (currentIndex - 1 + buttons.length) % buttons.length;
						break;
					case 'Home':
						e.preventDefault();
						newIndex = 0;
						break;
					case 'End':
						e.preventDefault();
						newIndex = buttons.length - 1;
						break;
					default:
						break;
				}

				if (newIndex !== null && buttons[newIndex]) {
					buttons[newIndex].focus();
				}
			});

			// Resize observer to recalculate maxHeight for open panels on window resize
			window.addEventListener('resize', function() {
				cards.forEach(function(card) {
					if (card.classList.contains('sf-open')) {
						const panel = card.querySelector('.sf-faq-answer-panel');
						if (panel) {
							panel.style.maxHeight = panel.scrollHeight + 'px';
						}
					}
				});
			});
		});
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initFaqAccordions);
	} else {
		initFaqAccordions();
	}
})();
