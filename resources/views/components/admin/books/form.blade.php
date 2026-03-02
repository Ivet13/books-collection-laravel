<form class="js-crud-form" method="POST" action="{{ route('admin.authors.store') }}"
    data-store-url="{{ route('admin.authors.store') }}" data-show-url-base="{{ url('/admin/authors') }}"
    data-update-url-base="{{ url('/admin/authors') }}" data-destroy-url-base="{{ url('/admin/authors') }}">
    @csrf

    <input type="hidden" id="id" name="id" value="{{ $author->id ?? '' }}">
    <input type="hidden" id="method" name="_method" value="POST">

    <div class="buttons">
        <button type="submit" title="Guardar">
            <x-icons.content-save />
        </button>
        <button type="button" class="js-crud-reset" title="Limpiar">
            <x-icons.broom />
        </button>

        <button type="button" class="js-crud-delete {{ empty($author?->id) ? 'hidden' : '' }}">
            <x-icons.delete /></button>

    </div>

    <div class="js-errors" style="color:red; margin:8px 0;"></div>

    <div class="form-fields">
        <div>
            <label for="name">Nombre</label>

            <input name="name" value="{{ old('name', $author->name ?? '') }}">
        </div>

        <div style="width:100%;">
            <label for="bio">Bio</label>
            <textarea name="bio">{{ old('bio', $author->bio ?? '') }}</textarea>

        </div>
    </div>

    {{-- meta info (opcional) --}}
    <section style="margin-top:12px;">
        <strong>Libros:</strong>
        <div id="meta-books">—</div>
    </section>
</form>

<style>
    .hidden {
        display: none;
    }
</style>
