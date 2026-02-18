@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="authors-page">
        <aside class="authors-left">
            @include('admin.authors.partials.filter')

            {{-- lista + paginación (en carga normal también) --}}
            @include('admin.authors.partials.list', ['records' => $records])
        </aside>

        <main class="authors-right">
            <form class="js-author-form" method="POST" action="{{ route('admin.authors.store') }}"
                data-store-url="{{ route('admin.authors.store') }}" data-show-url-base="{{ url('/admin/authors') }}">
                @csrf

                <input type="hidden" id="author_id" value="">
                <input type="hidden" id="method" value="POST">

                <div class="buttons">
                    <button type="submit" title="Guardar"><x-icon.content-save /></button>
                    <button type="button" class="js-author-reset" title="Limpiar"><x-icon.broom /></button>
                    <button type="button" class="delete-btn" style="display:none;"
                        title="Eliminar"><x-icon.delete /></button>
                </div>

                <div class="js-errors" style="color:red; margin:8px 0;"></div>

                <div class="form-fields">
                    <div>
                        <label for="name">Nombre</label>
                        <input id="name" name="name" type="text" placeholder="Ursula K. Le Guin">
                    </div>

                    <div style="width:100%;">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio" placeholder="..."></textarea>
                    </div>
                </div>

                {{-- meta info (opcional) --}}
                <section style="margin-top:12px;">
                    <strong>Libros:</strong>
                    <div id="meta-books">—</div>
                </section>
            </form>
        </main>
    </div>
@endsection
