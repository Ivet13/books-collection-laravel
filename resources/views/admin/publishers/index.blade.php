@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="publishers-page">
        <aside class="publishers-left">
            @include('admin.publishers.partials.filter')
            @include('admin.publishers.partials.list', ['records' => $records])
        </aside>

        <main class="publishers-right">
            <form class="js-publisher-form" method="POST" action="{{ route('admin.publishers.store') }}"
                data-store-url="{{ route('admin.publishers.store') }}" data-base-url="{{ url('/admin/publishers') }}">
                @csrf

                <input type="hidden" id="publisher_id" value="">
                <input type="hidden" id="method" value="POST">

                <div class="buttons">
                    <button type="submit" title="Guardar"><x-icon.content-save /></button>
                    <button type="button" class="js-publisher-reset" title="Limpiar"><x-icon.broom /></button>
                    <button type="button" class="delete-btn" style="display:none;"
                        title="Eliminar"><x-icon.delete /></button>
                </div>

                <div class="js-errors" style="color:red; margin: 8px 0;"></div>

                <div class="form-fields">
                    <div>
                        <label for="name">Nombre</label>
                        <input type="text" name="name" id="name" placeholder="Planeta">
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
