<x-admin.admin-layout>
    {{-- CSRF para fetch --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="customers-page">
        {{-- COLUMNA IZQUIERDA: lista + filtros + paginación --}}
        <aside class="customers-left">

            <div id="customers-left-fragment">
                @include('admin.customers._list_and_pagination', ['records' => $records])
            </div>

            <div class="table-menu">
                {{-- FILTROS --}}
                <x-admin.customer-filter />

            </div>


        </aside>

        {{-- COLUMNA DERECHA: panel (form + meta) --}}
        <main class="customers-right">
            <section class="panel">
                <x-admin.customer-form />
            </section>
        </main>
    </div>
</x-admin.admin-layout>

@push('scripts')
    <style>
        .customers-page {
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 16px;
        }

        .edit-tab {
            cursor: pointer;
        }

        .edit-tab.selected {
            outline: 2px solid blue;
        }
    </style>
@endpush
