<form class="js-crud-form" data-store-url="{{ route('admin.books.store') }}" data-show-url-base="{{ url('/admin/books') }}"
    data-update-url-base="{{ url('/admin/books') }}" data-destroy-url-base="{{ url('/admin/books') }}"
    data-view-url-base="{{ url('/admin/books') }}">
    @csrf

    <input type="hidden" id="id" name="id" value="{{ $book->id ?? '' }}">
    <input type="hidden" id="slug" name="slug" value="{{ $book->sitemap->slug ?? '' }}">

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

        <button type="button" class="js-crud-delete {{ empty($book?->id) ? 'hidden' : '' }}">
            <x-icons.delete /></button>

    </div>

    <div class="js-errors" style="color:red; margin:8px 0;"></div>

    <div class="form-fields">

        <x-admin.books.tabs :book="$book ?? ''" />

    </div>
</form>
