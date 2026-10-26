<?php
/**
 * Standalone Duplicate Post Slug Detector Engine & CSV Generator
 */

if (!defined('ABSPATH')) {
    exit;
}

class Duplicate_Post_Detector {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Helper to compute base slug by stripping auto-increment numbers like -2, -3
     */
    public function get_base_slug($slug) {
        if (empty($slug)) {
            return '';
        }

        // Strip trailing -2, -3, -45 etc.
        $base = preg_replace('/-\d+$/', '', strtolower(trim($slug)));
        return $base;
    }

    /**
     * Scan site posts for duplicate slugs or titles
     *
     * @param array $args Filter options (mode, post_type, post_status, search, min_occurrences)
     * @return array Scan results & summary metrics
     */
    public function scan_duplicates($args = array()) {
        $mode = isset($args['mode']) ? sanitize_text_field($args['mode']) : 'base_slug';

        $post_types = isset($args['post_type']) && !empty($args['post_type']) && $args['post_type'] !== 'all'
            ? array($args['post_type'])
            : array_values(get_post_types(array('public' => true), 'names'));

        $post_statuses = isset($args['post_status']) && !empty($args['post_status']) && $args['post_status'] !== 'all'
            ? array($args['post_status'])
            : array('publish', 'draft', 'pending', 'future', 'private', 'trash');

        $min_occurrences = isset($args['min_occurrences']) ? max(2, intval($args['min_occurrences'])) : 2;

        $query_args = array(
            'post_type'      => $post_types,
            'post_status'    => $post_statuses,
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        if (!empty($args['search'])) {
            $query_args['s'] = sanitize_text_field($args['search']);
        }

        $query = new WP_Query($query_args);
        $posts = $query->posts;

        // Grouping map
        $groups_map = array();

        foreach ($posts as $post) {
            $pid = $post->ID;
            $title = get_the_title($post) ? get_the_title($post) : '(No Title - ID #' . $pid . ')';
            $slug = $post->post_name;

            if (empty($slug) && !empty($title)) {
                $slug = sanitize_title($title);
            }

            if (empty($slug)) {
                continue;
            }

            // Determine key based on matching mode
            if ($mode === 'exact_slug') {
                $key = strtolower(trim($slug));
                $display_key = $slug;
            } else if ($mode === 'exact_title') {
                $key = strtolower(trim($title));
                $display_key = $title;
            } else {
                // Default: base_slug (strips -2, -3)
                $key = $this->get_base_slug($slug);
                $display_key = $key;
            }

            if (empty($key)) {
                continue;
            }

            $edit_url = get_edit_post_link($pid, 'raw');

            $post_info = array(
                'id'          => $pid,
                'title'       => $title,
                'slug'        => $slug,
                'base_slug'   => $this->get_base_slug($slug),
                'url'         => get_permalink($pid),
                'edit_url'    => $edit_url ? $edit_url : admin_url('post.php?post=' . $pid . '&action=edit'),
                'post_type'   => $post->post_type,
                'post_status' => $post->post_status,
                'date'        => get_the_date('Y-m-d H:i:s', $pid),
            );

            if (!isset($groups_map[$key])) {
                $groups_map[$key] = array(
                    'group_key'    => $display_key,
                    'base_slug'    => $this->get_base_slug($slug),
                    'total_count'  => 0,
                    'posts'        => array(),
                );
            }

            $groups_map[$key]['total_count']++;
            $groups_map[$key]['posts'][] = $post_info;
        }

        // Filter out items with count < min_occurrences
        $duplicate_groups = array();
        $total_duplicate_posts = 0;
        $max_duplicate_count = 0;

        foreach ($groups_map as $key => $group) {
            if ($group['total_count'] >= $min_occurrences) {
                $total_duplicate_posts += $group['total_count'];
                if ($group['total_count'] > $max_duplicate_count) {
                    $max_duplicate_count = $group['total_count'];
                }

                $duplicate_groups[] = array(
                    'group_key'   => $group['group_key'],
                    'base_slug'   => $group['base_slug'],
                    'count'       => $group['total_count'],
                    'posts'       => $group['posts'],
                );
            }
        }

        // Sort duplicate groups by occurrence count descending
        usort($duplicate_groups, function($a, $b) {
            return $b['count'] - $a['count'];
        });

        return array(
            'success'   => true,
            'summary'   => array(
                'total_scanned_posts'   => count($posts),
                'duplicate_groups_count'=> count($duplicate_groups),
                'total_duplicate_posts' => $total_duplicate_posts,
                'max_duplication_count' => $max_duplicate_count,
            ),
            'duplicate_groups' => $duplicate_groups,
            'timestamp'        => current_time('mysql'),
        );
    }

    /**
     * Export duplicate groups to CSV format
     *
     * @param array $groups Duplicate groups data
     * @param bool $download Stream download or return string
     */
    public function export_csv($groups, $download = true) {
        if ($download) {
            $filename = 'duplicate-posts-' . date('Y-m-d-His') . '.csv';

            if (ob_get_length()) {
                ob_clean();
            }

            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Pragma: no-cache');
            header('Expires: 0');

            $output = fopen('php://output', 'w');

            // UTF-8 BOM for Microsoft Excel compatibility
            fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // CSV Header Row
            fputcsv($output, array(
                'Duplicate Group (Base Slug)',
                'Duplicate Group Count',
                'Post ID',
                'Post Name / Title',
                'Exact Post Slug',
                'Post URL',
                'Edit Post Link',
                'Post Type',
                'Post Status',
                'Published Date'
            ));

            foreach ($groups as $group) {
                foreach ($group['posts'] as $p) {
                    fputcsv($output, array(
                        $group['group_key'],
                        $group['count'],
                        $p['id'],
                        $p['title'],
                        $p['slug'],
                        $p['url'],
                        $p['edit_url'],
                        $p['post_type'],
                        $p['post_status'],
                        $p['date']
                    ));
                }
            }

            fclose($output);
            exit;
        } else {
            $stream = fopen('php://temp', 'r+');
            fprintf($stream, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($stream, array(
                'Duplicate Group (Base Slug)',
                'Duplicate Group Count',
                'Post ID',
                'Post Name / Title',
                'Exact Post Slug',
                'Post URL',
                'Edit Post Link',
                'Post Type',
                'Post Status',
                'Published Date'
            ));

            foreach ($groups as $group) {
                foreach ($group['posts'] as $p) {
                    fputcsv($stream, array(
                        $group['group_key'],
                        $group['count'],
                        $p['id'],
                        $p['title'],
                        $p['slug'],
                        $p['url'],
                        $p['edit_url'],
                        $p['post_type'],
                        $p['post_status'],
                        $p['date']
                    ));
                }
            }

            rewind($stream);
            $csv_content = stream_get_contents($stream);
            fclose($stream);

            return $csv_content;
        }
    }
}
