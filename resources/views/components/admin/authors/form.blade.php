<form class="js-crud-form" data-store-url="{{ route('admin.authors.store') }}"
    data-show-url-base="{{ url('/admin/authors') }}" data-update-url-base="{{ url('/admin/authors') }}"
    data-destroy-url-base="{{ url('/admin/authors') }}" data-view-url-base="{{ url('/customer/authors') }}">
    @csrf

    <input type="hidden" id="id" name="id" value="{{ $author->id ?? '' }}">
    <input type="hidden" id="slug" name="slug" value="{{ $author->slug ?? '' }}">

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

    </div>

    <div class="js-errors" style="color:red; margin:8px 0;"></div>

    <div class="form-fields">

        <?php /* 
        <x-admin.authors.tabs />
        <?php */
        ?>

        <x-admin.authors.tabs-new />


    </div>
</form>
