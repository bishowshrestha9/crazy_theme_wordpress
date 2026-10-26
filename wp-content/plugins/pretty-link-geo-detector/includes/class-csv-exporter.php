<?php
/**
 * CSV Exporter for Pretty Links GEO Detector
 */

if (!defined('ABSPATH')) {
    exit;
}

class PLGD_CSV_Exporter
{
    /**
     * Stream CSV output directly to browser download
     */
    public static function output_csv(array $links, $filename = 'pretty_links_geo_report.csv')
    {
        // Clear any previous output buffers
        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . esc_attr($filename) . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // Add UTF-8 BOM for Excel compatibility
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // CSV Headers - Only Product Name
        fputcsv($output, array('Product Name'));

        // Rows - Product names of detected GEO-redirect links
        foreach ($links as $link) {
            $product_name = !empty($link['name']) ? $link['name'] : ($link['slug'] ?? '');
            if (!empty($product_name)) {
                fputcsv($output, array($product_name));
            }
        }

        fclose($output);
        exit;
    }
}
