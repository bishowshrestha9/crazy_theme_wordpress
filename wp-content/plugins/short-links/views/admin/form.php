<?php
/**
 * Admin View – Link Create / Edit Form.
 *
 * Variables available (extracted by LinksController::renderView):
 *   @var object|null $link         Existing link row (null for new).
 *   @var string      $nonce_field  Nonce input name.
 *   @var string      $nonce_action Nonce action string.
 *   @var string      $notice       Notice key from query arg.
 *
 * @package ShortLinks
 */

declare(strict_types=1);

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$is_edit   = null !== $link;
$page_title = $is_edit
    ? __( 'Edit Link', 'short-links' )
    : __( 'Add New Link', 'short-links' );

// Safely extract existing values (or defaults for new).
$link_id       = $is_edit ? absint( $link->id )                        : 0;
$slug_val      = $is_edit ? esc_attr( $link->slug )                    : '';
$target_val    = $is_edit ? esc_attr( $link->target_url )              : '';
$title_val     = $is_edit ? esc_attr( $link->title )                   : '';
$redirect_val  = $is_edit ? esc_attr( $link->redirect_type )           : '307';
$list_url      = add_query_arg( [ 'page' => 'shortlinks' ], admin_url( 'admin.php' ) );
?>
<div class="wrap shortlinks-wrap" id="shortlinks-app" style="margin-top: 32px;">

    <div style="margin-bottom: 32px; display: flex; align-items: center; justify-content: space-between;">
        <div>
            <h1 style="font-size: 28px; font-weight: 300; margin: 0 0 8px;"><?php echo esc_html( $page_title ); ?></h1>
            <p style="font-size: 14px; color: var(--ul-text-muted); margin: 0;">Configure your link routing parameters.</p>
        </div>
        <a href="<?php echo esc_url( $list_url ); ?>" class="shortlinks-btn shortlinks-btn--ghost">
            &larr; <?php esc_html_e( 'Back to All Links', 'short-links' ); ?>
        </a>
    </div>

    <?php
    $notice_map = [
        'saved'          => [ 'success', __( 'Link saved successfully.',       'short-links' ) ],
        'error'          => [ 'error',   __( 'An error occurred. Please try again.', 'short-links' ) ],
        'missing_fields' => [ 'warning', __( 'Slug and Destination URL are required.', 'short-links' ) ],
        'slug_taken'     => [ 'error',   __( 'That slug is already in use. Please choose another.', 'short-links' ) ],
    ];

    if ( isset( $notice_map[ $notice ] ) ) :
        [ $type, $message ] = $notice_map[ $notice ];
        $bg = $type === 'success' ? 'var(--ul-success-light)' : ($type === 'error' ? 'var(--ul-danger-light)' : 'var(--ul-warning-light)');
        $color = $type === 'success' ? 'var(--ul-success)' : ($type === 'error' ? 'var(--ul-danger)' : 'var(--ul-warning)');
        ?>
        <div style="background: <?php echo esc_attr( $bg ); ?>; color: <?php echo esc_attr( $color ); ?>; padding: 16px; border-radius: var(--ul-radius); margin-bottom: 24px; font-weight: 500;">
            <?php echo esc_html( $message ); ?>
        </div>
    <?php endif; ?>

    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display: grid; grid-template-columns: 1fr 350px; gap: 32px; align-items: start;">
        
        <input type="hidden" name="action"  value="shortlinks_save">
        <input type="hidden" name="link_id" value="<?php echo absint( $link_id ); ?>">
        <?php wp_nonce_field( $nonce_action, $nonce_field ); ?>
        <!-- Left Column: Main Inputs -->
        <div style="background: var(--ul-surface); border: 1px solid var(--ul-border); border-radius: var(--ul-radius); padding: 32px; box-shadow: var(--ul-shadow);">
            
            <div style="margin-bottom: 24px;">
                <label for="shortlinks-title" style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                    <?php esc_html_e( 'Link Title', 'short-links' ); ?>
                    <span style="font-weight: 400; color: var(--ul-text-muted); font-size: 12px; margin-left: 4px;">(Optional)</span>
                </label>
                <input
                    type="text"
                    id="shortlinks-title"
                    name="title"
                    value="<?php echo $title_val; ?>"
                    class="shortlinks-form__input"
                    placeholder="<?php esc_attr_e( 'e.g. Summer Sale Campaign', 'short-links' ); ?>">
            </div>

            <div style="margin-bottom: 24px;">
                <label for="shortlinks-target-url" style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                    <?php esc_html_e( 'Destination URL', 'short-links' ); ?>
                    <span style="color: var(--ul-danger);">*</span>
                </label>
                <input
                    type="url"
                    id="shortlinks-target-url"
                    name="target_url"
                    value="<?php echo $target_val; ?>"
                    class="shortlinks-form__input"
                    required
                    placeholder="https://example.com/your/very/long/landing-page-url">
                <p style="font-size: 12px; color: var(--ul-text-muted); margin: 6px 0 0;">Where should users be redirected when they click this link?</p>
            </div>

            <div style="margin-bottom: 8px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                    <?php esc_html_e( 'Short Slug', 'short-links' ); ?>
                    <span style="color: var(--ul-danger);">*</span>
                </label>
                <div style="display: flex; align-items: center; background: var(--ul-surface-alt); border: 1px solid var(--ul-border); border-radius: var(--ul-radius-sm); overflow: hidden; transition: border-color var(--ul-transition), box-shadow var(--ul-transition);">
                    <span style="padding: 12px 16px; color: var(--ul-text-muted); border-right: 1px solid var(--ul-border); font-size: 14px; background: rgba(0,0,0,0.02);"><?php echo esc_html( home_url( '/' ) ); ?></span>
                    <input
                        type="text"
                        name="slug"
                        value="<?php echo $slug_val; ?>"
                        class="shortlinks-form__input"
                        required
                        pattern="[a-zA-Z0-9\-_]+"
                        placeholder="my-link"
                        style="border: none; border-radius: 0; box-shadow: none; background: transparent;">
                </div>
                <p style="font-size: 12px; color: var(--ul-text-muted); margin: 6px 0 0;">Only alphanumeric characters, hyphens, and underscores.</p>
            </div>

            <div style="margin-top: 24px;">
                <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                    <?php esc_html_e( 'Geotargeting Rules', 'short-links' ); ?>
                    <span style="font-weight: 400; color: var(--ul-text-muted); font-size: 12px; margin-left: 4px;">(Optional)</span>
                </label>
                <div style="background: var(--ul-surface-alt); border: 1px solid var(--ul-border); border-radius: var(--ul-radius-sm); overflow: hidden;">
                    <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;" id="geo-rules-table">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--ul-border); background: rgba(0,0,0,0.02);">
                                <th style="padding: 10px 12px; font-weight: 500; width: 120px;">Country Code</th>
                                <th style="padding: 10px 12px; font-weight: 500;">Destination URL</th>
                                <th style="padding: 10px 12px; font-weight: 500; width: 40px;"></th>
                            </tr>
                        </thead>
                        <tbody id="geo-rules-body">
                            <!-- Rows injected via JS -->
                        </tbody>
                    </table>
                    <div style="padding: 12px; border-top: 1px solid var(--ul-border);">
                        <button type="button" id="add-geo-rule-btn" class="shortlinks-btn shortlinks-btn--ghost" style="font-size: 12px; padding: 6px 10px;">+ Add Rule</button>
                    </div>
                </div>
                <input type="hidden" id="form-geo-rules" name="geo_rules" value="<?php echo esc_attr( $link->geo_rules ?? '' ); ?>">
                <p style="font-size: 12px; color: var(--ul-text-muted); margin: 6px 0 0;">Users from matching countries will be redirected to the specific URL instead of the default.</p>
            </div>
            
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tbody = document.getElementById('geo-rules-body');
                const addBtn = document.getElementById('add-geo-rule-btn');
                const hiddenInput = document.getElementById('form-geo-rules');

                let rules = {};
                try {
                    rules = JSON.parse(hiddenInput.value || '{}');
                } catch(e) {}

                function renderRows() {
                    tbody.innerHTML = '';
                    for (const [code, url] of Object.entries(rules)) {
                        addRow(code, url);
                    }
                    if (Object.keys(rules).length === 0) {
                        tbody.innerHTML = '<tr><td colspan="3" style="padding: 16px; text-align: center; color: var(--ul-text-muted); font-size: 13px;">No geotargeting rules active.</td></tr>';
                    }
                }

                function addRow(code = '', url = '') {
                    if (Object.keys(rules).length === 0 && tbody.querySelector('td[colspan]')) {
                        tbody.innerHTML = '';
                    }
                    const tr = document.createElement('tr');
                    tr.style.borderBottom = '1px solid var(--ul-border)';
                    tr.innerHTML = `
                        <td style="padding: 8px 12px;">
                            <input type="text" class="geo-code-input shortlinks-form__input" value="${code}" placeholder="US" style="width: 100%; padding: 6px 8px; text-transform: uppercase;" maxlength="2">
                        </td>
                        <td style="padding: 8px 12px;">
                            <input type="url" class="geo-url-input shortlinks-form__input" value="${url}" placeholder="https://..." style="width: 100%; padding: 6px 8px;">
                        </td>
                        <td style="padding: 8px 12px; text-align: right;">
                            <button type="button" class="remove-geo-rule shortlinks-icon-btn" style="color: var(--ul-danger);" title="Remove">
                                &times;
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);

                    tr.querySelector('.remove-geo-rule').addEventListener('click', function() {
                        tr.remove();
                        syncHiddenInput();
                    });

                    tr.querySelectorAll('input').forEach(inp => {
                        inp.addEventListener('input', syncHiddenInput);
                    });
                }

                function syncHiddenInput() {
                    const newRules = {};
                    const rows = tbody.querySelectorAll('tr');
                    rows.forEach(row => {
                        const codeInput = row.querySelector('.geo-code-input');
                        if (!codeInput) return;
                        const code = codeInput.value.trim().toUpperCase();
                        const url = row.querySelector('.geo-url-input').value.trim();
                        if (code && url) {
                            newRules[code] = url;
                        }
                    });
                    hiddenInput.value = Object.keys(newRules).length > 0 ? JSON.stringify(newRules) : '';
                }

                addBtn.addEventListener('click', function() {
                    addRow('', '');
                });

                renderRows();
            });
            </script>
        </div>

        <!-- Right Column: Settings & Publish -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <div style="background: var(--ul-surface); border: 1px solid var(--ul-border); border-radius: var(--ul-radius); padding: 24px; box-shadow: var(--ul-shadow);">
                <h2 style="font-size: 16px; font-weight: 600; margin: 0 0 16px;">Publish</h2>
                
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 13px;">Redirect Type</label>
                    <select name="redirect_type" class="shortlinks-form__input" style="padding: 8px 12px;">
                        <option value="301" <?php selected( $redirect_val, '301' ); ?>>301 — Permanent</option>
                        <option value="302" <?php selected( $redirect_val, '302' ); ?>>302 — Temporary</option>
                        <option value="307" <?php selected( $redirect_val, '307' ); ?>>307 — Temporary</option>
                        <option value="cloak" <?php selected( $redirect_val, 'cloak' ); ?>>Cloak (Iframe)</option>
                    </select>
                </div>

                <div style="display: flex; gap: 12px; flex-direction: column;">
                    <button type="submit" class="shortlinks-btn shortlinks-btn--primary" style="width: 100%; padding: 12px; font-size: 14px;">
                        <?php echo esc_html( $is_edit ? __( 'Update Link', 'short-links' ) : __( 'Create Link', 'short-links' ) ); ?>
                    </button>
                    <?php if ( $is_edit ) : ?>
                        <a href="<?php echo esc_url( home_url( '/' . $link->slug ) ); ?>" class="shortlinks-btn shortlinks-btn--ghost" target="_blank" rel="noopener noreferrer" style="width: 100%; border: 1px solid var(--ul-border); padding: 12px; font-size: 14px;">
                            <?php esc_html_e( 'Preview Link', 'short-links' ); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ( $is_edit ) : ?>
            <div style="background: var(--ul-surface); border: 1px solid var(--ul-border); border-radius: var(--ul-radius); padding: 24px; box-shadow: var(--ul-shadow);">
                <h2 style="font-size: 16px; font-weight: 600; margin: 0 0 16px;">Stats</h2>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px dashed var(--ul-border);">
                    <span style="font-size: 13px; color: var(--ul-text-muted);">Total Clicks</span>
                    <span style="font-size: 16px; font-weight: 700; color: var(--ul-primary);"><?php echo absint( \ShortLinks\App\Models\Click::countForLink( absint( $link->id ) ) ); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 13px; color: var(--ul-text-muted);">Created On</span>
                    <span style="font-size: 13px; font-weight: 500; color: var(--ul-text);"><?php echo esc_html( wp_date( get_option( 'date_format' ), strtotime( $link->created_at ) ) ); ?></span>
                </div>
            </div>
            <?php endif; ?>

            <!-- Pro Feature Teasers -->
            <div style="background: var(--ul-surface-alt); border: 1px solid var(--ul-primary); border-radius: var(--ul-radius); padding: 24px; margin-top: 24px;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                    <h3 style="margin: 0; font-size: 14px; font-weight: 600; color: var(--ul-primary);">Pro Features</h3>
                    <span style="background: var(--ul-primary); color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 600; text-transform: uppercase;">Active</span>
                </div>
                
                <div style="margin-bottom: 16px;">
                    <label for="form-ab-target" class="shortlinks-form__label" style="font-size: 13px;">A/B Split Test Target URL</label>
                    <input type="url" id="form-ab-target" name="ab_target" value="<?php echo esc_attr( $link->ab_target ?? '' ); ?>" class="shortlinks-form__input" placeholder="https://example.com/b-variant" style="font-size: 13px; padding: 8px;">
                    <p style="font-size: 11px; color: var(--ul-text-muted); margin: 4px 0 0;">50% of traffic will be randomly sent to this URL instead of the main Destination URL.</p>
                </div>

                <div>
                    <label for="form-retargeting-pixels" class="shortlinks-form__label" style="font-size: 13px;">Retargeting Pixels (HTML/JS)</label>
                    <textarea id="form-retargeting-pixels" name="retargeting_pixels" class="shortlinks-form__input" placeholder="<script>...</script>" style="font-size: 13px; font-family: monospace; min-height: 80px; padding: 8px;"><?php echo esc_textarea( $link->retargeting_pixels ?? '' ); ?></textarea>
                    <p style="font-size: 11px; color: var(--ul-text-muted); margin: 4px 0 0;">Add Meta, Google, or other tracking pixels here. This changes the redirect to a JS-based redirect.</p>
                </div>
            </div>

        </div>

    </form>
</div>
