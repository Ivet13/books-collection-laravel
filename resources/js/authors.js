document.addEventListener("DOMContentLoaded", () => {
    // ✅ Root para evitar choques con books.js
    const root = document.querySelector(".authors-page");
    if (!root) return;

    console.log("authors.js activo");

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

    const listEl = root.querySelector(".js-list");
    const paginationEl = root.querySelector(".js-pagination");
    const filterForm = root.querySelector(".js-filter-form");
    const filterResetBtn = root.querySelector(".js-filter-reset");

    const form = root.querySelector(".js-author-form");
    if (!form || !listEl) return;

    const storeUrl = form.dataset.storeUrl;
    const showBase = form.dataset.showUrlBase;

    const deleteBtn = form.querySelector(".delete-btn");
    const resetBtn = form.querySelector(".js-author-reset");
    const errorsEl = form.querySelector(".js-errors");

    const authorIdEl = form.querySelector("#author_id");
    const methodEl = form.querySelector("#method");

    const nameEl = form.querySelector("#name");
    const bioEl = form.querySelector("#bio");

    const headersJson = () => ({
        "X-CSRF-TOKEN": csrf,
        "Accept": "application/json",
    });

    function setErrors(msgs = []) {
        if (!errorsEl) return;
        errorsEl.innerHTML = msgs.length
            ? `<ul>${msgs.map(m => `<li>${m}</li>`).join("")}</ul>`
            : "";
    }

    function setDeleteVisible(visible) {
        if (!deleteBtn) return;
        deleteBtn.style.display = visible ? "inline-block" : "none";
    }

    function clearSelected() {
        root.querySelectorAll(".edit-tab.selected").forEach(el => el.classList.remove("selected"));
    }

    function enterCreateMode() {
        form.action = storeUrl;
        authorIdEl.value = "";
        methodEl.value = "POST";
        nameEl.value = "";
        bioEl.value = "";
        setDeleteVisible(false);
        setErrors([]);
        clearSelected();
    }

    async function loadAuthor(id, tabEl) {
        setErrors([]);
        const url = `${showBase}/${id}`;

        const res = await fetch(url, { headers: headersJson() });
        if (!res.ok) {
            setErrors([`No se pudo cargar el autor (HTTP ${res.status}).`]);
            return;
        }

        const data = await res.json();

        authorIdEl.value = data.id;
        nameEl.value = data.name || "";
        bioEl.value = data.bio || "";

        form.action = url;
        methodEl.value = "PUT";
        setDeleteVisible(true);

        clearSelected();
        tabEl?.classList.add("selected");
    }

    async function saveAuthor() {
        setErrors([]);

        const fd = new FormData(form);
        fd.set("_method", methodEl.value);

        const res = await fetch(form.action, {
            method: "POST",
            headers: headersJson(),
            body: fd,
        });

        if (res.status === 422) {
            const json = await res.json().catch(() => null);
            const msgs = json?.errors ? Object.values(json.errors).flat() : ["Error de validación."];
            setErrors(msgs);
            return;
        }

        if (!res.ok) {
            setErrors([`Error guardando (HTTP ${res.status}).`]);
            return;
        }

        const json = await res.json();
        await refreshList();       // refresca lista/paginación
        await loadAuthor(json.id); // deja seleccionado el guardado
    }

    async function deleteAuthor() {
        const id = authorIdEl.value;
        if (!id) return;
        if (!confirm("¿Eliminar este autor?")) return;

        setErrors([]);

        const fd = new FormData();
        fd.set("_token", form.querySelector('input[name="_token"]')?.value || "");
        fd.set("_method", "DELETE");

        const url = `${showBase}/${id}`;

        const res = await fetch(url, {
            method: "POST",
            headers: headersJson(),
            body: fd,
        });

        if (!res.ok) {
            setErrors([`Error eliminando (HTTP ${res.status}).`]);
            return;
        }

        await refreshList();
        enterCreateMode();
    }

    async function refreshList(url = window.location.href) {
        const res = await fetch(url, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                "Accept": "text/html",
            },
        });

        if (!res.ok) {
            setErrors([`No se pudo refrescar la lista (HTTP ${res.status}).`]);
            return;
        }

        const html = await res.text();
        const tmp = document.createElement("div");
        tmp.innerHTML = html;

        const newList = tmp.querySelector(".js-list");
        const newPagination = tmp.querySelector(".js-pagination");

        if (newList) listEl.innerHTML = newList.innerHTML;
        if (newPagination && paginationEl) paginationEl.innerHTML = newPagination.innerHTML;

        bindListClicks();
        bindPaginationClicks();

        window.history.pushState({}, "", url);
    }

    function bindListClicks() {
        root.querySelectorAll(".edit-tab").forEach(tab => {
            tab.addEventListener("click", () => loadAuthor(tab.dataset.id, tab));
        });
    }

    function bindPaginationClicks() {
        if (!paginationEl) return;
        paginationEl.querySelectorAll("a").forEach(a => {
            a.addEventListener("click", (e) => {
                e.preventDefault();
                refreshList(a.href);
            });
        });
    }

    // Eventos
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        saveAuthor();
    });

    resetBtn?.addEventListener("click", enterCreateMode);
    deleteBtn?.addEventListener("click", deleteAuthor);

    filterForm?.addEventListener("submit", (e) => {
        e.preventDefault();
        const params = new URLSearchParams(new FormData(filterForm));
        const url = `${filterForm.action}?${params.toString()}`;
        refreshList(url);
    });

    filterResetBtn?.addEventListener("click", () => {
        filterForm.reset();
        refreshList(filterForm.action);
    });

    // Init
    bindListClicks();
    bindPaginationClicks();
    enterCreateMode();
});
