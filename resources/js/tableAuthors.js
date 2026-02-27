// resources/js/tableAuthors.js
function csrf() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? "";
}

function showDeleteButton(form, show) {
    const btn = form.querySelector(".delete-btn");
    if (!btn) return;
    btn.classList.toggle("hidden", !show);
}

export function initCrudTable() {
    document.addEventListener("click", async (e) => {
        const host = document.querySelector("#crudTable");
        if (!host || !host.contains(e.target)) return;

        // EDIT: cualquier elemento con data-id y data-action="edit" (o enlace)
        const editEl = e.target.closest('[data-action="edit"][data-id], [data-id]');
        if (!editEl) return;

        // Si clicas en la paginación, no queremos tratarlo como edit:
        const a = e.target.closest("a");
        if (a) return; // navegación/paginación la manejará tu navigation.js

        const id = editEl.getAttribute("data-id");
        if (!id) return;

        const formHost = document.querySelector("#crudForm");
        const form = formHost?.querySelector(".js-author-form");
        if (!form) return;

        const showBase = form.dataset.showUrlBase;
        if (!showBase) return;

        const url = `${showBase.replace(/\/$/, "")}/${id}`;

        const res = await fetch(url, {
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN": csrf(),
            },
            credentials: "same-origin",
        });

        if (!res.ok) return;

        const data = await res.json();

        // rellena form
        form.querySelector('input[name="id"]').value = String(id);
        form.querySelector('input[name="_method"]').value = "PUT";
        if (form.querySelector('[name="name"]')) form.querySelector('[name="name"]').value = data.name ?? "";
        if (form.querySelector('[name="bio"]')) form.querySelector('[name="bio"]').value = data.bio ?? "";

        showDeleteButton(form, true);

        // meta opcional
        const meta = form.querySelector("#meta-books");
        if (meta) meta.textContent = (data.books_count ?? data.books?.length ?? "—");
    });
}