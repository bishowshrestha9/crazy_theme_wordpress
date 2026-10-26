<?php
/**
 * Standalone Product Card Duplicate Detector Engine & CSV Generator
 */

if (!defined('ABSPATH')) {
    exit;
}

class ProductCard_Duplicate_Detector {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Common block name patterns for Product Cards
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
     * Scan site posts and detect duplicate product names
     *
     * @param array $args Filter parameters
     * @return array Detection results and metrics
     */
    public function scan_duplicates($args = array()) {
        $post_types = isset($args['post_type']) && !empty($args['post_type']) && $args['post_type'] !== 'all'
            ? array($args['post_type'])
            : array_values(get_post_types(array('public' => true), 'names'));

        $post_statuses = isset($args['post_status']) && !empty($args['post_status']) && $args['post_status'] !== 'all'
            ? array($args['post_status'])
            : array('publish', 'draft', 'pending', 'future', 'private');

        $min_occurrences = isset($args['min_occurrences']) ? max(2, intval($args['min_occurrences'])) : 2;

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

        $patterns = $this->get_target_block_patterns();

        // Product map: key = normalized product name, value = data array
        $product_map = array();
        $total_cards_found = 0;

        foreach ($post_ids as $pid) {
            $post = get_post($pid);
            if (!$post || empty($post->post_content)) {
                continue;
            }

            $content = $post->post_content;
            $card_count = 0;
            $found_products = array();

            // 1. Gutenberg block parser
            if (has_blocks($content)) {
                $blocks = parse_blocks($content);
                $this->extract_card_data_from_blocks($blocks, $patterns, $card_count, $found_products);
            }

            // 2. Fallback regex search
            if ($card_count === 0) {
                $this->extract_card_data_from_raw_content($content, $card_count, $found_products);
            }

            if ($card_count > 0) {
                $total_cards_found += $card_count;

                $edit_url = get_edit_post_link($pid, 'raw');
                $post_info = array(
                    'id'          => $pid,
                    'title'       => get_the_title($pid) ? get_the_title($pid) : '(No Title - ID #' . $pid . ')',
                    'url'         => get_permalink($pid),
                    'edit_url'    => $edit_url ? $edit_url : admin_url('post.php?post=' . $pid . '&action=edit'),
                    'post_type'   => get_post_type($pid),
                    'post_status' => get_post_status($pid),
                    'date'        => get_the_date('Y-m-d H:i:s', $pid),
                );

                if (!empty($found_products)) {
                    foreach ($found_products as $pname) {
                        $pname_clean = trim($pname);
                        if (empty($pname_clean)) {
                            continue;
                        }

                        $norm_key = strtolower($pname_clean);

                        if (!isset($product_map[$norm_key])) {
                            $product_map[$norm_key] = array(
                                'display_name' => $pname_clean,
                                'total_count'  => 0,
                                'posts_map'    => array(),
                            );
                        }

                        $product_map[$norm_key]['total_count']++;

                        if (!isset($product_map[$norm_key]['posts_map'][$pid])) {
                            $product_map[$norm_key]['posts_map'][$pid] = $post_info;
                            $product_map[$norm_key]['posts_map'][$pid]['count_in_post'] = 1;
                        } else {
                            $product_map[$norm_key]['posts_map'][$pid]['count_in_post']++;
                        }
                    }
                }
            }
        }

        // Filter map to keep only duplicates matching min_occurrences threshold
        $duplicates_list = array();
        $total_duplicate_occurrences = 0;

        foreach ($product_map as $key => $data) {
            if ($data['total_count'] >= $min_occurrences) {
                $posts_list = array_values($data['posts_map']);
                $total_duplicate_occurrences += $data['total_count'];

                $duplicates_list[] = array(
                    'product_name' => $data['display_name'],
                    'count'        => $data['total_count'],
                    'posts_count'  => count($posts_list),
                    'posts'        => $posts_list,
                );
            }
        }

        // Sort duplicates by occurrence count descending
        usort($duplicates_list, function($a, $b) {
            return $b['count'] - $a['count'];
        });

        return array(
            'success'   => true,
            'summary'   => array(
                'total_scanned_posts'         => count($post_ids),
                'total_cards_found'           => $total_cards_found,
                'total_duplicate_products'    => count($duplicates_list),
                'total_duplicate_occurrences' => $total_duplicate_occurrences,
            ),
            'duplicates' => $duplicates_list,
            'timestamp'  => current_time('mysql'),
        );
    }

    /**
     * Recursively parse Gutenberg blocks
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

        if ($card_count === 0 && preg_match_all('/class="[^"]*pcpx-(?:product-card-outer|card)[^"]*"/i', $content, $html_matches)) {
            $card_count += count($html_matches[0]);
            if (preg_match_all('/data-product-name="([^"]+)"/i', $content, $name_matches)) {
                foreach ($name_matches[1] as $pname) {
                    $found_products[] = sanitize_text_field($pname);
                }
            }
        }
    }

    /**
     * Export duplicates to CSV file stream or return CSV string
     */
    public function export_csv($duplicates, $download = true) {
        if ($download) {
            $filename = 'duplicate-product-names-' . date('Y-m-d-His') . '.csv';

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

            // CSV Columns
            fputcsv($output, array(
                'Product Name',
                'Total Duplicate Occurrences',
                'Post ID',
                'Post Name / Title',
                'Post URL',
                'Edit Post Link',
                'Post Type',
                'Post Status',
                'Cards in Post',
                'Published Date'
            ));

            foreach ($duplicates as $dup) {
                foreach ($dup['posts'] as $p) {
                    fputcsv($output, array(
                        $dup['product_name'],
                        $dup['count'],
                        $p['id'],
                        $p['title'],
                        $p['url'],
                        $p['edit_url'],
                        $p['post_type'],
                        $p['post_status'],
                        $p['count_in_post'],
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
                'Product Name',
                'Total Duplicate Occurrences',
                'Post ID',
                'Post Name / Title',
                'Post URL',
                'Edit Post Link',
                'Post Type',
                'Post Status',
                'Cards in Post',
                'Published Date'
            ));

            foreach ($duplicates as $dup) {
                foreach ($dup['posts'] as $p) {
                    fputcsv($stream, array(
                        $dup['product_name'],
                        $dup['count'],
                        $p['id'],
                        $p['title'],
                        $p['url'],
                        $p['edit_url'],
                        $p['post_type'],
                        $p['post_status'],
                        $p['count_in_post'],
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
