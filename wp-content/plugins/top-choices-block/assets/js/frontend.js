document.addEventListener('DOMContentLoaded', function() {
    const seeMoreLinks = document.querySelectorAll('.top-choices-see-more');

    seeMoreLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const productName = this.getAttribute('data-product-name');
            if (!productName) return;

            const cleanProductName = productName.trim().toLowerCase();
            const slug = cleanProductName
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/(^-|-$)/g, '');

            let target = null;

            // Search ONLY H3 headings in the post content (excluding H3 titles inside the block cards)
            const headings = Array.from(document.querySelectorAll('h3')).filter(function(h3) {
                return !h3.closest('.top-choices-container');
            });

            // Pass 1: Exact match, ID match, or full substring match
            for (let i = 0; i < headings.length; i++) {
                const h3 = headings[i];
                const headingText = h3.textContent.trim().toLowerCase();
                const h3Id = h3.id ? h3.id.trim().toLowerCase() : '';

                if (h3Id === slug || headingText === cleanProductName || headingText.includes(cleanProductName) || cleanProductName.includes(headingText)) {
                    target = h3;
                    break;
                }
            }

            // Pass 2: Fuzzy LIKE match (matching key words like "AiraBreeze")
            if (!target) {
                // Extract words from product name that are at least 3 characters long
                const words = cleanProductName.split(/[^a-z0-9]+/).filter(function(w) { return w.length >= 3; });
                
                for (let i = 0; i < headings.length; i++) {
                    const h3 = headings[i];
                    const headingText = h3.textContent.trim().toLowerCase();
                    
                    // Check if the heading contains any of the significant words (LIKE '%word%')
                    const isMatch = words.some(function(word) { return headingText.includes(word); });
                    if (isMatch) {
                        target = h3;
                        break;
                    }
                }
            }

            // Scroll smoothly to the matched H3 heading with sticky header offset
            if (target) {
                const headerOffset = 80;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                if (!target.id) {
                    target.id = slug;
                }
                history.pushState(null, null, '#' + target.id);
            }
        });
    });
});
