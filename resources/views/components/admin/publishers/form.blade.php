<form class="js-crud-form" data-store-url="{{ route('admin.publishers.store') }}"
    data-show-url-base="{{ url('/admin/publishers') }}" data-update-url-base="{{ url('/admin/publishers') }}"
    data-destroy-url-base="{{ url('/admin/publishers') }}" data-view-url-base="{{ url('/admin/publishers') }}">
    @csrf

    <input type="hidden" id="id" name="id" value="{{ $publisher->id ?? '' }}">
    <input type="hidden" id="slug" name="slug" value="{{ $publisher->sitemap->slug ?? '' }}">

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

        <button type="button" class="js-crud-delete {{ empty($publisher?->id) ? 'hidden' : '' }}">
            <x-icons.delete /></button>

    </div>

    <div class="js-errors" style="color:red; margin:8px 0;"></div>

    <div class="form-fields">

        <x-admin.publishers.tabs :publisher="$publisher ?? ''" />

    </div>
</form>
