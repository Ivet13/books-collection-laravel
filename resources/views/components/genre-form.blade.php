<form class="js-genre-form" method="POST" action="{{ route('admin.genres.store') }}"
    data-store-url="{{ route('admin.genres.store') }}" data-show-url-base="{{ url('/admin/genres') }}">
    @csrf

    <input type="hidden" id="genre_id" value="">
    <input type="hidden" id="method" value="POST">

    <div class="buttons">
        <button type="submit" title="Guardar">
            <x-icon.content-save />
        </button>
        <button type="button" class="js-genre-reset" title="Limpiar">
            <x-icon.broom />
        </button>
        <button type="button" class="delete-btn" style="display:none;" title="Eliminar">
            <x-icon.delete />
        </button>
    </div>

    <div class="js-errors" style="color:red; margin:8px 0;"></div>

    <div class="form-fields">
        <div>
            <label for="name">Nombre</label>
            <input id="name" name="name" type="text" placeholder="Science Fiction">
        </div>

        <div style="width:100%;">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" placeholder="..."></textarea>
        </div>
    </div>
</form>
