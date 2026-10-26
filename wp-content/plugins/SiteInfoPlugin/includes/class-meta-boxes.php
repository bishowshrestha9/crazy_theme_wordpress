<?php
/**
 * Meta Boxes Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class SIP_Meta_Boxes
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
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'), 10, 2);
    }

    public function add_meta_boxes()
    {
        add_meta_box(
            'sip_fetch_info',
            __('Fetch Site Information', 'site-info-plugin'),
            array($this, 'render_fetch_info_box'),
            'site_info',
            'normal',
            'high'
        );

        add_meta_box(
            'sip_site_data',
            __('Site Analysis Results', 'site-info-plugin'),
            array($this, 'render_site_data_box'),
            'site_info',
            'normal',
            'default'
        );
    }

    public function render_fetch_info_box($post)
    {
        wp_nonce_field('sip_meta_box', 'sip_meta_box_nonce');
        
        $target_url = get_post_meta($post->ID, '_sip_target_url', true);
        ?>
        <div class="sip-fetch-container">
            <p>
                <label for="sip_target_url"><strong><?php _e('Target URL (Optional):', 'site-info-plugin'); ?></strong></label><br>
                <input type="url" id="sip_target_url" name="sip_target_url" value="<?php echo esc_url($target_url); ?>" class="widefat" placeholder="<?php echo esc_url(home_url()); ?>">
                <span class="description"><?php _e('Leave empty to analyze current site', 'site-info-plugin'); ?></span>
            </p>
            
            <p>
                <button type="button" id="sip-fetch-btn" class="button button-primary button-large">
                    <span class="dashicons dashicons-update"></span> <?php _e('Fetch Site Info', 'site-info-plugin'); ?>
                </button>
                <span class="spinner" style="float: none; margin: 0 10px;"></span>
            </p>
            
            <div id="sip-fetch-message" style="margin-top: 15px;"></div>
        </div>
        <?php
    }

    public function render_site_data_box($post)
    {
        $site_data = get_post_meta($post->ID, '_sip_site_data', true);
        $last_fetched = get_post_meta($post->ID, '_sip_last_fetched', true);
        
        if ($last_fetched) {
            echo '<p><strong>' . __('Last Fetched:', 'site-info-plugin') . '</strong> ' . date('F j, Y g:i a', strtotime($last_fetched)) . '</p>';
        }
        
        if (empty($site_data)) {
            echo '<p class="description">' . __('Click "Fetch Site Info" button above to analyze the site.', 'site-info-plugin') . '</p>';
            return;
        }
        
        $this->display_site_data($site_data);
    }

    private function display_site_data($data)
    {
        if (!is_array($data)) {
            echo '<p>' . __('No data available.', 'site-info-plugin') . '</p>';
            return;
        }
        ?>
        <div class="sip-data-container">
            
            <!-- Site Overview -->
            <?php if (isset($data['overview'])): ?>
            <div class="sip-section">
                <h3>📊 Site Overview</h3>
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
                <h3>📄 Pages & Content Analysis (<?php echo count($data['pages']); ?>)</h3>
                <div class="sip-scrollable">
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Page Title</th>
                                <th>Type</th>
                                <th>Products Mentioned</th>
                                <th>Affiliate Links</th>
                                <th>Links</th>
                                <th>Words</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['pages'] as $page): ?>
                            <tr>
                                <td>
                                    <strong><a href="<?php echo esc_url($page['url']); ?>" target="_blank"><?php echo esc_html($page['title']); ?></a></strong>
                                </td>
                                <td>
                                    <span class="sip-badge sip-badge-type"><?php echo esc_html($page['type_label']); ?></span>
                                </td>
                                <td>
                                    <?php if (!empty($page['products_mentioned'])): ?>
                                        <details>
                                            <summary><strong><?php echo count($page['products_mentioned']); ?> products</strong></summary>
                                            <ul style="margin: 5px 0; padding-left: 20px;">
                                                <?php foreach ($page['products_mentioned'] as $product): ?>
                                                <li><?php echo esc_html($product); ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </details>
                                    <?php else: ?>
                                        <em>None</em>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($page['affiliate_links'])): ?>
                                        <details>
                                            <summary><strong><?php echo count($page['affiliate_links']); ?> links</strong></summary>
                                            <ul style="margin: 5px 0; padding-left: 20px; font-size: 11px;">
                                                <?php foreach ($page['affiliate_links'] as $link): ?>
                                                <li>
                                                    <strong><?php echo esc_html($link['text']); ?></strong><br>
                                                    <a href="<?php echo esc_url($link['url']); ?>" target="_blank"><?php echo esc_html($link['domain']); ?></a>
                                                </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </details>
                                    <?php else: ?>
                                        <em>None</em>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small>
                                        Internal: <?php echo (int)$page['internal_links_count']; ?><br>
                                        External: <?php echo (int)$page['external_links_count']; ?>
                                    </small>
                                </td>
                                <td><?php echo number_format($page['word_count']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Products Mentioned Across Site -->
            <?php if (isset($data['products']) && !empty($data['products'])): ?>
            <div class="sip-section">
                <h3>🛍️ Products Mentioned (<?php echo count($data['products']); ?>)</h3>
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

            <!-- All Links -->
            <?php if (isset($data['all_links']) && !empty($data['all_links'])): ?>
            <div class="sip-section">
                <h3>🔗 All Links (<?php echo count($data['all_links']); ?>)</h3>
                <div class="sip-scrollable">
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Link Text</th>
                                <th>URL</th>
                                <th>Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($data['all_links'], 0, 100) as $link): ?>
                            <tr>
                                <td><?php echo esc_html(wp_trim_words($link['text'], 10)); ?></td>
                                <td><a href="<?php echo esc_url($link['url']); ?>" target="_blank"><?php echo esc_html(wp_trim_words($link['url'], 8)); ?></a></td>
                                <td><span class="sip-badge sip-badge-<?php echo esc_attr($link['type']); ?>"><?php echo esc_html($link['type']); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php if (count($data['all_links']) > 100): ?>
                    <p class="description"><?php printf(__('Showing first 100 of %d links', 'site-info-plugin'), count($data['all_links'])); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- External Links -->
            <?php if (isset($data['external_links']) && !empty($data['external_links'])): ?>
            <div class="sip-section">
                <h3>🌐 External Links (<?php echo count($data['external_links']); ?>)</h3>
                <div class="sip-scrollable">
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Link Text</th>
                                <th>URL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($data['external_links'], 0, 50) as $link): ?>
                            <tr>
                                <td><?php echo esc_html(wp_trim_words($link['text'], 10)); ?></td>
                                <td><a href="<?php echo esc_url($link['url']); ?>" target="_blank"><?php echo esc_html(wp_trim_words($link['url'], 8)); ?></a></td>
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
                <h3>💰 Affiliate Links (<?php echo count($data['affiliate_links']); ?>)</h3>
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
                                <td><a href="<?php echo esc_url($link['url']); ?>" target="_blank"><?php echo esc_html(wp_trim_words($link['url'], 8)); ?></a></td>
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
                <h3>✨ Pretty Links (<?php echo count($data['pretty_links']); ?>)</h3>
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
                                <td><a href="<?php echo esc_url($link['destination']); ?>" target="_blank"><?php echo esc_html(wp_trim_words($link['destination'], 8)); ?></a></td>
                                <td><?php echo esc_html($link['type']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Products -->
            <?php if (isset($data['products']) && !empty($data['products'])): ?>
            <div class="sip-section">
                <h3>🛍️ Products (<?php echo count($data['products']); ?>)</h3>
                <div class="sip-scrollable">
                    <table class="widefat striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($data['products'], 0, 50) as $product): ?>
                            <tr>
                                <td><?php echo esc_html($product['name']); ?></td>
                                <td><?php echo esc_html($product['type']); ?></td>
                                <td><?php echo esc_html($product['status']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <!-- Images -->
            <?php if (isset($data['images']) && !empty($data['images'])): ?>
            <div class="sip-section">
                <h3>🖼️ Images (<?php echo count($data['images']); ?>)</h3>
                <p class="description"><?php printf(__('Found %d images on the site', 'site-info-plugin'), count($data['images'])); ?></p>
            </div>
            <?php endif; ?>

            <!-- Scripts & Styles -->
            <?php if (isset($data['scripts']) || isset($data['styles'])): ?>
            <div class="sip-section">
                <h3>📦 Loaded Assets</h3>
                <table class="widefat striped">
                    <tbody>
                        <?php if (isset($data['scripts'])): ?>
                        <tr>
                            <td><strong>Scripts</strong></td>
                            <td><?php echo esc_html($data['scripts']); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if (isset($data['styles'])): ?>
                        <tr>
                            <td><strong>Stylesheets</strong></td>
                            <td><?php echo esc_html($data['styles']); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

        </div>
        <?php
    }

    public function save_meta_boxes($post_id, $post)
    {
        if (!isset($_POST['sip_meta_box_nonce']) || !wp_verify_nonce($_POST['sip_meta_box_nonce'], 'sip_meta_box')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        if ($post->post_type !== 'site_info') {
            return;
        }

        if (isset($_POST['sip_target_url'])) {
            update_post_meta($post_id, '_sip_target_url', esc_url_raw($_POST['sip_target_url']));
        }
    }
}
