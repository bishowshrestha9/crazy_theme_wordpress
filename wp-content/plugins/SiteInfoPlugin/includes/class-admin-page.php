<?php
/**
 * Admin Page Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SIP_Admin_Page
{
    private static $instance = null;

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
    }

    public function add_admin_menu()
    {
        add_menu_page(
            __('Site Info', 'site-info-plugin'),
            __('Site Info', 'site-info-plugin'),
            'manage_options',
            'site-info-plugin',
            array($this, 'render_admin_page'),
            'dashicons-info',
            25
        );
    }

    public function render_admin_page()
    {
        $site_data = get_option('sip_site_data', array());
        $last_fetched = get_option('sip_last_fetched', '');
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="sip-admin-container">
                <div class="sip-fetch-section">
                    <h2><?php _e('Fetch Complete Site Information', 'site-info-plugin'); ?></h2>
                    <p class="description">
                        <?php _e('Click the button below to analyze your entire WordPress site and get comprehensive information about pages, products, links, affiliates, and more.', 'site-info-plugin'); ?>
                    </p>
                    
                    <div class="sip-button-group">
                        <button type="button" id="sip-fetch-btn" class="button button-primary button-hero">
                            <span class="dashicons dashicons-update"></span> <?php _e('Fetch Site Info', 'site-info-plugin'); ?>
                        </button>
                        
                        <?php if (!empty($site_data)): ?>
                        <a href="<?php echo esc_url(admin_url('admin-ajax.php?action=sip_export_csv&nonce=' . wp_create_nonce('sip_export_csv'))); ?>" class="button button-secondary button-hero">
                            <span class="dashicons dashicons-download"></span> <?php _e('Export to CSV', 'site-info-plugin'); ?>
                        </a>
                        <?php endif; ?>
                        
                        <span class="spinner"></span>
                    </div>
                    
                    <?php if ($last_fetched): ?>
                    <p class="sip-last-fetched">
                        <strong><?php _e('Last Fetched:', 'site-info-plugin'); ?></strong> 
                        <?php echo date('F j, Y g:i a', strtotime($last_fetched)); ?>
                    </p>
                    <?php endif; ?>
                    
                    <div id="sip-fetch-message"></div>
                </div>
                
                <?php if (!empty($site_data)): ?>
                <div class="sip-results-section">
                    <?php $this->display_site_data($site_data); ?>
                </div>
                <?php else: ?>
                <div class="sip-empty-state">
                    <p><?php _e('No data available yet. Click "Fetch Site Info" to analyze your site.', 'site-info-plugin'); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    private function display_site_data($data)
    {
        if (!is_array($data)) {
            return;
        }
        ?>
        <div class="sip-data-container">
            
            <!-- Site Overview -->
            <?php if (isset($data['overview'])): ?>
            <div class="sip-section">
                <h2>📊 Site Overview</h2>
                <table class="widefat striped">
                    <tbody>
                        <?php foreach ($data['overview'] as $key => $value): ?>
                        <tr>
                            <td><strong><?php echo esc_html(ucwords(str_replace('_', ' ', $key))); ?></strong></td>
                            <td><?php echo esc_html($value); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <!-- Pages Section -->
            <?php if (isset($data['pages']) && !empty($data['pages'])): ?>
            <div class="sip-section">
                <h2>📄 Complete Site Pages Overview (<?php echo count($data['pages']); ?>)</h2>
                <div class="sip-scrollable">
                    <table class="widefat striped sip-main-table">
                        <thead>
                            <tr>
                                <th>URL</th>
                                <th>Title</th>
                                <th>Meta Title</th>
                                <th>Pretty Link</th>
                                <th>Affiliate URL</th>
                                <th>Other Aff Links</th>
                                <th>Views (last 30 days)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['pages'] as $page): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo esc_url($page['url']); ?>" target="_blank" title="<?php echo esc_attr($page['url']); ?>">
                                        <?php echo esc_html(wp_trim_words($page['url'], 6)); ?>
                                    </a>
                                </td>
                                <td><strong><?php echo esc_html($page['title']); ?></strong></td>
                                <td><?php echo esc_html($page['meta_title']); ?></td>
                                <td>
                                    <?php if (!empty($page['pretty_links']) && isset($page['pretty_links'][0])): ?>
                                        <?php $plink = $page['pretty_links'][0]; // Only one pretty link per post ?>
                                        <div>
                                            <strong style="color: #0073aa;"><?php echo esc_html($plink['slug']); ?></strong><br>
                                            <a href="<?php echo esc_url($plink['pretty_url']); ?>" target="_blank" class="sip-link-small" title="<?php echo esc_attr($plink['pretty_url']); ?>">
                                                <?php echo esc_html(home_url()); ?>/<strong><?php echo esc_html($plink['slug']); ?></strong>
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <em>—</em>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($page['pretty_links']) && isset($page['pretty_links'][0]['target_url'])): ?>
                                        <?php $target = $page['pretty_links'][0]['target_url']; ?>
                                        <a href="<?php echo esc_url($target); ?>" target="_blank" title="<?php echo esc_attr($target); ?>">
                                            <?php echo esc_html(parse_url($target, PHP_URL_HOST)); ?>
                                        </a>
                                    <?php else: ?>
                                        <em>—</em>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($page['affiliate_links'])): ?>
                                        <details>
                                            <summary><strong><?php echo count($page['affiliate_links']); ?> links</strong></summary>
                                            <ul class="sip-compact-list">
                                                <?php foreach ($page['affiliate_links'] as $alink): ?>
                                                <li>
                                                    <a href="<?php echo esc_url($alink['url']); ?>" target="_blank" title="<?php echo esc_attr($alink['url']); ?>">
                                                        <?php echo esc_html($alink['domain']); ?>
                                                    </a>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </details>
                                    <?php else: ?>
                                        <em>—</em>
                                    <?php endif; ?>
                                </td>
                                <td class="sip-views-column">
                                    <?php if ($page['views_30_days'] > 0): ?>
                                        <strong><?php echo number_format($page['views_30_days']); ?></strong>
                                    <?php else: ?>
                                        <em>No data</em>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="description" style="margin-top: 15px;">
                    <strong>Note:</strong> Views data requires an analytics plugin (WP Statistics, Jetpack Stats, or Google Analytics integration).
                </p>
            </div>
            <?php endif; ?>

            <!-- Detailed Analysis Sections -->
            <div class="sip-section">
                <h2>📊 Detailed Analysis</h2>
            </div>

            <!-- Products Mentioned -->
            <?php if (isset($data['products']) && !empty($data['products'])): ?>
            <div class="sip-section">
                <h2>🛍️ Products Mentioned (<?php echo count($data['products']); ?>)</h2>
                <div class="sip-scrollable">
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Mentioned In Pages</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['products'] as $product): ?>
                            <tr>
                                <td><strong><?php echo esc_html($product['name']); ?></strong></td>
                                <td>
                                    <details>
                                        <summary><?php echo count($product['mentioned_in']); ?> pages</summary>
                                        <ul style="margin: 5px 0; padding-left: 20px;">
                                            <?php foreach ($product['mentioned_in'] as $mention): ?>
                                            <li>
                                                <a href="<?php echo esc_url($mention['page_url']); ?>" target="_blank">
                                                    <?php echo esc_html($mention['page_title']); ?>
                                                </a>
                                                <em>(<?php echo esc_html($mention['page_type']); ?>)</em>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </details>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Affiliate Links -->
            <?php if (isset($data['affiliate_links']) && !empty($data['affiliate_links'])): ?>
            <div class="sip-section">
                <h2>💰 All Affiliate Links (<?php echo count($data['affiliate_links']); ?>)</h2>
                <div class="sip-scrollable">
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Link Text</th>
                                <th>URL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['affiliate_links'] as $link): ?>
                            <tr>
                                <td><?php echo esc_html(wp_trim_words($link['text'], 10)); ?></td>
                                <td><a href="<?php echo esc_url($link['url']); ?>" target="_blank"><?php echo esc_html(wp_trim_words($link['url'], 10)); ?></a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Pretty Links -->
            <?php if (isset($data['pretty_links']) && !empty($data['pretty_links'])): ?>
            <div class="sip-section">
                <h2>✨ Pretty Links (<?php echo count($data['pretty_links']); ?>)</h2>
                <div class="sip-scrollable">
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Pretty URL</th>
                                <th>Destination</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['pretty_links'] as $link): ?>
                            <tr>
                                <td><?php echo esc_html($link['pretty_url']); ?></td>
                                <td><a href="<?php echo esc_url($link['destination']); ?>" target="_blank"><?php echo esc_html(wp_trim_words($link['destination'], 10)); ?></a></td>
                                <td><?php echo esc_html($link['type']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- External Links -->
            <?php if (isset($data['external_links']) && !empty($data['external_links'])): ?>
            <div class="sip-section">
                <h2>🌐 External Links (<?php echo count($data['external_links']); ?>)</h2>
                <div class="sip-scrollable">
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Link Text</th>
                                <th>URL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['external_links'] as $link): ?>
                            <tr>
                                <td><?php echo esc_html(wp_trim_words($link['text'], 10)); ?></td>
                                <td><a href="<?php echo esc_url($link['url']); ?>" target="_blank"><?php echo esc_html(wp_trim_words($link['url'], 10)); ?></a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Internal Links -->
            <?php if (isset($data['internal_links']) && !empty($data['internal_links'])): ?>
            <div class="sip-section">
                <h2>🔗 Internal Links (<?php echo count($data['internal_links']); ?>)</h2>
                <div class="sip-scrollable">
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Link Text</th>
                                <th>URL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['internal_links'] as $link): ?>
                            <tr>
                                <td><?php echo esc_html(wp_trim_words($link['text'], 10)); ?></td>
                                <td><a href="<?php echo esc_url($link['url']); ?>" target="_blank"><?php echo esc_html(wp_trim_words($link['url'], 10)); ?></a></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Active Plugins -->
            <?php if (isset($data['plugins']) && !empty($data['plugins'])): ?>
            <div class="sip-section">
                <h2>🔌 Active Plugins (<?php echo count($data['plugins']); ?>)</h2>
                <div class="sip-scrollable">
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Plugin Name</th>
                                <th>Version</th>
                                <th>Author</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['plugins'] as $plugin): ?>
                            <tr>
                                <td><?php echo esc_html($plugin['name']); ?></td>
                                <td><?php echo esc_html($plugin['version']); ?></td>
                                <td><?php echo esc_html($plugin['author']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

        </div>
        <?php
    }
}
