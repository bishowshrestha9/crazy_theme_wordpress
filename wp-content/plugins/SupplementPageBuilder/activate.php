<?php
/**
 * Simple Activation Script
 * 
 * Visit: http://localhost:10018/wp-content/plugins/SupplementPageBuilder/activate.php
 */

// Load WordPress
define('ABSPATH', dirname(__FILE__) . '/../../../');
require_once(ABSPATH . 'wp-load.php');

// Check if user is logged in as admin
if (!is_user_logged_in() || !current_user_can('activate_plugins')) {
    wp_die('You must be logged in as an administrator.');
}

// Get plugin file
$plugin_file = 'SupplementPageBuilder/supplement-page-builder.php';

// Activate the plugin
$result = activate_plugin($plugin_file);

if (is_wp_error($result)) {
    echo '<h1>Error Activating Plugin</h1>';
    echo '<p>' . $result->get_error_message() . '</p>';
} else {
    // Flush rewrite rules
    flush_rewrite_rules(true);
    
    echo '<!DOCTYPE html>
    <html>
    <head>
        <title>Plugin Activated Successfully</title>
        <style>
            body { 
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; 
                max-width: 800px; 
                margin: 50px auto; 
                padding: 20px;
                background: #f0f0f1;
            }
            .container {
                background: white;
                padding: 40px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            h1 { color: #2ecc71; margin-top: 0; }
            .success { 
                background: #d4edda; 
                border: 1px solid #c3e6cb; 
                padding: 15px; 
                border-radius: 5px; 
                margin: 20px 0; 
            }
            .steps { 
                background: #f8f9fa; 
                padding: 20px; 
                border-radius: 5px; 
                margin: 20px 0; 
            }
            .btn { 
                display: inline-block; 
                padding: 12px 24px; 
                background: #2271b1; 
                color: white !important; 
                text-decoration: none; 
                border-radius: 5px; 
                margin: 10px 10px 0 0;
                font-weight: 600;
            }
            .btn:hover { background: #135e96; }
            .btn-success { background: #46b450; }
            .btn-success:hover { background: #3a9642; }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>✓ Plugin Activated Successfully!</h1>
            <div class="success">
                <strong>Success!</strong> The Supplement Page Builder plugin has been activated and rewrite rules have been flushed.
            </div>
            <div class="steps">
                <h3>Next Steps:</h3>
                <ol>
                    <li><strong>Look in your sidebar</strong> - You should now see "Supplement Pages" menu item</li>
                    <li><strong>If you still don\'t see it:</strong> Try refreshing this page or logging out and back in</li>
                    <li>Click the button below to go to the admin dashboard</li>
                    <li>Look for the menu item with a 📊 icon</li>
                </ol>
            </div>
            <a href="' . admin_url() . '" class="btn">← Back to Dashboard</a>
            <a href="' . admin_url('post-new.php?post_type=supplement_page') . '" class="btn btn-success">+ Create Supplement Page</a>
            <p style="margin-top: 30px; color: #666; font-size: 14px;">
                <strong>Still not showing?</strong> Try clearing your browser cache or opening in a private/incognito window.
            </p>
        </div>
    </body>
    </html>';
}
