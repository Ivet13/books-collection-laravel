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
    // si en el futuro pones mensajes por campo, los borras aquí
}

function showDeleteButton(form, show) {
    const btn = form.querySelector(".js-crud-delete");
    if (!btn) return;
    btn.classList.toggle("hidden", !show);
}

async function refreshCrud(url = location.href) {
    // recarga la URL actual; tu controller debe devolver {form, table} en JSON
    const res = await fetch(url, { headers: { Accept: "application/json" } });
    if (!res.ok) throw new Error(`Refresh failed ${res.status}`);
    const data = await res.json();
    document.querySelector("#crudForm").innerHTML = data.form ?? "";
    document.querySelector("#crudTable").innerHTML = data.table ?? "";
}

export function initCrudForm() {
    console.log('initCrudForm')
    // SUBMIT (crear/editar)
    document.addEventListener("submit", async (e) => {
        const host = document.querySelector("#crudForm");
        if (!host || !host.contains(e.target)) return;

        const form = e.target.closest("form.js-crud-form, form");
        if (!form || !form.classList.contains("js-crud-form")) return;

        e.preventDefault();
        clearErrors(form);

        const id = form.querySelector('input[name="id"]')?.value?.trim() || "";
        const storeUrl = form.dataset.storeUrl;
        const updateBase = form.dataset.updateUrlBase;

        if (!storeUrl) return setGeneralError(form, "Falta data-store-url");
        if (!updateBase) return setGeneralError(form, "Falta data-update-url-base");

        const url = id ? `${updateBase.replace(/\/$/, "")}/${id}` : storeUrl;
        console.log(url)
        const fd = new FormData(form);
        // Laravel: siempre POST con _method
        if (id) fd.set("_method", "PUT");
        else fd.set("_method", "POST");

        const res = await fetch(url, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": csrf(),
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
            body: fd,
        });

        if (res.status === 422) {
            const payload = await res.json().catch(() => ({}));
            // por ahora: mensaje general, luego lo refinamos por campos
            const first = payload?.message || "Validación incorrecta";
            setGeneralError(form, first);
            // opcional: marcar inputs si payload.errors existe
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
    });

    // RESET
    document.addEventListener("click", (e) => {
        const host = document.querySelector("#crudForm");
        if (!host || !host.contains(e.target)) return;

        const btn = e.target.closest(".js-crud-reset");
        if (!btn) return;

        const form = host.querySelector(".js-crud-form");
        if (!form) return;

        clearErrors(form);
        form.reset();
        form.querySelector('input[name="id"]')?.setAttribute("value", "");
        const method = form.querySelector('input[name="_method"]');
        if (method) method.value = "POST";
        showDeleteButton(form, false);

        // opcional: limpia meta
        const meta = form.querySelector("#meta-books");
        if (meta) meta.textContent = "—";
    });


    // DELETE
    document.addEventListener("click", async (e) => {
        const host = document.querySelector("#crudForm");
        if (!host || !host.contains(e.target)) return;

        const btn = e.target.closest(".js-crud-delete");
        if (!btn) return;

        const form = host.querySelector(".js-crud-form");
        if (!form) return;

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

        if (!res.ok) {
            setGeneralError(form, `Error borrando (${res.status})`);
            return;
        }

        await refreshCrud();
    });
}