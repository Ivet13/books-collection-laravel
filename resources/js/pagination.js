import { store } from './redux/store';
import { updateTable } from './redux/crud-slice';

const tableContainer = document.querySelector("#crudTable");

store.subscribe(() => {
    const currentState = store.getState();

    if (currentState.crud.form) {
        tableContainer.innerHTML = currentState.crud.form;
    }
});

tableContainer.addEventListener("click", async event => {

    if (event.target.closest('.table-pagination-page')) {

        const paginationButton = event.target.closest('.table-pagination-page')

        if (paginationButton.classList.contains('inactive')) {
            return
        }

        try {

            let endpoint = paginationButton.dataset.pagination
            console.log(endpoint)
            const response = await fetch(endpoint, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
                method: 'GET',
            })

            if (response.status === 500) {
                throw response
            }

            const json = await response.json()
            store.dispatch(updateTable(json.table));

        } catch (err) {

            console.error("CLICK HANDLER ERROR:", err);
        }
    }

});