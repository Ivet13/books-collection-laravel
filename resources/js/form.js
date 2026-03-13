import { store } from './redux/store';
import { updateTable } from './redux/crud-slice';

const formContainer = document.querySelector('#crudForm');
let form = null;

store.subscribe(() => {
    const currentState = store.getState();

    if (currentState.crud.form && currentState.crud.form !== form) {
        formContainer.innerHTML = currentState.crud.form;
        form = currentState.crud.form;
    }
});

formContainer.addEventListener('click', async event => {

    // CREATE / UPDATE
    if (event.target.closest(".js-crud-save")) {

        const form = document.querySelector(".js-crud-form");
        clearErrors(form);

        const id = form.querySelector('#id')?.value?.trim() || "";
        const storeUrl = form.dataset.storeUrl;
        const updateBase = form.dataset.updateUrlBase;

        if (!storeUrl) return setGeneralError(form, "Falta data-store-url");
        if (!updateBase) return setGeneralError(form, "Falta data-update-url-base");

        const url = id ? `${updateBase.replace(/\/$/, "")}/${id}` : storeUrl;
        const fd = new FormData(form);

        const res = await fetch(url, {
            method: id ? "PUT" : "POST",
            headers: {
                "X-CSRF-TOKEN": csrf(),
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
            body: fd,
        });

        if (res.status === 422) {
            const payload = await res.json().catch(() => ({}));

            const first = payload?.message || "Validación incorrecta";
            setGeneralError(form, first);

            if (payload?.errors) {
                for (const [field] of Object.entries(payload.errors)) {
                    const input = form.querySelector(`[name="${CSS.escape(field)}"]`);
                    if (input) input.classList.add("is-invalid");
                }
            }
            return;
        }

        if (!res.ok) {
            setGeneralError(form, `Error guardando (${res.status})`);
            return;
        }

        await refreshCrud();
        showDeleteButton(form, true);


    }

    //RESET
    if (event.target.closest(".js-crud-reset")) {
        const form = document.querySelector(".js-crud-form");
        form.querySelectorAll("input, textarea, select").forEach(el => {
            el.value = "";
        });
        form.querySelector('input[name="id"]')?.setAttribute("value", "");
        const method = form.querySelector('input[name="_method"]');
        if (method) method.value = "POST";
        showDeleteButton(form, false);
    }

    //DELETE
    if (event.target.closest(".js-crud-delete")) {
        const form = document.querySelector(".js-crud-form");

        const id = form.querySelector('input[name="id"]')?.value?.trim();
        if (!id) return setGeneralError(form, "No hay ID para borrar");

        const destroyBase = form.dataset.destroyUrlBase;
        if (!destroyBase) return setGeneralError(form, "Falta data-destroy-url-base");

        if (!confirm("¿Seguro que quieres borrar este autor?")) return;

        const url = `${destroyBase.replace(/\/$/, "")}/${id}`;

        const res = await fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrf(),
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
            body: new URLSearchParams({ _method: "DELETE" }),
        });
        await refreshCrud();

        if (!res.ok) {
            setGeneralError(form, `Error borrando (${res.status})`);
            return;
        }
    }

    //VIEW
    if (event.target.closest(".js-crud-view")) {
        const form = document.querySelector(".js-crud-form");

        const slug = form.querySelector('input[name="slug"]')?.value?.trim();
        if (!slug) return setGeneralError(form, "Error: Falta el slug.");

        const viewBase = form.dataset.viewUrlBase;
        if (!viewBase) return setGeneralError(form, "Error: Falta data-view-url-base");

        const url = `${viewBase.replace(/\/$/, "")}/${slug}`;

        const res = await fetch(url, {
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": csrf(),
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
            },
        });
        await refreshCrud();

        if (!res.ok) {
            setGeneralError(form, `Error viendo (${res.status})`);
            return;
        }
    }
});

function csrf() {
    return document.querySelector('meta[name="csrf-token"]')?.content ?? "";
}

function setGeneralError(form, msg) {
    const box = form.querySelector(".js-errors");
    if (box) box.textContent = msg || "";
}

function clearErrors(form) {
    setGeneralError(form, "");
    form.querySelectorAll(".is-invalid").forEach((el) => el.classList.remove("is-invalid"));

}

function showDeleteButton(show) {
    const form = document.querySelector("#crudForm .js-crud-form");
    const btn = form?.querySelector(".js-crud-delete");
    if (!btn) return;
    btn.classList.toggle("hidden", !show);
}

async function refreshCrud(url = location.href) {

    const res = await fetch(url, { headers: { Accept: "application/json" } });
    if (!res.ok) throw new Error(`Refresh failed ${res.status}`);
    const data = await res.json();
    formContainer.innerHTML = data.form;
    store.dispatch(updateTable(data.table));
}
