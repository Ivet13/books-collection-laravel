<form class="js-crud-form" data-store-url="{{ route('admin.faqs.store') }}" data-show-url-base="{{ url('/admin/faqs') }}"
    data-update-url-base="{{ url('/admin/faqs') }}" data-destroy-url-base="{{ url('/admin/faqs') }}"
    data-view-url-base="{{ url('/admin/faqs') }}">
    @csrf

    <input type="hidden" id="id" name="id" value="{{ $faq->id ?? '' }}">

    <div class="buttons">
        <button type="button" class="js-crud-save" title="Guardar">
            <x-icons.content-save />
        </button>
        <button type="button" class="js-crud-reset" title="Limpiar">
            <x-icons.broom />
        </button>
        <button type="button" class="js-crud-delete {{ empty($faq?->id) ? 'hidden' : '' }}">
            <x-icons.delete />
        </button>
    </div>

    <div class="js-errors" style="color:red; margin:8px 0;"></div>

    <div class="form-fields">

        <x-admin.faqs.tabs :faq="$faq ?? ''" />

    </div>
</form>
