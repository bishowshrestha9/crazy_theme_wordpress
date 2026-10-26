<?php
/**
 * Standalone WordPress ACF CSV Exporter
 *
 * Usage:
 * /export-acf-csv.php?key=CHANGE_THIS_SECRET_KEY
 * /export-acf-csv.php?key=CHANGE_THIS_SECRET_KEY&post_type=product
 */

// -----------------------------
// Configuration (edit these)
// -----------------------------
$export_secret_key = 'CHANGE_THIS_SECRET_KEY';
$default_post_type = 'post'; // e.g. post, product

// Add extra ACF/meta fields here (column => field_name)
$extra_fields = array(
    // 'price' => 'price',
    // 'sku' => 'sku',
);

// Image-like fields that should always be converted to absolute URL
$image_fields = array(
    'product_image',
    // 'gallery_image',
);

// Query batch size
$batch_size = 200;

// -----------------------------
// Bootstrap WordPress
// -----------------------------
$wp_load = __DIR__ . '/wp-load.php';
if (!file_exists($wp_load)) {
    http_response_code(500);
    exit('wp-load.php not found. Place this file in your WordPress root directory.');
}
require_once $wp_load;

if (!function_exists('add_query_arg')) {
    http_response_code(500);
    exit('WordPress bootstrap failed.');
}

// -----------------------------
// Security check
// -----------------------------
$provided_key = isset($_GET['key']) ? (string) $_GET['key'] : '';
if (!hash_equals((string) $export_secret_key, $provided_key)) {
    http_response_code(403);
    exit('Invalid export key.');
}

// -----------------------------
// Input options
// -----------------------------
$post_type = isset($_GET['post_type']) ? sanitize_key(wp_unslash($_GET['post_type'])) : $default_post_type;
if (empty($post_type)) {
    $post_type = $default_post_type;
}

if (!post_type_exists($post_type)) {
    http_response_code(400);
    exit('Invalid post_type.');
}

@set_time_limit(0);

// -----------------------------
// Helpers
// -----------------------------
/**
 * Get ACF field if available, fallback to post meta.
 */
function exporter_get_field_value($post_id, $field_name)
{
    if (function_exists('get_field')) {
        $value = get_field($field_name, $post_id);
        if ($value !== null && $value !== false && $value !== '') {
            return $value;
        }
    }

    return get_post_meta($post_id, $field_name, true);
}

/**
 * Convert ACF image return formats (ID/array/url) into absolute URL string.
 */
function exporter_image_to_url($value)
{
    if (empty($value)) {
        return '';
    }

    // ACF Image as array
    if (is_array($value)) {
        if (!empty($value['url'])) {
            return esc_url_raw($value['url']);
        }
        if (!empty($value['ID'])) {
            $url = wp_get_attachment_url((int) $value['ID']);
            return $url ? esc_url_raw($url) : '';
        }
        if (!empty($value['id'])) {
            $url = wp_get_attachment_url((int) $value['id']);
            return $url ? esc_url_raw($url) : '';
        }
        return '';
    }

    // ACF Image as attachment ID
    if (is_numeric($value)) {
        $url = wp_get_attachment_url((int) $value);
        return $url ? esc_url_raw($url) : '';
    }

    // ACF Image as URL string
    if (is_string($value)) {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return esc_url_raw($value);
        }
        // Sometimes attachment ID is stored as numeric string
        if (ctype_digit($value)) {
            $url = wp_get_attachment_url((int) $value);
            return $url ? esc_url_raw($url) : '';
        }
    }

    return '';
}

/**
 * Flatten arbitrary values to CSV-safe string.
 */
function exporter_flatten_value($value)
{
    if (is_array($value) || is_object($value)) {
        $json = wp_json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $json !== false ? $json : '';
    }

    if (is_bool($value)) {
        return $value ? '1' : '0';
    }

    if ($value === null) {
        return '';
    }

    return (string) $value;
}

// -----------------------------
// Build CSV headers
// -----------------------------
$base_columns = array(
    'post_id' => 'Post ID',
    'post_title' => 'Post Title',
    'product_name' => 'product_name',
    'product_image' => 'product_image',
);

$all_columns = array_merge($base_columns, $extra_fields);

$filename = sprintf(
    'acf-export-%s-%s.csv',
    $post_type,
    gmdate('Ymd-His')
);

nocache_headers();
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

$output = fopen('php://output', 'w');
if ($output === false) {
    http_response_code(500);
    exit('Unable to open output stream.');
}

// UTF-8 BOM for Excel compatibility
fwrite($output, "\xEF\xBB\xBF");

// Header row
fputcsv($output, array_values($all_columns));

// -----------------------------
// Query in batches
// -----------------------------
$paged = 1;

do {
    $query = new WP_Query(array(
        'post_type'              => $post_type,
        'post_status'            => 'publish',
        'fields'                 => 'ids',
        'posts_per_page'         => $batch_size,
        'paged'                  => $paged,
        'orderby'                => 'ID',
        'order'                  => 'ASC',
        'no_found_rows'          => false,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
        'cache_results'          => false,
    ));

    if (empty($query->posts)) {
        break;
    }

    foreach ($query->posts as $post_id) {
        $post_id = (int) $post_id;
        $row = array();

        foreach ($all_columns as $column_key => $column_label) {
            switch ($column_key) {
                case 'post_id':
                    $row[] = $post_id;
                    break;

                case 'post_title':
                    $row[] = get_the_title($post_id);
                    break;

                case 'product_name':
                    $value = exporter_get_field_value($post_id, 'product_name');
                    $row[] = exporter_flatten_value($value);
                    break;

                case 'product_image':
                    $value = exporter_get_field_value($post_id, 'product_image');
                    $row[] = exporter_image_to_url($value);
                    break;

                default:
                    $value = exporter_get_field_value($post_id, $column_key);
                    if (in_array($column_key, $image_fields, true)) {
                        $row[] = exporter_image_to_url($value);
                    } else {
                        $row[] = exporter_flatten_value($value);
                    }
                    break;
            }
        }

        fputcsv($output, $row);
    }

    $paged++;
    wp_cache_flush();
} while ($paged <= (int) $query->max_num_pages);

wp_reset_postdata();
fclose($output);
exit;
