<?php
/**
 * Standalone Product Card Scanner Engine & CSV Generator
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProductCard_Post_Scanner {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Common block name patterns to match Product Cards
     */
    private function get_target_block_patterns() {
        return array(
            'product-card-plugin/product-card',
            'product-card/card',
            'cardplugin/card',
            'create-block/product-card-plugin',
            'product-card-dhs',
            'product-card-grc',
            'product-card-scde',
            'product-card-sm',
            'productcardbhfr',
            'product-card',
            'productcard',
        );
    }

    /**
     * Scan site posts for Product Card block instances
     *
     * @param array $args Filter options
     * @return array Scan results & summary metrics
     */
    public function scan_posts($args = array()) {
        $post_types = isset($args['post_type']) && !empty($args['post_type']) && $args['post_type'] !== 'all'
            ? array($args['post_type'])
            : array_values(get_post_types(array('public' => true), 'names'));

        $post_statuses = isset($args['post_status']) && !empty($args['post_status']) && $args['post_status'] !== 'all'
            ? array($args['post_status'])
            : array('publish', 'draft', 'pending', 'future', 'private');

        $query_args = array(
            'post_type'      => $post_types,
            'post_status'    => $post_statuses,
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC',
            'fields'         => 'ids',
        );

        if (!empty($args['search'])) {
            $query_args['s'] = sanitize_text_field($args['search']);
        }

        $query = new WP_Query($query_args);
        $post_ids = $query->posts;

        $matched_posts = array();
        $total_cards_count = 0;
        $all_unique_products = array();

        $patterns = $this->get_target_block_patterns();

        foreach ($post_ids as $pid) {
            $post = get_post($pid);
            if (!$post || empty($post->post_content)) {
                continue;
            }

            $content = $post->post_content;
            $card_count = 0;
            $found_products = array();

            // 1. Gutenberg block parser detection
            if (has_blocks($content)) {
                $blocks = parse_blocks($content);
                $this->extract_card_data_from_blocks($blocks, $patterns, $card_count, $found_products);
            }

            // 2. Fallback regex detection for raw block comment tags & HTML markup
            if ($card_count === 0) {
                $this->extract_card_data_from_raw_content($content, $card_count, $found_products);
            }

            if ($card_count > 0) {
                $found_products = array_values(array_unique(array_filter($found_products)));
                $total_cards_count += $card_count;

                foreach ($found_products as $pname) {
                    $all_unique_products[$pname] = true;
                }

                $edit_url = get_edit_post_link($pid, 'raw');

                $matched_posts[] = array(
                    'id'           => $pid,
                    'title'        => get_the_title($pid) ? get_the_title($pid) : '(No Title - ID #' . $pid . ')',
                    'url'          => get_permalink($pid),
                    'edit_url'     => $edit_url ? $edit_url : admin_url('post.php?post=' . $pid . '&action=edit'),
                    'post_type'    => get_post_type($pid),
                    'post_status'  => get_post_status($pid),
                    'card_count'   => $card_count,
                    'products'     => $found_products,
                    'product_list' => !empty($found_products) ? implode(', ', $found_products) : 'N/A',
                    'date'         => get_the_date('Y-m-d H:i:s', $pid),
                );
            }
        }

        return array(
            'success'   => true,
            'summary'   => array(
                'total_scanned_posts' => count($post_ids),
                'matched_posts_count' => count($matched_posts),
                'total_cards_found'   => $total_cards_count,
                'unique_products'     => count($all_unique_products),
            ),
            'posts'     => $matched_posts,
            'timestamp' => current_time('mysql'),
        );
    }

    /**
     * Recursively parse Gutenberg blocks for Product Card blocks
     */
    private function extract_card_data_from_blocks($blocks, $patterns, &$card_count, &$found_products) {
        if (!is_array($blocks)) {
            return;
        }

        foreach ($blocks as $block) {
            if (empty($block['blockName'])) {
                if (!empty($block['innerBlocks'])) {
                    $this->extract_card_data_from_blocks($block['innerBlocks'], $patterns, $card_count, $found_products);
                }
                continue;
            }

            $bname = strtolower($block['blockName']);
            $is_target = false;

            foreach ($patterns as $pattern) {
                if (strpos($bname, strtolower($pattern)) !== false) {
                    $is_target = true;
                    break;
                }
            }

            if ($is_target) {
                $card_count++;
                if (!empty($block['attrs']['productName'])) {
                    $found_products[] = sanitize_text_field($block['attrs']['productName']);
                }
            }

            if (!empty($block['innerBlocks'])) {
                $this->extract_card_data_from_blocks($block['innerBlocks'], $patterns, $card_count, $found_products);
            }
        }
    }

    /**
     * Regex fallback for comment blocks or HTML attributes
     */
    private function extract_card_data_from_raw_content($content, &$card_count, &$found_products) {
        // Regex 1: Gutenberg block comments
        $comment_pattern = '/<!--\s+wp:(?:[a-z0-9-]+\/)?product-card[a-z0-9-]*\s+(\{.*?\})\s+\/-->/is';
        if (preg_match_all($comment_pattern, $content, $matches)) {
            $card_count += count($matches[0]);
            foreach ($matches[1] as $json_str) {
                $json = json_decode($json_str, true);
                if (is_array($json) && !empty($json['productName'])) {
                    $found_products[] = sanitize_text_field($json['productName']);
                }
            }
        }

        // Regex 2: Direct outer HTML wrapper data attribute
        if ($card_count === 0 && preg_match_all('/class="[^"]*pcpx-(?:product-card-outer|card)[^"]*"/i', $content, $html_matches)) {
            $card_count += count($html_matches[0]);
            if (preg_match_all('/data-product-name="([^"]+)"/i', $content, $name_matches)) {
                foreach ($name_matches[1] as $pname) {
                    $found_products[] = sanitize_text_field($pname);
                }
            }
        }

        // Regex 3: General keyword check if card blocks are detected
        if ($card_count === 0 && (strpos($content, 'wp:product-card') !== false || strpos($content, 'pcpx-product-card-outer') !== false)) {
            $card_count++;
        }
    }

    /**
     * Generate CSV export stream or CSV string
     *
     * @param array $posts Matched posts dataset
     * @param bool $download Stream download or return string
     */
    public function export_csv($posts, $download = true) {
        if ($download) {
            $filename = 'product-card-posts-' . date('Y-m-d-His') . '.csv';

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

            // CSV Columns Header
            fputcsv($output, array(
                'Post ID',
                'Post Name / Title',
                'Post URL',
                'Edit Post Link',
                'Post Type',
                'Post Status',
                'Product Cards Count',
                'Detected Product Names',
                'Published Date'
            ));

            foreach ($posts as $p) {
                fputcsv($output, array(
                    $p['id'],
                    $p['title'],
                    $p['url'],
                    $p['edit_url'],
                    $p['post_type'],
                    $p['post_status'],
                    $p['card_count'],
                    $p['product_list'],
                    $p['date']
                ));
            }

            fclose($output);
            exit;
        } else {
            $stream = fopen('php://temp', 'r+');
            fprintf($stream, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($stream, array(
                'Post ID',
                'Post Name / Title',
                'Post URL',
                'Edit Post Link',
                'Post Type',
                'Post Status',
                'Product Cards Count',
                'Detected Product Names',
                'Published Date'
            ));

            foreach ($posts as $p) {
                fputcsv($stream, array(
                    $p['id'],
                    $p['title'],
                    $p['url'],
                    $p['edit_url'],
                    $p['post_type'],
                    $p['post_status'],
                    $p['card_count'],
                    $p['product_list'],
                    $p['date']
                ));
            }

            rewind($stream);
            $csv_content = stream_get_contents($stream);
            fclose($stream);

            return $csv_content;
        }
    }
}
