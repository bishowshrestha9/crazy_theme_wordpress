<?php
if (!defined('ABSPATH')) {
    exit;
}

class TWR_Scanner {

    /**
     * Get all public post types to scan.
     */
    public function get_scannable_post_types() {
        $post_types = get_post_types(array('public' => true), 'names');
        // Remove attachment
        unset($post_types['attachment']);
        return array_values($post_types);
    }

    /**
     * Scan posts with pagination and filter arguments.
     *
     * @param array $args
     * @return array
     */
    public function scan_posts($args = array()) {
        global $wpdb;

        $defaults = array(
            'posts_per_page' => 20,
            'paged'          => 1,
            'post_type'      => '',
            'search'         => '',
            'keyword'        => 'Trustpilot',
        );

        $params = wp_parse_args($args, $defaults);
        $keyword = sanitize_text_field($params['keyword']);
        if (empty($keyword)) {
            $keyword = 'Trustpilot';
        }

        $offset = ($params['paged'] - 1) * $params['posts_per_page'];
        $post_types = !empty($params['post_type']) ? array($params['post_type']) : $this->get_scannable_post_types();

        if (empty($post_types)) {
            return array(
                'items'       => array(),
                'total'       => 0,
                'max_pages'   => 0,
                'occurrences' => 0,
            );
        }

        $post_type_placeholders = implode(',', array_fill(0, count($post_types), '%s'));
        $like_keyword = '%' . $wpdb->esc_like($keyword) . '%';

        $where_clauses = array(
            "post_type IN ($post_type_placeholders)",
            "post_status IN ('publish', 'draft', 'pending', 'future', 'private')",
            "(post_content LIKE %s OR post_title LIKE %s OR post_excerpt LIKE %s)"
        );

        $query_params = array_merge($post_types, array($like_keyword, $like_keyword, $like_keyword));

        if (!empty($params['search'])) {
            $search_like = '%' . $wpdb->esc_like(sanitize_text_field($params['search'])) . '%';
            $where_clauses[] = "(post_title LIKE %s OR ID = %d)";
            $query_params[] = $search_like;
            $query_params[] = intval($params['search']);
        }

        $where_sql = implode(' AND ', $where_clauses);

        // Count Total Posts
        $count_sql = "SELECT COUNT(DISTINCT ID) FROM {$wpdb->posts} WHERE {$where_sql}";
        $total_posts = (int) $wpdb->get_var($wpdb->prepare($count_sql, $query_params));

        // Get Paginated Posts
        $posts_sql = "SELECT ID, post_title, post_type, post_status, post_content, post_modified 
                      FROM {$wpdb->posts} 
                      WHERE {$where_sql} 
                      ORDER BY ID DESC 
                      LIMIT %d OFFSET %d";
        
        $posts_query_params = array_merge($query_params, array($params['posts_per_page'], $offset));
        $raw_posts = $wpdb->get_results($wpdb->prepare($posts_sql, $posts_query_params));

        $results = array();
        $total_occurrences_count = 0;

        foreach ($raw_posts as $post) {
            $content = $post->post_content;
            $title   = $post->post_title;

            $matches_count = substr_count(strtolower($content), strtolower($keyword));
            $title_matches = substr_count(strtolower($title), strtolower($keyword));
            $total_post_matches = $matches_count + $title_matches;
            $total_occurrences_count += $total_post_matches;

            $snippets = $this->extract_snippets($content, $keyword);
            $edit_link = get_edit_post_link($post->ID, 'raw');
            $view_link = get_permalink($post->ID);

            $results[] = array(
                'id'            => $post->ID,
                'title'         => !empty($title) ? $title : __('(No Title)', 'trustpilot-word-remover'),
                'post_type'     => $post->post_type,
                'post_status'   => $post->post_status,
                'occurrences'   => $total_post_matches,
                'snippets'      => $snippets,
                'edit_link'     => $edit_link,
                'view_link'     => $view_link,
                'post_modified' => $post->post_modified,
            );
        }

        $max_pages = $params['posts_per_page'] > 0 ? ceil($total_posts / $params['posts_per_page']) : 1;

        return array(
            'items'       => $results,
            'total'       => $total_posts,
            'max_pages'   => $max_pages,
            'occurrences' => $total_occurrences_count,
        );
    }

    /**
     * Get ALL matching post IDs without pagination.
     *
     * @param array $args
     * @return array Array of post IDs
     */
    public function get_all_matching_post_ids($args = array()) {
        global $wpdb;

        $defaults = array(
            'post_type' => '',
            'search'    => '',
            'keyword'   => 'Trustpilot',
        );

        $params = wp_parse_args($args, $defaults);
        $keyword = sanitize_text_field($params['keyword']);
        if (empty($keyword)) {
            $keyword = 'Trustpilot';
        }

        $post_types = !empty($params['post_type']) ? array($params['post_type']) : $this->get_scannable_post_types();

        if (empty($post_types)) {
            return array();
        }

        $post_type_placeholders = implode(',', array_fill(0, count($post_types), '%s'));
        $like_keyword = '%' . $wpdb->esc_like($keyword) . '%';

        $where_clauses = array(
            "post_type IN ($post_type_placeholders)",
            "post_status IN ('publish', 'draft', 'pending', 'future', 'private')",
            "(post_content LIKE %s OR post_title LIKE %s OR post_excerpt LIKE %s)"
        );

        $query_params = array_merge($post_types, array($like_keyword, $like_keyword, $like_keyword));

        if (!empty($params['search'])) {
            $search_like = '%' . $wpdb->esc_like(sanitize_text_field($params['search'])) . '%';
            $where_clauses[] = "(post_title LIKE %s OR ID = %d)";
            $query_params[] = $search_like;
            $query_params[] = intval($params['search']);
        }

        $where_sql = implode(' AND ', $where_clauses);

        $sql = "SELECT DISTINCT ID FROM {$wpdb->posts} WHERE {$where_sql} ORDER BY ID DESC";
        $ids = $wpdb->get_col($wpdb->prepare($sql, $query_params));

        return array_map('intval', $ids);
    }

    /**
     * Get summary counts (total posts scanned, total posts matching, total occurrences).
     */
    public function get_summary_stats($keyword = 'Trustpilot') {
        global $wpdb;
        $post_types = $this->get_scannable_post_types();
        if (empty($post_types)) {
            return array('total_scanned' => 0, 'total_matched' => 0, 'total_occurrences' => 0);
        }

        $post_type_placeholders = implode(',', array_fill(0, count($post_types), '%s'));

        // Total public posts
        $sql_all = "SELECT COUNT(ID) FROM {$wpdb->posts} WHERE post_type IN ($post_type_placeholders) AND post_status IN ('publish', 'draft', 'pending', 'future', 'private')";
        $total_scanned = (int) $wpdb->get_var($wpdb->prepare($sql_all, $post_types));

        // Matching posts
        $like_keyword = '%' . $wpdb->esc_like($keyword) . '%';
        $sql_matched = "SELECT ID, post_content, post_title FROM {$wpdb->posts} 
                        WHERE post_type IN ($post_type_placeholders) 
                        AND post_status IN ('publish', 'draft', 'pending', 'future', 'private')
                        AND (post_content LIKE %s OR post_title LIKE %s)";
        
        $params = array_merge($post_types, array($like_keyword, $like_keyword));
        $matched_rows = $wpdb->get_results($wpdb->prepare($sql_matched, $params));

        $total_matched = count($matched_rows);
        $total_occurrences = 0;
        foreach ($matched_rows as $row) {
            $total_occurrences += substr_count(strtolower($row->post_content), strtolower($keyword));
            $total_occurrences += substr_count(strtolower($row->post_title), strtolower($keyword));
        }

        return array(
            'total_scanned'     => $total_scanned,
            'total_matched'     => $total_matched,
            'total_occurrences' => $total_occurrences,
        );
    }

    /**
     * Extract context snippets around matched keyword.
     *
     * @param string $content
     * @param string $keyword
     * @param int $padding
     * @param int $max_snippets
     * @return array
     */
    public function extract_snippets($content, $keyword = 'Trustpilot', $padding = 60, $max_snippets = 3) {
        $snippets = array();
        $plain_text = strip_tags($content);
        $keyword_length = strlen($keyword);
        
        $offset = 0;
        $count = 0;
        while (($pos = stripos($plain_text, $keyword, $offset)) !== false && $count < $max_snippets) {
            $start = max(0, $pos - $padding);
            $length = ($pos - $start) + $keyword_length + $padding;
            $snippet_text = substr($plain_text, $start, $length);

            if ($start > 0) {
                $snippet_text = '...' . $snippet_text;
            }
            if (($start + $length) < strlen($plain_text)) {
                $snippet_text .= '...';
            }

            // Highlight keyword
            $highlighted = preg_replace(
                '/' . preg_quote($keyword, '/') . '/i',
                '<mark class="twr-highlight">$0</mark>',
                esc_html($snippet_text)
            );

            // Re-convert mark tags safely
            $highlighted = str_replace(
                array('&lt;mark class=&quot;twr-highlight&quot;&gt;', '&lt;/mark&gt;'),
                array('<mark class="twr-highlight">', '</mark>'),
                $highlighted
            );

            $snippets[] = $highlighted;
            $offset = $pos + $keyword_length;
            $count++;
        }

        return $snippets;
    }
}
