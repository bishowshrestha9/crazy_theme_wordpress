/**
 * ShortLinks Admin SPA – Vanilla JS Fetch API Controller
 *
 * Architecture:
 *   - One ShortLinksApp singleton owns all state and DOM refs.
 *   - All server communication goes through ApiClient (thin Fetch wrapper).
 *   - Rendering is done by pure functions returning HTML strings; the grid
 *     is wiped and re-rendered on state changes (virtual-DOM-lite).
 *   - Inline editing uses PATCH /shortlinks/v1/links/{id} for zero-reload updates.
 *
 * @package ShortLinks
 */

/* global ShortLinksConfig */

(function () {
    'use strict';

    const C = window.ShortLinksConfig;

    // ── API Client ────────────────────────────────────────────────────────────

    const ApiClient = {
        /**
         * Perform a JSON REST request.
         *
         * @param {string} path   Relative to C.restRoot (e.g. '/links').
         * @param {string} method HTTP method.
         * @param {object|null} body Request body (JSON-serialised).
         * @returns {Promise<any>}
         */
        async request(path, method = 'GET', body = null) {
            const opts = {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': C.nonce,
                },
            };
            if (body !== null) {
                opts.body = JSON.stringify(body);
            }

            const res = await fetch(C.restRoot + path, opts);
            const json = await res.json().catch(() => ({}));

            if (!res.ok) {
                const msg = json.message || C.i18n.errorGeneric;
                // Attach the REST error code for callers to inspect.
                const err = new Error(msg);
                err.code = json.code || 'unknown';
                err.status = res.status;
                throw err;
            }

            // Expose pagination headers on the response object.
            json._total      = parseInt(res.headers.get('X-ShortLinks-Total') || '0', 10);
            json._totalPages = parseInt(res.headers.get('X-ShortLinks-TotalPages') || '1', 10);

            return json;
        },

        getLinks:  (page, perPage) => ApiClient.request(`/links?page=${page}&per_page=${perPage}`),
        getLink:   (id)            => ApiClient.request(`/links/${id}`),
        createLink:(data)          => ApiClient.request('/links', 'POST', data),
        updateLink:(id, data)      => ApiClient.request(`/links/${id}`, 'PUT', data),
        patchLink: (id, data)      => ApiClient.request(`/links/${id}`, 'PATCH', data),
        deleteLink:(id)            => ApiClient.request(`/links/${id}`, 'DELETE'),
    };

    // ── State ─────────────────────────────────────────────────────────────────

    const State = {
        links:       [],
        page:        1,
        totalPages:  1,
        total:       0,
        loading:     true,
        filterQuery: '',
        editingId:   null,  // link id being edited in drawer, or null for new
    };

    // ── DOM References ────────────────────────────────────────────────────────

    const DOM = {
        grid:          () => document.getElementById('shortlinks-grid'),
        pagination:    () => document.getElementById('shortlinks-pagination'),
        notice:        () => document.getElementById('shortlinks-notice'),
        countLabel:    () => document.getElementById('shortlinks-count-label'),
        summaryTotal:  () => document.getElementById('summary-total-links'),
        summaryClicks: () => document.getElementById('summary-total-clicks'),
        summaryTop:    () => document.getElementById('summary-top-link'),
        drawer:        () => document.getElementById('shortlinks-drawer'),
        drawerTitle:   () => document.getElementById('shortlinks-drawer-title'),
        drawerBackdrop:() => document.getElementById('shortlinks-drawer-backdrop'),
        form:          () => document.getElementById('shortlinks-form'),
        formId:        () => document.getElementById('form-link-id'),
        formTitle:     () => document.getElementById('form-title'),
        formTargetUrl: () => document.getElementById('form-target-url'),
        formSlug:      () => document.getElementById('form-slug'),
        formSubmitBtn: () => document.getElementById('shortlinks-form-submit'),
        formSubmitLbl: () => document.querySelector('#shortlinks-form-submit .shortlinks-btn__label'),
        slugPrefix:    () => document.getElementById('slug-prefix-display'),
        searchInput:   () => document.getElementById('shortlinks-search'),
    };

    // ── Notice System ─────────────────────────────────────────────────────────

    let noticeTimer = null;

    function showNotice(message, type = 'success') {
        const el = DOM.notice();
        el.textContent = message;
        el.className   = `shortlinks-notice shortlinks-notice--${type}`;
        clearTimeout(noticeTimer);
        noticeTimer = setTimeout(() => {
            el.className = 'shortlinks-notice shortlinks-notice--hidden';
        }, 4000);
    }

    // ── Rendering ─────────────────────────────────────────────────────────────

    /**
     * Render a single link row HTML string.
     *
     * @param {object} link
     * @returns {string}
     */
    function renderRow(link) {
        const shortUrl    = `${C.homeUrl}${link.slug}`;
        const clicks      = parseInt(link.total_clicks, 10) || 0;
        const typeBadge   = `<span class="shortlinks-type-badge shortlinks-type-${link.redirect_type}">${link.redirect_type}</span>`;
        const truncTarget = link.target_url.length > 60
            ? link.target_url.substring(0, 57) + '…'
            : link.target_url;

        return `
<tr class="shortlinks-card" data-id="${link.id}">
    <td class="column-title">
        <strong class="shortlinks-card__title" title="${escHtml(link.title || link.slug)}">
            ${escHtml(link.title || '—')}
        </strong>
        <div class="shortlinks-card__slug-row">
            <a href="${escHtml(shortUrl)}" class="shortlinks-card__short-url" target="_blank" rel="noopener noreferrer">
                ${escHtml(shortUrl)}
            </a>
            <button
                class="shortlinks-icon-btn shortlinks-copy-btn"
                data-url="${escHtml(shortUrl)}"
                title="Copy short URL"
                aria-label="Copy short URL to clipboard">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            </button>
        </div>
    </td>

    <td class="column-target">
        <div class="shortlinks-card__target-row">
            <span class="shortlinks-card__target-display" title="${escHtml(link.target_url)}">
                ${escHtml(truncTarget)}
            </span>
            <div class="shortlinks-inline-edit" style="display:none;">
                <input
                    type="url"
                    class="shortlinks-inline-input"
                    value="${escHtml(link.target_url)}"
                    data-original="${escHtml(link.target_url)}"
                    aria-label="Destination URL">
                <button class="shortlinks-icon-btn shortlinks-inline-save" title="Save URL" aria-label="Save URL">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg>
                </button>
                <button class="shortlinks-icon-btn shortlinks-inline-cancel" title="Cancel" aria-label="Cancel edit">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
            <button
                class="shortlinks-icon-btn shortlinks-edit-target-btn"
                title="Edit destination URL"
                aria-label="Edit destination URL inline">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
        </div>
    </td>

    <td class="column-type">
        ${typeBadge}
    </td>

    <td class="column-clicks num">
        <strong>${clicks.toLocaleString()}</strong>
    </td>

    <td class="column-actions">
        <div class="shortlinks-card__actions">
            <button
                class="shortlinks-btn shortlinks-btn--sm shortlinks-btn--ghost shortlinks-edit-btn"
                data-id="${link.id}"
                aria-label="Edit ${escHtml(link.title || link.slug)}">
                Edit
            </button>
            <button
                class="shortlinks-btn shortlinks-btn--sm shortlinks-btn--danger shortlinks-delete-btn"
                data-id="${link.id}"
                aria-label="Delete ${escHtml(link.title || link.slug)}">
                Delete
            </button>
        </div>
    </td>
</tr>`;
    }

    /**
     * Re-render the full grid from current State.links.
     */
    function renderGrid() {
        const grid = DOM.grid();
        const filtered = State.filterQuery
            ? State.links.filter(l =>
                l.slug.includes(State.filterQuery) ||
                l.title.toLowerCase().includes(State.filterQuery) ||
                l.target_url.toLowerCase().includes(State.filterQuery)
              )
            : State.links;



        DOM.countLabel().textContent = `${filtered.length} / ${State.total}`;

        if (filtered.length === 0) {
            grid.innerHTML = `
                <tr><td colspan="5">
                    <div class="shortlinks-empty-state">
                        <span class="shortlinks-empty-icon" aria-hidden="true">&#x1F517;</span>
                        <p>${State.filterQuery ? 'No links match your filter.' : C.i18n.noLinks}</p>
                        ${!State.filterQuery ? `<button class="shortlinks-btn shortlinks-btn--primary" id="empty-create-btn">+ ${C.i18n.newLink}</button>` : ''}
                    </div>
                </td></tr>`;
            document.getElementById('empty-create-btn')?.addEventListener('click', openCreateDrawer);
            return;
        }

        grid.setAttribute('aria-busy', 'false');
        grid.innerHTML = filtered.map(renderRow).join('');

        bindCardEvents(grid);
    }

    /**
     * Render summary cards.
     */
    function renderSummary() {
        const totalClicks = State.links.reduce((sum, l) => sum + (parseInt(l.total_clicks, 10) || 0), 0);
        const top = State.links.reduce((best, l) =>
            (parseInt(l.total_clicks, 10) || 0) > (parseInt(best.total_clicks, 10) || 0) ? l : best,
            State.links[0] || {}
        );

        DOM.summaryTotal().textContent  = State.total.toLocaleString();
        DOM.summaryClicks().textContent = totalClicks.toLocaleString();
        DOM.summaryTop().textContent    = top?.slug || '—';

        document.querySelectorAll('.shortlinks-stat-card').forEach(el => el.classList.remove('shortlinks-skeleton'));
    }

    /**
     * Render pagination controls.
     */
    function renderPagination() {
        const el = DOM.pagination();
        if (State.totalPages <= 1) { el.innerHTML = ''; return; }

        let html = '';
        for (let i = 1; i <= State.totalPages; i++) {
            const active = i === State.page ? ' shortlinks-pagination__btn--active' : '';
            html += `<button class="shortlinks-pagination__btn${active}" data-page="${i}" aria-label="Page ${i}" ${i === State.page ? 'aria-current="page"' : ''}>${i}</button>`;
        }
        el.innerHTML = html;

        el.querySelectorAll('[data-page]').forEach(btn => {
            btn.addEventListener('click', () => {
                State.page = parseInt(btn.dataset.page, 10);
                loadLinks();
            });
        });
    }

    // ── Data Loading ──────────────────────────────────────────────────────────

    async function loadLinks() {
        DOM.grid().setAttribute('aria-busy', 'true');
        try {
            const data = await ApiClient.getLinks(State.page, C.perPage);
            State.links      = Array.isArray(data) ? data : [];
            State.total      = data._total      ?? State.links.length;
            State.totalPages = data._totalPages  ?? 1;
            State.loading    = false;
            renderGrid();
            renderSummary();
            renderPagination();
        } catch (err) {
            showNotice(err.message || C.i18n.errorGeneric, 'error');
        }
    }

    // ── Card Event Bindings ───────────────────────────────────────────────────

    function bindCardEvents(grid) {
        // Copy to clipboard.
        grid.querySelectorAll('.shortlinks-copy-btn').forEach(btn => {
            btn.addEventListener('click', async () => {
                const url = btn.dataset.url;
                try {
                    await navigator.clipboard.writeText(url);
                    btn.classList.add('shortlinks-copy-btn--success');
                    showNotice(C.i18n.copied);
                    setTimeout(() => btn.classList.remove('shortlinks-copy-btn--success'), 1500);
                } catch {
                    showNotice(C.i18n.copyFailed, 'error');
                }
            });
        });

        // Inline target URL editor.
        grid.querySelectorAll('.shortlinks-edit-target-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const card    = btn.closest('.shortlinks-card');
                const display = card.querySelector('.shortlinks-card__target-display');
                const editor  = card.querySelector('.shortlinks-inline-edit');
                display.style.display = 'none';
                btn.style.display     = 'none';
                editor.style.display  = 'flex';
                editor.querySelector('.shortlinks-inline-input').focus();
            });
        });

        grid.querySelectorAll('.shortlinks-inline-cancel').forEach(btn => {
            btn.addEventListener('click', () => {
                const card    = btn.closest('.shortlinks-card');
                const input   = card.querySelector('.shortlinks-inline-input');
                input.value   = input.dataset.original;
                cancelInlineEdit(card);
            });
        });

        grid.querySelectorAll('.shortlinks-inline-save').forEach(btn => {
            btn.addEventListener('click', () => saveInlineUrl(btn.closest('.shortlinks-card')));
        });

        grid.querySelectorAll('.shortlinks-inline-input').forEach(input => {
            input.addEventListener('keydown', e => {
                if (e.key === 'Enter')  saveInlineUrl(input.closest('.shortlinks-card'));
                if (e.key === 'Escape') {
                    input.value = input.dataset.original;
                    cancelInlineEdit(input.closest('.shortlinks-card'));
                }
            });
        });

        // Full edit (opens drawer).
        grid.querySelectorAll('.shortlinks-edit-btn').forEach(btn => {
            btn.addEventListener('click', () => openEditDrawer(parseInt(btn.dataset.id, 10)));
        });

        // Delete.
        grid.querySelectorAll('.shortlinks-delete-btn').forEach(btn => {
            btn.addEventListener('click', () => deleteLink(parseInt(btn.dataset.id, 10)));
        });
    }

    function cancelInlineEdit(card) {
        card.querySelector('.shortlinks-card__target-display').style.display = '';
        card.querySelector('.shortlinks-edit-target-btn').style.display      = '';
        card.querySelector('.shortlinks-inline-edit').style.display           = 'none';
    }

    /**
     * PATCH the target_url for a single card without a full page reload.
     *
     * @param {HTMLElement} card
     */
    async function saveInlineUrl(card) {
        const id    = parseInt(card.dataset.id, 10);
        const input = card.querySelector('.shortlinks-inline-input');
        const newUrl = input.value.trim();

        if (!newUrl || !isValidUrl(newUrl)) {
            input.classList.add('shortlinks-form__input--invalid');
            showNotice(C.i18n.invalidUrl, 'error');
            return;
        }

        input.classList.remove('shortlinks-form__input--invalid');
        const saveBtn = card.querySelector('.shortlinks-inline-save');
        saveBtn.disabled = true;

        try {
            const updated = await ApiClient.patchLink(id, { target_url: newUrl });

            // Update in-memory state so a re-render would be correct.
            const idx = State.links.findIndex(l => l.id === id);
            if (idx > -1) State.links[idx] = updated;

            // Update the DOM display without a full grid re-render.
            const display = card.querySelector('.shortlinks-card__target-display');
            const truncated = newUrl.length > 60 ? newUrl.substring(0, 57) + '…' : newUrl;
            display.textContent = truncated;
            display.title       = newUrl;
            input.dataset.original = newUrl;

            cancelInlineEdit(card);
            showNotice(C.i18n.saved);
        } catch (err) {
            showNotice(err.message || C.i18n.errorGeneric, 'error');
        } finally {
            saveBtn.disabled = false;
        }
    }

    // ── Drawer (Create / Edit) ────────────────────────────────────────────────

    function openCreateDrawer() {
        State.editingId = null;
        DOM.drawerTitle().textContent = C.i18n.newLink;
        DOM.formSubmitLbl().textContent = C.i18n.newLink;
        DOM.form().reset();
        DOM.formId().value = '';
        DOM.slugPrefix().textContent = C.homeUrl;
        
        // Default to 307 for new links
        const defaultRadio = DOM.form().querySelector(`input[name="redirect_type"][value="307"]`);
        if (defaultRadio) defaultRadio.checked = true;
        
        openDrawer();
    }

    async function openEditDrawer(id) {
        State.editingId = id;
        DOM.drawerTitle().textContent   = C.i18n.editLink;
        DOM.formSubmitLbl().textContent = C.i18n.updateLink;
        DOM.slugPrefix().textContent    = C.homeUrl;

        // Pre-populate from local state (instant) then verify with server.
        const local = State.links.find(l => l.id === id);
        if (local) populateForm(local);
        openDrawer();

        try {
            const fresh = await ApiClient.getLink(id);
            populateForm(fresh);
        } catch {
            // Already shown from cache; silently ignore.
        }
    }

    function populateForm(link) {
        DOM.formId().value        = link.id;
        DOM.formTitle().value     = link.title || '';
        DOM.formTargetUrl().value = link.target_url;
        DOM.formSlug().value      = link.slug;
        const radioVal = link.redirect_type || '307';
        DOM.form().querySelector(`input[name="redirect_type"][value="${radioVal}"]`).checked = true;

        // Pro fields
        document.getElementById('form-ab-target').value = link.ab_target || '';
        document.getElementById('form-geo-rules').value = link.geo_rules || '';
        document.getElementById('form-retargeting-pixels').value = link.retargeting_pixels || '';
        
        if (typeof renderDrawerGeoRules === 'function') {
            renderDrawerGeoRules();
        }
    }

    function openDrawer() {
        const drawer = DOM.drawer();
        drawer.classList.replace('shortlinks-drawer--closed', 'shortlinks-drawer--open');
        drawer.setAttribute('aria-hidden', 'false');
        DOM.formTargetUrl().focus();
        document.body.classList.add('shortlinks-drawer-active');
    }

    function closeDrawer() {
        const drawer = DOM.drawer();
        drawer.classList.replace('shortlinks-drawer--open', 'shortlinks-drawer--closed');
        drawer.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('shortlinks-drawer-active');
        clearFormErrors();
    }

    // ── Form Submission ───────────────────────────────────────────────────────

    async function handleFormSubmit(e) {
        e.preventDefault();
        clearFormErrors();

        const id          = parseInt(DOM.formId().value || '0', 10);
        const target_url  = DOM.formTargetUrl().value.trim();
        const slug        = DOM.formSlug().value.trim();
        const title       = DOM.formTitle().value.trim();
        const redirect_type = DOM.form().querySelector('input[name="redirect_type"]:checked')?.value || '307';
        
        // Pro fields
        const ab_target = document.getElementById('form-ab-target')?.value.trim() || '';
        const geo_rules = document.getElementById('form-geo-rules')?.value.trim() || '';
        const retargeting_pixels = document.getElementById('form-retargeting-pixels')?.value.trim() || '';
        
        const payload = { slug, target_url, title, redirect_type, ab_target, geo_rules, retargeting_pixels };

        // Client-side validation.
        let valid = true;
        if (!target_url || !isValidUrl(target_url)) {
            showFieldError('error-target-url', C.i18n.invalidUrl);
            valid = false;
        }
        if (!slug) {
            showFieldError('error-slug', 'Slug is required.');
            valid = false;
        }
        if (!valid) return;

        setSubmitting(true);

        try {
            let result;
            if (id > 0) {
                result = await ApiClient.updateLink(id, payload);
                // Patch local state.
                const idx = State.links.findIndex(l => l.id === id);
                if (idx > -1) State.links[idx] = result;
            } else {
                result = await ApiClient.createLink(payload);
                State.links.unshift(result);
                State.total++;
            }

            closeDrawer();
            renderGrid();
            renderSummary();
            showNotice(C.i18n.saved);
        } catch (err) {
            if (err.code === 'shortlinks_slug_taken') {
                showFieldError('error-slug', C.i18n.slugTaken);
            } else {
                showNotice(err.message || C.i18n.errorGeneric, 'error');
            }
        } finally {
            setSubmitting(false);
        }
    }

    async function deleteLink(id) {
        if (!window.confirm(C.i18n.confirmDelete)) return;

        try {
            await ApiClient.deleteLink(id);
            State.links  = State.links.filter(l => l.id !== id);
            State.total  = Math.max(0, State.total - 1);
            renderGrid();
            renderSummary();
            showNotice(C.i18n.deleted);
        } catch (err) {
            showNotice(err.message || C.i18n.errorGeneric, 'error');
        }
    }

    // ── Utility ───────────────────────────────────────────────────────────────

    function isValidUrl(str) {
        try { new URL(str); return true; } catch { return false; }
    }

    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function showFieldError(id, message) {
        const el = document.getElementById(id);
        if (el) { el.textContent = message; el.style.display = 'block'; }
    }

    function clearFormErrors() {
        document.querySelectorAll('.shortlinks-form__error').forEach(el => {
            el.textContent = ''; el.style.display = 'none';
        });
    }

    function setSubmitting(active) {
        const btn = DOM.formSubmitBtn();
        btn.disabled = active;
        btn.classList.toggle('shortlinks-btn--loading', active);
    }

    // ── Drawer Geo Rules UI ───────────────────────────────────────────────────

    function initDrawerGeoRules() {
        const tbody = document.getElementById('drawer-geo-rules-body');
        const addBtn = document.getElementById('drawer-add-geo-rule-btn');
        if (!tbody || !addBtn) return;

        addBtn.addEventListener('click', () => {
            addDrawerGeoRuleRow('', '');
        });

        // Expose render globally for populateForm
        window.renderDrawerGeoRules = function() {
            tbody.innerHTML = '';
            const hiddenInput = document.getElementById('form-geo-rules');
            let rules = {};
            try { rules = JSON.parse(hiddenInput.value || '{}'); } catch(e) {}
            
            for (const [code, url] of Object.entries(rules)) {
                addDrawerGeoRuleRow(code, url);
            }
            if (Object.keys(rules).length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" style="padding: 16px; text-align: center; color: var(--ul-text-muted); font-size: 13px;">No rules active.</td></tr>';
            }
        };

        function addDrawerGeoRuleRow(code, url) {
            if (tbody.querySelector('td[colspan]')) tbody.innerHTML = '';
            
            const tr = document.createElement('tr');
            tr.style.borderBottom = '1px solid var(--ul-border)';
            tr.innerHTML = `
                <td style="padding: 6px 8px;">
                    <input type="text" class="geo-code-input shortlinks-form__input" value="${code}" placeholder="US" style="width: 100%; padding: 4px 6px; text-transform: uppercase;" maxlength="2">
                </td>
                <td style="padding: 6px 8px;">
                    <input type="url" class="geo-url-input shortlinks-form__input" value="${url}" placeholder="https://..." style="width: 100%; padding: 4px 6px;">
                </td>
                <td style="padding: 6px 8px; text-align: right;">
                    <button type="button" class="remove-geo-rule shortlinks-icon-btn" style="color: var(--ul-danger);" title="Remove">&times;</button>
                </td>
            `;
            tbody.appendChild(tr);

            tr.querySelector('.remove-geo-rule').addEventListener('click', function() {
                tr.remove();
                syncDrawerGeoRules();
            });

            tr.querySelectorAll('input').forEach(inp => {
                inp.addEventListener('input', syncDrawerGeoRules);
            });
            syncDrawerGeoRules();
        }

        function syncDrawerGeoRules() {
            const newRules = {};
            tbody.querySelectorAll('tr').forEach(row => {
                const codeInput = row.querySelector('.geo-code-input');
                if (!codeInput) return;
                const code = codeInput.value.trim().toUpperCase();
                const url = row.querySelector('.geo-url-input').value.trim();
                if (code && url) newRules[code] = url;
            });
            const hiddenInput = document.getElementById('form-geo-rules');
            hiddenInput.value = Object.keys(newRules).length > 0 ? JSON.stringify(newRules) : '';
        }
    }

    // ── Initialization ────────────────────────────────────────────────────────

    function init() {
        initDrawerGeoRules();
        
        // Slug prefix display.
        if (DOM.slugPrefix()) DOM.slugPrefix().textContent = C.homeUrl;

        // Drawer open/close.
        document.getElementById('shortlinks-open-create')?.addEventListener('click', openCreateDrawer);
        document.getElementById('shortlinks-drawer-close')?.addEventListener('click', closeDrawer);
        document.getElementById('shortlinks-cancel-drawer')?.addEventListener('click', closeDrawer);
        DOM.drawerBackdrop()?.addEventListener('click', closeDrawer);

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeDrawer();
        });

        // Form submit.
        DOM.form()?.addEventListener('submit', handleFormSubmit);

        // Live search filter (client-side — no extra request).
        let searchTimer;
        DOM.searchInput()?.addEventListener('input', e => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                State.filterQuery = e.target.value.trim().toLowerCase();
                renderGrid();
            }, 200);
        });

        // Load first page.
        loadLinks();

        // ── Auto-slug Generator ──
        const autoSlugBtn = document.getElementById('shortlinks-auto-slug');
        if (autoSlugBtn) {
            autoSlugBtn.addEventListener('click', () => {
                const chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let slug = '';
                for (let i = 0; i < 6; i++) {
                    slug += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                const slugInput = document.getElementById('form-slug');
                if (slugInput) slugInput.value = slug;
            });
        }

        // ── Real-time URL Validation ──
        const urlInput = document.getElementById('form-target-url');
        const validationIcon = document.getElementById('target-url-validation');
        if (urlInput && validationIcon) {
            urlInput.addEventListener('input', () => {
                const val = urlInput.value.trim();
                validationIcon.style.display = 'block';
                if (!val) {
                    validationIcon.style.display = 'none';
                } else if (/^https?:\/\/[^\s$.?#].[^\s]*$/i.test(val)) {
                    validationIcon.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>';
                } else {
                    validationIcon.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>';
                }
            });
        }
        // ── Revert Backup Modal Logic ──
        const revertBtns = document.querySelectorAll('.shortlinks-revert-btn');
        const revertModal = document.getElementById('shortlinks-revert-modal');
        if (revertBtns.length && revertModal) {
            const cancelBtn = document.getElementById('revert-cancel');
            const targetFileEl = document.getElementById('revert-target-file');
            const backupFileInput = document.getElementById('revert-backup-file');
            const confirmInput = document.getElementById('revert-confirm-input');
            const submitBtn = document.getElementById('revert-submit');

            revertBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const filename = btn.getAttribute('data-filename');
                    targetFileEl.textContent = filename;
                    backupFileInput.value = filename;
                    revertModal.style.display = 'flex';
                });
            });

            cancelBtn.addEventListener('click', () => {
                revertModal.style.display = 'none';
                confirmInput.value = '';
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.5';
                submitBtn.style.cursor = 'not-allowed';
            });

            confirmInput.addEventListener('input', (e) => {
                if (e.target.value === 'REVERT') {
                    submitBtn.disabled = false;
                    submitBtn.style.opacity = '1';
                    submitBtn.style.cursor = 'pointer';
                } else {
                    submitBtn.disabled = true;
                    submitBtn.style.opacity = '0.5';
                    submitBtn.style.cursor = 'not-allowed';
                }
            });
        }
    }

    // Run after DOM ready.
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

}());
