import { createSlice } from '@reduxjs/toolkit'

export const crudSlice = createSlice({
    name: 'crud',
    initialState: {
        form: null,
        table: null,
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
        }
    }
})

export const {
    updateForm,
    updateTable,
    showDeleteModal
} = crudSlice.actions

export default crudSlice.reducer