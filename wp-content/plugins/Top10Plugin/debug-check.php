<?php
/**
 * Debug Check for Top 10 Plugin
 * Add this to your theme's functions.php temporarily to check if plugin is loading
 */

// Check if plugin classes exist
add_action('admin_notices', function () {
    if (class_exists('Top10_Plugin')) {
        echo '<div class="notice notice-success"><p>✅ Top10_Plugin class exists</p></div>';
    } else {
        echo '<div class="notice notice-error"><p>❌ Top10_Plugin class NOT found</p></div>';
    }

    if (class_exists('Top10_Custom_Post_Types')) {
        echo '<div class="notice notice-success"><p>✅ Top10_Custom_Post_Types class exists</p></div>';
    } else {
        echo '<div class="notice notice-error"><p>❌ Top10_Custom_Post_Types class NOT found</p></div>';
    }

    // Check if post types are registered
    if (post_type_exists('top10_product')) {
        echo '<div class="notice notice-success"><p>✅ top10_product post type registered</p></div>';
    } else {
        echo '<div class="notice notice-error"><p>❌ top10_product post type NOT registered</p></div>';
    }

    if (post_type_exists('top10_page')) {
        echo '<div class="notice notice-success"><p>✅ top10_page post type registered</p></div>';
    } else {
        echo '<div class="notice notice-error"><p>❌ top10_page post type NOT registered</p></div>';
    }
});
