<x-app-layout>
    {{-- CSRF para fetch --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="books-page">
        {{-- COLUMNA IZQUIERDA: lista + filtros + paginación --}}
        <aside class="books-left">

            <div class="table-menu">
                {{-- FILTROS --}}
                <x-filter :authors="$authors" :genres="$genres" :publishers="$publishers" />

                {{-- PAGINACIÓN --}}
                <div class="js-pagination">
                    {{ $records->links() }}
                </div>
            </div>

            {{-- LISTA --}}
            <div class="js-list">
                @forelse ($records as $record)
                    <x-book-item :book="$record" />
                @empty
                    <p>No hay libros con esos filtros.</p>
                @endforelse
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
