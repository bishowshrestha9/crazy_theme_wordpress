<?php
/**
 * GEO-Redirect Detector Engine for Pretty Links
 */

if (!defined('ABSPATH')) {
    exit;
}

class PLGD_Geo_Detector
{
    /**
     * Check if Pretty Links tables exist
     */
    public function check_tables_exist()
    {
        global $wpdb;
        $prli_table = $wpdb->prefix . 'prli_links';
        $exists = $wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $prli_table));

        if ($exists === $prli_table) {
            return array('success' => true, 'message' => '');
        }

        // Check if custom post type prli-link exists
        $cpt_count = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'prli-link'");
        if ($cpt_count > 0) {
            return array('success' => true, 'message' => '');
        }

        return array(
            'success' => false,
            'message' => __('Pretty Links database tables (prli_links) not found. Scanning post meta & fallback tables.', 'pretty-link-geo-detector')
        );
    }

    /**
     * Scan all Pretty Links for GEO-Redirect rules
     */
    public function scan_all_links()
    {
        global $wpdb;

        $prli_table = $wpdb->prefix . 'prli_links';
        $meta_table = $wpdb->prefix . 'prli_link_metas';
        $site_url = site_url();

        $scanned_links = array();
        $total_count = 0;
        $geo_count = 0;

        // Check if prli_links table exists
        $has_prli_table = ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $prli_table)) === $prli_table);
        $has_meta_table = ($wpdb->get_var($wpdb->prepare("SHOW TABLES LIKE %s", $meta_table)) === $meta_table);

        if ($has_prli_table) {
            $rows = $wpdb->get_results("SELECT id, slug, url, name, redirect_type, param_struct FROM {$prli_table} ORDER BY id DESC", ARRAY_A);

            // Fetch meta table if present
            $link_metas = array();
            if ($has_meta_table) {
                $raw_metas = $wpdb->get_results("SELECT link_id, meta_key, meta_value FROM {$meta_table}", ARRAY_A);
                foreach ($raw_metas as $m) {
                    $link_metas[$m['link_id']][$m['meta_key']] = $m['meta_value'];
                }
            }

            foreach ($rows as $row) {
                $total_count++;
                $id = (int) $row['id'];
                $slug = (string) ($row['slug'] ?? '');
                $target_url = (string) ($row['url'] ?? '');
                $name = (string) (!empty($row['name']) ? $row['name'] : $slug);
                $pretty_url = esc_url($site_url . '/' . $slug);

                $metas = $link_metas[$id] ?? array();
                $geo_info = $this->analyze_geo_rules($row, $metas, $id);

                if ($geo_info['has_geo']) {
                    $geo_count++;
                    $scanned_links[] = array(
                        'id' => $id,
                        'slug' => $slug,
                        'name' => $name,
                        'pretty_url' => $pretty_url,
                        'target_url' => $target_url,
                        'has_geo' => 'YES',
                        'geo_status' => __('Has GEO-Redirect', 'pretty-link-geo-detector'),
                        'geo_details' => $geo_info['details']
                    );
                }
            }
        } else {
            // CPT Fallback scan
            $posts = $wpdb->get_results("SELECT ID, post_title, post_name FROM {$wpdb->posts} WHERE post_type = 'prli-link' AND post_status = 'publish' ORDER BY ID DESC", ARRAY_A);

            foreach ($posts as $post) {
                $total_count++;
                $id = (int) $post['ID'];
                $slug = (string) $post['post_name'];
                $name = (string) (!empty($post['post_title']) ? $post['post_title'] : $slug);
                $pretty_url = esc_url($site_url . '/' . $slug);
                $target_url = get_post_meta($id, '_prli_target_url', true);

                $geo_info = $this->analyze_cpt_postmeta($id);

                if ($geo_info['has_geo']) {
                    $geo_count++;
                    $scanned_links[] = array(
                        'id' => $id,
                        'slug' => $slug,
                        'name' => $name,
                        'pretty_url' => $pretty_url,
                        'target_url' => (string) $target_url,
                        'has_geo' => 'YES',
                        'geo_status' => __('Has GEO-Redirect', 'pretty-link-geo-detector'),
                        'geo_details' => $geo_info['details']
                    );
                }
            }
        }

        return array(
            'total' => $total_count,
            'geo_count' => $geo_count,
            'standard_count' => ($total_count - $geo_count),
            'links' => $scanned_links
        );
    }

    /**
     * Analyze Pretty Link row and its meta for GEO rules
     */
    private function analyze_geo_rules($row, $metas, $link_id)
    {
        $has_geo = false;
        $rules = array();

        // 1. Check meta table keys
        foreach ($metas as $key => $val) {
            $key_lower = strtolower($key);
            if (strpos($key_lower, 'geo') !== false || strpos($key_lower, 'country') !== false || strpos($key_lower, 'location') !== false) {
                if (!empty($val)) {
                    $has_geo = true;
                    $parsed = $this->parse_geo_meta_value($val);
                    if ($parsed) {
                        $rules[] = $parsed;
                    } else {
                        $rules[] = $key . ': ' . (is_array($val) ? wp_json_encode($val) : $val);
                    }
                }
            }
        }

        // 2. Check postmeta table fallback for link_id
        global $wpdb;
        $post_metas = $wpdb->get_results($wpdb->prepare("SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE meta_key LIKE %s AND post_id = %d", '%geo%', $link_id), ARRAY_A);

        foreach ($post_metas as $pm) {
            if (!empty($pm['meta_value'])) {
                $has_geo = true;
                $parsed = $this->parse_geo_meta_value($pm['meta_value']);
                if ($parsed) {
                    $rules[] = $parsed;
                } else {
                    $rules[] = $pm['meta_key'] . ': ' . $pm['meta_value'];
                }
            }
        }

        // 3. Check param_struct / redirect_type
        if (isset($row['redirect_type']) && (strtolower($row['redirect_type']) === 'geo' || strpos(strtolower($row['redirect_type']), 'geo') !== false)) {
            $has_geo = true;
            $rules[] = __('Redirect Type: GEO', 'pretty-link-geo-detector');
        }

        if (isset($row['param_struct']) && !empty($row['param_struct'])) {
            $struct_lower = strtolower($row['param_struct']);
            if (strpos($struct_lower, 'geo') !== false || strpos($struct_lower, 'country') !== false) {
                $has_geo = true;
                $rules[] = __('Param Struct GEO Rule', 'pretty-link-geo-detector');
            }
        }

        $details = $has_geo ? implode(' | ', array_unique($rules)) : __('None', 'pretty-link-geo-detector');
        if ($has_geo && empty($details)) {
            $details = __('GEO-Redirect Configured', 'pretty-link-geo-detector');
        }

        return array(
            'has_geo' => $has_geo,
            'details' => $details
        );
    }

    /**
     * Analyze Postmeta for CPT prli-link
     */
    private function analyze_cpt_postmeta($post_id)
    {
        $has_geo = false;
        $rules = array();

        $all_meta = get_post_meta($post_id);
        foreach ($all_meta as $key => $values) {
            $key_lower = strtolower($key);
            if (strpos($key_lower, 'geo') !== false || strpos($key_lower, 'country') !== false) {
                foreach ($values as $val) {
                    if (!empty($val)) {
                        $has_geo = true;
                        $parsed = $this->parse_geo_meta_value($val);
                        if ($parsed) {
                            $rules[] = $parsed;
                        } else {
                            $rules[] = $key . ': ' . (is_string($val) ? $val : wp_json_encode($val));
                        }
                    }
                }
            }
        }

        return array(
            'has_geo' => $has_geo,
            'details' => $has_geo ? implode(' | ', array_unique($rules)) : __('None', 'pretty-link-geo-detector')
        );
    }

    /**
     * Helper to format serialized / array GEO meta values
     */
    private function parse_geo_meta_value($val)
    {
        if (is_string($val)) {
            $unserialized = @maybe_unserialize($val);
            if ($unserialized && $unserialized !== $val) {
                $val = $unserialized;
            }
        }

        if (is_array($val)) {
            $formatted = array();
            foreach ($val as $k => $v) {
                if (is_array($v)) {
                    $country = $v['country'] ?? $v['iso'] ?? $v['code'] ?? $k;
                    $target = $v['url'] ?? $v['target'] ?? $v['link'] ?? wp_json_encode($v);
                    $formatted[] = strtoupper((string) $country) . ' => ' . (string) $target;
                } else {
                    $formatted[] = strtoupper((string) $k) . ' => ' . (string) $v;
                }
            }
            return implode(', ', $formatted);
        }

        return null;
    }
}
