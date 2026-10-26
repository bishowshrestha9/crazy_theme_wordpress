<?php
/**
 * SPC_Converter — detects and converts Pros & Cons into the
 * Simple Pros & Cons Gutenberg block.
 *
 * Priority 1 : affiliate-booster/propsandcons blocks (parses saved HTML).
 * Priority 2 : core/heading + core/list pattern in Gutenberg posts.
 * Priority 3 : raw <hN> + <ul> pattern in classic HTML posts.
 */

if (!defined('ABSPATH')) {
    exit;
}

class SPC_Converter
{

    // ─────────────────────────────────────────────────────────────────
    // Public API
    // ─────────────────────────────────────────────────────────────────

    /**
     * Find all posts/pages that contain detectable pros/cons headings
     * and have NOT already been converted.
     *
     * @return array  Array of WP_Post objects.
     */
    public function scan_posts(): array
    {
        $query = new WP_Query(array(
            'post_type' => 'any',
            'post_status' => 'any',
            'posts_per_page' => -1,
            'fields' => 'all',
        ));

        $matches = array();

        foreach ($query->posts as $post) {
            $content = $post->post_content;

            // Skip posts already converted
            if (strpos($content, '<!-- wp:simple-pros-cons/pros-cons') !== false) {
                continue;
            }

            $detection = $this->detect($content);
            if ($detection['found']) {
                $post->spc_detection = $detection;
                $matches[] = $post;
            }
        }

        return $matches;
    }

    /**
     * Convert a single post by ID.
     *
     * @param  int   $post_id
     * @return array { success: bool, message: string, blocks_converted: int }
     */
    public function convert_post(int $post_id): array
    {
        $post = get_post($post_id);
        if (!$post) {
            return array('success' => false, 'message' => 'Post not found.');
        }

        $content = $post->post_content;
        $detection = $this->detect($content);

        if (!$detection['found']) {
            return array('success' => false, 'message' => 'No convertible pros/cons found.');
        }

        $new_content = $detection['new_content'];

        $result = wp_update_post(array(
            'ID' => $post_id,
            'post_content' => $new_content,
        ), true);

        if (is_wp_error($result)) {
            return array('success' => false, 'message' => $result->get_error_message());
        }

        return array(
            'success' => true,
            'message' => 'Converted ' . $detection['count'] . ' pros/cons section(s).',
            'blocks_converted' => $detection['count'],
        );
    }

    /**
     * Preview what will be detected in a post (without saving).
     *
     * @param  int   $post_id
     * @return array
     */
    public function preview_post(int $post_id): array
    {
        $post = get_post($post_id);
        if (!$post) {
            return array('found' => false);
        }
        return $this->detect($post->post_content);
    }

    // ─────────────────────────────────────────────────────────────────
    // Detection (dispatcher)
    // ─────────────────────────────────────────────────────────────────

    /**
     * Detect pros/cons in content and build the converted version.
     *
     * @param  string $content Raw post_content.
     * @return array  { found, count, new_content, pairs[] }
     */
    private function detect(string $content): array
    {
        // Priority 1: Affiliate Booster propsandcons (Gutenberg block OR raw HTML)
        if (
            strpos($content, 'affiliate-booster/propsandcons') !== false ||
            strpos($content, 'wp-block-affiliate-booster-propsandcons') !== false
        ) {
            return $this->detect_affiliate_booster($content);
        }

        // Priority 2 / 3: heading+list pattern
        if (has_blocks($content)) {
            return $this->detect_gutenberg($content);
        }
        return $this->detect_classic($content);
    }

    // ─────────────────────────────────────────────────────────────────
    // Affiliate Booster → Simple Pros & Cons migration
    // ─────────────────────────────────────────────────────────────────

    /**
     * Find every affiliate-booster/propsandcons block in the post and
     * replace it with a simple-pros-cons/pros-cons block.
     *
     * Items are extracted from the saved HTML (the block innerHTML)
     * because the RichList attribute format varies between AB versions.
     */
    private function detect_affiliate_booster(string $content): array
    {
        $blocks = parse_blocks($content);
        $new_blocks = array();
        $count = 0;
        $pairs = array();

        foreach ($blocks as $block) {
            $is_ab_block = (($block['blockName'] ?? '') === 'affiliate-booster/propsandcons');
            $has_ab_html = (strpos($block['innerHTML'] ?? '', 'wp-block-affiliate-booster-propsandcons') !== false);

            if (!$is_ab_block && !$has_ab_html) {
                $new_blocks[] = $block;
                continue;
            }

            // Extract from innerHTML (reliable for both block & raw HTML)
            $html = $block['innerHTML'];

            /* ── Titles ───────────────────────────────────────────── */
            $pros_title = 'Pros';
            if (preg_match('/<[^>]+affiliate-props-title[^>]*>\s*(.*?)\s*<\/[^>]+>/is', $html, $m)) {
                $pros_title = trim(strip_tags($m[1]));
            }

            $cons_title = 'Cons';
            if (preg_match('/<[^>]+affiliate-con[st]+[-\w]*title[^>]*>\s*(.*?)\s*<\/[^>]+>/is', $html, $m)) {
                $cons_title = trim(strip_tags($m[1]));
            }

            /* ── List items ───────────────────────────────────────── */
            $pros_items = array();
            if (preg_match('/<div[^>]+affiliate-props-list[^>]*>(.*?)<\/div>/is', $html, $div)) {
                $pros_items = $this->extract_items_from_html($div[1]);
            }

            $cons_items = array();
            if (preg_match('/<div[^>]+affiliate-cons-list[^>]*>(.*?)<\/div>/is', $html, $div)) {
                $cons_items = $this->extract_items_from_html($div[1]);
            }

            // Attributes fallback (only if it was a block)
            if ($is_ab_block) {
                if (empty($pros_items) && !empty($block['attrs']['listTextItems'])) {
                    foreach ((array) $block['attrs']['listTextItems'] as $item) {
                        $t = trim(strip_tags(is_string($item) ? $item : (string) $item));
                        if ($t !== '')
                            $pros_items[] = $t;
                    }
                }
                if (empty($cons_items) && !empty($block['attrs']['listTextItemsTwo'])) {
                    foreach ((array) $block['attrs']['listTextItemsTwo'] as $item) {
                        $t = trim(strip_tags(is_string($item) ? $item : (string) $item));
                        if ($t !== '')
                            $cons_items[] = $t;
                    }
                }
            }

            // Final fallback defaults
            if ($pros_title === '')
                $pros_title = 'Pros';
            if ($cons_title === '')
                $cons_title = 'Cons';

            $new_blocks[] = $this->make_block_array($pros_title, $pros_items, $cons_title, $cons_items);
            $pairs[] = array(
                'pros_title' => $pros_title,
                'pros_items' => $pros_items,
                'cons_title' => $cons_title,
                'cons_items' => $cons_items,
            );
            $count++;
        }

        if ($count === 0) {
            return array('found' => false, 'count' => 0, 'pairs' => array(), 'new_content' => $content);
        }

        return array(
            'found' => true,
            'count' => $count,
            'pairs' => $pairs,
            'new_content' => serialize_blocks($new_blocks),
        );
    }

    // ─────────────────────────────────────────────────────────────────
    // Gutenberg (block-based) detection
    // ─────────────────────────────────────────────────────────────────

    private function detect_gutenberg(string $content): array
    {
        $blocks = parse_blocks($content);
        $new_blocks = array();
        $count = 0;
        $pairs = array();
        $i = 0;
        $total = count($blocks);

        while ($i < $total) {
            $block = $blocks[$i];

            // Try to match: Heading(pros/cons) → List → Heading(other) → List
            $type = $this->heading_type($block);

            if ($type !== null && isset($blocks[$i + 1]) && $this->is_list_block($blocks[$i + 1])) {

                $first_title = $this->heading_text($block);
                $first_items = $this->list_items($blocks[$i + 1]);

                // Look for the companion heading+list
                if (
                    isset($blocks[$i + 2]) &&
                    $this->heading_type($blocks[$i + 2]) !== null &&
                    $this->heading_type($blocks[$i + 2]) !== $type &&
                    isset($blocks[$i + 3]) &&
                    $this->is_list_block($blocks[$i + 3])
                ) {
                    $second_title = $this->heading_text($blocks[$i + 2]);
                    $second_items = $this->list_items($blocks[$i + 3]);

                    list($pros_title, $pros_items, $cons_title, $cons_items) =
                        $type === 'pros'
                        ? array($first_title, $first_items, $second_title, $second_items)
                        : array($second_title, $second_items, $first_title, $first_items);

                    $new_blocks[] = $this->make_block_array($pros_title, $pros_items, $cons_title, $cons_items);
                    $pairs[] = array(
                        'pros_title' => $pros_title,
                        'pros_items' => $pros_items,
                        'cons_title' => $cons_title,
                        'cons_items' => $cons_items,
                    );
                    $count++;
                    $i += 4;
                    continue;
                }

                // Only one side found — still convert with empty opposite
                list($pros_title, $pros_items, $cons_title, $cons_items) =
                    $type === 'pros'
                    ? array($first_title, $first_items, 'Cons', array())
                    : array('Pros', array(), $first_title, $first_items);

                $new_blocks[] = $this->make_block_array($pros_title, $pros_items, $cons_title, $cons_items);
                $pairs[] = compact('pros_title', 'pros_items', 'cons_title', 'cons_items');
                $count++;
                $i += 2;
                continue;
            }

            $new_blocks[] = $block;
            $i++;
        }

        if ($count === 0) {
            return array('found' => false, 'count' => 0, 'pairs' => array(), 'new_content' => $content);
        }

        return array(
            'found' => true,
            'count' => $count,
            'pairs' => $pairs,
            'new_content' => serialize_blocks($new_blocks),
        );
    }

    // ─────────────────────────────────────────────────────────────────
    // Classic HTML detection
    // ─────────────────────────────────────────────────────────────────

    private function detect_classic(string $content): array
    {
        $count = 0;
        $pairs = array();

        // Match: <hN>...pros...</hN> \n <ul>...</ul>
        //   and: <hN>...cons...</hN> \n <ul>...</ul>
        // Optionally they appear together in either order.
        $heading_list = '/<h([1-6])[^>]*>(.*?)<\/h\1>\s*(?:<p[^>]*>.*?<\/p>\s*)?<(?:ul|ol)[^>]*>(.*?)<\/(?:ul|ol)>/is';

        $segments = array();
        preg_match_all($heading_list, $content, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        foreach ($matches as $match) {
            $heading_html = $match[2][0];
            $heading_text = strtolower(trim(strip_tags($heading_html)));
            $type = null;

            if (preg_match('/^(pros?|advantages?|benefits?|positives?|strengths?|the good|what we liked)\b/u', $heading_text)) {
                $type = 'pros';
            } elseif (preg_match('/^(cons?|disadvantages?|drawbacks?|negatives?|weaknesses?|the bad|what we didn.?t like)\b/u', $heading_text)) {
                $type = 'cons';
            }

            if ($type) {
                $items = $this->extract_items_from_html($match[3][0]);
                $segments[] = array(
                    'type' => $type,
                    'title' => trim(strip_tags($heading_html)),
                    'items' => $items,
                    'full' => $match[0][0],
                    'offset' => $match[0][1],
                );
            }
        }

        if (empty($segments)) {
            return array('found' => false, 'count' => 0, 'pairs' => array(), 'new_content' => $content);
        }

        // Pair up consecutive pros+cons segments
        $new_content = $content;
        $offset_adjustment = 0;  // track content length changes
        $i = 0;

        while ($i < count($segments)) {
            $seg = $segments[$i];

            if (
                isset($segments[$i + 1]) &&
                $segments[$i + 1]['type'] !== $seg['type']
            ) {
                $next = $segments[$i + 1];

                list($pros_title, $pros_items, $cons_title, $cons_items) =
                    $seg['type'] === 'pros'
                    ? array($seg['title'], $seg['items'], $next['title'], $next['items'])
                    : array($next['title'], $next['items'], $seg['title'], $seg['items']);

                $block_comment = $this->make_block_comment($pros_title, $pros_items, $cons_title, $cons_items);
                $search_pattern = '/' . preg_quote($seg['full'], '/') . '\s*' . preg_quote($next['full'], '/') . '/s';
                $new_content = preg_replace($search_pattern, $block_comment, $new_content, 1);

                $pairs[] = compact('pros_title', 'pros_items', 'cons_title', 'cons_items');
                $count++;
                $i += 2;
                continue;
            }

            // Solo section
            list($pros_title, $pros_items, $cons_title, $cons_items) =
                $seg['type'] === 'pros'
                ? array($seg['title'], $seg['items'], 'Cons', array())
                : array('Pros', array(), $seg['title'], $seg['items']);

            $block_comment = $this->make_block_comment($pros_title, $pros_items, $cons_title, $cons_items);
            $new_content = str_replace($seg['full'], $block_comment, $new_content);

            $pairs[] = compact('pros_title', 'pros_items', 'cons_title', 'cons_items');
            $count++;
            $i++;
        }

        return array(
            'found' => $count > 0,
            'count' => $count,
            'pairs' => $pairs,
            'new_content' => $new_content,
        );
    }

    // ─────────────────────────────────────────────────────────────────
    // Block helpers
    // ─────────────────────────────────────────────────────────────────

    /**
     * Detect whether a parsed block is a pros or cons heading.
     *
     * @return string|null  'pros', 'cons', or null.
     */
    private function heading_type(array $block): ?string
    {
        if (($block['blockName'] ?? '') !== 'core/heading') {
            return null;
        }
        $text = strtolower(trim(strip_tags($block['innerHTML'] ?? '')));

        if (preg_match('/^(pros?|advantages?|benefits?|positives?|strengths?|the good|what we liked)\b/u', $text)) {
            return 'pros';
        }
        if (preg_match('/^(cons?|disadvantages?|drawbacks?|negatives?|weaknesses?|the bad|what we didn.?t like)\b/u', $text)) {
            return 'cons';
        }
        return null;
    }

    private function heading_text(array $block): string
    {
        return trim(strip_tags($block['innerHTML'] ?? ''));
    }

    private function is_list_block(array $block): bool
    {
        return in_array($block['blockName'] ?? '', array('core/list', 'core/list-item'), true);
    }

    /**
     * Extract plain-text items from a core/list block.
     */
    private function list_items(array $block): array
    {
        $items = array();

        // Newer WP: items are core/list-item inner blocks
        if (!empty($block['innerBlocks'])) {
            foreach ($block['innerBlocks'] as $inner) {
                if (($inner['blockName'] ?? '') === 'core/list-item') {
                    $text = trim(strip_tags($inner['innerHTML'] ?? ''));
                    if ($text !== '') {
                        $items[] = $text;
                    }
                }
            }
        }

        // Fallback: parse innerHTML directly
        if (empty($items) && !empty($block['innerHTML'])) {
            $items = $this->extract_items_from_html($block['innerHTML']);
        }

        return array_values(array_filter($items));
    }

    /**
     * Build a parsed-block array for serialize_blocks().
     */
    private function make_block_array(
        string $pros_title,
        array $pros_items,
        string $cons_title,
        array $cons_items
    ): array {
        return array(
            'blockName' => 'simple-pros-cons/pros-cons',
            'attrs' => array(
                'prosTitle' => $pros_title,
                'consTitle' => $cons_title,
                'prosItems' => array_values($pros_items),
                'consItems' => array_values($cons_items),
            ),
            'innerBlocks' => array(),
            'innerHTML' => '',
            'innerContent' => array(),
        );
    }

    /**
     * Build raw block-comment markup (for classic-post insertions).
     */
    private function make_block_comment(
        string $pros_title,
        array $pros_items,
        string $cons_title,
        array $cons_items
    ): string {
        $attrs = wp_json_encode(array(
            'prosTitle' => $pros_title,
            'consTitle' => $cons_title,
            'prosItems' => array_values($pros_items),
            'consItems' => array_values($cons_items),
        ));
        return "\n<!-- wp:simple-pros-cons/pros-cons {$attrs} /-->\n";
    }

    // ─────────────────────────────────────────────────────────────────
    // HTML helpers
    // ─────────────────────────────────────────────────────────────────

    private function extract_items_from_html(string $html): array
    {
        $items = array();
        preg_match_all('/<li[^>]*>(.*?)<\/li>/is', $html, $matches);
        foreach ($matches[1] as $match) {
            $text = trim(strip_tags($match));
            if ($text !== '') {
                $items[] = $text;
            }
        }
        return $items;
    }
}
