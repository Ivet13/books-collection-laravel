<div class="js-crud-form" data-store-url="{{ route('admin.authors.store') }}"
    data-show-url-base="{{ url('/admin/authors') }}" data-update-url-base="{{ url('/admin/authors') }}"
    data-destroy-url-base="{{ url('/admin/authors') }}" data-view-url-base="{{ url('/admin/authors') }}">
    @csrf

    <input type="hidden" id="id" name="id" value="{{ $author->id ?? '' }}">

    <div class="buttons">
        <button type="button" class="js-crud-save" title="Guardar">
            <x-icons.content-save />
        </button>
        <button type="button" class="js-crud-reset" title="Limpiar">
            <x-icons.broom />
        </button>

        <button type="button" class="js-crud-view " title="Ver">
            <x-icons.eye />
        </button>

        <button type="button" class="js-crud-delete {{ empty($author?->id) ? 'hidden' : '' }}">
            <x-icons.delete /></button>

        <button type="button" class="js-crud-upload {{ empty($author?->id) ? 'hidden' : '' }}"
            data-entity-type="author" data-entity-id="{{ $author->id ?? '' }}">
            <x-icons.upload /></button>

    </div>

    <div class="js-errors" style="color:red; margin:8px 0;"></div>

    <div class="form-fields">

        <x-admin.authors.tabs :author="$author ?? ''" />

    </div>
</div>
