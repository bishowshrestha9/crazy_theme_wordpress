document.addEventListener('DOMContentLoaded', function () {
    const blocks = document.querySelectorAll('.wp-block-create-block-button-plugin');

    blocks.forEach(function (block) {
        const button = block.querySelector('.bp-reveal-button');
        const content = block.querySelector('.bp-reveal-content');

        if (button && content) {
            button.addEventListener('click', function () {
                const loadingContainer = block.querySelector('.bp-loading-container');
                const loadingTextEl = block.querySelector('.bp-loading-text');
                const rawLoadingTexts = button.getAttribute('data-loading-texts');
                let loadingTexts = [];

                try {
                    loadingTexts = JSON.parse(rawLoadingTexts);
                } catch (e) {
                    loadingTexts = ["Loading..."];
                }

                if (!loadingTexts || !Array.isArray(loadingTexts) || loadingTexts.length === 0) {
                    loadingTexts = ["Loading..."];
                }

                // Hide button
                button.style.display = 'none';

                // Show loading container
                if (loadingContainer) {
                    loadingContainer.style.display = 'flex';
                }

                // Get progress bar element
                const progressFill = block.querySelector('.bp-progress-fill');

                // Timing Logic
                // Total duration fixed at 8000ms as requested
                const totalDuration = 8000;
                const stepDuration = totalDuration / loadingTexts.length;
                let currentStep = 0;

                // Animate progress bar
                if (progressFill) {
                    // Start progress bar animation
                    setTimeout(() => {
                        progressFill.style.width = '100%';
                        progressFill.style.transition = `width ${totalDuration}ms linear`;
                    }, 50);
                }

                const updateText = () => {
                    if (currentStep < loadingTexts.length) {
                        if (loadingTextEl) {
                            // Reset animation
                            loadingTextEl.classList.remove('bp-animate-in');
                            void loadingTextEl.offsetWidth; // Force reflow

                            loadingTextEl.innerText = loadingTexts[currentStep];
                            loadingTextEl.classList.add('bp-animate-in');
                        }
                        currentStep++;
                    }
                };

                // Initial text
                updateText();

                // Interval for subsequent texts
                const interval = setInterval(() => {
                    updateText();
                }, stepDuration);

                // Finish after total duration
                setTimeout(function () {
                    clearInterval(interval);

                    // Hide loading container
                    if (loadingContainer) {
                        loadingContainer.style.display = 'none';
                    }

                    // REMOVE button completely from DOM
                    if (button && button.parentNode) {
                        button.parentNode.removeChild(button);
                    }

                    // Show content
                    content.style.display = 'block';

                    // Process ACF shortcodes in the revealed content
                    processACFShortcodes(content);

                    // Trigger fade in
                    // Small timeout to ensure display:block is processed
                    setTimeout(() => {
                        content.classList.add('bp-fade-in');
                    }, 50);

                }, totalDuration);
            });
        }
    });

    // Function to process [acf field="..."] shortcodes in content
    function processACFShortcodes(container) {
        // Get all text nodes
        const walker = document.createTreeWalker(
            container,
            NodeFilter.SHOW_TEXT,
            null,
            false
        );

        const textNodes = [];
        let node;
        while (node = walker.nextNode()) {
            if (node.nodeValue.includes('[acf field=')) {
                textNodes.push(node);
            }
        }

        // Replace ACF shortcodes in text nodes
        textNodes.forEach(textNode => {
            let text = textNode.nodeValue;
            const regex = /\[acf field=["']([^"']+)["']\]/g;
            let match;

            while ((match = regex.exec(text)) !== null) {
                const fieldName = match[1];
                const fieldValue = getACFFieldValue(fieldName);
                if (fieldValue) {
                    text = text.replace(match[0], fieldValue);
                }
            }

            textNode.nodeValue = text;
        });
    }

    // Function to get ACF field value from the page
    function getACFFieldValue(fieldName) {
        // Try to get from page title for product_name
        if (fieldName === 'product_name') {
            const h1 = document.querySelector('h1.entry-title, h1.page-title, h1, .wp-block-post-title');
            if (h1) {
                return h1.textContent.trim();
            }
        }

        return '';
    }
});
