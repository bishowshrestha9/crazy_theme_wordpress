<?php
/**
 * CSV Exporter Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SIP_CSV_Exporter
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function export()
    {
        $filename = 'site-info-' . date('Y-m-d-H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        
        // Add BOM for UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Export Overview
        fputcsv($output, array('SITE OVERVIEW'));
        fputcsv($output, array('Field', 'Value'));
        if (isset($this->data['overview'])) {
            foreach ($this->data['overview'] as $key => $value) {
                fputcsv($output, array(ucwords(str_replace('_', ' ', $key)), $value));
            }
        }
        fputcsv($output, array()); // Empty line
        
        // Export Pages
        fputcsv($output, array('PAGES & CONTENT ANALYSIS'));
        fputcsv($output, array('URL', 'Title', 'Meta Title', 'Pretty Link (Slug)', 'Pretty Link (Full URL)', 'Affiliate URL (Target)', 'Other Aff Links', 'Views (last 30 days)'));
        if (isset($this->data['pages'])) {
            foreach ($this->data['pages'] as $page) {
                $pretty_link_slug = '';
                $pretty_link_url = '';
                $affiliate_url = '';
                
                // Each post has only ONE pretty link
                if (!empty($page['pretty_links']) && isset($page['pretty_links'][0])) {
                    $plink = $page['pretty_links'][0];
                    $pretty_link_slug = isset($plink['slug']) ? $plink['slug'] : '';
                    $pretty_link_url = isset($plink['pretty_url']) ? $plink['pretty_url'] : '';
                    $affiliate_url = isset($plink['target_url']) ? $plink['target_url'] : '';
                }
                
                $other_aff_links = '';
                if (!empty($page['affiliate_links'])) {
                    $alinks = array();
                    foreach ($page['affiliate_links'] as $alink) {
                        $alinks[] = $alink['url'];
                    }
                    $other_aff_links = implode('; ', $alinks);
                }
                
                fputcsv($output, array(
                    $page['url'],
                    $page['title'],
                    isset($page['meta_title']) ? $page['meta_title'] : '',
                    $pretty_link_slug,
                    $pretty_link_url,
                    $affiliate_url,
                    $other_aff_links,
                    isset($page['views_30_days']) ? $page['views_30_days'] : 0
                ));
            }
        }
        fputcsv($output, array()); // Empty line
        
        // Export Page Affiliate Links Detail
        fputcsv($output, array('PAGE AFFILIATE LINKS DETAIL'));
        fputcsv($output, array('Page Title', 'Affiliate Link Text', 'Affiliate URL', 'Domain'));
        if (isset($this->data['pages'])) {
            foreach ($this->data['pages'] as $page) {
                if (!empty($page['affiliate_links'])) {
                    foreach ($page['affiliate_links'] as $link) {
                        fputcsv($output, array(
                            $page['title'],
                            $link['text'],
                            $link['url'],
                            $link['domain']
                        ));
                    }
                }
            }
        }
        fputcsv($output, array()); // Empty line
        
        // Export Products
        fputcsv($output, array('PRODUCTS MENTIONED'));
        fputcsv($output, array('Product Name', 'Mentioned In (Page)', 'Page Type', 'Page URL'));
        if (isset($this->data['products'])) {
            foreach ($this->data['products'] as $product) {
                foreach ($product['mentioned_in'] as $mention) {
                    fputcsv($output, array(
                        $product['name'],
                        $mention['page_title'],
                        $mention['page_type'],
                        $mention['page_url']
                    ));
                }
            }
        }
        fputcsv($output, array()); // Empty line
        
        // Export All Affiliate Links
        fputcsv($output, array('ALL AFFILIATE LINKS'));
        fputcsv($output, array('Link Text', 'URL'));
        if (isset($this->data['affiliate_links'])) {
            foreach ($this->data['affiliate_links'] as $link) {
                fputcsv($output, array($link['text'], $link['url']));
            }
        }
        fputcsv($output, array()); // Empty line
        
        // Export Pretty Links
        fputcsv($output, array('PRETTY LINKS'));
        fputcsv($output, array('Pretty URL', 'Destination', 'Type'));
        if (isset($this->data['pretty_links'])) {
            foreach ($this->data['pretty_links'] as $link) {
                fputcsv($output, array($link['pretty_url'], $link['destination'], $link['type']));
            }
        }
        fputcsv($output, array()); // Empty line
        
        // Export External Links
        fputcsv($output, array('EXTERNAL LINKS'));
        fputcsv($output, array('Link Text', 'URL'));
        if (isset($this->data['external_links'])) {
            foreach ($this->data['external_links'] as $link) {
                fputcsv($output, array($link['text'], $link['url']));
            }
        }
        fputcsv($output, array()); // Empty line
        
        // Export All Links
        fputcsv($output, array('ALL LINKS'));
        fputcsv($output, array('Link Text', 'URL', 'Type'));
        if (isset($this->data['all_links'])) {
            foreach ($this->data['all_links'] as $link) {
                fputcsv($output, array($link['text'], $link['url'], $link['type']));
            }
        }
        fputcsv($output, array()); // Empty line
        
        // Export Plugins
        fputcsv($output, array('ACTIVE PLUGINS'));
        fputcsv($output, array('Plugin Name', 'Version', 'Author'));
        if (isset($this->data['plugins'])) {
            foreach ($this->data['plugins'] as $plugin) {
                fputcsv($output, array($plugin['name'], $plugin['version'], $plugin['author']));
            }
        }
        fputcsv($output, array()); // Empty line
        
        // Export Theme
        fputcsv($output, array('THEME INFORMATION'));
        fputcsv($output, array('Field', 'Value'));
        if (isset($this->data['theme'])) {
            foreach ($this->data['theme'] as $key => $value) {
                fputcsv($output, array(ucwords($key), $value));
            }
        }
        
        fclose($output);
        exit;
    }
}
