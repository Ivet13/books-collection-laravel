@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="genres-page">
        <aside class="genres-left">
            @include('admin.genres.partials.filter')
            @include('admin.genres.partials.list', ['records' => $records])
        </aside>

        <main class="genres-right">
            <form class="js-genre-form" method="POST" action="{{ route('admin.genres.store') }}"
                data-store-url="{{ route('admin.genres.store') }}" data-base-url="{{ url('/admin/genres') }}">
                @csrf

                <input type="hidden" id="genre_id" value="">
                <input type="hidden" id="method" value="POST">

                <div class="buttons">
                    <button type="submit" title="Guardar"><x-icon.content-save /></button>
                    <button type="button" class="js-genre-reset" title="Limpiar"><x-icon.broom /></button>
                    <button type="button" class="delete-btn" style="display:none;"
                        title="Eliminar"><x-icon.delete /></button>
                </div>

                <div class="js-errors" style="color:red; margin: 8px 0;"></div>

                <div class="form-fields">
                    <div>
                        <label for="name">Nombre</label>
                        <input type="text" name="name" id="name" placeholder="Fantasía">
                    </div>

                    <div>
                        <label for="bio">Bio</label>
                        <textarea name="bio" id="bio" placeholder="..."></textarea>
                    </div>
                </div>
            </form>
        </main>
    </div>
@endsection
