<?php
/**
 * Admin View – SPA-like Reactive Link List.
 *
 * Renders a shell grid layout. All data is loaded and mutated
 * exclusively via the uglylinks/v1 REST API by admin.js.
 *
 * PHP only outputs:
 *   – The static page shell and accessible skeleton.
 *   – A single <script> block injecting the JS config object
 *     (nonce, REST root, page strings) — no PHP data loops.
 *
 * @package UglyLinks
 */

declare(strict_types=1);

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wrap uglylinks-wrap" id="uglylinks-app">

    <!-- ── Page Header ─────────────────────────────────────────────────────── -->
    <div class="uglylinks-header">
        <div class="uglylinks-header__left">
            <h1 id="uglylinks-page-title">
                <span class="uglylinks-logo-icon" aria-hidden="true" style="color: #2563eb; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; margin-right: 6px; vertical-align: middle;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="-50 -50 100 100" fill="currentColor"><g><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(0)" /><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(60)" /><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(120)" /><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(180)" /><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(240)" /><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(300)" /><circle cx="0" cy="0" r="12" /></g></svg>
                </span>
                <?php esc_html_e( 'UglyLinks', 'ug-links' ); ?>
            </h1>
            <p class="uglylinks-tagline"><?php esc_html_e( 'High-performance link shortener & tracking engine. Designed and engineered by Bishow Shrestha.', 'ug-links' ); ?></p>
        </div>
        <div class="uglylinks-header__right" style="display: flex; gap: 8px; align-items: center;">
            
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=uglylinks-tools' ) ); ?>" class="uglylinks-btn uglylinks-btn--ghost" title="Import / Export Tools">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                <?php esc_html_e( 'Import / Export', 'ug-links' ); ?>
            </a>

            <button
                id="uglylinks-open-create"
                class="uglylinks-btn uglylinks-btn--primary"
                aria-haspopup="dialog">
                <span aria-hidden="true">+</span>
                <?php esc_html_e( 'New Link', 'ug-links' ); ?>
            </button>
        </div>
    </div>

    <!-- ── Global Notice Bar ───────────────────────────────────────────────── -->
    <div
        id="uglylinks-notice"
        class="uglylinks-notice uglylinks-notice--hidden"
        role="status"
        aria-live="polite">
    </div>

    <!-- ── Stats Summary Row ───────────────────────────────────────────────── -->
    <div class="uglylinks-summary-grid" id="uglylinks-summary" aria-busy="true">
        <div class="uglylinks-stat-card uglylinks-skeleton">
            <span class="uglylinks-stat-card__label"><?php esc_html_e( 'Total Links', 'ug-links' ); ?></span>
            <span class="uglylinks-stat-card__value" id="summary-total-links">—</span>
        </div>
        <div class="uglylinks-stat-card uglylinks-skeleton">
            <span class="uglylinks-stat-card__label"><?php esc_html_e( 'Total Clicks', 'ug-links' ); ?></span>
            <span class="uglylinks-stat-card__value" id="summary-total-clicks">—</span>
        </div>
        <div class="uglylinks-stat-card uglylinks-skeleton">
            <span class="uglylinks-stat-card__label"><?php esc_html_e( 'Top Link', 'ug-links' ); ?></span>
            <span class="uglylinks-stat-card__value" id="summary-top-link">—</span>
        </div>
    </div>

    <!-- ── Link Grid ───────────────────────────────────────────────────────── -->
    <div class="uglylinks-toolbar">
        <input
            type="search"
            id="uglylinks-search"
            class="uglylinks-search-input"
            placeholder="<?php esc_attr_e( 'Filter links…', 'ug-links' ); ?>"
            aria-label="<?php esc_attr_e( 'Filter links', 'ug-links' ); ?>">
        <span class="uglylinks-count-label" id="uglylinks-count-label" aria-live="polite"></span>
    </div>

    <table class="uglylinks-table wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col" class="column-title"><?php esc_html_e( 'Title / Slug', 'ug-links' ); ?></th>
                <th scope="col" class="column-target"><?php esc_html_e( 'Destination URL', 'ug-links' ); ?></th>
                <th scope="col" class="column-type" style="width: 80px;"><?php esc_html_e( 'Type', 'ug-links' ); ?></th>
                <th scope="col" class="column-clicks num" style="width: 100px;"><?php esc_html_e( 'Clicks', 'ug-links' ); ?></th>
                <th scope="col" class="column-actions" style="width: 150px;"></th>
            </tr>
        </thead>
        <tbody id="uglylinks-grid" aria-busy="true" aria-live="polite">
            <!-- Rows injected by admin.js -->
            <?php for ( $i = 0; $i < 6; $i++ ) : ?>
            <tr class="uglylinks-skeleton-row" aria-hidden="true">
                <td><div class="uglylinks-skeleton-bar"></div><div class="uglylinks-skeleton-bar" style="width:60%; margin-top:4px;"></div></td>
                <td><div class="uglylinks-skeleton-bar"></div></td>
                <td><div class="uglylinks-skeleton-bar"></div></td>
                <td><div class="uglylinks-skeleton-bar"></div></td>
                <td><div class="uglylinks-skeleton-bar"></div></td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <!-- ── Pagination ──────────────────────────────────────────────────────── -->
    <div class="uglylinks-pagination" id="uglylinks-pagination" aria-label="<?php esc_attr_e( 'Pagination', 'ug-links' ); ?>">
    </div>

    <!-- ── Create / Edit Drawer ────────────────────────────────────────────── -->
    <div
        id="uglylinks-drawer"
        class="uglylinks-drawer uglylinks-drawer--closed"
        role="dialog"
        aria-modal="true"
        aria-labelledby="uglylinks-drawer-title"
        aria-hidden="true">

        <div class="uglylinks-drawer__backdrop" id="uglylinks-drawer-backdrop"></div>

        <div class="uglylinks-drawer__panel">
            <div class="uglylinks-drawer__header">
                <h2 id="uglylinks-drawer-title"><?php esc_html_e( 'New Link', 'ug-links' ); ?></h2>
                <button
                    id="uglylinks-drawer-close"
                    class="uglylinks-drawer__close"
                    aria-label="<?php esc_attr_e( 'Close drawer', 'ug-links' ); ?>">
                    &#x2715;
                </button>
            </div>

            <form id="uglylinks-form" class="uglylinks-form" novalidate>
                <input type="hidden" id="form-link-id" name="id" value="">

                <div class="uglylinks-form__group">
                    <label for="form-title" class="uglylinks-form__label">
                        <?php esc_html_e( 'Title', 'ug-links' ); ?>
                        <span class="uglylinks-form__hint"><?php esc_html_e( '(optional)', 'ug-links' ); ?></span>
                    </label>
                    <input
                        type="text"
                        id="form-title"
                        name="title"
                        class="uglylinks-form__input"
                        placeholder="<?php esc_html_e( 'e.g. Black Friday Campaign', 'ug-links' ); ?>">
                </div>

                <div class="uglylinks-form__group uglylinks-form__group--required">
                    <label for="form-target-url" class="uglylinks-form__label">
                        <?php esc_html_e( 'Destination URL', 'ug-links' ); ?>
                    </label>
                    <div style="position: relative;">
                        <input
                            type="url"
                            id="form-target-url"
                            name="target_url"
                            class="uglylinks-form__input"
                            required
                            placeholder="https://example.com/your/long/link"
                            style="padding-right: 32px;">
                        <span id="target-url-validation" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); display: none;"></span>
                    </div>
                    <span class="uglylinks-form__error" id="error-target-url" role="alert"></span>
                </div>

                <div class="uglylinks-form__group uglylinks-form__group--required">
                    <label for="form-slug" class="uglylinks-form__label">
                        <?php esc_html_e( 'Short Slug', 'ug-links' ); ?>
                    </label>
                    <div class="uglylinks-slug-row">
                        <span class="uglylinks-slug-row__prefix" id="slug-prefix-display"></span>
                        <input
                            type="text"
                            id="form-slug"
                            name="slug"
                            class="uglylinks-form__input uglylinks-slug-row__input"
                            required
                            pattern="[a-zA-Z0-9\-_]+"
                            placeholder="my-link"
                            autocomplete="off"
                            spellcheck="false">
                        <button type="button" id="uglylinks-auto-slug" class="uglylinks-icon-btn" title="Generate random slug" style="margin: 4px; border-left: 1px solid var(--ul-border); border-radius: 0; padding-left: 8px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        </button>
                    </div>
                    <span class="uglylinks-form__error" id="error-slug" role="alert"></span>
                </div>

                <div class="uglylinks-form__group">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                        <?php esc_html_e( 'Geotargeting Rules', 'ug-links' ); ?>
                        <span class="uglylinks-form__hint"><?php esc_html_e( '(optional)', 'ug-links' ); ?></span>
                    </label>
                    <div style="background: var(--ul-surface-alt); border: 1px solid var(--ul-border); border-radius: var(--ul-radius-sm); overflow: hidden;">
                        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 13px;" id="drawer-geo-rules-table">
                            <thead>
                                <tr style="border-bottom: 1px solid var(--ul-border); background: rgba(0,0,0,0.02);">
                                    <th style="padding: 8px 10px; font-weight: 500; width: 60px;">Code</th>
                                    <th style="padding: 8px 10px; font-weight: 500;">Destination URL</th>
                                    <th style="padding: 8px 10px; font-weight: 500; width: 30px;"></th>
                                </tr>
                            </thead>
                            <tbody id="drawer-geo-rules-body">
                                <!-- Rows injected via JS -->
                            </tbody>
                        </table>
                        <div style="padding: 8px 10px; border-top: 1px solid var(--ul-border);">
                            <button type="button" id="drawer-add-geo-rule-btn" class="uglylinks-btn uglylinks-btn--ghost" style="font-size: 12px; padding: 4px 8px;">+ Add Rule</button>
                        </div>
                    </div>
                    <input type="hidden" id="form-geo-rules" name="geo_rules" value="">
                    <p style="font-size: 12px; color: var(--ul-text-muted); margin: 6px 0 0;">Users from matching countries will be redirected to the specific URL instead of the default.</p>
                </div>

                <div class="uglylinks-form__group">
                    <label for="form-redirect-type" class="uglylinks-form__label">
                        <?php esc_html_e( 'Redirect Type', 'ug-links' ); ?>
                    </label>
                    <div class="uglylinks-toggle-group" role="group" aria-label="<?php esc_attr_e( 'Redirect type', 'ug-links' ); ?>">
                        <label class="uglylinks-toggle-option">
                            <input type="radio" name="redirect_type" value="301">
                            <span>301 — <?php esc_html_e( 'Permanent', 'ug-links' ); ?></span>
                        </label>
                        <label class="uglylinks-toggle-option">
                            <input type="radio" name="redirect_type" value="302">
                            <span>302 — <?php esc_html_e( 'Temporary', 'ug-links' ); ?></span>
                        </label>
                        <label class="uglylinks-toggle-option">
                            <input type="radio" name="redirect_type" value="307" checked>
                            <span>307 — <?php esc_html_e( 'Temporary', 'ug-links' ); ?></span>
                        </label>
                    </div>
                </div>

                <!-- Pro Features -->
                <div class="uglylinks-form__group" style="margin-top: 12px; padding: 16px; border: 1px solid var(--ul-primary); border-radius: var(--ul-radius); background: var(--ul-surface-alt);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                        <span class="uglylinks-form__label" style="margin: 0; color: var(--ul-primary);">
                            <?php esc_html_e( 'Pro Features', 'ug-links' ); ?>
                        </span>
                        <span style="background: var(--ul-primary); color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 600; text-transform: uppercase;">Active</span>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="form-ab-target" class="uglylinks-form__label" style="font-size: 13px;">A/B Split Test Target URL</label>
                        <input type="url" id="form-ab-target" name="ab_target" class="uglylinks-form__input" placeholder="https://example.com/b-variant" style="font-size: 13px;">
                        <p style="font-size: 11px; color: var(--ul-text-muted); margin: 4px 0 0;">50% of traffic will be randomly sent to this URL instead of the main Destination URL.</p>
                    </div>

                    <div>
                        <label for="form-retargeting-pixels" class="uglylinks-form__label" style="font-size: 13px;">Retargeting Pixels (HTML/JS)</label>
                        <textarea id="form-retargeting-pixels" name="retargeting_pixels" class="uglylinks-form__input" placeholder="<script>...</script>" style="font-size: 13px; font-family: monospace; min-height: 80px;"></textarea>
                        <p style="font-size: 11px; color: var(--ul-text-muted); margin: 4px 0 0;">Add Meta, Google, or other tracking pixels here. This changes the redirect to a JS-based redirect.</p>
                    </div>
                </div>

                <div class="uglylinks-form__actions">
                    <button type="button" id="uglylinks-cancel-drawer" class="uglylinks-btn uglylinks-btn--ghost">
                        <?php esc_html_e( 'Cancel', 'ug-links' ); ?>
                    </button>
                    <button type="submit" id="uglylinks-form-submit" class="uglylinks-btn uglylinks-btn--primary">
                        <span class="uglylinks-btn__label"><?php esc_html_e( 'Create Link', 'ug-links' ); ?></span>
                        <span class="uglylinks-btn__spinner" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div><!-- #uglylinks-app -->

<?php
// ── JavaScript Config Injection ───────────────────────────────────────────────
// Pass PHP values to JS without inline data attributes.
// wp_create_nonce generates a nonce scoped to the REST API.
?>
<script id="uglylinks-config">
window.UglyLinksConfig = <?php
    echo wp_json_encode( [
        'restRoot'  => esc_url_raw( rest_url( 'uglylinks/v1' ) ),
        'nonce'     => wp_create_nonce( 'wp_rest' ),
        'homeUrl'   => esc_url_raw( home_url( '/' ) ),
        'perPage'   => 20,
        'i18n'      => [
            'confirmDelete'  => __( 'Delete this link? All click data will be lost.', 'ug-links' ),
            'copied'         => __( 'Copied!', 'ug-links' ),
            'copyFailed'     => __( 'Copy failed', 'ug-links' ),
            'saved'          => __( 'Link saved.', 'ug-links' ),
            'deleted'        => __( 'Link deleted.', 'ug-links' ),
            'errorGeneric'   => __( 'Something went wrong. Please try again.', 'ug-links' ),
            'slugTaken'      => __( 'This slug is already in use.', 'ug-links' ),
            'invalidUrl'     => __( 'Please enter a valid URL.', 'ug-links' ),
            'noLinks'        => __( 'No links yet. Create your first!', 'ug-links' ),
            'editLink'       => __( 'Edit Link', 'ug-links' ),
            'newLink'        => __( 'New Link', 'ug-links' ),
            'updateLink'     => __( 'Update Link', 'ug-links' ),
            'clicks'         => __( 'clicks', 'ug-links' ),
        ],
    ] );
?>;
</script>
