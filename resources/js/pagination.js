import { store } from './redux/store';
import { updateTable } from './redux/crud-slice';

const tableContainer = document.querySelector("#crudTable");

document.addEventListener("click", async event => {

    const tableContainer = event.target.closest("#crudTable");

    if (!tableContainer) return;

    if (event.target.closest('.table-pagination-page')) {

        const paginationButton = event.target.closest('.table-pagination-page')

        if (paginationButton.classList.contains('inactive')) {
            return
        }

        try {

            let endpoint = paginationButton.dataset.pagination

            let filterQuery = store.getState().crud.filterQuery

            if (filterQuery) {
                endpoint += '&' + filterQuery
            }

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