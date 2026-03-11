<form class="js-filter-form" data-url-base="{{ route('admin.customers.index') }}">
    <button type="submit" title="Aplicar filtros" class="js-filter-submit">
        <x-icons.filter-menu />
    </button>

    <input type="text" name="name" placeholder="Buscar por nombre del libro" value="{{ request('name') }}">
    <input type="text" name="synopsis" placeholder="Buscar por sinopsis del libro" value="{{ request('synopsis') }}">


    <button type="button" class="js-filter-reset">Reset</button>

</form>
