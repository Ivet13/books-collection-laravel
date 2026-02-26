// resources/js/modules/crudPage.js
// Generic “admin CRUD page” helper for your pattern:
// - One page has: list container + pagination container + form (create/update) + optional delete.
// - Clicking items/pagination/filter updates only parts via fetch (HTML partials) and updates URL.
// - Loading an item fetches JSON and fills the form.
// - Saving uses FormData and handles 422 validation errors.
//
// Usage example (in app.js):
//   import { initCrudPage } from './modules/crudPage';
//   initCrudPage({ rootSelector: '.authors-page', ... });

console.log('crudPage.js loaded');

function defaultGetCsrfToken() {
    const el = document.querySelector('meta[name="csrf-token"]');
    return el ? el.getAttribute('content') : '';
}

function headersJson() {
    return {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    };
}

function headersHtml() {
    return {
        Accept: 'text/html',
        'X-Requested-With': 'XMLHttpRequest',
    };
}

function buildUrl(urlOrPath, baseUrl = window.location.origin) {
    try {
        return new URL(urlOrPath, baseUrl).toString();
    } catch {
        // fallback: best effort
        return String(urlOrPath);
    }
}

function setHtml(target, html) {
    if (!target) return;
    target.innerHTML = html;
}

function clearErrors(form) {
    if (!form) return;

    // Remove common patterns: .is-invalid, .invalid-feedback, [data-error-for]
    form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback, .error-message, [data-error-for]').forEach((el) => el.remove());

    const general = form.querySelector('[data-errors]');
    if (general) general.innerHTML = '';
}

function setErrors(form, errors) {
    if (!form || !errors) return;

    // Optional general error box
    const general = form.querySelector('[data-errors]');
    const generalMessages = [];

    Object.entries(errors).forEach(([field, messages]) => {
        const msg = Array.isArray(messages) ? messages[0] : String(messages);

        // Try common selectors
        const input =
            form.querySelector(`[name="${CSS.escape(field)}"]`) ||
            form.querySelector(`[name="${CSS.escape(field)}[]"]`) ||
            form.querySelector(`[data-field="${CSS.escape(field)}"]`);

        if (!input) {
            generalMessages.push(`${field}: ${msg}`);
            return;
        }

        input.classList.add('is-invalid');

        // Place error right after the input
        const div = document.createElement('div');
        div.className = 'invalid-feedback';
        div.textContent = msg;

        // If input is inside an input-group, append after group
        const inputGroup = input.closest('.input-group');
        if (inputGroup && inputGroup.parentElement) {
            inputGroup.parentElement.appendChild(div);
        } else if (input.parentElement) {
            input.parentElement.appendChild(div);
        } else {
            generalMessages.push(`${field}: ${msg}`);
        }
    });

    if (general && generalMessages.length) {
        general.innerHTML = generalMessages.map((m) => `<div>${escapeHtml(m)}</div>`).join('');
    }
}

function escapeHtml(s) {
    return String(s)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');
}

async function fetchText(url, opts = {}) {
    const res = await fetch(url, opts);
    const text = await res.text();
    if (!res.ok) {
        const err = new Error(`Request failed (${res.status})`);
        err.status = res.status;
        err.body = text;
        throw err;
    }
    return text;
}

async function fetchJson(url, opts = {}) {
    const res = await fetch(url, opts);
    const data = await res.json().catch(() => null);
    if (!res.ok) {
        const err = new Error(`Request failed (${res.status})`);
        err.status = res.status;
        err.body = data;
        throw err;
    }
    return data;
}




/**
 * @typedef CrudPageConfig
 * @property {string} rootSelector - Page root selector (e.g. ".authors-page")
 * @property {string} formSelector - Form selector (e.g. ".js-author-form")
 * @property {string} listSelector - Container selector for list HTML
 * @property {string} paginationSelector - Container selector for pagination HTML
 * @property {string} [filtersFormSelector] - Optional filters/search form selector
 * @property {string} [resetSelector] - Optional reset link/button selector
 *
 * @property {(root:Element)=>string} listUrl - Function returning URL for list refresh (usually from data attribute)
 * @property {(id:string, root:Element)=>string} showUrl - Function returning URL for item JSON (from data attribute)
 * @property {(root:Element)=>string} storeUrl - Function returning URL for POST/PUT (from data attribute)
 * @property {(id:string, root:Element)=>string} destroyUrl - Function returning URL for delete (from data attribute)
 * @property {(id:string, root:Element)=>string} updateUrl - Function returning URL for update (from data attribute)
 *
 * @property {string} [idFieldSelector] - Hidden input selector for id (default: 'input[name="id"]')
 * @property {string} [methodFieldSelector] - Hidden _method selector (default: 'input[name="_method"]')
 *
 * @property {(data:any, ctx:any)=>void} [onLoadToForm] - Hook to map JSON -> form inputs
 * @property {(form:HTMLFormElement, ctx:any)=>FormData} [buildFormData] - Hook to build FormData (default: new FormData(form))
 * @property {(ctx:any)=>void} [onAfterSave] - Hook after save success
 * @property {(ctx:any)=>void} [onAfterDelete] - Hook after delete success
 *
 * @property {string} [itemLinkSelector] - Selector inside list to open item (default: 'a[data-id], [data-action="edit"][data-id]')
 * @property {string} [deleteBtnSelector] - Selector inside list to delete (default: '[data-action="delete"][data-id]')
 * @property {string} [activeClass] - CSS class to mark selected item
 *
 * @property {boolean} [useHistory] - Enable pushState/popstate (default true)
 * @property {boolean} [includeQueryStringOnList] - Keep URL query string when refreshing list (default true)
 * @property {boolean} [debug] - Console log some actions
 */

/**
 * Initialize a CRUD page if its root exists.
 * @param {CrudPageConfig} config
 */
export function initCrudPage(config) {
    const root = document.querySelector(config.rootSelector);
    if (!root) return;

    const form = root.querySelector(config.formSelector);
    const listEl = root.querySelector(config.listSelector);
    const paginationEl = root.querySelector(config.paginationSelector);

    const idFieldSel = config.idFieldSelector || 'input[name="id"]';
    const methodFieldSel = config.methodFieldSelector || 'input[name="_method"]';

    const csrf = defaultGetCsrfToken();
    const useHistory = config.useHistory !== false;

    const ctx = {
        root,
        form,
        listEl,
        paginationEl,
        csrf,
        selectedId: null,
        config,
    };

    const log = (...args) => {
        if (config.debug) console.log('[crudPage]', ...args);
    };

    function setSelected(id) {
        ctx.selectedId = id;

        if (!config.activeClass) return;
        const cls = config.activeClass;

        root.querySelectorAll(`.${cls}`).forEach((el) => el.classList.remove(cls));
        if (!id) return;

        // Try find row/card with matching data-id
        const item = root.querySelector(`[data-id="${CSS.escape(String(id))}"]`);
        if (item) item.classList.add(cls);
    }

    function setFormModeCreate() {
        if (!form) return;

        const idField = form.querySelector(idFieldSel);
        const methodField = form.querySelector(methodFieldSel);

        if (idField) idField.value = '';
        if (methodField) methodField.value = 'POST';

        clearErrors(form);
        form.reset();
    }

    function setFormModeEdit(id) {
        if (!form) return;

        const idField = form.querySelector(idFieldSel);
        const methodField = form.querySelector(methodFieldSel);

        if (idField) idField.value = String(id);
        if (methodField) methodField.value = 'PUT';


        clearErrors(form);
    }

    async function refreshList(url, { push = false } = {}) {
        const finalUrl = buildUrl(url);

        log('refreshList', finalUrl);

        const html = await fetchText(finalUrl, {
            headers: headersHtml(),
            credentials: 'same-origin',
        });

        // If your endpoint returns only the list+pagination fragment:
        // - You can just set listEl.innerHTML = html
        // If it returns a page with two partials in known wrappers, you can parse.
        //
        // This generic version assumes the response contains *the same* list and pagination containers.
        // It will extract those nodes and swap them.
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');

        const newList = doc.querySelector(config.listSelector);
        const newPagination = doc.querySelector(config.paginationSelector);

        if (newList) setHtml(listEl, newList.innerHTML);
        else setHtml(listEl, html); // fallback

        if (newPagination) setHtml(paginationEl, newPagination.innerHTML);

        if (useHistory && push) {
            history.pushState({}, '', finalUrl);
        }
    }

    async function loadItem(id, { push = true } = {}) {
        console.log(id)
        if (!id) return;

        const url = buildUrl(config.showUrl(String(id), root));
        log('loadItem', id, url);

        const data = await fetchJson(url, {
            headers: { ...headersJson(), 'X-CSRF-TOKEN': csrf },
            credentials: 'same-origin',
        });

        setSelected(id);
        setFormModeEdit(id);
        document.getElementsByClassName('delete-btn')[0].classList.remove('hidden');

        if (config.onLoadToForm) {
            config.onLoadToForm(data, ctx);
        } else {
            // Default mapping: try set fields with same keys by name
            if (form && data && typeof data === 'object') {
                Object.entries(data).forEach(([k, v]) => {
                    const input = form.querySelector(`[name="${CSS.escape(k)}"]`);
                    if (!input) return;
                    if (input.type === 'checkbox') {
                        input.checked = Boolean(v);
                    } else {
                        input.value = v ?? '';
                    }
                });
            }
        }

        if (useHistory && push) {
            // Keep current list URL if provided by config, but add ?id=
            // If your UI uses a different URL shape, override outside.
            const u = new URL(window.location.href);
            u.searchParams.set('id', String(id));
            history.pushState({}, '', u.toString());
        }
    }

    async function saveFromForm(e) {
        e.preventDefault();
        if (!form) return;

        clearErrors(form);

        const idField = form.querySelector(idFieldSel);
        const id = idField?.value ? String(idField.value) : '';

        // build FormData (must include _method when updating)
        const fd = config.buildFormData ? config.buildFormData(form, ctx) : new FormData(form);

        // Ensure method override exists when editing
        if (id) {
            // If your form already has <input name="_method"> it will be included automatically.
            // This is a safety net in case it's missing.
            if (!fd.has('_method')) fd.append('_method', 'PUT');
        } else {
            // For create, ensure no stray _method
            if (fd.has('_method')) fd.set('_method', 'POST');
        }

        // Choose correct URL
        let actionUrl = buildUrl(config.storeUrl(root)); // create
        if (id) {
            if (typeof config.updateUrl === 'function') {
                actionUrl = buildUrl(config.updateUrl(id, root));
            } else {
                // fallback: RESTful /{id}
                actionUrl = buildUrl(`${config.storeUrl(root).replace(/\/$/, '')}/${id}`);
            }
        }


        // Ensure CSRF for POST
        const res = await fetch(actionUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, ...headersJson() },
            credentials: 'same-origin',
            body: fd,
        });

        if (res.status === 422) {
            const payload = await res.json().catch(() => ({}));
            setErrors(form, payload.errors || payload);
            return;
        }

        if (!res.ok) {
            const txt = await res.text().catch(() => '');
            throw new Error(`Save failed (${res.status}). ${txt}`);
        }

        // success: refresh list and optionally reset form or select item
        let payload = null;
        const ct = res.headers.get('content-type') || '';
        if (ct.includes('application/json')) payload = await res.json().catch(() => null);

        if (config.onAfterSave) config.onAfterSave({ ...ctx, payload });

        // refresh list keeping current querystring (filters)
        const listUrl = config.listUrl(root);
        await refreshList(listUrl, { push: false });
    }

    async function deleteItem(id) {
        if (!id) return;
        const ok = window.confirm('¿Seguro que quieres borrar este elemento?');
        if (!ok) return;

        const url = buildUrl(config.destroyUrl(String(id), root));
        log('deleteItem', id, url);

        const res = await fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, ...headersJson() },
            credentials: 'same-origin',
            body: new URLSearchParams({ _method: 'DELETE' }),
        });

        if (!res.ok) {
            const txt = await res.text().catch(() => '');
            throw new Error(`Delete failed (${res.status}). ${txt}`);
        }

        if (config.onAfterDelete) config.onAfterDelete(ctx);

        // If we deleted the selected, reset form
        if (ctx.selectedId && String(ctx.selectedId) === String(id)) {
            setSelected(null);
            setFormModeCreate();
            document.getElementsByClassName('delete-btn')[0].classList.add('hidden');
        }

        const listUrl = config.listUrl(root);
        await refreshList(listUrl, { push: false });
    }

    // Event delegation: list clicks (edit/delete) + pagination/filter nav
    root.addEventListener('click', async (e) => {
        const a = e.target.closest('a');
        const editEl =
            e.target.closest(config.itemLinkSelector || '[data-id], [data-action="edit"][data-id]');
        console.log(editEl)


        console.log(config.deleteBtnSelector)
        const delEl = e.target.closest(config.deleteBtnSelector || '[data-action="delete"][data-id]');
        console.log(delEl)

        // Delete buttons
        if (delEl) {
            e.preventDefault();
            // const id = delEl.getAttribute('data-id');
            const id = form.querySelector('[name="id"]').value;
            try {
                await deleteItem(id);
            } catch (err) {
                console.error(err);
            }
            return;
        }

        // Delete buttons
        if (delEl) {
            e.preventDefault();
            const id = delEl.getAttribute('data-id');
            try {
                await deleteItem(id);
            } catch (err) {
                console.error(err);
            }
            return;
        }

        // Edit click
        if (editEl) {
            console.log(editEl)
            e.preventDefault();
            const id = editEl.getAttribute('data-id');
            try {
                await loadItem(id);
            } catch (err) {
                console.error(err);
            }
            return;
        }

        // Pagination / normal links inside root: fetch partial list
        if (a && paginationEl && paginationEl.contains(a)) {
            e.preventDefault();
            const href = a.getAttribute('href');
            if (!href) return;

            try {
                await refreshList(href, { push: true });
            } catch (err) {
                console.error(err);
            }
            return;
        }

        // Optional reset link
        if (config.resetSelector) {
            const reset = e.target.closest(config.resetSelector);
            if (reset) {
                e.preventDefault();
                try {
                    await refreshList(config.listUrl(root), { push: true });
                    // also reset form mode
                    setSelected(null);
                    setFormModeCreate();
                } catch (err) {
                    console.error(err);
                }
            }
        }
    });

    // Filters form submit => refresh list with querystring
    if (config.filtersFormSelector) {
        const filtersForm = root.querySelector(config.filtersFormSelector);
        if (filtersForm) {
            filtersForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                const url = new URL(buildUrl(config.listUrl(root)));
                const fd = new FormData(filtersForm);
                fd.forEach((v, k) => {
                    if (v === null || String(v).trim() === '') return;
                    url.searchParams.set(k, String(v));
                });

                try {
                    await refreshList(url.toString(), { push: true });
                } catch (err) {
                    console.error(err);
                }
            });
        }
    }

    // Form submit
    if (form) {
        form.addEventListener('submit', async (e) => {
            try {
                await saveFromForm(e);
            } catch (err) {
                console.error(err);
            }
        });
    }

    // Back/forward button support
    if (useHistory) {
        window.addEventListener('popstate', async () => {
            try {
                // Reload list for current URL
                await refreshList(window.location.href, { push: false });

                // If URL has ?id=, load item
                const u = new URL(window.location.href);
                const id = u.searchParams.get('id');
                if (id) await loadItem(id, { push: false });
                else {
                    setSelected(null);
                    setFormModeCreate();
                }
            } catch (err) {
                console.error(err);
            }
        });
    }

    // Initial state: if URL has ?id= load item, else create mode
    (function init() {
        const u = new URL(window.location.href);
        const id = u.searchParams.get('id');
        if (id) {
            loadItem(id, { push: false }).catch((err) => console.error(err));
        } else {
            setFormModeCreate();
            document.getElementsByClassName('delete-btn')[0].classList.add('hidden');
        }
    })();
}