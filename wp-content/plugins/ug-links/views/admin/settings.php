<?php
/**
 * UglyLinks Settings View
 *
 * @package UglyLinks\Views\Admin
 */

declare(strict_types=1);

use UglyLinks\App\Controllers\Admin\SettingsController;

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$default_type = get_option( 'uglylinks_default_redirect_type', '301' );
$tracking_enabled = get_option( 'uglylinks_enable_tracking', true );
$ip2location_token = get_option( 'uglylinks_ip2location_token', '' );
?>
<div class="wrap uglylinks-wrap" id="uglylinks-app" style="margin-top: 32px;">
    
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 28px; font-weight: 300; margin-bottom: 8px;">UglyLinks Settings</h1>
        <p style="font-size: 14px; color: var(--ul-text-muted);">Configure global behaviors for your link routing engine.</p>
    </div>

    <?php 
    $sync_status = isset( $_GET['sync'] ) ? sanitize_text_field( wp_unslash( $_GET['sync'] ) ) : '';
    if ( $sync_status === 'success' ): 
    ?>
        <div style="background: var(--ul-success-light); color: var(--ul-success); padding: 16px; border-radius: var(--ul-radius); margin-bottom: 24px; font-weight: 500;">
            IP2Location database successfully downloaded and configured!
        </div>
    <?php elseif ( $sync_status === 'error' ): ?>
        <div style="background: var(--ul-danger-light); color: var(--ul-danger); padding: 16px; border-radius: var(--ul-radius); margin-bottom: 24px; font-weight: 500;">
            There was an error downloading or extracting the database. Please verify your token.
        </div>
    <?php endif; ?>

    <form method="post" action="options.php" style="background: var(--ul-surface); border: 1px solid var(--ul-border); border-radius: var(--ul-radius); padding: 32px; max-width: 800px; box-shadow: var(--ul-shadow);">
        <?php settings_fields( SettingsController::OPTION_GROUP ); ?>
        
        <!-- Redirect Type Default -->
        <div style="margin-bottom: 24px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px;">Default Redirect Type</label>
            <select name="uglylinks_default_redirect_type" style="width: 100%; max-width: 300px; padding: 8px; border-radius: var(--ul-radius-sm); border: 1px solid var(--ul-border);">
                <option value="301" <?php selected( $default_type, '301' ); ?>>301 Permanent (Best for SEO)</option>
                <option value="302" <?php selected( $default_type, '302' ); ?>>302 Temporary</option>
            </select>
            <p style="font-size: 12px; color: var(--ul-text-muted); margin-top: 6px;">This sets the default option when creating a new link.</p>
        </div>

        <!-- IP2Location Token -->
        <div style="margin-bottom: 24px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px;">IP2Location Download Token</label>
            <input type="text" name="uglylinks_ip2location_token" value="<?php echo esc_attr( $ip2location_token ); ?>" placeholder="e.g. mRY26..." style="width: 100%; max-width: 500px; padding: 8px; border-radius: var(--ul-radius-sm); border: 1px solid var(--ul-border);">
            <p style="font-size: 12px; color: var(--ul-text-muted); margin-top: 6px;">Your IP2Location download token. You can find this in your IP2Location dashboard. <br>If left blank, the plugin will fallback to a free online IP API.</p>
        </div>

        <!-- Tracking Toggle -->
        <div style="margin-bottom: 32px;">
            <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px;">Click Tracking</label>
            <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer;">
                <input type="checkbox" name="uglylinks_enable_tracking" value="1" <?php checked( $tracking_enabled, true ); ?>>
                <span style="font-size: 14px;">Enable Analytics Collection</span>
            </label>
            <p style="font-size: 12px; color: var(--ul-text-muted); margin-top: 6px;">Uncheck to disable all click tracking for compliance (e.g. GDPR) or performance reasons.</p>
        </div>

        <button type="submit" class="uglylinks-btn uglylinks-btn--primary">
            <?php esc_html_e( 'Save Settings', 'ug-links' ); ?>
        </button>
    </form>

    <!-- IP2Location Sync Form -->
    <div style="margin-top: 32px; background: var(--ul-surface); border: 1px solid var(--ul-border); border-radius: var(--ul-radius); padding: 32px; max-width: 800px; box-shadow: var(--ul-shadow);">
        <h2 style="font-size: 18px; font-weight: 600; margin: 0 0 16px;">Sync IP2Location Database</h2>
        <p style="font-size: 14px; color: var(--ul-text-muted); margin-bottom: 24px;">Click the button below to automatically download and extract the latest database using your token.</p>
        
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="uglylinks_sync_ip2location">
            <?php wp_nonce_field( 'uglylinks_sync_ip2location_nonce', 'uglylinks_nonce' ); ?>
            
            <button type="submit" class="uglylinks-btn" style="background: var(--ul-surface-alt); border: 1px solid var(--ul-border); padding: 8px 16px;" <?php echo empty($ip2location_token) ? 'disabled' : ''; ?>>
                <?php echo empty($ip2location_token) ? 'Save Token First' : 'Sync Database Now'; ?>
            </button>
        </form>
    </div>

</div>
