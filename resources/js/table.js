import { store } from "./redux/store";
import { updateForm } from "./redux/crud-slice";

const tableContainer = document.querySelector("#crudTable");
let table = null;

store.subscribe(() => {
    const currentState = store.getState();

    if (currentState.crud.table && currentState.crud.table !== table) {
        tableContainer.innerHTML = currentState.crud.table;
        table = currentState.crud.table;
    }
});

tableContainer.addEventListener("click", async event => {

    if (event.target.closest(".edit-tab")) {

        const editElement = event.target.closest(".edit-tab");
        const endpoint = editElement.dataset.endpoint;
        // const slug = editElement.dataset.slug;

        try {
            const res = await fetch(endpoint, {
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                credentials: "same-origin",
            });

            const data = await res.json()

            store.dispatch(updateForm(data.form));

        } catch (err) {
            console.error("CLICK HANDLER ERROR:", err);
        }
    }

    if (event.target.closest(".js-filter-reset")) {
        try {
            const form = event.target.closest(".js-filter-form");
            const res = await fetch(form.dataset.urlBase, { headers: { Accept: "application/json" } });
            if (!res.ok) throw new Error(`Refresh failed ${res.status}`);
            const data = await res.json();

            tableContainer.innerHTML = data.table;
        } catch (err) {
            console.error("CLICK HANDLER ERROR:", err);
        }
    }

    if (event.target.closest(".js-filter-submit")) {
        event.preventDefault();

        try {
            const form = event.target.closest(".js-filter-form");
            const formData = new FormData(form);
            const url = form.dataset.urlBase + "?" + new URLSearchParams(formData);

            const res = await fetch(url, { headers: { Accept: "application/json" } });
            if (!res.ok) throw new Error(`Refresh failed ${res.status}`);
            const data = await res.json();

            console.log(data);

            tableContainer.innerHTML = data.table;
        } catch (err) {
            console.error("CLICK HANDLER ERROR:", err);
        }
    }
});
