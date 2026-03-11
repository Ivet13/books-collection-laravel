import { createSlice } from '@reduxjs/toolkit'

export const crudSlice = createSlice({
    name: 'crud',
    initialState: {
        form: null,
        table: null,
        filterQuery: null,
        deleteModal: {
            id: null,
            endpoint: null
        }
    },
    reducers: {
        updateForm: (state, action) => {
            state.form = action.payload
        },
        updateTable: (state, action) => {
            state.table = action.payload
        },
        showDeleteModal: (state, action) => {
            state.deleteModal = action.payload
        },
        setFilterQuery: (state, action) => {
            state.filterQuery = action.payload
        }
    }
})

export const {
    updateForm,
    updateTable,
    showDeleteModal,
    setFilterQuery
} = crudSlice.actions

export default crudSlice.reducer