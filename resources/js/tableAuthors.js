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
    console.log("initCrudTable: mounted");

    document.addEventListener("click", async (e) => {
        const host = document.querySelector("#crudTable");
        if (!host) return console.log("no #crudTable");
        if (!host.contains(e.target)) return; // normal: clics fuera

        console.log("click inside #crudTable", e.target);

        const editEl = e.target.closest(".edit-tab[data-id]");
        if (!editEl) return console.log("no .edit-tab[data-id] found");

        const a = e.target.closest("a");
        if (a) return console.log("clicked link, ignoring", a.href);

        const id = editEl.getAttribute("data-id");
        console.log("picked id", id);

        const form = document.querySelector("#crudForm .js-crud-form");
        if (!form) return console.log("no form found");

        const showBase = form.dataset.showUrlBase;
        if (!showBase) return console.log("no data-show-url-base on form");

        const url = `${showBase.replace(/\/$/, "")}/${id}`;
        console.log("fetching", url);

        try {
            const res = await fetch(url, {
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": csrf(),
                },
                credentials: "same-origin",
            });

            console.log("response", res.status, res.headers.get("content-type"));

            const text = await res.text(); // <- clave para ver si te devuelven HTML
            console.log("body preview", text.slice(0, 200));

            // Si esto es JSON, entonces parseamos:
            const data = JSON.parse(text);
            console.log("json", data);

            form.querySelector('input[name="id"]').value = String(id);
            form.querySelector('input[name="_method"]').value = "PUT";
            form.querySelector('[name="name"]').value = data.name ?? "";
            form.querySelector('[name="bio"]').value = data.bio ?? "";

        } catch (err) {
            console.error("CLICK HANDLER ERROR:", err);
        }
    });
}