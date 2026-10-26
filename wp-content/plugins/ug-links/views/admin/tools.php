<?php
/**
 * UglyLinks Tools View (Import/Export)
 *
 * @package UglyLinks\Views\Admin
 */

declare(strict_types=1);

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$notice = isset( $_GET['uglylinks_notice'] ) ? sanitize_text_field( wp_unslash( $_GET['uglylinks_notice'] ) ) : '';
$has_errors = isset( $_GET['uglylinks_has_errors'] ) ? true : false;
$errors = [];

if ( $has_errors ) {
    $transient_key = 'uglylinks_import_errors_' . get_current_user_id();
    $errors = get_transient( $transient_key ) ?: [];
    delete_transient( $transient_key ); // Clear immediately after reading
}
?>
<div class="wrap uglylinks-wrap" id="uglylinks-app" style="margin-top: 32px;">
    
    <div style="margin-bottom: 32px;">
        <h1 style="font-size: 28px; font-weight: 300; margin-bottom: 8px;">Import & Export Tools</h1>
        <p style="font-size: 14px; color: var(--ul-text-muted);">Backup your data or bulk-import links via CSV.</p>
    </div>

    <?php if ( strpos( $notice, 'imported_' ) === 0 ): ?>
        <?php $count = intval( str_replace( 'imported_', '', $notice ) ); ?>
        <div style="background: var(--ul-success-light); color: var(--ul-success); padding: 16px; border-radius: var(--ul-radius); margin-bottom: 24px; font-weight: 500;">
            Successfully imported <?php echo esc_html( $count ); ?> links!
        </div>
    <?php elseif ( $notice === 'error' ): ?>
        <div style="background: var(--ul-danger-light); color: var(--ul-danger); padding: 16px; border-radius: var(--ul-radius); margin-bottom: 24px; font-weight: 500;">
            There was a critical error uploading or parsing your CSV. Please ensure it is a valid CSV file.
        </div>
    <?php elseif ( $notice === 'revert_success' ): ?>
        <div style="background: var(--ul-success-light); color: var(--ul-success); padding: 16px; border-radius: var(--ul-radius); margin-bottom: 24px; font-weight: 500;">
            Database successfully reverted to the selected backup state!
        </div>
    <?php elseif ( $notice === 'revert_error' ): ?>
        <div style="background: var(--ul-danger-light); color: var(--ul-danger); padding: 16px; border-radius: var(--ul-radius); margin-bottom: 24px; font-weight: 500;">
            Failed to revert backup. The file may be unreadable or invalid.
        </div>
    <?php endif; ?>

    <?php if ( ! empty( $errors ) ): ?>
        <div style="background: #fffbfa; border: 1px solid #fecaca; border-radius: var(--ul-radius); padding: 24px; margin-bottom: 32px; box-shadow: var(--ul-shadow);">
            <h2 style="margin: 0 0 12px; color: var(--ul-danger); font-size: 18px; font-weight: 600;">Import Warnings / Errors</h2>
            <p style="font-size: 14px; margin-bottom: 16px; color: var(--ul-text-muted);">The following rows were skipped due to missing fields or duplicate slugs:</p>
            <ul style="list-style-type: disc; padding-left: 20px; font-size: 13px; color: var(--ul-danger); margin: 0; max-height: 200px; overflow-y: auto;">
                <?php foreach ( $errors as $error ): ?>
                    <li style="margin-bottom: 4px;"><?php echo esc_html( $error ); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px;">
        
        <!-- Import Card -->
        <div style="background: var(--ul-surface); border: 1px solid var(--ul-border); border-radius: var(--ul-radius); padding: 32px; box-shadow: var(--ul-shadow);">
            <h2 style="font-size: 18px; font-weight: 600; margin: 0 0 16px;">Import Links (CSV)</h2>
            
            <div style="background: var(--ul-surface-alt); padding: 16px; border-radius: var(--ul-radius-sm); margin-bottom: 24px; border: 1px dashed var(--ul-border);">
                <h3 style="font-size: 14px; font-weight: 600; margin: 0 0 8px;">CSV Format Rules:</h3>
                <ul style="font-size: 13px; color: var(--ul-text-muted); margin: 0; padding-left: 16px;">
                    <li style="margin-bottom: 4px;"><strong>Required Columns:</strong> <code>Slug</code>, <code>Target URL</code></li>
                    <li style="margin-bottom: 4px;"><strong>Optional Columns:</strong> <code>Title</code>, <code>Redirect Type</code></li>
                    <li style="margin-bottom: 4px;">Extra columns in your CSV will be safely ignored.</li>
                    <li>If an optional field is missing, a default value will be applied.</li>
                </ul>
            </div>

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
                <input type="hidden" name="action" value="uglylinks_import">
                <?php wp_nonce_field( 'uglylinks_import_nonce', 'uglylinks_nonce' ); ?>
                
                <div style="border: 2px dashed var(--ul-border); border-radius: var(--ul-radius); padding: 32px 24px; text-align: center; margin-bottom: 24px; background: var(--ul-surface-alt); position: relative; transition: border-color var(--ul-transition), background var(--ul-transition);">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="var(--ul-primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; opacity: 0.8;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                    <p style="margin: 0 0 8px; font-weight: 500; font-size: 14px; color: var(--ul-text);">Drag and drop your CSV file here</p>
                    <p style="margin: 0; font-size: 13px; color: var(--ul-text-muted);">or click to browse from your computer</p>
                    <input type="file" name="import_file" accept=".csv" required style="opacity: 0; position: absolute; inset: 0; width: 100%; height: 100%; cursor: pointer;">
                </div>
                
                <button type="submit" class="uglylinks-btn uglylinks-btn--primary" style="width: 100%; padding: 12px; font-size: 14px;">
                    <?php esc_html_e( 'Upload and Import', 'ug-links' ); ?>
                </button>
            </form>
        </div>

        <!-- Export Card -->
        <div style="background: var(--ul-surface); border: 1px solid var(--ul-border); border-radius: var(--ul-radius); padding: 32px; box-shadow: var(--ul-shadow);">
            <h2 style="font-size: 18px; font-weight: 600; margin: 0 0 16px;">Export Links</h2>
            <p style="font-size: 14px; color: var(--ul-text-muted); margin-bottom: 24px;">Download a complete backup of your links, including current click counts, as a CSV file.</p>
            
            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                <input type="hidden" name="action" value="uglylinks_export">
                <?php wp_nonce_field( 'uglylinks_export_nonce', 'uglylinks_nonce' ); ?>
                <button type="submit" class="uglylinks-btn" style="width: 100%; padding: 12px; font-size: 14px; background: var(--ul-surface-alt); color: var(--ul-text); border: 1px solid var(--ul-border); border-radius: var(--ul-radius-sm); font-weight: 500;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:8px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                    <?php esc_html_e( 'Download Backup (CSV)', 'ug-links' ); ?>
                </button>
            </form>
        </div>

    </div> <!-- / grid -->

    <?php 
    $backups = \UglyLinks\App\Controllers\Admin\BackupController::getBackups(); 
    if ( ! empty( $backups ) ):
    ?>
    <div style="margin-top: 48px; background: var(--ul-surface); border: 1px solid var(--ul-border); border-radius: var(--ul-radius); padding: 32px; box-shadow: var(--ul-shadow);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <div>
                <h2 style="font-size: 18px; font-weight: 600; margin: 0 0 4px;">Automated Daily Backups</h2>
                <p style="font-size: 13px; color: var(--ul-text-muted); margin: 0;">These backups are generated automatically and stored securely on your server.</p>
            </div>
            <span style="background: var(--ul-primary-light); color: var(--ul-primary); padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">Active</span>
        </div>

        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 14px;">
            <thead>
                <tr style="border-bottom: 2px solid var(--ul-border); color: var(--ul-text-muted);">
                    <th style="padding: 12px 16px; font-weight: 600;">Date & Time</th>
                    <th style="padding: 12px 16px; font-weight: 600;">Filename</th>
                    <th style="padding: 12px 16px; font-weight: 600;">Total Links</th>
                    <th style="padding: 12px 16px; font-weight: 600;">File Size</th>
                    <th style="padding: 12px 16px; font-weight: 600; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ( $backups as $index => $backup ): ?>
                    <tr style="border-bottom: 1px solid var(--ul-border); <?php echo $index % 2 === 0 ? 'background: var(--ul-surface-alt);' : ''; ?>">
                        <td style="padding: 12px 16px; font-weight: 500;">
                            <?php echo esc_html( wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $backup['timestamp'] ) ); ?>
                        </td>
                        <td style="padding: 12px 16px; font-family: monospace; color: var(--ul-text-muted);">
                            <?php echo esc_html( $backup['filename'] ); ?>
                        </td>
                        <td style="padding: 12px 16px;">
                            <span style="background: var(--ul-success-light); color: var(--ul-success); padding: 2px 8px; border-radius: 12px; font-size: 12px; font-weight: 600;">
                                <?php echo esc_html( number_format_i18n( $backup['total_links'] ) ); ?>
                            </span>
                        </td>
                        <td style="padding: 12px 16px; color: var(--ul-text-muted);">
                            <?php echo esc_html( $backup['size'] ); ?>
                        </td>
                        <td style="padding: 12px 16px; text-align: right;">
                            <button type="button" class="uglylinks-btn uglylinks-btn--ghost uglylinks-revert-btn" data-filename="<?php echo esc_attr( $backup['filename'] ); ?>" style="color: var(--ul-danger); border: 1px solid #fecaca; background: #fffbfa; padding: 6px 12px; font-size: 12px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;"><path d="M3 11V9a4 4 0 0 1 4-4h14"></path><polyline points="7 1 3 5 7 9"></polyline><path d="M21 13v2a4 4 0 0 1-4 4H3"></path><polyline points="17 23 21 19 17 15"></polyline></svg>
                                Revert
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- Revert Confirmation Modal -->
    <div id="uglylinks-revert-modal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(2px); z-index: 99999; align-items: center; justify-content: center;">
        <div style="background: var(--ul-surface); border-radius: var(--ul-radius); box-shadow: 0 20px 40px rgba(0,0,0,0.2); width: min(400px, 90vw); padding: 32px; text-align: center;">
            <div style="width: 48px; height: 48px; border-radius: 50%; background: var(--ul-danger-light); color: var(--ul-danger); display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
            </div>
            <h2 style="font-size: 20px; font-weight: 700; margin: 0 0 8px;">Are you absolutely sure?</h2>
            <p style="font-size: 13px; color: var(--ul-text-muted); margin: 0 0 16px;">This action will wipe your current links and restore them to the state of <strong id="revert-target-file" style="color: var(--ul-text);"></strong>. Click counts will be reset to 0.</p>
            
            <p style="font-size: 13px; font-weight: 600; margin: 0 0 8px;">Type REVERT to confirm:</p>
            <input type="text" id="revert-confirm-input" class="uglylinks-form__input" placeholder="REVERT" style="text-align: center; font-weight: 600; margin-bottom: 24px; text-transform: uppercase;">

            <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="revert-form" style="display: flex; gap: 12px; justify-content: center;">
                <input type="hidden" name="action" value="uglylinks_revert">
                <input type="hidden" name="backup_file" id="revert-backup-file" value="">
                <?php wp_nonce_field( 'uglylinks_revert_nonce', 'uglylinks_nonce' ); ?>
                
                <button type="button" id="revert-cancel" class="uglylinks-btn uglylinks-btn--ghost" style="border: 1px solid var(--ul-border);">Cancel</button>
                <button type="submit" id="revert-submit" class="uglylinks-btn" style="background: var(--ul-danger); color: white; border: none; opacity: 0.5; cursor: not-allowed;" disabled>Restore Backup</button>
            </form>
        </div>
    </div>

</div>
