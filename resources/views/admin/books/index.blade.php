<x-app-layout>
    {{-- CSRF para fetch --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="books-page">
        {{-- COLUMNA IZQUIERDA: lista + filtros + paginación --}}
        <aside class="books-left">

            <div id="books-left-fragment">
                @include('admin.books._list_and_pagination', ['records' => $records])
            </div>

            <div class="table-menu">
                {{-- FILTROS --}}
                <x-filter :authors="$authors" :genres="$genres" :publishers="$publishers" />

            </div>


        </aside>

        {{-- COLUMNA DERECHA: panel (form + meta) --}}
        <main class="books-right">
            <section class="panel">
                <x-book-form />
            </section>
        </main>
    </div>
</x-app-layout>

@push('scripts')
    <style>
        .books-page {
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
