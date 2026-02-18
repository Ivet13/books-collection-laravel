@extends('layouts.app')

@section('content')
    {{-- CSRF para fetch --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="books-page">
        {{-- COLUMNA IZQUIERDA: lista + filtros + paginación --}}
        <aside class="books-left">

            <div class="table-menu">
                {{-- FILTROS --}}
                @include('admin.books.partials.filter') {{-- IMPORTANTE: que el <form> de filtros tenga una clase .js-filter-form --}}

                {{-- PAGINACIÓN --}}
                <div class="js-pagination">
                    {{ $records->links() }}
                </div>
            </div>

            {{-- LISTA --}}
            <div class="js-list">
                @forelse ($records as $record)
                    @include('admin.books.partials.item', ['record' => $record])
                @empty
                    <p>No hay libros con esos filtros.</p>
                @endforelse
            </div>
        </aside>

        {{-- COLUMNA DERECHA: panel (form + meta) --}}
        <main class="books-right">
            <section class="panel">

                <form class="js-book-form" method="POST" action="{{ route('admin.books.store') }}"
                    data-store-url="{{ route('admin.books.store') }}" data-show-url-base="{{ url('/admin/books') }}">
                    @csrf

                    {{-- Estado del formulario --}}
                    <input type="hidden" name="book_id" id="book_id" value="">
                    <input type="hidden" name="_method" id="method" value="POST"> {{-- lo usaremos para update/delete por AJAX --}}

                    <header class="form-options">
                        <div class="tabs">
                            <button type="button">GENERAL</button>
                        </div>

                        <div class="buttons">
                            <button type="submit" title="Guardar">
                                <x-icon.content-save />
                            </button>

                            <button type="reset" title="Limpiar">
                                <x-icon.broom />
                            </button>

                            <button type="button" title="Eliminar" class="delete-btn" style="display:none;">
                                <x-icon.delete />
                            </button>
                        </div>
                    </header>

                    {{-- ERRORES AJAX --}}
                    <div class="js-errors" style="color:red; margin: 8px 0;"></div>

                    <div class="form-fields">
                        <div>
                            <label for="title">Título</label>
                            <input type="text" name="title" id="title" placeholder="El imperio del vampiro">
                        </div>

                        <div>
                            <label for="isbn">ISBN</label>
                            <input type="text" name="isbn" id="isbn" placeholder="978...">
                        </div>

                        <div>
                            <label for="description">Sinopsis</label>
                            <textarea name="description" id="description" placeholder="..."></textarea>
                        </div>
                    </div>

                    {{-- INFO DEL LIBRO SELECCIONADO (NO inputs) --}}
                    <section class="book-meta" style="margin-top: 16px;">
                        <div>
                            <strong>Autores:</strong>
                            <div id="meta-authors">—</div>
                        </div>

                        <div style="margin-top: 8px;">
                            <strong>Editorial:</strong>
                            <span id="meta-publisher">—</span>
                        </div>

                        <div style="margin-top: 8px;">
                            <strong>Año publicación:</strong>
                            <span id="meta-year">—</span>
                        </div>

                        <div style="margin-top: 8px;">
                            <strong>Genres:</strong>
                            <div id="meta-genres">—</div>
                        </div>
                    </section>
                </form>
            </section>
        </main>
    </div>
@endsection

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
