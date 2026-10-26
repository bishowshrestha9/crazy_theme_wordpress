export function sanitizeHtml(html, allowedTags = []) {
    // Function to determine if a tag is allowed
    function isAllowedTag(tag) {
        const matches = tag.match(/^<\/?(\w+)/);
        return matches && allowedTags.includes(matches[1].toLowerCase());
    }

    // Remove all comments
    html = html.replace(/<!--[\s\S]*?-->/g, '');

    // Remove all HTML tags except those that are allowed
    html = html.replace(/<\/?[^>]+(>|$)/g, function(tag) {
        // Check if the tag is allowed
        return isAllowedTag(tag) ? tag : '';
    });

    return html; // Return the sanitized HTML
}