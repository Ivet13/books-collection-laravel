<form class="js-filter-form" data-url-base="{{ route('admin.publishers.index') }}">
    <button type="submit" title="Aplicar filtros" class="js-filter-submit">
        <x-icons.filter-menu />
    </button>

    <input type="text" name="name" placeholder="Buscar por nombre del autor" value="{{ request('name') }}">
    <input type="text" name="bio" placeholder="Buscar por biografía del autor" value="{{ request('bio') }}">


    <button type="button" class="js-filter-reset">Reset</button>

</form>
