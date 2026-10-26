<?php
/**
 * Admin View – SPA-like Reactive Link List.
 *
 * Renders a shell grid layout. All data is loaded and mutated
 * exclusively via the shortlinks/v1 REST API by admin.js.
 *
 * PHP only outputs:
 *   – The static page shell and accessible skeleton.
 *   – A single <script> block injecting the JS config object
 *     (nonce, REST root, page strings) — no PHP data loops.
 *
 * @package ShortLinks
 */

declare(strict_types=1);

// Prevent direct file access.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<div class="wrap shortlinks-wrap" id="shortlinks-app">

    <!-- ── Page Header ─────────────────────────────────────────────────────── -->
    <div class="shortlinks-header">
        <div class="shortlinks-header__left">
            <h1 id="shortlinks-page-title">
                <span class="shortlinks-logo-icon" aria-hidden="true" style="color: #2563eb; display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; margin-right: 6px; vertical-align: middle;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="-50 -50 100 100" fill="currentColor"><g><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(0)" /><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(60)" /><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(120)" /><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(180)" /><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(240)" /><path d="M 10,0 C 20,-20 40,-30 50,-10 C 30,0 20,10 10,0 Z" transform="rotate(300)" /><circle cx="0" cy="0" r="12" /></g></svg>
                </span>
                <?php esc_html_e( 'ShortLinks', 'short-links' ); ?>
            </h1>
            <p class="shortlinks-tagline"><?php esc_html_e( 'High-performance link shortener & tracking engine. Designed and engineered by Bishow Shrestha.', 'short-links' ); ?></p>
        </div>
        <div class="shortlinks-header__right" style="display: flex; gap: 8px; align-items: center;">
            
            <a href="<?php echo esc_url( admin_url( 'admin.php?page=shortlinks-tools' ) ); ?>" class="shortlinks-btn shortlinks-btn--ghost" title="Import / Export Tools">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:4px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                <?php esc_html_e( 'Import / Export', 'short-links' ); ?>
            </a>

            <button
                id="shortlinks-open-create"
                class="shortlinks-btn shortlinks-btn--primary"
                aria-haspopup="dialog">
                <span aria-hidden="true">+</span>
                <?php esc_html_e( 'New Link', 'short-links' ); ?>
            </button>
        </div>
    </div>

    <!-- ── Global Notice Bar ───────────────────────────────────────────────── -->
    <div
        id="shortlinks-notice"
        class="shortlinks-notice shortlinks-notice--hidden"
        role="status"
        aria-live="polite">
    </div>

    <!-- ── Stats Summary Row ───────────────────────────────────────────────── -->
    <div class="shortlinks-summary-grid" id="shortlinks-summary" aria-busy="true">
        <div class="shortlinks-stat-card shortlinks-skeleton">
            <span class="shortlinks-stat-card__label"><?php esc_html_e( 'Total Links', 'short-links' ); ?></span>
            <span class="shortlinks-stat-card__value" id="summary-total-links">—</span>
        </div>
        <div class="shortlinks-stat-card shortlinks-skeleton">
            <span class="shortlinks-stat-card__label"><?php esc_html_e( 'Total Clicks', 'short-links' ); ?></span>
            <span class="shortlinks-stat-card__value" id="summary-total-clicks">—</span>
        </div>
        <div class="shortlinks-stat-card shortlinks-skeleton">
            <span class="shortlinks-stat-card__label"><?php esc_html_e( 'Top Link', 'short-links' ); ?></span>
            <span class="shortlinks-stat-card__value" id="summary-top-link">—</span>
        </div>
    </div>

    <!-- ── Link Grid ───────────────────────────────────────────────────────── -->
    <div class="shortlinks-toolbar">
        <input
            type="search"
            id="shortlinks-search"
            class="shortlinks-search-input"
            placeholder="<?php esc_attr_e( 'Filter links…', 'short-links' ); ?>"
            aria-label="<?php esc_attr_e( 'Filter links', 'short-links' ); ?>">
        <span class="shortlinks-count-label" id="shortlinks-count-label" aria-live="polite"></span>
    </div>

    <table class="shortlinks-table wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col" class="column-title"><?php esc_html_e( 'Title / Slug', 'short-links' ); ?></th>
                <th scope="col" class="column-target"><?php esc_html_e( 'Destination URL', 'short-links' ); ?></th>
                <th scope="col" class="column-type" style="width: 80px;"><?php esc_html_e( 'Type', 'short-links' ); ?></th>
                <th scope="col" class="column-clicks num" style="width: 100px;"><?php esc_html_e( 'Clicks', 'short-links' ); ?></th>
                <th scope="col" class="column-actions" style="width: 150px;"></th>
            </tr>
        </thead>
        <tbody id="shortlinks-grid" aria-busy="true" aria-live="polite">
            <!-- Rows injected by admin.js -->
            <?php for ( $i = 0; $i < 6; $i++ ) : ?>
            <tr class="shortlinks-skeleton-row" aria-hidden="true">
                <td><div class="shortlinks-skeleton-bar"></div><div class="shortlinks-skeleton-bar" style="width:60%; margin-top:4px;"></div></td>
                <td><div class="shortlinks-skeleton-bar"></div></td>
                <td><div class="shortlinks-skeleton-bar"></div></td>
                <td><div class="shortlinks-skeleton-bar"></div></td>
                <td><div class="shortlinks-skeleton-bar"></div></td>
            </tr>
            <?php endfor; ?>
        </tbody>
    </table>

    <!-- ── Pagination ──────────────────────────────────────────────────────── -->
    <div class="shortlinks-pagination" id="shortlinks-pagination" aria-label="<?php esc_attr_e( 'Pagination', 'short-links' ); ?>">
    </div>

    <!-- ── Create / Edit Drawer ────────────────────────────────────────────── -->
    <div
        id="shortlinks-drawer"
        class="shortlinks-drawer shortlinks-drawer--closed"
        role="dialog"
        aria-modal="true"
        aria-labelledby="shortlinks-drawer-title"
        aria-hidden="true">

        <div class="shortlinks-drawer__backdrop" id="shortlinks-drawer-backdrop"></div>

        <div class="shortlinks-drawer__panel">
            <div class="shortlinks-drawer__header">
                <h2 id="shortlinks-drawer-title"><?php esc_html_e( 'New Link', 'short-links' ); ?></h2>
                <button
                    id="shortlinks-drawer-close"
                    class="shortlinks-drawer__close"
                    aria-label="<?php esc_attr_e( 'Close drawer', 'short-links' ); ?>">
                    &#x2715;
                </button>
            </div>

            <form id="shortlinks-form" class="shortlinks-form" novalidate>
                <input type="hidden" id="form-link-id" name="id" value="">

                <div class="shortlinks-form__group">
                    <label for="form-title" class="shortlinks-form__label">
                        <?php esc_html_e( 'Title', 'short-links' ); ?>
                        <span class="shortlinks-form__hint"><?php esc_html_e( '(optional)', 'short-links' ); ?></span>
                    </label>
                    <input
                        type="text"
                        id="form-title"
                        name="title"
                        class="shortlinks-form__input"
                        placeholder="<?php esc_html_e( 'e.g. Black Friday Campaign', 'short-links' ); ?>">
                </div>

                <div class="shortlinks-form__group shortlinks-form__group--required">
                    <label for="form-target-url" class="shortlinks-form__label">
                        <?php esc_html_e( 'Destination URL', 'short-links' ); ?>
                    </label>
                    <div style="position: relative;">
                        <input
                            type="url"
                            id="form-target-url"
                            name="target_url"
                            class="shortlinks-form__input"
                            required
                            placeholder="https://example.com/your/long/link"
                            style="padding-right: 32px;">
                        <span id="target-url-validation" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); display: none;"></span>
                    </div>
                    <span class="shortlinks-form__error" id="error-target-url" role="alert"></span>
                </div>

                <div class="shortlinks-form__group shortlinks-form__group--required">
                    <label for="form-slug" class="shortlinks-form__label">
                        <?php esc_html_e( 'Short Slug', 'short-links' ); ?>
                    </label>
                    <div class="shortlinks-slug-row">
                        <span class="shortlinks-slug-row__prefix" id="slug-prefix-display"></span>
                        <input
                            type="text"
                            id="form-slug"
                            name="slug"
                            class="shortlinks-form__input shortlinks-slug-row__input"
                            required
                            pattern="[a-zA-Z0-9\-_]+"
                            placeholder="my-link"
                            autocomplete="off"
                            spellcheck="false">
                        <button type="button" id="shortlinks-auto-slug" class="shortlinks-icon-btn" title="Generate random slug" style="margin: 4px; border-left: 1px solid var(--ul-border); border-radius: 0; padding-left: 8px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        </button>
                    </div>
                    <span class="shortlinks-form__error" id="error-slug" role="alert"></span>
                </div>

                <div class="shortlinks-form__group">
                    <label style="display: block; font-weight: 600; margin-bottom: 8px; font-size: 14px;">
                        <?php esc_html_e( 'Geotargeting Rules', 'short-links' ); ?>
                        <span class="shortlinks-form__hint"><?php esc_html_e( '(optional)', 'short-links' ); ?></span>
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
                            <button type="button" id="drawer-add-geo-rule-btn" class="shortlinks-btn shortlinks-btn--ghost" style="font-size: 12px; padding: 4px 8px;">+ Add Rule</button>
                        </div>
                    </div>
                    <input type="hidden" id="form-geo-rules" name="geo_rules" value="">
                    <p style="font-size: 12px; color: var(--ul-text-muted); margin: 6px 0 0;">Users from matching countries will be redirected to the specific URL instead of the default.</p>
                </div>

                <div class="shortlinks-form__group">
                    <label for="form-redirect-type" class="shortlinks-form__label">
                        <?php esc_html_e( 'Redirect Type', 'short-links' ); ?>
                    </label>
                    <div class="shortlinks-toggle-group" role="group" aria-label="<?php esc_attr_e( 'Redirect type', 'short-links' ); ?>">
                        <label class="shortlinks-toggle-option">
                            <input type="radio" name="redirect_type" value="301">
                            <span>301 — <?php esc_html_e( 'Permanent', 'short-links' ); ?></span>
                        </label>
                        <label class="shortlinks-toggle-option">
                            <input type="radio" name="redirect_type" value="302">
                            <span>302 — <?php esc_html_e( 'Temporary', 'short-links' ); ?></span>
                        </label>
                        <label class="shortlinks-toggle-option">
                            <input type="radio" name="redirect_type" value="307" checked>
                            <span>307 — <?php esc_html_e( 'Temporary', 'short-links' ); ?></span>
                        </label>
                    </div>
                </div>

                <!-- Pro Features -->
                <div class="shortlinks-form__group" style="margin-top: 12px; padding: 16px; border: 1px solid var(--ul-primary); border-radius: var(--ul-radius); background: var(--ul-surface-alt);">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                        <span class="shortlinks-form__label" style="margin: 0; color: var(--ul-primary);">
                            <?php esc_html_e( 'Pro Features', 'short-links' ); ?>
                        </span>
                        <span style="background: var(--ul-primary); color: white; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 600; text-transform: uppercase;">Active</span>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="form-ab-target" class="shortlinks-form__label" style="font-size: 13px;">A/B Split Test Target URL</label>
                        <input type="url" id="form-ab-target" name="ab_target" class="shortlinks-form__input" placeholder="https://example.com/b-variant" style="font-size: 13px;">
                        <p style="font-size: 11px; color: var(--ul-text-muted); margin: 4px 0 0;">50% of traffic will be randomly sent to this URL instead of the main Destination URL.</p>
                    </div>

                    <div>
                        <label for="form-retargeting-pixels" class="shortlinks-form__label" style="font-size: 13px;">Retargeting Pixels (HTML/JS)</label>
                        <textarea id="form-retargeting-pixels" name="retargeting_pixels" class="shortlinks-form__input" placeholder="<script>...</script>" style="font-size: 13px; font-family: monospace; min-height: 80px;"></textarea>
                        <p style="font-size: 11px; color: var(--ul-text-muted); margin: 4px 0 0;">Add Meta, Google, or other tracking pixels here. This changes the redirect to a JS-based redirect.</p>
                    </div>
                </div>

                <div class="shortlinks-form__actions">
                    <button type="button" id="shortlinks-cancel-drawer" class="shortlinks-btn shortlinks-btn--ghost">
                        <?php esc_html_e( 'Cancel', 'short-links' ); ?>
                    </button>
                    <button type="submit" id="shortlinks-form-submit" class="shortlinks-btn shortlinks-btn--primary">
                        <span class="shortlinks-btn__label"><?php esc_html_e( 'Create Link', 'short-links' ); ?></span>
                        <span class="shortlinks-btn__spinner" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

</div><!-- #shortlinks-app -->

<?php
// ── JavaScript Config Injection ───────────────────────────────────────────────
// Pass PHP values to JS without inline data attributes.
// wp_create_nonce generates a nonce scoped to the REST API.
?>
<script id="shortlinks-config">
window.ShortLinksConfig = <?php
    echo wp_json_encode( [
        'restRoot'  => esc_url_raw( rest_url( 'shortlinks/v1' ) ),
        'nonce'     => wp_create_nonce( 'wp_rest' ),
        'homeUrl'   => esc_url_raw( home_url( '/' ) ),
        'perPage'   => 20,
        'i18n'      => [
            'confirmDelete'  => __( 'Delete this link? All click data will be lost.', 'short-links' ),
            'copied'         => __( 'Copied!', 'short-links' ),
            'copyFailed'     => __( 'Copy failed', 'short-links' ),
            'saved'          => __( 'Link saved.', 'short-links' ),
            'deleted'        => __( 'Link deleted.', 'short-links' ),
            'errorGeneric'   => __( 'Something went wrong. Please try again.', 'short-links' ),
            'slugTaken'      => __( 'This slug is already in use.', 'short-links' ),
            'invalidUrl'     => __( 'Please enter a valid URL.', 'short-links' ),
            'noLinks'        => __( 'No links yet. Create your first!', 'short-links' ),
            'editLink'       => __( 'Edit Link', 'short-links' ),
            'newLink'        => __( 'New Link', 'short-links' ),
            'updateLink'     => __( 'Update Link', 'short-links' ),
            'clicks'         => __( 'clicks', 'short-links' ),
        ],
    ] );
?>;
</script>
