<x-admin.admin-layout>
    {{-- CSRF para fetch --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="authors-page" data-list-url="{{ route('admin.authors.index') }}"
        data-store-url="{{ route('admin.authors.store') }}" data-show-url="{{ url('/admin/authors') }}"
        data-destroy-url="{{ url('/admin/authors') }}">
        {{-- COLUMNA IZQUIERDA: lista + filtros + paginación --}}
        <aside class="authors-left">

            <div id="authors-left-fragment">
                @include('admin.authors._list_and_pagination', ['records' => $records])
            </div>

            <div class="table-menu">


            </div>


        </aside>

        {{-- COLUMNA DERECHA: panel (form + meta) --}}
        <main class="authors-right">
            <section class="panel">
                <x-admin.author-form />
            </section>
        </main>
    </div>
</x-admin.admin-layout>

@push('scripts')
    <style>
        .authors-page {
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
