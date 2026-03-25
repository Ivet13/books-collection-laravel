<form class="js-filter-form" data-url-base="{{ route('admin.faqs.index') }}">
    <button type="submit" title="Aplicar filtros" class="js-filter-submit">
        <x-icons.filter-menu />
    </button>

    <input type="text" name="title" placeholder="Buscar por título del faq" value="{{ request('title') }}">
    <input type="text" name="description" placeholder="Buscar por descripción del faq"
        value="{{ request('description') }}">


    <button type="button" class="js-filter-reset">Reset</button>

</form>
