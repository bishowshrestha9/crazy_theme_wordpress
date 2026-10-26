<?php
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Handle Form Submission
if (isset($_POST['top10_save_products']) && check_admin_referer('top10_save_products_nonce')) {
    $products = isset($_POST['products']) ? $_POST['products'] : array();

    // Sanitize
    $sanitized = array();
    foreach ($products as $id => $product) {
        if (!empty($product['name'])) { // Require name
            $sanitized[$id] = array_map('sanitize_text_field', $product);
        }
    }

    // Add New Product
    if (!empty($_POST['new_product']['name'])) {
        $new_id = uniqid();
        $sanitized[$new_id] = array_map('sanitize_text_field', $_POST['new_product']);
    }

    update_option('top10_supplements_products', $sanitized);
    echo '<div class="notice notice-success is-dismissible"><p>Products saved successfully.</p></div>';
}

// Get Products
$products = get_option('top10_supplements_products', array());
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Top 10 Supplements Data</h1>
    <p>Manage products for the Top 10 Supplements page here.</p>

    <form method="post" action="">
        <?php wp_nonce_field('top10_save_products_nonce'); ?>

        <table class="wp-list-table widefat fixed striped table-view-list">
            <thead>
                <tr>
                    <th style="width: 200px;">Product Name</th>
                    <th>Badge Text</th>
                    <th>Price</th>
                    <th style="width: 80px;">Rating</th>
                    <th>Review Link</th>
                    <th>Overview/Best For</th>
                    <th style="width: 60px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $id => $product): ?>
                        <tr>
                            <td>
                                <input type="text" name="products[<?php echo esc_attr($id); ?>][name]"
                                    value="<?php echo esc_attr($product['name']); ?>" class="widefat">
                            </td>
                            <td>
                                <input type="text" name="products[<?php echo esc_attr($id); ?>][badge_text]"
                                    value="<?php echo esc_attr($product['badge_text'] ?? ''); ?>" class="widefat">
                            </td>
                            <td>
                                <input type="text" name="products[<?php echo esc_attr($id); ?>][price]"
                                    value="<?php echo esc_attr($product['price'] ?? ''); ?>" class="widefat">
                            </td>
                            <td>
                                <input type="number" step="0.1" max="10" name="products[<?php echo esc_attr($id); ?>][rating]"
                                    value="<?php echo esc_attr($product['rating'] ?? ''); ?>" class="small-text">
                            </td>
                            <td>
                                <input type="text" name="products[<?php echo esc_attr($id); ?>][affiliate_link]"
                                    value="<?php echo esc_attr($product['affiliate_link'] ?? ''); ?>" class="widefat">
                            </td>
                            <td>
                                <input type="text" name="products[<?php echo esc_attr($id); ?>][best_for]"
                                    value="<?php echo esc_attr($product['best_for'] ?? ''); ?>" class="widefat">
                            </td>
                            <td>
                                <a href="#" class="button top10-delete-row" style="color: #a00;">X</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No products found. Add one below.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr style="background: #f0f0f1; border-top: 2px solid #ccc;">
                    <td colspan="7"><strong>Add New Product:</strong></td>
                </tr>
                <tr style="background: #f0f0f1;">
                    <td>
                        <input type="text" name="new_product[name]" placeholder="New Product Name" class="widefat">
                    </td>
                    <td>
                        <input type="text" name="new_product[badge_text]" placeholder="No.1 Choice" class="widefat">
                    </td>
                    <td>
                        <input type="text" name="new_product[price]" placeholder="$19.99" class="widefat">
                    </td>
                    <td>
                        <input type="number" step="0.1" max="10" name="new_product[rating]" placeholder="9.5"
                            class="small-text">
                    </td>
                    <td>
                        <input type="text" name="new_product[affiliate_link]" placeholder="http://..." class="widefat">
                    </td>
                    <td>
                        <input type="text" name="new_product[best_for]" placeholder="Best for recovery" class="widefat">
                    </td>
                    <td>
                        <span class="dashicons dashicons-plus"></span>
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="tablenav bottom">
            <div class="alignleft actions">
                <input type="submit" name="top10_save_products" class="button button-primary" value="Save Changes">
            </div>
        </div>
    </form>

    <script>
        jQuery(document).ready(function ($) {
            $('.top10-delete-row').on('click', function (e) {
                e.preventDefault();
                if (confirm('Are you sure? Click Save Changes to confirm deletion.')) {
                    $(this).closest('tr').remove();
                }
            });
        });
    </script>
</div>